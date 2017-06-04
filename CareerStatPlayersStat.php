<!DOCTYPE html>
<?php include "Header.php";?>
<?php
$Team = (integer)-1; /* -1 All Team */
$Title = (string)"";
$Active = 2; /* Show Webpage Top Menu */
If (file_exists($DatabaseFile) == false){
	$LeagueName = $DatabaseNotFound;
	$CareerPlayerStat = Null;
	echo "<title>" . $DatabaseNotFound . "</title>";
	$Title = $DatabaseNotFound;
}else{
	$TypeText = (string)"Pro";$TitleType = $DynamicTitleLang['Pro'];
	$ACSQuery = (boolean)FALSE;/* The SQL Query must be Ascending Order and not Descending */
	$Playoff = (string)"False";
	$MaximumResult = (integer)0;
	$OrderByField = (string)"P";
	$OrderByFieldText = (string)"Points";
	$OrderByInput = (string)"";
	$TitleOverwrite = (string)"";
	$Year = (integer)0;	
	if(isset($_GET['Farm'])){$TypeText = "Farm";$TitleType = $DynamicTitleLang['Farm'];$Active = 3;}
	if(isset($_GET['ACS'])){$ACSQuery= TRUE;}
	if(isset($_GET['Playoff'])){$Playoff="True";}
	if(isset($_GET['Max'])){$MaximumResult = filter_var($_GET['Max'], FILTER_SANITIZE_NUMBER_INT);} 
	if(isset($_GET['Order'])){$OrderByInput  = filter_var($_GET['Order'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH);} 
	if(isset($_GET['Title'])){$TitleOverwrite  = filter_var($_GET['Title'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH);} 
	if(isset($_GET['Year'])){$Year = filter_var($_GET['Year'], FILTER_SANITIZE_NUMBER_INT);} 	
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
	
	If (file_exists($CareerStatDatabaseFile) == true){ /* CareerStat */
		$CareerStatdb = new SQLite3($CareerStatDatabaseFile);
		
		If($MaximumResult == 0){$Title = $DynamicTitleLang['CareerStat'] . $DynamicTitleLang['All'];}else{$Title = $DynamicTitleLang['CareerStat'] . $DynamicTitleLang['Top'] . $MaximumResult . " ";}
		
		$Query = "SELECT Name AS SumOfName, UniqueID, Sum(Player" . $TypeText . "StatCareer.GP) AS SumOfGP, Sum(Player" . $TypeText . "StatCareer.Shots) AS SumOfShots, Sum(Player" . $TypeText . "StatCareer.G) AS SumOfG, Sum(Player" . $TypeText . "StatCareer.A) AS SumOfA, Sum(Player" . $TypeText . "StatCareer.P) AS SumOfP, Sum(Player" . $TypeText . "StatCareer.PlusMinus) AS SumOfPlusMinus, Sum(Player" . $TypeText . "StatCareer.Pim) AS SumOfPim, Sum(Player" . $TypeText . "StatCareer.Pim5) AS SumOfPim5, Sum(Player" . $TypeText . "StatCareer.ShotsBlock) AS SumOfShotsBlock, Sum(Player" . $TypeText . "StatCareer.OwnShotsBlock) AS SumOfOwnShotsBlock, Sum(Player" . $TypeText . "StatCareer.OwnShotsMissGoal) AS SumOfOwnShotsMissGoal, Sum(Player" . $TypeText . "StatCareer.Hits) AS SumOfHits, Sum(Player" . $TypeText . "StatCareer.HitsTook) AS SumOfHitsTook, Sum(Player" . $TypeText . "StatCareer.GW) AS SumOfGW, Sum(Player" . $TypeText . "StatCareer.GT) AS SumOfGT, Sum(Player" . $TypeText . "StatCareer.FaceOffWon) AS SumOfFaceOffWon, Sum(Player" . $TypeText . "StatCareer.FaceOffTotal) AS SumOfFaceOffTotal, Sum(Player" . $TypeText . "StatCareer.PenalityShotsScore) AS SumOfPenalityShotsScore, Sum(Player" . $TypeText . "StatCareer.PenalityShotsTotal) AS SumOfPenalityShotsTotal, Sum(Player" . $TypeText . "StatCareer.EmptyNetGoal) AS SumOfEmptyNetGoal, Sum(Player" . $TypeText . "StatCareer.SecondPlay) AS SumOfSecondPlay, Sum(Player" . $TypeText . "StatCareer.HatTrick) AS SumOfHatTrick, Sum(Player" . $TypeText . "StatCareer.PPG) AS SumOfPPG, Sum(Player" . $TypeText . "StatCareer.PPA) AS SumOfPPA, Sum(Player" . $TypeText . "StatCareer.PPP) AS SumOfPPP, Sum(Player" . $TypeText . "StatCareer.PPShots) AS SumOfPPShots, Sum(Player" . $TypeText . "StatCareer.PPSecondPlay) AS SumOfPPSecondPlay, Sum(Player" . $TypeText . "StatCareer.PKG) AS SumOfPKG, Sum(Player" . $TypeText . "StatCareer.PKA) AS SumOfPKA, Sum(Player" . $TypeText . "StatCareer.PKP) AS SumOfPKP, Sum(Player" . $TypeText . "StatCareer.PKShots) AS SumOfPKShots, Sum(Player" . $TypeText . "StatCareer.PKSecondPlay) AS SumOfPKSecondPlay, Sum(Player" . $TypeText . "StatCareer.GiveAway) AS SumOfGiveAway, Sum(Player" . $TypeText . "StatCareer.TakeAway) AS SumOfTakeAway, Sum(Player" . $TypeText . "StatCareer.PuckPossesionTime) AS SumOfPuckPossesionTime, Sum(Player" . $TypeText . "StatCareer.FightW) AS SumOfFightW, Sum(Player" . $TypeText . "StatCareer.FightL) AS SumOfFightL, Sum(Player" . $TypeText . "StatCareer.FightT) AS SumOfFightT, Sum(Player" . $TypeText . "StatCareer.Star1) AS SumOfStar1, Sum(Player" . $TypeText . "StatCareer.Star2) AS SumOfStar2, Sum(Player" . $TypeText . "StatCareer.Star3) AS SumOfStar3, ROUND((CAST(Sum(Player" . $TypeText . "StatCareer.G) AS REAL) / (Sum(Player" . $TypeText . "StatCareer.Shots)))*100,2) AS SumOfShotsPCT, ROUND((CAST(Sum(Player" . $TypeText . "StatCareer.SecondPlay) AS REAL) / 60 / (Sum(Player" . $TypeText . "StatCareer.GP))),2) AS SumOfAMG, ROUND((CAST(Sum(Player" . $TypeText . "StatCareer.FaceOffWon) AS REAL) / (Sum(Player" . $TypeText . "StatCareer.FaceOffTotal)))*100,2) as SumOfFaceoffPCT, ROUND((CAST(Sum(Player" . $TypeText . "StatCareer.P) AS REAL) / (Sum(Player" . $TypeText . "StatCareer.SecondPlay)) * 60 * 20),2) AS SumOfP20 FROM Player" . $TypeText . "StatCareer WHERE Playoff = '" . $Playoff . "'";
		If($Year > 0){$Query = $Query . " AND YEAR = '" . $Year . "'";}
		$Query = $Query . " GROUP BY Player" . $TypeText . "StatCareer.UniqueID ORDER BY SumOf" . $OrderByField;
			
		$Title = $Title  . $DynamicTitleLang['PlayersStat'] . $TitleType;	
		
		If ($ACSQuery == TRUE){
			$Query = $Query . " ASC";
			$Title = $Title . $DynamicTitleLang['InAscendingOrderBy'] . $OrderByFieldText;
		}else{
			$Query = $Query . " DESC";
			$Title = $Title . $DynamicTitleLang['InDecendingOrderBy'] . $OrderByFieldText;
		}
		If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
		$CareerPlayerStat = $CareerStatdb->query($Query);		
	}else{
		$CareerPlayerStat = Null;
		$Title = $CareeratabaseNotFound;
	}			
	
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
      columnSelector_breakpoints : [ '20em', '40em', '60em', '80em', '90em', '95em' ],
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
	
	$Query = "SELECT * FROM Player" . $TypeText . "Stat WHERE UniqueID = " . $Row['UniqueID'];
	$PlayerStat = Null;
	$PlayerStat = $db->querySingle($Query,true);
	echo "<tr><td>" . $Order ."</td>";
	
	if ($PlayerStat <> Null){
		echo "<td><a href=\"PlayerReport.php?Player=" . $PlayerStat['Number'] . "\">" . $PlayerStat['Name'] . "</a></td>";
		echo "<td>" . ($Row['SumOfGP'] + $PlayerStat['GP']) . "</td>";
		echo "<td>" . ($Row['SumOfG'] + $PlayerStat['G']) . "</td>";
		echo "<td>" . ($Row['SumOfA'] + $PlayerStat['A']) . "</td>";
		echo "<td>" . ($Row['SumOfP'] + $PlayerStat['P']) . "</td>";
		echo "<td>" . ($Row['SumOfPlusMinus'] + $PlayerStat['PlusMinus']) . "</td>";
		echo "<td>" . ($Row['SumOfPim'] + $PlayerStat['Pim']) . "</td>";
		echo "<td>" . ($Row['SumOfPim5'] + $PlayerStat['Pim5']) . "</td>";
		echo "<td>" . ($Row['SumOfHits'] + $PlayerStat['Hits']) . "</td>";	
		echo "<td>" . ($Row['SumOfHitsTook'] + $PlayerStat['HitsTook']) . "</td>";		
		echo "<td>" . ($Row['SumOfShots'] + $PlayerStat['Shots']) . "</td>";
		echo "<td>" . ($Row['SumOfOwnShotsBlock'] + $PlayerStat['OwnShotsBlock']) . "</td>";
		echo "<td>" . ($Row['SumOfOwnShotsMissGoal'] + $PlayerStat['OwnShotsMissGoal']) . "</td>";
		echo "<td>"; #ShotPCT
		if (($Row['SumOfShots'] + $PlayerStat['Shots']) > "0"){echo sprintf("%.2f%%", ($Row['SumOfG'] + $PlayerStat['G']) / ($Row['SumOfShots'] + $PlayerStat['Shots']) *100 ); } else {echo "0%";}
		echo "</td>";
		echo "<td>" . ($Row['SumOfShotsBlock'] + $PlayerStat['ShotsBlock']) . "</td>";	
		echo "<td>" . Floor(($Row['SumOfSecondPlay'] + $PlayerStat['SecondPlay'])/60) . "</td>";
		echo "<td>"; #AMG
		if (($Row['SumOfGP'] + $PlayerStat['GP']) > "0"){echo number_format(($Row['SumOfSecondPlay'] + $PlayerStat['SecondPlay']) / 60 / ($Row['SumOfGP'] + $PlayerStat['GP']) ,2 ); } else {echo "0";}
		echo "</td>";	
		echo "<td>" . ($Row['SumOfPPG'] + $PlayerStat['PPG']) . "</td>";
		echo "<td>" . ($Row['SumOfPPA'] + $PlayerStat['PPA']) . "</td>";
		echo "<td>" . ($Row['SumOfPPP'] + $PlayerStat['PPP']) . "</td>";
		echo "<td>" . ($Row['SumOfPPShots'] + $PlayerStat['PPShots']) . "</td>";
		echo "<td>" . Floor(($Row['SumOfPPSecondPlay'] + $PlayerStat['PPSecondPlay'])/60) . "</td>";
		echo "<td>" . ($Row['SumOfPKG'] + $PlayerStat['PKG']) . "</td>";
		echo "<td>" . ($Row['SumOfPKA'] + $PlayerStat['PKA']) . "</td>";
		echo "<td>" . ($Row['SumOfPKP'] + $PlayerStat['PKP']) . "</td>";
		echo "<td>" . ($Row['SumOfPKShots'] + $PlayerStat['PKShots']) . "</td>";
		echo "<td>" . Floor(($Row['SumOfPKSecondPlay'] + $PlayerStat['PKSecondPlay'])/60) . "</td>";	
		echo "<td>" . ($Row['SumOfGW'] + $PlayerStat['GW']) . "</td>";
		echo "<td>" . ($Row['SumOfGT'] + $PlayerStat['GT']) . "</td>";
		echo "<td>"; #FaceoffPCT
		if (($Row['SumOfFaceOffTotal'] + $PlayerStat['FaceOffTotal']) > "0"){echo sprintf("%.2f%%", ($Row['SumOfFaceOffWon'] + $PlayerStat['FaceOffWon']) / ($Row['SumOfFaceOffTotal'] + $PlayerStat['FaceOffTotal']) *100 ); } else {echo "0%";}
		echo "</td>";
		echo "<td>" . ($Row['SumOfFaceOffTotal'] + $PlayerStat['FaceOffTotal']) . "</td>";
		echo "<td>" . ($Row['SumOfGiveAway'] + $PlayerStat['GiveAway']) . "</td>";
		echo "<td>" . ($Row['SumOfTakeAway'] + $PlayerStat['TakeAway']) . "</td>";
		echo "<td>" . ($Row['SumOfEmptyNetGoal'] + $PlayerStat['EmptyNetGoal']) . "</td>";
		echo "<td>" . ($Row['SumOfHatTrick'] + $PlayerStat['HatTrick']) . "</td>";	
		echo "<td>"; #P20
		if (($Row['SumOfSecondPlay'] + $PlayerStat['SecondPlay']) > "60"){echo number_format( ($Row['SumOfP'] +  $PlayerStat['P']) / ($Row['SumOfSecondPlay'] + $PlayerStat['SecondPlay']) * 60 * 20,2 ); } else {echo "0";}
		echo "</td>";
		echo "<td>" . ($Row['SumOfPenalityShotsScore'] + $PlayerStat['PenalityShotsScore']) . "</td>";
		echo "<td>" . ($Row['SumOfPenalityShotsTotal'] + $PlayerStat['PenalityShotsScore']) . "</td>";
		echo "<td>" . ($Row['SumOfFightW'] + $PlayerStat['FightW']) . "</td>";
		echo "<td>" . ($Row['SumOfFightL'] + $PlayerStat['FightL']) . "</td>";
		echo "<td>" . ($Row['SumOfFightT'] + $PlayerStat['FightT']) . "</td>";
		echo "<td>" . ($Row['SumOfStar1'] + $PlayerStat['Star1']) . "</td>";
		echo "<td>" . ($Row['SumOfStar2'] + $PlayerStat['Star2']) . "</td>";
		echo "<td>" . ($Row['SumOfStar3'] + $PlayerStat['Star3']) . "</td>";
		echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
		
	}else{
		echo "<td>" . $Row['SumOfName'] . "</td>";	
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
		echo "<td>" . number_Format($Row['SumOfShotsPCT'],2) . "%</td>";		
		echo "<td>" . $Row['SumOfShotsBlock'] . "</td>";	
		echo "<td>" . Floor($Row['SumOfSecondPlay']/60) . "</td>";
		echo "<td>" . number_Format($Row['SumOfAMG'],2) . "</td>";		
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
		echo "<td>" . number_Format($Row['SumOfFaceoffPCT'],2) . "%</td>";	
		echo "<td>" . $Row['SumOfFaceOffTotal'] . "</td>";
		echo "<td>" . $Row['SumOfGiveAway'] . "</td>";
		echo "<td>" . $Row['SumOfTakeAway'] . "</td>";
		echo "<td>" . $Row['SumOfEmptyNetGoal'] . "</td>";
		echo "<td>" . $Row['SumOfHatTrick'] . "</td>";	
		echo "<td>" . number_Format($Row['SumOfP20'],2) . "</td>";			
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
<em><?php echo $PlayersLang['CareerNote'];?></em><br />
<?php include "Footer.php";?>
