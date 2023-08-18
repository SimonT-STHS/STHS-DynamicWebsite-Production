<?php include "Header.php";
If ($lang == "fr"){include 'LanguageFR-League.php';}else{include 'LanguageEN-League.php';}
$Title = (string)"";
If (file_exists($DatabaseFile) == false){
	Goto STHSErrorEntryDraftProjection;
}else{try{
	$LeagueName = (string)"";
		
	$db = new SQLite3($DatabaseFile);
	
	$Query = "Select Name, LeagueYearOutput, NumbersOfTeam, PlayOffStarted from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	
	if ($LeagueGeneral['PlayOffStarted'] == "False"){
		$Query = "SELECT MainTable.*, DraftPick.*, TeamProInfo.Name AS CurrentTeam, TeamProInfo.TeamThemeID As CurrentTeamThemeID FROM ((SELECT TeamProInfo.Number, TeamProInfo.Name AS OriginalTeam, TeamProInfo.TeamThemeID As OriginalTeamThemeID, RankingOrder.TeamOrder FROM TeamProInfo LEFT JOIN RankingOrder ON TeamProInfo.Number = RankingOrder.TeamProNumber WHERE RankingOrder.Type=0 ORDER BY TeamOrder DESC)  AS MainTable LEFT JOIN DraftPick ON MainTable.Number = DraftPick.FromTeam) INNER JOIN TeamProInfo ON DraftPick.TeamNumber = TeamProInfo.Number WHERE DraftPick.Year = " . ($LeagueGeneral['LeagueYearOutput'] + 1) . " ORDER BY DraftPick.Round, MainTable.TeamOrder DESC";
	}else{
		$Query = "SELECT MainTable.*, DraftPick.*, TeamProInfo.Name AS CurrentTeam, TeamProInfo.TeamThemeID As CurrentTeamThemeID FROM ((SELECT TeamProInfo.Number, TeamProInfo.Name AS OriginalTeam, TeamProInfo.TeamThemeID As OriginalTeamThemeID, TeamProInfo.PlayoffEliminated, TeamProInfo.DidNotMakePlayoff, RankingOrder.TeamOrder FROM TeamProInfo LEFT JOIN RankingOrder ON TeamProInfo.Number = RankingOrder.TeamProNumber WHERE RankingOrder.Type=0) AS MainTable LEFT JOIN DraftPick ON MainTable.Number = DraftPick.FromTeam) INNER JOIN TeamProInfo ON DraftPick.TeamNumber = TeamProInfo.Number WHERE DraftPick.Year = " . ($LeagueGeneral['LeagueYearOutput'] + 1) . " ORDER BY DraftPick.Round, MainTable.DidNotMakePlayoff DESC, MainTable.PlayoffEliminated DESC,MainTable.TeamOrder DESC";
	}
	$EntryDraft = $db->query($Query);

	echo "<title>" . $LeagueName . " - " . $EntryDraftLang['EntryDraft'] . "</title>";
} catch (Exception $e) {
STHSErrorEntryDraftProjection:
	$LeagueName = $DatabaseNotFound;
	$EntryDraft = Null;
	echo "<title>" . $DatabaseNotFound ."</title>";
}}?>
</head><body>
<?php include "Menu.php";?>
<?php echo "<h1>" . $EntryDraftLang['EntryDraftProjection']. "</h1>"; ?>


<div style="width:99%;margin:auto;">
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
		If ($row['CurrentTeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $row['CurrentTeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPEntryDraftTeamImage\" />";}
		echo  $row['CurrentTeam'];
	}else{
		echo "<tr><td>" . $Count . "</td><td>";
		If ($row['CurrentTeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $row['CurrentTeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPEntryDraftTeamImage\" />";}
		echo  $row['CurrentTeam'];
		echo "   <img src=\"" . $ImagesCDNPath . "/images/switch.png\">(";
		If ($row['OriginalTeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $row['OriginalTeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPEntryDraftTeamImage\" />";}
		echo  $row['OriginalTeam'] . ")";
	}
	If ($row['ConditionalTrade'] != ""){echo " (CON " . $row['ConditionalTrade'] . ")";}
	echo "</td></tr>";
}}
?>
</tbody></table>

</div>

<?php include "Footer.php";?>
