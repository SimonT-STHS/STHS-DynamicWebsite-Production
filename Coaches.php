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
    <button class="tablesorter_Reset" type="button"><?php echo $TableSorterLang['ResetAllSearchFilter'];?></button>
	<div class="tablesorter_Reset FilterTipMain"><?php echo $TableSorterLang['FilterTips'];?>
	<table class="FilterTip"><thead><tr><th style="width:55px">Priority</th><th style="width:100px">Type</th><th style="width:485px">Description</th></tr></thead>
		<tbody>
			<tr><td class="STHSCenter">1</td><td><code>|</code> or <code>&nbsp;OR&nbsp;</code></td><td>Logical &quot;or&quot; (Vertical bar). Filter the column for content that matches text from either side of the bar</td></tr>
			<tr><td class="STHSCenter">2</td><td><code>&nbsp;&&&nbsp;</code> or <code>&nbsp;AND&nbsp;</code></td><td>Logical &quot;and&quot;. Filter the column for content that matches text from either side of the operator.</td></tr>
			<tr><td class="STHSCenter">3</td><td><code>/\d/</code></td><td>Add any regex to the query to use in the query ("mig" flags can be included <code>/\w/mig</code>)</td></tr>
			<tr><td class="STHSCenter">4</td><td><code>&lt; &lt;= &gt;= &gt;</code></td><td>Find alphabetical or numerical values less than or greater than or equal to the filtered query</td></tr>
			<tr><td class="STHSCenter">5</td><td><code>!</code> or <code>!=</code></td><td>Not operator, or not exactly match. Filter the column with content that <strong>do not</strong> match the query. Include an equal (<code>=</code>), single (<code>'</code>) or double quote (<code>&quot;</code>) to exactly <em>not</em> match a filter.</td></tr>
			<tr><td class="STHSCenter">6</td><td><code>&quot;</code> or <code>=</code></td><td>To exactly match the search query, add a quote, apostrophe or equal sign to the beginning and/or end of the query</td></tr>
			<tr><td class="STHSCenter">7</td><td><code>&nbsp;-&nbsp;</code> or <code>&nbsp;to&nbsp;</code></td><td>Find a range of values. Make sure there is a space before and after the dash (or the word &quot;to&quot;)</td></tr>
			<tr><td class="STHSCenter">8</td><td><code>?</code></td><td>Wildcard for a single, non-space character.</td></tr>
			<tr><td class="STHSCenter">8</td><td><code>*</code></td><td>Wildcard for zero or more non-space characters.</td></tr>
			<tr><td class="STHSCenter">9</td><td><code>~</code></td><td>Perform a fuzzy search (matches sequential characters) by adding a tilde to the beginning of the query</td></tr>
			<tr><td class="STHSCenter">10</td><td>text</td><td>Any text entered in the filter will <strong>match</strong> text found within the column</td></tr>
		</tbody>
	</table>
	</div>
</div>

<br />
</div>

<?php include "Footer.php";?>
