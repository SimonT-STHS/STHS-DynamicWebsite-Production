<?php include "Header.php";
$PlayerBase = (integer)0;
$PlayerCompare1 = (integer)0;
$PlayerCompare2 = (integer)0;
$TeamBase = (integer)-1;
$TeamCompare1 = (integer)-1;
$TeamCompare2 = (integer)-1;
$Query = (string)"";
$PlayerBaseName = $PlayersLang['IncorrectGoalie'];
$PlayerCompare1Name = $PlayersLang['IncorrectGoalie'];
$PlayerCompare2Name = $PlayersLang['IncorrectGoalie'];
$LeagueName = (string)"";

if(isset($_GET['PlayerBase'])){$PlayerBase = filter_var($_GET['PlayerBase'], FILTER_SANITIZE_NUMBER_INT);} 
if(isset($_GET['TeamBase'])){$TeamBase = filter_var($_GET['TeamBase'], FILTER_SANITIZE_NUMBER_INT);} 
if(isset($_GET['PlayerCompare1'])){$PlayerCompare1 = filter_var($_GET['PlayerCompare1'], FILTER_SANITIZE_NUMBER_INT);} 
if(isset($_GET['TeamCompare1'])){$TeamCompare1 = filter_var($_GET['TeamCompare1'], FILTER_SANITIZE_NUMBER_INT);} 
if(isset($_GET['PlayerCompare2'])){$PlayerCompare2 = filter_var($_GET['PlayerCompare2'], FILTER_SANITIZE_NUMBER_INT);} 
if(isset($_GET['TeamCompare2'])){$TeamCompare2 = filter_var($_GET['TeamCompare2'], FILTER_SANITIZE_NUMBER_INT);} 

If (file_exists($DatabaseFile) == false){
	Goto STHSErrorGoaliesCompare;
}else{try{
	$db = new SQLite3($DatabaseFile);
	$Query = "Select Name, OutputName, LeagueYearOutput, PreSeasonSchedule, PlayOffStarted from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);	
	$LeagueName = $LeagueGeneral['Name'];	
	
	$Query = "Select OutputSalariesRemaining,OutputSalariesAverageTotal,OutputSalariesAverageRemaining from LeagueOutputOption";
	$LeagueOutputOption = $db->querySingle($Query,true);	
} catch (Exception $e) {
STHSErrorGoaliesCompare:
	$PlayerBaseName = $DatabaseNotFound;
	$LeagueOutputOption = Null;
	$LeagueGeneral = Null;
	$PlayerBase = (integer)0;
	$PlayerCompare1 = (integer)0;
	$PlayerCompare2 = (integer)0;	
}}

If ($PlayerBase == 0){
	$PlayerBaseInfo = Null;
	echo "<style>.STHSPHPPlayerStat_Main1 {display:none;}</style>";
	echo "<style>.STHSPHPPlayerStat_Main2 {display:none;}</style>";
}else{
	$Query = "SELECT count(*) AS count FROM GoalerInfo WHERE Number = " . $PlayerBase;
	$Result = $db->querySingle($Query,true);
	If ($Result['count'] == 1){
		$Query = "SELECT GoalerInfo.*, TeamProInfo.Name AS ProTeamName FROM GoalerInfo LEFT JOIN TeamProInfo ON GoalerInfo.Team = TeamProInfo.Number WHERE GoalerInfo.Retire = 'False' AND GoalerInfo.Number = " . $PlayerBase;
		$PlayerBaseInfo = $db->querySingle($Query,true);								
		$PlayerBaseName = $PlayerBaseInfo['Name'];	
	}else{
		$PlayerBaseName = $PlayersLang['Playernotfound'];
		$PlayerBaseInfo = Null;
	}
}

If ($PlayerCompare1 == 0){
	$PlayerCompare1Info = Null;
	echo "<style>.STHSPHPPlayerStat_Main1 {display:none;}</style>";
}else{
	$Query = "SELECT count(*) AS count FROM GoalerInfo WHERE Number = " . $PlayerCompare1;
	$Result = $db->querySingle($Query,true);
	If ($Result['count'] == 1){
		$Query = "SELECT GoalerInfo.*, TeamProInfo.Name AS ProTeamName FROM GoalerInfo LEFT JOIN TeamProInfo ON GoalerInfo.Team = TeamProInfo.Number WHERE GoalerInfo.Retire = 'False' AND GoalerInfo.Number = " . $PlayerCompare1;
		$PlayerCompare1Info = $db->querySingle($Query,true);								
		$PlayerCompare1Name = $PlayerCompare1Info['Name'];	
	}else{
		$PlayerCompare1Name = $PlayersLang['Playernotfound'];
		$PlayerCompare1Info = Null;
	}
}

If ($PlayerCompare2 == 0){
	$PlayerCompare2Info = Null;
	echo "<style>.STHSPHPPlayerStat_Main2 {display:none;}</style>";
}else{
	$Query = "SELECT count(*) AS count FROM GoalerInfo WHERE Number = " . $PlayerCompare2;
	$Result = $db->querySingle($Query,true);
	If ($Result['count'] == 1){
		$Query = "SELECT GoalerInfo.*, TeamProInfo.Name AS ProTeamName FROM GoalerInfo LEFT JOIN TeamProInfo ON GoalerInfo.Team = TeamProInfo.Number WHERE GoalerInfo.Retire = 'False' AND GoalerInfo.Number = " . $PlayerCompare2;
		$PlayerCompare2Info = $db->querySingle($Query,true);								
		$PlayerCompare2Name = $PlayerCompare2Info['Name'];	
	}else{
		$PlayerCompare2Name = $PlayersLang['Playernotfound'];
		$PlayerCompare2Info = Null;
	}
}

echo "<title>" . $LeagueName . " - " . $DynamicTitleLang['CompareGoalies'] . "</title>";
?>
<style>
.STHSPHPCompare_Select {width:200px;margin-left:10px;margin-right:10px}
</style>
</head><body>
<?php include "Menu.php";?>
<?php 
If ($PlayerBase != 0 AND $PlayerCompare1 != 0 AND $PlayerCompare2 != 0){
	echo "<h1>" . $PlayerBaseName . $PlayersLang['CompareTo'] . $PlayerCompare1Name . " & " . $PlayerCompare2Name . " " . $PlayersLang['Information'] . "</h1>"; 
}elseIf ($PlayerBase != 0 AND $PlayerCompare1 != 0){
	echo "<h1>" . $PlayerBaseName . $PlayersLang['CompareTo'] . $PlayerCompare1Name . " " . $PlayersLang['Information'] . "</h1>"; 
}else{
	echo "<h1>" . $DynamicTitleLang['CompareGoalies'] . "</h1>"; 
}?>
<br />


<div class="STHSPHPPlayerStat_Search">
<form action="GoaliesCompare.php" method="get">
<table class="STHSPHPPlayerStat_Table">
<tr>
	<th class="STHSW250"><?php echo $PlayersLang['BaseGoalieName'];?></th>
	<th class="STHSW250"><?php echo $PlayersLang['CompareGoalieName'];?>1</th>
	<th class="STHSW250"><?php echo $PlayersLang['CompareGoalieName'];?>2</th>
</tr>
<tr>
	<td style="padding:4px;">
	<select id="TeamBase" name="TeamBase" class="STHSPHPCompare_Select" size="20" required>
	<?php if(isset($db)){
	$Query = "Select Name, Number from TeamProInfo ORDER BY Name";
	$TeamListBase = $db->query($Query);	
		if (empty($TeamListBase) == false){while ($Row = $TeamListBase ->fetchArray()) {
		echo "<option value=\"" . $Row['Number'] . "\">" . $Row['Name'] . "</option>";}
	}
	echo "<option value=\"0\">" . $PlayersLang['Unassigned'] . "</option>";
	}?>
	</select>
	<select id="PlayerBase" name="PlayerBase" class="STHSPHPCompare_Select" size="20" required>
	<?php if(isset($db)){
	$Query = "SELECT GoalerInfo.Name, GoalerInfo.Number, GoalerInfo.Team FROM GoalerInfo ORDER BY GoalerInfo.Name";
	$PlayerListBase = $db->query($Query);
	if (empty($PlayerListBase) == false){while ($Row = $PlayerListBase ->fetchArray()) {
		echo "<option value=\"" . $Row['Number'] . "\" class=\"" . $Row['Team'] . "\">" . $Row['Name'] . "</option>";}
	}
	}?>
	</select>
	</td>
	<td style="padding:4px;">
		<select id="TeamCompare1" name="TeamCompare1" class="STHSPHPCompare_Select" size="20" required>
	<?php if(isset($db)){
	$Query = "Select Name, Number from TeamProInfo ORDER BY Name";
	$TeamListCompare = $db->query($Query);	
		if (empty($TeamListCompare) == false){while ($Row = $TeamListCompare ->fetchArray()) {
		echo "<option value=\"" . $Row['Number'] . "\">" . $Row['Name'] . "</option>";}
	}
	echo "<option value=\"0\">" . $PlayersLang['Unassigned'] . "</option>";
	}?>
	</select>
	<select id="PlayerCompare1" name="PlayerCompare1" class="STHSPHPCompare_Select" size="20" required>
	<?php if(isset($db)){
	$Query = "SELECT GoalerInfo.Name, GoalerInfo.Number, GoalerInfo.Team FROM GoalerInfo ORDER BY GoalerInfo.Name";
	$TeamListCompare = $db->query($Query);
	if (empty($TeamListCompare) == false){while ($Row = $TeamListCompare ->fetchArray()) {
		echo "<option value=\"" . $Row['Number'] . "\" class=\"" . $Row['Team'] . "\">" . $Row['Name'] . "</option>";}
	}
	}?>
	</select>	
	</td>
		<td style="padding:4px;">
		<select id="TeamCompare2" name="TeamCompare2" class="STHSPHPCompare_Select" size="20">
	<?php if(isset($db)){
	$Query = "Select Name, Number from TeamProInfo ORDER BY Name";
	$TeamListCompare = $db->query($Query);	
		if (empty($TeamListCompare) == false){while ($Row = $TeamListCompare ->fetchArray()) {
		echo "<option value=\"" . $Row['Number'] . "\">" . $Row['Name'] . "</option>";}
	}
	echo "<option value=\"0\">" . $PlayersLang['Unassigned'] . "</option>";
	}?>
	</select>
	<select id="PlayerCompare2" name="PlayerCompare2" class="STHSPHPCompare_Select" size="20">
	<?php if(isset($db)){
	$Query = "SELECT GoalerInfo.Name, GoalerInfo.Number, GoalerInfo.Team FROM GoalerInfo ORDER BY GoalerInfo.Name";
	$TeamListCompare = $db->query($Query);
	if (empty($TeamListCompare) == false){while ($Row = $TeamListCompare ->fetchArray()) {
		echo "<option value=\"" . $Row['Number'] . "\" class=\"" . $Row['Team'] . "\">" . $Row['Name'] . "</option>";}
	}
	}?>
	</select>	
	</td>
</tr>
<tr>
	<td colspan="3" class="STHSCenter"><br /><input type="submit" class="SubmitButton" value="<?php echo $SearchLang['Submit'];?>"><br /><br /></td>
</tr>
</table>
</form>
</div>

<br /><br />

<div class="STHSPHPPlayerStat_Main1">
<br />
<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $PlayersLang['PlayerName'];?></th>
	<th><?php echo $PlayersLang['TeamName'];?></th>
	<th><?php echo $PlayersLang['Age'];?></th>
	<th><?php echo $PlayersLang['Condition'];?></th>
	<th><?php echo $PlayersLang['Height'];?></th>
	<th><?php echo $PlayersLang['Weight'];?></th>
	<th><?php echo $PlayersLang['Link'];?></th>
	<th><?php echo $PlayersLang['Contract'];?></th>
	<?php if(isset($LeagueOutputOption)){if($LeagueOutputOption['OutputSalariesAverageTotal'] == "True"){echo "<th>" . $PlayersLang['SalaryAverage'] . "</th>";}}?>
	<th><?php echo $PlayersLang['SalaryYear'];?> 1</th>
	<?php if(isset($LeagueOutputOption)){if($LeagueOutputOption['OutputSalariesRemaining'] == "True"){ echo "<th>" . $PlayersLang['SalaryRemaining'] . "</th>";}}?>
	<?php if(isset($LeagueOutputOption)){if($LeagueOutputOption['OutputSalariesAverageRemaining'] == "True"){ echo "<th>" . $PlayersLang['SalaryAveRemaining']. "</th>";}}?>
	<th><?php echo $PlayersLang['SalaryCap'];?></th>
	<th><?php echo $PlayersLang['SalaryCapRemaining'];?></th>	
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
</tr>
<?php 
If ($PlayerBaseInfo <> Null AND $PlayerCompare1Info <> Null){
	echo "<tr>";
	echo "<td>" . "<a href=\"PlayerReport.php?Player=" . $PlayerBaseInfo['Number'] . "\">" . $PlayerBaseName . "</a></td>";
	echo "<td>" . $PlayerBaseInfo['TeamName'] . "</td>";
	echo "<td>" . $PlayerBaseInfo['Age'] . "</td>"; 
	echo "<td>"; if ($PlayerBaseInfo <> Null){echo number_format(str_replace(",",".",$PlayerBaseInfo['ConditionDecimal']),2);}echo "</td>";
	echo "<td>" . $PlayerBaseInfo['Height'] . "</td>"; 
	echo "<td>" . $PlayerBaseInfo['Weight'] . "</td>"; 
	echo "<td>";
	if ($PlayerBaseInfo['URLLink'] != ""){echo "<a href=" . $PlayerBaseInfo['URLLink'] . " target=\"new\">" . $PlayersLang['Link'] . "</a>";}
	if ($PlayerBaseInfo['URLLink'] != "" AND $PlayerBaseInfo['NHLID'] != ""){echo " / ";}
	if ($PlayerBaseInfo['NHLID'] != ""){echo "<a href=\"https://www.nhl.com/player/" . $PlayerBaseInfo['NHLID'] . "\" target=\"new\">" . $PlayersLang['NHLLink'] . "</a>";}
	echo "</td>";
	echo "<td>" . $PlayerBaseInfo['Contract'] . "</td>"; 
	if($LeagueOutputOption['OutputSalariesAverageTotal'] == "True"){echo "<td>";if ($PlayerBaseInfo <> Null){echo number_format($PlayerBaseInfo['SalaryAverage'],0) . "$";}echo "</td>";}
	echo "<td>"; if ($PlayerBaseInfo <> Null){echo number_format($PlayerBaseInfo['Salary1'],0) . "$";} echo "</td>";
	if($LeagueOutputOption['OutputSalariesRemaining'] == "True"){echo "<td>";if ($PlayerBaseInfo <> Null){echo number_format($PlayerBaseInfo['SalaryRemaining'],0) . "$";}echo "</td>";}
	if($LeagueOutputOption['OutputSalariesAverageRemaining'] == "True"){echo "<td>";if ($PlayerBaseInfo <> Null){echo number_format($PlayerBaseInfo['SalaryAverageRemaining'],0) . "$";}echo "</td>";}
	echo "<td>" .  number_format($PlayerBaseInfo['SalaryCap'],0) . "$</td>";
	echo "<td>" .  number_format($PlayerBaseInfo['SalaryCapRemaining'],0) . "$</td>";	
	echo "<td>" . $PlayerBaseInfo['SK'] . "</td>"; 
	echo "<td>" . $PlayerBaseInfo['DU'] . "</td>"; 
	echo "<td>" . $PlayerBaseInfo['EN'] . "</td>"; 
	echo "<td>" . $PlayerBaseInfo['SZ'] . "</td>"; 
	echo "<td>" . $PlayerBaseInfo['AG'] . "</td>"; 
	echo "<td>" . $PlayerBaseInfo['RB'] . "</td>"; 
	echo "<td>" . $PlayerBaseInfo['SC'] . "</td>"; 
	echo "<td>" . $PlayerBaseInfo['HS'] . "</td>"; 
	echo "<td>" . $PlayerBaseInfo['RT'] . "</td>"; 
	echo "<td>" . $PlayerBaseInfo['PH'] . "</td>"; 
	echo "<td>" . $PlayerBaseInfo['PS'] . "</td>"; 
	echo "<td>" . $PlayerBaseInfo['EX'] . "</td>"; 
	echo "<td>" . $PlayerBaseInfo['LD'] . "</td>"; 
	echo "<td>" . $PlayerBaseInfo['PO'] . "</td>"; 
	echo "<td>" . $PlayerBaseInfo['MO'] . "</td>"; 
	echo "<td>" . $PlayerBaseInfo['Overall'] . "</td>"; 
	echo "</tr><tr>";
	echo "<td>" . "<a href=\"GoalieReport.php?Goalie=" . $PlayerCompare1Info['Number'] . "\">" . $PlayerCompare1Name . "</a></td>";	
	echo "<td>" . $PlayerCompare1Info['TeamName'] . "</td>";
	echo "<td>" . $PlayerCompare1Info['Age'] . "</td>"; 
	echo "<td>"; if ($PlayerCompare1Info <> Null){echo number_format(str_replace(",",".",$PlayerCompare1Info['ConditionDecimal']),2);} echo "</td>";	
	echo "<td>" . $PlayerCompare1Info['Height'] . "</td>"; 
	echo "<td>" . $PlayerCompare1Info['Weight'] . "</td>"; 
	echo "<td>"; 
	if ($PlayerCompare1Info['URLLink'] != ""){echo "<a href=" . $PlayerCompare1Info['URLLink'] . " target=\"new\">" . $PlayersLang['Link'] . "</a>";}
	if ($PlayerCompare1Info['URLLink'] != "" AND $PlayerCompare1Info['NHLID'] != ""){echo " / ";}
	if ($PlayerCompare1Info['NHLID'] != ""){echo "<a href=\"https://www.nhl.com/player/" . $PlayerCompare1Info['NHLID'] . "\" target=\"new\">" . $PlayersLang['NHLLink'] . "</a>";}
	echo "</td>";
	echo "<td>" . $PlayerCompare1Info['Contract'] . "</td>"; 
	if($LeagueOutputOption['OutputSalariesAverageTotal'] == "True"){echo "<td>";if ($PlayerCompare1Info <> Null){echo number_format($PlayerCompare1Info['SalaryAverage'],0) . "$";}echo "</td>";}
	echo "<td>"; if ($PlayerCompare1Info <> Null){echo number_format($PlayerCompare1Info['Salary1'],0) . "$";}echo "</td>";
	if($LeagueOutputOption['OutputSalariesRemaining'] == "True"){echo "<td>";if ($PlayerCompare1Info <> Null){echo number_format($PlayerCompare1Info['SalaryRemaining'],0) . "$";}echo "</td>";}
	if($LeagueOutputOption['OutputSalariesAverageRemaining'] == "True"){echo "<td>";if ($PlayerCompare1Info <> Null){echo number_format($PlayerCompare1Info['SalaryAverageRemaining'],0) . "$";}echo "</td>";}
	echo "<td>" .  number_format($PlayerCompare1Info['SalaryCap'],0) . "$</td>";
	echo "<td>" .  number_format($PlayerCompare1Info['SalaryCapRemaining'],0) . "$</td>";
	echo "<td>" . $PlayerCompare1Info['SK'] . "</td>"; 
	echo "<td>" . $PlayerCompare1Info['DU'] . "</td>"; 
	echo "<td>" . $PlayerCompare1Info['EN'] . "</td>"; 
	echo "<td>" . $PlayerCompare1Info['SZ'] . "</td>"; 
	echo "<td>" . $PlayerCompare1Info['AG'] . "</td>"; 
	echo "<td>" . $PlayerCompare1Info['RB'] . "</td>"; 
	echo "<td>" . $PlayerCompare1Info['SC'] . "</td>"; 
	echo "<td>" . $PlayerCompare1Info['HS'] . "</td>"; 
	echo "<td>" . $PlayerCompare1Info['RT'] . "</td>"; 
	echo "<td>" . $PlayerCompare1Info['PH'] . "</td>"; 
	echo "<td>" . $PlayerCompare1Info['PS'] . "</td>"; 
	echo "<td>" . $PlayerCompare1Info['EX'] . "</td>"; 
	echo "<td>" . $PlayerCompare1Info['LD'] . "</td>"; 
	echo "<td>" . $PlayerCompare1Info['PO'] . "</td>"; 
	echo "<td>" . $PlayerCompare1Info['MO'] . "</td>"; 
	echo "<td>" . $PlayerCompare1Info['Overall'] . "</td>";  	
	echo "</tr><tr>";
	echo "<td></td><td></td>";
	
	echo "<td>"; if($PlayerCompare1Info['Age'] > $PlayerBaseInfo['Age']){echo "<span style=\"color:red\">+" . ($PlayerCompare1Info['Age'] - $PlayerBaseInfo['Age']) . "</span>";}elseif($PlayerCompare1Info['Age'] < $PlayerBaseInfo['Age']){echo "<span style=\"color:green\">-" . ($PlayerBaseInfo['Age'] - $PlayerCompare1Info['Age']) . "</span>";}else{echo "E";}echo "</td>"; 	
	echo "<td>"; if($PlayerCompare1Info['ConditionDecimal'] > $PlayerBaseInfo['ConditionDecimal']){echo "<span style=\"color:green\">+" . ($PlayerCompare1Info['ConditionDecimal'] - $PlayerBaseInfo['ConditionDecimal']) . "</span>";}elseif($PlayerCompare1Info['ConditionDecimal'] < $PlayerBaseInfo['ConditionDecimal']){echo "<span style=\"color:red\">-" . ($PlayerBaseInfo['ConditionDecimal'] - $PlayerCompare1Info['ConditionDecimal']) . "</span>";}else{echo "E";}echo "</td>"; 
	echo "<td>"; if($PlayerCompare1Info['Height'] > $PlayerBaseInfo['Height']){echo "<span style=\"color:green\">+" . number_format(str_replace(",",".",($PlayerCompare1Info['Height'] - $PlayerBaseInfo['Height'])),2) . "</span>";}elseif($PlayerCompare1Info['Height'] < $PlayerBaseInfo['Height']){echo "<span style=\"color:red\">-" . number_format(str_replace(",",".",($PlayerBaseInfo['Height'] - $PlayerCompare1Info['Height'])),2) . "</span>";}else{echo "E";}echo "</td>"; 	
	echo "<td>"; if($PlayerCompare1Info['Weight'] > $PlayerBaseInfo['Weight']){echo "<span style=\"color:green\">+" . ($PlayerCompare1Info['Weight'] - $PlayerBaseInfo['Weight']) . "</span>";}elseif($PlayerCompare1Info['Weight'] < $PlayerBaseInfo['Weight']){echo "<span style=\"color:red\">-" . ($PlayerBaseInfo['Weight'] - $PlayerCompare1Info['Weight']) . "</span>";}else{echo "E";}echo "</td>"; 	
	echo "<td></td>";
	echo "<td>"; if($PlayerCompare1Info['Contract'] > $PlayerBaseInfo['Contract']){echo "<span style=\"color:green\">+" . ($PlayerCompare1Info['Contract'] - $PlayerBaseInfo['Contract']) . "</span>";}elseif($PlayerCompare1Info['Contract'] < $PlayerBaseInfo['Contract']){echo "<span style=\"color:red\">-" . ($PlayerBaseInfo['Contract'] - $PlayerCompare1Info['Contract']) . "</span>";}else{echo "E";}echo "</td>"; 	
	if($LeagueOutputOption['OutputSalariesAverageTotal'] == "True"){echo "<td>";if($PlayerCompare1Info['SalaryAverage'] > $PlayerBaseInfo['SalaryAverage']){echo "<span style=\"color:red\">+" . number_format(($PlayerCompare1Info['SalaryAverage'] - $PlayerBaseInfo['SalaryAverage']),0) . "</span>";}elseif($PlayerCompare1Info['SalaryAverage'] < $PlayerBaseInfo['SalaryAverage']){echo "<span style=\"color:green\">-" . number_format(($PlayerBaseInfo['SalaryAverage'] - $PlayerCompare1Info['SalaryAverage']),0) . "</span>";}else{echo "E";};	echo "</td>";}
	echo "<td>"; if($PlayerCompare1Info['Salary1'] > $PlayerBaseInfo['Salary1']){echo "<span style=\"color:red\">+" . number_format(($PlayerCompare1Info['Salary1'] - $PlayerBaseInfo['Salary1']),0) . "</span>";}elseif($PlayerCompare1Info['Salary1'] < $PlayerBaseInfo['Salary1']){echo "<span style=\"color:green\">-" . number_format(($PlayerBaseInfo['Salary1'] - $PlayerCompare1Info['Salary1']),0) . "</span>";}else{echo "E";};echo "</td>"; 
	if($LeagueOutputOption['OutputSalariesRemaining'] == "True"){echo "<td>";	if($PlayerCompare1Info['SalaryRemaining'] > $PlayerBaseInfo['SalaryRemaining']){echo "<span style=\"color:red\">+" . number_format(($PlayerCompare1Info['SalaryRemaining'] - $PlayerBaseInfo['SalaryRemaining']),0) . "</span>";}elseif($PlayerCompare1Info['SalaryRemaining'] < $PlayerBaseInfo['SalaryRemaining']){echo "<span style=\"color:green\">-" . number_format(($PlayerBaseInfo['SalaryRemaining'] - $PlayerCompare1Info['SalaryRemaining']),0) . "</span>";}else{echo "E";};	echo "</td>";}
	if($LeagueOutputOption['OutputSalariesAverageRemaining'] == "True"){echo "<td>";if($PlayerCompare1Info['SalaryAverageRemaining'] > $PlayerBaseInfo['SalaryAverageRemaining']){echo "<span style=\"color:red\">+" . number_format(($PlayerCompare1Info['SalaryAverageRemaining'] - $PlayerBaseInfo['SalaryAverageRemaining']),0) . "</span>";}elseif($PlayerCompare1Info['SalaryAverageRemaining'] < $PlayerBaseInfo['SalaryAverageRemaining']){echo "<span style=\"color:green\">-" . number_format(($PlayerBaseInfo['SalaryAverageRemaining'] - $PlayerCompare1Info['SalaryAverageRemaining']),0) . "</span>";}else{echo "E";};	echo "</td>";}
	echo "<td>"; if($PlayerCompare1Info['SalaryCap'] > $PlayerBaseInfo['SalaryCap']){echo "<span style=\"color:red\">+" . number_format(($PlayerCompare1Info['SalaryCap'] - $PlayerBaseInfo['SalaryCap']),0) . "</span>";}elseif($PlayerCompare1Info['SalaryCap'] < $PlayerBaseInfo['SalaryCap']){echo "<span style=\"color:green\">-" . number_format(($PlayerBaseInfo['SalaryCap'] - $PlayerCompare1Info['SalaryCap']),0) . "</span>";}else{echo "E";};echo "</td>"; 	
	echo "<td>"; if($PlayerCompare1Info['SalaryCapRemaining'] > $PlayerBaseInfo['SalaryCapRemaining']){echo "<span style=\"color:red\">+" . number_format(($PlayerCompare1Info['SalaryCapRemaining'] - $PlayerBaseInfo['SalaryCapRemaining']),0) . "</span>";}elseif($PlayerCompare1Info['SalaryCapRemaining'] < $PlayerBaseInfo['SalaryCapRemaining']){echo "<span style=\"color:green\">-" . number_format(($PlayerBaseInfo['SalaryCapRemaining'] - $PlayerCompare1Info['SalaryCapRemaining']),0) . "</span>";}else{echo "E";};echo "</td>";
	
	echo "<td>"; if($PlayerCompare1Info['SK'] > $PlayerBaseInfo['SK']){echo "<span style=\"color:green\">+" . ($PlayerCompare1Info['SK'] - $PlayerBaseInfo['SK']) . "</span>";}elseif($PlayerCompare1Info['SK'] < $PlayerBaseInfo['SK']){echo "<span style=\"color:red\">-" . ($PlayerBaseInfo['SK'] - $PlayerCompare1Info['SK']) . "</span>";}else{echo "E";}; echo "</td>";   		
	echo "<td>"; if($PlayerCompare1Info['DU'] > $PlayerBaseInfo['DU']){echo "<span style=\"color:green\">+" . ($PlayerCompare1Info['DU'] - $PlayerBaseInfo['DU']) . "</span>";}elseif($PlayerCompare1Info['DU'] < $PlayerBaseInfo['DU']){echo "<span style=\"color:red\">-" . ($PlayerBaseInfo['DU'] - $PlayerCompare1Info['DU']) . "</span>";}else{echo "E";}; echo "</td>";   	
	echo "<td>"; if($PlayerCompare1Info['EN'] > $PlayerBaseInfo['EN']){echo "<span style=\"color:green\">+" . ($PlayerCompare1Info['EN'] - $PlayerBaseInfo['EN']) . "</span>";}elseif($PlayerCompare1Info['EN'] < $PlayerBaseInfo['EN']){echo "<span style=\"color:red\">-" . ($PlayerBaseInfo['EN'] - $PlayerCompare1Info['EN']) . "</span>";}else{echo "E";}; echo "</td>";   	
	echo "<td>"; if($PlayerCompare1Info['SZ'] > $PlayerBaseInfo['SZ']){echo "<span style=\"color:green\">+" . ($PlayerCompare1Info['SZ'] - $PlayerBaseInfo['SZ']) . "</span>";}elseif($PlayerCompare1Info['SZ'] < $PlayerBaseInfo['SZ']){echo "<span style=\"color:red\">-" . ($PlayerBaseInfo['SZ'] - $PlayerCompare1Info['SZ']) . "</span>";}else{echo "E";}; echo "</td>";   	
	echo "<td>"; if($PlayerCompare1Info['AG'] > $PlayerBaseInfo['AG']){echo "<span style=\"color:green\">+" . ($PlayerCompare1Info['AG'] - $PlayerBaseInfo['AG']) . "</span>";}elseif($PlayerCompare1Info['AG'] < $PlayerBaseInfo['AG']){echo "<span style=\"color:red\">-" . ($PlayerBaseInfo['AG'] - $PlayerCompare1Info['AG']) . "</span>";}else{echo "E";}; echo "</td>";   	
	echo "<td>"; if($PlayerCompare1Info['RB'] > $PlayerBaseInfo['RB']){echo "<span style=\"color:green\">+" . ($PlayerCompare1Info['RB'] - $PlayerBaseInfo['RB']) . "</span>";}elseif($PlayerCompare1Info['RB'] < $PlayerBaseInfo['RB']){echo "<span style=\"color:red\">-" . ($PlayerBaseInfo['RB'] - $PlayerCompare1Info['RB']) . "</span>";}else{echo "E";}; echo "</td>";   	
	echo "<td>"; if($PlayerCompare1Info['SC'] > $PlayerBaseInfo['SC']){echo "<span style=\"color:green\">+" . ($PlayerCompare1Info['SC'] - $PlayerBaseInfo['SC']) . "</span>";}elseif($PlayerCompare1Info['SC'] < $PlayerBaseInfo['SC']){echo "<span style=\"color:red\">-" . ($PlayerBaseInfo['SC'] - $PlayerCompare1Info['SC']) . "</span>";}else{echo "E";}; echo "</td>";   
	echo "<td>"; if($PlayerCompare1Info['HS'] > $PlayerBaseInfo['HS']){echo "<span style=\"color:green\">+" . ($PlayerCompare1Info['HS'] - $PlayerBaseInfo['HS']) . "</span>";}elseif($PlayerCompare1Info['HS'] < $PlayerBaseInfo['HS']){echo "<span style=\"color:red\">-" . ($PlayerBaseInfo['HS'] - $PlayerCompare1Info['HS']) . "</span>";}else{echo "E";}; echo "</td>";   	
	echo "<td>"; if($PlayerCompare1Info['RT'] > $PlayerBaseInfo['RT']){echo "<span style=\"color:green\">+" . ($PlayerCompare1Info['RT'] - $PlayerBaseInfo['RT']) . "</span>";}elseif($PlayerCompare1Info['RT'] < $PlayerBaseInfo['RT']){echo "<span style=\"color:red\">-" . ($PlayerBaseInfo['RT'] - $PlayerCompare1Info['RT']) . "</span>";}else{echo "E";}; echo "</td>";   	
	echo "<td>"; if($PlayerCompare1Info['PH'] > $PlayerBaseInfo['PH']){echo "<span style=\"color:green\">+" . ($PlayerCompare1Info['PH'] - $PlayerBaseInfo['PH']) . "</span>";}elseif($PlayerCompare1Info['PH'] < $PlayerBaseInfo['PH']){echo "<span style=\"color:red\">-" . ($PlayerBaseInfo['PH'] - $PlayerCompare1Info['PH']) . "</span>";}else{echo "E";}; echo "</td>";   
	echo "<td>"; if($PlayerCompare1Info['PS'] > $PlayerBaseInfo['PS']){echo "<span style=\"color:green\">+" . ($PlayerCompare1Info['PS'] - $PlayerBaseInfo['PS']) . "</span>";}elseif($PlayerCompare1Info['PS'] < $PlayerBaseInfo['PS']){echo "<span style=\"color:red\">-" . ($PlayerBaseInfo['PS'] - $PlayerCompare1Info['PS']) . "</span>";}else{echo "E";}; echo "</td>";   	
	echo "<td>"; if($PlayerCompare1Info['EX'] > $PlayerBaseInfo['EX']){echo "<span style=\"color:green\">+" . ($PlayerCompare1Info['EX'] - $PlayerBaseInfo['EX']) . "</span>";}elseif($PlayerCompare1Info['EX'] < $PlayerBaseInfo['EX']){echo "<span style=\"color:red\">-" . ($PlayerBaseInfo['EX'] - $PlayerCompare1Info['EX']) . "</span>";}else{echo "E";}; echo "</td>";   	
	echo "<td>"; if($PlayerCompare1Info['LD'] > $PlayerBaseInfo['LD']){echo "<span style=\"color:green\">+" . ($PlayerCompare1Info['LD'] - $PlayerBaseInfo['LD']) . "</span>";}elseif($PlayerCompare1Info['LD'] < $PlayerBaseInfo['LD']){echo "<span style=\"color:red\">-" . ($PlayerBaseInfo['LD'] - $PlayerCompare1Info['LD']) . "</span>";}else{echo "E";}; echo "</td>";   	
	echo "<td>"; if($PlayerCompare1Info['PO'] > $PlayerBaseInfo['PO']){echo "<span style=\"color:green\">+" . ($PlayerCompare1Info['PO'] - $PlayerBaseInfo['PO']) . "</span>";}elseif($PlayerCompare1Info['PO'] < $PlayerBaseInfo['PO']){echo "<span style=\"color:red\">-" . ($PlayerBaseInfo['PO'] - $PlayerCompare1Info['PO']) . "</span>";}else{echo "E";}; echo "</td>";   	
	echo "<td>"; if($PlayerCompare1Info['MO'] > $PlayerBaseInfo['MO']){echo "<span style=\"color:green\">+" . ($PlayerCompare1Info['MO'] - $PlayerBaseInfo['MO']) . "</span>";}elseif($PlayerCompare1Info['MO'] < $PlayerBaseInfo['MO']){echo "<span style=\"color:red\">-" . ($PlayerBaseInfo['MO'] - $PlayerCompare1Info['MO']) . "</span>";}else{echo "E";}; echo "</td>";   	
	echo "<td>"; if($PlayerCompare1Info['Overall'] > $PlayerBaseInfo['Overall']){echo "<span style=\"color:green\">+" . ($PlayerCompare1Info['Overall'] - $PlayerBaseInfo['Overall']) . "</span>";}elseif($PlayerCompare1Info['Overall'] < $PlayerBaseInfo['Overall']){echo "<span style=\"color:red\">-" . ($PlayerBaseInfo['Overall'] - $PlayerCompare1Info['Overall']) . "</span>";}else{echo "E";}; echo "</td>";   
	echo "</tr>";
}?>	
</table>
<div class="STHSBlankDiv"></div>
<br />
</div>


<div class="STHSPHPPlayerStat_Main2">
<br />
<table class="STHSPHPPlayerStat_Table">
<tr>
	<th><?php echo $PlayersLang['PlayerName'];?></th>
	<th><?php echo $PlayersLang['TeamName'];?></th>
	<th><?php echo $PlayersLang['Age'];?></th>
	<th><?php echo $PlayersLang['Condition'];?></th>
	<th><?php echo $PlayersLang['Height'];?></th>
	<th><?php echo $PlayersLang['Weight'];?></th>
	<th><?php echo $PlayersLang['Link'];?></th>
	<th><?php echo $PlayersLang['Contract'];?></th>
	<?php if(isset($LeagueOutputOption)){if($LeagueOutputOption['OutputSalariesAverageTotal'] == "True"){echo "<th>" . $PlayersLang['SalaryAverage'] . "</th>";}}?>
	<th><?php echo $PlayersLang['SalaryYear'];?> 1</th>
	<?php if(isset($LeagueOutputOption)){if($LeagueOutputOption['OutputSalariesRemaining'] == "True"){ echo "<th>" . $PlayersLang['SalaryRemaining'] . "</th>";}}?>
	<?php if(isset($LeagueOutputOption)){if($LeagueOutputOption['OutputSalariesAverageRemaining'] == "True"){ echo "<th>" . $PlayersLang['SalaryAveRemaining']. "</th>";}}?>
	<th><?php echo $PlayersLang['SalaryCap'];?></th>
	<th><?php echo $PlayersLang['SalaryCapRemaining'];?></th>	
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
</tr>
<?php 
If ($PlayerBaseInfo <> Null AND $PlayerCompare2Info <> Null){
	echo "<tr>";
	echo "<td>" . "<a href=\"PlayerReport.php?Player=" . $PlayerBaseInfo['Number'] . "\">" . $PlayerBaseName . "</a></td>";
	echo "<td>" . $PlayerBaseInfo['TeamName'] . "</td>";
	echo "<td>" . $PlayerBaseInfo['Age'] . "</td>"; 
	echo "<td>"; if ($PlayerBaseInfo <> Null){echo number_format(str_replace(",",".",$PlayerBaseInfo['ConditionDecimal']),2);}echo "</td>";
	echo "<td>" . $PlayerBaseInfo['Height'] . "</td>"; 
	echo "<td>" . $PlayerBaseInfo['Weight'] . "</td>"; 
	echo "<td>";
	if ($PlayerBaseInfo['URLLink'] != ""){echo "<a href=" . $PlayerBaseInfo['URLLink'] . " target=\"new\">" . $PlayersLang['Link'] . "</a>";}
	if ($PlayerBaseInfo['URLLink'] != "" AND $PlayerBaseInfo['NHLID'] != ""){echo " / ";}
	if ($PlayerBaseInfo['NHLID'] != ""){echo "<a href=\"https://www.nhl.com/player/" . $PlayerBaseInfo['NHLID'] . "\" target=\"new\">" . $PlayersLang['NHLLink'] . "</a>";}
	echo "</td>";
	echo "<td>" . $PlayerBaseInfo['Contract'] . "</td>"; 
	if($LeagueOutputOption['OutputSalariesAverageTotal'] == "True"){echo "<td>";if ($PlayerBaseInfo <> Null){echo number_format($PlayerBaseInfo['SalaryAverage'],0) . "$";}echo "</td>";}
	echo "<td>"; if ($PlayerBaseInfo <> Null){echo number_format($PlayerBaseInfo['Salary1'],0) . "$";} echo "</td>";
	if($LeagueOutputOption['OutputSalariesRemaining'] == "True"){echo "<td>";if ($PlayerBaseInfo <> Null){echo number_format($PlayerBaseInfo['SalaryRemaining'],0) . "$";}echo "</td>";}
	if($LeagueOutputOption['OutputSalariesAverageRemaining'] == "True"){echo "<td>";if ($PlayerBaseInfo <> Null){echo number_format($PlayerBaseInfo['SalaryAverageRemaining'],0) . "$";}echo "</td>";}
	echo "<td>" .  number_format($PlayerBaseInfo['SalaryCap'],0) . "$</td>";
	echo "<td>" .  number_format($PlayerBaseInfo['SalaryCapRemaining'],0) . "$</td>";	
	echo "<td>" . $PlayerBaseInfo['SK'] . "</td>"; 
	echo "<td>" . $PlayerBaseInfo['DU'] . "</td>"; 
	echo "<td>" . $PlayerBaseInfo['EN'] . "</td>"; 
	echo "<td>" . $PlayerBaseInfo['SZ'] . "</td>"; 
	echo "<td>" . $PlayerBaseInfo['AG'] . "</td>"; 
	echo "<td>" . $PlayerBaseInfo['RB'] . "</td>"; 
	echo "<td>" . $PlayerBaseInfo['SC'] . "</td>"; 
	echo "<td>" . $PlayerBaseInfo['HS'] . "</td>"; 
	echo "<td>" . $PlayerBaseInfo['RT'] . "</td>"; 
	echo "<td>" . $PlayerBaseInfo['PH'] . "</td>"; 
	echo "<td>" . $PlayerBaseInfo['PS'] . "</td>"; 
	echo "<td>" . $PlayerBaseInfo['EX'] . "</td>"; 
	echo "<td>" . $PlayerBaseInfo['LD'] . "</td>"; 
	echo "<td>" . $PlayerBaseInfo['PO'] . "</td>"; 
	echo "<td>" . $PlayerBaseInfo['MO'] . "</td>"; 
	echo "<td>" . $PlayerBaseInfo['Overall'] . "</td>"; 
	echo "</tr><tr>";
	echo "<td>" . "<a href=\"GoalieReport.php?Goalie=" . $PlayerCompare2Info['Number'] . "\">" . $PlayerCompare1Name . "</a></td>";	
	echo "<td>" . $PlayerCompare2Info['TeamName'] . "</td>";
	echo "<td>" . $PlayerCompare2Info['Age'] . "</td>"; 
	echo "<td>"; if ($PlayerCompare2Info <> Null){echo number_format(str_replace(",",".",$PlayerCompare2Info['ConditionDecimal']),2);} echo "</td>";	
	echo "<td>" . $PlayerCompare2Info['Height'] . "</td>"; 
	echo "<td>" . $PlayerCompare2Info['Weight'] . "</td>"; 
	echo "<td>"; 
	if ($PlayerCompare2Info['URLLink'] != ""){echo "<a href=" . $PlayerCompare2Info['URLLink'] . " target=\"new\">" . $PlayersLang['Link'] . "</a>";}
	if ($PlayerCompare2Info['URLLink'] != "" AND $PlayerCompare2Info['NHLID'] != ""){echo " / ";}
	if ($PlayerCompare2Info['NHLID'] != ""){echo "<a href=\"https://www.nhl.com/player/" . $PlayerCompare2Info['NHLID'] . "\" target=\"new\">" . $PlayersLang['NHLLink'] . "</a>";}
	echo "</td>";
	echo "<td>" . $PlayerCompare2Info['Contract'] . "</td>"; 
	if($LeagueOutputOption['OutputSalariesAverageTotal'] == "True"){echo "<td>";if ($PlayerCompare2Info <> Null){echo number_format($PlayerCompare2Info['SalaryAverage'],0) . "$";}echo "</td>";}
	echo "<td>"; if ($PlayerCompare2Info <> Null){echo number_format($PlayerCompare2Info['Salary1'],0) . "$";}echo "</td>";
	if($LeagueOutputOption['OutputSalariesRemaining'] == "True"){echo "<td>";if ($PlayerCompare2Info <> Null){echo number_format($PlayerCompare2Info['SalaryRemaining'],0) . "$";}echo "</td>";}
	if($LeagueOutputOption['OutputSalariesAverageRemaining'] == "True"){echo "<td>";if ($PlayerCompare2Info <> Null){echo number_format($PlayerCompare2Info['SalaryAverageRemaining'],0) . "$";}echo "</td>";}
	echo "<td>" .  number_format($PlayerCompare2Info['SalaryCap'],0) . "$</td>";
	echo "<td>" .  number_format($PlayerCompare2Info['SalaryCapRemaining'],0) . "$</td>";
	echo "<td>" . $PlayerCompare2Info['SK'] . "</td>"; 
	echo "<td>" . $PlayerCompare2Info['DU'] . "</td>"; 
	echo "<td>" . $PlayerCompare2Info['EN'] . "</td>"; 
	echo "<td>" . $PlayerCompare2Info['SZ'] . "</td>"; 
	echo "<td>" . $PlayerCompare2Info['AG'] . "</td>"; 
	echo "<td>" . $PlayerCompare2Info['RB'] . "</td>"; 
	echo "<td>" . $PlayerCompare2Info['SC'] . "</td>"; 
	echo "<td>" . $PlayerCompare2Info['HS'] . "</td>"; 
	echo "<td>" . $PlayerCompare2Info['RT'] . "</td>"; 
	echo "<td>" . $PlayerCompare2Info['PH'] . "</td>"; 
	echo "<td>" . $PlayerCompare2Info['PS'] . "</td>"; 
	echo "<td>" . $PlayerCompare2Info['EX'] . "</td>"; 
	echo "<td>" . $PlayerCompare2Info['LD'] . "</td>"; 
	echo "<td>" . $PlayerCompare2Info['PO'] . "</td>"; 
	echo "<td>" . $PlayerCompare2Info['MO'] . "</td>"; 
	echo "<td>" . $PlayerCompare2Info['Overall'] . "</td>";  	
	echo "</tr><tr>";
	echo "<td></td><td></td>";
	
	echo "<td>"; if($PlayerCompare2Info['Age'] > $PlayerBaseInfo['Age']){echo "<span style=\"color:red\">+" . ($PlayerCompare2Info['Age'] - $PlayerBaseInfo['Age']) . "</span>";}elseif($PlayerCompare2Info['Age'] < $PlayerBaseInfo['Age']){echo "<span style=\"color:green\">-" . ($PlayerBaseInfo['Age'] - $PlayerCompare2Info['Age']) . "</span>";}else{echo "E";}echo "</td>"; 	
	echo "<td>"; if($PlayerCompare2Info['ConditionDecimal'] > $PlayerBaseInfo['ConditionDecimal']){echo "<span style=\"color:green\">+" . ($PlayerCompare2Info['ConditionDecimal'] - $PlayerBaseInfo['ConditionDecimal']) . "</span>";}elseif($PlayerCompare2Info['ConditionDecimal'] < $PlayerBaseInfo['ConditionDecimal']){echo "<span style=\"color:red\">-" . ($PlayerBaseInfo['ConditionDecimal'] - $PlayerCompare2Info['ConditionDecimal']) . "</span>";}else{echo "E";}echo "</td>"; 
	echo "<td>"; if($PlayerCompare2Info['Height'] > $PlayerBaseInfo['Height']){echo "<span style=\"color:green\">+" . number_format(str_replace(",",".",($PlayerCompare2Info['Height'] - $PlayerBaseInfo['Height'])),2) . "</span>";}elseif($PlayerCompare2Info['Height'] < $PlayerBaseInfo['Height']){echo "<span style=\"color:red\">-" . number_format(str_replace(",",".",($PlayerBaseInfo['Height'] - $PlayerCompare2Info['Height'])),2) . "</span>";}else{echo "E";}echo "</td>"; 	
	echo "<td>"; if($PlayerCompare2Info['Weight'] > $PlayerBaseInfo['Weight']){echo "<span style=\"color:green\">+" . ($PlayerCompare2Info['Weight'] - $PlayerBaseInfo['Weight']) . "</span>";}elseif($PlayerCompare2Info['Weight'] < $PlayerBaseInfo['Weight']){echo "<span style=\"color:red\">-" . ($PlayerBaseInfo['Weight'] - $PlayerCompare2Info['Weight']) . "</span>";}else{echo "E";}echo "</td>"; 	
	echo "<td></td>";
	echo "<td>"; if($PlayerCompare2Info['Contract'] > $PlayerBaseInfo['Contract']){echo "<span style=\"color:green\">+" . ($PlayerCompare2Info['Contract'] - $PlayerBaseInfo['Contract']) . "</span>";}elseif($PlayerCompare2Info['Contract'] < $PlayerBaseInfo['Contract']){echo "<span style=\"color:red\">-" . ($PlayerBaseInfo['Contract'] - $PlayerCompare2Info['Contract']) . "</span>";}else{echo "E";}echo "</td>"; 	
	if($LeagueOutputOption['OutputSalariesAverageTotal'] == "True"){echo "<td>";if($PlayerCompare2Info['SalaryAverage'] > $PlayerBaseInfo['SalaryAverage']){echo "<span style=\"color:red\">+" . number_format(($PlayerCompare2Info['SalaryAverage'] - $PlayerBaseInfo['SalaryAverage']),0) . "</span>";}elseif($PlayerCompare2Info['SalaryAverage'] < $PlayerBaseInfo['SalaryAverage']){echo "<span style=\"color:green\">-" . number_format(($PlayerBaseInfo['SalaryAverage'] - $PlayerCompare2Info['SalaryAverage']),0) . "</span>";}else{echo "E";};	echo "</td>";}
	echo "<td>"; if($PlayerCompare2Info['Salary1'] > $PlayerBaseInfo['Salary1']){echo "<span style=\"color:red\">+" . number_format(($PlayerCompare2Info['Salary1'] - $PlayerBaseInfo['Salary1']),0) . "</span>";}elseif($PlayerCompare2Info['Salary1'] < $PlayerBaseInfo['Salary1']){echo "<span style=\"color:green\">-" . number_format(($PlayerBaseInfo['Salary1'] - $PlayerCompare2Info['Salary1']),0) . "</span>";}else{echo "E";};echo "</td>"; 
	if($LeagueOutputOption['OutputSalariesRemaining'] == "True"){echo "<td>";	if($PlayerCompare2Info['SalaryRemaining'] > $PlayerBaseInfo['SalaryRemaining']){echo "<span style=\"color:red\">+" . number_format(($PlayerCompare2Info['SalaryRemaining'] - $PlayerBaseInfo['SalaryRemaining']),0) . "</span>";}elseif($PlayerCompare2Info['SalaryRemaining'] < $PlayerBaseInfo['SalaryRemaining']){echo "<span style=\"color:green\">-" . number_format(($PlayerBaseInfo['SalaryRemaining'] - $PlayerCompare2Info['SalaryRemaining']),0) . "</span>";}else{echo "E";};	echo "</td>";}
	if($LeagueOutputOption['OutputSalariesAverageRemaining'] == "True"){echo "<td>";if($PlayerCompare2Info['SalaryAverageRemaining'] > $PlayerBaseInfo['SalaryAverageRemaining']){echo "<span style=\"color:red\">+" . number_format(($PlayerCompare2Info['SalaryAverageRemaining'] - $PlayerBaseInfo['SalaryAverageRemaining']),0) . "</span>";}elseif($PlayerCompare2Info['SalaryAverageRemaining'] < $PlayerBaseInfo['SalaryAverageRemaining']){echo "<span style=\"color:green\">-" . number_format(($PlayerBaseInfo['SalaryAverageRemaining'] - $PlayerCompare2Info['SalaryAverageRemaining']),0) . "</span>";}else{echo "E";};	echo "</td>";}
	echo "<td>"; if($PlayerCompare2Info['SalaryCap'] > $PlayerBaseInfo['SalaryCap']){echo "<span style=\"color:red\">+" . number_format(($PlayerCompare2Info['SalaryCap'] - $PlayerBaseInfo['SalaryCap']),0) . "</span>";}elseif($PlayerCompare2Info['SalaryCap'] < $PlayerBaseInfo['SalaryCap']){echo "<span style=\"color:green\">-" . number_format(($PlayerBaseInfo['SalaryCap'] - $PlayerCompare2Info['SalaryCap']),0) . "</span>";}else{echo "E";};echo "</td>"; 	
	echo "<td>"; if($PlayerCompare2Info['SalaryCapRemaining'] > $PlayerBaseInfo['SalaryCapRemaining']){echo "<span style=\"color:red\">+" . number_format(($PlayerCompare2Info['SalaryCapRemaining'] - $PlayerBaseInfo['SalaryCapRemaining']),0) . "</span>";}elseif($PlayerCompare2Info['SalaryCapRemaining'] < $PlayerBaseInfo['SalaryCapRemaining']){echo "<span style=\"color:green\">-" . number_format(($PlayerBaseInfo['SalaryCapRemaining'] - $PlayerCompare2Info['SalaryCapRemaining']),0) . "</span>";}else{echo "E";};echo "</td>";
	
	echo "<td>"; if($PlayerCompare2Info['SK'] > $PlayerBaseInfo['SK']){echo "<span style=\"color:green\">+" . ($PlayerCompare2Info['SK'] - $PlayerBaseInfo['SK']) . "</span>";}elseif($PlayerCompare2Info['SK'] < $PlayerBaseInfo['SK']){echo "<span style=\"color:red\">-" . ($PlayerBaseInfo['SK'] - $PlayerCompare2Info['SK']) . "</span>";}else{echo "E";}; echo "</td>";   		
	echo "<td>"; if($PlayerCompare2Info['DU'] > $PlayerBaseInfo['DU']){echo "<span style=\"color:green\">+" . ($PlayerCompare2Info['DU'] - $PlayerBaseInfo['DU']) . "</span>";}elseif($PlayerCompare2Info['DU'] < $PlayerBaseInfo['DU']){echo "<span style=\"color:red\">-" . ($PlayerBaseInfo['DU'] - $PlayerCompare2Info['DU']) . "</span>";}else{echo "E";}; echo "</td>";   	
	echo "<td>"; if($PlayerCompare2Info['EN'] > $PlayerBaseInfo['EN']){echo "<span style=\"color:green\">+" . ($PlayerCompare2Info['EN'] - $PlayerBaseInfo['EN']) . "</span>";}elseif($PlayerCompare2Info['EN'] < $PlayerBaseInfo['EN']){echo "<span style=\"color:red\">-" . ($PlayerBaseInfo['EN'] - $PlayerCompare2Info['EN']) . "</span>";}else{echo "E";}; echo "</td>";   	
	echo "<td>"; if($PlayerCompare2Info['SZ'] > $PlayerBaseInfo['SZ']){echo "<span style=\"color:green\">+" . ($PlayerCompare2Info['SZ'] - $PlayerBaseInfo['SZ']) . "</span>";}elseif($PlayerCompare2Info['SZ'] < $PlayerBaseInfo['SZ']){echo "<span style=\"color:red\">-" . ($PlayerBaseInfo['SZ'] - $PlayerCompare2Info['SZ']) . "</span>";}else{echo "E";}; echo "</td>";   	
	echo "<td>"; if($PlayerCompare2Info['AG'] > $PlayerBaseInfo['AG']){echo "<span style=\"color:green\">+" . ($PlayerCompare2Info['AG'] - $PlayerBaseInfo['AG']) . "</span>";}elseif($PlayerCompare2Info['AG'] < $PlayerBaseInfo['AG']){echo "<span style=\"color:red\">-" . ($PlayerBaseInfo['AG'] - $PlayerCompare2Info['AG']) . "</span>";}else{echo "E";}; echo "</td>";   	
	echo "<td>"; if($PlayerCompare2Info['RB'] > $PlayerBaseInfo['RB']){echo "<span style=\"color:green\">+" . ($PlayerCompare2Info['RB'] - $PlayerBaseInfo['RB']) . "</span>";}elseif($PlayerCompare2Info['RB'] < $PlayerBaseInfo['RB']){echo "<span style=\"color:red\">-" . ($PlayerBaseInfo['RB'] - $PlayerCompare2Info['RB']) . "</span>";}else{echo "E";}; echo "</td>";   	
	echo "<td>"; if($PlayerCompare2Info['SC'] > $PlayerBaseInfo['SC']){echo "<span style=\"color:green\">+" . ($PlayerCompare2Info['SC'] - $PlayerBaseInfo['SC']) . "</span>";}elseif($PlayerCompare2Info['SC'] < $PlayerBaseInfo['SC']){echo "<span style=\"color:red\">-" . ($PlayerBaseInfo['SC'] - $PlayerCompare2Info['SC']) . "</span>";}else{echo "E";}; echo "</td>";   
	echo "<td>"; if($PlayerCompare2Info['HS'] > $PlayerBaseInfo['HS']){echo "<span style=\"color:green\">+" . ($PlayerCompare2Info['HS'] - $PlayerBaseInfo['HS']) . "</span>";}elseif($PlayerCompare2Info['HS'] < $PlayerBaseInfo['HS']){echo "<span style=\"color:red\">-" . ($PlayerBaseInfo['HS'] - $PlayerCompare2Info['HS']) . "</span>";}else{echo "E";}; echo "</td>";   	
	echo "<td>"; if($PlayerCompare2Info['RT'] > $PlayerBaseInfo['RT']){echo "<span style=\"color:green\">+" . ($PlayerCompare2Info['RT'] - $PlayerBaseInfo['RT']) . "</span>";}elseif($PlayerCompare2Info['RT'] < $PlayerBaseInfo['RT']){echo "<span style=\"color:red\">-" . ($PlayerBaseInfo['RT'] - $PlayerCompare2Info['RT']) . "</span>";}else{echo "E";}; echo "</td>";   	
	echo "<td>"; if($PlayerCompare2Info['PH'] > $PlayerBaseInfo['PH']){echo "<span style=\"color:green\">+" . ($PlayerCompare2Info['PH'] - $PlayerBaseInfo['PH']) . "</span>";}elseif($PlayerCompare2Info['PH'] < $PlayerBaseInfo['PH']){echo "<span style=\"color:red\">-" . ($PlayerBaseInfo['PH'] - $PlayerCompare2Info['PH']) . "</span>";}else{echo "E";}; echo "</td>";   
	echo "<td>"; if($PlayerCompare2Info['PS'] > $PlayerBaseInfo['PS']){echo "<span style=\"color:green\">+" . ($PlayerCompare2Info['PS'] - $PlayerBaseInfo['PS']) . "</span>";}elseif($PlayerCompare2Info['PS'] < $PlayerBaseInfo['PS']){echo "<span style=\"color:red\">-" . ($PlayerBaseInfo['PS'] - $PlayerCompare2Info['PS']) . "</span>";}else{echo "E";}; echo "</td>";   	
	echo "<td>"; if($PlayerCompare2Info['EX'] > $PlayerBaseInfo['EX']){echo "<span style=\"color:green\">+" . ($PlayerCompare2Info['EX'] - $PlayerBaseInfo['EX']) . "</span>";}elseif($PlayerCompare2Info['EX'] < $PlayerBaseInfo['EX']){echo "<span style=\"color:red\">-" . ($PlayerBaseInfo['EX'] - $PlayerCompare2Info['EX']) . "</span>";}else{echo "E";}; echo "</td>";   	
	echo "<td>"; if($PlayerCompare2Info['LD'] > $PlayerBaseInfo['LD']){echo "<span style=\"color:green\">+" . ($PlayerCompare2Info['LD'] - $PlayerBaseInfo['LD']) . "</span>";}elseif($PlayerCompare2Info['LD'] < $PlayerBaseInfo['LD']){echo "<span style=\"color:red\">-" . ($PlayerBaseInfo['LD'] - $PlayerCompare2Info['LD']) . "</span>";}else{echo "E";}; echo "</td>";   	
	echo "<td>"; if($PlayerCompare2Info['PO'] > $PlayerBaseInfo['PO']){echo "<span style=\"color:green\">+" . ($PlayerCompare2Info['PO'] - $PlayerBaseInfo['PO']) . "</span>";}elseif($PlayerCompare2Info['PO'] < $PlayerBaseInfo['PO']){echo "<span style=\"color:red\">-" . ($PlayerBaseInfo['PO'] - $PlayerCompare2Info['PO']) . "</span>";}else{echo "E";}; echo "</td>";   	
	echo "<td>"; if($PlayerCompare2Info['MO'] > $PlayerBaseInfo['MO']){echo "<span style=\"color:green\">+" . ($PlayerCompare2Info['MO'] - $PlayerBaseInfo['MO']) . "</span>";}elseif($PlayerCompare2Info['MO'] < $PlayerBaseInfo['MO']){echo "<span style=\"color:red\">-" . ($PlayerBaseInfo['MO'] - $PlayerCompare2Info['MO']) . "</span>";}else{echo "E";}; echo "</td>";   	
	echo "<td>"; if($PlayerCompare2Info['Overall'] > $PlayerBaseInfo['Overall']){echo "<span style=\"color:green\">+" . ($PlayerCompare2Info['Overall'] - $PlayerBaseInfo['Overall']) . "</span>";}elseif($PlayerCompare2Info['Overall'] < $PlayerBaseInfo['Overall']){echo "<span style=\"color:red\">-" . ($PlayerBaseInfo['Overall'] - $PlayerCompare2Info['Overall']) . "</span>";}else{echo "E";}; echo "</td>";   
	echo "</tr>";
}?>	
</table>
<div class="STHSBlankDiv"></div>
<br />
</div>

<script>
function hook(main, sub) {
  console.log(main, sub)
  var mains = main.children(),
    subs = sub.children()
  main.change(function() {
    var val = $(this).val()
    sub.empty()
    subs.filter("." + val).clone().appendTo(sub)
  }).change()
}
$(document).ready(function() {
  hook($("[name=TeamBase]"), $("[name=PlayerBase]"))
  hook($("[name=TeamCompare1]"), $("[name=PlayerCompare1]"))
  hook($("[name=TeamCompare2]"), $("[name=PlayerCompare2]"))
});
$('#TeamBase option[value="<?php echo $TeamBase;?>"]').attr('selected', 'selected');
$('#PlayerBase option[value="<?php echo $PlayerBase;?>"]').attr('selected', 'selected');
$('#TeamCompare1 option[value="<?php echo $TeamCompare1;?>"]').attr('selected', 'selected');
$('#PlayerCompare1 option[value="<?php echo $PlayerCompare1;?>"]').attr('selected', 'selected');
$('#TeamCompare2 option[value="<?php echo $TeamCompare2;?>"]').attr('selected', 'selected');
$('#PlayerCompare2 option[value="<?php echo $PlayerCompare2;?>"]').attr('selected', 'selected');

</script>


<?php include "Footer.php";?>
