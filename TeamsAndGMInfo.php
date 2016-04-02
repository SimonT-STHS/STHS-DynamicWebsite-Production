<!DOCTYPE html>
<?php include "Header.php";?>
<?php
$LeagueName = (string)"";

If (file_exists($DatabaseFile) == false){
	$LeagueName = $DatabaseNotFound;
	$TeamAndGM = Null;
}else{
	$db = new SQLite3($DatabaseFile);
	$Query = "SELECT TeamProInfo.*, TeamFarmInfo.Name AS FarmTeamName FROM TeamProInfo LEFT JOIN TeamFarmInfo ON TeamProInfo.Number = TeamFarmInfo.Number ORDER BY TeamProInfo.Name";
	$TeamAndGM = $db->query($Query);

	$Query = "Select Name, OutputName from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
}
echo "<title>" . $LeagueName . " - " . $TeamAndGMLang['TeamAndGM'] . "</title>";

?>
</head><body>
<?php include "Menu.php";?>
<br />

<script type="text/javascript">$(function(){$(".STHSTeamsAndGMInfo_MainTable").tablesorter();});</script>

<div style="width:95%;margin:auto;">
<h1><?php echo $TeamAndGMLang['TeamAndGM'];?></h1>
<table class="STHSTeamsAndGMInfo_MainTable tablesorter"><thead><tr>
<th title="Team Name" class="STHSW100"><?php echo $TeamAndGMLang['TeamName'];?></th>
<th title="General Manager" class="STHSW75"><?php echo $TeamAndGMLang['GeneralManager'];?></th>
<th title="Instant Messenger" class="STHSW75"><?php echo $TeamAndGMLang['InstantMessenger'];?></th>
<th title="Email" class="STHSW75"><?php echo $TeamAndGMLang['Email'];?></th>
<th title="City" class="STHSW75"><?php echo $TeamAndGMLang['City'];?></th>
<th title="Arena" class="STHSW75"><?php echo $TeamAndGMLang['Arena'];?></th>
<th title="Farm Team Name" class="STHSW100"><?php echo $TeamAndGMLang['FarmTeamName'];?></th>
<th title="Last File Load Date" class="STHSW45"><?php echo $TeamAndGMLang['LastFileLoadDate'];?></th>
<th title="# of Load Lines" class="STHSW45"><?php echo $TeamAndGMLang['LoadLines'];?></th>
<th title="# of Fail Auto Roster" class="STHSW45"><?php echo $TeamAndGMLang['FailAutoRoster'];?></th>
<th title="# of Fail Pro Auto Line" class="STHSW45"><?php echo $TeamAndGMLang['FailProAutoLine'];?></th>
<th title="# of Fail Farm Auto Line" class="STHSW45"><?php echo $TeamAndGMLang['FailFarmAutoLine'];?></th>
<th title="# of Fail Simulation" class="STHSW45"><?php echo $TeamAndGMLang['FailSimulation'];?></th>
</tr></thead>
<tbody>
<?php
if (empty($TeamAndGM) == false){while ($Row = $TeamAndGM ->fetchArray()) {
	echo "<tr><td>" . $Row['Name'] . "</td>";
	echo "<td>" . $Row['GMName'] . "</td>";
	echo "<td>" . $Row['Messenger'] . "</td>";
	echo "<td>" . $Row['Email'] . "</td>";
	echo "<td>" . $Row['City'] . "</td>";
	echo "<td>" . $Row['Arena'] . "</td>";
	echo "<td>" . $Row['FarmTeamName'] . "</td>";
	echo "<td>" . $Row['LastLoadFileDate'] . "</td>";
	echo "<td>" . $Row['LinesLoad'] . "</td>";
	echo "<td>" . $Row['FailAutoRoster'] . "</td>";	
	echo "<td>" . $Row['FailProAutoLine'] . "</td>";	
	echo "<td>" . $Row['FailFarmAutoLine'] . "</td>";		
	echo "<td>" . $Row['FailSimulation'] . "</td>";		
	echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
}}
?>
</tbody></table>
</div>

<?php include "Footer.php";?>
