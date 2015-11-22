<?php include "Header.php";?>
<?php $DatabaseFile = (string)"SIMON2-STHS.db"; ?>
<?php
/*
Syntax to call this webpage should be ProTeam.php?Team=2 where only the number change and it's based on the Tean Number Field.
*/

$Team = (integer)0;
$OtherTeam = (integer)0;
$Query = (string)"";
$TeamName = (string)"Incorrect Team";
if($_GET){$Team = filter_var($_GET['Team'], FILTER_SANITIZE_NUMBER_INT);} 

If (file_exists($DatabaseFile) == false){
	$Team == 0;
	$TeamName = "Database File Not Found";
}else{
	$db = new SQLite3($DatabaseFile);
}
If ($Team == 0){
	$TeamProInfo = Null;
	$TeamProFinance = Null;	
	$TeamProStat = Null;
	echo "<style type=\"text/css\">.STHSPHPTeamStat_Main {display:none;}</style>";
}else{
	$Query = "SELECT count(*) AS count FROM TeamProInfo WHERE Number = " . $Team;
	$Result = $db->querySingle($Query,true);
	If ($Result['count'] == 1){
		$Query = "SELECT * FROM TeamProInfo WHERE Number = " . $Team;
		$TeamInfo = $db->querySingle($Query,true);
		$TeamName = $TeamInfo['Name'];	
		$Query = "SELECT * FROM TeamProFinance WHERE Number = " . $Team;
		$TeamProFinance = $db->querySingle($Query,true);
		$Query = "SELECT * FROM TeamProStat WHERE Number = " . $Team;
		$TeamProStat = $db->querySingle($Query,true);
		$Query = "SELECT * FROM PlayerInfo WHERE Team = " . $Team . " AND Status1 >= 2 Order By PosD, Overall DESC";
		$PlayerRoster = $db->query($Query);
		$Query = "SELECT * FROM PlayerInfo WHERE Team = " . $Team . " AND Status1 >= 2 Order By Name";
		$PlayerInfo = $db->query($Query);		
		$Query = "SELECT PlayerProStat.*, PlayerInfo.PosC, PlayerInfo.PosLW, PlayerInfo.PosRW, PlayerInfo.PosD FROM PlayerInfo INNER JOIN PlayerProStat ON PlayerInfo.Number = PlayerProStat.Number WHERE (((PlayerInfo.Team)=" . $Team . ") AND ((PlayerInfo.Status1)>=2)  AND ((PlayerProStat.GP)>0)) ORDER BY PlayerProStat.P DESC";
		$PlayerStat = $db->query($Query);
		$Query = "SELECT GoalerProStat.* FROM GoalerInfo INNER JOIN GoalerProStat ON GoalerInfo.Number = GoalerProStat.Number WHERE (((GoalerInfo.Team)=" . $Team . ") AND ((GoalerInfo.Status1)>=2)  AND ((GoalerProStat.GP)>0)) ORDER BY GoalerProStat.W DESC";
		$GoalieStat = $db->query($Query);
		$Query = "SELECT * FROM GoalerInfo WHERE Team = " . $Team . " AND Status1 >= 2 Order By Overall DESC";
		$GoalieRoster = $db->query($Query);
		$Query = "SELECT * FROM GoalerInfo WHERE Team = " . $Team . " AND Status1 >= 2 Order By Name";
		$GoalieInfo = $db->query($Query);		
		$Query = "SELECT * FROM SchedulePro WHERE (VisitorTeam = " . $Team . " OR HomeTeam = " . $Team . ") ORDER BY GameNumber";
		$TeamSchedule = $db->query($Query);
		$Query = "SELECT * FROM ProRivalryInfo WHERE Team1 = " . $Team . " Order By TEAM2";
		$RivalryInfo = $db->query($Query);		
		$Query = "Select PointSystemSO from LeagueGeneral";
		$LeagueGeneral = $db->querySingle($Query,true);
	}else{
		$TeamName = (string)"Team not found";
		$TeamInfo = Null;
		$TeamProStat = Null;
		$TeamFarmStat = Null;	
		echo "<style type=\"text/css\">.STHSPHPTeamStat_Main {display:none;}</style>";
	}
}
?>
<div class="STHSPHPPlayerStat_PlayerNameHeader"><?php echo $TeamName;?></div><br />
<script type="text/javascript">$(function(){$("table").basictablesorter()});</script>

<div class="STHSPHPTeamStat_Main">
<br />
<div class="tabsmain standard"><ul class="tabmain-links">
<li class="activemain"><a href="#tabmain1">Roster</a></li>
<li><a href="#tabmain2">Scoring</a></li>
<li><a href="#tabmain3">Players Info</a></li>
<li><a href="#tabmain4">Lines</a></li>
<li><a href="#tabmain5">Team Stats</a></li>
<li><a href="#tabmain6">Schedule</a></li>
<li><a href="#tabmain7">Finance</a></li>
<li><a href="#tabmain8">Future</a></li>
<li><a href="#tabmain9">History</a></li>
<li><a href="#tabmain10">Injury / Suspension</a></li>
</ul>
<div style="border-radius:1px;box-shadow:-1px 1px 1px rgba(0,0,0,0.15);background:#FFFFF0;border-style: solid;border-color: #dedede">
<div class="tabmain" id="tabmain1">

<table class="basictablesorter STHSTeam_PlayersRosterTable"><thead><tr>
<th class="STHSW25">#</th><th class="STHSW200">Player Name</th><th class="STHSW10">C</th><th class="STHSW10">L</th><th class="STHSW10">R</th><th class="STHSW10">D</th><th class="STHSW25">CON</th><th class="STHSW25">CK</th><th class="STHSW25">FG</th><th class="STHSW25">DI</th><th class="STHSW25">SK</th><th class="STHSW25">ST</th><th class="STHSW25">EN</th><th class="STHSW25">DU</th><th class="STHSW25">PH</th><th class="STHSW25">FO</th><th class="STHSW25">PA</th><th class="STHSW25">SC</th><th class="STHSW25">DF</th><th class="STHSW25">PS</th><th class="STHSW25">EX</th><th class="STHSW25">LD</th><th class="STHSW25">PO</th><th class="STHSW25">MO</th><th class="STHSW25">OV</th><th class="STHSW25">TA</th><th class="STHSW25">SP</th><th class="STHSW25">Age</th></tr></thead>

<?php
$LoopCount = (integer)0;
while ($Row = $PlayerRoster ->fetchArray()) {
	$LoopCount +=1;
?>
	<tr>
		<td><?php echo $LoopCount; ?></td>
		<td><a href="PlayersStats.php?Player=<?php echo $Row['Number'];?>"><?php echo $Row['Name'];?></a></td>
		<td><?php if ($Row['PosC']== "True"){ echo "X";}?></td>
		<td><?php if ($Row['PosLW']== "True"){ echo "X";}?></td>
		<td><?php if ($Row['PosRW']== "True"){ echo "X";}?></td>
		<td><?php if ($Row['PosD']== "True"){ echo "X";}?></td>		
		<td><?php if ($Row <> Null){echo number_format($Row['ConditionDecimal'],2);} ?></td>	
		<td><?php echo $Row['CK']; ?></td>
		<td><?php echo $Row['FG']; ?></td>
		<td><?php echo $Row['DI']; ?></td>
		<td><?php echo $Row['SK']; ?></td>
		<td><?php echo $Row['ST']; ?></td>
		<td><?php echo $Row['EN']; ?></td>
		<td><?php echo $Row['DU']; ?></td>
		<td><?php echo $Row['PH']; ?></td>
		<td><?php echo $Row['FO']; ?></td>
		<td><?php echo $Row['PA']; ?></td>
		<td><?php echo $Row['SC']; ?></td>
		<td><?php echo $Row['DF']; ?></td>
		<td><?php echo $Row['PS']; ?></td>
		<td><?php echo $Row['EX']; ?></td>
		<td><?php echo $Row['LD']; ?></td>
		<td><?php echo $Row['PO']; ?></td>
		<td><?php echo $Row['MO']; ?></td>
		<td><?php echo $Row['Overall']; ?></td> 
		<td><?php if ($Row['AvailableforTrade']== "True"){ echo "X";}?></td>
		<td><?php echo $Row['StarPower'];?></td>
		<td><?php echo $Row['Age']; ?></td>			
	</tr>
<?php 
}
?>
</table>

<table class="basictablesorter STHSTeam_GoaliesRosterTable"><thead><tr>
<th class="STHSW200">Goalie Name</th><th class="STHSW25">PO</th><th class="STHSW25">CON</th><th class="STHSW25">SK</th><th class="STHSW25">DU</th><th class="STHSW25">EN</th><th class="STHSW25">SZ</th><th class="STHSW25">AG</th><th class="STHSW25">RB</th><th class="STHSW25">SC</th><th class="STHSW25">HS</th><th class="STHSW25">RT</th><th class="STHSW25">PH</th><th class="STHSW25">PS</th><th class="STHSW25">EX</th><th class="STHSW25">LD</th><th class="STHSW25">PO</th><th class="STHSW25">MO</th><th class="STHSW25">OV</th><th class="STHSW25">TA</th><th class="STHSW25">SP</th><th class="STHSW25">Age</th></tr></thead>

<?php
while ($Row = $GoalieRoster ->fetchArray()) {
?>
	<tr>
		<td><a href="GoaliesStats.php?Player=<?php echo $Row['Number'];?>"><?php echo $Row['Name'];?></a></td>
		<td>G</td>
		<td><?php if ($Row <> Null){echo number_format($Row['ConditionDecimal'],2);} ?></td>	
		<td><?php echo $Row['SK']; ?></td>
		<td><?php echo $Row['DU']; ?></td>
		<td><?php echo $Row['EN']; ?></td>
		<td><?php echo $Row['SZ']; ?></td>
		<td><?php echo $Row['AG']; ?></td>
		<td><?php echo $Row['RB']; ?></td>
		<td><?php echo $Row['SC']; ?></td>
		<td><?php echo $Row['HS']; ?></td>
		<td><?php echo $Row['RT']; ?></td>
		<td><?php echo $Row['PH']; ?></td>
		<td><?php echo $Row['PS']; ?></td>
		<td><?php echo $Row['EX']; ?></td>
		<td><?php echo $Row['LD']; ?></td>
		<td><?php echo $Row['PO']; ?></td>
		<td><?php echo $Row['MO']; ?></td>
		<td><?php echo $Row['Overall']; ?></td> 
		<td><?php if ($Row['AvailableforTrade']== "True"){ echo "X";}?></td>
		<td><?php echo $Row['StarPower'];?></td>
		<td><?php echo $Row['Age']; ?></td>			
	</tr>
<?php 
}
?>
</table>

<br /><br /></div>
<div class="tabmain" id="tabmain2">

<table class="basictablesorter STHSScoring_PlayersTable1"><thead><tr><th class="STHSW200">Player Name</th><th class="STHSW10">F</th><th class="STHSW10">D</th><th class="STHSW25">GP</th><th class="STHSW25">G</th><th class="STHSW25">A</th><th class="STHSW25">P</th><th class="STHSW25">+/-</th><th class="STHSW25">PIM</th><th class="STHSW25">HIT</th><th class="STHSW25">SHT</th><th class="STHSW55">SHT %</th><th class="STHSW25">SB</th><th class="STHSW35">MP</th><th class="STHSW35">AMG</th><th class="STHSW25">PPG</th><th class="STHSW25">PPA</th><th class="STHSW25">PPP</th><th class="STHSW25">PKG</th><th class="STHSW25">PKA</th><th class="STHSW25">PKP</th><th class="STHSW25">FO%</th><th class="STHSW25">P/20</th></tr></thead>

<?php while ($Row = $PlayerStat ->fetchArray()) { ?>
	<tr>
		<td><a href="PlayersStats.php?Player=<?php echo $Row['Number'];?>"><?php echo $Row['Name'];?></a></td>
		<td><?php if ($Row['PosC']== "True" OR $Row['PosLW']== "True" OR $Row['PosRW']== "True"){ echo "X";}?></td>
		<td><?php if ($Row['PosD']== "True"){ echo "X";}?></td>			
		<td><?php echo $Row['GP']; ?></td>
		<td><?php echo $Row['G']; ?></td>
		<td><?php echo $Row['A']; ?></td>
		<td><?php echo $Row['P']; ?></td>
		<td><?php echo $Row['PlusMinus']; ?></td>
		<td><?php echo $Row['Pim']; ?></td>
		<td><?php echo $Row['Hits']; ?></td>
		<td><?php echo $Row['Shots']; ?></td>
		<td><?php if ($Row['Shots'] > 0){echo number_Format($Row['G'] / $Row['Shots'] * 100,2) . "%" ;} else { echo "0.00%";}?></td>		
		<td><?php echo $Row['ShotsBlock']; ?></td>
		<td><?php echo Floor($Row['SecondPlay']/60); ?></td>
		<td><?php if ($Row['GP'] > 0){echo number_Format($Row['SecondPlay'] / 60 / $Row['GP'] ,0) ;} else { echo "0";}?></td>		
		<td><?php echo $Row['PPG']; ?></td>
		<td><?php echo $Row['PPA']; ?></td>
		<td><?php echo $Row['PPG'] + $Row['PPA']; ?></td>
		<td><?php echo $Row['PKG']; ?></td>
		<td><?php echo $Row['PKA']; ?></td>
		<td><?php echo $Row['PKG'] + $Row['PKA']; ?></td>
		<td><?php if ($Row['FaceOffTotal'] > 0){echo number_Format($Row['FaceOffWon'] / $Row['FaceOffTotal'] ,0) . "%" ;} { echo "0.00%"; }  ?></td>			
		<td><?php if ($Row['SecondPlay'] > 0){echo number_Format($Row['P'] / $Row['SecondPlay'] * 60 * 20,2) ;} else { echo "0";}?></td>
	</tr>
<?php 
}
?>
</table>

<table class="basictablesorter STHSScoring_GoaliesTable"><thead><tr><th class="STHSW200">Goalie Name</th><th class="STHSW25">GP</th><th class="STHSW25">W</th><th class="STHSW25">L</th><th class="STHSW25">OTL</th><th class="STHSW50">PCT</th><th class="STHSW50">GAA</th><th class="STHSW50">MP</th><th class="STHSW25">PIM</th><th class="STHSW25">SO</th><th class="STHSW25">GA</th><th class="STHSW45">SA</th><th class="STHSW45">SAR</th><th class="STHSW25">A</th><th class="STHSW25">EG</th><th class="STHSW50">PS %</th><th class="STHSW25">PSA</th></tr></thead>

<?php while ($Row = $GoalieStat ->fetchArray()) { ?>
	<tr>
		<td><a href="GoaliesStats.php?Goalie=<?php echo $Row['Number'];?>"><?php echo $Row['Name'];?></a></td>
		<td><?php echo $Row['GP']; ?></td>
		<td><?php echo $Row['W']; ?></td>
		<td><?php echo $Row['L']; ?></td>
		<td><?php echo $Row['OTL']; ?></td>
		<td><?php  If ($Row['SA'] > 0){echo number_Format(($Row['SA'] - $Row['GA']) / $Row['SA'],3);}else{echo number_Format("0",3);}?></td>
		<td><?php  If ($Row['SecondPlay'] > 0){echo number_Format($Row['GA'] / $Row['SecondPlay'] * 3600,3);}else{echo number_Format("0",2);}?></td>
		<td><?php if ($Row <> Null){echo Floor($Row['SecondPlay']/60);} ?></td>	
		<td><?php echo $Row['Pim']; ?></td>
		<td><?php echo $Row['Shootout']; ?></td>
		<td><?php echo $Row['GA']; ?></td>
		<td><?php echo $Row['SA']; ?></td>
		<td><?php echo $Row['SARebound']; ?></td>
		<td><?php echo $Row['A']; ?></td>
		<td><?php echo $Row['EmptyNetGoal']; ?></td>			
		<td><?php  If ($Row['PenalityShotsShots'] > 0){echo number_Format(($Row['PenalityShotsShots'] - $Row['PenalityShotsGoals']) / $Row['PenalityShotsShots'],3);}else{echo number_Format("0",3);}?></td>
		<td><?php echo $Row['PenalityShotsShots']; ?></td>
	
	</tr>
<?php 
}
?>
</table>

<br /><br /></div>
<div class="tabmain  active" id="tabmain3">
<table class="basictablesorter"><thead><tr>
<th class="STHSW200">Player Name</th><th class="STHSW65">PO</th><th class="STHSW25">Age</th><th class="STHSW65">Birthday</th><th class="STHSW65">Rookie</th><th class="STHSW65">Weight</th><th class="STHSW55">Height</th><th class="STHSW65">No Trade</th><th class="STHSW75">Force Waiver</th><th class="STHSW65">Contract</th><th class="STHSW100">Type</th><th class="STHSW85">Salary Ave</th><th class="STHSW85">Salary #1</th></tr></thead>

<?php while ($Row = $PlayerInfo ->fetchArray()) { ?>
	<tr>
		<td><a href="PlayersStats.php?Player=<?php echo $Row['Number'];?>"><?php echo $Row['Name'];?></a></td>
		<td><?php
		$Position = (string)"";
		if ($Row['PosC']== "True"){if ($Position == ""){$Position = "C";}else{$Position = $Position . "/C";}}
		if ($Row['PosLW']== "True"){if ($Position == ""){$Position = "LW";}else{$Position = $Position . "/LW";}}
		if ($Row['PosRW']== "True"){if ($Position == ""){$Position = "RW";}else{$Position = $Position . "/RW";}}
		if ($Row['PosD']== "True"){if ($Position == ""){$Position = "D";}else{$Position = $Position . "/D";}}
		echo $Position;
		?></td>		
		<td><?php echo $Row['Age']; ?></td>
		<td><?php echo $Row['AgeDate']; ?></td>
		<td><?php if ($Row['Rookie']== "True"){ echo "Yes"; }else{echo "No";} ?></td>		
		<td><?php echo $Row['Weight']; ?></td>
		<td><?php echo $Row['Height']; ?></td>		
		<td><?php if ($Row['NoTrade']== "True"){ echo "Yes"; }else{echo "No";} ?></td>
		<td><?php if ($Row['ForceWaiver']== "True"){ echo "Yes"; }else{echo "No";} ?></td>
		<td><?php echo $Row['Contract']; ?></td>
		<td><?php if ($Row['CanPlayPro']== "True" AND $Row['CanPlayFarm']== "True"){
			echo "Pro &amp; Farm"; 
		}elseif($Row['CanPlayPro']== "True" AND $Row['CanPlayFarm']== "False"){
			echo "Pro Only";
		}else{
			echo "Farm Only";
		}?></td>
		<td><?php if ($Row <> Null){echo number_format($Row['SalaryAverage'],0) . "$";} ?></td>
		<td><?php if ($Row <> Null){echo number_format($Row['Salary1'],0) . "$";} ?></td>		
	</tr>
<?php 
}
?>

<?php while ($Row = $GoalieInfo ->fetchArray()) { ?>
	<tr>
		<td><a href="GoaliesStats.php?Goalie=<?php echo $Row['Number'];?>"><?php echo $Row['Name'];?></a></td>
		<td>G</td>		
		<td><?php echo $Row['Age']; ?></td>
		<td><?php echo $Row['AgeDate']; ?></td>
		<td><?php if ($Row['Rookie']== "True"){ echo "Yes"; }else{echo "No";} ?></td>		
		<td><?php echo $Row['Weight']; ?></td>
		<td><?php echo $Row['Height']; ?></td>		
		<td><?php if ($Row['NoTrade']== "True"){ echo "Yes"; }else{echo "No";} ?></td>
		<td><?php if ($Row['ForceWaiver']== "True"){ echo "Yes"; }else{echo "No";} ?></td>
		<td><?php echo $Row['Contract']; ?></td>
		<td><?php if ($Row['CanPlayPro']== "True" AND $Row['CanPlayFarm']== "True"){
			echo "Pro &amp; Farm"; 
		}elseif($Row['CanPlayPro']== "True" AND $Row['CanPlayFarm']== "False"){
			echo "Pro Only";
		}else{
			echo "Farm Only";
		}?></td>
		<td><?php if ($Row <> Null){echo number_format($Row['SalaryAverage'],0) . "$";} ?></td>
		<td><?php if ($Row <> Null){echo number_format($Row['Salary1'],0) . "$";} ?></td>		
	</tr>
<?php 
}
?>

</table>

<br /><br /></div>
<div class="tabmain" id="tabmain4">
Lines

<br /><br /></div>
<div class="tabmain" id="tabmain5">

<br />
<table class="STHSPHPTeamStat_Table"><tr>
<th colspan="3"></th><th colspan="10">Total Players</th></tr><tr>
<th class="STHSW25">Game Played</th><th class="STHSW25">Points</th><th class="STHSW25">Streak</th><th class="STHSW25">Goals</th><th class="STHSW25">Assists</th><th class="STHSW25">Point</th><th class="STHSW25">Shots For</th><th class="STHSW25">Shots Against</th><th class="STHSW25">Shots Block</th><th class="STHSW25">Penality Minutes</th><th class="STHSW25">Hits</th><th class="STHSW25">Empty Net Goals</th><th class="STHSW25">Shutouts</th></tr>
	<tr>
		<td><?php echo $TeamProStat['GP']; ?></td>
		<td><?php echo $TeamProStat['Points']; ?></td>
		<td><?php echo $TeamProStat['Streak']; ?></td>
		<td><?php echo $TeamProStat['TotalGoal']; ?></td>
		<td><?php echo $TeamProStat['TotalAssist']; ?></td>
		<td><?php echo $TeamProStat['TotalPoint']; ?></td>
		<td><?php echo $TeamProStat['ShotsFor']; ?></td>
		<td><?php echo $TeamProStat['ShotsAga']; ?></td>
		<td><?php echo $TeamProStat['ShotsBlock']; ?></td>		
		<td><?php echo $TeamProStat['Pim']; ?></td>
		<td><?php echo $TeamProStat['Hits']; ?></td>
		<td><?php echo $TeamProStat['EmptyNetGoal']; ?></td>
		<td><?php echo $TeamProStat['Shutouts']; ?></td>		
	</tr>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPTeamStat_Table"><tr><th colspan="<?php if($LeagueGeneral['PointSystemSO']=="True"){echo "9";}else{echo "8";}?>">All Games</th></tr><tr>
<th class="STHSW25">GP</th><th class="STHSW25">W</th><th class="STHSW25">L</th><th class="STHSW25">OTW</th><th class="STHSW25">OTL</th>
<?php if($LeagueGeneral['PointSystemSO']=="True"){	echo "<th class=\"STHSW25\">SOW</th><th class=\"STHSW25\">SOL</th>";}else{	echo "<th class=\"STHSW25\">T</th>";}?>
<th class="STHSW25">GF</th><th class="STHSW25">GA</th></tr>
	<tr>
		<td><?php echo $TeamProStat['GP']; ?></td>
		<td><?php echo $TeamProStat['W']; ?></td>
		<td><?php echo $TeamProStat['L']; ?></td>
		<td><?php echo $TeamProStat['OTW']; ?></td>
		<td><?php echo $TeamProStat['OTL']; ?></td>
		<?php if($LeagueGeneral['PointSystemSO']=="True"){	
		echo "<td>" . $TeamProStat['SOW'] . "</td>";
		echo "<td>" . $TeamProStat['SOL'] . "</td>";
		}else{	
		echo "<td>" . $TeamProStat['T'] . "</td>";}?>
		<td><?php echo $TeamProStat['GF']; ?></td>
		<td><?php echo $TeamProStat['GA']; ?></td>
	</tr>
</table>
<div class="STHSBlankDiv"></div>	

<table class="STHSPHPTeamStat_Table"><tr><th colspan="<?php if($LeagueGeneral['PointSystemSO']=="True"){echo "9";}else{echo "8";}?>">Home Games</th></tr><tr>
<th class="STHSW25">GP</th><th class="STHSW25">W</th><th class="STHSW25">L</th><th class="STHSW25">OTW</th><th class="STHSW25">OTL</th>
<?php if($LeagueGeneral['PointSystemSO']=="True"){	echo "<th class=\"STHSW25\">SOW</th><th class=\"STHSW25\">SOL</th>";}else{	echo "<th class=\"STHSW25\">T</th>";}?>
<th class="STHSW25">GF</th><th class="STHSW25">GA</th></tr>
	<tr>
		<td><?php echo $TeamProStat['HomeGP']; ?></td>
		<td><?php echo $TeamProStat['HomeW']; ?></td>
		<td><?php echo $TeamProStat['HomeL']; ?></td>
		<td><?php echo $TeamProStat['HomeOTW']; ?></td>
		<td><?php echo $TeamProStat['HomeOTL']; ?></td>
		<?php if($LeagueGeneral['PointSystemSO']=="True"){	
		echo "<td>" . $TeamProStat['HomeSOW'] . "</td>";
		echo "<td>" . $TeamProStat['HomeSOL'] . "</td>";
		}else{	
		echo "<td>" . $TeamProStat['HomeT'] . "</td>";}?>
		<td><?php echo $TeamProStat['HomeGF']; ?></td>
		<td><?php echo $TeamProStat['HomeGA']; ?></td>
	</tr>
</table>
<div class="STHSBlankDiv"></div>	
	
<table class="STHSPHPTeamStat_Table"><tr><th colspan="<?php if($LeagueGeneral['PointSystemSO']=="True"){echo "9";}else{echo "8";}?>">Visitor Games</th></tr><tr>
<th class="STHSW25">GP</th><th class="STHSW25">W</th><th class="STHSW25">L</th><th class="STHSW25">OTW</th><th class="STHSW25">OTL</th>
<?php if($LeagueGeneral['PointSystemSO']=="True"){	echo "<th class=\"STHSW25\">SOW</th><th class=\"STHSW25\">SOL</th>";}else{	echo "<th class=\"STHSW25\">T</th>";}?>
<th class="STHSW25">GF</th><th class="STHSW25">GA</th></tr>
	<tr>
		<td><?php echo ($TeamProStat['GP'] - $TeamProStat['HomeGP']); ?></td>
		<td><?php echo ($TeamProStat['W'] - $TeamProStat['HomeW']); ?></td>
		<td><?php echo ($TeamProStat['L'] - $TeamProStat['HomeL']); ?></td>
		<td><?php echo ($TeamProStat['OTW'] - $TeamProStat['HomeOTW']); ?></td>
		<td><?php echo ($TeamProStat['OTL'] - $TeamProStat['HomeOTL']); ?></td>
		<?php if($LeagueGeneral['PointSystemSO']=="True"){	
		echo "<td>" . ($TeamProStat['SOW'] - $TeamProStat['HomeSOW']) . "</td>";
		echo "<td>" . ($TeamProStat['SOL'] - $TeamProStat['HomeSOL']) . "</td>";
		}else{	
		echo "<td>" . ($TeamProStat['T'] - $TeamProStat['HomeT']) . "</td>";}?>
		<td><?php echo ($TeamProStat['GF'] - $TeamProStat['HomeGF']); ?></td>
		<td><?php echo ($TeamProStat['GA'] - $TeamProStat['HomeGA']); ?></td>
	</tr>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPTeamStat_Table"><tr><th colspan="<?php if($LeagueGeneral['PointSystemSO']=="True"){echo "6";}else{echo "5";}?>">Last 10 Games</th></tr><tr>
<th class="STHSW25">W</th><th class="STHSW25">L</th><th class="STHSW25">OTW</th><th class="STHSW25">OTL</th>
<?php if($LeagueGeneral['PointSystemSO']=="True"){	echo "<th class=\"STHSW25\">SOW</th><th class=\"STHSW25\">SOL</th>";}else{	echo "<th class=\"STHSW25\">T</th>";}?></tr>
	<tr>
		<td><?php echo $TeamProStat['Last10W']; ?></td>
		<td><?php echo $TeamProStat['Last10L']; ?></td>
		<td><?php echo $TeamProStat['Last10OTW']; ?></td>
		<td><?php echo $TeamProStat['Last10OTL']; ?></td>
		<?php if($LeagueGeneral['PointSystemSO']=="True"){	
		echo "<td>" . $TeamProStat['Last10SOW'] . "</td>";
		echo "<td>" . $TeamProStat['Last10SOL'] . "</td>";
		}else{	
		echo "<td>" . $TeamProStat['Last10T'] . "</td>";}?>
	</tr>
</table>
<div class="STHSBlankDiv"></div>	

<table class="STHSPHPTeamStat_Table"><tr>
<th class="STHSW25">Power Play Attemps</th><th class="STHSW25">Power Play Goals</th><th class="STHSW25">Power Play %</th><th class="STHSW25">Penality Kill Attemps</th><th class="STHSW25">Penality Kill Goals Against</th><th class="STHSW25">Penality Kill %</th><th class="STHSW25">Penality Kill Goals For</th></tr>
	<tr>
		<td><?php echo $TeamProStat['PPAttemp']; ?></td>
		<td><?php echo $TeamProStat['PPGoal']; ?></td>
		<td><?php if ($TeamProStat['PPAttemp'] > 0){echo number_Format($TeamProStat['PPGoal'] / $TeamProStat['PPAttemp'] * 100,2) ;} else { echo "0.00%"; }  ?></td>
		<td><?php echo $TeamProStat['PKAttemp']; ?></td>
		<td><?php echo $TeamProStat['PKGoalGA']; ?></td>
		<td><?php if ($TeamProStat['PKAttemp'] > 0){echo number_Format(($TeamProStat['PKAttemp'] - $TeamProStat['PKGoalGA']) / $TeamProStat['PKAttemp'] * 100,2);} else { echo "0.00%"; }  ?></td>	<td><?php echo $TeamProStat['PKGoalGF']; ?></td>		
	</tr>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPTeamStat_Table"><tr>
<th class="STHSW25">Shots 1 Period</th><th class="STHSW25">Shots 2 Period</th><th class="STHSW25">Shots 3 Period</th><th class="STHSW25">Shots 4+ Period</th><th class="STHSW25">Goals 1 Period</th><th class="STHSW25">Goals 2 Period</th><th class="STHSW25">Goals 3 Period</th><th class="STHSW25">Goals 4+ Period</th></tr>
	<tr>
		<td><?php echo $TeamProStat['ShotsPerPeriod1']; ?></td>
		<td><?php echo $TeamProStat['ShotsPerPeriod2']; ?></td>
		<td><?php echo $TeamProStat['ShotsPerPeriod3']; ?></td>
		<td><?php echo $TeamProStat['ShotsPerPeriod4']; ?></td>
		<td><?php echo $TeamProStat['GoalsPerPeriod1']; ?></td>		
		<td><?php echo $TeamProStat['GoalsPerPeriod2']; ?></td>	
		<td><?php echo $TeamProStat['GoalsPerPeriod3']; ?></td>	
		<td><?php echo $TeamProStat['GoalsPerPeriod4']; ?></td>	
	</tr>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPTeamStat_Table"><tr>
<th colspan="9">Face Offs</th></tr><tr>
<th class="STHSW25">Won Offensif Zone</th><th class="STHSW25">Won Offensif Total</th><th class="STHSW25">Won Offensif %</th><th class="STHSW25">Won Defensif Zone</th><th class="STHSW25">Won Defensif Total</th><th class="STHSW25">Won Defensif %</th><th class="STHSW25">Won Neutral Zone</th><th class="STHSW25">Won Neutral Total</th><th class="STHSW25">Won Neutral %</th></tr>
	<tr>
		<td><?php echo $TeamProStat['FaceOffWonOffensifZone']; ?></td>
		<td><?php echo $TeamProStat['FaceOffTotalOffensifZone']; ?></td>		
		<td><?php if ($TeamProStat['FaceOffTotalOffensifZone'] > 0){echo number_Format($TeamProStat['FaceOffWonOffensifZone'] / $TeamProStat['FaceOffTotalOffensifZone'] * 100,2) ;} else { echo "0.00%"; }  ?></td>
		<td><?php echo $TeamProStat['FaceOffWonDefensifZone']; ?></td>
		<td><?php echo $TeamProStat['FaceOffTotalDefensifZone']; ?></td>
		<td><?php if ($TeamProStat['FaceOffTotalDefensifZone'] > 0){echo number_Format($TeamProStat['FaceOffWonDefensifZone'] / $TeamProStat['FaceOffTotalDefensifZone'] * 100,2) ;} else { echo "0.00%"; }  ?></td>
		<td><?php echo $TeamProStat['FaceOffWonNeutralZone']; ?></td>	
		<td><?php echo $TeamProStat['FaceOffTotalNeutralZone']; ?></td>	
		<td><?php if ($TeamProStat['FaceOffTotalNeutralZone'] > 0){echo number_Format($TeamProStat['FaceOffWonNeutralZone'] / $TeamProStat['FaceOffTotalNeutralZone'] * 100,2) ;} else { echo "0.00%"; }  ?></td>	
	</tr>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPTeamStat_Table"><tr>
<th colspan="6">Puck Time</th></tr><tr>
<th class="STHSW25">In Offensif Zone</th><th class="STHSW25">Control In Offensif Zone</th><th class="STHSW25">In Defensif Zone</th><th class="STHSW25">Control In Defensif Zone</th><th class="STHSW25">In Neutral Zone</th><th class="STHSW25">Control In Neutral Zone</th>
</tr>
	<tr>
		<td><?php echo Floor($TeamProStat['PuckTimeInZoneOF']/60); ?></td>
		<td><?php echo Floor($TeamProStat['PuckTimeControlinZoneOF']/60); ?></td>
		<td><?php echo Floor($TeamProStat['PuckTimeInZoneDF']/60); ?></td>
		<td><?php echo Floor($TeamProStat['PuckTimeControlinZoneDF']/60); ?></td>
		<td><?php echo Floor($TeamProStat['PuckTimeInZoneNT']/60); ?></td>		
		<td><?php echo Floor($TeamProStat['PuckTimeControlinZoneNT']/60); ?></td>	
	</tr>
</table>
<div class="STHSBlankDiv"></div>

<br /><br /></div>
<div class="tabmain" id="tabmain6">

<table class="basictablesorter STHSTeam_ScheduleTable"><tr><th class="STHSW35">Day</th><th class="STHSW35">Game</th><th class="STHSW200">Visitor Team</th><th class="STHSW35">Score</th><th class="STHSW200">Home Team</th><th class="STHSW35">Score</th><th class="STHSW35">ST</th><th class="STHSW35">OT</th><th class="STHSW35">SO</th><th class="STHSW35">RI</th><th class="STHSW200">Link</th></tr>

<?php
while ($row = $TeamSchedule ->fetchArray()) {
?>
    <tr>
		<td><?php echo $row['Day']; ?></td>
		<td><?php echo $row['GameNumber']; ?></td>
		<td><?php echo $row['VisitorTeamName']; ?></td>
		<td><?php if ($row['Play'] == "True"){echo $row['VisitorScore'];} else { echo "-"; }  ?></td>
		<td><?php echo $row['HomeTeamName']; ?></td>
		<td><?php echo $row['HomeScore']; ?></td>
		<td>
		<?php if( $row['VisitorTeam'] == $Team){
			if($row['VisitorScore'] >  $row['HomeScore']){ 
				echo "W";
			}elseif($row['VisitorScore'] <  $row['HomeScore']){ 
				echo "L";
			}else{
				echo "T";
			}
			$OtherTeam = $row['HomeTeam'];
		}else{
			if($row['HomeScore'] >  $row['VisitorScore']){ 
				echo "W";
			}elseif($row['HomeScore'] <  $row['VisitorScore']){ 
				echo "L";
			}else{
				echo "T";
			}
			$OtherTeam = $row['VisitorTeam'];
		} ?>		</td>
		<td><?php if ($row['Overtime'] != "False"){echo "X"; }  ?></td>		
		<td><?php if ($row['Shutout'] != "False"){echo "X"; }  ?></td>		
		<td>
			<?php while ($rowR = $RivalryInfo ->fetchArray()) {
			if ($rowR['Team2'] == $OtherTeam){
				echo "R" . $rowR['Rivalry'];
				break;
			}}?>		
		</td>	
		<td><?php if ($row['Play'] == "True") { ?><a href="<?php echo $row['Link']; ?>" target="_blank">BoxScore</a><?php }?></td>
	</tr>
<?php 
}
?>
</table>

<br /><br /></div>
<div class="tabmain" id="tabmain7">
Finance

<br /><br /></div>
<div class="tabmain" id="tabmain8">
Future

<br /><br /></div>
<div class="tabmain" id="tabmain9">
History

<br /><br /></div>
<div class="tabmain" id="tabmain10">
Injury / Suspension

<br /><br /></div>

</div>
</div>
</div>

<?php include "Footer.php";?>
