<?php include "Header.php";
$Team = (integer)0;
If ($lang == "fr"){include 'LanguageFR-Stat.php';}else{include 'LanguageEN-Stat.php';}
If (file_exists($DatabaseFile) == false){
	Goto STHSErrorTeamSalaryCapDetail;
}else{try{
	$TypeQuery = "Number > 0";
	$TeamQuery = "Team >= 0";
	$LeagueName = (string)"";
	$SelectPlayers = Null;
	$TeamFinance = Null;
	$SubmitPlayer = (boolean)false;
	If($CookieTeamNumber > 0 AND $CookieTeamNumber <= 100){$Team = $CookieTeamNumber;}
	if(isset($_GET['Team'])){$Team = filter_var($_GET['Team'], FILTER_SANITIZE_NUMBER_INT);} 
	if(isset($_POST['SelectPlayers'])){$SelectPlayers = $_POST['SelectPlayers'];$SubmitPlayer=True;}

	$db = new SQLite3($DatabaseFile);
	$Query = "Select AllowPlayerEditionFromWebsite from LeagueWebClient";
	$LeagueWebClient = $db->querySingle($Query,true);		
	$Query = "Select SalaryCapOption, ProSalaryCapValue, BonusIncludeSalaryCap from LeagueFinance";
	$LeagueFinance = $db->querySingle($Query,true);	
	$Query = "Select FreeAgentUseDateInsteadofDay, FreeAgentRealDate from LeagueOutputOption";
	$LeagueOutputOption = $db->querySingle($Query,true);		
	$Query = "Select Name, RFAAge, UFAAge, LeagueYearOutput from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	$LeagueYear = (int)$LeagueGeneral['LeagueYearOutput'];
	$SalaryCap = (int)$LeagueFinance['ProSalaryCapValue'];

	/* Team or All */
	if($Team > 0 and $Team < 100 And $LeagueFinance['SalaryCapOption'] > 0){
		$QueryTeam = "SELECT Name FROM TeamProInfo WHERE Number = " . $Team;
		$TeamName = $db->querySingle($QueryTeam,true);	
		$Title = $Title . $TeamName['Name'] . " - " . $DynamicTitleLang['TeamContractsOverview'];	
		$TeamQuery = "Team = " . $Team;
		$Query = "Select Number, Name, CurrentBankAccount, SpecialSalaryCapY1,SpecialSalaryCapY2,SpecialSalaryCapY3,SpecialSalaryCapY4,SpecialSalaryCapY5 from TeamProFinance WHERE Number = " . $Team;
		$TeamFinance = $db->querySingle($Query,true);
		If ($LeagueFinance['SalaryCapOption'] == 2 OR $LeagueFinance['SalaryCapOption'] == 5){$SalaryCap = $SalaryCap + $TeamFinance['CurrentBankAccount'];}
		
	}elseif($LeagueFinance['SalaryCapOption'] ==0){
		$Title = $PlayersLang['SalaryCapNotActivate'];
		$TeamQuery = "Team < 0";
		$Team = 0;
		echo "<style>#MainDiv{display:none;}</style>";
	}else{
		$Title = $TeamLang['IncorrectTeam'];
		$TeamQuery = "Team < 0";
		$Team = 0;
	}
			
	If ($SubmitPlayer==False OR empty($SelectPlayers) == True){
		$Query = "SELECT MainTable.* FROM (SELECT PlayerInfo.Number, PlayerInfo.Name, PlayerInfo.Team, PlayerInfo.TeamName, PlayerInfo.ProTeamName, PlayerInfo.Age, PlayerInfo.AgeDate, PlayerInfo.Contract, PlayerInfo.Rookie, PlayerInfo.NoTrade, PlayerInfo.CanPlayPro, PlayerInfo.CanPlayFarm, PlayerInfo.ForceWaiver, PlayerInfo.WaiverPossible, PlayerInfo.ExcludeSalaryCap, PlayerInfo.ProSalaryinFarm, PlayerInfo.SalaryAverage, PlayerInfo.Salary1, PlayerInfo.Salary2, PlayerInfo.Salary3, PlayerInfo.Salary4, PlayerInfo.Salary5, PlayerInfo.Salary6, PlayerInfo.Salary7, PlayerInfo.Salary8, PlayerInfo.Salary9, PlayerInfo.Salary10, PlayerInfo.SalaryRemaining, PlayerInfo.SalaryAverageRemaining, PlayerInfo.SalaryCap, PlayerInfo.SalaryCapRemaining, PlayerInfo.Condition, PlayerInfo.Status1, PlayerInfo.URLLink, PlayerInfo.NHLID, PlayerInfo.PProtected,PlayerInfo.PosC, PlayerInfo.PosLW, PlayerInfo.PosRW, PlayerInfo.PosD, 'False' AS PosG, PlayerInfo.Retire as Retire FROM PlayerInfo WHERE " . $TeamQuery . " AND Retire = 'False' AND Status1 >= 2 UNION ALL SELECT GoalerInfo.Number +10000, GoalerInfo.Name, GoalerInfo.Team, GoalerInfo.TeamName, GoalerInfo.ProTeamName, GoalerInfo.Age, GoalerInfo.AgeDate, GoalerInfo.Contract, GoalerInfo.Rookie, GoalerInfo.NoTrade, GoalerInfo.CanPlayPro, GoalerInfo.CanPlayFarm, GoalerInfo.ForceWaiver, GoalerInfo.WaiverPossible, GoalerInfo.ExcludeSalaryCap, GoalerInfo.ProSalaryinFarm, GoalerInfo.SalaryAverage, GoalerInfo.Salary1, GoalerInfo.Salary2, GoalerInfo.Salary3, GoalerInfo.Salary4, GoalerInfo.Salary5, GoalerInfo.Salary6, GoalerInfo.Salary7, GoalerInfo.Salary8, GoalerInfo.Salary9, GoalerInfo.Salary10, GoalerInfo.SalaryRemaining, GoalerInfo.SalaryAverageRemaining, GoalerInfo.SalaryCap, GoalerInfo.SalaryCapRemaining, GoalerInfo.Condition, GoalerInfo.Status1, GoalerInfo.URLLink, GoalerInfo.NHLID, GoalerInfo.PProtected,'False' AS PosC, 'False' AS PosLW, 'False' AS PosRW, 'False' AS PosD, 'True' AS PosG, GoalerInfo.Retire as Retire FROM GoalerInfo WHERE " . $TeamQuery . " AND Retire = 'False' AND Status1 >= 2) AS MainTable ORDER BY PosG ASC, PosD ASC, Name ASC";
	}else{
		$Query = "SELECT MainTable.* FROM (SELECT PlayerInfo.Number, PlayerInfo.Name, PlayerInfo.Team, PlayerInfo.TeamName, PlayerInfo.ProTeamName, PlayerInfo.Age, PlayerInfo.AgeDate, PlayerInfo.Contract, PlayerInfo.Rookie, PlayerInfo.NoTrade, PlayerInfo.CanPlayPro, PlayerInfo.CanPlayFarm, PlayerInfo.ForceWaiver, PlayerInfo.WaiverPossible, PlayerInfo.ExcludeSalaryCap, PlayerInfo.ProSalaryinFarm, PlayerInfo.SalaryAverage, PlayerInfo.Salary1, PlayerInfo.Salary2, PlayerInfo.Salary3, PlayerInfo.Salary4, PlayerInfo.Salary5, PlayerInfo.Salary6, PlayerInfo.Salary7, PlayerInfo.Salary8, PlayerInfo.Salary9, PlayerInfo.Salary10, PlayerInfo.SalaryRemaining, PlayerInfo.SalaryAverageRemaining, PlayerInfo.SalaryCap, PlayerInfo.SalaryCapRemaining, PlayerInfo.Condition, PlayerInfo.Status1, PlayerInfo.URLLink, PlayerInfo.NHLID, PlayerInfo.PProtected,PlayerInfo.PosC, PlayerInfo.PosLW, PlayerInfo.PosRW, PlayerInfo.PosD, 'False' AS PosG, PlayerInfo.Retire as Retire FROM PlayerInfo WHERE Retire = 'False' AND Status1 >= 2 UNION ALL SELECT GoalerInfo.Number + 10000, GoalerInfo.Name, GoalerInfo.Team, GoalerInfo.TeamName, GoalerInfo.ProTeamName, GoalerInfo.Age, GoalerInfo.AgeDate, GoalerInfo.Contract, GoalerInfo.Rookie, GoalerInfo.NoTrade, GoalerInfo.CanPlayPro, GoalerInfo.CanPlayFarm, GoalerInfo.ForceWaiver, GoalerInfo.WaiverPossible, GoalerInfo.ExcludeSalaryCap, GoalerInfo.ProSalaryinFarm, GoalerInfo.SalaryAverage, GoalerInfo.Salary1, GoalerInfo.Salary2, GoalerInfo.Salary3, GoalerInfo.Salary4, GoalerInfo.Salary5, GoalerInfo.Salary6, GoalerInfo.Salary7, GoalerInfo.Salary8, GoalerInfo.Salary9, GoalerInfo.Salary10, GoalerInfo.SalaryRemaining, GoalerInfo.SalaryAverageRemaining, GoalerInfo.SalaryCap, GoalerInfo.SalaryCapRemaining, GoalerInfo.Condition, GoalerInfo.Status1, GoalerInfo.URLLink, GoalerInfo.NHLID, GoalerInfo.PProtected,'False' AS PosC, 'False' AS PosLW, 'False' AS PosRW, 'False' AS PosD, 'True' AS PosG, GoalerInfo.Retire as Retire FROM GoalerInfo WHERE Retire = 'False' AND Status1 >= 2) AS MainTable WHERE ";
		foreach ($SelectPlayers as $values){
			$Query = $Query . "Number = " . $values . " OR ";
		}
		$Query = $Query . " Number = 20000 ORDER BY PosG ASC, PosD ASC, Name ASC";
	}
	$PlayerSalaryCap = $db->query($Query);
	
	$Query = "SELECT MainTable.* FROM (SELECT PlayerInfo.Number, PlayerInfo.Name, PlayerInfo.Team, PlayerInfo.ProTeamName, PlayerInfo.Status1, PlayerInfo.Retire as Retire FROM PlayerInfo WHERE Retire = 'False' AND Status1 >= 2 AND Contract > 0 AND Team > 0 UNION ALL SELECT GoalerInfo.Number + 10000, GoalerInfo.Name, GoalerInfo.Team, GoalerInfo.ProTeamName, GoalerInfo.Status1, GoalerInfo.Retire as Retire FROM GoalerInfo WHERE Retire = 'False' AND Status1 >= 2 AND Contract > 0 and Team > 0) AS MainTable ORDER BY Name ASC";
	$AllPlayers = $db->query($Query);
			
	echo "<title>" . $LeagueName . " - " . $Title . "</title>";
		
} catch (Exception $e) {
STHSErrorTeamSalaryCapDetail:
	$LeagueName = $DatabaseNotFound;
	$PlayerSalaryCap = Null;
	$AllPlayers = Null;
	$SelectPlayers = Null;
	$TeamFinance = Null;
	$LeagueYear = (int)0;
	$SalaryCap = (int)0;
	echo "<title>" . $DatabaseNotFound . "</title>";
	$Title = $DatabaseNotFound;
}}?>
</head><body>
<?php include "Menu.php";?>
<script>
$(function() {
  $(".STHSPHPTeamSalaryCapDetail_Table").tablesorter({
    widgets: ['columnSelector', 'stickyHeaders', 'filter', 'output'],
    widgetOptions : {
      columnSelector_container : $('#tablesorter_ColumnSelector'),
      columnSelector_layout : '<label><input type="checkbox">{name}</label>',
      columnSelector_name  : 'title',
      columnSelector_mediaquery: true,
      columnSelector_mediaqueryName: 'Automatic',
      columnSelector_mediaqueryState: true,
      columnSelector_mediaqueryHidden: true,
      columnSelector_breakpoints : [ '5em', '20em', '60em', '70em', '80em', '90em' ],
	  filter_columnFilters: true,
      filter_placeholder: { search : '<?php echo $TableSorterLang['Search'];?>' },
	  filter_searchDelay : 1000,	  
      filter_reset: '.tablesorter_Reset',	 
	  output_delivery: 'd',
	  output_saveFileName: 'STHSTeamSalaryCap.CSV'
    }
  });
  $('.download').click(function(){
      var $table = $('.STHSPHPTeamSalaryCapDetail_Table'),
      wo = $table[0].config.widgetOptions;
      $table.trigger('outputTable');
      return false;
  });  
});
</script>

<div style="width:98%;margin:auto;">
<?php echo "<h1>" . $Title . "</h1>"; ?>
</div><div id="MainDiv" style="width:98%;margin:auto;">
<div id="ReQueryDiv" style="display:<?php if($Team > 0){echo "none";}?>">
<form action="TeamSalaryCapDetail.php" method="get">
<table class="STHSTable">
<tr>
	<td class="STHSW200 STHSPHPSearch_Field"><strong><?php echo $SearchLang['Team'];?></strong></td><td class="STHSW250">
	<select name="Team" class="STHSSelect STHSW250">
	<?php
	$Query = "SELECT Number, Name FROM TeamProInfo Order By Name";
	If (isset($db)){$TeamNameSearch = $db->query($Query);}
	if (empty($TeamNameSearch) == false){while ($Row = $TeamNameSearch ->fetchArray()) {
		echo "<option value=\"" . $Row['Number'] . "\""; 
		if ($Row['Number'] == $Team){echo " selected=\"selected\"";}
		echo ">" . $Row['Name'] . "</option>"; 
	}}
	?>
	</select></td>
</tr>
<tr>
	<td colspan="2" class="STHSCenter"><input type="submit" class="SubmitButton" style="margin-top:10px;" value="<?php echo $SearchLang['Submit'];?>"></td>
</tr>
</table></form>
</div>
<div class="tablesorter_ColumnSelectorWrapper">
	<button class="tablesorter_Output" id="ReQuery"><?php echo $SearchLang['ChangeSearch'];?></button>
    <input id="tablesorter_colSelect1" type="checkbox" class="hidden">
    <label class="tablesorter_ColumnSelectorButton" for="tablesorter_colSelect1"><?php echo $TableSorterLang['ShoworHideColumn'];?></label>
	<button class="tablesorter_Output download" type="button">Output</button>
    <div id="tablesorter_ColumnSelector" class="tablesorter_ColumnSelector"></div>
	<?php include "FilterTip.php";?>
</div>

<table class="tablesorter STHSPHPTeamSalaryCapDetail_Table"><thead><tr>
<th data-priority="critical" title="Player Name" class="STHSW140Min"><?php echo $PlayersLang['PlayerName'];?></th>
<th data-priority="2" title="Position" class="STHSW45">POS</th>
<th data-priority="1" title="Age" class="STHSW25"><?php echo $PlayersLang['Age'];?></th>
<th data-priority="2" title="Birthday" class="STHSW45"><?php echo $PlayersLang['Birthday'];?></th>
<th data-priority="2" title="Terms" class="STHSW35"><?php echo $PlayersLang['Terms'];?></th>
<th data-priority="1" title="Contract Duration" class="STHSW25"><?php echo $PlayersLang['Contract'];?></th>
<th data-priority="2" title="Cap %" class="STHSW25">Cap %</th>
<?php
echo "<th data-priority=\"2\" title=\"Year " . $LeagueYear . "\" class=\"STHSW75\">" . $SearchLang['Year'] . " " . $LeagueYear . "</th>";
echo "<th data-priority=\"3\" title=\"Year " . ($LeagueYear + 1) . "\" class=\"STHSW75\">" . $SearchLang['Year'] . " " . ($LeagueYear + 1) . "</th>";
echo "<th data-priority=\"4\" title=\"Year " . ($LeagueYear + 2) . "\" class=\"STHSW75\">" . $SearchLang['Year'] . " " . ($LeagueYear + 2) . "</th>";
echo "<th data-priority=\"5\" title=\"Year " . ($LeagueYear + 3) . "\" class=\"STHSW75\">" . $SearchLang['Year'] . " " . ($LeagueYear + 3) . "</th>";
echo "<th data-priority=\"6\" title=\"Year " . ($LeagueYear + 4) . "\" class=\"STHSW75\">" . $SearchLang['Year'] . " " . ($LeagueYear + 4) . "</th>";
?>
</tr></thead>
<?php
echo "<tbody class=\"tablesorter-no-sort\"><tr><th colspan=\"12\">" . $TeamLang['Forward'] . "</th></tr></tbody><tbody>";
$FoundD=(boolean)False;$FoundG=(boolean)False;
$AverageAge=(integer)0;$AverageCap1=(integer)0;$AverageCap2=(integer)0;$AverageCap3=(integer)0;$AverageCap4=(integer)0;$AverageCap5=(integer)0;$AverageCount=(integer)0;
$AverageTotalCap1=(integer)0;$AverageTotalCap2=(integer)0;$AverageTotalCap3=(integer)0;$AverageTotalCap4=(integer)0;$AverageTotalCap5=(integer)0;$AverageTotalCount=(integer)0;
if (empty($PlayerSalaryCap) == false){while ($Row = $PlayerSalaryCap ->fetchArray()) {
	if ($Row['PosD']== "True" And $FoundD == False){
		If ($AverageCount > 0){
			echo "</tbody>\n<tbody class=\"tablesorter-no-sort STHSPHPTeamSalaryCapDetail_Table_AverageTH\"><tr><td colspan=\"2\">" . $TeamLang['Average'] . " (" . $AverageCount . ")"  . "</td>";
			echo "<td>" . number_format($AverageAge / $AverageCount,2) . "</td><td colspan=\"3\"></td>";
			If ($SalaryCap > 0){echo "<td>" . number_Format(($AverageCap1 / $SalaryCap)*100,2) . "%</td>";}else{echo "<td>N/A</td>";}
			echo "<td>" . number_format($AverageCap1,0) . "$</td><td>" . number_format($AverageCap2,0) . "$</td><td>" . number_format($AverageCap3,0) . "$</td><td>" . number_format($AverageCap4,0) . "$</td><td>" . number_format($AverageCap5,0) . "$</td></tr>";		
		}
		echo "</tbody>\n<tbody></tbody><tbody class=\"tablesorter-no-sort\"><tr><th colspan=\"12\">" . $TeamLang['Defenseman'] . "</th></tr></tbody><tbody>";		
		$AverageTotalCap1=$AverageTotalCap1+$AverageCap1;$AverageTotalCap2=$AverageTotalCap2+$AverageCap2;$AverageTotalCap3=$AverageTotalCap3+$AverageCap3;$AverageTotalCap4=$AverageTotalCap4+$AverageCap4;$AverageTotalCap5=$AverageTotalCap5+$AverageCap5;$AverageTotalCount=$AverageTotalCount+$AverageCount;
		$AverageAge=(integer)0;$AverageCap1=(integer)0;$AverageCap2=(integer)0;$AverageCap3=(integer)0;$AverageCap4=(integer)0;$AverageCap5=(integer)0;$AverageCount=(integer)0;$FoundD = True;
	}
	if ($Row['PosG']== "True" And $FoundG == False){
		If ($AverageCount > 0){
			echo "</tbody>\n<tbody class=\"tablesorter-no-sort STHSPHPTeamSalaryCapDetail_Table_AverageTH\"><tr><td colspan=\"2\">" . $TeamLang['Average'] . " (" . $AverageCount . ")"  . "</td>";
			echo "<td>" . number_format($AverageAge / $AverageCount,2) . "</td><td colspan=\"3\"></td>";
			If ($SalaryCap > 0){echo "<td>" . number_Format(($AverageCap1 / $SalaryCap)*100,2) . "%</td>";}else{echo "<td>N/A</td>";}
			echo "<td>" . number_format($AverageCap1,0) . "$</td><td>" . number_format($AverageCap2,0) . "$</td><td>" . number_format($AverageCap3,0) . "$</td><td>" . number_format($AverageCap4,0) . "$</td><td>" . number_format($AverageCap5,0) . "$</td></tr>";		
		}	
		echo "</tbody>\n<tbody class=\"tablesorter-no-sort\"><tr><th colspan=\"12\">" . $TeamLang['Goalies'] . "</th></tr></tbody><tbody>";
		$AverageTotalCap1=$AverageTotalCap1+$AverageCap1;$AverageTotalCap2=$AverageTotalCap2+$AverageCap2;$AverageTotalCap3=$AverageTotalCap3+$AverageCap3;$AverageTotalCap4=$AverageTotalCap4+$AverageCap4;$AverageTotalCap5=$AverageTotalCap5+$AverageCap5;$AverageTotalCount=$AverageTotalCount+$AverageCount;
		$AverageAge=(integer)0;$AverageCap1=(integer)0;$AverageCap2=(integer)0;$AverageCap3=(integer)0;$AverageCap4=(integer)0;$AverageCap5=(integer)0;$AverageCount=(integer)0;$FoundG = True;
	}
	$AverageCount=$AverageCount+1;
	echo "<tr><td>";if ($Row['PosG']== "True"){echo "<a href=\"GoalieReport.php?Goalie=" . ($Row['Number'] - 10000) . "\">";}else{echo "<a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">";} echo $Row['Name'] . "</a></td>";	
	echo "<td>" .$Position = (string)"";
	if ($Row['PosC']== "True"){if ($Position == ""){$Position = "C";}else{$Position = $Position . "/C";}}
	if ($Row['PosLW']== "True"){if ($Position == ""){$Position = "LW";}else{$Position = $Position . "/LW";}}
	if ($Row['PosRW']== "True"){if ($Position == ""){$Position = "RW";}else{$Position = $Position . "/RW";}}
	if ($Row['PosD']== "True"){if ($Position == ""){$Position = "D";}else{$Position = $Position . "/D";}}
	if ($Row['PosG']== "True"){if ($Position == ""){$Position = "G";}}
	echo $Position . "</td>";	
	echo "<td>" . $Row['Age'] . "</td>";$AverageAge=$AverageAge+$Row['Age'];
	echo "<td>" . $Row['AgeDate'] . "</td>";
	echo "<td>";
	If ($Row['ForceWaiver'] == "True"){echo "FV ";}
	If ($Row['NoTrade'] == "True"){echo "NT ";}
	If ($Row['Condition'] < '95'){echo "IN ";}
	If ($Row['CanPlayPro']== "True" AND $Row['CanPlayFarm']== "True"){echo "TW ";}		
	echo "</td>";	
	echo "<td>" . $Row['Contract'] . "</td>";
	If ($SalaryCap > 0){echo "<td>" . number_Format(($Row['SalaryCap'] / $SalaryCap)*100,2) . "%</td>";}else{echo "<td>N/A</td>";}
	for ($i=1;$i <=5;$i = $i + 1){
		If ($Row['Contract'] >= $i){
			If ($i == 1){
				echo "<td>" .  number_format($Row['SalaryCap'],0) . "$</td>";$AverageCap1=$AverageCap1+$Row['SalaryCap'];
			}else{
				If ($LeagueFinance['SalaryCapOption'] >=1 and $LeagueFinance['SalaryCapOption'] <=3 ){
					If ($i == 2){echo "<td>" .  number_format($Row['Salary2'],0) . "$</td>";$AverageCap2=$AverageCap2+$Row['Salary2'];}
					If ($i == 3){echo "<td>" .  number_format($Row['Salary3'],0) . "$</td>";$AverageCap3=$AverageCap3+$Row['Salary3'];}
					If ($i == 4){echo "<td>" .  number_format($Row['Salary4'],0) . "$</td>";$AverageCap4=$AverageCap4+$Row['Salary4'];}
					If ($i == 5){echo "<td>" .  number_format($Row['Salary5'],0) . "$</td>";$AverageCap5=$AverageCap5+$Row['Salary5'];}
				}elseif($LeagueFinance['SalaryCapOption'] >= 4 and $LeagueFinance['SalaryCapOption'] <= 6){
					echo "<td>" .  number_format($Row['SalaryCap'],0) . "$</td>";
					If ($i == 2){$AverageCap2=$AverageCap2+$Row['SalaryAverage'];}
					If ($i == 3){$AverageCap3=$AverageCap3+$Row['SalaryAverage'];}
					If ($i == 4){$AverageCap4=$AverageCap4+$Row['SalaryAverage'];}
					If ($i == 5){$AverageCap5=$AverageCap5+$Row['SalaryAverage'];}
				}else{
					echo "<td></td>";
				}
			}
		}elseif ($Row['Contract'] + 1 == $i){
			if ($LeagueOutputOption['FreeAgentUseDateInsteadofDay'] == "True"){
				$age = date_diff(date_create($Row['AgeDate']), date_create($LeagueOutputOption['FreeAgentRealDate']))->y;
			}else{
				$age = $Row['Age'];
			}
			if ($age + $i > $LeagueGeneral['UFAAge']){
				echo "<td class=\"STHSTeamSalaryCapDetail_UFA\">UFA [Age: " . ($age + $i -1) . "]</td>";
			}elseif($age  + $i > $LeagueGeneral['RFAAge']){
				echo "<td class=\"STHSTeamSalaryCapDetail_RFA\">RFA [Age: " . ($age + $i -1) . "]</td>";
			}
		}else{
			echo "<td></td>";
		}
	}
	echo "</tr>\n";
}}
If ($AverageCount > 0){
	echo "</tbody>\n<tbody class=\"tablesorter-no-sort STHSPHPTeamSalaryCapDetail_Table_AverageTH\"><tr><td colspan=\"2\">" . $TeamLang['Average'] . " (" . $AverageCount . ")"  . "</td>";
	echo "<td>" . number_format($AverageAge / $AverageCount,2) . "</td><td colspan=\"3\"></td>";
	If ($SalaryCap > 0){echo "<td>" . number_Format(($AverageCap1 / $SalaryCap)*100,2) . "%</td>";}else{echo "<td>N/A</td>";}
	echo "<td>" . number_format($AverageCap1,0) . "$</td><td>" . number_format($AverageCap2,0) . "$</td><td>" . number_format($AverageCap3,0) . "$</td><td>" . number_format($AverageCap4,0) . "$</td><td>" . number_format($AverageCap5,0) . "$</td></tr>";		
	$AverageTotalCap1=$AverageTotalCap1+$AverageCap1;$AverageTotalCap2=$AverageTotalCap2+$AverageCap2;$AverageTotalCap3=$AverageTotalCap3+$AverageCap3;$AverageTotalCap4=$AverageTotalCap4+$AverageCap4;$AverageTotalCap5=$AverageTotalCap5+$AverageCap5;$AverageTotalCount=$AverageTotalCount+$AverageCount;
}
echo "</tbody>\n";
If ($LeagueFinance['BonusIncludeSalaryCap'] == "True"){
	// Add Special in Salary Cap
	echo "<tbody class=\"tablesorter-no-sort\"><tr><td colspan=\"7\">" . $TeamLang['SpecialSalaryCapValue'] . "</td><td>" . number_format($TeamFinance['SpecialSalaryCapY1'],0) . "$</td><td>" . number_format($TeamFinance['SpecialSalaryCapY2'],0) . "$</td><td>" . number_format($TeamFinance['SpecialSalaryCapY3'],0) . "$</td><td>" . number_format($TeamFinance['SpecialSalaryCapY4'],0) . "$</td><td>" . number_format($TeamFinance['SpecialSalaryCapY5'],0) . "$</td></tbody>\n";
	$AverageTotalCap1=$AverageTotalCap1+$TeamFinance['SpecialSalaryCapY1'];$AverageTotalCap2=$AverageTotalCap2+$TeamFinance['SpecialSalaryCapY2'];$AverageTotalCap3=$AverageTotalCap3+$TeamFinance['SpecialSalaryCapY3'];$AverageTotalCap4=$AverageTotalCap4+$TeamFinance['SpecialSalaryCapY4'];$AverageTotalCap5=$AverageTotalCap5+$TeamFinance['SpecialSalaryCapY5'];
}
echo "<tbody class=\"tablesorter-no-sort\"><tr><td colspan=\"6\">" . $TeamLang['Total'] . " (" . $AverageTotalCount . ")"  . "</td>";
If ($SalaryCap > 0){
	If ($AverageTotalCap1 / $SalaryCap > 1){
		echo "<td style=\"background-color:#f44336;color:#fff;\">" . number_Format(($AverageTotalCap1 / $SalaryCap)*100,2) . "%</td>";
	}elseif ($AverageTotalCap1 / $SalaryCap > 0.95){
		echo "<td style=\"background-color:#FFA500\">" . number_Format(($AverageTotalCap1 / $SalaryCap)*100,2) . "%</td>";
	}elseif($AverageTotalCap1 / $SalaryCap > 0.90){
		echo "<td style=\"background-color:#FFFF00\">" . number_Format(($AverageTotalCap1 / $SalaryCap)*100,2) . "%</td>";
	}else{
		echo "<td style=\"background-color:#00ff00\">" . number_Format(($AverageTotalCap1 / $SalaryCap)*100,2) . "%</td>";
	}
}else{echo "<td>N/A</td>";}
echo "<td>" . number_format($AverageTotalCap1,0) . "$</td><td>" . number_format($AverageTotalCap2,0) . "$</td><td>" . number_format($AverageTotalCap3,0) . "$</td><td>" . number_format($AverageTotalCap4,0) . "$</td><td>" . number_format($AverageTotalCap5,0) . "$</td></tr>";		
echo "</tbody></table><br />";
echo $TeamLang['TermsLegend'] . "<br /><br />";
echo $TeamLang['NoteContractOverviewSalaryCap'] . "<strong>" . number_format($SalaryCap,0) . "$</strong>.<br /><br />";
?>
<hr /><br />
<div>
<?php 
echo "<h1>" . $TeamLang['SalaryCapSimulation'] . "</h1>"; 
echo $TeamLang['SalaryCapSimulationNote'] . "<br /><br /><br />"; 
?>
<form class="STHSCenter" id="ChangePlayer" name="ChangePlayer" method="post" action="TeamSalaryCapDetail.php<?php If ($lang == "fr" ){echo "?Lang=fr";}?>">
	<table class="STHSCenter">
	<tr>
		<th style="padding-left:300px;"></th>
		<th class="STHSPHPTeamSalaryCapDetailTitle"><?php echo $TeamLang['SelectPlayers'];?></th>
	</tr>
	
	<tr>
	<td></td>
	<td><select id="SelectPlayers" name="SelectPlayers[]"  multiple="multiple">
	<?php
	if (empty($AllPlayers) == false){while ($Row = $AllPlayers ->fetchArray()) { 
		echo "<option value='" . $Row['Number'] . "'";
		If ($SubmitPlayer==False AND empty($SelectPlayers) == True){
			If ($Row['Team'] == $Team){echo " selected";}
		}else{
			If (in_array($Row['Number'],$SelectPlayers)==True){echo " selected";}
		}			
		echo  ">" . $Row['Name'] . "</option>\n";
	}}?>
	</select></td>
	</tr>
		
	<tr>
      <td></td><td class="STHSPHPTradeType"><input class="SubmitButton" type="submit" name="Submit" value="<?php echo $PlayersLang['Submit'];?>" /></td>
    </tr>
	</table>
</form>
<script>
$('#SelectPlayers').multiSelect();
</script>
</div>

</div>
<?php include "Footer.php";?>
