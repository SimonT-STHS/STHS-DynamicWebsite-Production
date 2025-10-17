<?php If (isset($SearchLang) == False){include 'LanguageEN.php';}?> 
<form action="EntryDraftHistory.php" method="get">
<table class="STHSTable">
<tr>	
	<td class="STHSW200 STHSPHPSearch_Field"><?php echo $SearchLang['Year'];?></td><td class="STHSW250">
	<select name="Year" class="STHSSelect STHSW250">
	<?php
	$Query = "SELECT DraftYear FROM (SELECT DraftYear FROM PlayerInfo UNION SELECT DraftYear FROM GoalerInfo UNION SELECT Year AS DraftYear FROM Prospects) Where DraftYear > 0 ORDER BY DraftYear";
	If (isset($db)){$DraftYear = $db->query($Query);}
	if (empty($DraftYear) == false){while ($Row = $DraftYear ->fetchArray()) {
		echo "<option value=\"" . $Row['DraftYear'] . "\">" . $Row['DraftYear'] . "</option>"; 
	}}
	?>
	</select></td>	
</tr>
<tr>
	<td colspan="2" class="STHSCenter"><input type="submit" class="SubmitButton" value="<?php echo $SearchLang['Submit'];?>"></td>
</tr>
</table></form>