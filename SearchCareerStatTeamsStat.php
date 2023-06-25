<?php If (isset($SearchLang) == False){include 'LanguageEN.php';} if (isset($MaximumResult) == False){$Year = -1;$TeamStatPossibleOrderField = array();$Playoff= Null;$OrderByInput = "";$DESCQuery = True;$MaximumResult=0;$Search=False;$Team=0;$DatabaseFile="";$lang="en";}?> 
<form action="CareerStatTeamsStat.php" method="get">
<table class="STHSTable">
<tr>	
	<td class="STHSW200 STHSPHPSearch_Field"><?php echo $SearchLang['Year'];?></td><td class="STHSW250">
	<select name="Year" class="STHSSelect STHSW250">
	<?php 
	echo "<option";if($Year == 0){echo " selected=\"selected\"";} echo " value=\"\">" .  $SearchLang['Select'] . "</option>";
	if (empty($TeamYear) == false){while ($Row = $TeamYear ->fetchArray()) { 
		echo "<option";if($Row['Year'] == $Year){echo " selected=\"selected\"";} echo " value=\"" . $Row['Year'] . "\">" . $Row['Year'] . "</option>"; 
	}} ?>
	</select></td>	
</tr>
<tr>
	<td class="STHSW200 STHSPHPSearch_Field"><?php echo $SearchLang['TeamStatVS'];?></td><td class="STHSW250">
	<select name="Team" class="STHSSelect STHSW250">
	<?php
	echo "<option";	if($Search == TRUE OR $Team == 0){echo " selected=\"selected\"";}echo" value=\"\">" . $SearchLang['Select'] . "</option>";
	$Query = "SELECT Number, Name FROM TeamProInfo Order By Name";
	If (file_exists($DatabaseFile) ==True AND isset($db) == True){$TeamNameSearch = $db->query($Query);}
	if (empty($TeamNameSearch) == false){while ($Row = $TeamNameSearch ->fetchArray()) {
		echo "<option value=\"" . $Row['Number'] . "\""; 
		if($Search == False){if ($Row['Number'] == $Team){echo " selected=\"selected\"";}}
		echo ">" . $Row['Name'] . "</option>"; 
	}}
	?>
	</select></td>
</tr>
<tr>
	<td class="STHSW200 STHSPHPSearch_Field"><?php echo $SearchLang['OrderField'];?></td><td class="STHSW250">
	<select name="Order" class="STHSSelect STHSW250">
	<?php 
	echo "<option";if($OrderByInput == ""){echo " selected=\"selected\"";} echo " value=\"\">" . $SearchLang['Select'] . "</option>";
	foreach ($TeamStatPossibleOrderField as $Value) {
		echo "<option";if($OrderByInput == $Value[0]){echo " selected=\"selected\"";} echo " value=\"" . $Value[0] . "\">" . $Value[1] . "</option>"; 
	} ?>
	</select></td>
</tr>
<?php if (isset($LeagueSimulationMenu)){If ($LeagueSimulationMenu['FarmEnable'] == "True"){echo "<tr><td class=\"STHSW200\">" . $SearchLang['Farm'] . "</td><td class=\"STHSW250\"><input type=\"checkbox\" name=\"Farm\"";if($TypeText == "Farm"){echo " checked";}echo "></td></tr>";}}?>
<tr>
	<td class="STHSW200 STHSPHPSearch_Field"><?php echo $SearchLang['Playoff'];?></td><td class="STHSW250">
	<input type="checkbox" name="Playoff"<?php if($Playoff == "True"){echo " checked";}?>></td>
</tr>
<tr>
	<td class="STHSW200 STHSPHPSearch_Field"><?php echo $SearchLang['DecendingOrder'];?></td><td class="STHSW250">
	<?php If ($lang == "fr"){echo "<input type=\"hidden\" name=\"Lang\" value=\"fr\">";}?>
	<input type="checkbox" name="DESC"<?php if($DESCQuery == True){echo " checked";}?>></td>
</tr>
<tr>
	<td colspan="2" class="STHSCenter"><input type="submit" class="SubmitButton" value="<?php echo $SearchLang['Submit'];?>"></td>
</tr>
</table></form>