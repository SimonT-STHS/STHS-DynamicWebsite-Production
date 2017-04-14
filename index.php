<!DOCTYPE html>
<?php include "Header.php";?>
<script type="text/javascript">
/*! jCarouselLite - v1.1 - 2014-09-28  */
!function(a){a.jCarouselLite={version:"1.1"},a.fn.jCarouselLite=function(b){return b=a.extend({},a.fn.jCarouselLite.options,b||{}),this.each(function(){function c(a){return n||(clearTimeout(A),z=a,b.beforeStart&&b.beforeStart.call(this,i()),b.circular?j(a):k(a),m({start:function(){n=!0},done:function(){b.afterEnd&&b.afterEnd.call(this,i()),b.auto&&h(),n=!1}}),b.circular||l()),!1}function d(){if(n=!1,o=b.vertical?"top":"left",p=b.vertical?"height":"width",q=B.find(">ul"),r=q.find(">li"),x=r.size(),w=x<b.visible?x:b.visible,b.circular){var c=r.slice(x-w).clone(),d=r.slice(0,w).clone();q.prepend(c).append(d),b.start+=w}s=a("li",q),y=s.size(),z=b.start}function e(){B.css("visibility","visible"),s.css({overflow:"hidden","float":b.vertical?"none":"left"}),q.css({margin:"0",padding:"0",position:"relative","list-style":"none","z-index":"1"}),B.css({overflow:"hidden",position:"relative","z-index":"2",left:"0px"}),!b.circular&&b.btnPrev&&0==b.start&&a(b.btnPrev).addClass("disabled")}function f(){t=b.vertical?s.outerHeight(!0):s.outerWidth(!0),u=t*y,v=t*w,s.css({width:s.width(),height:s.height()}),q.css(p,u+"px").css(o,-(z*t)),B.css(p,v+"px")}function g(){b.btnPrev&&a(b.btnPrev).click(function(){return c(z-b.scroll)}),b.btnNext&&a(b.btnNext).click(function(){return c(z+b.scroll)}),b.btnGo&&a.each(b.btnGo,function(d,e){a(e).click(function(){return c(b.circular?w+d:d)})}),b.mouseWheel&&B.mousewheel&&B.mousewheel(function(a,d){return c(d>0?z-b.scroll:z+b.scroll)}),b.auto&&h()}function h(){A=setTimeout(function(){c(z+b.scroll)},b.auto)}function i(){return s.slice(z).slice(0,w)}function j(a){var c;a<=b.start-w-1?(c=a+x+b.scroll,q.css(o,-(c*t)+"px"),z=c-b.scroll):a>=y-w+1&&(c=a-x-b.scroll,q.css(o,-(c*t)+"px"),z=c+b.scroll)}function k(a){0>a?z=0:a>y-w&&(z=y-w)}function l(){a(b.btnPrev+","+b.btnNext).removeClass("disabled"),a(z-b.scroll<0&&b.btnPrev||z+b.scroll>y-w&&b.btnNext||[]).addClass("disabled")}function m(c){n=!0,q.animate("left"==o?{left:-(z*t)}:{top:-(z*t)},a.extend({duration:b.speed,easing:b.easing},c))}var n,o,p,q,r,s,t,u,v,w,x,y,z,A,B=a(this);d(),e(),f(),g()})},a.fn.jCarouselLite.options={btnPrev:null,btnNext:null,btnGo:null,mouseWheel:!1,auto:null,speed:200,easing:null,vertical:!1,circular:!0,visible:3,start:0,scroll:1,beforeStart:null,afterEnd:null}}(jQuery);
function toggleDiv(divId) {$("#"+divId).toggle();}
</script>
<?php
$Active = 1; /* Show Webpage Top Menu */
If (file_exists($DatabaseFile) == false){
	$LeagueName = $DatabaseNotFound;
	$Transaction = Null;
	$Schedule = Null;
	echo "<title>" . $DatabaseNotFound . "</title>";
	echo "<style type=\"text/css\">";
	echo ".STHSIndex_Main{display:none;}";
}else{
	$LeagueName = (string)"";
	
	$db = new SQLite3($DatabaseFile);
	
	$Query = "Select Name, ScheduleNextDay, DefaultSimulationPerDay, PointSystemSO, OffSeason from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];	
	
	$Query = "SELECT LeagueLog.* FROM LeagueLog WHERE ((LeagueLog.TransactionType = 1) OR (LeagueLog.TransactionType = 2) OR  (LeagueLog.TransactionType = 3) OR  (LeagueLog.TransactionType = 6)) ORDER BY LeagueLog.Number DESC LIMIT 10";
	$Transaction = $db->query($Query);
	
	$Query = "Select ProMinimumGamePlayerLeader, ShowFarmScoreinPHPHomePage, NumberofNewsinPHPHomePage, NumberofLatestScoreinPHPHomePage from LeagueOutputOption";
	$LeagueOutputOption = $db->querySingle($Query,true);		
	
	If (file_exists($NewsDatabaseFile) == false){
		$LeagueNews = Null;
	}else{
		$dbNews = new SQLite3($NewsDatabaseFile);
		$Query = "Select * FROM LeagueNews WHERE Remove = 'False' ORDER BY Time DESC";
		$LeagueNews = $dbNews->query($Query);
	}
		
	If ($LeagueOutputOption['ShowFarmScoreinPHPHomePage'] == 'True'){
		$Query = "SELECT *,'Pro' as Type FROM SchedulePro WHERE Day = " . ($LeagueGeneral['ScheduleNextDay'] - $LeagueGeneral['DefaultSimulationPerDay']) . " UNION SELECT *,'Farm' as Type FROM ScheduleFarm WHERE Day = " . ($LeagueGeneral['ScheduleNextDay'] - $LeagueGeneral['DefaultSimulationPerDay']) . " ORDER BY TYPE DESC, GAMENUMBER";
		$QuerySchedule = "Select ProSchedule.*, 'Pro' AS Type FROM (SELECT TeamProStatVisitor.Last10W AS VLast10W, TeamProStatVisitor.Last10L AS VLast10L, TeamProStatVisitor.Last10T AS VLast10T, TeamProStatVisitor.Last10OTW AS VLast10OTW, TeamProStatVisitor.Last10OTL AS VLast10OTL, TeamProStatVisitor.Last10SOW AS VLast10SOW, TeamProStatVisitor.Last10SOL AS VLast10SOL, TeamProStatVisitor.GP AS VGP, TeamProStatVisitor.W AS VW, TeamProStatVisitor.L AS VL, TeamProStatVisitor.T AS VT, TeamProStatVisitor.OTW AS VOTW, TeamProStatVisitor.OTL AS VOTL, TeamProStatVisitor.SOW AS VSOW, TeamProStatVisitor.SOL AS VSOL, TeamProStatVisitor.Points AS VPoints, TeamProStatVisitor.Streak AS VStreak, TeamProStatHome.Last10W AS HLast10W, TeamProStatHome.Last10L AS HLast10L, TeamProStatHome.Last10T AS HLast10T, TeamProStatHome.Last10OTW AS HLast10OTW, TeamProStatHome.Last10OTL AS HLast10OTL, TeamProStatHome.Last10SOW AS HLast10SOW, TeamProStatHome.Last10SOL AS HLast10SOL, TeamProStatHome.GP AS HGP, TeamProStatHome.W AS HW, TeamProStatHome.L AS HL, TeamProStatHome.T AS HT, TeamProStatHome.OTW AS HOTW, TeamProStatHome.OTL AS HOTL, TeamProStatHome.SOW AS HSOW, TeamProStatHome.SOL AS HSOL, TeamProStatHome.Points AS HPoints, TeamProStatHome.Streak AS HStreak, SchedulePro.* FROM (SchedulePRO LEFT JOIN TeamProStat AS TeamProStatHome ON SchedulePRO.HomeTeam = TeamProStatHome.Number) LEFT JOIN TeamProStat AS TeamProStatVisitor ON SchedulePRO.VisitorTeam = TeamProStatVisitor.Number WHERE DAY >= " . $LeagueGeneral['ScheduleNextDay'] . " AND DAY <= " . ($LeagueGeneral['ScheduleNextDay'] + $LeagueGeneral['DefaultSimulationPerDay'] -1) . ") AS ProSchedule  UNION ALL Select FarmSchedule.*, 'Farm' AS Type FROM (SELECT TeamFarmStatVisitor.Last10W AS VLast10W, TeamFarmStatVisitor.Last10L AS VLast10L, TeamFarmStatVisitor.Last10T AS VLast10T, TeamFarmStatVisitor.Last10OTW AS VLast10OTW, TeamFarmStatVisitor.Last10OTL AS VLast10OTL, TeamFarmStatVisitor.Last10SOW AS VLast10SOW, TeamFarmStatVisitor.Last10SOL AS VLast10SOL, TeamFarmStatVisitor.GP AS VGP, TeamFarmStatVisitor.W AS VW, TeamFarmStatVisitor.L AS VL, TeamFarmStatVisitor.T AS VT, TeamFarmStatVisitor.OTW AS VOTW, TeamFarmStatVisitor.OTL AS VOTL, TeamFarmStatVisitor.SOW AS VSOW, TeamFarmStatVisitor.SOL AS VSOL, TeamFarmStatVisitor.Points AS VPoints, TeamFarmStatVisitor.Streak AS VStreak, TeamFarmStatHome.Last10W AS HLast10W, TeamFarmStatHome.Last10L AS HLast10L, TeamFarmStatHome.Last10T AS HLast10T, TeamFarmStatHome.Last10OTW AS HLast10OTW, TeamFarmStatHome.Last10OTL AS HLast10OTL, TeamFarmStatHome.Last10SOW AS HLast10SOW, TeamFarmStatHome.Last10SOL AS HLast10SOL, TeamFarmStatHome.GP AS HGP, TeamFarmStatHome.W AS HW, TeamFarmStatHome.L AS HL, TeamFarmStatHome.T AS HT, TeamFarmStatHome.OTW AS HOTW, TeamFarmStatHome.OTL AS HOTL, TeamFarmStatHome.SOW AS HSOW, TeamFarmStatHome.SOL AS HSOL, TeamFarmStatHome.Points AS HPoints, TeamFarmStatHome.Streak AS HStreak, ScheduleFarm.* FROM (ScheduleFarm LEFT JOIN TeamFarmStat AS TeamFarmStatHome ON ScheduleFarm.HomeTeam = TeamFarmStatHome.Number) LEFT JOIN TeamFarmStat AS TeamFarmStatVisitor ON ScheduleFarm.VisitorTeam = TeamFarmStatVisitor.Number WHERE DAY >= " . $LeagueGeneral['ScheduleNextDay'] . " AND DAY <= " . ($LeagueGeneral['ScheduleNextDay'] + $LeagueGeneral['DefaultSimulationPerDay'] -1) . ") AS FarmSchedule ORDER BY Day, Type DESC, GameNumber";
	}else{
		$Query = "SELECT * FROM SchedulePro WHERE Day = " . ($LeagueGeneral['ScheduleNextDay'] - $LeagueGeneral['DefaultSimulationPerDay']) . " ORDER BY GameNumber ";
		$QuerySchedule = "SELECT SchedulePro.*, 'Pro' AS Type, TeamProStatVisitor.Last10W AS VLast10W, TeamProStatVisitor.Last10L AS VLast10L, TeamProStatVisitor.Last10T AS VLast10T, TeamProStatVisitor.Last10OTW AS VLast10OTW, TeamProStatVisitor.Last10OTL AS VLast10OTL, TeamProStatVisitor.Last10SOW AS VLast10SOW, TeamProStatVisitor.Last10SOL AS VLast10SOL, TeamProStatVisitor.GP AS VGP, TeamProStatVisitor.W AS VW, TeamProStatVisitor.L AS VL, TeamProStatVisitor.T AS VT, TeamProStatVisitor.OTW AS VOTW, TeamProStatVisitor.OTL AS VOTL, TeamProStatVisitor.SOW AS VSOW, TeamProStatVisitor.SOL AS VSOL, TeamProStatVisitor.Points AS VPoints, TeamProStatVisitor.Streak AS VStreak, TeamProStatHome.Last10W AS HLast10W, TeamProStatHome.Last10L AS HLast10L, TeamProStatHome.Last10T AS HLast10T, TeamProStatHome.Last10OTW AS HLast10OTW, TeamProStatHome.Last10OTL AS HLast10OTL, TeamProStatHome.Last10SOW AS HLast10SOW, TeamProStatHome.Last10SOL AS HLast10SOL, TeamProStatHome.GP AS HGP, TeamProStatHome.W AS HW, TeamProStatHome.L AS HL, TeamProStatHome.T AS HT, TeamProStatHome.OTW AS HOTW, TeamProStatHome.OTL AS HOTL, TeamProStatHome.SOW AS HSOW, TeamProStatHome.SOL AS HSOL, TeamProStatHome.Points AS HPoints, TeamProStatHome.Streak AS HStreak FROM (SchedulePRO LEFT JOIN TeamProStat AS TeamProStatHome ON SchedulePRO.HomeTeam = TeamProStatHome.Number) LEFT JOIN TeamProStat AS TeamProStatVisitor ON SchedulePRO.VisitorTeam = TeamProStatVisitor.Number WHERE DAY >= " . $LeagueGeneral['ScheduleNextDay'] . " AND DAY <= " . ($LeagueGeneral['ScheduleNextDay'] + $LeagueGeneral['DefaultSimulationPerDay'] -1) . " ORDER BY Day, GameNumber";
	}
	
	$LatestScore = $db->query($Query);
	$Schedule = $db->query($QuerySchedule);
	
	echo "<title>" . $LeagueName . " - " . $IndexLang['IndexTitle'] . "</title>";
	echo "<style type=\"text/css\">";
}

Function PrintMainNews($row, $IndexLang, $dbNews){
	/* This Function Print a News */
	$UTC = new DateTimeZone("UTC");
	$ServerTimeZone = new DateTimeZone(date_default_timezone_get());
	echo "<h2>" . $row['Title'] . "</h2>";
	$Date = new DateTime($row['Time'], $UTC );
	$Date->setTimezone($ServerTimeZone);
	
	/* The following two lines publish the news */
	echo "<strong>" . $IndexLang['By'] . " " . $row['Owner'] . " " . $IndexLang['On'] . " " . $Date->format('l jS F Y / g:ia ')  . "</strong><br />";
	echo  $row['Message'] . "\n"; /* The \n is for a new line in the HTML Code */
	
	/* Get the Number of Reply */
	$NewsReplyCount = Null;
	$Query = "Select Count(Message) as CountMessage FROM LeagueNews WHERE Remove = 'False' AND AnswerNumber = " . $row['Number'] . " ORDER BY Number";
	$NewsReplyCount = $dbNews->querySingle($Query,true);
	
	If ($NewsReplyCount['CountMessage'] > 0 ){ /* If Reply are Found */

		/* Query Reply */
		$NewsReply = Null;
		$Query = "Select * FROM LeagueNews WHERE Remove = 'False' AND AnswerNumber = " . $row['Number'] . " ORDER BY Number";
		$NewsReply = $dbNews->query($Query);
	
		/* Show the Number of News + Create the Link */
		echo "<a href=\"javascript:toggleDiv('News" . $row['Number'] . "');\">" . $IndexLang['Viewcomments'] . " (" .  $NewsReplyCount['CountMessage'] . ")</a>"; 

		/* Publish all the Comments in Table */
		echo "<table class=\"STHSIndex_NewsReplyTable\" id=\"News" . $row['Number'] . "\"><tbody>";
		if (empty($NewsReply) == false){
			while ($ReplyRow = $NewsReply ->fetchArray()) { 
			$Date = new DateTime($ReplyRow['Time'], $UTC );
			$Date->setTimezone($ServerTimeZone);
			echo "<tr><td><span class=\"STHSIndex_NewsReplyOwner\">" . $ReplyRow['Owner'] . "</span> <span class=\"STHSIndex_NewsReplyTime\">" . $IndexLang['On'] . " " . $Date->format('jS F / g:ia ') . "</span> : " . $ReplyRow['Message'] . "</td></tr>";			
		}}
		echo "<tr><td><a href=\"NewsEditor.php?ReplyNews=" . $row['Number'] . "\">" . $IndexLang['Comment'] . "</a></td></tr>";
		echo "</tbody></table>";
	
	}else{
		/* No Reply, print link to create the first reply */
		echo "<a href=\"NewsEditor.php?ReplyNews=" . $row['Number'] . "\">" . $IndexLang['Comment'] . "</a>\n";	
	}
}
?>
.carousel {	border: 1px solid rgb(186, 186, 186); border-image: none; left: -5000px; float: left; visibility: hidden; position: relative}
.carousel > ul > li  {	border: 1px solid rgb(186, 186, 186);}
a.prev {	border-radius: 8px; width: 26px; height: 30px; color: ghostwhite; line-height: 1; font-family: Arial, sans-serif; font-size: 25px; text-decoration: none; float: left; display: block; background-color: rgb(51, 51, 51); -moz-border-radius: 30px; -webkit-border-radius: 30px;}
a.next {	border-radius: 8px; width: 26px; height: 30px; color: ghostwhite; line-height: 1; font-family: Arial, sans-serif; font-size: 25px; text-decoration: none; float: left; display: block; background-color: rgb(51, 51, 51); -moz-border-radius: 30px; -webkit-border-radius: 30px;}
a.prev {	margin: 50px 0px 0px 0px; text-indent: 7px;}
a.next {	margin: 50px 0px 0px 0px; text-indent: 10px;}
a.prev:hover {background-color: rgb(102, 102, 102);}
a.next:hover {background-color: rgb(102, 102, 102);}
.CarouselTable {border-width: 0.5px;border-style: solid;border-collapse: collapse;}
.CarouselTable th {font-weight: bold;}
.CarouselTable td {padding-left: 5px;}
<?php 
If ($LeagueGeneral['OffSeason'] == "True"){
	echo ".STHSIndex_Score{display:none;}";
	echo ".STHSIndex_Top5Table {display:none;}";
	echo "@media screen and (max-width: 890px) {.STHSIndex_Top5 {display:none;}}";
}else{
	echo ".STHSIndex_Top20FreeAgents {display:none;}";
	echo "@media screen and (max-width: 890px) {.STHSIndex_Score{display:none;}}";
	echo "@media screen and (max-width: 1200px) {.STHSIndex_Top5 {display:none;}}";
}?>
</style>
</head><body>
<?php include "Menu.php";
If (file_exists($DatabaseFile) == false){echo "<br /><br /><h1 class=\"STHSCenter\">" . $DatabaseNotFound . "</h1>";}
?>
<table class="STHSIndex_Main"><tr><td class="STHSIndex_Score">
<table class="STHSTableFullW"><tr><td>
<div class="STHSIndex_LastestResult"><?php echo $IndexLang['LatestScores'];?></div>
<div class="custom-container TodayGame"><a class="prev" href="#">‹</a><div class="carousel"><ul>
<?php
if (empty($LatestScore) == false){while ($row = $LatestScore ->fetchArray()) {
	echo "<li><table class=\"CarouselTable\" style=\"width:200px;\">";
	echo "<tr><th class=\"STHSW140\">Day " . $row['Day']. "</th><th class=\"STHSCTRight\">#" . $row['GameNumber']. "</th></tr>";
	echo "<tr><td>" . $row['VisitorTeamName']. "</td><td class=\"STHSRight\">" . $row['VisitorScore'] . "</td></tr>";
	echo "<tr><td>" . $row['HomeTeamName']. "</td><td class=\"STHSRight\">" . $row['HomeScore'] . "</td></tr>";
	echo "<tr><td colspan=\"2\" class=\"STHSCenter\"><a href=\"" . $row['Link'] ."\">" . $TodayGamesLang['BoxScore'] .  "</a></td></tr>";
	echo "</table></li>";
}}
?>
</ul></div><a class="next" href="#">›</a><div class="clear"></div></div>
<script type="text/javascript">$(function() {$(".TodayGame .carousel").jCarouselLite({btnNext: ".TodayGame .next", btnPrev: ".TodayGame .prev",vertical: true, visible: <?php echo $LeagueOutputOption['NumberofLatestScoreinPHPHomePage'];?>});});</script>
</td></tr><tr><td><br /><br />
<div class="STHSIndex_LastestResult"><?php echo $TodayGamesLang['NextGames'];?></div>
<div class="custom-container NextGame"><a class="prev" href="#">‹</a><div class="carousel"><ul>
<?php
if (empty($Schedule) == false){while ($row = $Schedule ->fetchArray()) {
	echo "<li><table class=\"CarouselTable\" style=\"width:200px;\">";
	echo "<tr><th class=\"STHSW140\">Day " . $row['Day']. " - " . $row['Type'] . " - " . $row['GameNumber']. "</th></tr>";
	echo "<tr><td><a href=\"" . $row['Type']  . "Team.php?Team=" . $row['VisitorTeam'] . "\">" . $row['VisitorTeamName']. "</a> (" . ($row['VW'] + $row['VOTW'] + $row['VSOW']) . "-";
	if ($LeagueGeneral['PointSystemSO'] == "True"){
		echo $row['VL'] . "-" . ($row['VOTL'] + $row['VSOL']);
	}else{
		echo ($row['VL'] + $row['VOTL'] + $row['VSOL']) . "-" . $row['VT'];
	}
	echo ") - " . $row['VStreak'] . "</td></tr>";
	echo "<tr><td><a href=\"" . $row['Type'] . "Team.php?Team=" . $row['HomeTeam'] . "\">" . $row['HomeTeamName']. "</a> (" . ($row['HW'] + $row['HOTW'] + $row['HSOW']) . "-";
	if ($LeagueGeneral['PointSystemSO'] == "True"){
		echo $row['HL'] . "-" . ($row['HOTL'] + $row['HSOL']);
	}else{
		echo ($row['HL'] + $row['HOTL'] + $row['HSOL']) . "-" . $row['HT'];
	}
	echo ") - " . $row['HStreak']. "</td></tr>";
	echo "</table></li>";
}}
?>
</ul></div><a class="next" href="#">›</a><div class="clear"></div></div>
<script type="text/javascript">$(function() {$(".NextGame .carousel").jCarouselLite({btnNext: ".NextGame .next", btnPrev: ".NextGame .prev",vertical: true, visible: <?php echo $LeagueOutputOption['NumberofLatestScoreinPHPHomePage'];?>});});</script>

</td></tr></table>
</td><td class="STHSIndex_NewsTD">
<div class="STHSIndex_TheNews"><?php echo $LeagueName . $IndexLang['News'];?></div>
<?php
$NewsPublish = array(); /* Array that Contain News Publish Already Publish */
$CountNews = 0; /* Number of New Publish so we can apply the STHS option 'Number of News in Home Page' */

if (empty($LeagueNews) == false){while ($row = $LeagueNews ->fetchArray()) { /* Loop News in Reserve Order of Publish Time */
	if (in_array($row['Number'],$NewsPublish) == FALSE AND in_array($row['AnswerNumber'],$NewsPublish) == FALSE ){ /* Make sure we already didn't publish this news */
		if ($row['AnswerNumber'] == 0){
			/* This row of the Table is not answer comment so it's main news */
			PrintMainNews($row, $IndexLang, $dbNews);  /* Print the News */
			
			/* Increment the Number of News Publish */
			$CountNews +=1; 
			
			/* If we publish enough news based on the the STHS option 'Number of News in Home Page', we close the loop */
			If ($CountNews >= $LeagueOutputOption['NumberofNewsinPHPHomePage']){break;} 
		}else{
			/* This is row is answer to previous news. Finding the Main News Information */
			
			$Query = "Select * FROM LeagueNews WHERE Number = " . $row['AnswerNumber'];
			$NewsTemp = $dbNews->querySingle($Query,True);
					
			/* Print the News */
			PrintMainNews($NewsTemp, $IndexLang, $dbNews);  
			
			/* Increment the Number of News Publish */
			$CountNews +=1; 
			
			/* If we publish enough news based on the the STHS option 'Number of News in Home Page', we close the loop */
			If ($CountNews >= $LeagueOutputOption['NumberofNewsinPHPHomePage']){break;} 
			
			/* Add in the Array the Main News will be publish */
			array_push($NewsPublish, $row['AnswerNumber']); 
		}
	}
}}else{echo "<br /><h3>" . $NewsDatabaseNotFound . "</h3>";}
?>

<br /><br /><h2><?php echo $IndexLang['LatestTransactions'];?></h2>
<?php
if (empty($Transaction) == false){while ($row = $Transaction ->fetchArray()) { 
	echo "[" . $row['DateTime'] . "] " . $row['Text'] . "<br />\n"; /* The \n is for a new line in the HTML Code */
}}
?>
</td><td class="STHSIndex_Top5">

<table class="STHSIndex_Top5Table">
<tr><th colspan="2" class="STHSTop5"><?php echo $IndexLang['Top5Point'];?></th></tr>
<tr><td class="STHSIndex_Top5PointNameHeader"><?php echo $PlayersLang['PlayerName'];?></td><td class="STHSIndex_Top5PointResultHeader">G-A-P</td></tr>
<?php
$Query = "SELECT PlayerProStat.G, PlayerProStat.A, PlayerProStat.P, PlayerProStat.GP, PlayerProStat.Name, PlayerProStat.Number, TeamProInfo.Abbre FROM (PlayerInfo INNER JOIN PlayerProStat ON PlayerInfo.Number = PlayerProStat.Number) LEFT JOIN TeamProInfo ON PlayerInfo.Team = TeamProInfo.Number WHERE (PlayerProStat.GP >= " . $LeagueOutputOption['ProMinimumGamePlayerLeader'] . ") AND (PlayerInfo.Team > 0) AND (PlayerProStat.P > 0) ORDER BY PlayerProStat.P DESC, PlayerProStat.G DESC, PlayerProStat.GP ASC LIMIT 5";
$PlayerStat = $db->query($Query);
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	echo "<tr><td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " (" . $Row['Abbre'] . ")</a></td><td>" . $Row['G'] . "-" . $Row['A'] . "-" . $Row['P'] . "</td></tr>\n";
}}?>

<tr><th colspan="2" class="STHSTop5"><br /><br /><?php echo $IndexLang['Top5Goal'];?></th></tr>
<tr><td class="STHSIndex_Top5PointNameHeader"><?php echo $PlayersLang['PlayerName'];?></td><td class="STHSIndex_Top5PointResultHeader">GP-G</td></tr>
<?php
$Query = "SELECT PlayerProStat.G, PlayerProStat.A, PlayerProStat.P, PlayerProStat.GP, PlayerProStat.Name, PlayerProStat.Number, TeamProInfo.Abbre FROM (PlayerInfo INNER JOIN PlayerProStat ON PlayerInfo.Number = PlayerProStat.Number) LEFT JOIN TeamProInfo ON PlayerInfo.Team = TeamProInfo.Number WHERE (PlayerProStat.GP >= " . $LeagueOutputOption['ProMinimumGamePlayerLeader'] . ") AND (PlayerInfo.Team > 0) AND (PlayerProStat.P > 0) ORDER BY PlayerProStat.G DESC, PlayerProStat.GP ASC LIMIT 5";
$PlayerStat = $db->query($Query);
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	echo "<tr><td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " (" . $Row['Abbre'] . ")</a></td><td>" . $Row['GP'] . " - " . $Row['G'] . "</td></tr>\n";
}}?>

<tr><th colspan="2" class="STHSTop5"><br /><br /><?php echo $IndexLang['Top5Goalies'];?></th></tr>
<tr><td class="STHSIndex_Top5PointNameHeader"><?php echo $PlayersLang['GoalieName'];?></td><td class="STHSIndex_Top5PointResultHeader">W-PCT</td></tr>
<?php
$Query = "SELECT ROUND((CAST(GoalerProStat.SA - GoalerProStat.GA AS REAL) / (GoalerProStat.SA)),3) AS PCT, GoalerProStat.W, GoalerProStat.SecondPlay, GoalerProStat.Name, GoalerProStat.Number, TeamProInfo.Abbre FROM (GoalerInfo INNER JOIN GoalerProStat ON GoalerInfo.Number = GoalerProStat.Number) LEFT JOIN TeamProInfo ON GoalerInfo.Team = TeamProInfo.Number WHERE (GoalerProStat.SecondPlay >= (" . $LeagueOutputOption['ProMinimumGamePlayerLeader'] . "*3600)) AND (GoalerInfo.Team > 0) AND (PCT > 0) ORDER BY PCT DESC, GoalerProStat.W DESC LIMIT 5";
$PlayerStat = $db->query($Query);
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	echo "<tr><td><a href=\"GoalieReport.php?Goalie=" . $Row['Number'] . "\">" . $Row['Name'] . " (" . $Row['Abbre'] . ")</a></td><td>" . $Row['W'] . " - " . number_Format($Row['PCT'],3) .  "</td></tr>\n";
}}?>

<tr><th colspan="2" class="STHSTop5"><br /><br /><?php echo $IndexLang['Top5Defenseman'];?></th></tr>
<tr><td class="STHSIndex_Top5PointNameHeader"><?php echo $PlayersLang['PlayerName'];?></td><td class="STHSIndex_Top5PointResultHeader">G-A-P</td></tr>
<?php
$Query = "SELECT PlayerProStat.G, PlayerProStat.A, PlayerProStat.P, PlayerProStat.GP, PlayerProStat.Name, PlayerProStat.Number, TeamProInfo.Abbre FROM (PlayerInfo INNER JOIN PlayerProStat ON PlayerInfo.Number = PlayerProStat.Number) LEFT JOIN TeamProInfo ON PlayerInfo.Team = TeamProInfo.Number WHERE (PlayerProStat.GP >= " . $LeagueOutputOption['ProMinimumGamePlayerLeader'] . ") AND (PlayerInfo.Team > 0) AND (PlayerInfo.PosD='True') AND (PlayerProStat.P > 0) ORDER BY PlayerProStat.P DESC, PlayerProStat.G DESC, PlayerProStat.GP ASC LIMIT 5";
$PlayerStat = $db->query($Query);
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	echo "<tr><td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " (" . $Row['Abbre'] . ")</a></td><td>" . $Row['G'] . "-" . $Row['A'] . "-" . $Row['P'] . "</td></tr>\n";
}}?>

<tr><th colspan="2" class="STHSTop5"><br /><br /><?php echo $IndexLang['Top5Rookies'];?></th></tr>
<tr><td class="STHSIndex_Top5PointNameHeader"><?php echo $PlayersLang['PlayerName'];?></td><td class="STHSIndex_Top5PointResultHeader">G-A-P</td></tr>
<?php
$Query = "SELECT PlayerProStat.G, PlayerProStat.A, PlayerProStat.P, PlayerProStat.GP, PlayerProStat.Name, PlayerProStat.Number, TeamProInfo.Abbre FROM (PlayerInfo INNER JOIN PlayerProStat ON PlayerInfo.Number = PlayerProStat.Number) LEFT JOIN TeamProInfo ON PlayerInfo.Team = TeamProInfo.Number WHERE (PlayerProStat.GP >= " . $LeagueOutputOption['ProMinimumGamePlayerLeader'] . ") AND (PlayerInfo.Team > 0) AND (PlayerInfo.Rookie='True') AND (PlayerProStat.P > 0) ORDER BY PlayerProStat.P DESC, PlayerProStat.G DESC, PlayerProStat.GP ASC LIMIT 5";
$PlayerStat = $db->query($Query);
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	echo "<tr><td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " (" . $Row['Abbre'] . ")</a></td><td>" . $Row['G'] . "-" . $Row['A'] . "-" . $Row['P'] . "</td></tr>\n";
}}?>

</table>
<table class="STHSIndex_Top20FreeAgents">
<tr><th colspan="2" class="STHSTop5"><?php echo $IndexLang['Top20FreeAgents'];?></th></tr>
<tr><td class="STHSIndex_Top5PointNameHeader"><?php echo $PlayersLang['PlayerName'];?></td><td class="STHSIndex_Top5PointResultHeader">Overall-Age</td></tr>
<?php
$Query = "SELECT MainTable.*, GoalerInfo.PosG FROM ((SELECT PlayerInfo.Number, PlayerInfo.Name, PlayerInfo.Team, PlayerInfo.Age, PlayerInfo.Contract, PlayerInfo.SalaryAverage, PlayerInfo.Salary1, PlayerInfo.Overall FROM PlayerInfo WHERE Team >= 0 AND Number > 0 UNION ALL SELECT GoalerInfo.Number, GoalerInfo.Name, GoalerInfo.Team, GoalerInfo.Age, GoalerInfo.Contract, GoalerInfo.SalaryAverage, GoalerInfo.Salary1, GoalerInfo.Overall FROM GoalerInfo WHERE Team >= 0 AND Number > 0) AS MainTable) LEFT JOIN GoalerInfo ON MainTable.Name = GoalerInfo.Name WHERE (MainTable.Team >= 0 AND MainTable.Contract = 0) OR (MainTable.Team = 0) ORDER BY MainTable.Overall DESC LIMIT 20";
$PlayerStat = $db->query($Query);
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	echo "<tr><td>";
	if ($Row['PosG']== "True"){echo "<a href=\"GoalieReport.php?Goalie=";}else{echo "<a href=\"PlayerReport.php?Player=";}
	Echo $Row['Number'] . "\">" . $Row['Name'] . "</a></td>";
	echo "<td>" . $Row['Overall'] . " - " . $Row['Age'] . "</td></tr>\n";
}}?>
</table>
</td>
</tr>
</table>

<?php include "Footer.php";?>
