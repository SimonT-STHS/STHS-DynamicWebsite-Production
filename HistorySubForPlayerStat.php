<th data-priority="3" title="Order Number" class="STHSW10 sorter-false">#</th>
<th data-priority="critical" title="Player Name" class="STHSW140Min"><?php If (isset($PlayersLang) == True){echo $PlayersLang['PlayerName'];}?></th>
<th data-priority="1" title="Games Played" class="STHSW25">GP</th>
<th data-priority="1" title="Goals" class="STHSW25">G</th>
<th data-priority="1" title="Assists" class="STHSW25">A</th>
<th data-priority="1" title="Points" class="STHSW25">P</th>
<th data-priority="2" title="Plus/Minus" class="STHSW25">+/-</th>
<th data-priority="2" title="Penalty Minutes" class="STHSW25">PIM</th>
<th data-priority="2" title="Hits" class="STHSW25">HIT</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Hits Received">HTT</th>
<th data-priority="2" title="Shots" class="STHSW25">SHT</th>
<th data-priority="3" title="Shooting Percentage" class="STHSW55">SHT%</th>
<th data-priority="3" title="Shots Blocked" class="STHSW25">SB</th>
<th data-priority="3" title="Minutes Played" class="STHSW35">MP</th>
<th data-priority="3" title="Average Minutes Played per Game" class="STHSW35">AMG</th>
<th data-priority="4" title="Power Play Goals" class="STHSW25">PPG</th>
<th data-priority="4" title="Power Play Assists" class="STHSW25">PPA</th>
<th data-priority="4" title="Power Play Points" class="STHSW25">PPP</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Power Play Shots">PPS</th>
<th data-priority="5" title="Short Handed Goals" class="STHSW25">PKG</th>
<th data-priority="5" title="Short Handed Assists" class="STHSW25">PKA</th>
<th data-priority="5" title="Short Handed Points" class="STHSW25">PKP</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Penalty Kill Shots">PKS</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Game Winning Goals">GW</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Game Tying Goals">GT</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Face offs Percentage">FO%</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Hat Tricks">HT</th>
<th data-priority="4" title="Points per 20 Minutes" class="STHSW25">P/20</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Penalty Shots Goals">PSG</th>
<th class="columnSelector-false STHSW25" data-priority="6" title="Penalty Shots Taken">PSS</th>
</tr></thead><tbody>
<?php 
$Order = 0;
if (empty($InputJson) == false){foreach($InputJson as $Row) {
	$Order +=1;
	echo "<tr><td>" . $Order ."</td>";
	If ($Row['Number'] != Null){
		echo "<td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $Row['Name'] . "</a></td>";
	}else{
		echo "<td>" . $Row['Name'] . "</td>";
	}	

	echo "<td>" . $Row['GP'] . "</td>";
	echo "<td>" . $Row['G'] . "</td>";
	echo "<td>" . $Row['A'] . "</td>";
	echo "<td>" . $Row['P'] . "</td>";
	echo "<td>" . $Row['PlusMinus'] . "</td>";
	echo "<td>" . $Row['Pim'] . "</td>";
	echo "<td>" . $Row['Hits'] . "</td>";	
	echo "<td>" . $Row['HitsTook'] . "</td>";		
	echo "<td>" . $Row['Shots'] . "</td>";
	If ($Row['ShotsPCT'] == Null){echo "<td>0%</td>";}else{echo "<td>" . number_Format($Row['ShotsPCT'],2) . "%</td>";}		
	echo "<td>" . $Row['ShotsBlock'] . "</td>";	
	echo "<td>" . Floor($Row['SecondPlay']/60) . "</td>";
	If ($Row['AMG'] == Null){echo "<td>0</td>";}else{echo "<td>" . number_Format($Row['AMG'],2) . "</td>";}
	echo "<td>" . $Row['PPG'] . "</td>";
	echo "<td>" . $Row['PPA'] . "</td>";
	echo "<td>" . $Row['PPP'] . "</td>";
	echo "<td>" . $Row['PPShots'] . "</td>";
	echo "<td>" . $Row['PKG'] . "</td>";
	echo "<td>" . $Row['PKA'] . "</td>";
	echo "<td>" . $Row['PKP'] . "</td>";
	echo "<td>" . $Row['PKShots'] . "</td>";
	echo "<td>" . $Row['GW'] . "</td>";
	echo "<td>" . $Row['GT'] . "</td>";
	If ($Row['FaceoffPCT'] == Null){echo "<td>0%</td>";}else{echo "<td>" . number_Format($Row['FaceoffPCT'],2) . "%</td>";}
	echo "<td>" . $Row['HatTrick'] . "</td>";	
	If ($Row['P20'] == Null){echo "<td>0</td>";}else{echo "<td>" . number_Format($Row['P20'],2) . "</td>";}	
	echo "<td>" . $Row['PenalityShotsScore'] . "</td>";
	echo "<td>" . $Row['PenalityShotsTotal'] . "</td>";
	echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
}}
?>
</tbody></table>