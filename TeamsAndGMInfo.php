<?php include "Header.php";
If ($lang == "fr"){include 'LanguageFR-League.php';}else{include 'LanguageEN-League.php';}
$LeagueName = (string)"";
$MailTo = (string)"";
If (file_exists($DatabaseFile) == false){
	Goto STHSErrorTeamandGMInfo;
}else{try{
	$db = new SQLite3($DatabaseFile);
	$Query = "SELECT TeamProInfo.*, TeamFarmInfo.Name AS FarmTeamName, TeamFarmInfo.TeamThemeID as FarmTeamThemeID FROM TeamProInfo LEFT JOIN TeamFarmInfo ON TeamProInfo.Number = TeamFarmInfo.Number ORDER BY TeamProInfo.Name";
	$TeamAndGM = $db->query($Query);
	
	$Query = "Select HideEmailMessengerAddressOnWebsite from LeagueOutputOption";
	$LeagueOutputOption = $db->querySingle($Query,true);

	$Query = "Select Name, OutputName from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
} catch (Exception $e) {
STHSErrorTeamandGMInfo:
	$LeagueName = $DatabaseNotFound;
	$TeamAndGM = Null;
	$LeagueOutputOption = Null;
}}
echo "<title>" . $LeagueName . " - " . $TeamAndGMLang['TeamAndGM'] . "</title>";

?>

<style>
@media screen and (max-width: 1060px) {
.STHSTeamsAndGMInfo_MainTable thead th:nth-last-child(1){display:none;}
.STHSTeamsAndGMInfo_MainTable tbody td:nth-last-child(1){display:none;}
.STHSTeamsAndGMInfo_MainTable thead th:nth-last-child(2){display:none;}
.STHSTeamsAndGMInfo_MainTable tbody td:nth-last-child(2){display:none;}
.STHSTeamsAndGMInfo_MainTable thead th:nth-last-child(3){display:none;}
.STHSTeamsAndGMInfo_MainTable tbody td:nth-last-child(3){display:none;}
.STHSTeamsAndGMInfo_MainTable thead th:nth-last-child(4){display:none;}
.STHSTeamsAndGMInfo_MainTable tbody td:nth-last-child(4){display:none;}
.STHSTeamsAndGMInfo_MainTable thead th:nth-last-child(5){display:none;}
.STHSTeamsAndGMInfo_MainTable tbody td:nth-last-child(5){display:none;}
.STHSTeamsAndGMInfo_MainTable thead th:nth-last-child(6){display:none;}
.STHSTeamsAndGMInfo_MainTable tbody td:nth-last-child(6){display:none;}
}@media screen and (max-width: 890px) {
.STHSTeamsAndGMInfo_MainTable thead th:nth-last-child(9){display:none;}
.STHSTeamsAndGMInfo_MainTable tbody td:nth-last-child(9){display:none;}
.STHSTeamsAndGMInfo_MainTable thead th:nth-last-child(8){display:none;}
.STHSTeamsAndGMInfo_MainTable tbody td:nth-last-child(8){display:none;}
}

</style>

</head><body>
<?php include "Menu.php";?>
<br />

<script>$(function(){$(".STHSTeamsAndGMInfo_MainTable").tablesorter();});</script>

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
If ($CookieTeamNumber > 0){
if (empty($TeamAndGM) == false){while ($Row = $TeamAndGM ->fetchArray()) {
	echo "<tr><td>";
	If ($Row['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPTeamGMInfoTeamImage\" />";}		
	echo $Row['Name'] . "</td><td>" . $Row['GMName'] . "</td>";
	If ($LeagueOutputOption != Null){
		If ($LeagueOutputOption['HideEmailMessengerAddressOnWebsite'] == "False"){
			echo "<td>" . $Row['Messenger'] . "</td>";
			echo "<td>" . $Row['Email'] . "</td>";
		}else{
			echo "<td></td><td></td>";
		}
	}
	If (strlen($Row['Email']) > 0){$MailTo = $MailTo . $Row['Email'] . ";";}
	echo "<td>" . $Row['City'] . "</td>";
	echo "<td>" . $Row['Arena'] . "</td><td>";
	If ($Row['FarmTeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Row['FarmTeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPTeamGMInfoTeamImage\" />";}		
	echo $Row['FarmTeamName'] . "</td><td>" . $Row['LastLoadFileDate'] . "</td>";
	echo "<td>" . $Row['LinesLoad'] . "</td>";
	echo "<td>" . $Row['FailAutoRoster'] . "</td>";	
	echo "<td>" . $Row['FailProAutoLine'] . "</td>";	
	echo "<td>" . $Row['FailFarmAutoLine'] . "</td>";		
	echo "<td>" . $Row['FailSimulation'] . "</td>";		
	echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
}}}
echo "</tbody></table>";
If (strlen($MailTo) > 0){echo "<a href=\"mailto:" . $MailTo . "\">" . $TeamAndGMLang['EmailAll'] . "</a>";}
?>
</div>

<?php include "Footer.php";?>
