<?php include "Header.php";?>

<?php
/*
Syntax to call this webpage should be PlayersStat.php?Player=2 where only the number change and it's based on the UniqueID of players.
*/

$Player = (integer)0;
$Row= (integer)0;
$Query = (string)"";
$PlayerName = (string)"Incorrect Player";
if($_GET){$Player = filter_var($_GET['Player'], FILTER_SANITIZE_NUMBER_INT);} 

If (file_exists($DatabaseFile) == false){
	$Player == 0;
	$PlayerName = "Database File Not Found";
}else{
	$db = new SQLite3($DatabaseFile);
}
If ($Player == 0){
	$PlayerInfo = Null;
	$PlayerProStat = Null;
	$PlayerFarmStat = Null;	
	echo "<style type=\"text/css\">.STHS_PlayersInformationStatMain {display:none;}</style>";
}else{
	$Query = "SELECT count(*) AS count FROM PlayerInfo WHERE Number = " . $Player;
	$Result = $db->querySingle($Query,true);
	If ($Result['count'] == 1){
		$Query = "SELECT * FROM PlayerInfo WHERE Number = " . $Player;
		$PlayerInfo = $db->querySingle($Query,true);
		$PlayerName = $PlayerInfo['Name'];	
		$Query = "SELECT * FROM PlayerProStat WHERE Number = " . $Player;
		$PlayerProStat = $db->querySingle($Query,true);
		$Query = "SELECT * FROM PlayerFarmStat WHERE Number = " . $Player;
		$PlayerFarmStat = $db->querySingle($Query,true);
	}else{
		$PlayerName = (string)"Player not found";
		$PlayerInfo = Null;
		$PlayerProStat = Null;
		$PlayerFarmStat = Null;	
		echo "<style type=\"text/css\">.STHS_PlayersInformationStatMain {display:none;}</style>";
	}
}

/*
Not Add Yet : URLLink, GameInRow*, Jersey
*/

?>
<style type="text/css">
.tabmain-content{border-radius:1px;box-shadow:-1px 1px 1px rgba(0,0,0,0.15);background:#FFFFF0;border-style: solid;border-color: #dedede}
</style>
<div class="STHSPHPPlayerStat_PlayerNameHeader"><?php echo $PlayerName . " - " . $PlayerInfo['TeamName'];; ?></div><br />

<div class="STHS_PlayersInformationStatMain">
<br />

<table class="STHSPHPPlayerStat_Table">
<tr>
	<th>Position</th>
	<th>Age</th>
	<th>Condition</th>
	<th>Suspension</th>	
	<th>Height</th>
	<th>Weight</th>
</tr><tr>
	<td><?php
	$Position = (string)"";
	if ($PlayerInfo['PosC']== "True"){if ($Position == ""){$Position = "Center";}else{$Position = $Position . " - Center";}}
	if ($PlayerInfo['PosLW']== "True"){if ($Position == ""){$Position = "Left Wing";}else{$Position = $Position . " - Left Wing";}}
	if ($PlayerInfo['PosRW']== "True"){if ($Position == ""){$Position = "Right Wing";}else{$Position = $Position . " - Right Wing";}}
	if ($PlayerInfo['PosD']== "True"){if ($Position == ""){$Position = "Defence";}else{$Position = $Position . " - Defence";}}
	echo $Position;
	?></td>
	<td><?php echo $PlayerInfo['Age']; ?></td>	
	<td><?php if ($PlayerInfo <> Null){echo number_format($PlayerInfo['ConditionDecimal'],2);} ?></td>	
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
<li class="activemain"><a href="#tabmain1">Information</a></li>
<li><a href="#tabmain2">Pro Stat - Basic</a></li>
<li><a href="#tabmain3">Pro Stat - Advanced</a></li>
<li><a href="#tabmain4">Farm Stat - Basic</a></li>
<li><a href="#tabmain5">Farm Stat - Advanced</a></li>
</ul>
<div class="tabmain-content">
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
	<td><?php echo $PlayerInfo['AgeDate']; ?></td>
	<td><?php echo $PlayerInfo['Country']; ?></td>
	<td><?php if ($PlayerInfo['Rookie']== "True"){ echo "Yes"; }else{echo "No";} ?></td>		
	<td><?php echo $PlayerInfo['Injury']; ?></td>	
	<td><?php echo $PlayerInfo['NumberOfInjury']; ?></td>
	<td><?php echo $PlayerInfo['StarPower']; ?></td>	
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
	<th>Contract Duration</th>
	<th>Salary Average</th>
	<th>Salary Year 1</th>
	<th>Salary Year 2</th>
	<th>Salary Year 3</th>
	<th>Salary Year 4</th>
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
<br /><div class="STHSPHPPlayerStat_TabHeader">Pro Stat - Basic</div><br />
<table class="STHSPHPPlayerStat_Table">
<tr>
	<th>Games Played</th>
	<th>Goals</th>
	<th>Assists</th>
	<th>Points</th>
	<th>Plus/Minus</th>
	<th>Penalty Minutes</th>
	<th>Minutes Played</th>
	<th>Average Minutes Played per Game</th>
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
	<th>Major Penalty Minutes</th>
	<th>Hits</th>
	<th>Hit Received</th>
	<th>Shots</th>
	<th>Own Shots Block</th>
	<th>Own Shots Miss</th>
	<th>Shooting Percentage</th>
	<th>Shots Blocked</th>	
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
	<th>Power Play Goals</th>
	<th>Power Play Assists</th>
	<th>Power Play Points</th>
	<th>Power Play Shots</th>
	<th>Power Play Minutes Played</th>	
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
	<th>Penality Kill Goals</th>
	<th>Penality Kill Assists</th>
	<th>Penality Kill Points</th>
	<th>Penality Kill Shots</th>
	<th>Penality Kill Minutes Played</th>	
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
<br /><div class="STHSPHPPlayerStat_TabHeader">Pro Stat - Advanced</div><br />
<table class="STHSPHPPlayerStat_Table">
<tr>
	<th>Game Winning Goals</th>
	<th>Game Tying Goals</th>
	<th>Face off Percentage</th>
	<th>Face offs Taken</th>
	<th>Give Aways</th>
	<th>Take Aways</th>
	<th>Empty Net Goals</th>
	<th>Hat Tricks</th>
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
	<th>Points per 20 Minutes</th>
	<th>Penalty Shot Goals</th>
	<th>Penalty Shots Taken</th>
	<th>Fight Won</th>
	<th>Fight Lost</th>
	<th>Fight Ties</th>
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
	<th>Current Goal Scoring Streak</th>
	<th>Current Point Scoring Steak</th>
	<th>Current Goal Scoring Slump</th>
	<th>Current Point Scoring Slump</th>
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
	<th>Number of time players was star #1 in a game</th>
	<th>Number of time players was star #2 in a game</th>
	<th>Number of time players was star #3 in a game</th>	
</tr><tr>		
	<td><?php echo $PlayerProStat['Star1']; ?></td>	
	<td><?php echo $PlayerProStat['Star2']; ?></td>
	<td><?php echo $PlayerProStat['Star3']; ?></td>
</tr>
</table>

<br /><br /></div>
<div class="tabmain" id="tabmain4">
<br /><div class="STHSPHPPlayerStat_TabHeader">Farm Stat - Basic</div><br />
<table class="STHSPHPPlayerStat_Table">
<tr>
	<th>Games Played</th>
	<th>Goals</th>
	<th>Assists</th>
	<th>Points</th>
	<th>Plus/Minus</th>
	<th>Penalty Minutes</th>
	<th>Minutes Played</th>
	<th>Average Minutes Played per Game</th>
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
	<th>Major Penalty Minutes</th>
	<th>Hits</th>
	<th>Hit Received</th>
	<th>Shots</th>
	<th>Own Shots Block</th>
	<th>Own Shots Miss</th>
	<th>Shooting Percentage</th>
	<th>Shots Blocked</th>	
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
	<th>Power Play Goals</th>
	<th>Power Play Assists</th>
	<th>Power Play Points</th>
	<th>Power Play Shots</th>
	<th>Power Play Minutes Played</th>	
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
	<th>Penality Kill Goals</th>
	<th>Penality Kill Assists</th>
	<th>Penality Kill Points</th>
	<th>Penality Kill Shots</th>
	<th>Penality Kill Minutes Played</th>	
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
<br /><div class="STHSPHPPlayerStat_TabHeader">Farm Stat - Advanced</div><br />
<table class="STHSPHPPlayerStat_Table">
<tr>
	<th>Game Winning Goals</th>
	<th>Game Tying Goals</th>
	<th>Face off Percentage</th>
	<th>Face offs Taken</th>
	<th>Give Aways</th>
	<th>Take Aways</th>
	<th>Empty Net Goals</th>
	<th>Hat Tricks</th>
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
	<th>Points per 20 Minutes</th>
	<th>Penalty Shot Goals</th>
	<th>Penalty Shots Taken</th>
	<th>Fight Won</th>
	<th>Fight Lost</th>
	<th>Fight Ties</th>
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
	<th>Current Goal Scoring Streak</th>
	<th>Current Point Scoring Steak</th>
	<th>Current Goal Scoring Slump</th>
	<th>Current Point Scoring Slump</th>
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
	<th>Number of time players was star #1 in a game</th>
	<th>Number of time players was star #2 in a game</th>
	<th>Number of time players was star #3 in a game</th>	
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
