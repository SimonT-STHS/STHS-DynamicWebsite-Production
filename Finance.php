<!DOCTYPE html>
<?php include "Header.php";?>
<?php
$LeagueName = (string)"";
$Active = 2; /* Show Webpage Top Menu */
$TypeText = "Pro";
If (file_exists($DatabaseFile) == false){
	$LeagueName = $DatabaseNotFound;
	$Finance = Null;
}else{
	$db = new SQLite3($DatabaseFile);
	
	$TypeText = (string)"Pro";$TitleType = $DynamicTitleLang['Pro'];
	if(isset($_GET['Farm'])){$TypeText = "Farm";$TitleType = $DynamicTitleLang['Farm'];$Active = 3;}
	
	If ($TypeText == "Farm"){
		$Query = "SELECT TeamFarmFinance.*, TeamFarmStat.HomeGP FROM TeamFarmFinance LEFT JOIN TeamFarmStat ON TeamFarmFinance.Number = TeamFarmStat.Number ORDER by TeamFarmFinance.Name";
	}else{
		$Query = "SELECT TeamProFinance.*, TempTable.EstimatedSeasonExpense AS FarmEstimatedSeasonExpense, TempTable.HomeGP AS HomeGP FROM TeamProFinance INNER JOIN (SELECT  TeamFarmFinance.Number, TeamFarmFinance.EstimatedSeasonExpense,TeamProStat.HomeGP FROM TeamProStat INNER JOIN TeamFarmFinance ON TeamProStat.Number = TeamFarmFinance.Number)  AS TempTable ON TeamProFinance.Number = TempTable.Number ORDER by TeamProFinance.Name";
	}
	$Finance = $db->query($Query);
		
	$Query = "Select SalaryCapOption, ProSalaryCapValue, ProMinimumSalaryCap, CurrentFundMinimumWarning from LeagueFinance";
	$LeagueFinance = $db->querySingle($Query,true);		
	
	$Query = "Select Name, OutputName, ProScheduleTotalDay, ScheduleNextDay from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	
	$Title = $TypeText . " " . $TeamLang['Finance'];
}
echo "<title>" . $LeagueName . " - " . $Title . "</title>";

?>
</head><body>
<?php include "Menu.php";?>

<script type="text/javascript">
$(function() {
  $.tablesorter.addWidget({ id: "numbering",format: function(table) {var c = table.config;$("tr:visible", table.tBodies[0]).each(function(i) {$(this).find('td').eq(0).text(i + 1);});}});	
  $(".STHSPHPFinance_Table").tablesorter({
    widgets: ['numbering','columnSelector', 'stickyHeaders', 'filter'],
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

<div style="width:95%;margin:auto;">
<?php echo "<h1>" . $Title . "</h1>"; ?>
<div class="tablesorter_ColumnSelectorWrapper">
    <input id="tablesorter_colSelect1" type="checkbox" class="hidden">
    <label class="tablesorter_ColumnSelectorButton" for="tablesorter_colSelect1"><?php echo $TableSorterLang['ShoworHideColumn'];?></label>
    <div id="tablesorter_ColumnSelector" class="tablesorter_ColumnSelector"></div>
	<?php include "FilterTip.php";?>
	</div>
</div>

<table class="STHSPHPFinance_Table tablesorter"><thead><tr>

<th class="sorter-false" colspan="2"></th>
<?php
$ColsPan = 2;
if ($TypeText == "Pro"){$ColsPan="5";}
?>
<th class="sorter-false" colspan="<?php echo $ColsPan . "\">" . $TeamLang['ArenaCapacity'];?></th>
<th class="sorter-false" colspan="<?php echo $ColsPan . "\">" . $TeamLang['TicketPrice'];?></th>
<th class="sorter-false" colspan="<?php echo $ColsPan . "\">" . $TeamLang['Attendance'];?></th>
<th class="sorter-false" colspan="<?php echo $ColsPan . "\">" . $TeamLang['AttendancePCT'];?></th>
<th class="sorter-false" colspan="6"><?php echo $TeamLang['Income'];?></th>
<th class="sorter-false" colspan="<?php if ($TypeText == "Pro"){echo "9";}else{echo "7";}?>"><?php echo $TeamLang['Expenses'];?></th>
<th class="sorter-false" colspan="4"><?php echo $TeamLang['Estimate'];?></th>
<?php if ($TypeText == "Pro"){
	echo "<th class=\"sorter-false\" colspan=\"";
	if ($LeagueFinance['SalaryCapOption'] > 0){echo "6";}else{echo "3";}
	echo "\">" . $TeamLang['TeamTotalEstime'] . "</th>";}
?>
</tr><tr>

<th data-priority="1" title="Order Number" class="STHSW10 sorter-false">#</th>
<th data-priority="critical" title="Team Name" class="STHSW200"><?php echo $TeamStatLang['TeamName'];?></th>
<?php
echo "<th data-priority=\"6\" title=\"Arena Capacity Level 1\" class=\"columnSelector-false STHSW25\">" . $TeamLang['Level'] . "1</th>";
echo "<th data-priority=\"6\" title=\"Arena Capacity Level 2\" class=\"columnSelector-false STHSW25\">" . $TeamLang['Level'] . "2</th>";
if ($TypeText == "Pro"){
	echo "<th data-priority=\"6\" title=\"Arena Capacity Level 3\" class=\"columnSelector-false STHSW25\">" . $TeamLang['Level'] . "3</th>";
	echo "<th data-priority=\"6\" title=\"Arena Capacity Level 4\" class=\"columnSelector-false STHSW25\">" . $TeamLang['Level'] . "4</th>";
	echo "<th data-priority=\"6\" title=\"Arena Capacity Luxury\" class=\"columnSelector-false STHSW25\">" . $TeamLang['Luxury'] . "</th>";
}
echo "<th data-priority=\"6\" title=\"Ticket Price Level 1\" class=\"columnSelector-false STHSW25\">" . $TeamLang['Level'] . "1</th>";
echo "<th data-priority=\"6\" title=\"Ticket Price Level 2\" class=\"columnSelector-false STHSW25\">" . $TeamLang['Level'] . "2</th>";
if ($TypeText == "Pro"){
	echo "<th data-priority=\"6\" title=\"Ticket Price Level 3\" class=\"columnSelector-false STHSW25\">" . $TeamLang['Level'] . "3</th>";
	echo "<th data-priority=\"6\" title=\"Ticket Price Level 4\" class=\"columnSelector-false STHSW25\">" . $TeamLang['Level'] . "4</th>";
	echo "<th data-priority=\"6\" title=\"Ticket Price Luxury\" class=\"columnSelector-false STHSW25\">" . $TeamLang['Luxury'] . "</th>";
}
echo "<th data-priority=\"6\" title=\"Attendance Level 1\" class=\"columnSelector-false STHSW25\">" . $TeamLang['Level'] . "1</th>";
echo "<th data-priority=\"6\" title=\"Attendance Level 2\" class=\"columnSelector-false STHSW25\">" . $TeamLang['Level'] . "2</th>";
if ($TypeText == "Pro"){
	echo "<th data-priority=\"6\" title=\"Attendance Level 3\" class=\"columnSelector-false STHSW25\">" . $TeamLang['Level'] . "3</th>";
	echo "<th data-priority=\"6\" title=\"Attendance Level 4\" class=\"columnSelector-false STHSW25\">" . $TeamLang['Level'] . "4</th>";
	echo "<th data-priority=\"6\" title=\"Attendance Luxury\" class=\"columnSelector-false STHSW25\">" . $TeamLang['Luxury'] . "</th>";
}
echo "<th data-priority=\"6\" title=\"Attendance PCT Level 1\" class=\"columnSelector-false STHSW25\">" . $TeamLang['Level'] . "1</th>";
echo "<th data-priority=\"6\" title=\"Attendance PCT Level 2\" class=\"columnSelector-false STHSW25\">" . $TeamLang['Level'] . "2</th>";
if ($TypeText == "Pro"){
	echo "<th data-priority=\"6\" title=\"Attendance PCT Level 3\" class=\"columnSelector-false STHSW25\">" . $TeamLang['Level'] . "3</th>";
	echo "<th data-priority=\"6\" title=\"Attendance PCT Level 4\" class=\"columnSelector-false STHSW25\">" . $TeamLang['Level'] . "4</th>";
	echo "<th data-priority=\"6\" title=\"Attendance PCT Luxury\" class=\"columnSelector-false STHSW25\">" . $TeamLang['Luxury'] . "</th>";
}
?>

<th data-priority="5" title="Home Games Left" class="columnSelector-false STHSW25"><?php echo $TeamLang['HomeGamesLeft'];?></th>
<th data-priority="6" title="Average Attendance PCT" class="columnSelector-false STHSW75"><?php echo $TeamLang['AverageAttendancePCT'];?></th>
<th data-priority="4" title="Average Income per Game" class="STHSW75"><?php echo $TeamLang['AverageIncomeperGame'];?></th>
<th data-priority="3" title="Year to Date Revenue" class="STHSW75"><?php echo $TeamLang['YeartoDateRevenue'];?></th>
<th data-priority="5" title="Arena Capacity" class="columnSelector-false STHSW75"><?php echo $TeamLang['ArenaCapacity'];?></th>
<th data-priority="6" title="Team Popularity" class="columnSelector-false STHSW35"><?php echo $TeamLang['TeamPopularity'];?></th>

<th data-priority="3" title="Players Total Salaries" class="STHSW75"><?php echo $TeamLang['PlayersTotalSalaries'];?></th>
<th data-priority="3" title="Players Total Average Salaries" class="STHSW75"><?php echo $TeamLang['PlayersTotalAverageSalaries'];?></th>
<?php if ($TypeText == "Pro"){echo "<th data-priority=\"5\" title=\"Special Salary Cap Value\" class=\"columnSelector-false STHSW75\">" . $TeamLang['SpecialSalaryCapValue']. "</th>";}?>
<th data-priority="2" title="Year To Date Expenses" class="STHSW75"><?php echo $TeamLang['YearToDateExpenses'];?></th>
<th data-priority="2" title="Salary Cap Per Days" class="<?php if ($LeagueFinance['SalaryCapOption'] == 0){echo "columnSelector-false ";}?>STHSW75"><?php echo $TeamLang['SalaryCapPerDays'];?></th>
<th data-priority="2" title="Salary Cap To Date" class="<?php if ($LeagueFinance['SalaryCapOption'] == 0){echo "columnSelector-false ";}?>STHSW75" style="min-width:55px;"><?php echo $TeamLang['SalaryCapToDate'];?></th>
<th data-priority="5" title="Players In Salary Cap" class="STHSW35"><?php echo $TeamLang['PlayerInSalaryCap'];?></th>
<th data-priority="5" title="Players Out of Salary Cap" class="STHSW35"><?php echo $TeamLang['PlayerOutofSalaryCap'];?></th>
<?php if ($TypeText == "Pro"){echo "<th data-priority=\"5\" title=\"Luxury Taxe Total\" class=\"columnSelector-false STHSW75\">" . $TeamLang['LuxuryTaxeTotal'] . "</th>";}?>
<th data-priority="4" title="Estimated Season Revenue" class="STHSW75"><?php echo $TeamLang['EstimatedSeasonRevenue'];?></th>
<th data-priority="5" title="Remaining Season Days" class="columnSelector-false STHSW35"><?php echo $TeamLang['RemainingSeasonDays'];?></th>
<th data-priority="2" title="Expenses Per Days" class="STHSW75"><?php echo $TeamLang['ExpensesPerDays'];?></th>
<th data-priority="2" title="Estimated Season Expenses" class="STHSW75"><?php echo $TeamLang['EstimatedSeasonExpenses'];?></th>

<?php if ($TypeText == "Pro"){
echo "<th data-priority=\"1\" title=\"Estimated Season Expenses\" class=\"STHSW75\">" . $TeamLang['EstimatedSeasonExpenses']. "</th>";
if ($LeagueFinance['SalaryCapOption'] > 0){
	echo "<th data-priority=\"1\" title=\"Estimated Season Salary Cap\" class=\"STHSW75\">" .  $TeamLang['EstimatedSeasonSalaryCap']. "</th>";
	echo "<th data-priority=\"4\" title=\"Maximum Salary Cap\" class=\"columnSelector-false STHSW75\">" .  $TeamLang['MaximumSalaryCap']. "</th>";
	echo "<th data-priority=\"2\" title=\"Available Salary Cap\" class=\"STHSW75\">" .  $TeamLang['AvailableSalaryCap']. "</th>";
}
echo "<th data-priority=\"1\" title=\"Current Bank Account\" class=\"STHSW75\" style=\"min-width:65px;\">" .  $TeamLang['CurrentBankAccount']. "</th>";
echo "<th data-priority=\"1\" title=\"Projected Bank Account\" class=\"STHSW75\" style=\"min-width:65px;\">" .  $TeamLang['ProjectedBankAccount']. "</th>";
}?>

</tr></thead>
<tbody>
<?php
$Order = 0;
$NoSort = (boolean)FALSE;
if (empty($Finance) == false){while ($Row = $Finance ->fetchArray()) {
	$Order +=1;
	If ($Row['Number'] <= 100){
		echo "<tr><td>" . $Order ."</td>";		
		echo "<td><a href=\"" . $TypeText . "Team.php?Team=" . $Row['Number'] . "\">" . $Row['Name'] . "</a></td>";
	}else{
		If ($NoSort == False){echo "</tbody><tbody class=\"tablesorter-no-sort\">";$NoSort=True;}
		echo "<tr><td></td>";
		echo "<td>" . $Row['Name'] . "</td>";
	}	
	echo "<td>" . $Row['ArenaCapacityL1'] . "</td>";
	echo "<td>" . $Row['ArenaCapacityL2'] . "</td>";
	if ($TypeText == "Pro"){
		echo "<td>" . $Row['ArenaCapacityL3'] . "</td>";
		echo "<td>" . $Row['ArenaCapacityL4'] . "</td>";
		echo "<td>" . $Row['ArenaCapacityLuxury'] . "</td>";
	}
	echo "<td>" . $Row['TicketPriceL1'] . "</td>";
	echo "<td>" . $Row['TicketPriceL2'] . "</td>";
	if ($TypeText == "Pro"){
		echo "<td>" . $Row['TicketPriceL3'] . "</td>";
		echo "<td>" . $Row['TicketPriceL4'] . "</td>";
		echo "<td>" . $Row['TicketPriceLuxury'] . "</td>";	
	}
	echo "<td>" . $Row['AttendanceL1'] . "</td>";
	echo "<td>" . $Row['AttendanceL2'] . "</td>";
	if ($TypeText == "Pro"){
		echo "<td>" . $Row['AttendanceL3'] . "</td>";
		echo "<td>" . $Row['AttendanceL4'] . "</td>";
		echo "<td>" . $Row['AttendanceLuxury'] . "</td>";
	}
	echo "<td>";if ($Row['ArenaCapacityL1'] > 0 AND $Row['HomeGP'] > 0){echo number_format(($Row['AttendanceL1'] / ($Row['ArenaCapacityL1'] * $Row['HomeGP'])) *100 ,2) . "%";} else { echo "0.00%";} echo "</td>";	
	echo "<td>";if ($Row['ArenaCapacityL2'] > 0 AND $Row['HomeGP'] > 0){echo number_format(($Row['AttendanceL2'] / ($Row['ArenaCapacityL2'] * $Row['HomeGP'])) *100 ,2) . "%";} else { echo "0.00%";} echo "</td>";	
	if ($TypeText == "Pro"){
		echo "<td>";if ($Row['ArenaCapacityL3'] > 0 AND $Row['HomeGP'] > 0){echo number_format(($Row['AttendanceL3'] / ($Row['ArenaCapacityL3'] * $Row['HomeGP'])) *100 ,2) . "%";} else { echo "0.00%";} echo "</td>";	
		echo "<td>";if ($Row['ArenaCapacityL4'] > 0 AND $Row['HomeGP'] > 0){echo number_format(($Row['AttendanceL4'] / ($Row['ArenaCapacityL4'] * $Row['HomeGP'])) *100 ,2) . "%";} else { echo "0.00%";} echo "</td>";		
		echo "<td>";if ($Row['ArenaCapacityLuxury'] > 0 AND $Row['HomeGP'] > 0){echo number_format(($Row['AttendanceLuxury'] / ($Row['ArenaCapacityLuxury'] * $Row['HomeGP'])) *100 ,2) . "%";} else { echo "0.00%";} echo "</td>";	
	}
	
	$TotalArenaCapacity = 0;
	if ($TypeText == "Pro"){
		$TotalArenaCapacity = ($Row['ArenaCapacityL1'] + $Row['ArenaCapacityL2'] + $Row['ArenaCapacityL3'] + $Row['ArenaCapacityL4'] + $Row['ArenaCapacityLuxury']);
	}else{
		$TotalArenaCapacity = ($Row['ArenaCapacityL1'] + $Row['ArenaCapacityL2']);
	}
	If ($Row['ScheduleHomeGameInAYear'] > 0){echo "<td>" . ($Row['ScheduleHomeGameInAYear'] - $Row['HomeGP'] ). "</td>\n";}else{echo "<td>" . (($Row['ScheduleGameInAYear'] / 2) - $Row['HomeGP'])  . "</td>\n";}
	if ($Row['HomeGP'] > 0){echo "<td>" . Round($Row['TotalAttendance'] / $Row['HomeGP']) . " - ";echo number_Format(($Row['TotalAttendance'] / ($TotalArenaCapacity * $Row['HomeGP'])) *100,2) . "%</td>\n";
	}else{echo "<td>0 - 0.00%</td>";}
	if ($Row['HomeGP'] > 0){echo "<td>" . number_format($Row['TotalIncome'] / $Row['HomeGP'],0) . "$</td>";}else{echo "<td>0$</td>";}
	echo "<td>" . number_format($Row['TotalIncome'],0) . "$</td>";
	echo "<td>" . $TotalArenaCapacity . "</td>";
	echo "<td>" . $Row['TeamPopularity'] . "</td>";	
	
	echo "<td>" . number_Format($Row['TotalPlayersSalaries'],0) . "$</td>\n";
	echo "<td>" . number_Format($Row['TotalPlayersSalariesAverage'],0) . "$</td>\n";
	if ($TypeText == "Pro"){echo "<td>" . number_Format($Row['SpecialSalaryCapY1'],0) . "$</td>\n";}
	echo "<td>" . number_Format(($Row['ExpenseThisSeason']),0) . "$</td>\n";
	echo "<td>" . number_Format($Row['SalaryCapPerDay'],0) . "$</td>\n";
	echo "<td>" . number_Format($Row['SalaryCapToDate'],0) . "$</td>\n";
	echo "<td>" . $Row['PlayerInSalaryCap'] . "</td>\n";
	echo "<td>" . $Row['PlayerOutofSalaryCap'] . "</td>\n";
	if ($TypeText == "Pro"){echo "<td>" . number_Format($Row['LuxuryTaxeTotal'],0) . "$</td>\n";}
	
	echo "<td>" . number_Format($Row['EstimatedRevenue'],0) . "$</td>\n";
	$Remaining = ($LeagueGeneral['ProScheduleTotalDay'] - $LeagueGeneral['ScheduleNextDay'] + 1);
	echo "<td>";if($Remaining > 0){echo $Remaining;}else{echo "0";}echo "</td>\n";
	echo "<td>" . number_Format($Row['ExpensePerDay'],0) . "$</td>\n";
	echo "<td>" . number_Format($Row['EstimatedSeasonExpense'],0) . "$</td>\n";
	
	if ($TypeText == "Pro"){
		echo "<td>" . number_Format(($Row['EstimatedSeasonExpense'] + $Row['FarmEstimatedSeasonExpense']) ,0) . "$</td>\n";
		if ($LeagueFinance['SalaryCapOption'] > 0){
			$TeamSalaryCap = 0;
			if ($LeagueFinance['SalaryCapOption'] == 0){
				$TeamSalaryCap = 2147483647;
			}elseif($LeagueFinance['SalaryCapOption'] == 2 OR $LeagueFinance['SalaryCapOption'] == 5){
				$TeamSalaryCap = $Row['CurrentBankAccount'] + $LeagueFinance['ProSalaryCapValue'];
			}else{
				$TeamSalaryCap = $LeagueFinance['ProSalaryCapValue'];
			}
			If ($Row['TotalSalaryCap'] > $TeamSalaryCap OR $Row['TotalSalaryCap'] < $LeagueFinance['ProMinimumSalaryCap']){
				echo "<td><span style=\"color:red\">" . number_Format($Row['TotalSalaryCap'],0) . "$</span></td>\n";
			}else{		
				echo "<td>" . number_Format($Row['TotalSalaryCap'],0) . "$</td>\n";
			}
			echo "<td>" . number_Format($TeamSalaryCap,0) . "$</td>\n";
			echo "<td>" . number_Format($TeamSalaryCap - $Row['TotalSalaryCap'],0) . "$</td>\n";
		}
		if ($LeagueFinance['CurrentFundMinimumWarning'] > $Row['CurrentBankAccount']){echo "<td><span style=\"color:red\">" . number_Format($Row['CurrentBankAccount'],0) . "$</span></td>\n";}else{echo "<td>" . number_Format($Row['CurrentBankAccount'],0) . "$</td>\n";}
		if ($LeagueFinance['CurrentFundMinimumWarning'] > $Row['ProjectedBankAccount']){echo "<td><span style=\"color:red\">" . number_Format($Row['ProjectedBankAccount'],0) . "$</span></td>\n";}else{echo "<td>" . number_Format($Row['ProjectedBankAccount'],0) . "$</td>\n";}
	}
	
	echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
}}
?>
</tbody></table>

<?php include "Footer.php";?>
