<!DOCTYPE html>
<?php include "Header.php";?>
<?php
/*
Syntax to call this webpage should be GoaliesStat.php?Goalie=2 where only the number change and it's based on the UniqueID of Goalies.
*/
$Active = 1; /* Show Webpage Top Menu */
$Query = (string)"";
$LeagueName = "";
$GoalieName = $PlayersLang['IncorrectGoalie'];
$GoalieCareerStatFound = (boolean)false;
$GoalieProCareerSeason = Null;
$GoalieProCareerPlayoff = Null;
$GoalieProCareerSumSeasonOnly = Null;
$GoalieProCareerSumPlayoffOnly = Null;
$GoalieFarmCareerSeason = Null;
$GoalieFarmCareerPlayoff = Null;
$GoalieFarmCareerSumSeasonOnly = Null;
$GoalieFarmCareerSumPlayoffOnly = Null;

if(isset($_GET['Goalie'])){$GoalieName = filter_var($_GET['Goalie'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH);} 

If (file_exists($DatabaseFile) == true){
	$db = new SQLite3($DatabaseFile);
	$Query = "Select Name,PlayOffStarted from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
}

If ($GoalieName == $PlayersLang['IncorrectGoalie']){
	echo "<style type=\"text/css\">.STHSPHPPlayerStat_Main {display:none;}</style>";
}else{
	If (file_exists($CareerStatDatabaseFile) == true){ /* CareerStat */
		$CareerStatdb = new SQLite3($CareerStatDatabaseFile);
		
		$Query = "SELECT GoalerProStatCareer.*, ROUND((CAST(GoalerProStatCareer.GA AS REAL) / (GoalerProStatCareer.SecondPlay / 60))*60,3) AS GAA, ROUND((CAST(GoalerProStatCareer.SA - GoalerProStatCareer.GA AS REAL) / (GoalerProStatCareer.SA)),3) AS PCT, ROUND((CAST(GoalerProStatCareer.PenalityShotsShots - GoalerProStatCareer.PenalityShotsGoals AS REAL) / (GoalerProStatCareer.PenalityShotsShots)),3) AS PenalityShotsPCT FROM GoalerProStatCareer WHERE Playoff = 'False' AND (Name = '" . str_replace("'","''",$GoalieName) . "') ORDER BY GoalerProStatCareer.Year";
		$GoalieProCareerSeason = $CareerStatdb->query($Query);
		$Query = "SELECT GoalerProStatCareer.*, ROUND((CAST(GoalerProStatCareer.GA AS REAL) / (GoalerProStatCareer.SecondPlay / 60))*60,3) AS GAA, ROUND((CAST(GoalerProStatCareer.SA - GoalerProStatCareer.GA AS REAL) / (GoalerProStatCareer.SA)),3) AS PCT, ROUND((CAST(GoalerProStatCareer.PenalityShotsShots - GoalerProStatCareer.PenalityShotsGoals AS REAL) / (GoalerProStatCareer.PenalityShotsShots)),3) AS PenalityShotsPCT FROM GoalerProStatCareer WHERE Playoff = 'True' AND (Name = '" . str_replace("'","''",$GoalieName) . "') ORDER BY GoalerProStatCareer.Year";
		$GoalieProCareerPlayoff = $CareerStatdb->query($Query);	
		$Query = "SELECT Sum(GoalerProStatCareer.GP) AS SumOfGP, Sum(GoalerProStatCareer.SecondPlay) AS SumOfSecondPlay, Sum(GoalerProStatCareer.W) AS SumOfW, Sum(GoalerProStatCareer.L) AS SumOfL, Sum(GoalerProStatCareer.OTL) AS SumOfOTL, Sum(GoalerProStatCareer.Shootout) AS SumOfShootout, Sum(GoalerProStatCareer.GA) AS SumOfGA, Sum(GoalerProStatCareer.SA) AS SumOfSA, Sum(GoalerProStatCareer.SARebound) AS SumOfSARebound, Sum(GoalerProStatCareer.Pim) AS SumOfPim, Sum(GoalerProStatCareer.A) AS SumOfA, Sum(GoalerProStatCareer.PenalityShotsShots) AS SumOfPenalityShotsShots, Sum(GoalerProStatCareer.PenalityShotsGoals) AS SumOfPenalityShotsGoals, Sum(GoalerProStatCareer.StartGoaler) AS SumOfStartGoaler, Sum(GoalerProStatCareer.BackupGoaler) AS SumOfBackupGoaler, Sum(GoalerProStatCareer.EmptyNetGoal) AS SumOfEmptyNetGoal, Sum(GoalerProStatCareer.Star1) AS SumOfStar1, Sum(GoalerProStatCareer.Star2) AS SumOfStar2, Sum(GoalerProStatCareer.Star3) AS SumOfStar3 FROM GoalerProStatCareer WHERE Playoff = 'False' AND (Name = '" . str_replace("'","''",$GoalieName) . "')";
		$GoalieProCareerSumSeasonOnly = $CareerStatdb->querySingle($Query,true);		
		$Query = "SELECT Sum(GoalerProStatCareer.GP) AS SumOfGP, Sum(GoalerProStatCareer.SecondPlay) AS SumOfSecondPlay, Sum(GoalerProStatCareer.W) AS SumOfW, Sum(GoalerProStatCareer.L) AS SumOfL, Sum(GoalerProStatCareer.OTL) AS SumOfOTL, Sum(GoalerProStatCareer.Shootout) AS SumOfShootout, Sum(GoalerProStatCareer.GA) AS SumOfGA, Sum(GoalerProStatCareer.SA) AS SumOfSA, Sum(GoalerProStatCareer.SARebound) AS SumOfSARebound, Sum(GoalerProStatCareer.Pim) AS SumOfPim, Sum(GoalerProStatCareer.A) AS SumOfA, Sum(GoalerProStatCareer.PenalityShotsShots) AS SumOfPenalityShotsShots, Sum(GoalerProStatCareer.PenalityShotsGoals) AS SumOfPenalityShotsGoals, Sum(GoalerProStatCareer.StartGoaler) AS SumOfStartGoaler, Sum(GoalerProStatCareer.BackupGoaler) AS SumOfBackupGoaler, Sum(GoalerProStatCareer.EmptyNetGoal) AS SumOfEmptyNetGoal, Sum(GoalerProStatCareer.Star1) AS SumOfStar1, Sum(GoalerProStatCareer.Star2) AS SumOfStar2, Sum(GoalerProStatCareer.Star3) AS SumOfStar3 FROM GoalerProStatCareer WHERE Playoff = 'True' AND (Name = '" . str_replace("'","''",$GoalieName) . "')";
		$GoalieProCareerSumPlayoffOnly = $CareerStatdb->querySingle($Query,true);				
		
		$Query = "SELECT GoalerFarmStatCareer.*, ROUND((CAST(GoalerFarmStatCareer.GA AS REAL) / (GoalerFarmStatCareer.SecondPlay / 60))*60,3) AS GAA, ROUND((CAST(GoalerFarmStatCareer.SA - GoalerFarmStatCareer.GA AS REAL) / (GoalerFarmStatCareer.SA)),3) AS PCT, ROUND((CAST(GoalerFarmStatCareer.PenalityShotsShots - GoalerFarmStatCareer.PenalityShotsGoals AS REAL) / (GoalerFarmStatCareer.PenalityShotsShots)),3) AS PenalityShotsPCT FROM GoalerFarmStatCareer WHERE Playoff = 'False' AND (Name = '" . str_replace("'","''",$GoalieName) . "') ORDER BY GoalerFarmStatCareer.Year";
		$GoalieFarmCareerSeason = $CareerStatdb->query($Query);
		$Query = "SELECT GoalerFarmStatCareer.*, ROUND((CAST(GoalerFarmStatCareer.GA AS REAL) / (GoalerFarmStatCareer.SecondPlay / 60))*60,3) AS GAA, ROUND((CAST(GoalerFarmStatCareer.SA - GoalerFarmStatCareer.GA AS REAL) / (GoalerFarmStatCareer.SA)),3) AS PCT, ROUND((CAST(GoalerFarmStatCareer.PenalityShotsShots - GoalerFarmStatCareer.PenalityShotsGoals AS REAL) / (GoalerFarmStatCareer.PenalityShotsShots)),3) AS PenalityShotsPCT FROM GoalerFarmStatCareer WHERE Playoff = 'True' AND (Name = '" . str_replace("'","''",$GoalieName) . "') ORDER BY GoalerFarmStatCareer.Year";
		$GoalieFarmCareerPlayoff = $CareerStatdb->query($Query);	
		$Query = "SELECT Sum(GoalerFarmStatCareer.GP) AS SumOfGP, Sum(GoalerFarmStatCareer.SecondPlay) AS SumOfSecondPlay, Sum(GoalerFarmStatCareer.W) AS SumOfW, Sum(GoalerFarmStatCareer.L) AS SumOfL, Sum(GoalerFarmStatCareer.OTL) AS SumOfOTL, Sum(GoalerFarmStatCareer.Shootout) AS SumOfShootout, Sum(GoalerFarmStatCareer.GA) AS SumOfGA, Sum(GoalerFarmStatCareer.SA) AS SumOfSA, Sum(GoalerFarmStatCareer.SARebound) AS SumOfSARebound, Sum(GoalerFarmStatCareer.Pim) AS SumOfPim, Sum(GoalerFarmStatCareer.A) AS SumOfA, Sum(GoalerFarmStatCareer.PenalityShotsShots) AS SumOfPenalityShotsShots, Sum(GoalerFarmStatCareer.PenalityShotsGoals) AS SumOfPenalityShotsGoals, Sum(GoalerFarmStatCareer.StartGoaler) AS SumOfStartGoaler, Sum(GoalerFarmStatCareer.BackupGoaler) AS SumOfBackupGoaler, Sum(GoalerFarmStatCareer.EmptyNetGoal) AS SumOfEmptyNetGoal, Sum(GoalerFarmStatCareer.Star1) AS SumOfStar1, Sum(GoalerFarmStatCareer.Star2) AS SumOfStar2, Sum(GoalerFarmStatCareer.Star3) AS SumOfStar3 FROM GoalerFarmStatCareer WHERE Playoff = 'False' AND (Name = '" . str_replace("'","''",$GoalieName) . "')";
		$GoalieFarmCareerSumSeasonOnly = $CareerStatdb->querySingle($Query,true);		
		$Query = "SELECT Sum(GoalerFarmStatCareer.GP) AS SumOfGP, Sum(GoalerFarmStatCareer.SecondPlay) AS SumOfSecondPlay, Sum(GoalerFarmStatCareer.W) AS SumOfW, Sum(GoalerFarmStatCareer.L) AS SumOfL, Sum(GoalerFarmStatCareer.OTL) AS SumOfOTL, Sum(GoalerFarmStatCareer.Shootout) AS SumOfShootout, Sum(GoalerFarmStatCareer.GA) AS SumOfGA, Sum(GoalerFarmStatCareer.SA) AS SumOfSA, Sum(GoalerFarmStatCareer.SARebound) AS SumOfSARebound, Sum(GoalerFarmStatCareer.Pim) AS SumOfPim, Sum(GoalerFarmStatCareer.A) AS SumOfA, Sum(GoalerFarmStatCareer.PenalityShotsShots) AS SumOfPenalityShotsShots, Sum(GoalerFarmStatCareer.PenalityShotsGoals) AS SumOfPenalityShotsGoals, Sum(GoalerFarmStatCareer.StartGoaler) AS SumOfStartGoaler, Sum(GoalerFarmStatCareer.BackupGoaler) AS SumOfBackupGoaler, Sum(GoalerFarmStatCareer.EmptyNetGoal) AS SumOfEmptyNetGoal, Sum(GoalerFarmStatCareer.Star1) AS SumOfStar1, Sum(GoalerFarmStatCareer.Star2) AS SumOfStar2, Sum(GoalerFarmStatCareer.Star3) AS SumOfStar3 FROM GoalerFarmStatCareer WHERE Playoff = 'True' AND (Name = '" . str_replace("'","''",$GoalieName) . "')";
		$GoalieFarmCareerSumPlayoffOnly = $CareerStatdb->querySingle($Query,true);
		
		$GoalieCareerStatFound = true;
	}else{
		echo "<style type=\"text/css\">.STHSPHPPlayerStat_Main {display:none;}</style>";
	}
}

echo "<title>" . $LeagueName . " - " . $DynamicTitleLang['CareerStat'] . $GoalieName . "</title>";
echo "<style type=\"text/css\">";
if ($GoalieCareerStatFound == true){
	echo "#tablesorter_colSelect2:checked + label {background: #5797d7;  border-color: #555;}";
	echo "#tablesorter_colSelect2:checked ~ #tablesorter_ColumnSelector2 {display: block;}";
	echo "#tablesorter_colSelect3:checked + label {background: #5797d7;  border-color: #555;}";
	echo "#tablesorter_colSelect3:checked ~ #tablesorter_ColumnSelector3 {display: block;}";	
}
echo "</style>";
?>
</head><body>
<?php include "Menu.php";?>
<br />

<div class="STHSPHPPlayerStat_PlayerNameHeader">
<?php echo $GoalieName; ?></div><br />

<div class="STHSPHPPlayerStat_Main">
<br />

<div class="tabsmain standard"><ul class="tabmain-links">
<?php
if ($GoalieCareerStatFound == true){
	echo "<li  class=\"activemain\"><a href=\"#tabmain4\">" . $PlayersLang['CareerProStat'] . "</a></li>";
	echo "<li><a href=\"#tabmain5\">" . $PlayersLang['CareerFarmStat'] . "</a></li>";
}
?>
</ul>
<div class="STHSPHPPlayerStat_Tabmain-content">
<div class="tabmain active" id="tabmain4">
<br /><div class="STHSPHPPlayerStat_TabHeader"><?php echo $PlayersLang['CareerProStat'];?></div><br />

<div class="tablesorter_ColumnSelectorWrapper">
    <input id="tablesorter_colSelect2" type="checkbox" class="hidden">
    <label class="tablesorter_ColumnSelectorButton" for="tablesorter_colSelect2"><?php echo $TableSorterLang['ShoworHideColumn'];?></label>
    <div id="tablesorter_ColumnSelector2" class="tablesorter_ColumnSelector"></div>
</div>

<table class="tablesorter STHSPHPProCareerStat_Table"><thead><tr>
<th data-priority="2" title="Team Name" class="STHSW140Min"><?php echo $PlayersLang['TeamName'];?></th>
<th data-priority="1" title="Year" class="STHSW35"><?php echo $TeamLang['Year'];?></th>
<th data-priority="1" title="Games Played" class="STHSW25">GP</th>
<th data-priority="1" title="Wins" class="STHSW25">W</th>
<th data-priority="2" title="Losses" class="STHSW25">L</th>
<th data-priority="2" title="Overtime Losses" class="STHSW25">OTL</th>
<th data-priority="critical" title="Save Percentage" class="STHSW50">PCT</th>
<th data-priority="critical" title="Goals Against Average" class="STHSW50">GAA</th>
<th data-priority="3" title="Minutes Played" class="STHSW50">MP</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Penalty Minutes">PIM</th>
<th data-priority="4" title="Shutouts" class="STHSW25">SO</th>
<th data-priority="3" title="Goals Against" class="STHSW25">GA</th>
<th data-priority="3" title="Shots Against" class="STHSW45">SA</th>
<th data-priority="5" title="Shots Against Rebound" class="STHSW45">SAR</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Assists">A</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Empty net Goals">EG</th>
<th data-priority="4" title="Penalty Shots Save %" class="STHSW50">PS %</th>
<th data-priority="5" title="Penalty Shots Against" class="STHSW25">PSA</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Number of game goalies start as Start goalie">ST</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Number of game goalies start as Backup goalie">BG</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Number of time players was star #1 in a game">S1</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Number of time players was star #2 in a game">S2</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Number of time players was star #3 in a game">S3</th>
</tr></thead><tbody>
<?php 
if ($GoalieProCareerSumSeasonOnly['SumOfGP'] > 0){echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"23\"><strong>" . $PlayersLang['RegularSeason'] . "</strong></td></tr>\n";}
if (empty($GoalieProCareerSeason) == false){while ($Row = $GoalieProCareerSeason ->fetchArray()) {
	/* Loop ProGoalieCareerInfo */
	echo "<tr><td>" . $Row['TeamName'] . "</td>";
	echo "<td>" . $Row['Year'] . "</td>";
	echo "<td>" . $Row['GP'] . "</td>";
	echo "<td>" . $Row['W'] . "</td>";
	echo "<td>" . $Row['L'] . "</td>";
	echo "<td>" . $Row['OTL'] . "</td>";
	echo "<td>" . number_Format($Row['PCT'],3) . "</td>";
	echo "<td>" . number_Format($Row['GAA'],2) . "</td>";
	echo "<td>";if ($Row <> Null){echo Floor($Row['SecondPlay']/60);}; echo "</td>";
	echo "<td>" . $Row['Pim'] . "</td>";
	echo "<td>" . $Row['Shootout'] . "</td>";
	echo "<td>" . $Row['GA'] . "</td>";
	echo "<td>" . $Row['SA'] . "</td>";
	echo "<td>" . $Row['SARebound'] . "</td>";
	echo "<td>" . $Row['A'] . "</td>";
	echo "<td>" . $Row['EmptyNetGoal'] . "</td>";			
	echo "<td>" . number_Format($Row['PenalityShotsPCT'],3) . "</td>";
	echo "<td>" . $Row['PenalityShotsShots'] . "</td>";
	echo "<td>" . $Row['StartGoaler'] . "</td>";
	echo "<td>" . $Row['BackupGoaler'] . "</td>";
	echo "<td>" . $Row['Star1'] . "</td>";
	echo "<td>" . $Row['Star2'] . "</td>";
	echo "<td>" . $Row['Star3'] . "</td>";
	echo "</tr>\n"; 
}}

if ($GoalieProCareerSumSeasonOnly['SumOfGP'] > 0){
	/* Show ProCareer Total for Season */
	echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"2\"><strong>" . $PlayersLang['Total'] . " " . $PlayersLang['RegularSeason']. "</strong></td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumSeasonOnly['SumOfGP'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumSeasonOnly['SumOfW'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumSeasonOnly['SumOfL'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumSeasonOnly['SumOfOTL'] . "</td>";
	echo "<td>"; if ($GoalieProCareerSumSeasonOnly['SumOfSA'] > "0"){echo number_format(($GoalieProCareerSumSeasonOnly['SumOfSA'] - $GoalieProCareerSumSeasonOnly['SumOfGA']) / $GoalieProCareerSumSeasonOnly['SumOfSA'] ,3);}else {echo "0";}	echo "</td>";
	echo "<td>"; if ($GoalieProCareerSumSeasonOnly['SumOfSecondPlay'] > "0"){echo number_format($GoalieProCareerSumSeasonOnly['SumOfGA'] / ($GoalieProCareerSumSeasonOnly['SumOfSecondPlay'] / 60) *60,2);}else {echo "0%";}	echo "</td>";		
	echo "<td class=\"staticTD\">";if ($GoalieProCareerSumSeasonOnly <> Null){echo Floor($GoalieProCareerSumSeasonOnly['SumOfSecondPlay']/60);}; echo "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumSeasonOnly['SumOfPim'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumSeasonOnly['SumOfShootout'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumSeasonOnly['SumOfGA'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumSeasonOnly['SumOfSA'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumSeasonOnly['SumOfSARebound'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumSeasonOnly['SumOfA'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumSeasonOnly['SumOfEmptyNetGoal'] . "</td>";			
	echo "<td>"; if ($GoalieProCareerSumSeasonOnly['SumOfPenalityShotsShots'] > "0"){echo number_format(($GoalieProCareerSumSeasonOnly['SumOfPenalityShotsShots'] - $GoalieProCareerSumSeasonOnly['SumOfPenalityShotsGoals']) / $GoalieProCareerSumSeasonOnly['SumOfPenalityShotsShots'],3);}else {echo "0%";}echo "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumSeasonOnly['SumOfPenalityShotsShots'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumSeasonOnly['SumOfStartGoaler'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumSeasonOnly['SumOfBackupGoaler'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumSeasonOnly['SumOfStar1'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumSeasonOnly['SumOfStar2'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumSeasonOnly['SumOfStar3'] . "</td>";
	echo "</tr>\n";
}

If ($GoalieProCareerSumPlayoffOnly['SumOfGP'] > 0){echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"23\"><strong>" . $PlayersLang['Playoff'] . "</strong></td></tr>\n";}
if (empty($GoalieProCareerPlayoff) == false){while ($Row = $GoalieProCareerPlayoff ->fetchArray()) {
	/* Loop ProPlayerCareerPlayofff */
	echo "<tr><td>" . $Row['TeamName'] . "</td>";
	echo "<td>" . $Row['Year'] . "</td>";
	echo "<td>" . $Row['GP'] . "</td>";
	echo "<td>" . $Row['W'] . "</td>";
	echo "<td>" . $Row['L'] . "</td>";
	echo "<td>" . $Row['OTL'] . "</td>";
	echo "<td>" . number_Format($Row['PCT'],3) . "</td>";
	echo "<td>" . number_Format($Row['GAA'],2) . "</td>";
	echo "<td>";if ($Row <> Null){echo Floor($Row['SecondPlay']/60);}; echo "</td>";
	echo "<td>" . $Row['Pim'] . "</td>";
	echo "<td>" . $Row['Shootout'] . "</td>";
	echo "<td>" . $Row['GA'] . "</td>";
	echo "<td>" . $Row['SA'] . "</td>";
	echo "<td>" . $Row['SARebound'] . "</td>";
	echo "<td>" . $Row['A'] . "</td>";
	echo "<td>" . $Row['EmptyNetGoal'] . "</td>";			
	echo "<td>" . number_Format($Row['PenalityShotsPCT'],3) . "</td>";
	echo "<td>" . $Row['PenalityShotsShots'] . "</td>";
	echo "<td>" . $Row['StartGoaler'] . "</td>";
	echo "<td>" . $Row['BackupGoaler'] . "</td>";
	echo "<td>" . $Row['Star1'] . "</td>";
	echo "<td>" . $Row['Star2'] . "</td>";
	echo "<td>" . $Row['Star3'] . "</td>";
	echo "</tr>\n";
}}

If ($GoalieProCareerSumPlayoffOnly['SumOfGP'] > 0){
	/* Show ProCareer Total for Playoff */
	echo "<tr class=\"static\"><td colspan=\"2\" class=\"staticTD\"><strong>" . $PlayersLang['Total'] . " " . $PlayersLang['Playoff']. "</strong></td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumPlayoffOnly['SumOfGP'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumPlayoffOnly['SumOfW'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumPlayoffOnly['SumOfL'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumPlayoffOnly['SumOfOTL'] . "</td>";
	echo "<td>"; if ($GoalieProCareerSumPlayoffOnly['SumOfSA'] > "0"){echo number_format(($GoalieProCareerSumPlayoffOnly['SumOfSA'] - $GoalieProCareerSumPlayoffOnly['SumOfGA']) / $GoalieProCareerSumPlayoffOnly['SumOfSA'] ,3);}else {echo "0";}	echo "</td>";
	echo "<td>"; if ($GoalieProCareerSumPlayoffOnly['SumOfSecondPlay'] > "0"){echo number_format($GoalieProCareerSumPlayoffOnly['SumOfGA'] / ($GoalieProCareerSumPlayoffOnly['SumOfSecondPlay'] / 60) *60,2);}else {echo "0%";}	echo "</td>";	
	echo "<td class=\"staticTD\">";if ($GoalieProCareerSumPlayoffOnly <> Null){echo Floor($GoalieProCareerSumPlayoffOnly['SumOfSecondPlay']/60);}; echo "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumPlayoffOnly['SumOfPim'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumPlayoffOnly['SumOfShootout'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumPlayoffOnly['SumOfGA'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumPlayoffOnly['SumOfSA'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumPlayoffOnly['SumOfSARebound'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumPlayoffOnly['SumOfA'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumPlayoffOnly['SumOfEmptyNetGoal'] . "</td>";			
	echo "<td>"; if ($GoalieProCareerSumPlayoffOnly['SumOfPenalityShotsShots'] > "0"){echo number_format(($GoalieProCareerSumPlayoffOnly['SumOfPenalityShotsShots'] - $GoalieProCareerSumPlayoffOnly['SumOfPenalityShotsGoals']) / $GoalieProCareerSumPlayoffOnly['SumOfPenalityShotsShots'],3);}else {echo "0%";}echo "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumPlayoffOnly['SumOfPenalityShotsShots'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumPlayoffOnly['SumOfStartGoaler'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumPlayoffOnly['SumOfBackupGoaler'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumPlayoffOnly['SumOfStar1'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumPlayoffOnly['SumOfStar2'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumPlayoffOnly['SumOfStar3'] . "</td>";
	echo "</tr>\n";
}
?>
</tbody></table>
<br /></div>

<div class="tabmain" id="tabmain5">
<br /><div class="STHSPHPPlayerStat_TabHeader"><?php echo $PlayersLang['CareerFarmStat'];?></div><br />

<div class="tablesorter_ColumnSelectorWrapper">
    <input id="tablesorter_colSelect3" type="checkbox" class="hidden">
    <label class="tablesorter_ColumnSelectorButton" for="tablesorter_colSelect3"><?php echo $TableSorterLang['ShoworHideColumn'];?></label>
    <div id="tablesorter_ColumnSelector3" class="tablesorter_ColumnSelector"></div>
</div>


<table class="tablesorter STHSPHPFarmCareerStat_Table"><thead><tr>
<th data-priority="1" title="Team Name" class="STHSW140Min"><?php echo $PlayersLang['TeamName'];?></th>
<th data-priority="critical" title="Year" class="STHSW35"><?php echo $TeamLang['Year'];?></th>
<th data-priority="1" title="Games Played" class="STHSW25">GP</th>
<th data-priority="1" title="Wins" class="STHSW25">W</th>
<th data-priority="2" title="Losses" class="STHSW25">L</th>
<th data-priority="2" title="Overtime Losses" class="STHSW25">OTL</th>
<th data-priority="critical" title="Save Percentage" class="STHSW50">PCT</th>
<th data-priority="critical" title="Goals Against Average" class="STHSW50">GAA</th>
<th data-priority="3" title="Minutes Played" class="STHSW50">MP</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Penalty Minutes">PIM</th>
<th data-priority="4" title="Shutouts" class="STHSW25">SO</th>
<th data-priority="3" title="Goals Against" class="STHSW25">GA</th>
<th data-priority="3" title="Shots Against" class="STHSW45">SA</th>
<th data-priority="5" title="Shots Against Rebound" class="STHSW45">SAR</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Assists">A</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Empty net Goals">EG</th>
<th data-priority="4" title="Penalty Shots Save %" class="STHSW50">PS %</th>
<th data-priority="5" title="Penalty Shots Against" class="STHSW25">PSA</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Number of game goalies start as Start goalie">ST</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Number of game goalies start as Backup goalie">BG</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Number of time players was star #1 in a game">S1</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Number of time players was star #2 in a game">S2</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Number of time players was star #3 in a game">S3</th>
</tr></thead><tbody>
<?php 
if ($GoalieFarmCareerSumSeasonOnly['SumOfGP'] > 0){echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"23\"><strong>" . $PlayersLang['RegularSeason'] . "</strong></td></tr>\n";}
if (empty($GoalieFarmCareerSeason) == false){while ($Row = $GoalieFarmCareerSeason ->fetchArray()) {
	/* Loop FarmPlayerCareerInfo */
	echo "<tr><td>" . $Row['TeamName'] . "</td>";
	echo "<td>" . $Row['Year'] . "</td>";
	echo "<td>" . $Row['GP'] . "</td>";
	echo "<td>" . $Row['W'] . "</td>";
	echo "<td>" . $Row['L'] . "</td>";
	echo "<td>" . $Row['OTL'] . "</td>";
	echo "<td>" . number_Format($Row['PCT'],3) . "</td>";
	echo "<td>" . number_Format($Row['GAA'],2) . "</td>";
	echo "<td>";if ($Row <> Null){echo Floor($Row['SecondPlay']/60);}; echo "</td>";
	echo "<td>" . $Row['Pim'] . "</td>";
	echo "<td>" . $Row['Shootout'] . "</td>";
	echo "<td>" . $Row['GA'] . "</td>";
	echo "<td>" . $Row['SA'] . "</td>";
	echo "<td>" . $Row['SARebound'] . "</td>";
	echo "<td>" . $Row['A'] . "</td>";
	echo "<td>" . $Row['EmptyNetGoal'] . "</td>";			
	echo "<td>" . number_Format($Row['PenalityShotsPCT'],3) . "</td>";
	echo "<td>" . $Row['PenalityShotsShots'] . "</td>";
	echo "<td>" . $Row['StartGoaler'] . "</td>";
	echo "<td>" . $Row['BackupGoaler'] . "</td>";
	echo "<td>" . $Row['Star1'] . "</td>";
	echo "<td>" . $Row['Star2'] . "</td>";
	echo "<td>" . $Row['Star3'] . "</td>";
	echo "</tr>\n"; 
}}

if ($GoalieFarmCareerSumSeasonOnly['SumOfGP'] > 0){
	/* Show FarmCareer Total for Season */
	echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"2\"><strong>" . $PlayersLang['Total'] . " " . $PlayersLang['RegularSeason']. "</strong></td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumSeasonOnly['SumOfGP'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumSeasonOnly['SumOfW'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumSeasonOnly['SumOfL'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumSeasonOnly['SumOfOTL'] . "</td>";
	echo "<td>"; if ($GoalieFarmCareerSumSeasonOnly['SumOfSA'] > "0"){echo number_format(($GoalieFarmCareerSumSeasonOnly['SumOfSA'] - $GoalieFarmCareerSumSeasonOnly['SumOfGA']) / $GoalieFarmCareerSumSeasonOnly['SumOfSA'] ,3);}else {echo "0";}	echo "</td>";
	echo "<td>"; if ($GoalieFarmCareerSumSeasonOnly['SumOfSecondPlay'] > "0"){echo number_format($GoalieFarmCareerSumSeasonOnly['SumOfGA'] / ($GoalieFarmCareerSumSeasonOnly['SumOfSecondPlay'] / 60) *60,2);}else {echo "0%";}	echo "</td>";		
	echo "<td class=\"staticTD\">";if ($GoalieFarmCareerSumSeasonOnly <> Null){echo Floor($GoalieFarmCareerSumSeasonOnly['SumOfSecondPlay']/60);}; echo "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumSeasonOnly['SumOfPim'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumSeasonOnly['SumOfShootout'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumSeasonOnly['SumOfGA'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumSeasonOnly['SumOfSA'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumSeasonOnly['SumOfSARebound'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumSeasonOnly['SumOfA'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumSeasonOnly['SumOfEmptyNetGoal'] . "</td>";			
	echo "<td>"; if ($GoalieFarmCareerSumSeasonOnly['SumOfPenalityShotsShots'] > "0"){echo number_format(($GoalieFarmCareerSumSeasonOnly['SumOfPenalityShotsShots'] - $GoalieFarmCareerSumSeasonOnly['SumOfPenalityShotsGoals']) / $GoalieFarmCareerSumSeasonOnly['SumOfPenalityShotsShots'],3);}else {echo "0%";}echo "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumSeasonOnly['SumOfPenalityShotsShots'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumSeasonOnly['SumOfStartGoaler'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumSeasonOnly['SumOfBackupGoaler'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumSeasonOnly['SumOfStar1'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumSeasonOnly['SumOfStar2'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumSeasonOnly['SumOfStar3'] . "</td>";
	echo "</tr>\n";
}

If ($GoalieFarmCareerSumPlayoffOnly['SumOfGP'] > 0){echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"23\"><strong>" . $PlayersLang['Playoff'] . "</strong></td></tr>\n";}
if (empty($GoalieFarmCareerPlayoff) == false){while ($Row = $GoalieFarmCareerPlayoff ->fetchArray()) {
	/* Loop FarmPlayerCareerPlayofff */
	echo "<tr><td>" . $Row['TeamName'] . "</td>";
	echo "<td>" . $Row['Year'] . "</td>";
	echo "<td>" . $Row['GP'] . "</td>";
	echo "<td>" . $Row['W'] . "</td>";
	echo "<td>" . $Row['L'] . "</td>";
	echo "<td>" . $Row['OTL'] . "</td>";
	echo "<td>" . number_Format($Row['PCT'],3) . "</td>";
	echo "<td>" . number_Format($Row['GAA'],2) . "</td>";
	echo "<td>";if ($Row <> Null){echo Floor($Row['SecondPlay']/60);}; echo "</td>";
	echo "<td>" . $Row['Pim'] . "</td>";
	echo "<td>" . $Row['Shootout'] . "</td>";
	echo "<td>" . $Row['GA'] . "</td>";
	echo "<td>" . $Row['SA'] . "</td>";
	echo "<td>" . $Row['SARebound'] . "</td>";
	echo "<td>" . $Row['A'] . "</td>";
	echo "<td>" . $Row['EmptyNetGoal'] . "</td>";			
	echo "<td>" . number_Format($Row['PenalityShotsPCT'],3) . "</td>";
	echo "<td>" . $Row['PenalityShotsShots'] . "</td>";
	echo "<td>" . $Row['StartGoaler'] . "</td>";
	echo "<td>" . $Row['BackupGoaler'] . "</td>";
	echo "<td>" . $Row['Star1'] . "</td>";
	echo "<td>" . $Row['Star2'] . "</td>";
	echo "<td>" . $Row['Star3'] . "</td>";
	echo "</tr>\n"; 
}}

If ($GoalieFarmCareerSumPlayoffOnly['SumOfGP'] > 0){
	/* Show FarmCareer Total for Playoff */
	echo "<tr class=\"static\"><td colspan=\"2\" class=\"staticTD\"><strong>" . $PlayersLang['Total'] . " " . $PlayersLang['Playoff']. "</strong></td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumPlayoffOnly['SumOfGP'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumPlayoffOnly['SumOfW'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumPlayoffOnly['SumOfL'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumPlayoffOnly['SumOfOTL'] . "</td>";
	echo "<td>"; if ($GoalieFarmCareerSumPlayoffOnly['SumOfSA'] > "0"){echo number_format(($GoalieFarmCareerSumPlayoffOnly['SumOfSA'] - $GoalieFarmCareerSumPlayoffOnly['SumOfGA']) / $GoalieFarmCareerSumPlayoffOnly['SumOfSA'] ,3);}else {echo "0";}	echo "</td>";
	echo "<td>"; if ($GoalieFarmCareerSumPlayoffOnly['SumOfSecondPlay'] > "0"){echo number_format($GoalieFarmCareerSumPlayoffOnly['SumOfGA'] / ($GoalieFarmCareerSumPlayoffOnly['SumOfSecondPlay'] / 60) *60,2);}else {echo "0%";}	echo "</td>";		
	echo "<td class=\"staticTD\">";if ($GoalieFarmCareerSumPlayoffOnly <> Null){echo Floor($GoalieFarmCareerSumPlayoffOnly['SumOfSecondPlay']/60);}; echo "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumPlayoffOnly['SumOfPim'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumPlayoffOnly['SumOfShootout'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumPlayoffOnly['SumOfGA'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumPlayoffOnly['SumOfSA'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumPlayoffOnly['SumOfSARebound'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumPlayoffOnly['SumOfA'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumPlayoffOnly['SumOfEmptyNetGoal'] . "</td>";			
	echo "<td>"; if ($GoalieFarmCareerSumPlayoffOnly['SumOfPenalityShotsShots'] > "0"){echo number_format(($GoalieFarmCareerSumPlayoffOnly['SumOfPenalityShotsShots'] - $GoalieFarmCareerSumPlayoffOnly['SumOfPenalityShotsGoals']) / $GoalieFarmCareerSumPlayoffOnly['SumOfPenalityShotsShots'],3);}else {echo "0%";}echo "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumPlayoffOnly['SumOfPenalityShotsShots'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumPlayoffOnly['SumOfStartGoaler'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumPlayoffOnly['SumOfBackupGoaler'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumPlayoffOnly['SumOfStar1'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumPlayoffOnly['SumOfStar2'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumPlayoffOnly['SumOfStar3'] . "</td>";
	echo "</tr>\n";
}
?>
</tbody></table>
<br /></div>

</div>
</div>
</div>

<?php
if ($GoalieCareerStatFound == true){
	echo "<script type=\"text/javascript\">\$(function() {\$(\".STHSPHPProCareerStat_Table\").tablesorter( {widgets: ['staticRow', 'columnSelector'], widgetOptions : {columnSelector_container : \$('#tablesorter_ColumnSelector2'), columnSelector_layout : '<label><input type=\"checkbox\">{name}</label>', columnSelector_name  : 'title', columnSelector_mediaquery: true, columnSelector_mediaqueryName: 'Automatic', columnSelector_mediaqueryState: true, columnSelector_mediaqueryHidden: true, columnSelector_breakpoints : [ '20em', '40em', '60em', '80em', '90em', '95em' ],}});});</script>";
	echo "<script type=\"text/javascript\">\$(function() {\$(\".STHSPHPFarmCareerStat_Table\").tablesorter({widgets: ['staticRow', 'columnSelector'], widgetOptions : {columnSelector_container : \$('#tablesorter_ColumnSelector3'), columnSelector_layout : '<label><input type=\"checkbox\">{name}</label>', columnSelector_name  : 'title', columnSelector_mediaquery: true, columnSelector_mediaqueryName: 'Automatic', columnSelector_mediaqueryState: true, columnSelector_mediaqueryHidden: true, columnSelector_breakpoints : [ '20em', '40em', '60em', '80em', '90em', '95em' ],}});});</script>";
}
?>

<?php include "Footer.php";?>
