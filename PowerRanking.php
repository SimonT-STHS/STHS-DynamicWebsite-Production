<!DOCTYPE html>
<?php include "Header.php";?>
<?php
$LeagueName = (string)"";
$TypeText = (string)"Pro";$TitleType = $DynamicTitleLang['Pro'];
	if(isset($_GET['Farm'])){$TypeText = "Farm";$TitleType = $DynamicTitleLang['Farm'];}

If (file_exists($DatabaseFile) == false){
	$LeagueName = $DatabaseNotFound;
	$PowerRanking = Null;
}else{

	$db = new SQLite3($DatabaseFile);
	$Query = "SELECT PowerRanking" . $TypeText . ".*, Team" . $TypeText . "Info.Name FROM PowerRanking" . $TypeText . " LEFT JOIN Team" . $TypeText . "Info ON PowerRanking" . $TypeText . ".Teams = Team" . $TypeText . "Info.Number ORDER BY PowerRanking" . $TypeText . ".TodayRanking;";
	$PowerRanking = $db->query($Query);

	$Query = "Select Name, OutputName from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
}
echo "<title>" . $LeagueName . " - " . $PowerRankingLang['PowerRanking'] . " " . $TitleType . "</title>";

?>
</head><body>
<?php include "Menu.php";?>
<br />

<script type="text/javascript">$(function(){$(".STHSPowerRanking_Table").tablesorter();});</script>

<div style="width:95%;margin:auto;">
<h1><?php echo $PowerRankingLang['PowerRanking'] . " " . $TitleType;?></h1>
<table class="STHSPowerRanking_Table tablesorter"><thead><tr>
<th title="Actual Rank" class="STHSW35"><?php echo $PowerRankingLang['ActualRank'];?></th>
<th title="Last Rank" class="STHSW35"><?php echo $PowerRankingLang['LastRank'];?></th>
<th title="Team Name" class="STHSW200"><?php echo $PowerRankingLang['TeamName'];?></th>
<th title="Points" class="STHSW45">Points</th>
<th title="Wins" class="STHSW25">W</th>
<th title="Loss" class="STHSW25">L</th>
<th title="Ties" class="STHSW25">T</th>
<th title="Overtime Loss" class="STHSW25">OTL</th>
<th title="Shootout Loss" class="STHSW25">SOL</th>
<th title="Goals For" class="STHSW25">GF</th>
<th title="Goals Against" class="STHSW25">GA</th>
</tr></thead>
<tbody>
<?php
if (empty($PowerRanking) == false){while ($Row = $PowerRanking ->fetchArray()) {
	echo "<tr><td>" . $Row['TodayRanking'] . "</td>";
	echo "<td>" . $Row['LastRanking'] . "</td>";
	echo "<td>" . $Row['Name'] . "</td>";
	echo "<td>" . $Row['Points'] . "</td>";
	echo "<td>" . $Row['W'] . "</td>";
	echo "<td>" . $Row['L'] . "</td>";
	echo "<td>" . $Row['T'] . "</td>";
	echo "<td>" . $Row['OTL'] . "</td>";
	echo "<td>" . $Row['SOL'] . "</td>";	
	echo "<td>" . $Row['GF'] . "</td>";	
	echo "<td>" . $Row['GA'] . "</td>";		
	echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
}}
?>
</tbody></table>
</div>

<?php include "Footer.php";?>
