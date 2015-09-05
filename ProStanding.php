<?php
$Title = (string)"Pro Standing";
$Query = (string)"";
if($_GET){$Team = $_GET['Team'];} 

$db = new SQLite3('STHS.db');
$LeagueName = $db->querySingle('Select Name FROM LeagueGeneral');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><head>
<title><?php echo $LeagueName . " - " . $Title; ?></title>
</head><body>

<h1><?php echo $Title; ?></h1>

<?php
$Query = "SELECT * FROM TeamProStat ORDER BY Points, W";
$ProStanding = $db->query($Query);
?>

<h2>Conference</h2>
<table  cellspacing="0" border="0" width="100%" class="tablesorter" >
<thead>
<tr>
    <th width="200"><div align="left"></div></th>
	<th width="30"><a title="Games Played">GP</a></th>
	<th width="30"><a title="Wins">W</a></th>
	<th width="30"><a title="Loss">L</a></th>
	<th width="30"><a title="Overtime">OTL</a></th>		
	<th width="30"><a title="ROW">ROW</a></th>
	<th width="30"><a title="Points">Pts</a></th>
	<th width="50"><a title="Goals For">GF</a></th>
	<th width="50"><a title="Goals Against">GA</a></th>
	<th width="80"><a title="HOME">HOME</a></th>
	<th width="80"><a title="AWAY">AWAY</a></th>
	<th width="80"><a title="Last 10 Games">LAST 10</a></th>
	<th width="80">Streak</th>
</tr>
</thead>
<tbody>

<?php
while ($row = $ProStanding ->fetchArray()) {
?>
	<tr>
		<td nowrap="nowrap" style="vertical-align:middle;"><?php 
			if($row['StandingPlayoffTitle']=="E"){
				echo "";
			} else if($row['StandingPlayoffTitle']=="X"){
				echo "X -";
			} else if($row['StandingPlayoffTitle']=="Y"){
				echo "Y -";
			} else if($row['StandingPlayoffTitle']=="Z"){
				echo "Z -";
			} else if($row['StandingPlayoffTitle']=="Z" && $row['PowerRanking']==1){
				echo "P -";
			}
			?>
		<a href="pro_roster.php?team=<?php echo $row['Number']; ?>"><?php echo $row['Name']; ?></a></td>
		<td align="center" style="vertical-align:middle;"><?php echo $row['GP']; ?></td>
		<td align="center" style="vertical-align:middle;"><?php echo ($row['W'] + $row['OTW'] + $row['SOW']); ?></td>
		<td align="center" style="vertical-align:middle;"><?php echo $row['L']; ?></td>
		<td align="center" style="vertical-align:middle;"><?php echo ($row['OTL'] + $row['SOL']) ?></td>		
		<td align="center" style="vertical-align:middle;"><?php echo ($row['W'] + $row['OTW']); ?></td>		
		<td align="center" style="vertical-align:middle;"><strong><?php echo $row['Points']; ?></strong></td>
		<td align="center" style="vertical-align:middle;"><?php echo $row['GF']; ?></td>
		<td align="center" style="vertical-align:middle;"><?php echo $row['GA']; ?></td>
		<td align="center" style="vertical-align:middle;"><?php echo ($row['HomeW'] + $row['HomeOTW'] + $row['HomeSOW'])."-".$row['HomeL']."-".($row['HomeOTL']+$row['HomeSOL']); ?></td>
		<td align="center" style="vertical-align:middle;"><?php echo ($row['W'] + $row['OTW'] + $row['SOW'] - $row['HomeW'] - $row['HomeOTW'] - $row['HomeSOW'])."-".($row['L'] - $row['HomeL'])."-".($row['OTL']+$row['SOL']-$row['HomeOTL']-$row['HomeSOL']); ?></td>
		<td align="center" style="vertical-align:middle;"><?php echo ($row['Last10W'] + $row['Last10OTW'] + $row['Last10SOW'])."-".$row['Last10L']."-".($row['Last10OTL']+$row['Last10SOL']); ?></td>
		<td align="center" style="vertical-align:middle;"><?php echo $row['Streak']; ?></td>
	</tr>
<?php 
}
?>
</tbody> 
</table>



<?php
$Query = "SELECT * FROM TeamProStat ORDER BY Points, W";
$ProStanding = $db->query($Query);
?>

<h2>League Wide</h2>
<table  cellspacing="0" border="0" width="100%" class="tablesorter" >
<thead>
<tr>
    <th width="200"><div align="left"></div></th>
	<th width="30"><a title="Games Played">GP</a></th>
	<th width="30"><a title="Wins">W</a></th>
	<th width="30"><a title="Loss">L</a></th>
	<th width="30"><a title="Overtime">OTL</a></th>		
	<th width="30"><a title="ROW">ROW</a></th>
	<th width="30"><a title="Points">Pts</a></th>
	<th width="50"><a title="Goals For">GF</a></th>
	<th width="50"><a title="Goals Against">GA</a></th>
	<th width="80"><a title="HOME">HOME</a></th>
	<th width="80"><a title="AWAY">AWAY</a></th>
	<th width="80"><a title="Last 10 Games">LAST 10</a></th>
	<th width="80">Streak</th>
</tr>
</thead>
<tbody>

<?php
while ($row = $ProStanding ->fetchArray()) {
?>
	<tr>
		<td nowrap="nowrap" style="vertical-align:middle;"><?php 
			if($row['StandingPlayoffTitle']=="E"){
				echo "";
			} else if($row['StandingPlayoffTitle']=="X"){
				echo "X -";
			} else if($row['StandingPlayoffTitle']=="Y"){
				echo "Y -";
			} else if($row['StandingPlayoffTitle']=="Z"){
				echo "Z -";
			} else if($row['StandingPlayoffTitle']=="Z" && $row['PowerRanking']==1){
				echo "P -";
			}
			?>
		<a href="pro_roster.php?team=<?php echo $row['Number']; ?>"><?php echo $row['Name']; ?></a></td>
		<td align="center" style="vertical-align:middle;"><?php echo $row['GP']; ?></td>
		<td align="center" style="vertical-align:middle;"><?php echo ($row['W'] + $row['OTW'] + $row['SOW']); ?></td>
		<td align="center" style="vertical-align:middle;"><?php echo $row['L']; ?></td>
		<td align="center" style="vertical-align:middle;"><?php echo ($row['OTL'] + $row['SOL']) ?></td>		
		<td align="center" style="vertical-align:middle;"><?php echo ($row['W'] + $row['OTW']); ?></td>		
		<td align="center" style="vertical-align:middle;"><strong><?php echo $row['Points']; ?></strong></td>
		<td align="center" style="vertical-align:middle;"><?php echo $row['GF']; ?></td>
		<td align="center" style="vertical-align:middle;"><?php echo $row['GA']; ?></td>
		<td align="center" style="vertical-align:middle;"><?php echo ($row['HomeW'] + $row['HomeOTW'] + $row['HomeSOW'])."-".$row['HomeL']."-".($row['HomeOTL']+$row['HomeSOL']); ?></td>
		<td align="center" style="vertical-align:middle;"><?php echo ($row['W'] + $row['OTW'] + $row['SOW'] - $row['HomeW'] - $row['HomeOTW'] - $row['HomeSOW'])."-".($row['L'] - $row['HomeL'])."-".($row['OTL']+$row['SOL']-$row['HomeOTL']-$row['HomeSOL']); ?></td>
		<td align="center" style="vertical-align:middle;"><?php echo ($row['Last10W'] + $row['Last10OTW'] + $row['Last10SOW'])."-".$row['Last10L']."-".($row['Last10OTL']+$row['Last10SOL']); ?></td>
		<td align="center" style="vertical-align:middle;"><?php echo $row['Streak']; ?></td>
	</tr>
<?php 
}
?>
</tbody> 
</table>



<?php
include "Footer.php";
?>

