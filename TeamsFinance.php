<!DOCTYPE html>
<?php include "Header.php";?>
<?php
$Title = (string)"";
If (file_exists($DatabaseFile) == false){
	$LeagueName = $DatabaseNotFound;
	$TeamStat = Null;
	echo "<title>" . $DatabaseNotFound . "</title>";
	$Title = $DatabaseNotFound;
	$Team = 0;
}else{
	$DESCQuery = (boolean)FALSE;/* The SQL Query must be Descending Order and not Ascending*/
	$TypeText = (string)"Pro";$TitleType = $DynamicTitleLang['Pro'];
	$LeagueName = (string)"";
	$Team = (integer)0;
	if(isset($_GET['Farm'])){$TypeText = "Farm";$TitleType = $DynamicTitleLang['Farm'];}
	if(isset($_GET['Order'])){$OrderByInput  = filter_var($_GET['Order'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH);} 
	
	$db = new SQLite3($DatabaseFile);
	
	$Query = "Select Name, PointSystemW from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	
	$Query = "SELECT * FROM Team" . $TypeText . "Finance ORDER BY Name";
	$Title = $DynamicTitleLang['TeamFinance'] . " " . $TitleType;	
	
	echo "<title>" . $LeagueName . " - " . $Title . "</title>";
	$TeamFinance = $db->query($Query);
}
?>

</head><body>
<?php include "Menu.php";?>

<script>
$(function() {
  $.tablesorter.addWidget({ id: "numbering",format: function(table) {var c = table.config;$("tr:visible", table.tBodies[0]).each(function(i) {$(this).find('td').eq(0).text(i + 1);});}});	
  $(".STHSPHPTeamsFinance_Table").tablesorter({
    widgets: ['numbering','columnSelector', 'stickyHeaders', 'filter'],
    widgetOptions : {
      columnSelector_container : $('#tablesorter_ColumnSelector'),
      columnSelector_layout : '<label><input type="checkbox">{name}</label>',
      columnSelector_name  : 'title',
      columnSelector_mediaquery: true,
      columnSelector_mediaqueryName: 'Automatic',
      columnSelector_mediaqueryState: true,
      columnSelector_mediaqueryHidden: true,
	  <?php	If ($TypeText == "Pro"){
		  echo "columnSelector_breakpoints : [ '40em', '60em', '75em', '85em', '90em', '95em' ],";
	  }else{
		  echo "columnSelector_breakpoints : [ '30em', '50em', '70em', '80em', '90em', '95em' ],";
	  }?>
	  filter_columnFilters: true,
      filter_placeholder: { search : '<?php echo $TableSorterLang['Search'];?>' },
	  filter_searchDelay : 500,	  
      filter_reset: '.tablesorter_Reset'	 
    }
  });
});
</script>

<div style="width:99%;margin:auto;">
<?php echo "<h1>" . $Title . "</h1>"; ?>
<div class="tablesorter_ColumnSelectorWrapper">
    <input id="tablesorter_colSelect1" type="checkbox" class="hidden">
    <label class="tablesorter_ColumnSelectorButton" for="tablesorter_colSelect1"><?php echo $TableSorterLang['ShoworHideColumn'];?></label>
    <div id="tablesorter_ColumnSelector" class="tablesorter_ColumnSelector"></div>
	<?php include "FilterTip.php";?>
	</div>
</div>

<table class="tablesorter STHSPHPTeamsFinance_Table"><thead><tr>
<th data-priority="1" title="Order Number" class="STHSW10 sorter-false">#</th>
<th data-priority="critical" title="Team Name" class="STHSW200"><?php echo $TeamStatLang['TeamName'];?></th>
<th data-priority="5" title="Arena Capacity Level 1" class="columnSelector-false STHSW25">ACL1</th>
<th data-priority="5" title="Arena Capacity Level 2" class="columnSelector-false STHSW25">ACL2</th>
<?php
If ($TypeText == "Pro"){
echo "<th data-priority=\"5\" title=\"Arena Capacity Level 3\" class=\"columnSelector-false STHSW25\">ACL3</th>";
echo "<th data-priority=\"5\" title=\"Arena Capacity Level 4\" class=\"columnSelector-false STHSW25\">ACL4</th>";
echo "<th data-priority=\"5\" title=\"Arena Capacity Luxury\" class=\"columnSelector-false STHSW25\">ARL</th>";
}?>
<th data-priority="4" title="Ticket Price Level 1" class="columnSelector-false STHSW25">TPL1</th>
<th data-priority="4" title="Ticket Price Level 2" class="columnSelector-false STHSW25">TPL2</th>
<?php
If ($TypeText == "Pro"){
echo "<th data-priority=\"4\" title=\"Ticket Price Level 3\" class=\"columnSelector-false STHSW25\">TPL3</th>";
echo "<th data-priority=\"4\" title=\"Ticket Price Level 4\" class=\"columnSelector-false STHSW25\">TPL4</th>";
echo "<th data-priority=\"4\" title=\"Ticket Price Luxury\" class=\"columnSelector-false STHSW25\">TPL</th>";
}?>
<th data-priority="4" title="Attendance Level 1" class="columnSelector-false STHSW25">AL1</th>
<th data-priority="4" title="Attendance Level 2" class="columnSelector-false STHSW25">AL2</th>
<?php
If ($TypeText == "Pro"){
echo "<th data-priority=\"4\" title=\"Attendance Level 3\" class=\"columnSelector-false STHSW25\">AL3</th>";
echo "<th data-priority=\"4\" title=\"Attendance Level 4\" class=\"columnSelector-false STHSW25\">AL4</th>";
echo "<th data-priority=\"4\" title=\"Attendance Luxury\" class=\"columnSelector-false STHSW25\">AL</th>";
}?>
<th data-priority="1" title="Salary Cap To Date" class="STHSW100">Salary Cap To Date</th>
<th data-priority="2" title="Salary Cap Per Day" class="STHSW100">Salary Cap Per Day</th>
<th data-priority="3" title="Total Players Salaries" class="STHSW100">Total Players Salaries</th>
<th data-priority="3" title="Total Players Salaries Average" class="STHSW100">Total Players Salaries Average</th>
<th data-priority="1" title="Expense Per Day" class="STHSW100">Expense Per Day</th>
<th data-priority="2" title="Estimated Revenue" class="STHSW100">Estimated Revenue</th>
<th data-priority="2" title="Estimated Season Expense" class="STHSW100">Estimated Season Expense</th>
<?php If ($TypeText == "Pro"){echo "<th data-priority=\"1\" title=\"Total Salary Cap\" class=\"STHSW100\">Total Salary Cap</th>";}?>
<th data-priority="2" title="Total Attendance" class="STHSW100">Total Attendance</th>
<th data-priority="1" title="Total Income" class="STHSW100">Total Income</th>
<th data-priority="1" title="Expense This Season" class="STHSW100">Expense This Season</th>
<th data-priority="4" title="Season Ticket PCT" class="columnSelector-false STHSW100">Season Ticket PCT</th>
<th data-priority="4" title="Team Popularity" class="STHSW100">Team Popularity</th>

<?php
If ($TypeText == "Pro"){
echo "<th data-priority=\"1\" title=\"Current Bank Account\" class=\"STHSW100\">Current Bank Account</th>";
echo "<th data-priority=\"1\" title=\"Projected Bank Account\" class=\"STHSW100\">Projected Bank Account</th>";
echo "<th data-priority=\"2\" title=\"Special Salary Cap Year 1\" class=\"STHSW25\">SSCY1</th>";
echo "<th data-priority=\"6\" title=\"Special Salary Cap Year 2\" class=\"columnSelector-false STHSW25\">SSCY2</th>";
echo "<th data-priority=\"6\" title=\"Special Salary Cap Year 3\" class=\"columnSelector-false STHSW25\">SSCY3</th>";
echo "<th data-priority=\"6\" title=\"Special Salary Cap Year 4\" class=\"columnSelector-false STHSW25\">SSCY4</th>";
echo "<th data-priority=\"6\" title=\"Special Salary Cap Year 5\" class=\"columnSelector-false STHSW25\">SSCY5</th>";
echo "<th data-priority=\"6\" title=\"Special Salary Cap Year 6\" class=\"columnSelector-false STHSW25\">SSCY6</th>";
echo "<th data-priority=\"6\" title=\"Special Salary Cap Year 7\" class=\"columnSelector-false STHSW25\">SSCY7</th>";
echo "<th data-priority=\"6\" title=\"Special Salary Cap Year 8\" class=\"columnSelector-false STHSW25\">SSCY8</th>";
echo "<th data-priority=\"6\" title=\"Special Salary Cap Year 9\" class=\"columnSelector-false STHSW25\">SSCY9</th>";
echo "<th data-priority=\"6\" title=\"Special Salary Cap Year 10\" class=\"columnSelector-false STHSW25\">SSCY10</th>";
echo "<th data-priority=\"4\" title=\"Luxury Taxe Total\" class=\"columnSelector-false STHSW100\">Luxury Taxe Total</th>";
}
?>
<th data-priority="3" title="Player In Salary Cap" class="columnSelector-false STHSW100">Player In Salary Cap</th>
<th data-priority="3" title="Player Out Of Salary Cap" class="columnSelector-false STHSW100">Player Out of Salary Cap</th>
</tr></thead><tbody>
<?php
$Order = 0;
$NoSort = (boolean)FALSE;
if (empty($TeamFinance) == false){while ($row = $TeamFinance ->fetchArray()) {
	$Order +=1;
	If ($row['Number'] <= 100){
		echo "<tr><td>" . $Order ."</td>";		
		echo "<td><a href=\"" . $TypeText . "Team.php?Team=" . $row['Number'] . "\">" . $row['Name'] . "</a></td>";
	}else{
		If ($NoSort == False){echo "</tbody><tbody class=\"tablesorter-no-sort\">";$NoSort=True;}
		echo "<tr><td></td>";
		echo "<td>" . $row['Name'] . "</td>";
	}
	echo "<td>" . $row['ArenaCapacityL1'] . "</td>";
	echo "<td>" . $row['ArenaCapacityL2'] . "</td>";
	If ($TypeText == "Pro"){
		echo "<td>" . $row['ArenaCapacityL3'] . "</td>";
		echo "<td>" . $row['ArenaCapacityL4'] . "</td>";
		echo "<td>" . $row['ArenaCapacityLuxury'] . "</td>";
	}
	echo "<td>" . $row['TicketPriceL1'] . "$</td>";
	echo "<td>" . $row['TicketPriceL2'] . "$</td>";
	If ($TypeText == "Pro"){	
		echo "<td>" . $row['TicketPriceL3'] . "$</td>";
		echo "<td>" . $row['TicketPriceL4'] . "$</td>";
		echo "<td>" . $row['TicketPriceLuxury'] . "$</td>";
	}
	echo "<td>" . $row['AttendanceL1'] . "</td>";
	echo "<td>" . $row['AttendanceL2'] . "</td>";
	If ($TypeText == "Pro"){	
		echo "<td>" . $row['AttendanceL3'] . "</td>";
		echo "<td>" . $row['AttendanceL4'] . "</td>";
		echo "<td>" . $row['AttendanceLuxury'] . "</td>";
	}
	echo "<td>" . number_format($row['SalaryCapToDate'],0) . "$</td>";
	echo "<td>" . number_format($row['SalaryCapPerDay'],0) . "$</td>";
	echo "<td>" . number_format($row['TotalPlayersSalaries'],0) . "$</td>";
	echo "<td>" . number_format($row['TotalPlayersSalariesAverage'],0) . "$</td>";
	echo "<td>" . number_format($row['ExpensePerDay'],0) . "$</td>";
	echo "<td>" . number_format($row['EstimatedRevenue'],0) . "$</td>";
	echo "<td>" . number_format($row['EstimatedSeasonExpense'],0) . "$</td>";
	If ($TypeText == "Pro"){echo "<td>" . number_format($row['TotalSalaryCap'],0) . "$</td>";}
	echo "<td>" . number_format($row['TotalAttendance'],0) . "$</td>";
	echo "<td>" . number_format($row['TotalIncome'],0) . "$</td>";
	echo "<td>" . number_format($row['ExpenseThisSeason'],0) . "$</td>";
	echo "<td>" . $row['SeasonTicketPCT'] . "</td>";
	echo "<td>" . $row['TeamPopularity'] . "</td>";
	If ($TypeText == "Pro"){	
		echo "<td>" . number_format($row['CurrentBankAccount'],0) . "$</td>";
		echo "<td>" . number_format($row['ProjectedBankAccount'],0) . "$</td>";
		echo "<td>" . number_format($row['SpecialSalaryCapY1'],0) . "$</td>";
		echo "<td>" . number_format($row['SpecialSalaryCapY2'],0) . "$</td>";
		echo "<td>" . number_format($row['SpecialSalaryCapY3'],0) . "$</td>";
		echo "<td>" . number_format($row['SpecialSalaryCapY4'],0) . "$</td>";
		echo "<td>" . number_format($row['SpecialSalaryCapY5'],0) . "$</td>";
		echo "<td>" . number_format($row['SpecialSalaryCapY6'],0) . "$</td>";
		echo "<td>" . number_format($row['SpecialSalaryCapY7'],0) . "$</td>";
		echo "<td>" . number_format($row['SpecialSalaryCapY8'],0) . "$</td>";
		echo "<td>" . number_format($row['SpecialSalaryCapY9'],0) . "$</td>";
		echo "<td>" . number_format($row['SpecialSalaryCapY10'],0) . "$</td>";
		echo "<td>" . number_format($row['LuxuryTaxeTotal'],0) . "$</td>";
	}
	echo "<td>" . $row['PlayerInSalaryCap'] . "</td>";
	echo "<td>" . $row['PlayerOutofSalaryCap'] . "</td>";
	echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
}}
?>
</tbody></table>
</div>


<?php
include "Footer.php";
?>

