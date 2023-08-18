<?php include "Header.php";
If ($lang == "fr"){include 'LanguageFR-League.php';}else{include 'LanguageEN-League.php';}
If ($lang == "fr"){include 'LanguageFR-Stat.php';}else{include 'LanguageEN-Stat.php';}
$TypeText = (string)"Pro";$TitleType = $DynamicTitleLang['Pro'];
$TypeTextTeam = (string)"Pro";
$Playoff = (boolean)False;
$PlayoffString = (string)"False";
$Title = (string)"";
$DatabaseFound = (boolean)False;
$Search = (boolean)False;
$LeagueOutputOption = Null;
$ColumnPerTable = 14;
$Playoff = (boolean)False;
$Year = (integer)0;	
If (file_exists($CareerStatDatabaseFile) == false){
	Goto STHSErrorHistoryStanding;
}else{try{
	$DatabaseFound = True;
	$Title = (string)"";
	$LeagueName = (string)"";
	if(isset($_GET['Farm'])){$TypeText = "Farm";$TypeTextTeam = (string)"Farm";$TitleType = $DynamicTitleLang['Farm'];}
	if(isset($_GET['Playoff'])){$Playoff = True;$PlayoffString="True";}
	if(isset($_GET['Year'])){$Year = filter_var($_GET['Year'], FILTER_SANITIZE_NUMBER_INT);} 
		
	$db = new SQLite3($CareerStatDatabaseFile);
	$CareerDBFormatV2CheckCheck = $db->querySingle("SELECT Count(name) AS CountName FROM sqlite_master WHERE type='table' AND name='LeagueGeneral'",true);
	If ($Year > 0 AND $CareerDBFormatV2CheckCheck['CountName'] == 1){
	
		$Query = "Select Name, PointSystemW, PointSystemSO, " . $TypeText . "ConferenceName1 AS ConferenceName1," . $TypeText . "ConferenceName2 AS ConferenceName2," . $TypeText . "DivisionName1 AS DivisionName1," . $TypeText . "DivisionName2 AS DivisionName2," . $TypeText . "DivisionName3 AS DivisionName3," . $TypeText . "DivisionName4 AS DivisionName4," . $TypeText . "DivisionName5 AS DivisionName5," . $TypeText . "DivisionName6 AS DivisionName6," . $TypeText . "HowManyPlayOffTeam AS HowManyPlayOffTeam," . $TypeText . "DivisionNewNHLPlayoff  AS DivisionNewNHLPlayoff,PlayOffWinner" . $TypeText . " AS PlayOffWinner, PlayOffStarted, PlayOffRound FROM LeagueGeneral WHERE Year = " . $Year . " And Playoff = '" . $PlayoffString . "'";
		$LeagueGeneral = $db->querySingle($Query,true);		
		$Query = "Select StandardStandingOutput From LeagueOutputOption WHERE Year = " . $Year . " And Playoff = '" . $PlayoffString . "'";
		$LeagueOutputOption = $db->querySingle($Query,true);

		//Confirm Valid Data Found
		$CareerDBFormatV2CheckCheck = $db->querySingle("Select Count(Name) As CountName from LeagueGeneral  WHERE Year = " . $Year . " And Playoff = '" . $PlayoffString . "'",true);
		If ($CareerDBFormatV2CheckCheck['CountName'] == 1){
			$LeagueName = $LeagueGeneral['Name'];
		}else{
			Goto RegularCode;
		}
		
		$Conference = array($LeagueGeneral['ConferenceName1'], $LeagueGeneral['ConferenceName2']);
		$Division = array($LeagueGeneral['DivisionName1'], $LeagueGeneral['DivisionName2'], $LeagueGeneral['DivisionName3'], $LeagueGeneral['DivisionName4'], $LeagueGeneral['DivisionName5'], $LeagueGeneral['DivisionName6']);
		
		$Query = "Select " . $TypeText . "TwoConference AS TwoConference from LeagueSimulation WHERE Year = " . $Year . " And Playoff = '" . $PlayoffString . "'";
		$LeagueSimulation = $db->querySingle($Query,true);		
		
		If ($LeagueOutputOption['StandardStandingOutput'] == "False"){
			$ColumnPerTable = 17;
			If ($LeagueGeneral['PointSystemSO'] == "False"){$ColumnPerTable = $ColumnPerTable -1;}
		}
		
		If ($Playoff=="True"){$Title = $SearchLang['Playoff'] .  " ";}
		$Title = $Title . $DynamicTitleLang['PreviousStanding'];
		If ($Year != ""){$Title = $Title . $Year . " - ";}
		$Title = $Title . " " . $TitleType;
	}else{
		RegularCode:
		$dbLive = new SQLite3($DatabaseFile);
		$Query = "Select Name, PointSystemW, PointSystemSO, " . $TypeText . "ConferenceName1 AS ConferenceName1," . $TypeText . "ConferenceName2 AS ConferenceName2," . $TypeText . "DivisionName1 AS DivisionName1," . $TypeText . "DivisionName2 AS DivisionName2," . $TypeText . "DivisionName3 AS DivisionName3," . $TypeText . "DivisionName4 AS DivisionName4," . $TypeText . "DivisionName5 AS DivisionName5," . $TypeText . "DivisionName6 AS DivisionName6," . $TypeText . "HowManyPlayOffTeam AS HowManyPlayOffTeam," . $TypeText . "DivisionNewNHLPlayoff  AS DivisionNewNHLPlayoff,PlayOffWinner" . $TypeText . " AS PlayOffWinner, PlayOffStarted, PlayOffRound FROM LeagueGeneral";
		$LeagueGeneral = $dbLive->querySingle($Query,true);		
		$LeagueName = $LeagueGeneral['Name'];
		$Query = "Select StandardStandingOutput From LeagueOutputOption";
		$LeagueOutputOption = $dbLive->querySingle($Query,true);		
		$Conference = array($LeagueGeneral['ConferenceName1'], $LeagueGeneral['ConferenceName2']);
		$Division = array($LeagueGeneral['DivisionName1'], $LeagueGeneral['DivisionName2'], $LeagueGeneral['DivisionName3'], $LeagueGeneral['DivisionName4'], $LeagueGeneral['DivisionName5'], $LeagueGeneral['DivisionName6']);
		$Title = $NoHistoryData;
		$DatabaseFound = (boolean)False;		
	}
} catch (Exception $e) {
STHSErrorHistoryStanding:
	$DatabaseFound = False;
	$LeagueName = $DatabaseNotFound;
	$Standing = Null;
	$LeagueGeneral = Null;
	echo "<title>" . $DatabaseNotFound . "</title>";
	$Title = $DatabaseNotFound;
}}
echo "<title>" . $Title . "</title>";

function PrintStandingTop($TeamLang, $StandardStandingOutput, $PointSystemSO) {
echo "<table class=\"tablesorter STHSPHPStanding_Table\"><thead><tr>";
echo "<th title=\"Position\" class=\"STHSW35\">PO</th>";
echo "<th title=\"Team Name\" class=\"STHSW200\">" . $TeamLang['TeamName'] ."</th>";
echo "<th title=\"Games Played\" class=\"STHSW30\">GP</th>";
If ($StandardStandingOutput == "True"){
	echo "<th title=\"Wins\" class=\"STHSW30\">W</th>";
	echo "<th title=\"Loss\" class=\"STHSW30\">L</th>";
	echo "<th title=\"Overtime Loss\" class=\"STHSW30\">OTL</th>";
}else{
	echo "<th title=\"Wins\" class=\"STHSW30\">W</th>";
	echo "<th title=\"Loss\" class=\"STHSW30\">L</th>";
	if ($PointSystemSO == "False"){echo "<th title=\"Ties\" class=\"STHSW30\">T</th>";}
	echo "<th title=\"Overtime Wins\" class=\"STHSW30\">OTW</th>";
	echo "<th title=\"Overtime Loss\" class=\"STHSW30\">OTL</th>";
	if ($PointSystemSO == "True"){	
		echo "<th title=\"Shutouts Wins\" class=\"STHSW30\">SOW</th>";
		echo "<th title=\"Shutouts Loss\" class=\"STHSW30\">SOL</th>";	
	}
}
echo "<th title=\"Points\" class=\"STHSW30\">P</th>";
echo "<th title=\"Normal Wins + Overtime Win\" class=\"STHSW30\">ROW</th>";
echo "<th title=\"Goals For\" class=\"STHSW30\">GF</th>";
echo "<th title=\"Goals Against\" class=\"STHSW30\">GA</th>";
echo "<th title=\"Goals For Diffirencial against Goals Against\" class=\"STHSW30\">Diff</th>";
echo "<th title=\"Points Percentage\" class=\"STHSW45\">PCT</th>";																  
echo "<th title=\"Home Only\" class=\"STHSW75\">" . $TeamLang['Home'] ."</th>";
echo "<th title=\"Visitor Only\" class=\"STHSW75\">" . $TeamLang['Visitor'] ."</th>";
echo "</tr></thead><tbody>";
}

Function PrintStandingTable($Standing, $TypeText, $StandardStandingOutput, $PointSystemSO, $PointSystemW, $ColumnPerTable, $LinesNumber,$DatabaseFile){
$LoopCount =0;
while ($row = $Standing ->fetchArray()) {
	$LoopCount +=1;
	PrintStandingTableRow($row, $TypeText, $StandardStandingOutput, $PointSystemSO, $PointSystemW, $LoopCount,$DatabaseFile);
	If ($LoopCount > 0 AND $LoopCount == $LinesNumber){echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"" . $ColumnPerTable . "\"><hr /></td></tr>";}
}
echo "</tbody></table>";
}

Function PrintStandingTableRow($row, $TypeText, $StandardStandingOutput, $PointSystemSO, $PointSystemW, $LoopCount,$DatabaseFile){
	echo "<tr><td>" . $LoopCount . "</td>";
	echo "<td><span class=\"" . $TypeText . "Standing_Team" . $LoopCount . "\"></span>";
	echo "<a href=\"" . $TypeText . "Team.php?Team=" . $LoopCount . "\">" . $row['Name'] . "</a></td>";
	echo "<td>" . $row['GP'] . "</td>";
	If ($StandardStandingOutput == "True"){
		echo "<td>" . ($row['W'] + $row['OTW'] + $row['SOW']) . "</td>";
		echo "<td>" . $row['L'] . "</td>";
		echo "<td>" . ($row['OTL'] + $row['SOL']) . "</td>";
	}else{		
		echo "<td>" . $row['W'] . "</td>";
		echo "<td>" . $row['L'] . "</td>";
		if ($PointSystemSO == "False"){echo "<td>" . $row['T'] . "</td>";}
		echo "<td>" . $row['OTW'] . "</td>";
		echo "<td>" . $row['OTL'] . "</td>";
		if ($PointSystemSO == "True"){	
			echo "<td>" . $row['SOW'] . "</td>";
			echo "<td>" . $row['SOL'] . "</td>";
		}	
	}
	echo "<td><strong>" . $row['Points'] . "</strong></td>";	
	echo "<td>" . ($row['W'] + $row['OTW']) . "</td>";		
	echo "<td>" . $row['GF'] . "</td>";
	echo "<td>" . $row['GA'] . "</td>";
	echo "<td>" . ($row['GF'] - $row['GA']) . "</td>";
	if ($row['GP'] > 0 AND $PointSystemW > 0){echo "<td>" . number_Format(($row['Points'] / ($row['GP'] * $PointSystemW)),3) . "</td>";}else{echo "<td>" . number_Format("0",3) . "</td>";}	
	echo "<td>" . ($row['HomeW'] + $row['HomeOTW'] + $row['HomeSOW'])."-".$row['HomeL']."-".($row['HomeOTL']+$row['HomeSOL']) . "</td>";
	echo "<td>" . ($row['W'] + $row['OTW'] + $row['SOW'] - $row['HomeW'] - $row['HomeOTW'] - $row['HomeSOW'])."-".($row['L'] - $row['HomeL'])."-".($row['OTL']+$row['SOL']-$row['HomeOTL']-$row['HomeSOL']) . "</td>";
	echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
}

?>

<style>
@media screen and (max-width: 890px) {
.STHSWarning {display:block;}
.STHSPHPStanding_Table thead th:nth-last-child(1){display:none;}
.STHSPHPStanding_Table tbody td:nth-last-child(1){display:none;}
.STHSPHPStanding_Table thead th:nth-last-child(2){display:none;}
.STHSPHPStanding_Table tbody td:nth-last-child(2){display:none;}
}
.STHSPHPStanding_Table tbody td.staticTD {font-size:9pt;border-right:hidden; border-left:hidden;}
<?php 
If ($Year == 0){echo "#ReQueryDiv{display: block;}";}else{echo "#ReQueryDiv{display: none;}";}
if ($Playoff == True){
	echo "#tabmain1{display:none;}\n";
	echo "#tabmain2{display:none;}\n";
	echo "#tabmain3{display:none;}\n";
	echo "#tabmain4{display:none;}\n";
}else{
	echo "#tabmain5{display:none;}\n";
}?>
</style>

</head><body>
<?php include "Menu.php";?>
<div class="STHSWarning"><?php echo $WarningResolution;?><br /></div>
<div style="width:99%;margin:auto;">
<?php echo "<h1>" . $Title . "</h1>"; ?>
<div id="ReQueryDiv">
<?php include "SearchHistorySub.php";include "SearchHistoryStanding.php";?>
</div>
<div class="tablesorter_ColumnSelectorWrapper">
	<button class="tablesorter_Output" id="ReQuery"><?php echo $SearchLang['ChangeSearch'];?></button>
</div>
<div class="tabsmain standard"><ul class="tabmain-links">
<?php
if ($Playoff == True OR isset($LeagueGeneral) == False){
	echo "<li><a class=\"activemain\" href=\"#tabmain5\">" . $StandingLang['Playoff'] . "</a></li>";
}else{
	If ($LeagueGeneral['DivisionNewNHLPlayoff'] == "True"){
		echo "<li class=\"activemain\"><a href=\"#tabmain1\">" . $StandingLang['Wildcard'] . "</a></li>";
		echo "<li><a href=\"#tabmain2\">" . $StandingLang['Conference'] . "</a></li>";
	}else{
		echo "<li class=\"activemain\"><a href=\"#tabmain2\">" . $StandingLang['Conference'] . "</a></li>";
	}
	echo "<li><a href=\"#tabmain3\">" . $StandingLang['Division'] . "</a></li>";
	echo "<li><a href=\"#tabmain4\">" . $StandingLang['Overall'] . "</a></li>";
}
?>

</ul><div class="tabmain-content">
<div class="tabmain <?php if(isset($LeagueGeneral)){If ($LeagueGeneral['DivisionNewNHLPlayoff'] == "True"){echo "active";}}?>" id="tabmain1">

<?php
If ($DatabaseFound == True){
	echo "<h2>" . $LeagueGeneral['ConferenceName1'] . "</h2>";
	PrintStandingTop($TeamLang, $LeagueOutputOption['StandardStandingOutput'], $LeagueGeneral['PointSystemSO']);

	/* Division 1 */
	Echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"" . $ColumnPerTable . "\">" . $LeagueGeneral['DivisionName1'] . "</td></tr>";
	$Query = "SELECT Team" . $TypeTextTeam . "StatHistory.*, Team" . $TypeText . "InfoHistory.Conference, Team" . $TypeText . "InfoHistory.Division, RankingOrder.Type FROM (Team" . $TypeTextTeam . "StatHistory INNER JOIN Team" . $TypeText . "InfoHistory ON Team" . $TypeTextTeam . "StatHistory.Number = Team" . $TypeText . "InfoHistory.Number) INNER JOIN RankingOrder ON Team" . $TypeTextTeam . "StatHistory.Number = RankingOrder.Team" . $TypeText . "Number WHERE (((Team" . $TypeText . "InfoHistory.Division)=\"" . $LeagueGeneral['DivisionName1'] . "\") AND ((RankingOrder.Type)=0)) AND Team" . $TypeTextTeam . "StatHistory.Year = " . $Year . " And Team" . $TypeTextTeam . "StatHistory.Playoff = '" . $PlayoffString . "' AND Team" . $TypeTextTeam . "InfoHistory.Year = " . $Year . " And Team" . $TypeTextTeam . "InfoHistory.Playoff = '" . $PlayoffString . "' AND RankingOrder.Year = " . $Year . " And RankingOrder.Playoff = '" . $PlayoffString . "' ORDER BY RankingOrder.TeamOrder LIMIT 3";
	$Standing = $db->query($Query);
	$LoopCount =0;
	if (empty($Standing) == false){while ($row = $Standing ->fetchArray()) {
		$LoopCount +=1;
		PrintStandingTableRow($row, $TypeText, $LeagueOutputOption['StandardStandingOutput'], $LeagueGeneral['PointSystemSO'], $LeagueGeneral['PointSystemW'], $LoopCount,$DatabaseFile);
	}}
		
	/* Division 2 */	
	Echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"" . $ColumnPerTable . "\">" . $LeagueGeneral['DivisionName2'] . "</td></tr>";
	$Query = "SELECT Team" . $TypeTextTeam . "StatHistory.*, Team" . $TypeText . "InfoHistory.Conference, Team" . $TypeText . "InfoHistory.Division, RankingOrder.Type FROM (Team" . $TypeTextTeam . "StatHistory INNER JOIN Team" . $TypeText . "InfoHistory ON Team" . $TypeTextTeam . "StatHistory.Number = Team" . $TypeText . "InfoHistory.Number) INNER JOIN RankingOrder ON Team" . $TypeTextTeam . "StatHistory.Number = RankingOrder.Team" . $TypeText . "Number WHERE (((Team" . $TypeText . "InfoHistory.Division)=\"" . $LeagueGeneral['DivisionName2'] . "\") AND ((RankingOrder.Type)=0)) AND Team" . $TypeTextTeam . "StatHistory.Year = " . $Year . " And Team" . $TypeTextTeam . "StatHistory.Playoff = '" . $PlayoffString . "' AND Team" . $TypeTextTeam . "InfoHistory.Year = " . $Year . " And Team" . $TypeTextTeam . "InfoHistory.Playoff = '" . $PlayoffString . "' AND RankingOrder.Year = " . $Year . " And RankingOrder.Playoff = '" . $PlayoffString . "' ORDER BY RankingOrder.TeamOrder LIMIT 3";
	$Standing = $db->query($Query);
	$LoopCount =0;
	if (empty($Standing) == false){while ($row = $Standing ->fetchArray()) {
		$LoopCount +=1;
		PrintStandingTableRow($row, $TypeText, $LeagueOutputOption['StandardStandingOutput'], $LeagueGeneral['PointSystemSO'], $LeagueGeneral['PointSystemW'], $LoopCount,$DatabaseFile);
	}}

	/* Overall for Conference 1 */	
	Echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"" . $ColumnPerTable . "\">" . $StandingLang['Wildcard'] ."</td></tr>";
	$Query = "SELECT Team" . $TypeTextTeam . "StatHistory.*, Team" . $TypeText . "InfoHistory.Conference, Team" . $TypeText . "InfoHistory.Division, RankingOrder.Type FROM (Team" . $TypeTextTeam . "StatHistory INNER JOIN Team" . $TypeText . "InfoHistory ON Team" . $TypeTextTeam . "StatHistory.Number = Team" . $TypeText . "InfoHistory.Number) INNER JOIN RankingOrder ON Team" . $TypeTextTeam . "StatHistory.Number = RankingOrder.Team" . $TypeText . "Number WHERE (((Team" . $TypeText . "InfoHistory.Conference)=\"" . $LeagueGeneral['ConferenceName1'] . "\") AND ((RankingOrder.Type)=1)) AND Team" . $TypeTextTeam . "StatHistory.Year = " . $Year . " And Team" . $TypeTextTeam . "StatHistory.Playoff = '" . $PlayoffString . "' AND Team" . $TypeTextTeam . "InfoHistory.Year = " . $Year . " And Team" . $TypeTextTeam . "InfoHistory.Playoff = '" . $PlayoffString . "' AND RankingOrder.Year = " . $Year . " And RankingOrder.Playoff = '" . $PlayoffString . "' ORDER BY RankingOrder.TeamOrder";
	$Standing = $db->query($Query);
	$LoopCount =0;
	if (empty($Standing) == false){while ($row = $Standing ->fetchArray()) {
		$LoopCount +=1;
		If ($LoopCount > 6 ){PrintStandingTableRow($row, $TypeText, $LeagueOutputOption['StandardStandingOutput'], $LeagueGeneral['PointSystemSO'], $LeagueGeneral['PointSystemW'], $LoopCount,$DatabaseFile);}
		If ($LoopCount == 8){echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"" . $ColumnPerTable . "\"><hr /></td></tr>";}
	}}

	echo "</tbody></table>";	


	echo "<h2>" . $LeagueGeneral['ConferenceName2'] . "</h2>";
	PrintStandingTop($TeamLang, $LeagueOutputOption['StandardStandingOutput'], $LeagueGeneral['PointSystemSO']);

	/* Division 4 */
	Echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"" . $ColumnPerTable . "\">" . $LeagueGeneral['DivisionName4'] . "</td></tr>";
	$Query = "SELECT Team" . $TypeTextTeam . "StatHistory.*, Team" . $TypeText . "InfoHistory.Conference, Team" . $TypeText . "InfoHistory.Division, RankingOrder.Type FROM (Team" . $TypeTextTeam . "StatHistory INNER JOIN Team" . $TypeText . "InfoHistory ON Team" . $TypeTextTeam . "StatHistory.Number = Team" . $TypeText . "InfoHistory.Number) INNER JOIN RankingOrder ON Team" . $TypeTextTeam . "StatHistory.Number = RankingOrder.Team" . $TypeText . "Number WHERE (((Team" . $TypeText . "InfoHistory.Division)=\"" . $LeagueGeneral['DivisionName4'] . "\") AND ((RankingOrder.Type)=0)) AND Team" . $TypeTextTeam . "StatHistory.Year = " . $Year . " And Team" . $TypeTextTeam . "StatHistory.Playoff = '" . $PlayoffString . "' AND Team" . $TypeTextTeam . "InfoHistory.Year = " . $Year . " And Team" . $TypeTextTeam . "InfoHistory.Playoff = '" . $PlayoffString . "' AND RankingOrder.Year = " . $Year . " And RankingOrder.Playoff = '" . $PlayoffString . "' ORDER BY RankingOrder.TeamOrder LIMIT 3";
	$Standing = $db->query($Query);
	$LoopCount =0;
	if (empty($Standing) == false){while ($row = $Standing ->fetchArray()) {
		$LoopCount +=1;
		PrintStandingTableRow($row, $TypeText, $LeagueOutputOption['StandardStandingOutput'], $LeagueGeneral['PointSystemSO'], $LeagueGeneral['PointSystemW'], $LoopCount,$DatabaseFile);
	}}
		
	/* Division 5 */	
	Echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"" . $ColumnPerTable . "\">" . $LeagueGeneral['DivisionName5'] . "</td></tr>";
	$Query = "SELECT Team" . $TypeTextTeam . "StatHistory.*, Team" . $TypeText . "InfoHistory.Conference, Team" . $TypeText . "InfoHistory.Division, RankingOrder.Type FROM (Team" . $TypeTextTeam . "StatHistory INNER JOIN Team" . $TypeText . "InfoHistory ON Team" . $TypeTextTeam . "StatHistory.Number = Team" . $TypeText . "InfoHistory.Number) INNER JOIN RankingOrder ON Team" . $TypeTextTeam . "StatHistory.Number = RankingOrder.Team" . $TypeText . "Number WHERE (((Team" . $TypeText . "InfoHistory.Division)=\"" . $LeagueGeneral['DivisionName5'] . "\") AND ((RankingOrder.Type)=0)) AND Team" . $TypeTextTeam . "StatHistory.Year = " . $Year . " And Team" . $TypeTextTeam . "StatHistory.Playoff = '" . $PlayoffString . "' AND Team" . $TypeTextTeam . "InfoHistory.Year = " . $Year . " And Team" . $TypeTextTeam . "InfoHistory.Playoff = '" . $PlayoffString . "' AND RankingOrder.Year = " . $Year . " And RankingOrder.Playoff = '" . $PlayoffString . "' ORDER BY RankingOrder.TeamOrder LIMIT 3";
	$Standing = $db->query($Query);
	$LoopCount =0;
	if (empty($Standing) == false){while ($row = $Standing ->fetchArray()) {
		$LoopCount +=1;
		PrintStandingTableRow($row, $TypeText, $LeagueOutputOption['StandardStandingOutput'], $LeagueGeneral['PointSystemSO'], $LeagueGeneral['PointSystemW'], $LoopCount,$DatabaseFile);
	}}

	/* Overall for Conference 2 */	
	Echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"" . $ColumnPerTable . "\">" . $StandingLang['Wildcard'] . "</td></tr>";
	$Query = "SELECT Team" . $TypeTextTeam . "StatHistory.*, Team" . $TypeText . "InfoHistory.Conference, Team" . $TypeText . "InfoHistory.Division, RankingOrder.Type FROM (Team" . $TypeTextTeam . "StatHistory INNER JOIN Team" . $TypeText . "InfoHistory ON Team" . $TypeTextTeam . "StatHistory.Number = Team" . $TypeText . "InfoHistory.Number) INNER JOIN RankingOrder ON Team" . $TypeTextTeam . "StatHistory.Number = RankingOrder.Team" . $TypeText . "Number WHERE (((Team" . $TypeText . "InfoHistory.Conference)=\"" . $LeagueGeneral['ConferenceName2'] . "\") AND ((RankingOrder.Type)=2)) AND Team" . $TypeTextTeam . "StatHistory.Year = " . $Year . " And Team" . $TypeTextTeam . "StatHistory.Playoff = '" . $PlayoffString . "' AND Team" . $TypeTextTeam . "InfoHistory.Year = " . $Year . " And Team" . $TypeTextTeam . "InfoHistory.Playoff = '" . $PlayoffString . "' AND RankingOrder.Year = " . $Year . " And RankingOrder.Playoff = '" . $PlayoffString . "' ORDER BY RankingOrder.TeamOrder";
	$Standing = $db->query($Query);
	$LoopCount =0;
	if (empty($Standing) == false){while ($row = $Standing ->fetchArray()) {
		$LoopCount +=1;
		If ($LoopCount > 6 ){PrintStandingTableRow($row, $TypeText, $LeagueOutputOption['StandardStandingOutput'], $LeagueGeneral['PointSystemSO'], $LeagueGeneral['PointSystemW'], $LoopCount,$DatabaseFile);}
		If ($LoopCount == 8){echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"" . $ColumnPerTable . "\"><hr /></td></tr>";}
	}}

	echo "</tbody></table>";
}
?>

</div>
<div class="tabmain <?php if(isset($LeagueGeneral)){If ($LeagueGeneral['DivisionNewNHLPlayoff'] == "False"){echo "active";}}?>" id="tabmain2">
<?php
If ($DatabaseFound == True){
	$LoopCount =0;
	foreach ($Conference as $Value){
		$LoopCount +=1;
		$Query = "SELECT Team" . $TypeTextTeam . "StatHistory.*, Team" . $TypeText . "InfoHistory.Conference, Team" . $TypeText . "InfoHistory.Division, RankingOrder.Type FROM (Team" . $TypeTextTeam . "StatHistory INNER JOIN Team" . $TypeText . "InfoHistory ON Team" . $TypeTextTeam . "StatHistory.Number = Team" . $TypeText . "InfoHistory.Number) INNER JOIN RankingOrder ON Team" . $TypeTextTeam . "StatHistory.Number = RankingOrder.Team" . $TypeText . "Number WHERE (((Team" . $TypeText . "InfoHistory.Conference)=\"" . $Value . "\") AND ((RankingOrder.Type)=" . $LoopCount . ")) AND Team" . $TypeTextTeam . "StatHistory.Year = " . $Year . " And Team" . $TypeTextTeam . "StatHistory.Playoff = '" . $PlayoffString . "' AND Team" . $TypeTextTeam . "InfoHistory.Year = " . $Year . " And Team" . $TypeTextTeam . "InfoHistory.Playoff = '" . $PlayoffString . "' AND RankingOrder.Year = " . $Year . " And RankingOrder.Playoff = '" . $PlayoffString . "' ORDER BY RankingOrder.TeamOrder";
		$Standing = $db->query($Query);
		$DataReturn = $db->query($Query); /* Run the Query Twice to Loop Second Array to confirm the first Query Return Data  */
		if($DataReturn->fetchArray()){ /* Only Print Information if Query has row */
			echo "<h2>" . $Value . "</h2>";
			PrintStandingTop($TeamLang, $LeagueOutputOption['StandardStandingOutput'], $LeagueGeneral['PointSystemSO']);
			If ($LeagueSimulation['TwoConference'] == "True"){
				PrintStandingTable($Standing, $TypeText, $LeagueOutputOption['StandardStandingOutput'], $LeagueGeneral['PointSystemSO'], $LeagueGeneral['PointSystemW'], $ColumnPerTable, $LeagueGeneral['HowManyPlayOffTeam']/2,$DatabaseFile);
			}else{
				PrintStandingTable($Standing, $TypeText, $LeagueOutputOption['StandardStandingOutput'], $LeagueGeneral['PointSystemSO'], $LeagueGeneral['PointSystemW'], $ColumnPerTable, $LeagueGeneral['HowManyPlayOffTeam'],$DatabaseFile);
			}
		}
	}
}
?>
</div>
<div class="tabmain" id="tabmain3">
<?php
If ($DatabaseFound == True){
	foreach ($Division as $Value){
		$Query = "SELECT Team" . $TypeTextTeam . "StatHistory.*, Team" . $TypeText . "InfoHistory.Conference, Team" . $TypeText . "InfoHistory.Division, RankingOrder.Type FROM (Team" . $TypeTextTeam . "StatHistory INNER JOIN Team" . $TypeText . "InfoHistory ON Team" . $TypeTextTeam . "StatHistory.Number = Team" . $TypeText . "InfoHistory.Number) INNER JOIN RankingOrder ON Team" . $TypeTextTeam . "StatHistory.Number = RankingOrder.Team" . $TypeText . "Number WHERE (((Team" . $TypeText . "InfoHistory.Division)=\"" . $Value . "\") AND ((RankingOrder.Type)=0)) AND Team" . $TypeTextTeam . "StatHistory.Year = " . $Year . " And Team" . $TypeTextTeam . "StatHistory.Playoff = '" . $PlayoffString . "' AND Team" . $TypeTextTeam . "InfoHistory.Year = " . $Year . " And Team" . $TypeTextTeam . "InfoHistory.Playoff = '" . $PlayoffString . "' AND RankingOrder.Year = " . $Year . " And RankingOrder.Playoff = '" . $PlayoffString . "' ORDER BY RankingOrder.TeamOrder";
		$Standing = $db->query($Query);
		$DataReturn = $db->query($Query); /* Run the Query Twice to Loop Second Array to confirm the first Query Return Data  */
		if($DataReturn->fetchArray()){ /* Only Print Information if Query has row */
			echo "<h2>" . $Value . "</h2>";
			PrintStandingTop($TeamLang, $LeagueOutputOption['StandardStandingOutput'], $LeagueGeneral['PointSystemSO']);
			PrintStandingTable($Standing, $TypeText, $LeagueOutputOption['StandardStandingOutput'], $LeagueGeneral['PointSystemSO'], $LeagueGeneral['PointSystemW'],$ColumnPerTable,0,$DatabaseFile);
		}
	}
}
?>
</div>
<div class="tabmain" id="tabmain4">
<?php
If ($DatabaseFound == True){
	Echo "<h2>" . $StandingLang['Overall'] . "</h2>";
	$Query = "SELECT Team" . $TypeTextTeam . "StatHistory.*, RankingOrder.TeamOrder FROM Team" . $TypeTextTeam . "StatHistory INNER JOIN RankingOrder ON Team" . $TypeTextTeam . "StatHistory.Number = RankingOrder.Team" . $TypeText . "Number WHERE (((RankingOrder.Type)=0)) AND Team" . $TypeTextTeam . "StatHistory.Year = " . $Year . " And Team" . $TypeTextTeam . "StatHistory.Playoff = '" . $PlayoffString . "' AND RankingOrder.Year = " . $Year . " And RankingOrder.Playoff = '" . $PlayoffString . "' ORDER BY RankingOrder.TeamOrder";
	$Standing = $db->query($Query);
	PrintStandingTop($TeamLang, $LeagueOutputOption['StandardStandingOutput'], $LeagueGeneral['PointSystemSO']);
	PrintStandingTable($Standing, $TypeText, $LeagueOutputOption['StandardStandingOutput'], $LeagueGeneral['PointSystemSO'], $LeagueGeneral['PointSystemW'],$ColumnPerTable,0,$DatabaseFile);
}
?>

</div>

<div class="tabmain<?php if ($Playoff == True){echo " active";}?>" id="tabmain5">






<?php
If ($DatabaseFound == True){
	If ($LeagueGeneral['PlayOffWinner'] != 0 AND $Playoff == True){
		$Winner = $db->querySingle("Select Team" . $TypeText . "InfoHistory.Name from Team" . $TypeText . "InfoHistory WHERE Team" . $TypeTextTeam . "InfoHistory.Year = " . $Year . " And Team" . $TypeTextTeam . "InfoHistory.Playoff = '" . $PlayoffString . "' AND Team" . $TypeText . "InfoHistory.Number = ". $LeagueGeneral['PlayOffWinner'] ,true);
		echo "<div class=\"STHSCenter\"><h1>" . $Winner['Name'] . $StandingLang['WinsPlayoff'] . "</h1></div>";
	}
	echo "<table class=\"STHSTableFullW\"><tr>";
	for($Round = 1; $Round <= 5; $Round++){
		If ($Round <= $LeagueGeneral['PlayOffRound']){
			echo "<td><b> " . $StandingLang['Round'] . $Round . "</b></td>";
		}else{
			echo "<td></td>";
		}
	}
	echo "</tr>";
	$Query = "SELECT Playoff" . $TypeText . "Number.* FROM Playoff" . $TypeText . "Number WHERE Year = " . $Year . " ORDER BY Playoff" . $TypeText . "Number.Number";
	$PlayoffStanding = $db->query($Query);
	if (empty($PlayoffStanding) == false){while ($Row = $PlayoffStanding ->fetchArray()) {
		echo "<tr>";
		If ($Row['Round1'] == 0){echo "<td></td>";}else{
			$Round1 = $db->querySingle("SELECT Playoff" . $TypeText . ".*, TeamInfoHome.Name as HomeTeamName, TeamInfoVisitor.Name as VisitorTeamName FROM (Playoff" . $TypeText . " INNER JOIN Team" . $TypeText . "InfoHistory AS TeamInfoHome ON Playoff" . $TypeText . ".HomeTeam = TeamInfoHome.Number) LEFT JOIN Team" . $TypeText . "InfoHistory AS TeamInfoVisitor ON Playoff" . $TypeText . ".VisitorTeam = TeamInfoVisitor.Number WHERE Playoff" . $TypeText . ".Number = " . $Row['Round1'] . " AND Playoff" . $TypeTextTeam . ".Year = " . $Year . " And TeamInfoHome.Year = " . $Year . " and TeamInfoVisitor.Year = " . $Year ,true);	
			echo "<td><a href=\"" . $TypeText . "Team.php?Team=" . $Round1['VisitorTeam'] . "\">" . $Round1['VisitorTeamName'] . " - " . $Round1['VisitorWin'] . "</a><br />";
			echo "<a href=\"" . $TypeText . "Team.php?Team=" . $Round1['HomeTeam'] . "\">" . $Round1['HomeTeamName'] . " - " . $Round1['HomeWin'] . "</a><br /><br /></td>";
		}
		If ($Row['Round2'] == 0){echo "<td></td>";}else{
			$Round2 = $db->querySingle("SELECT Playoff" . $TypeText . ".*, TeamInfoHome.Name as HomeTeamName, TeamInfoVisitor.Name as VisitorTeamName FROM (Playoff" . $TypeText . " INNER JOIN Team" . $TypeText . "InfoHistory AS TeamInfoHome ON Playoff" . $TypeText . ".HomeTeam = TeamInfoHome.Number) LEFT JOIN Team" . $TypeText . "InfoHistory AS TeamInfoVisitor ON Playoff" . $TypeText . ".VisitorTeam = TeamInfoVisitor.Number WHERE Playoff" . $TypeText . ".Number = " . $Row['Round2'] . " AND Playoff" . $TypeTextTeam . ".Year = " . $Year . " And TeamInfoHome.Year = " . $Year . " and TeamInfoVisitor.Year = " . $Year,true);	
			echo "<td><a href=\"" . $TypeText . "Team.php?Team=" . $Round2['VisitorTeam'] . "\">" . $Round2['VisitorTeamName'] . " - " . $Round2['VisitorWin'] . "</a><br />";
			echo "<a href=\"" . $TypeText . "Team.php?Team=" . $Round2['HomeTeam'] . "\">" . $Round2['HomeTeamName'] . " - " . $Round2['HomeWin'] . "</a><br /><br /></td>";
		}
		If ($Row['Round3'] == 0){echo "<td></td>";}else{
			$Round3 = $db->querySingle("SELECT Playoff" . $TypeText . ".*, TeamInfoHome.Name as HomeTeamName, TeamInfoVisitor.Name as VisitorTeamName FROM (Playoff" . $TypeText . " INNER JOIN Team" . $TypeText . "InfoHistory AS TeamInfoHome ON Playoff" . $TypeText . ".HomeTeam = TeamInfoHome.Number) LEFT JOIN Team" . $TypeText . "InfoHistory AS TeamInfoVisitor ON Playoff" . $TypeText . ".VisitorTeam = TeamInfoVisitor.Number WHERE Playoff" . $TypeText . ".Number = " . $Row['Round3'] . " AND Playoff" . $TypeTextTeam . ".Year = " . $Year . " And TeamInfoHome.Year = " . $Year . " and TeamInfoVisitor.Year = " . $Year,true);	
			echo "<td><a href=\"" . $TypeText . "Team.php?Team=" . $Round3['VisitorTeam'] . "\">" . $Round3['VisitorTeamName'] . " - " . $Round3['VisitorWin'] . "</a><br />";
			echo "<a href=\"" . $TypeText . "Team.php?Team=" . $Round3['HomeTeam'] . "\">" . $Round3['HomeTeamName'] . " - " . $Round3['HomeWin'] . "</a><br /><br /></td>";
		}
		If ($Row['Round4'] == 0){echo "<td></td>";}else{
			$Round4 = $db->querySingle("SELECT Playoff" . $TypeText . ".*, TeamInfoHome.Name as HomeTeamName, TeamInfoVisitor.Name as VisitorTeamName FROM (Playoff" . $TypeText . " INNER JOIN Team" . $TypeText . "InfoHistory AS TeamInfoHome ON Playoff" . $TypeText . ".HomeTeam = TeamInfoHome.Number) LEFT JOIN Team" . $TypeText . "InfoHistory AS TeamInfoVisitor ON Playoff" . $TypeText . ".VisitorTeam = TeamInfoVisitor.Number WHERE Playoff" . $TypeText . ".Number = " . $Row['Round4'] . " AND Playoff" . $TypeTextTeam . ".Year = " . $Year . " And TeamInfoHome.Year = " . $Year . " and TeamInfoVisitor.Year = " . $Year,true);	
			echo "<td><a href=\"" . $TypeText . "Team.php?Team=" . $Round4['VisitorTeam'] . "\">" . $Round4['VisitorTeamName'] . " - " . $Round4['VisitorWin'] . "</a><br />";
			echo "<a href=\"" . $TypeText . "Team.php?Team=" . $Round4['HomeTeam'] . "\">" . $Round4['HomeTeamName'] . " - " . $Round4['HomeWin'] . "</a><br /><br /></td>";
		}
		If ($Row['Round5'] == 0){echo "<td></td>";}else{
			$Round5 = $db->querySingle("SELECT Playoff" . $TypeText . ".*, TeamInfoHome.Name as HomeTeamName, TeamInfoVisitor.Name as VisitorTeamName FROM (Playoff" . $TypeText . " INNER JOIN Team" . $TypeText . "InfoHistory AS TeamInfoHome ON Playoff" . $TypeText . ".HomeTeam = TeamInfoHome.Number) LEFT JOIN Team" . $TypeText . "InfoHistory AS TeamInfoVisitor ON Playoff" . $TypeText . ".VisitorTeam = TeamInfoVisitor.Number WHERE Playoff" . $TypeText . ".Number = " . $Row['Round5'] . " AND Playoff" . $TypeTextTeam . ".Year = " . $Year . " And TeamInfoHome.Year = " . $Year . " and TeamInfoVisitor.Year = " . $Year,true);	
			echo "<td><a href=\"" . $TypeText . "Team.php?Team=" . $Round5['VisitorTeam'] . "\">" . $Round5['VisitorTeamName'] . " - " . $Round5['VisitorWin'] . "</a><br />";
			echo "<a href=\"" . $TypeText . "Team.php?Team=" . $Round5['HomeTeam'] . "\">" . $Round5['HomeTeamName'] . " - " . $Round5['HomeWin'] . "</a><br /><br /></td>";
		}
		echo "</tr>";
	}}
	echo "</table>";
}?>

</div>

</div>
</div>



<script>
$(function(){
  $(".STHSPHPStanding_Table").tablesorter({widgets:['staticRow']});
});
</script>
</div>


<?php
include "Footer.php";
?>

