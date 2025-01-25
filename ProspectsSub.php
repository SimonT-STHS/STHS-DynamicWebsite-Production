<?php If (isset($ProspectsLang) == False){include 'LanguageEN-League.php';} If (isset($Team) == False){$Team = (integer)-1;}If (isset($AllowProspectEdition) == False){$AllowProspectEdition =(boolean)False;}
echo "<th data-priority=\"critical\" title=\"Prospect Name\" class=\"STHSW140Min\">" . $ProspectsLang['Prospect']. "</th>";
if($Team >= 0){echo "<th class=\"columnSelector-false STHSW140Min\" data-priority=\"6\" title=\"Team Name\">" . $ProspectsLang['TeamName'] . "</th>";}else{echo "<th data-priority=\"2\" title=\"Team Name\" class=\"STHSW140Min\">" . $ProspectsLang['TeamName'] ."</th>";}
echo "<th data-priority=\"4\" title=\"Draft Year\" class=\"STHSW35\">" . $ProspectsLang['DraftYear']. "</th>";
echo "<th data-priority=\"3\" title=\"Overall Pick\" class=\"STHSW35\">" . $ProspectsLang['OverallPick']. "</th>";
echo "<th data-priority=\"3\" title=\"Information\" class=\"STHSW200\">" . $ProspectsLang['Information']. "</th>";
if ($AllowProspectEdition == True){
	echo "<th data-priority=\"2\" title=\"Hyperlink\" class=\"STHSW200\">" . $ProspectsLang['Link']. "</th>";	
	echo "<th data-priority=\"4\" title=\"Edit\" class=\"STHSW35\">" . $ProspectsLang['Edit'] . "</th>";
}else{
	echo "<th data-priority=\"6\" title=\"LastTradeDate\" class=\"STHSW55\">" . $ProspectsLang['LastTradeDate']. "</th>";
	echo "<th data-priority=\"3\" title=\"Hyperlink\" class=\"STHSW35\">" . $ProspectsLang['Link']. "</th>";
}
echo "</tr></thead><tbody>\n";
if (empty($Prospects) == false){while ($Row = $Prospects ->fetchArray()) {
	echo "<tr><td>" . $Row['Name'] . "</td><td>";
	If ($Row['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPProspectsTeamImage\">";}		
	echo $Row['TeamName'] . "</td>";
	if($AllowProspectEdition == True){
		echo "<td class=\"STHSCenter\"><input type=\"number\" min=\"0\" max=\"9999\" id=\"Year" . $Row['Number'] . "\" name=\"Year\" value=\"";If(isset($Row['Year'])){Echo $Row['Year'];}echo "\"></td>";
		echo "<td class=\"STHSCenter\"><input type=\"number\" min=\"0\" max=\"1000\" id=\"OverallPick" . $Row['Number'] . "\" name=\"OverallPick\" value=\"";If(isset($Row['OverallPick'])){Echo $Row['OverallPick'];}echo "\"></td>";
		echo "<td class=\"STHSCenter\"><input type=\"text\" id=\"Information" . $Row['Number'] . "\" name=\"Information\" value=\"";If(isset($Row['Information'])){Echo $Row['Information'];}echo "\" size=\"60\"></td>";
		echo "<td class=\"STHSCenter\"><input type=\"url\" id=\"Hyperlink" . $Row['Number'] . "\" name=\"Hyperlink\" value=\"";If(isset($Row['URLLink'])){Echo $Row['URLLink'];}echo "\" size=\"60\"></td>";
		echo "<td class=\"STHSCenter\"><input type=\"submit\" class=\"SubmitButtonSmall\" value=\"" . $ProspectsLang['Edit'] . "\" onclick=\"UpdateProspect('" . $Row['Number'] . "');\"></td>";
	}else{
		echo "<td>" . $Row['Year'] . "</td>";
		echo "<td>" . $Row['OverallPick'] . "</td>";
		echo "<td>" . $Row['Information'] . "</td>";
		echo "<td>" . $Row['LastTradeDate'] . "</td>";
		If ($Row['URLLink'] == ""){echo "<td></td>";}else{echo "<td><a href=\"" . $Row['URLLink'] . "\" target=\"new\">" . $PlayersLang['Link'] ."</a></td>";}
	}
	echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
}}
?>