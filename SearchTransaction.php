<?php If (isset($SearchLang) == False){include 'LanguageEN.php';} if (isset($MaximumResult) == False){$MaximumResult=0;$Type=0;$TransactionType=array();$Search=False;$Team=0;$DatabaseFile="";}?> 
<form action="Transaction.php" method="get">
<table class="STHSTable">
<tr>
	<td class="STHSW200 STHSPHPSearch_Field"><?php echo $SearchLang['TransactionType'];?></td><td class="STHSW250">
	<select name="Type" class="STHSSelect STHSW250">
	<?php 
	echo "<option";if($Type == 0){echo " selected=\"selected\"";} echo " value=\"\" label=\"All\"></option>";
	foreach ($TransactionType as $Value) {
		echo "<option";if($Type == $Value[0]){echo " selected=\"selected\"";} echo " value=\"" . $Value[0] . "\">" . $Value[1] . "</option>"; 
	} ?>	
	</select></td>
</tr>
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
	<td colspan="2" class="STHSCenter"><input type="submit" class="SubmitButton" value="<?php echo $SearchLang['Submit'];?>"></td>
</tr>
</table></form>