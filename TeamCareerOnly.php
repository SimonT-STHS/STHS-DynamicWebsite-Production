<?php include "Header.php";
If ($lang == "fr"){include 'LanguageFR-Stat.php';}else{include 'LanguageEN-Stat.php';}
$Team = (integer)0;
$TypeText = (string)"Pro";
$Pro = (boolean)True; 
$LeagueName = (string)"";
$Query = (string)"";
$TeamName = $TeamLang['IncorrectTeam'];
if(isset($_GET['Team'])){$Team = filter_var($_GET['Team'], FILTER_SANITIZE_NUMBER_INT);}
if(isset($_GET['Farm'])){$Pro = False;$TypeText="Farm";} 

$Title = (string)"";
try{
If (file_exists($DatabaseFile) == false){
	$Team = 0;
	$LeagueName = $DatabaseNotFound;
}else{
	$db = new SQLite3($DatabaseFile);
}
If ($Team == 0 OR $Team > 100){
	$Team = 0;
	echo "<style>.STHSPHPTeamStat_Main {display:none;}</style>";
	Goto TeamCareerError;
}else{
	$Query = "SELECT count(*) AS count FROM TeamProInfo WHERE Number = " . $Team;
	$Result = $db->querySingle($Query,true);
	If ($Result['count'] == 1){
		If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS Start Page PHP Performance : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}
				
		$Query = "SELECT Name, UniqueID FROM TeamProInfo WHERE Number = " . $Team;
		$TeamInfo = $db->querySingle($Query,true);
		$Query = "SELECT Name FROM TeamFarmInfo WHERE Number = " . $Team;
		$TeamFarmInfo = $db->querySingle($Query,true);			

		$Query = "Select Name from LeagueGeneral";
		$LeagueGeneral = $db->querySingle($Query,true);
		
		$LeagueName = $LeagueGeneral['Name'];
		If ($Pro == true){$TeamName = $TeamInfo['Name'];}else{$TeamName = $TeamFarmInfo['Name'];}
		If (file_exists($CareerStatDatabaseFile) == true){ /* CareerStat */
			$CareerStatdb = new SQLite3($CareerStatDatabaseFile);
						
			$CareerDBFormatV2CheckCheck = $CareerStatdb->querySingle("SELECT Count(name) AS CountName FROM sqlite_master WHERE type='table' AND name='LeagueGeneral'",true);
			If ($CareerDBFormatV2CheckCheck['CountName'] == 1){
				$CareerStatdb->query("ATTACH DATABASE '".realpath($DatabaseFile)."' AS CurrentDB");
				
				include "APIFunction.php";
				If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS CareerStat Start Page PHP Performance : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}
				$TeamCareerSeason = APIPost(array('TeamStat'.$TypeText.'HistoryAllSeasonPerYear' => '', 'Team' => $TeamInfo['UniqueID']));
				If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS CareerStat TeamCareerSeason Page PHP Performance : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}
				$TeamCareerSumSeasonOnly = APIPost(array('TeamStat'.$TypeText.'HistoryAllSeasonMerge' => '', 'Team' => $TeamInfo['UniqueID'], 'ReturnOnlyTeamData' => '' ));
				If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS CareerStat TeamCareerSumSeasonOnly  Page PHP Performance : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}
				$TeamCareerPlayoff = APIPost(array('TeamStat'.$TypeText.'HistoryAllSeasonPerYear' => '', 'Team' => $TeamInfo['UniqueID'], 'Playoff' => ''));
				If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS CareerStat TeamCareerPlayoff Page PHP Performance : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}
				$TeamCareerSumPlayoffOnly =  APIPost(array('TeamStat'.$TypeText.'HistoryAllSeasonMerge' => '', 'Team' => $TeamInfo['UniqueID'], 'ReturnOnlyTeamData' => '', 'Playoff' => '' ));
				If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS CareerStat TeamCareerSumPlayoffOnly Page PHP Performance : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}
				$TeamCareerPlayersSeasonTop5 = APIPost(array('PlayerStat'.$TypeText.'HistoryAllSeasonMerge' => '', 'Team' => $TeamInfo['UniqueID'], 'Max' => '5'));
				If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS CareerStat TeamCareerPlayersSeasonTop5 Page PHP Performance : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}
				$TeamCareerPlayersPlayoffTop5  = APIPost(array('PlayerStat'.$TypeText.'HistoryAllSeasonMerge' => '', 'Team' => $TeamInfo['UniqueID'], 'Max' => '5', 'Playoff' => '' ));
				If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS CareerStat TeamCareerPlayersPlayoffTop5 Page PHP Performance : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}
				$TeamCareerGoaliesSeasonTop5 = APIPost(array('GoalerStat'.$TypeText.'HistoryAllSeasonMerge' => '', 'Team' => $TeamInfo['UniqueID'], 'Max' => '5'));
				If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS CareerStat TeamCareerGoaliesSeasonTop5 Page PHP Performance : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}
				$TeamCareerGoaliesPlayoffTop5 = APIPost(array('GoalerStat'.$TypeText.'HistoryAllSeasonMerge' => '', 'Team' => $TeamInfo['UniqueID'], 'Max' => '5', 'Playoff' => '' ));
				If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS CareerStat TeamCareerGoaliesPlayoffTop Page PHP Performance : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}
			}else{
				Goto TeamCareerError;
			}
		}else{
			Goto TeamCareerError;
		}
	}else{		
		$TeamName = $TeamLang['Teamnotfound'];
		echo "<style>.STHSPHPTeamStat_Main {display:none;}</style>";
		Goto TeamCareerError;
	}
}} catch (Exception $e) {
	$Team = 0;
	$LeagueName = $DatabaseNotFound;
TeamCareerError:	
	$TeamCareerSeason = Null;
	$TeamCareerPlayoff = Null;
	$TeamCareerSumSeasonOnly = Null;
	$TeamCareerSumPlayoffOnly = Null;	
	$TeamCareerPlayersSeasonTop5 = Null;	
	$TeamCareerPlayersPlayoffTop5 = Null;	
	$TeamCareerGoaliesSeasonTop5 = Null;	
	$TeamCareerGoaliesPlayoffTop5 = Null;		
}

echo "<title>" . $LeagueName . " - " . $TeamLang['CareerTeamStat'] . " - " . $TeamName . "</title>";
If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS Header Page PHP Performance : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}

echo "<style>";
echo "#tablesorter_colSelect11:checked + label {background: #5797d7;  border-color: #555;}\n";
echo "#tablesorter_colSelect11:checked ~ #tablesorter_ColumnSelector11 {display: block;}\n";
echo "#tablesorter_colSelect11SeasonP:checked + label {background: #5797d7;  border-color: #555;}\n";
echo "#tablesorter_colSelect11SeasonP:checked ~ #tablesorter_ColumnSelector11SeasonP {display: block;}\n";
echo "#tablesorter_colSelect11SeasonG:checked + label {background: #5797d7;  border-color: #555;}\n";
echo "#tablesorter_colSelect11SeasonG:checked ~ #tablesorter_ColumnSelector11SeasonG {display: block;}\n";
echo "#tablesorter_colSelect11PlayoffP:checked + label {background: #5797d7;  border-color: #555;}\n";
echo "#tablesorter_colSelect11PlayoffP:checked ~ #tablesorter_ColumnSelector11PlayoffP {display: block;}\n";
echo "#tablesorter_colSelect11PlayoffG:checked + label {background: #5797d7;  border-color: #555;}\n";
echo "#tablesorter_colSelect11PlayoffG:checked ~ #tablesorter_ColumnSelector11PlayoffG {display: block;}\n";	
echo "</style>";
?>

</head><body>
<?php include "Menu.php";?>

<div style="width:99%;margin:auto;">
<?php echo "<h1>" . $TeamLang['CareerTeamStat'] . " - " . $TeamName .  "</h1><br />";?>
<h1><?php echo $TeamLang['CareerPlayerLeaderSeason'];?></h1>
<div class="tablesorter_ColumnSelectorWrapper">
    <input id="tablesorter_colSelect11SeasonP" type="checkbox" class="hidden">
    <label class="tablesorter_ColumnSelectorButton" for="tablesorter_colSelect11SeasonP"><?php echo $TableSorterLang['ShoworHideColumn'];?></label>
    <div id="tablesorter_ColumnSelector11SeasonP" class="tablesorter_ColumnSelector"></div>
</div>

<table class="tablesorter STHSPHPTeam_TeamCareerPlayersSeasonTop5"><thead><tr>
<?php $InputJson = $TeamCareerPlayersSeasonTop5; include "HistorySubForPlayerStat.php";?>

<br /><h1><?php echo $TeamLang['CareerGoaliesLeaderSeason'];?></h1>
<div class="tablesorter_ColumnSelectorWrapper">
    <input id="tablesorter_colSelect11SeasonG" type="checkbox" class="hidden">
    <label class="tablesorter_ColumnSelectorButton" for="tablesorter_colSelect11SeasonG"><?php echo $TableSorterLang['ShoworHideColumn'];?></label>
    <div id="tablesorter_ColumnSelector11SeasonG" class="tablesorter_ColumnSelector"></div>
</div>

<table class="tablesorter STHSPHPTeam_TeamCareerGoaliesSeasonTop5"><thead><tr>
<?php
$InputJson = $TeamCareerGoaliesSeasonTop5;
include "HistorySubForGoalieStat.php";
?>

<br /><h1><?php echo $TeamLang['CareerTeamStats'];?></h1>
<div class="tablesorter_ColumnSelectorWrapper">
    <input id="tablesorter_colSelect11" type="checkbox" class="hidden">
    <label class="tablesorter_ColumnSelectorButton" for="tablesorter_colSelect11"><?php echo $TableSorterLang['ShoworHideColumn'];?></label>
    <div id="tablesorter_ColumnSelector11" class="tablesorter_ColumnSelector"></div>
</div>

<table class="tablesorter STHSPHPTeam_TeamCareerStat"><thead><tr>
<th class="sorter-false"></th><th class="sorter-false" colspan="11"><?php echo $TeamLang['Overall'];?></th><th class="sorter-false" colspan="11"><?php echo $TeamLang['Home'];?></th><th class="sorter-false" colspan="11"><?php echo $TeamLang['Visitor'];?></th><th class="sorter-false" colspan="41"></th></tr><tr>
<th data-priority="critical" title="Year" class="STHSW55"><?php echo $TeamLang['Year'];?></th>
<th data-priority="1" title="Overall Games Played" class="STHSW25">GP</th>
<th data-priority="1" title="Overall Wins" class="STHSW25">W</th>
<th data-priority="1" title="Overall Loss" class="STHSW25">L</th>
<th data-priority="6" title="Overall Ties" class="columnSelector-false STHSW35">T</th>
<th data-priority="1" title="Overall Overtime Wins" class="STHSW25">OTW</th>
<th data-priority="1" title="Overall Overtime Loss" class="STHSW25">OTL</th>
<th data-priority="1" title="Overall Shootout Wins" class="STHSW25">SOW</th>
<th data-priority="1" title="Overall Shootout Loss" class="STHSW25">SOL</th>
<th data-priority="1" title="Overall Goals For" class="STHSW25">GF</th>
<th data-priority="1" title="Overall Goals Against" class="STHSW25">GA</th>
<th data-priority="1" title="Overall Goals For Diffirencial against Goals Against" class="STHSW25">Diff</th>
<th data-priority="3" title="Home Games Played" class="columnSelector-false STHSW25">GP</th>
<th data-priority="3" title="Home Wins" class="columnSelector-false STHSW25">W</th>
<th data-priority="3" title="Home Loss" class="columnSelector-false STHSW25">L</th>
<th data-priority="6" title="Home Ties" class="columnSelector-false STHSW35">T</th>
<th data-priority="3" title="Home Overtime Wins" class="columnSelector-false STHSW25">OTW</th>
<th data-priority="3" title="Home Overtime Loss" class="columnSelector-false STHSW25">OTL</th>
<th data-priority="3" title="Home Shootout Wins" class="columnSelector-false STHSW25">SOW</th>
<th data-priority="3" title="Home Shootout Loss" class="columnSelector-false STHSW25">SOL</th>
<th data-priority="3" title="Home Goals For" class="columnSelector-false STHSW25">GF</th>
<th data-priority="3" title="Home Goals Against" class="columnSelector-false STHSW25">GA</th>
<th data-priority="3" title="Home Goals For Diffirencial against Goals Against" class="columnSelector-false STHSW25">Diff</th>
<th data-priority="5" title="Visitor Games Played" class="columnSelector-false STHSW25">GP</th>
<th data-priority="5" title="Visitor Wins" class="columnSelector-false STHSW25">W</th>
<th data-priority="5" title="Visitor Loss" class="columnSelector-false STHSW25">L</th>
<th data-priority="6" title="Visitor Ties" class="columnSelector-false STHSW35">T</th>
<th data-priority="5" title="Visitor Overtime Wins" class="columnSelector-false STHSW25">OTW</th>
<th data-priority="5" title="Visitor Overtime Loss" class="columnSelector-false STHSW25">OTL</th>
<th data-priority="5" title="Visitor Shootout Wins" class="columnSelector-false STHSW25">SOW</th>
<th data-priority="5" title="Visitor Shootout Loss" class="columnSelector-false STHSW25">SOL</th>
<th data-priority="5" title="Visitor Goals For" class="columnSelector-false STHSW25">GF</th>
<th data-priority="5" title="Visitor Goals Against" class="columnSelector-false STHSW25">GA</th>
<th data-priority="5" title="Visitor Goals For Diffirencial against Goals Against" class="columnSelector-false STHSW25">Diff</th>
<th data-priority="1" title="Points" class="STHSW25">P</th>
<th data-priority="4" title="Total Team Goals" class="STHSW25">G</th>
<th data-priority="4" title="Total Team Assists" class="STHSW25">A</th>
<th data-priority="6" title="Total Team Players Points" class="columnSelector-false STHSW25">TP</th>
<th data-priority="4" title="Shutouts" class="columnSelector-false STHSW25">SO</th>
<th data-priority="4" title="Empty Net Goals" class="columnSelector-false STHSW25">EG</th>
<th data-priority="6" title="Goals for 1st Period" class="columnSelector-false STHSW25">GP1</th>
<th data-priority="6" title="Goals for 2nd Period" class="columnSelector-false STHSW25">GP2</th>
<th data-priority="6" title="Goals for 3rd Period" class="columnSelector-false STHSW25">GP3</th>
<th data-priority="6" title="Goals for 4th Period" class="columnSelector-false STHSW25">GP4</th>
<th data-priority="2" title="Shots For" class="STHSW25">SHF</th>
<th data-priority="6" title="Shots for 1st Period" class="columnSelector-false STHSW25">SH1</th>
<th data-priority="6" title="Shots for 2nd Period" class="columnSelector-false STHSW25">SP2</th>
<th data-priority="6" title="Shots for 3rd Period" class="columnSelector-false STHSW25">SP3</th>
<th data-priority="6" title="Goals for 4th Period" class="columnSelector-false STHSW25">SP4</th>
<th data-priority="2" title="Shots Against" class="STHSW25">SHA</th>
<th data-priority="2" title="Shots Block" class="STHSW25">SHB</th>
<th data-priority="3" title="Penalty Minutes" class="STHSW25">Pim</th>
<th data-priority="3" title="Hits" class="STHSW25">Hit</th>
<th data-priority="6" title="Power Play Attemps" class="columnSelector-false STHSW25">PPA</th>
<th data-priority="5" title="Power Play Goals" class="STHSW25">PPG</th>
<th data-priority="4" title="Power Play %" class="STHSW35">PP%</th>
<th data-priority="6" title="Penalty Kill Attemps" class="columnSelector-false STHSW25">PKA</th>
<th data-priority="5" title="Penalty Kill Goals Against" class="STHSW25">PK GA</th>
<th data-priority="4" title="Penalty Kill %" class="STHSW35">PK%</th>
<th data-priority="6" title="Penalty Kill Goals For" class="columnSelector-false STHSW25">PK GF</th>
<th data-priority="6" title="Won Offensif Zone Faceoff" class="columnSelector-false STHSW35">W OF FO</th>
<th data-priority="6" title="Total Offensif Zone Faceoff" class="columnSelector-false STHSW35">T OF FO</th>
<th data-priority="6" title="Offensif Zone Faceoff %" class="columnSelector-false STHSW35">OF FO%</th>
<th data-priority="6" title="Won Defensif Zone Faceoff" class="columnSelector-false STHSW35">W DF FO</th>
<th data-priority="6" title="Total Defensif Zone Faceoff" class="columnSelector-false STHSW35">T DF FO</th>
<th data-priority="6" title="Defensif Zone Faceoff %" class="columnSelector-false STHSW35">DF FO%</th>
<th data-priority="6" title="Won Neutral Zone Faceoff" class="columnSelector-false STHSW35">W NT FO</th>
<th data-priority="6" title="Total Neutral Zone Faceoff" class="columnSelector-false STHSW35">T NT FO</th>
<th data-priority="6" title="Neutral Zone Faceoff %" class="columnSelector-false STHSW35">NT FO%</th>
<th data-priority="6" title="Puck Time In Offensif Zone" class="columnSelector-false STHSW25">PZ DF</th>
<th data-priority="6" title="Puck Time Control In Offensif Zone" class="columnSelector-false STHSW25">PZ OF</th>
<th data-priority="6" title="Puck Time In Defensif Zone" class="columnSelector-false STHSW25">PZ NT</th>
<th data-priority="6" title="Puck Time Control In Defensif Zone" class="columnSelector-false STHSW25">PC DF</th>
<th data-priority="6" title="Puck Time In Neutral Zone" class="columnSelector-false STHSW25">PC OF</th>
<th data-priority="6" title="Puck Time Control In Neutral Zone" class="columnSelector-false STHSW25">PC NT</th>
</tr></thead><tbody>
<?php
if ($TeamCareerSumSeasonOnly != Null){
	echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"75\"><strong>" . $PlayersLang['RegularSeason'] . "</strong></td></tr>\n";
	if (empty($TeamCareerSeason) == false){foreach($TeamCareerSeason as $row) {
		if ($row['GP'] > 0){
			echo "<tr><td>" . $row['Year'] . "</td>";
			include "HistorySubForTeamStat.php";
		}
	}}
	if (empty($TeamCareerSumSeasonOnly) == false){
		$row = $TeamCareerSumSeasonOnly['0'];
		echo "<tr class=\"static\"><td><strong>" . $PlayersLang['Total'] . " " . $PlayersLang['RegularSeason']. "</strong></td>";
		include "HistorySubForTeamStat.php";
	}
}
if ($TeamCareerSumPlayoffOnly != Null){
	echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"75\"><strong>" . $SearchLang['Playoff'] . "</strong></td></tr>\n";
	if (empty($TeamCareerPlayoff) == false){foreach($TeamCareerPlayoff as $row) {
		if ($row['GP'] > 0){
			echo "<tr><td>" . $row['Year'] . "</td>";
			include "HistorySubForTeamStat.php";
		}
	}}
	if (empty($TeamCareerSumPlayoffOnly) == false){
		$row = $TeamCareerSumPlayoffOnly['0'];
		echo "<tr class=\"static\"><td><strong>" . $PlayersLang['Total'] . " " . $SearchLang['Playoff']. "</strong></td>";
		include "HistorySubForTeamStat.php";
	}
}
?>
</tbody></table>
<br />
<h1><?php echo $TeamLang['CareerPlayerLeaderPlayoff'];?></h1>
<div class="tablesorter_ColumnSelectorWrapper">
    <input id="tablesorter_colSelect11PlayoffP" type="checkbox" class="hidden">
    <label class="tablesorter_ColumnSelectorButton" for="tablesorter_colSelect11PlayoffP"><?php echo $TableSorterLang['ShoworHideColumn'];?></label>
    <div id="tablesorter_ColumnSelector11PlayoffP" class="tablesorter_ColumnSelector"></div>
</div>

<table class="tablesorter STHSPHPTeam_TeamCareerPlayersPlayoffTop5"><thead><tr>
<?php $InputJson = $TeamCareerPlayersPlayoffTop5; include "HistorySubForPlayerStat.php";?>

<br /><h1><?php echo $TeamLang['CareerGoaliesLeaderPlayoff'];?></h1>
<div class="tablesorter_ColumnSelectorWrapper">
    <input id="tablesorter_colSelect11PlayoffG" type="checkbox" class="hidden">
    <label class="tablesorter_ColumnSelectorButton" for="tablesorter_colSelect11PlayoffG"><?php echo $TableSorterLang['ShoworHideColumn'];?></label>
    <div id="tablesorter_ColumnSelector11PlayoffG" class="tablesorter_ColumnSelector"></div>
</div>

<table class="tablesorter STHSPHPTeam_TeamCareerGoaliesPlayoffTop5"><thead><tr>
<?php $InputJson = $TeamCareerGoaliesPlayoffTop5; include "HistorySubForGoalieStat.php";?>
<br /><br /></div>
</div>

<script>
$(function(){
  $.tablesorter.addWidget({ id: "numbering",format: function(table) {var c = table.config;$("tr:visible", table.tBodies[0]).each(function(i) {$(this).find('td').eq(0).text(i + 1);});}});
    <?php
	  echo "\$(\".STHSPHPTeam_TeamCareerStat\").tablesorter({widgets: ['staticRow', 'columnSelector','filter'], widgetOptions : {columnSelector_container : \$('#tablesorter_ColumnSelector11'), columnSelector_layout : '<label><input type=\"checkbox\">{name}</label>', columnSelector_name  : 'title', columnSelector_mediaquery: true, columnSelector_mediaqueryName: 'Automatic', columnSelector_mediaqueryState: true, columnSelector_mediaqueryHidden: true, columnSelector_breakpoints : [ '20em', '40em', '60em', '80em', '90em', '95em' ],filter_columnFilters: false,}});";
	  echo "\$(\".STHSPHPTeam_TeamCareerPlayersSeasonTop5\").tablesorter({widgets: ['staticRow', 'columnSelector','filter'], widgetOptions : {columnSelector_container : \$('#tablesorter_ColumnSelector11SeasonP'), columnSelector_layout : '<label><input type=\"checkbox\">{name}</label>', columnSelector_name  : 'title', columnSelector_mediaquery: true, columnSelector_mediaqueryName: 'Automatic', columnSelector_mediaqueryState: true, columnSelector_mediaqueryHidden: true, columnSelector_breakpoints : [ '20em', '40em', '60em', '80em', '90em', '95em' ],filter_columnFilters: false,}});";
	  echo "\$(\".STHSPHPTeam_TeamCareerGoaliesSeasonTop5\").tablesorter({widgets: ['staticRow', 'columnSelector','filter'], widgetOptions : {columnSelector_container : \$('#tablesorter_ColumnSelector11SeasonG'), columnSelector_layout : '<label><input type=\"checkbox\">{name}</label>', columnSelector_name  : 'title', columnSelector_mediaquery: true, columnSelector_mediaqueryName: 'Automatic', columnSelector_mediaqueryState: true, columnSelector_mediaqueryHidden: true, columnSelector_breakpoints : [ '20em', '40em', '60em', '80em', '90em', '95em' ],filter_columnFilters: false,}});";
	  echo "\$(\".STHSPHPTeam_TeamCareerPlayersPlayoffTop5\").tablesorter({widgets: ['staticRow', 'columnSelector','filter'], widgetOptions : {columnSelector_container : \$('#tablesorter_ColumnSelector11PlayoffP'), columnSelector_layout : '<label><input type=\"checkbox\">{name}</label>', columnSelector_name  : 'title', columnSelector_mediaquery: true, columnSelector_mediaqueryName: 'Automatic', columnSelector_mediaqueryState: true, columnSelector_mediaqueryHidden: true, columnSelector_breakpoints : [ '20em', '40em', '60em', '80em', '90em', '95em' ],filter_columnFilters: false,}});";
	  echo "\$(\".STHSPHPTeam_TeamCareerGoaliesPlayoffTop5\").tablesorter({widgets: ['staticRow', 'columnSelector','filter'], widgetOptions : {columnSelector_container : \$('#tablesorter_ColumnSelector11PlayoffG'), columnSelector_layout : '<label><input type=\"checkbox\">{name}</label>', columnSelector_name  : 'title', columnSelector_mediaquery: true, columnSelector_mediaqueryName: 'Automatic', columnSelector_mediaqueryState: true, columnSelector_mediaqueryHidden: true, columnSelector_breakpoints : [ '20em', '40em', '60em', '80em', '90em', '95em' ],filter_columnFilters: false,}});";	
   ?>
});
</script>


<?php include "Footer.php";?>
