<th data-priority="critical" title="Prospect Name" class="STHSW140Min"><?php echo $ProspectsLang['Prospect'];?></th>
<?php if($Team >= 0){echo "<th class=\"columnSelector-false STHSW140Min\" data-priority=\"6\" title=\"Team Name\">" . $ProspectsLang['TeamName'] . "</th>";}else{echo "<th data-priority=\"2\" title=\"Team Name\" class=\"STHSW140Min\">" . $ProspectsLang['TeamName'] ."</th>";}?>
<th data-priority="5" title="Draft Year" class="STHSW35"><?php echo $ProspectsLang['DraftYear'];?></th>
<th data-priority="6" title="Overall Pick" class="STHSW35"><?php echo $ProspectsLang['OverallPick'];?></th>
<th data-priority="3" title="Information" class="STHSW200"><?php echo $ProspectsLang['Information'];?></th>
<th data-priority="4" title="Hyperlink" class="STHSW35"><?php echo $ProspectsLang['Link'];?></th>
</tr></thead><tbody>
<?php 
if (empty($Prospects) == false){while ($Row = $Prospects ->fetchArray()) {
	echo "<tr><td>" . $Row['Name'] . "</td>";
	echo "<td>" . $Row['TeamName'] . "</td>";
	If ($Row['Year'] != 0){echo "<td>" . $Row['Year'] . "</td>";}else{echo "<td></td>";}
	If ($Row['OverallPick'] != 0){echo "<td>" . $Row['OverallPick'] . "</td>";}else{echo "<td></td>";}
	echo "<td>" . $Row['Information'] . "</td>";	
	If ($Row['URLLink'] == ""){echo "<td></td>";}else{echo "<td><a href=\"" . $Row['URLLink'] . "\" target=\"new\">" . $PlayersLang['Link'] ."</td>";}
	echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
}}
?>