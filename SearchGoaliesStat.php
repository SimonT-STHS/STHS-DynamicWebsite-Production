<?php If (isset($SearchLang) == False){include 'LanguageEN.php';} if (isset($MaximumResult) == False){$Year = -1;$GoaliesStatPossibleOrderField=array();$Playoff= Null;$OrderByInput = "";$ACSQuery = True;$MinGP = False;$MaximumResult=0;$lang="en";$Search=True;$Team=0;}?> 
<form action="GoaliesStat.php" method="get">
<table class="STHSTable">
<tr>
	<td class="STHSW200 STHSPHPSearch_Field"><?php echo $SearchLang['Team'];?></td><td class="STHSW250">
	<select name="Team" class="STHSSelect STHSW250">
	<?php
	echo "<option";	if($Search == TRUE OR $Team == 0){echo " selected=\"selected\"";}echo" value=\"\">" . $SearchLang['AllTeam'] . "</option>";
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
	<td class="STHSW200 STHSPHPSearch_Field"><?php echo $SearchLang['OrderField'];?></td><td class="STHSW250">
	<select name="Order" class="STHSSelect STHSW250">
	<?php 
	echo "<option";if($OrderByInput == ""){echo " selected=\"selected\"";} echo " value=\"\">" . $SearchLang['Select'] . "</option>";
	foreach ($GoaliesStatPossibleOrderField as $Value) {
		echo "<option";if($OrderByInput == $Value[0]){echo " selected=\"selected\"";} echo " value=\"" . $Value[0] . "\">" . $Value[1] . "</option>"; 
	} ?>
	</select></td>
</tr>
<?php if (isset($LeagueSimulationMenu)){If ($LeagueSimulationMenu['FarmEnable'] == "True"){echo "<tr><td class=\"STHSW200\">" . $SearchLang['Farm'] . "</td><td class=\"STHSW250\"><input type=\"checkbox\" name=\"Farm\"";if($TypeText == "Farm"){echo " checked";}echo "></td></tr>";}}?>
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
<?php if (isset($LeagueGeneral)){if ($LeagueGeneral['PlayOffStarted'] == "True"){echo "<tr><td class=\"STHSW200\">" . $SearchLang['SeasonStat'] . "</td><td class=\"STHSW250\"><input type=\"checkbox\" name=\"Season\"></td></tr>";}}?>
<tr>
	<td colspan="2" class="STHSCenter"><input type="submit" class="SubmitButton" value="<?php echo $SearchLang['Submit'];?>"></td>
</tr>
</table></form>