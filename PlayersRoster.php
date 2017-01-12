<!DOCTYPE html>
<?php include "Header.php";?>
<?php
$Title = (string)"";
$Team = (integer)-1; /* -1 All Team */
$Active = 4; /* Show Webpage Top Menu */
If (file_exists($DatabaseFile) == false){
	$LeagueName = $DatabaseNotFound;
	$PlayerRoster = Null;
	$LeagueOutputOption = Null;
	$FreeAgentYear = Null;
	echo "<title>" . $DatabaseNotFound . "</title>";
	$Title = $DatabaseNotFound;
}else{
	$ACSQuery = (boolean)FALSE;/* The SQL Query must be Ascending Order and not Descending */
	$Expansion = FALSE; /* To show Expension Draft Avaiable Player - Not Apply if Free Agent Option or Unassigned option is also request */
	$MaximumResult = (integer)0;
	$OrderByField = (string)"Overall";
	$OrderByFieldText = (string)"Overall";
	$OrderByInput = (string)"";
	$FreeAgentYear = (integer)-1; /* -1 = No Input */
	$Type = (integer)0; /* 0 = All / 1 = Pro / 2 = Farm */
	
	$TitleOverwrite = (string)"";
	$LeagueName = (string)"";
	if(isset($_GET['Type'])){$Type = filter_var($_GET['Type'], FILTER_SANITIZE_NUMBER_INT);} 
	if(isset($_GET['ACS'])){$ACSQuery = TRUE;}
	if(isset($_GET['Max'])){$MaximumResult = filter_var($_GET['Max'], FILTER_SANITIZE_NUMBER_INT);} 
	if(isset($_GET['Order'])){$OrderByInput  = filter_var($_GET['Order'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH);} 
	if(isset($_GET['Team'])){$Team = filter_var($_GET['Team'], FILTER_SANITIZE_NUMBER_INT);}
    if(isset($_GET['Title'])){$TitleOverwrite  = filter_var($_GET['Title'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH);} 	
	if(isset($_GET['FreeAgent'])){$FreeAgentYear = filter_var($_GET['FreeAgent'], FILTER_SANITIZE_NUMBER_INT);} 
	if(isset($_GET['Expansion'])){$Expansion = TRUE;} 

	$PlayersRosterPossibleOrderField  = array(
	array("Name","Player Name"),
	array("ConditionDecimal","Condition"),
	array("CK","Checking"),
	array("FG","Fighting"),
	array("DI","Discipline"),
	array("SK","Skating"),
	array("ST","Strength"),
	array("EN","Endurance"),
	array("DU","Durability"),
	array("PH","Puck Handling"),
	array("FO","Face Offs"),
	array("PA","Passing"),
	array("SC","Scoring"),
	array("DF","Defense"),
	array("PS","Penalty Shot"),
	array("EX","Experience"),
	array("LD","Leadership"),
	array("PO","Potential"),
	array("MO","Morale"),
	array("Overall","Overall"),
	);
	foreach ($PlayersRosterPossibleOrderField as $Value) {
		If (strtoupper($Value[0]) == strtoupper($OrderByInput)){
			$OrderByField = $Value[0];
			$OrderByFieldText = $Value[1];
			Break;
		}
	}
	
	$db = new SQLite3($DatabaseFile);
	$Query = "Select Name, RFAAge, UFAAge from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	$Query = "Select SalaryCapOption from LeagueFinance";
	$LeagueFinance = $db->querySingle($Query,true);		
	$Query = "Select MergeRosterPlayerInfo, FreeAgentUseDateInsteadofDay, FreeAgentRealDate from LeagueOutputOption";
	$LeagueOutputOption = $db->querySingle($Query,true);	
	
	If ($FreeAgentYear == 1){
		$Query = "SELECT PlayerInfo.*, NextYearFreeAgent.PlayerType AS NextYearFreeAgentPlayerType FROM PlayerInfo LEFT JOIN NextYearFreeAgent ON PlayerInfo.Number = NextYearFreeAgent.Number";
	}else{
		$Query = "SELECT * FROM PlayerInfo";
	}
		
	If($Expansion == TRUE){$Title = $DynamicTitleLang['ExpansionDraft'];}
	
	/* Team or All */
	if($Team >= 0){
		if($Team > 0){
			$QueryTeam = "SELECT Name FROM TeamProInfo WHERE Number = " . $Team;
			$TeamName = $db->querySingle($QueryTeam,true);	
			$Title = $Title . $TeamName['Name'];
		}else{
			$Title = $DynamicTitleLang['Unassigned'];
		}
		$Query = $Query . " WHERE PlayerInfo.Team = " . $Team;
	}else{
		if($Type == 1 Or $Type == 2){$Query = $Query . " WHERE PlayerInfo.Number > 0"; /* Default Place Order Where everything will return */ }
	}
	
	If($MaximumResult == 0){$Title = $Title . $DynamicTitleLang['All'];}else{$Title = $Title . $DynamicTitleLang['Top'] .$MaximumResult;}
	
	/* Pro Only or Farm  */
	if($Type == 1){
		$Query = $Query . " AND PlayerInfo.Status1 >= 2";
		$Title = $Title . $DynamicTitleLang['Pro'];
	}elseif($Type == 2){
		$Query = $Query . " AND PlayerInfo.Status1 <= 1";
		$Title = $Title . $DynamicTitleLang['Farm'];
	}
	
	/* Free Agents */
	If ($FreeAgentYear >= 0){
		if($Type == 0 AND $Team == -1){$Query = $Query . " WHERE PlayerInfo.Team > 0";}
		$Query = $Query . " AND PlayerInfo.Contract = " . $FreeAgentYear; /* Free Agent Query */ 
		If ($FreeAgentYear == 0){$Title = $Title . $DynamicTitleLang['ThisYearFreeAgents'];}elseIf ($FreeAgentYear == 1){$Title = $Title . $DynamicTitleLang['NextYearFreeAgents'];}else{$Title = $Title . " " . $FreeAgentYear . $DynamicTitleLang['YearsFreeAgents'];}
	}elseif($Expansion == TRUE){
		if($Type == 0 AND $Team == -1){$Query = $Query . " WHERE PlayerInfo.Team > 0";}
		$Query = $Query . " AND PlayerInfo.PProtected = 'False'";
	}
	
	$Title = $Title . $DynamicTitleLang['PlayersRoster'];	
	
	/* Order by and Limit */
	$Query = $Query . " ORDER BY " . $OrderByField;
	If ($ACSQuery == TRUE){
		$Query = $Query . " ASC";
		$Title = $Title . $DynamicTitleLang['InAscendingOrderBy'] . $OrderByFieldText;
	}else{
		$Query = $Query . " DESC";
		$Title = $Title . $DynamicTitleLang['InDecendingOrderBy'] . $OrderByFieldText;
	}
	If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}

	/* Ran Query */	
	$PlayerRoster = $db->query($Query);
	
	/* OverWrite Title if information is get from PHP GET */
	if($TitleOverwrite <> ""){$Title = $TitleOverwrite;}	
	echo "<title>" . $LeagueName . " - " . $Title . "</title>";
}?>
</head><body>
<?php include "Menu.php";?>
<?php echo "<h1>" . $Title . "</h1>"; ?>
<script type="text/javascript">
$(function() {
  $(".STHSPHPAllPlayerRoster_Table").tablesorter({
    widgets: ['columnSelector', 'stickyHeaders', 'filter'],
    widgetOptions : {
      columnSelector_container : $('#tablesorter_ColumnSelector'),
      columnSelector_layout : '<label><input type="checkbox">{name}</label>',
      columnSelector_name  : 'title',
      columnSelector_mediaquery: true,
      columnSelector_mediaqueryName: 'Automatic',
      columnSelector_mediaqueryState: true,
      columnSelector_mediaqueryHidden: true,
      columnSelector_breakpoints : [ '40em', '65em', '70em', '78em', '94em', '99em' ],
	  filter_columnFilters: true,
      filter_placeholder: { search : '<?php echo $TableSorterLang['Search'];?>' },
	  filter_searchDelay : 500,	  
      filter_reset: '.tablesorter_Reset'	 
    }
  });
});
</script>

<div style="width:99%;margin:auto;">

<div class="tablesorter_ColumnSelectorWrapper">
    <input id="tablesorter_colSelect1" type="checkbox" class="hidden">
    <label class="tablesorter_ColumnSelectorButton" for="tablesorter_colSelect1"><?php echo $TableSorterLang['ShoworHideColumn'];?></label>
    <div id="tablesorter_ColumnSelector" class="tablesorter_ColumnSelector"></div>
	<?php include "FilterTip.php";?>
	</div>
</div>

<table class="tablesorter STHSPHPAllPlayerRoster_Table"><thead><tr>
<th data-priority="critical" title="Player Name" class="STHSW140Min"><?php echo $PlayersLang['PlayerName'];?></th>
<?php if($Team >= 0){echo "<th class=\"columnSelector-false STHSW140Min\" data-priority=\"6\" title=\"Team Name\">" . $PlayersLang['TeamName'] . "</th>";}else{echo "<th data-priority=\"2\" title=\"Team Name\" class=\"STHSW140Min\">" . $PlayersLang['TeamName'] ."</th>";}?>
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
<?php
	if ($FreeAgentYear == -1){
		echo "<th data-priority=\"5\" class=\"STHSW25\" title=\"Trade Available\">TA</th>";
	}else{
		echo "<th data-priority=\"4\" class=\"STHSW25\" title=\"Status\">" . $PlayersLang['Status'] . "</th>";
	}
	if ($LeagueOutputOption['MergeRosterPlayerInfo'] == "True"){ 
		echo "<th data-priority=\"6\" title=\"Star Power\" class=\"columnSelector-false STHSW25\">SP</th>";	
		echo "<th data-priority=\"5\" class=\"STHSW25\" title=\"Age\">" . $PlayersLang['Age'] . "</th>";
		echo "<th data-priority=\"5\" class=\"STHSW25\" title=\"Contract\">" . $PlayersLang['Contract'] . "</th>";
		if ($LeagueFinance['SalaryCapOption'] == 4 OR $LeagueFinance['SalaryCapOption'] == 5 OR $LeagueFinance['SalaryCapOption'] == 6){
			echo "<th data-priority=\"5\" class=\"STHSW100\" title=\"Salary Average\">" . $PlayersLang['SalaryAverage'] ."</th>";
		}else{
			echo "<th data-priority=\"5\" class=\"STHSW100\" title=\"Salary\">" . $PlayersLang['Salary'] ."</th>";
		}
	}else{
		echo "<th data-priority=\"5\" title=\"Star Power\" class=\"STHSW25\">SP</th>";	
	}
?>
<th data-priority="5" title="Hyperlink" class="STHSW35"><?php echo $PlayersLang['Link'];?></th>
</tr></thead><tbody>
<?php
if (empty($PlayerRoster) == false){while ($Row = $PlayerRoster ->fetchArray()) {
	$strTemp = (string)$Row['Name'];
	If ($Row['Rookie']== "True"){ $strTemp = $strTemp . " (R)";}
	echo "<tr><td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $strTemp . "</a></td>";
	echo "<td>" . $Row['TeamName'] . "</td>";	
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
	if ($FreeAgentYear == -1){
		echo "<td>";if ($Row['AvailableforTrade']== "True"){ echo "X";}; echo"</td>";
	}else{
		If ($FreeAgentYear == 1 AND $Row['NextYearFreeAgentPlayerType']=="True"){
			echo "<td>" . $PlayersLang['AlreadyResign'] . "</td>";
		}elseif ($LeagueOutputOption['FreeAgentUseDateInsteadofDay'] == "True" AND $FreeAgentYear == 1){
			$age = date_diff(date_create($Row['AgeDate']), date_create($LeagueOutputOption['FreeAgentRealDate']))->y;
			if ($age >= $LeagueGeneral['UFAAge']){echo "<td>" . $PlayersLang['UFA'] . "</td>";}elseif($age >= $LeagueGeneral['RFAAge']){echo "<td>" . $PlayersLang['RFA'] . "</td>";}else{echo "<td>" . $PlayersLang['ELC'] . "</td>";}
		}else{
			if ($Row['Age'] >= $LeagueGeneral['UFAAge']){echo "<td>" . $PlayersLang['UFA'] . "</td>";}elseif($Row['Age'] >= $LeagueGeneral['RFAAge']){echo "<td>" . $PlayersLang['RFA'] . "</td>";}else{echo "<td>" . $PlayersLang['ELC'] . "</td>";}
		}	
	}
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
	If ($Row['URLLink'] == ""){echo "<td></td>";}else{echo "<td><a href=\"" . $Row['URLLink'] . "\" target=\"new\">" . $PlayersLang['Link'] ."</td>";}
	echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
}}
?>
</tbody></table>
<?php 
if ($FreeAgentYear >= 0){
	echo "<em>"  . $DynamicTitleLang['FreeAgentStatus'];
	if ($LeagueOutputOption['FreeAgentUseDateInsteadofDay'] == "True" AND $FreeAgentYear == 1){
		echo date_Format(date_create($LeagueOutputOption['FreeAgentRealDate']),"Y-m-d") . "</em>";
	}else{
		echo date("Y-m-d") . "</em>";
	}
}
?>
<br />
</div>

<?php include "Footer.php";?>
