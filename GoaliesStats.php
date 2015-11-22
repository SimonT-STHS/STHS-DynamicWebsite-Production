<?php include "Header.php";?>
<?php $DatabaseFile = (string)"SIMON2-STHS.db"; ?>
<?php
/*
Syntax to call this webpage should be GoaliesStat.php?Goalie=2 where only the number change and it's based on the UniqueID of Goalies.
*/

$Goalie = (integer)0;
$Query = (string)"";
$GoalieName = (string)"Incorrect Goalie";
if($_GET){$Goalie = filter_var($_GET['Goalie'], FILTER_SANITIZE_NUMBER_INT);} 

If (file_exists($DatabaseFile) == false){
	$Goalie == 0;
	$GoalieName = "Database File Not Found";
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
		$GoalieName = $GoalieInfo['Name'];	
		$Query = "SELECT * FROM GoalerProStat WHERE Number = " . $Goalie;
		$GoalieProStat = $db->querySingle($Query,true);
		$Query = "SELECT * FROM GoalerFarmStat WHERE Number = " . $Goalie;
		$GoalieFarmStat = $db->querySingle($Query,true);
	}else{
		$GoalieName = (string)"Goalie not found";
		$GoalieInfo = Null;
		$GoalieProStat = Null;
		$GoalieFarmStat = Null;	
		echo "<style type=\"text/css\">.STHSPHPPlayerStat_Main {display:none;}</style>";
	}
}

?>
<div class="STHSPHPPlayerStat_PlayerNameHeader"><?php echo $GoalieName . " - " . $GoalieInfo['TeamName']; ?></div><br />

<div class="STHSPHPPlayerStat_Main">
<br />

<table class="STHSPHPPlayerStat_Table">
<tr>
	<th>Age</th>
	<th>Condition</th>
	<th>Suspension</th>	
	<th>Height</th>
	<th>Weight</th>
</tr><tr>
	<td><?php echo $GoalieInfo['Age']; ?></td>	
	<td><?php if ($GoalieInfo <> Null){echo number_format($GoalieInfo['ConditionDecimal'],2);} ?></td>	
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
<li class="activemain"><a href="#tabmain1">Information</a></li>
<li><a href="#tabmain2">Pro Stat</a></li>
<li><a href="#tabmain3">Farm Stat</a></li>
</ul>
<div class="STHSPHPPlayerStat_Tabmain-content">
<div class="tabmain active" id="tabmain1">
<br /><div class="STHSPHPPlayerStat_TabHeader">Information</div><br />
<table class="STHSPHPPlayerStat_Table">
<tr>
	<th>Birthday</th>
	<th>Country</th>
	<th>Rookie</th>
	<th>Injury</th>
	<th>Health # Loss</th>
	<th>Star Power</th>	
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
	<th>Available for Trade</th>
	<th>No Trade</th>
	<th>Force Waiver</th>
	<th>Can Play Pro</th>
	<th>Can Play Farm</th>
	<th>Exclude from Salary Cap</th>
	<th>Pro Salary in Farm / 1 Way Contract</th>
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
	<th>Contract Duration</th>
	<th>Salary Average</th>
	<th>Salary Year 1</th>
	<th>Salary Year 2</th>
	<th>Salary Year 3</th>
	<th>Salary Year 4</th>
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
<br /><div class="STHSPHPPlayerStat_TabHeader">Pro Stat - Basic</div><br />
<table class="STHSPHPPlayerStat_Table">
<tr>
	<th>Games Played</th>
	<th>Wins</th>
	<th>Losses</th>
	<th>Overtime Losses</th>
	<th>Save Percentage</th>
	<th>Goals Against Average</th>
	<th>Minutes Played</th>
</tr><tr>
	<td><?php echo $GoalieProStat['GP']; ?></td>
	<td><?php echo $GoalieProStat['W']; ?></td>
	<td><?php echo $GoalieProStat['L']; ?></td>
	<td><?php echo $GoalieProStat['OTL']; ?></td>
	<td><?php  If ($GoalieProStat['SA'] > 0){echo number_Format(($GoalieProStat['SA'] - $GoalieProStat['GA']) / $GoalieProStat['SA'],3);}else{echo number_Format("0",3);}?></td>
	<td><?php  If ($GoalieProStat['SecondPlay'] > 0){echo number_Format($GoalieProStat['GA'] / $GoalieProStat['SecondPlay'] * 3600,3);}else{echo number_Format("0",2);}?></td>
	<td><?php if ($GoalieProStat <> Null){echo Floor($GoalieProStat['SecondPlay']/60);} ?></td>		
</tr>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPPlayerStat_Table">
<tr>
	<th>Penalty Minutes</th>
	<th>Shootout</th>
	<th>Goals Against</th>
	<th>Shots Against</th>
	<th>Shots Against Rebound</th>
	<th>Assists</th>
	<th>Empty net Goals</th>	
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
	<th>Penalty Shots Save %</th>
	<th>Penalty Shots Against</th>
	<th>Number of game goalies start as Start goalie</th>	
	<th>Number of game goalies start as Backup goalie</th>	
</tr><tr>		
	<td><?php  If ($GoalieProStat['PenalityShotsShots'] > 0){echo number_Format(($GoalieProStat['PenalityShotsShots'] - $GoalieProStat['PenalityShotsGoals']) / $GoalieProStat['PenalityShotsShots'],3);}else{echo number_Format("0",3);}?></td>
	<td><?php echo $GoalieProStat['PenalityShotsShots']; ?></td>
	<td><?php echo $GoalieProStat['StartGoaler']; ?></td>
	<td><?php echo $GoalieProStat['BackupGoaler']; ?></td>	
</tr>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPPlayerStat_Table">
<tr>
	<th>Number of time players was star #1 in a game</th>
	<th>Number of time players was star #2 in a game</th>
	<th>Number of time players was star #3 in a game</th>	
</tr><tr>		
	<td><?php echo $GoalieProStat['Star1']; ?></td>	
	<td><?php echo $GoalieProStat['Star2']; ?></td>
	<td><?php echo $GoalieProStat['Star3']; ?></td>
</tr>
</table>

<br /><br /></div>
<div class="tabmain" id="tabmain3">
<br /><div class="STHSPHPPlayerStat_TabHeader">Farm Stat - Basic</div><br />
<table class="STHSPHPPlayerStat_Table">
<tr>
	<th>Games Played</th>
	<th>Wins</th>
	<th>Losses</th>
	<th>Overtime Losses</th>
	<th>Save Percentage</th>
	<th>Goals Against Average</th>
	<th>Minutes Played</th>
</tr><tr>
	<td><?php echo $GoalieFarmStat['GP']; ?></td>
	<td><?php echo $GoalieFarmStat['W']; ?></td>
	<td><?php echo $GoalieFarmStat['L']; ?></td>
	<td><?php echo $GoalieFarmStat['OTL']; ?></td>
	<td><?php  If ($GoalieFarmStat['SA'] > 0){echo number_Format(($GoalieFarmStat['SA'] - $GoalieFarmStat['GA']) / $GoalieFarmStat['SA'],3);}else{echo number_Format("0",3);}?></td>
	<td><?php  If ($GoalieFarmStat['SecondPlay'] > 0){echo number_Format($GoalieFarmStat['GA'] / $GoalieFarmStat['SecondPlay'] * 3600,3);}else{echo number_Format("0",2);}?></td>
	<td><?php if ($GoalieFarmStat <> Null){echo Floor($GoalieFarmStat['SecondPlay']/60);} ?></td>		
</tr>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPPlayerStat_Table">
<tr>
	<th>Penalty Minutes</th>
	<th>Shootout</th>
	<th>Goals Against</th>
	<th>Shots Against</th>
	<th>Shots Against Rebound</th>
	<th>Assists</th>
	<th>Empty net Goals</th>	
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
	<th>Penalty Shots Save %</th>
	<th>Penalty Shots Against</th>
	<th>Number of game goalies start as Start goalie</th>	
	<th>Number of game goalies start as Backup goalie</th>	
</tr><tr>		
	<td><?php  If ($GoalieFarmStat['PenalityShotsShots'] > 0){echo number_Format(($GoalieFarmStat['PenalityShotsShots'] - $GoalieFarmStat['PenalityShotsGoals']) / $GoalieFarmStat['PenalityShotsShots'],3);}else{echo number_Format("0",3);}?></td>
	<td><?php echo $GoalieFarmStat['PenalityShotsShots']; ?></td>
	<td><?php echo $GoalieFarmStat['StartGoaler']; ?></td>
	<td><?php echo $GoalieFarmStat['BackupGoaler']; ?></td>	
</tr>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPPlayerStat_Table">
<tr>
	<th>Number of time players was star #1 in a game</th>
	<th>Number of time players was star #2 in a game</th>
	<th>Number of time players was star #3 in a game</th>	
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
