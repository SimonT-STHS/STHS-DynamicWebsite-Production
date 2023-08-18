<?php include "Header.php";
If ($lang == "fr"){include 'LanguageFR-Stat.php';}else{include 'LanguageEN-Stat.php';}
$Title = (string)"";
$TypeText = (string)"Pro";$TitleType = $DynamicTitleLang['Pro'];
if(isset($_GET['Farm'])){$TypeText = "Farm";$TitleType = $DynamicTitleLang['Farm'];}
$MaximumResult = (integer)10;
$MimimumData = (integer)10;
$Playoff = (string)"False";
if(isset($_GET['Playoff'])){$Playoff="True";$MimimumData=1;}

If (file_exists($DatabaseFile) == false){
	Goto CareerStatIndividualLeaders;
}else{try{
	if(isset($_GET['Max'])){$MaximumResult = filter_var($_GET['Max'], FILTER_SANITIZE_NUMBER_INT);} 
	$LeagueName = (string)"";
	$db = new SQLite3($DatabaseFile);
	$Query = "Select Name, PlayOffStarted, PreSeasonSchedule from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	$Query = "Select ProMinimumGamePlayerLeader, FarmMinimumGamePlayerLeader from LeagueOutputOption";
	$LeagueOutputOption = $db->querySingle($Query,true);		
	
	If ($Playoff=="True"){$Title = $SearchLang['Playoff'] .  " ";}
	$Title = $Title . $TopMenuLang['CareerStatsIndividualLeaders'] . " " . $TitleType ;
	
	If (file_exists($CareerStatDatabaseFile) == true){ /* CareerStat */
		$CareerStatdb = new SQLite3($CareerStatDatabaseFile);
		$CareerDBFormatV2CheckCheck = $CareerStatdb->querySingle("SELECT Count(name) AS CountName FROM sqlite_master WHERE type='table' AND name='LeagueGeneral'",true);
		If ($CareerDBFormatV2CheckCheck['CountName'] == 1){		
			$CareerStatdb->query("ATTACH DATABASE '".realpath($DatabaseFile)."' AS CurrentDB");
		}else{
			$CareerPlayerStat = Null;
			$Title = $CareeratabaseNotFound;	
		}
	}else{
		$CareerPlayerStat = Null;
		$Title = $CareeratabaseNotFound;
	}
	
	echo "<title>" . $LeagueName . " - " . $Title ."</title>";
} catch (Exception $e) {
CareerStatIndividualLeaders:
	$LeagueName = $DatabaseNotFound;
	echo "<title>" . $DatabaseNotFound . "</title>";
	$Title = $DatabaseNotFound;
	$LeagueGeneral = Null;
}}?>
</head><body>
<?php include "Menu.php";?>
<div style="width:99%;margin:auto;">
<?php echo "<h1>" . $Title . "</h1>"; ?>
<table class="STHSTableFullW">
<tr><td colspan="3"><h2 class="STHSIndividualLeader_Players STHSCenter"><?php echo $DynamicTitleLang['Players'];?></h2></td></tr>


<tr>
<td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['Points'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Points">PTS</th></tr></thead>
<?php
$Query = "SELECT MainTable.*, Player" . $TypeText . "Stat.*, PlayerInfo.NHLID, PlayerInfo.Country, PlayerInfo.TeamName FROM (SELECT Name AS SumOfName, UniqueID, Sum(Player" . $TypeText . "StatCareer.GP) AS SumOfGP, Sum(Player" . $TypeText . "StatCareer.G) AS SumOfG, Sum(Player" . $TypeText . "StatCareer.A) AS SumOfA FROM Player" . $TypeText . "StatCareer WHERE Playoff = \"" . $Playoff . "\" GROUP BY Player" . $TypeText . "StatCareer.Name) AS MainTable LEFT JOIN Player" . $TypeText . "Stat ON MainTable.SumOfName = Player" . $TypeText . "Stat.Name LEFT JOIN PlayerInfo ON MainTable.SumOfName = PlayerInfo.Name ORDER BY (MainTable.SumOfG + MainTable.SumOfA + IfNull(Player" . $TypeText . "Stat.G,0) + IfNull(Player" . $TypeText . "Stat.A,0)) DESC, (MainTable.SumofGP";
if (isset($LeagueGeneral)){if ($LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False"){$Query = $Query . " + IfNull(Player" . $TypeText . "Stat.GP,0)";}}
$Query = $Query . " ) ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound OR $Title == $CareeratabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $CareerStatdb->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td>";
	If ($Row['Number'] > 0){
		echo "<td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . "</a></td>";
	}else{
		echo "<td><a href=\"CareerStatPlayerReport.php?Player=" . $Row['SumOfName'] . "\">" . $Row['SumOfName'] . "*</a></td>";
	}
	if ($LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False"){
		echo "<td>" . ($Row['SumOfGP'] + $Row['GP']) . "</td><td>" . ($Row['SumOfG'] + $Row['G'] + $Row['SumOfA'] + $Row['A']) .  "</td></tr>\n";
	}else{
		echo "<td>" . ($Row['SumOfGP']) . "</td><td>" . ($Row['SumOfG'] + $Row['SumOfA']) .  "</td></tr>\n";
	}
}}
If ($LoopCount > 1){
	echo "</table></td><td class=\"STHSWP2\"></td>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td><td class=\"STHSWP2\"></td>";
}?>

<td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['Goals'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Goals">G</th></tr></thead>
<?php
$Query = "SELECT MainTable.*, Player" . $TypeText . "Stat.*, PlayerInfo.NHLID, PlayerInfo.Country, PlayerInfo.TeamName FROM (SELECT Name AS SumOfName, UniqueID, Sum(Player" . $TypeText . "StatCareer.GP) AS SumOfGP, Sum(Player" . $TypeText . "StatCareer.G) AS SumOfG FROM Player" . $TypeText . "StatCareer WHERE Playoff = \"" . $Playoff . "\" GROUP BY Player" . $TypeText . "StatCareer.Name) AS MainTable LEFT JOIN Player" . $TypeText . "Stat ON MainTable.SumOfName = Player" . $TypeText . "Stat.Name LEFT JOIN PlayerInfo ON MainTable.SumOfName = PlayerInfo.Name ORDER BY (MainTable.SumOfG + IfNull(Player" . $TypeText . "Stat.G,0)) DESC, (MainTable.SumofGP";
if (isset($LeagueGeneral)){if ($LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False"){$Query = $Query . " + IfNull(Player" . $TypeText . "Stat.GP,0)";}}
$Query = $Query . " ) ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound OR $Title == $CareeratabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $CareerStatdb->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td>";
	If ($Row['Number'] > 0){
		echo "<td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . "</a></td>";
	}else{
		echo "<td><a href=\"CareerStatPlayerReport.php?Player=" . $Row['SumOfName'] . "\">" . $Row['SumOfName'] . "*</a></td>";
	}
	if ($LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False"){
		echo "<td>" . ($Row['SumOfGP'] + $Row['GP']) . "</td><td>" . ($Row['SumOfG'] + $Row['G']) .  "</td></tr>\n";
	}else{
		echo "<td>" . ($Row['SumOfGP']) . "</td><td>" . ($Row['SumOfG']) .  "</td></tr>\n";
	}	
}}
If ($LoopCount > 1){
	echo "</table></td></tr>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td></tr>";
}?>

<tr>
<td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['Assists'];?>
</span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Assists">A</th></tr></thead>
<?php
$Query = "SELECT MainTable.*, Player" . $TypeText . "Stat.*, PlayerInfo.NHLID, PlayerInfo.Country, PlayerInfo.TeamName FROM (SELECT Name AS SumOfName, UniqueID, Sum(Player" . $TypeText . "StatCareer.GP) AS SumOfGP, Sum(Player" . $TypeText . "StatCareer.A) AS SumOfA FROM Player" . $TypeText . "StatCareer WHERE Playoff = \"" . $Playoff . "\" GROUP BY Player" . $TypeText . "StatCareer.Name) AS MainTable LEFT JOIN Player" . $TypeText . "Stat ON MainTable.SumOfName = Player" . $TypeText . "Stat.Name LEFT JOIN PlayerInfo ON MainTable.SumOfName = PlayerInfo.Name ORDER BY (MainTable.SumOfA + IfNull(Player" . $TypeText . "Stat.A,0)) DESC, (MainTable.SumofGP";
if (isset($LeagueGeneral)){if ($LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False"){$Query = $Query . " + IfNull(Player" . $TypeText . "Stat.GP,0)";}}
$Query = $Query . " ) ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound OR $Title == $CareeratabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $CareerStatdb->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td>";
	If ($Row['Number'] > 0){
		echo "<td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . "</a></td>";
	}else{
		echo "<td><a href=\"CareerStatPlayerReport.php?Player=" . $Row['SumOfName'] . "\">" . $Row['SumOfName'] . "*</a></td>";
	}
	if ($LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False"){
		echo "<td>" . ($Row['SumOfGP'] + $Row['GP']) . "</td><td>" . ($Row['SumOfA'] + $Row['A']) .  "</td></tr>\n";
	}else{
		echo "<td>" . ($Row['SumOfGP']) . "</td><td>" . ($Row['SumOfA']) .  "</td></tr>\n";
	}	
}}
If ($LoopCount > 1){
	echo "</table></td><td class=\"STHSWP2\"></td>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td><td class=\"STHSWP2\"></td>";
}?>

<td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['Shots'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Shots">SHT</th></tr></thead>
<?php
$Query = "SELECT MainTable.*, Player" . $TypeText . "Stat.*, PlayerInfo.NHLID, PlayerInfo.Country, PlayerInfo.TeamName FROM (SELECT Name AS SumOfName, UniqueID, Sum(Player" . $TypeText . "StatCareer.GP) AS SumOfGP, Sum(Player" . $TypeText . "StatCareer.Shots) AS SumOfShots FROM Player" . $TypeText . "StatCareer WHERE Playoff = \"" . $Playoff . "\" GROUP BY Player" . $TypeText . "StatCareer.Name) AS MainTable LEFT JOIN Player" . $TypeText . "Stat ON MainTable.SumOfName = Player" . $TypeText . "Stat.Name LEFT JOIN PlayerInfo ON MainTable.SumOfName = PlayerInfo.Name ORDER BY (MainTable.SumOfShots + IfNull(Player" . $TypeText . "Stat.Shots,0)) DESC, (MainTable.SumofGP";
if (isset($LeagueGeneral)){if ($LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False"){$Query = $Query . " + IfNull(Player" . $TypeText . "Stat.GP,0)";}}
$Query = $Query . " ) ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound OR $Title == $CareeratabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $CareerStatdb->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td>";
	If ($Row['Number'] > 0){
		echo "<td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . "</a></td>";
	}else{
		echo "<td><a href=\"CareerStatPlayerReport.php?Player=" . $Row['SumOfName'] . "\">" . $Row['SumOfName'] . "*</a></td>";
	}
	if ($LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False"){
		echo "<td>" . ($Row['SumOfGP'] + $Row['GP']) . "</td><td>" . ($Row['SumOfShots'] + $Row['Shots']) .  "</td></tr>\n";
	}else{
		echo "<td>" . ($Row['SumOfGP']) . "</td><td>" . ($Row['SumOfShots']) .  "</td></tr>\n";
	}		
}}
If ($LoopCount > 1){
	echo "</table></td></tr>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td></tr>";
}?>

<tr>
<td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['ShotsPCT'];?>
</span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Shooting Percentage">SHT%</th></tr></thead>
<?php
$Query = "SELECT MainTable.*, Player" . $TypeText . "Stat.*, PlayerInfo.NHLID, PlayerInfo.Country, PlayerInfo.TeamName, ROUND(CAST((MainTable.SumOfG";
if (isset($LeagueGeneral)){if ($LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False"){$Query = $Query . " + IfNull(Player" . $TypeText . "Stat.G,0)";}}
$Query = $Query . ") AS REAL) / CAST((MainTable.SumOfShots";
if (isset($LeagueGeneral)){if ($LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False"){$Query = $Query . "+ IfNull(Player" . $TypeText . "Stat.Shots,0)";}}
$Query = $Query . " ) AS REAL) *100,2) AS TotalShotsPCT FROM (SELECT Name AS SumOfName, UniqueID, Sum(Player" . $TypeText . "StatCareer.GP) AS SumOfGP, Sum(Player" . $TypeText . "StatCareer.G) AS SumOfG, Sum(Player" . $TypeText . "StatCareer.Shots) AS SumOfShots FROM Player" . $TypeText . "StatCareer WHERE Playoff = \"" . $Playoff . "\" GROUP BY Player" . $TypeText . "StatCareer.Name) AS MainTable LEFT JOIN Player" . $TypeText . "Stat ON MainTable.SumOfName = Player" . $TypeText . "Stat.Name LEFT JOIN PlayerInfo ON MainTable.SumOfName = PlayerInfo.Name WHERE SumOfShots >= " . ($MimimumData *  10) . " ORDER BY TotalShotsPCT DESC, (MainTable.SumofGP";
if (isset($LeagueGeneral)){if ($LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False"){$Query = $Query . " + IfNull(Player" . $TypeText . "Stat.GP,0)";}}
$Query = $Query . " ) ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound OR $Title == $CareeratabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $CareerStatdb->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td>";
	If ($Row['Number'] > 0){
		echo "<td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . "</a></td>";
	}else{
		echo "<td><a href=\"CareerStatPlayerReport.php?Player=" . $Row['SumOfName'] . "\">" . $Row['SumOfName'] . "*</a></td>";
	}
	if ($LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False"){
		echo "<td>" . ($Row['SumOfGP'] + $Row['GP']) . "</td><td>" . number_Format($Row['TotalShotsPCT'],2) .  "</td></tr>\n";
	}else{
		echo "<td>" . ($Row['SumOfGP']) . "</td><td>" . number_Format($Row['TotalShotsPCT'],2) .  "</td></tr>\n";
	}		
}}
If ($LoopCount > 1){
	echo "</table></td><td class=\"STHSWP2\"></td>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td><td class=\"STHSWP2\"></td>";
}?>

<td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['ShotsBlock'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Shots Blocked">SB</th></tr></thead>
<?php
$Query = "SELECT MainTable.*, Player" . $TypeText . "Stat.*, PlayerInfo.NHLID, PlayerInfo.Country, PlayerInfo.TeamName FROM (SELECT Name AS SumOfName, UniqueID, Sum(Player" . $TypeText . "StatCareer.GP) AS SumOfGP, Sum(Player" . $TypeText . "StatCareer.ShotsBlock) AS SumOfShotsBlock FROM Player" . $TypeText . "StatCareer WHERE Playoff = \"" . $Playoff . "\" GROUP BY Player" . $TypeText . "StatCareer.Name) AS MainTable LEFT JOIN Player" . $TypeText . "Stat ON MainTable.SumOfName = Player" . $TypeText . "Stat.Name LEFT JOIN PlayerInfo ON MainTable.SumOfName = PlayerInfo.Name ORDER BY (MainTable.SumOfShotsBlock + IfNull(Player" . $TypeText . "Stat.ShotsBlock,0)) DESC, (MainTable.SumofGP";
if (isset($LeagueGeneral)){if ($LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False"){$Query = $Query . " + IfNull(Player" . $TypeText . "Stat.GP,0)";}}
$Query = $Query . " ) ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound OR $Title == $CareeratabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $CareerStatdb->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td>";
	If ($Row['Number'] > 0){
		echo "<td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . "</a></td>";
	}else{
		echo "<td><a href=\"CareerStatPlayerReport.php?Player=" . $Row['SumOfName'] . "\">" . $Row['SumOfName'] . "*</a></td>";
	}
	if ($LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False"){
		echo "<td>" . ($Row['SumOfGP'] + $Row['GP']) . "</td><td>" . ($Row['SumOfShotsBlock'] + $Row['ShotsBlock']) .  "</td></tr>\n";
	}else{
		echo "<td>" . ($Row['SumOfGP']) . "</td><td>" . ($Row['SumOfShotsBlock']) .  "</td></tr>\n";
	}	
}}
If ($LoopCount > 1){
	echo "</table></td></tr>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td></tr>";
}?>

<tr>
<td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['Pointper20Minutes'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Points per 20 Minutes">P/20</th></tr></thead>
<?php
$Query = "SELECT MainTable.*, Player" . $TypeText . "Stat.*, PlayerInfo.NHLID, PlayerInfo.Country, PlayerInfo.TeamName, ROUND(CAST((MainTable.SumOfP";
if (isset($LeagueGeneral)){if ($LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False"){$Query = $Query . " + IfNull(Player" . $TypeText . "Stat.P,0)";}}
$Query = $Query . ") AS REAL) / CAST((MainTable.SumOfSecondPlay";
if (isset($LeagueGeneral)){if ($LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False"){$Query = $Query . " + IfNull(Player" . $TypeText . "Stat.SecondPlay,0)";}}
$Query = $Query . ") AS REAL) * 60 * 20,2) AS TotalP20 FROM (SELECT Name AS SumOfName, UniqueID, Sum(Player" . $TypeText . "StatCareer.GP) AS SumOfGP, Sum(Player" . $TypeText . "StatCareer.P) AS SumOfP, Sum(Player" . $TypeText . "StatCareer.SecondPlay) AS SumOfSecondPlay FROM Player" . $TypeText . "StatCareer WHERE Playoff = \"" . $Playoff . "\" GROUP BY Player" . $TypeText . "StatCareer.Name) AS MainTable LEFT JOIN Player" . $TypeText . "Stat ON MainTable.SumOfName = Player" . $TypeText . "Stat.Name LEFT JOIN PlayerInfo ON MainTable.SumOfName = PlayerInfo.Name WHERE SumOfP >= " . ($MimimumData *  5) . " ORDER BY TotalP20 DESC, (MainTable.SumofGP";
if (isset($LeagueGeneral)){if ($LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False"){$Query = $Query . " + IfNull(Player" . $TypeText . "Stat.GP,0)";}}
$Query = $Query . " ) ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound OR $Title == $CareeratabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $CareerStatdb->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td>";
	If ($Row['Number'] > 0){
		echo "<td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . "</a></td>";
	}else{
		echo "<td><a href=\"CareerStatPlayerReport.php?Player=" . $Row['SumOfName'] . "\">" . $Row['SumOfName'] . "*</a></td>";
	}
	if ($LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False"){
		echo "<td>" . ($Row['SumOfGP'] + $Row['GP']) . "</td><td>" . number_Format($Row['TotalP20'],2) .  "</td></tr>\n";
	}else{
		echo "<td>" . ($Row['SumOfGP']) . "</td><td>" . number_Format($Row['TotalP20'],2) .  "</td></tr>\n";
	}		
}}
If ($LoopCount > 1){
	echo "</table></td><td class=\"STHSWP2\"></td>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td><td class=\"STHSWP2\"></td>";
}?>

<td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['FaceoffPCT'];?>
</span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Face offs Percentage">FO%</th></tr></thead>
<?php
$Query = "SELECT MainTable.*, Player" . $TypeText . "Stat.*, PlayerInfo.NHLID, PlayerInfo.Country, PlayerInfo.TeamName, ROUND(CAST((MainTable.SumOfFaceOffWon";
if (isset($LeagueGeneral)){if ($LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False"){$Query = $Query . "+ IfNull(Player" . $TypeText . "Stat.FaceOffWon,0)";}}
$Query = $Query . ") AS REAL) / CAST((MainTable.SumOfFaceOffTotal";
if (isset($LeagueGeneral)){if ($LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False"){$Query = $Query . " + IfNull(Player" . $TypeText . "Stat.FaceOffTotal,0)";}}
$Query = $Query . ") AS REAL) *100,2) as TotalFaceoffPCT FROM (SELECT Name AS SumOfName, UniqueID, Sum(Player" . $TypeText . "StatCareer.GP) AS SumOfGP, Sum(Player" . $TypeText . "StatCareer.FaceOffWon) AS SumOfFaceOffWon, Sum(Player" . $TypeText . "StatCareer.FaceOffTotal) AS SumOfFaceOffTotal FROM Player" . $TypeText . "StatCareer WHERE Playoff = \"" . $Playoff . "\" GROUP BY Player" . $TypeText . "StatCareer.Name) AS MainTable LEFT JOIN Player" . $TypeText . "Stat ON MainTable.SumOfName = Player" . $TypeText . "Stat.Name LEFT JOIN PlayerInfo ON MainTable.SumOfName = PlayerInfo.Name WHERE SumOfFaceOffTotal >= " . ($MimimumData *  10) . " ORDER BY TotalFaceoffPCT DESC, (MainTable.SumofGP";
if (isset($LeagueGeneral)){if ($LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False"){$Query = $Query . " + IfNull(Player" . $TypeText . "Stat.GP,0)";}}
$Query = $Query . " ) ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound OR $Title == $CareeratabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $CareerStatdb->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td>";
	If ($Row['Number'] > 0){
		echo "<td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . "</a></td>";
	}else{
		echo "<td><a href=\"CareerStatPlayerReport.php?Player=" . $Row['SumOfName'] . "\">" . $Row['SumOfName'] . "*</a></td>";
	}
	if ($LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False"){
		echo "<td>" . ($Row['SumOfGP'] + $Row['GP']) . "</td><td>" . number_Format($Row['TotalFaceoffPCT'],2) .  "</td></tr>\n";
	}else{
		echo "<td>" . ($Row['SumOfGP']) . "</td><td>" . number_Format($Row['TotalFaceoffPCT'],2) .  "</td></tr>\n";
	}	
}}
If ($LoopCount > 1){
	echo "</table></td></tr>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td></tr>";
}?>


<tr>
<td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['PlusMinus'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Plus/Minus">+/-</th></tr></thead>
<?php
$Query = "SELECT MainTable.*, Player" . $TypeText . "Stat.*, PlayerInfo.NHLID, PlayerInfo.Country, PlayerInfo.TeamName FROM (SELECT Name AS SumOfName, UniqueID, Sum(Player" . $TypeText . "StatCareer.GP) AS SumOfGP, Sum(Player" . $TypeText . "StatCareer.PlusMinus) AS SumOfPlusMinus FROM Player" . $TypeText . "StatCareer WHERE Playoff = \"" . $Playoff . "\" GROUP BY Player" . $TypeText . "StatCareer.Name) AS MainTable LEFT JOIN Player" . $TypeText . "Stat ON MainTable.SumOfName = Player" . $TypeText . "Stat.Name LEFT JOIN PlayerInfo ON MainTable.SumOfName = PlayerInfo.Name ORDER BY (MainTable.SumOfPlusMinus + IfNull(Player" . $TypeText . "Stat.PlusMinus,0)) DESC, (MainTable.SumofGP";
if (isset($LeagueGeneral)){if ($LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False"){$Query = $Query . " + IfNull(Player" . $TypeText . "Stat.GP,0)";}}
$Query = $Query . " ) ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound OR $Title == $CareeratabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $CareerStatdb->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td>";
	If ($Row['Number'] > 0){
		echo "<td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . "</a></td>";
	}else{
		echo "<td><a href=\"CareerStatPlayerReport.php?Player=" . $Row['SumOfName'] . "\">" . $Row['SumOfName'] . "*</a></td>";
	}
	if ($LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False"){
		echo "<td>" . ($Row['SumOfGP'] + $Row['GP']) . "</td><td>" . ($Row['SumOfPlusMinus'] + $Row['PlusMinus']) .  "</td></tr>\n";
	}else{
		echo "<td>" . ($Row['SumOfGP']) . "</td><td>" . ($Row['SumOfPlusMinus']) .  "</td></tr>\n";
	}		
}}
If ($LoopCount > 1){
	echo "</table></td><td class=\"STHSWP2\"></td>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td><td class=\"STHSWP2\"></td>";
}?>


<td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['PenaltyMinutes'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Penalty Minutes">PIM</th></tr></thead>
<?php
$Query = "SELECT MainTable.*, Player" . $TypeText . "Stat.*, PlayerInfo.NHLID, PlayerInfo.Country, PlayerInfo.TeamName FROM (SELECT Name AS SumOfName, UniqueID, Sum(Player" . $TypeText . "StatCareer.GP) AS SumOfGP, Sum(Player" . $TypeText . "StatCareer.Pim) AS SumOfPim FROM Player" . $TypeText . "StatCareer WHERE Playoff = \"" . $Playoff . "\" GROUP BY Player" . $TypeText . "StatCareer.Name) AS MainTable LEFT JOIN Player" . $TypeText . "Stat ON MainTable.SumOfName = Player" . $TypeText . "Stat.Name LEFT JOIN PlayerInfo ON MainTable.SumOfName = PlayerInfo.Name ORDER BY (MainTable.SumOfPim + IfNull(Player" . $TypeText . "Stat.Pim,0)) DESC, (MainTable.SumofGP";
if (isset($LeagueGeneral)){if ($LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False"){$Query = $Query . " + IfNull(Player" . $TypeText . "Stat.GP,0)";}}
$Query = $Query . " ) ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound OR $Title == $CareeratabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $CareerStatdb->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td>";
	If ($Row['Number'] > 0){
		echo "<td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . "</a></td>";
	}else{
		echo "<td><a href=\"CareerStatPlayerReport.php?Player=" . $Row['SumOfName'] . "\">" . $Row['SumOfName'] . "*</a></td>";
	}
	if ($LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False"){
		echo "<td>" . ($Row['SumOfGP'] + $Row['GP']) . "</td><td>" . ($Row['SumOfPim'] + $Row['Pim']) .  "</td></tr>\n";
	}else{
		echo "<td>" . ($Row['SumOfGP']) . "</td><td>" . ($Row['SumOfPim']) .  "</td></tr>\n";
	}		
}}
If ($LoopCount > 1){
	echo "</table></td></tr>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td></tr>";
}?>

<tr>
<td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['Hits'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Hits">HIT</th></tr></thead>
<?php
$Query = "SELECT MainTable.*, Player" . $TypeText . "Stat.*, PlayerInfo.NHLID, PlayerInfo.Country, PlayerInfo.TeamName FROM (SELECT Name AS SumOfName, UniqueID, Sum(Player" . $TypeText . "StatCareer.GP) AS SumOfGP, Sum(Player" . $TypeText . "StatCareer.Hits) AS SumOfHits FROM Player" . $TypeText . "StatCareer WHERE Playoff = \"" . $Playoff . "\" GROUP BY Player" . $TypeText . "StatCareer.Name) AS MainTable LEFT JOIN Player" . $TypeText . "Stat ON MainTable.SumOfName = Player" . $TypeText . "Stat.Name LEFT JOIN PlayerInfo ON MainTable.SumOfName = PlayerInfo.Name ORDER BY (MainTable.SumOfHits + IfNull(Player" . $TypeText . "Stat.Hits,0)) DESC, (MainTable.SumofGP";
if (isset($LeagueGeneral)){if ($LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False"){$Query = $Query . " + IfNull(Player" . $TypeText . "Stat.GP,0)";}}
$Query = $Query . " ) ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound OR $Title == $CareeratabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $CareerStatdb->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td>";
	If ($Row['Number'] > 0){
		echo "<td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . "</a></td>";
	}else{
		echo "<td><a href=\"CareerStatPlayerReport.php?Player=" . $Row['SumOfName'] . "\">" . $Row['SumOfName'] . "*</a></td>";
	}
	if ($LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False"){
		echo "<td>" . ($Row['SumOfGP'] + $Row['GP']) . "</td><td>" . ($Row['SumOfHits'] + $Row['Hits']) .  "</td></tr>\n";
	}else{
		echo "<td>" . ($Row['SumOfGP']) . "</td><td>" . ($Row['SumOfHits']) .  "</td></tr>\n";
	}		
}}
If ($LoopCount > 1){
	echo "</table></td><td class=\"STHSWP2\"></td>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td><td class=\"STHSWP2\"></td>";
}?>

<td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['PowerPlayGoals'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Power Play Goals">PPG</th></tr></thead>
<?php
$Query = "SELECT MainTable.*, Player" . $TypeText . "Stat.*, PlayerInfo.NHLID, PlayerInfo.Country, PlayerInfo.TeamName FROM (SELECT Name AS SumOfName, UniqueID, Sum(Player" . $TypeText . "StatCareer.GP) AS SumOfGP, Sum(Player" . $TypeText . "StatCareer.PPG) AS SumOfPPG FROM Player" . $TypeText . "StatCareer WHERE Playoff = \"" . $Playoff . "\" GROUP BY Player" . $TypeText . "StatCareer.Name) AS MainTable LEFT JOIN Player" . $TypeText . "Stat ON MainTable.SumOfName = Player" . $TypeText . "Stat.Name LEFT JOIN PlayerInfo ON MainTable.SumOfName = PlayerInfo.Name ORDER BY (MainTable.SumOfPPG + IfNull(Player" . $TypeText . "Stat.PPG,0)) DESC, (MainTable.SumofGP";
if (isset($LeagueGeneral)){if ($LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False"){$Query = $Query . " + IfNull(Player" . $TypeText . "Stat.GP,0)";}}
$Query = $Query . " ) ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound OR $Title == $CareeratabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $CareerStatdb->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td>";
	If ($Row['Number'] > 0){
		echo "<td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . "</a></td>";
	}else{
		echo "<td><a href=\"CareerStatPlayerReport.php?Player=" . $Row['SumOfName'] . "\">" . $Row['SumOfName'] . "*</a></td>";
	}
	if ($LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False"){
		echo "<td>" . ($Row['SumOfGP'] + $Row['GP']) . "</td><td>" . ($Row['SumOfPPG'] + $Row['PPG']) .  "</td></tr>\n";
	}else{
		echo "<td>" . ($Row['SumOfGP']) . "</td><td>" . ($Row['SumOfPPG']) .  "</td></tr>\n";
	}		
}}
If ($LoopCount > 1){
	echo "</table></td></tr>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td></tr>";
}?>

<tr>
<td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['ShortHandedGoals'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Short Handed Goals">PKG</th></tr></thead>
<?php
$Query = "SELECT MainTable.*, Player" . $TypeText . "Stat.*, PlayerInfo.NHLID, PlayerInfo.Country, PlayerInfo.TeamName FROM (SELECT Name AS SumOfName, UniqueID, Sum(Player" . $TypeText . "StatCareer.GP) AS SumOfGP, Sum(Player" . $TypeText . "StatCareer.PKG) AS SumOfPKG FROM Player" . $TypeText . "StatCareer WHERE Playoff = \"" . $Playoff . "\" GROUP BY Player" . $TypeText . "StatCareer.Name) AS MainTable LEFT JOIN Player" . $TypeText . "Stat ON MainTable.SumOfName = Player" . $TypeText . "Stat.Name LEFT JOIN PlayerInfo ON MainTable.SumOfName = PlayerInfo.Name ORDER BY (MainTable.SumOfPKG + IfNull(Player" . $TypeText . "Stat.PKG,0)) DESC, (MainTable.SumofGP";
if (isset($LeagueGeneral)){if ($LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False"){$Query = $Query . " + IfNull(Player" . $TypeText . "Stat.GP,0)";}}
$Query = $Query . " ) ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound OR $Title == $CareeratabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $CareerStatdb->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td>";
	If ($Row['Number'] > 0){
		echo "<td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . "</a></td>";
	}else{
		echo "<td><a href=\"CareerStatPlayerReport.php?Player=" . $Row['SumOfName'] . "\">" . $Row['SumOfName'] . "*</a></td>";
	}
	if ($LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False"){
		echo "<td>" . ($Row['SumOfGP'] + $Row['GP']) . "</td><td>" . ($Row['SumOfPKG'] + $Row['PKG']) .  "</td></tr>\n";
	}else{
		echo "<td>" . ($Row['SumOfGP']) . "</td><td>" . ($Row['SumOfPKG']) .  "</td></tr>\n";
	}		
}}
If ($LoopCount > 1){
	echo "</table></td><td class=\"STHSWP2\"></td>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td><td class=\"STHSWP2\"></td>";
}?>

<td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['GameWinningGoals'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Game Winning Goals">GW</th></tr></thead>
<?php
$Query = "SELECT MainTable.*, Player" . $TypeText . "Stat.*, PlayerInfo.NHLID, PlayerInfo.Country, PlayerInfo.TeamName FROM (SELECT Name AS SumOfName, UniqueID, Sum(Player" . $TypeText . "StatCareer.GP) AS SumOfGP, Sum(Player" . $TypeText . "StatCareer.GW) AS SumOfGW FROM Player" . $TypeText . "StatCareer WHERE Playoff = \"" . $Playoff . "\" GROUP BY Player" . $TypeText . "StatCareer.Name) AS MainTable LEFT JOIN Player" . $TypeText . "Stat ON MainTable.SumOfName = Player" . $TypeText . "Stat.Name LEFT JOIN PlayerInfo ON MainTable.SumOfName = PlayerInfo.Name ORDER BY (MainTable.SumOfGW + IfNull(Player" . $TypeText . "Stat.GW,0)) DESC, (MainTable.SumofGP";
if (isset($LeagueGeneral)){if ($LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False"){$Query = $Query . " + IfNull(Player" . $TypeText . "Stat.GP,0)";}}
$Query = $Query . " ) ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound OR $Title == $CareeratabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $CareerStatdb->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td>";
	If ($Row['Number'] > 0){
		echo "<td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . "</a></td>";
	}else{
		echo "<td><a href=\"CareerStatPlayerReport.php?Player=" . $Row['SumOfName'] . "\">" . $Row['SumOfName'] . "*</a></td>";
	}
	if ($LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False"){
		echo "<td>" . ($Row['SumOfGP'] + $Row['GP']) . "</td><td>" . ($Row['SumOfGW'] + $Row['GW']) .  "</td></tr>\n";
	}else{
		echo "<td>" . ($Row['SumOfGP']) . "</td><td>" . ($Row['SumOfGW']) .  "</td></tr>\n";
	}	
}}
If ($LoopCount > 1){
	echo "</table></td></tr>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td></tr>";
}?>

<tr>
<td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['MinutesPlayed'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Minutes Played">MP</th></tr></thead>
<?php
$Query = "SELECT MainTable.*, Player" . $TypeText . "Stat.*, PlayerInfo.NHLID, PlayerInfo.Country, PlayerInfo.TeamName FROM (SELECT Name AS SumOfName, UniqueID, Sum(Player" . $TypeText . "StatCareer.GP) AS SumOfGP, Sum(Player" . $TypeText . "StatCareer.SecondPlay) AS SumOfSecondPlay FROM Player" . $TypeText . "StatCareer WHERE Playoff = \"" . $Playoff . "\" GROUP BY Player" . $TypeText . "StatCareer.Name) AS MainTable LEFT JOIN Player" . $TypeText . "Stat ON MainTable.SumOfName = Player" . $TypeText . "Stat.Name LEFT JOIN PlayerInfo ON MainTable.SumOfName = PlayerInfo.Name ORDER BY (MainTable.SumOfSecondPlay + IfNull(Player" . $TypeText . "Stat.SecondPlay,0)) DESC, (MainTable.SumofGP";
if (isset($LeagueGeneral)){if ($LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False"){$Query = $Query . " + IfNull(Player" . $TypeText . "Stat.GP,0)";}}
$Query = $Query . " ) ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound OR $Title == $CareeratabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $CareerStatdb->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td>";
	If ($Row['Number'] > 0){
		echo "<td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . "</a></td>";
	}else{
		echo "<td><a href=\"CareerStatPlayerReport.php?Player=" . $Row['SumOfName'] . "\">" . $Row['SumOfName'] . "*</a></td>";
	}
	if ($LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False"){
		echo "<td>" . ($Row['SumOfGP'] + $Row['GP']) . "</td><td>" . Floor(($Row['SumOfSecondPlay'] + $Row['SecondPlay'])/60) .  "</td></tr>\n";
	}else{
		echo "<td>" . ($Row['SumOfGP']) . "</td><td>" . Floor(($Row['SumOfSecondPlay'])/60) .  "</td></tr>\n";
	}		
}}
If ($LoopCount > 1){
	echo "</table></td><td class=\"STHSWP2\"></td>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td><td class=\"STHSWP2\"></td>";
}?>

<td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['HatTricks'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Hat Tricks">HT</th></tr></thead>
<?php
$Query = "SELECT MainTable.*, Player" . $TypeText . "Stat.*, PlayerInfo.NHLID, PlayerInfo.Country, PlayerInfo.TeamName FROM (SELECT Name AS SumOfName, UniqueID, Sum(Player" . $TypeText . "StatCareer.GP) AS SumOfGP, Sum(Player" . $TypeText . "StatCareer.HatTrick) AS SumOfHatTrick FROM Player" . $TypeText . "StatCareer WHERE Playoff = \"" . $Playoff . "\" GROUP BY Player" . $TypeText . "StatCareer.Name) AS MainTable LEFT JOIN Player" . $TypeText . "Stat ON MainTable.SumOfName = Player" . $TypeText . "Stat.Name LEFT JOIN PlayerInfo ON MainTable.SumOfName = PlayerInfo.Name ORDER BY (MainTable.SumOfHatTrick + IfNull(Player" . $TypeText . "Stat.HatTrick,0)) DESC, (MainTable.SumofGP";
if (isset($LeagueGeneral)){if ($LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False"){$Query = $Query . " + IfNull(Player" . $TypeText . "Stat.GP,0)";}}
$Query = $Query . " ) ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound OR $Title == $CareeratabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $CareerStatdb->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td>";
	If ($Row['Number'] > 0){
		echo "<td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . "</a></td>";
	}else{
		echo "<td><a href=\"CareerStatPlayerReport.php?Player=" . $Row['SumOfName'] . "\">" . $Row['SumOfName'] . "*</a></td>";
	}
	if ($LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False"){
		echo "<td>" . ($Row['SumOfGP'] + $Row['GP']) . "</td><td>" . ($Row['SumOfHatTrick'] + $Row['HatTrick']) .  "</td></tr>\n";
	}else{
		echo "<td>" . ($Row['SumOfGP']) . "</td><td>" . ($Row['SumOfHatTrick']) .  "</td></tr>\n";
	}		
}}
If ($LoopCount > 1){
	echo "</table></td></tr>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td></tr>";
}?>

<tr>
<td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['HitsReceived'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Hits Received">HTT</th></tr></thead>
<?php
$Query = "SELECT MainTable.*, Player" . $TypeText . "Stat.*, PlayerInfo.NHLID, PlayerInfo.Country, PlayerInfo.TeamName FROM (SELECT Name AS SumOfName, UniqueID, Sum(Player" . $TypeText . "StatCareer.GP) AS SumOfGP, Sum(Player" . $TypeText . "StatCareer.HitsTook) AS SumOfHitsTook FROM Player" . $TypeText . "StatCareer WHERE Playoff = \"" . $Playoff . "\" GROUP BY Player" . $TypeText . "StatCareer.Name) AS MainTable LEFT JOIN Player" . $TypeText . "Stat ON MainTable.SumOfName = Player" . $TypeText . "Stat.Name LEFT JOIN PlayerInfo ON MainTable.SumOfName = PlayerInfo.Name ORDER BY (MainTable.SumOfHitsTook + IfNull(Player" . $TypeText . "Stat.HitsTook,0)) DESC, (MainTable.SumofGP";
if (isset($LeagueGeneral)){if ($LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False"){$Query = $Query . " + IfNull(Player" . $TypeText . "Stat.GP,0)";}}
$Query = $Query . " ) ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound OR $Title == $CareeratabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $CareerStatdb->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td>";
	If ($Row['Number'] > 0){
		echo "<td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . "</a></td>";
	}else{
		echo "<td><a href=\"CareerStatPlayerReport.php?Player=" . $Row['SumOfName'] . "\">" . $Row['SumOfName'] . "*</a></td>";
	}
	if ($LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False"){
		echo "<td>" . ($Row['SumOfGP'] + $Row['GP']) . "</td><td>" . ($Row['SumOfHitsTook'] + $Row['HitsTook']) .  "</td></tr>\n";
	}else{
		echo "<td>" . ($Row['SumOfGP']) . "</td><td>" . ($Row['SumOfHitsTook']) .  "</td></tr>\n";
	}		
}}
If ($LoopCount > 1){
	echo "</table></td><td class=\"STHSWP2\"></td>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td><td class=\"STHSWP2\"></td>";
}?>

<td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['PenaltyShotsGoals'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Penalty Shots Goals">PSG</th></tr></thead>
<?php
$Query = "SELECT MainTable.*, Player" . $TypeText . "Stat.*, PlayerInfo.NHLID, PlayerInfo.Country, PlayerInfo.TeamName FROM (SELECT Name AS SumOfName, UniqueID, Sum(Player" . $TypeText . "StatCareer.GP) AS SumOfGP, Sum(Player" . $TypeText . "StatCareer.PenalityShotsScore) AS SumOfPenalityShotsScore FROM Player" . $TypeText . "StatCareer WHERE Playoff = \"" . $Playoff . "\" GROUP BY Player" . $TypeText . "StatCareer.Name) AS MainTable LEFT JOIN Player" . $TypeText . "Stat ON MainTable.SumOfName = Player" . $TypeText . "Stat.Name LEFT JOIN PlayerInfo ON MainTable.SumOfName = PlayerInfo.Name ORDER BY (MainTable.SumOfPenalityShotsScore + IfNull(Player" . $TypeText . "Stat.PenalityShotsScore,0)) DESC, (MainTable.SumofGP";
if (isset($LeagueGeneral)){if ($LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False"){$Query = $Query . " + IfNull(Player" . $TypeText . "Stat.GP,0)";}}
$Query = $Query . " ) ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound OR $Title == $CareeratabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $CareerStatdb->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td>";
	If ($Row['Number'] > 0){
		echo "<td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . "</a></td>";
	}else{
		echo "<td><a href=\"CareerStatPlayerReport.php?Player=" . $Row['SumOfName'] . "\">" . $Row['SumOfName'] . "*</a></td>";
	}
	if ($LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False"){
		echo "<td>" . ($Row['SumOfGP'] + $Row['GP']) . "</td><td>" . ($Row['SumOfPenalityShotsScore'] + $Row['PenalityShotsScore']) .  "</td></tr>\n";
	}else{
		echo "<td>" . ($Row['SumOfGP']) . "</td><td>" . ($Row['SumOfPenalityShotsScore']) .  "</td></tr>\n";
	}		
}}
If ($LoopCount > 1){
	echo "</table></td></tr>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td></tr>";
}?>

<tr>
<td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['TotalFight'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Total Fight">TF</th></tr></thead>
<?php
$Query = "SELECT MainTable.*, Player" . $TypeText . "Stat.*, PlayerInfo.NHLID, PlayerInfo.Country, PlayerInfo.TeamName FROM (SELECT Name AS SumOfName, UniqueID, Sum(Player" . $TypeText . "StatCareer.GP) AS SumOfGP, Sum(Player" . $TypeText . "StatCareer.FightW) AS SumOfFightW, Sum(Player" . $TypeText . "StatCareer.FightL) AS SumOfFightL, Sum(Player" . $TypeText . "StatCareer.FightT) AS SumOfFightT FROM Player" . $TypeText . "StatCareer WHERE Playoff = \"" . $Playoff . "\" GROUP BY Player" . $TypeText . "StatCareer.Name) AS MainTable LEFT JOIN Player" . $TypeText . "Stat ON MainTable.SumOfName = Player" . $TypeText . "Stat.Name LEFT JOIN PlayerInfo ON MainTable.SumOfName = PlayerInfo.Name ORDER BY (MainTable.SumOfFightW + MainTable.SumOfFightL + MainTable.SumOfFightT + IfNull(Player" . $TypeText . "Stat.FightW,0) + IfNull(Player" . $TypeText . "Stat.FightL,0) + IfNull(Player" . $TypeText . "Stat.FightT,0)) DESC, (MainTable.SumofGP";
if (isset($LeagueGeneral)){if ($LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False"){$Query = $Query . " + IfNull(Player" . $TypeText . "Stat.GP,0)";}}
$Query = $Query . " ) ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound OR $Title == $CareeratabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $CareerStatdb->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td>";
	If ($Row['Number'] > 0){
		echo "<td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . "</a></td>";
	}else{
		echo "<td><a href=\"CareerStatPlayerReport.php?Player=" . $Row['SumOfName'] . "\">" . $Row['SumOfName'] . "*</a></td>";
	}
	if ($LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False"){
		echo "<td>" . ($Row['SumOfGP'] + $Row['GP']) . "</td><td>" . ($Row['SumOfFightW'] + $Row['FightW'] + $Row['SumOfFightL'] + $Row['FightL'] + $Row['SumOfFightT'] + $Row['FightT']) .  "</td></tr>\n";
	}else{
		echo "<td>" . ($Row['SumOfGP']) . "</td><td>" . ($Row['SumOfFightW'] + $Row['SumOfFightL'] + $Row['SumOfFightT']) .  "</td></tr>\n";
	}		
}}
If ($LoopCount > 1){
	echo "</table></td><td class=\"STHSWP2\"></td>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td><td class=\"STHSWP2\"></td>";
}?>

<td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['FightWon'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Fight Won">FW</th></tr></thead>
<?php
$Query = "SELECT MainTable.*, Player" . $TypeText . "Stat.*, PlayerInfo.NHLID, PlayerInfo.Country, PlayerInfo.TeamName FROM (SELECT Name AS SumOfName, UniqueID, Sum(Player" . $TypeText . "StatCareer.GP) AS SumOfGP, Sum(Player" . $TypeText . "StatCareer.FightW) AS SumOfFightW FROM Player" . $TypeText . "StatCareer WHERE Playoff = \"" . $Playoff . "\" GROUP BY Player" . $TypeText . "StatCareer.Name) AS MainTable LEFT JOIN Player" . $TypeText . "Stat ON MainTable.SumOfName = Player" . $TypeText . "Stat.Name LEFT JOIN PlayerInfo ON MainTable.SumOfName = PlayerInfo.Name ORDER BY (MainTable.SumOfFightW + IfNull(Player" . $TypeText . "Stat.FightW,0)) DESC, (MainTable.SumofGP";
if (isset($LeagueGeneral)){if ($LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False"){$Query = $Query . " + IfNull(Player" . $TypeText . "Stat.GP,0)";}}
$Query = $Query . " ) ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound OR $Title == $CareeratabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $CareerStatdb->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td>";
	If ($Row['Number'] > 0){
		echo "<td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . "</a></td>";
	}else{
		echo "<td><a href=\"CareerStatPlayerReport.php?Player=" . $Row['SumOfName'] . "\">" . $Row['SumOfName'] . "*</a></td>";
	}
	if ($LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False"){
		echo "<td>" . ($Row['SumOfGP'] + $Row['GP']) . "</td><td>" . ($Row['SumOfFightW'] + $Row['FightW']) .  "</td></tr>\n";
	}else{
		echo "<td>" . ($Row['SumOfGP']) . "</td><td>" . ($Row['SumOfFightW']) .  "</td></tr>\n";
	}		
}}
If ($LoopCount > 1){
	echo "</table></td></tr>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td></tr>";
}?>
<tr><td colspan="3"><h2 class="STHSProIndividualLeader_Players STHSCenter"><?php echo $DynamicTitleLang['Goalies'];?></h2></td></tr>

<tr>
<td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['SavePCT'];?>
</span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['GoalieName'];?></th><th title="Games Played">GP</th><th title="Save Percentage">PCT</th></tr></thead>
<?php
$Query = "SELECT MainTable.*, Goaler" . $TypeText . "Stat.*, GoalerInfo.NHLID, GoalerInfo.Country, GoalerInfo.TeamName, ROUND((CAST((MainTable.SumofSA";
if (isset($LeagueGeneral)){if ($LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False"){$Query = $Query . " + IfNull(Goaler" . $TypeText . "Stat.SA,0)";}}
$Query = $Query . ") - (MainTable.SumofGA + IfNull(Goaler" . $TypeText . "Stat.GA,0)) AS REAL) / (MainTable.SumofSA";
if (isset($LeagueGeneral)){if ($LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False"){$Query = $Query . " + IfNull(Goaler" . $TypeText . "Stat.SA,0)";}}
$Query = $Query . ")),3) AS TotalPCT FROM ( SELECT Name AS SumOfName, UniqueID, Sum(Goaler" . $TypeText . "StatCareer.GP) AS SumOfGP, Sum(Goaler" . $TypeText . "StatCareer.GA) AS SumOfGA, Sum(Goaler" . $TypeText . "StatCareer.SA) AS SumOfSA FROM Goaler" . $TypeText . "StatCareer WHERE Playoff = \"" . $Playoff . "\" GROUP BY Goaler" . $TypeText . "StatCareer.Name) AS MainTable LEFT JOIN Goaler" . $TypeText . "Stat ON MainTable.SumOfName = Goaler" . $TypeText . "Stat.Name LEFT JOIN GoalerInfo ON MainTable.SumOfName = GoalerInfo.Name WHERE SumofSA >= " . ($MimimumData *  25) . " ORDER BY TotalPCT DESC, (MainTable.SumOfGP + IfNull(Goaler" . $TypeText . "Stat.GP,0)) ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound OR $Title == $CareeratabaseNotFound){$GoalerStat = Null;}else{$GoalerStat = $CareerStatdb->query($Query);}
$LoopCount = (integer)0;
if (empty($GoalerStat) == false){while ($Row = $GoalerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td>";
	If ($Row['Number'] > 0){
		echo "<td><a href=\"GoalieReport.php?Goalie=" . $Row['Number'] . "\">" . $Row['Name'] . "</a></td>";
	}else{
		echo "<td><a href=\"CareerStatGoalieReport.php?Goalie=" . $Row['SumOfName'] . "\">" . $Row['SumOfName'] . "*</a></td>";
	}		
	if ($LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False"){
		echo "<td>" . ($Row['SumOfGP'] + $Row['GP']) . "</td><td>" . number_Format($Row['TotalPCT'],3) .  "</td></tr>\n";
	}else{
		echo "<td>" . ($Row['SumOfGP']) . "</td><td>" . number_Format($Row['TotalPCT'],3) .  "</td></tr>\n";
	}	
	
}}
If ($LoopCount > 1){
	echo "</table></td><td class=\"STHSWP2\"></td>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td><td class=\"STHSWP2\"></td>";
}?>

<td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['GoalsAgainstAverage'];?></span></th></tr>
<tr><th>#</th><th title="Goalie Name"><?php echo $PlayersLang['GoalieName'];?></th><th title="Games Played">GP</th><th title="Goals Against Average">GAA</th></tr></thead>
<?php
$Query = "SELECT MainTable.*, Goaler" . $TypeText . "Stat.*, GoalerInfo.NHLID, GoalerInfo.Country, GoalerInfo.TeamName, ROUND((CAST((MainTable.SumofGA";
if (isset($LeagueGeneral)){if ($LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False"){$Query = $Query . " + IfNull(Goaler" . $TypeText . "Stat.GA,0)";}}
$Query = $Query . ") AS REAL) / ( (MainTable.SumOfSecondPlay";
if (isset($LeagueGeneral)){if ($LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False"){$Query = $Query . " + IfNull(Goaler" . $TypeText . "Stat.SecondPlay,0)";}}
$Query = $Query . ") / 60))*60,3) AS TotalGAA FROM ( SELECT Name AS SumOfName, UniqueID, Sum(Goaler" . $TypeText . "StatCareer.GP) AS SumOfGP, Sum(Goaler" . $TypeText . "StatCareer.SecondPlay) AS SumOfSecondPlay, Sum(Goaler" . $TypeText . "StatCareer.GA) AS SumOfGA FROM Goaler" . $TypeText . "StatCareer WHERE Playoff = \"" . $Playoff . "\" GROUP BY Goaler" . $TypeText . "StatCareer.Name) AS MainTable LEFT JOIN Goaler" . $TypeText . "Stat ON MainTable.SumOfName = Goaler" . $TypeText . "Stat.Name LEFT JOIN GoalerInfo ON MainTable.SumOfName = GoalerInfo.Name WHERE SumofGA >= " . ($MimimumData *  5) . " ORDER BY TotalGAA ASC, (MainTable.SumOfGP + IfNull(Goaler" . $TypeText . "Stat.GP,0)) ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound OR $Title == $CareeratabaseNotFound){$GoalerStat = Null;}else{$GoalerStat = $CareerStatdb->query($Query);}
$LoopCount = (integer)0;
if (empty($GoalerStat) == false){while ($Row = $GoalerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td>";
	If ($Row['Number'] > 0){
		echo "<td><a href=\"GoalieReport.php?Goalie=" . $Row['Number'] . "\">" . $Row['Name'] . "</a></td>";
	}else{
		echo "<td><a href=\"CareerStatGoalieReport.php?Goalie=" . $Row['SumOfName'] . "\">" . $Row['SumOfName'] . "*</a></td>";
	}	
	if ($LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False"){
		echo "<td>" . ($Row['SumOfGP'] + $Row['GP']) . "</td><td>" . number_Format($Row['TotalGAA'],2) .  "</td></tr>\n";
	}else{
		echo "<td>" . ($Row['SumOfGP']) . "</td><td>" . number_Format($Row['TotalGAA'],2) .  "</td></tr>\n";
	}
}}
If ($LoopCount > 1){
	echo "</table></td></tr>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td></tr>";
}?>

<tr>
<td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['MinutesPlayed'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['GoalieName'];?></th><th title="Games Played">GP</th><th title="Minutes Played">MP</th></tr></thead>
<?php
$Query = "SELECT MainTable.*, Goaler" . $TypeText . "Stat.*, GoalerInfo.NHLID, GoalerInfo.Country, GoalerInfo.TeamName FROM ( SELECT Name AS SumOfName, UniqueID, Sum(Goaler" . $TypeText . "StatCareer.GP) AS SumOfGP, Sum(Goaler" . $TypeText . "StatCareer.SecondPlay) AS SumOfSecondPlay FROM Goaler" . $TypeText . "StatCareer WHERE Playoff = \"" . $Playoff . "\" GROUP BY Goaler" . $TypeText . "StatCareer.Name) AS MainTable LEFT JOIN Goaler" . $TypeText . "Stat ON MainTable.SumOfName = Goaler" . $TypeText . "Stat.Name LEFT JOIN GoalerInfo ON MainTable.SumOfName = GoalerInfo.Name  ORDER BY (MainTable.SumOfSecondPlay + IfNull(Goaler" . $TypeText . "Stat.SecondPlay,0)) DESC, (MainTable.SumOfGP + IfNull(Goaler" . $TypeText . "Stat.GP,0)) ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound OR $Title == $CareeratabaseNotFound){$GoalerStat = Null;}else{$GoalerStat = $CareerStatdb->query($Query);}
$LoopCount = (integer)0;
if (empty($GoalerStat) == false){while ($Row = $GoalerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td>";
	If ($Row['Number'] > 0){
		echo "<td><a href=\"GoalieReport.php?Goalie=" . $Row['Number'] . "\">" . $Row['Name'] . "</a></td>";
	}else{
		echo "<td><a href=\"CareerStatGoalieReport.php?Goalie=" . $Row['SumOfName'] . "\">" . $Row['SumOfName'] . "*</a></td>";
	}	
	if ($LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False"){
		echo "<td>" . ($Row['SumOfGP'] + $Row['GP']) . "</td><td>" . Floor(($Row['SumOfSecondPlay'] + $Row['SecondPlay'])/60) .  "</td></tr>\n";
	}else{
		echo "<td>" . ($Row['SumOfGP']) . "</td><td>" . Floor(($Row['SumOfSecondPlay'])/60) .  "</td></tr>\n";
	}			
}}
If ($LoopCount > 1){
	echo "</table></td><td class=\"STHSWP2\"></td>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td><td class=\"STHSWP2\"></td>";
}?>

<td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['ShotsAgainst'];?></span></th></tr>
<tr><th>#</th><th title="Goalie Name"><?php echo $PlayersLang['GoalieName'];?></th><th title="Games Played">GP</th><th title="Shots Against">SA</th></tr></thead>
<?php
$Query = "SELECT MainTable.*, Goaler" . $TypeText . "Stat.*, GoalerInfo.NHLID, GoalerInfo.Country, GoalerInfo.TeamName FROM ( SELECT Name AS SumOfName, UniqueID, Sum(Goaler" . $TypeText . "StatCareer.GP) AS SumOfGP, Sum(Goaler" . $TypeText . "StatCareer.SA) AS SumOfSA FROM Goaler" . $TypeText . "StatCareer WHERE Playoff = \"" . $Playoff . "\" GROUP BY Goaler" . $TypeText . "StatCareer.Name) AS MainTable LEFT JOIN Goaler" . $TypeText . "Stat ON MainTable.SumOfName = Goaler" . $TypeText . "Stat.Name LEFT JOIN GoalerInfo ON MainTable.SumOfName = GoalerInfo.Name ORDER BY (MainTable.SumOfSA + IfNull(Goaler" . $TypeText . "Stat.SA,0)) DESC, (MainTable.SumOfGP + IfNull(Goaler" . $TypeText . "Stat.GP,0)) ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound OR $Title == $CareeratabaseNotFound){$GoalerStat = Null;}else{$GoalerStat = $CareerStatdb->query($Query);}
$LoopCount = (integer)0;
if (empty($GoalerStat) == false){while ($Row = $GoalerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td>";
	If ($Row['Number'] > 0){
		echo "<td><a href=\"GoalieReport.php?Goalie=" . $Row['Number'] . "\">" . $Row['Name'] . "</a></td>";
	}else{
		echo "<td><a href=\"CareerStatGoalieReport.php?Goalie=" . $Row['SumOfName'] . "\">" . $Row['SumOfName'] . "*</a></td>";
	}	
	if ($LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False"){
		echo "<td>" . ($Row['SumOfGP'] + $Row['GP']) . "</td><td>" . ($Row['SA'] + $Row['SumOfSA']) .  "</td></tr>\n";
	}else{
		echo "<td>" . ($Row['SumOfGP']) . "</td><td>" . ($Row['SumOfSA']) .  "</td></tr>\n";
	}		
}}
If ($LoopCount > 1){
	echo "</table></td></tr>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td></tr>";
}?>

<tr>
<td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['Shutouts'];?>
</span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['GoalieName'];?></th><th title="Games Played">GP</th><th title="Shutouts">SO</th></tr></thead>
<?php
$Query = "SELECT MainTable.*, Goaler" . $TypeText . "Stat.*, GoalerInfo.NHLID, GoalerInfo.Country, GoalerInfo.TeamName FROM ( SELECT Name AS SumOfName, UniqueID, Sum(Goaler" . $TypeText . "StatCareer.GP) AS SumOfGP, Sum(Goaler" . $TypeText . "StatCareer.Shootout) AS SumOfShootout FROM Goaler" . $TypeText . "StatCareer WHERE Playoff = \"" . $Playoff . "\" GROUP BY Goaler" . $TypeText . "StatCareer.Name) AS MainTable LEFT JOIN Goaler" . $TypeText . "Stat ON MainTable.SumOfName = Goaler" . $TypeText . "Stat.Name LEFT JOIN GoalerInfo ON MainTable.SumOfName = GoalerInfo.Name ORDER BY (MainTable.SumOfShootout + IfNull(Goaler" . $TypeText . "Stat.Shootout,0)) DESC, (MainTable.SumOfGP + IfNull(Goaler" . $TypeText . "Stat.GP,0)) ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound OR $Title == $CareeratabaseNotFound){$GoalerStat = Null;}else{$GoalerStat = $CareerStatdb->query($Query);}
$LoopCount = (integer)0;
if (empty($GoalerStat) == false){while ($Row = $GoalerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td>";
	If ($Row['Number'] > 0){
		echo "<td><a href=\"GoalieReport.php?Goalie=" . $Row['Number'] . "\">" . $Row['Name'] . "</a></td>";
	}else{
		echo "<td><a href=\"CareerStatGoalieReport.php?Goalie=" . $Row['SumOfName'] . "\">" . $Row['SumOfName'] . "*</a></td>";
	}	
	if ($LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False"){
		echo "<td>" . ($Row['SumOfGP'] + $Row['GP']) . "</td><td>" . ($Row['SumOfShootout'] + $Row['Shootout']) .  "</td></tr>\n";
	}else{
		echo "<td>" . ($Row['SumOfGP']) . "</td><td>" . ($Row['SumOfShootout']) .  "</td></tr>\n";
	}	
}}
If ($LoopCount > 1){
	echo "</table></td><td class=\"STHSWP2\"></td>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td><td class=\"STHSWP2\"></td>";
}?>

<td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['Wins'];?></span></th></tr>
<tr><th>#</th><th title="Goalie Name"><?php echo $PlayersLang['GoalieName'];?></th><th title="Games Played">GP</th><th title="Wins">W</th></tr></thead>
<?php
$Query = "SELECT MainTable.*, Goaler" . $TypeText . "Stat.*, GoalerInfo.NHLID, GoalerInfo.Country, GoalerInfo.TeamName FROM ( SELECT Name AS SumOfName, UniqueID, Sum(Goaler" . $TypeText . "StatCareer.GP) AS SumOfGP, Sum(Goaler" . $TypeText . "StatCareer.W) AS SumOfW FROM Goaler" . $TypeText . "StatCareer WHERE Playoff = \"" . $Playoff . "\" GROUP BY Goaler" . $TypeText . "StatCareer.Name) AS MainTable LEFT JOIN Goaler" . $TypeText . "Stat ON MainTable.SumOfName = Goaler" . $TypeText . "Stat.Name LEFT JOIN GoalerInfo ON MainTable.SumOfName = GoalerInfo.Name  ORDER BY (MainTable.SumOfW + IfNull(Goaler" . $TypeText . "Stat.W,0)) DESC, (MainTable.SumOfGP + IfNull(Goaler" . $TypeText . "Stat.GP,0)) ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound OR $Title == $CareeratabaseNotFound){$GoalerStat = Null;}else{$GoalerStat = $CareerStatdb->query($Query);}
$LoopCount = (integer)0;
if (empty($GoalerStat) == false){while ($Row = $GoalerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td>";
	If ($Row['Number'] > 0){
		echo "<td><a href=\"GoalieReport.php?Goalie=" . $Row['Number'] . "\">" . $Row['Name'] . "</a></td>";
	}else{
		echo "<td><a href=\"CareerStatGoalieReport.php?Goalie=" . $Row['SumOfName'] . "\">" . $Row['SumOfName'] . "*</a></td>";
	}	
	if ($LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False"){
		echo "<td>" . ($Row['SumOfGP'] + $Row['GP']) . "</td><td>" . ($Row['W'] + $Row['SumOfW']) .  "</td></tr>\n";
	}else{
		echo "<td>" . ($Row['SumOfGP']) . "</td><td>" . ($Row['SumOfW']) .  "</td></tr>\n";
	}		
}}
If ($LoopCount > 1){
	echo "</table></td></tr>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td></tr>";
}?>

<tr>
<td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['PenaltyShotsSavePCT'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['GoalieName'];?></th><th title="Penalty Shots Against">PSA</th><th title="Losses">PS %</th></tr></thead>
<?php
$Query = "SELECT MainTable.*, Goaler" . $TypeText . "Stat.*, GoalerInfo.NHLID, GoalerInfo.Country, GoalerInfo.TeamName, ROUND((CAST((MainTable.SumOfPenalityShotsShots";
if (isset($LeagueGeneral)){if ($LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False"){$Query = $Query . " + IfNull(Goaler" . $TypeText . "Stat.PenalityShotsShots,0)";}}
$Query = $Query . ") - (MainTable.SumofPenalityShotsGoals";
if (isset($LeagueGeneral)){if ($LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False"){$Query = $Query . " + IfNull(Goaler" . $TypeText . "Stat.PenalityShotsGoals,0)";}}
$Query = $Query . ") AS REAL) / (MainTable.SumOfPenalityShotsShots";
if (isset($LeagueGeneral)){if ($LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False"){$Query = $Query . " + IfNull(Goaler" . $TypeText . "Stat.PenalityShotsShots,0)";}}
$Query = $Query . ")),3) AS TotalPenalityShotsPCT FROM ( SELECT Name AS SumOfName, UniqueID, Sum(Goaler" . $TypeText . "StatCareer.GP) AS SumOfGP, Sum(Goaler" . $TypeText . "StatCareer.PenalityShotsShots) AS SumOfPenalityShotsShots, Sum(Goaler" . $TypeText . "StatCareer.PenalityShotsGoals) AS SumOfPenalityShotsGoals FROM Goaler" . $TypeText . "StatCareer WHERE Playoff = \"" . $Playoff . "\" GROUP BY Goaler" . $TypeText . "StatCareer.Name) AS MainTable LEFT JOIN Goaler" . $TypeText . "Stat ON MainTable.SumOfName = Goaler" . $TypeText . "Stat.Name LEFT JOIN GoalerInfo ON MainTable.SumOfName = GoalerInfo.Name  Where SumOfPenalityShotsShots >= " . ($MimimumData *  1) . " ORDER BY TotalPenalityShotsPCT DESC, (MainTable.SumOfGP + IfNull(Goaler" . $TypeText . "Stat.GP,0)) ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound OR $Title == $CareeratabaseNotFound){$GoalerStat = Null;}else{$GoalerStat = $CareerStatdb->query($Query);}
$LoopCount = (integer)0;
if (empty($GoalerStat) == false){while ($Row = $GoalerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td>";
	If ($Row['Number'] > 0){
		echo "<td><a href=\"GoalieReport.php?Goalie=" . $Row['Number'] . "\">" . $Row['Name'] . "</a></td>";
	}else{
		echo "<td><a href=\"CareerStatGoalieReport.php?Goalie=" . $Row['SumOfName'] . "\">" . $Row['SumOfName'] . "*</a></td>";
	}	
	if ($LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False"){
		echo "<td>" . ($Row['PenalityShotsShots'] + $Row['SumOfPenalityShotsShots']) . "</td><td>" . number_Format($Row['TotalPenalityShotsPCT'],3) .  "</td></tr>\n";
	}else{
		echo "<td>" . ($Row['SumOfPenalityShotsShots']) . "</td><td>" . number_Format($Row['TotalPenalityShotsPCT'],3) .  "</td></tr>\n";
	}		
}}
If ($LoopCount > 1){
	echo "</table></td><td class=\"STHSWP2\"></td>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td><td class=\"STHSWP2\"></td>";
}?>

<td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['Losses'];?></span></th></tr>
<tr><th>#</th><th title="Goalie Name"><?php echo $PlayersLang['GoalieName'];?></th><th title="Games Played">GP</th><th title="Losses">L</th></tr></thead>
<?php
$Query = "SELECT MainTable.*, Goaler" . $TypeText . "Stat.*, GoalerInfo.NHLID, GoalerInfo.Country, GoalerInfo.TeamName FROM ( SELECT Name AS SumOfName, UniqueID, Sum(Goaler" . $TypeText . "StatCareer.GP) AS SumOfGP, Sum(Goaler" . $TypeText . "StatCareer.L) AS SumOfL FROM Goaler" . $TypeText . "StatCareer WHERE Playoff = \"" . $Playoff . "\" GROUP BY Goaler" . $TypeText . "StatCareer.Name) AS MainTable LEFT JOIN Goaler" . $TypeText . "Stat ON MainTable.SumOfName = Goaler" . $TypeText . "Stat.Name LEFT JOIN GoalerInfo ON MainTable.SumOfName = GoalerInfo.Name  ORDER BY (MainTable.SumOfL + IfNull(Goaler" . $TypeText . "Stat.L,0)) DESC, (MainTable.SumOfGP + IfNull(Goaler" . $TypeText . "Stat.GP,0)) ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound OR $Title == $CareeratabaseNotFound){$GoalerStat = Null;}else{$GoalerStat = $CareerStatdb->query($Query);}
$LoopCount = (integer)0;
if (empty($GoalerStat) == false){while ($Row = $GoalerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td>";
	If ($Row['Number'] > 0){
		echo "<td><a href=\"GoalieReport.php?Goalie=" . $Row['Number'] . "\">" . $Row['Name'] . "</a></td>";
	}else{
		echo "<td><a href=\"CareerStatGoalieReport.php?Goalie=" . $Row['SumOfName'] . "\">" . $Row['SumOfName'] . "*</a></td>";
	}	
	if ($LeagueGeneral['PlayOffStarted'] == $Playoff AND $LeagueGeneral['PreSeasonSchedule'] == "False"){
		echo "<td>" . ($Row['SumOfGP'] + $Row['GP']) . "</td><td>" . ($Row['L'] + $Row['SumOfL']) .  "</td></tr>\n";
	}else{
		echo "<td>" . ($Row['SumOfGP']) . "</td><td>" . ($Row['SumOfL']) .  "</td></tr>\n";
	}			
}}
If ($LoopCount > 1){
	echo "</table></td></tr>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td></tr>";
}?>

</table>

<script>
$(function() {
  $(".STHSIndividualLeader_Table").tablesorter();
});
</script>

</div>



<?php include "Footer.php";?>
