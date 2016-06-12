<!DOCTYPE html>
<?php include "Header.php";?>
<?php
/*
Syntax to call this webpage should be GoaliesStat.php?Goalie=2 where only the number change and it's based on the UniqueID of Goalies.
*/

$Goalie = (integer)0;
$Query = (string)"";
$GoalieName = $PlayersLang['IncorrectGoalie'];
$LeagueName = (string)"";
$GoalieCareerStatFound = (boolean)false;
$GoalieProCareerSeason = Null;
$GoalieProCareerPlayoff = Null;
$GoalieProCareerSumSeasonOnly = Null;
$GoalieProCareerSumPlayoffOnly = Null;
$GoalieFarmCareerSeason = Null;
$GoalieFarmCareerPlayoff = Null;
$GoalieFarmCareerSumSeasonOnly = Null;
$GoalieFarmCareerSumPlayoffOnly = Null;
if(isset($_GET['Goalie'])){$Goalie = filter_var($_GET['Goalie'], FILTER_SANITIZE_NUMBER_INT);} 

If (file_exists($DatabaseFile) == false){
	$Goalie = 0;
	$GoalieName = $DatabaseNotFound;
}else{
	$db = new SQLite3($DatabaseFile);
}
If ($Goalie == 0){
	$GoalieInfo = Null;
	$GoalieProStat = Null;
	$GoalieFarmStat = Null;	
	$LeagueOutputOption = Null;
	echo "<style type=\"text/css\">.STHSPHPPlayerStat_Main {display:none;}</style>";
}else{
	$Query = "SELECT count(*) AS count FROM GoalerInfo WHERE Number = " . $Goalie;
	$Result = $db->querySingle($Query,true);
	If ($Result['count'] == 1){
		$Query = "SELECT * FROM GoalerInfo WHERE Number = " . $Goalie;
		$GoalieInfo = $db->querySingle($Query,true);
		$Query = "SELECT GoalerProStat.*, ROUND((CAST(GoalerProStat.GA AS REAL) / (GoalerProStat.SecondPlay / 60))*60,3) AS GAA, ROUND((CAST(GoalerProStat.SA - GoalerProStat.GA AS REAL) / (GoalerProStat.SA)),3) AS PCT, ROUND((CAST(GoalerProStat.PenalityShotsShots - GoalerProStat.PenalityShotsGoals AS REAL) / (GoalerProStat.PenalityShotsShots)),3) AS PenalityShotsPCT  FROM GoalerProStat WHERE Number = " . $Goalie;
		$GoalieProStat = $db->querySingle($Query,true);
		$Query = "SELECT GoalerFarmStat.*, ROUND((CAST(GoalerFarmStat.GA AS REAL) / (GoalerFarmStat.SecondPlay / 60))*60,3) AS GAA, ROUND((CAST(GoalerFarmStat.SA - GoalerFarmStat.GA AS REAL) / (GoalerFarmStat.SA)),3) AS PCT, ROUND((CAST(GoalerFarmStat.PenalityShotsShots - GoalerFarmStat.PenalityShotsGoals AS REAL) / (GoalerFarmStat.PenalityShotsShots)),3) AS PenalityShotsPCT FROM GoalerFarmStat WHERE Number = " . $Goalie;
		$GoalieFarmStat = $db->querySingle($Query,true);
		$Query = "Select Name, OutputName from LeagueGeneral";
		$LeagueGeneral = $db->querySingle($Query,true);	
		$Query = "Select PlayersMugShotBaseURL, PlayersMugShotFileExtension from LeagueOutputOption";
		$LeagueOutputOption = $db->querySingle($Query,true);			
		
		$LeagueName = $LeagueGeneral['Name'];		
		$GoalieName = $GoalieInfo['Name'];

		If (file_exists($CareerStatDatabaseFile) == true){ /* CareerStat */
			$CareerStatdb = new SQLite3($CareerStatDatabaseFile);
			
			$Query = "SELECT GoalerProStatCareer.*, ROUND((CAST(GoalerProStatCareer.GA AS REAL) / (GoalerProStatCareer.SecondPlay / 60))*60,3) AS GAA, ROUND((CAST(GoalerProStatCareer.SA - GoalerProStatCareer.GA AS REAL) / (GoalerProStatCareer.SA)),3) AS PCT, ROUND((CAST(GoalerProStatCareer.PenalityShotsShots - GoalerProStatCareer.PenalityShotsGoals AS REAL) / (GoalerProStatCareer.PenalityShotsShots)),3) AS PenalityShotsPCT FROM GoalerProStatCareer WHERE Playoff = 'False' AND (UniqueID = " . $GoalieInfo['UniqueID'] . " OR Name = '" . $GoalieName . "') ORDER BY GoalerProStatCareer.Year";
			$GoalieProCareerSeason = $CareerStatdb->query($Query);
			$Query = "SELECT GoalerProStatCareer.*, ROUND((CAST(GoalerProStatCareer.GA AS REAL) / (GoalerProStatCareer.SecondPlay / 60))*60,3) AS GAA, ROUND((CAST(GoalerProStatCareer.SA - GoalerProStatCareer.GA AS REAL) / (GoalerProStatCareer.SA)),3) AS PCT, ROUND((CAST(GoalerProStatCareer.PenalityShotsShots - GoalerProStatCareer.PenalityShotsGoals AS REAL) / (GoalerProStatCareer.PenalityShotsShots)),3) AS PenalityShotsPCT FROM GoalerProStatCareer WHERE Playoff = 'True' AND (UniqueID = " . $GoalieInfo['UniqueID'] . " OR Name = '" . $GoalieName . "') ORDER BY GoalerProStatCareer.Year";
			$GoalieProCareerPlayoff = $CareerStatdb->query($Query);	
			$Query = "SELECT Sum(GoalerProStatCareer.GP) AS SumOfGP, Sum(GoalerProStatCareer.SecondPlay) AS SumOfSecondPlay, Sum(GoalerProStatCareer.W) AS SumOfW, Sum(GoalerProStatCareer.L) AS SumOfL, Sum(GoalerProStatCareer.OTL) AS SumOfOTL, Sum(GoalerProStatCareer.Shootout) AS SumOfShootout, Sum(GoalerProStatCareer.GA) AS SumOfGA, Sum(GoalerProStatCareer.SA) AS SumOfSA, Sum(GoalerProStatCareer.SARebound) AS SumOfSARebound, Sum(GoalerProStatCareer.Pim) AS SumOfPim, Sum(GoalerProStatCareer.A) AS SumOfA, Sum(GoalerProStatCareer.PenalityShotsShots) AS SumOfPenalityShotsShots, Sum(GoalerProStatCareer.PenalityShotsGoals) AS SumOfPenalityShotsGoals, Sum(GoalerProStatCareer.StartGoaler) AS SumOfStartGoaler, Sum(GoalerProStatCareer.BackupGoaler) AS SumOfBackupGoaler, Sum(GoalerProStatCareer.EmptyNetGoal) AS SumOfEmptyNetGoal, Sum(GoalerProStatCareer.Star1) AS SumOfStar1, Sum(GoalerProStatCareer.Star2) AS SumOfStar2, Sum(GoalerProStatCareer.Star3) AS SumOfStar3, ROUND((CAST(Sum(GoalerProStatCareer.GA) AS REAL) / (Sum(GoalerProStatCareer.SecondPlay) / 60))*60,3) AS SumOfGAA, ROUND((CAST(Sum(GoalerProStatCareer.SA) - Sum(GoalerProStatCareer.GA) AS REAL) / (Sum(GoalerProStatCareer.SA))),3) AS SumOfPCT, ROUND((CAST(Sum(GoalerProStatCareer.PenalityShotsShots) - Sum(GoalerProStatCareer.PenalityShotsGoals) AS REAL) / (Sum(GoalerProStatCareer.PenalityShotsShots))),3) AS SumOfPenalityShotsPCT FROM GoalerProStatCareer WHERE Playoff = 'False' AND (UniqueID = " . $GoalieInfo['UniqueID'] . " OR Name = '" . $GoalieName . "')";
			$GoalieProCareerSumSeasonOnly = $CareerStatdb->querySingle($Query,true);		
			$Query = "SELECT Sum(GoalerProStatCareer.GP) AS SumOfGP, Sum(GoalerProStatCareer.SecondPlay) AS SumOfSecondPlay, Sum(GoalerProStatCareer.W) AS SumOfW, Sum(GoalerProStatCareer.L) AS SumOfL, Sum(GoalerProStatCareer.OTL) AS SumOfOTL, Sum(GoalerProStatCareer.Shootout) AS SumOfShootout, Sum(GoalerProStatCareer.GA) AS SumOfGA, Sum(GoalerProStatCareer.SA) AS SumOfSA, Sum(GoalerProStatCareer.SARebound) AS SumOfSARebound, Sum(GoalerProStatCareer.Pim) AS SumOfPim, Sum(GoalerProStatCareer.A) AS SumOfA, Sum(GoalerProStatCareer.PenalityShotsShots) AS SumOfPenalityShotsShots, Sum(GoalerProStatCareer.PenalityShotsGoals) AS SumOfPenalityShotsGoals, Sum(GoalerProStatCareer.StartGoaler) AS SumOfStartGoaler, Sum(GoalerProStatCareer.BackupGoaler) AS SumOfBackupGoaler, Sum(GoalerProStatCareer.EmptyNetGoal) AS SumOfEmptyNetGoal, Sum(GoalerProStatCareer.Star1) AS SumOfStar1, Sum(GoalerProStatCareer.Star2) AS SumOfStar2, Sum(GoalerProStatCareer.Star3) AS SumOfStar3, ROUND((CAST(Sum(GoalerProStatCareer.GA) AS REAL) / (Sum(GoalerProStatCareer.SecondPlay) / 60))*60,3) AS SumOfGAA, ROUND((CAST(Sum(GoalerProStatCareer.SA) - Sum(GoalerProStatCareer.GA) AS REAL) / (Sum(GoalerProStatCareer.SA))),3) AS SumOfPCT, ROUND((CAST(Sum(GoalerProStatCareer.PenalityShotsShots) - Sum(GoalerProStatCareer.PenalityShotsGoals) AS REAL) / (Sum(GoalerProStatCareer.PenalityShotsShots))),3) AS SumOfPenalityShotsPCT FROM GoalerProStatCareer WHERE Playoff = 'True' AND (UniqueID = " . $GoalieInfo['UniqueID'] . " OR Name = '" . $GoalieName . "')";
			$GoalieProCareerSumPlayoffOnly = $CareerStatdb->querySingle($Query,true);				
			
			$Query = "SELECT GoalerFarmStatCareer.*, ROUND((CAST(GoalerFarmStatCareer.GA AS REAL) / (GoalerFarmStatCareer.SecondPlay / 60))*60,3) AS GAA, ROUND((CAST(GoalerFarmStatCareer.SA - GoalerFarmStatCareer.GA AS REAL) / (GoalerFarmStatCareer.SA)),3) AS PCT, ROUND((CAST(GoalerFarmStatCareer.PenalityShotsShots - GoalerFarmStatCareer.PenalityShotsGoals AS REAL) / (GoalerFarmStatCareer.PenalityShotsShots)),3) AS PenalityShotsPCT FROM GoalerFarmStatCareer WHERE Playoff = 'False' AND (UniqueID = " . $GoalieInfo['UniqueID'] . " OR Name = '" . $GoalieName . "') ORDER BY GoalerFarmStatCareer.Year";
			$GoalieFarmCareerSeason = $CareerStatdb->query($Query);
			$Query = "SELECT GoalerFarmStatCareer.*, ROUND((CAST(GoalerFarmStatCareer.GA AS REAL) / (GoalerFarmStatCareer.SecondPlay / 60))*60,3) AS GAA, ROUND((CAST(GoalerFarmStatCareer.SA - GoalerFarmStatCareer.GA AS REAL) / (GoalerFarmStatCareer.SA)),3) AS PCT, ROUND((CAST(GoalerFarmStatCareer.PenalityShotsShots - GoalerFarmStatCareer.PenalityShotsGoals AS REAL) / (GoalerFarmStatCareer.PenalityShotsShots)),3) AS PenalityShotsPCT FROM GoalerFarmStatCareer WHERE Playoff = 'True' AND (UniqueID = " . $GoalieInfo['UniqueID'] . " OR Name = '" . $GoalieName . "') ORDER BY GoalerFarmStatCareer.Year";
			$GoalieFarmCareerPlayoff = $CareerStatdb->query($Query);	
			$Query = "SELECT Sum(GoalerFarmStatCareer.GP) AS SumOfGP, Sum(GoalerFarmStatCareer.SecondPlay) AS SumOfSecondPlay, Sum(GoalerFarmStatCareer.W) AS SumOfW, Sum(GoalerFarmStatCareer.L) AS SumOfL, Sum(GoalerFarmStatCareer.OTL) AS SumOfOTL, Sum(GoalerFarmStatCareer.Shootout) AS SumOfShootout, Sum(GoalerFarmStatCareer.GA) AS SumOfGA, Sum(GoalerFarmStatCareer.SA) AS SumOfSA, Sum(GoalerFarmStatCareer.SARebound) AS SumOfSARebound, Sum(GoalerFarmStatCareer.Pim) AS SumOfPim, Sum(GoalerFarmStatCareer.A) AS SumOfA, Sum(GoalerFarmStatCareer.PenalityShotsShots) AS SumOfPenalityShotsShots, Sum(GoalerFarmStatCareer.PenalityShotsGoals) AS SumOfPenalityShotsGoals, Sum(GoalerFarmStatCareer.StartGoaler) AS SumOfStartGoaler, Sum(GoalerFarmStatCareer.BackupGoaler) AS SumOfBackupGoaler, Sum(GoalerFarmStatCareer.EmptyNetGoal) AS SumOfEmptyNetGoal, Sum(GoalerFarmStatCareer.Star1) AS SumOfStar1, Sum(GoalerFarmStatCareer.Star2) AS SumOfStar2, Sum(GoalerFarmStatCareer.Star3) AS SumOfStar3, ROUND((CAST(Sum(GoalerFarmStatCareer.GA) AS REAL) / (Sum(GoalerFarmStatCareer.SecondPlay) / 60))*60,3) AS SumOfGAA, ROUND((CAST(Sum(GoalerFarmStatCareer.SA) - Sum(GoalerFarmStatCareer.GA) AS REAL) / (Sum(GoalerFarmStatCareer.SA))),3) AS SumOfPCT, ROUND((CAST(Sum(GoalerFarmStatCareer.PenalityShotsShots) - Sum(GoalerFarmStatCareer.PenalityShotsGoals) AS REAL) / (Sum(GoalerFarmStatCareer.PenalityShotsShots))),3) AS SumOfPenalityShotsPCT FROM GoalerFarmStatCareer WHERE Playoff = 'False' AND (UniqueID = " . $GoalieInfo['UniqueID'] . " OR Name = '" . $GoalieName . "')";
			$GoalieFarmCareerSumSeasonOnly = $CareerStatdb->querySingle($Query,true);		
			$Query = "SELECT Sum(GoalerFarmStatCareer.GP) AS SumOfGP, Sum(GoalerFarmStatCareer.SecondPlay) AS SumOfSecondPlay, Sum(GoalerFarmStatCareer.W) AS SumOfW, Sum(GoalerFarmStatCareer.L) AS SumOfL, Sum(GoalerFarmStatCareer.OTL) AS SumOfOTL, Sum(GoalerFarmStatCareer.Shootout) AS SumOfShootout, Sum(GoalerFarmStatCareer.GA) AS SumOfGA, Sum(GoalerFarmStatCareer.SA) AS SumOfSA, Sum(GoalerFarmStatCareer.SARebound) AS SumOfSARebound, Sum(GoalerFarmStatCareer.Pim) AS SumOfPim, Sum(GoalerFarmStatCareer.A) AS SumOfA, Sum(GoalerFarmStatCareer.PenalityShotsShots) AS SumOfPenalityShotsShots, Sum(GoalerFarmStatCareer.PenalityShotsGoals) AS SumOfPenalityShotsGoals, Sum(GoalerFarmStatCareer.StartGoaler) AS SumOfStartGoaler, Sum(GoalerFarmStatCareer.BackupGoaler) AS SumOfBackupGoaler, Sum(GoalerFarmStatCareer.EmptyNetGoal) AS SumOfEmptyNetGoal, Sum(GoalerFarmStatCareer.Star1) AS SumOfStar1, Sum(GoalerFarmStatCareer.Star2) AS SumOfStar2, Sum(GoalerFarmStatCareer.Star3) AS SumOfStar3, ROUND((CAST(Sum(GoalerFarmStatCareer.GA) AS REAL) / (Sum(GoalerFarmStatCareer.SecondPlay) / 60))*60,3) AS SumOfGAA, ROUND((CAST(Sum(GoalerFarmStatCareer.SA) - Sum(GoalerFarmStatCareer.GA) AS REAL) / (Sum(GoalerFarmStatCareer.SA))),3) AS SumOfPCT, ROUND((CAST(Sum(GoalerFarmStatCareer.PenalityShotsShots) - Sum(GoalerFarmStatCareer.PenalityShotsGoals) AS REAL) / (Sum(GoalerFarmStatCareer.PenalityShotsShots))),3) AS SumOfPenalityShotsPCT FROM GoalerFarmStatCareer WHERE Playoff = 'True' AND (UniqueID = " . $GoalieInfo['UniqueID'] . " OR Name = '" . $GoalieName . "')";
			$GoalieFarmCareerSumPlayoffOnly = $CareerStatdb->querySingle($Query,true);
			
			$GoalieCareerStatFound = true;
		}
		
	}else{
		$GoalieName = $PlayersLang['Goalienotfound'];
		$GoalieInfo = Null;
		$GoalieProStat = Null;
		$GoalieFarmStat = Null;	
		echo "<style type=\"text/css\">.STHSPHPPlayerStat_Main {display:none;}</style>";
	}
}
echo "<title>" . $LeagueName . " - " . $GoalieName . "</title>";
if ($GoalieCareerStatFound == true){
	echo "<style type=\"text/css\">";
	echo "#tablesorter_colSelect2:checked + label {background: #5797d7;  border-color: #555;}";
	echo "#tablesorter_colSelect2:checked ~ #tablesorter_ColumnSelector2 {display: block;}";
	echo "#tablesorter_colSelect3:checked + label {background: #5797d7;  border-color: #555;}";
	echo "#tablesorter_colSelect3:checked ~ #tablesorter_ColumnSelector3 {display: block;}";
	echo "</style>";
}
?>
</head><body>
<?php include "Menu.php";?>
<br />

<div class="STHSPHPPlayerStat_PlayerNameHeader">
<?php
If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $GoalieInfo['NHLID'] != ""){
	echo "<table class=\"STHSTableFullW STHSPHPPlayerMugShot\"><tr><td>" . $GoalieName . "<br /><br />" . $GoalieInfo['TeamName'];
	echo "</td><td><img src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $GoalieInfo['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $GoalieName . "\" /></td></tr></table>";
}else{
	echo $GoalieName . " - " . $GoalieInfo['TeamName'];
}
 ?></div><br />

<div class="STHSPHPPlayerStat_Main">
<br />

<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $PlayersLang['Age'];?></th>
	<th><?php echo $PlayersLang['Condition'];?></th>
	<th><?php echo $PlayersLang['Suspension'];?></th>	
	<th><?php echo $PlayersLang['Height'];?></th>
	<th><?php echo $PlayersLang['Weight'];?></th>	
</tr><tr>
	<td><?php echo $GoalieInfo['Age']; ?></td>	
	<td><?php if ($GoalieInfo <> Null){echo number_format(str_replace(",",".",$GoalieInfo['ConditionDecimal']),2);} ?></td>	
	<td><?php echo $GoalieInfo['Suspension']; ?></td>	
	<td><?php echo $GoalieInfo['Height']; ?></td>
	<td><?php echo $GoalieInfo['Weight']; ?></td>
</tr>
</table>
<div class="STHSBlankDiv"></div>
<table class="STHSPHPPlayerStat_Table">
<tr>
	<th>SK</th>
	<th>DU</th>
	<th>EN</th>
	<th>SZ</th>
	<th>AG</th>
	<th>RB</th>
	<th>SC</th>
	<th>HS</th>
	<th>RT</th>
	<th>PH</th>
	<th>PS</th>
	<th>EX</th>
	<th>LD</th>
	<th>PO</th>
	<th>MO</th>
	<th>OV</th>
</tr><tr>
	<td><?php echo $GoalieInfo['SK']; ?></td>
	<td><?php echo $GoalieInfo['DU']; ?></td>
	<td><?php echo $GoalieInfo['EN']; ?></td>
	<td><?php echo $GoalieInfo['SZ']; ?></td>
	<td><?php echo $GoalieInfo['AG']; ?></td>
	<td><?php echo $GoalieInfo['RB']; ?></td>
	<td><?php echo $GoalieInfo['SC']; ?></td>
	<td><?php echo $GoalieInfo['HS']; ?></td>
	<td><?php echo $GoalieInfo['RT']; ?></td>
	<td><?php echo $GoalieInfo['PH']; ?></td>
	<td><?php echo $GoalieInfo['PS']; ?></td>
	<td><?php echo $GoalieInfo['EX']; ?></td>
	<td><?php echo $GoalieInfo['LD']; ?></td>
	<td><?php echo $GoalieInfo['PO']; ?></td>
	<td><?php echo $GoalieInfo['MO']; ?></td>
	<td><?php echo $GoalieInfo['Overall']; ?></td> 
</tr>
</table>
<br />

<div class="tabsmain standard"><ul class="tabmain-links">
<li class="activemain"><a href="#tabmain1"><?php echo $PlayersLang['Information'];?></a></li>
<li><a href="#tabmain2"><?php echo $PlayersLang['ProStat'];?></a></li>
<li><a href="#tabmain3"><?php echo $PlayersLang['FarmStat'];?></a></li>
<?php if ($GoalieCareerStatFound == true){
	echo "<li><a href=\"#tabmain4\">" . $PlayersLang['CareerProStat'] . "</a></li>";
	echo "<li><a href=\"#tabmain5\">" . $PlayersLang['CareerFarmStat'] . "</a></li>";
}?>
</ul>
<div class="STHSPHPPlayerStat_Tabmain-content">
<div class="tabmain active" id="tabmain1">
<br /><div class="STHSPHPPlayerStat_TabHeader"><?php echo $PlayersLang['Information'];?></div><br />
<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $PlayersLang['Birthday'];?></th>
	<th><?php echo $PlayersLang['Country'];?></th>
	<th><?php echo $PlayersLang['Rookie'];?></th>
	<th><?php echo $PlayersLang['Injury'];?></th>
	<th><?php echo $PlayersLang['HealthLoss'];?></th>
	<th><?php echo $PlayersLang['StarPower'];?></th>	
</tr><tr>
	<td><?php echo $GoalieInfo['AgeDate']; ?></td>
	<td><?php echo $GoalieInfo['Country']; ?></td>
	<td><?php if ($GoalieInfo['Rookie']== "True"){ echo "Yes"; }else{echo "No";} ?></td>		
	<td><?php echo $GoalieInfo['Injury']; ?></td>	
	<td><?php echo $GoalieInfo['NumberOfInjury']; ?></td>
	<td><?php echo $GoalieInfo['StarPower']; ?></td>	
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $PlayersLang['AvailableforTrade'];?></th>
	<th><?php echo $PlayersLang['NoTrade'];?></th>
	<th><?php echo $PlayersLang['ForceWaiver'];?></th>
	<th><?php echo $PlayersLang['CanPlayPro'];?></th>
	<th><?php echo $PlayersLang['CanPlayFarm'];?></th>
	<th><?php echo $PlayersLang['ExcludefromSalaryCap'];?></th>
	<th><?php echo $PlayersLang['ProSalaryinFarm'];?></th>
</tr><tr>
	<td><?php if ($GoalieInfo['AvailableforTrade']== "True"){ echo "Yes"; }else{echo "No";} ?></td>
	<td><?php if ($GoalieInfo['NoTrade']== "True"){ echo "Yes"; }else{echo "No";} ?></td>
	<td><?php if ($GoalieInfo['ForceWaiver']== "True"){ echo "Yes"; }else{echo "No";} ?></td>
	<td><?php if ($GoalieInfo['CanPlayPro']== "True"){ echo "Yes"; }else{echo "No";} ?></td>
	<td><?php if ($GoalieInfo['CanPlayFarm']== "True"){ echo "Yes"; }else{echo "No";} ?></td>	
	<td><?php if ($GoalieInfo['ExcludeSalaryCap']== "True"){ echo "Yes"; }else{echo "No";} ?></td>
	<td><?php if ($GoalieInfo['ProSalaryinFarm']== "True"){ echo "Yes"; }else{echo "No";} ?></td>
</tr>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $PlayersLang['Contract'];?></th>
	<th><?php echo $PlayersLang['SalaryAverage'];?></th>
	<th><?php echo $PlayersLang['SalaryYear'];?> 1</th>
	<th><?php echo $PlayersLang['SalaryYear'];?> 2</th>
	<th><?php echo $PlayersLang['SalaryYear'];?> 3</th>
	<th><?php echo $PlayersLang['SalaryYear'];?> 4</th>
</tr><tr>
	<td><?php echo $GoalieInfo['Contract']; ?></td>
	<td><?php if ($GoalieInfo <> Null){echo number_format($GoalieInfo['SalaryAverage'],0) . "$";} ?></td>
	<td><?php if ($GoalieInfo <> Null){echo number_format($GoalieInfo['Salary1'],0) . "$";} ?></td>
	<td><?php if ($GoalieInfo <> Null){echo number_format($GoalieInfo['Salary2'],0) . "$";} ?></td>
	<td><?php if ($GoalieInfo <> Null){echo number_format($GoalieInfo['Salary3'],0) . "$";} ?></td>
	<td><?php if ($GoalieInfo <> Null){echo number_format($GoalieInfo['Salary4'],0) . "$";} ?></td>
</tr>
</table>
<div class="STHSBlankDiv"></div>

<br /><br /></div>
<div class="tabmain" id="tabmain2">
<br /><div class="STHSPHPPlayerStat_TabHeader"><?php echo $PlayersLang['ProStat'];?></div><br />
<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $GeneralStatLang['GamePlayed'];?></th>
	<th><?php echo $GeneralStatLang['Wins'];?></th>
	<th><?php echo $GeneralStatLang['Losses'];?></th>
	<th><?php echo $GeneralStatLang['OvertimeLosses'];?></th>
	<th><?php echo $GeneralStatLang['SavePCT'];?></th>
	<th><?php echo $GeneralStatLang['GoalsAgainstAverage'];?></th>
	<th><?php echo $GeneralStatLang['MinutesPlayed'];?></th>
</tr><tr>
	<td><?php echo $GoalieProStat['GP']; ?></td>
	<td><?php echo $GoalieProStat['W']; ?></td>
	<td><?php echo $GoalieProStat['L']; ?></td>
	<td><?php echo $GoalieProStat['OTL']; ?></td>
	<td><?php echo number_Format($GoalieProStat['PCT'],3); ?></td>
	<td><?php echo number_Format($GoalieProStat['GAA'],2); ?></td>
	<td><?php if ($GoalieProStat <> Null){echo Floor($GoalieProStat['SecondPlay']/60);} ?></td>		
</tr>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $GeneralStatLang['PenaltyMinutes'];?></th>
	<th><?php echo $GeneralStatLang['Shutouts'];?></th>
	<th><?php echo $GeneralStatLang['GoalsAgainst'];?></th>
	<th><?php echo $GeneralStatLang['ShotsAgainst'];?></th>
	<th><?php echo $GeneralStatLang['ShotsAgainstRebound'];?></th>
	<th><?php echo $GeneralStatLang['Assists'];?></th>
	<th><?php echo $GeneralStatLang['EmptyNetGoals'];?></th>	
</tr><tr>
	<td><?php echo $GoalieProStat['Pim']; ?></td>
	<td><?php echo $GoalieProStat['Shootout']; ?></td>
	<td><?php echo $GoalieProStat['GA']; ?></td>
	<td><?php echo $GoalieProStat['SA']; ?></td>
	<td><?php echo $GoalieProStat['SARebound']; ?></td>
	<td><?php echo $GoalieProStat['A']; ?></td>
	<td><?php echo $GoalieProStat['EmptyNetGoal']; ?></td>		
</tr>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $GeneralStatLang['PenaltyShotsSavePCT'];?></th>
	<th><?php echo $GeneralStatLang['PenaltyShotsAgainst'];?></th>
	<th><?php echo $GeneralStatLang['PenaltyShotsGoals'];?></th>
	<th><?php echo $GeneralStatLang['NumberStartGoalie'];?></th>	
	<th><?php echo $GeneralStatLang['NumberBackupGoalie'];?></th>	
</tr><tr>		
	<td><?php echo number_Format($GoalieProStat['PenalityShotsPCT'],3); ?></td>
	<td><?php echo $GoalieProStat['PenalityShotsShots']; ?></td>
	<td><?php echo $GoalieProStat['PenalityShotsGoals']; ?></td>	
	<td><?php echo $GoalieProStat['StartGoaler']; ?></td>
	<td><?php echo $GoalieProStat['BackupGoaler']; ?></td>	
</tr>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $GeneralStatLang['NumberTimeStar1'];?></th>
	<th><?php echo $GeneralStatLang['NumberTimeStar2'];?></th>
	<th><?php echo $GeneralStatLang['NumberTimeStar3'];?></th>	
</tr><tr>		
	<td><?php echo $GoalieProStat['Star1']; ?></td>	
	<td><?php echo $GoalieProStat['Star2']; ?></td>
	<td><?php echo $GoalieProStat['Star3']; ?></td>
</tr>
</table>

<br /><br /></div>
<div class="tabmain" id="tabmain3">
<br /><div class="STHSPHPPlayerStat_TabHeader"><?php echo $PlayersLang['FarmStat'];?></div><br />
<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $GeneralStatLang['GamePlayed'];?></th>
	<th><?php echo $GeneralStatLang['Wins'];?></th>
	<th><?php echo $GeneralStatLang['Losses'];?></th>
	<th><?php echo $GeneralStatLang['OvertimeLosses'];?></th>
	<th><?php echo $GeneralStatLang['SavePCT'];?></th>
	<th><?php echo $GeneralStatLang['GoalsAgainstAverage'];?></th>
	<th><?php echo $GeneralStatLang['MinutesPlayed'];?></th>
</tr><tr>
	<td><?php echo $GoalieFarmStat['GP']; ?></td>
	<td><?php echo $GoalieFarmStat['W']; ?></td>
	<td><?php echo $GoalieFarmStat['L']; ?></td>
	<td><?php echo $GoalieFarmStat['OTL']; ?></td>
	<td><?php echo number_Format($GoalieFarmStat['PCT'],3); ?></td>
	<td><?php echo number_Format($GoalieFarmStat['GAA'],2); ?></td>
	<td><?php if ($GoalieFarmStat <> Null){echo Floor($GoalieFarmStat['SecondPlay']/60);} ?></td>		
</tr>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $GeneralStatLang['PenaltyMinutes'];?></th>
	<th><?php echo $GeneralStatLang['Shutouts'];?></th>
	<th><?php echo $GeneralStatLang['GoalsAgainst'];?></th>
	<th><?php echo $GeneralStatLang['ShotsAgainst'];?></th>
	<th><?php echo $GeneralStatLang['ShotsAgainstRebound'];?></th>
	<th><?php echo $GeneralStatLang['Assists'];?></th>
	<th><?php echo $GeneralStatLang['EmptyNetGoals'];?></th>		
</tr><tr>
	<td><?php echo $GoalieFarmStat['Pim']; ?></td>
	<td><?php echo $GoalieFarmStat['Shootout']; ?></td>
	<td><?php echo $GoalieFarmStat['GA']; ?></td>
	<td><?php echo $GoalieFarmStat['SA']; ?></td>
	<td><?php echo $GoalieFarmStat['SARebound']; ?></td>
	<td><?php echo $GoalieFarmStat['A']; ?></td>
	<td><?php echo $GoalieFarmStat['EmptyNetGoal']; ?></td>		
</tr>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $GeneralStatLang['PenaltyShotsSavePCT'];?></th>
	<th><?php echo $GeneralStatLang['PenaltyShotsAgainst'];?></th>
	<th><?php echo $GeneralStatLang['PenaltyShotsGoals'];?></th>	
	<th><?php echo $GeneralStatLang['NumberStartGoalie'];?></th>	
	<th><?php echo $GeneralStatLang['NumberBackupGoalie'];?></th>	
</tr><tr>		
	<td><?php echo number_Format($GoalieFarmStat['PenalityShotsPCT'],3); ?></td>	
	<td><?php echo $GoalieFarmStat['PenalityShotsShots']; ?></td>
	<td><?php echo $GoalieFarmStat['PenalityShotsGoals']; ?></td>
	<td><?php echo $GoalieFarmStat['StartGoaler']; ?></td>
	<td><?php echo $GoalieFarmStat['BackupGoaler']; ?></td>	
</tr>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $GeneralStatLang['NumberTimeStar1'];?></th>
	<th><?php echo $GeneralStatLang['NumberTimeStar2'];?></th>
	<th><?php echo $GeneralStatLang['NumberTimeStar3'];?></th>	
</tr><tr>		
	<td><?php echo $GoalieFarmStat['Star1']; ?></td>	
	<td><?php echo $GoalieFarmStat['Star2']; ?></td>
	<td><?php echo $GoalieFarmStat['Star3']; ?></td>
</tr>
</table>
<br /><br /></div>

<div class="tabmain" id="tabmain4">
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
	echo "<td class=\"staticTD\">" . number_Format($GoalieProCareerSumSeasonOnly['SumOfPCT'],3) . "</td>";
	echo "<td class=\"staticTD\">" . number_Format($GoalieProCareerSumSeasonOnly['SumOfGAA'],2) . "</td>";
	echo "<td class=\"staticTD\">";if ($GoalieProCareerSumSeasonOnly <> Null){echo Floor($GoalieProCareerSumSeasonOnly['SumOfSecondPlay']/60);}; echo "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumSeasonOnly['SumOfPim'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumSeasonOnly['SumOfShootout'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumSeasonOnly['SumOfGA'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumSeasonOnly['SumOfSA'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumSeasonOnly['SumOfSARebound'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumSeasonOnly['SumOfA'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumSeasonOnly['SumOfEmptyNetGoal'] . "</td>";			
	echo "<td class=\"staticTD\">" . number_Format($GoalieProCareerSumSeasonOnly['SumOfPenalityShotsPCT'],3) . "</td>";
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
	echo "<td class=\"staticTD\">" . number_Format($GoalieProCareerSumPlayoffOnly['SumOfPCT'],3) . "</td>";
	echo "<td class=\"staticTD\">" . number_Format($GoalieProCareerSumPlayoffOnly['SumOfGAA'],2) . "</td>";
	echo "<td class=\"staticTD\">";if ($GoalieProCareerSumPlayoffOnly <> Null){echo Floor($GoalieProCareerSumPlayoffOnly['SumOfSecondPlay']/60);}; echo "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumPlayoffOnly['SumOfPim'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumPlayoffOnly['SumOfShootout'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumPlayoffOnly['SumOfGA'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumPlayoffOnly['SumOfSA'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumPlayoffOnly['SumOfSARebound'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumPlayoffOnly['SumOfA'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumPlayoffOnly['SumOfEmptyNetGoal'] . "</td>";			
	echo "<td class=\"staticTD\">" . number_Format($GoalieProCareerSumPlayoffOnly['SumOfPenalityShotsPCT'],3) . "</td>";
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
	echo "<td class=\"staticTD\">" . number_Format($GoalieFarmCareerSumSeasonOnly['SumOfPCT'],3) . "</td>";
	echo "<td class=\"staticTD\">" . number_Format($GoalieFarmCareerSumSeasonOnly['SumOfGAA'],2) . "</td>";
	echo "<td class=\"staticTD\">";if ($GoalieFarmCareerSumSeasonOnly <> Null){echo Floor($GoalieFarmCareerSumSeasonOnly['SumOfSecondPlay']/60);}; echo "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumSeasonOnly['SumOfPim'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumSeasonOnly['SumOfShootout'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumSeasonOnly['SumOfGA'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumSeasonOnly['SumOfSA'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumSeasonOnly['SumOfSARebound'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumSeasonOnly['SumOfA'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumSeasonOnly['SumOfEmptyNetGoal'] . "</td>";			
	echo "<td class=\"staticTD\">" . number_Format($GoalieFarmCareerSumSeasonOnly['SumOfPenalityShotsPCT'],3) . "</td>";
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
	echo "<td class=\"staticTD\">" . number_Format($GoalieFarmCareerSumPlayoffOnly['SumOfPCT'],3) . "</td>";
	echo "<td class=\"staticTD\">" . number_Format($GoalieFarmCareerSumPlayoffOnly['SumOfGAA'],2) . "</td>";
	echo "<td class=\"staticTD\">";if ($GoalieFarmCareerSumPlayoffOnly <> Null){echo Floor($GoalieFarmCareerSumPlayoffOnly['SumOfSecondPlay']/60);}; echo "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumPlayoffOnly['SumOfPim'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumPlayoffOnly['SumOfShootout'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumPlayoffOnly['SumOfGA'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumPlayoffOnly['SumOfSA'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumPlayoffOnly['SumOfSARebound'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumPlayoffOnly['SumOfA'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumPlayoffOnly['SumOfEmptyNetGoal'] . "</td>";			
	echo "<td class=\"staticTD\">" . number_Format($GoalieFarmCareerSumPlayoffOnly['SumOfPenalityShotsPCT'],3) . "</td>";
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
	echo "<script type=\"text/javascript\">\$(function() {\$(\".STHSPHPProCareerStat_Table\").tablesorter( {widgets: ['staticRow', 'columnSelector'], widgetOptions : {columnSelector_container : \$('#tablesorter_ColumnSelector2'), columnSelector_layout : '<label><input type=\"checkbox\">{name}</label>', columnSelector_name  : 'title', columnSelector_mediaquery: true, columnSelector_mediaqueryName: 'Automatic', columnSelector_mediaqueryState: true, columnSelector_mediaqueryHidden: true, columnSelector_breakpoints : [ '50em', '60em', '70em', '80em', '90em', '95em' ],}});});</script>";
	echo "<script type=\"text/javascript\">\$(function() {\$(\".STHSPHPFarmCareerStat_Table\").tablesorter({widgets: ['staticRow', 'columnSelector'], widgetOptions : {columnSelector_container : \$('#tablesorter_ColumnSelector3'), columnSelector_layout : '<label><input type=\"checkbox\">{name}</label>', columnSelector_name  : 'title', columnSelector_mediaquery: true, columnSelector_mediaqueryName: 'Automatic', columnSelector_mediaqueryState: true, columnSelector_mediaqueryHidden: true, columnSelector_breakpoints : [ '50em', '60em', '70em', '80em', '90em', '95em' ],}});});</script>";
}
?>

<?php include "Footer.php";?>
