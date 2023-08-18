<?php If (isset($SearchLang) == False){include 'LanguageEN.php';} if (isset($Year) == False){$Year=-1;$Playoff=False;$MaximumResult=0;$Team=0;$HistoryFarm=False;$MinGP=0;$lang="en"; $ACSQuery=True;$PlayersStatPossibleOrderField=array();$OrderByInput="";}?> 
<form action="PlayersStat.php" method="get">
<table class="STHSTable">
<tr>	
	<td class="STHSW200 STHSPHPSearch_Field"><?php echo $SearchLang['Year'];?></td><td class="STHSW250">
	<select name="Year" class="STHSSelect STHSW250">
	<?php 
	echo "<option";if($Year == 0){echo " selected=\"selected\"";} echo " value=\"\">" .  $SearchLang['ThisSeason'] . "</option>";
	if (empty($HistoryYear) == false){while ($Row = $HistoryYear ->fetchArray()) { 
		echo "<option";if($Row['Year'] == $Year){echo " selected=\"selected\"";} echo " value=\"" . $Row['Year'] . "\">" . $Row['Year'] . "</option>"; 
	}} 
	echo "<option";if($Year == 9999){echo " selected=\"selected\"";} echo " value=\"9999\">" .  $SearchLang['AllSeasonPerYear'] . "</option>";
	echo "<option";if($Year == 9998){echo " selected=\"selected\"";} echo " value=\"9998\">" .  $SearchLang['AllSeasonMerge'] . "</option>";?>
	</select></td>	
</tr>
<tr>
	<td class="STHSW200 STHSPHPSearch_Field"><?php echo $SearchLang['Team'];?></td><td class="STHSW250">
	<select name="Team" class="STHSSelect STHSW250">
	<?php
	echo "<option";	if($Team == 0){echo " selected=\"selected\"";}echo" value=\"\">" . $SearchLang['AllTeam'] . "</option>";
	if (empty($HistoryTeam) == false){while ($Row = $HistoryTeam->fetchArray()) {
		echo "<option value=\"" . $Row['Number'] . "\""; 
		if ($Row['Number'] == $Team){echo " selected=\"selected\"";}
		echo ">" . $Row['Name'] . "</option>"; 
	}}
	?>
	</select></td>
</tr>
<tr>
	<td class="STHSW200 STHSPHPSearch_Field"><?php echo $SearchLang['Playoff'];?></td><td class="STHSW250">
	<input type="checkbox" name="Playoff"<?php if($Playoff == "True"){echo " checked";}?>></td>
</tr>
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
<?php If ($HistoryFarm == True){echo "<tr><td class=\"STHSW200\">" . $SearchLang['Farm'] . "</td><td class=\"STHSW250\"><input type=\"checkbox\" name=\"Farm\"";if($TypeText == "Farm"){echo " checked";}echo "></td></tr>";}?>
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
	<td class="STHSW200 STHSPHPSearch_Field"><?php echo $TopMenuLang['MinimumGamesPlayed'];?></td><td class="STHSW250">
	<input type="checkbox" name="MinGP"<?php if($MinGP == True){echo " checked";}?>></td>
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