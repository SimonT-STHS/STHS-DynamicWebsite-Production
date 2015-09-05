<?php
/*
Syntax to call this webpage should be PlayerInfo.php?Player=2 where only the number change and it's based on the UniqueID of players.
*/

$DatabaseFile = (string)"STHS.db";
$Player = (integer)0;
$Title = (string)"";
$Query = (string)"";
$PlayerName = (string)"Incorrect Player";
if($_GET){$Player = $_GET['Player'];} 

If (file_exists($DatabaseFile) == false){
	$LeagueName = "Unknown League";
	$LeagueOwner = "Unknown League Owner";	
	$Title = "Unknown League";
	$Player == 0;
	$PlayerName = "Database File Not Found";
}else{
	$db = new SQLite3($DatabaseFile);
	$LeagueName = $db->querySingle('Select Name FROM LeagueGeneral');
	$LeagueOwner = $db->querySingle('Select LeagueOwner FROM LeagueGeneral');
}

If ($Player == 0){
	$Title = $LeagueName;
	$PlayerInfo = Null;
	$PlayerProStat = Null;
	$PlayerFarmStat = Null;	
}else{
	$Query = "SELECT * FROM PlayerInfo WHERE Number = " . $Player;
	$PlayerInfo = $db->querySingle($Query,true);
	$PlayerName = $PlayerInfo['Name'];
	$Title = $LeagueName . " - " . $PlayerInfo['Name'];
	
	$Query = "SELECT * FROM PlayerProStat WHERE Number = " . $Player;
	$PlayerProStat = $db->querySingle($Query,true);
	
	$Query = "SELECT * FROM PlayerFarmStat WHERE Number = " . $Player;
	$PlayerFarmStat = $db->querySingle($Query,true);
}

/*
Not Add Yet : URLLink, GameInRow*, Jersey
*/

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><head>
<title><?php echo $Title; ?></title>
<link href="SIMON2.css" rel="stylesheet" type="text/css" />
<style type="text/css">
</style>
</head><body>

<h1><center><?php echo $PlayerName; ?></center></h1>
<b><center><?php echo $PlayerInfo['TeamName']; ?></center></b><br />

<table align=center border="1" width="80%">
<tr>
	<th align=center width="14%">Country</th>
	<th align=center width="14%">Birthday</th>
	<th align=center width="10%">Age</th>
	<th align=center width="8%">Height</th>
	<th align=center width="8%">Weight</th>
	<th align=center width="12%">Center</th>
	<th align=center width="12%">Left Wing</th>
	<th align=center width="12%">Right Wing</th>
	<th align=center width="12%">Defense</th>
</tr><tr>
	<td align=center><?php echo $PlayerInfo['Country']; ?></td>
	<td align=center><?php echo $PlayerInfo['AgeDate']; ?></td>
	<td align=center><?php echo $PlayerInfo['Age']; ?></td>	
	<td align=center><?php echo $PlayerInfo['Height']; ?></td>
	<td align=center><?php echo $PlayerInfo['Weight']; ?></td>
	<td align=center><?php if ($PlayerInfo['PosC']== "True"){ echo "x"; } ?></td>
	<td align=center><?php if ($PlayerInfo['PosLW']== "True"){ echo "x"; } ?></td>
	<td align=center><?php if ($PlayerInfo['PosRW']== "True"){ echo "x"; } ?></td>
	<td align=center><?php if ($PlayerInfo['PosD']== "True"){ echo "x"; } ?></td>
</tr>
</table><br />

<table align=center border="1" width="80%">
<tr>
	<th align=center width="10%">Suspension</th>
	<th align=center width="12%">Condition</th>
	<th align=center width="28%">Injury</th>
	<th align=center width="10%">Health # Loss</th>
	<th align=center width="8%">Rookie</th>
	<th align=center width="8%">Available for Trade</th>
	<th align=center width="8%">No Trade</th>
	<th align=center width="8%">Force Waiver</th>
	<th align=center width="8%">Star Power</th>


</tr><tr>
	<td align=center><?php echo $PlayerInfo['Suspension']; ?></td>
	<td align=center><?php echo number_format($PlayerInfo['ConditionDecimal'],2); ?></td>
	<td align=center><?php echo $PlayerInfo['Injury']; ?></td>	
	<td align=center><?php echo $PlayerInfo['NumberOfInjury']; ?></td>
	<td align=center><?php if ($PlayerInfo['Rookie']== "True"){ echo "x"; } ?></td>
	<td align=center><?php if ($PlayerInfo['AvailableforTrade']== "True"){ echo "x"; } ?></td>
	<td align=center><?php if ($PlayerInfo['NoTrade']== "True"){ echo "x"; } ?></td>
	<td align=center><?php if ($PlayerInfo['ForceWaiver']== "True"){ echo "x"; } ?></td>
	<td align=center><?php echo $PlayerInfo['StarPower']; ?></td>
</tr>
</table><br />

<table align=center border="1" width="80%">
<tr>
	<th align=center width="8%">Can Play Pro</th>
	<th align=center width="8%">Can Play Farm</th>
	<th align=center width="10%">Exclude from Salary Cap</th>
	<th align=center width="14%">Pro Salary in Farm / 1 Way Contract</th>
	<th align=center width="10%">Contract Duration</th>
	<th align=center width="10%">Salary Average</th>
	<th align=center width="10%">Salary Year 1</th>
	<th align=center width="10%">Salary Year 2</th>
	<th align=center width="10%">Salary Year 3</th>
	<th align=center width="10%">Salary Year 4</th>
</tr><tr>
	<td align=center><?php if ($PlayerInfo['CanPlayPro']== "True"){ echo "x"; } ?></td>
	<td align=center><?php if ($PlayerInfo['CanPlayFarm']== "True"){ echo "x"; } ?></td>	
	<td align=center><?php if ($PlayerInfo['ExcludeSalaryCap']== "True"){ echo "x"; } ?></td>
	<td align=center><?php if ($PlayerInfo['ProSalaryinFarm']== "True"){ echo "x"; } ?></td>
	<td align=center><?php echo $PlayerInfo['Contract']; ?></td>
	<td align=center><?php echo number_format($PlayerInfo['SalaryAverage'],0) . "$"; ?></td>
	<td align=center><?php echo number_format($PlayerInfo['Salary1'],0) . "$"; ?></td>
	<td align=center><?php echo number_format($PlayerInfo['Salary2'],0) . "$"; ?></td>
	<td align=center><?php echo number_format($PlayerInfo['Salary3'],0) . "$"; ?></td>
	<td align=center><?php echo number_format($PlayerInfo['Salary4'],0) . "$"; ?></td>

</tr>
</table><br />

<table align=center border="1" width="80%">
<tr>
	<th align=center width="3%">CK</th>
	<th align=center width="3%">FG</th>
	<th align=center width="3%">DI</th>
	<th align=center width="3%">SK</th>
	<th align=center width="3%">ST</th>
	<th align=center width="3%">EN</th>
	<th align=center width="3%">DU</th>
	<th align=center width="3%">PH</th>
	<th align=center width="3%">FO</th>
	<th align=center width="3%">PA</th>
	<th align=center width="3%">SC</th>
	<th align=center width="3%">DF</th>
	<th align=center width="3%">PS</th>
	<th align=center width="3%">EX</th>
	<th align=center width="3%">LD</th>
	<th align=center width="3%">PO</th>
	<th align=center width="3%">MO</th>
	<th align=center width="3%">OV</th>
</tr><tr>
	<td align=center><?php echo $PlayerInfo['CK']; ?></td>
	<td align=center><?php echo $PlayerInfo['FG']; ?></td>
	<td align=center><?php echo $PlayerInfo['DI']; ?></td>
	<td align=center><?php echo $PlayerInfo['SK']; ?></td>
	<td align=center><?php echo $PlayerInfo['ST']; ?></td>
	<td align=center><?php echo $PlayerInfo['EN']; ?></td>
	<td align=center><?php echo $PlayerInfo['DU']; ?></td>
	<td align=center><?php echo $PlayerInfo['PH']; ?></td>
	<td align=center><?php echo $PlayerInfo['FO']; ?></td>
	<td align=center><?php echo $PlayerInfo['PA']; ?></td>
	<td align=center><?php echo $PlayerInfo['SC']; ?></td>
	<td align=center><?php echo $PlayerInfo['DF']; ?></td>
	<td align=center><?php echo $PlayerInfo['PS']; ?></td>
	<td align=center><?php echo $PlayerInfo['EX']; ?></td>
	<td align=center><?php echo $PlayerInfo['LD']; ?></td>
	<td align=center><?php echo $PlayerInfo['PO']; ?></td>
	<td align=center><?php echo $PlayerInfo['MO']; ?></td>
	<td align=center><?php echo $PlayerInfo['Overall']; ?></td> 
</tr>
</table><br /><br />

<b><center>Pro Stats</center></b><br />
<table align=center border="1" width="80%">
<tr>
	<th align=center width="3%">GP</th>
	<th align=center width="3%">G</th>
	<th align=center width="3%">A</th>
	<th align=center width="3%">P</th>
	<th align=center width="3%">+/-</th>
	<th align=center width="3%">PIM</th>
	<th align=center width="3%">PIM5</th>
	<th align=center width="3%">HIT</th>
	<th align=center width="3%">HTT</th>
	<th align=center width="3%">SHT</th>
	<th align=center width="3%">OSB</th>
	<th align=center width="3%">OSM</th>
	<th align=center width="3%">SHT %</th>
	<th align=center width="3%">SB</th>	
	<th align=center width="3%">MP</th>
	<th align=center width="3%">AMG</th>
	<th align=center width="3%">PPG</th>
	<th align=center width="3%">PPA</th>
	<th align=center width="3%">PPS</th>
	<th align=center width="3%">PPM</th>	
	<th align=center width="3%">PKG</th>
	<th align=center width="3%">PKA</th>
	<th align=center width="3%">PKS</th>
	<th align=center width="3%">PKM</th>
	
	
	<th align=center width="3%">FO%</th>
	<th align=center width="3%">GA</th>
	<th align=center width="3%">TA</th>
</tr><tr>
	<td align=center><?php echo $PlayerProStat['GP']; ?></td>
	<td align=center><?php echo $PlayerProStat['G']; ?></td>
	<td align=center><?php echo $PlayerProStat['A']; ?></td>
	<td align=center><?php echo $PlayerProStat['P']; ?></td>
	<td align=center><?php echo $PlayerProStat['PlusMinus']; ?></td>
	<td align=center><?php echo $PlayerProStat['Pim']; ?></td>
	<td align=center><?php echo $PlayerProStat['Pim5']; ?></td>
	<td align=center><?php echo $PlayerProStat['Hits']; ?></td>
	<td align=center><?php echo $PlayerProStat['HitsTook']; ?></td>
	<td align=center><?php echo $PlayerProStat['Shots']; ?></td>
	<td align=center><?php echo $PlayerProStat['OwnShotsBlock']; ?></td>
	<td align=center><?php echo $PlayerProStat['OwnShotsMissGoal']; ?></td>
	<td align=center><?php if ($PlayerProStat['Shots'] > "0"){echo sprintf("%.2f%%", $PlayerProStat['G'] / $PlayerProStat['Shots'] *100 ); } else {echo "0%";}?></td>
	<td align=center><?php echo $PlayerProStat['ShotsBlock']; ?></td>
	<td align=center><?php echo Floor($PlayerProStat['SecondPlay']/60); ?></td>
	<td align=center>AMG</td>
	<td align=center><?php echo $PlayerProStat['PPG']; ?></td>
	<td align=center><?php echo $PlayerProStat['PPA']; ?></td>
	<td align=center><?php echo $PlayerProStat['PPShots']; ?></td>
	<td align=center><?php echo $PlayerProStat['PPSecondPlay']/60; ?></td>	
	<td align=center><?php echo $PlayerProStat['PKG']; ?></td>
	<td align=center><?php echo $PlayerProStat['PKA']; ?></td>
	<td align=center><?php echo $PlayerProStat['PKShots']; ?></td>
	<td align=center><?php echo $PlayerProStat['PKSecondPlay']/60; ?></td>		
	<td align=center><?php if ($PlayerProStat['FaceOffTotal'] > "0"){echo sprintf("%.2f%%", $PlayerProStat['FaceOffWon'] / $PlayerProStat['FaceOffTotal'] *100 ); } else {echo "0%";}?></td>
	<td align=center><?php echo $PlayerProStat['GiveAway']; ?></td>
	<td align=center><?php echo $PlayerProStat['TakeAway']; ?></td>
</tr>
</table><br /><br />

<b><center>Farm Stats</center></b><br />
<table align=center border="1" width="51%">
<tr>
	<th align=center width="3%">GP</th>
	<th align=center width="3%">G</th>
	<th align=center width="3%">A</th>
	<th align=center width="3%">P</th>
	<th align=center width="3%">+/-</th>
	<th align=center width="3%">PIM</th>
	<th align=center width="3%">HIT</th>
	<th align=center width="3%">SHT</th>
	<th align=center width="3%">SB</th>
	<th align=center width="3%">MP</th>
	<th align=center width="3%">PPG</th>
	<th align=center width="3%">PPA</th>
	<th align=center width="3%">FO%</th>
	<th align=center width="3%">GA</th>
	<th align=center width="3%">TA</th>
</tr><tr>
	<td align=center width="3%"><?php echo $PlayerFarmStat['GP']; ?></td>
	<td align=center width="3%"><?php echo $PlayerFarmStat['G']; ?></td>
	<td align=center width="3%"><?php echo $PlayerFarmStat['A']; ?></td>
	<td align=center width="3%"><?php echo $PlayerFarmStat['P']; ?></td>
	<td align=center width="3%"><?php echo $PlayerFarmStat['PlusMinus']; ?></td>
	<td align=center width="3%"><?php echo $PlayerFarmStat['Pim']; ?></td>
	<td align=center width="3%"><?php echo $PlayerFarmStat['Hits']; ?></td>
	<td align=center width="3%"><?php echo $PlayerFarmStat['Shots']; ?></td>
	<td align=center width="3%"><?php echo $PlayerFarmStat['ShotsBlock']; ?></td>
	<td align=center width="3%"><?php echo Floor($PlayerFarmStat['SecondPlay']/60); ?></td>
	<td align=center width="3%"><?php echo $PlayerFarmStat['PPG']; ?></td>
	<td align=center width="3%"><?php echo $PlayerFarmStat['PPA']; ?></td>
	<td align=center width="3%"><?php if ($PlayerFarmStat['FaceOffTotal'] > "0"){echo sprintf("%.2f%%", $PlayerFarmStat['FaceOffWon'] / $PlayerFarmStat['FaceOffTotal'] *100 ); } else {echo "0%";}?></td>
	<td align=center width="3%"><?php echo $PlayerFarmStat['GiveAway']; ?></td>
	<td align=center width="3%"><?php echo $PlayerFarmStat['TakeAway']; ?></td>
</tr>
</table><br /><br />

<?php
include "Footer.php";
?>
		
