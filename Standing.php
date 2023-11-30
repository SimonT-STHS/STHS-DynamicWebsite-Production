<?php include "Header.php";
If ($lang == "fr"){include 'LanguageFR-League.php';}else{include 'LanguageEN-League.php';}
If ($lang == "fr"){include 'LanguageFR-Stat.php';}else{include 'LanguageEN-Stat.php';}
$TypeText = (string)"Pro";$TitleType = $DynamicTitleLang['Pro'];
$TypeTextTeam = (string)"Pro";
$Playoff = (boolean)False;
$Title = (string)"";
$StandingQueryOK = (boolean)False;
$Search = (boolean)False;
$LeagueOutputOption = Null;
$ColumnPerTable = 18;
If (file_exists($DatabaseFile) == false){
	Goto STHSErrorStanding;
}else{try{
	$Title = (string)"";
	$LeagueName = (string)"";
	if(isset($_GET['Farm'])){$TypeText = "Farm";$TypeTextTeam = (string)"Farm";$TitleType = $DynamicTitleLang['Farm'];}
	
	$db = new SQLite3($DatabaseFile);
	
	$Query = "Select Name, PointSystemW, PointSystemSO, NoOvertime, " . $TypeText . "ConferenceName1 AS ConferenceName1," . $TypeText . "ConferenceName2 AS ConferenceName2," . $TypeText . "DivisionName1 AS DivisionName1," . $TypeText . "DivisionName2 AS DivisionName2," . $TypeText . "DivisionName3 AS DivisionName3," . $TypeText . "DivisionName4 AS DivisionName4," . $TypeText . "DivisionName5 AS DivisionName5," . $TypeText . "DivisionName6 AS DivisionName6," . $TypeText . "HowManyPlayOffTeam AS HowManyPlayOffTeam," . $TypeText . "DivisionNewNHLPlayoff  AS DivisionNewNHLPlayoff,PlayOffWinner" . $TypeText . " AS PlayOffWinner, PlayOffStarted, PlayOffRound, TieBreaker2010, TieBreaker2019 FROM LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	$Query = "Select StandardStandingOutput From LeagueOutputOption";
	$LeagueOutputOption = $db->querySingle($Query,true);		
	$Conference = array($LeagueGeneral['ConferenceName1'], $LeagueGeneral['ConferenceName2']);
	$Division = array($LeagueGeneral['DivisionName1'], $LeagueGeneral['DivisionName2'], $LeagueGeneral['DivisionName3'], $LeagueGeneral['DivisionName4'], $LeagueGeneral['DivisionName5'], $LeagueGeneral['DivisionName6']);
	
	$Query = "Select " . $TypeText . "TwoConference AS TwoConference from LeagueSimulation";
	$LeagueSimulation = $db->querySingle($Query,true);	
	
	If ($LeagueOutputOption['StandardStandingOutput'] == "False"){
		$ColumnPerTable = 21;
		If ($LeagueGeneral['PointSystemSO'] == "False"){$ColumnPerTable = $ColumnPerTable -1;}
		If ($LeagueGeneral['TieBreaker2019'] == "False"){$ColumnPerTable = $ColumnPerTable -1;}
		If ($LeagueGeneral['TieBreaker2019'] == "False" AND $LeagueGeneral['TieBreaker2010'] == "False"){$ColumnPerTable = $ColumnPerTable -1;}
	}
	
	if ($LeagueGeneral['PlayOffStarted'] == "True"){
		if(isset($_GET['Season'])){
			$Title = $LeagueName . " - " . $StandingLang['Standing'] . " " . $TitleType;
			$TypeTextTeam = $TypeTextTeam . "Season";
		}else{
			$Title = $LeagueName . " - " . $StandingLang['Playoff'] . " " . $TitleType;
			$Playoff = True;
		}
	}else{
		$Title = $LeagueName . " - " . $StandingLang['Standing'] . " " . $TitleType;
	}
	$StandingQueryOK = True;
} catch (Exception $e) {
STHSErrorStanding:
	$StandingQueryOK = False;
	$LeagueName = $DatabaseNotFound;
	$Standing = Null;
	$LeagueGeneral = Null;
	echo "<title>" . $DatabaseNotFound . "</title>";
	$Title = $DatabaseNotFound;
}}
echo "<title>" . $Title . "</title>";

function PrintStandingTop($TeamLang, $StandardStandingOutput, $LeagueGeneral) {
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
	if ($LeagueGeneral['PointSystemSO'] == "False"){echo "<th title=\"Ties\" class=\"STHSW30\">T</th>";}
	if ($LeagueGeneral['NoOvertime'] == "True"){	
		echo "<th title=\"Overtime Wins\" class=\"STHSW30\">OTW</th>";
		echo "<th title=\"Overtime Loss\" class=\"STHSW30\">OTL</th>";
	}
	if ($LeagueGeneral['PointSystemSO'] == "True"){	
		echo "<th title=\"Shutouts Wins\" class=\"STHSW30\">SOW</th>";
		echo "<th title=\"Shutouts Loss\" class=\"STHSW30\">SOL</th>";	
	}
}
echo "<th title=\"Points\" class=\"STHSW30\">P</th>";
If ($LeagueGeneral['TieBreaker2019'] == "True"){echo "<th title=\"Normal Wins\" class=\"STHSW30\">RW</th>";}
If ($LeagueGeneral['TieBreaker2019'] == "True" OR $LeagueGeneral['TieBreaker2010'] == "True"){echo "<th title=\"Normal Wins + Overtime Win\" class=\"STHSW30\">ROW</th>";}
echo "<th title=\"Goals For\" class=\"STHSW30\">GF</th>";
echo "<th title=\"Goals Against\" class=\"STHSW30\">GA</th>";
echo "<th title=\"Goals For Diffirencial against Goals Against\" class=\"STHSW30\">Diff</th>";
echo "<th title=\"Points Percentage\" class=\"STHSW45\">PCT</th>";
echo "<th title=\"Home Only\" class=\"STHSW75\">" . $TeamLang['Home'] ."</th>";
echo "<th title=\"Visitor Only\" class=\"STHSW75\">" . $TeamLang['Visitor'] ."</th>";
echo "<th title=\"Last 10 Game\" class=\"STHSW75\">" . $TeamLang['Last10'] ."</th>";
echo "<th title=\"Streak\" class=\"STHSW30\">STK</th>";
echo "<th title=\"Next Game\" class=\"STHSW30\">Next</th>";
echo "</tr></thead><tbody>";
}

Function PrintStandingTable($Standing, $TypeText, $StandardStandingOutput, $LeagueGeneral, $ColumnPerTable, $LinesNumber ,$DatabaseFile,$ImagesCDNPath){
$LoopCount =0;
while ($row = $Standing ->fetchArray()) {
	$LoopCount +=1;
	PrintStandingTableRow($row, $TypeText, $StandardStandingOutput, $LeagueGeneral, $LoopCount, $DatabaseFile,$ImagesCDNPath);
	If ($LoopCount > 0 AND $LoopCount == $LinesNumber){echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"" . $ColumnPerTable . "\"><hr /></td></tr>";}
}
echo "</tbody></table>";
}

Function PrintStandingTableRow($row, $TypeText, $StandardStandingOutput, $LeagueGeneral, $LoopCount,$DatabaseFile,$ImagesCDNPath){
	echo "<tr><td>" . $LoopCount . "</td>";
	echo "<td><span class=\"" . $TypeText . "Standing_Team" . $row['Number'] . "\"></span>";
	If ($row['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPStandingTeamImage\" />";}
	echo "<a href=\"" . $TypeText . "Team.php?Team=" . $row['Number'] . "\">" . $row['Name'] . "</a>";
	if($row['StandingPlayoffTitle']=="E"){echo " - E ";
	} else if($row['StandingPlayoffTitle']=="X"){echo " - X";
	} else if($row['StandingPlayoffTitle']=="Y"){echo " - Y";
	} else if($row['StandingPlayoffTitle']=="Z"){echo " - Z";
	}
	echo "</td><td>" . $row['GP'] . "</td>";
	If ($StandardStandingOutput == "True"){
		echo "<td>" . ($row['W'] + $row['OTW'] + $row['SOW']) . "</td>";
		echo "<td>" . $row['L'] . "</td>";
		echo "<td>" . ($row['OTL'] + $row['SOL']) . "</td>";
	}else{		
		echo "<td>" . $row['W'] . "</td>";
		echo "<td>" . $row['L'] . "</td>";
		if ($LeagueGeneral['PointSystemSO'] == "False"){echo "<td>" . $row['T'] . "</td>";}
		if ($LeagueGeneral['NoOvertime'] == "True"){
			echo "<td>" . $row['OTW'] . "</td>";
			echo "<td>" . $row['OTL'] . "</td>";
		}
		if ($LeagueGeneral['PointSystemSO'] == "True"){	
			echo "<td>" . $row['SOW'] . "</td>";
			echo "<td>" . $row['SOL'] . "</td>";
		}	
	}
	echo "<td><strong>" . $row['Points'] . "</strong></td>";
	If ($LeagueGeneral['TieBreaker2019'] == "True"){echo "<td>" . ($row['W']) . "</td>";}
	If ($LeagueGeneral['TieBreaker2019'] == "True" OR $LeagueGeneral['TieBreaker2010'] == "True"){echo "<td>" . ($row['W'] + $row['OTW']) . "</td>";}
	echo "<td>" . $row['GF'] . "</td>";
	echo "<td>" . $row['GA'] . "</td>";
	echo "<td>" . ($row['GF'] - $row['GA']) . "</td>";
	if ($row['GP'] > 0 AND $LeagueGeneral['PointSystemW'] > 0){echo "<td>" . number_Format(($row['Points'] / ($row['GP'] * $LeagueGeneral['PointSystemW'])),3) . "</td>";}else{echo "<td>" . number_Format("0",3) . "</td>";}	
	echo "<td>" . ($row['HomeW'] + $row['HomeOTW'] + $row['HomeSOW'])."-".$row['HomeL']."-".($row['HomeOTL']+$row['HomeSOL']) . "</td>";
	echo "<td>" . ($row['W'] + $row['OTW'] + $row['SOW'] - $row['HomeW'] - $row['HomeOTW'] - $row['HomeSOW'])."-".($row['L'] - $row['HomeL'])."-".($row['OTL']+$row['SOL']-$row['HomeOTL']-$row['HomeSOL']) . "</td>";
	echo "<td>" . ($row['Last10W'] + $row['Last10OTW'] + $row['Last10SOW'])."-".$row['Last10L']."-".($row['Last10OTL']+$row['Last10SOL']) . "</td>";
	echo "<td>" . $row['Streak'] . "</td>";
	$dbS = new SQLite3($DatabaseFile);
	$Query = "SELECT count(*) AS count FROM Schedule" . $TypeText . " WHERE (VisitorTeam = " . $row['Number'] . " OR HomeTeam = " . $row['Number'] . ") AND Play = 'False' ORDER BY GameNumber LIMIT 1";
	$Result = $dbS->querySingle($Query,true);
	If ($Result['count'] > 0){
		$Query = "SELECT * FROM Schedule" . $TypeText . " WHERE (VisitorTeam = " . $row['Number'] . " OR HomeTeam = " . $row['Number'] . ") AND Play = 'False' ORDER BY GameNumber LIMIT 1";
		$ScheduleNext = $dbS->querySingle($Query,true);			
		If ($ScheduleNext['HomeTeam'] == $row['Number']){
			echo "<td> vs " . $ScheduleNext['VisitorTeamAbbre'] . "</td>";
		}elseif($ScheduleNext['VisitorTeam'] == $row['Number']){
			echo "<td> vs " . $ScheduleNext['HomeTeamAbbre'] . "</td>";
		}
	}else{
		echo "<td></td>";
	}
	echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
}

?>

<style>
@media screen and (max-width: 1060px) {
.STHSWarning {display:block;}
.STHSPHPStanding_Table thead th:nth-last-child(1){display:none;}
.STHSPHPStanding_Table tbody td:nth-last-child(1){display:none;}
.STHSPHPStanding_Table thead th:nth-last-child(3){display:none;}
.STHSPHPStanding_Table tbody td:nth-last-child(3){display:none;}
.STHSPHPStanding_Table thead th:nth-last-child(4){display:none;}
.STHSPHPStanding_Table tbody td:nth-last-child(4){display:none;}
.STHSPHPStanding_Table thead th:nth-last-child(5){display:none;}
.STHSPHPStanding_Table tbody td:nth-last-child(5){display:none;}
}@media screen and (max-width: 890px) {
.STHSPHPStanding_Table thead th:nth-last-child(2){display:none;}
.STHSPHPStanding_Table tbody td:nth-last-child(2){display:none;}
.STHSPHPStanding_Table thead th:nth-last-child(6){display:none;}
.STHSPHPStanding_Table tbody td:nth-last-child(6){display:none;}
}
.STHSPHPStanding_Table tbody td.staticTD {font-size:9pt;border-right:hidden; border-left:hidden;}

<?php 
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
If ($StandingQueryOK == True){
	echo "<h2>" . $LeagueGeneral['ConferenceName1'] . "</h2>";
	PrintStandingTop($TeamLang, $LeagueOutputOption['StandardStandingOutput'], $LeagueGeneral);

	/* Division 1 */
	Echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"" . $ColumnPerTable . "\">" . $LeagueGeneral['DivisionName1'] . "</td></tr>";
	$Query = "SELECT Team" . $TypeTextTeam . "Stat.*, Team" . $TypeText . "Info.Conference, Team" . $TypeText . "Info.Division,Team" . $TypeText . "Info.TeamThemeID, RankingOrder.Type FROM (Team" . $TypeTextTeam . "Stat INNER JOIN Team" . $TypeText . "Info ON Team" . $TypeTextTeam . "Stat.Number = Team" . $TypeText . "Info.Number) INNER JOIN RankingOrder ON Team" . $TypeTextTeam . "Stat.Number = RankingOrder.Team" . $TypeText . "Number WHERE (((Team" . $TypeText . "Info.Division)=\"" . $LeagueGeneral['DivisionName1'] . "\") AND ((RankingOrder.Type)=0)) ORDER BY RankingOrder.TeamOrder LIMIT 3";
	$Standing = $db->query($Query);
	$LoopCount =0;
	if (empty($Standing) == false){while ($row = $Standing ->fetchArray()) {
		$LoopCount +=1;
		PrintStandingTableRow($row, $TypeText, $LeagueOutputOption['StandardStandingOutput'], $LeagueGeneral, $LoopCount,$DatabaseFile,$ImagesCDNPath);
	}}
		
	/* Division 2 */	
	Echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"" . $ColumnPerTable . "\">" . $LeagueGeneral['DivisionName2'] . "</td></tr>";
	$Query = "SELECT Team" . $TypeTextTeam . "Stat.*, Team" . $TypeText . "Info.Conference, Team" . $TypeText . "Info.Division,Team" . $TypeText . "Info.TeamThemeID, RankingOrder.Type FROM (Team" . $TypeTextTeam . "Stat INNER JOIN Team" . $TypeText . "Info ON Team" . $TypeTextTeam . "Stat.Number = Team" . $TypeText . "Info.Number) INNER JOIN RankingOrder ON Team" . $TypeTextTeam . "Stat.Number = RankingOrder.Team" . $TypeText . "Number WHERE (((Team" . $TypeText . "Info.Division)=\"" . $LeagueGeneral['DivisionName2'] . "\") AND ((RankingOrder.Type)=0)) ORDER BY RankingOrder.TeamOrder LIMIT 3";
	$Standing = $db->query($Query);
	$LoopCount =0;
	if (empty($Standing) == false){while ($row = $Standing ->fetchArray()) {
		$LoopCount +=1;
		PrintStandingTableRow($row, $TypeText, $LeagueOutputOption['StandardStandingOutput'], $LeagueGeneral, $LoopCount,$DatabaseFile,$ImagesCDNPath);
	}}

	/* Overall for Conference 1 */	
	Echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"" . $ColumnPerTable . "\">" . $StandingLang['Wildcard'] ."</td></tr>";
	$Query = "SELECT Team" . $TypeTextTeam . "Stat.*, Team" . $TypeText . "Info.Conference, Team" . $TypeText . "Info.Division,Team" . $TypeText . "Info.TeamThemeID, RankingOrder.Type FROM (Team" . $TypeTextTeam . "Stat INNER JOIN Team" . $TypeText . "Info ON Team" . $TypeTextTeam . "Stat.Number = Team" . $TypeText . "Info.Number) INNER JOIN RankingOrder ON Team" . $TypeTextTeam . "Stat.Number = RankingOrder.Team" . $TypeText . "Number WHERE (((Team" . $TypeText . "Info.Conference)=\"" . $LeagueGeneral['ConferenceName1'] . "\") AND ((RankingOrder.Type)=1)) ORDER BY RankingOrder.TeamOrder";
	$Standing = $db->query($Query);
	$LoopCount =0;
	if (empty($Standing) == false){while ($row = $Standing ->fetchArray()) {
		$LoopCount +=1;
		If ($LoopCount > 6 ){PrintStandingTableRow($row, $TypeText, $LeagueOutputOption['StandardStandingOutput'], $LeagueGeneral, $LoopCount,$DatabaseFile,$ImagesCDNPath);}
		If ($LoopCount == 8){echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"" . $ColumnPerTable . "\"><hr /></td></tr>";}
	}}

	echo "</tbody></table>";	


	echo "<h2>" . $LeagueGeneral['ConferenceName2'] . "</h2>";
	PrintStandingTop($TeamLang, $LeagueOutputOption['StandardStandingOutput'], $LeagueGeneral);

	/* Division 4 */
	Echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"" . $ColumnPerTable . "\">" . $LeagueGeneral['DivisionName4'] . "</td></tr>";
	$Query = "SELECT Team" . $TypeTextTeam . "Stat.*, Team" . $TypeText . "Info.Conference, Team" . $TypeText . "Info.Division,Team" . $TypeText . "Info.TeamThemeID, RankingOrder.Type FROM (Team" . $TypeTextTeam . "Stat INNER JOIN Team" . $TypeText . "Info ON Team" . $TypeTextTeam . "Stat.Number = Team" . $TypeText . "Info.Number) INNER JOIN RankingOrder ON Team" . $TypeTextTeam . "Stat.Number = RankingOrder.Team" . $TypeText . "Number WHERE (((Team" . $TypeText . "Info.Division)=\"" . $LeagueGeneral['DivisionName4'] . "\") AND ((RankingOrder.Type)=0)) ORDER BY RankingOrder.TeamOrder LIMIT 3";
	$Standing = $db->query($Query);
	$LoopCount =0;
	if (empty($Standing) == false){while ($row = $Standing ->fetchArray()) {
		$LoopCount +=1;
		PrintStandingTableRow($row, $TypeText, $LeagueOutputOption['StandardStandingOutput'], $LeagueGeneral, $LoopCount,$DatabaseFile,$ImagesCDNPath);
	}}
		
	/* Division 5 */	
	Echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"" . $ColumnPerTable . "\">" . $LeagueGeneral['DivisionName5'] . "</td></tr>";
	$Query = "SELECT Team" . $TypeTextTeam . "Stat.*, Team" . $TypeText . "Info.Conference, Team" . $TypeText . "Info.Division,Team" . $TypeText . "Info.TeamThemeID, RankingOrder.Type FROM (Team" . $TypeTextTeam . "Stat INNER JOIN Team" . $TypeText . "Info ON Team" . $TypeTextTeam . "Stat.Number = Team" . $TypeText . "Info.Number) INNER JOIN RankingOrder ON Team" . $TypeTextTeam . "Stat.Number = RankingOrder.Team" . $TypeText . "Number WHERE (((Team" . $TypeText . "Info.Division)=\"" . $LeagueGeneral['DivisionName5'] . "\") AND ((RankingOrder.Type)=0)) ORDER BY RankingOrder.TeamOrder LIMIT 3";
	$Standing = $db->query($Query);
	$LoopCount =0;
	if (empty($Standing) == false){while ($row = $Standing ->fetchArray()) {
		$LoopCount +=1;
		PrintStandingTableRow($row, $TypeText, $LeagueOutputOption['StandardStandingOutput'], $LeagueGeneral, $LoopCount,$DatabaseFile,$ImagesCDNPath);
	}}

	/* Overall for Conference 2 */	
	Echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"" . $ColumnPerTable . "\">" . $StandingLang['Wildcard'] . "</td></tr>";
	$Query = "SELECT Team" . $TypeTextTeam . "Stat.*, Team" . $TypeText . "Info.Conference, Team" . $TypeText . "Info.Division,Team" . $TypeText . "Info.TeamThemeID, RankingOrder.Type FROM (Team" . $TypeTextTeam . "Stat INNER JOIN Team" . $TypeText . "Info ON Team" . $TypeTextTeam . "Stat.Number = Team" . $TypeText . "Info.Number) INNER JOIN RankingOrder ON Team" . $TypeTextTeam . "Stat.Number = RankingOrder.Team" . $TypeText . "Number WHERE (((Team" . $TypeText . "Info.Conference)=\"" . $LeagueGeneral['ConferenceName2'] . "\") AND ((RankingOrder.Type)=2)) ORDER BY RankingOrder.TeamOrder";
	$Standing = $db->query($Query);
	$LoopCount =0;
	if (empty($Standing) == false){while ($row = $Standing ->fetchArray()) {
		$LoopCount +=1;
		If ($LoopCount > 6 ){PrintStandingTableRow($row, $TypeText, $LeagueOutputOption['StandardStandingOutput'], $LeagueGeneral, $LoopCount,$DatabaseFile,$ImagesCDNPath);}
		If ($LoopCount == 8){echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"" . $ColumnPerTable . "\"><hr /></td></tr>";}
	}}

	echo "</tbody></table>";
}
?>

</div>
<div class="tabmain <?php if(isset($LeagueGeneral)){If ($LeagueGeneral['DivisionNewNHLPlayoff'] == "False"){echo "active";}}?>" id="tabmain2">
<?php
If ($StandingQueryOK == True){
	$LoopCount =0;
	foreach ($Conference as $Value){
		$LoopCount +=1;
		$Query = "SELECT Team" . $TypeTextTeam . "Stat.*, Team" . $TypeText . "Info.Conference, Team" . $TypeText . "Info.Division,Team" . $TypeText . "Info.TeamThemeID, RankingOrder.Type FROM (Team" . $TypeTextTeam . "Stat INNER JOIN Team" . $TypeText . "Info ON Team" . $TypeTextTeam . "Stat.Number = Team" . $TypeText . "Info.Number) INNER JOIN RankingOrder ON Team" . $TypeTextTeam . "Stat.Number = RankingOrder.Team" . $TypeText . "Number WHERE (((Team" . $TypeText . "Info.Conference)=\"" . $Value . "\") AND ((RankingOrder.Type)=" . $LoopCount . ")) ORDER BY RankingOrder.TeamOrder";
		$Standing = $db->query($Query);
		$DataReturn = $db->query($Query); /* Run the Query Twice to Loop Second Array to confirm the first Query Return Data  */
		If ($DataReturn == True){if($DataReturn->fetchArray()){ /* Only Print Information if Query has row */
			echo "<h2>" . $Value . "</h2>";
			PrintStandingTop($TeamLang, $LeagueOutputOption['StandardStandingOutput'], $LeagueGeneral);
			If ($LeagueSimulation['TwoConference'] == "True"){
				PrintStandingTable($Standing, $TypeText, $LeagueOutputOption['StandardStandingOutput'], $LeagueGeneral, $ColumnPerTable, $LeagueGeneral['HowManyPlayOffTeam']/2,$DatabaseFile,$ImagesCDNPath);
			}else{
				PrintStandingTable($Standing, $TypeText, $LeagueOutputOption['StandardStandingOutput'], $LeagueGeneral, $ColumnPerTable, $LeagueGeneral['HowManyPlayOffTeam'],$DatabaseFile,$ImagesCDNPath);
			}
		}}
	}
}
?>
</div>
<div class="tabmain" id="tabmain3">
<?php
If ($StandingQueryOK == True){
	foreach ($Division as $Value){
		$Query = "SELECT Team" . $TypeTextTeam . "Stat.*, Team" . $TypeText . "Info.Conference, Team" . $TypeText . "Info.Division,Team" . $TypeText . "Info.TeamThemeID, RankingOrder.Type FROM (Team" . $TypeTextTeam . "Stat INNER JOIN Team" . $TypeText . "Info ON Team" . $TypeTextTeam . "Stat.Number = Team" . $TypeText . "Info.Number) INNER JOIN RankingOrder ON Team" . $TypeTextTeam . "Stat.Number = RankingOrder.Team" . $TypeText . "Number WHERE (((Team" . $TypeText . "Info.Division)=\"" . $Value . "\") AND ((RankingOrder.Type)=0)) ORDER BY RankingOrder.TeamOrder";
		$Standing = $db->query($Query);
		$DataReturn = $db->query($Query); /* Run the Query Twice to Loop Second Array to confirm the first Query Return Data  */
		If ($DataReturn == True){if($DataReturn->fetchArray()){ /* Only Print Information if Query has row */
			echo "<h2>" . $Value . "</h2>";
			PrintStandingTop($TeamLang, $LeagueOutputOption['StandardStandingOutput'], $LeagueGeneral);
			PrintStandingTable($Standing, $TypeText, $LeagueOutputOption['StandardStandingOutput'], $LeagueGeneral,$ColumnPerTable,0,$DatabaseFile,$ImagesCDNPath);
		}}
	}
}
?>
</div>
<div class="tabmain" id="tabmain4">
<?php
If ($StandingQueryOK == True){
	Echo "<h2>" . $StandingLang['Overall'] . "</h2>";
	$Query = "SELECT Team" . $TypeTextTeam . "Stat.*, Team" . $TypeText . "Info.Conference, Team" . $TypeText . "Info.Division,Team" . $TypeText . "Info.TeamThemeID, RankingOrder.Type FROM (Team" . $TypeTextTeam . "Stat INNER JOIN Team" . $TypeText . "Info ON Team" . $TypeTextTeam . "Stat.Number = Team" . $TypeText . "Info.Number) INNER JOIN RankingOrder ON Team" . $TypeTextTeam . "Stat.Number = RankingOrder.Team" . $TypeText . "Number WHERE (((RankingOrder.Type)=0)) ORDER BY RankingOrder.TeamOrder";
	$Standing = $db->query($Query);
	$DataReturn = $db->query($Query); /* Run the Query Twice to Loop Second Array to confirm the first Query Return Data  */
	If ($DataReturn == True){
		PrintStandingTop($TeamLang, $LeagueOutputOption['StandardStandingOutput'], $LeagueGeneral);
		PrintStandingTable($Standing, $TypeText, $LeagueOutputOption['StandardStandingOutput'], $LeagueGeneral,$ColumnPerTable,0,$DatabaseFile,$ImagesCDNPath);
	}
}
?>

</div>

<div class="tabmain<?php if ($Playoff == True){echo " active";}?>" id="tabmain5">






<?php
If ($StandingQueryOK == True){
	If ($LeagueGeneral['PlayOffWinner'] != 0 AND $Playoff == True){
		$Winner = $db->querySingle("Select Team" . $TypeText . "Info.Name,Team" . $TypeText . "Info.TeamThemeID from Team" . $TypeText . "Info WHERE Team" . $TypeText . "Info.Number = ". $LeagueGeneral['PlayOffWinner'],true);
		echo "<div class=\"STHSCenter\">";
		echo "<td>";If ($Winner['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Winner['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPStandingPlayoffWinnerImage \" />";}
		echo "<h1>" . $Winner['Name'] . $StandingLang['WinsPlayoff'] . "</h1><br /><br /></div>";
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
	$Query = "SELECT Playoff" . $TypeText . "Number.* FROM Playoff" . $TypeText . "Number ORDER BY Playoff" . $TypeText . "Number.Number";
	$PlayoffStanding = $db->query($Query);
	if (empty($PlayoffStanding) == false){while ($Row = $PlayoffStanding ->fetchArray()) {
		echo "<tr>";
		If ($Row['Round1'] == 0){echo "<td></td>";}else{
			$Round1 = $db->querySingle("SELECT Playoff" . $TypeText . ".*, TeamInfoHome.Name as HomeTeamName, TeamInfoVisitor.Name as VisitorTeamName, TeamInfoHome.TeamThemeID as HomeThemID, TeamInfoVisitor.TeamThemeID as VisitorThemID FROM (Playoff" . $TypeText . " INNER JOIN Team" . $TypeText . "Info AS TeamInfoHome ON Playoff" . $TypeText . ".HomeTeam = TeamInfoHome.Number) LEFT JOIN Team" . $TypeText . "Info AS TeamInfoVisitor ON Playoff" . $TypeText . ".VisitorTeam = TeamInfoVisitor.Number WHERE Playoff" . $TypeText . ".Number = " . $Row['Round1'],true);	
			if($Round1 != Null){
				echo "<td>";If ($Round1['VisitorThemID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Round1['VisitorThemID'] .".png\" alt=\"\" class=\"STHSPHPStandingTeamImage\" />";
				echo "<a href=\"" . $TypeText . "Team.php?Team=" . $Round1['VisitorTeam'] . "\">" . $Round1['VisitorTeamName'] . " - " . $Round1['VisitorWin'] . "</a><br />";}
				If ($Round1['HomeThemID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Round1['HomeThemID'] .".png\" alt=\"\" class=\"STHSPHPStandingTeamImage\" />";
				echo "<a href=\"" . $TypeText . "Team.php?Team=" . $Round1['HomeTeam'] . "\">" . $Round1['HomeTeamName'] . " - " . $Round1['HomeWin'] . "</a><br /><br /></td>\n";}
			}
		}
		If ($Row['Round2'] == 0){echo "<td></td>";}else{
			$Round2 = $db->querySingle("SELECT Playoff" . $TypeText . ".*, TeamInfoHome.Name as HomeTeamName, TeamInfoVisitor.Name as VisitorTeamName, TeamInfoHome.TeamThemeID as HomeThemID, TeamInfoVisitor.TeamThemeID as VisitorThemID FROM (Playoff" . $TypeText . " INNER JOIN Team" . $TypeText . "Info AS TeamInfoHome ON Playoff" . $TypeText . ".HomeTeam = TeamInfoHome.Number) LEFT JOIN Team" . $TypeText . "Info AS TeamInfoVisitor ON Playoff" . $TypeText . ".VisitorTeam = TeamInfoVisitor.Number WHERE Playoff" . $TypeText . ".Number = " . $Row['Round2'],true);
			if($Round2 != Null){
				echo "<td>";If ($Round2['VisitorThemID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Round2['VisitorThemID'] .".png\" alt=\"\" class=\"STHSPHPStandingTeamImage\" />";
				echo "<a href=\"" . $TypeText . "Team.php?Team=" . $Round2['VisitorTeam'] . "\">" . $Round2['VisitorTeamName'] . " - " . $Round2['VisitorWin'] . "</a><br />";}
				If ($Round2['HomeThemID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Round2['HomeThemID'] .".png\" alt=\"\" class=\"STHSPHPStandingTeamImage\" />";
				echo "<a href=\"" . $TypeText . "Team.php?Team=" . $Round2['HomeTeam'] . "\">" . $Round2['HomeTeamName'] . " - " . $Round2['HomeWin'] . "</a><br /><br /></td>\n";}
			}
		}
		If ($Row['Round3'] == 0){echo "<td></td>";}else{
			$Round3 = $db->querySingle("SELECT Playoff" . $TypeText . ".*, TeamInfoHome.Name as HomeTeamName, TeamInfoVisitor.Name as VisitorTeamName, TeamInfoHome.TeamThemeID as HomeThemID, TeamInfoVisitor.TeamThemeID as VisitorThemID FROM (Playoff" . $TypeText . " INNER JOIN Team" . $TypeText . "Info AS TeamInfoHome ON Playoff" . $TypeText . ".HomeTeam = TeamInfoHome.Number) LEFT JOIN Team" . $TypeText . "Info AS TeamInfoVisitor ON Playoff" . $TypeText . ".VisitorTeam = TeamInfoVisitor.Number WHERE Playoff" . $TypeText . ".Number = " . $Row['Round3'],true);	
			if($Round3 != Null){
				echo "<td>";If ($Round3['VisitorThemID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Round3['VisitorThemID'] .".png\" alt=\"\" class=\"STHSPHPStandingTeamImage\" />";		
				echo "<a href=\"" . $TypeText . "Team.php?Team=" . $Round3['VisitorTeam'] . "\">" . $Round3['VisitorTeamName'] . " - " . $Round3['VisitorWin'] . "</a><br />";}
				If ($Round3['HomeThemID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Round3['HomeThemID'] .".png\" alt=\"\" class=\"STHSPHPStandingTeamImage\" />";
				echo "<a href=\"" . $TypeText . "Team.php?Team=" . $Round3['HomeTeam'] . "\">" . $Round3['HomeTeamName'] . " - " . $Round3['HomeWin'] . "</a><br /><br /></td>\n";}
			}
		}
		If ($Row['Round4'] == 0){echo "<td></td>";}else{
			$Round4 = $db->querySingle("SELECT Playoff" . $TypeText . ".*, TeamInfoHome.Name as HomeTeamName, TeamInfoVisitor.Name as VisitorTeamName, TeamInfoHome.TeamThemeID as HomeThemID, TeamInfoVisitor.TeamThemeID as VisitorThemID FROM (Playoff" . $TypeText . " INNER JOIN Team" . $TypeText . "Info AS TeamInfoHome ON Playoff" . $TypeText . ".HomeTeam = TeamInfoHome.Number) LEFT JOIN Team" . $TypeText . "Info AS TeamInfoVisitor ON Playoff" . $TypeText . ".VisitorTeam = TeamInfoVisitor.Number WHERE Playoff" . $TypeText . ".Number = " . $Row['Round4'],true);	
			if($Round4 != Null){
				echo "<td>";If ($Round4['VisitorThemID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Round4['VisitorThemID'] .".png\" alt=\"\" class=\"STHSPHPStandingTeamImage\" />";			
				echo "<a href=\"" . $TypeText . "Team.php?Team=" . $Round4['VisitorTeam'] . "\">" . $Round4['VisitorTeamName'] . " - " . $Round4['VisitorWin'] . "</a><br />";}
				If ($Round4['HomeThemID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Round4['HomeThemID'] .".png\" alt=\"\" class=\"STHSPHPStandingTeamImage\" />";
				echo "<a href=\"" . $TypeText . "Team.php?Team=" . $Round4['HomeTeam'] . "\">" . $Round4['HomeTeamName'] . " - " . $Round4['HomeWin'] . "</a><br /><br /></td>\n";}
			}
		}
		If ($Row['Round5'] == 0){echo "<td></td>";}else{
			$Round5 = $db->querySingle("SELECT Playoff" . $TypeText . ".*, TeamInfoHome.Name as HomeTeamName, TeamInfoVisitor.Name as VisitorTeamName, TeamInfoHome.TeamThemeID as HomeThemID, TeamInfoVisitor.TeamThemeID as VisitorThemID FROM (Playoff" . $TypeText . " INNER JOIN Team" . $TypeText . "Info AS TeamInfoHome ON Playoff" . $TypeText . ".HomeTeam = TeamInfoHome.Number) LEFT JOIN Team" . $TypeText . "Info AS TeamInfoVisitor ON Playoff" . $TypeText . ".VisitorTeam = TeamInfoVisitor.Number WHERE Playoff" . $TypeText . ".Number = " . $Row['Round5'],true);	
			if($Round5 != Null){
				echo "<td>";If ($Round5['VisitorThemID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Round5['VisitorThemID'] .".png\" alt=\"\" class=\"STHSPHPStandingTeamImage\" />";	
				echo "<a href=\"" . $TypeText . "Team.php?Team=" . $Round5['VisitorTeam'] . "\">" . $Round5['VisitorTeamName'] . " - " . $Round5['VisitorWin'] . "</a><br />";}
				If ($Round4['HomeThemID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Round4['HomeThemID'] .".png\" alt=\"\" class=\"STHSPHPStandingTeamImage\" />";
				echo "<a href=\"" . $TypeText . "Team.php?Team=" . $Round5['HomeTeam'] . "\">" . $Round5['HomeTeamName'] . " - " . $Round5['HomeWin'] . "</a><br /><br /></td>\n";}
			}
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

