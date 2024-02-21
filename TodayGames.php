<?php include "Header.php";
If ($lang == "fr"){include 'LanguageFR-League.php';}else{include 'LanguageEN-League.php';}
$LeagueName = (string)"";
$Title = (string)"";
If (file_exists($DatabaseFile) == false){
	Goto STHSErrorTodayGame;
}else{try{
	$db = new SQLite3($DatabaseFile);
	
	$Type = (integer)0; /* 0 = All / 1 = Pro / 2 = Farm */
	if(isset($_GET['Type'])){$Type = filter_var($_GET['Type'], FILTER_SANITIZE_NUMBER_INT);} 
	
	$Query = "Select Name, OutputName, DefaultSimulationPerDay, ScheduleNextDay, PointSystemSO, Today3StarPro, Today3StarFarm from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	
	$Query = "Select OutputGameHTMLToSQLiteDatabase from LeagueOutputOption";
	$LeagueOutputOption = $db->querySingle($Query,true);
	
	/* Pro Only, Farm Only or Both  */ 
	if($Type == 1){
		/* Pro Only */
		$Query = "SELECT TodayGame.* FROM TodayGame WHERE TodayGame.GameNumber Like 'Pro%'";
		$Title = $LeagueName . " - " . $ScheduleLang['TodayGamesTitle'] . $DynamicTitleLang['Pro'];
		$QuerySchedule = "SELECT SchedulePro.*, 'Pro' AS Type, TeamProStatVisitor.Last10W AS VLast10W, TeamProStatVisitor.Last10L AS VLast10L, TeamProStatVisitor.Last10T AS VLast10T, TeamProStatVisitor.Last10OTW AS VLast10OTW, TeamProStatVisitor.Last10OTL AS VLast10OTL, TeamProStatVisitor.Last10SOW AS VLast10SOW, TeamProStatVisitor.Last10SOL AS VLast10SOL, TeamProStatVisitor.GP AS VGP, TeamProStatVisitor.W AS VW, TeamProStatVisitor.L AS VL, TeamProStatVisitor.T AS VT, TeamProStatVisitor.OTW AS VOTW, TeamProStatVisitor.OTL AS VOTL, TeamProStatVisitor.SOW AS VSOW, TeamProStatVisitor.SOL AS VSOL, TeamProStatVisitor.Points AS VPoints, TeamProStatVisitor.Streak AS VStreak, TeamProStatHome.Last10W AS HLast10W, TeamProStatHome.Last10L AS HLast10L, TeamProStatHome.Last10T AS HLast10T, TeamProStatHome.Last10OTW AS HLast10OTW, TeamProStatHome.Last10OTL AS HLast10OTL, TeamProStatHome.Last10SOW AS HLast10SOW, TeamProStatHome.Last10SOL AS HLast10SOL, TeamProStatHome.GP AS HGP, TeamProStatHome.W AS HW, TeamProStatHome.L AS HL, TeamProStatHome.T AS HT, TeamProStatHome.OTW AS HOTW, TeamProStatHome.OTL AS HOTL, TeamProStatHome.SOW AS HSOW, TeamProStatHome.SOL AS HSOL, TeamProStatHome.Points AS HPoints, TeamProStatHome.Streak AS HStreak FROM (SchedulePRO LEFT JOIN TeamProStat AS TeamProStatHome ON SchedulePRO.HomeTeam = TeamProStatHome.Number) LEFT JOIN TeamProStat AS TeamProStatVisitor ON SchedulePRO.VisitorTeam = TeamProStatVisitor.Number WHERE DAY >= " . $LeagueGeneral['ScheduleNextDay'] . " AND DAY <= " . ($LeagueGeneral['ScheduleNextDay'] + $LeagueGeneral['DefaultSimulationPerDay'] -1) . " ORDER BY Day, GameNumber";
	}elseif($Type == 2){
		/* Farm Only */
		$Query = "SELECT TodayGame.* FROM TodayGame WHERE TodayGame.GameNumber Like 'Farm%'";
		$Title = $LeagueName . " - " . $ScheduleLang['TodayGamesTitle'] .  $DynamicTitleLang['Farm'];
		$QuerySchedule = "SELECT ScheduleFarm.*, 'Farm' AS Type, TeamFarmStatVisitor.Last10W AS VLast10W, TeamFarmStatVisitor.Last10L AS VLast10L, TeamFarmStatVisitor.Last10T AS VLast10T, TeamFarmStatVisitor.Last10OTW AS VLast10OTW, TeamFarmStatVisitor.Last10OTL AS VLast10OTL, TeamFarmStatVisitor.Last10SOW AS VLast10SOW, TeamFarmStatVisitor.Last10SOL AS VLast10SOL, TeamFarmStatVisitor.GP AS VGP, TeamFarmStatVisitor.W AS VW, TeamFarmStatVisitor.L AS VL, TeamFarmStatVisitor.T AS VT, TeamFarmStatVisitor.OTW AS VOTW, TeamFarmStatVisitor.OTL AS VOTL, TeamFarmStatVisitor.SOW AS VSOW, TeamFarmStatVisitor.SOL AS VSOL, TeamFarmStatVisitor.Points AS VPoints, TeamFarmStatVisitor.Streak AS VStreak, TeamFarmStatHome.Last10W AS HLast10W, TeamFarmStatHome.Last10L AS HLast10L, TeamFarmStatHome.Last10T AS HLast10T, TeamFarmStatHome.Last10OTW AS HLast10OTW, TeamFarmStatHome.Last10OTL AS HLast10OTL, TeamFarmStatHome.Last10SOW AS HLast10SOW, TeamFarmStatHome.Last10SOL AS HLast10SOL, TeamFarmStatHome.GP AS HGP, TeamFarmStatHome.W AS HW, TeamFarmStatHome.L AS HL, TeamFarmStatHome.T AS HT, TeamFarmStatHome.OTW AS HOTW, TeamFarmStatHome.OTL AS HOTL, TeamFarmStatHome.SOW AS HSOW, TeamFarmStatHome.SOL AS HSOL, TeamFarmStatHome.Points AS HPoints, TeamFarmStatHome.Streak AS HStreak FROM (ScheduleFarm LEFT JOIN TeamFarmStat AS TeamFarmStatHome ON ScheduleFarm.HomeTeam = TeamFarmStatHome.Number) LEFT JOIN TeamFarmStat AS TeamFarmStatVisitor ON ScheduleFarm.HomeTeam = TeamFarmStatVisitor.Number WHERE DAY >= " . $LeagueGeneral['ScheduleNextDay'] . " AND DAY <= " . ($LeagueGeneral['ScheduleNextDay'] + $LeagueGeneral['DefaultSimulationPerDay'] -1) . " ORDER BY Day, GameNumber";
	}else{
		/* Both */
		$Query = "SELECT TodayGame.*, substr(TodayGame.GameNumber,1,3) AS Type FROM TodayGame ORDER BY TYPE DESC, GameNumber";
		$Title = $LeagueName . " - " . $ScheduleLang['TodayGamesTitle'];
		$QuerySchedule = "Select ProSchedule.*, 'Pro' AS Type FROM (SELECT TeamProStatVisitor.Last10W AS VLast10W, TeamProStatVisitor.Last10L AS VLast10L, TeamProStatVisitor.Last10T AS VLast10T, TeamProStatVisitor.Last10OTW AS VLast10OTW, TeamProStatVisitor.Last10OTL AS VLast10OTL, TeamProStatVisitor.Last10SOW AS VLast10SOW, TeamProStatVisitor.Last10SOL AS VLast10SOL, TeamProStatVisitor.GP AS VGP, TeamProStatVisitor.W AS VW, TeamProStatVisitor.L AS VL, TeamProStatVisitor.T AS VT, TeamProStatVisitor.OTW AS VOTW, TeamProStatVisitor.OTL AS VOTL, TeamProStatVisitor.SOW AS VSOW, TeamProStatVisitor.SOL AS VSOL, TeamProStatVisitor.Points AS VPoints, TeamProStatVisitor.Streak AS VStreak, TeamProStatHome.Last10W AS HLast10W, TeamProStatHome.Last10L AS HLast10L, TeamProStatHome.Last10T AS HLast10T, TeamProStatHome.Last10OTW AS HLast10OTW, TeamProStatHome.Last10OTL AS HLast10OTL, TeamProStatHome.Last10SOW AS HLast10SOW, TeamProStatHome.Last10SOL AS HLast10SOL, TeamProStatHome.GP AS HGP, TeamProStatHome.W AS HW, TeamProStatHome.L AS HL, TeamProStatHome.T AS HT, TeamProStatHome.OTW AS HOTW, TeamProStatHome.OTL AS HOTL, TeamProStatHome.SOW AS HSOW, TeamProStatHome.SOL AS HSOL, TeamProStatHome.Points AS HPoints, TeamProStatHome.Streak AS HStreak, SchedulePro.* FROM (SchedulePRO LEFT JOIN TeamProStat AS TeamProStatHome ON SchedulePRO.HomeTeam = TeamProStatHome.Number) LEFT JOIN TeamProStat AS TeamProStatVisitor ON SchedulePRO.VisitorTeam = TeamProStatVisitor.Number WHERE DAY >= " . $LeagueGeneral['ScheduleNextDay'] . " AND DAY <= " . ($LeagueGeneral['ScheduleNextDay'] + $LeagueGeneral['DefaultSimulationPerDay'] -1) . ") AS ProSchedule  UNION ALL Select FarmSchedule.*, 'Farm' AS Type FROM (SELECT TeamFarmStatVisitor.Last10W AS VLast10W, TeamFarmStatVisitor.Last10L AS VLast10L, TeamFarmStatVisitor.Last10T AS VLast10T, TeamFarmStatVisitor.Last10OTW AS VLast10OTW, TeamFarmStatVisitor.Last10OTL AS VLast10OTL, TeamFarmStatVisitor.Last10SOW AS VLast10SOW, TeamFarmStatVisitor.Last10SOL AS VLast10SOL, TeamFarmStatVisitor.GP AS VGP, TeamFarmStatVisitor.W AS VW, TeamFarmStatVisitor.L AS VL, TeamFarmStatVisitor.T AS VT, TeamFarmStatVisitor.OTW AS VOTW, TeamFarmStatVisitor.OTL AS VOTL, TeamFarmStatVisitor.SOW AS VSOW, TeamFarmStatVisitor.SOL AS VSOL, TeamFarmStatVisitor.Points AS VPoints, TeamFarmStatVisitor.Streak AS VStreak, TeamFarmStatHome.Last10W AS HLast10W, TeamFarmStatHome.Last10L AS HLast10L, TeamFarmStatHome.Last10T AS HLast10T, TeamFarmStatHome.Last10OTW AS HLast10OTW, TeamFarmStatHome.Last10OTL AS HLast10OTL, TeamFarmStatHome.Last10SOW AS HLast10SOW, TeamFarmStatHome.Last10SOL AS HLast10SOL, TeamFarmStatHome.GP AS HGP, TeamFarmStatHome.W AS HW, TeamFarmStatHome.L AS HL, TeamFarmStatHome.T AS HT, TeamFarmStatHome.OTW AS HOTW, TeamFarmStatHome.OTL AS HOTL, TeamFarmStatHome.SOW AS HSOW, TeamFarmStatHome.SOL AS HSOL, TeamFarmStatHome.Points AS HPoints, TeamFarmStatHome.Streak AS HStreak, ScheduleFarm.* FROM (ScheduleFarm LEFT JOIN TeamFarmStat AS TeamFarmStatHome ON ScheduleFarm.HomeTeam = TeamFarmStatHome.Number) LEFT JOIN TeamFarmStat AS TeamFarmStatVisitor ON ScheduleFarm.VisitorTeam = TeamFarmStatVisitor.Number WHERE DAY >= " . $LeagueGeneral['ScheduleNextDay'] . " AND DAY <= " . ($LeagueGeneral['ScheduleNextDay'] + $LeagueGeneral['DefaultSimulationPerDay'] -1) . ") AS FarmSchedule ORDER BY Day, Type DESC, GameNumber";
	}
	$TodayGame = $db->query($Query);
	$Schedule = $db->query($QuerySchedule);
	
	$Query = "SELECT Count(TodayGame.GameNumber) AS GameInTable FROM TodayGame";
	$TodayGameCount = $db->querySingle($Query,True);
} catch (Exception $e) {
STHSErrorTodayGame:	
	$LeagueName = $DatabaseNotFound;
	$TodayGame = Null;
	$LeagueGeneral = Null;
	$TodayGameCount = Null;
	$LeagueOutputOption = Null;
	echo "<title>" . $DatabaseNotFound . "</title>";
}}
echo "<title>" . $Title . "</title>";


Function PrintGames($Row, $ScheduleLang, $LeagueOutputOption, $ImagesCDNPath){
	echo "<div class=\"STHSTodayGame_GameOverall\"><table class=\"STHSTodayGame_GameTitle\"><tr><td class=\"STHSTodayGame_GameNumber\"><h3>";
	If (substr($Row['GameNumber'],0,3) == "Pro"){
		echo $ScheduleLang['ProGames'] . substr($Row['GameNumber'],3);
	}elseif (substr($Row['GameNumber'],0,4) == "Farm"){
		echo $ScheduleLang['FarmGames'] . substr($Row['GameNumber'],4);
	}else{
		echo $ScheduleLang['UnknownGames'];
	}
	If ($Row['Note'] != ""){echo " - " . $Row['Note'];}
	If ($LeagueOutputOption['OutputGameHTMLToSQLiteDatabase'] == "True"){
			If (substr($Row['GameNumber'],0,3) == "Pro"){
				echo "</h3></td><td class=\"STHSTodayGame_Boxscore\"><h3><a href=\"Boxscore.php?Game=" .  substr($Row['GameNumber'],3) ."\">" . $ScheduleLang['BoxScore'] .  "</a></h3></td>";
			}elseif(substr($Row['GameNumber'],0,4) == "Farm"){
				echo "</h3></td><td class=\"STHSTodayGame_Boxscore\"><h3><a href=\"Boxscore.php?Game=" .  substr($Row['GameNumber'],4) ."&Farm\">" . $ScheduleLang['BoxScore'] .  "</a></h3></td>";
			}else{
				echo "</h3></td><td class=\"STHSTodayGame_Boxscore\"></td>";
			}
	}else{
		echo "</h3></td><td class=\"STHSTodayGame_Boxscore\"><h3><a href=\"" . $Row['Link'] ."\">" . $ScheduleLang['BoxScore'] .  "</a></h3></td>";
	}
	echo "</tr></table>";
	echo "<table class=\"STHSTodayGame_GameData\"><tr>";
	echo "<td class=\"STHSTodayGame_TeamName\"><h3>";
	If ($Row['VisitorTeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Row['VisitorTeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPTodayGameTeamImage\" />";}
	echo $Row['VisitorTeam'] ."</h3></td>";
	echo "<td class=\"STHSTodayGame_TeamScore\"><h3>";
	If ($Row['VisitorTeamScore'] > $Row['HomeTeamScore']){echo "<span style=\"color:red;font-weight:bold;\">" . $Row['VisitorTeamScore'] ."</span>";}else{echo $Row['VisitorTeamScore'];}
	echo "</h3></td></tr><tr>";
	echo "<td colspan=\"2\" class=\"STHSTodayGame_TeamNote\">" . $Row['VisitorTeamGoal'] ."<br /><br />" . $Row['VisitorTeamGoaler'] ."<br /></td>";
	echo "</tr><tr>";
	echo "<td class=\"STHSTodayGame_TeamName\"><h3>";
	If ($Row['HomeTeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Row['HomeTeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPTodayGameTeamImage\" />";}	
	echo $Row['HomeTeam'] ."</h3></td>";
	echo "<td class=\"STHSTodayGame_TeamScore\"><h3>";
	If ($Row['HomeTeamScore'] > $Row['VisitorTeamScore']){echo "<span style=\"color:red;font-weight:bold;\">" . $Row['HomeTeamScore'] ."</span>";}else{echo $Row['HomeTeamScore'];}
	echo "</h3></td></tr><tr>";
	echo "<td colspan=\"2\" class=\"STHSTodayGame_TeamNote\">" . $Row['HomeTeamGoal'] ."<br /><br />" . $Row['HomeTeamGoaler'] ."<br /></td>";
	echo "</tr><tr>";
	echo "<td colspan=\"2\" class=\"STHSTodayGame_3Star\"><br /><table style=\"width:300px;\">";
	echo "<tr><td style=\"text-align:right;width:75px;\"><img src=\"" . $ImagesCDNPath . "/images/Star1.png\" alt=\"Star1\" style=\"width:25px;vertical-align:middle;padding-right:4px\" /></td><td style=\"text-align:left;\">" . $Row['Star1'] . "</td></tr>";
	echo "<tr><td style=\"text-align:right;width:75px;\"><img src=\"" . $ImagesCDNPath . "/images/Star2.png\" alt=\"Star2\" style=\"width:25px;vertical-align:middle;padding-right:4px\" /></td><td style=\"text-align:left;\">" . $Row['Star2'] . "</td></tr>";
	echo "<tr><td style=\"text-align:right;width:75px;\"><img src=\"" . $ImagesCDNPath . "/images/Star3.png\" alt=\"Star3\" style=\"width:25px;vertical-align:middle;padding-right:4px\" /></td><td style=\"text-align:left;\">" . $Row['Star3'] . "</td></tr></table>";	
	echo "</td></tr></table></div>\n";
}
?>
<style>
.TodayGameDiv {
	-webkit-column-count: 3;
	-moz-column-count: 3;
	 column-count: 3;
	-webkit-column-width: 400px;
	-moz-column-width: 400px;
	column-width: 400px;
	width:99%;
	margin:auto;	
 } 
</style>
</head><body>
<?php include "Menu.php";?>
<br />


<div style="width:95%;margin:auto;">
<table class="STHSTableFullW"><tr><td><h1><?php echo $Title;?></h1></td><td class="STHSHeaderDate"><?php if(isset($LeagueGeneralMenu)){echo $ScheduleLang['LastUpdate'] . $LeagueGeneralMenu['DatabaseCreationDate'];}?></td></tr></table>
<div class="TodayGameDiv">
<?php
$LoopCount = (integer)0;
$BooFound = (boolean)False;
if (empty($TodayGame) == false){while ($Row = $TodayGame ->fetchArray()) {
	$LoopCount +=1;
	If ($Row['Type'] == "Far" AND $BooFound == False){
		echo "</div><br /><hr /><br /><div class=\"TodayGameDiv\">";
		$BooFound = True;
	}
	PrintGames($Row, $ScheduleLang,$LeagueOutputOption,$ImagesCDNPath);
}}
If ($LoopCount == 0){echo "<h3 class=\"STHSCenter\">" . $ScheduleLang['NoGameToday'] . "</h3>";}
?>
</div>
<br />

<h1><?php echo $ScheduleLang['NextGames'];?></h1>

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
	echo  $row['GameNumber'] . "</td><td>";
	If ($row['VisitorTeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $row['VisitorTeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPTodayGameTeamImage\" />";}
	echo "<a href=\"" . $row['Type']  . "Team.php?Team=" . $row['VisitorTeam'] . "\">" . $row['VisitorTeamName']. "</a> (" . ($row['VW'] + $row['VOTW'] + $row['VSOW']) . "-";
	if ($LeagueGeneral['PointSystemSO'] == "True"){
		echo $row['VL'] . "-" . ($row['VOTL'] + $row['VSOL']);
		echo ") -- " . $ScheduleLang['Last10Games'] . " : (" . ($row['VLast10W'] + $row['VLast10OTW'] + $row['VLast10SOW']) . "-" . $row['VLast10L'] . "-" . ($row['VLast10OTL'] + $row['VLast10SOL']) . ") - " . $row['VStreak'];
	}else{
		echo ($row['VL'] + $row['VOTL'] + $row['VSOL']) . "-" . $row['VT'];
		echo ") -- " . $ScheduleLang['Last10Games'] ." : (" . ($row['VLast10W'] + $row['VLast10OTW'] + $row['VLast10SOW']) . "-" . ($row['VLast10L'] + $row['VLast10OTL'] + $row['VLast10SOL']) . "-" . $row['VLast10T'] . ")";
	}
	echo "</td><td>";
	If ($row['HomeTeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $row['HomeTeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPTodayGameTeamImage\" />";}	
	echo "<a href=\"" . $row['Type'] . "Team.php?Team=" . $row['HomeTeam'] . "\">" . $row['HomeTeamName']. "</a> (" . ($row['HW'] + $row['HOTW'] + $row['HSOW']) . "-";
	if ($LeagueGeneral['PointSystemSO'] == "True"){
		echo $row['HL'] . "-" . ($row['HOTL'] + $row['HSOL']);
		echo ") -- " . $ScheduleLang['Last10Games'] . " : (" . ($row['HLast10W'] + $row['HLast10OTW'] + $row['HLast10SOW']) . "-" . $row['HLast10L'] . "-" . ($row['HLast10OTL'] + $row['HLast10SOL']) . ") - " . $row['HStreak'];
	}else{
		echo ($row['HL'] + $row['HOTL'] + $row['HSOL']) . "-" . $row['HT'];
		echo ") -- " . $ScheduleLang['Last10Games'] ." : (" . ($row['HLast10W'] + $row['HLast10OTW'] + $row['HLast10SOW']) . "-" . ($row['HLast10L'] + $row['HLast10OTL'] + $row['HLast10SOL']) . "-" . $row['HLast10T'] . ")";
	}
	echo "</td>";
	echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
}}
?>
</tbody></table>
</div>

<?php include "Footer.php";?>
