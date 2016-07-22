<!DOCTYPE html>
<?php include "Header.php";?>
<?php
$LeagueName = (string)"";
$Active = 4; /* Show Webpage Top Menu */
If (file_exists($DatabaseFile) == false){
	$LeagueName = $DatabaseNotFound;
	$Waiver = Null;
	$WaiverOrder = Null;
}else{
	$db = new SQLite3($DatabaseFile);
	$Query = "SELECT Waiver.*, TeamProInfo.Name As FromTeamName, TeamProInfo_ToTeam.Name AS ToTeamName FROM (Waiver LEFT JOIN TeamProInfo ON Waiver.FromTeam = TeamProInfo.Number) LEFT JOIN TeamProInfo AS TeamProInfo_ToTeam ON Waiver.ToTeam = TeamProInfo_ToTeam.Number ORDER BY Waiver.Player";
	$Waiver = $db->query($Query);
	$Query = "SELECT WaiverOrder.*, TeamProInfo.Name FROM WaiverOrder LEFT JOIN TeamProInfo ON WaiverOrder.TeamProNumber = TeamProInfo.Number ORDER BY WaiverOrder.Number";
	$WaiverOrder = $db->query($Query);
	
	$Query = "Select Name, OutputName from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
}
echo "<title>" . $LeagueName . " - " . $WaiverLang['Title'] . "</title>";
?>
</head><body>
<?php include "Menu.php";?>
<br />


<div style="width:95%;margin:auto;">
<h1><?php echo $WaiverLang['Waiver'];?></h1>
<table class="STHSWaiver_Table"><thead><tr>
<th title="Player"><?php echo $WaiverLang['PlayerName'];?> </th>
<th title="From Team"><?php echo $WaiverLang['FromTeam'];?> </th>
<th title="Picked by"><?php echo $WaiverLang['Pickedby'];?> </th>
<th title="Day Put on Waivers"><?php echo $WaiverLang['DayPutonWaivers'];?> </th>
<th title="Day Removed from Waivers"><?php echo $WaiverLang['DayRemovedfromWaivers'];?> </th>
</tr></thead>
<tbody>
<?php
if (empty($Waiver) == false){while ($Row = $Waiver ->fetchArray()) {
	echo "<tr><td>" . $Row['PlayerNameOV'] . "</td>";
	echo "<td>" . $Row['FromTeamName'] . "</td>";
	echo "<td>" . $Row['ToTeamName'] . "</td>";
	echo "<td>" . $Row['DayPutOnWaiver'] . "</td>";
	echo "<td>" . $Row['DayRemoveFromWaiver'] . "</td>";
	echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
}}
?>
</tbody></table>
<br />
<h1><?php echo $WaiverLang['WaiverOrder'];?></h1>
<?php
if (empty($WaiverOrder) == false){while ($Row = $WaiverOrder ->fetchArray()) {
	echo $Row['Number'] . " - " . $Row['Name'];
	echo "<br />\n"; /* The \n is for a new line in the HTML Code */
}}
?>

<br />
</div>

<?php include "Footer.php";?>
