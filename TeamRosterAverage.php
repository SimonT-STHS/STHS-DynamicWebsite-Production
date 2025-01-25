<?php include "Header.php";
If ($lang == "fr"){include 'LanguageFR-Stat.php';}else{include 'LanguageEN-Stat.php';}
$LeagueName = (string)"";
$TypeText = (string)"Pro";$TitleType = $DynamicTitleLang['Pro'];
if(isset($_GET['Farm'])){$TypeText = "Farm";$TitleType = $DynamicTitleLang['Farm'];}
If (file_exists($DatabaseFile) == false){
	Goto STHSErrorBlankPage;
}else{try{
	$LeagueName = (string)"";
		
	$db = new SQLite3($DatabaseFile);
	
	$Query = "Select Name FROM LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	
	$Query = "SELECT Number, Name, TeamThemeID FROM Team" . $TypeText . "Info ORDER BY Name";
	$Team = $db->query($Query);
	
} catch (Exception $e) {
STHSErrorBlankPage:
	$LeagueName = $DatabaseNotFound;
	echo "<style>.STHSBlankPage_MainDiv{display:none}</style>";
}}
echo "<title>" . $LeagueName . " - " . $TypeText . " " . $TopMenuLang['AverageTeamsRoster'] . "</title>";
?>
<style>
@media screen and (max-width: 1200px) {
.tablesorter thead th:nth-last-child(1), .tablesorter tbody td:nth-last-child(1), .tablesorter .tablesorter-filter-row td:nth-last-child(1){display:none;}
.tablesorter thead th:nth-last-child(2), .tablesorter tbody td:nth-last-child(2), .tablesorter .tablesorter-filter-row td:nth-last-child(2){display:none;}
.tablesorter thead th:nth-last-child(3), .tablesorter tbody td:nth-last-child(3), .tablesorter .tablesorter-filter-row td:nth-last-child(3){display:none;}
}
@media screen and (max-width: 1050px) {.STHSPHPTeamRosterAverageTeamImage{display:none;}}
</style>
</head><body>
<?php include "Menu.php";?>
<script>
$(function() {
  $.tablesorter.addWidget({ id: "numbering",format: function(table) {var c = table.config;$("tr:visible", table.tBodies[0]).each(function(i) {$(this).find('td').eq(0).text(i + 1);});}});	
  $(".STHSPHPTeam_AveragePlayersRosterTable").tablesorter({	  
	sortList : [[19,1], [1,0]],
    showProcessing: true,
    widgets: ['numbering', 'stickyHeaders', 'filter'],
    widgetOptions : {
	  stickyHeaders_zIndex : 110,
	  filter_columnFilters: true,
      filter_placeholder: { search : '<?php echo $TableSorterLang['Search'];?>' },
	  filter_searchDelay : 500,	  
      filter_reset: '.tablesorter_Reset'	 
    }
  });
  $(".STHSPHPTeam_AverageGoaliesRosterTable").tablesorter({
	sortList : [[17,1], [1,0]],	  
    showProcessing: true,
    widgets: ['numbering', 'stickyHeaders', 'filter'],
    widgetOptions : {
	  stickyHeaders_zIndex : 110,
	  filter_columnFilters: true,
      filter_placeholder: { search : '<?php echo $TableSorterLang['Search'];?>' },
	  filter_searchDelay : 500,	  
      filter_reset: '.tablesorter_Reset'	 
    }
  });   
});
</script>

<div class="STHSBlankPage_MainDiv" style="width:99%;margin:auto;">
<h1><?php echo $TypeText . " " . $TeamLang['PlayersAverage'];?></h1>
<table class="STHSPHPTeam_AveragePlayersRosterTable tablesorter"><thead><tr>
<th data-priority="1" title="Order Number" class="STHSW10 sorter-false">#</th>
<th data-priority="critical" title="Team Name" class="STHSW140"><?php echo $PlayersLang['TeamName'];?></th>
<th data-priority="2" title="Checking" class="STHSW25">CK</th>
<th data-priority="2" title="Fighting" class="STHSW25">FG</th>
<th data-priority="2" title="Discipline" class="STHSW25">DI</th>
<th data-priority="2" title="Skating" class="STHSW25">SK</th>
<th data-priority="2" title="Strength" class="STHSW25">ST</th>
<th data-priority="2" title="Endurance" class="STHSW25">EN</th>
<th data-priority="2" title="Durability" class="STHSW25">DU</th>
<th data-priority="2" title="Puck Handling" class="STHSW25">PH</th>
<th data-priority="2" title="Face Offs" class="STHSW25">FO</th>
<th data-priority="2" title="Passing" class="STHSW25">PA</th>
<th data-priority="2" title="Scoring" class="STHSW25">SC</th>
<th data-priority="2" title="Defense" class="STHSW25">DF</th>
<th data-priority="2" title="Penalty Shot" class="STHSW25">PS</th>
<th data-priority="2" title="Experience" class="STHSW25">EX</th>
<th data-priority="2" title="Leadership" class="STHSW25">LD</th>
<th data-priority="3" title="Potential" class="STHSW25">PO</th>
<th data-priority="3" title="Morale" class="STHSW25">MO</th>
<th data-priority="critical" title="Overall" class="STHSW25">OV</th>
<?php
echo "<th data-priority=\"5\" class=\"STHSW25\" title=\"Age\">" . $PlayersLang['Age'] . "</th>";
echo "<th data-priority=\"5\" class=\"STHSW25\" title=\"Contract\">" . $PlayersLang['Contract'] . "</th>";
echo "<th data-priority=\"5\" class=\"STHSW65\" title=\"Salary\">" . $PlayersLang['Salary'] ."</th>";
echo "</tr></thead><tbody>";
$Order = 0;
if (empty($Team) == false){while ($Row = $Team ->fetchArray()) { 
	$Query = "SELECT Avg(PlayerInfo.ConditionDecimal) AS AvgOfConditionDecimal, Avg(PlayerInfo.CK) AS AvgOfCK, Avg(PlayerInfo.FG) AS AvgOfFG, Avg(PlayerInfo.DI) AS AvgOfDI, Avg(PlayerInfo.SK) AS AvgOfSK, Avg(PlayerInfo.ST) AS AvgOfST, Avg(PlayerInfo.EN) AS AvgOfEN, Avg(PlayerInfo.DU) AS AvgOfDU, Avg(PlayerInfo.PH) AS AvgOfPH, Avg(PlayerInfo.FO) AS AvgOfFO, Avg(PlayerInfo.PA) AS AvgOfPA, Avg(PlayerInfo.SC) AS AvgOfSC, Avg(PlayerInfo.DF) AS AvgOfDF, Avg(PlayerInfo.PS) AS AvgOfPS, Avg(PlayerInfo.EX) AS AvgOfEX, Avg(PlayerInfo.LD) AS AvgOfLD, Avg(PlayerInfo.PO) AS AvgOfPO, Avg(PlayerInfo.MO) AS AvgOfMO, Avg(PlayerInfo.Overall) AS AvgOfOverall, Avg(PlayerInfo.Age) AS AvgOfAge, Avg(PlayerInfo.Contract) AS AvgOfContract, Avg(PlayerInfo.Salary1) AS AvgOfSalary1 FROM PlayerInfo WHERE Team = " . $Row['Number'];
	If ($TypeText  == "Pro"){$Query = $Query . " AND Status1 >= 2";}elseif ($TypeText  == "Farm"){$Query = $Query . " AND Status1 <= 1";}
	$PlayerRosterAverage = $db->querySingle($Query,True);	
	$Order +=1;
	echo "<tr><td>" . $Order ."</td><td>";		
	If ($Row['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPTeamRosterAverageTeamImage\">";}			
	echo "<a href=\"" . $TypeText . "Team.php?Team=" . $Row['Number'] . "\">" . $Row['Name'] . "</a></td>";	
	echo "<td>" . Round($PlayerRosterAverage['AvgOfCK']) . "</td>";
	echo "<td>" . Round($PlayerRosterAverage['AvgOfFG']) . "</td>";
	echo "<td>" . Round($PlayerRosterAverage['AvgOfDI']) . "</td>";
	echo "<td>" . Round($PlayerRosterAverage['AvgOfSK']) . "</td>";
	echo "<td>" . Round($PlayerRosterAverage['AvgOfST']) . "</td>";
	echo "<td>" . Round($PlayerRosterAverage['AvgOfEN']) . "</td>";
	echo "<td>" . Round($PlayerRosterAverage['AvgOfDU']) . "</td>";
	echo "<td>" . Round($PlayerRosterAverage['AvgOfPH']) . "</td>";
	echo "<td>" . Round($PlayerRosterAverage['AvgOfFO']) . "</td>";
	echo "<td>" . Round($PlayerRosterAverage['AvgOfPA']) . "</td>";
	echo "<td>" . Round($PlayerRosterAverage['AvgOfSC']) . "</td>";
	echo "<td>" . Round($PlayerRosterAverage['AvgOfDF']) . "</td>";
	echo "<td>" . Round($PlayerRosterAverage['AvgOfPS']) . "</td>";
	echo "<td>" . Round($PlayerRosterAverage['AvgOfEX']) . "</td>";
	echo "<td>" . Round($PlayerRosterAverage['AvgOfLD']) . "</td>";
	echo "<td>" . Round($PlayerRosterAverage['AvgOfPO']) . "</td>";
	echo "<td>" . Round($PlayerRosterAverage['AvgOfMO']) . "</td>";
	echo "<td>" . Round($PlayerRosterAverage['AvgOfOverall']) . "</td>";
	echo "<td>" . Round($PlayerRosterAverage['AvgOfAge']). "</td>";
	echo "<td>" . Round($PlayerRosterAverage['AvgOfContract']). "</td>";
	echo "<td>" . number_Format(Round($PlayerRosterAverage['AvgOfSalary1']),0). "$</td></tr>\n";	
}}
?>
</tbody></table><div class="STHSBlankDiv"></div>
<h1><?php echo $TypeText . " " . $TeamLang['GoaliesAverage'];?></h1>
<table class="STHSPHPTeam_AverageGoaliesRosterTable tablesorter"><thead><tr>
<th data-priority="1" title="Order Number" class="STHSW10 sorter-false">#</th>
<th data-priority="critical" title="Goalie Name" class="STHSW140"><?php echo $PlayersLang['TeamName'];?></th>
<th data-priority="2" title="Skating" class="STHSW25">SK</th>
<th data-priority="2" title="Durability" class="STHSW25">DU</th>
<th data-priority="2" title="Endurance" class="STHSW25">EN</th>
<th data-priority="2" title="Size" class="STHSW25">SZ</th>
<th data-priority="2" title="Agility" class="STHSW25">AG</th>
<th data-priority="2" title="Rebound Control" class="STHSW25">RB</th>
<th data-priority="2" title="Style Control" class="STHSW25">SC</th>
<th data-priority="2" title="Hand Speed" class="STHSW25">HS</th>
<th data-priority="2" title="Reaction Time" class="STHSW25">RT</th>
<th data-priority="2" title="Puck Handling" class="STHSW25">PH</th>
<th data-priority="2" title="Penalty Shot" class="STHSW25">PS</th>
<th data-priority="2" title="Experience" class="STHSW25">EX</th>
<th data-priority="2" title="Leadership" class="STHSW25">LD</th>
<th data-priority="3" title="Potential" class="STHSW25">PO</th>
<th data-priority="3" title="Morale" class="STHSW25">MO</th>
<th data-priority="critical" title="Overall" class="STHSW25">OV</th>
<?php
echo "<th data-priority=\"5\" class=\"STHSW25\" title=\"Age\">" . $PlayersLang['Age'] . "</th>";
echo "<th data-priority=\"5\" class=\"STHSW25\" title=\"Contract\">" . $PlayersLang['Contract'] . "</th>";
echo "<th data-priority=\"5\" class=\"STHSW65\" title=\"Salary\">" . $PlayersLang['Salary'] ."</th>";
echo "</tr></thead><tbody>";
$Order = 0;
if (empty($Team) == false){while ($Row = $Team ->fetchArray()) { 
	$Query = "SELECT GoalerInfo.Team, GoalerInfo.Status1, Avg(GoalerInfo.ConditionDecimal) AS AvgOfConditionDecimal, Avg(GoalerInfo.SK) AS AvgOfSK, Avg(GoalerInfo.DU) AS AvgOfDU, Avg(GoalerInfo.EN) AS AvgOfEN, Avg(GoalerInfo.SZ) AS AvgOfSZ, Avg(GoalerInfo.AG) AS AvgOfAG, Avg(GoalerInfo.RB) AS AvgOfRB, Avg(GoalerInfo.SC) AS AvgOfSC, Avg(GoalerInfo.HS) AS AvgOfHS, Avg(GoalerInfo.RT) AS AvgOfRT, Avg(GoalerInfo.PH) AS AvgOfPH, Avg(GoalerInfo.PS) AS AvgOfPS, Avg(GoalerInfo.EX) AS AvgOfEX, Avg(GoalerInfo.LD) AS AvgOfLD, Avg(GoalerInfo.PO) AS AvgOfPO, Avg(GoalerInfo.MO) AS AvgOfMO, Avg(GoalerInfo.Overall) AS AvgOfOverall, Avg(GoalerInfo.Age) AS AvgOfAge, Avg(GoalerInfo.Contract) AS AvgOfContract, Avg(GoalerInfo.Salary1) AS AvgOfSalary1 FROM GoalerInfo WHERE Team = " . $Row['Number'];
	If ($TypeText  == "Pro"){$Query = $Query . " AND Status1 >= 2";}elseif ($TypeText  == "Farm"){$Query = $Query . " AND Status1 <= 1";}
	$GoalieRosterAverage = $db->querySingle($Query,True);
	$Order +=1;
	echo "<tr><td>" . $Order ."</td><td>";	
	If ($Row['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPTeamRosterAverageTeamImage\">";}			
	echo "<a href=\"" . $TypeText . "Team.php?Team=" . $Row['Number'] . "\">" . $Row['Name'] . "</a></td>";	
	echo "<td>" . Round($GoalieRosterAverage['AvgOfSK']). "</td>";
	echo "<td>" . Round($GoalieRosterAverage['AvgOfDU']). "</td>";
	echo "<td>" . Round($GoalieRosterAverage['AvgOfEN']). "</td>";
	echo "<td>" . Round($GoalieRosterAverage['AvgOfSZ']). "</td>";
	echo "<td>" . Round($GoalieRosterAverage['AvgOfAG']). "</td>";
	echo "<td>" . Round($GoalieRosterAverage['AvgOfRB']). "</td>";
	echo "<td>" . Round($GoalieRosterAverage['AvgOfSC']). "</td>";
	echo "<td>" . Round($GoalieRosterAverage['AvgOfHS']). "</td>";
	echo "<td>" . Round($GoalieRosterAverage['AvgOfRT']). "</td>";
	echo "<td>" . Round($GoalieRosterAverage['AvgOfPH']). "</td>";
	echo "<td>" . Round($GoalieRosterAverage['AvgOfPS']). "</td>";
	echo "<td>" . Round($GoalieRosterAverage['AvgOfEX']). "</td>";
	echo "<td>" . Round($GoalieRosterAverage['AvgOfLD']). "</td>";
	echo "<td>" . Round($GoalieRosterAverage['AvgOfPO']). "</td>";
	echo "<td>" . Round($GoalieRosterAverage['AvgOfMO']). "</td>";
	echo "<td>" . Round($GoalieRosterAverage['AvgOfOverall']). "</td>"; 
	echo "<td>" . Round($GoalieRosterAverage['AvgOfAge']). "</td>";
	echo "<td>" . Round($GoalieRosterAverage['AvgOfContract']). "</td>";
	echo "<td>" . number_Format(Round($GoalieRosterAverage['AvgOfSalary1']),0). "$</td></tr>\n";	
	
}}
?>
</tbody></table>

</div>
<?php include "Footer.php";?>
