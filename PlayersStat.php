<!DOCTYPE html>
<?php include "Header.php";?>
<?php
$Team = (integer)-1; /* -1 All Team */
$Title = (string)"";
$Active = 2; /* Show Webpage Top Menu */
If (file_exists($DatabaseFile) == false){
	$LeagueName = $DatabaseNotFound;
	$PlayerStat = Null;
	echo "<title>" . $DatabaseNotFound . "</title>";
	$Title = $DatabaseNotFound;
}else{
	$TypeText = (string)"Pro";$TitleType = $DynamicTitleLang['Pro'];
	$ACSQuery = (boolean)FALSE;/* The SQL Query must be Ascending Order and not Descending */
	$MaximumResult = (integer)0;
	$MinimumGP = (integer)0;
	$OrderByField = (string)"P";
	$OrderByFieldText = (string)"Points";
	$OrderByInput = (string)"";
	$TitleOverwrite = (string)"";
	if(isset($_GET['Farm'])){$TypeText = "Farm";$TitleType = $DynamicTitleLang['Farm'];$Active = 3;}
	if(isset($_GET['ACS'])){$ACSQuery= TRUE;}
	if(isset($_GET['Max'])){$MaximumResult = filter_var($_GET['Max'], FILTER_SANITIZE_NUMBER_INT);} 
	if(isset($_GET['Order'])){$OrderByInput  = filter_var($_GET['Order'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH);} 
	if(isset($_GET['Team'])){$Team = filter_var($_GET['Team'], FILTER_SANITIZE_NUMBER_INT);} 
	if(isset($_GET['Title'])){$TitleOverwrite  = filter_var($_GET['Title'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH);} 
	$LeagueName = (string)"";

	$PlayersStatPossibleOrderField = array(
	array("Name","Player Name"),
	array("GP","Games Played"),
	array("G","Goals"),
	array("A","Assists"),
	array("P","Points"),
	array("PlusMinus","Plus/Minus"),
	array("Pim","Penalty Minutes"),
	array("Pim5","Penalty Minutes for Major Penalty"),
	array("Hits","Hits"),
	array("HitsTook","Hits Received"),
	array("Shots","Shots"),
	array("OwnShotsBlock","Own Shots Block by others players"),
	array("OwnShotsMissGoal","Own Shots Miss the net"),
	array("ShotsPCT","Shooting Percentage"),
	array("ShotsBlock","Shots Blocked"),
	array("SecondPlay","Minutes Played"),
	array("AMG","Average Minutes Played per Game"),
	array("PPG","Power Play Goals"),
	array("PPA","Power Play Assists"),
	array("PPP","Power Play Points"),
	array("PPShots","Power Play Shots"),
	array("PPSecondPlay","Power Play Minutes Played"),
	array("PKG","Penalty Kill Goals"),
	array("PKA","Penalty Kill Assists"),
	array("PKP","Penalty Kill Points"),
	array("PKShots","Penalty Kill Shots"),
	array("PKSecondPlay","Penalty Kill Minutes Played"),
	array("GW","Game Winning Goals"),
	array("GT","Game Tying Goals"),
	array("FaceoffPCT","Face off Percentage"),
	array("FaceOffTotal","Face offs Taken"),
	array("GiveAway","Give Aways"),
	array("TakeAway","Take Aways"),
	array("EmptyNetGoal","Empty Net Goals"),
	array("HatTrick","Hat Tricks"),
	array("P20","Points per 20 Minutes"),
	array("PenalityShotsScore","Penalty Shots Goals"),
	array("PenalityShotsTotal","Penalty Shots Taken"),
	array("FightW","Fight Won"),
	array("FightL","Fight Lost"),
	array("FightT","Fight Ties"),
	array("Star1","Number of time players was star #1 in a game"),
	array("Star2","Number of time players was star #2 in a game"),
	array("Star3","Number of time players was star #3 in a game"),
	);
	foreach ($PlayersStatPossibleOrderField as $Value) {
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
		
	if(isset($_GET['MinGP'])){
		$Query = "Select " . $TypeText . "MinimumGamePlayerLeader AS MinimumGamePlayerLeader from LeagueOutputOption";
		$LeagueOutputOption = $db->querySingle($Query,true);	
		$MinimumGP = $LeagueOutputOption['MinimumGamePlayerLeader'];
	}
	
	If($MaximumResult == 0){$Title = $DynamicTitleLang['All'];}else{$Title = $DynamicTitleLang['Top'] . $MaximumResult . " ";}
	$Query = "SELECT Player" . $TypeText . "Stat.*, PlayerInfo.PosC, PlayerInfo.PosLW, PlayerInfo.PosRW, PlayerInfo.PosD, PlayerInfo.TeamName, ROUND((CAST(Player" . $TypeText . "Stat.G AS REAL) / (Player" . $TypeText . "Stat.Shots))*100,2) AS ShotsPCT, ROUND((CAST(Player" . $TypeText . "Stat.SecondPlay AS REAL) / 60 / (Player" . $TypeText . "Stat.GP)),2) AS AMG,ROUND((CAST(Player" . $TypeText . "Stat.FaceOffWon AS REAL) / (Player" . $TypeText . "Stat.FaceOffTotal))*100,2) as FaceoffPCT,ROUND((CAST(Player" . $TypeText . "Stat.P AS REAL) / (Player" . $TypeText . "Stat.SecondPlay) * 60 * 20),2) AS P20 FROM PlayerInfo INNER JOIN Player" . $TypeText . "Stat ON PlayerInfo.Number = Player" . $TypeText . "Stat.Number WHERE Player" . $TypeText . "Stat.GP > " . $MinimumGP;
	if($Team > 0){
		$Query = $Query . " AND Team = " . $Team;
		$QueryTeam = "SELECT Name FROM Team" . $TypeText . "Info WHERE Number = " . $Team;
		$TeamName = $db->querySingle($QueryTeam,true);	
		$Title = $Title . $TeamName['Name'];		
	}
	
	If ($OrderByField == "ShotsPCT" OR $OrderByField == "AMG" OR $OrderByField == "FaceoffPCT" OR $OrderByField == "P20"){$Query = $Query . " ORDER BY " . $OrderByField;}else{$Query = $Query . " ORDER BY Player" . $TypeText . "Stat." . $OrderByField;}
	$Title = $Title  . $DynamicTitleLang['PlayersStat'] . $TitleType;		
	If ($ACSQuery == TRUE){
		$Query = $Query . " ASC";
		$Title = $Title . $DynamicTitleLang['InAscendingOrderBy'] . $OrderByFieldText;
	}else{
		$Query = $Query . " DESC";
		$Title = $Title . $DynamicTitleLang['InDecendingOrderBy'] . $OrderByFieldText;
	}
	If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
	$PlayerStat = $db->query($Query);
	
	
	if(isset($_GET['MinGP'])){$Title = $Title . " - " . $TeamStatLang['MinimumGamesPlayed'] . $MinimumGP;}
	
	/* OverWrite Title if information is get from PHP GET */
	if($TitleOverwrite <> ""){$Title = $TitleOverwrite;}
	echo "<title>" . $LeagueName . " - " . $Title . "</title>";
}?>
</head><body>
<?php include "Menu.php";?>
<?php echo "<h1>" . $Title . "</h1>"; ?>
<script type="text/javascript">
$(function() {
  $.tablesorter.addWidget({ id: "numbering",format: function(table) {var c = table.config;$("tr:visible", table.tBodies[0]).each(function(i) {$(this).find('td').eq(0).text(i + 1);});}});
  $(".STHSPHPAllPlayerStat_Table").tablesorter({
	widgets: ['numbering', 'columnSelector', 'stickyHeaders', 'filter'],
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

<table class="tablesorter STHSPHPAllPlayerStat_Table"><thead><tr>
	<?php include "PlayersStatSub.php";?>
</tbody></table>
<br />
</div>

<?php include "Footer.php";?>
