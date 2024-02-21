<?php include "Header.php";
If ($lang == "fr"){include 'LanguageFR-Stat.php';}else{include 'LanguageEN-Stat.php';}
$Title = (string)"";
$Search = (boolean)False;
$CareerLeaderSubPrintOut = (int)1;
If (file_exists($DatabaseFile) == false){
	Goto CareerStatTeamsStatByYear;
}else{try{
	$ACSQuery = (boolean)FALSE;/* The SQL Query must be Ascending Order and not Descending */
	$Playoff = (string)"False";
	$TypeText = (string)"Pro";$TitleType = $DynamicTitleLang['Pro'];
	$LeagueName = (string)"";
	$OrderByField = (string)"Points";
	$OrderByFieldText = (string)"Points";
	$OrderByInput = (string)"";
	$Team = (integer)0;
	$Year = (integer)0;	
	if(isset($_GET['ACS'])){$ACSQuery= TRUE;}
	if(isset($_GET['Farm'])){$TypeText = "Farm";$TitleType = $DynamicTitleLang['Farm'];}
	if(isset($_GET['Playoff'])){$Playoff="True";}
	if(isset($_GET['Order'])){$OrderByInput  = filter_var($_GET['Order'], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH || FILTER_FLAG_NO_ENCODE_QUOTES || FILTER_FLAG_STRIP_BACKTICK);} 
	if(isset($_GET['Year'])){$Year = filter_var($_GET['Year'], FILTER_SANITIZE_NUMBER_INT);} 	
	
	include "SearchPossibleOrderField.php";
	
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
	
	$Query = "SELECT 0 As TeamThemeID, 0 as Number, Team" . $TypeText . "StatCareer.Name as Name, Team" . $TypeText . "StatCareer.Year as Year, Team" . $TypeText . "StatCareer.Name as OrderName,  Team" . $TypeText . "StatCareer.GP AS GP, Team" . $TypeText . "StatCareer.W AS W, Team" . $TypeText . "StatCareer.L AS L, Team" . $TypeText . "StatCareer.T AS T, Team" . $TypeText . "StatCareer.OTW AS OTW, Team" . $TypeText . "StatCareer.OTL AS OTL, Team" . $TypeText . "StatCareer.SOW AS SOW, Team" . $TypeText . "StatCareer.SOL AS SOL, Team" . $TypeText . "StatCareer.Points AS Points, Team" . $TypeText . "StatCareer.GF AS GF, Team" . $TypeText . "StatCareer.GA AS GA, Team" . $TypeText . "StatCareer.HomeGP AS HomeGP, Team" . $TypeText . "StatCareer.HomeW AS HomeW, Team" . $TypeText . "StatCareer.HomeL AS HomeL, Team" . $TypeText . "StatCareer.HomeT AS HomeT, Team" . $TypeText . "StatCareer.HomeOTW AS HomeOTW, Team" . $TypeText . "StatCareer.HomeOTL AS HomeOTL, Team" . $TypeText . "StatCareer.HomeSOW AS HomeSOW, Team" . $TypeText . "StatCareer.HomeSOL AS HomeSOL, Team" . $TypeText . "StatCareer.HomeGF AS HomeGF, Team" . $TypeText . "StatCareer.HomeGA AS HomeGA, Team" . $TypeText . "StatCareer.PPAttemp AS PPAttemp, Team" . $TypeText . "StatCareer.PPGoal AS PPGoal, Team" . $TypeText . "StatCareer.PKAttemp AS PKAttemp, Team" . $TypeText . "StatCareer.PKGoalGA AS PKGoalGA, Team" . $TypeText . "StatCareer.PKGoalGF AS PKGoalGF, Team" . $TypeText . "StatCareer.ShotsFor AS ShotsFor, Team" . $TypeText . "StatCareer.ShotsAga AS ShotsAga, Team" . $TypeText . "StatCareer.ShotsBlock AS ShotsBlock, Team" . $TypeText . "StatCareer.ShotsPerPeriod1 AS ShotsPerPeriod1, Team" . $TypeText . "StatCareer.ShotsPerPeriod2 AS ShotsPerPeriod2, Team" . $TypeText . "StatCareer.ShotsPerPeriod3 AS ShotsPerPeriod3, Team" . $TypeText . "StatCareer.ShotsPerPeriod4 AS ShotsPerPeriod4, Team" . $TypeText . "StatCareer.GoalsPerPeriod1 AS GoalsPerPeriod1, Team" . $TypeText . "StatCareer.GoalsPerPeriod2 AS GoalsPerPeriod2, Team" . $TypeText . "StatCareer.GoalsPerPeriod3 AS GoalsPerPeriod3, Team" . $TypeText . "StatCareer.GoalsPerPeriod4 AS GoalsPerPeriod4, Team" . $TypeText . "StatCareer.PuckTimeInZoneDF AS PuckTimeInZoneDF, Team" . $TypeText . "StatCareer.PuckTimeInZoneOF AS PuckTimeInZoneOF, Team" . $TypeText . "StatCareer.PuckTimeInZoneNT AS PuckTimeInZoneNT, Team" . $TypeText . "StatCareer.PuckTimeControlinZoneDF AS PuckTimeControlinZoneDF, Team" . $TypeText . "StatCareer.PuckTimeControlinZoneOF AS PuckTimeControlinZoneOF, Team" . $TypeText . "StatCareer.PuckTimeControlinZoneNT AS PuckTimeControlinZoneNT, Team" . $TypeText . "StatCareer.Shutouts AS Shutouts, Team" . $TypeText . "StatCareer.TotalGoal AS TotalGoal, Team" . $TypeText . "StatCareer.TotalAssist AS TotalAssist, Team" . $TypeText . "StatCareer.TotalPoint AS TotalPoint, Team" . $TypeText . "StatCareer.Pim AS Pim, Team" . $TypeText . "StatCareer.Hits AS Hits, Team" . $TypeText . "StatCareer.FaceOffWonDefensifZone AS FaceOffWonDefensifZone, Team" . $TypeText . "StatCareer.FaceOffTotalDefensifZone AS FaceOffTotalDefensifZone, Team" . $TypeText . "StatCareer.FaceOffWonOffensifZone AS FaceOffWonOffensifZone, Team" . $TypeText . "StatCareer.FaceOffTotalOffensifZone AS FaceOffTotalOffensifZone, Team" . $TypeText . "StatCareer.FaceOffWonNeutralZone AS FaceOffWonNeutralZone, Team" . $TypeText . "StatCareer.FaceOffTotalNeutralZone AS FaceOffTotalNeutralZone, Team" . $TypeText . "StatCareer.EmptyNetGoal AS EmptyNetGoal FROM Team" . $TypeText . "StatCareer WHERE Playoff = '" . $Playoff . "'";
	If($Year > 0){$Query = $Query ." AND YEAR = '" . $Year . "'";}
	$Query = $Query . " ORDER BY ". $OrderByField;
	
	If ($Playoff=="True"){$Title = $SearchLang['Playoff'] .  " ";}
	$Title = $Title . $DynamicTitleLang['CareerStatByYear'];
	If ($Year > 0){$Title = $Title . $Year . " - ";}
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
		include "SearchCareerSub.php";	
	}	
} catch (Exception $e) {
CareerStatTeamsStatByYear:
	$LeagueName = $DatabaseNotFound;
	$TeamStat = Null;
	echo "<title>" . $DatabaseNotFound . "</title>";
	$Title = $DatabaseNotFound;
	$Team = 0;	
}}
?>

</head><body>
<?php include "Menu.php";?>
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
      columnSelector_breakpoints : [ '20em', '60em', '85em', '92em', '98em', '99em' ],
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
<?php echo "<h1>" . $Title . "</h1>";?>
<div id="ReQueryDiv" style="display:none;">
<?php  if($LeagueName != $DatabaseNotFound){include "SearchCareerStatTeamsStatByYear.php";}?>
</div>
<div class="tablesorter_ColumnSelectorWrapper">
	<button class="tablesorter_Output" id="ReQuery"><?php echo $SearchLang['ChangeSearch'];?></button>
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

