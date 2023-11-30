<?php include "Header.php";
If ($lang == "fr"){include 'LanguageFR-League.php';}else{include 'LanguageEN-League.php';}
$CoachesQueryOK = (boolean)False;
$HistoryOutput = (boolean)False;
$ExtraH1 = (string)"";
If (file_exists($DatabaseFile) == false){
	Goto STHSErrorCoach;
}else{try{
	
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
			$Query = "Select FarmEnable from LeagueSimulation WHERE Year = " . $Year . " And Playoff = '" . $PlayoffString. "'";
			$LeagueSimulationMenu = $db->querySingle($Query,true);
			
			$Query = "Select Name, OutputName from LeagueGeneral WHERE Year = " . $Year . " And Playoff = '" . $PlayoffString. "'";
			$LeagueGeneral = $db->querySingle($Query,true);		
			
			//Confirm Valid Data Found
			$CareerDBFormatV2CheckCheck = $db->querySingle("Select Count(Name) As CountName from LeagueGeneral  WHERE Year = " . $Year . " And Playoff = '" . $PlayoffString. "'",true);
			If ($CareerDBFormatV2CheckCheck['CountName'] == 1){$LeagueName = $LeagueGeneral['Name'];}else{$Year = (integer)0;$HistoryOutput = (boolean)False;Goto RegularSeason;}
			
			$Title = $LeagueName . " - " . $CoachesLang['CoachesTitle'] . " - " . $Year;
			$ExtraH1 = " - " . $Year;
			If ($Playoff == True){$Title = $Title . $TopMenuLang['Playoff'];$ExtraH1 = $ExtraH1 . $TopMenuLang['Playoff'];}
		}else{
			Goto RegularSeason;
		}
	}else{
		/* Regular Season */	
		RegularSeason:		
		$db = new SQLite3($DatabaseFile);
		$Query = "Select FarmEnable from LeagueSimulation";
		$LeagueSimulationMenu = $db->querySingle($Query,true);
		
		$Query = "Select Name, OutputName from LeagueGeneral";
		$LeagueGeneral = $db->querySingle($Query,true);		
		$LeagueName = $LeagueGeneral['Name'];
		$Title = $LeagueName . " - " . $CoachesLang['CoachesTitle'];
	}
	$CoachesQueryOK = True;
} catch (Exception $e) {
STHSErrorCoach:
	$LeagueName = $DatabaseNotFound;
	$Coach = Null;
	echo "<style>Div{display:none}</style>";
	$Title = $DatabaseNotFound;
	$LeagueSimulationMenu = Null;
	$HistoryOutput = False;
}}
echo "<title>" . $Title  . "</title>";
?>
<style>
#tablesorter_colSelectPro:checked + label {background: #5797d7;  border-color: #555;}
#tablesorter_colSelectPro:checked ~ #tablesorter_ColumnSelectorPro {display: block;}
#tablesorter_colSelectFarm:checked + label {background: #5797d7;  border-color: #555;}
#tablesorter_colSelectFarm:checked ~ #tablesorter_ColumnSelectorFarm {display: block;}
#tablesorter_colSelectAvailable:checked + label {background: #5797d7;  border-color: #555;}
#tablesorter_colSelectAvailable:checked ~ #tablesorter_ColumnSelectorAvailable {display: block;}
<?php if (isset($LeagueSimulationMenu)){If ($LeagueSimulationMenu['FarmEnable'] == "False"){echo "#FarmTable{display:none;}\n#FarmH1{display:none;}";}}?>
</style>
</head><body>
<?php include "Menu.php";?>
<script>
$(function() {
  $(".STHSPHPProCoaches_Table").tablesorter({
    widgets: ['columnSelector', 'stickyHeaders', 'filter'],
    widgetOptions : {
      columnSelector_container : $('#tablesorter_ColumnSelectorPro'),
      columnSelector_layout : '<label><input type="checkbox">{name}</label>',
      columnSelector_name  : 'title',
      columnSelector_mediaquery: true,
      columnSelector_mediaqueryName: 'Automatic',
      columnSelector_mediaqueryState: true,
      columnSelector_mediaqueryHidden: true,
      columnSelector_breakpoints : [ '20em', '40em', '60em', '80em', '90em', '95em' ],		
	  filter_columnFilters: true,
      filter_placeholder: { search : '<?php echo $TableSorterLang['Search'];?>' },
	  filter_searchDelay : 500,	  
      filter_reset: '.tablesorter_Reset'	 
    }
  });
  $(".STHSPHPFarmCoaches_Table").tablesorter({
    widgets: ['columnSelector', 'stickyHeaders', 'filter'],
    widgetOptions : {
      columnSelector_container : $('#tablesorter_ColumnSelectorFarm'),
      columnSelector_layout : '<label><input type="checkbox">{name}</label>',
      columnSelector_name  : 'title',
      columnSelector_mediaquery: true,
      columnSelector_mediaqueryName: 'Automatic',
      columnSelector_mediaqueryState: true,
      columnSelector_mediaqueryHidden: true,
      columnSelector_breakpoints : [ '20em', '40em', '60em', '80em', '90em', '95em' ],		
	  filter_columnFilters: true,
      filter_placeholder: { search : '<?php echo $TableSorterLang['Search'];?>' },
	  filter_searchDelay : 500,	  
      filter_reset: '.tablesorter_Reset'	 
    }
  });  
  $(".STHSPHPAvailableCoaches_Table").tablesorter({
    widgets: ['columnSelector', 'stickyHeaders', 'filter'],
    widgetOptions : {
      columnSelector_container : $('#tablesorter_ColumnSelectorAvailable'),
      columnSelector_layout : '<label><input type="checkbox">{name}</label>',
      columnSelector_name  : 'title',
      columnSelector_mediaquery: true,
      columnSelector_mediaqueryName: 'Automatic',
      columnSelector_mediaqueryState: true,
      columnSelector_mediaqueryHidden: true,
      columnSelector_breakpoints : [ '20em', '40em', '60em', '80em', '90em', '95em' ],		
	  filter_columnFilters: true,
      filter_placeholder: { search : '<?php echo $TableSorterLang['Search'];?>' },
	  filter_searchDelay : 500,	  
      filter_reset: '.tablesorter_Reset'	 
    }
  });  
});
</script>

<div style="width:95%;margin:auto;">
<?php echo "<h1>" . $CoachesLang['CoachesTitle'] . $ExtraH1 . "</h1>"; 
If($HistoryOutput == True){
	echo "<div id=\"ReQueryDiv\" style=\"display:none;\">";include "SearchHistorySub.php";include "SearchHistoryCoaches.php";echo "</div>";
	echo "<button class=\"tablesorter_Output\" style=\"margin-left:15px\" id=\"ReQuery\">" . $SearchLang['ChangeSearch'] . "</button>";
}?>

<br />
<div class="tablesorter_ColumnSelectorWrapper">
	<input id="tablesorter_colSelectPro" type="checkbox" class="hidden">
    <label class="tablesorter_ColumnSelectorButton" for="tablesorter_colSelectPro"><?php echo $TableSorterLang['ShoworHideColumn'];?></label>
    <div id="tablesorter_ColumnSelectorPro" class="tablesorter_ColumnSelector"></div>
	<?php include "FilterTip.php";?>
	</div>

<h1><?php echo $CoachesLang['ProCoaches'] . $ExtraH1;?></h1>
<table class="STHSPHPProCoaches_Table tablesorter"><thead><tr>
<th data-priority="critical" title="Coaches Name" class="STHSW200"><?php echo $CoachesLang['CoachesName'];?></th>
<th data-priority="1" title="Team Name" class="STHSW200"><?php echo $CoachesLang['TeamName'];?></th>
<th data-priority="2" title="Physical Style" class="STHSW25">PH</th>
<th data-priority="2" title="Defense Style" class="STHSW25">DF</th>
<th data-priority="2" title="Offense Style" class="STHSW25">OF</th>
<th data-priority="2" title="Player Discipline" class="STHSW25">PD</th>
<th data-priority="2" title="Experience" class="STHSW25">EX</th>
<th data-priority="2" title="Leadership" class="STHSW25">LD</th>
<th data-priority="3" title="Potential" class="STHSW25">PO</th>
<th data-priority="6" title="Country" class="STHSW35">CNT</th>
<th data-priority="5" title="Age" class="STHSW35"><?php echo $CoachesLang['Age'];?></th>
<th data-priority="4" title="Contract" class="STHSW25"><?php echo $CoachesLang['Contract'];?></th>
<th data-priority="6" title="Contract Signature Date" class="STHSW55"><?php echo $CoachesLang['ContractSignatureDate'];?></th>
<th data-priority="4" title="Salary" class="STHSW100"><?php echo $CoachesLang['Salary'];?></th>
</tr></thead>
<tbody>
<?php
If($CoachesQueryOK == True){If ($HistoryOutput == False){
	$Query = "SELECT CoachInfo.*, TeamProInfo.Name as TeamProName, TeamFarmInfo.Name As TeamFarmName, TeamProInfo.CoachID as ProCoachTeamID, TeamFarmInfo.CoachID as FarmCoachTeamID, TeamProInfo.TeamThemeID As TeamThemeID FROM (CoachInfo LEFT JOIN TeamFarmInfo ON CoachInfo.Team = TeamFarmInfo.Number) LEFT JOIN TeamProInfo ON CoachInfo.Team = TeamProInfo.Number WHERE TEAM <> 0 ORDER BY CoachInfo.Name";
	If (file_exists($DatabaseFile) ==True){$Coach = $db->query($Query);}
}else{
	$Query = "SELECT CoachInfo.*, TeamProInfoHistory.Name as TeamProName, TeamFarmInfoHistory.Name As TeamFarmName, TeamProInfoHistory.CoachID as ProCoachTeamID, TeamFarmInfoHistory.CoachID as FarmCoachTeamID, 0 AS TeamThemeID FROM (CoachInfo LEFT JOIN TeamFarmInfoHistory ON CoachInfo.Team = TeamFarmInfoHistory.Number) LEFT JOIN TeamProInfoHistory ON CoachInfo.Team = TeamProInfoHistory.Number WHERE  TEAM <> 0 AND CoachInfo.Year = " . $Year . " AND CoachInfo.Playoff = '" . $PlayoffString. "' AND TeamProInfoHistory.Year = " . $Year . " AND TeamProInfoHistory.Playoff = '" . $PlayoffString. "' AND  TeamFarmInfoHistory.Year = " . $Year . " AND  TeamFarmInfoHistory.Playoff = '" . $PlayoffString. "' ORDER BY CoachInfo.Name";
	If (file_exists($CareerStatDatabaseFile) ==True){$Coach = $db->query($Query);}	
}
if (empty($Coach) == false){while ($row = $Coach ->fetchArray()) {
	If ($row['Number'] == $row['ProCoachTeamID']){
		echo "<tr><td>" . $row['Name'] . "</td><td>";
		If ($row['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPCoachesTeamImage\" />";}
		echo $row['TeamProName'] . "</td>";
		echo "<td>" . $row['PH'] . "</td>";
		echo "<td>" . $row['DF'] . "</td>";
		echo "<td>" . $row['OF'] . "</td>";
		echo "<td>" . $row['PD'] . "</td>";
		echo "<td>" . $row['EX'] . "</td>";
		echo "<td>" . $row['LD'] . "</td>";
		echo "<td>" . $row['PO'] . "</td>";
		echo "<td>" . $row['Country'] . "</td>";
		echo "<td>" . $row['Age'] . "</td>";
		echo "<td>" . $row['Contract'] . "</td>";
		echo "<td>" . $row['ContractSignatureDate'] . "</td>";
		echo "<td>" . number_format($row['Salary'],0) . "$</td>";
		echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
	}
}}}
?>
</tbody></table>
<br />

<div class="tablesorter_ColumnSelectorWrapper">
	<input id="tablesorter_colSelectFarm" type="checkbox" class="hidden">
    <label class="tablesorter_ColumnSelectorButton" for="tablesorter_colSelectFarm"><?php echo $TableSorterLang['ShoworHideColumn'];?></label>
    <div id="tablesorter_ColumnSelectorFarm" class="tablesorter_ColumnSelector"></div>
	<?php include "FilterTip.php";?>
</div>

<h1 id="FarmH1"><?php echo $CoachesLang['FarmCoaches'] . $ExtraH1;?></h1>
<table id="FarmTable" class="STHSPHPFarmCoaches_Table tablesorter"><thead><tr>
<th data-priority="critical" title="Coaches Name" class="STHSW200"><?php echo $CoachesLang['CoachesName'];?></th>
<th data-priority="1" title="Team Name" class="STHSW200"><?php echo $CoachesLang['TeamName'];?></th>
<th data-priority="2" title="Physical Style" class="STHSW25">PH</th>
<th data-priority="2" title="Defense Style" class="STHSW25">DF</th>
<th data-priority="2" title="Offense Style" class="STHSW25">OF</th>
<th data-priority="2" title="Player Discipline" class="STHSW25">PD</th>
<th data-priority="2" title="Experience" class="STHSW25">EX</th>
<th data-priority="2" title="Leadership" class="STHSW25">LD</th>
<th data-priority="3" title="Potential" class="STHSW25">PO</th>
<th data-priority="6" title="Country" class="STHSW35">CNT</th>
<th data-priority="5" title="Age" class="STHSW35"><?php echo $CoachesLang['Age'];?></th>
<th data-priority="4" title="Contract" class="STHSW25"><?php echo $CoachesLang['Contract'];?></th>
<th data-priority="6" title="Contract Signature Date" class="STHSW55"><?php echo $CoachesLang['ContractSignatureDate'];?></th>
<th data-priority="4" title="Salary" class="STHSW100"><?php echo $CoachesLang['Salary'];?></th>
</tr></thead>
<tbody>
<?php
If($CoachesQueryOK == True){If ($HistoryOutput == False){
	$Query = "SELECT CoachInfo.*, TeamProInfo.Name as TeamProName, TeamFarmInfo.Name As TeamFarmName, TeamProInfo.CoachID as ProCoachTeamID, TeamFarmInfo.CoachID as FarmCoachTeamID, TeamFarmInfo.TeamThemeID As TeamThemeID  FROM (CoachInfo LEFT JOIN TeamFarmInfo ON CoachInfo.Team = TeamFarmInfo.Number) LEFT JOIN TeamProInfo ON CoachInfo.Team = TeamProInfo.Number WHERE TEAM <> 0 ORDER BY CoachInfo.Name";
	If (file_exists($DatabaseFile) ==True){$Coach = $db->query($Query);}
}else{
	$Query = "SELECT CoachInfo.*, TeamProInfoHistory.Name as TeamProName, TeamFarmInfoHistory.Name As TeamFarmName, TeamProInfoHistory.CoachID as ProCoachTeamID, TeamFarmInfoHistory.CoachID as FarmCoachTeamID, 0 AS TeamThemeID FROM (CoachInfo LEFT JOIN TeamFarmInfoHistory ON CoachInfo.Team = TeamFarmInfoHistory.Number) LEFT JOIN TeamProInfoHistory ON CoachInfo.Team = TeamProInfoHistory.Number WHERE TEAM <> 0 AND CoachInfo.Year = " . $Year . " AND CoachInfo.Playoff = '" . $PlayoffString. "' AND TeamProInfoHistory.Year = " . $Year . " AND TeamProInfoHistory.Playoff = '" . $PlayoffString. "' AND  TeamFarmInfoHistory.Year = " . $Year . " AND  TeamFarmInfoHistory.Playoff = '" . $PlayoffString. "' ORDER BY CoachInfo.Name";
	If (file_exists($CareerStatDatabaseFile) ==True){$Coach = $db->query($Query);}	
}
if (empty($Coach) == false){while ($row = $Coach ->fetchArray()) {
	If ($row['Number'] == $row['FarmCoachTeamID']){
		echo "<tr><td>" . $row['Name'] . "</td><td>";
		If ($row['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPCoachesTeamImage\" />";}		
		echo $row['TeamFarmName'] . "</td>";
		echo "<td>" . $row['PH'] . "</td>";
		echo "<td>" . $row['DF'] . "</td>";
		echo "<td>" . $row['OF'] . "</td>";
		echo "<td>" . $row['PD'] . "</td>";
		echo "<td>" . $row['EX'] . "</td>";
		echo "<td>" . $row['LD'] . "</td>";
		echo "<td>" . $row['PO'] . "</td>";
		echo "<td>" . $row['Country'] . "</td>";
		echo "<td>" . $row['Age'] . "</td>";
		echo "<td>" . $row['Contract'] . "</td>";
		echo "<td>" . $row['ContractSignatureDate'] . "</td>";
		echo "<td>" . number_format($row['Salary'],0) . "$</td>";
		echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
	}
}}}
?>
</tbody></table>
<br />

<div class="tablesorter_ColumnSelectorWrapper">
	<input id="tablesorter_colSelectAvailable" type="checkbox" class="hidden">
    <label class="tablesorter_ColumnSelectorButton" for="tablesorter_colSelectAvailable"><?php echo $TableSorterLang['ShoworHideColumn'];?></label>
    <div id="tablesorter_ColumnSelectorAvailable" class="tablesorter_ColumnSelector"></div>
	<?php include "FilterTip.php";?>
</div>

<h1><?php echo $CoachesLang['AvailableCoaches'] . $ExtraH1;?></h1>
<table class="STHSPHPAvailableCoaches_Table tablesorter"><thead><tr>
<th data-priority="critical" title="Coaches Name" class="STHSW200"><?php echo $CoachesLang['CoachesName'];?></th>
<th data-priority="2" title="Physical Style" class="STHSW25">PH</th>
<th data-priority="2" title="Defense Style" class="STHSW25">DF</th>
<th data-priority="2" title="Offense Style" class="STHSW25">OF</th>
<th data-priority="2" title="Player Discipline" class="STHSW25">PD</th>
<th data-priority="2" title="Experience" class="STHSW25">EX</th>
<th data-priority="2" title="Leadership" class="STHSW25">LD</th>
<th data-priority="3" title="Potential" class="STHSW25">PO</th>
<th data-priority="6" title="Country" class="STHSW35">CNT</th>
<th data-priority="5" title="Age" class="STHSW35"><?php echo $CoachesLang['Age'];?></th>
<th data-priority="4" title="Contract" class="STHSW25"><?php echo $CoachesLang['Contract'];?></th>
<th data-priority="6" title="Contract Signature Date" class="STHSW55"><?php echo $CoachesLang['ContractSignatureDate'];?></th>
<th data-priority="4" title="Salary" class="STHSW100"><?php echo $CoachesLang['Salary'];?></th>
</tr></thead>
<tbody>
<?php
If($CoachesQueryOK == True){If ($HistoryOutput == False){
	$Query = "SELECT CoachInfo.*, TeamProInfo.Name as TeamProName, TeamFarmInfo.Name As TeamFarmName, TeamProInfo.CoachID as ProCoachTeamID, TeamFarmInfo.CoachID as FarmCoachTeamID FROM (CoachInfo LEFT JOIN TeamFarmInfo ON CoachInfo.Team = TeamFarmInfo.Number) LEFT JOIN TeamProInfo ON CoachInfo.Team = TeamProInfo.Number WHERE TEAM = 0 ORDER BY CoachInfo.Name";
	If (file_exists($DatabaseFile) ==True){$Coach = $db->query($Query);}
}else{
	$Query = "SELECT CoachInfo.*, TeamProInfoHistory.Name as TeamProName, TeamFarmInfoHistory.Name As TeamFarmName, TeamProInfoHistory.CoachID as ProCoachTeamID, TeamFarmInfoHistory.CoachID as FarmCoachTeamID, 0 AS TeamThemeID FROM (CoachInfo LEFT JOIN TeamFarmInfoHistory ON CoachInfo.Team = TeamFarmInfoHistory.Number) LEFT JOIN TeamProInfoHistory ON CoachInfo.Team = TeamProInfoHistory.Number WHERE  TEAM <> 0 AND CoachInfo.Year = " . $Year . " AND CoachInfo.Playoff = '" . $PlayoffString. "' AND TeamProInfoHistory.Year = " . $Year . " AND TeamProInfoHistory.Playoff = '" . $PlayoffString. "' AND  TeamFarmInfoHistory.Year = " . $Year . " AND  TeamFarmInfoHistory.Playoff = '" . $PlayoffString. "' ORDER BY CoachInfo.Name";
	If (file_exists($CareerStatDatabaseFile) ==True){$Coach = $db->query($Query);}	
}
if (empty($Coach) == false){while ($row = $Coach ->fetchArray()) {
	echo "<tr><td>" . $row['Name'] . "</td>";
	echo "<td>" . $row['PH'] . "</td>";
	echo "<td>" . $row['DF'] . "</td>";
	echo "<td>" . $row['OF'] . "</td>";
	echo "<td>" . $row['PD'] . "</td>";
	echo "<td>" . $row['EX'] . "</td>";
	echo "<td>" . $row['LD'] . "</td>";
	echo "<td>" . $row['PO'] . "</td>";
	echo "<td>" . $row['Country'] . "</td>";
	echo "<td>" . $row['Age'] . "</td>";
	echo "<td>" . $row['Contract'] . "</td>";
	echo "<td>" . $row['ContractSignatureDate'] . "</td>";
	echo "<td>" . number_format($row['Salary'],0) . "$</td>";
	echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
}}}
?>
</tbody></table>

<br />
</div>

<?php include "Footer.php";?>
