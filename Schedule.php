<!DOCTYPE html>
<?php include "Header.php";?>
<?php
$Title = (string)"";
If (file_exists($DatabaseFile) == false){
	$LeagueName = $DatabaseNotFound;
	$Schedule = Null;
	echo "<title>" . $DatabaseNotFound . "</title>";
	$Title = $DatabaseNotFound;
}else{
	$Team = (integer)0; /* 0 All Team */
	$TypeText = (string)"Pro";$TitleType = $DynamicTitleLang['Pro'];
	$LeagueName = (string)"";
	if(isset($_GET['Farm'])){$TypeText = "Farm";$TitleType = $DynamicTitleLang['Farm'];}
	if(isset($_GET['Team'])){$Team = filter_var($_GET['Team'], FILTER_SANITIZE_NUMBER_INT);}

	$db = new SQLite3($DatabaseFile);
	
	$Query = "Select ScheduleUseDateInsteadofDay, ScheduleRealDate from LeagueOutputOption";
	$LeagueOutputOption = $db->querySingle($Query,true);	
	$Query = "Select Name, DefaultSimulationPerDay, TradeDeadLine, ProScheduleTotalDay from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	
	If ($Team == 0){
		$Title = $ScheduleLang['ScheduleTitle1'] . $ScheduleLang['ScheduleTitle2'] . " " . $TitleType;
		$Query = "SELECT * FROM Schedule" . $TypeText . " ORDER BY GameNumber";
	}else{
		$Query = "SELECT Name FROM Team" . $TypeText . "Info WHERE Number = " . $Team ;
		$TeamName = $db->querySingle($Query);
		$Title =  $ScheduleLang['TeamTitle'] . $TitleType . " " .  $TeamName;
		
		$Query = "SELECT * FROM Schedule" . $TypeText . " WHERE (VisitorTeam = " . $Team . " OR HomeTeam = " . $Team . ") ORDER BY GameNumber";
	}
	$Schedule = $db->query($Query);
	
	$Query = "SELECT * FROM " . $TypeText . "RivalryInfo WHERE Team1 = " . $Team . " ORDER BY Team2";
	$RivalryInfo = $db->query($Query);	
	echo "<title>" . $LeagueName . " - " . $Title . "</title>";
}?>
</head><body>
<?php include "Menu.php";?>
<?php echo "<h1>" . $Title . "</h1>"; ?>
<script type="text/javascript">
$(function() {
  $(".custom-popup").tablesorter({
    widgets: ['stickyHeaders', 'filter', 'staticRow'],
    widgetOptions : {
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

<table class="tablesorter custom-popup STHSPHPSchedule_ScheduleTable"><thead><tr>
<?php
if ($LeagueOutputOption['ScheduleUseDateInsteadofDay'] == TRUE){
	echo "<th title=\"Day\" class=\"STHSW100\">" . $ScheduleLang['Day'] ."</th>";
}else{
	echo "<th title=\"Day\" class=\"STHSW45\">" . $ScheduleLang['Day'] ."</th>";
}
?>
<th title="Game Number" class="STHSW35"><?php echo $ScheduleLang['Game'];?></th>
<th title="Visitor Team" class="STHSW200"><?php echo $ScheduleLang['VisitorTeam'];?></th>
<th title="Visitor Team Score" class="STHSW35"><?php echo $ScheduleLang['Score'];?></th>
<th title="Home Team" class="STHSW200"><?php echo $ScheduleLang['HomeTeam'];?></th>
<th title="Home Team Score" class="STHSW35"><?php echo $ScheduleLang['Score'];?></th>
<th title="Streak" class="STHSW35">ST</th>
<th title="Overtime" class="STHSW35">OT</th>
<th title="Shootout" class="STHSW35">SO</th>
<th title="Rivalry" class="STHSW35">RI</th>
<th title="Game Link" class="STHSW100"><?php echo $ScheduleLang['Link'];?></th>
</tr></thead><tbody>
<?php
$TradeDeadLine = (boolean)False;
if (empty($Schedule) == false){while ($row = $Schedule ->fetchArray()) {
	If ($TradeDeadLine == False AND ($row['Day'] > (($LeagueGeneral['TradeDeadLine'] / 100) * $LeagueGeneral['ProScheduleTotalDay']))){
		$TradeDeadLine = True;
		echo "<tr class=\"static\"><td colspan=\"11\" class=\"STHSCenter\"><strong>Trade Deadline --- Trades can’t be done after this day is simulated!</strong></td></tr>";
	}
	if ($LeagueOutputOption['ScheduleUseDateInsteadofDay'] == TRUE){
		$ScheduleDate = date_create($LeagueOutputOption['ScheduleRealDate']);
		date_add($ScheduleDate, DateInterval::createFromDateString(Floor((($row['Day'] -1) / $LeagueGeneral['DefaultSimulationPerDay'])) . " days"));
		echo "<tr><td>" . $row['Day'] . " - " . date_Format($ScheduleDate,"Y-m-d") . "</td>";
	}else{
		echo "<tr><td>" . $row['Day']. "</td>";
	}
	echo "<td>" . $row['GameNumber']. "</td>";
	echo "<td><a href=\"" . $TypeText . "Team.php?Team=" . $row['VisitorTeam'] . "\">" . $row['VisitorTeamName']. "</a></td>";
	echo "<td>"; if ($row['Play'] == "True"){echo $row['VisitorScore'];} else { echo "-";};echo "</td>";
	echo "<td><a href=\"" . $TypeText . "Team.php?Team=" . $row['HomeTeam'] . "\">" . $row['HomeTeamName']. "</a></td>";	
	echo "<td>"; if ($row['Play'] == "True"){echo $row['HomeScore'];} else { echo "-";};echo "</td>";	
	echo "<td>"; if ($row['Play'] == "True"){
	if( $row['VisitorTeam'] == $Team){
		if($row['VisitorScore'] >  $row['HomeScore']){echo "W";}elseif($row['VisitorScore'] <  $row['HomeScore']){echo "L";}else{echo "T";}
		$OtherTeam = $row['HomeTeam'];
	}else{
		if($row['HomeScore'] >  $row['VisitorScore']){echo "W";}elseif($row['HomeScore'] <  $row['VisitorScore']){echo "L";}else{echo "T";}
		$OtherTeam = $row['VisitorTeam'];
	}; 
	};	echo "</td>";
	echo "<td>"; if ($row['Overtime'] != "False"){echo "X";};echo "</td>";
	echo "<td>"; if ($row['Shootout'] != "False"){echo "X";};echo "</td>";
	echo "<td>";
	if (empty($RivalryInfo) == false){while ($rowR = $RivalryInfo ->fetchArray()) {
	if ($rowR['Team2'] == $OtherTeam){
		echo "R" . $rowR['Rivalry'];
		break;
	}}}
	echo "</td>";
	echo "<td>"; if ($row['Play'] == "True") {echo "<a href=\"" . $row['Link'] . "\" target=\"_blank\">" . $ScheduleLang['BoxScore'] . "</a>";} echo "</td>";
	echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
}}
?>
</tbody></table>

<br />
</div>

<?php include "Footer.php";?>
