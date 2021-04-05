<!DOCTYPE html>
<?php include "Header.php";?>
<?php
$Team = (integer)-1; /* -1 All Team */
$Title = (string)"";
$Search = (boolean)False;
$MimimumData = (integer)10;
$UpdateCareerStatDBV1 = (boolean)false;
If (file_exists($DatabaseFile) == false){
	$LeagueName = $DatabaseNotFound;
	$CareerStatGoalie = Null;
	echo "<title>" . $DatabaseNotFound . "</title>";
	$Title = $DatabaseNotFound;
	$TeamName = Null;
}else{
	$TypeText = (string)"Pro";$TitleType = $DynamicTitleLang['Pro'];
	$ACSQuery = (boolean)FALSE;/* The SQL Query must be Ascending Order and not Descending */
	$Playoff = (string)"False";
	$MaximumResult = (integer)0;
	$OrderByField = (string)"W";
	$OrderByFieldText = (string)"Win";
	$OrderByInput = (string)"";
	$TitleOverwrite = (string)"";
	$TeamName = (string)"";
	$Year = (integer)0;
	if(isset($_GET['Farm'])){$TypeText = "Farm";$TitleType = $DynamicTitleLang['Farm'];}
	if(isset($_GET['ACS'])){$ACSQuery= TRUE;}
	if(isset($_GET['Playoff'])){$Playoff="True";$MimimumData=1;}
	if(isset($_GET['Max'])){$MaximumResult = filter_var($_GET['Max'], FILTER_SANITIZE_NUMBER_INT);} 
	if(isset($_GET['Order'])){$OrderByInput = filter_var($_GET['Order'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH || FILTER_FLAG_NO_ENCODE_QUOTES || FILTER_FLAG_STRIP_BACKTICK);} 
	if(isset($_GET['Title'])){$TitleOverwrite  = filter_var($_GET['Title'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH || FILTER_FLAG_NO_ENCODE_QUOTES || FILTER_FLAG_STRIP_BACKTICK);} 
	if(isset($_GET['Year'])){$Year = filter_var($_GET['Year'], FILTER_SANITIZE_NUMBER_INT);} 
	if(isset($_GET['TeamName'])){$TeamName = filter_var($_GET['TeamName'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH || FILTER_FLAG_NO_ENCODE_QUOTES || FILTER_FLAG_STRIP_BACKTICK);}
	$LeagueName = (string)"";
	
	
	foreach ($GoaliesStatPossibleOrderField as $Value) {
		If (strtoupper($Value[0]) == strtoupper($OrderByInput)){
			$OrderByField = $Value[0];
			$OrderByFieldText = $Value[1];
			Break;
		}
	}
	
	$db = new SQLite3($DatabaseFile);
	$Query = "Select Name,PlayOffStarted,PreSeasonSchedule from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	If (file_exists($CareerStatDatabaseFile) == true){ /* CareerStat */
		$CareerStatdb = new SQLite3($CareerStatDatabaseFile);
		$CareerStatdb->query("ATTACH DATABASE '".realpath($DatabaseFile)."' AS CurrentDB");
		
		If ($Playoff=="True"){$Title = $PlayersLang['Playoff'] .  " ";}
		$Title = $Title . $DynamicTitleLang['CareerStat'];
		If ($TeamName != ""){$Title = $Title . $TeamName . " - ";}
		If ($Year != ""){$Title = $Title . $Year . " - ";}
		If($MaximumResult == 0){$Title = $Title . $DynamicTitleLang['All'];}else{$Title = $Title . $DynamicTitleLang['Top'] . $MaximumResult . " ";}

$TeamName = STL;
		
		$Query = "SELECT MainTable.*, Goaler" . $TypeText . "Stat.*, GoalerInfo.NHLID, GoalerInfo.Country, GoalerInfo.TeamName, ROUND((CAST((MainTable.SumofGA + IfNull(Goaler" . $TypeText . "Stat.GA,0)) AS REAL) / (  (MainTable.SumofSecondPlay + IfNull(Goaler" . $TypeText . "Stat.SecondPlay,0)) / 60))*60,3) AS TotalGAA, ROUND((CAST((MainTable.SumofSA + IfNull(Goaler" . $TypeText . "Stat.SA,0)) - (MainTable.SumofGA + IfNull(Goaler" . $TypeText . "Stat.GA,0)) AS REAL) / (MainTable.SumofSA + IfNull(Goaler" . $TypeText . "Stat.SA,0))),3) AS TotalPCT, ROUND((CAST((MainTable.SumofPenalityShotsShots + IfNull(Goaler" . $TypeText . "Stat.PenalityShotsShots,0)) - (MainTable.SumofPenalityShotsGoals + IfNull(Goaler" . $TypeText . "Stat.PenalityShotsGoals,0)) AS REAL) / (MainTable.SumofPenalityShotsShots + Goaler" . $TypeText . "Stat.PenalityShotsShots)),3) AS TotalPenalityShotsPCT FROM ( SELECT Name AS SumOfName, UniqueID, Sum(Goaler" . $TypeText . "StatCareer.GP) AS SumOfGP, Sum(Goaler" . $TypeText . "StatCareer.SecondPlay) AS SumOfSecondPlay, Sum(Goaler" . $TypeText . "StatCareer.W) AS SumOfW, Sum(Goaler" . $TypeText . "StatCareer.L) AS SumOfL, Sum(Goaler" . $TypeText . "StatCareer.OTL) AS SumOfOTL, Sum(Goaler" . $TypeText . "StatCareer.Shootout) AS SumOfShootout, Sum(Goaler" . $TypeText . "StatCareer.GA) AS SumOfGA, Sum(Goaler" . $TypeText . "StatCareer.SA) AS SumOfSA, Sum(Goaler" . $TypeText . "StatCareer.SARebound) AS SumOfSARebound, Sum(Goaler" . $TypeText . "StatCareer.Pim) AS SumOfPim, Sum(Goaler" . $TypeText . "StatCareer.A) AS SumOfA, Sum(Goaler" . $TypeText . "StatCareer.PenalityShotsShots) AS SumOfPenalityShotsShots, Sum(Goaler" . $TypeText . "StatCareer.PenalityShotsGoals) AS SumOfPenalityShotsGoals, Sum(Goaler" . $TypeText . "StatCareer.StartGoaler) AS SumOfStartGoaler, Sum(Goaler" . $TypeText . "StatCareer.BackupGoaler) AS SumOfBackupGoaler, Sum(Goaler" . $TypeText . "StatCareer.EmptyNetGoal) AS SumOfEmptyNetGoal, Sum(Goaler" . $TypeText . "StatCareer.Star1) AS SumOfStar1, Sum(Goaler" . $TypeText . "StatCareer.Star2) AS SumOfStar2, Sum(Goaler" . $TypeText . "StatCareer.Star3) AS SumOfStar3, ROUND((CAST(Sum(Goaler" . $TypeText . "StatCareer.GA) AS REAL) / (Sum(Goaler" . $TypeText . "StatCareer.SecondPlay) / 60))*60,3) AS SumOfGAA, ROUND((CAST(Sum(Goaler" . $TypeText . "StatCareer.SA) - Sum(Goaler" . $TypeText . "StatCareer.GA) AS REAL) / (Sum(Goaler" . $TypeText . "StatCareer.SA))),3) AS SumOfPCT, ROUND((CAST(Sum(Goaler" . $TypeText . "StatCareer.PenalityShotsShots) - Sum(Goaler" . $TypeText . "StatCareer.PenalityShotsGoals) AS REAL) / (Sum(Goaler" . $TypeText . "StatCareer.PenalityShotsShots))),3) AS SumOfPenalityShotsPCT FROM Goaler" . $TypeText . "StatCareer WHERE Playoff = \"" . $Playoff . "\"";

		If($Year > 0){$Query = $Query . " AND YEAR = \"" . $Year . "\"";}
		If($TeamName != ""){$Query = $Query . " AND TeamName = \"" . $TeamName . "\"";}
	
	
		If($Year > 0 OR $LeagueGeneral['PlayOffStarted'] != $Playoff OR $TeamName != ""){
			$Query = $Query . " GROUP BY Goaler" . $TypeText . "StatCareer.Name) AS MainTable LEFT JOIN Goaler" . $TypeText . "Stat ON MainTable.SumOfName = Goaler" . $TypeText . "Stat.Name LEFT JOIN GoalerInfo ON MainTable.SumOfName = GoalerInfo.Name ORDER BY (MainTable.SumOf".$OrderByField.") ";
		}elseif($OrderByField == "GAA"){
			$Query = $Query . " GROUP BY Goaler" . $TypeText . "StatCareer.Name) AS MainTable LEFT JOIN Goaler" . $TypeText . "Stat ON MainTable.SumOfName = Goaler" . $TypeText . "Stat.Name LEFT JOIN GoalerInfo ON MainTable.SumOfName = GoalerInfo.Name Where SumofGA >= " . ($MimimumData *  5) . " ORDER BY Total".$OrderByField." ";
		}elseif($OrderByField == "PCT"){
			$Query = $Query . " GROUP BY Goaler" . $TypeText . "StatCareer.Name) AS MainTable LEFT JOIN Goaler" . $TypeText . "Stat ON MainTable.SumOfName = Goaler" . $TypeText . "Stat.Name LEFT JOIN GoalerInfo ON MainTable.SumOfName = GoalerInfo.Name Where SumOfSA >= " . ($MimimumData *  25) . " ORDER BY Total".$OrderByField." ";
		}elseif($OrderByField == "PenalityShotsPCT"){
			$Query = $Query . " GROUP BY Goaler" . $TypeText . "StatCareer.Name) AS MainTable LEFT JOIN Goaler" . $TypeText . "Stat ON MainTable.SumOfName = Goaler" . $TypeText . "Stat.Name LEFT JOIN GoalerInfo ON MainTable.SumOfName = GoalerInfo.Name Where SumOfPenalityShotsShots >= " . ($MimimumData *  1) . " ORDER BY Total".$OrderByField." ";			
		}else{
			$Query = $Query . " GROUP BY Goaler" . $TypeText . "StatCareer.Name) AS MainTable LEFT JOIN Goaler" . $TypeText . "Stat ON MainTable.SumOfName = Goaler" . $TypeText . "Stat.Name LEFT JOIN GoalerInfo ON MainTable.SumOfName = GoalerInfo.Name ORDER BY (MainTable.SumOf".$OrderByField." + IfNull(Goaler" . $TypeText . "Stat.".$OrderByField.",0)) ";
		}
		
		$Title = $Title  . $DynamicTitleLang['GoaliesStat'] . $TitleType;
		
		If ($ACSQuery == TRUE){
			$Query = $Query . " ASC";
			$Title = $Title . $DynamicTitleLang['InAscendingOrderBy'] . $OrderByFieldText;
		}else{
			$Query = $Query . " DESC";
			$Title = $Title . $DynamicTitleLang['InDecendingOrderBy'] . $OrderByFieldText;
		}
		$Query = $Query . ", (MainTable.SumOfGP + IfNull(Goaler" . $TypeText . "Stat.GP,0)) ASC LIMIT 3";
		If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
		$CareerStatGoalie = $CareerStatdb->query($Query);
		
		include "SearchCareerSub.php";	
	}else{
		$CareerStatGoalie = Null;
		$Title = $CareeratabaseNotFound;
	}	

	/* OverWrite Title if information is get from PHP GET */
	if($TitleOverwrite <> ""){$Title = $TitleOverwrite;}	
	echo "<title>" . $LeagueName . " - " . $Title . "</title>";
}?>



<?php
$Team = (integer)-1; /* -1 All Team */
$Title = (string)"";
$MimimumData = (integer)10;
$UpdateCareerStatDBV1 = (boolean)false;
$Search = (boolean)False;
If (file_exists($DatabaseFile) == false){
	$LeagueName = $DatabaseNotFound;
	$CareerPlayerStat = Null;
	echo "<title>" . $DatabaseNotFound . "</title>";
	$Title = $DatabaseNotFound;
	$TeamName = Null;
}else{
	$TypeText = (string)"Pro";$TitleType = $DynamicTitleLang['Pro'];
	$ACSQuery = (boolean)FALSE;/* The SQL Query must be Ascending Order and not Descending */
	$PosF = (boolean)FALSE; $PosD = (boolean)FALSE;
	$Playoff = (string)"False";
	if(isset($_GET['PosF'])){$PosF= TRUE;}
	if(isset($_GET['PosD'])){$PosD= TRUE;}	
	$MaximumResult = (integer)0;
	$OrderByField = (string)"P";
	$OrderByFieldText = (string)"Points";
	$OrderByInput = (string)"";
	$TitleOverwrite = (string)"";
	$Year = (integer)0;	
	$TeamName = (string)"";
	if(isset($_GET['Farm'])){$TypeText = "Farm";$TitleType = $DynamicTitleLang['Farm'];}
	if(isset($_GET['ACS'])){$ACSQuery= TRUE;}
	if(isset($_GET['Playoff'])){$Playoff="True";$MimimumData=1;}
	if(isset($_GET['Max'])){$MaximumResult = filter_var($_GET['Max'], FILTER_SANITIZE_NUMBER_INT);} 
	if(isset($_GET['Order'])){$OrderByInput  = filter_var($_GET['Order'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH || FILTER_FLAG_NO_ENCODE_QUOTES || FILTER_FLAG_STRIP_BACKTICK);} 
	if(isset($_GET['Title'])){$TitleOverwrite  = filter_var($_GET['Title'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH || FILTER_FLAG_NO_ENCODE_QUOTES || FILTER_FLAG_STRIP_BACKTICK);} 
	if(isset($_GET['Year'])){$Year = filter_var($_GET['Year'], FILTER_SANITIZE_NUMBER_INT);} 	
	if(isset($_GET['TeamName'])){$TeamName = filter_var($_GET['TeamName'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH || FILTER_FLAG_NO_ENCODE_QUOTES || FILTER_FLAG_STRIP_BACKTICK);}	
	$LeagueName = (string)"";

	include "SearchPossibleOrderField.php";
	
	foreach ($PlayersStatPossibleOrderField as $Value) {
		If (strtoupper($Value[0]) == strtoupper($OrderByInput)){
			$OrderByField = $Value[0];
			$OrderByFieldText = $Value[1];
			Break;
		}
	}
	
	$TeamName = STL;


	$db = new SQLite3($DatabaseFile);
	$Query = "Select Name,PlayOffStarted,PreSeasonSchedule from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	
	If (file_exists($CareerStatDatabaseFile) == true){ /* CareerStat */
		$CareerStatdb = new SQLite3($CareerStatDatabaseFile);
		$CareerStatdb->query("ATTACH DATABASE '".realpath($DatabaseFile)."' AS CurrentDB");
		
		If ($Playoff=="True"){$Title = $PlayersLang['Playoff'] .  " ";}
		$Title = $Title . $DynamicTitleLang['CareerStat'];
		If($PosF == True){$Title = $Title . $TeamLang['Forward'] . " - ";}
		If($PosD == True){$Title = $Title . $TeamLang['Defenseman'] . " - ";}			
		If ($TeamName != ""){$Title = $Title . $TeamName . " - ";}
		If ($Year != ""){$Title = $Title . $Year . " - ";}
		If($MaximumResult == 0){$Title = $Title . $DynamicTitleLang['All'];}else{$Title = $Title . $DynamicTitleLang['Top'] . $MaximumResult . " ";}
		
		$Query="SELECT MainTable.*, Player" . $TypeText . "Stat.*, PlayerInfo.NHLID, PlayerInfo.Country, PlayerInfo.TeamName,ROUND(CAST((MainTable.SumOfG + IfNull(Player" . $TypeText . "Stat.G,0)) AS REAL) / CAST((MainTable.SumOfShots + IfNull(Player" . $TypeText . "Stat.Shots,0)) AS REAL) *100,2) AS TotalShotsPCT,ROUND(CAST((MainTable.SumOfSecondPlay+ IfNull(Player" . $TypeText . "Stat.SecondPlay,0)) AS REAL) / 60 / CAST((MainTable.SumOfGP + IfNull(Player" . $TypeText . "Stat.GP,0)) AS REAL),2) AS TotalAMG, ROUND(CAST((MainTable.SumOfFaceOffWon + IfNull(Player" . $TypeText . "Stat.FaceOffWon,0)) AS REAL) / CAST((MainTable.SumOfFaceOffTotal + IfNull(Player" . $TypeText . "Stat.FaceOffTotal,0)) AS REAL) *100,2) as TotalFaceoffPCT, ROUND(CAST((MainTable.SumOfP+ IfNull(Player" . $TypeText . "Stat.P,0)) AS REAL) / CAST((MainTable.SumOfSecondPlay + IfNull(Player" . $TypeText . "Stat.SecondPlay,0)) AS REAL) * 60 * 20,2) AS TotalP20 FROM (SELECT Name AS SumOfName, UniqueID, Sum(Player" . $TypeText . "StatCareer.GP) AS SumOfGP, Sum(Player" . $TypeText . "StatCareer.Shots) AS SumOfShots, Sum(Player" . $TypeText . "StatCareer.G) AS SumOfG, Sum(Player" . $TypeText . "StatCareer.A) AS SumOfA, Sum(Player" . $TypeText . "StatCareer.P) AS SumOfP, Sum(Player" . $TypeText . "StatCareer.PlusMinus) AS SumOfPlusMinus, Sum(Player" . $TypeText . "StatCareer.Pim) AS SumOfPim, Sum(Player" . $TypeText . "StatCareer.Pim5) AS SumOfPim5, Sum(Player" . $TypeText . "StatCareer.ShotsBlock) AS SumOfShotsBlock, Sum(Player" . $TypeText . "StatCareer.OwnShotsBlock) AS SumOfOwnShotsBlock, Sum(Player" . $TypeText . "StatCareer.OwnShotsMissGoal) AS SumOfOwnShotsMissGoal, Sum(Player" . $TypeText . "StatCareer.Hits) AS SumOfHits, Sum(Player" . $TypeText . "StatCareer.HitsTook) AS SumOfHitsTook, Sum(Player" . $TypeText . "StatCareer.GW) AS SumOfGW, Sum(Player" . $TypeText . "StatCareer.GT) AS SumOfGT, Sum(Player" . $TypeText . "StatCareer.FaceOffWon) AS SumOfFaceOffWon, Sum(Player" . $TypeText . "StatCareer.FaceOffTotal) AS SumOfFaceOffTotal, Sum(Player" . $TypeText . "StatCareer.PenalityShotsScore) AS SumOfPenalityShotsScore, Sum(Player" . $TypeText . "StatCareer.PenalityShotsTotal) AS SumOfPenalityShotsTotal, Sum(Player" . $TypeText . "StatCareer.EmptyNetGoal) AS SumOfEmptyNetGoal, Sum(Player" . $TypeText . "StatCareer.SecondPlay) AS SumOfSecondPlay, Sum(Player" . $TypeText . "StatCareer.HatTrick) AS SumOfHatTrick, Sum(Player" . $TypeText . "StatCareer.PPG) AS SumOfPPG, Sum(Player" . $TypeText . "StatCareer.PPA) AS SumOfPPA, Sum(Player" . $TypeText . "StatCareer.PPP) AS SumOfPPP, Sum(Player" . $TypeText . "StatCareer.PPShots) AS SumOfPPShots, Sum(Player" . $TypeText . "StatCareer.PPSecondPlay) AS SumOfPPSecondPlay, Sum(Player" . $TypeText . "StatCareer.PKG) AS SumOfPKG, Sum(Player" . $TypeText . "StatCareer.PKA) AS SumOfPKA, Sum(Player" . $TypeText . "StatCareer.PKP) AS SumOfPKP, Sum(Player" . $TypeText . "StatCareer.PKShots) AS SumOfPKShots, Sum(Player" . $TypeText . "StatCareer.PKSecondPlay) AS SumOfPKSecondPlay, Sum(Player" . $TypeText . "StatCareer.GiveAway) AS SumOfGiveAway, Sum(Player" . $TypeText . "StatCareer.TakeAway) AS SumOfTakeAway, Sum(Player" . $TypeText . "StatCareer.PuckPossesionTime) AS SumOfPuckPossesionTime, Sum(Player" . $TypeText . "StatCareer.FightW) AS SumOfFightW, Sum(Player" . $TypeText . "StatCareer.FightL) AS SumOfFightL, Sum(Player" . $TypeText . "StatCareer.FightT) AS SumOfFightT, Sum(Player" . $TypeText . "StatCareer.Star1) AS SumOfStar1, Sum(Player" . $TypeText . "StatCareer.Star2) AS SumOfStar2, Sum(Player" . $TypeText . "StatCareer.Star3) AS SumOfStar3, ROUND((CAST(Sum(Player" . $TypeText . "StatCareer.G) AS REAL) / (Sum(Player" . $TypeText . "StatCareer.Shots)))*100,2) AS SumOfShotsPCT, ROUND((CAST(Sum(Player" . $TypeText . "StatCareer.SecondPlay) AS REAL) / 60 / (Sum(Player" . $TypeText . "StatCareer.GP))),2) AS SumOfAMG, ROUND((CAST(Sum(Player" . $TypeText . "StatCareer.FaceOffWon) AS REAL) / (Sum(Player" . $TypeText . "StatCareer.FaceOffTotal)))*100,2) as SumOfFaceoffPCT, ROUND((CAST(Sum(Player" . $TypeText . "StatCareer.P) AS REAL) / (Sum(Player" . $TypeText . "StatCareer.SecondPlay)) * 60 * 20),2) AS SumOfP20 FROM Player" . $TypeText . "StatCareer WHERE Playoff = \"" . $Playoff . "\"";
		
		If($Year > 0){$Query = $Query . " AND Player" . $TypeText . "StatCareer.Year = \"" . $Year . "\"";}
		If($TeamName != ""){$Query = $Query . " AND Player" . $TypeText . "StatCareer.TeamName = \"" . $TeamName . "\"";}
		If($PosF == True){$Query = $Query . " AND (Player" . $TypeText . "StatCareer.PosC = \"True\" OR Player" . $TypeText . "StatCareer.PosLW = \"True\" OR Player" . $TypeText . "StatCareer.PosRW = \"True\")";}		
		If($PosD == True){$Query = $Query . " AND Player" . $TypeText . "StatCareer.PosD = \"True\"";}		
		If($Year > 0 OR $LeagueGeneral['PlayOffStarted'] != $Playoff OR $TeamName != ""){
			$Query = $Query . " GROUP BY Player" . $TypeText . "StatCareer.Name) AS MainTable LEFT JOIN Player" . $TypeText . "Stat ON MainTable.SumOfName = Player" . $TypeText . "Stat.Name LEFT JOIN PlayerInfo ON MainTable.SumOfName = PlayerInfo.Name ORDER BY (MainTable.SumOf".$OrderByField.") ";
		}elseif($OrderByField == "AMG" OR $OrderByField == "P20"){
			$Query = $Query . " GROUP BY Player" . $TypeText . "StatCareer.Name) AS MainTable LEFT JOIN Player" . $TypeText . "Stat ON MainTable.SumOfName = Player" . $TypeText . "Stat.Name LEFT JOIN PlayerInfo ON MainTable.SumOfName = PlayerInfo.Name WHERE SumOfP >= " . ($MimimumData *  5) . " ORDER BY Total".$OrderByField." ";
		}elseif($OrderByField == "FaceoffPCT"){
			$Query = $Query . " GROUP BY Player" . $TypeText . "StatCareer.Name) AS MainTable LEFT JOIN Player" . $TypeText . "Stat ON MainTable.SumOfName = Player" . $TypeText . "Stat.Name LEFT JOIN PlayerInfo ON MainTable.SumOfName = PlayerInfo.Name WHERE SumOfFaceOffTotal >= " . ($MimimumData *  10) . " ORDER BY Total".$OrderByField." ";			
		}elseif($OrderByField == "ShotsPCT"){
			$Query = $Query . " GROUP BY Player" . $TypeText . "StatCareer.Name) AS MainTable LEFT JOIN Player" . $TypeText . "Stat ON MainTable.SumOfName = Player" . $TypeText . "Stat.Name LEFT JOIN PlayerInfo ON MainTable.SumOfName = PlayerInfo.Name WHERE SumOfShots >= " . ($MimimumData *  10) . " ORDER BY Total".$OrderByField." ";			
		}else{
			$Query = $Query . " GROUP BY Player" . $TypeText . "StatCareer.Name) AS MainTable LEFT JOIN Player" . $TypeText . "Stat ON MainTable.SumOfName = Player" . $TypeText . "Stat.Name LEFT JOIN PlayerInfo ON MainTable.SumOfName = PlayerInfo.Name ORDER BY (MainTable.SumOf".$OrderByField." + IfNull(Player" . $TypeText . "Stat.".$OrderByField.",0)) ";
		}
		
		$Title = $Title  . $DynamicTitleLang['PlayersStat'] . $TitleType;	
		
		If ($ACSQuery == TRUE){
			$Query = $Query . " ASC";
			$Title = $Title . $DynamicTitleLang['InAscendingOrderBy'] . $OrderByFieldText;
		}else{
			$Query = $Query . " DESC";
			$Title = $Title . $DynamicTitleLang['InDecendingOrderBy'] . $OrderByFieldText;
		}
		$Query = $Query . ", (MainTable.SumOfGP + IfNull(Player" . $TypeText . "Stat.GP,0)) ASC LIMIT 5";
		If ($MaximumResult  > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
		$CareerPlayerStat = $CareerStatdb->query($Query);	

		include "SearchCareerSub.php";		
	}else{
		$CareerPlayerStat = Null;
		$Title = $CareeratabaseNotFound;
	}			
	
	/* OverWrite Title if information is get from PHP GET */
	if($TitleOverwrite <> ""){$Title = $TitleOverwrite;}
	echo "<title>" . $LeagueName . " - " . $Title . "</title>";
}?>

<?php
$Team = (integer)-1; /* -1 All Team */
$Title = (string)"";
$Search = (boolean)False;
$MimimumData = (integer)10;
$UpdateCareerStatDBV1 = (boolean)false;
If (file_exists($DatabaseFile) == false){
	$LeagueName = $DatabaseNotFound;
	$CareerStatGoalie2 = Null;
	echo "<title>" . $DatabaseNotFound . "</title>";
	$Title = $DatabaseNotFound;
	$TeamName = Null;
}else{
	$TypeText = (string)"Pro";$TitleType = $DynamicTitleLang['Pro'];
	$ACSQuery = (boolean)FALSE;/* The SQL Query must be Ascending Order and not Descending */
	$Playoff = (string)"False";
	$MaximumResult = (integer)0;
	$OrderByField = (string)"W";
	$OrderByFieldText = (string)"Win";
	$OrderByInput = (string)"";
	$TitleOverwrite = (string)"";
	$TeamName = (string)"";
	$Year = (integer)0;
	if(isset($_GET['Farm'])){$TypeText = "Farm";$TitleType = $DynamicTitleLang['Farm'];}
	if(isset($_GET['ACS'])){$ACSQuery= TRUE;}
	if(isset($_GET['Playoff'])){$Playoff="True";$MimimumData=1;}
	if(isset($_GET['Max'])){$MaximumResult = filter_var($_GET['Max'], FILTER_SANITIZE_NUMBER_INT);} 
	if(isset($_GET['Order'])){$OrderByInput = filter_var($_GET['Order'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH || FILTER_FLAG_NO_ENCODE_QUOTES || FILTER_FLAG_STRIP_BACKTICK);} 
	if(isset($_GET['Title'])){$TitleOverwrite  = filter_var($_GET['Title'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH || FILTER_FLAG_NO_ENCODE_QUOTES || FILTER_FLAG_STRIP_BACKTICK);} 
	if(isset($_GET['Year'])){$Year = filter_var($_GET['Year'], FILTER_SANITIZE_NUMBER_INT);} 
	if(isset($_GET['TeamName'])){$TeamName = filter_var($_GET['TeamName'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH || FILTER_FLAG_NO_ENCODE_QUOTES || FILTER_FLAG_STRIP_BACKTICK);}
	$LeagueName = (string)"";
	
	
	foreach ($GoaliesStatPossibleOrderField as $Value) {
		If (strtoupper($Value[0]) == strtoupper($OrderByInput)){
			$OrderByField = $Value[0];
			$OrderByFieldText = $Value[1];
			Break;
		}
	}
	
	$db = new SQLite3($DatabaseFile);
	$Query = "Select Name,PlayOffStarted,PreSeasonSchedule from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	If (file_exists($CareerStatDatabaseFile) == true){ /* CareerStat */
		$CareerStatdb = new SQLite3($CareerStatDatabaseFile);
		$CareerStatdb->query("ATTACH DATABASE '".realpath($DatabaseFile)."' AS CurrentDB");
		
		If ($Playoff=="True"){$Title = $PlayersLang['Playoff'] .  " ";}
		$Title = $Title . $DynamicTitleLang['CareerStat'];
		If ($TeamName != ""){$Title = $Title . $TeamName . " - ";}
		If ($Year != ""){$Title = $Title . $Year . " - ";}
		If($MaximumResult == 0){$Title = $Title . $DynamicTitleLang['All'];}else{$Title = $Title . $DynamicTitleLang['Top'] . $MaximumResult . " ";}

$TeamName = STL;
$Playoff="True";
		
		$Query = "SELECT MainTable.*, Goaler" . $TypeText . "Stat.*, GoalerInfo.NHLID, GoalerInfo.Country, GoalerInfo.TeamName, ROUND((CAST((MainTable.SumofGA + IfNull(Goaler" . $TypeText . "Stat.GA,0)) AS REAL) / (  (MainTable.SumofSecondPlay + IfNull(Goaler" . $TypeText . "Stat.SecondPlay,0)) / 60))*60,3) AS TotalGAA, ROUND((CAST((MainTable.SumofSA + IfNull(Goaler" . $TypeText . "Stat.SA,0)) - (MainTable.SumofGA + IfNull(Goaler" . $TypeText . "Stat.GA,0)) AS REAL) / (MainTable.SumofSA + IfNull(Goaler" . $TypeText . "Stat.SA,0))),3) AS TotalPCT, ROUND((CAST((MainTable.SumofPenalityShotsShots + IfNull(Goaler" . $TypeText . "Stat.PenalityShotsShots,0)) - (MainTable.SumofPenalityShotsGoals + IfNull(Goaler" . $TypeText . "Stat.PenalityShotsGoals,0)) AS REAL) / (MainTable.SumofPenalityShotsShots + Goaler" . $TypeText . "Stat.PenalityShotsShots)),3) AS TotalPenalityShotsPCT FROM ( SELECT Name AS SumOfName, UniqueID, Sum(Goaler" . $TypeText . "StatCareer.GP) AS SumOfGP, Sum(Goaler" . $TypeText . "StatCareer.SecondPlay) AS SumOfSecondPlay, Sum(Goaler" . $TypeText . "StatCareer.W) AS SumOfW, Sum(Goaler" . $TypeText . "StatCareer.L) AS SumOfL, Sum(Goaler" . $TypeText . "StatCareer.OTL) AS SumOfOTL, Sum(Goaler" . $TypeText . "StatCareer.Shootout) AS SumOfShootout, Sum(Goaler" . $TypeText . "StatCareer.GA) AS SumOfGA, Sum(Goaler" . $TypeText . "StatCareer.SA) AS SumOfSA, Sum(Goaler" . $TypeText . "StatCareer.SARebound) AS SumOfSARebound, Sum(Goaler" . $TypeText . "StatCareer.Pim) AS SumOfPim, Sum(Goaler" . $TypeText . "StatCareer.A) AS SumOfA, Sum(Goaler" . $TypeText . "StatCareer.PenalityShotsShots) AS SumOfPenalityShotsShots, Sum(Goaler" . $TypeText . "StatCareer.PenalityShotsGoals) AS SumOfPenalityShotsGoals, Sum(Goaler" . $TypeText . "StatCareer.StartGoaler) AS SumOfStartGoaler, Sum(Goaler" . $TypeText . "StatCareer.BackupGoaler) AS SumOfBackupGoaler, Sum(Goaler" . $TypeText . "StatCareer.EmptyNetGoal) AS SumOfEmptyNetGoal, Sum(Goaler" . $TypeText . "StatCareer.Star1) AS SumOfStar1, Sum(Goaler" . $TypeText . "StatCareer.Star2) AS SumOfStar2, Sum(Goaler" . $TypeText . "StatCareer.Star3) AS SumOfStar3, ROUND((CAST(Sum(Goaler" . $TypeText . "StatCareer.GA) AS REAL) / (Sum(Goaler" . $TypeText . "StatCareer.SecondPlay) / 60))*60,3) AS SumOfGAA, ROUND((CAST(Sum(Goaler" . $TypeText . "StatCareer.SA) - Sum(Goaler" . $TypeText . "StatCareer.GA) AS REAL) / (Sum(Goaler" . $TypeText . "StatCareer.SA))),3) AS SumOfPCT, ROUND((CAST(Sum(Goaler" . $TypeText . "StatCareer.PenalityShotsShots) - Sum(Goaler" . $TypeText . "StatCareer.PenalityShotsGoals) AS REAL) / (Sum(Goaler" . $TypeText . "StatCareer.PenalityShotsShots))),3) AS SumOfPenalityShotsPCT FROM Goaler" . $TypeText . "StatCareer WHERE Playoff = \"" . $Playoff . "\"";

		If($Year > 0){$Query = $Query . " AND YEAR = \"" . $Year . "\"";}
		If($TeamName != ""){$Query = $Query . " AND TeamName = \"" . $TeamName . "\"";}
	
	
		If($Year > 0 OR $LeagueGeneral['PlayOffStarted'] != $Playoff OR $TeamName != ""){
			$Query = $Query . " GROUP BY Goaler" . $TypeText . "StatCareer.Name) AS MainTable LEFT JOIN Goaler" . $TypeText . "Stat ON MainTable.SumOfName = Goaler" . $TypeText . "Stat.Name LEFT JOIN GoalerInfo ON MainTable.SumOfName = GoalerInfo.Name ORDER BY (MainTable.SumOf".$OrderByField.") ";
		}elseif($OrderByField == "GAA"){
			$Query = $Query . " GROUP BY Goaler" . $TypeText . "StatCareer.Name) AS MainTable LEFT JOIN Goaler" . $TypeText . "Stat ON MainTable.SumOfName = Goaler" . $TypeText . "Stat.Name LEFT JOIN GoalerInfo ON MainTable.SumOfName = GoalerInfo.Name Where SumofGA >= " . ($MimimumData *  5) . " ORDER BY Total".$OrderByField." ";
		}elseif($OrderByField == "PCT"){
			$Query = $Query . " GROUP BY Goaler" . $TypeText . "StatCareer.Name) AS MainTable LEFT JOIN Goaler" . $TypeText . "Stat ON MainTable.SumOfName = Goaler" . $TypeText . "Stat.Name LEFT JOIN GoalerInfo ON MainTable.SumOfName = GoalerInfo.Name Where SumOfSA >= " . ($MimimumData *  25) . " ORDER BY Total".$OrderByField." ";
		}elseif($OrderByField == "PenalityShotsPCT"){
			$Query = $Query . " GROUP BY Goaler" . $TypeText . "StatCareer.Name) AS MainTable LEFT JOIN Goaler" . $TypeText . "Stat ON MainTable.SumOfName = Goaler" . $TypeText . "Stat.Name LEFT JOIN GoalerInfo ON MainTable.SumOfName = GoalerInfo.Name Where SumOfPenalityShotsShots >= " . ($MimimumData *  1) . " ORDER BY Total".$OrderByField." ";			
		}else{
			$Query = $Query . " GROUP BY Goaler" . $TypeText . "StatCareer.Name) AS MainTable LEFT JOIN Goaler" . $TypeText . "Stat ON MainTable.SumOfName = Goaler" . $TypeText . "Stat.Name LEFT JOIN GoalerInfo ON MainTable.SumOfName = GoalerInfo.Name ORDER BY (MainTable.SumOf".$OrderByField." + IfNull(Goaler" . $TypeText . "Stat.".$OrderByField.",0)) ";
		}
		
		$Title = $Title  . $DynamicTitleLang['GoaliesStat'] . $TitleType;
		
		If ($ACSQuery == TRUE){
			$Query = $Query . " ASC";
			$Title = $Title . $DynamicTitleLang['InAscendingOrderBy'] . $OrderByFieldText;
		}else{
			$Query = $Query . " DESC";
			$Title = $Title . $DynamicTitleLang['InDecendingOrderBy'] . $OrderByFieldText;
		}
		$Query = $Query . ", (MainTable.SumOfGP + IfNull(Goaler" . $TypeText . "Stat.GP,0)) ASC LIMIT 3";
		If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
		$CareerStatGoalie2 = $CareerStatdb->query($Query);
		
		include "SearchCareerSub.php";	
	}else{
		$CareerStatGoalie2 = Null;
		$Title = $CareeratabaseNotFound;
	}	

	/* OverWrite Title if information is get from PHP GET */
	if($TitleOverwrite <> ""){$Title = $TitleOverwrite;}	
	echo "<title>" . $LeagueName . " - " . $Title . "</title>";
}?>



<?php
$Team = (integer)-1; /* -1 All Team */
$Title = (string)"";
$MimimumData = (integer)10;
$UpdateCareerStatDBV1 = (boolean)false;
$Search = (boolean)False;
If (file_exists($DatabaseFile) == false){
	$LeagueName = $DatabaseNotFound;
	$CareerPlayerStat2 = Null;
	echo "<title>" . $DatabaseNotFound . "</title>";
	$Title = $DatabaseNotFound;
	$TeamName = Null;
}else{
	$TypeText = (string)"Pro";$TitleType = $DynamicTitleLang['Pro'];
	$ACSQuery = (boolean)FALSE;/* The SQL Query must be Ascending Order and not Descending */
	$PosF = (boolean)FALSE; $PosD = (boolean)FALSE;
	$Playoff = (string)"False";
	if(isset($_GET['PosF'])){$PosF= TRUE;}
	if(isset($_GET['PosD'])){$PosD= TRUE;}	
	$MaximumResult = (integer)0;
	$OrderByField = (string)"P";
	$OrderByFieldText = (string)"Points";
	$OrderByInput = (string)"";
	$TitleOverwrite = (string)"";
	$Year = (integer)0;	
	$TeamName = (string)"";
	if(isset($_GET['Farm'])){$TypeText = "Farm";$TitleType = $DynamicTitleLang['Farm'];}
	if(isset($_GET['ACS'])){$ACSQuery= TRUE;}
	if(isset($_GET['Playoff'])){$Playoff="True";$MimimumData=1;}
	if(isset($_GET['Max'])){$MaximumResult = filter_var($_GET['Max'], FILTER_SANITIZE_NUMBER_INT);} 
	if(isset($_GET['Order'])){$OrderByInput  = filter_var($_GET['Order'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH || FILTER_FLAG_NO_ENCODE_QUOTES || FILTER_FLAG_STRIP_BACKTICK);} 
	if(isset($_GET['Title'])){$TitleOverwrite  = filter_var($_GET['Title'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH || FILTER_FLAG_NO_ENCODE_QUOTES || FILTER_FLAG_STRIP_BACKTICK);} 
	if(isset($_GET['Year'])){$Year = filter_var($_GET['Year'], FILTER_SANITIZE_NUMBER_INT);} 	
	if(isset($_GET['TeamName'])){$TeamName = filter_var($_GET['TeamName'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH || FILTER_FLAG_NO_ENCODE_QUOTES || FILTER_FLAG_STRIP_BACKTICK);}	
	$LeagueName = (string)"";

	include "SearchPossibleOrderField.php";
	
	foreach ($PlayersStatPossibleOrderField as $Value) {
		If (strtoupper($Value[0]) == strtoupper($OrderByInput)){
			$OrderByField = $Value[0];
			$OrderByFieldText = $Value[1];
			Break;
		}
	}
	
	$TeamName = STL;
	$Playoff="True";


	$db = new SQLite3($DatabaseFile);
	$Query = "Select Name,PlayOffStarted,PreSeasonSchedule from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	
	If (file_exists($CareerStatDatabaseFile) == true){ /* CareerStat */
		$CareerStatdb = new SQLite3($CareerStatDatabaseFile);
		$CareerStatdb->query("ATTACH DATABASE '".realpath($DatabaseFile)."' AS CurrentDB");
		
		If ($Playoff=="True"){$Title = $PlayersLang['Playoff'] .  " ";}
		$Title = $Title . $DynamicTitleLang['CareerStat'];
		If($PosF == True){$Title = $Title . $TeamLang['Forward'] . " - ";}
		If($PosD == True){$Title = $Title . $TeamLang['Defenseman'] . " - ";}			
		If ($TeamName != ""){$Title = $Title . $TeamName . " - ";}
		If ($Year != ""){$Title = $Title . $Year . " - ";}
		If($MaximumResult == 0){$Title = $Title . $DynamicTitleLang['All'];}else{$Title = $Title . $DynamicTitleLang['Top'] . $MaximumResult . " ";}
		
		$Query="SELECT MainTable.*, Player" . $TypeText . "Stat.*, PlayerInfo.NHLID, PlayerInfo.Country, PlayerInfo.TeamName,ROUND(CAST((MainTable.SumOfG + IfNull(Player" . $TypeText . "Stat.G,0)) AS REAL) / CAST((MainTable.SumOfShots + IfNull(Player" . $TypeText . "Stat.Shots,0)) AS REAL) *100,2) AS TotalShotsPCT,ROUND(CAST((MainTable.SumOfSecondPlay+ IfNull(Player" . $TypeText . "Stat.SecondPlay,0)) AS REAL) / 60 / CAST((MainTable.SumOfGP + IfNull(Player" . $TypeText . "Stat.GP,0)) AS REAL),2) AS TotalAMG, ROUND(CAST((MainTable.SumOfFaceOffWon + IfNull(Player" . $TypeText . "Stat.FaceOffWon,0)) AS REAL) / CAST((MainTable.SumOfFaceOffTotal + IfNull(Player" . $TypeText . "Stat.FaceOffTotal,0)) AS REAL) *100,2) as TotalFaceoffPCT, ROUND(CAST((MainTable.SumOfP+ IfNull(Player" . $TypeText . "Stat.P,0)) AS REAL) / CAST((MainTable.SumOfSecondPlay + IfNull(Player" . $TypeText . "Stat.SecondPlay,0)) AS REAL) * 60 * 20,2) AS TotalP20 FROM (SELECT Name AS SumOfName, UniqueID, Sum(Player" . $TypeText . "StatCareer.GP) AS SumOfGP, Sum(Player" . $TypeText . "StatCareer.Shots) AS SumOfShots, Sum(Player" . $TypeText . "StatCareer.G) AS SumOfG, Sum(Player" . $TypeText . "StatCareer.A) AS SumOfA, Sum(Player" . $TypeText . "StatCareer.P) AS SumOfP, Sum(Player" . $TypeText . "StatCareer.PlusMinus) AS SumOfPlusMinus, Sum(Player" . $TypeText . "StatCareer.Pim) AS SumOfPim, Sum(Player" . $TypeText . "StatCareer.Pim5) AS SumOfPim5, Sum(Player" . $TypeText . "StatCareer.ShotsBlock) AS SumOfShotsBlock, Sum(Player" . $TypeText . "StatCareer.OwnShotsBlock) AS SumOfOwnShotsBlock, Sum(Player" . $TypeText . "StatCareer.OwnShotsMissGoal) AS SumOfOwnShotsMissGoal, Sum(Player" . $TypeText . "StatCareer.Hits) AS SumOfHits, Sum(Player" . $TypeText . "StatCareer.HitsTook) AS SumOfHitsTook, Sum(Player" . $TypeText . "StatCareer.GW) AS SumOfGW, Sum(Player" . $TypeText . "StatCareer.GT) AS SumOfGT, Sum(Player" . $TypeText . "StatCareer.FaceOffWon) AS SumOfFaceOffWon, Sum(Player" . $TypeText . "StatCareer.FaceOffTotal) AS SumOfFaceOffTotal, Sum(Player" . $TypeText . "StatCareer.PenalityShotsScore) AS SumOfPenalityShotsScore, Sum(Player" . $TypeText . "StatCareer.PenalityShotsTotal) AS SumOfPenalityShotsTotal, Sum(Player" . $TypeText . "StatCareer.EmptyNetGoal) AS SumOfEmptyNetGoal, Sum(Player" . $TypeText . "StatCareer.SecondPlay) AS SumOfSecondPlay, Sum(Player" . $TypeText . "StatCareer.HatTrick) AS SumOfHatTrick, Sum(Player" . $TypeText . "StatCareer.PPG) AS SumOfPPG, Sum(Player" . $TypeText . "StatCareer.PPA) AS SumOfPPA, Sum(Player" . $TypeText . "StatCareer.PPP) AS SumOfPPP, Sum(Player" . $TypeText . "StatCareer.PPShots) AS SumOfPPShots, Sum(Player" . $TypeText . "StatCareer.PPSecondPlay) AS SumOfPPSecondPlay, Sum(Player" . $TypeText . "StatCareer.PKG) AS SumOfPKG, Sum(Player" . $TypeText . "StatCareer.PKA) AS SumOfPKA, Sum(Player" . $TypeText . "StatCareer.PKP) AS SumOfPKP, Sum(Player" . $TypeText . "StatCareer.PKShots) AS SumOfPKShots, Sum(Player" . $TypeText . "StatCareer.PKSecondPlay) AS SumOfPKSecondPlay, Sum(Player" . $TypeText . "StatCareer.GiveAway) AS SumOfGiveAway, Sum(Player" . $TypeText . "StatCareer.TakeAway) AS SumOfTakeAway, Sum(Player" . $TypeText . "StatCareer.PuckPossesionTime) AS SumOfPuckPossesionTime, Sum(Player" . $TypeText . "StatCareer.FightW) AS SumOfFightW, Sum(Player" . $TypeText . "StatCareer.FightL) AS SumOfFightL, Sum(Player" . $TypeText . "StatCareer.FightT) AS SumOfFightT, Sum(Player" . $TypeText . "StatCareer.Star1) AS SumOfStar1, Sum(Player" . $TypeText . "StatCareer.Star2) AS SumOfStar2, Sum(Player" . $TypeText . "StatCareer.Star3) AS SumOfStar3, ROUND((CAST(Sum(Player" . $TypeText . "StatCareer.G) AS REAL) / (Sum(Player" . $TypeText . "StatCareer.Shots)))*100,2) AS SumOfShotsPCT, ROUND((CAST(Sum(Player" . $TypeText . "StatCareer.SecondPlay) AS REAL) / 60 / (Sum(Player" . $TypeText . "StatCareer.GP))),2) AS SumOfAMG, ROUND((CAST(Sum(Player" . $TypeText . "StatCareer.FaceOffWon) AS REAL) / (Sum(Player" . $TypeText . "StatCareer.FaceOffTotal)))*100,2) as SumOfFaceoffPCT, ROUND((CAST(Sum(Player" . $TypeText . "StatCareer.P) AS REAL) / (Sum(Player" . $TypeText . "StatCareer.SecondPlay)) * 60 * 20),2) AS SumOfP20 FROM Player" . $TypeText . "StatCareer WHERE Playoff = \"" . $Playoff . "\"";
		
		If($Year > 0){$Query = $Query . " AND Player" . $TypeText . "StatCareer.Year = \"" . $Year . "\"";}
		If($TeamName != ""){$Query = $Query . " AND Player" . $TypeText . "StatCareer.TeamName = \"" . $TeamName . "\"";}
		If($PosF == True){$Query = $Query . " AND (Player" . $TypeText . "StatCareer.PosC = \"True\" OR Player" . $TypeText . "StatCareer.PosLW = \"True\" OR Player" . $TypeText . "StatCareer.PosRW = \"True\")";}		
		If($PosD == True){$Query = $Query . " AND Player" . $TypeText . "StatCareer.PosD = \"True\"";}		
		If($Year > 0 OR $LeagueGeneral['PlayOffStarted'] != $Playoff OR $TeamName != ""){
			$Query = $Query . " GROUP BY Player" . $TypeText . "StatCareer.Name) AS MainTable LEFT JOIN Player" . $TypeText . "Stat ON MainTable.SumOfName = Player" . $TypeText . "Stat.Name LEFT JOIN PlayerInfo ON MainTable.SumOfName = PlayerInfo.Name ORDER BY (MainTable.SumOf".$OrderByField.") ";
		}elseif($OrderByField == "AMG" OR $OrderByField == "P20"){
			$Query = $Query . " GROUP BY Player" . $TypeText . "StatCareer.Name) AS MainTable LEFT JOIN Player" . $TypeText . "Stat ON MainTable.SumOfName = Player" . $TypeText . "Stat.Name LEFT JOIN PlayerInfo ON MainTable.SumOfName = PlayerInfo.Name WHERE SumOfP >= " . ($MimimumData *  5) . " ORDER BY Total".$OrderByField." ";
		}elseif($OrderByField == "FaceoffPCT"){
			$Query = $Query . " GROUP BY Player" . $TypeText . "StatCareer.Name) AS MainTable LEFT JOIN Player" . $TypeText . "Stat ON MainTable.SumOfName = Player" . $TypeText . "Stat.Name LEFT JOIN PlayerInfo ON MainTable.SumOfName = PlayerInfo.Name WHERE SumOfFaceOffTotal >= " . ($MimimumData *  10) . " ORDER BY Total".$OrderByField." ";			
		}elseif($OrderByField == "ShotsPCT"){
			$Query = $Query . " GROUP BY Player" . $TypeText . "StatCareer.Name) AS MainTable LEFT JOIN Player" . $TypeText . "Stat ON MainTable.SumOfName = Player" . $TypeText . "Stat.Name LEFT JOIN PlayerInfo ON MainTable.SumOfName = PlayerInfo.Name WHERE SumOfShots >= " . ($MimimumData *  10) . " ORDER BY Total".$OrderByField." ";			
		}else{
			$Query = $Query . " GROUP BY Player" . $TypeText . "StatCareer.Name) AS MainTable LEFT JOIN Player" . $TypeText . "Stat ON MainTable.SumOfName = Player" . $TypeText . "Stat.Name LEFT JOIN PlayerInfo ON MainTable.SumOfName = PlayerInfo.Name ORDER BY (MainTable.SumOf".$OrderByField." + IfNull(Player" . $TypeText . "Stat.".$OrderByField.",0)) ";
		}
		
		$Title = $Title  . $DynamicTitleLang['PlayersStat'] . $TitleType;	
		
		If ($ACSQuery == TRUE){
			$Query = $Query . " ASC";
			$Title = $Title . $DynamicTitleLang['InAscendingOrderBy'] . $OrderByFieldText;
		}else{
			$Query = $Query . " DESC";
			$Title = $Title . $DynamicTitleLang['InDecendingOrderBy'] . $OrderByFieldText;
		}
		$Query = $Query . ", (MainTable.SumOfGP + IfNull(Player" . $TypeText . "Stat.GP,0)) ASC LIMIT 5";
		If ($MaximumResult  > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
		$CareerPlayerStat2 = $CareerStatdb->query($Query);	

		include "SearchCareerSub.php";		
	}else{
		$CareerPlayerStat2 = Null;
		$Title = $CareeratabaseNotFound;
	}			
	
	/* OverWrite Title if information is get from PHP GET */
	if($TitleOverwrite <> ""){$Title = $TitleOverwrite;}
	echo "<title>" . $LeagueName . " - " . $Title . "</title>";
}?>

<?php
$Active = 1; /* Show Webpage Top Menu */
If (file_exists($NHLDatabaseFile) == false){
	$LeagueName = $NHLDatabaseFile;
	$Transaction = Null;
	$Schedule = Null;
	echo "<style type=\"text/css\">";
	echo ".STHSIndex_Main{;}";
}else{
	$LeagueName = (string)"";
	
	$db = new SQLite3($NHLDatabaseFile);
	$Query = "SELECT field8, field11, field18, field21 , field24 from NHLData";
	$NHLData = $db->query($Query);

}?>
<?php
$Title = (string)"";
$Active = 2; /* Show Webpage Top Menu */
$PlayerInfo = (string)"Name, TeamName";
$GoalerInfo = (string)"Name, TeamName";
If (file_exists($DatabaseFile) == false){
	$LeagueName = $DatabaseNotFound;
	$Schedule = Null;
	$TeamInfo = Null;
	$PlayerInfo = Null;	
	$GoalerInfo = Null;
	$LeagueOutputOption = Null;
	echo "<title>" . $DatabaseNotFound . "</title>";
	$Title = $DatabaseNotFound;
}else{
	$Team = (integer)0; /* 0 All Team */
	$TypeText = (string)"Pro";$TitleType = $DynamicTitleLang['Pro'];
	$LeagueName = (string)"";
	if(isset($_GET['Farm'])){$TypeText = "Farm";$TitleType = $DynamicTitleLang['Farm'];$Active = 3;}
	if(isset($_GET['Team'])){$Team = filter_var($_GET['Team'], FILTER_SANITIZE_NUMBER_INT);}

	$db = new SQLite3($DatabaseFile);
	
	$Query = "SELECT Name, TeamName,Team ,Jersey , NHLID,AgeDate from PlayerInfo";
	$PlayerInfo = $db->query($Query);
	$Query = "SELECT Name, TeamName,Team ,Jersey , NHLID,AgeDate from GoalerInfo";
	$GoalerInfo = $db->query($Query);
	$Query = "SELECT Abbre FROM TeamProInfo WHERE Number = " . $Team;
	$TeamInfo = $db->querySingle($Query,true);
	$Query = "SELECT Name FROM Team" . $TypeText . "Info WHERE Number = " . $Team ;
	$TeamName = $db->querySingle($Query);
	$Title =  $ScheduleLang['TeamTitle'] . $TitleType . " " .  $TeamName;	
	$Query = "Select ScheduleUseDateInsteadofDay,FreeAgentUseDateInsteadofDay,FreeAgentRealDate, ScheduleRealDate, NumberofNewsinPHPHomePage from LeagueOutputOption";
	$LeagueOutputOption = $db->querySingle($Query,true);	
	$Query = "Select Name, DefaultSimulationPerDay, TradeDeadLine, ProScheduleTotalDay, ScheduleNextDay from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	
	If ($Team == 0){
		$Title = $ScheduleLang['ScheduleTitle1'] . $ScheduleLang['ScheduleTitle2'] . " " . $TitleType;
		$Query = "SELECT * FROM Schedule" . $TypeText . " ORDER BY GameNumber";
	}else{
		
		$Query = "SELECT * FROM Schedule" . $TypeText . " WHERE (VisitorTeam = " . $Team . " OR HomeTeam = " . $Team . ") ORDER BY GameNumber";
	}
	$Schedule = $db->query($Query);
	
	$Query = "SELECT * FROM ProRivalryInfo WHERE Team1 = " . $Team . " ORDER BY Team2";
	$RivalryInfo = $db->query($Query);	
	echo "<title>" . $LeagueName . " - " . $Title . "</title>";
}?>

<script type="text/javascript">
/*! jCarouselLite - v1.1 - 2014-09-28  */
!function(a){a.jCarouselLite={version:"1.1"},a.fn.jCarouselLite=function(b){return b=a.extend({},a.fn.jCarouselLite.options,b||{}),this.each(function(){function c(a){return n||(clearTimeout(A),z=a,b.beforeStart&&b.beforeStart.call(this,i()),b.circular?j(a):k(a),m({start:function(){n=!0},done:function(){b.afterEnd&&b.afterEnd.call(this,i()),b.auto&&h(),n=!1}}),b.circular||l()),!1}function d(){if(n=!1,o=b.vertical?"top":"left",p=b.vertical?"height":"width",q=B.find(">ul"),r=q.find(">li"),x=r.size(),w=x<b.visible?x:b.visible,b.circular){var c=r.slice(x-w).clone(),d=r.slice(0,w).clone();q.prepend(c).append(d),b.start+=w}s=a("li",q),y=s.size(),z=b.start}function e(){B.css("visibility","visible"),s.css({overflow:"hidden","float":b.vertical?"none":"left"}),q.css({margin:"0",padding:"0",position:"relative","list-style":"none","z-index":"1"}),B.css({overflow:"hidden",position:"relative","z-index":"2",left:"0px"}),!b.circular&&b.btnPrev&&0==b.start&&a(b.btnPrev).addClass("disabled")}function f(){t=b.vertical?s.outerHeight(!0):s.outerWidth(!0),u=t*y,v=t*w,s.css({width:s.width(),height:s.height()}),q.css(p,u+"px").css(o,-(z*t)),B.css(p,v+"px")}function g(){b.btnPrev&&a(b.btnPrev).click(function(){return c(z-b.scroll)}),b.btnNext&&a(b.btnNext).click(function(){return c(z+b.scroll)}),b.btnGo&&a.each(b.btnGo,function(d,e){a(e).click(function(){return c(b.circular?w+d:d)})}),b.mouseWheel&&B.mousewheel&&B.mousewheel(function(a,d){return c(d>0?z-b.scroll:z+b.scroll)}),b.auto&&h()}function h(){A=setTimeout(function(){c(z+b.scroll)},b.auto)}function i(){return s.slice(z).slice(0,w)}function j(a){var c;a<=b.start-w-1?(c=a+x+b.scroll,q.css(o,-(c*t)+"px"),z=c-b.scroll):a>=y-w+1&&(c=a-x-b.scroll,q.css(o,-(c*t)+"px"),z=c+b.scroll)}function k(a){0>a?z=0:a>y-w&&(z=y-w)}function l(){a(b.btnPrev+","+b.btnNext).removeClass("disabled"),a(z-b.scroll<0&&b.btnPrev||z+b.scroll>y-w&&b.btnNext||[]).addClass("disabled")}function m(c){n=!0,q.animate("left"==o?{left:-(z*t)}:{top:-(z*t)},a.extend({duration:b.speed,easing:b.easing},c))}var n,o,p,q,r,s,t,u,v,w,x,y,z,A,B=a(this);d(),e(),f(),g()})},a.fn.jCarouselLite.options={btnPrev:null,btnNext:null,btnGo:null,mouseWheel:!1,auto:null,speed:200,easing:null,vertical:!1,circular:!0,visible:3,start:0,scroll:1,beforeStart:null,afterEnd:null}}(jQuery);
function toggleDiv(divId) {$("#"+divId).toggle();}
</script>
<?php
$Active = 1; /* Show Webpage Top Menu */
If (file_exists($DatabaseFile) == false){
	$LeagueName = $DatabaseNotFound;
	$Transaction = Null;
	$Schedule = Null;
	echo "<title>" . $DatabaseNotFound . "</title>";
	echo "<style type=\"text/css\">";
	echo ".STHSIndex_Main{;}";
}else{
	$LeagueName = (string)"";
	
	$db = new SQLite3($DatabaseFile);
	
	$Query = "Select Name, ScheduleNextDay, DefaultSimulationPerDay, PointSystemSO, OffSeason, Days73StarPro,Days73StarPro1,Days73StarPro2,Days73StarPro3, Days303StarPro, Days303StarPro1, Days303StarPro2, Days303StarPro3 from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];	
	
	$Query = "SELECT LeagueLog.* FROM LeagueLog WHERE ((LeagueLog.TransactionType = 1) OR (LeagueLog.TransactionType = 2) OR  (LeagueLog.TransactionType = 3) OR  (LeagueLog.TransactionType = 6)) ORDER BY LeagueLog.Number DESC LIMIT 10";
	$Transaction = $db->query($Query);
	
	$Query = "Select ProMinimumGamePlayerLeader,FreeAgentUseDateInsteadofDay,FreeAgentRealDate, ShowFarmScoreinPHPHomePage, NumberofNewsinPHPHomePage, NumberofLatestScoreinPHPHomePage, NumberofNewsinPHPHomePage from LeagueOutputOption";
	$LeagueOutputOption = $db->querySingle($Query,true);		
	
	If (file_exists($NewsDatabaseFile) == false){
		$LeagueNews = Null;
	}else{
		$dbNews = new SQLite3($NewsDatabaseFile);
		$Query = "Select * FROM LeagueNews WHERE Remove = 'False' ORDER BY Time DESC";
		$LeagueNews = $dbNews->query($Query);
	}
		
	If ($LeagueOutputOption['ShowFarmScoreinPHPHomePage'] == 'True'){
		$Query = "SELECT *,'Pro' as Type FROM SchedulePro WHERE Day = " . ($LeagueGeneral['ScheduleNextDay'] - $LeagueGeneral['DefaultSimulationPerDay']) . " UNION SELECT *,'Farm' as Type FROM ScheduleFarm WHERE Day = " . ($LeagueGeneral['ScheduleNextDay'] - $LeagueGeneral['DefaultSimulationPerDay']) . " ORDER BY TYPE DESC, GAMENUMBER";
		$QuerySchedule = "Select ProSchedule.*, 'Pro' AS Type FROM (SELECT TeamProStatVisitor.Last10W AS VLast10W, TeamProStatVisitor.Last10L AS VLast10L, TeamProStatVisitor.Last10T AS VLast10T, TeamProStatVisitor.Last10OTW AS VLast10OTW, TeamProStatVisitor.Last10OTL AS VLast10OTL, TeamProStatVisitor.Last10SOW AS VLast10SOW, TeamProStatVisitor.Last10SOL AS VLast10SOL, TeamProStatVisitor.GP AS VGP, TeamProStatVisitor.W AS VW, TeamProStatVisitor.L AS VL, TeamProStatVisitor.T AS VT, TeamProStatVisitor.OTW AS VOTW, TeamProStatVisitor.OTL AS VOTL, TeamProStatVisitor.SOW AS VSOW, TeamProStatVisitor.SOL AS VSOL, TeamProStatVisitor.Points AS VPoints, TeamProStatVisitor.Streak AS VStreak, TeamProStatHome.Last10W AS HLast10W, TeamProStatHome.Last10L AS HLast10L, TeamProStatHome.Last10T AS HLast10T, TeamProStatHome.Last10OTW AS HLast10OTW, TeamProStatHome.Last10OTL AS HLast10OTL, TeamProStatHome.Last10SOW AS HLast10SOW, TeamProStatHome.Last10SOL AS HLast10SOL, TeamProStatHome.GP AS HGP, TeamProStatHome.W AS HW, TeamProStatHome.L AS HL, TeamProStatHome.T AS HT, TeamProStatHome.OTW AS HOTW, TeamProStatHome.OTL AS HOTL, TeamProStatHome.SOW AS HSOW, TeamProStatHome.SOL AS HSOL, TeamProStatHome.Points AS HPoints, TeamProStatHome.Streak AS HStreak, SchedulePro.* FROM (SchedulePRO LEFT JOIN TeamProStat AS TeamProStatHome ON SchedulePRO.HomeTeam = TeamProStatHome.Number) LEFT JOIN TeamProStat AS TeamProStatVisitor ON SchedulePRO.VisitorTeam = TeamProStatVisitor.Number WHERE DAY >= " . $LeagueGeneral['ScheduleNextDay'] . " AND DAY <= " . ($LeagueGeneral['ScheduleNextDay'] + $LeagueGeneral['DefaultSimulationPerDay'] -1) . ") AS ProSchedule  UNION ALL Select FarmSchedule.*, 'Farm' AS Type FROM (SELECT TeamFarmStatVisitor.Last10W AS VLast10W, TeamFarmStatVisitor.Last10L AS VLast10L, TeamFarmStatVisitor.Last10T AS VLast10T, TeamFarmStatVisitor.Last10OTW AS VLast10OTW, TeamFarmStatVisitor.Last10OTL AS VLast10OTL, TeamFarmStatVisitor.Last10SOW AS VLast10SOW, TeamFarmStatVisitor.Last10SOL AS VLast10SOL, TeamFarmStatVisitor.GP AS VGP, TeamFarmStatVisitor.W AS VW, TeamFarmStatVisitor.L AS VL, TeamFarmStatVisitor.T AS VT, TeamFarmStatVisitor.OTW AS VOTW, TeamFarmStatVisitor.OTL AS VOTL, TeamFarmStatVisitor.SOW AS VSOW, TeamFarmStatVisitor.SOL AS VSOL, TeamFarmStatVisitor.Points AS VPoints, TeamFarmStatVisitor.Streak AS VStreak, TeamFarmStatHome.Last10W AS HLast10W, TeamFarmStatHome.Last10L AS HLast10L, TeamFarmStatHome.Last10T AS HLast10T, TeamFarmStatHome.Last10OTW AS HLast10OTW, TeamFarmStatHome.Last10OTL AS HLast10OTL, TeamFarmStatHome.Last10SOW AS HLast10SOW, TeamFarmStatHome.Last10SOL AS HLast10SOL, TeamFarmStatHome.GP AS HGP, TeamFarmStatHome.W AS HW, TeamFarmStatHome.L AS HL, TeamFarmStatHome.T AS HT, TeamFarmStatHome.OTW AS HOTW, TeamFarmStatHome.OTL AS HOTL, TeamFarmStatHome.SOW AS HSOW, TeamFarmStatHome.SOL AS HSOL, TeamFarmStatHome.Points AS HPoints, TeamFarmStatHome.Streak AS HStreak, ScheduleFarm.* FROM (ScheduleFarm LEFT JOIN TeamFarmStat AS TeamFarmStatHome ON ScheduleFarm.HomeTeam = TeamFarmStatHome.Number) LEFT JOIN TeamFarmStat AS TeamFarmStatVisitor ON ScheduleFarm.VisitorTeam = TeamFarmStatVisitor.Number WHERE DAY >= " . $LeagueGeneral['ScheduleNextDay'] . " AND DAY <= " . ($LeagueGeneral['ScheduleNextDay'] + $LeagueGeneral['DefaultSimulationPerDay'] -1) . ") AS FarmSchedule ORDER BY Day, Type DESC, GameNumber";
	}else{
		$Query = "SELECT * FROM SchedulePro WHERE Day = " . ($LeagueGeneral['ScheduleNextDay'] - $LeagueGeneral['DefaultSimulationPerDay']) . " ORDER BY GameNumber ";
		$QuerySchedule = "SELECT SchedulePro.*, 'Pro' AS Type, TeamProStatVisitor.Last10W AS VLast10W, TeamProStatVisitor.Last10L AS VLast10L, TeamProStatVisitor.Last10T AS VLast10T, TeamProStatVisitor.Last10OTW AS VLast10OTW, TeamProStatVisitor.Last10OTL AS VLast10OTL, TeamProStatVisitor.Last10SOW AS VLast10SOW, TeamProStatVisitor.Last10SOL AS VLast10SOL, TeamProStatVisitor.GP AS VGP, TeamProStatVisitor.W AS VW, TeamProStatVisitor.L AS VL, TeamProStatVisitor.T AS VT, TeamProStatVisitor.OTW AS VOTW, TeamProStatVisitor.OTL AS VOTL, TeamProStatVisitor.SOW AS VSOW, TeamProStatVisitor.SOL AS VSOL, TeamProStatVisitor.Points AS VPoints, TeamProStatVisitor.Streak AS VStreak, TeamProStatHome.Last10W AS HLast10W, TeamProStatHome.Last10L AS HLast10L, TeamProStatHome.Last10T AS HLast10T, TeamProStatHome.Last10OTW AS HLast10OTW, TeamProStatHome.Last10OTL AS HLast10OTL, TeamProStatHome.Last10SOW AS HLast10SOW, TeamProStatHome.Last10SOL AS HLast10SOL, TeamProStatHome.GP AS HGP, TeamProStatHome.W AS HW, TeamProStatHome.L AS HL, TeamProStatHome.T AS HT, TeamProStatHome.OTW AS HOTW, TeamProStatHome.OTL AS HOTL, TeamProStatHome.SOW AS HSOW, TeamProStatHome.SOL AS HSOL, TeamProStatHome.Points AS HPoints, TeamProStatHome.Streak AS HStreak FROM (SchedulePRO LEFT JOIN TeamProStat AS TeamProStatHome ON SchedulePRO.HomeTeam = TeamProStatHome.Number) LEFT JOIN TeamProStat AS TeamProStatVisitor ON SchedulePRO.VisitorTeam = TeamProStatVisitor.Number WHERE DAY >= " . $LeagueGeneral['ScheduleNextDay'] . " AND DAY <= " . ($LeagueGeneral['ScheduleNextDay'] + $LeagueGeneral['DefaultSimulationPerDay'] -1) . " ORDER BY Day, GameNumber";
	}
	
	$LatestScore = $db->query($Query);
	$Schedule = $db->query($QuerySchedule);
	
	echo "<title>" . $LeagueName . " - " . $IndexLang['IndexTitle'] . "</title>";
	echo "<style type=\"text/css\">";
}

Function PrintMainNews($row, $IndexLang, $dbNews){
	/* This Function Print a News */
	$UTC = new DateTimeZone("UTC");
	$ServerTimeZone = new DateTimeZone(date_default_timezone_get());
	$Date = new DateTime($row['Time'], $UTC );
	$Date->setTimezone($ServerTimeZone);
	
	/* The following two lines publish the news */
	echo "<h2 style=display:flex;padding-top:20px;font-size:24px;font-family:Nunito,sans-serif;color:#262525;font-weight:bold;border-top:2px;border-top-style:solid;border-top-color:#ececec><div class=\"topheader2_" . $row['TeamNumber'] ."\"  style=margin-right:150px;font-size:24px;font-family:Nunito,sans-serif;height:50px;width:50px;display:-webkit-inline-box;vertical-align:middle;background-position:center;background-size:75%;background-repeat:no-repeat;border-radius:50%;background-image:url(www.profinhl.cz/images/LogoTeams/" . $row['TeamNumber'] . "." . jpg . ")></div><div style=display:-webkit-inline-box>" . $row['Title'] ."</div></h2><br />";
	echo "<div style=display:-webkit-inline-box><div style=width:112px;font-size:13px;font-weight:normal;color:#999>" .  $IndexLang['By'] . " " . $row['Owner'] . "<br><br>" . $IndexLang['On'] . " " . $Date->format('l jS F Y / g:ia ')  . "</div><div style=margin-left:100px;margin-top:-14px;color:#999>" .$row['Message'] ."</div></div><br>\n"; /* The \n is for a new line in the HTML Code */
	
	/* Get the Number of Reply */
	$NewsReplyCount = Null;
	$Query = "Select Count(Message) as CountMessage FROM LeagueNews WHERE Remove = 'False' AND AnswerNumber = " . $row['Number'] . " ORDER BY Number";
	$NewsReplyCount = $dbNews->querySingle($Query,true);
	
	If ($NewsReplyCount['CountMessage'] > 0 ){ /* If Reply are Found */

		/* Query Reply */
		$NewsReply = Null;
		$Query = "Select * FROM LeagueNews WHERE Remove = 'False' AND AnswerNumber = " . $row['Number'] . " ORDER BY Number";
		$NewsReply = $dbNews->query($Query);
	
		/* Show the Number of News + Create the Link */
		echo "<a style=\"margin-left:212px\" href=\"javascript:toggleDiv('News" . $row['Number'] . "');\">" . $IndexLang['Viewcomments'] . " (" .  $NewsReplyCount['CountMessage'] . ")</a>"; 

		/* Publish all the Comments in Table */
		echo "<table style=\"margin-left:212px\" class=\"STHSIndex_NewsReplyTable\" id=\"News" . $row['Number'] . "\"><tbody>";
		if (empty($NewsReply) == false){
			while ($ReplyRow = $NewsReply ->fetchArray()) { 
			$Date = new DateTime($ReplyRow['Time'], $UTC );
			$Date->setTimezone($ServerTimeZone);
			echo "<tr><td><div class=\"topheader2_" . $ReplyRow['TeamNumber'] ."\"  style=margin-right:4px;font-size:24px;font-family:Nunito,sans-serif;height:35px;width:35px;display:-webkit-inline-box;vertical-align:middle;background-position:center;background-size:75%;background-repeat:no-repeat;border-radius:50%;background-image:url(www.profinhl.cz/images/LogoTeams/" . $ReplyRow['TeamNumber'] . "." . jpg . ")></div><span class=\"STHSIndex_NewsReplyOwner\">" . $ReplyRow['Owner'] . "</span> <span class=\"STHSIndex_NewsReplyTime\">" . $IndexLang['On'] . " " . $Date->format('jS F / g:ia ') . "</span> : " . $ReplyRow['Message'] . "</td></tr>";			
		}}
		echo "<tr><td><a style=\"margin-left:212px\" href=\"NewsEditor.php?ReplyNews=" . $row['Number'] . "\">" . $IndexLang['Comment'] . "</a></td></tr>";
		echo "</tbody></table>";
	
	}else{
		/* No Reply, print link to create the first reply */
		echo "<a style=\"margin-left:212px\" href=\"NewsEditor.php?ReplyNews=" . $row['Number'] . "\">" . $IndexLang['Comment'] . "</a>\n";	
	}
}
?>
.carousel {	border: 1px solid rgb(186, 186, 186); border-image: none; left: -5000px; float: left; visibility: hidden; position: relative}
.carousel > ul > li  {	border: 1px solid rgb(186, 186, 186);}
a.prev {	border-radius: 8px; width: 26px; height: 30px; color: ghostwhite; line-height: 1; font-family: Arial, sans-serif; font-size: 25px; text-decoration: none; float: left; display: block; background-color: rgb(51, 51, 51); -moz-border-radius: 30px; -webkit-border-radius: 30px;}
a.next {	border-radius: 8px; width: 26px; height: 30px; color: ghostwhite; line-height: 1; font-family: Arial, sans-serif; font-size: 25px; text-decoration: none; float: left; display: block; background-color: rgb(51, 51, 51); -moz-border-radius: 30px; -webkit-border-radius: 30px;}
a.prev {	margin: 50px 0px 0px 0px; text-indent: 7px;}
a.next {	margin: 50px 0px 0px 0px; text-indent: 10px;}
a.prev:hover {background-color: rgb(102, 102, 102);}
a.next:hover {background-color: rgb(102, 102, 102);}
.CarouselTable {border-width: 0.5px;border-style: solid;border-collapse: collapse;}
.CarouselTable th {font-weight: bold;}
.CarouselTable td {padding-left: 5px;}
p{max-width:525px;max-height:300px;overflow:hidden}

<?php 
If ($LeagueGeneral['OffSeason'] == "True"){
	echo ".STHSIndex_Score{;}";
	echo ".STHSIndex_Top5Table {;}";
	echo "@media screen and (max-width: 890px) {.STHSIndex_Top5 {;}}";
}else{
	echo ".STHSIndex_Top20FreeAgents {;}";
	echo "@media screen and (max-width: 890px) {.STHSIndex_Score{;}}";
	echo "@media screen and (max-width: 1200px) {.STHSIndex_Top5 {;}}";
}?>
</style>

<?php
$LeagueName = (string)"";
$Title = (string)"";
$Active = 1; /* Show Webpage Top Menu */
If (file_exists($DatabaseFile) == false){
	$LeagueName = $DatabaseNotFound;
	$TodayGame = Null;
	$LeagueGeneral = Null;
	echo "<title>" . $DatabaseNotFound . "</title>";
}else{
	$db = new SQLite3($DatabaseFile);
	
	$Type = (integer)1; /* 0 = All / 1 = Pro / 2 = Farm */
	if(isset($_GET['Type'])){$Type = filter_var($_GET['Type'], FILTER_SANITIZE_NUMBER_INT);} 
	
	$Query = "Select Name, OutputName, DefaultSimulationPerDay, ScheduleNextDay, DatabaseCreationDate, PointSystemSO, Today3StarPro, Today3StarFarm,Select Name, PointSystemW, PointSystemSO, LeagueYearOutput, DatabaseCreationDate, ProInjuryRecoverySpeed, FarmInjuryRecoverySpeed, ProScheduleTotalDay, ScheduleNextDay, DraftPickByYear, DefaultSimulationPerDay, UFAAge, RFAAge,TradeDeadLine from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	
	/* Pro Only, Farm Only or Both  */ 
	if($Type == 1){
		/* Pro Only */
		$Query = "SELECT TodayGame.* FROM TodayGame WHERE TodayGame.GameNumber Like 'Pro%'";
		$Title = $LeagueName . " - " . $TodayGamesLang['TodayGamesTitle'] . $DynamicTitleLang['Pro'];
		$QuerySchedule = "SELECT SchedulePro.*, 'Pro' AS Type, TeamProStatVisitor.Last10W AS VLast10W, TeamProStatVisitor.Last10L AS VLast10L, TeamProStatVisitor.Last10T AS VLast10T, TeamProStatVisitor.Last10OTW AS VLast10OTW, TeamProStatVisitor.Last10OTL AS VLast10OTL, TeamProStatVisitor.Last10SOW AS VLast10SOW, TeamProStatVisitor.Last10SOL AS VLast10SOL, TeamProStatVisitor.GP AS VGP, TeamProStatVisitor.W AS VW, TeamProStatVisitor.L AS VL, TeamProStatVisitor.T AS VT, TeamProStatVisitor.OTW AS VOTW, TeamProStatVisitor.OTL AS VOTL, TeamProStatVisitor.SOW AS VSOW, TeamProStatVisitor.SOL AS VSOL, TeamProStatVisitor.Points AS VPoints, TeamProStatVisitor.Streak AS VStreak, TeamProStatHome.Last10W AS HLast10W, TeamProStatHome.Last10L AS HLast10L, TeamProStatHome.Last10T AS HLast10T, TeamProStatHome.Last10OTW AS HLast10OTW, TeamProStatHome.Last10OTL AS HLast10OTL, TeamProStatHome.Last10SOW AS HLast10SOW, TeamProStatHome.Last10SOL AS HLast10SOL, TeamProStatHome.GP AS HGP, TeamProStatHome.W AS HW, TeamProStatHome.L AS HL, TeamProStatHome.T AS HT, TeamProStatHome.OTW AS HOTW, TeamProStatHome.OTL AS HOTL, TeamProStatHome.SOW AS HSOW, TeamProStatHome.SOL AS HSOL, TeamProStatHome.Points AS HPoints, TeamProStatHome.Streak AS HStreak FROM (SchedulePRO LEFT JOIN TeamProStat AS TeamProStatHome ON SchedulePRO.HomeTeam = TeamProStatHome.Number) LEFT JOIN TeamProStat AS TeamProStatVisitor ON SchedulePRO.VisitorTeam = TeamProStatVisitor.Number WHERE DAY >= " . $LeagueGeneral['ScheduleNextDay'] . " AND DAY <= " . ($LeagueGeneral['ScheduleNextDay'] + $LeagueGeneral['DefaultSimulationPerDay'] -1) . " ORDER BY Day, GameNumber";
	}elseif($Type == 2){
		/* Farm Only */
		$Query = "SELECT TodayGame.* FROM TodayGame WHERE TodayGame.GameNumber Like 'Farm%'";
		$Title = $LeagueName . " - " . $TodayGamesLang['TodayGamesTitle'] .  $DynamicTitleLang['Farm'];
		$QuerySchedule = "SELECT ScheduleFarm.*, 'Farm' AS Type, TeamFarmStatVisitor.Last10W AS VLast10W, TeamFarmStatVisitor.Last10L AS VLast10L, TeamFarmStatVisitor.Last10T AS VLast10T, TeamFarmStatVisitor.Last10OTW AS VLast10OTW, TeamFarmStatVisitor.Last10OTL AS VLast10OTL, TeamFarmStatVisitor.Last10SOW AS VLast10SOW, TeamFarmStatVisitor.Last10SOL AS VLast10SOL, TeamFarmStatVisitor.GP AS VGP, TeamFarmStatVisitor.W AS VW, TeamFarmStatVisitor.L AS VL, TeamFarmStatVisitor.T AS VT, TeamFarmStatVisitor.OTW AS VOTW, TeamFarmStatVisitor.OTL AS VOTL, TeamFarmStatVisitor.SOW AS VSOW, TeamFarmStatVisitor.SOL AS VSOL, TeamFarmStatVisitor.Points AS VPoints, TeamFarmStatVisitor.Streak AS VStreak, TeamFarmStatHome.Last10W AS HLast10W, TeamFarmStatHome.Last10L AS HLast10L, TeamFarmStatHome.Last10T AS HLast10T, TeamFarmStatHome.Last10OTW AS HLast10OTW, TeamFarmStatHome.Last10OTL AS HLast10OTL, TeamFarmStatHome.Last10SOW AS HLast10SOW, TeamFarmStatHome.Last10SOL AS HLast10SOL, TeamFarmStatHome.GP AS HGP, TeamFarmStatHome.W AS HW, TeamFarmStatHome.L AS HL, TeamFarmStatHome.T AS HT, TeamFarmStatHome.OTW AS HOTW, TeamFarmStatHome.OTL AS HOTL, TeamFarmStatHome.SOW AS HSOW, TeamFarmStatHome.SOL AS HSOL, TeamFarmStatHome.Points AS HPoints, TeamFarmStatHome.Streak AS HStreak FROM (ScheduleFarm LEFT JOIN TeamFarmStat AS TeamFarmStatHome ON ScheduleFarm.HomeTeam = TeamFarmStatHome.Number) LEFT JOIN TeamFarmStat AS TeamFarmStatVisitor ON ScheduleFarm.HomeTeam = TeamFarmStatVisitor.Number WHERE DAY >= " . $LeagueGeneral['ScheduleNextDay'] . " AND DAY <= " . ($LeagueGeneral['ScheduleNextDay'] + $LeagueGeneral['DefaultSimulationPerDay'] -1) . " ORDER BY Day, GameNumber";
	}else{
/* Both */
		$Query = "SELECT TodayGame.*, substr(TodayGame.GameNumber,1,3) AS Type FROM TodayGame ORDER BY TYPE DESC, GameNumber";
		$Title = $LeagueName . " - " . $TodayGamesLang['TodayGamesTitle'];
		$QuerySchedule = "Select ProSchedule.*, 'Pro' AS Type FROM (SELECT TeamProStatVisitor.Last10W AS VLast10W, TeamProStatVisitor.Last10L AS VLast10L, TeamProStatVisitor.Last10T AS VLast10T, TeamProStatVisitor.Last10OTW AS VLast10OTW, TeamProStatVisitor.Last10OTL AS VLast10OTL, TeamProStatVisitor.Last10SOW AS VLast10SOW, TeamProStatVisitor.Last10SOL AS VLast10SOL, TeamProStatVisitor.GP AS VGP, TeamProStatVisitor.W AS VW, TeamProStatVisitor.L AS VL, TeamProStatVisitor.T AS VT, TeamProStatVisitor.OTW AS VOTW, TeamProStatVisitor.OTL AS VOTL, TeamProStatVisitor.SOW AS VSOW, TeamProStatVisitor.SOL AS VSOL, TeamProStatVisitor.Points AS VPoints, TeamProStatVisitor.Streak AS VStreak, TeamProStatHome.Last10W AS HLast10W, TeamProStatHome.Last10L AS HLast10L, TeamProStatHome.Last10T AS HLast10T, TeamProStatHome.Last10OTW AS HLast10OTW, TeamProStatHome.Last10OTL AS HLast10OTL, TeamProStatHome.Last10SOW AS HLast10SOW, TeamProStatHome.Last10SOL AS HLast10SOL, TeamProStatHome.GP AS HGP, TeamProStatHome.W AS HW, TeamProStatHome.L AS HL, TeamProStatHome.T AS HT, TeamProStatHome.OTW AS HOTW, TeamProStatHome.OTL AS HOTL, TeamProStatHome.SOW AS HSOW, TeamProStatHome.SOL AS HSOL, TeamProStatHome.Points AS HPoints, TeamProStatHome.Streak AS HStreak, SchedulePro.* FROM (SchedulePRO LEFT JOIN TeamProStat AS TeamProStatHome ON SchedulePRO.HomeTeam = TeamProStatHome.Number) LEFT JOIN TeamProStat AS TeamProStatVisitor ON SchedulePRO.VisitorTeam = TeamProStatVisitor.Number WHERE DAY >= " . $LeagueGeneral['ScheduleNextDay'] . " AND DAY <= " . ($LeagueGeneral['ScheduleNextDay'] + $LeagueGeneral['DefaultSimulationPerDay'] -1) . ") AS ProSchedule  UNION ALL Select FarmSchedule.*, 'Farm' AS Type FROM (SELECT TeamFarmStatVisitor.Last10W AS VLast10W, TeamFarmStatVisitor.Last10L AS VLast10L, TeamFarmStatVisitor.Last10T AS VLast10T, TeamFarmStatVisitor.Last10OTW AS VLast10OTW, TeamFarmStatVisitor.Last10OTL AS VLast10OTL, TeamFarmStatVisitor.Last10SOW AS VLast10SOW, TeamFarmStatVisitor.Last10SOL AS VLast10SOL, TeamFarmStatVisitor.GP AS VGP, TeamFarmStatVisitor.W AS VW, TeamFarmStatVisitor.L AS VL, TeamFarmStatVisitor.T AS VT, TeamFarmStatVisitor.OTW AS VOTW, TeamFarmStatVisitor.OTL AS VOTL, TeamFarmStatVisitor.SOW AS VSOW, TeamFarmStatVisitor.SOL AS VSOL, TeamFarmStatVisitor.Points AS VPoints, TeamFarmStatVisitor.Streak AS VStreak, TeamFarmStatHome.Last10W AS HLast10W, TeamFarmStatHome.Last10L AS HLast10L, TeamFarmStatHome.Last10T AS HLast10T, TeamFarmStatHome.Last10OTW AS HLast10OTW, TeamFarmStatHome.Last10OTL AS HLast10OTL, TeamFarmStatHome.Last10SOW AS HLast10SOW, TeamFarmStatHome.Last10SOL AS HLast10SOL, TeamFarmStatHome.GP AS HGP, TeamFarmStatHome.W AS HW, TeamFarmStatHome.L AS HL, TeamFarmStatHome.T AS HT, TeamFarmStatHome.OTW AS HOTW, TeamFarmStatHome.OTL AS HOTL, TeamFarmStatHome.SOW AS HSOW, TeamFarmStatHome.SOL AS HSOL, TeamFarmStatHome.Points AS HPoints, TeamFarmStatHome.Streak AS HStreak, ScheduleFarm.* FROM (ScheduleFarm LEFT JOIN TeamFarmStat AS TeamFarmStatHome ON ScheduleFarm.HomeTeam = TeamFarmStatHome.Number) LEFT JOIN TeamFarmStat AS TeamFarmStatVisitor ON ScheduleFarm.VisitorTeam = TeamFarmStatVisitor.Number WHERE DAY >= " . $LeagueGeneral['ScheduleNextDay'] . " AND DAY <= " . ($LeagueGeneral['ScheduleNextDay'] + $LeagueGeneral['DefaultSimulationPerDay'] -1) . ") AS FarmSchedule ORDER BY Day, Type DESC, GameNumber";	}
	$TodayGame = $db->query($Query);
	
	$Query = "SELECT Count(TodayGame.GameNumber) AS GameInTable FROM TodayGame";
	$TodayGameCount = $db->querySingle($Query,True);
}


Function PrintGames($Row, $TodayGamesLang){
	echo "<table class=\"STHSTodayGame_GameData\"     style=\"border-collapse:collapse;width:250px;background-repeat:no-repeat;background-position: right;\"><tr style=\"background-repeat:no-repeat;background-position: left\">";
	echo "<tr style=font-size:12px;color:#383732;font-weight:bold>";
	echo "<td><a href=\"" . $Row['Link'] ."\" style=color:#383732;font-weight:bold;margin-top:0px;margin-bottom:0px>" ."FINAL "."</a>";
	if ($Row['Note'] == "End in Overtime"){echo "(OT)";}else if($Row['Note'] == "End in ShootOut"){echo "(SO)";}else {echo "";}
	echo "</td></tr><tr style=line-height:35px><td class=\"STHSTodayGame_TeamName\"><h4 style=\"color:#383732;font-weight:bold;margin:0px;font-size:14px\"><span><img src=\"/images/LogoTeams/Pro/Pro" . $Row['VisitorTeamNumber'] . "." . png . "\" alt=\"" . $TeamAbbre . "\" style=width:25px;vertical-align:middle;padding-right:4px></span>";
	If ($Row['VisitorTeamScore'] < $Row['HomeTeamScore']){echo "<span style=\"color:#ababab;font-weight:bold;margin:0px;font-size:14px\">" . $Row['VisitorTeam'] ."</span>";}else{echo $Row['VisitorTeam'];}	
	echo "</h4></td>";	
	echo "<td class=\"STHSTodayGame_TeamScore\"><h4 style=\"color:#383732;font-weight:bold;padding-right:4px;font-family:Nunito,sans-serif;font-size:24px\">";
	If ($Row['VisitorTeamScore'] < $Row['HomeTeamScore']){echo "<span style=\"color:#ababab;font-weight:bold;margin:0px;font-size:24px\">" . $Row['VisitorTeamScore'] ."</span>";}else{echo $Row['VisitorTeamScore'];}
	echo "</h4></td>";
	echo "</tr><tr style=\"background-repeat:no-repeat;background-position: left;line-height:35px\">";	echo "<td class=\"STHSTodayGame_TeamName\"><h4 style=\"color:#383732;font-weight:bold;margin:0px;font-size:14px\"><span><img src=\"/images/LogoTeams/Pro/Pro" . $Row['HomeTeamNumber'] . "." . png . "\" alt=\"" . $TeamName . "\" style=width:25px;vertical-align:middle;padding-right:4px></span>";
	If ($Row['HomeTeamScore'] < $Row['VisitorTeamScore']){echo "<span style=\"color:#ababab;font-weight:bold;margin:0px;font-size:14px\">" . $Row['HomeTeam'] ."</span>";}else{echo $Row['HomeTeam'];}	
	echo "</h4></td>";	
	echo "<td class=\"STHSTodayGame_TeamScore\"><h4 style=\"color:#383732;font-weight:bold;font-family:Nunito,sans-serif;padding-right:4px;font-size:24px\">";
	If ($Row['HomeTeamScore'] < $Row['VisitorTeamScore']){echo "<span style=\"color:#ababab;font-weight:bold;margin:0px;font-size:24px\">" . $Row['HomeTeamScore'] ."</span>";}else{echo $Row['HomeTeamScore'];}
	echo "</h4></td>";
	echo "</tr></table>\n";
}
?>
<?php
$Title = (string)"";
$Active = 2; /* Show Webpage Top Menu */
If (file_exists($DatabaseFile) == false){
	$LeagueName = $DatabaseNotFound;
	$Schedule = Null;
	$TeamInfo = Null;
	$LeagueOutputOption = Null;
	echo "<title>" . $DatabaseNotFound . "</title>";
	$Title = $DatabaseNotFound;
}else{
	$Team = (integer)0; /* 0 All Team */
	$TypeText = (string)"Pro";$TitleType = $DynamicTitleLang['Pro'];
	$LeagueName = (string)"";
	if(isset($_GET['Farm'])){$TypeText = "Farm";$TitleType = $DynamicTitleLang['Farm'];$Active = 3;}
	if(isset($_GET['Team'])){$Team = filter_var($_GET['Team'], FILTER_SANITIZE_NUMBER_INT);}

	$db = new SQLite3($DatabaseFile);
	
	$Query = "SELECT Abbre FROM TeamProInfo WHERE Number = " . $Team;
	$TeamInfo = $db->querySingle($Query,true);
	$Query = "Select ScheduleUseDateInsteadofDay, ScheduleRealDate,FreeAgentUseDateInsteadofDay,FreeAgentRealDate, NumberofNewsinPHPHomePage from LeagueOutputOption";
	$LeagueOutputOption = $db->querySingle($Query,true);	
	$Query = "Select Name, DefaultSimulationPerDay, TradeDeadLine, ProScheduleTotalDay, ScheduleNextDay from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	
	If ($Team == 0){
		$Title = $ScheduleLang['ScheduleTitle1'] . $ScheduleLang['ScheduleTitle2'] . " " . $TitleType;
		$Query = "SELECT * FROM Schedule" . $TypeText . " ORDER BY GameNumber";
	}else{
		$Query = "SELECT Name FROM Team" . $TypeText . "Info WHERE Number = " . $Team ;
		$TeamName = $db->querySingle($Query);
		$Title =  $ScheduleLang['TeamTitle'] . $TitleType . " " .  $TeamName;
		
		$Query = "SELECT * FROM Schedule" . $TypeText . " WHERE (VisitorTeam = " . $Team . " OR HomeTeam = " . $Team . ") ORDER BY GameNumber";
	}
	$Schedule = $db->query($Query);
	
	$Query = "SELECT * FROM ProRivalryInfo WHERE Team1 = " . $Team . " ORDER BY Team2";
	$RivalryInfo = $db->query($Query);	
	echo "<title>" . $LeagueName . " - " . $Title . "</title>";
}?>
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
	
	$Query = "Select Name,OffSeason, PointSystemW," . $TypeText . "ConferenceName1 AS ConferenceName1," . $TypeText . "ConferenceName2 AS ConferenceName2," . $TypeText . "DivisionName1 AS DivisionName1," . $TypeText . "DivisionName2 AS DivisionName2," . $TypeText . "DivisionName3 AS DivisionName3," . $TypeText . "DivisionName4 AS DivisionName4," . $TypeText . "DivisionName5 AS DivisionName5," . $TypeText . "DivisionName6 AS DivisionName6," . $TypeText . "HowManyPlayOffTeam AS HowManyPlayOffTeam," . $TypeText . "DivisionNewNHLPlayoff  AS DivisionNewNHLPlayoff,PlayOffWinner" . $TypeText . " AS PlayOffWinner, PlayOffStarted,ScheduleNextDay, PlayOffRound from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	$Query = "SELECT * FROM SchedulePro WHERE (VisitorTeam = " . $TeamProInfo['Number'] . " OR HomeTeam = " . $TeamProInfo['Number'] . ") ORDER BY Play, GameNumber ASC LIMIT 1";
	$ScheduleNext = $db->querySingle($Query,true);		
	$Query = "SELECT City FROM Team" . $TypeText . "Info WHERE Number = " . $Team;
	$TeamCity = $db->querySingle($Query,true);

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
echo "<table class=\"STHSPHPStanding_Table\" style=padding:20px;background-color:white;border-collapse:collapse;border:none><thead><tr>";
echo "<th style=\"text-align:center;background-color:#fff;color:#d5d5d5;padding-top:8px;padding-bottom:8px;padding-left:8px;padding-right:8px;text-shadow:none;font-size:13px;border-bottom-width:1px;border-bottom-style:solid;border-bottom-color:#d5d5d5\" data-priority=\"1\" title=\"Team Name\" class=\"STHSW200\">" . $TeamStatLang['TeamName'] ."</th>";
echo "<th style=\"text-align:center;background-color:#fff;color:#d5d5d5;padding-top:8px;padding-bottom:8px;padding-left:8px;padding-right:8px;text-shadow:none;font-size:13px;border-bottom-width:1px;border-bottom-style:solid;border-bottom-color:#d5d5d5\" data-priority=\"1\"title=\"Games Played\" class=\"STHSW30\"  style=text-align:center>GP</th>";
echo "<th style=\"text-align:center;background-color:#fff;color:#d5d5d5;padding-top:8px;padding-bottom:8px;padding-left:8px;padding-right:8px;text-shadow:none;font-size:13px;border-bottom-width:1px;border-bottom-style:solid;border-bottom-color:#d5d5d5\" data-priority=\"1\"title=\"Wins\" class=\"STHSW30\">W</th>";
echo "<th style=\"text-align:center;background-color:#fff;color:#d5d5d5;padding-top:8px;padding-bottom:8px;padding-left:8px;padding-right:8px;text-shadow:none;font-size:13px;border-bottom-width:1px;border-bottom-style:solid;border-bottom-color:#d5d5d5\" data-priority=\"1\"title=\"Loss\" class=\"STHSW30\">L</th>";
echo "<th style=\"text-align:center;background-color:#fff;color:#d5d5d5;padding-top:8px;padding-bottom:8px;padding-left:8px;padding-right:8px;text-shadow:none;font-size:13px;border-bottom-width:1px;border-bottom-style:solid;border-bottom-color:#d5d5d5\" data-priority=\"1\"title=\"Overtime Loss\" class=\"STHSW30\">OTL</th>";
echo "<th style=\"text-align:center;background-color:#fff;color:#d5d5d5;padding-top:8px;padding-bottom:8px;padding-left:8px;padding-right:8px;text-shadow:none;font-size:13px;border-bottom-width:1px;border-bottom-style:solid;border-bottom-color:#d5d5d5\" data-priority=\"1\"title=\"Points\" class=\"STHSW30\">PTS</th>";
echo "</tr></thead><tbody>";
}

Function PrintStandingTable($Standing, $TypeText, $PointSystem, $LinesNumber = 0){
$LoopCount =0;
while ($row = $Standing ->fetchArray()) {
	$LoopCount +=1;
	PrintStandingTableRow($row, $TypeText, $PointSystem, $LoopCount);
	If ($LoopCount > 0 AND $LoopCount == $LinesNumber){echo "<tr class=\"static\"  style=\"background-color:#cc0000;line-height:3px;font-family:Nunito,sans-serif;color:white;\"><td class=\"staticTD\" colspan=\"16\"></td></tr>";}
}
echo "</tbody></table>";
}

Function PrintStandingTableRow($row, $TypeText, $PointSystem, $LoopCount){
	echo "<tr style=line-height:30px;font-size:14px;font-family:Nunito,sans-serif;color:#262525;background-color:white;border:none>";
	echo "<td style=background-color:#fff;border:none;>";
	echo "<span><img src=\"/images/LogoTeams/" . $TypeText . "/" . $row['Number'] . "." . png . "\" alt=\"" . $TeamName . "\" style=width:24px;height:24px;padding-left:8px;vertical-align:middle></span><a href=\"" . $TypeText . "Team.php?Team=" . $row['Number'] . "\" style=font-size:14px;padding-left:8px;font-family:Nunito,sans-serif>";
	if($row['StandingPlayoffTitle']=="E"){echo "";
	} else if($row['StandingPlayoffTitle']=="X"){echo "x-";
	} else if($row['StandingPlayoffTitle']=="Y"){echo "y-";
	} else if($row['StandingPlayoffTitle']=="Z"){echo "z-";
	} else if($row['StandingPlayoffTitle']=="Z" && $row['PowerRanking']==1){
	  echo "p-";}
	echo "<span style=font-weight:bold>" . $row['Name'] . "</a></td>";
	echo "<td style=text-align:center>" . $row['GP'] . "</td>";
	echo "<td style=text-align:center>" . ($row['W'] + $row['OTW'] + $row['SOW']) . "</td>";
	echo "<td style=text-align:center>" . $row['L'] . "</td>";
	echo "<td style=text-align:center>" . ($row['OTL'] + $row['SOL']) . "</td>";	
	echo "<td style=text-align:center;background:#fff><strong>" . $row['Points'] . "</strong></td>";	
	echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
}

?>

<?php
/*
Syntax to call this webpage should be ProTeam.php?Team=2 where only the number change and it's based on the Tean Number Field.
*/
$Active = 3; /* Show Webpage Top Menu */
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
	$SchedulePlay= Null;
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
	$TeamTransaction;
	$TeamLeader;
	echo "<style type=\"text/css\">.STHSPHPTeamStat_Main {;}</style>";
}else{
	$Query = "SELECT count(*) AS count FROM TeamProInfo WHERE Number = " . $Team;
	$Result = $db->querySingle($Query,true);
	If ($Result['count'] == 1){
		$Query = "SELECT * FROM TeamProInfo WHERE Number = " . $Team;
		$TeamInfo = $db->querySingle($Query,true);
		$Query = "SELECT * FROM TeamProInfo WHERE Number = " . $OtherTeam;
		$OtherTeamInfo = $db->querySingle($Query,true);
		$Query = "SELECT Name, City, Conference, Division, Captain, CoachID    FROM TeamFarmInfo WHERE Number = " . $Team;
		$TeamFarmInfo = $db->querySingle($Query,true);			
		$Query = "SELECT * FROM TeamProFinance WHERE Number = " . $Team;
		$TeamFinance = $db->querySingle($Query,true);
		$Query = "SELECT EstimatedSeasonExpense, EstimatedRevenue FROM TeamFarmFinance WHERE Number = " . $Team;
		$TeamFarmFinance = $db->querySingle($Query,true);		
		$Query = "SELECT * FROM TeamProStat WHERE Number = " . $Team;
		$TeamStat = $db->querySingle($Query,true);
		$Query = "SELECT MainTable.* FROM (SELECT TeamProStatVS.TeamVSName AS Name, TeamProStatVS.TeamVSNumber AS Number, TeamProStatVS.GP, TeamProStatVS.W, TeamProStatVS.L, TeamProStatVS.T, TeamProStatVS.OTW, TeamProStatVS.OTL, TeamProStatVS.SOW, TeamProStatVS.SOL, TeamProStatVS.Points, TeamProStatVS.GF, TeamProStatVS.GA, TeamProStatVS.HomeGP, TeamProStatVS.HomeW, TeamProStatVS.HomeL, TeamProStatVS.HomeT, TeamProStatVS.HomeOTW, TeamProStatVS.HomeOTL, TeamProStatVS.HomeSOW, TeamProStatVS.HomeSOL, TeamProStatVS.HomeGF, TeamProStatVS.HomeGA, TeamProStatVS.PPAttemp, TeamProStatVS.PPGoal, TeamProStatVS.PKAttemp, TeamProStatVS.PKGoalGA, TeamProStatVS.PKGoalGF, TeamProStatVS.ShotsFor, TeamProStatVS.ShotsAga, TeamProStatVS.ShotsBlock, TeamProStatVS.ShotsPerPeriod1, TeamProStatVS.ShotsPerPeriod2, TeamProStatVS.ShotsPerPeriod3, TeamProStatVS.ShotsPerPeriod4, TeamProStatVS.GoalsPerPeriod1, TeamProStatVS.GoalsPerPeriod2, TeamProStatVS.GoalsPerPeriod3, TeamProStatVS.GoalsPerPeriod4, TeamProStatVS.PuckTimeInZoneDF, TeamProStatVS.PuckTimeInZoneOF, TeamProStatVS.PuckTimeInZoneNT, TeamProStatVS.PuckTimeControlinZoneDF, TeamProStatVS.PuckTimeControlinZoneOF, TeamProStatVS.PuckTimeControlinZoneNT, TeamProStatVS.Shutouts, TeamProStatVS.TotalGoal, TeamProStatVS.TotalAssist, TeamProStatVS.TotalPoint, TeamProStatVS.Pim, TeamProStatVS.Hits, TeamProStatVS.FaceOffWonDefensifZone, TeamProStatVS.FaceOffTotalDefensifZone, TeamProStatVS.FaceOffWonOffensifZone, TeamProStatVS.FaceOffTotalOffensifZone, TeamProStatVS.FaceOffWonNeutralZone, TeamProStatVS.FaceOffTotalNeutralZone, TeamProStatVS.EmptyNetGoal FROM TeamProStatVS WHERE GP > 0 AND TeamNumber = " . $Team . " UNION ALL SELECT 'Total' as Name, '104' as Number, TeamProStat.GP, TeamProStat.W, TeamProStat.L, TeamProStat.T, TeamProStat.OTW, TeamProStat.OTL, TeamProStat.SOW, TeamProStat.SOL, TeamProStat.Points, TeamProStat.GF, TeamProStat.GA, TeamProStat.HomeGP, TeamProStat.HomeW, TeamProStat.HomeL, TeamProStat.HomeT, TeamProStat.HomeOTW, TeamProStat.HomeOTL, TeamProStat.HomeSOW, TeamProStat.HomeSOL, TeamProStat.HomeGF, TeamProStat.HomeGA,  TeamProStat.PPAttemp, TeamProStat.PPGoal, TeamProStat.PKAttemp, TeamProStat.PKGoalGA, TeamProStat.PKGoalGF, TeamProStat.ShotsFor, TeamProStat.ShotsAga, TeamProStat.ShotsBlock, TeamProStat.ShotsPerPeriod1, TeamProStat.ShotsPerPeriod2, TeamProStat.ShotsPerPeriod3, TeamProStat.ShotsPerPeriod4, TeamProStat.GoalsPerPeriod1, TeamProStat.GoalsPerPeriod2, TeamProStat.GoalsPerPeriod3, TeamProStat.GoalsPerPeriod4, TeamProStat.PuckTimeInZoneDF, TeamProStat.PuckTimeInZoneOF, TeamProStat.PuckTimeInZoneNT, TeamProStat.PuckTimeControlinZoneDF, TeamProStat.PuckTimeControlinZoneOF, TeamProStat.PuckTimeControlinZoneNT, TeamProStat.Shutouts, TeamProStat.TotalGoal, TeamProStat.TotalAssist, TeamProStat.TotalPoint, TeamProStat.Pim, TeamProStat.Hits, TeamProStat.FaceOffWonDefensifZone, TeamProStat.FaceOffTotalDefensifZone, TeamProStat.FaceOffWonOffensifZone, TeamProStat.FaceOffTotalOffensifZone, TeamProStat.FaceOffWonNeutralZone, TeamProStat.FaceOffTotalNeutralZone, TeamProStat.EmptyNetGoal FROM TeamProStat WHERE Number = " . $Team . ") AS MainTable ORDER BY Number";
		$TeamStatSub = $db->query($Query);
		$Query = "SELECT * FROM PlayerInfo WHERE Team = " . $Team . " AND Status1 >= 2 Order By PosD, Overall DESC";
		$PlayerRoster = $db->query($Query);
		$Query = "SELECT MainTable.* FROM (SELECT PlayerInfo.Number, PlayerInfo.Name, PlayerInfo.Team, PlayerInfo.TeamName, PlayerInfo.Age, PlayerInfo.AgeDate, PlayerInfo.Weight, PlayerInfo.Height, PlayerInfo.Contract, PlayerInfo.Rookie, PlayerInfo.NoTrade, PlayerInfo.CanPlayPro, PlayerInfo.CanPlayFarm, PlayerInfo.ForceWaiver, PlayerInfo.ExcludeSalaryCap, PlayerInfo.ProSalaryinFarm, PlayerInfo.SalaryAverage, PlayerInfo.Salary1, PlayerInfo.Salary2, PlayerInfo.Salary3, PlayerInfo.Salary4, PlayerInfo.Salary5, PlayerInfo.Salary6, PlayerInfo.Salary7, PlayerInfo.Salary8, PlayerInfo.Salary9, PlayerInfo.Salary10, PlayerInfo.SalaryRemaining, PlayerInfo.SalaryAverageRemaining, PlayerInfo.SalaryCap, PlayerInfo.SalaryCapRemaining, PlayerInfo.Condition, PlayerInfo.ConditionDecimal,PlayerInfo.Status1, PlayerInfo.URLLink, PlayerInfo.NHLID, PlayerInfo.AvailableForTrade, PlayerInfo.PosC, PlayerInfo.PosLW, PlayerInfo.PosRW, PlayerInfo.PosD, 'False' AS PosG FROM PlayerInfo Where Team =" . $Team . " AND Status1 >= 2 UNION ALL SELECT GoalerInfo.Number, GoalerInfo.Name, GoalerInfo.Team, GoalerInfo.TeamName, GoalerInfo.Age, GoalerInfo.AgeDate,GoalerInfo.Weight, GoalerInfo.Height, GoalerInfo.Contract, GoalerInfo.Rookie, GoalerInfo.NoTrade, GoalerInfo.CanPlayPro, GoalerInfo.CanPlayFarm, GoalerInfo.ForceWaiver, GoalerInfo.ExcludeSalaryCap, GoalerInfo.ProSalaryinFarm, GoalerInfo.SalaryAverage, GoalerInfo.Salary1, GoalerInfo.Salary2, GoalerInfo.Salary3, GoalerInfo.Salary4, GoalerInfo.Salary5, GoalerInfo.Salary6, GoalerInfo.Salary7, GoalerInfo.Salary8, GoalerInfo.Salary9, GoalerInfo.Salary10, GoalerInfo.SalaryRemaining, GoalerInfo.SalaryAverageRemaining, GoalerInfo.SalaryCap, GoalerInfo.SalaryCapRemaining, GoalerInfo.Condition, GoalerInfo.ConditionDecimal, GoalerInfo.Status1, GoalerInfo.URLLink, GoalerInfo.NHLID, GoalerInfo.AvailableForTrade,'False' AS PosC, 'False' AS PosLW, 'False' AS PosRW, 'False' AS PosD, 'True' AS PosG FROM GoalerInfo Where Team =" . $Team . " AND Status1 >= 2) AS MainTable ORDER BY MainTable.Name";
		$PlayerInfo = $db->query($Query);	
		$Query = "SELECT MainTable.* FROM (SELECT PlayerInfo.Number, PlayerInfo.Name, PlayerInfo.Team, PlayerInfo.TeamName, PlayerInfo.Age, PlayerInfo.AgeDate, PlayerInfo.Weight, PlayerInfo.Height, PlayerInfo.Contract, PlayerInfo.Rookie, PlayerInfo.NoTrade, PlayerInfo.CanPlayPro, PlayerInfo.CanPlayFarm, PlayerInfo.ForceWaiver, PlayerInfo.ExcludeSalaryCap, PlayerInfo.ProSalaryinFarm, PlayerInfo.SalaryAverage, PlayerInfo.Salary1, PlayerInfo.Salary2, PlayerInfo.Salary3, PlayerInfo.Salary4, PlayerInfo.Salary5, PlayerInfo.Salary6, PlayerInfo.Salary7, PlayerInfo.Salary8, PlayerInfo.Salary9, PlayerInfo.Salary10, PlayerInfo.SalaryRemaining, PlayerInfo.SalaryAverageRemaining, PlayerInfo.SalaryCap, PlayerInfo.SalaryCapRemaining, PlayerInfo.Condition, PlayerInfo.ConditionDecimal,PlayerInfo.Status1, PlayerInfo.URLLink, PlayerInfo.NHLID, PlayerInfo.AvailableForTrade, PlayerInfo.PosC, PlayerInfo.PosLW, PlayerInfo.PosRW, PlayerInfo.PosD, 'False' AS PosG FROM PlayerInfo Where Team =" . $Team . " AND Status1 >= 2 UNION ALL SELECT GoalerInfo.Number, GoalerInfo.Name, GoalerInfo.Team, GoalerInfo.TeamName, GoalerInfo.Age, GoalerInfo.AgeDate,GoalerInfo.Weight, GoalerInfo.Height, GoalerInfo.Contract, GoalerInfo.Rookie, GoalerInfo.NoTrade, GoalerInfo.CanPlayPro, GoalerInfo.CanPlayFarm, GoalerInfo.ForceWaiver, GoalerInfo.ExcludeSalaryCap, GoalerInfo.ProSalaryinFarm, GoalerInfo.SalaryAverage, GoalerInfo.Salary1, GoalerInfo.Salary2, GoalerInfo.Salary3, GoalerInfo.Salary4, GoalerInfo.Salary5, GoalerInfo.Salary6, GoalerInfo.Salary7, GoalerInfo.Salary8, GoalerInfo.Salary9, GoalerInfo.Salary10, GoalerInfo.SalaryRemaining, GoalerInfo.SalaryAverageRemaining, GoalerInfo.SalaryCap, GoalerInfo.SalaryCapRemaining, GoalerInfo.Condition, GoalerInfo.ConditionDecimal, GoalerInfo.Status1, GoalerInfo.URLLink, GoalerInfo.NHLID, GoalerInfo.AvailableForTrade,'False' AS PosC, 'False' AS PosLW, 'False' AS PosRW, 'False' AS PosD, 'True' AS PosG FROM GoalerInfo Where Team =" . $Team . " AND Status1 >= 2) AS MainTable ORDER BY MainTable.Name";
		$PlayerInfoCapHit = $db->query($Query);	
		$Query = "SELECT MainTable.* FROM (SELECT PlayerInfo.Number, PlayerInfo.Name, PlayerInfo.Team, PlayerInfo.TeamName, PlayerInfo.Age, PlayerInfo.AgeDate, PlayerInfo.Weight, PlayerInfo.Height, PlayerInfo.Contract, PlayerInfo.Rookie, PlayerInfo.NoTrade, PlayerInfo.CanPlayPro, PlayerInfo.CanPlayFarm, PlayerInfo.ForceWaiver, PlayerInfo.ExcludeSalaryCap, PlayerInfo.ProSalaryinFarm, PlayerInfo.SalaryAverage, PlayerInfo.Salary1, PlayerInfo.Salary2, PlayerInfo.Salary3, PlayerInfo.Salary4, PlayerInfo.Salary5, PlayerInfo.Salary6, PlayerInfo.Salary7, PlayerInfo.Salary8, PlayerInfo.Salary9, PlayerInfo.Salary10, PlayerInfo.SalaryRemaining, PlayerInfo.SalaryAverageRemaining, PlayerInfo.SalaryCap, PlayerInfo.SalaryCapRemaining, PlayerInfo.Condition, PlayerInfo.ConditionDecimal,PlayerInfo.Status1, PlayerInfo.URLLink, PlayerInfo.NHLID, PlayerInfo.AvailableForTrade, PlayerInfo.PosC, PlayerInfo.PosLW, PlayerInfo.PosRW, PlayerInfo.PosD, 'False' AS PosG FROM PlayerInfo Where Team =" . $Team . " AND Status1 <= 1 UNION ALL SELECT GoalerInfo.Number, GoalerInfo.Name, GoalerInfo.Team, GoalerInfo.TeamName, GoalerInfo.Age, GoalerInfo.AgeDate,GoalerInfo.Weight, GoalerInfo.Height, GoalerInfo.Contract, GoalerInfo.Rookie, GoalerInfo.NoTrade, GoalerInfo.CanPlayPro, GoalerInfo.CanPlayFarm, GoalerInfo.ForceWaiver, GoalerInfo.ExcludeSalaryCap, GoalerInfo.ProSalaryinFarm, GoalerInfo.SalaryAverage, GoalerInfo.Salary1, GoalerInfo.Salary2, GoalerInfo.Salary3, GoalerInfo.Salary4, GoalerInfo.Salary5, GoalerInfo.Salary6, GoalerInfo.Salary7, GoalerInfo.Salary8, GoalerInfo.Salary9, GoalerInfo.Salary10, GoalerInfo.SalaryRemaining, GoalerInfo.SalaryAverageRemaining, GoalerInfo.SalaryCap, GoalerInfo.SalaryCapRemaining, GoalerInfo.Condition, GoalerInfo.ConditionDecimal, GoalerInfo.Status1, GoalerInfo.URLLink, GoalerInfo.NHLID, GoalerInfo.AvailableForTrade,'False' AS PosC, 'False' AS PosLW, 'False' AS PosRW, 'False' AS PosD, 'True' AS PosG FROM GoalerInfo Where Team =" . $Team . " AND Status1 <= 1) AS MainTable ORDER BY MainTable.Name";
		$PlayerInfoFarmCapHit = $db->query($Query);	
		$Query = "SELECT MainTable.* FROM (SELECT NextYearFreeAgent.Number, NextYearFreeAgent.PlayerName, NextYearFreeAgent.Team, NextYearFreeAgent.Contract, NextYearFreeAgent.NoTrade, NextYearFreeAgent.CanPlayPro, NextYearFreeAgent.CanPlayFarm, NextYearFreeAgent.ForceWaiver, NextYearFreeAgent.ExcludeSalaryCap, NextYearFreeAgent.ProSalaryinFarm, NextYearFreeAgent.Salary FROM NextYearFreeAgent Where Team =" . $Team . " AS MainTable ORDER BY MainTable.PlayerName";
		$PlayerInfoNextCapHit = $db->query($Query);	
		$Query = "SELECT Avg(PlayerInfo.ConditionDecimal) AS AvgOfConditionDecimal, Avg(PlayerInfo.CK) AS AvgOfCK, Avg(PlayerInfo.FG) AS AvgOfFG, Avg(PlayerInfo.DI) AS AvgOfDI, Avg(PlayerInfo.SK) AS AvgOfSK, Avg(PlayerInfo.ST) AS AvgOfST, Avg(PlayerInfo.EN) AS AvgOfEN, Avg(PlayerInfo.DU) AS AvgOfDU, Avg(PlayerInfo.PH) AS AvgOfPH, Avg(PlayerInfo.FO) AS AvgOfFO, Avg(PlayerInfo.PA) AS AvgOfPA, Avg(PlayerInfo.SC) AS AvgOfSC, Avg(PlayerInfo.DF) AS AvgOfDF, Avg(PlayerInfo.PS) AS AvgOfPS, Avg(PlayerInfo.EX) AS AvgOfEX, Avg(PlayerInfo.LD) AS AvgOfLD, Avg(PlayerInfo.PO) AS AvgOfPO, Avg(PlayerInfo.MO) AS AvgOfMO, Avg(PlayerInfo.Overall) AS AvgOfOverall FROM PlayerInfo WHERE Team = " . $Team . " AND Status1 >= 2";
		$PlayerRosterAverage = $db->querySingle($Query,True);	
		$Query = "SELECT GoalerInfo.Team, GoalerInfo.Status1, Avg(GoalerInfo.ConditionDecimal) AS AvgOfConditionDecimal, Avg(GoalerInfo.SK) AS AvgOfSK, Avg(GoalerInfo.DU) AS AvgOfDU, Avg(GoalerInfo.EN) AS AvgOfEN, Avg(GoalerInfo.SZ) AS AvgOfSZ, Avg(GoalerInfo.AG) AS AvgOfAG, Avg(GoalerInfo.RB) AS AvgOfRB, Avg(GoalerInfo.SC) AS AvgOfSC, Avg(GoalerInfo.HS) AS AvgOfHS, Avg(GoalerInfo.RT) AS AvgOfRT, Avg(GoalerInfo.PH) AS AvgOfPH, Avg(GoalerInfo.PS) AS AvgOfPS, Avg(GoalerInfo.EX) AS AvgOfEX, Avg(GoalerInfo.LD) AS AvgOfLD, Avg(GoalerInfo.PO) AS AvgOfPO, Avg(GoalerInfo.MO) AS AvgOfMO, Avg(GoalerInfo.Overall) AS AvgOfOverall FROM GoalerInfo WHERE Team = " . $Team . " AND Status1 >= 2";
		$GoalieRosterAverage = $db->querySingle($Query,True);	
		$Query = "SELECT Count(MainTable.Name) AS CountOfName, Avg(MainTable.Age) AS AvgOfAge, Avg(MainTable.Weight) AS AvgOfWeight, Avg(MainTable.Height) AS AvgOfHeight, Avg(MainTable.Contract) AS AvgOfContract, Avg(MainTable.Salary1) AS AvgOfSalary1 FROM (SELECT PlayerInfo.Name, PlayerInfo.Team, PlayerInfo.Age,PlayerInfo.AgeDate, PlayerInfo.Weight, PlayerInfo.Height, PlayerInfo.Contract, PlayerInfo.Salary1, PlayerInfo.Status1 FROM PlayerInfo WHERE Team = " . $Team . " and Status1 >= 2 UNION ALL SELECT GoalerInfo.Name, GoalerInfo.Team, GoalerInfo.Age, GoalerInfo.Weight, GoalerInfo.Height, GoalerInfo.Contract, GoalerInfo.Salary1, GoalerInfo.Status1 FROM GoalerInfo WHERE Team= " . $Team . " and Status1 >= 2) AS MainTable";
		$PlayerInfoAverage = $db->querySingle($Query,true);
		$Query = "SELECT PlayerProStat.*, PlayerInfo.TeamName, PlayerInfo.TeamName, PlayerInfo.PosC, PlayerInfo.PosLW, PlayerInfo.PosRW,PlayerInfo.Jersey,PlayerInfo.NHLID, PlayerInfo.PosD, ROUND((CAST(PlayerProStat.G AS REAL) / (PlayerProStat.Shots))*100,2) AS ShotsPCT, ROUND((CAST(PlayerProStat.SecondPlay AS REAL) / 60 / (PlayerProStat.GP)),2) AS AMG,ROUND((CAST(PlayerProStat.FaceOffWon AS REAL) / (PlayerProStat.FaceOffTotal))*100,2) as FaceoffPCT,ROUND((CAST(PlayerProStat.P AS REAL) / (PlayerProStat.SecondPlay) * 60 * 20),2) AS P20 FROM PlayerInfo INNER JOIN PlayerProStat ON PlayerInfo.Number = PlayerProStat.Number WHERE ((PlayerInfo.Team=" . $Team . ") AND (PlayerInfo.Status1 >= 2)  AND (PlayerProStat.GP>0)) ORDER BY PlayerProStat.P DESC";
		$PlayerStat = $db->query($Query);
		$Query = "SELECT Count(MainTable.Name) AS CountOfName FROM (SELECT PlayerInfo.Name, PlayerInfo.Team, PlayerInfo.CanPlayPro, PlayerInfo.Status1, PlayerInfo.Contract FROM PlayerInfo WHERE Team = " . $Team . " and Status1 >= 0 and CanPlayPro = 'True' and Contract >=1 UNION ALL SELECT GoalerInfo.Name, GoalerInfo.Team, GoalerInfo.CanPlayPro, GoalerInfo.Status1, GoalerInfo.Contract FROM GoalerInfo WHERE Team= " . $Team . "  AND Status1 >= 0 and CanPlayPro = 'True' and Contract >=1 ) AS MainTable";
		$PlayerInfoAverage = $db->querySingle($Query,true);
		$Query = "SELECT Count(MainTable.Name) AS CountOfName FROM (SELECT PlayerInfo.Name, PlayerInfo.Team, PlayerInfo.CanPlayPro, PlayerInfo.Status1, PlayerInfo.Contract FROM PlayerInfo WHERE Team = " . $Team . " and Status1 >= 2 and CanPlayPro = 'True' and Contract >=1 UNION ALL SELECT GoalerInfo.Name, GoalerInfo.Team, GoalerInfo.CanPlayPro, GoalerInfo.Status1, GoalerInfo.Contract FROM GoalerInfo WHERE Team= " . $Team . "  AND Status1 >= 2 and CanPlayPro = 'True' and Contract >=1 ) AS MainTable";
		$PlayerProInfoAverage = $db->querySingle($Query,true);
		$Query = "SELECT Count(MainTable.Name) AS CountOfName FROM (SELECT Prospects.Name, Prospects.TeamNumber FROM Prospects WHERE TeamNumber = " . $Team . ")  AS MainTable";
		$PlayerProspectInfoAverage = $db->querySingle($Query,true);
		$Query = "SELECT Count(MainTable.Name) AS CountOfName FROM (SELECT PlayerInfo.Name, PlayerInfo.Team, PlayerInfo.CanPlayPro, PlayerInfo.Status1, PlayerInfo.Contract FROM PlayerInfo WHERE Team = " . $Team . " and Status1 >= 0  UNION ALL SELECT GoalerInfo.Name, GoalerInfo.Team, GoalerInfo.CanPlayPro, GoalerInfo.Status1, GoalerInfo.Contract FROM GoalerInfo WHERE Team= " . $Team . "  AND Status1 >= 0 ) AS MainTable";
		$PlayerTotalInfoAverage = $db->querySingle($Query,true);
		$Query = "SELECT Sum(PlayerProStat.GP) AS SumOfGP, Sum(PlayerProStat.Shots) AS SumOfShots, Sum(PlayerProStat.G) AS SumOfG, Sum(PlayerProStat.A) AS SumOfA, Sum(PlayerProStat.P) AS SumOfP, Sum(PlayerProStat.PlusMinus) AS SumOfPlusMinus, Sum(PlayerProStat.Pim) AS SumOfPim, Sum(PlayerProStat.Pim5) AS SumOfPim5, Sum(PlayerProStat.ShotsBlock) AS SumOfShotsBlock, Sum(PlayerProStat.OwnShotsBlock) AS SumOfOwnShotsBlock, Sum(PlayerProStat.OwnShotsMissGoal) AS SumOfOwnShotsMissGoal, Sum(PlayerProStat.Hits) AS SumOfHits, Sum(PlayerProStat.HitsTook) AS SumOfHitsTook, Sum(PlayerProStat.GW) AS SumOfGW, Sum(PlayerProStat.GT) AS SumOfGT, Sum(PlayerProStat.FaceOffWon) AS SumOfFaceOffWon, Sum(PlayerProStat.FaceOffTotal) AS SumOfFaceOffTotal, Sum(PlayerProStat.PenalityShotsScore) AS SumOfPenalityShotsScore, Sum(PlayerProStat.PenalityShotsTotal) AS SumOfPenalityShotsTotal, Sum(PlayerProStat.EmptyNetGoal) AS SumOfEmptyNetGoal, Sum(PlayerProStat.SecondPlay) AS SumOfSecondPlay, Sum(PlayerProStat.HatTrick) AS SumOfHatTrick, Sum(PlayerProStat.PPG) AS SumOfPPG, Sum(PlayerProStat.PPA) AS SumOfPPA, Sum(PlayerProStat.PPP) AS SumOfPPP, Sum(PlayerProStat.PPShots) AS SumOfPPShots, Sum(PlayerProStat.PPSecondPlay) AS SumOfPPSecondPlay, Sum(PlayerProStat.PKG) AS SumOfPKG, Sum(PlayerProStat.PKA) AS SumOfPKA, Sum(PlayerProStat.PKP) AS SumOfPKP, Sum(PlayerProStat.PKShots) AS SumOfPKShots, Sum(PlayerProStat.PKSecondPlay) AS SumOfPKSecondPlay, Sum(PlayerProStat.GiveAway) AS SumOfGiveAway, Sum(PlayerProStat.TakeAway) AS SumOfTakeAway, Sum(PlayerProStat.PuckPossesionTime) AS SumOfPuckPossesionTime, Sum(PlayerProStat.FightW) AS SumOfFightW, Sum(PlayerProStat.FightL) AS SumOfFightL, Sum(PlayerProStat.FightT) AS SumOfFightT, Sum(PlayerProStat.Star1) AS SumOfStar1, Sum(PlayerProStat.Star2) AS SumOfStar2, Sum(PlayerProStat.Star3) AS SumOfStar3, ROUND((CAST(Sum(PlayerProStat.G) AS REAL) / (Sum(PlayerProStat.Shots)))*100,2) AS SumOfShotsPCT, ROUND((CAST(Sum(PlayerProStat.SecondPlay) AS REAL) / 60 / (Sum(PlayerProStat.GP))),2) AS SumOfAMG, ROUND((CAST(Sum(PlayerProStat.FaceOffWon) AS REAL) / (Sum(PlayerProStat.FaceOffTotal)))*100,2) as SumOfFaceoffPCT, ROUND((CAST(Sum(PlayerProStat.P) AS REAL) / (Sum(PlayerProStat.SecondPlay)) * 60 * 20),2) AS SumOfP20 FROM PlayerInfo INNER JOIN PlayerProStat ON PlayerInfo.Number = PlayerProStat.Number WHERE ((PlayerInfo.Team=" . $Team . ") AND (PlayerInfo.Status1 >= 2)  AND (PlayerProStat.GP>0)) ORDER BY PlayerProStat.P DESC";
		$PlayerStatTeam = $db->querySingle($Query,true);	
		$Query = "SELECT GoalerProStat.*, GoalerInfo.TeamName,GoalerInfo.Jersey,GoalerInfo.NHLID, ROUND((CAST(GoalerProStat.GA AS REAL) / (GoalerProStat.SecondPlay / 60))*60,3) AS GAA, ROUND((CAST(GoalerProStat.SA - GoalerProStat.GA AS REAL) / (GoalerProStat.SA)),3) AS PCT, ROUND((CAST(GoalerProStat.PenalityShotsShots - GoalerProStat.PenalityShotsGoals AS REAL) / (GoalerProStat.PenalityShotsShots)),3) AS PenalityShotsPCT FROM GoalerInfo INNER JOIN GoalerProStat ON GoalerInfo.Number = GoalerProStat.Number WHERE ((GoalerInfo.Team)=" . $Team . ") AND ((GoalerInfo.Status1)>=2)  AND ((GoalerProStat.GP)>0) ORDER BY GoalerProStat.W DESC";
		$GoalieStat = $db->query($Query);
		$Query = "SELECT Sum(GoalerProStat.GP) AS SumOfGP, Sum(GoalerProStat.SecondPlay) AS SumOfSecondPlay, Sum(GoalerProStat.W) AS SumOfW, Sum(GoalerProStat.L) AS SumOfL, Sum(GoalerProStat.OTL) AS SumOfOTL, Sum(GoalerProStat.Shootout) AS SumOfShootout, Sum(GoalerProStat.GA) AS SumOfGA, Sum(GoalerProStat.SA) AS SumOfSA, Sum(GoalerProStat.SARebound) AS SumOfSARebound, Sum(GoalerProStat.Pim) AS SumOfPim, Sum(GoalerProStat.A) AS SumOfA, Sum(GoalerProStat.PenalityShotsShots) AS SumOfPenalityShotsShots, Sum(GoalerProStat.PenalityShotsGoals) AS SumOfPenalityShotsGoals, Sum(GoalerProStat.StartGoaler) AS SumOfStartGoaler, Sum(GoalerProStat.BackupGoaler) AS SumOfBackupGoaler, Sum(GoalerProStat.EmptyNetGoal) AS SumOfEmptyNetGoal, Sum(GoalerProStat.Star1) AS SumOfStar1, Sum(GoalerProStat.Star2) AS SumOfStar2, Sum(GoalerProStat.Star3) AS SumOfStar3, ROUND((CAST(Sum(GoalerProStat.GA) AS REAL) / (Sum(GoalerProStat.SecondPlay) / 60))*60,3) AS SumOfGAA, ROUND((CAST(Sum(GoalerProStat.SA) - Sum(GoalerProStat.GA) AS REAL) / (Sum(GoalerProStat.SA))),3) AS SumOfPCT, ROUND((CAST(Sum(GoalerProStat.PenalityShotsShots) - Sum(GoalerProStat.PenalityShotsGoals) AS REAL) / (Sum(GoalerProStat.PenalityShotsShots))),3) AS SumOfPenalityShotsPCT FROM GoalerInfo INNER JOIN GoalerProStat ON GoalerInfo.Number = GoalerProStat.Number WHERE ((GoalerInfo.Team)=" . $Team . ") AND ((GoalerInfo.Status1)>=2)  AND ((GoalerProStat.GP)>0) ORDER BY GoalerProStat.W DESC";
		$GoalieStatTeam = $db->querySingle($Query,true);	
		$Query = "SELECT * FROM GoalerInfo WHERE Team = " . $Team . " AND Status1 >= 2 ORDER By Overall DESC";
		$GoalieRoster = $db->query($Query);
		$Query = "SELECT * FROM SchedulePro WHERE (VisitorTeam = " . $Team . " OR HomeTeam = " . $Team . ") ORDER BY GameNumber ";
		$Schedule= $db->query($Query);
		$Query = "SELECT * FROM SchedulePro WHERE (VisitorTeam = " . $Team . " OR HomeTeam = " . $Team . ") ORDER BY GameNumber DESC LIMIT 1";
		$SchedulePlay= $db->query($Query);	
		$Query = "SELECT * FROM SchedulePro WHERE (HomeTeam = " . $Team . ") ORDER BY Play, GameNumber ASC LIMIT 1";
		$ScheduleNext = $db->querySingle($Query,true);		
		$Query = "SELECT * FROM SchedulePro WHERE (VisitorTeam = " . $Team . " OR HomeTeam = " . $Team . ") AND Play = 'True' ORDER BY GameNumber DESC LIMIT 1";
		$ScheduleLast = $db->querySingle($Query,true);		
		$Query = "SELECT CoachInfo.* FROM CoachInfo INNER JOIN TeamProInfo ON CoachInfo.Number = TeamProInfo.CoachID WHERE (CoachInfo.Team)=" . $Team;
		$CoachInfo = $db->querySingle($Query,true);	
		$Query = "SELECT CoachInfo.* FROM CoachInfo INNER JOIN TeamFarmInfo ON CoachInfo.Number = TeamFarmInfo.CoachID WHERE (CoachInfo.Team)=" . $Team;
		$FarmCoachInfo = $db->querySingle($Query,true);	
		$Query = "SELECT * FROM ProRivalryInfo WHERE Team1 = " . $Team . " ORDER By TEAM2";
		$RivalryInfo = $db->query($Query);		
		$Query = "Select Name, PointSystemW, PointSystemSO, LeagueYearOutput, DatabaseCreationDate, ProInjuryRecoverySpeed, FarmInjuryRecoverySpeed, ProScheduleTotalDay, ScheduleNextDay, DraftPickByYear, DefaultSimulationPerDay, TradeDeadLine from LeagueGeneral";
		$LeagueGeneral = $db->querySingle($Query,true);
		$Query = "Select FarmEnable from LeagueSimulation";
		$LeagueSimulation = $db->querySingle($Query,true);
		$Query = "Select RemoveSalaryCapWhenPlayerUnderCondition, SalaryCapOption, OneWayContractSalaryCapBuriedSalary,  ProSalaryCapValue from LeagueFinance";
		$LeagueFinance = $db->querySingle($Query,true);		
		$Query = "Select ProCustomOTLines from LeagueWebClient";
		$LeagueWebClient = $db->querySingle($Query,true);	
		$Query = "Select OutputSalariesRemaining, OutputSalariesAverageTotal, OutputSalariesAverageRemaining, InchInsteadofCM, LBSInsteadofKG, ScheduleUseDateInsteadofDay, ScheduleRealDate,FreeAgentUseDateInsteadofDay,FreeAgentRealDate, ShowWebClientInDymanicWebsite, NumberofNewsinPHPHomePage from LeagueOutputOption";
		$LeagueOutputOption = $db->querySingle($Query,true);	
		$Query = "SELECT * FROM TeamProLines WHERE TeamNumber = " . $Team . " AND Day = 1";
		$TeamLines = $db->querySingle($Query,true);
		$Query = "SELECT * PlayerProInfo.Name As Number FROM TeamProLinesNumberOnly WHERE TeamNumber = " . $Team . " AND Day = 1";
		$TeamLinesNumberOnly = $db->querySingle($Query,true);
		$Query = "SELECT * FROM TeamLog WHERE TeamNumber = " . $Team ." ORDER BY Number DESC";
		$TeamLog = $db->query($Query);		
		$Query = "SELECT Prospects.*, TeamProInfo.Name As TeamName FROM Prospects LEFT JOIN TeamProInfo ON Prospects.TeamNumber = TeamProInfo.Number WHERE TeamNumber = " . $Team . " ORDER By Name";
		$Prospects = $db->query($Query);
		$Query = "SELECT * FROM DraftPick WHERE TeamNumber = " . $Team . " ORDER By Year, Round";
		$TeamDraftPick = $db->query($Query);
		$Query = "SELECT GoalerInfo.Name, GoalerInfo.Status1, GoalerInfo.Team, GoalerInfo.Injury, GoalerInfo.Condition, GoalerInfo.ConditionDecimal, GoalerInfo.Suspension FROM GoalerInfo WHERE TEAM = " . $Team . " AND (ConditionDecimal < 95 OR Suspension > 0) UNION ALL SELECT PlayerInfo.Name, PlayerInfo.Status1, PlayerInfo.Team, PlayerInfo.Injury, PlayerInfo.Condition, PlayerInfo.ConditionDecimal, PlayerInfo.Suspension FROM PlayerInfo WHERE TEAM = " . $Team . " AND (ConditionDecimal < 95 OR Suspension > 0)";
		$TeamInjurySuspension = $db->query($Query);		
		$Query = "SELECT GoalerInfo.Name, GoalerInfo.Rookie, GoalerInfo.Condition,GoalerInfo.Country,GoalerInfo.StarPower,  GoalerInfo.Age, GoalerInfo.PO, GoalerInfo.Overall, GoalerInfo.SalaryAverage FROM GoalerInfo WHERE (GoalerInfo.Team)=" . $Team . " ORDER By SalaryAverage DESC, Status1 DESC";
		$GoalieDepthChart = $db->query($Query);
		$Query = "SELECT GoalerInfo.Name, GoalerInfo.Rookie, GoalerInfo.Age, GoalerInfo.PO, GoalerInfo.Overall FROM GoalerInfo WHERE (GoalerInfo.Team)=" . $Team . " ORDER By Overall DESC, PO DESC";
		$GLinesDepthChart = $db->query($Query);
		$Query = "SELECT PlayerInfo.Name, PlayerInfo.PosLW, PlayerInfo.PosC, PlayerInfo.PosRW, PlayerInfo.PosD, PlayerInfo.StarPower, PlayerInfo.GameInRowWithOutAPoint, PlayerInfo.GameInRowWithAGoal, PlayerInfo.GameInRowWithAPoint, PlayerInfo.Rookie, PlayerInfo.Condition, PlayerInfo.Age, PlayerInfo.MO, PlayerInfo.Country, PlayerInfo.SalaryAverage FROM PlayerInfo WHERE (PlayerInfo.Team)=" . $Team . " ORDER By SalaryAverage DESC, Status1 DESC";
		$PlayerDepthChart = $db->query($Query);		
		$Query = "SELECT PlayerInfo.Name, PlayerInfo.PosLW, PlayerInfo.PosC, PlayerInfo.PosRW, PlayerInfo.PosD, PlayerInfo.NHLID, PlayerInfo.GameInRowWithOutAPoint, PlayerInfo.GameInRowWithAGoal, PlayerInfo.GameInRowWithAPoint, PlayerInfo.Rookie, PlayerInfo.Age, PlayerInfo.PO, PlayerInfo.Overall FROM PlayerInfo WHERE (PlayerInfo.Team)=" . $Team;
		$LinesDepthChart = $db->query($Query);				
		$Query = "SELECT LeagueLog.* FROM LeagueLog WHERE LeagueLog.Text LIKE \"%" . $TeamInfo['Name'] . "%\" OR LeagueLog.Text LIKE \"%" . $TeamFarmInfo['Name'] . "%\" ORDER BY LeagueLog.Number DESC LIMIT 50";
		$TeamTransaction = $db->query($Query);	
		$Query = "SELECT TeamProInfo.Name, PlayerInfo_1.Name, PlayerInfo_2.Name, PlayerInfo_3.Name FROM ((TeamProInfo LEFT JOIN PlayerInfo AS PlayerInfo_1 ON TeamProInfo.Captain = PlayerInfo_1.Number) LEFT JOIN PlayerInfo AS PlayerInfo_2 ON TeamProInfo.Assistant1 = PlayerInfo_2.Number) LEFT JOIN PlayerInfo AS PlayerInfo_3 ON TeamProInfo.Assistant2 = PlayerInfo_3.Number WHERE TeamProInfo.Number = " . $Team;
		$TeamLeader = $db->querySingle($Query,true);
		
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
			$Query = "SELECT Sum(TeamProStatCareer.GP) AS SumOfGP,Count(TeamProStatCareer.Playoff) AS SumOfPlayoff,Count(TeamProStatCareer.W) AS SumOfCup, Sum(TeamProStatCareer.W) AS SumOfW, Sum(TeamProStatCareer.L) AS SumOfL, Sum(TeamProStatCareer.T) AS SumOfT, Sum(TeamProStatCareer.OTW) AS SumOfOTW, Sum(TeamProStatCareer.OTL) AS SumOfOTL, Sum(TeamProStatCareer.SOW) AS SumOfSOW, Sum(TeamProStatCareer.SOL) AS SumOfSOL, Sum(TeamProStatCareer.Points) AS SumOfPoints, Sum(TeamProStatCareer.GF) AS SumOfGF, Sum(TeamProStatCareer.GA) AS SumOfGA, Sum(TeamProStatCareer.HomeGP) AS SumOfHomeGP, Sum(TeamProStatCareer.HomeW) AS SumOfHomeW, Sum(TeamProStatCareer.HomeL) AS SumOfHomeL, Sum(TeamProStatCareer.HomeT) AS SumOfHomeT, Sum(TeamProStatCareer.HomeOTW) AS SumOfHomeOTW, Sum(TeamProStatCareer.HomeOTL) AS SumOfHomeOTL, Sum(TeamProStatCareer.HomeSOW) AS SumOfHomeSOW, Sum(TeamProStatCareer.HomeSOL) AS SumOfHomeSOL, Sum(TeamProStatCareer.HomeGF) AS SumOfHomeGF, Sum(TeamProStatCareer.HomeGA) AS SumOfHomeGA, Sum(TeamProStatCareer.PPAttemp) AS SumOfPPAttemp, Sum(TeamProStatCareer.PPGoal) AS SumOfPPGoal, Sum(TeamProStatCareer.PKAttemp) AS SumOfPKAttemp, Sum(TeamProStatCareer.PKGoalGA) AS SumOfPKGoalGA, Sum(TeamProStatCareer.PKGoalGF) AS SumOfPKGoalGF, Sum(TeamProStatCareer.ShotsFor) AS SumOfShotsFor, Sum(TeamProStatCareer.ShotsAga) AS SumOfShotsAga, Sum(TeamProStatCareer.ShotsBlock) AS SumOfShotsBlock, Sum(TeamProStatCareer.ShotsPerPeriod1) AS SumOfShotsPerPeriod1, Sum(TeamProStatCareer.ShotsPerPeriod2) AS SumOfShotsPerPeriod2, Sum(TeamProStatCareer.ShotsPerPeriod3) AS SumOfShotsPerPeriod3, Sum(TeamProStatCareer.ShotsPerPeriod4) AS SumOfShotsPerPeriod4, Sum(TeamProStatCareer.GoalsPerPeriod1) AS SumOfGoalsPerPeriod1, Sum(TeamProStatCareer.GoalsPerPeriod2) AS SumOfGoalsPerPeriod2, Sum(TeamProStatCareer.GoalsPerPeriod3) AS SumOfGoalsPerPeriod3, Sum(TeamProStatCareer.GoalsPerPeriod4) AS SumOfGoalsPerPeriod4, Sum(TeamProStatCareer.PuckTimeInZoneDF) AS SumOfPuckTimeInZoneDF, Sum(TeamProStatCareer.PuckTimeInZoneOF) AS SumOfPuckTimeInZoneOF, Sum(TeamProStatCareer.PuckTimeInZoneNT) AS SumOfPuckTimeInZoneNT, Sum(TeamProStatCareer.PuckTimeControlinZoneDF) AS SumOfPuckTimeControlinZoneDF, Sum(TeamProStatCareer.PuckTimeControlinZoneOF) AS SumOfPuckTimeControlinZoneOF, Sum(TeamProStatCareer.PuckTimeControlinZoneNT) AS SumOfPuckTimeControlinZoneNT, Sum(TeamProStatCareer.Shutouts) AS SumOfShutouts, Sum(TeamProStatCareer.TotalGoal) AS SumOfTotalGoal, Sum(TeamProStatCareer.TotalAssist) AS SumOfTotalAssist, Sum(TeamProStatCareer.TotalPoint) AS SumOfTotalPoint, Sum(TeamProStatCareer.Pim) AS SumOfPim, Sum(TeamProStatCareer.Hits) AS SumOfHits, Sum(TeamProStatCareer.FaceOffWonDefensifZone) AS SumOfFaceOffWonDefensifZone, Sum(TeamProStatCareer.FaceOffTotalDefensifZone) AS SumOfFaceOffTotalDefensifZone, Sum(TeamProStatCareer.FaceOffWonOffensifZone) AS SumOfFaceOffWonOffensifZone, Sum(TeamProStatCareer.FaceOffTotalOffensifZone) AS SumOfFaceOffTotalOffensifZone, Sum(TeamProStatCareer.FaceOffWonNeutralZone) AS SumOfFaceOffWonNeutralZone, Sum(TeamProStatCareer.FaceOffTotalNeutralZone) AS SumOfFaceOffTotalNeutralZone, Sum(TeamProStatCareer.EmptyNetGoal) AS SumOfEmptyNetGoal FROM TeamProStatCareer WHERE Playoff = 'True' AND (UniqueID = " . $TeamInfo ['UniqueID'] . " OR Name = '" . $TeamName . "')";
			$TeamCareerSumPlayoffOnly = $CareerStatdb->querySingle($Query,true);	
			$Query = "Select Count(TeamProStatCareer.W) AS SumOfCup FROM TeamProStatCareer WHERE Playoff = 'True' AND W = '16' AND (UniqueID = " . $TeamInfo ['UniqueID'] . " OR Name = '" . $TeamName . "') ORDER BY Year";
			$TeamCareerSumPlayoffCup = $CareerStatdb->querySingle($Query,true);			
			
			$TeamCareerStatFound = true;
		}
		
		if (file_exists($NewsDatabaseFile) == false){
			$LeagueNews = Null;
		}else{
			$dbNews = new SQLite3($NewsDatabaseFile);
			$Query = "Select * FROM LeagueNews WHERE Remove = 'False' AND TeamNumber = " . $TeamInfo['UniqueID'] . " ORDER BY Time DESC";
			$LeagueNews = $dbNews->query($Query);
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
		$Prospects = Null;
		$TeamDraftPick = Null;
		$GoalieDepthChart = Null;
		$PlayerDepthChart = Null;
	$LeagueNews = Null;
		$TeamName = $TeamLang['Teamnotfound'];
		echo "<style type=\"text/css\">.STHSPHPTeamStat_Main {}</style>";
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
#tablesorter_colSelect8P:checked + label {background: #5797d7;  border-color: #555;}
#tablesorter_colSelect8P:checked ~ #tablesorter_ColumnSelector8P {display: block;z-index:10;}
@media screen and (max-width: 992px) {
.STHSWarning {display:block;}
.STHSPHPTeamStatDepthChart_Table td:nth-child(3){}
}@media screen and (max-width: 890px) {
.STHSPHPTeamStatDepthChart_Table td:nth-child(2){}
#STHSPHPTeamStat_SubHeader {;}
}

</style>

<link rel="icon" type="image/svg" href="/images/LogoTeams/Pro/<?php echo $TeamInfo['Number']; ?>.png" /> 

</head>
<body style="background-position:center;background-repeat:no-repeat;background-attachment:fixed;background-color: #eeeeee;background:#fff url(/images/LogoTeams/Background/<?php echo $TeamInfo['Number']; ?>.png) fixed no-repeat top center;">


<td style="width:33%;font-weight:bold;font-size:22px;background-image:url(/images/LogoTeams/Pro/background/&quot;; background-position: center; background-size: cover" . $Row['Team'] . "." . png .  ") style=background-position:center>




<?php include "Menu2.php";?>
<?php include "STHSMenuEnd.php";?>
<div style="width:100%;background: #fff;border-radius: 1px;box-shadow: 0 4px 2px -2px rgba(0,0,0,0.3);font-size: 13px;overflow: visible;height:40px;float:left;z-index:-1">


<div class="STHSPHPTeamStat_Main" style="width: 100%;margin:0  auto; max-width: 1284px;z-index:-1">

<div class=""style="background:transparent;background-position:center;z-index:-1">
<div id="cssmenu" >

<ul class="" style="max-width:1400px;margin:0 auto;display:inline-flex;position:static;width:auto;z-index:-1">
<li class=""    style="text-align:center;padding:0px;font-size:14px;line-height:38px;padding-left:0px;padding-right:0px;">

<li><a href="ProTeam.php?Team=<?php echo $TeamInfo['Number']; ?>"  class="border3_<?php echo $TeamInfo['Number']; ?>" style="padding:0px;margin:12px;color:rgb(100, 100, 100);font-size:13px;text-transform:none">Home</a></li>


<li><a href="ProTeam-roster.php?Team=<?php echo $TeamInfo['Number']; ?>"  class="border3_<?php echo $TeamInfo['Number']; ?>" style="padding:0px;margin:12px;color:rgb(100, 100, 100);font-size:13px;text-transform:none">Roster</a></li>

<li><a href="ProTeam-schedule.php?Team=<?php echo $TeamInfo['Number']; ?>"  class="border3_<?php echo $TeamInfo['Number']; ?>" style="padding:0px;margin:12px;color:rgb(100, 100, 100);font-size:13px;text-transform:none">Schedule</a></li>

<li><a href="ProTeam-stats.php?Team=<?php echo $TeamInfo['Number']; ?>"  class="border3_<?php echo $TeamInfo['Number']; ?>" style="padding:0px;margin:12px;color:rgb(100, 100, 100);font-size:13px;text-transform:none">Statistics</a></li>

<li><a href="ProTeam-trades.php?Team=<?php echo $TeamInfo['Number']; ?>"  class="border3_<?php echo $TeamInfo['Number']; ?>" style="padding:0px;margin:12px;color:rgb(100, 100, 100);font-size:13px;text-transform:none">Trades</a></li>

<li><a href="ProTeam-lines.php?Team=<?php echo $TeamInfo['Number']; ?>"  class="border3_<?php echo $TeamInfo['Number']; ?>" style="padding:0px;margin:12px;color:rgb(100, 100, 100);font-size:13px;text-transform:none">Lines</a></li>

<li><a href="ProTeam-prospects.php?Team=<?php echo $TeamInfo['Number']; ?>"  class="border3_<?php echo $TeamInfo['Number']; ?>" style="padding:0px;margin:12px;color:rgb(100, 100, 100);font-size:13px;text-transform:none">Prospects</a></li>

<li><a href="ProTeam-draftpicks.php?Team=<?php echo $TeamInfo['Number']; ?>"  class="border3_<?php echo $TeamInfo['Number']; ?>" style="padding:0px;margin:12px;color:rgb(100, 100, 100);font-size:13px;text-transform:none">Draft Picks</a></li>

<li><a href="ProTeam-depthchart.php?Team=<?php echo $TeamInfo['Number']; ?>"  class="border3_<?php echo $TeamInfo['Number']; ?>" style="padding:0px;margin:12px;color:rgb(100, 100, 100);font-size:13px;text-transform:none">Depth Chart</a></li>

<li><a href="ProTeam-salary.php?Team=<?php echo $TeamInfo['Number']; ?>"  class="border3_<?php echo $TeamInfo['Number']; ?>" style="padding:0px;margin:12px;color:rgb(100, 100, 100);font-size:13px;text-transform:none">Salary</a></li>

<li><a href="ProTeam-injuries.php?Team=<?php echo $TeamInfo['Number']; ?>"  class="border3_<?php echo $TeamInfo['Number']; ?>" style="padding:0px;margin:12px;color:rgb(100, 100, 100);font-size:13px;text-transform:none">Injuries</a></li>

<li><a href="ProTeam-news.php?Team=<?php echo $TeamInfo['Number']; ?>"  class="border3_<?php echo $TeamInfo['Number']; ?>" style="padding:0px;margin:12px;color:rgb(100, 100, 100);font-size:13px;text-transform:none">History</a></li>

<li><a href="HoF<?php echo $TeamInfo['Number']; ?>.php?Team=<?php echo $TeamInfo['Number']; ?>"  class="border3_<?php echo $TeamInfo['Number']; ?>" style="padding:0px;margin:12px;color:rgb(100, 100, 100);font-size:13px;text-transform:none">Hall of Fame</a></li>

<li><a href="FarmTeam.php?Team=<?php echo $TeamInfo['Number']; ?>"  class="border3_<?php echo $TeamInfo['Number']; ?>" style="padding:0px;margin:12px;color:rgb(100, 100, 100);font-size:13px;text-transform:none">Farm</a></li>



</ul>


<table class="STHSIndex_Main" style="color:#262525;margin-top:0px;padding:25px;z-index:-1"><tr><td class="STHSIndex_Score">
<table class="STHSTableFullW" style="z-index:-1"><tr>
<td class="STHSIndex_NewsTD" id="STHSIndex_Top5" style="background-color:#fff;border-radius:10px;padding-left:0px;padding-right:0px;max-width:660px;box-shadow:0 2px 3px rgba(0,0,0,.1);z-index:-1">




<table style="border-collapse:collapse;" class="STHSIndex_Top5Table">


<div class="color4_<?php echo $TeamInfo['Number']; ?>" style="font-size:16px;padding-left:12px;margin-bottom:15px;padding-top:5px;font-weight:700;color:#000"><?php	echo $TeamInfo['Name'] ?> Cult Players & Legends</div>



<td rowspan="1" style="background:white;border:none;width:20px;text-align:center"><img src=https://cms.nhl.bamgrid.com/images/headshots/current/168x168/8474578@2x.png . "\" style=border-radius:50%;vertical-align:middle;width:120px;border:2px;border-style:solid;border-color:#d2d2d2;margin-bottom:10px;background-color:white\" alt=\"" . $TeamName . "\"  "."<span style=color:#262525;font-size:14px;font-weight:bold><p style=margin-top:-5px><strong>Erik Karlsson</strong></p>
</td>


<td rowspan="1" style="background:white;border:none;width:20px;text-align:center"><img src=https://cms.nhl.bamgrid.com/images/headshots/current/168x168/8470612@2x.png . "\" style=border-radius:50%;vertical-align:middle;width:120px;border:2px;border-style:solid;border-color:#d2d2d2;margin-bottom:10px;background-color:white\" alt=\"" . $TeamName . "\"  "."<span style=color:#262525;font-size:14px;font-weight:bold><p style=margin-top:-5px><strong>Ryan Getzlaf</strong></p>
</td>

<td rowspan="1" style="background:white;border:none;width:20px;text-align:center"><img src=https://cms.nhl.bamgrid.com/images/headshots/current/168x168/8468685@2x.png . "\" style=border-radius:50%;vertical-align:middle;width:120px;border:2px;border-style:solid;border-color:#d2d2d2;margin-bottom:10px;background-color:white\" alt=\"" . $TeamName . "\"  "."<span style=color:#262525;font-size:14px;font-weight:bold><p style=margin-top:-5px><strong>Henrik Lundqvist</strong></p>
</td>



<td rowspan="1" style="background:white;border:none;width:20px;text-align:center"><img src=https://cms.nhl.bamgrid.com/images/headshots/current/168x168/8475765@2x.png . "\" style=border-radius:50%;vertical-align:middle;width:120px;border:2px;border-style:solid;border-color:#d2d2d2;margin-bottom:10px;background-color:white\" alt=\"" . $TeamName . "\"  "."<span style=color:#262525;font-size:14px;font-weight:bold><p style=margin-top:-5px><strong>Vladimir Tarasenko</strong></p>
</td>

<td rowspan="1" style="background:white;border:none;width:20px;text-align:center"><img src=https://cms.nhl.bamgrid.com/images/headshots/current/168x168/8474161@2x.png . "\" style=border-radius:50%;vertical-align:middle;width:120px;border:2px;border-style:solid;border-color:#d2d2d2;margin-bottom:10px;background-color:white\" alt=\"" . $TeamName . "\"  "."<span style=color:#262525;font-size:14px;font-weight:bold><p style=margin-top:-5px><strong>Jakub Voracek</strong></p>
</td></tr>
</table>



<div style="padding-top:25px;border-radius: 10px;">


<div class="color4_<?php echo $TeamInfo['Number']; ?>" style="font-size:16px;padding-left:12px;margin-bottom:15px;padding-top:5px;font-weight:700;color:#000"><?php	echo $TeamInfo['Name'] ?> Stat Leaders (Regular Season)</div>

<table class="tablesorter STHSPHPAllPlayerStat_Table"><thead><tr>
<th style="text-align:left;text-align:center;font-weight:bold;border-width:0px;background-color:#2a2a2a;color:#fff" data-priority="3" title="Order Number" class="STHSW10 sorter-false">#</th>

<th style="text-align:left;text-align:center;font-weight:bold;border-width:0px;background-color:#2a2a2a;color:#fff;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px;text-shadow:none;font-size:12px;text-transform:uppercase"data-priority="critical" title="Player Name" class="STHSW140Min"><?php echo $PlayersLang['PlayerName'];?></th>
<th style="text-align:center;text-align:center;font-weight:bold;border-width:0px;background-color:#2a2a2a;color:#fff;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px;text-shadow:none;font-size:12px;text-transform:uppercase"data-priority="critical" title="Games Played" class="STHSW25">GP</th>
<th style="text-align:center;text-align:center;font-weight:bold;border-width:0px;background-color:#2a2a2a;color:#fff;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px;text-shadow:none;font-size:12px;text-transform:uppercase"data-priority="critical" title="Goals" class="STHSW25">G</th>
<th style="text-align:center;text-align:center;font-weight:bold;border-width:0px;background-color:#2a2a2a;color:#fff;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px;text-shadow:none;font-size:12px;text-transform:uppercase"data-priority="critical" title="Assists" class="STHSW25">A</th>
<th style="text-align:center;text-align:center;font-weight:bold;border-width:0px;background-color:#2a2a2a;color:#fff;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px;text-shadow:none;font-size:12px;text-transform:uppercase"data-priority="critical" title="Points" class="STHSW25">P</th>
<th style="text-align:center;text-align:center;font-weight:bold;border-width:0px;background-color:#2a2a2a;color:#fff;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px;text-shadow:none;font-size:12px;text-transform:uppercase"data-priority="critical" title="Plus/Minus" class="STHSW25">+/-</th>
<th style="text-align:center;text-align:center;font-weight:bold;border-width:0px;background-color:#2a2a2a;color:#fff;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px;text-shadow:none;font-size:12px;text-transform:uppercase"data-priority="critical" title="Penalty Minutes" class="STHSW25">PIM</th>
<th style="text-align:center;text-align:center;font-weight:bold;border-width:0px;background-color:#2a2a2a;color:#fff;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px;text-shadow:none;font-size:12px;text-transform:uppercase"data-priority="2" title="Hits" class="STHSW25">HIT</th>
<th style="text-align:center;text-align:center;font-weight:bold;border-width:0px;background-color:#2a2a2a;color:#fff;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px;text-shadow:none;font-size:12px;text-transform:uppercase"data-priority="2" title="Shots" class="STHSW25">SHT</th>
<th style="text-align:center;text-align:center;font-weight:bold;border-width:0px;background-color:#2a2a2a;color:#fff;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px;text-shadow:none;font-size:12px;text-transform:uppercase"data-priority="3" title="Shooting Percentage" class="STHSW55">SHT%</th>
<th style="text-align:center;text-align:center;font-weight:bold;border-width:0px;background-color:#2a2a2a;color:#fff;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px;text-shadow:none;font-size:12px;text-transform:uppercase"data-priority="3" title="Shots Blocked" class="STHSW25">SB</th>
<th style="text-align:center;text-align:center;font-weight:bold;border-width:0px;background-color:#2a2a2a;color:#fff;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px;text-shadow:none;font-size:12px;text-transform:uppercase"data-priority="4" title="Average Minutes Played per Game" class="STHSW25">AMG</th>

<th style="text-align:center;text-align:center;font-weight:bold;border-width:0px;background-color:#2a2a2a;color:#fff;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px;text-shadow:none;font-size:12px;text-transform:uppercase"data-priority="4" title="Power Play Points" class="STHSW25">PPP</th>
<th style="text-align:center;text-align:center;font-weight:bold;border-width:0px;background-color:#2a2a2a;color:#fff;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px;text-shadow:none;font-size:12px;text-transform:uppercase"data-priority="5" title="Short Handed Goals" class="STHSW25">PKG</th>

<th style="text-align:center;text-align:center;font-weight:bold;border-width:0px;background-color:#2a2a2a;color:#fff;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px;text-shadow:none;font-size:12px;text-transform:uppercase"class="columnSelector-false STHSW25" data-priority="6" title="Face offs Percentage">FO%</th>
<th style="text-align:center;text-align:center;font-weight:bold;border-width:0px;background-color:#2a2a2a;color:#fff;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px;text-shadow:none;font-size:12px;text-transform:uppercase"class="columnSelector-false STHSW25" data-priority="6" title="Face offs Taken">FOT</th>
</tr></thead><tbody>
<?php 
$Order = 0;
if (empty($CareerPlayerStat) == false){while ($Row = $CareerPlayerStat ->fetchArray()) {
	$Order +=1;
	echo "<tr><td>" . $Order ."</td>";
	If ($Row['Number'] > 0){
		echo "<td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . "</a></td>";
	}else{
		echo "<td><a href=\"CareerStatPlayerReport.php?Player=" . $Row['SumOfName'] . "\">" . $Row['SumOfName'] . "*</a></td>";
	}	
	If ($Year == 0 AND $LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False" AND $TeamName == ""){
		echo "<td>" . ($Row['SumOfGP'] + $Row['GP']) . "</td>";
		echo "<td>" . ($Row['SumOfG'] + $Row['G']) . "</td>";
		echo "<td>" . ($Row['SumOfA'] + $Row['A']) . "</td>";
		echo "<td>" . ($Row['SumOfP'] + $Row['P']) . "</td>";
		echo "<td>" . ($Row['SumOfPlusMinus'] + $Row['PlusMinus']) . "</td>";
		echo "<td>" . ($Row['SumOfPim'] + $Row['Pim']) . "</td>";
		echo "<td>" . ($Row['SumOfHits'] + $Row['Hits']) . "</td>";	
		echo "<td>" . ($Row['SumOfShots'] + $Row['Shots']) . "</td>";
		echo "<td>" . number_Format($Row['TotalShotsPCT'],2) . "%</td>";		
		echo "<td>" . ($Row['SumOfShotsBlock'] + $Row['ShotsBlock']) . "</td>";	
		echo "<td>" . number_Format($Row['TotalAMG'],2) . "</td>";	
		echo "<td>" . ($Row['SumOfPPP'] + $Row['PPP']) . "</td>";
		echo "<td>" . ($Row['SumOfPKG'] + $Row['PKG']) . "</td>";
		echo "<td>" . number_Format($Row['TotalFaceoffPCT'],2) . "%</td>";
		echo "<td>" . ($Row['SumOfFaceOffTotal'] + $Row['FaceOffTotal']) . "</td>";
		echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
	}else{
		echo "<td>" . $Row['SumOfGP'] . "</td>";
		echo "<td>" . $Row['SumOfG'] . "</td>";
		echo "<td>" . $Row['SumOfA'] . "</td>";
		echo "<td>" . $Row['SumOfP'] . "</td>";
		echo "<td>" . $Row['SumOfPlusMinus'] . "</td>";
		echo "<td>" . $Row['SumOfPim'] . "</td>";
		echo "<td>" . $Row['SumOfHits'] . "</td>";	
		echo "<td>" . $Row['SumOfShots'] . "</td>";
		echo "<td>" . number_Format($Row['SumOfShotsPCT'],2) . "%</td>";		
		echo "<td>" . $Row['SumOfShotsBlock'] . "</td>";	
		echo "<td>" . number_Format($Row['SumOfAMG'],2) . "</td>";		
		echo "<td>" . $Row['SumOfPPP'] . "</td>";
		echo "<td>" . $Row['SumOfPKG'] . "</td>";
		echo "<td>" . number_Format($Row['SumOfFaceoffPCT'],2) . "%</td>";	
		echo "<td>" . $Row['SumOfFaceOffTotal'] . "</td>";
		echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
	}
}}
?>
</div>
</div>


</td>

</tr>
</table>

<div style="padding-top:25px;border-radius: 10px;">


<div class="color4_<?php echo $TeamInfo['Number']; ?>" style="font-size:16px;padding-left:12px;margin-bottom:-10px;padding-top:5px;font-weight:700;color:#000"><?php	echo $TeamInfo['Name'] ?> Stat Leaders (Play-Off)</div>

<table class="tablesorter STHSPHPAllPlayerStat_Table"><thead><tr>
<table class="tablesorter STHSPHPAllPlayerStat_Table"><thead><tr>
<th style="text-align:left;text-align:center;font-weight:bold;border-width:0px;background-color:#2a2a2a;color:#fff" data-priority="3" title="Order Number" class="STHSW10 sorter-false">#</th>

<th style="text-align:left;text-align:center;font-weight:bold;border-width:0px;background-color:#2a2a2a;color:#fff;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px;text-shadow:none;font-size:12px;text-transform:uppercase"data-priority="critical" title="Player Name" class="STHSW140Min"><?php echo $PlayersLang['PlayerName'];?></th>
<th style="text-align:center;text-align:center;font-weight:bold;border-width:0px;background-color:#2a2a2a;color:#fff;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px;text-shadow:none;font-size:12px;text-transform:uppercase"data-priority="critical" title="Games Played" class="STHSW25">GP</th>
<th style="text-align:center;text-align:center;font-weight:bold;border-width:0px;background-color:#2a2a2a;color:#fff;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px;text-shadow:none;font-size:12px;text-transform:uppercase"data-priority="critical" title="Goals" class="STHSW25">G</th>
<th style="text-align:center;text-align:center;font-weight:bold;border-width:0px;background-color:#2a2a2a;color:#fff;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px;text-shadow:none;font-size:12px;text-transform:uppercase"data-priority="critical" title="Assists" class="STHSW25">A</th>
<th style="text-align:center;text-align:center;font-weight:bold;border-width:0px;background-color:#2a2a2a;color:#fff;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px;text-shadow:none;font-size:12px;text-transform:uppercase"data-priority="critical" title="Points" class="STHSW25">P</th>
<th style="text-align:center;text-align:center;font-weight:bold;border-width:0px;background-color:#2a2a2a;color:#fff;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px;text-shadow:none;font-size:12px;text-transform:uppercase"data-priority="critical" title="Plus/Minus" class="STHSW25">+/-</th>
<th style="text-align:center;text-align:center;font-weight:bold;border-width:0px;background-color:#2a2a2a;color:#fff;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px;text-shadow:none;font-size:12px;text-transform:uppercase"data-priority="critical" title="Penalty Minutes" class="STHSW25">PIM</th>
<th style="text-align:center;text-align:center;font-weight:bold;border-width:0px;background-color:#2a2a2a;color:#fff;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px;text-shadow:none;font-size:12px;text-transform:uppercase"data-priority="2" title="Hits" class="STHSW25">HIT</th>
<th style="text-align:center;text-align:center;font-weight:bold;border-width:0px;background-color:#2a2a2a;color:#fff;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px;text-shadow:none;font-size:12px;text-transform:uppercase"data-priority="2" title="Shots" class="STHSW25">SHT</th>
<th style="text-align:center;text-align:center;font-weight:bold;border-width:0px;background-color:#2a2a2a;color:#fff;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px;text-shadow:none;font-size:12px;text-transform:uppercase"data-priority="3" title="Shooting Percentage" class="STHSW55">SHT%</th>
<th style="text-align:center;text-align:center;font-weight:bold;border-width:0px;background-color:#2a2a2a;color:#fff;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px;text-shadow:none;font-size:12px;text-transform:uppercase"data-priority="3" title="Shots Blocked" class="STHSW25">SB</th>
<th style="text-align:center;text-align:center;font-weight:bold;border-width:0px;background-color:#2a2a2a;color:#fff;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px;text-shadow:none;font-size:12px;text-transform:uppercase"data-priority="4" title="Average Minutes Played per Game" class="STHSW25">AMG</th>

<th style="text-align:center;text-align:center;font-weight:bold;border-width:0px;background-color:#2a2a2a;color:#fff;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px;text-shadow:none;font-size:12px;text-transform:uppercase"data-priority="4" title="Power Play Points" class="STHSW25">PPP</th>
<th style="text-align:center;text-align:center;font-weight:bold;border-width:0px;background-color:#2a2a2a;color:#fff;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px;text-shadow:none;font-size:12px;text-transform:uppercase"data-priority="5" title="Short Handed Goals" class="STHSW25">PKG</th>

<th style="text-align:center;text-align:center;font-weight:bold;border-width:0px;background-color:#2a2a2a;color:#fff;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px;text-shadow:none;font-size:12px;text-transform:uppercase"class="columnSelector-false STHSW25" data-priority="6" title="Face offs Percentage">FO%</th>
<th style="text-align:center;text-align:center;font-weight:bold;border-width:0px;background-color:#2a2a2a;color:#fff;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px;text-shadow:none;font-size:12px;text-transform:uppercase"class="columnSelector-false STHSW25" data-priority="6" title="Face offs Taken">FOT</th>
</tr></thead><tbody>
<?php 
$Order = 0;
if (empty($CareerPlayerStat2) == false){while ($Row = $CareerPlayerStat2 ->fetchArray()) {
	$Order +=1;
	echo "<tr><td>" . $Order ."</td>";
	If ($Row['Number'] > 0){
		echo "<td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . "</a></td>";
	}else{
		echo "<td><a href=\"CareerStatPlayerReport.php?Player=" . $Row['SumOfName'] . "\">" . $Row['SumOfName'] . "*</a></td>";
	}	
	If ($Year == 0 AND $LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False" AND $TeamName == ""){
		echo "<td>" . ($Row['SumOfGP'] + $Row['GP']) . "</td>";
		echo "<td>" . ($Row['SumOfG'] + $Row['G']) . "</td>";
		echo "<td>" . ($Row['SumOfA'] + $Row['A']) . "</td>";
		echo "<td>" . ($Row['SumOfP'] + $Row['P']) . "</td>";
		echo "<td>" . ($Row['SumOfPlusMinus'] + $Row['PlusMinus']) . "</td>";
		echo "<td>" . ($Row['SumOfPim'] + $Row['Pim']) . "</td>";
		echo "<td>" . ($Row['SumOfHits'] + $Row['Hits']) . "</td>";	
		echo "<td>" . ($Row['SumOfShots'] + $Row['Shots']) . "</td>";
		echo "<td>" . number_Format($Row['TotalShotsPCT'],2) . "%</td>";		
		echo "<td>" . ($Row['SumOfShotsBlock'] + $Row['ShotsBlock']) . "</td>";	
		echo "<td>" . number_Format($Row['TotalAMG'],2) . "</td>";	
		echo "<td>" . ($Row['SumOfPPP'] + $Row['PPP']) . "</td>";
		echo "<td>" . ($Row['SumOfPKG'] + $Row['PKG']) . "</td>";
		echo "<td>" . number_Format($Row['TotalFaceoffPCT'],2) . "%</td>";
		echo "<td>" . ($Row['SumOfFaceOffTotal'] + $Row['FaceOffTotal']) . "</td>";
		echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
	}else{
		echo "<td>" . $Row['SumOfGP'] . "</td>";
		echo "<td>" . $Row['SumOfG'] . "</td>";
		echo "<td>" . $Row['SumOfA'] . "</td>";
		echo "<td>" . $Row['SumOfP'] . "</td>";
		echo "<td>" . $Row['SumOfPlusMinus'] . "</td>";
		echo "<td>" . $Row['SumOfPim'] . "</td>";
		echo "<td>" . $Row['SumOfHits'] . "</td>";	
		echo "<td>" . $Row['SumOfShots'] . "</td>";
		echo "<td>" . number_Format($Row['SumOfShotsPCT'],2) . "%</td>";		
		echo "<td>" . $Row['SumOfShotsBlock'] . "</td>";	
		echo "<td>" . number_Format($Row['SumOfAMG'],2) . "</td>";		
		echo "<td>" . $Row['SumOfPPP'] . "</td>";
		echo "<td>" . $Row['SumOfPKG'] . "</td>";
		echo "<td>" . number_Format($Row['SumOfFaceoffPCT'],2) . "%</td>";	
		echo "<td>" . $Row['SumOfFaceOffTotal'] . "</td>";
		echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
	}
}}
?>
</div>
</div>


</td>

</tr>
</table>

<div style="padding-top:25px;border-radius: 10px;">

<div class="color4_<?php echo $TeamInfo['Number']; ?>" style="font-size:16px;padding-left:12px;margin-bottom:15px;padding-top:5px;font-weight:700;color:#000"><?php	echo $TeamInfo['Name'] ?> Goalies Stat Leaders (Regular Season)</div>

<table class="tablesorter STHSPHPAllGoalieStat_Table"><thead><tr>
<th style="background-color:#2a2a2a;color:#fff;text-shadow:none;font-weight:bold;text-transform:uppercase;font-size:12px;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px" data-priority="3" title="Order Number" class="STHSW10 sorter-false">#</th>
<th style="background-color:#2a2a2a;color:#fff;text-shadow:none;font-weight:bold;text-transform:uppercase;font-size:12px;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px data-priority="critical" title="Goalie Name" class="STHSW140Min"><?php echo $PlayersLang['GoalieName'];?></th>
<th style="background-color:#2a2a2a;color:#fff;text-shadow:none;font-weight:bold;text-transform:uppercase;font-size:12px;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px data-priority="1" title="Games Played" class="STHSW25">GP</th>
<th style="background-color:#2a2a2a;color:#fff;text-shadow:none;font-weight:bold;text-transform:uppercase;font-size:12px;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px data-priority="1" title="Wins" class="STHSW25">W</th>
<th style="background-color:#2a2a2a;color:#fff;text-shadow:none;font-weight:bold;text-transform:uppercase;font-size:12px;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px data-priority="2" title="Losses" class="STHSW25">L</th>
<th style="background-color:#2a2a2a;color:#fff;text-shadow:none;font-weight:bold;text-transform:uppercase;font-size:12px;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px data-priority="2" title="Overtime Losses" class="STHSW25">OTL</th>
<th style="background-color:#2a2a2a;color:#fff;text-shadow:none;font-weight:bold;text-transform:uppercase;font-size:12px;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px data-priority="critical" title="Save Percentage" class="STHSW50">PCT</th>
<th style="background-color:#2a2a2a;color:#fff;text-shadow:none;font-weight:bold;text-transform:uppercase;font-size:12px;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px data-priority="critical" title="Goals Against Average" class="STHSW50">GAA</th>
<th style="background-color:#2a2a2a;color:#fff;text-shadow:none;font-weight:bold;text-transform:uppercase;font-size:12px;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px data-priority="3" title="Minutes Played" class="STHSW50">MP</th>
<th style="background-color:#2a2a2a;color:#fff;text-shadow:none;font-weight:bold;text-transform:uppercase;font-size:12px;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px data-priority="5" title="Penalty Minutes" class="STHSW25">PIM</th>
<th style="background-color:#2a2a2a;color:#fff;text-shadow:none;font-weight:bold;text-transform:uppercase;font-size:12px;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px data-priority="4" title="Shutouts" class="STHSW25">SO</th>
<th style="background-color:#2a2a2a;color:#fff;text-shadow:none;font-weight:bold;text-transform:uppercase;font-size:12px;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px data-priority="3" title="Goals Against" class="STHSW25">GA</th>
<th style="background-color:#2a2a2a;color:#fff;text-shadow:none;font-weight:bold;text-transform:uppercase;font-size:12px;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px data-priority="3" title="Shots Against" class="STHSW45">SA</th>
<th style="background-color:#2a2a2a;color:#fff;text-shadow:none;font-weight:bold;text-transform:uppercase;font-size:12px;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px data-priority="5" title="Assists" class="STHSW25">A</th>
<th style="background-color:#2a2a2a;color:#fff;text-shadow:none;font-weight:bold;text-transform:uppercase;font-size:12px;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px data-priority="5" title="Empty net Goals" class="STHSW25">EG</th>
<th style="background-color:#2a2a2a;color:#fff;text-shadow:none;font-weight:bold;text-transform:uppercase;font-size:12px;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px data-priority="4" title="Penalty Shots Save %" class="STHSW50">PS %</th>
<th style="background-color:#2a2a2a;color:#fff;text-shadow:none;font-weight:bold;text-transform:uppercase;font-size:12px;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px data-priority="5" title="Penalty Shots Against" class="STHSW25">PSA</th>
</tr></thead><tbody>
<?php
$Order = 0;
if (empty($CareerStatGoalie) == false){while ($Row = $CareerStatGoalie ->fetchArray()) {
	$Order +=1;
	echo "<tr><td>" . $Order ."</td>";
	If ($Row['Number'] > 0){
		echo "<td><a href=\"GoalieReport.php?Goalie=" . $Row['Number'] . "\">" . $Row['Name'] . "</a></td>";
	}else{
		echo "<td><a href=\"CareerStatGoalieReport.php?Goalie=" . $Row['SumOfName'] . "\">" . $Row['SumOfName'] . "*</a></td>";
	}	
	
	If ($Year == 0 AND $LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False" AND $TeamName == ""){
		echo "<td>" . ($Row['SumOfGP'] + $Row['GP']) . "</td>";
		echo "<td>" . ($Row['SumOfW'] + $Row['W']) . "</td>";
		echo "<td>" . ($Row['SumOfL'] + $Row['L']) . "</td>";
		echo "<td>" . ($Row['SumOfOTL'] + $Row['OTL']) . "</td>";
		echo "<td>" . number_Format($Row['TotalPCT'],3) . "</td>";
		echo "<td>" . number_Format($Row['TotalGAA'],2) . "</td>";
		echo "<td>";if ($Row <> Null){echo Floor(($Row['SumOfSecondPlay'] + $Row['SecondPlay']) /60);}; echo "</td>";
		echo "<td>" . ($Row['SumOfPim'] + $Row['Pim']) . "</td>";
		echo "<td>" . ($Row['SumOfShootout'] + $Row['Shootout']) . "</td>";
		echo "<td>" . ($Row['SumOfGA'] + $Row['GA']) . "</td>";
		echo "<td>" . ($Row['SumOfSA'] + $Row['SA']) . "</td>";
		echo "<td>" . ($Row['SumOfA'] + $Row['A']) . "</td>";
		echo "<td>" . ($Row['SumOfEmptyNetGoal'] + $Row['EmptyNetGoal']) . "</td>";			
		echo "<td>" . number_Format($Row['TotalPenalityShotsPCT'],3) . "</td>";
		echo "<td>" . ($Row['SumOfPenalityShotsShots'] + $Row['PenalityShotsShots']) . "</td>";
		echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
	}else{
		echo "<td>" . $Row['SumOfGP'] . "</td>";
		echo "<td>" . $Row['SumOfW'] . "</td>";
		echo "<td>" . $Row['SumOfL'] . "</td>";
		echo "<td>" . $Row['SumOfOTL'] . "</td>";
		echo "<td>" . number_Format($Row['SumOfPCT'],3) . "</td>";
		echo "<td>" . number_Format($Row['SumOfGAA'],2) . "</td>";
		echo "<td>";if ($Row <> Null){echo Floor($Row['SumOfSecondPlay']/60);}; echo "</td>";
		echo "<td>" . $Row['SumOfPim'] . "</td>";
		echo "<td>" . $Row['SumOfShootout'] . "</td>";
		echo "<td>" . $Row['SumOfGA'] . "</td>";
		echo "<td>" . $Row['SumOfSA'] . "</td>";
		echo "<td>" . $Row['SumOfA'] . "</td>";
		echo "<td>" . $Row['SumOfEmptyNetGoal'] . "</td>";			
		echo "<td>" . number_Format($Row['SumOfPenalityShotsPCT'],3) . "</td>";
		echo "<td>" . $Row['SumOfPenalityShotsShots'] . "</td>";
		echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
	}
}}
?>

</div>
</div>


</td>

</tr>
</table>



<div style="padding-top:25px;border-radius: 10px;">

<div class="color4_<?php echo $TeamInfo['Number']; ?>" style="font-size:16px;padding-left:12px;margin-bottom:15px;padding-top:5px;font-weight:700;color:#000"><?php	echo $TeamInfo['Name'] ?> Goalies Stat Leaders (Play-Off)</div>

<table class="tablesorter STHSPHPAllGoalieStat_Table"><thead><tr>
<th style="background-color:#2a2a2a;color:#fff;text-shadow:none;font-weight:bold;text-transform:uppercase;font-size:12px;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px" data-priority="3" title="Order Number" class="STHSW10 sorter-false">#</th>
<th style="background-color:#2a2a2a;color:#fff;text-shadow:none;font-weight:bold;text-transform:uppercase;font-size:12px;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px data-priority="critical" title="Goalie Name" class="STHSW140Min"><?php echo $PlayersLang['GoalieName'];?></th>
<th style="background-color:#2a2a2a;color:#fff;text-shadow:none;font-weight:bold;text-transform:uppercase;font-size:12px;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px data-priority="1" title="Games Played" class="STHSW25">GP</th>
<th style="background-color:#2a2a2a;color:#fff;text-shadow:none;font-weight:bold;text-transform:uppercase;font-size:12px;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px data-priority="1" title="Wins" class="STHSW25">W</th>
<th style="background-color:#2a2a2a;color:#fff;text-shadow:none;font-weight:bold;text-transform:uppercase;font-size:12px;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px data-priority="2" title="Losses" class="STHSW25">L</th>
<th style="background-color:#2a2a2a;color:#fff;text-shadow:none;font-weight:bold;text-transform:uppercase;font-size:12px;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px data-priority="2" title="Overtime Losses" class="STHSW25">OTL</th>
<th style="background-color:#2a2a2a;color:#fff;text-shadow:none;font-weight:bold;text-transform:uppercase;font-size:12px;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px data-priority="critical" title="Save Percentage" class="STHSW50">PCT</th>
<th style="background-color:#2a2a2a;color:#fff;text-shadow:none;font-weight:bold;text-transform:uppercase;font-size:12px;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px data-priority="critical" title="Goals Against Average" class="STHSW50">GAA</th>
<th style="background-color:#2a2a2a;color:#fff;text-shadow:none;font-weight:bold;text-transform:uppercase;font-size:12px;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px data-priority="3" title="Minutes Played" class="STHSW50">MP</th>
<th style="background-color:#2a2a2a;color:#fff;text-shadow:none;font-weight:bold;text-transform:uppercase;font-size:12px;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px data-priority="5" title="Penalty Minutes" class="STHSW25">PIM</th>
<th style="background-color:#2a2a2a;color:#fff;text-shadow:none;font-weight:bold;text-transform:uppercase;font-size:12px;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px data-priority="4" title="Shutouts" class="STHSW25">SO</th>
<th style="background-color:#2a2a2a;color:#fff;text-shadow:none;font-weight:bold;text-transform:uppercase;font-size:12px;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px data-priority="3" title="Goals Against" class="STHSW25">GA</th>
<th style="background-color:#2a2a2a;color:#fff;text-shadow:none;font-weight:bold;text-transform:uppercase;font-size:12px;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px data-priority="3" title="Shots Against" class="STHSW45">SA</th>
<th style="background-color:#2a2a2a;color:#fff;text-shadow:none;font-weight:bold;text-transform:uppercase;font-size:12px;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px data-priority="5" title="Assists" class="STHSW25">A</th>
<th style="background-color:#2a2a2a;color:#fff;text-shadow:none;font-weight:bold;text-transform:uppercase;font-size:12px;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px data-priority="5" title="Empty net Goals" class="STHSW25">EG</th>
<th style="background-color:#2a2a2a;color:#fff;text-shadow:none;font-weight:bold;text-transform:uppercase;font-size:12px;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px data-priority="4" title="Penalty Shots Save %" class="STHSW50">PS %</th>
<th style="background-color:#2a2a2a;color:#fff;text-shadow:none;font-weight:bold;text-transform:uppercase;font-size:12px;padding-top:12px;padding-bottom:12px;padding-left:8px;padding-right:8px data-priority="5" title="Penalty Shots Against" class="STHSW25">PSA</th>
</tr></thead><tbody>
<?php
$Order = 0;
if (empty($CareerStatGoalie2) == false){while ($Row = $CareerStatGoalie2 ->fetchArray()) {
	$Order +=1;
	echo "<tr><td>" . $Order ."</td>";
	If ($Row['Number'] > 0){
		echo "<td><a href=\"GoalieReport.php?Goalie=" . $Row['Number'] . "\">" . $Row['Name'] . "</a></td>";
	}else{
		echo "<td><a href=\"CareerStatGoalieReport.php?Goalie=" . $Row['SumOfName'] . "\">" . $Row['SumOfName'] . "*</a></td>";
	}	
	
	If ($Year == 0 AND $LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False" AND $TeamName == ""){
		echo "<td>" . ($Row['SumOfGP'] + $Row['GP']) . "</td>";
		echo "<td>" . ($Row['SumOfW'] + $Row['W']) . "</td>";
		echo "<td>" . ($Row['SumOfL'] + $Row['L']) . "</td>";
		echo "<td>" . ($Row['SumOfOTL'] + $Row['OTL']) . "</td>";
		echo "<td>" . number_Format($Row['TotalPCT'],3) . "</td>";
		echo "<td>" . number_Format($Row['TotalGAA'],2) . "</td>";
		echo "<td>";if ($Row <> Null){echo Floor(($Row['SumOfSecondPlay'] + $Row['SecondPlay']) /60);}; echo "</td>";
		echo "<td>" . ($Row['SumOfPim'] + $Row['Pim']) . "</td>";
		echo "<td>" . ($Row['SumOfShootout'] + $Row['Shootout']) . "</td>";
		echo "<td>" . ($Row['SumOfGA'] + $Row['GA']) . "</td>";
		echo "<td>" . ($Row['SumOfSA'] + $Row['SA']) . "</td>";
		echo "<td>" . ($Row['SumOfA'] + $Row['A']) . "</td>";
		echo "<td>" . ($Row['SumOfEmptyNetGoal'] + $Row['EmptyNetGoal']) . "</td>";			
		echo "<td>" . number_Format($Row['TotalPenalityShotsPCT'],3) . "</td>";
		echo "<td>" . ($Row['SumOfPenalityShotsShots'] + $Row['PenalityShotsShots']) . "</td>";
		echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
	}else{
		echo "<td>" . $Row['SumOfGP'] . "</td>";
		echo "<td>" . $Row['SumOfW'] . "</td>";
		echo "<td>" . $Row['SumOfL'] . "</td>";
		echo "<td>" . $Row['SumOfOTL'] . "</td>";
		echo "<td>" . number_Format($Row['SumOfPCT'],3) . "</td>";
		echo "<td>" . number_Format($Row['SumOfGAA'],2) . "</td>";
		echo "<td>";if ($Row <> Null){echo Floor($Row['SumOfSecondPlay']/60);}; echo "</td>";
		echo "<td>" . $Row['SumOfPim'] . "</td>";
		echo "<td>" . $Row['SumOfShootout'] . "</td>";
		echo "<td>" . $Row['SumOfGA'] . "</td>";
		echo "<td>" . $Row['SumOfSA'] . "</td>";
		echo "<td>" . $Row['SumOfA'] . "</td>";
		echo "<td>" . $Row['SumOfEmptyNetGoal'] . "</td>";			
		echo "<td>" . number_Format($Row['SumOfPenalityShotsPCT'],3) . "</td>";
		echo "<td>" . $Row['SumOfPenalityShotsShots'] . "</td>";
		echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
	}
}}
?>


</div>
</div>


</td>

</tr>
</table>





<table class="STHSTableFullW" style="z-index:-1"><tr>



<td class="STHSIndex_NewsTD" id="STHSIndex_Top5" style="background-color:#fff;border-radius:10px;padding-left:0px;padding-right:0px;max-width:450px;box-shadow:0 2px 3px rgba(0,0,0,.1);z-index:-1">


<div class="color4_<?php echo $TeamInfo['Number']; ?>" style="font-size:16px;padding-left:12px;margin-bottom:-20px;padding-top:0px;font-weight:700;color:#000">Career Team Stats</div>

<div class="tablesorter_ColumnSelectorWrapper">
    <input id="tablesorter_colSelect11" type="checkbox" class="hidden">
    <div id="tablesorter_ColumnSelector11" class="tablesorter_ColumnSelector"></div>
</div>

<table class="tablesorter STHSPHPTeam_TeamCareerStat"><thead><tr>
<th data-priority="critical" title="Year" class="STHSW55"><?php echo $TeamLang['Year'];?></th>
<th data-priority="1" title="Games Played" class="STHSW25">GP</th>
<th data-priority="1" title="Wins" class="STHSW25">W</th>
<th data-priority="1" title="Loss" class="STHSW25">L</th>
<th data-priority="1" title="Overtime Wins" class="STHSW10">OTW</th>
<th data-priority="1" title="Overtime Loss" class="STHSW10">OTL</th>
<th data-priority="1" title="Shootout Wins" class="STHSW10">SOW</th>
<th data-priority="1" title="Shootout Loss" class="STHSW10">SOL</th>
<th data-priority="1" title="Goals For" class="STHSW25">GF</th>
<th data-priority="1" title="Goals Against" class="STHSW25">GA</th>
<th data-priority="1" title="Goals For Diffirencial against Goals Against" class="STHSW25">Diff</th>
<th data-priority="1" title="Points" class="STHSW25">P</th>
<th data-priority="3" title="Penalty Minutes" class="STHSW25">Pim</th>
<th data-priority="6" title="Power Play Attemps" class="columnSelector-false STHSW25">PPA</th>
<th data-priority="5" title="Power Play Goals" class="STHSW25">PPG</th>
<th data-priority="4" title="Power Play %" class="STHSW35">PP%</th>
<th data-priority="6" title="Penalty Kill Attemps" class="columnSelector-false STHSW25">PKA</th>
<th data-priority="5" title="Penalty Kill Goals Against" class="STHSW25">PK GA</th>
<th data-priority="4" title="Penalty Kill %" class="STHSW35">PK%</th>
<th data-priority="6" title="Penalty Kill Goals For" class="columnSelector-false STHSW25">PK GF</th>
</tr></thead><tbody>
<?php
if ($TeamCareerSumSeasonOnly != Null){
if ($TeamCareerSumSeasonOnly['SumOfGP'] > 0){echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"75\"><strong>" . $PlayersLang['RegularSeason'] . "</strong></td></tr>\n";}
if (empty($TeamCareerSeason) == false){while ($row = $TeamCareerSeason ->fetchArray()) {
	/* Loop Team Career Season */
	echo "<tr><td>" . $row['Year'] . "-" . ($row['Year']-1999) . "</td>";
	echo "<td>" . $row['GP'] . "</td>";
	echo "<td>" . $row['W']  . "</td>";
	echo "<td>" . $row['L'] . "</td>";
	echo "<td>" . $row['OTW'] . "</td>";	
	echo "<td>" . $row['OTL'] . "</td>";	
	echo "<td>" . $row['SOW'] . "</td>";	
	echo "<td>" . $row['SOL'] . "</td>";	
	echo "<td>" . $row['GF'] . "</td>";
	echo "<td>" . $row['GA'] . "</td>";
	echo "<td>" . ($row['GF'] - $row['GA']) . "</td>";		
	echo "<td><strong>" . $row['Points'] . "</strong></td>";
	echo "<td>" . $row['Pim']. "</td>";
	echo "<td>" . $row['PPAttemp']. "</td>";
	echo "<td>" . $row['PPGoal']. "</td>";
	echo "<td>";if ($row['PPAttemp'] > 0){echo number_Format($row['PPGoal'] / $row['PPAttemp'] * 100,2) . "%";} else { echo "0.00%";} echo "</td>";		
	echo "<td>" . $row['PKAttemp']. "</td>";
	echo "<td>" . $row['PKGoalGA']. "</td>";
	echo "<td>";if ($row['PKAttemp'] > 0){echo number_Format(($row['PKAttemp'] - $row['PKGoalGA']) / $row['PKAttemp'] * 100,2) . "%";} else {echo "0.00%";} echo "</td>";
	echo "<td>" .  $row['PKGoalGF']. "</td>";	
	echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
}}
if ($TeamStat['GP'] > 0 AND $LeagueGeneral['PreSeasonSchedule'] == "False" AND $LeagueGeneral['PlayOffStarted'] == "False"){
	echo "<tr><td>" . $LeagueGeneral['LeagueYearOutput'] . "</td>";
	echo "<td>" . $TeamStat['GP'] . "</td>";
	echo "<td>" . $TeamStat['W']  . "</td>";
	echo "<td>" . $TeamStat['L'] . "</td>";
	echo "<td>" . $TeamStat['OTW'] . "</td>";	
	echo "<td>" . $TeamStat['OTL'] . "</td>";	
	echo "<td>" . $TeamStat['SOW'] . "</td>";	
	echo "<td>" . $TeamStat['SOL'] . "</td>";	
	echo "<td>" . $TeamStat['GF'] . "</td>";
	echo "<td>" . $TeamStat['GA'] . "</td>";
	echo "<td>" . ($TeamStat['GF'] - $TeamStat['GA']) . "</td>";	
	echo "<td><strong>" . $TeamStat['Points'] . "</strong></td>";
	echo "<td>" . $TeamStat['Pim']. "</td>";
	echo "<td>" . $TeamStat['PPAttemp']. "</td>";
	echo "<td>" . $TeamStat['PPGoal']. "</td>";
	echo "<td>";if ($TeamStat['PPAttemp'] > 0){echo number_Format($TeamStat['PPGoal'] / $TeamStat['PPAttemp'] * 100,2) . "%";} else { echo "0.00%";} echo "</td>";		
	echo "<td>" . $TeamStat['PKAttemp']. "</td>";
	echo "<td>" . $TeamStat['PKGoalGA']. "</td>";
	echo "<td>";if ($TeamStat['PKAttemp'] > 0){echo number_Format(($TeamStat['PKAttemp'] - $TeamStat['PKGoalGA']) / $TeamStat['PKAttemp'] * 100,2) . "%";} else {echo "0.00%";} echo "</td>";
	echo "<td>" .  $TeamStat['PKGoalGF']. "</td>";			
	echo "</tr>\n"; /* The \n is for a new line in the HTML Code */	
	
	#Add Current Year in Career Stat
	$TeamCareerSumSeasonOnly['SumOfGP'] =  $TeamCareerSumSeasonOnly['SumOfGP'] + $TeamStat['GP'];
	$TeamCareerSumSeasonOnly['SumOfW'] =  $TeamCareerSumSeasonOnly['SumOfW'] + $TeamStat['W'];
	$TeamCareerSumSeasonOnly['SumOfL'] =  $TeamCareerSumSeasonOnly['SumOfL'] + $TeamStat['L'];
	$TeamCareerSumSeasonOnly['SumOfOTW'] =  $TeamCareerSumSeasonOnly['SumOfOTW'] + $TeamStat['OTW'];
	$TeamCareerSumSeasonOnly['SumOfOTL'] =  $TeamCareerSumSeasonOnly['SumOfOTL'] + $TeamStat['OTL'];
	$TeamCareerSumSeasonOnly['SumOfSOW'] =  $TeamCareerSumSeasonOnly['SumOfSOW'] + $TeamStat['SOW'];
	$TeamCareerSumSeasonOnly['SumOfSOL'] =  $TeamCareerSumSeasonOnly['SumOfSOL'] + $TeamStat['SOL'];
	$TeamCareerSumSeasonOnly['SumOfPoints'] =  $TeamCareerSumSeasonOnly['SumOfPoints'] + $TeamStat['Points'];
	$TeamCareerSumSeasonOnly['SumOfGF'] =  $TeamCareerSumSeasonOnly['SumOfGF'] + $TeamStat['GF'];
	$TeamCareerSumSeasonOnly['SumOfGA'] =  $TeamCareerSumSeasonOnly['SumOfGA'] + $TeamStat['GA'];
	$TeamCareerSumSeasonOnly['SumOfPPAttemp'] =  $TeamCareerSumSeasonOnly['SumOfPPAttemp'] + $TeamStat['PPAttemp'];
	$TeamCareerSumSeasonOnly['SumOfPPGoal'] =  $TeamCareerSumSeasonOnly['SumOfPPGoal'] + $TeamStat['PPGoal'];
	$TeamCareerSumSeasonOnly['SumOfPKAttemp'] =  $TeamCareerSumSeasonOnly['SumOfPKAttemp'] + $TeamStat['PKAttemp'];
	$TeamCareerSumSeasonOnly['SumOfPKGoalGA'] =  $TeamCareerSumSeasonOnly['SumOfPKGoalGA'] + $TeamStat['PKGoalGA'];
	$TeamCareerSumSeasonOnly['SumOfPKGoalGF'] =  $TeamCareerSumSeasonOnly['SumOfPKGoalGF'] + $TeamStat['PKGoalGF'];
	$TeamCareerSumSeasonOnly['SumOfPim'] =  $TeamCareerSumSeasonOnly['SumOfPim'] + $TeamStat['Pim'];
}
if ($TeamCareerSumSeasonOnly['SumOfGP'] > 0){
	/* Show TeamCareer Total for Season */
	echo "<tr class=\"static\"><td><strong>" . $PlayersLang['Total'] . " " . $PlayersLang['RegularSeason']. "</strong></td>";
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfGP'] . "</td>";
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfW']  . "</td>";
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfL'] . "</td>";
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfOTW'] . "</td>";	
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfOTL'] . "</td>";	
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfSOW'] . "</td>";	
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfSOL'] . "</td>";	
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfGF'] . "</td>";
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfGA'] . "</td>";
	echo "<td>" . ($TeamCareerSumSeasonOnly['SumOfGF'] - $TeamCareerSumSeasonOnly['SumOfGA']) . "</td>";	
	echo "<td><strong>" . $TeamCareerSumSeasonOnly['SumOfPoints'] . "</strong></td>";
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfPim']. "</td>";
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfPPAttemp']. "</td>";
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfPPGoal']. "</td>";
	echo "<td>";if ($TeamCareerSumSeasonOnly['SumOfPPAttemp'] > 0){echo number_Format($TeamCareerSumSeasonOnly['SumOfPPGoal'] / $TeamCareerSumSeasonOnly['SumOfPPAttemp'] * 100,2) . "%";} else { echo "0.00%";} echo "</td>";		
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfPKAttemp']. "</td>";
	echo "<td>" . $TeamCareerSumSeasonOnly['SumOfPKGoalGA']. "</td>";
	echo "<td>";if ($TeamCareerSumSeasonOnly['SumOfPKAttemp'] > 0){echo number_Format(($TeamCareerSumSeasonOnly['SumOfPKAttemp'] - $TeamCareerSumSeasonOnly['SumOfPKGoalGA']) / $TeamCareerSumSeasonOnly['SumOfPKAttemp'] * 100,2) . "%";} else {echo "0.00%";} echo "</td>";
	echo "<td>" .  $TeamCareerSumSeasonOnly['SumOfPKGoalGF']. "</td>";			
	echo "</tr>\n"; /* The \n is for a new line in the HTML Code */	
}
if ($TeamCareerSumPlayoffOnly['SumOfGP'] > 0){echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"75\"><strong>" . $PlayersLang['Playoff'] . "</strong></td></tr>\n";}
if (empty($TeamCareerPlayoff) == false){while ($row = $TeamCareerPlayoff ->fetchArray()) {
	/* Loop Team Career Playoff */
	echo "<tr><td>" . $row['Year'] . "-" . ($row['Year']-1999) . "</td>";
	echo "<td>" . $row['GP'] . "</td>";
	echo "<td>" . $row['W']  . "</td>";
	echo "<td>" . $row['L'] . "</td>";
	echo "<td>" . $row['OTW'] . "</td>";	
	echo "<td>" . $row['OTL'] . "</td>";	
	echo "<td>" . $row['SOW'] . "</td>";	
	echo "<td>" . $row['SOL'] . "</td>";	
	echo "<td>" . $row['GF'] . "</td>";
	echo "<td>" . $row['GA'] . "</td>";
	echo "<td>" . ($row['GF'] - $row['GA']) . "</td>";	
	echo "<td><strong>" . $row['Points'] . "</strong></td>";
	echo "<td>" . $row['Pim']. "</td>";
	echo "<td>" . $row['PPAttemp']. "</td>";
	echo "<td>" . $row['PPGoal']. "</td>";
	echo "<td>";if ($row['PPAttemp'] > 0){echo number_Format($row['PPGoal'] / $row['PPAttemp'] * 100,2) . "%";} else { echo "0.00%";} echo "</td>";		
	echo "<td>" . $row['PKAttemp']. "</td>";
	echo "<td>" . $row['PKGoalGA']. "</td>";
	echo "<td>";if ($row['PKAttemp'] > 0){echo number_Format(($row['PKAttemp'] - $row['PKGoalGA']) / $row['PKAttemp'] * 100,2) . "%";} else {echo "0.00%";} echo "</td>";
	echo "<td>" .  $row['PKGoalGF']. "</td>";	
		
	echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
}}
if ($TeamStat['GP'] > 0 AND $LeagueGeneral['PreSeasonSchedule'] == "False" AND $LeagueGeneral['PlayOffStarted'] == "True"){
	echo "<tr><td>" . $LeagueGeneral['LeagueYearOutput'] . "</td>";
	echo "<td>" . $TeamStat['GP'] . "</td>";
	echo "<td>" . $TeamStat['W']  . "</td>";
	echo "<td>" . $TeamStat['L'] . "</td>";
	echo "<td>" . $TeamStat['OTW'] . "</td>";	
	echo "<td>" . $TeamStat['OTL'] . "</td>";	
	echo "<td>" . $TeamStat['SOW'] . "</td>";	
	echo "<td>" . $TeamStat['SOL'] . "</td>";	
	echo "<td>" . $TeamStat['GF'] . "</td>";
	echo "<td>" . $TeamStat['GA'] . "</td>";
	echo "<td>" . ($TeamStat['GF'] - $TeamStat['GA']) . "</td>";	
	echo "<td><strong>" . $TeamStat['Points'] . "</strong></td>";
	echo "<td>" . $TeamStat['Pim']. "</td>";
	echo "<td>" . $TeamStat['PPAttemp']. "</td>";
	echo "<td>" . $TeamStat['PPGoal']. "</td>";
	echo "<td>";if ($TeamStat['PPAttemp'] > 0){echo number_Format($TeamStat['PPGoal'] / $TeamStat['PPAttemp'] * 100,2) . "%";} else { echo "0.00%";} echo "</td>";		
	echo "<td>" . $TeamStat['PKAttemp']. "</td>";
	echo "<td>" . $TeamStat['PKGoalGA']. "</td>";
	echo "<td>";if ($TeamStat['PKAttemp'] > 0){echo number_Format(($TeamStat['PKAttemp'] - $TeamStat['PKGoalGA']) / $TeamStat['PKAttemp'] * 100,2) . "%";} else {echo "0.00%";} echo "</td>";
	echo "<td>" .  $TeamStat['PKGoalGF']. "</td>";			
	echo "</tr>\n"; /* The \n is for a new line in the HTML Code */	
	
	#Add Current Year in Career Stat
	$TeamCareerSumPlayoffOnly['SumOfGP'] =  $TeamCareerSumPlayoffOnly['SumOfGP'] + $TeamStat['GP'];
	$TeamCareerSumPlayoffOnly['SumOfW'] =  $TeamCareerSumPlayoffOnly['SumOfW'] + $TeamStat['W'];
	$TeamCareerSumPlayoffOnly['SumOfL'] =  $TeamCareerSumPlayoffOnly['SumOfL'] + $TeamStat['L'];
	$TeamCareerSumPlayoffOnly['SumOfOTW'] =  $TeamCareerSumPlayoffOnly['SumOfOTW'] + $TeamStat['OTW'];
	$TeamCareerSumPlayoffOnly['SumOfOTL'] =  $TeamCareerSumPlayoffOnly['SumOfOTL'] + $TeamStat['OTL'];
	$TeamCareerSumPlayoffOnly['SumOfSOW'] =  $TeamCareerSumPlayoffOnly['SumOfSOW'] + $TeamStat['SOW'];
	$TeamCareerSumPlayoffOnly['SumOfSOL'] =  $TeamCareerSumPlayoffOnly['SumOfSOL'] + $TeamStat['SOL'];
	$TeamCareerSumPlayoffOnly['SumOfPoints'] =  $TeamCareerSumPlayoffOnly['SumOfPoints'] + $TeamStat['Points'];
	$TeamCareerSumPlayoffOnly['SumOfGF'] =  $TeamCareerSumPlayoffOnly['SumOfGF'] + $TeamStat['GF'];
	$TeamCareerSumPlayoffOnly['SumOfGA'] =  $TeamCareerSumPlayoffOnly['SumOfGA'] + $TeamStat['GA'];
	$TeamCareerSumPlayoffOnly['SumOfPPAttemp'] =  $TeamCareerSumPlayoffOnly['SumOfPPAttemp'] + $TeamStat['PPAttemp'];
	$TeamCareerSumPlayoffOnly['SumOfPPGoal'] =  $TeamCareerSumPlayoffOnly['SumOfPPGoal'] + $TeamStat['PPGoal'];
	$TeamCareerSumPlayoffOnly['SumOfPKAttemp'] =  $TeamCareerSumPlayoffOnly['SumOfPKAttemp'] + $TeamStat['PKAttemp'];
	$TeamCareerSumPlayoffOnly['SumOfPKGoalGA'] =  $TeamCareerSumPlayoffOnly['SumOfPKGoalGA'] + $TeamStat['PKGoalGA'];
	$TeamCareerSumPlayoffOnly['SumOfPKGoalGF'] =  $TeamCareerSumPlayoffOnly['SumOfPKGoalGF'] + $TeamStat['PKGoalGF'];
	$TeamCareerSumPlayoffOnly['SumOfPim'] =  $TeamCareerSumPlayoffOnly['SumOfPim'] + $TeamStat['Pim'];
}
if ($TeamCareerSumPlayoffOnly['SumOfGP'] > 0){
	/* Show TeamCareer Total for Playoff */
	echo "<tr class=\"static\"><td><strong>" . $PlayersLang['Total'] . " " . $PlayersLang['Playoff']. "</strong></td>";
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfGP'] . "</td>";
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfW']  . "</td>";
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfL'] . "</td>";
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfOTW'] . "</td>";	
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfOTL'] . "</td>";	
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfSOW'] . "</td>";	
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfSOL'] . "</td>";	
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfGF'] . "</td>";
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfGA'] . "</td>";
	echo "<td>" . ($TeamCareerSumPlayoffOnly['SumOfGF'] - $TeamCareerSumPlayoffOnly['SumOfGA']) . "</td>";	
	echo "<td><strong>" . $TeamCareerSumPlayoffOnly['SumOfPoints'] . "</strong></td>";
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfPim']. "</td>";
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfPPAttemp']. "</td>";
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfPPGoal']. "</td>";
	echo "<td>";if ($TeamCareerSumPlayoffOnly['SumOfPPAttemp'] > 0){echo number_Format($TeamCareerSumPlayoffOnly['SumOfPPGoal'] / $TeamCareerSumPlayoffOnly['SumOfPPAttemp'] * 100,2) . "%";} else { echo "0.00%";} echo "</td>";		
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfPKAttemp']. "</td>";
	echo "<td>" . $TeamCareerSumPlayoffOnly['SumOfPKGoalGA']. "</td>";
	echo "<td>";if ($TeamCareerSumPlayoffOnly['SumOfPKAttemp'] > 0){echo number_Format(($TeamCareerSumPlayoffOnly['SumOfPKAttemp'] - $TeamCareerSumPlayoffOnly['SumOfPKGoalGA']) / $TeamCareerSumPlayoffOnly['SumOfPKAttemp'] * 100,2) . "%";} else {echo "0.00%";} echo "</td>";
	echo "<td>" .  $TeamCareerSumPlayoffOnly['SumOfPKGoalGF']. "</td>";			
	echo "</tr>\n"; /* The \n is for a new line in the HTML Code */	
}}
?>

</td>
</table>

</div>
</div>
</div>
</div></div>
</div></div>



</tbody>
<br />

</div>



</div>



</div>



</td>
</tr>
<br>
</table>







</td>

<td style="width:16px"  id="STHSIndex_Main" >&nbsp</td>
<td class="STHSIndex_Main" id="STHSIndex_Main" style="background-color:white;width:10%;box-shadow:0 2px 3px rgba(0,0,0,.1);border-radius:10px;min-width:400px">



<table style="border-collapse:collapse" class="STHSIndex_Top5Table">
<tr><h2 colspan="2" style="font-size:16px;padding-bottom:16px;font-weight:bold">Team Info</h2></tr>
<td rowspan="5" style="background:white;border:none;width:110px;text-align:center"><?php	echo "<img src=\"/images/LogoTeams/Pro/" . $TeamInfo['Number'] . "." . png . "\" style=\"height:64px;vertical-align:middle;height:78px\" alt=\"" . $TeamName . "\"  "."";?>
</td>
<?php
	echo "<tr style=border-bottom-style:solid;border-bottom-width:1px;border-bottom-color:#ececec><td style=background-color:white;border:none>" ."General Manager"."</td><td  style=padding-right:12px;background-color:white;border:none;font-size:13px;font-weight:bold;text-align:right>" . $TeamInfo['GMName'] . "</td></tr>\n";
?>

<?php
	echo "<tr style=border-bottom-style:solid;border-bottom-width:1px;border-bottom-color:#ececec><td style=background-color:white;border:none>" ."Head Coach"."</td><td  style=padding-right:12px;background-color:white;border:none;font-size:13px;font-weight:bold;text-align:right>" . $CoachInfo['Name'] . "</td></tr>\n";
?>
<?php
	echo "<tr style=border-bottom-style:solid;border-bottom-width:1px;border-bottom-color:#ececec><td style=background-color:white;border:none>" ."Conference"."</td><td  style=padding-right:12px;background-color:white;border:none;font-size:13px;font-weight:bold;text-align:right>" . $TeamInfo['Conference'] . "</td></tr>\n";
?>

<?php
	echo "<tr><td style=background-color:white;border:none>" ."Division"."</td><td  style=padding-right:12px;background-color:white;border:none;font-size:13px;font-weight:bold;text-align:right>" . $TeamInfo['Division'] . "</td></tr>\n";
?>

</tr>
</table>

<table style="border-collapse:collapse" class="STHSIndex_Top5Table">
<tr><h2 colspan="2" style="font-size:16px;padding-bottom:16px;font-weight:bold;border-top: 2px solid #ececec;padding-top: 8px;">Team History</h2></tr>
<td rowspan="7" style="background:white;border:none;width:110px;text-align:center">
<img src="https://image.flaticon.com/icons/png/512/87/87578.png" style="height:64px;height:78px\; vertical-align: middle">
</td>
<?php
if ($TeamCareerSumSeasonOnly['SumOfGP'] > 0)
if ($TeamStat['GP'] > 0 AND $LeagueGeneral['PreSeasonSchedule'] == "False" AND $LeagueGeneral['PlayOffStarted'] == "False"){
	echo "<tr style=border-bottom-style:solid;border-bottom-width:1px;border-bottom-color:#ececec><td style=background-color:white;border:none;white-space:nowrap>" . $LeagueGeneral['LeagueYearOutput'] . "</td>";
	echo "<td  style=padding-right:12px;background-color:white;border:none;font-size:13px;font-weight:bold;text-align:right>" . ($TeamStat['W'] + $TeamStat['OTW'] + $TeamStat['SOW'])  . "-" . $TeamStat['L']. "-" . ($TeamStat['OTL'] + $TeamStat['SOL']) . " (" . $TeamStat['Points'] . "PTS)" . "</td>";
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
}
if ($TeamCareerSumSeasonOnly['SumOfGP'] > 0){
	/* Show TeamCareer Total for Season */
	echo "<tr style=border-bottom-style:solid;border-bottom-width:1px;border-bottom-color:#ececec><td style=background-color:white;border:none;white-space:nowrap>" . "Record (W-L-OTL)" . "</td>";
	echo "<td  style=padding-right:12px;background-color:white;border:none;font-size:13px;font-weight:bold;text-align:right>" . ($TeamCareerSumSeasonOnly['SumOfW'] + $TeamCareerSumSeasonOnly['SumOfOTW'] + $TeamCareerSumSeasonOnly['SumOfSOW'])  . "-" . $TeamCareerSumSeasonOnly['SumOfL']. "-" . ($TeamCareerSumSeasonOnly['SumOfOTL'] + $TeamCareerSumSeasonOnly['SumOfSOL']) . " (" .$TeamCareerSumSeasonOnly['SumOfPoints']."PTS)" .  "</td>";
	echo "</tr>\n"; /* The \n is for a new line in the HTML Code */	
}

if ($TeamCareerSumPlayoffOnly['SumOfGP'] >= 0){
	/* Show TeamCareer Total for Playoff */
	echo "<tr style=border-bottom-style:solid;border-bottom-width:1px;border-bottom-color:#ececec><td style=background-color:white;border:none;white-space:nowrap>" . $PlayersLang['Playoff']. " Appearances". "</td>";
	echo "<td  style=padding-right:12px;background-color:white;border:none;font-size:13px;font-weight:bold;text-align:right>" . $TeamCareerSumPlayoffOnly['SumOfPlayoff'] .  "</td>";
	echo "</tr>\n"; /* The \n is for a new line in the HTML Code */	
}

if ($TeamStat['GP'] >= 0 AND $LeagueGeneral['PreSeasonSchedule'] == "False" AND $LeagueGeneral['PlayOffStarted'] == "True"){
	
	#Add Current Year in Career Stat
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
}
if ($TeamCareerSumPlayoffOnly['SumOfGP'] >= 0){
	/* Show TeamCareer Total for Playoff */
	echo "<tr style=border-bottom-style:solid;border-bottom-width:1px;border-bottom-color:#ececec><td style=background-color:white;border:none;white-space:nowrap>" . $PlayersLang['Playoff']. " Record (W-L)". "</td>";
	echo "<td  style=padding-right:12px;background-color:white;border:none;font-size:13px;font-weight:bold;text-align:right>" . ($TeamCareerSumPlayoffOnly['SumOfW'] + $TeamCareerSumPlayoffOnly['SumOfOTW'] + $TeamCareerSumPlayoffOnly['SumOfSOW'])  . "-" . $TeamCareerSumPlayoffOnly['SumOfL'].   "</td>";
	echo "</tr>\n"; /* The \n is for a new line in the HTML Code */	
}
if ($TeamCareerSumPlayoffCup['SumOfCup'] >= 0){
	/* Show TeamCareer Total for Playoff */
	echo "<tr><td style=background-color:white;border:none;white-space:nowrap>" . "Stanley Cup". "</td>";
	echo "<td  style=padding-right:12px;background-color:white;border:none;font-size:13px;font-weight:bold;text-align:right>" . $TeamCareerSumPlayoffCup['SumOfCup'] .  "</td>";
	echo "</tr>\n"; /* The \n is for a new line in the HTML Code */	
}

echo "</tbody></table>";
?>

<table style="border-collapse:collapse" class="STHSIndex_Top5Table">



<h2 colspan="2" style="font-size:16px;padding-bottom:16px;font-weight:bold;border-top: 2px solid #ececec;padding-top: 8px;">Awards & Achievements</h2></tr>


<table style="margin-top:12px;border-collapse:collapse" class="STHSIndex_Top5Table">
<div class="slideshow-container">

<div class="mySlides fade">
	<img src="http://profinhl.cz/images/SCwinners/2020.png" style="width:40%;display:block;margin-left:auto;margin-right:auto">
</div>

<div class="mySlides fade">
	<img src="http://profinhl.cz/images/SCwinners/2016.png" style="width:40%;display:block;margin-left:auto;margin-right:auto">  
</div>


<div class="mySlides fade">
	<img src="http://profinhl.cz/images/SCwinners/2014.png" style="width:40%;display:block;margin-left:auto;margin-right:auto">  
</div>

<div class="mySlides fade">
	<img src="http://profinhl.cz/images/SCwinners/2010.png" style="width:40%;display:block;margin-left:auto;margin-right:auto">  
</div>

</div>
<br>

<div style="text-align:center">
  <span class="dot"></span> 
  <span class="dot"></span>
	<span class="dot"></span> 
<span class="dot"></span> 
<span class="dot"></span> 
<span class="dot"></span> 
<span class="dot"></span> 
<span class="dot"></span> 
<span class="dot"></span> 
<span class="dot"></span> 
<span class="dot"></span> 
</div>

<script>
var slideIndex = 0;
showSlides();

function showSlides() {
  var i;
  var slides = document.getElementsByClassName("mySlides");
  var dots = document.getElementsByClassName("dot");
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";  
  }
  slideIndex++;
  if (slideIndex > slides.length) {slideIndex = 1}    
  for (i = 0; i < dots.length; i++) {
    dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex-1].style.display = "block";  
  dots[slideIndex-1].className += " active";
  setTimeout(showSlides, 2000); // Change image every 2 seconds
}
</script>
</table>


<table style="border-collapse:collapse" class="STHSIndex_Top5Table">

<tr><h2 colspan="2" style="font-size:14px;padding-bottom:0px;padding-top:10px;font-weight:bold">Presidents Trophy</h2></tr>

<td rowspan="5" style="background:white;border:none;width:110px;text-align:center"><?php	echo "<img src=/images/LogoTeams/Awards/presidentstrophy.png style=\"height:120px;vertical-align:middle;height:78px\" alt=\"" . "" . "\"  "."";?>
</td>
<?php
	echo "<tr style=border-bottom-style:solid;border-bottom-width:1px;border-bottom-color:#ececec><td style=background-color:white;border:none;font-weight:bold>" .""."</td><td  style=padding-right:12px;background-color:white;border:none;font-size:13px;font-weight:bold;text-align:right>" . "2018/19" . "</td></tr>\n";
?>

<?php
	echo "<tr style=border-bottom-style:solid;border-bottom-width:1px;border-bottom-color:#ececec><td style=background-color:white;font-weight:bold;border:none>" .""."</td><td  style=padding-right:12px;background-color:white;border:none;font-size:13px;font-weight:bold;text-align:right>" . "2019/20" . "</td></tr>\n";
?>

</tr>
</table>

<table style="border-collapse:collapse" class="STHSIndex_Top5Table">

<tr><h2 colspan="2" style="font-size:14px;padding-bottom:0px;padding-top:10px;font-weight:bold">Clarence S. Campbell Trophy</h2></tr>

<td rowspan="5" style="background:white;border:none;width:110px;text-align:center"><?php	echo "<img src=/images/LogoTeams/Awards/clarence.png style=\"height:120px;vertical-align:middle;height:78px\" alt=\"" . "" . "\"  "."";?>
</td>
<?php
	echo "<tr style=border-bottom-style:solid;border-bottom-width:1px;border-bottom-color:#ececec><td style=background-color:white;border:none;font-weight:bold>" ."2009/10"."</td><td  style=padding-right:12px;background-color:white;border:none;font-size:13px;font-weight:bold;text-align:right>" . "2013/14" . "</td></tr>\n";
?>

<?php
	echo "<tr style=border-bottom-style:solid;border-bottom-width:1px;border-bottom-color:#ececec><td style=background-color:white;font-weight:bold;border:none>" ."2015/16"."</td><td  style=padding-right:12px;background-color:white;border:none;font-size:13px;font-weight:bold;text-align:right>" . "2017/18" . "</td></tr>\n";
?>
<?php
	echo "<tr style=border-bottom-style:solid;border-bottom-width:1px;border-bottom-color:#ececec><td style=background-color:white;border:none;font-weight:bold>" ."2018/19"."</td><td  style=padding-right:12px;background-color:white;border:none;font-size:13px;font-weight:bold;text-align:right>" . "2019/20" . "</td></tr>\n";
?>

</tr>
</table>





<table style="border-collapse:collapse" class="STHSIndex_Top5Table">

<tr><h2 colspan="2" style="font-size:14px;padding-bottom:0px;padding-top:10px;font-weight:bold">Hart Memorial Trophy</h2></tr>

<td rowspan="5" style="background:white;border:none;width:110px;text-align:center"><?php	echo "<img src=/images/LogoTeams/Awards/harttrophy.png style=\"height:120px;vertical-align:middle;height:78px\" alt=\"" . "" . "\"  "."";?>
</td>
<?php
	echo "<tr style=border-bottom-style:solid;border-bottom-width:1px;border-bottom-color:#ececec><td style=background-color:white;border:none;font-weight:bold>" ."Auston Matthews"."</td><td  style=padding-right:12px;background-color:white;border:none;font-size:13px;font-weight:bold;text-align:right>" . "2018/19" . "</td></tr>\n";
?>

<?php
	echo "<tr style=border-bottom-style:solid;border-bottom-width:1px;border-bottom-color:#ececec><td style=background-color:white;font-weight:bold;border:none>" ."Brayden Point"."</td><td  style=padding-right:12px;background-color:white;border:none;font-size:13px;font-weight:bold;text-align:right>" . "2019/20" . "</td></tr>\n";
?>

</tr>
</table>



<table style="border-collapse:collapse" class="STHSIndex_Top5Table">

<tr><h2 colspan="2" style="font-size:14px;padding-bottom:0px;padding-top:10px;font-weight:bold">Conn Smythe Trophy</h2></tr>

<td rowspan="5" style="background:white;border:none;width:110px;text-align:center"><?php	echo "<img src=/images/LogoTeams/Awards/connsmythe.png style=\"height:120px;vertical-align:middle;height:78px\" alt=\"" . "" . "\"  "."";?>
</td>
<?php
	echo "<tr style=border-bottom-style:solid;border-bottom-width:1px;border-bottom-color:#ececec><td style=background-color:white;border:none;font-weight:bold>" ."Manny Legace"."</td><td  style=padding-right:12px;background-color:white;border:none;font-size:13px;font-weight:bold;text-align:right>" . "2009/10" . "</td></tr>\n";
?>

<?php
	echo "<tr style=border-bottom-style:solid;border-bottom-width:1px;border-bottom-color:#ececec><td style=background-color:white;font-weight:bold;border:none>" ."Henrik Lundqvist"."</td><td  style=padding-right:12px;background-color:white;border:none;font-size:13px;font-weight:bold;text-align:right>" . "2013/14" . "</td></tr>\n";
?>
<?php
	echo "<tr style=border-bottom-style:solid;border-bottom-width:1px;border-bottom-color:#ececec><td style=background-color:white;font-weight:bold;border:none>" ."Erik Karlsson"."</td><td  style=padding-right:12px;background-color:white;border:none;font-size:13px;font-weight:bold;text-align:right>" . "2015/16" . "</td></tr>\n";
?>
<?php
	echo "<tr style=border-bottom-style:solid;border-bottom-width:1px;border-bottom-color:#ececec><td style=background-color:white;font-weight:bold;border:none>" ."Brayden Point"."</td><td  style=padding-right:12px;background-color:white;border:none;font-size:13px;font-weight:bold;text-align:right>" . "2019/20" . "</td></tr>\n";
?>

</tr>
</table>


<table style="border-collapse:collapse" class="STHSIndex_Top5Table">

<tr><h2 colspan="2" style="font-size:14px;padding-bottom:0px;padding-top:10px;font-weight:bold">Art Ross Trophy</h2></tr>

<td rowspan="5" style="background:white;border:none;width:110px;text-align:center"><?php	echo "<img src=/images/LogoTeams/Awards/ArtRoss.jpg style=\"height:120px;vertical-align:middle;height:78px\" alt=\"" . "" . "\"  "."";?>
</td>
<?php
	echo "<tr style=border-bottom-style:solid;border-bottom-width:1px;border-bottom-color:#ececec><td style=background-color:white;border:none;font-weight:bold>" ."Auston Matthews"."</td><td  style=padding-right:12px;background-color:white;border:none;font-size:13px;font-weight:bold;text-align:right>" . "2018/19" . "</td></tr>\n";
?>


<table style="border-collapse:collapse" class="STHSIndex_Top5Table">

<tr><h2 colspan="2" style="font-size:14px;padding-bottom:0px;padding-top:10px;font-weight:bold">Maurice Rocket Richard</h2></tr>

<td rowspan="5" style="background:white;border:none;width:110px;text-align:center"><?php	echo "<img src=/images/LogoTeams/Awards/mauricerichard.png style=\"height:120px;vertical-align:middle;height:78px\" alt=\"" . "" . "\"  "."";?>
</td>
<?php
	echo "<tr style=border-bottom-style:solid;border-bottom-width:1px;border-bottom-color:#ececec><td style=background-color:white;border:none;font-weight:bold>" ."Auston Matthews"."</td><td  style=padding-right:12px;background-color:white;border:none;font-size:13px;font-weight:bold;text-align:right>" . "2018/19" . "</td></tr>\n";
?>

<?php
	echo "<tr style=border-bottom-style:solid;border-bottom-width:1px;border-bottom-color:#ececec><td style=background-color:white;border:none;font-weight:bold>" ."Auston Matthews"."</td><td  style=padding-right:12px;background-color:white;border:none;font-size:13px;font-weight:bold;text-align:right>" . "2019/20" . "</td></tr>\n";
?>

</tr>
</table>



<table style="border-collapse:collapse" class="STHSIndex_Top5Table">

<tr><h2 colspan="2" style="font-size:14px;padding-bottom:0px;padding-top:10px;font-weight:bold">James Norris Memorial Trophy</h2></tr>

<td rowspan="5" style="background:white;border:none;width:110px;text-align:center"><?php	echo "<img src=/images/LogoTeams/Awards/jamesnorristrophy.png style=\"height:120px;vertical-align:middle;height:78px\" alt=\"" . "" . "\"  "."";?>
</td>
<?php
	echo "<tr style=border-bottom-style:solid;border-bottom-width:1px;border-bottom-color:#ececec><td style=background-color:white;border:none;font-weight:bold>" ."Erik Karlsson"."</td><td  style=padding-right:12px;background-color:white;border:none;font-size:13px;font-weight:bold;text-align:right>" . "2012/13" . "</td></tr>\n";
?>
<?php
	echo "<tr style=border-bottom-style:solid;border-bottom-width:1px;border-bottom-color:#ececec><td style=background-color:white;border:none;font-weight:bold>" ."Erik Karlsson"."</td><td  style=padding-right:12px;background-color:white;border:none;font-size:13px;font-weight:bold;text-align:right>" . "2016/17" . "</td></tr>\n";
?>

<?php
	echo "<tr style=border-bottom-style:solid;border-bottom-width:1px;border-bottom-color:#ececec><td style=background-color:white;border:none;font-weight:bold>" ."Erik Karlsson"."</td><td  style=padding-right:12px;background-color:white;border:none;font-size:13px;font-weight:bold;text-align:right>" . "2015/16" . "</td></tr>\n";
?>


</tr>
</table>

<table style="border-collapse:collapse" class="STHSIndex_Top5Table">

<tr><h2 colspan="2" style="font-size:14px;padding-bottom:0px;padding-top:10px;font-weight:bold">Frank J. Selke Trophy</h2></tr>

<td rowspan="5" style="background:white;border:none;width:110px;text-align:center"><?php	echo "<img src=/images/LogoTeams/Awards/frankselketrophy.png style=\"height:120px;vertical-align:middle;height:78px\" alt=\"" . "" . "\"  "."";?>
</td>
<?php
	echo "<tr style=border-bottom-style:solid;border-bottom-width:1px;border-bottom-color:#ececec><td style=background-color:white;border:none;font-weight:bold>" ."Ryan Getzlaf"."</td><td  style=padding-right:12px;background-color:white;border:none;font-size:13px;font-weight:bold;text-align:right>" . "2013/14" . "</td></tr>\n";
?>
<?php
	echo "<tr style=border-bottom-style:solid;border-bottom-width:1px;border-bottom-color:#ececec><td style=background-color:white;border:none;font-weight:bold>" ."Ryan Getzlaf"."</td><td  style=padding-right:12px;background-color:white;border:none;font-size:13px;font-weight:bold;text-align:right>" . "2014/15" . "</td></tr>\n";
?>


</tr>
</table>

<table style="border-collapse:collapse" class="STHSIndex_Top5Table">

<tr><h2 colspan="2" style="font-size:14px;padding-bottom:0px;padding-top:10px;font-weight:bold">GM of the Year Trophy</h2></tr>

<td rowspan="5" style="background:white;border:none;width:110px;text-align:center"><?php	echo "<img src=/images/LogoTeams/Awards/gmoftheyear.png style=\"height:120px;vertical-align:middle;height:78px\" alt=\"" . "" . "\"  "."";?>
</td>
<?php
	echo "<tr style=border-bottom-style:solid;border-bottom-width:1px;border-bottom-color:#ececec><td style=background-color:white;border:none;font-weight:bold>" ."Michal Polak"."</td><td  style=padding-right:12px;background-color:white;border:none;font-size:13px;font-weight:bold;text-align:right>" . "2009/10" . "</td></tr>\n";
?>
<?php
	echo "<tr style=border-bottom-style:solid;border-bottom-width:1px;border-bottom-color:#ececec><td style=background-color:white;border:none;font-weight:bold>" ."Michal Polak"."</td><td  style=padding-right:12px;background-color:white;border:none;font-size:13px;font-weight:bold;text-align:right>" . "2013/14" . "</td></tr>\n";
?>
<?php
	echo "<tr style=border-bottom-style:solid;border-bottom-width:1px;border-bottom-color:#ececec><td style=background-color:white;border:none;font-weight:bold>" ."David Goldmann"."</td><td  style=padding-right:12px;background-color:white;border:none;font-size:13px;font-weight:bold;text-align:right>" . "2019/20" . "</td></tr>\n";
?>


</tr>
</table>








</table>




</tr>
</table>
</td>
</tr>
</table>

</table>
</div>

</div>

<br></br>
<br></br>
<br></br>


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
  <?php if ($TeamCareerStatFound == true){echo "\$(\".STHSPHPTeam_TeamCareerStat\").tablesorter({widgets: ['staticRow', 'columnSelector','filter'], widgetOptions : {columnSelector_container : \$('#tablesorter_ColumnSelector11'), columnSelector_layout : '<label><input type=\"checkbox\">{name}</label>', columnSelector_name  : 'title', columnSelector_mediaquery: true, columnSelector_mediaqueryName: 'Automatic', columnSelector_mediaqueryState: true, columnSelector_mediaqueryHidden: true, columnSelector_breakpoints : [ '50em', '60em', '70em', '80em', '90em', '95em' ],filter_columnFilters: false,}});";}?>
});
</script>
</div>

