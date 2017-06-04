<!DOCTYPE html>
<?php include "Header.php";?>
<?php
$Title = (string)"";
$Active = 2; /* Show Webpage Top Menu */
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
	
	$Query = "Select Name from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	
	If (file_exists($CareerStatDatabaseFile) == true){ /* CareerStat */
		$CareerStatdb = new SQLite3($CareerStatDatabaseFile);
		
		$Query = "SELECT Name AS SumOfName, UniqueID, Sum(Team" . $TypeText . "StatCareer.GP) AS SumOfGP, Sum(Team" . $TypeText . "StatCareer.W) AS SumOfW, Sum(Team" . $TypeText . "StatCareer.L) AS SumOfL, Sum(Team" . $TypeText . "StatCareer.T) AS SumOfT, Sum(Team" . $TypeText . "StatCareer.OTW) AS SumOfOTW, Sum(Team" . $TypeText . "StatCareer.OTL) AS SumOfOTL, Sum(Team" . $TypeText . "StatCareer.SOW) AS SumOfSOW, Sum(Team" . $TypeText . "StatCareer.SOL) AS SumOfSOL, Sum(Team" . $TypeText . "StatCareer.Points) AS SumOfPoints, Sum(Team" . $TypeText . "StatCareer.GF) AS SumOfGF, Sum(Team" . $TypeText . "StatCareer.GA) AS SumOfGA, Sum(Team" . $TypeText . "StatCareer.HomeGP) AS SumOfHomeGP, Sum(Team" . $TypeText . "StatCareer.HomeW) AS SumOfHomeW, Sum(Team" . $TypeText . "StatCareer.HomeL) AS SumOfHomeL, Sum(Team" . $TypeText . "StatCareer.HomeT) AS SumOfHomeT, Sum(Team" . $TypeText . "StatCareer.HomeOTW) AS SumOfHomeOTW, Sum(Team" . $TypeText . "StatCareer.HomeOTL) AS SumOfHomeOTL, Sum(Team" . $TypeText . "StatCareer.HomeSOW) AS SumOfHomeSOW, Sum(Team" . $TypeText . "StatCareer.HomeSOL) AS SumOfHomeSOL, Sum(Team" . $TypeText . "StatCareer.HomeGF) AS SumOfHomeGF, Sum(Team" . $TypeText . "StatCareer.HomeGA) AS SumOfHomeGA, Sum(Team" . $TypeText . "StatCareer.PPAttemp) AS SumOfPPAttemp, Sum(Team" . $TypeText . "StatCareer.PPGoal) AS SumOfPPGoal, Sum(Team" . $TypeText . "StatCareer.PKAttemp) AS SumOfPKAttemp, Sum(Team" . $TypeText . "StatCareer.PKGoalGA) AS SumOfPKGoalGA, Sum(Team" . $TypeText . "StatCareer.PKGoalGF) AS SumOfPKGoalGF, Sum(Team" . $TypeText . "StatCareer.ShotsFor) AS SumOfShotsFor, Sum(Team" . $TypeText . "StatCareer.ShotsAga) AS SumOfShotsAga, Sum(Team" . $TypeText . "StatCareer.ShotsBlock) AS SumOfShotsBlock, Sum(Team" . $TypeText . "StatCareer.ShotsPerPeriod1) AS SumOfShotsPerPeriod1, Sum(Team" . $TypeText . "StatCareer.ShotsPerPeriod2) AS SumOfShotsPerPeriod2, Sum(Team" . $TypeText . "StatCareer.ShotsPerPeriod3) AS SumOfShotsPerPeriod3, Sum(Team" . $TypeText . "StatCareer.ShotsPerPeriod4) AS SumOfShotsPerPeriod4, Sum(Team" . $TypeText . "StatCareer.GoalsPerPeriod1) AS SumOfGoalsPerPeriod1, Sum(Team" . $TypeText . "StatCareer.GoalsPerPeriod2) AS SumOfGoalsPerPeriod2, Sum(Team" . $TypeText . "StatCareer.GoalsPerPeriod3) AS SumOfGoalsPerPeriod3, Sum(Team" . $TypeText . "StatCareer.GoalsPerPeriod4) AS SumOfGoalsPerPeriod4, Sum(Team" . $TypeText . "StatCareer.PuckTimeInZoneDF) AS SumOfPuckTimeInZoneDF, Sum(Team" . $TypeText . "StatCareer.PuckTimeInZoneOF) AS SumOfPuckTimeInZoneOF, Sum(Team" . $TypeText . "StatCareer.PuckTimeInZoneNT) AS SumOfPuckTimeInZoneNT, Sum(Team" . $TypeText . "StatCareer.PuckTimeControlinZoneDF) AS SumOfPuckTimeControlinZoneDF, Sum(Team" . $TypeText . "StatCareer.PuckTimeControlinZoneOF) AS SumOfPuckTimeControlinZoneOF, Sum(Team" . $TypeText . "StatCareer.PuckTimeControlinZoneNT) AS SumOfPuckTimeControlinZoneNT, Sum(Team" . $TypeText . "StatCareer.Shutouts) AS SumOfShutouts, Sum(Team" . $TypeText . "StatCareer.TotalGoal) AS SumOfTotalGoal, Sum(Team" . $TypeText . "StatCareer.TotalAssist) AS SumOfTotalAssist, Sum(Team" . $TypeText . "StatCareer.TotalPoint) AS SumOfTotalPoint, Sum(Team" . $TypeText . "StatCareer.Pim) AS SumOfPim, Sum(Team" . $TypeText . "StatCareer.Hits) AS SumOfHits, Sum(Team" . $TypeText . "StatCareer.FaceOffWonDefensifZone) AS SumOfFaceOffWonDefensifZone, Sum(Team" . $TypeText . "StatCareer.FaceOffTotalDefensifZone) AS SumOfFaceOffTotalDefensifZone, Sum(Team" . $TypeText . "StatCareer.FaceOffWonOffensifZone) AS SumOfFaceOffWonOffensifZone, Sum(Team" . $TypeText . "StatCareer.FaceOffTotalOffensifZone) AS SumOfFaceOffTotalOffensifZone, Sum(Team" . $TypeText . "StatCareer.FaceOffWonNeutralZone) AS SumOfFaceOffWonNeutralZone, Sum(Team" . $TypeText . "StatCareer.FaceOffTotalNeutralZone) AS SumOfFaceOffTotalNeutralZone, Sum(Team" . $TypeText . "StatCareer.EmptyNetGoal) AS SumOfEmptyNetGoal FROM Team" . $TypeText . "StatCareer WHERE Playoff = '" . $Playoff . "'";
		If($Year > 0){$Query = $Query ." AND YEAR = '" . $Year . "'";}
		$Query = $Query . " GROUP BY Team" . $TypeText . "StatCareer.UniqueID ORDER BY SumOf" . $OrderByField;

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
if (empty($CareerTeamStat) == false){while ($row = $CareerTeamStat ->fetchArray()) {
	$Order +=1;
	
	$Query = "SELECT Team" . $TypeText . "Stat.* FROM Team" . $TypeText . "Stat LEFT JOIN Team" . $TypeText . "Info ON Team" . $TypeText . "Stat.Number = Team" . $TypeText . "Info.Number WHERE Team" . $TypeText . "Info.UniqueID = " . $row['UniqueID'];
	$TeamStat = Null;
	$TeamStat = $db->querySingle($Query,true);
	echo "<tr><td>" . $Order ."</td>";
	
	if ($TeamStat <> Null){
		echo "<td><a href=\"" . $TypeText ."Team.php?Team=" . $TeamStat['Number'] . "\">" . $row['SumOfName'] . "</td>";
		echo "<td>" . ($row['SumOfGP'] + $TeamStat['GP']) . "</td>";
		echo "<td>" . ($row['SumOfW'] + $TeamStat['W']) . "</td>";
		echo "<td>" . ($row['SumOfL'] + $TeamStat['L']) . "</td>";
		echo "<td>" . ($row['SumOfT'] + $TeamStat['T']) . "</td>";
		echo "<td>" . ($row['SumOfOTW'] + $TeamStat['OTW']) . "</td>";	
		echo "<td>" . ($row['SumOfOTL'] + $TeamStat['OTL']) . "</td>";	
		echo "<td>" . ($row['SumOfSOW'] + $TeamStat['SOW']) . "</td>";	
		echo "<td>" . ($row['SumOfSOL'] + $TeamStat['SOL']) . "</td>";	
		echo "<td>" . ($row['SumOfGF'] + $TeamStat['GF']) . "</td>";
		echo "<td>" . ($row['SumOfGA'] + $TeamStat['GA']) . "</td>";
		echo "<td>" . ($row['SumOfGF'] - $row['SumOfGA'] + $TeamStat['GF'] -  $TeamStat['GA']  ) . "</td>";	
		echo "<td>" . ($row['SumOfHomeGP'] + $TeamStat['HomeGP']) . "</td>";
		echo "<td>" . ($row['SumOfHomeW']+ $TeamStat['HomeW']) . "</td>";
		echo "<td>" . ($row['SumOfHomeL'] + $TeamStat['HomeL']) . "</td>";
		echo "<td>" . ($row['SumOfHomeT'] + $TeamStat['HomeT']) . "</td>";
		echo "<td>" . ($row['SumOfHomeOTW'] + $TeamStat['HomeOTW']) . "</td>";	
		echo "<td>" . ($row['SumOfHomeOTL'] + $TeamStat['HomeOTL']) . "</td>";	
		echo "<td>" . ($row['SumOfHomeSOW'] + $TeamStat['HomeSOW']) . "</td>";	
		echo "<td>" . ($row['SumOfHomeSOL'] + $TeamStat['HomeSOL']) . "</td>";	
		echo "<td>" . ($row['SumOfHomeGF'] + $TeamStat['HomeGF']) . "</td>";
		echo "<td>" . ($row['SumOfHomeGA'] + $TeamStat['HomeGA']) . "</td>";	
		echo "<td>" . ($row['SumOfHomeGF'] - $row['SumOfHomeGA'] + $TeamStat['GF'] -  $TeamStat['HomeGA']  ) . "</td>";	
		echo "<td>" . ($row['SumOfGP'] - $row['SumOfHomeGP'] + $TeamStat['GP'] -  $TeamStat['HomeGP']) . "</td>";
		echo "<td>" . ($row['SumOfW'] - $row['SumOfHomeW'] + $TeamStat['W'] -  $TeamStat['HomeW']) . "</td>";
		echo "<td>" . ($row['SumOfL'] - $row['SumOfHomeL'] + $TeamStat['L'] -  $TeamStat['HomeL']) . "</td>";
		echo "<td>" . ($row['SumOfT'] - $row['SumOfHomeT'] + $TeamStat['T'] -  $TeamStat['HomeT']	) . "</td>";	
		echo "<td>" . ($row['SumOfOTW'] - $row['SumOfHomeOTW'] + $TeamStat['OTW'] -  $TeamStat['HomeOTW']) . "</td>";
		echo "<td>" . ($row['SumOfOTL'] - $row['SumOfHomeOTL'] + $TeamStat['OTL'] -  $TeamStat['HomeOTL']) . "</td>";
		echo "<td>" . ($row['SumOfSOW'] - $row['SumOfHomeSOW'] + $TeamStat['SOW'] -  $TeamStat['HomeSOW']) . "</td>";
		echo "<td>" . ($row['SumOfSOL'] - $row['SumOfHomeSOL'] + $TeamStat['SOL'] -  $TeamStat['HomeSOL']) . "</td>";
		echo "<td>" . ($row['SumOfGF'] - $row['SumOfHomeGF'] + $TeamStat['GF'] -  $TeamStat['HomeGF']) . "</td>";
		echo "<td>" . ($row['SumOfGA'] - $row['SumOfHomeGA'] + $TeamStat['GA'] -  $TeamStat['HomeGA']) . "</td>";
		echo "<td>" . (($row['SumOfGF'] - $row['SumOfHomeGF']) - ($row['SumOfGA'] - $row['SumOfHomeGA']) + ($TeamStat['GF'] - $TeamStat['HomeGF']) - ($TeamStat['GA'] - $TeamStat['HomeGA'])) . "</td>";		
		echo "<td><strong>" . ($row['SumOfPoints'] + $TeamStat['Points']) . "</strong></td>";
		echo "<td>" . ($row['SumOfTotalGoal'] + $TeamStat['TotalGoal']) . "</td>";
		echo "<td>" . ($row['SumOfTotalAssist'] + $TeamStat['TotalAssist']) . "</td>";
		echo "<td>" . ($row['SumOfTotalPoint'] + $TeamStat['TotalPoint']) . "</td>";
		echo "<td>" . ($row['SumOfEmptyNetGoal'] + $TeamStat['EmptyNetGoal']) . "</td>";
		echo "<td>" . ($row['SumOfShutouts'] + $TeamStat['Shutouts']) . "</td>";		
		echo "<td>" . ($row['SumOfGoalsPerPeriod1'] + $TeamStat['GoalsPerPeriod1']) . "</td>";		
		echo "<td>" . ($row['SumOfGoalsPerPeriod2'] + $TeamStat['GoalsPerPeriod2']) . "</td>";	
		echo "<td>" . ($row['SumOfGoalsPerPeriod3'] + $TeamStat['GoalsPerPeriod3']) . "</td>";	
		echo "<td>" . ($row['SumOfGoalsPerPeriod4'] + $TeamStat['GoalsPerPeriod4']) . "</td>";	
		echo "<td>" . ($row['SumOfShotsFor'] + $TeamStat['ShotsFor']) . "</td>";	
		echo "<td>" . ($row['SumOfShotsPerPeriod1'] + $TeamStat['ShotsPerPeriod1']) . "</td>";
		echo "<td>" . ($row['SumOfShotsPerPeriod2'] + $TeamStat['ShotsPerPeriod2']) . "</td>";
		echo "<td>" . ($row['SumOfShotsPerPeriod3'] + $TeamStat['ShotsPerPeriod3']) . "</td>";
		echo "<td>" . ($row['SumOfShotsPerPeriod4'] + $TeamStat['ShotsPerPeriod4']) . "</td>";
		echo "<td>" . ($row['SumOfShotsAga'] + $TeamStat['ShotsAga']) . "</td>";
		echo "<td>" . ($row['SumOfShotsBlock'] + $TeamStat['ShotsBlock']) . "</td>";		
		echo "<td>" . ($row['SumOfPim'] + $TeamStat['Pim']) . "</td>";
		echo "<td>" . ($row['SumOfHits'] + $TeamStat['Hits']) . "</td>";	
		echo "<td>" . ($row['SumOfPPAttemp'] + $TeamStat['PPAttemp']) . "</td>";
		echo "<td>" . ($row['SumOfPPGoal'] + $TeamStat['PPGoal']) . "</td>";
		echo "<td>";if (($TeamStat['PPAttemp']+ $row['SumOfPPAttemp']) > 0){echo number_Format(($row['SumOfPPGoal']+$TeamStat['PPGoal']) / ($TeamStat['PPAttemp']+ $row['SumOfPPAttemp']) * 100,2) . "%";} else { echo "0.00%";} echo "</td>";		
		echo "<td>" . ($row['SumOfPKAttemp'] + $TeamStat['PKAttemp']) . "</td>";
		echo "<td>" . ($row['SumOfPKGoalGA'] + $TeamStat['PKGoalGA']) . "</td>";
		echo "<td>";if (($row['SumOfPKAttemp'] + $TeamStat['PKAttemp']) > 0){echo number_Format(( ($row['SumOfPKAttemp'] + $TeamStat['PKAttemp']) - ($row['SumOfPKGoalGA'] + $TeamStat['PKGoalGA'])) / ($row['SumOfPKAttemp'] + $TeamStat['PKAttemp']) * 100,2) . "%";} else {echo "0.00%";} echo "</td>";
		echo "<td>" . ($row['SumOfPKGoalGF'] + $TeamStat['PKGoalGF']) . "</td>";	
		echo "<td>" . ($row['SumOfFaceOffWonOffensifZone'] + $TeamStat['FaceOffWonOffensifZone']) . "</td>";
		echo "<td>" . ($row['SumOfFaceOffTotalOffensifZone'] + $TeamStat['FaceOffTotalOffensifZone']) . "</td>";		
		echo "<td>";if (($row['SumOfFaceOffWonOffensifZone'] + $TeamStat['FaceOffWonOffensifZone']) > 0){echo number_Format(($row['SumOfFaceOffWonOffensifZone'] + $TeamStat['FaceOffWonOffensifZone']) / ($row['SumOfFaceOffTotalOffensifZone'] + $TeamStat['FaceOffTotalOffensifZone']) * 100,2) . "%" ;} else { echo "0.00%";} echo "</td>";	
		echo "<td>" . ($row['SumOfFaceOffWonDefensifZone'] + $TeamStat['FaceOffWonDefensifZone']) . "</td>";
		echo "<td>" . ($row['SumOfFaceOffTotalDefensifZone'] + $TeamStat['FaceOffTotalDefensifZone']) . "</td>";
		echo "<td>";if (($row['SumOfFaceOffWonDefensifZone']+ $TeamStat['FaceOffWonDefensifZone']) > 0){echo number_Format(($row['SumOfFaceOffWonDefensifZone']+ $TeamStat['FaceOffWonDefensifZone']) / ($row['SumOfFaceOffTotalDefensifZone'] + $TeamStat['FaceOffTotalDefensifZone']) * 100,2) . "%" ;} else { echo "0.00%";} echo "</td>";	
		echo "<td>" . ($row['SumOfFaceOffWonNeutralZone'] + $TeamStat['FaceOffWonNeutralZone']) . "</td>";	
		echo "<td>" . ($row['SumOfFaceOffTotalNeutralZone'] + $TeamStat['FaceOffTotalNeutralZone']) . "</td>";	
		echo "<td>";if (($row['SumOfFaceOffWonNeutralZone'] + $TeamStat['FaceOffWonNeutralZone']) > 0){echo number_Format(($row['SumOfFaceOffWonNeutralZone'] + $TeamStat['FaceOffWonNeutralZone']) / ($row['SumOfFaceOffTotalNeutralZone'] + $TeamStat['FaceOffTotalNeutralZone']) * 100,2) . "%" ;} else { echo "0.00%";} echo "</td>";	
		echo "<td>" . Floor(($row['SumOfPuckTimeInZoneOF'] + $TeamStat['PuckTimeInZoneOF']) / 60). "</td>";
		echo "<td>" . Floor(($row['SumOfPuckTimeControlinZoneOF'] + $TeamStat['PuckTimeControlinZoneOF']) / 60). "</td>";
		echo "<td>" . Floor(($row['SumOfPuckTimeInZoneDF'] + $TeamStat['PuckTimeInZoneDF']) / 60). "</td>";
		echo "<td>" . Floor(($row['SumOfPuckTimeControlinZoneDF'] + $TeamStat['PuckTimeControlinZoneDF']) / 60). "</td>";
		echo "<td>" . Floor(($row['SumOfPuckTimeInZoneNT'] + $TeamStat['PuckTimeInZoneNT']) / 60). "</td>";		
		echo "<td>" . Floor(($row['SumOfPuckTimeControlinZoneNT'] + $TeamStat['PuckTimeControlinZoneNT']) / 60). "</td>";		

		echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
	
	}else{
		echo "<tr><td>" . $row['SumOfName'] . "</td>";
		echo "<td>" . $row['SumOfGP'] . "</td>";
		echo "<td>" . $row['SumOfW']  . "</td>";
		echo "<td>" . $row['SumOfL'] . "</td>";
		echo "<td>" . $row['SumOfT'] . "</td>";
		echo "<td>" . $row['SumOfOTW'] . "</td>";	
		echo "<td>" . $row['SumOfOTL'] . "</td>";	
		echo "<td>" . $row['SumOfSOW'] . "</td>";	
		echo "<td>" . $row['SumOfSOL'] . "</td>";	
		echo "<td>" . $row['SumOfGF'] . "</td>";
		echo "<td>" . $row['SumOfGA'] . "</td>";
		echo "<td>" . ($row['SumOfGF'] - $row['SumOfGA']) . "</td>";	
		echo "<td>" . $row['SumOfHomeGP'] . "</td>";
		echo "<td>" . $row['SumOfHomeW']  . "</td>";
		echo "<td>" . $row['SumOfHomeL'] . "</td>";
		echo "<td>" . $row['SumOfHomeT'] . "</td>";
		echo "<td>" . $row['SumOfHomeOTW'] . "</td>";	
		echo "<td>" . $row['SumOfHomeOTL'] . "</td>";	
		echo "<td>" . $row['SumOfHomeSOW'] . "</td>";	
		echo "<td>" . $row['SumOfHomeSOL'] . "</td>";	
		echo "<td>" . $row['SumOfHomeGF'] . "</td>";
		echo "<td>" . $row['SumOfHomeGA'] . "</td>";	
		echo "<td>" . ($row['SumOfHomeGF'] - $row['SumOfHomeGA']) . "</td>";	
		echo "<td>" . ($row['SumOfGP'] - $row['SumOfHomeGP']) . "</td>";
		echo "<td>" . ($row['SumOfW'] - $row['SumOfHomeW']) . "</td>";
		echo "<td>" . ($row['SumOfL'] - $row['SumOfHomeL']) . "</td>";
		echo "<td>" . ($row['SumOfT'] - $row['SumOfHomeT']) . "</td>";	
		echo "<td>" . ($row['SumOfOTW'] - $row['SumOfHomeOTW']) . "</td>";
		echo "<td>" . ($row['SumOfOTL'] - $row['SumOfHomeOTL']) . "</td>";
		echo "<td>" . ($row['SumOfSOW'] - $row['SumOfHomeSOW']) . "</td>";
		echo "<td>" . ($row['SumOfSOL'] - $row['SumOfHomeSOL']) . "</td>";
		echo "<td>" . ($row['SumOfGF'] - $row['SumOfHomeGF']) . "</td>";
		echo "<td>" . ($row['SumOfGA'] - $row['SumOfHomeGA']) . "</td>";
		echo "<td>" . (($row['SumOfGF'] - $row['SumOfHomeGF']) - ($row['SumOfGA'] - $row['SumOfHomeGA'])) . "</td>";		
		echo "<td><strong>" . $row['SumOfPoints'] . "</strong></td>";
		echo "<td>" . $row['SumOfTotalGoal'] . "</td>";
		echo "<td>" . $row['SumOfTotalAssist'] . "</td>";
		echo "<td>" . $row['SumOfTotalPoint'] . "</td>";
		echo "<td>" . $row['SumOfEmptyNetGoal']. "</td>";
		echo "<td>" . $row['SumOfShutouts']. "</td>";		
		echo "<td>" . $row['SumOfGoalsPerPeriod1']. "</td>";		
		echo "<td>" . $row['SumOfGoalsPerPeriod2']. "</td>";	
		echo "<td>" . $row['SumOfGoalsPerPeriod3']. "</td>";	
		echo "<td>" . $row['SumOfGoalsPerPeriod4']. "</td>";	
		echo "<td>" . $row['SumOfShotsFor']. "</td>";	
		echo "<td>" . $row['SumOfShotsPerPeriod1']. "</td>";
		echo "<td>" . $row['SumOfShotsPerPeriod2']. "</td>";
		echo "<td>" . $row['SumOfShotsPerPeriod3']. "</td>";
		echo "<td>" . $row['SumOfShotsPerPeriod4']. "</td>";
		echo "<td>" . $row['SumOfShotsAga']. "</td>";
		echo "<td>" . $row['SumOfShotsBlock']. "</td>";		
		echo "<td>" . $row['SumOfPim']. "</td>";
		echo "<td>" . $row['SumOfHits']. "</td>";	
		echo "<td>" . $row['SumOfPPAttemp']. "</td>";
		echo "<td>" . $row['SumOfPPGoal']. "</td>";
		echo "<td>";if ($row['SumOfPPAttemp'] > 0){echo number_Format($row['SumOfPPGoal'] / $row['SumOfPPAttemp'] * 100,2) . "%";} else { echo "0.00%";} echo "</td>";		
		echo "<td>" . $row['SumOfPKAttemp']. "</td>";
		echo "<td>" . $row['SumOfPKGoalGA']. "</td>";
		echo "<td>";if ($row['SumOfPKAttemp'] > 0){echo number_Format(($row['SumOfPKAttemp'] - $row['SumOfPKGoalGA']) / $row['SumOfPKAttemp'] * 100,2) . "%";} else {echo "0.00%";} echo "</td>";
		echo "<td>" .  $row['SumOfPKGoalGF']. "</td>";	
		echo "<td>" . $row['SumOfFaceOffWonOffensifZone']. "</td>";
		echo "<td>" . $row['SumOfFaceOffTotalOffensifZone']. "</td>";		
		echo "<td>";if ($row['SumOfFaceOffTotalOffensifZone'] > 0){echo number_Format($row['SumOfFaceOffWonOffensifZone'] / $row['SumOfFaceOffTotalOffensifZone'] * 100,2) . "%" ;} else { echo "0.00%";} echo "</td>";	
		echo "<td>" . $row['SumOfFaceOffWonDefensifZone']. "</td>";
		echo "<td>" . $row['SumOfFaceOffTotalDefensifZone']. "</td>";
		echo "<td>";if ($row['SumOfFaceOffTotalDefensifZone'] > 0){echo number_Format($row['SumOfFaceOffWonDefensifZone'] / $row['SumOfFaceOffTotalDefensifZone'] * 100,2) . "%" ;} else { echo "0.00%";} echo "</td>";	
		echo "<td>" . $row['SumOfFaceOffWonNeutralZone']. "</td>";	
		echo "<td>" . $row['SumOfFaceOffTotalNeutralZone']. "</td>";	
		echo "<td>";if ($row['SumOfFaceOffTotalNeutralZone'] > 0){echo number_Format($row['SumOfFaceOffWonNeutralZone'] / $row['SumOfFaceOffTotalNeutralZone'] * 100,2) . "%" ;} else { echo "0.00%";} echo "</td>";	
		echo "<td>" . Floor($row['SumOfPuckTimeInZoneOF']/60). "</td>";
		echo "<td>" . Floor($row['SumOfPuckTimeControlinZoneOF']/60). "</td>";
		echo "<td>" . Floor($row['SumOfPuckTimeInZoneDF']/60). "</td>";
		echo "<td>" . Floor($row['SumOfPuckTimeControlinZoneDF']/60). "</td>";
		echo "<td>" . Floor($row['SumOfPuckTimeInZoneNT']/60). "</td>";		
		echo "<td>" . Floor($row['SumOfPuckTimeControlinZoneNT']/60). "</td>";		
		echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
	}
}}
;?>
</tbody></table>

</div>


<?php
include "Footer.php";
?>

