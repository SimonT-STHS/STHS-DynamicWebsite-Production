<!DOCTYPE html>
<?php include "Header.php";?>
<?php
$PlayersStatPossibleOrderField = array(
array("Name","Player Name"),
array("GP","Games Played"),
array("G","Goals"),
array("A","Assists"),
array("P","Points"),
array("PlusMinus","Plus/Minus"),
array("Pim","Penalty Minutes"),
array("Pim5","Penalty Minutes for Major Penalty"),
array("Hits","Hits"),
array("HitsTook","Hits Received"),
array("Shots","Shots"),
array("OwnShotsBlock","Own Shots Block by others players"),
array("OwnShotsMissGoal","Own Shots Miss the net"),
array("ShotsPCT","Shooting Percentage"),
array("ShotsBlock","Shots Blocked"),
array("SecondPlay","Minutes Played"),
array("AMG","Average Minutes Played per Game"),
array("PPG","Power Play Goals"),
array("PPA","Power Play Assists"),
array("PPP","Power Play Points"),
array("PPShots","Power Play Shots"),
array("PPSecondPlay","Power Play Minutes Played"),
array("PKG","Penalty Kill Goals"),
array("PKA","Penalty Kill Assists"),
array("PKP","Penalty Kill Points"),
array("PKShots","Penalty Kill Shots"),
array("PKSecondPlay","Penalty Kill Minutes Played"),
array("GW","Game Winning Goals"),
array("GT","Game Tying Goals"),
array("FaceoffPCT","Face off Percentage"),
array("FaceOffTotal","Face offs Taken"),
array("GiveAway","Give Aways"),
array("TakeAway","Take Aways"),
array("EmptyNetGoal","Empty Net Goals"),
array("HatTrick","Hat Tricks"),
array("P20","Points per 20 Minutes"),
array("PenalityShotsScore","Penalty Shots Goals"),
array("PenalityShotsTotal","Penalty Shots Taken"),
array("FightW","Fight Won"),
array("FightL","Fight Lost"),
array("FightT","Fight Ties"),
array("Star1","Number of time players was star #1 in a game"),
array("Star2","Number of time players was star #2 in a game"),
array("Star3","Number of time players was star #3 in a game"),
);

$GoaliesStatPossibleOrderField = array(
array("Name","Goalie Name"),
array("GP","Games Played"),
array("W","Wins"),
array("L","Losses"),
array("OTL","Overtime Losses"),
array("PCT","Save Percentage"),
array("GAA","Goals Against Average"),
array("SecondPlay","Minutes Played"),
array("Pim","Penalty Minutes"),
array("Shootout","Shootout"),
array("SA","Shots Against"),
array("GA","Goals Against"),
array("SARebound","Shots Against Rebound"),
array("A","Assists"),
array("EmptyNetGoal","Empty net Goals"),
array("PenalityShotsShots","Penalty Shots Against"),
array("PenalityShotsGoals","Penalty Shots Goals"),
array("PenalityShotsPCT","Penalty Shots Save Percentage"),
array("StartGoaler","Number of game goalies start as Start goalie"),
array("BackupGoaler","Number of game goalies start as Backup goalie"),
array("Star1","Number of time players was star #1 in a game"),
array("Star2","Number of time players was star #2 in a game"),
array("Star3","Number of time players was star #3 in a game"),
);

$PlayersInformationPossibleOrderField = array(
array("Name","Player Name"),
array("Team","Team Number"),
array("Age","Age"),
array("Rookie","Rookie"),
array("Weight","Weight"),
array("Height","Height"),
array("NoTrade","No Trade"),
array("ForceWaiver","Force Waiver"),
array("Contract","Contract Duration"),
array("Salary1","Current Salary"),
array("SalaryAverage","Salary Average"),
array("Salary2","Salary Year 2"),
array("Salary3","Salary Year 3"),
array("Salary4","Salary Year 4"),
array("Salary5","Salary Year 5"),
array("Salary6","Salary Year 6"),
array("Salary7","Salary Year 7"),
array("Salary8","Salary Year 8"),
array("Salary9","Salary Year 9"),
array("Salary10","Salary Year 10"),
);

$PlayersRosterPossibleOrderField  = array(
array("Name","Player Name"),
array("ConditionDecimal","Condition"),
array("CK","Checking"),
array("FG","Fighting"),
array("DI","Discipline"),
array("SK","Skating"),
array("ST","Strength"),
array("EN","Endurance"),
array("DU","Durability"),
array("PH","Puck Handling"),
array("FO","Face Offs"),
array("PA","Passing"),
array("SC","Scoring"),
array("DF","Defense"),
array("PS","Penalty Shot"),
array("EX","Experience"),
array("LD","Leadership"),
array("PO","Potential"),
array("MO","Morale"),
array("Overall","Overall"),
);

$GoaliesRosterPossibleOrderField = array(
array("Name","Goalie Name"),
array("ConditionDecimal","Condition"),
array("SK","Skating"),
array("DU","Durability"),
array("EN","Endurance"),
array("SZ","Size"),
array("AG","Agility"),
array("RB","Rebound Control"),
array("SC","Style Control"),
array("HS","Hand Speed"),
array("RT","Reaction Time"),
array("PH","Puck Handling"),
array("PS","Penalty Shot"),
array("EX","Experience"),
array("LD","Leadership"),
array("PO","Potential"),
array("MO","Morale"),
array("Overall","Overall"),
);

$TeamStatPossibleOrderField = array(
array("Name","Team Name"),
array("GP","Overall Games Played"),
array("W","Overall Wins"),
array("L","Overall Loss"),
array("OTW","Overall Overtime Wins"),
array("OTL","Overall Overtime Loss"),
array("SOW","Overall Shootout Wins"),
array("SOL","Overall Shootout Loss"),
array("GF","Overall Goals For"),
array("GA","Overall Goals Against"),
array("HomeGP","Home Games Played"),
array("HomeW","Home Wins"),
array("HomeL","Home Loss"),
array("HomeOTW","Home Overtime Wins"),
array("HomeOTL","Home Overtime Loss"),
array("HomeSOW","Home Shootout Wins"),
array("HomeSOL","Home Shootout Loss"),
array("HomeGF","Home Goals For"),
array("HomeGA","Home Goals Against"),
array("P","Points"),
array("TotalGoal","Total Team Goals"),
array("TotalAssist","Total Team Assists"),
array("TotalPoint","Total Team Players Points"),
array("Shutouts","Shutouts"),
array("EmptyNetGoal","Empty Net Goals"),
array("GoalsPerPeriod1","Goals for 1st Period"),
array("GoalsPerPeriod2","Goals for 2nd Period"),
array("GoalsPerPeriod3","Goals for 3rd Period"),
array("GoalsPerPeriod4","Goals for 4th Period"),
array("ShotsFor","Shots For"),
array("ShotsPerPeriod1","Shots for 1st Period"),
array("ShotsPerPeriod2","Shots for 2nd Period"),
array("ShotsPerPeriod3","Shots for 3rd Period"),
array("ShotsPerPeriod4","Goals for 4th Period"),
array("ShotsAga","Shots Against"),
array("ShotsBlock","Shots Block"),
array("Pim","Penalty Minutes"),
array("Hit","Hits"),
array("PPAttemp","Power Play Attemps"),
array("PPGoal","Power Play Goals"),
array("PKAttemp","Penalty Kill Attemps"),
array("PKGoalGA","Penalty Kill Goals Against"),
array("PKGoalGF","Penalty Kill Goals For"),
array("FaceOffWonOffensifZone","Won Offensif Zone Faceoff"),
array("FaceOffTotalOffensifZone","Total Offensif Zone Faceoff"),
array("FaceOffWonDefensifZone","Won Defensif Zone Faceoff"),
array("FaceOffTotalDefensifZone","Total Defensif Zone Faceoff"),
array("FaceOffWonNeutralZone","Won Neutral Zone Faceoff"),
array("FaceOffTotalNeutralZone","Total Neutral Zone Faceoff"),
array("PuckTimeInZoneDF","Puck Time In Offensif Zone"),
array("PuckTimeInZoneOF","Puck Time Control In Offensif Zone"),
array("PuckTimeInZoneNT","Puck Time In Defensif Zone"),
array("PuckTimeControlinZoneDF","Puck Time Control In Defensif Zone"),
array("PuckTimeControlinZoneOF","Puck Time In Neutral Zone"),
array("PuckTimeControlinZoneNT","Puck Time Control In Neutral Zone"),
);

$LeagueName = (string)"";

If (file_exists($DatabaseFile) == false){
	$LeagueName = $DatabaseNotFound;
	$TeamName = Null;
}else{
	$db = new SQLite3($DatabaseFile);
	$Query = "SELECT Number, Name FROM TeamProInfo Order By Name";
	$TeamName = $db->query($Query);
	$Query = "Select Name, OutputName from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
}
echo "<title>" . $LeagueName . " - " . $SearchLang['SearchTitle'] . "</title>";
?>
<style type="text/css">
.SearchDiv {
	-webkit-column-count: 2;
	-moz-column-count: 2;
	 column-count: 2;
	-webkit-column-width: 400px;
	-moz-column-width: 400px;
	column-width: 400px;
	width:99%;
	margin:auto;	
 } 
.DivSection{
	-webkit-column-break-inside: avoid;
	page-break-inside: avoid;
	break-inside: avoid;
}
</style>
</head><body>
<?php include "Menu.php";?>
<?php echo "<h1>" . $LeagueName . " - " . $SearchLang['SearchTitle'] . "</h1>";?>
<br />

<div class="SearchDiv">

<div class="DivSection"><h1><?php echo $SearchLang['PlayersRosterMenu'];?></h1>
<form action="PlayersRoster.php" method="get">
<table class="STHSTable">
<tr>
	<td class="STHSW200"><?php echo $SearchLang['Team'];?></td><td class="STHSW250">
	<select name="Team" class="STHSW250" >
	<option selected value=""><?php echo $SearchLang['AllTeam'];?></option> 
	<option value="0"><?php echo $DynamicTitleLang['Unassigned'];?></option>
	<?php
	if (empty($TeamName) == false){while ($Row = $TeamName ->fetchArray()) {
		echo "<option value=\"" . $Row['Number'] . "\">" . $Row['Name'] . "</option>"; 
	}}
	?>
	</select></td>
</tr>
<tr>
	<td class="STHSW200"><?php echo $SearchLang['Type'];?></td><td class="STHSW250">
	<select name="Type" class="STHSW250">
	<option selected value="0"><?php echo $SearchLang['ProandFarm'];?></option>
	<option value="1"><?php echo $SearchLang['ProOnly'];?></option>
	<option value="2"><?php echo $SearchLang['FarmOnly'];?></option>
	</select></td>
</tr>
<tr>
	<td class="STHSW200"><?php echo $SearchLang['OrderField'];?></td><td class="STHSW250">
	<select name="Order" class="STHSW250">
	<option selected value=""><?php echo $SearchLang['Select'];?></option>
	<?php 
	foreach ($PlayersRosterPossibleOrderField as $Value) {
		echo "<option value=\"" . $Value[0] . "\">" . $Value[1] . "</option>"; 
	} ?>
	</select></td>
</tr>
<tr>
	<td class="STHSW200"><?php echo $SearchLang['FreeAgents'];?></td><td class="STHSW250">
	<select name="FreeAgent" class="STHSW250">
	<option selected value=""><?php echo $SearchLang['Select'];?></option>
	<option value="0"><?php echo $SearchLang['ThisYear'];?></option>
	<option value="1"><?php echo $SearchLang['NextYear'];?></option>
	<option value="2"><?php echo $SearchLang['In2Years'];?></option>
	<option value="2"><?php echo $SearchLang['In3Years'];?></option>
	<option value="2"><?php echo $SearchLang['In4Years'];?></option>
	<option value="2"><?php echo $SearchLang['In5Years'];?></option>
	</select></td>
</tr>
<tr>
	<td class="STHSW200"><?php echo $SearchLang['Max'];?></td><td class="STHSW250">
	<select name="Max" class="STHSW250">
	<option selected value=""><?php echo $SearchLang['Unlimited'];?></option>
	<?php 
	for ($i=5;$i <=100;$i = $i +5)
	{
		echo "<option value=\"" . $i . "\">" . $i . "</option>"; 
	}
	?>
	</select></td>
</tr>
<tr>
	<td class="STHSW200"><?php echo $SearchLang['AcsendingOrder'];?></td><td class="STHSW250">
	<?php If ($lang == "fr"){echo "<input type=\"hidden\" name=\"Lang\" value=\"fr\">";}?>
	<input type="checkbox" name="ACS"></td>
</tr>
<tr>
	<td class="STHSW200"><?php echo $SearchLang['ExpansionDraft'];?></td><td class="STHSW250">
	<input type="checkbox" name="Expansion"></td>
</tr>
<tr>
	<td colspan="2" class="STHSCenter"><input type="submit" value="Submit"></td>
</tr>
</table></form></div>

<div class="DivSection"><h1><?php echo $SearchLang['GoaliesRosterMenu'];?></h1>
<form action="GoaliesRoster.php" method="get">
<table class="STHSTable">
<tr>
	<td class="STHSW200"><?php echo $SearchLang['Team'];?></td><td class="STHSW250">
	<select name="Team" class="STHSW250" >
	<option selected value=""><?php echo $SearchLang['AllTeam'];?></option> 
	<option value="0"><?php echo $DynamicTitleLang['Unassigned'];?></option>
	<?php
	if (empty($TeamName) == false){while ($Row = $TeamName ->fetchArray()) {
		echo "<option value=\"" . $Row['Number'] . "\">" . $Row['Name'] . "</option>"; 
	}}
	?>
	</select></td>
</tr>
<tr>
	<td class="STHSW200"><?php echo $SearchLang['Type'];?></td><td class="STHSW250">
	<select name="Type" class="STHSW250">
	<option selected value="0"><?php echo $SearchLang['ProandFarm'];?></option>
	<option value="1"><?php echo $SearchLang['ProOnly'];?></option>
	<option value="2"><?php echo $SearchLang['FarmOnly'];?></option>
	</select></td>
</tr>
<tr>
	<td class="STHSW200"><?php echo $SearchLang['OrderField'];?></td><td class="STHSW250">
	<select name="Order" class="STHSW250">
	<option selected value=""><?php echo $SearchLang['Select'];?></option>
	<?php 
	foreach ($GoaliesRosterPossibleOrderField as $Value) {
		echo "<option value=\"" . $Value[0] . "\">" . $Value[1] . "</option>"; 
	} ?>
	</select></td>
</tr>
<tr>
	<td class="STHSW200"><?php echo $SearchLang['FreeAgents'];?></td><td class="STHSW250">
	<select name="FreeAgent" class="STHSW250">
	<option selected value=""><?php echo $SearchLang['Select'];?></option>
	<option value="0"><?php echo $SearchLang['ThisYear'];?></option>
	<option value="1"><?php echo $SearchLang['NextYear'];?></option>
	<option value="2"><?php echo $SearchLang['In2Years'];?></option>
	<option value="2"><?php echo $SearchLang['In3Years'];?></option>
	<option value="2"><?php echo $SearchLang['In4Years'];?></option>
	<option value="2"><?php echo $SearchLang['In5Years'];?></option>
	</select></td>
</tr>
<tr>
	<td class="STHSW200"><?php echo $SearchLang['Max'];?></td><td class="STHSW250">
	<select name="Max" class="STHSW250">
	<option selected value=""><?php echo $SearchLang['Unlimited'];?></option>
	<?php 
	for ($i=5;$i <=100;$i = $i +5)
	{
		echo "<option value=\"" . $i . "\">" . $i . "</option>"; 
	}
	?>
	</select></td>
</tr>
<tr>
	<td class="STHSW200"><?php echo $SearchLang['AcsendingOrder'];?></td><td class="STHSW250">
	<?php If ($lang == "fr"){echo "<input type=\"hidden\" name=\"Lang\" value=\"fr\">";}?>
	<input type="checkbox" name="ACS">	</td>
</tr>
<tr>
	<td class="STHSW200"><?php echo $SearchLang['ExpansionDraft'];?></td><td class="STHSW250">
	<input type="checkbox" name="Expansion"></td>
</tr>
<tr>
	<td colspan="2" class="STHSCenter"><input type="submit" value="Submit"></td>
</tr>
</table></form></div> 

<div class="DivSection"><h1><?php echo $SearchLang['PlayersInformationMenu'];?></h1>
<form action="PlayersInfo.php" method="get">
<table class="STHSTable">
<tr>
	<td class="STHSW200"><?php echo $SearchLang['Team'];?></td><td class="STHSW250">
	<select name="Team" class="STHSW250" >
	<option selected value=""><?php echo $SearchLang['AllTeam'];?></option> 
	<option value="0"><?php echo $DynamicTitleLang['Unassigned'];?></option>
	<?php
	if (empty($TeamName) == false){while ($Row = $TeamName ->fetchArray()) {
		echo "<option value=\"" . $Row['Number'] . "\">" . $Row['Name'] . "</option>"; 
	}}
	?>
	</select></td>
</tr>
<tr>
	<td class="STHSW200"><?php echo $SearchLang['Type'];?></td><td class="STHSW250">
	<select name="Type" class="STHSW250">
	<option selected value="0"><?php echo $SearchLang['ProandFarm'];?></option>
	<option value="1"><?php echo $SearchLang['ProOnly'];?></option>
	<option value="2"><?php echo $SearchLang['FarmOnly'];?></option>
	</select></td>
</tr>
<tr>
	<td class="STHSW200"><?php echo $SearchLang['OrderField'];?></td><td class="STHSW250">
	<select name="Order" class="STHSW250">
	<option selected value=""><?php echo $SearchLang['Select'];?></option>
	<?php 
	foreach ($PlayersInformationPossibleOrderField as $Value) {
		echo "<option value=\"" . $Value[0] . "\">" . $Value[1] . "</option>"; 
	} ?>
	</select></td>
</tr>
<tr>
	<td class="STHSW200"><?php echo $SearchLang['FreeAgents'];?></td><td class="STHSW250">
	<select name="FreeAgent" class="STHSW250">
	<option selected value=""><?php echo $SearchLang['Select'];?></option>
	<option value="0"><?php echo $SearchLang['ThisYear'];?></option>
	<option value="1"><?php echo $SearchLang['NextYear'];?></option>
	<option value="2"><?php echo $SearchLang['In2Years'];?></option>
	<option value="2"><?php echo $SearchLang['In3Years'];?></option>
	<option value="2"><?php echo $SearchLang['In4Years'];?></option>
	<option value="2"><?php echo $SearchLang['In5Years'];?></option>
	</select></td>
</tr>
<tr>
	<td class="STHSW200"><?php echo $SearchLang['Max'];?></td><td class="STHSW250">
	<select name="Max" class="STHSW250">
	<option selected value=""><?php echo $SearchLang['Unlimited'];?></option>
	<?php 
	for ($i=5;$i <=100;$i = $i +5)
	{
		echo "<option value=\"" . $i . "\">" . $i . "</option>"; 
	}
	?>
	</select></td>
</tr>
<tr>
	<td class="STHSW200"><?php echo $SearchLang['DecendingOrder'];?></td><td class="STHSW250">
	<?php If ($lang == "fr"){echo "<input type=\"hidden\" name=\"Lang\" value=\"fr\">";}?>
	<input type="checkbox" name="DESC"></td>
</tr>
<tr>
	<td class="STHSW200"><?php echo $SearchLang['ExpansionDraft'];?></td><td class="STHSW250">
	<input type="checkbox" name="Expansion"></td>
</tr>
<tr>
	<td colspan="2" class="STHSCenter"><input type="submit" value="Submit"></td>
</tr>
</table></form></div>

<div class="DivSection"><h1><?php echo $SearchLang['PlayersStatsMenu'];?></h1>
<form action="PlayersStat.php" method="get">
<table class="STHSTable">
<tr>
	<td class="STHSW200"><?php echo $SearchLang['Team'];?></td><td class="STHSW250">
	<select name="Team" class="STHSW250" >
	<option selected value=""><?php echo $SearchLang['AllTeam'];?></option> 
	<?php
	if (empty($TeamName) == false){while ($Row = $TeamName ->fetchArray()) {
		echo "<option value=\"" . $Row['Number'] . "\">" . $Row['Name'] . "</option>"; 
	}}
	?>
	</select></td>
</tr>
<tr>
	<td class="STHSW200"><?php echo $SearchLang['OrderField'];?></td><td class="STHSW250">
	<select name="Order" class="STHSW250">
	<option selected value=""><?php echo $SearchLang['Select'];?></option>
	<?php 
	foreach ($PlayersStatPossibleOrderField as $Value) {
		echo "<option value=\"" . $Value[0] . "\">" . $Value[1] . "</option>"; 
	} ?>
	</select></td>
</tr>
<tr>
	<td class="STHSW200"><?php echo $SearchLang['Farm'];?></td><td class="STHSW250">
	<input type="checkbox" name="Farm"></td>
</tr>
<tr>
	<td class="STHSW200"><?php echo $SearchLang['Max'];?></td><td class="STHSW250">
	<select name="Max" class="STHSW250">
	<option selected value=""><?php echo $SearchLang['Unlimited'];?></option>
	<?php 
	for ($i=5;$i <=100;$i = $i +5)
	{
		echo "<option value=\"" . $i . "\">" . $i . "</option>"; 
	}
	?>
	</select></td>
</tr>
<tr>
	<td class="STHSW200"><?php echo $TeamStatLang['MinimumGamesPlayed'];?></td><td class="STHSW250">
	<input type="checkbox" name="MinGP"></td>
</tr>
<tr>
	<td class="STHSW200"><?php echo $SearchLang['AcsendingOrder'];?></td><td class="STHSW250">
	<?php If ($lang == "fr"){echo "<input type=\"hidden\" name=\"Lang\" value=\"fr\">";}?>
	<input type="checkbox" name="ACS"></td>
</tr>
<tr>
	<td colspan="2" class="STHSCenter"><input type="submit" value="Submit"></td>
</tr>
</table></form></div> 

<div class="DivSection"><h1><?php echo $SearchLang['GoaliesStatsMenu'];?></h1>
<form action="GoaliesStat.php" method="get">
<table class="STHSTable">
<tr>
	<td class="STHSW200"><?php echo $SearchLang['Team'];?></td><td class="STHSW250">
	<select name="Team" class="STHSW250" >
	<option selected value=""><?php echo $SearchLang['AllTeam'];?></option> 
	<?php
	if (empty($TeamName) == false){while ($Row = $TeamName ->fetchArray()) {
		echo "<option value=\"" . $Row['Number'] . "\">" . $Row['Name'] . "</option>"; 
	}}
	?>
	</select></td>
</tr>
<tr>
	<td class="STHSW200"><?php echo $SearchLang['OrderField'];?></td><td class="STHSW250">
	<select name="Order" class="STHSW250">
	<option selected value=""><?php echo $SearchLang['Select'];?></option>
	<?php 
	foreach ($GoaliesStatPossibleOrderField as $Value) {
		echo "<option value=\"" . $Value[0] . "\">" . $Value[1] . "</option>"; 
	} ?>
	</select></td>
</tr>
<tr>
	<td class="STHSW200"><?php echo $SearchLang['Farm'];?></td><td class="STHSW250">
	<input type="checkbox" name="Farm"></td>
</tr>
<tr>
	<td class="STHSW200"><?php echo $SearchLang['Max'];?></td><td class="STHSW250">
	<select name="Max" class="STHSW250">
	<option selected value=""><?php echo $SearchLang['Unlimited'];?></option>
	<?php 
	for ($i=5;$i <=100;$i = $i +5)
	{
		echo "<option value=\"" . $i . "\">" . $i . "</option>"; 
	}
	?>
	</select></td>
</tr>
<tr>
	<td class="STHSW200"><?php echo $TeamStatLang['MinimumGamesPlayed'];?></td><td class="STHSW250">
	<input type="checkbox" name="MinGP"></td>
</tr>
<tr>
	<td class="STHSW200"><?php echo $SearchLang['AcsendingOrder'];?></td><td class="STHSW250">
	<?php If ($lang == "fr"){echo "<input type=\"hidden\" name=\"Lang\" value=\"fr\">";}?>
	<input type="checkbox" name="ACS"></td>
</tr>
<tr>
	<td colspan="2" class="STHSCenter"><input type="submit" value="Submit"></td>
</tr>
</table></form></div>

<div class="DivSection"><h1><?php echo $SearchLang['TeamStatsMenu'];?></h1>
<form action="TeamsStat.php" method="get">
<table class="STHSTable">
<tr>
	<td class="STHSW200"><?php echo $SearchLang['Team'];?></td><td class="STHSW250">
	<select name="Team" class="STHSW250" >
	<option selected value=""><?php echo $SearchLang['AllTeam'];?></option> 
	<?php
	if (empty($TeamName) == false){while ($Row = $TeamName ->fetchArray()) {
		echo "<option value=\"" . $Row['Number'] . "\">" . $Row['Name'] . "</option>"; 
	}}
	?>
	</select></td>
</tr>
<tr>
	<td class="STHSW200"><?php echo $SearchLang['OrderField'];?></td><td class="STHSW250">
	<select name="Order" class="STHSW250">
	<option selected value=""><?php echo $SearchLang['Select'];?></option>
	<?php 
	foreach ($TeamStatPossibleOrderField as $Value) {
		echo "<option value=\"" . $Value[0] . "\">" . $Value[1] . "</option>"; 
	} ?>
	</select></td>
</tr>
<tr>
	<td class="STHSW200"><?php echo $SearchLang['Farm'];?></td><td class="STHSW250">
	<input type="checkbox" name="Farm"></td>
</tr>
<tr>
	<td class="STHSW200"><?php echo $SearchLang['DecendingOrder'];?></td><td class="STHSW250">
	<?php If ($lang == "fr"){echo "<input type=\"hidden\" name=\"Lang\" value=\"fr\">";}?>
	<input type="checkbox" name="DESC"></td>
</tr>
<tr>
	<td colspan="2" class="STHSCenter"><input type="submit" value="Submit"></td>
</tr>
</table></form></div>

</div>

<script type="text/javascript">
jQuery(document).ready(function($){
	$("form").submit(function() {
		$(this).find(":input").filter(function(){ return !this.value; }).attr("disabled", "disabled");
		return true; 
	});
	$( "form" ).find( ":input" ).prop( "disabled", false );
})
</script>

<?php include "Footer.php";?>
