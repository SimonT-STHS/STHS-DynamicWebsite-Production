<?php include "Header.php";
$Team = (integer)-1; /* -1 All Team */
$Title = (string)"";
$Search = (boolean)False;
$HistoryOutput = (boolean)False;
$CareerLeaderSubPrintOut = (int)0;
include "SearchPossibleOrderField.php";
If (file_exists($DatabaseFile) == false){
	Goto STHSErrorGoalersStat;
}else{try{
	$TypeText = (string)"Pro";$TitleType = $DynamicTitleLang['Pro'];
	$ACSQuery = (boolean)FALSE;/* The SQL Query must be Ascending Order and not Descending */
	$MaximumResult = (integer)0;
	$MinimumGP = (integer)1;
	$OrderByField = (string)"W";
	$OrderByFieldText = (string)"Win";
	$OrderByInput = (string)"";
	$TitleOverwrite = (string)"";
	$MinGP = (boolean)FALSE;
	if(isset($_GET['Farm'])){$TypeText = "Farm";$TitleType = $DynamicTitleLang['Farm'];}
	if(isset($_GET['ACS'])){$ACSQuery= TRUE;}
	if(isset($_GET['Max'])){$MaximumResult = filter_var($_GET['Max'], FILTER_SANITIZE_NUMBER_INT);} 
	if(isset($_GET['Order'])){$OrderByInput = filter_var($_GET['Order'], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH || FILTER_FLAG_NO_ENCODE_QUOTES || FILTER_FLAG_STRIP_BACKTICK);} 
	if(isset($_GET['Team'])){$Team = filter_var($_GET['Team'], FILTER_SANITIZE_NUMBER_INT);} 
	if(isset($_GET['Title'])){$TitleOverwrite  = filter_var($_GET['Title'], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH || FILTER_FLAG_NO_ENCODE_QUOTES || FILTER_FLAG_STRIP_BACKTICK);} 
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

	If($Year > 0 AND file_exists($CareerStatDatabaseFile) == true){  /* History Database */
		$db = new SQLite3($CareerStatDatabaseFile);
		$CareerDBFormatV2CheckCheck = $db->querySingle("SELECT Count(name) AS CountName FROM sqlite_master WHERE type='table' AND name='LeagueGeneral'",true);
		If ($CareerDBFormatV2CheckCheck['CountName'] == 1){
			$HistoryOutput = True;
			If ($Year == 9999 Or $Year == 9998){
				/* All Year : 9999 = All Season Per Year / 9998 = All Season Merge  */
				
				$dbLive = new SQLite3($DatabaseFile);
				$Query = "Select Name, LeagueYearOutput, PlayOffStarted, PreSeasonSchedule from LeagueGeneral";
				$LeagueGeneral = $dbLive ->querySingle($Query,true);		
				$LeagueName = $LeagueGeneral['Name'];
				
				$db->query("ATTACH DATABASE '".realpath($DatabaseFile)."' AS CurrentDB");
				
				If ($Playoff=="True"){$Title = $SearchLang['Playoff'] .  " ";}
				If ($Year == 9999 ){
					$Title = $Title . $SearchLang['AllSeasonPerYear'] . " - ";
					$CareerLeaderSubPrintOut = 1;
				}elseif ($Year == 9998){
					$Title = $Title . $SearchLang['AllSeasonMerge'] . " - ";
				}else{
					$Title = $Title . $DynamicTitleLang['CareerStat'];
				}
				
				if($Team > 0){
					$QueryTeam = "SELECT Name FROM Team" . $TypeText . "Info WHERE Number = " . $Team;
					$TeamName = $db->querySingle($QueryTeam,true);	
					$Title = $Title . $TeamName['Name'] . " - ";;		
				}				
				
				If($MaximumResult == 0){$Title = $Title . $DynamicTitleLang['All'];}else{$Title = $Title . $DynamicTitleLang['Top'] . $MaximumResult . " ";}
				
				If ($Year == 9998 ){
					$Query = "SELECT MainTable.Number, MainTable.UniqueID, '0' As TeamThemeID, MainTable.Name, MainTable.Team, MainTable.TeamName, Sum(MainTable.GP) AS GP, Sum(MainTable.SecondPlay) AS SecondPlay, Sum(MainTable.W) AS W, Sum(MainTable.L) AS L, Sum(MainTable.OTL) AS OTL, Sum(MainTable.Shootout) AS Shootout, Sum(MainTable.GA) AS GA, Sum(MainTable.SA) AS SA, Sum(MainTable.SARebound) AS SARebound, Sum(MainTable.Pim) AS Pim, Sum(MainTable.A) AS A, Sum(MainTable.PenalityShotsShots) AS PenalityShotsShots, Sum(MainTable.PenalityShotsGoals) AS PenalityShotsGoals, Sum(MainTable.StartGoaler) AS StartGoaler, Sum(MainTable.BackupGoaler) AS BackupGoaler, Sum(MainTable.EmptyNetGoal) AS EmptyNetGoal, Sum(MainTable.Star1) AS Star1, Sum(MainTable.Star2) AS Star2, Sum(MainTable.Star3) AS Star3, ROUND((CAST(Sum(MainTable.GA) AS REAL) / (Sum(MainTable.SecondPlay) / 60))*60, 3) AS GAA, ROUND((CAST(Sum(MainTable.SA) - Sum(MainTable.GA) AS REAL) / (Sum(MainTable.SA))), 3) AS PCT, ROUND((CAST(Sum(MainTable.PenalityShotsShots) - Sum(MainTable.PenalityShotsGoals) AS REAL) / (Sum(MainTable.PenalityShotsShots))), 3) AS PenalityShotsPCT";
				}else{
					$Query = "Select MainTable.*";
				}

				if($LeagueGeneral['PlayOffStarted'] == $PlayoffString AND $LeagueGeneral['PreSeasonSchedule'] == "False"){
					/* Regular Query */	
					$Query = $Query . " FROM ( Select MainLive.* FROM (SELECT '". $LeagueGeneral['LeagueYearOutput'] . "' as Year, GoalerInfo.TeamName, GoalerInfo.Team, GoalerInfo.Rookie, '0' As TeamThemeID, Goaler" . $TypeText . "Stat.Number, Goaler" . $TypeText . "Stat.UniqueID, Goaler" . $TypeText . "Stat.Name, Goaler" . $TypeText . "Stat.GP, Goaler" . $TypeText . "Stat.SecondPlay, Goaler" . $TypeText . "Stat.W, Goaler" . $TypeText . "Stat.L, Goaler" . $TypeText . "Stat.OTL, Goaler" . $TypeText . "Stat.Shootout, Goaler" . $TypeText . "Stat.GA, Goaler" . $TypeText . "Stat.SA, Goaler" . $TypeText . "Stat.SARebound, Goaler" . $TypeText . "Stat.Pim, Goaler" . $TypeText . "Stat.A, Goaler" . $TypeText . "Stat.PenalityShotsShots, Goaler" . $TypeText . "Stat.PenalityShotsGoals, Goaler" . $TypeText . "Stat.StartGoaler, Goaler" . $TypeText . "Stat.BackupGoaler, Goaler" . $TypeText . "Stat.EmptyNetGoal, Goaler" . $TypeText . "Stat.Star1, Goaler" . $TypeText . "Stat.Star2, Goaler" . $TypeText . "Stat.Star3, ROUND((CAST(Goaler" . $TypeText . "Stat.GA AS REAL) / (Goaler" . $TypeText . "Stat.SecondPlay / 60))*60, 3) AS GAA,  ROUND((CAST(Goaler" . $TypeText . "Stat.SA - Goaler" . $TypeText . "Stat.GA AS REAL) / (Goaler" . $TypeText . "Stat.SA)), 3) AS PCT,  ROUND((CAST(Goaler" . $TypeText . "Stat.PenalityShotsShots - Goaler" . $TypeText . "Stat.PenalityShotsGoals AS REAL) / (Goaler" . $TypeText . "Stat.PenalityShotsShots)), 3) AS PenalityShotsPCT FROM GoalerInfo INNER JOIN Goaler" . $TypeText . "Stat ON GoalerInfo.Number = Goaler" . $TypeText . "Stat.Number WHERE Goaler" . $TypeText . "Stat.GP > 0 ) AS MainLive UNION ALL Select MainHistory.* FROM (SELECT Goaler" . $TypeText . "StatHistory.Year, GoalerInfoHistory.ProTeamName AS TeamName,  GoalerInfoHistory.Team, GoalerInfoHistory.Rookie, '0' As TeamThemeID, Goaler" . $TypeText . "StatHistory.Number, Goaler" . $TypeText . "StatHistory.UniqueID, Goaler" . $TypeText . "StatHistory.Name, Goaler" . $TypeText . "StatHistory.GP, Goaler" . $TypeText . "StatHistory.SecondPlay, Goaler" . $TypeText . "StatHistory.W, Goaler" . $TypeText . "StatHistory.L, Goaler" . $TypeText . "StatHistory.OTL, Goaler" . $TypeText . "StatHistory.Shootout, Goaler" . $TypeText . "StatHistory.GA, Goaler" . $TypeText . "StatHistory.SA, Goaler" . $TypeText . "StatHistory.SARebound, Goaler" . $TypeText . "StatHistory.Pim, Goaler" . $TypeText . "StatHistory.A, Goaler" . $TypeText . "StatHistory.PenalityShotsShots, Goaler" . $TypeText . "StatHistory.PenalityShotsGoals, Goaler" . $TypeText . "StatHistory.StartGoaler, Goaler" . $TypeText . "StatHistory.BackupGoaler, Goaler" . $TypeText . "StatHistory.EmptyNetGoal, Goaler" . $TypeText . "StatHistory.Star1, Goaler" . $TypeText . "StatHistory.Star2, Goaler" . $TypeText . "StatHistory.Star3, ROUND((CAST(Goaler" . $TypeText . "StatHistory.GA AS REAL) / (Goaler" . $TypeText . "StatHistory.SecondPlay / 60))*60,3) AS GAA, ROUND((CAST(Goaler" . $TypeText . "StatHistory.SA - Goaler" . $TypeText . "StatHistory.GA AS REAL) / (Goaler" . $TypeText . "StatHistory.SA)),3) AS PCT, ROUND((CAST(Goaler" . $TypeText . "StatHistory.PenalityShotsShots - Goaler" . $TypeText . "StatHistory.PenalityShotsGoals AS REAL) / (Goaler" . $TypeText . "StatHistory.PenalityShotsShots)),3) AS PenalityShotsPCT FROM GoalerInfoHistory INNER JOIN Goaler" . $TypeText . "StatHistory ON GoalerInfoHistory.Number = Goaler" . $TypeText . "StatHistory.Number AND GoalerInfoHistory.Year = Goaler" . $TypeText . "StatHistory.Year AND GoalerInfoHistory.Playoff = Goaler" . $TypeText . "StatHistory.Playoff WHERE Goaler" . $TypeText . "StatHistory.GP > 0 AND GoalerInfoHistory.Playoff = '" . $PlayoffString. "') AS MainHistory) AS MainTable";
				}else{
					/* Requesting Playoff While in Season or Requesting Season while in Playoff or In Pre-Season Mode - Do not fetch data from live database */
					$Query = $Query . " FROM (SELECT Goaler" . $TypeText . "StatHistory.Year, GoalerInfoHistory.ProTeamName AS TeamName,  GoalerInfoHistory.Team, GoalerInfoHistory.Rookie, '0' As TeamThemeID, Goaler" . $TypeText . "StatHistory.Number, Goaler" . $TypeText . "StatHistory.UniqueID, Goaler" . $TypeText . "StatHistory.Name, Goaler" . $TypeText . "StatHistory.GP, Goaler" . $TypeText . "StatHistory.SecondPlay, Goaler" . $TypeText . "StatHistory.W, Goaler" . $TypeText . "StatHistory.L, Goaler" . $TypeText . "StatHistory.OTL, Goaler" . $TypeText . "StatHistory.Shootout, Goaler" . $TypeText . "StatHistory.GA, Goaler" . $TypeText . "StatHistory.SA, Goaler" . $TypeText . "StatHistory.SARebound, Goaler" . $TypeText . "StatHistory.Pim, Goaler" . $TypeText . "StatHistory.A, Goaler" . $TypeText . "StatHistory.PenalityShotsShots, Goaler" . $TypeText . "StatHistory.PenalityShotsGoals, Goaler" . $TypeText . "StatHistory.StartGoaler, Goaler" . $TypeText . "StatHistory.BackupGoaler, Goaler" . $TypeText . "StatHistory.EmptyNetGoal, Goaler" . $TypeText . "StatHistory.Star1, Goaler" . $TypeText . "StatHistory.Star2, Goaler" . $TypeText . "StatHistory.Star3, ROUND((CAST(Goaler" . $TypeText . "StatHistory.GA AS REAL) / (Goaler" . $TypeText . "StatHistory.SecondPlay / 60))*60,3) AS GAA, ROUND((CAST(Goaler" . $TypeText . "StatHistory.SA - Goaler" . $TypeText . "StatHistory.GA AS REAL) / (Goaler" . $TypeText . "StatHistory.SA)),3) AS PCT, ROUND((CAST(Goaler" . $TypeText . "StatHistory.PenalityShotsShots - Goaler" . $TypeText . "StatHistory.PenalityShotsGoals AS REAL) / (Goaler" . $TypeText . "StatHistory.PenalityShotsShots)),3) AS PenalityShotsPCT FROM GoalerInfoHistory INNER JOIN Goaler" . $TypeText . "StatHistory ON GoalerInfoHistory.Number = Goaler" . $TypeText . "StatHistory.Number AND GoalerInfoHistory.Year = Goaler" . $TypeText . "StatHistory.Year AND GoalerInfoHistory.Playoff = Goaler" . $TypeText . "StatHistory.Playoff WHERE Goaler" . $TypeText . "StatHistory.GP > 0 AND GoalerInfoHistory.Playoff = '" . $PlayoffString. "') AS MainTable";
				}
				if($Team > 0){$Query = $Query . " WHERE MainTable.Team = " . $Team;}
				
				
				If ($OrderByInput == "" AND $ACSQuery == FALSE){
					/* Default Sorting Hardcode  */
					$ACSQuery = TRUE;
					if($Year == 9998){
						$Query = $Query . " GROUP BY UniqueID ORDER BY Sum(MainTable.W) DESC, Sum(MainTable.GP)";
					}else{
						$Query = $Query . " ORDER BY MainTable.W DESC, MainTable.GP";
					}
				}else{				
					if($Year == 9998){
						$Query = $Query . " GROUP BY UniqueID ORDER BY Sum(MainTable." . $OrderByField . ")";
					}else{
						$Query = $Query . " ORDER BY MainTable." . $OrderByField;
					}	
				}
				
				$Title = $Title  . $DynamicTitleLang['GoaliesStat'] . $TitleType;	

				/* Order by  */				
				If ($ACSQuery == TRUE){
					$Query = $Query . " ASC";
					$Title = $Title . $DynamicTitleLang['InAscendingOrderBy'] . $OrderByFieldText;
				}else{
					$Query = $Query . " DESC";
					$Title = $Title . $DynamicTitleLang['InDecendingOrderBy'] . $OrderByFieldText;
				}
				If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
				$GoalieStat = $db->query($Query);
	
			}else{
				/* Specific Year */
				
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
				
				$Query = "SELECT GoalerInfoHistory.ProTeamName AS TeamName, '0' As TeamThemeID, Goaler" . $TypeText . "StatHistory.*, ROUND((CAST(Goaler" . $TypeText . "StatHistory.GA AS REAL) / (Goaler" . $TypeText . "StatHistory.SecondPlay / 60))*60,3) AS GAA, ROUND((CAST(Goaler" . $TypeText . "StatHistory.SA - Goaler" . $TypeText . "StatHistory.GA AS REAL) / (Goaler" . $TypeText . "StatHistory.SA)),3) AS PCT, ROUND((CAST(Goaler" . $TypeText . "StatHistory.PenalityShotsShots - Goaler" . $TypeText . "StatHistory.PenalityShotsGoals AS REAL) / (Goaler" . $TypeText . "StatHistory.PenalityShotsShots)),3) AS PenalityShotsPCT FROM GoalerInfoHistory INNER JOIN Goaler" . $TypeText . "StatHistory ON GoalerInfoHistory.Number = Goaler" . $TypeText . "StatHistory.Number WHERE GoalerInfoHistory.Retire = 'False' AND Goaler" . $TypeText . "StatHistory.GP >= " . $MinimumGP . " AND GoalerInfoHistory.Year = " . $Year . " AND GoalerInfoHistory.Playoff = '" . $PlayoffString. "' AND Goaler" . $TypeText . "StatHistory.Year = " . $Year . " AND Goaler" . $TypeText . "StatHistory.Playoff = '" . $PlayoffString. "'";
				if($Team > 0){
					$Query = $Query . " AND GoalerInfoHistory.Team = " . $Team;
					$QueryTeam = "SELECT Name FROM Team" . $TypeText . "InfoHistory WHERE Number = " . $Team . " AND Year = " . $Year . " And Playoff = '" . $PlayoffString. "'";
					$TeamName = $db->querySingle($QueryTeam,true);	
					$Title = $Title . $TeamName['Name'];
				}
				$Title = $Title  . $DynamicTitleLang['GoaliesStat'] . $TitleType;
				If ($OrderByField == "PCT" OR $OrderByField == "GAA" OR $OrderByField == "PenalityShotsPCT"){$Query = $Query . " ORDER BY " . $OrderByField;}else{$Query = $Query . " ORDER BY Goaler" . $TypeText . "StatHistory." . $OrderByField;}

				/* Order by  */
				If ($ACSQuery == TRUE){
					$Query = $Query . " ASC";
					$Title = $Title . $DynamicTitleLang['InAscendingOrderBy'] . $OrderByFieldText;
				}else{
					$Query = $Query . " DESC";
					$Title = $Title . $DynamicTitleLang['InDecendingOrderBy'] . $OrderByFieldText;
				}
				If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
				$GoalieStat = $db->query($Query);

				$Title = $Title  . " - " . $SearchLang['Year'] . " " . $Year;
				if(isset($_GET['MinGP'])){$Title = $Title . " - " . $TopMenuLang['MinimumGamesPlayed'] . $MinimumGP;}
				If ($Playoff == True){$Title = $Title . $TopMenuLang['Playoff'];}	
			}

			echo "<title>" . $LeagueName . " - " . $Title . "</title>";			

		}else{
			Goto RegularSeason;
		}
	}else{
		/* Regular Season Database Only */	
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

		/* Order by  */
		If ($ACSQuery == TRUE){
			$Query = $Query . " ASC";
			$Title = $Title . $DynamicTitleLang['InAscendingOrderBy'] . $OrderByFieldText;
		}else{
			$Query = $Query . " DESC";
			$Title = $Title . $DynamicTitleLang['InDecendingOrderBy'] . $OrderByFieldText;
		}
		If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
		$GoalieStat = $db->query($Query);
		
		if(isset($_GET['MinGP'])){$Title = $Title . " - " . $TopMenuLang['MinimumGamesPlayed'] . $MinimumGP;}

		/* OverWrite Title if information is get from PHP GET */
		if($TitleOverwrite <> ""){$Title = $TitleOverwrite;}	
		echo "<title>" . $LeagueName . " - " . $Title . "</title>";
	}
} catch (Exception $e) {
STHSErrorGoalersStat:
	$LeagueName = $DatabaseNotFound;
	$GoalieStat = Null;
	echo "<title>" . $DatabaseNotFound . "</title>";
	$Title = $DatabaseNotFound;	
}}?>
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

<table class="tablesorter STHSPHPAllGoalieStat_Table"><thead><tr>
	<?php include "GoaliesStatSub.php";?>
</tbody></table></div>
<br />
</div>
<?php include "Footer.php";?>
