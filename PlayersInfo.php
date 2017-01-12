<!DOCTYPE html>
<?php include "Header.php";?>
<?php
$Team = (integer)-1; /* -1 All Team */
$Active = 1; /* Show Webpage Top Menu */
If (file_exists($DatabaseFile) == false){
	$LeagueName = $DatabaseNotFound;
	$PlayerInfo = Null;
	$LeagueOutputOption = Null;
	$FreeAgentYear = Null;
	echo "<title>" . $DatabaseNotFound . "</title>";
	$Title = $DatabaseNotFound;
}else{
	$DESCQuery = (boolean)FALSE;/* The SQL Query must be Descending Order and not Ascending*/
	$Expansion = FALSE; /* To show Expension Draft Avaiable Player - Not Apply if Free Agent Option or Unassigned option is also request */
	$MaximumResult = (integer)0;
	$OrderByField = (string)"Name";
	$OrderByFieldText = (string)"Name";
	$OrderByInput = (string)"";
	$FreeAgentYear = (integer)-1; /* -1 = No Input */
	$Type = (integer)0; /* 0 = All / 1 = Pro / 2 = Farm */
	$TypeQuery = "Number > 0";
	$TeamQuery = "Team >= 0";
	$Title = (string)"";
	$TitleOverwrite = (string)"";
	$LeagueName = (string)"";
	if(isset($_GET['Type'])){$Type = filter_var($_GET['Type'], FILTER_SANITIZE_NUMBER_INT);} 
	if(isset($_GET['DESC'])){$DESCQuery = TRUE;}
	if(isset($_GET['Max'])){$MaximumResult = filter_var($_GET['Max'], FILTER_SANITIZE_NUMBER_INT);} 
	if(isset($_GET['Order'])){$OrderByInput  = filter_var($_GET['Order'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH);} 
	if(isset($_GET['Team'])){$Team = filter_var($_GET['Team'], FILTER_SANITIZE_NUMBER_INT);} 
	if(isset($_GET['Title'])){$TitleOverwrite = filter_var($_GET['Title'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH);} 
	if(isset($_GET['FreeAgent'])){$FreeAgentYear = filter_var($_GET['FreeAgent'], FILTER_SANITIZE_NUMBER_INT);} 
	if(isset($_GET['Expansion'])){$Expansion = TRUE;} 

	$PlayersInformationPossibleOrderField = array(
	array("Name","Player Name"),
	array("Team","Team Number"),
	array("Age","Age"),
	array("Rookie","Rookie"),
	array("Weight","Weight"),
	array("Height","Height"),
	array("NoTrade","No Trade"),
	array("ForceWaiver","Force Waiver"),
	array("Contract","Contract Duration"),
	array("Salary1","Current Salary"),
	array("SalaryAverage","Salary Average"),
	array("Salary2","Salary Year 2"),
	array("Salary3","Salary Year 3"),
	array("Salary4","Salary Year 4"),
	array("Salary5","Salary Year 5"),
	array("Salary6","Salary Year 6"),
	array("Salary7","Salary Year 7"),
	array("Salary8","Salary Year 8"),
	array("Salary9","Salary Year 9"),
	array("Salary10","Salary Year 10"),
	);
	foreach ($PlayersInformationPossibleOrderField as $Value) {
		If (strtoupper($Value[0]) == strtoupper($OrderByInput)){
			$OrderByField = $Value[0];
			$OrderByFieldText = $Value[1];
			Break;
		}
	}
	
	$db = new SQLite3($DatabaseFile);
	$Query = "Select OutputSalariesRemaining, OutputSalariesAverageTotal, OutputSalariesAverageRemaining, InchInsteadofCM, LBSInsteadofKG, FreeAgentUseDateInsteadofDay, FreeAgentRealDate from LeagueOutputOption";
	$LeagueOutputOption = $db->querySingle($Query,true);	
	$Query = "Select RemoveSalaryCapWhenPlayerUnderCondition, SalaryCapOption from LeagueFinance";
	$LeagueFinance = $db->querySingle($Query,true);	
	$Query = "Select Name, ProScheduleTotalDay, FarmScheduleTotalDay, ScheduleNextDay, RFAAge, UFAAge from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	
	If($Expansion == TRUE){$Title = $DynamicTitleLang['ExpansionDraft'];}
	
	/* Team or All */
	If ($Team >= 0){
		if($Team > 0){
			$QueryTeam = "SELECT Name FROM TeamProInfo WHERE Number = " . $Team;
			$TeamName = $db->querySingle($QueryTeam,true);	
			$Title = $Title . $TeamName['Name'];
		}else{
			$Title = $DynamicTitleLang['Unassigned'];
		}
		$TeamQuery = "Team = " . $Team;
	}else{
		$TeamQuery = "Team >= 0"; /* Default Place Order Where everything will return */
	}
	
	If($MaximumResult == 0){$Title = $Title . $DynamicTitleLang['All'];}else{$Title = $Title . $DynamicTitleLang['Top'] .$MaximumResult;}
	
	/* Pro Only or Farm  */
	if($Type == 1){
		$TypeQuery = "Status1 >= 2";
		$ScheduleTotalDay = $LeagueGeneral['ProScheduleTotalDay'];
		$Title = $Title . $DynamicTitleLang['Pro'];
	}elseif($Type == 2){
		$TypeQuery = "Status1 <= 1";
		$ScheduleTotalDay = $LeagueGeneral['FarmScheduleTotalDay'];
		$Title = $Title . $DynamicTitleLang['Farm'];
	}else{
		$TypeQuery = "Number > 0"; /* Default Place Order Where everything will return */
		$ScheduleTotalDay = $LeagueGeneral['ProScheduleTotalDay'];
	} 
		
	/* Main Query with correct Variable */
	$Query = "SELECT MainTable.*, PlayerInfo.PosC, PlayerInfo.PosLW, PlayerInfo.PosRW, PlayerInfo.PosD, GoalerInfo.PosG FROM ((SELECT PlayerInfo.Number, PlayerInfo.Name, PlayerInfo.Team, PlayerInfo.TeamName, PlayerInfo.Age, PlayerInfo.AgeDate, PlayerInfo.Weight, PlayerInfo.Height, PlayerInfo.Contract, PlayerInfo.Rookie, PlayerInfo.NoTrade, PlayerInfo.CanPlayPro, PlayerInfo.CanPlayFarm, PlayerInfo.ForceWaiver, PlayerInfo.ExcludeSalaryCap, PlayerInfo.ProSalaryinFarm, PlayerInfo.SalaryAverage, PlayerInfo.Salary1, PlayerInfo.Salary2, PlayerInfo.Salary3, PlayerInfo.Salary4, PlayerInfo.Salary5, PlayerInfo.Salary6, PlayerInfo.Salary7, PlayerInfo.Salary8, PlayerInfo.Salary9, PlayerInfo.Salary10, PlayerInfo.SalaryRemaining, PlayerInfo.SalaryAverageRemaining, PlayerInfo.Condition, PlayerInfo.ConditionDecimal,PlayerInfo.Status1, PlayerInfo.URLLink, PlayerInfo.PProtected FROM PlayerInfo WHERE " . $TeamQuery . " AND " . $TypeQuery . " UNION ALL SELECT GoalerInfo.Number, GoalerInfo.Name, GoalerInfo.Team, GoalerInfo.TeamName, GoalerInfo.Age, GoalerInfo.AgeDate,GoalerInfo.Weight, GoalerInfo.Height, GoalerInfo.Contract, GoalerInfo.Rookie, GoalerInfo.NoTrade, GoalerInfo.CanPlayPro, GoalerInfo.CanPlayFarm, GoalerInfo.ForceWaiver, GoalerInfo.ExcludeSalaryCap, GoalerInfo.ProSalaryinFarm, GoalerInfo.SalaryAverage, GoalerInfo.Salary1, GoalerInfo.Salary2, GoalerInfo.Salary3, GoalerInfo.Salary4, GoalerInfo.Salary5, GoalerInfo.Salary6, GoalerInfo.Salary7, GoalerInfo.Salary8, GoalerInfo.Salary9, GoalerInfo.Salary10, GoalerInfo.SalaryRemaining, GoalerInfo.SalaryAverageRemaining, GoalerInfo.Condition, GoalerInfo.ConditionDecimal, GoalerInfo.Status1, GoalerInfo.URLLink, GoalerInfo.PProtected FROM GoalerInfo WHERE " . $TeamQuery . " AND " . $TypeQuery . ")  AS MainTable LEFT JOIN PlayerInfo ON MainTable.Name = PlayerInfo.Name) LEFT JOIN GoalerInfo ON MainTable.Name = GoalerInfo.Name"; 

	/* Free Agents */
	If ($FreeAgentYear >= 0){
		$Query = $Query . " WHERE MainTable.Contract = " . $FreeAgentYear; /* Free Agent Query */ 
		If ($FreeAgentYear == 0){$Title = $Title . $DynamicTitleLang['ThisYearFreeAgents'];}elseIf ($FreeAgentYear == 1){$Title = $Title . $DynamicTitleLang['NextYearFreeAgents'];}else{$Title = $Title . " " . $FreeAgentYear . $DynamicTitleLang['YearsFreeAgents'];}
		If ($FreeAgentYear == 1){ /* OverWrite to add a Left Join to NextYearFreeAgent */
			$Query = "SELECT MainTable.*, PlayerInfo.PosC, PlayerInfo.PosLW, PlayerInfo.PosRW, PlayerInfo.PosD, GoalerInfo.PosG FROM ((SELECT PlayerInfo.Number, PlayerInfo.Name, PlayerInfo.Team, PlayerInfo.TeamName, PlayerInfo.Age, PlayerInfo.AgeDate, PlayerInfo.Weight, PlayerInfo.Height, PlayerInfo.Contract, PlayerInfo.Rookie, PlayerInfo.NoTrade, PlayerInfo.CanPlayPro, PlayerInfo.CanPlayFarm, PlayerInfo.ForceWaiver, PlayerInfo.ExcludeSalaryCap, PlayerInfo.ProSalaryinFarm, PlayerInfo.SalaryAverage, PlayerInfo.Salary1, PlayerInfo.Salary2, PlayerInfo.Salary3, PlayerInfo.Salary4, PlayerInfo.Salary5, PlayerInfo.Salary6, PlayerInfo.Salary7, PlayerInfo.Salary8, PlayerInfo.Salary9, PlayerInfo.Salary10, PlayerInfo.SalaryRemaining, PlayerInfo.SalaryAverageRemaining, PlayerInfo.Condition, PlayerInfo.ConditionDecimal,PlayerInfo.Status1, PlayerInfo.URLLink, NextYearFreeAgent.PlayerType AS NextYearFreeAgentPlayerType FROM PlayerInfo LEFT JOIN NextYearFreeAgent ON PlayerInfo.Number = NextYearFreeAgent.Number WHERE PlayerInfo." . $TeamQuery . " AND PlayerInfo." . $TypeQuery . " UNION ALL SELECT GoalerInfo.Number, GoalerInfo.Name, GoalerInfo.Team, GoalerInfo.TeamName, GoalerInfo.Age, GoalerInfo.AgeDate,GoalerInfo.Weight, GoalerInfo.Height, GoalerInfo.Contract, GoalerInfo.Rookie, GoalerInfo.NoTrade, GoalerInfo.CanPlayPro, GoalerInfo.CanPlayFarm, GoalerInfo.ForceWaiver, GoalerInfo.ExcludeSalaryCap, GoalerInfo.ProSalaryinFarm, GoalerInfo.SalaryAverage, GoalerInfo.Salary1, GoalerInfo.Salary2, GoalerInfo.Salary3, GoalerInfo.Salary4, GoalerInfo.Salary5, GoalerInfo.Salary6, GoalerInfo.Salary7, GoalerInfo.Salary8, GoalerInfo.Salary9, GoalerInfo.Salary10, GoalerInfo.SalaryRemaining, GoalerInfo.SalaryAverageRemaining, GoalerInfo.Condition, GoalerInfo.ConditionDecimal, GoalerInfo.Status1, GoalerInfo.URLLink, NextYearFreeAgent.PlayerType AS NextYearFreeAgentPlayerType FROM GoalerInfo LEFT JOIN NextYearFreeAgent ON GoalerInfo.Number = NextYearFreeAgent.Number WHERE GoalerInfo." . $TeamQuery . " AND GoalerInfo." . $TypeQuery . ")  AS MainTable LEFT JOIN PlayerInfo ON MainTable.Name = PlayerInfo.Name) LEFT JOIN GoalerInfo ON MainTable.Name = GoalerInfo.Name WHERE MainTable.Contract = " . $FreeAgentYear; 
		}
	}elseif($Expansion == TRUE){
		if($Type == 0 AND $Team == -1){$Query = $Query . " WHERE MainTable.Team > 0";}
		$Query = $Query . " AND MainTable.PProtected = 'False'";
	}
	
	$Query = $Query . " ORDER BY MainTable." . $OrderByField;
	
	$Title = $Title . $DynamicTitleLang['PlayersInformation'];	
	
	/* Order by and Limit */
	If ($DESCQuery == TRUE){
		$Query = $Query . " DESC";
		$Title = $Title . $DynamicTitleLang['InDecendingOrderBy'] . $OrderByFieldText;
	}else{
		$Query = $Query . " ASC";
		$Title = $Title . $DynamicTitleLang['InAscendingOrderBy'] . $OrderByFieldText;
	}	
	If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}

	/* Ran Query */	
	$PlayerInfo = $db->query($Query);
	
	/* OverWrite Title if information is get from PHP GET */
	if($TitleOverwrite <> ""){$Title = $TitleOverwrite;}
	echo "<title>" . $LeagueName . " - " . $Title . "</title>";
}?>
</head><body>
<?php include "Menu.php";?>
<?php echo "<h1>" . $Title . "</h1>"; ?>
<script type="text/javascript">
$(function() {
  $(".STHSPHPAllPlayerInformation_Table").tablesorter({
    widgets: ['columnSelector', 'stickyHeaders', 'filter'],
    widgetOptions : {
      columnSelector_container : $('#tablesorter_ColumnSelector'),
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

<table class="tablesorter STHSPHPAllPlayerInformation_Table"><thead><tr>
<?php 
$TeamSub = $Team;
include "PlayersInfoSub.php";
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
