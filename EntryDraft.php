<!DOCTYPE html>
<?php include "Header.php";?>
<?php
$Title = (string)"";
$Active = 4; /* Show Webpage Top Menu */
If (file_exists($DatabaseFile) == false){
	$LeagueName = $DatabaseNotFound;
	$EntryDraft = Null;
	$EntryDraftProspectAvailable = Null;
	echo "<title>" . $DatabaseNotFound ."</title>";
}else{
	$LeagueName = (string)"";
		
	$db = new SQLite3($DatabaseFile);
	
	$Query = "Select Name, NumbersOfTeam from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	
	$Query = "SELECT EntryDraft.*, TeamProInfoCurrent.Name AS CurrentTeamName, TeamProInfoOriginal.Name As OriginalTeamName FROM (EntryDraft LEFT JOIN TeamProInfo AS TeamProInfoCurrent ON EntryDraft.CurrentTeam = TeamProInfoCurrent.Number) LEFT JOIN TeamProInfo AS TeamProInfoOriginal ON EntryDraft.OriginalTeam = TeamProInfoOriginal.Number";
	$EntryDraft = $db->query($Query);
	
	$Query = "SELECT EntryDraftProspectAvailable.* FROM EntryDraftProspectAvailable ORDER BY ProspectName";
	$EntryDraftProspectAvailable = $db->query($Query);

	echo "<title>" . $LeagueName . " - " . $EntryDraftLang['EntryDraft'] . "</title>";
}?>
</head><body>
<?php include "Menu.php";?>
<?php echo "<h1>" . $EntryDraftLang['EntryDraft']. "</h1>"; ?>


<div style="width:99%;margin:auto;">
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
		echo "<tr><td>" . $row['PickNumber']. "</td><td>" . $row['CurrentTeamName'] . "</td><td>" . $row['ProspectPick'] . "</td></tr>";
	}else{
		echo "<tr><td>" . $row['PickNumber']. "</td><td>" . $row['CurrentTeamName'] . "(" . $row['OriginalTeamName'] . ")</td><td>" . $row['ProspectPick'] . "</td></tr>";
	}
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
