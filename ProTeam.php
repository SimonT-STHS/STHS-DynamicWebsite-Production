<?php include "Header.php";
/*
Syntax to call this webpage should be ProTeam.php?Team=2 where only the number change and it's based on the Tean Number Field.
SubMenu : 0 = Home / 1 = Roster / 2 = Scoring / 3 = PlayerInfo / 4 = Lines / 5 = Team Stats / 6 = Schedule / 7 =Finance / 8 = Depth / 9 =History / 10 = Last Transactions / 11 = CareerStat  / 12 = InjurySuspension / 13 = News
*/
If ($lang == "fr"){include 'LanguageFR-League.php';}else{include 'LanguageEN-League.php';}
If ($lang == "fr"){include 'LanguageFR-Main.php';}else{include 'LanguageEN-Main.php';}
If ($lang == "fr"){include 'LanguageFR-Stat.php';}else{include 'LanguageEN-Stat.php';}
$HistoryOutput = (boolean)False;
$Team = (integer)0;
$TypeText = (string)"Pro";
$LeagueName = (string)"";
$TeamCareerStatFound = (boolean)false;
$OtherTeam = (integer)0;
$Query = (string)"";
$TeamName = $TeamLang['IncorrectTeam'];
$CareerLeaderSubPrintOut = (int)0;
if(isset($_GET['Team'])){$Team = filter_var($_GET['Team'], FILTER_SANITIZE_NUMBER_INT);} 
$SubMenu = 0;
if(isset($_GET['SubMenu'])){$SubMenu = filter_var($_GET['SubMenu'], FILTER_SANITIZE_NUMBER_INT);} 
if($SubMenu < 0 OR $SubMenu > 12){$SubMenu = 0;}

try{
If (file_exists($DatabaseFile) == false){
	$Team = 0;
	$TeamName = $DatabaseNotFound;
}else{
	$db = new SQLite3($DatabaseFile);
	$Query = "Select Name FROM LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];	
}
If($Team == 0 AND $CookieTeamNumber > 0 AND $CookieTeamNumber <= 100){$Team = $CookieTeamNumber;} // If no team in URL, check the Cookie Team Number and show this Team
If ($Team == 0 OR $Team > 100){
	Goto STHSErrorProTeam;
}else{
	$Query = "SELECT count(*) AS count FROM TeamProInfo WHERE Number = " . $Team;
	$Result = $db->querySingle($Query,true);
	If ($Result['count'] == 1){
		If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS Start Page PHP Performance : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}
		$Query = "Select PlayersMugShotBaseURL, PlayersMugShotFileExtension, OutputSalariesRemaining, OutputSalariesAverageTotal, OutputSalariesAverageRemaining, InchInsteadofCM, LBSInsteadofKG, FreeAgentUseDateInsteadofDay, ScheduleUseDateInsteadofDay, ScheduleRealDate, ShowWebClientInDymanicWebsite,JerseyNumberInWebsite,MergeRosterPlayerInfo,MergeProFarmRoster, NumberofNewsinPHPHomePage, SeparateCareerStatFromTeamPage, ShowBannerForTeam from LeagueOutputOption";
		$LeagueOutputOption = $db->querySingle($Query,true);	
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
		$Query = "SELECT MainTable.* FROM (SELECT TeamProStatVS.TeamVSName AS Name, TeamProStatVS.TeamVSNumber AS Number, TeamProStatVS.TeamVSNumberThemeID as TeamThemeID, TeamProStatVS.GP, TeamProStatVS.W, TeamProStatVS.L, TeamProStatVS.T, TeamProStatVS.OTW, TeamProStatVS.OTL, TeamProStatVS.SOW, TeamProStatVS.SOL, TeamProStatVS.Points, TeamProStatVS.GF, TeamProStatVS.GA, TeamProStatVS.HomeGP, TeamProStatVS.HomeW, TeamProStatVS.HomeL, TeamProStatVS.HomeT, TeamProStatVS.HomeOTW, TeamProStatVS.HomeOTL, TeamProStatVS.HomeSOW, TeamProStatVS.HomeSOL, TeamProStatVS.HomeGF, TeamProStatVS.HomeGA, TeamProStatVS.PPAttemp, TeamProStatVS.PPGoal, TeamProStatVS.PKAttemp, TeamProStatVS.PKGoalGA, TeamProStatVS.PKGoalGF, TeamProStatVS.ShotsFor, TeamProStatVS.ShotsAga, TeamProStatVS.ShotsBlock, TeamProStatVS.ShotsPerPeriod1, TeamProStatVS.ShotsPerPeriod2, TeamProStatVS.ShotsPerPeriod3, TeamProStatVS.ShotsPerPeriod4, TeamProStatVS.GoalsPerPeriod1, TeamProStatVS.GoalsPerPeriod2, TeamProStatVS.GoalsPerPeriod3, TeamProStatVS.GoalsPerPeriod4, TeamProStatVS.PuckTimeInZoneDF, TeamProStatVS.PuckTimeInZoneOF, TeamProStatVS.PuckTimeInZoneNT, TeamProStatVS.PuckTimeControlinZoneDF, TeamProStatVS.PuckTimeControlinZoneOF, TeamProStatVS.PuckTimeControlinZoneNT, TeamProStatVS.Shutouts, TeamProStatVS.TotalGoal, TeamProStatVS.TotalAssist, TeamProStatVS.TotalPoint, TeamProStatVS.Pim, TeamProStatVS.Hits, TeamProStatVS.FaceOffWonDefensifZone, TeamProStatVS.FaceOffTotalDefensifZone, TeamProStatVS.FaceOffWonOffensifZone, TeamProStatVS.FaceOffTotalOffensifZone, TeamProStatVS.FaceOffWonNeutralZone, TeamProStatVS.FaceOffTotalNeutralZone, TeamProStatVS.EmptyNetGoal FROM TeamProStatVS WHERE GP > 0 AND TeamNumber = " . $Team . " UNION ALL SELECT 'Total' as Name, '104' as Number, '0' as TeamThemeID, TeamProStat.GP, TeamProStat.W, TeamProStat.L, TeamProStat.T, TeamProStat.OTW, TeamProStat.OTL, TeamProStat.SOW, TeamProStat.SOL, TeamProStat.Points, TeamProStat.GF, TeamProStat.GA, TeamProStat.HomeGP, TeamProStat.HomeW, TeamProStat.HomeL, TeamProStat.HomeT, TeamProStat.HomeOTW, TeamProStat.HomeOTL, TeamProStat.HomeSOW, TeamProStat.HomeSOL, TeamProStat.HomeGF, TeamProStat.HomeGA,  TeamProStat.PPAttemp, TeamProStat.PPGoal, TeamProStat.PKAttemp, TeamProStat.PKGoalGA, TeamProStat.PKGoalGF, TeamProStat.ShotsFor, TeamProStat.ShotsAga, TeamProStat.ShotsBlock, TeamProStat.ShotsPerPeriod1, TeamProStat.ShotsPerPeriod2, TeamProStat.ShotsPerPeriod3, TeamProStat.ShotsPerPeriod4, TeamProStat.GoalsPerPeriod1, TeamProStat.GoalsPerPeriod2, TeamProStat.GoalsPerPeriod3, TeamProStat.GoalsPerPeriod4, TeamProStat.PuckTimeInZoneDF, TeamProStat.PuckTimeInZoneOF, TeamProStat.PuckTimeInZoneNT, TeamProStat.PuckTimeControlinZoneDF, TeamProStat.PuckTimeControlinZoneOF, TeamProStat.PuckTimeControlinZoneNT, TeamProStat.Shutouts, TeamProStat.TotalGoal, TeamProStat.TotalAssist, TeamProStat.TotalPoint, TeamProStat.Pim, TeamProStat.Hits, TeamProStat.FaceOffWonDefensifZone, TeamProStat.FaceOffTotalDefensifZone, TeamProStat.FaceOffWonOffensifZone, TeamProStat.FaceOffTotalOffensifZone, TeamProStat.FaceOffWonNeutralZone, TeamProStat.FaceOffTotalNeutralZone, TeamProStat.EmptyNetGoal FROM TeamProStat WHERE Number = " . $Team . ") AS MainTable ORDER BY CASE WHEN Number > 100 THEN 2 ELSE 1 END, MainTable.Name";
		$TeamStatSub = $db->query($Query);
		If ($LeagueOutputOption['MergeProFarmRoster'] == "True"){
			$Query = "SELECT * FROM PlayerInfo WHERE Team = " . $Team . " Order By PosD, Overall DESC";
		}else{
			$Query = "SELECT * FROM PlayerInfo WHERE Team = " . $Team . " AND Status1 >= 2 Order By PosD, Overall DESC";
		}
		$PlayerRoster = $db->query($Query);
		$Query = "SELECT Avg(PlayerInfo.ConditionDecimal) AS AvgOfConditionDecimal, Avg(PlayerInfo.CK) AS AvgOfCK, Avg(PlayerInfo.FG) AS AvgOfFG, Avg(PlayerInfo.DI) AS AvgOfDI, Avg(PlayerInfo.SK) AS AvgOfSK, Avg(PlayerInfo.ST) AS AvgOfST, Avg(PlayerInfo.EN) AS AvgOfEN, Avg(PlayerInfo.DU) AS AvgOfDU, Avg(PlayerInfo.PH) AS AvgOfPH, Avg(PlayerInfo.FO) AS AvgOfFO, Avg(PlayerInfo.PA) AS AvgOfPA, Avg(PlayerInfo.SC) AS AvgOfSC, Avg(PlayerInfo.DF) AS AvgOfDF, Avg(PlayerInfo.PS) AS AvgOfPS, Avg(PlayerInfo.EX) AS AvgOfEX, Avg(PlayerInfo.LD) AS AvgOfLD, Avg(PlayerInfo.PO) AS AvgOfPO, Avg(PlayerInfo.MO) AS AvgOfMO, Avg(PlayerInfo.Overall) AS AvgOfOverall FROM PlayerInfo WHERE Team = " . $Team;
		If ($LeagueOutputOption['MergeProFarmRoster'] == "False"){}else{$Query = $Query . " AND Status1 >= 2";}		
		$PlayerRosterAverage = $db->querySingle($Query,True);	
		$Query = "SELECT GoalerInfo.Team, GoalerInfo.Status1, Avg(GoalerInfo.ConditionDecimal) AS AvgOfConditionDecimal, Avg(GoalerInfo.SK) AS AvgOfSK, Avg(GoalerInfo.DU) AS AvgOfDU, Avg(GoalerInfo.EN) AS AvgOfEN, Avg(GoalerInfo.SZ) AS AvgOfSZ, Avg(GoalerInfo.AG) AS AvgOfAG, Avg(GoalerInfo.RB) AS AvgOfRB, Avg(GoalerInfo.SC) AS AvgOfSC, Avg(GoalerInfo.HS) AS AvgOfHS, Avg(GoalerInfo.RT) AS AvgOfRT, Avg(GoalerInfo.PH) AS AvgOfPH, Avg(GoalerInfo.PS) AS AvgOfPS, Avg(GoalerInfo.EX) AS AvgOfEX, Avg(GoalerInfo.LD) AS AvgOfLD, Avg(GoalerInfo.PO) AS AvgOfPO, Avg(GoalerInfo.MO) AS AvgOfMO, Avg(GoalerInfo.Overall) AS AvgOfOverall FROM GoalerInfo WHERE Team = " . $Team;
		If ($LeagueOutputOption['MergeProFarmRoster'] == "False"){}else{$Query = $Query . " AND Status1 >= 2";}	
		$GoalieRosterAverage = $db->querySingle($Query,True);
		$Query = "SELECT MainTable.* FROM (SELECT PlayerInfo.Number, PlayerInfo.Name, PlayerInfo.Team, PlayerInfo.TeamName, PlayerInfo.ProTeamName, PlayerInfo.TeamThemeID, PlayerInfo.Age, PlayerInfo.AgeDate, PlayerInfo.Weight, PlayerInfo.Height, PlayerInfo.Contract, PlayerInfo.Rookie, PlayerInfo.NoTrade, PlayerInfo.CanPlayPro, PlayerInfo.CanPlayFarm, PlayerInfo.ForceWaiver, PlayerInfo.WaiverPossible, PlayerInfo.ExcludeSalaryCap, PlayerInfo.ProSalaryinFarm, PlayerInfo.SalaryAverage, PlayerInfo.Salary1, PlayerInfo.Salary2, PlayerInfo.Salary3, PlayerInfo.Salary4, PlayerInfo.Salary5, PlayerInfo.Salary6, PlayerInfo.Salary7, PlayerInfo.Salary8, PlayerInfo.Salary9, PlayerInfo.Salary10, PlayerInfo.NoTrade1, PlayerInfo.NoTrade2, PlayerInfo.NoTrade3, PlayerInfo.NoTrade4, PlayerInfo.NoTrade5, PlayerInfo.NoTrade6, PlayerInfo.NoTrade7, PlayerInfo.NoTrade8, PlayerInfo.NoTrade9, PlayerInfo.NoTrade10, PlayerInfo.SalaryRemaining, PlayerInfo.SalaryAverageRemaining, PlayerInfo.SalaryCap, PlayerInfo.SalaryCapRemaining, PlayerInfo.Condition, PlayerInfo.ConditionDecimal,PlayerInfo.Status1, PlayerInfo.URLLink, PlayerInfo.NHLID, PlayerInfo.AvailableForTrade, PlayerInfo.PosC, PlayerInfo.PosLW, PlayerInfo.PosRW, PlayerInfo.PosD, 'False' AS PosG, PlayerInfo.AcquiredType as AcquiredType, PlayerInfo.LastTradeDate as LastTradeDate, PlayerInfo.ContractSignatureDate As ContractSignatureDate, PlayerInfo.ForceUFA As ForceUFA, PlayerInfo.EmergencyRecall As EmergencyRecall, PlayerInfo.Retire as Retire FROM PlayerInfo Where Team =" . $Team . " AND Status1 >= 2 UNION ALL SELECT GoalerInfo.Number, GoalerInfo.Name, GoalerInfo.Team, GoalerInfo.TeamName, GoalerInfo.ProTeamName, GoalerInfo.TeamThemeID, GoalerInfo.Age, GoalerInfo.AgeDate,GoalerInfo.Weight, GoalerInfo.Height, GoalerInfo.Contract, GoalerInfo.Rookie, GoalerInfo.NoTrade, GoalerInfo.CanPlayPro, GoalerInfo.CanPlayFarm, GoalerInfo.ForceWaiver, GoalerInfo.WaiverPossible, GoalerInfo.ExcludeSalaryCap, GoalerInfo.ProSalaryinFarm, GoalerInfo.SalaryAverage, GoalerInfo.Salary1, GoalerInfo.Salary2, GoalerInfo.Salary3, GoalerInfo.Salary4, GoalerInfo.Salary5, GoalerInfo.Salary6, GoalerInfo.Salary7, GoalerInfo.Salary8, GoalerInfo.Salary9, GoalerInfo.Salary10, GoalerInfo.NoTrade1, GoalerInfo.NoTrade2, GoalerInfo.NoTrade3, GoalerInfo.NoTrade4, GoalerInfo.NoTrade5, GoalerInfo.NoTrade6, GoalerInfo.NoTrade7, GoalerInfo.NoTrade8, GoalerInfo.NoTrade9, GoalerInfo.NoTrade10, GoalerInfo.SalaryRemaining, GoalerInfo.SalaryAverageRemaining, GoalerInfo.SalaryCap, GoalerInfo.SalaryCapRemaining, GoalerInfo.Condition, GoalerInfo.ConditionDecimal, GoalerInfo.Status1, GoalerInfo.URLLink, GoalerInfo.NHLID, GoalerInfo.AvailableForTrade,'False' AS PosC, 'False' AS PosLW, 'False' AS PosRW, 'False' AS PosD, 'True' AS PosG, GoalerInfo.AcquiredType as AcquiredType, GoalerInfo.LastTradeDate as LastTradeDate, GoalerInfo.ContractSignatureDate As ContractSignatureDate, GoalerInfo.ForceUFA As ForceUFA, GoalerInfo.EmergencyRecall As EmergencyRecall, GoalerInfo.Retire as Retire FROM GoalerInfo Where Team =" . $Team . "  AND Status1 >= 2) AS MainTable ORDER BY MainTable.Name";
		$PlayerInfo = $db->query($Query);		
		$Query = "SELECT Count(MainTable.Name) AS CountOfName, Avg(MainTable.Age) AS AvgOfAge, Avg(MainTable.Weight) AS AvgOfWeight, Avg(MainTable.Height) AS AvgOfHeight, Avg(MainTable.Contract) AS AvgOfContract, Avg(MainTable.Salary1) AS AvgOfSalary1, Sum(MainTable.Salary1) AS SumOfSalary1, Sum(MainTable.Salary2) AS SumOfSalary2, Sum(MainTable.Salary3) AS SumOfSalary3, Sum(MainTable.Salary4) AS SumOfSalary4, Sum(MainTable.Salary5) AS SumOfSalary5 FROM (SELECT PlayerInfo.Name, PlayerInfo.Team, PlayerInfo.Age, PlayerInfo.Weight, PlayerInfo.Height, PlayerInfo.Contract, PlayerInfo.Salary1, PlayerInfo.Salary2, PlayerInfo.Salary3, PlayerInfo.Salary4, PlayerInfo.Salary5, PlayerInfo.Status1 FROM PlayerInfo WHERE Team = " . $Team . " and Status1 >= 2 UNION ALL SELECT GoalerInfo.Name, GoalerInfo.Team, GoalerInfo.Age, GoalerInfo.Weight, GoalerInfo.Height, GoalerInfo.Contract, GoalerInfo.Salary1, GoalerInfo.Salary2, GoalerInfo.Salary3, GoalerInfo.Salary4, GoalerInfo.Salary5, GoalerInfo.Status1 FROM GoalerInfo WHERE Team= " . $Team . "  AND Status1 >= 2) AS MainTable";
		$PlayerInfoAverage = $db->querySingle($Query,true);
		$Query = "SELECT Count(MainTable.Name) AS CountOfName, Avg(MainTable.Age) AS AvgOfAge, Avg(MainTable.Weight) AS AvgOfWeight, Avg(MainTable.Height) AS AvgOfHeight, Avg(MainTable.Contract) AS AvgOfContract, Avg(MainTable.Salary1) AS AvgOfSalary1, Sum(MainTable.Salary1) AS SumOfSalary1, Sum(MainTable.Salary2) AS SumOfSalary2, Sum(MainTable.Salary3) AS SumOfSalary3, Sum(MainTable.Salary4) AS SumOfSalary4, Sum(MainTable.Salary5) AS SumOfSalary5 FROM (SELECT PlayerInfo.Name, PlayerInfo.Team, PlayerInfo.Age, PlayerInfo.Weight, PlayerInfo.Height, PlayerInfo.Contract, PlayerInfo.Salary1, PlayerInfo.Salary2, PlayerInfo.Salary3, PlayerInfo.Salary4, PlayerInfo.Salary5, PlayerInfo.Status1 FROM PlayerInfo WHERE Team = " . $Team . " UNION ALL SELECT GoalerInfo.Name, GoalerInfo.Team, GoalerInfo.Age, GoalerInfo.Weight, GoalerInfo.Height, GoalerInfo.Contract, GoalerInfo.Salary1, GoalerInfo.Salary2, GoalerInfo.Salary3, GoalerInfo.Salary4, GoalerInfo.Salary5, GoalerInfo.Status1 FROM GoalerInfo WHERE Team= " . $Team . ") AS MainTable";
		$PlayerInfoTotalAverage = $db->querySingle($Query,true);		
		$Query = "SELECT PlayerProStat.*, PlayerInfo.TeamName, PlayerInfo.PosC, PlayerInfo.PosLW, PlayerInfo.PosRW, PlayerInfo.PosD, PlayerInfo.TeamThemeID, ROUND((CAST(PlayerProStat.G AS REAL) / (PlayerProStat.Shots))*100,2) AS ShotsPCT, ROUND((CAST(PlayerProStat.SecondPlay AS REAL) / 60 / (PlayerProStat.GP)),2) AS AMG,ROUND((CAST(PlayerProStat.FaceOffWon AS REAL) / (PlayerProStat.FaceOffTotal))*100,2) as FaceoffPCT,ROUND((CAST(PlayerProStat.P AS REAL) / (PlayerProStat.SecondPlay) * 60 * 20),2) AS P20 FROM PlayerInfo INNER JOIN PlayerProStat ON PlayerInfo.Number = PlayerProStat.Number WHERE ((PlayerInfo.Team=" . $Team . ") AND (PlayerProStat.SecondPlay>0)) ORDER BY PlayerProStat.P DESC";
		$PlayerStat = $db->query($Query);
		$Query = "SELECT Sum(PlayerProStat.GP) AS SumOfGP, Sum(PlayerProStat.Shots) AS SumOfShots, Sum(PlayerProStat.G) AS SumOfG, Sum(PlayerProStat.A) AS SumOfA, Sum(PlayerProStat.P) AS SumOfP, Sum(PlayerProStat.PlusMinus) AS SumOfPlusMinus, Sum(PlayerProStat.Pim) AS SumOfPim, Sum(PlayerProStat.Pim5) AS SumOfPim5, Sum(PlayerProStat.ShotsBlock) AS SumOfShotsBlock, Sum(PlayerProStat.OwnShotsBlock) AS SumOfOwnShotsBlock, Sum(PlayerProStat.OwnShotsMissGoal) AS SumOfOwnShotsMissGoal, Sum(PlayerProStat.Hits) AS SumOfHits, Sum(PlayerProStat.HitsTook) AS SumOfHitsTook, Sum(PlayerProStat.GW) AS SumOfGW, Sum(PlayerProStat.GT) AS SumOfGT, Sum(PlayerProStat.FaceOffWon) AS SumOfFaceOffWon, Sum(PlayerProStat.FaceOffTotal) AS SumOfFaceOffTotal, Sum(PlayerProStat.PenalityShotsScore) AS SumOfPenalityShotsScore, Sum(PlayerProStat.PenalityShotsTotal) AS SumOfPenalityShotsTotal, Sum(PlayerProStat.EmptyNetGoal) AS SumOfEmptyNetGoal, Sum(PlayerProStat.SecondPlay) AS SumOfSecondPlay, Sum(PlayerProStat.HatTrick) AS SumOfHatTrick, Sum(PlayerProStat.PPG) AS SumOfPPG, Sum(PlayerProStat.PPA) AS SumOfPPA, Sum(PlayerProStat.PPP) AS SumOfPPP, Sum(PlayerProStat.PPShots) AS SumOfPPShots, Sum(PlayerProStat.PPSecondPlay) AS SumOfPPSecondPlay, Sum(PlayerProStat.PKG) AS SumOfPKG, Sum(PlayerProStat.PKA) AS SumOfPKA, Sum(PlayerProStat.PKP) AS SumOfPKP, Sum(PlayerProStat.PKShots) AS SumOfPKShots, Sum(PlayerProStat.PKSecondPlay) AS SumOfPKSecondPlay, Sum(PlayerProStat.GiveAway) AS SumOfGiveAway, Sum(PlayerProStat.TakeAway) AS SumOfTakeAway, Sum(PlayerProStat.PuckPossesionTime) AS SumOfPuckPossesionTime, Sum(PlayerProStat.FightW) AS SumOfFightW, Sum(PlayerProStat.FightL) AS SumOfFightL, Sum(PlayerProStat.FightT) AS SumOfFightT, Sum(PlayerProStat.Star1) AS SumOfStar1, Sum(PlayerProStat.Star2) AS SumOfStar2, Sum(PlayerProStat.Star3) AS SumOfStar3, ROUND((CAST(Sum(PlayerProStat.G) AS REAL) / (Sum(PlayerProStat.Shots)))*100,2) AS SumOfShotsPCT, ROUND((CAST(Sum(PlayerProStat.SecondPlay) AS REAL) / 60 / (Sum(PlayerProStat.GP))),2) AS SumOfAMG, ROUND((CAST(Sum(PlayerProStat.FaceOffWon) AS REAL) / (Sum(PlayerProStat.FaceOffTotal)))*100,2) as SumOfFaceoffPCT, ROUND((CAST(Sum(PlayerProStat.P) AS REAL) / (Sum(PlayerProStat.SecondPlay)) * 60 * 20),2) AS SumOfP20 FROM PlayerInfo INNER JOIN PlayerProStat ON PlayerInfo.Number = PlayerProStat.Number WHERE ((PlayerInfo.Team=" . $Team . ")  AND (PlayerProStat.SecondPlay>0)) ORDER BY PlayerProStat.P DESC";
		$PlayerStatTeam = $db->querySingle($Query,true);	
		$Query = "SELECT GoalerProStat.*, GoalerInfo.TeamName, GoalerInfo.TeamThemeID, ROUND((CAST(GoalerProStat.GA AS REAL) / (GoalerProStat.SecondPlay / 60))*60,3) AS GAA, ROUND((CAST(GoalerProStat.SA - GoalerProStat.GA AS REAL) / (GoalerProStat.SA)),3) AS PCT, ROUND((CAST(GoalerProStat.PenalityShotsShots - GoalerProStat.PenalityShotsGoals AS REAL) / (GoalerProStat.PenalityShotsShots)),3) AS PenalityShotsPCT FROM GoalerInfo INNER JOIN GoalerProStat ON GoalerInfo.Number = GoalerProStat.Number WHERE ((GoalerInfo.Team)=" . $Team . ") AND (GoalerProStat.SecondPlay>0) ORDER BY GoalerProStat.W DESC";
		$GoalieStat = $db->query($Query);
		$Query = "SELECT Sum(GoalerProStat.GP) AS SumOfGP, Sum(GoalerProStat.SecondPlay) AS SumOfSecondPlay, Sum(GoalerProStat.W) AS SumOfW, Sum(GoalerProStat.L) AS SumOfL, Sum(GoalerProStat.OTL) AS SumOfOTL, Sum(GoalerProStat.Shootout) AS SumOfShootout, Sum(GoalerProStat.GA) AS SumOfGA, Sum(GoalerProStat.SA) AS SumOfSA, Sum(GoalerProStat.SARebound) AS SumOfSARebound, Sum(GoalerProStat.Pim) AS SumOfPim, Sum(GoalerProStat.A) AS SumOfA, Sum(GoalerProStat.PenalityShotsShots) AS SumOfPenalityShotsShots, Sum(GoalerProStat.PenalityShotsGoals) AS SumOfPenalityShotsGoals, Sum(GoalerProStat.StartGoaler) AS SumOfStartGoaler, Sum(GoalerProStat.BackupGoaler) AS SumOfBackupGoaler, Sum(GoalerProStat.EmptyNetGoal) AS SumOfEmptyNetGoal, Sum(GoalerProStat.Star1) AS SumOfStar1, Sum(GoalerProStat.Star2) AS SumOfStar2, Sum(GoalerProStat.Star3) AS SumOfStar3, ROUND((CAST(Sum(GoalerProStat.GA) AS REAL) / (Sum(GoalerProStat.SecondPlay) / 60))*60,3) AS SumOfGAA, ROUND((CAST(Sum(GoalerProStat.SA) - Sum(GoalerProStat.GA) AS REAL) / (Sum(GoalerProStat.SA))),3) AS SumOfPCT, ROUND((CAST(Sum(GoalerProStat.PenalityShotsShots) - Sum(GoalerProStat.PenalityShotsGoals) AS REAL) / (Sum(GoalerProStat.PenalityShotsShots))),3) AS SumOfPenalityShotsPCT FROM GoalerInfo INNER JOIN GoalerProStat ON GoalerInfo.Number = GoalerProStat.Number WHERE ((GoalerInfo.Team)=" . $Team . ") AND (GoalerProStat.SecondPlay>0) ORDER BY GoalerProStat.W DESC";
		$GoalieStatTeam = $db->querySingle($Query,true);
		If ($LeagueOutputOption['MergeProFarmRoster'] == "True"){
			$Query = "SELECT * FROM GoalerInfo WHERE Team = " . $Team . " ORDER By Overall DESC";
		}else{
			$Query = "SELECT * FROM GoalerInfo WHERE Team = " . $Team . " AND Status1 >= 2 ORDER By Overall DESC";
		}
		$GoalieRoster = $db->query($Query);
		$Query = "SELECT * FROM SchedulePro WHERE (VisitorTeam = " . $Team . " OR HomeTeam = " . $Team . ") ORDER BY GameNumber";
		$Schedule = $db->query($Query);
		$Query = "SELECT * FROM SchedulePro WHERE (VisitorTeam = " . $Team . " OR HomeTeam = " . $Team . ") AND Play = 'False' ORDER BY GameNumber LIMIT 1";
		$ScheduleNext = $db->querySingle($Query,true);		
		$Query = "SELECT CoachInfo.* FROM CoachInfo INNER JOIN TeamProInfo ON CoachInfo.Number = TeamProInfo.CoachID WHERE (CoachInfo.Team)=" . $Team;
		$CoachInfo = $db->querySingle($Query,true);	
		$Query = "SELECT * FROM ProRivalryInfo WHERE Team1 = " . $Team . " ORDER By TEAM2";
		$RivalryInfo = $db->query($Query);		
		$Query = "Select Name, LeagueYear, PointSystemW, PointSystemSO, OffSeason, LeagueYearOutput, ProInjuryRecoverySpeed, FarmInjuryRecoverySpeed, ProScheduleTotalDay, ScheduleNextDay, RFAAge, UFAAge, DraftPickByYear, DefaultSimulationPerDay, TradeDeadLine, PreSeasonSchedule, PlayOffStarted from LeagueGeneral";
		$LeagueGeneral = $db->querySingle($Query,true);
		$Query = "Select FarmEnable from LeagueSimulation";
		$LeagueSimulation = $db->querySingle($Query,true);
		$Query = "Select RemoveSalaryCapWhenPlayerUnderCondition, ProMinimumSalaryCap,ProSalaryCapValue, SalaryCapOption from LeagueFinance";
		$LeagueFinance = $db->querySingle($Query,true);		
		$Query = "Select ProPlayerLimit, MinimumPlayerPerTeam, MaximumPlayerPerTeam, ProCustomOTLines from LeagueWebClient";
		$LeagueWebClient = $db->querySingle($Query,true);	
		$Query = "SELECT * FROM TeamProLines WHERE TeamNumber = " . $Team . " AND Day = 1";
		$TeamLines = $db->querySingle($Query,true);
		$Query = "SELECT * FROM TeamLog WHERE TeamNumber = " . $Team . " ORDER By Number DESC";
		$TeamLog = $db->query($Query);	
		$Query = "SELECT LeagueLog.* FROM LeagueLog WHERE LeagueLog.Text LIKE \"%" . $TeamInfo['Name'] . "%\" OR LeagueLog.Text LIKE \"%" . $TeamFarmInfo['Name'] . "%\" ORDER BY LeagueLog.Number DESC LIMIT 50";
		$TeamTransaction = $db->query($Query);	
		$Query = "SELECT Prospects.*, TeamProInfo.Name As TeamName, TeamProInfo.TeamThemeID  FROM Prospects LEFT JOIN TeamProInfo ON Prospects.TeamNumber = TeamProInfo.Number WHERE TeamNumber = " . $Team . " ORDER By Name";
		$Prospects = $db->query($Query);
		$Query = "SELECT Count(Prospects.Name) As CountOfName FROM Prospects WHERE TeamNumber = " . $Team;
		$ProspectsCount = $db->querySingle($Query,true);	
		$Query = "SELECT * FROM DraftPick WHERE TeamNumber = " . $Team . " ORDER By Year, Round";
		$TeamDraftPick = $db->query($Query);
		$Query = "SELECT * FROM DraftPick WHERE ConditionalTrade = '" . $TeamInfo['Abbre'] . "' ORDER By Year, Round";
		$TeamDraftPickCon = $db->query($Query);			
		$Query = "SELECT GoalerInfo.Name, GoalerInfo.Status1, GoalerInfo.Team, GoalerInfo.Injury, GoalerInfo.Condition, GoalerInfo.ConditionDecimal, GoalerInfo.Suspension FROM GoalerInfo WHERE TEAM = " . $Team . " AND (Condition < 95 OR Suspension > 0) UNION ALL SELECT PlayerInfo.Name, PlayerInfo.Status1, PlayerInfo.Team, PlayerInfo.Injury, PlayerInfo.Condition, PlayerInfo.ConditionDecimal, PlayerInfo.Suspension FROM PlayerInfo WHERE TEAM = " . $Team . " AND (Condition < 95 OR Suspension > 0)";
		$TeamInjurySuspension = $db->query($Query);
		$Query = "SELECT GoalerInfo.Name, GoalerInfo.Number, GoalerInfo.Rookie, GoalerInfo.Age, GoalerInfo.PO, GoalerInfo.Overall FROM GoalerInfo WHERE (GoalerInfo.Team)=" . $Team . " ORDER By Overall DESC, PO DESC";
		$GoalieDepthChart = $db->query($Query);
		$Query = "SELECT PlayerInfo.Name, PlayerInfo.Number, PlayerInfo.PosLW, PlayerInfo.PosC, PlayerInfo.PosRW, PlayerInfo.PosD, PlayerInfo.Rookie, PlayerInfo.Age, PlayerInfo.PO, PlayerInfo.Overall FROM PlayerInfo WHERE (PlayerInfo.Team)=" . $Team . " ORDER By Overall DESC, PO DESC";
		$PlayerDepthChart = $db->query($Query);
		$Query = "SELECT TeamProInfo.Name as TeamName, PlayerInfo_1.Name As Captain, PlayerInfo_2.Name as Assistant1, PlayerInfo_3.Name as Assistant2 FROM ((TeamProInfo LEFT JOIN PlayerInfo AS PlayerInfo_1 ON TeamProInfo.Captain = PlayerInfo_1.Number) LEFT JOIN PlayerInfo AS PlayerInfo_2 ON TeamProInfo.Assistant1 = PlayerInfo_2.Number) LEFT JOIN PlayerInfo AS PlayerInfo_3 ON TeamProInfo.Assistant2 = PlayerInfo_3.Number WHERE TeamProInfo.Number = " . $Team;
		$TeamLeader = $db->querySingle($Query,true);	
		$Query = "SELECT * FROM (SELECT SchedulePro.*, 'Pro' AS Type, TeamProStatVisitor.Last10W AS VLast10W,TeamProStatVisitor.GF AS VGF,TeamProStatVisitor.GA AS VGA,TeamProStatVisitor.PKGoalGA AS VPKGA,TeamProStatVisitor.PKAttemp AS VPKAttemp,TeamProStatVisitor.PPGoal AS VPPGoal,TeamProStatVisitor.PPAttemp AS VPPAttemp,TeamProStatVisitor.GP AS VGP, TeamProStatVisitor.Last10L AS VLast10L, TeamProStatVisitor.Last10T AS VLast10T, TeamProStatVisitor.Last10OTW AS VLast10OTW, TeamProStatVisitor.Last10OTL AS VLast10OTL, TeamProStatVisitor.Last10SOW AS VLast10SOW, TeamProStatVisitor.Last10SOL AS VLast10SOL, TeamProStatVisitor.GP AS VGP, TeamProStatVisitor.W AS VW, TeamProStatVisitor.L AS VL, TeamProStatVisitor.T AS VT, TeamProStatVisitor.Points AS VPoints,TeamProStatVisitor.HomeW AS VHW,TeamProStatVisitor.HomeL AS VHL,TeamProStatVisitor.HomeOTW AS VHOTW,TeamProStatVisitor.HomeOTL AS VHOTL,TeamProStatVisitor.HomeSOW AS VHSOW,TeamProStatVisitor.HomeSOL AS VHSOL, TeamProStatVisitor.OTW AS VOTW, TeamProStatVisitor.OTL AS VOTL, TeamProStatVisitor.SOW AS VSOW, TeamProStatVisitor.SOL AS VSOL, TeamProStatVisitor.Points AS VPoints, TeamProStatVisitor.Streak AS VStreak, TeamProStatHome.Last10W AS HLast10W, TeamProStatHome.Last10L AS HLast10L, TeamProStatHome.Last10T AS HLast10T, TeamProStatHome.Last10OTW AS HLast10OTW,TeamProStatHome.PKGoalGA AS PKGA,TeamProStatHome.PKAttemp AS PKAttemp,TeamProStatHome.GF AS HGF, TeamProStatHome.GA AS HGA,TeamProStatHome.GP AS HGP,TeamProStatHome.Last10OTL AS HLast10OTL, TeamProStatHome.Last10SOW AS HLast10SOW, TeamProStatHome.Last10SOL AS HLast10SOL, TeamProStatHome.GP AS HGP, TeamProStatHome.W AS HW, TeamProStatHome.L AS HL, TeamProStatHome.Points AS HPoints,TeamProStatHome.PPAttemp AS HPPAttemp,TeamProStatHome.PKAttemp AS HPKAttemp,TeamProStatHome.PKGoalGA AS HPKGA,TeamProStatHome.PPGoal AS HPPGoal, TeamProStatHome.T AS HT, TeamProStatHome.OTW AS HOTW, TeamProStatHome.OTL AS HOTL, TeamProStatHome.SOW AS HSOW, TeamProStatHome.SOL AS HSOL, TeamProStatHome.Points AS HPoints,TeamProStatHome.HomeW AS HHW,TeamProStatHome.HomeL AS HHL,TeamProStatHome.HomeOTW AS HHOTW,TeamProStatHome.HomeOTL AS HHOTL,TeamProStatHome.HomeSOW AS HHSOW,TeamProStatHome.HomeSOL AS HHSOL, TeamProStatHome.Streak AS HStreak FROM (SchedulePRO LEFT JOIN TeamProStat AS TeamProStatHome ON SchedulePRO.HomeTeam = TeamProStatHome.Number) LEFT JOIN TeamProStat AS TeamProStatVisitor ON SchedulePRO.VisitorTeam = TeamProStatVisitor.Number WHERE Play = 'True' AND (VisitorTeam = " . $Team . " OR HomeTeam = " . $Team . ") ORDER BY GameNumber DESC LIMIT 2) ORDER BY GameNumber";
		$ScheduleLastGame = $db->query($Query);
		$Query = "SELECT SchedulePro.*, 'Pro' AS Type, TeamProStatVisitor.Last10W AS VLast10W,TeamProStatVisitor.GF AS VGF,TeamProStatVisitor.GA AS VGA,TeamProStatVisitor.PKGoalGA AS VPKGA,TeamProStatVisitor.PKAttemp AS VPKAttemp,TeamProStatVisitor.PPGoal AS VPPGoal,TeamProStatVisitor.PPAttemp AS VPPAttemp,TeamProStatVisitor.GP AS VGP, TeamProStatVisitor.Last10L AS VLast10L, TeamProStatVisitor.Last10T AS VLast10T, TeamProStatVisitor.Last10OTW AS VLast10OTW, TeamProStatVisitor.Last10OTL AS VLast10OTL, TeamProStatVisitor.Last10SOW AS VLast10SOW, TeamProStatVisitor.Last10SOL AS VLast10SOL, TeamProStatVisitor.GP AS VGP, TeamProStatVisitor.W AS VW, TeamProStatVisitor.L AS VL, TeamProStatVisitor.T AS VT, TeamProStatVisitor.Points AS VPoints,TeamProStatVisitor.HomeW AS VHW,TeamProStatVisitor.HomeL AS VHL,TeamProStatVisitor.HomeOTW AS VHOTW,TeamProStatVisitor.HomeOTL AS VHOTL,TeamProStatVisitor.HomeSOW AS VHSOW,TeamProStatVisitor.HomeSOL AS VHSOL, TeamProStatVisitor.OTW AS VOTW, TeamProStatVisitor.OTL AS VOTL, TeamProStatVisitor.SOW AS VSOW, TeamProStatVisitor.SOL AS VSOL, TeamProStatVisitor.Points AS VPoints, TeamProStatVisitor.Streak AS VStreak, TeamProStatHome.Last10W AS HLast10W, TeamProStatHome.Last10L AS HLast10L, TeamProStatHome.Last10T AS HLast10T, TeamProStatHome.Last10OTW AS HLast10OTW,TeamProStatHome.PKGoalGA AS PKGA,TeamProStatHome.PKAttemp AS PKAttemp,TeamProStatHome.GF AS HGF, TeamProStatHome.GA AS HGA,TeamProStatHome.GP AS HGP,TeamProStatHome.Last10OTL AS HLast10OTL, TeamProStatHome.Last10SOW AS HLast10SOW, TeamProStatHome.Last10SOL AS HLast10SOL, TeamProStatHome.GP AS HGP, TeamProStatHome.W AS HW, TeamProStatHome.L AS HL, TeamProStatHome.Points AS HPoints,TeamProStatHome.PPAttemp AS HPPAttemp,TeamProStatHome.PKAttemp AS HPKAttemp,TeamProStatHome.PKGoalGA AS HPKGA,TeamProStatHome.PPGoal AS HPPGoal, TeamProStatHome.T AS HT, TeamProStatHome.OTW AS HOTW, TeamProStatHome.OTL AS HOTL, TeamProStatHome.SOW AS HSOW, TeamProStatHome.SOL AS HSOL, TeamProStatHome.Points AS HPoints,TeamProStatHome.HomeW AS HHW,TeamProStatHome.HomeL AS HHL,TeamProStatHome.HomeOTW AS HHOTW,TeamProStatHome.HomeOTL AS HHOTL,TeamProStatHome.HomeSOW AS HHSOW,TeamProStatHome.HomeSOL AS HHSOL, TeamProStatHome.Streak AS HStreak FROM (SchedulePRO LEFT JOIN TeamProStat AS TeamProStatHome ON SchedulePRO.HomeTeam = TeamProStatHome.Number) LEFT JOIN TeamProStat AS TeamProStatVisitor ON SchedulePRO.VisitorTeam = TeamProStatVisitor.Number WHERE Play = 'False' AND (VisitorTeam = " . $Team . " OR HomeTeam = " . $Team . ") ORDER BY GameNumber ASC LIMIT 3";
		$ScheduleNextGame= $db->query($Query);		
		$Query = "SELECT PlayerProStat.*, PlayerInfo.TeamName, PlayerInfo.Team, PlayerInfo.PosC, PlayerInfo.PosLW, PlayerInfo.PosRW,PlayerInfo.Jersey,PlayerInfo.NHLID, PlayerInfo.PosD, ROUND((CAST(PlayerProStat.G AS REAL) / (PlayerProStat.Shots))*100,2) AS ShotsPCT, ROUND((CAST(PlayerProStat.SecondPlay AS REAL) / 60 / (PlayerProStat.GP)),2) AS AMG,ROUND((CAST(PlayerProStat.FaceOffWon AS REAL) / (PlayerProStat.FaceOffTotal))*100,2) as FaceoffPCT,ROUND((CAST(PlayerProStat.P AS REAL) / (PlayerProStat.SecondPlay) * 60 * 20),2) AS P20 FROM PlayerInfo INNER JOIN PlayerProStat ON PlayerInfo.Number = PlayerProStat.Number WHERE ((PlayerInfo.Team=" . $Team . ") AND (PlayerInfo.Status1 >= 2)  AND (PlayerProStat.GP>0)) ORDER BY PlayerProStat.G DESC, PlayerProStat.GP ASC, PlayerProStat.P DESC LIMIT 1";
		$TeamLeaderG = $db->query($Query);
		$Query = "SELECT PlayerProStat.*, PlayerInfo.TeamName, PlayerInfo.Team, PlayerInfo.PosC, PlayerInfo.PosLW, PlayerInfo.PosRW,PlayerInfo.Jersey,PlayerInfo.NHLID, PlayerInfo.PosD, ROUND((CAST(PlayerProStat.G AS REAL) / (PlayerProStat.Shots))*100,2) AS ShotsPCT, ROUND((CAST(PlayerProStat.SecondPlay AS REAL) / 60 / (PlayerProStat.GP)),2) AS AMG,ROUND((CAST(PlayerProStat.FaceOffWon AS REAL) / (PlayerProStat.FaceOffTotal))*100,2) as FaceoffPCT,ROUND((CAST(PlayerProStat.P AS REAL) / (PlayerProStat.SecondPlay) * 60 * 20),2) AS P20 FROM PlayerInfo INNER JOIN PlayerProStat ON PlayerInfo.Number = PlayerProStat.Number WHERE ((PlayerInfo.Team=" . $Team . ") AND (PlayerInfo.Status1 >= 2)  AND (PlayerProStat.GP>0))  ORDER BY PlayerProStat.A DESC, PlayerProStat.P DESC, PlayerProStat.GP ASC LIMIT 1";
		$TeamLeaderA = $db->query($Query);
		$Query = "SELECT PlayerProStat.*, PlayerInfo.TeamName, PlayerInfo.Team, PlayerInfo.PosC, PlayerInfo.PosLW, PlayerInfo.PosRW,PlayerInfo.Jersey,PlayerInfo.NHLID, PlayerInfo.PosD, ROUND((CAST(PlayerProStat.G AS REAL) / (PlayerProStat.Shots))*100,2) AS ShotsPCT, ROUND((CAST(PlayerProStat.SecondPlay AS REAL) / 60 / (PlayerProStat.GP)),2) AS AMG,ROUND((CAST(PlayerProStat.FaceOffWon AS REAL) / (PlayerProStat.FaceOffTotal))*100,2) as FaceoffPCT,ROUND((CAST(PlayerProStat.P AS REAL) / (PlayerProStat.SecondPlay) * 60 * 20),2) AS P20 FROM PlayerInfo INNER JOIN PlayerProStat ON PlayerInfo.Number = PlayerProStat.Number WHERE ((PlayerInfo.Team=" . $Team . ") AND (PlayerInfo.Status1 >= 2)  AND (PlayerProStat.GP>0)) ORDER BY PlayerProStat.P DESC, PlayerProStat.GP ASC LIMIT 1";
		$TeamLeaderP = $db->query($Query);
		$Query = "SELECT PlayerProStat.*, PlayerInfo.TeamName, PlayerInfo.Team, PlayerInfo.PosC, PlayerInfo.PosLW, PlayerInfo.PosRW,PlayerInfo.Jersey,PlayerInfo.NHLID, PlayerInfo.PosD, ROUND((CAST(PlayerProStat.G AS REAL) / (PlayerProStat.Shots))*100,2) AS ShotsPCT, ROUND((CAST(PlayerProStat.SecondPlay AS REAL) / 60 / (PlayerProStat.GP)),2) AS AMG,ROUND((CAST(PlayerProStat.FaceOffWon AS REAL) / (PlayerProStat.FaceOffTotal))*100,2) as FaceoffPCT,ROUND((CAST(PlayerProStat.P AS REAL) / (PlayerProStat.SecondPlay) * 60 * 20),2) AS P20 FROM PlayerInfo INNER JOIN PlayerProStat ON PlayerInfo.Number = PlayerProStat.Number WHERE ((PlayerInfo.Team=" . $Team . ") AND (PlayerInfo.Status1 >= 2)  AND (PlayerProStat.GP>0))  ORDER BY PlayerProStat.PlusMinus DESC, PlayerProStat.G DESC, PlayerProStat.GP ASC LIMIT 1";
		$TeamLeaderPlusMinus = $db->query($Query);
		$Query = "SELECT GoalerProStat.*, GoalerInfo.TeamName,GoalerInfo.Team,GoalerInfo.Jersey,GoalerInfo.NHLID, ROUND((CAST(GoalerProStat.GA AS REAL) / (GoalerProStat.SecondPlay / 60))*60,3) AS GAA, ROUND((CAST(GoalerProStat.SA - GoalerProStat.GA AS REAL) / (GoalerProStat.SA)),3) AS PCT, ROUND((CAST(GoalerProStat.PenalityShotsShots - GoalerProStat.PenalityShotsGoals AS REAL) / (GoalerProStat.PenalityShotsShots)),3) AS PenalityShotsPCT FROM GoalerInfo INNER JOIN GoalerProStat ON GoalerInfo.Number = GoalerProStat.Number WHERE ((GoalerInfo.Team)=" . $Team . ")  AND ((GoalerProStat.GP)>0) ORDER BY W DESC, GoalerProStat.GP DESC LIMIT 1";
		$TeamLeaderGAA = $db->query($Query);
		$Query = "SELECT GoalerProStat.*, GoalerInfo.TeamName,GoalerInfo.Team,GoalerInfo.Jersey,GoalerInfo.NHLID, ROUND((CAST(GoalerProStat.GA AS REAL) / (GoalerProStat.SecondPlay / 60))*60,3) AS GAA, ROUND((CAST(GoalerProStat.SA - GoalerProStat.GA AS REAL) / (GoalerProStat.SA)),3) AS PCT, ROUND((CAST(GoalerProStat.PenalityShotsShots - GoalerProStat.PenalityShotsGoals AS REAL) / (GoalerProStat.PenalityShotsShots)),3) AS PenalityShotsPCT FROM GoalerInfo INNER JOIN GoalerProStat ON GoalerInfo.Number = GoalerProStat.Number WHERE ((GoalerInfo.Team)=" . $Team . ")  AND ((GoalerProStat.GP)>0) ORDER BY PCT DESC, GoalerProStat.GP DESC LIMIT 1";
		$TeamLeaderSavePCT = $db->query($Query);		
		
		$LeagueName = $LeagueGeneral['Name'];
		$TeamName = $TeamInfo['Name'];	
		If (file_exists($CareerStatDatabaseFile) == true and $LeagueOutputOption['SeparateCareerStatFromTeamPage'] == "False"){ /* CareerStat */
			$TeamCareerStatFound = true;
			$CareerStatdb = new SQLite3($CareerStatDatabaseFile);
						
			$CareerDBFormatV2CheckCheck = $CareerStatdb->querySingle("SELECT Count(name) AS CountName FROM sqlite_master WHERE type='table' AND name='LeagueGeneral'",true);
			If ($CareerDBFormatV2CheckCheck['CountName'] == 1){
				$Query = "Select Count(PlayOffWinnerPro) As CupWinner From LeagueGeneral Where Playoff = 'True' AND PlayOffWinnerPro = " . $TeamInfo['UniqueID'];
				$CupWinner = $CareerStatdb->querySingle($Query,true);	
				unset($CareerStatdb);
				
				include "APIFunction.php";
				If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS CareerStat Start Page PHP Performance : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}
				$TeamCareerSeason = APIPost(array('TeamStatProHistoryAllSeasonPerYear' => '', 'Team' => $TeamInfo['UniqueID']));
				If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS CareerStat TeamCareerSeason Page PHP Performance : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}
				$TeamCareerSumSeasonOnly = APIPost(array('TeamStatProHistoryAllSeasonMerge' => '', 'Team' => $TeamInfo['UniqueID'], 'ReturnOnlyTeamData' => '' ));
				If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS CareerStat TeamCareerSumSeasonOnly  Page PHP Performance : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}
				$TeamCareerPlayoff = APIPost(array('TeamStatProHistoryAllSeasonPerYear' => '', 'Team' => $TeamInfo['UniqueID'], 'Playoff' => ''));
				If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS CareerStat TeamCareerPlayoff Page PHP Performance : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}
				$TeamCareerSumPlayoffOnly =  APIPost(array('TeamStatProHistoryAllSeasonMerge' => '', 'Team' => $TeamInfo['UniqueID'], 'ReturnOnlyTeamData' => '', 'Playoff' => '' ));
				If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS CareerStat TeamCareerSumPlayoffOnly Page PHP Performance : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}
				$TeamCareerPlayersSeasonTop5 = APIPost(array('PlayerStatProHistoryAllSeasonMerge' => '', 'Team' => $TeamInfo['UniqueID'], 'Max' => '5'));
				If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS CareerStat TeamCareerPlayersSeasonTop5 Page PHP Performance : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}
				$TeamCareerPlayersPlayoffTop5  = APIPost(array('PlayerStatProHistoryAllSeasonMerge' => '', 'Team' => $TeamInfo['UniqueID'], 'Max' => '5', 'Playoff' => '' ));
				If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS CareerStat TeamCareerPlayersPlayoffTop5 Page PHP Performance : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}
				$TeamCareerGoaliesSeasonTop5 = APIPost(array('GoalerStatProHistoryAllSeasonMerge' => '', 'Team' => $TeamInfo['UniqueID'], 'Max' => '5'));
				If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS CareerStat TeamCareerGoaliesSeasonTop5 Page PHP Performance : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}
				$TeamCareerGoaliesPlayoffTop5 = APIPost(array('GoalerStatProHistoryAllSeasonMerge' => '', 'Team' => $TeamInfo['UniqueID'], 'Max' => '5', 'Playoff' => '' ));
				If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS CareerStat TeamCareerGoaliesPlayoffTop Page PHP Performance : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}
			}else{
				
				$CupWinner = Null;
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
			$TeamCareerSeason = Null;
			$TeamCareerPlayoff = Null;
			$TeamCareerSumSeasonOnly = Null;
			$TeamCareerSumPlayoffOnly = Null;	
			$TeamCareerPlayersSeasonTop5 = Null;	
			$TeamCareerPlayersPlayoffTop5 = Null;	
			$TeamCareerGoaliesSeasonTop5 = Null;	
			$TeamCareerGoaliesPlayoffTop5 = Null;	
		}
		
		If (file_exists($NewsDatabaseFile) == false){
			$LeagueNews = Null;
		}else{
			$dbNews = new SQLite3($NewsDatabaseFile);
			$Query = "Select LeagueNews.*, TeamProInfo.TeamThemeID, TeamProInfo.Name FROM LeagueNews LEFT JOIN TeamProInfo ON LeagueNews.TeamNumber = TeamProInfo.Number WHERE Remove = 'False' AND TeamNumber = " . $TeamInfo['UniqueID'] . " ORDER BY Time DESC";
			$dbNews -> query("ATTACH DATABASE '".realpath($DatabaseFile)."' AS CurrentDB");			
			$LeagueNews = $dbNews->query($Query);
		}
		If ($LeagueOutputOption['ShowBannerForTeam'] == "True"){echo "<link href=\"" . $CSSJSCDNPath . "STHSBanner.css\" rel=\"stylesheet\" type=\"text/css\">";}
	}else{
		Goto STHSErrorProTeam;
	}
}} catch (Exception $e) {
STHSErrorProTeam:
	$Team = 0;
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
	$TeamDraftPickCon = Null;
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
	$LeagueNews = Null;
	$ScheduleLastGame = Null;
	$ScheduleNextGame = Null;
	$TeamLeaderG = Null;
	$TeamLeaderA = Null;
	$TeamLeaderP = Null;
	$TeamLeaderPlusMinus = Null;
	$TeamLeaderGAA = Null;
	$TeamLeaderSavePCT = Null;		
	$CupWinner = Null;
	$TeamCareerPlayersSeasonTop5 = Null;
	$TeamCareerPlayersPlayoffTop5 = Null;
	$TeamCareerGoaliesSeasonTop5 = Null;
	$TeamCareerGoaliesPlayoffTop5 = Null;		
	echo "<style>.STHSPHPTeamStat_Main {display:none;}</style>";
}
echo "<title>" . $LeagueName . " - " . $TeamName . "</title>";
If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS Header Page PHP Performance : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}
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
if (empty($LeagueGeneral) == false){If ($LeagueGeneral['OffSeason'] == "True"){echo ".STHSPHPPlayerStat_HomeMainTD{display:none;}";}}
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
#tablesorter_colSelect8P:checked + label {background: #5797d7;  border-color: #555;}
#tablesorter_colSelect8P:checked ~ #tablesorter_ColumnSelector8P {display: block;z-index:10;}
@media screen and (max-width: 992px) {
.STHSWarning {display:block;}
.STHSPHPTeamStatDepthChart_Table td:nth-child(3){display:none;}
.STHSPHPTeam_HomeTable td:nth-child(2){display:none;}
#STHSPHPTeam_HomePrimaryTableLeaders{display:none;}
}@media screen and (max-width: 890px) {
.STHSPHPTeamStatDepthChart_Table td:nth-child(2){display:none;}
#STHSPHPTeamStat_SubHeader {display:none;}
}
.tabmain-links a{font-size:18px;}
</style>
<script>function toggleDiv(divId) {$("#"+divId).toggle();}</script>
</head><body>
<?php include "Menu.php";?>
<br />

<?php 
If ($TeamInfo <> Null){
	echo "<div id=\"STHSPHPTeamStat_SubHeader\" class=\"STHSPHPTeamBanner_" . $TeamInfo['TeamThemeID'] . "\">";
	echo "<table class=\"STHSPHPTeamHeader_Table\"><tr><td rowspan=\"2\" class=\"STHSPHPTeamHeader_Logo\">";
	If ($TeamInfo['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $TeamInfo['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPTeamStatImage_" . $TeamInfo['TeamThemeID'] . " STHSPHPTeamStatImage\">";}
	echo "</td><td class=\"STHSPHPTeamHeader_TeamName STHSPHPTeamHeader_TeamNameColor_" . $TeamInfo['TeamThemeID'] . "\">" . $TeamName . "</td></tr><tr><td class=\"STHSPHPTeamHeader_TeamNameColor_" . $TeamInfo['TeamThemeID'] . " STHSPHPTeamHeader_Stat\">";
	echo "GP: " . $TeamStat['GP'] . " | W: " . ($TeamStat['W'] + $TeamStat['OTW'] + $TeamStat['SOW']) . " | L: " .  $TeamStat['L'];
	if ($LeagueGeneral['PlayOffStarted'] == "False"){
		if($LeagueGeneral['PointSystemSO'] == "True"){
			echo  " | OTL: " . ($TeamStat['OTL'] + $TeamStat['SOL']) . " | P: " . $TeamStat['Points'];
		}else{
			echo  " | T: " . $TeamStat['T'] . " | P: " . $TeamStat['Points'];
		}
	}
	echo "<br />" . "GF: " . $TeamStat['GF'] . " | GA: " . $TeamStat['GA'] . " | PP%: ";
	if ($TeamStat['PPAttemp'] > 0){echo number_Format($TeamStat['PPGoal'] / $TeamStat['PPAttemp'] * 100,2) . "%";} else { echo "0%";}
	echo " | PK%: ";
	if ($TeamStat['PKAttemp'] > 0){echo number_Format(($TeamStat['PKAttemp'] - $TeamStat['PKGoalGA']) / $TeamStat['PKAttemp'] * 100,2) . "%";} else {echo "0%";} 
	echo "<br />" . $TeamLang['GM'] . $TeamInfo['GMName'] . " | " . $TeamLang['Morale'] . $TeamInfo['Morale'] . " | " . $TeamLang['TeamOverall'] . $TeamInfo['TeamOverall'];
	If ($Team > 0 AND $Team <= 100){
		$Query = "SELECT count(*) AS count FROM SchedulePro WHERE (VisitorTeam = " . $Team . " OR HomeTeam = " . $Team . ") AND Play = 'False' ORDER BY GameNumber LIMIT 1";
		$Result = $db->querySingle($Query,true);
	}else{
		$Result = Null;
	}
	If ($Result['count'] > 0){
		If ($ScheduleNext['HomeTeam'] == $Team){
			echo "<br />" . $ScheduleLang['NextGames'] . " #" . $ScheduleNext['GameNumber'] ."  vs " . $ScheduleNext['VisitorTeamName'];
		}elseif($ScheduleNext['VisitorTeam'] == $Team){
			echo "<br />" . $$ScheduleLang['NextGames']  . " #" . $ScheduleNext['GameNumber'] ."  vs " . $ScheduleNext['HomeTeamName'];
		}
	}
	echo "</td></tr></table></div>";
}
?>
<div class="STHSWarning"><?php echo $WarningResolution;?><br /></div>
<div class="STHSPHPTeamStat_Main">
<br />
<div class="tabsmain standard"><ul class="tabmain-links">
<?php 
If ($LeagueSimulation != Null AND $TeamFarmInfo != Null){If ($LeagueSimulation['FarmEnable'] == "True"){echo "<li><a class=\"tabmenuhome\" href=\"FarmTeam.php?Team=" . $Team . "\">" . $TeamFarmInfo['Name'] . "</a></li>";}}?>
<li<?php if($SubMenu ==0){echo " class=\"activemain\"";}?>><a href="#tabmain0"><?php echo $TeamLang['Home'];?></a></li>
<li<?php if($SubMenu ==1){echo " class=\"activemain\"";}?>><a href="#tabmain1"><?php echo $TeamLang['Roster'];?></a></li>
<li<?php if($SubMenu ==2){echo " class=\"activemain\"";}?>><a href="#tabmain2"><?php echo $TeamLang['Scoring'];?></a></li>
<li<?php if($SubMenu ==3){echo " class=\"activemain\"";}?>><a href="#tabmain3"><?php echo $TeamLang['PlayersInfo'];?></a></li>
<li<?php if($SubMenu ==4){echo " class=\"activemain\"";}?>><a href="#tabmain4"><?php echo $TeamLang['Lines'];?></a></li>
<li<?php if($SubMenu ==5){echo " class=\"activemain\"";}?>><a href="#tabmain5"><?php echo $TeamLang['TeamStats'];?></a></li>
<li<?php if($SubMenu ==6){echo " class=\"activemain\"";}?>><a href="#tabmain6"><?php echo $TeamLang['Schedule'];?></a></li>
<li<?php if($SubMenu ==7){echo " class=\"activemain\"";}?>><a href="#tabmain7"><?php echo $TeamLang['Finance'];?></a></li>
<li<?php if($SubMenu ==8){echo " class=\"activemain\"";}?>><a href="#tabmain8"><?php echo $TeamLang['Depth'];?></a></li>
<li<?php if($SubMenu ==13){echo " class=\"activemain\"";}?>><a href="#tabmain13"><?php echo $TeamLang['News'];?></a></li>
<li<?php if($SubMenu ==9){echo " class=\"activemain\"";}?>><a href="#tabmain9"><?php echo $TeamLang['History'];?></a></li>
<li<?php if($SubMenu ==10){echo " class=\"activemain\"";}?>><a href="#tabmain10"><?php echo $TeamLang['TeamTransaction'];?></a></li>
<li<?php if($SubMenu ==12){echo " class=\"activemain\"";}?>><a href="#tabmain12"><?php echo $TeamLang['InjurySuspension'];?></a></li>
<?php 
if ($TeamCareerStatFound == true){echo "<li";if($SubMenu ==11){echo " class=\"activemain\"";};echo "><a href=\"#tabmain11\">" . $TeamLang['CareerTeamStat'] . "</a></li>\n";}
if ($LeagueOutputOption != Null){if (file_exists($CareerStatDatabaseFile) == true AND $LeagueOutputOption['SeparateCareerStatFromTeamPage'] == "True"){echo "<li><a class=\"tabmenuhome\" href=\"TeamCareerOnly.php?Team=" . $Team . "\">" . $TeamLang['CareerTeamStat'] . "</a></li>\n";}}
if ($LeagueOutputOption != Null){if ($LeagueOutputOption['ShowWebClientInDymanicWebsite'] == "True" AND ($DoNotRequiredLoginDynamicWebsite == True or $CookieTeamNumber == $Team)){
	echo "<li><a class=\"tabmenuhome\" href=\"WebClientRoster.php?TeamID=" . $Team . "\">" . $TeamLang['WebRosterEditor'] . "</a></li>\n";
	echo "<li><a class=\"tabmenuhome\" href=\"WebClientLines.php?League=Pro&TeamID=" . $Team . "\">" . $TeamLang['WebLinesEditor'] . "</a></li>\n";
}}?>
</ul>
<div style="border-radius:1px;box-shadow:-1px 1px 1px rgba(0,0,0,0.15);border-style: solid;border-color: #dedede">

<div class="tabmain<?php if($SubMenu ==0){echo " active";}?>" id="tabmain0">
<?php If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS 0 Page PHP Performance : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}?>

<table class="STHSPHPTeam_HomeTable"><tr><td class="STHSPHPPlayerStat_HomeMainTD">
<?php
$LoopCount = (integer)0;
if (empty($ScheduleLastGame) == false){while ($row = $ScheduleLastGame ->fetchArray()) {
	$LoopCount +=1;
	echo "<table class=\"STHSPHPTeam_HomePrimaryTable\">";
	If ($LoopCount == 1){echo "<tr><td colspan=\"7\" class=\"STHSPHPTeamStat_TableTitle\">" . $TeamLang['GameCenter'] .   "</td></tr>";}
	echo "<tr onclick=\"Game" . $LoopCount  . "()\"><td class=\"STHSPHPTeam_HomePrimaryTableTeamImag\">\n";
	If ($row['VisitorTeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $row['VisitorTeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPTeam_HomePrimaryTableTeamImageSpec\" />\n";}
	echo "</td><td class=\"STHSPHPTeam_HomePrimaryTableTeamInfo\" style=\"text-align:right;\"><span class=\"STHSPHPTeam_HomePrimaryTableTeamName\">" . $row['VisitorTeamName'] . "</span><br />" . ($row['VW'] + $row['VOTW'] + $row['VSOW']) . "-" .$row['VL'] . "-" . ($row['VOTL'] + $row['VSOL']). ", ".$row['VPoints']. "pts</td>";
	echo "<td class=\"STHSPHPTeam_HomePrimaryTableTeamScore\">" . $row['VisitorScore'] . "</td>";
	echo "<td class=\"STHSPHPTeam_HomePrimaryTableTeamMiddlePlay\"><div class=\"STHSPHPTeam_HomePrimaryTableTeamInfoBeforeTriangle\">FINAL</div><div class=\"STHSPHPTeam_HomePrimaryTableTeamInfoTriangle\"></div></td>\n";
	echo "<td class=\"STHSPHPTeam_HomePrimaryTableTeamScore\">" . $row['HomeScore'] . "</td>\n";
	echo "<td class=\"STHSPHPTeam_HomePrimaryTableTeamInfo\"><span class=\"STHSPHPTeam_HomePrimaryTableTeamName\">" . $row['HomeTeamName'] . "</span><br />" . ($row['HW'] + $row['HOTW'] + $row['HSOW']) . "-" .$row['HL'] . "-" . ($row['HOTL'] + $row['HSOL']). ", ".$row['HPoints']. "pts</td><td class=\"STHSPHPTeam_HomePrimaryTableTeamImag\">\n";
	If ($row['HomeTeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $row['HomeTeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPTeam_HomePrimaryTableTeamImageSpec\" />\n";}
	echo "</td></tr></table>\n"; 
	
	echo "<table class=\"STHSPHPTeam_HomeTeamStatTable\" id=\"Game" . $LoopCount . "\"><tr><th colspan=\"3\" >Team Stats</th></tr>\n";
	echo "<tr><td class=\"STHSPHPTeam_HomeTeamStatR\">" . $row['VStreak'] ."</td><td class=\"STHSPHPTeam_HomeTeamStatC\">" . $GeneralStatLang['Streak'] . "</td><td class=\"STHSPHPTeam_HomeTeamStatL\">" . $row['HStreak'] ."</td></tr>\n";
	echo "<tr><td class=\"STHSPHPTeam_HomeTeamStatR\">" . ($row['VHW'] + $row['VHOTW'] + $row['VHSOW']) . "-" . $row['VHL'] . "-" . ($row['VHOTL'] + $row['VHSOL'])  ."</td><td class=\"STHSPHPTeam_HomeTeamStatC\">" . $TeamLang['HomeRecord'] ."</td><td class=\"STHSPHPTeam_HomeTeamStatL\">" . ($row['HHW'] + $row['HHOTW'] + $row['HHSOW']) . "-" . $row['HHL'] . "-" . ($row['HHOTL'] + $row['HHSOL']) ."</td></tr>\n";
	echo "<tr><td class=\"STHSPHPTeam_HomeTeamStatR\">" . (($row['VW']-$row['VHW']) + ($row['VOTW']-$row['VHOTW']) + ($row['VSOW']-$row['VHSOW'])) . "-" . ($row['VL']-$row['VHL']) . "-" . (($row['VOTL']-$row['VHOTL']) + ($row['VSOL']-$row['VHSOL']))  ."</td><td class=\"STHSPHPTeam_HomeTeamStatC\">" . $TeamLang['AwayRecord'] ."</td><td class=\"STHSPHPTeam_HomeTeamStatL\">" . (($row['HW']-$row['HHW']) + ($row['HOTW']-$row['HHOTW']) + ($row['HSOW']-$row['HHSOW'])) . "-" . ($row['HL']-$row['HHL']) . "-" . (($row['HOTL']-$row['HHOTL']) + ($row['HSOL']-$row['HHSOL'])) ."</td></tr>\n";
	echo "<tr><td style=text-align:right;width:33%;font-size:14px;font-weight:bold>" . ($row['VLast10W'] + $row['VLast10OTW'] + $row['VLast10SOW']) . "-" . $row['VLast10L'] . "-" . ($row['VLast10OTL'] + $row['VLast10SOL'])  ."</td><td class=\"STHSPHPTeam_HomeTeamStatC\">" . $TeamLang['Last10Games'] . "</td><td class=\"STHSPHPTeam_HomeTeamStatL\">" . ($row['HLast10W'] + $row['HLast10OTW'] + $row['HLast10SOW']) . "-" . $row['HLast10L'] . "-" . ($row['HLast10OTL'] + $row['HLast10SOL']) ."</td></tr>\n";
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
	If ($row['VisitorTeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $row['VisitorTeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPTeam_HomePrimaryTableTeamImageSpec\" />\n";}
	echo "</td><td class=\"STHSPHPTeam_HomePrimaryTableTeamInfo\" style=\"text-align:right;\"><span class=\"STHSPHPTeam_HomePrimaryTableTeamName\">" . $row['VisitorTeamName'] . "</span><br />" . ($row['VW'] + $row['VOTW'] + $row['VSOW']) . "-" .$row['VL'] . "-" . ($row['VOTL'] + $row['VSOL']). ", ".$row['VPoints']. "pts</td>";
	
	echo "<td class=\"STHSPHPTeam_HomePrimaryTableTeamMiddleNotPlay\"><div class=\"STHSPHPTeam_HomePrimaryTableTeamInfoBeforeTriangle\">";
	if ($LeagueOutputOption['ScheduleUseDateInsteadofDay'] == "True"){
		$ScheduleDate = date_create($LeagueOutputOption['ScheduleRealDate']);
		date_add($ScheduleDate, DateInterval::createFromDateString(Floor((($row['Day'] -1) / $LeagueGeneral['DefaultSimulationPerDay'])) . " days"));
		echo date_Format($ScheduleDate,"Y-m-d") . "</div>\n";
	}else{
		echo $ScheduleLang['Day'] . " " . $row['Day'] . "</div>\n";
	}	
	echo "<div class=\"STHSPHPTeam_HomePrimaryTableTeamInfoTriangle\"></div></td>\n";
	echo "<td class=\"STHSPHPTeam_HomePrimaryTableTeamInfo\"><span class=\"STHSPHPTeam_HomePrimaryTableTeamName\">" . $row['HomeTeamName'] . "</span><br />" . ($row['HW'] + $row['HOTW'] + $row['HSOW']) . "-" .$row['HL'] . "-" . ($row['HOTL'] + $row['HSOL']). ", ".$row['HPoints']. "pts</td><td class=\"STHSPHPTeam_HomePrimaryTableTeamImag\">\n";
	If ($row['HomeTeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $row['HomeTeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPTeam_HomePrimaryTableTeamImageSpec\" />\n";}
	echo "</td></tr></table>\n"; 
	
	echo "<table class=\"STHSPHPTeam_HomeTeamStatTable\" id=\"Game" . $LoopCount . "\"><tr><th colspan=\"3\">" . $TeamLang['TeamStats'] . "</th></tr>\n";
	echo "<tr><td class=\"STHSPHPTeam_HomeTeamStatR\">" . $row['VStreak'] ."</td><td class=\"STHSPHPTeam_HomeTeamStatC\">" . $GeneralStatLang['Streak'] ."</td><td class=\"STHSPHPTeam_HomeTeamStatL\">" . $row['HStreak'] ."</td></tr>\n";
	echo "<tr><td class=\"STHSPHPTeam_HomeTeamStatR\">" . ($row['VHW'] + $row['VHOTW'] + $row['VHSOW']) . "-" . $row['VHL'] . "-" . ($row['VHOTL'] + $row['VHSOL'])  ."</td><td class=\"STHSPHPTeam_HomeTeamStatC\">" . $TeamLang['HomeRecord']	 ."</td><td class=\"STHSPHPTeam_HomeTeamStatL\">" . ($row['HHW'] + $row['HHOTW'] + $row['HHSOW']) . "-" . $row['HHL'] . "-" . ($row['HHOTL'] + $row['HHSOL']) ."</td></tr>\n";
	echo "<tr><td class=\"STHSPHPTeam_HomeTeamStatR\">" . (($row['VW']-$row['VHW']) + ($row['VOTW']-$row['VHOTW']) + ($row['VSOW']-$row['VHSOW'])) . "-" . ($row['VL']-$row['VHL']) . "-" . (($row['VOTL']-$row['VHOTL']) + ($row['VSOL']-$row['VHSOL']))  ."</td><td class=\"STHSPHPTeam_HomeTeamStatC\">" . $TeamLang['AwayRecord']	 ."</td><td class=\"STHSPHPTeam_HomeTeamStatL\">" . (($row['HW']-$row['HHW']) + ($row['HOTW']-$row['HHOTW']) + ($row['HSOW']-$row['HHSOW'])) . "-" . ($row['HL']-$row['HHL']) . "-" . (($row['HOTL']-$row['HHOTL']) + ($row['HSOL']-$row['HHSOL'])) ."</td></tr>\n";
	echo "<tr><td style=text-align:right;width:33%;font-size:14px;font-weight:bold>" . ($row['VLast10W'] + $row['VLast10OTW'] + $row['VLast10SOW']) . "-" . $row['VLast10L'] . "-" . ($row['VLast10OTL'] + $row['VLast10SOL'])  ."</td><td class=\"STHSPHPTeam_HomeTeamStatC\">" . $ScheduleLang['Last10Games'] ."</td><td class=\"STHSPHPTeam_HomeTeamStatL\">" . ($row['HLast10W'] + $row['HLast10OTW'] + $row['HLast10SOW']) . "-" . $row['HLast10L'] . "-" . ($row['HLast10OTL'] + $row['HLast10SOL']) ."</td></tr>\n";
	echo "<tr><td class=\"STHSPHPTeam_HomeTeamStatR\">";
	if ($row['VGP'] > 0){echo number_Format($row['VGF'] / $row['VGP'],2);}else{echo "0";}
	echo "</td><td class=\"STHSPHPTeam_HomeTeamStatC\">" . $TeamLang['GoalsPerGame'] ."</td><td class=\"STHSPHPTeam_HomeTeamStatL\">";
	if ($row['HGP'] > 0){echo number_Format($row['HGF'] / $row['HGP'],2);}else{echo "0";}
	echo "</td></tr>\n";
	echo "<tr><td class=\"STHSPHPTeam_HomeTeamStatR\">";
	if ($row['VGP'] > 0){echo number_Format($row['VGA'] / $row['VGP'],2);}else{echo "0";}
	echo "</td><td class=\"STHSPHPTeam_HomeTeamStatC\">" . $TeamLang['GoalsAgainstPerGame'] ."</td><td class=\"STHSPHPTeam_HomeTeamStatL\">";
	if ($row['HGP'] > 0){echo number_Format($row['HGF'] / $row['HGP'],2);}else{echo "0";}
	echo "</td></tr>\n";
	echo "<tr><td class=\"STHSPHPTeam_HomeTeamStatR\">";
	if ($row['VPPAttemp'] > 0){echo number_Format($row['VPPGoal'] / $row['VPPAttemp'] * 100,2)."%";}else{echo "0%";}
	echo "</td><td class=\"STHSPHPTeam_HomeTeamStatC\">" . $TeamLang['PowerPlayPercentage'] ."</td><td class=\"STHSPHPTeam_HomeTeamStatL\">";
	if ($row['HPPAttemp'] > 0){echo number_Format($row['HPPGoal'] / $row['HPPAttemp'] * 100,2)."%";}else{echo "0%";}
	echo "</td></tr>\n";
	echo "<tr><td class=\"STHSPHPTeam_HomeTeamStatR\">";
	if ($row['VPKAttemp'] > 0){echo number_Format(($row['VPKAttemp']-$row['VPKGA']) / $row['VPKAttemp'] * 100,2)."%";}else{echo "0%";}
	echo "</td><td class=\"STHSPHPTeam_HomeTeamStatC\">" . $TeamLang['PenaltyKillPercentage'] ."</td><td class=\"STHSPHPTeam_HomeTeamStatL\">";
	if ($row['HPKAttemp'] > 0){echo number_Format(($row['HPKAttemp']-$row['HPKGA']) / $row['HPKAttemp'] * 100,2)."%";}else{echo "0%";}
	echo "</td></tr>\n";
	echo "</table>\n";
}}
?>

<table id="STHSPHPTeam_HomePrimaryTableLeaders" style="width:100%;border-collapse:collapse">

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
}}
?>
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
	echo "<td class=\"STHSPHPTeam_HomePrimaryTableTeamStatTD\" style=\"border-bottom:1px solid #ddd;border-left:1px solid #ddd;\"><span>" . $TeamLang['PowerPlayPercentage'] . "</span><br><span class=\"STHSPHPTeam_HomePrimaryTableTeamStatStrongText\">";If ($TeamStat['PPAttemp'] > 0){echo number_Format($TeamStat['PPGoal'] / $TeamStat['PPAttemp'] * 100,1);}else{echo "0";}echo "%</span><br><span>" .  ($TeamStat['PPGoal']). "&nbsp;GF</span></td>\n";
	echo "<td class=\"STHSPHPTeam_HomePrimaryTableTeamStatTD\" style=\"border-bottom:1px solid #ddd;border-left:1px solid #ddd;\"><span>" . $TeamLang['OffensiveZoneStart'] . "</span><br><span class=\"STHSPHPTeam_HomePrimaryTableTeamStatStrongText\">" . number_Format($TeamStat['FaceOffTotalOffensifZone'] / ($TeamStat['FaceOffTotalDefensifZone']+$TeamStat['FaceOffTotalOffensifZone']+$TeamStat['FaceOffTotalNeutralZone'])*100,1). "%</span><br></td>\n";
	echo "</tr><tr>";
	echo "<td class=\"STHSPHPTeam_HomePrimaryTableTeamStatTD\"><span>" . $GeneralStatLang['GoalsAgainst'] . "</span><br><span class=\"STHSPHPTeam_HomePrimaryTableTeamStatStrongText\">" . $TeamStat['GA']. "</span><br><span>" .  number_Format($TeamStat['GA']/$TeamStat['GP'],2). "&nbsp;GAA</span></td>\n";
	echo "<td class=\"STHSPHPTeam_HomePrimaryTableTeamStatTD\" style=\"border-left:1px solid #ddd;\"><span>" . $GeneralStatLang['ShotsAgainst'] . "</span><br><span class=\"STHSPHPTeam_HomePrimaryTableTeamStatStrongText\">" . $TeamStat['ShotsAga']. "</span><br><span>" .  number_Format($TeamStat['ShotsAga']/$TeamStat['GP'],2). "&nbsp;Avg</span></td>\n";
	echo "<td class=\"STHSPHPTeam_HomePrimaryTableTeamStatTD\" style=\"border-left:1px solid #ddd;\"><span>" . $TeamLang['PenaltyKillPercentage'] . "</span><br><span class=\"STHSPHPTeam_HomePrimaryTableTeamStatStrongText\">";If ($TeamStat['PKAttemp'] > 0){echo number_Format(($TeamStat['PKAttemp'] - $TeamStat['PKGoalGA']) / $TeamStat['PKAttemp'] * 100,1);}else{echo "0";}echo "%%</span><br><span>" .  ($TeamStat['PKGoalGA']). "&nbsp;GA</span></td>\n";
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
	echo "<tr><td rowspan=\"";if (empty($CoachInfo) == false){echo "7";}else{echo "6";}echo "\">"; If ($TeamInfo['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $TeamInfo['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPTeamStatImage\" />";};echo "</td>\n";
	echo "<td>" . $TeamLang['GeneralManager'] . "</td><td class=\"STHSPHPTeam_HomeSecondaryTableTDStrongText\">" . $TeamInfo['GMName'] . "</td></tr>\n";
	if (empty($CoachInfo) == false){echo "<tr><td>" . $TeamLang['Coach'] . "</td><td class=\"STHSPHPTeam_HomeSecondaryTableTDStrongText\">" . $CoachInfo['Name'] . "</td></tr>\n";}
	echo "<tr><td>" . $TeamLang['Division'] . "</td><td class=\"STHSPHPTeam_HomeSecondaryTableTDStrongText\">" . $TeamInfo['Division'] . "</td></tr>\n";
	echo "<tr><td>" . $TeamLang['Conference'] . "</td><td class=\"STHSPHPTeam_HomeSecondaryTableTDStrongText\">" . $TeamInfo['Conference'] . "</td></tr>\n";	
	echo "<tr><td>" . $TeamLang['Captain'] . "</td><td class=\"STHSPHPTeam_HomeSecondaryTableTDStrongText\">" . $TeamLeader['Captain'] . "</td></tr>\n";	
	echo "<tr><td>" . $TeamLang['Assistant1'] . "</td><td class=\"STHSPHPTeam_HomeSecondaryTableTDStrongText\">" . $TeamLeader['Assistant1'] . "</td></tr>\n";	
	echo "<tr><td>" . $TeamLang['Assistant2'] . "</td><td class=\"STHSPHPTeam_HomeSecondaryTableTDStrongText\">" . $TeamLeader['Assistant2'] . "</td></tr>\n";		
	echo "<tr><td colspan=\"3\" class=\"STHSPHPTeamStat_TableTitle\"><br /><br />" . $TeamLang['ArenaInfo'] . "<br /><br /></td></tr>\n";
	echo "<tr><td rowspan=\"4\"><img src=\"" . $ImagesCDNPath . "/images/ArenaInfo.png\" alt=\"\" class=\"STHSPHPTeam_HomeSecondaryTableImage\"></td>";
	echo "<td>" . $TeamLang['ArenaName'] . "</td><td class=\"STHSPHPTeam_HomeSecondaryTableTDStrongText\">" . $TeamInfo['Arena']  . "</td></tr>\n";	
	echo "<tr><td>" . $TeamLang['ArenaCapacity'] . "</td><td class=\"STHSPHPTeam_HomeSecondaryTableTDStrongText\">" . number_Format($TeamFinance['ArenaCapacityL1'] + $TeamFinance['ArenaCapacityL2'] + $TeamFinance['ArenaCapacityL3'] + $TeamFinance['ArenaCapacityL4'] + $TeamFinance['ArenaCapacityLuxury']) . "</td></tr>\n";		
	echo "<tr><td>" . $TeamLang['Attendance'] . "</td><td class=\"STHSPHPTeam_HomeSecondaryTableTDStrongText\">";If ($TeamStat['HomeGP'] > 0){echo number_Format($TeamFinance['TotalAttendance'] / $TeamStat['HomeGP']);};echo  "</td></tr>\n";
	echo "<tr><td>" . $TeamLang['ArenaSeasonTickets'] . "</td><td class=\"STHSPHPTeam_HomeSecondaryTableTDStrongText\">" . number_Format((($TeamFinance['ArenaCapacityL1'] + $TeamFinance['ArenaCapacityL2'] + $TeamFinance['ArenaCapacityL3'] + $TeamFinance['ArenaCapacityL4'] + $TeamFinance['ArenaCapacityLuxury'])*$TeamFinance['SeasonTicketPCT'])/100) . "</td></tr>\n";	
	echo "<tr><td colspan=\"3\" class=\"STHSPHPTeamStat_TableTitle\"><br /><br />" . $TeamLang['RosterInfo'] . "<br /><br /></td></tr>\n";
	echo "<tr><td rowspan=\"4\"><img src=\"" . $ImagesCDNPath . "/images/RosterInfo.png\" alt=\"\" class=\"STHSPHPTeam_HomeSecondaryTableImage\"></td>";
	echo "<td>" . $TeamLang['ProTeam'] . "</td><td class=\"STHSPHPTeam_HomeSecondaryTableTDStrongText\">" .  $PlayerInfoAverage['CountOfName'] . "</td></tr>\n";	
	echo "<tr><td>" . $TeamLang['FarmTeam'] . "</td><td class=\"STHSPHPTeam_HomeSecondaryTableTDStrongText\">" . ($PlayerInfoTotalAverage['CountOfName'] - $PlayerInfoAverage['CountOfName']) . "</td></tr>\n";	
	echo "<tr><td>" . $TeamLang['ContractLimit'] . "</td><td class=\"STHSPHPTeam_HomeSecondaryTableTDStrongText\">" .  $PlayerInfoTotalAverage['CountOfName']  . " / " . $LeagueWebClient['MaximumPlayerPerTeam'] . "</td></tr>\n";	
	echo "<tr><td>" . $TeamLang['Prospects'] . "</td><td class=\"STHSPHPTeam_HomeSecondaryTableTDStrongText\">" . $ProspectsCount['CountOfName'] . "</td></tr>\n";	
	
	
	If ($TeamFinance != Null){
		if ($LeagueFinance['SalaryCapOption'] > 0){
			echo "<tr><td colspan=\"3\" class=\"STHSPHPTeamStat_TableTitle\"><br /><br />" . $PlayersLang['SalaryCap'] . "<br /><br /></td></tr>\n";
			echo "<tr><td rowspan=\"4\"><img src=\"" . $ImagesCDNPath . "/images/Financial1.png\" alt=\"\" class=\"STHSPHPTeam_HomeSecondaryTableImage\"></td>";
			echo "<td>" . $TeamLang['EstimatedSeasonSalaryCap']  . "</td><td class=\"STHSPHPTeam_HomeSecondaryTableTDStrongText\">" .  number_Format($TeamFinance['TotalSalaryCap'],0) . "$</td></tr>\n";	
			$TeamSalaryCap = 0;
			if ($LeagueFinance['SalaryCapOption'] == 0){
				$TeamSalaryCap = 2147483647;
			}elseif($LeagueFinance['SalaryCapOption'] == 2 OR $LeagueFinance['SalaryCapOption'] == 5){
				$TeamSalaryCap = $TeamFinance['CurrentBankAccount'] + $LeagueFinance['ProSalaryCapValue'];
			}else{
				$TeamSalaryCap = $LeagueFinance['ProSalaryCapValue'];
			}			
			
			echo "<tr><td>" . $TeamLang['AvailableSalaryCap']  . "</td><td class=\"STHSPHPTeam_HomeSecondaryTableTDStrongText\">" . number_Format($TeamSalaryCap - $TeamFinance['TotalSalaryCap'],0) . "$</td></tr>\n";	
			echo "<tr><td>" . $TeamLang['SpecialSalaryCapValue']  . "</td><td class=\"STHSPHPTeam_HomeSecondaryTableTDStrongText\">" . number_Format($TeamFinance['SpecialSalaryCapY1'],0) . "$</td></tr>\n";	
			echo "<tr><td>" . $TeamLang['PlayerInSalaryCap'] . "</td><td class=\"STHSPHPTeam_HomeSecondaryTableTDStrongText\">" . $TeamFinance['PlayerInSalaryCap'] . "</td></tr>\n";	
		}
	
		echo "<tr><td colspan=\"3\" class=\"STHSPHPTeamStat_TableTitle\"><br /><br />" . $TeamLang['Finance'] . "<br /><br /></td></tr>\n";
		echo "<tr><td rowspan=\"6\"><img src=\"" . $ImagesCDNPath . "/images/Financial2.png\" alt=\"\" class=\"STHSPHPTeam_HomeSecondaryTableImage\"></td>";
		echo "<td>" . $TeamLang['YeartoDateRevenue'] . "</td><td class=\"STHSPHPTeam_HomeSecondaryTableTDStrongText\">" .  number_format($TeamFinance['TotalIncome'],0) . "$</td></tr>\n";	
		echo "<tr><td>" . $TeamLang['YearToDateExpenses'] . "</td><td class=\"STHSPHPTeam_HomeSecondaryTableTDStrongText\">" . number_Format(($TeamFinance['ExpenseThisSeason']),0) . "$</td></tr>\n";	
		echo "<tr><td>" . $TeamLang['EstimatedSeasonRevenue'] . "</td><td class=\"STHSPHPTeam_HomeSecondaryTableTDStrongText\">" . number_Format($TeamFinance['EstimatedRevenue'],0) . "$</td></tr>\n";	
		echo "<tr><td>" . $TeamLang['EstimatedSeasonExpenses'] . "</td><td class=\"STHSPHPTeam_HomeSecondaryTableTDStrongText\">" . number_Format($TeamFinance['EstimatedSeasonExpense'],0) . "$</td></tr>\n";	
		echo "<tr><td>" . $TeamLang['CurrentBankAccount'] . "</td><td class=\"STHSPHPTeam_HomeSecondaryTableTDStrongText\">" . number_Format($TeamFinance['CurrentBankAccount'],0) . "$</td></tr>\n";	
		echo "<tr><td>" . $TeamLang['ProjectedBankAccount'] . "</td><td class=\"STHSPHPTeam_HomeSecondaryTableTDStrongText\">" . number_Format($TeamFinance['ProjectedBankAccount'],0) . "$</td></tr>\n";	
	}
	
	if ($TeamCareerStatFound == true){
		echo "<tr><td colspan=\"3\" class=\"STHSPHPTeamStat_TableTitle\"><br /><br />" . $TeamLang['TeamHistory'] . "<br /><br /></td></tr>\n";
		echo "<tr><td rowspan=\"5\"><img src=\"" . $ImagesCDNPath . "/images/Stats.png\" alt=\"\" class=\"STHSPHPTeam_HomeSecondaryTableImage\"></td>";
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
		
		if ($TeamCareerSumSeasonOnly != Null){
			$TeamCareerW = $TeamCareerSumSeasonOnly['0']['W'] + $TeamCareerSumSeasonOnly['0']['OTW'] + $TeamCareerSumSeasonOnly['0']['SOW'] ;
			$TeamCareerL = $TeamCareerSumSeasonOnly['0']['L'];
			$TeamCareerOTLSOL = $TeamCareerSumSeasonOnly['0']['OTW'] + $TeamCareerSumSeasonOnly['0']['SOL'];
			$TeamCareerT = $TeamCareerSumSeasonOnly['0']['T'];
		}else{
			$TeamCareerW = 0 ;
			$TeamCareerL = 0;
			$TeamCareerOTLSOL = 0;
			$TeamCareerT = 0;
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
		if ($TeamCareerSumPlayoffOnly != Null){
			echo "<tr><td>" . $TeamLang['PlayoffAppearances']  . "</td><td class=\"STHSPHPTeam_HomeSecondaryTableTDStrongText\">" . $TeamCareerSumPlayoffOnly['0']['CountYear'] . "</td></tr>\n";	
			echo "<tr><td>" . $TeamLang['PlayoffRecord']  . "</td><td class=\"STHSPHPTeam_HomeSecondaryTableTDStrongText\">" . $TeamCareerSumPlayoffOnly['0']['W'] . "-" .  $TeamCareerSumPlayoffOnly['0']['L'] . "</td></tr>\n";	
		}else{
			echo "<tr><td></td></tr>\n";	
			echo "<tr><td></td></tr>\n";	
		}
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
<?php If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS 1 Page PHP Performance : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}?>

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
for($Status = 3; $Status >= $LoopEnd ; $Status--){
	if ($Status == 3){echo "<tbody>";$LoopCount = (integer)0;}
	if ($Status == 2){echo "</tbody><tbody class=\"tablesorter-no-sort\"><tr><th colspan=\"" . $Colspan . "\">" . $TeamLang['Scratches'] . "</th></tr></tbody><tbody>";$LoopCount = (integer)0;}
	if ($Status == 1){echo "</tbody><tbody class=\"tablesorter-no-sort\"><tr><th colspan=\"" . $Colspan . "\">" . $TeamLang['FarmTeam'] . "</th></tr></tbody><tbody>";$LoopCount = (integer)0;}	
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
		echo "<td>";if ($Row['AvailableforTrade']== "True"){ echo "X";}elseif($Row['NoTrade']== "True"){ echo "N";}; echo"</td>";
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
<?php if ($LeagueOutputOption != Null){if ($LeagueOutputOption['JerseyNumberInWebsite'] == "True") {echo "<th data-priority=\"6\" title=\"Jesery\" class=\"STHSW10\">#</th>";}}?>
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
If ($LeagueOutputOption['MergeRosterPlayerInfo'] == "True"){$LoopEnd = 0;$Colspan=24;}else{$LoopEnd = 2;$Colspan=21;}
If ($LeagueOutputOption['JerseyNumberInWebsite'] == "True"){$Colspan +=1;}
for($Status = 3; $Status >= $LoopEnd ; $Status--){
	if ($Status == 3){echo "<tbody>";$LoopCount = (integer)0;}
	if ($Status == 2){echo "</tbody><tbody class=\"tablesorter-no-sort\"><tr><th colspan=\"" . $Colspan . "\">" . $TeamLang['Scratches'] . "</th></tr></tbody><tbody>";$LoopCount = (integer)0;}
	if ($Status == 1){echo "</tbody><tbody class=\"tablesorter-no-sort\"><tr><th colspan=\"" . $Colspan . "\">" . $TeamLang['FarmTeam'] . "</th></tr></tbody><tbody>";$LoopCount = (integer)0;}	
	$Query = "SELECT * FROM GoalerInfo WHERE Team = " . $Team . " AND Status1 = " . $Status . " ORDER By Overall DESC";
	If (file_exists($DatabaseFile) ==True){$GoalieRoster = $db->query($Query);}
	if (empty($GoalieRoster) == false){while ($Row = $GoalieRoster ->fetchArray()) {
		$LoopCount +=1;
		echo "<tr><td>" . $LoopCount . "</td>";
		$strTemp = (string)$Row['Name'];
		if ($Row['Rookie']== "True"){ $strTemp = $strTemp . " (R)";}
		echo "<td><a href=\"GoalieReport.php?Goalie=" . $Row['Number'] . "\">" . $strTemp . "</a></td>";
		if ($LeagueOutputOption['JerseyNumberInWebsite'] == "True") {echo "<td>" . $Row['Jersey'] . "</td>";}
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
		echo "<td>";if ($Row['AvailableforTrade']== "True"){ echo "X";}elseif($Row['NoTrade']== "True"){ echo "N";}; echo"</td>";
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
If ($LeagueOutputOption['JerseyNumberInWebsite'] == "True"){echo "<td></td>";}
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
<?php If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS 2 Page PHP Performance : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}?>

<div class="tablesorter_ColumnSelectorWrapper">
    <input id="tablesorter_colSelect2P" type="checkbox" class="hidden">
    <label class="tablesorter_ColumnSelectorButton" for="tablesorter_colSelect2P"><?php echo $TableSorterLang['ShoworHideColumn'];?></label>
    <div id="tablesorter_ColumnSelector2P" class="tablesorter_ColumnSelector"></div>
	<?php include "FilterTip.php";?>
</div>

<table class="tablesorter STHSPHPTeam_PlayersScoringTable"><thead><tr>
<?php 
include "PlayersStatSub.php";
if($PlayerStatTeam != Null){if ($PlayerStatTeam['SumOfGP'] > 0){
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
if($GoalieStatTeam != Null){If ($GoalieStatTeam['SumOfGP'] > 0){
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
	echo "<td>";if ($GoalieStatTeam['SumOfPenalityShotsPCT'] <> Null){number_Format($GoalieStatTeam['SumOfPenalityShotsPCT'],3);}; echo "</td>";	
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
<?php If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS 3 Page PHP Performance : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}?>

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
<br />
<table class="STHSPHPTeamStat_Table"><tr>
<th class="STHSW140"><?php echo $TeamLang['SumYear1Salary'];?></th>
<th class="STHSW140"><?php echo $TeamLang['SumYear2Salary'];?></th>
<th class="STHSW140"><?php echo $TeamLang['SumYear3Salary'];?></th>
<th class="STHSW140"><?php echo $TeamLang['SumYear4Salary'];?></th>
<th class="STHSW140"><?php echo $TeamLang['SumYear5Salary'];?></th></tr>
<?php
If ($PlayerInfoAverage != Null){
	echo "<tr><td>" . number_format($PlayerInfoAverage['SumOfSalary1'],0) . "$</td>";	
	echo "<td>" . number_format($PlayerInfoAverage['SumOfSalary2'],0) . "$</td>";	
	echo "<td>" . number_format($PlayerInfoAverage['SumOfSalary3'],0) . "$</td>";	
	echo "<td>" . number_format($PlayerInfoAverage['SumOfSalary4'],0) . "$</td>";	
	echo "<td>" . number_format($PlayerInfoAverage['SumOfSalary5'],0) . "$</td></tr>";	
}
?>
</table>

<br /><br /></div>
<div class="tabmain<?php if($SubMenu ==4){echo " active";}?>" id="tabmain4">
<?php If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS 4 Page PHP Performance : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}?>
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

<table class="STHSPHPTeamStat_Table"<?php if($LeagueWebClient != Null){if ($LeagueWebClient['ProCustomOTLines'] == "False"){echo " style=\"display:none;\"";}} ?>><tr><th><?php echo $TeamLang['CustomOTLinesForwards'];?></th></tr>
<?php if ($TeamLines != Null){echo "<tr><td>" . $TeamLines['OTForward1'] . ", " . $TeamLines['OTForward2'] . ", " . $TeamLines['OTForward3'] . ", " . $TeamLines['OTForward4'] . ", " . $TeamLines['OTForward5'] . ", " . $TeamLines['OTForward6'] . ", " . $TeamLines['OTForward6'] . ", " . $TeamLines['OTForward7'] . ", " . $TeamLines['OTForward8'] . ", " . $TeamLines['OTForward9'] . ", " . $TeamLines['OTForward10'] . "</td></tr>";}?></table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPTeamStat_Table"<?php if($LeagueWebClient != Null){if ($LeagueWebClient['ProCustomOTLines'] == "False"){echo " style=\"display:none;\"";}} ?>><tr><th><?php echo $TeamLang['CustomOTLinesDefensemen'];?></th></tr>
<?php if ($TeamLines != Null){echo "<tr><td>" . $TeamLines['OTDefense1'] . ", " . $TeamLines['OTDefense2'] . ", " . $TeamLines['OTDefense3'] . ", " . $TeamLines['OTDefense4'] . ", " . $TeamLines['OTDefense5'] . "</td></tr>";}?></table>
<div class="STHSBlankDiv"></div>

<br /><br /></div>
<div class="tabmain<?php if($SubMenu ==5){echo " active";}?>" id="tabmain5">
<?php If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS 5 Page PHP Performance : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}?>

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
echo "<td>";if ($TeamStat['PPAttemp'] > 0){echo number_Format($TeamStat['PPGoal'] / $TeamStat['PPAttemp'] * 100,2) . "%";} else { echo "0%";} echo "</td>";		
echo "<td>" . $TeamStat['PKAttemp']. "</td>";
echo "<td>" . $TeamStat['PKGoalGA']. "</td>";
echo "<td>";if ($TeamStat['PKAttemp'] > 0){echo number_Format(($TeamStat['PKAttemp'] - $TeamStat['PKGoalGA']) / $TeamStat['PKAttemp'] * 100,2) . "%";} else {echo "0%";} echo "</td>";
echo "<td>" .  $TeamStat['PKGoalGF']. "</td>";		
echo "</tr>";}?>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPTeamStat_Table"><tr>
<th class="STHSW25"><?php echo $TeamLang['Shots1Period'];?></th><th class="STHSW25"><?php echo $TeamLang['Shots2Period'];?></th><th class="STHSW25"><?php echo $TeamLang['Shots3Period'];?></th><th class="STHSW25"><?php echo $TeamLang['Shots4Period'];?></th><th class="STHSW25"><?php echo $TeamLang['Goals1Period'];?></th><th class="STHSW25"><?php echo $TeamLang['Goals2Period'];?></th><th class="STHSW25"><?php echo $TeamLang['Goals3Period'];?></th><th class="STHSW25"><?php echo $TeamLang['Goals4Period'];?>
</th></tr>
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
echo "<td>";if ($TeamStat['FaceOffTotalOffensifZone'] > 0){echo number_Format($TeamStat['FaceOffWonOffensifZone'] / $TeamStat['FaceOffTotalOffensifZone'] * 100,2) . "%" ;} else { echo "0%";} echo "</td>";	
echo "<td>" . $TeamStat['FaceOffWonDefensifZone']. "</td>";
echo "<td>" . $TeamStat['FaceOffTotalDefensifZone']. "</td>";
echo "<td>";if ($TeamStat['FaceOffTotalDefensifZone'] > 0){echo number_Format($TeamStat['FaceOffWonDefensifZone'] / $TeamStat['FaceOffTotalDefensifZone'] * 100,2) . "%" ;} else { echo "0%";} echo "</td>";	
echo "<td>" . $TeamStat['FaceOffWonNeutralZone']. "</td>";	
echo "<td>" . $TeamStat['FaceOffTotalNeutralZone']. "</td>";	
echo "<td>";if ($TeamStat['FaceOffTotalNeutralZone'] > 0){echo number_Format($TeamStat['FaceOffWonNeutralZone'] / $TeamStat['FaceOffTotalNeutralZone'] * 100,2) . "%" ;} else { echo "0%";} echo "</td>";	
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
<?php If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS 6 Page PHP Performance : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}?>
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
<?php If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS 7 Page PHP Performance : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}?>
<br />
<table class="STHSPHPTeamStat_Table"><tr><th colspan="6"><?php echo $TeamLang['ArenaCapacityTicketPriceAttendance'];?></th></tr><tr><th class="STHSW200"></th><th class="STHSW100"><?php echo $TeamLang['Level'];?> 1</th><th class="STHSW100"><?php echo $TeamLang['Level'];?> 2</th><th class="STHSW100"><?php echo $TeamLang['Level'];?> 3</th><th class="STHSW100"><?php echo $TeamLang['Level'];?> 4</th><th class="STHSW100"><?php echo $TeamLang['Luxury'];?></th></tr>
<?php 
If ($TeamFinance != Null){
echo "<tr><th>" . $TeamLang['ArenaCapacity'] . "</th><td>" . $TeamFinance['ArenaCapacityL1'] . "</td><td>" . $TeamFinance['ArenaCapacityL2'] . "</td><td>" . $TeamFinance['ArenaCapacityL3'] . "</td><td>" . $TeamFinance['ArenaCapacityL4'] . "</td><td>" . $TeamFinance['ArenaCapacityLuxury'] . "</td></tr>\n";
echo "<tr><th>" . $TeamLang['TicketPrice'] . "</th><td>" . $TeamFinance['TicketPriceL1'] . "</td><td>" . $TeamFinance['TicketPriceL2'] . "</td><td>" . $TeamFinance['TicketPriceL3'] . "</td><td>" . $TeamFinance['TicketPriceL4'] . "</td><td>" . $TeamFinance['TicketPriceLuxury'] . "</td></tr>\n";
if ($TeamStat['HomeGP'] > 0){echo "<tr><th>" . $TeamLang['Attendance'] . "</th><td>" . number_Format($TeamFinance['AttendanceL1'],0) . "</td><td>" . number_Format($TeamFinance['AttendanceL2'],0) . "</td><td>" . number_Format($TeamFinance['AttendanceL3'],0) . "</td><td>" . number_Format($TeamFinance['AttendanceL4'],0) . "</td><td>" . number_Format($TeamFinance['AttendanceLuxury'],0) . "</td></tr>\n";
}else{echo "<tr><th>" . $TeamLang['Attendance'] . "</th><td>0%</td><td>0%</td><td>0%</td><td>0%</td><td>0%</td></tr>\n";}
echo "<tr><th>Attendance PCT</th>";
echo "<td>";if ($TeamFinance['ArenaCapacityL1'] > 0 AND $TeamStat['HomeGP'] > 0){echo number_format(($TeamFinance['AttendanceL1'] / ($TeamFinance['ArenaCapacityL1'] * $TeamStat['HomeGP'])) *100 ,2) . "%";} else { echo "0%";} echo "</td>";	
echo "<td>";if ($TeamFinance['ArenaCapacityL2'] > 0 AND $TeamStat['HomeGP'] > 0){echo number_format(($TeamFinance['AttendanceL2'] / ($TeamFinance['ArenaCapacityL2'] * $TeamStat['HomeGP'])) *100 ,2) . "%";} else { echo "0%";} echo "</td>";	
echo "<td>";if ($TeamFinance['ArenaCapacityL3'] > 0 AND $TeamStat['HomeGP'] > 0){echo number_format(($TeamFinance['AttendanceL3'] / ($TeamFinance['ArenaCapacityL3'] * $TeamStat['HomeGP'])) *100 ,2) . "%";} else { echo "0%";} echo "</td>";	
echo "<td>";if ($TeamFinance['ArenaCapacityL4'] > 0 AND $TeamStat['HomeGP'] > 0){echo number_format(($TeamFinance['AttendanceL4'] / ($TeamFinance['ArenaCapacityL4'] * $TeamStat['HomeGP'])) *100 ,2) . "%";} else { echo "0%";} echo "</td>";	
echo "<td>";if ($TeamFinance['ArenaCapacityLuxury'] > 0 AND $TeamStat['HomeGP'] > 0){echo number_format(($TeamFinance['AttendanceLuxury'] / ($TeamFinance['ArenaCapacityLuxury'] * $TeamStat['HomeGP'])) *100 ,2) . "%";} else { echo "0%";} echo "</td></tr>";	
}?>
</table>

<br />
<table class="STHSPHPTeamStat_Table"><tr><th colspan="6"><?php echo $TeamLang['Income'];?>
</th></tr><tr><th class="STHSW140"><?php echo $TeamLang['HomeGamesLeft'];?></th><th class="STHSW140"><?php echo $TeamLang['AverageAttendancePCT'];?></th><th class="STHSW140"><?php echo $TeamLang['AverageIncomeperGame'];?></th><th class="STHSW140"><?php echo $TeamLang['YeartoDateRevenue'];?></th><th class="STHSW140"><?php echo $TeamLang['ArenaCapacity'];?></th><th class="STHSW140"><?php echo $TeamLang['TeamPopularity'];?>
</th></tr>
<?php 
If ($TeamFinance != Null){
$TotalArenaCapacity = ($TeamFinance['ArenaCapacityL1'] + $TeamFinance['ArenaCapacityL2'] + $TeamFinance['ArenaCapacityL3'] + $TeamFinance['ArenaCapacityL4'] + $TeamFinance['ArenaCapacityLuxury']);
If ($TeamFinance['ScheduleHomeGameInAYear'] > 0){echo "<tr><td>" . ($TeamFinance['ScheduleHomeGameInAYear'] - $TeamStat['HomeGP'] ). "</td>\n";}else{echo "<td>" . (($TeamFinance['ScheduleGameInAYear'] / 2) - $TeamStat['HomeGP'])  . "</td>\n";}
if ($TeamStat['HomeGP'] > 0){echo "<td>" . Round($TeamFinance['TotalAttendance'] / $TeamStat['HomeGP']) . " - ";echo number_Format(($TeamFinance['TotalAttendance'] / ($TotalArenaCapacity * $TeamStat['HomeGP'])) *100,2) . "%</td>\n";
}else{echo "<td>0 - 0%</td>";}
if ($TeamStat['HomeGP'] > 0){echo "<td>" . number_format($TeamFinance['TotalIncome'] / $TeamStat['HomeGP'],0) . "$</td>";}else{echo "<td>0$</td>";}
echo "<td>" . number_format($TeamFinance['TotalIncome'],0) . "$</td>";
echo "<td>" . $TotalArenaCapacity . "</td>";
echo "<td>" . $TeamFinance['TeamPopularity'] . "</td></tr>";
}?>
</table>

<br />
<table class="STHSPHPTeamStat_Table"><tr><th colspan="5"><?php echo $TeamLang['Expenses'];?></th></tr><tr><th class="STHSW140"><?php echo $TeamLang['YearToDateExpenses'];?></th><th class="STHSW140"><?php echo $TeamLang['PlayersTotalSalaries'];?>
</th><th class="STHSW140"><?php echo $TeamLang['PlayersTotalAverageSalaries'];?></th><th class="STHSW140"><?php echo $TeamLang['CoachesSalaries'];?></th><th class="STHSW140"><?php echo $TeamLang['SpecialSalaryCapValue'];?></th></tr>
<?php 
If ($TeamFinance != Null){
echo "<tr><td>" . number_Format(($TeamFinance['ExpenseThisSeason']),0) . "$</td>\n";
echo "<td>" . number_Format($TeamFinance['TotalPlayersSalaries'],0) . "$</td>\n";
echo "<td>" . number_Format($TeamFinance['TotalPlayersSalariesAverage'],0) . "$</td>\n";
echo "<td>";If (Count($CoachInfo) == 1){echo number_Format($CoachInfo['Salary'],0) . "$";};echo "0$</td>\n";
echo "<td>" . number_Format($TeamFinance['SpecialSalaryCapY1'],0) . "$</td></tr>\n";
}?>
</table>
<table class="STHSPHPTeamStat_Table"><tr><th class="STHSW140"><?php echo $TeamLang['SalaryCapPerDays'];?></th><th class="STHSW140"><?php echo $TeamLang['SalaryCapToDate'];?></th><th class="STHSW140"><?php echo $TeamLang['LuxuryTaxeTotal'];?></th><th class="STHSW140"><?php echo $TeamLang['PlayerInSalaryCap'];?></th><th class="STHSW140"><?php echo $TeamLang['PlayerOutofSalaryCap'];?></th></tr>
<?php 
If ($TeamFinance != Null){
echo "<tr><td>" . number_Format($TeamFinance['SalaryCapPerDay'],0) . "$</td>\n";
echo "<td>" . number_Format($TeamFinance['SalaryCapToDate'],0) . "$</td>\n";
echo "<td>" . number_Format($TeamFinance['LuxuryTaxeTotal'],0) . "$</td>\n";
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
$Remaining = ($LeagueGeneral['ProScheduleTotalDay'] - $LeagueGeneral['ScheduleNextDay'] + 1);
echo "<td>";if($Remaining > 0){echo $Remaining;}else{echo "0";}echo "</td>\n";
echo "<td>" . number_Format($TeamFinance['ExpensePerDay'],0) . "$</td>\n";
echo "<td>" . number_Format($TeamFinance['EstimatedSeasonExpense'],0) . "$</td></tr>\n";
}?>

</table>
<br />

<table class="STHSPHPTeamStat_Table"><tr><th colspan="3"><?php echo $TeamLang['TeamTotalEstime'];?></th></tr><tr>
<th class="STHSW140"><?php echo $TeamLang['EstimatedSeasonExpenses'];?></th>
<th class="STHSW140"><?php echo $TeamLang['CurrentBankAccount'];?></th>
<th class="STHSW140"><?php echo $TeamLang['ProjectedBankAccount'];?></th>
</tr>
<?php 
If ($TeamFinance != Null){
echo "<tr><td>" . number_Format(($TeamFinance['EstimatedSeasonExpense'] + $TeamFarmFinance['EstimatedSeasonExpense']) ,0) . "$</td>\n";
echo "<td>" . number_Format($TeamFinance['CurrentBankAccount'],0) . "$</td>\n";
echo "<td>" . number_Format($TeamFinance['ProjectedBankAccount'],0) . "$</td></tr>\n";
}
?>
</table>

<?php
If ($TeamFinance != Null){
if ($LeagueFinance['SalaryCapOption'] > 0){
	echo "<table class=\"STHSPHPTeamStat_Table\"><tr>";
	echo "<th class=\"STHSW140\">" . $TeamLang['EstimatedSeasonSalaryCap'] . "</th>";
	echo "<th class=\"STHSW140\">" . $TeamLang['AvailableSalaryCap'] . "</th>";
	echo "<th class=\"STHSW140\">" . $TeamLang['MaximumSalaryCap']. "</th>";
	echo "<th class=\"STHSW140\">" . $TeamLang['OverMinimumSalaryCap'] . "</th>";
	echo "</tr><tr>";
	echo "<td>" . number_Format($TeamFinance['TotalSalaryCap'],0) . "$</td>\n";
	$TeamSalaryCap = 0;
	if ($LeagueFinance['SalaryCapOption'] == 0){
		$TeamSalaryCap = 2147483647;
	}elseif($LeagueFinance['SalaryCapOption'] == 2 OR $LeagueFinance['SalaryCapOption'] == 5){
		$TeamSalaryCap = $TeamFinance['CurrentBankAccount'] + $LeagueFinance['ProSalaryCapValue'];
	}else{
		$TeamSalaryCap = $LeagueFinance['ProSalaryCapValue'];
	}
	echo "<td>" . number_Format($TeamSalaryCap - $TeamFinance['TotalSalaryCap'],0) . "$</td>\n";
	echo "<td>" . number_Format($TeamSalaryCap,0) . "$</td>\n";
	echo "<td>" . number_Format($TeamFinance['TotalSalaryCap'] - $LeagueFinance['ProMinimumSalaryCap'],0) . "$</td>\n";
	echo "</tr></table>";
}}
?>
<br />

<br /><br /></div>
<div class="tabmain<?php if($SubMenu ==8){echo " active";}?>" id="tabmain8">
<?php If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS 8 Page PHP Performance : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}?>
<h3 class="STHSTeamProspect_DraftPick"><?php echo $TeamLang['DepthChart'];?></h3>
<table class="STHSPHPTeamStat_Table"><tr><th style="width:33%;"><?php echo $TeamLang['LeftWing'];?></th><th style="width:33%;"><?php echo $TeamLang['Center'];?></th><th style="width:33%;"><?php echo $TeamLang['RightWing'];?></th></tr>
<tr><td class="STHSAlignTop">
<table class="STHSPHPTeamStatDepthChart_Table">
<?php
If ($Team != 0){
$Query = "SELECT PlayerInfo.Name, PlayerInfo.Number, PlayerInfo.PosLW, PlayerInfo.PosC, PlayerInfo.PosRW, PlayerInfo.PosD, PlayerInfo.Rookie, PlayerInfo.Age, PlayerInfo.PO, PlayerInfo.Overall FROM PlayerInfo WHERE (PlayerInfo.Team)=" . $Team . " ORDER By Overall DESC, PO DESC";
If (file_exists($DatabaseFile) ==True){
$PlayerDepthChartC = $db->query($Query);	
$PlayerDepthChartLW = $db->query($Query);	
$PlayerDepthChartRW = $db->query($Query);	
$PlayerDepthChartD = $db->query($Query);
}}

if (empty($PlayerDepthChartC) == false){while ($Row = $PlayerDepthChartC ->fetchArray()) {
	If ($Row['PosLW']== "True"){
		echo "<tr><td class=\"STHSW140\">";
		$strTemp = (string)$Row['Name'];
		If ($Row['Rookie']== "True"){ $strTemp = $strTemp . " (R)";}
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $strTemp . "</a></td>";
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
		$strTemp = (string)$Row['Name'];
		If ($Row['Rookie']== "True"){ $strTemp = $strTemp . " (R)";}
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $strTemp . "</a></td>";
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
		$strTemp = (string)$Row['Name'];
		If ($Row['Rookie']== "True"){ $strTemp = $strTemp . " (R)";}
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $strTemp . "</a></td>";
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
		$strTemp = (string)$Row['Name'];
		If ($Row['Rookie']== "True"){ $strTemp = $strTemp . " (R)";}
		echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $strTemp . "</a></td>";
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
	$strTemp = (string)$Row['Name'];
	if ($Row['Rookie']== "True"){ $strTemp = $strTemp . " (R)";}
	echo "<a href=\"GoalieReport.php?Goalie=" . $Row['Number'] . "\">" . $strTemp . "</a></td>";	
	echo "<td class=\"STHSW35\">AGE:" . $Row['Age'] . "</td>";
	echo "<td class=\"STHSW35\">PO:" . $Row['PO'] . "</td>";
	echo "<td class=\"STHSW35\">OV:" . $Row['Overall'] . "</td>"; 
	echo "</tr>";
}}
?>
</table>
</td></tr></table>

<br />
<h3 class="STHSTeamProspect_Prospect"><?php echo  $TeamLang['Prospects'];?></h3>
<div class="tablesorter_ColumnSelectorWrapper">
    <input id="tablesorter_colSelect8P" type="checkbox" class="hidden">
    <label class="tablesorter_ColumnSelectorButton" for="tablesorter_colSelect8P"><?php echo $TableSorterLang['ShoworHideColumn'];?></label>
    <div id="tablesorter_ColumnSelector8P" class="tablesorter_ColumnSelector"></div>
	<?php include "FilterTip.php";?>
</div>

<table class="tablesorter STHSPHPTeam_ProspectsTable"><thead><tr>
<?php include "ProspectsSub.php";?>
</tbody></table>

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
		If ($row['FromTeamThemeID'] > 0){
			echo "<img src=\"" . $ImagesCDNPath . "/images/" . $row['FromTeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPDraftPickTeamImage \" /> ";		
		}else{
			echo $row['FromTeamAbbre'] . " ";
		}
		if ($row['ConditionalTrade'] != ""){echo "<span title=\"" . $row['ConditionalTradeExplication'] . "\">[CON: " . $row['ConditionalTrade'] . "]</span>";}
	}
	if ($CurrentRound < $DraftPickByYear  AND $CurrentRound > 0){for($x = $CurrentRound; $x < $DraftPickByYear; $x++){echo "<td></td>";}echo "</tr>\n";}else{echo "</td></tr>";} /* Code to Create Empty TD if team doesn't have last round Pick for the last year*/
	if (empty($TeamDraftPickCon) == false){
		echo "<tr><td><strong>" . $TradeLang['DraftPicksCon']. "</strong></td><td colspan=\"4\">";
		while ($row = $TeamDraftPickCon ->fetchArray()) {
			echo "<span title=\"" . $row['ConditionalTradeExplication'] . "\">" . $row['FromTeamAbbre'] . " - Y:" . $row['Year'] . " - R:" . $row['Round'] . "</span> / ";
		}
		echo "</td></tr>\n";
	}
	echo "</table>\n";
}else{
	echo "</tr></table>";
}
?>
<br /><br /></div>
<div class="tabmain<?php if($SubMenu ==13){echo " active";}?>" id="tabmain13">
<?php If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS 13 Page PHP Performance : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}?>
<br />
<?php include "NewsSub.php";?>
<br /><br /></div>
<div class="tabmain<?php if($SubMenu ==9){echo " active";}?>" id="tabmain9">
<?php If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS 9 Page PHP Performance : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}?>
<br />
<?php
echo "<h2><a href=\"Transaction.php?TradeLogHistory&Team=" . $Team . "\">" . $TeamName . " " . $TopMenuLang['TradeHistory'] . "</a></h2>";
if (empty($TeamLog ) == false){while ($row = $TeamLog ->fetchArray()) {
	echo "[" . $row['DateTime'] . "] - " . $row['Text'] . "<br />";
}}
?>
<br /><br /></div>
<div class="tabmain<?php if($SubMenu ==10){echo " active";}?>" id="tabmain10">
<?php If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS 10 Page PHP Performance : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}?>
<br />
<?php
if (empty($TeamTransaction) == false){while ($row = $TeamTransaction ->fetchArray()) { 
	If ($row['Color'] == ""){
		echo "[" . $row['DateTime'] . "] " . $row['Text'] . "<br />\n"; /* The \n is for a new line in the HTML Code */
	}else{
		echo "<span style=\"color:" . $row['Color'] . "\">[" . $row['DateTime'] . "] " . $row['Text'] . "</span><br />\n"; /* The \n is for a new line in the HTML Code */
	}
}}
?>
<br /><br /></div>
<div class="tabmain<?php if($SubMenu ==12){echo " active";}?>" id="tabmain12">
<?php If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS 12 Page PHP Performance : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}?>
<br />
<?php 
$booFound = (bool)False;
$sngInjury = (float)0;
if (empty($TeamInjurySuspension) == false){while ($Row = $TeamInjurySuspension ->fetchArray()) { 
	if ($Row['ConditionDecimal'] <= 95){
		$booFound = True;
		if($Row['Status1'] >= 2){$sngInjury = (95 - $Row['Condition']) / $LeagueGeneral['ProInjuryRecoverySpeed'];}else{$sngInjury = (95 - $Row['Condition']) / $LeagueGeneral['FarmInjuryRecoverySpeed'];}
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

<div class="tabmain<?php if($SubMenu ==11){echo " active";}?>" id="tabmain11">
<?php If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS 11 Page PHP Performance : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}?>
<br />

<h1><?php echo $TeamName . $TeamLang['CareerPlayerLeaderSeason'];?></h1>
<div class="tablesorter_ColumnSelectorWrapper">
    <input id="tablesorter_colSelect11SeasonP" type="checkbox" class="hidden">
    <label class="tablesorter_ColumnSelectorButton" for="tablesorter_colSelect11SeasonP"><?php echo $TableSorterLang['ShoworHideColumn'];?></label>
    <div id="tablesorter_ColumnSelector11SeasonP" class="tablesorter_ColumnSelector"></div>
</div>

<table class="tablesorter STHSPHPTeam_TeamCareerPlayersSeasonTop5"><thead><tr>
<?php $InputJson = $TeamCareerPlayersSeasonTop5; include "HistorySubForPlayerStat.php";?>

<br /><h1><?php echo $TeamName . $TeamLang['CareerGoaliesLeaderSeason'];?></h1>
<div class="tablesorter_ColumnSelectorWrapper">
    <input id="tablesorter_colSelect11SeasonG" type="checkbox" class="hidden">
    <label class="tablesorter_ColumnSelectorButton" for="tablesorter_colSelect11SeasonG"><?php echo $TableSorterLang['ShoworHideColumn'];?></label>
    <div id="tablesorter_ColumnSelector11SeasonG" class="tablesorter_ColumnSelector"></div>
</div>

<table class="tablesorter STHSPHPTeam_TeamCareerGoaliesSeasonTop5"><thead><tr>
<?php
$InputJson = $TeamCareerGoaliesSeasonTop5;
include "HistorySubForGoalieStat.php";
?>

<br /><h1><?php echo $TeamName . $TeamLang['CareerTeamStats'];?></h1>
<div class="tablesorter_ColumnSelectorWrapper">
    <input id="tablesorter_colSelect11" type="checkbox" class="hidden">
    <label class="tablesorter_ColumnSelectorButton" for="tablesorter_colSelect11"><?php echo $TableSorterLang['ShoworHideColumn'];?></label>
    <div id="tablesorter_ColumnSelector11" class="tablesorter_ColumnSelector"></div>
</div>

<table class="tablesorter STHSPHPTeam_TeamCareerStat"><thead><tr>
<th class="sorter-false"></th><th class="sorter-false" colspan="11"><?php echo $TeamLang['Overall'];?></th><th class="sorter-false" colspan="11"><?php echo $TeamLang['Home'];?></th><th class="sorter-false" colspan="11"><?php echo $TeamLang['Visitor'];?></th><th class="sorter-false" colspan="41"></th></tr><tr>
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
	echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"75\"><strong>" . $PlayersLang['RegularSeason'] . "</strong></td></tr>\n";
	if (empty($TeamCareerSeason) == false){foreach($TeamCareerSeason as $row) {
		if ($row['GP'] > 0){
			echo "<tr><td>" . $row['Year'] . "</td>";
			include "HistorySubForTeamStat.php";
		}
	}}
	if (empty($TeamCareerSumSeasonOnly) == false){
		$row = $TeamCareerSumSeasonOnly['0'];
		echo "<tr class=\"static\"><td><strong>" . $PlayersLang['Total'] . " " . $PlayersLang['RegularSeason']. "</strong></td>";
		include "HistorySubForTeamStat.php";
	}
}
if ($TeamCareerSumPlayoffOnly != Null){
	echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"75\"><strong>" . $SearchLang['Playoff'] . "</strong></td></tr>\n";
	if (empty($TeamCareerPlayoff) == false){foreach($TeamCareerPlayoff as $row) {
		if ($row['GP'] > 0){
			echo "<tr><td>" . $row['Year'] . "</td>";
			include "HistorySubForTeamStat.php";
		}
	}}
	if (empty($TeamCareerSumPlayoffOnly) == false){
		$row = $TeamCareerSumPlayoffOnly['0'];
		echo "<tr class=\"static\"><td><strong>" . $PlayersLang['Total'] . " " . $SearchLang['Playoff']. "</strong></td>";
		include "HistorySubForTeamStat.php";
	}
}
?>
</tbody></table>
<br />
<h1><?php echo $TeamName . $TeamLang['CareerPlayerLeaderPlayoff'];?></h1>
<div class="tablesorter_ColumnSelectorWrapper">
    <input id="tablesorter_colSelect11PlayoffP" type="checkbox" class="hidden">
    <label class="tablesorter_ColumnSelectorButton" for="tablesorter_colSelect11PlayoffP"><?php echo $TableSorterLang['ShoworHideColumn'];?></label>
    <div id="tablesorter_ColumnSelector11PlayoffP" class="tablesorter_ColumnSelector"></div>
</div>

<table class="tablesorter STHSPHPTeam_TeamCareerPlayersPlayoffTop5"><thead><tr>
<?php $InputJson = $TeamCareerPlayersPlayoffTop5; include "HistorySubForPlayerStat.php";?>

<br /><h1><?php echo $TeamName . $TeamLang['CareerGoaliesLeaderPlayoff'];?></h1>
<div class="tablesorter_ColumnSelectorWrapper">
    <input id="tablesorter_colSelect11PlayoffG" type="checkbox" class="hidden">
    <label class="tablesorter_ColumnSelectorButton" for="tablesorter_colSelect11PlayoffG"><?php echo $TableSorterLang['ShoworHideColumn'];?></label>
    <div id="tablesorter_ColumnSelector11PlayoffG" class="tablesorter_ColumnSelector"></div>
</div>

<table class="tablesorter STHSPHPTeam_TeamCareerGoaliesPlayoffTop5"><thead><tr>
<?php $InputJson = $TeamCareerGoaliesPlayoffTop5; include "HistorySubForGoalieStat.php";?>
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
      columnSelector_breakpoints : [ '20em', '60em', '85em', '92em', '98em', '99em' ],
	  filter_columnFilters: true,
      filter_placeholder: { search : '<?php echo $TableSorterLang['Search'];?>' },
	  filter_searchDelay : 500,	  
      filter_reset: '.tablesorter_Reset'	 
    }
  });
    $(".STHSPHPTeam_ProspectsTable").tablesorter({
    widgets: ['columnSelector', 'stickyHeaders', 'filter'],
    widgetOptions : {
      columnSelector_container : $('#tablesorter_ColumnSelector8P'),
      columnSelector_layout : '<label><input type="checkbox">{name}</label>',
      columnSelector_name  : 'title',
      columnSelector_mediaquery: true,
      columnSelector_mediaqueryName: 'Automatic',
      columnSelector_mediaqueryState: true,
      columnSelector_mediaqueryHidden: true,
      columnSelector_breakpoints : [ '10em', '20em', '30em', '40em', '50em', '60em' ],
	  filter_columnFilters: true,
      filter_placeholder: { search : '<?php echo $TableSorterLang['Search'];?>' },
	  filter_searchDelay : 1000,	  
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