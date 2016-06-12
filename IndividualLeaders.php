<!DOCTYPE html>
<?php include "Header.php";?>
<?php
$Title = (string)"";
$TypeText = (string)"Pro";$TitleType = $DynamicTitleLang['Pro'];
if(isset($_GET['Farm'])){$TypeText = "Farm";$TitleType = $DynamicTitleLang['Farm'];}
$MaximumResult = (integer)10;
$MinimumGamePlayer = (integer)1;

If (file_exists($DatabaseFile) == false){
	$LeagueName = $DatabaseNotFound;
	echo "<title>" . $DatabaseNotFound . "</title>";
	$Title = $DatabaseNotFound;
}else{
	if(isset($_GET['Max'])){$MaximumResult = filter_var($_GET['Max'], FILTER_SANITIZE_NUMBER_INT);} 
	$LeagueName = (string)"";
	$db = new SQLite3($DatabaseFile);
	$Query = "Select Name from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	$Query = "Select ProMinimumGamePlayerLeader, FarmMinimumGamePlayerLeader from LeagueOutputOption";
	$LeagueOutputOption = $db->querySingle($Query,true);		
	
	$Title = $LeagueName . " - " . $DynamicTitleLang['IndividualLeadersTitle'] . " " . $TitleType ;
	If ($TypeText == "Pro"){
		$MinimumGamePlayer = $LeagueOutputOption['ProMinimumGamePlayerLeader'];
	}elseif($TypeText == "Farm"){
		$MinimumGamePlayer = $LeagueOutputOption['FarmMinimumGamePlayerLeader'];
	}
	
	echo "<title>" . $Title ."</title>";
}?>
</head><body>
<?php include "Menu.php";?>
<?php echo "<h1>" . $Title . "</h1>"; ?>



<div style="width:99%;margin:auto;">
<b><?php echo $TeamStatLang['MinimumGamesPlayed'] . $MinimumGamePlayer;?></b><br />
<table class="STHSTableFullW">
<tr><td colspan="3"><h2 class="STHSIndividualLeader_Players STHSCenter"><?php echo $TeamLang['Players'];?></h2></td></tr>

<tr><td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['Goals'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Goals">G</th></tr></thead>
<?php
$Query = "SELECT Player" . $TypeText . "Stat.G, Player" . $TypeText . "Stat.GP, Player" . $TypeText . "Stat.Name, Player" . $TypeText . "Stat.Number, Team" . $TypeText . "Info.Abbre FROM (PlayerInfo INNER JOIN Player" . $TypeText . "Stat ON PlayerInfo.Number = Player" . $TypeText . "Stat.Number) LEFT JOIN Team" . $TypeText . "Info ON PlayerInfo.Team = Team" . $TypeText . "Info.Number WHERE (Player" . $TypeText . "Stat.GP >= " . $MinimumGamePlayer. ") AND (PlayerInfo.Team > 0) AND (Player" . $TypeText . "Stat.G > 0) ORDER BY Player" . $TypeText . "Stat.G DESC, Player" . $TypeText . "Stat.GP ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td><td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " (" . $Row['Abbre'] . ")</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['G'] .  "</td></tr>\n";
}}
If ($LoopCount > 1){
	echo "</table></td><td class=\"STHSWP2\"></td>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td><td class=\"STHSWP2\"></td>";
}?>

<td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['Assists'];?>
</span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Assists">A</th></tr></thead>
<?php
$Query = "SELECT Player" . $TypeText . "Stat.A, Player" . $TypeText . "Stat.GP, Player" . $TypeText . "Stat.Name, Player" . $TypeText . "Stat.Number, Team" . $TypeText . "Info.Abbre FROM (PlayerInfo INNER JOIN Player" . $TypeText . "Stat ON PlayerInfo.Number = Player" . $TypeText . "Stat.Number) LEFT JOIN Team" . $TypeText . "Info ON PlayerInfo.Team = Team" . $TypeText . "Info.Number WHERE (Player" . $TypeText . "Stat.GP >= " . $MinimumGamePlayer. ") AND (PlayerInfo.Team > 0) AND (Player" . $TypeText . "Stat.A > 0) ORDER BY Player" . $TypeText . "Stat.A DESC, Player" . $TypeText . "Stat.GP ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td><td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " (" . $Row['Abbre'] . ")</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['A'] .  "</td></tr>\n";
}}
If ($LoopCount > 1){
	echo "</table></td></tr>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td></tr>";
}?>

<tr><td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['Shots'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Shots">SHT</th></tr></thead>
<?php
$Query = "SELECT Player" . $TypeText . "Stat.Shots, Player" . $TypeText . "Stat.GP, Player" . $TypeText . "Stat.Name, Player" . $TypeText . "Stat.Number, Team" . $TypeText . "Info.Abbre FROM (PlayerInfo INNER JOIN Player" . $TypeText . "Stat ON PlayerInfo.Number = Player" . $TypeText . "Stat.Number) LEFT JOIN Team" . $TypeText . "Info ON PlayerInfo.Team = Team" . $TypeText . "Info.Number WHERE (Player" . $TypeText . "Stat.GP >= " . $MinimumGamePlayer. ") AND (PlayerInfo.Team > 0) AND (Player" . $TypeText . "Stat.Shots > 0) ORDER BY Player" . $TypeText . "Stat.Shots DESC, Player" . $TypeText . "Stat.GP ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td><td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " (" . $Row['Abbre'] . ")</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['Shots'] .  "</td></tr>\n";
}}
If ($LoopCount > 1){
	echo "</table></td><td class=\"STHSWP2\"></td>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td><td class=\"STHSWP2\"></td>";
}?>

<td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['ShotsPCT'];?>
</span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Shooting Percentage">SHT%</th></tr></thead>
<?php
$Query = "SELECT ROUND((CAST(Player" . $TypeText . "Stat.G AS REAL) / (Player" . $TypeText . "Stat.Shots))*100,2) AS ShotsPCT, Player" . $TypeText . "Stat.GP, Player" . $TypeText . "Stat.Name, Player" . $TypeText . "Stat.Number, Team" . $TypeText . "Info.Abbre FROM (PlayerInfo INNER JOIN Player" . $TypeText . "Stat ON PlayerInfo.Number = Player" . $TypeText . "Stat.Number) LEFT JOIN Team" . $TypeText . "Info ON PlayerInfo.Team = Team" . $TypeText . "Info.Number WHERE (Player" . $TypeText . "Stat.GP >= " . $MinimumGamePlayer. ") AND (Player" . $TypeText . "Stat.Shots > Player" . $TypeText . "Stat.GP) AND (PlayerInfo.Team > 0) AND (ShotsPCT > 0) ORDER BY ShotsPCT DESC, Player" . $TypeText . "Stat.GP ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td><td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " (" . $Row['Abbre'] . ")</a></td><td>" . $Row['GP'] . "</td><td>" . number_Format($Row['ShotsPCT'],2) . "%</td></tr>\n";
}}
If ($LoopCount > 1){
	echo "</table></td></tr>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td></tr>";
}?>

<tr><td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $TeamLang['Center'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Points">PTS</th></tr></thead>
<?php
$Query = "SELECT Player" . $TypeText . "Stat.G, Player" . $TypeText . "Stat.A, Player" . $TypeText . "Stat.P, Player" . $TypeText . "Stat.GP, Player" . $TypeText . "Stat.Name, Player" . $TypeText . "Stat.Number, Team" . $TypeText . "Info.Abbre, PlayerInfo.PosC FROM (PlayerInfo INNER JOIN Player" . $TypeText . "Stat ON PlayerInfo.Number = Player" . $TypeText . "Stat.Number) LEFT JOIN Team" . $TypeText . "Info ON PlayerInfo.Team = Team" . $TypeText . "Info.Number WHERE (Player" . $TypeText . "Stat.GP >= " . $MinimumGamePlayer. ") AND (PlayerInfo.Team > 0) AND (Player" . $TypeText . "Stat.G > 0) AND (PlayerInfo.PosC='True') ORDER BY Player" . $TypeText . "Stat.P DESC, Player" . $TypeText . "Stat.GP ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td><td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " (" . $Row['Abbre'] . ")</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['G'] . "-" . $Row['A'] . "-" . $Row['P'] . "</td></tr>\n";
}}
If ($LoopCount > 1){
	echo "</table></td><td class=\"STHSWP2\"></td>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td><td class=\"STHSWP2\"></td>";
}?>

<td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $TeamLang['LeftWing'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Points">PTS</th></tr></thead>
<?php
$Query = "SELECT Player" . $TypeText . "Stat.G, Player" . $TypeText . "Stat.A, Player" . $TypeText . "Stat.P, Player" . $TypeText . "Stat.GP, Player" . $TypeText . "Stat.Name, Player" . $TypeText . "Stat.Number, Team" . $TypeText . "Info.Abbre, PlayerInfo.PosLW FROM (PlayerInfo INNER JOIN Player" . $TypeText . "Stat ON PlayerInfo.Number = Player" . $TypeText . "Stat.Number) LEFT JOIN Team" . $TypeText . "Info ON PlayerInfo.Team = Team" . $TypeText . "Info.Number WHERE (Player" . $TypeText . "Stat.GP >= " . $MinimumGamePlayer. ") AND (PlayerInfo.Team > 0) AND (Player" . $TypeText . "Stat.G > 0) AND (PlayerInfo.PosLW='True') ORDER BY Player" . $TypeText . "Stat.P DESC, Player" . $TypeText . "Stat.GP ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td><td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " (" . $Row['Abbre'] . ")</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['G'] . "-" . $Row['A'] . "-" . $Row['P'] . "</td></tr>\n";
}}
If ($LoopCount > 1){
	echo "</table></td></tr>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td></tr>";
}?>

<tr><td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $TeamLang['RightWing'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Points">PTS</th></tr></thead>
<?php
$Query = "SELECT Player" . $TypeText . "Stat.G, Player" . $TypeText . "Stat.A, Player" . $TypeText . "Stat.P, Player" . $TypeText . "Stat.GP, Player" . $TypeText . "Stat.Name, Player" . $TypeText . "Stat.Number, Team" . $TypeText . "Info.Abbre, PlayerInfo.PosRW FROM (PlayerInfo INNER JOIN Player" . $TypeText . "Stat ON PlayerInfo.Number = Player" . $TypeText . "Stat.Number) LEFT JOIN Team" . $TypeText . "Info ON PlayerInfo.Team = Team" . $TypeText . "Info.Number WHERE (Player" . $TypeText . "Stat.GP >= " . $MinimumGamePlayer. ") AND (PlayerInfo.Team > 0) AND (Player" . $TypeText . "Stat.G > 0) AND (PlayerInfo.PosRW='True') ORDER BY Player" . $TypeText . "Stat.P DESC, Player" . $TypeText . "Stat.GP ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td><td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " (" . $Row['Abbre'] . ")</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['G'] . "-" . $Row['A'] . "-" . $Row['P'] . "</td></tr>\n";
}}
If ($LoopCount > 1){
	echo "</table></td><td class=\"STHSWP2\"></td>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td><td class=\"STHSWP2\"></td>";
}?>

<td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $TeamLang['Defenseman'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Points">PTS</th></tr></thead>
<?php
$Query = "SELECT Player" . $TypeText . "Stat.G, Player" . $TypeText . "Stat.A, Player" . $TypeText . "Stat.P, Player" . $TypeText . "Stat.GP, Player" . $TypeText . "Stat.Name, Player" . $TypeText . "Stat.Number, Team" . $TypeText . "Info.Abbre, PlayerInfo.PosD FROM (PlayerInfo INNER JOIN Player" . $TypeText . "Stat ON PlayerInfo.Number = Player" . $TypeText . "Stat.Number) LEFT JOIN Team" . $TypeText . "Info ON PlayerInfo.Team = Team" . $TypeText . "Info.Number WHERE (Player" . $TypeText . "Stat.GP >= " . $MinimumGamePlayer. ") AND (PlayerInfo.Team > 0) AND (Player" . $TypeText . "Stat.G > 0) AND (PlayerInfo.PosD='True') ORDER BY Player" . $TypeText . "Stat.P DESC, Player" . $TypeText . "Stat.GP ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td><td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " (" . $Row['Abbre'] . ")</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['G'] . "-" . $Row['A'] . "-" . $Row['P'] . "</td></tr>\n";
}}
If ($LoopCount > 1){
	echo "</table></td></tr>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td></tr>";
}?>

<tr><td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['Pointper20Minutes'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Points per 20 Minutes">P/20</th></tr></thead>
<?php
$Query = "SELECT ROUND((CAST(Player" . $TypeText . "Stat.P AS REAL) / (Player" . $TypeText . "Stat.SecondPlay) * 60 * 20),2) AS P20, Player" . $TypeText . "Stat.GP, Player" . $TypeText . "Stat.Name, Player" . $TypeText . "Stat.Number, Team" . $TypeText . "Info.Abbre FROM (PlayerInfo INNER JOIN Player" . $TypeText . "Stat ON PlayerInfo.Number = Player" . $TypeText . "Stat.Number) LEFT JOIN Team" . $TypeText . "Info ON PlayerInfo.Team = Team" . $TypeText . "Info.Number WHERE (Player" . $TypeText . "Stat.GP >= " . $MinimumGamePlayer. ") AND (PlayerInfo.Team > 0) AND (Player" . $TypeText . "Stat.P > 0) ORDER BY P20 DESC, Player" . $TypeText . "Stat.GP ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td><td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " (" . $Row['Abbre'] . ")</a></td><td>" . $Row['GP'] . "</td><td>" . number_Format($Row['P20'],2) .  "</td></tr>\n";
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
$Query = "SELECT ROUND((CAST(Player" . $TypeText . "Stat.FaceOffWon AS REAL) / (Player" . $TypeText . "Stat.FaceOffTotal))*100,2) as FaceoffPCT, Player" . $TypeText . "Stat.GP, Player" . $TypeText . "Stat.Name, Player" . $TypeText . "Stat.Number, Team" . $TypeText . "Info.Abbre FROM (PlayerInfo INNER JOIN Player" . $TypeText . "Stat ON PlayerInfo.Number = Player" . $TypeText . "Stat.Number) LEFT JOIN Team" . $TypeText . "Info ON PlayerInfo.Team = Team" . $TypeText . "Info.Number WHERE (Player" . $TypeText . "Stat.GP >= " . $MinimumGamePlayer. ") AND (Player" . $TypeText . "Stat.FaceOffTotal > (Player" . $TypeText . "Stat.GP * 5)) AND (PlayerInfo.Team > 0) ORDER BY FaceoffPCT DESC, Player" . $TypeText . "Stat.GP ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td><td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " (" . $Row['Abbre'] . ")</a></td><td>" . $Row['GP'] . "</td><td>" . number_Format($Row['FaceoffPCT'],2) . "%</td></tr>\n";
}}
If ($LoopCount > 1){
	echo "</table></td></tr>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td></tr>";
}?>

<tr><td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['PlusMinus'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Plus/Minus">+/-</th></tr></thead>
<?php
$Query = "SELECT Player" . $TypeText . "Stat.PlusMinus, Player" . $TypeText . "Stat.GP, Player" . $TypeText . "Stat.Name, Player" . $TypeText . "Stat.Number, Team" . $TypeText . "Info.Abbre FROM (PlayerInfo INNER JOIN Player" . $TypeText . "Stat ON PlayerInfo.Number = Player" . $TypeText . "Stat.Number) LEFT JOIN Team" . $TypeText . "Info ON PlayerInfo.Team = Team" . $TypeText . "Info.Number WHERE (Player" . $TypeText . "Stat.GP >= " . $MinimumGamePlayer. ") AND (PlayerInfo.Team > 0) ORDER BY Player" . $TypeText . "Stat.PlusMinus DESC, Player" . $TypeText . "Stat.GP ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td><td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " (" . $Row['Abbre'] . ")</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['PlusMinus'] .  "</td></tr>\n";
}}
If ($LoopCount > 1){
	echo "</table></td><td class=\"STHSWP2\"></td>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td><td class=\"STHSWP2\"></td>";
}?>

<td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['PenaltyMinutes'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Penalty Minutes">PIM</th></tr></thead>
<?php
$Query = "SELECT Player" . $TypeText . "Stat.Pim, Player" . $TypeText . "Stat.GP, Player" . $TypeText . "Stat.Name, Player" . $TypeText . "Stat.Number, Team" . $TypeText . "Info.Abbre FROM (PlayerInfo INNER JOIN Player" . $TypeText . "Stat ON PlayerInfo.Number = Player" . $TypeText . "Stat.Number) LEFT JOIN Team" . $TypeText . "Info ON PlayerInfo.Team = Team" . $TypeText . "Info.Number WHERE (Player" . $TypeText . "Stat.GP >= " . $MinimumGamePlayer. ") AND (PlayerInfo.Team > 0) AND (Player" . $TypeText . "Stat.Pim > 0) ORDER BY Player" . $TypeText . "Stat.Pim DESC, Player" . $TypeText . "Stat.GP ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td><td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " (" . $Row['Abbre'] . ")</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['Pim'] .  "</td></tr>\n";
}}
If ($LoopCount > 1){
	echo "</table></td></tr>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td></tr>";
}?>

<tr><td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['ShotsBlock'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Shots Blocked">SB</th></tr></thead>
<?php
$Query = "SELECT Player" . $TypeText . "Stat.ShotsBlock, Player" . $TypeText . "Stat.GP, Player" . $TypeText . "Stat.Name, Player" . $TypeText . "Stat.Number, Team" . $TypeText . "Info.Abbre FROM (PlayerInfo INNER JOIN Player" . $TypeText . "Stat ON PlayerInfo.Number = Player" . $TypeText . "Stat.Number) LEFT JOIN Team" . $TypeText . "Info ON PlayerInfo.Team = Team" . $TypeText . "Info.Number WHERE (Player" . $TypeText . "Stat.GP >= " . $MinimumGamePlayer. ") AND (PlayerInfo.Team > 0) AND (Player" . $TypeText . "Stat.ShotsBlock > 0) ORDER BY Player" . $TypeText . "Stat.ShotsBlock DESC, Player" . $TypeText . "Stat.GP ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td><td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " (" . $Row['Abbre'] . ")</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['ShotsBlock'] .  "</td></tr>\n";
}}
If ($LoopCount > 1){
	echo "</table></td><td class=\"STHSWP2\"></td>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td><td class=\"STHSWP2\"></td>";
}?>

<td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['Rookie'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Points">PTS</th></tr></thead>
<?php
$Query = "SELECT Player" . $TypeText . "Stat.G, Player" . $TypeText . "Stat.A, Player" . $TypeText . "Stat.P, Player" . $TypeText . "Stat.GP, Player" . $TypeText . "Stat.Name, Player" . $TypeText . "Stat.Number, Team" . $TypeText . "Info.Abbre, PlayerInfo.Rookie FROM (PlayerInfo INNER JOIN Player" . $TypeText . "Stat ON PlayerInfo.Number = Player" . $TypeText . "Stat.Number) LEFT JOIN Team" . $TypeText . "Info ON PlayerInfo.Team = Team" . $TypeText . "Info.Number WHERE (Player" . $TypeText . "Stat.GP >= " . $MinimumGamePlayer. ") AND (PlayerInfo.Team > 0) AND (Player" . $TypeText . "Stat.G > 0) AND (PlayerInfo.Rookie='True') ORDER BY Player" . $TypeText . "Stat.P DESC, Player" . $TypeText . "Stat.GP ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td><td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " (" . $Row['Abbre'] . ")</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['G'] . "-" . $Row['A'] . "-" . $Row['P'] . "</td></tr>\n";
}}
If ($LoopCount > 1){
	echo "</table></td></tr>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td></tr>";
}?>

<tr><td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['Hits'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Hits">HIT</th></tr></thead>
<?php
$Query = "SELECT Player" . $TypeText . "Stat.Hits, Player" . $TypeText . "Stat.GP, Player" . $TypeText . "Stat.Name, Player" . $TypeText . "Stat.Number, Team" . $TypeText . "Info.Abbre FROM (PlayerInfo INNER JOIN Player" . $TypeText . "Stat ON PlayerInfo.Number = Player" . $TypeText . "Stat.Number) LEFT JOIN Team" . $TypeText . "Info ON PlayerInfo.Team = Team" . $TypeText . "Info.Number WHERE (Player" . $TypeText . "Stat.GP >= " . $MinimumGamePlayer. ") AND (PlayerInfo.Team > 0) AND (Player" . $TypeText . "Stat.Hits > 0) ORDER BY Player" . $TypeText . "Stat.Hits DESC, Player" . $TypeText . "Stat.GP ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td><td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " (" . $Row['Abbre'] . ")</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['Hits'] .  "</td></tr>\n";
}}
If ($LoopCount > 1){
	echo "</table></td><td class=\"STHSWP2\"></td>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td><td class=\"STHSWP2\"></td>";
}?>

<td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['PowerPlayGoals'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Power Play Goals">PPG</th></tr></thead>
<?php
$Query = "SELECT Player" . $TypeText . "Stat.PPG, Player" . $TypeText . "Stat.GP, Player" . $TypeText . "Stat.Name, Player" . $TypeText . "Stat.Number, Team" . $TypeText . "Info.Abbre FROM (PlayerInfo INNER JOIN Player" . $TypeText . "Stat ON PlayerInfo.Number = Player" . $TypeText . "Stat.Number) LEFT JOIN Team" . $TypeText . "Info ON PlayerInfo.Team = Team" . $TypeText . "Info.Number WHERE (Player" . $TypeText . "Stat.GP >= " . $MinimumGamePlayer. ") AND (PlayerInfo.Team > 0) AND (Player" . $TypeText . "Stat.PPG > 0) ORDER BY Player" . $TypeText . "Stat.PPG DESC, Player" . $TypeText . "Stat.GP ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td><td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " (" . $Row['Abbre'] . ")</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['PPG'] .  "</td></tr>\n";
}}
If ($LoopCount > 1){
	echo "</table></td></tr>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td></tr>";
}?>

<tr><td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['ShortHandedGoals'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Short Handed Goals">PKG</th></tr></thead>
<?php
$Query = "SELECT Player" . $TypeText . "Stat.PKG, Player" . $TypeText . "Stat.GP, Player" . $TypeText . "Stat.Name, Player" . $TypeText . "Stat.Number, Team" . $TypeText . "Info.Abbre FROM (PlayerInfo INNER JOIN Player" . $TypeText . "Stat ON PlayerInfo.Number = Player" . $TypeText . "Stat.Number) LEFT JOIN Team" . $TypeText . "Info ON PlayerInfo.Team = Team" . $TypeText . "Info.Number WHERE (Player" . $TypeText . "Stat.GP >= " . $MinimumGamePlayer. ") AND (PlayerInfo.Team > 0) AND (Player" . $TypeText . "Stat.PKG > 0) ORDER BY Player" . $TypeText . "Stat.PKG DESC, Player" . $TypeText . "Stat.GP ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td><td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " (" . $Row['Abbre'] . ")</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['PKG'] .  "</td></tr>\n";
}}
If ($LoopCount > 1){
	echo "</table></td><td class=\"STHSWP2\"></td>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td><td class=\"STHSWP2\"></td>";
}?>

<td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['GameWinningGoals'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Game Winning Goals">GW</th></tr></thead>
<?php
$Query = "SELECT Player" . $TypeText . "Stat.GW, Player" . $TypeText . "Stat.GP, Player" . $TypeText . "Stat.Name, Player" . $TypeText . "Stat.Number, Team" . $TypeText . "Info.Abbre FROM (PlayerInfo INNER JOIN Player" . $TypeText . "Stat ON PlayerInfo.Number = Player" . $TypeText . "Stat.Number) LEFT JOIN Team" . $TypeText . "Info ON PlayerInfo.Team = Team" . $TypeText . "Info.Number WHERE (Player" . $TypeText . "Stat.GP >= " . $MinimumGamePlayer. ") AND (PlayerInfo.Team > 0) AND (Player" . $TypeText . "Stat.GW > 0) ORDER BY Player" . $TypeText . "Stat.GW DESC, Player" . $TypeText . "Stat.GP ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td><td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " (" . $Row['Abbre'] . ")</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['GW'] .  "</td></tr>\n";
}}
If ($LoopCount > 1){
	echo "</table></td></tr>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td></tr>";
}?>

<tr><td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['GameTyingGoals'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Game Tying Goals">GT</th></tr></thead>
<?php
$Query = "SELECT Player" . $TypeText . "Stat.GT, Player" . $TypeText . "Stat.GP, Player" . $TypeText . "Stat.Name, Player" . $TypeText . "Stat.Number, Team" . $TypeText . "Info.Abbre FROM (PlayerInfo INNER JOIN Player" . $TypeText . "Stat ON PlayerInfo.Number = Player" . $TypeText . "Stat.Number) LEFT JOIN Team" . $TypeText . "Info ON PlayerInfo.Team = Team" . $TypeText . "Info.Number WHERE (Player" . $TypeText . "Stat.GP >= " . $MinimumGamePlayer. ") AND (PlayerInfo.Team > 0) AND (Player" . $TypeText . "Stat.GT > 0) ORDER BY Player" . $TypeText . "Stat.GT DESC, Player" . $TypeText . "Stat.GP ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td><td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " (" . $Row['Abbre'] . ")</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['GT'] .  "</td></tr>\n";
}}
If ($LoopCount > 1){
	echo "</table></td><td class=\"STHSWP2\"></td>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td><td class=\"STHSWP2\"></td>";
}?>

<td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['EmptyNetGoals'];?>
</span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Empty Net Goals">EG</th></tr></thead>
<?php
$Query = "SELECT Player" . $TypeText . "Stat.EmptyNetGoal, Player" . $TypeText . "Stat.GP, Player" . $TypeText . "Stat.Name, Player" . $TypeText . "Stat.Number, Team" . $TypeText . "Info.Abbre FROM (PlayerInfo INNER JOIN Player" . $TypeText . "Stat ON PlayerInfo.Number = Player" . $TypeText . "Stat.Number) LEFT JOIN Team" . $TypeText . "Info ON PlayerInfo.Team = Team" . $TypeText . "Info.Number WHERE (Player" . $TypeText . "Stat.GP >= " . $MinimumGamePlayer. ") AND (PlayerInfo.Team > 0) AND (Player" . $TypeText . "Stat.EmptyNetGoal > 0) ORDER BY Player" . $TypeText . "Stat.EmptyNetGoal DESC, Player" . $TypeText . "Stat.GP ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td><td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " (" . $Row['Abbre'] . ")</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['EmptyNetGoal'] .  "</td></tr>\n";
}}
If ($LoopCount > 1){
	echo "</table></td></tr>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td></tr>";
}?>

<tr><td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['MinutesPlayed'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Minutes Played">MP</th></tr></thead>
<?php
$Query = "SELECT Player" . $TypeText . "Stat.SecondPlay, Player" . $TypeText . "Stat.GP, Player" . $TypeText . "Stat.Name, Player" . $TypeText . "Stat.Number, Team" . $TypeText . "Info.Abbre FROM (PlayerInfo INNER JOIN Player" . $TypeText . "Stat ON PlayerInfo.Number = Player" . $TypeText . "Stat.Number) LEFT JOIN Team" . $TypeText . "Info ON PlayerInfo.Team = Team" . $TypeText . "Info.Number WHERE (Player" . $TypeText . "Stat.GP >= " . $MinimumGamePlayer. ") AND (PlayerInfo.Team > 0) AND (Player" . $TypeText . "Stat.SecondPlay > 0) ORDER BY Player" . $TypeText . "Stat.SecondPlay DESC, Player" . $TypeText . "Stat.GP ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td><td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " (" . $Row['Abbre'] . ")</a></td><td>" . $Row['GP'] . "</td><td>" . Floor($Row['SecondPlay']/60) .  "</td></tr>\n";
}}
If ($LoopCount > 1){
	echo "</table></td><td class=\"STHSWP2\"></td>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td><td class=\"STHSWP2\"></td>";
}?>

<td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['HatTricks'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Hat Tricks">HT</th></tr></thead>
<?php
$Query = "SELECT Player" . $TypeText . "Stat.HatTrick, Player" . $TypeText . "Stat.GP, Player" . $TypeText . "Stat.Name, Player" . $TypeText . "Stat.Number, Team" . $TypeText . "Info.Abbre FROM (PlayerInfo INNER JOIN Player" . $TypeText . "Stat ON PlayerInfo.Number = Player" . $TypeText . "Stat.Number) LEFT JOIN Team" . $TypeText . "Info ON PlayerInfo.Team = Team" . $TypeText . "Info.Number WHERE (Player" . $TypeText . "Stat.GP >= " . $MinimumGamePlayer. ") AND (PlayerInfo.Team > 0) AND (Player" . $TypeText . "Stat.HatTrick > 0) ORDER BY Player" . $TypeText . "Stat.HatTrick DESC, Player" . $TypeText . "Stat.GP ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td><td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " (" . $Row['Abbre'] . ")</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['HatTrick'] .  "</td></tr>\n";
}}
If ($LoopCount > 1){
	echo "</table></td></tr>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td></tr>";
}?>

<tr><td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['GoalsScoringStreak'];?>
</span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Goal Scoring Streak">GS</th></tr></thead>
<?php
If($TypeText == "Pro"){
$Query = "SELECT PlayerProStat.GP, PlayerProStat.Name, PlayerInfo.Number, TeamProInfo.Abbre, PlayerInfo.Status1, PlayerInfo.GameInRowWithAGoal FROM (PlayerInfo INNER JOIN PlayerProStat ON PlayerInfo.Number = PlayerProStat.Number) LEFT JOIN TeamProInfo ON PlayerInfo.Team = TeamProInfo.Number WHERE (PlayerProStat.GP >= " . $MinimumGamePlayer. ") AND (PlayerInfo.Team>0) AND (PlayerInfo.Status1 >=2) AND (PlayerInfo.GameInRowWithAGoal > 0) ORDER BY PlayerInfo.GameInRowWithAGoal DESC , PlayerProStat.GP";
}elseIf($TypeText == "Farm"){
$Query = "SELECT PlayerFarmStat.GP, PlayerFarmStat.Name, PlayerInfo.Number, TeamFarmInfo.Abbre, PlayerInfo.Status1, PlayerInfo.GameInRowWithAGoal FROM (PlayerInfo INNER JOIN PlayerFarmStat ON PlayerInfo.Number = PlayerFarmStat.Number) LEFT JOIN TeamFarmInfo ON PlayerInfo.Team = TeamFarmInfo.Number WHERE (PlayerFarmStat.GP >= " . $MinimumGamePlayer. ") AND (PlayerInfo.Team>0) AND (PlayerInfo.Status1 <=1) AND (PlayerInfo.GameInRowWithAGoal > 0) ORDER BY PlayerInfo.GameInRowWithAGoal DESC , PlayerFarmStat.GP";
}else{$Query = "";}	
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td><td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " (" . $Row['Abbre'] . ")</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['GameInRowWithAGoal'] .  "</td></tr>\n";
}}
If ($LoopCount > 1){
	echo "</table></td><td class=\"STHSWP2\"></td>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td><td class=\"STHSWP2\"></td>";
}?>

<td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['PointsScoringStreak'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Point Scoring Streak">PS</th></tr></thead>
<?php
If($TypeText == "Pro"){
$Query = "SELECT PlayerProStat.GP, PlayerProStat.Name, PlayerInfo.Number, TeamProInfo.Abbre, PlayerInfo.Status1, PlayerInfo.GameInRowWithAPoint FROM (PlayerInfo INNER JOIN PlayerProStat ON PlayerInfo.Number = PlayerProStat.Number) LEFT JOIN TeamProInfo ON PlayerInfo.Team = TeamProInfo.Number WHERE (PlayerProStat.GP >= " . $MinimumGamePlayer. ") AND (PlayerInfo.Team>0) AND (PlayerInfo.Status1 >=2) AND (PlayerInfo.GameInRowWithAPoint > 0) ORDER BY PlayerInfo.GameInRowWithAPoint DESC , PlayerProStat.GP";
}elseIf($TypeText == "Farm"){
$Query = "SELECT PlayerFarmStat.GP, PlayerFarmStat.Name, PlayerInfo.Number, TeamFarmInfo.Abbre, PlayerInfo.Status1, PlayerInfo.GameInRowWithAPoint FROM (PlayerInfo INNER JOIN PlayerFarmStat ON PlayerInfo.Number = PlayerFarmStat.Number) LEFT JOIN TeamFarmInfo ON PlayerInfo.Team = TeamFarmInfo.Number WHERE (PlayerFarmStat.GP >= " . $MinimumGamePlayer. ") AND (PlayerInfo.Team>0) AND (PlayerInfo.Status1 <=1) AND (PlayerInfo.GameInRowWithAPoint > 0) ORDER BY PlayerInfo.GameInRowWithAPoint DESC , PlayerFarmStat.GP";
}else{$Query = "";}	
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td><td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " (" . $Row['Abbre'] . ")</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['GameInRowWithAPoint'] .  "</td></tr>\n";
}}
If ($LoopCount > 1){
	echo "</table></td></tr>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td></tr>";
}?>

<tr><td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['HitsReceived'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Hits Received">HTT</th></tr></thead>
<?php
$Query = "SELECT Player" . $TypeText . "Stat.HitsTook, Player" . $TypeText . "Stat.GP, Player" . $TypeText . "Stat.Name, Player" . $TypeText . "Stat.Number, Team" . $TypeText . "Info.Abbre FROM (PlayerInfo INNER JOIN Player" . $TypeText . "Stat ON PlayerInfo.Number = Player" . $TypeText . "Stat.Number) LEFT JOIN Team" . $TypeText . "Info ON PlayerInfo.Team = Team" . $TypeText . "Info.Number WHERE (Player" . $TypeText . "Stat.GP >= " . $MinimumGamePlayer. ") AND (PlayerInfo.Team > 0) AND (Player" . $TypeText . "Stat.HitsTook > 0) ORDER BY Player" . $TypeText . "Stat.HitsTook DESC, Player" . $TypeText . "Stat.GP ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td><td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " (" . $Row['Abbre'] . ")</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['HitsTook'] .  "</td></tr>\n";
}}
If ($LoopCount > 1){
	echo "</table></td><td class=\"STHSWP2\"></td>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td><td class=\"STHSWP2\"></td>";
}?>

<td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['PenaltyShotsGoals'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Penalty Shots Goals">PSG</th></tr></thead>
<?php
$Query = "SELECT Player" . $TypeText . "Stat.PenalityShotsScore, Player" . $TypeText . "Stat.GP, Player" . $TypeText . "Stat.Name, Player" . $TypeText . "Stat.Number, Team" . $TypeText . "Info.Abbre FROM (PlayerInfo INNER JOIN Player" . $TypeText . "Stat ON PlayerInfo.Number = Player" . $TypeText . "Stat.Number) LEFT JOIN Team" . $TypeText . "Info ON PlayerInfo.Team = Team" . $TypeText . "Info.Number WHERE (Player" . $TypeText . "Stat.GP >= " . $MinimumGamePlayer. ") AND (PlayerInfo.Team > 0) AND (Player" . $TypeText . "Stat.PenalityShotsScore > 0) ORDER BY Player" . $TypeText . "Stat.PenalityShotsScore DESC, Player" . $TypeText . "Stat.GP ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td><td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " (" . $Row['Abbre'] . ")</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['PenalityShotsScore'] .  "</td></tr>\n";
}}
If ($LoopCount > 1){
	echo "</table></td></tr>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td></tr>";
}?>

<tr><td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['GiveAways'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Give Aways">GA</th></tr></thead>
<?php
$Query = "SELECT Player" . $TypeText . "Stat.GiveAway, Player" . $TypeText . "Stat.GP, Player" . $TypeText . "Stat.Name, Player" . $TypeText . "Stat.Number, Team" . $TypeText . "Info.Abbre FROM (PlayerInfo INNER JOIN Player" . $TypeText . "Stat ON PlayerInfo.Number = Player" . $TypeText . "Stat.Number) LEFT JOIN Team" . $TypeText . "Info ON PlayerInfo.Team = Team" . $TypeText . "Info.Number WHERE (Player" . $TypeText . "Stat.GP >= " . $MinimumGamePlayer. ") AND (PlayerInfo.Team > 0) AND (Player" . $TypeText . "Stat.GiveAway > 0) ORDER BY Player" . $TypeText . "Stat.GiveAway DESC, Player" . $TypeText . "Stat.GP ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td><td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " (" . $Row['Abbre'] . ")</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['GiveAway'] .  "</td></tr>\n";
}}
If ($LoopCount > 1){
	echo "</table></td><td class=\"STHSWP2\"></td>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td><td class=\"STHSWP2\"></td>";
}?>

<td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['TakeAways'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Penalty Shots Goals">TA</th></tr></thead>
<?php
$Query = "SELECT Player" . $TypeText . "Stat.TakeAway, Player" . $TypeText . "Stat.GP, Player" . $TypeText . "Stat.Name, Player" . $TypeText . "Stat.Number, Team" . $TypeText . "Info.Abbre FROM (PlayerInfo INNER JOIN Player" . $TypeText . "Stat ON PlayerInfo.Number = Player" . $TypeText . "Stat.Number) LEFT JOIN Team" . $TypeText . "Info ON PlayerInfo.Team = Team" . $TypeText . "Info.Number WHERE (Player" . $TypeText . "Stat.GP >= " . $MinimumGamePlayer. ") AND (PlayerInfo.Team > 0) AND (Player" . $TypeText . "Stat.TakeAway > 0) ORDER BY Player" . $TypeText . "Stat.TakeAway DESC, Player" . $TypeText . "Stat.GP ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td><td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " (" . $Row['Abbre'] . ")</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['TakeAway'] .  "</td></tr>\n";
}}
If ($LoopCount > 1){
	echo "</table></td></tr>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td></tr>";
}?>

<tr><td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['TotalFight'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Total Fight">TF</th></tr></thead>
<?php
$Query = "SELECT [Player" . $TypeText . "Stat].[FightW]+[Player" . $TypeText . "Stat].[FightL]+[Player" . $TypeText . "Stat].[FightT] AS TotalFight, Player" . $TypeText . "Stat.GP, Player" . $TypeText . "Stat.Name, PlayerInfo.Number, Team" . $TypeText . "Info.Abbre FROM (PlayerInfo INNER JOIN Player" . $TypeText . "Stat ON PlayerInfo.Number = Player" . $TypeText . "Stat.Number) LEFT JOIN Team" . $TypeText . "Info ON PlayerInfo.Team = Team" . $TypeText ."Info.Number WHERE (Player" . $TypeText . "Stat.GP>=5) AND (PlayerInfo.Team>0) AND (TotalFight > 0)
ORDER BY TotalFight DESC , Player" . $TypeText . "Stat.GP ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td><td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " (" . $Row['Abbre'] . ")</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['TotalFight'] .  "</td></tr>\n";
}}
If ($LoopCount > 1){
	echo "</table></td><td class=\"STHSWP2\"></td>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td><td class=\"STHSWP2\"></td>";
}?>

<td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['FightWon'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Fight Won">FW</th></tr></thead>
<?php
$Query = "SELECT Player" . $TypeText . "Stat.FightW, Player" . $TypeText . "Stat.GP, Player" . $TypeText . "Stat.Name, Player" . $TypeText . "Stat.Number, Team" . $TypeText . "Info.Abbre FROM (PlayerInfo INNER JOIN Player" . $TypeText . "Stat ON PlayerInfo.Number = Player" . $TypeText . "Stat.Number) LEFT JOIN Team" . $TypeText . "Info ON PlayerInfo.Team = Team" . $TypeText . "Info.Number WHERE (Player" . $TypeText . "Stat.GP >= " . $MinimumGamePlayer. ") AND (PlayerInfo.Team > 0) AND (Player" . $TypeText . "Stat.FightW > 0) ORDER BY Player" . $TypeText . "Stat.FightW DESC, Player" . $TypeText . "Stat.GP ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td><td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . " (" . $Row['Abbre'] . ")</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['FightW'] .  "</td></tr>\n";
}}
If ($LoopCount > 1){
	echo "</table></td></tr>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td></tr>";
}?>

<tr><td colspan="3"><h2 class="STHSProIndividualLeader_Players STHSCenter"><?php echo $TeamLang['Goalies'];?></h2></td></tr>

<tr><td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['SavePCT'];?>
</span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Save Percentage">PCT</th></tr></thead>
<?php
$Query = "SELECT ROUND((CAST(Goaler" . $TypeText . "Stat.SA - Goaler" . $TypeText . "Stat.GA AS REAL) / (Goaler" . $TypeText . "Stat.SA)),3) AS PCT, Goaler" . $TypeText . "Stat.GP, Goaler" . $TypeText . "Stat.SecondPlay, Goaler" . $TypeText . "Stat.Name, Goaler" . $TypeText . "Stat.Number, Team" . $TypeText . "Info.Abbre FROM (GoalerInfo INNER JOIN Goaler" . $TypeText . "Stat ON GoalerInfo.Number = Goaler" . $TypeText . "Stat.Number) LEFT JOIN Team" . $TypeText . "Info ON GoalerInfo.Team = Team" . $TypeText . "Info.Number WHERE (Goaler" . $TypeText . "Stat.SecondPlay >= (" . $MinimumGamePlayer . "*3600)) AND (GoalerInfo.Team > 0) AND (PCT > 0) ORDER BY PCT DESC, Goaler" . $TypeText . "Stat.GP ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td><td><a href=\"GoalieReport.php?Goalie=" . $Row['Number'] . "\">" . $Row['Name'] . " (" . $Row['Abbre'] . ")</a></td><td>" . $Row['GP'] . "</td><td>" . number_Format($Row['PCT'],3) .  "</td></tr>\n";
}}
If ($LoopCount > 1){
	echo "</table></td><td class=\"STHSWP2\"></td>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td><td class=\"STHSWP2\"></td>";
}?>

<td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['GoalsAgainstAverage'];?></span></th></tr>
<tr><th>#</th><th title="Goalie Name"><?php echo $PlayersLang['GoalieName'];?></th><th title="Games Played">GP</th><th title="Goals Against Average">GAA</th></tr></thead>
<?php
$Query = "SELECT ROUND((CAST(Goaler" . $TypeText . "Stat.GA AS REAL) / (Goaler" . $TypeText . "Stat.SecondPlay / 60))*60,3) AS GAA, Goaler" . $TypeText . "Stat.GP, Goaler" . $TypeText . "Stat.SecondPlay, Goaler" . $TypeText . "Stat.Name, Goaler" . $TypeText . "Stat.Number, Team" . $TypeText . "Info.Abbre FROM (GoalerInfo INNER JOIN Goaler" . $TypeText . "Stat ON GoalerInfo.Number = Goaler" . $TypeText . "Stat.Number) LEFT JOIN Team" . $TypeText . "Info ON GoalerInfo.Team = Team" . $TypeText . "Info.Number WHERE (Goaler" . $TypeText . "Stat.SecondPlay >= (" . $MinimumGamePlayer . "*3600)) AND (GoalerInfo.Team > 0) AND (GAA > 0) ORDER BY GAA ASC, Goaler" . $TypeText . "Stat.GP ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$GoalerStat = Null;}else{$GoalerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($GoalerStat) == false){while ($Row = $GoalerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td><td><a href=\"GoalieReport.php?Goalie=" . $Row['Number'] . "\">" . $Row['Name'] . " (" . $Row['Abbre'] . ")</a></td><td>" . $Row['GP'] . "</td><td>" . number_Format($Row['GAA'],2) .  "</td></tr>\n";
}}
If ($LoopCount > 1){
	echo "</table></td></tr>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td></tr>";
}?>

<tr><td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['MinutesPlayed'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Minutes Played">MP</th></tr></thead>
<?php
$Query = "SELECT Goaler" . $TypeText . "Stat.SecondPlay, Goaler" . $TypeText . "Stat.GP, Goaler" . $TypeText . "Stat.SecondPlay, Goaler" . $TypeText . "Stat.Name, Goaler" . $TypeText . "Stat.Number, Team" . $TypeText . "Info.Abbre FROM (GoalerInfo INNER JOIN Goaler" . $TypeText . "Stat ON GoalerInfo.Number = Goaler" . $TypeText . "Stat.Number) LEFT JOIN Team" . $TypeText . "Info ON GoalerInfo.Team = Team" . $TypeText . "Info.Number WHERE (Goaler" . $TypeText . "Stat.SecondPlay >= (" . $MinimumGamePlayer . "*3600)) AND (GoalerInfo.Team > 0) AND (Goaler" . $TypeText . "Stat.SecondPlay > 0) ORDER BY Goaler" . $TypeText . "Stat.SecondPlay DESC, Goaler" . $TypeText . "Stat.GP ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td><td><a href=\"GoalieReport.php?Goalie=" . $Row['Number'] . "\">" . $Row['Name'] . " (" . $Row['Abbre'] . ")</a></td><td>" . $Row['GP'] . "</td><td>" . Floor($Row['SecondPlay']/60) .  "</td></tr>\n";
}}
If ($LoopCount > 1){
	echo "</table></td><td class=\"STHSWP2\"></td>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td><td class=\"STHSWP2\"></td>";
}?>

<td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['ShotsAgainst'];?></span></th></tr>
<tr><th>#</th><th title="Goalie Name"><?php echo $PlayersLang['GoalieName'];?></th><th title="Games Played">GP</th><th title="Shots Against">SA</th></tr></thead>
<?php
$Query = "SELECT Goaler" . $TypeText . "Stat.SA, Goaler" . $TypeText . "Stat.GP, Goaler" . $TypeText . "Stat.SecondPlay, Goaler" . $TypeText . "Stat.Name, Goaler" . $TypeText . "Stat.Number, Team" . $TypeText . "Info.Abbre FROM (GoalerInfo INNER JOIN Goaler" . $TypeText . "Stat ON GoalerInfo.Number = Goaler" . $TypeText . "Stat.Number) LEFT JOIN Team" . $TypeText . "Info ON GoalerInfo.Team = Team" . $TypeText . "Info.Number WHERE (Goaler" . $TypeText . "Stat.SecondPlay >= (" . $MinimumGamePlayer . "*3600)) AND (GoalerInfo.Team > 0) AND (Goaler" . $TypeText . "Stat.SA > 0) ORDER BY Goaler" . $TypeText . "Stat.SA DESC, Goaler" . $TypeText . "Stat.GP ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$GoalerStat = Null;}else{$GoalerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($GoalerStat) == false){while ($Row = $GoalerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td><td><a href=\"GoalieReport.php?Goalie=" . $Row['Number'] . "\">" . $Row['Name'] . " (" . $Row['Abbre'] . ")</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['SA'] .  "</td></tr>\n";
}}
If ($LoopCount > 1){
	echo "</table></td></tr>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td></tr>";
}?>

<tr><td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['Shutouts'];?>
</span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Games Played">GP</th><th title="Shutouts">SO</th></tr></thead>
<?php
$Query = "SELECT Goaler" . $TypeText . "Stat.Shootout, Goaler" . $TypeText . "Stat.GP, Goaler" . $TypeText . "Stat.SecondPlay, Goaler" . $TypeText . "Stat.Name, Goaler" . $TypeText . "Stat.Number, Team" . $TypeText . "Info.Abbre FROM (GoalerInfo INNER JOIN Goaler" . $TypeText . "Stat ON GoalerInfo.Number = Goaler" . $TypeText . "Stat.Number) LEFT JOIN Team" . $TypeText . "Info ON GoalerInfo.Team = Team" . $TypeText . "Info.Number WHERE (Goaler" . $TypeText . "Stat.SecondPlay >= (" . $MinimumGamePlayer . "*3600)) AND (GoalerInfo.Team > 0) AND (Goaler" . $TypeText . "Stat.Shootout > 0) ORDER BY Goaler" . $TypeText . "Stat.Shootout DESC, Goaler" . $TypeText . "Stat.GP ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td><td><a href=\"GoalieReport.php?Goalie=" . $Row['Number'] . "\">" . $Row['Name'] . " (" . $Row['Abbre'] . ")</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['Shootout'] .  "</td></tr>\n";
}}
If ($LoopCount > 1){
	echo "</table></td><td class=\"STHSWP2\"></td>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td><td class=\"STHSWP2\"></td>";
}?>

<td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['Wins'];?></span></th></tr>
<tr><th>#</th><th title="Goalie Name"><?php echo $PlayersLang['GoalieName'];?></th><th title="Games Played">GP</th><th title="Wins">W</th></tr></thead>
<?php
$Query = "SELECT Goaler" . $TypeText . "Stat.W, Goaler" . $TypeText . "Stat.GP, Goaler" . $TypeText . "Stat.SecondPlay, Goaler" . $TypeText . "Stat.Name, Goaler" . $TypeText . "Stat.Number, Team" . $TypeText . "Info.Abbre FROM (GoalerInfo INNER JOIN Goaler" . $TypeText . "Stat ON GoalerInfo.Number = Goaler" . $TypeText . "Stat.Number) LEFT JOIN Team" . $TypeText . "Info ON GoalerInfo.Team = Team" . $TypeText . "Info.Number WHERE (Goaler" . $TypeText . "Stat.SecondPlay >= (" . $MinimumGamePlayer . "*3600)) AND (GoalerInfo.Team > 0) AND (Goaler" . $TypeText . "Stat.W > 0) ORDER BY Goaler" . $TypeText . "Stat.W DESC, Goaler" . $TypeText . "Stat.GP ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$GoalerStat = Null;}else{$GoalerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($GoalerStat) == false){while ($Row = $GoalerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td><td><a href=\"GoalieReport.php?Goalie=" . $Row['Number'] . "\">" . $Row['Name'] . " (" . $Row['Abbre'] . ")</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['W'] .  "</td></tr>\n";
}}
If ($LoopCount > 1){
	echo "</table></td></tr>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td></tr>";
}?>

<tr><td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['PenaltyShotsSavePCT'];?></span></th></tr>
<tr><th>#</th><th title="Player Name"><?php echo $PlayersLang['PlayerName'];?></th><th title="Penalty Shots Against">PSA</th><th title="Losses">PS %</th></tr></thead>
<?php
$Query = "SELECT ROUND((CAST(Goaler" . $TypeText . "Stat.PenalityShotsShots - Goaler" . $TypeText . "Stat.PenalityShotsGoals AS REAL) / (Goaler" . $TypeText . "Stat.PenalityShotsShots)),3) AS PenalityShotsPCT, Goaler" . $TypeText . "Stat.PenalityShotsShots, Goaler" . $TypeText . "Stat.SecondPlay, Goaler" . $TypeText . "Stat.Name, Goaler" . $TypeText . "Stat.Number, Team" . $TypeText . "Info.Abbre FROM (GoalerInfo INNER JOIN Goaler" . $TypeText . "Stat ON GoalerInfo.Number = Goaler" . $TypeText . "Stat.Number) LEFT JOIN Team" . $TypeText . "Info ON GoalerInfo.Team = Team" . $TypeText . "Info.Number WHERE (Goaler" . $TypeText . "Stat.SecondPlay >= (" . $MinimumGamePlayer . "*3600)) AND (GoalerInfo.Team > 0) AND (PenalityShotsPCT > 0) ORDER BY PenalityShotsPCT DESC, Goaler" . $TypeText . "Stat.PenalityShotsShots DESC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$PlayerStat = Null;}else{$PlayerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($PlayerStat) == false){while ($Row = $PlayerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td><td><a href=\"GoalieReport.php?Goalie=" . $Row['Number'] . "\">" . $Row['Name'] . " (" . $Row['Abbre'] . ")</a></td><td>" . $Row['PenalityShotsShots'] . "</td><td>" . number_Format($Row['PenalityShotsPCT'],3) .  "</td></tr>\n";
}}
If ($LoopCount > 1){
	echo "</table></td><td class=\"STHSWP2\"></td>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td><td class=\"STHSWP2\"></td>";
}?>

<td class="STHSWP49"><table class="tablesorter STHSIndividualLeader_Table"><thead><tr><th colspan="4" class="sorter-false"><span class="STHSIndividualLeadersTitle"><?php echo $GeneralStatLang['Losses'];?></span></th></tr>
<tr><th>#</th><th title="Goalie Name"><?php echo $PlayersLang['GoalieName'];?></th><th title="Games Played">GP</th><th title="Losses">L</th></tr></thead>
<?php
$Query = "SELECT Goaler" . $TypeText . "Stat.L, Goaler" . $TypeText . "Stat.GP, Goaler" . $TypeText . "Stat.SecondPlay, Goaler" . $TypeText . "Stat.Name, Goaler" . $TypeText . "Stat.Number, Team" . $TypeText . "Info.Abbre FROM (GoalerInfo INNER JOIN Goaler" . $TypeText . "Stat ON GoalerInfo.Number = Goaler" . $TypeText . "Stat.Number) LEFT JOIN Team" . $TypeText . "Info ON GoalerInfo.Team = Team" . $TypeText . "Info.Number WHERE (Goaler" . $TypeText . "Stat.SecondPlay >= (" . $MinimumGamePlayer . "*3600)) AND (GoalerInfo.Team > 0) AND (Goaler" . $TypeText . "Stat.L > 0) ORDER BY Goaler" . $TypeText . "Stat.L DESC, Goaler" . $TypeText . "Stat.GP ASC";
If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
If ($Title == $DatabaseNotFound){$GoalerStat = Null;}else{$GoalerStat = $db->query($Query);}
$LoopCount = (integer)0;
if (empty($GoalerStat) == false){while ($Row = $GoalerStat ->fetchArray()) {
	$LoopCount +=1;
	echo "<tr><td>" . $LoopCount . "</td><td><a href=\"GoalieReport.php?Goalie=" . $Row['Number'] . "\">" . $Row['Name'] . " (" . $Row['Abbre'] . ")</a></td><td>" . $Row['GP'] . "</td><td>" . $Row['L'] .  "</td></tr>\n";
}}
If ($LoopCount > 1){
	echo "</table></td></tr>";
}else{
	echo "<tr><td colspan=\"4\" class=\"STHSCenter\">No Result</td></tr></table></td></tr>";
}?>

</table>

<script type="text/javascript">
$(function() {
  $(".STHSIndividualLeader_Table").tablesorter();
});
</script>

</div>



<?php include "Footer.php";?>
