<!DOCTYPE html>
<?php include "Header.php";?>
<?php
$TypeText = (string)"Pro";$TitleType = $DynamicTitleLang['Pro'];
$Title = (string)"";
$DatabaseFound = (boolean)False;
$Active = 2; /* Show Webpage Top Menu */
If (file_exists($DatabaseFile) == false){
	$DatabaseFound = False;
	$LeagueName = $DatabaseNotFound;
	$Standing = Null;
	$LeagueGeneral = Null;
	echo "<title>" . $DatabaseNotFound . "</title>";
	$Title = $DatabaseNotFound;
}else{
	$DatabaseFound = True;
	$Title = (string)"";
	$LeagueName = (string)"";
	if(isset($_GET['Farm'])){$TypeText = "Farm";$TitleType = $DynamicTitleLang['Farm'];$Active = 3;}

	$db = new SQLite3($DatabaseFile);
	
	$Query = "Select Name, PointSystemW," . $TypeText . "ConferenceName1 AS ConferenceName1," . $TypeText . "ConferenceName2 AS ConferenceName2," . $TypeText . "DivisionName1 AS DivisionName1," . $TypeText . "DivisionName2 AS DivisionName2," . $TypeText . "DivisionName3 AS DivisionName3," . $TypeText . "DivisionName4 AS DivisionName4," . $TypeText . "DivisionName5 AS DivisionName5," . $TypeText . "DivisionName6 AS DivisionName6," . $TypeText . "HowManyPlayOffTeam AS HowManyPlayOffTeam," . $TypeText . "DivisionNewNHLPlayoff  AS DivisionNewNHLPlayoff,PlayOffWinner" . $TypeText . " AS PlayOffWinner, PlayOffStarted, PlayOffRound from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	$Conference = array($LeagueGeneral['ConferenceName1'], $LeagueGeneral['ConferenceName2']);
	$Division = array($LeagueGeneral['DivisionName1'], $LeagueGeneral['DivisionName2'], $LeagueGeneral['DivisionName3'], $LeagueGeneral['DivisionName4'], $LeagueGeneral['DivisionName5'], $LeagueGeneral['DivisionName6']);
	if ($LeagueGeneral['PlayOffStarted'] == "True"){
		$Title = $LeagueName . " - " . $StandingLang['Playoff'] . " " . $TitleType;
	}else{
		$Title = $LeagueName . " - " . $StandingLang['Standing'] . " " . $TitleType;
	}
}
echo "<title>" . $Title . "</title>";

function PrintStandingTop($TeamStatLang) {
echo "<table class=\"tablesorter STHSPHPStanding_Table\"><thead><tr>";
echo "<th title=\"Position\" class=\"STHSW35\">PO</th>";
echo "<th title=\"Team Name\" class=\"STHSW200\">" . $TeamStatLang['TeamName'] ."</th>";
echo "<th title=\"Games Played\" class=\"STHSW30\">GP</th>";
echo "<th title=\"Wins\" class=\"STHSW30\">W</th>";
echo "<th title=\"Loss\" class=\"STHSW30\">L</th>";
echo "<th title=\"Overtime Loss\" class=\"STHSW30\">OTL</th>";
echo "<th title=\"Points\" class=\"STHSW30\">P</th>";
echo "<th title=\"Normal Wins + Overtime Win\" class=\"STHSW30\">ROW</th>";
echo "<th title=\"Goals For\" class=\"STHSW30\">GF</th>";
echo "<th title=\"Goals Against\" class=\"STHSW30\">GA</th>";
echo "<th title=\"Goals For Diffirencial against Goals Against\" class=\"STHSW30\">Diff</th>";
echo "<th title=\"Points Percentage\" class=\"STHSW45\">PCT</th>";
echo "<th title=\"Home Only\" class=\"STHSW75\">" . $TeamStatLang['Home'] ."</th>";
echo "<th title=\"Visitor Only\" class=\"STHSW75\">" . $TeamStatLang['Visitor'] ."</th>";
echo "<th title=\"Last 10 Game\" class=\"STHSW75\">" . $TeamStatLang['Last10'] ."</th>";
echo "<th title=\"Streak\" class=\"STHSW30\">STK</th>";
echo "</tr></thead><tbody>";
}

Function PrintStandingTable($Standing, $TypeText, $PointSystem, $LinesNumber = 0){
$LoopCount =0;
while ($row = $Standing ->fetchArray()) {
	$LoopCount +=1;
	PrintStandingTableRow($row, $TypeText, $PointSystem, $LoopCount);
	If ($LoopCount > 0 AND $LoopCount == $LinesNumber){echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"16\"><hr /></td></tr>";}
}
echo "</tbody></table>";
}

Function PrintStandingTableRow($row, $TypeText, $PointSystem, $LoopCount){
	echo "<tr><td>" . $LoopCount . "</td>";
	echo "<td>";
	if($row['StandingPlayoffTitle']=="E"){echo "";
	} else if($row['StandingPlayoffTitle']=="X"){echo "X -";
	} else if($row['StandingPlayoffTitle']=="Y"){echo "Y -";
	} else if($row['StandingPlayoffTitle']=="Z"){echo "Z -";
	} else if($row['StandingPlayoffTitle']=="Z" && $row['PowerRanking']==1){
	  echo "P -";}
	echo "<a href=\"" . $TypeText . "Team.php?Team=" . $row['Number'] . "\">" . $row['Name'] . "</a></td>";
	echo "<td>" . $row['GP'] . "</td>";
	echo "<td>" . ($row['W'] + $row['OTW'] + $row['SOW']) . "</td>";
	echo "<td>" . $row['L'] . "</td>";
	echo "<td>" . ($row['OTL'] + $row['SOL']) . "</td>";	
	echo "<td><strong>" . $row['Points'] . "</strong></td>";	
	echo "<td>" . ($row['W'] + $row['OTW']) . "</td>";		
	echo "<td>" . $row['GF'] . "</td>";
	echo "<td>" . $row['GA'] . "</td>";
	echo "<td>" . ($row['GF'] - $row['GA']) . "</td>";
	if ($row['GP'] > 0 AND $PointSystem > 0){echo "<td>" . number_Format(($row['Points'] / ($row['GP'] * $PointSystem)),3) . "</td>";}else{echo "<td>" . number_Format("0",3) . "</td>";}	
	echo "<td>" . ($row['HomeW'] + $row['HomeOTW'] + $row['HomeSOW'])."-".$row['HomeL']."-".($row['HomeOTL']+$row['HomeSOL']) . "</td>";
	echo "<td>" . ($row['W'] + $row['OTW'] + $row['SOW'] - $row['HomeW'] - $row['HomeOTW'] - $row['HomeSOW'])."-".($row['L'] - $row['HomeL'])."-".($row['OTL']+$row['SOL']-$row['HomeOTL']-$row['HomeSOL']) . "</td>";
	echo "<td>" . ($row['Last10W'] + $row['Last10OTW'] + $row['Last10SOW'])."-".$row['Last10L']."-".($row['Last10OTL']+$row['Last10SOL']) . "</td>";
	echo "<td>" . $row['Streak'] . "</td>";
	echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
}

?>

<style type="text/css">
@media screen and (max-width: 1060px) {
.STHSWarning {display:block;}
.STHSPHPStanding_Table thead th:nth-last-child(2){display:none;}
.STHSPHPStanding_Table tbody td:nth-last-child(2){display:none;}
.STHSPHPStanding_Table thead th:nth-last-child(3){display:none;}
.STHSPHPStanding_Table tbody td:nth-last-child(3){display:none;}
.STHSPHPStanding_Table thead th:nth-last-child(4){display:none;}
.STHSPHPStanding_Table tbody td:nth-last-child(4){display:none;}
}@media screen and (max-width: 890px) {
.STHSPHPStanding_Table thead th:nth-last-child(5){display:none;}
.STHSPHPStanding_Table tbody td:nth-last-child(5){display:none;}
.STHSPHPStanding_Table thead th:nth-last-child(6){display:none;}
.STHSPHPStanding_Table tbody td:nth-last-child(6){display:none;}
}
.STHSPHPStanding_Table tbody td.staticTD {font-size:9pt;border-right:hidden; border-left:hidden;}

<?php 
if ($LeagueGeneral['PlayOffStarted'] == "True"){
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
<?php echo "<h1>" . $Title . "</h1>"; ?>
<div class="STHSWarning"><?php echo $WarningResolution;?><br /></div>
<div style="width:99%;margin:auto;">
<div class="tabsmain standard"><ul class="tabmain-links">
<?php
if ($LeagueGeneral['PlayOffStarted'] == "True"){
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
<div class="tabmain <?php If ($LeagueGeneral['DivisionNewNHLPlayoff'] == "True"){echo "active";}?>" id="tabmain1">

<?php
If ($DatabaseFound == True){
	echo "<h2>" . $LeagueGeneral['ConferenceName1'] . "</h2>";
	PrintStandingTop($TeamStatLang);

	/* Division 1 */
	Echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"16\">" . $LeagueGeneral['DivisionName1'] . "</td></tr>";
	$Query = " SELECT Team" . $TypeText . "Stat.*, Team" . $TypeText . "Info.Conference, Team" . $TypeText . "Info.Division, RankingOrder.Type FROM (Team" . $TypeText . "Stat INNER JOIN Team" . $TypeText . "Info ON Team" . $TypeText . "Stat.Number = Team" . $TypeText . "Info.Number) INNER JOIN RankingOrder ON Team" . $TypeText . "Stat.Number = RankingOrder.Team" . $TypeText . "Number WHERE (((Team" . $TypeText . "Info.Division)=\"" . $LeagueGeneral['DivisionName1'] . "\") AND ((RankingOrder.Type)=0)) ORDER BY RankingOrder.TeamOrder LIMIT 3";
	$Standing = $db->query($Query);
	$LoopCount =0;
	if (empty($Standing) == false){while ($row = $Standing ->fetchArray()) {
		$LoopCount +=1;
		PrintStandingTableRow($row, $TypeText, $LeagueGeneral['PointSystemW'], $LoopCount);
	}}
		
	/* Division 2 */	
	Echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"16\">" . $LeagueGeneral['DivisionName2'] . "</td></tr>";
	$Query = " SELECT Team" . $TypeText . "Stat.*, Team" . $TypeText . "Info.Conference, Team" . $TypeText . "Info.Division, RankingOrder.Type FROM (Team" . $TypeText . "Stat INNER JOIN Team" . $TypeText . "Info ON Team" . $TypeText . "Stat.Number = Team" . $TypeText . "Info.Number) INNER JOIN RankingOrder ON Team" . $TypeText . "Stat.Number = RankingOrder.Team" . $TypeText . "Number WHERE (((Team" . $TypeText . "Info.Division)=\"" . $LeagueGeneral['DivisionName2'] . "\") AND ((RankingOrder.Type)=0)) ORDER BY RankingOrder.TeamOrder LIMIT 3";
	$Standing = $db->query($Query);
	$LoopCount =0;
	if (empty($Standing) == false){while ($row = $Standing ->fetchArray()) {
		$LoopCount +=1;
		PrintStandingTableRow($row, $TypeText, $LeagueGeneral['PointSystemW'], $LoopCount);
	}}

	/* Overall for Conference 1 */	
	Echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"16\">" . $StandingLang['Wildcard'] ."</td></tr>";
	$Query = " SELECT Team" . $TypeText . "Stat.*, Team" . $TypeText . "Info.Conference, Team" . $TypeText . "Info.Division, RankingOrder.Type FROM (Team" . $TypeText . "Stat INNER JOIN Team" . $TypeText . "Info ON Team" . $TypeText . "Stat.Number = Team" . $TypeText . "Info.Number) INNER JOIN RankingOrder ON Team" . $TypeText . "Stat.Number = RankingOrder.Team" . $TypeText . "Number WHERE (((Team" . $TypeText . "Info.Conference)=\"" . $LeagueGeneral['ConferenceName1'] . "\") AND ((RankingOrder.Type)=1)) ORDER BY RankingOrder.TeamOrder";
	$Standing = $db->query($Query);
	$LoopCount =0;
	if (empty($Standing) == false){while ($row = $Standing ->fetchArray()) {
		$LoopCount +=1;
		If ($LoopCount > 6 ){PrintStandingTableRow($row, $TypeText, $LeagueGeneral['PointSystemW'], $LoopCount);}
		If ($LoopCount == 8){echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"16\"><hr /></td></tr>";}
	}}

	echo "</tbody></table>";	


	echo "<h2>" . $LeagueGeneral['ConferenceName2'] . "</h2>";
	PrintStandingTop($TeamStatLang);

	/* Division 4 */
	Echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"16\">" . $LeagueGeneral['DivisionName4'] . "</td></tr>";
	$Query = " SELECT Team" . $TypeText . "Stat.*, Team" . $TypeText . "Info.Conference, Team" . $TypeText . "Info.Division, RankingOrder.Type FROM (Team" . $TypeText . "Stat INNER JOIN Team" . $TypeText . "Info ON Team" . $TypeText . "Stat.Number = Team" . $TypeText . "Info.Number) INNER JOIN RankingOrder ON Team" . $TypeText . "Stat.Number = RankingOrder.Team" . $TypeText . "Number WHERE (((Team" . $TypeText . "Info.Division)=\"" . $LeagueGeneral['DivisionName4'] . "\") AND ((RankingOrder.Type)=0)) ORDER BY RankingOrder.TeamOrder LIMIT 3";
	$Standing = $db->query($Query);
	$LoopCount =0;
	if (empty($Standing) == false){while ($row = $Standing ->fetchArray()) {
		$LoopCount +=1;
		PrintStandingTableRow($row, $TypeText, $LeagueGeneral['PointSystemW'], $LoopCount);
	}}
		
	/* Division 5 */	
	Echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"16\">" . $LeagueGeneral['DivisionName5'] . "</td></tr>";
	$Query = " SELECT Team" . $TypeText . "Stat.*, Team" . $TypeText . "Info.Conference, Team" . $TypeText . "Info.Division, RankingOrder.Type FROM (Team" . $TypeText . "Stat INNER JOIN Team" . $TypeText . "Info ON Team" . $TypeText . "Stat.Number = Team" . $TypeText . "Info.Number) INNER JOIN RankingOrder ON Team" . $TypeText . "Stat.Number = RankingOrder.Team" . $TypeText . "Number WHERE (((Team" . $TypeText . "Info.Division)=\"" . $LeagueGeneral['DivisionName5'] . "\") AND ((RankingOrder.Type)=0)) ORDER BY RankingOrder.TeamOrder LIMIT 3";
	$Standing = $db->query($Query);
	$LoopCount =0;
	if (empty($Standing) == false){while ($row = $Standing ->fetchArray()) {
		$LoopCount +=1;
		PrintStandingTableRow($row, $TypeText, $LeagueGeneral['PointSystemW'], $LoopCount);
	}}

	/* Overall for Conference 2 */	
	Echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"16\">" . $StandingLang['Wildcard'] . "</td></tr>";
	$Query = " SELECT Team" . $TypeText . "Stat.*, Team" . $TypeText . "Info.Conference, Team" . $TypeText . "Info.Division, RankingOrder.Type FROM (Team" . $TypeText . "Stat INNER JOIN Team" . $TypeText . "Info ON Team" . $TypeText . "Stat.Number = Team" . $TypeText . "Info.Number) INNER JOIN RankingOrder ON Team" . $TypeText . "Stat.Number = RankingOrder.Team" . $TypeText . "Number WHERE (((Team" . $TypeText . "Info.Conference)=\"" . $LeagueGeneral['ConferenceName2'] . "\") AND ((RankingOrder.Type)=2)) ORDER BY RankingOrder.TeamOrder";
	$Standing = $db->query($Query);
	$LoopCount =0;
	if (empty($Standing) == false){while ($row = $Standing ->fetchArray()) {
		$LoopCount +=1;
		If ($LoopCount > 6 ){PrintStandingTableRow($row, $TypeText, $LeagueGeneral['PointSystemW'], $LoopCount);}
		If ($LoopCount == 8){echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"16\"><hr /></td></tr>";}
	}}

	echo "</tbody></table>";
}
?>

</div>
<div class="tabmain <?php If ($LeagueGeneral['DivisionNewNHLPlayoff'] == "False"){echo "active";}?>" id="tabmain2">
<?php
If ($DatabaseFound == True){
	$LoopCount =0;
	foreach ($Conference as $Value){
		$LoopCount +=1;
		$Query = " SELECT Team" . $TypeText . "Stat.*, Team" . $TypeText . "Info.Conference, Team" . $TypeText . "Info.Division, RankingOrder.Type FROM (Team" . $TypeText . "Stat INNER JOIN Team" . $TypeText . "Info ON Team" . $TypeText . "Stat.Number = Team" . $TypeText . "Info.Number) INNER JOIN RankingOrder ON Team" . $TypeText . "Stat.Number = RankingOrder.Team" . $TypeText . "Number WHERE (((Team" . $TypeText . "Info.Conference)=\"" . $Value . "\") AND ((RankingOrder.Type)=" . $LoopCount . ")) ORDER BY RankingOrder.TeamOrder";
		$Standing = $db->query($Query);
		$DataReturn = $db->query($Query); /* Run the Query Twice to Loop Second Array to confirm the first Query Return Data  */
		if($DataReturn->fetchArray()){ /* Only Print Information if Query has row */
			echo "<h2>" . $Value . "</h2>";
			PrintStandingTop($TeamStatLang);
			PrintStandingTable($Standing, $TypeText, $LeagueGeneral['PointSystemW'],$LeagueGeneral['HowManyPlayOffTeam']/2);
		}
	}
}
?>
</div>
<div class="tabmain" id="tabmain3">
<?php
If ($DatabaseFound == True){
	foreach ($Division as $Value){
		$Query = " SELECT Team" . $TypeText . "Stat.*, Team" . $TypeText . "Info.Conference, Team" . $TypeText . "Info.Division, RankingOrder.Type FROM (Team" . $TypeText . "Stat INNER JOIN Team" . $TypeText . "Info ON Team" . $TypeText . "Stat.Number = Team" . $TypeText . "Info.Number) INNER JOIN RankingOrder ON Team" . $TypeText . "Stat.Number = RankingOrder.Team" . $TypeText . "Number WHERE (((Team" . $TypeText . "Info.Division)=\"" . $Value . "\") AND ((RankingOrder.Type)=0)) ORDER BY RankingOrder.TeamOrder";
		$Standing = $db->query($Query);
		$DataReturn = $db->query($Query); /* Run the Query Twice to Loop Second Array to confirm the first Query Return Data  */
		if($DataReturn->fetchArray()){ /* Only Print Information if Query has row */
			echo "<h2>" . $Value . "</h2>";
			PrintStandingTop($TeamStatLang);
			PrintStandingTable($Standing, $TypeText, $LeagueGeneral['PointSystemW'],0);
		}
	}
}
?>
</div>
<div class="tabmain" id="tabmain4">
<?php
If ($DatabaseFound == True){
	Echo "<h2>" . $StandingLang['Overall'] . "</h2>";
	$Query = "SELECT Team" . $TypeText . "Stat.*, RankingOrder.TeamOrder FROM Team" . $TypeText . "Stat INNER JOIN RankingOrder ON Team" . $TypeText . "Stat.Number = RankingOrder.Team" . $TypeText . "Number WHERE (((RankingOrder.Type)=0)) ORDER BY RankingOrder.TeamOrder";
	$Standing = $db->query($Query);
	PrintStandingTop($TeamStatLang);
	PrintStandingTable($Standing, $TypeText, $LeagueGeneral['PointSystemW'],0);
}
?>

</div>

<div class="tabmain<?php if ($LeagueGeneral['PlayOffStarted'] == "True"){echo " active";}?>" id="tabmain5">






<?php
If ($DatabaseFound == True){
	If ($LeagueGeneral['PlayOffWinner'] != 0 AND $LeagueGeneral['PlayOffStarted'] == "True"){
		$Winner = $db->querySingle("Select Team" . $TypeText . "Info.Name from Team" . $TypeText . "Info WHERE Team" . $TypeText . "Info.Number = ". $LeagueGeneral['PlayOffWinner'],true);
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
	$Query = "SELECT Playoff" . $TypeText . "Number.* FROM Playoff" . $TypeText . "Number ORDER BY Playoff" . $TypeText . "Number.Number";
	$PlayoffStanding = $db->query($Query);
	if (empty($PlayoffStanding) == false){while ($Row = $PlayoffStanding ->fetchArray()) {
		echo "<tr>";
		If ($Row['Round1'] == 0){echo "<td></td>";}else{
			$Round1 = $db->querySingle("SELECT Playoff" . $TypeText . ".*, TeamInfoHome.Name as HomeTeamName, TeamInfoVisitor.Name as VisitorTeamName FROM (Playoff" . $TypeText . " INNER JOIN Team" . $TypeText . "Info AS TeamInfoHome ON Playoff" . $TypeText . ".HomeTeam = TeamInfoHome.Number) LEFT JOIN Team" . $TypeText . "Info AS TeamInfoVisitor ON Playoff" . $TypeText . ".VisitorTeam = TeamInfoVisitor.Number WHERE Playoff" . $TypeText . ".Number = " . $Row['Round1'],true);	
			echo "<td><a href=\"" . $TypeText . "Team.php?Team=" . $Round1['VisitorTeam'] . "\">" . $Round1['VisitorTeamName'] . " - " . $Round1['VisitorWin'] . "</a><br />";
			echo "<a href=\"" . $TypeText . "Team.php?Team=" . $Round1['HomeTeam'] . "\">" . $Round1['HomeTeamName'] . " - " . $Round1['HomeWin'] . "</a><br /><br /></td>";
		}
		If ($Row['Round2'] == 0){echo "<td></td>";}else{
			$Round2 = $db->querySingle("SELECT Playoff" . $TypeText . ".*, TeamInfoHome.Name as HomeTeamName, TeamInfoVisitor.Name as VisitorTeamName FROM (Playoff" . $TypeText . " INNER JOIN Team" . $TypeText . "Info AS TeamInfoHome ON Playoff" . $TypeText . ".HomeTeam = TeamInfoHome.Number) LEFT JOIN Team" . $TypeText . "Info AS TeamInfoVisitor ON Playoff" . $TypeText . ".VisitorTeam = TeamInfoVisitor.Number WHERE Playoff" . $TypeText . ".Number = " . $Row['Round2'],true);	
			echo "<td><a href=\"" . $TypeText . "Team.php?Team=" . $Round2['VisitorTeam'] . "\">" . $Round2['VisitorTeamName'] . " - " . $Round2['VisitorWin'] . "</a><br />";
			echo "<a href=\"" . $TypeText . "Team.php?Team=" . $Round2['HomeTeam'] . "\">" . $Round2['HomeTeamName'] . " - " . $Round2['HomeWin'] . "</a><br /><br /></td>";
		}
		If ($Row['Round3'] == 0){echo "<td></td>";}else{
			$Round3 = $db->querySingle("SELECT Playoff" . $TypeText . ".*, TeamInfoHome.Name as HomeTeamName, TeamInfoVisitor.Name as VisitorTeamName FROM (Playoff" . $TypeText . " INNER JOIN Team" . $TypeText . "Info AS TeamInfoHome ON Playoff" . $TypeText . ".HomeTeam = TeamInfoHome.Number) LEFT JOIN Team" . $TypeText . "Info AS TeamInfoVisitor ON Playoff" . $TypeText . ".VisitorTeam = TeamInfoVisitor.Number WHERE Playoff" . $TypeText . ".Number = " . $Row['Round3'],true);	
			echo "<td><a href=\"" . $TypeText . "Team.php?Team=" . $Round3['VisitorTeam'] . "\">" . $Round3['VisitorTeamName'] . " - " . $Round3['VisitorWin'] . "</a><br />";
			echo "<a href=\"" . $TypeText . "Team.php?Team=" . $Round3['HomeTeam'] . "\">" . $Round3['HomeTeamName'] . " - " . $Round3['HomeWin'] . "</a><br /><br /></td>";
		}
		If ($Row['Round4'] == 0){echo "<td></td>";}else{
			$Round4 = $db->querySingle("SELECT Playoff" . $TypeText . ".*, TeamInfoHome.Name as HomeTeamName, TeamInfoVisitor.Name as VisitorTeamName FROM (Playoff" . $TypeText . " INNER JOIN Team" . $TypeText . "Info AS TeamInfoHome ON Playoff" . $TypeText . ".HomeTeam = TeamInfoHome.Number) LEFT JOIN Team" . $TypeText . "Info AS TeamInfoVisitor ON Playoff" . $TypeText . ".VisitorTeam = TeamInfoVisitor.Number WHERE Playoff" . $TypeText . ".Number = " . $Row['Round4'],true);	
			echo "<td><a href=\"" . $TypeText . "Team.php?Team=" . $Round4['VisitorTeam'] . "\">" . $Round4['VisitorTeamName'] . " - " . $Round4['VisitorWin'] . "</a><br />";
			echo "<a href=\"" . $TypeText . "Team.php?Team=" . $Round4['HomeTeam'] . "\">" . $Round4['HomeTeamName'] . " - " . $Round4['HomeWin'] . "</a><br /><br /></td>";
		}
		If ($Row['Round5'] == 0){echo "<td></td>";}else{
			$Round5 = $db->querySingle("SELECT Playoff" . $TypeText . ".*, TeamInfoHome.Name as HomeTeamName, TeamInfoVisitor.Name as VisitorTeamName FROM (Playoff" . $TypeText . " INNER JOIN Team" . $TypeText . "Info AS TeamInfoHome ON Playoff" . $TypeText . ".HomeTeam = TeamInfoHome.Number) LEFT JOIN Team" . $TypeText . "Info AS TeamInfoVisitor ON Playoff" . $TypeText . ".VisitorTeam = TeamInfoVisitor.Number WHERE Playoff" . $TypeText . ".Number = " . $Row['Round5'],true);	
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



<script type="text/javascript">
$(function(){
  $(".STHSPHPStanding_Table").tablesorter({widgets:['staticRow']});
});
</script>

</div>


<?php
include "Footer.php";
?>

