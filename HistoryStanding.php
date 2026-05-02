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
	
		$Query = "Select Name, PointSystemW, PointSystemSO, " . $TypeText . "ConferenceName1 AS ConferenceName1," . $TypeText . "ConferenceName2 AS ConferenceName2," . $TypeText . "DivisionName1 AS DivisionName1," . $TypeText . "DivisionName2 AS DivisionName2," . $TypeText . "DivisionName3 AS DivisionName3," . $TypeText . "DivisionName4 AS DivisionName4," . $TypeText . "DivisionName5 AS DivisionName5," . $TypeText . "DivisionName6 AS DivisionName6," . $TypeText . "HowManyPlayOffTeam AS HowManyPlayOffTeam," . $TypeText . "DivisionNewNHLPlayoff  AS DivisionNewNHLPlayoff,PlayOffWinner" . $TypeText . " AS PlayOffWinner, PlayOffStarted, PlayOffRound, " . $TypeText . "PlayOffLength AS PlayOffLength," . $TypeText . "HowManyPlayOffTeam AS HowManyPlayOffTeam FROM LeagueGeneral WHERE Year = " . $Year . " And Playoff = '" . $PlayoffString . "'";
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
	echo "<style>.STHSHistoryStanding_MainDiv{display:none}</style>";
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
echo "<th title=\"Points Percentage\" class=\"STHSW45\">PCT</th>";		
echo "<th title=\"Normal Wins + Overtime Win\" class=\"STHSW30\">ROW</th>";
echo "<th title=\"Goals For\" class=\"STHSW30\">GF</th>";
echo "<th title=\"Goals Against\" class=\"STHSW30\">GA</th>";
echo "<th title=\"Goals For Diffirencial against Goals Against\" class=\"STHSW30\">Diff</th>";														  
echo "<th title=\"Home Only\" class=\"STHSW75\">" . $TeamLang['Home'] ."</th>";
echo "<th title=\"Visitor Only\" class=\"STHSW75\">" . $TeamLang['Visitor'] ."</th>";
echo "</tr></thead><tbody>";
}

Function PrintStandingTable($Standing, $TypeText, $StandardStandingOutput, $PointSystemSO, $PointSystemW, $ColumnPerTable, $LinesNumber, $DatabaseFile, $Year){
$LoopCount =0;
while ($row = $Standing ->fetchArray()) {
	$LoopCount +=1;
	PrintStandingTableRow($row, $TypeText, $StandardStandingOutput, $PointSystemSO, $PointSystemW, $LoopCount, $DatabaseFile, $Year);
	If ($LoopCount > 0 AND $LoopCount == $LinesNumber){echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"" . $ColumnPerTable . "\"><hr /></td></tr>";}
}
echo "</tbody></table>";
}

Function PrintStandingTableRow($row, $TypeText, $StandardStandingOutput, $PointSystemSO, $PointSystemW, $LoopCount, $DatabaseFile, $Year){
	echo "<tr><td>" . $LoopCount . "</td>";
	echo "<td><span class=\"" . $TypeText . "Standing_Team" . $LoopCount . "\"></span>";
	echo "<a href=\"" . $TypeText . "Team.php?Year=" . $Year . "&Team=" . $row['Number'] . "\">" . $row['Name'] . "</a></td>";
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
	if ($row['GP'] > 0 AND $PointSystemW > 0){echo "<td>" . number_Format(($row['Points'] / ($row['GP'] * $PointSystemW)),3) . "</td>";}else{echo "<td>" . number_Format("0",3) . "</td>";}		
	echo "<td>" . ($row['W'] + $row['OTW']) . "</td>";		
	echo "<td>" . $row['GF'] . "</td>";
	echo "<td>" . $row['GA'] . "</td>";
	echo "<td>" . ($row['GF'] - $row['GA']) . "</td>";
	echo "<td>" . ($row['HomeW'] + $row['HomeOTW'] + $row['HomeSOW'])."-".$row['HomeL']."-".($row['HomeOTL']+$row['HomeSOL']) . "</td>";
	echo "<td>" . ($row['W'] + $row['OTW'] + $row['SOW'] - $row['HomeW'] - $row['HomeOTW'] - $row['HomeSOW'])."-".($row['L'] - $row['HomeL'])."-".($row['OTL']+$row['SOL']-$row['HomeOTL']-$row['HomeSOL']) . "</td>";
	echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
}

?>

<style>
.grid2 {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    grid-template-rows: repeat(7, auto);
    gap: 4px;
    padding: 20px;
	align:center;

    grid-template-areas:
      ".   .   S1  .   ."
      ".   .   S1  .   ."
      ".   .   S1  .   ."
      ".   .   S1  .   ."
      ".   .   S1  .   ."
      ".   .   S1  .   ."
      ".   .   S1  .   ."
}
.grid4 {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    grid-template-rows: repeat(7, auto);
    gap: 4px;
    padding: 20px;
	align:center;

    grid-template-areas:
      ".   .   S3  .   ."
      ".   .   S3  .   . "
      ".   S1  S3  S2  . "
      ".   S1  S3  S2  . "
      ".   S1  S3  S2  . "
      ".   .   S3  .   . "
      ".   .   S3  .   ."

}
.grid8 {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    grid-template-rows: repeat(11, auto);
    gap: 4px;
    padding: 20px;
	align:center;

    grid-template-areas:
      "S1  .   .   .   S3"
      "S1  .   .   .   S3"
      "S1  .   S7  .   S3"
      ".   .   S7  .   . "
      ".   S5  S7  S6  . "
      ".   S5  S7  S6  . "
      ".   S5  S7  S6  . "
      ".   .   S7  .   . "
      "S2  .   S7  .   S4"
      "S2  .   .   .   S4"
      "S2  .   .   .   S4"
}

.grid16 {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    grid-template-rows: repeat(20, auto);
    gap: 4px;
    padding: 20px;
	align:center;

    grid-template-areas:
      "S1  .   .   .   .   .   S5 "
      "S1  .   .   .   .   .   S5 "
      "S1  .   .   .   .   .   S5 "
      ".   S9  .   .   .   S11 .  "
      ".   S9  .   .   .   S11 .  "
      "S2  S9  .   .   .   S11 S6 "
      "S2  .   .   S15 .   .   S6 "
      "S2  .   .   S15 .   .   S6 "
      ".   .   .   S15 .   .   .  "
      ".   .   S13 S15 S14 .   .  "
      ".   .   S13 S15 S14 .   .  "
      ".   .   S13 S15 S14 .   .  "
      "S3  .   .   S15 .   .   S7 "
      "S3  .   .   S15 .   .   S7 "
      "S3  S10 .   S15 .   S12 S7 "
      ".   S10 .   .   .   S12 .  "
      ".   S10 .   .   .   S12 .  "
      "S4  .   .   .   .   .   S8 "
      "S4  .   .   .   .   .   S8 "
      "S4  .   .   .   .   .   S8 "
  }
  
.grid32 {
    display: grid;
    grid-template-columns: repeat(9, 1fr);
    grid-template-rows: repeat(24, auto);
    gap: 4px;
    padding: 20px;
	align:center;

    grid-template-areas:
      "S1  .   .   .   .   .   .   .   S9"
      "S1  .   .   .   .   .   .   .   S9"
      "S1  S17 .   .   .   .   .   S21 S9"
      "S2  S17 .   .   .   .   .   S21 S10"
      "S2  S17 .   .   .   .   .   S21 S10"
      "S2  .   S25 .   .   .   S27 .   S10"
      "S3  .   S25 .   .   .   S27 .   S11"
      "S3  S18 S25 .   .   .   S27 S22 S11"
      "S3  S18 .   .   .   .   .   S22 S11"
      "S4  S18 .   .   S31 .   .   S22 S12"
      "S4  .   .   .   S31 .   .   .   S12"
      "S4  .   .   S29 S31 S30 .   .   S12"
      "S5  .   .   S29 S31 S30 .   .   S13"
      "S5  .   .   S29 S31 S30 .   .   S13"
      "S5  S19 .   .   S31 .   .   S23 S13"
      "S6  S19 .   .   S31 .   .   S23 S14"
      "S6  S19 S26 .   S31 .   S28 S23 S14"
      "S6  .   S26 .   .   .   S28 .   S14"
      "S7  .   S26 .   .   .   S28 .   S15"
      "S7  S20 .   .   .   .   .   S24 S15"
      "S7  S20 .   .   .   .   .   S24 S15"
      "S8  S20 .   .   .   .   .   S24 S16"
      "S8  .   .   .   .   .   .   .   S16"
      "S8  .   .   .   .   .   .   .   S16"
}  

/* Assign grid areas */
#S1 { grid-area: S1; }
#S2 { grid-area: S2; }
#S3 { grid-area: S3; }
#S4 { grid-area: S4; }
#S5 { grid-area: S5; }
#S6 { grid-area: S6; }
#S7 { grid-area: S7; }
#S8 { grid-area: S8; }
#S9 { grid-area: S9; }
#S10 { grid-area: S10; }
#S11 { grid-area: S11; }
#S12 { grid-area: S12; }
#S13 { grid-area: S13; }
#S14 { grid-area: S14; }
#S15 { grid-area: S15; }  
#S16 { grid-area: S16; }  
#S17 { grid-area: S17; }  
#S18 { grid-area: S18; }  
#S19 { grid-area: S19; }  
#S20 { grid-area: S20; }  
#S21 { grid-area: S21; }  
#S22 { grid-area: S22; }  
#S23 { grid-area: S23; }  
#S24 { grid-area: S24; }  
#S25 { grid-area: S25; }  
#S26 { grid-area: S26; }  
#S27 { grid-area: S27; }  
#S28 { grid-area: S28; }  
#S29 { grid-area: S29; }  
#S30 { grid-area: S30; }  
#S31 { grid-area: S31; }  
.StanleyCupImage{text-align: center;}
.StanleyCupText{font-size:24px;	text-align:center;padding: 10px 15px 10px 10px;}
.RoundWinner{font-weight: bold;font-size: calc(1em + 3px);}
.RoundWinner .STHSPHPStandingTeamImage {transform: scale(1.25);transform-origin: center;}

@media screen and (max-width: 1200px) {
	.STHSPlayoff_Div{padding: 5px 5px 5px 12px;font-size:18px;}	
}@media screen and (max-width: 1060px) {	
	.StanleyCupText{padding-top: 30px;}
	.STHSPHPStanding_Table thead th:nth-last-child(1){display:none;}
	.STHSPHPStanding_Table tbody td:nth-last-child(1){display:none;}
	.STHSPHPStanding_Table thead th:nth-last-child(3){display:none;}
	.STHSPHPStanding_Table tbody td:nth-last-child(3){display:none;}
	.STHSPHPStanding_Table thead th:nth-last-child(4){display:none;}
	.STHSPHPStanding_Table tbody td:nth-last-child(4){display:none;}
	.STHSPHPStanding_Table thead th:nth-last-child(5){display:none;}
	.STHSPHPStanding_Table tbody td:nth-last-child(5){display:none;}
	.STHSPHPStanding_Table tbody td.staticTD {font-size:9pt;border-right:hidden; border-left:hidden;display:block;}
	.StanleyCupText{font-size:14px;padding-top: 30px;}
	.STHSPlayoff_Div{font-size:14px;}
	.RoundWinner{font-weight: bold;font-size: calc(1em + 1px);}
	.STHSPHPStandingTeamImage {display:none;}
}@media screen and (max-width: 890px) {
	.STHSPHPStanding_Table thead th:nth-last-child(2){display:none;}
	.STHSPHPStanding_Table tbody td:nth-last-child(2){display:none;}
	.STHSPHPStanding_Table thead th:nth-last-child(6){display:none;}
	.STHSPHPStanding_Table tbody td:nth-last-child(6){display:none;}
}
<?php 
If ($Year == 0){echo "#ReQueryDiv{display: block;}";}else{echo "#ReQueryDiv{display: none;}";}
if ($Playoff == True){
	echo "#tabmain1{display:none;}\n";
	echo "#tabmain2{display:none;}\n";
	echo "#tabmain3{display:none;}\n";
	echo "#tabmain4{display:none;}\n";
	echo ".tabmain-content{border-radius:0px;box-shadow:0px 0px 0px rgba(0,0,0,0.15);}\n";	
}else{
	echo "#tabmain5{display:none;}\n";
	echo "#tabmain6{display:none;}\n";	
}?>
</style>

</head><body>
<?php include "Menu.php";?>
<div class="STHSHistoryStanding_MainDiv" style="width:99%;margin:auto;">
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
	echo "<li><a class=\"activemain\" href=\"#tabmain6\">" . $StandingLang['PlayoffLegacy'] . "</a></li>";
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
		PrintStandingTableRow($row, $TypeText, $LeagueOutputOption['StandardStandingOutput'], $LeagueGeneral['PointSystemSO'], $LeagueGeneral['PointSystemW'], $LoopCount, $DatabaseFile, $Year);
	}}
		
	/* Division 2 */	
	Echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"" . $ColumnPerTable . "\">" . $LeagueGeneral['DivisionName2'] . "</td></tr>";
	$Query = "SELECT Team" . $TypeTextTeam . "StatHistory.*, Team" . $TypeText . "InfoHistory.Conference, Team" . $TypeText . "InfoHistory.Division, RankingOrder.Type FROM (Team" . $TypeTextTeam . "StatHistory INNER JOIN Team" . $TypeText . "InfoHistory ON Team" . $TypeTextTeam . "StatHistory.Number = Team" . $TypeText . "InfoHistory.Number) INNER JOIN RankingOrder ON Team" . $TypeTextTeam . "StatHistory.Number = RankingOrder.Team" . $TypeText . "Number WHERE (((Team" . $TypeText . "InfoHistory.Division)=\"" . $LeagueGeneral['DivisionName2'] . "\") AND ((RankingOrder.Type)=0)) AND Team" . $TypeTextTeam . "StatHistory.Year = " . $Year . " And Team" . $TypeTextTeam . "StatHistory.Playoff = '" . $PlayoffString . "' AND Team" . $TypeTextTeam . "InfoHistory.Year = " . $Year . " And Team" . $TypeTextTeam . "InfoHistory.Playoff = '" . $PlayoffString . "' AND RankingOrder.Year = " . $Year . " And RankingOrder.Playoff = '" . $PlayoffString . "' ORDER BY RankingOrder.TeamOrder LIMIT 3";
	$Standing = $db->query($Query);
	$LoopCount =0;
	if (empty($Standing) == false){while ($row = $Standing ->fetchArray()) {
		$LoopCount +=1;
		PrintStandingTableRow($row, $TypeText, $LeagueOutputOption['StandardStandingOutput'], $LeagueGeneral['PointSystemSO'], $LeagueGeneral['PointSystemW'], $LoopCount, $DatabaseFile, $Year);
	}}

	/* Overall for Conference 1 */	
	Echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"" . $ColumnPerTable . "\">" . $StandingLang['Wildcard'] ."</td></tr>";
	$Query = "SELECT Team" . $TypeTextTeam . "StatHistory.*, Team" . $TypeText . "InfoHistory.Conference, Team" . $TypeText . "InfoHistory.Division, RankingOrder.Type FROM (Team" . $TypeTextTeam . "StatHistory INNER JOIN Team" . $TypeText . "InfoHistory ON Team" . $TypeTextTeam . "StatHistory.Number = Team" . $TypeText . "InfoHistory.Number) INNER JOIN RankingOrder ON Team" . $TypeTextTeam . "StatHistory.Number = RankingOrder.Team" . $TypeText . "Number WHERE (((Team" . $TypeText . "InfoHistory.Conference)=\"" . $LeagueGeneral['ConferenceName1'] . "\") AND ((RankingOrder.Type)=1)) AND Team" . $TypeTextTeam . "StatHistory.Year = " . $Year . " And Team" . $TypeTextTeam . "StatHistory.Playoff = '" . $PlayoffString . "' AND Team" . $TypeTextTeam . "InfoHistory.Year = " . $Year . " And Team" . $TypeTextTeam . "InfoHistory.Playoff = '" . $PlayoffString . "' AND RankingOrder.Year = " . $Year . " And RankingOrder.Playoff = '" . $PlayoffString . "' ORDER BY RankingOrder.TeamOrder";
	$Standing = $db->query($Query);
	$LoopCount =0;
	if (empty($Standing) == false){while ($row = $Standing ->fetchArray()) {
		$LoopCount +=1;
		If ($LoopCount > 6 ){PrintStandingTableRow($row, $TypeText, $LeagueOutputOption['StandardStandingOutput'], $LeagueGeneral['PointSystemSO'], $LeagueGeneral['PointSystemW'], $LoopCount, $DatabaseFile, $Year);}
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
		PrintStandingTableRow($row, $TypeText, $LeagueOutputOption['StandardStandingOutput'], $LeagueGeneral['PointSystemSO'], $LeagueGeneral['PointSystemW'], $LoopCount, $DatabaseFile, $Year);
	}}
		
	/* Division 5 */	
	Echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"" . $ColumnPerTable . "\">" . $LeagueGeneral['DivisionName5'] . "</td></tr>";
	$Query = "SELECT Team" . $TypeTextTeam . "StatHistory.*, Team" . $TypeText . "InfoHistory.Conference, Team" . $TypeText . "InfoHistory.Division, RankingOrder.Type FROM (Team" . $TypeTextTeam . "StatHistory INNER JOIN Team" . $TypeText . "InfoHistory ON Team" . $TypeTextTeam . "StatHistory.Number = Team" . $TypeText . "InfoHistory.Number) INNER JOIN RankingOrder ON Team" . $TypeTextTeam . "StatHistory.Number = RankingOrder.Team" . $TypeText . "Number WHERE (((Team" . $TypeText . "InfoHistory.Division)=\"" . $LeagueGeneral['DivisionName5'] . "\") AND ((RankingOrder.Type)=0)) AND Team" . $TypeTextTeam . "StatHistory.Year = " . $Year . " And Team" . $TypeTextTeam . "StatHistory.Playoff = '" . $PlayoffString . "' AND Team" . $TypeTextTeam . "InfoHistory.Year = " . $Year . " And Team" . $TypeTextTeam . "InfoHistory.Playoff = '" . $PlayoffString . "' AND RankingOrder.Year = " . $Year . " And RankingOrder.Playoff = '" . $PlayoffString . "' ORDER BY RankingOrder.TeamOrder LIMIT 3";
	$Standing = $db->query($Query);
	$LoopCount =0;
	if (empty($Standing) == false){while ($row = $Standing ->fetchArray()) {
		$LoopCount +=1;
		PrintStandingTableRow($row, $TypeText, $LeagueOutputOption['StandardStandingOutput'], $LeagueGeneral['PointSystemSO'], $LeagueGeneral['PointSystemW'], $LoopCount, $DatabaseFile, $Year);
	}}

	/* Overall for Conference 2 */	
	Echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"" . $ColumnPerTable . "\">" . $StandingLang['Wildcard'] . "</td></tr>";
	$Query = "SELECT Team" . $TypeTextTeam . "StatHistory.*, Team" . $TypeText . "InfoHistory.Conference, Team" . $TypeText . "InfoHistory.Division, RankingOrder.Type FROM (Team" . $TypeTextTeam . "StatHistory INNER JOIN Team" . $TypeText . "InfoHistory ON Team" . $TypeTextTeam . "StatHistory.Number = Team" . $TypeText . "InfoHistory.Number) INNER JOIN RankingOrder ON Team" . $TypeTextTeam . "StatHistory.Number = RankingOrder.Team" . $TypeText . "Number WHERE (((Team" . $TypeText . "InfoHistory.Conference)=\"" . $LeagueGeneral['ConferenceName2'] . "\") AND ((RankingOrder.Type)=2)) AND Team" . $TypeTextTeam . "StatHistory.Year = " . $Year . " And Team" . $TypeTextTeam . "StatHistory.Playoff = '" . $PlayoffString . "' AND Team" . $TypeTextTeam . "InfoHistory.Year = " . $Year . " And Team" . $TypeTextTeam . "InfoHistory.Playoff = '" . $PlayoffString . "' AND RankingOrder.Year = " . $Year . " And RankingOrder.Playoff = '" . $PlayoffString . "' ORDER BY RankingOrder.TeamOrder";
	$Standing = $db->query($Query);
	$LoopCount =0;
	if (empty($Standing) == false){while ($row = $Standing ->fetchArray()) {
		$LoopCount +=1;
		If ($LoopCount > 6 ){PrintStandingTableRow($row, $TypeText, $LeagueOutputOption['StandardStandingOutput'], $LeagueGeneral['PointSystemSO'], $LeagueGeneral['PointSystemW'], $LoopCount, $DatabaseFile, $Year);}
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
				PrintStandingTable($Standing, $TypeText, $LeagueOutputOption['StandardStandingOutput'], $LeagueGeneral['PointSystemSO'], $LeagueGeneral['PointSystemW'], $ColumnPerTable, $LeagueGeneral['HowManyPlayOffTeam']/2, $DatabaseFile, $Year);
			}else{
				PrintStandingTable($Standing, $TypeText, $LeagueOutputOption['StandardStandingOutput'], $LeagueGeneral['PointSystemSO'], $LeagueGeneral['PointSystemW'], $ColumnPerTable, $LeagueGeneral['HowManyPlayOffTeam'], $DatabaseFile, $Year);
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
			PrintStandingTable($Standing, $TypeText, $LeagueOutputOption['StandardStandingOutput'], $LeagueGeneral['PointSystemSO'], $LeagueGeneral['PointSystemW'], $ColumnPerTable, 0 , $DatabaseFile, $Year);
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
	PrintStandingTable($Standing, $TypeText, $LeagueOutputOption['StandardStandingOutput'], $LeagueGeneral['PointSystemSO'], $LeagueGeneral['PointSystemW'], $ColumnPerTable, 0 , $DatabaseFile, $Year);
}
?>

</div>

<div class="tabmain<?php if ($Playoff == True){echo " active";}?>" id="tabmain5">

<?php
If ($DatabaseFound == True){
	If ($LeagueGeneral['PlayOffWinner'] != 0 AND $Playoff == True){
		$Winner = $db->querySingle("Select Team" . $TypeText . "InfoHistory.Name  from Team" . $TypeText . "InfoHistory WHERE Team" . $TypeTextTeam . "InfoHistory.Year = " . $Year . " And Team" . $TypeTextTeam . "InfoHistory.Playoff = '" . $PlayoffString . "' AND Team" . $TypeText . "InfoHistory.Number = ". $LeagueGeneral['PlayOffWinner'] ,true);
		echo "<div class=\"STHSCenter\">";
		echo "<td>";If ($Winner['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Winner['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPStandingPlayoffWinnerImage \" />";}
		echo "<div class=\"STHSPlayoff_Div\"><h1>" . $Winner['Name'] . $StandingLang['WinsPlayoff'] . "</h1></div><br><br></div>\n";
	}	
		
	If ($LeagueGeneral['HowManyPlayOffTeam'] <= 2){
		echo "<div class=\"grid2\">\n";
		$StanleyCupDiv = 1;		
	}elseif ($LeagueGeneral['HowManyPlayOffTeam'] <= 4){
		echo "<div class=\"grid4\">\n";
		$StanleyCupDiv = 3;	
	}elseif ($LeagueGeneral['HowManyPlayOffTeam'] <= 8){
		echo "<div class=\"grid8\">\n";
		$StanleyCupDiv = 7;
	}elseif ($LeagueGeneral['HowManyPlayOffTeam'] <= 16){
		echo "<div class=\"grid16\">\n";
		$StanleyCupDiv = 15;
	}else{
		echo "<div class=\"grid32\">\n";
		$StanleyCupDiv = 31;
	}
	
	$Query = "SELECT Playoff" . $TypeText . ".*, TeamInfoHome.Name AS HomeTeamName, TeamInfoVisitor.Name AS VisitorTeamName, TeamInfoHome.Abbre AS HomeTeamAbbre, TeamInfoVisitor.Abbre AS VisitorTeamAbbre, 0 AS HomeThemID, 0 AS VisitorThemID FROM Playoff" . $TypeText . " INNER JOIN Team" . $TypeText . "InfoHistory AS TeamInfoHome   ON Playoff" . $TypeText . ".HomeTeam = TeamInfoHome.Number AND TeamInfoHome.Year = " . $Year . " AND TeamInfoHome.Playoff = 'False' LEFT JOIN Team" . $TypeText . "InfoHistory AS TeamInfoVisitor ON Playoff" . $TypeText . ".VisitorTeam = TeamInfoVisitor.Number AND TeamInfoVisitor.Year = " . $Year . " AND TeamInfoVisitor.Playoff = 'False' WHERE Playoff" . $TypeText . ".Year = " . $Year . " ORDER BY Playoff" . $TypeText . ".Number";
	$PlayoffStanding = $db->query($Query);
	$Count = 0;
	
	if (empty($PlayoffStanding) == false){while ($Row = $PlayoffStanding ->fetchArray()) {
		$Count++;
		echo "<div id=\"S" . $Row['Number'] . "\" class=\"STHSPlayoff_Div"; If ($Row['Number'] == $StanleyCupDiv){echo " StanleyCupText";} echo "\">";
		If ($Row['Number'] == $StanleyCupDiv){echo "<div class=\"StanleyCupImage\"><img src=\"" . $ImagesCDNPath . "/images/StanleyCup.png\" alt=\"Stanley Cup Final\"></div>";}
		
		If ($Row['VisitorTeam'] > 0){
			If ($Row['VisitorWin'] == $LeagueGeneral['PlayOffLength']){echo "<span class=\"RoundWinner\">";}else{echo "<span>";}
			If ($Row['VisitorThemID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Row['VisitorThemID'] .".png\" alt=\"\" class=\"STHSPHPStandingTeamImage\">";}
			echo "<a href=\"" . $TypeText . "Team.php?Team=" . $Row['VisitorTeam'] . "\">" . $Row['VisitorTeamAbbre'] . " - " . $Row['VisitorWin'] . "</a></span><br>";
		}
		
		If ($Row['HomeTeam'] > 0){
			If ($Row['HomeWin'] == $LeagueGeneral['PlayOffLength']){echo "<span class=\"RoundWinner\">";}else{echo "<span>";}
			If ($Row['HomeThemID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Row['HomeThemID'] .".png\" alt=\"\" class=\"STHSPHPStandingTeamImage\">";}
			echo "<a href=\"" . $TypeText . "Team.php?Team=" . $Row['HomeTeam'] . "\">" . $Row['HomeTeamAbbre'] . " - " . $Row['HomeWin'] . "</a></span></div>\n";
		}
	}}
	for($Count = $Count + 1; $Count <= $StanleyCupDiv; $Count ++){
		echo "<div id=\"S" . $Count . "\" class=\"STHSPlayoff_Div\">";
		If ($Count == $StanleyCupDiv){echo "<div class=\"StanleyCupImage\"><img src=\"" . $ImagesCDNPath . "/images/StanleyCup.png\" alt=\"Stanley Cup Final\"></div>";}
		echo "<br /></div>";
	}
	echo "</div>";
}?>

</div>

<div class="tabmain" id="tabmain6">

<?php
If ($DatabaseFound == True){
	If ($LeagueGeneral['PlayOffWinner'] != 0 AND $Playoff == True){
		$Winner = $db->querySingle("Select Team" . $TypeText . "InfoHistory.Name from Team" . $TypeText . "InfoHistory WHERE Team" . $TypeTextTeam . "InfoHistory.Year = " . $Year . " And Team" . $TypeTextTeam . "InfoHistory.Playoff = '" . $PlayoffString . "' AND Team" . $TypeText . "InfoHistory.Number = ". $LeagueGeneral['PlayOffWinner'] ,true);
		echo "<div class=\"STHSCenter\">";
		echo "<td>";If ($Winner['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Winner['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPStandingPlayoffWinnerImage \" />";}
		echo "<div class=\"STHSPlayoff_Div\"><h1>" . $Winner['Name'] . $StandingLang['WinsPlayoff'] . "</h1></div><br><br></div>\n";
	}
	echo "<table class=\"STHSTableFullW\"><tr>";
	
	If ($LeagueGeneral['HowManyPlayOffTeam'] <= 2){
		$TotalRound = 1;		
	}elseif ($LeagueGeneral['HowManyPlayOffTeam'] <= 4){
		$TotalRound = 2;	
	}elseif ($LeagueGeneral['HowManyPlayOffTeam'] <= 8){
		$TotalRound = 3;
	}elseif ($LeagueGeneral['HowManyPlayOffTeam'] <= 16){
		$TotalRound = 4;
	}else{
		$TotalRound = 5;
	}	
	
	for($Round = 1; $Round <= 5; $Round++){
		If ($Round <= $TotalRound){
			echo "<td><div class=\"STHSPlayoff_Div\"> " . $StandingLang['Round'] . $Round . "</div></td>";
		}else{
			echo "<td></td>";
		}
	}
	echo "</tr>\n";
	$Query = "SELECT Playoff" . $TypeText . "Number.* FROM Playoff" . $TypeText . "Number WHERE Year = " . $Year . " ORDER BY Playoff" . $TypeText . "Number.Number";
	$PlayoffStanding = $db->query($Query);
	if (empty($PlayoffStanding) == false){while ($Row = $PlayoffStanding ->fetchArray()) {
		echo "<tr>";
		If ($Row['Round1'] == 0){echo "<td></td>";}else{
			$Round1 = $db->querySingle("SELECT Playoff" . $TypeText . ".*, TeamInfoHome.Name as HomeTeamName, TeamInfoVisitor.Name as VisitorTeamName FROM (Playoff" . $TypeText . " INNER JOIN Team" . $TypeText . "InfoHistory AS TeamInfoHome ON Playoff" . $TypeText . ".HomeTeam = TeamInfoHome.Number) LEFT JOIN Team" . $TypeText . "InfoHistory AS TeamInfoVisitor ON Playoff" . $TypeText . ".VisitorTeam = TeamInfoVisitor.Number WHERE Playoff" . $TypeText . ".Number = " . $Row['Round1'] . " AND Playoff" . $TypeTextTeam . ".Year = " . $Year . " And TeamInfoHome.Year = " . $Year . " and TeamInfoVisitor.Year = " . $Year ,true);	
			if($Round1 != Null){
				echo "<td><div class=\"STHSPlayoff_Div\">";
				If ($Round1['VisitorTeam'] > 0){
					If ($Round1['VisitorThemID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Round1['VisitorThemID'] .".png\" alt=\"\" class=\"STHSPHPStandingTeamImage\">";}
					echo "<a href=\"" . $TypeText . "Team.php?Team=" . $Round1['VisitorTeam'] . "\">" . $Round1['VisitorTeamName'] . " - " . $Round1['VisitorWin'] . "</a><br>";
				}
				If ($Round1['HomeTeam'] > 0){
					If ($Round1['HomeThemID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Round1['HomeThemID'] .".png\" alt=\"\" class=\"STHSPHPStandingTeamImage\">";}
					echo "<a href=\"" . $TypeText . "Team.php?Team=" . $Round1['HomeTeam'] . "\">" . $Round1['HomeTeamName'] . " - " . $Round1['HomeWin'] . "</a></div></td>\n";
				}
			}
		}
		If ($Row['Round2'] == 0){echo "<td></td>";}else{
			$Round2 = $db->querySingle("SELECT Playoff" . $TypeText . ".*, TeamInfoHome.Name as HomeTeamName, TeamInfoVisitor.Name as VisitorTeamName FROM (Playoff" . $TypeText . " INNER JOIN Team" . $TypeText . "InfoHistory AS TeamInfoHome ON Playoff" . $TypeText . ".HomeTeam = TeamInfoHome.Number) LEFT JOIN Team" . $TypeText . "InfoHistory AS TeamInfoVisitor ON Playoff" . $TypeText . ".VisitorTeam = TeamInfoVisitor.Number WHERE Playoff" . $TypeText . ".Number = " . $Row['Round2'] . " AND Playoff" . $TypeTextTeam . ".Year = " . $Year . " And TeamInfoHome.Year = " . $Year . " and TeamInfoVisitor.Year = " . $Year,true);	
			if($Round2 != Null){
				echo "<td><div class=\"STHSPlayoff_Div\">";
				If ($Round2['VisitorTeam'] > 0){
					If ($Round2['VisitorThemID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Round2['VisitorThemID'] .".png\" alt=\"\" class=\"STHSPHPStandingTeamImage\">";}
					echo "<a href=\"" . $TypeText . "Team.php?Team=" . $Round2['VisitorTeam'] . "\">" . $Round2['VisitorTeamName'] . " - " . $Round2['VisitorWin'] . "</a><br>";
				}
				If ($Round2['HomeTeam'] > 0){	
					If ($Round2['HomeThemID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Round2['HomeThemID'] .".png\" alt=\"\" class=\"STHSPHPStandingTeamImage\">";}
					echo "<a href=\"" . $TypeText . "Team.php?Team=" . $Round2['HomeTeam'] . "\">" . $Round2['HomeTeamName'] . " - " . $Round2['HomeWin'] . "</a></div></td>\n";
				}
			}
		}
		If ($Row['Round3'] == 0){echo "<td></td>";}else{
			$Round3 = $db->querySingle("SELECT Playoff" . $TypeText . ".*, TeamInfoHome.Name as HomeTeamName, TeamInfoVisitor.Name as VisitorTeamName FROM (Playoff" . $TypeText . " INNER JOIN Team" . $TypeText . "InfoHistory AS TeamInfoHome ON Playoff" . $TypeText . ".HomeTeam = TeamInfoHome.Number) LEFT JOIN Team" . $TypeText . "InfoHistory AS TeamInfoVisitor ON Playoff" . $TypeText . ".VisitorTeam = TeamInfoVisitor.Number WHERE Playoff" . $TypeText . ".Number = " . $Row['Round3'] . " AND Playoff" . $TypeTextTeam . ".Year = " . $Year . " And TeamInfoHome.Year = " . $Year . " and TeamInfoVisitor.Year = " . $Year,true);	
			if($Round3 != Null){
				echo "<td><div class=\"STHSPlayoff_Div\">";
				If ($Round3['VisitorTeam'] > 0){
					If ($Round3['VisitorThemID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Round3['VisitorThemID'] .".png\" alt=\"\" class=\"STHSPHPStandingTeamImage\">";}
					echo "<a href=\"" . $TypeText . "Team.php?Team=" . $Round3['VisitorTeam'] . "\">" . $Round3['VisitorTeamName'] . " - " . $Round3['VisitorWin'] . "</a><br>";
				}
				If ($Round3['HomeTeam'] > 0){						
					If ($Round3['HomeThemID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Round3['HomeThemID'] .".png\" alt=\"\" class=\"STHSPHPStandingTeamImage\">";}
					echo "<a href=\"" . $TypeText . "Team.php?Team=" . $Round3['HomeTeam'] . "\">" . $Round3['HomeTeamName'] . " - " . $Round3['HomeWin'] . "</a></div></td>\n";
				}
			}
		}
		If ($Row['Round4'] == 0){echo "<td></td>";}else{
			$Round4 = $db->querySingle("SELECT Playoff" . $TypeText . ".*, TeamInfoHome.Name as HomeTeamName, TeamInfoVisitor.Name as VisitorTeamName FROM (Playoff" . $TypeText . " INNER JOIN Team" . $TypeText . "InfoHistory AS TeamInfoHome ON Playoff" . $TypeText . ".HomeTeam = TeamInfoHome.Number) LEFT JOIN Team" . $TypeText . "InfoHistory AS TeamInfoVisitor ON Playoff" . $TypeText . ".VisitorTeam = TeamInfoVisitor.Number WHERE Playoff" . $TypeText . ".Number = " . $Row['Round4'] . " AND Playoff" . $TypeTextTeam . ".Year = " . $Year . " And TeamInfoHome.Year = " . $Year . " and TeamInfoVisitor.Year = " . $Year,true);	
			if($Round4 != Null){
				echo "<td><div class=\"STHSPlayoff_Div\">";
				If ($Round4['VisitorTeam'] > 0){
					If ($Round4['VisitorThemID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Round4['VisitorThemID'] .".png\" alt=\"\" class=\"STHSPHPStandingTeamImage\">";}		
					echo "<a href=\"" . $TypeText . "Team.php?Team=" . $Round4['VisitorTeam'] . "\">" . $Round4['VisitorTeamName'] . " - " . $Round4['VisitorWin'] . "</a><br>";
				}
				If ($Round4['HomeTeam'] > 0){						
					If ($Round4['HomeThemID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Round4['HomeThemID'] .".png\" alt=\"\" class=\"STHSPHPStandingTeamImage\">";}
					echo "<a href=\"" . $TypeText . "Team.php?Team=" . $Round4['HomeTeam'] . "\">" . $Round4['HomeTeamName'] . " - " . $Round4['HomeWin'] . "</a></div></td>\n";
				}
			}
		}
		If ($Row['Round5'] == 0){echo "<td></td>";}else{
			$Round5 = $db->querySingle("SELECT Playoff" . $TypeText . ".*, TeamInfoHome.Name as HomeTeamName, TeamInfoVisitor.Name as VisitorTeamName FROM (Playoff" . $TypeText . " INNER JOIN Team" . $TypeText . "InfoHistory AS TeamInfoHome ON Playoff" . $TypeText . ".HomeTeam = TeamInfoHome.Number) LEFT JOIN Team" . $TypeText . "InfoHistory AS TeamInfoVisitor ON Playoff" . $TypeText . ".VisitorTeam = TeamInfoVisitor.Number WHERE Playoff" . $TypeText . ".Number = " . $Row['Round5'] . " AND Playoff" . $TypeTextTeam . ".Year = " . $Year . " And TeamInfoHome.Year = " . $Year . " and TeamInfoVisitor.Year = " . $Year,true);	
			if($Round5 != Null){
				echo "<td><div class=\"STHSPlayoff_Div\">";
				If ($Round5['VisitorTeam'] > 0){
					If ($Round5['VisitorThemID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Round5['VisitorThemID'] .".png\" alt=\"\" class=\"STHSPHPStandingTeamImage\">";}	
					echo "<a href=\"" . $TypeText . "Team.php?Team=" . $Round5['VisitorTeam'] . "\">" . $Round5['VisitorTeamName'] . " - " . $Round5['VisitorWin'] . "</a><br>";
				}
				If ($Round5['HomeTeam'] > 0){							
					If ($Round5['HomeThemID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Round4['HomeThemID'] .".png\" alt=\"\" class=\"STHSPHPStandingTeamImage\">";}
					echo "<a href=\"" . $TypeText . "Team.php?Team=" . $Round5['HomeTeam'] . "\">" . $Round5['HomeTeamName'] . " - " . $Round5['HomeWin'] . "</a></div></td>\n";
				}
			}
		}
		echo "</tr>\n";
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

