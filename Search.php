<?php include "Header.php";
$Search = (boolean)True;
$CareerStat = (boolean)False;

//Empty Variable for Search*****.php webpage to work without issue.
$Team = -1;
$ACSQuery = (boolean)False;
$DESCQuery = (boolean)False;
$TypeText = "";
$Expansion = (boolean)False;
$AvailableForTrade = (boolean)False;
$Injury = (boolean)False;
$Retire = (string )"";
$MinGP = (boolean)False;
$Playoff = (string)"False";
$Rookie = (boolean)False;
$PosC = (boolean)FALSE; $PosLW = (boolean)FALSE; $PosRW = (boolean)FALSE; $PosD = (boolean)FALSE;$PosF = (boolean)FALSE; $PosD = (boolean)FALSE;
$MaximumResult = (integer)0;
$Type = (integer)0;
$FreeAgentYear = (integer)-1;
$OrderByInput  = (string)"";
$Year = (integer)0;	

include "SearchPossibleOrderField.php";

$LeagueName = (string)"";

If (file_exists($DatabaseFile) == false){
	Goto STHSErrorSearch;
}else{try{
	$CareerStat = TRUE;
	$db = new SQLite3($DatabaseFile);
	$Query = "SELECT Number, Name FROM TeamProInfo Order By Name";
	$TeamName = $db->query($Query);
	
	$Query = "Select FarmEnable from LeagueSimulation";
	$LeagueSimulationMenu = $db->querySingle($Query,true);	
	
	$Query = "Select Name, OutputName, PlayOffStarted from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
} catch (Exception $e) {
STHSErrorSearch:
	$LeagueName = $DatabaseNotFound;
	$TeamName = Null;
	echo "<style>.SearchDiv{display:none}</style>";
	$Title = $DatabaseNotFound;
}}
echo "<title>" . $LeagueName . " - " . $SearchLang['SearchTitle'] . "</title>";
?>
<style>
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
<?php 
$PlayerYear = Null;
$GoalieYear = Null;
$TeamYear = Null;
$PlayerTeamName = Null;
$GoalieTeamName = Null;
$UpdateCareerStatDBV1 = (boolean)false;
If (file_exists($CareerStatDatabaseFile) == false){
	echo "#CareerStatDiv {display:none;}";
}else{try{
	$CareerStatdb = new SQLite3($CareerStatDatabaseFile);
	include "SearchCareerSub.php";	
	include "SearchHistorySub.php";
} catch (Exception $e) {
	echo "#CareerStatDiv {display:none;}";
	$CareerStatDatabaseFile = "";
}}
?>
</style>
</head><body>
<?php include "Menu.php";?>
<?php echo "<h1>" . $SearchLang['SearchTitle'] . "</h1>";?>
<br />

<div class="SearchDiv">

<div class="DivSection"><h1><?php echo $SearchLang['PlayersRosterMenu'];?></h1>
<?php include "SearchPlayersRoster.php";?>
</div>

<div class="DivSection"><h1><?php echo $SearchLang['GoaliesRosterMenu'];?></h1>
<?php include "SearchGoalierRoster.php";?>
</div> 

<div class="DivSection"><h1><?php echo $SearchLang['PlayersInformationMenu'];?></h1>
<?php include "SearchPlayerInfo.php";?>
</div> 

<div class="DivSection"><h1><?php echo $SearchLang['PlayersStatsMenu'];?></h1>
<?php include "SearchPlayersStat.php";?>
</div> 

<div class="DivSection"><h1><?php echo $SearchLang['GoaliesStatsMenu'];?></h1>
<?php include "SearchGoaliesStat.php";?>
</div>

<div class="DivSection"><h1><?php echo $SearchLang['TeamStatsMenu'];?></h1>
<?php include "SearchTeamsStat.php";?>
</div>

<div class="DivSection"><h1><?php echo $SearchLang['ProspectMenu'];?></h1>
<?php include "SearchProspects.php";?>
</div>

<div class="DivSection"><h1><?php echo $SearchLang['TransactionMenu'];?></h1>
<?php include "SearchTransaction.php";?>
</div>

</div>

<?php
If (file_exists($CareerStatDatabaseFile) == True){
$CareerDBFormatV2CheckCheck = $CareerStatdb->querySingle("SELECT Count(name) AS CountName FROM sqlite_master WHERE type='table' AND name='LeagueGeneral'",true);
If ($CareerDBFormatV2CheckCheck['CountName'] == 1){
	echo "<br /><hr />";
	echo "<h1><a id=\"History\">" . $SearchLang['History'] . "</a></h1>";
	echo "<div class=\"SearchDiv\">";
	
	echo "<div class=\"DivSection\"><h1>" . $SearchLang['StandingHistory'] . "</h1>";
	include "SearchHistoryStanding.php";
	echo "</div>";
	
	echo "<div class=\"DivSection\"><h1>" . $SearchLang['PlayersRosterHistory'] . "</h1>";
	include "SearchHistoryPlayersRoster.php";
	echo "</div>";
	
	echo "<div class=\"DivSection\"><h1>" . $SearchLang['GoaliesRosterHistory'] . "</h1>";
	include "SearchHistoryGoalierRoster.php";
	echo "</div>";		
	
	echo "<div class=\"DivSection\"><h1>" . $SearchLang['PlayersInformationHistory'] . "</h1>";
	include "SearchHistoryPlayerInfo.php";
	echo "</div>";	

	echo "<div class=\"DivSection\"><h1>" . $SearchLang['PlayersStatsHistory'] . "</h1>";
	include "SearchHistoryPlayersStat.php";
	echo "</div>";
	
	echo "<div class=\"DivSection\"><h1>" . $SearchLang['GoaliesStatsHistory'] . "</h1>";
	include "SearchHistoryGoaliesStat.php";
	echo "</div>";		
	
	echo "<div class=\"DivSection\"><h1>" . $SearchLang['TeamStatsHistory'] . "</h1>";
	include "SearchHistoryTeamsStat.php";
	echo "</div>";		
	
	echo "<div class=\"DivSection\"><h1>" . $SearchLang['ScheduleHistory'] . "</h1>";
	include "SearchHistorySchedule.php";
	echo "</div>";
	
	echo "<div class=\"DivSection\"><h1>" . $SearchLang['CoachesHistory'] . "</h1>";
	include "SearchHistoryCoaches.php";
	echo "</div>";
	
	echo "<div class=\"DivSection\"><h1>" . $SearchLang['FinanceHistory'] . "</h1>";
	include "SearchHistoryFinance.php";
	echo "</div>";
	
	echo "<div class=\"DivSection\"><h1>" . $SearchLang['ProspectsHistory'] . "</h1>";
	include "SearchHistoryProspects.php";
	echo "</div>";	
	

	echo "</div>";
}}?>

<div id="CareerStatDiv">
<br /><hr />
<h1><a id="CareerStat"><?php echo $SearchLang['CareerStat'];?></a></h1>

<div class="SearchDiv">
<div class="DivSection"><h1><?php echo $SearchLang['PlayersStatsCareer'];?></h1>
<?php include "SearchCareerStatPlayersStat.php";?>
</div> 

<div class="DivSection"><h1><?php echo $SearchLang['GoaliesStatsCareer'];?></h1>
<?php include "SearchCareerStatGoaliesStat.php";?>
</div>

<div class="DivSection"><h1><?php echo $SearchLang['PlayersStatsCareerByYear'];?></h1>
<?php include "SearchCareerStatPlayersStatByYear.php";?>
</div> 

<div class="DivSection"><h1><?php echo $SearchLang['GoaliesStatsCareerByYear'];?></h1>
<?php include "SearchCareerStatGoaliesStatByYear.php";?>
</div>

<div class="DivSection"><h1><?php echo $SearchLang['TeamStatsCareer'];?></h1>
<?php include "SearchCareerStatTeamsStat.php";?>
</div>

<div class="DivSection"><h1><?php echo $SearchLang['TeamStatsByYearCareer'];?></h1>
<?php include "SearchCareerStatTeamsStatByYear.php";?>
</div>

</div>



</div>

<?php include "Footer.php";?>
