<?php include "Header.php";
$Title = (string)"";
$Team = (integer)-1; /* -1 All Team */
$Search = (boolean)False;
$HistoryOutput = (boolean)False;
include "SearchPossibleOrderField.php";
If (file_exists($DatabaseFile) == false){
	Goto STHSErrorPlayerRoster;
}else{try{
	$ACSQuery = (boolean)FALSE;/* The SQL Query must be Ascending Order and not Descending */
	$Expansion = (boolean)FALSE; /* To show Expension Draft Avaiable Player - Not Apply if Free Agent Option or Unassigned option is also request */
	$AvailableForTrade = (boolean)FALSE; /* To show Available for Trade Only - Not Apply if Free Agent Option or Expansion option is also request */	
	$Retire = (string )"'False'"; /* To Show Retire Player or Not */
	$Injury = (boolean)FALSE; /* To show Available for Trade Only - Not Apply if Free Agent Option or Expansion option or Available for Trade is also request */
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
	if(isset($_GET['Order'])){$OrderByInput  = filter_var($_GET['Order'], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH || FILTER_FLAG_NO_ENCODE_QUOTES || FILTER_FLAG_STRIP_BACKTICK);} 
	if(isset($_GET['Team'])){$Team = filter_var($_GET['Team'], FILTER_SANITIZE_NUMBER_INT);}
    if(isset($_GET['Title'])){$TitleOverwrite  = filter_var($_GET['Title'], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH || FILTER_FLAG_NO_ENCODE_QUOTES || FILTER_FLAG_STRIP_BACKTICK);} 	
	if(isset($_GET['FreeAgent'])){$FreeAgentYear = filter_var($_GET['FreeAgent'], FILTER_SANITIZE_NUMBER_INT);If ($FreeAgentYear == null){$FreeAgentYear = (integer)0;}} 
	if(isset($_GET['Expansion'])){$Expansion = TRUE;} 
	if(isset($_GET['AvailableForTrade'])){$AvailableForTrade = TRUE;} 	
	if(isset($_GET['Injury'])){$Injury = TRUE;} 	
	if(isset($_GET['Retire'])){$Retire = "'True'";$FreeAgentYear=-1;}  /* Retire Overwrite Everything including FreeAgent */

	foreach ($PlayersRosterPossibleOrderField as $Value) {
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
			
			$Query = "Select Name, RFAAge, UFAAge from LeagueGeneral WHERE Year = " . $Year . " And Playoff = '" . $PlayoffString. "'";
			$LeagueGeneral = $db->querySingle($Query,true);	

			//Confirm Valid Data Found
			$CareerDBFormatV2CheckCheck = $db->querySingle("Select Count(Name) As CountName from LeagueGeneral  WHERE Year = " . $Year . " And Playoff = '" . $PlayoffString. "'",true);
			If ($CareerDBFormatV2CheckCheck['CountName'] == 1){$LeagueName = $LeagueGeneral['Name'];}else{$Year = (integer)0;$HistoryOutput = (boolean)False;Goto RegularSeason;}			
			
			$Query = "Select SalaryCapOption from LeagueFinance WHERE Year = " . $Year . " And Playoff = '" . $PlayoffString. "'";
			$LeagueFinance = $db->querySingle($Query,true);		
			$Query = "Select MergeRosterPlayerInfo, FreeAgentUseDateInsteadofDay, FreeAgentRealDate from LeagueOutputOption WHERE Year = " . $Year . " And Playoff = '" . $PlayoffString. "'";
			$LeagueOutputOption = $db->querySingle($Query,true);	
			
			$Query = "SELECT *, '0' As TeamThemeID FROM PlayerInfoHistory WHERE Retire = " . $Retire . " AND Year = " . $Year . " And Playoff = '" . $PlayoffString. "'";	
			
			If($AvailableForTrade == TRUE){$Title = $DynamicTitleLang['AvailableForTrade'];}
			If($Retire == "'True'"){$Title = $DynamicTitleLang['Retire'];}	
			
			/* Team or All */
			if($Team >= 0 And $Retire == "'False'"){
				if($Team > 0){
					$QueryTeam = "SELECT Name FROM TeamProInfoHistory WHERE Number = " . $Team . " AND Year = " . $Year . " And Playoff = '" . $PlayoffString. "'";			
					$TeamName = $db->querySingle($QueryTeam,true);	
					if (isset($TeamName['Name'])){$Title = $Title . $TeamName['Name'];}
				}else{
					$Title = $DynamicTitleLang['Unassigned'];
				}
				$Query = $Query . " AND PlayerInfoHistory.Team = " . $Team;
			}else{
				if($Type == 1 Or $Type == 2){$Query = $Query . " AND PlayerInfoHistory.Number > 0";}
			}
			
			If($MaximumResult == 0){$Title = $Title . $DynamicTitleLang['All'];}else{$Title = $Title . $DynamicTitleLang['Top'] .$MaximumResult;}
			
			/* Pro Only or Farm  */
			if($Type == 1){
				$Query = $Query . " AND PlayerInfoHistory.Status1 >= 2";
				$Title = $Title . $DynamicTitleLang['Pro'];
			}elseif($Type == 2){
				$Query = $Query . " AND PlayerInfoHistory.Status1 <= 1";
				$Title = $Title . $DynamicTitleLang['Farm'];
			}
			
			/* Option */
			If ($Retire == "'False'"){
				if($AvailableForTrade == TRUE){
					if($Type == 0 AND $Team == -1){$Query = $Query . " AND PlayerInfoHistory.Team > 0";}
					$Query = $Query . " AND PlayerInfoHistory.AvailableForTrade = 'True'";		
				}elseif($Injury == TRUE){
					if($Type == 0 AND $Team == -1){$Query = $Query . " AND PlayerInfoHistory.Team > 0";}
					$Query = $Query . " AND (PlayerInfoHistory.Condition < '95' OR PlayerInfoHistory.Suspension > '1')";		
				}
			}
			
			$Title = $Title . $DynamicTitleLang['PlayersRoster'] . " - " . $Year;
			If ($Playoff == True){$Title = $Title . $TopMenuLang['Playoff'];}
			
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
			
			echo "<title>" . $LeagueName . " - " . $Title . "</title>";			
			
		}else{
			Goto RegularSeason;
		}
	}else{
		/* Regular Season */
		RegularSeason:			
	
		$db = new SQLite3($DatabaseFile);
		$Query = "Select Name, RFAAge, UFAAge from LeagueGeneral";
		$LeagueGeneral = $db->querySingle($Query,true);		
		$LeagueName = $LeagueGeneral['Name'];
		$Query = "Select SalaryCapOption from LeagueFinance";
		$LeagueFinance = $db->querySingle($Query,true);		
		$Query = "Select MergeRosterPlayerInfo, FreeAgentUseDateInsteadofDay, FreeAgentRealDate from LeagueOutputOption";
		$LeagueOutputOption = $db->querySingle($Query,true);	
		$Query = "Select AllowFreeAgentSalaryRequestInSTHSClient from LeagueWebClient";
		$LeagueWebClient = $db->querySingle($Query,true);		
		
		If ($FreeAgentYear == 1){
			$Query = "SELECT PlayerInfo.*, NextYearFreeAgent.PlayerType AS NextYearFreeAgentPlayerType FROM PlayerInfo LEFT JOIN NextYearFreeAgent ON PlayerInfo.Number = NextYearFreeAgent.Number WHERE Retire = 'False'";
		}else{
			$Query = "SELECT * FROM PlayerInfo WHERE Retire = " . $Retire;
		}
			
		If($Expansion == TRUE){$Title = $DynamicTitleLang['ExpansionDraft'];}
		If($AvailableForTrade == TRUE){$Title = $DynamicTitleLang['AvailableForTrade'];}
		If($Retire == "'True'"){$Title = $DynamicTitleLang['Retire'];}	
		
		/* Team or All */
		if($Team >= 0 And $Retire == "'False'"){
			if($Team > 0){
				$QueryTeam = "SELECT Name FROM TeamProInfo WHERE Number = " . $Team;
				$TeamName = $db->querySingle($QueryTeam,true);
				if (isset($TeamName['Name'])){$Title = $Title . $TeamName['Name'];}
			}else{
				$Title = $DynamicTitleLang['Unassigned'];
			}
			$Query = $Query . " AND PlayerInfo.Team = " . $Team;
		}else{
			if($Type == 1 Or $Type == 2){$Query = $Query . " AND PlayerInfo.Number > 0";}
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
		
		/* Option */
		If ($Retire == "'False'"){
			If ($FreeAgentYear >= 0){
				if($Type == 0 AND $Team == -1){$Query = $Query . " AND PlayerInfo.Team > 0";}
				$Query = $Query . " AND PlayerInfo.Contract = " . $FreeAgentYear; /* Free Agent Query */ 
				If ($FreeAgentYear == 0){$Title = $Title . $DynamicTitleLang['ThisYearFreeAgents'];}elseIf ($FreeAgentYear == 1){$Title = $Title . $DynamicTitleLang['NextYearFreeAgents'];}else{$Title = $Title . " " . $FreeAgentYear . $DynamicTitleLang['YearsFreeAgents'];}
			}elseif($Expansion == TRUE){
				$Query = $Query . " AND PlayerInfo.PProtected = 'False'";
			}elseif($AvailableForTrade == TRUE){
				if($Type == 0 AND $Team == -1){$Query = $Query . " AND PlayerInfo.Team > 0";}
				$Query = $Query . " AND PlayerInfo.AvailableForTrade = 'True'";		
			}elseif($Injury == TRUE){
				if($Type == 0 AND $Team == -1){$Query = $Query . " AND PlayerInfo.Team > 0";}
				$Query = $Query . " AND (PlayerInfo.Condition < '95' OR PlayerInfo.Suspension > '1')";		
			}
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
	}
} catch (Exception $e) {
STHSErrorPlayerRoster:
	$LeagueName = $DatabaseNotFound;
	$PlayerRoster = Null;
	$LeagueOutputOption = Null;
	$FreeAgentYear = Null;
	echo "<title>" . $DatabaseNotFound . "</title>";
	$Title = $DatabaseNotFound;
}}?>
</head><body>
<?php include "Menu.php";?>
<script>
$(function() {
  $(".STHSPHPAllPlayerRoster_Table").tablesorter({
    widgets: ['columnSelector', 'stickyHeaders', 'filter', 'output'],
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
      filter_reset: '.tablesorter_Reset',	 
	  output_delivery: 'd',
	  output_saveFileName: 'STHSPlayerRoster.CSV'
    }
  });
  $('.download').click(function(){
      var $table = $('.STHSPHPAllPlayerRoster_Table'),
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
	include "SearchPlayersRoster.php";
}else{
	include "SearchHistorySub.php";
	include "SearchHistoryPlayersRoster.php";
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

<table class="tablesorter STHSPHPAllPlayerRoster_Table"><thead><tr>
<th data-priority="critical" title="Player Name" class="STHSW140Min"><?php echo $PlayersLang['PlayerName'];?></th>
<?php if($Team >= 0){echo "<th class=\"columnSelector-false STHSW140\" data-priority=\"6\" title=\"Team Name\">" . $PlayersLang['TeamName'] . "</th>";}else{echo "<th data-priority=\"2\" title=\"Team Name\" class=\"STHSW140Min\">" . $PlayersLang['TeamName'] ."</th>";}?>
<th data-priority="4" title="Center" class="STHSW10">C</th>
<th data-priority="4" title="Left Wing" class="STHSW10">L</th>
<th data-priority="4" title="Right Wing" class="STHSW10">R</th>
<th data-priority="4" title="Defenseman" class="STHSW10">D</th>
<th <?php if($Team >= 0){echo " data-priority=\"1\" class=\"STHSW25\"";}else{echo "data-priority=\"5\" class=\"columnSelector-false STHSW25\"";}?> title="Condition">CON</th>
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
<th <?php if($FreeAgentYear == -1){echo " data-priority=\"3\" class=\"STHSW25\"";}else{echo "data-priority=\"5\" class=\"columnSelector-false STHSW25\"";}?> title="Morale">MO</th>
<th data-priority="critical" title="Overall" class="STHSW25">OV</th>
<?php if ($PlayerRoster != Null){
	if ($FreeAgentYear == -1){
		echo "<th data-priority=\"5\" class=\"columnSelector-false STHSW25\" title=\"Trade Available\">TA</th>";
	}else{
		echo "<th data-priority=\"4\" class=\"STHSW45\" title=\"Status\">" . $PlayersLang['Status'] . "</th>";
		if ($LeagueWebClient['AllowFreeAgentSalaryRequestInSTHSClient'] == "True"){echo "<th data-priority=\"4\" class=\"STHSW75\" title=\"Free Agent Salary Request\">" . $PlayersLang['SalaryRequest'] . "</th>";}
	}
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
}?>
<th data-priority="3" title="Hyperlink" class="STHSW100"><?php echo $PlayersLang['Link'];?></th>
</tr></thead><tbody>
<?php
if (empty($PlayerRoster) == false){while ($Row = $PlayerRoster ->fetchArray()) {
	$strTemp = (string)$Row['Name'];
	If ($Row['Rookie']== "True"){ $strTemp = $strTemp . " (R)";}
	echo "<tr><td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $strTemp . "</a></td>";
	if ($Row['Retire']== "True"){
		echo "<td>" . $PlayersLang['Retire'] . "</td>";	
	}else{
		echo "<td>";
		If ($Row['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPPlayersRosterTeamImage\" />";}			
		if ($FreeAgentYear == -1){
			echo $Row['TeamName'] . "</td>";	
		}else{
			echo $Row['ProTeamName'] . "</td>";	
		}
	}
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
		if ($LeagueWebClient['AllowFreeAgentSalaryRequestInSTHSClient'] == "True"){echo "<td>" . number_format($Row['FreeAgentSalaryRequest'],0) . "$ / " . $Row['FreeAgentContratRequest'] . "</td>";}		
	}
	if ($LeagueOutputOption['MergeRosterPlayerInfo'] == "True"){ 	
		echo "<td>" . $Row['StarPower'] . "</td>";
		echo "<td>" . $Row['Age'] . "</td>";
		echo "<td>" . $Row['Contract'] . "</td>";
		if ($LeagueFinance['SalaryCapOption'] == 4 OR $LeagueFinance['SalaryCapOption'] == 5 OR $LeagueFinance['SalaryCapOption'] == 6){
			if ($FreeAgentYear == 0){
				echo "<td>" . number_format($Row['LastYearSalaryAverage'],0) . "$</td>";
			}else{
				echo "<td>" . number_format($Row['SalaryAverage'],0) . "$</td>";
			}
		}else{
			if ($FreeAgentYear == 0){
				echo "<td>" . number_format($Row['LastYearSalary'],0) . "$</td>";
			}else{
				echo "<td>" . number_format($Row['Salary1'],0) . "$</td>";
			}
		}		
	}
	echo "<td>";
	if ($Row['URLLink'] != ""){echo "<a href=" . $Row['URLLink'] . " target=\"new\">" . $PlayersLang['Link'] . "</a>";}
	if ($Row['URLLink'] != "" AND $Row['NHLID'] != ""){echo " / ";}
	if ($Row['NHLID'] != ""){echo "<a href=\"https://www.nhl.com/player/" . $Row['NHLID'] . "\" target=\"new\">" . $PlayersLang['NHLLink'] . "</a>";}
	echo "</td>";
	echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
}}
?>
</tbody></table></div>
<?php 
if ($FreeAgentYear >= 0 AND $PlayerRoster != Null){
	echo "<em>"  . $DynamicTitleLang['FreeAgentStatus'];
	if ($LeagueOutputOption['FreeAgentUseDateInsteadofDay'] == "True" AND $FreeAgentYear == 1){
		echo date_Format(date_create($LeagueOutputOption['FreeAgentRealDate']),"Y-m-d") . "</em>";
	}else{
		echo date("Y-m-d") . "</em>";
	}
}
?>
<br />

<?php include "Footer.php";?>
