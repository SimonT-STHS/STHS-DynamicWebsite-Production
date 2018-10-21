<!DOCTYPE html>
<?php include "Header.php";?>
<?php
$LeagueName = (string)"";
$Active = 4; /* Show Webpage Top Menu */

If (file_exists($DatabaseFile) == false){
	$LeagueName = $DatabaseNotFound;
	$Coach = Null;
	echo "<style>Div{display:none}</style>";
	$Title = $DatabaseNotFound;
	$LeagueSimulationMenu = Null;
}else{
	$db = new SQLite3($DatabaseFile);
	
	$Query = "Select FarmEnable from LeagueSimulation";
	$LeagueSimulationMenu = $db->querySingle($Query,true);
	
	$Query = "Select Name, OutputName from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
}
echo "<title>" . $LeagueName . " - " . $CoachesLang['CoachesTitle'] . "</title>";
?>
<style>
#tablesorter_colSelectPro:checked + label {background: #5797d7;  border-color: #555;}
#tablesorter_colSelectPro:checked ~ #tablesorter_ColumnSelectorPro {display: block;}
#tablesorter_colSelectFarm:checked + label {background: #5797d7;  border-color: #555;}
#tablesorter_colSelectFarm:checked ~ #tablesorter_ColumnSelectorFarm {display: block;}
#tablesorter_colSelectAvailable:checked + label {background: #5797d7;  border-color: #555;}
#tablesorter_colSelectAvailable:checked ~ #tablesorter_ColumnSelectorAvailable {display: block;}
<?php If ($LeagueSimulationMenu['FarmEnable'] == "False"){echo "#FarmTable{display:none;}\n#FarmH1{display:none;}";}?>
</style>
</head><body>
<?php include "Menu.php";?>
<script>
$(function() {
  $(".STHSPHPProCoaches_Table").tablesorter({
    widgets: ['columnSelector', 'stickyHeaders', 'filter'],
    widgetOptions : {
      columnSelector_container : $('#tablesorter_ColumnSelectorPro'),
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
  $(".STHSPHPFarmCoaches_Table").tablesorter({
    widgets: ['columnSelector', 'stickyHeaders', 'filter'],
    widgetOptions : {
      columnSelector_container : $('#tablesorter_ColumnSelectorFarm'),
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
  $(".STHSPHPAvailableCoaches_Table").tablesorter({
    widgets: ['columnSelector', 'stickyHeaders', 'filter'],
    widgetOptions : {
      columnSelector_container : $('#tablesorter_ColumnSelectorAvailable'),
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

<div style="width:95%;margin:auto;">
<br />
<div class="tablesorter_ColumnSelectorWrapper">
	<input id="tablesorter_colSelectPro" type="checkbox" class="hidden">
    <label class="tablesorter_ColumnSelectorButton" for="tablesorter_colSelectPro"><?php echo $TableSorterLang['ShoworHideColumn'];?></label>
    <div id="tablesorter_ColumnSelectorPro" class="tablesorter_ColumnSelector"></div>
	<?php include "FilterTip.php";?>
	</div>

<h1><?php echo $CoachesLang['ProCoaches'];?></h1>
<table class="STHSPHPProCoaches_Table tablesorter"><thead><tr>
<th data-priority="critical" title="Coaches Name" class="STHSW200"><?php echo $CoachesLang['CoachesName'];?></th>
<th data-priority="1" title="Team Name" class="STHSW200"><?php echo $CoachesLang['TeamName'];?></th>
<th data-priority="2" title="Physical Style" class="STHSW25">PH</th>
<th data-priority="2" title="Defense Style" class="STHSW25">DF</th>
<th data-priority="2" title="Offense Style" class="STHSW25">OF</th>
<th data-priority="2" title="Player Discipline" class="STHSW25">PD</th>
<th data-priority="2" title="Experience" class="STHSW25">EX</th>
<th data-priority="2" title="Leadership" class="STHSW25">LD</th>
<th data-priority="3" title="Potential" class="STHSW25">PO</th>
<th data-priority="6" title="Country" class="STHSW35">CNT</th>
<th data-priority="5" title="Age" class="STHSW35"><?php echo $CoachesLang['Age'];?></th>
<th data-priority="4" title="Contract" class="STHSW25"><?php echo $CoachesLang['Contract'];?></th>
<th data-priority="4" title="Salary" class="STHSW100"><?php echo $CoachesLang['Salary'];?></th>
</tr></thead>
<tbody>
<?php
$Query = "SELECT CoachInfo.*, TeamProInfo.Name as TeamProName, TeamFarmInfo.Name As TeamFarmName, TeamProInfo.CoachID as ProCoachTeamID, TeamFarmInfo.CoachID as FarmCoachTeamID FROM (CoachInfo LEFT JOIN TeamFarmInfo ON CoachInfo.Team = TeamFarmInfo.Number) LEFT JOIN TeamProInfo ON CoachInfo.Team = TeamProInfo.Number WHERE TEAM <> 0 ORDER BY CoachInfo.Name";
$Coach = $db->query($Query);
if (empty($Coach) == false){while ($Row = $Coach ->fetchArray()) {
	If ($Row['Number'] == $Row['ProCoachTeamID']){
		echo "<tr><td>" . $Row['Name'] . "</td>";
		echo "<td>" . $Row['TeamProName'] . "</td>";
		echo "<td>" . $Row['PH'] . "</td>";
		echo "<td>" . $Row['DF'] . "</td>";
		echo "<td>" . $Row['OF'] . "</td>";
		echo "<td>" . $Row['PD'] . "</td>";
		echo "<td>" . $Row['EX'] . "</td>";
		echo "<td>" . $Row['LD'] . "</td>";
		echo "<td>" . $Row['PO'] . "</td>";
		echo "<td>" . $Row['Country'] . "</td>";
		echo "<td>" . $Row['Age'] . "</td>";
		echo "<td>" . $Row['Contract'] . "</td>";
		echo "<td>" . number_format($Row['Salary'],0) . "$</td>";
		echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
	}
}}
?>
</tbody></table>
<br />

<div class="tablesorter_ColumnSelectorWrapper">
	<input id="tablesorter_colSelectFarm" type="checkbox" class="hidden">
    <label class="tablesorter_ColumnSelectorButton" for="tablesorter_colSelectFarm"><?php echo $TableSorterLang['ShoworHideColumn'];?></label>
    <div id="tablesorter_ColumnSelectorFarm" class="tablesorter_ColumnSelector"></div>
	<?php include "FilterTip.php";?>
</div>

<h1 id="FarmH1"><?php echo $CoachesLang['FarmCoaches'];?></h1>
<table id="FarmTable" class="STHSPHPFarmCoaches_Table tablesorter"><thead><tr>
<th data-priority="critical" title="Coaches Name" class="STHSW200"><?php echo $CoachesLang['CoachesName'];?></th>
<th data-priority="1" title="Team Name" class="STHSW200"><?php echo $CoachesLang['TeamName'];?></th>
<th data-priority="2" title="Physical Style" class="STHSW25">PH</th>
<th data-priority="2" title="Defense Style" class="STHSW25">DF</th>
<th data-priority="2" title="Offense Style" class="STHSW25">OF</th>
<th data-priority="2" title="Player Discipline" class="STHSW25">PD</th>
<th data-priority="2" title="Experience" class="STHSW25">EX</th>
<th data-priority="2" title="Leadership" class="STHSW25">LD</th>
<th data-priority="3" title="Potential" class="STHSW25">PO</th>
<th data-priority="6" title="Country" class="STHSW35">CNT</th>
<th data-priority="5" title="Age" class="STHSW35"><?php echo $CoachesLang['Age'];?></th>
<th data-priority="4" title="Contract" class="STHSW25"><?php echo $CoachesLang['Contract'];?></th>
<th data-priority="4" title="Salary" class="STHSW100"><?php echo $CoachesLang['Salary'];?></th>
</tr></thead>
<tbody>
<?php
$Query = "SELECT CoachInfo.*, TeamProInfo.Name as TeamProName, TeamFarmInfo.Name As TeamFarmName, TeamProInfo.CoachID as ProCoachTeamID, TeamFarmInfo.CoachID as FarmCoachTeamID FROM (CoachInfo LEFT JOIN TeamFarmInfo ON CoachInfo.Team = TeamFarmInfo.Number) LEFT JOIN TeamProInfo ON CoachInfo.Team = TeamProInfo.Number WHERE TEAM <> 0 ORDER BY CoachInfo.Name";
$Coach = $db->query($Query);
if (empty($Coach) == false){while ($Row = $Coach ->fetchArray()) {
	If ($Row['Number'] == $Row['FarmCoachTeamID']){
		echo "<tr><td>" . $Row['Name'] . "</td>";
		echo "<td>" . $Row['TeamFarmName'] . "</td>";
		echo "<td>" . $Row['PH'] . "</td>";
		echo "<td>" . $Row['DF'] . "</td>";
		echo "<td>" . $Row['OF'] . "</td>";
		echo "<td>" . $Row['PD'] . "</td>";
		echo "<td>" . $Row['EX'] . "</td>";
		echo "<td>" . $Row['LD'] . "</td>";
		echo "<td>" . $Row['PO'] . "</td>";
		echo "<td>" . $Row['Country'] . "</td>";
		echo "<td>" . $Row['Age'] . "</td>";
		echo "<td>" . $Row['Contract'] . "</td>";
		echo "<td>" . number_format($Row['Salary'],0) . "$</td>";
		echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
	}
}}
?>
</tbody></table>
<br />

<div class="tablesorter_ColumnSelectorWrapper">
	<input id="tablesorter_colSelectAvailable" type="checkbox" class="hidden">
    <label class="tablesorter_ColumnSelectorButton" for="tablesorter_colSelectAvailable"><?php echo $TableSorterLang['ShoworHideColumn'];?></label>
    <div id="tablesorter_ColumnSelectorAvailable" class="tablesorter_ColumnSelector"></div>
	<?php include "FilterTip.php";?>
</div>

<h1><?php echo $CoachesLang['AvailableCoaches'];?></h1>
<table class="STHSPHPAvailableCoaches_Table tablesorter"><thead><tr>
<th data-priority="critical" title="Coaches Name" class="STHSW200"><?php echo $CoachesLang['CoachesName'];?></th>
<th data-priority="2" title="Physical Style" class="STHSW25">PH</th>
<th data-priority="2" title="Defense Style" class="STHSW25">DF</th>
<th data-priority="2" title="Offense Style" class="STHSW25">OF</th>
<th data-priority="2" title="Player Discipline" class="STHSW25">PD</th>
<th data-priority="2" title="Experience" class="STHSW25">EX</th>
<th data-priority="2" title="Leadership" class="STHSW25">LD</th>
<th data-priority="3" title="Potential" class="STHSW25">PO</th>
<th data-priority="6" title="Country" class="STHSW35">CNT</th>
<th data-priority="5" title="Age" class="STHSW35"><?php echo $CoachesLang['Age'];?></th>
<th data-priority="4" title="Contract" class="STHSW25"><?php echo $CoachesLang['Contract'];?></th>
<th data-priority="4" title="Salary" class="STHSW100"><?php echo $CoachesLang['Salary'];?></th>
</tr></thead>
<tbody>
<?php
$Query = "SELECT CoachInfo.*, TeamProInfo.Name as TeamProName, TeamFarmInfo.Name As TeamFarmName, TeamProInfo.CoachID as ProCoachTeamID, TeamFarmInfo.CoachID as FarmCoachTeamID FROM (CoachInfo LEFT JOIN TeamFarmInfo ON CoachInfo.Team = TeamFarmInfo.Number) LEFT JOIN TeamProInfo ON CoachInfo.Team = TeamProInfo.Number WHERE TEAM = 0 ORDER BY CoachInfo.Name";
$Coach = $db->query($Query);
if (empty($Coach) == false){while ($Row = $Coach ->fetchArray()) {
	echo "<tr><td>" . $Row['Name'] . "</td>";
	echo "<td>" . $Row['PH'] . "</td>";
	echo "<td>" . $Row['DF'] . "</td>";
	echo "<td>" . $Row['OF'] . "</td>";
	echo "<td>" . $Row['PD'] . "</td>";
	echo "<td>" . $Row['EX'] . "</td>";
	echo "<td>" . $Row['LD'] . "</td>";
	echo "<td>" . $Row['PO'] . "</td>";
	echo "<td>" . $Row['Country'] . "</td>";
	echo "<td>" . $Row['Age'] . "</td>";
	echo "<td>" . $Row['Contract'] . "</td>";
	echo "<td>" . number_format($Row['Salary'],0) . "$</td>";
	echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
}}
?>
</tbody></table>

<br />
</div>

<?php include "Footer.php";?>
