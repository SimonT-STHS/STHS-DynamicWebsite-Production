<?php include "Header.php";
$Team = (integer)-1; /* -1 All Team */
$Search = (boolean)False;
include "SearchPossibleOrderField.php";
$HistoryOutput = (boolean)False;
If (file_exists($DatabaseFile) == false){
	Goto STHSErrorPlayerInfo;
}else{try{
	$DESCQuery = (boolean)FALSE;/* The SQL Query must be Descending Order and not Ascending*/
	$Expansion = (boolean)FALSE; /* To show Expension Draft Avaiable Player - Not Apply if Free Agent Option */
	$AvailableForTrade = (boolean)FALSE; /* To show Available for Trade Only - Not Apply if Free Agent Option or Expansion option is also request */
	$Retire = (string )"'False'"; /* To Show Retire Player or Not */
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
	if(isset($_GET['Order'])){$OrderByInput  = filter_var($_GET['Order'], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH || FILTER_FLAG_NO_ENCODE_QUOTES || FILTER_FLAG_STRIP_BACKTICK);} 
	if(isset($_GET['Team'])){$Team = filter_var($_GET['Team'], FILTER_SANITIZE_NUMBER_INT);} 
	if(isset($_GET['Title'])){$TitleOverwrite = filter_var($_GET['Title'], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH || FILTER_FLAG_NO_ENCODE_QUOTES || FILTER_FLAG_STRIP_BACKTICK);} 
	if(isset($_GET['FreeAgent'])){$FreeAgentYear = filter_var($_GET['FreeAgent'], FILTER_SANITIZE_NUMBER_INT);If ($FreeAgentYear == null){$FreeAgentYear = (integer)0;}} 
	if(isset($_GET['Expansion'])){$Expansion = TRUE;} 
	if(isset($_GET['AvailableForTrade'])){$AvailableForTrade = TRUE;} 
	if(isset($_GET['Retire'])){$Retire = "'True'";} 
	foreach ($PlayersInformationPossibleOrderField as $Value) {
		If (strtoupper($Value[0]) == strtoupper($OrderByInput)){
			$OrderByField = $Value[0];
			$OrderByFieldText = $Value[1];
			Break;
		}
	}
	
	$Playoff = (boolean)False;
	$PlayoffString = (string)"False";
	$Year = (integer)0;	
	if(isset($_GET['Playoff'])){$Playoff=True;$PlayoffString="True";}
	if(isset($_GET['Year'])){$Year = filter_var($_GET['Year'], FILTER_SANITIZE_NUMBER_INT);} 

	If($Year > 0 AND file_exists($CareerStatDatabaseFile) == true){  /* CareerStat */
		$db = new SQLite3($CareerStatDatabaseFile);
		$CareerDBFormatV2CheckCheck = $db->querySingle("SELECT Count(name) AS CountName FROM sqlite_master WHERE type='table' AND name='LeagueGeneral'",true);
		If ($CareerDBFormatV2CheckCheck['CountName'] == 1){
			$HistoryOutput = True;
			
			/* Reset Variable Ignore in History Mode */
			$FreeAgentYear = (integer)-1; /* -1 = No Input  */
			$Expansion = (boolean)FALSE;
			
			$Query = "Select Name, ProScheduleTotalDay, FarmScheduleTotalDay, ScheduleNextDay, RFAAge, UFAAge from LeagueGeneral WHERE Year = " . $Year . " And Playoff = '" . $PlayoffString. "'";
			$LeagueGeneral = $db->querySingle($Query,true);		

			//Confirm Valid Data Found
			$CareerDBFormatV2CheckCheck = $db->querySingle("Select Count(Name) As CountName from LeagueGeneral  WHERE Year = " . $Year . " And Playoff = '" . $PlayoffString. "'",true);
			If ($CareerDBFormatV2CheckCheck['CountName'] == 1){$LeagueName = $LeagueGeneral['Name'];}else{$Year = (integer)0;$HistoryOutput = (boolean)False;Goto RegularSeason;}		

			$Query = "Select OutputSalariesRemaining, OutputSalariesAverageTotal, OutputSalariesAverageRemaining, InchInsteadofCM, LBSInsteadofKG, FreeAgentUseDateInsteadofDay, FreeAgentRealDate from LeagueOutputOption WHERE Year = " . $Year . " And Playoff = '" . $PlayoffString. "'";
			$LeagueOutputOption = $db->querySingle($Query,true);	
			$Query = "Select RemoveSalaryCapWhenPlayerUnderCondition, SalaryCapOption from LeagueFinance WHERE Year = " . $Year . " And Playoff = '" . $PlayoffString. "'";
			$LeagueFinance = $db->querySingle($Query,true);	
			
			If($Expansion == TRUE){$Title = $DynamicTitleLang['ExpansionDraft'];}
			If($AvailableForTrade == TRUE){$Title = $DynamicTitleLang['AvailableForTrade'];}
			If($Retire == "'True'"){$Title = $DynamicTitleLang['Retire'];}
			
			/* Team or All */
			If ($Team >= 0){
				if($Team > 0){
					$QueryTeam = "SELECT Name FROM TeamProInfoHistory WHERE Number = " . $Team . " AND Year = " . $Year . " And Playoff = '" . $PlayoffString. "'";		
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
			$Query = "SELECT MainTable.* FROM (SELECT PlayerInfoHistory.Number, PlayerInfoHistory.Name, PlayerInfoHistory.Team, PlayerInfoHistory.TeamName, PlayerInfoHistory.ProTeamName, '0' As TeamThemeID, PlayerInfoHistory.Age, PlayerInfoHistory.AgeDate, PlayerInfoHistory.Weight, PlayerInfoHistory.Height, PlayerInfoHistory.Contract, PlayerInfoHistory.Rookie, PlayerInfoHistory.NoTrade, PlayerInfoHistory.CanPlayPro, PlayerInfoHistory.CanPlayFarm, PlayerInfoHistory.ForceWaiver, PlayerInfoHistory.ExcludeSalaryCap, PlayerInfoHistory.ProSalaryinFarm, PlayerInfoHistory.SalaryAverage, PlayerInfoHistory.Salary1, PlayerInfoHistory.Salary2, PlayerInfoHistory.Salary3, PlayerInfoHistory.Salary4, PlayerInfoHistory.Salary5, PlayerInfoHistory.Salary6, PlayerInfoHistory.Salary7, PlayerInfoHistory.Salary8, PlayerInfoHistory.Salary9, PlayerInfoHistory.Salary10, 'False' AS NoTrade1, 'False' AS NoTrade2, 'False' AS NoTrade3, 'False' AS NoTrade4, 'False' AS NoTrade5, 'False' AS NoTrade6, 'False' AS NoTrade7, 'False' AS NoTrade8, 'False' AS NoTrade9, 'False' AS NoTrade10, PlayerInfoHistory.SalaryRemaining, PlayerInfoHistory.SalaryAverageRemaining, PlayerInfoHistory.SalaryCap, PlayerInfoHistory.SalaryCapRemaining, PlayerInfoHistory.Condition, PlayerInfoHistory.ConditionDecimal,PlayerInfoHistory.Status1, PlayerInfoHistory.URLLink, PlayerInfoHistory.NHLID, PlayerInfoHistory.PProtected, PlayerInfoHistory.AvailableForTrade,PlayerInfoHistory.PosC, PlayerInfoHistory.PosLW, PlayerInfoHistory.PosRW, PlayerInfoHistory.PosD, 'False' AS PosG, '' As AcquiredType, '' As LastTradeDate, '' As ContractSignatureDate, '' As ForceUFA, '' As EmergencyRecall, PlayerInfoHistory.Retire as Retire FROM PlayerInfoHistory WHERE Year = " . $Year . " And Playoff = '" . $PlayoffString. "' AND " . $TeamQuery . " AND Retire = " . $Retire . " AND " . $TypeQuery . " UNION ALL SELECT GoalerInfoHistory.Number, GoalerInfoHistory.Name, GoalerInfoHistory.Team, GoalerInfoHistory.TeamName, GoalerInfoHistory.ProTeamName, '0' As TeamThemeID, GoalerInfoHistory.Age, GoalerInfoHistory.AgeDate, GoalerInfoHistory.Weight, GoalerInfoHistory.Height, GoalerInfoHistory.Contract, GoalerInfoHistory.Rookie, GoalerInfoHistory.NoTrade, GoalerInfoHistory.CanPlayPro, GoalerInfoHistory.CanPlayFarm, GoalerInfoHistory.ForceWaiver, GoalerInfoHistory.ExcludeSalaryCap, GoalerInfoHistory.ProSalaryinFarm, GoalerInfoHistory.SalaryAverage, GoalerInfoHistory.Salary1, GoalerInfoHistory.Salary2, GoalerInfoHistory.Salary3, GoalerInfoHistory.Salary4, GoalerInfoHistory.Salary5, GoalerInfoHistory.Salary6, GoalerInfoHistory.Salary7, GoalerInfoHistory.Salary8, GoalerInfoHistory.Salary9, GoalerInfoHistory.Salary10, 'False' AS NoTrade1, 'False' AS NoTrade2, 'False' AS NoTrade3, 'False' AS NoTrade4, 'False' AS NoTrade5, 'False' AS NoTrade6, 'False' AS NoTrade7, 'False' AS NoTrade8, 'False' AS NoTrade9, 'False' AS NoTrade10, GoalerInfoHistory.SalaryRemaining, GoalerInfoHistory.SalaryAverageRemaining, GoalerInfoHistory.SalaryCap, GoalerInfoHistory.SalaryCapRemaining, GoalerInfoHistory.Condition, GoalerInfoHistory.ConditionDecimal, GoalerInfoHistory.Status1, GoalerInfoHistory.URLLink, GoalerInfoHistory.NHLID, GoalerInfoHistory.PProtected, GoalerInfoHistory.AvailableForTrade,'False' AS PosC, 'False' AS PosLW, 'False' AS PosRW, 'False' AS PosD, 'True' AS PosG, '' As AcquiredType, '' As LastTradeDate, '' As ContractSignatureDate, ,'' As ForceUFA, '' As EmergencyRecall GoalerInfoHistory.Retire as Retire FROM GoalerInfoHistory WHERE Year = " . $Year . " And Playoff = '" . $PlayoffString. "' AND  " . $TeamQuery . " AND Retire = " . $Retire . " AND " . $TypeQuery . ") AS MainTable"; 

			If ($AvailableForTrade == TRUE){
				if($Type == 0 AND $Team == -1){$Query = $Query . " WHERE MainTable.Team > 0";}
				$Query = $Query . " AND MainTable.AvailableForTrade = 'True'";		
			}
			
			$Query = $Query . " ORDER BY MainTable." . $OrderByField;
			
			$Title = $Title . $DynamicTitleLang['PlayersInformation'] . " - " . $Year;
			If ($Playoff == True){$Title = $Title . $TopMenuLang['Playoff'];}	
			
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
			
			echo "<title>" . $LeagueName . " - " . $Title . "</title>";

		}else{
			Goto RegularSeason;
		}
	}else{
		/* Regular Season */
		RegularSeason:					
	
		$db = new SQLite3($DatabaseFile);
		$Query = "Select AllowPlayerEditionFromWebsite from LeagueWebClient";
		$LeagueWebClient = $db->querySingle($Query,true);		
		$Query = "Select OutputSalariesRemaining, OutputSalariesAverageTotal, OutputSalariesAverageRemaining, InchInsteadofCM, LBSInsteadofKG, FreeAgentUseDateInsteadofDay, FreeAgentRealDate from LeagueOutputOption";
		$LeagueOutputOption = $db->querySingle($Query,true);	
		$Query = "Select RemoveSalaryCapWhenPlayerUnderCondition, SalaryCapOption from LeagueFinance";
		$LeagueFinance = $db->querySingle($Query,true);	
		$Query = "Select Name, ProScheduleTotalDay, FarmScheduleTotalDay, ScheduleNextDay, RFAAge, UFAAge from LeagueGeneral";
		$LeagueGeneral = $db->querySingle($Query,true);		
		$LeagueName = $LeagueGeneral['Name'];
				
		If($Expansion == TRUE){$Title = $DynamicTitleLang['ExpansionDraft'];}
		If($AvailableForTrade == TRUE){$Title = $DynamicTitleLang['AvailableForTrade'];}
		If($Retire == "'True'"){$Title = $DynamicTitleLang['Retire'];}
		
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
		$Query = "SELECT MainTable.* FROM (SELECT PlayerInfo.Number, PlayerInfo.Name, PlayerInfo.Team, PlayerInfo.TeamName, PlayerInfo.ProTeamName, PlayerInfo.TeamThemeID, PlayerInfo.Age, PlayerInfo.AgeDate, PlayerInfo.Weight, PlayerInfo.Height, PlayerInfo.Contract, PlayerInfo.Rookie, PlayerInfo.NoTrade, PlayerInfo.CanPlayPro, PlayerInfo.CanPlayFarm, PlayerInfo.ForceWaiver, PlayerInfo.WaiverPossible, PlayerInfo.ExcludeSalaryCap, PlayerInfo.ProSalaryinFarm, PlayerInfo.SalaryAverage, PlayerInfo.Salary1, PlayerInfo.Salary2, PlayerInfo.Salary3, PlayerInfo.Salary4, PlayerInfo.Salary5, PlayerInfo.Salary6, PlayerInfo.Salary7, PlayerInfo.Salary8, PlayerInfo.Salary9, PlayerInfo.Salary10, PlayerInfo.NoTrade1, PlayerInfo.NoTrade2, PlayerInfo.NoTrade3, PlayerInfo.NoTrade4, PlayerInfo.NoTrade5, PlayerInfo.NoTrade6, PlayerInfo.NoTrade7, PlayerInfo.NoTrade8, PlayerInfo.NoTrade9, PlayerInfo.NoTrade10, PlayerInfo.SalaryRemaining, PlayerInfo.SalaryAverageRemaining, PlayerInfo.SalaryCap, PlayerInfo.SalaryCapRemaining, PlayerInfo.Condition, PlayerInfo.ConditionDecimal,PlayerInfo.Status1, PlayerInfo.URLLink, PlayerInfo.NHLID, PlayerInfo.PProtected, PlayerInfo.AvailableForTrade,PlayerInfo.PosC, PlayerInfo.PosLW, PlayerInfo.PosRW, PlayerInfo.PosD, 'False' AS PosG, PlayerInfo.AcquiredType as AcquiredType, PlayerInfo.LastTradeDate as LastTradeDate, PlayerInfo.ContractSignatureDate As ContractSignatureDate, PlayerInfo.ForceUFA As ForceUFA, PlayerInfo.EmergencyRecall As EmergencyRecall, PlayerInfo.Retire as Retire FROM PlayerInfo WHERE " . $TeamQuery . " AND Retire = " . $Retire . " AND " . $TypeQuery . " UNION ALL SELECT GoalerInfo.Number, GoalerInfo.Name, GoalerInfo.Team, GoalerInfo.TeamName, GoalerInfo.ProTeamName, GoalerInfo.TeamThemeID, GoalerInfo.Age, GoalerInfo.AgeDate, GoalerInfo.Weight, GoalerInfo.Height, GoalerInfo.Contract, GoalerInfo.Rookie, GoalerInfo.NoTrade, GoalerInfo.CanPlayPro, GoalerInfo.CanPlayFarm, GoalerInfo.ForceWaiver, GoalerInfo.WaiverPossible, GoalerInfo.ExcludeSalaryCap, GoalerInfo.ProSalaryinFarm, GoalerInfo.SalaryAverage, GoalerInfo.Salary1, GoalerInfo.Salary2, GoalerInfo.Salary3, GoalerInfo.Salary4, GoalerInfo.Salary5, GoalerInfo.Salary6, GoalerInfo.Salary7, GoalerInfo.Salary8, GoalerInfo.Salary9, GoalerInfo.Salary10, GoalerInfo.NoTrade1, GoalerInfo.NoTrade2, GoalerInfo.NoTrade3, GoalerInfo.NoTrade4, GoalerInfo.NoTrade5, GoalerInfo.NoTrade6, GoalerInfo.NoTrade7, GoalerInfo.NoTrade8, GoalerInfo.NoTrade9, GoalerInfo.NoTrade10, GoalerInfo.SalaryRemaining, GoalerInfo.SalaryAverageRemaining, GoalerInfo.SalaryCap, GoalerInfo.SalaryCapRemaining, GoalerInfo.Condition, GoalerInfo.ConditionDecimal, GoalerInfo.Status1, GoalerInfo.URLLink, GoalerInfo.NHLID, GoalerInfo.PProtected, GoalerInfo.AvailableForTrade,'False' AS PosC, 'False' AS PosLW, 'False' AS PosRW, 'False' AS PosD, 'True' AS PosG, GoalerInfo.AcquiredType as AcquiredType, GoalerInfo.LastTradeDate as LastTradeDate, GoalerInfo.ContractSignatureDate As ContractSignatureDate, GoalerInfo.ForceUFA As ForceUFA, GoalerInfo.EmergencyRecall As EmergencyRecall, GoalerInfo.Retire as Retire FROM GoalerInfo WHERE " . $TeamQuery . " AND Retire = " . $Retire . " AND " . $TypeQuery . ") AS MainTable"; 

		/* Free Agents */
		If ($FreeAgentYear >= 0){
			$Query = $Query . " WHERE MainTable.Contract = " . $FreeAgentYear; /* Free Agent Query */ 
			If ($FreeAgentYear == 0){$Title = $Title . $DynamicTitleLang['ThisYearFreeAgents'];}elseIf ($FreeAgentYear == 1){$Title = $Title . $DynamicTitleLang['NextYearFreeAgents'];}else{$Title = $Title . " " . $FreeAgentYear . $DynamicTitleLang['YearsFreeAgents'];}
			If ($FreeAgentYear == 1){ /* OverWrite to add a Left Join to NextYearFreeAgent */
				$Query = "SELECT MainTable.* FROM (SELECT PlayerInfo.Number, PlayerInfo.Name, PlayerInfo.Team, PlayerInfo.TeamName, PlayerInfo.ProTeamName, PlayerInfo.TeamThemeID, PlayerInfo.Age, PlayerInfo.AgeDate, PlayerInfo.Weight, PlayerInfo.Height, PlayerInfo.Contract, PlayerInfo.Rookie, PlayerInfo.NoTrade, PlayerInfo.CanPlayPro, PlayerInfo.CanPlayFarm, PlayerInfo.ForceWaiver, PlayerInfo.WaiverPossible, PlayerInfo.ExcludeSalaryCap, PlayerInfo.ProSalaryinFarm, PlayerInfo.SalaryAverage, PlayerInfo.Salary1, PlayerInfo.Salary2, PlayerInfo.Salary3, PlayerInfo.Salary4, PlayerInfo.Salary5, PlayerInfo.Salary6, PlayerInfo.Salary7, PlayerInfo.Salary8, PlayerInfo.Salary9, PlayerInfo.Salary10, PlayerInfo.SalaryRemaining, PlayerInfo.SalaryAverageRemaining, PlayerInfo.SalaryCap, PlayerInfo.SalaryCapRemaining, PlayerInfo.Condition, PlayerInfo.ConditionDecimal,PlayerInfo.Status1, PlayerInfo.URLLink, PlayerInfo.NHLID, PlayerInfo.AvailableForTrade,PlayerInfo.PosC, PlayerInfo.PosLW, PlayerInfo.PosRW, PlayerInfo.PosD, 'False' AS PosG, PlayerInfo.Retire as Retire, NextYearFreeAgent.PlayerType AS NextYearFreeAgentPlayerType FROM PlayerInfo LEFT JOIN NextYearFreeAgent ON PlayerInfo.Number = NextYearFreeAgent.Number WHERE PlayerInfo." . $TeamQuery . " AND Retire = " . $Retire . " AND PlayerInfo." . $TypeQuery . " UNION ALL SELECT GoalerInfo.Number, GoalerInfo.Name, GoalerInfo.Team, GoalerInfo.TeamName, GoalerInfo.ProTeamName, GoalerInfo.TeamThemeID, GoalerInfo.Age, GoalerInfo.AgeDate,GoalerInfo.Weight, GoalerInfo.Height, GoalerInfo.Contract, GoalerInfo.Rookie, GoalerInfo.NoTrade, GoalerInfo.CanPlayPro, GoalerInfo.CanPlayFarm, GoalerInfo.ForceWaiver, GoalerInfo.WaiverPossible, GoalerInfo.ExcludeSalaryCap, GoalerInfo.ProSalaryinFarm, GoalerInfo.SalaryAverage, GoalerInfo.Salary1, GoalerInfo.Salary2, GoalerInfo.Salary3, GoalerInfo.Salary4, GoalerInfo.Salary5, GoalerInfo.Salary6, GoalerInfo.Salary7, GoalerInfo.Salary8, GoalerInfo.Salary9, GoalerInfo.Salary10, GoalerInfo.SalaryRemaining, GoalerInfo.SalaryAverageRemaining, GoalerInfo.SalaryCap, GoalerInfo.SalaryCapRemaining, GoalerInfo.Condition, GoalerInfo.ConditionDecimal, GoalerInfo.Status1, GoalerInfo.URLLink, GoalerInfo.NHLID, GoalerInfo.AvailableForTrade,'False' AS PosC, 'False' AS PosLW, 'False' AS PosRW, 'False' AS PosD, 'True' AS PosG, GoalerInfo.Retire as Retire, NextYearFreeAgent.PlayerType AS NextYearFreeAgentPlayerType FROM GoalerInfo LEFT JOIN NextYearFreeAgent ON GoalerInfo.Number = NextYearFreeAgent.Number WHERE GoalerInfo." . $TeamQuery . " AND Retire = " . $Retire . " AND GoalerInfo." . $TypeQuery . ")  AS MainTable WHERE MainTable.Contract = " . $FreeAgentYear; 
			}
		}elseif($Expansion == TRUE){
			if($Type == 0 AND $Team == -1){$Query = $Query . " WHERE MainTable.Team > 0";}
			$Query = $Query . " AND MainTable.PProtected = 'False'";
		}elseif($AvailableForTrade == TRUE){
			if($Type == 0 AND $Team == -1){$Query = $Query . " WHERE MainTable.Team > 0";}
			$Query = $Query . " AND MainTable.AvailableForTrade = 'True'";		
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
	}
} catch (Exception $e) {
STHSErrorPlayerInfo:
	$LeagueName = $DatabaseNotFound;
	$PlayerInfo = Null;
	$LeagueOutputOption = Null;
	$LeagueWebClient = Null;
	$FreeAgentYear = Null;
	echo "<title>" . $DatabaseNotFound . "</title>";
	$Title = $DatabaseNotFound;
}}?>
</head><body>
<?php include "Menu.php";?>
<script>
$(function() {
  $(".STHSPHPAllPlayerInformation_Table").tablesorter({
    widgets: ['columnSelector', 'stickyHeaders', 'filter', 'output'],
    widgetOptions : {
      columnSelector_container : $('#tablesorter_ColumnSelector'),
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
      filter_reset: '.tablesorter_Reset',	 
	  output_delivery: 'd',
	  output_saveFileName: 'STHSPlayerInformation.CSV'
    }
  });
  $('.download').click(function(){
      var $table = $('.STHSPHPAllPlayerInformation_Table'),
      wo = $table[0].config.widgetOptions;
      $table.trigger('outputTable');
      return false;
  });  
});
</script>

<div style="width:99%;margin:auto;">
<?php echo "<h1>" . $Title . "</h1>"; ?>
<div id="ReQueryDiv" style="display:none;">
<?php If($HistoryOutput == False){
	include "SearchPlayerInfo.php";
}else{
	include "SearchHistorySub.php";
	include "SearchHistoryPlayerInfo.php";
	$Team = (integer)-1;
}?>
</div>
<div class="tablesorter_ColumnSelectorWrapper">
	<button class="tablesorter_Output" id="ReQuery"><?php echo $SearchLang['ChangeSearch'];?></button>
    <input id="tablesorter_colSelect1" type="checkbox" class="hidden">
    <label class="tablesorter_ColumnSelectorButton" for="tablesorter_colSelect1"><?php echo $TableSorterLang['ShoworHideColumn'];?></label>
	<button class="tablesorter_Output download" type="button">Output</button>
    <div id="tablesorter_ColumnSelector" class="tablesorter_ColumnSelector"></div>
	<?php include "FilterTip.php";?>
</div>

<table class="tablesorter STHSPHPAllPlayerInformation_Table"><thead><tr>
<?php 
$TeamSub = $Team;
include "PlayersInfoSub.php";
?>
</tbody></table>
<?php 
if ($FreeAgentYear >= 0 AND isset($LeagueOutputOption)){
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
<?php 
if (isset($LeagueWebClient)){If ($LeagueWebClient['AllowPlayerEditionFromWebsite'] == "True"){echo "<br /><h1 class=\"STHSCenter\"><a href=\"EditPlayerInfo.php?Type=" .$Type ;If ($Team > 0){echo "&Team=".$Team;}If ($lang == "fr"){echo "&Lang=fr";} echo "\">" . $PlayersLang['ClicktoEdit'] . "</a></h1>";}}
include "Footer.php";?>
