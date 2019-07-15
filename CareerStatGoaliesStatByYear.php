<!DOCTYPE html>
<?php include "Header.php";?>
<?php
$Team = (integer)-1; /* -1 All Team */
$Title = (string)"";
$Active = 2; /* Show Webpage Top Menu */
If (file_exists($DatabaseFile) == false){
	$LeagueName = $DatabaseNotFound;
	$GoalieStat = Null;
	echo "<title>" . $DatabaseNotFound . "</title>";
	$Title = $DatabaseNotFound;
}else{
	$TypeText = (string)"Pro";$TitleType = $DynamicTitleLang['Pro'];
	$ACSQuery = (boolean)FALSE;/* The SQL Query must be Ascending Order and not Descending */
	$Rookie = (boolean)FALSE;
	$Playoff = (string)"False";
	$MaximumResult = (integer)0;
	$MinimumGP = (integer)1;
	$TeamName = (string)"";
	$Year = (integer)0;	
	$OrderByField = (string)"W";
	$OrderByFieldText = (string)"Win";
	$OrderByInput = (string)"";
	$TitleOverwrite = (string)"";
	$CareerLeaderSubPrintOut = (int)1;
	if(isset($_GET['Farm'])){$TypeText = "Farm";$TitleType = $DynamicTitleLang['Farm'];$Active = 3;}
	if(isset($_GET['ACS'])){$ACSQuery= TRUE;}
	if(isset($_GET['Rookie'])){$Rookie= TRUE;}
	if(isset($_GET['Playoff'])){$Playoff="True";$MimimumData=1;}
	if(isset($_GET['Max'])){$MaximumResult = filter_var($_GET['Max'], FILTER_SANITIZE_NUMBER_INT);} 
	if(isset($_GET['Order'])){$OrderByInput = filter_var($_GET['Order'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH);} 
	if(isset($_GET['Year'])){$Year = filter_var($_GET['Year'], FILTER_SANITIZE_NUMBER_INT);} 
	if(isset($_GET['TeamName'])){$TeamName = filter_var($_GET['TeamName'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH);}	
	if(isset($_GET['Title'])){$TitleOverwrite  = filter_var($_GET['Title'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH);} 
	$LeagueName = (string)"";

	$GoaliesStatPossibleOrderField = array(
	array("Name","Goalie Name"),
	array("GP","Games Played"),
	array("W","Wins"),
	array("L","Losses"),
	array("OTL","Overtime Losses"),
	array("PCT","Save Percentage"),
	array("GAA","Goals Against Average"),
	array("SecondPlay","Minutes Played"),
	array("Pim","Penalty Minutes"),
	array("Shootout","Shootout"),
	array("SA","Shots Against"),
	array("GA","Goals Against"),
	array("SARebound","Shots Against Rebound"),
	array("A","Assists"),
	array("EmptyNetGoal","Empty net Goals"),
	array("PenalityShotsShots","Penalty Shots Against"),
	array("PenalityShotsGoals","Penalty Shots Goals"),
	array("PenalityShotsPCT","Penalty Shots Save Percentage"),
	array("StartGoaler","Number of game goalies start as Start goalie"),
	array("BackupGoaler","Number of game goalies start as Backup goalie"),
	array("Star1","Number of time players was star #1 in a game"),
	array("Star2","Number of time players was star #2 in a game"),
	array("Star3","Number of time players was star #3 in a game"),
	);
	foreach ($GoaliesStatPossibleOrderField as $Value) {
		If (strtoupper($Value[0]) == strtoupper($OrderByInput)){
			$OrderByField = $Value[0];
			$OrderByFieldText = $Value[1];
			Break;
		}
	}
	
	$db = new SQLite3($DatabaseFile);
	$Query = "Select Name from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
		
	If ($Playoff=="True"){$Title = $PlayersLang['Playoff'] .  " ";}
	$Title = $Title . $DynamicTitleLang['CareerStatByYear'];
	If($Rookie == True){$Title = $Title . $GeneralStatLang['Rookie'] . " - ";}
	If ($TeamName != ""){$Title = $Title . $TeamName . " - ";}
	If ($Year != ""){$Title = $Title . $Year . " - ";}
	If($MaximumResult == 0){$Title = $Title . $DynamicTitleLang['All'];}else{$Title = $Title . $DynamicTitleLang['Top'] . $MaximumResult . " ";}
	
	$Query = "SELECT GoalerInfo.Number As Number, Goaler" . $TypeText . "StatCareer.*, ROUND((CAST(Goaler" . $TypeText . "StatCareer.GA AS REAL) / (Goaler" . $TypeText . "StatCareer.SecondPlay / 60))*60,3) AS GAA, ROUND((CAST(Goaler" . $TypeText . "StatCareer.SA - Goaler" . $TypeText . "StatCareer.GA AS REAL) / (Goaler" . $TypeText . "StatCareer.SA)),3) AS PCT, ROUND((CAST(Goaler" . $TypeText . "StatCareer.PenalityShotsShots - Goaler" . $TypeText . "StatCareer.PenalityShotsGoals AS REAL) / (Goaler" . $TypeText . "StatCareer.PenalityShotsShots)),3) AS PenalityShotsPCT FROM Goaler" . $TypeText . "StatCareer LEFT JOIN GoalerInfo ON Goaler" . $TypeText . "StatCareer.Name = GoalerInfo.Name WHERE Goaler" . $TypeText . "StatCareer.GP >= " . $MinimumGP . " AND Goaler" . $TypeText . "StatCareer.Playoff = \"" . $Playoff . "\"";

	If($Year > 0){$Query = $Query . " AND Goaler" . $TypeText . "StatCareer.YEAR = \"" . $Year . "\"";}
	If($TeamName != ""){$Query = $Query . " AND Goaler" . $TypeText . "StatCareer.TeamName = \"" . $TeamName . "\"";}
	If($Rookie == True){$Query = $Query . " AND Goaler" . $TypeText . "StatCareer.Rookie = \"True\"";}

	$Title = $Title  . $DynamicTitleLang['GoaliesStat'] . $TitleType;
	If ($OrderByField == "PCT" OR $OrderByField == "GAA" OR $OrderByField == "PenalityShotsPCT"){$Query = $Query . " ORDER BY " . $OrderByField;}else{$Query = $Query . " ORDER BY Goaler" . $TypeText . "StatCareer." . $OrderByField;}

	If ($ACSQuery == TRUE){
		$Query = $Query . " ASC";
		$Title = $Title . $DynamicTitleLang['InAscendingOrderBy'] . $OrderByFieldText;
	}else{
		$Query = $Query . " DESC";
		$Title = $Title . $DynamicTitleLang['InDecendingOrderBy'] . $OrderByFieldText;
	}
	$Query = $Query . " ,Goaler" . $TypeText . "StatCareer.GP ASC"; // Force Second Order to be GP
	If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}

	If (file_exists($CareerStatDatabaseFile) == true){ /* CareerStat */
		$CareerStatdb = new SQLite3($CareerStatDatabaseFile);
		$CareerStatdb->query("ATTACH DATABASE '".$DatabaseFile."' AS CurrentDB");
		$GoalieStat = $CareerStatdb->query($Query);
	}

	/* OverWrite Title if information is get from PHP GET */
	if($TitleOverwrite <> ""){$Title = $TitleOverwrite;}	
	echo "<title>" . $LeagueName . " - " . $Title . "</title>";
}?>
</head><body>
<?php include "Menu.php";?>
<?php echo "<h1>" . $Title . "</h1>";?>
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

<div class="tablesorter_ColumnSelectorWrapper">
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
