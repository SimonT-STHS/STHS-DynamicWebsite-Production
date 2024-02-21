<?php include "Header.php";
/*
Syntax to call this webpage should be GoaliesStat.php?Goalie=2 where only the number change and it's based on the UniqueID of Goalies.
*/
If ($lang == "fr"){include 'LanguageFR-Stat.php';}else{include 'LanguageEN-Stat.php';}
$Goalie = (integer)0;
$Query = (string)"";
$GoalieName = $PlayersLang['IncorrectGoalie'];
$LeagueName = (string)"";
$CareerLeaderSubPrintOut = (int)0;
$GoalieCareerStatFound = (boolean)false;
$GoalieProCareerSeason = Null;
$GoalieProCareerPlayoff = Null;
$GoalieProCareerSumSeasonOnly = Null;
$GoalieProCareerSumPlayoffOnly = Null;
$GoalieFarmCareerSeason = Null;
$GoalieFarmCareerPlayoff = Null;
$GoalieFarmCareerSumSeasonOnly = Null;
$GoalieFarmCareerSumPlayoffOnly = Null;
$GoalieProStatMultipleTeamFound = (boolean)FALSE;
$GoalieFarmStatMultipleTeamFound = (boolean)FALSE;

if(isset($_GET['Goalie'])){$Goalie = filter_var($_GET['Goalie'], FILTER_SANITIZE_NUMBER_INT);} 
try{
If (file_exists($DatabaseFile) == false){
	$Goalie = 0;
	$GoalieName = $DatabaseNotFound;
	$LeagueOutputOption = Null;
	$LeagueGeneral = Null;		
}else{
	$db = new SQLite3($DatabaseFile);
	$Query = "Select Name, OutputName, LeagueYearOutput, PreSeasonSchedule, PlayOffStarted from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);	
	$Query = "Select PlayersMugShotBaseURL, PlayersMugShotFileExtension,OutputSalariesRemaining,OutputSalariesAverageTotal,OutputSalariesAverageRemaining from LeagueOutputOption";
	$LeagueOutputOption = $db->querySingle($Query,true);		
}
If ($Goalie == 0){
	$GoalieInfo = Null;
	$GoalieProStat = Null;
	$GoalieFarmStat = Null;	
	echo "<style>.STHSPHPPlayerStat_Main {display:none;}</style>";
}else{
	$Query = "SELECT count(*) AS count FROM GoalerInfo WHERE Number = " . $Goalie;
	$Result = $db->querySingle($Query,true);
	If ($Result['count'] == 1){
		If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS Start Page PHP Performance : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}
		$Query = "SELECT GoalerInfo.*, TeamProInfo.Name AS ProTeamName FROM GoalerInfo LEFT JOIN TeamProInfo ON GoalerInfo.Team = TeamProInfo.Number WHERE GoalerInfo.Number = " . $Goalie;
		$GoalieInfo = $db->querySingle($Query,true);
		$Query = "SELECT GoalerProStat.*, ROUND((CAST(GoalerProStat.GA AS REAL) / (GoalerProStat.SecondPlay / 60))*60,3) AS GAA, ROUND((CAST(GoalerProStat.SA - GoalerProStat.GA AS REAL) / (GoalerProStat.SA)),3) AS PCT, ROUND((CAST(GoalerProStat.PenalityShotsShots - GoalerProStat.PenalityShotsGoals AS REAL) / (GoalerProStat.PenalityShotsShots)),3) AS PenalityShotsPCT  FROM GoalerProStat WHERE Number = " . $Goalie;
		$GoalieProStat = $db->querySingle($Query,true);
		$Query = "SELECT GoalerFarmStat.*, ROUND((CAST(GoalerFarmStat.GA AS REAL) / (GoalerFarmStat.SecondPlay / 60))*60,3) AS GAA, ROUND((CAST(GoalerFarmStat.SA - GoalerFarmStat.GA AS REAL) / (GoalerFarmStat.SA)),3) AS PCT, ROUND((CAST(GoalerFarmStat.PenalityShotsShots - GoalerFarmStat.PenalityShotsGoals AS REAL) / (GoalerFarmStat.PenalityShotsShots)),3) AS PenalityShotsPCT FROM GoalerFarmStat WHERE Number = " . $Goalie;
		$GoalieFarmStat = $db->querySingle($Query,true);
		
		$Query = "SELECT count(*) AS count FROM GoalerProStatMultipleTeam WHERE Number = " . $Goalie;
		$Result = $db->querySingle($Query,true);
		If ($Result['count'] > 0){$GoalieProStatMultipleTeamFound = TRUE;}
		
		$Query = "SELECT count(*) AS count FROM GoalerFarmStatMultipleTeam WHERE Number = " . $Goalie;
		$Result = $db->querySingle($Query,true);
		If ($Result['count'] > 0){$GoalieFarmStatMultipleTeamFound = TRUE;}
		
		If ($GoalieInfo['Team'] > 0){
			$Query = "SELECT MainTable.* FROM (SELECT PlayerInfo.Number, PlayerInfo.Name, PlayerInfo.Team, PlayerInfo.TeamName, PlayerInfo.URLLink, PlayerInfo.NHLID, 'False' AS PosG FROM PlayerInfo WHERE Team = " . $GoalieInfo['Team'] . " UNION ALL SELECT GoalerInfo.Number, GoalerInfo.Name, GoalerInfo.Team, GoalerInfo.TeamName, GoalerInfo.URLLink, GoalerInfo.NHLID, 'True' AS PosG FROM GoalerInfo WHERE Team = " . $GoalieInfo['Team'] . ") AS MainTable ORDER BY Name";
			$TeamPlayers = $db->query($Query);		
		}
		
		$LeagueName = $LeagueGeneral['Name'];		
		$GoalieName = $GoalieInfo['Name'];
		If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS Normal Query PHP Performance : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}

		If (file_exists($CareerStatDatabaseFile) == true){ /* CareerStat */
			$CareerStatdb = new SQLite3($CareerStatDatabaseFile);
			
			$CareerDBFormatV2CheckCheck = $CareerStatdb->querySingle("SELECT Count(name) AS CountName FROM sqlite_master WHERE type='table' AND name='LeagueGeneral'",true);
			If ($CareerDBFormatV2CheckCheck['CountName'] == 1){
				
				include "APIFunction.php";			
			
				$GoalieProCareerSeason = APIPost(array('GoalerStatProHistoryAllSeasonPerYear' => '', 'UniqueID' => $GoalieInfo['UniqueID']));
				If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS ProCareerSeason Page PHP Performance : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}
				$GoalieProCareerPlayoff = APIPost(array('GoalerStatProHistoryAllSeasonPerYear' => '', 'UniqueID' => $GoalieInfo['UniqueID'], 'Playoff' => ''));
				If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS ProCareerPlayoff Page PHP Performance : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}
				$GoalieProCareerSumSeasonOnly = APIPost(array('GoalerStatProHistoryAllSeasonMerge' => '', 'UniqueID' => $GoalieInfo['UniqueID']));
				If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS ProCareerSumSeasonOnly Page PHP Performance : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}
				$GoalieProCareerSumPlayoffOnly = APIPost(array('GoalerStatProHistoryAllSeasonMerge' => '', 'UniqueID' => $GoalieInfo['UniqueID'], 'Playoff' => ''));
				If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS ProCareerSumPlayoffOnly Page PHP Performance : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}
				
				$GoalieFarmCareerSeason = APIPost(array('GoalerStatFarmHistoryAllSeasonPerYear' => '', 'UniqueID' => $GoalieInfo['UniqueID']));
				If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS FarmCareerSeason  Page PHP Performance : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}
				$GoalieFarmCareerPlayoff = APIPost(array('GoalerStatFarmHistoryAllSeasonPerYear' => '', 'UniqueID' => $GoalieInfo['UniqueID'], 'Playoff' => ''));
				If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS FarmCareerPlayoff Page PHP Performance : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}
				$GoalieFarmCareerSumSeasonOnly = APIPost(array('GoalerStatFarmHistoryAllSeasonMerge' => '', 'UniqueID' => $GoalieInfo['UniqueID']));
				If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS FarmCareerSumSeasonOnly Page PHP Performance : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}
				$GoalieFarmCareerSumPlayoffOnly = APIPost(array('GoalerStatFarmHistoryAllSeasonMerge' => '', 'UniqueID' => $GoalieInfo['UniqueID'], 'Playoff' => ''));		
				If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS FarmCareerSumPlayoffOnly Page PHP Performance : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}
				
				$GoalieCareerStatFound = true;
			}
			If (isset($PerformanceMonitorStart)){echo "<script>console.log(\"STHS CareerStat Query PHP Performance : " . (microtime(true)-$PerformanceMonitorStart) . "\"); </script>";}
		}
		
	}else{
		$GoalieName = $PlayersLang['Goalienotfound'];
		$GoalieInfo = Null;
		$GoalieProStat = Null;
		$GoalieFarmStat = Null;	
		echo "<style>.STHSPHPPlayerStat_Main {display:none;}</style>";
	}
}} catch (Exception $e) {
	$Goalie = 0;
	$GoalieName = $DatabaseNotFound;
	$LeagueOutputOption = Null;
	$LeagueGeneral = Null;
	$GoalieInfo = Null;
	$GoalieProStat = Null;
	$GoalieFarmStat = Null;		
}
echo "<title>" . $LeagueName . " - " . $GoalieName . "</title>";
echo "<style>";
if ($GoalieCareerStatFound == true){
	echo "#tablesorter_colSelect2:checked + label {background: #5797d7;  border-color: #555;}";
	echo "#tablesorter_colSelect2:checked ~ #tablesorter_ColumnSelector2 {display: block;}";
	echo "#tablesorter_colSelect3:checked + label {background: #5797d7;  border-color: #555;}";
	echo "#tablesorter_colSelect3:checked ~ #tablesorter_ColumnSelector3 {display: block;}";	
}
if ($GoalieProStatMultipleTeamFound == true){
	echo "#tablesorter_colSelect4:checked + label {background: #5797d7;  border-color: #555;}";
	echo "#tablesorter_colSelect4:checked ~ #tablesorter_ColumnSelector4 {display: block;}";
}
if ($GoalieFarmStatMultipleTeamFound == true){
	echo "#tablesorter_colSelect5:checked + label {background: #5797d7;  border-color: #555;}";
	echo "#tablesorter_colSelect5:checked ~ #tablesorter_ColumnSelector5 {display: block;}";
}
echo "</style>";
?>
</head><body>
<?php include "Menu.php";?>
<div class="STHSPHPPlayerStat_PlayerNameHeader">
<?php
echo "<table class=\"STHSTableFullW STHSPHPPlayerMugShot\"><tr>";
If($GoalieInfo <> Null){If ($GoalieInfo['TeamThemeID'] > 0){echo "<td><img src=\"" . $ImagesCDNPath . "/images/" . $GoalieInfo['TeamThemeID'] .".png\" alt=\"\" class=\".STHSPHPTradeTeamImage {width:48px;height:48px;padding-left:0px;padding-right:8px;vertical-align:middle}\" /></td>";}}
echo "<td style=\"padding-bottom: 10px;\">" . $GoalieName . "";
If($GoalieInfo <> Null AND $LeagueOutputOption <> Null){
	if ($GoalieInfo['Retire'] == 'False'){
		echo "<div id=\"cssmenu\" style=\"display:inline-block\"><ul style=\"max-width:150px;width:100%;margin:0 auto\"><li style=\"font-size:24px;cursor:pointer;line-height:0\">&#9660;<ul style=\"max-height:250px;overflow-x:hidden;overflow-y:scroll\">";
		if (empty($TeamPlayers) == false){while ($Row = $TeamPlayers ->fetchArray()) { 
			if ($Row['PosG']== "True"){
				echo "<li style=\"text-align:left;display:flex\"><a href=\"GoalieReport.php?Goalie=" . $Row['Number'] . "\">" . $Row['Name'] . "</a></li>";
			}else{
				echo "<li style=\"text-align:left;display:flex\"><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . "</a></li>";
			}
		}}
		echo "</ul></li></ul></div><br /><br />" . $GoalieInfo['TeamName'] . "</td>";
	}else{
		echo " - " . $PlayersLang['Retire'] . "</td>";
	}	
	If ($LeagueOutputOption['PlayersMugShotBaseURL'] != "" AND $LeagueOutputOption['PlayersMugShotFileExtension'] != "" AND $GoalieInfo['NHLID'] != ""){
		echo "<td><img src=\"" . $LeagueOutputOption['PlayersMugShotBaseURL'] . $GoalieInfo['NHLID'] . "." . $LeagueOutputOption['PlayersMugShotFileExtension'] . "\" alt=\"" . $GoalieName . "\" class=\"STHSPHPPlayerReportHeadshot\" /></td>";
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
	<th><?php echo $PlayersLang['Age'];?></th>
	<th><?php echo $PlayersLang['Condition'];?></th>
	<th><?php echo $PlayersLang['Suspension'];?></th>	
	<th><?php echo $PlayersLang['Height'];?></th>
	<th><?php echo $PlayersLang['Weight'];?></th>	
	<th><?php echo $PlayersLang['Link'];?></th>	
</tr><tr>
	<td><?php If($GoalieInfo <> Null){echo $GoalieInfo['Age']; }?></td>	
	<td><?php If($GoalieInfo <> Null){if ($GoalieInfo <> Null){echo number_format(str_replace(",",".",$GoalieInfo['ConditionDecimal']),2);} }?></td>	
	<td><?php If($GoalieInfo <> Null){echo $GoalieInfo['Suspension']; }?></td>	
	<td><?php If($GoalieInfo <> Null){echo $GoalieInfo['Height']; }?></td>
	<td><?php If($GoalieInfo <> Null){echo $GoalieInfo['Weight']; }?></td>
	<td><?php 
	If($GoalieInfo <> Null){
		if ($GoalieInfo['URLLink'] != ""){echo "<a href=" . $GoalieInfo['URLLink'] . " target=\"new\">" . $PlayersLang['Link'] . "</a>";}
		if ($GoalieInfo['URLLink'] != "" AND $GoalieInfo['NHLID'] != ""){echo " / ";}
		if ($GoalieInfo['NHLID'] != ""){echo "<a href=\"https://www.nhl.com/player/" . $GoalieInfo['NHLID'] . "\" target=\"new\">" . $PlayersLang['NHLLink'] . "</a>";}
	}
	?></td>	
</tr>
</table>
<div class="STHSBlankDiv"></div>
<table class="STHSPHPPlayerStat_Table">
<tr>
	<th>SK</th>
	<th>DU</th>
	<th>EN</th>
	<th>SZ</th>
	<th>AG</th>
	<th>RB</th>
	<th>SC</th>
	<th>HS</th>
	<th>RT</th>
	<th>PH</th>
	<th>PS</th>
	<th>EX</th>
	<th>LD</th>
	<th>PO</th>
	<th>MO</th>
	<th>OV</th>
</tr><tr>
<?php
If($GoalieInfo != Null){
	echo "<td>" . $GoalieInfo['SK']. "</td>";
	echo "<td>" . $GoalieInfo['DU']. "</td>";
	echo "<td>" . $GoalieInfo['EN']. "</td>";
	echo "<td>" . $GoalieInfo['SZ']. "</td>";
	echo "<td>" . $GoalieInfo['AG']. "</td>";
	echo "<td>" . $GoalieInfo['RB']. "</td>";
	echo "<td>" . $GoalieInfo['SC']. "</td>";
	echo "<td>" . $GoalieInfo['HS']. "</td>";
	echo "<td>" . $GoalieInfo['RT']. "</td>";
	echo "<td>" . $GoalieInfo['PH']. "</td>";
	echo "<td>" . $GoalieInfo['PS']. "</td>";
	echo "<td>" . $GoalieInfo['EX']. "</td>";
	echo "<td>" . $GoalieInfo['LD']. "</td>";
	echo "<td>" . $GoalieInfo['PO']. "</td>";
	echo "<td>" . $GoalieInfo['MO']. "</td>";
	echo "<td>" . $GoalieInfo['Overall']. "</td>"; 
}?>
</tr>
</table>
<br />

<div class="tabsmain standard"><ul class="tabmain-links">
<li class="activemain"><a href="#tabmain1"><?php echo $PlayersLang['Information'];?></a></li>
<li><a href="#tabmain2"><?php echo $PlayersLang['ProStat'];?></a></li>
<li><a href="#tabmain3"><?php echo $PlayersLang['FarmStat'];?></a></li>
<?php
if ($GoalieProStatMultipleTeamFound == TRUE OR $GoalieFarmStatMultipleTeamFound == TRUE){echo "<li><a href=\"#tabmain8\">" . $PlayersLang['StatperTeam'] . "</a></li>";}
if ($GoalieCareerStatFound == true){
	echo "<li><a href=\"#tabmain4\">" . $PlayersLang['CareerProStat'] . "</a></li>";
	echo "<li><a href=\"#tabmain5\">" . $PlayersLang['CareerFarmStat'] . "</a></li>";
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
If($GoalieInfo != Null){
	echo "<td>" . $GoalieInfo['AgeDate']. "</td>";
	echo "<td>" . $GoalieInfo['Country']. "</td>";
	echo "<td>"; if ($GoalieInfo['Rookie']== "True"){ echo "Yes"; }else{echo "No";};echo "</td>";
	echo "<td>" . $GoalieInfo['Injury']. "</td>";	
	echo "<td>" . $GoalieInfo['NumberOfInjury']. "</td>";
	echo "<td>" . $GoalieInfo['StarPower']. "</td>";	
	echo "<td>"; If ($GoalieInfo['DraftYear'] == 0){echo "-";}else{echo $GoalieInfo['DraftYear'];};echo "</td>";
	echo "<td>"; If ($GoalieInfo['DraftOverallPick'] == 0){echo "-";}else{echo $GoalieInfo['DraftOverallPick'];};echo "</td>";
	echo "<td>" . $GoalieInfo['AcquiredType']. "</td>";
	echo "<td>" . $GoalieInfo['LastTradeDate']. "</td>";
}?>	
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
If($GoalieInfo != Null){
	echo "<td>"; if($GoalieInfo['AvailableforTrade']== "True"){ echo "Yes"; }else{echo "No";};echo "</td>";
	echo "<td>"; if($GoalieInfo['NoTrade']== "True"){ echo "Yes"; }else{echo "No";};echo "</td>";
	echo "<td>"; if($GoalieInfo['ForceWaiver']== "True"){ echo "Yes"; }else{echo "No";};echo "</td>";
	echo "<td>"; if (array_key_exists('WaiverPossible',$GoalieInfo)){if ($GoalieInfo['WaiverPossible']== "True"){ echo "Yes"; }else{echo "No";};echo "</td>";}else{echo "N/A";}
	echo "<td>"; if($GoalieInfo['CanPlayPro']== "True"){ echo "Yes"; }else{echo "No";};echo "</td>";
	echo "<td>"; if($GoalieInfo['CanPlayFarm']== "True"){ echo "Yes"; }else{echo "No";};echo "</td>";	
	echo "<td>"; if($GoalieInfo['ExcludeSalaryCap']== "True"){ echo "Yes"; }else{echo "No";};echo "</td>";
	echo "<td>"; if($GoalieInfo['ProSalaryinFarm']== "True"){ echo "Yes"; }else{echo "No";};echo "</td>";
	echo "<td>"; if($GoalieInfo['ForceUFA']== "True"){ echo "Yes"; }else{echo "No";};echo "</td>";
	echo "<td>"; if($GoalieInfo['EmergencyRecall']== "True"){ echo "Yes"; }else{echo "No";};echo "</td>";
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
	<td><?php if ($GoalieInfo <> Null){echo $GoalieInfo['Contract'];} ?></td>
	<td><?php if ($GoalieInfo <> Null){echo $GoalieInfo['ContractSignatureDate'];} ?></td>
	<?php if($LeagueOutputOption != Null){if($LeagueOutputOption['OutputSalariesAverageTotal'] == "True"){echo "<td>";if ($GoalieInfo <> Null){echo number_format($GoalieInfo['SalaryAverage'],0) . "$";}echo "</td>";}}?>
	<td><?php if ($GoalieInfo <> Null){echo number_format($GoalieInfo['Salary1'],0) . "$";} ?></td>
	<?php if($LeagueOutputOption != Null){if($LeagueOutputOption['OutputSalariesRemaining'] == "True"){echo "<td>";if ($GoalieInfo <> Null){echo number_format($GoalieInfo['SalaryRemaining'],0) . "$";}echo "</td>";}}?>
	<?php if($LeagueOutputOption != Null){if($LeagueOutputOption['OutputSalariesAverageRemaining'] == "True"){echo "<td>";if ($GoalieInfo <> Null){echo number_format($GoalieInfo['SalaryAverageRemaining'],0) . "$";}echo "</td>";}}?>
	<?php
	echo "<td>"; if ($GoalieInfo <> Null){echo number_format($GoalieInfo['SalaryCap'],0);}; echo "$</td>";
	echo "<td>"; if ($GoalieInfo <> Null){echo number_format($GoalieInfo['SalaryCapRemaining'],0);}; echo "$</td>";
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
If($GoalieInfo != Null){
	echo "<td>"; If($GoalieInfo['Salary2'] > 0){echo number_format($GoalieInfo['Salary2'],0) . "$";}else{echo "-";}echo "</td>";
	echo "<td>"; If($GoalieInfo['Salary3'] > 0){echo number_format($GoalieInfo['Salary3'],0) . "$";}else{echo "-";}echo "</td>";
	echo "<td>"; If($GoalieInfo['Salary4'] > 0){echo number_format($GoalieInfo['Salary4'],0) . "$";}else{echo "-";}echo "</td>";
	echo "<td>"; If($GoalieInfo['Salary5'] > 0){echo number_format($GoalieInfo['Salary5'],0) . "$";}else{echo "-";}echo "</td>";
	echo "<td>"; If($GoalieInfo['Salary6'] > 0){echo number_format($GoalieInfo['Salary6'],0) . "$";}else{echo "-";}echo "</td>";
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
If($GoalieInfo != Null){
	echo "<td>"; If($GoalieInfo['Salary2'] > 0){ if($GoalieInfo['NoTrade2']== "True"){ echo "Yes"; }else{echo "No";}}else{echo "-";}echo "</td>";
	echo "<td>"; If($GoalieInfo['Salary3'] > 0){ if($GoalieInfo['NoTrade3']== "True"){ echo "Yes"; }else{echo "No";}}else{echo "-";}echo "</td>";
	echo "<td>"; If($GoalieInfo['Salary4'] > 0){ if($GoalieInfo['NoTrade4']== "True"){ echo "Yes"; }else{echo "No";}}else{echo "-";}echo "</td>";
	echo "<td>"; If($GoalieInfo['Salary5'] > 0){ if($GoalieInfo['NoTrade5']== "True"){ echo "Yes"; }else{echo "No";}}else{echo "-";}echo "</td>";
	echo "<td>"; If($GoalieInfo['Salary6'] > 0){ if($GoalieInfo['NoTrade6']== "True"){ echo "Yes"; }else{echo "No";}}else{echo "-";}echo "</td>";
}?>
</tr>
</table>
<div class="STHSBlankDiv"></div>

<br /><br /></div>
<div class="tabmain" id="tabmain2">
<br /><div class="STHSPHPPlayerStat_TabHeader"><?php echo $PlayersLang['ProStat'];?></div><br />
<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $GeneralStatLang['GamePlayed'];?></th>
	<th><?php echo $GeneralStatLang['Wins'];?></th>
	<th><?php echo $GeneralStatLang['Losses'];?></th>
	<th><?php echo $GeneralStatLang['OvertimeLosses'];?></th>
	<th><?php echo $GeneralStatLang['SavePCT'];?></th>
	<th><?php echo $GeneralStatLang['GoalsAgainstAverage'];?></th>
	<th><?php echo $GeneralStatLang['MinutesPlayed'];?></th>
</tr><tr>
	<td><?php if ($GoalieProStat <> Null){echo $GoalieProStat['GP'];} ?></td>
	<td><?php if ($GoalieProStat <> Null){echo $GoalieProStat['W'];} ?></td>
	<td><?php if ($GoalieProStat <> Null){echo $GoalieProStat['L'];} ?></td>
	<td><?php if ($GoalieProStat <> Null){echo $GoalieProStat['OTL'];} ?></td>
	<td><?php if ($GoalieProStat <> Null){echo number_Format($GoalieProStat['PCT'],3);} ?></td>
	<td><?php if ($GoalieProStat <> Null){echo number_Format($GoalieProStat['GAA'],2);} ?></td>
	<td><?php if ($GoalieProStat <> Null){echo Floor($GoalieProStat['SecondPlay']/60);} ?></td>		
</tr>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $GeneralStatLang['PenaltyMinutes'];?></th>
	<th><?php echo $GeneralStatLang['Shutouts'];?></th>
	<th><?php echo $GeneralStatLang['GoalsAgainst'];?></th>
	<th><?php echo $GeneralStatLang['ShotsAgainst'];?></th>
	<th><?php echo $GeneralStatLang['ShotsAgainstRebound'];?></th>
	<th><?php echo $GeneralStatLang['Assists'];?></th>
	<th><?php echo $GeneralStatLang['EmptyNetGoals'];?></th>	
</tr><tr>
	<td><?php if ($GoalieProStat <> Null){echo $GoalieProStat['Pim'];} ?></td>
	<td><?php if ($GoalieProStat <> Null){echo $GoalieProStat['Shootout'];} ?></td>
	<td><?php if ($GoalieProStat <> Null){echo $GoalieProStat['GA'];} ?></td>
	<td><?php if ($GoalieProStat <> Null){echo $GoalieProStat['SA'];} ?></td>
	<td><?php if ($GoalieProStat <> Null){echo $GoalieProStat['SARebound'];} ?></td>
	<td><?php if ($GoalieProStat <> Null){echo $GoalieProStat['A'];} ?></td>
	<td><?php if ($GoalieProStat <> Null){echo $GoalieProStat['EmptyNetGoal'];} ?></td>		
</tr>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $GeneralStatLang['PenaltyShotsSavePCT'];?></th>
	<th><?php echo $GeneralStatLang['PenaltyShotsAgainst'];?></th>
	<th><?php echo $GeneralStatLang['PenaltyShotsGoals'];?></th>
	<th><?php echo $GeneralStatLang['NumberStartGoalie'];?></th>	
	<th><?php echo $GeneralStatLang['NumberBackupGoalie'];?></th>	
</tr><tr>		
	<td><?php if ($GoalieProStat <> Null){echo number_Format($GoalieProStat['PenalityShotsPCT'],3);} ?></td>
	<td><?php if ($GoalieProStat <> Null){echo $GoalieProStat['PenalityShotsShots'];} ?></td>
	<td><?php if ($GoalieProStat <> Null){echo $GoalieProStat['PenalityShotsGoals'];} ?></td>	
	<td><?php if ($GoalieProStat <> Null){echo $GoalieProStat['StartGoaler'];} ?></td>
	<td><?php if ($GoalieProStat <> Null){echo $GoalieProStat['BackupGoaler'];} ?></td>	
</tr>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $GeneralStatLang['NumberTimeStar1'];?></th>
	<th><?php echo $GeneralStatLang['NumberTimeStar2'];?></th>
	<th><?php echo $GeneralStatLang['NumberTimeStar3'];?></th>	
</tr><tr>		
	<td><?php if ($GoalieProStat <> Null){echo $GoalieProStat['Star1'];} ?></td>	
	<td><?php if ($GoalieProStat <> Null){echo $GoalieProStat['Star2'];} ?></td>
	<td><?php if ($GoalieProStat <> Null){echo $GoalieProStat['Star3'];} ?></td>
</tr>
</table>

<br /><br /></div>
<div class="tabmain" id="tabmain3">
<br /><div class="STHSPHPPlayerStat_TabHeader"><?php echo $PlayersLang['FarmStat'];?></div><br />
<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $GeneralStatLang['GamePlayed'];?></th>
	<th><?php echo $GeneralStatLang['Wins'];?></th>
	<th><?php echo $GeneralStatLang['Losses'];?></th>
	<th><?php echo $GeneralStatLang['OvertimeLosses'];?></th>
	<th><?php echo $GeneralStatLang['SavePCT'];?></th>
	<th><?php echo $GeneralStatLang['GoalsAgainstAverage'];?></th>
	<th><?php echo $GeneralStatLang['MinutesPlayed'];?></th>
</tr><tr>
	<td><?php if ($GoalieFarmStat <> Null){echo $GoalieFarmStat['GP'];} ?></td>
	<td><?php if ($GoalieFarmStat <> Null){echo $GoalieFarmStat['W'];} ?></td>
	<td><?php if ($GoalieFarmStat <> Null){echo $GoalieFarmStat['L'];} ?></td>
	<td><?php if ($GoalieFarmStat <> Null){echo $GoalieFarmStat['OTL'];} ?></td>
	<td><?php if ($GoalieFarmStat <> Null){if($GoalieFarmStat['PCT'] <> Null){echo number_Format($GoalieFarmStat['PCT'],3);}} ?></td>
	<td><?php if ($GoalieFarmStat <> Null){if($GoalieFarmStat['GAA'] <> Null){echo number_Format($GoalieFarmStat['GAA'],2);}} ?></td>
	<td><?php if ($GoalieFarmStat <> Null){echo Floor($GoalieFarmStat['SecondPlay']/60);} ?></td>		
</tr>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $GeneralStatLang['PenaltyMinutes'];?></th>
	<th><?php echo $GeneralStatLang['Shutouts'];?></th>
	<th><?php echo $GeneralStatLang['GoalsAgainst'];?></th>
	<th><?php echo $GeneralStatLang['ShotsAgainst'];?></th>
	<th><?php echo $GeneralStatLang['ShotsAgainstRebound'];?></th>
	<th><?php echo $GeneralStatLang['Assists'];?></th>
	<th><?php echo $GeneralStatLang['EmptyNetGoals'];?></th>		
</tr><tr>
	<td><?php if ($GoalieFarmStat <> Null){echo $GoalieFarmStat['Pim'];} ?></td>
	<td><?php if ($GoalieFarmStat <> Null){echo $GoalieFarmStat['Shootout'];} ?></td>
	<td><?php if ($GoalieFarmStat <> Null){echo $GoalieFarmStat['GA'];} ?></td>
	<td><?php if ($GoalieFarmStat <> Null){echo $GoalieFarmStat['SA'];} ?></td>
	<td><?php if ($GoalieFarmStat <> Null){echo $GoalieFarmStat['SARebound'];} ?></td>
	<td><?php if ($GoalieFarmStat <> Null){echo $GoalieFarmStat['A'];} ?></td>
	<td><?php if ($GoalieFarmStat <> Null){echo $GoalieFarmStat['EmptyNetGoal'];} ?></td>		
</tr>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $GeneralStatLang['PenaltyShotsSavePCT'];?></th>
	<th><?php echo $GeneralStatLang['PenaltyShotsAgainst'];?></th>
	<th><?php echo $GeneralStatLang['PenaltyShotsGoals'];?></th>	
	<th><?php echo $GeneralStatLang['NumberStartGoalie'];?></th>	
	<th><?php echo $GeneralStatLang['NumberBackupGoalie'];?></th>	
</tr><tr>		
	<td><?php if ($GoalieFarmStat <> Null){if($GoalieFarmStat['PenalityShotsPCT'] <> Null){echo number_Format($GoalieFarmStat['PenalityShotsPCT'],3);}} ?></td>	
	<td><?php if ($GoalieFarmStat <> Null){echo $GoalieFarmStat['PenalityShotsShots'];} ?></td>
	<td><?php if ($GoalieFarmStat <> Null){echo $GoalieFarmStat['PenalityShotsGoals'];} ?></td>
	<td><?php if ($GoalieFarmStat <> Null){echo $GoalieFarmStat['StartGoaler'];} ?></td>
	<td><?php if ($GoalieFarmStat <> Null){echo $GoalieFarmStat['BackupGoaler'];} ?></td>	
</tr>
</table>
<div class="STHSBlankDiv"></div>

<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $GeneralStatLang['NumberTimeStar1'];?></th>
	<th><?php echo $GeneralStatLang['NumberTimeStar2'];?></th>
	<th><?php echo $GeneralStatLang['NumberTimeStar3'];?></th>	
</tr><tr>		
	<td><?php if ($GoalieFarmStat <> Null){echo $GoalieFarmStat['Star1'];} ?></td>	
	<td><?php if ($GoalieFarmStat <> Null){echo $GoalieFarmStat['Star2'];} ?></td>
	<td><?php if ($GoalieFarmStat <> Null){echo $GoalieFarmStat['Star3'];} ?></td>
</tr>
</table>
<br /><br /></div>

<div class="tabmain" id="tabmain8">
<br /><div class="STHSPHPPlayerStat_TabHeader"><?php echo $PlayersLang['StatperTeam'];?></div>

<?php 
if ($GoalieProStatMultipleTeamFound == TRUE){
	echo "<h2>" . $PlayersLang['ProStat'] . "</h2>";
	echo "<div style=\"width:99%;margin:auto;\"><div class=\"tablesorter_ColumnSelectorWrapper\"><input id=\"tablesorter_colSelect4\" type=\"checkbox\" class=\"hidden\"><label class=\"tablesorter_ColumnSelectorButton\" for=\"tablesorter_colSelect4\">" . $TableSorterLang['ShoworHideColumn'] . "</label><div id=\"tablesorter_ColumnSelector4\" class=\"tablesorter_ColumnSelector\"></div>"; include "FilterTip.php"; echo "</div></div>";
	
	$Query = "SELECT GoalerProStatMultipleTeam.*, ROUND((CAST(GoalerProStatMultipleTeam.GA AS REAL) / (GoalerProStatMultipleTeam.SecondPlay / 60))*60,3) AS GAA, ROUND((CAST(GoalerProStatMultipleTeam.SA - GoalerProStatMultipleTeam.GA AS REAL) / (GoalerProStatMultipleTeam.SA)),3) AS PCT, ROUND((CAST(GoalerProStatMultipleTeam.PenalityShotsShots - GoalerProStatMultipleTeam.PenalityShotsGoals AS REAL) / (GoalerProStatMultipleTeam.PenalityShotsShots)),3) AS PenalityShotsPCT, 0 as Star1, 0 as Star2, 0 As Star3 FROM  GoalerProStatMultipleTeam WHERE Number = " . $Goalie;
	$GoalieStat = $db->query($Query);
	$Team = (integer)-1;
	echo "<table class=\"tablesorter STHSPHPProGoalieStatPerTeam_Table\"><thead><tr>";
	include "GoaliesStatSub.php";
	echo "</tbody></table>";
}

if ($GoalieProStatMultipleTeamFound == TRUE AND $GoalieFarmStatMultipleTeamFound == TRUE){echo "<br /><hr /><br />";}

if ($GoalieFarmStatMultipleTeamFound == TRUE){
	echo "<h2>" . $PlayersLang['FarmStat'] . "</h2>";
	echo "<div style=\"width:99%;margin:auto;\"><div class=\"tablesorter_ColumnSelectorWrapper\"><input id=\"tablesorter_colSelect5\" type=\"checkbox\" class=\"hidden\"><label class=\"tablesorter_ColumnSelectorButton\" for=\"tablesorter_colSelect5\">" . $TableSorterLang['ShoworHideColumn'] . "</label><div id=\"tablesorter_ColumnSelector5\" class=\"tablesorter_ColumnSelector\"></div>"; include "FilterTip.php"; echo "</div></div>";
	
	$Query = "SELECT GoalerFarmStatMultipleTeam.*, ROUND((CAST(GoalerFarmStatMultipleTeam.GA AS REAL) / (GoalerFarmStatMultipleTeam.SecondPlay / 60))*60,3) AS GAA, ROUND((CAST(GoalerFarmStatMultipleTeam.SA - GoalerFarmStatMultipleTeam.GA AS REAL) / (GoalerFarmStatMultipleTeam.SA)),3) AS PCT, ROUND((CAST(GoalerFarmStatMultipleTeam.PenalityShotsShots - GoalerFarmStatMultipleTeam.PenalityShotsGoals AS REAL) / (GoalerFarmStatMultipleTeam.PenalityShotsShots)),3) AS PenalityShotsPCT, 0 as Star1, 0 as Star2, 0 As Star3 FROM  GoalerFarmStatMultipleTeam WHERE Number = " . $Goalie;
	$GoalieStat = $db->query($Query);
	$Team = (integer)-1;
	echo "<table class=\"tablesorter STHSPHPFarmGoaliesStatPerTeam_Table\"><thead><tr>";
	include "GoaliesStatSub.php";
	echo "</tbody></table>";
}
?>

<br /><br /></div>

<div class="tabmain" id="tabmain4">
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
<th data-priority="1" title="Wins" class="STHSW25">W</th>
<th data-priority="2" title="Losses" class="STHSW25">L</th>
<th data-priority="2" title="Overtime Losses" class="STHSW25">OTL</th>
<th data-priority="critical" title="Save Percentage" class="STHSW50">PCT</th>
<th data-priority="critical" title="Goals Against Average" class="STHSW50">GAA</th>
<th data-priority="3" title="Minutes Played" class="STHSW50">MP</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Penalty Minutes">PIM</th>
<th data-priority="4" title="Shutouts" class="STHSW25">SO</th>
<th data-priority="3" title="Goals Against" class="STHSW25">GA</th>
<th data-priority="3" title="Shots Against" class="STHSW45">SA</th>
<th data-priority="5" title="Shots Against Rebound" class="STHSW45">SAR</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Assists">A</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Empty net Goals">EG</th>
<th data-priority="4" title="Penalty Shots Save %" class="STHSW50">PS %</th>
<th data-priority="5" title="Penalty Shots Against" class="STHSW25">PSA</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Number of game goalies start as Start goalie">ST</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Number of game goalies start as Backup goalie">BG</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Number of time players was star #1 in a game">S1</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Number of time players was star #2 in a game">S2</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Number of time players was star #3 in a game">S3</th>
</tr></thead><tbody>
<?php If($GoalieProCareerSeason <> Null){
echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"23\"><strong>" . $PlayersLang['RegularSeason'] . "</strong></td></tr>\n";
if (empty($GoalieProCareerSeason) == false){foreach($GoalieProCareerSeason as $Row) {
	echo "<tr><td>" . $Row['TeamName'] . "</td>";
	echo "<td>" . $Row['Year'] . "</td>";
	echo "<td>" . $Row['GP'] . "</td>";
	echo "<td>" . $Row['W'] . "</td>";
	echo "<td>" . $Row['L'] . "</td>";
	echo "<td>" . $Row['OTL'] . "</td>";
	If ($Row['PCT'] == Null){echo "<td>0</td>";}else{echo "<td>" . number_Format($Row['PCT'],3) . "</td>";}
	If ($Row['GAA'] == Null){echo "<td>0</td>";}else{echo "<td>" . number_Format($Row['GAA'],2) . "</td>";}
	echo "<td>";if ($Row <> Null){echo Floor($Row['SecondPlay']/60);}; echo "</td>";
	echo "<td>" . $Row['Pim'] . "</td>";
	echo "<td>" . $Row['Shootout'] . "</td>";
	echo "<td>" . $Row['GA'] . "</td>";
	echo "<td>" . $Row['SA'] . "</td>";
	echo "<td>" . $Row['SARebound'] . "</td>";
	echo "<td>" . $Row['A'] . "</td>";
	echo "<td>" . $Row['EmptyNetGoal'] . "</td>";			
	If ($Row['PenalityShotsPCT'] == Null){echo "<td>0</td>";}else{echo "<td>" . number_Format($Row['PenalityShotsPCT'],3) . "</td>";}
	echo "<td>" . $Row['PenalityShotsShots'] . "</td>";
	echo "<td>" . $Row['StartGoaler'] . "</td>";
	echo "<td>" . $Row['BackupGoaler'] . "</td>";
	echo "<td>" . $Row['Star1'] . "</td>";
	echo "<td>" . $Row['Star2'] . "</td>";
	echo "<td>" . $Row['Star3'] . "</td>";
	echo "</tr>\n"; 
}}

if ($GoalieProCareerSumSeasonOnly != Null){if ($GoalieProCareerSumSeasonOnly['0']['GP'] > 0){
	echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"2\"><strong>" . $PlayersLang['Total'] . " " . $PlayersLang['RegularSeason']. "</strong></td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumSeasonOnly['0']['GP'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumSeasonOnly['0']['W'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumSeasonOnly['0']['L'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumSeasonOnly['0']['OTL'] . "</td>";
	echo "<td>"; if ($GoalieProCareerSumSeasonOnly['0']['SA'] > "0"){echo number_format(($GoalieProCareerSumSeasonOnly['0']['SA'] - $GoalieProCareerSumSeasonOnly['0']['GA']) / $GoalieProCareerSumSeasonOnly['0']['SA'] ,3);}else {echo "0";}	echo "</td>";
	echo "<td>"; if ($GoalieProCareerSumSeasonOnly['0']['SecondPlay'] > "0"){echo number_format($GoalieProCareerSumSeasonOnly['0']['GA'] / ($GoalieProCareerSumSeasonOnly['0']['SecondPlay'] / 60) *60,2);}else {echo "0%";}	echo "</td>";		
	echo "<td class=\"staticTD\">";if ($GoalieProCareerSumSeasonOnly <> Null){echo Floor($GoalieProCareerSumSeasonOnly['0']['SecondPlay']/60);}; echo "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumSeasonOnly['0']['Pim'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumSeasonOnly['0']['Shootout'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumSeasonOnly['0']['GA'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumSeasonOnly['0']['SA'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumSeasonOnly['0']['SARebound'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumSeasonOnly['0']['A'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumSeasonOnly['0']['EmptyNetGoal'] . "</td>";			
	echo "<td>"; if ($GoalieProCareerSumSeasonOnly['0']['PenalityShotsShots'] > "0"){echo number_format(($GoalieProCareerSumSeasonOnly['0']['PenalityShotsShots'] - $GoalieProCareerSumSeasonOnly['0']['PenalityShotsGoals']) / $GoalieProCareerSumSeasonOnly['0']['PenalityShotsShots'],3);}else {echo "0%";}echo "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumSeasonOnly['0']['PenalityShotsShots'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumSeasonOnly['0']['StartGoaler'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumSeasonOnly['0']['BackupGoaler'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumSeasonOnly['0']['Star1'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumSeasonOnly['0']['Star2'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumSeasonOnly['0']['Star3'] . "</td>";
	echo "</tr>\n";
}}

echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"23\"><strong>" . $SearchLang['Playoff'] . "</strong></td></tr>\n";
if (empty($GoalieProCareerPlayoff) == false){foreach($GoalieProCareerPlayoff as $Row) {
	echo "<tr><td>" . $Row['TeamName'] . "</td>";
	echo "<td>" . $Row['Year'] . "</td>";
	echo "<td>" . $Row['GP'] . "</td>";
	echo "<td>" . $Row['W'] . "</td>";
	echo "<td>" . $Row['L'] . "</td>";
	echo "<td>" . $Row['OTL'] . "</td>";
	If ($Row['PCT'] == Null){echo "<td>0</td>";}else{echo "<td>" . number_Format($Row['PCT'],3) . "</td>";}
	If ($Row['GAA'] == Null){echo "<td>0</td>";}else{echo "<td>" . number_Format($Row['GAA'],2) . "</td>";}
	echo "<td>";if ($Row <> Null){echo Floor($Row['SecondPlay']/60);}; echo "</td>";
	echo "<td>" . $Row['Pim'] . "</td>";
	echo "<td>" . $Row['Shootout'] . "</td>";
	echo "<td>" . $Row['GA'] . "</td>";
	echo "<td>" . $Row['SA'] . "</td>";
	echo "<td>" . $Row['SARebound'] . "</td>";
	echo "<td>" . $Row['A'] . "</td>";
	echo "<td>" . $Row['EmptyNetGoal'] . "</td>";			
	If ($Row['PenalityShotsPCT'] == Null){echo "<td>0</td>";}else{echo "<td>" . number_Format($Row['PenalityShotsPCT'],3) . "</td>";}
	echo "<td>" . $Row['PenalityShotsShots'] . "</td>";
	echo "<td>" . $Row['StartGoaler'] . "</td>";
	echo "<td>" . $Row['BackupGoaler'] . "</td>";
	echo "<td>" . $Row['Star1'] . "</td>";
	echo "<td>" . $Row['Star2'] . "</td>";
	echo "<td>" . $Row['Star3'] . "</td>";
	echo "</tr>\n";
}}

if ($GoalieProCareerSumPlayoffOnly != Null){If ($GoalieProCareerSumPlayoffOnly['0']['GP'] > 0){
	echo "<tr class=\"static\"><td colspan=\"2\" class=\"staticTD\"><strong>" . $PlayersLang['Total'] . " " . $SearchLang['Playoff']. "</strong></td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumPlayoffOnly['0']['GP'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumPlayoffOnly['0']['W'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumPlayoffOnly['0']['L'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumPlayoffOnly['0']['OTL'] . "</td>";
	echo "<td>"; if ($GoalieProCareerSumPlayoffOnly['0']['SA'] > "0"){echo number_format(($GoalieProCareerSumPlayoffOnly['0']['SA'] - $GoalieProCareerSumPlayoffOnly['0']['GA']) / $GoalieProCareerSumPlayoffOnly['0']['SA'] ,3);}else {echo "0";}	echo "</td>";
	echo "<td>"; if ($GoalieProCareerSumPlayoffOnly['0']['SecondPlay'] > "0"){echo number_format($GoalieProCareerSumPlayoffOnly['0']['GA'] / ($GoalieProCareerSumPlayoffOnly['0']['SecondPlay'] / 60) *60,2);}else {echo "0%";}	echo "</td>";	
	echo "<td class=\"staticTD\">";if ($GoalieProCareerSumPlayoffOnly <> Null){echo Floor($GoalieProCareerSumPlayoffOnly['0']['SecondPlay']/60);}; echo "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumPlayoffOnly['0']['Pim'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumPlayoffOnly['0']['Shootout'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumPlayoffOnly['0']['GA'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumPlayoffOnly['0']['SA'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumPlayoffOnly['0']['SARebound'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumPlayoffOnly['0']['A'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumPlayoffOnly['0']['EmptyNetGoal'] . "</td>";			
	echo "<td>"; if ($GoalieProCareerSumPlayoffOnly['0']['PenalityShotsShots'] > "0"){echo number_format(($GoalieProCareerSumPlayoffOnly['0']['PenalityShotsShots'] - $GoalieProCareerSumPlayoffOnly['0']['PenalityShotsGoals']) / $GoalieProCareerSumPlayoffOnly['0']['PenalityShotsShots'],3);}else {echo "0%";}echo "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumPlayoffOnly['0']['PenalityShotsShots'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumPlayoffOnly['0']['StartGoaler'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumPlayoffOnly['0']['BackupGoaler'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumPlayoffOnly['0']['Star1'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumPlayoffOnly['0']['Star2'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieProCareerSumPlayoffOnly['0']['Star3'] . "</td>";
	echo "</tr>\n";
}}
}?>
</tbody></table>
<br /></div>

<div class="tabmain" id="tabmain5">
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
<th data-priority="1" title="Wins" class="STHSW25">W</th>
<th data-priority="2" title="Losses" class="STHSW25">L</th>
<th data-priority="2" title="Overtime Losses" class="STHSW25">OTL</th>
<th data-priority="critical" title="Save Percentage" class="STHSW50">PCT</th>
<th data-priority="critical" title="Goals Against Average" class="STHSW50">GAA</th>
<th data-priority="3" title="Minutes Played" class="STHSW50">MP</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Penalty Minutes">PIM</th>
<th data-priority="4" title="Shutouts" class="STHSW25">SO</th>
<th data-priority="3" title="Goals Against" class="STHSW25">GA</th>
<th data-priority="3" title="Shots Against" class="STHSW45">SA</th>
<th data-priority="5" title="Shots Against Rebound" class="STHSW45">SAR</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Assists">A</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Empty net Goals">EG</th>
<th data-priority="4" title="Penalty Shots Save %" class="STHSW50">PS %</th>
<th data-priority="5" title="Penalty Shots Against" class="STHSW25">PSA</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Number of game goalies start as Start goalie">ST</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Number of game goalies start as Backup goalie">BG</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Number of time players was star #1 in a game">S1</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Number of time players was star #2 in a game">S2</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Number of time players was star #3 in a game">S3</th>
</tr></thead><tbody>
<?php If($GoalieFarmCareerSeason <> Null){
echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"23\"><strong>" . $PlayersLang['RegularSeason'] . "</strong></td></tr>\n";
if (empty($GoalieFarmCareerSeason) == false){foreach($GoalieFarmCareerSeason as $Row) {
	echo "<tr><td>" . $Row['TeamName'] . "</td>";
	echo "<td>" . $Row['Year'] . "</td>";
	echo "<td>" . $Row['GP'] . "</td>";
	echo "<td>" . $Row['W'] . "</td>";
	echo "<td>" . $Row['L'] . "</td>";
	echo "<td>" . $Row['OTL'] . "</td>";
	If ($Row['PCT'] == Null){echo "<td>0</td>";}else{echo "<td>" . number_Format($Row['PCT'],3) . "</td>";}
	If ($Row['GAA'] == Null){echo "<td>0</td>";}else{echo "<td>" . number_Format($Row['GAA'],2) . "</td>";}
	echo "<td>";if ($Row <> Null){echo Floor($Row['SecondPlay']/60);}; echo "</td>";
	echo "<td>" . $Row['Pim'] . "</td>";
	echo "<td>" . $Row['Shootout'] . "</td>";
	echo "<td>" . $Row['GA'] . "</td>";
	echo "<td>" . $Row['SA'] . "</td>";
	echo "<td>" . $Row['SARebound'] . "</td>";
	echo "<td>" . $Row['A'] . "</td>";
	echo "<td>" . $Row['EmptyNetGoal'] . "</td>";			
	If ($Row['PenalityShotsPCT'] == Null){echo "<td>0</td>";}else{echo "<td>" . number_Format($Row['PenalityShotsPCT'],3) . "</td>";}
	echo "<td>" . $Row['PenalityShotsShots'] . "</td>";
	echo "<td>" . $Row['StartGoaler'] . "</td>";
	echo "<td>" . $Row['BackupGoaler'] . "</td>";
	echo "<td>" . $Row['Star1'] . "</td>";
	echo "<td>" . $Row['Star2'] . "</td>";
	echo "<td>" . $Row['Star3'] . "</td>";
	echo "</tr>\n"; 
}}

if ($GoalieFarmCareerSumSeasonOnly != Null){if ($GoalieFarmCareerSumSeasonOnly['0']['GP'] > 0){
	/* Show FarmCareer Total for Season */
	echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"2\"><strong>" . $PlayersLang['Total'] . " " . $PlayersLang['RegularSeason']. "</strong></td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumSeasonOnly['0']['GP'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumSeasonOnly['0']['W'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumSeasonOnly['0']['L'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumSeasonOnly['0']['OTL'] . "</td>";
	echo "<td>"; if ($GoalieFarmCareerSumSeasonOnly['0']['SA'] > "0"){echo number_format(($GoalieFarmCareerSumSeasonOnly['0']['SA'] - $GoalieFarmCareerSumSeasonOnly['0']['GA']) / $GoalieFarmCareerSumSeasonOnly['0']['SA'] ,3);}else {echo "0";}	echo "</td>";
	echo "<td>"; if ($GoalieFarmCareerSumSeasonOnly['0']['SecondPlay'] > "0"){echo number_format($GoalieFarmCareerSumSeasonOnly['0']['GA'] / ($GoalieFarmCareerSumSeasonOnly['0']['SecondPlay'] / 60) *60,2);}else {echo "0%";}	echo "</td>";		
	echo "<td class=\"staticTD\">";if ($GoalieFarmCareerSumSeasonOnly <> Null){echo Floor($GoalieFarmCareerSumSeasonOnly['0']['SecondPlay']/60);}; echo "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumSeasonOnly['0']['Pim'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumSeasonOnly['0']['Shootout'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumSeasonOnly['0']['GA'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumSeasonOnly['0']['SA'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumSeasonOnly['0']['SARebound'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumSeasonOnly['0']['A'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumSeasonOnly['0']['EmptyNetGoal'] . "</td>";			
	echo "<td>"; if ($GoalieFarmCareerSumSeasonOnly['0']['PenalityShotsShots'] > "0"){echo number_format(($GoalieFarmCareerSumSeasonOnly['0']['PenalityShotsShots'] - $GoalieFarmCareerSumSeasonOnly['0']['PenalityShotsGoals']) / $GoalieFarmCareerSumSeasonOnly['0']['PenalityShotsShots'],3);}else {echo "0%";}echo "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumSeasonOnly['0']['PenalityShotsShots'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumSeasonOnly['0']['StartGoaler'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumSeasonOnly['0']['BackupGoaler'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumSeasonOnly['0']['Star1'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumSeasonOnly['0']['Star2'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumSeasonOnly['0']['Star3'] . "</td>";
	echo "</tr>\n";
}}

echo "<tr class=\"static\"><td class=\"staticTD\" colspan=\"23\"><strong>" . $SearchLang['Playoff'] . "</strong></td></tr>\n";
if (empty($GoalieFarmCareerPlayoff) == false){foreach($GoalieFarmCareerPlayoff as $Row) {
	echo "<tr><td>" . $Row['TeamName'] . "</td>";
	echo "<td>" . $Row['Year'] . "</td>";
	echo "<td>" . $Row['GP'] . "</td>";
	echo "<td>" . $Row['W'] . "</td>";
	echo "<td>" . $Row['L'] . "</td>";
	echo "<td>" . $Row['OTL'] . "</td>";
	If ($Row['PCT'] == Null){echo "<td>0</td>";}else{echo "<td>" . number_Format($Row['PCT'],3) . "</td>";}
	If ($Row['GAA'] == Null){echo "<td>0</td>";}else{echo "<td>" . number_Format($Row['GAA'],2) . "</td>";}
	echo "<td>";if ($Row <> Null){echo Floor($Row['SecondPlay']/60);}; echo "</td>";
	echo "<td>" . $Row['Pim'] . "</td>";
	echo "<td>" . $Row['Shootout'] . "</td>";
	echo "<td>" . $Row['GA'] . "</td>";
	echo "<td>" . $Row['SA'] . "</td>";
	echo "<td>" . $Row['SARebound'] . "</td>";
	echo "<td>" . $Row['A'] . "</td>";
	echo "<td>" . $Row['EmptyNetGoal'] . "</td>";			
	If ($Row['PenalityShotsPCT'] == Null){echo "<td>0</td>";}else{echo "<td>" . number_Format($Row['PenalityShotsPCT'],3) . "</td>";}
	echo "<td>" . $Row['PenalityShotsShots'] . "</td>";
	echo "<td>" . $Row['StartGoaler'] . "</td>";
	echo "<td>" . $Row['BackupGoaler'] . "</td>";
	echo "<td>" . $Row['Star1'] . "</td>";
	echo "<td>" . $Row['Star2'] . "</td>";
	echo "<td>" . $Row['Star3'] . "</td>";
	echo "</tr>\n"; 
}}

if ($GoalieFarmCareerSumPlayoffOnly != Null){If ($GoalieFarmCareerSumPlayoffOnly['0']['GP'] > 0){
	/* Show FarmCareer Total for Playoff */
	echo "<tr class=\"static\"><td colspan=\"2\" class=\"staticTD\"><strong>" . $PlayersLang['Total'] . " " . $SearchLang['Playoff']. "</strong></td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumPlayoffOnly['0']['GP'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumPlayoffOnly['0']['W'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumPlayoffOnly['0']['L'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumPlayoffOnly['0']['OTL'] . "</td>";
	echo "<td>"; if ($GoalieFarmCareerSumPlayoffOnly['0']['SA'] > "0"){echo number_format(($GoalieFarmCareerSumPlayoffOnly['0']['SA'] - $GoalieFarmCareerSumPlayoffOnly['0']['GA']) / $GoalieFarmCareerSumPlayoffOnly['0']['SA'] ,3);}else {echo "0";}	echo "</td>";
	echo "<td>"; if ($GoalieFarmCareerSumPlayoffOnly['0']['SecondPlay'] > "0"){echo number_format($GoalieFarmCareerSumPlayoffOnly['0']['GA'] / ($GoalieFarmCareerSumPlayoffOnly['0']['SecondPlay'] / 60) *60,2);}else {echo "0%";}	echo "</td>";		
	echo "<td class=\"staticTD\">";if ($GoalieFarmCareerSumPlayoffOnly <> Null){echo Floor($GoalieFarmCareerSumPlayoffOnly['0']['SecondPlay']/60);}; echo "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumPlayoffOnly['0']['Pim'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumPlayoffOnly['0']['Shootout'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumPlayoffOnly['0']['GA'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumPlayoffOnly['0']['SA'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumPlayoffOnly['0']['SARebound'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumPlayoffOnly['0']['A'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumPlayoffOnly['0']['EmptyNetGoal'] . "</td>";			
	echo "<td>"; if ($GoalieFarmCareerSumPlayoffOnly['0']['PenalityShotsShots'] > "0"){echo number_format(($GoalieFarmCareerSumPlayoffOnly['0']['PenalityShotsShots'] - $GoalieFarmCareerSumPlayoffOnly['0']['PenalityShotsGoals']) / $GoalieFarmCareerSumPlayoffOnly['0']['PenalityShotsShots'],3);}else {echo "0%";}echo "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumPlayoffOnly['0']['PenalityShotsShots'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumPlayoffOnly['0']['StartGoaler'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumPlayoffOnly['0']['BackupGoaler'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumPlayoffOnly['0']['Star1'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumPlayoffOnly['0']['Star2'] . "</td>";
	echo "<td class=\"staticTD\">" . $GoalieFarmCareerSumPlayoffOnly['0']['Star3'] . "</td>";
	echo "</tr>\n";
}}
}?>
</tbody></table>
<br /></div>

</div>
</div>
</div>

<?php
if ($GoalieCareerStatFound == true){
	echo "<script type=\"text/javascript\">\$(function() {\$(\".STHSPHPProCareerStat_Table\").tablesorter( {widgets: ['staticRow', 'columnSelector'], widgetOptions : {columnSelector_container : \$('#tablesorter_ColumnSelector2'), columnSelector_layout : '<label><input type=\"checkbox\">{name}</label>', columnSelector_name  : 'title', columnSelector_mediaquery: true, columnSelector_mediaqueryName: 'Automatic', columnSelector_mediaqueryState: true, columnSelector_mediaqueryHidden: true, columnSelector_breakpoints : [ '20em', '40em', '60em', '80em', '90em', '95em' ],}});});</script>";
	echo "<script type=\"text/javascript\">\$(function() {\$(\".STHSPHPFarmCareerStat_Table\").tablesorter({widgets: ['staticRow', 'columnSelector'], widgetOptions : {columnSelector_container : \$('#tablesorter_ColumnSelector3'), columnSelector_layout : '<label><input type=\"checkbox\">{name}</label>', columnSelector_name  : 'title', columnSelector_mediaquery: true, columnSelector_mediaqueryName: 'Automatic', columnSelector_mediaqueryState: true, columnSelector_mediaqueryHidden: true, columnSelector_breakpoints : [ '20em', '40em', '60em', '80em', '90em', '95em' ],}});});</script>";
}
if ($GoalieProStatMultipleTeamFound == TRUE){
	echo "<script type=\"text/javascript\">\$(function() {\$(\".STHSPHPProGoalieStatPerTeam_Table\").tablesorter( {widgets: ['columnSelector', 'stickyHeaders', 'filter'], widgetOptions : {columnSelector_container : \$('#tablesorter_ColumnSelector4'), columnSelector_layout : '<label><input type=\"checkbox\">{name}</label>', columnSelector_name  : 'title', columnSelector_mediaquery: true, columnSelector_mediaqueryName: 'Automatic', columnSelector_mediaqueryState: true, columnSelector_mediaqueryHidden: true, [ '20em', '40em', '60em', '80em', '90em', '95em' ],filter_columnFilters: true,filter_placeholder: { search : '" . $TableSorterLang['Search'] . "' },filter_searchDelay : 1000,filter_reset: '.tablesorter_Reset'}});});</script>";
}
if ($GoalieFarmStatMultipleTeamFound == TRUE){
	echo "<script type=\"text/javascript\">\$(function() {\$(\".STHSPHPFarmGoalieStatPerTeam_Table\").tablesorter( {widgets: ['columnSelector', 'stickyHeaders', 'filter'], widgetOptions : {columnSelector_container : \$('#tablesorter_ColumnSelector5'), columnSelector_layout : '<label><input type=\"checkbox\">{name}</label>', columnSelector_name  : 'title', columnSelector_mediaquery: true, columnSelector_mediaqueryName: 'Automatic', columnSelector_mediaqueryState: true, columnSelector_mediaqueryHidden: true, columnSelector_breakpoints : [ '20em', '40em', '60em', '80em', '90em', '95em' ],filter_columnFilters: true,filter_placeholder: { search : '" . $TableSorterLang['Search'] . "' },filter_searchDelay : 1000,filter_reset: '.tablesorter_Reset'}});});</script>";
}
?>

<?php include "Footer.php";?>
