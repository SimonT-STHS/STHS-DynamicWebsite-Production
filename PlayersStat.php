<?php include "Header.php";
$Team = (integer)-1; /* -1 All Team */
$Title = (string)"";
$Search = (boolean)False;
$HistoryOutput = (boolean)False;
$CareerLeaderSubPrintOut = (int)0;
include "SearchPossibleOrderField.php";
If (file_exists($DatabaseFile) == false){
	Goto STHSErrorPlayerStat;
}else{try{
	$TypeText = (string)"Pro";$TitleType = $DynamicTitleLang['Pro'];
	$ACSQuery = (boolean)FALSE;/* The SQL Query must be Ascending Order and not Descending */
	$MaximumResult = (integer)0;
	$MinimumGP = (integer)1;
	$OrderByField = (string)"P";
	$OrderByFieldText = (string)"Points";
	$OrderByInput = (string)"";
	$TitleOverwrite = (string)"";
	$MinGP = (boolean)FALSE;
	if(isset($_GET['Farm'])){$TypeText = "Farm";$TitleType = $DynamicTitleLang['Farm'];}
	if(isset($_GET['ACS'])){$ACSQuery= TRUE;}
	if(isset($_GET['Max'])){$MaximumResult = filter_var($_GET['Max'], FILTER_SANITIZE_NUMBER_INT);} 
	if(isset($_GET['Order'])){$OrderByInput  = filter_var($_GET['Order'], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH || FILTER_FLAG_NO_ENCODE_QUOTES || FILTER_FLAG_STRIP_BACKTICK);} 
	if(isset($_GET['Team'])){$Team = filter_var($_GET['Team'], FILTER_SANITIZE_NUMBER_INT);} 
	if(isset($_GET['Title'])){$TitleOverwrite  = filter_var($_GET['Title'], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH || FILTER_FLAG_NO_ENCODE_QUOTES || FILTER_FLAG_STRIP_BACKTICK);} 
	$LeagueName = (string)"";

	foreach ($PlayersStatPossibleOrderField as $Value) {
		If (strtoupper($Value[0]) == strtoupper($OrderByInput)){
			$OrderByField = $Value[0];
			$OrderByFieldText = $Value[1];
			Break;
		}
	}
	
	$Playoff = (boolean)False;
	$PlayoffString = (string)"False";
	$Year = (integer)0;	
	if(isset($_GET['Playoff'])){$Playoff=True;$PlayoffString="True";}
	if(isset($_GET['Year'])){$Year = filter_var($_GET['Year'], FILTER_SANITIZE_NUMBER_INT);} 

	If($Year > 0 AND file_exists($CareerStatDatabaseFile) == true){  /* History Database */
		$db = new SQLite3($CareerStatDatabaseFile);
		$CareerDBFormatV2CheckCheck = $db->querySingle("SELECT Count(name) AS CountName FROM sqlite_master WHERE type='table' AND name='LeagueGeneral'",true);
		If ($CareerDBFormatV2CheckCheck['CountName'] == 1){
			$HistoryOutput = True;
			If ($Year == 9999 Or $Year == 9998){
				/* All Year : 9999 = All Season Per Year / 9998 = All Season Merge  */
								
				$dbLive = new SQLite3($DatabaseFile);
				$Query = "Select Name, LeagueYearOutput, PlayOffStarted, PreSeasonSchedule from LeagueGeneral";
				$LeagueGeneral = $dbLive ->querySingle($Query,true);		
				$LeagueName = $LeagueGeneral['Name'];
				
				$db->query("ATTACH DATABASE '".realpath($DatabaseFile)."' AS CurrentDB");
				
				If ($Playoff=="True"){$Title = $SearchLang['Playoff'] .  " ";}
				If ($Year == 9999 ){
					$Title = $Title . $SearchLang['AllSeasonPerYear'] . " - ";
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
				
				If($MaximumResult == 0){$Title = $Title . $DynamicTitleLang['All'];}else{$Title = $Title . $DynamicTitleLang['Top'] . $MaximumResult . " ";}
				
				If ($Year == 9998 ){
					$Query = "SELECT MainTable.Number, MainTable.UniqueID, '0' As TeamThemeID, MainTable.Name, MainTable.Team, MainTable.TeamName, Sum(MainTable.GP) AS GP, Sum(MainTable.Shots) AS Shots, Sum(MainTable.G) AS G, Sum(MainTable.A) AS A, Sum(MainTable.P) AS P, Sum(MainTable.PlusMinus) AS PlusMinus, Sum(MainTable.Pim) AS Pim, Sum(MainTable.Pim5) AS Pim5, Sum(MainTable.ShotsBlock) AS ShotsBlock, Sum(MainTable.OwnShotsBlock) AS OwnShotsBlock, Sum(MainTable.OwnShotsMissGoal) AS OwnShotsMissGoal, Sum(MainTable.Hits) AS Hits, Sum(MainTable.HitsTook) AS HitsTook, Sum(MainTable.GW) AS GW, Sum(MainTable.GT) AS GT, Sum(MainTable.FaceOffWon) AS FaceOffWon, Sum(MainTable.FaceOffTotal) AS FaceOffTotal, Sum(MainTable.PenalityShotsScore) AS PenalityShotsScore, Sum(MainTable.PenalityShotsTotal) AS PenalityShotsTotal, Sum(MainTable.EmptyNetGoal) AS EmptyNetGoal, Sum(MainTable.SecondPlay) AS SecondPlay, Sum(MainTable.HatTrick) AS HatTrick, Sum(MainTable.PPG) AS PPG, Sum(MainTable.PPA) AS PPA, Sum(MainTable.PPP) AS PPP, Sum(MainTable.PPShots) AS PPShots, Sum(MainTable.PPSecondPlay) AS PPSecondPlay, Sum(MainTable.PKG) AS PKG, Sum(MainTable.PKA) AS PKA, Sum(MainTable.PKP) AS PKP, Sum(MainTable.PKShots) AS PKShots, Sum(MainTable.PKSecondPlay) AS PKSecondPlay, Sum(MainTable.GiveAway) AS GiveAway, Sum(MainTable.TakeAway) AS TakeAway, Sum(MainTable.PuckPossesionTime) AS PuckPossesionTime, Sum(MainTable.FightW) AS FightW, Sum(MainTable.FightL) AS FightL, Sum(MainTable.FightT) AS FightT, Sum(MainTable.Star1) AS Star1, Sum(MainTable.Star2) AS Star2, Sum(MainTable.Star3) AS Star3,  ROUND((CAST(Sum(MainTable.G) AS REAL) / (Sum(MainTable.Shots)))*100,2) AS ShotsPCT, ROUND((CAST(Sum(MainTable.SecondPlay) AS REAL) / 60 / (Sum(MainTable.GP))),2) AS AMG,ROUND((CAST(Sum(MainTable.FaceOffWon) AS REAL) / (Sum(MainTable.FaceOffTotal)))*100,2) as FaceoffPCT,ROUND((CAST(Sum(MainTable.P) AS REAL) / (Sum(MainTable.SecondPlay)) * 60 * 20),2) AS P20";
				}else{
					$Query = "Select MainTable.*";
				}
				
				if($LeagueGeneral['PlayOffStarted'] == $PlayoffString AND $LeagueGeneral['PreSeasonSchedule'] == "False"){
					/* Regular Query */
					$Query = $Query . " FROM (Select MainLive.* FROM (SELECT '". $LeagueGeneral['LeagueYearOutput'] . "' as Year, Player" . $TypeText . "Stat.Number, Player" . $TypeText . "Stat.UniqueID, '0' As TeamThemeID, Player" . $TypeText . "Stat.Name, Player" . $TypeText . "Stat.GP, Player" . $TypeText . "Stat.Shots, Player" . $TypeText . "Stat.G, Player" . $TypeText . "Stat.A, Player" . $TypeText . "Stat.P, Player" . $TypeText . "Stat.PlusMinus, Player" . $TypeText . "Stat.Pim, Player" . $TypeText . "Stat.Pim5, Player" . $TypeText . "Stat.ShotsBlock, Player" . $TypeText . "Stat.OwnShotsBlock, Player" . $TypeText . "Stat.OwnShotsMissGoal, Player" . $TypeText . "Stat.Hits, Player" . $TypeText . "Stat.HitsTook, Player" . $TypeText . "Stat.GW, Player" . $TypeText . "Stat.GT, Player" . $TypeText . "Stat.FaceOffWon, Player" . $TypeText . "Stat.FaceOffTotal, Player" . $TypeText . "Stat.PenalityShotsScore, Player" . $TypeText . "Stat.PenalityShotsTotal, Player" . $TypeText . "Stat.EmptyNetGoal, Player" . $TypeText . "Stat.SecondPlay, Player" . $TypeText . "Stat.HatTrick, Player" . $TypeText . "Stat.PPG, Player" . $TypeText . "Stat.PPA, Player" . $TypeText . "Stat.PPP, Player" . $TypeText . "Stat.PPShots, Player" . $TypeText . "Stat.PPSecondPlay, Player" . $TypeText . "Stat.PKG, Player" . $TypeText . "Stat.PKA, Player" . $TypeText . "Stat.PKP, Player" . $TypeText . "Stat.PKShots, Player" . $TypeText . "Stat.PKSecondPlay, Player" . $TypeText . "Stat.GiveAway, Player" . $TypeText . "Stat.TakeAway, Player" . $TypeText . "Stat.PuckPossesionTime, Player" . $TypeText . "Stat.FightW, Player" . $TypeText . "Stat.FightL, Player" . $TypeText . "Stat.FightT, Player" . $TypeText . "Stat.Star1, Player" . $TypeText . "Stat.Star2, Player" . $TypeText . "Stat.Star3, PlayerInfo.PosC, PlayerInfo.PosLW, PlayerInfo.PosRW, PlayerInfo.PosD, PlayerInfo.Team, PlayerInfo.TeamName, PlayerInfo.Rookie, ROUND((CAST(Player" . $TypeText . "Stat.G AS REAL) / (Player" . $TypeText . "Stat.Shots))*100,2) AS ShotsPCT, ROUND((CAST(Player" . $TypeText . "Stat.SecondPlay AS REAL) / 60 / (Player" . $TypeText . "Stat.GP)),2) AS AMG,ROUND((CAST(Player" . $TypeText . "Stat.FaceOffWon AS REAL) / (Player" . $TypeText . "Stat.FaceOffTotal))*100,2) as FaceoffPCT,ROUND((CAST(Player" . $TypeText . "Stat.P AS REAL) / (Player" . $TypeText . "Stat.SecondPlay) * 60 * 20),2) AS P20 FROM PlayerInfo INNER JOIN Player" . $TypeText . "Stat ON PlayerInfo.Number = Player" . $TypeText . "Stat.Number WHERE Player" . $TypeText . "Stat.GP > 0) AS MainLive UNION ALL Select MainHistory.* FROM (SELECT Player" . $TypeText . "StatHistory.Year, Player" . $TypeText . "StatHistory.Number, Player" . $TypeText . "StatHistory.UniqueID, '0' As TeamThemeID, Player" . $TypeText . "StatHistory.Name, Player" . $TypeText . "StatHistory.GP, Player" . $TypeText . "StatHistory.Shots, Player" . $TypeText . "StatHistory.G, Player" . $TypeText . "StatHistory.A, Player" . $TypeText . "StatHistory.P, Player" . $TypeText . "StatHistory.PlusMinus, Player" . $TypeText . "StatHistory.Pim, Player" . $TypeText . "StatHistory.Pim5, Player" . $TypeText . "StatHistory.ShotsBlock, Player" . $TypeText . "StatHistory.OwnShotsBlock, Player" . $TypeText . "StatHistory.OwnShotsMissGoal, Player" . $TypeText . "StatHistory.Hits, Player" . $TypeText . "StatHistory.HitsTook, Player" . $TypeText . "StatHistory.GW, Player" . $TypeText . "StatHistory.GT, Player" . $TypeText . "StatHistory.FaceOffWon, Player" . $TypeText . "StatHistory.FaceOffTotal, Player" . $TypeText . "StatHistory.PenalityShotsScore, Player" . $TypeText . "StatHistory.PenalityShotsTotal, Player" . $TypeText . "StatHistory.EmptyNetGoal, Player" . $TypeText . "StatHistory.SecondPlay, Player" . $TypeText . "StatHistory.HatTrick, Player" . $TypeText . "StatHistory.PPG, Player" . $TypeText . "StatHistory.PPA, Player" . $TypeText . "StatHistory.PPP, Player" . $TypeText . "StatHistory.PPShots, Player" . $TypeText . "StatHistory.PPSecondPlay, Player" . $TypeText . "StatHistory.PKG, Player" . $TypeText . "StatHistory.PKA, Player" . $TypeText . "StatHistory.PKP, Player" . $TypeText . "StatHistory.PKShots, Player" . $TypeText . "StatHistory.PKSecondPlay, Player" . $TypeText . "StatHistory.GiveAway, Player" . $TypeText . "StatHistory.TakeAway, Player" . $TypeText . "StatHistory.PuckPossesionTime, Player" . $TypeText . "StatHistory.FightW, Player" . $TypeText . "StatHistory.FightL, Player" . $TypeText . "StatHistory.FightT, Player" . $TypeText . "StatHistory.Star1, Player" . $TypeText . "StatHistory.Star2, Player" . $TypeText . "StatHistory.Star3, PlayerInfoHistory.PosC, PlayerInfoHistory.PosLW, PlayerInfoHistory.PosRW, PlayerInfoHistory.PosD, PlayerInfoHistory.Team, PlayerInfoHistory.ProTeamName AS TeamName, PlayerInfoHistory.Rookie,  ROUND((CAST(Player" . $TypeText . "StatHistory.G AS REAL) / (Player" . $TypeText . "StatHistory.Shots))*100,2) AS ShotsPCT, ROUND((CAST(Player" . $TypeText . "StatHistory.SecondPlay AS REAL) / 60 / (Player" . $TypeText . "StatHistory.GP)),2) AS AMG,ROUND((CAST(Player" . $TypeText . "StatHistory.FaceOffWon AS REAL) / (Player" . $TypeText . "StatHistory.FaceOffTotal))*100,2) as FaceoffPCT,ROUND((CAST(Player" . $TypeText . "StatHistory.P AS REAL) / (Player" . $TypeText . "StatHistory.SecondPlay) * 60 * 20),2) AS P20 FROM PlayerInfoHistory INNER JOIN Player" . $TypeText . "StatHistory ON PlayerInfoHistory.Number = Player" . $TypeText . "StatHistory.Number AND PlayerInfoHistory.Year = Player" . $TypeText . "StatHistory.Year AND PlayerInfoHistory.Playoff = Player" . $TypeText . "StatHistory.Playoff  WHERE Player" . $TypeText . "StatHistory.GP > 0 AND PlayerInfoHistory.Playoff = '" . $PlayoffString. "') AS MainHistory) AS MainTable";
				}else{
					/* Requesting Playoff While in Season or Requesting Season while in Playoff or In Pre-Season Mode - Do not fetch data from live database */
					$Query = $Query . " FROM (SELECT Player" . $TypeText . "StatHistory.Year, Player" . $TypeText . "StatHistory.Number, Player" . $TypeText . "StatHistory.UniqueID, '0' As TeamThemeID, Player" . $TypeText . "StatHistory.Name, Player" . $TypeText . "StatHistory.GP, Player" . $TypeText . "StatHistory.Shots, Player" . $TypeText . "StatHistory.G, Player" . $TypeText . "StatHistory.A, Player" . $TypeText . "StatHistory.P, Player" . $TypeText . "StatHistory.PlusMinus, Player" . $TypeText . "StatHistory.Pim, Player" . $TypeText . "StatHistory.Pim5, Player" . $TypeText . "StatHistory.ShotsBlock, Player" . $TypeText . "StatHistory.OwnShotsBlock, Player" . $TypeText . "StatHistory.OwnShotsMissGoal, Player" . $TypeText . "StatHistory.Hits, Player" . $TypeText . "StatHistory.HitsTook, Player" . $TypeText . "StatHistory.GW, Player" . $TypeText . "StatHistory.GT, Player" . $TypeText . "StatHistory.FaceOffWon, Player" . $TypeText . "StatHistory.FaceOffTotal, Player" . $TypeText . "StatHistory.PenalityShotsScore, Player" . $TypeText . "StatHistory.PenalityShotsTotal, Player" . $TypeText . "StatHistory.EmptyNetGoal, Player" . $TypeText . "StatHistory.SecondPlay, Player" . $TypeText . "StatHistory.HatTrick, Player" . $TypeText . "StatHistory.PPG, Player" . $TypeText . "StatHistory.PPA, Player" . $TypeText . "StatHistory.PPP, Player" . $TypeText . "StatHistory.PPShots, Player" . $TypeText . "StatHistory.PPSecondPlay, Player" . $TypeText . "StatHistory.PKG, Player" . $TypeText . "StatHistory.PKA, Player" . $TypeText . "StatHistory.PKP, Player" . $TypeText . "StatHistory.PKShots, Player" . $TypeText . "StatHistory.PKSecondPlay, Player" . $TypeText . "StatHistory.GiveAway, Player" . $TypeText . "StatHistory.TakeAway, Player" . $TypeText . "StatHistory.PuckPossesionTime, Player" . $TypeText . "StatHistory.FightW, Player" . $TypeText . "StatHistory.FightL, Player" . $TypeText . "StatHistory.FightT, Player" . $TypeText . "StatHistory.Star1, Player" . $TypeText . "StatHistory.Star2, Player" . $TypeText . "StatHistory.Star3, PlayerInfoHistory.PosC, PlayerInfoHistory.PosLW, PlayerInfoHistory.PosRW, PlayerInfoHistory.PosD, PlayerInfoHistory.Team, PlayerInfoHistory.ProTeamName AS TeamName, PlayerInfoHistory.Rookie, ROUND((CAST(Player" . $TypeText . "StatHistory.G AS REAL) / (Player" . $TypeText . "StatHistory.Shots))*100,2) AS ShotsPCT, ROUND((CAST(Player" . $TypeText . "StatHistory.SecondPlay AS REAL) / 60 / (Player" . $TypeText . "StatHistory.GP)),2) AS AMG,ROUND((CAST(Player" . $TypeText . "StatHistory.FaceOffWon AS REAL) / (Player" . $TypeText . "StatHistory.FaceOffTotal))*100,2) as FaceoffPCT,ROUND((CAST(Player" . $TypeText . "StatHistory.P AS REAL) / (Player" . $TypeText . "StatHistory.SecondPlay) * 60 * 20),2) AS P20 FROM PlayerInfoHistory INNER JOIN Player" . $TypeText . "StatHistory ON PlayerInfoHistory.Number = Player" . $TypeText . "StatHistory.Number AND PlayerInfoHistory.Year = Player" . $TypeText . "StatHistory.Year AND PlayerInfoHistory.Playoff = Player" . $TypeText . "StatHistory.Playoff WHERE Player" . $TypeText . "StatHistory.GP > 0 AND PlayerInfoHistory.Playoff = '" . $PlayoffString. "') AS MainTable";
				}
				if($Team > 0){$Query = $Query . " WHERE MainTable.Team = " . $Team;}
				
				If ($OrderByInput == "" AND $ACSQuery == FALSE){
					/* Default Sorting Hardcode  */
					$ACSQuery = TRUE;
					if($Year == 9998){
						$Query = $Query . " GROUP BY UniqueID ORDER BY Sum(MainTable.P) DESC, Sum(MainTable.GP)";
					}else{
						$Query = $Query . " ORDER BY MainTable.P DESC, MainTable.GP";
					}
				}else{
					if($Year == 9998){
						$Query = $Query . " GROUP BY UniqueID ORDER BY Sum(MainTable." . $OrderByField . ")";
					}else{
						$Query = $Query . " ORDER BY MainTable." . $OrderByField;
					}
				}
				
				$Title = $Title  . $DynamicTitleLang['PlayersStat'] . $TitleType;	

				/* Order by  */				
				If ($ACSQuery == TRUE){
					$Query = $Query . " ASC";
					$Title = $Title . $DynamicTitleLang['InAscendingOrderBy'] . $OrderByFieldText;
				}else{
					$Query = $Query . " DESC";
					$Title = $Title . $DynamicTitleLang['InDecendingOrderBy'] . $OrderByFieldText;
				}
				If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
				$PlayerStat = $db->query($Query);

				If ($Year == 9999 ){
					$CareerLeaderSubPrintOut = 2;
				}elseif ($Year == 9998){
					$CareerLeaderSubPrintOut = 3;
				}				
			
			}else{
				/* Specific Year */
				
				$Query = "Select Name,PlayOffStarted from LeagueGeneral WHERE Year = " . $Year . " And Playoff = '" . $PlayoffString. "'";
				$LeagueGeneral = $db->querySingle($Query,true);		
				
				//Confirm Valid Data Found
				$CareerDBFormatV2CheckCheck = $db->querySingle("Select Count(Name) As CountName from LeagueGeneral  WHERE Year = " . $Year . " And Playoff = '" . $PlayoffString. "'",true);
				If ($CareerDBFormatV2CheckCheck['CountName'] == 1){$LeagueName = $LeagueGeneral['Name'];}else{$Year = (integer)0;$HistoryOutput = (boolean)False;Goto RegularSeason;}
					
				if(isset($_GET['MinGP'])){
					$MinGP = True;
					$Query = "Select " . $TypeText . "MinimumGamePlayerLeader AS MinimumGamePlayerLeader from LeagueOutputOption WHERE Year = " . $Year . " And Playoff = '" . $PlayoffString. "'";
					$LeagueOutputOption = $db->querySingle($Query,true);	
					$MinimumGP = $LeagueOutputOption['MinimumGamePlayerLeader'];
				}
							
				If($MaximumResult == 0){$Title = $DynamicTitleLang['All'];}else{$Title = $DynamicTitleLang['Top'] . $MaximumResult . " ";}
				
				$Query = "SELECT Player" . $TypeText . "StatHistory.*, PlayerInfoHistory.PosC, PlayerInfoHistory.PosLW, PlayerInfoHistory.PosRW, PlayerInfoHistory.PosD, PlayerInfoHistory.ProTeamName AS TeamName, '0' As TeamThemeID, ROUND((CAST(Player" . $TypeText . "StatHistory.G AS REAL) / (Player" . $TypeText . "StatHistory.Shots))*100,2) AS ShotsPCT, ROUND((CAST(Player" . $TypeText . "StatHistory.SecondPlay AS REAL) / 60 / (Player" . $TypeText . "StatHistory.GP)),2) AS AMG,ROUND((CAST(Player" . $TypeText . "StatHistory.FaceOffWon AS REAL) / (Player" . $TypeText . "StatHistory.FaceOffTotal))*100,2) as FaceoffPCT,ROUND((CAST(Player" . $TypeText . "StatHistory.P AS REAL) / (Player" . $TypeText . "StatHistory.SecondPlay) * 60 * 20),2) AS P20 FROM PlayerInfoHistory INNER JOIN Player" . $TypeText . "StatHistory ON PlayerInfoHistory.Number = Player" . $TypeText . "StatHistory.Number WHERE PlayerInfoHistory.Retire = 'False' AND Player" . $TypeText . "StatHistory.GP >= " . $MinimumGP . " AND PlayerInfoHistory.Year = " . $Year . " AND PlayerInfoHistory.Playoff = '" . $PlayoffString. "' AND Player" . $TypeText . "StatHistory.Year = " . $Year . " AND Player" . $TypeText . "StatHistory.Playoff = '" . $PlayoffString. "'";
				if($Team > 0){
					$Query = $Query . " AND PlayerInfoHistory.Team = " . $Team;
					$QueryTeam = "SELECT Name FROM Team" . $TypeText . "InfoHistory WHERE Number = " . $Team . " AND Year = " . $Year . " And Playoff = '" . $PlayoffString. "'";
					$TeamName = $db->querySingle($QueryTeam,true);	
					$Title = $Title . $TeamName['Name'];		
				}
				
				If ($OrderByField == "ShotsPCT" OR $OrderByField == "AMG" OR $OrderByField == "FaceoffPCT" OR $OrderByField == "P20"){$Query = $Query . " ORDER BY " . $OrderByField;}else{$Query = $Query . " ORDER BY Player" . $TypeText . "StatHistory." . $OrderByField;}
				$Title = $Title  . $DynamicTitleLang['PlayersStat'] . $TitleType;		
				
				/* Order by  */
				If ($ACSQuery == TRUE){
					$Query = $Query . " ASC";
					$Title = $Title . $DynamicTitleLang['InAscendingOrderBy'] . $OrderByFieldText;
				}else{
					$Query = $Query . " DESC";
					$Title = $Title . $DynamicTitleLang['InDecendingOrderBy'] . $OrderByFieldText;
				}
				If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
				$PlayerStat = $db->query($Query);

				$Title = $Title  . " - " . $SearchLang['Year'] . " " . $Year;
				if(isset($_GET['MinGP'])){$Title = $Title . " - " . $TopMenuLang['MinimumGamesPlayed'] . $MinimumGP;}
				If ($Playoff == True){$Title = $Title . $TopMenuLang['Playoff'];}
			}
			
			echo "<title>" . $LeagueName . " - " . $Title . "</title>";			
			
		}else{
			Goto RegularSeason;
		}
	}else{
		/* Regular Season Database Only */	
		RegularSeason:			
	
		$db = new SQLite3($DatabaseFile);
		$Query = "Select Name,PlayOffStarted from LeagueGeneral";
		$LeagueGeneral = $db->querySingle($Query,true);		
		$LeagueName = $LeagueGeneral['Name'];
			
		if(isset($_GET['MinGP'])){
			$MinGP = True;
			$Query = "Select " . $TypeText . "MinimumGamePlayerLeader AS MinimumGamePlayerLeader from LeagueOutputOption";
			$LeagueOutputOption = $db->querySingle($Query,true);	
			$MinimumGP = $LeagueOutputOption['MinimumGamePlayerLeader'];
		}
		
		if(isset($_GET['Season'])){$TypeText = $TypeText . "Season";}
		
		If($MaximumResult == 0){$Title = $DynamicTitleLang['All'];}else{$Title = $DynamicTitleLang['Top'] . $MaximumResult . " ";}
		
		$Query = "SELECT Player" . $TypeText . "Stat.*, PlayerInfo.PosC, PlayerInfo.PosLW, PlayerInfo.PosRW, PlayerInfo.PosD, PlayerInfo.TeamName, PlayerInfo.TeamThemeID, ROUND((CAST(Player" . $TypeText . "Stat.G AS REAL) / (Player" . $TypeText . "Stat.Shots))*100,2) AS ShotsPCT, ROUND((CAST(Player" . $TypeText . "Stat.SecondPlay AS REAL) / 60 / (Player" . $TypeText . "Stat.GP)),2) AS AMG,ROUND((CAST(Player" . $TypeText . "Stat.FaceOffWon AS REAL) / (Player" . $TypeText . "Stat.FaceOffTotal))*100,2) as FaceoffPCT,ROUND((CAST(Player" . $TypeText . "Stat.P AS REAL) / (Player" . $TypeText . "Stat.SecondPlay) * 60 * 20),2) AS P20 FROM PlayerInfo INNER JOIN Player" . $TypeText . "Stat ON PlayerInfo.Number = Player" . $TypeText . "Stat.Number WHERE PlayerInfo.Retire = 'False' AND Player" . $TypeText . "Stat.GP >= " . $MinimumGP;
		if($Team > 0){
			$Query = $Query . " AND PlayerInfo.Team = " . $Team;
			$QueryTeam = "SELECT Name FROM Team" . $TypeText . "Info WHERE Number = " . $Team;
			$TeamName = $db->querySingle($QueryTeam,true);	
			$Title = $Title . $TeamName['Name'];		
		}
		
		If ($OrderByField == "ShotsPCT" OR $OrderByField == "AMG" OR $OrderByField == "FaceoffPCT" OR $OrderByField == "P20"){$Query = $Query . " ORDER BY " . $OrderByField;}else{$Query = $Query . " ORDER BY Player" . $TypeText . "Stat." . $OrderByField;}
		$Title = $Title  . $DynamicTitleLang['PlayersStat'] . $TitleType;		
		
		/* Order by  */
		If ($ACSQuery == TRUE){
			$Query = $Query . " ASC";
			$Title = $Title . $DynamicTitleLang['InAscendingOrderBy'] . $OrderByFieldText;
		}else{
			$Query = $Query . " DESC";
			$Title = $Title . $DynamicTitleLang['InDecendingOrderBy'] . $OrderByFieldText;
		}
		If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
		$PlayerStat = $db->query($Query);
		
		if(isset($_GET['MinGP'])){$Title = $Title . " - " . $TopMenuLang['MinimumGamesPlayed'] . $MinimumGP;}
		
		/* OverWrite Title if information is get from PHP GET */
		if($TitleOverwrite <> ""){$Title = $TitleOverwrite;}
		echo "<title>" . $LeagueName . " - " . $Title . "</title>";
	}
} catch (Exception $e) {
STHSErrorPlayerStat:
	$LeagueName = $DatabaseNotFound;
	$PlayerStat = Null;
	echo "<title>" . $DatabaseNotFound . "</title>";
	$Title = $DatabaseNotFound;
}}?>
</head><body>
<?php include "Menu.php";?>
<script>
$(function() {
  $.tablesorter.addWidget({ id: "numbering",format: function(table) {var c = table.config;$("tr:visible", table.tBodies[0]).each(function(i) {$(this).find('td').eq(0).text(i + 1);});}});
  $(".STHSPHPAllPlayerStat_Table").tablesorter({
	widgets: ['numbering', 'columnSelector', 'stickyHeaders', 'filter', 'output'],
    widgetOptions : {
      columnSelector_container : $('#tablesorter_ColumnSelector'),
      columnSelector_layout : '<label><input type="checkbox">{name}</label>',
      columnSelector_name  : 'title',
      columnSelector_mediaquery: true,
      columnSelector_mediaqueryName: 'Automatic',
      columnSelector_mediaqueryState: true,
      columnSelector_mediaqueryHidden: true,
      columnSelector_breakpoints : [ '20em', '40em', '60em', '80em', '90em', '95em' ],
	  filter_columnFilters: true,
      filter_placeholder: { search : '<?php echo $TableSorterLang['Search'];?>' },
	  filter_searchDelay : 1000,	  
      filter_reset: '.tablesorter_Reset',	 
	  output_delivery: 'd',
	  output_saveFileName: 'STHSPlayerStat.CSV'
    }
  });
  $('.download').click(function(){
      var $table = $('.STHSPHPAllPlayerStat_Table'),
      wo = $table[0].config.widgetOptions;
      $table.trigger('outputTable');
      return false;
  });  
});
</script>

<div style="width:99%;margin:auto;">
<?php echo "<h1>" . $Title . "</h1>";?>
<div id="ReQueryDiv" style="display:none;">
<?php If($HistoryOutput == False){
	include "SearchPlayersStat.php";
}else{
	include "SearchHistorySub.php";
	include "SearchHistoryPlayersStat.php";
	$Team = (integer)-1;
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

<table class="tablesorter STHSPHPAllPlayerStat_Table"><thead><tr>
	<?php include "PlayersStatSub.php";?>
</tbody></table>	</div>
<br />

<?php include "Footer.php";?>
