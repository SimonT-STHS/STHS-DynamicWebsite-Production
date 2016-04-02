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
	}else{
		$GoalieName = $PlayersLang['Goalienotfound'];
		$GoalieInfo = Null;
		$GoalieProStat = Null;
		$GoalieFarmStat = Null;	
		echo "<style type=\"text/css\">.STHSPHPPlayerStat_Main {display:none;}</style>";
	}
}
echo "<title>" . $LeagueName . " - " . $GoalieName . "</title>";
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

</div>
</div>
</div>

<?php include "Footer.php";?>
