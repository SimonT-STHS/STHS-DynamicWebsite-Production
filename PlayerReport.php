<!DOCTYPE html>
<?php include "Header.php";?>
<?php
/*
Syntax to call this webpage should be PlayersStat.php?Player=2 where only the number change and it's based on the UniqueID of players.
*/
$Active = 1; /* Show Webpage Top Menu */
$Player = (integer)0;
$Query = (string)"";
$PlayerName = $PlayersLang['IncorrectPlayer'];
$LeagueName = (string)"";
$PlayerCareerStatFound = (boolean)false;
$PlayerProCareerSeason = Null;
$PlayerProCareerPlayoff = Null;
$PlayerProCareerSumSeasonOnly = Null;
$PlayerProCareerSumPlayoffOnly = Null;
$PlayerFarmCareerSeason = Null;
$PlayerFarmCareerPlayoff = Null;
$PlayerFarmCareerSumSeasonOnly = Null;
$PlayerFarmCareerSumPlayoffOnly = Null;
if(isset($_GET['Player'])){$Player = filter_var($_GET['Player'], FILTER_SANITIZE_NUMBER_INT);} 

If (file_exists($DatabaseFile) == false){
	$Player = 0;
	$PlayerName = $DatabaseNotFound;
}else{
	$db = new SQLite3($DatabaseFile);
}
If ($Player == 0){
	$PlayerInfo = Null;
	$PlayerProStat = Null;
	$PlayerFarmStat = Null;	
	$LeagueOutputOption = Null;
	echo "<style type=\"text/css\">.STHSPHPPlayerStat_Main {display:none;}</style>";
}else{
	$Query = "SELECT count(*) AS count FROM PlayerInfo WHERE Number = " . $Player;
	$Result = $db->querySingle($Query,true);
	If ($Result['count'] == 1){
		$Query = "SELECT * FROM PlayerInfo WHERE Number = " . $Player;
		$PlayerInfo = $db->querySingle($Query,true);
		$Query = "SELECT * FROM PlayerProStat WHERE Number = " . $Player;
		$PlayerProStat = $db->querySingle($Query,true);
		$Query = "SELECT * FROM PlayerFarmStat WHERE Number = " . $Player;
		$PlayerFarmStat = $db->querySingle($Query,true);
		$Query = "Select Name, OutputName from LeagueGeneral";
		$LeagueGeneral = $db->querySingle($Query,true);	
		$Query = "Select PlayersMugShotBaseURL, PlayersMugShotFileExtension from LeagueOutputOption";
		$LeagueOutputOption = $db->querySingle($Query,true);				
		
		$LeagueName = $LeagueGeneral['Name'];
		$PlayerName = $PlayerInfo['Name'];	
		If (file_exists($CareerStatDatabaseFile) == true){ /* CareerStat */
			$CareerStatdb = new SQLite3($CareerStatDatabaseFile);
			
			$Query = "SELECT PlayerProStatCareer.*, ROUND((CAST(PlayerProStatCareer.G AS REAL) / (PlayerProStatCareer.Shots))*100,2) AS ShotsPCT, ROUND((CAST(PlayerProStatCareer.SecondPlay AS REAL) / 60 / (PlayerProStatCareer.GP)),2) AS AMG,ROUND((CAST(PlayerProStatCareer.FaceOffWon AS REAL) / (PlayerProStatCareer.FaceOffTotal))*100,2) as FaceoffPCT,ROUND((CAST(PlayerProStatCareer.P AS REAL) / (PlayerProStatCareer.SecondPlay) * 60 * 20),2) AS P20 FROM PlayerProStatCareer WHERE Playoff = 'False' AND (UniqueID = " . $PlayerInfo['UniqueID'] . " OR Name = '" . $PlayerName . "') ORDER BY PlayerProStatCareer.Year";
			$PlayerProCareerSeason = $CareerStatdb->query($Query);
			$Query = "SELECT PlayerProStatCareer.*, ROUND((CAST(PlayerProStatCareer.G AS REAL) / (PlayerProStatCareer.Shots))*100,2) AS ShotsPCT, ROUND((CAST(PlayerProStatCareer.SecondPlay AS REAL) / 60 / (PlayerProStatCareer.GP)),2) AS AMG,ROUND((CAST(PlayerProStatCareer.FaceOffWon AS REAL) / (PlayerProStatCareer.FaceOffTotal))*100,2) as FaceoffPCT,ROUND((CAST(PlayerProStatCareer.P AS REAL) / (PlayerProStatCareer.SecondPlay) * 60 * 20),2) AS P20 FROM PlayerProStatCareer WHERE Playoff = 'True' AND (UniqueID = " . $PlayerInfo['UniqueID'] . " OR Name = '" . $PlayerName . "') ORDER BY PlayerProStatCareer.Year";
			$PlayerProCareerPlayoff = $CareerStatdb->query($Query);	
			$Query = "SELECT Sum(PlayerProStatCareer.GP) AS SumOfGP, Sum(PlayerProStatCareer.Shots) AS SumOfShots, Sum(PlayerProStatCareer.G) AS SumOfG, Sum(PlayerProStatCareer.A) AS SumOfA, Sum(PlayerProStatCareer.P) AS SumOfP, Sum(PlayerProStatCareer.PlusMinus) AS SumOfPlusMinus, Sum(PlayerProStatCareer.Pim) AS SumOfPim, Sum(PlayerProStatCareer.Pim5) AS SumOfPim5, Sum(PlayerProStatCareer.ShotsBlock) AS SumOfShotsBlock, Sum(PlayerProStatCareer.OwnShotsBlock) AS SumOfOwnShotsBlock, Sum(PlayerProStatCareer.OwnShotsMissGoal) AS SumOfOwnShotsMissGoal, Sum(PlayerProStatCareer.Hits) AS SumOfHits, Sum(PlayerProStatCareer.HitsTook) AS SumOfHitsTook, Sum(PlayerProStatCareer.GW) AS SumOfGW, Sum(PlayerProStatCareer.GT) AS SumOfGT, Sum(PlayerProStatCareer.FaceOffWon) AS SumOfFaceOffWon, Sum(PlayerProStatCareer.FaceOffTotal) AS SumOfFaceOffTotal, Sum(PlayerProStatCareer.PenalityShotsScore) AS SumOfPenalityShotsScore, Sum(PlayerProStatCareer.PenalityShotsTotal) AS SumOfPenalityShotsTotal, Sum(PlayerProStatCareer.EmptyNetGoal) AS SumOfEmptyNetGoal, Sum(PlayerProStatCareer.SecondPlay) AS SumOfSecondPlay, Sum(PlayerProStatCareer.HatTrick) AS SumOfHatTrick, Sum(PlayerProStatCareer.PPG) AS SumOfPPG, Sum(PlayerProStatCareer.PPA) AS SumOfPPA, Sum(PlayerProStatCareer.PPP) AS SumOfPPP, Sum(PlayerProStatCareer.PPShots) AS SumOfPPShots, Sum(PlayerProStatCareer.PPSecondPlay) AS SumOfPPSecondPlay, Sum(PlayerProStatCareer.PKG) AS SumOfPKG, Sum(PlayerProStatCareer.PKA) AS SumOfPKA, Sum(PlayerProStatCareer.PKP) AS SumOfPKP, Sum(PlayerProStatCareer.PKShots) AS SumOfPKShots, Sum(PlayerProStatCareer.PKSecondPlay) AS SumOfPKSecondPlay, Sum(PlayerProStatCareer.GiveAway) AS SumOfGiveAway, Sum(PlayerProStatCareer.TakeAway) AS SumOfTakeAway, Sum(PlayerProStatCareer.PuckPossesionTime) AS SumOfPuckPossesionTime, Sum(PlayerProStatCareer.FightW) AS SumOfFightW, Sum(PlayerProStatCareer.FightL) AS SumOfFightL, Sum(PlayerProStatCareer.FightT) AS SumOfFightT, Sum(PlayerProStatCareer.Star1) AS SumOfStar1, Sum(PlayerProStatCareer.Star2) AS SumOfStar2, Sum(PlayerProStatCareer.Star3) AS SumOfStar3, ROUND((CAST(Sum(PlayerProStatCareer.G) AS REAL) / (Sum(PlayerProStatCareer.Shots)))*100,2) AS SumOfShotsPCT, ROUND((CAST(Sum(PlayerProStatCareer.SecondPlay) AS REAL) / 60 / (Sum(PlayerProStatCareer.GP))),2) AS SumOfAMG, ROUND((CAST(Sum(PlayerProStatCareer.FaceOffWon) AS REAL) / (Sum(PlayerProStatCareer.FaceOffTotal)))*100,2) as SumOfFaceoffPCT, ROUND((CAST(Sum(PlayerProStatCareer.P) AS REAL) / (Sum(PlayerProStatCareer.SecondPlay)) * 60 * 20),2) AS SumOfP20 FROM PlayerProStatCareer WHERE Playoff = 'False' AND (UniqueID = " . $PlayerInfo['UniqueID'] . " OR Name = '" . $PlayerName . "')";
			$PlayerProCareerSumSeasonOnly = $CareerStatdb->querySingle($Query,true);		
			$Query = "SELECT Sum(PlayerProStatCareer.GP) AS SumOfGP, Sum(PlayerProStatCareer.Shots) AS SumOfShots, Sum(PlayerProStatCareer.G) AS SumOfG, Sum(PlayerProStatCareer.A) AS SumOfA, Sum(PlayerProStatCareer.P) AS SumOfP, Sum(PlayerProStatCareer.PlusMinus) AS SumOfPlusMinus, Sum(PlayerProStatCareer.Pim) AS SumOfPim, Sum(PlayerProStatCareer.Pim5) AS SumOfPim5, Sum(PlayerProStatCareer.ShotsBlock) AS SumOfShotsBlock, Sum(PlayerProStatCareer.OwnShotsBlock) AS SumOfOwnShotsBlock, Sum(PlayerProStatCareer.OwnShotsMissGoal) AS SumOfOwnShotsMissGoal, Sum(PlayerProStatCareer.Hits) AS SumOfHits, Sum(PlayerProStatCareer.HitsTook) AS SumOfHitsTook, Sum(PlayerProStatCareer.GW) AS SumOfGW, Sum(PlayerProStatCareer.GT) AS SumOfGT, Sum(PlayerProStatCareer.FaceOffWon) AS SumOfFaceOffWon, Sum(PlayerProStatCareer.FaceOffTotal) AS SumOfFaceOffTotal, Sum(PlayerProStatCareer.PenalityShotsScore) AS SumOfPenalityShotsScore, Sum(PlayerProStatCareer.PenalityShotsTotal) AS SumOfPenalityShotsTotal, Sum(PlayerProStatCareer.EmptyNetGoal) AS SumOfEmptyNetGoal, Sum(PlayerProStatCareer.SecondPlay) AS SumOfSecondPlay, Sum(PlayerProStatCareer.HatTrick) AS SumOfHatTrick, Sum(PlayerProStatCareer.PPG) AS SumOfPPG, Sum(PlayerProStatCareer.PPA) AS SumOfPPA, Sum(PlayerProStatCareer.PPP) AS SumOfPPP, Sum(PlayerProStatCareer.PPShots) AS SumOfPPShots, Sum(PlayerProStatCareer.PPSecondPlay) AS SumOfPPSecondPlay, Sum(PlayerProStatCareer.PKG) AS SumOfPKG, Sum(PlayerProStatCareer.PKA) AS SumOfPKA, Sum(PlayerProStatCareer.PKP) AS SumOfPKP, Sum(PlayerProStatCareer.PKShots) AS SumOfPKShots, Sum(PlayerProStatCareer.PKSecondPlay) AS SumOfPKSecondPlay, Sum(PlayerProStatCareer.GiveAway) AS SumOfGiveAway, Sum(PlayerProStatCareer.TakeAway) AS SumOfTakeAway, Sum(PlayerProStatCareer.PuckPossesionTime) AS SumOfPuckPossesionTime, Sum(PlayerProStatCareer.FightW) AS SumOfFightW, Sum(PlayerProStatCareer.FightL) AS SumOfFightL, Sum(PlayerProStatCareer.FightT) AS SumOfFightT, Sum(PlayerProStatCareer.Star1) AS SumOfStar1, Sum(PlayerProStatCareer.Star2) AS SumOfStar2, Sum(PlayerProStatCareer.Star3) AS SumOfStar3, ROUND((CAST(Sum(PlayerProStatCareer.G) AS REAL) / (Sum(PlayerProStatCareer.Shots)))*100,2) AS SumOfShotsPCT, ROUND((CAST(Sum(PlayerProStatCareer.SecondPlay) AS REAL) / 60 / (Sum(PlayerProStatCareer.GP))),2) AS SumOfAMG, ROUND((CAST(Sum(PlayerProStatCareer.FaceOffWon) AS REAL) / (Sum(PlayerProStatCareer.FaceOffTotal)))*100,2) as SumOfFaceoffPCT, ROUND((CAST(Sum(PlayerProStatCareer.P) AS REAL) / (Sum(PlayerProStatCareer.SecondPlay)) * 60 * 20),2) AS SumOfP20 FROM PlayerProStatCareer WHERE Playoff = 'True' AND (UniqueID = " . $PlayerInfo['UniqueID'] . " OR Name = '" . $PlayerName . "')";
			$PlayerProCareerSumPlayoffOnly = $CareerStatdb->querySingle($Query,true);				
			
			$Query = "SELECT PlayerFarmStatCareer.*, ROUND((CAST(PlayerFarmStatCareer.G AS REAL) / (PlayerFarmStatCareer.Shots))*100,2) AS ShotsPCT, ROUND((CAST(PlayerFarmStatCareer.SecondPlay AS REAL) / 60 / (PlayerFarmStatCareer.GP)),2) AS AMG,ROUND((CAST(PlayerFarmStatCareer.FaceOffWon AS REAL) / (PlayerFarmStatCareer.FaceOffTotal))*100,2) as FaceoffPCT,ROUND((CAST(PlayerFarmStatCareer.P AS REAL) / (PlayerFarmStatCareer.SecondPlay) * 60 * 20),2) AS P20 FROM PlayerFarmStatCareer WHERE Playoff = 'False' AND (UniqueID = " . $PlayerInfo['UniqueID'] . " OR Name = '" . $PlayerName . "') ORDER BY PlayerFarmStatCareer.Year";
			$PlayerFarmCareerSeason = $CareerStatdb->query($Query);
			$Query = "SELECT PlayerFarmStatCareer.*, ROUND((CAST(PlayerFarmStatCareer.G AS REAL) / (PlayerFarmStatCareer.Shots))*100,2) AS ShotsPCT, ROUND((CAST(PlayerFarmStatCareer.SecondPlay AS REAL) / 60 / (PlayerFarmStatCareer.GP)),2) AS AMG,ROUND((CAST(PlayerFarmStatCareer.FaceOffWon AS REAL) / (PlayerFarmStatCareer.FaceOffTotal))*100,2) as FaceoffPCT,ROUND((CAST(PlayerFarmStatCareer.P AS REAL) / (PlayerFarmStatCareer.SecondPlay) * 60 * 20),2) AS P20 FROM PlayerFarmStatCareer WHERE Playoff = 'True' AND (UniqueID = " . $PlayerInfo['UniqueID'] . " OR Name = '" . $PlayerName . "') ORDER BY PlayerFarmStatCareer.Year";
			$PlayerFarmCareerPlayoff = $CareerStatdb->query($Query);	
			$Query = "SELECT Sum(PlayerFarmStatCareer.GP) AS SumOfGP, Sum(PlayerFarmStatCareer.Shots) AS SumOfShots, Sum(PlayerFarmStatCareer.G) AS SumOfG, Sum(PlayerFarmStatCareer.A) AS SumOfA, Sum(PlayerFarmStatCareer.P) AS SumOfP, Sum(PlayerFarmStatCareer.PlusMinus) AS SumOfPlusMinus, Sum(PlayerFarmStatCareer.Pim) AS SumOfPim, Sum(PlayerFarmStatCareer.Pim5) AS SumOfPim5, Sum(PlayerFarmStatCareer.ShotsBlock) AS SumOfShotsBlock, Sum(PlayerFarmStatCareer.OwnShotsBlock) AS SumOfOwnShotsBlock, Sum(PlayerFarmStatCareer.OwnShotsMissGoal) AS SumOfOwnShotsMissGoal, Sum(PlayerFarmStatCareer.Hits) AS SumOfHits, Sum(PlayerFarmStatCareer.HitsTook) AS SumOfHitsTook, Sum(PlayerFarmStatCareer.GW) AS SumOfGW, Sum(PlayerFarmStatCareer.GT) AS SumOfGT, Sum(PlayerFarmStatCareer.FaceOffWon) AS SumOfFaceOffWon, Sum(PlayerFarmStatCareer.FaceOffTotal) AS SumOfFaceOffTotal, Sum(PlayerFarmStatCareer.PenalityShotsScore) AS SumOfPenalityShotsScore, Sum(PlayerFarmStatCareer.PenalityShotsTotal) AS SumOfPenalityShotsTotal, Sum(PlayerFarmStatCareer.EmptyNetGoal) AS SumOfEmptyNetGoal, Sum(PlayerFarmStatCareer.SecondPlay) AS SumOfSecondPlay, Sum(PlayerFarmStatCareer.HatTrick) AS SumOfHatTrick, Sum(PlayerFarmStatCareer.PPG) AS SumOfPPG, Sum(PlayerFarmStatCareer.PPA) AS SumOfPPA, Sum(PlayerFarmStatCareer.PPP) AS SumOfPPP, Sum(PlayerFarmStatCareer.PPShots) AS SumOfPPShots, Sum(PlayerFarmStatCareer.PPSecondPlay) AS SumOfPPSecondPlay, Sum(PlayerFarmStatCareer.PKG) AS SumOfPKG, Sum(PlayerFarmStatCareer.PKA) AS SumOfPKA, Sum(PlayerFarmStatCareer.PKP) AS SumOfPKP, Sum(PlayerFarmStatCareer.PKShots) AS SumOfPKShots, Sum(PlayerFarmStatCareer.PKSecondPlay) AS SumOfPKSecondPlay, Sum(PlayerFarmStatCareer.GiveAway) AS SumOfGiveAway, Sum(PlayerFarmStatCareer.TakeAway) AS SumOfTakeAway, Sum(PlayerFarmStatCareer.PuckPossesionTime) AS SumOfPuckPossesionTime, Sum(PlayerFarmStatCareer.FightW) AS SumOfFightW, Sum(PlayerFarmStatCareer.FightL) AS SumOfFightL, Sum(PlayerFarmStatCareer.FightT) AS SumOfFightT, Sum(PlayerFarmStatCareer.Star1) AS SumOfStar1, Sum(PlayerFarmStatCareer.Star2) AS SumOfStar2, Sum(PlayerFarmStatCareer.Star3) AS SumOfStar3, ROUND((CAST(Sum(PlayerFarmStatCareer.G) AS REAL) / (Sum(PlayerFarmStatCareer.Shots)))*100,2) AS SumOfShotsPCT, ROUND((CAST(Sum(PlayerFarmStatCareer.SecondPlay) AS REAL) / 60 / (Sum(PlayerFarmStatCareer.GP))),2) AS SumOfAMG, ROUND((CAST(Sum(PlayerFarmStatCareer.FaceOffWon) AS REAL) / (Sum(PlayerFarmStatCareer.FaceOffTotal)))*100,2) as SumOfFaceoffPCT, ROUND((CAST(Sum(PlayerFarmStatCareer.P) AS REAL) / (Sum(PlayerFarmStatCareer.SecondPlay)) * 60 * 20),2) AS SumOfP20 FROM PlayerFarmStatCareer WHERE Playoff = 'False' AND (UniqueID = " . $PlayerInfo['UniqueID'] . " OR Name = '" . $PlayerName . "')";
			$PlayerFarmCareerSumSeasonOnly = $CareerStatdb->querySingle($Query,true);		
			$Query = "SELECT Sum(PlayerFarmStatCareer.GP) AS SumOfGP, Sum(PlayerFarmStatCareer.Shots) AS SumOfShots, Sum(PlayerFarmStatCareer.G) AS SumOfG, Sum(PlayerFarmStatCareer.A) AS SumOfA, Sum(PlayerFarmStatCareer.P) AS SumOfP, Sum(PlayerFarmStatCareer.PlusMinus) AS SumOfPlusMinus, Sum(PlayerFarmStatCareer.Pim) AS SumOfPim, Sum(PlayerFarmStatCareer.Pim5) AS SumOfPim5, Sum(PlayerFarmStatCareer.ShotsBlock) AS SumOfShotsBlock, Sum(PlayerFarmStatCareer.OwnShotsBlock) AS SumOfOwnShotsBlock, Sum(PlayerFarmStatCareer.OwnShotsMissGoal) AS SumOfOwnShotsMissGoal, Sum(PlayerFarmStatCareer.Hits) AS SumOfHits, Sum(PlayerFarmStatCareer.HitsTook) AS SumOfHitsTook, Sum(PlayerFarmStatCareer.GW) AS SumOfGW, Sum(PlayerFarmStatCareer.GT) AS SumOfGT, Sum(PlayerFarmStatCareer.FaceOffWon) AS SumOfFaceOffWon, Sum(PlayerFarmStatCareer.FaceOffTotal) AS SumOfFaceOffTotal, Sum(PlayerFarmStatCareer.PenalityShotsScore) AS SumOfPenalityShotsScore, Sum(PlayerFarmStatCareer.PenalityShotsTotal) AS SumOfPenalityShotsTotal, Sum(PlayerFarmStatCareer.EmptyNetGoal) AS SumOfEmptyNetGoal, Sum(PlayerFarmStatCareer.SecondPlay) AS SumOfSecondPlay, Sum(PlayerFarmStatCareer.HatTrick) AS SumOfHatTrick, Sum(PlayerFarmStatCareer.PPG) AS SumOfPPG, Sum(PlayerFarmStatCareer.PPA) AS SumOfPPA, Sum(PlayerFarmStatCareer.PPP) AS SumOfPPP, Sum(PlayerFarmStatCareer.PPShots) AS SumOfPPShots, Sum(PlayerFarmStatCareer.PPSecondPlay) AS SumOfPPSecondPlay, Sum(PlayerFarmStatCareer.PKG) AS SumOfPKG, Sum(PlayerFarmStatCareer.PKA) AS SumOfPKA, Sum(PlayerFarmStatCareer.PKP) AS SumOfPKP, Sum(PlayerFarmStatCareer.PKShots) AS SumOfPKShots, Sum(PlayerFarmStatCareer.PKSecondPlay) AS SumOfPKSecondPlay, Sum(PlayerFarmStatCareer.GiveAway) AS SumOfGiveAway, Sum(PlayerFarmStatCareer.TakeAway) AS SumOfTakeAway, Sum(PlayerFarmStatCareer.PuckPossesionTime) AS SumOfPuckPossesionTime, Sum(PlayerFarmStatCareer.FightW) AS SumOfFightW, Sum(PlayerFarmStatCareer.FightL) AS SumOfFightL, Sum(PlayerFarmStatCareer.FightT) AS SumOfFightT, Sum(PlayerFarmStatCareer.Star1) AS SumOfStar1, Sum(PlayerFarmStatCareer.Star2) AS SumOfStar2, Sum(PlayerFarmStatCareer.Star3) AS SumOfStar3, ROUND((CAST(Sum(PlayerFarmStatCareer.G) AS REAL) / (Sum(PlayerFarmStatCareer.Shots)))*100,2) AS SumOfShotsPCT, ROUND((CAST(Sum(PlayerFarmStatCareer.SecondPlay) AS REAL) / 60 / (Sum(PlayerFarmStatCareer.GP))),2) AS SumOfAMG, ROUND((CAST(Sum(PlayerFarmStatCareer.FaceOffWon) AS REAL) / (Sum(PlayerFarmStatCareer.FaceOffTotal)))*100,2) as SumOfFaceoffPCT, ROUND((CAST(Sum(PlayerFarmStatCareer.P) AS REAL) / (Sum(PlayerFarmStatCareer.SecondPlay)) * 60 * 20),2) AS SumOfP20 FROM PlayerFarmStatCareer WHERE Playoff = 'True' AND (UniqueID = " . $PlayerInfo['UniqueID'] . " OR Name = '" . $PlayerName . "')";
			$PlayerFarmCareerSumPlayoffOnly = $CareerStatdb->querySingle($Query,true);				
			
			$PlayerCareerStatFound = true;
		}
	}else{
		$PlayerName = $PlayersLang['Playernotfound'];
		$PlayerInfo = Null;
		$PlayerProStat = Null;
		$PlayerFarmStat = Null;	
		echo "<style type=\"text/css\">.STHSPHPPlayerStat_Main {display:none;}</style>";
	}
}

/*
Not Add Yet : URLLink, GameInRow*, Jersey
*/
echo "<title>" . $LeagueName . " - " . $PlayerName . "</title>";
if ($PlayerCareerStatFound == true){
	echo "<style type=\"text/css\">";
	echo "#tablesorter_colSelect2:checked + label {background: #5797d7;  border-color: #555;}";
	echo "#tablesorter_colSelect2:checked ~ #tablesorter_ColumnSelector2 {display: block;}";
	echo "#tablesorter_colSelect3:checked + label {background: #5797d7;  border-color: #555;}";
	echo "#tablesorter_colSelect3:checked ~ #tablesorter_ColumnSelector3 {display: block;}";
	echo "</style>";
}
?>
</head><body>
<?php include "Menu.php";?>
<br />

<div class="STHSPHPPlayerStat_PlayerNameHeader">
<?php
If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $PlayerInfo ['NHLID'] != ""){
	echo "<table class=\"STHSTableFullW STHSPHPPlayerMugShot\"><tr><td>" . $PlayerName . "<br /><br />" . $PlayerInfo['TeamName'];
	echo "</td><td><img src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $PlayerInfo['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $PlayerName . "\" /></td></tr></table>";
}else{
	echo $PlayerName . " - " . $PlayerInfo['TeamName'];
}
 ?></div><br />

<div class="STHSPHPPlayerStat_Main">
<br />

<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $PlayersLang['Position'];?></th>
	<th><?php echo $PlayersLang['Age'];?></th>
	<th><?php echo $PlayersLang['Condition'];?></th>
	<th><?php echo $PlayersLang['Suspension'];?></th>	
	<th><?php echo $PlayersLang['Height'];?></th>
	<th><?php echo $PlayersLang['Weight'];?></th>
</tr><tr>
	<td><?php
	$Position = (string)"";
	if ($PlayerInfo['PosC']== "True"){if ($Position == ""){$Position = $TeamLang['Center'];}else{$Position = $Position . " - " . $TeamLang['Center'];}}
	if ($PlayerInfo['PosLW']== "True"){if ($Position == ""){$Position = $TeamLang['LeftWing'];}else{$Position = $Position . " - " . $TeamLang['LeftWing'];}}
	if ($PlayerInfo['PosRW']== "True"){if ($Position == ""){$Position = $TeamLang['RightWing'];}else{$Position = $Position . " - " . $TeamLang['RightWing'];}}
	if ($PlayerInfo['PosD']== "True"){if ($Position == ""){$Position = $TeamLang['Defense'];}else{$Position = $Position . " - " . $TeamLang['Defense'];}}
	echo $Position;
	?></td>
	<td><?php echo $PlayerInfo['Age']; ?></td>	
	<td><?php if ($PlayerInfo <> Null){echo number_format(str_replace(",",".",$PlayerInfo['ConditionDecimal']),2);} ?></td>	
	<td><?php echo $PlayerInfo['Suspension']; ?></td>	
	<td><?php echo $PlayerInfo['Height']; ?></td>
	<td><?php echo $PlayerInfo['Weight']; ?></td>
</tr>
</table>
<div class="STHSBlankDiv"></div>
<table class="STHSPHPPlayerStat_Table">
<tr>
	<th>CK</th>
	<th>FG</th>
	<th>DI</th>
	<th>SK</th>
	<th>ST</th>
	<th>EN</th>
	<th>DU</th>
	<th>PH</th>
	<th>FO</th>
	<th>PA</th>
	<th>SC</th>
	<th>DF</th>
	<th>PS</th>
	<th>EX</th>
	<th>LD</th>
	<th>PO</th>
	<th>MO</th>
	<th>OV</th>
</tr><tr>
	<td><?php echo $PlayerInfo['CK']; ?></td>
	<td><?php echo $PlayerInfo['FG']; ?></td>
	<td><?php echo $PlayerInfo['DI']; ?></td>
	<td><?php echo $PlayerInfo['SK']; ?></td>
	<td><?php echo $PlayerInfo['ST']; ?></td>
	<td><?php echo $PlayerInfo['EN']; ?></td>
	<td><?php echo $PlayerInfo['DU']; ?></td>
	<td><?php echo $PlayerInfo['PH']; ?></td>
	<td><?php echo $PlayerInfo['FO']; ?></td>
	<td><?php echo $PlayerInfo['PA']; ?></td>
	<td><?php echo $PlayerInfo['SC']; ?></td>
	<td><?php echo $PlayerInfo['DF']; ?></td>
	<td><?php echo $PlayerInfo['PS']; ?></td>
	<td><?php echo $PlayerInfo['EX']; ?></td>
	<td><?php echo $PlayerInfo['LD']; ?></td>
	<td><?php echo $PlayerInfo['PO']; ?></td>
	<td><?php echo $PlayerInfo['MO']; ?></td>
	<td><?php echo $PlayerInfo['Overall']; ?></td> 
</tr>
</table>
<br />

<div class="tabsmain standard"><ul class="tabmain-links">
<li class="activemain"><a href="#tabmain1"><?php echo $PlayersLang['Information'];?></a></li>
<li><a href="#tabmain2"><?php echo $PlayersLang['ProStat'] . $PlayersLang['Basic'];?></a></li>
<li><a href="#tabmain3"><?php echo $PlayersLang['ProStat'] . $PlayersLang['Advanced'];?></a></li>
<li><a href="#tabmain4"><?php echo $PlayersLang['FarmStat'] . $PlayersLang['Basic'];?></a></li>
<li><a href="#tabmain5"><?php echo $PlayersLang['FarmStat'] . $PlayersLang['Advanced'];?></a></li>
<?php if ($PlayerCareerStatFound == true){
	echo "<li><a href=\"#tabmain6\">" . $PlayersLang['CareerProStat'] . "</a></li>";
	echo "<li><a href=\"#tabmain7\">" . $PlayersLang['CareerFarmStat'] . "</a></li>";
}?>
</ul>
<div class="STHSPHPPlayerStat_Tabmain-content">
<div class="tabmain active" id="tabmain1">
<br /><div class="STHSPHPPlayerStat_TabHeader"><?php echo $PlayersLang['Information'];?></div><br />
<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $PlayersLang['Birthday'];?></th>
	<th><?php echo $PlayersLang['Country'];?></th>
	<th><?php echo $PlayersLang['Rookie'];?></th>
	<th><?php echo $PlayersLang['Injury'];?></th>
	<th><?php echo $PlayersLang['HealthLoss'];?></th>
	<th><?php echo $PlayersLang['StarPower'];?></th>	
</tr><tr>
	<td><?php echo $PlayerInfo['AgeDate']; ?></td>
	<td><?php echo $PlayerInfo['Country']; ?></td>
	<td><?php if ($PlayerInfo['Rookie']== "True"){ echo "Yes"; }else{echo "No";} ?></td>		
	<td><?php echo $PlayerInfo['Injury']; ?></td>	
	<td><?php echo $PlayerInfo['NumberOfInjury']; ?></td>
	<td><?php echo $PlayerInfo['StarPower']; ?></td>	
</tr>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $PlayersLang['AvailableforTrade'];?></th>
	<th><?php echo $PlayersLang['NoTrade'];?></th>
	<th><?php echo $PlayersLang['ForceWaiver'];?></th>
	<th><?php echo $PlayersLang['CanPlayPro'];?></th>
	<th><?php echo $PlayersLang['CanPlayFarm'];?></th>
	<th><?php echo $PlayersLang['ExcludefromSalaryCap'];?></th>
	<th><?php echo $PlayersLang['ProSalaryinFarm'];?></th>
</tr><tr>
	<td><?php if ($PlayerInfo['AvailableforTrade']== "True"){ echo "Yes"; }else{echo "No";} ?></td>
	<td><?php if ($PlayerInfo['NoTrade']== "True"){ echo "Yes"; }else{echo "No";} ?></td>
	<td><?php if ($PlayerInfo['ForceWaiver']== "True"){ echo "Yes"; }else{echo "No";} ?></td>
	<td><?php if ($PlayerInfo['CanPlayPro']== "True"){ echo "Yes"; }else{echo "No";} ?></td>
	<td><?php if ($PlayerInfo['CanPlayFarm']== "True"){ echo "Yes"; }else{echo "No";} ?></td>	
	<td><?php if ($PlayerInfo['ExcludeSalaryCap']== "True"){ echo "Yes"; }else{echo "No";} ?></td>
	<td><?php if ($PlayerInfo['ProSalaryinFarm']== "True"){ echo "Yes"; }else{echo "No";} ?></td>
</tr>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $PlayersLang['Contract'];?></th>
	<th><?php echo $PlayersLang['SalaryAverage'];?></th>
	<th><?php echo $PlayersLang['SalaryYear'];?> 1</th>
	<th><?php echo $PlayersLang['SalaryYear'];?> 2</th>
	<th><?php echo $PlayersLang['SalaryYear'];?> 3</th>
	<th><?php echo $PlayersLang['SalaryYear'];?> 4</th>
</tr><tr>
	<td><?php echo $PlayerInfo['Contract']; ?></td>
	<td><?php if ($PlayerInfo <> Null){echo number_format($PlayerInfo['SalaryAverage'],0) . "$";} ?></td>
	<td><?php if ($PlayerInfo <> Null){echo number_format($PlayerInfo['Salary1'],0) . "$";} ?></td>
	<td><?php if ($PlayerInfo <> Null){echo number_format($PlayerInfo['Salary2'],0) . "$";} ?></td>
	<td><?php if ($PlayerInfo <> Null){echo number_format($PlayerInfo['Salary3'],0) . "$";} ?></td>
	<td><?php if ($PlayerInfo <> Null){echo number_format($PlayerInfo['Salary4'],0) . "$";} ?></td>
</tr>
</table>
<div class="STHSBlankDiv"></div>

<br /><br /></div>
<div class="tabmain" id="tabmain2">
<br /><div class="STHSPHPPlayerStat_TabHeader"><?php echo $PlayersLang['ProStat'] . $PlayersLang['Basic'];?></div><br />
<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $GeneralStatLang['GamePlayed'];?></th>
	<th><?php echo $GeneralStatLang['Goals'];?></th>
	<th><?php echo $GeneralStatLang['Assists'];?></th>
	<th><?php echo $GeneralStatLang['Points'];?></th>
	<th><?php echo $GeneralStatLang['PlusMinus'];?></th>
	<th><?php echo $GeneralStatLang['PenaltyMinutes'];?></th>
	<th><?php echo $GeneralStatLang['MinutesPlayed'];?></th>
	<th><?php echo $GeneralStatLang['AverageMinutesPlayedperGame'];?></th>
</tr><tr>
	<td><?php echo $PlayerProStat['GP']; ?></td>
	<td><?php echo $PlayerProStat['G']; ?></td>
	<td><?php echo $PlayerProStat['A']; ?></td>
	<td><?php echo $PlayerProStat['P']; ?></td>
	<td><?php echo $PlayerProStat['PlusMinus']; ?></td>
	<td><?php echo $PlayerProStat['Pim']; ?></td>
	<td><?php if ($PlayerProStat <> Null){echo Floor($PlayerProStat['SecondPlay']/60);} ?></td>
	<td><?php if ($PlayerProStat <> Null){if ($PlayerProStat['GP'] > "0"){echo number_format($PlayerProStat['SecondPlay']/ 60 /$PlayerProStat['GP'],2 ); } else {echo "0";}}?></td>			
</tr>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $GeneralStatLang['MajorPenaltyMinutes'];?></th>
	<th><?php echo $GeneralStatLang['Hits'];?></th>
	<th><?php echo $GeneralStatLang['HitsReceived'];?></th>
	<th><?php echo $GeneralStatLang['Shots'];?></th>
	<th><?php echo $GeneralStatLang['OwnShotsBlock'];?></th>
	<th><?php echo $GeneralStatLang['OwnShotsMiss'];?></th>
	<th><?php echo $GeneralStatLang['ShootingPercentage'];?></th>
	<th><?php echo $GeneralStatLang['ShotsBlock'];?></th>	
</tr><tr>
	<td><?php echo $PlayerProStat['Pim5']; ?></td>
	<td><?php echo $PlayerProStat['Hits']; ?></td>
	<td><?php echo $PlayerProStat['HitsTook']; ?></td>
	<td><?php echo $PlayerProStat['Shots']; ?></td>
	<td><?php echo $PlayerProStat['OwnShotsBlock']; ?></td>
	<td><?php echo $PlayerProStat['OwnShotsMissGoal']; ?></td>
	<td><?php if ($PlayerProStat <> Null){if ($PlayerProStat['Shots'] > "0"){echo sprintf("%.2f%%", $PlayerProStat['G'] / $PlayerProStat['Shots'] *100 ); } else {echo "0%";}}?></td>
	<td><?php echo $PlayerProStat['ShotsBlock']; ?></td>		
</tr>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $GeneralStatLang['PowerPlay'] . $GeneralStatLang['Goals'];?></th>
	<th><?php echo $GeneralStatLang['PowerPlay'] . $GeneralStatLang['Assists'];?></th>
	<th><?php echo $GeneralStatLang['PowerPlay'] . $GeneralStatLang['Points'];?></th>
	<th><?php echo $GeneralStatLang['PowerPlay'] . $GeneralStatLang['Shots'];?></th>
	<th><?php echo $GeneralStatLang['PowerPlay'] . $GeneralStatLang['MinutesPlayed'];?></th>
</tr><tr>
	<td><?php echo $PlayerProStat['PPG']; ?></td>
	<td><?php echo $PlayerProStat['PPA']; ?></td>
	<td><?php if ($PlayerProStat <> Null){echo $PlayerProStat['PPG'] + $PlayerProStat['PPA'];}?></td>	
	<td><?php echo $PlayerProStat['PPShots']; ?></td>
	<td><?php if ($PlayerProStat <> Null){echo Floor($PlayerProStat['PPSecondPlay']/60);} ?></td>			
</tr>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $GeneralStatLang['ShortHanded'] . $GeneralStatLang['Goals'];?></th>
	<th><?php echo $GeneralStatLang['ShortHanded'] . $GeneralStatLang['Assists'];?></th>
	<th><?php echo $GeneralStatLang['ShortHanded'] . $GeneralStatLang['Points'];?></th>
	<th><?php echo $GeneralStatLang['ShortHanded'] . $GeneralStatLang['Shots'];?></th>
	<th><?php echo $GeneralStatLang['ShortHanded'] . $GeneralStatLang['MinutesPlayed'];?></th>	
</tr><tr>
	<td><?php echo $PlayerProStat['PKG']; ?></td>
	<td><?php echo $PlayerProStat['PKA']; ?></td>
	<td><?php if ($PlayerProStat <> Null){echo $PlayerProStat['PKG'] + $PlayerProStat['PKA'];}?></td>		
	<td><?php echo $PlayerProStat['PKShots']; ?></td>
	<td><?php if ($PlayerProStat <> Null){echo Floor($PlayerProStat['PKSecondPlay']/60);} ?></td>			
</tr>
</table>

<br /><br /></div>
<div class="tabmain" id="tabmain3">
<br /><div class="STHSPHPPlayerStat_TabHeader"><?php echo $PlayersLang['ProStat'] . $PlayersLang['Advanced'];?></div><br />
<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $GeneralStatLang['GameWinningGoals'];?></th>
	<th><?php echo $GeneralStatLang['GameTyingGoals'];?></th>
	<th><?php echo $GeneralStatLang['FaceoffPCT'];?></th>
	<th><?php echo $GeneralStatLang['FaceoffsTaken'];?></th>
	<th><?php echo $GeneralStatLang['GiveAways'];?></th>
	<th><?php echo $GeneralStatLang['TakeAways'];?></th>
	<th><?php echo $GeneralStatLang['EmptyNetGoals'];?></th>
	<th><?php echo $GeneralStatLang['HatTricks'];?></th>
</tr><tr>	
	<td><?php echo $PlayerProStat['GW']; ?></td>
	<td><?php echo $PlayerProStat['GT']; ?></td>	
	<td><?php if ($PlayerProStat <> Null){if ($PlayerProStat['FaceOffTotal'] > "0"){echo sprintf("%.2f%%", $PlayerProStat['FaceOffWon'] / $PlayerProStat['FaceOffTotal'] *100 ); } else {echo "0%";}}?></td>
	<td><?php echo $PlayerProStat['FaceOffTotal']; ?></td>	
	<td><?php echo $PlayerProStat['GiveAway']; ?></td>
	<td><?php echo $PlayerProStat['TakeAway']; ?></td>
	<td><?php echo $PlayerProStat['EmptyNetGoal']; ?></td>	
	<td><?php echo $PlayerProStat['HatTrick']; ?></td>		
</tr>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $GeneralStatLang['Pointper20Minutes'];?></th>
	<th><?php echo $GeneralStatLang['PenaltyShotsGoals'];?></th>
	<th><?php echo $GeneralStatLang['PenaltyShotsTaken'];?></th>
	<th><?php echo $GeneralStatLang['FightWon'];?></th>
	<th><?php echo $GeneralStatLang['FightLost'];?></th>
	<th><?php echo $GeneralStatLang['FightTies'];?></th>
</tr><tr>	
	<td><?php if ($PlayerProStat <> Null){if ($PlayerProStat['SecondPlay'] > "60"){echo number_format($PlayerProStat['P'] / $PlayerProStat['SecondPlay'] * 60 * 20,2 ); } else {echo "0";}}?></td>
	<td><?php echo $PlayerProStat['PenalityShotsScore']; ?></td>	
	<td><?php echo $PlayerProStat['PenalityShotsTotal']; ?></td>
	<td><?php echo $PlayerProStat['FightW']; ?></td>	
	<td><?php echo $PlayerProStat['FightL']; ?></td>
	<td><?php echo $PlayerProStat['FightT']; ?></td>
</tr>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $GeneralStatLang['CurrentGoalScoringStreak'];?></th>
	<th><?php echo $GeneralStatLang['CurrentPointScoringSteak'];?></th>
	<th><?php echo $GeneralStatLang['CurrentGoalScoringSlump'];?></th>
	<th><?php echo $GeneralStatLang['CurrentPointScoringSlump'];?></th>
</tr><tr>	
	<td><?php echo $PlayerInfo['GameInRowWithAGoal']; ?></td>	
	<td><?php echo $PlayerInfo['GameInRowWithAPoint']; ?></td>
	<td><?php echo $PlayerInfo['GameInRowWithOutAGoal']; ?></td>	
	<td><?php echo $PlayerInfo['GameInRowWithOutAPoint']; ?></td>		
</tr>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $GeneralStatLang['NumberTimeStar1'];?></th>
	<th><?php echo $GeneralStatLang['NumberTimeStar2'];?></th>
	<th><?php echo $GeneralStatLang['NumberTimeStar3'];?></th>	
</tr><tr>		
	<td><?php echo $PlayerProStat['Star1']; ?></td>	
	<td><?php echo $PlayerProStat['Star2']; ?></td>
	<td><?php echo $PlayerProStat['Star3']; ?></td>
</tr>
</table>

<br /><br /></div>
<div class="tabmain" id="tabmain4">
<br /><div class="STHSPHPPlayerStat_TabHeader"><?php echo $PlayersLang['FarmStat'] . $PlayersLang['Basic'];?></div><br />
<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $GeneralStatLang['GamePlayed'];?></th>
	<th><?php echo $GeneralStatLang['Goals'];?></th>
	<th><?php echo $GeneralStatLang['Assists'];?></th>
	<th><?php echo $GeneralStatLang['Points'];?></th>
	<th><?php echo $GeneralStatLang['PlusMinus'];?></th>
	<th><?php echo $GeneralStatLang['PenaltyMinutes'];?></th>
	<th><?php echo $GeneralStatLang['MinutesPlayed'];?></th>
	<th><?php echo $GeneralStatLang['AverageMinutesPlayedperGame'];?></th>
</tr><tr>
	<td><?php echo $PlayerFarmStat['GP']; ?></td>
	<td><?php echo $PlayerFarmStat['G']; ?></td>
	<td><?php echo $PlayerFarmStat['A']; ?></td>
	<td><?php echo $PlayerFarmStat['P']; ?></td>
	<td><?php echo $PlayerFarmStat['PlusMinus']; ?></td>
	<td><?php echo $PlayerFarmStat['Pim']; ?></td>
	<td><?php if ($PlayerFarmStat <> Null){echo Floor($PlayerFarmStat['SecondPlay']/60);} ?></td>
	<td><?php if ($PlayerFarmStat <> Null){if ($PlayerFarmStat['GP'] > "0"){echo number_format($PlayerFarmStat['SecondPlay']/ 60 /$PlayerFarmStat['GP'],2 ); } else {echo "0";}}?></td>			
</tr>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $GeneralStatLang['MajorPenaltyMinutes'];?></th>
	<th><?php echo $GeneralStatLang['Hits'];?></th>
	<th><?php echo $GeneralStatLang['HitsReceived'];?></th>
	<th><?php echo $GeneralStatLang['Shots'];?></th>
	<th><?php echo $GeneralStatLang['OwnShotsBlock'];?></th>
	<th><?php echo $GeneralStatLang['OwnShotsMiss'];?></th>
	<th><?php echo $GeneralStatLang['ShootingPercentage'];?></th>
	<th><?php echo $GeneralStatLang['ShotsBlock'];?></th>	
</tr><tr>
	<td><?php echo $PlayerFarmStat['Pim5']; ?></td>
	<td><?php echo $PlayerFarmStat['Hits']; ?></td>
	<td><?php echo $PlayerFarmStat['HitsTook']; ?></td>
	<td><?php echo $PlayerFarmStat['Shots']; ?></td>
	<td><?php echo $PlayerFarmStat['OwnShotsBlock']; ?></td>
	<td><?php echo $PlayerFarmStat['OwnShotsMissGoal']; ?></td>
	<td><?php if ($PlayerFarmStat <> Null){if ($PlayerFarmStat['Shots'] > "0"){echo sprintf("%.2f%%", $PlayerFarmStat['G'] / $PlayerFarmStat['Shots'] *100 ); } else {echo "0%";}}?></td>
	<td><?php echo $PlayerFarmStat['ShotsBlock']; ?></td>		
</tr>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $GeneralStatLang['PowerPlay'] . $GeneralStatLang['Goals'];?></th>
	<th><?php echo $GeneralStatLang['PowerPlay'] . $GeneralStatLang['Assists'];?></th>
	<th><?php echo $GeneralStatLang['PowerPlay'] . $GeneralStatLang['Points'];?></th>
	<th><?php echo $GeneralStatLang['PowerPlay'] . $GeneralStatLang['Shots'];?></th>
	<th><?php echo $GeneralStatLang['PowerPlay'] . $GeneralStatLang['MinutesPlayed'];?></th>
</tr><tr>
	<td><?php echo $PlayerFarmStat['PPG']; ?></td>
	<td><?php echo $PlayerFarmStat['PPA']; ?></td>
	<td><?php if ($PlayerFarmStat <> Null){echo $PlayerFarmStat['PPG'] + $PlayerFarmStat['PPA'];}?></td>	
	<td><?php echo $PlayerFarmStat['PPShots']; ?></td>
	<td><?php if ($PlayerFarmStat <> Null){echo Floor($PlayerFarmStat['PPSecondPlay']/60);} ?></td>			
</tr>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $GeneralStatLang['ShortHanded'] . $GeneralStatLang['Goals'];?></th>
	<th><?php echo $GeneralStatLang['ShortHanded'] . $GeneralStatLang['Assists'];?></th>
	<th><?php echo $GeneralStatLang['ShortHanded'] . $GeneralStatLang['Points'];?></th>
	<th><?php echo $GeneralStatLang['ShortHanded'] . $GeneralStatLang['Shots'];?></th>
	<th><?php echo $GeneralStatLang['ShortHanded'] . $GeneralStatLang['MinutesPlayed'];?></th>		
</tr><tr>
	<td><?php echo $PlayerFarmStat['PKG']; ?></td>
	<td><?php echo $PlayerFarmStat['PKA']; ?></td>
	<td><?php if ($PlayerFarmStat <> Null){echo $PlayerFarmStat['PKG'] + $PlayerFarmStat['PKA'];}?></td>		
	<td><?php echo $PlayerFarmStat['PKShots']; ?></td>
	<td><?php if ($PlayerFarmStat <> Null){echo Floor($PlayerFarmStat['PKSecondPlay']/60);} ?></td>			
</tr>
</table>

<br /><br /></div>
<div class="tabmain" id="tabmain5">
<br /><div class="STHSPHPPlayerStat_TabHeader"><?php echo $PlayersLang['FarmStat'] . $PlayersLang['Advanced'];?></div><br />
<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $GeneralStatLang['GameWinningGoals'];?></th>
	<th><?php echo $GeneralStatLang['GameTyingGoals'];?></th>
	<th><?php echo $GeneralStatLang['FaceoffPCT'];?></th>
	<th><?php echo $GeneralStatLang['FaceoffsTaken'];?></th>
	<th><?php echo $GeneralStatLang['GiveAways'];?></th>
	<th><?php echo $GeneralStatLang['TakeAways'];?></th>
	<th><?php echo $GeneralStatLang['EmptyNetGoals'];?></th>
	<th><?php echo $GeneralStatLang['HatTricks'];?></th>
</tr><tr>	
	<td><?php echo $PlayerFarmStat['GW']; ?></td>
	<td><?php echo $PlayerFarmStat['GT']; ?></td>	
	<td><?php if ($PlayerFarmStat <> Null){if ($PlayerFarmStat['FaceOffTotal'] > "0"){echo sprintf("%.2f%%", $PlayerFarmStat['FaceOffWon'] / $PlayerFarmStat['FaceOffTotal'] *100 ); } else {echo "0%";}}?></td>
	<td><?php echo $PlayerFarmStat['FaceOffTotal']; ?></td>	
	<td><?php echo $PlayerFarmStat['GiveAway']; ?></td>
	<td><?php echo $PlayerFarmStat['TakeAway']; ?></td>
	<td><?php echo $PlayerFarmStat['EmptyNetGoal']; ?></td>	
	<td><?php echo $PlayerFarmStat['HatTrick']; ?></td>		
</tr>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $GeneralStatLang['Pointper20Minutes'];?></th>
	<th><?php echo $GeneralStatLang['PenaltyShotsGoals'];?></th>
	<th><?php echo $GeneralStatLang['PenaltyShotsTaken'];?></th>
	<th><?php echo $GeneralStatLang['FightWon'];?></th>
	<th><?php echo $GeneralStatLang['FightLost'];?></th>
	<th><?php echo $GeneralStatLang['FightTies'];?></th>
</tr><tr>	
	<td><?php if ($PlayerFarmStat <> Null){if ($PlayerFarmStat['SecondPlay'] > "60"){echo number_format($PlayerFarmStat['P'] / $PlayerFarmStat['SecondPlay'] * 60 * 20,2 ); } else {echo "0";}}?></td>
	<td><?php echo $PlayerFarmStat['PenalityShotsScore']; ?></td>	
	<td><?php echo $PlayerFarmStat['PenalityShotsTotal']; ?></td>
	<td><?php echo $PlayerFarmStat['FightW']; ?></td>	
	<td><?php echo $PlayerFarmStat['FightL']; ?></td>
	<td><?php echo $PlayerFarmStat['FightT']; ?></td>
</tr>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $GeneralStatLang['CurrentGoalScoringStreak'];?></th>
	<th><?php echo $GeneralStatLang['CurrentPointScoringSteak'];?></th>
	<th><?php echo $GeneralStatLang['CurrentGoalScoringSlump'];?></th>
	<th><?php echo $GeneralStatLang['CurrentPointScoringSlump'];?></th>
</tr><tr>	
	<td><?php echo $PlayerInfo['GameInRowWithAGoal']; ?></td>	
	<td><?php echo $PlayerInfo['GameInRowWithAPoint']; ?></td>
	<td><?php echo $PlayerInfo['GameInRowWithOutAGoal']; ?></td>	
	<td><?php echo $PlayerInfo['GameInRowWithOutAPoint']; ?></td>		
</tr>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $GeneralStatLang['NumberTimeStar1'];?></th>
	<th><?php echo $GeneralStatLang['NumberTimeStar2'];?></th>
	<th><?php echo $GeneralStatLang['NumberTimeStar3'];?></th>	
</tr><tr>		
	<td><?php echo $PlayerFarmStat['Star1']; ?></td>	
	<td><?php echo $PlayerFarmStat['Star2']; ?></td>
	<td><?php echo $PlayerFarmStat['Star3']; ?></td>
</tr>
</table>
<br /><br /></div>

<div class="tabmain" id="tabmain6">
<br /><div class="STHSPHPPlayerStat_TabHeader"><?php echo $PlayersLang['CareerProStat'];?></div><br />

<div class="tablesorter_ColumnSelectorWrapper">
    <input id="tablesorter_colSelect2" type="checkbox" class="hidden">
    <label class="tablesorter_ColumnSelectorButton" for="tablesorter_colSelect2"><?php echo $TableSorterLang['ShoworHideColumn'];?></label>
    <div id="tablesorter_ColumnSelector2" class="tablesorter_ColumnSelector"></div>
</div>

<table class="tablesorter STHSPHPProCareerStat_Table"><thead><tr>
<th data-priority="2" title="Team Name" class="STHSW140Min"><?php echo $PlayersLang['TeamName'];?></th>
<th data-priority="1" title="Year" class="STHSW35"><?php echo $TeamLang['Year'];?></th>
<th data-priority="1" title="Games Played" class="STHSW25">GP</th>
<th data-priority="1" title="Goals" class="STHSW25">G</th>
<th data-priority="1" title="Assists" class="STHSW25">A</th>
<th data-priority="1" title="Points" class="STHSW25">P</th>
<th data-priority="2" title="Plus/Minus" class="STHSW25">+/-</th>
<th data-priority="2" title="Penalty Minutes" class="STHSW25">PIM</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Penalty Minutes for Major Penalty">PIM5</th>
<th data-priority="2" title="Hits" class="STHSW25">HIT</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Hits Received">HTT</th>
<th data-priority="2" title="Shots" class="STHSW25">SHT</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Own Shots Block by others players">OSB</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Own Shots Miss the net">OSM</th>
<th data-priority="3" title="Shooting Percentage" class="STHSW55">SHT%</th>
<th data-priority="3" title="Shots Blocked" class="STHSW25">SB</th>
<th data-priority="3" title="Minutes Played" class="STHSW35">MP</th>
<th data-priority="3" title="Average Minutes Played per Game" class="STHSW35">AMG</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Power Play Goals">PPG</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Power Play Assists">PPA</th>
<th data-priority="4" title="Power Play Points" class="STHSW25">PPP</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Power Play Shots">PPS</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Power Play Minutes Played">PPM</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Short Handed Goals">PKG</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Short Handed Assists">PKA</th>
<th data-priority="5" title="Short Handed Points" class="STHSW25">PKP</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Penalty Kill Shots">PKS</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Penalty Kill Minutes Played">PKM</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Game Winning Goals">GW</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Game Tying Goals">GT</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Face offs Percentage">FO%</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Face offs Taken">FOT</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Give Aways">GA</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Take Aways">TA</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Empty Net Goals">EG</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Hat Tricks">HT</th>
<th data-priority="4" title="Points per 20 Minutes" class="STHSW25">P/20</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Penalty Shots Goals">PSG</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Penalty Shots Taken">PSS</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Fight Won">FW</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Fight Lost">FL</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Fight Ties">FT</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Number of time players was star #1 in a game">S1</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Number of time players was star #2 in a game">S2</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Number of time players was star #3 in a game">S3</th>
</tr></thead><tbody>
<?php 
if ($PlayerProCareerSumSeasonOnly['SumOfGP'] > 0){echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"45\"><strong>" . $PlayersLang['RegularSeason'] . "</strong></td></tr>\n";}
if (empty($PlayerProCareerSeason) == false){while ($Row = $PlayerProCareerSeason ->fetchArray()) {
	/* Loop ProPlayerCareerInfo */
	echo "<tr><td>" . $Row['TeamName'] . "</td>";
	echo "<td>" . $Row['Year'] . "</td>";
	echo "<td>" . $Row['GP'] . "</td>";
	echo "<td>" . $Row['G'] . "</td>";
	echo "<td>" . $Row['A'] . "</td>";
	echo "<td>" . $Row['P'] . "</td>";
	echo "<td>" . $Row['PlusMinus'] . "</td>";
	echo "<td>" . $Row['Pim'] . "</td>";
	echo "<td>" . $Row['Pim5'] . "</td>";
	echo "<td>" . $Row['Hits'] . "</td>";	
	echo "<td>" . $Row['HitsTook'] . "</td>";		
	echo "<td>" . $Row['Shots'] . "</td>";
	echo "<td>" . $Row['OwnShotsBlock'] . "</td>";
	echo "<td>" . $Row['OwnShotsMissGoal'] . "</td>";
	echo "<td>" . number_Format($Row['ShotsPCT'],2) . "%</td>";		
	echo "<td>" . $Row['ShotsBlock'] . "</td>";	
	echo "<td>" . Floor($Row['SecondPlay']/60) . "</td>";
	echo "<td>" . number_Format($Row['AMG'],2) . "</td>";		
	echo "<td>" . $Row['PPG'] . "</td>";
	echo "<td>" . $Row['PPA'] . "</td>";
	echo "<td>" . $Row['PPP'] . "</td>";
	echo "<td>" . $Row['PPShots'] . "</td>";
	echo "<td>" . Floor($Row['PPSecondPlay']/60) . "</td>";	
	echo "<td>" . $Row['PKG'] . "</td>";
	echo "<td>" . $Row['PKA'] . "</td>";
	echo "<td>" . $Row['PKP'] . "</td>";
	echo "<td>" . $Row['PKShots'] . "</td>";
	echo "<td>" . Floor($Row['PKSecondPlay']/60) . "</td>";	
	echo "<td>" . $Row['GW'] . "</td>";
	echo "<td>" . $Row['GT'] . "</td>";
	echo "<td>" . number_Format($Row['FaceoffPCT'],2) . "%</td>";	
	echo "<td>" . $Row['FaceOffTotal'] . "</td>";
	echo "<td>" . $Row['GiveAway'] . "</td>";
	echo "<td>" . $Row['TakeAway'] . "</td>";
	echo "<td>" . $Row['EmptyNetGoal'] . "</td>";
	echo "<td>" . $Row['HatTrick'] . "</td>";	
	echo "<td>" . number_Format($Row['P20'],2) . "</td>";			
	echo "<td>" . $Row['PenalityShotsScore'] . "</td>";
	echo "<td>" . $Row['PenalityShotsTotal'] . "</td>";
	echo "<td>" . $Row['FightW'] . "</td>";
	echo "<td>" . $Row['FightL'] . "</td>";
	echo "<td>" . $Row['FightT'] . "</td>";
	echo "<td>" . $Row['Star1'] . "</td>";
	echo "<td>" . $Row['Star2'] . "</td>";
	echo "<td>" . $Row['Star3'] . "</td>";
	echo "</tr>\n"; 
}}
if ($PlayerProCareerSumSeasonOnly['SumOfGP'] > 0){
	/* Show ProCareer Total for Season */
	echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"2\"><strong>" . $PlayersLang['Total'] . " " . $PlayersLang['RegularSeason']. "</strong></td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['SumOfGP'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['SumOfG'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['SumOfA'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['SumOfP'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['SumOfPlusMinus'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['SumOfPim'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['SumOfPim5'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['SumOfHits'] . "</td>";	
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['SumOfHitsTook'] . "</td>";		
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['SumOfShots'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['SumOfOwnShotsBlock'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['SumOfOwnShotsMissGoal'] . "</td>";
	echo "<td class=\"staticTD\">" . number_Format($PlayerProCareerSumSeasonOnly['SumOfShotsPCT'],2) . "%</td>";		
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['SumOfShotsBlock'] . "</td>";	
	echo "<td class=\"staticTD\">" . Floor($PlayerProCareerSumSeasonOnly['SumOfSecondPlay']/60) . "</td>";
	echo "<td class=\"staticTD\">" . number_Format($PlayerProCareerSumSeasonOnly['SumOfAMG'],2) . "</td>";		
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['SumOfPPG'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['SumOfPPA'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['SumOfPPP'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['SumOfPPShots'] . "</td>";
	echo "<td class=\"staticTD\">" . Floor($PlayerProCareerSumSeasonOnly['SumOfPPSecondPlay']/60) . "</td>";	
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['SumOfPKG'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['SumOfPKA'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['SumOfPKP'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['SumOfPKShots'] . "</td>";
	echo "<td class=\"staticTD\">" . Floor($PlayerProCareerSumSeasonOnly['SumOfPKSecondPlay']/60) . "</td>";	
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['SumOfGW'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['SumOfGT'] . "</td>";
	echo "<td class=\"staticTD\">" . number_Format($PlayerProCareerSumSeasonOnly['SumOfFaceoffPCT'],2) . "%</td>";	
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['SumOfFaceOffTotal'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['SumOfGiveAway'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['SumOfTakeAway'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['SumOfEmptyNetGoal'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['SumOfHatTrick'] . "</td>";	
	echo "<td class=\"staticTD\">" . number_Format($PlayerProCareerSumSeasonOnly['SumOfP20'],2) . "</td>";			
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['SumOfPenalityShotsScore'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['SumOfPenalityShotsTotal'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['SumOfFightW'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['SumOfFightL'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['SumOfFightT'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['SumOfStar1'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['SumOfStar2'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['SumOfStar3'] . "</td>";
	echo "</tr>\n";
}

If ($PlayerProCareerSumPlayoffOnly['SumOfGP'] > 0){echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"45\"><strong>" . $PlayersLang['Playoff'] . "</strong></td></tr>\n";}
if (empty($PlayerProCareerPlayoff) == false){while ($Row = $PlayerProCareerPlayoff ->fetchArray()) {
	/* Loop ProPlayerCareerPlayofff */
	echo "<tr><td>" . $Row['TeamName'] . "</td>";
	echo "<td>" . $Row['Year'] . "</td>";
	echo "<td>" . $Row['GP'] . "</td>";
	echo "<td>" . $Row['G'] . "</td>";
	echo "<td>" . $Row['A'] . "</td>";
	echo "<td>" . $Row['P'] . "</td>";
	echo "<td>" . $Row['PlusMinus'] . "</td>";
	echo "<td>" . $Row['Pim'] . "</td>";
	echo "<td>" . $Row['Pim5'] . "</td>";
	echo "<td>" . $Row['Hits'] . "</td>";	
	echo "<td>" . $Row['HitsTook'] . "</td>";		
	echo "<td>" . $Row['Shots'] . "</td>";
	echo "<td>" . $Row['OwnShotsBlock'] . "</td>";
	echo "<td>" . $Row['OwnShotsMissGoal'] . "</td>";
	echo "<td>" . number_Format($Row['ShotsPCT'],2) . "%</td>";		
	echo "<td>" . $Row['ShotsBlock'] . "</td>";	
	echo "<td>" . Floor($Row['SecondPlay']/60) . "</td>";
	echo "<td>" . number_Format($Row['AMG'],2) . "</td>";		
	echo "<td>" . $Row['PPG'] . "</td>";
	echo "<td>" . $Row['PPA'] . "</td>";
	echo "<td>" . $Row['PPP'] . "</td>";
	echo "<td>" . $Row['PPShots'] . "</td>";
	echo "<td>" . Floor($Row['PPSecondPlay']/60) . "</td>";	
	echo "<td>" . $Row['PKG'] . "</td>";
	echo "<td>" . $Row['PKA'] . "</td>";
	echo "<td>" . $Row['PKP'] . "</td>";
	echo "<td>" . $Row['PKShots'] . "</td>";
	echo "<td>" . Floor($Row['PKSecondPlay']/60) . "</td>";	
	echo "<td>" . $Row['GW'] . "</td>";
	echo "<td>" . $Row['GT'] . "</td>";
	echo "<td>" . number_Format($Row['FaceoffPCT'],2) . "%</td>";	
	echo "<td>" . $Row['FaceOffTotal'] . "</td>";
	echo "<td>" . $Row['GiveAway'] . "</td>";
	echo "<td>" . $Row['TakeAway'] . "</td>";
	echo "<td>" . $Row['EmptyNetGoal'] . "</td>";
	echo "<td>" . $Row['HatTrick'] . "</td>";	
	echo "<td>" . number_Format($Row['P20'],2) . "</td>";			
	echo "<td>" . $Row['PenalityShotsScore'] . "</td>";
	echo "<td>" . $Row['PenalityShotsTotal'] . "</td>";
	echo "<td>" . $Row['FightW'] . "</td>";
	echo "<td>" . $Row['FightL'] . "</td>";
	echo "<td>" . $Row['FightT'] . "</td>";
	echo "<td>" . $Row['Star1'] . "</td>";
	echo "<td>" . $Row['Star2'] . "</td>";
	echo "<td>" . $Row['Star3'] . "</td>";
	echo "</tr>\n"; 
}}

If ($PlayerProCareerSumPlayoffOnly['SumOfGP'] > 0){
	/* Show ProCareer Total for Playoff */
	echo "<tr class=\"static\"><td colspan=\"2\"><strong>" . $PlayersLang['Total'] . " " . $PlayersLang['Playoff']. "</strong></td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['SumOfGP'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['SumOfG'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['SumOfA'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['SumOfP'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['SumOfPlusMinus'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['SumOfPim'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['SumOfPim5'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['SumOfHits'] . "</td>";	
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['SumOfHitsTook'] . "</td>";		
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['SumOfShots'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['SumOfOwnShotsBlock'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['SumOfOwnShotsMissGoal'] . "</td>";
	echo "<td class=\"staticTD\">" . number_Format($PlayerProCareerSumPlayoffOnly['SumOfShotsPCT'],2) . "%</td>";		
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['SumOfShotsBlock'] . "</td>";	
	echo "<td class=\"staticTD\">" . Floor($PlayerProCareerSumPlayoffOnly['SumOfSecondPlay']/60) . "</td>";
	echo "<td class=\"staticTD\">" . number_Format($PlayerProCareerSumPlayoffOnly['SumOfAMG'],2) . "</td>";		
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['SumOfPPG'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['SumOfPPA'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['SumOfPPP'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['SumOfPPShots'] . "</td>";
	echo "<td class=\"staticTD\">" . Floor($PlayerProCareerSumPlayoffOnly['SumOfPPSecondPlay']/60) . "</td>";	
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['SumOfPKG'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['SumOfPKA'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['SumOfPKP'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['SumOfPKShots'] . "</td>";
	echo "<td class=\"staticTD\">" . Floor($PlayerProCareerSumPlayoffOnly['SumOfPKSecondPlay']/60) . "</td>";	
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['SumOfGW'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['SumOfGT'] . "</td>";
	echo "<td class=\"staticTD\">" . number_Format($PlayerProCareerSumPlayoffOnly['SumOfFaceoffPCT'],2) . "%</td>";	
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['SumOfFaceOffTotal'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['SumOfGiveAway'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['SumOfTakeAway'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['SumOfEmptyNetGoal'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['SumOfHatTrick'] . "</td>";	
	echo "<td class=\"staticTD\">" . number_Format($PlayerProCareerSumPlayoffOnly['SumOfP20'],2) . "</td>";			
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['SumOfPenalityShotsScore'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['SumOfPenalityShotsTotal'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['SumOfFightW'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['SumOfFightL'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['SumOfFightT'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['SumOfStar1'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['SumOfStar2'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['SumOfStar3'] . "</td>";
	echo "</tr>\n";
}
?>
</tbody></table>
<br /></div>

<div class="tabmain" id="tabmain7">
<br /><div class="STHSPHPPlayerStat_TabHeader"><?php echo $PlayersLang['CareerFarmStat'];?></div><br />

<div class="tablesorter_ColumnSelectorWrapper">
    <input id="tablesorter_colSelect3" type="checkbox" class="hidden">
    <label class="tablesorter_ColumnSelectorButton" for="tablesorter_colSelect3"><?php echo $TableSorterLang['ShoworHideColumn'];?></label>
    <div id="tablesorter_ColumnSelector3" class="tablesorter_ColumnSelector"></div>
</div>


<table class="tablesorter STHSPHPFarmCareerStat_Table"><thead><tr>
<th data-priority="1" title="Team Name" class="STHSW140Min"><?php echo $PlayersLang['TeamName'];?></th>
<th data-priority="critical" title="Year" class="STHSW35"><?php echo $TeamLang['Year'];?></th>
<th data-priority="1" title="Games Played" class="STHSW25">GP</th>
<th data-priority="1" title="Goals" class="STHSW25">G</th>
<th data-priority="1" title="Assists" class="STHSW25">A</th>
<th data-priority="1" title="Points" class="STHSW25">P</th>
<th data-priority="2" title="Plus/Minus" class="STHSW25">+/-</th>
<th data-priority="2" title="Penalty Minutes" class="STHSW25">PIM</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Penalty Minutes for Major Penalty">PIM5</th>
<th data-priority="2" title="Hits" class="STHSW25">HIT</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Hits Received">HTT</th>
<th data-priority="2" title="Shots" class="STHSW25">SHT</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Own Shots Block by others players">OSB</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Own Shots Miss the net">OSM</th>
<th data-priority="3" title="Shooting Percentage" class="STHSW55">SHT%</th>
<th data-priority="3" title="Shots Blocked" class="STHSW25">SB</th>
<th data-priority="3" title="Minutes Played" class="STHSW35">MP</th>
<th data-priority="3" title="Average Minutes Played per Game" class="STHSW35">AMG</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Power Play Goals">PPG</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Power Play Assists">PPA</th>
<th data-priority="4" title="Power Play Points" class="STHSW25">PPP</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Power Play Shots">PPS</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Power Play Minutes Played">PPM</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Short Handed Goals">PKG</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Short Handed Assists">PKA</th>
<th data-priority="5" title="Short Handed Points" class="STHSW25">PKP</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Penalty Kill Shots">PKS</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Penalty Kill Minutes Played">PKM</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Game Winning Goals">GW</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Game Tying Goals">GT</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Face offs Percentage">FO%</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Face offs Taken">FOT</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Give Aways">GA</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Take Aways">TA</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Empty Net Goals">EG</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Hat Tricks">HT</th>
<th data-priority="4" title="Points per 20 Minutes" class="STHSW25">P/20</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Penalty Shots Goals">PSG</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Penalty Shots Taken">PSS</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Fight Won">FW</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Fight Lost">FL</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Fight Ties">FT</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Number of time players was star #1 in a game">S1</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Number of time players was star #2 in a game">S2</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Number of time players was star #3 in a game">S3</th>
</tr></thead><tbody>
<?php 
if ($PlayerFarmCareerSumSeasonOnly['SumOfGP'] > 0){echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"45\"><strong>" . $PlayersLang['RegularSeason'] . "</strong></td></tr>\n";}
if (empty($PlayerFarmCareerSeason) == false){while ($Row = $PlayerFarmCareerSeason ->fetchArray()) {
	/* Loop FarmPlayerCareerInfo */
	echo "<tr><td>" . $Row['TeamName'] . "</td>";
	echo "<td>" . $Row['Year'] . "</td>";
	echo "<td>" . $Row['GP'] . "</td>";
	echo "<td>" . $Row['G'] . "</td>";
	echo "<td>" . $Row['A'] . "</td>";
	echo "<td>" . $Row['P'] . "</td>";
	echo "<td>" . $Row['PlusMinus'] . "</td>";
	echo "<td>" . $Row['Pim'] . "</td>";
	echo "<td>" . $Row['Pim5'] . "</td>";
	echo "<td>" . $Row['Hits'] . "</td>";	
	echo "<td>" . $Row['HitsTook'] . "</td>";		
	echo "<td>" . $Row['Shots'] . "</td>";
	echo "<td>" . $Row['OwnShotsBlock'] . "</td>";
	echo "<td>" . $Row['OwnShotsMissGoal'] . "</td>";
	echo "<td>" . number_Format($Row['ShotsPCT'],2) . "%</td>";		
	echo "<td>" . $Row['ShotsBlock'] . "</td>";	
	echo "<td>" . Floor($Row['SecondPlay']/60) . "</td>";
	echo "<td>" . number_Format($Row['AMG'],2) . "</td>";		
	echo "<td>" . $Row['PPG'] . "</td>";
	echo "<td>" . $Row['PPA'] . "</td>";
	echo "<td>" . $Row['PPP'] . "</td>";
	echo "<td>" . $Row['PPShots'] . "</td>";
	echo "<td>" . Floor($Row['PPSecondPlay']/60) . "</td>";	
	echo "<td>" . $Row['PKG'] . "</td>";
	echo "<td>" . $Row['PKA'] . "</td>";
	echo "<td>" . $Row['PKP'] . "</td>";
	echo "<td>" . $Row['PKShots'] . "</td>";
	echo "<td>" . Floor($Row['PKSecondPlay']/60) . "</td>";	
	echo "<td>" . $Row['GW'] . "</td>";
	echo "<td>" . $Row['GT'] . "</td>";
	echo "<td>" . number_Format($Row['FaceoffPCT'],2) . "%</td>";	
	echo "<td>" . $Row['FaceOffTotal'] . "</td>";
	echo "<td>" . $Row['GiveAway'] . "</td>";
	echo "<td>" . $Row['TakeAway'] . "</td>";
	echo "<td>" . $Row['EmptyNetGoal'] . "</td>";
	echo "<td>" . $Row['HatTrick'] . "</td>";	
	echo "<td>" . number_Format($Row['P20'],2) . "</td>";			
	echo "<td>" . $Row['PenalityShotsScore'] . "</td>";
	echo "<td>" . $Row['PenalityShotsTotal'] . "</td>";
	echo "<td>" . $Row['FightW'] . "</td>";
	echo "<td>" . $Row['FightL'] . "</td>";
	echo "<td>" . $Row['FightT'] . "</td>";
	echo "<td>" . $Row['Star1'] . "</td>";
	echo "<td>" . $Row['Star2'] . "</td>";
	echo "<td>" . $Row['Star3'] . "</td>";
	echo "</tr>\n"; 
}}
if ($PlayerFarmCareerSumSeasonOnly['SumOfGP'] > 0){
	/* Show FarmCareer Total for Season */
	echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"2\"><strong>" . $PlayersLang['Total'] . " " . $PlayersLang['RegularSeason']. "</strong></td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['SumOfGP'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['SumOfG'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['SumOfA'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['SumOfP'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['SumOfPlusMinus'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['SumOfPim'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['SumOfPim5'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['SumOfHits'] . "</td>";	
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['SumOfHitsTook'] . "</td>";		
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['SumOfShots'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['SumOfOwnShotsBlock'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['SumOfOwnShotsMissGoal'] . "</td>";
	echo "<td class=\"staticTD\">" . number_Format($PlayerFarmCareerSumSeasonOnly['SumOfShotsPCT'],2) . "%</td>";		
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['SumOfShotsBlock'] . "</td>";	
	echo "<td class=\"staticTD\">" . Floor($PlayerFarmCareerSumSeasonOnly['SumOfSecondPlay']/60) . "</td>";
	echo "<td class=\"staticTD\">" . number_Format($PlayerFarmCareerSumSeasonOnly['SumOfAMG'],2) . "</td>";		
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['SumOfPPG'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['SumOfPPA'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['SumOfPPP'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['SumOfPPShots'] . "</td>";
	echo "<td class=\"staticTD\">" . Floor($PlayerFarmCareerSumSeasonOnly['SumOfPPSecondPlay']/60) . "</td>";	
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['SumOfPKG'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['SumOfPKA'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['SumOfPKP'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['SumOfPKShots'] . "</td>";
	echo "<td class=\"staticTD\">" . Floor($PlayerFarmCareerSumSeasonOnly['SumOfPKSecondPlay']/60) . "</td>";	
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['SumOfGW'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['SumOfGT'] . "</td>";
	echo "<td class=\"staticTD\">" . number_Format($PlayerFarmCareerSumSeasonOnly['SumOfFaceoffPCT'],2) . "%</td>";	
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['SumOfFaceOffTotal'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['SumOfGiveAway'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['SumOfTakeAway'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['SumOfEmptyNetGoal'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['SumOfHatTrick'] . "</td>";	
	echo "<td class=\"staticTD\">" . number_Format($PlayerFarmCareerSumSeasonOnly['SumOfP20'],2) . "</td>";			
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['SumOfPenalityShotsScore'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['SumOfPenalityShotsTotal'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['SumOfFightW'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['SumOfFightL'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['SumOfFightT'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['SumOfStar1'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['SumOfStar2'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['SumOfStar3'] . "</td>";
	echo "</tr>\n";
}

If ($PlayerFarmCareerSumPlayoffOnly['SumOfGP'] > 0){echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"45\"><strong>" . $PlayersLang['Playoff'] . "</strong></td></tr>\n";}
if (empty($PlayerFarmCareerPlayoff) == false){while ($Row = $PlayerFarmCareerPlayoff ->fetchArray()) {
	/* Loop FarmPlayerCareerPlayofff */
	echo "<tr><td>" . $Row['TeamName'] . "</td>";
	echo "<td>" . $Row['Year'] . "</td>";
	echo "<td>" . $Row['GP'] . "</td>";
	echo "<td>" . $Row['G'] . "</td>";
	echo "<td>" . $Row['A'] . "</td>";
	echo "<td>" . $Row['P'] . "</td>";
	echo "<td>" . $Row['PlusMinus'] . "</td>";
	echo "<td>" . $Row['Pim'] . "</td>";
	echo "<td>" . $Row['Pim5'] . "</td>";
	echo "<td>" . $Row['Hits'] . "</td>";	
	echo "<td>" . $Row['HitsTook'] . "</td>";		
	echo "<td>" . $Row['Shots'] . "</td>";
	echo "<td>" . $Row['OwnShotsBlock'] . "</td>";
	echo "<td>" . $Row['OwnShotsMissGoal'] . "</td>";
	echo "<td>" . number_Format($Row['ShotsPCT'],2) . "%</td>";		
	echo "<td>" . $Row['ShotsBlock'] . "</td>";	
	echo "<td>" . Floor($Row['SecondPlay']/60) . "</td>";
	echo "<td>" . number_Format($Row['AMG'],2) . "</td>";		
	echo "<td>" . $Row['PPG'] . "</td>";
	echo "<td>" . $Row['PPA'] . "</td>";
	echo "<td>" . $Row['PPP'] . "</td>";
	echo "<td>" . $Row['PPShots'] . "</td>";
	echo "<td>" . Floor($Row['PPSecondPlay']/60) . "</td>";	
	echo "<td>" . $Row['PKG'] . "</td>";
	echo "<td>" . $Row['PKA'] . "</td>";
	echo "<td>" . $Row['PKP'] . "</td>";
	echo "<td>" . $Row['PKShots'] . "</td>";
	echo "<td>" . Floor($Row['PKSecondPlay']/60) . "</td>";	
	echo "<td>" . $Row['GW'] . "</td>";
	echo "<td>" . $Row['GT'] . "</td>";
	echo "<td>" . number_Format($Row['FaceoffPCT'],2) . "%</td>";	
	echo "<td>" . $Row['FaceOffTotal'] . "</td>";
	echo "<td>" . $Row['GiveAway'] . "</td>";
	echo "<td>" . $Row['TakeAway'] . "</td>";
	echo "<td>" . $Row['EmptyNetGoal'] . "</td>";
	echo "<td>" . $Row['HatTrick'] . "</td>";	
	echo "<td>" . number_Format($Row['P20'],2) . "</td>";			
	echo "<td>" . $Row['PenalityShotsScore'] . "</td>";
	echo "<td>" . $Row['PenalityShotsTotal'] . "</td>";
	echo "<td>" . $Row['FightW'] . "</td>";
	echo "<td>" . $Row['FightL'] . "</td>";
	echo "<td>" . $Row['FightT'] . "</td>";
	echo "<td>" . $Row['Star1'] . "</td>";
	echo "<td>" . $Row['Star2'] . "</td>";
	echo "<td>" . $Row['Star3'] . "</td>";
	echo "</tr>\n"; 
}}

If ($PlayerFarmCareerSumPlayoffOnly['SumOfGP'] > 0){
	/* Show FarmCareer Total for Playoff */
	echo "<tr class=\"static\"><td colspan=\"2\"><strong>" . $PlayersLang['Total'] . " " . $PlayersLang['Playoff']. "</strong></td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['SumOfGP'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['SumOfG'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['SumOfA'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['SumOfP'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['SumOfPlusMinus'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['SumOfPim'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['SumOfPim5'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['SumOfHits'] . "</td>";	
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['SumOfHitsTook'] . "</td>";		
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['SumOfShots'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['SumOfOwnShotsBlock'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['SumOfOwnShotsMissGoal'] . "</td>";
	echo "<td class=\"staticTD\">" . number_Format($PlayerFarmCareerSumPlayoffOnly['SumOfShotsPCT'],2) . "%</td>";		
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['SumOfShotsBlock'] . "</td>";	
	echo "<td class=\"staticTD\">" . Floor($PlayerFarmCareerSumPlayoffOnly['SumOfSecondPlay']/60) . "</td>";
	echo "<td class=\"staticTD\">" . number_Format($PlayerFarmCareerSumPlayoffOnly['SumOfAMG'],2) . "</td>";		
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['SumOfPPG'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['SumOfPPA'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['SumOfPPP'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['SumOfPPShots'] . "</td>";
	echo "<td class=\"staticTD\">" . Floor($PlayerFarmCareerSumPlayoffOnly['SumOfPPSecondPlay']/60) . "</td>";	
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['SumOfPKG'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['SumOfPKA'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['SumOfPKP'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['SumOfPKShots'] . "</td>";
	echo "<td class=\"staticTD\">" . Floor($PlayerFarmCareerSumPlayoffOnly['SumOfPKSecondPlay']/60) . "</td>";	
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['SumOfGW'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['SumOfGT'] . "</td>";
	echo "<td class=\"staticTD\">" . number_Format($PlayerFarmCareerSumPlayoffOnly['SumOfFaceoffPCT'],2) . "%</td>";	
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['SumOfFaceOffTotal'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['SumOfGiveAway'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['SumOfTakeAway'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['SumOfEmptyNetGoal'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['SumOfHatTrick'] . "</td>";	
	echo "<td class=\"staticTD\">" . number_Format($PlayerFarmCareerSumPlayoffOnly['SumOfP20'],2) . "</td>";			
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['SumOfPenalityShotsScore'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['SumOfPenalityShotsTotal'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['SumOfFightW'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['SumOfFightL'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['SumOfFightT'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['SumOfStar1'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['SumOfStar2'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['SumOfStar3'] . "</td>";
	echo "</tr>\n";
}
?>
</tbody></table>
<br /></div>

</div>
</div>
</div>

<?php
if ($PlayerCareerStatFound == true){
	echo "<script type=\"text/javascript\">\$(function() {\$(\".STHSPHPProCareerStat_Table\").tablesorter( {widgets: ['staticRow', 'columnSelector'], widgetOptions : {columnSelector_container : \$('#tablesorter_ColumnSelector2'), columnSelector_layout : '<label><input type=\"checkbox\">{name}</label>', columnSelector_name  : 'title', columnSelector_mediaquery: true, columnSelector_mediaqueryName: 'Automatic', columnSelector_mediaqueryState: true, columnSelector_mediaqueryHidden: true, columnSelector_breakpoints : [ '50em', '60em', '70em', '80em', '90em', '95em' ],}});});</script>";
	echo "<script type=\"text/javascript\">\$(function() {\$(\".STHSPHPFarmCareerStat_Table\").tablesorter({widgets: ['staticRow', 'columnSelector'], widgetOptions : {columnSelector_container : \$('#tablesorter_ColumnSelector3'), columnSelector_layout : '<label><input type=\"checkbox\">{name}</label>', columnSelector_name  : 'title', columnSelector_mediaquery: true, columnSelector_mediaqueryName: 'Automatic', columnSelector_mediaqueryState: true, columnSelector_mediaqueryHidden: true, columnSelector_breakpoints : [ '50em', '60em', '70em', '80em', '90em', '95em' ],}});});</script>";
}
?>


<?php include "Footer.php";?>
