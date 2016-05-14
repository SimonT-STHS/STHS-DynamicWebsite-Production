<!DOCTYPE html>
<?php include "Header.php";?>
<?php
$LeagueName = (string)"";
$Title = (string)"";
If (file_exists($DatabaseFile) == false){
	$LeagueName = $DatabaseNotFound;
	$TodayGame = Null;
	echo "<title>" . $DatabaseNotFound . "</title>";
}else{
	$db = new SQLite3($DatabaseFile);
	
	$Type = (integer)0; /* 0 = All / 1 = Pro / 2 = Farm */
	if(isset($_GET['Type'])){$Type = filter_var($_GET['Type'], FILTER_SANITIZE_NUMBER_INT);} 
	
	$Query = "Select Name, OutputName, DefaultSimulationPerDay, ScheduleNextDay, PointSystemSO, Today3StarPro, Today3StarFarm from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	
	/* Pro Only, Farm Only or Both  */ 
	if($Type == 1){
		/* Pro Only */
		$Query = "SELECT TodayGame.* FROM TodayGame WHERE TodayGame.GameNumber Like 'Pro%'";
		$Title = $LeagueName . $TodayGamesLang['TodayGamesTitle'] . $DynamicTitleLang['Pro'];
		$QuerySchedule = "SELECT SchedulePro.*, 'Pro' AS Type, TeamProStatVisitor.Last10W AS VLast10W, TeamProStatVisitor.Last10L AS VLast10L, TeamProStatVisitor.Last10T AS VLast10T, TeamProStatVisitor.Last10OTW AS VLast10OTW, TeamProStatVisitor.Last10OTL AS VLast10OTL, TeamProStatVisitor.Last10SOW AS VLast10SOW, TeamProStatVisitor.Last10SOL AS VLast10SOL, TeamProStatVisitor.GP AS VGP, TeamProStatVisitor.W AS VW, TeamProStatVisitor.L AS VL, TeamProStatVisitor.T AS VT, TeamProStatVisitor.OTW AS VOTW, TeamProStatVisitor.OTL AS VOTL, TeamProStatVisitor.SOW AS VSOW, TeamProStatVisitor.SOL AS VSOL, TeamProStatVisitor.Points AS VPoints, TeamProStatVisitor.Streak AS VStreak, TeamProStatHome.Last10W AS HLast10W, TeamProStatHome.Last10L AS HLast10L, TeamProStatHome.Last10T AS HLast10T, TeamProStatHome.Last10OTW AS HLast10OTW, TeamProStatHome.Last10OTL AS HLast10OTL, TeamProStatHome.Last10SOW AS HLast10SOW, TeamProStatHome.Last10SOL AS HLast10SOL, TeamProStatHome.GP AS HGP, TeamProStatHome.W AS HW, TeamProStatHome.L AS HL, TeamProStatHome.T AS HT, TeamProStatHome.OTW AS HOTW, TeamProStatHome.OTL AS HOTL, TeamProStatHome.SOW AS HSOW, TeamProStatHome.SOL AS HSOL, TeamProStatHome.Points AS HPoints, TeamProStatHome.Streak AS HStreak FROM (SchedulePRO LEFT JOIN TeamProStat AS TeamProStatHome ON SchedulePRO.VisitorTeam = TeamProStatHome.Number) LEFT JOIN TeamProStat AS TeamProStatVisitor ON SchedulePRO.HomeTeam = TeamProStatVisitor.Number WHERE DAY >= " . $LeagueGeneral['ScheduleNextDay'] . " AND DAY <= " . ($LeagueGeneral['ScheduleNextDay'] + $LeagueGeneral['DefaultSimulationPerDay'] -1) . " ORDER BY Day, GameNumber";
	}elseif($Type == 2){
		/* Farm Only */
		$Query = "SELECT TodayGame.* FROM TodayGame WHERE TodayGame.GameNumber Like 'Farm%'";
		$Title = $LeagueName . $TodayGamesLang['TodayGamesTitle'] .  $DynamicTitleLang['Farm'];
		$QuerySchedule = "SELECT ScheduleFarm.*, 'Farm' AS Type, TeamFarmStatVisitor.Last10W AS VLast10W, TeamFarmStatVisitor.Last10L AS VLast10L, TeamFarmStatVisitor.Last10T AS VLast10T, TeamFarmStatVisitor.Last10OTW AS VLast10OTW, TeamFarmStatVisitor.Last10OTL AS VLast10OTL, TeamFarmStatVisitor.Last10SOW AS VLast10SOW, TeamFarmStatVisitor.Last10SOL AS VLast10SOL, TeamFarmStatVisitor.GP AS VGP, TeamFarmStatVisitor.W AS VW, TeamFarmStatVisitor.L AS VL, TeamFarmStatVisitor.T AS VT, TeamFarmStatVisitor.OTW AS VOTW, TeamFarmStatVisitor.OTL AS VOTL, TeamFarmStatVisitor.SOW AS VSOW, TeamFarmStatVisitor.SOL AS VSOL, TeamFarmStatVisitor.Points AS VPoints, TeamFarmStatVisitor.Streak AS VStreak, TeamFarmStatHome.Last10W AS HLast10W, TeamFarmStatHome.Last10L AS HLast10L, TeamFarmStatHome.Last10T AS HLast10T, TeamFarmStatHome.Last10OTW AS HLast10OTW, TeamFarmStatHome.Last10OTL AS HLast10OTL, TeamFarmStatHome.Last10SOW AS HLast10SOW, TeamFarmStatHome.Last10SOL AS HLast10SOL, TeamFarmStatHome.GP AS HGP, TeamFarmStatHome.W AS HW, TeamFarmStatHome.L AS HL, TeamFarmStatHome.T AS HT, TeamFarmStatHome.OTW AS HOTW, TeamFarmStatHome.OTL AS HOTL, TeamFarmStatHome.SOW AS HSOW, TeamFarmStatHome.SOL AS HSOL, TeamFarmStatHome.Points AS HPoints, TeamFarmStatHome.Streak AS HStreak FROM (ScheduleFarm LEFT JOIN TeamFarmStat AS TeamFarmStatHome ON ScheduleFarm.VisitorTeam = TeamFarmStatHome.Number) LEFT JOIN TeamFarmStat AS TeamFarmStatVisitor ON ScheduleFarm.HomeTeam = TeamFarmStatVisitor.Number WHERE DAY >= " . $LeagueGeneral['ScheduleNextDay'] . " AND DAY <= " . ($LeagueGeneral['ScheduleNextDay'] + $LeagueGeneral['DefaultSimulationPerDay'] -1) . " ORDER BY Day, GameNumber";
	}else{
		/* Both */
		$Query = "SELECT TodayGame.* FROM TodayGame";
		$Title = $LeagueName . $TodayGamesLang['TodayGamesTitle'];
		$QuerySchedule = "Select ProSchedule.*, 'Pro' AS Type FROM (SELECT TeamProStatVisitor.Last10W AS VLast10W, TeamProStatVisitor.Last10L AS VLast10L, TeamProStatVisitor.Last10T AS VLast10T, TeamProStatVisitor.Last10OTW AS VLast10OTW, TeamProStatVisitor.Last10OTL AS VLast10OTL, TeamProStatVisitor.Last10SOW AS VLast10SOW, TeamProStatVisitor.Last10SOL AS VLast10SOL, TeamProStatVisitor.GP AS VGP, TeamProStatVisitor.W AS VW, TeamProStatVisitor.L AS VL, TeamProStatVisitor.T AS VT, TeamProStatVisitor.OTW AS VOTW, TeamProStatVisitor.OTL AS VOTL, TeamProStatVisitor.SOW AS VSOW, TeamProStatVisitor.SOL AS VSOL, TeamProStatVisitor.Points AS VPoints, TeamProStatVisitor.Streak AS VStreak, TeamProStatHome.Last10W AS HLast10W, TeamProStatHome.Last10L AS HLast10L, TeamProStatHome.Last10T AS HLast10T, TeamProStatHome.Last10OTW AS HLast10OTW, TeamProStatHome.Last10OTL AS HLast10OTL, TeamProStatHome.Last10SOW AS HLast10SOW, TeamProStatHome.Last10SOL AS HLast10SOL, TeamProStatHome.GP AS HGP, TeamProStatHome.W AS HW, TeamProStatHome.L AS HL, TeamProStatHome.T AS HT, TeamProStatHome.OTW AS HOTW, TeamProStatHome.OTL AS HOTL, TeamProStatHome.SOW AS HSOW, TeamProStatHome.SOL AS HSOL, TeamProStatHome.Points AS HPoints, TeamProStatHome.Streak AS HStreak, SchedulePro.* FROM (SchedulePRO LEFT JOIN TeamProStat AS TeamProStatHome ON SchedulePRO.VisitorTeam = TeamProStatHome.Number) LEFT JOIN TeamProStat AS TeamProStatVisitor ON SchedulePRO.HomeTeam = TeamProStatVisitor.Number WHERE DAY >= " . $LeagueGeneral['ScheduleNextDay'] . " AND DAY <= " . ($LeagueGeneral['ScheduleNextDay'] + $LeagueGeneral['DefaultSimulationPerDay'] -1) . ") AS ProSchedule  UNION ALL Select FarmSchedule.*, 'Farm' AS Type FROM (SELECT TeamFarmStatVisitor.Last10W AS VLast10W, TeamFarmStatVisitor.Last10L AS VLast10L, TeamFarmStatVisitor.Last10T AS VLast10T, TeamFarmStatVisitor.Last10OTW AS VLast10OTW, TeamFarmStatVisitor.Last10OTL AS VLast10OTL, TeamFarmStatVisitor.Last10SOW AS VLast10SOW, TeamFarmStatVisitor.Last10SOL AS VLast10SOL, TeamFarmStatVisitor.GP AS VGP, TeamFarmStatVisitor.W AS VW, TeamFarmStatVisitor.L AS VL, TeamFarmStatVisitor.T AS VT, TeamFarmStatVisitor.OTW AS VOTW, TeamFarmStatVisitor.OTL AS VOTL, TeamFarmStatVisitor.SOW AS VSOW, TeamFarmStatVisitor.SOL AS VSOL, TeamFarmStatVisitor.Points AS VPoints, TeamFarmStatVisitor.Streak AS VStreak, TeamFarmStatHome.Last10W AS HLast10W, TeamFarmStatHome.Last10L AS HLast10L, TeamFarmStatHome.Last10T AS HLast10T, TeamFarmStatHome.Last10OTW AS HLast10OTW, TeamFarmStatHome.Last10OTL AS HLast10OTL, TeamFarmStatHome.Last10SOW AS HLast10SOW, TeamFarmStatHome.Last10SOL AS HLast10SOL, TeamFarmStatHome.GP AS HGP, TeamFarmStatHome.W AS HW, TeamFarmStatHome.L AS HL, TeamFarmStatHome.T AS HT, TeamFarmStatHome.OTW AS HOTW, TeamFarmStatHome.OTL AS HOTL, TeamFarmStatHome.SOW AS HSOW, TeamFarmStatHome.SOL AS HSOL, TeamFarmStatHome.Points AS HPoints, TeamFarmStatHome.Streak AS HStreak, ScheduleFarm.* FROM (ScheduleFarm LEFT JOIN TeamFarmStat AS TeamFarmStatHome ON ScheduleFarm.VisitorTeam = TeamFarmStatHome.Number) LEFT JOIN TeamFarmStat AS TeamFarmStatVisitor ON ScheduleFarm.HomeTeam = TeamFarmStatVisitor.Number WHERE DAY >= " . $LeagueGeneral['ScheduleNextDay'] . " AND DAY <= " . ($LeagueGeneral['ScheduleNextDay'] + $LeagueGeneral['DefaultSimulationPerDay'] -1) . ") AS FarmSchedule ORDER BY Day, Type DESC, GameNumber";
	}
	$TodayGame = $db->query($Query);
	$Schedule = $db->query($QuerySchedule);
}
echo "<title>" . $Title . "</title>";


Function PrintGames($Row, $TodayGamesLang){
	echo "<table class=\"STHSTodayGame_GameTitle\"><tr><td class=\"STHSTodayGame_GameNumber\"><h3>";
	If (substr($Row['GameNumber'],0,3) == "Pro"){
		echo $TodayGamesLang['ProGames'] . substr($Row['GameNumber'],3);
	}elseif (substr($Row['GameNumber'],0,4) == "Farm"){
		echo $TodayGamesLang['FarmGames'] . substr($Row['GameNumber'],4);
	}else{
		echo $TodayGamesLang['UnknownGames'];
	}
	echo "</h3></td><td class=\"STHSTodayGame_Boxscore\"><h3><a href=\"" . $Row['Link'] ."\">" . $TodayGamesLang['BoxScore'] .  "</a></h3></td>";
	echo "</tr></table>";
	echo "<table class=\"STHSTodayGame_GameData\"><tr>";
	echo "<td class=\"STHSTodayGame_TeamName\"><h3>" . $Row['VisitorTeam'] ."</h3></td>";
	echo "<td class=\"STHSTodayGame_TeamScore\"><h3>";
	If ($Row['VisitorTeamScore'] > $Row['HomeTeamScore']){echo "<span style=\"color:red\">" . $Row['VisitorTeamScore'] ."</span>";}else{echo $Row['VisitorTeamScore'];}
	echo "</h3></td></tr><tr>";
	echo "<td colspan=\"2\" class=\"STHSTodayGame_TeamNote\">" . $Row['VisitorTeamGoal'] ."<br /><br />" . $Row['VisitorTeamGoaler'] ."<br /><br /></td>";
	echo "</tr><tr>";
	echo "<td class=\"STHSTodayGame_TeamName\"><h3>" . $Row['HomeTeam'] ."</h3></td>";
	echo "<td class=\"STHSTodayGame_TeamScore\"><h3>";
	If ($Row['HomeTeamScore'] > $Row['VisitorTeamScore']){echo "<span style=\"color:red\">" . $Row['HomeTeamScore'] ."</span>";}else{echo $Row['HomeTeamScore'];}
	echo "</h3></td></tr><tr>";
	echo "<td colspan=\"2\" class=\"STHSTodayGame_TeamNote\">" . $Row['HomeTeamGoal'] ."<br /><br />" . $Row['HomeTeamGoaler'] ."<br /><br /></td>";
	echo "</tr></table>\n";
}
?>
</head><body>
<?php include "Menu.php";?>
<br />


<div style="width:95%;margin:auto;">
<h1><?php echo $Title;?></h1>
<h3 class="STHSTodayGame_Today3Star"><?php echo $TodayGamesLang['Today3Star'];
If ($LeagueGeneral['Today3StarPro'] != "" AND $Type != 2 ){echo "<br />" . $TodayGamesLang['ProGames'] . ": " . $LeagueGeneral['Today3StarPro'];}
If ($LeagueGeneral['Today3StarFarm'] != "" AND $Type != 1){echo "<br />" . $TodayGamesLang['FarmGames'] . ": " . $LeagueGeneral['Today3StarFarm'];}
?></h3>
<table class="STHSTodayGame_MainTable">
<?php
$LoopCount = (integer)0;
if (empty($TodayGame) == false){while ($Row = $TodayGame ->fetchArray()) {
	$LoopCount +=1;
	If ($LoopCount % 2 == 1){
		echo "<tr><td class=\"STHSTodayGame_GameOverall\">\n";
		PrintGames($Row, $TodayGamesLang);
		echo "<hr class=\"STHSTodayGame_HR\"><br /></td>\n";
	}else{
		echo "<td class=\"STHSTodayGame_GameOverall\">\n";
		PrintGames($Row, $TodayGamesLang);
        echo "<hr class=\"STHSTodayGame_HR\"><br /></td></tr>\n";
	}
}}
If ($LoopCount % 2 == 0){
	echo "</table>";
}else{
	echo "<td></td></tr></table>";
}

?>
<br />



<h1><?php echo $TodayGamesLang['NextGames'];?></h1>

<table class="tablesorter STHSPHPSchedule_ScheduleTable"><thead><tr>
<th title="Day" class="STHSW45"><?php echo $ScheduleLang['Day'];?></th>
<th title="Game Number" class="STHSW35"><?php echo $ScheduleLang['Game'];?></th>
<th title="Visitor Team" class="STHSW200"><?php echo $ScheduleLang['VisitorTeam'];?></th>
<th title="Home Team" class="STHSW200"><?php echo $ScheduleLang['HomeTeam'];?></th>
</tr></thead><tbody>
<?php
$TradeDeadLine = (boolean)False;
if (empty($Schedule) == false){while ($row = $Schedule ->fetchArray()) {
	echo "<tr><td>" . $row['Day']. "</td><td>";
	if($Type == 0){echo $row['Type'] . " - ";}
	echo  $row['GameNumber'] . "</td>";
	echo "<td><a href=\"" . $row['Type'] . "Team.php?Team=" . $row['VisitorTeam'] . "\">" . $row['VisitorTeamName']. "</a> (" . ($row['HW'] + $row['HOTW'] + $row['HSOW']) . "-";
	if ($LeagueGeneral['PointSystemSO'] == "True"){
		echo $row['HL'] . "-" . ($row['HOTL'] + $row['HSOL']);
		echo ") -- Last 10 Games : (" . ($row['HLast10W'] + $row['HLast10OTW'] + $row['HLast10SOW']) . "-" . $row['HLast10L'] . "-" . ($row['HLast10OTL'] + $row['HLast10SOL']) . ") - " . $row['HStreak'];
	}else{
		echo ($row['HL'] + $row['HOTL'] + $row['HSOL']) . "-" . $row['HT'];
		echo ") -- " . $TeamLang['Last10Games'] ." : (" . ($row['HLast10W'] + $row['HLast10OTW'] + $row['HLast10SOW']) . "-" . ($row['HLast10L'] + $row['HLast10OTL'] + $row['HLast10SOL']) . "-" . $row['HLast10T'] . ")";
	}
	echo "</td>";
	echo "<td><a href=\"" . $row['Type']  . "Team.php?Team=" . $row['VisitorTeam'] . "\">" . $row['VisitorTeamName']. "</a> (" . ($row['VW'] + $row['VOTW'] + $row['VSOW']) . "-";
	if ($LeagueGeneral['PointSystemSO'] == "True"){
		echo $row['VL'] . "-" . ($row['VOTL'] + $row['VSOL']);
		echo ") -- Last 10 Games : (" . ($row['VLast10W'] + $row['VLast10OTW'] + $row['VLast10SOW']) . "-" . $row['VLast10L'] . "-" . ($row['VLast10OTL'] + $row['VLast10SOL']) . ") - " . $row['VStreak'];
	}else{
		echo ($row['VL'] + $row['VOTL'] + $row['VSOL']) . "-" . $row['VT'];
		echo ") -- " . $TeamLang['Last10Games'] ." : (" . ($row['VLast10W'] + $row['VLast10OTW'] + $row['VLast10SOW']) . "-" . ($row['VLast10L'] + $row['VLast10OTL'] + $row['VLast10SOL']) . "-" . $row['VLast10T'] . ")";
	}
	echo "</td>";
	echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
}}
?>
</tbody></table>

</div>

<?php include "Footer.php";?>
