<!DOCTYPE html>
<?php include "Header.php";?>
<?php
$Team = (integer)-1; /* -1 All Team */
$Title = (string)"";
$Active = 5; /* Show Webpage Top Menu */
If (file_exists($DatabaseFile) == false){
	$LeagueName = $DatabaseNotFound;
	$CareerStatGoalie = Null;
	echo "<title>" . $DatabaseNotFound . "</title>";
	$Title = $DatabaseNotFound;
}else{
	$TypeText = (string)"Pro";$TitleType = $DynamicTitleLang['Pro'];
	$ACSQuery = (boolean)FALSE;/* The SQL Query must be Ascending Order and not Descending */
	$Playoff = (string)"False";
	$MaximumResult = (integer)0;
	$OrderByField = (string)"W";
	$OrderByFieldText = (string)"Win";
	$OrderByInput = (string)"";
	$TitleOverwrite = (string)"";
	$TeamName = (string)"";
	$Year = (integer)0;
	if(isset($_GET['Farm'])){$TypeText = "Farm";$TitleType = $DynamicTitleLang['Farm'];$Active = 3;}
	if(isset($_GET['ACS'])){$ACSQuery= TRUE;}
	if(isset($_GET['Playoff'])){$Playoff="True";}
	if(isset($_GET['Max'])){$MaximumResult = filter_var($_GET['Max'], FILTER_SANITIZE_NUMBER_INT);} 
	if(isset($_GET['Order'])){$OrderByInput = filter_var($_GET['Order'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH);} 
	if(isset($_GET['Title'])){$TitleOverwrite  = filter_var($_GET['Title'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH);} 
	if(isset($_GET['Year'])){$Year = filter_var($_GET['Year'], FILTER_SANITIZE_NUMBER_INT);} 
	if(isset($_GET['TeamName'])){$TeamName = filter_var($_GET['TeamName'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH);}
	$LeagueName = (string)"";
	
	$GoaliesStatPossibleOrderField = array(
	array("Name","Goalie Name"),
	array("GP","Games Played"),
	array("W","Wins"),
	array("L","Losses"),
	array("OTL","Overtime Losses"),
	array("PCT","Save Percentage"),
	array("GAA","Goals Against Average"),
	array("SecondPlay","Minutes Played"),
	array("Pim","Penalty Minutes"),
	array("Shootout","Shootout"),
	array("SA","Shots Against"),
	array("GA","Goals Against"),
	array("SARebound","Shots Against Rebound"),
	array("A","Assists"),
	array("EmptyNetGoal","Empty net Goals"),
	array("PenalityShotsShots","Penalty Shots Against"),
	array("PenalityShotsGoals","Penalty Shots Goals"),
	array("PenalityShotsPCT","Penalty Shots Save Percentage"),
	array("StartGoaler","Number of game goalies start as Start goalie"),
	array("BackupGoaler","Number of game goalies start as Backup goalie"),
	array("Star1","Number of time players was star #1 in a game"),
	array("Star2","Number of time players was star #2 in a game"),
	array("Star3","Number of time players was star #3 in a game"),
	);
	foreach ($GoaliesStatPossibleOrderField as $Value) {
		If (strtoupper($Value[0]) == strtoupper($OrderByInput)){
			$OrderByField = $Value[0];
			$OrderByFieldText = $Value[1];
			Break;
		}
	}
	
	$db = new SQLite3($DatabaseFile);
	$Query = "Select Name,PlayOffStarted from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	If (file_exists($CareerStatDatabaseFile) == true){ /* CareerStat */
		$CareerStatdb = new SQLite3($CareerStatDatabaseFile);
		$CareerStatdb->query("ATTACH DATABASE '".$DatabaseFile."' AS CurrentDB");
		
		If($MaximumResult == 0){If ($TeamName == ""){$Title = $DynamicTitleLang['CareerStat'] . $DynamicTitleLang['All'];}else{$Title = $DynamicTitleLang['CareerStat'] . $TeamName;}}else{$Title = $DynamicTitleLang['CareerStat'] .$DynamicTitleLang['Top'] . $MaximumResult . " ";}
		
		$Query = "SELECT MainTable.*, Goaler" . $TypeText . "Stat.*, ROUND((CAST((MainTable.SumofGA + Goaler" . $TypeText . "Stat.GA) AS REAL) / (  (MainTable.SumofSecondPlay +  Goaler" . $TypeText . "Stat.SecondPlay) / 60))*60,3) AS TotalGAA, ROUND((CAST((MainTable.SumofSA + Goaler" . $TypeText . "Stat.SA) - (MainTable.SumofGA + Goaler" . $TypeText . "Stat.GA) AS REAL) / (MainTable.SumofSA + Goaler" . $TypeText . "Stat.SA)),3) AS TotalPCT, ROUND((CAST((MainTable.SumofPenalityShotsShots + Goaler" . $TypeText . "Stat.PenalityShotsShots) - (MainTable.SumofPenalityShotsGoals + Goaler" . $TypeText . "Stat.PenalityShotsGoals) AS REAL) / (MainTable.SumofPenalityShotsShots + Goaler" . $TypeText . "Stat.PenalityShotsShots)),3) AS TotalPenalityShotsPCT FROM ( SELECT Name AS SumOfName, UniqueID, Sum(Goaler" . $TypeText . "StatCareer.GP) AS SumOfGP, Sum(Goaler" . $TypeText . "StatCareer.SecondPlay) AS SumOfSecondPlay, Sum(Goaler" . $TypeText . "StatCareer.W) AS SumOfW, Sum(Goaler" . $TypeText . "StatCareer.L) AS SumOfL, Sum(Goaler" . $TypeText . "StatCareer.OTL) AS SumOfOTL, Sum(Goaler" . $TypeText . "StatCareer.Shootout) AS SumOfShootout, Sum(Goaler" . $TypeText . "StatCareer.GA) AS SumOfGA, Sum(Goaler" . $TypeText . "StatCareer.SA) AS SumOfSA, Sum(Goaler" . $TypeText . "StatCareer.SARebound) AS SumOfSARebound, Sum(Goaler" . $TypeText . "StatCareer.Pim) AS SumOfPim, Sum(Goaler" . $TypeText . "StatCareer.A) AS SumOfA, Sum(Goaler" . $TypeText . "StatCareer.PenalityShotsShots) AS SumOfPenalityShotsShots, Sum(Goaler" . $TypeText . "StatCareer.PenalityShotsGoals) AS SumOfPenalityShotsGoals, Sum(Goaler" . $TypeText . "StatCareer.StartGoaler) AS SumOfStartGoaler, Sum(Goaler" . $TypeText . "StatCareer.BackupGoaler) AS SumOfBackupGoaler, Sum(Goaler" . $TypeText . "StatCareer.EmptyNetGoal) AS SumOfEmptyNetGoal, Sum(Goaler" . $TypeText . "StatCareer.Star1) AS SumOfStar1, Sum(Goaler" . $TypeText . "StatCareer.Star2) AS SumOfStar2, Sum(Goaler" . $TypeText . "StatCareer.Star3) AS SumOfStar3, ROUND((CAST(Sum(Goaler" . $TypeText . "StatCareer.GA) AS REAL) / (Sum(Goaler" . $TypeText . "StatCareer.SecondPlay) / 60))*60,3) AS SumOfGAA, ROUND((CAST(Sum(Goaler" . $TypeText . "StatCareer.SA) - Sum(Goaler" . $TypeText . "StatCareer.GA) AS REAL) / (Sum(Goaler" . $TypeText . "StatCareer.SA))),3) AS SumOfPCT, ROUND((CAST(Sum(Goaler" . $TypeText . "StatCareer.PenalityShotsShots) - Sum(Goaler" . $TypeText . "StatCareer.PenalityShotsGoals) AS REAL) / (Sum(Goaler" . $TypeText . "StatCareer.PenalityShotsShots))),3) AS SumOfPenalityShotsPCT FROM Goaler" . $TypeText . "StatCareer WHERE Playoff = \"" . $Playoff . "\"";

		If($Year > 0){$Query = $Query . " AND YEAR = \"" . $Year . "\"";}
		If($TeamName != ""){$Query = $Query . " AND TeamName = \"" . $TeamName . "\"";}
		
		If($Year > 0 OR $LeagueGeneral['PlayOffStarted'] != $Playoff OR $TeamName != ""){
			$Query = $Query . " GROUP BY Goaler" . $TypeText . "StatCareer.Name) AS MainTable LEFT JOIN Goaler" . $TypeText . "Stat ON MainTable.SumOfName = Goaler" . $TypeText . "Stat.Name ORDER BY (MainTable.SumOf".$OrderByField.") ";
		}elseif($OrderByField == "GAA" OR $OrderByField == "PCT" OR $OrderByField == "PenalityShotsPCT"){
			$Query = $Query . " GROUP BY Goaler" . $TypeText . "StatCareer.Name) AS MainTable LEFT JOIN Goaler" . $TypeText . "Stat ON MainTable.SumOfName = Goaler" . $TypeText . "Stat.Name ORDER BY Total".$OrderByField." ";
		}else{
			$Query = $Query . " GROUP BY Goaler" . $TypeText . "StatCareer.Name) AS MainTable LEFT JOIN Goaler" . $TypeText . "Stat ON MainTable.SumOfName = Goaler" . $TypeText . "Stat.Name ORDER BY (MainTable.SumOf".$OrderByField." + IfNull(Goaler" . $TypeText . "Stat.".$OrderByField.",0)) ";
		}
		
		$Title = $Title  . $DynamicTitleLang['GoaliesStat'] . $TitleType;
		
		If ($ACSQuery == TRUE){
			$Query = $Query . " ASC";
			$Title = $Title . $DynamicTitleLang['InAscendingOrderBy'] . $OrderByFieldText;
		}else{
			$Query = $Query . " DESC";
			$Title = $Title . $DynamicTitleLang['InDecendingOrderBy'] . $OrderByFieldText;
		}
		If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
		$CareerStatGoalie = $CareerStatdb->query($Query);
	}else{
		$CareerStatGoalie = Null;
		$Title = $CareeratabaseNotFound;
	}	

	/* OverWrite Title if information is get from PHP GET */
	if($TitleOverwrite <> ""){$Title = $TitleOverwrite;}	
	echo "<title>" . $LeagueName . " - " . $Title . "</title>";
}?>
</head><body>
<?php include "Menu.php";?>
<?php echo "<h1>" . $Title . "</h1>"; ?>
<script type="text/javascript">
$(function() {
  $.tablesorter.addWidget({ id: "numbering",format: function(table) {var c = table.config;$("tr:visible", table.tBodies[0]).each(function(i) {$(this).find('td').eq(0).text(i + 1);});}});
  $(".STHSPHPAllGoalieStat_Table").tablesorter({
    widgets: ['numbering', 'columnSelector', 'stickyHeaders', 'filter'],
    widgetOptions : {
      columnSelector_container : $('#tablesorter_ColumnSelector'),
      columnSelector_layout : '<label><input type="checkbox">{name}</label>',
      columnSelector_name  : 'title',
      columnSelector_mediaquery: true,
      columnSelector_mediaqueryName: 'Automatic',
      columnSelector_mediaqueryState: true,
      columnSelector_mediaqueryHidden: true,
      columnSelector_breakpoints : [ '20em', '40em', '60em', '80em', '90em', '95em' ],
	  filter_columnFilters: true,
      filter_placeholder: { search : '<?php echo $TableSorterLang['Search'];?>' },
	  filter_searchDelay : 500,	  
      filter_reset: '.tablesorter_Reset'	 
    }
  });
});
</script>

<div style="width:99%;margin:auto;">

<div class="tablesorter_ColumnSelectorWrapper">
    <input id="tablesorter_colSelect1" type="checkbox" class="hidden">
    <label class="tablesorter_ColumnSelectorButton" for="tablesorter_colSelect1"><?php echo $TableSorterLang['ShoworHideColumn'];?></label>
    <div id="tablesorter_ColumnSelector" class="tablesorter_ColumnSelector"></div>
	<?php include "FilterTip.php";?>
	</div>
</div>

<table class="tablesorter STHSPHPAllGoalieStat_Table"><thead><tr>
<th data-priority="3" title="Order Number" class="STHSW10 sorter-false">#</th>
<th data-priority="critical" title="Goalie Name" class="STHSW140Min"><?php echo $PlayersLang['GoalieName'];?></th>
<th data-priority="1" title="Games Played" class="STHSW25">GP</th>
<th data-priority="1" title="Wins" class="STHSW25">W</th>
<th data-priority="2" title="Losses" class="STHSW25">L</th>
<th data-priority="2" title="Overtime Losses" class="STHSW25">OTL</th>
<th data-priority="critical" title="Save Percentage" class="STHSW50">PCT</th>
<th data-priority="critical" title="Goals Against Average" class="STHSW50">GAA</th>
<th data-priority="3" title="Minutes Played" class="STHSW50">MP</th>
<th data-priority="5" title="Penalty Minutes" class="STHSW25">PIM</th>
<th data-priority="4" title="Shutouts" class="STHSW25">SO</th>
<th data-priority="3" title="Goals Against" class="STHSW25">GA</th>
<th data-priority="3" title="Shots Against" class="STHSW45">SA</th>
<th data-priority="4" title="Shots Against Rebound" class="STHSW45">SAR</th>
<th data-priority="5" title="Assists" class="STHSW25">A</th>
<th data-priority="5" title="Empty net Goals" class="STHSW25">EG</th>
<th data-priority="4" title="Penalty Shots Save %" class="STHSW50">PS %</th>
<th data-priority="5" title="Penalty Shots Against" class="STHSW25">PSA</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Number of game goalies start as Start goalie">ST</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Number of game goalies start as Backup goalie">BG</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Number of time players was star #1 in a game">S1</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Number of time players was star #2 in a game">S2</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Number of time players was star #3 in a game">S3</th>
</tr></thead><tbody>
<?php
$Order = 0;
if (empty($CareerStatGoalie) == false){while ($Row = $CareerStatGoalie ->fetchArray()) {
	$Order +=1;
	echo "<tr><td>" . $Order ."</td>";
	If ($Row['Number'] > 0){
		echo "<td><a href=\"GoalieReport.php?Goalie=" . $Row['Number'] . "\">" . $Row['Name'] . "</a></td>";
	}else{
		echo "<td><a href=\"CareerStatGoalieReport.php?Goalie=" . $Row['SumOfName'] . "\">" . $Row['SumOfName'] . "*</a></td>";
	}	
	
	If ($Year == 0 AND $LeagueGeneral['PlayOffStarted'] == $Playoff AND $TeamName == ""){
		echo "<td>" . ($Row['SumOfGP'] + $Row['GP']) . "</td>";
		echo "<td>" . ($Row['SumOfW'] + $Row['W']) . "</td>";
		echo "<td>" . ($Row['SumOfL'] + $Row['L']) . "</td>";
		echo "<td>" . ($Row['SumOfOTL'] + $Row['OTL']) . "</td>";
		echo "<td>" . number_Format($Row['TotalPCT'],3) . "</td>";
		echo "<td>" . number_Format($Row['TotalGAA'],2) . "</td>";
		echo "<td>";if ($Row <> Null){echo Floor(($Row['SumOfSecondPlay'] + $Row['SecondPlay']) /60);}; echo "</td>";
		echo "<td>" . ($Row['SumOfPim'] + $Row['Pim']) . "</td>";
		echo "<td>" . ($Row['SumOfShootout'] + $Row['Shootout']) . "</td>";
		echo "<td>" . ($Row['SumOfGA'] + $Row['GA']) . "</td>";
		echo "<td>" . ($Row['SumOfSA'] + $Row['SA']) . "</td>";
		echo "<td>" . ($Row['SumOfSARebound'] + $Row['SARebound']) . "</td>";
		echo "<td>" . ($Row['SumOfA'] + $Row['A']) . "</td>";
		echo "<td>" . ($Row['SumOfEmptyNetGoal'] + $Row['EmptyNetGoal']) . "</td>";			
		echo "<td>" . number_Format($Row['TotalPenalityShotsPCT'],3) . "</td>";
		echo "<td>" . ($Row['SumOfPenalityShotsShots'] + $Row['PenalityShotsShots']) . "</td>";
		echo "<td>" . ($Row['SumOfStartGoaler'] + $Row['StartGoaler']) . "</td>";
		echo "<td>" . ($Row['SumOfBackupGoaler'] + $Row['BackupGoaler']) . "</td>";
		echo "<td>" . ($Row['SumOfStar1'] + $Row['Star1']) . "</td>";
		echo "<td>" . ($Row['SumOfStar2'] + $Row['Star2']) . "</td>";
		echo "<td>" . ($Row['SumOfStar3'] + $Row['Star3']) . "</td>";
		echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
	}else{
		echo "<td>" . $Row['SumOfGP'] . "</td>";
		echo "<td>" . $Row['SumOfW'] . "</td>";
		echo "<td>" . $Row['SumOfL'] . "</td>";
		echo "<td>" . $Row['SumOfOTL'] . "</td>";
		echo "<td>" . number_Format($Row['SumOfPCT'],3) . "</td>";
		echo "<td>" . number_Format($Row['SumOfGAA'],2) . "</td>";
		echo "<td>";if ($Row <> Null){echo Floor($Row['SumOfSecondPlay']/60);}; echo "</td>";
		echo "<td>" . $Row['SumOfPim'] . "</td>";
		echo "<td>" . $Row['SumOfShootout'] . "</td>";
		echo "<td>" . $Row['SumOfGA'] . "</td>";
		echo "<td>" . $Row['SumOfSA'] . "</td>";
		echo "<td>" . $Row['SumOfSARebound'] . "</td>";
		echo "<td>" . $Row['SumOfA'] . "</td>";
		echo "<td>" . $Row['SumOfEmptyNetGoal'] . "</td>";			
		echo "<td>" . number_Format($Row['SumOfPenalityShotsPCT'],3) . "</td>";
		echo "<td>" . $Row['SumOfPenalityShotsShots'] . "</td>";
		echo "<td>" . $Row['SumOfStartGoaler'] . "</td>";
		echo "<td>" . $Row['SumOfBackupGoaler'] . "</td>";
		echo "<td>" . $Row['SumOfStar1'] . "</td>";
		echo "<td>" . $Row['SumOfStar2'] . "</td>";
		echo "<td>" . $Row['SumOfStar3'] . "</td>";
		echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
	}
}}
?>
</tbody></table>
<br />
</div>
<em><?php 
echo $PlayersLang['CareerNote'];
If ($TeamName != ""){echo $PlayersLang['CareerTeamNote'];}
?></em><br />
<?php include "Footer.php";?>
