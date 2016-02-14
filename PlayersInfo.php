<?php include "Header.php";?>
<?php
$Team = (integer)-1; /* -1 All Team */
If (file_exists($DatabaseFile) == false){
	$LeagueName = "Database File Not Found";
	$PlayerInfo = Null;
	$LeagueOutputOption = Null;
	echo "<title>Database File Not Found</title>";
	$Title = "Database File Not Found";
}else{
	$DESCQuery = (boolean)FALSE;/* The SQL Query must be Descending Order and not Ascending*/
	$MaximumResult = (integer)0;
	$OrderByField = (string)"Name";
	$OrderByFieldText = (string)"Name";
	$OrderByInput = (string)"";
	$FreeAgentYear = (integer)-1; /* -1 = No Input */
	$Type = (integer)0; /* 0 = All / 1 = Pro / 2 = Farm */
	$TypeQuery = "Number > 0";
	$TeamQuery = "Team >= 0";
	$Title = (string)"";
	$TitleOverwrite = (string)"";
	$LeagueName = (string)"";
	if(isset($_GET['Type'])){$Type = filter_var($_GET['Type'], FILTER_SANITIZE_NUMBER_INT);} 
	if(isset($_GET['DESC'])){$DESCQuery = TRUE;}
	if(isset($_GET['Max'])){$MaximumResult = filter_var($_GET['Max'], FILTER_SANITIZE_NUMBER_INT);} 
	if(isset($_GET['Order'])){$OrderByInput  = filter_var($_GET['Order'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH);} 
	if(isset($_GET['Team'])){$Team = filter_var($_GET['Team'], FILTER_SANITIZE_NUMBER_INT);} 
	if(isset($_GET['Title'])){$TitleOverwrite = filter_var($_GET['Title'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH);} 
	if(isset($_GET['FreeAgent'])){$FreeAgentYear = filter_var($_GET['FreeAgent'], FILTER_SANITIZE_NUMBER_INT);} 

	$PlayersInformationPossibleOrderField = array(
	array("Name","Player Name"),
	array("Team","Team Number"),
	array("Age","Age"),
	array("Rookie","Rookie"),
	array("Weight","Weight"),
	array("Height","Height"),
	array("NoTrade","No Trade"),
	array("ForceWaiver","Force Waiver"),
	array("Contract","Contract Duration"),
	array("Salary1","Current Salary"),
	array("SalaryAverage","Salary Average"),
	array("Salary2","Salary Year 2"),
	array("Salary3","Salary Year 3"),
	array("Salary4","Salary Year 4"),
	array("Salary5","Salary Year 5"),
	array("Salary6","Salary Year 6"),
	array("Salary7","Salary Year 7"),
	array("Salary8","Salary Year 8"),
	array("Salary9","Salary Year 9"),
	array("Salary10","Salary Year 10"),
	);
	foreach ($PlayersInformationPossibleOrderField as $Value) {
		If (strtoupper($Value[0]) == strtoupper($OrderByInput)){
			$OrderByField = $Value[0];
			$OrderByFieldText = $Value[1];
			Break;
		}
	}
	
	$db = new SQLite3($DatabaseFile);
	$Query = "Select OutputSalariesRemaining, OutputSalariesAverageTotal, OutputSalariesAverageRemaining, InchInsteadofCM, LBSInsteadofKG from LeagueOutputOption";
	$LeagueOutputOption = $db->querySingle($Query,true);	
	$Query = "Select RemoveSalaryCapWhenPlayerUnderCondition, SalaryCapOption from LeagueFinance";
	$LeagueFinance = $db->querySingle($Query,true);	
	$Query = "Select Name, ProScheduleTotalDay, FarmScheduleTotalDay, ScheduleNextDay from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	
	/* Team or All */
	If ($Team >= 0){
		if($Team > 0){
			$QueryTeam = "SELECT Name FROM TeamProInfo WHERE Number = " . $Team;
			$TeamName = $db->querySingle($QueryTeam,true);	
			$Title = $TeamName['Name'];
		}else{
			$Title = "Unassigned";
		}
		$TeamQuery = "Team = " . $Team;
	}else{
		$TeamQuery = "Team >= 0"; /* Default Place Order Where everything will return */
	}
	
	If($MaximumResult == 0){$Title = $Title . " All";}else{$Title = $Title . " Top " .$MaximumResult;}
	
	/* Pro Only or Farm  */
	if($Type == 1){
		$TypeQuery = "Status1 >= 2";
		$ScheduleTotalDay = $LeagueGeneral['ProScheduleTotalDay'];
		$Title = $Title . " Pro";
	}elseif($Type == 2){
		$TypeQuery = "Status1 <= 1";
		$ScheduleTotalDay = $LeagueGeneral['FarmScheduleTotalDay'];
		$Title = $Title . " Farm";
	}else{
		$TypeQuery = "Number > 0"; /* Default Place Order Where everything will return */
		$ScheduleTotalDay = $LeagueGeneral['ProScheduleTotalDay'];
	} 
		
	/* Main Query with correct Variable */
	$Query = "SELECT MainTable.*, PlayerInfo.PosC, PlayerInfo.PosLW, PlayerInfo.PosRW, PlayerInfo.PosD, GoalerInfo.PosG FROM ((SELECT PlayerInfo.Number, PlayerInfo.Name, PlayerInfo.Team, PlayerInfo.TeamName, PlayerInfo.Age, PlayerInfo.AgeDate, PlayerInfo.Weight, PlayerInfo.Height, PlayerInfo.Contract, PlayerInfo.Rookie, PlayerInfo.NoTrade, PlayerInfo.CanPlayPro, PlayerInfo.CanPlayFarm, PlayerInfo.ForceWaiver, PlayerInfo.ExcludeSalaryCap, PlayerInfo.ProSalaryinFarm, PlayerInfo.SalaryAverage, PlayerInfo.Salary1, PlayerInfo.Salary2, PlayerInfo.Salary3, PlayerInfo.Salary4, PlayerInfo.Salary5, PlayerInfo.Salary6, PlayerInfo.Salary7, PlayerInfo.Salary8, PlayerInfo.Salary9, PlayerInfo.Salary10, PlayerInfo.Condition, PlayerInfo.ConditionDecimal,PlayerInfo.Status1, PlayerInfo.URLLink FROM PlayerInfo WHERE " . $TeamQuery . " AND " . $TypeQuery . " UNION ALL SELECT GoalerInfo.Number, GoalerInfo.Name, GoalerInfo.Team, GoalerInfo.TeamName, GoalerInfo.Age, GoalerInfo.AgeDate,GoalerInfo.Weight, GoalerInfo.Height, GoalerInfo.Contract, GoalerInfo.Rookie, GoalerInfo.NoTrade, GoalerInfo.CanPlayPro, GoalerInfo.CanPlayFarm, GoalerInfo.ForceWaiver, GoalerInfo.ExcludeSalaryCap, GoalerInfo.ProSalaryinFarm, GoalerInfo.SalaryAverage, GoalerInfo.Salary1, GoalerInfo.Salary2, GoalerInfo.Salary3, GoalerInfo.Salary4, GoalerInfo.Salary5, GoalerInfo.Salary6, GoalerInfo.Salary7, GoalerInfo.Salary8, GoalerInfo.Salary9, GoalerInfo.Salary10, GoalerInfo.Condition, GoalerInfo.ConditionDecimal, GoalerInfo.Status1, GoalerInfo.URLLink FROM GoalerInfo WHERE " . $TeamQuery . " AND " . $TypeQuery . ")  AS MainTable LEFT JOIN PlayerInfo ON MainTable.Name = PlayerInfo.Name) LEFT JOIN GoalerInfo ON MainTable.Name = GoalerInfo.Name"; 

	/* Free Agents */
	If ($FreeAgentYear >= 0){
		$Query = $Query . " WHERE MainTable.Contract = " . $FreeAgentYear; /* Free Agent Query */ 
		If ($FreeAgentYear == 0){$Title = $Title . " This Year Free Agents";}elseIf ($FreeAgentYear == 1){$Title = $Title . " Next Year Free Agents";}else{	$Title = $Title . " " . $FreeAgentYear . " Years Free Agents";		}
	}
	
	$Query = $Query . " ORDER BY MainTable." . $OrderByField;
	
	$Title = $Title . " Players Information";	
	
	/* Order by and Limit */
	If ($DESCQuery == TRUE){
		$Query = $Query . " DESC";
		$Title = $Title . " In Decending Order By " . $OrderByFieldText;
	}else{
		$Query = $Query . " ASC";
		$Title = $Title . " In Ascending Order By " . $OrderByFieldText;
	}	
	If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}

	/* Ran Query */	
	$PlayerInfo = $db->query($Query);
	
	/* OverWrite Title if information is get from PHP GET */
	if($TitleOverwrite <> ""){$Title = $TitleOverwrite;}
	echo "<title>" . $LeagueName . " - " . $Title . "</title>";
}?>
</head><body>
<!-- TOP MENU PLACE HOLDER -->
<?php echo "<h1>" . $Title . "</h1>"; ?>
<script type="text/javascript">
$(function() {
  $(".custom-popup").tablesorter({
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
      filter_placeholder: { search : 'Search' },
	  filter_searchDelay : 1000,	  
      filter_reset: '.tablesorter_Reset'	
    }
  });
});
</script>

<div style="width:99%;margin:auto;">

<div class="tablesorter_ColumnSelectorWrapper">
    <input id="tablesorter_colSelect1" type="checkbox" class="hidden">
    <label class="tablesorter_ColumnSelectorButton" for="tablesorter_colSelect1">Show or Hide Column</label>
    <div id="tablesorter_ColumnSelector" class="tablesorter_ColumnSelector"></div>
    <button class="tablesorter_Reset" type="button">Reset Search Filter</button>
	<div class="tablesorter_Reset FilterTipMain">Filter Tips
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

<table class="tablesorter custom-popup STHSPHPAllPlayerInformation_Table"><thead><tr>
<th data-priority="critical" title="Player Name" class="STHSW140Min">Player Name</th>
<?php if($Team >= 0){echo "<th class=\"columnSelector-false STHSW140Min\" data-priority=\"6\" title=\"Team Name\">Team Name</th>";}else{echo "<th data-priority=\"2\" title=\"Team Name\" class=\"STHSW140Min\">Team Name</th>";}?>
<th data-priority="2" title="Position" class="STHSW45">POS</th>
<th data-priority="1" title="Age" class="STHSW25">Age</th>
<th data-priority="4" title="Birthday" class="STHSW45">Birthday</th>
<th data-priority="3" title="Rookie" class="STHSW35">Rookie</th>
<th data-priority="2" title="Weight" class="STHSW45">Weight</th>
<th data-priority="2" title="Height" class="STHSW45">Height</th>
<th data-priority="3" title="No Trade" class="STHSW35">No Trade</th>
<th data-priority="3" title="Force Waiver" class="STHSW45">Force<br /> Waiver</th>
<th data-priority="1" title="Contract Duration" class="STHSW45">Contract</th>
<th class="columnSelector-false STHSW85" data-priority="5" title="Type">Type</th>
<th data-priority="1" title="Current Salary" class="STHSW85">Current<br />Salary</th>
<?php 
	$Remaining = (float)0;
	if($LeagueOutputOption['OutputSalariesRemaining'] == "True"){Echo "<th data-priority=\"4\" title=\"Salary Remaining\" class=\"STHSW85\">Salary<br />Remaining</th>";}
	if($LeagueOutputOption['OutputSalariesAverageTotal'] == "True"){Echo "<th data-priority=\"4\" title=\"Salary Average\" class=\"STHSW85\">Salary<br />Average</th>";}
	if($LeagueOutputOption['OutputSalariesAverageRemaining'] == "True"){echo "<th data-priority=\"4\" title=\"Salary Average Remaining\" class=\"STHSW85\">Salary Ave<br />Remaining</th>";}
	if($LeagueOutputOption['OutputSalariesRemaining'] == "True" OR $LeagueOutputOption['OutputSalariesAverageRemaining'] == "True"){If ($ScheduleTotalDay > 0){$Remaining = ($LeagueGeneral['ProScheduleTotalDay'] - $LeagueGeneral['ScheduleNextDay'] + 1) / $LeagueGeneral['ProScheduleTotalDay'];}}
?>
<th data-priority="5" title="Salary Year 2" class="STHSW85">Salary<br />Year 2</th>
<th data-priority="5" title="Salary Year 3" class="STHSW85">Salary<br />Year 3</th>
<th data-priority="5" title="Salary Year 4" class="STHSW85">Salary<br />Year 4</th>
<th class="columnSelector-false STHSW85" data-priority="6" title="Salary Year 5">Salary<br />Year 5</th>
<th class="columnSelector-false STHSW85" data-priority="6" title="Salary Year 6">Salary<br />Year 6</th>
<th class="columnSelector-false STHSW85" data-priority="6" title="Salary Year 7">Salary<br />Year 7</th>
<th class="columnSelector-false STHSW85" data-priority="6" title="Salary Year 8">Salary<br />Year 8</th>
<th class="columnSelector-false STHSW85" data-priority="6" title="Salary Year 9">Salary<br />Year 9</th>
<th class="columnSelector-false STHSW85" data-priority="6" title="Salary Year 10">Salary<br />Year 10</th>
<th data-priority="5" title="Hyperlink" class="STHSW35">Link</th>
</tr></thead><tbody>

<?php 
if (empty($PlayerInfo) == false){while ($Row = $PlayerInfo ->fetchArray()) { 
	echo "<tr><td>";
	if ($Row['PosG']== "True"){echo "<a href=\"GoalieReport.php?Goalie=";}else{echo "<a href=\"PlayerReport.php?Player=";}
	Echo $Row['Number'] . "\">" . $Row['Name'] . "</a>";
	If ($Row['ConditionDecimal'] > $LeagueFinance['RemoveSalaryCapWhenPlayerUnderCondition'] AND $Row['ExcludeSalaryCap'] == "False"){
	If($Row['ProSalaryinFarm'] == "True"){echo " (1 Way Contract)</td>";}else{echo "</td>";}}else{echo " (Out of Payroll)</td>";}
	echo "<td>" . $Row['TeamName'] . "</td>";
	echo "<td>" .$Position = (string)"";
	if ($Row['PosC']== "True"){if ($Position == ""){$Position = "C";}else{$Position = $Position . "/C";}}
	if ($Row['PosLW']== "True"){if ($Position == ""){$Position = "LW";}else{$Position = $Position . "/LW";}}
	if ($Row['PosRW']== "True"){if ($Position == ""){$Position = "RW";}else{$Position = $Position . "/RW";}}
	if ($Row['PosD']== "True"){if ($Position == ""){$Position = "D";}else{$Position = $Position . "/D";}}
	if ($Row['PosG']== "True"){if ($Position == ""){$Position = "G";}}
	echo $Position . "</td>";	
	echo "<td>" . $Row['Age'] . "</td>";
	echo "<td>" . $Row['AgeDate'] . "</td>";
	echo "<td>"; if ($Row['Rookie'] == "True"){ echo "Yes"; }else{echo "No";};echo "</td>";	
	If ($LeagueOutputOption['LBSInsteadofKG'] == "True"){echo "<td>" . $Row['Weight'] . " Lbs</td>";}else{echo "<td>" . Round($Row['Weight'] / 2.2) . " Kg</td>";}
	If ($LeagueOutputOption['InchInsteadofCM'] == "True"){echo "<td>" . (($Row['Height'] - ($Row['Height'] % 12))/12) . " ft" .  ($Row['Height'] % 12) .  "</td>";}else{echo "<td>" . Round($Row['Height'] * 2.54) . " CM</td>";}	
	echo "<td>"; if ($Row['NoTrade']== "True"){ echo "Yes"; }else{echo "No";};echo "</td>";
	echo "<td>"; if ($Row['ForceWaiver']== "True"){ echo "Yes"; }else{echo "No";};echo "</td>";
	echo "<td>" . $Row['Contract'] . "</td>";
	echo "<td>"; if ($Row['CanPlayPro']== "True" AND $Row['CanPlayFarm']== "True"){echo "Pro &amp; Farm";}elseif($Row['CanPlayPro']== "True" AND $Row['CanPlayFarm']== "False"){echo "Pro Only";}else{echo "Farm Only";	};echo "</td>";
	echo "<td>"; if ($Row['Salary1'] > 0){echo number_format($Row['Salary1'],0) . "$";};echo "</td>";	
	if($LeagueOutputOption['OutputSalariesRemaining'] == "True"){echo "<td>"; if ($Row['Salary1'] > 0){echo number_format($Row['Salary1'] * $Remaining,0) . "$";};echo "</td>";}
	if($LeagueOutputOption['OutputSalariesAverageTotal'] == "True"){echo "<td>"; if ($Row['SalaryAverage'] > 0){echo number_format($Row['SalaryAverage'],0) . "$";};echo "</td>";}
	if($LeagueOutputOption['OutputSalariesAverageRemaining'] == "True"){echo "<td>"; if ($Row['SalaryAverage'] > 0){echo number_format($Row['SalaryAverage'] * $Remaining,0) . "$";};echo "</td>";}
	echo "<td>"; if ($Row['Salary2'] > 0){echo number_format($Row['Salary2'],0) . "$";};echo "</td>";	
	echo "<td>"; if ($Row['Salary3'] > 0){echo number_format($Row['Salary3'],0) . "$";};echo "</td>";	
	echo "<td>"; if ($Row['Salary4'] > 0){echo number_format($Row['Salary4'],0) . "$";};echo "</td>";	
	echo "<td>"; if ($Row['Salary5'] > 0){echo number_format($Row['Salary5'],0) . "$";};echo "</td>";	
	echo "<td>"; if ($Row['Salary6'] > 0){echo number_format($Row['Salary6'],0) . "$";};echo "</td>";	
	echo "<td>"; if ($Row['Salary7'] > 0){echo number_format($Row['Salary7'],0) . "$";};echo "</td>";	
	echo "<td>"; if ($Row['Salary8'] > 0){echo number_format($Row['Salary8'],0) . "$";};echo "</td>";	
	echo "<td>"; if ($Row['Salary9'] > 0){echo number_format($Row['Salary9'],0) . "$";};echo "</td>";	
	echo "<td>"; if ($Row['Salary10'] > 0){echo number_format($Row['Salary10'],0) . "$";};echo "</td>";		
	If ($Row['URLLink'] == ""){echo "<td></td>";}else{echo "<td><a href=\"" . $Row['URLLink'] . "\" target=\"new\">Link</td>";}
	echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
}}
?>
</tbody></table>
<br />
</div>

<?php include "Footer.php";?>
