<?php include "Header.php";
If ($lang == "fr"){include 'LanguageFR-League.php';}else{include 'LanguageEN-League.php';}
$Title = (string)"";
$TypeText = (string)"Pro";$TitleType = $DynamicTitleLang['Pro'];
if(isset($_GET['Farm'])){$TypeText = "Farm";$TitleType = $DynamicTitleLang['Farm'];}
$MaximumResult = (integer)10;
$MinimumGamePlayer = (integer)1;

If (file_exists($DatabaseFile) == false){
	Goto STHSErrorAwards;
}else{try{
	if(isset($_GET['Max'])){$MaximumResult = filter_var($_GET['Max'], FILTER_SANITIZE_NUMBER_INT);} 
	$LeagueName = (string)"";
	$db = new SQLite3($DatabaseFile);
	$Query = "Select Name from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	$Query = "Select PlayersMugShotBaseURL, PlayersMugShotFileExtension from LeagueOutputOption";
	$LeagueOutputOption = $db->querySingle($Query,true);
	
	$Title = $LeagueName . " - " . $DynamicTitleLang['Awards'] . " " . $TitleType ;
	
	If (file_exists($CareerStatDatabaseFile) == true){ /* CareerStat */
		$CareerStatdb = new SQLite3($CareerStatDatabaseFile);
	}else{
		$CareerPlayerStat = Null;
		$Title = $CareeratabaseNotFound;
	}	
	
	echo "<title>" . $Title ."</title>";
} catch (Exception $e) {
STHSErrorAwards:
	$LeagueName = $DatabaseNotFound;
	echo "<title>" . $DatabaseNotFound . "</title>";
	$Title = $DatabaseNotFound;
	echo "<style>.STHSAwards_MainDiv{display:none}</style>";
}}?>
<style>
@media screen and (max-width: 720px) {
.STHSPHPAwardsHeadshot {display:none;}
}

<?php 
$PlayerYear = Null;
$GoalieYear = Null;
$TeamYear = Null;
$PlayerTeamName = Null;
$GoalieTeamName = Null;
$UpdateCareerStatDBV1 = (boolean)false;
If (file_exists($CareerStatDatabaseFile) == false){
	echo "#CareerStatDiv {display:none;}";
}else{try{
	$CareerStatdb = new SQLite3($CareerStatDatabaseFile);		
	include "SearchHistorySub.php";
} catch (Exception $e) {
	echo ".STHSAwards_MainDiv{display:none}";
	$CareerStatDatabaseFile = "";
}}
?>

</style>
</head><body>
<?php include "Menu.php";?>

<div class="STHSAwards_MainDiv" style="width:99%;margin:auto;">

<?php echo "<h1>" . $Title . "</h1>";?>
<table class="STHSAward_Table"><thead><tr><th colspan="3" class="STHSPHPAwardsTitle"><?php echo $AwardsLang['HartMemorialTrophy'];?></th></tr>
<?php
echo "<th class=\"STHSPHPAwardsYearHeader\">" . $AwardsLang['Year'] . "</th><th class=\"STHSPHPAwardsPlayerHeader\">" . $AwardsLang['PlayerName'] . "</th><th class=\"STHSPHPAwardsTeamHeader\">" . $AwardsLang['TeamName'] . "</th></thead>";
if (empty($HistoryYear) == false){while ($RowYear = $HistoryYear ->fetchArray()) { 

	$Query = "SELECT filteredPlayer" . $TypeText . "StatHistory.StarPowerYear, filteredPlayer" . $TypeText . "StatHistory.Name, filteredPlayer" . $TypeText . "StatHistory.Number, filteredPlayer" . $TypeText . "StatHistory.Year, filteredPlayer" . $TypeText . "StatHistory.Playoff, filteredPlayerInfoHistory.NHLID, filteredPlayerInfoHistory.Team, filteredPlayerInfoHistory.TeamName FROM (SELECT Number, Year, Playoff, NHLID, Team, ProTeamName As TeamName FROM PlayerInfoHistory WHERE Playoff = 'False' AND Year = " . $RowYear['Year'] . ") AS filteredPlayerInfoHistory LEFT JOIN (SELECT StarPowerYear, Name, Number, Year, Playoff FROM Player" . $TypeText . "StatHistory WHERE Playoff = 'False' AND Year = " . $RowYear['Year'] . ") AS filteredPlayer" . $TypeText . "StatHistory ON (filteredPlayer" . $TypeText . "StatHistory.Number = filteredPlayerInfoHistory.Number) AND (filteredPlayer" . $TypeText . "StatHistory.Year = filteredPlayerInfoHistory.Year) AND (filteredPlayer" . $TypeText . "StatHistory.Playoff = filteredPlayerInfoHistory.Playoff) UNION ALL SELECT filteredGoaler" . $TypeText . "StatHistory.StarPowerYear, filteredGoaler" . $TypeText . "StatHistory.Name, filteredGoaler" . $TypeText . "StatHistory.Number, filteredGoaler" . $TypeText . "StatHistory.Year, filteredGoaler" . $TypeText . "StatHistory.Playoff, filteredGoalerInfoHistory.NHLID, filteredGoalerInfoHistory.Team, filteredGoalerInfoHistory.TeamName FROM (SELECT Number, Year, Playoff, NHLID, Team, ProTeamName As TeamName FROM GoalerInfoHistory WHERE Playoff = 'False' AND Year = " . $RowYear['Year'] . ") AS filteredGoalerInfoHistory LEFT JOIN (SELECT StarPowerYear, Name, Number, Year, Playoff FROM Goaler" . $TypeText . "StatHistory WHERE Playoff = 'False' AND Year = " . $RowYear['Year'] . ") AS filteredGoaler" . $TypeText . "StatHistory ON (filteredGoaler" . $TypeText . "StatHistory.Number = filteredGoalerInfoHistory.Number) AND (filteredGoaler" . $TypeText . "StatHistory.Year = filteredGoalerInfoHistory.Year) AND (filteredGoaler" . $TypeText . "StatHistory.Playoff = filteredGoalerInfoHistory.Playoff) ORDER BY StarPowerYear DESC LIMIT 1";
	$Result = $CareerStatdb->querySingle($Query,true);
	
	if (empty($Result) == false){
		echo "<tbody><tr><td>" . $RowYear['Year'] . "</td><td>";
		If ($Result['Number'] != Null){
			If ($Result['PosG'] = 'False'){
				echo "<a href=\"PlayerReport.php?Player=" . $Result['Number'] . "\">" . $Result['Name'] . "</a>";
			}else{
				echo "<a href=\"GoalieReport.php?Goalie=" . $Result['Number'] . "\">" . $Result['Name'] . "</a>";
			}	
		}else{
			echo $Result['Name'];
		}
		If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $Result['NHLID'] != ""){
		echo "<div class=\"Headshot\"><img loading=\"lazy\" src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $Result['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $Result['Name']. "\" class=\"STHSPHPAwardsHeadshot\"></div>";}
		echo "</td><td>";
		If ($Result['Team'] != Null){	
			echo "<a href=\"ProTeam.php?Team=" . $Result['Team'] . "\">" . $Result['TeamName'] . "</a>";
		}else{
			echo $Result['TeamName'];
		}
		echo "</td></tr></tboby>\n";
	}

}}	If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS Hart Memorial Trophy : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}?>
</table>
<br>

<table class="STHSAward_Table"><thead><tr><th colspan="3" class="STHSPHPAwardsTitle"><?php echo $AwardsLang['ArtRossTrophy'];?></th></tr>
<?php
echo "<th class=\"STHSPHPAwardsYearHeader\">" . $AwardsLang['Year'] . "</th><th class=\"STHSPHPAwardsPlayerHeader\">" . $AwardsLang['PlayerName'] . "</th><th class=\"STHSPHPAwardsTeamHeader\">" . $AwardsLang['TeamName'] . "</th></thead>";
if (empty($HistoryYear) == false){while ($RowYear = $HistoryYear ->fetchArray()) { 

	$Query = "SELECT MainTable.*, PlayerInfoHistory.NHLID, PlayerInfoHistory.Team, PlayerInfoHistory.ProTeamName as TeamName  FROM (SELECT Player" . $TypeText . "StatHistory.P, Player" . $TypeText . "StatHistory.Name, Player" . $TypeText . "StatHistory.Number, Player" . $TypeText . "StatHistory.Year, Player" . $TypeText . "StatHistory.Playoff  FROM Player" . $TypeText . "StatHistory WHERE Playoff='False' and Year = '" . $RowYear['Year'] . "' ORDER BY Player" . $TypeText . "StatHistory.P DESC, Player" . $TypeText . "StatHistory.GP ASC LIMIT 1) AS MainTable LEFT JOIN PlayerInfoHistory ON (MainTable.Number = PlayerInfoHistory.Number) AND (MainTable.Year = PlayerInfoHistory.Year) AND (MainTable.Playoff = PlayerInfoHistory.Playoff)";
	$Result = $CareerStatdb->querySingle($Query,true);
	
	if (empty($Result) == false){
		echo "<tbody><tr><td>" . $RowYear['Year'] . "</td><td>";
		If ($Result['Number'] != Null){
			echo "<a href=\"PlayerReport.php?Player=" . $Result['Number'] . "\">" . $Result['Name'] . "</a>";
		}else{
			echo $Result['Name'];
		}
		echo " (" . $Result['P'] . " P)";
		If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $Result['NHLID'] != ""){
		echo "<div class=\"Headshot\"><img loading=\"lazy\" src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $Result['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $Result['Name']. "\" class=\"STHSPHPAwardsHeadshot\"></div>";}
		echo "</td><td>";
		If ($Result['Team'] != Null){	
			echo "<a href=\"ProTeam.php?Team=" . $Result['Team'] . "\">" . $Result['TeamName'] . "</a>";
		}else{
			echo $Result['TeamName'];
		}
		echo "</td></tr></tboby>\n";
	}

}}	If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS Art Ross Trophy : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}?>
</table>
<br>

<table class="STHSAward_Table"><thead><tr><th colspan="3" class="STHSPHPAwardsTitle"><?php echo $AwardsLang['MauriceRocketRichardTrophy'];?></th></tr>
<?php
echo "<th class=\"STHSPHPAwardsYearHeader\">" . $AwardsLang['Year'] . "</th><th class=\"STHSPHPAwardsPlayerHeader\">" . $AwardsLang['PlayerName'] . "</th><th class=\"STHSPHPAwardsTeamHeader\">" . $AwardsLang['TeamName'] . "</th></thead>";
if (empty($HistoryYear) == false){while ($RowYear = $HistoryYear ->fetchArray()) { 

	$Query = "SELECT MainTable.*, PlayerInfoHistory.NHLID, PlayerInfoHistory.Team, PlayerInfoHistory.ProTeamName as TeamName  FROM (SELECT Player" . $TypeText . "StatHistory.G, Player" . $TypeText . "StatHistory.Name, Player" . $TypeText . "StatHistory.Number, Player" . $TypeText . "StatHistory.Year, Player" . $TypeText . "StatHistory.Playoff  FROM Player" . $TypeText . "StatHistory WHERE Playoff='False' and Year = '" . $RowYear['Year'] . "' ORDER BY Player" . $TypeText . "StatHistory.G DESC, Player" . $TypeText . "StatHistory.GP ASC LIMIT 1) AS MainTable LEFT JOIN PlayerInfoHistory ON (MainTable.Number = PlayerInfoHistory.Number) AND (MainTable.Year = PlayerInfoHistory.Year) AND (MainTable.Playoff = PlayerInfoHistory.Playoff)";
	$Result = $CareerStatdb->querySingle($Query,true);
	
	if (empty($Result) == false){
		echo "<tbody><tr><td>" . $RowYear['Year'] . "</td><td>";
		If ($Result['Number'] != Null){
			echo "<a href=\"PlayerReport.php?Player=" . $Result['Number'] . "\">" . $Result['Name'] . "</a>";
		}else{
			echo $Result['Name'];
		}	
		echo " (" . $Result['G'] . " G)";
		If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $Result['NHLID'] != ""){
		echo "<div class=\"Headshot\"><img loading=\"lazy\" src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $Result['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $Result['Name']. "\" class=\"STHSPHPAwardsHeadshot\"></div>";}
		echo "</td><td>";
		If ($Result['Team'] != Null){	
			echo "<a href=\"ProTeam.php?Team=" . $Result['Team'] . "\">" . $Result['TeamName'] . "</a>";
		}else{
			echo $Result['TeamName'];
		}
		echo "</td></tr></tboby>\n";
	}

}}	If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS Maurice Rocket Richard Trophy : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}?>
</table>
<br>

<table class="STHSAward_Table"><thead><tr><th colspan="3" class="STHSPHPAwardsTitle"><?php echo $AwardsLang['VezinaTrophy'];?></th></tr>
<?php
echo "<th class=\"STHSPHPAwardsYearHeader\">" . $AwardsLang['Year'] . "</th><th class=\"STHSPHPAwardsPlayerHeader\">" . $AwardsLang['PlayerName'] . "</th><th class=\"STHSPHPAwardsTeamHeader\">" . $AwardsLang['TeamName'] . "</th></thead>";
if (empty($HistoryYear) == false){while ($RowYear = $HistoryYear ->fetchArray()) { 

	$Query = "SELECT filteredGoaler" . $TypeText . "StatHistory.StarPowerYear, filteredGoaler" . $TypeText . "StatHistory.Name, filteredGoaler" . $TypeText . "StatHistory.Number, filteredGoaler" . $TypeText . "StatHistory.Year, filteredGoaler" . $TypeText . "StatHistory.Playoff, filteredGoalerInfoHistory.NHLID, filteredGoalerInfoHistory.Team, filteredGoalerInfoHistory.TeamName FROM (SELECT Number, Year, Playoff, NHLID, Team, ProTeamName As TeamName FROM GoalerInfoHistory WHERE Playoff = 'False' AND Year = " . $RowYear['Year'] . ") AS filteredGoalerInfoHistory LEFT JOIN (SELECT StarPowerYear, Name, Number, Year, Playoff FROM Goaler" . $TypeText . "StatHistory WHERE Playoff = 'False' AND Year = " . $RowYear['Year'] . ") AS filteredGoaler" . $TypeText . "StatHistory ON (filteredGoaler" . $TypeText . "StatHistory.Number = filteredGoalerInfoHistory.Number) AND (filteredGoaler" . $TypeText . "StatHistory.Year = filteredGoalerInfoHistory.Year) AND (filteredGoaler" . $TypeText . "StatHistory.Playoff = filteredGoalerInfoHistory.Playoff) ORDER BY StarPowerYear DESC LIMIT 1";
	$Result = $CareerStatdb->querySingle($Query,true);
	
	if (empty($Result) == false){
		echo "<tbody><tr><td>" . $RowYear['Year'] . "</td><td>";
		If ($Result['Number'] != Null){
			echo "<a href=\"GoalieReport.php?Goalie=" . $Result['Number'] . "\">" . $Result['Name'] . "</a>";
		}else{
			echo $Result['Name'];
		}
		If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $Result['NHLID'] != ""){
		echo "<div class=\"Headshot\"><img loading=\"lazy\" src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $Result['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $Result['Name']. "\" class=\"STHSPHPAwardsHeadshot\"></div>";}
		echo "</td><td>";
		If ($Result['Team'] != Null){	
			echo "<a href=\"ProTeam.php?Team=" . $Result['Team'] . "\">" . $Result['TeamName'] . "</a>";
		}else{
			echo $Result['TeamName'];
		}
		echo "</td></tr></tboby>\n";
	}

}}	If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS Vezina Trophy : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}?>
</table>
<br>

<table class="STHSAward_Table"><thead><tr><th colspan="3" class="STHSPHPAwardsTitle"><?php echo $AwardsLang['JamesNorrisMemorialTrophy'];?></th></tr>
<?php
echo "<th class=\"STHSPHPAwardsYearHeader\">" . $AwardsLang['Year'] . "</th><th class=\"STHSPHPAwardsPlayerHeader\">" . $AwardsLang['PlayerName'] . "</th><th class=\"STHSPHPAwardsTeamHeader\">" . $AwardsLang['TeamName'] . "</th></thead>";
if (empty($HistoryYear) == false){while ($RowYear = $HistoryYear ->fetchArray()) { 

	$Query = "SELECT filteredPlayer" . $TypeText . "StatHistory.StarPowerYear, filteredPlayer" . $TypeText . "StatHistory.Name, filteredPlayer" . $TypeText . "StatHistory.Number, filteredPlayer" . $TypeText . "StatHistory.Year, filteredPlayer" . $TypeText . "StatHistory.Playoff, filteredPlayerInfoHistory.NHLID, filteredPlayerInfoHistory.Team, filteredPlayerInfoHistory.TeamName FROM (SELECT Number, Year, Playoff, NHLID, Team, ProTeamName As TeamName FROM PlayerInfoHistory WHERE Playoff = 'False' AND Year = " . $RowYear['Year'] . " AND PosD = 'True') AS filteredPlayerInfoHistory LEFT JOIN (SELECT StarPowerYear, Name, Number, Year, Playoff FROM Player" . $TypeText . "StatHistory WHERE Playoff = 'False' AND Year = " . $RowYear['Year'] . ") AS filteredPlayer" . $TypeText . "StatHistory ON (filteredPlayer" . $TypeText . "StatHistory.Number = filteredPlayerInfoHistory.Number) AND (filteredPlayer" . $TypeText . "StatHistory.Year = filteredPlayerInfoHistory.Year) AND (filteredPlayer" . $TypeText . "StatHistory.Playoff = filteredPlayerInfoHistory.Playoff) ORDER BY StarPowerYear DESC LIMIT 1";
	$Result = $CareerStatdb->querySingle($Query,true);
	
	if (empty($Result) == false){
		echo "<tbody><tr><td>" . $RowYear['Year'] . "</td><td>";
		If ($Result['Number'] != Null){
			echo "<a href=\"PlayerReport.php?Player=" . $Result['Number'] . "\">" . $Result['Name'] . "</a>";
		}else{
			echo $Result['Name'];
		}	
		If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $Result['NHLID'] != ""){
		echo "<div class=\"Headshot\"><img loading=\"lazy\" src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $Result['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $Result['Name']. "\" class=\"STHSPHPAwardsHeadshot\"></div>";}
		echo "</td><td>";
		If ($Result['Team'] != Null){	
			echo "<a href=\"ProTeam.php?Team=" . $Result['Team'] . "\">" . $Result['TeamName'] . "</a>";
		}else{
			echo $Result['TeamName'];
		}
		echo "</td></tr></tboby>\n";
	}

}}	If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"James Norris Memorial Trophy : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}?>
</table>
<br>

<table class="STHSAward_Table"><thead><tr><th colspan="3" class="STHSPHPAwardsTitle"><?php echo $AwardsLang['FrankJ.SelkeTrophy'];?></th></tr>
<?php
echo "<th class=\"STHSPHPAwardsYearHeader\">" . $AwardsLang['Year'] . "</th><th class=\"STHSPHPAwardsPlayerHeader\">" . $AwardsLang['PlayerName'] . "</th><th class=\"STHSPHPAwardsTeamHeader\">" . $AwardsLang['TeamName'] . "</th></thead>";
if (empty($HistoryYear) == false){while ($RowYear = $HistoryYear ->fetchArray()) { 

	$Query = "SELECT filteredPlayer" . $TypeText . "StatHistory.StarPowerYear, filteredPlayer" . $TypeText . "StatHistory.Name, filteredPlayer" . $TypeText . "StatHistory.Number, filteredPlayer" . $TypeText . "StatHistory.Year, filteredPlayer" . $TypeText . "StatHistory.Playoff, filteredPlayerInfoHistory.NHLID, filteredPlayerInfoHistory.Team, filteredPlayerInfoHistory.TeamName FROM (SELECT Number, Year, Playoff, NHLID, Team, ProTeamName As TeamName FROM PlayerInfoHistory WHERE Playoff = 'False' AND Year = " . $RowYear['Year'] . " AND PosD = 'False' AND DF > ((SC + PA)/2)) AS filteredPlayerInfoHistory LEFT JOIN (SELECT StarPowerYear, Name, Number, Year, Playoff FROM Player" . $TypeText . "StatHistory WHERE Playoff = 'False' AND Year = " . $RowYear['Year'] . ") AS filteredPlayer" . $TypeText . "StatHistory ON (filteredPlayer" . $TypeText . "StatHistory.Number = filteredPlayerInfoHistory.Number) AND (filteredPlayer" . $TypeText . "StatHistory.Year = filteredPlayerInfoHistory.Year) AND (filteredPlayer" . $TypeText . "StatHistory.Playoff = filteredPlayerInfoHistory.Playoff) ORDER BY StarPowerYear DESC LIMIT 1";
	$Result = $CareerStatdb->querySingle($Query,true);
	
	if (empty($Result) == false){
		echo "<tbody><tr><td>" . $RowYear['Year'] . "</td><td>";
		If ($Result['Number'] != Null){
			echo "<a href=\"PlayerReport.php?Player=" . $Result['Number'] . "\">" . $Result['Name'] . "</a>";
		}else{
			echo $Result['Name'];
		}	
		If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $Result['NHLID'] != ""){
		echo "<div class=\"Headshot\"><img loading=\"lazy\" src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $Result['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $Result['Name']. "\" class=\"STHSPHPAwardsHeadshot\"></div>";}
		echo "</td><td>";
		If ($Result['Team'] != Null){	
			echo "<a href=\"ProTeam.php?Team=" . $Result['Team'] . "\">" . $Result['TeamName'] . "</a>";
		}else{
			echo $Result['TeamName'];
		}
		echo "</td></tr></tboby>\n";
	}

}}	If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"Frank J.Selke Trophy : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}?>
</table>
<br>

<table class="STHSAward_Table"><thead><tr><th colspan="3" class="STHSPHPAwardsTitle"><?php echo $AwardsLang['ConnSmytheTrophy'];?></th></tr>
<?php
echo "<th class=\"STHSPHPAwardsYearHeader\">" . $AwardsLang['Year'] . "</th><th class=\"STHSPHPAwardsPlayerHeader\">" . $AwardsLang['PlayerName'] . "</th><th class=\"STHSPHPAwardsTeamHeader\">" . $AwardsLang['TeamName'] . "</th></thead>";
if (empty($HistoryYear) == false){while ($RowYear = $HistoryYear ->fetchArray()) { 

	$Query = "SELECT filteredPlayer" . $TypeText . "StatHistory.StarPowerYear, filteredPlayer" . $TypeText . "StatHistory.Name, filteredPlayer" . $TypeText . "StatHistory.Number, filteredPlayer" . $TypeText . "StatHistory.Year, filteredPlayer" . $TypeText . "StatHistory.Playoff, filteredPlayerInfoHistory.NHLID, filteredPlayerInfoHistory.Team, filteredPlayerInfoHistory.TeamName FROM (SELECT Number, Year, Playoff, NHLID, Team, ProTeamName As TeamName FROM PlayerInfoHistory WHERE Playoff = 'True' AND Year = " . $RowYear['Year'] . ") AS filteredPlayerInfoHistory LEFT JOIN (SELECT StarPowerYear, Name, Number, Year, Playoff FROM Player" . $TypeText . "StatHistory WHERE Playoff = 'True' AND Year = " . $RowYear['Year'] . ") AS filteredPlayer" . $TypeText . "StatHistory ON (filteredPlayer" . $TypeText . "StatHistory.Number = filteredPlayerInfoHistory.Number) AND (filteredPlayer" . $TypeText . "StatHistory.Year = filteredPlayerInfoHistory.Year) AND (filteredPlayer" . $TypeText . "StatHistory.Playoff = filteredPlayerInfoHistory.Playoff) UNION ALL SELECT filteredGoaler" . $TypeText . "StatHistory.StarPowerYear, filteredGoaler" . $TypeText . "StatHistory.Name, filteredGoaler" . $TypeText . "StatHistory.Number, filteredGoaler" . $TypeText . "StatHistory.Year, filteredGoaler" . $TypeText . "StatHistory.Playoff, filteredGoalerInfoHistory.NHLID, filteredGoalerInfoHistory.Team, filteredGoalerInfoHistory.TeamName FROM (SELECT Number, Year, Playoff, NHLID, Team, ProTeamName As TeamName FROM GoalerInfoHistory WHERE Playoff = 'True' AND Year = " . $RowYear['Year'] . ") AS filteredGoalerInfoHistory LEFT JOIN (SELECT StarPowerYear, Name, Number, Year, Playoff FROM Goaler" . $TypeText . "StatHistory WHERE Playoff = 'True' AND Year = " . $RowYear['Year'] . ") AS filteredGoaler" . $TypeText . "StatHistory ON (filteredGoaler" . $TypeText . "StatHistory.Number = filteredGoalerInfoHistory.Number) AND (filteredGoaler" . $TypeText . "StatHistory.Year = filteredGoalerInfoHistory.Year) AND (filteredGoaler" . $TypeText . "StatHistory.Playoff = filteredGoalerInfoHistory.Playoff) ORDER BY StarPowerYear DESC LIMIT 1";
	$Result = $CareerStatdb->querySingle($Query,true);
	
	if (empty($Result) == false){
		echo "<tbody><tr><td>" . $RowYear['Year'] . "</td><td>";
		If ($Result['Number'] != Null){
			If ($Result['PosG'] = 'False'){
				echo "<a href=\"PlayerReport.php?Player=" . $Result['Number'] . "\">" . $Result['Name'] . "</a>";
			}else{
				echo "<a href=\"GoalieReport.php?Goalie=" . $Result['Number'] . "\">" . $Result['Name'] . "</a>";
			}	
		}else{
			echo $Result['Name'];
		}
		If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $Result['NHLID'] != ""){
		echo "<div class=\"Headshot\"><img loading=\"lazy\" src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $Result['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $Result['Name']. "\" class=\"STHSPHPAwardsHeadshot\"></div>";}
		echo "</td><td>";
		If ($Result['Team'] != Null){	
			echo "<a href=\"ProTeam.php?Team=" . $Result['Team'] . "\">" . $Result['TeamName'] . "</a>";
		}else{
			echo $Result['TeamName'];
		}
		echo "</td></tr></tboby>\n";
	}

}}	If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"Conn Smythe Trophy : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}?>
</table>
<br>

<table class="STHSAward_Table"><thead><tr><th colspan="3" class="STHSPHPAwardsTitle"><?php echo $AwardsLang['TedLindsayAward'];?></th></tr>
<?php
echo "<th class=\"STHSPHPAwardsYearHeader\">" . $AwardsLang['Year'] . "</th><th class=\"STHSPHPAwardsPlayerHeader\">" . $AwardsLang['PlayerName'] . "</th><th class=\"STHSPHPAwardsTeamHeader\">" . $AwardsLang['TeamName'] . "</th></thead>";
if (empty($HistoryYear) == false){while ($RowYear = $HistoryYear ->fetchArray()) { 

	$Query = "SELECT filteredPlayer" . $TypeText . "StatHistory.TotalScore, filteredPlayer" . $TypeText . "StatHistory.StarPowerYear, filteredPlayer" . $TypeText . "StatHistory.Name, filteredPlayer" . $TypeText . "StatHistory.Number, filteredPlayer" . $TypeText . "StatHistory.Year, filteredPlayer" . $TypeText . "StatHistory.Playoff, filteredPlayerInfoHistory.NHLID, filteredPlayerInfoHistory.Team, filteredPlayerInfoHistory.TeamName FROM (SELECT Number, Year, Playoff, NHLID, Team, ProTeamName As TeamName FROM PlayerInfoHistory WHERE Playoff = 'False' AND Year = " . $RowYear['Year'] . ") AS filteredPlayerInfoHistory LEFT JOIN (SELECT ((G * 100) + (A * 50) + (ShotsBlock * 10) + StarPowerYear) AS TotalScore, StarPowerYear, Name, Number, Year, Playoff FROM Player" . $TypeText . "StatHistory WHERE Playoff = 'False' AND Year = " . $RowYear['Year'] . ") AS filteredPlayer" . $TypeText . "StatHistory ON (filteredPlayer" . $TypeText . "StatHistory.Number = filteredPlayerInfoHistory.Number) AND (filteredPlayer" . $TypeText . "StatHistory.Year = filteredPlayerInfoHistory.Year) AND (filteredPlayer" . $TypeText . "StatHistory.Playoff = filteredPlayerInfoHistory.Playoff) ORDER BY TotalScore DESC LIMIT 1";
	$Result = $CareerStatdb->querySingle($Query,true);
	
	if (empty($Result) == false){
		echo "<tbody><tr><td>" . $RowYear['Year'] . "</td><td>";
		If ($Result['Number'] != Null){
			echo "<a href=\"PlayerReport.php?Player=" . $Result['Number'] . "\">" . $Result['Name'] . "</a>";
		}else{
			echo $Result['Name'];
		}
		If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $Result['NHLID'] != ""){
		echo "<div class=\"Headshot\"><img loading=\"lazy\" src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $Result['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $Result['Name']. "\" class=\"STHSPHPAwardsHeadshot\"></div>";}
		echo "</td><td>";
		If ($Result['Team'] != Null){	
			echo "<a href=\"ProTeam.php?Team=" . $Result['Team'] . "\">" . $Result['TeamName'] . "</a>";
		}else{
			echo $Result['TeamName'];
		}
		echo "</td></tr></tboby>\n";
	}

}}	If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"Ted Lindsay Award : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}?>
</table>
<br>

<table class="STHSAward_Table"><thead><tr><th colspan="3" class="STHSPHPAwardsTitle"><?php echo $AwardsLang['CalderMemorialTrophy'];?></th></tr>
<?php
echo "<th class=\"STHSPHPAwardsYearHeader\">" . $AwardsLang['Year'] . "</th><th class=\"STHSPHPAwardsPlayerHeader\">" . $AwardsLang['PlayerName'] . "</th><th class=\"STHSPHPAwardsTeamHeader\">" . $AwardsLang['TeamName'] . "</th></thead>";
if (empty($HistoryYear) == false){while ($RowYear = $HistoryYear ->fetchArray()) { 

	$Query = "SELECT filteredPlayer" . $TypeText . "StatHistory.StarPowerYear, filteredPlayer" . $TypeText . "StatHistory.Name, filteredPlayer" . $TypeText . "StatHistory.Number, filteredPlayer" . $TypeText . "StatHistory.Year, filteredPlayer" . $TypeText . "StatHistory.Playoff, filteredPlayerInfoHistory.NHLID, filteredPlayerInfoHistory.Team, filteredPlayerInfoHistory.TeamName FROM (SELECT Number, Year, Playoff, NHLID, Team, ProTeamName As TeamName FROM PlayerInfoHistory WHERE Playoff = 'False' AND Year = " . $RowYear['Year'] . " AND Rookie = 'True') AS filteredPlayerInfoHistory LEFT JOIN (SELECT StarPowerYear, Name, Number, Year, Playoff FROM Player" . $TypeText . "StatHistory WHERE Playoff = 'False' AND Year = " . $RowYear['Year'] . ") AS filteredPlayer" . $TypeText . "StatHistory ON (filteredPlayer" . $TypeText . "StatHistory.Number = filteredPlayerInfoHistory.Number) AND (filteredPlayer" . $TypeText . "StatHistory.Year = filteredPlayerInfoHistory.Year) AND (filteredPlayer" . $TypeText . "StatHistory.Playoff = filteredPlayerInfoHistory.Playoff) UNION ALL SELECT filteredGoaler" . $TypeText . "StatHistory.StarPowerYear, filteredGoaler" . $TypeText . "StatHistory.Name, filteredGoaler" . $TypeText . "StatHistory.Number, filteredGoaler" . $TypeText . "StatHistory.Year, filteredGoaler" . $TypeText . "StatHistory.Playoff, filteredGoalerInfoHistory.NHLID, filteredGoalerInfoHistory.Team, filteredGoalerInfoHistory.TeamName FROM (SELECT Number, Year, Playoff, NHLID, Team, ProTeamName As TeamName FROM GoalerInfoHistory WHERE Playoff = 'False' AND Year = " . $RowYear['Year'] . " AND Rookie = 'True') AS filteredGoalerInfoHistory LEFT JOIN (SELECT StarPowerYear, Name, Number, Year, Playoff FROM Goaler" . $TypeText . "StatHistory WHERE Playoff = 'False' AND Year = " . $RowYear['Year'] . ") AS filteredGoaler" . $TypeText . "StatHistory ON (filteredGoaler" . $TypeText . "StatHistory.Number = filteredGoalerInfoHistory.Number) AND (filteredGoaler" . $TypeText . "StatHistory.Year = filteredGoalerInfoHistory.Year) AND (filteredGoaler" . $TypeText . "StatHistory.Playoff = filteredGoalerInfoHistory.Playoff) ORDER BY StarPowerYear DESC LIMIT 1";
	$Result = $CareerStatdb->querySingle($Query,true);
	
	if (empty($Result) == false){
		echo "<tbody><tr><td>" . $RowYear['Year'] . "</td><td>";
		If ($Result['Number'] != Null){
			echo "<a href=\"PlayerReport.php?Player=" . $Result['Number'] . "\">" . $Result['Name'] . "</a>";
		}else{
			echo $Result['Name'];
		}	
		If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $Result['NHLID'] != ""){
		echo "<div class=\"Headshot\"><img loading=\"lazy\" src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $Result['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $Result['Name']. "\" class=\"STHSPHPAwardsHeadshot\"></div>";}
		echo "</td><td>";
		If ($Result['Team'] != Null){	
			echo "<a href=\"ProTeam.php?Team=" . $Result['Team'] . "\">" . $Result['TeamName'] . "</a>";
		}else{
			echo $Result['TeamName'];
		}
		echo "</td></tr></tboby>\n";
	}

}}	If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"Calder Memorial Trophy : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}?>
</table>
<br>

<table class="STHSAward_Table"><thead><tr><th colspan="3" class="STHSPHPAwardsTitle"><?php echo $AwardsLang['LadyByngMemorialTrophy'];?></th></tr>
<?php
echo "<th class=\"STHSPHPAwardsYearHeader\">" . $AwardsLang['Year'] . "</th><th class=\"STHSPHPAwardsPlayerHeader\">" . $AwardsLang['PlayerName'] . "</th><th class=\"STHSPHPAwardsTeamHeader\">" . $AwardsLang['TeamName'] . "</th></thead>";
if (empty($HistoryYear) == false){while ($RowYear = $HistoryYear ->fetchArray()) { 

	$Query = "SELECT " . $TypeText . "ScheduleGamePerTeam AS ScheduleGamePerTeam FROM LeagueGeneral WHERE Playoff = 'False' AND Year =" . $RowYear['Year'];
	$LeagueGeneralTrophy = $CareerStatdb->querySingle($Query,true);

	$Query = "SELECT filteredPlayer" . $TypeText . "StatHistory.StarPowerYear, filteredPlayer" . $TypeText . "StatHistory.Name, filteredPlayer" . $TypeText . "StatHistory.Number, filteredPlayer" . $TypeText . "StatHistory.Year, filteredPlayer" . $TypeText . "StatHistory.Playoff, filteredPlayerInfoHistory.NHLID, filteredPlayerInfoHistory.Team, filteredPlayerInfoHistory.TeamName FROM (SELECT Number, Year, Playoff, NHLID, Team, ProTeamName As TeamName FROM PlayerInfoHistory WHERE Playoff = 'False' AND Year = " . $RowYear['Year'] . ") AS filteredPlayerInfoHistory LEFT JOIN (SELECT StarPowerYear, Name, Number, Year, Playoff FROM Player" . $TypeText . "StatHistory WHERE Playoff = 'False' AND Year = " . $RowYear['Year'] . " AND SecondPlay > (" . $LeagueGeneralTrophy['ScheduleGamePerTeam'] . " * 600) ORDER BY PIM LIMIT 50) AS filteredPlayer" . $TypeText . "StatHistory ON (filteredPlayer" . $TypeText . "StatHistory.Number = filteredPlayerInfoHistory.Number) AND (filteredPlayer" . $TypeText . "StatHistory.Year = filteredPlayerInfoHistory.Year) AND (filteredPlayer" . $TypeText . "StatHistory.Playoff = filteredPlayerInfoHistory.Playoff) ORDER BY StarPowerYear DESC LIMIT 1";
	$Result = $CareerStatdb->querySingle($Query,true);
	
	if (empty($Result) == false){
		echo "<tbody><tr><td>" . $RowYear['Year'] . "</td><td>";
		If ($Result['Number'] != Null){
			echo "<a href=\"PlayerReport.php?Player=" . $Result['Number'] . "\">" . $Result['Name'] . "</a>";
		}else{
			echo $Result['Name'];
		}	
		If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $Result['NHLID'] != ""){
		echo "<div class=\"Headshot\"><img loading=\"lazy\" src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $Result['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $Result['Name']. "\" class=\"STHSPHPAwardsHeadshot\"></div>";}
		echo "</td><td>";
		If ($Result['Team'] != Null){	
			echo "<a href=\"ProTeam.php?Team=" . $Result['Team'] . "\">" . $Result['TeamName'] . "</a>";
		}else{
			echo $Result['TeamName'];
		}
		echo "</td></tr></tboby>\n";
	}

}}	If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"Lady Byng Memorial Trophy : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}?>
</table>
<br>

<table class="STHSAward_Table"><thead><tr><th colspan="3" class="STHSPHPAwardsTitle"><?php echo $AwardsLang['WilliamM.JenningsTrophy'];?></th></tr>
<?php
echo "<th class=\"STHSPHPAwardsYearHeader\">" . $AwardsLang['Year'] . "</th><th class=\"STHSPHPAwardsPlayerHeader\">" . $AwardsLang['PlayerName'] . "</th><th class=\"STHSPHPAwardsTeamHeader\">" . $AwardsLang['TeamName'] . "</th></thead>";
if (empty($HistoryYear) == false){while ($RowYear = $HistoryYear ->fetchArray()) { 
	/* Complex Query Because Multiple Team can have the same Goal Against at the end of the year */
	$Query = "WITH MinGA AS (SELECT MIN(GA) AS MinValue FROM TeamProStatHistory WHERE Playoff = 'False' AND Year = " . $RowYear['Year'] . ") SELECT GA, Name, Number FROM TeamProStatHistory WHERE Playoff = 'False' AND Year = " . $RowYear['Year'] . " AND GA = (SELECT MinValue FROM MinGA)";
	$LeagueGeneralTrophy3 = $CareerStatdb->query($Query);
	$TeamQuery = "";
	$TeamGA = "";
	if (empty($LeagueGeneralTrophy3) == false){while ($Row = $LeagueGeneralTrophy3 ->fetchArray()) {
		If ($TeamQuery == ""){
			$TeamQuery = $Row['Name'];
		}else{
			$TeamQuery = $TeamQuery . "' OR TeamName = '" . $Row['Name'];
		}
		$TeamGA = $Row['GA'];
	}}
	
	$Query = "SELECT " . $TypeText . "ScheduleGamePerTeam AS ScheduleGamePerTeam FROM LeagueGeneral WHERE Playoff = 'False' AND Year =" . $RowYear['Year'];
	$LeagueGeneralTrophy2 = $CareerStatdb->querySingle($Query,true);

	$Query = "SELECT filteredGoaler" . $TypeText . "StatHistory.StarPowerYear, filteredGoaler" . $TypeText . "StatHistory.Name, filteredGoaler" . $TypeText . "StatHistory.Number, filteredGoaler" . $TypeText . "StatHistory.Year, filteredGoaler" . $TypeText . "StatHistory.Playoff, filteredGoalerInfoHistory.NHLID, filteredGoalerInfoHistory.Team, filteredGoalerInfoHistory.TeamName FROM (SELECT Number, Year, Playoff, NHLID, Team, ProTeamName As TeamName FROM GoalerInfoHistory WHERE Playoff = 'False' AND Year = " . $RowYear['Year'] . ") AS filteredGoalerInfoHistory INNER JOIN (SELECT StarPowerYear, Name, Number, Year, Playoff FROM Goaler" . $TypeText . "StatHistory WHERE Playoff = 'False' AND Year = " . $RowYear['Year'] . " AND GP > " . ($LeagueGeneralTrophy2['ScheduleGamePerTeam'] / 4) . ") AS filteredGoaler" . $TypeText . "StatHistory ON (filteredGoaler" . $TypeText . "StatHistory.Number = filteredGoalerInfoHistory.Number) AND (filteredGoaler" . $TypeText . "StatHistory.Year = filteredGoalerInfoHistory.Year) AND (filteredGoaler" . $TypeText . "StatHistory.Playoff = filteredGoalerInfoHistory.Playoff) WHERE TeamName = '" . $TeamQuery  ."' ORDER BY StarPowerYear DESC";
	$Result = $CareerStatdb->query($Query);
	echo "<tbody><tr><td>" . $RowYear['Year'] . "</td><td>";
	
	if (empty($Result) == false){while ($Row = $Result ->fetchArray()) {
		If ($Row['Number'] != Null){
			echo "<a href=\"GoalieReport.php?Goalie=" . $Row['Number'] . "\">" . $Row['Name'] . "</a>";
		}else{
			echo $Row['Name'];
		}
		If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $Row['NHLID'] != ""){
		echo "<div class=\"Headshot\"><img loading=\"lazy\" src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $Row['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $Row['Name']. "\" class=\"STHSPHPAwardsHeadshot\"></div>";}
		echo "<br><br>";
	}}
	echo "</td><td>";
	if (empty($LeagueGeneralTrophy3) == false){while ($Row = $LeagueGeneralTrophy3 ->fetchArray()) {
		If ($Row['Number'] != Null){	
			echo "<a href=\"ProTeam.php?Team=" . $Row['Number'] . "\">" . $Row['Name'] . "</a>";
		}else{
			echo $Row['Name'];
		}		
		echo " (" . $TeamGA . " GA)<br><br>";
	}}	
	echo "</td></tr></tboby>\n";

}}	If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"William M. Jennings Trophy : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}?>
</table>
<br>

<table class="STHSAward_Table"><thead><tr><th colspan="2" class="STHSPHPAwardsTitle"><?php echo $AwardsLang['PresidentsTrophy'];?></th></tr>
<?php
echo "<th class=\"STHSPHPAwardsYearHeader\">" . $AwardsLang['Year'] . "</th><th class=\"STHSPHPAwardsTeamHeader\">" . $AwardsLang['TeamName'] . "</th></thead>";
if (empty($HistoryYear) == false){while ($RowYear = $HistoryYear ->fetchArray()) { 

	$Query = "Select " . $TypeText . "ConferenceName1 AS ConferenceName1," . $TypeText . "ConferenceName2 AS ConferenceName2 FROM LeagueGeneral WHERE Year = " . $RowYear['Year']. " And Playoff = 'False'";
	$LeagueGeneralTrophy = $CareerStatdb->querySingle($Query,true);

	$Query = "SELECT Team" . $TypeText . "StatHistory.*, Team" . $TypeText . "InfoHistory.Conference, Team" . $TypeText . "InfoHistory.Division, RankingOrder.Type FROM (Team" . $TypeText . "StatHistory INNER JOIN Team" . $TypeText . "InfoHistory ON Team" . $TypeText . "StatHistory.Number = Team" . $TypeText . "InfoHistory.Number) INNER JOIN RankingOrder ON Team" . $TypeText . "StatHistory.Number = RankingOrder.Team" . $TypeText . "Number WHERE (((RankingOrder.Type)=0)) AND Team" . $TypeText . "StatHistory.Year = " . $RowYear['Year'] . " And Team" . $TypeText . "StatHistory.Playoff = 'False' AND Team" . $TypeText . "InfoHistory.Year = " . $RowYear['Year'] . " And Team" . $TypeText . "InfoHistory.Playoff = 'False' AND RankingOrder.Year = " . $RowYear['Year'] . " And RankingOrder.Playoff = 'False' ORDER BY RankingOrder.TeamOrder LIMIT 1";
	$Result = $CareerStatdb->querySingle($Query,true);

	if (empty($Result) == false){
		echo "<tbody><tr><td>" . $RowYear['Year'] . "</td><td>";
		If ($Result['Number'] != Null){	
			echo "<a href=\"ProTeam.php?Team=" . $Result['Number'] . "\">" . $Result['Name'] . "</a>";
		}else{
			echo $Result['Name'];
		}
		echo "</td></tr></tboby>\n";
	}

}}	If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"Presidents Trophy : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}?>
</table>
<br>

<table class="STHSAward_Table"><thead><tr><th colspan="2" class="STHSPHPAwardsTitle"><?php echo $AwardsLang['PrinceofWalesTrophy'];?></th></tr>
<?php
echo "<th class=\"STHSPHPAwardsYearHeader\">" . $AwardsLang['Year'] . "</th><th class=\"STHSPHPAwardsTeamHeader\">" . $AwardsLang['TeamName'] . "</th></thead>";
if (empty($HistoryYear) == false){while ($RowYear = $HistoryYear ->fetchArray()) { 

	$Query = "Select " . $TypeText . "ConferenceName1 AS ConferenceName1," . $TypeText . "ConferenceName2 AS ConferenceName2 FROM LeagueGeneral WHERE Year = " . $RowYear['Year']. " And Playoff = 'True'";
	$LeagueGeneralTrophy = $CareerStatdb->querySingle($Query,true);
	
	$Query = "SELECT Team" . $TypeText . "StatHistory.*, Team" . $TypeText . "InfoHistory.* FROM (Team" . $TypeText . "StatHistory INNER JOIN Team" . $TypeText . "InfoHistory ON Team" . $TypeText . "StatHistory.Number = Team" . $TypeText . "InfoHistory.Number) WHERE ((Team" . $TypeText . "InfoHistory.Conference)=\"" . $LeagueGeneralTrophy['ConferenceName1'] . "\") AND Team" . $TypeText . "StatHistory.Year = " . $RowYear['Year'] . " And Team" . $TypeText . "StatHistory.Playoff = 'True' AND Team" . $TypeText . "InfoHistory.Year = " . $RowYear['Year'] . " AND Team" . $TypeText . "InfoHistory.Playoff = 'True' ORDER By Points DESC, GP DESC LIMIT 1";
	$Result = $CareerStatdb->querySingle($Query,true);

	if (empty($Result) == false){
		echo "<tbody><tr><td>" . $RowYear['Year'] . "</td><td>";
		If ($Result['Number'] != Null){	
			echo "<a href=\"ProTeam.php?Team=" . $Result['Number'] . "\">" . $Result['Name'] . "</a>";
		}else{
			echo $Result['Name'];
		}
		echo "</td></tr></tboby>\n";
	}

}}	If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"Prince of Wales Trophy : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}?>
</table>
<br>

<table class="STHSAward_Table"><thead><tr><th colspan="2" class="STHSPHPAwardsTitle"><?php echo $AwardsLang['ClarenceSCampbellBowl'];?></th></tr>
<?php
echo "<th class=\"STHSPHPAwardsYearHeader\">" . $AwardsLang['Year'] . "</th><th class=\"STHSPHPAwardsTeamHeader\">" . $AwardsLang['TeamName'] . "</th></thead>";
if (empty($HistoryYear) == false){while ($RowYear = $HistoryYear ->fetchArray()) { 

	$Query = "Select " . $TypeText . "ConferenceName1 AS ConferenceName1," . $TypeText . "ConferenceName2 AS ConferenceName2 FROM LeagueGeneral WHERE Year = " . $RowYear['Year']. " And Playoff = 'True'";
	$LeagueGeneralTrophy = $CareerStatdb->querySingle($Query,true);
	
	$Query = "SELECT Team" . $TypeText . "StatHistory.*, Team" . $TypeText . "InfoHistory.* FROM (Team" . $TypeText . "StatHistory INNER JOIN Team" . $TypeText . "InfoHistory ON Team" . $TypeText . "StatHistory.Number = Team" . $TypeText . "InfoHistory.Number) WHERE ((Team" . $TypeText . "InfoHistory.Conference)=\"" . $LeagueGeneralTrophy['ConferenceName2'] . "\") AND Team" . $TypeText . "StatHistory.Year = " . $RowYear['Year'] . " And Team" . $TypeText . "StatHistory.Playoff = 'True' AND Team" . $TypeText . "InfoHistory.Year = " . $RowYear['Year'] . " AND Team" . $TypeText . "InfoHistory.Playoff = 'True' ORDER By Points DESC, GP DESC LIMIT 1";
	$Result = $CareerStatdb->querySingle($Query,true);

	if (empty($Result) == false){
		echo "<tbody><tr><td>" . $RowYear['Year'] . "</td><td>";
		If ($Result['Number'] != Null){	
			echo "<a href=\"ProTeam.php?Team=" . $Result['Number'] . "\">" . $Result['Name'] . "</a>";
		}else{
			echo $Result['Name'];
		}
		echo "</td></tr></tboby>\n";
	}

}}	If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"Clarence S Campbell Bowl : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}?>
</table>
<br>
<?php
if ($TypeText == "Farm"){
	echo "<div class=\"STHSCenter\"><br ><a href=\"Awards.php?Pro\" class=\"SubmitButton\">" . $AwardsLang['ProAwards'] . "</a></div>";
}else{
	echo "<div class=\"STHSCenter\"><br ><a href=\"Awards.php?Farm\" class=\"SubmitButton\">" . $AwardsLang['FarmAwards'] . "</a></div>";
}
?>
</div>

<?php include "Footer.php";?>
