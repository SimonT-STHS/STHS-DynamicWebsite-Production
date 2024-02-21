<?php include "Header.php";
If ($lang == "fr"){include 'LanguageFR-Stat.php';}else{include 'LanguageEN-Stat.php';}
$Title = (string)"";
$Search = (boolean)False;
$HistoryOutput = (boolean)False;
$CareerLeaderSubPrintOut = (int)0;
include "SearchPossibleOrderField.php";
If (file_exists($DatabaseFile) == false){
	Goto STHSErrorTeamStat;
}else{try{
	$DESCQuery = (boolean)FALSE;/* The SQL Query must be Descending Order and not Ascending*/
	$AllSeasonMergeTeam = (integer)0;
	$TypeText = (string)"Pro";$TitleType = $DynamicTitleLang['Pro'];
	$LeagueName = (string)"";
	$OrderByField = (string)"Name";
	$OrderByFieldText = (string)"Team Name";
	$OrderByInput = (string)"";
	$Team = (integer)0;
	if(isset($_GET['DESC'])){$DESCQuery= TRUE;}
	if(isset($_GET['AllSeasonMergeTeam'])){$AllSeasonMergeTeam = filter_var($_GET['AllSeasonMergeTeam'], FILTER_SANITIZE_NUMBER_INT);}
	if(isset($_GET['Farm'])){$TypeText = "Farm";$TitleType = $DynamicTitleLang['Farm'];}
	if(isset($_GET['Team'])){$Team = filter_var($_GET['Team'], FILTER_SANITIZE_NUMBER_INT);}
	if(isset($_GET['Order'])){$OrderByInput  = filter_var($_GET['Order'], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH || FILTER_FLAG_NO_ENCODE_QUOTES || FILTER_FLAG_STRIP_BACKTICK);} 
	
	$Playoff = (boolean)False;
	$PlayoffString = (string)"False";
	$Year = (integer)0;	
	if(isset($_GET['Playoff'])){$Playoff=True;$PlayoffString="True";}
	if(isset($_GET['Year'])){$Year = filter_var($_GET['Year'], FILTER_SANITIZE_NUMBER_INT);} 	
	
	include "SearchPossibleOrderField.php";
		
	foreach ($TeamStatPossibleOrderField as $Value) {
		If (strtoupper($Value[0]) == strtoupper($OrderByInput)){
			$OrderByField = $Value[0];
			$OrderByFieldText = $Value[1];
			Break;
		}
	}

	If($Year > 0 AND file_exists($CareerStatDatabaseFile) == true){  /* History Database */
		$db = new SQLite3($CareerStatDatabaseFile);
		$CareerDBFormatV2CheckCheck = $db->querySingle("SELECT Count(name) AS CountName FROM sqlite_master WHERE type='table' AND name='LeagueGeneral'",true);
		If ($CareerDBFormatV2CheckCheck['CountName'] == 1){
			$HistoryOutput = True;
			
			If ($Year == 9999 Or $Year == 9998){
				/* All Year : 9999 = All Season Per Year / 9998 = All Season Merge  */
				
				$dbLive = new SQLite3($DatabaseFile);
				$Query = "Select Name, LeagueYearOutput, PlayOffStarted, PointSystemW, PreSeasonSchedule from LeagueGeneral";
				$LeagueGeneral = $dbLive ->querySingle($Query,true);		
				$LeagueName = $LeagueGeneral['Name'];
				
				$db->query("ATTACH DATABASE '".realpath($DatabaseFile)."' AS CurrentDB");
				
				If ($Playoff=="True"){$Title = $SearchLang['Playoff'] .  " ";}
				If ($Year == 9999 ){
					$Title = $Title . $SearchLang['AllSeasonPerYear'] . " - ";
					$CareerLeaderSubPrintOut = 1;
				}elseif ($Year == 9998){
					$Title = $Title . $SearchLang['AllSeasonMerge'] . " - ";
				}else{
					$Title = $Title . $DynamicTitleLang['CareerStat'];
				}
				
				if($Team > 0){
					$QueryTeam = "SELECT Name FROM Team" . $TypeText . "Info WHERE Number = " . $Team;
					$TeamName = $db->querySingle($QueryTeam,true);	
					$Title = $Title . $TeamName['Name'] . " - ";;		
				}					

				If ($Year == 9998 ){
					$Query = "SELECT MainTable.Number AS Number, MainTable.Name AS Name, MainTable.Name AS OrderName, '0' AS TeamThemeID, Sum(MainTable.GP) AS GP, Sum(MainTable.W) AS W, Sum(MainTable.L) AS L, Sum(MainTable.T) AS T, Sum(MainTable.OTW) AS OTW, Sum(MainTable.OTL) AS OTL, Sum(MainTable.SOW) AS SOW, Sum(MainTable.SOL) AS SOL, Sum(MainTable.Points) AS Points, Sum(MainTable.GF) AS GF, Sum(MainTable.GA) AS GA, Sum(MainTable.HomeGP) AS HomeGP, Sum(MainTable.HomeW) AS HomeW, Sum(MainTable.HomeL) AS HomeL, Sum(MainTable.HomeT) AS HomeT, Sum(MainTable.HomeOTW) AS HomeOTW, Sum(MainTable.HomeOTL) AS HomeOTL, Sum(MainTable.HomeSOW) AS HomeSOW, Sum(MainTable.HomeSOL) AS HomeSOL, Sum(MainTable.HomeGF) AS HomeGF, Sum(MainTable.HomeGA) AS HomeGA, Sum(MainTable.PPAttemp) AS PPAttemp, Sum(MainTable.PPGoal) AS PPGoal, Sum(MainTable.PKAttemp) AS PKAttemp, Sum(MainTable.PKGoalGA) AS PKGoalGA, Sum(MainTable.PKGoalGF) AS PKGoalGF, Sum(MainTable.ShotsFor) AS ShotsFor, Sum(MainTable.ShotsAga) AS ShotsAga, Sum(MainTable.ShotsBlock) AS ShotsBlock, Sum(MainTable.ShotsPerPeriod1) AS ShotsPerPeriod1, Sum(MainTable.ShotsPerPeriod2) AS ShotsPerPeriod2, Sum(MainTable.ShotsPerPeriod3) AS ShotsPerPeriod3, Sum(MainTable.ShotsPerPeriod4) AS ShotsPerPeriod4, Sum(MainTable.GoalsPerPeriod1) AS GoalsPerPeriod1, Sum(MainTable.GoalsPerPeriod2) AS GoalsPerPeriod2, Sum(MainTable.GoalsPerPeriod3) AS GoalsPerPeriod3, Sum(MainTable.GoalsPerPeriod4) AS GoalsPerPeriod4, Sum(MainTable.PuckTimeInZoneDF) AS PuckTimeInZoneDF, Sum(MainTable.PuckTimeInZoneOF) AS PuckTimeInZoneOF, Sum(MainTable.PuckTimeInZoneNT) AS PuckTimeInZoneNT, Sum(MainTable.PuckTimeControlinZoneDF) AS PuckTimeControlinZoneDF, Sum(MainTable.PuckTimeControlinZoneOF) AS PuckTimeControlinZoneOF, Sum(MainTable.PuckTimeControlinZoneNT) AS PuckTimeControlinZoneNT, Sum(MainTable.Shutouts) AS Shutouts, Sum(MainTable.TotalGoal) AS TotalGoal, Sum(MainTable.TotalAssist) AS TotalAssist, Sum(MainTable.TotalPoint) AS TotalPoint, Sum(MainTable.Pim) AS Pim, Sum(MainTable.Hits) AS Hits, Sum(MainTable.FaceOffWonDefensifZone) AS FaceOffWonDefensifZone, Sum(MainTable.FaceOffTotalDefensifZone) AS FaceOffTotalDefensifZone, Sum(MainTable.FaceOffWonOffensifZone) AS FaceOffWonOffensifZone, Sum(MainTable.FaceOffTotalOffensifZone) AS FaceOffTotalOffensifZone, Sum(MainTable.FaceOffWonNeutralZone) AS FaceOffWonNeutralZone, Sum(MainTable.FaceOffTotalNeutralZone) AS FaceOffTotalNeutralZone, Sum(MainTable.EmptyNetGoal) AS EmptyNetGoal";
				}else{
					$Query = "Select MainTable.*";
				}
				
				if($Team > 0 AND $Year == 9998){
					if($LeagueGeneral['PlayOffStarted'] == $PlayoffString AND $LeagueGeneral['PreSeasonSchedule'] == "False"){
						/* Regular Query */
						$Query = $Query . " FROM (SELECT '". $LeagueGeneral['LeagueYearOutput'] . "' as Year, Team" . $TypeText . "StatVS.TeamVSName AS Name, Team" . $TypeText . "StatVS.TeamVSNumber AS Number, Team" . $TypeText . "StatVS.TeamVSNumberThemeID as TeamThemeID, Team" . $TypeText . "StatVS.GP, Team" . $TypeText . "StatVS.W, Team" . $TypeText . "StatVS.L, Team" . $TypeText . "StatVS.T, Team" . $TypeText . "StatVS.OTW, Team" . $TypeText . "StatVS.OTL, Team" . $TypeText . "StatVS.SOW, Team" . $TypeText . "StatVS.SOL, Team" . $TypeText . "StatVS.Points, Team" . $TypeText . "StatVS.GF, Team" . $TypeText . "StatVS.GA, Team" . $TypeText . "StatVS.HomeGP, Team" . $TypeText . "StatVS.HomeW, Team" . $TypeText . "StatVS.HomeL, Team" . $TypeText . "StatVS.HomeT, Team" . $TypeText . "StatVS.HomeOTW, Team" . $TypeText . "StatVS.HomeOTL, Team" . $TypeText . "StatVS.HomeSOW, Team" . $TypeText . "StatVS.HomeSOL, Team" . $TypeText . "StatVS.HomeGF, Team" . $TypeText . "StatVS.HomeGA, Team" . $TypeText . "StatVS.PPAttemp, Team" . $TypeText . "StatVS.PPGoal, Team" . $TypeText . "StatVS.PKAttemp, Team" . $TypeText . "StatVS.PKGoalGA, Team" . $TypeText . "StatVS.PKGoalGF, Team" . $TypeText . "StatVS.ShotsFor, Team" . $TypeText . "StatVS.ShotsAga, Team" . $TypeText . "StatVS.ShotsBlock, Team" . $TypeText . "StatVS.ShotsPerPeriod1, Team" . $TypeText . "StatVS.ShotsPerPeriod2, Team" . $TypeText . "StatVS.ShotsPerPeriod3, Team" . $TypeText . "StatVS.ShotsPerPeriod4, Team" . $TypeText . "StatVS.GoalsPerPeriod1, Team" . $TypeText . "StatVS.GoalsPerPeriod2, Team" . $TypeText . "StatVS.GoalsPerPeriod3, Team" . $TypeText . "StatVS.GoalsPerPeriod4, Team" . $TypeText . "StatVS.PuckTimeInZoneDF, Team" . $TypeText . "StatVS.PuckTimeInZoneOF, Team" . $TypeText . "StatVS.PuckTimeInZoneNT, Team" . $TypeText . "StatVS.PuckTimeControlinZoneDF, Team" . $TypeText . "StatVS.PuckTimeControlinZoneOF, Team" . $TypeText . "StatVS.PuckTimeControlinZoneNT, Team" . $TypeText . "StatVS.Shutouts, Team" . $TypeText . "StatVS.TotalGoal, Team" . $TypeText . "StatVS.TotalAssist, Team" . $TypeText . "StatVS.TotalPoint, Team" . $TypeText . "StatVS.Pim, Team" . $TypeText . "StatVS.Hits, Team" . $TypeText . "StatVS.FaceOffWonDefensifZone, Team" . $TypeText . "StatVS.FaceOffTotalDefensifZone, Team" . $TypeText . "StatVS.FaceOffWonOffensifZone, Team" . $TypeText . "StatVS.FaceOffTotalOffensifZone, Team" . $TypeText . "StatVS.FaceOffWonNeutralZone, Team" . $TypeText . "StatVS.FaceOffTotalNeutralZone, Team" . $TypeText . "StatVS.EmptyNetGoal FROM Team" . $TypeText . "StatVS WHERE GP > 0 AND NUMBER < 103 AND TeamNumber = " . $Team . " UNION ALL SELECT Team" . $TypeText . "StatVSHistory.Year AS Year, Team" . $TypeText . "StatVSHistory.TeamVSName AS Name, Team" . $TypeText . "StatVSHistory.TeamVSNumber AS Number, '0' As TeamThemeID, Team" . $TypeText . "StatVSHistory.GP, Team" . $TypeText . "StatVSHistory.W, Team" . $TypeText . "StatVSHistory.L, Team" . $TypeText . "StatVSHistory.T, Team" . $TypeText . "StatVSHistory.OTW, Team" . $TypeText . "StatVSHistory.OTL, Team" . $TypeText . "StatVSHistory.SOW, Team" . $TypeText . "StatVSHistory.SOL, Team" . $TypeText . "StatVSHistory.Points, Team" . $TypeText . "StatVSHistory.GF, Team" . $TypeText . "StatVSHistory.GA, Team" . $TypeText . "StatVSHistory.HomeGP, Team" . $TypeText . "StatVSHistory.HomeW, Team" . $TypeText . "StatVSHistory.HomeL, Team" . $TypeText . "StatVSHistory.HomeT, Team" . $TypeText . "StatVSHistory.HomeOTW, Team" . $TypeText . "StatVSHistory.HomeOTL, Team" . $TypeText . "StatVSHistory.HomeSOW, Team" . $TypeText . "StatVSHistory.HomeSOL, Team" . $TypeText . "StatVSHistory.HomeGF, Team" . $TypeText . "StatVSHistory.HomeGA, Team" . $TypeText . "StatVSHistory.PPAttemp, Team" . $TypeText . "StatVSHistory.PPGoal, Team" . $TypeText . "StatVSHistory.PKAttemp, Team" . $TypeText . "StatVSHistory.PKGoalGA, Team" . $TypeText . "StatVSHistory.PKGoalGF, Team" . $TypeText . "StatVSHistory.ShotsFor, Team" . $TypeText . "StatVSHistory.ShotsAga, Team" . $TypeText . "StatVSHistory.ShotsBlock, Team" . $TypeText . "StatVSHistory.ShotsPerPeriod1, Team" . $TypeText . "StatVSHistory.ShotsPerPeriod2, Team" . $TypeText . "StatVSHistory.ShotsPerPeriod3, Team" . $TypeText . "StatVSHistory.ShotsPerPeriod4, Team" . $TypeText . "StatVSHistory.GoalsPerPeriod1, Team" . $TypeText . "StatVSHistory.GoalsPerPeriod2, Team" . $TypeText . "StatVSHistory.GoalsPerPeriod3, Team" . $TypeText . "StatVSHistory.GoalsPerPeriod4, Team" . $TypeText . "StatVSHistory.PuckTimeInZoneDF, Team" . $TypeText . "StatVSHistory.PuckTimeInZoneOF, Team" . $TypeText . "StatVSHistory.PuckTimeInZoneNT, Team" . $TypeText . "StatVSHistory.PuckTimeControlinZoneDF, Team" . $TypeText . "StatVSHistory.PuckTimeControlinZoneOF, Team" . $TypeText . "StatVSHistory.PuckTimeControlinZoneNT, Team" . $TypeText . "StatVSHistory.Shutouts, Team" . $TypeText . "StatVSHistory.TotalGoal, Team" . $TypeText . "StatVSHistory.TotalAssist, Team" . $TypeText . "StatVSHistory.TotalPoint, Team" . $TypeText . "StatVSHistory.Pim, Team" . $TypeText . "StatVSHistory.Hits, Team" . $TypeText . "StatVSHistory.FaceOffWonDefensifZone, Team" . $TypeText . "StatVSHistory.FaceOffTotalDefensifZone, Team" . $TypeText . "StatVSHistory.FaceOffWonOffensifZone, Team" . $TypeText . "StatVSHistory.FaceOffTotalOffensifZone, Team" . $TypeText . "StatVSHistory.FaceOffWonNeutralZone, Team" . $TypeText . "StatVSHistory.FaceOffTotalNeutralZone, Team" . $TypeText . "StatVSHistory.EmptyNetGoal FROM Team" . $TypeText . "StatVSHistory WHERE GP > 0 AND NUMBER < 103 AND TeamNumber = " . $Team . " And Playoff = '" . $PlayoffString. "' ) AS MainTable GROUP BY Number ORDER BY CASE WHEN Number > 100 THEN 2 ELSE 1 END, ";
						If ($OrderByField = "Name"){
							$Query = $Query . " MainTable.Name";
						}else{
							$Query = $Query . " Sum(MainTable." . $OrderByField . ")";
						}
					}else{
						/* Requesting Playoff While in Season or Requesting Season while in Playoff or In Pre-Season Mode - Do not fetch data from live database */
						$Query = $Query . " FROM (SELECT Team" . $TypeText . "StatVSHistory.Year AS Year, Team" . $TypeText . "StatVSHistory.TeamVSName AS Name, Team" . $TypeText . "StatVSHistory.TeamVSNumber AS Number, '0' As TeamThemeID, Team" . $TypeText . "StatVSHistory.GP, Team" . $TypeText . "StatVSHistory.W, Team" . $TypeText . "StatVSHistory.L, Team" . $TypeText . "StatVSHistory.T, Team" . $TypeText . "StatVSHistory.OTW, Team" . $TypeText . "StatVSHistory.OTL, Team" . $TypeText . "StatVSHistory.SOW, Team" . $TypeText . "StatVSHistory.SOL, Team" . $TypeText . "StatVSHistory.Points, Team" . $TypeText . "StatVSHistory.GF, Team" . $TypeText . "StatVSHistory.GA, Team" . $TypeText . "StatVSHistory.HomeGP, Team" . $TypeText . "StatVSHistory.HomeW, Team" . $TypeText . "StatVSHistory.HomeL, Team" . $TypeText . "StatVSHistory.HomeT, Team" . $TypeText . "StatVSHistory.HomeOTW, Team" . $TypeText . "StatVSHistory.HomeOTL, Team" . $TypeText . "StatVSHistory.HomeSOW, Team" . $TypeText . "StatVSHistory.HomeSOL, Team" . $TypeText . "StatVSHistory.HomeGF, Team" . $TypeText . "StatVSHistory.HomeGA, Team" . $TypeText . "StatVSHistory.PPAttemp, Team" . $TypeText . "StatVSHistory.PPGoal, Team" . $TypeText . "StatVSHistory.PKAttemp, Team" . $TypeText . "StatVSHistory.PKGoalGA, Team" . $TypeText . "StatVSHistory.PKGoalGF, Team" . $TypeText . "StatVSHistory.ShotsFor, Team" . $TypeText . "StatVSHistory.ShotsAga, Team" . $TypeText . "StatVSHistory.ShotsBlock, Team" . $TypeText . "StatVSHistory.ShotsPerPeriod1, Team" . $TypeText . "StatVSHistory.ShotsPerPeriod2, Team" . $TypeText . "StatVSHistory.ShotsPerPeriod3, Team" . $TypeText . "StatVSHistory.ShotsPerPeriod4, Team" . $TypeText . "StatVSHistory.GoalsPerPeriod1, Team" . $TypeText . "StatVSHistory.GoalsPerPeriod2, Team" . $TypeText . "StatVSHistory.GoalsPerPeriod3, Team" . $TypeText . "StatVSHistory.GoalsPerPeriod4, Team" . $TypeText . "StatVSHistory.PuckTimeInZoneDF, Team" . $TypeText . "StatVSHistory.PuckTimeInZoneOF, Team" . $TypeText . "StatVSHistory.PuckTimeInZoneNT, Team" . $TypeText . "StatVSHistory.PuckTimeControlinZoneDF, Team" . $TypeText . "StatVSHistory.PuckTimeControlinZoneOF, Team" . $TypeText . "StatVSHistory.PuckTimeControlinZoneNT, Team" . $TypeText . "StatVSHistory.Shutouts, Team" . $TypeText . "StatVSHistory.TotalGoal, Team" . $TypeText . "StatVSHistory.TotalAssist, Team" . $TypeText . "StatVSHistory.TotalPoint, Team" . $TypeText . "StatVSHistory.Pim, Team" . $TypeText . "StatVSHistory.Hits, Team" . $TypeText . "StatVSHistory.FaceOffWonDefensifZone, Team" . $TypeText . "StatVSHistory.FaceOffTotalDefensifZone, Team" . $TypeText . "StatVSHistory.FaceOffWonOffensifZone, Team" . $TypeText . "StatVSHistory.FaceOffTotalOffensifZone, Team" . $TypeText . "StatVSHistory.FaceOffWonNeutralZone, Team" . $TypeText . "StatVSHistory.FaceOffTotalNeutralZone, Team" . $TypeText . "StatVSHistory.EmptyNetGoal FROM Team" . $TypeText . "StatVSHistory WHERE GP > 0 AND NUMBER < 103 AND TeamNumber = " . $Team . " And Playoff = '" . $PlayoffString. "' ) AS MainTable GROUP BY Number ORDER BY CASE WHEN Number > 100 THEN 2 ELSE 1 END, ";
						If ($OrderByField = "Name"){
							$Query = $Query . " MainTable.Name";
						}else{
							$Query = $Query . " Sum(MainTable." . $OrderByField . ")";
						}
					}
					$Title = $Title  . $DynamicTitleLang['TeamStatVS'] . $TitleType;	
					
				}else{
					
					if($LeagueGeneral['PlayOffStarted'] == $PlayoffString AND $LeagueGeneral['PreSeasonSchedule'] == "False"){
						/* Regular Query */
						$Query = $Query . " FROM (SELECT '". $LeagueGeneral['LeagueYearOutput'] . "' as Year, Team" . $TypeText . "Stat.Number as Number, Team" . $TypeText . "Stat.UniqueID as UniqueID, Team" . $TypeText . "Stat.Name as Name, Team" . $TypeText . "Stat.Name as OrderName, Team" . $TypeText . "Stat.TeamThemeID as TeamThemeID, Team" . $TypeText . "Stat.GP AS GP, Team" . $TypeText . "Stat.W AS W, Team" . $TypeText . "Stat.L AS L, Team" . $TypeText . "Stat.T AS T, Team" . $TypeText . "Stat.OTW AS OTW, Team" . $TypeText . "Stat.OTL AS OTL, Team" . $TypeText . "Stat.SOW AS SOW, Team" . $TypeText . "Stat.SOL AS SOL, Team" . $TypeText . "Stat.Points AS Points, Team" . $TypeText . "Stat.GF AS GF, Team" . $TypeText . "Stat.GA AS GA, Team" . $TypeText . "Stat.HomeGP AS HomeGP, Team" . $TypeText . "Stat.HomeW AS HomeW, Team" . $TypeText . "Stat.HomeL AS HomeL, Team" . $TypeText . "Stat.HomeT AS HomeT, Team" . $TypeText . "Stat.HomeOTW AS HomeOTW, Team" . $TypeText . "Stat.HomeOTL AS HomeOTL, Team" . $TypeText . "Stat.HomeSOW AS HomeSOW, Team" . $TypeText . "Stat.HomeSOL AS HomeSOL, Team" . $TypeText . "Stat.HomeGF AS HomeGF, Team" . $TypeText . "Stat.HomeGA AS HomeGA, Team" . $TypeText . "Stat.PPAttemp AS PPAttemp, Team" . $TypeText . "Stat.PPGoal AS PPGoal, Team" . $TypeText . "Stat.PKAttemp AS PKAttemp, Team" . $TypeText . "Stat.PKGoalGA AS PKGoalGA, Team" . $TypeText . "Stat.PKGoalGF AS PKGoalGF, Team" . $TypeText . "Stat.ShotsFor AS ShotsFor, Team" . $TypeText . "Stat.ShotsAga AS ShotsAga, Team" . $TypeText . "Stat.ShotsBlock AS ShotsBlock, Team" . $TypeText . "Stat.ShotsPerPeriod1 AS ShotsPerPeriod1, Team" . $TypeText . "Stat.ShotsPerPeriod2 AS ShotsPerPeriod2, Team" . $TypeText . "Stat.ShotsPerPeriod3 AS ShotsPerPeriod3, Team" . $TypeText . "Stat.ShotsPerPeriod4 AS ShotsPerPeriod4, Team" . $TypeText . "Stat.GoalsPerPeriod1 AS GoalsPerPeriod1, Team" . $TypeText . "Stat.GoalsPerPeriod2 AS GoalsPerPeriod2, Team" . $TypeText . "Stat.GoalsPerPeriod3 AS GoalsPerPeriod3, Team" . $TypeText . "Stat.GoalsPerPeriod4 AS GoalsPerPeriod4, Team" . $TypeText . "Stat.PuckTimeInZoneDF AS PuckTimeInZoneDF, Team" . $TypeText . "Stat.PuckTimeInZoneOF AS PuckTimeInZoneOF, Team" . $TypeText . "Stat.PuckTimeInZoneNT AS PuckTimeInZoneNT, Team" . $TypeText . "Stat.PuckTimeControlinZoneDF AS PuckTimeControlinZoneDF, Team" . $TypeText . "Stat.PuckTimeControlinZoneOF AS PuckTimeControlinZoneOF, Team" . $TypeText . "Stat.PuckTimeControlinZoneNT AS PuckTimeControlinZoneNT, Team" . $TypeText . "Stat.Shutouts AS Shutouts, Team" . $TypeText . "Stat.TotalGoal AS TotalGoal, Team" . $TypeText . "Stat.TotalAssist AS TotalAssist, Team" . $TypeText . "Stat.TotalPoint AS TotalPoint, Team" . $TypeText . "Stat.Pim AS Pim, Team" . $TypeText . "Stat.Hits AS Hits, Team" . $TypeText . "Stat.FaceOffWonDefensifZone AS FaceOffWonDefensifZone, Team" . $TypeText . "Stat.FaceOffTotalDefensifZone AS FaceOffTotalDefensifZone, Team" . $TypeText . "Stat.FaceOffWonOffensifZone AS FaceOffWonOffensifZone, Team" . $TypeText . "Stat.FaceOffTotalOffensifZone AS FaceOffTotalOffensifZone, Team" . $TypeText . "Stat.FaceOffWonNeutralZone AS FaceOffWonNeutralZone, Team" . $TypeText . "Stat.FaceOffTotalNeutralZone AS FaceOffTotalNeutralZone, Team" . $TypeText . "Stat.EmptyNetGoal AS EmptyNetGoal FROM Team" . $TypeText . "Stat UNION ALL SELECT Team" . $TypeText . "StatHistory.Year as Year,  Team" . $TypeText . "StatHistory.Number as Number,   Team" . $TypeText . "StatHistory.UniqueID as UniqueID, Team" . $TypeText . "StatHistory.Name as Name, Team" . $TypeText . "StatHistory.Name as OrderName,'0' As TeamThemeID, Team" . $TypeText . "StatHistory.GP AS GP, Team" . $TypeText . "StatHistory.W AS W, Team" . $TypeText . "StatHistory.L AS L, Team" . $TypeText . "StatHistory.T AS T, Team" . $TypeText . "StatHistory.OTW AS OTW, Team" . $TypeText . "StatHistory.OTL AS OTL, Team" . $TypeText . "StatHistory.SOW AS SOW, Team" . $TypeText . "StatHistory.SOL AS SOL, Team" . $TypeText . "StatHistory.Points AS Points, Team" . $TypeText . "StatHistory.GF AS GF, Team" . $TypeText . "StatHistory.GA AS GA, Team" . $TypeText . "StatHistory.HomeGP AS HomeGP, Team" . $TypeText . "StatHistory.HomeW AS HomeW, Team" . $TypeText . "StatHistory.HomeL AS HomeL, Team" . $TypeText . "StatHistory.HomeT AS HomeT, Team" . $TypeText . "StatHistory.HomeOTW AS HomeOTW, Team" . $TypeText . "StatHistory.HomeOTL AS HomeOTL, Team" . $TypeText . "StatHistory.HomeSOW AS HomeSOW, Team" . $TypeText . "StatHistory.HomeSOL AS HomeSOL, Team" . $TypeText . "StatHistory.HomeGF AS HomeGF, Team" . $TypeText . "StatHistory.HomeGA AS HomeGA, Team" . $TypeText . "StatHistory.PPAttemp AS PPAttemp, Team" . $TypeText . "StatHistory.PPGoal AS PPGoal, Team" . $TypeText . "StatHistory.PKAttemp AS PKAttemp, Team" . $TypeText . "StatHistory.PKGoalGA AS PKGoalGA, Team" . $TypeText . "StatHistory.PKGoalGF AS PKGoalGF, Team" . $TypeText . "StatHistory.ShotsFor AS ShotsFor, Team" . $TypeText . "StatHistory.ShotsAga AS ShotsAga, Team" . $TypeText . "StatHistory.ShotsBlock AS ShotsBlock, Team" . $TypeText . "StatHistory.ShotsPerPeriod1 AS ShotsPerPeriod1, Team" . $TypeText . "StatHistory.ShotsPerPeriod2 AS ShotsPerPeriod2, Team" . $TypeText . "StatHistory.ShotsPerPeriod3 AS ShotsPerPeriod3, Team" . $TypeText . "StatHistory.ShotsPerPeriod4 AS ShotsPerPeriod4, Team" . $TypeText . "StatHistory.GoalsPerPeriod1 AS GoalsPerPeriod1, Team" . $TypeText . "StatHistory.GoalsPerPeriod2 AS GoalsPerPeriod2, Team" . $TypeText . "StatHistory.GoalsPerPeriod3 AS GoalsPerPeriod3, Team" . $TypeText . "StatHistory.GoalsPerPeriod4 AS GoalsPerPeriod4, Team" . $TypeText . "StatHistory.PuckTimeInZoneDF AS PuckTimeInZoneDF, Team" . $TypeText . "StatHistory.PuckTimeInZoneOF AS PuckTimeInZoneOF, Team" . $TypeText . "StatHistory.PuckTimeInZoneNT AS PuckTimeInZoneNT, Team" . $TypeText . "StatHistory.PuckTimeControlinZoneDF AS PuckTimeControlinZoneDF, Team" . $TypeText . "StatHistory.PuckTimeControlinZoneOF AS PuckTimeControlinZoneOF, Team" . $TypeText . "StatHistory.PuckTimeControlinZoneNT AS PuckTimeControlinZoneNT, Team" . $TypeText . "StatHistory.Shutouts AS Shutouts, Team" . $TypeText . "StatHistory.TotalGoal AS TotalGoal, Team" . $TypeText . "StatHistory.TotalAssist AS TotalAssist, Team" . $TypeText . "StatHistory.TotalPoint AS TotalPoint, Team" . $TypeText . "StatHistory.Pim AS Pim, Team" . $TypeText . "StatHistory.Hits AS Hits, Team" . $TypeText . "StatHistory.FaceOffWonDefensifZone AS FaceOffWonDefensifZone, Team" . $TypeText . "StatHistory.FaceOffTotalDefensifZone AS FaceOffTotalDefensifZone, Team" . $TypeText . "StatHistory.FaceOffWonOffensifZone AS FaceOffWonOffensifZone, Team" . $TypeText . "StatHistory.FaceOffTotalOffensifZone AS FaceOffTotalOffensifZone, Team" . $TypeText . "StatHistory.FaceOffWonNeutralZone AS FaceOffWonNeutralZone, Team" . $TypeText . "StatHistory.FaceOffTotalNeutralZone AS FaceOffTotalNeutralZone, Team" . $TypeText . "StatHistory.EmptyNetGoal AS EmptyNetGoal FROM Team" . $TypeText . "StatHistory WHERE Playoff = '" . $PlayoffString. "' ) AS MainTable";
					}else{
						/* Requesting Playoff While in Season or Requesting Season while in Playoff or In Pre-Season Mode - Do not fetch data from live database */
						$Query = $Query . " FROM (SELECT Team" . $TypeText . "StatHistory.Year as Year, Team" . $TypeText . "StatHistory.Number as Number, Team" . $TypeText . "StatHistory.UniqueID as UniqueID, Team" . $TypeText . "StatHistory.Name as Name, Team" . $TypeText . "StatHistory.Name as OrderName,'0' As TeamThemeID, Team" . $TypeText . "StatHistory.GP AS GP, Team" . $TypeText . "StatHistory.W AS W, Team" . $TypeText . "StatHistory.L AS L, Team" . $TypeText . "StatHistory.T AS T, Team" . $TypeText . "StatHistory.OTW AS OTW, Team" . $TypeText . "StatHistory.OTL AS OTL, Team" . $TypeText . "StatHistory.SOW AS SOW, Team" . $TypeText . "StatHistory.SOL AS SOL, Team" . $TypeText . "StatHistory.Points AS Points, Team" . $TypeText . "StatHistory.GF AS GF, Team" . $TypeText . "StatHistory.GA AS GA, Team" . $TypeText . "StatHistory.HomeGP AS HomeGP, Team" . $TypeText . "StatHistory.HomeW AS HomeW, Team" . $TypeText . "StatHistory.HomeL AS HomeL, Team" . $TypeText . "StatHistory.HomeT AS HomeT, Team" . $TypeText . "StatHistory.HomeOTW AS HomeOTW, Team" . $TypeText . "StatHistory.HomeOTL AS HomeOTL, Team" . $TypeText . "StatHistory.HomeSOW AS HomeSOW, Team" . $TypeText . "StatHistory.HomeSOL AS HomeSOL, Team" . $TypeText . "StatHistory.HomeGF AS HomeGF, Team" . $TypeText . "StatHistory.HomeGA AS HomeGA, Team" . $TypeText . "StatHistory.PPAttemp AS PPAttemp, Team" . $TypeText . "StatHistory.PPGoal AS PPGoal, Team" . $TypeText . "StatHistory.PKAttemp AS PKAttemp, Team" . $TypeText . "StatHistory.PKGoalGA AS PKGoalGA, Team" . $TypeText . "StatHistory.PKGoalGF AS PKGoalGF, Team" . $TypeText . "StatHistory.ShotsFor AS ShotsFor, Team" . $TypeText . "StatHistory.ShotsAga AS ShotsAga, Team" . $TypeText . "StatHistory.ShotsBlock AS ShotsBlock, Team" . $TypeText . "StatHistory.ShotsPerPeriod1 AS ShotsPerPeriod1, Team" . $TypeText . "StatHistory.ShotsPerPeriod2 AS ShotsPerPeriod2, Team" . $TypeText . "StatHistory.ShotsPerPeriod3 AS ShotsPerPeriod3, Team" . $TypeText . "StatHistory.ShotsPerPeriod4 AS ShotsPerPeriod4, Team" . $TypeText . "StatHistory.GoalsPerPeriod1 AS GoalsPerPeriod1, Team" . $TypeText . "StatHistory.GoalsPerPeriod2 AS GoalsPerPeriod2, Team" . $TypeText . "StatHistory.GoalsPerPeriod3 AS GoalsPerPeriod3, Team" . $TypeText . "StatHistory.GoalsPerPeriod4 AS GoalsPerPeriod4, Team" . $TypeText . "StatHistory.PuckTimeInZoneDF AS PuckTimeInZoneDF, Team" . $TypeText . "StatHistory.PuckTimeInZoneOF AS PuckTimeInZoneOF, Team" . $TypeText . "StatHistory.PuckTimeInZoneNT AS PuckTimeInZoneNT, Team" . $TypeText . "StatHistory.PuckTimeControlinZoneDF AS PuckTimeControlinZoneDF, Team" . $TypeText . "StatHistory.PuckTimeControlinZoneOF AS PuckTimeControlinZoneOF, Team" . $TypeText . "StatHistory.PuckTimeControlinZoneNT AS PuckTimeControlinZoneNT, Team" . $TypeText . "StatHistory.Shutouts AS Shutouts, Team" . $TypeText . "StatHistory.TotalGoal AS TotalGoal, Team" . $TypeText . "StatHistory.TotalAssist AS TotalAssist, Team" . $TypeText . "StatHistory.TotalPoint AS TotalPoint, Team" . $TypeText . "StatHistory.Pim AS Pim, Team" . $TypeText . "StatHistory.Hits AS Hits, Team" . $TypeText . "StatHistory.FaceOffWonDefensifZone AS FaceOffWonDefensifZone, Team" . $TypeText . "StatHistory.FaceOffTotalDefensifZone AS FaceOffTotalDefensifZone, Team" . $TypeText . "StatHistory.FaceOffWonOffensifZone AS FaceOffWonOffensifZone, Team" . $TypeText . "StatHistory.FaceOffTotalOffensifZone AS FaceOffTotalOffensifZone, Team" . $TypeText . "StatHistory.FaceOffWonNeutralZone AS FaceOffWonNeutralZone, Team" . $TypeText . "StatHistory.FaceOffTotalNeutralZone AS FaceOffTotalNeutralZone, Team" . $TypeText . "StatHistory.EmptyNetGoal AS EmptyNetGoal FROM Team" . $TypeText . "StatHistory WHERE Playoff = '" . $PlayoffString. "' ) AS MainTable";
					}
					if($Team > 0){$Query = $Query . " WHERE MainTable.Number = " . $Team;}
					
					
					If ($OrderByInput == "" AND $DESCQuery == FALSE){
						/* Default Sorting Hardcode  */
						if($Year == 9998){
							If ($AllSeasonMergeTeam > 0){
								$Query = $Query . " WHERE UniqueID = " . $AllSeasonMergeTeam . " ORDER BY MainTable.Name";
							}else{
								$Query = $Query . " GROUP BY UniqueID ORDER BY MainTable.Name";
							}
						}else{
							$Query = $Query . " ORDER BY MainTable.Year, MainTable.Name";
						}
						
					}else{
					
						if($Year == 9998){
							If ($AllSeasonMergeTeam > 0){
								If ($OrderByField = "Name"){
									$Query = $Query . " WHERE UniqueID = " . $AllSeasonMergeTeam . " ORDER BY MainTable.Name";
								}else{
									$Query = $Query . " WHERE UniqueID = " . $AllSeasonMergeTeam . " ORDER BY Sum(MainTable." . $OrderByField . ")";
								}
							}else{
								If ($OrderByField = "Name"){
									$Query = $Query . " GROUP BY UniqueID ORDER BY MainTable.Name";
								}else{
									$Query = $Query . " GROUP BY UniqueID ORDER BY Sum(MainTable." . $OrderByField . ")";
								}
							}
						}else{
							$Query = $Query . " ORDER BY MainTable." . $OrderByField;
						}
					}
					$Title = $Title  . $DynamicTitleLang['TeamStat'] . $TitleType;	
				}

				/* Order by  */
				If ($DESCQuery == TRUE){
					$Query = $Query . " DESC";
					$Title = $Title . $DynamicTitleLang['InDecendingOrderBy'] . $OrderByFieldText;
				}else{
					$Query = $Query . " ASC";
					$Title = $Title . $DynamicTitleLang['InAscendingOrderBy'] . $OrderByFieldText;
				}
				$TeamStatSub = $db->query($Query);
	
			}else{
				/* Specific Year */
				
				$Query = "Select Name, PointSystemW from LeagueGeneral";
				$LeagueGeneral = $db->querySingle($Query,true);		

				//Confirm Valid Data Found
				$CareerDBFormatV2CheckCheck = $db->querySingle("Select Count(Name) As CountName from LeagueGeneral  WHERE Year = " . $Year . " And Playoff = '" . $PlayoffString. "'",true);
				If ($CareerDBFormatV2CheckCheck['CountName'] == 1){$LeagueName = $LeagueGeneral['Name'];}else{$Year = (integer)0;$HistoryOutput = (boolean)False;Goto RegularSeason;}
				
				If ($Team == 0 OR isset($_GET['Season'])){ /*  The Season Variable Overwrite the Team  */ 
					$Query = "SELECT MainTable.* FROM (SELECT Team" . $TypeText . "StatHistory.Number as Number, Team" . $TypeText . "StatHistory.Name as Name, Team" . $TypeText . "StatHistory.Name as OrderName,'0' As TeamThemeID,  Team" . $TypeText . "StatHistory.GP AS GP, Team" . $TypeText . "StatHistory.W AS W, Team" . $TypeText . "StatHistory.L AS L, Team" . $TypeText . "StatHistory.T AS T, Team" . $TypeText . "StatHistory.OTW AS OTW, Team" . $TypeText . "StatHistory.OTL AS OTL, Team" . $TypeText . "StatHistory.SOW AS SOW, Team" . $TypeText . "StatHistory.SOL AS SOL, Team" . $TypeText . "StatHistory.Points AS Points, Team" . $TypeText . "StatHistory.GF AS GF, Team" . $TypeText . "StatHistory.GA AS GA, Team" . $TypeText . "StatHistory.HomeGP AS HomeGP, Team" . $TypeText . "StatHistory.HomeW AS HomeW, Team" . $TypeText . "StatHistory.HomeL AS HomeL, Team" . $TypeText . "StatHistory.HomeT AS HomeT, Team" . $TypeText . "StatHistory.HomeOTW AS HomeOTW, Team" . $TypeText . "StatHistory.HomeOTL AS HomeOTL, Team" . $TypeText . "StatHistory.HomeSOW AS HomeSOW, Team" . $TypeText . "StatHistory.HomeSOL AS HomeSOL, Team" . $TypeText . "StatHistory.HomeGF AS HomeGF, Team" . $TypeText . "StatHistory.HomeGA AS HomeGA, Team" . $TypeText . "StatHistory.PPAttemp AS PPAttemp, Team" . $TypeText . "StatHistory.PPGoal AS PPGoal, Team" . $TypeText . "StatHistory.PKAttemp AS PKAttemp, Team" . $TypeText . "StatHistory.PKGoalGA AS PKGoalGA, Team" . $TypeText . "StatHistory.PKGoalGF AS PKGoalGF, Team" . $TypeText . "StatHistory.ShotsFor AS ShotsFor, Team" . $TypeText . "StatHistory.ShotsAga AS ShotsAga, Team" . $TypeText . "StatHistory.ShotsBlock AS ShotsBlock, Team" . $TypeText . "StatHistory.ShotsPerPeriod1 AS ShotsPerPeriod1, Team" . $TypeText . "StatHistory.ShotsPerPeriod2 AS ShotsPerPeriod2, Team" . $TypeText . "StatHistory.ShotsPerPeriod3 AS ShotsPerPeriod3, Team" . $TypeText . "StatHistory.ShotsPerPeriod4 AS ShotsPerPeriod4, Team" . $TypeText . "StatHistory.GoalsPerPeriod1 AS GoalsPerPeriod1, Team" . $TypeText . "StatHistory.GoalsPerPeriod2 AS GoalsPerPeriod2, Team" . $TypeText . "StatHistory.GoalsPerPeriod3 AS GoalsPerPeriod3, Team" . $TypeText . "StatHistory.GoalsPerPeriod4 AS GoalsPerPeriod4, Team" . $TypeText . "StatHistory.PuckTimeInZoneDF AS PuckTimeInZoneDF, Team" . $TypeText . "StatHistory.PuckTimeInZoneOF AS PuckTimeInZoneOF, Team" . $TypeText . "StatHistory.PuckTimeInZoneNT AS PuckTimeInZoneNT, Team" . $TypeText . "StatHistory.PuckTimeControlinZoneDF AS PuckTimeControlinZoneDF, Team" . $TypeText . "StatHistory.PuckTimeControlinZoneOF AS PuckTimeControlinZoneOF, Team" . $TypeText . "StatHistory.PuckTimeControlinZoneNT AS PuckTimeControlinZoneNT, Team" . $TypeText . "StatHistory.Shutouts AS Shutouts, Team" . $TypeText . "StatHistory.TotalGoal AS TotalGoal, Team" . $TypeText . "StatHistory.TotalAssist AS TotalAssist, Team" . $TypeText . "StatHistory.TotalPoint AS TotalPoint, Team" . $TypeText . "StatHistory.Pim AS Pim, Team" . $TypeText . "StatHistory.Hits AS Hits, Team" . $TypeText . "StatHistory.FaceOffWonDefensifZone AS FaceOffWonDefensifZone, Team" . $TypeText . "StatHistory.FaceOffTotalDefensifZone AS FaceOffTotalDefensifZone, Team" . $TypeText . "StatHistory.FaceOffWonOffensifZone AS FaceOffWonOffensifZone, Team" . $TypeText . "StatHistory.FaceOffTotalOffensifZone AS FaceOffTotalOffensifZone, Team" . $TypeText . "StatHistory.FaceOffWonNeutralZone AS FaceOffWonNeutralZone, Team" . $TypeText . "StatHistory.FaceOffTotalNeutralZone AS FaceOffTotalNeutralZone, Team" . $TypeText . "StatHistory.EmptyNetGoal AS EmptyNetGoal FROM Team" . $TypeText . "StatHistory WHERE Year = " . $Year . " And Playoff = '" . $PlayoffString. "' UNION ALL SELECT 105 as Number, '<strong>Average</strong>' as Name,'ZZZZZZZZZZZZZ' as OrderName,'0' As TeamThemeID, Round(AVG(Team" . $TypeText . "StatHistory.GP),2) AS GP, Round(AVG(Team" . $TypeText . "StatHistory.W),2) AS W, Round(AVG(Team" . $TypeText . "StatHistory.L),2) AS L, Round(AVG(Team" . $TypeText . "StatHistory.T),2) AS T, Round(AVG(Team" . $TypeText . "StatHistory.OTW),2) AS OTW, Round(AVG(Team" . $TypeText . "StatHistory.OTL),2) AS OTL, Round(AVG(Team" . $TypeText . "StatHistory.SOW),2) AS SOW, Round(AVG(Team" . $TypeText . "StatHistory.SOL),2) AS SOL, Round(AVG(Team" . $TypeText . "StatHistory.Points),2) AS Points, Round(AVG(Team" . $TypeText . "StatHistory.GF),2) AS GF, Round(AVG(Team" . $TypeText . "StatHistory.GA),2) AS GA, Round(AVG(Team" . $TypeText . "StatHistory.HomeGP),2) AS HomeGP, Round(AVG(Team" . $TypeText . "StatHistory.HomeW),2) AS HomeW, Round(AVG(Team" . $TypeText . "StatHistory.HomeL),2) AS HomeL, Round(AVG(Team" . $TypeText . "StatHistory.HomeT),2) AS HomeT, Round(AVG(Team" . $TypeText . "StatHistory.HomeOTW),2) AS HomeOTW, Round(AVG(Team" . $TypeText . "StatHistory.HomeOTL),2) AS HomeOTL, Round(AVG(Team" . $TypeText . "StatHistory.HomeSOW),2) AS HomeSOW, Round(AVG(Team" . $TypeText . "StatHistory.HomeSOL),2) AS HomeSOL, Round(AVG(Team" . $TypeText . "StatHistory.HomeGF),2) AS HomeGF, Round(AVG(Team" . $TypeText . "StatHistory.HomeGA),2) AS HomeGA, Round(AVG(Team" . $TypeText . "StatHistory.PPAttemp),2) AS PPAttemp, Round(AVG(Team" . $TypeText . "StatHistory.PPGoal),2) AS PPGoal, Round(AVG(Team" . $TypeText . "StatHistory.PKAttemp),2) AS PKAttemp, Round(AVG(Team" . $TypeText . "StatHistory.PKGoalGA),2) AS PKGoalGA, Round(AVG(Team" . $TypeText . "StatHistory.PKGoalGF),2) AS PKGoalGF, Round(AVG(Team" . $TypeText . "StatHistory.ShotsFor),2) AS ShotsFor, Round(AVG(Team" . $TypeText . "StatHistory.ShotsAga),2) AS ShotsAga, Round(AVG(Team" . $TypeText . "StatHistory.ShotsBlock),2) AS ShotsBlock, Round(AVG(Team" . $TypeText . "StatHistory.ShotsPerPeriod1),2) AS ShotsPerPeriod1, Round(AVG(Team" . $TypeText . "StatHistory.ShotsPerPeriod2),2) AS ShotsPerPeriod2, Round(AVG(Team" . $TypeText . "StatHistory.ShotsPerPeriod3),2) AS ShotsPerPeriod3, Round(AVG(Team" . $TypeText . "StatHistory.ShotsPerPeriod4),2) AS ShotsPerPeriod4, Round(AVG(Team" . $TypeText . "StatHistory.GoalsPerPeriod1),2) AS GoalsPerPeriod1, Round(AVG(Team" . $TypeText . "StatHistory.GoalsPerPeriod2),2) AS GoalsPerPeriod2, Round(AVG(Team" . $TypeText . "StatHistory.GoalsPerPeriod3),2) AS GoalsPerPeriod3, Round(AVG(Team" . $TypeText . "StatHistory.GoalsPerPeriod4),2) AS GoalsPerPeriod4, Round(AVG(Team" . $TypeText . "StatHistory.PuckTimeInZoneDF),2) AS PuckTimeInZoneDF, Round(AVG(Team" . $TypeText . "StatHistory.PuckTimeInZoneOF),2) AS PuckTimeInZoneOF, Round(AVG(Team" . $TypeText . "StatHistory.PuckTimeInZoneNT),2) AS PuckTimeInZoneNT, Round(AVG(Team" . $TypeText . "StatHistory.PuckTimeControlinZoneDF),2) AS PuckTimeControlinZoneDF, Round(AVG(Team" . $TypeText . "StatHistory.PuckTimeControlinZoneOF),2) AS PuckTimeControlinZoneOF, Round(AVG(Team" . $TypeText . "StatHistory.PuckTimeControlinZoneNT),2) AS PuckTimeControlinZoneNT, Round(AVG(Team" . $TypeText . "StatHistory.Shutouts),2) AS Shutouts, Round(AVG(Team" . $TypeText . "StatHistory.TotalGoal),2) AS TotalGoal, Round(AVG(Team" . $TypeText . "StatHistory.TotalAssist),2) AS TotalAssist, Round(AVG(Team" . $TypeText . "StatHistory.TotalPoint),2) AS TotalPoint, Round(AVG(Team" . $TypeText . "StatHistory.Pim),2) AS Pim, Round(AVG(Team" . $TypeText . "StatHistory.Hits),2) AS Hits, Round(AVG(Team" . $TypeText . "StatHistory.FaceOffWonDefensifZone),2) AS FaceOffWonDefensifZone, Round(AVG(Team" . $TypeText . "StatHistory.FaceOffTotalDefensifZone),2) AS FaceOffTotalDefensifZone, Round(AVG(Team" . $TypeText . "StatHistory.FaceOffWonOffensifZone),2) AS FaceOffWonOffensifZone, Round(AVG(Team" . $TypeText . "StatHistory.FaceOffTotalOffensifZone),2) AS FaceOffTotalOffensifZone, Round(AVG(Team" . $TypeText . "StatHistory.FaceOffWonNeutralZone),2) AS FaceOffWonNeutralZone, Round(AVG(Team" . $TypeText . "StatHistory.FaceOffTotalNeutralZone),2) AS FaceOffTotalNeutralZone, Round(AVG(Team" . $TypeText . "StatHistory.EmptyNetGoal),2) AS EmptyNetGoal FROM Team" . $TypeText . "StatHistory WHERE Year = " . $Year . " And Playoff = '" . $PlayoffString. "') AS MainTable  ORDER BY CASE WHEN Number > 100 THEN 2 ELSE 1 END, ". $OrderByField;
					$Title = $DynamicTitleLang['TeamStat'] . " " . $TitleType;
				}else{
					$Query = "SELECT Name FROM Team" . $TypeText . "InfoHistory WHERE Number = " . $Team . " AND Year = " . $Year . " And Playoff = '" . $PlayoffString. "'";
					$TeamName = $db->querySingle($Query);
					$Title = $DynamicTitleLang['TeamStatVS'] . " " . $TitleType . " " . $TeamName . " ";
					$Query = "SELECT MainTable.* FROM (SELECT Team" . $TypeText . "StatVSHistory.TeamVSName AS Name, Team" . $TypeText . "StatVSHistory.TeamVSNumber AS Number, '0' As TeamThemeID, Team" . $TypeText . "StatVSHistory.GP, Team" . $TypeText . "StatVSHistory.W, Team" . $TypeText . "StatVSHistory.L, Team" . $TypeText . "StatVSHistory.T, Team" . $TypeText . "StatVSHistory.OTW, Team" . $TypeText . "StatVSHistory.OTL, Team" . $TypeText . "StatVSHistory.SOW, Team" . $TypeText . "StatVSHistory.SOL, Team" . $TypeText . "StatVSHistory.Points, Team" . $TypeText . "StatVSHistory.GF, Team" . $TypeText . "StatVSHistory.GA, Team" . $TypeText . "StatVSHistory.HomeGP, Team" . $TypeText . "StatVSHistory.HomeW, Team" . $TypeText . "StatVSHistory.HomeL, Team" . $TypeText . "StatVSHistory.HomeT, Team" . $TypeText . "StatVSHistory.HomeOTW, Team" . $TypeText . "StatVSHistory.HomeOTL, Team" . $TypeText . "StatVSHistory.HomeSOW, Team" . $TypeText . "StatVSHistory.HomeSOL, Team" . $TypeText . "StatVSHistory.HomeGF, Team" . $TypeText . "StatVSHistory.HomeGA, Team" . $TypeText . "StatVSHistory.PPAttemp, Team" . $TypeText . "StatVSHistory.PPGoal, Team" . $TypeText . "StatVSHistory.PKAttemp, Team" . $TypeText . "StatVSHistory.PKGoalGA, Team" . $TypeText . "StatVSHistory.PKGoalGF, Team" . $TypeText . "StatVSHistory.ShotsFor, Team" . $TypeText . "StatVSHistory.ShotsAga, Team" . $TypeText . "StatVSHistory.ShotsBlock, Team" . $TypeText . "StatVSHistory.ShotsPerPeriod1, Team" . $TypeText . "StatVSHistory.ShotsPerPeriod2, Team" . $TypeText . "StatVSHistory.ShotsPerPeriod3, Team" . $TypeText . "StatVSHistory.ShotsPerPeriod4, Team" . $TypeText . "StatVSHistory.GoalsPerPeriod1, Team" . $TypeText . "StatVSHistory.GoalsPerPeriod2, Team" . $TypeText . "StatVSHistory.GoalsPerPeriod3, Team" . $TypeText . "StatVSHistory.GoalsPerPeriod4, Team" . $TypeText . "StatVSHistory.PuckTimeInZoneDF, Team" . $TypeText . "StatVSHistory.PuckTimeInZoneOF, Team" . $TypeText . "StatVSHistory.PuckTimeInZoneNT, Team" . $TypeText . "StatVSHistory.PuckTimeControlinZoneDF, Team" . $TypeText . "StatVSHistory.PuckTimeControlinZoneOF, Team" . $TypeText . "StatVSHistory.PuckTimeControlinZoneNT, Team" . $TypeText . "StatVSHistory.Shutouts, Team" . $TypeText . "StatVSHistory.TotalGoal, Team" . $TypeText . "StatVSHistory.TotalAssist, Team" . $TypeText . "StatVSHistory.TotalPoint, Team" . $TypeText . "StatVSHistory.Pim, Team" . $TypeText . "StatVSHistory.Hits, Team" . $TypeText . "StatVSHistory.FaceOffWonDefensifZone, Team" . $TypeText . "StatVSHistory.FaceOffTotalDefensifZone, Team" . $TypeText . "StatVSHistory.FaceOffWonOffensifZone, Team" . $TypeText . "StatVSHistory.FaceOffTotalOffensifZone, Team" . $TypeText . "StatVSHistory.FaceOffWonNeutralZone, Team" . $TypeText . "StatVSHistory.FaceOffTotalNeutralZone, Team" . $TypeText . "StatVSHistory.EmptyNetGoal FROM Team" . $TypeText . "StatVSHistory WHERE GP > 0 AND TeamNumber = " . $Team . " AND Year = " . $Year . " And Playoff = '" . $PlayoffString. "' UNION ALL SELECT 'Total' as Name, '104' as Number, '0' As TeamThemeID, Team" . $TypeText . "StatHistory.GP, Team" . $TypeText . "StatHistory.W, Team" . $TypeText . "StatHistory.L, Team" . $TypeText . "StatHistory.T, Team" . $TypeText . "StatHistory.OTW, Team" . $TypeText . "StatHistory.OTL, Team" . $TypeText . "StatHistory.SOW, Team" . $TypeText . "StatHistory.SOL, Team" . $TypeText . "StatHistory.Points, Team" . $TypeText . "StatHistory.GF, Team" . $TypeText . "StatHistory.GA, Team" . $TypeText . "StatHistory.HomeGP, Team" . $TypeText . "StatHistory.HomeW, Team" . $TypeText . "StatHistory.HomeL, Team" . $TypeText . "StatHistory.HomeT, Team" . $TypeText . "StatHistory.HomeOTW, Team" . $TypeText . "StatHistory.HomeOTL, Team" . $TypeText . "StatHistory.HomeSOW, Team" . $TypeText . "StatHistory.HomeSOL, Team" . $TypeText . "StatHistory.HomeGF, Team" . $TypeText . "StatHistory.HomeGA,  Team" . $TypeText . "StatHistory.PPAttemp, Team" . $TypeText . "StatHistory.PPGoal, Team" . $TypeText . "StatHistory.PKAttemp, Team" . $TypeText . "StatHistory.PKGoalGA, Team" . $TypeText . "StatHistory.PKGoalGF, Team" . $TypeText . "StatHistory.ShotsFor, Team" . $TypeText . "StatHistory.ShotsAga, Team" . $TypeText . "StatHistory.ShotsBlock, Team" . $TypeText . "StatHistory.ShotsPerPeriod1, Team" . $TypeText . "StatHistory.ShotsPerPeriod2, Team" . $TypeText . "StatHistory.ShotsPerPeriod3, Team" . $TypeText . "StatHistory.ShotsPerPeriod4, Team" . $TypeText . "StatHistory.GoalsPerPeriod1, Team" . $TypeText . "StatHistory.GoalsPerPeriod2, Team" . $TypeText . "StatHistory.GoalsPerPeriod3, Team" . $TypeText . "StatHistory.GoalsPerPeriod4, Team" . $TypeText . "StatHistory.PuckTimeInZoneDF, Team" . $TypeText . "StatHistory.PuckTimeInZoneOF, Team" . $TypeText . "StatHistory.PuckTimeInZoneNT, Team" . $TypeText . "StatHistory.PuckTimeControlinZoneDF, Team" . $TypeText . "StatHistory.PuckTimeControlinZoneOF, Team" . $TypeText . "StatHistory.PuckTimeControlinZoneNT, Team" . $TypeText . "StatHistory.Shutouts, Team" . $TypeText . "StatHistory.TotalGoal, Team" . $TypeText . "StatHistory.TotalAssist, Team" . $TypeText . "StatHistory.TotalPoint, Team" . $TypeText . "StatHistory.Pim, Team" . $TypeText . "StatHistory.Hits, Team" . $TypeText . "StatHistory.FaceOffWonDefensifZone, Team" . $TypeText . "StatHistory.FaceOffTotalDefensifZone, Team" . $TypeText . "StatHistory.FaceOffWonOffensifZone, Team" . $TypeText . "StatHistory.FaceOffTotalOffensifZone, Team" . $TypeText . "StatHistory.FaceOffWonNeutralZone, Team" . $TypeText . "StatHistory.FaceOffTotalNeutralZone, Team" . $TypeText . "StatHistory.EmptyNetGoal FROM Team" . $TypeText . "StatHistory WHERE Number = " . $Team . " AND Year = " . $Year . " And Playoff = '" . $PlayoffString. "') AS MainTable ORDER BY CASE WHEN Number > 100 THEN 2 ELSE 1 END, ".  $OrderByField;
				}
				
				/* Order by  */
				If ($DESCQuery == TRUE){
					$Query = $Query . " DESC";
					$Title = $Title . $DynamicTitleLang['InDecendingOrderBy'] . $OrderByFieldText;
				}else{
					$Query = $Query . " ASC";
					$Title = $Title . $DynamicTitleLang['InAscendingOrderBy'] . $OrderByFieldText;
				}
				$TeamStatSub = $db->query($Query);
				
				$Title = $Title  . " - " . $SearchLang['Year'] . " " . $Year;
				If ($Playoff == True){$Title = $Title . $TopMenuLang['Playoff'];}				
				
			}
			
			echo "<title>" . $LeagueName . " - " . $Title . "</title>";

		}else{
			Goto RegularSeason;
		}
	}else{ 
		/* Regular Season Database Only */	
		RegularSeason:

		if(isset($_GET['Season'])){$TypeText = $TypeText . "Season";}		
	
		$db = new SQLite3($DatabaseFile);
		
		$Query = "Select Name, PointSystemW from LeagueGeneral";
		$LeagueGeneral = $db->querySingle($Query,true);		
		$LeagueName = $LeagueGeneral['Name'];
		
		If ($Team == 0 OR isset($_GET['Season'])){ /*  The Season Variable Overwrite the Team  */ 
			$Query = "SELECT MainTable.* FROM (SELECT Team" . $TypeText . "Stat.Number as Number, Team" . $TypeText . "Stat.Name as Name, Team" . $TypeText . "Stat.Name as OrderName, Team" . $TypeText . "Stat.TeamThemeID as TeamThemeID,  Team" . $TypeText . "Stat.GP AS GP, Team" . $TypeText . "Stat.W AS W, Team" . $TypeText . "Stat.L AS L, Team" . $TypeText . "Stat.T AS T, Team" . $TypeText . "Stat.OTW AS OTW, Team" . $TypeText . "Stat.OTL AS OTL, Team" . $TypeText . "Stat.SOW AS SOW, Team" . $TypeText . "Stat.SOL AS SOL, Team" . $TypeText . "Stat.Points AS Points, Team" . $TypeText . "Stat.GF AS GF, Team" . $TypeText . "Stat.GA AS GA, Team" . $TypeText . "Stat.HomeGP AS HomeGP, Team" . $TypeText . "Stat.HomeW AS HomeW, Team" . $TypeText . "Stat.HomeL AS HomeL, Team" . $TypeText . "Stat.HomeT AS HomeT, Team" . $TypeText . "Stat.HomeOTW AS HomeOTW, Team" . $TypeText . "Stat.HomeOTL AS HomeOTL, Team" . $TypeText . "Stat.HomeSOW AS HomeSOW, Team" . $TypeText . "Stat.HomeSOL AS HomeSOL, Team" . $TypeText . "Stat.HomeGF AS HomeGF, Team" . $TypeText . "Stat.HomeGA AS HomeGA, Team" . $TypeText . "Stat.PPAttemp AS PPAttemp, Team" . $TypeText . "Stat.PPGoal AS PPGoal, Team" . $TypeText . "Stat.PKAttemp AS PKAttemp, Team" . $TypeText . "Stat.PKGoalGA AS PKGoalGA, Team" . $TypeText . "Stat.PKGoalGF AS PKGoalGF, Team" . $TypeText . "Stat.ShotsFor AS ShotsFor, Team" . $TypeText . "Stat.ShotsAga AS ShotsAga, Team" . $TypeText . "Stat.ShotsBlock AS ShotsBlock, Team" . $TypeText . "Stat.ShotsPerPeriod1 AS ShotsPerPeriod1, Team" . $TypeText . "Stat.ShotsPerPeriod2 AS ShotsPerPeriod2, Team" . $TypeText . "Stat.ShotsPerPeriod3 AS ShotsPerPeriod3, Team" . $TypeText . "Stat.ShotsPerPeriod4 AS ShotsPerPeriod4, Team" . $TypeText . "Stat.GoalsPerPeriod1 AS GoalsPerPeriod1, Team" . $TypeText . "Stat.GoalsPerPeriod2 AS GoalsPerPeriod2, Team" . $TypeText . "Stat.GoalsPerPeriod3 AS GoalsPerPeriod3, Team" . $TypeText . "Stat.GoalsPerPeriod4 AS GoalsPerPeriod4, Team" . $TypeText . "Stat.PuckTimeInZoneDF AS PuckTimeInZoneDF, Team" . $TypeText . "Stat.PuckTimeInZoneOF AS PuckTimeInZoneOF, Team" . $TypeText . "Stat.PuckTimeInZoneNT AS PuckTimeInZoneNT, Team" . $TypeText . "Stat.PuckTimeControlinZoneDF AS PuckTimeControlinZoneDF, Team" . $TypeText . "Stat.PuckTimeControlinZoneOF AS PuckTimeControlinZoneOF, Team" . $TypeText . "Stat.PuckTimeControlinZoneNT AS PuckTimeControlinZoneNT, Team" . $TypeText . "Stat.Shutouts AS Shutouts, Team" . $TypeText . "Stat.TotalGoal AS TotalGoal, Team" . $TypeText . "Stat.TotalAssist AS TotalAssist, Team" . $TypeText . "Stat.TotalPoint AS TotalPoint, Team" . $TypeText . "Stat.Pim AS Pim, Team" . $TypeText . "Stat.Hits AS Hits, Team" . $TypeText . "Stat.FaceOffWonDefensifZone AS FaceOffWonDefensifZone, Team" . $TypeText . "Stat.FaceOffTotalDefensifZone AS FaceOffTotalDefensifZone, Team" . $TypeText . "Stat.FaceOffWonOffensifZone AS FaceOffWonOffensifZone, Team" . $TypeText . "Stat.FaceOffTotalOffensifZone AS FaceOffTotalOffensifZone, Team" . $TypeText . "Stat.FaceOffWonNeutralZone AS FaceOffWonNeutralZone, Team" . $TypeText . "Stat.FaceOffTotalNeutralZone AS FaceOffTotalNeutralZone, Team" . $TypeText . "Stat.EmptyNetGoal AS EmptyNetGoal FROM Team" . $TypeText . "Stat UNION ALL SELECT 105 as Number, '<strong>Average</strong>' as Name,'ZZZZZZZZZZZZZ' as OrderName, '0' As TeamThemeID, Round(AVG(Team" . $TypeText . "Stat.GP),2) AS GP, Round(AVG(Team" . $TypeText . "Stat.W),2) AS W, Round(AVG(Team" . $TypeText . "Stat.L),2) AS L, Round(AVG(Team" . $TypeText . "Stat.T),2) AS T, Round(AVG(Team" . $TypeText . "Stat.OTW),2) AS OTW, Round(AVG(Team" . $TypeText . "Stat.OTL),2) AS OTL, Round(AVG(Team" . $TypeText . "Stat.SOW),2) AS SOW, Round(AVG(Team" . $TypeText . "Stat.SOL),2) AS SOL, Round(AVG(Team" . $TypeText . "Stat.Points),2) AS Points, Round(AVG(Team" . $TypeText . "Stat.GF),2) AS GF, Round(AVG(Team" . $TypeText . "Stat.GA),2) AS GA, Round(AVG(Team" . $TypeText . "Stat.HomeGP),2) AS HomeGP, Round(AVG(Team" . $TypeText . "Stat.HomeW),2) AS HomeW, Round(AVG(Team" . $TypeText . "Stat.HomeL),2) AS HomeL, Round(AVG(Team" . $TypeText . "Stat.HomeT),2) AS HomeT, Round(AVG(Team" . $TypeText . "Stat.HomeOTW),2) AS HomeOTW, Round(AVG(Team" . $TypeText . "Stat.HomeOTL),2) AS HomeOTL, Round(AVG(Team" . $TypeText . "Stat.HomeSOW),2) AS HomeSOW, Round(AVG(Team" . $TypeText . "Stat.HomeSOL),2) AS HomeSOL, Round(AVG(Team" . $TypeText . "Stat.HomeGF),2) AS HomeGF, Round(AVG(Team" . $TypeText . "Stat.HomeGA),2) AS HomeGA, Round(AVG(Team" . $TypeText . "Stat.PPAttemp),2) AS PPAttemp, Round(AVG(Team" . $TypeText . "Stat.PPGoal),2) AS PPGoal, Round(AVG(Team" . $TypeText . "Stat.PKAttemp),2) AS PKAttemp, Round(AVG(Team" . $TypeText . "Stat.PKGoalGA),2) AS PKGoalGA, Round(AVG(Team" . $TypeText . "Stat.PKGoalGF),2) AS PKGoalGF, Round(AVG(Team" . $TypeText . "Stat.ShotsFor),2) AS ShotsFor, Round(AVG(Team" . $TypeText . "Stat.ShotsAga),2) AS ShotsAga, Round(AVG(Team" . $TypeText . "Stat.ShotsBlock),2) AS ShotsBlock, Round(AVG(Team" . $TypeText . "Stat.ShotsPerPeriod1),2) AS ShotsPerPeriod1, Round(AVG(Team" . $TypeText . "Stat.ShotsPerPeriod2),2) AS ShotsPerPeriod2, Round(AVG(Team" . $TypeText . "Stat.ShotsPerPeriod3),2) AS ShotsPerPeriod3, Round(AVG(Team" . $TypeText . "Stat.ShotsPerPeriod4),2) AS ShotsPerPeriod4, Round(AVG(Team" . $TypeText . "Stat.GoalsPerPeriod1),2) AS GoalsPerPeriod1, Round(AVG(Team" . $TypeText . "Stat.GoalsPerPeriod2),2) AS GoalsPerPeriod2, Round(AVG(Team" . $TypeText . "Stat.GoalsPerPeriod3),2) AS GoalsPerPeriod3, Round(AVG(Team" . $TypeText . "Stat.GoalsPerPeriod4),2) AS GoalsPerPeriod4, Round(AVG(Team" . $TypeText . "Stat.PuckTimeInZoneDF),2) AS PuckTimeInZoneDF, Round(AVG(Team" . $TypeText . "Stat.PuckTimeInZoneOF),2) AS PuckTimeInZoneOF, Round(AVG(Team" . $TypeText . "Stat.PuckTimeInZoneNT),2) AS PuckTimeInZoneNT, Round(AVG(Team" . $TypeText . "Stat.PuckTimeControlinZoneDF),2) AS PuckTimeControlinZoneDF, Round(AVG(Team" . $TypeText . "Stat.PuckTimeControlinZoneOF),2) AS PuckTimeControlinZoneOF, Round(AVG(Team" . $TypeText . "Stat.PuckTimeControlinZoneNT),2) AS PuckTimeControlinZoneNT, Round(AVG(Team" . $TypeText . "Stat.Shutouts),2) AS Shutouts, Round(AVG(Team" . $TypeText . "Stat.TotalGoal),2) AS TotalGoal, Round(AVG(Team" . $TypeText . "Stat.TotalAssist),2) AS TotalAssist, Round(AVG(Team" . $TypeText . "Stat.TotalPoint),2) AS TotalPoint, Round(AVG(Team" . $TypeText . "Stat.Pim),2) AS Pim, Round(AVG(Team" . $TypeText . "Stat.Hits),2) AS Hits, Round(AVG(Team" . $TypeText . "Stat.FaceOffWonDefensifZone),2) AS FaceOffWonDefensifZone, Round(AVG(Team" . $TypeText . "Stat.FaceOffTotalDefensifZone),2) AS FaceOffTotalDefensifZone, Round(AVG(Team" . $TypeText . "Stat.FaceOffWonOffensifZone),2) AS FaceOffWonOffensifZone, Round(AVG(Team" . $TypeText . "Stat.FaceOffTotalOffensifZone),2) AS FaceOffTotalOffensifZone, Round(AVG(Team" . $TypeText . "Stat.FaceOffWonNeutralZone),2) AS FaceOffWonNeutralZone, Round(AVG(Team" . $TypeText . "Stat.FaceOffTotalNeutralZone),2) AS FaceOffTotalNeutralZone, Round(AVG(Team" . $TypeText . "Stat.EmptyNetGoal),2) AS EmptyNetGoal FROM Team" . $TypeText . "Stat) AS MainTable ORDER BY CASE WHEN Number > 100 THEN 2 ELSE 1 END, ". $OrderByField;
			$Title = $DynamicTitleLang['TeamStat'] . " " . $TitleType;	
		}else{
			$Query = "SELECT Name FROM Team" . $TypeText . "Info WHERE Number = " . $Team ;
			$TeamName = $db->querySingle($Query);
			$Title = $DynamicTitleLang['TeamStatVS'] . " " . $TitleType . " " . $TeamName . " ";
			$Query = "SELECT MainTable.* FROM (SELECT Team" . $TypeText . "StatVS.TeamVSName AS Name, Team" . $TypeText . "StatVS.TeamVSNumber AS Number, Team" . $TypeText . "StatVS.TeamVSNumberThemeID as TeamThemeID, Team" . $TypeText . "StatVS.GP, Team" . $TypeText . "StatVS.W, Team" . $TypeText . "StatVS.L, Team" . $TypeText . "StatVS.T, Team" . $TypeText . "StatVS.OTW, Team" . $TypeText . "StatVS.OTL, Team" . $TypeText . "StatVS.SOW, Team" . $TypeText . "StatVS.SOL, Team" . $TypeText . "StatVS.Points, Team" . $TypeText . "StatVS.GF, Team" . $TypeText . "StatVS.GA, Team" . $TypeText . "StatVS.HomeGP, Team" . $TypeText . "StatVS.HomeW, Team" . $TypeText . "StatVS.HomeL, Team" . $TypeText . "StatVS.HomeT, Team" . $TypeText . "StatVS.HomeOTW, Team" . $TypeText . "StatVS.HomeOTL, Team" . $TypeText . "StatVS.HomeSOW, Team" . $TypeText . "StatVS.HomeSOL, Team" . $TypeText . "StatVS.HomeGF, Team" . $TypeText . "StatVS.HomeGA, Team" . $TypeText . "StatVS.PPAttemp, Team" . $TypeText . "StatVS.PPGoal, Team" . $TypeText . "StatVS.PKAttemp, Team" . $TypeText . "StatVS.PKGoalGA, Team" . $TypeText . "StatVS.PKGoalGF, Team" . $TypeText . "StatVS.ShotsFor, Team" . $TypeText . "StatVS.ShotsAga, Team" . $TypeText . "StatVS.ShotsBlock, Team" . $TypeText . "StatVS.ShotsPerPeriod1, Team" . $TypeText . "StatVS.ShotsPerPeriod2, Team" . $TypeText . "StatVS.ShotsPerPeriod3, Team" . $TypeText . "StatVS.ShotsPerPeriod4, Team" . $TypeText . "StatVS.GoalsPerPeriod1, Team" . $TypeText . "StatVS.GoalsPerPeriod2, Team" . $TypeText . "StatVS.GoalsPerPeriod3, Team" . $TypeText . "StatVS.GoalsPerPeriod4, Team" . $TypeText . "StatVS.PuckTimeInZoneDF, Team" . $TypeText . "StatVS.PuckTimeInZoneOF, Team" . $TypeText . "StatVS.PuckTimeInZoneNT, Team" . $TypeText . "StatVS.PuckTimeControlinZoneDF, Team" . $TypeText . "StatVS.PuckTimeControlinZoneOF, Team" . $TypeText . "StatVS.PuckTimeControlinZoneNT, Team" . $TypeText . "StatVS.Shutouts, Team" . $TypeText . "StatVS.TotalGoal, Team" . $TypeText . "StatVS.TotalAssist, Team" . $TypeText . "StatVS.TotalPoint, Team" . $TypeText . "StatVS.Pim, Team" . $TypeText . "StatVS.Hits, Team" . $TypeText . "StatVS.FaceOffWonDefensifZone, Team" . $TypeText . "StatVS.FaceOffTotalDefensifZone, Team" . $TypeText . "StatVS.FaceOffWonOffensifZone, Team" . $TypeText . "StatVS.FaceOffTotalOffensifZone, Team" . $TypeText . "StatVS.FaceOffWonNeutralZone, Team" . $TypeText . "StatVS.FaceOffTotalNeutralZone, Team" . $TypeText . "StatVS.EmptyNetGoal FROM Team" . $TypeText . "StatVS WHERE GP > 0 AND TeamNumber = " . $Team . " UNION ALL SELECT 'Total' as Name, '104' as Number, '0' as TeamThemeID, Team" . $TypeText . "Stat.GP, Team" . $TypeText . "Stat.W, Team" . $TypeText . "Stat.L, Team" . $TypeText . "Stat.T, Team" . $TypeText . "Stat.OTW, Team" . $TypeText . "Stat.OTL, Team" . $TypeText . "Stat.SOW, Team" . $TypeText . "Stat.SOL, Team" . $TypeText . "Stat.Points, Team" . $TypeText . "Stat.GF, Team" . $TypeText . "Stat.GA, Team" . $TypeText . "Stat.HomeGP, Team" . $TypeText . "Stat.HomeW, Team" . $TypeText . "Stat.HomeL, Team" . $TypeText . "Stat.HomeT, Team" . $TypeText . "Stat.HomeOTW, Team" . $TypeText . "Stat.HomeOTL, Team" . $TypeText . "Stat.HomeSOW, Team" . $TypeText . "Stat.HomeSOL, Team" . $TypeText . "Stat.HomeGF, Team" . $TypeText . "Stat.HomeGA,  Team" . $TypeText . "Stat.PPAttemp, Team" . $TypeText . "Stat.PPGoal, Team" . $TypeText . "Stat.PKAttemp, Team" . $TypeText . "Stat.PKGoalGA, Team" . $TypeText . "Stat.PKGoalGF, Team" . $TypeText . "Stat.ShotsFor, Team" . $TypeText . "Stat.ShotsAga, Team" . $TypeText . "Stat.ShotsBlock, Team" . $TypeText . "Stat.ShotsPerPeriod1, Team" . $TypeText . "Stat.ShotsPerPeriod2, Team" . $TypeText . "Stat.ShotsPerPeriod3, Team" . $TypeText . "Stat.ShotsPerPeriod4, Team" . $TypeText . "Stat.GoalsPerPeriod1, Team" . $TypeText . "Stat.GoalsPerPeriod2, Team" . $TypeText . "Stat.GoalsPerPeriod3, Team" . $TypeText . "Stat.GoalsPerPeriod4, Team" . $TypeText . "Stat.PuckTimeInZoneDF, Team" . $TypeText . "Stat.PuckTimeInZoneOF, Team" . $TypeText . "Stat.PuckTimeInZoneNT, Team" . $TypeText . "Stat.PuckTimeControlinZoneDF, Team" . $TypeText . "Stat.PuckTimeControlinZoneOF, Team" . $TypeText . "Stat.PuckTimeControlinZoneNT, Team" . $TypeText . "Stat.Shutouts, Team" . $TypeText . "Stat.TotalGoal, Team" . $TypeText . "Stat.TotalAssist, Team" . $TypeText . "Stat.TotalPoint, Team" . $TypeText . "Stat.Pim, Team" . $TypeText . "Stat.Hits, Team" . $TypeText . "Stat.FaceOffWonDefensifZone, Team" . $TypeText . "Stat.FaceOffTotalDefensifZone, Team" . $TypeText . "Stat.FaceOffWonOffensifZone, Team" . $TypeText . "Stat.FaceOffTotalOffensifZone, Team" . $TypeText . "Stat.FaceOffWonNeutralZone, Team" . $TypeText . "Stat.FaceOffTotalNeutralZone, Team" . $TypeText . "Stat.EmptyNetGoal FROM Team" . $TypeText . "Stat WHERE Number = " . $Team . ") AS MainTable ORDER BY CASE WHEN Number > 100 THEN 2 ELSE 1 END, ".  $OrderByField;
		}
		
		/* Order by  */
		If ($DESCQuery == TRUE){
			$Query = $Query . " DESC";
			$Title = $Title . $DynamicTitleLang['InDecendingOrderBy'] . $OrderByFieldText;
		}else{
			$Query = $Query . " ASC";
			$Title = $Title . $DynamicTitleLang['InAscendingOrderBy'] . $OrderByFieldText;
		}
		echo "<title>" . $LeagueName . " - " . $Title . "</title>";
		$TeamStatSub = $db->query($Query);

	}
} catch (Exception $e) {
STHSErrorTeamStat:
	$LeagueName = $DatabaseNotFound;
	$TeamStat = Null;
	echo "<title>" . $DatabaseNotFound . "</title>";
	$Title = $DatabaseNotFound;
	$Team = 0;
}}?>
</head><body>
<?php include "Menu.php";?>

<script>
$(function() {
  $.tablesorter.addWidget({ id: "numbering",format: function(table) {var c = table.config;$("tr:visible", table.tBodies[0]).each(function(i) {$(this).find('td').eq(0).text(i + 1);});}});	
  $(".STHSPHPTeamsStat_Table").tablesorter({
    widgets: ['numbering','columnSelector', 'stickyHeaders', 'filter', 'output'],
    widgetOptions : {
      columnSelector_container : $('#tablesorter_ColumnSelector'),
      columnSelector_layout : '<label><input type="checkbox">{name}</label>',
      columnSelector_name  : 'title',
      columnSelector_mediaquery: true,
      columnSelector_mediaqueryName: 'Automatic',
      columnSelector_mediaqueryState: true,
      columnSelector_mediaqueryHidden: true,
      columnSelector_breakpoints : [ '20em', '60em', '85em', '92em', '98em', '99em' ],
	  filter_columnFilters: true,
      filter_placeholder: { search : '<?php echo $TableSorterLang['Search'];?>' },
	  filter_searchDelay : 500,	  
      filter_reset: '.tablesorter_Reset',	 
	  output_delivery: 'd',
	  output_saveFileName: 'STHSTeamStat.CSV'
    }
  });
  $('.download').click(function(){
      var $table = $('.STHSPHPTeamsStat_Table'),
      wo = $table[0].config.widgetOptions;
      $table.trigger('outputTable');
      return false;
  });  
});
</script>

<div style="width:99%;margin:auto;">
<?php echo "<h1>" . $Title . "</h1>"; ?>
<div id="ReQueryDiv" style="display:none;">
<?php If($HistoryOutput == False){
	include "SearchTeamsStat.php";
}else{
	include "SearchHistorySub.php";
	include "SearchHistoryTeamsStat.php";
}?>
</div>
<div class="tablesorter_ColumnSelectorWrapper">
	<button class="tablesorter_Output" id="ReQuery"><?php echo $SearchLang['ChangeSearch'];?></button>
    <input id="tablesorter_colSelect1" type="checkbox" class="hidden">
    <label class="tablesorter_ColumnSelectorButton" for="tablesorter_colSelect1"><?php echo $TableSorterLang['ShoworHideColumn'];?></label>
	<button class="tablesorter_Output download" type="button">Output</button>
    <div id="tablesorter_ColumnSelector" class="tablesorter_ColumnSelector"></div>
	<?php include "FilterTip.php";?>
</div>

<table class="tablesorter STHSPHPTeamsStat_Table"><thead><tr>
<?php include "TeamsStatSub.php";?>
</tbody></table></div>
</div>


<?php
include "Footer.php";
?>

