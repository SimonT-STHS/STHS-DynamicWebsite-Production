<?php include "Header.php";?>
<?php
/*
Syntax to call this webpage should be FarmTeam.php?Team=2 where only the number change and it's based on the Tean Number Field.
SubMenu : 0 = Home / 1 = Roster / 2 = Scoring / 3 = PlayerInfo / 4 = Lines / 5 = Team Stats / 6 = Schedule /7 = Finance / 8 = CareerStat
*/
$Team = (integer)0;
$TypeText = (string)"Farm";
$LeagueName = (string)"";
$OtherTeam = (integer)0;
$TeamCareerStatFound = (boolean)false;
$Query = (string)"";
$TeamName = $TeamLang['IncorrectTeam'];
$CareerLeaderSubPrintOut = (int)0;
if(isset($_GET['Team'])){$Team = filter_var($_GET['Team'], FILTER_SANITIZE_NUMBER_INT);} 
$SubMenu = 0;
if(isset($_GET['SubMenu'])){$SubMenu = filter_var($_GET['SubMenu'], FILTER_SANITIZE_NUMBER_INT);}
if($SubMenu < 0 OR $SubMenu > 8){$SubMenu = 0;} 

If (file_exists($DatabaseFile) == false){
	$Team = 0;
	$TeamName = $DatabaseNotFound;
}else{
	$db = new SQLite3($DatabaseFile);
}
If ($Team == 0 OR $Team > 100){
	$TeamInfo = Null;
	$TeamProInfo = Null;			
	$TeamFinance = Null;	
	$TeamStat = Null;
	$PlayerRoster = Null;
	$PlayerInfo = Null;
	$PlayerRosterAverage = Null;	
	$GoalieRosterAverage = Null;	
	$PlayerInfoAverage = Null;
	$PlayerStat = Null;
	$GoalieStat = Null;
	$GoalieRoster = Null;
	$Schedule = Null;
	$ScheduleNext = Null;
	$CoachInfo = Null;	
	$RivalryInfo = Null;		
	$LeagueGeneral = Null;
	$LeagueFinance = Null;		
	$LeagueWebClient = Null;	
	$LeagueOutputOption = Null;	
	$TeamLines = Null;
	$TeamCareerSeason = Null;
	$TeamCareerPlayoff = Null;
	$TeamCareerSumSeasonOnly = Null;
	$TeamCareerSumPlayoffOnly = Null;	
	$TeamCareerPlayersSeasonTop5 = Null;	
	$TeamCareerPlayersPlayoffTop5 = Null;	
	$TeamCareerGoaliesSeasonTop5 = Null;	
	$TeamCareerGoaliesPlayoffTop5 = Null;	
	$PlayerStatTeam  = Null;
	$GoalieStatTeam = Null;	
	$TeamLeader = Null;
	$LeagueSimulation = Null;
	$ScheduleLastGame = Null;
	$ScheduleNextGame = Null;
	$TeamLeaderG = Null;
	$TeamLeaderA = Null;
	$TeamLeaderP = Null;
	$TeamLeaderPlusMinus = Null;
	$TeamLeaderGAA = Null;
	$TeamLeaderSavePCT = Null;
	$CupWinner = Null;	
	echo "<style>.STHSPHPTeamStat_Main {display:none;}</style>";
}else{
	$Query = "SELECT count(*) AS count FROM TeamFarmInfo WHERE Number = " . $Team;
	$Result = $db->querySingle($Query,true);
	If ($Result['count'] == 1){
		$Query = "SELECT * FROM TeamFarmInfo WHERE Number = " . $Team;
		$TeamInfo = $db->querySingle($Query,true);
		$Query = "SELECT Name, GMName FROM TeamProInfo WHERE Number = " . $Team;
		$TeamProInfo = $db->querySingle($Query,true);		
		$Query = "SELECT * FROM TeamFarmFinance WHERE Number = " . $Team;
		$TeamFinance = $db->querySingle($Query,true);
		$Query = "SELECT * FROM TeamFarmStat WHERE Number = " . $Team;
		$TeamStat = $db->querySingle($Query,true);
		$Query = "SELECT MainTable.* FROM (SELECT TeamFarmStatVS.TeamVSName AS Name, TeamFarmStatVS.TeamVSNumber AS Number, TeamFarmStatVS.TeamVSNumberThemeID as TeamThemeID, TeamFarmStatVS.GP, TeamFarmStatVS.W, TeamFarmStatVS.L, TeamFarmStatVS.T, TeamFarmStatVS.OTW, TeamFarmStatVS.OTL, TeamFarmStatVS.SOW, TeamFarmStatVS.SOL, TeamFarmStatVS.Points, TeamFarmStatVS.GF, TeamFarmStatVS.GA, TeamFarmStatVS.HomeGP, TeamFarmStatVS.HomeW, TeamFarmStatVS.HomeL, TeamFarmStatVS.HomeT, TeamFarmStatVS.HomeOTW, TeamFarmStatVS.HomeOTL, TeamFarmStatVS.HomeSOW, TeamFarmStatVS.HomeSOL, TeamFarmStatVS.HomeGF, TeamFarmStatVS.HomeGA, TeamFarmStatVS.PPAttemp, TeamFarmStatVS.PPGoal, TeamFarmStatVS.PKAttemp, TeamFarmStatVS.PKGoalGA, TeamFarmStatVS.PKGoalGF, TeamFarmStatVS.ShotsFor, TeamFarmStatVS.ShotsAga, TeamFarmStatVS.ShotsBlock, TeamFarmStatVS.ShotsPerPeriod1, TeamFarmStatVS.ShotsPerPeriod2, TeamFarmStatVS.ShotsPerPeriod3, TeamFarmStatVS.ShotsPerPeriod4, TeamFarmStatVS.GoalsPerPeriod1, TeamFarmStatVS.GoalsPerPeriod2, TeamFarmStatVS.GoalsPerPeriod3, TeamFarmStatVS.GoalsPerPeriod4, TeamFarmStatVS.PuckTimeInZoneDF, TeamFarmStatVS.PuckTimeInZoneOF, TeamFarmStatVS.PuckTimeInZoneNT, TeamFarmStatVS.PuckTimeControlinZoneDF, TeamFarmStatVS.PuckTimeControlinZoneOF, TeamFarmStatVS.PuckTimeControlinZoneNT, TeamFarmStatVS.Shutouts, TeamFarmStatVS.TotalGoal, TeamFarmStatVS.TotalAssist, TeamFarmStatVS.TotalPoint, TeamFarmStatVS.Pim, TeamFarmStatVS.Hits, TeamFarmStatVS.FaceOffWonDefensifZone, TeamFarmStatVS.FaceOffTotalDefensifZone, TeamFarmStatVS.FaceOffWonOffensifZone, TeamFarmStatVS.FaceOffTotalOffensifZone, TeamFarmStatVS.FaceOffWonNeutralZone, TeamFarmStatVS.FaceOffTotalNeutralZone, TeamFarmStatVS.EmptyNetGoal FROM TeamFarmStatVS WHERE GP > 0 AND TeamNumber = " . $Team . " UNION ALL SELECT 'Total' as Name, '104' as Number, '0' as TeamThemeID, TeamFarmStat.GP, TeamFarmStat.W, TeamFarmStat.L, TeamFarmStat.T, TeamFarmStat.OTW, TeamFarmStat.OTL, TeamFarmStat.SOW, TeamFarmStat.SOL, TeamFarmStat.Points, TeamFarmStat.GF, TeamFarmStat.GA, TeamFarmStat.HomeGP, TeamFarmStat.HomeW, TeamFarmStat.HomeL, TeamFarmStat.HomeT, TeamFarmStat.HomeOTW, TeamFarmStat.HomeOTL, TeamFarmStat.HomeSOW, TeamFarmStat.HomeSOL, TeamFarmStat.HomeGF, TeamFarmStat.HomeGA,  TeamFarmStat.PPAttemp, TeamFarmStat.PPGoal, TeamFarmStat.PKAttemp, TeamFarmStat.PKGoalGA, TeamFarmStat.PKGoalGF, TeamFarmStat.ShotsFor, TeamFarmStat.ShotsAga, TeamFarmStat.ShotsBlock, TeamFarmStat.ShotsPerPeriod1, TeamFarmStat.ShotsPerPeriod2, TeamFarmStat.ShotsPerPeriod3, TeamFarmStat.ShotsPerPeriod4, TeamFarmStat.GoalsPerPeriod1, TeamFarmStat.GoalsPerPeriod2, TeamFarmStat.GoalsPerPeriod3, TeamFarmStat.GoalsPerPeriod4, TeamFarmStat.PuckTimeInZoneDF, TeamFarmStat.PuckTimeInZoneOF, TeamFarmStat.PuckTimeInZoneNT, TeamFarmStat.PuckTimeControlinZoneDF, TeamFarmStat.PuckTimeControlinZoneOF, TeamFarmStat.PuckTimeControlinZoneNT, TeamFarmStat.Shutouts, TeamFarmStat.TotalGoal, TeamFarmStat.TotalAssist, TeamFarmStat.TotalPoint, TeamFarmStat.Pim, TeamFarmStat.Hits, TeamFarmStat.FaceOffWonDefensifZone, TeamFarmStat.FaceOffTotalDefensifZone, TeamFarmStat.FaceOffWonOffensifZone, TeamFarmStat.FaceOffTotalOffensifZone, TeamFarmStat.FaceOffWonNeutralZone, TeamFarmStat.FaceOffTotalNeutralZone, TeamFarmStat.EmptyNetGoal FROM TeamFarmStat WHERE Number = " . $Team . ") AS MainTable ORDER BY CASE WHEN Number > 100 THEN 2 ELSE 1 END, MainTable.Name";
		$TeamStatSub = $db->query($Query);		
		$Query = "SELECT * FROM PlayerInfo WHERE Team = " . $Team . " AND Status1 <= 1 Order By PosD, Overall DESC";
		$PlayerRoster = $db->query($Query);
		$Query = "SELECT MainTable.* FROM (SELECT PlayerInfo.Number, PlayerInfo.Name, PlayerInfo.Team, PlayerInfo.TeamName, PlayerInfo.ProTeamName, PlayerInfo.TeamThemeID, PlayerInfo.Age, PlayerInfo.AgeDate, PlayerInfo.Weight, PlayerInfo.Height, PlayerInfo.Contract, PlayerInfo.Rookie, PlayerInfo.NoTrade, PlayerInfo.CanPlayPro, PlayerInfo.CanPlayFarm, PlayerInfo.ForceWaiver, PlayerInfo.ExcludeSalaryCap, PlayerInfo.ProSalaryinFarm, PlayerInfo.SalaryAverage, PlayerInfo.Salary1, PlayerInfo.Salary2, PlayerInfo.Salary3, PlayerInfo.Salary4, PlayerInfo.Salary5, PlayerInfo.Salary6, PlayerInfo.Salary7, PlayerInfo.Salary8, PlayerInfo.Salary9, PlayerInfo.Salary10, PlayerInfo.SalaryRemaining, PlayerInfo.SalaryAverageRemaining, PlayerInfo.SalaryCap, PlayerInfo.SalaryCapRemaining, PlayerInfo.Condition, PlayerInfo.ConditionDecimal,PlayerInfo.Status1, PlayerInfo.URLLink, PlayerInfo.NHLID, PlayerInfo.AvailableForTrade, PlayerInfo.PosC, PlayerInfo.PosLW, PlayerInfo.PosRW, PlayerInfo.PosD, 'False' AS PosG, PlayerInfo.Retire as Retire FROM PlayerInfo Where Team =" . $Team . " AND Status1<= 1 UNION ALL SELECT GoalerInfo.Number, GoalerInfo.Name, GoalerInfo.Team, GoalerInfo.TeamName, GoalerInfo.ProTeamName, GoalerInfo.TeamThemeID, GoalerInfo.Age, GoalerInfo.AgeDate, GoalerInfo.Weight, GoalerInfo.Height, GoalerInfo.Contract, GoalerInfo.Rookie, GoalerInfo.NoTrade, GoalerInfo.CanPlayPro, GoalerInfo.CanPlayFarm, GoalerInfo.ForceWaiver, GoalerInfo.ExcludeSalaryCap, GoalerInfo.ProSalaryinFarm, GoalerInfo.SalaryAverage, GoalerInfo.Salary1, GoalerInfo.Salary2, GoalerInfo.Salary3, GoalerInfo.Salary4, GoalerInfo.Salary5, GoalerInfo.Salary6, GoalerInfo.Salary7, GoalerInfo.Salary8, GoalerInfo.Salary9, GoalerInfo.Salary10, GoalerInfo.SalaryRemaining, GoalerInfo.SalaryAverageRemaining, GoalerInfo.SalaryCap, GoalerInfo.SalaryCapRemaining, GoalerInfo.Condition, GoalerInfo.ConditionDecimal, GoalerInfo.Status1, GoalerInfo.URLLink, GoalerInfo.NHLID, GoalerInfo.AvailableForTrade,'False' AS PosC, 'False' AS PosLW, 'False' AS PosRW, 'False' AS PosD, 'True' AS PosG, GoalerInfo.Retire as Retire FROM GoalerInfo Where Team =" . $Team . "  AND Status1 <= 1) AS MainTable ORDER BY MainTable.Name";
		$PlayerInfo = $db->query($Query);
		$Query = "SELECT Count(MainTable.Name) AS CountOfName, Avg(MainTable.Age) AS AvgOfAge, Avg(MainTable.Weight) AS AvgOfWeight, Avg(MainTable.Height) AS AvgOfHeight, Avg(MainTable.Contract) AS AvgOfContract, Avg(MainTable.Salary1) AS AvgOfSalary1 FROM (SELECT PlayerInfo.Name, PlayerInfo.Team, PlayerInfo.Age, PlayerInfo.Weight, PlayerInfo.Height, PlayerInfo.Contract, PlayerInfo.Salary1, PlayerInfo.Status1 FROM PlayerInfo WHERE Team = " . $Team . " and Status1 <= 1 UNION ALL SELECT GoalerInfo.Name, GoalerInfo.Team, GoalerInfo.Age, GoalerInfo.Weight, GoalerInfo.Height, GoalerInfo.Contract, GoalerInfo.Salary1, GoalerInfo.Status1 FROM GoalerInfo WHERE Team= " . $Team . "  AND Status1 <= 1) AS MainTable";
		$PlayerInfoAverage = $db->querySingle($Query,true);
		$Query = "SELECT Count(MainTable.Name) AS CountOfName, Avg(MainTable.Age) AS AvgOfAge, Avg(MainTable.Weight) AS AvgOfWeight, Avg(MainTable.Height) AS AvgOfHeight, Avg(MainTable.Contract) AS AvgOfContract, Avg(MainTable.Salary1) AS AvgOfSalary1, Sum(MainTable.Salary1) AS SumOfSalary1, Sum(MainTable.Salary2) AS SumOfSalary2, Sum(MainTable.Salary3) AS SumOfSalary3, Sum(MainTable.Salary4) AS SumOfSalary4, Sum(MainTable.Salary5) AS SumOfSalary5 FROM (SELECT PlayerInfo.Name, PlayerInfo.Team, PlayerInfo.Age, PlayerInfo.Weight, PlayerInfo.Height, PlayerInfo.Contract, PlayerInfo.Salary1, PlayerInfo.Salary2, PlayerInfo.Salary3, PlayerInfo.Salary4, PlayerInfo.Salary5, PlayerInfo.Status1 FROM PlayerInfo WHERE Team = " . $Team . " UNION ALL SELECT GoalerInfo.Name, GoalerInfo.Team, GoalerInfo.Age, GoalerInfo.Weight, GoalerInfo.Height, GoalerInfo.Contract, GoalerInfo.Salary1, GoalerInfo.Salary2, GoalerInfo.Salary3, GoalerInfo.Salary4, GoalerInfo.Salary5, GoalerInfo.Status1 FROM GoalerInfo WHERE Team= " . $Team . "  AND Status1 <= 1) AS MainTable";
		$PlayerInfoTotalAverage = $db->querySingle($Query,true);		
		$Query = "SELECT Avg(PlayerInfo.ConditionDecimal) AS AvgOfConditionDecimal, Avg(PlayerInfo.CK) AS AvgOfCK, Avg(PlayerInfo.FG) AS AvgOfFG, Avg(PlayerInfo.DI) AS AvgOfDI, Avg(PlayerInfo.SK) AS AvgOfSK, Avg(PlayerInfo.ST) AS AvgOfST, Avg(PlayerInfo.EN) AS AvgOfEN, Avg(PlayerInfo.DU) AS AvgOfDU, Avg(PlayerInfo.PH) AS AvgOfPH, Avg(PlayerInfo.FO) AS AvgOfFO, Avg(PlayerInfo.PA) AS AvgOfPA, Avg(PlayerInfo.SC) AS AvgOfSC, Avg(PlayerInfo.DF) AS AvgOfDF, Avg(PlayerInfo.PS) AS AvgOfPS, Avg(PlayerInfo.EX) AS AvgOfEX, Avg(PlayerInfo.LD) AS AvgOfLD, Avg(PlayerInfo.PO) AS AvgOfPO, Avg(PlayerInfo.MO) AS AvgOfMO, Avg(PlayerInfo.Overall) AS AvgOfOverall FROM PlayerInfo WHERE Team = " . $Team . " AND Status1 <= 1";
		$PlayerRosterAverage = $db->querySingle($Query,True);	
		$Query = "SELECT GoalerInfo.Team, GoalerInfo.Status1, Avg(GoalerInfo.ConditionDecimal) AS AvgOfConditionDecimal, Avg(GoalerInfo.SK) AS AvgOfSK, Avg(GoalerInfo.DU) AS AvgOfDU, Avg(GoalerInfo.EN) AS AvgOfEN, Avg(GoalerInfo.SZ) AS AvgOfSZ, Avg(GoalerInfo.AG) AS AvgOfAG, Avg(GoalerInfo.RB) AS AvgOfRB, Avg(GoalerInfo.SC) AS AvgOfSC, Avg(GoalerInfo.HS) AS AvgOfHS, Avg(GoalerInfo.RT) AS AvgOfRT, Avg(GoalerInfo.PH) AS AvgOfPH, Avg(GoalerInfo.PS) AS AvgOfPS, Avg(GoalerInfo.EX) AS AvgOfEX, Avg(GoalerInfo.LD) AS AvgOfLD, Avg(GoalerInfo.PO) AS AvgOfPO, Avg(GoalerInfo.MO) AS AvgOfMO, Avg(GoalerInfo.Overall) AS AvgOfOverall FROM GoalerInfo WHERE Team = " . $Team . " AND Status1 <= 1";
		$GoalieRosterAverage = $db->querySingle($Query,True);			
		$Query = "SELECT PlayerFarmStat.*, PlayerInfo.TeamName, PlayerInfo.PosC, PlayerInfo.PosLW, PlayerInfo.PosRW, PlayerInfo.PosD, PlayerInfo.TeamThemeID, ROUND((CAST(PlayerFarmStat.G AS REAL) / (PlayerFarmStat.Shots))*100,2) AS ShotsPCT, ROUND((CAST(PlayerFarmStat.SecondPlay AS REAL) / 60 / (PlayerFarmStat.GP)),2) AS AMG,ROUND((CAST(PlayerFarmStat.FaceOffWon AS REAL) / (PlayerFarmStat.FaceOffTotal))*100,2) as FaceoffPCT,ROUND((CAST(PlayerFarmStat.P AS REAL) / (PlayerFarmStat.SecondPlay) * 60 * 20),2) AS P20 FROM PlayerInfo INNER JOIN PlayerFarmStat ON PlayerInfo.Number = PlayerFarmStat.Number WHERE ((PlayerInfo.Team=" . $Team . ") AND (PlayerFarmStat.SecondPlay>0)) ORDER BY PlayerFarmStat.P DESC";
		$PlayerStat = $db->query($Query);
		$Query = "SELECT Sum(PlayerFarmStat.GP) AS SumOfGP, Sum(PlayerFarmStat.Shots) AS SumOfShots, Sum(PlayerFarmStat.G) AS SumOfG, Sum(PlayerFarmStat.A) AS SumOfA, Sum(PlayerFarmStat.P) AS SumOfP, Sum(PlayerFarmStat.PlusMinus) AS SumOfPlusMinus, Sum(PlayerFarmStat.Pim) AS SumOfPim, Sum(PlayerFarmStat.Pim5) AS SumOfPim5, Sum(PlayerFarmStat.ShotsBlock) AS SumOfShotsBlock, Sum(PlayerFarmStat.OwnShotsBlock) AS SumOfOwnShotsBlock, Sum(PlayerFarmStat.OwnShotsMissGoal) AS SumOfOwnShotsMissGoal, Sum(PlayerFarmStat.Hits) AS SumOfHits, Sum(PlayerFarmStat.HitsTook) AS SumOfHitsTook, Sum(PlayerFarmStat.GW) AS SumOfGW, Sum(PlayerFarmStat.GT) AS SumOfGT, Sum(PlayerFarmStat.FaceOffWon) AS SumOfFaceOffWon, Sum(PlayerFarmStat.FaceOffTotal) AS SumOfFaceOffTotal, Sum(PlayerFarmStat.PenalityShotsScore) AS SumOfPenalityShotsScore, Sum(PlayerFarmStat.PenalityShotsTotal) AS SumOfPenalityShotsTotal, Sum(PlayerFarmStat.EmptyNetGoal) AS SumOfEmptyNetGoal, Sum(PlayerFarmStat.SecondPlay) AS SumOfSecondPlay, Sum(PlayerFarmStat.HatTrick) AS SumOfHatTrick, Sum(PlayerFarmStat.PPG) AS SumOfPPG, Sum(PlayerFarmStat.PPA) AS SumOfPPA, Sum(PlayerFarmStat.PPP) AS SumOfPPP, Sum(PlayerFarmStat.PPShots) AS SumOfPPShots, Sum(PlayerFarmStat.PPSecondPlay) AS SumOfPPSecondPlay, Sum(PlayerFarmStat.PKG) AS SumOfPKG, Sum(PlayerFarmStat.PKA) AS SumOfPKA, Sum(PlayerFarmStat.PKP) AS SumOfPKP, Sum(PlayerFarmStat.PKShots) AS SumOfPKShots, Sum(PlayerFarmStat.PKSecondPlay) AS SumOfPKSecondPlay, Sum(PlayerFarmStat.GiveAway) AS SumOfGiveAway, Sum(PlayerFarmStat.TakeAway) AS SumOfTakeAway, Sum(PlayerFarmStat.PuckPossesionTime) AS SumOfPuckPossesionTime, Sum(PlayerFarmStat.FightW) AS SumOfFightW, Sum(PlayerFarmStat.FightL) AS SumOfFightL, Sum(PlayerFarmStat.FightT) AS SumOfFightT, Sum(PlayerFarmStat.Star1) AS SumOfStar1, Sum(PlayerFarmStat.Star2) AS SumOfStar2, Sum(PlayerFarmStat.Star3) AS SumOfStar3, ROUND((CAST(Sum(PlayerFarmStat.G) AS REAL) / (Sum(PlayerFarmStat.Shots)))*100,2) AS SumOfShotsPCT, ROUND((CAST(Sum(PlayerFarmStat.SecondPlay) AS REAL) / 60 / (Sum(PlayerFarmStat.GP))),2) AS SumOfAMG, ROUND((CAST(Sum(PlayerFarmStat.FaceOffWon) AS REAL) / (Sum(PlayerFarmStat.FaceOffTotal)))*100,2) as SumOfFaceoffPCT, ROUND((CAST(Sum(PlayerFarmStat.P) AS REAL) / (Sum(PlayerFarmStat.SecondPlay)) * 60 * 20),2) AS SumOfP20 FROM PlayerInfo INNER JOIN PlayerFarmStat ON PlayerInfo.Number = PlayerFarmStat.Number WHERE ((PlayerInfo.Team=" . $Team . ") AND (PlayerFarmStat.SecondPlay>0)) ORDER BY PlayerFarmStat.P DESC";
		$PlayerStatTeam = $db->querySingle($Query,true);		
		$Query = "SELECT GoalerFarmStat.*, GoalerInfo.TeamName, GoalerInfo.TeamThemeID, ROUND((CAST(GoalerFarmStat.GA AS REAL) / (GoalerFarmStat.SecondPlay / 60))*60,3) AS GAA, ROUND((CAST(GoalerFarmStat.SA - GoalerFarmStat.GA AS REAL) / (GoalerFarmStat.SA)),3) AS PCT, ROUND((CAST(GoalerFarmStat.PenalityShotsShots - GoalerFarmStat.PenalityShotsGoals AS REAL) / (GoalerFarmStat.PenalityShotsShots)),3) AS PenalityShotsPCT FROM GoalerInfo INNER JOIN GoalerFarmStat ON GoalerInfo.Number = GoalerFarmStat.Number WHERE (((GoalerInfo.Team)=" . $Team . ") AND (GoalerFarmStat.SecondPlay>0)) ORDER BY GoalerFarmStat.W DESC";
		$GoalieStat = $db->query($Query);
		$Query = "SELECT Sum(GoalerFarmStat.GP) AS SumOfGP, Sum(GoalerFarmStat.SecondPlay) AS SumOfSecondPlay, Sum(GoalerFarmStat.W) AS SumOfW, Sum(GoalerFarmStat.L) AS SumOfL, Sum(GoalerFarmStat.OTL) AS SumOfOTL, Sum(GoalerFarmStat.Shootout) AS SumOfShootout, Sum(GoalerFarmStat.GA) AS SumOfGA, Sum(GoalerFarmStat.SA) AS SumOfSA, Sum(GoalerFarmStat.SARebound) AS SumOfSARebound, Sum(GoalerFarmStat.Pim) AS SumOfPim, Sum(GoalerFarmStat.A) AS SumOfA, Sum(GoalerFarmStat.PenalityShotsShots) AS SumOfPenalityShotsShots, Sum(GoalerFarmStat.PenalityShotsGoals) AS SumOfPenalityShotsGoals, Sum(GoalerFarmStat.StartGoaler) AS SumOfStartGoaler, Sum(GoalerFarmStat.BackupGoaler) AS SumOfBackupGoaler, Sum(GoalerFarmStat.EmptyNetGoal) AS SumOfEmptyNetGoal, Sum(GoalerFarmStat.Star1) AS SumOfStar1, Sum(GoalerFarmStat.Star2) AS SumOfStar2, Sum(GoalerFarmStat.Star3) AS SumOfStar3, ROUND((CAST(Sum(GoalerFarmStat.GA) AS REAL) / (Sum(GoalerFarmStat.SecondPlay) / 60))*60,3) AS SumOfGAA, ROUND((CAST(Sum(GoalerFarmStat.SA) - Sum(GoalerFarmStat.GA) AS REAL) / (Sum(GoalerFarmStat.SA))),3) AS SumOfPCT, ROUND((CAST(Sum(GoalerFarmStat.PenalityShotsShots) - Sum(GoalerFarmStat.PenalityShotsGoals) AS REAL) / (Sum(GoalerFarmStat.PenalityShotsShots))),3) AS SumOfPenalityShotsPCT FROM GoalerInfo INNER JOIN GoalerFarmStat ON GoalerInfo.Number = GoalerFarmStat.Number WHERE (((GoalerInfo.Team)=" . $Team . ")  AND (GoalerFarmStat.SecondPlay>0)) ORDER BY GoalerFarmStat.W DESC";
		$GoalieStatTeam = $db->querySingle($Query,true);			
		$Query = "SELECT * FROM GoalerInfo WHERE Team = " . $Team . " AND Status1 <= 1 Order By Overall DESC";
		$GoalieRoster = $db->query($Query);
		$Query = "SELECT * FROM ScheduleFarm WHERE (VisitorTeam = " . $Team . " OR HomeTeam = " . $Team . ") ORDER BY GameNumber";
		$Schedule= $db->query($Query);
		$Query = "SELECT * FROM ScheduleFarm WHERE (VisitorTeam = " . $Team . " OR HomeTeam = " . $Team . ") AND Play = 'False' ORDER BY GameNumber LIMIT 1";
		$ScheduleNext = $db->querySingle($Query,true);		
		$Query = "SELECT CoachInfo.* FROM CoachInfo INNER JOIN TeamFarmInfo ON CoachInfo.Number = TeamFarmInfo.CoachID WHERE (CoachInfo.Team)=" . $Team;
		$CoachInfo = $db->querySingle($Query,true);	
		$Query = "SELECT * FROM FarmRivalryInfo WHERE Team1 = " . $Team . " Order By TEAM2";
		$RivalryInfo = $db->query($Query);		
		$Query = "Select Name, LeagueYear, PointSystemW, PointSystemSO, OffSeason, LeagueYearOutput, FarmScheduleTotalDay, ScheduleNextDay, RFAAge, UFAAge, DefaultSimulationPerDay, TradeDeadLine, ProScheduleTotalDay, PreSeasonSchedule, PlayOffStarted from LeagueGeneral";
		$LeagueGeneral = $db->querySingle($Query,true);
		$Query = "Select RemoveSalaryCapWhenPlayerUnderCondition, SalaryCapOption from LeagueFinance";
		$LeagueFinance = $db->querySingle($Query,true);		
		$Query = "Select FarmPlayerLimit, MinimumPlayerPerTeam, MaximumPlayerPerTeam, FarmCustomOTLines from LeagueWebClient";
		$LeagueWebClient = $db->querySingle($Query,true);	
		$Query = "Select PlayersMugShotBaseURL, PlayersMugShotFileExtension, OutputSalariesRemaining, OutputSalariesAverageTotal, OutputSalariesAverageRemaining, InchInsteadofCM, LBSInsteadofKG, FreeAgentUseDateInsteadofDay, ScheduleUseDateInsteadofDay, ScheduleRealDate, ShowWebClientInDymanicWebsite,JerseyNumberInWebsite,MergeRosterPlayerInfo,MergeProFarmRoster from LeagueOutputOption";
		$LeagueOutputOption = $db->querySingle($Query,true);	
		$Query = "SELECT Count(Prospects.Name) As CountOfName FROM Prospects WHERE TeamNumber = " . $Team;
		$ProspectsCount = $db->querySingle($Query,true);			
		$Query = "SELECT * FROM TeamFarmLines WHERE TeamNumber = " . $Team . " AND Day = 1";
		$TeamLines = $db->querySingle($Query,true);
		$Query = "SELECT TeamFarmInfo.Name as TeamName, PlayerInfo_1.Name As Captain, PlayerInfo_2.Name as Assistant1, PlayerInfo_3.Name as Assistant2 FROM ((TeamFarmInfo LEFT JOIN PlayerInfo AS PlayerInfo_1 ON TeamFarmInfo.Captain = PlayerInfo_1.Number) LEFT JOIN PlayerInfo AS PlayerInfo_2 ON TeamFarmInfo.Assistant1 = PlayerInfo_2.Number) LEFT JOIN PlayerInfo AS PlayerInfo_3 ON TeamFarmInfo.Assistant2 = PlayerInfo_3.Number WHERE TeamFarmInfo.Number = " . $Team;
		$TeamLeader = $db->querySingle($Query,true);
		$Query = "SELECT * FROM (SELECT ScheduleFarm.*, 'Farm' AS Type, TeamFarmStatVisitor.Last10W AS VLast10W,TeamFarmStatVisitor.GF AS VGF,TeamFarmStatVisitor.GA AS VGA,TeamFarmStatVisitor.PKGoalGA AS VPKGA,TeamFarmStatVisitor.PKAttemp AS VPKAttemp,TeamFarmStatVisitor.PPGoal AS VPPGoal,TeamFarmStatVisitor.PPAttemp AS VPPAttemp,TeamFarmStatVisitor.GP AS VGP, TeamFarmStatVisitor.Last10L AS VLast10L, TeamFarmStatVisitor.Last10T AS VLast10T, TeamFarmStatVisitor.Last10OTW AS VLast10OTW, TeamFarmStatVisitor.Last10OTL AS VLast10OTL, TeamFarmStatVisitor.Last10SOW AS VLast10SOW, TeamFarmStatVisitor.Last10SOL AS VLast10SOL, TeamFarmStatVisitor.GP AS VGP, TeamFarmStatVisitor.W AS VW, TeamFarmStatVisitor.L AS VL, TeamFarmStatVisitor.T AS VT, TeamFarmStatVisitor.Points AS VPoints,TeamFarmStatVisitor.HomeW AS VHW,TeamFarmStatVisitor.HomeL AS VHL,TeamFarmStatVisitor.HomeOTW AS VHOTW,TeamFarmStatVisitor.HomeOTL AS VHOTL,TeamFarmStatVisitor.HomeSOW AS VHSOW,TeamFarmStatVisitor.HomeSOL AS VHSOL, TeamFarmStatVisitor.OTW AS VOTW, TeamFarmStatVisitor.OTL AS VOTL, TeamFarmStatVisitor.SOW AS VSOW, TeamFarmStatVisitor.SOL AS VSOL, TeamFarmStatVisitor.Points AS VPoints, TeamFarmStatVisitor.Streak AS VStreak, TeamFarmStatHome.Last10W AS HLast10W, TeamFarmStatHome.Last10L AS HLast10L, TeamFarmStatHome.Last10T AS HLast10T, TeamFarmStatHome.Last10OTW AS HLast10OTW,TeamFarmStatHome.PKGoalGA AS PKGA,TeamFarmStatHome.PKAttemp AS PKAttemp,TeamFarmStatHome.GF AS HGF, TeamFarmStatHome.GA AS HGA,TeamFarmStatHome.GP AS HGP,TeamFarmStatHome.Last10OTL AS HLast10OTL, TeamFarmStatHome.Last10SOW AS HLast10SOW, TeamFarmStatHome.Last10SOL AS HLast10SOL, TeamFarmStatHome.GP AS HGP, TeamFarmStatHome.W AS HW, TeamFarmStatHome.L AS HL, TeamFarmStatHome.Points AS HPoints,TeamFarmStatHome.PPAttemp AS HPPAttemp,TeamFarmStatHome.PKAttemp AS HPKAttemp,TeamFarmStatHome.PKGoalGA AS HPKGA,TeamFarmStatHome.PPGoal AS HPPGoal, TeamFarmStatHome.T AS HT, TeamFarmStatHome.OTW AS HOTW, TeamFarmStatHome.OTL AS HOTL, TeamFarmStatHome.SOW AS HSOW, TeamFarmStatHome.SOL AS HSOL, TeamFarmStatHome.Points AS HPoints,TeamFarmStatHome.HomeW AS HHW,TeamFarmStatHome.HomeL AS HHL,TeamFarmStatHome.HomeOTW AS HHOTW,TeamFarmStatHome.HomeOTL AS HHOTL,TeamFarmStatHome.HomeSOW AS HHSOW,TeamFarmStatHome.HomeSOL AS HHSOL, TeamFarmStatHome.Streak AS HStreak FROM (ScheduleFarm LEFT JOIN TeamFarmStat AS TeamFarmStatHome ON ScheduleFarm.HomeTeam = TeamFarmStatHome.Number) LEFT JOIN TeamFarmStat AS TeamFarmStatVisitor ON ScheduleFarm.VisitorTeam = TeamFarmStatVisitor.Number WHERE Play = 'True' AND (VisitorTeam = " . $Team . " OR HomeTeam = " . $Team . ") ORDER BY GameNumber DESC LIMIT 2) ORDER BY GameNumber";
		$ScheduleLastGame = $db->query($Query);
		$Query = "SELECT ScheduleFarm.*, 'Farm' AS Type, TeamFarmStatVisitor.Last10W AS VLast10W,TeamFarmStatVisitor.GF AS VGF,TeamFarmStatVisitor.GA AS VGA,TeamFarmStatVisitor.PKGoalGA AS VPKGA,TeamFarmStatVisitor.PKAttemp AS VPKAttemp,TeamFarmStatVisitor.PPGoal AS VPPGoal,TeamFarmStatVisitor.PPAttemp AS VPPAttemp,TeamFarmStatVisitor.GP AS VGP, TeamFarmStatVisitor.Last10L AS VLast10L, TeamFarmStatVisitor.Last10T AS VLast10T, TeamFarmStatVisitor.Last10OTW AS VLast10OTW, TeamFarmStatVisitor.Last10OTL AS VLast10OTL, TeamFarmStatVisitor.Last10SOW AS VLast10SOW, TeamFarmStatVisitor.Last10SOL AS VLast10SOL, TeamFarmStatVisitor.GP AS VGP, TeamFarmStatVisitor.W AS VW, TeamFarmStatVisitor.L AS VL, TeamFarmStatVisitor.T AS VT, TeamFarmStatVisitor.Points AS VPoints,TeamFarmStatVisitor.HomeW AS VHW,TeamFarmStatVisitor.HomeL AS VHL,TeamFarmStatVisitor.HomeOTW AS VHOTW,TeamFarmStatVisitor.HomeOTL AS VHOTL,TeamFarmStatVisitor.HomeSOW AS VHSOW,TeamFarmStatVisitor.HomeSOL AS VHSOL, TeamFarmStatVisitor.OTW AS VOTW, TeamFarmStatVisitor.OTL AS VOTL, TeamFarmStatVisitor.SOW AS VSOW, TeamFarmStatVisitor.SOL AS VSOL, TeamFarmStatVisitor.Points AS VPoints, TeamFarmStatVisitor.Streak AS VStreak, TeamFarmStatHome.Last10W AS HLast10W, TeamFarmStatHome.Last10L AS HLast10L, TeamFarmStatHome.Last10T AS HLast10T, TeamFarmStatHome.Last10OTW AS HLast10OTW,TeamFarmStatHome.PKGoalGA AS PKGA,TeamFarmStatHome.PKAttemp AS PKAttemp,TeamFarmStatHome.GF AS HGF, TeamFarmStatHome.GA AS HGA,TeamFarmStatHome.GP AS HGP,TeamFarmStatHome.Last10OTL AS HLast10OTL, TeamFarmStatHome.Last10SOW AS HLast10SOW, TeamFarmStatHome.Last10SOL AS HLast10SOL, TeamFarmStatHome.GP AS HGP, TeamFarmStatHome.W AS HW, TeamFarmStatHome.L AS HL, TeamFarmStatHome.Points AS HPoints,TeamFarmStatHome.PPAttemp AS HPPAttemp,TeamFarmStatHome.PKAttemp AS HPKAttemp,TeamFarmStatHome.PKGoalGA AS HPKGA,TeamFarmStatHome.PPGoal AS HPPGoal, TeamFarmStatHome.T AS HT, TeamFarmStatHome.OTW AS HOTW, TeamFarmStatHome.OTL AS HOTL, TeamFarmStatHome.SOW AS HSOW, TeamFarmStatHome.SOL AS HSOL, TeamFarmStatHome.Points AS HPoints,TeamFarmStatHome.HomeW AS HHW,TeamFarmStatHome.HomeL AS HHL,TeamFarmStatHome.HomeOTW AS HHOTW,TeamFarmStatHome.HomeOTL AS HHOTL,TeamFarmStatHome.HomeSOW AS HHSOW,TeamFarmStatHome.HomeSOL AS HHSOL, TeamFarmStatHome.Streak AS HStreak FROM (ScheduleFarm LEFT JOIN TeamFarmStat AS TeamFarmStatHome ON ScheduleFarm.HomeTeam = TeamFarmStatHome.Number) LEFT JOIN TeamFarmStat AS TeamFarmStatVisitor ON ScheduleFarm.VisitorTeam = TeamFarmStatVisitor.Number WHERE Play = 'False' AND (VisitorTeam = " . $Team . " OR HomeTeam = " . $Team . ") ORDER BY GameNumber ASC LIMIT 3";
		$ScheduleNextGame= $db->query($Query);		
		$Query = "SELECT PlayerFarmStat.*, PlayerInfo.TeamName, PlayerInfo.Team, PlayerInfo.PosC, PlayerInfo.PosLW, PlayerInfo.PosRW,PlayerInfo.Jersey,PlayerInfo.NHLID, PlayerInfo.PosD, ROUND((CAST(PlayerFarmStat.G AS REAL) / (PlayerFarmStat.Shots))*100,2) AS ShotsPCT, ROUND((CAST(PlayerFarmStat.SecondPlay AS REAL) / 60 / (PlayerFarmStat.GP)),2) AS AMG,ROUND((CAST(PlayerFarmStat.FaceOffWon AS REAL) / (PlayerFarmStat.FaceOffTotal))*100,2) as FaceoffPCT,ROUND((CAST(PlayerFarmStat.P AS REAL) / (PlayerFarmStat.SecondPlay) * 60 * 20),2) AS P20 FROM PlayerInfo INNER JOIN PlayerFarmStat ON PlayerInfo.Number = PlayerFarmStat.Number WHERE ((PlayerInfo.Team=" . $Team . ") AND (PlayerInfo.Status1 <= 1)  AND (PlayerFarmStat.GP>0)) ORDER BY PlayerFarmStat.G DESC, PlayerFarmStat.GP ASC, PlayerFarmStat.P DESC LIMIT 1";
		$TeamLeaderG = $db->query($Query);
		$Query = "SELECT PlayerFarmStat.*, PlayerInfo.TeamName, PlayerInfo.Team, PlayerInfo.PosC, PlayerInfo.PosLW, PlayerInfo.PosRW,PlayerInfo.Jersey,PlayerInfo.NHLID, PlayerInfo.PosD, ROUND((CAST(PlayerFarmStat.G AS REAL) / (PlayerFarmStat.Shots))*100,2) AS ShotsPCT, ROUND((CAST(PlayerFarmStat.SecondPlay AS REAL) / 60 / (PlayerFarmStat.GP)),2) AS AMG,ROUND((CAST(PlayerFarmStat.FaceOffWon AS REAL) / (PlayerFarmStat.FaceOffTotal))*100,2) as FaceoffPCT,ROUND((CAST(PlayerFarmStat.P AS REAL) / (PlayerFarmStat.SecondPlay) * 60 * 20),2) AS P20 FROM PlayerInfo INNER JOIN PlayerFarmStat ON PlayerInfo.Number = PlayerFarmStat.Number WHERE ((PlayerInfo.Team=" . $Team . ") AND (PlayerInfo.Status1 <= 1)  AND (PlayerFarmStat.GP>0))  ORDER BY PlayerFarmStat.A DESC, PlayerFarmStat.P DESC, PlayerFarmStat.GP ASC LIMIT 1";
		$TeamLeaderA = $db->query($Query);
		$Query = "SELECT PlayerFarmStat.*, PlayerInfo.TeamName, PlayerInfo.Team, PlayerInfo.PosC, PlayerInfo.PosLW, PlayerInfo.PosRW,PlayerInfo.Jersey,PlayerInfo.NHLID, PlayerInfo.PosD, ROUND((CAST(PlayerFarmStat.G AS REAL) / (PlayerFarmStat.Shots))*100,2) AS ShotsPCT, ROUND((CAST(PlayerFarmStat.SecondPlay AS REAL) / 60 / (PlayerFarmStat.GP)),2) AS AMG,ROUND((CAST(PlayerFarmStat.FaceOffWon AS REAL) / (PlayerFarmStat.FaceOffTotal))*100,2) as FaceoffPCT,ROUND((CAST(PlayerFarmStat.P AS REAL) / (PlayerFarmStat.SecondPlay) * 60 * 20),2) AS P20 FROM PlayerInfo INNER JOIN PlayerFarmStat ON PlayerInfo.Number = PlayerFarmStat.Number WHERE ((PlayerInfo.Team=" . $Team . ") AND (PlayerInfo.Status1 <= 1)  AND (PlayerFarmStat.GP>0)) ORDER BY PlayerFarmStat.P DESC, PlayerFarmStat.GP ASC LIMIT 1";
		$TeamLeaderP = $db->query($Query);
		$Query = "SELECT PlayerFarmStat.*, PlayerInfo.TeamName, PlayerInfo.Team, PlayerInfo.PosC, PlayerInfo.PosLW, PlayerInfo.PosRW,PlayerInfo.Jersey,PlayerInfo.NHLID, PlayerInfo.PosD, ROUND((CAST(PlayerFarmStat.G AS REAL) / (PlayerFarmStat.Shots))*100,2) AS ShotsPCT, ROUND((CAST(PlayerFarmStat.SecondPlay AS REAL) / 60 / (PlayerFarmStat.GP)),2) AS AMG,ROUND((CAST(PlayerFarmStat.FaceOffWon AS REAL) / (PlayerFarmStat.FaceOffTotal))*100,2) as FaceoffPCT,ROUND((CAST(PlayerFarmStat.P AS REAL) / (PlayerFarmStat.SecondPlay) * 60 * 20),2) AS P20 FROM PlayerInfo INNER JOIN PlayerFarmStat ON PlayerInfo.Number = PlayerFarmStat.Number WHERE ((PlayerInfo.Team=" . $Team . ") AND (PlayerInfo.Status1 <= 1)  AND (PlayerFarmStat.GP>0))  ORDER BY PlayerFarmStat.PlusMinus DESC, PlayerFarmStat.G DESC, PlayerFarmStat.GP ASC LIMIT 1";
		$TeamLeaderPlusMinus = $db->query($Query);
		$Query = "SELECT GoalerFarmStat.*, GoalerInfo.TeamName,GoalerInfo.Team,GoalerInfo.Jersey,GoalerInfo.NHLID, ROUND((CAST(GoalerFarmStat.GA AS REAL) / (GoalerFarmStat.SecondPlay / 60))*60,3) AS GAA, ROUND((CAST(GoalerFarmStat.SA - GoalerFarmStat.GA AS REAL) / (GoalerFarmStat.SA)),3) AS PCT, ROUND((CAST(GoalerFarmStat.PenalityShotsShots - GoalerFarmStat.PenalityShotsGoals AS REAL) / (GoalerFarmStat.PenalityShotsShots)),3) AS PenalityShotsPCT FROM GoalerInfo INNER JOIN GoalerFarmStat ON GoalerInfo.Number = GoalerFarmStat.Number WHERE ((GoalerInfo.Team)=" . $Team . ")  AND ((GoalerFarmStat.GP)>0) ORDER BY W DESC, GoalerFarmStat.GP DESC LIMIT 1";
		$TeamLeaderGAA = $db->query($Query);
		$Query = "SELECT GoalerFarmStat.*, GoalerInfo.TeamName,GoalerInfo.Team,GoalerInfo.Jersey,GoalerInfo.NHLID, ROUND((CAST(GoalerFarmStat.GA AS REAL) / (GoalerFarmStat.SecondPlay / 60))*60,3) AS GAA, ROUND((CAST(GoalerFarmStat.SA - GoalerFarmStat.GA AS REAL) / (GoalerFarmStat.SA)),3) AS PCT, ROUND((CAST(GoalerFarmStat.PenalityShotsShots - GoalerFarmStat.PenalityShotsGoals AS REAL) / (GoalerFarmStat.PenalityShotsShots)),3) AS PenalityShotsPCT FROM GoalerInfo INNER JOIN GoalerFarmStat ON GoalerInfo.Number = GoalerFarmStat.Number WHERE ((GoalerInfo.Team)=" . $Team . ")  AND ((GoalerFarmStat.GP)>0) ORDER BY PCT DESC, GoalerFarmStat.GP DESC LIMIT 1";
		$TeamLeaderSavePCT = $db->query($Query);
		
		$LeagueName = $LeagueGeneral['Name'];
		$TeamName = $TeamInfo['Name'];	
		
		If (file_exists($CareerStatDatabaseFile) == true){ /* CareerStat */
			$CareerStatdb = new SQLite3($CareerStatDatabaseFile);
			
			$Query = "Select TeamFarmStatCareer.* FROM TeamFarmStatCareer WHERE Playoff = 'False' AND (UniqueID = " . $TeamInfo['UniqueID'] . " OR Name = '" . str_replace("'","''",$TeamName) . "') ORDER BY Year";
			$TeamCareerSeason = $CareerStatdb->query($Query);
			$Query = "Select TeamFarmStatCareer.* FROM TeamFarmStatCareer WHERE Playoff = 'True' AND (UniqueID = " . $TeamInfo['UniqueID'] . " OR Name = '" . str_replace("'","''",$TeamName) . "') ORDER BY Year";
			$TeamCareerPlayoff = $CareerStatdb->query($Query);			
			$Query = "SELECT Sum(TeamFarmStatCareer.GP) AS SumOfGP, Sum(TeamFarmStatCareer.W) AS SumOfW, Sum(TeamFarmStatCareer.L) AS SumOfL, Sum(TeamFarmStatCareer.T) AS SumOfT, Sum(TeamFarmStatCareer.OTW) AS SumOfOTW, Sum(TeamFarmStatCareer.OTL) AS SumOfOTL, Sum(TeamFarmStatCareer.SOW) AS SumOfSOW, Sum(TeamFarmStatCareer.SOL) AS SumOfSOL, Sum(TeamFarmStatCareer.Points) AS SumOfPoints, Sum(TeamFarmStatCareer.GF) AS SumOfGF, Sum(TeamFarmStatCareer.GA) AS SumOfGA, Sum(TeamFarmStatCareer.HomeGP) AS SumOfHomeGP, Sum(TeamFarmStatCareer.HomeW) AS SumOfHomeW, Sum(TeamFarmStatCareer.HomeL) AS SumOfHomeL, Sum(TeamFarmStatCareer.HomeT) AS SumOfHomeT, Sum(TeamFarmStatCareer.HomeOTW) AS SumOfHomeOTW, Sum(TeamFarmStatCareer.HomeOTL) AS SumOfHomeOTL, Sum(TeamFarmStatCareer.HomeSOW) AS SumOfHomeSOW, Sum(TeamFarmStatCareer.HomeSOL) AS SumOfHomeSOL, Sum(TeamFarmStatCareer.HomeGF) AS SumOfHomeGF, Sum(TeamFarmStatCareer.HomeGA) AS SumOfHomeGA, Sum(TeamFarmStatCareer.PPAttemp) AS SumOfPPAttemp, Sum(TeamFarmStatCareer.PPGoal) AS SumOfPPGoal, Sum(TeamFarmStatCareer.PKAttemp) AS SumOfPKAttemp, Sum(TeamFarmStatCareer.PKGoalGA) AS SumOfPKGoalGA, Sum(TeamFarmStatCareer.PKGoalGF) AS SumOfPKGoalGF, Sum(TeamFarmStatCareer.ShotsFor) AS SumOfShotsFor, Sum(TeamFarmStatCareer.ShotsAga) AS SumOfShotsAga, Sum(TeamFarmStatCareer.ShotsBlock) AS SumOfShotsBlock, Sum(TeamFarmStatCareer.ShotsPerPeriod1) AS SumOfShotsPerPeriod1, Sum(TeamFarmStatCareer.ShotsPerPeriod2) AS SumOfShotsPerPeriod2, Sum(TeamFarmStatCareer.ShotsPerPeriod3) AS SumOfShotsPerPeriod3, Sum(TeamFarmStatCareer.ShotsPerPeriod4) AS SumOfShotsPerPeriod4, Sum(TeamFarmStatCareer.GoalsPerPeriod1) AS SumOfGoalsPerPeriod1, Sum(TeamFarmStatCareer.GoalsPerPeriod2) AS SumOfGoalsPerPeriod2, Sum(TeamFarmStatCareer.GoalsPerPeriod3) AS SumOfGoalsPerPeriod3, Sum(TeamFarmStatCareer.GoalsPerPeriod4) AS SumOfGoalsPerPeriod4, Sum(TeamFarmStatCareer.PuckTimeInZoneDF) AS SumOfPuckTimeInZoneDF, Sum(TeamFarmStatCareer.PuckTimeInZoneOF) AS SumOfPuckTimeInZoneOF, Sum(TeamFarmStatCareer.PuckTimeInZoneNT) AS SumOfPuckTimeInZoneNT, Sum(TeamFarmStatCareer.PuckTimeControlinZoneDF) AS SumOfPuckTimeControlinZoneDF, Sum(TeamFarmStatCareer.PuckTimeControlinZoneOF) AS SumOfPuckTimeControlinZoneOF, Sum(TeamFarmStatCareer.PuckTimeControlinZoneNT) AS SumOfPuckTimeControlinZoneNT, Sum(TeamFarmStatCareer.Shutouts) AS SumOfShutouts, Sum(TeamFarmStatCareer.TotalGoal) AS SumOfTotalGoal, Sum(TeamFarmStatCareer.TotalAssist) AS SumOfTotalAssist, Sum(TeamFarmStatCareer.TotalPoint) AS SumOfTotalPoint, Sum(TeamFarmStatCareer.Pim) AS SumOfPim, Sum(TeamFarmStatCareer.Hits) AS SumOfHits, Sum(TeamFarmStatCareer.FaceOffWonDefensifZone) AS SumOfFaceOffWonDefensifZone, Sum(TeamFarmStatCareer.FaceOffTotalDefensifZone) AS SumOfFaceOffTotalDefensifZone, Sum(TeamFarmStatCareer.FaceOffWonOffensifZone) AS SumOfFaceOffWonOffensifZone, Sum(TeamFarmStatCareer.FaceOffTotalOffensifZone) AS SumOfFaceOffTotalOffensifZone, Sum(TeamFarmStatCareer.FaceOffWonNeutralZone) AS SumOfFaceOffWonNeutralZone, Sum(TeamFarmStatCareer.FaceOffTotalNeutralZone) AS SumOfFaceOffTotalNeutralZone, Sum(TeamFarmStatCareer.EmptyNetGoal) AS SumOfEmptyNetGoal FROM TeamFarmStatCareer WHERE Playoff = 'False' AND (UniqueID = " . $TeamInfo ['UniqueID'] . " OR Name = '" . str_replace("'","''",$TeamName) . "')";
			$TeamCareerSumSeasonOnly = $CareerStatdb->querySingle($Query,true);	
			$Query = "SELECT Sum(TeamFarmStatCareer.GP) AS SumOfGP, Sum(TeamFarmStatCareer.W) AS SumOfW, Sum(TeamFarmStatCareer.L) AS SumOfL, Sum(TeamFarmStatCareer.T) AS SumOfT, Sum(TeamFarmStatCareer.OTW) AS SumOfOTW, Sum(TeamFarmStatCareer.OTL) AS SumOfOTL, Sum(TeamFarmStatCareer.SOW) AS SumOfSOW, Sum(TeamFarmStatCareer.SOL) AS SumOfSOL, Sum(TeamFarmStatCareer.Points) AS SumOfPoints, Sum(TeamFarmStatCareer.GF) AS SumOfGF, Sum(TeamFarmStatCareer.GA) AS SumOfGA, Sum(TeamFarmStatCareer.HomeGP) AS SumOfHomeGP, Sum(TeamFarmStatCareer.HomeW) AS SumOfHomeW, Sum(TeamFarmStatCareer.HomeL) AS SumOfHomeL, Sum(TeamFarmStatCareer.HomeT) AS SumOfHomeT, Sum(TeamFarmStatCareer.HomeOTW) AS SumOfHomeOTW, Sum(TeamFarmStatCareer.HomeOTL) AS SumOfHomeOTL, Sum(TeamFarmStatCareer.HomeSOW) AS SumOfHomeSOW, Sum(TeamFarmStatCareer.HomeSOL) AS SumOfHomeSOL, Sum(TeamFarmStatCareer.HomeGF) AS SumOfHomeGF, Sum(TeamFarmStatCareer.HomeGA) AS SumOfHomeGA, Sum(TeamFarmStatCareer.PPAttemp) AS SumOfPPAttemp, Sum(TeamFarmStatCareer.PPGoal) AS SumOfPPGoal, Sum(TeamFarmStatCareer.PKAttemp) AS SumOfPKAttemp, Sum(TeamFarmStatCareer.PKGoalGA) AS SumOfPKGoalGA, Sum(TeamFarmStatCareer.PKGoalGF) AS SumOfPKGoalGF, Sum(TeamFarmStatCareer.ShotsFor) AS SumOfShotsFor, Sum(TeamFarmStatCareer.ShotsAga) AS SumOfShotsAga, Sum(TeamFarmStatCareer.ShotsBlock) AS SumOfShotsBlock, Sum(TeamFarmStatCareer.ShotsPerPeriod1) AS SumOfShotsPerPeriod1, Sum(TeamFarmStatCareer.ShotsPerPeriod2) AS SumOfShotsPerPeriod2, Sum(TeamFarmStatCareer.ShotsPerPeriod3) AS SumOfShotsPerPeriod3, Sum(TeamFarmStatCareer.ShotsPerPeriod4) AS SumOfShotsPerPeriod4, Sum(TeamFarmStatCareer.GoalsPerPeriod1) AS SumOfGoalsPerPeriod1, Sum(TeamFarmStatCareer.GoalsPerPeriod2) AS SumOfGoalsPerPeriod2, Sum(TeamFarmStatCareer.GoalsPerPeriod3) AS SumOfGoalsPerPeriod3, Sum(TeamFarmStatCareer.GoalsPerPeriod4) AS SumOfGoalsPerPeriod4, Sum(TeamFarmStatCareer.PuckTimeInZoneDF) AS SumOfPuckTimeInZoneDF, Sum(TeamFarmStatCareer.PuckTimeInZoneOF) AS SumOfPuckTimeInZoneOF, Sum(TeamFarmStatCareer.PuckTimeInZoneNT) AS SumOfPuckTimeInZoneNT, Sum(TeamFarmStatCareer.PuckTimeControlinZoneDF) AS SumOfPuckTimeControlinZoneDF, Sum(TeamFarmStatCareer.PuckTimeControlinZoneOF) AS SumOfPuckTimeControlinZoneOF, Sum(TeamFarmStatCareer.PuckTimeControlinZoneNT) AS SumOfPuckTimeControlinZoneNT, Sum(TeamFarmStatCareer.Shutouts) AS SumOfShutouts, Sum(TeamFarmStatCareer.TotalGoal) AS SumOfTotalGoal, Sum(TeamFarmStatCareer.TotalAssist) AS SumOfTotalAssist, Sum(TeamFarmStatCareer.TotalPoint) AS SumOfTotalPoint, Sum(TeamFarmStatCareer.Pim) AS SumOfPim, Sum(TeamFarmStatCareer.Hits) AS SumOfHits, Sum(TeamFarmStatCareer.FaceOffWonDefensifZone) AS SumOfFaceOffWonDefensifZone, Sum(TeamFarmStatCareer.FaceOffTotalDefensifZone) AS SumOfFaceOffTotalDefensifZone, Sum(TeamFarmStatCareer.FaceOffWonOffensifZone) AS SumOfFaceOffWonOffensifZone, Sum(TeamFarmStatCareer.FaceOffTotalOffensifZone) AS SumOfFaceOffTotalOffensifZone, Sum(TeamFarmStatCareer.FaceOffWonNeutralZone) AS SumOfFaceOffWonNeutralZone, Sum(TeamFarmStatCareer.FaceOffTotalNeutralZone) AS SumOfFaceOffTotalNeutralZone, Sum(TeamFarmStatCareer.EmptyNetGoal) AS SumOfEmptyNetGoal, Count(TeamFarmStatCareer.Playoff) AS SumOfPlayoff FROM TeamFarmStatCareer WHERE Playoff = 'True' AND (UniqueID = " . $TeamInfo ['UniqueID'] . " OR Name = '" . str_replace("'","''",$TeamName) . "')";
			$TeamCareerSumPlayoffOnly = $CareerStatdb->querySingle($Query,true);
			
			$CareerDBFormatV2CheckCheck = $db->querySingle("SELECT Count(name) AS CountName FROM sqlite_master WHERE type='table' AND name='LeagueGeneral'",true);
			If ($CareerDBFormatV2CheckCheck['CountName'] == 1){
				$Query = "Select Count(PlayOffWinnerFarm) As CupWinner From LeagueGeneral Where Playoff = 'True' AND PlayOffWinnerFarm = " . $TeamInfo['UniqueID'];
				$CupWinner = $CareerStatdb->querySingle($Query,true);
			}else{
				$CupWinner = Null;
			}				
			
			$TeamCareerStatFound = true;
			
			$CareerStatdb->query("ATTACH DATABASE '".realpath($DatabaseFile)."' AS CurrentDB");
			
			/* Players Career Stat Leader Season */
			$PlayoffString = (string)"False";
			$Query = "SELECT MainTable.Number, MainTable.UniqueID, '0' As TeamThemeID, MainTable.Name, MainTable.Team, MainTable.TeamName, Sum(MainTable.GP) AS GP, Sum(MainTable.Shots) AS Shots, Sum(MainTable.G) AS G, Sum(MainTable.A) AS A, Sum(MainTable.P) AS P, Sum(MainTable.PlusMinus) AS PlusMinus, Sum(MainTable.Pim) AS Pim, Sum(MainTable.Pim5) AS Pim5, Sum(MainTable.ShotsBlock) AS ShotsBlock, Sum(MainTable.OwnShotsBlock) AS OwnShotsBlock, Sum(MainTable.OwnShotsMissGoal) AS OwnShotsMissGoal, Sum(MainTable.Hits) AS Hits, Sum(MainTable.HitsTook) AS HitsTook, Sum(MainTable.GW) AS GW, Sum(MainTable.GT) AS GT, Sum(MainTable.FaceOffWon) AS FaceOffWon, Sum(MainTable.FaceOffTotal) AS FaceOffTotal, Sum(MainTable.PenalityShotsScore) AS PenalityShotsScore, Sum(MainTable.PenalityShotsTotal) AS PenalityShotsTotal, Sum(MainTable.EmptyNetGoal) AS EmptyNetGoal, Sum(MainTable.SecondPlay) AS SecondPlay, Sum(MainTable.HatTrick) AS HatTrick, Sum(MainTable.PPG) AS PPG, Sum(MainTable.PPA) AS PPA, Sum(MainTable.PPP) AS PPP, Sum(MainTable.PPShots) AS PPShots, Sum(MainTable.PPSecondPlay) AS PPSecondPlay, Sum(MainTable.PKG) AS PKG, Sum(MainTable.PKA) AS PKA, Sum(MainTable.PKP) AS PKP, Sum(MainTable.PKShots) AS PKShots, Sum(MainTable.PKSecondPlay) AS PKSecondPlay, Sum(MainTable.GiveAway) AS GiveAway, Sum(MainTable.TakeAway) AS TakeAway, Sum(MainTable.PuckPossesionTime) AS PuckPossesionTime, Sum(MainTable.FightW) AS FightW, Sum(MainTable.FightL) AS FightL, Sum(MainTable.FightT) AS FightT, Sum(MainTable.Star1) AS Star1, Sum(MainTable.Star2) AS Star2, Sum(MainTable.Star3) AS Star3,  ROUND((CAST(Sum(MainTable.G) AS REAL) / (Sum(MainTable.Shots)))*100,2) AS ShotsPCT, ROUND((CAST(Sum(MainTable.SecondPlay) AS REAL) / 60 / (Sum(MainTable.GP))),2) AS AMG,ROUND((CAST(Sum(MainTable.FaceOffWon) AS REAL) / (Sum(MainTable.FaceOffTotal)))*100,2) as FaceoffPCT,ROUND((CAST(Sum(MainTable.P) AS REAL) / (Sum(MainTable.SecondPlay)) * 60 * 20),2) AS P20";
			if($LeagueGeneral['PlayOffStarted'] == $PlayoffString){
				/* Regular Query */
				$Query = $Query . " FROM (Select MainLive.* FROM (SELECT '". $LeagueGeneral['LeagueYear'] . "' as Year, Player" . $TypeText . "Stat.Number, Player" . $TypeText . "Stat.UniqueID, '0' As TeamThemeID, Player" . $TypeText . "Stat.Name, Player" . $TypeText . "Stat.GP, Player" . $TypeText . "Stat.Shots, Player" . $TypeText . "Stat.G, Player" . $TypeText . "Stat.A, Player" . $TypeText . "Stat.P, Player" . $TypeText . "Stat.PlusMinus, Player" . $TypeText . "Stat.Pim, Player" . $TypeText . "Stat.Pim5, Player" . $TypeText . "Stat.ShotsBlock, Player" . $TypeText . "Stat.OwnShotsBlock, Player" . $TypeText . "Stat.OwnShotsMissGoal, Player" . $TypeText . "Stat.Hits, Player" . $TypeText . "Stat.HitsTook, Player" . $TypeText . "Stat.GW, Player" . $TypeText . "Stat.GT, Player" . $TypeText . "Stat.FaceOffWon, Player" . $TypeText . "Stat.FaceOffTotal, Player" . $TypeText . "Stat.PenalityShotsScore, Player" . $TypeText . "Stat.PenalityShotsTotal, Player" . $TypeText . "Stat.EmptyNetGoal, Player" . $TypeText . "Stat.SecondPlay, Player" . $TypeText . "Stat.HatTrick, Player" . $TypeText . "Stat.PPG, Player" . $TypeText . "Stat.PPA, Player" . $TypeText . "Stat.PPP, Player" . $TypeText . "Stat.PPShots, Player" . $TypeText . "Stat.PPSecondPlay, Player" . $TypeText . "Stat.PKG, Player" . $TypeText . "Stat.PKA, Player" . $TypeText . "Stat.PKP, Player" . $TypeText . "Stat.PKShots, Player" . $TypeText . "Stat.PKSecondPlay, Player" . $TypeText . "Stat.GiveAway, Player" . $TypeText . "Stat.TakeAway, Player" . $TypeText . "Stat.PuckPossesionTime, Player" . $TypeText . "Stat.FightW, Player" . $TypeText . "Stat.FightL, Player" . $TypeText . "Stat.FightT, Player" . $TypeText . "Stat.Star1, Player" . $TypeText . "Stat.Star2, Player" . $TypeText . "Stat.Star3, PlayerInfo.PosC, PlayerInfo.PosLW, PlayerInfo.PosRW, PlayerInfo.PosD, PlayerInfo.Team, PlayerInfo.TeamName, PlayerInfo.Rookie, ROUND((CAST(Player" . $TypeText . "Stat.G AS REAL) / (Player" . $TypeText . "Stat.Shots))*100,2) AS ShotsPCT, ROUND((CAST(Player" . $TypeText . "Stat.SecondPlay AS REAL) / 60 / (Player" . $TypeText . "Stat.GP)),2) AS AMG,ROUND((CAST(Player" . $TypeText . "Stat.FaceOffWon AS REAL) / (Player" . $TypeText . "Stat.FaceOffTotal))*100,2) as FaceoffPCT,ROUND((CAST(Player" . $TypeText . "Stat.P AS REAL) / (Player" . $TypeText . "Stat.SecondPlay) * 60 * 20),2) AS P20 FROM PlayerInfo INNER JOIN Player" . $TypeText . "Stat ON PlayerInfo.Number = Player" . $TypeText . "Stat.Number WHERE Player" . $TypeText . "Stat.GP > 0) AS MainLive UNION ALL Select MainHistory.* FROM (SELECT Player" . $TypeText . "StatHistory.Year, Player" . $TypeText . "StatHistory.Number, Player" . $TypeText . "StatHistory.UniqueID, '0' As TeamThemeID, Player" . $TypeText . "StatHistory.Name, Player" . $TypeText . "StatHistory.GP, Player" . $TypeText . "StatHistory.Shots, Player" . $TypeText . "StatHistory.G, Player" . $TypeText . "StatHistory.A, Player" . $TypeText . "StatHistory.P, Player" . $TypeText . "StatHistory.PlusMinus, Player" . $TypeText . "StatHistory.Pim, Player" . $TypeText . "StatHistory.Pim5, Player" . $TypeText . "StatHistory.ShotsBlock, Player" . $TypeText . "StatHistory.OwnShotsBlock, Player" . $TypeText . "StatHistory.OwnShotsMissGoal, Player" . $TypeText . "StatHistory.Hits, Player" . $TypeText . "StatHistory.HitsTook, Player" . $TypeText . "StatHistory.GW, Player" . $TypeText . "StatHistory.GT, Player" . $TypeText . "StatHistory.FaceOffWon, Player" . $TypeText . "StatHistory.FaceOffTotal, Player" . $TypeText . "StatHistory.PenalityShotsScore, Player" . $TypeText . "StatHistory.PenalityShotsTotal, Player" . $TypeText . "StatHistory.EmptyNetGoal, Player" . $TypeText . "StatHistory.SecondPlay, Player" . $TypeText . "StatHistory.HatTrick, Player" . $TypeText . "StatHistory.PPG, Player" . $TypeText . "StatHistory.PPA, Player" . $TypeText . "StatHistory.PPP, Player" . $TypeText . "StatHistory.PPShots, Player" . $TypeText . "StatHistory.PPSecondPlay, Player" . $TypeText . "StatHistory.PKG, Player" . $TypeText . "StatHistory.PKA, Player" . $TypeText . "StatHistory.PKP, Player" . $TypeText . "StatHistory.PKShots, Player" . $TypeText . "StatHistory.PKSecondPlay, Player" . $TypeText . "StatHistory.GiveAway, Player" . $TypeText . "StatHistory.TakeAway, Player" . $TypeText . "StatHistory.PuckPossesionTime, Player" . $TypeText . "StatHistory.FightW, Player" . $TypeText . "StatHistory.FightL, Player" . $TypeText . "StatHistory.FightT, Player" . $TypeText . "StatHistory.Star1, Player" . $TypeText . "StatHistory.Star2, Player" . $TypeText . "StatHistory.Star3, PlayerInfoHistory.PosC, PlayerInfoHistory.PosLW, PlayerInfoHistory.PosRW, PlayerInfoHistory.PosD, PlayerInfoHistory.Team, PlayerInfoHistory.ProTeamName AS TeamName, PlayerInfoHistory.Rookie,  ROUND((CAST(Player" . $TypeText . "StatHistory.G AS REAL) / (Player" . $TypeText . "StatHistory.Shots))*100,2) AS ShotsPCT, ROUND((CAST(Player" . $TypeText . "StatHistory.SecondPlay AS REAL) / 60 / (Player" . $TypeText . "StatHistory.GP)),2) AS AMG,ROUND((CAST(Player" . $TypeText . "StatHistory.FaceOffWon AS REAL) / (Player" . $TypeText . "StatHistory.FaceOffTotal))*100,2) as FaceoffPCT,ROUND((CAST(Player" . $TypeText . "StatHistory.P AS REAL) / (Player" . $TypeText . "StatHistory.SecondPlay) * 60 * 20),2) AS P20 FROM PlayerInfoHistory INNER JOIN Player" . $TypeText . "StatHistory ON PlayerInfoHistory.Number = Player" . $TypeText . "StatHistory.Number AND PlayerInfoHistory.Year = Player" . $TypeText . "StatHistory.Year AND PlayerInfoHistory.Playoff = Player" . $TypeText . "StatHistory.Playoff  WHERE Player" . $TypeText . "StatHistory.GP > 0 AND PlayerInfoHistory.Playoff = '" . $PlayoffString. "') AS MainHistory) AS MainTable";
			}else{
				/* Requesting Playoff While in Season or Requesting Season while in Playoff - Do not fetch data from live database */		
				$Query = $Query . " FROM (SELECT Player" . $TypeText . "StatHistory.Year, Player" . $TypeText . "StatHistory.Number, Player" . $TypeText . "StatHistory.UniqueID, '0' As TeamThemeID, Player" . $TypeText . "StatHistory.Name, Player" . $TypeText . "StatHistory.GP, Player" . $TypeText . "StatHistory.Shots, Player" . $TypeText . "StatHistory.G, Player" . $TypeText . "StatHistory.A, Player" . $TypeText . "StatHistory.P, Player" . $TypeText . "StatHistory.PlusMinus, Player" . $TypeText . "StatHistory.Pim, Player" . $TypeText . "StatHistory.Pim5, Player" . $TypeText . "StatHistory.ShotsBlock, Player" . $TypeText . "StatHistory.OwnShotsBlock, Player" . $TypeText . "StatHistory.OwnShotsMissGoal, Player" . $TypeText . "StatHistory.Hits, Player" . $TypeText . "StatHistory.HitsTook, Player" . $TypeText . "StatHistory.GW, Player" . $TypeText . "StatHistory.GT, Player" . $TypeText . "StatHistory.FaceOffWon, Player" . $TypeText . "StatHistory.FaceOffTotal, Player" . $TypeText . "StatHistory.PenalityShotsScore, Player" . $TypeText . "StatHistory.PenalityShotsTotal, Player" . $TypeText . "StatHistory.EmptyNetGoal, Player" . $TypeText . "StatHistory.SecondPlay, Player" . $TypeText . "StatHistory.HatTrick, Player" . $TypeText . "StatHistory.PPG, Player" . $TypeText . "StatHistory.PPA, Player" . $TypeText . "StatHistory.PPP, Player" . $TypeText . "StatHistory.PPShots, Player" . $TypeText . "StatHistory.PPSecondPlay, Player" . $TypeText . "StatHistory.PKG, Player" . $TypeText . "StatHistory.PKA, Player" . $TypeText . "StatHistory.PKP, Player" . $TypeText . "StatHistory.PKShots, Player" . $TypeText . "StatHistory.PKSecondPlay, Player" . $TypeText . "StatHistory.GiveAway, Player" . $TypeText . "StatHistory.TakeAway, Player" . $TypeText . "StatHistory.PuckPossesionTime, Player" . $TypeText . "StatHistory.FightW, Player" . $TypeText . "StatHistory.FightL, Player" . $TypeText . "StatHistory.FightT, Player" . $TypeText . "StatHistory.Star1, Player" . $TypeText . "StatHistory.Star2, Player" . $TypeText . "StatHistory.Star3, PlayerInfoHistory.PosC, PlayerInfoHistory.PosLW, PlayerInfoHistory.PosRW, PlayerInfoHistory.PosD, PlayerInfoHistory.Team, PlayerInfoHistory.ProTeamName AS TeamName, PlayerInfoHistory.Rookie, ROUND((CAST(Player" . $TypeText . "StatHistory.G AS REAL) / (Player" . $TypeText . "StatHistory.Shots))*100,2) AS ShotsPCT, ROUND((CAST(Player" . $TypeText . "StatHistory.SecondPlay AS REAL) / 60 / (Player" . $TypeText . "StatHistory.GP)),2) AS AMG,ROUND((CAST(Player" . $TypeText . "StatHistory.FaceOffWon AS REAL) / (Player" . $TypeText . "StatHistory.FaceOffTotal))*100,2) as FaceoffPCT,ROUND((CAST(Player" . $TypeText . "StatHistory.P AS REAL) / (Player" . $TypeText . "StatHistory.SecondPlay) * 60 * 20),2) AS P20 FROM PlayerInfoHistory INNER JOIN Player" . $TypeText . "StatHistory ON PlayerInfoHistory.Number = Player" . $TypeText . "StatHistory.Number AND PlayerInfoHistory.Year = Player" . $TypeText . "StatHistory.Year AND PlayerInfoHistory.Playoff = Player" . $TypeText . "StatHistory.Playoff WHERE Player" . $TypeText . "StatHistory.GP > 0 AND PlayerInfoHistory.Playoff = '" . $PlayoffString. "') AS MainTable";
			}
			$Query = $Query . " WHERE MainTable.Team = " . $Team . " GROUP BY UniqueID ORDER BY Sum(MainTable.P) DESC LIMIT 5";
			$TeamCareerPlayersSeasonTop5 = $CareerStatdb->query($Query);
			
			/* Players Career Stat Leader Playoff */
			$PlayoffString = (string)"True";
			$Query = "SELECT MainTable.Number, MainTable.UniqueID, '0' As TeamThemeID, MainTable.Name, MainTable.Team, MainTable.TeamName, Sum(MainTable.GP) AS GP, Sum(MainTable.Shots) AS Shots, Sum(MainTable.G) AS G, Sum(MainTable.A) AS A, Sum(MainTable.P) AS P, Sum(MainTable.PlusMinus) AS PlusMinus, Sum(MainTable.Pim) AS Pim, Sum(MainTable.Pim5) AS Pim5, Sum(MainTable.ShotsBlock) AS ShotsBlock, Sum(MainTable.OwnShotsBlock) AS OwnShotsBlock, Sum(MainTable.OwnShotsMissGoal) AS OwnShotsMissGoal, Sum(MainTable.Hits) AS Hits, Sum(MainTable.HitsTook) AS HitsTook, Sum(MainTable.GW) AS GW, Sum(MainTable.GT) AS GT, Sum(MainTable.FaceOffWon) AS FaceOffWon, Sum(MainTable.FaceOffTotal) AS FaceOffTotal, Sum(MainTable.PenalityShotsScore) AS PenalityShotsScore, Sum(MainTable.PenalityShotsTotal) AS PenalityShotsTotal, Sum(MainTable.EmptyNetGoal) AS EmptyNetGoal, Sum(MainTable.SecondPlay) AS SecondPlay, Sum(MainTable.HatTrick) AS HatTrick, Sum(MainTable.PPG) AS PPG, Sum(MainTable.PPA) AS PPA, Sum(MainTable.PPP) AS PPP, Sum(MainTable.PPShots) AS PPShots, Sum(MainTable.PPSecondPlay) AS PPSecondPlay, Sum(MainTable.PKG) AS PKG, Sum(MainTable.PKA) AS PKA, Sum(MainTable.PKP) AS PKP, Sum(MainTable.PKShots) AS PKShots, Sum(MainTable.PKSecondPlay) AS PKSecondPlay, Sum(MainTable.GiveAway) AS GiveAway, Sum(MainTable.TakeAway) AS TakeAway, Sum(MainTable.PuckPossesionTime) AS PuckPossesionTime, Sum(MainTable.FightW) AS FightW, Sum(MainTable.FightL) AS FightL, Sum(MainTable.FightT) AS FightT, Sum(MainTable.Star1) AS Star1, Sum(MainTable.Star2) AS Star2, Sum(MainTable.Star3) AS Star3,  ROUND((CAST(Sum(MainTable.G) AS REAL) / (Sum(MainTable.Shots)))*100,2) AS ShotsPCT, ROUND((CAST(Sum(MainTable.SecondPlay) AS REAL) / 60 / (Sum(MainTable.GP))),2) AS AMG,ROUND((CAST(Sum(MainTable.FaceOffWon) AS REAL) / (Sum(MainTable.FaceOffTotal)))*100,2) as FaceoffPCT,ROUND((CAST(Sum(MainTable.P) AS REAL) / (Sum(MainTable.SecondPlay)) * 60 * 20),2) AS P20";
			if($LeagueGeneral['PlayOffStarted'] == $PlayoffString){
				/* Regular Query */
				$Query = $Query . " FROM (Select MainLive.* FROM (SELECT '". $LeagueGeneral['LeagueYear'] . "' as Year, Player" . $TypeText . "Stat.Number, Player" . $TypeText . "Stat.UniqueID, '0' As TeamThemeID, Player" . $TypeText . "Stat.Name, Player" . $TypeText . "Stat.GP, Player" . $TypeText . "Stat.Shots, Player" . $TypeText . "Stat.G, Player" . $TypeText . "Stat.A, Player" . $TypeText . "Stat.P, Player" . $TypeText . "Stat.PlusMinus, Player" . $TypeText . "Stat.Pim, Player" . $TypeText . "Stat.Pim5, Player" . $TypeText . "Stat.ShotsBlock, Player" . $TypeText . "Stat.OwnShotsBlock, Player" . $TypeText . "Stat.OwnShotsMissGoal, Player" . $TypeText . "Stat.Hits, Player" . $TypeText . "Stat.HitsTook, Player" . $TypeText . "Stat.GW, Player" . $TypeText . "Stat.GT, Player" . $TypeText . "Stat.FaceOffWon, Player" . $TypeText . "Stat.FaceOffTotal, Player" . $TypeText . "Stat.PenalityShotsScore, Player" . $TypeText . "Stat.PenalityShotsTotal, Player" . $TypeText . "Stat.EmptyNetGoal, Player" . $TypeText . "Stat.SecondPlay, Player" . $TypeText . "Stat.HatTrick, Player" . $TypeText . "Stat.PPG, Player" . $TypeText . "Stat.PPA, Player" . $TypeText . "Stat.PPP, Player" . $TypeText . "Stat.PPShots, Player" . $TypeText . "Stat.PPSecondPlay, Player" . $TypeText . "Stat.PKG, Player" . $TypeText . "Stat.PKA, Player" . $TypeText . "Stat.PKP, Player" . $TypeText . "Stat.PKShots, Player" . $TypeText . "Stat.PKSecondPlay, Player" . $TypeText . "Stat.GiveAway, Player" . $TypeText . "Stat.TakeAway, Player" . $TypeText . "Stat.PuckPossesionTime, Player" . $TypeText . "Stat.FightW, Player" . $TypeText . "Stat.FightL, Player" . $TypeText . "Stat.FightT, Player" . $TypeText . "Stat.Star1, Player" . $TypeText . "Stat.Star2, Player" . $TypeText . "Stat.Star3, PlayerInfo.PosC, PlayerInfo.PosLW, PlayerInfo.PosRW, PlayerInfo.PosD, PlayerInfo.Team, PlayerInfo.TeamName, PlayerInfo.Rookie, ROUND((CAST(Player" . $TypeText . "Stat.G AS REAL) / (Player" . $TypeText . "Stat.Shots))*100,2) AS ShotsPCT, ROUND((CAST(Player" . $TypeText . "Stat.SecondPlay AS REAL) / 60 / (Player" . $TypeText . "Stat.GP)),2) AS AMG,ROUND((CAST(Player" . $TypeText . "Stat.FaceOffWon AS REAL) / (Player" . $TypeText . "Stat.FaceOffTotal))*100,2) as FaceoffPCT,ROUND((CAST(Player" . $TypeText . "Stat.P AS REAL) / (Player" . $TypeText . "Stat.SecondPlay) * 60 * 20),2) AS P20 FROM PlayerInfo INNER JOIN Player" . $TypeText . "Stat ON PlayerInfo.Number = Player" . $TypeText . "Stat.Number WHERE Player" . $TypeText . "Stat.GP > 0) AS MainLive UNION ALL Select MainHistory.* FROM (SELECT Player" . $TypeText . "StatHistory.Year, Player" . $TypeText . "StatHistory.Number, Player" . $TypeText . "StatHistory.UniqueID, '0' As TeamThemeID, Player" . $TypeText . "StatHistory.Name, Player" . $TypeText . "StatHistory.GP, Player" . $TypeText . "StatHistory.Shots, Player" . $TypeText . "StatHistory.G, Player" . $TypeText . "StatHistory.A, Player" . $TypeText . "StatHistory.P, Player" . $TypeText . "StatHistory.PlusMinus, Player" . $TypeText . "StatHistory.Pim, Player" . $TypeText . "StatHistory.Pim5, Player" . $TypeText . "StatHistory.ShotsBlock, Player" . $TypeText . "StatHistory.OwnShotsBlock, Player" . $TypeText . "StatHistory.OwnShotsMissGoal, Player" . $TypeText . "StatHistory.Hits, Player" . $TypeText . "StatHistory.HitsTook, Player" . $TypeText . "StatHistory.GW, Player" . $TypeText . "StatHistory.GT, Player" . $TypeText . "StatHistory.FaceOffWon, Player" . $TypeText . "StatHistory.FaceOffTotal, Player" . $TypeText . "StatHistory.PenalityShotsScore, Player" . $TypeText . "StatHistory.PenalityShotsTotal, Player" . $TypeText . "StatHistory.EmptyNetGoal, Player" . $TypeText . "StatHistory.SecondPlay, Player" . $TypeText . "StatHistory.HatTrick, Player" . $TypeText . "StatHistory.PPG, Player" . $TypeText . "StatHistory.PPA, Player" . $TypeText . "StatHistory.PPP, Player" . $TypeText . "StatHistory.PPShots, Player" . $TypeText . "StatHistory.PPSecondPlay, Player" . $TypeText . "StatHistory.PKG, Player" . $TypeText . "StatHistory.PKA, Player" . $TypeText . "StatHistory.PKP, Player" . $TypeText . "StatHistory.PKShots, Player" . $TypeText . "StatHistory.PKSecondPlay, Player" . $TypeText . "StatHistory.GiveAway, Player" . $TypeText . "StatHistory.TakeAway, Player" . $TypeText . "StatHistory.PuckPossesionTime, Player" . $TypeText . "StatHistory.FightW, Player" . $TypeText . "StatHistory.FightL, Player" . $TypeText . "StatHistory.FightT, Player" . $TypeText . "StatHistory.Star1, Player" . $TypeText . "StatHistory.Star2, Player" . $TypeText . "StatHistory.Star3, PlayerInfoHistory.PosC, PlayerInfoHistory.PosLW, PlayerInfoHistory.PosRW, PlayerInfoHistory.PosD, PlayerInfoHistory.Team, PlayerInfoHistory.ProTeamName AS TeamName, PlayerInfoHistory.Rookie,  ROUND((CAST(Player" . $TypeText . "StatHistory.G AS REAL) / (Player" . $TypeText . "StatHistory.Shots))*100,2) AS ShotsPCT, ROUND((CAST(Player" . $TypeText . "StatHistory.SecondPlay AS REAL) / 60 / (Player" . $TypeText . "StatHistory.GP)),2) AS AMG,ROUND((CAST(Player" . $TypeText . "StatHistory.FaceOffWon AS REAL) / (Player" . $TypeText . "StatHistory.FaceOffTotal))*100,2) as FaceoffPCT,ROUND((CAST(Player" . $TypeText . "StatHistory.P AS REAL) / (Player" . $TypeText . "StatHistory.SecondPlay) * 60 * 20),2) AS P20 FROM PlayerInfoHistory INNER JOIN Player" . $TypeText . "StatHistory ON PlayerInfoHistory.Number = Player" . $TypeText . "StatHistory.Number AND PlayerInfoHistory.Year = Player" . $TypeText . "StatHistory.Year AND PlayerInfoHistory.Playoff = Player" . $TypeText . "StatHistory.Playoff  WHERE Player" . $TypeText . "StatHistory.GP > 0 AND PlayerInfoHistory.Playoff = '" . $PlayoffString. "') AS MainHistory) AS MainTable";
			}else{
				/* Requesting Playoff While in Season or Requesting Season while in Playoff - Do not fetch data from live database */		
				$Query = $Query . " FROM (SELECT Player" . $TypeText . "StatHistory.Year, Player" . $TypeText . "StatHistory.Number, Player" . $TypeText . "StatHistory.UniqueID, '0' As TeamThemeID, Player" . $TypeText . "StatHistory.Name, Player" . $TypeText . "StatHistory.GP, Player" . $TypeText . "StatHistory.Shots, Player" . $TypeText . "StatHistory.G, Player" . $TypeText . "StatHistory.A, Player" . $TypeText . "StatHistory.P, Player" . $TypeText . "StatHistory.PlusMinus, Player" . $TypeText . "StatHistory.Pim, Player" . $TypeText . "StatHistory.Pim5, Player" . $TypeText . "StatHistory.ShotsBlock, Player" . $TypeText . "StatHistory.OwnShotsBlock, Player" . $TypeText . "StatHistory.OwnShotsMissGoal, Player" . $TypeText . "StatHistory.Hits, Player" . $TypeText . "StatHistory.HitsTook, Player" . $TypeText . "StatHistory.GW, Player" . $TypeText . "StatHistory.GT, Player" . $TypeText . "StatHistory.FaceOffWon, Player" . $TypeText . "StatHistory.FaceOffTotal, Player" . $TypeText . "StatHistory.PenalityShotsScore, Player" . $TypeText . "StatHistory.PenalityShotsTotal, Player" . $TypeText . "StatHistory.EmptyNetGoal, Player" . $TypeText . "StatHistory.SecondPlay, Player" . $TypeText . "StatHistory.HatTrick, Player" . $TypeText . "StatHistory.PPG, Player" . $TypeText . "StatHistory.PPA, Player" . $TypeText . "StatHistory.PPP, Player" . $TypeText . "StatHistory.PPShots, Player" . $TypeText . "StatHistory.PPSecondPlay, Player" . $TypeText . "StatHistory.PKG, Player" . $TypeText . "StatHistory.PKA, Player" . $TypeText . "StatHistory.PKP, Player" . $TypeText . "StatHistory.PKShots, Player" . $TypeText . "StatHistory.PKSecondPlay, Player" . $TypeText . "StatHistory.GiveAway, Player" . $TypeText . "StatHistory.TakeAway, Player" . $TypeText . "StatHistory.PuckPossesionTime, Player" . $TypeText . "StatHistory.FightW, Player" . $TypeText . "StatHistory.FightL, Player" . $TypeText . "StatHistory.FightT, Player" . $TypeText . "StatHistory.Star1, Player" . $TypeText . "StatHistory.Star2, Player" . $TypeText . "StatHistory.Star3, PlayerInfoHistory.PosC, PlayerInfoHistory.PosLW, PlayerInfoHistory.PosRW, PlayerInfoHistory.PosD, PlayerInfoHistory.Team, PlayerInfoHistory.ProTeamName AS TeamName, PlayerInfoHistory.Rookie, ROUND((CAST(Player" . $TypeText . "StatHistory.G AS REAL) / (Player" . $TypeText . "StatHistory.Shots))*100,2) AS ShotsPCT, ROUND((CAST(Player" . $TypeText . "StatHistory.SecondPlay AS REAL) / 60 / (Player" . $TypeText . "StatHistory.GP)),2) AS AMG,ROUND((CAST(Player" . $TypeText . "StatHistory.FaceOffWon AS REAL) / (Player" . $TypeText . "StatHistory.FaceOffTotal))*100,2) as FaceoffPCT,ROUND((CAST(Player" . $TypeText . "StatHistory.P AS REAL) / (Player" . $TypeText . "StatHistory.SecondPlay) * 60 * 20),2) AS P20 FROM PlayerInfoHistory INNER JOIN Player" . $TypeText . "StatHistory ON PlayerInfoHistory.Number = Player" . $TypeText . "StatHistory.Number AND PlayerInfoHistory.Year = Player" . $TypeText . "StatHistory.Year AND PlayerInfoHistory.Playoff = Player" . $TypeText . "StatHistory.Playoff WHERE Player" . $TypeText . "StatHistory.GP > 0 AND PlayerInfoHistory.Playoff = '" . $PlayoffString. "') AS MainTable";
			}
			$Query = $Query . " WHERE MainTable.Team = " . $Team . " GROUP BY UniqueID ORDER BY Sum(MainTable.P) DESC LIMIT 5";
			$TeamCareerPlayersPlayoffTop5 = $CareerStatdb->query($Query);			


			/* Goalies Career Stat Leader Season */
			$PlayoffString = (string)"False";
			$Query = "SELECT MainTable.Number, MainTable.UniqueID, '0' As TeamThemeID, MainTable.Name, MainTable.Team, MainTable.TeamName, Sum(MainTable.GP) AS GP, Sum(MainTable.SecondPlay) AS SecondPlay, Sum(MainTable.W) AS W, Sum(MainTable.L) AS L, Sum(MainTable.OTL) AS OTL, Sum(MainTable.Shootout) AS Shootout, Sum(MainTable.GA) AS GA, Sum(MainTable.SA) AS SA, Sum(MainTable.SARebound) AS SARebound, Sum(MainTable.Pim) AS Pim, Sum(MainTable.A) AS A, Sum(MainTable.PenalityShotsShots) AS PenalityShotsShots, Sum(MainTable.PenalityShotsGoals) AS PenalityShotsGoals, Sum(MainTable.StartGoaler) AS StartGoaler, Sum(MainTable.BackupGoaler) AS BackupGoaler, Sum(MainTable.EmptyNetGoal) AS EmptyNetGoal, Sum(MainTable.Star1) AS Star1, Sum(MainTable.Star2) AS Star2, Sum(MainTable.Star3) AS Star3, ROUND((CAST(Sum(MainTable.GA) AS REAL) / (Sum(MainTable.SecondPlay) / 60))*60, 3) AS GAA, ROUND((CAST(Sum(MainTable.SA) - Sum(MainTable.GA) AS REAL) / (Sum(MainTable.SA))), 3) AS PCT, ROUND((CAST(Sum(MainTable.PenalityShotsShots) - Sum(MainTable.PenalityShotsGoals) AS REAL) / (Sum(MainTable.PenalityShotsShots))), 3) AS PenalityShotsPCT";
			if($LeagueGeneral['PlayOffStarted'] == $PlayoffString){
				/* Regular Query */	
				$Query = $Query . " FROM ( Select MainLive.* FROM (SELECT '". $LeagueGeneral['LeagueYear'] . "' as Year, GoalerInfo.TeamName, GoalerInfo.Team, GoalerInfo.Rookie, '0' As TeamThemeID, Goaler" . $TypeText . "Stat.Number, Goaler" . $TypeText . "Stat.UniqueID, Goaler" . $TypeText . "Stat.Name, Goaler" . $TypeText . "Stat.GP, Goaler" . $TypeText . "Stat.SecondPlay, Goaler" . $TypeText . "Stat.W, Goaler" . $TypeText . "Stat.L, Goaler" . $TypeText . "Stat.OTL, Goaler" . $TypeText . "Stat.Shootout, Goaler" . $TypeText . "Stat.GA, Goaler" . $TypeText . "Stat.SA, Goaler" . $TypeText . "Stat.SARebound, Goaler" . $TypeText . "Stat.Pim, Goaler" . $TypeText . "Stat.A, Goaler" . $TypeText . "Stat.PenalityShotsShots, Goaler" . $TypeText . "Stat.PenalityShotsGoals, Goaler" . $TypeText . "Stat.StartGoaler, Goaler" . $TypeText . "Stat.BackupGoaler, Goaler" . $TypeText . "Stat.EmptyNetGoal, Goaler" . $TypeText . "Stat.Star1, Goaler" . $TypeText . "Stat.Star2, Goaler" . $TypeText . "Stat.Star3, ROUND((CAST(Goaler" . $TypeText . "Stat.GA AS REAL) / (Goaler" . $TypeText . "Stat.SecondPlay / 60))*60, 3) AS GAA,  ROUND((CAST(Goaler" . $TypeText . "Stat.SA - Goaler" . $TypeText . "Stat.GA AS REAL) / (Goaler" . $TypeText . "Stat.SA)), 3) AS PCT,  ROUND((CAST(Goaler" . $TypeText . "Stat.PenalityShotsShots - Goaler" . $TypeText . "Stat.PenalityShotsGoals AS REAL) / (Goaler" . $TypeText . "Stat.PenalityShotsShots)), 3) AS PenalityShotsPCT FROM GoalerInfo INNER JOIN Goaler" . $TypeText . "Stat ON GoalerInfo.Number = Goaler" . $TypeText . "Stat.Number WHERE Goaler" . $TypeText . "Stat.GP > 0 ) AS MainLive UNION ALL Select MainHistory.* FROM (SELECT Goaler" . $TypeText . "StatHistory.Year, GoalerInfoHistory.ProTeamName AS TeamName,  GoalerInfoHistory.Team, GoalerInfoHistory.Rookie, '0' As TeamThemeID, Goaler" . $TypeText . "StatHistory.Number, Goaler" . $TypeText . "StatHistory.UniqueID, Goaler" . $TypeText . "StatHistory.Name, Goaler" . $TypeText . "StatHistory.GP, Goaler" . $TypeText . "StatHistory.SecondPlay, Goaler" . $TypeText . "StatHistory.W, Goaler" . $TypeText . "StatHistory.L, Goaler" . $TypeText . "StatHistory.OTL, Goaler" . $TypeText . "StatHistory.Shootout, Goaler" . $TypeText . "StatHistory.GA, Goaler" . $TypeText . "StatHistory.SA, Goaler" . $TypeText . "StatHistory.SARebound, Goaler" . $TypeText . "StatHistory.Pim, Goaler" . $TypeText . "StatHistory.A, Goaler" . $TypeText . "StatHistory.PenalityShotsShots, Goaler" . $TypeText . "StatHistory.PenalityShotsGoals, Goaler" . $TypeText . "StatHistory.StartGoaler, Goaler" . $TypeText . "StatHistory.BackupGoaler, Goaler" . $TypeText . "StatHistory.EmptyNetGoal, Goaler" . $TypeText . "StatHistory.Star1, Goaler" . $TypeText . "StatHistory.Star2, Goaler" . $TypeText . "StatHistory.Star3, ROUND((CAST(Goaler" . $TypeText . "StatHistory.GA AS REAL) / (Goaler" . $TypeText . "StatHistory.SecondPlay / 60))*60,3) AS GAA, ROUND((CAST(Goaler" . $TypeText . "StatHistory.SA - Goaler" . $TypeText . "StatHistory.GA AS REAL) / (Goaler" . $TypeText . "StatHistory.SA)),3) AS PCT, ROUND((CAST(Goaler" . $TypeText . "StatHistory.PenalityShotsShots - Goaler" . $TypeText . "StatHistory.PenalityShotsGoals AS REAL) / (Goaler" . $TypeText . "StatHistory.PenalityShotsShots)),3) AS PenalityShotsPCT FROM GoalerInfoHistory INNER JOIN Goaler" . $TypeText . "StatHistory ON GoalerInfoHistory.Number = Goaler" . $TypeText . "StatHistory.Number AND GoalerInfoHistory.Year = Goaler" . $TypeText . "StatHistory.Year AND GoalerInfoHistory.Playoff = Goaler" . $TypeText . "StatHistory.Playoff WHERE Goaler" . $TypeText . "StatHistory.GP > 0 AND GoalerInfoHistory.Playoff = '" . $PlayoffString. "') AS MainHistory) AS MainTable";
			}else{
				/* Requesting Playoff While in Season or Requesting Season while in Playoff - Do not fetch data from live database */
				$Query = $Query . " FROM (SELECT Goaler" . $TypeText . "StatHistory.Year, GoalerInfoHistory.ProTeamName AS TeamName,  GoalerInfoHistory.Team, GoalerInfoHistory.Rookie, '0' As TeamThemeID, Goaler" . $TypeText . "StatHistory.Number, Goaler" . $TypeText . "StatHistory.UniqueID, Goaler" . $TypeText . "StatHistory.Name, Goaler" . $TypeText . "StatHistory.GP, Goaler" . $TypeText . "StatHistory.SecondPlay, Goaler" . $TypeText . "StatHistory.W, Goaler" . $TypeText . "StatHistory.L, Goaler" . $TypeText . "StatHistory.OTL, Goaler" . $TypeText . "StatHistory.Shootout, Goaler" . $TypeText . "StatHistory.GA, Goaler" . $TypeText . "StatHistory.SA, Goaler" . $TypeText . "StatHistory.SARebound, Goaler" . $TypeText . "StatHistory.Pim, Goaler" . $TypeText . "StatHistory.A, Goaler" . $TypeText . "StatHistory.PenalityShotsShots, Goaler" . $TypeText . "StatHistory.PenalityShotsGoals, Goaler" . $TypeText . "StatHistory.StartGoaler, Goaler" . $TypeText . "StatHistory.BackupGoaler, Goaler" . $TypeText . "StatHistory.EmptyNetGoal, Goaler" . $TypeText . "StatHistory.Star1, Goaler" . $TypeText . "StatHistory.Star2, Goaler" . $TypeText . "StatHistory.Star3, ROUND((CAST(Goaler" . $TypeText . "StatHistory.GA AS REAL) / (Goaler" . $TypeText . "StatHistory.SecondPlay / 60))*60,3) AS GAA, ROUND((CAST(Goaler" . $TypeText . "StatHistory.SA - Goaler" . $TypeText . "StatHistory.GA AS REAL) / (Goaler" . $TypeText . "StatHistory.SA)),3) AS PCT, ROUND((CAST(Goaler" . $TypeText . "StatHistory.PenalityShotsShots - Goaler" . $TypeText . "StatHistory.PenalityShotsGoals AS REAL) / (Goaler" . $TypeText . "StatHistory.PenalityShotsShots)),3) AS PenalityShotsPCT FROM GoalerInfoHistory INNER JOIN Goaler" . $TypeText . "StatHistory ON GoalerInfoHistory.Number = Goaler" . $TypeText . "StatHistory.Number AND GoalerInfoHistory.Year = Goaler" . $TypeText . "StatHistory.Year AND GoalerInfoHistory.Playoff = Goaler" . $TypeText . "StatHistory.Playoff WHERE Goaler" . $TypeText . "StatHistory.GP > 0 AND GoalerInfoHistory.Playoff = '" . $PlayoffString. "') AS MainTable";
			}
			$Query = $Query . " WHERE MainTable.Team = " . $Team . " GROUP BY UniqueID ORDER BY Sum(MainTable.W) DESC LIMIT 5";
			$TeamCareerGoaliesSeasonTop5 = $CareerStatdb->query($Query);			

			/* Goalies Career Stat Leader Playoff */
			$PlayoffString = (string)"True";
			$Query = "SELECT MainTable.Number, MainTable.UniqueID, '0' As TeamThemeID, MainTable.Name, MainTable.Team, MainTable.TeamName, Sum(MainTable.GP) AS GP, Sum(MainTable.SecondPlay) AS SecondPlay, Sum(MainTable.W) AS W, Sum(MainTable.L) AS L, Sum(MainTable.OTL) AS OTL, Sum(MainTable.Shootout) AS Shootout, Sum(MainTable.GA) AS GA, Sum(MainTable.SA) AS SA, Sum(MainTable.SARebound) AS SARebound, Sum(MainTable.Pim) AS Pim, Sum(MainTable.A) AS A, Sum(MainTable.PenalityShotsShots) AS PenalityShotsShots, Sum(MainTable.PenalityShotsGoals) AS PenalityShotsGoals, Sum(MainTable.StartGoaler) AS StartGoaler, Sum(MainTable.BackupGoaler) AS BackupGoaler, Sum(MainTable.EmptyNetGoal) AS EmptyNetGoal, Sum(MainTable.Star1) AS Star1, Sum(MainTable.Star2) AS Star2, Sum(MainTable.Star3) AS Star3, ROUND((CAST(Sum(MainTable.GA) AS REAL) / (Sum(MainTable.SecondPlay) / 60))*60, 3) AS GAA, ROUND((CAST(Sum(MainTable.SA) - Sum(MainTable.GA) AS REAL) / (Sum(MainTable.SA))), 3) AS PCT, ROUND((CAST(Sum(MainTable.PenalityShotsShots) - Sum(MainTable.PenalityShotsGoals) AS REAL) / (Sum(MainTable.PenalityShotsShots))), 3) AS PenalityShotsPCT";
			if($LeagueGeneral['PlayOffStarted'] == $PlayoffString){
				/* Regular Query */	
				$Query = $Query . " FROM ( Select MainLive.* FROM (SELECT '". $LeagueGeneral['LeagueYear'] . "' as Year, GoalerInfo.TeamName, GoalerInfo.Team, GoalerInfo.Rookie, '0' As TeamThemeID, Goaler" . $TypeText . "Stat.Number, Goaler" . $TypeText . "Stat.UniqueID, Goaler" . $TypeText . "Stat.Name, Goaler" . $TypeText . "Stat.GP, Goaler" . $TypeText . "Stat.SecondPlay, Goaler" . $TypeText . "Stat.W, Goaler" . $TypeText . "Stat.L, Goaler" . $TypeText . "Stat.OTL, Goaler" . $TypeText . "Stat.Shootout, Goaler" . $TypeText . "Stat.GA, Goaler" . $TypeText . "Stat.SA, Goaler" . $TypeText . "Stat.SARebound, Goaler" . $TypeText . "Stat.Pim, Goaler" . $TypeText . "Stat.A, Goaler" . $TypeText . "Stat.PenalityShotsShots, Goaler" . $TypeText . "Stat.PenalityShotsGoals, Goaler" . $TypeText . "Stat.StartGoaler, Goaler" . $TypeText . "Stat.BackupGoaler, Goaler" . $TypeText . "Stat.EmptyNetGoal, Goaler" . $TypeText . "Stat.Star1, Goaler" . $TypeText . "Stat.Star2, Goaler" . $TypeText . "Stat.Star3, ROUND((CAST(Goaler" . $TypeText . "Stat.GA AS REAL) / (Goaler" . $TypeText . "Stat.SecondPlay / 60))*60, 3) AS GAA,  ROUND((CAST(Goaler" . $TypeText . "Stat.SA - Goaler" . $TypeText . "Stat.GA AS REAL) / (Goaler" . $TypeText . "Stat.SA)), 3) AS PCT,  ROUND((CAST(Goaler" . $TypeText . "Stat.PenalityShotsShots - Goaler" . $TypeText . "Stat.PenalityShotsGoals AS REAL) / (Goaler" . $TypeText . "Stat.PenalityShotsShots)), 3) AS PenalityShotsPCT FROM GoalerInfo INNER JOIN Goaler" . $TypeText . "Stat ON GoalerInfo.Number = Goaler" . $TypeText . "Stat.Number WHERE Goaler" . $TypeText . "Stat.GP > 0 ) AS MainLive UNION ALL Select MainHistory.* FROM (SELECT Goaler" . $TypeText . "StatHistory.Year, GoalerInfoHistory.ProTeamName AS TeamName,  GoalerInfoHistory.Team, GoalerInfoHistory.Rookie, '0' As TeamThemeID, Goaler" . $TypeText . "StatHistory.Number, Goaler" . $TypeText . "StatHistory.UniqueID, Goaler" . $TypeText . "StatHistory.Name, Goaler" . $TypeText . "StatHistory.GP, Goaler" . $TypeText . "StatHistory.SecondPlay, Goaler" . $TypeText . "StatHistory.W, Goaler" . $TypeText . "StatHistory.L, Goaler" . $TypeText . "StatHistory.OTL, Goaler" . $TypeText . "StatHistory.Shootout, Goaler" . $TypeText . "StatHistory.GA, Goaler" . $TypeText . "StatHistory.SA, Goaler" . $TypeText . "StatHistory.SARebound, Goaler" . $TypeText . "StatHistory.Pim, Goaler" . $TypeText . "StatHistory.A, Goaler" . $TypeText . "StatHistory.PenalityShotsShots, Goaler" . $TypeText . "StatHistory.PenalityShotsGoals, Goaler" . $TypeText . "StatHistory.StartGoaler, Goaler" . $TypeText . "StatHistory.BackupGoaler, Goaler" . $TypeText . "StatHistory.EmptyNetGoal, Goaler" . $TypeText . "StatHistory.Star1, Goaler" . $TypeText . "StatHistory.Star2, Goaler" . $TypeText . "StatHistory.Star3, ROUND((CAST(Goaler" . $TypeText . "StatHistory.GA AS REAL) / (Goaler" . $TypeText . "StatHistory.SecondPlay / 60))*60,3) AS GAA, ROUND((CAST(Goaler" . $TypeText . "StatHistory.SA - Goaler" . $TypeText . "StatHistory.GA AS REAL) / (Goaler" . $TypeText . "StatHistory.SA)),3) AS PCT, ROUND((CAST(Goaler" . $TypeText . "StatHistory.PenalityShotsShots - Goaler" . $TypeText . "StatHistory.PenalityShotsGoals AS REAL) / (Goaler" . $TypeText . "StatHistory.PenalityShotsShots)),3) AS PenalityShotsPCT FROM GoalerInfoHistory INNER JOIN Goaler" . $TypeText . "StatHistory ON GoalerInfoHistory.Number = Goaler" . $TypeText . "StatHistory.Number AND GoalerInfoHistory.Year = Goaler" . $TypeText . "StatHistory.Year AND GoalerInfoHistory.Playoff = Goaler" . $TypeText . "StatHistory.Playoff WHERE Goaler" . $TypeText . "StatHistory.GP > 0 AND GoalerInfoHistory.Playoff = '" . $PlayoffString. "') AS MainHistory) AS MainTable";
			}else{
				/* Requesting Playoff While in Season or Requesting Season while in Playoff - Do not fetch data from live database */
				$Query = $Query . " FROM (SELECT Goaler" . $TypeText . "StatHistory.Year, GoalerInfoHistory.ProTeamName AS TeamName,  GoalerInfoHistory.Team, GoalerInfoHistory.Rookie, '0' As TeamThemeID, Goaler" . $TypeText . "StatHistory.Number, Goaler" . $TypeText . "StatHistory.UniqueID, Goaler" . $TypeText . "StatHistory.Name, Goaler" . $TypeText . "StatHistory.GP, Goaler" . $TypeText . "StatHistory.SecondPlay, Goaler" . $TypeText . "StatHistory.W, Goaler" . $TypeText . "StatHistory.L, Goaler" . $TypeText . "StatHistory.OTL, Goaler" . $TypeText . "StatHistory.Shootout, Goaler" . $TypeText . "StatHistory.GA, Goaler" . $TypeText . "StatHistory.SA, Goaler" . $TypeText . "StatHistory.SARebound, Goaler" . $TypeText . "StatHistory.Pim, Goaler" . $TypeText . "StatHistory.A, Goaler" . $TypeText . "StatHistory.PenalityShotsShots, Goaler" . $TypeText . "StatHistory.PenalityShotsGoals, Goaler" . $TypeText . "StatHistory.StartGoaler, Goaler" . $TypeText . "StatHistory.BackupGoaler, Goaler" . $TypeText . "StatHistory.EmptyNetGoal, Goaler" . $TypeText . "StatHistory.Star1, Goaler" . $TypeText . "StatHistory.Star2, Goaler" . $TypeText . "StatHistory.Star3, ROUND((CAST(Goaler" . $TypeText . "StatHistory.GA AS REAL) / (Goaler" . $TypeText . "StatHistory.SecondPlay / 60))*60,3) AS GAA, ROUND((CAST(Goaler" . $TypeText . "StatHistory.SA - Goaler" . $TypeText . "StatHistory.GA AS REAL) / (Goaler" . $TypeText . "StatHistory.SA)),3) AS PCT, ROUND((CAST(Goaler" . $TypeText . "StatHistory.PenalityShotsShots - Goaler" . $TypeText . "StatHistory.PenalityShotsGoals AS REAL) / (Goaler" . $TypeText . "StatHistory.PenalityShotsShots)),3) AS PenalityShotsPCT FROM GoalerInfoHistory INNER JOIN Goaler" . $TypeText . "StatHistory ON GoalerInfoHistory.Number = Goaler" . $TypeText . "StatHistory.Number AND GoalerInfoHistory.Year = Goaler" . $TypeText . "StatHistory.Year AND GoalerInfoHistory.Playoff = Goaler" . $TypeText . "StatHistory.Playoff WHERE Goaler" . $TypeText . "StatHistory.GP > 0 AND GoalerInfoHistory.Playoff = '" . $PlayoffString. "') AS MainTable";
			}
			$Query = $Query . " WHERE MainTable.Team = " . $Team . " GROUP BY UniqueID ORDER BY Sum(MainTable.W) DESC LIMIT 5";
			$TeamCareerGoaliesPlayoffTop5 = $CareerStatdb->query($Query);
						
		}else{
			$TeamCareerSeason = Null;
			$TeamCareerPlayoff = Null;
			$TeamCareerSumSeasonOnly = Null;
			$TeamCareerSumPlayoffOnly = Null;	
			$TeamCareerPlayersSeasonTop5 = Null;	
			$TeamCareerPlayersPlayoffTop5 = Null;	
			$TeamCareerGoaliesSeasonTop5 = Null;	
			$TeamCareerGoaliesPlayoffTop5 = Null;	
		}
	}else{
		$TeamInfo = Null;
		$TeamProInfo = Null;			
		$TeamFinance = Null;	
		$TeamStat = Null;
		$PlayerRoster = Null;
		$PlayerInfo = Null;
		$PlayerRosterAverage = Null;	
		$GoalieRosterAverage = Null;	
		$PlayerInfoAverage = Null;
		$PlayerStat = Null;
		$GoalieStat = Null;
		$GoalieRoster = Null;
		$Schedule = Null;
		$ScheduleNext = Null;
		$CoachInfo = Null;	
		$RivalryInfo = Null;		
		$LeagueGeneral = Null;
		$LeagueFinance = Null;		
		$LeagueWebClient = Null;	
		$LeagueOutputOption = Null;	
		$TeamLines = Null;
		$TeamLog = Null;		
		$Prospects = Null;
		$TeamDraftPick = Null;
		$TeamInjurySuspension = Null;
		$GoalieDepthChart = Null;
		$PlayerDepthChart = Null;
		$TeamCareerSeason = Null;
		$TeamCareerPlayoff = Null;
		$TeamCareerSumSeasonOnly = Null;
		$TeamCareerSumPlayoffOnly = Null;	
		$PlayerStatTeam  = Null;
		$GoalieStatTeam = Null;
		$TeamTransaction = Null;
		$TeamLeader = Null;
		$LeagueSimulation = Null;
		$ScheduleLastGame = Null;
		$ScheduleNextGame = Null;
		$TeamLeaderG = Null;
		$TeamLeaderA = Null;
		$TeamLeaderP = Null;
		$TeamLeaderPlusMinus = Null;
		$TeamLeaderGAA = Null;
		$TeamLeaderSavePCT = Null;
		$CupWinner = Null;
		$TeamName = $TeamLang['Teamnotfound'];
		echo "<style>.STHSPHPTeamStat_Main {display:none;}</style>";
	}
}
echo "<title>" . $LeagueName . " - " . $TeamName . "</title>";
?>
<style>
<?php
if ($TeamCareerStatFound == true){
	echo "#tablesorter_colSelect11:checked + label {background: #5797d7;  border-color: #555;}\n";
	echo "#tablesorter_colSelect11:checked ~ #tablesorter_ColumnSelector11 {display: block;}\n";
	echo "#tablesorter_colSelect11SeasonP:checked + label {background: #5797d7;  border-color: #555;}\n";
	echo "#tablesorter_colSelect11SeasonP:checked ~ #tablesorter_ColumnSelector11SeasonP {display: block;}\n";
	echo "#tablesorter_colSelect11SeasonG:checked + label {background: #5797d7;  border-color: #555;}\n";
	echo "#tablesorter_colSelect11SeasonG:checked ~ #tablesorter_ColumnSelector11SeasonG {display: block;}\n";
	echo "#tablesorter_colSelect11PlayoffP:checked + label {background: #5797d7;  border-color: #555;}\n";
	echo "#tablesorter_colSelect11PlayoffP:checked ~ #tablesorter_ColumnSelector11PlayoffP {display: block;}\n";
	echo "#tablesorter_colSelect11PlayoffG:checked + label {background: #5797d7;  border-color: #555;}\n";
	echo "#tablesorter_colSelect11PlayoffG:checked ~ #tablesorter_ColumnSelector11PlayoffG {display: block;}\n";	
}
If ($LeagueGeneral['OffSeason'] == "True"){
	echo ".STHSPHPPlayerStat_HomeMainTD{display:none;}";
}
?>
#tablesorter_colSelect1P:checked + label {background: #5797d7;  border-color: #555;}
#tablesorter_colSelect1P:checked ~ #tablesorter_ColumnSelector1P {display: block;z-index:10;}
#tablesorter_colSelect1G:checked + label {background: #5797d7;  border-color: #555;}
#tablesorter_colSelect1G:checked ~ #tablesorter_ColumnSelector1G {display: block;}
#tablesorter_colSelect2P:checked + label {background: #5797d7;  border-color: #555;}
#tablesorter_colSelect2P:checked ~ #tablesorter_ColumnSelector2P {display: block;z-index:10;}
#tablesorter_colSelect2G:checked + label {background: #5797d7;  border-color: #555;}
#tablesorter_colSelect2G:checked ~ #tablesorter_ColumnSelector2G {display: block;}
#tablesorter_colSelect3:checked + label {background: #5797d7;  border-color: #555;}
#tablesorter_colSelect3:checked ~ #tablesorter_ColumnSelector3 {display: block;}
#tablesorter_colSelect5:checked + label {background: #5797d7;  border-color: #555;}
#tablesorter_colSelect5:checked ~ #tablesorter_ColumnSelector5 {display: block;}
#tablesorter_colSelect6:checked + label {background: #5797d7;  border-color: #555;}
#tablesorter_colSelect6:checked ~ #tablesorter_ColumnSelector6 {display: block;}
@media screen and (max-width: 992px) {
.STHSWarning {display:block;}
}@media screen and (max-width: 890px) {
#STHSPHPTeamStat_SubHeader {display:none;}
}
.tabmain-links a{font-size:18px;}
</style>
</head><body>
<?php include "Menu.php";?>
<br />

<div id="STHSPHPTeamStat_SubHeader">
<?php 
If ($TeamInfo <> Null){
	echo "<table class=\"STHSPHPTeamHeader_Table\"><tr><td rowspan=\"2\" class=\"STHSPHPTeamHeader_Logo\">";
	If ($TeamInfo['TeamThemeID'] > 0){echo "<img src=\"./images/" . $TeamInfo['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPTeamStatImage\" />";}
	echo "</td><td class=\"STHSPHPTeamHeader_TeamName STHSPHPTeamHeader_TeamNameColor_";
	If ($TeamInfo['TeamThemeID'] > 0){echo $TeamInfo['TeamThemeID'];}
	echo "\">" . $TeamName . "</td></tr><tr><td class=\"STHSPHPTeamHeader_Stat\">";
	echo "GP: " . $TeamStat['GP'] . " | W: " . ($TeamStat['W'] + $TeamStat['OTW'] + $TeamStat['SOW']) . " | L: " .  $TeamStat['L'];
	if ($LeagueGeneral['PlayOffStarted'] == "False"){
		if($LeagueGeneral['PointSystemSO'] == "True"){
			echo  " | OTL: " . ($TeamStat['OTL'] + $TeamStat['SOL']) . " | P: " . $TeamStat['Points'];
		}else{
			echo  " | T: " . $TeamStat['T'] . " | P: " . $TeamStat['Points'];
		}
	}
	echo "<br />" . "GF: " . $TeamStat['GF'] . " | GA: " . $TeamStat['GA'] . " | PP%: ";
	if ($TeamStat['PPAttemp'] > 0){echo number_Format($TeamStat['PPGoal'] / $TeamStat['PPAttemp'] * 100,2) . "%";} else { echo "0.00%";}
	echo " | PK%: ";
	if ($TeamStat['PKAttemp'] > 0){echo number_Format(($TeamStat['PKAttemp'] - $TeamStat['PKGoalGA']) / $TeamStat['PKAttemp'] * 100,2) . "%";} else {echo "0.00%";} 
	echo "<br />" . $TeamLang['GM'] . $TeamProInfo['GMName'] . " | " . $TeamLang['Morale'] . $TeamInfo['Morale'] . " | " . $TeamLang['TeamOverall'] . $TeamInfo['TeamOverall'];
	If ($Team > 0 AND $Team < 101){
		$Query = "SELECT count(*) AS count FROM ScheduleFarm WHERE (VisitorTeam = " . $Team . " OR HomeTeam = " . $Team . ") AND Play = 'False' ORDER BY GameNumber LIMIT 1";
		$Result = $db->querySingle($Query,true);
	}else{
		$Result = Null;
	}
	If ($Result['count'] > 0){
		If ($ScheduleNext['HomeTeam'] == $Team){
			echo "<br />" .$TodayGamesLang['NextGames'] . " #" . $ScheduleNext['GameNumber'] ."  vs " . $ScheduleNext['VisitorTeamName'];
		}elseif($ScheduleNext['VisitorTeam'] == $Team){
			echo "<br />" . $TodayGamesLang['NextGames']  . " #" . $ScheduleNext['GameNumber'] ."  vs " . $ScheduleNext['HomeTeamName'];
		}
	}
	echo "</td></tr></table>";
}
?>
</div>
<div class="STHSWarning"><?php echo $WarningResolution;?><br /></div>
<div class="STHSPHPTeamStat_Main">
<br />
<div class="tabsmain standard"><ul class="tabmain-links">
<li><a class="tabmenuhome" <?php echo "href=\"ProTeam.php?Team=" . $Team . "\">"; if ($TeamProInfo != Null){echo $TeamProInfo['Name'];}?></a></li>
<li<?php if($SubMenu ==0){echo " class=\"activemain\"";}?>><a href="#tabmain0"><?php echo $TeamLang['Home'];?></a></li>
<li<?php if($SubMenu ==1){echo " class=\"activemain\"";}?>><a href="#tabmain1"><?php echo $TeamLang['Roster'];?></a></li>
<li<?php if($SubMenu ==2){echo " class=\"activemain\"";}?>><a href="#tabmain2"><?php echo $TeamLang['Scoring'];?></a></li>
<li<?php if($SubMenu ==3){echo " class=\"activemain\"";}?>><a href="#tabmain3"><?php echo $TeamLang['PlayersInfo'];?></a></li>
<li<?php if($SubMenu ==4){echo " class=\"activemain\"";}?>><a href="#tabmain4"><?php echo $TeamLang['Lines'];?></a></li>
<li<?php if($SubMenu ==5){echo " class=\"activemain\"";}?>><a href="#tabmain5"><?php echo $TeamLang['TeamStats'];?></a></li>
<li<?php if($SubMenu ==6){echo " class=\"activemain\"";}?>><a href="#tabmain6"><?php echo $TeamLang['Schedule'];?></a></li>
<li<?php if($SubMenu ==7){echo " class=\"activemain\"";}?>><a href="#tabmain7"><?php echo $TeamLang['Finance'];?></a></li>
<?php
if ($TeamCareerStatFound == true){echo "<li";if($SubMenu ==8){echo " class=\"activemain\"";};echo "><a href=\"#tabmain8\">" . $TeamLang['CareerTeamStat'] . "</a></li>\n";}
if ($LeagueOutputOption != Null){if ($LeagueOutputOption['ShowWebClientInDymanicWebsite'] == "True"){echo "<li><a class=\"tabmenuhome\" href=\"WebClientLines.php?League=Farm&TeamID=" . $Team . "\">" . $TeamLang['WebLinesEditor'] . "</a></li>\n";}}
?>
</ul>
<div style="border-radius:1px;box-shadow:-1px 1px 1px rgba(0,0,0,0.15);border-style: solid;border-color: #dedede">

<div class="tabmain<?php if($SubMenu ==0){echo " active";}?>" id="tabmain0">

<table class="STHSPHPTeam_HomeTable"><tr><td class="STHSPHPPlayerStat_HomeMainTD">
<?php
$LoopCount = (integer)0;
if (empty($ScheduleLastGame) == false){while ($row = $ScheduleLastGame ->fetchArray()) {
	$LoopCount +=1;
	echo "<table class=\"STHSPHPTeam_HomePrimaryTable\">";
	If ($LoopCount == 1){echo "<tr><td colspan=\"7\" class=\"STHSPHPTeamStat_TableTitle\">" . $TeamLang['GameCenter'] .   "</td></tr>";}
	echo "<tr onclick=\"Game" . $LoopCount  . "()\"><td class=\"STHSPHPTeam_HomePrimaryTableTeamImag\">\n";
	If ($row['VisitorTeamThemeID'] > 0){echo "<img src=\"./images/" . $row['VisitorTeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPTeam_HomePrimaryTableTeamImageSpec\" />\n";}
	echo "</td><td class=\"STHSPHPTeam_HomePrimaryTableTeamInfo\" style=\"text-align:right;\"><span class=\"STHSPHPTeam_HomePrimaryTableTeamName\">" . $row['VisitorTeamName'] . "</span><br />" . ($row['VW'] + $row['VOTW'] + $row['VSOW']) . "-" .$row['VL'] . "-" . ($row['VOTL'] + $row['VSOL']). ", ".$row['VPoints']. "pts</td>";
	echo "<td class=\"STHSPHPTeam_HomePrimaryTableTeamScore\">" . $row['VisitorScore'] . "</td>";
	echo "<td class=\"STHSPHPTeam_HomePrimaryTableTeamMiddlePlay\"><div class=\"STHSPHPTeam_HomePrimaryTableTeamInfoBeforeTriangle\">FINAL</div><div class=\"STHSPHPTeam_HomePrimaryTableTeamInfoTriangle\"></div></td>\n";
	echo "<td class=\"STHSPHPTeam_HomePrimaryTableTeamScore\">" . $row['HomeScore'] . "</td>\n";
	echo "<td class=\"STHSPHPTeam_HomePrimaryTableTeamInfo\"><span class=\"STHSPHPTeam_HomePrimaryTableTeamName\">" . $row['HomeTeamName'] . "</span><br />" . ($row['HW'] + $row['HOTW'] + $row['HSOW']) . "-" .$row['HL'] . "-" . ($row['HOTL'] + $row['HSOL']). ", ".$row['HPoints']. "pts</td><td class=\"STHSPHPTeam_HomePrimaryTableTeamImag\">\n";
	If ($row['HomeTeamThemeID'] > 0){echo "<img src=\"./images/" . $row['HomeTeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPTeam_HomePrimaryTableTeamImageSpec\" />\n";}
	echo "</td></tr></table>\n"; 
	
	echo "<table class=\"STHSPHPTeam_HomeTeamStatTable\" id=\"Game" . $LoopCount . "\"><tr><th colspan=\"3\" >Team Stats</th></tr>\n";
	echo "<tr><td class=\"STHSPHPTeam_HomeTeamStatR\">" . $row['VStreak'] ."</td><td class=\"STHSPHPTeam_HomeTeamStatC\">" . "Streak" ."</td><td class=\"STHSPHPTeam_HomeTeamStatL\">" . $row['HStreak'] ."</td></tr>\n";
	echo "<tr><td class=\"STHSPHPTeam_HomeTeamStatR\">" . ($row['VHW'] + $row['VHOTW'] + $row['VHSOW']) . "-" . $row['VHL'] . "-" . ($row['VHOTL'] + $row['VHSOL'])  ."</td><td class=\"STHSPHPTeam_HomeTeamStatC\">" . "Home Record" ."</td><td class=\"STHSPHPTeam_HomeTeamStatL\">" . ($row['HHW'] + $row['HHOTW'] + $row['HHSOW']) . "-" . $row['HHL'] . "-" . ($row['HHOTL'] + $row['HHSOL']) ."</td></tr>\n";
	echo "<tr><td class=\"STHSPHPTeam_HomeTeamStatR\">" . (($row['VW']-$row['VHW']) + ($row['VOTW']-$row['VHOTW']) + ($row['VSOW']-$row['VHSOW'])) . "-" . ($row['VL']-$row['VHL']) . "-" . (($row['VOTL']-$row['VHOTL']) + ($row['VSOL']-$row['VHSOL']))  ."</td><td class=\"STHSPHPTeam_HomeTeamStatC\">" . "Away Record" ."</td><td class=\"STHSPHPTeam_HomeTeamStatL\">" . (($row['HW']-$row['HHW']) + ($row['HOTW']-$row['HHOTW']) + ($row['HSOW']-$row['HHSOW'])) . "-" . ($row['HL']-$row['HHL']) . "-" . (($row['HOTL']-$row['HHOTL']) + ($row['HSOL']-$row['HHSOL'])) ."</td></tr>\n";
	echo "<tr><td style=text-align:right;width:33%;font-size:14px;font-weight:bold>" . ($row['VLast10W'] + $row['VLast10OTW'] + $row['VLast10SOW']) . "-" . $row['VLast10L'] . "-" . ($row['VLast10OTL'] + $row['VLast10SOL'])  ."</td><td class=\"STHSPHPTeam_HomeTeamStatC\">" . "Last 10 Games" ."</td><td class=\"STHSPHPTeam_HomeTeamStatL\">" . ($row['HLast10W'] + $row['HLast10OTW'] + $row['HLast10SOW']) . "-" . $row['HLast10L'] . "-" . ($row['HLast10OTL'] + $row['HLast10SOL']) ."</td></tr>\n";
	echo "<tr><td class=\"STHSPHPTeam_HomeTeamStatR\">";
	if($row['VGP'] > 0){echo number_Format($row['VGF'] / $row['VGP'],2);}else{echo "0";} echo "</td><td class=\"STHSPHPTeam_HomeTeamStatC\">" . $TeamLang['GoalsPerGame'] ."</td><td class=\"STHSPHPTeam_HomeTeamStatL\">";
	if($row['HGP'] > 0){echo number_Format($row['HGF'] / $row['HGP'],2);}else{echo "0";} echo "</td></tr>\n";
	echo "<tr><td class=\"STHSPHPTeam_HomeTeamStatR\">";
	if($row['VGP'] > 0){echo number_Format($row['VGA'] / $row['VGP'],2);}else{echo "0";} echo "</td><td class=\"STHSPHPTeam_HomeTeamStatC\">" . $TeamLang['GoalsAgainstPerGame'] ."</td><td class=\"STHSPHPTeam_HomeTeamStatL\">"; 
	if($row['HGP'] > 0){echo number_Format($row['HGA'] / $row['HGP'],2);}else{echo "0";} echo "</td></tr>\n";
	echo "<tr><td class=\"STHSPHPTeam_HomeTeamStatR\">";
	if($row['VPPAttemp'] > 0){echo number_Format($row['VPPGoal'] / $row['VPPAttemp'] * 100,2)."%";}else{echo "0%";} echo "</td><td class=\"STHSPHPTeam_HomeTeamStatC\">" . $TeamLang['PowerPlayPercentage'] ."</td><td class=\"STHSPHPTeam_HomeTeamStatL\">"; 
	if($row['HPPAttemp'] > 0){echo number_Format($row['HPPGoal'] / $row['HPPAttemp'] * 100,2)."%";}else{echo "0%";} echo "</td></tr>\n";
	echo "<tr><td class=\"STHSPHPTeam_HomeTeamStatR\">"; 
	if($row['VPKAttemp'] > 0){echo number_Format(($row['VPKAttemp']-$row['VPKGA']) / $row['VPKAttemp'] * 100,2)."%";}else{echo "0%";} echo "</td><td class=\"STHSPHPTeam_HomeTeamStatC\">" . $TeamLang['PenaltyKillPercentage'] ."</td><td class=\"STHSPHPTeam_HomeTeamStatL\">";
	if($row['HPKAttemp'] > 0){echo number_Format(($row['HPKAttemp']-$row['HPKGA']) / $row['HPKAttemp'] * 100,2)."%";}else{echo "0%";} echo "</td></tr>\n";
	echo "</table>\n";
}}

if (empty($ScheduleNextGame) == false){while ($row = $ScheduleNextGame ->fetchArray()) {
	$LoopCount +=1;
	echo "<table class=\"STHSPHPTeam_HomePrimaryTable\"><tr onclick=\"Game" . $LoopCount  . "()\"><td class=\"STHSPHPTeam_HomePrimaryTableTeamImag\">\n";
	If ($row['VisitorTeamThemeID'] > 0){echo "<img src=\"./images/" . $row['VisitorTeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPTeam_HomePrimaryTableTeamImageSpec\" />\n";}
	echo "</td><td class=\"STHSPHPTeam_HomePrimaryTableTeamInfo\" style=\"text-align:right;\"><span class=\"STHSPHPTeam_HomePrimaryTableTeamName\">" . $row['VisitorTeamName'] . "</span><br />" . ($row['VW'] + $row['VOTW'] + $row['VSOW']) . "-" .$row['VL'] . "-" . ($row['VOTL'] + $row['VSOL']). ", ".$row['VPoints']. "pts</td>";
	
	echo "<td class=\"STHSPHPTeam_HomePrimaryTableTeamMiddleNotPlay\"><div class=\"STHSPHPTeam_HomePrimaryTableTeamInfoBeforeTriangle\">";
	if ($LeagueOutputOption['ScheduleUseDateInsteadofDay'] == TRUE){
		$ScheduleDate = date_create($LeagueOutputOption['ScheduleRealDate']);
		date_add($ScheduleDate, DateInterval::createFromDateString(Floor((($row['Day'] -1) / $LeagueGeneral['DefaultSimulationPerDay'])) . " days"));
		echo date_Format($ScheduleDate,"Y-m-d") . "</div>\n";
	}else{
		echo $ScheduleLang['Day'] . " " . $row['Day'] . "</div>\n";
	}	
	echo "<div class=\"STHSPHPTeam_HomePrimaryTableTeamInfoTriangle\"></div></td>\n";
	echo "<td class=\"STHSPHPTeam_HomePrimaryTableTeamInfo\"><span class=\"STHSPHPTeam_HomePrimaryTableTeamName\">" . $row['HomeTeamName'] . "</span><br />" . ($row['HW'] + $row['HOTW'] + $row['HSOW']) . "-" .$row['HL'] . "-" . ($row['HOTL'] + $row['HSOL']). ", ".$row['HPoints']. "pts</td><td class=\"STHSPHPTeam_HomePrimaryTableTeamImag\">\n";
	If ($row['HomeTeamThemeID'] > 0){echo "<img src=\"./images/" . $row['HomeTeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPTeam_HomePrimaryTableTeamImageSpec\" />\n";}
	echo "</td></tr></table>\n"; 
	
	echo "<table class=\"STHSPHPTeam_HomeTeamStatTable\" id=\"Game" . $LoopCount . "\"><tr><th colspan=\"3\">" . $TeamLang['TeamStats'] . "</th></tr>\n";
	echo "<tr><td class=\"STHSPHPTeam_HomeTeamStatR\">" . $row['VStreak'] ."</td><td class=\"STHSPHPTeam_HomeTeamStatC\">" . $GeneralStatLang['Streak'] ."</td><td class=\"STHSPHPTeam_HomeTeamStatL\">" . $row['HStreak'] ."</td></tr>\n";
	echo "<tr><td class=\"STHSPHPTeam_HomeTeamStatR\">" . ($row['VHW'] + $row['VHOTW'] + $row['VHSOW']) . "-" . $row['VHL'] . "-" . ($row['VHOTL'] + $row['VHSOL'])  ."</td><td class=\"STHSPHPTeam_HomeTeamStatC\">" . $TeamLang['HomeRecord']	 ."</td><td class=\"STHSPHPTeam_HomeTeamStatL\">" . ($row['HHW'] + $row['HHOTW'] + $row['HHSOW']) . "-" . $row['HHL'] . "-" . ($row['HHOTL'] + $row['HHSOL']) ."</td></tr>\n";
	echo "<tr><td class=\"STHSPHPTeam_HomeTeamStatR\">" . (($row['VW']-$row['VHW']) + ($row['VOTW']-$row['VHOTW']) + ($row['VSOW']-$row['VHSOW'])) . "-" . ($row['VL']-$row['VHL']) . "-" . (($row['VOTL']-$row['VHOTL']) + ($row['VSOL']-$row['VHSOL']))  ."</td><td class=\"STHSPHPTeam_HomeTeamStatC\">" . $TeamLang['AwayRecord']	 ."</td><td class=\"STHSPHPTeam_HomeTeamStatL\">" . (($row['HW']-$row['HHW']) + ($row['HOTW']-$row['HHOTW']) + ($row['HSOW']-$row['HHSOW'])) . "-" . ($row['HL']-$row['HHL']) . "-" . (($row['HOTL']-$row['HHOTL']) + ($row['HSOL']-$row['HHSOL'])) ."</td></tr>\n";
	echo "<tr><td style=text-align:right;width:33%;font-size:14px;font-weight:bold>" . ($row['VLast10W'] + $row['VLast10OTW'] + $row['VLast10SOW']) . "-" . $row['VLast10L'] . "-" . ($row['VLast10OTL'] + $row['VLast10SOL'])  ."</td><td class=\"STHSPHPTeam_HomeTeamStatC\">" . $TodayGamesLang['Last10Games'] ."</td><td class=\"STHSPHPTeam_HomeTeamStatL\">" . ($row['HLast10W'] + $row['HLast10OTW'] + $row['HLast10SOW']) . "-" . $row['HLast10L'] . "-" . ($row['HLast10OTL'] + $row['HLast10SOL']) ."</td></tr>\n";
	echo "<tr><td class=\"STHSPHPTeam_HomeTeamStatR\">" .number_Format($row['VGF'] / $row['VGP'],2) ."</td><td class=\"STHSPHPTeam_HomeTeamStatC\">" . $TeamLang['GoalsPerGame'] ."</td><td class=\"STHSPHPTeam_HomeTeamStatL\">" . number_Format($row['HGF'] / $row['HGP'],2) ."</td></tr>\n";
	echo "<tr><td class=\"STHSPHPTeam_HomeTeamStatR\">" . number_Format($row['VGA'] / $row['VGP'],2) ."</td><td class=\"STHSPHPTeam_HomeTeamStatC\">" . $TeamLang['GoalsAgainstPerGame'] ."</td><td class=\"STHSPHPTeam_HomeTeamStatL\">" . number_Format($row['HGA'] / $row['HGP'],2) ."</td></tr>\n";
	echo "<tr><td class=\"STHSPHPTeam_HomeTeamStatR\">" . number_Format($row['VPPGoal'] / $row['VPPAttemp'] * 100,2)."%" ."</td><td class=\"STHSPHPTeam_HomeTeamStatC\">" . $TeamLang['PowerPlayPercentage'] ."</td><td class=\"STHSPHPTeam_HomeTeamStatL\">" . number_Format($row['HPPGoal'] / $row['HPPAttemp'] * 100,2)."%" ."</td></tr>\n";
	echo "<tr><td class=\"STHSPHPTeam_HomeTeamStatR\">" . number_Format(($row['VPKAttemp']-$row['VPKGA']) / $row['VPKAttemp'] * 100,2)."%" ."</td><td class=\"STHSPHPTeam_HomeTeamStatC\">" . $TeamLang['PenaltyKillPercentage'] ."</td><td class=\"STHSPHPTeam_HomeTeamStatL\">" . number_Format(($row['HPKAttemp']-$row['HPKGA']) / $row['HPKAttemp'] * 100,2)."%" ."</td></tr>\n";
	echo "</table>\n";
}}
?>

<table style="width:100%;border-collapse:collapse">

<tr><td colspan="5" class="STHSPHPTeamStat_TableTitle" style="padding:10px"><?php echo $TeamLang['TeamLeaders'];?></td></tr>
<tr>
<?php
$ResultBound = False;
if (empty($TeamLeaderG) == false){while ($Row = $TeamLeaderG ->fetchArray()) {
	echo "<td class=\"STHSPHPTeam_HomePrimaryTableLeadersHeadshotTD\">";
	If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $Row['NHLID'] != ""){
	echo "<img loading=\"lazy\" src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $Row['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $Row['Name']. "\" class=\"STHSPHPTeam_HomePrimaryTableLeadersHeadshot\" />";}
	echo "</td><td class=\"STHSPHPTeam_HomePrimaryTableLeadersTextTD \"><span class=\"STHSPHPTeam_HomePrimaryTableLeadersTextStat\">" . $GeneralStatLang['Goals'] . "</span><br /><a class=\"STHSPHPTeam_HomePrimaryTableLeadersTextPlayer\" href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . "</a><br /><span class=\"STHSPHPTeam_HomePrimaryTableLeadersTextResult\">" . $Row['G'] . "</span></td>\n";
	$ResultBound = True;
}}?>
<td style="width:20px"></td>
<?php
if (empty($TeamLeaderA) == false){while ($Row = $TeamLeaderA ->fetchArray()) {
	echo "<td class=\"STHSPHPTeam_HomePrimaryTableLeadersHeadshotTD\">";
	If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $Row['NHLID'] != ""){
	echo "<img loading=\"lazy\" src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $Row['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $Row['Name']. "\" class=\"STHSPHPTeam_HomePrimaryTableLeadersHeadshot\" />";}
	echo "</td><td class=\"STHSPHPTeam_HomePrimaryTableLeadersTextTD \"><span class=\"STHSPHPTeam_HomePrimaryTableLeadersTextStat\">" . $GeneralStatLang['Assists'] . "</span><br /><a class=\"STHSPHPTeam_HomePrimaryTableLeadersTextPlayer\" href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . "</a><br /><span class=\"STHSPHPTeam_HomePrimaryTableLeadersTextResult\">" . $Row['A'] . "</span></td>\n";	
	$ResultBound = True;
}}
If ($ResultBound == False){echo "<td></td><td></td><td></td><td></td>\n";}?>
</tr>
<tr style="height:20px"><td colspan="5"></td></tr>
<tr>
<?php
$ResultBound = False;
if (empty($TeamLeaderP) == false){while ($Row = $TeamLeaderP ->fetchArray()) {
	echo "<td class=\"STHSPHPTeam_HomePrimaryTableLeadersHeadshotTD\">";
	If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $Row['NHLID'] != ""){
	echo "<img loading=\"lazy\" src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $Row['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $Row['Name']. "\" class=\"STHSPHPTeam_HomePrimaryTableLeadersHeadshot\" />";}
	echo "</td><td class=\"STHSPHPTeam_HomePrimaryTableLeadersTextTD \"><span class=\"STHSPHPTeam_HomePrimaryTableLeadersTextStat\">" . $GeneralStatLang['Points'] . "</span><br /><a class=\"STHSPHPTeam_HomePrimaryTableLeadersTextPlayer\" href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . "</a><br /><span class=\"STHSPHPTeam_HomePrimaryTableLeadersTextResult\">" . $Row['P'] . "</span></td>\n";	
	$ResultBound = True;
}}?>
<td style="width:20px"></td>
<?php
if (empty($TeamLeaderPlusMinus) == false){while ($Row = $TeamLeaderPlusMinus ->fetchArray()) {
	echo "<td class=\"STHSPHPTeam_HomePrimaryTableLeadersHeadshotTD\">";
	If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $Row['NHLID'] != ""){
	echo "<img loading=\"lazy\" src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $Row['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $Row['Name']. "\" class=\"STHSPHPTeam_HomePrimaryTableLeadersHeadshot\" />";}
	echo "</td><td class=\"STHSPHPTeam_HomePrimaryTableLeadersTextTD \"><span class=\"STHSPHPTeam_HomePrimaryTableLeadersTextStat\">" . $GeneralStatLang['PlusMinus'] . "</span><br /><a class=\"STHSPHPTeam_HomePrimaryTableLeadersTextPlayer\" href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . "</a><br /><span class=\"STHSPHPTeam_HomePrimaryTableLeadersTextResult\">" . $Row['PlusMinus'] . "</span></td>\n";	
	$ResultBound = True;
}}
If ($ResultBound == False){echo "<td></td><td></td><td></td><td></td>\n";}?>
</tr>
<tr style="height:20px"><td colspan="5"></td></tr>
<tr>
<?php
$ResultBound = False;
if (empty($TeamLeaderGAA ) == false){while ($Row = $TeamLeaderGAA  ->fetchArray()) {
	echo "<td class=\"STHSPHPTeam_HomePrimaryTableLeadersHeadshotTD\">";
	If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $Row['NHLID'] != ""){
	echo "<img loading=\"lazy\" src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $Row['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $Row['Name']. "\" class=\"STHSPHPTeam_HomePrimaryTableLeadersHeadshot\" />";}
	echo "</td><td class=\"STHSPHPTeam_HomePrimaryTableLeadersTextTD \"><span class=\"STHSPHPTeam_HomePrimaryTableLeadersTextStat\">" . $GeneralStatLang['Wins'] . "</span><br /><a class=\"STHSPHPTeam_HomePrimaryTableLeadersTextPlayer\" href=\"GoalieReport.php?Goalie=" . $Row['Number'] . "\">" . $Row['Name'] . "</a><br /><span class=\"STHSPHPTeam_HomePrimaryTableLeadersTextResult\">" . $Row['W'] . "</span></td>\n";	
	$ResultBound = True;	
}}?>
<td style="width:20px"></td>
<?php
if (empty($TeamLeaderSavePCT) == false){while ($Row = $TeamLeaderSavePCT ->fetchArray()) {
	echo "<td class=\"STHSPHPTeam_HomePrimaryTableLeadersHeadshotTD\">";
	If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $Row['NHLID'] != ""){
	echo "<img loading=\"lazy\" src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $Row['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $Row['Name']. "\" class=\"STHSPHPTeam_HomePrimaryTableLeadersHeadshot\" />";}
	echo "</td><td class=\"STHSPHPTeam_HomePrimaryTableLeadersTextTD \"><span class=\"STHSPHPTeam_HomePrimaryTableLeadersTextStat\">" . $GeneralStatLang['SavePCT'] . "</span><br /><a class=\"STHSPHPTeam_HomePrimaryTableLeadersTextPlayer\" href=\"GoalieReport.php?Goalie=" . $Row['Number'] . "\">" . $Row['Name'] . "</a><br /><span class=\"STHSPHPTeam_HomePrimaryTableLeadersTextResult\">" . $Row['PCT'] . "</span></td>\n";	
	$ResultBound = True;	
}}
If ($ResultBound == False){echo "<td></td><td></td><td></td><td></td>\n";}?>

</tr>
</table>
<br />
<table class="STHSPHPTeam_HomePrimaryTable" style="border-bottom-width:0px">
<tr><td colspan="4" class="STHSPHPTeamStat_TableTitle"><?php echo $TeamLang['TeamStats']?></td></tr>
<tr>
<?php if ($TeamStat != Null){
If ($TeamStat['GP'] > 0){	
	echo "<td class=\"STHSPHPTeam_HomePrimaryTableTeamStatTD\" style=\"border-bottom:1px solid #ddd;\"><span>" . $GeneralStatLang['GoalsFor'] . "</span><br><span class=\"STHSPHPTeam_HomePrimaryTableTeamStatStrongText\">" . $TeamStat['GF']. "</span><br><span>" .  number_Format($TeamStat['GF']/$TeamStat['GP'],2). "&nbsp;GFG</span></td>\n";
	echo "<td class=\"STHSPHPTeam_HomePrimaryTableTeamStatTD\" style=\"border-bottom:1px solid #ddd;border-left:1px solid #ddd;\"><span>" . $GeneralStatLang['ShotsFor'] . "</span><br><span class=\"STHSPHPTeam_HomePrimaryTableTeamStatStrongText\">" . $TeamStat['ShotsFor']. "</span><br><span>" .  number_Format($TeamStat['ShotsFor']/$TeamStat['GP'],2). "&nbsp;Avg</span></td>\n";
	echo "<td class=\"STHSPHPTeam_HomePrimaryTableTeamStatTD\" style=\"border-bottom:1px solid #ddd;border-left:1px solid #ddd;\"><span>" . $TeamLang['PowerPlayPercentage'] . "</span><br><span class=\"STHSPHPTeam_HomePrimaryTableTeamStatStrongText\">" . number_Format($TeamStat['PPGoal'] / $TeamStat['PPAttemp'] * 100,1). "%</span><br><span>" .  ($TeamStat['PPGoal']). "&nbsp;GF</span></td>\n";
	echo "<td class=\"STHSPHPTeam_HomePrimaryTableTeamStatTD\" style=\"border-bottom:1px solid #ddd;border-left:1px solid #ddd;\"><span>" . $TeamLang['OffensiveZoneStart'] . "</span><br><span class=\"STHSPHPTeam_HomePrimaryTableTeamStatStrongText\">" . number_Format($TeamStat['FaceOffTotalOffensifZone'] / ($TeamStat['FaceOffTotalDefensifZone']+$TeamStat['FaceOffTotalOffensifZone']+$TeamStat['FaceOffTotalNeutralZone'])*100,1). "%</span><br></td>\n";
	echo "</tr><tr>";
	echo "<td class=\"STHSPHPTeam_HomePrimaryTableTeamStatTD\"><span>" . $GeneralStatLang['GoalsAgainst'] . "</span><br><span class=\"STHSPHPTeam_HomePrimaryTableTeamStatStrongText\">" . $TeamStat['GA']. "</span><br><span>" .  number_Format($TeamStat['GA']/$TeamStat['GP'],2). "&nbsp;GAA</span></td>\n";
	echo "<td class=\"STHSPHPTeam_HomePrimaryTableTeamStatTD\" style=\"border-left:1px solid #ddd;\"><span>" . $GeneralStatLang['ShotsAgainst'] . "</span><br><span class=\"STHSPHPTeam_HomePrimaryTableTeamStatStrongText\">" . $TeamStat['ShotsAga']. "</span><br><span>" .  number_Format($TeamStat['ShotsAga']/$TeamStat['GP'],2). "&nbsp;Avg</span></td>\n";
	echo "<td class=\"STHSPHPTeam_HomePrimaryTableTeamStatTD\" style=\"border-left:1px solid #ddd;\"><span>" . $TeamLang['PenaltyKillPercentage'] . "</span><br><span class=\"STHSPHPTeam_HomePrimaryTableTeamStatStrongText\">" . number_Format(($TeamStat['PKAttemp'] - $TeamStat['PKGoalGA']) / $TeamStat['PKAttemp'] * 100,1). "%</span><br><span>" .  ($TeamStat['PKGoalGA']). "&nbsp;GA</span></td>\n";
	echo "<td class=\"STHSPHPTeam_HomePrimaryTableTeamStatTD\" style=\"border-left:1px solid #ddd;\"><span>" . $TeamLang['DefensiveZoneStart'] . "</span><br><span class=\"STHSPHPTeam_HomePrimaryTableTeamStatStrongText\">" . number_Format($TeamStat['FaceOffTotalDefensifZone'] / ($TeamStat['FaceOffTotalOffensifZone']+$TeamStat['FaceOffTotalDefensifZone']+$TeamStat['FaceOffTotalNeutralZone'])*100,1). "%</span><br></td>\n";
}else{echo "<td></td><td></td><td></td><td></td>";}}else{echo "<td></td><td></td><td></td><td></td>";}
?>
</tr>
</table>

</td><td class="STHSPHPPlayerStat_HomeLeftTD">
<?php
If ($TeamInfo <> Null){
	echo "<table class=\"STHSPHPTeam_HomeSecondaryTable\">";
	echo "<tr><td colspan=\"3\" class=\"STHSPHPTeamStat_TableTitle\">" . $TeamLang['TeamInfo'] . "<br /><br /></td></tr>\n";
	echo "<tr><td rowspan=\"";if (empty($CoachInfo) == false){echo "7";}else{echo "6";}echo "\">"; If ($TeamInfo['TeamThemeID'] > 0){echo "<img src=\"./images/" . $TeamInfo['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPTeamStatImage\" />";};echo "</td>\n";
	echo "<td>" . $TeamAndGMLang['GeneralManager'] . "</td><td class=\"STHSPHPTeam_HomeSecondaryTableTDStrongText\">" . $TeamProInfo['GMName'] . "</td></tr>\n";
	if (empty($CoachInfo) == false){echo "<tr><td>" . $TeamLang['Coach'] . "</td><td class=\"STHSPHPTeam_HomeSecondaryTableTDStrongText\">" . $CoachInfo['Name'] . "</td></tr>\n";}
	echo "<tr><td>" . $TeamLang['Division'] . "</td><td class=\"STHSPHPTeam_HomeSecondaryTableTDStrongText\">" . $TeamInfo['Division'] . "</td></tr>\n";
	echo "<tr><td>" . $TeamLang['Conference'] . "</td><td class=\"STHSPHPTeam_HomeSecondaryTableTDStrongText\">" . $TeamInfo['Conference'] . "</td></tr>\n";	
	echo "<tr><td>" . $TeamLang['Captain'] . "</td><td class=\"STHSPHPTeam_HomeSecondaryTableTDStrongText\">" . $TeamLeader['Captain'] . "</td></tr>\n";	
	echo "<tr><td>" . $TeamLang['Assistant1'] . "</td><td class=\"STHSPHPTeam_HomeSecondaryTableTDStrongText\">" . $TeamLeader['Assistant1'] . "</td></tr>\n";	
	echo "<tr><td>" . $TeamLang['Assistant2'] . "</td><td class=\"STHSPHPTeam_HomeSecondaryTableTDStrongText\">" . $TeamLeader['Assistant2'] . "</td></tr>\n";		
	echo "<tr><td colspan=\"3\" class=\"STHSPHPTeamStat_TableTitle\"><br /><br />" . $TeamLang['ArenaInfo'] . "<br /><br /></td></tr>\n";
	echo "<tr><td rowspan=\"3\"><img src=\"./images/ArenaInfo.png\" alt=\"\" class=\"STHSPHPTeam_HomeSecondaryTableImage\"></td>";
	echo "<td>" . $TeamLang['ArenaCapacity'] . "</td><td class=\"STHSPHPTeam_HomeSecondaryTableTDStrongText\">" . number_Format($TeamFinance['ArenaCapacityL1'] + $TeamFinance['ArenaCapacityL2']) . "</td></tr>\n";	
	echo "<tr><td>" . $TeamLang['Attendance'] . "</td><td class=\"STHSPHPTeam_HomeSecondaryTableTDStrongText\">";If ($TeamStat['HomeGP'] > 0){echo number_Format($TeamFinance['TotalAttendance'] / $TeamStat['HomeGP']);};echo  "</td></tr>\n";
	echo "<tr><td>" . $TeamLang['ArenaSeasonTickets'] . "</td><td class=\"STHSPHPTeam_HomeSecondaryTableTDStrongText\">" . number_Format((($TeamFinance['ArenaCapacityL1'] + $TeamFinance['ArenaCapacityL2'])*$TeamFinance['SeasonTicketPCT'])/100) . "</td></tr>\n";	

	echo "<tr><td colspan=\"3\" class=\"STHSPHPTeamStat_TableTitle\"><br /><br />" . $TeamLang['RosterInfo'] . "<br /><br /></td></tr>\n";
	echo "<tr><td rowspan=\"4\"><img src=\"./images/RosterInfo.png\" alt=\"\" class=\"STHSPHPTeam_HomeSecondaryTableImage\"></td>";
	echo "<td>" . $TeamLang['ProTeam'] . "</td><td class=\"STHSPHPTeam_HomeSecondaryTableTDStrongText\">" .  $PlayerInfoAverage['CountOfName'] . "</td></tr>\n";	
	echo "<tr><td>" . $TeamLang['FarmTeam'] . "</td><td class=\"STHSPHPTeam_HomeSecondaryTableTDStrongText\">" . ($PlayerInfoTotalAverage['CountOfName'] - $PlayerInfoAverage['CountOfName']) . "</td></tr>\n";	
	echo "<tr><td>" . $TeamLang['ContractLimit'] . "</td><td class=\"STHSPHPTeam_HomeSecondaryTableTDStrongText\">" .  $PlayerInfoTotalAverage['CountOfName']  . " / " . $LeagueWebClient['MaximumPlayerPerTeam'] . "</td></tr>\n";	
	echo "<tr><td>" . $TeamLang['Prospects'] . "</td><td class=\"STHSPHPTeam_HomeSecondaryTableTDStrongText\">" . $ProspectsCount['CountOfName'] . "</td></tr>\n";	
		
	if ($TeamCareerStatFound == true){
		echo "<tr><td colspan=\"3\" class=\"STHSPHPTeamStat_TableTitle\"><br /><br />" . $TeamLang['TeamHistory'] . "<br /><br /></td></tr>\n";
		echo "<tr><td rowspan=\"5\"><img src=\"./images/Stats.png\" alt=\"\" class=\"STHSPHPTeam_HomeSecondaryTableImage\"></td>";
		echo "<td>" . $TeamLang['ThisSeason']  . "</td><td class=\"STHSPHPTeam_HomeSecondaryTableTDStrongText\">";
		echo ($TeamStat['W'] + $TeamStat['OTW'] + $TeamStat['SOW']) . "-" .  $TeamStat['L'];
		if ($LeagueGeneral['PlayOffStarted'] == "False"){
			if($LeagueGeneral['PointSystemSO'] == "True"){
				echo  "-" . ($TeamStat['OTL'] + $TeamStat['SOL']) . " (" . $TeamStat['Points'] . "PTS)";
			}else{
				echo  "-" . $TeamStat['T'] . " ( " . $TeamStat['Points']. "PTS)";
			}
		}
		echo "</td></tr>\n";
		
		if ($TeamStat['GP'] > 0 AND $LeagueGeneral['PreSeasonSchedule'] == "False" AND $LeagueGeneral['PlayOffStarted'] == "False"){
			$TeamCareerW = $TeamCareerSumSeasonOnly['SumOfW'] + $TeamStat['W'] + $TeamCareerSumSeasonOnly['SumOfOTW'] + $TeamStat['OTW'] + $TeamCareerSumSeasonOnly['SumOfSOW'] + $TeamStat['SOW'];
			$TeamCareerL = $TeamCareerSumSeasonOnly['SumOfL'] + $TeamStat['L'];
			$TeamCareerOTLSOL = $TeamCareerSumSeasonOnly['SumOfOTW'] + $TeamStat['OTW'] + $TeamCareerSumSeasonOnly['SumOfSOL'] + $TeamStat['SOL'];
			$TeamCareerT = $TeamCareerSumSeasonOnly['SumOfT'] + $TeamStat['T'];
		}else{
			$TeamCareerW = $TeamCareerSumSeasonOnly['SumOfW']  + $TeamCareerSumSeasonOnly['SumOfOTW'] + $TeamCareerSumSeasonOnly['SumOfSOW'];
			$TeamCareerL = $TeamCareerSumSeasonOnly['SumOfL'];
			$TeamCareerOTLSOL = $TeamCareerSumSeasonOnly['SumOfOTW']  + $TeamCareerSumSeasonOnly['SumOfSOL'];
			$TeamCareerT = $TeamCareerSumSeasonOnly['SumOfT'];
		}
		
		echo "<tr><td>" . $TeamLang['History']  . "</td><td class=\"STHSPHPTeam_HomeSecondaryTableTDStrongText\">";
		echo $TeamCareerW . "-" .  $TeamCareerL;
		if($LeagueGeneral['PointSystemSO'] == "True"){
			echo  "-" . $TeamCareerOTLSOL;
		}else{
			echo  "-" . $TeamCareerT;
		}
		If ($TeamCareerL >0 ){echo " (" . number_Format($TeamCareerW / ($TeamCareerW + $TeamCareerL + $TeamCareerT + $TeamCareerOTLSOL),3) . "%)";}
		echo "</td></tr>\n";
		echo "<tr><td>" . $TeamLang['PlayoffAppearances']  . "</td><td class=\"STHSPHPTeam_HomeSecondaryTableTDStrongText\">" . $TeamCareerSumPlayoffOnly['SumOfPlayoff'] . "</td></tr>\n";	

		if ($TeamStat['GP'] >= 0 AND $LeagueGeneral['PreSeasonSchedule'] == "False" AND $LeagueGeneral['PlayOffStarted'] == "True"){
			$TeamCareerW = $TeamCareerSumPlayoffOnly['SumOfW'] + $TeamStat['W'];
			$TeamCareerL = $TeamCareerSumPlayoffOnly['SumOfL'] + $TeamStat['L'];
		}else{
			$TeamCareerW = $TeamCareerSumPlayoffOnly['SumOfW'];
			$TeamCareerL = $TeamCareerSumPlayoffOnly['SumOfL'];
		}		
		echo "<tr><td>" . $TeamLang['PlayoffRecord']  . "</td><td class=\"STHSPHPTeam_HomeSecondaryTableTDStrongText\">" . $TeamCareerW . "-" .  $TeamCareerL . "</td></tr>\n";
		If ($CupWinner <> Null){
			echo "<tr><td>" . $TeamLang['StanleyCup'] . "</td><td class=\"STHSPHPTeam_HomeSecondaryTableTDStrongText\">" . $CupWinner['CupWinner'] . "</td></tr>\n";			
		}else{
			echo "<tr><td></td><td class=\"STHSPHPTeam_HomeSecondaryTableTDStrongText\"></td></tr>\n";		
		}
	;}	
	
	
	echo "</table>";
}
?>
</td></tr></table>

<br /><br /></div>

<div class="tabmain<?php if($SubMenu ==1){echo " active";}?>" id="tabmain1">

<div class="tablesorter_ColumnSelectorWrapper">
    <input id="tablesorter_colSelect1P" type="checkbox" class="hidden">
    <label class="tablesorter_ColumnSelectorButton" for="tablesorter_colSelect1P"><?php echo $TableSorterLang['ShoworHideColumn'];?></label>
    <div id="tablesorter_ColumnSelector1P" class="tablesorter_ColumnSelector"></div>
	<?php include "FilterTip.php";?>
</div>

<table class="tablesorter STHSPHPTeam_PlayersRosterTable"><thead><tr>
<th data-priority="3" title="Order Number" class="STHSW10">#</th>
<th data-priority="critical" title="Player Name" class="STHSW140Min"><?php echo $PlayersLang['PlayerName'];?></th>
<?php if ($LeagueOutputOption != Null){if ($LeagueOutputOption['JerseyNumberInWebsite'] == "True") {echo "<th data-priority=\"6\" title=\"Jesery\" class=\"STHSW10\">#</th>";}}?>
<th data-priority="4" title="Center" class="STHSW10">C</th>
<th data-priority="4" title="Left Wing" class="STHSW10">L</th>
<th data-priority="4" title="Right Wing" class="STHSW10">R</th>
<th data-priority="4" title="Defenseman" class="STHSW10">D</th>
<th data-priority="1" title="Condition" class="STHSW25">CON</th>
<th data-priority="2" title="Checking" class="STHSW25">CK</th>
<th data-priority="2" title="Fighting" class="STHSW25">FG</th>
<th data-priority="2" title="Discipline" class="STHSW25">DI</th>
<th data-priority="2" title="Skating" class="STHSW25">SK</th>
<th data-priority="2" title="Strength" class="STHSW25">ST</th>
<th data-priority="2" title="Endurance" class="STHSW25">EN</th>
<th data-priority="2" title="Durability" class="STHSW25">DU</th>
<th data-priority="2" title="Puck Handling" class="STHSW25">PH</th>
<th data-priority="2" title="Face Offs" class="STHSW25">FO</th>
<th data-priority="2" title="Passing" class="STHSW25">PA</th>
<th data-priority="2" title="Scoring" class="STHSW25">SC</th>
<th data-priority="2" title="Defense" class="STHSW25">DF</th>
<th data-priority="2" title="Penalty Shot" class="STHSW25">PS</th>
<th data-priority="2" title="Experience" class="STHSW25">EX</th>
<th data-priority="2" title="Leadership" class="STHSW25">LD</th>
<th data-priority="3" title="Potential" class="STHSW25">PO</th>
<th data-priority="3" title="Morale" class="STHSW25">MO</th>
<th data-priority="critical" title="Overall" class="STHSW25">OV</th>
<th data-priority="5" title="Trade Available" class="STHSW25">TA</th>
<?php
if ($LeagueOutputOption != Null){
	if ($LeagueOutputOption['MergeRosterPlayerInfo'] == "True"){ 
		echo "<th data-priority=\"6\" title=\"Star Power\" class=\"columnSelector-false STHSW25\">SP</th>";	
		echo "<th data-priority=\"5\" class=\"STHSW25\" title=\"Age\">" . $PlayersLang['Age'] . "</th>";
		echo "<th data-priority=\"5\" class=\"STHSW25\" title=\"Contract\">" . $PlayersLang['Contract'] . "</th>";
		if ($LeagueFinance['SalaryCapOption'] == 4 OR $LeagueFinance['SalaryCapOption'] == 5 OR $LeagueFinance['SalaryCapOption'] == 6){
			echo "<th data-priority=\"5\" class=\"STHSW65\" title=\"Salary Average\">" . $PlayersLang['SalaryAverage'] ."</th>";
		}else{
			echo "<th data-priority=\"5\" class=\"STHSW65\" title=\"Salary\">" . $PlayersLang['Salary'] ."</th>";
		}
	}else{
		echo "<th data-priority=\"5\" title=\"Star Power\" class=\"STHSW25\">SP</th>";	
	}
}
echo "</tr></thead>";
If ($TeamInfo <> Null){
If ($LeagueOutputOption['MergeRosterPlayerInfo'] == "True"){$LoopEnd = 0;$Colspan=30;}else{$LoopEnd = 2;$Colspan=27;}
If ($LeagueOutputOption['JerseyNumberInWebsite'] == "True"){$Colspan +=1;}
for($Status = 1; $Status >= 0; $Status--){
	if ($Status == 1){echo "<tbody>";}
	if ($Status == 0){echo "</tbody><tbody class=\"tablesorter-no-sort\"><tr><th colspan=\"" . $Colspan . "\">" . $TeamLang['Scratches'] . "</th></tr></tbody><tbody>";}
	$LoopCount = (integer)0;
	$Query = "SELECT * FROM PlayerInfo WHERE Team = " . $Team . " AND Status1 = " . $Status . " Order By PosD, Overall DESC";
	If (file_exists($DatabaseFile) ==True){$PlayerRoster = $db->query($Query);}
	if (empty($PlayerRoster) == false){while ($Row = $PlayerRoster ->fetchArray()) {
		$LoopCount +=1;
		echo "<tr><td>" . $LoopCount . "</td>";
		$strTemp = (string)$Row['Name'];
		If ($Row['Rookie']== "True"){ $strTemp = $strTemp . " (R)";}
		If ($TeamInfo['Captain'] == $Row['Number']){ $strTemp = $strTemp . " (C)";}
		If ($TeamInfo['Assistant1'] == $Row['Number']){ $strTemp = $strTemp . " (A)";}
		If ($TeamInfo['Assistant2'] == $Row['Number']){ $strTemp = $strTemp . " (A)";}
		echo "<td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $strTemp . "</a></td>";
		if ($LeagueOutputOption['JerseyNumberInWebsite'] == "True") {echo "<td>" . $Row['Jersey'] . "</td>";}	
		echo "<td>";if  ($Row['PosC']== "True"){ echo "X";}; echo"</td>";
		echo "<td>";if  ($Row['PosLW']== "True"){ echo "X";}; echo"</td>";
		echo "<td>";if  ($Row['PosRW']== "True"){ echo "X";}; echo"</td>";
		echo "<td>";if  ($Row['PosD']== "True"){ echo "X";}; echo"</td>";		
		echo "<td>";if  ($Row <> Null){
			if ($Row['Suspension'] == 99){
				echo "HO";}elseif ($Row['Suspension'] > 0){echo "S" . $Row['Suspension'] . "</td>";
			}else{
				echo number_format(str_replace(",",".",$Row['ConditionDecimal']),2);
			}
		} echo"</td>";
		echo "<td>" . $Row['CK'] . "</td>";
		echo "<td>" . $Row['FG'] . "</td>";
		echo "<td>" . $Row['DI'] . "</td>";
		echo "<td>" . $Row['SK'] . "</td>";
		echo "<td>" . $Row['ST'] . "</td>";
		echo "<td>" . $Row['EN'] . "</td>";
		echo "<td>" . $Row['DU'] . "</td>";
		echo "<td>" . $Row['PH'] . "</td>";
		echo "<td>" . $Row['FO'] . "</td>";
		echo "<td>" . $Row['PA'] . "</td>";
		echo "<td>" . $Row['SC'] . "</td>";
		echo "<td>" . $Row['DF'] . "</td>";
		echo "<td>" . $Row['PS'] . "</td>";
		echo "<td>" . $Row['EX'] . "</td>";
		echo "<td>" . $Row['LD'] . "</td>";
		echo "<td>" . $Row['PO'] . "</td>";
		echo "<td>" . $Row['MO'] . "</td>";
		echo "<td>" . $Row['Overall'] . "</td>"; 
		echo "<td>";if ($Row['AvailableforTrade']== "True"){ echo "X";}; echo"</td>";
		echo "<td>" . $Row['StarPower'] . "</td>";
		if ($LeagueOutputOption['MergeRosterPlayerInfo'] == "True"){ 	
			echo "<td>" . $Row['Age'] . "</td>";
			echo "<td>" . $Row['Contract'] . "</td>";
			if ($LeagueFinance['SalaryCapOption'] == 4 OR $LeagueFinance['SalaryCapOption'] == 5 OR $LeagueFinance['SalaryCapOption'] == 6){
				echo "<td>" . number_format($Row['SalaryAverage'],0) . "$</td>";
			}else{
				echo "<td>" . number_format($Row['Salary1'],0) . "$</td>";
			}		
		}		
		echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
	}}
} 
echo "</tbody><tbody class=\"tablesorter-no-sort\">";
echo "<tr><td colspan=\"" . $Colspan . "\"></td></tr></tbody><tbody class=\"tablesorter-no-sort\">";
echo "<tr><td></td><td style=\"text-align:right;font-weight:bold\">" . $TeamLang['TeamAverage'] . "</td>";
echo "<td></td><td></td><td></td><td></td>";
If ($LeagueOutputOption['JerseyNumberInWebsite'] == "True"){echo "<td></td>";}
echo "<td>" . number_format($PlayerRosterAverage['AvgOfConditionDecimal'],2) . "</td>";
echo "<td>" . Round($PlayerRosterAverage['AvgOfCK']) . "</td>";
echo "<td>" . Round($PlayerRosterAverage['AvgOfFG']) . "</td>";
echo "<td>" . Round($PlayerRosterAverage['AvgOfDI']) . "</td>";
echo "<td>" . Round($PlayerRosterAverage['AvgOfSK']) . "</td>";
echo "<td>" . Round($PlayerRosterAverage['AvgOfST']) . "</td>";
echo "<td>" . Round($PlayerRosterAverage['AvgOfEN']) . "</td>";
echo "<td>" . Round($PlayerRosterAverage['AvgOfDU']) . "</td>";
echo "<td>" . Round($PlayerRosterAverage['AvgOfPH']) . "</td>";
echo "<td>" . Round($PlayerRosterAverage['AvgOfFO']) . "</td>";
echo "<td>" . Round($PlayerRosterAverage['AvgOfPA']) . "</td>";
echo "<td>" . Round($PlayerRosterAverage['AvgOfSC']) . "</td>";
echo "<td>" . Round($PlayerRosterAverage['AvgOfDF']) . "</td>";
echo "<td>" . Round($PlayerRosterAverage['AvgOfPS']) . "</td>";
echo "<td>" . Round($PlayerRosterAverage['AvgOfEX']) . "</td>";
echo "<td>" . Round($PlayerRosterAverage['AvgOfLD']) . "</td>";
echo "<td>" . Round($PlayerRosterAverage['AvgOfPO']) . "</td>";
echo "<td>" . Round($PlayerRosterAverage['AvgOfMO']) . "</td>";
echo "<td>" . Round($PlayerRosterAverage['AvgOfOverall']) . "</td>";
If ($LeagueOutputOption['MergeRosterPlayerInfo'] == "True"){echo "<td></td><td></td><td></td>";}
echo "<td></td><td></td></tr></tbody>";
}?>
</table>

<div class="tablesorter_ColumnSelectorWrapper">
    <input id="tablesorter_colSelect1G" type="checkbox" class="hidden">
    <label class="tablesorter_ColumnSelectorButton" for="tablesorter_colSelect1G"><?php echo $TableSorterLang['ShoworHideColumn'];?></label>
    <div id="tablesorter_ColumnSelector1G" class="tablesorter_ColumnSelector"></div>
	<?php include "FilterTip.php";?>
</div>

<table class="tablesorter STHSPHPTeam_GoaliesRosterTable"><thead><tr>
<th data-priority="4" title="Order Number" class="STHSW25">#</th>
<th data-priority="critical" title="Goalie Name" class="STHSW140Min"><?php echo $PlayersLang['GoalieName'];?></th>
<th data-priority="1" title="Condition" class="STHSW25">CON</th>
<th data-priority="2" title="Skating" class="STHSW25">SK</th>
<th data-priority="2" title="Durability" class="STHSW25">DU</th>
<th data-priority="2" title="Endurance" class="STHSW25">EN</th>
<th data-priority="2" title="Size" class="STHSW25">SZ</th>
<th data-priority="2" title="Agility" class="STHSW25">AG</th>
<th data-priority="2" title="Rebound Control" class="STHSW25">RB</th>
<th data-priority="2" title="Style Control" class="STHSW25">SC</th>
<th data-priority="2" title="Hand Speed" class="STHSW25">HS</th>
<th data-priority="2" title="Reaction Time" class="STHSW25">RT</th>
<th data-priority="2" title="Puck Handling" class="STHSW25">PH</th>
<th data-priority="2" title="Penalty Shot" class="STHSW25">PS</th>
<th data-priority="2" title="Experience" class="STHSW25">EX</th>
<th data-priority="2" title="Leadership" class="STHSW25">LD</th>
<th data-priority="3" title="Potential" class="STHSW25">PO</th>
<th data-priority="3" title="Morale" class="STHSW25">MO</th>
<th data-priority="critical" title="Overall" class="STHSW25">OV</th>
<th data-priority="5" title="Trade Available" class="STHSW25">TA</th>
<?php
if ($LeagueOutputOption != Null){
	if ($LeagueOutputOption['MergeRosterPlayerInfo'] == "True"){ 
		echo "<th data-priority=\"6\" title=\"Star Power\" class=\"columnSelector-false STHSW25\">SP</th>";	
		echo "<th data-priority=\"5\" class=\"STHSW25\" title=\"Age\">" . $PlayersLang['Age'] . "</th>";
		echo "<th data-priority=\"5\" class=\"STHSW25\" title=\"Contract\">" . $PlayersLang['Contract'] . "</th>";
		if ($LeagueFinance['SalaryCapOption'] == 4 OR $LeagueFinance['SalaryCapOption'] == 5 OR $LeagueFinance['SalaryCapOption'] == 6){
			echo "<th data-priority=\"5\" class=\"STHSW65\" title=\"Salary Average\">" . $PlayersLang['SalaryAverage'] ."</th>";
		}else{
			echo "<th data-priority=\"5\" class=\"STHSW65\" title=\"Salary\">" . $PlayersLang['Salary'] ."</th>";
		}
	}else{
		echo "<th data-priority=\"5\" title=\"Star Power\" class=\"STHSW25\">SP</th>";	
	}
}	
echo "</tr></thead>";
If ($TeamInfo <> Null){
If ($LeagueOutputOption['MergeRosterPlayerInfo'] == "True"){$Colspan=24;}else{$Colspan=21;}
for($Status = 1; $Status >= 0; $Status--){
	if ($Status == 1){echo "<tbody>";}
	if ($Status == 0){echo "</tbody><tbody class=\"tablesorter-no-sort\"><tr><th colspan=\"" . $Colspan . "\">" . $TeamLang['Scratches'] . "</th></tr></tbody><tbody>";}
	$LoopCount = (integer)0;
	$Query = "SELECT * FROM GoalerInfo WHERE Team = " . $Team . " AND Status1 = " . $Status . " Order By Overall DESC";
	If (file_exists($DatabaseFile) ==True){$GoalieRoster = $db->query($Query);}
	if (empty($GoalieRoster) == false){while ($Row = $GoalieRoster ->fetchArray()) {
		$LoopCount +=1;
		echo "<tr><td>" . $LoopCount . "</td>";
		$strTemp = (string)$Row['Name'];
		if ($Row['Rookie']== "True"){ $strTemp = $strTemp . " (R)";}
		echo "<td><a href=\"GoalieReport.php?Goalie=" . $Row['Number'] . "\">" . $strTemp . "</a></td>";
		echo "<td>";if  ($Row <> Null){
			if ($Row['Suspension'] == 99){
				echo "HO";}elseif ($Row['Suspension'] > 0){echo "S" . $Row['Suspension'] . "</td>";
			}else{
				echo number_format(str_replace(",",".",$Row['ConditionDecimal']),2);
			}
		} echo"</td>";
		echo "<td>" . $Row['SK'] . "</td>";
		echo "<td>" . $Row['DU'] . "</td>";
		echo "<td>" . $Row['EN'] . "</td>";
		echo "<td>" . $Row['SZ'] . "</td>";
		echo "<td>" . $Row['AG'] . "</td>";
		echo "<td>" . $Row['RB'] . "</td>";
		echo "<td>" . $Row['SC'] . "</td>";
		echo "<td>" . $Row['HS'] . "</td>";
		echo "<td>" . $Row['RT'] . "</td>";
		echo "<td>" . $Row['PH'] . "</td>";
		echo "<td>" . $Row['PS'] . "</td>";
		echo "<td>" . $Row['EX'] . "</td>";
		echo "<td>" . $Row['LD'] . "</td>";
		echo "<td>" . $Row['PO'] . "</td>";
		echo "<td>" . $Row['MO'] . "</td>";
		echo "<td>" . $Row['Overall'] . "</td>"; 
		echo "<td>";if ($Row['AvailableforTrade']== "True"){ echo "X";}; echo"</td>";
		echo "<td>" . $Row['StarPower'] . "</td>"; 
		if ($LeagueOutputOption['MergeRosterPlayerInfo'] == "True"){ 	
			echo "<td>" . $Row['Age'] . "</td>";
			echo "<td>" . $Row['Contract'] . "</td>";
			if ($LeagueFinance['SalaryCapOption'] == 4 OR $LeagueFinance['SalaryCapOption'] == 5 OR $LeagueFinance['SalaryCapOption'] == 6){
				echo "<td>" . number_format($Row['SalaryAverage'],0) . "$</td>";
			}else{
				echo "<td>" . number_format($Row['Salary1'],0) . "$</td>";
			}		
		}			
		echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
	}}
}
echo "</tbody><tbody class=\"tablesorter-no-sort\">";
echo "<tr><td colspan=\"" . $Colspan . "\"></td></tr></tbody><tbody class=\"tablesorter-no-sort\">";
echo "<tr><td></td><td style=\"text-align:right;font-weight:bold;\">" . $TeamLang['TeamAverage'] . "</td>";
echo "<td>" . number_format($GoalieRosterAverage['AvgOfConditionDecimal'],2) . "</td>";
echo "<td>" . Round($GoalieRosterAverage['AvgOfSK']). "</td>";
echo "<td>" . Round($GoalieRosterAverage['AvgOfDU']). "</td>";
echo "<td>" . Round($GoalieRosterAverage['AvgOfEN']). "</td>";
echo "<td>" . Round($GoalieRosterAverage['AvgOfSZ']). "</td>";
echo "<td>" . Round($GoalieRosterAverage['AvgOfAG']). "</td>";
echo "<td>" . Round($GoalieRosterAverage['AvgOfRB']). "</td>";
echo "<td>" . Round($GoalieRosterAverage['AvgOfSC']). "</td>";
echo "<td>" . Round($GoalieRosterAverage['AvgOfHS']). "</td>";
echo "<td>" . Round($GoalieRosterAverage['AvgOfRT']). "</td>";
echo "<td>" . Round($GoalieRosterAverage['AvgOfPH']). "</td>";
echo "<td>" . Round($GoalieRosterAverage['AvgOfPS']). "</td>";
echo "<td>" . Round($GoalieRosterAverage['AvgOfEX']). "</td>";
echo "<td>" . Round($GoalieRosterAverage['AvgOfLD']). "</td>";
echo "<td>" . Round($GoalieRosterAverage['AvgOfPO']). "</td>";
echo "<td>" . Round($GoalieRosterAverage['AvgOfMO']). "</td>";
echo "<td>" . Round($GoalieRosterAverage['AvgOfOverall']). "</td>";
If ($LeagueOutputOption['MergeRosterPlayerInfo'] == "True"){echo "<td></td><td></td><td></td>";}
echo "<td></td><td></td></tr></tbody>";
}?>
</table>

<table class="tablesorter STHSPHPTeam_CoachesTable"><thead><tr>
<th title="Coaches Name" class="STHSW200"><?php echo $CoachesLang['CoachesName'];?></th>
<th title="Physical Style" class="STHSW25">PH</th>
<th title="Defense Style" class="STHSW25">DF</th>
<th title="Offense Style" class="STHSW25">OF</th>
<th title="Player Discipline" class="STHSW25">PD</th>
<th title="Experience" class="STHSW25">EX</th>
<th title="Leadership" class="STHSW25">LD</th>
<th title="Potential" class="STHSW25">PO</th>
<th title="Country" class="STHSW35">CNT</th>
<th title="Age" class="STHSW35"><?php echo $CoachesLang['Age'];?></th>
<th title="Contract" class="STHSW25"><?php echo $CoachesLang['Contract'];?></th>
<th title="Salary" class="STHSW100"><?php echo $CoachesLang['Salary'];?></th>
</thead><tbody>
<?php
if (empty($CoachInfo) == false){
	echo "<tr><td style=\"text-align:center;\">" . $CoachInfo['Name'] . "</td>";
	echo "<td>" . $CoachInfo['PH'] . "</td>";
	echo "<td>" . $CoachInfo['DF'] . "</td>";
	echo "<td>" . $CoachInfo['OF'] . "</td>";
	echo "<td>" . $CoachInfo['PD'] . "</td>";
	echo "<td>" . $CoachInfo['EX'] . "</td>";
	echo "<td>" . $CoachInfo['LD'] . "</td>";
	echo "<td>" . $CoachInfo['PO'] . "</td>";
	echo "<td>" . $CoachInfo['Country'] . "</td>";
	echo "<td>" . $CoachInfo['Age'] . "</td>";
	echo "<td>" . $CoachInfo['Contract'] . "</td>";
	echo "<td>" . number_format($CoachInfo['Salary'],0) . "$</td></tr>";
}
?>
</tbody></table>

<br /><br /></div>
<div class="tabmain<?php if($SubMenu ==2){echo " active";}?>" id="tabmain2">

<div class="tablesorter_ColumnSelectorWrapper">
    <input id="tablesorter_colSelect2P" type="checkbox" class="hidden">
    <label class="tablesorter_ColumnSelectorButton" for="tablesorter_colSelect2P"><?php echo $TableSorterLang['ShoworHideColumn'];?></label>
    <div id="tablesorter_ColumnSelector2P" class="tablesorter_ColumnSelector"></div>
	<?php include "FilterTip.php";?>
</div>

<table class="tablesorter STHSPHPTeam_PlayersScoringTable"><thead><tr>
<?php 
include "PlayersStatSub.php";
if($PlayerStatTeam != Null){If ($PlayerStatTeam['SumOfGP'] > 0){
	echo "</tbody><tbody class=\"tablesorter-no-sort\">";
	echo "<tr><td colspan=\"2\" style=\"text-align:right;font-weight:bold\">" . $TeamLang['TeamTotalAverage'] . "</td><td></td><td></td>";
	echo "<td>" . $PlayerStatTeam['SumOfGP'] . "</td>";
	echo "<td>" . $PlayerStatTeam['SumOfG'] . "</td>";
	echo "<td>" . $PlayerStatTeam['SumOfA'] . "</td>";
	echo "<td>" . $PlayerStatTeam['SumOfP'] . "</td>";
	echo "<td>" . $PlayerStatTeam['SumOfPlusMinus'] . "</td>";
	echo "<td>" . $PlayerStatTeam['SumOfPim'] . "</td>";
	echo "<td>" . $PlayerStatTeam['SumOfPim5'] . "</td>";
	echo "<td>" . $PlayerStatTeam['SumOfHits'] . "</td>";	
	echo "<td>" . $PlayerStatTeam['SumOfHitsTook'] . "</td>";		
	echo "<td>" . $PlayerStatTeam['SumOfShots'] . "</td>";
	echo "<td>" . $PlayerStatTeam['SumOfOwnShotsBlock'] . "</td>";
	echo "<td>" . $PlayerStatTeam['SumOfOwnShotsMissGoal'] . "</td>";
	echo "<td>" . number_Format($PlayerStatTeam['SumOfShotsPCT'],2) . "%</td>";		
	echo "<td>" . $PlayerStatTeam['SumOfShotsBlock'] . "</td>";	
	echo "<td>" . Floor($PlayerStatTeam['SumOfSecondPlay']/60) . "</td>";
	echo "<td>" . number_Format($PlayerStatTeam['SumOfAMG'],2) . "</td>";		
	echo "<td>" . $PlayerStatTeam['SumOfPPG'] . "</td>";
	echo "<td>" . $PlayerStatTeam['SumOfPPA'] . "</td>";
	echo "<td>" . $PlayerStatTeam['SumOfPPP'] . "</td>";
	echo "<td>" . $PlayerStatTeam['SumOfPPShots'] . "</td>";
	echo "<td>" . Floor($PlayerStatTeam['SumOfPPSecondPlay']/60) . "</td>";	
	echo "<td>" . $PlayerStatTeam['SumOfPKG'] . "</td>";
	echo "<td>" . $PlayerStatTeam['SumOfPKA'] . "</td>";
	echo "<td>" . $PlayerStatTeam['SumOfPKP'] . "</td>";
	echo "<td>" . $PlayerStatTeam['SumOfPKShots'] . "</td>";
	echo "<td>" . Floor($PlayerStatTeam['SumOfPKSecondPlay']/60) . "</td>";	
	echo "<td>" . $PlayerStatTeam['SumOfGW'] . "</td>";
	echo "<td>" . $PlayerStatTeam['SumOfGT'] . "</td>";
	echo "<td>" . number_Format($PlayerStatTeam['SumOfFaceoffPCT'],2) . "%</td>";	
	echo "<td>" . $PlayerStatTeam['SumOfFaceOffTotal'] . "</td>";
	echo "<td>" . $PlayerStatTeam['SumOfGiveAway'] . "</td>";
	echo "<td>" . $PlayerStatTeam['SumOfTakeAway'] . "</td>";
	echo "<td>" . $PlayerStatTeam['SumOfEmptyNetGoal'] . "</td>";
	echo "<td>" . $PlayerStatTeam['SumOfHatTrick'] . "</td>";	
	echo "<td>" . number_Format($PlayerStatTeam['SumOfP20'],2) . "</td>";			
	echo "<td>" . $PlayerStatTeam['SumOfPenalityShotsScore'] . "</td>";
	echo "<td>" . $PlayerStatTeam['SumOfPenalityShotsTotal'] . "</td>";
	echo "<td>" . $PlayerStatTeam['SumOfFightW'] . "</td>";
	echo "<td>" . $PlayerStatTeam['SumOfFightL'] . "</td>";
	echo "<td>" . $PlayerStatTeam['SumOfFightT'] . "</td>";
	echo "<td>" . $PlayerStatTeam['SumOfStar1'] . "</td>";
	echo "<td>" . $PlayerStatTeam['SumOfStar2'] . "</td>";
	echo "<td>" . $PlayerStatTeam['SumOfStar3'] . "</td>";
	echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
}}
?>
</tbody></table>

<div class="tablesorter_ColumnSelectorWrapper">
    <input id="tablesorter_colSelect2G" type="checkbox" class="hidden">
    <label class="tablesorter_ColumnSelectorButton" for="tablesorter_colSelect2G"><?php echo $TableSorterLang['ShoworHideColumn'];?></label>
    <div id="tablesorter_ColumnSelector2G" class="tablesorter_ColumnSelector"></div>
	<?php include "FilterTip.php";?>
</div>

<table class="tablesorter STHSPHPTeam_GoaliesScoringTable"><thead><tr>
<?php 
include "GoaliesStatSub.php";
if($PlayerStatTeam != Null){If ($PlayerStatTeam['SumOfGP'] > 0){
	echo "</tbody><tbody class=\"tablesorter-no-sort\">";
	echo "<tr><td colspan=\"2\" style=\"text-align:right;font-weight:bold\">" . $TeamLang['TeamTotalAverage'] . "</td><td></td>";
	echo "<td>" . $GoalieStatTeam['SumOfGP'] . "</td>";
	echo "<td>" . $GoalieStatTeam['SumOfW'] . "</td>";
	echo "<td>" . $GoalieStatTeam['SumOfL'] . "</td>";
	echo "<td>" . $GoalieStatTeam['SumOfOTL'] . "</td>";
	echo "<td>" . number_Format($GoalieStatTeam['SumOfPCT'],3) . "</td>";
	echo "<td>" . number_Format($GoalieStatTeam['SumOfGAA'],2) . "</td>";
	echo "<td>";if ($GoalieStatTeam <> Null){echo Floor($GoalieStatTeam['SumOfSecondPlay']/60);}; echo "</td>";
	echo "<td>" . $GoalieStatTeam['SumOfPim'] . "</td>";
	echo "<td>" . $GoalieStatTeam['SumOfShootout'] . "</td>";
	echo "<td>" . $GoalieStatTeam['SumOfGA'] . "</td>";
	echo "<td>" . $GoalieStatTeam['SumOfSA'] . "</td>";
	echo "<td>" . $GoalieStatTeam['SumOfSARebound'] . "</td>";
	echo "<td>" . $GoalieStatTeam['SumOfA'] . "</td>";
	echo "<td>" . $GoalieStatTeam['SumOfEmptyNetGoal'] . "</td>";			
	echo "<td>" . number_Format($GoalieStatTeam['SumOfPenalityShotsPCT'],3) . "</td>";
	echo "<td>" . $GoalieStatTeam['SumOfPenalityShotsShots'] . "</td>";
	echo "<td>" . $GoalieStatTeam['SumOfStartGoaler'] . "</td>";
	echo "<td>" . $GoalieStatTeam['SumOfBackupGoaler'] . "</td>";
	echo "<td>" . $GoalieStatTeam['SumOfStar1'] . "</td>";
	echo "<td>" . $GoalieStatTeam['SumOfStar2'] . "</td>";
	echo "<td>" . $GoalieStatTeam['SumOfStar3'] . "</td>";
	echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
}}
?>
</tbody></table>

<br /><br /></div>
<div class="tabmain<?php if($SubMenu ==3){echo " active";}?>" id="tabmain3">

<div class="tablesorter_ColumnSelectorWrapper">
    <input id="tablesorter_colSelect3" type="checkbox" class="hidden">
    <label class="tablesorter_ColumnSelectorButton" for="tablesorter_colSelect3"><?php echo $TableSorterLang['ShoworHideColumn'];?></label>
    <div id="tablesorter_ColumnSelector3" class="tablesorter_ColumnSelector"></div>
	<?php include "FilterTip.php";?>
</div>
<table class="tablesorter STHSPHPTeam_PlayerInfoTable"><thead><tr>
<?php 
$FreeAgentYear = -1;
include "PlayersInfoSub.php";
?>
</tbody></table>

<table class="STHSPHPTeamStat_Table"><tr><th class="STHSW100"><?php echo $TeamLang['TotalPlayers'];?></th><th class="STHSW100"><?php echo $TeamLang['AverageAge'];?></th><th class="STHSW120"><?php echo $TeamLang['AverageWeight'];?></th><th class="STHSW120"><?php echo $TeamLang['AverageHeight'];?></th><th class="STHSW120"><?php echo $TeamLang['AverageContract'];?></th><th class="STHSW140"><?php echo $TeamLang['AverageYear1Salary'];?></th></tr>
<?php
If ($PlayerInfoAverage != Null){
	echo "<tr><td>" . $PlayerInfoAverage['CountOfName'] . "</td>";
	echo "<td>" . number_format($PlayerInfoAverage['AvgOfAge'],2) . "</td>";
	If ($LeagueOutputOption['LBSInsteadofKG'] == "True"){echo "<td>" . Round($PlayerInfoAverage['AvgOfWeight']) . " Lbs</td>";}else{echo "<td>" . Round(Round($PlayerInfoAverage['AvgOfWeight']) / 2.2) . " Kg</td>";}
	If ($LeagueOutputOption['InchInsteadofCM'] == "True"){echo "<td>" . ((Round($PlayerInfoAverage['AvgOfHeight']) - (Round($PlayerInfoAverage['AvgOfHeight']) % 12))/12) . " ft" .  (Round($PlayerInfoAverage['AvgOfHeight']) % 12) .  "</td>";}else{echo "<td>" . Round(Round($PlayerInfoAverage['AvgOfHeight']) * 2.54) . " CM</td>";}		
	echo "<td>" . number_format($PlayerInfoAverage['AvgOfContract'],2) . "</td>";
	echo "<td>" . number_format($PlayerInfoAverage['AvgOfSalary1'],0) . "$</td></tr>";	
}	
?>
</table>

<br /><br /></div>
<div class="tabmain<?php if($SubMenu ==4){echo " active";}?>" id="tabmain4">
<br />

<table class="STHSPHPTeamStat_Table"><tr><th colspan="8"><?php echo $TeamLang['5vs5Forward'];?></th></tr><tr>
<th class="STHSW25"><?php echo $TeamLang['LineNumber'];?></th><th class="STHSW140"><?php echo $TeamLang['LeftWing'];?></th><th class="STHSW140"><?php echo $TeamLang['Center'];?></th><th class="STHSW140"><?php echo $TeamLang['RightWing'];?></th><th class="STHSW25"><?php echo $TeamLang['TimePCT'];?></th><th class="STHSW25"><?php echo $TeamLang['PHY'];?></th><th class="STHSW25"><?php echo $TeamLang['DF'];?></th><th class="STHSW25"><?php echo $TeamLang['OF'];?></th></tr>
<?php if ($TeamLines != Null){
echo "<tr><td>1</td>";
echo "<td>" . $TeamLines['Line15vs5ForwardLeftWing'] . "</td>";
echo "<td>" . $TeamLines['Line15vs5ForwardCenter'] . "</td>";
echo "<td>" . $TeamLines['Line15vs5ForwardRightWing'] . "</td>";
echo "<td>" . $TeamLines['Line15vs5ForwardTime'] . "</td>";
echo "<td>" . $TeamLines['Line15vs5ForwardPhy'] . "</td>";
echo "<td>" . $TeamLines['Line15vs5ForwardDF'] . "</td>";
echo "<td>" . $TeamLines['Line15vs5ForwardOF'] . "</td>";
echo "</tr>\n<tr><td>2</td>";
echo "<td>" . $TeamLines['Line25vs5ForwardLeftWing'] . "</td>";
echo "<td>" . $TeamLines['Line25vs5ForwardCenter'] . "</td>";
echo "<td>" . $TeamLines['Line25vs5ForwardRightWing'] . "</td>";
echo "<td>" . $TeamLines['Line25vs5ForwardTime'] . "</td>";
echo "<td>" . $TeamLines['Line25vs5ForwardPhy'] . "</td>";
echo "<td>" . $TeamLines['Line25vs5ForwardDF'] . "</td>";
echo "<td>" . $TeamLines['Line25vs5ForwardOF'] . "</td>";
echo "</tr>\n<tr><td>3</td>";
echo "<td>" . $TeamLines['Line35vs5ForwardLeftWing'] . "</td>";
echo "<td>" . $TeamLines['Line35vs5ForwardCenter'] . "</td>";
echo "<td>" . $TeamLines['Line35vs5ForwardRightWing'] . "</td>";
echo "<td>" . $TeamLines['Line35vs5ForwardTime'] . "</td>";
echo "<td>" . $TeamLines['Line35vs5ForwardPhy'] . "</td>";
echo "<td>" . $TeamLines['Line35vs5ForwardDF'] . "</td>";
echo "<td>" . $TeamLines['Line35vs5ForwardOF'] . "</td>";
echo "</tr>\n<tr><td>4</td>";
echo "<td>" . $TeamLines['Line45vs5ForwardLeftWing'] . "</td>";
echo "<td>" . $TeamLines['Line45vs5ForwardCenter'] . "</td>";
echo "<td>" . $TeamLines['Line45vs5ForwardRightWing'] . "</td>";
echo "<td>" . $TeamLines['Line45vs5ForwardTime'] . "</td>";
echo "<td>" . $TeamLines['Line45vs5ForwardPhy'] . "</td>";
echo "<td>" . $TeamLines['Line45vs5ForwardDF'] . "</td>";
echo "<td>" . $TeamLines['Line45vs5ForwardOF'] . "</td>";
echo "</tr>";
}?></table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPTeamStat_Table"><tr><th colspan="8"><?php echo $TeamLang['5vs5Defense'];?></th></tr><tr>
<th class="STHSW25"><?php echo $TeamLang['LineNumber'];?></th><th class="STHSW140"><?php echo $TeamLang['Defense'];?></th><th class="STHSW140"><?php echo $TeamLang['Defense'];?></th><th class="STHSW140"></th><th class="STHSW25"><?php echo $TeamLang['TimePCT'];?></th><th class="STHSW25"><?php echo $TeamLang['PHY'];?></th><th class="STHSW25"><?php echo $TeamLang['DF'];?></th><th class="STHSW25"><?php echo $TeamLang['OF'];?></th></tr>
<?php if ($TeamLines != Null){
echo "<tr><td>1</td>";
echo "<td>" . $TeamLines['Line15vs5DefenseDefense1'] . "</td>";
echo "<td>" . $TeamLines['Line15vs5DefenseDefense2'] . "</td>";
echo "<td></td>";
echo "<td>" . $TeamLines['Line15vs5DefenseTime'] . "</td>";
echo "<td>" . $TeamLines['Line15vs5DefensePhy'] . "</td>";
echo "<td>" . $TeamLines['Line15vs5DefenseDF'] . "</td>";
echo "<td>" . $TeamLines['Line15vs5DefenseOF'] . "</td>";
echo "</tr>\n<tr><td>2</td>";
echo "<td>" . $TeamLines['Line25vs5DefenseDefense1'] . "</td>";
echo "<td>" . $TeamLines['Line25vs5DefenseDefense2'] . "</td>";
echo "<td></td>";
echo "<td>" . $TeamLines['Line25vs5DefenseTime'] . "</td>";
echo "<td>" . $TeamLines['Line25vs5DefensePhy'] . "</td>";
echo "<td>" . $TeamLines['Line25vs5DefenseDF'] . "</td>";
echo "<td>" . $TeamLines['Line25vs5DefenseOF'] . "</td>";
echo "</tr>\n<tr><td>3</td>";
echo "<td>" . $TeamLines['Line35vs5DefenseDefense1'] . "</td>";
echo "<td>" . $TeamLines['Line35vs5DefenseDefense2'] . "</td>";
echo "<td></td>";
echo "<td>" . $TeamLines['Line35vs5DefenseTime'] . "</td>";
echo "<td>" . $TeamLines['Line35vs5DefensePhy'] . "</td>";
echo "<td>" . $TeamLines['Line35vs5DefenseDF'] . "</td>";
echo "<td>" . $TeamLines['Line35vs5DefenseOF'] . "</td>";
echo "</tr>\n<tr><td>4</td>";
echo "<td>" . $TeamLines['Line45vs5DefenseDefense1'] . "</td>";
echo "<td>" . $TeamLines['Line45vs5DefenseDefense2'] . "</td>";
echo "<td></td>";
echo "<td>" . $TeamLines['Line45vs5DefenseTime'] . "</td>";
echo "<td>" . $TeamLines['Line45vs5DefensePhy'] . "</td>";
echo "<td>" . $TeamLines['Line45vs5DefenseDF'] . "</td>";
echo "<td>" . $TeamLines['Line45vs5DefenseOF'] . "</td>";
echo "</tr>";
}?></table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPTeamStat_Table"><tr><th colspan="8"><?php echo $TeamLang['PowerPlayForward'];?></th></tr><tr>
<th class="STHSW25"><?php echo $TeamLang['LineNumber'];?></th><th class="STHSW140"><?php echo $TeamLang['LeftWing'];?></th><th class="STHSW140"><?php echo $TeamLang['Center'];?></th><th class="STHSW140"><?php echo $TeamLang['RightWing'];?></th><th class="STHSW25"><?php echo $TeamLang['TimePCT'];?></th><th class="STHSW25"><?php echo $TeamLang['PHY'];?></th><th class="STHSW25"><?php echo $TeamLang['DF'];?></th><th class="STHSW25"><?php echo $TeamLang['OF'];?></th></tr>
<?php if ($TeamLines != Null){
echo "<tr><td>1</td>";
echo "<td>" . $TeamLines['Line1PPForwardLeftWing'] . "</td>";
echo "<td>" . $TeamLines['Line1PPForwardCenter'] . "</td>";
echo "<td>" . $TeamLines['Line1PPForwardRightWing'] . "</td>";
echo "<td>" . $TeamLines['Line1PPForwardTime'] . "</td>";
echo "<td>" . $TeamLines['Line1PPForwardPhy'] . "</td>";
echo "<td>" . $TeamLines['Line1PPForwardDF'] . "</td>";
echo "<td>" . $TeamLines['Line1PPForwardOF'] . "</td>";
echo "</tr>\n<tr><td>2</td>";
echo "<td>" . $TeamLines['Line2PPForwardLeftWing'] . "</td>";
echo "<td>" . $TeamLines['Line2PPForwardCenter'] . "</td>";
echo "<td>" . $TeamLines['Line2PPForwardRightWing'] . "</td>";
echo "<td>" . $TeamLines['Line2PPForwardTime'] . "</td>";
echo "<td>" . $TeamLines['Line2PPForwardPhy'] . "</td>";
echo "<td>" . $TeamLines['Line2PPForwardDF'] . "</td>";
echo "<td>" . $TeamLines['Line2PPForwardOF'] . "</td>";
echo "</tr>";
}?></table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPTeamStat_Table"><tr><th colspan="8"><?php echo $TeamLang['PowerPlayDefense'];?></th></tr><tr>
<th class="STHSW25"><?php echo $TeamLang['LineNumber'];?></th><th class="STHSW140"><?php echo $TeamLang['Defense'];?></th><th class="STHSW140"><?php echo $TeamLang['Defense'];?></th><th class="STHSW140"></th><th class="STHSW25"><?php echo $TeamLang['TimePCT'];?></th><th class="STHSW25"><?php echo $TeamLang['PHY'];?></th><th class="STHSW25"><?php echo $TeamLang['DF'];?></th><th class="STHSW25"><?php echo $TeamLang['OF'];?></th></tr>
<?php if ($TeamLines != Null){
echo "<tr><td>1</td>";
echo "<td>" . $TeamLines['Line1PPDefenseDefense1'] . "</td>";
echo "<td>" . $TeamLines['Line1PPDefenseDefense2'] . "</td>";
echo "<td></td>";
echo "<td>" . $TeamLines['Line1PPDefenseTime'] . "</td>";
echo "<td>" . $TeamLines['Line1PPDefensePhy'] . "</td>";
echo "<td>" . $TeamLines['Line1PPDefenseDF'] . "</td>";
echo "<td>" . $TeamLines['Line1PPDefenseOF'] . "</td>";
echo "</tr>\n<tr><td>2</td>";
echo "<td>" . $TeamLines['Line2PPDefenseDefense1'] . "</td>";
echo "<td>" . $TeamLines['Line2PPDefenseDefense2'] . "</td>";
echo "<td></td>";
echo "<td>" . $TeamLines['Line2PPDefenseTime'] . "</td>";
echo "<td>" . $TeamLines['Line2PPDefensePhy'] . "</td>";
echo "<td>" . $TeamLines['Line2PPDefenseDF'] . "</td>";
echo "<td>" . $TeamLines['Line2PPDefenseOF'] . "</td>";
echo "</tr>";
}?></table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPTeamStat_Table"><tr><th colspan="7"><?php echo $TeamLang['PenaltyKill4PlayersForward'];?></th></tr><tr>
<th class="STHSW25"><?php echo $TeamLang['LineNumber'];?></th><th class="STHSW140"><?php echo $TeamLang['Center'];?></th><th class="STHSW140"><?php echo $TeamLang['Wing'];?></th><th class="STHSW25"><?php echo $TeamLang['TimePCT'];?></th><th class="STHSW25"><?php echo $TeamLang['PHY'];?></th><th class="STHSW25"><?php echo $TeamLang['DF'];?></th><th class="STHSW25"><?php echo $TeamLang['OF'];?></th></tr>
<?php if ($TeamLines != Null){
echo "<tr><td>1</td>";
echo "<td>" . $TeamLines['Line1PK4ForwardCenter'] . "</td>";
echo "<td>" . $TeamLines['Line1PK4ForwardWing'] . "</td>";
echo "<td>" . $TeamLines['Line1PK4ForwardTime'] . "</td>";
echo "<td>" . $TeamLines['Line1PK4ForwardPhy'] . "</td>";
echo "<td>" . $TeamLines['Line1PK4ForwardDF'] . "</td>";
echo "<td>" . $TeamLines['Line1PK4ForwardOF'] . "</td>";
echo "</tr>\n<tr><td>2</td>";
echo "<td>" . $TeamLines['Line2PK4ForwardCenter'] . "</td>";
echo "<td>" . $TeamLines['Line2PK4ForwardWing'] . "</td>";
echo "<td>" . $TeamLines['Line2PK4ForwardTime'] . "</td>";
echo "<td>" . $TeamLines['Line2PK4ForwardPhy'] . "</td>";
echo "<td>" . $TeamLines['Line2PK4ForwardDF'] . "</td>";
echo "<td>" . $TeamLines['Line2PK4ForwardOF'] . "</td>";
echo "</tr>";
}?></table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPTeamStat_Table"><tr><th colspan="7"><?php echo $TeamLang['PenaltyKill4PlayersDefense'];?></th></tr><tr>
<th class="STHSW25"><?php echo $TeamLang['LineNumber'];?></th><th class="STHSW140"><?php echo $TeamLang['Defense'];?></th><th class="STHSW140"><?php echo $TeamLang['Defense'];?></th><th class="STHSW25"><?php echo $TeamLang['TimePCT'];?></th><th class="STHSW25"><?php echo $TeamLang['PHY'];?></th><th class="STHSW25"><?php echo $TeamLang['DF'];?></th><th class="STHSW25"><?php echo $TeamLang['OF'];?></th></tr>
<?php if ($TeamLines != Null){
echo "<tr><td>1</td>";
echo "<td>" . $TeamLines['Line1PK4DefenseDefense1'] . "</td>";
echo "<td>" . $TeamLines['Line1PK4DefenseDefense2'] . "</td>";
echo "<td>" . $TeamLines['Line1PK4DefenseTime'] . "</td>";
echo "<td>" . $TeamLines['Line1PK4DefensePhy'] . "</td>";
echo "<td>" . $TeamLines['Line1PK4DefenseDF'] . "</td>";
echo "<td>" . $TeamLines['Line1PK4DefenseOF'] . "</td>";
echo "</tr>\n<tr><td>2</td>";
echo "<td>" . $TeamLines['Line2PK4DefenseDefense1'] . "</td>";
echo "<td>" . $TeamLines['Line2PK4DefenseDefense2'] . "</td>";
echo "<td>" . $TeamLines['Line2PK4DefenseTime'] . "</td>";
echo "<td>" . $TeamLines['Line2PK4DefensePhy'] . "</td>";
echo "<td>" . $TeamLines['Line2PK4DefenseDF'] . "</td>";
echo "<td>" . $TeamLines['Line2PK4DefenseOF'] . "</td>";
echo "</tr>";
}?></table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPTeamStat_Table"><tr><th colspan="12"><?php echo $TeamLang['PenaltyKill3Players'];?></th></tr><tr>
<th class="STHSW25"><?php echo $TeamLang['LineNumber'];?></th><th class="STHSW140"><?php echo $TeamLang['Wing'];?></th><th class="STHSW25"><?php echo $TeamLang['TimePCT'];?></th><th class="STHSW25"><?php echo $TeamLang['PHY'];?></th><th class="STHSW25"><?php echo $TeamLang['DF'];?></th><th class="STHSW25"><?php echo $TeamLang['OF'];?></th><th class="STHSW140"><?php echo $TeamLang['Defense'];?></th><th class="STHSW140"><?php echo $TeamLang['Defense'];?></th><th class="STHSW25"><?php echo $TeamLang['TimePCT'];?></th><th class="STHSW25"><?php echo $TeamLang['PHY'];?></th><th class="STHSW25"><?php echo $TeamLang['DF'];?></th><th class="STHSW25"><?php echo $TeamLang['OF'];?></th></tr>
<?php if ($TeamLines != Null){
echo "<tr><td>1</td>";
echo "<td>" . $TeamLines['Line1PK3ForwardCenter'] . "</td>";
echo "<td>" . $TeamLines['Line1PK3ForwardTime'] . "</td>";
echo "<td>" . $TeamLines['Line1PK3ForwardPhy'] . "</td>";
echo "<td>" . $TeamLines['Line1PK3ForwardDF'] . "</td>";
echo "<td>" . $TeamLines['Line1PK3ForwardOF'] . "</td>";
echo "<td>" . $TeamLines['Line1PK3DefenseDefense1'] . "</td>";
echo "<td>" . $TeamLines['Line1PK3DefenseDefense2'] . "</td>";
echo "<td>" . $TeamLines['Line1PK3DefenseTime'] . "</td>";
echo "<td>" . $TeamLines['Line1PK3DefensePhy'] . "</td>";
echo "<td>" . $TeamLines['Line1PK3DefenseDF'] . "</td>";
echo "<td>" . $TeamLines['Line1PK3DefenseOF'] . "</td>";
echo "</tr>\n<tr><td>2</td>";
echo "<td>" . $TeamLines['Line2PK3ForwardCenter'] . "</td>";
echo "<td>" . $TeamLines['Line2PK3ForwardTime'] . "</td>";
echo "<td>" . $TeamLines['Line2PK3ForwardPhy'] . "</td>";
echo "<td>" . $TeamLines['Line2PK3ForwardDF'] . "</td>";
echo "<td>" . $TeamLines['Line2PK3ForwardOF'] . "</td>";
echo "<td>" . $TeamLines['Line2PK3DefenseDefense1'] . "</td>";
echo "<td>" . $TeamLines['Line2PK3DefenseDefense2'] . "</td>";
echo "<td>" . $TeamLines['Line2PK3DefenseTime'] . "</td>";
echo "<td>" . $TeamLines['Line2PK3DefensePhy'] . "</td>";
echo "<td>" . $TeamLines['Line2PK3DefenseDF'] . "</td>";
echo "<td>" . $TeamLines['Line2PK3DefenseOF'] . "</td>";
echo "</tr>";
}?></table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPTeamStat_Table"><tr><th colspan="7"><?php echo $TeamLang['4vs4Forward'];?></th></tr><tr>
<th class="STHSW25"><?php echo $TeamLang['LineNumber'];?></th><th class="STHSW140"><?php echo $TeamLang['Center'];?></th><th class="STHSW140"><?php echo $TeamLang['Wing'];?></th><th class="STHSW25"><?php echo $TeamLang['TimePCT'];?></th><th class="STHSW25"><?php echo $TeamLang['PHY'];?></th><th class="STHSW25"><?php echo $TeamLang['DF'];?></th><th class="STHSW25"><?php echo $TeamLang['OF'];?></th></tr>
<?php if ($TeamLines != Null){
echo "<tr><td>1</td>";
echo "<td>" . $TeamLines['Line14VS4ForwardCenter'] . "</td>";
echo "<td>" . $TeamLines['Line14VS4ForwardWing'] . "</td>";
echo "<td>" . $TeamLines['Line14VS4ForwardTime'] . "</td>";
echo "<td>" . $TeamLines['Line14VS4ForwardPhy'] . "</td>";	
echo "<td>" . $TeamLines['Line14VS4ForwardDF'] . "</td>";
echo "<td>" . $TeamLines['Line14VS4ForwardOF'] . "</td>";
echo "</tr>\n<tr><td>2</td>";
echo "<td>" . $TeamLines['Line24VS4ForwardCenter'] . "</td>";
echo "<td>" . $TeamLines['Line24VS4ForwardWing'] . "</td>";
echo "<td>" . $TeamLines['Line24VS4ForwardTime'] . "</td>";
echo "<td>" . $TeamLines['Line24VS4ForwardPhy'] . "</td>";	
echo "<td>" . $TeamLines['Line24VS4ForwardDF'] . "</td>";
echo "<td>" . $TeamLines['Line24VS4ForwardOF'] . "</td>";
echo "</tr>";
}?></table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPTeamStat_Table"><tr><th colspan="7"><?php echo $TeamLang['4vs4Defense'];?></th></tr><tr>
<th class="STHSW25"><?php echo $TeamLang['LineNumber'];?></th><th class="STHSW140"><?php echo $TeamLang['Defense'];?></th><th class="STHSW140"><?php echo $TeamLang['Defense'];?></th><th class="STHSW25"><?php echo $TeamLang['TimePCT'];?></th><th class="STHSW25"><?php echo $TeamLang['PHY'];?></th><th class="STHSW25"><?php echo $TeamLang['DF'];?></th><th class="STHSW25"><?php echo $TeamLang['OF'];?></th></tr>
<?php if ($TeamLines != Null){
echo "<tr><td>1</td>";
echo "<td>" . $TeamLines['Line14VS4DefenseDefense1'] . "</td>";
echo "<td>" . $TeamLines['Line14VS4DefenseDefense2'] . "</td>";
echo "<td>" . $TeamLines['Line14VS4DefenseTime'] . "</td>";
echo "<td>" . $TeamLines['Line14VS4DefensePhy'] . "</td>";	
echo "<td>" . $TeamLines['Line14VS4DefenseDF'] . "</td>";
echo "<td>" . $TeamLines['Line14VS4DefenseOF'] . "</td>";
echo "</tr>\n<tr><td>2</td>";
echo "<td>" . $TeamLines['Line24VS4DefenseDefense1'] . "</td>";
echo "<td>" . $TeamLines['Line24VS4DefenseDefense2'] . "</td>";
echo "<td>" . $TeamLines['Line24VS4DefenseTime'] . "</td>";
echo "<td>" . $TeamLines['Line24VS4DefensePhy'] . "</td>";	
echo "<td>" . $TeamLines['Line24VS4DefenseDF'] . "</td>";
echo "<td>" . $TeamLines['Line24VS4DefenseOF'] . "</td>";
echo "</tr>";
}?></table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPTeamStat_Table"><tr><th colspan="5"><?php echo $TeamLang['LastMinutesOffensive'];?></th></tr><tr>
<th class="STHSW140"><?php echo $TeamLang['LeftWing'];?></th><th class="STHSW140"><?php echo $TeamLang['Center'];?></th><th class="STHSW140"><?php echo $TeamLang['RightWing'];?></th><th class="STHSW140"><?php echo $TeamLang['Defense'];?></th><th class="STHSW140"><?php echo $TeamLang['Defense'];?></th></tr>
<?php if ($TeamLines != Null){
echo "<tr>";
echo "<td>" . $TeamLines['LastMinOffForwardLeftWing'] . "</td>";
echo "<td>" . $TeamLines['LastMinOffForwardCenter'] . "</td>";
echo "<td>" . $TeamLines['LastMinOffForwardRightWing'] . "</td>";
echo "<td>" . $TeamLines['LastMinOffDefenseDefense1'] . "</td>";
echo "<td>" . $TeamLines['LastMinOffDefenseDefense2'] . "</td>";
echo "</tr>";
}?></table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPTeamStat_Table"><tr><th colspan="5"><?php echo $TeamLang['LastMinutesDefensive'];?></th></tr><tr>
<th class="STHSW140"><?php echo $TeamLang['LeftWing'];?></th><th class="STHSW140"><?php echo $TeamLang['Center'];?></th><th class="STHSW140"><?php echo $TeamLang['RightWing'];?></th><th class="STHSW140"><?php echo $TeamLang['Defense'];?></th><th class="STHSW140"><?php echo $TeamLang['Defense'];?></th></tr>
<?php if ($TeamLines != Null){
echo "<tr>";
echo "<td>" . $TeamLines['LastMinDefForwardLeftWing'] . "</td>";
echo "<td>" . $TeamLines['LastMinDefForwardCenter'] . "</td>";
echo "<td>" . $TeamLines['LastMinDefForwardRightWing'] . "</td>";
echo "<td>" . $TeamLines['LastMinDefDefenseDefense1'] . "</td>";
echo "<td>" . $TeamLines['LastMinDefDefenseDefense2'] . "</td>";
echo "</tr>";
}?></table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPTeamStat_Table"><tr><th colspan="3"><?php echo $TeamLang['ExtraForwards'];?></th></tr><tr>
<th class="STHSW250"><?php echo $TeamLang['Normal'];?> </th><th class="STHSW250"><?php echo $TeamLang['PowerPlay'];?></th><th class="STHSW250"><?php echo $TeamLang['PenaltyKill'];?></th></tr>
<?php if ($TeamLines != Null){
echo "<tr>";
echo "<td>" . $TeamLines['ExtraForwardN1'] . ", " . $TeamLines['ExtraForwardN2'] . ", " . $TeamLines['ExtraForwardN3'] . "</td>";
echo "<td>" . $TeamLines['ExtraForwardPP1'] . ", " . $TeamLines['ExtraForwardPP2'] . "</td>";
echo "<td>" . $TeamLines['ExtraForwardPK'] . "</td>";
echo "</tr>";
}?></table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPTeamStat_Table"><tr><th colspan="3"><?php echo $TeamLang['ExtraDefensemen'];?> </th></tr><tr>
<th class="STHSW250"><?php echo $TeamLang['Normal'];?> </th><th class="STHSW250"><?php echo $TeamLang['PowerPlay'];?></th><th class="STHSW250"><?php echo $TeamLang['PenaltyKill'];?></th></tr>
<?php if ($TeamLines != Null){
echo "<tr>";
echo "<td>" . $TeamLines['ExtraDefenseN1'] . ", " . $TeamLines['ExtraDefenseN2'] . ", " . $TeamLines['ExtraDefenseN3'] . "</td>";
echo "<td>" . $TeamLines['ExtraDefensePP'] . "</td>";
echo "<td>" . $TeamLines['ExtraDefensePK1']  . ", " . $TeamLines['ExtraDefensePK2'] . "</td>";
echo "</tr>";
}?></table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPTeamStat_Table"><tr><th><?php echo $TeamLang['PenaltyShots'];?></th></tr>
<?php if ($TeamLines != Null){echo "<tr><td>" . $TeamLines['PenaltyShots1'] . ", " . $TeamLines['PenaltyShots2'] . ", " . $TeamLines['PenaltyShots3'] . ", " . $TeamLines['PenaltyShots4'] . ", " . $TeamLines['PenaltyShots5'] . "</td></tr>";}?></table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPTeamStat_Table"><tr><th><?php echo $TeamLang['Goalie'];?></th></tr>
<?php if ($TeamLines != Null){echo "<tr><td>#1 : " . $TeamLines['Goaler1'] . ", #2 : " . $TeamLines['Goaler2']; if($TeamLines['Goaler3'] != ""){echo ", #3 : " . $TeamLines['Goaler3'];} echo "</td></tr>";}?></table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPTeamStat_Table"<?php if($LeagueWebClient != Null){if ($LeagueWebClient['FarmCustomOTLines'] == "False"){echo " style=\"display:none;\"";}} ?>><tr><th><?php echo $TeamLang['CustomOTLinesForwards'];?></th></tr>
<?php if ($TeamLines != Null){echo "<tr><td>" . $TeamLines['OTForward1'] . ", " . $TeamLines['OTForward2'] . ", " . $TeamLines['OTForward3'] . ", " . $TeamLines['OTForward4'] . ", " . $TeamLines['OTForward5'] . ", " . $TeamLines['OTForward6'] . ", " . $TeamLines['OTForward6'] . ", " . $TeamLines['OTForward7'] . ", " . $TeamLines['OTForward8'] . ", " . $TeamLines['OTForward9'] . ", " . $TeamLines['OTForward10'] . "</td></tr>";}?></table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPTeamStat_Table"<?php if($LeagueWebClient != Null){if ($LeagueWebClient['FarmCustomOTLines'] == "False"){echo " style=\"display:none;\"";}} ?>><tr><th><?php echo $TeamLang['CustomOTLinesDefensemen'];?></th></tr>
<?php if ($TeamLines != Null){echo "<tr><td>" . $TeamLines['OTDefense1'] . ", " . $TeamLines['OTDefense2'] . ", " . $TeamLines['OTDefense3'] . ", " . $TeamLines['OTDefense4'] . ", " . $TeamLines['OTDefense5'] . "</td></tr>";}?></table>
<div class="STHSBlankDiv"></div>

<br /><br /></div>
<div class="tabmain<?php if($SubMenu ==5){echo " active";}?>" id="tabmain5">

<div class="tablesorter_ColumnSelectorWrapper">
    <input id="tablesorter_colSelect5" type="checkbox" class="hidden">
    <label class="tablesorter_ColumnSelectorButton" for="tablesorter_colSelect5"><?php echo $TableSorterLang['ShoworHideColumn'];?></label>
    <div id="tablesorter_ColumnSelector5" class="tablesorter_ColumnSelector"></div>
	<?php include "FilterTip.php";?>
</div>

<table class="tablesorter STHSPHPTeamsStatSub_Table"><thead><tr>
<?php include "TeamsStatSub.php";?>
</tbody></table>

<br />
<table class="STHSPHPTeamStat_Table"><tr>
<th colspan="3"></th><th colspan="10"><?php echo $TeamLang['TotalForPlayers'];?></th></tr><tr>
<th class="STHSW25"><?php echo $GeneralStatLang['GamePlayed'];?></th><th class="STHSW25"><?php echo $GeneralStatLang['Points'];?></th><th class="STHSW25"><?php echo $GeneralStatLang['Streak'];?></th><th class="STHSW25"><?php echo $GeneralStatLang['Goals'];?></th><th class="STHSW25"><?php echo $GeneralStatLang['Assists'];?></th><th class="STHSW25"><?php echo $GeneralStatLang['Points'];?></th><th class="STHSW25"><?php echo $GeneralStatLang['ShotsFor'];?></th><th class="STHSW25"><?php echo $GeneralStatLang['ShotsAgainst'];?></th><th class="STHSW25"><?php echo $GeneralStatLang['ShotsBlock'];?></th><th class="STHSW25"><?php echo $GeneralStatLang['PenaltyMinutes'];?></th><th class="STHSW25"><?php echo $GeneralStatLang['Hits'];?></th><th class="STHSW25"><?php echo $GeneralStatLang['EmptyNetGoals'];?></th><th class="STHSW25"><?php echo $GeneralStatLang['Shutouts'];?></th></tr>
<?php if ($TeamStat != Null){
echo "<tr>";
echo "<td>" . $TeamStat['GP']. "</td>";
echo "<td>" . $TeamStat['Points']. "</td>";
echo "<td>" . $TeamStat['Streak']. "</td>";
echo "<td>" . $TeamStat['TotalGoal']. "</td>";
echo "<td>" . $TeamStat['TotalAssist']. "</td>";
echo "<td>" . $TeamStat['TotalPoint']. "</td>";
echo "<td>" . $TeamStat['ShotsFor']. "</td>";
echo "<td>" . $TeamStat['ShotsAga']. "</td>";
echo "<td>" . $TeamStat['ShotsBlock']. "</td>";		
echo "<td>" . $TeamStat['Pim']. "</td>";
echo "<td>" . $TeamStat['Hits']. "</td>";
echo "<td>" . $TeamStat['EmptyNetGoal']. "</td>";
echo "<td>" . $TeamStat['Shutouts']. "</td>";		
echo "</tr>";}?>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPTeamStat_Table"><tr><th colspan="<?php if($LeagueGeneral != Null){if($LeagueGeneral['PointSystemSO'] == "True"){echo "9";}}else{echo "8";}?>"><?php echo $TeamLang['AllGames'];?></th></tr><tr>
<th class="STHSW25">GP</th><th class="STHSW25">W</th><th class="STHSW25">L</th><th class="STHSW25">OTW</th><th class="STHSW25">OTL</th>
<?php if($LeagueGeneral != Null){if($LeagueGeneral['PointSystemSO'] == "True"){	echo "<th class=\"STHSW25\">SOW</th><th class=\"STHSW25\">SOL</th>";}else{	echo "<th class=\"STHSW25\">T</th>";}}?>
<th class="STHSW25">GF</th><th class="STHSW25">GA</th></tr>
<?php if ($TeamStat != Null){
echo "<tr>";
echo "<td>" . $TeamStat['GP']. "</td>";
echo "<td>" . $TeamStat['W']. "</td>";
echo "<td>" . $TeamStat['L']. "</td>";
echo "<td>" . $TeamStat['OTW']. "</td>";
echo "<td>" . $TeamStat['OTL']. "</td>";
if($LeagueGeneral['PointSystemSO'] == "True"){	
echo "<td>" . $TeamStat['SOW'] . "</td>";
echo "<td>" . $TeamStat['SOL'] . "</td>";
}else{	
echo "<td>" . $TeamStat['T'] . "</td>";}
echo "<td>" . $TeamStat['GF']. "</td>";
echo "<td>" . $TeamStat['GA']. "</td>";
echo "</tr>";}?>
</table>
<div class="STHSBlankDiv"></div>	

<table class="STHSPHPTeamStat_Table"><tr><th colspan="<?php if($LeagueGeneral != Null){if($LeagueGeneral['PointSystemSO'] == "True"){echo "9";}}else{echo "8";}?>"><?php echo $TeamLang['HomeGames'];?></th></tr><tr>
<th class="STHSW25">GP</th><th class="STHSW25">W</th><th class="STHSW25">L</th><th class="STHSW25">OTW</th><th class="STHSW25">OTL</th>
<?php if($LeagueGeneral != Null){if($LeagueGeneral['PointSystemSO'] == "True"){	echo "<th class=\"STHSW25\">SOW</th><th class=\"STHSW25\">SOL</th>";}else{	echo "<th class=\"STHSW25\">T</th>";}}?>
<th class="STHSW25">GF</th><th class="STHSW25">GA</th></tr>
<?php if ($TeamStat != Null){
echo "<tr>";
echo "<td>" . $TeamStat['HomeGP']. "</td>";
echo "<td>" . $TeamStat['HomeW']. "</td>";
echo "<td>" . $TeamStat['HomeL']. "</td>";
echo "<td>" . $TeamStat['HomeOTW']. "</td>";
echo "<td>" . $TeamStat['HomeOTL']. "</td>";
if($LeagueGeneral['PointSystemSO'] == "True"){	
echo "<td>" . $TeamStat['HomeSOW'] . "</td>";
echo "<td>" . $TeamStat['HomeSOL'] . "</td>";
}else{	
echo "<td>" . $TeamStat['HomeT'] . "</td>";}
echo "<td>" . $TeamStat['HomeGF']. "</td>";
echo "<td>" . $TeamStat['HomeGA']. "</td>";
echo "</tr>";}?>
</table>
<div class="STHSBlankDiv"></div>	
	
<table class="STHSPHPTeamStat_Table"><tr><th colspan="<?php if($LeagueGeneral != Null){if($LeagueGeneral['PointSystemSO'] == "True"){echo "9";}}else{echo "8";}?>"><?php echo $TeamLang['VisitorGames'];?></th></tr><tr>
<th class="STHSW25">GP</th><th class="STHSW25">W</th><th class="STHSW25">L</th><th class="STHSW25">OTW</th><th class="STHSW25">OTL</th>
<?php if($LeagueGeneral != Null){if($LeagueGeneral['PointSystemSO'] == "True"){	echo "<th class=\"STHSW25\">SOW</th><th class=\"STHSW25\">SOL</th>";}else{	echo "<th class=\"STHSW25\">T</th>";}}?>
<th class="STHSW25">GF</th><th class="STHSW25">GA</th></tr>
<?php if ($TeamStat != Null){
echo "<tr>";
echo "<td>" . ($TeamStat['GP'] - $TeamStat['HomeGP']). "</td>";
echo "<td>" . ($TeamStat['W'] - $TeamStat['HomeW']). "</td>";
echo "<td>" . ($TeamStat['L'] - $TeamStat['HomeL']). "</td>";
echo "<td>" . ($TeamStat['OTW'] - $TeamStat['HomeOTW']). "</td>";
echo "<td>" . ($TeamStat['OTL'] - $TeamStat['HomeOTL']). "</td>";
if($LeagueGeneral['PointSystemSO'] == "True"){	
echo "<td>" . ($TeamStat['SOW'] - $TeamStat['HomeSOW']) . "</td>";
echo "<td>" . ($TeamStat['SOL'] - $TeamStat['HomeSOL']) . "</td>";
}else{	
echo "<td>" . ($TeamStat['T'] - $TeamStat['HomeT']) . "</td>";}
echo "<td>" . ($TeamStat['GF'] - $TeamStat['HomeGF']). "</td>";
echo "<td>" . ($TeamStat['GA'] - $TeamStat['HomeGA']). "</td>";
echo "</tr>";}?>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPTeamStat_Table"><tr><th colspan="<?php if($LeagueGeneral != Null){if($LeagueGeneral['PointSystemSO'] == "True"){echo "6";}}else{echo "5";}?>"><?php echo $TeamLang['Last10Games'];?>
</th></tr><tr>
<th class="STHSW25">W</th><th class="STHSW25">L</th><th class="STHSW25">OTW</th><th class="STHSW25">OTL</th>
<?php if($LeagueGeneral != Null){if($LeagueGeneral['PointSystemSO'] == "True"){	echo "<th class=\"STHSW25\">SOW</th><th class=\"STHSW25\">SOL</th>";}else{	echo "<th class=\"STHSW25\">T</th>";}}?></tr>
<?php if ($TeamStat != Null){
echo "<tr>";
echo "<td>" . $TeamStat['Last10W']. "</td>";
echo "<td>" . $TeamStat['Last10L']. "</td>";
echo "<td>" . $TeamStat['Last10OTW']. "</td>";
echo "<td>" . $TeamStat['Last10OTL']. "</td>";
if($LeagueGeneral['PointSystemSO'] == "True"){	
echo "<td>" . $TeamStat['Last10SOW'] . "</td>";
echo "<td>" . $TeamStat['Last10SOL'] . "</td>";
}else{	
echo "<td>" . $TeamStat['Last10T'] . "</td>";}
echo "</tr>";}?>
</table>
<div class="STHSBlankDiv"></div>	

<table class="STHSPHPTeamStat_Table"><tr>
<th class="STHSW25"><?php echo $TeamLang['PowerPlayAttemps'];?></th><th class="STHSW25"><?php echo $TeamLang['PowerPlayGoals'];?></th><th class="STHSW25"><?php echo $TeamLang['PowerPlayPCT'];?></th><th class="STHSW25"><?php echo $TeamLang['PenaltyKillAttemps'];?></th><th class="STHSW25"><?php echo $TeamLang['PenaltyKillGoalsAgainst'];?></th><th class="STHSW25"><?php echo $TeamLang['PenaltyKillPCT'];?></th><th class="STHSW25"><?php echo $TeamLang['PenaltyKillPCTGoalsFor'];?></th></tr>
<?php if ($TeamStat != Null){
echo "<tr>";
echo "<td>" . $TeamStat['PPAttemp']. "</td>";
echo "<td>" . $TeamStat['PPGoal']. "</td>";
echo "<td>";if ($TeamStat['PPAttemp'] > 0){echo number_Format($TeamStat['PPGoal'] / $TeamStat['PPAttemp'] * 100,2) . "%";} else { echo "0.00%";} echo "</td>";		
echo "<td>" . $TeamStat['PKAttemp']. "</td>";
echo "<td>" . $TeamStat['PKGoalGA']. "</td>";
echo "<td>";if ($TeamStat['PKAttemp'] > 0){echo number_Format(($TeamStat['PKAttemp'] - $TeamStat['PKGoalGA']) / $TeamStat['PKAttemp'] * 100,2) . "%";} else {echo "0.00%";} echo "</td>";
echo "<td>" .  $TeamStat['PKGoalGF']. "</td>";		
echo "</tr>";}?>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPTeamStat_Table"><tr>
<th class="STHSW25"><?php echo $TeamLang['Shots1Period'];?></th><th class="STHSW25"><?php echo $TeamLang['Shots2Period'];?></th><th class="STHSW25"><?php echo $TeamLang['Shots3Period'];?></th><th class="STHSW25"><?php echo $TeamLang['Shots4Period'];?></th><th class="STHSW25"><?php echo $TeamLang['Goals1Period'];?></th><th class="STHSW25"><?php echo $TeamLang['Goals2Period'];?></th><th class="STHSW25"><?php echo $TeamLang['Goals3Period'];?></th><th class="STHSW25"><?php echo $TeamLang['Goals4Period'];?>
<?php if ($TeamStat != Null){
echo "<tr>";
echo "<td>" . $TeamStat['ShotsPerPeriod1']. "</td>";
echo "<td>" . $TeamStat['ShotsPerPeriod2']. "</td>";
echo "<td>" . $TeamStat['ShotsPerPeriod3']. "</td>";
echo "<td>" . $TeamStat['ShotsPerPeriod4']. "</td>";
echo "<td>" . $TeamStat['GoalsPerPeriod1']. "</td>";		
echo "<td>" . $TeamStat['GoalsPerPeriod2']. "</td>";	
echo "<td>" . $TeamStat['GoalsPerPeriod3']. "</td>";	
echo "<td>" . $TeamStat['GoalsPerPeriod4']. "</td>";	
echo "</tr>";}?>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPTeamStat_Table"><tr>
<th colspan="9"><?php echo $TeamLang['FaceOffs'];?></th></tr><tr>
<th class="STHSW25"><?php echo $TeamLang['WonOffensifZone'];?></th><th class="STHSW25"><?php echo $TeamLang['TotalOffensif'];?></th><th class="STHSW25"><?php echo $TeamLang['WonOffensifPCT'];?></th><th class="STHSW25"><?php echo $TeamLang['WonDefensifZone'];?></th><th class="STHSW25"><?php echo $TeamLang['TotalDefensif'];?></th><th class="STHSW25"><?php echo $TeamLang['WonDefensifPCT'];?></th><th class="STHSW25"><?php echo $TeamLang['WonNeutralZone'];?></th><th class="STHSW25"><?php echo $TeamLang['TotalNeutral'];?></th><th class="STHSW25"><?php echo $TeamLang['WonNeutralPCT'];?></th></tr>
<?php if ($TeamStat != Null){
echo "<tr>";
echo "<td>" . $TeamStat['FaceOffWonOffensifZone']. "</td>";
echo "<td>" . $TeamStat['FaceOffTotalOffensifZone']. "</td>";		
echo "<td>";if ($TeamStat['FaceOffTotalOffensifZone'] > 0){echo number_Format($TeamStat['FaceOffWonOffensifZone'] / $TeamStat['FaceOffTotalOffensifZone'] * 100,2) . "%" ;} else { echo "0.00%";} echo "</td>";	
echo "<td>" . $TeamStat['FaceOffWonDefensifZone']. "</td>";
echo "<td>" . $TeamStat['FaceOffTotalDefensifZone']. "</td>";
echo "<td>";if ($TeamStat['FaceOffTotalDefensifZone'] > 0){echo number_Format($TeamStat['FaceOffWonDefensifZone'] / $TeamStat['FaceOffTotalDefensifZone'] * 100,2) . "%" ;} else { echo "0.00%";} echo "</td>";	
echo "<td>" . $TeamStat['FaceOffWonNeutralZone']. "</td>";	
echo "<td>" . $TeamStat['FaceOffTotalNeutralZone']. "</td>";	
echo "<td>";if ($TeamStat['FaceOffTotalNeutralZone'] > 0){echo number_Format($TeamStat['FaceOffWonNeutralZone'] / $TeamStat['FaceOffTotalNeutralZone'] * 100,2) . "%" ;} else { echo "0.00%";} echo "</td>";	
echo "</tr>";}?>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPTeamStat_Table"><tr>
<th colspan="6"><?php echo $TeamLang['PuckTime'];?></th></tr><tr>
<th class="STHSW25"><?php echo $TeamLang['InOffensifZone'];?></th><th class="STHSW25"><?php echo $TeamLang['ControlInOffensifZone'];?></th><th class="STHSW25"><?php echo $TeamLang['InDefensifZone'];?></th><th class="STHSW25"><?php echo $TeamLang['ControlInDefensifZone'];?></th><th class="STHSW25"><?php echo $TeamLang['InNeutralZone'];?></th><th class="STHSW25"><?php echo $TeamLang['ControlInNeutralZone'];?></th>
</tr>
<?php if ($TeamStat != Null){
echo "<tr>";
echo "<td>" . Floor($TeamStat['PuckTimeInZoneOF']/60). "</td>";
echo "<td>" . Floor($TeamStat['PuckTimeControlinZoneOF']/60). "</td>";
echo "<td>" . Floor($TeamStat['PuckTimeInZoneDF']/60). "</td>";
echo "<td>" . Floor($TeamStat['PuckTimeControlinZoneDF']/60). "</td>";
echo "<td>" . Floor($TeamStat['PuckTimeInZoneNT']/60). "</td>";		
echo "<td>" . Floor($TeamStat['PuckTimeControlinZoneNT']/60). "</td>";	
echo "</tr>";}?>
</table>

<br /><br /></div>
<div class="tabmain<?php if($SubMenu ==6){echo " active";}?>" id="tabmain6">

<div class="tablesorter_ColumnSelectorWrapper">
	<input id="tablesorter_colSelect6" type="checkbox" class="hidden">
    <div id="tablesorter_ColumnSelector" class="tablesorter_ColumnSelector6"></div>
	<a href="#Last_Simulate_Day" style="background: #99bfe6;  border: #888 1px solid;  color: #111;  border-radius: 5px;  padding: 5px; text-decoration: none"><?php echo $ScheduleLang['LastPlayedGames'];?></a>
    <label class="tablesorter_ColumnSelectorButton" for="tablesorter_colSelect6"><?php echo $TableSorterLang['ShoworHideColumn'];?></label>
    <div id="tablesorter_ColumnSelector6" class="tablesorter_ColumnSelector"></div>	
	<?php include "FilterTip.php";?>
</div>

<table class="tablesorter STHSPHPTeam_ScheduleTable"><thead><tr>
<?php include "ScheduleSub.php";?>
</tbody></table>

<br /><br /></div>
<div class="tabmain<?php if($SubMenu ==7){echo " active";}?>" id="tabmain7">
<br />
<table class="STHSPHPTeamStat_Table"><tr><th colspan="3"><?php echo $TeamLang['ArenaCapacityTicketPriceAttendance'];?></th></tr><tr><th class="STHSW200"></th><th class="STHSW100"><?php echo $TeamLang['Level'];?> 1</th><th class="STHSW100"><?php echo $TeamLang['Level'];?> 2</th></tr>
<?php 
If ($TeamFinance != Null){
echo "<tr><th>" . $TeamLang['ArenaCapacity'] . "</th><td>" . $TeamFinance['ArenaCapacityL1'] . "</td><td>" . $TeamFinance['ArenaCapacityL2'] . "</td></tr>\n";
echo "<tr><th>" . $TeamLang['TicketPrice'] . "</th><td>" . $TeamFinance['TicketPriceL1'] . "</td><td>" . $TeamFinance['TicketPriceL2'] . "</td></tr>\n";
if ($TeamStat['HomeGP'] > 0){echo "<tr><th>" . $TeamLang['Attendance'] . "</th><td>" . number_Format($TeamFinance['AttendanceL1'],0) . "</td><td>" . number_Format($TeamFinance['AttendanceL2'],0) . "</td></tr>\n";
}else{echo "<tr><th>" . $TeamLang['Attendance'] . "</th><td>0.00%</td><td>0.00%</td></tr>\n";}
echo "<tr><th>" . $TeamLang['AttendancePCT'] . "</th>";
echo "<td>";if ($TeamFinance['ArenaCapacityL1'] > 0 AND $TeamStat['HomeGP'] > 0){echo number_format(($TeamFinance['AttendanceL1'] / ($TeamFinance['ArenaCapacityL1'] * $TeamStat['HomeGP'])) *100 ,2) . "%";} else { echo "0.00%";} echo "</td>";	
echo "<td>";if ($TeamFinance['ArenaCapacityL2'] > 0 AND $TeamStat['HomeGP'] > 0){echo number_format(($TeamFinance['AttendanceL2'] / ($TeamFinance['ArenaCapacityL2'] * $TeamStat['HomeGP'])) *100 ,2) . "%";} else { echo "0.00%";} echo "</td></tr>";	
}?>
</table>

<br />
<table class="STHSPHPTeamStat_Table"><tr><th colspan="6"><?php echo $TeamLang['Income'];?>
</th></tr><tr><th class="STHSW140"><?php echo $TeamLang['HomeGamesLeft'];?></th><th class="STHSW140"><?php echo $TeamLang['AverageAttendancePCT'];?></th><th class="STHSW140"><?php echo $TeamLang['AverageIncomeperGame'];?></th><th class="STHSW140"><?php echo $TeamLang['YeartoDateRevenue'];?></th><th class="STHSW140"><?php echo $TeamLang['ArenaCapacity'];?></th><th class="STHSW140"><?php echo $TeamLang['TeamPopularity'];?>
</th></tr>
<?php 
If ($TeamFinance != Null){
$TotalArenaCapacity = ($TeamFinance['ArenaCapacityL1'] + $TeamFinance['ArenaCapacityL2']);
If ($TeamFinance['ScheduleHomeGameInAYear'] > 0){echo "<tr><td>" . ($TeamFinance['ScheduleHomeGameInAYear'] - $TeamStat['HomeGP'] ). "</td>\n";}else{echo "<td>" . (($TeamFinance['ScheduleGameInAYear'] / 2) - $TeamStat['HomeGP'])  . "</td>\n";}
if ($TeamStat['HomeGP'] > 0){echo "<td>" . Round($TeamFinance['TotalAttendance'] / $TeamStat['HomeGP']) . " - ";echo number_Format(($TeamFinance['TotalAttendance'] / ($TotalArenaCapacity * $TeamStat['HomeGP'])) *100,2) . "%</td>\n";
}else{echo "<td>0 - 0.00%</td>";}
if ($TeamStat['HomeGP'] > 0){echo "<td>" . number_format($TeamFinance['TotalIncome'] / $TeamStat['HomeGP'],0) . "$</td>";}else{echo "<td>0$</td>";}
echo "<td>" . number_format($TeamFinance['TotalIncome'],0) . "$</td>";
echo "<td>" . $TotalArenaCapacity . "</td>";
echo "<td>" . $TeamFinance['TeamPopularity'] . "</td></tr>";
}?>
</table>

<br />
<table class="STHSPHPTeamStat_Table"><tr><th colspan="4"><?php echo $TeamLang['Expenses'];?></th></tr><tr><th class="STHSW140"><?php echo $TeamLang['YearToDateExpenses'];?></th><th class="STHSW140"><?php echo $TeamLang['PlayersTotalSalaries'];?></th><th class="STHSW140"><?php echo $TeamLang['PlayersTotalAverageSalaries'];?></th><th class="STHSW140"><?php echo $TeamLang['CoachesSalaries'];?></th></tr>
<?php 
If ($TeamFinance != Null){
echo "<tr><td>" . number_Format(($TeamFinance['ExpenseThisSeason']),0) . "$</td>\n";
echo "<td>" . number_Format($TeamFinance['TotalPlayersSalaries'],0) . "$</td>\n";
echo "<td>" . number_Format($TeamFinance['TotalPlayersSalariesAverage'],0) . "$</td>\n";
echo "<td>";If (Count($CoachInfo) == 1){echo number_Format($CoachInfo['Salary'],0) . "$";};echo "0$</td></tr>\n";
}?>
</table>
<table class="STHSPHPTeamStat_Table"><tr><th class="STHSW140"><?php echo $TeamLang['SalaryCapPerDays'];?></th><th class="STHSW140"><?php echo $TeamLang['SalaryCapToDate'];?></th><th class="STHSW140"><?php echo $TeamLang['PlayerInSalaryCap'];?></th><th class="STHSW140"><?php echo $TeamLang['PlayerOutofSalaryCap'];?></th></tr>
<?php 
If ($TeamFinance != Null){
echo "<tr><td>" . number_Format($TeamFinance['SalaryCapPerDay'],0) . "$</td>\n";
echo "<td>" . number_Format($TeamFinance['SalaryCapToDate'],0) . "$</td>\n";
echo "<td>" . $TeamFinance['PlayerInSalaryCap'] . "</td>\n";
echo "<td>" . $TeamFinance['PlayerOutofSalaryCap'] . "</td></tr>\n";
}?>
</table>
<br />

<table class="STHSPHPTeamStat_Table"><tr><th colspan="4"><?php echo $TeamLang['Estimate'];?></th></tr><tr><th class="STHSW140"><?php echo $TeamLang['EstimatedSeasonRevenue'];?></th><th class="STHSW140"><?php echo $TeamLang['RemainingSeasonDays'];?>
</th><th class="STHSW140"><?php echo $TeamLang['ExpensesPerDays'];?></th><th class="STHSW140"><?php echo $TeamLang['EstimatedSeasonExpenses'];?></th></tr>
<?php 
If ($TeamFinance != Null){
	echo "<tr><td>" . number_Format($TeamFinance['EstimatedRevenue'],0) . "$</td>\n";
	$Remaining = ($LeagueGeneral['FarmScheduleTotalDay'] - $LeagueGeneral['ScheduleNextDay'] + 1);
	echo "<td>";if($Remaining > 0){echo $Remaining;}else{echo "0";}echo "</td>\n";
	echo "<td>" . number_Format($TeamFinance['ExpensePerDay'],0) . "$</td>\n";
	echo "<td>" . number_Format($TeamFinance['EstimatedSeasonExpense'],0) . "$</td></tr>\n";
}?>
</table>
<br /><br /><br />
</div>

<div class="tabmain<?php if($SubMenu ==8){echo " active";}?>" id="tabmain8">
<br />

<h1><?php echo $TeamName . $TeamLang['CareerPlayerLeaderSeason'];?></h1>
<div class="tablesorter_ColumnSelectorWrapper">
    <input id="tablesorter_colSelect11SeasonP" type="checkbox" class="hidden">
    <label class="tablesorter_ColumnSelectorButton" for="tablesorter_colSelect11SeasonP"><?php echo $TableSorterLang['ShoworHideColumn'];?></label>
    <div id="tablesorter_ColumnSelector11SeasonP" class="tablesorter_ColumnSelector"></div>
</div>

<table class="tablesorter STHSPHPTeam_TeamCareerPlayersSeasonTop5"><thead><tr>
<th data-priority="3" title="Order Number" class="STHSW10 sorter-false">#</th>
<th data-priority="critical" title="Player Name" class="STHSW140Min"><?php If (isset($PlayersLang) == True){echo $PlayersLang['PlayerName'];}?></th>
<th data-priority="1" title="Games Played" class="STHSW25">GP</th>
<th data-priority="1" title="Goals" class="STHSW25">G</th>
<th data-priority="1" title="Assists" class="STHSW25">A</th>
<th data-priority="1" title="Points" class="STHSW25">P</th>
<th data-priority="2" title="Plus/Minus" class="STHSW25">+/-</th>
<th data-priority="2" title="Penalty Minutes" class="STHSW25">PIM</th>
<th data-priority="2" title="Hits" class="STHSW25">HIT</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Hits Received">HTT</th>
<th data-priority="2" title="Shots" class="STHSW25">SHT</th>
<th data-priority="3" title="Shooting Percentage" class="STHSW55">SHT%</th>
<th data-priority="3" title="Shots Blocked" class="STHSW25">SB</th>
<th data-priority="3" title="Minutes Played" class="STHSW35">MP</th>
<th data-priority="3" title="Average Minutes Played per Game" class="STHSW35">AMG</th>
<th data-priority="4" title="Power Play Goals" class="STHSW25">PPG</th>
<th data-priority="4" title="Power Play Assists" class="STHSW25">PPA</th>
<th data-priority="4" title="Power Play Points" class="STHSW25">PPP</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Power Play Shots">PPS</th>
<th data-priority="5" title="Short Handed Goals" class="STHSW25">PKG</th>
<th data-priority="5" title="Short Handed Assists" class="STHSW25">PKA</th>
<th data-priority="5" title="Short Handed Points" class="STHSW25">PKP</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Penalty Kill Shots">PKS</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Game Winning Goals">GW</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Game Tying Goals">GT</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Face offs Percentage">FO%</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Hat Tricks">HT</th>
<th data-priority="4" title="Points per 20 Minutes" class="STHSW25">P/20</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Penalty Shots Goals">PSG</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Penalty Shots Taken">PSS</th>
</tr></thead><tbody>
<?php 
$Order = 0;
if (empty($TeamCareerPlayersSeasonTop5 ) == false){while ($Row = $TeamCareerPlayersSeasonTop5 ->fetchArray()) {
	$Order +=1;
	echo "<tr><td>" . $Order ."</td>";
	If ($Row['Number'] != Null){
		echo "<td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . "</a></td>";
	}else{
		echo "<td>" . $Row['Name'] . "</td>";
	}	

	echo "<td>" . $Row['GP'] . "</td>";
	echo "<td>" . $Row['G'] . "</td>";
	echo "<td>" . $Row['A'] . "</td>";
	echo "<td>" . $Row['P'] . "</td>";
	echo "<td>" . $Row['PlusMinus'] . "</td>";
	echo "<td>" . $Row['Pim'] . "</td>";
	echo "<td>" . $Row['Hits'] . "</td>";	
	echo "<td>" . $Row['HitsTook'] . "</td>";		
	echo "<td>" . $Row['Shots'] . "</td>";
	echo "<td>" . number_Format($Row['ShotsPCT'],2) . "%</td>";		
	echo "<td>" . $Row['ShotsBlock'] . "</td>";	
	echo "<td>" . Floor($Row['SecondPlay']/60) . "</td>";
	echo "<td>" . number_Format($Row['AMG'],2) . "</td>";		
	echo "<td>" . $Row['PPG'] . "</td>";
	echo "<td>" . $Row['PPA'] . "</td>";
	echo "<td>" . $Row['PPP'] . "</td>";
	echo "<td>" . $Row['PPShots'] . "</td>";
	echo "<td>" . $Row['PKG'] . "</td>";
	echo "<td>" . $Row['PKA'] . "</td>";
	echo "<td>" . $Row['PKP'] . "</td>";
	echo "<td>" . $Row['PKShots'] . "</td>";
	echo "<td>" . $Row['GW'] . "</td>";
	echo "<td>" . $Row['GT'] . "</td>";
	echo "<td>" . number_Format($Row['FaceoffPCT'],2) . "%</td>";	
	echo "<td>" . $Row['HatTrick'] . "</td>";	
	echo "<td>" . number_Format($Row['P20'],2) . "</td>";			
	echo "<td>" . $Row['PenalityShotsScore'] . "</td>";
	echo "<td>" . $Row['PenalityShotsTotal'] . "</td>";
	echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
}}
?>
</tbody></table>

<br /><h1><?php echo $TeamName . $TeamLang['CareerGoaliesLeaderSeason'];?></h1>
<div class="tablesorter_ColumnSelectorWrapper">
    <input id="tablesorter_colSelect11SeasonG" type="checkbox" class="hidden">
    <label class="tablesorter_ColumnSelectorButton" for="tablesorter_colSelect11SeasonG"><?php echo $TableSorterLang['ShoworHideColumn'];?></label>
    <div id="tablesorter_ColumnSelector11SeasonG" class="tablesorter_ColumnSelector"></div>
</div>

<table class="tablesorter STHSPHPTeam_TeamCareerGoaliesSeasonTop5"><thead><tr>
<th data-priority="3" title="Order Number" class="STHSW10 sorter-false">#</th>
<th data-priority="critical" title="Goalie Name" class="STHSW140Min"><?php If (isset($PlayersLang) == True){echo $PlayersLang['GoalieName'];}?></th>
<th data-priority="1" title="Games Played" class="STHSW25">GP</th>
<th data-priority="1" title="Wins" class="STHSW25">W</th>
<th data-priority="2" title="Losses" class="STHSW25">L</th>
<th data-priority="2" title="Overtime Losses" class="STHSW25">OTL</th>
<th data-priority="critical" title="Save Percentage" class="STHSW50">PCT</th>
<th data-priority="critical" title="Goals Against Average" class="STHSW50">GAA</th>
<th data-priority="3" title="Minutes Played" class="STHSW50">MP</th>
<th data-priority="5" title="Penalty Minutes" class="STHSW25">PIM</th>
<th data-priority="4" title="Shutouts" class="STHSW25">SO</th>
<th data-priority="3" title="Goals Against" class="STHSW25">GA</th>
<th data-priority="3" title="Shots Against" class="STHSW45">SA</th>
<th data-priority="4" title="Shots Against Rebound" class="STHSW45">SAR</th>
<th class="columnSelector-false STHSW25" data-priority="6"  title="Assists">A</th>
<th class="columnSelector-false STHSW25" data-priority="6"  title="Empty net Goals">EG</th>
<th data-priority="4" title="Penalty Shots Save %" class="STHSW50">PS %</th>
<th data-priority="5" title="Penalty Shots Against" class="STHSW25">PSA</th>
</tr></thead><tbody>
<?php
$Order = 0;
if (empty($TeamCareerGoaliesSeasonTop5) == false){while ($Row = $TeamCareerGoaliesSeasonTop5 ->fetchArray()) {
	$Order +=1;
	echo "<tr><td>" . $Order ."</td>";
	If ($Row['Number'] != Null){
		echo "<td><a href=\"GoalieReport.php?Goalie=" . $Row['Number'] . "\">" . $Row['Name'] . "</a></td>";
	}else{
		echo "<td>" . $Row['Name'] . "</td>";
	}
	echo "<td>" . $Row['GP'] . "</td>";
	echo "<td>" . $Row['W'] . "</td>";
	echo "<td>" . $Row['L'] . "</td>";
	echo "<td>" . $Row['OTL'] . "</td>";
	echo "<td>" . number_Format($Row['PCT'],3) . "</td>";
	echo "<td>" . number_Format($Row['GAA'],2) . "</td>";
	echo "<td>";if ($Row <> Null){echo Floor($Row['SecondPlay']/60);}; echo "</td>";
	echo "<td>" . $Row['Pim'] . "</td>";
	echo "<td>" . $Row['Shootout'] . "</td>";
	echo "<td>" . $Row['GA'] . "</td>";
	echo "<td>" . $Row['SA'] . "</td>";
	echo "<td>" . $Row['SARebound'] . "</td>";
	echo "<td>" . $Row['A'] . "</td>";
	echo "<td>" . $Row['EmptyNetGoal'] . "</td>";			
	echo "<td>" . number_Format($Row['PenalityShotsPCT'],3) . "</td>";
	echo "<td>" . $Row['PenalityShotsShots'] . "</td>";
	echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
}}
?>
</tbody></table>

<br /><h1><?php echo $TeamName . $TeamLang['CareerTeamStats'];?></h1>
<div class="tablesorter_ColumnSelectorWrapper">
    <input id="tablesorter_colSelect11" type="checkbox" class="hidden">
    <label class="tablesorter_ColumnSelectorButton" for="tablesorter_colSelect11"><?php echo $TableSorterLang['ShoworHideColumn'];?></label>
    <div id="tablesorter_ColumnSelector11" class="tablesorter_ColumnSelector"></div>
</div>

<table class="tablesorter STHSPHPTeam_TeamCareerStat"><thead><tr>
<th class="sorter-false"></th><th class="sorter-false" colspan="11"><?php echo $TeamStatLang['Overall'];?></th><th class="sorter-false" colspan="11"><?php echo $TeamStatLang['Home'];?></th><th class="sorter-false" colspan="11"><?php echo $TeamStatLang['Visitor'];?></th><th class="sorter-false" colspan="41"></th></tr><tr>
<th data-priority="critical" title="Year" class="STHSW55"><?php echo $TeamLang['Year'];?></th>
<th data-priority="1" title="Overall Games Played" class="STHSW25">GP</th>
<th data-priority="1" title="Overall Wins" class="STHSW25">W</th>
<th data-priority="1" title="Overall Loss" class="STHSW25">L</th>
<th data-priority="6" title="Overall Ties" class="columnSelector-false STHSW35">T</th>
<th data-priority="1" title="Overall Overtime Wins" class="STHSW25">OTW</th>
<th data-priority="1" title="Overall Overtime Loss" class="STHSW25">OTL</th>
<th data-priority="1" title="Overall Shootout Wins" class="STHSW25">SOW</th>
<th data-priority="1" title="Overall Shootout Loss" class="STHSW25">SOL</th>
<th data-priority="1" title="Overall Goals For" class="STHSW25">GF</th>
<th data-priority="1" title="Overall Goals Against" class="STHSW25">GA</th>
<th data-priority="1" title="Overall Goals For Diffirencial against Goals Against" class="STHSW25">Diff</th>
<th data-priority="3" title="Home Games Played" class="columnSelector-false STHSW25">GP</th>
<th data-priority="3" title="Home Wins" class="columnSelector-false STHSW25">W</th>
<th data-priority="3" title="Home Loss" class="columnSelector-false STHSW25">L</th>
<th data-priority="6" title="Home Ties" class="columnSelector-false STHSW35">T</th>
<th data-priority="3" title="Home Overtime Wins" class="columnSelector-false STHSW25">OTW</th>
<th data-priority="3" title="Home Overtime Loss" class="columnSelector-false STHSW25">OTL</th>
<th data-priority="3" title="Home Shootout Wins" class="columnSelector-false STHSW25">SOW</th>
<th data-priority="3" title="Home Shootout Loss" class="columnSelector-false STHSW25">SOL</th>
<th data-priority="3" title="Home Goals For" class="columnSelector-false STHSW25">GF</th>
<th data-priority="3" title="Home Goals Against" class="columnSelector-false STHSW25">GA</th>
<th data-priority="3" title="Home Goals For Diffirencial against Goals Against" class="columnSelector-false STHSW25">Diff</th>
<th data-priority="5" title="Visitor Games Played" class="columnSelector-false STHSW25">GP</th>
<th data-priority="5" title="Visitor Wins" class="columnSelector-false STHSW25">W</th>
<th data-priority="5" title="Visitor Loss" class="columnSelector-false STHSW25">L</th>
<th data-priority="6" title="Visitor Ties" class="columnSelector-false STHSW35">T</th>
<th data-priority="5" title="Visitor Overtime Wins" class="columnSelector-false STHSW25">OTW</th>
<th data-priority="5" title="Visitor Overtime Loss" class="columnSelector-false STHSW25">OTL</th>
<th data-priority="5" title="Visitor Shootout Wins" class="columnSelector-false STHSW25">SOW</th>
<th data-priority="5" title="Visitor Shootout Loss" class="columnSelector-false STHSW25">SOL</th>
<th data-priority="5" title="Visitor Goals For" class="columnSelector-false STHSW25">GF</th>
<th data-priority="5" title="Visitor Goals Against" class="columnSelector-false STHSW25">GA</th>
<th data-priority="5" title="Visitor Goals For Diffirencial against Goals Against" class="columnSelector-false STHSW25">Diff</th>
<th data-priority="1" title="Points" class="STHSW25">P</th>
<th data-priority="4" title="Total Team Goals" class="STHSW25">G</th>
<th data-priority="4" title="Total Team Assists" class="STHSW25">A</th>
<th data-priority="6" title="Total Team Players Points" class="columnSelector-false STHSW25">TP</th>
<th data-priority="4" title="Shutouts" class="columnSelector-false STHSW25">SO</th>
<th data-priority="4" title="Empty Net Goals" class="columnSelector-false STHSW25">EG</th>
<th data-priority="6" title="Goals for 1st Period" class="columnSelector-false STHSW25">GP1</th>
<th data-priority="6" title="Goals for 2nd Period" class="columnSelector-false STHSW25">GP2</th>
<th data-priority="6" title="Goals for 3rd Period" class="columnSelector-false STHSW25">GP3</th>
<th data-priority="6" title="Goals for 4th Period" class="columnSelector-false STHSW25">GP4</th>
<th data-priority="2" title="Shots For" class="STHSW25">SHF</th>
<th data-priority="6" title="Shots for 1st Period" class="columnSelector-false STHSW25">SH1</th>
<th data-priority="6" title="Shots for 2nd Period" class="columnSelector-false STHSW25">SP2</th>
<th data-priority="6" title="Shots for 3rd Period" class="columnSelector-false STHSW25">SP3</th>
<th data-priority="6" title="Goals for 4th Period" class="columnSelector-false STHSW25">SP4</th>
<th data-priority="2" title="Shots Against" class="STHSW25">SHA</th>
<th data-priority="2" title="Shots Block" class="STHSW25">SHB</th>
<th data-priority="3" title="Penalty Minutes" class="STHSW25">Pim</th>
<th data-priority="3" title="Hits" class="STHSW25">Hit</th>
<th data-priority="6" title="Power Play Attemps" class="columnSelector-false STHSW25">PPA</th>
<th data-priority="5" title="Power Play Goals" class="STHSW25">PPG</th>
<th data-priority="4" title="Power Play %" class="STHSW35">PP%</th>
<th data-priority="6" title="Penalty Kill Attemps" class="columnSelector-false STHSW25">PKA</th>
<th data-priority="5" title="Penalty Kill Goals Against" class="STHSW25">PK GA</th>
<th data-priority="4" title="Penalty Kill %" class="STHSW35">PK%</th>
<th data-priority="6" title="Penalty Kill Goals For" class="columnSelector-false STHSW25">PK GF</th>
<th data-priority="6" title="Won Offensif Zone Faceoff" class="columnSelector-false STHSW35">W OF FO</th>
<th data-priority="6" title="Total Offensif Zone Faceoff" class="columnSelector-false STHSW35">T OF FO</th>
<th data-priority="6" title="Offensif Zone Faceoff %" class="columnSelector-false STHSW35">OF FO%</th>
<th data-priority="6" title="Won Defensif Zone Faceoff" class="columnSelector-false STHSW35">W DF FO</th>
<th data-priority="6" title="Total Defensif Zone Faceoff" class="columnSelector-false STHSW35">T DF FO</th>
<th data-priority="6" title="Defensif Zone Faceoff %" class="columnSelector-false STHSW35">DF FO%</th>
<th data-priority="6" title="Won Neutral Zone Faceoff" class="columnSelector-false STHSW35">W NT FO</th>
<th data-priority="6" title="Total Neutral Zone Faceoff" class="columnSelector-false STHSW35">T NT FO</th>
<th data-priority="6" title="Neutral Zone Faceoff %" class="columnSelector-false STHSW35">NT FO%</th>
<th data-priority="6" title="Puck Time In Offensif Zone" class="columnSelector-false STHSW25">PZ DF</th>
<th data-priority="6" title="Puck Time Control In Offensif Zone" class="columnSelector-false STHSW25">PZ OF</th>
<th data-priority="6" title="Puck Time In Defensif Zone" class="columnSelector-false STHSW25">PZ NT</th>
<th data-priority="6" title="Puck Time Control In Defensif Zone" class="columnSelector-false STHSW25">PC DF</th>
<th data-priority="6" title="Puck Time In Neutral Zone" class="columnSelector-false STHSW25">PC OF</th>
<th data-priority="6" title="Puck Time Control In Neutral Zone" class="columnSelector-false STHSW25">PC NT</th>
</tr></thead><tbody>
<?php
if ($TeamCareerSumSeasonOnly != Null){
if ($TeamCareerSumSeasonOnly['SumOfGP'] > 0){echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"75\"><strong>" . $PlayersLang['RegularSeason'] . "</strong></td></tr>\n";}
if (empty($TeamCareerSeason) == false){while ($row = $TeamCareerSeason ->fetchArray()) {
	/* Loop Team Career Season */
	echo "<tr><td>" . $row['Year'] . "</td>";
	echo "<td>" . $row['GP'] . "</td>";
	echo "<td>" . $row['W']  . "</td>";
	echo "<td>" . $row['L'] . "</td>";
	echo "<td>" . $row['T'] . "</td>";
	echo "<td>" . $row['OTW'] . "</td>";	
	echo "<td>" . $row['OTL'] . "</td>";	
	echo "<td>" . $row['SOW'] . "</td>";	
	echo "<td>" . $row['SOL'] . "</td>";	
	echo "<td>" . $row['GF'] . "</td>";
	echo "<td>" . $row['GA'] . "</td>";
	echo "<td>" . ($row['GF'] - $row['GA']) . "</td>";	
	echo "<td>" . $row['HomeGP'] . "</td>";
	echo "<td>" . $row['HomeW']  . "</td>";
	echo "<td>" . $row['HomeL'] . "</td>";
	echo "<td>" . $row['HomeT'] . "</td>";
	echo "<td>" . $row['HomeOTW'] . "</td>";	
	echo "<td>" . $row['HomeOTL'] . "</td>";	
	echo "<td>" . $row['HomeSOW'] . "</td>";	
	echo "<td>" . $row['HomeSOL'] . "</td>";	
	echo "<td>" . $row['HomeGF'] . "</td>";
	echo "<td>" . $row['HomeGA'] . "</td>";	
	echo "<td>" . ($row['HomeGF'] - $row['HomeGA']) . "</td>";	
	echo "<td>" . ($row['GP'] - $row['HomeGP']) . "</td>";
	echo "<td>" . ($row['W'] - $row['HomeW']) . "</td>";
	echo "<td>" . ($row['L'] - $row['HomeL']) . "</td>";
	echo "<td>" . ($row['T'] - $row['HomeT']) . "</td>";	
	echo "<td>" . ($row['OTW'] - $row['HomeOTW']) . "</td>";
	echo "<td>" . ($row['OTL'] - $row['HomeOTL']) . "</td>";
	echo "<td>" . ($row['SOW'] - $row['HomeSOW']) . "</td>";
	echo "<td>" . ($row['SOL'] - $row['HomeSOL']) . "</td>";
	echo "<td>" . ($row['GF'] - $row['HomeGF']) . "</td>";
	echo "<td>" . ($row['GA'] - $row['HomeGA']) . "</td>";
	echo "<td>" . (($row['GF'] - $row['HomeGF']) - ($row['GA'] - $row['HomeGA'])) . "</td>";		
	echo "<td><strong>" . $row['Points'] . "</strong></td>";
	echo "<td>" . $row['TotalGoal'] . "</td>";
	echo "<td>" . $row['TotalAssist'] . "</td>";
	echo "<td>" . $row['TotalPoint'] . "</td>";
	echo "<td>" . $row['EmptyNetGoal']. "</td>";
	echo "<td>" . $row['Shutouts']. "</td>";		
	echo "<td>" . $row['GoalsPerPeriod1']. "</td>";		
	echo "<td>" . $row['GoalsPerPeriod2']. "</td>";	
	echo "<td>" . $row['GoalsPerPeriod3']. "</td>";	
	echo "<td>" . $row['GoalsPerPeriod4']. "</td>";	
	echo "<td>" . $row['ShotsFor']. "</td>";	
	echo "<td>" . $row['ShotsPerPeriod1']. "</td>";
	echo "<td>" . $row['ShotsPerPeriod2']. "</td>";
	echo "<td>" . $row['ShotsPerPeriod3']. "</td>";
	echo "<td>" . $row['ShotsPerPeriod4']. "</td>";
	echo "<td>" . $row['ShotsAga']. "</td>";
	echo "<td>" . $row['ShotsBlock']. "</td>";		
	echo "<td>" . $row['Pim']. "</td>";
	echo "<td>" . $row['Hits']. "</td>";	
	echo "<td>" . $row['PPAttemp']. "</td>";
	echo "<td>" . $row['PPGoal']. "</td>";
	echo "<td>";if ($row['PPAttemp'] > 0){echo number_Format($row['PPGoal'] / $row['PPAttemp'] * 100,2) . "%";} else { echo "0.00%";} echo "</td>";		
	echo "<td>" . $row['PKAttemp']. "</td>";
	echo "<td>" . $row['PKGoalGA']. "</td>";
	echo "<td>";if ($row['PKAttemp'] > 0){echo number_Format(($row['PKAttemp'] - $row['PKGoalGA']) / $row['PKAttemp'] * 100,2) . "%";} else {echo "0.00%";} echo "</td>";
	echo "<td>" .  $row['PKGoalGF']. "</td>";	
	echo "<td>" . $row['FaceOffWonOffensifZone']. "</td>";
	echo "<td>" . $row['FaceOffTotalOffensifZone']. "</td>";		
	echo "<td>";if ($row['FaceOffTotalOffensifZone'] > 0){echo number_Format($row['FaceOffWonOffensifZone'] / $row['FaceOffTotalOffensifZone'] * 100,2) . "%" ;} else { echo "0.00%";} echo "</td>";	
	echo "<td>" . $row['FaceOffWonDefensifZone']. "</td>";
	echo "<td>" . $row['FaceOffTotalDefensifZone']. "</td>";
	echo "<td>";if ($row['FaceOffTotalDefensifZone'] > 0){echo number_Format($row['FaceOffWonDefensifZone'] / $row['FaceOffTotalDefensifZone'] * 100,2) . "%" ;} else { echo "0.00%";} echo "</td>";	
	echo "<td>" . $row['FaceOffWonNeutralZone']. "</td>";	
	echo "<td>" . $row['FaceOffTotalNeutralZone']. "</td>";	
	echo "<td>";if ($row['FaceOffTotalNeutralZone'] > 0){echo number_Format($row['FaceOffWonNeutralZone'] / $row['FaceOffTotalNeutralZone'] * 100,2) . "%" ;} else { echo "0.00%";} echo "</td>";	
	echo "<td>" . Floor($row['PuckTimeInZoneOF']/60). "</td>";
	echo "<td>" . Floor($row['PuckTimeControlinZoneOF']/60). "</td>";
	echo "<td>" . Floor($row['PuckTimeInZoneDF']/60). "</td>";
	echo "<td>" . Floor($row['PuckTimeControlinZoneDF']/60). "</td>";
	echo "<td>" . Floor($row['PuckTimeInZoneNT']/60). "</td>";		
	echo "<td>" . Floor($row['PuckTimeControlinZoneNT']/60). "</td>";		
	echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
}}
if ($TeamStat['GP'] > 0 AND $LeagueGeneral['PreSeasonSchedule'] == "False" AND $LeagueGeneral['PlayOffStarted'] == "False"){
	echo "<tr><td>" . $LeagueGeneral['LeagueYearOutput'] . "</td>";
	echo "<td>" . $TeamStat['GP'] . "</td>";
	echo "<td>" . $TeamStat['W']  . "</td>";
	echo "<td>" . $TeamStat['L'] . "</td>";
	echo "<td>" . $TeamStat['T'] . "</td>";
	echo "<td>" . $TeamStat['OTW'] . "</td>";	
	echo "<td>" . $TeamStat['OTL'] . "</td>";	
	echo "<td>" . $TeamStat['SOW'] . "</td>";	
	echo "<td>" . $TeamStat['SOL'] . "</td>";	
	echo "<td>" . $TeamStat['GF'] . "</td>";
	echo "<td>" . $TeamStat['GA'] . "</td>";
	echo "<td>" . ($TeamStat['GF'] - $TeamStat['GA']) . "</td>";	
	echo "<td>" . $TeamStat['HomeGP'] . "</td>";
	echo "<td>" . $TeamStat['HomeW']  . "</td>";
	echo "<td>" . $TeamStat['HomeL'] . "</td>";
	echo "<td>" . $TeamStat['HomeT'] . "</td>";
	echo "<td>" . $TeamStat['HomeOTW'] . "</td>";	
	echo "<td>" . $TeamStat['HomeOTL'] . "</td>";	
	echo "<td>" . $TeamStat['HomeSOW'] . "</td>";	
	echo "<td>" . $TeamStat['HomeSOL'] . "</td>";	
	echo "<td>" . $TeamStat['HomeGF'] . "</td>";
	echo "<td>" . $TeamStat['HomeGA'] . "</td>";	
	echo "<td>" . ($TeamStat['HomeGF'] - $TeamStat['HomeGA']) . "</td>";	
	echo "<td>" . ($TeamStat['GP'] - $TeamStat['HomeGP']) . "</td>";
	echo "<td>" . ($TeamStat['W'] - $TeamStat['HomeW']) . "</td>";
	echo "<td>" . ($TeamStat['L'] - $TeamStat['HomeL']) . "</td>";
	echo "<td>" . ($TeamStat['T'] - $TeamStat['HomeT']) . "</td>";	
	echo "<td>" . ($TeamStat['OTW'] - $TeamStat['HomeOTW']) . "</td>";
	echo "<td>" . ($TeamStat['OTL'] - $TeamStat['HomeOTL']) . "</td>";
	echo "<td>" . ($TeamStat['SOW'] - $TeamStat['HomeSOW']) . "</td>";
	echo "<td>" . ($TeamStat['SOL'] - $TeamStat['HomeSOL']) . "</td>";
	echo "<td>" . ($TeamStat['GF'] - $TeamStat['HomeGF']) . "</td>";
	echo "<td>" . ($TeamStat['GA'] - $TeamStat['HomeGA']) . "</td>";
	echo "<td>" . (($TeamStat['GF'] - $TeamStat['HomeGF']) - ($TeamStat['GA'] - $TeamStat['HomeGA'])) . "</td>";		
	echo "<td><strong>" . $TeamStat['Points'] . "</strong></td>";
	echo "<td>" . $TeamStat['TotalGoal'] . "</td>";
	echo "<td>" . $TeamStat['TotalAssist'] . "</td>";
	echo "<td>" . $TeamStat['TotalPoint'] . "</td>";
	echo "<td>" . $TeamStat['EmptyNetGoal']. "</td>";
	echo "<td>" . $TeamStat['Shutouts']. "</td>";		
	echo "<td>" . $TeamStat['GoalsPerPeriod1']. "</td>";		
	echo "<td>" . $TeamStat['GoalsPerPeriod2']. "</td>";	
	echo "<td>" . $TeamStat['GoalsPerPeriod3']. "</td>";	
	echo "<td>" . $TeamStat['GoalsPerPeriod4']. "</td>";	
	echo "<td>" . $TeamStat['ShotsFor']. "</td>";	
	echo "<td>" . $TeamStat['ShotsPerPeriod1']. "</td>";
	echo "<td>" . $TeamStat['ShotsPerPeriod2']. "</td>";
	echo "<td>" . $TeamStat['ShotsPerPeriod3']. "</td>";
	echo "<td>" . $TeamStat['ShotsPerPeriod4']. "</td>";
	echo "<td>" . $TeamStat['ShotsAga']. "</td>";
	echo "<td>" . $TeamStat['ShotsBlock']. "</td>";		
	echo "<td>" . $TeamStat['Pim']. "</td>";
	echo "<td>" . $TeamStat['Hits']. "</td>";	
	echo "<td>" . $TeamStat['PPAttemp']. "</td>";
	echo "<td>" . $TeamStat['PPGoal']. "</td>";
	echo "<td>";if ($TeamStat['PPAttemp'] > 0){echo number_Format($TeamStat['PPGoal'] / $TeamStat['PPAttemp'] * 100,2) . "%";} else { echo "0.00%";} echo "</td>";		
	echo "<td>" . $TeamStat['PKAttemp']. "</td>";
	echo "<td>" . $TeamStat['PKGoalGA']. "</td>";
	echo "<td>";if ($TeamStat['PKAttemp'] > 0){echo number_Format(($TeamStat['PKAttemp'] - $TeamStat['PKGoalGA']) / $TeamStat['PKAttemp'] * 100,2) . "%";} else {echo "0.00%";} echo "</td>";
	echo "<td>" .  $TeamStat['PKGoalGF']. "</td>";	
	echo "<td>" . $TeamStat['FaceOffWonOffensifZone']. "</td>";
	echo "<td>" . $TeamStat['FaceOffTotalOffensifZone']. "</td>";		
	echo "<td>";if ($TeamStat['FaceOffTotalOffensifZone'] > 0){echo number_Format($TeamStat['FaceOffWonOffensifZone'] / $TeamStat['FaceOffTotalOffensifZone'] * 100,2) . "%" ;} else { echo "0.00%";} echo "</td>";	
	echo "<td>" . $TeamStat['FaceOffWonDefensifZone']. "</td>";
	echo "<td>" . $TeamStat['FaceOffTotalDefensifZone']. "</td>";
	echo "<td>";if ($TeamStat['FaceOffTotalDefensifZone'] > 0){echo number_Format($TeamStat['FaceOffWonDefensifZone'] / $TeamStat['FaceOffTotalDefensifZone'] * 100,2) . "%" ;} else { echo "0.00%";} echo "</td>";	
	echo "<td>" . $TeamStat['FaceOffWonNeutralZone']. "</td>";	
	echo "<td>" . $TeamStat['FaceOffTotalNeutralZone']. "</td>";	
	echo "<td>";if ($TeamStat['FaceOffTotalNeutralZone'] > 0){echo number_Format($TeamStat['FaceOffWonNeutralZone'] / $TeamStat['FaceOffTotalNeutralZone'] * 100,2) . "%" ;} else { echo "0.00%";} echo "</td>";	
	echo "<td>" . Floor($TeamStat['PuckTimeInZoneOF']/60). "</td>";
	echo "<td>" . Floor($TeamStat['PuckTimeControlinZoneOF']/60). "</td>";
	echo "<td>" . Floor($TeamStat['PuckTimeInZoneDF']/60). "</td>";
	echo "<td>" . Floor($TeamStat['PuckTimeControlinZoneDF']/60). "</td>";
	echo "<td>" . Floor($TeamStat['PuckTimeInZoneNT']/60). "</td>";		
	echo "<td>" . Floor($TeamStat['PuckTimeControlinZoneNT']/60). "</td>";		
	echo "</tr>\n"; /* The \n is for a new line in the HTML Code */	
	
	#Add Current Year in Career Stat
	$TeamCareerSumSeasonOnly['SumOfGP'] =  $TeamCareerSumSeasonOnly['SumOfGP'] + $TeamStat['GP'];
	$TeamCareerSumSeasonOnly['SumOfW'] =  $TeamCareerSumSeasonOnly['SumOfW'] + $TeamStat['W'];
	$TeamCareerSumSeasonOnly['SumOfL'] =  $TeamCareerSumSeasonOnly['SumOfL'] + $TeamStat['L'];
	$TeamCareerSumSeasonOnly['SumOfT'] =  $TeamCareerSumSeasonOnly['SumOfT'] + $TeamStat['T'];
	$TeamCareerSumSeasonOnly['SumOfOTW'] =  $TeamCareerSumSeasonOnly['SumOfOTW'] + $TeamStat['OTW'];
	$TeamCareerSumSeasonOnly['SumOfOTL'] =  $TeamCareerSumSeasonOnly['SumOfOTL'] + $TeamStat['OTL'];
	$TeamCareerSumSeasonOnly['SumOfSOW'] =  $TeamCareerSumSeasonOnly['SumOfSOW'] + $TeamStat['SOW'];
	$TeamCareerSumSeasonOnly['SumOfSOL'] =  $TeamCareerSumSeasonOnly['SumOfSOL'] + $TeamStat['SOL'];
	$TeamCareerSumSeasonOnly['SumOfPoints'] =  $TeamCareerSumSeasonOnly['SumOfPoints'] + $TeamStat['Points'];
	$TeamCareerSumSeasonOnly['SumOfGF'] =  $TeamCareerSumSeasonOnly['SumOfGF'] + $TeamStat['GF'];
	$TeamCareerSumSeasonOnly['SumOfGA'] =  $TeamCareerSumSeasonOnly['SumOfGA'] + $TeamStat['GA'];
	$TeamCareerSumSeasonOnly['SumOfHomeGP'] =  $TeamCareerSumSeasonOnly['SumOfHomeGP'] + $TeamStat['HomeGP'];
	$TeamCareerSumSeasonOnly['SumOfHomeW'] =  $TeamCareerSumSeasonOnly['SumOfHomeW'] + $TeamStat['HomeW'];
	$TeamCareerSumSeasonOnly['SumOfHomeL'] =  $TeamCareerSumSeasonOnly['SumOfHomeL'] + $TeamStat['HomeL'];
	$TeamCareerSumSeasonOnly['SumOfHomeT'] =  $TeamCareerSumSeasonOnly['SumOfHomeT'] + $TeamStat['HomeT'];
	$TeamCareerSumSeasonOnly['SumOfHomeOTW'] =  $TeamCareerSumSeasonOnly['SumOfHomeOTW'] + $TeamStat['HomeOTW'];
	$TeamCareerSumSeasonOnly['SumOfHomeOTL'] =  $TeamCareerSumSeasonOnly['SumOfHomeOTL'] + $TeamStat['HomeOTL'];
	$TeamCareerSumSeasonOnly['SumOfHomeSOW'] =  $TeamCareerSumSeasonOnly['SumOfHomeSOW'] + $TeamStat['HomeSOW'];
	$TeamCareerSumSeasonOnly['SumOfHomeSOL'] =  $TeamCareerSumSeasonOnly['SumOfHomeSOL'] + $TeamStat['HomeSOL'];
	$TeamCareerSumSeasonOnly['SumOfHomeGF'] =  $TeamCareerSumSeasonOnly['SumOfHomeGF'] + $TeamStat['HomeGF'];
	$TeamCareerSumSeasonOnly['SumOfHomeGA'] =  $TeamCareerSumSeasonOnly['SumOfHomeGA'] + $TeamStat['HomeGA'];
	$TeamCareerSumSeasonOnly['SumOfPPAttemp'] =  $TeamCareerSumSeasonOnly['SumOfPPAttemp'] + $TeamStat['PPAttemp'];
	$TeamCareerSumSeasonOnly['SumOfPPGoal'] =  $TeamCareerSumSeasonOnly['SumOfPPGoal'] + $TeamStat['PPGoal'];
	$TeamCareerSumSeasonOnly['SumOfPKAttemp'] =  $TeamCareerSumSeasonOnly['SumOfPKAttemp'] + $TeamStat['PKAttemp'];
	$TeamCareerSumSeasonOnly['SumOfPKGoalGA'] =  $TeamCareerSumSeasonOnly['SumOfPKGoalGA'] + $TeamStat['PKGoalGA'];
	$TeamCareerSumSeasonOnly['SumOfPKGoalGF'] =  $TeamCareerSumSeasonOnly['SumOfPKGoalGF'] + $TeamStat['PKGoalGF'];
	$TeamCareerSumSeasonOnly['SumOfShotsFor'] =  $TeamCareerSumSeasonOnly['SumOfShotsFor'] + $TeamStat['ShotsFor'];
	$TeamCareerSumSeasonOnly['SumOfShotsAga'] =  $TeamCareerSumSeasonOnly['SumOfShotsAga'] + $TeamStat['ShotsAga'];
	$TeamCareerSumSeasonOnly['SumOfShotsBlock'] =  $TeamCareerSumSeasonOnly['SumOfShotsBlock'] + $TeamStat['ShotsBlock'];
	$TeamCareerSumSeasonOnly['SumOfShotsPerPeriod1'] =  $TeamCareerSumSeasonOnly['SumOfShotsPerPeriod1'] + $TeamStat['ShotsPerPeriod1'];
	$TeamCareerSumSeasonOnly['SumOfShotsPerPeriod2'] =  $TeamCareerSumSeasonOnly['SumOfShotsPerPeriod2'] + $TeamStat['ShotsPerPeriod2'];
	$TeamCareerSumSeasonOnly['SumOfShotsPerPeriod3'] =  $TeamCareerSumSeasonOnly['SumOfShotsPerPeriod3'] + $TeamStat['ShotsPerPeriod3'];
	$TeamCareerSumSeasonOnly['SumOfShotsPerPeriod4'] =  $TeamCareerSumSeasonOnly['SumOfShotsPerPeriod4'] + $TeamStat['ShotsPerPeriod4'];
	$TeamCareerSumSeasonOnly['SumOfGoalsPerPeriod1'] =  $TeamCareerSumSeasonOnly['SumOfGoalsPerPeriod1'] + $TeamStat['GoalsPerPeriod1'];
	$TeamCareerSumSeasonOnly['SumOfGoalsPerPeriod2'] =  $TeamCareerSumSeasonOnly['SumOfGoalsPerPeriod2'] + $TeamStat['GoalsPerPeriod2'];
	$TeamCareerSumSeasonOnly['SumOfGoalsPerPeriod3'] =  $TeamCareerSumSeasonOnly['SumOfGoalsPerPeriod3'] + $TeamStat['GoalsPerPeriod3'];
	$TeamCareerSumSeasonOnly['SumOfGoalsPerPeriod4'] =  $TeamCareerSumSeasonOnly['SumOfGoalsPerPeriod4'] + $TeamStat['GoalsPerPeriod4'];
	$TeamCareerSumSeasonOnly['SumOfPuckTimeInZoneDF'] =  $TeamCareerSumSeasonOnly['SumOfPuckTimeInZoneDF'] + $TeamStat['PuckTimeInZoneDF'];
	$TeamCareerSumSeasonOnly['SumOfPuckTimeInZoneOF'] =  $TeamCareerSumSeasonOnly['SumOfPuckTimeInZoneOF'] + $TeamStat['PuckTimeInZoneOF'];
	$TeamCareerSumSeasonOnly['SumOfPuckTimeInZoneNT'] =  $TeamCareerSumSeasonOnly['SumOfPuckTimeInZoneNT'] + $TeamStat['PuckTimeInZoneNT'];
	$TeamCareerSumSeasonOnly['SumOfPuckTimeControlinZoneDF'] =  $TeamCareerSumSeasonOnly['SumOfPuckTimeControlinZoneDF'] + $TeamStat['PuckTimeControlinZoneDF'];
	$TeamCareerSumSeasonOnly['SumOfPuckTimeControlinZoneOF'] =  $TeamCareerSumSeasonOnly['SumOfPuckTimeControlinZoneOF'] + $TeamStat['PuckTimeControlinZoneOF'];
	$TeamCareerSumSeasonOnly['SumOfPuckTimeControlinZoneNT'] =  $TeamCareerSumSeasonOnly['SumOfPuckTimeControlinZoneNT'] + $TeamStat['PuckTimeControlinZoneNT'];
	$TeamCareerSumSeasonOnly['SumOfShutouts'] =  $TeamCareerSumSeasonOnly['SumOfShutouts'] + $TeamStat['Shutouts'];
	$TeamCareerSumSeasonOnly['SumOfTotalGoal'] =  $TeamCareerSumSeasonOnly['SumOfTotalGoal'] + $TeamStat['TotalGoal'];
	$TeamCareerSumSeasonOnly['SumOfTotalAssist'] =  $TeamCareerSumSeasonOnly['SumOfTotalAssist'] + $TeamStat['TotalAssist'];
	$TeamCareerSumSeasonOnly['SumOfTotalPoint'] =  $TeamCareerSumSeasonOnly['SumOfTotalPoint'] + $TeamStat['TotalPoint'];
	$TeamCareerSumSeasonOnly['SumOfPim'] =  $TeamCareerSumSeasonOnly['SumOfPim'] + $TeamStat['Pim'];
	$TeamCareerSumSeasonOnly['SumOfHits'] =  $TeamCareerSumSeasonOnly['SumOfHits'] + $TeamStat['Hits'];
	$TeamCareerSumSeasonOnly['SumOfFaceOffWonDefensifZone'] =  $TeamCareerSumSeasonOnly['SumOfFaceOffWonDefensifZone'] + $TeamStat['FaceOffWonDefensifZone'];
	$TeamCareerSumSeasonOnly['SumOfFaceOffTotalDefensifZone'] =  $TeamCareerSumSeasonOnly['SumOfFaceOffTotalDefensifZone'] + $TeamStat['FaceOffTotalDefensifZone'];
	$TeamCareerSumSeasonOnly['SumOfFaceOffWonOffensifZone'] =  $TeamCareerSumSeasonOnly['SumOfFaceOffWonOffensifZone'] + $TeamStat['FaceOffWonOffensifZone'];
	$TeamCareerSumSeasonOnly['SumOfFaceOffTotalOffensifZone'] =  $TeamCareerSumSeasonOnly['SumOfFaceOffTotalOffensifZone'] + $TeamStat['FaceOffTotalOffensifZone'];
	$TeamCareerSumSeasonOnly['SumOfFaceOffWonNeutralZone'] =  $TeamCareerSumSeasonOnly['SumOfFaceOffWonNeutralZone'] + $TeamStat['FaceOffWonNeutralZone'];
	$TeamCareerSumSeasonOnly['SumOfFaceOffTotalNeutralZone'] =  $TeamCareerSumSeasonOnly['SumOfFaceOffTotalNeutralZone'] + $TeamStat['FaceOffTotalNeutralZone'];
	$TeamCareerSumSeasonOnly['SumOfEmptyNetGoal'] =  $TeamCareerSumSeasonOnly['SumOfEmptyNetGoal'] + $TeamStat['EmptyNetGoal'];	
}
if ($TeamCareerSumSeasonOnly['SumOfGP'] > 0){
	/* Show TeamCareer Total for Season */
	echo "<tr class=\"static\"><td class=\"staticTD\"><strong>" . $PlayersLang['Total'] . " " . $PlayersLang['RegularSeason']. "</strong></td>";
	echo "<td class=\"staticTD\">" . $TeamCareerSumSeasonOnly['SumOfGP'] . "</td>";
	echo "<td class=\"staticTD\">" . $TeamCareerSumSeasonOnly['SumOfW']  . "</td>";
	echo "<td class=\"staticTD\">" . $TeamCareerSumSeasonOnly['SumOfL'] . "</td>";
	echo "<td class=\"staticTD\">" . $TeamCareerSumSeasonOnly['SumOfT'] . "</td>";
	echo "<td class=\"staticTD\">" . $TeamCareerSumSeasonOnly['SumOfOTW'] . "</td>";	
	echo "<td class=\"staticTD\">" . $TeamCareerSumSeasonOnly['SumOfOTL'] . "</td>";	
	echo "<td class=\"staticTD\">" . $TeamCareerSumSeasonOnly['SumOfSOW'] . "</td>";	
	echo "<td class=\"staticTD\">" . $TeamCareerSumSeasonOnly['SumOfSOL'] . "</td>";	
	echo "<td class=\"staticTD\">" . $TeamCareerSumSeasonOnly['SumOfGF'] . "</td>";
	echo "<td class=\"staticTD\">" . $TeamCareerSumSeasonOnly['SumOfGA'] . "</td>";
	echo "<td class=\"staticTD\">" . ($TeamCareerSumSeasonOnly['SumOfGF'] - $TeamCareerSumSeasonOnly['SumOfGA']) . "</td>";	
	echo "<td class=\"staticTD\">" . $TeamCareerSumSeasonOnly['SumOfHomeGP'] . "</td>";
	echo "<td class=\"staticTD\">" . $TeamCareerSumSeasonOnly['SumOfHomeW']  . "</td>";
	echo "<td class=\"staticTD\">" . $TeamCareerSumSeasonOnly['SumOfHomeL'] . "</td>";
	echo "<td class=\"staticTD\">" . $TeamCareerSumSeasonOnly['SumOfHomeT'] . "</td>";
	echo "<td class=\"staticTD\">" . $TeamCareerSumSeasonOnly['SumOfHomeOTW'] . "</td>";	
	echo "<td class=\"staticTD\">" . $TeamCareerSumSeasonOnly['SumOfHomeOTL'] . "</td>";	
	echo "<td class=\"staticTD\">" . $TeamCareerSumSeasonOnly['SumOfHomeSOW'] . "</td>";	
	echo "<td class=\"staticTD\">" . $TeamCareerSumSeasonOnly['SumOfHomeSOL'] . "</td>";	
	echo "<td class=\"staticTD\">" . $TeamCareerSumSeasonOnly['SumOfHomeGF'] . "</td>";
	echo "<td class=\"staticTD\">" . $TeamCareerSumSeasonOnly['SumOfHomeGA'] . "</td>";	
	echo "<td class=\"staticTD\">" . ($TeamCareerSumSeasonOnly['SumOfHomeGF'] - $TeamCareerSumSeasonOnly['SumOfHomeGA']) . "</td>";	
	echo "<td class=\"staticTD\">" . ($TeamCareerSumSeasonOnly['SumOfGP'] - $TeamCareerSumSeasonOnly['SumOfHomeGP']) . "</td>";
	echo "<td class=\"staticTD\">" . ($TeamCareerSumSeasonOnly['SumOfW'] - $TeamCareerSumSeasonOnly['SumOfHomeW']) . "</td>";
	echo "<td class=\"staticTD\">" . ($TeamCareerSumSeasonOnly['SumOfL'] - $TeamCareerSumSeasonOnly['SumOfHomeL']) . "</td>";
	echo "<td class=\"staticTD\">" . ($TeamCareerSumSeasonOnly['SumOfT'] - $TeamCareerSumSeasonOnly['SumOfHomeT']) . "</td>";	
	echo "<td class=\"staticTD\">" . ($TeamCareerSumSeasonOnly['SumOfOTW'] - $TeamCareerSumSeasonOnly['SumOfHomeOTW']) . "</td>";
	echo "<td class=\"staticTD\">" . ($TeamCareerSumSeasonOnly['SumOfOTL'] - $TeamCareerSumSeasonOnly['SumOfHomeOTL']) . "</td>";
	echo "<td class=\"staticTD\">" . ($TeamCareerSumSeasonOnly['SumOfSOW'] - $TeamCareerSumSeasonOnly['SumOfHomeSOW']) . "</td>";
	echo "<td class=\"staticTD\">" . ($TeamCareerSumSeasonOnly['SumOfSOL'] - $TeamCareerSumSeasonOnly['SumOfHomeSOL']) . "</td>";
	echo "<td class=\"staticTD\">" . ($TeamCareerSumSeasonOnly['SumOfGF'] - $TeamCareerSumSeasonOnly['SumOfHomeGF']) . "</td>";
	echo "<td class=\"staticTD\">" . ($TeamCareerSumSeasonOnly['SumOfGA'] - $TeamCareerSumSeasonOnly['SumOfHomeGA']) . "</td>";
	echo "<td class=\"staticTD\">" . (($TeamCareerSumSeasonOnly['SumOfGF'] - $TeamCareerSumSeasonOnly['SumOfHomeGF']) - ($TeamCareerSumSeasonOnly['SumOfGA'] - $TeamCareerSumSeasonOnly['SumOfHomeGA'])) . "</td>";		
	echo "<td class=\"staticTD\"><strong>" . $TeamCareerSumSeasonOnly['SumOfPoints'] . "</strong></td>";
	echo "<td class=\"staticTD\">" . $TeamCareerSumSeasonOnly['SumOfTotalGoal'] . "</td>";
	echo "<td class=\"staticTD\">" . $TeamCareerSumSeasonOnly['SumOfTotalAssist'] . "</td>";
	echo "<td class=\"staticTD\">" . $TeamCareerSumSeasonOnly['SumOfTotalPoint'] . "</td>";
	echo "<td class=\"staticTD\">" . $TeamCareerSumSeasonOnly['SumOfEmptyNetGoal']. "</td>";
	echo "<td class=\"staticTD\">" . $TeamCareerSumSeasonOnly['SumOfShutouts']. "</td>";		
	echo "<td class=\"staticTD\">" . $TeamCareerSumSeasonOnly['SumOfGoalsPerPeriod1']. "</td>";		
	echo "<td class=\"staticTD\">" . $TeamCareerSumSeasonOnly['SumOfGoalsPerPeriod2']. "</td>";	
	echo "<td class=\"staticTD\">" . $TeamCareerSumSeasonOnly['SumOfGoalsPerPeriod3']. "</td>";	
	echo "<td class=\"staticTD\">" . $TeamCareerSumSeasonOnly['SumOfGoalsPerPeriod4']. "</td>";	
	echo "<td class=\"staticTD\">" . $TeamCareerSumSeasonOnly['SumOfShotsFor']. "</td>";	
	echo "<td class=\"staticTD\">" . $TeamCareerSumSeasonOnly['SumOfShotsPerPeriod1']. "</td>";
	echo "<td class=\"staticTD\">" . $TeamCareerSumSeasonOnly['SumOfShotsPerPeriod2']. "</td>";
	echo "<td class=\"staticTD\">" . $TeamCareerSumSeasonOnly['SumOfShotsPerPeriod3']. "</td>";
	echo "<td class=\"staticTD\">" . $TeamCareerSumSeasonOnly['SumOfShotsPerPeriod4']. "</td>";
	echo "<td class=\"staticTD\">" . $TeamCareerSumSeasonOnly['SumOfShotsAga']. "</td>";
	echo "<td class=\"staticTD\">" . $TeamCareerSumSeasonOnly['SumOfShotsBlock']. "</td>";		
	echo "<td class=\"staticTD\">" . $TeamCareerSumSeasonOnly['SumOfPim']. "</td>";
	echo "<td class=\"staticTD\">" . $TeamCareerSumSeasonOnly['SumOfHits']. "</td>";	
	echo "<td class=\"staticTD\">" . $TeamCareerSumSeasonOnly['SumOfPPAttemp']. "</td>";
	echo "<td class=\"staticTD\">" . $TeamCareerSumSeasonOnly['SumOfPPGoal']. "</td>";
	echo "<td class=\"staticTD\">";if ($TeamCareerSumSeasonOnly['SumOfPPAttemp'] > 0){echo number_Format($TeamCareerSumSeasonOnly['SumOfPPGoal'] / $TeamCareerSumSeasonOnly['SumOfPPAttemp'] * 100,2) . "%";} else { echo "0.00%";} echo "</td>";		
	echo "<td class=\"staticTD\">" . $TeamCareerSumSeasonOnly['SumOfPKAttemp']. "</td>";
	echo "<td class=\"staticTD\">" . $TeamCareerSumSeasonOnly['SumOfPKGoalGA']. "</td>";
	echo "<td class=\"staticTD\">";if ($TeamCareerSumSeasonOnly['SumOfPKAttemp'] > 0){echo number_Format(($TeamCareerSumSeasonOnly['SumOfPKAttemp'] - $TeamCareerSumSeasonOnly['SumOfPKGoalGA']) / $TeamCareerSumSeasonOnly['SumOfPKAttemp'] * 100,2) . "%";} else {echo "0.00%";} echo "</td>";
	echo "<td class=\"staticTD\">" .  $TeamCareerSumSeasonOnly['SumOfPKGoalGF']. "</td>";	
	echo "<td class=\"staticTD\">" . $TeamCareerSumSeasonOnly['SumOfFaceOffWonOffensifZone']. "</td>";
	echo "<td class=\"staticTD\">" . $TeamCareerSumSeasonOnly['SumOfFaceOffTotalOffensifZone']. "</td>";		
	echo "<td class=\"staticTD\">";if ($TeamCareerSumSeasonOnly['SumOfFaceOffTotalOffensifZone'] > 0){echo number_Format($TeamCareerSumSeasonOnly['SumOfFaceOffWonOffensifZone'] / $TeamCareerSumSeasonOnly['SumOfFaceOffTotalOffensifZone'] * 100,2) . "%" ;} else { echo "0.00%";} echo "</td>";	
	echo "<td class=\"staticTD\">" . $TeamCareerSumSeasonOnly['SumOfFaceOffWonDefensifZone']. "</td>";
	echo "<td class=\"staticTD\">" . $TeamCareerSumSeasonOnly['SumOfFaceOffTotalDefensifZone']. "</td>";
	echo "<td class=\"staticTD\">";if ($TeamCareerSumSeasonOnly['SumOfFaceOffTotalDefensifZone'] > 0){echo number_Format($TeamCareerSumSeasonOnly['SumOfFaceOffWonDefensifZone'] / $TeamCareerSumSeasonOnly['SumOfFaceOffTotalDefensifZone'] * 100,2) . "%" ;} else { echo "0.00%";} echo "</td>";	
	echo "<td class=\"staticTD\">" . $TeamCareerSumSeasonOnly['SumOfFaceOffWonNeutralZone']. "</td>";	
	echo "<td class=\"staticTD\">" . $TeamCareerSumSeasonOnly['SumOfFaceOffTotalNeutralZone']. "</td>";	
	echo "<td class=\"staticTD\">";if ($TeamCareerSumSeasonOnly['SumOfFaceOffTotalNeutralZone'] > 0){echo number_Format($TeamCareerSumSeasonOnly['SumOfFaceOffWonNeutralZone'] / $TeamCareerSumSeasonOnly['SumOfFaceOffTotalNeutralZone'] * 100,2) . "%" ;} else { echo "0.00%";} echo "</td>";	
	echo "<td class=\"staticTD\">" . Floor($TeamCareerSumSeasonOnly['SumOfPuckTimeInZoneOF']/60). "</td>";
	echo "<td class=\"staticTD\">" . Floor($TeamCareerSumSeasonOnly['SumOfPuckTimeControlinZoneOF']/60). "</td>";
	echo "<td class=\"staticTD\">" . Floor($TeamCareerSumSeasonOnly['SumOfPuckTimeInZoneDF']/60). "</td>";
	echo "<td class=\"staticTD\">" . Floor($TeamCareerSumSeasonOnly['SumOfPuckTimeControlinZoneDF']/60). "</td>";
	echo "<td class=\"staticTD\">" . Floor($TeamCareerSumSeasonOnly['SumOfPuckTimeInZoneNT']/60). "</td>";		
	echo "<td class=\"staticTD\">" . Floor($TeamCareerSumSeasonOnly['SumOfPuckTimeControlinZoneNT']/60). "</td>";		
	echo "</tr>\n"; /* The \n is for a new line in the HTML Code */	
}
if ($TeamCareerSumPlayoffOnly['SumOfGP'] > 0){echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"75\"><strong>" . $PlayersLang['Playoff'] . "</strong></td></tr>\n";}
if (empty($TeamCareerPlayoff) == false){while ($row = $TeamCareerPlayoff ->fetchArray()) {
	/* Loop Team Career Playoff */
	echo "<tr><td>" . $row['Year'] . "</td>";
	echo "<td>" . $row['GP'] . "</td>";
	echo "<td>" . $row['W']  . "</td>";
	echo "<td>" . $row['L'] . "</td>";
	echo "<td>" . $row['T'] . "</td>";
	echo "<td>" . $row['OTW'] . "</td>";	
	echo "<td>" . $row['OTL'] . "</td>";	
	echo "<td>" . $row['SOW'] . "</td>";	
	echo "<td>" . $row['SOL'] . "</td>";	
	echo "<td>" . $row['GF'] . "</td>";
	echo "<td>" . $row['GA'] . "</td>";
	echo "<td>" . ($row['GF'] - $row['GA']) . "</td>";	
	echo "<td>" . $row['HomeGP'] . "</td>";
	echo "<td>" . $row['HomeW']  . "</td>";
	echo "<td>" . $row['HomeL'] . "</td>";
	echo "<td>" . $row['HomeT'] . "</td>";
	echo "<td>" . $row['HomeOTW'] . "</td>";	
	echo "<td>" . $row['HomeOTL'] . "</td>";	
	echo "<td>" . $row['HomeSOW'] . "</td>";	
	echo "<td>" . $row['HomeSOL'] . "</td>";	
	echo "<td>" . $row['HomeGF'] . "</td>";
	echo "<td>" . $row['HomeGA'] . "</td>";	
	echo "<td>" . ($row['HomeGF'] - $row['HomeGA']) . "</td>";	
	echo "<td>" . ($row['GP'] - $row['HomeGP']) . "</td>";
	echo "<td>" . ($row['W'] - $row['HomeW']) . "</td>";
	echo "<td>" . ($row['L'] - $row['HomeL']) . "</td>";
	echo "<td>" . ($row['T'] - $row['HomeT']) . "</td>";	
	echo "<td>" . ($row['OTW'] - $row['HomeOTW']) . "</td>";
	echo "<td>" . ($row['OTL'] - $row['HomeOTL']) . "</td>";
	echo "<td>" . ($row['SOW'] - $row['HomeSOW']) . "</td>";
	echo "<td>" . ($row['SOL'] - $row['HomeSOL']) . "</td>";
	echo "<td>" . ($row['GF'] - $row['HomeGF']) . "</td>";
	echo "<td>" . ($row['GA'] - $row['HomeGA']) . "</td>";
	echo "<td>" . (($row['GF'] - $row['HomeGF']) - ($row['GA'] - $row['HomeGA'])) . "</td>";		
	echo "<td><strong>" . $row['Points'] . "</strong></td>";
	echo "<td>" . $row['TotalGoal'] . "</td>";
	echo "<td>" . $row['TotalAssist'] . "</td>";
	echo "<td>" . $row['TotalPoint'] . "</td>";
	echo "<td>" . $row['EmptyNetGoal']. "</td>";
	echo "<td>" . $row['Shutouts']. "</td>";		
	echo "<td>" . $row['GoalsPerPeriod1']. "</td>";		
	echo "<td>" . $row['GoalsPerPeriod2']. "</td>";	
	echo "<td>" . $row['GoalsPerPeriod3']. "</td>";	
	echo "<td>" . $row['GoalsPerPeriod4']. "</td>";	
	echo "<td>" . $row['ShotsFor']. "</td>";	
	echo "<td>" . $row['ShotsPerPeriod1']. "</td>";
	echo "<td>" . $row['ShotsPerPeriod2']. "</td>";
	echo "<td>" . $row['ShotsPerPeriod3']. "</td>";
	echo "<td>" . $row['ShotsPerPeriod4']. "</td>";
	echo "<td>" . $row['ShotsAga']. "</td>";
	echo "<td>" . $row['ShotsBlock']. "</td>";		
	echo "<td>" . $row['Pim']. "</td>";
	echo "<td>" . $row['Hits']. "</td>";	
	echo "<td>" . $row['PPAttemp']. "</td>";
	echo "<td>" . $row['PPGoal']. "</td>";
	echo "<td>";if ($row['PPAttemp'] > 0){echo number_Format($row['PPGoal'] / $row['PPAttemp'] * 100,2) . "%";} else { echo "0.00%";} echo "</td>";		
	echo "<td>" . $row['PKAttemp']. "</td>";
	echo "<td>" . $row['PKGoalGA']. "</td>";
	echo "<td>";if ($row['PKAttemp'] > 0){echo number_Format(($row['PKAttemp'] - $row['PKGoalGA']) / $row['PKAttemp'] * 100,2) . "%";} else {echo "0.00%";} echo "</td>";
	echo "<td>" .  $row['PKGoalGF']. "</td>";	
	echo "<td>" . $row['FaceOffWonOffensifZone']. "</td>";
	echo "<td>" . $row['FaceOffTotalOffensifZone']. "</td>";		
	echo "<td>";if ($row['FaceOffTotalOffensifZone'] > 0){echo number_Format($row['FaceOffWonOffensifZone'] / $row['FaceOffTotalOffensifZone'] * 100,2) . "%" ;} else { echo "0.00%";} echo "</td>";	
	echo "<td>" . $row['FaceOffWonDefensifZone']. "</td>";
	echo "<td>" . $row['FaceOffTotalDefensifZone']. "</td>";
	echo "<td>";if ($row['FaceOffTotalDefensifZone'] > 0){echo number_Format($row['FaceOffWonDefensifZone'] / $row['FaceOffTotalDefensifZone'] * 100,2) . "%" ;} else { echo "0.00%";} echo "</td>";	
	echo "<td>" . $row['FaceOffWonNeutralZone']. "</td>";	
	echo "<td>" . $row['FaceOffTotalNeutralZone']. "</td>";	
	echo "<td>";if ($row['FaceOffTotalNeutralZone'] > 0){echo number_Format($row['FaceOffWonNeutralZone'] / $row['FaceOffTotalNeutralZone'] * 100,2) . "%" ;} else { echo "0.00%";} echo "</td>";	
	echo "<td>" . Floor($row['PuckTimeInZoneOF']/60). "</td>";
	echo "<td>" . Floor($row['PuckTimeControlinZoneOF']/60). "</td>";
	echo "<td>" . Floor($row['PuckTimeInZoneDF']/60). "</td>";
	echo "<td>" . Floor($row['PuckTimeControlinZoneDF']/60). "</td>";
	echo "<td>" . Floor($row['PuckTimeInZoneNT']/60). "</td>";		
	echo "<td>" . Floor($row['PuckTimeControlinZoneNT']/60). "</td>";		
	echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
}}
if ($TeamStat['GP'] > 0 AND $LeagueGeneral['PreSeasonSchedule'] == "False" AND $LeagueGeneral['PlayOffStarted'] == "True"){
	echo "<tr><td>" . $LeagueGeneral['LeagueYearOutput'] . "</td>";
	echo "<td>" . $TeamStat['GP'] . "</td>";
	echo "<td>" . $TeamStat['W']  . "</td>";
	echo "<td>" . $TeamStat['L'] . "</td>";
	echo "<td>" . $TeamStat['T'] . "</td>";
	echo "<td>" . $TeamStat['OTW'] . "</td>";	
	echo "<td>" . $TeamStat['OTL'] . "</td>";	
	echo "<td>" . $TeamStat['SOW'] . "</td>";	
	echo "<td>" . $TeamStat['SOL'] . "</td>";	
	echo "<td>" . $TeamStat['GF'] . "</td>";
	echo "<td>" . $TeamStat['GA'] . "</td>";
	echo "<td>" . ($TeamStat['GF'] - $TeamStat['GA']) . "</td>";	
	echo "<td>" . $TeamStat['HomeGP'] . "</td>";
	echo "<td>" . $TeamStat['HomeW']  . "</td>";
	echo "<td>" . $TeamStat['HomeL'] . "</td>";
	echo "<td>" . $TeamStat['HomeT'] . "</td>";
	echo "<td>" . $TeamStat['HomeOTW'] . "</td>";	
	echo "<td>" . $TeamStat['HomeOTL'] . "</td>";	
	echo "<td>" . $TeamStat['HomeSOW'] . "</td>";	
	echo "<td>" . $TeamStat['HomeSOL'] . "</td>";	
	echo "<td>" . $TeamStat['HomeGF'] . "</td>";
	echo "<td>" . $TeamStat['HomeGA'] . "</td>";	
	echo "<td>" . ($TeamStat['HomeGF'] - $TeamStat['HomeGA']) . "</td>";	
	echo "<td>" . ($TeamStat['GP'] - $TeamStat['HomeGP']) . "</td>";
	echo "<td>" . ($TeamStat['W'] - $TeamStat['HomeW']) . "</td>";
	echo "<td>" . ($TeamStat['L'] - $TeamStat['HomeL']) . "</td>";
	echo "<td>" . ($TeamStat['T'] - $TeamStat['HomeT']) . "</td>";	
	echo "<td>" . ($TeamStat['OTW'] - $TeamStat['HomeOTW']) . "</td>";
	echo "<td>" . ($TeamStat['OTL'] - $TeamStat['HomeOTL']) . "</td>";
	echo "<td>" . ($TeamStat['SOW'] - $TeamStat['HomeSOW']) . "</td>";
	echo "<td>" . ($TeamStat['SOL'] - $TeamStat['HomeSOL']) . "</td>";
	echo "<td>" . ($TeamStat['GF'] - $TeamStat['HomeGF']) . "</td>";
	echo "<td>" . ($TeamStat['GA'] - $TeamStat['HomeGA']) . "</td>";
	echo "<td>" . (($TeamStat['GF'] - $TeamStat['HomeGF']) - ($TeamStat['GA'] - $TeamStat['HomeGA'])) . "</td>";		
	echo "<td><strong>" . $TeamStat['Points'] . "</strong></td>";
	echo "<td>" . $TeamStat['TotalGoal'] . "</td>";
	echo "<td>" . $TeamStat['TotalAssist'] . "</td>";
	echo "<td>" . $TeamStat['TotalPoint'] . "</td>";
	echo "<td>" . $TeamStat['EmptyNetGoal']. "</td>";
	echo "<td>" . $TeamStat['Shutouts']. "</td>";		
	echo "<td>" . $TeamStat['GoalsPerPeriod1']. "</td>";		
	echo "<td>" . $TeamStat['GoalsPerPeriod2']. "</td>";	
	echo "<td>" . $TeamStat['GoalsPerPeriod3']. "</td>";	
	echo "<td>" . $TeamStat['GoalsPerPeriod4']. "</td>";	
	echo "<td>" . $TeamStat['ShotsFor']. "</td>";	
	echo "<td>" . $TeamStat['ShotsPerPeriod1']. "</td>";
	echo "<td>" . $TeamStat['ShotsPerPeriod2']. "</td>";
	echo "<td>" . $TeamStat['ShotsPerPeriod3']. "</td>";
	echo "<td>" . $TeamStat['ShotsPerPeriod4']. "</td>";
	echo "<td>" . $TeamStat['ShotsAga']. "</td>";
	echo "<td>" . $TeamStat['ShotsBlock']. "</td>";		
	echo "<td>" . $TeamStat['Pim']. "</td>";
	echo "<td>" . $TeamStat['Hits']. "</td>";	
	echo "<td>" . $TeamStat['PPAttemp']. "</td>";
	echo "<td>" . $TeamStat['PPGoal']. "</td>";
	echo "<td>";if ($TeamStat['PPAttemp'] > 0){echo number_Format($TeamStat['PPGoal'] / $TeamStat['PPAttemp'] * 100,2) . "%";} else { echo "0.00%";} echo "</td>";		
	echo "<td>" . $TeamStat['PKAttemp']. "</td>";
	echo "<td>" . $TeamStat['PKGoalGA']. "</td>";
	echo "<td>";if ($TeamStat['PKAttemp'] > 0){echo number_Format(($TeamStat['PKAttemp'] - $TeamStat['PKGoalGA']) / $TeamStat['PKAttemp'] * 100,2) . "%";} else {echo "0.00%";} echo "</td>";
	echo "<td>" .  $TeamStat['PKGoalGF']. "</td>";	
	echo "<td>" . $TeamStat['FaceOffWonOffensifZone']. "</td>";
	echo "<td>" . $TeamStat['FaceOffTotalOffensifZone']. "</td>";		
	echo "<td>";if ($TeamStat['FaceOffTotalOffensifZone'] > 0){echo number_Format($TeamStat['FaceOffWonOffensifZone'] / $TeamStat['FaceOffTotalOffensifZone'] * 100,2) . "%" ;} else { echo "0.00%";} echo "</td>";	
	echo "<td>" . $TeamStat['FaceOffWonDefensifZone']. "</td>";
	echo "<td>" . $TeamStat['FaceOffTotalDefensifZone']. "</td>";
	echo "<td>";if ($TeamStat['FaceOffTotalDefensifZone'] > 0){echo number_Format($TeamStat['FaceOffWonDefensifZone'] / $TeamStat['FaceOffTotalDefensifZone'] * 100,2) . "%" ;} else { echo "0.00%";} echo "</td>";	
	echo "<td>" . $TeamStat['FaceOffWonNeutralZone']. "</td>";	
	echo "<td>" . $TeamStat['FaceOffTotalNeutralZone']. "</td>";	
	echo "<td>";if ($TeamStat['FaceOffTotalNeutralZone'] > 0){echo number_Format($TeamStat['FaceOffWonNeutralZone'] / $TeamStat['FaceOffTotalNeutralZone'] * 100,2) . "%" ;} else { echo "0.00%";} echo "</td>";	
	echo "<td>" . Floor($TeamStat['PuckTimeInZoneOF']/60). "</td>";
	echo "<td>" . Floor($TeamStat['PuckTimeControlinZoneOF']/60). "</td>";
	echo "<td>" . Floor($TeamStat['PuckTimeInZoneDF']/60). "</td>";
	echo "<td>" . Floor($TeamStat['PuckTimeControlinZoneDF']/60). "</td>";
	echo "<td>" . Floor($TeamStat['PuckTimeInZoneNT']/60). "</td>";		
	echo "<td>" . Floor($TeamStat['PuckTimeControlinZoneNT']/60). "</td>";		
	echo "</tr>\n"; /* The \n is for a new line in the HTML Code */	
	
	#Add Current Year in Career Stat
	$TeamCareerSumPlayoffOnly['SumOfGP'] =  $TeamCareerSumPlayoffOnly['SumOfGP'] + $TeamStat['GP'];
	$TeamCareerSumPlayoffOnly['SumOfW'] =  $TeamCareerSumPlayoffOnly['SumOfW'] + $TeamStat['W'];
	$TeamCareerSumPlayoffOnly['SumOfL'] =  $TeamCareerSumPlayoffOnly['SumOfL'] + $TeamStat['L'];
	$TeamCareerSumPlayoffOnly['SumOfT'] =  $TeamCareerSumPlayoffOnly['SumOfT'] + $TeamStat['T'];
	$TeamCareerSumPlayoffOnly['SumOfOTW'] =  $TeamCareerSumPlayoffOnly['SumOfOTW'] + $TeamStat['OTW'];
	$TeamCareerSumPlayoffOnly['SumOfOTL'] =  $TeamCareerSumPlayoffOnly['SumOfOTL'] + $TeamStat['OTL'];
	$TeamCareerSumPlayoffOnly['SumOfSOW'] =  $TeamCareerSumPlayoffOnly['SumOfSOW'] + $TeamStat['SOW'];
	$TeamCareerSumPlayoffOnly['SumOfSOL'] =  $TeamCareerSumPlayoffOnly['SumOfSOL'] + $TeamStat['SOL'];
	$TeamCareerSumPlayoffOnly['SumOfPoints'] =  $TeamCareerSumPlayoffOnly['SumOfPoints'] + $TeamStat['Points'];
	$TeamCareerSumPlayoffOnly['SumOfGF'] =  $TeamCareerSumPlayoffOnly['SumOfGF'] + $TeamStat['GF'];
	$TeamCareerSumPlayoffOnly['SumOfGA'] =  $TeamCareerSumPlayoffOnly['SumOfGA'] + $TeamStat['GA'];
	$TeamCareerSumPlayoffOnly['SumOfHomeGP'] =  $TeamCareerSumPlayoffOnly['SumOfHomeGP'] + $TeamStat['HomeGP'];
	$TeamCareerSumPlayoffOnly['SumOfHomeW'] =  $TeamCareerSumPlayoffOnly['SumOfHomeW'] + $TeamStat['HomeW'];
	$TeamCareerSumPlayoffOnly['SumOfHomeL'] =  $TeamCareerSumPlayoffOnly['SumOfHomeL'] + $TeamStat['HomeL'];
	$TeamCareerSumPlayoffOnly['SumOfHomeT'] =  $TeamCareerSumPlayoffOnly['SumOfHomeT'] + $TeamStat['HomeT'];
	$TeamCareerSumPlayoffOnly['SumOfHomeOTW'] =  $TeamCareerSumPlayoffOnly['SumOfHomeOTW'] + $TeamStat['HomeOTW'];
	$TeamCareerSumPlayoffOnly['SumOfHomeOTL'] =  $TeamCareerSumPlayoffOnly['SumOfHomeOTL'] + $TeamStat['HomeOTL'];
	$TeamCareerSumPlayoffOnly['SumOfHomeSOW'] =  $TeamCareerSumPlayoffOnly['SumOfHomeSOW'] + $TeamStat['HomeSOW'];
	$TeamCareerSumPlayoffOnly['SumOfHomeSOL'] =  $TeamCareerSumPlayoffOnly['SumOfHomeSOL'] + $TeamStat['HomeSOL'];
	$TeamCareerSumPlayoffOnly['SumOfHomeGF'] =  $TeamCareerSumPlayoffOnly['SumOfHomeGF'] + $TeamStat['HomeGF'];
	$TeamCareerSumPlayoffOnly['SumOfHomeGA'] =  $TeamCareerSumPlayoffOnly['SumOfHomeGA'] + $TeamStat['HomeGA'];
	$TeamCareerSumPlayoffOnly['SumOfPPAttemp'] =  $TeamCareerSumPlayoffOnly['SumOfPPAttemp'] + $TeamStat['PPAttemp'];
	$TeamCareerSumPlayoffOnly['SumOfPPGoal'] =  $TeamCareerSumPlayoffOnly['SumOfPPGoal'] + $TeamStat['PPGoal'];
	$TeamCareerSumPlayoffOnly['SumOfPKAttemp'] =  $TeamCareerSumPlayoffOnly['SumOfPKAttemp'] + $TeamStat['PKAttemp'];
	$TeamCareerSumPlayoffOnly['SumOfPKGoalGA'] =  $TeamCareerSumPlayoffOnly['SumOfPKGoalGA'] + $TeamStat['PKGoalGA'];
	$TeamCareerSumPlayoffOnly['SumOfPKGoalGF'] =  $TeamCareerSumPlayoffOnly['SumOfPKGoalGF'] + $TeamStat['PKGoalGF'];
	$TeamCareerSumPlayoffOnly['SumOfShotsFor'] =  $TeamCareerSumPlayoffOnly['SumOfShotsFor'] + $TeamStat['ShotsFor'];
	$TeamCareerSumPlayoffOnly['SumOfShotsAga'] =  $TeamCareerSumPlayoffOnly['SumOfShotsAga'] + $TeamStat['ShotsAga'];
	$TeamCareerSumPlayoffOnly['SumOfShotsBlock'] =  $TeamCareerSumPlayoffOnly['SumOfShotsBlock'] + $TeamStat['ShotsBlock'];
	$TeamCareerSumPlayoffOnly['SumOfShotsPerPeriod1'] =  $TeamCareerSumPlayoffOnly['SumOfShotsPerPeriod1'] + $TeamStat['ShotsPerPeriod1'];
	$TeamCareerSumPlayoffOnly['SumOfShotsPerPeriod2'] =  $TeamCareerSumPlayoffOnly['SumOfShotsPerPeriod2'] + $TeamStat['ShotsPerPeriod2'];
	$TeamCareerSumPlayoffOnly['SumOfShotsPerPeriod3'] =  $TeamCareerSumPlayoffOnly['SumOfShotsPerPeriod3'] + $TeamStat['ShotsPerPeriod3'];
	$TeamCareerSumPlayoffOnly['SumOfShotsPerPeriod4'] =  $TeamCareerSumPlayoffOnly['SumOfShotsPerPeriod4'] + $TeamStat['ShotsPerPeriod4'];
	$TeamCareerSumPlayoffOnly['SumOfGoalsPerPeriod1'] =  $TeamCareerSumPlayoffOnly['SumOfGoalsPerPeriod1'] + $TeamStat['GoalsPerPeriod1'];
	$TeamCareerSumPlayoffOnly['SumOfGoalsPerPeriod2'] =  $TeamCareerSumPlayoffOnly['SumOfGoalsPerPeriod2'] + $TeamStat['GoalsPerPeriod2'];
	$TeamCareerSumPlayoffOnly['SumOfGoalsPerPeriod3'] =  $TeamCareerSumPlayoffOnly['SumOfGoalsPerPeriod3'] + $TeamStat['GoalsPerPeriod3'];
	$TeamCareerSumPlayoffOnly['SumOfGoalsPerPeriod4'] =  $TeamCareerSumPlayoffOnly['SumOfGoalsPerPeriod4'] + $TeamStat['GoalsPerPeriod4'];
	$TeamCareerSumPlayoffOnly['SumOfPuckTimeInZoneDF'] =  $TeamCareerSumPlayoffOnly['SumOfPuckTimeInZoneDF'] + $TeamStat['PuckTimeInZoneDF'];
	$TeamCareerSumPlayoffOnly['SumOfPuckTimeInZoneOF'] =  $TeamCareerSumPlayoffOnly['SumOfPuckTimeInZoneOF'] + $TeamStat['PuckTimeInZoneOF'];
	$TeamCareerSumPlayoffOnly['SumOfPuckTimeInZoneNT'] =  $TeamCareerSumPlayoffOnly['SumOfPuckTimeInZoneNT'] + $TeamStat['PuckTimeInZoneNT'];
	$TeamCareerSumPlayoffOnly['SumOfPuckTimeControlinZoneDF'] =  $TeamCareerSumPlayoffOnly['SumOfPuckTimeControlinZoneDF'] + $TeamStat['PuckTimeControlinZoneDF'];
	$TeamCareerSumPlayoffOnly['SumOfPuckTimeControlinZoneOF'] =  $TeamCareerSumPlayoffOnly['SumOfPuckTimeControlinZoneOF'] + $TeamStat['PuckTimeControlinZoneOF'];
	$TeamCareerSumPlayoffOnly['SumOfPuckTimeControlinZoneNT'] =  $TeamCareerSumPlayoffOnly['SumOfPuckTimeControlinZoneNT'] + $TeamStat['PuckTimeControlinZoneNT'];
	$TeamCareerSumPlayoffOnly['SumOfShutouts'] =  $TeamCareerSumPlayoffOnly['SumOfShutouts'] + $TeamStat['Shutouts'];
	$TeamCareerSumPlayoffOnly['SumOfTotalGoal'] =  $TeamCareerSumPlayoffOnly['SumOfTotalGoal'] + $TeamStat['TotalGoal'];
	$TeamCareerSumPlayoffOnly['SumOfTotalAssist'] =  $TeamCareerSumPlayoffOnly['SumOfTotalAssist'] + $TeamStat['TotalAssist'];
	$TeamCareerSumPlayoffOnly['SumOfTotalPoint'] =  $TeamCareerSumPlayoffOnly['SumOfTotalPoint'] + $TeamStat['TotalPoint'];
	$TeamCareerSumPlayoffOnly['SumOfPim'] =  $TeamCareerSumPlayoffOnly['SumOfPim'] + $TeamStat['Pim'];
	$TeamCareerSumPlayoffOnly['SumOfHits'] =  $TeamCareerSumPlayoffOnly['SumOfHits'] + $TeamStat['Hits'];
	$TeamCareerSumPlayoffOnly['SumOfFaceOffWonDefensifZone'] =  $TeamCareerSumPlayoffOnly['SumOfFaceOffWonDefensifZone'] + $TeamStat['FaceOffWonDefensifZone'];
	$TeamCareerSumPlayoffOnly['SumOfFaceOffTotalDefensifZone'] =  $TeamCareerSumPlayoffOnly['SumOfFaceOffTotalDefensifZone'] + $TeamStat['FaceOffTotalDefensifZone'];
	$TeamCareerSumPlayoffOnly['SumOfFaceOffWonOffensifZone'] =  $TeamCareerSumPlayoffOnly['SumOfFaceOffWonOffensifZone'] + $TeamStat['FaceOffWonOffensifZone'];
	$TeamCareerSumPlayoffOnly['SumOfFaceOffTotalOffensifZone'] =  $TeamCareerSumPlayoffOnly['SumOfFaceOffTotalOffensifZone'] + $TeamStat['FaceOffTotalOffensifZone'];
	$TeamCareerSumPlayoffOnly['SumOfFaceOffWonNeutralZone'] =  $TeamCareerSumPlayoffOnly['SumOfFaceOffWonNeutralZone'] + $TeamStat['FaceOffWonNeutralZone'];
	$TeamCareerSumPlayoffOnly['SumOfFaceOffTotalNeutralZone'] =  $TeamCareerSumPlayoffOnly['SumOfFaceOffTotalNeutralZone'] + $TeamStat['FaceOffTotalNeutralZone'];
	$TeamCareerSumPlayoffOnly['SumOfEmptyNetGoal'] =  $TeamCareerSumPlayoffOnly['SumOfEmptyNetGoal'] + $TeamStat['EmptyNetGoal'];
}
if ($TeamCareerSumPlayoffOnly['SumOfGP'] > 0){
	/* Show TeamCareer Total for Playoff */
	echo "<tr class=\"static\"><td class=\"staticTD\"><strong>" . $PlayersLang['Total'] . " " . $PlayersLang['Playoff']. "</strong></td>";
	echo "<td class=\"staticTD\">" . $TeamCareerSumPlayoffOnly['SumOfGP'] . "</td>";
	echo "<td class=\"staticTD\">" . $TeamCareerSumPlayoffOnly['SumOfW']  . "</td>";
	echo "<td class=\"staticTD\">" . $TeamCareerSumPlayoffOnly['SumOfL'] . "</td>";
	echo "<td class=\"staticTD\">" . $TeamCareerSumPlayoffOnly['SumOfT'] . "</td>";
	echo "<td class=\"staticTD\">" . $TeamCareerSumPlayoffOnly['SumOfOTW'] . "</td>";	
	echo "<td class=\"staticTD\">" . $TeamCareerSumPlayoffOnly['SumOfOTL'] . "</td>";	
	echo "<td class=\"staticTD\">" . $TeamCareerSumPlayoffOnly['SumOfSOW'] . "</td>";	
	echo "<td class=\"staticTD\">" . $TeamCareerSumPlayoffOnly['SumOfSOL'] . "</td>";	
	echo "<td class=\"staticTD\">" . $TeamCareerSumPlayoffOnly['SumOfGF'] . "</td>";
	echo "<td class=\"staticTD\">" . $TeamCareerSumPlayoffOnly['SumOfGA'] . "</td>";
	echo "<td class=\"staticTD\">" . ($TeamCareerSumPlayoffOnly['SumOfGF'] - $TeamCareerSumPlayoffOnly['SumOfGA']) . "</td>";	
	echo "<td class=\"staticTD\">" . $TeamCareerSumPlayoffOnly['SumOfHomeGP'] . "</td>";
	echo "<td class=\"staticTD\">" . $TeamCareerSumPlayoffOnly['SumOfHomeW']  . "</td>";
	echo "<td class=\"staticTD\">" . $TeamCareerSumPlayoffOnly['SumOfHomeL'] . "</td>";
	echo "<td class=\"staticTD\">" . $TeamCareerSumPlayoffOnly['SumOfHomeT'] . "</td>";
	echo "<td class=\"staticTD\">" . $TeamCareerSumPlayoffOnly['SumOfHomeOTW'] . "</td>";	
	echo "<td class=\"staticTD\">" . $TeamCareerSumPlayoffOnly['SumOfHomeOTL'] . "</td>";	
	echo "<td class=\"staticTD\">" . $TeamCareerSumPlayoffOnly['SumOfHomeSOW'] . "</td>";	
	echo "<td class=\"staticTD\">" . $TeamCareerSumPlayoffOnly['SumOfHomeSOL'] . "</td>";	
	echo "<td class=\"staticTD\">" . $TeamCareerSumPlayoffOnly['SumOfHomeGF'] . "</td>";
	echo "<td class=\"staticTD\">" . $TeamCareerSumPlayoffOnly['SumOfHomeGA'] . "</td>";	
	echo "<td class=\"staticTD\">" . ($TeamCareerSumPlayoffOnly['SumOfHomeGF'] - $TeamCareerSumPlayoffOnly['SumOfHomeGA']) . "</td>";	
	echo "<td class=\"staticTD\">" . ($TeamCareerSumPlayoffOnly['SumOfGP'] - $TeamCareerSumPlayoffOnly['SumOfHomeGP']) . "</td>";
	echo "<td class=\"staticTD\">" . ($TeamCareerSumPlayoffOnly['SumOfW'] - $TeamCareerSumPlayoffOnly['SumOfHomeW']) . "</td>";
	echo "<td class=\"staticTD\">" . ($TeamCareerSumPlayoffOnly['SumOfL'] - $TeamCareerSumPlayoffOnly['SumOfHomeL']) . "</td>";
	echo "<td class=\"staticTD\">" . ($TeamCareerSumPlayoffOnly['SumOfT'] - $TeamCareerSumPlayoffOnly['SumOfHomeT']) . "</td>";	
	echo "<td class=\"staticTD\">" . ($TeamCareerSumPlayoffOnly['SumOfOTW'] - $TeamCareerSumPlayoffOnly['SumOfHomeOTW']) . "</td>";
	echo "<td class=\"staticTD\">" . ($TeamCareerSumPlayoffOnly['SumOfOTL'] - $TeamCareerSumPlayoffOnly['SumOfHomeOTL']) . "</td>";
	echo "<td class=\"staticTD\">" . ($TeamCareerSumPlayoffOnly['SumOfSOW'] - $TeamCareerSumPlayoffOnly['SumOfHomeSOW']) . "</td>";
	echo "<td class=\"staticTD\">" . ($TeamCareerSumPlayoffOnly['SumOfSOL'] - $TeamCareerSumPlayoffOnly['SumOfHomeSOL']) . "</td>";
	echo "<td class=\"staticTD\">" . ($TeamCareerSumPlayoffOnly['SumOfGF'] - $TeamCareerSumPlayoffOnly['SumOfHomeGF']) . "</td>";
	echo "<td class=\"staticTD\">" . ($TeamCareerSumPlayoffOnly['SumOfGA'] - $TeamCareerSumPlayoffOnly['SumOfHomeGA']) . "</td>";
	echo "<td class=\"staticTD\">" . (($TeamCareerSumPlayoffOnly['SumOfGF'] - $TeamCareerSumPlayoffOnly['SumOfHomeGF']) - ($TeamCareerSumPlayoffOnly['SumOfGA'] - $TeamCareerSumPlayoffOnly['SumOfHomeGA'])) . "</td>";		
	echo "<td class=\"staticTD\"><strong>" . $TeamCareerSumPlayoffOnly['SumOfPoints'] . "</strong></td>";
	echo "<td class=\"staticTD\">" . $TeamCareerSumPlayoffOnly['SumOfTotalGoal'] . "</td>";
	echo "<td class=\"staticTD\">" . $TeamCareerSumPlayoffOnly['SumOfTotalAssist'] . "</td>";
	echo "<td class=\"staticTD\">" . $TeamCareerSumPlayoffOnly['SumOfTotalPoint'] . "</td>";
	echo "<td class=\"staticTD\">" . $TeamCareerSumPlayoffOnly['SumOfEmptyNetGoal']. "</td>";
	echo "<td class=\"staticTD\">" . $TeamCareerSumPlayoffOnly['SumOfShutouts']. "</td>";		
	echo "<td class=\"staticTD\">" . $TeamCareerSumPlayoffOnly['SumOfGoalsPerPeriod1']. "</td>";		
	echo "<td class=\"staticTD\">" . $TeamCareerSumPlayoffOnly['SumOfGoalsPerPeriod2']. "</td>";	
	echo "<td class=\"staticTD\">" . $TeamCareerSumPlayoffOnly['SumOfGoalsPerPeriod3']. "</td>";	
	echo "<td class=\"staticTD\">" . $TeamCareerSumPlayoffOnly['SumOfGoalsPerPeriod4']. "</td>";	
	echo "<td class=\"staticTD\">" . $TeamCareerSumPlayoffOnly['SumOfShotsFor']. "</td>";	
	echo "<td class=\"staticTD\">" . $TeamCareerSumPlayoffOnly['SumOfShotsPerPeriod1']. "</td>";
	echo "<td class=\"staticTD\">" . $TeamCareerSumPlayoffOnly['SumOfShotsPerPeriod2']. "</td>";
	echo "<td class=\"staticTD\">" . $TeamCareerSumPlayoffOnly['SumOfShotsPerPeriod3']. "</td>";
	echo "<td class=\"staticTD\">" . $TeamCareerSumPlayoffOnly['SumOfShotsPerPeriod4']. "</td>";
	echo "<td class=\"staticTD\">" . $TeamCareerSumPlayoffOnly['SumOfShotsAga']. "</td>";
	echo "<td class=\"staticTD\">" . $TeamCareerSumPlayoffOnly['SumOfShotsBlock']. "</td>";		
	echo "<td class=\"staticTD\">" . $TeamCareerSumPlayoffOnly['SumOfPim']. "</td>";
	echo "<td class=\"staticTD\">" . $TeamCareerSumPlayoffOnly['SumOfHits']. "</td>";	
	echo "<td class=\"staticTD\">" . $TeamCareerSumPlayoffOnly['SumOfPPAttemp']. "</td>";
	echo "<td class=\"staticTD\">" . $TeamCareerSumPlayoffOnly['SumOfPPGoal']. "</td>";
	echo "<td class=\"staticTD\">";if ($TeamCareerSumPlayoffOnly['SumOfPPAttemp'] > 0){echo number_Format($TeamCareerSumPlayoffOnly['SumOfPPGoal'] / $TeamCareerSumPlayoffOnly['SumOfPPAttemp'] * 100,2) . "%";} else { echo "0.00%";} echo "</td>";		
	echo "<td class=\"staticTD\">" . $TeamCareerSumPlayoffOnly['SumOfPKAttemp']. "</td>";
	echo "<td class=\"staticTD\">" . $TeamCareerSumPlayoffOnly['SumOfPKGoalGA']. "</td>";
	echo "<td class=\"staticTD\">";if ($TeamCareerSumPlayoffOnly['SumOfPKAttemp'] > 0){echo number_Format(($TeamCareerSumPlayoffOnly['SumOfPKAttemp'] - $TeamCareerSumPlayoffOnly['SumOfPKGoalGA']) / $TeamCareerSumPlayoffOnly['SumOfPKAttemp'] * 100,2) . "%";} else {echo "0.00%";} echo "</td>";
	echo "<td class=\"staticTD\">" .  $TeamCareerSumPlayoffOnly['SumOfPKGoalGF']. "</td>";	
	echo "<td class=\"staticTD\">" . $TeamCareerSumPlayoffOnly['SumOfFaceOffWonOffensifZone']. "</td>";
	echo "<td class=\"staticTD\">" . $TeamCareerSumPlayoffOnly['SumOfFaceOffTotalOffensifZone']. "</td>";		
	echo "<td class=\"staticTD\">";if ($TeamCareerSumPlayoffOnly['SumOfFaceOffTotalOffensifZone'] > 0){echo number_Format($TeamCareerSumPlayoffOnly['SumOfFaceOffWonOffensifZone'] / $TeamCareerSumPlayoffOnly['SumOfFaceOffTotalOffensifZone'] * 100,2) . "%" ;} else { echo "0.00%";} echo "</td>";	
	echo "<td class=\"staticTD\">" . $TeamCareerSumPlayoffOnly['SumOfFaceOffWonDefensifZone']. "</td>";
	echo "<td class=\"staticTD\">" . $TeamCareerSumPlayoffOnly['SumOfFaceOffTotalDefensifZone']. "</td>";
	echo "<td class=\"staticTD\">";if ($TeamCareerSumPlayoffOnly['SumOfFaceOffTotalDefensifZone'] > 0){echo number_Format($TeamCareerSumPlayoffOnly['SumOfFaceOffWonDefensifZone'] / $TeamCareerSumPlayoffOnly['SumOfFaceOffTotalDefensifZone'] * 100,2) . "%" ;} else { echo "0.00%";} echo "</td>";	
	echo "<td class=\"staticTD\">" . $TeamCareerSumPlayoffOnly['SumOfFaceOffWonNeutralZone']. "</td>";	
	echo "<td class=\"staticTD\">" . $TeamCareerSumPlayoffOnly['SumOfFaceOffTotalNeutralZone']. "</td>";	
	echo "<td class=\"staticTD\">";if ($TeamCareerSumPlayoffOnly['SumOfFaceOffTotalNeutralZone'] > 0){echo number_Format($TeamCareerSumPlayoffOnly['SumOfFaceOffWonNeutralZone'] / $TeamCareerSumPlayoffOnly['SumOfFaceOffTotalNeutralZone'] * 100,2) . "%" ;} else { echo "0.00%";} echo "</td>";	
	echo "<td class=\"staticTD\">" . Floor($TeamCareerSumPlayoffOnly['SumOfPuckTimeInZoneOF']/60). "</td>";
	echo "<td class=\"staticTD\">" . Floor($TeamCareerSumPlayoffOnly['SumOfPuckTimeControlinZoneOF']/60). "</td>";
	echo "<td class=\"staticTD\">" . Floor($TeamCareerSumPlayoffOnly['SumOfPuckTimeInZoneDF']/60). "</td>";
	echo "<td class=\"staticTD\">" . Floor($TeamCareerSumPlayoffOnly['SumOfPuckTimeControlinZoneDF']/60). "</td>";
	echo "<td class=\"staticTD\">" . Floor($TeamCareerSumPlayoffOnly['SumOfPuckTimeInZoneNT']/60). "</td>";		
	echo "<td class=\"staticTD\">" . Floor($TeamCareerSumPlayoffOnly['SumOfPuckTimeControlinZoneNT']/60). "</td>";		
	echo "</tr>\n"; /* The \n is for a new line in the HTML Code */	
}
}?>
</tbody></table>
<br />
<h1><?php echo $TeamName . $TeamLang['CareerPlayerLeaderPlayoff'];?></h1>
<div class="tablesorter_ColumnSelectorWrapper">
    <input id="tablesorter_colSelect11PlayoffP" type="checkbox" class="hidden">
    <label class="tablesorter_ColumnSelectorButton" for="tablesorter_colSelect11PlayoffP"><?php echo $TableSorterLang['ShoworHideColumn'];?></label>
    <div id="tablesorter_ColumnSelector11PlayoffP" class="tablesorter_ColumnSelector"></div>
</div>

<table class="tablesorter STHSPHPTeam_TeamCareerPlayersPlayoffTop5"><thead><tr>
<th data-priority="3" title="Order Number" class="STHSW10 sorter-false">#</th>
<th data-priority="critical" title="Player Name" class="STHSW140Min"><?php If (isset($PlayersLang) == True){echo $PlayersLang['PlayerName'];}?></th>
<th data-priority="1" title="Games Played" class="STHSW25">GP</th>
<th data-priority="1" title="Goals" class="STHSW25">G</th>
<th data-priority="1" title="Assists" class="STHSW25">A</th>
<th data-priority="1" title="Points" class="STHSW25">P</th>
<th data-priority="2" title="Plus/Minus" class="STHSW25">+/-</th>
<th data-priority="2" title="Penalty Minutes" class="STHSW25">PIM</th>
<th data-priority="2" title="Hits" class="STHSW25">HIT</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Hits Received">HTT</th>
<th data-priority="2" title="Shots" class="STHSW25">SHT</th>
<th data-priority="3" title="Shooting Percentage" class="STHSW55">SHT%</th>
<th data-priority="3" title="Shots Blocked" class="STHSW25">SB</th>
<th data-priority="3" title="Minutes Played" class="STHSW35">MP</th>
<th data-priority="3" title="Average Minutes Played per Game" class="STHSW35">AMG</th>
<th data-priority="4" title="Power Play Goals" class="STHSW25">PPG</th>
<th data-priority="4" title="Power Play Assists" class="STHSW25">PPA</th>
<th data-priority="4" title="Power Play Points" class="STHSW25">PPP</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Power Play Shots">PPS</th>
<th data-priority="5" title="Short Handed Goals" class="STHSW25">PKG</th>
<th data-priority="5" title="Short Handed Assists" class="STHSW25">PKA</th>
<th data-priority="5" title="Short Handed Points" class="STHSW25">PKP</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Penalty Kill Shots">PKS</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Game Winning Goals">GW</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Game Tying Goals">GT</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Face offs Percentage">FO%</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Hat Tricks">HT</th>
<th data-priority="4" title="Points per 20 Minutes" class="STHSW25">P/20</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Penalty Shots Goals">PSG</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Penalty Shots Taken">PSS</th>
</tr></thead><tbody>
<?php 
$Order = 0;
if (empty($TeamCareerPlayersPlayoffTop5) == false){while ($Row = $TeamCareerPlayersPlayoffTop5 ->fetchArray()) {
	$Order +=1;
	echo "<tr><td>" . $Order ."</td>";
	If ($Row['Number'] != Null){
		echo "<td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . "</a></td>";
	}else{
		echo "<td>" . $Row['Name'] . "</td>";
	}	

	echo "<td>" . $Row['GP'] . "</td>";
	echo "<td>" . $Row['G'] . "</td>";
	echo "<td>" . $Row['A'] . "</td>";
	echo "<td>" . $Row['P'] . "</td>";
	echo "<td>" . $Row['PlusMinus'] . "</td>";
	echo "<td>" . $Row['Pim'] . "</td>";
	echo "<td>" . $Row['Hits'] . "</td>";	
	echo "<td>" . $Row['HitsTook'] . "</td>";		
	echo "<td>" . $Row['Shots'] . "</td>";
	echo "<td>" . number_Format($Row['ShotsPCT'],2) . "%</td>";		
	echo "<td>" . $Row['ShotsBlock'] . "</td>";	
	echo "<td>" . Floor($Row['SecondPlay']/60) . "</td>";
	echo "<td>" . number_Format($Row['AMG'],2) . "</td>";		
	echo "<td>" . $Row['PPG'] . "</td>";
	echo "<td>" . $Row['PPA'] . "</td>";
	echo "<td>" . $Row['PPP'] . "</td>";
	echo "<td>" . $Row['PPShots'] . "</td>";
	echo "<td>" . $Row['PKG'] . "</td>";
	echo "<td>" . $Row['PKA'] . "</td>";
	echo "<td>" . $Row['PKP'] . "</td>";
	echo "<td>" . $Row['PKShots'] . "</td>";
	echo "<td>" . $Row['GW'] . "</td>";
	echo "<td>" . $Row['GT'] . "</td>";
	echo "<td>" . number_Format($Row['FaceoffPCT'],2) . "%</td>";	
	echo "<td>" . $Row['HatTrick'] . "</td>";	
	echo "<td>" . number_Format($Row['P20'],2) . "</td>";			
	echo "<td>" . $Row['PenalityShotsScore'] . "</td>";
	echo "<td>" . $Row['PenalityShotsTotal'] . "</td>";
	echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
}}
?>
</tbody></table>

<br /><h1><?php echo $TeamName . $TeamLang['CareerGoaliesLeaderPlayoff'];?></h1>
<div class="tablesorter_ColumnSelectorWrapper">
    <input id="tablesorter_colSelect11PlayoffG" type="checkbox" class="hidden">
    <label class="tablesorter_ColumnSelectorButton" for="tablesorter_colSelect11PlayoffG"><?php echo $TableSorterLang['ShoworHideColumn'];?></label>
    <div id="tablesorter_ColumnSelector11PlayoffG" class="tablesorter_ColumnSelector"></div>
</div>

<table class="tablesorter STHSPHPTeam_TeamCareerGoaliesPlayoffTop5"><thead><tr>
<th data-priority="3" title="Order Number" class="STHSW10 sorter-false">#</th>
<th data-priority="critical" title="Goalie Name" class="STHSW140Min"><?php If (isset($PlayersLang) == True){echo $PlayersLang['GoalieName'];}?></th>
<th data-priority="1" title="Games Played" class="STHSW25">GP</th>
<th data-priority="1" title="Wins" class="STHSW25">W</th>
<th data-priority="2" title="Losses" class="STHSW25">L</th>
<th data-priority="2" title="Overtime Losses" class="STHSW25">OTL</th>
<th data-priority="critical" title="Save Percentage" class="STHSW50">PCT</th>
<th data-priority="critical" title="Goals Against Average" class="STHSW50">GAA</th>
<th data-priority="3" title="Minutes Played" class="STHSW50">MP</th>
<th data-priority="5" title="Penalty Minutes" class="STHSW25">PIM</th>
<th data-priority="4" title="Shutouts" class="STHSW25">SO</th>
<th data-priority="3" title="Goals Against" class="STHSW25">GA</th>
<th data-priority="3" title="Shots Against" class="STHSW45">SA</th>
<th data-priority="4" title="Shots Against Rebound" class="STHSW45">SAR</th>
<th class="columnSelector-false STHSW25" data-priority="6"  title="Assists">A</th>
<th class="columnSelector-false STHSW25" data-priority="6"  title="Empty net Goals">EG</th>
<th data-priority="4" title="Penalty Shots Save %" class="STHSW50">PS %</th>
<th data-priority="5" title="Penalty Shots Against" class="STHSW25">PSA</th>
</tr></thead><tbody>
<?php
$Order = 0;
if (empty($TeamCareerGoaliesPlayoffTop5) == false){while ($Row = $TeamCareerGoaliesPlayoffTop5 ->fetchArray()) {
	$Order +=1;
	echo "<tr><td>" . $Order ."</td>";
	If ($Row['Number'] != Null){
		echo "<td><a href=\"GoalieReport.php?Goalie=" . $Row['Number'] . "\">" . $Row['Name'] . "</a></td>";
	}else{
		echo "<td>" . $Row['Name'] . "</td>";
	}
	echo "<td>" . $Row['GP'] . "</td>";
	echo "<td>" . $Row['W'] . "</td>";
	echo "<td>" . $Row['L'] . "</td>";
	echo "<td>" . $Row['OTL'] . "</td>";
	echo "<td>" . number_Format($Row['PCT'],3) . "</td>";
	echo "<td>" . number_Format($Row['GAA'],2) . "</td>";
	echo "<td>";if ($Row <> Null){echo Floor($Row['SecondPlay']/60);}; echo "</td>";
	echo "<td>" . $Row['Pim'] . "</td>";
	echo "<td>" . $Row['Shootout'] . "</td>";
	echo "<td>" . $Row['GA'] . "</td>";
	echo "<td>" . $Row['SA'] . "</td>";
	echo "<td>" . $Row['SARebound'] . "</td>";
	echo "<td>" . $Row['A'] . "</td>";
	echo "<td>" . $Row['EmptyNetGoal'] . "</td>";			
	echo "<td>" . number_Format($Row['PenalityShotsPCT'],3) . "</td>";
	echo "<td>" . $Row['PenalityShotsShots'] . "</td>";
	echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
}}
?>
</tbody></table>
<br /><br /></div>


</div>
</div>
</div>

<script>
$(function(){
  $.tablesorter.addWidget({ id: "numbering",format: function(table) {var c = table.config;$("tr:visible", table.tBodies[0]).each(function(i) {$(this).find('td').eq(0).text(i + 1);});}});
  $(".STHSPHPTeam_PlayersRosterTable").tablesorter({
    widgets: ['columnSelector', 'stickyHeaders', 'filter'],
    widgetOptions : {
      columnSelector_container : $('#tablesorter_ColumnSelector1P'),
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
      filter_reset: '.tablesorter_Reset'	  
    }
  }); 
  $(".STHSPHPTeam_GoaliesRosterTable").tablesorter({
    widgets: ['columnSelector', 'stickyHeaders', 'filter'],
    widgetOptions : {
      columnSelector_container : $('#tablesorter_ColumnSelector1G'),
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
      filter_reset: '.tablesorter_Reset'	  
    }
  });
  $(".STHSPHPTeam_PlayerInfoTable").tablesorter({
    widgets: ['columnSelector', 'stickyHeaders', 'filter'],
    widgetOptions : {
      columnSelector_container : $('#tablesorter_ColumnSelector3'),
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
      filter_reset: '.tablesorter_Reset'
    }
  });
  $(".STHSPHPTeam_ScheduleTable").tablesorter({
    widgets: ['columnSelector', 'stickyHeaders', 'filter'],
    widgetOptions : {
      columnSelector_container : $('#tablesorter_ColumnSelector6'),
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
      filter_reset: '.tablesorter_Reset'		  
    }
  });  
  $(".STHSPHPTeam_PlayersScoringTable").tablesorter({
    widgets: ['numbering', 'columnSelector', 'stickyHeaders', 'filter'],
    widgetOptions : {
      columnSelector_container : $('#tablesorter_ColumnSelector2P'),
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
      filter_reset: '.tablesorter_Reset'		  
    }
  }); 
  $(".STHSPHPTeam_GoaliesScoringTable").tablesorter({
    widgets: ['numbering', 'columnSelector', 'stickyHeaders', 'filter'],
    widgetOptions : {
      columnSelector_container : $('#tablesorter_ColumnSelector2G'),
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
      filter_reset: '.tablesorter_Reset'		  
    }
  });
      $(".STHSPHPTeamsStatSub_Table").tablesorter({
    widgets: ['columnSelector', 'stickyHeaders', 'filter'],
    widgetOptions : {
      columnSelector_container : $('#tablesorter_ColumnSelector5'),
      columnSelector_layout : '<label><input type="checkbox">{name}</label>',
      columnSelector_name  : 'title',
      columnSelector_mediaquery: true,
      columnSelector_mediaqueryName: 'Automatic',
      columnSelector_mediaqueryState: true,
      columnSelector_mediaqueryHidden: true,
      columnSelector_breakpoints : [ '20em', '40em', '70em', '80em', '90em', '95em' ],
	  filter_columnFilters: true,
      filter_placeholder: { search : '<?php echo $TableSorterLang['Search'];?>' },
	  filter_searchDelay : 500,	  
      filter_reset: '.tablesorter_Reset'	 
    }
  });
    <?php if ($TeamCareerStatFound == true){
	  echo "\$(\".STHSPHPTeam_TeamCareerStat\").tablesorter({widgets: ['staticRow', 'columnSelector','filter'], widgetOptions : {columnSelector_container : \$('#tablesorter_ColumnSelector11'), columnSelector_layout : '<label><input type=\"checkbox\">{name}</label>', columnSelector_name  : 'title', columnSelector_mediaquery: true, columnSelector_mediaqueryName: 'Automatic', columnSelector_mediaqueryState: true, columnSelector_mediaqueryHidden: true, columnSelector_breakpoints : [ '20em', '40em', '60em', '80em', '90em', '95em' ],filter_columnFilters: false,}});";
	  echo "\$(\".STHSPHPTeam_TeamCareerPlayersSeasonTop5\").tablesorter({widgets: ['staticRow', 'columnSelector','filter'], widgetOptions : {columnSelector_container : \$('#tablesorter_ColumnSelector11SeasonP'), columnSelector_layout : '<label><input type=\"checkbox\">{name}</label>', columnSelector_name  : 'title', columnSelector_mediaquery: true, columnSelector_mediaqueryName: 'Automatic', columnSelector_mediaqueryState: true, columnSelector_mediaqueryHidden: true, columnSelector_breakpoints : [ '20em', '40em', '60em', '80em', '90em', '95em' ],filter_columnFilters: false,}});";
	  echo "\$(\".STHSPHPTeam_TeamCareerGoaliesSeasonTop5\").tablesorter({widgets: ['staticRow', 'columnSelector','filter'], widgetOptions : {columnSelector_container : \$('#tablesorter_ColumnSelector11SeasonG'), columnSelector_layout : '<label><input type=\"checkbox\">{name}</label>', columnSelector_name  : 'title', columnSelector_mediaquery: true, columnSelector_mediaqueryName: 'Automatic', columnSelector_mediaqueryState: true, columnSelector_mediaqueryHidden: true, columnSelector_breakpoints : [ '20em', '40em', '60em', '80em', '90em', '95em' ],filter_columnFilters: false,}});";
	  echo "\$(\".STHSPHPTeam_TeamCareerPlayersPlayoffTop5\").tablesorter({widgets: ['staticRow', 'columnSelector','filter'], widgetOptions : {columnSelector_container : \$('#tablesorter_ColumnSelector11PlayoffP'), columnSelector_layout : '<label><input type=\"checkbox\">{name}</label>', columnSelector_name  : 'title', columnSelector_mediaquery: true, columnSelector_mediaqueryName: 'Automatic', columnSelector_mediaqueryState: true, columnSelector_mediaqueryHidden: true, columnSelector_breakpoints : [ '20em', '40em', '60em', '80em', '90em', '95em' ],filter_columnFilters: false,}});";
	  echo "\$(\".STHSPHPTeam_TeamCareerGoaliesPlayoffTop5\").tablesorter({widgets: ['staticRow', 'columnSelector','filter'], widgetOptions : {columnSelector_container : \$('#tablesorter_ColumnSelector11PlayoffG'), columnSelector_layout : '<label><input type=\"checkbox\">{name}</label>', columnSelector_name  : 'title', columnSelector_mediaquery: true, columnSelector_mediaqueryName: 'Automatic', columnSelector_mediaqueryState: true, columnSelector_mediaqueryHidden: true, columnSelector_breakpoints : [ '20em', '40em', '60em', '80em', '90em', '95em' ],filter_columnFilters: false,}});";	
   }?>
});
function Game1() {
    var x = document.getElementById('Game1');
	if (x.style.display === 'none') {x.style.display = 'table';} else {x.style.display = 'none';}
}
function Game2() {
    var x = document.getElementById('Game2');
    if (x.style.display === 'none') {x.style.display = 'table';} else {x.style.display = 'none';}
}
function Game3() {
    var x = document.getElementById('Game3');
    if (x.style.display === 'none') {x.style.display = 'table';} else {x.style.display = 'none';}
}
function Game4() {
    var x = document.getElementById('Game4');
    if (x.style.display === 'none') {x.style.display = 'table';} else {x.style.display = 'none';}
}
function Game5() {
    var x = document.getElementById('Game5');
    if (x.style.display === 'none') {x.style.display = 'table';} else {x.style.display = 'none';}
}
</script>

<?php include "Footer.php";?>