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
		If ($OrderByField == "Name"){$OrderByField = "TeamVSName";}
		$Query = "SELECT Team" . $TypeText . "StatVS.* FROM Team" . $TypeText . "StatVS WHERE GP > 0 AND TeamNumber = " . $Team . " ORDER BY Team" . $TypeText . "StatVS." . $OrderByField;
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
	$TeamStat = $db->query($Query);
}
?>

</head><body>
<?php include "Menu.php";?>
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
    <button class="tablesorter_Reset" type="button"><?php echo $TableSorterLang['ResetAllSearchFilter'];?></button>
	<div class="tablesorter_Reset FilterTipMain"><?php echo $TableSorterLang['FilterTips'];?>
	<table class="FilterTip"><thead><tr><th style="width:55px">Priority</th><th style="width:100px"><?php echo $PlayersLang['Type'];?></th><th style="width:485px">Description</th></tr></thead>
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

<table class="tablesorter custom-popup STHSPHPTeamsStat_Table"><thead><tr>
<th class="sorter-false"></th><th class="sorter-false" colspan="10"><?php echo $TeamStatLang['Overall'];?></th><th class="sorter-false" colspan="10"><?php echo $TeamStatLang['Home'];?></th><th class="sorter-false" colspan="10"><?php echo $TeamStatLang['Visitor'];?></th><th class="sorter-false" colspan="41"></th></tr><tr>
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
<th data-priority="4" title="Points Percentage" class="STHSW45">PCT</th>
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
if (empty($TeamStat) == false){while ($row = $TeamStat ->fetchArray()) {
	If ($Team == 0){
		echo "<tr><td><a href=\"" . $TypeText . "Team.php?Team=" . $row['Number'] . "\">" . $row['Name'] . "</a></td>";
	}else{
		echo "<tr><td><a href=\"" . $TypeText . "Team.php?Team=" . $row['TeamVSNumber'] . "\">" . $row['TeamVSName'] . "</a></td>";
	}
	echo "<td>" . $row['GP'] . "</td>";
	echo "<td>" . $row['W']  . "</td>";
	echo "<td>" . $row['L'] . "</td>";
	echo "<td>" . $row['T'] . "</td>";
	echo "<td>" . $row['OTW'] . "</td>";	
	echo "<td>" . $row['OTL'] . "</td>";	
	echo "<td>" . $row['SOW'] . "</td>";	
	echo "<td>" . $row['SOL'] . "</td>";	
	echo "<td>" . $row['GF'] . "</td>";
	echo "<td>" . $row['GA'] . "</td>";
	echo "<td>" . ($row['GF'] - $row['GA']) . "</td>";	
	echo "<td>" . $row['HomeGP'] . "</td>";
	echo "<td>" . $row['HomeW']  . "</td>";
	echo "<td>" . $row['HomeL'] . "</td>";
	echo "<td>" . $row['HomeT'] . "</td>";
	echo "<td>" . $row['HomeOTW'] . "</td>";	
	echo "<td>" . $row['HomeOTL'] . "</td>";	
	echo "<td>" . $row['HomeSOW'] . "</td>";	
	echo "<td>" . $row['HomeSOL'] . "</td>";	
	echo "<td>" . $row['HomeGF'] . "</td>";
	echo "<td>" . $row['HomeGA'] . "</td>";	
	echo "<td>" . ($row['HomeGF'] - $row['HomeGA']) . "</td>";	
	echo "<td>" . ($row['GP'] - $row['HomeGP']) . "</td>";
	echo "<td>" . ($row['W'] - $row['HomeW']) . "</td>";
	echo "<td>" . ($row['L'] - $row['HomeL']) . "</td>";
	echo "<td>" . ($row['T'] - $row['HomeT']) . "</td>";	
	echo "<td>" . ($row['OTW'] - $row['HomeOTW']) . "</td>";
	echo "<td>" . ($row['OTL'] - $row['HomeOTL']) . "</td>";
	echo "<td>" . ($row['SOW'] - $row['HomeSOW']) . "</td>";
	echo "<td>" . ($row['SOL'] - $row['HomeSOL']) . "</td>";
	echo "<td>" . ($row['GF'] - $row['HomeGF']) . "</td>";
	echo "<td>" . ($row['GA'] - $row['HomeGA']) . "</td>";
	echo "<td>" . (($row['GF'] - $row['HomeGF']) - ($row['GA'] - $row['HomeGA'])) . "</td>";		
	echo "<td><strong>" . $row['Points'] . "</strong></td>";
	if ($row['GP'] > 0 AND $LeagueGeneral['PointSystemW'] > 0){echo "<td>" . number_Format(($row['Points'] / ($row['GP'] * $LeagueGeneral['PointSystemW'])),3) . "</td>";}else{echo "<td>" . number_Format("0",3) . "</td>";}
	echo "<td>" . $row['TotalGoal'] . "</td>";
	echo "<td>" . $row['TotalAssist'] . "</td>";
	echo "<td>" . $row['TotalPoint'] . "</td>";
	echo "<td>" . $row['EmptyNetGoal']. "</td>";
	echo "<td>" . $row['Shutouts']. "</td>";		
	echo "<td>" . $row['GoalsPerPeriod1']. "</td>";		
	echo "<td>" . $row['GoalsPerPeriod2']. "</td>";	
	echo "<td>" . $row['GoalsPerPeriod3']. "</td>";	
	echo "<td>" . $row['GoalsPerPeriod4']. "</td>";	
	echo "<td>" . $row['ShotsFor']. "</td>";	
	echo "<td>" . $row['ShotsPerPeriod1']. "</td>";
	echo "<td>" . $row['ShotsPerPeriod2']. "</td>";
	echo "<td>" . $row['ShotsPerPeriod3']. "</td>";
	echo "<td>" . $row['ShotsPerPeriod4']. "</td>";
	echo "<td>" . $row['ShotsAga']. "</td>";
	echo "<td>" . $row['ShotsBlock']. "</td>";		
	echo "<td>" . $row['Pim']. "</td>";
	echo "<td>" . $row['Hits']. "</td>";	
	echo "<td>" . $row['PPAttemp']. "</td>";
	echo "<td>" . $row['PPGoal']. "</td>";
	echo "<td>";if ($row['PPAttemp'] > 0){echo number_Format($row['PPGoal'] / $row['PPAttemp'] * 100,2) . "%";} else { echo "0.00%";} echo "</td>";		
	echo "<td>" . $row['PKAttemp']. "</td>";
	echo "<td>" . $row['PKGoalGA']. "</td>";
	echo "<td>";if ($row['PKAttemp'] > 0){echo number_Format(($row['PKAttemp'] - $row['PKGoalGA']) / $row['PKAttemp'] * 100,2) . "%";} else {echo "0.00%";} echo "</td>";
	echo "<td>" .  $row['PKGoalGF']. "</td>";	
	echo "<td>" . $row['FaceOffWonOffensifZone']. "</td>";
	echo "<td>" . $row['FaceOffTotalOffensifZone']. "</td>";		
	echo "<td>";if ($row['FaceOffTotalOffensifZone'] > 0){echo number_Format($row['FaceOffWonOffensifZone'] / $row['FaceOffTotalOffensifZone'] * 100,2) . "%" ;} else { echo "0.00%";} echo "</td>";	
	echo "<td>" . $row['FaceOffWonDefensifZone']. "</td>";
	echo "<td>" . $row['FaceOffTotalDefensifZone']. "</td>";
	echo "<td>";if ($row['FaceOffTotalDefensifZone'] > 0){echo number_Format($row['FaceOffWonDefensifZone'] / $row['FaceOffTotalDefensifZone'] * 100,2) . "%" ;} else { echo "0.00%";} echo "</td>";	
	echo "<td>" . $row['FaceOffWonNeutralZone']. "</td>";	
	echo "<td>" . $row['FaceOffTotalNeutralZone']. "</td>";	
	echo "<td>";if ($row['FaceOffTotalNeutralZone'] > 0){echo number_Format($row['FaceOffWonNeutralZone'] / $row['FaceOffTotalNeutralZone'] * 100,2) . "%" ;} else { echo "0.00%";} echo "</td>";	
	echo "<td>" . Floor($row['PuckTimeInZoneOF']/60). "</td>";
	echo "<td>" . Floor($row['PuckTimeControlinZoneOF']/60). "</td>";
	echo "<td>" . Floor($row['PuckTimeInZoneDF']/60). "</td>";
	echo "<td>" . Floor($row['PuckTimeControlinZoneDF']/60). "</td>";
	echo "<td>" . Floor($row['PuckTimeInZoneNT']/60). "</td>";		
	echo "<td>" . Floor($row['PuckTimeControlinZoneNT']/60). "</td>";		
	echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
}}
echo "</tbody></table>";

?>
</div>


<?php
include "Footer.php";
?>

