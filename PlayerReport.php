<?php include "Header.php";
/*
Syntax to call this webpage should be PlayersStat.php?Player=2 where only the number change and it's based on the UniqueID of players.
*/
If ($lang == "fr"){include 'LanguageFR-Stat.php';}else{include 'LanguageEN-Stat.php';}
$Player = (integer)0;
$Query = (string)"";
$PlayerName = $PlayersLang['IncorrectPlayer'];
$LeagueName = (string)"";
$CareerLeaderSubPrintOut = (int)0;
$PlayerCareerStatFound = (boolean)false;
$PlayerProCareerSeason = Null;
$PlayerProCareerPlayoff = Null;
$PlayerProCareerSumSeasonOnly = Null;
$PlayerProCareerSumPlayoffOnly = Null;
$PlayerFarmCareerSeason = Null;
$PlayerFarmCareerPlayoff = Null;
$PlayerFarmCareerSumSeasonOnly = Null;
$PlayerFarmCareerSumPlayoffOnly = Null;
$PlayerProStatMultipleTeamFound = (boolean)FALSE;
$PlayerFarmStatMultipleTeamFound = (boolean)FALSE;

if(isset($_GET['Player'])){$Player = filter_var($_GET['Player'], FILTER_SANITIZE_NUMBER_INT);} 
try{
If (file_exists($DatabaseFile) == false){
	$Player = 0;
	$PlayerName = $DatabaseNotFound;
	$LeagueOutputOption = Null;
	$LeagueGeneral = Null;
}else{
	$db = new SQLite3($DatabaseFile);
	$Query = "Select Name, OutputName, LeagueYearOutput, PreSeasonSchedule, PlayOffStarted from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);	
	$Query = "Select PlayersMugShotBaseURL, PlayersMugShotFileExtension,OutputSalariesRemaining,OutputSalariesAverageTotal,OutputSalariesAverageRemaining from LeagueOutputOption";
	$LeagueOutputOption = $db->querySingle($Query,true);	
}
If ($Player == 0){
	$PlayerInfo = Null;
	$PlayerProStat = Null;
	$PlayerFarmStat = Null;		
	echo "<style>.STHSPHPPlayerStat_Main {display:none;}</style>";
}else{
	$Query = "SELECT count(*) AS count FROM PlayerInfo WHERE Number = " . $Player;
	$Result = $db->querySingle($Query,true);
	If ($Result['count'] == 1){
		If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS Start Page PHP Performance : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}
		$Query = "SELECT PlayerInfo.*, TeamProInfo.Name AS ProTeamName FROM PlayerInfo LEFT JOIN TeamProInfo ON PlayerInfo.Team = TeamProInfo.Number WHERE PlayerInfo.Number = " . $Player;
		$PlayerInfo = $db->querySingle($Query,true);
		$Query = "SELECT PlayerProStat.*, ROUND((CAST(PlayerProStat.G AS REAL) / (PlayerProStat.Shots))*100,2) AS ShotsPCT, ROUND((CAST(PlayerProStat.SecondPlay AS REAL) / 60 / (PlayerProStat.GP)),2) AS AMG,ROUND((CAST(PlayerProStat.FaceOffWon AS REAL) / (PlayerProStat.FaceOffTotal))*100,2) as FaceoffPCT,ROUND((CAST(PlayerProStat.P AS REAL) / (PlayerProStat.SecondPlay) * 60 * 20),2) AS P20 FROM PlayerProStat WHERE Number = " . $Player;
		$PlayerProStat = $db->querySingle($Query,true);
		$Query = "SELECT PlayerFarmStat.*, ROUND((CAST(PlayerFarmStat.G AS REAL) / (PlayerFarmStat.Shots))*100,2) AS ShotsPCT, ROUND((CAST(PlayerFarmStat.SecondPlay AS REAL) / 60 / (PlayerFarmStat.GP)),2) AS AMG,ROUND((CAST(PlayerFarmStat.FaceOffWon AS REAL) / (PlayerFarmStat.FaceOffTotal))*100,2) as FaceoffPCT,ROUND((CAST(PlayerFarmStat.P AS REAL) / (PlayerFarmStat.SecondPlay) * 60 * 20),2) AS P20 FROM PlayerFarmStat WHERE Number = " . $Player;
		$PlayerFarmStat = $db->querySingle($Query,true);
		
		$Query = "SELECT count(*) AS count FROM PlayerProStatMultipleTeam WHERE Number = " . $Player;
		$Result = $db->querySingle($Query,true);
		If ($Result['count'] > 0){$PlayerProStatMultipleTeamFound = TRUE;}
		
		$Query = "SELECT count(*) AS count FROM PlayerFarmStatMultipleTeam WHERE Number = " . $Player;
		$Result = $db->querySingle($Query,true);
		If ($Result['count'] > 0){$PlayerFarmStatMultipleTeamFound = TRUE;}
		
		If ($PlayerInfo['Team'] > 0){
			$Query = "SELECT MainTable.* FROM (SELECT PlayerInfo.Number, PlayerInfo.Name, PlayerInfo.Team, PlayerInfo.TeamName, PlayerInfo.URLLink, PlayerInfo.NHLID, 'False' AS PosG FROM PlayerInfo WHERE Team = " . $PlayerInfo['Team'] . " UNION ALL SELECT GoalerInfo.Number, GoalerInfo.Name, GoalerInfo.Team, GoalerInfo.TeamName, GoalerInfo.URLLink, GoalerInfo.NHLID, 'True' AS PosG FROM GoalerInfo WHERE Team = " . $PlayerInfo['Team'] . ") AS MainTable ORDER BY Name";
			$TeamPlayers = $db->query($Query);
		}
		If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS Normal Query PHP Performance : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}
								
		$LeagueName = $LeagueGeneral['Name'];
		$PlayerName = $PlayerInfo['Name'];	
		If (file_exists($CareerStatDatabaseFile) == true){ /* CareerStat */
			$CareerStatdb = new SQLite3($CareerStatDatabaseFile);
			
			$CareerDBFormatV2CheckCheck = $CareerStatdb->querySingle("SELECT Count(name) AS CountName FROM sqlite_master WHERE type='table' AND name='LeagueGeneral'",true);
			If ($CareerDBFormatV2CheckCheck['CountName'] == 1){
				
				include "APIFunction.php";
				
				$PlayerProCareerSeason = APIPost(array('PlayerStatProHistoryAllSeasonPerYear' => '', 'UniqueID' => $PlayerInfo['UniqueID']));
				If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS ProCareerSeason Page PHP Performance : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}
				$PlayerProCareerPlayoff = APIPost(array('PlayerStatProHistoryAllSeasonPerYear' => '', 'UniqueID' => $PlayerInfo['UniqueID'], 'Playoff' => ''));
				If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS ProCareerPlayoff Page PHP Performance : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}
				$PlayerProCareerSumSeasonOnly = APIPost(array('PlayerStatProHistoryAllSeasonMerge' => '', 'UniqueID' => $PlayerInfo['UniqueID']));
				If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS ProCareerSumSeasonOnly Page PHP Performance : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}
				$PlayerProCareerSumPlayoffOnly = APIPost(array('PlayerStatProHistoryAllSeasonMerge' => '', 'UniqueID' => $PlayerInfo['UniqueID'], 'Playoff' => ''));
				If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS ProCareerSumPlayoffOnly Page PHP Performance : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}
				
				$PlayerFarmCareerSeason = APIPost(array('PlayerStatFarmHistoryAllSeasonPerYear' => '', 'UniqueID' => $PlayerInfo['UniqueID']));
				If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS FarmCareerSeason  Page PHP Performance : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}
				$PlayerFarmCareerPlayoff = APIPost(array('PlayerStatFarmHistoryAllSeasonPerYear' => '', 'UniqueID' => $PlayerInfo['UniqueID'], 'Playoff' => ''));
				If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS FarmCareerPlayoff Page PHP Performance : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}
				$PlayerFarmCareerSumSeasonOnly = APIPost(array('PlayerStatFarmHistoryAllSeasonMerge' => '', 'UniqueID' => $PlayerInfo['UniqueID']));
				If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS FarmCareerSumSeasonOnly Page PHP Performance : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}
				$PlayerFarmCareerSumPlayoffOnly = APIPost(array('PlayerStatFarmHistoryAllSeasonMerge' => '', 'UniqueID' => $PlayerInfo['UniqueID'], 'Playoff' => ''));		
				If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS FarmCareerSumPlayoffOnly Page PHP Performance : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}
				
				$PlayerCareerStatFound = true;	
			}
			If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS CareerStat Query PHP Performance : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}
		}
	}else{
		$PlayerName = $PlayersLang['Playernotfound'];
		$PlayerInfo = Null;
		$PlayerProStat = Null;
		$PlayerFarmStat = Null;	
		echo "<style>.STHSPHPPlayerStat_Main {display:none;}</style>";
	}
}} catch (Exception $e) {
	$Player = 0;
	$PlayerName = $DatabaseNotFound;
	$LeagueOutputOption = Null;
	$LeagueGeneral = Null;
	$PlayerInfo = Null;
	$PlayerProStat = Null;
	$PlayerFarmStat = Null;		
}
echo "<title>" . $LeagueName . " - " . $PlayerName . "</title>";
echo "<style>";
if ($PlayerCareerStatFound == true){
	echo "#tablesorter_colSelect2:checked + label {background: #5797d7;  border-color: #555;}";
	echo "#tablesorter_colSelect2:checked ~ #tablesorter_ColumnSelector2 {display: block;}";
	echo "#tablesorter_colSelect3:checked + label {background: #5797d7;  border-color: #555;}";
	echo "#tablesorter_colSelect3:checked ~ #tablesorter_ColumnSelector3 {display: block;}";
}
if ($PlayerProStatMultipleTeamFound == true){
	echo "#tablesorter_colSelect4:checked + label {background: #5797d7;  border-color: #555;}";
	echo "#tablesorter_colSelect4:checked ~ #tablesorter_ColumnSelector4 {display: block;}";
}
if ($PlayerFarmStatMultipleTeamFound == true){
	echo "#tablesorter_colSelect5:checked + label {background: #5797d7;  border-color: #555;}";
	echo "#tablesorter_colSelect5:checked ~ #tablesorter_ColumnSelector5 {display: block;}";
}
echo "</style>";
?>
</head><body>
<?php include "Menu.php";?>
<br />

<div class="STHSPHPPlayerStat_PlayerNameHeader">
<?php
echo "<table class=\"STHSTableFullW STHSPHPPlayerMugShot\"><tr>";
If($PlayerInfo <> Null){If ($PlayerInfo['TeamThemeID'] > 0){echo "<td><img src=\"" . $ImagesCDNPath . "/images/" . $PlayerInfo['TeamThemeID'] .".png\" alt=\"\" class=\".STHSPHPTradeTeamImage {width:48px;height:48px;padding-left:0px;padding-right:8px;vertical-align:middle}\" /></td>";}}
echo "<td style=\"padding-bottom: 10px;\">" . $PlayerName . "";
If($PlayerInfo <> Null AND $LeagueOutputOption <> Null){
	if ($PlayerInfo['Retire'] == 'False'){
		echo "<div id=\"cssmenu\" style=\"display:inline-block\"><ul style=\"max-width:150px;width:100%;margin:0 auto\"><li style=\"font-size:24px;cursor:pointer;line-height:0\">&#9660;<ul style=\"max-height:250px;overflow-x:hidden;overflow-y:scroll\">";
		if (empty($TeamPlayers) == false){while ($Row = $TeamPlayers ->fetchArray()) { 
			if ($Row['PosG']== "True"){
				echo "<li style=\"text-align:left;display:flex\"><a href=\"GoalieReport.php?Goalie=" . $Row['Number'] . "\">" . $Row['Name'] . "</a></li>";
			}else{
				echo "<li style=\"text-align:left;display:flex\"><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . "</a></li>";
			}
		}}
		echo "</ul></li></ul></div><br /><br />" . $PlayerInfo['TeamName'] . "</td>";
	}else{
		echo " - " . $PlayersLang['Retire'] . "</td>";
	}
	If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $PlayerInfo['NHLID'] != ""){
		echo "<td><img src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $PlayerInfo['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $PlayerName . "\" class=\"STHSPHPPlayerReportHeadshot\" /></td>";
	}
else
	echo "</td>";	
}
echo "</tr></table>";
 ?></div>

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
	<th><?php echo $PlayersLang['Link'];?></th>
</tr><tr>
	<td><?php
	If($PlayerInfo <> Null){
		$Position = (string)"";
		if ($PlayerInfo['PosC']== "True"){if ($Position == ""){$Position = $PlayersLang['Center'];}else{$Position = $Position . " - " . $PlayersLang['Center'];}}
		if ($PlayerInfo['PosLW']== "True"){if ($Position == ""){$Position = $PlayersLang['LeftWing'];}else{$Position = $Position . " - " . $PlayersLang['LeftWing'];}}
		if ($PlayerInfo['PosRW']== "True"){if ($Position == ""){$Position = $PlayersLang['RightWing'];}else{$Position = $Position . " - " . $PlayersLang['RightWing'];}}
		if ($PlayerInfo['PosD']== "True"){if ($Position == ""){$Position = $PlayersLang['Defense'];}else{$Position = $Position . " - " . $PlayersLang['Defense'];}}
		echo $Position;
	}
	?></td>
	<td><?php if ($PlayerInfo <> Null){echo $PlayerInfo['Age'];}?></td>	
	<td><?php if ($PlayerInfo <> Null){echo number_format(str_replace(",",".",$PlayerInfo['ConditionDecimal']),2);} ?></td>	
	<td><?php if ($PlayerInfo <> Null){echo $PlayerInfo['Suspension'];} ?></td>	
	<td><?php if ($PlayerInfo <> Null){echo $PlayerInfo['Height'];} ?></td>
	<td><?php if ($PlayerInfo <> Null){echo $PlayerInfo['Weight'];} ?></td>
	<td><?php 
	if ($PlayerInfo <> Null){
		if ($PlayerInfo['URLLink'] != ""){echo "<a href=" . $PlayerInfo['URLLink'] . " target=\"new\">" . $PlayersLang['Link'] . "</a>";}
		if ($PlayerInfo['URLLink'] != "" AND $PlayerInfo['NHLID'] != ""){echo " / ";}
		if ($PlayerInfo['NHLID'] != ""){echo "<a href=\"https://www.nhl.com/player/" . $PlayerInfo['NHLID'] . "\" target=\"new\">" . $PlayersLang['NHLLink'] . "</a>";}
	}
	?></td>
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
<?php
If($PlayerInfo != Null){
	echo "<td>" . $PlayerInfo['CK']. "</td>";
	echo "<td>" . $PlayerInfo['FG']. "</td>";
	echo "<td>" . $PlayerInfo['DI']. "</td>";
	echo "<td>" . $PlayerInfo['SK']. "</td>";
	echo "<td>" . $PlayerInfo['ST']. "</td>";
	echo "<td>" . $PlayerInfo['EN']. "</td>";
	echo "<td>" . $PlayerInfo['DU']. "</td>";
	echo "<td>" . $PlayerInfo['PH']. "</td>";
	echo "<td>" . $PlayerInfo['FO']. "</td>";
	echo "<td>" . $PlayerInfo['PA']. "</td>";
	echo "<td>" . $PlayerInfo['SC']. "</td>";
	echo "<td>" . $PlayerInfo['DF']. "</td>";
	echo "<td>" . $PlayerInfo['PS']. "</td>";
	echo "<td>" . $PlayerInfo['EX']. "</td>";
	echo "<td>" . $PlayerInfo['LD']. "</td>";
	echo "<td>" . $PlayerInfo['PO']. "</td>";
	echo "<td>" . $PlayerInfo['MO']. "</td>";
	echo "<td>" . $PlayerInfo['Overall']. "</td>"; 
}?>
</tr>
</table>
<br />

<div class="tabsmain standard"><ul class="tabmain-links">
<li class="activemain"><a href="#tabmain1"><?php echo $PlayersLang['Information'];?></a></li>
<li><a href="#tabmain2"><?php echo $PlayersLang['ProStat'] . $PlayersLang['Basic'];?></a></li>
<li><a href="#tabmain3"><?php echo $PlayersLang['ProStat'] . $PlayersLang['Advanced'];?></a></li>
<li><a href="#tabmain4"><?php echo $PlayersLang['FarmStat'] . $PlayersLang['Basic'];?></a></li>
<li><a href="#tabmain5"><?php echo $PlayersLang['FarmStat'] . $PlayersLang['Advanced'];?></a></li>

<?php 
if ($PlayerProStatMultipleTeamFound == TRUE OR $PlayerFarmStatMultipleTeamFound == TRUE){echo "<li><a href=\"#tabmain8\">" . $PlayersLang['StatperTeam'] . "</a></li>";}
if ($PlayerCareerStatFound == true){
	echo "<li><a href=\"#tabmain6\">" . $PlayersLang['CareerProStat'] . "</a></li>";
	echo "<li><a href=\"#tabmain7\">" . $PlayersLang['CareerFarmStat'] . "</a></li>";
}
?>
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
	<th><?php echo $PlayersLang['DraftYear'];?></th>
	<th><?php echo $PlayersLang['DraftOverallPick'];?></th>		
	<th><?php echo $PlayersLang['AcquiredBy'];?></th>
	<th><?php echo $PlayersLang['LastTradeDate'];?></th>
</tr><tr>
<?php
If($PlayerInfo != Null){
	echo "<td>" . $PlayerInfo['AgeDate']. "</td>";
	echo "<td>" . $PlayerInfo['Country']. "</td>";
	echo "<td>"; if ($PlayerInfo['Rookie']== "True"){ echo "Yes"; }else{echo "No";};echo "</td>";
	echo "<td>" . $PlayerInfo['Injury']. "</td>";	
	echo "<td>" . $PlayerInfo['NumberOfInjury']. "</td>";
	echo "<td>" . $PlayerInfo['StarPower']. "</td>";	
	echo "<td>"; If ($PlayerInfo['DraftYear'] == 0){echo "-";}else{echo $PlayerInfo['DraftYear'];};echo "</td>";
	echo "<td>"; If ($PlayerInfo['DraftOverallPick'] == 0){echo "-";}else{echo $PlayerInfo['DraftOverallPick'];};echo "</td>";
	echo "<td>" . $PlayerInfo['AcquiredType']. "</td>";
	echo "<td>" . $PlayerInfo['LastTradeDate']. "</td>";
}?>
</tr>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $PlayersLang['AvailableforTrade'];?></th>
	<th><?php echo $PlayersLang['NoTrade'];?></th>
	<th><?php echo $PlayersLang['ForceWaiver'];?></th>
	<th><?php echo $PlayersLang['PossibleWaiver'];?></th>
	<th><?php echo $PlayersLang['CanPlayPro'];?></th>
	<th><?php echo $PlayersLang['CanPlayFarm'];?></th>
	<th><?php echo $PlayersLang['ExcludefromSalaryCap'];?></th>
	<th><?php echo $PlayersLang['ProSalaryinFarm'];?></th>
	<th><?php echo $PlayersLang['ForceUFA'];?></th>
	<th><?php echo $PlayersLang['EmergencyRecall'];?></th>
</tr><tr>
<?php
If($PlayerInfo != Null){
	echo "<td>"; if($PlayerInfo['AvailableforTrade']== "True"){ echo "Yes"; }else{echo "No";};echo "</td>";
	echo "<td>"; if($PlayerInfo['NoTrade']== "True"){ echo "Yes"; }else{echo "No";};echo "</td>";
	echo "<td>"; if($PlayerInfo['ForceWaiver']== "True"){ echo "Yes"; }else{echo "No";};echo "</td>";
	echo "<td>"; if (array_key_exists('WaiverPossible',$PlayerInfo)){if ($PlayerInfo['WaiverPossible']== "True"){ echo "Yes"; }else{echo "No";};echo "</td>";}else{echo "N/A";}
	echo "<td>"; if($PlayerInfo['CanPlayPro']== "True"){ echo "Yes"; }else{echo "No";};echo "</td>";
	echo "<td>"; if($PlayerInfo['CanPlayFarm']== "True"){ echo "Yes"; }else{echo "No";};echo "</td>";	
	echo "<td>"; if($PlayerInfo['ExcludeSalaryCap']== "True"){ echo "Yes"; }else{echo "No";};echo "</td>";
	echo "<td>"; if($PlayerInfo['ProSalaryinFarm']== "True"){ echo "Yes"; }else{echo "No";};echo "</td>";
	echo "<td>"; if($PlayerInfo['ForceUFA']== "True"){ echo "Yes"; }else{echo "No";};echo "</td>";
	echo "<td>"; if($PlayerInfo['EmergencyRecall']== "True"){ echo "Yes"; }else{echo "No";};echo "</td>";
}?>
</tr>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPPlayerStat_Table">
<tr>
<?php 
	echo "<th>" . $PlayersLang['Contract']. "</th>";
	echo "<th>" . $PlayersLang['ContractSignatureDate']. "</th>";
	if($LeagueOutputOption != Null){if($LeagueOutputOption['OutputSalariesAverageTotal'] == "True"){echo "<th>" . $PlayersLang['SalaryAverage'] . "</th>";}}
	echo "<th>" .  $PlayersLang['SalaryYear'] . "1</th>";
	if($LeagueOutputOption != Null){if($LeagueOutputOption['OutputSalariesRemaining'] == "True"){ echo "<th>" . $PlayersLang['SalaryRemaining'] . "</th>";}}
	if($LeagueOutputOption != Null){if($LeagueOutputOption['OutputSalariesAverageRemaining'] == "True"){ echo "<th>" . $PlayersLang['SalaryAveRemaining']. "</th>";}}
	echo "<th>" . $PlayersLang['SalaryCap']. "</th>";
	echo "<th>" . $PlayersLang['SalaryCapRemaining']. "</th>";
?>
</tr><tr>
	<td><?php If($PlayerInfo <> Null){echo $PlayerInfo['Contract'];} ?></td>
	<td><?php if($PlayerInfo <> Null){echo $PlayerInfo['ContractSignatureDate'];} ?></td>
	<?php if($LeagueOutputOption != Null){if($LeagueOutputOption['OutputSalariesAverageTotal'] == "True"){echo "<td>";if ($PlayerInfo <> Null){echo number_format($PlayerInfo['SalaryAverage'],0) . "$";}echo "</td>";}}?>
	<td><?php if ($PlayerInfo <> Null){echo number_format($PlayerInfo['Salary1'],0) . "$";} ?></td>
	<?php if($LeagueOutputOption != Null){if($LeagueOutputOption['OutputSalariesRemaining'] == "True"){echo "<td>";if ($PlayerInfo <> Null){echo number_format($PlayerInfo['SalaryRemaining'],0) . "$";}echo "</td>";}}?>
	<?php if($LeagueOutputOption != Null){if($LeagueOutputOption['OutputSalariesAverageRemaining'] == "True"){echo "<td>";if ($PlayerInfo <> Null){echo number_format($PlayerInfo['SalaryAverageRemaining'],0) . "$";}echo "</td>";}}?>
	<?php
	echo "<td>"; If($PlayerInfo <> Null){echo number_format($PlayerInfo['SalaryCap'],0);}; echo "$</td>";
	echo "<td>"; If($PlayerInfo <> Null){echo number_format($PlayerInfo['SalaryCapRemaining'],0);}; echo "$</td>";
	?>
</tr>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $PlayersLang['SalaryYear'];?> 2</th>
	<th><?php echo $PlayersLang['SalaryYear'];?> 3</th>
	<th><?php echo $PlayersLang['SalaryYear'];?> 4</th>
	<th><?php echo $PlayersLang['SalaryYear'];?> 5</th>
	<th><?php echo $PlayersLang['SalaryYear'];?> 6</th>	
</tr><tr>
<?php
If($PlayerInfo != Null){
	echo "<td>"; If($PlayerInfo['Salary2'] > 0){echo number_format($PlayerInfo['Salary2'],0) . "$";}else{echo "-";}echo "</td>";
	echo "<td>"; If($PlayerInfo['Salary3'] > 0){echo number_format($PlayerInfo['Salary3'],0) . "$";}else{echo "-";}echo "</td>";
	echo "<td>"; If($PlayerInfo['Salary4'] > 0){echo number_format($PlayerInfo['Salary4'],0) . "$";}else{echo "-";}echo "</td>";
	echo "<td>"; If($PlayerInfo['Salary5'] > 0){echo number_format($PlayerInfo['Salary5'],0) . "$";}else{echo "-";}echo "</td>";
	echo "<td>"; If($PlayerInfo['Salary6'] > 0){echo number_format($PlayerInfo['Salary6'],0) . "$";}else{echo "-";}echo "</td>";
}?>
</tr>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $PlayersLang['NoTradeYear'];?> 2</th>
	<th><?php echo $PlayersLang['NoTradeYear'];?> 3</th>
	<th><?php echo $PlayersLang['NoTradeYear'];?> 4</th>
	<th><?php echo $PlayersLang['NoTradeYear'];?> 5</th>
	<th><?php echo $PlayersLang['NoTradeYear'];?> 6</th>
</tr><tr>
<?php
If($PlayerInfo != Null){
	echo "<td>"; If($PlayerInfo['Salary2'] > 0){ if($PlayerInfo['NoTrade2']== "True"){ echo "Yes"; }else{echo "No";}}else{echo "-";}echo "</td>";
	echo "<td>"; If($PlayerInfo['Salary3'] > 0){ if($PlayerInfo['NoTrade3']== "True"){ echo "Yes"; }else{echo "No";}}else{echo "-";}echo "</td>";
	echo "<td>"; If($PlayerInfo['Salary4'] > 0){ if($PlayerInfo['NoTrade4']== "True"){ echo "Yes"; }else{echo "No";}}else{echo "-";}echo "</td>";
	echo "<td>"; If($PlayerInfo['Salary5'] > 0){ if($PlayerInfo['NoTrade5']== "True"){ echo "Yes"; }else{echo "No";}}else{echo "-";}echo "</td>";
	echo "<td>"; If($PlayerInfo['Salary6'] > 0){ if($PlayerInfo['NoTrade6']== "True"){ echo "Yes"; }else{echo "No";}}else{echo "-";}echo "</td>";
}?>
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
	<td><?php if ($PlayerProStat <> Null){echo $PlayerProStat['GP'];} ?></td>
	<td><?php if ($PlayerProStat <> Null){echo $PlayerProStat['G'];} ?></td>
	<td><?php if ($PlayerProStat <> Null){echo $PlayerProStat['A'];} ?></td>
	<td><?php if ($PlayerProStat <> Null){echo $PlayerProStat['P'];} ?></td>
	<td><?php if ($PlayerProStat <> Null){echo $PlayerProStat['PlusMinus'];} ?></td>
	<td><?php if ($PlayerProStat <> Null){echo $PlayerProStat['Pim'];} ?></td>
	<td><?php if ($PlayerProStat <> Null){echo Floor($PlayerProStat['SecondPlay']/60);} ?></td>
	<td><?php if ($PlayerProStat <> Null){if ($PlayerProStat['AMG']){echo number_format($PlayerProStat['AMG'],2);}}?></td>		
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
	<td><?php if ($PlayerProStat <> Null){echo $PlayerProStat['Pim5'];} ?></td>
	<td><?php if ($PlayerProStat <> Null){echo $PlayerProStat['Hits'];} ?></td>
	<td><?php if ($PlayerProStat <> Null){echo $PlayerProStat['HitsTook'];} ?></td>
	<td><?php if ($PlayerProStat <> Null){echo $PlayerProStat['Shots'];} ?></td>
	<td><?php if ($PlayerProStat <> Null){echo $PlayerProStat['OwnShotsBlock'];} ?></td>
	<td><?php if ($PlayerProStat <> Null){echo $PlayerProStat['OwnShotsMissGoal'];} ?></td>
	<td><?php if ($PlayerProStat <> Null){echo sprintf("%.2f%%", $PlayerProStat['ShotsPCT']);}?></td>
	<td><?php if ($PlayerProStat <> Null){echo $PlayerProStat['ShotsBlock'];} ?></td>		
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
	<td><?php if ($PlayerProStat <> Null){echo $PlayerProStat['PPG'];} ?></td>
	<td><?php if ($PlayerProStat <> Null){echo $PlayerProStat['PPA'];} ?></td>
	<td><?php if ($PlayerProStat <> Null){echo $PlayerProStat['PPG'] + $PlayerProStat['PPA'];}?></td>	
	<td><?php if ($PlayerProStat <> Null){echo $PlayerProStat['PPShots'];} ?></td>
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
	<td><?php if ($PlayerProStat <> Null){echo $PlayerProStat['PKG'];} ?></td>
	<td><?php if ($PlayerProStat <> Null){echo $PlayerProStat['PKA'];} ?></td>
	<td><?php if ($PlayerProStat <> Null){echo $PlayerProStat['PKG'] + $PlayerProStat['PKA'];}?></td>		
	<td><?php if ($PlayerProStat <> Null){echo $PlayerProStat['PKShots'];} ?></td>
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
	<td><?php if ($PlayerProStat <> Null){echo $PlayerProStat['GW'];} ?></td>
	<td><?php if ($PlayerProStat <> Null){echo $PlayerProStat['GT'];} ?></td>
	<td><?php if ($PlayerProStat <> Null){echo sprintf("%.2f%%", $PlayerProStat['FaceoffPCT']);}?></td>	
	<td><?php if ($PlayerProStat <> Null){echo $PlayerProStat['FaceOffTotal'];} ?></td>	
	<td><?php if ($PlayerProStat <> Null){echo $PlayerProStat['GiveAway'];} ?></td>
	<td><?php if ($PlayerProStat <> Null){echo $PlayerProStat['TakeAway'];} ?></td>
	<td><?php if ($PlayerProStat <> Null){echo $PlayerProStat['EmptyNetGoal'];} ?></td>	
	<td><?php if ($PlayerProStat <> Null){echo $PlayerProStat['HatTrick'];} ?></td>		
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
	<td><?php if ($PlayerProStat <> Null){if ($PlayerProStat['P20'] <> Null){echo number_format($PlayerProStat['P20'],2);}}?></td>
	<td><?php if ($PlayerProStat <> Null){echo $PlayerProStat['PenalityShotsScore'];} ?></td>	
	<td><?php if ($PlayerProStat <> Null){echo $PlayerProStat['PenalityShotsTotal'];} ?></td>
	<td><?php if ($PlayerProStat <> Null){echo $PlayerProStat['FightW'];} ?></td>	
	<td><?php if ($PlayerProStat <> Null){echo $PlayerProStat['FightL'];} ?></td>
	<td><?php if ($PlayerProStat <> Null){echo $PlayerProStat['FightT'];} ?></td>
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
	<td><?php if ($PlayerInfo <> Null){echo $PlayerInfo['GameInRowWithAGoal'];} ?></td>	
	<td><?php if ($PlayerInfo <> Null){echo $PlayerInfo['GameInRowWithAPoint'];} ?></td>
	<td><?php if ($PlayerInfo <> Null){echo $PlayerInfo['GameInRowWithOutAGoal'];} ?></td>	
	<td><?php if ($PlayerInfo <> Null){echo $PlayerInfo['GameInRowWithOutAPoint'];} ?></td>		
</tr>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $GeneralStatLang['NumberTimeStar1'];?></th>
	<th><?php echo $GeneralStatLang['NumberTimeStar2'];?></th>
	<th><?php echo $GeneralStatLang['NumberTimeStar3'];?></th>	
</tr><tr>		
	<td><?php if ($PlayerProStat <> Null){echo $PlayerProStat['Star1'];} ?></td>	
	<td><?php if ($PlayerProStat <> Null){echo $PlayerProStat['Star2'];} ?></td>
	<td><?php if ($PlayerProStat <> Null){echo $PlayerProStat['Star3'];} ?></td>
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
	<td><?php if ($PlayerFarmStat <> Null){echo $PlayerFarmStat['GP'];} ?></td>
	<td><?php if ($PlayerFarmStat <> Null){echo $PlayerFarmStat['G'];} ?></td>
	<td><?php if ($PlayerFarmStat <> Null){echo $PlayerFarmStat['A'];} ?></td>
	<td><?php if ($PlayerFarmStat <> Null){echo $PlayerFarmStat['P'];} ?></td>
	<td><?php if ($PlayerFarmStat <> Null){echo $PlayerFarmStat['PlusMinus'];} ?></td>
	<td><?php if ($PlayerFarmStat <> Null){echo $PlayerFarmStat['Pim'];} ?></td>
	<td><?php if ($PlayerFarmStat <> Null){if ($PlayerFarmStat <> Null){echo Floor($PlayerFarmStat['SecondPlay']/60);}} ?></td>
	<td><?php if ($PlayerFarmStat <> Null){if ($PlayerFarmStat['AMG']){echo number_format($PlayerFarmStat['AMG'],2);}}?></td>	
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
	<td><?php if ($PlayerFarmStat <> Null){echo $PlayerFarmStat['Pim5'];} ?></td>
	<td><?php if ($PlayerFarmStat <> Null){echo $PlayerFarmStat['Hits'];} ?></td>
	<td><?php if ($PlayerFarmStat <> Null){echo $PlayerFarmStat['HitsTook'];} ?></td>
	<td><?php if ($PlayerFarmStat <> Null){echo $PlayerFarmStat['Shots'];} ?></td>
	<td><?php if ($PlayerFarmStat <> Null){echo $PlayerFarmStat['OwnShotsBlock'];} ?></td>
	<td><?php if ($PlayerFarmStat <> Null){echo $PlayerFarmStat['OwnShotsMissGoal'];} ?></td>
	<td><?php if ($PlayerFarmStat <> Null){echo sprintf("%.2f%%", $PlayerFarmStat['ShotsPCT']);}?></td>
	<td><?php if ($PlayerFarmStat <> Null){echo $PlayerFarmStat['ShotsBlock'];} ?></td>		
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
	<td><?php if ($PlayerFarmStat <> Null){echo $PlayerFarmStat['PPG'];} ?></td>
	<td><?php if ($PlayerFarmStat <> Null){echo $PlayerFarmStat['PPA'];} ?></td>
	<td><?php if ($PlayerFarmStat <> Null){echo $PlayerFarmStat['PPG'] + $PlayerFarmStat['PPA'];}?></td>	
	<td><?php if ($PlayerFarmStat <> Null){echo $PlayerFarmStat['PPShots'];} ?></td>
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
	<td><?php if ($PlayerFarmStat <> Null){echo $PlayerFarmStat['PKG'];} ?></td>
	<td><?php if ($PlayerFarmStat <> Null){echo $PlayerFarmStat['PKA'];} ?></td>
	<td><?php if ($PlayerFarmStat <> Null){echo $PlayerFarmStat['PKG'] + $PlayerFarmStat['PKA'];}?></td>		
	<td><?php if ($PlayerFarmStat <> Null){echo $PlayerFarmStat['PKShots'];} ?></td>
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
	<td><?php if ($PlayerFarmStat <> Null){echo $PlayerFarmStat['GW'];} ?></td>
	<td><?php if ($PlayerFarmStat <> Null){echo $PlayerFarmStat['GT'];} ?></td>	
	<td><?php if ($PlayerFarmStat <> Null){echo sprintf("%.2f%%", $PlayerFarmStat['FaceoffPCT']);}?></td>	
	<td><?php if ($PlayerFarmStat <> Null){echo $PlayerFarmStat['FaceOffTotal'];} ?></td>	
	<td><?php if ($PlayerFarmStat <> Null){echo $PlayerFarmStat['GiveAway'];} ?></td>
	<td><?php if ($PlayerFarmStat <> Null){echo $PlayerFarmStat['TakeAway'];} ?></td>
	<td><?php if ($PlayerFarmStat <> Null){echo $PlayerFarmStat['EmptyNetGoal'];} ?></td>	
	<td><?php if ($PlayerFarmStat <> Null){echo $PlayerFarmStat['HatTrick'];} ?></td>		
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
	<td><?php if ($PlayerFarmStat <> Null){if ($PlayerFarmStat['P20'] <> Null){echo number_format($PlayerFarmStat['P20'],2);}}?></td>
	<td><?php if ($PlayerFarmStat <> Null){echo $PlayerFarmStat['PenalityShotsScore'];} ?></td>	
	<td><?php if ($PlayerFarmStat <> Null){echo $PlayerFarmStat['PenalityShotsTotal'];} ?></td>
	<td><?php if ($PlayerFarmStat <> Null){echo $PlayerFarmStat['FightW'];} ?></td>	
	<td><?php if ($PlayerFarmStat <> Null){echo $PlayerFarmStat['FightL'];} ?></td>
	<td><?php if ($PlayerFarmStat <> Null){ echo $PlayerFarmStat['FightT'];} ?></td>
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
	<td><?php If($PlayerInfo <> Null){echo $PlayerInfo['GameInRowWithAGoal'];} ?></td>	
	<td><?php If($PlayerInfo <> Null){echo $PlayerInfo['GameInRowWithAPoint'];} ?></td>
	<td><?php If($PlayerInfo <> Null){echo $PlayerInfo['GameInRowWithOutAGoal'];} ?></td>	
	<td><?php If($PlayerInfo <> Null){echo $PlayerInfo['GameInRowWithOutAPoint'];} ?></td>		
</tr>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $GeneralStatLang['NumberTimeStar1'];?></th>
	<th><?php echo $GeneralStatLang['NumberTimeStar2'];?></th>
	<th><?php echo $GeneralStatLang['NumberTimeStar3'];?></th>	
</tr><tr>		
	<td><?php if ($PlayerFarmStat <> Null){echo $PlayerFarmStat['Star1'];} ?></td>	
	<td><?php if ($PlayerFarmStat <> Null){echo $PlayerFarmStat['Star2'];} ?></td>
	<td><?php if ($PlayerFarmStat <> Null){echo $PlayerFarmStat['Star3'];} ?></td>
</tr>
</table>
<br /><br /></div>

<div class="tabmain" id="tabmain8">
<br /><div class="STHSPHPPlayerStat_TabHeader"><?php echo $PlayersLang['StatperTeam'];?></div>

<?php 
if ($PlayerProStatMultipleTeamFound == TRUE){
	echo "<h2>" . $PlayersLang['ProStat'] . "</h2>";
	echo "<div style=\"width:99%;margin:auto;\"><div class=\"tablesorter_ColumnSelectorWrapper\"><input id=\"tablesorter_colSelect4\" type=\"checkbox\" class=\"hidden\"><label class=\"tablesorter_ColumnSelectorButton\" for=\"tablesorter_colSelect4\">" . $TableSorterLang['ShoworHideColumn'] . "</label><div id=\"tablesorter_ColumnSelector4\" class=\"tablesorter_ColumnSelector\"></div>"; include "FilterTip.php"; echo "</div></div>";
	
	$Query = "SELECT PlayerProStatMultipleTeam.*, PlayerInfo.PosC, PlayerInfo.PosLW, PlayerInfo.PosRW, PlayerInfo.PosD, ROUND((CAST(PlayerProStatMultipleTeam.G AS REAL) / (PlayerProStatMultipleTeam.Shots))*100,2) AS ShotsPCT, ROUND((CAST(PlayerProStatMultipleTeam.SecondPlay AS REAL) / 60 / (PlayerProStatMultipleTeam.GP)),2) AS AMG,ROUND((CAST(PlayerProStatMultipleTeam.FaceOffWon AS REAL) / (PlayerProStatMultipleTeam.FaceOffTotal))*100,2) as FaceoffPCT,ROUND((CAST(PlayerProStatMultipleTeam.P AS REAL) / (PlayerProStatMultipleTeam.SecondPlay) * 60 * 20),2) AS P20, 0 as Star1, 0 as Star2, 0 As Star3 FROM PlayerInfo INNER JOIN PlayerProStatMultipleTeam ON PlayerInfo.Number = PlayerProStatMultipleTeam.Number WHERE PlayerProStatMultipleTeam.Number = " . $Player;
	$PlayerStat = $db->query($Query);
	$Team = (integer)-1;
	echo "<table class=\"tablesorter STHSPHPProPlayerStatPerTeam_Table\"><thead><tr>";
	include "PlayersStatSub.php";
	echo "</tbody></table>";
}

if ($PlayerProStatMultipleTeamFound == TRUE AND $PlayerFarmStatMultipleTeamFound == TRUE){echo "<br /><hr /><br />";}

if ($PlayerFarmStatMultipleTeamFound == TRUE){
	echo "<h2>" . $PlayersLang['FarmStat'] . "</h2>";
	echo "<div style=\"width:99%;margin:auto;\"><div class=\"tablesorter_ColumnSelectorWrapper\"><input id=\"tablesorter_colSelect5\" type=\"checkbox\" class=\"hidden\"><label class=\"tablesorter_ColumnSelectorButton\" for=\"tablesorter_colSelect5\">" . $TableSorterLang['ShoworHideColumn'] . "</label><div id=\"tablesorter_ColumnSelector5\" class=\"tablesorter_ColumnSelector\"></div>"; include "FilterTip.php"; echo "</div></div>";
	
	$Query = "SELECT PlayerFarmStatMultipleTeam.*, PlayerInfo.PosC, PlayerInfo.PosLW, PlayerInfo.PosRW, PlayerInfo.PosD, ROUND((CAST(PlayerFarmStatMultipleTeam.G AS REAL) / (PlayerFarmStatMultipleTeam.Shots))*100,2) AS ShotsPCT, ROUND((CAST(PlayerFarmStatMultipleTeam.SecondPlay AS REAL) / 60 / (PlayerFarmStatMultipleTeam.GP)),2) AS AMG,ROUND((CAST(PlayerFarmStatMultipleTeam.FaceOffWon AS REAL) / (PlayerFarmStatMultipleTeam.FaceOffTotal))*100,2) as FaceoffPCT,ROUND((CAST(PlayerFarmStatMultipleTeam.P AS REAL) / (PlayerFarmStatMultipleTeam.SecondPlay) * 60 * 20),2) AS P20, 0 as Star1, 0 as Star2, 0 As Star3 FROM PlayerInfo INNER JOIN PlayerFarmStatMultipleTeam ON PlayerInfo.Number = PlayerFarmStatMultipleTeam.Number WHERE PlayerFarmStatMultipleTeam.Number = " . $Player;
	$PlayerStat = $db->query($Query);
	$Team = (integer)-1;
	echo "<table class=\"tablesorter STHSPHPFarmPlayerStatPerTeam_Table\"><thead><tr>";
	include "PlayersStatSub.php";
	echo "</tbody></table>";
}
?>

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
<?php If($PlayerProCareerSeason <> Null){
echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"45\"><strong>" . $PlayersLang['RegularSeason'] . "</strong></td></tr>\n";
if (empty($PlayerProCareerSeason) == false){foreach($PlayerProCareerSeason as $Row) {
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
	If ($Row['ShotsPCT'] == Null){echo "<td>0%</td>";}else{echo "<td>" . number_Format($Row['ShotsPCT'],2) . "%</td>";}		
	echo "<td>" . $Row['ShotsBlock'] . "</td>";	
	echo "<td>" . Floor($Row['SecondPlay']/60) . "</td>";
	If ($Row['AMG'] == Null){echo "<td>0</td>";}else{echo "<td>" . number_Format($Row['AMG'],2) . "</td>";}	
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
	If ($Row['FaceoffPCT'] == Null){echo "<td>0</td>";}else{echo "<td>" . number_Format($Row['FaceoffPCT'],2) . "</td>";}
	echo "<td>" . $Row['FaceOffTotal'] . "</td>";
	echo "<td>" . $Row['GiveAway'] . "</td>";
	echo "<td>" . $Row['TakeAway'] . "</td>";
	echo "<td>" . $Row['EmptyNetGoal'] . "</td>";
	echo "<td>" . $Row['HatTrick'] . "</td>";	
	If ($Row['P20'] == Null){echo "<td>0</td>";}else{echo "<td>" . number_Format($Row['P20'],2) . "</td>";}				
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

if ($PlayerProCareerSumSeasonOnly != Null){if ($PlayerProCareerSumSeasonOnly['0']['GP'] > 0){
	echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"2\"><strong>" . $PlayersLang['Total'] . " " . $PlayersLang['RegularSeason']. "</strong></td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['0']['GP'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['0']['G'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['0']['A'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['0']['P'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['0']['PlusMinus'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['0']['Pim'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['0']['Pim5'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['0']['Hits'] . "</td>";	
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['0']['HitsTook'] . "</td>";		
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['0']['Shots'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['0']['OwnShotsBlock'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['0']['OwnShotsMissGoal'] . "</td>";
	echo "<td class=\"staticTD\">"; if($PlayerProCareerSumSeasonOnly['0']['Shots'] > 0){echo sprintf("%.2f%%",($PlayerProCareerSumSeasonOnly['0']['G'] / $PlayerProCareerSumSeasonOnly['0']['Shots']*100));}else{echo "0%";}echo "</td>";		
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['0']['ShotsBlock'] . "</td>";	
	echo "<td class=\"staticTD\">" . Floor($PlayerProCareerSumSeasonOnly['0']['SecondPlay']/60) . "</td>";
	echo "<td class=\"staticTD\">"; if($PlayerProCareerSumSeasonOnly['0']['GP'] > 0){echo number_format(($PlayerProCareerSumSeasonOnly['0']['SecondPlay'] / 60 / $PlayerProCareerSumSeasonOnly['0']['GP']),2);}else{echo "0";}echo "</td>";				
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['0']['PPG'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['0']['PPA'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['0']['PPP'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['0']['PPShots'] . "</td>";
	echo "<td class=\"staticTD\">" . Floor($PlayerProCareerSumSeasonOnly['0']['PPSecondPlay']/60) . "</td>";	
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['0']['PKG'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['0']['PKA'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['0']['PKP'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['0']['PKShots'] . "</td>";
	echo "<td class=\"staticTD\">" . Floor($PlayerProCareerSumSeasonOnly['0']['PKSecondPlay']/60) . "</td>";	
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['0']['GW'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['0']['GT'] . "</td>";
	echo "<td class=\"staticTD\">"; if($PlayerProCareerSumSeasonOnly['0']['FaceOffTotal'] > 0){echo sprintf("%.2f%%",($PlayerProCareerSumSeasonOnly['0']['FaceOffWon'] / $PlayerProCareerSumSeasonOnly['0']['FaceOffTotal']*100));}else{echo "0%";}echo "</td>";					
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['0']['FaceOffTotal'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['0']['GiveAway'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['0']['TakeAway'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['0']['EmptyNetGoal'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['0']['HatTrick'] . "</td>";	
	echo "<td class=\"staticTD\">"; if($PlayerProCareerSumSeasonOnly['0']['SecondPlay'] > 0){echo number_format($PlayerProCareerSumSeasonOnly['0']['P'] / $PlayerProCareerSumSeasonOnly['0']['SecondPlay'] * 60 *20 ,2);}else{echo "0";}echo "</td>";					
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['0']['PenalityShotsScore'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['0']['PenalityShotsTotal'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['0']['FightW'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['0']['FightL'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['0']['FightT'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['0']['Star1'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['0']['Star2'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumSeasonOnly['0']['Star3'] . "</td>";
	echo "</tr>\n";
}}

echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"45\"><strong>" . $SearchLang['Playoff'] . "</strong></td></tr>\n";
if (empty($PlayerProCareerPlayoff) == false){foreach($PlayerProCareerPlayoff as $Row) {
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
	If ($Row['ShotsPCT'] == Null){echo "<td>0%</td>";}else{echo "<td>" . number_Format($Row['ShotsPCT'],2) . "%</td>";}
	echo "<td>" . $Row['ShotsBlock'] . "</td>";	
	echo "<td>" . Floor($Row['SecondPlay']/60) . "</td>";
	If ($Row['AMG'] == Null){echo "<td>0</td>";}else{echo "<td>" . number_Format($Row['AMG'],2) . "</td>";}	
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
	If ($Row['FaceoffPCT'] == Null){echo "<td>0</td>";}else{echo "<td>" . number_Format($Row['FaceoffPCT'],2) . "</td>";}
	echo "<td>" . $Row['FaceOffTotal'] . "</td>";
	echo "<td>" . $Row['GiveAway'] . "</td>";
	echo "<td>" . $Row['TakeAway'] . "</td>";
	echo "<td>" . $Row['EmptyNetGoal'] . "</td>";
	echo "<td>" . $Row['HatTrick'] . "</td>";	
	If ($Row['P20'] == Null){echo "<td>0</td>";}else{echo "<td>" . number_Format($Row['P20'],2) . "</td>";}				
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

If ($PlayerProCareerSumPlayoffOnly != Null){If ($PlayerProCareerSumPlayoffOnly['0']['GP'] > 0){
	echo "<tr class=\"static\"><td colspan=\"2\"><strong>" . $PlayersLang['Total'] . " " . $SearchLang['Playoff']. "</strong></td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['0']['GP'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['0']['G'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['0']['A'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['0']['P'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['0']['PlusMinus'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['0']['Pim'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['0']['Pim5'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['0']['Hits'] . "</td>";	
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['0']['HitsTook'] . "</td>";		
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['0']['Shots'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['0']['OwnShotsBlock'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['0']['OwnShotsMissGoal'] . "</td>";
	echo "<td class=\"staticTD\">"; if($PlayerProCareerSumPlayoffOnly['0']['Shots'] > 0){echo sprintf("%.2f%%",($PlayerProCareerSumPlayoffOnly['0']['G'] / $PlayerProCareerSumPlayoffOnly['0']['Shots']*100));}else{echo "0%";}echo "</td>";				
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['0']['ShotsBlock'] . "</td>";	
	echo "<td class=\"staticTD\">" . Floor($PlayerProCareerSumPlayoffOnly['0']['SecondPlay']/60) . "</td>";
	echo "<td class=\"staticTD\">"; if($PlayerProCareerSumPlayoffOnly['0']['GP'] > 0){echo number_format(($PlayerProCareerSumPlayoffOnly['0']['SecondPlay'] / 60 / $PlayerProCareerSumPlayoffOnly['0']['GP']),2);}else{echo "0";}echo "</td>";					
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['0']['PPG'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['0']['PPA'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['0']['PPP'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['0']['PPShots'] . "</td>";
	echo "<td class=\"staticTD\">" . Floor($PlayerProCareerSumPlayoffOnly['0']['PPSecondPlay']/60) . "</td>";	
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['0']['PKG'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['0']['PKA'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['0']['PKP'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['0']['PKShots'] . "</td>";
	echo "<td class=\"staticTD\">" . Floor($PlayerProCareerSumPlayoffOnly['0']['PKSecondPlay']/60) . "</td>";	
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['0']['GW'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['0']['GT'] . "</td>";
	echo "<td class=\"staticTD\">"; if($PlayerProCareerSumPlayoffOnly['0']['FaceOffTotal'] > 0){echo sprintf("%.2f%%",($PlayerProCareerSumPlayoffOnly['0']['FaceOffWon'] / $PlayerProCareerSumPlayoffOnly['0']['FaceOffTotal']*100));}else{echo "0%";}echo "</td>";						
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['0']['FaceOffTotal'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['0']['GiveAway'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['0']['TakeAway'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['0']['EmptyNetGoal'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['0']['HatTrick'] . "</td>";	
	echo "<td class=\"staticTD\">"; if($PlayerProCareerSumPlayoffOnly['0']['SecondPlay'] > 0){echo number_format($PlayerProCareerSumPlayoffOnly['0']['P'] / $PlayerProCareerSumPlayoffOnly['0']['SecondPlay'] * 60 *20 ,2);}else{echo "0";}echo "</td>";							
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['0']['PenalityShotsScore'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['0']['PenalityShotsTotal'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['0']['FightW'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['0']['FightL'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['0']['FightT'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['0']['Star1'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['0']['Star2'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerProCareerSumPlayoffOnly['0']['Star3'] . "</td>";
	echo "</tr>\n";
}}
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
<?php If($PlayerFarmCareerSeason <> Null){
echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"45\"><strong>" . $PlayersLang['RegularSeason'] . "</strong></td></tr>\n";
if (empty($PlayerFarmCareerSeason) == false){foreach($PlayerFarmCareerSeason as $Row) {
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
	If ($Row['ShotsPCT'] == Null){echo "<td>0%</td>";}else{echo "<td>" . number_Format($Row['ShotsPCT'],2) . "%</td>";}
	echo "<td>" . $Row['ShotsBlock'] . "</td>";	
	echo "<td>" . Floor($Row['SecondPlay']/60) . "</td>";
	If ($Row['AMG'] == Null){echo "<td>0</td>";}else{echo "<td>" . number_Format($Row['AMG'],2) . "</td>";}		
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
	If ($Row['FaceoffPCT'] == Null){echo "<td>0</td>";}else{echo "<td>" . number_Format($Row['FaceoffPCT'],2) . "</td>";}
	echo "<td>" . $Row['FaceOffTotal'] . "</td>";
	echo "<td>" . $Row['GiveAway'] . "</td>";
	echo "<td>" . $Row['TakeAway'] . "</td>";
	echo "<td>" . $Row['EmptyNetGoal'] . "</td>";
	echo "<td>" . $Row['HatTrick'] . "</td>";	
	If ($Row['P20'] == Null){echo "<td>0</td>";}else{echo "<td>" . number_Format($Row['P20'],2) . "</td>";}			
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

if ($PlayerFarmCareerSumSeasonOnly != Null){if ($PlayerFarmCareerSumSeasonOnly['0']['GP'] > 0){
	echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"2\"><strong>" . $PlayersLang['Total'] . " " . $PlayersLang['RegularSeason']. "</strong></td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['0']['GP'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['0']['G'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['0']['A'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['0']['P'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['0']['PlusMinus'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['0']['Pim'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['0']['Pim5'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['0']['Hits'] . "</td>";	
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['0']['HitsTook'] . "</td>";		
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['0']['Shots'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['0']['OwnShotsBlock'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['0']['OwnShotsMissGoal'] . "</td>";
	echo "<td class=\"staticTD\">"; if($PlayerFarmCareerSumSeasonOnly['0']['Shots'] > 0){echo sprintf("%.2f%%",($PlayerFarmCareerSumSeasonOnly['0']['G'] / $PlayerFarmCareerSumSeasonOnly['0']['Shots']*100));}else{echo "0%";}echo "</td>";		
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['0']['ShotsBlock'] . "</td>";	
	echo "<td class=\"staticTD\">" . Floor($PlayerFarmCareerSumSeasonOnly['0']['SecondPlay']/60) . "</td>";
	echo "<td class=\"staticTD\">"; if($PlayerFarmCareerSumSeasonOnly['0']['GP'] > 0){echo number_format(($PlayerFarmCareerSumSeasonOnly['0']['SecondPlay'] / 60 / $PlayerFarmCareerSumSeasonOnly['0']['GP']),2);}else{echo "0";}echo "</td>";						
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['0']['PPG'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['0']['PPA'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['0']['PPP'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['0']['PPShots'] . "</td>";
	echo "<td class=\"staticTD\">" . Floor($PlayerFarmCareerSumSeasonOnly['0']['PPSecondPlay']/60) . "</td>";	
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['0']['PKG'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['0']['PKA'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['0']['PKP'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['0']['PKShots'] . "</td>";
	echo "<td class=\"staticTD\">" . Floor($PlayerFarmCareerSumSeasonOnly['0']['PKSecondPlay']/60) . "</td>";	
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['0']['GW'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['0']['GT'] . "</td>";
	echo "<td class=\"staticTD\">"; if($PlayerFarmCareerSumSeasonOnly['0']['FaceOffTotal'] > 0){echo sprintf("%.2f%%",($PlayerFarmCareerSumSeasonOnly['0']['FaceOffWon'] / $PlayerFarmCareerSumSeasonOnly['0']['FaceOffTotal']*100));}else{echo "0%";}echo "</td>";						
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['0']['FaceOffTotal'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['0']['GiveAway'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['0']['TakeAway'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['0']['EmptyNetGoal'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['0']['HatTrick'] . "</td>";	
	echo "<td class=\"staticTD\">"; if($PlayerFarmCareerSumSeasonOnly['0']['SecondPlay'] > 0){echo number_format($PlayerFarmCareerSumSeasonOnly['0']['P'] / $PlayerFarmCareerSumSeasonOnly['0']['SecondPlay'] * 60 *20 ,2);}else{echo "0";}echo "</td>";							
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['0']['PenalityShotsScore'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['0']['PenalityShotsTotal'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['0']['FightW'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['0']['FightL'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['0']['FightT'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['0']['Star1'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['0']['Star2'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumSeasonOnly['0']['Star3'] . "</td>";
	echo "</tr>\n";
}}

echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"45\"><strong>" . $SearchLang['Playoff'] . "</strong></td></tr>\n";
if (empty($PlayerFarmCareerPlayoff) == false){foreach($PlayerFarmCareerPlayoff as $Row) {
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
	If ($Row['ShotsPCT'] == Null){echo "<td>0%</td>";}else{echo "<td>" . number_Format($Row['ShotsPCT'],2) . "%</td>";}
	echo "<td>" . $Row['ShotsBlock'] . "</td>";	
	echo "<td>" . Floor($Row['SecondPlay']/60) . "</td>";
	If ($Row['AMG'] == Null){echo "<td>0</td>";}else{echo "<td>" . number_Format($Row['AMG'],2) . "</td>";}	
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
	If ($Row['FaceoffPCT'] == Null){echo "<td>0</td>";}else{echo "<td>" . number_Format($Row['FaceoffPCT'],2) . "</td>";}	
	echo "<td>" . $Row['FaceOffTotal'] . "</td>";
	echo "<td>" . $Row['GiveAway'] . "</td>";
	echo "<td>" . $Row['TakeAway'] . "</td>";
	echo "<td>" . $Row['EmptyNetGoal'] . "</td>";
	echo "<td>" . $Row['HatTrick'] . "</td>";	
	If ($Row['P20'] == Null){echo "<td>0</td>";}else{echo "<td>" . number_Format($Row['P20'],2) . "</td>";}			
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

If ($PlayerFarmCareerSumPlayoffOnly != Null){If ($PlayerFarmCareerSumPlayoffOnly['0']['GP'] > 0){
	echo "<tr class=\"static\"><td colspan=\"2\"><strong>" . $PlayersLang['Total'] . " " . $SearchLang['Playoff']. "</strong></td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['0']['GP'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['0']['G'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['0']['A'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['0']['P'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['0']['PlusMinus'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['0']['Pim'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['0']['Pim5'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['0']['Hits'] . "</td>";	
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['0']['HitsTook'] . "</td>";		
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['0']['Shots'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['0']['OwnShotsBlock'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['0']['OwnShotsMissGoal'] . "</td>";
	echo "<td class=\"staticTD\">"; if($PlayerFarmCareerSumPlayoffOnly['0']['Shots'] > 0){echo sprintf("%.2f%%",($PlayerFarmCareerSumPlayoffOnly['0']['G'] / $PlayerFarmCareerSumPlayoffOnly['0']['Shots']*100));}else{echo "0%";}echo "</td>";						
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['0']['ShotsBlock'] . "</td>";	
	echo "<td class=\"staticTD\">" . Floor($PlayerFarmCareerSumPlayoffOnly['0']['SecondPlay']/60) . "</td>";
	echo "<td class=\"staticTD\">"; if($PlayerFarmCareerSumPlayoffOnly['0']['GP'] > 0){echo number_format(($PlayerFarmCareerSumPlayoffOnly['0']['SecondPlay'] / 60 / $PlayerFarmCareerSumPlayoffOnly['0']['GP']),2);}else{echo "0";}echo "</td>";		
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['0']['PPG'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['0']['PPA'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['0']['PPP'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['0']['PPShots'] . "</td>";
	echo "<td class=\"staticTD\">" . Floor($PlayerFarmCareerSumPlayoffOnly['0']['PPSecondPlay']/60) . "</td>";	
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['0']['PKG'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['0']['PKA'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['0']['PKP'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['0']['PKShots'] . "</td>";
	echo "<td class=\"staticTD\">" . Floor($PlayerFarmCareerSumPlayoffOnly['0']['PKSecondPlay']/60) . "</td>";	
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['0']['GW'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['0']['GT'] . "</td>";
	echo "<td class=\"staticTD\">"; if($PlayerFarmCareerSumPlayoffOnly['0']['FaceOffTotal'] > 0){echo sprintf("%.2f%%",($PlayerFarmCareerSumPlayoffOnly['0']['FaceOffWon'] / $PlayerFarmCareerSumPlayoffOnly['0']['FaceOffTotal']*100));}else{echo "0%";}echo "</td>";							
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['0']['FaceOffTotal'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['0']['GiveAway'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['0']['TakeAway'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['0']['EmptyNetGoal'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['0']['HatTrick'] . "</td>";	
	echo "<td class=\"staticTD\">"; if($PlayerFarmCareerSumPlayoffOnly['0']['SecondPlay'] > 0){echo number_format($PlayerFarmCareerSumPlayoffOnly['0']['P'] / $PlayerFarmCareerSumPlayoffOnly['0']['SecondPlay'] * 60 *20 ,2);}else{echo "0";}echo "</td>";				
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['0']['PenalityShotsScore'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['0']['PenalityShotsTotal'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['0']['FightW'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['0']['FightL'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['0']['FightT'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['0']['Star1'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['0']['Star2'] . "</td>";
	echo "<td class=\"staticTD\">" . $PlayerFarmCareerSumPlayoffOnly['0']['Star3'] . "</td>";
	echo "</tr>\n";
}}
}?>
</tbody></table>
<br /></div>

</div>
</div>
</div>

<?php
if ($PlayerCareerStatFound == true){
	echo "<script type=\"text/javascript\">\$(function() {\$(\".STHSPHPProCareerStat_Table\").tablesorter( {widgets: ['staticRow', 'columnSelector'], widgetOptions : {columnSelector_container : \$('#tablesorter_ColumnSelector2'), columnSelector_layout : '<label><input type=\"checkbox\">{name}</label>', columnSelector_name  : 'title', columnSelector_mediaquery: true, columnSelector_mediaqueryName: 'Automatic', columnSelector_mediaqueryState: true, columnSelector_mediaqueryHidden: true, columnSelector_breakpoints : [ '20em', '40em', '60em', '80em', '90em', '95em' ],}});});</script>";
	echo "<script type=\"text/javascript\">\$(function() {\$(\".STHSPHPFarmCareerStat_Table\").tablesorter({widgets: ['staticRow', 'columnSelector'], widgetOptions : {columnSelector_container : \$('#tablesorter_ColumnSelector3'), columnSelector_layout : '<label><input type=\"checkbox\">{name}</label>', columnSelector_name  : 'title', columnSelector_mediaquery: true, columnSelector_mediaqueryName: 'Automatic', columnSelector_mediaqueryState: true, columnSelector_mediaqueryHidden: true, columnSelector_breakpoints : [ '20em', '40em', '60em', '80em', '90em', '95em' ],}});});</script>";
}
if ($PlayerProStatMultipleTeamFound == TRUE){
	echo "<script type=\"text/javascript\">\$(function() {\$(\".STHSPHPProPlayerStatPerTeam_Table\").tablesorter( {widgets: ['columnSelector', 'stickyHeaders', 'filter'], widgetOptions : {columnSelector_container : \$('#tablesorter_ColumnSelector4'), columnSelector_layout : '<label><input type=\"checkbox\">{name}</label>', columnSelector_name  : 'title', columnSelector_mediaquery: true, columnSelector_mediaqueryName: 'Automatic', columnSelector_mediaqueryState: true, columnSelector_mediaqueryHidden: true, columnSelector_breakpoints : [ '20em', '40em', '60em', '80em', '90em', '95em' ],filter_columnFilters: true,filter_placeholder: { search : '" . $TableSorterLang['Search'] . "' },filter_searchDelay : 1000,filter_reset: '.tablesorter_Reset'}});});</script>";
}
if ($PlayerFarmStatMultipleTeamFound == TRUE){
	echo "<script type=\"text/javascript\">\$(function() {\$(\".STHSPHPFarmPlayerStatPerTeam_Table\").tablesorter( {widgets: ['columnSelector', 'stickyHeaders', 'filter'], widgetOptions : {columnSelector_container : \$('#tablesorter_ColumnSelector5'), columnSelector_layout : '<label><input type=\"checkbox\">{name}</label>', columnSelector_name  : 'title', columnSelector_mediaquery: true, columnSelector_mediaqueryName: 'Automatic', columnSelector_mediaqueryState: true, columnSelector_mediaqueryHidden: true, columnSelector_breakpoints : [ '20em', '40em', '60em', '80em', '90em', '95em' ],filter_columnFilters: true,filter_placeholder: { search : '" . $TableSorterLang['Search'] . "' },filter_searchDelay : 1000,filter_reset: '.tablesorter_Reset'}});});</script>";
}
?>


<?php include "Footer.php";?>
