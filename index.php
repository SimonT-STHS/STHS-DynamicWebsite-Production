<?php 
If (file_exists("Header.php") == true){
	include "Header.php";
}else{
	$DatabaseFile = "";
	$ImagesCDNPath = (string)".";
	$CSSJSCDNPath = (string)"";
	$STHSIntegratedHosting = (bool)False;
	$IndexLang = array();
	echo "<!DOCTYPE html><html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\"><head>\n";
}
If ($lang == "fr"){include 'LanguageFR-Main.php';}else{include 'LanguageEN-Main.php';}
$IndexQueryOK = (bool)False;
$IndexBotProtectionEnable = (bool)False;
$InformationMessage = (string)"";
If (file_exists($DatabaseFile) == false){
	Goto STHSErrorIndex;
}else{
	$LeagueName = (string)"";
try{
	$db = new SQLite3($DatabaseFile);
	
	$Query = "Select Name, ScheduleNextDay, IndexHeadLineDay0, IndexHeadLineDay1 ,IndexHeadLineDay2, DefaultSimulationPerDay, PointSystemSO, OffSeason, Days73StarPro, Days303StarPro, Days73StarFarm, Days303StarFarm, Today3StarPro1, Today3StarPro2, Today3StarPro3, Today3StarFarm1, Today3StarFarm2, Today3StarFarm3, Days73StarPro1, Days73StarPro2, Days73StarPro3, Days73StarFarm1, Days73StarFarm2, Days73StarFarm3,Days303StarPro1, Days303StarPro2, Days303StarPro3, Days303StarFarm1, Days303StarFarm2, Days303StarFarm3 from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	
	$Query = "Select PlayersMugShotBaseURL, PlayersMugShotFileExtension, ProMinimumGamePlayerLeader, ShowFarmScoreinPHPHomePage, NumberofNewsinPHPHomePage, NumberofLatestScoreinPHPHomePage from LeagueOutputOption";
	$LeagueOutputOption = $db->querySingle($Query,true);			
	
	if ($CookieTeamNumber == 0 AND $STHSBotProtectionLevel2 == True){
		$Headlines0 = Null;
		$Transaction0 = Null;
		$Headlines1 = Null;
		$Transaction1 = Null;
		$Transaction2 = Null;
		$LatestScore = Null;
		$Schedule = Null;
		$IndexBotProtectionEnable = True;
		$InformationMessage = $BotProtectionEnableMessage;
	}else{
		$Query = "SELECT LeagueLog.* FROM LeagueLog WHERE (Datetxt = '" . $LeagueGeneral['IndexHeadLineDay0'] . "') AND ((LeagueLog.TransactionType = 2) OR (LeagueLog.TransactionType = 3) OR (LeagueLog.TransactionType = 6)) ORDER BY LeagueLog.Number ";
		$Headlines0 = $db->query($Query);
		$Query = "SELECT TradeLog.* FROM TradeLog WHERE (Datetxt = '" . $LeagueGeneral['IndexHeadLineDay0'] . "') ORDER BY TradeLog.Number";
		$Transaction0 = $db->query($Query);
		$Query = "SELECT LeagueLog.* FROM LeagueLog WHERE (Datetxt = '" . $LeagueGeneral['IndexHeadLineDay1'] . "') AND ((LeagueLog.TransactionType = 2) OR (LeagueLog.TransactionType = 3) OR (LeagueLog.TransactionType = 6)) ORDER BY LeagueLog.Number ";
		$Headlines1 = $db->query($Query);
		$Query = "SELECT TradeLog.* FROM TradeLog WHERE (Datetxt = '" . $LeagueGeneral['IndexHeadLineDay1'] . "') ORDER BY TradeLog.Number";
		$Transaction1 = $db->query($Query);
		$Query = "SELECT LeagueLog.* FROM LeagueLog WHERE (Datetxt = '" . $LeagueGeneral['IndexHeadLineDay2'] . "') AND ((LeagueLog.TransactionType = 2) OR (LeagueLog.TransactionType = 3) OR (LeagueLog.TransactionType = 6)) ORDER BY LeagueLog.Number ";
		$Headlines2 = $db->query($Query);
		$Query = "SELECT TradeLog.* FROM TradeLog WHERE (Datetxt = '" . $LeagueGeneral['IndexHeadLineDay2'] . "') ORDER BY TradeLog.Number";
		$Transaction2 = $db->query($Query);
		
		If ($LeagueOutputOption['ShowFarmScoreinPHPHomePage'] == 'True'){
			$Query = "SELECT *,'Pro' as Type FROM SchedulePro WHERE Day >= " . ($LeagueGeneral['ScheduleNextDay'] - $LeagueGeneral['DefaultSimulationPerDay']) . " AND PLAY = 'True' UNION SELECT *,'Farm' as Type FROM ScheduleFarm WHERE Day = " . ($LeagueGeneral['ScheduleNextDay'] - $LeagueGeneral['DefaultSimulationPerDay']) . " AND PLAY = 'True' ORDER BY TYPE DESC, GAMENUMBER";
			$QuerySchedule = "Select ProSchedule.*, 'Pro' AS Type FROM (SELECT TeamProStatVisitor.Last10W AS VLast10W, TeamProStatVisitor.Last10L AS VLast10L, TeamProStatVisitor.Last10T AS VLast10T, TeamProStatVisitor.Last10OTW AS VLast10OTW, TeamProStatVisitor.Last10OTL AS VLast10OTL, TeamProStatVisitor.Last10SOW AS VLast10SOW, TeamProStatVisitor.Last10SOL AS VLast10SOL, TeamProStatVisitor.GP AS VGP, TeamProStatVisitor.W AS VW, TeamProStatVisitor.L AS VL, TeamProStatVisitor.T AS VT, TeamProStatVisitor.OTW AS VOTW, TeamProStatVisitor.OTL AS VOTL, TeamProStatVisitor.SOW AS VSOW, TeamProStatVisitor.SOL AS VSOL, TeamProStatVisitor.Points AS VPoints, TeamProStatVisitor.Streak AS VStreak, TeamProStatHome.Last10W AS HLast10W, TeamProStatHome.Last10L AS HLast10L, TeamProStatHome.Last10T AS HLast10T, TeamProStatHome.Last10OTW AS HLast10OTW, TeamProStatHome.Last10OTL AS HLast10OTL, TeamProStatHome.Last10SOW AS HLast10SOW, TeamProStatHome.Last10SOL AS HLast10SOL, TeamProStatHome.GP AS HGP, TeamProStatHome.W AS HW, TeamProStatHome.L AS HL, TeamProStatHome.T AS HT, TeamProStatHome.OTW AS HOTW, TeamProStatHome.OTL AS HOTL, TeamProStatHome.SOW AS HSOW, TeamProStatHome.SOL AS HSOL, TeamProStatHome.Points AS HPoints, TeamProStatHome.Streak AS HStreak, SchedulePro.* FROM (SchedulePRO LEFT JOIN TeamProStat AS TeamProStatHome ON SchedulePRO.HomeTeam = TeamProStatHome.Number) LEFT JOIN TeamProStat AS TeamProStatVisitor ON SchedulePRO.VisitorTeam = TeamProStatVisitor.Number WHERE DAY >= " . $LeagueGeneral['ScheduleNextDay'] . " AND DAY <= " . ($LeagueGeneral['ScheduleNextDay'] + $LeagueGeneral['DefaultSimulationPerDay'] -1) . ") AS ProSchedule  UNION ALL Select FarmSchedule.*, 'Farm' AS Type FROM (SELECT TeamFarmStatVisitor.Last10W AS VLast10W, TeamFarmStatVisitor.Last10L AS VLast10L, TeamFarmStatVisitor.Last10T AS VLast10T, TeamFarmStatVisitor.Last10OTW AS VLast10OTW, TeamFarmStatVisitor.Last10OTL AS VLast10OTL, TeamFarmStatVisitor.Last10SOW AS VLast10SOW, TeamFarmStatVisitor.Last10SOL AS VLast10SOL, TeamFarmStatVisitor.GP AS VGP, TeamFarmStatVisitor.W AS VW, TeamFarmStatVisitor.L AS VL, TeamFarmStatVisitor.T AS VT, TeamFarmStatVisitor.OTW AS VOTW, TeamFarmStatVisitor.OTL AS VOTL, TeamFarmStatVisitor.SOW AS VSOW, TeamFarmStatVisitor.SOL AS VSOL, TeamFarmStatVisitor.Points AS VPoints, TeamFarmStatVisitor.Streak AS VStreak, TeamFarmStatHome.Last10W AS HLast10W, TeamFarmStatHome.Last10L AS HLast10L, TeamFarmStatHome.Last10T AS HLast10T, TeamFarmStatHome.Last10OTW AS HLast10OTW, TeamFarmStatHome.Last10OTL AS HLast10OTL, TeamFarmStatHome.Last10SOW AS HLast10SOW, TeamFarmStatHome.Last10SOL AS HLast10SOL, TeamFarmStatHome.GP AS HGP, TeamFarmStatHome.W AS HW, TeamFarmStatHome.L AS HL, TeamFarmStatHome.T AS HT, TeamFarmStatHome.OTW AS HOTW, TeamFarmStatHome.OTL AS HOTL, TeamFarmStatHome.SOW AS HSOW, TeamFarmStatHome.SOL AS HSOL, TeamFarmStatHome.Points AS HPoints, TeamFarmStatHome.Streak AS HStreak, ScheduleFarm.* FROM (ScheduleFarm LEFT JOIN TeamFarmStat AS TeamFarmStatHome ON ScheduleFarm.HomeTeam = TeamFarmStatHome.Number) LEFT JOIN TeamFarmStat AS TeamFarmStatVisitor ON ScheduleFarm.VisitorTeam = TeamFarmStatVisitor.Number WHERE DAY >= " . $LeagueGeneral['ScheduleNextDay'] . " AND DAY <= " . ($LeagueGeneral['ScheduleNextDay'] + $LeagueGeneral['DefaultSimulationPerDay'] -1) . ") AS FarmSchedule ORDER BY Day, Type DESC, GameNumber";
		}else{
			$Query = "SELECT *,'Pro' as Type FROM SchedulePro WHERE Day >= " . ($LeagueGeneral['ScheduleNextDay'] - $LeagueGeneral['DefaultSimulationPerDay']) . " AND PLAY = 'True' ORDER BY GameNumber ";
			$QuerySchedule = "SELECT SchedulePro.*, 'Pro' AS Type, TeamProStatVisitor.Last10W AS VLast10W, TeamProStatVisitor.Last10L AS VLast10L, TeamProStatVisitor.Last10T AS VLast10T, TeamProStatVisitor.Last10OTW AS VLast10OTW, TeamProStatVisitor.Last10OTL AS VLast10OTL, TeamProStatVisitor.Last10SOW AS VLast10SOW, TeamProStatVisitor.Last10SOL AS VLast10SOL, TeamProStatVisitor.GP AS VGP, TeamProStatVisitor.W AS VW, TeamProStatVisitor.L AS VL, TeamProStatVisitor.T AS VT, TeamProStatVisitor.OTW AS VOTW, TeamProStatVisitor.OTL AS VOTL, TeamProStatVisitor.SOW AS VSOW, TeamProStatVisitor.SOL AS VSOL, TeamProStatVisitor.Points AS VPoints, TeamProStatVisitor.Streak AS VStreak, TeamProStatHome.Last10W AS HLast10W, TeamProStatHome.Last10L AS HLast10L, TeamProStatHome.Last10T AS HLast10T, TeamProStatHome.Last10OTW AS HLast10OTW, TeamProStatHome.Last10OTL AS HLast10OTL, TeamProStatHome.Last10SOW AS HLast10SOW, TeamProStatHome.Last10SOL AS HLast10SOL, TeamProStatHome.GP AS HGP, TeamProStatHome.W AS HW, TeamProStatHome.L AS HL, TeamProStatHome.T AS HT, TeamProStatHome.OTW AS HOTW, TeamProStatHome.OTL AS HOTL, TeamProStatHome.SOW AS HSOW, TeamProStatHome.SOL AS HSOL, TeamProStatHome.Points AS HPoints, TeamProStatHome.Streak AS HStreak FROM (SchedulePRO LEFT JOIN TeamProStat AS TeamProStatHome ON SchedulePRO.HomeTeam = TeamProStatHome.Number) LEFT JOIN TeamProStat AS TeamProStatVisitor ON SchedulePRO.VisitorTeam = TeamProStatVisitor.Number WHERE DAY >= " . $LeagueGeneral['ScheduleNextDay'] . " AND DAY <= " . ($LeagueGeneral['ScheduleNextDay'] + $LeagueGeneral['DefaultSimulationPerDay'] -1) . " ORDER BY Day, GameNumber";
		}
		$LatestScore = $db->query($Query);
		$Schedule = $db->query($QuerySchedule);		
	}

	echo "<title>" . $LeagueName . " - " . $IndexLang['IndexTitle'] . "</title>\n";
	echo "<style>";
	If ($IndexBotProtectionEnable == True){
		echo ".STHSIndex_Top5, .STHSIndex_Top5Table, .swiper-container, STHSIndex_Top20FreeAgents {display:none;}\n";
		echo ".STHSDivInformationMessage {font-size:18px;}\n";
	}else{
		If ($LeagueGeneral['OffSeason'] == "True"){
			echo ".STHSIndex_Top5Table {display:none;}\n";
			echo "@media screen and (max-width: 768px) {.STHSIndex_Top5 {display:none;}}\n";
			echo ".swiper-container{display:none;}\n";
		}else{
			echo ".STHSIndex_Top20FreeAgents {display:none;}\n";
			echo "@media screen and (max-width: 768px) {.STHSIndex_Top5 {display:none;}}\n";
			echo "@media screen and (max-width: 1300px) {.STHSIndex_Top5Table .Headshot {display:none;}}\n";
		}		
	}
	If ($CookieTeamWebsiteThemeID == 2){echo ".STHSIndex_3StarImage {filter: invert(100%);}\n";}
	echo "</style>\n";	
	$IndexQueryOK = True;
} catch (Exception $e) {
STHSErrorIndex:
	$LeagueName = $DatabaseNotFound;
	$Transaction = Null;
	$Schedule = Null;
	$LeagueGeneral = Null;
	$LeagueOutputOption = Null;
	echo "<title>" . $DatabaseNotFound . "</title>\n";
	echo "<style>.STHSIndex_Main{display:none;}</style>\n";
}}
try{
If ($IndexQueryOK== True){	
	If (file_exists($NewsDatabaseFile) == false){
		$LeagueNews = Null;
	}else{
		$dbNews = new SQLite3($NewsDatabaseFile);
		$Query = "Select LeagueNews.*, TeamProInfo.TeamThemeID, TeamProInfo.Name FROM LeagueNews LEFT JOIN TeamProInfo ON LeagueNews.TeamNumber = TeamProInfo.Number WHERE Remove = 'False' ORDER BY Time DESC";
		$dbNews -> query("ATTACH DATABASE '".realpath($DatabaseFile)."' AS CurrentDB");
		$LeagueNews = $dbNews->query($Query);
	}	
}} catch (Exception $e) {
$LeagueNews = Null;
}

$LoopCurrentDate = (string)"";
echo "<link href=\"" . $CSSJSCDNPath . "swiper-bundle.min.css\" rel=\"stylesheet\" type=\"text/css\">";
echo "<script src=\"" . $CSSJSCDNPath . "swiper-bundle.min.js\"></script>";
If ($STHSIntegratedHosting ==False){
	$result = checkRequiredFiles();
	if (!$result['success']) {
		echo "<div style=\"position:fixed;top:0;left:0;width:100%;max-height:30vh;background:#E74C3C;color:#fff;z-index:900;border-radius:20px;padding:10px;box-sizing:border-box;\">
			<div style=\"font-size:24px;font-weight:bold;margin-bottom:10px;\">The following required STHS Dynamic Website files are missing:</div>
			<div style=\"max-height:calc(20vh - 50px);overflow-y:auto;font-size:20px;line-height:1.5;text-align:center;\">'" . implode("', '", $result['missing']) . "'</div></div>";
		}
}
?>
<style>
	:root{
	  --swiper-navigation-sides-offset:-0.5rem;
	  --swiper-theme-color: #000000;
	}
	.swiper-container{
	  max-width:95%;
	  margin-inline: auto;
	  padding: 1rem;	
	  position:relative;
	  z-index: 0;
	}
	.swiper{
	  position:static;
	}
    .swiper-slide {
      text-align: center;
      font-size: 18px;
      display: flex;
      justify-content: center;
      align-items: center;
	  width: 25%;
    }
    .swiper-slide img { object-fit: cover;}
	.swiper-link {all: unset; display: flex; justify-content: center; align-items: center; width: 100%; height: 100%; text-decoration: none;cursor: pointer;}
	.swiper-link:visited {color:#000000;}
	@media screen and (max-width: 1200px) {.swiper-slide { width: 33.33%;font-size: 16px;}}
	@media screen and (max-width: 890px)  {.swiper-slide { width: 50%;font-size: 14px;}}
</style>
<script>function toggleDiv(divId) {$("#"+divId).toggle();}</script>
</head><body>
<?php If (file_exists("Menu.php") == true){ include "Menu.php";}
if ($InformationMessage != ""){echo "<div class=\"STHSDivInformationMessage\">" . $InformationMessage . "<br><br></div>";}?>

<div class="swiper-container">
<div class="swiper STHSIndex">
  <div class="swiper-wrapper">
<?php
if (empty($LatestScore) == false){while ($row = $LatestScore ->fetchArray()) {
	echo "<div class=\"swiper-slide\"><a href=\"" . $row['Link'] ."\" class=\"swiper-link\">";
	echo "<table class=\"STHSIndex_GamesResult\">";
	echo "<tr><th colspan=\"2\">" . $IndexLang['BoxScore'] . $row['Type'] . " #" .  $row['GameNumber']. "</th></tr>";
	If ($row['VisitorScore'] < $row['HomeScore']){echo "<tr class=\"STHSIndex_GamesResult-LosingTeam\"><td>";}else{echo "<tr class=\"STHSIndex_GamesResult-Winning-Team\"><td>";}
	If ($row['VisitorTeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $row['VisitorTeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPIndexTeamImage\">";}
	echo $row['VisitorTeamName']. "</td><td>" . $row['VisitorScore'] . "</td></tr>";
	If ($row['VisitorScore'] > $row['HomeScore']){echo "<tr class=\"STHSIndex_GamesResult-LosingTeam\"><td>";}else{echo "<tr class=\"STHSIndex_GamesResult-Winning-Team\"><td>";}
	If ($row['HomeTeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $row['HomeTeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPIndexTeamImage\">";}
	echo $row['HomeTeamName']. "</td><td>" . $row['HomeScore'] . "</td></tr>";
	echo "</table></a></div>\n";	
}}
if (empty($Schedule) == false){while ($row = $Schedule ->fetchArray()) {
	echo "<div class=\"swiper-slide\"><a href=\"" . $row['Link'] ."\" class=\"swiper-link\">";
	echo "<table class=\"STHSIndex_GamesResult\">";
	echo "<tr><th>" . $IndexLang['NextGames'] . " - " .  $row['Type'] . " " .  " - #" . $row['GameNumber']. "</th></tr>";
	echo "<tr><td>";
	If ($row['VisitorTeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $row['VisitorTeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPIndexTeamImage\">";}
	echo "<a href=\"" . $row['Type']  . "Team.php?Team=" . $row['VisitorTeam'] . "\">" . $row['VisitorTeamName']. "</a> (" . ($row['VW'] + $row['VOTW'] + $row['VSOW']) . "-";
	if ($LeagueGeneral['PointSystemSO'] == "True"){
		echo $row['VL'] . "-" . ($row['VOTL'] + $row['VSOL']);
	}else{
		echo ($row['VL'] + $row['VOTL'] + $row['VSOL']) . "-" . $row['VT'];
	}
	echo ") - " . $row['VStreak'] . "</td></tr>";
	echo "<tr><td>";
	If ($row['HomeTeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $row['HomeTeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPIndexTeamImage\">";}
	echo "<a href=\"" . $row['Type'] . "Team.php?Team=" . $row['HomeTeam'] . "\">" . $row['HomeTeamName']. "</a> (" . ($row['HW'] + $row['HOTW'] + $row['HSOW']) . "-";
	if ($LeagueGeneral['PointSystemSO'] == "True"){
		echo $row['HL'] . "-" . ($row['HOTL'] + $row['HSOL']);
	}else{
		echo ($row['HL'] + $row['HOTL'] + $row['HSOL']) . "-" . $row['HT'];
	}
	echo ") - " . $row['HStreak']. "</td></tr>";
	echo "</table></a></div>\n";
}}
?>
</div>
<div class="swiper-button-prev"></div>
<div class="swiper-button-next"></div>
</div>
</div>

<table class="STHSIndex_Main"><tr><td class="STHSIndex_Top5">
<div class="STHSIndex_Headline"><?php echo $IndexLang['TopHeadlines'];?></div>
<table class="STHSIndex_HeadlineTable">
<?php
$LoopCurrentDate = "";
$HeadlineFound = (bool)False;
If ($CookieTeamNumber > 0 AND $STHSIntegratedHosting == True){echo "<tr><th colspan=\"4\" class=\"STHSCenter\">" .  $IndexLang['NewFeature'] . "</th></tr>\n";}
if (empty($Headlines0) == false){while ($row = $Headlines0 ->fetchArray()) {
	$HeadlineFound = True;
	If ($LoopCurrentDate == ""){echo "<tr><th colspan=\"4\" class=\"STHSCenter\">" . $row['DateTxt'] . "</th></tr>\n";$LoopCurrentDate = $row['DateTxt'];}
	echo "<tr><td colspan=\"4\">" . $row['Text'] . "</td></tr>\n"; 
}}
if (empty($Transaction0) == false){while ($row = $Transaction0 ->fetchArray()) {
	$HeadlineFound = True;
	If ($LoopCurrentDate == ""){echo "<tr><th colspan=\"4\" class=\"STHSCenter\">" . $row['DateTxt'] . "</th></tr>\n";$LoopCurrentDate = $row['DateTxt'];}
	echo "<tr><td>";
	If ($row['SendingTeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $row['SendingTeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPTradeLogHistoryTeamImageIndex\" />";}else{echo $row['SendingTeamName'];}
	echo "</td><td><img src=\"" . $ImagesCDNPath . "/images/TradeArrow.png\" alt=\"Trade Arrow\" width=\"12\" height=\"12\"></td><td>";
	If ($row['ReceivingTeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $row['ReceivingTeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPTradeLogHistoryTeamImageIndex\" />";}else{echo $row['ReceivingTeamName'];}
	echo "</td><td style=\"text-align:left;padding-left:20px;\">" . $row['ReceivingTeamText'] . "</td></tr>\n";
}}

$LoopCurrentDate = "";
if (empty($Headlines1) == false){while ($row = $Headlines1 ->fetchArray()) { 
	$HeadlineFound = True;
	If ($LoopCurrentDate == ""){echo "<tr><th colspan=\"4\" class=\"STHSCenter\">" . $row['DateTxt'] . "</th></tr>\n";$LoopCurrentDate = $row['DateTxt'];}
	echo "<tr><td colspan=\"4\">" . $row['Text'] . "</td></tr>\n"; 
}}
if (empty($Transaction1) == false){while ($row = $Transaction1 ->fetchArray()) {
	$HeadlineFound = True;
	If ($LoopCurrentDate == ""){echo "<tr><th colspan=\"4\" class=\"STHSCenter\">" . $row['DateTxt'] . "</th></tr>\n";$LoopCurrentDate = $row['DateTxt'];}
	echo "<tr><td>";
	If ($row['SendingTeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $row['SendingTeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPTradeLogHistoryTeamImageIndex\" />";}else{echo $row['SendingTeamName'];}
	echo "</td><td><img src=\"" . $ImagesCDNPath . "/images/TradeArrow.png\" alt=\"Trade Arrow\" width=\"12\" height=\"12\"></td><td>";
	If ($row['ReceivingTeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $row['ReceivingTeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPTradeLogHistoryTeamImageIndex\" />";}else{echo $row['ReceivingTeamName'];}
	echo "</td><td style=\"text-align:left;padding-left:20px;\">" . $row['ReceivingTeamText'] . "</td></tr>\n";
}}

$LoopCurrentDate = "";
if (empty($Headlines2) == false){while ($row = $Headlines2 ->fetchArray()) { 
	$HeadlineFound = True;
	If ($LoopCurrentDate == ""){echo "<tr><th colspan=\"4\" class=\"STHSCenter\">" . $row['DateTxt'] . "</th></tr>\n";$LoopCurrentDate = $row['DateTxt'];}
	echo "<tr><td colspan=\"4\">" . $row['Text'] . "</td></tr>\n"; 
}}
if (empty($Transaction2) == false){while ($row = $Transaction2 ->fetchArray()) {
	$HeadlineFound = True;
	If ($LoopCurrentDate == ""){echo "<tr><th colspan=\"4\" class=\"STHSCenter\">" . $row['DateTxt'] . "</th></tr>\n";$LoopCurrentDate = $row['DateTxt'];}
	echo "<tr><td>";
	If ($row['SendingTeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $row['SendingTeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPTradeLogHistoryTeamImageIndex\" />";}else{echo $row['SendingTeamName'];}
	echo "</td><td><img src=\"" . $ImagesCDNPath . "/images/TradeArrow.png\" alt=\"Trade Arrow\" width=\"12\" height=\"12\"></td><td>";
	If ($row['ReceivingTeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $row['ReceivingTeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPTradeLogHistoryTeamImageIndex\" />";}else{echo $row['ReceivingTeamName'];}
	echo "</td><td style=\"text-align:left;padding-left:20px;\">" . $row['ReceivingTeamText'] . "</td></tr>\n";
}}
If ($HeadlineFound  == False){echo "<tr><th colspan=\"4\" class=\"STHSCenter\">" . $IndexLang['NoHeadlines'] . "</th></tr>\n";}
?>
</table>


<div class="STHSIndex_Top5TableImage"><img id="Top5" src="<?php echo $ImagesCDNPath;?>/images/top5.png" alt="Top 5" width="281" height="56"></div>
<table class="STHSIndex_Top5Table">
<?php 

if(isset($LeagueGeneral) AND $IndexBotProtectionEnable == False AND $IndexQueryOK == True){
	echo "<tr><th colspan=\"2\" class=\"STHSIndex_3StarNameHeader\">" . $IndexLang['ProGamesDaysStar'] . "</th></tr>";
	echo "<tr><td colspan=\"2\"><img src=\"" . $ImagesCDNPath . "/images/Star1.png\" alt=\"Star1\" class=\"STHSIndex_3StarImage\">" . $LeagueGeneral['Today3StarPro1'] . "</td></tr>";
	echo "<tr><td colspan=\"2\"><img src=\"" . $ImagesCDNPath . "/images/Star2.png\" alt=\"Star2\" class=\"STHSIndex_3StarImage\">" . $LeagueGeneral['Today3StarPro2'] . "</td></tr>";
	echo "<tr><td colspan=\"2\"><img src=\"" . $ImagesCDNPath . "/images/Star3.png\" alt=\"Star3\" class=\"STHSIndex_3StarImage\">" . $LeagueGeneral['Today3StarPro3'] . "</td></tr>";
	If ($LeagueOutputOption['ShowFarmScoreinPHPHomePage'] == 'True'){
		echo "<tr><th colspan=\"2\" class=\"STHSIndex_3StarNameHeader\">" . $IndexLang['FarmGamesDaysStar'] . "</th></tr>";
		echo "<tr><td colspan=\"2\"><img src=\"" . $ImagesCDNPath . "/images/Star1.png\" alt=\"Star1\" class=\"STHSIndex_3StarImage\">" . $LeagueGeneral['Today3StarFarm1'] . "</td></tr>";
		echo "<tr><td colspan=\"2\"><img src=\"" . $ImagesCDNPath . "/images/Star2.png\" alt=\"Star2\" class=\"STHSIndex_3StarImage\">" . $LeagueGeneral['Today3StarFarm2'] . "</td></tr>";
		echo "<tr><td colspan=\"2\"><img src=\"" . $ImagesCDNPath . "/images/Star3.png\" alt=\"Star3\" class=\"STHSIndex_3StarImage\">" . $LeagueGeneral['Today3StarFarm3'] . "</td></tr>";
	}
	echo "<tr><th colspan=\"2\" class=\"STHSTop5\">" . $IndexLang['Top5Point'] ."</th></tr>";
	echo "<tr><td class=\"STHSIndex_Top5PointNameHeader\">" . $PlayersLang['PlayerName'] . "</td><td class=\"STHSIndex_Top5PointResultHeader\">G-A-P</td></tr>";

	$Query = "SELECT PlayerProStat.G, PlayerProStat.A, PlayerProStat.P, PlayerProStat.GP, PlayerProStat.Name, PlayerProStat.Number, TeamProInfo.Abbre, TeamProInfo.TeamThemeID, PlayerInfo.NHLID  FROM (PlayerInfo INNER JOIN PlayerProStat ON PlayerInfo.Number = PlayerProStat.Number) LEFT JOIN TeamProInfo ON PlayerInfo.Team = TeamProInfo.Number WHERE (PlayerProStat.GP >= " . $LeagueOutputOption['ProMinimumGamePlayerLeader'] . ") AND (PlayerInfo.Team > 0) AND (PlayerProStat.P > 0) ORDER BY PlayerProStat.P DESC, PlayerProStat.G DESC, PlayerProStat.GP ASC LIMIT 5";
	$PlayerStat = $db->query($Query);

	$LoopCount = (int)0;
	if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
		$LoopCount +=1;echo "<tr><td>";
		If ($Row['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPIndividualLeadersTeamImage\">";}	echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " (" . $Row['Abbre'] . ")</a>";
		If($LoopCount == 1){If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $Row['NHLID'] != ""){
		echo "<div class=\"Headshot\"><img loading=\"lazy\" src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $Row['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $Row['Name']. "\" class=\"STHSPHPIndexLeadersHeadshot\" /></div>";}}
		echo "</td><td>" . $Row['G'] . "-" . $Row['A'] . "-" . $Row['P'] . "</td></tr>\n";
	}}

	echo "<tr><th colspan=\"2\" class=\"STHSTop5\">" . $IndexLang['Top5Goal'] . "</th></tr>";
	echo "<tr><td class=\"STHSIndex_Top5PointNameHeader\">" .  $PlayersLang['PlayerName'] . "</td><td class=\"STHSIndex_Top5PointResultHeader\">GP-G</td></tr>";

	$Query = "SELECT PlayerProStat.G, PlayerProStat.A, PlayerProStat.P, PlayerProStat.GP, PlayerProStat.Name, PlayerProStat.Number, TeamProInfo.Abbre, TeamProInfo.TeamThemeID, PlayerInfo.NHLID  FROM (PlayerInfo INNER JOIN PlayerProStat ON PlayerInfo.Number = PlayerProStat.Number) LEFT JOIN TeamProInfo ON PlayerInfo.Team = TeamProInfo.Number WHERE (PlayerProStat.GP >= " . $LeagueOutputOption['ProMinimumGamePlayerLeader'] . ") AND (PlayerInfo.Team > 0) AND (PlayerProStat.P > 0) ORDER BY PlayerProStat.G DESC, PlayerProStat.GP ASC LIMIT 5";
	$PlayerStat = $db->query($Query);

	$LoopCount = (int)0;
	if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
		$LoopCount +=1;echo "<tr><td>";
		If ($Row['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPIndividualLeadersTeamImage\">";}	echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " (" . $Row['Abbre'] . ")</a>";
		If($LoopCount == 1){If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $Row['NHLID'] != ""){
		echo "<div class=\"Headshot\"><img loading=\"lazy\" src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $Row['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $Row['Name']. "\" class=\"STHSPHPIndexLeadersHeadshot\" /></div>";}}
		echo "</td><td>" . $Row['GP'] . " - " . $Row['G'] . "</td></tr>\n";
	}}

	echo "<tr><th colspan=\"2\" class=\"STHSTop5\">" . $IndexLang['Top5Goalies'] . "</th></tr>";
	echo "<tr><td class=\"STHSIndex_Top5PointNameHeader\">" . $PlayersLang['GoalieName'] . "</td><td class=\"STHSIndex_Top5PointResultHeader\">W-PCT</td></tr>";

	$Query = "SELECT ROUND((CAST(GoalerProStat.SA - GoalerProStat.GA AS REAL) / (GoalerProStat.SA)),3) AS PCT, GoalerProStat.W, GoalerProStat.SecondPlay, GoalerProStat.Name, GoalerProStat.Number, TeamProInfo.Abbre, TeamProInfo.TeamThemeID, GoalerInfo.NHLID  FROM (GoalerInfo INNER JOIN GoalerProStat ON GoalerInfo.Number = GoalerProStat.Number) LEFT JOIN TeamProInfo ON GoalerInfo.Team = TeamProInfo.Number WHERE (GoalerProStat.SecondPlay >= (" . $LeagueOutputOption['ProMinimumGamePlayerLeader'] . "*3600)) AND (GoalerInfo.Team > 0) AND (PCT > 0) ORDER BY PCT DESC, GoalerProStat.W DESC LIMIT 5";
	$PlayerStat = $db->query($Query);

	$LoopCount = (int)0;
	if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
		$LoopCount +=1;echo "<tr><td>";
		If ($Row['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPIndividualLeadersTeamImage\">";}	echo "<a href=\"GoalieReport.php?Goalie=" . $Row['Number'] . "\">" . $Row['Name'] . " (" . $Row['Abbre'] . ")</a>";
		If($LoopCount == 1){If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $Row['NHLID'] != ""){
		echo "<div class=\"Headshot\"><img loading=\"lazy\" src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $Row['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $Row['Name']. "\" class=\"STHSPHPIndexLeadersHeadshot\" /></div>";}}
		echo "</td><td>" . $Row['W'] . " - " . number_Format($Row['PCT'],3) .  "</td></tr>\n";
	}}
	echo "<tr><th colspan=\"2\" class=\"STHSTop5\">" . $IndexLang['Top5Defenseman'] . "</th></tr>";
	echo "<tr><td class=\"STHSIndex_Top5PointNameHeader\">" . $PlayersLang['PlayerName'] . "</td><td class=\"STHSIndex_Top5PointResultHeader\">G-A-P</td></tr>";

	$Query = "SELECT PlayerProStat.G, PlayerProStat.A, PlayerProStat.P, PlayerProStat.GP, PlayerProStat.Name, PlayerProStat.Number, TeamProInfo.Abbre, TeamProInfo.TeamThemeID, PlayerInfo.NHLID  FROM (PlayerInfo INNER JOIN PlayerProStat ON PlayerInfo.Number = PlayerProStat.Number) LEFT JOIN TeamProInfo ON PlayerInfo.Team = TeamProInfo.Number WHERE (PlayerProStat.GP >= " . $LeagueOutputOption['ProMinimumGamePlayerLeader'] . ") AND (PlayerInfo.Team > 0) AND (PlayerInfo.PosD='True') AND (PlayerProStat.P > 0) ORDER BY PlayerProStat.P DESC, PlayerProStat.G DESC, PlayerProStat.GP ASC LIMIT 5";
	$PlayerStat = $db->query($Query);

	$LoopCount = (int)0;
	if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
		$LoopCount +=1;echo "<tr><td>";
		If ($Row['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPIndividualLeadersTeamImage\">";}	echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " (" . $Row['Abbre'] . ")</a>";
		If($LoopCount == 1){If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $Row['NHLID'] != ""){
		echo "<div class=\"Headshot\"><img loading=\"lazy\" src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $Row['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $Row['Name']. "\" class=\"STHSPHPIndexLeadersHeadshot\" /></div>";}}
		echo "</td><td>" . $Row['G'] . "-" . $Row['A'] . "-" . $Row['P'] . "</td></tr>\n";
	}}
	echo "<tr><th colspan=\"2\" class=\"STHSTop5\">" . $IndexLang['Top5Rookies'] . "</th></tr>";
	echo "<tr><td class=\"STHSIndex_Top5PointNameHeader\">" . $PlayersLang['PlayerName'] . "</td><td class=\"STHSIndex_Top5PointResultHeader\">G-A-P</td></tr>";

	$Query = "SELECT PlayerProStat.G, PlayerProStat.A, PlayerProStat.P, PlayerProStat.GP, PlayerProStat.Name, PlayerProStat.Number, TeamProInfo.Abbre, TeamProInfo.TeamThemeID, PlayerInfo.NHLID  FROM (PlayerInfo INNER JOIN PlayerProStat ON PlayerInfo.Number = PlayerProStat.Number) LEFT JOIN TeamProInfo ON PlayerInfo.Team = TeamProInfo.Number WHERE (PlayerProStat.GP >= " . $LeagueOutputOption['ProMinimumGamePlayerLeader'] . ") AND (PlayerInfo.Team > 0) AND (PlayerInfo.Rookie='True') AND (PlayerProStat.P > 0) ORDER BY PlayerProStat.P DESC, PlayerProStat.G DESC, PlayerProStat.GP ASC LIMIT 5";
	$PlayerStat = $db->query($Query);

	$LoopCount = (int)0;
	if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
		$LoopCount +=1;echo "<tr><td>";
		If ($Row['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPIndividualLeadersTeamImage\">";}	echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " (" . $Row['Abbre'] . ")</a>";
		If($LoopCount == 1){If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $Row['NHLID'] != ""){
		echo "<div class=\"Headshot\"><img loading=\"lazy\" src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $Row['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $Row['Name']. "\" class=\"STHSPHPIndexLeadersHeadshot\" /></div>";}}
		echo "</td><td>" . $Row['G'] . "-" . $Row['A'] . "-" . $Row['P'] . "</td></tr>\n";
	}}
	echo "<tr><th colspan=\"2\" class=\"STHSTop5\"><br><br>" . $IndexLang['7DaysStar'] . "</th></tr>\n";
	echo "<tr><td colspan=\"2\"><img src=\"" . $ImagesCDNPath . "/images/Star1.png\" alt=\"Star1\" class=\"STHSIndex_3StarImage\">" . $LeagueGeneral['Days73StarPro1'] . "</td></tr>";
	echo "<tr><td colspan=\"2\"><img src=\"" . $ImagesCDNPath . "/images/Star2.png\" alt=\"Star2\" class=\"STHSIndex_3StarImage\">" . $LeagueGeneral['Days73StarPro2'] . "</td></tr>";
	echo "<tr><td colspan=\"2\"><img src=\"" . $ImagesCDNPath . "/images/Star3.png\" alt=\"Star3\" class=\"STHSIndex_3StarImage\">" . $LeagueGeneral['Days73StarPro3'] . "</td></tr>";
	echo "<tr><th colspan=\"2\" class=\"STHSTop5\"><br><br>" . $IndexLang['30DaysStar'] . "</th></tr>\n";
	echo "<tr><td colspan=\"2\"><img src=\"" . $ImagesCDNPath . "/images/Star1.png\" alt=\"Star1\" class=\"STHSIndex_3StarImage\">" . $LeagueGeneral['Days303StarPro1'] . "</td></tr>";
	echo "<tr><td colspan=\"2\"><img src=\"" . $ImagesCDNPath . "/images/Star2.png\" alt=\"Star2\" class=\"STHSIndex_3StarImage\">" . $LeagueGeneral['Days303StarPro2'] . "</td></tr>";
	echo "<tr><td colspan=\"2\"><img src=\"" . $ImagesCDNPath . "/images/Star3.png\" alt=\"Star3\" class=\"STHSIndex_3StarImage\">" . $LeagueGeneral['Days303StarPro3'] . "</td></tr>";

	If ($LeagueOutputOption['ShowFarmScoreinPHPHomePage'] == 'True'){
		echo "<tr><th colspan=\"2\" class=\"STHSTop5\"><br><br>" . $TopMenuLang['FarmLeague'] . " : " . $IndexLang['7DaysStar'] . "</th></tr>\n";
		echo "<tr><td colspan=\"2\"><img src=\"" . $ImagesCDNPath . "/images/Star1.png\" alt=\"Star1\" class=\"STHSIndex_3StarImage\">" . $LeagueGeneral['Days73StarFarm1'] . "</td></tr>";
		echo "<tr><td colspan=\"2\"><img src=\"" . $ImagesCDNPath . "/images/Star2.png\" alt=\"Star2\" class=\"STHSIndex_3StarImage\">" . $LeagueGeneral['Days73StarFarm2'] . "</td></tr>";
		echo "<tr><td colspan=\"2\"><img src=\"" . $ImagesCDNPath . "/images/Star3.png\" alt=\"Star3\" class=\"STHSIndex_3StarImage\">" . $LeagueGeneral['Days73StarFarm3'] . "</td></tr>";
		echo "<tr><th colspan=\"2\" class=\"STHSTop5\"><br><br>" . $TopMenuLang['FarmLeague'] . " : " . $IndexLang['30DaysStar'] . "</th></tr>\n";
		echo "<tr><td colspan=\"2\"><img src=\"" . $ImagesCDNPath . "/images/Star1.png\" alt=\"Star1\" class=\"STHSIndex_3StarImage\">" . $LeagueGeneral['Days303StarFarm1'] . "</td></tr>";
		echo "<tr><td colspan=\"2\"><img src=\"" . $ImagesCDNPath . "/images/Star2.png\" alt=\"Star2\" class=\"STHSIndex_3StarImage\">" . $LeagueGeneral['Days303StarFarm2'] . "</td></tr>";
		echo "<tr><td colspan=\"2\"><img src=\"" . $ImagesCDNPath . "/images/Star3.png\" alt=\"Star3\" class=\"STHSIndex_3StarImage\">" . $LeagueGeneral['Days303StarFarm3'] . "</td></tr>";	
	}
}?>

</table>
<table class="STHSIndex_Top20FreeAgents">
<tr><th colspan="2" class="STHSTop5"><?php echo $IndexLang['TopFreeAgents'];?></th></tr>
<tr><td class="STHSIndex_Top5PointNameHeader"><?php echo $PlayersLang['PlayerName'];?></td><td class="STHSIndex_Top5PointResultHeader">Overall-Age</td></tr>
<?php
$Query = "SELECT MainTable.*, GoalerInfo.PosG FROM ((SELECT PlayerInfo.Number, PlayerInfo.Name, PlayerInfo.Team, PlayerInfo.Age, PlayerInfo.Contract, PlayerInfo.Salary1, PlayerInfo.Overall FROM PlayerInfo WHERE Team >= 0 AND Number > 0 UNION ALL SELECT GoalerInfo.Number, GoalerInfo.Name, GoalerInfo.Team, GoalerInfo.Age, GoalerInfo.Contract, GoalerInfo.Salary1, GoalerInfo.Overall FROM GoalerInfo WHERE Team >= 0 AND Number > 0) AS MainTable) LEFT JOIN GoalerInfo ON MainTable.Name = GoalerInfo.Name WHERE (MainTable.Team >= 0 AND MainTable.Contract = 0) OR (MainTable.Team = 0) ORDER BY MainTable.Overall DESC LIMIT 50";
If ($IndexQueryOK == True AND $IndexBotProtectionEnable == False){$PlayerStat = $db->query($Query);}
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	echo "<tr><td>";
	if ($Row['PosG']== "True"){echo "<a href=\"GoalieReport.php?Goalie=";}else{echo "<a href=\"PlayerReport.php?Player=";}
	Echo $Row['Number'] . "\">" . $Row['Name'] . "</a></td>";
	echo "<td>" . $Row['Overall'] . " - " . $Row['Age'] . "</td></tr>\n";
}}?>
</table>
</td>

<td class="STHSIndex_NewsTD">
<div class="STHSIndex_TheNews"><?php echo $LeagueName . $IndexLang['News'];?></div>
<div class="STHSIndex_NewsDiv"><?php If (file_exists("NewsSub.php") == true){ include "NewsSub.php";}?></div>
<br><br>
</td>

</tr>
</table>
<script>
const swiper = new Swiper('.STHSIndex', {
	autoHeight: true,
    direction: 'horizontal',
	loop: false,
	slidesPerView:'auto',  
	initialSlide:0,
	slideActiveClass:'swiper-slide-active',
	navigation: {
		nextEl: '.swiper-button-next',
		prevEl: '.swiper-button-prev',
	},
});
</script>
<?php If (file_exists("Footer.php") == true){ include "Footer.php";}
function checkRequiredFiles(string $basePath = '.')
{
    $requiredFiles = [
		'STHSSetting.php',	
        'STHSMain.css',
        'STHSTeam.css',
        'STHSThemeStyleA.css',
        'STHSBanner.css',		
        'swiper-bundle.min.css',
        'STHSMain.js',
        'swiper-bundle.min.js',
        'js/jquery.labs.js',
        'js/jquery.ui.touch-punch.min.js',
        'js/lineeditor.js',
        'js/rostereditor.js',
        'js/scripts_labs.js',
        'css/labs.css',
        'css/lineeditor.css',
        'css/required.css',
        'css/rostereditor.css',
        'images/ArenaInfo.png',
        'images/bg.jpg',
        'images/farmleague.png',
        'images/Financial1.png',
        'images/Financial2.png',
        'images/footerbg.gif',
        'images/icon_top.png',
        'images/index.html',
        'images/league.png',
        'images/Players.png',
        'images/proleague.png',
        'images/RosterInfo.png',
        'images/StanleyCup.png',
        'images/Star1.png',
        'images/Star2.png',
        'images/Star3.png',
        'images/Stats.png',
        'images/sthsheader.png',
        'images/switch.png',
        'images/Tickets.png',
        'images/top5.png',
        'images/TradeArrow.png',
        'images/World.png',
		'API.php',
		'APIBackEnd.php',
		'APIFunction.php',
		'APISearchLive.php',
		'Awards.php',
		'Boxscore.php',
		'Coaches.php',
		'Cookie.php',
		'CupWinner.php',
		'DownloadDB.php',
		'DownloadSTHSClientFiles.php',
		'DraftSelection.php',
		'EditPlayerInfo.php',
		'EntryDraft.php',
		'EntryDraftHistory.php',
		'EntryDraftProjection.php',
		'FarmTeam.php',
		'FilterTip.php',
		'Finance.php',
		'Footer.php',
		'FreeAgentOffers.php',
		'GoalieReport.php',
		'GoaliesCompare.php',
		'GoaliesRoster.php',
		'GoaliesStat.php',
		'GoaliesStatSub.php',
		'Header.php',
		'HistoryStanding.php',
		'HistorySubForGoalieStat.php',
		'HistorySubForPlayerStat.php',
		'HistorySubForTeamStat.php',
		'IndividualLeaders.php',
		'LanguageEN-League.php',
		'LanguageEN-Main.php',
		'LanguageEN-Stat.php',
		'LanguageEN.php',
		'LanguageFR-League.php',
		'LanguageFR-Main.php',
		'LanguageFR-Stat.php',
		'LanguageFR.php',
		'Leaderboard.php',
		'LeagueInformation.php',
		'LegacyPages.php',
		'Login.php',
		'Menu.php',
		'NewsEditor.php',
		'NewsManagement.php',
		'NewsSub.php',
		'PlayerReport.php',
		'PlayersCompare.php',
		'PlayersInfo.php',
		'PlayersInfoSub.php',
		'PlayersRoster.php',
		'PlayersStat.php',
		'PlayersStatSub.php',
		'PowerRanking.php',
		'Prospects.php',
		'ProspectsSub.php',
		'ProTeam.php',
		'Schedule.php',
		'ScheduleSub.php',
		'Search.php',
		'SearchEntryDraft.php',
		'SearchGoalierRoster.php',
		'SearchGoaliesStat.php',
		'SearchHistoryCoaches.php',
		'SearchHistoryFinance.php',
		'SearchHistoryGoalierRoster.php',
		'SearchHistoryGoaliesStat.php',
		'SearchHistoryPlayerInfo.php',
		'SearchHistoryPlayersRoster.php',
		'SearchHistoryPlayersStat.php',
		'SearchHistoryProspects.php',
		'SearchHistorySchedule.php',
		'SearchHistoryStanding.php',
		'SearchHistorySub.php',
		'SearchHistoryTeams.php',
		'SearchHistoryTeamsStat.php',
		'SearchPlayerInfo.php',
		'SearchPlayersRoster.php',
		'SearchPlayersStat.php',
		'SearchPossibleOrderField.php',
		'SearchProspects.php',
		'SearchTeamsStat.php',
		'SearchTransaction.php',
		'SendEmail.php',
		'Standing.php',
		'TeamCareerOnly.php',
		'TeamRosterAverage.php',
		'TeamSalaryCapDetail.php',
		'TeamsAndGMInfo.php',
		'TeamsStat.php',
		'TeamsStatSub.php',
		'ThemeEditor.php',
		'ThemeFunction.php',
		'TodayGames.php',
		'Trade.php',
		'TradeConfirm.php',
		'TradeOtherTeam.php',
		'TradePending.php',
		'TradeView.php',
		'Transaction.php',
		'Upload.php',
		'UploadSTHSClient.php',
		'Waivers.php',
		'WebClientAPI.php',
		'WebClientIndex.php',
		'WebClientLines.php',
		'WebClientRoster.php',
		'WebClientTeam.php',
		
    ];

    $missing = [];

    foreach ($requiredFiles as $file) {
        if (!file_exists(rtrim($basePath, '/\\') . DIRECTORY_SEPARATOR . $file)) {
            $missing[] = $file;
        }
    }

    if (!empty($missing)) {
        return [
            'success' => false,
            'missing' => $missing,
        ];
    }

    return [
        'success' => true,
        'missing' => [],
    ];
}

?>
