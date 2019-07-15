<!DOCTYPE html>
<?php include "Header.php";?>
<?php
$Title = (string)"";
$Active = 2; /* Show Webpage Top Menu */
If (file_exists($DatabaseFile) == false){
	$LeagueName = $DatabaseNotFound;
	$TeamStat = Null;
	echo "<title>" . $DatabaseNotFound . "</title>";
	$Title = $DatabaseNotFound;
	$Team = 0;
}else{
	$ACSQuery = (boolean)FALSE;/* The SQL Query must be Ascending Order and not Descending */
	$Playoff = (string)"False";
	$TypeText = (string)"Pro";$TitleType = $DynamicTitleLang['Pro'];
	$LeagueName = (string)"";
	$OrderByField = (string)"Points";
	$OrderByFieldText = (string)"Points";
	$OrderByInput = (string)"";
	$Team = (integer)0;
	$Year = (integer)0;	
	$CareerLeaderSubPrintOut = (int)1;
	if(isset($_GET['ACS'])){$ACSQuery= TRUE;}
	if(isset($_GET['Farm'])){$TypeText = "Farm";$TitleType = $DynamicTitleLang['Farm'];$Active = 3;}
	if(isset($_GET['Playoff'])){$Playoff="True";}
	if(isset($_GET['Order'])){$OrderByInput  = filter_var($_GET['Order'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH);} 
	if(isset($_GET['Year'])){$Year = filter_var($_GET['Year'], FILTER_SANITIZE_NUMBER_INT);} 	
	
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
	array("Points","Points"),
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
	
	foreach ($TeamStatPossibleOrderField as $Value) {
		If (strtoupper($Value[0]) == strtoupper($OrderByInput)){
			$OrderByField = $Value[0];
			$OrderByFieldText = $Value[1];
			Break;
		}
	}

	$db = new SQLite3($DatabaseFile);
	
	$Query = "Select Name, PlayOffStarted, PointSystemW from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	
	$Query = "SELECT 0 as Number, Team" . $TypeText . "StatCareer.Name as Name, Team" . $TypeText . "StatCareer.Year as Year, Team" . $TypeText . "StatCareer.Name as OrderName,  Team" . $TypeText . "StatCareer.GP AS GP, Team" . $TypeText . "StatCareer.W AS W, Team" . $TypeText . "StatCareer.L AS L, Team" . $TypeText . "StatCareer.T AS T, Team" . $TypeText . "StatCareer.OTW AS OTW, Team" . $TypeText . "StatCareer.OTL AS OTL, Team" . $TypeText . "StatCareer.SOW AS SOW, Team" . $TypeText . "StatCareer.SOL AS SOL, Team" . $TypeText . "StatCareer.Points AS Points, Team" . $TypeText . "StatCareer.GF AS GF, Team" . $TypeText . "StatCareer.GA AS GA, Team" . $TypeText . "StatCareer.HomeGP AS HomeGP, Team" . $TypeText . "StatCareer.HomeW AS HomeW, Team" . $TypeText . "StatCareer.HomeL AS HomeL, Team" . $TypeText . "StatCareer.HomeT AS HomeT, Team" . $TypeText . "StatCareer.HomeOTW AS HomeOTW, Team" . $TypeText . "StatCareer.HomeOTL AS HomeOTL, Team" . $TypeText . "StatCareer.HomeSOW AS HomeSOW, Team" . $TypeText . "StatCareer.HomeSOL AS HomeSOL, Team" . $TypeText . "StatCareer.HomeGF AS HomeGF, Team" . $TypeText . "StatCareer.HomeGA AS HomeGA, Team" . $TypeText . "StatCareer.PPAttemp AS PPAttemp, Team" . $TypeText . "StatCareer.PPGoal AS PPGoal, Team" . $TypeText . "StatCareer.PKAttemp AS PKAttemp, Team" . $TypeText . "StatCareer.PKGoalGA AS PKGoalGA, Team" . $TypeText . "StatCareer.PKGoalGF AS PKGoalGF, Team" . $TypeText . "StatCareer.ShotsFor AS ShotsFor, Team" . $TypeText . "StatCareer.ShotsAga AS ShotsAga, Team" . $TypeText . "StatCareer.ShotsBlock AS ShotsBlock, Team" . $TypeText . "StatCareer.ShotsPerPeriod1 AS ShotsPerPeriod1, Team" . $TypeText . "StatCareer.ShotsPerPeriod2 AS ShotsPerPeriod2, Team" . $TypeText . "StatCareer.ShotsPerPeriod3 AS ShotsPerPeriod3, Team" . $TypeText . "StatCareer.ShotsPerPeriod4 AS ShotsPerPeriod4, Team" . $TypeText . "StatCareer.GoalsPerPeriod1 AS GoalsPerPeriod1, Team" . $TypeText . "StatCareer.GoalsPerPeriod2 AS GoalsPerPeriod2, Team" . $TypeText . "StatCareer.GoalsPerPeriod3 AS GoalsPerPeriod3, Team" . $TypeText . "StatCareer.GoalsPerPeriod4 AS GoalsPerPeriod4, Team" . $TypeText . "StatCareer.PuckTimeInZoneDF AS PuckTimeInZoneDF, Team" . $TypeText . "StatCareer.PuckTimeInZoneOF AS PuckTimeInZoneOF, Team" . $TypeText . "StatCareer.PuckTimeInZoneNT AS PuckTimeInZoneNT, Team" . $TypeText . "StatCareer.PuckTimeControlinZoneDF AS PuckTimeControlinZoneDF, Team" . $TypeText . "StatCareer.PuckTimeControlinZoneOF AS PuckTimeControlinZoneOF, Team" . $TypeText . "StatCareer.PuckTimeControlinZoneNT AS PuckTimeControlinZoneNT, Team" . $TypeText . "StatCareer.Shutouts AS Shutouts, Team" . $TypeText . "StatCareer.TotalGoal AS TotalGoal, Team" . $TypeText . "StatCareer.TotalAssist AS TotalAssist, Team" . $TypeText . "StatCareer.TotalPoint AS TotalPoint, Team" . $TypeText . "StatCareer.Pim AS Pim, Team" . $TypeText . "StatCareer.Hits AS Hits, Team" . $TypeText . "StatCareer.FaceOffWonDefensifZone AS FaceOffWonDefensifZone, Team" . $TypeText . "StatCareer.FaceOffTotalDefensifZone AS FaceOffTotalDefensifZone, Team" . $TypeText . "StatCareer.FaceOffWonOffensifZone AS FaceOffWonOffensifZone, Team" . $TypeText . "StatCareer.FaceOffTotalOffensifZone AS FaceOffTotalOffensifZone, Team" . $TypeText . "StatCareer.FaceOffWonNeutralZone AS FaceOffWonNeutralZone, Team" . $TypeText . "StatCareer.FaceOffTotalNeutralZone AS FaceOffTotalNeutralZone, Team" . $TypeText . "StatCareer.EmptyNetGoal AS EmptyNetGoal FROM Team" . $TypeText . "StatCareer WHERE Playoff = '" . $Playoff . "'";
	If($Year > 0){$Query = $Query ." AND YEAR = '" . $Year . "'";}
	$Query = $Query . " ORDER BY ". $OrderByField;
	
	If ($Playoff=="True"){$Title = $PlayersLang['Playoff'] .  " ";}
	$Title = $Title . $DynamicTitleLang['CareerStatByYear'];
	If ($Year != ""){$Title = $Title . $Year . " - ";}
	$Title = $Title . $DynamicTitleLang['TeamStat'] . " " . $TitleType;
	
	/* Order by  */
	If ($ACSQuery == TRUE){
		$Query = $Query . " ASC";
		$Title = $Title . $DynamicTitleLang['InAscendingOrderBy'] . $OrderByFieldText;
	}else{
		$Query = $Query . " DESC";
		$Title = $Title . $DynamicTitleLang['InDecendingOrderBy'] . $OrderByFieldText;
	}

	echo "<title>" . $LeagueName . " - " . $Title . "</title>";

	If (file_exists($CareerStatDatabaseFile) == true){ /* CareerStat */
		$CareerStatdb = new SQLite3($CareerStatDatabaseFile);
		$TeamStatSub = $CareerStatdb->query($Query);
	}	
	
}
?>

</head><body>
<?php include "Menu.php";?>
<?php echo "<h1>" . $Title . "</h1>"; ?>

<script>
$(function() {
  $.tablesorter.addWidget({ id: "numbering",format: function(table) {var c = table.config;$("tr:visible", table.tBodies[0]).each(function(i) {$(this).find('td').eq(0).text(i + 1);});}});	
  $(".STHSPHPTeamsStat_Table").tablesorter({
    widgets: ['numbering','columnSelector', 'stickyHeaders', 'filter', 'output'],
    widgetOptions : {
      columnSelector_container : $('#tablesorter_ColumnSelector'),
      columnSelector_layout : '<label><input type="checkbox">{name}</label>',
      columnSelector_name  : 'title',
      columnSelector_mediaquery: true,
      columnSelector_mediaqueryName: 'Automatic',
      columnSelector_mediaqueryState: true,
      columnSelector_mediaqueryHidden: true,
      columnSelector_breakpoints : [ '20em', '40em', '60em', '80em', '90em', '95em' ],
	  filter_columnFilters: true,
      filter_placeholder: { search : '<?php echo $TableSorterLang['Search'];?>' },
	  filter_searchDelay : 500,	  
      filter_reset: '.tablesorter_Reset',	 
	  output_delivery: 'd',
	  output_saveFileName: 'STHSTeamStat.CSV'
    }
  });
  $('.download').click(function(){
      var $table = $('.STHSPHPTeamsStat_Table'),
      wo = $table[0].config.widgetOptions;
      $table.trigger('outputTable');
      return false;
  });  
});
</script>

<div style="width:99%;margin:auto;">

<div class="tablesorter_ColumnSelectorWrapper">
    <input id="tablesorter_colSelect1" type="checkbox" class="hidden">
    <label class="tablesorter_ColumnSelectorButton" for="tablesorter_colSelect1"><?php echo $TableSorterLang['ShoworHideColumn'];?></label>
	<button class="tablesorter_Output download" type="button">Output</button>
    <div id="tablesorter_ColumnSelector" class="tablesorter_ColumnSelector"></div>
	<?php include "FilterTip.php";?>
	</div>
</div>

<table class="tablesorter STHSPHPTeamsStat_Table"><thead><tr>
<?php include "TeamsStatSub.php";?>
</tbody></table>
</div>


<?php
include "Footer.php";
?>

