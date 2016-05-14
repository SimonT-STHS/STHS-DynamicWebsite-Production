<!DOCTYPE html>
<?php include "Header.php";?>
<?php
$Title = (string)"";
If (file_exists($DatabaseFile) == false){
	$LeagueName = $DatabaseNotFound;
	$TeamStat = Null;
	echo "<title>" . $DatabaseNotFound . "</title>";
	$Title = $DatabaseNotFound;
}else{
	$DESCQuery = (boolean)FALSE;/* The SQL Query must be Descending Order and not Ascending*/
	$TypeText = (string)"Pro";$TitleType = $DynamicTitleLang['Pro'];
	$LeagueName = (string)"";
	$OrderByField = (string)"Name";
	$OrderByFieldText = (string)"Team Name";
	$OrderByInput = (string)"";
	$Team = (integer)0;
	if(isset($_GET['DESC'])){$DESCQuery= TRUE;}
	if(isset($_GET['Farm'])){$TypeText = "Farm";$TitleType = $DynamicTitleLang['Farm'];}
	if(isset($_GET['Team'])){$Team = filter_var($_GET['Team'], FILTER_SANITIZE_NUMBER_INT);}
	if(isset($_GET['Order'])){$OrderByInput  = filter_var($_GET['Order'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH);} 
	
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
	
	foreach ($TeamStatPossibleOrderField as $Value) {
		If (strtoupper($Value[0]) == strtoupper($OrderByInput)){
			$OrderByField = $Value[0];
			$OrderByFieldText = $Value[1];
			Break;
		}
	}

	$db = new SQLite3($DatabaseFile);
	
	$Query = "Select Name, PointSystemW from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	
	
	If ($Team == 0){
		$Query = "SELECT Team" . $TypeText . "Stat.* FROM Team" . $TypeText . "Stat ORDER BY Team" . $TypeText . "Stat.". $OrderByField;
		$Title = $DynamicTitleLang['TeamStat'] . " " . $TitleType;
	}else{
		$Query = "SELECT Name FROM Team" . $TypeText . "Info WHERE Number = " . $Team ;
		$TeamName = $db->querySingle($Query);
		$Title = $DynamicTitleLang['TeamStatVS'] . " " . $TitleType . " " . $TeamName . " ";
		If ($OrderByField == "Name"){$OrderByField = "Number";}
		$Query = "SELECT MainTable.* FROM (SELECT Team" . $TypeText . "StatVS.TeamVSName AS Name, Team" . $TypeText . "StatVS.TeamVSNumber AS Number, Team" . $TypeText . "StatVS.GP, Team" . $TypeText . "StatVS.W, Team" . $TypeText . "StatVS.L, Team" . $TypeText . "StatVS.T, Team" . $TypeText . "StatVS.OTW, Team" . $TypeText . "StatVS.OTL, Team" . $TypeText . "StatVS.SOW, Team" . $TypeText . "StatVS.SOL, Team" . $TypeText . "StatVS.Points, Team" . $TypeText . "StatVS.GF, Team" . $TypeText . "StatVS.GA, Team" . $TypeText . "StatVS.HomeGP, Team" . $TypeText . "StatVS.HomeW, Team" . $TypeText . "StatVS.HomeL, Team" . $TypeText . "StatVS.HomeT, Team" . $TypeText . "StatVS.HomeOTW, Team" . $TypeText . "StatVS.HomeOTL, Team" . $TypeText . "StatVS.HomeSOW, Team" . $TypeText . "StatVS.HomeSOL, Team" . $TypeText . "StatVS.HomeGF, Team" . $TypeText . "StatVS.HomeGA, Team" . $TypeText . "StatVS.PPAttemp, Team" . $TypeText . "StatVS.PPGoal, Team" . $TypeText . "StatVS.PKAttemp, Team" . $TypeText . "StatVS.PKGoalGA, Team" . $TypeText . "StatVS.PKGoalGF, Team" . $TypeText . "StatVS.ShotsFor, Team" . $TypeText . "StatVS.ShotsAga, Team" . $TypeText . "StatVS.ShotsBlock, Team" . $TypeText . "StatVS.ShotsPerPeriod1, Team" . $TypeText . "StatVS.ShotsPerPeriod2, Team" . $TypeText . "StatVS.ShotsPerPeriod3, Team" . $TypeText . "StatVS.ShotsPerPeriod4, Team" . $TypeText . "StatVS.GoalsPerPeriod1, Team" . $TypeText . "StatVS.GoalsPerPeriod2, Team" . $TypeText . "StatVS.GoalsPerPeriod3, Team" . $TypeText . "StatVS.GoalsPerPeriod4, Team" . $TypeText . "StatVS.PuckTimeInZoneDF, Team" . $TypeText . "StatVS.PuckTimeInZoneOF, Team" . $TypeText . "StatVS.PuckTimeInZoneNT, Team" . $TypeText . "StatVS.PuckTimeControlinZoneDF, Team" . $TypeText . "StatVS.PuckTimeControlinZoneOF, Team" . $TypeText . "StatVS.PuckTimeControlinZoneNT, Team" . $TypeText . "StatVS.Shutouts, Team" . $TypeText . "StatVS.TotalGoal, Team" . $TypeText . "StatVS.TotalAssist, Team" . $TypeText . "StatVS.TotalPoint, Team" . $TypeText . "StatVS.Pim, Team" . $TypeText . "StatVS.Hits, Team" . $TypeText . "StatVS.FaceOffWonDefensifZone, Team" . $TypeText . "StatVS.FaceOffTotalDefensifZone, Team" . $TypeText . "StatVS.FaceOffWonOffensifZone, Team" . $TypeText . "StatVS.FaceOffTotalOffensifZone, Team" . $TypeText . "StatVS.FaceOffWonNeutralZone, Team" . $TypeText . "StatVS.FaceOffTotalNeutralZone, Team" . $TypeText . "StatVS.EmptyNetGoal FROM Team" . $TypeText . "StatVS WHERE GP > 0 AND TeamNumber = " . $Team . " UNION ALL SELECT 'Total' as Name, '104' as Number, Team" . $TypeText . "Stat.GP, Team" . $TypeText . "Stat.W, Team" . $TypeText . "Stat.L, Team" . $TypeText . "Stat.T, Team" . $TypeText . "Stat.OTW, Team" . $TypeText . "Stat.OTL, Team" . $TypeText . "Stat.SOW, Team" . $TypeText . "Stat.SOL, Team" . $TypeText . "Stat.Points, Team" . $TypeText . "Stat.GF, Team" . $TypeText . "Stat.GA, Team" . $TypeText . "Stat.HomeGP, Team" . $TypeText . "Stat.HomeW, Team" . $TypeText . "Stat.HomeL, Team" . $TypeText . "Stat.HomeT, Team" . $TypeText . "Stat.HomeOTW, Team" . $TypeText . "Stat.HomeOTL, Team" . $TypeText . "Stat.HomeSOW, Team" . $TypeText . "Stat.HomeSOL, Team" . $TypeText . "Stat.HomeGF, Team" . $TypeText . "Stat.HomeGA,  Team" . $TypeText . "Stat.PPAttemp, Team" . $TypeText . "Stat.PPGoal, Team" . $TypeText . "Stat.PKAttemp, Team" . $TypeText . "Stat.PKGoalGA, Team" . $TypeText . "Stat.PKGoalGF, Team" . $TypeText . "Stat.ShotsFor, Team" . $TypeText . "Stat.ShotsAga, Team" . $TypeText . "Stat.ShotsBlock, Team" . $TypeText . "Stat.ShotsPerPeriod1, Team" . $TypeText . "Stat.ShotsPerPeriod2, Team" . $TypeText . "Stat.ShotsPerPeriod3, Team" . $TypeText . "Stat.ShotsPerPeriod4, Team" . $TypeText . "Stat.GoalsPerPeriod1, Team" . $TypeText . "Stat.GoalsPerPeriod2, Team" . $TypeText . "Stat.GoalsPerPeriod3, Team" . $TypeText . "Stat.GoalsPerPeriod4, Team" . $TypeText . "Stat.PuckTimeInZoneDF, Team" . $TypeText . "Stat.PuckTimeInZoneOF, Team" . $TypeText . "Stat.PuckTimeInZoneNT, Team" . $TypeText . "Stat.PuckTimeControlinZoneDF, Team" . $TypeText . "Stat.PuckTimeControlinZoneOF, Team" . $TypeText . "Stat.PuckTimeControlinZoneNT, Team" . $TypeText . "Stat.Shutouts, Team" . $TypeText . "Stat.TotalGoal, Team" . $TypeText . "Stat.TotalAssist, Team" . $TypeText . "Stat.TotalPoint, Team" . $TypeText . "Stat.Pim, Team" . $TypeText . "Stat.Hits, Team" . $TypeText . "Stat.FaceOffWonDefensifZone, Team" . $TypeText . "Stat.FaceOffTotalDefensifZone, Team" . $TypeText . "Stat.FaceOffWonOffensifZone, Team" . $TypeText . "Stat.FaceOffTotalOffensifZone, Team" . $TypeText . "Stat.FaceOffWonNeutralZone, Team" . $TypeText . "Stat.FaceOffTotalNeutralZone, Team" . $TypeText . "Stat.EmptyNetGoal FROM Team" . $TypeText . "Stat WHERE Number = " . $Team . ") AS MainTable ORDER BY ". $OrderByField;
	}
	
	/* Order by  */
	If ($DESCQuery == TRUE){
		$Query = $Query . " DESC";
		$Title = $Title . $DynamicTitleLang['InDecendingOrderBy'] . $OrderByFieldText;
	}else{
		$Query = $Query . " ASC";
		$Title = $Title . $DynamicTitleLang['InAscendingOrderBy'] . $OrderByFieldText;
	}

	echo "<title>" . $LeagueName . " - " . $Title . "</title>";
	$TeamStatSub = $db->query($Query);
}
?>

</head><body>
<?php include "Menu.php";?>
<?php echo "<h1>" . $Title . "</h1>"; ?>

<script type="text/javascript">
$(function() {
  $(".STHSPHPTeamsStat_Table").tablesorter({
    widgets: ['columnSelector', 'stickyHeaders', 'filter'],
    widgetOptions : {
      columnSelector_container : $('#tablesorter_ColumnSelector'),
      columnSelector_layout : '<label><input type="checkbox">{name}</label>',
      columnSelector_name  : 'title',
      columnSelector_mediaquery: true,
      columnSelector_mediaqueryName: 'Automatic',
      columnSelector_mediaqueryState: true,
      columnSelector_mediaqueryHidden: true,
      columnSelector_breakpoints : [ '50em', '60em', '70em', '80em', '90em', '95em' ],
	  filter_columnFilters: true,
      filter_placeholder: { search : '<?php echo $TableSorterLang['Search'];?>' },
	  filter_searchDelay : 500,	  
      filter_reset: '.tablesorter_Reset'	 
    }
  });
});
</script>

<div style="width:99%;margin:auto;">

<div class="tablesorter_ColumnSelectorWrapper">
    <input id="tablesorter_colSelect1" type="checkbox" class="hidden">
    <label class="tablesorter_ColumnSelectorButton" for="tablesorter_colSelect1"><?php echo $TableSorterLang['ShoworHideColumn'];?></label>
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

