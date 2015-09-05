<?php
$Team = (integer)0;
$Title = (string)"";
$Query = (string)"";
if($_GET){$Team = $_GET['Team'];} 

$db = new SQLite3('STHS.db');
If ($Team == 0){
	$Title = "League Farm Schedule";
	
	$MainQuery = "SELECT * FROM ScheduleFarm ORDER BY GameNumber";
}else{
	$Query = "SELECT Name FROM TeamFarmInfo WHERE Number = " . $Team ;
	$TeamName = $db->querySingle($Query);
	$Title = $TeamName . " Farm Schedule";
	
	$MainQuery = "SELECT * FROM ScheduleFarm WHERE (VisitorTeam = " . $Team . " OR HomeTeam = " . $Team . ") ORDER BY GameNumber";
}
$FarmSchedule = $db->query($MainQuery);

$LeagueName = $db->querySingle('Select Name FROM LeagueGeneral');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><head>
<title><?php echo $LeagueName . " - " . $Title; ?></title>
</head><body>

<h1><?php echo $Title; ?></h1>

<table  cellspacing="0" border="0" width="100%">
<thead>
<tr>
	<th>Day</th>
	<th>Game</th>
	<th>Visitor Team</th>
	<th>Score</th>
	<th>Home Team</th>
	<th>Score</th>
	<th>Overtime</th>
	<th>Shutout</th>
	<th>Game Details</th>
</tr>
</thead>



<?php
while ($row = $FarmSchedule ->fetchArray()) {
?>
    <tr>
		<td align="center"><?php echo $row['Day']; ?></td>
		<td align="center"><?php echo $row['GameNumber']; ?></td>
		<td align="center"><?php echo $row['VisitorTeamName']; ?></td>
		<td align="center"><?php echo $row['VisitorScore']; ?></td>
		<td align="center"><?php echo $row['HomeTeamName']; ?></td>
		<td align="center"><?php echo $row['HomeScore']; ?></td>
		<td align="center"><?php if ($row['Overtime'] != "False"){ echo $row['Overtime']; } else { echo "-"; } ?></td>
		<td align="center"><?php if ($row['Shutout'] != "False"){ echo $row['Shutout']; } else { echo "-"; } ?></td>
		<td align="center"><?php if ($row['Play'] == "True") { ?><a href="<?php echo $row['Link']; ?>" target="_blank">BoxScore</a><?php }?></td>
	</tr>
<?php 
}
?>
</tbody> 
</table>

<?php
include "Footer.php";
?>


