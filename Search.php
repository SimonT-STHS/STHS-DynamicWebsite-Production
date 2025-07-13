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
$TradeLogHistory = (boolean)False;

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
	echo "<style>.SearchDiv{display:none}";
	echo ".STHSSearch_MainDiv{display:none;}</style>";
	$Title = $DatabaseNotFound;
}}
echo "<title>" . $LeagueName . " - " . $SearchLang['SearchTitle'] . "</title>";
?>
<script>
function SearchPlayer(str) {
  if (str.length==0) {
    document.getElementById("PlayersLiveSearch").innerHTML="";
    document.getElementById("PlayersLiveSearch").style.border="0px";
    return;
  }
  var xmlhttp=new XMLHttpRequest();
  xmlhttp.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
      document.getElementById("PlayersLiveSearch").innerHTML=this.responseText;
      document.getElementById("PlayersLiveSearch").style.border="1px solid #A5ACB2";
    }
  }
  xmlhttp.open("GET","APISearchLive.php?PlayerSearch="+str,true);
  xmlhttp.send();
}
function STHS_JS_CareerStatToggle() {var ele = document.getElementById('CareerStatToggle');if(ele.style.display == "block") {ele.style.display = "none";} else {ele.style.display = "block";}}

</script>
<style>
.SearchDiv {
    display: grid;
    grid-template-columns: repeat(2, 1fr);     gap: 10px;
}
.DivSection {
	flex-direction: column;
}
#CareerStatToggle {display:none;}
@media (max-width: 920px) { .SearchDiv {grid-template-columns: repeat(1, 1fr); }}
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
<div class="STHSSearch_MainDiv">
<?php echo "<h1>" . $SearchLang['SearchTitle'] . "</h1>";?>
<br>

<div class="SearchDiv">



<div class="DivSection"><h1><?php echo $SearchLang['PlayersRosterMenu'];?></h1>
<?php include "SearchPlayersRoster.php";?>
</div>

<div class="DivSection"><h1><?php echo $SearchLang['GoaliesRosterMenu'];?></h1>
<?php include "SearchGoalierRoster.php";?>
</div> 
<div class="DivSection"><h1><?php echo $SearchLang['PlayersStatsMenu'];?></h1>
<?php include "SearchPlayersStat.php";?>
</div> 

<div class="DivSection"><h1><?php echo $SearchLang['GoaliesStatsMenu'];?></h1>
<?php include "SearchGoaliesStat.php";?>
</div>

<div class="DivSection"><h1><?php echo $SearchLang['PlayersInformationMenu'];?></h1>
<?php include "SearchPlayerInfo.php";?>
</div>

<div class="DivSection"><h1><?php echo $SearchLang['PlayerReport'];?></h1>
<form><table class="STHSTable">
<tr>
	<td class="STHSW200 STHSPHPSearch_Field"><?php echo $SearchLang['PlayerName'];?></td>
	<td class="STHSW250"><input type="text" size="30" placeholder="<?php echo $SearchLang['EnterSearchName'];?>" onkeyup="SearchPlayer(this.value)"><div id="PlayersLiveSearch"></div></td>
</tr>
</table></form>
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
	echo "<br><hr />";
	echo "<h1><a id=\"History\">" . $SearchLang['History'] . "</a></h1>";
	echo "<div class=\"SearchDiv\">";
		
	echo "<div class=\"DivSection\"><h1>" . $SearchLang['PlayersRosterHistory'] . "</h1>";
	include "SearchHistoryPlayersRoster.php";
	echo "</div>";
	
	echo "<div class=\"DivSection\"><h1>" . $SearchLang['GoaliesRosterHistory'] . "</h1>";
	include "SearchHistoryGoalierRoster.php";
	echo "</div>";		
	
	echo "<div class=\"DivSection\"><h1>" . $SearchLang['PlayersStatsHistory'] . "</h1>";
	include "SearchHistoryPlayersStat.php";
	echo "</div>";
	
	echo "<div class=\"DivSection\"><h1>" . $SearchLang['GoaliesStatsHistory'] . "</h1>";
	include "SearchHistoryGoaliesStat.php";
	echo "</div>";		
	
	echo "<div class=\"DivSection\"><h1>" . $SearchLang['PlayersInformationHistory'] . "</h1>";
	include "SearchHistoryPlayerInfo.php";
	echo "</div>";	

	echo "<div class=\"DivSection\"><h1>" . $SearchLang['StandingHistory'] . "</h1>";
	include "SearchHistoryStanding.php";
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
<br><hr />
<h1><a href="javascript:STHS_JS_CareerStatToggle();" id="CareerStat"><?php echo $SearchLang['CareerStat'];?></a></h1>
<div id="CareerStatToggle">
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
</div>
</div>

<?php include "Footer.php";?>
