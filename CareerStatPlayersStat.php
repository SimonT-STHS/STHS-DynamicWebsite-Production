<?php include "Header.php";
$Team = (integer)-1; /* -1 All Team */
$Title = (string)"";
$MimimumData = (integer)10;
$UpdateCareerStatDBV1 = (boolean)false;
$Search = (boolean)False;
If (file_exists($DatabaseFile) == false){
	Goto CareerStatPlayersStat;
}else{try{
	$TypeText = (string)"Pro";$TitleType = $DynamicTitleLang['Pro'];
	$ACSQuery = (boolean)FALSE;/* The SQL Query must be Ascending Order and not Descending */
	$PosF = (boolean)FALSE; $PosD = (boolean)FALSE;
	$Playoff = (string)"False";
	if(isset($_GET['PosF'])){$PosF= TRUE;}
	if(isset($_GET['PosD'])){$PosD= TRUE;}	
	$MaximumResult = (integer)0;
	$OrderByField = (string)"P";
	$OrderByFieldText = (string)"Points";
	$OrderByInput = (string)"";
	$TitleOverwrite = (string)"";
	$Year = (integer)0;	
	$TeamName = (string)"";
	if(isset($_GET['Farm'])){$TypeText = "Farm";$TitleType = $DynamicTitleLang['Farm'];}
	if(isset($_GET['ACS'])){$ACSQuery= TRUE;}
	if(isset($_GET['Playoff'])){$Playoff="True";$MimimumData=1;}
	if(isset($_GET['Max'])){$MaximumResult = filter_var($_GET['Max'], FILTER_SANITIZE_NUMBER_INT);} 
	if(isset($_GET['Order'])){$OrderByInput  = filter_var($_GET['Order'], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH || FILTER_FLAG_NO_ENCODE_QUOTES || FILTER_FLAG_STRIP_BACKTICK);} 
	if(isset($_GET['Title'])){$TitleOverwrite  = filter_var($_GET['Title'], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH || FILTER_FLAG_NO_ENCODE_QUOTES || FILTER_FLAG_STRIP_BACKTICK);} 
	if(isset($_GET['Year'])){$Year = filter_var($_GET['Year'], FILTER_SANITIZE_NUMBER_INT);} 	
	if(isset($_GET['TeamName'])){$TeamName = filter_var($_GET['TeamName'], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH || FILTER_FLAG_NO_ENCODE_QUOTES || FILTER_FLAG_STRIP_BACKTICK);}	
	$LeagueName = (string)"";

	include "SearchPossibleOrderField.php";
	
	foreach ($PlayersStatPossibleOrderField as $Value) {
		If (strtoupper($Value[0]) == strtoupper($OrderByInput)){
			$OrderByField = $Value[0];
			$OrderByFieldText = $Value[1];
			Break;
		}
	}
	
	$db = new SQLite3($DatabaseFile);
	$Query = "Select Name,PlayOffStarted,PreSeasonSchedule from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	
	If (file_exists($CareerStatDatabaseFile) == true){ /* CareerStat */
		$CareerStatdb = new SQLite3($CareerStatDatabaseFile);
		$CareerStatdb->query("ATTACH DATABASE '".realpath($DatabaseFile)."' AS CurrentDB");
		
		If ($Playoff=="True"){$Title = $SearchLang['Playoff'] .  " ";}
		$Title = $Title . $DynamicTitleLang['CareerStat'];
		If($PosF == True){$Title = $Title . $PlayersLang['Forward'] . " - ";}
		If($PosD == True){$Title = $Title . $PlayersLang['Defenseman'] . " - ";}			
		If ($TeamName != ""){$Title = $Title . $TeamName . " - ";}
		If ($Year > 0){$Title = $Title . $Year . " - ";}
		If($MaximumResult == 0){$Title = $Title . $DynamicTitleLang['All'];}else{$Title = $Title . $DynamicTitleLang['Top'] . $MaximumResult . " ";}
		
		$Query="SELECT MainTable.*, Player" . $TypeText . "Stat.*, PlayerInfo.NHLID, PlayerInfo.Country, PlayerInfo.TeamName,ROUND(CAST((MainTable.SumOfG + IfNull(Player" . $TypeText . "Stat.G,0)) AS REAL) / CAST((MainTable.SumOfShots + IfNull(Player" . $TypeText . "Stat.Shots,0)) AS REAL) *100,2) AS TotalShotsPCT,ROUND(CAST((MainTable.SumOfSecondPlay+ IfNull(Player" . $TypeText . "Stat.SecondPlay,0)) AS REAL) / 60 / CAST((MainTable.SumOfGP + IfNull(Player" . $TypeText . "Stat.GP,0)) AS REAL),2) AS TotalAMG, ROUND(CAST((MainTable.SumOfFaceOffWon + IfNull(Player" . $TypeText . "Stat.FaceOffWon,0)) AS REAL) / CAST((MainTable.SumOfFaceOffTotal + IfNull(Player" . $TypeText . "Stat.FaceOffTotal,0)) AS REAL) *100,2) as TotalFaceoffPCT, ROUND(CAST((MainTable.SumOfP+ IfNull(Player" . $TypeText . "Stat.P,0)) AS REAL) / CAST((MainTable.SumOfSecondPlay + IfNull(Player" . $TypeText . "Stat.SecondPlay,0)) AS REAL) * 60 * 20,2) AS TotalP20 FROM (SELECT Name AS SumOfName, UniqueID, Sum(Player" . $TypeText . "StatCareer.GP) AS SumOfGP, Sum(Player" . $TypeText . "StatCareer.Shots) AS SumOfShots, Sum(Player" . $TypeText . "StatCareer.G) AS SumOfG, Sum(Player" . $TypeText . "StatCareer.A) AS SumOfA, Sum(Player" . $TypeText . "StatCareer.P) AS SumOfP, Sum(Player" . $TypeText . "StatCareer.PlusMinus) AS SumOfPlusMinus, Sum(Player" . $TypeText . "StatCareer.Pim) AS SumOfPim, Sum(Player" . $TypeText . "StatCareer.Pim5) AS SumOfPim5, Sum(Player" . $TypeText . "StatCareer.ShotsBlock) AS SumOfShotsBlock, Sum(Player" . $TypeText . "StatCareer.OwnShotsBlock) AS SumOfOwnShotsBlock, Sum(Player" . $TypeText . "StatCareer.OwnShotsMissGoal) AS SumOfOwnShotsMissGoal, Sum(Player" . $TypeText . "StatCareer.Hits) AS SumOfHits, Sum(Player" . $TypeText . "StatCareer.HitsTook) AS SumOfHitsTook, Sum(Player" . $TypeText . "StatCareer.GW) AS SumOfGW, Sum(Player" . $TypeText . "StatCareer.GT) AS SumOfGT, Sum(Player" . $TypeText . "StatCareer.FaceOffWon) AS SumOfFaceOffWon, Sum(Player" . $TypeText . "StatCareer.FaceOffTotal) AS SumOfFaceOffTotal, Sum(Player" . $TypeText . "StatCareer.PenalityShotsScore) AS SumOfPenalityShotsScore, Sum(Player" . $TypeText . "StatCareer.PenalityShotsTotal) AS SumOfPenalityShotsTotal, Sum(Player" . $TypeText . "StatCareer.EmptyNetGoal) AS SumOfEmptyNetGoal, Sum(Player" . $TypeText . "StatCareer.SecondPlay) AS SumOfSecondPlay, Sum(Player" . $TypeText . "StatCareer.HatTrick) AS SumOfHatTrick, Sum(Player" . $TypeText . "StatCareer.PPG) AS SumOfPPG, Sum(Player" . $TypeText . "StatCareer.PPA) AS SumOfPPA, Sum(Player" . $TypeText . "StatCareer.PPP) AS SumOfPPP, Sum(Player" . $TypeText . "StatCareer.PPShots) AS SumOfPPShots, Sum(Player" . $TypeText . "StatCareer.PPSecondPlay) AS SumOfPPSecondPlay, Sum(Player" . $TypeText . "StatCareer.PKG) AS SumOfPKG, Sum(Player" . $TypeText . "StatCareer.PKA) AS SumOfPKA, Sum(Player" . $TypeText . "StatCareer.PKP) AS SumOfPKP, Sum(Player" . $TypeText . "StatCareer.PKShots) AS SumOfPKShots, Sum(Player" . $TypeText . "StatCareer.PKSecondPlay) AS SumOfPKSecondPlay, Sum(Player" . $TypeText . "StatCareer.GiveAway) AS SumOfGiveAway, Sum(Player" . $TypeText . "StatCareer.TakeAway) AS SumOfTakeAway, Sum(Player" . $TypeText . "StatCareer.PuckPossesionTime) AS SumOfPuckPossesionTime, Sum(Player" . $TypeText . "StatCareer.FightW) AS SumOfFightW, Sum(Player" . $TypeText . "StatCareer.FightL) AS SumOfFightL, Sum(Player" . $TypeText . "StatCareer.FightT) AS SumOfFightT, Sum(Player" . $TypeText . "StatCareer.Star1) AS SumOfStar1, Sum(Player" . $TypeText . "StatCareer.Star2) AS SumOfStar2, Sum(Player" . $TypeText . "StatCareer.Star3) AS SumOfStar3, ROUND((CAST(Sum(Player" . $TypeText . "StatCareer.G) AS REAL) / (Sum(Player" . $TypeText . "StatCareer.Shots)))*100,2) AS SumOfShotsPCT, ROUND((CAST(Sum(Player" . $TypeText . "StatCareer.SecondPlay) AS REAL) / 60 / (Sum(Player" . $TypeText . "StatCareer.GP))),2) AS SumOfAMG, ROUND((CAST(Sum(Player" . $TypeText . "StatCareer.FaceOffWon) AS REAL) / (Sum(Player" . $TypeText . "StatCareer.FaceOffTotal)))*100,2) as SumOfFaceoffPCT, ROUND((CAST(Sum(Player" . $TypeText . "StatCareer.P) AS REAL) / (Sum(Player" . $TypeText . "StatCareer.SecondPlay)) * 60 * 20),2) AS SumOfP20 FROM Player" . $TypeText . "StatCareer WHERE Playoff = \"" . $Playoff . "\"";
		
		If($Year > 0){$Query = $Query . " AND Player" . $TypeText . "StatCareer.Year = \"" . $Year . "\"";}
		If($TeamName != ""){$Query = $Query . " AND Player" . $TypeText . "StatCareer.TeamName = \"" . $TeamName . "\"";}
		If($PosF == True){$Query = $Query . " AND (Player" . $TypeText . "StatCareer.PosC = \"True\" OR Player" . $TypeText . "StatCareer.PosLW = \"True\" OR Player" . $TypeText . "StatCareer.PosRW = \"True\")";}		
		If($PosD == True){$Query = $Query . " AND Player" . $TypeText . "StatCareer.PosD = \"True\"";}		
		If($Year > 0 OR $LeagueGeneral['PlayOffStarted'] != $Playoff OR $TeamName != ""){
			$Query = $Query . " GROUP BY Player" . $TypeText . "StatCareer.Name) AS MainTable LEFT JOIN Player" . $TypeText . "Stat ON MainTable.SumOfName = Player" . $TypeText . "Stat.Name LEFT JOIN PlayerInfo ON MainTable.SumOfName = PlayerInfo.Name ORDER BY (MainTable.SumOf".$OrderByField.") ";
		}elseif($OrderByField == "AMG" OR $OrderByField == "P20"){
			$Query = $Query . " GROUP BY Player" . $TypeText . "StatCareer.Name) AS MainTable LEFT JOIN Player" . $TypeText . "Stat ON MainTable.SumOfName = Player" . $TypeText . "Stat.Name LEFT JOIN PlayerInfo ON MainTable.SumOfName = PlayerInfo.Name WHERE SumOfP >= " . ($MimimumData *  5) . " ORDER BY Total".$OrderByField." ";
		}elseif($OrderByField == "FaceoffPCT"){
			$Query = $Query . " GROUP BY Player" . $TypeText . "StatCareer.Name) AS MainTable LEFT JOIN Player" . $TypeText . "Stat ON MainTable.SumOfName = Player" . $TypeText . "Stat.Name LEFT JOIN PlayerInfo ON MainTable.SumOfName = PlayerInfo.Name WHERE SumOfFaceOffTotal >= " . ($MimimumData *  10) . " ORDER BY Total".$OrderByField." ";			
		}elseif($OrderByField == "ShotsPCT"){
			$Query = $Query . " GROUP BY Player" . $TypeText . "StatCareer.Name) AS MainTable LEFT JOIN Player" . $TypeText . "Stat ON MainTable.SumOfName = Player" . $TypeText . "Stat.Name LEFT JOIN PlayerInfo ON MainTable.SumOfName = PlayerInfo.Name WHERE SumOfShots >= " . ($MimimumData *  10) . " ORDER BY Total".$OrderByField." ";			
		}else{
			$Query = $Query . " GROUP BY Player" . $TypeText . "StatCareer.Name) AS MainTable LEFT JOIN Player" . $TypeText . "Stat ON MainTable.SumOfName = Player" . $TypeText . "Stat.Name LEFT JOIN PlayerInfo ON MainTable.SumOfName = PlayerInfo.Name ORDER BY (MainTable.SumOf".$OrderByField." + IfNull(Player" . $TypeText . "Stat.".$OrderByField.",0)) ";
		}
		
		$Title = $Title  . $DynamicTitleLang['PlayersStat'] . $TitleType;	
		
		If ($ACSQuery == TRUE){
			$Query = $Query . " ASC";
			$Title = $Title . $DynamicTitleLang['InAscendingOrderBy'] . $OrderByFieldText;
		}else{
			$Query = $Query . " DESC";
			$Title = $Title . $DynamicTitleLang['InDecendingOrderBy'] . $OrderByFieldText;
		}
		$Query = $Query . ", (MainTable.SumOfGP + IfNull(Player" . $TypeText . "Stat.GP,0)) ASC";
		If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
		$CareerPlayerStat = $CareerStatdb->query($Query);	

		include "SearchCareerSub.php";		
	}else{
		$CareerPlayerStat = Null;
		$Title = $CareeratabaseNotFound;
	}			
	
	/* OverWrite Title if information is get from PHP GET */
	if($TitleOverwrite <> ""){$Title = $TitleOverwrite;}
	echo "<title>" . $LeagueName . " - " . $Title . "</title>";
} catch (Exception $e) {
CareerStatPlayersStat:
	$LeagueName = $DatabaseNotFound;
	$CareerPlayerStat = Null;
	echo "<title>" . $DatabaseNotFound . "</title>";
	$Title = $DatabaseNotFound;
	$TeamName = Null;
}}
?>
</head><body>
<?php include "Menu.php";?>
<script>
$(function() {
  $.tablesorter.addWidget({ id: "numbering",format: function(table) {var c = table.config;$("tr:visible", table.tBodies[0]).each(function(i) {$(this).find('td').eq(0).text(i + 1);});}});
  $(".STHSPHPAllPlayerStat_Table").tablesorter({
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
	  filter_searchDelay : 1000,	  
      filter_reset: '.tablesorter_Reset',	 
	  output_delivery: 'd',
	  output_saveFileName: 'STHSCareerStatPlayerStat.CSV'
    }
  });
  $('.download').click(function(){
      var $table = $('.STHSPHPAllPlayerStat_Table'),
      wo = $table[0].config.widgetOptions;
      $table.trigger('outputTable');
      return false;
  });  
});
</script>

<div style="width:99%;margin:auto;">
<?php echo "<h1>" . $Title . "</h1>"; ?>
<div id="ReQueryDiv" style="display:none;">
<?php if($LeagueName != $DatabaseNotFound){include "SearchCareerStatPlayersStat.php";}?>
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

<table class="tablesorter STHSPHPAllPlayerStat_Table"><thead><tr>
<th data-priority="3" title="Order Number" class="STHSW10 sorter-false">#</th>
<th data-priority="critical" title="Player Name" class="STHSW140Min"><?php echo $PlayersLang['PlayerName'];?></th>
<th data-priority="1" title="Games Played" class="STHSW25">GP</th>
<th data-priority="1" title="Goals" class="STHSW25">G</th>
<th data-priority="1" title="Assists" class="STHSW25">A</th>
<th data-priority="1" title="Points" class="STHSW25">P</th>
<th data-priority="2" title="Plus/Minus" class="STHSW25">+/-</th>
<th data-priority="2" title="Penalty Minutes" class="STHSW25">PIM</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Penalty Minutes for Major Penalty">PIM5</th>
<th data-priority="2" title="Hits" class="STHSW25">HIT</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Hits Received">HTT</th>
<th data-priority="2" title="Shots" class="STHSW25">SHT</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Own Shots Block by others players">OSB</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Own Shots Miss the net">OSM</th>
<th data-priority="3" title="Shooting Percentage" class="STHSW55">SHT%</th>
<th data-priority="3" title="Shots Blocked" class="STHSW25">SB</th>
<th data-priority="3" title="Minutes Played" class="STHSW35">MP</th>
<th data-priority="3" title="Average Minutes Played per Game" class="STHSW35">AMG</th>
<th data-priority="4" title="Power Play Goals" class="STHSW25">PPG</th>
<th data-priority="4" title="Power Play Assists" class="STHSW25">PPA</th>
<th data-priority="4" title="Power Play Points" class="STHSW25">PPP</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Power Play Shots">PPS</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Power Play Minutes Played">PPM</th>
<th data-priority="5" title="Short Handed Goals" class="STHSW25">PKG</th>
<th data-priority="5" title="Short Handed Assists" class="STHSW25">PKA</th>
<th data-priority="5" title="Short Handed Points" class="STHSW25">PKP</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Penalty Kill Shots">PKS</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Penalty Kill Minutes Played">PKM</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Game Winning Goals">GW</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Game Tying Goals">GT</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Face offs Percentage">FO%</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Face offs Taken">FOT</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Give Aways">GA</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Take Aways">TA</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Empty Net Goals">EG</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Hat Tricks">HT</th>
<th data-priority="4" title="Points per 20 Minutes" class="STHSW25">P/20</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Penalty Shots Goals">PSG</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Penalty Shots Taken">PSS</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Fight Won">FW</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Fight Lost">FL</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Fight Ties">FT</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Number of time players was star #1 in a game">S1</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Number of time players was star #2 in a game">S2</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Number of time players was star #3 in a game">S3</th>
</tr></thead><tbody>
<?php 
$Order = 0;
if (empty($CareerPlayerStat) == false){while ($Row = $CareerPlayerStat ->fetchArray()) {
	$Order +=1;
	echo "<tr><td>" . $Order ."</td>";
	If ($Row['Number'] > 0){
		echo "<td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . "</a></td>";
	}else{
		echo "<td><a href=\"CareerStatPlayerReport.php?Player=" . $Row['SumOfName'] . "\">" . $Row['SumOfName'] . "*</a></td>";
	}	
	If ($Year == 0 AND $LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False" AND $TeamName == ""){
		echo "<td>" . ($Row['SumOfGP'] + $Row['GP']) . "</td>";
		echo "<td>" . ($Row['SumOfG'] + $Row['G']) . "</td>";
		echo "<td>" . ($Row['SumOfA'] + $Row['A']) . "</td>";
		echo "<td>" . ($Row['SumOfP'] + $Row['P']) . "</td>";
		echo "<td>" . ($Row['SumOfPlusMinus'] + $Row['PlusMinus']) . "</td>";
		echo "<td>" . ($Row['SumOfPim'] + $Row['Pim']) . "</td>";
		echo "<td>" . ($Row['SumOfPim5'] + $Row['Pim5']) . "</td>";
		echo "<td>" . ($Row['SumOfHits'] + $Row['Hits']) . "</td>";	
		echo "<td>" . ($Row['SumOfHitsTook'] + $Row['HitsTook']) . "</td>";		
		echo "<td>" . ($Row['SumOfShots'] + $Row['Shots']) . "</td>";
		echo "<td>" . ($Row['SumOfOwnShotsBlock'] + $Row['OwnShotsBlock']) . "</td>";
		echo "<td>" . ($Row['SumOfOwnShotsMissGoal'] + $Row['OwnShotsMissGoal']) . "</td>";
		If ($Row['TotalShotsPCT'] == Null){echo "<td>0%</td>";}else{echo "<td>" . number_Format($Row['TotalShotsPCT'],2) . "%</td>";}		
		echo "<td>" . ($Row['SumOfShotsBlock'] + $Row['ShotsBlock']) . "</td>";	
		echo "<td>" . Floor(($Row['SumOfSecondPlay'] + $Row['SecondPlay'])/60) . "</td>";
		If ($Row['TotalAMG'] == Null){echo "<td>0</td>";}else{echo "<td>" . number_Format($Row['TotalAMG'],2) . "</td>";}
		echo "<td>" . ($Row['SumOfPPG'] + $Row['PPG']) . "</td>";
		echo "<td>" . ($Row['SumOfPPA'] + $Row['PPA']) . "</td>";
		echo "<td>" . ($Row['SumOfPPP'] + $Row['PPP']) . "</td>";
		echo "<td>" . ($Row['SumOfPPShots'] + $Row['PPShots']) . "</td>";
		echo "<td>" . Floor(($Row['SumOfPPSecondPlay'] + $Row['PPSecondPlay'])/60) . "</td>";
		echo "<td>" . ($Row['SumOfPKG'] + $Row['PKG']) . "</td>";
		echo "<td>" . ($Row['SumOfPKA'] + $Row['PKA']) . "</td>";
		echo "<td>" . ($Row['SumOfPKP'] + $Row['PKP']) . "</td>";
		echo "<td>" . ($Row['SumOfPKShots'] + $Row['PKShots']) . "</td>";
		echo "<td>" . Floor(($Row['SumOfPKSecondPlay'] + $Row['PKSecondPlay'])/60) . "</td>";	
		echo "<td>" . ($Row['SumOfGW'] + $Row['GW']) . "</td>";
		echo "<td>" . ($Row['SumOfGT'] + $Row['GT']) . "</td>";
		If ($Row['TotalFaceoffPCT'] == Null){echo "<td>0%</td>";}else{echo "<td>" . number_Format($Row['TotalFaceoffPCT'],2) . "%</td>";}
		echo "<td>" . ($Row['SumOfFaceOffTotal'] + $Row['FaceOffTotal']) . "</td>";
		echo "<td>" . ($Row['SumOfGiveAway'] + $Row['GiveAway']) . "</td>";
		echo "<td>" . ($Row['SumOfTakeAway'] + $Row['TakeAway']) . "</td>";
		echo "<td>" . ($Row['SumOfEmptyNetGoal'] + $Row['EmptyNetGoal']) . "</td>";
		echo "<td>" . ($Row['SumOfHatTrick'] + $Row['HatTrick']) . "</td>";	
		If ($Row['TotalP20'] == Null){echo "<td>0.00</td>";}else{echo "<td>" . number_Format($Row['TotalP20'],2) . "</td>";}		
		echo "<td>" . ($Row['SumOfPenalityShotsScore'] + $Row['PenalityShotsScore']) . "</td>";
		echo "<td>" . ($Row['SumOfPenalityShotsTotal'] + $Row['PenalityShotsScore']) . "</td>";
		echo "<td>" . ($Row['SumOfFightW'] + $Row['FightW']) . "</td>";
		echo "<td>" . ($Row['SumOfFightL'] + $Row['FightL']) . "</td>";
		echo "<td>" . ($Row['SumOfFightT'] + $Row['FightT']) . "</td>";
		echo "<td>" . ($Row['SumOfStar1'] + $Row['Star1']) . "</td>";
		echo "<td>" . ($Row['SumOfStar2'] + $Row['Star2']) . "</td>";
		echo "<td>" . ($Row['SumOfStar3'] + $Row['Star3']) . "</td>";
		echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
	}else{
		echo "<td>" . $Row['SumOfGP'] . "</td>";
		echo "<td>" . $Row['SumOfG'] . "</td>";
		echo "<td>" . $Row['SumOfA'] . "</td>";
		echo "<td>" . $Row['SumOfP'] . "</td>";
		echo "<td>" . $Row['SumOfPlusMinus'] . "</td>";
		echo "<td>" . $Row['SumOfPim'] . "</td>";
		echo "<td>" . $Row['SumOfPim5'] . "</td>";
		echo "<td>" . $Row['SumOfHits'] . "</td>";	
		echo "<td>" . $Row['SumOfHitsTook'] . "</td>";		
		echo "<td>" . $Row['SumOfShots'] . "</td>";
		echo "<td>" . $Row['SumOfOwnShotsBlock'] . "</td>";
		echo "<td>" . $Row['SumOfOwnShotsMissGoal'] . "</td>";
		If ($Row['SumOfShotsPCT'] == Null){echo "<td>0%</td>";}else{echo "<td>" . number_Format($Row['SumOfShotsPCT'],2) . "%</td>";}		
		echo "<td>" . $Row['SumOfShotsBlock'] . "</td>";	
		echo "<td>" . Floor($Row['SumOfSecondPlay']/60) . "</td>";
		If ($Row['SumOfAMG'] == Null){echo "<td>0</td>";}else{echo "<td>" . number_Format($Row['SumOfAMG'],2) . "</td>";}		
		echo "<td>" . $Row['SumOfPPG'] . "</td>";
		echo "<td>" . $Row['SumOfPPA'] . "</td>";
		echo "<td>" . $Row['SumOfPPP'] . "</td>";
		echo "<td>" . $Row['SumOfPPShots'] . "</td>";
		echo "<td>" . Floor($Row['SumOfPPSecondPlay']/60) . "</td>";	
		echo "<td>" . $Row['SumOfPKG'] . "</td>";
		echo "<td>" . $Row['SumOfPKA'] . "</td>";
		echo "<td>" . $Row['SumOfPKP'] . "</td>";
		echo "<td>" . $Row['SumOfPKShots'] . "</td>";
		echo "<td>" . Floor($Row['SumOfPKSecondPlay']/60) . "</td>";	
		echo "<td>" . $Row['SumOfGW'] . "</td>";
		echo "<td>" . $Row['SumOfGT'] . "</td>";
		If ($Row['SumOfFaceoffPCT'] == Null){echo "<td>0%</td>";}else{echo "<td>" . number_Format($Row['SumOfFaceoffPCT'],2) . "%</td>";}
		echo "<td>" . $Row['SumOfFaceOffTotal'] . "</td>";
		echo "<td>" . $Row['SumOfGiveAway'] . "</td>";
		echo "<td>" . $Row['SumOfTakeAway'] . "</td>";
		echo "<td>" . $Row['SumOfEmptyNetGoal'] . "</td>";
		echo "<td>" . $Row['SumOfHatTrick'] . "</td>";	
		If ($Row['SumOfP20'] == Null){echo "<td>0</td>";}else{echo "<td>" . number_Format($Row['SumOfP20'],2) . "</td>";}		
		echo "<td>" . $Row['SumOfPenalityShotsScore'] . "</td>";
		echo "<td>" . $Row['SumOfPenalityShotsTotal'] . "</td>";
		echo "<td>" . $Row['SumOfFightW'] . "</td>";
		echo "<td>" . $Row['SumOfFightL'] . "</td>";
		echo "<td>" . $Row['SumOfFightT'] . "</td>";
		echo "<td>" . $Row['SumOfStar1'] . "</td>";
		echo "<td>" . $Row['SumOfStar2'] . "</td>";
		echo "<td>" . $Row['SumOfStar3'] . "</td>";
		echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
	}
}}
?>
</tbody></table>
<br />
</div>
<em><?php 
echo $PlayersLang['CareerNote'];
If ($TeamName != ""){echo $PlayersLang['CareerTeamNote'];}
?></em><br />
<?php include "Footer.php";?>
