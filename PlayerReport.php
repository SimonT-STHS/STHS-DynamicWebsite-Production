<!DOCTYPE html>
<?php include "Header.php";?>
<?php
/*
Syntax to call this webpage should be PlayersStat.php?Player=2 where only the number change and it's based on the UniqueID of players.
*/

$Player = (integer)0;
$Query = (string)"";
$PlayerName = $PlayersLang['IncorrectPlayer'];
$LeagueName = (string)"";
if(isset($_GET['Player'])){$Player = filter_var($_GET['Player'], FILTER_SANITIZE_NUMBER_INT);} 

If (file_exists($DatabaseFile) == false){
	$Player = 0;
	$PlayerName = $DatabaseNotFound;
}else{
	$db = new SQLite3($DatabaseFile);
}
If ($Player == 0){
	$PlayerInfo = Null;
	$PlayerProStat = Null;
	$PlayerFarmStat = Null;	
	echo "<style type=\"text/css\">.STHSPHPPlayerStat_Main {display:none;}</style>";
}else{
	$Query = "SELECT count(*) AS count FROM PlayerInfo WHERE Number = " . $Player;
	$Result = $db->querySingle($Query,true);
	If ($Result['count'] == 1){
		$Query = "SELECT * FROM PlayerInfo WHERE Number = " . $Player;
		$PlayerInfo = $db->querySingle($Query,true);
		$Query = "SELECT * FROM PlayerProStat WHERE Number = " . $Player;
		$PlayerProStat = $db->querySingle($Query,true);
		$Query = "SELECT * FROM PlayerFarmStat WHERE Number = " . $Player;
		$PlayerFarmStat = $db->querySingle($Query,true);
		$Query = "Select Name, OutputName from LeagueGeneral";
		$LeagueGeneral = $db->querySingle($Query,true);	
		$Query = "Select PlayersMugShotBaseURL, PlayersMugShotFileExtension from LeagueOutputOption";
		$LeagueOutputOption = $db->querySingle($Query,true);				
		
		$LeagueName = $LeagueGeneral['Name'];
		$PlayerName = $PlayerInfo['Name'];	
	}else{
		$PlayerName = $PlayersLang['Playernotfound'];
		$PlayerInfo = Null;
		$PlayerProStat = Null;
		$PlayerFarmStat = Null;	
		echo "<style type=\"text/css\">.STHSPHPPlayerStat_Main {display:none;}</style>";
	}
}

/*
Not Add Yet : URLLink, GameInRow*, Jersey
*/
echo "<title>" . $LeagueName . " - " . $PlayerName . "</title>";
?>
</head><body>
<?php include "Menu.php";?>
<br />

<div class="STHSPHPPlayerStat_PlayerNameHeader">
<?php
If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $PlayerInfo ['NHLID'] != ""){
	echo "<table class=\"STHSTableFullW STHSPHPPlayerMugShot\"><tr><td>" . $PlayerName . "<br /><br />" . $PlayerInfo['TeamName'];
	echo "</td><td><img src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $PlayerInfo['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $PlayerName . "\" /></td></tr></table>";
}else{
	echo $PlayerName . " - " . $PlayerInfo['TeamName'];
}
 ?></div><br />

<div class="STHSPHPPlayerStat_Main">
<br />

<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $PlayersLang['Position'];?></th>
	<th><?php echo $PlayersLang['Age'];?></th>
	<th><?php echo $PlayersLang['Condition'];?></th>
	<th><?php echo $PlayersLang['Suspension'];?></th>	
	<th><?php echo $PlayersLang['Height'];?></th>
	<th><?php echo $PlayersLang['Weight'];?></th>
</tr><tr>
	<td><?php
	$Position = (string)"";
	if ($PlayerInfo['PosC']== "True"){if ($Position == ""){$Position = $TeamLang['Center'];}else{$Position = $Position . " - " . $TeamLang['Center'];}}
	if ($PlayerInfo['PosLW']== "True"){if ($Position == ""){$Position = $TeamLang['LeftWing'];}else{$Position = $Position . " - " . $TeamLang['LeftWing'];}}
	if ($PlayerInfo['PosRW']== "True"){if ($Position == ""){$Position = $TeamLang['RightWing'];}else{$Position = $Position . " - " . $TeamLang['RightWing'];}}
	if ($PlayerInfo['PosD']== "True"){if ($Position == ""){$Position = $TeamLang['Defense'];}else{$Position = $Position . " - " . $TeamLang['Defense'];}}
	echo $Position;
	?></td>
	<td><?php echo $PlayerInfo['Age']; ?></td>	
	<td><?php if ($PlayerInfo <> Null){echo number_format(str_replace(",",".",$PlayerInfo['ConditionDecimal']),2);} ?></td>	
	<td><?php echo $PlayerInfo['Suspension']; ?></td>	
	<td><?php echo $PlayerInfo['Height']; ?></td>
	<td><?php echo $PlayerInfo['Weight']; ?></td>
</tr>
</table>
<div class="STHSBlankDiv"></div>
<table class="STHSPHPPlayerStat_Table">
<tr>
	<th>CK</th>
	<th>FG</th>
	<th>DI</th>
	<th>SK</th>
	<th>ST</th>
	<th>EN</th>
	<th>DU</th>
	<th>PH</th>
	<th>FO</th>
	<th>PA</th>
	<th>SC</th>
	<th>DF</th>
	<th>PS</th>
	<th>EX</th>
	<th>LD</th>
	<th>PO</th>
	<th>MO</th>
	<th>OV</th>
</tr><tr>
	<td><?php echo $PlayerInfo['CK']; ?></td>
	<td><?php echo $PlayerInfo['FG']; ?></td>
	<td><?php echo $PlayerInfo['DI']; ?></td>
	<td><?php echo $PlayerInfo['SK']; ?></td>
	<td><?php echo $PlayerInfo['ST']; ?></td>
	<td><?php echo $PlayerInfo['EN']; ?></td>
	<td><?php echo $PlayerInfo['DU']; ?></td>
	<td><?php echo $PlayerInfo['PH']; ?></td>
	<td><?php echo $PlayerInfo['FO']; ?></td>
	<td><?php echo $PlayerInfo['PA']; ?></td>
	<td><?php echo $PlayerInfo['SC']; ?></td>
	<td><?php echo $PlayerInfo['DF']; ?></td>
	<td><?php echo $PlayerInfo['PS']; ?></td>
	<td><?php echo $PlayerInfo['EX']; ?></td>
	<td><?php echo $PlayerInfo['LD']; ?></td>
	<td><?php echo $PlayerInfo['PO']; ?></td>
	<td><?php echo $PlayerInfo['MO']; ?></td>
	<td><?php echo $PlayerInfo['Overall']; ?></td> 
</tr>
</table>
<br />

<div class="tabsmain standard"><ul class="tabmain-links">
<li class="activemain"><a href="#tabmain1"><?php echo $PlayersLang['Information'];?></a></li>
<li><a href="#tabmain2"><?php echo $PlayersLang['ProStat'] . $PlayersLang['Basic'];?></a></li>
<li><a href="#tabmain3"><?php echo $PlayersLang['ProStat'] . $PlayersLang['Advanced'];?></a></li>
<li><a href="#tabmain4"><?php echo $PlayersLang['FarmStat'] . $PlayersLang['Basic'];?></a></li>
<li><a href="#tabmain5"><?php echo $PlayersLang['FarmStat'] . $PlayersLang['Advanced'];?></a></li>
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
	<td><?php echo $PlayerInfo['AgeDate']; ?></td>
	<td><?php echo $PlayerInfo['Country']; ?></td>
	<td><?php if ($PlayerInfo['Rookie']== "True"){ echo "Yes"; }else{echo "No";} ?></td>		
	<td><?php echo $PlayerInfo['Injury']; ?></td>	
	<td><?php echo $PlayerInfo['NumberOfInjury']; ?></td>
	<td><?php echo $PlayerInfo['StarPower']; ?></td>	
</tr>
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
	<td><?php if ($PlayerInfo['AvailableforTrade']== "True"){ echo "Yes"; }else{echo "No";} ?></td>
	<td><?php if ($PlayerInfo['NoTrade']== "True"){ echo "Yes"; }else{echo "No";} ?></td>
	<td><?php if ($PlayerInfo['ForceWaiver']== "True"){ echo "Yes"; }else{echo "No";} ?></td>
	<td><?php if ($PlayerInfo['CanPlayPro']== "True"){ echo "Yes"; }else{echo "No";} ?></td>
	<td><?php if ($PlayerInfo['CanPlayFarm']== "True"){ echo "Yes"; }else{echo "No";} ?></td>	
	<td><?php if ($PlayerInfo['ExcludeSalaryCap']== "True"){ echo "Yes"; }else{echo "No";} ?></td>
	<td><?php if ($PlayerInfo['ProSalaryinFarm']== "True"){ echo "Yes"; }else{echo "No";} ?></td>
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
	<td><?php echo $PlayerInfo['Contract']; ?></td>
	<td><?php if ($PlayerInfo <> Null){echo number_format($PlayerInfo['SalaryAverage'],0) . "$";} ?></td>
	<td><?php if ($PlayerInfo <> Null){echo number_format($PlayerInfo['Salary1'],0) . "$";} ?></td>
	<td><?php if ($PlayerInfo <> Null){echo number_format($PlayerInfo['Salary2'],0) . "$";} ?></td>
	<td><?php if ($PlayerInfo <> Null){echo number_format($PlayerInfo['Salary3'],0) . "$";} ?></td>
	<td><?php if ($PlayerInfo <> Null){echo number_format($PlayerInfo['Salary4'],0) . "$";} ?></td>
</tr>
</table>
<div class="STHSBlankDiv"></div>

<br /><br /></div>
<div class="tabmain" id="tabmain2">
<br /><div class="STHSPHPPlayerStat_TabHeader"><?php echo $PlayersLang['ProStat'] . $PlayersLang['Basic'];?></div><br />
<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $GeneralStatLang['GamePlayed'];?></th>
	<th><?php echo $GeneralStatLang['Goals'];?></th>
	<th><?php echo $GeneralStatLang['Assists'];?></th>
	<th><?php echo $GeneralStatLang['Points'];?></th>
	<th><?php echo $GeneralStatLang['PlusMinus'];?></th>
	<th><?php echo $GeneralStatLang['PenaltyMinutes'];?></th>
	<th><?php echo $GeneralStatLang['MinutesPlayed'];?></th>
	<th><?php echo $GeneralStatLang['AverageMinutesPlayedperGame'];?></th>
</tr><tr>
	<td><?php echo $PlayerProStat['GP']; ?></td>
	<td><?php echo $PlayerProStat['G']; ?></td>
	<td><?php echo $PlayerProStat['A']; ?></td>
	<td><?php echo $PlayerProStat['P']; ?></td>
	<td><?php echo $PlayerProStat['PlusMinus']; ?></td>
	<td><?php echo $PlayerProStat['Pim']; ?></td>
	<td><?php if ($PlayerProStat <> Null){echo Floor($PlayerProStat['SecondPlay']/60);} ?></td>
	<td><?php if ($PlayerProStat <> Null){if ($PlayerProStat['GP'] > "0"){echo number_format($PlayerProStat['SecondPlay']/ 60 /$PlayerProStat['GP'],2 ); } else {echo "0";}}?></td>			
</tr>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $GeneralStatLang['MajorPenaltyMinutes'];?></th>
	<th><?php echo $GeneralStatLang['Hits'];?></th>
	<th><?php echo $GeneralStatLang['HitsReceived'];?></th>
	<th><?php echo $GeneralStatLang['Shots'];?></th>
	<th><?php echo $GeneralStatLang['OwnShotsBlock'];?></th>
	<th><?php echo $GeneralStatLang['OwnShotsMiss'];?></th>
	<th><?php echo $GeneralStatLang['ShootingPercentage'];?></th>
	<th><?php echo $GeneralStatLang['ShotsBlock'];?></th>	
</tr><tr>
	<td><?php echo $PlayerProStat['Pim5']; ?></td>
	<td><?php echo $PlayerProStat['Hits']; ?></td>
	<td><?php echo $PlayerProStat['HitsTook']; ?></td>
	<td><?php echo $PlayerProStat['Shots']; ?></td>
	<td><?php echo $PlayerProStat['OwnShotsBlock']; ?></td>
	<td><?php echo $PlayerProStat['OwnShotsMissGoal']; ?></td>
	<td><?php if ($PlayerProStat <> Null){if ($PlayerProStat['Shots'] > "0"){echo sprintf("%.2f%%", $PlayerProStat['G'] / $PlayerProStat['Shots'] *100 ); } else {echo "0%";}}?></td>
	<td><?php echo $PlayerProStat['ShotsBlock']; ?></td>		
</tr>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $GeneralStatLang['PowerPlay'] . $GeneralStatLang['Goals'];?></th>
	<th><?php echo $GeneralStatLang['PowerPlay'] . $GeneralStatLang['Assists'];?></th>
	<th><?php echo $GeneralStatLang['PowerPlay'] . $GeneralStatLang['Points'];?></th>
	<th><?php echo $GeneralStatLang['PowerPlay'] . $GeneralStatLang['Shots'];?></th>
	<th><?php echo $GeneralStatLang['PowerPlay'] . $GeneralStatLang['MinutesPlayed'];?></th>
</tr><tr>
	<td><?php echo $PlayerProStat['PPG']; ?></td>
	<td><?php echo $PlayerProStat['PPA']; ?></td>
	<td><?php if ($PlayerProStat <> Null){echo $PlayerProStat['PPG'] + $PlayerProStat['PPA'];}?></td>	
	<td><?php echo $PlayerProStat['PPShots']; ?></td>
	<td><?php if ($PlayerProStat <> Null){echo Floor($PlayerProStat['PPSecondPlay']/60);} ?></td>			
</tr>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $GeneralStatLang['ShortHanded'] . $GeneralStatLang['Goals'];?></th>
	<th><?php echo $GeneralStatLang['ShortHanded'] . $GeneralStatLang['Assists'];?></th>
	<th><?php echo $GeneralStatLang['ShortHanded'] . $GeneralStatLang['Points'];?></th>
	<th><?php echo $GeneralStatLang['ShortHanded'] . $GeneralStatLang['Shots'];?></th>
	<th><?php echo $GeneralStatLang['ShortHanded'] . $GeneralStatLang['MinutesPlayed'];?></th>	
</tr><tr>
	<td><?php echo $PlayerProStat['PKG']; ?></td>
	<td><?php echo $PlayerProStat['PKA']; ?></td>
	<td><?php if ($PlayerProStat <> Null){echo $PlayerProStat['PKG'] + $PlayerProStat['PKA'];}?></td>		
	<td><?php echo $PlayerProStat['PKShots']; ?></td>
	<td><?php if ($PlayerProStat <> Null){echo Floor($PlayerProStat['PKSecondPlay']/60);} ?></td>			
</tr>
</table>

<br /><br /></div>
<div class="tabmain" id="tabmain3">
<br /><div class="STHSPHPPlayerStat_TabHeader"><?php echo $PlayersLang['ProStat'] . $PlayersLang['Advanced'];?></div><br />
<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $GeneralStatLang['GameWinningGoals'];?></th>
	<th><?php echo $GeneralStatLang['GameTyingGoals'];?></th>
	<th><?php echo $GeneralStatLang['FaceoffPCT'];?></th>
	<th><?php echo $GeneralStatLang['FaceoffsTaken'];?></th>
	<th><?php echo $GeneralStatLang['GiveAways'];?></th>
	<th><?php echo $GeneralStatLang['TakeAways'];?></th>
	<th><?php echo $GeneralStatLang['EmptyNetGoals'];?></th>
	<th><?php echo $GeneralStatLang['HatTricks'];?></th>
</tr><tr>	
	<td><?php echo $PlayerProStat['GW']; ?></td>
	<td><?php echo $PlayerProStat['GT']; ?></td>	
	<td><?php if ($PlayerProStat <> Null){if ($PlayerProStat['FaceOffTotal'] > "0"){echo sprintf("%.2f%%", $PlayerProStat['FaceOffWon'] / $PlayerProStat['FaceOffTotal'] *100 ); } else {echo "0%";}}?></td>
	<td><?php echo $PlayerProStat['FaceOffTotal']; ?></td>	
	<td><?php echo $PlayerProStat['GiveAway']; ?></td>
	<td><?php echo $PlayerProStat['TakeAway']; ?></td>
	<td><?php echo $PlayerProStat['EmptyNetGoal']; ?></td>	
	<td><?php echo $PlayerProStat['HatTrick']; ?></td>		
</tr>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $GeneralStatLang['Pointper20Minutes'];?></th>
	<th><?php echo $GeneralStatLang['PenaltyShotsGoals'];?></th>
	<th><?php echo $GeneralStatLang['PenaltyShotsTaken'];?></th>
	<th><?php echo $GeneralStatLang['FightWon'];?></th>
	<th><?php echo $GeneralStatLang['FightLost'];?></th>
	<th><?php echo $GeneralStatLang['FightTies'];?></th>
</tr><tr>	
	<td><?php if ($PlayerProStat <> Null){if ($PlayerProStat['SecondPlay'] > "60"){echo number_format($PlayerProStat['P'] / $PlayerProStat['SecondPlay'] * 60 * 20,2 ); } else {echo "0";}}?></td>
	<td><?php echo $PlayerProStat['PenalityShotsScore']; ?></td>	
	<td><?php echo $PlayerProStat['PenalityShotsTotal']; ?></td>
	<td><?php echo $PlayerProStat['FightW']; ?></td>	
	<td><?php echo $PlayerProStat['FightL']; ?></td>
	<td><?php echo $PlayerProStat['FightT']; ?></td>
</tr>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $GeneralStatLang['CurrentGoalScoringStreak'];?></th>
	<th><?php echo $GeneralStatLang['CurrentPointScoringSteak'];?></th>
	<th><?php echo $GeneralStatLang['CurrentGoalScoringSlump'];?></th>
	<th><?php echo $GeneralStatLang['CurrentPointScoringSlump'];?></th>
</tr><tr>	
	<td><?php echo $PlayerInfo['GameInRowWithAGoal']; ?></td>	
	<td><?php echo $PlayerInfo['GameInRowWithAPoint']; ?></td>
	<td><?php echo $PlayerInfo['GameInRowWithOutAGoal']; ?></td>	
	<td><?php echo $PlayerInfo['GameInRowWithOutAPoint']; ?></td>		
</tr>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $GeneralStatLang['NumberTimeStar1'];?></th>
	<th><?php echo $GeneralStatLang['NumberTimeStar2'];?></th>
	<th><?php echo $GeneralStatLang['NumberTimeStar3'];?></th>	
</tr><tr>		
	<td><?php echo $PlayerProStat['Star1']; ?></td>	
	<td><?php echo $PlayerProStat['Star2']; ?></td>
	<td><?php echo $PlayerProStat['Star3']; ?></td>
</tr>
</table>

<br /><br /></div>
<div class="tabmain" id="tabmain4">
<br /><div class="STHSPHPPlayerStat_TabHeader"><?php echo $PlayersLang['FarmStat'] . $PlayersLang['Basic'];?></div><br />
<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $GeneralStatLang['GamePlayed'];?></th>
	<th><?php echo $GeneralStatLang['Goals'];?></th>
	<th><?php echo $GeneralStatLang['Assists'];?></th>
	<th><?php echo $GeneralStatLang['Points'];?></th>
	<th><?php echo $GeneralStatLang['PlusMinus'];?></th>
	<th><?php echo $GeneralStatLang['PenaltyMinutes'];?></th>
	<th><?php echo $GeneralStatLang['MinutesPlayed'];?></th>
	<th><?php echo $GeneralStatLang['AverageMinutesPlayedperGame'];?></th>
</tr><tr>
	<td><?php echo $PlayerFarmStat['GP']; ?></td>
	<td><?php echo $PlayerFarmStat['G']; ?></td>
	<td><?php echo $PlayerFarmStat['A']; ?></td>
	<td><?php echo $PlayerFarmStat['P']; ?></td>
	<td><?php echo $PlayerFarmStat['PlusMinus']; ?></td>
	<td><?php echo $PlayerFarmStat['Pim']; ?></td>
	<td><?php if ($PlayerFarmStat <> Null){echo Floor($PlayerFarmStat['SecondPlay']/60);} ?></td>
	<td><?php if ($PlayerFarmStat <> Null){if ($PlayerFarmStat['GP'] > "0"){echo number_format($PlayerFarmStat['SecondPlay']/ 60 /$PlayerFarmStat['GP'],2 ); } else {echo "0";}}?></td>			
</tr>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $GeneralStatLang['MajorPenaltyMinutes'];?></th>
	<th><?php echo $GeneralStatLang['Hits'];?></th>
	<th><?php echo $GeneralStatLang['HitsReceived'];?></th>
	<th><?php echo $GeneralStatLang['Shots'];?></th>
	<th><?php echo $GeneralStatLang['OwnShotsBlock'];?></th>
	<th><?php echo $GeneralStatLang['OwnShotsMiss'];?></th>
	<th><?php echo $GeneralStatLang['ShootingPercentage'];?></th>
	<th><?php echo $GeneralStatLang['ShotsBlock'];?></th>	
</tr><tr>
	<td><?php echo $PlayerFarmStat['Pim5']; ?></td>
	<td><?php echo $PlayerFarmStat['Hits']; ?></td>
	<td><?php echo $PlayerFarmStat['HitsTook']; ?></td>
	<td><?php echo $PlayerFarmStat['Shots']; ?></td>
	<td><?php echo $PlayerFarmStat['OwnShotsBlock']; ?></td>
	<td><?php echo $PlayerFarmStat['OwnShotsMissGoal']; ?></td>
	<td><?php if ($PlayerFarmStat <> Null){if ($PlayerFarmStat['Shots'] > "0"){echo sprintf("%.2f%%", $PlayerFarmStat['G'] / $PlayerFarmStat['Shots'] *100 ); } else {echo "0%";}}?></td>
	<td><?php echo $PlayerFarmStat['ShotsBlock']; ?></td>		
</tr>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $GeneralStatLang['PowerPlay'] . $GeneralStatLang['Goals'];?></th>
	<th><?php echo $GeneralStatLang['PowerPlay'] . $GeneralStatLang['Assists'];?></th>
	<th><?php echo $GeneralStatLang['PowerPlay'] . $GeneralStatLang['Points'];?></th>
	<th><?php echo $GeneralStatLang['PowerPlay'] . $GeneralStatLang['Shots'];?></th>
	<th><?php echo $GeneralStatLang['PowerPlay'] . $GeneralStatLang['MinutesPlayed'];?></th>
</tr><tr>
	<td><?php echo $PlayerFarmStat['PPG']; ?></td>
	<td><?php echo $PlayerFarmStat['PPA']; ?></td>
	<td><?php if ($PlayerFarmStat <> Null){echo $PlayerFarmStat['PPG'] + $PlayerFarmStat['PPA'];}?></td>	
	<td><?php echo $PlayerFarmStat['PPShots']; ?></td>
	<td><?php if ($PlayerFarmStat <> Null){echo Floor($PlayerFarmStat['PPSecondPlay']/60);} ?></td>			
</tr>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $GeneralStatLang['ShortHanded'] . $GeneralStatLang['Goals'];?></th>
	<th><?php echo $GeneralStatLang['ShortHanded'] . $GeneralStatLang['Assists'];?></th>
	<th><?php echo $GeneralStatLang['ShortHanded'] . $GeneralStatLang['Points'];?></th>
	<th><?php echo $GeneralStatLang['ShortHanded'] . $GeneralStatLang['Shots'];?></th>
	<th><?php echo $GeneralStatLang['ShortHanded'] . $GeneralStatLang['MinutesPlayed'];?></th>		
</tr><tr>
	<td><?php echo $PlayerFarmStat['PKG']; ?></td>
	<td><?php echo $PlayerFarmStat['PKA']; ?></td>
	<td><?php if ($PlayerFarmStat <> Null){echo $PlayerFarmStat['PKG'] + $PlayerFarmStat['PKA'];}?></td>		
	<td><?php echo $PlayerFarmStat['PKShots']; ?></td>
	<td><?php if ($PlayerFarmStat <> Null){echo Floor($PlayerFarmStat['PKSecondPlay']/60);} ?></td>			
</tr>
</table>

<br /><br /></div>
<div class="tabmain" id="tabmain5">
<br /><div class="STHSPHPPlayerStat_TabHeader"><?php echo $PlayersLang['FarmStat'] . $PlayersLang['Advanced'];?></div><br />
<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $GeneralStatLang['GameWinningGoals'];?></th>
	<th><?php echo $GeneralStatLang['GameTyingGoals'];?></th>
	<th><?php echo $GeneralStatLang['FaceoffPCT'];?></th>
	<th><?php echo $GeneralStatLang['FaceoffsTaken'];?></th>
	<th><?php echo $GeneralStatLang['GiveAways'];?></th>
	<th><?php echo $GeneralStatLang['TakeAways'];?></th>
	<th><?php echo $GeneralStatLang['EmptyNetGoals'];?></th>
	<th><?php echo $GeneralStatLang['HatTricks'];?></th>
</tr><tr>	
	<td><?php echo $PlayerFarmStat['GW']; ?></td>
	<td><?php echo $PlayerFarmStat['GT']; ?></td>	
	<td><?php if ($PlayerFarmStat <> Null){if ($PlayerFarmStat['FaceOffTotal'] > "0"){echo sprintf("%.2f%%", $PlayerFarmStat['FaceOffWon'] / $PlayerFarmStat['FaceOffTotal'] *100 ); } else {echo "0%";}}?></td>
	<td><?php echo $PlayerFarmStat['FaceOffTotal']; ?></td>	
	<td><?php echo $PlayerFarmStat['GiveAway']; ?></td>
	<td><?php echo $PlayerFarmStat['TakeAway']; ?></td>
	<td><?php echo $PlayerFarmStat['EmptyNetGoal']; ?></td>	
	<td><?php echo $PlayerFarmStat['HatTrick']; ?></td>		
</tr>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $GeneralStatLang['Pointper20Minutes'];?></th>
	<th><?php echo $GeneralStatLang['PenaltyShotsGoals'];?></th>
	<th><?php echo $GeneralStatLang['PenaltyShotsTaken'];?></th>
	<th><?php echo $GeneralStatLang['FightWon'];?></th>
	<th><?php echo $GeneralStatLang['FightLost'];?></th>
	<th><?php echo $GeneralStatLang['FightTies'];?></th>
</tr><tr>	
	<td><?php if ($PlayerFarmStat <> Null){if ($PlayerFarmStat['SecondPlay'] > "60"){echo number_format($PlayerFarmStat['P'] / $PlayerFarmStat['SecondPlay'] * 60 * 20,2 ); } else {echo "0";}}?></td>
	<td><?php echo $PlayerFarmStat['PenalityShotsScore']; ?></td>	
	<td><?php echo $PlayerFarmStat['PenalityShotsTotal']; ?></td>
	<td><?php echo $PlayerFarmStat['FightW']; ?></td>	
	<td><?php echo $PlayerFarmStat['FightL']; ?></td>
	<td><?php echo $PlayerFarmStat['FightT']; ?></td>
</tr>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $GeneralStatLang['CurrentGoalScoringStreak'];?></th>
	<th><?php echo $GeneralStatLang['CurrentPointScoringSteak'];?></th>
	<th><?php echo $GeneralStatLang['CurrentGoalScoringSlump'];?></th>
	<th><?php echo $GeneralStatLang['CurrentPointScoringSlump'];?></th>
</tr><tr>	
	<td><?php echo $PlayerInfo['GameInRowWithAGoal']; ?></td>	
	<td><?php echo $PlayerInfo['GameInRowWithAPoint']; ?></td>
	<td><?php echo $PlayerInfo['GameInRowWithOutAGoal']; ?></td>	
	<td><?php echo $PlayerInfo['GameInRowWithOutAPoint']; ?></td>		
</tr>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $GeneralStatLang['NumberTimeStar1'];?></th>
	<th><?php echo $GeneralStatLang['NumberTimeStar2'];?></th>
	<th><?php echo $GeneralStatLang['NumberTimeStar3'];?></th>	
</tr><tr>		
	<td><?php echo $PlayerFarmStat['Star1']; ?></td>	
	<td><?php echo $PlayerFarmStat['Star2']; ?></td>
	<td><?php echo $PlayerFarmStat['Star3']; ?></td>
</tr>
</table>
<br /><br /></div>

</div>
</div>
</div>


<?php include "Footer.php";?>
