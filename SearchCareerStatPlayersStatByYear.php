<?php If (isset($SearchLang) == False){include 'LanguageEN.php';} if (isset($MaximumResult) == False){$Year = -1;$PlayersStatPossibleOrderField = array();$Playoff= Null;$OrderByInput = "";$ACSQuery = True;$MaximumResult=0;$Search=False;$Team=0;$DatabaseFile="";$lang="en";$UpdateCareerStatDBV1=False;}?> 
<form action="CareerStatPlayersStatByYear.php" method="get">
<table class="STHSTable">
<tr>
	<td class="STHSW200 STHSPHPSearch_Field"><?php echo $SearchLang['OrderField'];?></td><td class="STHSW250">
	<select name="Order" class="STHSSelect STHSW250">
	<?php 
	echo "<option";if($OrderByInput == ""){echo " selected=\"selected\"";} echo " value=\"\">" . $SearchLang['Select'] . "</option>";
	foreach ($PlayersStatPossibleOrderField as $Value) {
		echo "<option";if($OrderByInput == $Value[0]){echo " selected=\"selected\"";} echo " value=\"" . $Value[0] . "\">" . $Value[1] . "</option>"; 
	} ?>
	</select></td>
</tr>
<?php if (isset($LeagueSimulationMenu)){If ($LeagueSimulationMenu['FarmEnable'] == "True"){echo "<tr><td class=\"STHSW200\">" . $SearchLang['Farm'] . "</td><td class=\"STHSW250\"><input type=\"checkbox\" name=\"Farm\"";if($TypeText == "Farm"){echo " checked";}echo "></td></tr>";}}?>
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
	<td class="STHSW200 STHSPHPSearch_Field"><?php echo $SearchLang['Team'];?></td><td class="STHSW250">
	<select name="TeamName" class="STHSSelect STHSW250">
	<?php
	echo "<option";	if($Search == TRUE OR $Team == 0){echo " selected=\"selected\"";}echo" value=\"\">" . $SearchLang['AllTeam'] . "</option>";
	$Query = "SELECT Number, Name FROM TeamProInfo Order By Name";
	If (file_exists($DatabaseFile) ==True AND isset($db) == True){$TeamNameSearch = $db->query($Query);}
	if (empty($TeamNameSearch) == false){while ($Row = $TeamNameSearch ->fetchArray()) {
		echo "<option value=\"" . $Row['Name'] . "\""; 
		if($Search == False){if ($Row['Name'] == $TeamName){echo " selected=\"selected\"";}}
		echo ">" . $Row['Name'] . "</option>"; 
	}}
	?>
	</select></td>
</tr>
<tr>
	<td class="STHSW200 STHSPHPSearch_Field"><?php echo $SearchLang['Playoff'];?></td><td class="STHSW250">
	<input type="checkbox" name="Playoff"<?php if($Playoff == "True"){echo " checked";}?>></td>
</tr>
<?php
If ($UpdateCareerStatDBV1 == true){
	echo "<tr><td class=\"STHSW200\">" . $PlayersLang['Rookie'] . "</td><td class=\"STHSW250\"><input type=\"checkbox\" name=\"Rookie\"";if($Rookie == "True"){echo " checked";}echo "></td></tr>";
	echo "<tr><td class=\"STHSW200\">" . $PlayersLang['Position'] . "</td><td class=\"STHSW250\">C<input type=\"checkbox\" name=\"PosC\"";if($PosC == "True"){echo " checked";}echo "> / LW<input type=\"checkbox\" name=\"PosLW\"";if($PosLW == "True"){echo " checked";}echo "> / RW<input type=\"checkbox\" name=\"PosRW\"";if($PosRW == "True"){echo " checked";}echo "> / D<input type=\"checkbox\" name=\"PosD\"";if($PosD == "True"){echo " checked";}echo "></td></tr>";	
}
?>
<tr>
	<td class="STHSW200 STHSPHPSearch_Field"><?php echo $SearchLang['Max'];?></td><td class="STHSW250">
	<select name="Max" class="STHSSelect STHSW250">
	<?php echo "<option ";if($MaximumResult == 0){echo " selected=\"selected\"";}echo" value=\"\">" . $SearchLang['Unlimited']. "</option>";
	for ($i=5;$i <=100;$i = $i +5)
	{
		echo "<option";if($MaximumResult == $i){echo " selected=\"selected\"";} echo " value=\"" . $i . "\">" . $i . "</option>"; 
	}
	?>
	</select></td>
</tr>
<tr>
	<td class="STHSW200 STHSPHPSearch_Field"><?php echo $SearchLang['AcsendingOrder'];?></td><td class="STHSW250">
	<?php If ($lang == "fr"){echo "<input type=\"hidden\" name=\"Lang\" value=\"fr\">";}?>
	<input type="checkbox" name="ACS"<?php if($ACSQuery == True){echo " checked";}?>></td>
</tr>
<tr>
	<td colspan="2" class="STHSCenter"><input type="submit" class="SubmitButton" value="<?php echo $SearchLang['Submit'];?>"></td>
</tr>
</table></form>