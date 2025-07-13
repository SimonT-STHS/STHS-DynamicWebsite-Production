<?php include "Header.php";
If ($lang == "fr"){include 'LanguageFR-Stat.php';}else{include 'LanguageEN-Stat.php';}
$Title = (string)"";
$TypeText = (string)"Pro";$TitleType = $DynamicTitleLang['Pro'];
if(isset($_GET['Farm'])){$TypeText = "Farm";$TitleType = $DynamicTitleLang['Farm'];}
$MaximumResult = (integer)10;
$MinimumGamePlayer = (integer)1;

$HistoryOutput = (boolean)False;

If (file_exists($DatabaseFile) == false){
	Goto STHSErrorIndividualLeaders;
}else{try{
	
	$Playoff = (boolean)False;
	$PlayoffString = (string)"False";
	$Year = (integer)0;	

	if(isset($_GET['Playoff'])){$Playoff=True;$PlayoffString="True";}
	if(isset($_GET['Year'])){$Year = filter_var($_GET['Year'], FILTER_SANITIZE_NUMBER_INT);} 	
	if(isset($_GET['Max'])){$MaximumResult = filter_var($_GET['Max'], FILTER_SANITIZE_NUMBER_INT);} 	
	
	
	If($Year == 9998 AND file_exists($CareerStatDatabaseFile) == true){  /* History Database */
		$db = new SQLite3($CareerStatDatabaseFile);
		$CareerDBFormatV2CheckCheck = $db->querySingle("SELECT Count(name) AS CountName FROM sqlite_master WHERE type='table' AND name='LeagueGeneral'",true);
		If ($CareerDBFormatV2CheckCheck['CountName'] == 1){
			$HistoryOutput = True;	
			
			$dbLive = new SQLite3($DatabaseFile);
			$Query = "Select Name, LeagueYearOutput, PlayOffStarted, PreSeasonSchedule from LeagueGeneral";
			$LeagueGeneral = $dbLive ->querySingle($Query,true);		
			$LeagueName = $LeagueGeneral['Name'];
			
			$db->query("ATTACH DATABASE '".realpath($DatabaseFile)."' AS CurrentDB");
			If ($Playoff=="True"){$Title = $SearchLang['Playoff'] .  " ";}			
			$Title = $Title . $SearchLang['AllSeasonMerge'] . " - " . $DynamicTitleLang['IndividualLeadersTitle'] . " " . $TitleType ;
	
		}else{
			Goto RegularSeason;
		}
	}else{
		/* Regular Season Database Only */	
		RegularSeason:		
	
		$LeagueName = (string)"";
		$db = new SQLite3($DatabaseFile);
		$Query = "Select Name from LeagueGeneral";
		$LeagueGeneral = $db->querySingle($Query,true);		
		$LeagueName = $LeagueGeneral['Name'];
		$Query = "Select PlayersMugShotBaseURL, PlayersMugShotFileExtension, ProMinimumGamePlayerLeader, FarmMinimumGamePlayerLeader, NumberOfInvidualLeader from LeagueOutputOption";
		$LeagueOutputOption = $db->querySingle($Query,true);
		$MaximumResult = $LeagueOutputOption['NumberOfInvidualLeader'];
		
		$Title = $DynamicTitleLang['IndividualLeadersTitle'] . " " . $TitleType ;
	}
	If ($TypeText == "Pro"){
		$MinimumGamePlayer = $LeagueOutputOption['ProMinimumGamePlayerLeader'];
	}elseif($TypeText == "Farm"){
		$MinimumGamePlayer = $LeagueOutputOption['FarmMinimumGamePlayerLeader'];
	}
	
	echo "<title>" . $LeagueName . " - " . $Title . "</title>";		
	
} catch (Exception $e) {
STHSErrorIndividualLeaders:
	$LeagueName = $DatabaseNotFound;
	echo "<title>" . $DatabaseNotFound . "</title>";
	$Title = $DatabaseNotFound;
	echo "<style>.STHSIndividualLeaders_MainDiv{display:none}</style>";
}}?>
<style>
.HeadshotHide{display: none}
.STHSIndividualLeader_Table tbody tr:hover .HeadshotHide{display: inline;vertical-align:middle;}
.STHSIndividualLeader_Table tbody tr:hover {font-weight: 900;}
.STHSIndividualLeader_Table tbody tr:first-child .HeadshotHide{display: inline;vertical-align:middle;}
.STHSIndividualLeader_Table tbody:hover tr:first-child .HeadshotHide {display: none;}
.STHSIndividualLeader_Table tbody tr:first-child:hover .HeadshotHide {display: inline;}
.STHSIndividualLeader_Table tbody tr:hover td { vertical-align: middle !important;}
.STHSIndividualLeader_Table tbody tr:first-child {font-weight: 900;}
/* @media screen and (max-width: 1024px) {.STHSPHPIndividualLeadersHeadshot {display:none;}} */
.LeaderDiv{
    display: grid;
    grid-template-columns: repeat(3, 1fr);     gap: 10px;
}
.DivSection {
	flex-direction: column;
}
@media (max-width: 1400px) { .LeaderDiv {grid-template-columns: repeat(2, 1fr); }}
@media (max-width: 868px) { .LeaderDiv {grid-template-columns: repeat(1, 1fr); }}
<?php
If ($HistoryOutput == True){
	echo ".LeaderPlayerStatRookie, .LeaderPlayerStatGS, .LeaderPlayerStatPS {display:none;}";
}
?>
</style>
</head><body>
<?php include "Menu.php";?>
<div class="STHSLeader_MainDiv">
<?php echo "<h1>" . $Title . "</h1>"; 
If ($Year == 0){echo "<b>" . $TopMenuLang['MinimumGamesPlayed'] . $MinimumGamePlayer . "</b><br>";}

function GetPlayerQuery($HistoryOutput, $Stat, $TypeText, $LeagueGeneral,$PlayoffString,$MinimumGamePlayer) {
	If ($HistoryOutput == False){
		$Query = "SELECT Player" . $TypeText . "Stat." . $Stat . ", Player" . $TypeText . "Stat.GP, Player" . $TypeText . "Stat.Name, PlayerInfo.NHLID, PlayerInfo.PosC, PlayerInfo.PosLW, PlayerInfo.PosRW, PlayerInfo.PosD, Player" . $TypeText . "Stat.Number, Team" . $TypeText . "Info.Abbre, Team" . $TypeText . "Info.TeamThemeID FROM (PlayerInfo INNER JOIN Player" . $TypeText . "Stat ON PlayerInfo.Number = Player" . $TypeText . "Stat.Number) LEFT JOIN Team" . $TypeText . "Info ON PlayerInfo.Team = Team" . $TypeText . "Info.Number WHERE (Player" . $TypeText . "Stat.GP >= " . $MinimumGamePlayer. ") AND (PlayerInfo.Team > 0) AND (Player" . $TypeText . "Stat." . $Stat . " > 0) ORDER BY Player" . $TypeText . "Stat." . $Stat . " DESC, Player" . $TypeText . "Stat.GP ASC";
	}else{
		$Query = "SELECT MainTable.Number, MainTable.UniqueID, '0' As TeamThemeID, MainTable.Name, Sum(MainTable.GP) AS GP, Sum(MainTable." . $Stat . ") AS " . $Stat . "";
		if($LeagueGeneral['PlayOffStarted'] == $PlayoffString AND $LeagueGeneral['PreSeasonSchedule'] == "False"){
			/* Regular Query */
			$Query = $Query . " FROM (Select MainLive.* FROM (SELECT '". $LeagueGeneral['LeagueYearOutput'] . "' as Year, Player" . $TypeText . "Stat.Number, Player" . $TypeText . "Stat.UniqueID, '0' As TeamThemeID, Player" . $TypeText . "Stat.Name, Player" . $TypeText . "Stat.GP, Player" . $TypeText . "Stat." . $Stat . " FROM PlayerInfo INNER JOIN Player" . $TypeText . "Stat ON PlayerInfo.Number = Player" . $TypeText . "Stat.Number WHERE Player" . $TypeText . "Stat.GP > 10) AS MainLive UNION ALL Select MainHistory.* FROM (SELECT Player" . $TypeText . "StatHistory.Year, Player" . $TypeText . "StatHistory.Number, Player" . $TypeText . "StatHistory.UniqueID, '0' As TeamThemeID, Player" . $TypeText . "StatHistory.Name, Player" . $TypeText . "StatHistory.GP, Player" . $TypeText . "StatHistory." . $Stat . " FROM PlayerInfoHistory INNER JOIN Player" . $TypeText . "StatHistory ON PlayerInfoHistory.Number = Player" . $TypeText . "StatHistory.Number AND PlayerInfoHistory.Year = Player" . $TypeText . "StatHistory.Year AND PlayerInfoHistory.Playoff = Player" . $TypeText . "StatHistory.Playoff  WHERE Player" . $TypeText . "StatHistory.GP > 50 AND PlayerInfoHistory.Playoff = '" . $PlayoffString. "') AS MainHistory) AS MainTable";
		}else{
			/* Requesting Playoff While in Season or Requesting Season while in Playoff or In Pre-Season Mode - Do not fetch data from live database */
			$Query = $Query . " FROM (SELECT Player" . $TypeText . "StatHistory.Year, Player" . $TypeText . "StatHistory.Number, Player" . $TypeText . "StatHistory.UniqueID, '0' As TeamThemeID, Player" . $TypeText . "StatHistory.Name, Player" . $TypeText . "StatHistory.GP, Player" . $TypeText . "StatHistory." . $Stat . " FROM PlayerInfoHistory INNER JOIN Player" . $TypeText . "StatHistory ON PlayerInfoHistory.Number = Player" . $TypeText . "StatHistory.Number AND PlayerInfoHistory.Year = Player" . $TypeText . "StatHistory.Year AND PlayerInfoHistory.Playoff = Player" . $TypeText . "StatHistory.Playoff WHERE Player" . $TypeText . "StatHistory.GP > 0 AND PlayerInfoHistory.Playoff = '" . $PlayoffString. "') AS MainTable";
		}
		$Query = $Query . " WHERE " . $Stat . " > 0 GROUP BY UniqueID ORDER BY " . $Stat . " DESC, GP";
	}
	return $Query;
}

function GetPlayerQueryPositionHistory($Stat, $TypeText, $LeagueGeneral,$PlayoffString,$Pos) {
	$Query = "SELECT MainTable.Number, MainTable.UniqueID, '0' As TeamThemeID, MainTable.Name, Sum(MainTable.GP) AS GP, Sum(MainTable." . $Stat . ") AS " . $Stat . "";
	if($LeagueGeneral['PlayOffStarted'] == $PlayoffString AND $LeagueGeneral['PreSeasonSchedule'] == "False"){
		/* Regular Query */
		$Query = $Query . " FROM (Select MainLive.* FROM (SELECT '". $LeagueGeneral['LeagueYearOutput'] . "' as Year, Player" . $TypeText . "Stat.Number, Player" . $TypeText . "Stat.UniqueID, '0' As TeamThemeID, Player" . $TypeText . "Stat.Name, Player" . $TypeText . "Stat.GP, Player" . $TypeText . "Stat." . $Stat . " FROM PlayerInfo INNER JOIN Player" . $TypeText . "Stat ON PlayerInfo.Number = Player" . $TypeText . "Stat.Number WHERE Player" . $TypeText . "Stat.GP > 10 AND PlayerInfo.Pos" . $Pos . " = 'True') AS MainLive UNION ALL Select MainHistory.* FROM (SELECT Player" . $TypeText . "StatHistory.Year, Player" . $TypeText . "StatHistory.Number, Player" . $TypeText . "StatHistory.UniqueID, '0' As TeamThemeID, Player" . $TypeText . "StatHistory.Name, Player" . $TypeText . "StatHistory.GP, Player" . $TypeText . "StatHistory." . $Stat . " FROM PlayerInfoHistory INNER JOIN Player" . $TypeText . "StatHistory ON PlayerInfoHistory.Number = Player" . $TypeText . "StatHistory.Number AND PlayerInfoHistory.Year = Player" . $TypeText . "StatHistory.Year AND PlayerInfoHistory.Playoff = Player" . $TypeText . "StatHistory.Playoff  WHERE Player" . $TypeText . "StatHistory.GP > 50 AND PlayerInfoHistory.Playoff = '" . $PlayoffString. "' AND PlayerInfoHistory.Pos" . $Pos . " = 'True') AS MainHistory) AS MainTable";
	}else{
		/* Requesting Playoff While in Season or Requesting Season while in Playoff or In Pre-Season Mode - Do not fetch data from live database */
		$Query = $Query . " FROM (SELECT Player" . $TypeText . "StatHistory.Year, Player" . $TypeText . "StatHistory.Number, Player" . $TypeText . "StatHistory.UniqueID, '0' As TeamThemeID, Player" . $TypeText . "StatHistory.Name, Player" . $TypeText . "StatHistory.GP, Player" . $TypeText . "StatHistory." . $Stat . " FROM PlayerInfoHistory INNER JOIN Player" . $TypeText . "StatHistory ON PlayerInfoHistory.Number = Player" . $TypeText . "StatHistory.Number AND PlayerInfoHistory.Year = Player" . $TypeText . "StatHistory.Year AND PlayerInfoHistory.Playoff = Player" . $TypeText . "StatHistory.Playoff WHERE Player" . $TypeText . "StatHistory.GP > 0 AND PlayerInfoHistory.Playoff = '" . $PlayoffString. "' AND PlayerInfoHistory.Pos" . $Pos . " = 'True') AS MainTable";
	}
	$Query = $Query . " WHERE " . $Stat . " > 0 GROUP BY UniqueID ORDER BY " . $Stat . " DESC, GP";
	return $Query;
}

function GetGoalieQuery($HistoryOutput, $Stat, $TypeText, $LeagueGeneral,$PlayoffString,$MinimumGamePlayer) {
	If ($HistoryOutput == False){
		$Query = "SELECT Goaler" . $TypeText . "Stat." . $Stat . ", Goaler" . $TypeText . "Stat.GP, Goaler" . $TypeText . "Stat.SecondPlay, Goaler" . $TypeText . "Stat.Name, GoalerInfo.NHLID, Goaler" . $TypeText . "Stat.Number, Team" . $TypeText . "Info.Abbre, Team" . $TypeText . "Info.TeamThemeID FROM (GoalerInfo INNER JOIN Goaler" . $TypeText . "Stat ON GoalerInfo.Number = Goaler" . $TypeText . "Stat.Number) LEFT JOIN Team" . $TypeText . "Info ON GoalerInfo.Team = Team" . $TypeText . "Info.Number WHERE (Goaler" . $TypeText . "Stat.SecondPlay >= (" . $MinimumGamePlayer . "*3600)) AND (GoalerInfo.Team > 0) AND (Goaler" . $TypeText . "Stat.L > 0) ORDER BY Goaler" . $TypeText . "Stat." . $Stat . " DESC, Goaler" . $TypeText . "Stat.GP ASC";		
	}else{
		$Query = "SELECT MainTable.Number, MainTable.UniqueID, '0' As TeamThemeID, MainTable.Name, Sum(MainTable.GP) AS GP, Sum(MainTable." . $Stat . ") AS " . $Stat . "";
		if($LeagueGeneral['PlayOffStarted'] == $PlayoffString AND $LeagueGeneral['PreSeasonSchedule'] == "False"){
			/* Regular Query */
			$Query = $Query . " FROM (Select MainLive.* FROM (SELECT '". $LeagueGeneral['LeagueYearOutput'] . "' as Year, Goaler" . $TypeText . "Stat.Number, Goaler" . $TypeText . "Stat.UniqueID, '0' As TeamThemeID, Goaler" . $TypeText . "Stat.Name, Goaler" . $TypeText . "Stat.GP, Goaler" . $TypeText . "Stat." . $Stat . " FROM GoalerInfo INNER JOIN Goaler" . $TypeText . "Stat ON GoalerInfo.Number = Goaler" . $TypeText . "Stat.Number WHERE Goaler" . $TypeText . "Stat.GP > 10) AS MainLive UNION ALL Select MainHistory.* FROM (SELECT Goaler" . $TypeText . "StatHistory.Year, Goaler" . $TypeText . "StatHistory.Number, Goaler" . $TypeText . "StatHistory.UniqueID, '0' As TeamThemeID, Goaler" . $TypeText . "StatHistory.Name, Goaler" . $TypeText . "StatHistory.GP, Goaler" . $TypeText . "StatHistory." . $Stat . " FROM GoalerInfoHistory INNER JOIN Goaler" . $TypeText . "StatHistory ON GoalerInfoHistory.Number = Goaler" . $TypeText . "StatHistory.Number AND GoalerInfoHistory.Year = Goaler" . $TypeText . "StatHistory.Year AND GoalerInfoHistory.Playoff = Goaler" . $TypeText . "StatHistory.Playoff  WHERE Goaler" . $TypeText . "StatHistory.GP > 50 AND GoalerInfoHistory.Playoff = '" . $PlayoffString. "') AS MainHistory) AS MainTable";
		}else{
			/* Requesting Playoff While in Season or Requesting Season while in Playoff or In Pre-Season Mode - Do not fetch data from live database */
			$Query = $Query . " FROM (SELECT Goaler" . $TypeText . "StatHistory.Year, Goaler" . $TypeText . "StatHistory.Number, Goaler" . $TypeText . "StatHistory.UniqueID, '0' As TeamThemeID, Goaler" . $TypeText . "StatHistory.Name, Goaler" . $TypeText . "StatHistory.GP, Goaler" . $TypeText . "StatHistory." . $Stat . " FROM GoalerInfoHistory INNER JOIN Goaler" . $TypeText . "StatHistory ON GoalerInfoHistory.Number = Goaler" . $TypeText . "StatHistory.Number AND GoalerInfoHistory.Year = Goaler" . $TypeText . "StatHistory.Year AND GoalerInfoHistory.Playoff = Goaler" . $TypeText . "StatHistory.Playoff WHERE Goaler" . $TypeText . "StatHistory.GP > 0 AND GoalerInfoHistory.Playoff = '" . $PlayoffString. "') AS MainTable";
		}
		$Query = $Query . " WHERE " . $Stat . " > 0 GROUP BY UniqueID ORDER BY " . $Stat . " DESC, GP";
	}
	return $Query;
}

function GetPlayerQueryFull($OrderByField, $TypeText, $LeagueGeneral,$PlayoffString, $Order = " DESC") {
	/* Copy from PlayersStat */
	$Query = "SELECT MainTable.Number, MainTable.UniqueID, '0' As TeamThemeID, MainTable.Name, MainTable.Team, MainTable.TeamName, Sum(MainTable.GP) AS GP, Sum(MainTable.Shots) AS Shots, Sum(MainTable.G) AS G, Sum(MainTable.A) AS A, Sum(MainTable.P) AS P, Sum(MainTable.PlusMinus) AS PlusMinus, Sum(MainTable.Pim) AS Pim, Sum(MainTable.Pim5) AS Pim5, Sum(MainTable.ShotsBlock) AS ShotsBlock, Sum(MainTable.OwnShotsBlock) AS OwnShotsBlock, Sum(MainTable.OwnShotsMissGoal) AS OwnShotsMissGoal, Sum(MainTable.Hits) AS Hits, Sum(MainTable.HitsTook) AS HitsTook, Sum(MainTable.GW) AS GW, Sum(MainTable.GT) AS GT, Sum(MainTable.FaceOffWon) AS FaceOffWon, Sum(MainTable.FaceOffTotal) AS FaceOffTotal, Sum(MainTable.PenalityShotsScore) AS PenalityShotsScore, Sum(MainTable.PenalityShotsTotal) AS PenalityShotsTotal, Sum(MainTable.EmptyNetGoal) AS EmptyNetGoal, Sum(MainTable.SecondPlay) AS SecondPlay, Sum(MainTable.HatTrick) AS HatTrick, Sum(MainTable.PPG) AS PPG, Sum(MainTable.PPA) AS PPA, Sum(MainTable.PPP) AS PPP, Sum(MainTable.PPShots) AS PPShots, Sum(MainTable.PPSecondPlay) AS PPSecondPlay, Sum(MainTable.PKG) AS PKG, Sum(MainTable.PKA) AS PKA, Sum(MainTable.PKP) AS PKP, Sum(MainTable.PKShots) AS PKShots, Sum(MainTable.PKSecondPlay) AS PKSecondPlay, Sum(MainTable.GiveAway) AS GiveAway, Sum(MainTable.TakeAway) AS TakeAway, Sum(MainTable.PuckPossesionTime) AS PuckPossesionTime, Sum(MainTable.FightW) AS FightW, Sum(MainTable.FightL) AS FightL, Sum(MainTable.FightT) AS FightT, Sum(MainTable.Star1) AS Star1, Sum(MainTable.Star2) AS Star2, Sum(MainTable.Star3) AS Star3,  ROUND((CAST(Sum(MainTable.G) AS REAL) / (Sum(MainTable.Shots)))*100,2) AS ShotsPCT, ROUND((CAST(Sum(MainTable.SecondPlay) AS REAL) / 60 / (Sum(MainTable.GP))),2) AS AMG,ROUND((CAST(Sum(MainTable.FaceOffWon) AS REAL) / (Sum(MainTable.FaceOffTotal)))*100,2) as FaceoffPCT,ROUND((CAST(Sum(MainTable.P) AS REAL) / (Sum(MainTable.SecondPlay)) * 60 * 20),2) AS P20";
	$Query = $Query . ", (Sum(MainTable.FightW) + Sum(MainTable.FightT) + Sum(MainTable.FightL)) AS TotalFight";
	
	if($LeagueGeneral['PlayOffStarted'] == $PlayoffString AND $LeagueGeneral['PreSeasonSchedule'] == "False"){
		/* Regular Query */
		$Query = $Query . " FROM (Select MainLive.* FROM (SELECT '". $LeagueGeneral['LeagueYearOutput'] . "' as Year, Player" . $TypeText . "Stat.Number, Player" . $TypeText . "Stat.UniqueID, '0' As TeamThemeID, Player" . $TypeText . "Stat.Name, Player" . $TypeText . "Stat.GP, Player" . $TypeText . "Stat.Shots, Player" . $TypeText . "Stat.G, Player" . $TypeText . "Stat.A, Player" . $TypeText . "Stat.P, Player" . $TypeText . "Stat.PlusMinus, Player" . $TypeText . "Stat.Pim, Player" . $TypeText . "Stat.Pim5, Player" . $TypeText . "Stat.ShotsBlock, Player" . $TypeText . "Stat.OwnShotsBlock, Player" . $TypeText . "Stat.OwnShotsMissGoal, Player" . $TypeText . "Stat.Hits, Player" . $TypeText . "Stat.HitsTook, Player" . $TypeText . "Stat.GW, Player" . $TypeText . "Stat.GT, Player" . $TypeText . "Stat.FaceOffWon, Player" . $TypeText . "Stat.FaceOffTotal, Player" . $TypeText . "Stat.PenalityShotsScore, Player" . $TypeText . "Stat.PenalityShotsTotal, Player" . $TypeText . "Stat.EmptyNetGoal, Player" . $TypeText . "Stat.SecondPlay, Player" . $TypeText . "Stat.HatTrick, Player" . $TypeText . "Stat.PPG, Player" . $TypeText . "Stat.PPA, Player" . $TypeText . "Stat.PPP, Player" . $TypeText . "Stat.PPShots, Player" . $TypeText . "Stat.PPSecondPlay, Player" . $TypeText . "Stat.PKG, Player" . $TypeText . "Stat.PKA, Player" . $TypeText . "Stat.PKP, Player" . $TypeText . "Stat.PKShots, Player" . $TypeText . "Stat.PKSecondPlay, Player" . $TypeText . "Stat.GiveAway, Player" . $TypeText . "Stat.TakeAway, Player" . $TypeText . "Stat.PuckPossesionTime, Player" . $TypeText . "Stat.FightW, Player" . $TypeText . "Stat.FightL, Player" . $TypeText . "Stat.FightT, Player" . $TypeText . "Stat.Star1, Player" . $TypeText . "Stat.Star2, Player" . $TypeText . "Stat.Star3, PlayerInfo.PosC, PlayerInfo.PosLW, PlayerInfo.PosRW, PlayerInfo.PosD, PlayerInfo.Team, PlayerInfo.TeamName, PlayerInfo.Rookie, ROUND((CAST(Player" . $TypeText . "Stat.G AS REAL) / (Player" . $TypeText . "Stat.Shots))*100,2) AS ShotsPCT, ROUND((CAST(Player" . $TypeText . "Stat.SecondPlay AS REAL) / 60 / (Player" . $TypeText . "Stat.GP)),2) AS AMG,ROUND((CAST(Player" . $TypeText . "Stat.FaceOffWon AS REAL) / (Player" . $TypeText . "Stat.FaceOffTotal))*100,2) as FaceoffPCT,ROUND((CAST(Player" . $TypeText . "Stat.P AS REAL) / (Player" . $TypeText . "Stat.SecondPlay) * 60 * 20),2) AS P20 FROM PlayerInfo INNER JOIN Player" . $TypeText . "Stat ON PlayerInfo.Number = Player" . $TypeText . "Stat.Number WHERE Player" . $TypeText . "Stat.GP > 10) AS MainLive UNION ALL Select MainHistory.* FROM (SELECT Player" . $TypeText . "StatHistory.Year, Player" . $TypeText . "StatHistory.Number, Player" . $TypeText . "StatHistory.UniqueID, '0' As TeamThemeID, Player" . $TypeText . "StatHistory.Name, Player" . $TypeText . "StatHistory.GP, Player" . $TypeText . "StatHistory.Shots, Player" . $TypeText . "StatHistory.G, Player" . $TypeText . "StatHistory.A, Player" . $TypeText . "StatHistory.P, Player" . $TypeText . "StatHistory.PlusMinus, Player" . $TypeText . "StatHistory.Pim, Player" . $TypeText . "StatHistory.Pim5, Player" . $TypeText . "StatHistory.ShotsBlock, Player" . $TypeText . "StatHistory.OwnShotsBlock, Player" . $TypeText . "StatHistory.OwnShotsMissGoal, Player" . $TypeText . "StatHistory.Hits, Player" . $TypeText . "StatHistory.HitsTook, Player" . $TypeText . "StatHistory.GW, Player" . $TypeText . "StatHistory.GT, Player" . $TypeText . "StatHistory.FaceOffWon, Player" . $TypeText . "StatHistory.FaceOffTotal, Player" . $TypeText . "StatHistory.PenalityShotsScore, Player" . $TypeText . "StatHistory.PenalityShotsTotal, Player" . $TypeText . "StatHistory.EmptyNetGoal, Player" . $TypeText . "StatHistory.SecondPlay, Player" . $TypeText . "StatHistory.HatTrick, Player" . $TypeText . "StatHistory.PPG, Player" . $TypeText . "StatHistory.PPA, Player" . $TypeText . "StatHistory.PPP, Player" . $TypeText . "StatHistory.PPShots, Player" . $TypeText . "StatHistory.PPSecondPlay, Player" . $TypeText . "StatHistory.PKG, Player" . $TypeText . "StatHistory.PKA, Player" . $TypeText . "StatHistory.PKP, Player" . $TypeText . "StatHistory.PKShots, Player" . $TypeText . "StatHistory.PKSecondPlay, Player" . $TypeText . "StatHistory.GiveAway, Player" . $TypeText . "StatHistory.TakeAway, Player" . $TypeText . "StatHistory.PuckPossesionTime, Player" . $TypeText . "StatHistory.FightW, Player" . $TypeText . "StatHistory.FightL, Player" . $TypeText . "StatHistory.FightT, Player" . $TypeText . "StatHistory.Star1, Player" . $TypeText . "StatHistory.Star2, Player" . $TypeText . "StatHistory.Star3, PlayerInfoHistory.PosC, PlayerInfoHistory.PosLW, PlayerInfoHistory.PosRW, PlayerInfoHistory.PosD, PlayerInfoHistory.Team, PlayerInfoHistory.ProTeamName AS TeamName, PlayerInfoHistory.Rookie,  ROUND((CAST(Player" . $TypeText . "StatHistory.G AS REAL) / (Player" . $TypeText . "StatHistory.Shots))*100,2) AS ShotsPCT, ROUND((CAST(Player" . $TypeText . "StatHistory.SecondPlay AS REAL) / 60 / (Player" . $TypeText . "StatHistory.GP)),2) AS AMG,ROUND((CAST(Player" . $TypeText . "StatHistory.FaceOffWon AS REAL) / (Player" . $TypeText . "StatHistory.FaceOffTotal))*100,2) as FaceoffPCT,ROUND((CAST(Player" . $TypeText . "StatHistory.P AS REAL) / (Player" . $TypeText . "StatHistory.SecondPlay) * 60 * 20),2) AS P20 FROM PlayerInfoHistory INNER JOIN Player" . $TypeText . "StatHistory ON PlayerInfoHistory.Number = Player" . $TypeText . "StatHistory.Number AND PlayerInfoHistory.Year = Player" . $TypeText . "StatHistory.Year AND PlayerInfoHistory.Playoff = Player" . $TypeText . "StatHistory.Playoff  WHERE Player" . $TypeText . "StatHistory.GP > 50 AND PlayerInfoHistory.Playoff = '" . $PlayoffString. "') AS MainHistory) AS MainTable";
	}else{
		/* Requesting Playoff While in Season or Requesting Season while in Playoff or In Pre-Season Mode - Do not fetch data from live database */
		$Query = $Query . " FROM (SELECT Player" . $TypeText . "StatHistory.Year, Player" . $TypeText . "StatHistory.Number, Player" . $TypeText . "StatHistory.UniqueID, '0' As TeamThemeID, Player" . $TypeText . "StatHistory.Name, Player" . $TypeText . "StatHistory.GP, Player" . $TypeText . "StatHistory.Shots, Player" . $TypeText . "StatHistory.G, Player" . $TypeText . "StatHistory.A, Player" . $TypeText . "StatHistory.P, Player" . $TypeText . "StatHistory.PlusMinus, Player" . $TypeText . "StatHistory.Pim, Player" . $TypeText . "StatHistory.Pim5, Player" . $TypeText . "StatHistory.ShotsBlock, Player" . $TypeText . "StatHistory.OwnShotsBlock, Player" . $TypeText . "StatHistory.OwnShotsMissGoal, Player" . $TypeText . "StatHistory.Hits, Player" . $TypeText . "StatHistory.HitsTook, Player" . $TypeText . "StatHistory.GW, Player" . $TypeText . "StatHistory.GT, Player" . $TypeText . "StatHistory.FaceOffWon, Player" . $TypeText . "StatHistory.FaceOffTotal, Player" . $TypeText . "StatHistory.PenalityShotsScore, Player" . $TypeText . "StatHistory.PenalityShotsTotal, Player" . $TypeText . "StatHistory.EmptyNetGoal, Player" . $TypeText . "StatHistory.SecondPlay, Player" . $TypeText . "StatHistory.HatTrick, Player" . $TypeText . "StatHistory.PPG, Player" . $TypeText . "StatHistory.PPA, Player" . $TypeText . "StatHistory.PPP, Player" . $TypeText . "StatHistory.PPShots, Player" . $TypeText . "StatHistory.PPSecondPlay, Player" . $TypeText . "StatHistory.PKG, Player" . $TypeText . "StatHistory.PKA, Player" . $TypeText . "StatHistory.PKP, Player" . $TypeText . "StatHistory.PKShots, Player" . $TypeText . "StatHistory.PKSecondPlay, Player" . $TypeText . "StatHistory.GiveAway, Player" . $TypeText . "StatHistory.TakeAway, Player" . $TypeText . "StatHistory.PuckPossesionTime, Player" . $TypeText . "StatHistory.FightW, Player" . $TypeText . "StatHistory.FightL, Player" . $TypeText . "StatHistory.FightT, Player" . $TypeText . "StatHistory.Star1, Player" . $TypeText . "StatHistory.Star2, Player" . $TypeText . "StatHistory.Star3, PlayerInfoHistory.PosC, PlayerInfoHistory.PosLW, PlayerInfoHistory.PosRW, PlayerInfoHistory.PosD, PlayerInfoHistory.Team, PlayerInfoHistory.ProTeamName AS TeamName, PlayerInfoHistory.Rookie, ROUND((CAST(Player" . $TypeText . "StatHistory.G AS REAL) / (Player" . $TypeText . "StatHistory.Shots))*100,2) AS ShotsPCT, ROUND((CAST(Player" . $TypeText . "StatHistory.SecondPlay AS REAL) / 60 / (Player" . $TypeText . "StatHistory.GP)),2) AS AMG,ROUND((CAST(Player" . $TypeText . "StatHistory.FaceOffWon AS REAL) / (Player" . $TypeText . "StatHistory.FaceOffTotal))*100,2) as FaceoffPCT,ROUND((CAST(Player" . $TypeText . "StatHistory.P AS REAL) / (Player" . $TypeText . "StatHistory.SecondPlay) * 60 * 20),2) AS P20 FROM PlayerInfoHistory INNER JOIN Player" . $TypeText . "StatHistory ON PlayerInfoHistory.Number = Player" . $TypeText . "StatHistory.Number AND PlayerInfoHistory.Year = Player" . $TypeText . "StatHistory.Year AND PlayerInfoHistory.Playoff = Player" . $TypeText . "StatHistory.Playoff WHERE Player" . $TypeText . "StatHistory.GP > 0 AND PlayerInfoHistory.Playoff = '" . $PlayoffString. "') AS MainTable";
	}
	If ($OrderByField == "FaceoffPCT"){$Query = $Query . " WHERE FaceOffTotal > 100";}
	$Query = $Query . " GROUP BY UniqueID ORDER BY " . $OrderByField . $Order;
	return $Query;
}

function GetGoalieQueryFull($OrderByField, $TypeText, $LeagueGeneral,$PlayoffString, $Order = " DESC") {
	/* Copy from GoaliesStat */
	$Query = "SELECT MainTable.Number, MainTable.UniqueID, '0' As TeamThemeID, MainTable.Name, MainTable.Team, MainTable.TeamName, Sum(MainTable.GP) AS GP, Sum(MainTable.SecondPlay) AS SecondPlay, Sum(MainTable.W) AS W, Sum(MainTable.L) AS L, Sum(MainTable.OTL) AS OTL, Sum(MainTable.Shootout) AS Shootout, Sum(MainTable.GA) AS GA, Sum(MainTable.SA) AS SA, Sum(MainTable.SARebound) AS SARebound, Sum(MainTable.Pim) AS Pim, Sum(MainTable.A) AS A, Sum(MainTable.PenalityShotsShots) AS PenalityShotsShots, Sum(MainTable.PenalityShotsGoals) AS PenalityShotsGoals, Sum(MainTable.StartGoaler) AS StartGoaler, Sum(MainTable.BackupGoaler) AS BackupGoaler, Sum(MainTable.EmptyNetGoal) AS EmptyNetGoal, Sum(MainTable.Star1) AS Star1, Sum(MainTable.Star2) AS Star2, Sum(MainTable.Star3) AS Star3, ROUND((CAST(Sum(MainTable.GA) AS REAL) / (Sum(MainTable.SecondPlay) / 60))*60, 3) AS GAA, ROUND((CAST(Sum(MainTable.SA) - Sum(MainTable.GA) AS REAL) / (Sum(MainTable.SA))), 3) AS PCT, ROUND((CAST(Sum(MainTable.PenalityShotsShots) - Sum(MainTable.PenalityShotsGoals) AS REAL) / (Sum(MainTable.PenalityShotsShots))), 3) AS PenalityShotsPCT";
	if($LeagueGeneral['PlayOffStarted'] == $PlayoffString AND $LeagueGeneral['PreSeasonSchedule'] == "False"){
		/* Regular Query */	
		$Query = $Query . " FROM ( Select MainLive.* FROM (SELECT '". $LeagueGeneral['LeagueYearOutput'] . "' as Year, GoalerInfo.TeamName, GoalerInfo.Team, GoalerInfo.Rookie, '0' As TeamThemeID, Goaler" . $TypeText . "Stat.Number, Goaler" . $TypeText . "Stat.UniqueID, Goaler" . $TypeText . "Stat.Name, Goaler" . $TypeText . "Stat.GP, Goaler" . $TypeText . "Stat.SecondPlay, Goaler" . $TypeText . "Stat.W, Goaler" . $TypeText . "Stat.L, Goaler" . $TypeText . "Stat.OTL, Goaler" . $TypeText . "Stat.Shootout, Goaler" . $TypeText . "Stat.GA, Goaler" . $TypeText . "Stat.SA, Goaler" . $TypeText . "Stat.SARebound, Goaler" . $TypeText . "Stat.Pim, Goaler" . $TypeText . "Stat.A, Goaler" . $TypeText . "Stat.PenalityShotsShots, Goaler" . $TypeText . "Stat.PenalityShotsGoals, Goaler" . $TypeText . "Stat.StartGoaler, Goaler" . $TypeText . "Stat.BackupGoaler, Goaler" . $TypeText . "Stat.EmptyNetGoal, Goaler" . $TypeText . "Stat.Star1, Goaler" . $TypeText . "Stat.Star2, Goaler" . $TypeText . "Stat.Star3, ROUND((CAST(Goaler" . $TypeText . "Stat.GA AS REAL) / (Goaler" . $TypeText . "Stat.SecondPlay / 60))*60, 3) AS GAA,  ROUND((CAST(Goaler" . $TypeText . "Stat.SA - Goaler" . $TypeText . "Stat.GA AS REAL) / (Goaler" . $TypeText . "Stat.SA)), 3) AS PCT,  ROUND((CAST(Goaler" . $TypeText . "Stat.PenalityShotsShots - Goaler" . $TypeText . "Stat.PenalityShotsGoals AS REAL) / (Goaler" . $TypeText . "Stat.PenalityShotsShots)), 3) AS PenalityShotsPCT FROM GoalerInfo INNER JOIN Goaler" . $TypeText . "Stat ON GoalerInfo.Number = Goaler" . $TypeText . "Stat.Number WHERE Goaler" . $TypeText . "Stat.GP > 10 ) AS MainLive UNION ALL Select MainHistory.* FROM (SELECT Goaler" . $TypeText . "StatHistory.Year, GoalerInfoHistory.ProTeamName AS TeamName,  GoalerInfoHistory.Team, GoalerInfoHistory.Rookie, '0' As TeamThemeID, Goaler" . $TypeText . "StatHistory.Number, Goaler" . $TypeText . "StatHistory.UniqueID, Goaler" . $TypeText . "StatHistory.Name, Goaler" . $TypeText . "StatHistory.GP, Goaler" . $TypeText . "StatHistory.SecondPlay, Goaler" . $TypeText . "StatHistory.W, Goaler" . $TypeText . "StatHistory.L, Goaler" . $TypeText . "StatHistory.OTL, Goaler" . $TypeText . "StatHistory.Shootout, Goaler" . $TypeText . "StatHistory.GA, Goaler" . $TypeText . "StatHistory.SA, Goaler" . $TypeText . "StatHistory.SARebound, Goaler" . $TypeText . "StatHistory.Pim, Goaler" . $TypeText . "StatHistory.A, Goaler" . $TypeText . "StatHistory.PenalityShotsShots, Goaler" . $TypeText . "StatHistory.PenalityShotsGoals, Goaler" . $TypeText . "StatHistory.StartGoaler, Goaler" . $TypeText . "StatHistory.BackupGoaler, Goaler" . $TypeText . "StatHistory.EmptyNetGoal, Goaler" . $TypeText . "StatHistory.Star1, Goaler" . $TypeText . "StatHistory.Star2, Goaler" . $TypeText . "StatHistory.Star3, ROUND((CAST(Goaler" . $TypeText . "StatHistory.GA AS REAL) / (Goaler" . $TypeText . "StatHistory.SecondPlay / 60))*60,3) AS GAA, ROUND((CAST(Goaler" . $TypeText . "StatHistory.SA - Goaler" . $TypeText . "StatHistory.GA AS REAL) / (Goaler" . $TypeText . "StatHistory.SA)),3) AS PCT, ROUND((CAST(Goaler" . $TypeText . "StatHistory.PenalityShotsShots - Goaler" . $TypeText . "StatHistory.PenalityShotsGoals AS REAL) / (Goaler" . $TypeText . "StatHistory.PenalityShotsShots)),3) AS PenalityShotsPCT FROM GoalerInfoHistory INNER JOIN Goaler" . $TypeText . "StatHistory ON GoalerInfoHistory.Number = Goaler" . $TypeText . "StatHistory.Number AND GoalerInfoHistory.Year = Goaler" . $TypeText . "StatHistory.Year AND GoalerInfoHistory.Playoff = Goaler" . $TypeText . "StatHistory.Playoff WHERE Goaler" . $TypeText . "StatHistory.GP > 50 AND GoalerInfoHistory.Playoff = '" . $PlayoffString. "') AS MainHistory) AS MainTable";
	}else{
		/* Requesting Playoff While in Season or Requesting Season while in Playoff or In Pre-Season Mode - Do not fetch data from live database */
		$Query = $Query . " FROM (SELECT Goaler" . $TypeText . "StatHistory.Year, GoalerInfoHistory.ProTeamName AS TeamName,  GoalerInfoHistory.Team, GoalerInfoHistory.Rookie, '0' As TeamThemeID, Goaler" . $TypeText . "StatHistory.Number, Goaler" . $TypeText . "StatHistory.UniqueID, Goaler" . $TypeText . "StatHistory.Name, Goaler" . $TypeText . "StatHistory.GP, Goaler" . $TypeText . "StatHistory.SecondPlay, Goaler" . $TypeText . "StatHistory.W, Goaler" . $TypeText . "StatHistory.L, Goaler" . $TypeText . "StatHistory.OTL, Goaler" . $TypeText . "StatHistory.Shootout, Goaler" . $TypeText . "StatHistory.GA, Goaler" . $TypeText . "StatHistory.SA, Goaler" . $TypeText . "StatHistory.SARebound, Goaler" . $TypeText . "StatHistory.Pim, Goaler" . $TypeText . "StatHistory.A, Goaler" . $TypeText . "StatHistory.PenalityShotsShots, Goaler" . $TypeText . "StatHistory.PenalityShotsGoals, Goaler" . $TypeText . "StatHistory.StartGoaler, Goaler" . $TypeText . "StatHistory.BackupGoaler, Goaler" . $TypeText . "StatHistory.EmptyNetGoal, Goaler" . $TypeText . "StatHistory.Star1, Goaler" . $TypeText . "StatHistory.Star2, Goaler" . $TypeText . "StatHistory.Star3, ROUND((CAST(Goaler" . $TypeText . "StatHistory.GA AS REAL) / (Goaler" . $TypeText . "StatHistory.SecondPlay / 60))*60,3) AS GAA, ROUND((CAST(Goaler" . $TypeText . "StatHistory.SA - Goaler" . $TypeText . "StatHistory.GA AS REAL) / (Goaler" . $TypeText . "StatHistory.SA)),3) AS PCT, ROUND((CAST(Goaler" . $TypeText . "StatHistory.PenalityShotsShots - Goaler" . $TypeText . "StatHistory.PenalityShotsGoals AS REAL) / (Goaler" . $TypeText . "StatHistory.PenalityShotsShots)),3) AS PenalityShotsPCT FROM GoalerInfoHistory INNER JOIN Goaler" . $TypeText . "StatHistory ON GoalerInfoHistory.Number = Goaler" . $TypeText . "StatHistory.Number AND GoalerInfoHistory.Year = Goaler" . $TypeText . "StatHistory.Year AND GoalerInfoHistory.Playoff = Goaler" . $TypeText . "StatHistory.Playoff WHERE Goaler" . $TypeText . "StatHistory.GP > 0 AND GoalerInfoHistory.Playoff = '" . $PlayoffString. "') AS MainTable";
	}
	$Query = $Query . " GROUP BY UniqueID ORDER BY " .$OrderByField . $Order;
	return $Query;
}
?>

<h1 class="STHSProIndividualLeader_Players STHSCenter"><?php echo $DynamicTitleLang['Players'];?></h1>

<div class="LeaderDiv ">

<div class="DivSection LeaderPlayerStatP">
<table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false" style="text-align:center;"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['Points'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Points">P</th></tr></thead>
<?php
$Query = GetPlayerQuery($HistoryOutput, "P",$TypeText,$LeagueGeneral,$PlayoffString,$MinimumGamePlayer);
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1; $Position = (string)"";	if ($Row['PosC']== "True"){if ($Position == ""){$Position = "C";}else{$Position = $Position . "/C";}}if ($Row['PosLW']== "True"){if ($Position == ""){$Position = "LW";}else{$Position = $Position . "/LW";}}if ($Row['PosRW']== "True"){if ($Position == ""){$Position = "RW";}else{$Position = $Position . "/RW";}}if ($Row['PosD']== "True"){if ($Position == ""){$Position = "D";}else{$Position = $Position . "/D";}}
	
	echo "<tr><td>" . $LoopCount . "</td><td>";
	If ($Row['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPIndividualLeadersTeamImage\">";}
	If ($HistoryOutput == False){
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " - " . $Position . " (" . $Row['Abbre'] . ")";		
		If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $Row['NHLID'] != ""){
		echo "<div class=\"HeadshotHide\"><img loading=\"lazy\" src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $Row['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $Row['Name']. "\" class=\"STHSPHPIndividualLeadersHeadshot\" /></div>";}
	}else{
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'];		
	}
	echo "</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['P'] .  "</td></tr>\n";
}}
If ($LoopCount > 1){
	echo "</table>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table>";
}?>
</div>

<div class="DivSection LeaderPlayerStatG">
<table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false" style="text-align:center;"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['Goals'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Goals">G</th></tr></thead>
<?php
$Query = GetPlayerQuery($HistoryOutput, "G",$TypeText,$LeagueGeneral,$PlayoffString,$MinimumGamePlayer);
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1; $Position = (string)"";	if ($Row['PosC']== "True"){if ($Position == ""){$Position = "C";}else{$Position = $Position . "/C";}}if ($Row['PosLW']== "True"){if ($Position == ""){$Position = "LW";}else{$Position = $Position . "/LW";}}if ($Row['PosRW']== "True"){if ($Position == ""){$Position = "RW";}else{$Position = $Position . "/RW";}}if ($Row['PosD']== "True"){if ($Position == ""){$Position = "D";}else{$Position = $Position . "/D";}}
	
	echo "<tr><td>" . $LoopCount . "</td><td>";
	If ($Row['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPIndividualLeadersTeamImage\">";}
	If ($HistoryOutput == False){
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " - " . $Position . " (" . $Row['Abbre'] . ")";		
		If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $Row['NHLID'] != ""){
		echo "<div class=\"HeadshotHide\"><img loading=\"lazy\" src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $Row['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $Row['Name']. "\" class=\"STHSPHPIndividualLeadersHeadshot\" /></div>";}
	}else{
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'];		
	}
	echo "</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['G'] .  "</td></tr>\n";
}}
If ($LoopCount > 1){
	echo "</table>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table>";
}?>
</div>
<div class="DivSection LeaderPlayerStatA">
<table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false" style="text-align:center;"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['Assists'];?>
</span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Assists">A</th></tr></thead>
<?php
$Query = GetPlayerQuery($HistoryOutput, "A",$TypeText,$LeagueGeneral,$PlayoffString,$MinimumGamePlayer);
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1; $Position = (string)"";	if ($Row['PosC']== "True"){if ($Position == ""){$Position = "C";}else{$Position = $Position . "/C";}}if ($Row['PosLW']== "True"){if ($Position == ""){$Position = "LW";}else{$Position = $Position . "/LW";}}if ($Row['PosRW']== "True"){if ($Position == ""){$Position = "RW";}else{$Position = $Position . "/RW";}}if ($Row['PosD']== "True"){if ($Position == ""){$Position = "D";}else{$Position = $Position . "/D";}}
		
	echo "<tr><td>" . $LoopCount . "</td><td>";
	If ($Row['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPIndividualLeadersTeamImage\">";}
	If ($HistoryOutput == False){
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " - " . $Position . " (" . $Row['Abbre'] . ")";		
		If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $Row['NHLID'] != ""){
		echo "<div class=\"HeadshotHide\"><img loading=\"lazy\" src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $Row['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $Row['Name']. "\" class=\"STHSPHPIndividualLeadersHeadshot\" /></div>";}
	}else{
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'];		
	}
	echo "</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['A'] .  "</td></tr>\n";
	
}}
If ($LoopCount > 1){
	echo "</table>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table>";
}?>
</div>
<div class="DivSection LeaderPlayerStatSHT">
<table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false" style="text-align:center;"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['Shots'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Shots">SHT</th></tr></thead>
<?php
$Query = GetPlayerQuery($HistoryOutput, "Shots",$TypeText,$LeagueGeneral,$PlayoffString,$MinimumGamePlayer);
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1; $Position = (string)"";	if ($Row['PosC']== "True"){if ($Position == ""){$Position = "C";}else{$Position = $Position . "/C";}}if ($Row['PosLW']== "True"){if ($Position == ""){$Position = "LW";}else{$Position = $Position . "/LW";}}if ($Row['PosRW']== "True"){if ($Position == ""){$Position = "RW";}else{$Position = $Position . "/RW";}}if ($Row['PosD']== "True"){if ($Position == ""){$Position = "D";}else{$Position = $Position . "/D";}}
		
	echo "<tr><td>" . $LoopCount . "</td><td>";
	If ($Row['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPIndividualLeadersTeamImage\">";}
	If ($HistoryOutput == False){
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " - " . $Position . " (" . $Row['Abbre'] . ")";		
		If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $Row['NHLID'] != ""){
		echo "<div class=\"HeadshotHide\"><img loading=\"lazy\" src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $Row['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $Row['Name']. "\" class=\"STHSPHPIndividualLeadersHeadshot\" /></div>";}
	}else{
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'];		
	}
	echo "</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['Shots'] .  "</td></tr>\n";	
		
}}
If ($LoopCount > 1){
	echo "</table>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table>";
}?>
</div>
<div class="DivSection LeaderPlayerStatSHTPCT">
<table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false" style="text-align:center;"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['ShotsPCT'];?>
</span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Shooting Percentage">SHT%</th></tr></thead>
<?php
If ($HistoryOutput == False){
	$Query = "SELECT ROUND((CAST(Player" . $TypeText . "Stat.G AS REAL) / (Player" . $TypeText . "Stat.Shots))*100,2) AS ShotsPCT, Player" . $TypeText . "Stat.GP, Player" . $TypeText . "Stat.Name, PlayerInfo.NHLID, PlayerInfo.PosC, PlayerInfo.PosLW, PlayerInfo.PosRW, PlayerInfo.PosD, Player" . $TypeText . "Stat.Number, Team" . $TypeText . "Info.Abbre, Team" . $TypeText . "Info.TeamThemeID FROM (PlayerInfo INNER JOIN Player" . $TypeText . "Stat ON PlayerInfo.Number = Player" . $TypeText . "Stat.Number) LEFT JOIN Team" . $TypeText . "Info ON PlayerInfo.Team = Team" . $TypeText . "Info.Number WHERE (Player" . $TypeText . "Stat.GP >= " . $MinimumGamePlayer. ") AND (Player" . $TypeText . "Stat.Shots > Player" . $TypeText . "Stat.GP) AND (PlayerInfo.Team > 0) AND (ShotsPCT > 0) ORDER BY ShotsPCT DESC, Player" . $TypeText . "Stat.GP ASC";
}else{
	$Query = GetPlayerQueryFull("ShotsPCT",$TypeText,$LeagueGeneral,$PlayoffString);
}
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1; $Position = (string)"";	if ($Row['PosC']== "True"){if ($Position == ""){$Position = "C";}else{$Position = $Position . "/C";}}if ($Row['PosLW']== "True"){if ($Position == ""){$Position = "LW";}else{$Position = $Position . "/LW";}}if ($Row['PosRW']== "True"){if ($Position == ""){$Position = "RW";}else{$Position = $Position . "/RW";}}if ($Row['PosD']== "True"){if ($Position == ""){$Position = "D";}else{$Position = $Position . "/D";}}
		
	echo "<tr><td>" . $LoopCount . "</td><td>";
	If ($Row['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPIndividualLeadersTeamImage\">";}
	If ($HistoryOutput == False){
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " - " . $Position . " (" . $Row['Abbre'] . ")";		
		If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $Row['NHLID'] != ""){
		echo "<div class=\"HeadshotHide\"><img loading=\"lazy\" src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $Row['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $Row['Name']. "\" class=\"STHSPHPIndividualLeadersHeadshot\" /></div>";}
	}else{
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'];		
	}
	echo "</a></td><td>" . $Row['GP'] . "</td><td>" . number_Format($Row['ShotsPCT'],2) . "%</td></tr>\n";	
		
}}
If ($LoopCount > 1){
	echo "</table>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table>";
}?>
</div>
<div class="DivSection LeaderPlayerStatPlusMinus">
<table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false" style="text-align:center;"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['PlusMinus'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Plus/Minus">+/-</th></tr></thead>
<?php
$Query = GetPlayerQuery($HistoryOutput, "PlusMinus",$TypeText,$LeagueGeneral,$PlayoffString,$MinimumGamePlayer);
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1; $Position = (string)"";	if ($Row['PosC']== "True"){if ($Position == ""){$Position = "C";}else{$Position = $Position . "/C";}}if ($Row['PosLW']== "True"){if ($Position == ""){$Position = "LW";}else{$Position = $Position . "/LW";}}if ($Row['PosRW']== "True"){if ($Position == ""){$Position = "RW";}else{$Position = $Position . "/RW";}}if ($Row['PosD']== "True"){if ($Position == ""){$Position = "D";}else{$Position = $Position . "/D";}}
		
	echo "<tr><td>" . $LoopCount . "</td><td>";
	If ($Row['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPIndividualLeadersTeamImage\">";}
	If ($HistoryOutput == False){
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " - " . $Position . " (" . $Row['Abbre'] . ")";		
		If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $Row['NHLID'] != ""){
		echo "<div class=\"HeadshotHide\"><img loading=\"lazy\" src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $Row['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $Row['Name']. "\" class=\"STHSPHPIndividualLeadersHeadshot\" /></div>";}
	}else{
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'];		
	}
	echo "</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['PlusMinus'] .  "</td></tr>\n";
	
}}
If ($LoopCount > 1){
	echo "</table>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table>";
}?>
</div>
<div class="DivSection LeaderPlayerStatP20">
<table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false" style="text-align:center;"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['Pointper20Minutes'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Points per 20 Minutes">P/20</th></tr></thead>
<?php
If ($HistoryOutput == False){
	$Query = "SELECT ROUND((CAST(Player" . $TypeText . "Stat.P AS REAL) / (Player" . $TypeText . "Stat.SecondPlay) * 60 * 20),2) AS P20, Player" . $TypeText . "Stat.GP, Player" . $TypeText . "Stat.Name, PlayerInfo.NHLID, PlayerInfo.PosC, PlayerInfo.PosLW, PlayerInfo.PosRW, PlayerInfo.PosD, Player" . $TypeText . "Stat.Number, Team" . $TypeText . "Info.Abbre, Team" . $TypeText . "Info.TeamThemeID FROM (PlayerInfo INNER JOIN Player" . $TypeText . "Stat ON PlayerInfo.Number = Player" . $TypeText . "Stat.Number) LEFT JOIN Team" . $TypeText . "Info ON PlayerInfo.Team = Team" . $TypeText . "Info.Number WHERE (Player" . $TypeText . "Stat.GP >= " . $MinimumGamePlayer. ") AND (PlayerInfo.Team > 0) AND (Player" . $TypeText . "Stat.P > 0) ORDER BY P20 DESC, Player" . $TypeText . "Stat.GP ASC";
}else{
	$Query = GetPlayerQueryFull("P20",$TypeText,$LeagueGeneral,$PlayoffString);
}
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1; $Position = (string)"";	if ($Row['PosC']== "True"){if ($Position == ""){$Position = "C";}else{$Position = $Position . "/C";}}if ($Row['PosLW']== "True"){if ($Position == ""){$Position = "LW";}else{$Position = $Position . "/LW";}}if ($Row['PosRW']== "True"){if ($Position == ""){$Position = "RW";}else{$Position = $Position . "/RW";}}if ($Row['PosD']== "True"){if ($Position == ""){$Position = "D";}else{$Position = $Position . "/D";}}
		
	echo "<tr><td>" . $LoopCount . "</td><td>";
	If ($Row['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPIndividualLeadersTeamImage\">";}
	If ($HistoryOutput == False){
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " - " . $Position . " (" . $Row['Abbre'] . ")";		
		If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $Row['NHLID'] != ""){
		echo "<div class=\"HeadshotHide\"><img loading=\"lazy\" src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $Row['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $Row['Name']. "\" class=\"STHSPHPIndividualLeadersHeadshot\" /></div>";}
	}else{
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'];		
	}
	echo "</a></td><td>" . $Row['GP'] . "</td><td>" . number_Format($Row['P20'],2) .  "</td></tr>\n";
	
}}
If ($LoopCount > 1){
	echo "</table>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table>";
}?>
</div>
<div class="DivSection LeaderPlayerStatFOPCT">
<table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false" style="text-align:center;"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['FaceoffPCT'];?>
</span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Face offs Percentage">FO%</th></tr></thead>
<?php
If ($HistoryOutput == False){
	$Query = "SELECT ROUND((CAST(Player" . $TypeText . "Stat.FaceOffWon AS REAL) / (Player" . $TypeText . "Stat.FaceOffTotal))*100,2) as FaceoffPCT, Player" . $TypeText . "Stat.GP, Player" . $TypeText . "Stat.Name, PlayerInfo.NHLID, PlayerInfo.PosC, PlayerInfo.PosLW, PlayerInfo.PosRW, PlayerInfo.PosD, Player" . $TypeText . "Stat.Number, Team" . $TypeText . "Info.Abbre, Team" . $TypeText . "Info.TeamThemeID FROM (PlayerInfo INNER JOIN Player" . $TypeText . "Stat ON PlayerInfo.Number = Player" . $TypeText . "Stat.Number) LEFT JOIN Team" . $TypeText . "Info ON PlayerInfo.Team = Team" . $TypeText . "Info.Number WHERE (Player" . $TypeText . "Stat.GP >= " . $MinimumGamePlayer. ") AND (Player" . $TypeText . "Stat.FaceOffTotal > (Player" . $TypeText . "Stat.GP * 5)) AND (PlayerInfo.Team > 0) ORDER BY FaceoffPCT DESC, Player" . $TypeText . "Stat.GP ASC";
}else{
	$Query = GetPlayerQueryFull("FaceoffPCT",$TypeText,$LeagueGeneral,$PlayoffString);
}

If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1; $Position = (string)"";	if ($Row['PosC']== "True"){if ($Position == ""){$Position = "C";}else{$Position = $Position . "/C";}}if ($Row['PosLW']== "True"){if ($Position == ""){$Position = "LW";}else{$Position = $Position . "/LW";}}if ($Row['PosRW']== "True"){if ($Position == ""){$Position = "RW";}else{$Position = $Position . "/RW";}}if ($Row['PosD']== "True"){if ($Position == ""){$Position = "D";}else{$Position = $Position . "/D";}}
		
	echo "<tr><td>" . $LoopCount . "</td><td>";
	If ($Row['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPIndividualLeadersTeamImage\">";}
	If ($HistoryOutput == False){
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " - " . $Position . " (" . $Row['Abbre'] . ")";		
		If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $Row['NHLID'] != ""){
		echo "<div class=\"HeadshotHide\"><img loading=\"lazy\" src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $Row['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $Row['Name']. "\" class=\"STHSPHPIndividualLeadersHeadshot\" /></div>";}
	}else{
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'];		
	}
	echo "</a></td><td>" . $Row['GP'] . "</td><td>" . number_Format($Row['FaceoffPCT'],2) . "%</td></tr>\n";
	
}}
If ($LoopCount > 1){
	echo "</table>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table>";
}?>
</div>
<div class="DivSection LeaderPlayerStatHIT">
<table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false" style="text-align:center;"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['Hits'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Hits">HIT</th></tr></thead>
<?php
$Query = GetPlayerQuery($HistoryOutput, "Hits",$TypeText,$LeagueGeneral,$PlayoffString,$MinimumGamePlayer);
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1; $Position = (string)"";	if ($Row['PosC']== "True"){if ($Position == ""){$Position = "C";}else{$Position = $Position . "/C";}}if ($Row['PosLW']== "True"){if ($Position == ""){$Position = "LW";}else{$Position = $Position . "/LW";}}if ($Row['PosRW']== "True"){if ($Position == ""){$Position = "RW";}else{$Position = $Position . "/RW";}}if ($Row['PosD']== "True"){if ($Position == ""){$Position = "D";}else{$Position = $Position . "/D";}}
		
	echo "<tr><td>" . $LoopCount . "</td><td>";
	If ($Row['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPIndividualLeadersTeamImage\">";}
	If ($HistoryOutput == False){
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " - " . $Position . " (" . $Row['Abbre'] . ")";		
		If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $Row['NHLID'] != ""){
		echo "<div class=\"HeadshotHide\"><img loading=\"lazy\" src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $Row['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $Row['Name']. "\" class=\"STHSPHPIndividualLeadersHeadshot\" /></div>";}
	}else{
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'];		
	}
	echo "</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['Hits'] .  "</td></tr>\n";
		
}}
If ($LoopCount > 1){
	echo "</table>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table>";
}?>
</div>
<div class="DivSection LeaderPlayerStatPIM">
<table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false" style="text-align:center;"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['PenaltyMinutes'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Penalty Minutes">PIM</th></tr></thead>
<?php
$Query = GetPlayerQuery($HistoryOutput, "Pim",$TypeText,$LeagueGeneral,$PlayoffString,$MinimumGamePlayer);
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1; $Position = (string)"";	if ($Row['PosC']== "True"){if ($Position == ""){$Position = "C";}else{$Position = $Position . "/C";}}if ($Row['PosLW']== "True"){if ($Position == ""){$Position = "LW";}else{$Position = $Position . "/LW";}}if ($Row['PosRW']== "True"){if ($Position == ""){$Position = "RW";}else{$Position = $Position . "/RW";}}if ($Row['PosD']== "True"){if ($Position == ""){$Position = "D";}else{$Position = $Position . "/D";}}
		
	echo "<tr><td>" . $LoopCount . "</td><td>";
	If ($Row['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPIndividualLeadersTeamImage\">";}
	If ($HistoryOutput == False){
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " - " . $Position . " (" . $Row['Abbre'] . ")";		
		If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $Row['NHLID'] != ""){
		echo "<div class=\"HeadshotHide\"><img loading=\"lazy\" src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $Row['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $Row['Name']. "\" class=\"STHSPHPIndividualLeadersHeadshot\" /></div>";}
	}else{
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'];		
	}
	echo "</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['Pim'] .  "</td></tr>\n";
		
}}
If ($LoopCount > 1){
	echo "</table>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table>";
}?>
</div>
<div class="DivSection LeaderPlayerStatSB">
<table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false" style="text-align:center;"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['ShotsBlock'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Shots Blocked">SB</th></tr></thead>
<?php
$Query = GetPlayerQuery($HistoryOutput, "ShotsBlock",$TypeText,$LeagueGeneral,$PlayoffString,$MinimumGamePlayer);
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1; $Position = (string)"";	if ($Row['PosC']== "True"){if ($Position == ""){$Position = "C";}else{$Position = $Position . "/C";}}if ($Row['PosLW']== "True"){if ($Position == ""){$Position = "LW";}else{$Position = $Position . "/LW";}}if ($Row['PosRW']== "True"){if ($Position == ""){$Position = "RW";}else{$Position = $Position . "/RW";}}if ($Row['PosD']== "True"){if ($Position == ""){$Position = "D";}else{$Position = $Position . "/D";}}
		
	echo "<tr><td>" . $LoopCount . "</td><td>";
	If ($Row['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPIndividualLeadersTeamImage\">";}
	If ($HistoryOutput == False){
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " - " . $Position . " (" . $Row['Abbre'] . ")";		
		If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $Row['NHLID'] != ""){
		echo "<div class=\"HeadshotHide\"><img loading=\"lazy\" src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $Row['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $Row['Name']. "\" class=\"STHSPHPIndividualLeadersHeadshot\" /></div>";}
	}else{
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'];		
	}
	echo "</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['ShotsBlock'] .  "</td></tr>\n";
	
}}
If ($LoopCount > 1){
	echo "</table>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table>";
}?>
</div>
<div class="DivSection LeaderPlayerStatPPG">
<table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false" style="text-align:center;"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['PowerPlayGoals'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Power Play Goals">PPG</th></tr></thead>
<?php
$Query = GetPlayerQuery($HistoryOutput, "PPG",$TypeText,$LeagueGeneral,$PlayoffString,$MinimumGamePlayer);
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1; $Position = (string)"";	if ($Row['PosC']== "True"){if ($Position == ""){$Position = "C";}else{$Position = $Position . "/C";}}if ($Row['PosLW']== "True"){if ($Position == ""){$Position = "LW";}else{$Position = $Position . "/LW";}}if ($Row['PosRW']== "True"){if ($Position == ""){$Position = "RW";}else{$Position = $Position . "/RW";}}if ($Row['PosD']== "True"){if ($Position == ""){$Position = "D";}else{$Position = $Position . "/D";}}
		
	echo "<tr><td>" . $LoopCount . "</td><td>";
	If ($Row['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPIndividualLeadersTeamImage\">";}
	If ($HistoryOutput == False){
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " - " . $Position . " (" . $Row['Abbre'] . ")";		
		If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $Row['NHLID'] != ""){
		echo "<div class=\"HeadshotHide\"><img loading=\"lazy\" src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $Row['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $Row['Name']. "\" class=\"STHSPHPIndividualLeadersHeadshot\" /></div>";}
	}else{
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'];		
	}
	echo "</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['PPG'] .  "</td></tr>\n";
		
}}
If ($LoopCount > 1){
	echo "</table>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table>";
}?>
</div>
<div class="DivSection LeaderPlayerStatPKG">
<table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false" style="text-align:center;"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['ShortHandedGoals'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Short Handed Goals">PKG</th></tr></thead>
<?php
$Query = GetPlayerQuery($HistoryOutput, "PKG",$TypeText,$LeagueGeneral,$PlayoffString,$MinimumGamePlayer);
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1; $Position = (string)"";	if ($Row['PosC']== "True"){if ($Position == ""){$Position = "C";}else{$Position = $Position . "/C";}}if ($Row['PosLW']== "True"){if ($Position == ""){$Position = "LW";}else{$Position = $Position . "/LW";}}if ($Row['PosRW']== "True"){if ($Position == ""){$Position = "RW";}else{$Position = $Position . "/RW";}}if ($Row['PosD']== "True"){if ($Position == ""){$Position = "D";}else{$Position = $Position . "/D";}}
		
	echo "<tr><td>" . $LoopCount . "</td><td>";
	If ($Row['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPIndividualLeadersTeamImage\">";}
	If ($HistoryOutput == False){
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " - " . $Position . " (" . $Row['Abbre'] . ")";		
		If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $Row['NHLID'] != ""){
		echo "<div class=\"HeadshotHide\"><img loading=\"lazy\" src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $Row['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $Row['Name']. "\" class=\"STHSPHPIndividualLeadersHeadshot\" /></div>";}
	}else{
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'];		
	}
	echo "</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['PKG'] .  "</td></tr>\n";
	
}}
If ($LoopCount > 1){
	echo "</table>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table>";
}?>
</div>
<div class="DivSection LeaderPlayerStatGW">
<table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false" style="text-align:center;"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['GameWinningGoals'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Game Winning Goals">GW</th></tr></thead>
<?php
$Query = GetPlayerQuery($HistoryOutput, "GW",$TypeText,$LeagueGeneral,$PlayoffString,$MinimumGamePlayer);
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1; $Position = (string)"";	if ($Row['PosC']== "True"){if ($Position == ""){$Position = "C";}else{$Position = $Position . "/C";}}if ($Row['PosLW']== "True"){if ($Position == ""){$Position = "LW";}else{$Position = $Position . "/LW";}}if ($Row['PosRW']== "True"){if ($Position == ""){$Position = "RW";}else{$Position = $Position . "/RW";}}if ($Row['PosD']== "True"){if ($Position == ""){$Position = "D";}else{$Position = $Position . "/D";}}
		
	echo "<tr><td>" . $LoopCount . "</td><td>";
	If ($Row['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPIndividualLeadersTeamImage\">";}
	If ($HistoryOutput == False){
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " - " . $Position . " (" . $Row['Abbre'] . ")";		
		If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $Row['NHLID'] != ""){
		echo "<div class=\"HeadshotHide\"><img loading=\"lazy\" src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $Row['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $Row['Name']. "\" class=\"STHSPHPIndividualLeadersHeadshot\" /></div>";}
	}else{
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'];		
	}
	echo "</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['GW'] .  "</td></tr>\n";
		
}}
If ($LoopCount > 1){
	echo "</table>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table>";
}?>
</div>
<div class="DivSection LeaderPlayerStatGT">
<table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false" style="text-align:center;"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['GameTyingGoals'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Game Tying Goals">GT</th></tr></thead>
<?php
$Query = GetPlayerQuery($HistoryOutput, "GT",$TypeText,$LeagueGeneral,$PlayoffString,$MinimumGamePlayer);
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1; $Position = (string)"";	if ($Row['PosC']== "True"){if ($Position == ""){$Position = "C";}else{$Position = $Position . "/C";}}if ($Row['PosLW']== "True"){if ($Position == ""){$Position = "LW";}else{$Position = $Position . "/LW";}}if ($Row['PosRW']== "True"){if ($Position == ""){$Position = "RW";}else{$Position = $Position . "/RW";}}if ($Row['PosD']== "True"){if ($Position == ""){$Position = "D";}else{$Position = $Position . "/D";}}
		
	echo "<tr><td>" . $LoopCount . "</td><td>";
	If ($Row['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPIndividualLeadersTeamImage\">";}
	If ($HistoryOutput == False){
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " - " . $Position . " (" . $Row['Abbre'] . ")";		
		If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $Row['NHLID'] != ""){
		echo "<div class=\"HeadshotHide\"><img loading=\"lazy\" src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $Row['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $Row['Name']. "\" class=\"STHSPHPIndividualLeadersHeadshot\" /></div>";}
	}else{
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'];		
	}
	echo "</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['GT'] .  "</td></tr>\n";
	
}}
If ($LoopCount > 1){
	echo "</table>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table>";
}?>
</div>
<div class="DivSection LeaderPlayerStatED">
<table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false" style="text-align:center;"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['EmptyNetGoals'];?>
</span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Empty Net Goals">EG</th></tr></thead>
<?php
$Query = GetPlayerQuery($HistoryOutput, "EmptyNetGoal",$TypeText,$LeagueGeneral,$PlayoffString,$MinimumGamePlayer);
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1; $Position = (string)"";	if ($Row['PosC']== "True"){if ($Position == ""){$Position = "C";}else{$Position = $Position . "/C";}}if ($Row['PosLW']== "True"){if ($Position == ""){$Position = "LW";}else{$Position = $Position . "/LW";}}if ($Row['PosRW']== "True"){if ($Position == ""){$Position = "RW";}else{$Position = $Position . "/RW";}}if ($Row['PosD']== "True"){if ($Position == ""){$Position = "D";}else{$Position = $Position . "/D";}}
		
	echo "<tr><td>" . $LoopCount . "</td><td>";
	If ($Row['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPIndividualLeadersTeamImage\">";}
	If ($HistoryOutput == False){
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " - " . $Position . " (" . $Row['Abbre'] . ")";		
		If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $Row['NHLID'] != ""){
		echo "<div class=\"HeadshotHide\"><img loading=\"lazy\" src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $Row['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $Row['Name']. "\" class=\"STHSPHPIndividualLeadersHeadshot\" /></div>";}
	}else{
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'];		
	}
	echo "</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['EmptyNetGoal'] .  "</td></tr>\n";
	
}}
If ($LoopCount > 1){
	echo "</table>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table>";
}?>
</div>
<div class="DivSection LeaderPlayerStatMP">
<table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false" style="text-align:center;"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['MinutesPlayed'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Minutes Played">MP</th></tr></thead>
<?php
$Query = GetPlayerQuery($HistoryOutput, "SecondPlay",$TypeText,$LeagueGeneral,$PlayoffString,$MinimumGamePlayer);
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1; $Position = (string)"";	if ($Row['PosC']== "True"){if ($Position == ""){$Position = "C";}else{$Position = $Position . "/C";}}if ($Row['PosLW']== "True"){if ($Position == ""){$Position = "LW";}else{$Position = $Position . "/LW";}}if ($Row['PosRW']== "True"){if ($Position == ""){$Position = "RW";}else{$Position = $Position . "/RW";}}if ($Row['PosD']== "True"){if ($Position == ""){$Position = "D";}else{$Position = $Position . "/D";}}
		
	echo "<tr><td>" . $LoopCount . "</td><td>";
	If ($Row['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPIndividualLeadersTeamImage\">";}
	If ($HistoryOutput == False){
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " - " . $Position . " (" . $Row['Abbre'] . ")";		
		If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $Row['NHLID'] != ""){
		echo "<div class=\"HeadshotHide\"><img loading=\"lazy\" src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $Row['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $Row['Name']. "\" class=\"STHSPHPIndividualLeadersHeadshot\" /></div>";}
	}else{
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'];		
	}
	echo "</a></td><td>" . $Row['GP'] . "</td><td>" . Floor($Row['SecondPlay']/60) .  "</td></tr>\n";
		
}}
If ($LoopCount > 1){
	echo "</table>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table>";
}?>
</div>
<div class="DivSection LeaderPlayerStatC">
<table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false" style="text-align:center;"><span class="STHSIndividualLeadersTitle"><?php echo $PlayersLang['Center'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Points">PTS</th></tr></thead>
<?php
If ($HistoryOutput == False){
	$Query = "SELECT Player" . $TypeText . "Stat.G, Player" . $TypeText . "Stat.A, Player" . $TypeText . "Stat.P, Player" . $TypeText . "Stat.GP, Player" . $TypeText . "Stat.Name, PlayerInfo.NHLID, PlayerInfo.PosC, PlayerInfo.PosLW, PlayerInfo.PosRW, PlayerInfo.PosD, Player" . $TypeText . "Stat.Number, Team" . $TypeText . "Info.Abbre, Team" . $TypeText . "Info.TeamThemeID, PlayerInfo.NHLID, PlayerInfo.PosC FROM (PlayerInfo INNER JOIN Player" . $TypeText . "Stat ON PlayerInfo.Number = Player" . $TypeText . "Stat.Number) LEFT JOIN Team" . $TypeText . "Info ON PlayerInfo.Team = Team" . $TypeText . "Info.Number WHERE (Player" . $TypeText . "Stat.GP >= " . $MinimumGamePlayer. ") AND (PlayerInfo.Team > 0) AND (PlayerInfo.PosC='True') ORDER BY Player" . $TypeText . "Stat.P DESC, Player" . $TypeText . "Stat.GP ASC";
}else{
	$Query = GetPlayerQueryPositionHistory("P", $TypeText,$LeagueGeneral,$PlayoffString,"C");
}

If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1; $Position = (string)"";	if ($Row['PosC']== "True"){if ($Position == ""){$Position = "C";}else{$Position = $Position . "/C";}}if ($Row['PosLW']== "True"){if ($Position == ""){$Position = "LW";}else{$Position = $Position . "/LW";}}if ($Row['PosRW']== "True"){if ($Position == ""){$Position = "RW";}else{$Position = $Position . "/RW";}}if ($Row['PosD']== "True"){if ($Position == ""){$Position = "D";}else{$Position = $Position . "/D";}}
		
	echo "<tr><td>" . $LoopCount . "</td><td>";
	If ($Row['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPIndividualLeadersTeamImage\">";}
	If ($HistoryOutput == False){
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " - " . $Position . " (" . $Row['Abbre'] . ")";		
		If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $Row['NHLID'] != ""){
		echo "<div class=\"HeadshotHide\"><img loading=\"lazy\" src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $Row['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $Row['Name']. "\" class=\"STHSPHPIndividualLeadersHeadshot\" /></div>";}
		echo "</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['G'] . "-" . $Row['A'] . "-" . $Row['P'] . "</td></tr>\n";	
	}else{
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'];		
		echo "</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['P'] . "</td></tr>\n";
	}
		
}}
If ($LoopCount > 1){
	echo "</table>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table>";
}?>
</div>
<div class="DivSection LeaderPlayerStatLW">
<table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false" style="text-align:center;"><span class="STHSIndividualLeadersTitle"><?php echo $PlayersLang['LeftWing'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Points">PTS</th></tr></thead>
<?php
If ($HistoryOutput == False){
	$Query = "SELECT Player" . $TypeText . "Stat.G, Player" . $TypeText . "Stat.A, Player" . $TypeText . "Stat.P, Player" . $TypeText . "Stat.GP, Player" . $TypeText . "Stat.Name, PlayerInfo.NHLID, PlayerInfo.PosC, PlayerInfo.PosLW, PlayerInfo.PosRW, PlayerInfo.PosD, Player" . $TypeText . "Stat.Number, Team" . $TypeText . "Info.Abbre, Team" . $TypeText . "Info.TeamThemeID, PlayerInfo.PosLW FROM (PlayerInfo INNER JOIN Player" . $TypeText . "Stat ON PlayerInfo.Number = Player" . $TypeText . "Stat.Number) LEFT JOIN Team" . $TypeText . "Info ON PlayerInfo.Team = Team" . $TypeText . "Info.Number WHERE (Player" . $TypeText . "Stat.GP >= " . $MinimumGamePlayer. ") AND (PlayerInfo.Team > 0) AND (PlayerInfo.PosLW='True') ORDER BY Player" . $TypeText . "Stat.P DESC, Player" . $TypeText . "Stat.GP ASC";
}else{
	$Query = GetPlayerQueryPositionHistory("P", $TypeText,$LeagueGeneral,$PlayoffString,"LW");
}

If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1; $Position = (string)"";	if ($Row['PosC']== "True"){if ($Position == ""){$Position = "C";}else{$Position = $Position . "/C";}}if ($Row['PosLW']== "True"){if ($Position == ""){$Position = "LW";}else{$Position = $Position . "/LW";}}if ($Row['PosRW']== "True"){if ($Position == ""){$Position = "RW";}else{$Position = $Position . "/RW";}}if ($Row['PosD']== "True"){if ($Position == ""){$Position = "D";}else{$Position = $Position . "/D";}}
		
	echo "<tr><td>" . $LoopCount . "</td><td>";
	If ($Row['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPIndividualLeadersTeamImage\">";}
	If ($HistoryOutput == False){
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " - " . $Position . " (" . $Row['Abbre'] . ")";		
		If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $Row['NHLID'] != ""){
		echo "<div class=\"HeadshotHide\"><img loading=\"lazy\" src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $Row['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $Row['Name']. "\" class=\"STHSPHPIndividualLeadersHeadshot\" /></div>";}
		echo "</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['G'] . "-" . $Row['A'] . "-" . $Row['P'] . "</td></tr>\n";
	}else{
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'];		
		echo "</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['P'] . "</td></tr>\n";	
	}
		
}}
If ($LoopCount > 1){
	echo "</table>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table>";
}?>
</div>
<div class="DivSection LeaderPlayerStatRW">
<table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false" style="text-align:center;"><span class="STHSIndividualLeadersTitle"><?php echo $PlayersLang['RightWing'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Points">PTS</th></tr></thead>
<?php
If ($HistoryOutput == False){
	$Query = "SELECT Player" . $TypeText . "Stat.G, Player" . $TypeText . "Stat.A, Player" . $TypeText . "Stat.P, Player" . $TypeText . "Stat.GP, Player" . $TypeText . "Stat.Name, PlayerInfo.NHLID, PlayerInfo.PosC, PlayerInfo.PosLW, PlayerInfo.PosRW, PlayerInfo.PosD, Player" . $TypeText . "Stat.Number, Team" . $TypeText . "Info.Abbre, Team" . $TypeText . "Info.TeamThemeID, PlayerInfo.PosRW FROM (PlayerInfo INNER JOIN Player" . $TypeText . "Stat ON PlayerInfo.Number = Player" . $TypeText . "Stat.Number) LEFT JOIN Team" . $TypeText . "Info ON PlayerInfo.Team = Team" . $TypeText . "Info.Number WHERE (Player" . $TypeText . "Stat.GP >= " . $MinimumGamePlayer. ") AND (PlayerInfo.Team > 0) AND (PlayerInfo.PosRW='True') ORDER BY Player" . $TypeText . "Stat.P DESC, Player" . $TypeText . "Stat.GP ASC";
}else{
	$Query = GetPlayerQueryPositionHistory("P", $TypeText,$LeagueGeneral,$PlayoffString,"RW");
}
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1; $Position = (string)"";	if ($Row['PosC']== "True"){if ($Position == ""){$Position = "C";}else{$Position = $Position . "/C";}}if ($Row['PosLW']== "True"){if ($Position == ""){$Position = "LW";}else{$Position = $Position . "/LW";}}if ($Row['PosRW']== "True"){if ($Position == ""){$Position = "RW";}else{$Position = $Position . "/RW";}}if ($Row['PosD']== "True"){if ($Position == ""){$Position = "D";}else{$Position = $Position . "/D";}}
		
	echo "<tr><td>" . $LoopCount . "</td><td>";
	If ($Row['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPIndividualLeadersTeamImage\">";}
	If ($HistoryOutput == False){
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " - " . $Position . " (" . $Row['Abbre'] . ")";		
		If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $Row['NHLID'] != ""){
		echo "<div class=\"HeadshotHide\"><img loading=\"lazy\" src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $Row['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $Row['Name']. "\" class=\"STHSPHPIndividualLeadersHeadshot\" /></div>";}
		echo "</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['G'] . "-" . $Row['A'] . "-" . $Row['P'] . "</td></tr>\n";
	}else{
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'];		
		echo "</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['P'] . "</td></tr>\n";
	}
}}
If ($LoopCount > 1){
	echo "</table>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table>";
}?>
</div>
<div class="DivSection LeaderPlayerStatD">
<table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false" style="text-align:center;"><span class="STHSIndividualLeadersTitle"><?php echo $PlayersLang['Defenseman'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Points">PTS</th></tr></thead>
<?php
If ($HistoryOutput == False){
	$Query = "SELECT Player" . $TypeText . "Stat.G, Player" . $TypeText . "Stat.A, Player" . $TypeText . "Stat.P, Player" . $TypeText . "Stat.GP, Player" . $TypeText . "Stat.Name, PlayerInfo.NHLID, PlayerInfo.PosC, PlayerInfo.PosLW, PlayerInfo.PosRW, PlayerInfo.PosD, Player" . $TypeText . "Stat.Number, Team" . $TypeText . "Info.Abbre, Team" . $TypeText . "Info.TeamThemeID, PlayerInfo.PosD FROM (PlayerInfo INNER JOIN Player" . $TypeText . "Stat ON PlayerInfo.Number = Player" . $TypeText . "Stat.Number) LEFT JOIN Team" . $TypeText . "Info ON PlayerInfo.Team = Team" . $TypeText . "Info.Number WHERE (Player" . $TypeText . "Stat.GP >= " . $MinimumGamePlayer. ") AND (PlayerInfo.Team > 0) AND (PlayerInfo.PosD='True') ORDER BY Player" . $TypeText . "Stat.P DESC, Player" . $TypeText . "Stat.GP ASC";
}else{
	$Query = GetPlayerQueryPositionHistory("P", $TypeText,$LeagueGeneral,$PlayoffString,"D");
}
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1; $Position = (string)"";	if ($Row['PosC']== "True"){if ($Position == ""){$Position = "C";}else{$Position = $Position . "/C";}}if ($Row['PosLW']== "True"){if ($Position == ""){$Position = "LW";}else{$Position = $Position . "/LW";}}if ($Row['PosRW']== "True"){if ($Position == ""){$Position = "RW";}else{$Position = $Position . "/RW";}}if ($Row['PosD']== "True"){if ($Position == ""){$Position = "D";}else{$Position = $Position . "/D";}}
		
	echo "<tr><td>" . $LoopCount . "</td><td>";
	If ($Row['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPIndividualLeadersTeamImage\">";}
	If ($HistoryOutput == False){
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " - " . $Position . " (" . $Row['Abbre'] . ")";		
		If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $Row['NHLID'] != ""){
		echo "<div class=\"HeadshotHide\"><img loading=\"lazy\" src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $Row['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $Row['Name']. "\" class=\"STHSPHPIndividualLeadersHeadshot\" /></div>";}
		echo "</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['G'] . "-" . $Row['A'] . "-" . $Row['P'] . "</td></tr>\n";
	}else{
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'];		
		echo "</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['P'] . "</td></tr>\n";
	}
		
}}
If ($LoopCount > 1){
	echo "</table>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table>";
}?>
</div>
<div class="DivSection LeaderPlayerStatRookie">
<table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false" style="text-align:center;"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['Rookie'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Points">PTS</th></tr></thead>
<?php
$Query = "SELECT Player" . $TypeText . "Stat.G, Player" . $TypeText . "Stat.A, Player" . $TypeText . "Stat.P, Player" . $TypeText . "Stat.GP, Player" . $TypeText . "Stat.Name, PlayerInfo.NHLID, PlayerInfo.PosC, PlayerInfo.PosLW, PlayerInfo.PosRW, PlayerInfo.PosD, Player" . $TypeText . "Stat.Number, Team" . $TypeText . "Info.Abbre, Team" . $TypeText . "Info.TeamThemeID, PlayerInfo.Rookie FROM (PlayerInfo INNER JOIN Player" . $TypeText . "Stat ON PlayerInfo.Number = Player" . $TypeText . "Stat.Number) LEFT JOIN Team" . $TypeText . "Info ON PlayerInfo.Team = Team" . $TypeText . "Info.Number WHERE (Player" . $TypeText . "Stat.GP >= " . $MinimumGamePlayer. ") AND (PlayerInfo.Team > 0) AND (PlayerInfo.Rookie='True') ORDER BY Player" . $TypeText . "Stat.P DESC, Player" . $TypeText . "Stat.GP ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1; $Position = (string)"";	if ($Row['PosC']== "True"){if ($Position == ""){$Position = "C";}else{$Position = $Position . "/C";}}if ($Row['PosLW']== "True"){if ($Position == ""){$Position = "LW";}else{$Position = $Position . "/LW";}}if ($Row['PosRW']== "True"){if ($Position == ""){$Position = "RW";}else{$Position = $Position . "/RW";}}if ($Row['PosD']== "True"){if ($Position == ""){$Position = "D";}else{$Position = $Position . "/D";}}
		
	echo "<tr><td>" . $LoopCount . "</td><td>";
	If ($Row['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPIndividualLeadersTeamImage\">";}
	If ($HistoryOutput == False){
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " - " . $Position . " (" . $Row['Abbre'] . ")";		
		If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $Row['NHLID'] != ""){
		echo "<div class=\"HeadshotHide\"><img loading=\"lazy\" src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $Row['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $Row['Name']. "\" class=\"STHSPHPIndividualLeadersHeadshot\" /></div>";}
	}else{
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'];		
	}
	echo "</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['G'] . "-" . $Row['A'] . "-" . $Row['P'] . "</td></tr>\n";
	
}}
If ($LoopCount > 1){
	echo "</table>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table>";
}?>
</div>
<div class="DivSection LeaderPlayerStatHT">
<table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false" style="text-align:center;"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['HatTricks'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Hat Tricks">HT</th></tr></thead>
<?php
$Query = GetPlayerQuery($HistoryOutput, "HatTrick",$TypeText,$LeagueGeneral,$PlayoffString,$MinimumGamePlayer);
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1; $Position = (string)"";	if ($Row['PosC']== "True"){if ($Position == ""){$Position = "C";}else{$Position = $Position . "/C";}}if ($Row['PosLW']== "True"){if ($Position == ""){$Position = "LW";}else{$Position = $Position . "/LW";}}if ($Row['PosRW']== "True"){if ($Position == ""){$Position = "RW";}else{$Position = $Position . "/RW";}}if ($Row['PosD']== "True"){if ($Position == ""){$Position = "D";}else{$Position = $Position . "/D";}}
		
	echo "<tr><td>" . $LoopCount . "</td><td>";
	If ($Row['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPIndividualLeadersTeamImage\">";}
	If ($HistoryOutput == False){
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " - " . $Position . " (" . $Row['Abbre'] . ")";		
		If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $Row['NHLID'] != ""){
		echo "<div class=\"HeadshotHide\"><img loading=\"lazy\" src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $Row['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $Row['Name']. "\" class=\"STHSPHPIndividualLeadersHeadshot\" /></div>";}
	}else{
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'];		
	}
	echo "</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['HatTrick'] .  "</td></tr>\n";
	
}}
If ($LoopCount > 1){
	echo "</table>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table>";
}?>
</div>
<div class="DivSection LeaderPlayerStatGS">
<table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false" style="text-align:center;"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['GoalsScoringStreak'];?>
</span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Goal Scoring Streak">GS</th></tr></thead>
<?php
If($TypeText == "Pro"){
$Query = "SELECT PlayerProStat.GP, PlayerProStat.Name, PlayerInfo.Number, TeamProInfo.Abbre, PlayerInfo.NHLID, PlayerInfo.PosC, PlayerInfo.PosLW, PlayerInfo.PosRW, PlayerInfo.PosD, PlayerInfo.Status1, PlayerInfo.GameInRowWithAGoal, PlayerInfo.TeamThemeID FROM (PlayerInfo INNER JOIN PlayerProStat ON PlayerInfo.Number = PlayerProStat.Number) LEFT JOIN TeamProInfo ON PlayerInfo.Team = TeamProInfo.Number WHERE (PlayerProStat.GP >= " . $MinimumGamePlayer. ") AND (PlayerInfo.Team>0) AND (PlayerInfo.Status1 >=2) AND (PlayerInfo.GameInRowWithAGoal > 0) ORDER BY PlayerInfo.GameInRowWithAGoal DESC , PlayerProStat.GP";
}elseIf($TypeText == "Farm"){
$Query = "SELECT PlayerFarmStat.GP, PlayerFarmStat.Name, PlayerInfo.Number, TeamFarmInfo.Abbre, PlayerInfo.NHLID, PlayerInfo.PosC, PlayerInfo.PosLW, PlayerInfo.PosRW, PlayerInfo.PosD, PlayerInfo.Status1, PlayerInfo.GameInRowWithAGoal, PlayerInfo.TeamThemeID FROM (PlayerInfo INNER JOIN PlayerFarmStat ON PlayerInfo.Number = PlayerFarmStat.Number) LEFT JOIN TeamFarmInfo ON PlayerInfo.Team = TeamFarmInfo.Number WHERE (PlayerFarmStat.GP >= " . $MinimumGamePlayer. ") AND (PlayerInfo.Team>0) AND (PlayerInfo.Status1 <=1) AND (PlayerInfo.GameInRowWithAGoal > 0) ORDER BY PlayerInfo.GameInRowWithAGoal DESC , PlayerFarmStat.GP";
}else{$Query = "";}	
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1; $Position = (string)"";	if ($Row['PosC']== "True"){if ($Position == ""){$Position = "C";}else{$Position = $Position . "/C";}}if ($Row['PosLW']== "True"){if ($Position == ""){$Position = "LW";}else{$Position = $Position . "/LW";}}if ($Row['PosRW']== "True"){if ($Position == ""){$Position = "RW";}else{$Position = $Position . "/RW";}}if ($Row['PosD']== "True"){if ($Position == ""){$Position = "D";}else{$Position = $Position . "/D";}}
		
	echo "<tr><td>" . $LoopCount . "</td><td>";
	If ($Row['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPIndividualLeadersTeamImage\">";}
	If ($HistoryOutput == False){
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " - " . $Position . " (" . $Row['Abbre'] . ")";		
		If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $Row['NHLID'] != ""){
		echo "<div class=\"HeadshotHide\"><img loading=\"lazy\" src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $Row['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $Row['Name']. "\" class=\"STHSPHPIndividualLeadersHeadshot\" /></div>";}
	}else{
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'];		
	}
	echo "</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['GameInRowWithAGoal'] .  "</td></tr>\n";
		
}}
If ($LoopCount > 1){
	echo "</table>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table>";
}?>
</div>
<div class="DivSection LeaderPlayerStatPS">
<table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false" style="text-align:center;"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['PointsScoringStreak'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Point Scoring Streak">PS</th></tr></thead>
<?php
If($TypeText == "Pro"){
$Query = "SELECT PlayerProStat.GP, PlayerProStat.Name, PlayerInfo.Number, TeamProInfo.Abbre, PlayerInfo.NHLID, PlayerInfo.PosC, PlayerInfo.PosLW, PlayerInfo.PosRW, PlayerInfo.PosD, PlayerInfo.Status1, PlayerInfo.GameInRowWithAPoint, PlayerInfo.TeamThemeID FROM (PlayerInfo INNER JOIN PlayerProStat ON PlayerInfo.Number = PlayerProStat.Number) LEFT JOIN TeamProInfo ON PlayerInfo.Team = TeamProInfo.Number WHERE (PlayerProStat.GP >= " . $MinimumGamePlayer. ") AND (PlayerInfo.Team>0) AND (PlayerInfo.Status1 >=2) AND (PlayerInfo.GameInRowWithAPoint > 0) ORDER BY PlayerInfo.GameInRowWithAPoint DESC , PlayerProStat.GP";
}elseIf($TypeText == "Farm"){
$Query = "SELECT PlayerFarmStat.GP, PlayerFarmStat.Name, PlayerInfo.Number, TeamFarmInfo.Abbre, PlayerInfo.NHLID, PlayerInfo.PosC, PlayerInfo.PosLW, PlayerInfo.PosRW, PlayerInfo.PosD, PlayerInfo.Status1, PlayerInfo.GameInRowWithAPoint, PlayerInfo.TeamThemeID FROM (PlayerInfo INNER JOIN PlayerFarmStat ON PlayerInfo.Number = PlayerFarmStat.Number) LEFT JOIN TeamFarmInfo ON PlayerInfo.Team = TeamFarmInfo.Number WHERE (PlayerFarmStat.GP >= " . $MinimumGamePlayer. ") AND (PlayerInfo.Team>0) AND (PlayerInfo.Status1 <=1) AND (PlayerInfo.GameInRowWithAPoint > 0) ORDER BY PlayerInfo.GameInRowWithAPoint DESC , PlayerFarmStat.GP";
}else{$Query = "";}	
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1; $Position = (string)"";	if ($Row['PosC']== "True"){if ($Position == ""){$Position = "C";}else{$Position = $Position . "/C";}}if ($Row['PosLW']== "True"){if ($Position == ""){$Position = "LW";}else{$Position = $Position . "/LW";}}if ($Row['PosRW']== "True"){if ($Position == ""){$Position = "RW";}else{$Position = $Position . "/RW";}}if ($Row['PosD']== "True"){if ($Position == ""){$Position = "D";}else{$Position = $Position . "/D";}}
		
	echo "<tr><td>" . $LoopCount . "</td><td>";
	If ($Row['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPIndividualLeadersTeamImage\">";}
	If ($HistoryOutput == False){
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " - " . $Position . " (" . $Row['Abbre'] . ")";		
		If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $Row['NHLID'] != ""){
		echo "<div class=\"HeadshotHide\"><img loading=\"lazy\" src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $Row['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $Row['Name']. "\" class=\"STHSPHPIndividualLeadersHeadshot\" /></div>";}
	}else{
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'];		
	}
	echo "</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['GameInRowWithAPoint'] .  "</td></tr>\n";
	
}}
If ($LoopCount > 1){
	echo "</table>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table>";
}?>
</div>
<div class="DivSection LeaderPlayerStatHTT">
<table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false" style="text-align:center;"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['HitsReceived'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Hits Received">HTT</th></tr></thead>
<?php
$Query = GetPlayerQuery($HistoryOutput, "HitsTook",$TypeText,$LeagueGeneral,$PlayoffString,$MinimumGamePlayer);
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1; $Position = (string)"";	if ($Row['PosC']== "True"){if ($Position == ""){$Position = "C";}else{$Position = $Position . "/C";}}if ($Row['PosLW']== "True"){if ($Position == ""){$Position = "LW";}else{$Position = $Position . "/LW";}}if ($Row['PosRW']== "True"){if ($Position == ""){$Position = "RW";}else{$Position = $Position . "/RW";}}if ($Row['PosD']== "True"){if ($Position == ""){$Position = "D";}else{$Position = $Position . "/D";}}
		
	echo "<tr><td>" . $LoopCount . "</td><td>";
	If ($Row['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPIndividualLeadersTeamImage\">";}
	If ($HistoryOutput == False){
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " - " . $Position . " (" . $Row['Abbre'] . ")";		
		If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $Row['NHLID'] != ""){
		echo "<div class=\"HeadshotHide\"><img loading=\"lazy\" src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $Row['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $Row['Name']. "\" class=\"STHSPHPIndividualLeadersHeadshot\" /></div>";}
	}else{
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'];		
	}
	echo "</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['HitsTook'] .  "</td></tr>\n";
	
}}
If ($LoopCount > 1){
	echo "</table>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table>";
}?>
</div>
<div class="DivSection LeaderPlayerStatPSG">
<table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false" style="text-align:center;"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['PenaltyShotsGoals'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Penalty Shots Goals">PSG</th></tr></thead>
<?php
$Query = GetPlayerQuery($HistoryOutput, "PenalityShotsScore",$TypeText,$LeagueGeneral,$PlayoffString,$MinimumGamePlayer);
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1; $Position = (string)"";	if ($Row['PosC']== "True"){if ($Position == ""){$Position = "C";}else{$Position = $Position . "/C";}}if ($Row['PosLW']== "True"){if ($Position == ""){$Position = "LW";}else{$Position = $Position . "/LW";}}if ($Row['PosRW']== "True"){if ($Position == ""){$Position = "RW";}else{$Position = $Position . "/RW";}}if ($Row['PosD']== "True"){if ($Position == ""){$Position = "D";}else{$Position = $Position . "/D";}}
		
	echo "<tr><td>" . $LoopCount . "</td><td>";
	If ($Row['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPIndividualLeadersTeamImage\">";}
	If ($HistoryOutput == False){
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " - " . $Position . " (" . $Row['Abbre'] . ")";		
		If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $Row['NHLID'] != ""){
		echo "<div class=\"HeadshotHide\"><img loading=\"lazy\" src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $Row['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $Row['Name']. "\" class=\"STHSPHPIndividualLeadersHeadshot\" /></div>";}
	}else{
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'];		
	}
	echo "</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['PenalityShotsScore'] .  "</td></tr>\n";
		
}}
If ($LoopCount > 1){
	echo "</table>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table>";
}?>
</div>
<div class="DivSection LeaderPlayerStatGA">
<table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false" style="text-align:center;"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['GiveAways'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Give Aways">GA</th></tr></thead>
<?php
$Query = GetPlayerQuery($HistoryOutput, "GiveAway",$TypeText,$LeagueGeneral,$PlayoffString,$MinimumGamePlayer);
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1; $Position = (string)"";	if ($Row['PosC']== "True"){if ($Position == ""){$Position = "C";}else{$Position = $Position . "/C";}}if ($Row['PosLW']== "True"){if ($Position == ""){$Position = "LW";}else{$Position = $Position . "/LW";}}if ($Row['PosRW']== "True"){if ($Position == ""){$Position = "RW";}else{$Position = $Position . "/RW";}}if ($Row['PosD']== "True"){if ($Position == ""){$Position = "D";}else{$Position = $Position . "/D";}}
		
	echo "<tr><td>" . $LoopCount . "</td><td>";
	If ($Row['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPIndividualLeadersTeamImage\">";}
	If ($HistoryOutput == False){
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " - " . $Position . " (" . $Row['Abbre'] . ")";		
		If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $Row['NHLID'] != ""){
		echo "<div class=\"HeadshotHide\"><img loading=\"lazy\" src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $Row['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $Row['Name']. "\" class=\"STHSPHPIndividualLeadersHeadshot\" /></div>";}
	}else{
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'];		
	}
	echo "</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['GiveAway'] .  "</td></tr>\n";
		
}}
If ($LoopCount > 1){
	echo "</table>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table>";
}?>
</div>
<div class="DivSection LeaderPlayerStatTA">
<table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false" style="text-align:center;"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['TakeAways'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Penalty Shots Goals">TA</th></tr></thead>
<?php
$Query = GetPlayerQuery($HistoryOutput, "TakeAway",$TypeText,$LeagueGeneral,$PlayoffString,$MinimumGamePlayer);
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1; $Position = (string)"";	if ($Row['PosC']== "True"){if ($Position == ""){$Position = "C";}else{$Position = $Position . "/C";}}if ($Row['PosLW']== "True"){if ($Position == ""){$Position = "LW";}else{$Position = $Position . "/LW";}}if ($Row['PosRW']== "True"){if ($Position == ""){$Position = "RW";}else{$Position = $Position . "/RW";}}if ($Row['PosD']== "True"){if ($Position == ""){$Position = "D";}else{$Position = $Position . "/D";}}
		
	echo "<tr><td>" . $LoopCount . "</td><td>";
	If ($Row['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPIndividualLeadersTeamImage\">";}
	If ($HistoryOutput == False){
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " - " . $Position . " (" . $Row['Abbre'] . ")";		
		If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $Row['NHLID'] != ""){
		echo "<div class=\"HeadshotHide\"><img loading=\"lazy\" src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $Row['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $Row['Name']. "\" class=\"STHSPHPIndividualLeadersHeadshot\" /></div>";}
	}else{
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'];		
	}
	echo "</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['TakeAway'] .  "</td></tr>\n";
	
}}
If ($LoopCount > 1){
	echo "</table>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table>";
}?>
</div>
<div class="DivSection LeaderPlayerStatTF">
<table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false" style="text-align:center;"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['TotalFight'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Total Fight">TF</th></tr></thead>
<?php
If ($HistoryOutput == False){
	$Query = "SELECT [Player" . $TypeText . "Stat].[FightW]+[Player" . $TypeText . "Stat].[FightL]+[Player" . $TypeText . "Stat].[FightT] AS TotalFight, Player" . $TypeText . "Stat.GP, Player" . $TypeText . "Stat.Name, PlayerInfo.Number, Team" . $TypeText . "Info.Abbre, PlayerInfo.NHLID ,PlayerInfo.PosC, PlayerInfo.PosLW, PlayerInfo.PosRW, PlayerInfo.PosD, Team" . $TypeText . "Info.TeamThemeID FROM (PlayerInfo INNER JOIN Player" . $TypeText . "Stat ON PlayerInfo.Number = Player" . $TypeText . "Stat.Number) LEFT JOIN Team" . $TypeText . "Info ON PlayerInfo.Team = Team" . $TypeText ."Info.Number WHERE (Player" . $TypeText . "Stat.GP>=5) AND (PlayerInfo.Team>0) AND (TotalFight > 0) ORDER BY TotalFight DESC , Player" . $TypeText . "Stat.GP ASC";
}else{
	$Query = GetPlayerQueryFull("TotalFight",$TypeText,$LeagueGeneral,$PlayoffString);
}

If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1; $Position = (string)"";	if ($Row['PosC']== "True"){if ($Position == ""){$Position = "C";}else{$Position = $Position . "/C";}}if ($Row['PosLW']== "True"){if ($Position == ""){$Position = "LW";}else{$Position = $Position . "/LW";}}if ($Row['PosRW']== "True"){if ($Position == ""){$Position = "RW";}else{$Position = $Position . "/RW";}}if ($Row['PosD']== "True"){if ($Position == ""){$Position = "D";}else{$Position = $Position . "/D";}}
		
	echo "<tr><td>" . $LoopCount . "</td><td>";
	If ($Row['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPIndividualLeadersTeamImage\">";}
	If ($HistoryOutput == False){
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " - " . $Position . " (" . $Row['Abbre'] . ")";		
		If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $Row['NHLID'] != ""){
		echo "<div class=\"HeadshotHide\"><img loading=\"lazy\" src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $Row['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $Row['Name']. "\" class=\"STHSPHPIndividualLeadersHeadshot\" /></div>";}
	}else{
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'];		
	}
	echo "</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['TotalFight'] .  "</td></tr>\n";
		
}}
If ($LoopCount > 1){
	echo "</table>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table>";
}?>
</div>
<div class="DivSection LeaderPlayerStatFW">
<table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false" style="text-align:center;"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['FightWon'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Fight Won">FW</th></tr></thead>
<?php
$Query = GetPlayerQuery($HistoryOutput, "FightW",$TypeText,$LeagueGeneral,$PlayoffString,$MinimumGamePlayer);
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1; $Position = (string)"";	if ($Row['PosC']== "True"){if ($Position == ""){$Position = "C";}else{$Position = $Position . "/C";}}if ($Row['PosLW']== "True"){if ($Position == ""){$Position = "LW";}else{$Position = $Position . "/LW";}}if ($Row['PosRW']== "True"){if ($Position == ""){$Position = "RW";}else{$Position = $Position . "/RW";}}if ($Row['PosD']== "True"){if ($Position == ""){$Position = "D";}else{$Position = $Position . "/D";}}
		
	echo "<tr><td>" . $LoopCount . "</td><td>";
	If ($Row['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPIndividualLeadersTeamImage\">";}
	If ($HistoryOutput == False){
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " - " . $Position . " (" . $Row['Abbre'] . ")";		
		If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $Row['NHLID'] != ""){
		echo "<div class=\"HeadshotHide\"><img loading=\"lazy\" src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $Row['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $Row['Name']. "\" class=\"STHSPHPIndividualLeadersHeadshot\" /></div>";}
	}else{
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'];		
	}
	echo "</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['FightW'] .  "</td></tr>\n";
		
}}
If ($LoopCount > 1){
	echo "</table>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table>";
}?>
</div>
</div>

<h1 class="STHSProIndividualLeader_Players STHSCenter"><?php echo $DynamicTitleLang['Goalies'];?></h1>

<div class="LeaderDiv">
<div class="DivSection"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false" style="text-align:center;"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['SavePCT'];?>
</span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['GoalieName'];?></th><th title="Games Played">GP</th><th title="Save Percentage">PCT</th></tr></thead>
<?php
If ($HistoryOutput == False){
	$Query = "SELECT ROUND((CAST(Goaler" . $TypeText . "Stat.SA - Goaler" . $TypeText . "Stat.GA AS REAL) / (Goaler" . $TypeText . "Stat.SA)),3) AS PCT, Goaler" . $TypeText . "Stat.GP, Goaler" . $TypeText . "Stat.SecondPlay, Goaler" . $TypeText . "Stat.Name, GoalerInfo.NHLID, Goaler" . $TypeText . "Stat.Number, Team" . $TypeText . "Info.Abbre, Team" . $TypeText . "Info.TeamThemeID FROM (GoalerInfo INNER JOIN Goaler" . $TypeText . "Stat ON GoalerInfo.Number = Goaler" . $TypeText . "Stat.Number) LEFT JOIN Team" . $TypeText . "Info ON GoalerInfo.Team = Team" . $TypeText . "Info.Number WHERE (Goaler" . $TypeText . "Stat.SecondPlay >= (" . $MinimumGamePlayer . "*3600)) AND (GoalerInfo.Team > 0) AND (PCT > 0) ORDER BY PCT DESC, Goaler" . $TypeText . "Stat.GP ASC";	
}else{
	$Query = GetGoalieQueryFull("PCT",$TypeText,$LeagueGeneral,$PlayoffString);
}
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$GoalerStat = Null;}else{$GoalerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($GoalerStat) == false){while ($Row = $GoalerStat ->fetchArray()) {
	$LoopCount +=1;
	
	echo "<tr><td>" . $LoopCount . "</td><td>";
	If ($Row['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPIndividualLeadersTeamImage\">";}
	If ($HistoryOutput == False){
		echo "<a href=\"GoalieReport.php?Goalie=" . $Row['Number'] . "\">" . $Row['Name'] . " (" . $Row['Abbre'] . ")";	
		If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $Row['NHLID'] != ""){
		echo "<div class=\"HeadshotHide\"><img loading=\"lazy\" src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $Row['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $Row['Name']. "\" class=\"STHSPHPIndividualLeadersHeadshot\" /></div>";}
	}else{
		echo "<a href=\"GoalieReport.php?Goalie=" . $Row['Number'] . "\">" . $Row['Name'];		
	}
	echo "</a></td><td>" . $Row['GP'] . "</td><td>" . number_Format($Row['PCT'],3) .  "</td></tr>\n";
		
}}
If ($LoopCount > 1){
	echo "</table>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table>";
}?>
</div>
<div class="DivSection"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false" style="text-align:center;"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['GoalsAgainstAverage'];?></span></th></tr>
<tr><th>#</th><th title="Goalie Name"><?php echo $PlayersLang['GoalieName'];?></th><th title="Games Played">GP</th><th title="Goals Against Average">GAA</th></tr></thead>
<?php
If ($HistoryOutput == False){
	$Query = "SELECT ROUND((CAST(Goaler" . $TypeText . "Stat.GA AS REAL) / (Goaler" . $TypeText . "Stat.SecondPlay / 60))*60,3) AS GAA, Goaler" . $TypeText . "Stat.GP, Goaler" . $TypeText . "Stat.SecondPlay, Goaler" . $TypeText . "Stat.Name, GoalerInfo.NHLID, Goaler" . $TypeText . "Stat.Number, Team" . $TypeText . "Info.Abbre, Team" . $TypeText . "Info.TeamThemeID FROM (GoalerInfo INNER JOIN Goaler" . $TypeText . "Stat ON GoalerInfo.Number = Goaler" . $TypeText . "Stat.Number) LEFT JOIN Team" . $TypeText . "Info ON GoalerInfo.Team = Team" . $TypeText . "Info.Number WHERE (Goaler" . $TypeText . "Stat.SecondPlay >= (" . $MinimumGamePlayer . "*3600)) AND (GoalerInfo.Team > 0) AND (GAA > 0) ORDER BY GAA ASC, Goaler" . $TypeText . "Stat.GP ASC";
}else{
	$Query = GetGoalieQueryFull("GAA",$TypeText,$LeagueGeneral,$PlayoffString, " ASC");
}
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$GoalerStat = Null;}else{$GoalerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($GoalerStat) == false){while ($Row = $GoalerStat ->fetchArray()) {
	$LoopCount +=1;
		
	echo "<tr><td>" . $LoopCount . "</td><td>";
	If ($Row['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPIndividualLeadersTeamImage\">";}
	If ($HistoryOutput == False){
		echo "<a href=\"GoalieReport.php?Goalie=" . $Row['Number'] . "\">" . $Row['Name'] . " (" . $Row['Abbre'] . ")";	
		If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $Row['NHLID'] != ""){
		echo "<div class=\"HeadshotHide\"><img loading=\"lazy\" src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $Row['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $Row['Name']. "\" class=\"STHSPHPIndividualLeadersHeadshot\" /></div>";}
	}else{
		echo "<a href=\"GoalieReport.php?Goalie=" . $Row['Number'] . "\">" . $Row['Name'];		
	}
	echo "</a></td><td>" . $Row['GP'] . "</td><td>" . number_Format($Row['GAA'],2) .  "</td></tr>\n";
	
}}
If ($LoopCount > 1){
	echo "</table>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table>";
}?>
</div>
<div class="DivSection"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false" style="text-align:center;"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['MinutesPlayed'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['GoalieName'];?></th><th title="Games Played">GP</th><th title="Minutes Played">MP</th></tr></thead>
<?php
$Query = GetGoalieQuery($HistoryOutput, "SecondPlay",$TypeText,$LeagueGeneral,$PlayoffString,$MinimumGamePlayer);
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$GoalerStat = Null;}else{$GoalerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($GoalerStat) == false){while ($Row = $GoalerStat ->fetchArray()) {
	$LoopCount +=1;
		
	echo "<tr><td>" . $LoopCount . "</td><td>";
	If ($Row['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPIndividualLeadersTeamImage\">";}
	If ($HistoryOutput == False){
		echo "<a href=\"GoalieReport.php?Goalie=" . $Row['Number'] . "\">" . $Row['Name'] . " (" . $Row['Abbre'] . ")";	
		If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $Row['NHLID'] != ""){
		echo "<div class=\"HeadshotHide\"><img loading=\"lazy\" src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $Row['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $Row['Name']. "\" class=\"STHSPHPIndividualLeadersHeadshot\" /></div>";}
	}else{
		echo "<a href=\"GoalieReport.php?Goalie=" . $Row['Number'] . "\">" . $Row['Name'];		
	}
	echo "</a></td><td>" . $Row['GP'] . "</td><td>" . Floor($Row['SecondPlay']/60) .  "</td></tr>\n";
	
}}
If ($LoopCount > 1){
	echo "</table>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table>";
}?>
</div>
<div class="DivSection"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false" style="text-align:center;"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['ShotsAgainst'];?></span></th></tr>
<tr><th>#</th><th title="Goalie Name"><?php echo $PlayersLang['GoalieName'];?></th><th title="Games Played">GP</th><th title="Shots Against">SA</th></tr></thead>
<?php
$Query = GetGoalieQuery($HistoryOutput, "SA",$TypeText,$LeagueGeneral,$PlayoffString,$MinimumGamePlayer);
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$GoalerStat = Null;}else{$GoalerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($GoalerStat) == false){while ($Row = $GoalerStat ->fetchArray()) {
	$LoopCount +=1;
		
	echo "<tr><td>" . $LoopCount . "</td><td>";
	If ($Row['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPIndividualLeadersTeamImage\">";}
	If ($HistoryOutput == False){
		echo "<a href=\"GoalieReport.php?Goalie=" . $Row['Number'] . "\">" . $Row['Name'] . " (" . $Row['Abbre'] . ")";	
		If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $Row['NHLID'] != ""){
		echo "<div class=\"HeadshotHide\"><img loading=\"lazy\" src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $Row['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $Row['Name']. "\" class=\"STHSPHPIndividualLeadersHeadshot\" /></div>";}
	}else{
		echo "<a href=\"GoalieReport.php?Goalie=" . $Row['Number'] . "\">" . $Row['Name'];		
	}
	echo "</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['SA'] .  "</td></tr>\n";
	
}}
If ($LoopCount > 1){
	echo "</table>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table>";
}?>
</div>
<div class="DivSection"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false" style="text-align:center;"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['Shutouts'];?>
</span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['GoalieName'];?></th><th title="Games Played">GP</th><th title="Shutouts">SO</th></tr></thead>
<?php
$Query = GetGoalieQuery($HistoryOutput, "Shootout",$TypeText,$LeagueGeneral,$PlayoffString,$MinimumGamePlayer);
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$GoalerStat = Null;}else{$GoalerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($GoalerStat) == false){while ($Row = $GoalerStat ->fetchArray()) {
	$LoopCount +=1;
		
	echo "<tr><td>" . $LoopCount . "</td><td>";
	If ($Row['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPIndividualLeadersTeamImage\">";}
	If ($HistoryOutput == False){
		echo "<a href=\"GoalieReport.php?Goalie=" . $Row['Number'] . "\">" . $Row['Name'] . " (" . $Row['Abbre'] . ")";	
		If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $Row['NHLID'] != ""){
		echo "<div class=\"HeadshotHide\"><img loading=\"lazy\" src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $Row['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $Row['Name']. "\" class=\"STHSPHPIndividualLeadersHeadshot\" /></div>";}
	}else{
		echo "<a href=\"GoalieReport.php?Goalie=" . $Row['Number'] . "\">" . $Row['Name'];		
	}	
	echo "</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['Shootout'] .  "</td></tr>\n";
		
}}
If ($LoopCount > 1){
	echo "</table>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table>";
}?>
</div>
<div class="DivSection"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false" style="text-align:center;"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['Wins'];?></span></th></tr>
<tr><th>#</th><th title="Goalie Name"><?php echo $PlayersLang['GoalieName'];?></th><th title="Games Played">GP</th><th title="Wins">W</th></tr></thead>
<?php
$Query = GetGoalieQuery($HistoryOutput, "W",$TypeText,$LeagueGeneral,$PlayoffString,$MinimumGamePlayer);
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$GoalerStat = Null;}else{$GoalerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($GoalerStat) == false){while ($Row = $GoalerStat ->fetchArray()) {
	$LoopCount +=1;
		
	echo "<tr><td>" . $LoopCount . "</td><td>";
	If ($Row['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPIndividualLeadersTeamImage\">";}
	If ($HistoryOutput == False){
		echo "<a href=\"GoalieReport.php?Goalie=" . $Row['Number'] . "\">" . $Row['Name'] . " (" . $Row['Abbre'] . ")";	
		If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $Row['NHLID'] != ""){
		echo "<div class=\"HeadshotHide\"><img loading=\"lazy\" src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $Row['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $Row['Name']. "\" class=\"STHSPHPIndividualLeadersHeadshot\" /></div>";}
	}else{
		echo "<a href=\"GoalieReport.php?Goalie=" . $Row['Number'] . "\">" . $Row['Name'];		
	}	
	echo "</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['W'] .  "</td></tr>\n";
		
}}
If ($LoopCount > 1){
	echo "</table>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table>";
}?>
</div>
<div class="DivSection"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false" style="text-align:center;"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['PenaltyShotsSavePCT'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['GoalieName'];?></th><th title="Penalty Shots Against">PSA</th><th title="Losses">PS %</th></tr></thead>
<?php
If ($HistoryOutput == False){
	$Query = "SELECT ROUND((CAST(Goaler" . $TypeText . "Stat.PenalityShotsShots - Goaler" . $TypeText . "Stat.PenalityShotsGoals AS REAL) / (Goaler" . $TypeText . "Stat.PenalityShotsShots)),3) AS PenalityShotsPCT, Goaler" . $TypeText . "Stat.PenalityShotsShots, Goaler" . $TypeText . "Stat.SecondPlay, Goaler" . $TypeText . "Stat.Name, GoalerInfo.NHLID, Goaler" . $TypeText . "Stat.Number, Team" . $TypeText . "Info.Abbre, Team" . $TypeText . "Info.TeamThemeID FROM (GoalerInfo INNER JOIN Goaler" . $TypeText . "Stat ON GoalerInfo.Number = Goaler" . $TypeText . "Stat.Number) LEFT JOIN Team" . $TypeText . "Info ON GoalerInfo.Team = Team" . $TypeText . "Info.Number WHERE (Goaler" . $TypeText . "Stat.SecondPlay >= (" . $MinimumGamePlayer . "*3600)) AND (GoalerInfo.Team > 0) AND (PenalityShotsPCT > 0) ORDER BY PenalityShotsPCT DESC, Goaler" . $TypeText . "Stat.PenalityShotsShots DESC";
}else{
	$Query = GetGoalieQueryFull("PenalityShotsPCT",$TypeText,$LeagueGeneral,$PlayoffString);
}
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$GoalerStat = Null;}else{$GoalerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($GoalerStat) == false){while ($Row = $GoalerStat ->fetchArray()) {
	$LoopCount +=1;
		
	echo "<tr><td>" . $LoopCount . "</td><td>";
	If ($Row['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPIndividualLeadersTeamImage\">";}
	If ($HistoryOutput == False){
		echo "<a href=\"GoalieReport.php?Goalie=" . $Row['Number'] . "\">" . $Row['Name'] . " (" . $Row['Abbre'] . ")";	
		If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $Row['NHLID'] != ""){
		echo "<div class=\"HeadshotHide\"><img loading=\"lazy\" src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $Row['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $Row['Name']. "\" class=\"STHSPHPIndividualLeadersHeadshot\" /></div>";}
	}else{
		echo "<a href=\"GoalieReport.php?Goalie=" . $Row['Number'] . "\">" . $Row['Name'];		
	}
	echo "</a></td><td>" . $Row['PenalityShotsShots'] . "</td><td>" . number_Format($Row['PenalityShotsPCT'],3) .  "</td></tr>\n";
	
}}
If ($LoopCount > 1){
	echo "</table>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table>";
}?>
</div>
<div class="DivSection"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false" style="text-align:center;"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['Losses'];?></span></th></tr>
<tr><th>#</th><th title="Goalie Name"><?php echo $PlayersLang['GoalieName'];?></th><th title="Games Played">GP</th><th title="Losses">L</th></tr></thead>
<?php
$Query = GetGoalieQuery($HistoryOutput, "L",$TypeText,$LeagueGeneral,$PlayoffString,$MinimumGamePlayer);
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$GoalerStat = Null;}else{$GoalerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($GoalerStat) == false){while ($Row = $GoalerStat ->fetchArray()) {
	$LoopCount +=1;
		
	echo "<tr><td>" . $LoopCount . "</td><td>";
	If ($Row['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPIndividualLeadersTeamImage\">";}
	If ($HistoryOutput == False){
		echo "<a href=\"GoalieReport.php?Goalie=" . $Row['Number'] . "\">" . $Row['Name'] . " (" . $Row['Abbre'] . ")";	
		If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $Row['NHLID'] != ""){
		echo "<div class=\"HeadshotHide\"><img loading=\"lazy\" src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $Row['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $Row['Name']. "\" class=\"STHSPHPIndividualLeadersHeadshot\" /></div>";}
	}else{
		echo "<a href=\"GoalieReport.php?Goalie=" . $Row['Number'] . "\">" . $Row['Name'];		
	}
	echo "</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['L'] .  "</td></tr>\n";
	
}}
If ($LoopCount > 1){
	echo "</table>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table>";
}?>
</div>
</div>

<script>
$(function() {
  $(".STHSIndividualLeader_Table").tablesorter();
});
document.addEventListener("DOMContentLoaded", function () {
    let sections = document.querySelectorAll(".DivSection.LeaderPlayerStatTA, .DivSection.LeaderPlayerStatGA, .DivSection.LeaderPlayerStatPSG, .DivSection.LeaderPlayerStatRookie");
    sections.forEach(section => {
        if (section.querySelector(".STHSCenter")?.textContent.trim() === "No Result") {section.style.display = "none";}
    });
});
</script>

</div>

<?php include "Footer.php";?>
