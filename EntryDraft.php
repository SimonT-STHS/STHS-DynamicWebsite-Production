<?php include "Header.php";
If ($lang == "fr"){include 'LanguageFR-League.php';}else{include 'LanguageEN-League.php';}
$Title = (string)"";
If (file_exists($DatabaseFile) == false){
	Goto STHSErrorEntryDraft;
}else{try{
	$LeagueName = (string)"";
		
	$db = new SQLite3($DatabaseFile);
	
	$Query = "Select Name, NumbersOfTeam from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	
	$Query = "SELECT EntryDraft.*, TeamProInfoCurrent.Name AS CurrentTeamName, TeamProInfoCurrent.TeamThemeID As CurrentTeamThemeID, TeamProInfoOriginal.Name As OriginalTeamName, TeamProInfoOriginal.TeamThemeID As OriginalTeamThemeID FROM (EntryDraft LEFT JOIN TeamProInfo AS TeamProInfoCurrent ON EntryDraft.CurrentTeam = TeamProInfoCurrent.Number) LEFT JOIN TeamProInfo AS TeamProInfoOriginal ON EntryDraft.OriginalTeam = TeamProInfoOriginal.Number";
	$EntryDraft = $db->query($Query);
	
	$Query = "SELECT EntryDraftProspectAvailable.* FROM EntryDraftProspectAvailable ORDER BY ProspectName";
	$EntryDraftProspectAvailable = $db->query($Query);

	echo "<title>" . $LeagueName . " - " . $EntryDraftLang['EntryDraft'] . "</title>";
} catch (Exception $e) {
STHSErrorEntryDraft:
	$LeagueName = $DatabaseNotFound;
	$EntryDraft = Null;
	$EntryDraftProspectAvailable = Null;
	echo "<title>" . $DatabaseNotFound ."</title>";
}}?>
</head><body>
<?php include "Menu.php";?>

<div style="width:99%;margin:auto;">
<?php echo "<h1>" . $EntryDraftLang['EntryDraft']. "</h1>"; ?>
<table class="STHSEntryDraft_MainTable">
<thead><tr>
<th class="STHSEntryDraft_Rank"><?php echo $EntryDraftLang['Rank'];?></th>
<th class="STHSEntryDraft_Team"><?php echo $EntryDraftLang['Team'];?></th>
<th class="STHSEntryDraft_Pick"><?php echo $EntryDraftLang['Pick'];?></th>
</tr></thead><tbody>
<?php
$LoopCount =0;
$Round =0;
if (empty($EntryDraft) == false){while ($row = $EntryDraft ->fetchArray()) {
	If ($LoopCount % $LeagueGeneral['NumbersOfTeam'] == 0){
		$Round +=1;
		echo "<tr><td colspan=\"3\" class=\"STHSCenter\"><b> " . $EntryDraftLang['Round'] . " #" . $Round . "</b></td></tr>";
	}
	$LoopCount +=1;
	
	If ($row['OriginalTeam'] == $row['CurrentTeam']){
		echo "<tr><td>" . $row['PickNumber'] . "</td><td>";
		If ($row['CurrentTeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $row['CurrentTeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPEntryDraftTeamImage\" />";}
		echo  $row['CurrentTeamName'] . "</td><td>" . $row['ProspectPick'];
	}else{
		echo "<tr><td>" . $row['PickNumber'] . "</td><td>";
		If ($row['CurrentTeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $row['CurrentTeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPEntryDraftTeamImage\" />";}
		echo  $row['CurrentTeamName'];
		echo "   <img src=\"" . $ImagesCDNPath . "/images/switch.png\">(";
		If ($row['OriginalTeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $row['OriginalTeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPEntryDraftTeamImage\" />";}
		echo  $row['OriginalTeamName'] . ")</td><td>" . $row['ProspectPick'];
	}
	echo "</td></tr>";	

}}
?>
</tbody></table>

<br />
<h1 class="STHSEntryDraft_AvailableProspect"><?php echo $EntryDraftLang['AvailablesProspect'];?></h1>
<?php
if (empty($EntryDraftProspectAvailable) == false){while ($row = $EntryDraftProspectAvailable ->fetchArray()) { 
	echo $row['ProspectName'] . "<br />\n"; 
}}
?>

</div>

<?php include "Footer.php";?>
