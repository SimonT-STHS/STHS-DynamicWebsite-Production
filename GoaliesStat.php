<!DOCTYPE html>
<?php include "Header.php";?>
<?php
$Team = (integer)-1; /* -1 All Team */
$Title = (string)"";
$Search = (boolean)False;
$HistoryOutput = (boolean)False;
$CareerLeaderSubPrintOut = (int)0;
include "SearchPossibleOrderField.php";
If (file_exists($DatabaseFile) == false){
	$LeagueName = $DatabaseNotFound;
	$GoalieStat = Null;
	echo "<title>" . $DatabaseNotFound . "</title>";
	$Title = $DatabaseNotFound;
}else{
	$TypeText = (string)"Pro";$TitleType = $DynamicTitleLang['Pro'];
	$ACSQuery = (boolean)FALSE;/* The SQL Query must be Ascending Order and not Descending */
	$MaximumResult = (integer)0;
	$MinimumGP = (integer)0;
	$OrderByField = (string)"W";
	$OrderByFieldText = (string)"Win";
	$OrderByInput = (string)"";
	$TitleOverwrite = (string)"";
	$MinGP = (boolean)FALSE;
	if(isset($_GET['Farm'])){$TypeText = "Farm";$TitleType = $DynamicTitleLang['Farm'];}
	if(isset($_GET['ACS'])){$ACSQuery= TRUE;}
	if(isset($_GET['Max'])){$MaximumResult = filter_var($_GET['Max'], FILTER_SANITIZE_NUMBER_INT);} 
	if(isset($_GET['Order'])){$OrderByInput = filter_var($_GET['Order'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH || FILTER_FLAG_NO_ENCODE_QUOTES || FILTER_FLAG_STRIP_BACKTICK);} 
	if(isset($_GET['Team'])){$Team = filter_var($_GET['Team'], FILTER_SANITIZE_NUMBER_INT);} 
	if(isset($_GET['Title'])){$TitleOverwrite  = filter_var($_GET['Title'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH || FILTER_FLAG_NO_ENCODE_QUOTES || FILTER_FLAG_STRIP_BACKTICK);} 
	$LeagueName = (string)"";

	foreach ($GoaliesStatPossibleOrderField as $Value) {
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
			
			$Query = "Select Name, PlayOffStarted from LeagueGeneral WHERE Year = " . $Year . " And Playoff = '" . $PlayoffString. "'";
			$LeagueGeneral = $db->querySingle($Query,true);	
			
			//Confirm Valid Data Found
			$CareerDBFormatV2CheckCheck = $db->querySingle("Select Count(Name) As CountName from LeagueGeneral  WHERE Year = " . $Year . " And Playoff = '" . $PlayoffString. "'",true);
			If ($CareerDBFormatV2CheckCheck['CountName'] == 1){$LeagueName = $LeagueGeneral['Name'];}else{$Year = (integer)0;$HistoryOutput = (boolean)False;Goto RegularSeason;}
			
			if(isset($_GET['MinGP'])){
				$MinGP = True;
				$Query = "Select " . $TypeText . "MinimumGamePlayerLeader AS MinimumGamePlayerLeader from LeagueOutputOption WHERE Year = " . $Year . " And Playoff = '" . $PlayoffString. "'";
				$LeagueOutputOption = $db->querySingle($Query,true);	
				$MinimumGP = $LeagueOutputOption['MinimumGamePlayerLeader'];
			}
			
			If($MaximumResult == 0){$Title = $DynamicTitleLang['All'];}else{$Title = $DynamicTitleLang['Top'] . $MaximumResult . " ";}
			$Query = "SELECT GoalerInfoHistory.TeamName,'0' As TeamThemeID, Goaler" . $TypeText . "StatHistory.*, ROUND((CAST(Goaler" . $TypeText . "StatHistory.GA AS REAL) / (Goaler" . $TypeText . "StatHistory.SecondPlay / 60))*60,3) AS GAA, ROUND((CAST(Goaler" . $TypeText . "StatHistory.SA - Goaler" . $TypeText . "StatHistory.GA AS REAL) / (Goaler" . $TypeText . "StatHistory.SA)),3) AS PCT, ROUND((CAST(Goaler" . $TypeText . "StatHistory.PenalityShotsShots - Goaler" . $TypeText . "StatHistory.PenalityShotsGoals AS REAL) / (Goaler" . $TypeText . "StatHistory.PenalityShotsShots)),3) AS PenalityShotsPCT FROM GoalerInfoHistory INNER JOIN Goaler" . $TypeText . "StatHistory ON GoalerInfoHistory.Number = Goaler" . $TypeText . "StatHistory.Number WHERE GoalerInfoHistory.Retire = 'False' AND Goaler" . $TypeText . "StatHistory.GP >= " . $MinimumGP . " AND GoalerInfoHistory.Year = " . $Year . " AND GoalerInfoHistory.Playoff = '" . $PlayoffString. "' AND Goaler" . $TypeText . "StatHistory.Year = " . $Year . " AND Goaler" . $TypeText . "StatHistory.Playoff = '" . $PlayoffString. "'";
			if($Team > 0){
				$Query = $Query . " AND GoalerInfoHistory.Team = " . $Team;
				$QueryTeam = "SELECT Name FROM Team" . $TypeText . "InfoHistory WHERE Number = " . $Team . " AND Year = " . $Year . " And Playoff = '" . $PlayoffString. "'";
				$TeamName = $db->querySingle($QueryTeam,true);	
				$Title = $Title . $TeamName['Name'];
			}
			$Title = $Title  . $DynamicTitleLang['GoaliesStat'] . $TitleType;
			If ($OrderByField == "PCT" OR $OrderByField == "GAA" OR $OrderByField == "PenalityShotsPCT"){$Query = $Query . " ORDER BY " . $OrderByField;}else{$Query = $Query . " ORDER BY Goaler" . $TypeText . "StatHistory." . $OrderByField;}

			If ($ACSQuery == TRUE){
				$Query = $Query . " ASC";
				$Title = $Title . $DynamicTitleLang['InAscendingOrderBy'] . $OrderByFieldText;
			}else{
				$Query = $Query . " DESC";
				$Title = $Title . $DynamicTitleLang['InDecendingOrderBy'] . $OrderByFieldText;
			}
			If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
			$GoalieStat = $db->query($Query);

			if(isset($_GET['MinGP'])){$Title = $Title . " - " . $TeamStatLang['MinimumGamesPlayed'] . $MinimumGP;}
			$Title = $Title  . " - " . $Year;
			If ($Playoff == True){$Title = $Title . $TopMenuLang['Playoff'];}			

			echo "<title>" . $LeagueName . " - " . $Title . "</title>";			

		}else{
			Goto RegularSeason;
		}
	}else{
		/* Regular Season */			
		RegularSeason:			
	
		$db = new SQLite3($DatabaseFile);
		$Query = "Select Name, PlayOffStarted from LeagueGeneral";
		$LeagueGeneral = $db->querySingle($Query,true);		
		$LeagueName = $LeagueGeneral['Name'];
		
		if(isset($_GET['MinGP'])){
			$MinGP = True;
			$Query = "Select " . $TypeText . "MinimumGamePlayerLeader AS MinimumGamePlayerLeader from LeagueOutputOption";
			$LeagueOutputOption = $db->querySingle($Query,true);	
			$MinimumGP = $LeagueOutputOption['MinimumGamePlayerLeader'];
		}
		
		if(isset($_GET['Season'])){$TypeText = $TypeText . "Season";}
		
		If($MaximumResult == 0){$Title = $DynamicTitleLang['All'];}else{$Title = $DynamicTitleLang['Top'] . $MaximumResult . " ";}
		$Query = "SELECT GoalerInfo.TeamName, GoalerInfo.TeamThemeID, Goaler" . $TypeText . "Stat.*, ROUND((CAST(Goaler" . $TypeText . "Stat.GA AS REAL) / (Goaler" . $TypeText . "Stat.SecondPlay / 60))*60,3) AS GAA, ROUND((CAST(Goaler" . $TypeText . "Stat.SA - Goaler" . $TypeText . "Stat.GA AS REAL) / (Goaler" . $TypeText . "Stat.SA)),3) AS PCT, ROUND((CAST(Goaler" . $TypeText . "Stat.PenalityShotsShots - Goaler" . $TypeText . "Stat.PenalityShotsGoals AS REAL) / (Goaler" . $TypeText . "Stat.PenalityShotsShots)),3) AS PenalityShotsPCT FROM GoalerInfo INNER JOIN Goaler" . $TypeText . "Stat ON GoalerInfo.Number = Goaler" . $TypeText . "Stat.Number WHERE GoalerInfo.Retire = 'False' AND Goaler" . $TypeText . "Stat.GP >= " . $MinimumGP;
		if($Team > 0){
			$Query = $Query . " AND GoalerInfo.Team = " . $Team;
			$QueryTeam = "SELECT Name FROM Team" . $TypeText . "Info WHERE Number = " . $Team;
			$TeamName = $db->querySingle($QueryTeam,true);	
			$Title = $Title . $TeamName['Name'];
		}
		$Title = $Title  . $DynamicTitleLang['GoaliesStat'] . $TitleType;
		If ($OrderByField == "PCT" OR $OrderByField == "GAA" OR $OrderByField == "PenalityShotsPCT"){$Query = $Query . " ORDER BY " . $OrderByField;}else{$Query = $Query . " ORDER BY Goaler" . $TypeText . "Stat." . $OrderByField;}

		If ($ACSQuery == TRUE){
			$Query = $Query . " ASC";
			$Title = $Title . $DynamicTitleLang['InAscendingOrderBy'] . $OrderByFieldText;
		}else{
			$Query = $Query . " DESC";
			$Title = $Title . $DynamicTitleLang['InDecendingOrderBy'] . $OrderByFieldText;
		}
		If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
		$GoalieStat = $db->query($Query);

		if(isset($_GET['MinGP'])){$Title = $Title . " - " . $TeamStatLang['MinimumGamesPlayed'] . $MinimumGP;}

		/* OverWrite Title if information is get from PHP GET */
		if($TitleOverwrite <> ""){$Title = $TitleOverwrite;}	
		echo "<title>" . $LeagueName . " - " . $Title . "</title>";
	}
}?>
</head><body>
<?php include "Menu.php";?>
<script>
$(function() {
  $.tablesorter.addWidget({ id: "numbering",format: function(table) {var c = table.config;$("tr:visible", table.tBodies[0]).each(function(i) {$(this).find('td').eq(0).text(i + 1);});}});
  $(".STHSPHPAllGoalieStat_Table").tablesorter({
    widgets: ['numbering', 'columnSelector', 'stickyHeaders', 'filter', 'output'],
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
	  filter_searchDelay : 500,	  
      filter_reset: '.tablesorter_Reset',	 
	  output_delivery: 'd',
	  output_saveFileName: 'STHSGoalieStat.CSV'
    }
  });
  $('.download').click(function(){
      var $table = $('.STHSPHPAllGoalieStat_Table'),
      wo = $table[0].config.widgetOptions;
      $table.trigger('outputTable');
      return false;
  });  
});
</script>

<div style="width:99%;margin:auto;">
<?php echo "<h1>" . $Title . "</h1>";?>
<div id="ReQueryDiv" style="display:none;">
<?php If($HistoryOutput == False){
	include "SearchGoaliesStat.php";
}else{
	include "SearchHistorySub.php";
	include "SearchHistoryGoaliesStat.php";
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
</div>

<table class="tablesorter STHSPHPAllGoalieStat_Table"><thead><tr>
	<?php include "GoaliesStatSub.php";?>
</tbody></table>
<br />
</div>
<?php include "Footer.php";?>
