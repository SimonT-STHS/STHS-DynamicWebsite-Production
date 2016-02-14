<?php include "Header.php";?>
<?php
$LeagueName = (string)"";

If (file_exists($DatabaseFile) == false){
	$LeagueName = "Database File Not Found";
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
echo "<title>" . $LeagueName . " - Waivers</title>";
?>
</head><body>
<!-- TOP MENU PLACE HOLDER -->
<br />


<div style="width:95%;margin:auto;">
<h1>Waiver</h1>
<table class="STHSWaiver_Table"><thead><tr>
<th title="Player">Player Name (Overall)</th>
<th title="From Team">From Team</th>
<th title="Picked by">Picked by</th>
<th title="Day Put on Waivers">Day Put on Waivers</th>
<th title="Day Removed from Waivers">Day Removed from Waivers</th>
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
<h1>Waiver Order</h1>
<?php
if (empty($WaiverOrder) == false){while ($Row = $WaiverOrder ->fetchArray()) {
	echo $Row['Number'] . " - " . $Row['Name'];
	echo "<br />\n"; /* The \n is for a new line in the HTML Code */
}}
?>

<br />
</div>

<?php include "Footer.php";?>
