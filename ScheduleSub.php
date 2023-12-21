<?php
if (isset($ScheduleLang) == False){include 'LanguageEN-League.php';}
if (isset($LeagueOutputOption)){
	if ($LeagueOutputOption['ScheduleUseDateInsteadofDay'] == "True"){
		echo "<th data-priority=\"1\" title=\"Day\" class=\"STHSW100\">" . $ScheduleLang['Day'] ."</th>";
	}else{
		echo "<th data-priority=\"1\" title=\"Day\" class=\"STHSW45\">" . $ScheduleLang['Day'] ."</th>";
	}
}else{
	echo "<th></th>";
}
?>
<th data-priority="2" title="Game Number" class="STHSW35"><?php echo $ScheduleLang['Game'];?></th>
<th data-priority="critical" title="Visitor Team" class="STHSW200"><?php echo $ScheduleLang['VisitorTeam'];?></th>
<th data-priority="critical" title="Visitor Team Score" class="STHSW35"><?php echo $ScheduleLang['Score'];?></th>
<th data-priority="critical" title="Home Team" class="STHSW200"><?php echo $ScheduleLang['HomeTeam'];?></th>
<th data-priority="critical" title="Home Team Score" class="STHSW35"><?php echo $ScheduleLang['Score'];?></th>
<th data-priority="6" title="Streak" class="STHSW35">ST</th>
<th data-priority="3" title="Overtime" class="STHSW35">OT</th>
<th data-priority="4" title="Shootout" class="STHSW35">SO</th>
<th data-priority="5" title="Rivalry" class="STHSW35">RI</th>
<th data-priority="2" title="Game Link" class="STHSW100"><?php echo $ScheduleLang['Link'];?></th>
</tr></thead><tbody>
<?php
$TradeDeadLine = (boolean)False;
if (isset($LeagueGeneral)){if ($LeagueGeneral['PlayOffStarted'] == "True"){$TradeDeadLine = True;}}
$LastSimulateDay = (boolean)False;
if (empty($Schedule) == false){while ($row = $Schedule ->fetchArray()) {
	If ($TradeDeadLine == False AND ($row['Day'] > (($LeagueGeneral['TradeDeadLine'] / 100) * $LeagueGeneral['ProScheduleTotalDay']))){
		$TradeDeadLine = True;
		echo "<tr class=\"static\"><td colspan=\"11\" class=\"STHSCenter\"><strong>" . $ScheduleLang['TradeDeadline'] ."</strong></td></tr>";
	}
	if ($LastSimulateDay == False AND $row['Day'] == $LeagueGeneral['ScheduleNextDay'] AND $LeagueGeneral['ScheduleNextDay'] > 1){echo "<tr><td><a id=\"Last_Simulate_Day\"></a>";$LastSimulateDay=TRUE;}else{echo "<tr><td>";}
	if ($LeagueOutputOption['ScheduleUseDateInsteadofDay'] == "True"){
		$ScheduleDate = date_create($LeagueOutputOption['ScheduleRealDate']);
		date_add($ScheduleDate, DateInterval::createFromDateString(Floor((($row['Day'] -1) / $LeagueGeneral['DefaultSimulationPerDay'])) . " days"));
		echo $row['Day'] . " - " . date_Format($ScheduleDate,"Y-m-d") . "</td>";
	}else{
		echo $row['Day']. "</td>";
	}
	
	echo "<td>" . $row['GameNumber']. "</td><td>";
	If ($row['VisitorTeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $row['VisitorTeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPScheduleTeamImage\" />";}
	echo "<span class=\"" . $TypeText . "Schedule_Team" . $row['VisitorTeam'] . "\"></span>";
	echo "<a href=\"" . $TypeText . "Team.php?Team=" . $row['VisitorTeam'] . "\">" . $row['VisitorTeamName']. "</a></td>";
	echo "<td>"; if ($row['Play'] == "True"){echo $row['VisitorScore'];} else { echo "-";};echo "</td><td>";
	If ($row['HomeTeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $row['HomeTeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPScheduleTeamImage\" />";}
	echo "<span class=\"" . $TypeText . "Schedule_Team" . $row['HomeTeam'] . "\"></span>";
	echo "<a href=\"" . $TypeText . "Team.php?Team=" . $row['HomeTeam'] . "\">" . $row['HomeTeamName']. "</a></td>";	
	echo "<td>"; if ($row['Play'] == "True"){echo $row['HomeScore'];} else { echo "-";};echo "</td>";	
	echo "<td>"; if ($row['Play'] == "True"){
	if( $row['VisitorTeam'] == $Team){
		if($row['VisitorScore'] >  $row['HomeScore']){echo "W";}elseif($row['VisitorScore'] <  $row['HomeScore']){echo "L";}else{echo "T";}
		$OtherTeam = $row['HomeTeam'];
	}else{
		if($row['HomeScore'] >  $row['VisitorScore']){echo "W";}elseif($row['HomeScore'] <  $row['VisitorScore']){echo "L";}else{echo "T";}
		$OtherTeam = $row['VisitorTeam'];
	}; 
	}else{$OtherTeam = 0;};	echo "</td>";
	echo "<td>"; if ($row['Overtime'] != "False"){echo "X";};echo "</td>";
	echo "<td>"; if ($row['Shootout'] != "False"){echo "X";};echo "</td>";
	echo "<td>";
	if (empty($RivalryInfo) == false){while ($rowR = $RivalryInfo ->fetchArray()) {
	if ($rowR['Team2'] == $OtherTeam){
		echo "R" . $rowR['Rivalry'];
		break;
	}elseif($rowR['Team1'] == $OtherTeam){
		echo "R" . $rowR['Rivalry'];
		break;
	}}}
	echo "</td>";
	echo "<td>"; if ($row['Play'] == "True" AND $row['Link'] <> "") {echo "<a href=\"" . $row['Link'] . "\" target=\"_blank\">" . $ScheduleLang['BoxScore'] . "</a>";} echo "</td>";
	echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
}}
?>