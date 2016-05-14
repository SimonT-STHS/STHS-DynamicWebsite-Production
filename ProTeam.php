<!DOCTYPE html>
<?php include "Header.php";?>
<?php
/*
Syntax to call this webpage should be ProTeam.php?Team=2 where only the number change and it's based on the Tean Number Field.
*/

$Team = (integer)0;
$TypeText = (string)"Pro";
$LeagueName = (string)"";
$TeamCareerStatFound = (boolean)false;
$OtherTeam = (integer)0;
$Query = (string)"";
$TeamName = $TeamLang['IncorrectTeam'];
if(isset($_GET['Team'])){$Team = filter_var($_GET['Team'], FILTER_SANITIZE_NUMBER_INT);} 

If (file_exists($DatabaseFile) == false){
	$Team = 0;
	$TeamName = $DatabaseNotFound;
}else{
	$db = new SQLite3($DatabaseFile);
}
If ($Team == 0){
	$TeamInfo = Null;
	$TeamFarmInfo = Null;		
	$TeamFinance = Null;
	$TeamFarmFinance = Null;	
	$TeamStat = Null;
	$PlayerRoster = Null;
	$PlayerInfo = Null;
	$PlayerRosterAverage = Null;	
	$GoalieRosterAverage = Null;	
	$PlayerInfoAverage = Null;
	$PlayerStat = Null;
	$GoalieStat = Null;
	$GoalieRoster = Null;
	$Schedule= Null;
	$CoachInfo = Null;	
	$RivalryInfo = Null;	
	$LeagueGeneral = Null;
	$LeagueFinance = Null;	
	$LeagueWebClient = Null;	
	$LeagueOutputOption = Null;	
	$TeamLines = Null;
	$TeamLog = Null;	
	$TeamProspects = Null;
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
	echo "<style type=\"text/css\">.STHSPHPTeamStat_Main {display:none;}</style>";
}else{
	$Query = "SELECT count(*) AS count FROM TeamProInfo WHERE Number = " . $Team;
	$Result = $db->querySingle($Query,true);
	If ($Result['count'] == 1){
		$Query = "SELECT * FROM TeamProInfo WHERE Number = " . $Team;
		$TeamInfo = $db->querySingle($Query,true);
		$Query = "SELECT Name FROM TeamFarmInfo WHERE Number = " . $Team;
		$TeamFarmInfo = $db->querySingle($Query,true);			
		$Query = "SELECT * FROM TeamProFinance WHERE Number = " . $Team;
		$TeamFinance = $db->querySingle($Query,true);
		$Query = "SELECT EstimatedSeasonExpense FROM TeamFarmFinance WHERE Number = " . $Team;
		$TeamFarmFinance = $db->querySingle($Query,true);		
		$Query = "SELECT * FROM TeamProStat WHERE Number = " . $Team;
		$TeamStat = $db->querySingle($Query,true);
		$Query = "SELECT MainTable.* FROM (SELECT TeamProStatVS.TeamVSName AS Name, TeamProStatVS.TeamVSNumber AS Number, TeamProStatVS.GP, TeamProStatVS.W, TeamProStatVS.L, TeamProStatVS.T, TeamProStatVS.OTW, TeamProStatVS.OTL, TeamProStatVS.SOW, TeamProStatVS.SOL, TeamProStatVS.Points, TeamProStatVS.GF, TeamProStatVS.GA, TeamProStatVS.HomeGP, TeamProStatVS.HomeW, TeamProStatVS.HomeL, TeamProStatVS.HomeT, TeamProStatVS.HomeOTW, TeamProStatVS.HomeOTL, TeamProStatVS.HomeSOW, TeamProStatVS.HomeSOL, TeamProStatVS.HomeGF, TeamProStatVS.HomeGA, TeamProStatVS.PPAttemp, TeamProStatVS.PPGoal, TeamProStatVS.PKAttemp, TeamProStatVS.PKGoalGA, TeamProStatVS.PKGoalGF, TeamProStatVS.ShotsFor, TeamProStatVS.ShotsAga, TeamProStatVS.ShotsBlock, TeamProStatVS.ShotsPerPeriod1, TeamProStatVS.ShotsPerPeriod2, TeamProStatVS.ShotsPerPeriod3, TeamProStatVS.ShotsPerPeriod4, TeamProStatVS.GoalsPerPeriod1, TeamProStatVS.GoalsPerPeriod2, TeamProStatVS.GoalsPerPeriod3, TeamProStatVS.GoalsPerPeriod4, TeamProStatVS.PuckTimeInZoneDF, TeamProStatVS.PuckTimeInZoneOF, TeamProStatVS.PuckTimeInZoneNT, TeamProStatVS.PuckTimeControlinZoneDF, TeamProStatVS.PuckTimeControlinZoneOF, TeamProStatVS.PuckTimeControlinZoneNT, TeamProStatVS.Shutouts, TeamProStatVS.TotalGoal, TeamProStatVS.TotalAssist, TeamProStatVS.TotalPoint, TeamProStatVS.Pim, TeamProStatVS.Hits, TeamProStatVS.FaceOffWonDefensifZone, TeamProStatVS.FaceOffTotalDefensifZone, TeamProStatVS.FaceOffWonOffensifZone, TeamProStatVS.FaceOffTotalOffensifZone, TeamProStatVS.FaceOffWonNeutralZone, TeamProStatVS.FaceOffTotalNeutralZone, TeamProStatVS.EmptyNetGoal FROM TeamProStatVS WHERE GP > 0 AND TeamNumber = " . $Team . " UNION ALL SELECT 'Total' as Name, '104' as Number, TeamProStat.GP, TeamProStat.W, TeamProStat.L, TeamProStat.T, TeamProStat.OTW, TeamProStat.OTL, TeamProStat.SOW, TeamProStat.SOL, TeamProStat.Points, TeamProStat.GF, TeamProStat.GA, TeamProStat.HomeGP, TeamProStat.HomeW, TeamProStat.HomeL, TeamProStat.HomeT, TeamProStat.HomeOTW, TeamProStat.HomeOTL, TeamProStat.HomeSOW, TeamProStat.HomeSOL, TeamProStat.HomeGF, TeamProStat.HomeGA,  TeamProStat.PPAttemp, TeamProStat.PPGoal, TeamProStat.PKAttemp, TeamProStat.PKGoalGA, TeamProStat.PKGoalGF, TeamProStat.ShotsFor, TeamProStat.ShotsAga, TeamProStat.ShotsBlock, TeamProStat.ShotsPerPeriod1, TeamProStat.ShotsPerPeriod2, TeamProStat.ShotsPerPeriod3, TeamProStat.ShotsPerPeriod4, TeamProStat.GoalsPerPeriod1, TeamProStat.GoalsPerPeriod2, TeamProStat.GoalsPerPeriod3, TeamProStat.GoalsPerPeriod4, TeamProStat.PuckTimeInZoneDF, TeamProStat.PuckTimeInZoneOF, TeamProStat.PuckTimeInZoneNT, TeamProStat.PuckTimeControlinZoneDF, TeamProStat.PuckTimeControlinZoneOF, TeamProStat.PuckTimeControlinZoneNT, TeamProStat.Shutouts, TeamProStat.TotalGoal, TeamProStat.TotalAssist, TeamProStat.TotalPoint, TeamProStat.Pim, TeamProStat.Hits, TeamProStat.FaceOffWonDefensifZone, TeamProStat.FaceOffTotalDefensifZone, TeamProStat.FaceOffWonOffensifZone, TeamProStat.FaceOffTotalOffensifZone, TeamProStat.FaceOffWonNeutralZone, TeamProStat.FaceOffTotalNeutralZone, TeamProStat.EmptyNetGoal FROM TeamProStat WHERE Number = " . $Team . ") AS MainTable ORDER BY Number";
		$TeamStatSub = $db->query($Query);
		$Query = "SELECT * FROM PlayerInfo WHERE Team = " . $Team . " AND Status1 >= 2 Order By PosD, Overall DESC";
		$PlayerRoster = $db->query($Query);
		$Query = "SELECT MainTable.*, PlayerInfo.PosC, PlayerInfo.PosLW, PlayerInfo.PosRW, PlayerInfo.PosD, GoalerInfo.PosG FROM ((SELECT PlayerInfo.Number, PlayerInfo.Name, PlayerInfo.Team, PlayerInfo.Age, PlayerInfo.AgeDate, PlayerInfo.Weight, PlayerInfo.Height, PlayerInfo.Contract, PlayerInfo.Rookie, PlayerInfo.NoTrade, PlayerInfo.CanPlayPro, PlayerInfo.CanPlayFarm, PlayerInfo.ForceWaiver, PlayerInfo.ExcludeSalaryCap, PlayerInfo.ProSalaryinFarm, PlayerInfo.SalaryAverage, PlayerInfo.Salary1, PlayerInfo.Salary2, PlayerInfo.Salary3, PlayerInfo.Salary4, PlayerInfo.Salary5, PlayerInfo.Salary6, PlayerInfo.Salary7, PlayerInfo.Salary8, PlayerInfo.Salary9, PlayerInfo.Salary10, PlayerInfo.Condition, PlayerInfo.ConditionDecimal,PlayerInfo.Status1, PlayerInfo.URLLink FROM PlayerInfo Where Team =" . $Team . " AND Status1 >= 2 UNION ALL SELECT GoalerInfo.Number, GoalerInfo.Name, GoalerInfo.Team, GoalerInfo.Age, GoalerInfo.AgeDate,GoalerInfo.Weight, GoalerInfo.Height, GoalerInfo.Contract, GoalerInfo.Rookie, GoalerInfo.NoTrade, GoalerInfo.CanPlayPro, GoalerInfo.CanPlayFarm, GoalerInfo.ForceWaiver, GoalerInfo.ExcludeSalaryCap, GoalerInfo.ProSalaryinFarm, GoalerInfo.SalaryAverage, GoalerInfo.Salary1, GoalerInfo.Salary2, GoalerInfo.Salary3, GoalerInfo.Salary4, GoalerInfo.Salary5, GoalerInfo.Salary6, GoalerInfo.Salary7, GoalerInfo.Salary8, GoalerInfo.Salary9, GoalerInfo.Salary10, GoalerInfo.Condition, GoalerInfo.ConditionDecimal, GoalerInfo.Status1, GoalerInfo.URLLink FROM GoalerInfo Where Team =" . $Team . " AND Status1 >= 2)  AS MainTable LEFT JOIN PlayerInfo ON MainTable.Name = PlayerInfo.Name) LEFT JOIN GoalerInfo ON MainTable.Name = GoalerInfo.Name  ORDER BY MainTable.Name";
		$PlayerInfo = $db->query($Query);
		$Query = "SELECT Avg(PlayerInfo.ConditionDecimal) AS AvgOfConditionDecimal, Avg(PlayerInfo.CK) AS AvgOfCK, Avg(PlayerInfo.FG) AS AvgOfFG, Avg(PlayerInfo.DI) AS AvgOfDI, Avg(PlayerInfo.SK) AS AvgOfSK, Avg(PlayerInfo.ST) AS AvgOfST, Avg(PlayerInfo.EN) AS AvgOfEN, Avg(PlayerInfo.DU) AS AvgOfDU, Avg(PlayerInfo.PH) AS AvgOfPH, Avg(PlayerInfo.FO) AS AvgOfFO, Avg(PlayerInfo.PA) AS AvgOfPA, Avg(PlayerInfo.SC) AS AvgOfSC, Avg(PlayerInfo.DF) AS AvgOfDF, Avg(PlayerInfo.PS) AS AvgOfPS, Avg(PlayerInfo.EX) AS AvgOfEX, Avg(PlayerInfo.LD) AS AvgOfLD, Avg(PlayerInfo.PO) AS AvgOfPO, Avg(PlayerInfo.MO) AS AvgOfMO, Avg(PlayerInfo.Overall) AS AvgOfOverall FROM PlayerInfo WHERE Team = " . $Team . " AND Status1 >= 2";
		$PlayerRosterAverage = $db->querySingle($Query,True);	
		$Query = "SELECT GoalerInfo.Team, GoalerInfo.Status1, Avg(GoalerInfo.ConditionDecimal) AS AvgOfConditionDecimal, Avg(GoalerInfo.SK) AS AvgOfSK, Avg(GoalerInfo.DU) AS AvgOfDU, Avg(GoalerInfo.EN) AS AvgOfEN, Avg(GoalerInfo.SZ) AS AvgOfSZ, Avg(GoalerInfo.AG) AS AvgOfAG, Avg(GoalerInfo.RB) AS AvgOfRB, Avg(GoalerInfo.SC) AS AvgOfSC, Avg(GoalerInfo.HS) AS AvgOfHS, Avg(GoalerInfo.RT) AS AvgOfRT, Avg(GoalerInfo.PH) AS AvgOfPH, Avg(GoalerInfo.PS) AS AvgOfPS, Avg(GoalerInfo.EX) AS AvgOfEX, Avg(GoalerInfo.LD) AS AvgOfLD, Avg(GoalerInfo.PO) AS AvgOfPO, Avg(GoalerInfo.MO) AS AvgOfMO, Avg(GoalerInfo.Overall) AS AvgOfOverall FROM GoalerInfo WHERE Team = " . $Team . " AND Status1 >= 2";
		$GoalieRosterAverage = $db->querySingle($Query,True);	
		$Query = "SELECT Count(MainTable.Name) AS CountOfName, Avg(MainTable.Age) AS AvgOfAge, Avg(MainTable.Weight) AS AvgOfWeight, Avg(MainTable.Height) AS AvgOfHeight, Avg(MainTable.Contract) AS AvgOfContract, Avg(MainTable.Salary1) AS AvgOfSalary1 FROM (SELECT PlayerInfo.Name, PlayerInfo.Team, PlayerInfo.Age, PlayerInfo.Weight, PlayerInfo.Height, PlayerInfo.Contract, PlayerInfo.Salary1, PlayerInfo.Status1 FROM PlayerInfo WHERE Team = " . $Team . " and Status1 >= 2 UNION ALL SELECT GoalerInfo.Name, GoalerInfo.Team, GoalerInfo.Age, GoalerInfo.Weight, GoalerInfo.Height, GoalerInfo.Contract, GoalerInfo.Salary1, GoalerInfo.Status1 FROM GoalerInfo WHERE Team= " . $Team . " and Status1 >= 2) AS MainTable";
		$PlayerInfoAverage = $db->querySingle($Query,true);
		$Query = "SELECT PlayerProStat.*, PlayerInfo.TeamName, PlayerInfo.TeamName, PlayerInfo.PosC, PlayerInfo.PosLW, PlayerInfo.PosRW, PlayerInfo.PosD, ROUND((CAST(PlayerProStat.G AS REAL) / (PlayerProStat.Shots))*100,2) AS ShotsPCT, ROUND((CAST(PlayerProStat.SecondPlay AS REAL) / 60 / (PlayerProStat.GP)),2) AS AMG,ROUND((CAST(PlayerProStat.FaceOffWon AS REAL) / (PlayerProStat.FaceOffTotal))*100,2) as FaceoffPCT,ROUND((CAST(PlayerProStat.P AS REAL) / (PlayerProStat.SecondPlay) * 60 * 20),2) AS P20 FROM PlayerInfo INNER JOIN PlayerProStat ON PlayerInfo.Number = PlayerProStat.Number WHERE ((PlayerInfo.Team=" . $Team . ") AND (PlayerInfo.Status1 >= 2)  AND (PlayerProStat.GP>0)) ORDER BY PlayerProStat.P DESC";
		$PlayerStat = $db->query($Query);
		$Query = "SELECT Sum(PlayerProStat.GP) AS SumOfGP, Sum(PlayerProStat.Shots) AS SumOfShots, Sum(PlayerProStat.G) AS SumOfG, Sum(PlayerProStat.A) AS SumOfA, Sum(PlayerProStat.P) AS SumOfP, Sum(PlayerProStat.PlusMinus) AS SumOfPlusMinus, Sum(PlayerProStat.Pim) AS SumOfPim, Sum(PlayerProStat.Pim5) AS SumOfPim5, Sum(PlayerProStat.ShotsBlock) AS SumOfShotsBlock, Sum(PlayerProStat.OwnShotsBlock) AS SumOfOwnShotsBlock, Sum(PlayerProStat.OwnShotsMissGoal) AS SumOfOwnShotsMissGoal, Sum(PlayerProStat.Hits) AS SumOfHits, Sum(PlayerProStat.HitsTook) AS SumOfHitsTook, Sum(PlayerProStat.GW) AS SumOfGW, Sum(PlayerProStat.GT) AS SumOfGT, Sum(PlayerProStat.FaceOffWon) AS SumOfFaceOffWon, Sum(PlayerProStat.FaceOffTotal) AS SumOfFaceOffTotal, Sum(PlayerProStat.PenalityShotsScore) AS SumOfPenalityShotsScore, Sum(PlayerProStat.PenalityShotsTotal) AS SumOfPenalityShotsTotal, Sum(PlayerProStat.EmptyNetGoal) AS SumOfEmptyNetGoal, Sum(PlayerProStat.SecondPlay) AS SumOfSecondPlay, Sum(PlayerProStat.HatTrick) AS SumOfHatTrick, Sum(PlayerProStat.PPG) AS SumOfPPG, Sum(PlayerProStat.PPA) AS SumOfPPA, Sum(PlayerProStat.PPP) AS SumOfPPP, Sum(PlayerProStat.PPShots) AS SumOfPPShots, Sum(PlayerProStat.PPSecondPlay) AS SumOfPPSecondPlay, Sum(PlayerProStat.PKG) AS SumOfPKG, Sum(PlayerProStat.PKA) AS SumOfPKA, Sum(PlayerProStat.PKP) AS SumOfPKP, Sum(PlayerProStat.PKShots) AS SumOfPKShots, Sum(PlayerProStat.PKSecondPlay) AS SumOfPKSecondPlay, Sum(PlayerProStat.GiveAway) AS SumOfGiveAway, Sum(PlayerProStat.TakeAway) AS SumOfTakeAway, Sum(PlayerProStat.PuckPossesionTime) AS SumOfPuckPossesionTime, Sum(PlayerProStat.FightW) AS SumOfFightW, Sum(PlayerProStat.FightL) AS SumOfFightL, Sum(PlayerProStat.FightT) AS SumOfFightT, Sum(PlayerProStat.Star1) AS SumOfStar1, Sum(PlayerProStat.Star2) AS SumOfStar2, Sum(PlayerProStat.Star3) AS SumOfStar3, ROUND((CAST(Sum(PlayerProStat.G) AS REAL) / (Sum(PlayerProStat.Shots)))*100,2) AS SumOfShotsPCT, ROUND((CAST(Sum(PlayerProStat.SecondPlay) AS REAL) / 60 / (Sum(PlayerProStat.GP))),2) AS SumOfAMG, ROUND((CAST(Sum(PlayerProStat.FaceOffWon) AS REAL) / (Sum(PlayerProStat.FaceOffTotal)))*100,2) as SumOfFaceoffPCT, ROUND((CAST(Sum(PlayerProStat.P) AS REAL) / (Sum(PlayerProStat.SecondPlay)) * 60 * 20),2) AS SumOfP20 FROM PlayerInfo INNER JOIN PlayerProStat ON PlayerInfo.Number = PlayerProStat.Number WHERE ((PlayerInfo.Team=" . $Team . ") AND (PlayerInfo.Status1 >= 2)  AND (PlayerProStat.GP>0)) ORDER BY PlayerProStat.P DESC";
		$PlayerStatTeam = $db->querySingle($Query,true);	
		$Query = "SELECT GoalerProStat.*, GoalerInfo.TeamName, ROUND((CAST(GoalerProStat.GA AS REAL) / (GoalerProStat.SecondPlay / 60))*60,3) AS GAA, ROUND((CAST(GoalerProStat.SA - GoalerProStat.GA AS REAL) / (GoalerProStat.SA)),3) AS PCT, ROUND((CAST(GoalerProStat.PenalityShotsShots - GoalerProStat.PenalityShotsGoals AS REAL) / (GoalerProStat.PenalityShotsShots)),3) AS PenalityShotsPCT FROM GoalerInfo INNER JOIN GoalerProStat ON GoalerInfo.Number = GoalerProStat.Number WHERE ((GoalerInfo.Team)=" . $Team . ") AND ((GoalerInfo.Status1)>=2)  AND ((GoalerProStat.GP)>0) ORDER BY GoalerProStat.W DESC";
		$GoalieStat = $db->query($Query);
		$Query = "SELECT Sum(GoalerProStat.GP) AS SumOfGP, Sum(GoalerProStat.SecondPlay) AS SumOfSecondPlay, Sum(GoalerProStat.W) AS SumOfW, Sum(GoalerProStat.L) AS SumOfL, Sum(GoalerProStat.OTL) AS SumOfOTL, Sum(GoalerProStat.Shootout) AS SumOfShootout, Sum(GoalerProStat.GA) AS SumOfGA, Sum(GoalerProStat.SA) AS SumOfSA, Sum(GoalerProStat.SARebound) AS SumOfSARebound, Sum(GoalerProStat.Pim) AS SumOfPim, Sum(GoalerProStat.A) AS SumOfA, Sum(GoalerProStat.PenalityShotsShots) AS SumOfPenalityShotsShots, Sum(GoalerProStat.PenalityShotsGoals) AS SumOfPenalityShotsGoals, Sum(GoalerProStat.StartGoaler) AS SumOfStartGoaler, Sum(GoalerProStat.BackupGoaler) AS SumOfBackupGoaler, Sum(GoalerProStat.EmptyNetGoal) AS SumOfEmptyNetGoal, Sum(GoalerProStat.Star1) AS SumOfStar1, Sum(GoalerProStat.Star2) AS SumOfStar2, Sum(GoalerProStat.Star3) AS SumOfStar3, ROUND((CAST(Sum(GoalerProStat.GA) AS REAL) / (Sum(GoalerProStat.SecondPlay) / 60))*60,3) AS SumOfGAA, ROUND((CAST(Sum(GoalerProStat.SA) - Sum(GoalerProStat.GA) AS REAL) / (Sum(GoalerProStat.SA))),3) AS SumOfPCT, ROUND((CAST(Sum(GoalerProStat.PenalityShotsShots) - Sum(GoalerProStat.PenalityShotsGoals) AS REAL) / (Sum(GoalerProStat.PenalityShotsShots))),3) AS SumOfPenalityShotsPCT FROM GoalerInfo INNER JOIN GoalerProStat ON GoalerInfo.Number = GoalerProStat.Number WHERE ((GoalerInfo.Team)=" . $Team . ") AND ((GoalerInfo.Status1)>=2)  AND ((GoalerProStat.GP)>0) ORDER BY GoalerProStat.W DESC";
		$GoalieStatTeam = $db->querySingle($Query,true);	
		$Query = "SELECT * FROM GoalerInfo WHERE Team = " . $Team . " AND Status1 >= 2 ORDER By Overall DESC";
		$GoalieRoster = $db->query($Query);
		$Query = "SELECT * FROM SchedulePro WHERE (VisitorTeam = " . $Team . " OR HomeTeam = " . $Team . ") ORDER BY GameNumber";
		$Schedule= $db->query($Query);
		$Query = "SELECT CoachInfo.* FROM CoachInfo INNER JOIN TeamProInfo ON CoachInfo.Number = TeamProInfo.CoachID WHERE (CoachInfo.Team)=" . $Team;
		$CoachInfo = $db->querySingle($Query,true);	
		$Query = "SELECT * FROM ProRivalryInfo WHERE Team1 = " . $Team . " ORDER By TEAM2";
		$RivalryInfo = $db->query($Query);		
		$Query = "Select Name, PointSystemW, PointSystemSO, LeagueYearOutput, ProInjuryRecoverySpeed, FarmInjuryRecoverySpeed, ProScheduleTotalDay, ScheduleNextDay, DraftPickByYear, DefaultSimulationPerDay, TradeDeadLine from LeagueGeneral";
		$LeagueGeneral = $db->querySingle($Query,true);
		$Query = "Select FarmEnable from LeagueSimulation";
		$LeagueSimulation = $db->querySingle($Query,true);
		$Query = "Select RemoveSalaryCapWhenPlayerUnderCondition, SalaryCapOption from LeagueFinance";
		$LeagueFinance = $db->querySingle($Query,true);		
		$Query = "Select ProCustomOTLines from LeagueWebClient";
		$LeagueWebClient = $db->querySingle($Query,true);	
		$Query = "Select OutputSalariesRemaining, OutputSalariesAverageTotal, OutputSalariesAverageRemaining, InchInsteadofCM, LBSInsteadofKG, ScheduleUseDateInsteadofDay, ScheduleRealDate from LeagueOutputOption";
		$LeagueOutputOption = $db->querySingle($Query,true);	
		$Query = "SELECT * FROM TeamProLines WHERE TeamNumber = " . $Team . " AND Day = 1";
		$TeamLines = $db->querySingle($Query,true);
		$Query = "SELECT * FROM TeamLog WHERE TeamNumber = " . $Team;
		$TeamLog = $db->query($Query);		
		$Query = "SELECT * FROM Prospects WHERE TeamNumber = " . $Team . " ORDER By Name";
		$TeamProspects = $db->query($Query);
		$Query = "SELECT * FROM DraftPick WHERE TeamNumber = " . $Team . " ORDER By Year, Round";
		$TeamDraftPick = $db->query($Query);
		$Query = "SELECT GoalerInfo.Name, GoalerInfo.Status1, GoalerInfo.Team, GoalerInfo.Injury, GoalerInfo.Condition, GoalerInfo.ConditionDecimal, GoalerInfo.Suspension FROM GoalerInfo WHERE TEAM = " . $Team . " AND (ConditionDecimal < 95 OR Suspension > 0) UNION ALL SELECT PlayerInfo.Name, PlayerInfo.Status1, PlayerInfo.Team, PlayerInfo.Injury, PlayerInfo.Condition, PlayerInfo.ConditionDecimal, PlayerInfo.Suspension FROM PlayerInfo WHERE TEAM = " . $Team . " AND (ConditionDecimal < 95 OR Suspension > 0)";
		$TeamInjurySuspension = $db->query($Query);
		$Query = "SELECT GoalerInfo.Name, GoalerInfo.Rookie, GoalerInfo.Age, GoalerInfo.PO, GoalerInfo.Overall FROM GoalerInfo WHERE (GoalerInfo.Team)=" . $Team . " ORDER By Overall DESC, PO DESC";
		$GoalieDepthChart = $db->query($Query);
		$Query = "SELECT PlayerInfo.Name, PlayerInfo.PosLW, PlayerInfo.PosC, PlayerInfo.PosRW, PlayerInfo.PosD, PlayerInfo.Rookie, PlayerInfo.Age, PlayerInfo.PO, PlayerInfo.Overall FROM PlayerInfo WHERE (PlayerInfo.Team)=" . $Team . " ORDER By Overall DESC, PO DESC";
		$PlayerDepthChart = $db->query($Query);		
		
		$LeagueName = $LeagueGeneral['Name'];
		$TeamName = $TeamInfo['Name'];	
		
		If (file_exists($CareerStatDatabaseFile) == true){ /* CareerStat */
			$CareerStatdb = new SQLite3($CareerStatDatabaseFile);
			
			$Query = "Select TeamProStatCareer.* FROM TeamProStatCareer WHERE Playoff = 'False' AND (UniqueID = " . $TeamInfo ['UniqueID'] . " OR Name = '" . $TeamName . "') ORDER BY Year";
			$TeamCareerSeason = $CareerStatdb->query($Query);
			$Query = "Select TeamProStatCareer.* FROM TeamProStatCareer WHERE Playoff = 'True' AND (UniqueID = " . $TeamInfo ['UniqueID'] . " OR Name = '" . $TeamName . "') ORDER BY Year";
			$TeamCareerPlayoff = $CareerStatdb->query($Query);			
			$Query = "SELECT Sum(TeamProStatCareer.GP) AS SumOfGP, Sum(TeamProStatCareer.W) AS SumOfW, Sum(TeamProStatCareer.L) AS SumOfL, Sum(TeamProStatCareer.T) AS SumOfT, Sum(TeamProStatCareer.OTW) AS SumOfOTW, Sum(TeamProStatCareer.OTL) AS SumOfOTL, Sum(TeamProStatCareer.SOW) AS SumOfSOW, Sum(TeamProStatCareer.SOL) AS SumOfSOL, Sum(TeamProStatCareer.Points) AS SumOfPoints, Sum(TeamProStatCareer.GF) AS SumOfGF, Sum(TeamProStatCareer.GA) AS SumOfGA, Sum(TeamProStatCareer.HomeGP) AS SumOfHomeGP, Sum(TeamProStatCareer.HomeW) AS SumOfHomeW, Sum(TeamProStatCareer.HomeL) AS SumOfHomeL, Sum(TeamProStatCareer.HomeT) AS SumOfHomeT, Sum(TeamProStatCareer.HomeOTW) AS SumOfHomeOTW, Sum(TeamProStatCareer.HomeOTL) AS SumOfHomeOTL, Sum(TeamProStatCareer.HomeSOW) AS SumOfHomeSOW, Sum(TeamProStatCareer.HomeSOL) AS SumOfHomeSOL, Sum(TeamProStatCareer.HomeGF) AS SumOfHomeGF, Sum(TeamProStatCareer.HomeGA) AS SumOfHomeGA, Sum(TeamProStatCareer.PPAttemp) AS SumOfPPAttemp, Sum(TeamProStatCareer.PPGoal) AS SumOfPPGoal, Sum(TeamProStatCareer.PKAttemp) AS SumOfPKAttemp, Sum(TeamProStatCareer.PKGoalGA) AS SumOfPKGoalGA, Sum(TeamProStatCareer.PKGoalGF) AS SumOfPKGoalGF, Sum(TeamProStatCareer.ShotsFor) AS SumOfShotsFor, Sum(TeamProStatCareer.ShotsAga) AS SumOfShotsAga, Sum(TeamProStatCareer.ShotsBlock) AS SumOfShotsBlock, Sum(TeamProStatCareer.ShotsPerPeriod1) AS SumOfShotsPerPeriod1, Sum(TeamProStatCareer.ShotsPerPeriod2) AS SumOfShotsPerPeriod2, Sum(TeamProStatCareer.ShotsPerPeriod3) AS SumOfShotsPerPeriod3, Sum(TeamProStatCareer.ShotsPerPeriod4) AS SumOfShotsPerPeriod4, Sum(TeamProStatCareer.GoalsPerPeriod1) AS SumOfGoalsPerPeriod1, Sum(TeamProStatCareer.GoalsPerPeriod2) AS SumOfGoalsPerPeriod2, Sum(TeamProStatCareer.GoalsPerPeriod3) AS SumOfGoalsPerPeriod3, Sum(TeamProStatCareer.GoalsPerPeriod4) AS SumOfGoalsPerPeriod4, Sum(TeamProStatCareer.PuckTimeInZoneDF) AS SumOfPuckTimeInZoneDF, Sum(TeamProStatCareer.PuckTimeInZoneOF) AS SumOfPuckTimeInZoneOF, Sum(TeamProStatCareer.PuckTimeInZoneNT) AS SumOfPuckTimeInZoneNT, Sum(TeamProStatCareer.PuckTimeControlinZoneDF) AS SumOfPuckTimeControlinZoneDF, Sum(TeamProStatCareer.PuckTimeControlinZoneOF) AS SumOfPuckTimeControlinZoneOF, Sum(TeamProStatCareer.PuckTimeControlinZoneNT) AS SumOfPuckTimeControlinZoneNT, Sum(TeamProStatCareer.Shutouts) AS SumOfShutouts, Sum(TeamProStatCareer.TotalGoal) AS SumOfTotalGoal, Sum(TeamProStatCareer.TotalAssist) AS SumOfTotalAssist, Sum(TeamProStatCareer.TotalPoint) AS SumOfTotalPoint, Sum(TeamProStatCareer.Pim) AS SumOfPim, Sum(TeamProStatCareer.Hits) AS SumOfHits, Sum(TeamProStatCareer.FaceOffWonDefensifZone) AS SumOfFaceOffWonDefensifZone, Sum(TeamProStatCareer.FaceOffTotalDefensifZone) AS SumOfFaceOffTotalDefensifZone, Sum(TeamProStatCareer.FaceOffWonOffensifZone) AS SumOfFaceOffWonOffensifZone, Sum(TeamProStatCareer.FaceOffTotalOffensifZone) AS SumOfFaceOffTotalOffensifZone, Sum(TeamProStatCareer.FaceOffWonNeutralZone) AS SumOfFaceOffWonNeutralZone, Sum(TeamProStatCareer.FaceOffTotalNeutralZone) AS SumOfFaceOffTotalNeutralZone, Sum(TeamProStatCareer.EmptyNetGoal) AS SumOfEmptyNetGoal FROM TeamProStatCareer WHERE Playoff = 'False' AND (UniqueID = " . $TeamInfo ['UniqueID'] . " OR Name = '" . $TeamName . "')";
			$TeamCareerSumSeasonOnly = $CareerStatdb->querySingle($Query,true);	
			$Query = "SELECT Sum(TeamProStatCareer.GP) AS SumOfGP, Sum(TeamProStatCareer.W) AS SumOfW, Sum(TeamProStatCareer.L) AS SumOfL, Sum(TeamProStatCareer.T) AS SumOfT, Sum(TeamProStatCareer.OTW) AS SumOfOTW, Sum(TeamProStatCareer.OTL) AS SumOfOTL, Sum(TeamProStatCareer.SOW) AS SumOfSOW, Sum(TeamProStatCareer.SOL) AS SumOfSOL, Sum(TeamProStatCareer.Points) AS SumOfPoints, Sum(TeamProStatCareer.GF) AS SumOfGF, Sum(TeamProStatCareer.GA) AS SumOfGA, Sum(TeamProStatCareer.HomeGP) AS SumOfHomeGP, Sum(TeamProStatCareer.HomeW) AS SumOfHomeW, Sum(TeamProStatCareer.HomeL) AS SumOfHomeL, Sum(TeamProStatCareer.HomeT) AS SumOfHomeT, Sum(TeamProStatCareer.HomeOTW) AS SumOfHomeOTW, Sum(TeamProStatCareer.HomeOTL) AS SumOfHomeOTL, Sum(TeamProStatCareer.HomeSOW) AS SumOfHomeSOW, Sum(TeamProStatCareer.HomeSOL) AS SumOfHomeSOL, Sum(TeamProStatCareer.HomeGF) AS SumOfHomeGF, Sum(TeamProStatCareer.HomeGA) AS SumOfHomeGA, Sum(TeamProStatCareer.PPAttemp) AS SumOfPPAttemp, Sum(TeamProStatCareer.PPGoal) AS SumOfPPGoal, Sum(TeamProStatCareer.PKAttemp) AS SumOfPKAttemp, Sum(TeamProStatCareer.PKGoalGA) AS SumOfPKGoalGA, Sum(TeamProStatCareer.PKGoalGF) AS SumOfPKGoalGF, Sum(TeamProStatCareer.ShotsFor) AS SumOfShotsFor, Sum(TeamProStatCareer.ShotsAga) AS SumOfShotsAga, Sum(TeamProStatCareer.ShotsBlock) AS SumOfShotsBlock, Sum(TeamProStatCareer.ShotsPerPeriod1) AS SumOfShotsPerPeriod1, Sum(TeamProStatCareer.ShotsPerPeriod2) AS SumOfShotsPerPeriod2, Sum(TeamProStatCareer.ShotsPerPeriod3) AS SumOfShotsPerPeriod3, Sum(TeamProStatCareer.ShotsPerPeriod4) AS SumOfShotsPerPeriod4, Sum(TeamProStatCareer.GoalsPerPeriod1) AS SumOfGoalsPerPeriod1, Sum(TeamProStatCareer.GoalsPerPeriod2) AS SumOfGoalsPerPeriod2, Sum(TeamProStatCareer.GoalsPerPeriod3) AS SumOfGoalsPerPeriod3, Sum(TeamProStatCareer.GoalsPerPeriod4) AS SumOfGoalsPerPeriod4, Sum(TeamProStatCareer.PuckTimeInZoneDF) AS SumOfPuckTimeInZoneDF, Sum(TeamProStatCareer.PuckTimeInZoneOF) AS SumOfPuckTimeInZoneOF, Sum(TeamProStatCareer.PuckTimeInZoneNT) AS SumOfPuckTimeInZoneNT, Sum(TeamProStatCareer.PuckTimeControlinZoneDF) AS SumOfPuckTimeControlinZoneDF, Sum(TeamProStatCareer.PuckTimeControlinZoneOF) AS SumOfPuckTimeControlinZoneOF, Sum(TeamProStatCareer.PuckTimeControlinZoneNT) AS SumOfPuckTimeControlinZoneNT, Sum(TeamProStatCareer.Shutouts) AS SumOfShutouts, Sum(TeamProStatCareer.TotalGoal) AS SumOfTotalGoal, Sum(TeamProStatCareer.TotalAssist) AS SumOfTotalAssist, Sum(TeamProStatCareer.TotalPoint) AS SumOfTotalPoint, Sum(TeamProStatCareer.Pim) AS SumOfPim, Sum(TeamProStatCareer.Hits) AS SumOfHits, Sum(TeamProStatCareer.FaceOffWonDefensifZone) AS SumOfFaceOffWonDefensifZone, Sum(TeamProStatCareer.FaceOffTotalDefensifZone) AS SumOfFaceOffTotalDefensifZone, Sum(TeamProStatCareer.FaceOffWonOffensifZone) AS SumOfFaceOffWonOffensifZone, Sum(TeamProStatCareer.FaceOffTotalOffensifZone) AS SumOfFaceOffTotalOffensifZone, Sum(TeamProStatCareer.FaceOffWonNeutralZone) AS SumOfFaceOffWonNeutralZone, Sum(TeamProStatCareer.FaceOffTotalNeutralZone) AS SumOfFaceOffTotalNeutralZone, Sum(TeamProStatCareer.EmptyNetGoal) AS SumOfEmptyNetGoal FROM TeamProStatCareer WHERE Playoff = 'True' AND (UniqueID = " . $TeamInfo ['UniqueID'] . " OR Name = '" . $TeamName . "')";
			$TeamCareerSumPlayoffOnly = $CareerStatdb->querySingle($Query,true);	
			
			$TeamCareerStatFound = true;
		}
		
	}else{
		$TeamInfo = Null;
		$TeamFarmInfo = Null;			
		$TeamFinance = Null;
		$TeamFarmFinance = Null;		
		$TeamStat = Null;
		$PlayerRoster = Null;
		$PlayerInfo = Null;
		$PlayerRosterAverage = Null;	
		$GoalieRosterAverage = Null;	
		$PlayerInfoAverage = Null;
		$PlayerStat = Null;
		$GoalieStat = Null;
		$GoalieRoster = Null;
		$Schedule= Null;
		$CoachInfo = Null;	
		$RivalryInfo = Null;		
		$LeagueGeneral = Null;
		$LeagueFinance = Null;		
		$LeagueWebClient = Null;	
		$LeagueOutputOption = Null;	
		$TeamLines = Null;
		$TeamLog = Null;		
		$TeamProspects = Null;
		$TeamDraftPick = Null;
		$TeamInjurySuspension = Null;
		$GoalieDepthChart = Null;
		$PlayerDepthChart = Null;

		$TeamName = $TeamLang['Teamnotfound'];
		echo "<style type=\"text/css\">.STHSPHPTeamStat_Main {display:none;}</style>";
	}
}
echo "<title>" . $LeagueName . " - " . $TeamName . "</title>";
?>
<style type="text/css">
<?php
if ($TeamCareerStatFound == true){
	echo "#tablesorter_colSelect11:checked + label {background: #5797d7;  border-color: #555;}\n";
	echo "#tablesorter_colSelect11:checked ~ #tablesorter_ColumnSelector11 {display: block;}\n";
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
@media screen and (max-width: 992px) {
.STHSWarning {display:block;}
.STHSPHPTeamStatDepthChart_Table td:nth-child(3){display:none;}
}@media screen and (max-width: 890px) {
.STHSPHPTeamStatDepthChart_Table td:nth-child(2){display:none;}
#STHSPHPTeamStat_SubHeader {display:none;}
}
</style>
</head><body>
<?php include "Menu.php";?>
<br />

<div class="STHSPHPTeamStat_TeamNameHeader TeamRosterPro_<?php echo $TeamInfo['Abbre'] . "\">" . $TeamName;?></div><br />
<div id="STHSPHPTeamStat_SubHeader" style="font-size:20px;width:99%;text-align:center;margin:auto;">
<span style="width:60%;float:left;text-align:left;"><?php echo $TeamLang['GM'] . $TeamInfo['GMName'];?></span> 
<span style="width:17%;float:left;"><?php echo $TeamLang['Morale'] . $TeamInfo['Morale'];?> </span>
<span style="width:23%;float:left;"><?php echo $TeamLang['TeamOverall'] . $TeamInfo['TeamOverall'];?></span></div>
<div class="STHSWarning"><?php echo $WarningResolution;?><br /></div>
<div class="STHSPHPTeamStat_Main">
<br />
<div class="tabsmain standard"><ul class="tabmain-links">
<?php if ($LeagueSimulation['FarmEnable'] == "True"){echo "<li><a class=\"tabmenuhome\" href=\"FarmTeam.php?Team=" . $Team . "\">" . $TeamFarmInfo['Name'] . "</a></li>";}?>
<li class="activemain"><a href="#tabmain1"><?php echo $TeamLang['Roster'];?></a></li>
<li><a href="#tabmain2"><?php echo $TeamLang['Scoring'];?></a></li>
<li><a href="#tabmain3"><?php echo $TeamLang['PlayersInfo'];?></a></li>
<li><a href="#tabmain4"><?php echo $TeamLang['Lines'];?></a></li>
<li><a href="#tabmain5"><?php echo $TeamLang['TeamStats'];?></a></li>
<li><a href="#tabmain6"><?php echo $TeamLang['Schedule'];?></a></li>
<li><a href="#tabmain7"><?php echo $TeamLang['Finance'];?></a></li>
<li><a href="#tabmain8"><?php echo $TeamLang['Depth'];?></a></li>
<li><a href="#tabmain9"><?php echo $TeamLang['History'];?></a></li>
<li><a href="#tabmain10"><?php echo $TeamLang['InjurySuspension'];?></a></li>
<?php if ($TeamCareerStatFound == true){echo "<li><a href=\"#tabmain11\">" . $TeamLang['CareerTeamStat'] . "</a></li>";}?>
</ul>
<div style="border-radius:1px;box-shadow:-1px 1px 1px rgba(0,0,0,0.15);background:#FFFFF0;border-style: solid;border-color: #dedede">
<div class="tabmain active" id="tabmain1">

<div class="tablesorter_ColumnSelectorWrapper">
    <input id="tablesorter_colSelect1P" type="checkbox" class="hidden">
    <label class="tablesorter_ColumnSelectorButton" for="tablesorter_colSelect1P"><?php echo $TableSorterLang['ShoworHideColumn'];?></label>
    <div id="tablesorter_ColumnSelector1P" class="tablesorter_ColumnSelector"></div>
	<?php include "FilterTip.php";?>
</div>

<table class="tablesorter STHSPHPTeam_PlayersRosterTable"><thead><tr>
<th data-priority="3" title="Order Number" class="STHSW25">#</th>
<th data-priority="critical" title="Player Name" class="STHSW140Min"><?php echo $PlayersLang['PlayerName'];?></th>
<th data-priority="4" title="Center" class="STHSW10">C</th>
<th data-priority="4" title="Left Wing" class="STHSW10">L</th>
<th data-priority="4" title="Right Wing" class="STHSW10">R</th>
<th data-priority="4" title="Defenseman" class="STHSW10">D</th>
<th data-priority="2" title="Condition" class="STHSW25">CON</th>
<th data-priority="1" title="Checking" class="STHSW25">CK</th>
<th data-priority="1" title="Fighting" class="STHSW25">FG</th>
<th data-priority="1" title="Discipline" class="STHSW25">DI</th>
<th data-priority="1" title="Skating" class="STHSW25">SK</th>
<th data-priority="1" title="Strength" class="STHSW25">ST</th>
<th data-priority="1" title="Endurance" class="STHSW25">EN</th>
<th data-priority="1" title="Durability" class="STHSW25">DU</th>
<th data-priority="1" title="Puck Handling" class="STHSW25">PH</th>
<th data-priority="1" title="Face Offs" class="STHSW25">FO</th>
<th data-priority="1" title="Passing" class="STHSW25">PA</th>
<th data-priority="1" title="Scoring" class="STHSW25">SC</th>
<th data-priority="1" title="Defense" class="STHSW25">DF</th>
<th data-priority="1" title="Penalty Shot" class="STHSW25">PS</th>
<th data-priority="1" title="Experience" class="STHSW25">EX</th>
<th data-priority="1" title="Leadership" class="STHSW25">LD</th>
<th data-priority="3" title="Potential" class="STHSW25">PO</th>
<th data-priority="1" title="Morale" class="STHSW25">MO</th>
<th data-priority="critical" title="Overall" class="STHSW25">OV</th>
<th data-priority="5" title="Trade Available" class="STHSW25">TA</th>
<th data-priority="5" title="Star Power" class="STHSW25">SP</th>
</tr></thead>
<?php
for($Status = 3; $Status >= 2; $Status--){
	if ($Status == 3){echo "<tbody>";}
	if ($Status == 2){echo "</tbody><tbody class=\"tablesorter-no-sort\"><tr><th colspan=\"27\">" . $TeamLang['Scratches'] . "</th></tr></tbody><tbody>";}
	$LoopCount = (integer)0;
	if (empty($PlayerRoster) == false){while ($Row = $PlayerRoster ->fetchArray()) {
		If ($Row['Status1'] == $Status){
			$LoopCount +=1;
			echo "<tr><td>" . $LoopCount . "</td>";
			$strTemp = (string)$Row['Name'];
			If ($Row['Rookie']== "True"){ $strTemp = $strTemp . " (R)";}
			If ($TeamInfo['Captain'] == $Row['Number']){ $strTemp = $strTemp . " (C)";}
			If ($TeamInfo['Assistant1'] == $Row['Number']){ $strTemp = $strTemp . " (A)";}
			If ($TeamInfo['Assistant2'] == $Row['Number']){ $strTemp = $strTemp . " (A)";}
			echo "<td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $strTemp . "</a></td>";
			echo "<td>";if  ($Row['PosC']== "True"){ echo "X";}; echo"</td>";
			echo "<td>";if  ($Row['PosLW']== "True"){ echo "X";}; echo"</td>";
			echo "<td>";if  ($Row['PosRW']== "True"){ echo "X";}; echo"</td>";
			echo "<td>";if  ($Row['PosD']== "True"){ echo "X";}; echo"</td>";		
			echo "<td>";if  ($Row <> Null){echo number_format(str_replace(",",".",$Row['ConditionDecimal']),2);}; echo"</td>";
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
			echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
		}	
	}}
	/*if ($Status == 2 and $LoopCount ==0){echo "<tr><th colspan=\"28\">No Scratches Players</th></tr>";} */
} 
echo "</tbody><tbody class=\"tablesorter-no-sort\">";
echo "<tr><td colspan=\"27\"></td></tr></tbody><tbody class=\"tablesorter-no-sort\">";
echo "<tr><td></td><td style=\"text-align:right;font-weight:bold\">" . $TeamLang['TeamAverage'] . "</td>";
echo "<td></td><td></td><td></td><td></td>";
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
?>
<td></td><td></td></tr></tbody></table>

<div class="tablesorter_ColumnSelectorWrapper">
    <input id="tablesorter_colSelect1G" type="checkbox" class="hidden">
    <label class="tablesorter_ColumnSelectorButton" for="tablesorter_colSelect1G"><?php echo $TableSorterLang['ShoworHideColumn'];?></label>
    <div id="tablesorter_ColumnSelector1G" class="tablesorter_ColumnSelector"></div>
	<?php include "FilterTip.php";?>
</div>

<table class="tablesorter STHSPHPTeam_GoaliesRosterTable"><thead><tr>
<th data-priority="4" title="Order Number" class="STHSW25">#</th>
<th data-priority="critical" title="Goalie Name" class="STHSW140Min"><?php echo $PlayersLang['GoalieName'];?></th>
<th data-priority="2" title="Condition" class="STHSW25">CON</th>
<th data-priority="1" title="Skating" class="STHSW25">SK</th>
<th data-priority="1" title="Durability" class="STHSW25">DU</th>
<th data-priority="1" title="Endurance" class="STHSW25">EN</th>
<th data-priority="1" title="Size" class="STHSW25">SZ</th>
<th data-priority="1" title="Agility" class="STHSW25">AG</th>
<th data-priority="1" title="Rebound Control" class="STHSW25">RB</th>
<th data-priority="1" title="Style Control" class="STHSW25">SC</th>
<th data-priority="1" title="Hand Speed" class="STHSW25">HS</th>
<th data-priority="1" title="Reaction Time" class="STHSW25">RT</th>
<th data-priority="1" title="Puck Handling" class="STHSW25">PH</th>
<th data-priority="1" title="Penalty Shot" class="STHSW25">PS</th>
<th data-priority="1" title="Experience" class="STHSW25">EX</th>
<th data-priority="1" title="Leadership" class="STHSW25">LD</th>
<th data-priority="3" title="Potential" class="STHSW25">PO</th>
<th data-priority="1" title="Morale" class="STHSW25">MO</th>
<th data-priority="critical" title="Overall" class="STHSW25">OV</th>
<th data-priority="5" title="Trade Available" class="STHSW25">TA</th>
<th data-priority="6" title="Star Power" class="STHSW25">SP</th>
</tr></thead>
<?php
for($Status = 3; $Status >= 2; $Status--){
	if ($Status == 3){echo "<tbody>";}
	if ($Status == 2){echo "</tbody><tbody class=\"tablesorter-no-sort\"><tr><th colspan=\"21\">" . $TeamLang['Scratches'] . "</th></tr></tbody><tbody>";}
	$LoopCount = (integer)0;
	if (empty($GoalieRoster) == false){while ($Row = $GoalieRoster ->fetchArray()) {
		If ($Row['Status1'] == $Status){
		$LoopCount +=1;
		echo "<tr><td>" . $LoopCount . "</td>";
		$strTemp = (string)$Row['Name'];
		if ($Row['Rookie']== "True"){ $strTemp = $strTemp . " (R)";}
		echo "<td><a href=\"GoalieReport.php?Goalie=" . $Row['Number'] . "\">" . $strTemp . "</a></td>";
		echo "<td>";if  ($Row <> Null){echo number_format(str_replace(",",".",$Row['ConditionDecimal']),2);}; echo"</td>";
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
		echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
		}	
	}}
}
echo "</tbody><tbody class=\"tablesorter-no-sort\">";
echo "<tr><td colspan=\"21\"></td></tr></tbody><tbody class=\"tablesorter-no-sort\">";
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
?>
<td></td><td></td></tr></tbody></table>

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
<div class="tabmain" id="tabmain2">

<div class="tablesorter_ColumnSelectorWrapper">
    <input id="tablesorter_colSelect2P" type="checkbox" class="hidden">
    <label class="tablesorter_ColumnSelectorButton" for="tablesorter_colSelect2P"><?php echo $TableSorterLang['ShoworHideColumn'];?></label>
    <div id="tablesorter_ColumnSelector2P" class="tablesorter_ColumnSelector"></div>
	<?php include "FilterTip.php";?>
</div>

<table class="tablesorter STHSPHPTeam_PlayersScoringTable"><thead><tr>
<?php 
include "PlayersStatSub.php";
If ($PlayerStatTeam['SumOfGP'] > 0){
	echo "</tbody><tbody class=\"tablesorter-no-sort\">";
	echo "<tr><td style=\"text-align:right;font-weight:bold\">" . $TeamLang['TeamTotalAverage'] . "</td><td></td>";
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
}
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
If ($PlayerStatTeam['SumOfGP'] > 0){
	echo "</tbody><tbody class=\"tablesorter-no-sort\">";
	echo "<tr><td style=\"text-align:right;font-weight:bold\">" . $TeamLang['TeamTotalAverage'] . "</td>";
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
}
?>
</tbody></table>

<br /><br /></div>
<div class="tabmain" id="tabmain3">

<div class="tablesorter_ColumnSelectorWrapper">
    <input id="tablesorter_colSelect3" type="checkbox" class="hidden">
    <label class="tablesorter_ColumnSelectorButton" for="tablesorter_colSelect3"><?php echo $TableSorterLang['ShoworHideColumn'];?></label>
    <div id="tablesorter_ColumnSelector3" class="tablesorter_ColumnSelector"></div>
	<?php include "FilterTip.php";?>
</div>
<table class="tablesorter STHSPHPTeam_PlayerInfoTable"><thead><tr>
<th data-priority="critical" title="Player Name" class="STHSW140Min"><?php echo $PlayersLang['PlayerName'];?></th>
<th data-priority="2" title="Position" class="STHSW45">POS</th>
<th data-priority="1" title="Age" class="STHSW25"><?php echo $PlayersLang['Age'];?></th>
<th data-priority="4" title="Birthday" class="STHSW45"><?php echo $PlayersLang['Birthday'];?></th>
<th data-priority="3" title="Rookie" class="STHSW35"><?php echo $PlayersLang['Rookie'];?></th>
<th data-priority="2" title="Weight" class="STHSW45"><?php echo $PlayersLang['Weight'];?></th>
<th data-priority="2" title="Height" class="STHSW45"><?php echo $PlayersLang['Height'];?></th>
<th data-priority="3" title="No Trade" class="STHSW35"><?php echo $PlayersLang['NoTrade'];?></th>
<th data-priority="3" title="Force Waiver" class="STHSW45"><?php echo $PlayersLang['ForceWaiver'];?></th>
<th data-priority="1" title="Contract Duration" class="STHSW45"><?php echo $PlayersLang['Contract'];?></th>
<th class="columnSelector-false STHSW55" data-priority="5" title="Type"><?php echo $PlayersLang['Type'];?></th>
<th data-priority="1" title="Current Salary" class="STHSW85"><?php echo $PlayersLang['CurrentSalary'];?></th>
<?php 
	$Remaining = (float)0;
	if($LeagueOutputOption['OutputSalariesRemaining'] == "True"){Echo "<th data-priority=\"4\" title=\"Salary Remaining\" class=\"STHSW85\">" . $PlayersLang['SalaryRemaining'] . "</th>";}
	if($LeagueOutputOption['OutputSalariesAverageTotal'] == "True"){Echo "<th data-priority=\"4\" title=\"Salary Average\" class=\"STHSW85\">" . $PlayersLang['SalaryAverage'] . "</th>";}
	if($LeagueOutputOption['OutputSalariesAverageRemaining'] == "True"){echo "<th data-priority=\"4\" title=\"Salary Average Remaining\" class=\"STHSW85\">" . $PlayersLang['SalaryAveRemaining'] . "</th>";}
	if($LeagueOutputOption['OutputSalariesRemaining'] == "True" OR $LeagueOutputOption['OutputSalariesAverageRemaining'] == "True"){If ($LeagueGeneral['ProScheduleTotalDay'] > 0){$Remaining = ($LeagueGeneral['ProScheduleTotalDay'] - $LeagueGeneral['ScheduleNextDay'] + 1) / $LeagueGeneral['ProScheduleTotalDay'];}}	
?>
<th data-priority="5" title="Salary Year 2" class="STHSW85"><?php echo $PlayersLang['SalaryYear'];?> 2</th>
<th data-priority="5" title="Salary Year 3" class="STHSW85"><?php echo $PlayersLang['SalaryYear'];?> 3</th>
<th data-priority="5" title="Salary Year 4" class="STHSW85"><?php echo $PlayersLang['SalaryYear'];?> 4</th>
<th class="columnSelector-false STHSW85" data-priority="6" title="Salary Year 5"><?php echo $PlayersLang['SalaryYear'];?> 5</th>
<th class="columnSelector-false STHSW85" data-priority="6" title="Salary Year 6"><?php echo $PlayersLang['SalaryYear'];?> 6</th>
<th class="columnSelector-false STHSW85" data-priority="6" title="Salary Year 7"><?php echo $PlayersLang['SalaryYear'];?> 7</th>
<th class="columnSelector-false STHSW85" data-priority="6" title="Salary Year 8"><?php echo $PlayersLang['SalaryYear'];?> 8</th>
<th class="columnSelector-false STHSW85" data-priority="6" title="Salary Year 9"><?php echo $PlayersLang['SalaryYear'];?> 9</th>
<th class="columnSelector-false STHSW85" data-priority="6" title="Salary Year 10"><?php echo $PlayersLang['SalaryYear'];?> 10</th>
<th data-priority="5" title="Hyperlink" class="STHSW35">Link</th>
</tr></thead><tbody>
<?php 
if (empty($PlayerInfo) == false){while ($Row = $PlayerInfo ->fetchArray()) { 
	echo "<tr><td>";
	if ($Row['PosG']== "True"){echo "<a href=\"GoalieReport.php?Goalie=";}else{echo "<a href=\"PlayerReport.php?Player=";}
	Echo $Row['Number'] . "\">" . $Row['Name'] . "</a>";
	If ($Row['ConditionDecimal'] > $LeagueFinance['RemoveSalaryCapWhenPlayerUnderCondition'] AND $Row['ExcludeSalaryCap'] == "False"){
	If($Row['ProSalaryinFarm'] == "True"){echo $PlayersLang['1WayContract'] . "</td>";}else{echo "</td>";}}else{echo $PlayersLang['OutofPayroll'] . "</td>";}
	echo "<td>" .$Position = (string)"";
	if ($Row['PosC']== "True"){if ($Position == ""){$Position = "C";}else{$Position = $Position . "/C";}}
	if ($Row['PosLW']== "True"){if ($Position == ""){$Position = "LW";}else{$Position = $Position . "/LW";}}
	if ($Row['PosRW']== "True"){if ($Position == ""){$Position = "RW";}else{$Position = $Position . "/RW";}}
	if ($Row['PosD']== "True"){if ($Position == ""){$Position = "D";}else{$Position = $Position . "/D";}}
	if ($Row['PosG']== "True"){if ($Position == ""){$Position = "G";}}
	echo $Position . "</td>";	
	echo "<td>" . $Row['Age'] . "</td>";
	echo "<td>" . $Row['AgeDate'] . "</td>";
	echo "<td>"; if ($Row['Rookie'] == "True"){ echo "Yes"; }else{echo "No";};echo "</td>";	
	If ($LeagueOutputOption['LBSInsteadofKG'] == "True"){echo "<td>" . $Row['Weight'] . " Lbs</td>";}else{echo "<td>" . Round($Row['Weight'] / 2.2) . " Kg</td>";}
	If ($LeagueOutputOption['InchInsteadofCM'] == "True"){echo "<td>" . (($Row['Height'] - ($Row['Height'] % 12))/12) . " ft" .  ($Row['Height'] % 12) .  "</td>";}else{echo "<td>" . Round($Row['Height'] * 2.54) . " CM</td>";}	
	echo "<td>"; if ($Row['NoTrade']== "True"){ echo "Yes"; }else{echo "No";};echo "</td>";
	echo "<td>"; if ($Row['ForceWaiver']== "True"){ echo "Yes"; }else{echo "No";};echo "</td>";
	echo "<td>" . $Row['Contract'] . "</td>";
	echo "<td>"; if ($Row['CanPlayPro']== "True" AND $Row['CanPlayFarm']== "True"){echo "Pro &amp; Farm";}elseif($Row['CanPlayPro']== "True" AND $Row['CanPlayFarm']== "False"){echo "Pro Only";}else{echo "Farm Only";	};echo "</td>";
	echo "<td>"; if ($Row['Salary1'] > 0){echo number_format($Row['Salary1'],0) . "$";};echo "</td>";	
	if($LeagueOutputOption['OutputSalariesRemaining'] == "True"){echo "<td>"; if ($Row['Salary1'] > 0){echo number_format($Row['Salary1'] * $Remaining,0) . "$";};echo "</td>";}
	if($LeagueOutputOption['OutputSalariesAverageTotal'] == "True"){echo "<td>"; if ($Row['SalaryAverage'] > 0){echo number_format($Row['SalaryAverage'],0) . "$";};echo "</td>";}
	if($LeagueOutputOption['OutputSalariesAverageRemaining'] == "True"){echo "<td>"; if ($Row['SalaryAverage'] > 0){echo number_format($Row['SalaryAverage'] * $Remaining,0) . "$";};echo "</td>";}
	echo "<td>"; if ($Row['Salary2'] > 0){echo number_format($Row['Salary2'],0) . "$";};echo "</td>";	
	echo "<td>"; if ($Row['Salary3'] > 0){echo number_format($Row['Salary3'],0) . "$";};echo "</td>";	
	echo "<td>"; if ($Row['Salary4'] > 0){echo number_format($Row['Salary4'],0) . "$";};echo "</td>";	
	echo "<td>"; if ($Row['Salary5'] > 0){echo number_format($Row['Salary5'],0) . "$";};echo "</td>";	
	echo "<td>"; if ($Row['Salary6'] > 0){echo number_format($Row['Salary6'],0) . "$";};echo "</td>";	
	echo "<td>"; if ($Row['Salary7'] > 0){echo number_format($Row['Salary7'],0) . "$";};echo "</td>";	
	echo "<td>"; if ($Row['Salary8'] > 0){echo number_format($Row['Salary8'],0) . "$";};echo "</td>";	
	echo "<td>"; if ($Row['Salary9'] > 0){echo number_format($Row['Salary9'],0) . "$";};echo "</td>";	
	echo "<td>"; if ($Row['Salary10'] > 0){echo number_format($Row['Salary10'],0) . "$";};echo "</td>";		
	If ($Row['URLLink'] == ""){echo "<td></td>";}else{echo "<td><a href=\"" . $Row['URLLink'] . "\" target=\"new\">" . $PlayersLang['Link'] . "</a></td>";}
	echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
}}
?>
</tbody></table>

<table class="STHSPHPTeamStat_Table"><tr><th class="STHSW100"><?php echo $TeamLang['TotalPlayers'];?></th><th class="STHSW100"><?php echo $TeamLang['AverageAge'];?></th><th class="STHSW120"><?php echo $TeamLang['AverageWeight'];?></th><th class="STHSW120"><?php echo $TeamLang['AverageHeight'];?></th><th class="STHSW120"><?php echo $TeamLang['AverageContract'];?></th><th class="STHSW140"><?php echo $TeamLang['AverageYear1Salary'];?></th></tr>
<tr>
<?php
echo "<td>" . $PlayerInfoAverage['CountOfName'] . "</td>";
echo "<td>" . number_format($PlayerInfoAverage['AvgOfAge'],2) . "</td>";
If ($LeagueOutputOption['LBSInsteadofKG'] == "True"){echo "<td>" . Round($PlayerInfoAverage['AvgOfWeight']) . " Lbs</td>";}else{echo "<td>" . Round(Round($PlayerInfoAverage['AvgOfWeight']) / 2.2) . " Kg</td>";}
If ($LeagueOutputOption['InchInsteadofCM'] == "True"){echo "<td>" . ((Round($PlayerInfoAverage['AvgOfHeight']) - (Round($PlayerInfoAverage['AvgOfHeight']) % 12))/12) . " ft" .  (Round($PlayerInfoAverage['AvgOfHeight']) % 12) .  "</td>";}else{echo "<td>" . Round(Round($PlayerInfoAverage['AvgOfHeight']) * 2.54) . " CM</td>";}		
echo "<td>" . $PlayerInfoAverage['AvgOfContract'] . "</td>";
echo "<td>" . number_format($PlayerInfoAverage['AvgOfSalary1'],0) . "$</td>";	
?>
</tr></table>

<br /><br /></div>
<div class="tabmain" id="tabmain4">
<br />

<table class="STHSPHPTeamStat_Table"><tr><th colspan="8"><?php echo $TeamLang['5vs5Forward'];?></th></tr><tr>
<th class="STHSW25"><?php echo $TeamLang['LineNumber'];?></th><th class="STHSW140"><?php echo $TeamLang['Center'];?></th><th class="STHSW140"><?php echo $TeamLang['LeftWing'];?></th><th class="STHSW140"><?php echo $TeamLang['RightWing'];?></th><th class="STHSW25"><?php echo $TeamLang['TimePCT'];?></th><th class="STHSW25"><?php echo $TeamLang['PHY'];?></th><th class="STHSW25"><?php echo $TeamLang['DF'];?></th><th class="STHSW25"><?php echo $TeamLang['OF'];?></th></tr>
<?php echo "<tr><td>1</td>";
echo "<td>" . $TeamLines['Line15vs5ForwardCenter'] . "</td>";
echo "<td>" . $TeamLines['Line15vs5ForwardLeftWing'] . "</td>";
echo "<td>" . $TeamLines['Line15vs5ForwardRightWing'] . "</td>";
echo "<td>" . $TeamLines['Line15vs5ForwardTime'] . "</td>";
echo "<td>" . $TeamLines['Line15vs5ForwardPhy'] . "</td>";
echo "<td>" . $TeamLines['Line15vs5ForwardDF'] . "</td>";
echo "<td>" . $TeamLines['Line15vs5ForwardOF'] . "</td>";
echo "</tr>\n<tr><td>2</td>";
echo "<td>" . $TeamLines['Line25vs5ForwardCenter'] . "</td>";
echo "<td>" . $TeamLines['Line25vs5ForwardLeftWing'] . "</td>";
echo "<td>" . $TeamLines['Line25vs5ForwardRightWing'] . "</td>";
echo "<td>" . $TeamLines['Line25vs5ForwardTime'] . "</td>";
echo "<td>" . $TeamLines['Line25vs5ForwardPhy'] . "</td>";
echo "<td>" . $TeamLines['Line25vs5ForwardDF'] . "</td>";
echo "<td>" . $TeamLines['Line25vs5ForwardOF'] . "</td>";
echo "</tr>\n<tr><td>3</td>";
echo "<td>" . $TeamLines['Line35vs5ForwardCenter'] . "</td>";
echo "<td>" . $TeamLines['Line35vs5ForwardLeftWing'] . "</td>";
echo "<td>" . $TeamLines['Line35vs5ForwardRightWing'] . "</td>";
echo "<td>" . $TeamLines['Line35vs5ForwardTime'] . "</td>";
echo "<td>" . $TeamLines['Line35vs5ForwardPhy'] . "</td>";
echo "<td>" . $TeamLines['Line35vs5ForwardDF'] . "</td>";
echo "<td>" . $TeamLines['Line35vs5ForwardOF'] . "</td>";
echo "</tr>\n<tr><td>4</td>";
echo "<td>" . $TeamLines['Line45vs5ForwardCenter'] . "</td>";
echo "<td>" . $TeamLines['Line45vs5ForwardLeftWing'] . "</td>";
echo "<td>" . $TeamLines['Line45vs5ForwardRightWing'] . "</td>";
echo "<td>" . $TeamLines['Line45vs5ForwardTime'] . "</td>";
echo "<td>" . $TeamLines['Line45vs5ForwardPhy'] . "</td>";
echo "<td>" . $TeamLines['Line45vs5ForwardDF'] . "</td>";
echo "<td>" . $TeamLines['Line45vs5ForwardOF'] . "</td>";
?></tr></table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPTeamStat_Table"><tr><th colspan="8"><?php echo $TeamLang['5vs5Defense'];?></th></tr><tr>
<th class="STHSW25"><?php echo $TeamLang['LineNumber'];?></th><th class="STHSW140"><?php echo $TeamLang['Defense'];?></th><th class="STHSW140"><?php echo $TeamLang['Defense'];?></th><th class="STHSW140"></th><th class="STHSW25"><?php echo $TeamLang['TimePCT'];?></th><th class="STHSW25"><?php echo $TeamLang['PHY'];?></th><th class="STHSW25"><?php echo $TeamLang['DF'];?></th><th class="STHSW25"><?php echo $TeamLang['OF'];?></th></tr>
<?php echo "<tr><td>1</td>";
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
?></tr></table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPTeamStat_Table"><tr><th colspan="8"><?php echo $TeamLang['PowerPlayForward'];?></th></tr><tr>
<th class="STHSW25"><?php echo $TeamLang['LineNumber'];?></th><th class="STHSW140"><?php echo $TeamLang['Center'];?></th><th class="STHSW140"><?php echo $TeamLang['LeftWing'];?></th><th class="STHSW140"><?php echo $TeamLang['RightWing'];?></th><th class="STHSW25"><?php echo $TeamLang['TimePCT'];?></th><th class="STHSW25"><?php echo $TeamLang['PHY'];?></th><th class="STHSW25"><?php echo $TeamLang['DF'];?></th><th class="STHSW25"><?php echo $TeamLang['OF'];?></th></tr>
<?php echo "<tr><td>1</td>";
echo "<td>" . $TeamLines['Line1PPForwardCenter'] . "</td>";
echo "<td>" . $TeamLines['Line1PPForwardLeftWing'] . "</td>";
echo "<td>" . $TeamLines['Line1PPForwardRightWing'] . "</td>";
echo "<td>" . $TeamLines['Line1PPForwardTime'] . "</td>";
echo "<td>" . $TeamLines['Line1PPForwardPhy'] . "</td>";
echo "<td>" . $TeamLines['Line1PPForwardDF'] . "</td>";
echo "<td>" . $TeamLines['Line1PPForwardOF'] . "</td>";
echo "</tr>\n<tr><td>2</td>";
echo "<td>" . $TeamLines['Line2PPForwardCenter'] . "</td>";
echo "<td>" . $TeamLines['Line2PPForwardLeftWing'] . "</td>";
echo "<td>" . $TeamLines['Line2PPForwardRightWing'] . "</td>";
echo "<td>" . $TeamLines['Line2PPForwardTime'] . "</td>";
echo "<td>" . $TeamLines['Line2PPForwardPhy'] . "</td>";
echo "<td>" . $TeamLines['Line2PPForwardDF'] . "</td>";
echo "<td>" . $TeamLines['Line2PPForwardOF'] . "</td>";
?></tr></table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPTeamStat_Table"><tr><th colspan="8"><?php echo $TeamLang['PowerPlayDefense'];?></th></tr><tr>
<th class="STHSW25"><?php echo $TeamLang['LineNumber'];?></th><th class="STHSW140"><?php echo $TeamLang['Defense'];?></th><th class="STHSW140"><?php echo $TeamLang['Defense'];?></th><th class="STHSW140"></th><th class="STHSW25"><?php echo $TeamLang['TimePCT'];?></th><th class="STHSW25"><?php echo $TeamLang['PHY'];?></th><th class="STHSW25"><?php echo $TeamLang['DF'];?></th><th class="STHSW25"><?php echo $TeamLang['OF'];?></th></tr>
<?php echo "<tr><td>1</td>";
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
?></tr></table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPTeamStat_Table"><tr><th colspan="7"><?php echo $TeamLang['PenaltyKill4PlayersForward'];?></th></tr><tr>
<th class="STHSW25"><?php echo $TeamLang['LineNumber'];?></th><th class="STHSW140"><?php echo $TeamLang['Center'];?></th><th class="STHSW140"><?php echo $TeamLang['Wing'];?></th><th class="STHSW25"><?php echo $TeamLang['TimePCT'];?></th><th class="STHSW25"><?php echo $TeamLang['PHY'];?></th><th class="STHSW25"><?php echo $TeamLang['DF'];?></th><th class="STHSW25"><?php echo $TeamLang['OF'];?></th></tr>
<?php echo "<tr><td>1</td>";
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
?></tr></table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPTeamStat_Table"><tr><th colspan="7"><?php echo $TeamLang['PenaltyKill4PlayersDefense'];?></th></tr><tr>
<th class="STHSW25"><?php echo $TeamLang['LineNumber'];?></th><th class="STHSW140"><?php echo $TeamLang['Defense'];?></th><th class="STHSW140"><?php echo $TeamLang['Defense'];?></th><th class="STHSW25"><?php echo $TeamLang['TimePCT'];?></th><th class="STHSW25"><?php echo $TeamLang['PHY'];?></th><th class="STHSW25"><?php echo $TeamLang['DF'];?></th><th class="STHSW25"><?php echo $TeamLang['OF'];?></th></tr>
<?php echo "<tr><td>1</td>";
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
?></tr></table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPTeamStat_Table"><tr><th colspan="12"><?php echo $TeamLang['PenaltyKill3Players'];?></th></tr><tr>
<th class="STHSW25"><?php echo $TeamLang['LineNumber'];?></th><th class="STHSW140"><?php echo $TeamLang['Wing'];?></th><th class="STHSW25"><?php echo $TeamLang['TimePCT'];?></th><th class="STHSW25"><?php echo $TeamLang['PHY'];?></th><th class="STHSW25"><?php echo $TeamLang['DF'];?></th><th class="STHSW25"><?php echo $TeamLang['OF'];?></th><th class="STHSW140"><?php echo $TeamLang['Defense'];?></th><th class="STHSW140"><?php echo $TeamLang['Defense'];?></th><th class="STHSW25"><?php echo $TeamLang['TimePCT'];?></th><th class="STHSW25"><?php echo $TeamLang['PHY'];?></th><th class="STHSW25"><?php echo $TeamLang['DF'];?></th><th class="STHSW25"><?php echo $TeamLang['OF'];?></th></tr>
<?php echo "<tr><td>1</td>";
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
?></tr></table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPTeamStat_Table"><tr><th colspan="7"><?php echo $TeamLang['4vs4Forward'];?></th></tr><tr>
<th class="STHSW25"><?php echo $TeamLang['LineNumber'];?></th><th class="STHSW140"><?php echo $TeamLang['Center'];?></th><th class="STHSW140"><?php echo $TeamLang['Wing'];?></th><th class="STHSW25"><?php echo $TeamLang['TimePCT'];?></th><th class="STHSW25"><?php echo $TeamLang['PHY'];?></th><th class="STHSW25"><?php echo $TeamLang['DF'];?></th><th class="STHSW25"><?php echo $TeamLang['OF'];?></th></tr>
<?php echo "<tr><td>1</td>";
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
?></tr></table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPTeamStat_Table"><tr><th colspan="7"><?php echo $TeamLang['4vs4Defense'];?></th></tr><tr>
<th class="STHSW25"><?php echo $TeamLang['LineNumber'];?></th><th class="STHSW140"><?php echo $TeamLang['Defense'];?></th><th class="STHSW140"><?php echo $TeamLang['Defense'];?></th><th class="STHSW25"><?php echo $TeamLang['TimePCT'];?></th><th class="STHSW25"><?php echo $TeamLang['PHY'];?></th><th class="STHSW25"><?php echo $TeamLang['DF'];?></th><th class="STHSW25"><?php echo $TeamLang['OF'];?></th></tr>
<?php echo "<tr><td>1</td>";
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
?></tr></table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPTeamStat_Table"><tr><th colspan="5"><?php echo $TeamLang['LastMinutesOffensive'];?></th></tr><tr>
<th class="STHSW140"><?php echo $TeamLang['Center'];?></th><th class="STHSW140"><?php echo $TeamLang['LeftWing'];?></th><th class="STHSW140"><?php echo $TeamLang['RightWing'];?></th><th class="STHSW140"><?php echo $TeamLang['Defense'];?></th><th class="STHSW140"><?php echo $TeamLang['Defense'];?></th></tr>
<?php echo "<tr>";
echo "<td>" . $TeamLines['LastMinOffForwardCenter'] . "</td>";
echo "<td>" . $TeamLines['LastMinOffForwardLeftWing'] . "</td>";
echo "<td>" . $TeamLines['LastMinOffForwardRightWing'] . "</td>";
echo "<td>" . $TeamLines['LastMinOffDefenseDefense1'] . "</td>";
echo "<td>" . $TeamLines['LastMinOffDefenseDefense2'] . "</td>";
?></tr></table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPTeamStat_Table"><tr><th colspan="5"><?php echo $TeamLang['LastMinutesDefensive'];?></th></tr><tr>
<th class="STHSW140"><?php echo $TeamLang['Center'];?></th><th class="STHSW140"><?php echo $TeamLang['LeftWing'];?></th><th class="STHSW140"><?php echo $TeamLang['RightWing'];?></th><th class="STHSW140"><?php echo $TeamLang['Defense'];?></th><th class="STHSW140"><?php echo $TeamLang['Defense'];?></th></tr>
<?php echo "<tr>";
echo "<td>" . $TeamLines['LastMinDefForwardCenter'] . "</td>";
echo "<td>" . $TeamLines['LastMinDefForwardLeftWing'] . "</td>";
echo "<td>" . $TeamLines['LastMinDefForwardRightWing'] . "</td>";
echo "<td>" . $TeamLines['LastMinDefDefenseDefense1'] . "</td>";
echo "<td>" . $TeamLines['LastMinDefDefenseDefense2'] . "</td>";
?></tr></table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPTeamStat_Table"><tr><th colspan="3"><?php echo $TeamLang['ExtraForwards'];?></th></tr><tr>
<th class="STHSW250"><?php echo $TeamLang['Normal'];?> </th><th class="STHSW250"><?php echo $TeamLang['PowerPlay'];?></th><th class="STHSW250"><?php echo $TeamLang['PenaltyKill'];?></th></tr>
<?php echo "<tr>";
echo "<td>" . $TeamLines['ExtraForwardN1'] . ", " . $TeamLines['ExtraForwardN2'] . ", " . $TeamLines['ExtraForwardN3'] . "</td>";
echo "<td>" . $TeamLines['ExtraForwardPP1'] . ", " . $TeamLines['ExtraForwardPP2'] . "</td>";
echo "<td>" . $TeamLines['ExtraForwardPK'] . "</td>";
?></tr></table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPTeamStat_Table"><tr><th colspan="3"><?php echo $TeamLang['ExtraDefensemen'];?> </th></tr><tr>
<th class="STHSW250"><?php echo $TeamLang['Normal'];?> </th><th class="STHSW250"><?php echo $TeamLang['PowerPlay'];?></th><th class="STHSW250"><?php echo $TeamLang['PenaltyKill'];?></th></tr>
<?php echo "<tr>";
echo "<td>" . $TeamLines['ExtraDefenseN1'] . ", " . $TeamLines['ExtraDefenseN2'] . ", " . $TeamLines['ExtraDefenseN3'] . "</td>";
echo "<td>" . $TeamLines['ExtraDefensePP'] . "</td>";
echo "<td>" . $TeamLines['ExtraDefensePK1']  . ", " . $TeamLines['ExtraDefensePK2'] . "</td>";
?></tr></table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPTeamStat_Table"><tr><th><?php echo $TeamLang['PenaltyShots'];?></th></tr><tr>
<?php echo "<td>" . $TeamLines['PenaltyShots1'] . ", " . $TeamLines['PenaltyShots2'] . ", " . $TeamLines['PenaltyShots3'] . ", " . $TeamLines['PenaltyShots4'] . ", " . $TeamLines['PenaltyShots5'];?></td></tr></table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPTeamStat_Table"><tr><th><?php echo $TeamLang['Goalie'];?></th></tr><tr>
<?php echo "<td>#1 : " . $TeamLines['Goaler1'] . ", #2 : " . $TeamLines['Goaler2']; if($TeamLines['Goaler3'] != ""){echo ", #3 : " . $TeamLines['Goaler3'];}?></td></tr></table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPTeamStat_Table"<?php if ($LeagueWebClient['ProCustomOTLines'] == "False"){echo " style=\"display:none;\"";} ?>><tr><th><?php echo $TeamLang['CustomOTLinesForwards'];?></th></tr><tr>
<?php echo "<td>" . $TeamLines['OTForward1'] . ", " . $TeamLines['OTForward2'] . ", " . $TeamLines['OTForward3'] . ", " . $TeamLines['OTForward4'] . ", " . $TeamLines['OTForward5'] . ", " . $TeamLines['OTForward6'] . ", " . $TeamLines['OTForward6'] . ", " . $TeamLines['OTForward7'] . ", " . $TeamLines['OTForward8'] . ", " . $TeamLines['OTForward9'] . ", " . $TeamLines['OTForward10'];?></td></tr></table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPTeamStat_Table"<?php if ($LeagueWebClient['ProCustomOTLines'] == "False"){echo " style=\"display:none;\"";} ?>><tr><th><?php echo $TeamLang['CustomOTLinesDefensemen'];?></th></tr><tr>
<?php echo "<td>" . $TeamLines['OTDefense1'] . ", " . $TeamLines['OTDefense2'] . ", " . $TeamLines['OTDefense3'] . ", " . $TeamLines['OTDefense4'] . ", " . $TeamLines['OTDefense5'];?></td></tr></table>
<div class="STHSBlankDiv"></div>

<br /><br /></div>
<div class="tabmain" id="tabmain5">

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
<?php echo "<tr>";
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
echo "</tr>";?>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPTeamStat_Table"><tr><th colspan="<?php if($LeagueGeneral['PointSystemSO']=="True"){echo "9";}else{echo "8";}?>"><?php echo $TeamLang['AllGames'];?></th></tr><tr>
<th class="STHSW25">GP</th><th class="STHSW25">W</th><th class="STHSW25">L</th><th class="STHSW25">OTW</th><th class="STHSW25">OTL</th>
<?php if($LeagueGeneral['PointSystemSO']=="True"){	echo "<th class=\"STHSW25\">SOW</th><th class=\"STHSW25\">SOL</th>";}else{	echo "<th class=\"STHSW25\">T</th>";}?>
<th class="STHSW25">GF</th><th class="STHSW25">GA</th></tr>
<?php echo "<tr>";
echo "<td>" . $TeamStat['GP']. "</td>";
echo "<td>" . $TeamStat['W']. "</td>";
echo "<td>" . $TeamStat['L']. "</td>";
echo "<td>" . $TeamStat['OTW']. "</td>";
echo "<td>" . $TeamStat['OTL']. "</td>";
if($LeagueGeneral['PointSystemSO']=="True"){	
echo "<td>" . $TeamStat['SOW'] . "</td>";
echo "<td>" . $TeamStat['SOL'] . "</td>";
}else{	
echo "<td>" . $TeamStat['T'] . "</td>";}
echo "<td>" . $TeamStat['GF']. "</td>";
echo "<td>" . $TeamStat['GA']. "</td>";
echo "</tr>";?>
</table>
<div class="STHSBlankDiv"></div>	

<table class="STHSPHPTeamStat_Table"><tr><th colspan="<?php if($LeagueGeneral['PointSystemSO']=="True"){echo "9";}else{echo "8";}?>"><?php echo $TeamLang['HomeGames'];?></th></tr><tr>
<th class="STHSW25">GP</th><th class="STHSW25">W</th><th class="STHSW25">L</th><th class="STHSW25">OTW</th><th class="STHSW25">OTL</th>
<?php if($LeagueGeneral['PointSystemSO']=="True"){	echo "<th class=\"STHSW25\">SOW</th><th class=\"STHSW25\">SOL</th>";}else{	echo "<th class=\"STHSW25\">T</th>";}?>
<th class="STHSW25">GF</th><th class="STHSW25">GA</th></tr>
<?php echo "<tr>";
echo "<td>" . $TeamStat['HomeGP']. "</td>";
echo "<td>" . $TeamStat['HomeW']. "</td>";
echo "<td>" . $TeamStat['HomeL']. "</td>";
echo "<td>" . $TeamStat['HomeOTW']. "</td>";
echo "<td>" . $TeamStat['HomeOTL']. "</td>";
if($LeagueGeneral['PointSystemSO']=="True"){	
echo "<td>" . $TeamStat['HomeSOW'] . "</td>";
echo "<td>" . $TeamStat['HomeSOL'] . "</td>";
}else{	
echo "<td>" . $TeamStat['HomeT'] . "</td>";}
echo "<td>" . $TeamStat['HomeGF']. "</td>";
echo "<td>" . $TeamStat['HomeGA']. "</td>";
echo "</tr>";?>
</table>
<div class="STHSBlankDiv"></div>	
	
<table class="STHSPHPTeamStat_Table"><tr><th colspan="<?php if($LeagueGeneral['PointSystemSO']=="True"){echo "9";}else{echo "8";}?>"><?php echo $TeamLang['VisitorGames'];?></th></tr><tr>
<th class="STHSW25">GP</th><th class="STHSW25">W</th><th class="STHSW25">L</th><th class="STHSW25">OTW</th><th class="STHSW25">OTL</th>
<?php if($LeagueGeneral['PointSystemSO']=="True"){	echo "<th class=\"STHSW25\">SOW</th><th class=\"STHSW25\">SOL</th>";}else{	echo "<th class=\"STHSW25\">T</th>";}?>
<th class="STHSW25">GF</th><th class="STHSW25">GA</th></tr>
<?php echo "<tr>";
echo "<td>" . ($TeamStat['GP'] - $TeamStat['HomeGP']). "</td>";
echo "<td>" . ($TeamStat['W'] - $TeamStat['HomeW']). "</td>";
echo "<td>" . ($TeamStat['L'] - $TeamStat['HomeL']). "</td>";
echo "<td>" . ($TeamStat['OTW'] - $TeamStat['HomeOTW']). "</td>";
echo "<td>" . ($TeamStat['OTL'] - $TeamStat['HomeOTL']). "</td>";
if($LeagueGeneral['PointSystemSO']=="True"){	
echo "<td>" . ($TeamStat['SOW'] - $TeamStat['HomeSOW']) . "</td>";
echo "<td>" . ($TeamStat['SOL'] - $TeamStat['HomeSOL']) . "</td>";
}else{	
echo "<td>" . ($TeamStat['T'] - $TeamStat['HomeT']) . "</td>";}
echo "<td>" . ($TeamStat['GF'] - $TeamStat['HomeGF']). "</td>";
echo "<td>" . ($TeamStat['GA'] - $TeamStat['HomeGA']). "</td>";
echo "</tr>";?>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPTeamStat_Table"><tr><th colspan="<?php if($LeagueGeneral['PointSystemSO']=="True"){echo "6";}else{echo "5";}?>"><?php echo $TeamLang['Last10Games'];?>
</th></tr><tr>
<th class="STHSW25">W</th><th class="STHSW25">L</th><th class="STHSW25">OTW</th><th class="STHSW25">OTL</th>
<?php if($LeagueGeneral['PointSystemSO']=="True"){	echo "<th class=\"STHSW25\">SOW</th><th class=\"STHSW25\">SOL</th>";}else{	echo "<th class=\"STHSW25\">T</th>";}?></tr>
<?php echo "<tr>";
echo "<td>" . $TeamStat['Last10W']. "</td>";
echo "<td>" . $TeamStat['Last10L']. "</td>";
echo "<td>" . $TeamStat['Last10OTW']. "</td>";
echo "<td>" . $TeamStat['Last10OTL']. "</td>";
if($LeagueGeneral['PointSystemSO']=="True"){	
echo "<td>" . $TeamStat['Last10SOW'] . "</td>";
echo "<td>" . $TeamStat['Last10SOL'] . "</td>";
}else{	
echo "<td>" . $TeamStat['Last10T'] . "</td>";}
echo "</tr>";?>
</table>
<div class="STHSBlankDiv"></div>	

<table class="STHSPHPTeamStat_Table"><tr>
<th class="STHSW25"><?php echo $TeamLang['PowerPlayAttemps'];?></th><th class="STHSW25"><?php echo $TeamLang['PowerPlayGoals'];?></th><th class="STHSW25"><?php echo $TeamLang['PowerPlayPCT'];?></th><th class="STHSW25"><?php echo $TeamLang['PenaltyKillAttemps'];?></th><th class="STHSW25"><?php echo $TeamLang['PenaltyKillGoalsAgainst'];?></th><th class="STHSW25"><?php echo $TeamLang['PenaltyKillPCT'];?></th><th class="STHSW25"><?php echo $TeamLang['PenaltyKillPCTGoalsFor'];?></th></tr>
<?php echo "<tr>";
echo "<td>" . $TeamStat['PPAttemp']. "</td>";
echo "<td>" . $TeamStat['PPGoal']. "</td>";
echo "<td>";if ($TeamStat['PPAttemp'] > 0){echo number_Format($TeamStat['PPGoal'] / $TeamStat['PPAttemp'] * 100,2) . "%";} else { echo "0.00%";} echo "</td>";		
echo "<td>" . $TeamStat['PKAttemp']. "</td>";
echo "<td>" . $TeamStat['PKGoalGA']. "</td>";
echo "<td>";if ($TeamStat['PKAttemp'] > 0){echo number_Format(($TeamStat['PKAttemp'] - $TeamStat['PKGoalGA']) / $TeamStat['PKAttemp'] * 100,2) . "%";} else {echo "0.00%";} echo "</td>";
echo "<td>" .  $TeamStat['PKGoalGF']. "</td>";		
echo "</tr>";?>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPTeamStat_Table"><tr>
<th class="STHSW25"><?php echo $TeamLang['Shots1Period'];?></th><th class="STHSW25"><?php echo $TeamLang['Shots2Period'];?></th><th class="STHSW25"><?php echo $TeamLang['Shots3Period'];?></th><th class="STHSW25"><?php echo $TeamLang['Shots4Period'];?></th><th class="STHSW25"><?php echo $TeamLang['Goals1Period'];?></th><th class="STHSW25"><?php echo $TeamLang['Goals2Period'];?></th><th class="STHSW25"><?php echo $TeamLang['Goals3Period'];?></th><th class="STHSW25"><?php echo $TeamLang['Goals4Period'];?>
</th></tr>
<?php echo "<tr>";
echo "<td>" . $TeamStat['ShotsPerPeriod1']. "</td>";
echo "<td>" . $TeamStat['ShotsPerPeriod2']. "</td>";
echo "<td>" . $TeamStat['ShotsPerPeriod3']. "</td>";
echo "<td>" . $TeamStat['ShotsPerPeriod4']. "</td>";
echo "<td>" . $TeamStat['GoalsPerPeriod1']. "</td>";		
echo "<td>" . $TeamStat['GoalsPerPeriod2']. "</td>";	
echo "<td>" . $TeamStat['GoalsPerPeriod3']. "</td>";	
echo "<td>" . $TeamStat['GoalsPerPeriod4']. "</td>";	
echo "</tr>";?>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPTeamStat_Table"><tr>
<th colspan="9"><?php echo $TeamLang['FaceOffs'];?></th></tr><tr>
<th class="STHSW25"><?php echo $TeamLang['WonOffensifZone'];?></th><th class="STHSW25"><?php echo $TeamLang['TotalOffensif'];?></th><th class="STHSW25"><?php echo $TeamLang['WonOffensifPCT'];?></th><th class="STHSW25"><?php echo $TeamLang['WonDefensifZone'];?></th><th class="STHSW25"><?php echo $TeamLang['TotalDefensif'];?></th><th class="STHSW25"><?php echo $TeamLang['WonDefensifPCT'];?></th><th class="STHSW25"><?php echo $TeamLang['WonNeutralZone'];?></th><th class="STHSW25"><?php echo $TeamLang['TotalNeutral'];?></th><th class="STHSW25"><?php echo $TeamLang['WonNeutralPCT'];?></th></tr>
<?php echo "<tr>";
echo "<td>" . $TeamStat['FaceOffWonOffensifZone']. "</td>";
echo "<td>" . $TeamStat['FaceOffTotalOffensifZone']. "</td>";		
echo "<td>";if ($TeamStat['FaceOffTotalOffensifZone'] > 0){echo number_Format($TeamStat['FaceOffWonOffensifZone'] / $TeamStat['FaceOffTotalOffensifZone'] * 100,2) . "%" ;} else { echo "0.00%";} echo "</td>";	
echo "<td>" . $TeamStat['FaceOffWonDefensifZone']. "</td>";
echo "<td>" . $TeamStat['FaceOffTotalDefensifZone']. "</td>";
echo "<td>";if ($TeamStat['FaceOffTotalDefensifZone'] > 0){echo number_Format($TeamStat['FaceOffWonDefensifZone'] / $TeamStat['FaceOffTotalDefensifZone'] * 100,2) . "%" ;} else { echo "0.00%";} echo "</td>";	
echo "<td>" . $TeamStat['FaceOffWonNeutralZone']. "</td>";	
echo "<td>" . $TeamStat['FaceOffTotalNeutralZone']. "</td>";	
echo "<td>";if ($TeamStat['FaceOffTotalNeutralZone'] > 0){echo number_Format($TeamStat['FaceOffWonNeutralZone'] / $TeamStat['FaceOffTotalNeutralZone'] * 100,2) . "%" ;} else { echo "0.00%";} echo "</td>";	
echo "</tr>";?>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPTeamStat_Table"><tr>
<th colspan="6"><?php echo $TeamLang['PuckTime'];?></th></tr><tr>
<th class="STHSW25"><?php echo $TeamLang['InOffensifZone'];?></th><th class="STHSW25"><?php echo $TeamLang['ControlInOffensifZone'];?></th><th class="STHSW25"><?php echo $TeamLang['InDefensifZone'];?></th><th class="STHSW25"><?php echo $TeamLang['ControlInDefensifZone'];?></th><th class="STHSW25"><?php echo $TeamLang['InNeutralZone'];?></th><th class="STHSW25"><?php echo $TeamLang['ControlInNeutralZone'];?></th>
</tr>
<?php echo "<tr>";
echo "<td>" . Floor($TeamStat['PuckTimeInZoneOF']/60). "</td>";
echo "<td>" . Floor($TeamStat['PuckTimeControlinZoneOF']/60). "</td>";
echo "<td>" . Floor($TeamStat['PuckTimeInZoneDF']/60). "</td>";
echo "<td>" . Floor($TeamStat['PuckTimeControlinZoneDF']/60). "</td>";
echo "<td>" . Floor($TeamStat['PuckTimeInZoneNT']/60). "</td>";		
echo "<td>" . Floor($TeamStat['PuckTimeControlinZoneNT']/60). "</td>";	
echo "</tr>";?>
</table>

<br /><br /></div>
<div class="tabmain" id="tabmain6">
<div class="tablesorter_ColumnSelectorWrapper">
    <div id="tablesorter_ColumnSelector" class="tablesorter_ColumnSelector"></div>
	<?php include "FilterTip.php";?>
</div>
<table class="tablesorter STHSPHPTeam_ScheduleTable"><thead><tr>
<?php include "ScheduleSub.php";?>
</tbody></table>

<br /><br /></div>
<div class="tabmain" id="tabmain7">
<br />
<table class="STHSPHPTeamStat_Table"><tr><th colspan="6"><?php echo $TeamLang['ArenaCapacityTicketPriceAttendance'];?></th></tr><tr><th class="STHSW200"></th><th class="STHSW100"><?php echo $TeamLang['Level'];?> 1</th><th class="STHSW100"><?php echo $TeamLang['Level'];?> 2</th><th class="STHSW100"><?php echo $TeamLang['Level'];?> 3</th><th class="STHSW100"><?php echo $TeamLang['Level'];?> 4</th><th class="STHSW100"><?php echo $TeamLang['Luxury'];?></th></tr>
<?php 
echo "<tr><th>" . $TeamLang['ArenaCapacity'] . "</th><td>" . $TeamFinance['ArenaCapacityL1'] . "</td><td>" . $TeamFinance['ArenaCapacityL2'] . "</td><td>" . $TeamFinance['ArenaCapacityL3'] . "</td><td>" . $TeamFinance['ArenaCapacityL4'] . "</td><td>" . $TeamFinance['ArenaCapacityLuxury'] . "</td></tr>\n";
echo "<tr><th>" . $TeamLang['TicketPrice'] . "</th><td>" . $TeamFinance['TicketPriceL1'] . "</td><td>" . $TeamFinance['TicketPriceL2'] . "</td><td>" . $TeamFinance['TicketPriceL3'] . "</td><td>" . $TeamFinance['TicketPriceL4'] . "</td><td>" . $TeamFinance['TicketPriceLuxury'] . "</td></tr>\n";
if ($TeamStat['HomeGP'] > 0){echo "<tr><th>" . $TeamLang['Attendance'] . "</th><td>" . $TeamFinance['AttendanceL1'] . "</td><td>" . $TeamFinance['AttendanceL2'] . "</td><td>" . $TeamFinance['AttendanceL3'] . "</td><td>" . $TeamFinance['AttendanceL4'] . "</td><td>" . $TeamFinance['AttendanceLuxury'] . "</td></tr>\n";
}else{echo "<tr><th>" . $TeamLang['Attendance'] . "</th><td>0.00%</td><td>0.00%</td><td>0.00%</td><td>0.00%</td><td>0.00%</td></tr>\n";}
echo "<tr><th>Attendance PCT</th>";
echo "<td>";if ($TeamFinance['ArenaCapacityL1'] > 0 AND $TeamStat['HomeGP'] > 0){echo number_format(($TeamFinance['AttendanceL1'] / ($TeamFinance['ArenaCapacityL1'] * $TeamStat['HomeGP'])) *100 ,2) . "%";} else { echo "0.00%";} echo "</td>";	
echo "<td>";if ($TeamFinance['ArenaCapacityL2'] > 0 AND $TeamStat['HomeGP'] > 0){echo number_format(($TeamFinance['AttendanceL2'] / ($TeamFinance['ArenaCapacityL2'] * $TeamStat['HomeGP'])) *100 ,2) . "%";} else { echo "0.00%";} echo "</td>";	
echo "<td>";if ($TeamFinance['ArenaCapacityL3'] > 0 AND $TeamStat['HomeGP'] > 0){echo number_format(($TeamFinance['AttendanceL3'] / ($TeamFinance['ArenaCapacityL3'] * $TeamStat['HomeGP'])) *100 ,2) . "%";} else { echo "0.00%";} echo "</td>";	
echo "<td>";if ($TeamFinance['ArenaCapacityL4'] > 0 AND $TeamStat['HomeGP'] > 0){echo number_format(($TeamFinance['AttendanceL4'] / ($TeamFinance['ArenaCapacityL4'] * $TeamStat['HomeGP'])) *100 ,2) . "%";} else { echo "0.00%";} echo "</td>";	
echo "<td>";if ($TeamFinance['ArenaCapacityLuxury'] > 0 AND $TeamStat['HomeGP'] > 0){echo number_format(($TeamFinance['AttendanceLuxury'] / ($TeamFinance['ArenaCapacityLuxury'] * $TeamStat['HomeGP'])) *100 ,2) . "%";} else { echo "0.00%";} echo "</td>";	
?>
</tr></table>

<br />
<table class="STHSPHPTeamStat_Table"><tr><th colspan="6"><?php echo $TeamLang['Income'];?>
</th></tr><tr><th class="STHSW140"><?php echo $TeamLang['HomeGamesLeft'];?></th><th class="STHSW140"><?php echo $TeamLang['AverageAttendancePCT'];?></th><th class="STHSW140"><?php echo $TeamLang['AverageIncomeperGame'];?></th><th class="STHSW140"><?php echo $TeamLang['YeartoDateRevenue'];?></th><th class="STHSW140"><?php echo $TeamLang['ArenaCapacity'];?></th><th class="STHSW140"><?php echo $TeamLang['TeamPopularity'];?>
</th></tr><tr>
<?php 
$TotalArenaCapacity = ($TeamFinance['ArenaCapacityL1'] + $TeamFinance['ArenaCapacityL2'] + $TeamFinance['ArenaCapacityL3'] + $TeamFinance['ArenaCapacityL4'] + $TeamFinance['ArenaCapacityLuxury']);
If ($TeamFinance['ScheduleHomeGameInAYear'] > 0){echo "<td>" . ($TeamFinance['ScheduleHomeGameInAYear'] - $TeamStat['HomeGP'] ). "</td>\n";}else{echo "<td>" . (($TeamFinance['ScheduleGameInAYear'] / 2) - $TeamStat['HomeGP'])  . "</td>\n";}
if ($TeamStat['HomeGP'] > 0){echo "<td>" . Round($TeamFinance['TotalAttendance'] / $TeamStat['HomeGP']) . " - ";echo number_Format(($TeamFinance['TotalAttendance'] / ($TotalArenaCapacity * $TeamStat['HomeGP'])) *100,2) . "%</td>\n";
}else{echo "<td>0 - 0.00%</td>";}
if ($TeamStat['HomeGP'] > 0){echo "<td>" . number_format($TeamFinance['TotalIncome'] / $TeamStat['HomeGP'],0) . "$</td>";}else{echo "<td>0$</td>";}
echo "<td>" . number_format($TeamFinance['TotalIncome'],0) . "$</td>";
echo "<td>" . $TotalArenaCapacity . "</td>";
echo "<td>" . $TeamFinance['TeamPopularity'] . "</td>";
?>
</tr></table>

<br />
<table class="STHSPHPTeamStat_Table"><tr><th colspan="4"><?php echo $TeamLang['Expenses'];?></th></tr><tr><th class="STHSW140"><?php echo $TeamLang['PlayersTotalSalaries'];?>
</th><th class="STHSW140"><?php echo $TeamLang['PlayersTotalAverageSalaries'];?></th><th class="STHSW140"><?php echo $TeamLang['CoachesSalaries'];?></th><th class="STHSW140"><?php echo $TeamLang['SpecialSalaryCapValue'];?></th></tr><tr>
<?php 
echo "<td>" . number_Format($TeamFinance['TotalPlayersSalaries'],0) . "$</td>\n";
echo "<td>" . number_Format($TeamFinance['TotalPlayersSalariesAverage'],0) . "$</td>\n";
echo "<td>";If (Count($CoachInfo) == 1){echo number_Format($CoachInfo['Salary'],0) . "$";};echo "0$</td>\n";
echo "<td>" . number_Format($TeamFinance['SpecialSalaryCapY1'],0) . "$</td>\n";
?>
</tr></table>
<table class="STHSPHPTeamStat_Table"><tr><th class="STHSW140"><?php echo $TeamLang['YearToDateExpenses'];?></th><th class="STHSW140"><?php echo $TeamLang['SalaryCapPerDays'];?></th><th class="STHSW140"><?php echo $TeamLang['SalaryCapToDate'];?></th><th class="STHSW140"><?php echo $TeamLang['LuxuryTaxeTotal'];?></th></tr><tr>
<?php 
echo "<td>" . number_Format(($TeamFinance['ExpenseThisSeason']),0) . "$</td>\n";
echo "<td>" . number_Format($TeamFinance['SalaryCapPerDay'],0) . "$</td>\n";
echo "<td>" . number_Format($TeamFinance['SalaryCapToDate'],0) . "$</td>\n";
echo "<td>" . number_Format($TeamFinance['LuxuryTaxeTotal'],0) . "$</td>\n";
?>
</tr></table>
<br />

<table class="STHSPHPTeamStat_Table"><tr><th colspan="4"><?php echo $TeamLang['Estimate'];?></th></tr><tr><th class="STHSW140"><?php echo $TeamLang['EstimatedSeasonRevenue'];?></th><th class="STHSW140"><?php echo $TeamLang['RemainingSeasonDays'];?>
</th><th class="STHSW140"><?php echo $TeamLang['ExpensesPerDays'];?></th><th class="STHSW140"><?php echo $TeamLang['EstimatedSeasonExpenses'];?></th></tr><tr>
<?php 
echo "<td>" . number_Format($TeamFinance['EstimatedRevenue'],0) . "$</td>\n";
$Remaining = ($LeagueGeneral['ProScheduleTotalDay'] - $LeagueGeneral['ScheduleNextDay'] + 1);
echo "<td>";if($Remaining > 0){echo $Remaining;}else{echo "0";}echo "</td>\n";
echo "<td>" . number_Format($TeamFinance['ExpensePerDay'],0) . "$</td>\n";
echo "<td>" . number_Format($TeamFinance['EstimatedSeasonExpense'],0) . "$</td>\n";
?>
</tr>
</table>
<br />

<table class="STHSPHPTeamStat_Table"><tr><th colspan="4"><?php echo $TeamLang['TeamTotalEstime'];?></th></tr><tr><th class="STHSW140"><?php echo $TeamLang['EstimatedSeasonExpenses'];?></th><th class="STHSW140"><?php echo $TeamLang['EstimatedSeasonSalaryCap'];?></th><th class="STHSW140"><?php echo $TeamLang['CurrentBankAccount'];?></th><th class="STHSW140"><?php echo $TeamLang['ProjectedBankAccount'];?></th></tr><tr>
<?php 
echo "<td>" . number_Format(($TeamFinance['EstimatedSeasonExpense'] + $TeamFarmFinance['EstimatedSeasonExpense']) ,0) . "$</td>\n";
echo "<td>" . number_Format($TeamFinance['TotalSalaryCap'],0) . "$</td>\n";
echo "<td>" . number_Format($TeamFinance['CurrentBankAccount'],0) . "$</td>\n";
echo "<td>" . number_Format($TeamFinance['ProjectedBankAccount'],0) . "$</td>\n";
?>
</tr>
</table>
<br />

<br /><br /></div>
<div class="tabmain" id="tabmain8">
<h3 class="STHSTeamProspect_DraftPick"><?php echo $TeamLang['DepthChart'];?></h3>
<table class="STHSPHPTeamStat_Table"><tr><th style="width:33%;"><?php echo $TeamLang['LeftWing'];?></th><th style="width:33%;"><?php echo $TeamLang['Center'];?></th><th style="width:33%;"><?php echo $TeamLang['RightWing'];?></th></tr>
<tr><td class="STHSAlignTop">
<table class="STHSPHPTeamStatDepthChart_Table">
<?php
$PlayerDepthChartC = $PlayerDepthChart;
$PlayerDepthChartLW = $PlayerDepthChart;
$PlayerDepthChartRW = $PlayerDepthChart;
$PlayerDepthChartD = $PlayerDepthChart;

if (empty($PlayerDepthChartC) == false){while ($Row = $PlayerDepthChartC ->fetchArray()) {
	If ($Row['PosLW']== "True"){
		echo "<tr><td class=\"STHSW140\">";
		If ($Row['Rookie']== "True"){echo "*";}
		echo $Row['Name'] . "</td>";
		echo "<td class=\"STHSW35\">AGE:" . $Row['Age'] . "</td>";
		echo "<td class=\"STHSW35\">PO:" . $Row['PO'] . "</td>";
		echo "<td class=\"STHSW35\">OV:" . $Row['Overall'] . "</td>"; 
		echo "</tr>";
	}	
}}
?>
</table>
</td><td class="STHSAlignTop">
<table class="STHSPHPTeamStatDepthChart_Table">
<?php
if (empty($PlayerDepthChartLW) == false){while ($Row = $PlayerDepthChartLW ->fetchArray()) {
	If ($Row['PosC']== "True"){
		echo "<tr><td class=\"STHSW140\">";
		If ($Row['Rookie']== "True"){echo "*";}
		echo $Row['Name'] . "</td>";
		echo "<td class=\"STHSW35\">AGE:" . $Row['Age'] . "</td>";
		echo "<td class=\"STHSW35\">PO:" . $Row['PO'] . "</td>";
		echo "<td class=\"STHSW35\">OV:" . $Row['Overall'] . "</td>"; 
		echo "</tr>";
	}	
}}
?>
</table>
</td><td class="STHSAlignTop">
<table class="STHSPHPTeamStatDepthChart_Table">
<?php
if (empty($PlayerDepthChartRW) == false){while ($Row = $PlayerDepthChartRW ->fetchArray()) {
	If ($Row['PosRW']== "True"){
		echo "<tr><td class=\"STHSW140\">";
		If ($Row['Rookie']== "True"){echo "*";}
		echo $Row['Name'] . "</td>";
		echo "<td class=\"STHSW35\">AGE:" . $Row['Age'] . "</td>";
		echo "<td class=\"STHSW35\">PO:" . $Row['PO'] . "</td>";
		echo "<td class=\"STHSW35\">OV:" . $Row['Overall'] . "</td>"; 
		echo "</tr>";
	}	
}}
?>
</table>
</td></tr></table><br />

<table class="STHSPHPTeamStat_Table"><tr><th style="width:33%;"><?php echo $TeamLang['Defense'];?> #1</th><th style="width:33%;"><?php echo $TeamLang['Defense'];?> #2</th><th style="width:33%;"><?php echo $TeamLang['Goalie'];?></th></tr>
<tr><td class="STHSAlignTop">
<table class="STHSPHPTeamStatDepthChart_Table">
<?php
$NumOfD = (integer)0;
$Count = (integer)0;
if (empty($PlayerDepthChart) == false){while ($Row = $PlayerDepthChart ->fetchArray()) {If ($Row['PosD']== "True"){$NumOfD++;}}}
$NumOfD = Round($NumOfD / 2);
if (empty($PlayerDepthChartD) == false){while ($Row = $PlayerDepthChartD ->fetchArray()) {
	If ($Row['PosD']== "True"){
		echo "<tr><td class=\"STHSW140\">";
		If ($Row['Rookie']== "True"){echo "*";}
		echo $Row['Name'] . "</td>";
		echo "<td class=\"STHSW35\">AGE:" . $Row['Age'] . "</td>";
		echo "<td class=\"STHSW35\">PO:" . $Row['PO'] . "</td>";
		echo "<td class=\"STHSW35\">OV:" . $Row['Overall'] . "</td>"; 
		echo "</tr>";
		$Count++;
		If ($NumOfD == $Count){echo "</table></td><td class=\"STHSAlignTop\"><table class=\"STHSPHPTeamStatDepthChart_Table\">";}
	}	
}}
?>
</table>
</td><td class="STHSAlignTop">
<table class="STHSPHPTeamStatDepthChart_Table">
<?php
if (empty($GoalieDepthChart) == false){while ($Row = $GoalieDepthChart ->fetchArray()) {
	echo "<tr><td class=\"STHSW140\">";
	If ($Row['Rookie']== "True"){echo "*";}
	echo $Row['Name'] . "</td>";
	echo "<td class=\"STHSW35\">AGE:" . $Row['Age'] . "</td>";
	echo "<td class=\"STHSW35\">PO:" . $Row['PO'] . "</td>";
	echo "<td class=\"STHSW35\">OV:" . $Row['Overall'] . "</td>"; 
	echo "</tr>";
}}
?>
</table>
</td></tr></table>

<br />
<?php
$LoopCount = (integer)0;
$strTemp = (string)"";
if (empty($TeamProspects) == false){while ($row = $TeamProspects ->fetchArray()) {
	If (($LoopCount % 4) == 0 AND $LoopCount > 0){$strTemp = $strTemp . "</tr><tr>";}
	$LoopCount +=1;
	If ($row['URLLink'] == ""){
		$strTemp = $strTemp . "<td class=\"STHSW140\">" . $row['Name'] . "</td>";
	}else{
		$strTemp = $strTemp . "<td class=\"STHSW140\"><a href=\"" . $row['URLLink'] . "\" target=\"_blank\">" . $row['Name'] . "</a></td>";
	}
}}
If ($strTemp == ""){$strTemp = "<td></td>";} /* for HTML5 Validy */
Echo "<h3 class=\"STHSTeamProspect_Prospect\">" . $TeamLang['Prospects'] . " - " . $LoopCount . "</h3><table class=\"STHSPHPTeamStat_Table\"><tr>";
Echo $strTemp;
If (($LoopCount % 4) > 0){for($x = (4 - ($LoopCount % 4)); $x > 0; $x--){echo "<td class=\"STHSW140\"></td>";}}
?>
</tr></table>

<br />
<h3 class="STHSTeamProspect_DraftPick"><?php echo $TeamLang['DraftPicks'];?></h3>
<table class="STHSPHPTeamStat_Table"><tr><th class="STHSW140"><?php echo $TeamLang['Year'];?></th>
<?php
if (empty($TeamDraftPick) == false){
	/* Create Header Based on the $LeagueGeneral['DraftPickByYear'] Variable */
	$LoopCount = (integer)0;
	$DraftPickByYear = (integer)$LeagueGeneral['DraftPickByYear'];
	if($DraftPickByYear >= 10){$DraftPickByYear = 10;}
	for($x = 1; $x <= $LeagueGeneral['DraftPickByYear'];$x++){
		$LoopCount +=1;
		echo "<th class=\"STHSW140\">R" . $LoopCount . "</th>";
	}
	echo "</tr>\n";

	/* Very Complexe Logic to Loop throw Variable and make a valid HTML5 Table */
	$CurrentYear = (integer)0;
	$CurrentRound = (integer)0;
	while ($row = $TeamDraftPick ->fetchArray()) {
		If ($CurrentYear <> $row['Year']){
			if ($CurrentRound < $DraftPickByYear  AND $CurrentRound > 0){for($x = $CurrentRound; $x < $DraftPickByYear; $x++){echo "<td></td>";}echo "</tr>\n"; /* Code to Create Empty TD if team doesn't have last round Pick */
			}elseif ($CurrentYear > 0){echo "</td></tr>\n"; /* Close the Row for this Year */}
			$CurrentYear = $row['Year'];
			$CurrentRound = 0;
			Echo "<tr><td>" . $CurrentYear; /* Open for Row for the Year */
		}
		for($x = $CurrentRound; $x < $row['Round'];$x++){
			echo "</td><td>"; /* Close the Cell for last Round and Reopen a new one */
			$CurrentRound = $row['Round'];
		}
		echo $row['FromTeamAbbre'] . " ";
	}
	if ($CurrentRound < $DraftPickByYear  AND $CurrentRound > 0){for($x = $CurrentRound; $x < $DraftPickByYear; $x++){echo "<td></td>";}echo "</tr></table>\n";}else{echo "</td></tr></table>";} /* Code to Create Empty TD if team doesn't have last round Pick for the last year and to close the table*/
}else{
	echo "</tr></table>";
}
?>
<br /><br /></div>
<div class="tabmain " id="tabmain9">
<br />
<?php
if (empty($TeamLog ) == false){while ($row = $TeamLog ->fetchArray()) {
	echo "[" . $row['DateTime'] . "] - " . $row['Text'] . "<br />";
}}
?>
<br /><br /></div>
<div class="tabmain" id="tabmain10">
<br />
<?php 
$booFound = (bool)False;
$sngInjury = (float)0;
if (empty($TeamInjurySuspension) == false){while ($Row = $TeamInjurySuspension ->fetchArray()) { 
	if ($Row['ConditionDecimal'] <= 95){
		$booFound = True;
		if($Row['Status1'] >= 2){$sngInjury = (95 - $Row['ConditionDecimal']) / $LeagueGeneral['ProInjuryRecoverySpeed'];}else{$sngInjury = (95 - $Row['ConditionDecimal']) / $LeagueGeneral['FarmInjuryRecoverySpeed'];}
		Echo $Row['Name'] . $TeamLang['OutFor'];
		if ($Row['ConditionDecimal'] == 0){echo $TeamLang['Restoftheseason'];}elseif
		($sngInjury < 7){echo floor($sngInjury) . $TeamLang['Days'];}elseif
		($sngInjury < 13){echo $TeamLang['1Week'];}elseif
		($sngInjury < 19) {echo "2 " . $TeamLang['Weeks'];}elseif
		($sngInjury < 25){echo "3 " . $TeamLang['Weeks'];}elseif
		($sngInjury < 46){echo $TeamLang['1Month'];}elseif
		($sngInjury < 74){echo "2 " . $TeamLang['Months'];}else{
		echo "2 " . $TeamLang['Months'];}
		if ($Row['Injury'] == ""){echo $TeamLang['BecauseofFatigue'];}else{echo $TeamLang['Becauseof'] . $Row['Injury'] . ".";}
		echo "<br />\n"; /* The \n is for a new line in the HTML Code */
	}elseif($Row['Suspension'] > 0){
		$booFound = True;
		if($Row['Suspension'] == 99){echo $Row['Name'] . $TeamLang['SuspendedIndefinitely'];}else{echo $Row['Name'] . $TeamLang['SuspendedFor'] . $Row['Suspension'] . $TeamLang['MoresGames'];}
		echo "<br />\n"; /* The \n is for a new line in the HTML Code */
	}
}
If($booFound == False){echo $TeamLang['NoInjuryorSuspension'];}
}
?>
<br /><br />
</div>

<div class="tabmain" id="tabmain11">
<br />

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
if ($TeamCareerSumSeasonOnly['SumOfGP'] > 0){echo "<tr class=\"static\"><td style=\"display:block;\" colspan=\"75\"><strong>" . $PlayersLang['RegularSeason'] . "</strong></td></tr>\n";}
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
if ($TeamCareerSumSeasonOnly['SumOfGP'] > 0){
	/* Show TeamCareer Total for Season */
	echo "<tr class=\"static\"><td><strong>" . $PlayersLang['Total'] . " " . $PlayersLang['RegularSeason']. "</strong></td>";
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfGP'] . "</td>";
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfW']  . "</td>";
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfL'] . "</td>";
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfT'] . "</td>";
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfOTW'] . "</td>";	
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfOTL'] . "</td>";	
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfSOW'] . "</td>";	
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfSOL'] . "</td>";	
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfGF'] . "</td>";
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfGA'] . "</td>";
	echo "<td>" . ($TeamCareerSumSeasonOnly['SumOfGF'] - $TeamCareerSumSeasonOnly['SumOfGA']) . "</td>";	
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfHomeGP'] . "</td>";
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfHomeW']  . "</td>";
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfHomeL'] . "</td>";
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfHomeT'] . "</td>";
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfHomeOTW'] . "</td>";	
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfHomeOTL'] . "</td>";	
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfHomeSOW'] . "</td>";	
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfHomeSOL'] . "</td>";	
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfHomeGF'] . "</td>";
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfHomeGA'] . "</td>";	
	echo "<td>" . ($TeamCareerSumSeasonOnly['SumOfHomeGF'] - $TeamCareerSumSeasonOnly['SumOfHomeGA']) . "</td>";	
	echo "<td>" . ($TeamCareerSumSeasonOnly['SumOfGP'] - $TeamCareerSumSeasonOnly['SumOfHomeGP']) . "</td>";
	echo "<td>" . ($TeamCareerSumSeasonOnly['SumOfW'] - $TeamCareerSumSeasonOnly['SumOfHomeW']) . "</td>";
	echo "<td>" . ($TeamCareerSumSeasonOnly['SumOfL'] - $TeamCareerSumSeasonOnly['SumOfHomeL']) . "</td>";
	echo "<td>" . ($TeamCareerSumSeasonOnly['SumOfT'] - $TeamCareerSumSeasonOnly['SumOfHomeT']) . "</td>";	
	echo "<td>" . ($TeamCareerSumSeasonOnly['SumOfOTW'] - $TeamCareerSumSeasonOnly['SumOfHomeOTW']) . "</td>";
	echo "<td>" . ($TeamCareerSumSeasonOnly['SumOfOTL'] - $TeamCareerSumSeasonOnly['SumOfHomeOTL']) . "</td>";
	echo "<td>" . ($TeamCareerSumSeasonOnly['SumOfSOW'] - $TeamCareerSumSeasonOnly['SumOfHomeSOW']) . "</td>";
	echo "<td>" . ($TeamCareerSumSeasonOnly['SumOfSOL'] - $TeamCareerSumSeasonOnly['SumOfHomeSOL']) . "</td>";
	echo "<td>" . ($TeamCareerSumSeasonOnly['SumOfGF'] - $TeamCareerSumSeasonOnly['SumOfHomeGF']) . "</td>";
	echo "<td>" . ($TeamCareerSumSeasonOnly['SumOfGA'] - $TeamCareerSumSeasonOnly['SumOfHomeGA']) . "</td>";
	echo "<td>" . (($TeamCareerSumSeasonOnly['SumOfGF'] - $TeamCareerSumSeasonOnly['SumOfHomeGF']) - ($TeamCareerSumSeasonOnly['SumOfGA'] - $TeamCareerSumSeasonOnly['SumOfHomeGA'])) . "</td>";		
	echo "<td><strong>" . $TeamCareerSumSeasonOnly['SumOfPoints'] . "</strong></td>";
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfTotalGoal'] . "</td>";
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfTotalAssist'] . "</td>";
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfTotalPoint'] . "</td>";
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfEmptyNetGoal']. "</td>";
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfShutouts']. "</td>";		
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfGoalsPerPeriod1']. "</td>";		
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfGoalsPerPeriod2']. "</td>";	
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfGoalsPerPeriod3']. "</td>";	
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfGoalsPerPeriod4']. "</td>";	
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfShotsFor']. "</td>";	
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfShotsPerPeriod1']. "</td>";
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfShotsPerPeriod2']. "</td>";
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfShotsPerPeriod3']. "</td>";
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfShotsPerPeriod4']. "</td>";
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfShotsAga']. "</td>";
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfShotsBlock']. "</td>";		
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfPim']. "</td>";
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfHits']. "</td>";	
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfPPAttemp']. "</td>";
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfPPGoal']. "</td>";
	echo "<td>";if ($TeamCareerSumSeasonOnly['SumOfPPAttemp'] > 0){echo number_Format($TeamCareerSumSeasonOnly['SumOfPPGoal'] / $TeamCareerSumSeasonOnly['SumOfPPAttemp'] * 100,2) . "%";} else { echo "0.00%";} echo "</td>";		
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfPKAttemp']. "</td>";
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfPKGoalGA']. "</td>";
	echo "<td>";if ($TeamCareerSumSeasonOnly['SumOfPKAttemp'] > 0){echo number_Format(($TeamCareerSumSeasonOnly['SumOfPKAttemp'] - $TeamCareerSumSeasonOnly['SumOfPKGoalGA']) / $TeamCareerSumSeasonOnly['SumOfPKAttemp'] * 100,2) . "%";} else {echo "0.00%";} echo "</td>";
	echo "<td>" .  $TeamCareerSumSeasonOnly['SumOfPKGoalGF']. "</td>";	
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfFaceOffWonOffensifZone']. "</td>";
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfFaceOffTotalOffensifZone']. "</td>";		
	echo "<td>";if ($TeamCareerSumSeasonOnly['SumOfFaceOffTotalOffensifZone'] > 0){echo number_Format($TeamCareerSumSeasonOnly['SumOfFaceOffWonOffensifZone'] / $TeamCareerSumSeasonOnly['SumOfFaceOffTotalOffensifZone'] * 100,2) . "%" ;} else { echo "0.00%";} echo "</td>";	
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfFaceOffWonDefensifZone']. "</td>";
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfFaceOffTotalDefensifZone']. "</td>";
	echo "<td>";if ($TeamCareerSumSeasonOnly['SumOfFaceOffTotalDefensifZone'] > 0){echo number_Format($TeamCareerSumSeasonOnly['SumOfFaceOffWonDefensifZone'] / $TeamCareerSumSeasonOnly['SumOfFaceOffTotalDefensifZone'] * 100,2) . "%" ;} else { echo "0.00%";} echo "</td>";	
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfFaceOffWonNeutralZone']. "</td>";	
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfFaceOffTotalNeutralZone']. "</td>";	
	echo "<td>";if ($TeamCareerSumSeasonOnly['SumOfFaceOffTotalNeutralZone'] > 0){echo number_Format($TeamCareerSumSeasonOnly['SumOfFaceOffWonNeutralZone'] / $TeamCareerSumSeasonOnly['SumOfFaceOffTotalNeutralZone'] * 100,2) . "%" ;} else { echo "0.00%";} echo "</td>";	
	echo "<td>" . Floor($TeamCareerSumSeasonOnly['SumOfPuckTimeInZoneOF']/60). "</td>";
	echo "<td>" . Floor($TeamCareerSumSeasonOnly['SumOfPuckTimeControlinZoneOF']/60). "</td>";
	echo "<td>" . Floor($TeamCareerSumSeasonOnly['SumOfPuckTimeInZoneDF']/60). "</td>";
	echo "<td>" . Floor($TeamCareerSumSeasonOnly['SumOfPuckTimeControlinZoneDF']/60). "</td>";
	echo "<td>" . Floor($TeamCareerSumSeasonOnly['SumOfPuckTimeInZoneNT']/60). "</td>";		
	echo "<td>" . Floor($TeamCareerSumSeasonOnly['SumOfPuckTimeControlinZoneNT']/60). "</td>";		
	echo "</tr>\n"; /* The \n is for a new line in the HTML Code */	
}
if ($TeamCareerSumPlayoffOnly['SumOfGP'] > 0){echo "<tr class=\"static\"><td style=\"display:block;\" colspan=\"75\"><strong>" . $PlayersLang['Playoff'] . "</strong></td></tr>\n";}
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
if ($TeamCareerSumPlayoffOnly['SumOfGP'] > 0){
	/* Show TeamCareer Total for Playoff */
	echo "<tr class=\"static\"><td><strong>" . $PlayersLang['Total'] . " " . $PlayersLang['Playoff']. "</strong></td>";
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfGP'] . "</td>";
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfW']  . "</td>";
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfL'] . "</td>";
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfT'] . "</td>";
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfOTW'] . "</td>";	
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfOTL'] . "</td>";	
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfSOW'] . "</td>";	
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfSOL'] . "</td>";	
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfGF'] . "</td>";
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfGA'] . "</td>";
	echo "<td>" . ($TeamCareerSumPlayoffOnly['SumOfGF'] - $TeamCareerSumPlayoffOnly['SumOfGA']) . "</td>";	
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfHomeGP'] . "</td>";
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfHomeW']  . "</td>";
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfHomeL'] . "</td>";
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfHomeT'] . "</td>";
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfHomeOTW'] . "</td>";	
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfHomeOTL'] . "</td>";	
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfHomeSOW'] . "</td>";	
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfHomeSOL'] . "</td>";	
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfHomeGF'] . "</td>";
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfHomeGA'] . "</td>";	
	echo "<td>" . ($TeamCareerSumPlayoffOnly['SumOfHomeGF'] - $TeamCareerSumPlayoffOnly['SumOfHomeGA']) . "</td>";	
	echo "<td>" . ($TeamCareerSumPlayoffOnly['SumOfGP'] - $TeamCareerSumPlayoffOnly['SumOfHomeGP']) . "</td>";
	echo "<td>" . ($TeamCareerSumPlayoffOnly['SumOfW'] - $TeamCareerSumPlayoffOnly['SumOfHomeW']) . "</td>";
	echo "<td>" . ($TeamCareerSumPlayoffOnly['SumOfL'] - $TeamCareerSumPlayoffOnly['SumOfHomeL']) . "</td>";
	echo "<td>" . ($TeamCareerSumPlayoffOnly['SumOfT'] - $TeamCareerSumPlayoffOnly['SumOfHomeT']) . "</td>";	
	echo "<td>" . ($TeamCareerSumPlayoffOnly['SumOfOTW'] - $TeamCareerSumPlayoffOnly['SumOfHomeOTW']) . "</td>";
	echo "<td>" . ($TeamCareerSumPlayoffOnly['SumOfOTL'] - $TeamCareerSumPlayoffOnly['SumOfHomeOTL']) . "</td>";
	echo "<td>" . ($TeamCareerSumPlayoffOnly['SumOfSOW'] - $TeamCareerSumPlayoffOnly['SumOfHomeSOW']) . "</td>";
	echo "<td>" . ($TeamCareerSumPlayoffOnly['SumOfSOL'] - $TeamCareerSumPlayoffOnly['SumOfHomeSOL']) . "</td>";
	echo "<td>" . ($TeamCareerSumPlayoffOnly['SumOfGF'] - $TeamCareerSumPlayoffOnly['SumOfHomeGF']) . "</td>";
	echo "<td>" . ($TeamCareerSumPlayoffOnly['SumOfGA'] - $TeamCareerSumPlayoffOnly['SumOfHomeGA']) . "</td>";
	echo "<td>" . (($TeamCareerSumPlayoffOnly['SumOfGF'] - $TeamCareerSumPlayoffOnly['SumOfHomeGF']) - ($TeamCareerSumPlayoffOnly['SumOfGA'] - $TeamCareerSumPlayoffOnly['SumOfHomeGA'])) . "</td>";		
	echo "<td><strong>" . $TeamCareerSumPlayoffOnly['SumOfPoints'] . "</strong></td>";
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfTotalGoal'] . "</td>";
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfTotalAssist'] . "</td>";
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfTotalPoint'] . "</td>";
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfEmptyNetGoal']. "</td>";
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfShutouts']. "</td>";		
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfGoalsPerPeriod1']. "</td>";		
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfGoalsPerPeriod2']. "</td>";	
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfGoalsPerPeriod3']. "</td>";	
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfGoalsPerPeriod4']. "</td>";	
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfShotsFor']. "</td>";	
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfShotsPerPeriod1']. "</td>";
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfShotsPerPeriod2']. "</td>";
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfShotsPerPeriod3']. "</td>";
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfShotsPerPeriod4']. "</td>";
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfShotsAga']. "</td>";
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfShotsBlock']. "</td>";		
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfPim']. "</td>";
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfHits']. "</td>";	
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfPPAttemp']. "</td>";
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfPPGoal']. "</td>";
	echo "<td>";if ($TeamCareerSumPlayoffOnly['SumOfPPAttemp'] > 0){echo number_Format($TeamCareerSumPlayoffOnly['SumOfPPGoal'] / $TeamCareerSumPlayoffOnly['SumOfPPAttemp'] * 100,2) . "%";} else { echo "0.00%";} echo "</td>";		
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfPKAttemp']. "</td>";
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfPKGoalGA']. "</td>";
	echo "<td>";if ($TeamCareerSumPlayoffOnly['SumOfPKAttemp'] > 0){echo number_Format(($TeamCareerSumPlayoffOnly['SumOfPKAttemp'] - $TeamCareerSumPlayoffOnly['SumOfPKGoalGA']) / $TeamCareerSumPlayoffOnly['SumOfPKAttemp'] * 100,2) . "%";} else {echo "0.00%";} echo "</td>";
	echo "<td>" .  $TeamCareerSumPlayoffOnly['SumOfPKGoalGF']. "</td>";	
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfFaceOffWonOffensifZone']. "</td>";
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfFaceOffTotalOffensifZone']. "</td>";		
	echo "<td>";if ($TeamCareerSumPlayoffOnly['SumOfFaceOffTotalOffensifZone'] > 0){echo number_Format($TeamCareerSumPlayoffOnly['SumOfFaceOffWonOffensifZone'] / $TeamCareerSumPlayoffOnly['SumOfFaceOffTotalOffensifZone'] * 100,2) . "%" ;} else { echo "0.00%";} echo "</td>";	
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfFaceOffWonDefensifZone']. "</td>";
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfFaceOffTotalDefensifZone']. "</td>";
	echo "<td>";if ($TeamCareerSumPlayoffOnly['SumOfFaceOffTotalDefensifZone'] > 0){echo number_Format($TeamCareerSumPlayoffOnly['SumOfFaceOffWonDefensifZone'] / $TeamCareerSumPlayoffOnly['SumOfFaceOffTotalDefensifZone'] * 100,2) . "%" ;} else { echo "0.00%";} echo "</td>";	
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfFaceOffWonNeutralZone']. "</td>";	
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfFaceOffTotalNeutralZone']. "</td>";	
	echo "<td>";if ($TeamCareerSumPlayoffOnly['SumOfFaceOffTotalNeutralZone'] > 0){echo number_Format($TeamCareerSumPlayoffOnly['SumOfFaceOffWonNeutralZone'] / $TeamCareerSumPlayoffOnly['SumOfFaceOffTotalNeutralZone'] * 100,2) . "%" ;} else { echo "0.00%";} echo "</td>";	
	echo "<td>" . Floor($TeamCareerSumPlayoffOnly['SumOfPuckTimeInZoneOF']/60). "</td>";
	echo "<td>" . Floor($TeamCareerSumPlayoffOnly['SumOfPuckTimeControlinZoneOF']/60). "</td>";
	echo "<td>" . Floor($TeamCareerSumPlayoffOnly['SumOfPuckTimeInZoneDF']/60). "</td>";
	echo "<td>" . Floor($TeamCareerSumPlayoffOnly['SumOfPuckTimeControlinZoneDF']/60). "</td>";
	echo "<td>" . Floor($TeamCareerSumPlayoffOnly['SumOfPuckTimeInZoneNT']/60). "</td>";		
	echo "<td>" . Floor($TeamCareerSumPlayoffOnly['SumOfPuckTimeControlinZoneNT']/60). "</td>";		
	echo "</tr>\n"; /* The \n is for a new line in the HTML Code */	
}

echo "</tbody></table>";
?>
<br /><br /></div>

</div>
</div>
</div>

<script type="text/javascript">
$(function(){
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
      columnSelector_breakpoints : [ '50em', '60em', '70em', '80em', '90em', '95em' ],
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
      columnSelector_breakpoints : [ '50em', '60em', '70em', '80em', '90em', '95em' ],	
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
      columnSelector_breakpoints : [ '50em', '60em', '70em', '80em', '90em', '95em' ],
	  	  filter_columnFilters: true,
      filter_placeholder: { search : '<?php echo $TableSorterLang['Search'];?>' },
	  filter_searchDelay : 1000,	  
      filter_reset: '.tablesorter_Reset'
    }
  });
  $(".STHSPHPTeam_ScheduleTable").tablesorter({
    widgets: ['filter', 'staticRow'],
    widgetOptions : {
	  filter_columnFilters: true,
      filter_placeholder: { search : '<?php echo $TableSorterLang['Search'];?>' },
	  filter_searchDelay : 500,	  
      filter_reset: '.tablesorter_Reset'	 
    }
  }); 
  $(".STHSPHPTeam_PlayersScoringTable").tablesorter({
    widgets: ['columnSelector', 'stickyHeaders', 'filter'],
    widgetOptions : {
      columnSelector_container : $('#tablesorter_ColumnSelector2P'),
      columnSelector_layout : '<label><input type="checkbox">{name}</label>',
      columnSelector_name  : 'title',
      columnSelector_mediaquery: true,
      columnSelector_mediaqueryName: 'Automatic',
      columnSelector_mediaqueryState: true,
      columnSelector_mediaqueryHidden: true,
      columnSelector_breakpoints : [ '50em', '60em', '70em', '80em', '90em', '95em' ],
	  filter_columnFilters: true,
      filter_placeholder: { search : '<?php echo $TableSorterLang['Search'];?>' },
	  filter_searchDelay : 1000,	  
      filter_reset: '.tablesorter_Reset'		  
    }
  }); 
  $(".STHSPHPTeam_GoaliesScoringTable").tablesorter({
    widgets: ['columnSelector', 'stickyHeaders', 'filter'],
    widgetOptions : {
      columnSelector_container : $('#tablesorter_ColumnSelector2G'),
      columnSelector_layout : '<label><input type="checkbox">{name}</label>',
      columnSelector_name  : 'title',
      columnSelector_mediaquery: true,
      columnSelector_mediaqueryName: 'Automatic',
      columnSelector_mediaqueryState: true,
      columnSelector_mediaqueryHidden: true,
      columnSelector_breakpoints : [ '50em', '60em', '70em', '80em', '90em', '95em' ],	
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
      columnSelector_breakpoints : [ '50em', '60em', '70em', '80em', '90em', '95em' ],
	  filter_columnFilters: true,
      filter_placeholder: { search : '<?php echo $TableSorterLang['Search'];?>' },
	  filter_searchDelay : 500,	  
      filter_reset: '.tablesorter_Reset'	 
    }
  });
  <?php if ($TeamCareerStatFound == true){echo "\$(\".STHSPHPTeam_TeamCareerStat\").tablesorter({widgets: ['staticRow', 'columnSelector','filter'], widgetOptions : {columnSelector_container : \$('#tablesorter_ColumnSelector11'), columnSelector_layout : '<label><input type=\"checkbox\">{name}</label>', columnSelector_name  : 'title', columnSelector_mediaquery: true, columnSelector_mediaqueryName: 'Automatic', columnSelector_mediaqueryState: true, columnSelector_mediaqueryHidden: true, columnSelector_breakpoints : [ '50em', '60em', '70em', '80em', '90em', '95em' ],filter_columnFilters: false,}});";}?>
});
</script>

<?php include "Footer.php";?>