<?php If (isset($SearchLang) == False){include 'LanguageEN.php';} if (isset($MaximumResult) == False){$OrderByInput = "";$lang="en";$Type=0;$Expansion=False;$AvailableForTrade=False;$Injury=False;$Retire=False;$ACSQuery=True;$MaximumResult=0;$FreeAgentYear=0;$PlayersRosterPossibleOrderField=array();$Search=True;$Team=0;}?> 
<form action="PlayersRoster.php" method="get">
<table class="STHSTable">
<tr>
	<td class="STHSW200 STHSPHPSearch_Field"><?php echo $SearchLang['Team'];?></td><td class="STHSW250">
	<select name="Team" class="STHSSelect STHSW250">
	<?php
	echo "<option";	if($Search == TRUE){echo " selected=\"selected\"";}echo" value=\"\">" . $SearchLang['AllTeam'] . "</option>";
	echo "<option";	if($Team == 0){echo " selected=\"selected\"";}echo" value=\"0\">" . $DynamicTitleLang['Unassigned'] . "</option>";	
	$Query = "SELECT Number, Name FROM TeamProInfo Order By Name";
	If (isset($db)){$TeamNameSearch = $db->query($Query);}
	if (empty($TeamNameSearch) == false){while ($Row = $TeamNameSearch ->fetchArray()) {
		echo "<option value=\"" . $Row['Number'] . "\""; 
		if($Search == False){if ($Row['Number'] == $Team){echo " selected=\"selected\"";}}
		echo ">" . $Row['Name'] . "</option>"; 
	}}
	?>
	</select></td>
</tr>
<tr>
	<td class="STHSW200 STHSPHPSearch_Field"><?php echo $SearchLang['Type'];?></td><td class="STHSW250">
	<select name="Type" class="STHSSelect STHSW250">
	<option <?php if($Type == 0){echo " selected=\"selected\" ";}?>value="0"><?php echo $SearchLang['ProandFarm'];?></option>
	<option <?php if($Type == 1){echo " selected=\"selected\" ";}?>value="1"><?php echo $SearchLang['ProOnly'];?></option>
	<option <?php if($Type == 2){echo " selected=\"selected\" ";}?>value="2"><?php echo $SearchLang['FarmOnly'];?></option>
	</select></td>
</tr>
<tr>
	<td class="STHSW200 STHSPHPSearch_Field"><?php echo $SearchLang['OrderField'];?></td><td class="STHSW250">
	<select name="Order" class="STHSSelect STHSW250">
	<?php 
	echo "<option";if($OrderByInput == ""){echo " selected=\"selected\"";} echo " value=\"\">" . $SearchLang['Select'] . "</option>";
	foreach ($PlayersRosterPossibleOrderField as $Value) {
		echo "<option";if($OrderByInput == $Value[0]){echo " selected=\"selected\"";} echo " value=\"" . $Value[0] . "\">" . $Value[1] . "</option>"; 
	} ?>
	</select></td>
</tr>
<tr>
	<td class="STHSW200 STHSPHPSearch_Field"><?php echo $SearchLang['FreeAgents'];?></td><td class="STHSW250">
	<select name="FreeAgent" class="STHSSelect STHSW250">
	<option <?php if($FreeAgentYear == -1){echo " selected=\"selected\" ";}?>value=""><?php echo $SearchLang['Select'];?></option>
	<option <?php if($FreeAgentYear == 0){echo " selected=\"selected\" ";}?>value="0"><?php echo $SearchLang['ThisYear'];?></option>
	<option <?php if($FreeAgentYear == 1){echo " selected=\"selected\" ";}?>value="1"><?php echo $SearchLang['NextYear'];?></option>
	<option <?php if($FreeAgentYear == 2){echo " selected=\"selected\" ";}?>value="2"><?php echo $SearchLang['In2Years'];?></option>
	<option <?php if($FreeAgentYear == 3){echo " selected=\"selected\" ";}?>value="3"><?php echo $SearchLang['In3Years'];?></option>
	<option <?php if($FreeAgentYear == 4){echo " selected=\"selected\" ";}?>value="4"><?php echo $SearchLang['In4Years'];?></option>
	<option <?php if($FreeAgentYear == 5){echo " selected=\"selected\" ";}?>value="5"><?php echo $SearchLang['In5Years'];?></option>
	</select></td>
</tr>
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
	<td class="STHSW200 STHSPHPSearch_Field"><?php echo $SearchLang['ExpansionDraft'];?></td><td class="STHSW250">
	<input type="checkbox" name="Expansion"<?php if($Expansion == True){echo " checked";}?>></td>
</tr>
<tr>
	<td class="STHSW200 STHSPHPSearch_Field"><?php echo $SearchLang['AvailableForTrade'];?></td><td class="STHSW250">
	<input type="checkbox" name="AvailableForTrade"<?php if($AvailableForTrade == True){echo " checked";}?>></td>
</tr>
<tr>
	<td class="STHSW200 STHSPHPSearch_Field"><?php echo $SearchLang['Injury'];?></td><td class="STHSW250">
	<input type="checkbox" name="Injury"<?php if($Injury == True){echo " checked";}?>></td>
</tr>
<tr>
	<td class="STHSW200 STHSPHPSearch_Field"><?php echo $SearchLang['Retire'];?></td><td class="STHSW250">
	<input type="checkbox" name="Retire"<?php if($Retire == "'True'"){echo " checked";}?>></td>
</tr>
<tr>
	<td colspan="2" class="STHSCenter"><input type="submit" class="SubmitButton" value="<?php echo $SearchLang['Submit'];?>"></td>
</tr>
</table></form>