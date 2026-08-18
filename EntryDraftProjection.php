<?php include "Header.php";
If ($lang == "fr"){include 'LanguageFR-League.php';}else{include 'LanguageEN-League.php';}
$Title = (string)"";
If (file_exists($DatabaseFile) == false){
	Goto STHSErrorEntryDraftProjection;
}else{try{
	$LeagueName = (string)"";
		
	$db = new SQLite3($DatabaseFile);
	
	$Query = "Select Name, LeagueYearOutput, NumbersOfTeam, PlayOffStarted, OffSeason, EntryDraftGoldPlan from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	if ($LeagueGeneral['PlayOffStarted'] == "False" and $LeagueGeneral['OffSeason'] == "False"){
		$Query = "SELECT MainTable.*, DraftPick.*, TeamProInfo.Name AS CurrentTeam, TeamProInfo.TeamThemeID As CurrentTeamThemeID FROM ((SELECT TeamProInfo.Number, TeamProInfo.Name AS OriginalTeam, TeamProInfo.TeamThemeID As OriginalTeamThemeID, RankingOrder.TeamOrder FROM TeamProInfo LEFT JOIN RankingOrder ON TeamProInfo.Number = RankingOrder.TeamProNumber WHERE RankingOrder.Type=0 ORDER BY TeamOrder DESC)  AS MainTable LEFT JOIN DraftPick ON MainTable.Number = DraftPick.FromTeam) INNER JOIN TeamProInfo ON DraftPick.TeamNumber = TeamProInfo.Number WHERE DraftPick.Year = " . ($LeagueGeneral['LeagueYearOutput'] + 1) . " ORDER BY DraftPick.Round, MainTable.TeamOrder DESC";
	}elseif($LeagueGeneral['OffSeason'] == "True"){
		$Query = "";
	}else{
		$Query = "SELECT MainTable.*, DraftPick.*, TeamProInfo.Name AS CurrentTeam, TeamProInfo.TeamThemeID As CurrentTeamThemeID FROM ((SELECT TeamProInfo.Number, TeamProInfo.Name AS OriginalTeam, TeamProInfo.TeamThemeID As OriginalTeamThemeID, TeamProInfo.PlayoffEliminated, TeamProInfo.DidNotMakePlayoff, RankingOrder.TeamOrder FROM TeamProInfo LEFT JOIN RankingOrder ON TeamProInfo.Number = RankingOrder.TeamProNumber WHERE RankingOrder.Type=0) AS MainTable LEFT JOIN DraftPick ON MainTable.Number = DraftPick.FromTeam) INNER JOIN TeamProInfo ON DraftPick.TeamNumber = TeamProInfo.Number WHERE DraftPick.Year = " . ($LeagueGeneral['LeagueYearOutput'] + 1) . " ORDER BY DraftPick.Round, MainTable.DidNotMakePlayoff DESC, MainTable.PlayoffEliminated DESC,MainTable.TeamOrder DESC";
	}
	$EntryDraft = $db->query($Query);
	
	if ($LeagueGeneral['EntryDraftGoldPlan'] == "True"){
		$Query = "SELECT  TeamProInfo.TeamThemeID, TeamProInfo.Name, TeamProInfo.GoldDraftPoint, RankingOrder.Type, RankingOrder.TeamOrder, RankingOrder.TeamProNumber FROM TeamProInfo INNER JOIN RankingOrder ON TeamProInfo.Number = RankingOrder.TeamProNumber WHERE (RankingOrder.Type=0 AND GoldDraftPoint > 0 ) ORDER BY GoldDraftPoint DESC, RankingOrder.TeamOrder DESC";
		$GoldDraftPoint = $db->query($Query);
	}else{
		$GoldDraftPoint = Null;
	}

	echo "<title>" . $LeagueName . " - " . $EntryDraftLang['EntryDraft'] . "</title>";
} catch (Exception $e) {
STHSErrorEntryDraftProjection:
	$LeagueName = $DatabaseNotFound;
	$EntryDraft = Null;
	echo "<title>" . $DatabaseNotFound ."</title>";
	echo "<style>.STHSEntryDraftProjection_MainDiv{display:none}</style>";
}}?>
</head><body>
<?php include "Menu.php";?>
<div class="STHSEntryDraftProjection_MainDiv"  style="width:99%;margin:auto;">
<?php echo "<h1>" . $EntryDraftLang['EntryDraftProjection']. "</h1>"; ?>
<?php if ($LeagueGeneral['PlayOffStarted'] == "True" OR $LeagueGeneral['OffSeason'] == "True"){echo "<div class=\"STHSDivInformationMessage\">" . $EntryDraftLang['ProjectionNote'] .  "</div><br>";}?>
<table class="STHSEntryDraft_MainTable">
<thead><tr>
<th class="STHSEntryDraft_Rank"><?php echo $EntryDraftLang['Rank'];?></th>
<th class="STHSEntryDraft_Team"><?php echo $EntryDraftLang['Team'];?></th>
</tr></thead><tbody>
<?php
$LoopCount =0;
$Round =0;
$Count =0;
if (empty($EntryDraft) == false){while ($row = $EntryDraft ->fetchArray()) {
	If ($LoopCount % $LeagueGeneral['NumbersOfTeam'] == 0){
		$Round +=1;
		echo "<tr><td colspan=\"3\" class=\"STHSCenter\"><b> " . $EntryDraftLang['Round'] . " #" . $Round . "</b></td></tr>";
	}
	$LoopCount +=1;
	$Count +=1;
	If ($row['CurrentTeam'] == $row['OriginalTeam']){
		echo "<tr><td>" . $Count . "</td><td>";
		If ($row['CurrentTeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $row['CurrentTeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPEntryDraftTeamImage\">";}
		echo  $row['CurrentTeam'];
	}else{
		echo "<tr><td>" . $Count . "</td><td>";
		If ($row['CurrentTeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $row['CurrentTeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPEntryDraftTeamImage\">";}
		echo  $row['CurrentTeam'];
		echo "   <img src=\"" . $ImagesCDNPath . "/images/switch.png\">(";
		If ($row['OriginalTeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $row['OriginalTeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPEntryDraftTeamImage\">";}
		echo  $row['OriginalTeam'] . ")";
	}
	If ($row['ConditionalTrade'] != ""){echo " (CON " . $row['ConditionalTrade'] . ")";}
	echo "</td></tr>\n";
}}
?>
</tbody></table>

<?php
if ($LeagueGeneral['EntryDraftGoldPlan'] == "True"){
	if (empty($GoldDraftPoint) == false){
		$firstRowProcess = false;
	
		while ($row = $GoldDraftPoint ->fetchArray()) {
			If ($firstRowProcess == false){
				echo "<br ><hr><h1>" . $EntryDraftLang['GoldRanking']. "</h1>";
				echo "<table class=\"STHSEntryDraft_MainTable\">\n";
				echo "<thead><tr>\n";
				echo "<th class=\"STHSEntryDraft_Team\">" . $EntryDraftLang['GoldTeam'] . "</th>\n";
				echo "<th class=\"STHSEntryDraft_GoldPoint\">" . $EntryDraftLang['GoldPoints'] . "</th>\n";
				echo "</tr></thead><tbody>\n";
			}
			
			echo "<tr><td>";
			If ($row['CurrentTeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $row['CurrentTeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPEntryDraftTeamImage\">";}
			echo  $row['Name'] . "</td><td>" . $row['GoldDraftPoint'];
			echo "</td></tr>\n";
		}
		If ($firstRowProcess == true){echo "</tbody></table>";}
	}
}
?>

</div>

<?php include "Footer.php";?>
