<!DOCTYPE html>
<?php include "Header.php";?>
<?php
$Title = (string)"";
$Active = 5; /* Show Webpage Top Menu */
If (file_exists($DatabaseFile) == false){
	$LeagueName = $DatabaseNotFound;
	$CareerTeamStat = Null;
	echo "<title>" . $DatabaseNotFound . "</title>";
	$Title = $DatabaseNotFound;
	$Team = 0;
}else{
	$DESCQuery = (boolean)FALSE;/* The SQL Query must be Descending Order and not Ascending*/
	$Playoff = (string)"False";
	$TypeText = (string)"Pro";$TitleType = $DynamicTitleLang['Pro'];
	$LeagueName = (string)"";
	$OrderByField = (string)"Name";
	$OrderByFieldText = (string)"Team Name";
	$OrderByInput = (string)"";
	$Team = (integer)0;
	$Year = (integer)0;	
	if(isset($_GET['DESC'])){$DESCQuery= TRUE;}
	if(isset($_GET['Farm'])){$TypeText = "Farm";$TitleType = $DynamicTitleLang['Farm'];$Active = 3;}
	if(isset($_GET['Playoff'])){$Playoff="True";}
	if(isset($_GET['Order'])){$OrderByInput  = filter_var($_GET['Order'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH);} 
	if(isset($_GET['Year'])){$Year = filter_var($_GET['Year'], FILTER_SANITIZE_NUMBER_INT);} 	
	
	$CareerTeamStatPossibleOrderField = array(
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
	
	foreach ($CareerTeamStatPossibleOrderField as $Value) {
		If (strtoupper($Value[0]) == strtoupper($OrderByInput)){
			$OrderByField = $Value[0];
			$OrderByFieldText = $Value[1];
			Break;
		}
	}

	$db = new SQLite3($DatabaseFile);
	
	$Query = "Select Name,PlayOffStarted from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	
	If (file_exists($CareerStatDatabaseFile) == true){ /* CareerStat */
		$CareerStatdb = new SQLite3($CareerStatDatabaseFile);
		$CareerStatdb->query("ATTACH DATABASE '".$DatabaseFile."' AS CurrentDB");
		
		$Query = "SELECT MainTable.*, Team" . $TypeText . "Stat.* FROM (SELECT Name AS SumOfName, UniqueID, Sum(Team" . $TypeText . "StatCareer.GP) AS SumOfGP, Sum(Team" . $TypeText . "StatCareer.W) AS SumOfW, Sum(Team" . $TypeText . "StatCareer.L) AS SumOfL, Sum(Team" . $TypeText . "StatCareer.T) AS SumOfT, Sum(Team" . $TypeText . "StatCareer.OTW) AS SumOfOTW, Sum(Team" . $TypeText . "StatCareer.OTL) AS SumOfOTL, Sum(Team" . $TypeText . "StatCareer.SOW) AS SumOfSOW, Sum(Team" . $TypeText . "StatCareer.SOL) AS SumOfSOL, Sum(Team" . $TypeText . "StatCareer.Points) AS SumOfPoints, Sum(Team" . $TypeText . "StatCareer.GF) AS SumOfGF, Sum(Team" . $TypeText . "StatCareer.GA) AS SumOfGA, Sum(Team" . $TypeText . "StatCareer.HomeGP) AS SumOfHomeGP, Sum(Team" . $TypeText . "StatCareer.HomeW) AS SumOfHomeW, Sum(Team" . $TypeText . "StatCareer.HomeL) AS SumOfHomeL, Sum(Team" . $TypeText . "StatCareer.HomeT) AS SumOfHomeT, Sum(Team" . $TypeText . "StatCareer.HomeOTW) AS SumOfHomeOTW, Sum(Team" . $TypeText . "StatCareer.HomeOTL) AS SumOfHomeOTL, Sum(Team" . $TypeText . "StatCareer.HomeSOW) AS SumOfHomeSOW, Sum(Team" . $TypeText . "StatCareer.HomeSOL) AS SumOfHomeSOL, Sum(Team" . $TypeText . "StatCareer.HomeGF) AS SumOfHomeGF, Sum(Team" . $TypeText . "StatCareer.HomeGA) AS SumOfHomeGA, Sum(Team" . $TypeText . "StatCareer.PPAttemp) AS SumOfPPAttemp, Sum(Team" . $TypeText . "StatCareer.PPGoal) AS SumOfPPGoal, Sum(Team" . $TypeText . "StatCareer.PKAttemp) AS SumOfPKAttemp, Sum(Team" . $TypeText . "StatCareer.PKGoalGA) AS SumOfPKGoalGA, Sum(Team" . $TypeText . "StatCareer.PKGoalGF) AS SumOfPKGoalGF, Sum(Team" . $TypeText . "StatCareer.ShotsFor) AS SumOfShotsFor, Sum(Team" . $TypeText . "StatCareer.ShotsAga) AS SumOfShotsAga, Sum(Team" . $TypeText . "StatCareer.ShotsBlock) AS SumOfShotsBlock, Sum(Team" . $TypeText . "StatCareer.ShotsPerPeriod1) AS SumOfShotsPerPeriod1, Sum(Team" . $TypeText . "StatCareer.ShotsPerPeriod2) AS SumOfShotsPerPeriod2, Sum(Team" . $TypeText . "StatCareer.ShotsPerPeriod3) AS SumOfShotsPerPeriod3, Sum(Team" . $TypeText . "StatCareer.ShotsPerPeriod4) AS SumOfShotsPerPeriod4, Sum(Team" . $TypeText . "StatCareer.GoalsPerPeriod1) AS SumOfGoalsPerPeriod1, Sum(Team" . $TypeText . "StatCareer.GoalsPerPeriod2) AS SumOfGoalsPerPeriod2, Sum(Team" . $TypeText . "StatCareer.GoalsPerPeriod3) AS SumOfGoalsPerPeriod3, Sum(Team" . $TypeText . "StatCareer.GoalsPerPeriod4) AS SumOfGoalsPerPeriod4, Sum(Team" . $TypeText . "StatCareer.PuckTimeInZoneDF) AS SumOfPuckTimeInZoneDF, Sum(Team" . $TypeText . "StatCareer.PuckTimeInZoneOF) AS SumOfPuckTimeInZoneOF, Sum(Team" . $TypeText . "StatCareer.PuckTimeInZoneNT) AS SumOfPuckTimeInZoneNT, Sum(Team" . $TypeText . "StatCareer.PuckTimeControlinZoneDF) AS SumOfPuckTimeControlinZoneDF, Sum(Team" . $TypeText . "StatCareer.PuckTimeControlinZoneOF) AS SumOfPuckTimeControlinZoneOF, Sum(Team" . $TypeText . "StatCareer.PuckTimeControlinZoneNT) AS SumOfPuckTimeControlinZoneNT, Sum(Team" . $TypeText . "StatCareer.Shutouts) AS SumOfShutouts, Sum(Team" . $TypeText . "StatCareer.TotalGoal) AS SumOfTotalGoal, Sum(Team" . $TypeText . "StatCareer.TotalAssist) AS SumOfTotalAssist, Sum(Team" . $TypeText . "StatCareer.TotalPoint) AS SumOfTotalPoint, Sum(Team" . $TypeText . "StatCareer.Pim) AS SumOfPim, Sum(Team" . $TypeText . "StatCareer.Hits) AS SumOfHits, Sum(Team" . $TypeText . "StatCareer.FaceOffWonDefensifZone) AS SumOfFaceOffWonDefensifZone, Sum(Team" . $TypeText . "StatCareer.FaceOffTotalDefensifZone) AS SumOfFaceOffTotalDefensifZone, Sum(Team" . $TypeText . "StatCareer.FaceOffWonOffensifZone) AS SumOfFaceOffWonOffensifZone, Sum(Team" . $TypeText . "StatCareer.FaceOffTotalOffensifZone) AS SumOfFaceOffTotalOffensifZone, Sum(Team" . $TypeText . "StatCareer.FaceOffWonNeutralZone) AS SumOfFaceOffWonNeutralZone, Sum(Team" . $TypeText . "StatCareer.FaceOffTotalNeutralZone) AS SumOfFaceOffTotalNeutralZone, Sum(Team" . $TypeText . "StatCareer.EmptyNetGoal) AS SumOfEmptyNetGoal FROM Team" . $TypeText . "StatCareer WHERE Playoff = '" . $Playoff . "'";
		If($Year > 0){$Query = $Query ." AND YEAR = '" . $Year . "'";}
		If($Year > 0 OR $LeagueGeneral['PlayOffStarted'] != $Playoff){
			$Query = $Query . " GROUP BY Team" . $TypeText . "StatCareer.UniqueID) AS MainTable LEFT JOIN Team" . $TypeText . "Stat ON MainTable.UniqueID = Team" . $TypeText . "Stat.Number ORDER BY (MainTable.SumOf".$OrderByField.") ";
		}elseif($OrderByField == "ShotsPCT" OR $OrderByField == "AMG" OR $OrderByField == "FaceoffPCT" OR $OrderByField == "P20"){
			$Query = $Query . " GROUP BY Team" . $TypeText . "StatCareer.UniqueID) AS MainTable LEFT JOIN Team" . $TypeText . "Stat ON MainTable.UniqueID = Team" . $TypeText . "Stat.Number ORDER BY Total".$OrderByField." ";
		}else{
			$Query = $Query . " GROUP BY Team" . $TypeText . "StatCareer.UniqueID) AS MainTable LEFT JOIN Team" . $TypeText . "Stat ON MainTable.UniqueID = Team" . $TypeText . "Stat.Number ORDER BY (MainTable.SumOf".$OrderByField." + IfNull(Team" . $TypeText . "Stat.".$OrderByField.",0)) ";
		}
		
		$Title = $DynamicTitleLang['CareerStat'] . $DynamicTitleLang['TeamStat'] . " " . $TitleType;
		
		/* Order by  */
		If ($DESCQuery == TRUE){
			$Query = $Query . " DESC";
			$Title = $Title . $DynamicTitleLang['InDecendingOrderBy'] . $OrderByFieldText;
		}else{
			$Query = $Query . " ASC";
			$Title = $Title . $DynamicTitleLang['InAscendingOrderBy'] . $OrderByFieldText;
		}
		$CareerTeamStat = $CareerStatdb->query($Query);
	}else{
		$CareerTeamStat = Null;
		$Title = $CareeratabaseNotFound;
	}
	
	echo "<title>" . $LeagueName . " - " . $Title . "</title>";
	
}
?>

</head><body>
<?php include "Menu.php";?>
<?php echo "<h1>" . $Title . "</h1>"; ?>

<script type="text/javascript">
$(function() {
  $.tablesorter.addWidget({ id: "numbering",format: function(table) {var c = table.config;$("tr:visible", table.tBodies[0]).each(function(i) {$(this).find('td').eq(0).text(i + 1);});}});	
  $(".STHSPHPTeamsStat_Table").tablesorter({
    widgets: ['numbering', 'columnSelector', 'stickyHeaders', 'filter'],
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
<th class="sorter-false"></th><th class="sorter-false" colspan="12"><?php echo $TeamStatLang['Overall'];?></th><th class="sorter-false" colspan="11"><?php echo $TeamStatLang['Home'];?></th><th class="sorter-false" colspan="11"><?php echo $TeamStatLang['Visitor'];?></th><th class="sorter-false" colspan="42"></th></tr><tr>
<th data-priority="3" title="Order Number" class="STHSW10 sorter-false">#</th>
<th data-priority="critical" title="Team Name" class="STHSW200"><?php If ($Team <> 0){echo "VS ";}?><?php echo $TeamStatLang['TeamName'];?></th>
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
<th data-priority="3" title="Home Games Played" class="STHSW25">GP</th>
<th data-priority="3" title="Home Wins" class="STHSW25">W</th>
<th data-priority="3" title="Home Loss" class="STHSW25">L</th>
<th data-priority="6" title="Home Ties" class="columnSelector-false STHSW35">T</th>
<th data-priority="3" title="Home Overtime Wins" class="STHSW25">OTW</th>
<th data-priority="3" title="Home Overtime Loss" class="STHSW25">OTL</th>
<th data-priority="3" title="Home Shootout Wins" class="STHSW25">SOW</th>
<th data-priority="3" title="Home Shootout Loss" class="STHSW25">SOL</th>
<th data-priority="3" title="Home Goals For" class="STHSW25">GF</th>
<th data-priority="3" title="Home Goals Against" class="STHSW25">GA</th>
<th data-priority="3" title="Home Goals For Diffirencial against Goals Against" class="STHSW25">Diff</th>
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
<th data-priority="6" title="Power Play Goals" class="columnSelector-false STHSW25">PPG</th>
<th data-priority="4" title="Power Play %" class="STHSW35">PP%</th>
<th data-priority="6" title="Penalty Kill Attemps" class="columnSelector-false STHSW25">PKA</th>
<th data-priority="6" title="Penalty Kill Goals Against" class="columnSelector-false STHSW25">PK GA</th>
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
$Order = 0;
if (empty($CareerTeamStat) == false){while ($Row = $CareerTeamStat ->fetchArray()) {
	$Order +=1;
	echo "<tr><td>" . $Order ."</td>";
	If ($Row['Number'] > 0){
		echo "<td><a href=\"" . $TypeText ."Team.php?Team=" . $Row['Number'] . "\">" . $Row['Name'] . "</a></td>";
	}else{
		echo "<td>" . $Row['SumOfName'] . "</td>";	
	}	
	
	If ($Year == 0 AND $LeagueGeneral['PlayOffStarted'] == $Playoff){
		echo "<td>" . ($Row['SumOfGP'] + $Row['GP']) . "</td>";
		echo "<td>" . ($Row['SumOfW'] + $Row['W']) . "</td>";
		echo "<td>" . ($Row['SumOfL'] + $Row['L']) . "</td>";
		echo "<td>" . ($Row['SumOfT'] + $Row['T']) . "</td>";
		echo "<td>" . ($Row['SumOfOTW'] + $Row['OTW']) . "</td>";	
		echo "<td>" . ($Row['SumOfOTL'] + $Row['OTL']) . "</td>";	
		echo "<td>" . ($Row['SumOfSOW'] + $Row['SOW']) . "</td>";	
		echo "<td>" . ($Row['SumOfSOL'] + $Row['SOL']) . "</td>";	
		echo "<td>" . ($Row['SumOfGF'] + $Row['GF']) . "</td>";
		echo "<td>" . ($Row['SumOfGA'] + $Row['GA']) . "</td>";
		echo "<td>" . ($Row['SumOfGF'] - $Row['SumOfGA'] + $Row['GF'] -  $Row['GA']  ) . "</td>";	
		echo "<td>" . ($Row['SumOfHomeGP'] + $Row['HomeGP']) . "</td>";
		echo "<td>" . ($Row['SumOfHomeW']+ $Row['HomeW']) . "</td>";
		echo "<td>" . ($Row['SumOfHomeL'] + $Row['HomeL']) . "</td>";
		echo "<td>" . ($Row['SumOfHomeT'] + $Row['HomeT']) . "</td>";
		echo "<td>" . ($Row['SumOfHomeOTW'] + $Row['HomeOTW']) . "</td>";	
		echo "<td>" . ($Row['SumOfHomeOTL'] + $Row['HomeOTL']) . "</td>";	
		echo "<td>" . ($Row['SumOfHomeSOW'] + $Row['HomeSOW']) . "</td>";	
		echo "<td>" . ($Row['SumOfHomeSOL'] + $Row['HomeSOL']) . "</td>";	
		echo "<td>" . ($Row['SumOfHomeGF'] + $Row['HomeGF']) . "</td>";
		echo "<td>" . ($Row['SumOfHomeGA'] + $Row['HomeGA']) . "</td>";	
		echo "<td>" . ($Row['SumOfHomeGF'] - $Row['SumOfHomeGA'] + $Row['GF'] -  $Row['HomeGA']  ) . "</td>";	
		echo "<td>" . ($Row['SumOfGP'] - $Row['SumOfHomeGP'] + $Row['GP'] -  $Row['HomeGP']) . "</td>";
		echo "<td>" . ($Row['SumOfW'] - $Row['SumOfHomeW'] + $Row['W'] -  $Row['HomeW']) . "</td>";
		echo "<td>" . ($Row['SumOfL'] - $Row['SumOfHomeL'] + $Row['L'] -  $Row['HomeL']) . "</td>";
		echo "<td>" . ($Row['SumOfT'] - $Row['SumOfHomeT'] + $Row['T'] -  $Row['HomeT']	) . "</td>";	
		echo "<td>" . ($Row['SumOfOTW'] - $Row['SumOfHomeOTW'] + $Row['OTW'] -  $Row['HomeOTW']) . "</td>";
		echo "<td>" . ($Row['SumOfOTL'] - $Row['SumOfHomeOTL'] + $Row['OTL'] -  $Row['HomeOTL']) . "</td>";
		echo "<td>" . ($Row['SumOfSOW'] - $Row['SumOfHomeSOW'] + $Row['SOW'] -  $Row['HomeSOW']) . "</td>";
		echo "<td>" . ($Row['SumOfSOL'] - $Row['SumOfHomeSOL'] + $Row['SOL'] -  $Row['HomeSOL']) . "</td>";
		echo "<td>" . ($Row['SumOfGF'] - $Row['SumOfHomeGF'] + $Row['GF'] -  $Row['HomeGF']) . "</td>";
		echo "<td>" . ($Row['SumOfGA'] - $Row['SumOfHomeGA'] + $Row['GA'] -  $Row['HomeGA']) . "</td>";
		echo "<td>" . (($Row['SumOfGF'] - $Row['SumOfHomeGF']) - ($Row['SumOfGA'] - $Row['SumOfHomeGA']) + ($Row['GF'] - $Row['HomeGF']) - ($Row['GA'] - $Row['HomeGA'])) . "</td>";		
		echo "<td><strong>" . ($Row['SumOfPoints'] + $Row['Points']) . "</strong></td>";
		echo "<td>" . ($Row['SumOfTotalGoal'] + $Row['TotalGoal']) . "</td>";
		echo "<td>" . ($Row['SumOfTotalAssist'] + $Row['TotalAssist']) . "</td>";
		echo "<td>" . ($Row['SumOfTotalPoint'] + $Row['TotalPoint']) . "</td>";
		echo "<td>" . ($Row['SumOfEmptyNetGoal'] + $Row['EmptyNetGoal']) . "</td>";
		echo "<td>" . ($Row['SumOfShutouts'] + $Row['Shutouts']) . "</td>";		
		echo "<td>" . ($Row['SumOfGoalsPerPeriod1'] + $Row['GoalsPerPeriod1']) . "</td>";		
		echo "<td>" . ($Row['SumOfGoalsPerPeriod2'] + $Row['GoalsPerPeriod2']) . "</td>";	
		echo "<td>" . ($Row['SumOfGoalsPerPeriod3'] + $Row['GoalsPerPeriod3']) . "</td>";	
		echo "<td>" . ($Row['SumOfGoalsPerPeriod4'] + $Row['GoalsPerPeriod4']) . "</td>";	
		echo "<td>" . ($Row['SumOfShotsFor'] + $Row['ShotsFor']) . "</td>";	
		echo "<td>" . ($Row['SumOfShotsPerPeriod1'] + $Row['ShotsPerPeriod1']) . "</td>";
		echo "<td>" . ($Row['SumOfShotsPerPeriod2'] + $Row['ShotsPerPeriod2']) . "</td>";
		echo "<td>" . ($Row['SumOfShotsPerPeriod3'] + $Row['ShotsPerPeriod3']) . "</td>";
		echo "<td>" . ($Row['SumOfShotsPerPeriod4'] + $Row['ShotsPerPeriod4']) . "</td>";
		echo "<td>" . ($Row['SumOfShotsAga'] + $Row['ShotsAga']) . "</td>";
		echo "<td>" . ($Row['SumOfShotsBlock'] + $Row['ShotsBlock']) . "</td>";		
		echo "<td>" . ($Row['SumOfPim'] + $Row['Pim']) . "</td>";
		echo "<td>" . ($Row['SumOfHits'] + $Row['Hits']) . "</td>";	
		echo "<td>" . ($Row['SumOfPPAttemp'] + $Row['PPAttemp']) . "</td>";
		echo "<td>" . ($Row['SumOfPPGoal'] + $Row['PPGoal']) . "</td>";
		echo "<td>";if (($Row['PPAttemp']+ $Row['SumOfPPAttemp']) > 0){echo number_Format(($Row['SumOfPPGoal']+$Row['PPGoal']) / ($Row['PPAttemp']+ $Row['SumOfPPAttemp']) * 100,2) . "%";} else { echo "0.00%";} echo "</td>";		
		echo "<td>" . ($Row['SumOfPKAttemp'] + $Row['PKAttemp']) . "</td>";
		echo "<td>" . ($Row['SumOfPKGoalGA'] + $Row['PKGoalGA']) . "</td>";
		echo "<td>";if (($Row['SumOfPKAttemp'] + $Row['PKAttemp']) > 0){echo number_Format(( ($Row['SumOfPKAttemp'] + $Row['PKAttemp']) - ($Row['SumOfPKGoalGA'] + $Row['PKGoalGA'])) / ($Row['SumOfPKAttemp'] + $Row['PKAttemp']) * 100,2) . "%";} else {echo "0.00%";} echo "</td>";
		echo "<td>" . ($Row['SumOfPKGoalGF'] + $Row['PKGoalGF']) . "</td>";	
		echo "<td>" . ($Row['SumOfFaceOffWonOffensifZone'] + $Row['FaceOffWonOffensifZone']) . "</td>";
		echo "<td>" . ($Row['SumOfFaceOffTotalOffensifZone'] + $Row['FaceOffTotalOffensifZone']) . "</td>";		
		echo "<td>";if (($Row['SumOfFaceOffWonOffensifZone'] + $Row['FaceOffWonOffensifZone']) > 0){echo number_Format(($Row['SumOfFaceOffWonOffensifZone'] + $Row['FaceOffWonOffensifZone']) / ($Row['SumOfFaceOffTotalOffensifZone'] + $Row['FaceOffTotalOffensifZone']) * 100,2) . "%" ;} else { echo "0.00%";} echo "</td>";	
		echo "<td>" . ($Row['SumOfFaceOffWonDefensifZone'] + $Row['FaceOffWonDefensifZone']) . "</td>";
		echo "<td>" . ($Row['SumOfFaceOffTotalDefensifZone'] + $Row['FaceOffTotalDefensifZone']) . "</td>";
		echo "<td>";if (($Row['SumOfFaceOffWonDefensifZone']+ $Row['FaceOffWonDefensifZone']) > 0){echo number_Format(($Row['SumOfFaceOffWonDefensifZone']+ $Row['FaceOffWonDefensifZone']) / ($Row['SumOfFaceOffTotalDefensifZone'] + $Row['FaceOffTotalDefensifZone']) * 100,2) . "%" ;} else { echo "0.00%";} echo "</td>";	
		echo "<td>" . ($Row['SumOfFaceOffWonNeutralZone'] + $Row['FaceOffWonNeutralZone']) . "</td>";	
		echo "<td>" . ($Row['SumOfFaceOffTotalNeutralZone'] + $Row['FaceOffTotalNeutralZone']) . "</td>";	
		echo "<td>";if (($Row['SumOfFaceOffWonNeutralZone'] + $Row['FaceOffWonNeutralZone']) > 0){echo number_Format(($Row['SumOfFaceOffWonNeutralZone'] + $Row['FaceOffWonNeutralZone']) / ($Row['SumOfFaceOffTotalNeutralZone'] + $Row['FaceOffTotalNeutralZone']) * 100,2) . "%" ;} else { echo "0.00%";} echo "</td>";	
		echo "<td>" . Floor(($Row['SumOfPuckTimeInZoneOF'] + $Row['PuckTimeInZoneOF']) / 60). "</td>";
		echo "<td>" . Floor(($Row['SumOfPuckTimeControlinZoneOF'] + $Row['PuckTimeControlinZoneOF']) / 60). "</td>";
		echo "<td>" . Floor(($Row['SumOfPuckTimeInZoneDF'] + $Row['PuckTimeInZoneDF']) / 60). "</td>";
		echo "<td>" . Floor(($Row['SumOfPuckTimeControlinZoneDF'] + $Row['PuckTimeControlinZoneDF']) / 60). "</td>";
		echo "<td>" . Floor(($Row['SumOfPuckTimeInZoneNT'] + $Row['PuckTimeInZoneNT']) / 60). "</td>";		
		echo "<td>" . Floor(($Row['SumOfPuckTimeControlinZoneNT'] + $Row['PuckTimeControlinZoneNT']) / 60). "</td>";		
		echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
	
	}else{
		echo "<td>" . $Row['SumOfGP'] . "</td>";
		echo "<td>" . $Row['SumOfW']  . "</td>";
		echo "<td>" . $Row['SumOfL'] . "</td>";
		echo "<td>" . $Row['SumOfT'] . "</td>";
		echo "<td>" . $Row['SumOfOTW'] . "</td>";	
		echo "<td>" . $Row['SumOfOTL'] . "</td>";	
		echo "<td>" . $Row['SumOfSOW'] . "</td>";	
		echo "<td>" . $Row['SumOfSOL'] . "</td>";	
		echo "<td>" . $Row['SumOfGF'] . "</td>";
		echo "<td>" . $Row['SumOfGA'] . "</td>";
		echo "<td>" . ($Row['SumOfGF'] - $Row['SumOfGA']) . "</td>";	
		echo "<td>" . $Row['SumOfHomeGP'] . "</td>";
		echo "<td>" . $Row['SumOfHomeW']  . "</td>";
		echo "<td>" . $Row['SumOfHomeL'] . "</td>";
		echo "<td>" . $Row['SumOfHomeT'] . "</td>";
		echo "<td>" . $Row['SumOfHomeOTW'] . "</td>";	
		echo "<td>" . $Row['SumOfHomeOTL'] . "</td>";	
		echo "<td>" . $Row['SumOfHomeSOW'] . "</td>";	
		echo "<td>" . $Row['SumOfHomeSOL'] . "</td>";	
		echo "<td>" . $Row['SumOfHomeGF'] . "</td>";
		echo "<td>" . $Row['SumOfHomeGA'] . "</td>";	
		echo "<td>" . ($Row['SumOfHomeGF'] - $Row['SumOfHomeGA']) . "</td>";	
		echo "<td>" . ($Row['SumOfGP'] - $Row['SumOfHomeGP']) . "</td>";
		echo "<td>" . ($Row['SumOfW'] - $Row['SumOfHomeW']) . "</td>";
		echo "<td>" . ($Row['SumOfL'] - $Row['SumOfHomeL']) . "</td>";
		echo "<td>" . ($Row['SumOfT'] - $Row['SumOfHomeT']) . "</td>";	
		echo "<td>" . ($Row['SumOfOTW'] - $Row['SumOfHomeOTW']) . "</td>";
		echo "<td>" . ($Row['SumOfOTL'] - $Row['SumOfHomeOTL']) . "</td>";
		echo "<td>" . ($Row['SumOfSOW'] - $Row['SumOfHomeSOW']) . "</td>";
		echo "<td>" . ($Row['SumOfSOL'] - $Row['SumOfHomeSOL']) . "</td>";
		echo "<td>" . ($Row['SumOfGF'] - $Row['SumOfHomeGF']) . "</td>";
		echo "<td>" . ($Row['SumOfGA'] - $Row['SumOfHomeGA']) . "</td>";
		echo "<td>" . (($Row['SumOfGF'] - $Row['SumOfHomeGF']) - ($Row['SumOfGA'] - $Row['SumOfHomeGA'])) . "</td>";		
		echo "<td><strong>" . $Row['SumOfPoints'] . "</strong></td>";
		echo "<td>" . $Row['SumOfTotalGoal'] . "</td>";
		echo "<td>" . $Row['SumOfTotalAssist'] . "</td>";
		echo "<td>" . $Row['SumOfTotalPoint'] . "</td>";
		echo "<td>" . $Row['SumOfEmptyNetGoal']. "</td>";
		echo "<td>" . $Row['SumOfShutouts']. "</td>";		
		echo "<td>" . $Row['SumOfGoalsPerPeriod1']. "</td>";		
		echo "<td>" . $Row['SumOfGoalsPerPeriod2']. "</td>";	
		echo "<td>" . $Row['SumOfGoalsPerPeriod3']. "</td>";	
		echo "<td>" . $Row['SumOfGoalsPerPeriod4']. "</td>";	
		echo "<td>" . $Row['SumOfShotsFor']. "</td>";	
		echo "<td>" . $Row['SumOfShotsPerPeriod1']. "</td>";
		echo "<td>" . $Row['SumOfShotsPerPeriod2']. "</td>";
		echo "<td>" . $Row['SumOfShotsPerPeriod3']. "</td>";
		echo "<td>" . $Row['SumOfShotsPerPeriod4']. "</td>";
		echo "<td>" . $Row['SumOfShotsAga']. "</td>";
		echo "<td>" . $Row['SumOfShotsBlock']. "</td>";		
		echo "<td>" . $Row['SumOfPim']. "</td>";
		echo "<td>" . $Row['SumOfHits']. "</td>";	
		echo "<td>" . $Row['SumOfPPAttemp']. "</td>";
		echo "<td>" . $Row['SumOfPPGoal']. "</td>";
		echo "<td>";if ($Row['SumOfPPAttemp'] > 0){echo number_Format($Row['SumOfPPGoal'] / $Row['SumOfPPAttemp'] * 100,2) . "%";} else { echo "0.00%";} echo "</td>";		
		echo "<td>" . $Row['SumOfPKAttemp']. "</td>";
		echo "<td>" . $Row['SumOfPKGoalGA']. "</td>";
		echo "<td>";if ($Row['SumOfPKAttemp'] > 0){echo number_Format(($Row['SumOfPKAttemp'] - $Row['SumOfPKGoalGA']) / $Row['SumOfPKAttemp'] * 100,2) . "%";} else {echo "0.00%";} echo "</td>";
		echo "<td>" .  $Row['SumOfPKGoalGF']. "</td>";	
		echo "<td>" . $Row['SumOfFaceOffWonOffensifZone']. "</td>";
		echo "<td>" . $Row['SumOfFaceOffTotalOffensifZone']. "</td>";		
		echo "<td>";if ($Row['SumOfFaceOffTotalOffensifZone'] > 0){echo number_Format($Row['SumOfFaceOffWonOffensifZone'] / $Row['SumOfFaceOffTotalOffensifZone'] * 100,2) . "%" ;} else { echo "0.00%";} echo "</td>";	
		echo "<td>" . $Row['SumOfFaceOffWonDefensifZone']. "</td>";
		echo "<td>" . $Row['SumOfFaceOffTotalDefensifZone']. "</td>";
		echo "<td>";if ($Row['SumOfFaceOffTotalDefensifZone'] > 0){echo number_Format($Row['SumOfFaceOffWonDefensifZone'] / $Row['SumOfFaceOffTotalDefensifZone'] * 100,2) . "%" ;} else { echo "0.00%";} echo "</td>";	
		echo "<td>" . $Row['SumOfFaceOffWonNeutralZone']. "</td>";	
		echo "<td>" . $Row['SumOfFaceOffTotalNeutralZone']. "</td>";	
		echo "<td>";if ($Row['SumOfFaceOffTotalNeutralZone'] > 0){echo number_Format($Row['SumOfFaceOffWonNeutralZone'] / $Row['SumOfFaceOffTotalNeutralZone'] * 100,2) . "%" ;} else { echo "0.00%";} echo "</td>";	
		echo "<td>" . Floor($Row['SumOfPuckTimeInZoneOF']/60). "</td>";
		echo "<td>" . Floor($Row['SumOfPuckTimeControlinZoneOF']/60). "</td>";
		echo "<td>" . Floor($Row['SumOfPuckTimeInZoneDF']/60). "</td>";
		echo "<td>" . Floor($Row['SumOfPuckTimeControlinZoneDF']/60). "</td>";
		echo "<td>" . Floor($Row['SumOfPuckTimeInZoneNT']/60). "</td>";		
		echo "<td>" . Floor($Row['SumOfPuckTimeControlinZoneNT']/60). "</td>";		
		echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
	}
}}
;?>
</tbody></table>

</div>


<?php
include "Footer.php";
?>

