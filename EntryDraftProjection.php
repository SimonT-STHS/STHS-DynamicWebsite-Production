<!DOCTYPE html>
<?php include "Header.php";?>
<?php
$Title = (string)"";
$Active = 4; /* Show Webpage Top Menu */
If (file_exists($DatabaseFile) == false){
	$LeagueName = $DatabaseNotFound;
	$EntryDraft = Null;
	echo "<title>" . $DatabaseNotFound ."</title>";
}else{
	$LeagueName = (string)"";
		
	$db = new SQLite3($DatabaseFile);
	
	$Query = "Select Name, LeagueYear, NumbersOfTeam from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	
	$Query = "SELECT MainTable.*, DraftPick.*, TeamProInfo.Name AS CurrentTeam FROM ((SELECT TeamProInfo.Number, TeamProInfo.Name AS OriginalTeam, RankingOrder.TeamOrder FROM TeamProInfo LEFT JOIN RankingOrder ON TeamProInfo.Number = RankingOrder.TeamProNumber WHERE RankingOrder.Type=0 ORDER BY TeamOrder DESC)  AS MainTable LEFT JOIN DraftPick ON MainTable.Number = DraftPick.FromTeam) INNER JOIN TeamProInfo ON DraftPick.TeamNumber = TeamProInfo.Number WHERE DraftPick.Year = " . ($LeagueGeneral['LeagueYear'] + 1) . " ORDER BY DraftPick.Round, MainTable.TeamOrder DESC";
	$EntryDraft = $db->query($Query);
	
	echo "<title>" . $LeagueName . " - " . $EntryDraftLang['EntryDraft'] . "</title>";
}?>
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
		echo "<tr><td>" . $Count . "</td><td>" . $row['CurrentTeam'];
	}else{
		echo "<tr><td>" . $Count . "</td><td>" . $row['CurrentTeam'] . " (" . $row['OriginalTeam'] . ")";
	}
	If ($row['ConditionalTrade'] != ""){echo " (CON " . $row['ConditionalTrade'] . ")";}
	echo "</td></tr>";
}}
?>
</tbody></table>

</div>

<?php include "Footer.php";?>
