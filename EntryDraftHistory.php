<?php include "Header.php";
If ($lang == "fr"){include 'LanguageFR-League.php';}else{include 'LanguageEN-League.php';}
$Title = (string)"";
If (file_exists($DatabaseFile) == false){
	Goto STHSErrorEntryDraftHistory;
}else{try{
	$LeagueName = (string)"";
		
	$db = new SQLite3($DatabaseFile);
	
	$Query = "Select Name, NumbersOfTeam from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	
	$Year = (integer)0;	
	if(isset($_GET['Year'])){$Year = filter_var($_GET['Year'], FILTER_SANITIZE_NUMBER_INT);} 
	
	If ($Year > 0){
		$Query = "SELECT MainTable.* FROM (SELECT PlayerInfo.Number, PlayerInfo.Name, 'False' AS PosG, DraftYear, DraftOverallPick, DraftOriginalTeam FROM PlayerInfo WHERE DraftYear = " . $Year . " UNION ALL SELECT GoalerInfo.Number, GoalerInfo.Name, 'True' AS PosG, DraftYear, DraftOverallPick, DraftOriginalTeam FROM GoalerInfo WHERE DraftYear = " . $Year . ") AS MainTable UNION ALL SELECT 0 AS Number, Prospects.Name, 'False' AS PosG, Prospects.Year As DraftYear, Prospects.OverallPick As DraftOverallPick, TeamProInfo.Name AsDraftOriginalTeam FROM Prospects LEFT JOIN TeamProInfo ON Prospects.TeamNumber = TeamProInfo.Number Where DraftYear = " . $Year . " ORDER BY DraftYear, DraftOverallPick";
		$EntryDraftHistory = $db->query($Query);
		echo "<title>" . $LeagueName . " - " . $EntryDraftLang['HistoryDraftYear'] . $Year . "</title>";		
	}else{
		$EntryDraftHistory = Null;
		echo "<title>" . $LeagueName . " - " . $EntryDraftLang['HistoryDraftYear'] . "</title>";
		echo "<style>.STHSEntryDraftHistory_MainDiv{display:none}</style>";		
	}
	
} catch (Exception $e) {
STHSErrorEntryDraftHistory:
	$LeagueName = $DatabaseNotFound;
	$EntryDraftHistory = Null;
	echo "<title>" . $DatabaseNotFound ."</title>";
	echo "<style>.STHSEntryDraftHistory_MainDiv{display:none}</style>";
}}?>
</head><body>
<?php include "Menu.php";?>

<div class="STHSEntryDraftHistory_MainDiv" style="width:99%;margin:auto;">
<?php echo "<br /><div class=\"STHSDivInformationMessage\">" . $EntryDraftLang['HistoryDraftYearNote'] .  "</div><br>";
echo "<h1>" . $EntryDraftLang['HistoryDraftYear'] . $Year . "</h1>"; ?>
<table class="STHSEntryDraft_MainTable">
<thead><tr>
<th class="STHSEntryDraf_Rank"><?php echo $EntryDraftLang['Rank'];?></th>
<th class="STHSEntryDraft_Pick"><?php echo $EntryDraftLang['Pick'];?></th>
<th class="STHSEntryDraft_Team"><?php echo $EntryDraftLang['OriginalTeam'];?></th>
</tr></thead><tbody>
<?php
if (empty($EntryDraftHistory) == false){while ($row = $EntryDraftHistory ->fetchArray()) {
	echo "<tr><td>" . $row['DraftOverallPick'] . "</td>";
	if ($row['Number'] > 0){
		if ($row['PosG']== "True"){echo "<td><a href=\"GoalieReport.php?Goalie=";}else{echo "<td><a href=\"PlayerReport.php?Player=";}
		echo $row['Number'] . "\">" . $row['Name'] . "</a></td>";
	}else{
		echo "<td>" . $row['Name'] . "</td>";
	}
	echo "<td>" . $row['DraftOriginalTeam'] . "</td></tr>\n";
}}
?>
</tbody></table>

<br>
</div>

<?php include "Footer.php";?>
