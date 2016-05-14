<!DOCTYPE html>
<?php include "Header.php";?>
<?php
$LeagueName = (string)"";

If (file_exists($DatabaseFile) == false){
	$LeagueName = $DatabaseNotFound;
	$Coach = Null;
}else{
	$db = new SQLite3($DatabaseFile);
	$Query = "SELECT CoachInfo.*, TeamProInfo.Name as TeamProName, TeamFarmInfo.Name As TeamFarmName, TeamProInfo.CoachID as ProCoachTeamID, TeamFarmInfo.CoachID as FarmCoachTeamID FROM (CoachInfo LEFT JOIN TeamFarmInfo ON CoachInfo.Team = TeamFarmInfo.Number) LEFT JOIN TeamProInfo ON CoachInfo.Team = TeamProInfo.Number ORDER BY CoachInfo.Name";
	$Coach = $db->query($Query);
	$Query = "Select Name, OutputName from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
}
echo "<title>" . $LeagueName . " - " . $CoachesLang['CoachesTitle'] . "</title>";
?>
</head><body>
<?php include "Menu.php";?>
<br />

<script type="text/javascript">
$(function() {
  $(".STHSPHPCoaches_Table").tablesorter({
    widgets: ['stickyHeaders', 'filter'],
    widgetOptions : {
	  filter_columnFilters: true,
      filter_placeholder: { search : '<?php echo $TableSorterLang['Search'];?>' },
	  filter_searchDelay : 500,	  
      filter_reset: '.tablesorter_Reset'	 
    }
  });
});
</script>

<div style="width:95%;margin:auto;">
<h1><?php echo $CoachesLang['ProCoaches'];?></h1>
<table class="STHSPHPCoaches_Table tablesorter"><thead><tr>
<th title="Coaches Name" class="STHSW200"><?php echo $CoachesLang['CoachesName'];?></th>
<th title="Team Name" class="STHSW200"><?php echo $CoachesLang['TeamName'];?></th>
<th title="Physical Style" class="STHSW25">PH</th>
<th title="Defense Style" class="STHSW25">DF</th>
<th title="Offense Style" class="STHSW25">OF</th>
<th title="Player Discipline" class="STHSW25">PD</th>
<th title="Experience" class="STHSW25">EX</th>
<th title="Leadership" class="STHSW25">LD</th>
<th title="Potential" class="STHSW25">PO</th>
<th title="Country" class="STHSW35">CNT</th>
<th title="Age" class="STHSW35"><?php echo $CoachesLang['Age'];?></th>
<th title="Contract" class="STHSW25"><?php echo $CoachesLang['Contract'];?></th>
<th title="Salary" class="STHSW100"><?php echo $CoachesLang['Salary'];?></th>
</tr></thead>
<tbody>
<?php
if (empty($Coach) == false){while ($Row = $Coach ->fetchArray()) {
	If ($Row['Team'] <> 0){
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
	}
}}
?>
</tbody></table>
<br />

<h1><?php echo $CoachesLang['FarmCoaches'];?></h1>
<table class="STHSPHPCoaches_Table tablesorter"><thead><tr>
<th title="Coaches Name" class="STHSW200"><?php echo $CoachesLang['CoachesName'];?></th>
<th title="Team Name" class="STHSW200"><?php echo $CoachesLang['TeamName'];?></th>
<th title="Physical Style" class="STHSW25">PH</th>
<th title="Defense Style" class="STHSW25">DF</th>
<th title="Offense Style" class="STHSW25">OF</th>
<th title="Player Discipline" class="STHSW25">PD</th>
<th title="Experience" class="STHSW25">EX</th>
<th title="Leadership" class="STHSW25">LD</th>
<th title="Potential" class="STHSW25">PO</th>
<th title="Country" class="STHSW35">CNT</th>
<th title="Age" class="STHSW35">Age</th>
<th title="Contract" class="STHSW25"><?php echo $CoachesLang['Contract'];?></th>
<th title="Salary" class="STHSW100"><?php echo $CoachesLang['Salary'];?></th>
</tr></thead>
<tbody>
<?php
if (empty($Coach) == false){while ($Row = $Coach ->fetchArray()) {
	If ($Row['Team'] <> 0){
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
	}
}}
?>
</tbody></table>
<br />


<h1><?php echo $CoachesLang['AvailableCoaches'];?></h1>
<table class="STHSPHPCoaches_Table tablesorter"><thead><tr>
<th title="Coaches Name" class="STHSW200"><?php echo $CoachesLang['CoachesName'];?></th>
<th title="Physical Style" class="STHSW25">PH</th>
<th title="Defense Style" class="STHSW25">DF</th>
<th title="Offense Style " class="STHSW25">OF</th>
<th title="Player Discipline" class="STHSW25">PD</th>
<th title="Experience" class="STHSW25">EX</th>
<th title="Leadership" class="STHSW25">LD</th>
<th title="Potential -" class="STHSW25">PO</th>
<th title="Country" class="STHSW35">CNT</th>
<th title="Age" class="STHSW35">Age</th>
<th title="Contract" class="STHSW25"><?php echo $CoachesLang['Contract'];?></th>
<th title="Salary" class="STHSW100"><?php echo $CoachesLang['Salary'];?></th>
</tr></thead>
<tbody>
<?php
if (empty($Coach) == false){while ($Row = $Coach ->fetchArray()) {
	If ($Row['Team'] == 0){
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
	}
}}
?>
</tbody></table>

<div class="tablesorter_ColumnSelectorWrapper">
    <div id="tablesorter_ColumnSelector" class="tablesorter_ColumnSelector"></div>
	<?php include "FilterTip.php";?>
</div>

<br />
</div>

<?php include "Footer.php";?>
