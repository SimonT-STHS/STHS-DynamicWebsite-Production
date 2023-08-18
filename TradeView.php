<?php include "Header.php";
If ($lang == "fr"){include 'LanguageFR-Main.php';}else{include 'LanguageEN-Main.php';}
$Title = (string)"";
$Team = (integer)0;
$LeagueName = (string)"";
$TradeFound = (string)False;

$AlreadyShow = array();
for($Temp1 = 0; $Temp1 <= 100; $Temp1++){
	for($Temp2 = 0; $Temp2 <= 100; $Temp2++){
		$AlreadyShow[$Temp1][$Temp2] = "";
	}
}

If (file_exists($DatabaseFile) == false){
	Goto STHSErrorTradeView;
}else{try{
	$db = new SQLite3($DatabaseFile);
	
	$Query = "Select AllowTradefromWebsite from LeagueWebClient";
	$LeagueWebClient = $db->querySingle($Query,true);
	
	$Query = "Select Name, TradeDeadLinePass from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	$Title = $TradeLang['ConfirmTrade'];
	
	If($LeagueGeneral['TradeDeadLinePass'] == "False" AND $LeagueWebClient['AllowTradefromWebsite'] == "True"){
		$Query = "Select FromTeam, ToTeam FROM TRADE WHERE (ConfirmFrom = 'True' AND ConfirmTo = 'True') GROUP BY FromTeam ";
		$TradeFromTeam = $db->query($Query);	
	}else{
		$TradeFromTeam = Null;
	}
	
	echo "<title>" . $LeagueName . " - " . $Title  . "</title>";
} catch (Exception $e) {
STHSErrorTradeView:
	$LeagueName = $DatabaseNotFound;
	$LeagueOutputOption = Null;
	echo "<title>" . $DatabaseNotFound . "</title>";
	$Title = $DatabaseNotFound;
	$TradeFromTeam = Null;
}}?>
</head><body>
<?php include "Menu.php";?>
<div style="width:99%;margin:auto;">
	<?php echo "<h1>" . $Title . "</h1>"; ?>
	<table class="STHSTableFullW">
	
<?php
if (empty($TradeFromTeam) == false){while ($Row = $TradeFromTeam ->fetchArray()) {	
	$TradeFound = True;
	$Team = $Row['FromTeam'];
	$ToTeam = $Row['ToTeam'];
	echo "<tr><td style=\"vertical-align:top\">";

	$Query = "Select * From Trade WHERE FromTeam = " . $Team . " AND ToTeam = " . $ToTeam  . " AND (ConfirmFrom = 'True' AND ConfirmTo = 'True')";
	$TradeMain =  $db->querySingle($Query,true);

	If ($AlreadyShow[$Team][$TradeMain['ToTeam']] == ""){
	$AlreadyShow[$Team][$TradeMain['ToTeam']] = "Y";
	$AlreadyShow[$TradeMain['ToTeam']][$Team] = "Y";
	
	$Query = "SELECT Number, Name, Abbre, TeamThemeID FROM TeamProInfo Where Number = " . $TradeMain['FromTeam'];
	$TeamFrom =  $db->querySingle($Query,true);
	$Query = "SELECT Number, Name, Abbre, TeamThemeID FROM TeamProInfo Where Number = " . $TradeMain['ToTeam'];
	$TeamTo =  $db->querySingle($Query,true);
	
	echo "<div class=\"STHSPHPTradeTeamName\">" .  $TradeLang['From'];
	If ($TeamFrom['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $TeamFrom['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPTradeTeamImage \" />";}
    echo $TeamFrom['Name'] . "</div><br />";
		
	$Query = "Select * From Trade WHERE FromTeam = " . $Team . " AND ToTeam = " . $ToTeam  . " AND Player > 0 AND (ConfirmFrom = 'True' AND ConfirmTo = 'True') ORDER BY Player";
	$Trade =  $db->query($Query);	
	$Count = 0;
	if (empty($Trade) == false){while ($Row = $Trade ->fetchArray()) {
		If ($Row['Player']> 0 and $Row['Player']< 10000){
			/* Players */
			$Count +=1;if ($Count > 1){echo " / ";}else{echo $TradeLang['Players'] . " : ";}
			$Query = "SELECT Name FROM PlayerInfo WHERE Number = " . $Row['Player'];
			$Data = $db->querySingle($Query,true);	
			echo $Data['Name'];
		}elseif($Row['Player']> 10000 and $Row['Player']< 11000){
			/* Goalies */
			$Count +=1;if ($Count > 1){echo " / ";}else{echo $TradeLang['Players'] . " : ";}
			$Query = "SELECT Name FROM GoalerInfo WHERE Number = (" . $Row['Player'] . " - 10000)";
			$Data = $db->querySingle($Query,true);	
			echo $Data['Name'];				
		}
	}}
	
	$Query = "Select * From Trade WHERE FromTeam = " . $Team . " AND ToTeam = " . $ToTeam  . " AND Prospect > 0 AND (ConfirmFrom = 'True' AND ConfirmTo = 'True') ORDER BY Prospect";
	$Trade =  $db->query($Query);	
	$Count = 0;
	if (empty($Trade) == false){while ($Row = $Trade ->fetchArray()) {
			$Count +=1;if ($Count > 1){echo " / ";}else{echo "<br />" . $TradeLang['Prospects'] . " : ";}
			$Query = "SELECT Name FROM Prospects WHERE Number = " . $Row['Prospect'];
			$Data = $db->querySingle($Query,true);	
			echo $Data['Name'];
	}}
	
	$Query = "Select * From Trade WHERE FromTeam = " . $Team . " AND ToTeam = " . $ToTeam  . " AND DraftPick > 0 AND (ConfirmFrom = 'True' AND ConfirmTo = 'True') ORDER BY DraftPick";
	$Trade =  $db->query($Query);	
	$Count = 0;
	if (empty($Trade) == false){while ($Row = $Trade ->fetchArray()) {
			$Count +=1;if ($Count > 1){echo " / ";}else{echo "<br />" .  $TradeLang['DraftPicks'] . " : ";}
			If ($Row['DraftPick'] >= 10000){ /* Conditionnal Draft Pick */
				$Query = "SELECT * FROM DraftPick WHERE InternalNumber = " . ($Row['DraftPick'] - 10000) . " AND TeamNumber = " . $Row['FromTeam'];
			}else{
				$Query = "SELECT * FROM DraftPick WHERE InternalNumber = " . $Row['DraftPick'] . " AND TeamNumber = " . $Row['FromTeam'];
			}
			$Data = $db->querySingle($Query,true);	
			echo "Y:" . $Data['Year'] . "-RND:" . $Data['Round'] . "-" . $Data['FromTeamAbbre'];
			If ($Row['DraftPick'] >= 10000){echo " (CON)";}
	}}
	echo "<br />";
	
	$Query = "Select Sum(Money) as SumofMoney, Sum(SalaryCapY1) as SumofSalaryCapY1, Sum(SalaryCapY2) as SumofSalaryCapY2 From Trade WHERE FromTeam = "  . $Team . " AND ToTeam = " . $ToTeam  . " AND (ConfirmFrom = 'True' AND ConfirmTo = 'True')";
	$Trade =  $db->querySingle($Query,true);	
	
	If ($Trade['SumofMoney'] > 0){echo $TradeLang['Money'] . " : "  . number_format($Trade['SumofMoney'],0) . "$<br />";}
	If ($Trade['SumofSalaryCapY1'] > 0){	echo $TradeLang['SalaryCapY1'] . " : " . number_format($Trade['SumofSalaryCapY1'] ,0) . "$<br />";}
	If ($Trade['SumofSalaryCapY2'] > 0){	echo $TradeLang['SalaryCapY2'] . " : " . number_format($Trade['SumofSalaryCapY2'] ,0) . "$<br />";}
	
	echo "</td><td style=\"vertical-align:top\">";
	echo "<div class=\"STHSPHPTradeTeamName\">" .  $TradeLang['From'];
	If ($TeamTo['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $TeamTo['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPTradeTeamImage \" />";}
	echo $TeamTo['Name'] . "</div><br />";
		
	$Query = "Select * From Trade WHERE ToTeam = " . $Team . " AND FromTeam = " . $ToTeam . " AND Player > 0 AND (ConfirmFrom = 'True' AND ConfirmTo = 'True') ORDER BY Player";
	$Trade =  $db->query($Query);	
	$Count = 0;
	if (empty($Trade) == false){while ($Row = $Trade ->fetchArray()) {
		If ($Row['Player']> 0 and $Row['Player']< 10000){
			/* Players */
			$Count +=1;if ($Count > 1){echo " / ";}else{echo $TradeLang['Players'] . " : ";}
			$Query = "SELECT Name FROM PlayerInfo WHERE Number = " . $Row['Player'];
			$Data = $db->querySingle($Query,true);	
			echo $Data['Name'];
		}elseif($Row['Player']> 10000 and $Row['Player']< 11000){
			/* Goalies */
			$Count +=1;if ($Count > 1){echo " / ";}else{echo $TradeLang['Players'] . " : ";}
			$Query = "SELECT Name FROM GoalerInfo WHERE Number = (" . $Row['Player'] . " - 10000)";
			$Data = $db->querySingle($Query,true);	
			echo $Data['Name'];				
		}
	}}
		
	$Query = "Select * From Trade WHERE ToTeam = " . $Team . " AND FromTeam = " . $ToTeam . " AND Prospect > 0 AND (ConfirmFrom = 'True' AND ConfirmTo = 'True') ORDER BY Prospect";
	$Trade =  $db->query($Query);	
	$Count = 0;
	if (empty($Trade) == false){while ($Row = $Trade ->fetchArray()) {
			$Count +=1;if ($Count > 1){echo " / ";}else{echo "<br />" . $TradeLang['Prospects'] . " : ";}
			$Query = "SELECT Name FROM Prospects WHERE Number = " . $Row['Prospect'];
			$Data = $db->querySingle($Query,true);	
			echo $Data['Name'];
	}}
	
	$Query = "Select * From Trade WHERE ToTeam = " . $Team . " AND FromTeam = " . $ToTeam . " AND DraftPick > 0 AND (ConfirmFrom = 'True' AND ConfirmTo = 'True') ORDER BY DraftPick";
	$Trade =  $db->query($Query);	
	$Count = 0;
	if (empty($Trade) == false){while ($Row = $Trade ->fetchArray()) {
			$Count +=1;if ($Count > 1){echo " / ";}else{echo "<br />" .  $TradeLang['DraftPicks'] . " : ";}
			If ($Row['DraftPick'] >= 10000){ /* Conditionnal Draft Pick */
				$Query = "SELECT * FROM DraftPick WHERE InternalNumber = " . ($Row['DraftPick'] - 10000) . " AND TeamNumber = " . $Row['FromTeam'];
			}else{
				$Query = "SELECT * FROM DraftPick WHERE InternalNumber = " . $Row['DraftPick'] . " AND TeamNumber = " . $Row['FromTeam'];
			}
			$Data = $db->querySingle($Query,true);	
			echo "Y:" . $Data['Year'] . "-RND:" . $Data['Round'] . "-" . $Data['FromTeamAbbre'];
			If ($Row['DraftPick'] >= 10000){echo " (CON)";}
	}}
	echo "<br />";
	
	$Query = "Select Sum(Money) as SumofMoney, Sum(SalaryCapY1) as SumofSalaryCapY1, Sum(SalaryCapY2) as SumofSalaryCapY2 From Trade WHERE ToTeam = "  . $Team . " AND FromTeam = " . $ToTeam . " AND (ConfirmFrom = 'True' AND ConfirmTo = 'True')";
	$Trade =  $db->querySingle($Query,true);	
		
	If ($Trade['SumofMoney'] > 0){echo $TradeLang['Money'] . " : "  . number_format($Trade['SumofMoney'],0) . "$<br />";}
	If ($Trade['SumofSalaryCapY1'] > 0){	echo $TradeLang['SalaryCapY1'] . " : " . number_format($Trade['SumofSalaryCapY1'] ,0) . "$<br />";}	
	If ($Trade['SumofSalaryCapY2'] > 0){	echo $TradeLang['SalaryCapY2'] . " : " . number_format($Trade['SumofSalaryCapY2'] ,0) . "$<br />";}	
	echo "</td></tr>";
	echo "<tr><td colspan=\"2\" class=\"STHSPHPTradeType\"><hr /><?php ?></td></tr>";
	}
}}

if ($TradeFound == False){echo "<tr><td colspan=\"2\" class=\"STHSPHPTradeType\"><div class=\"STHSDivInformationMessage\">" . $TradeLang['ViewConfirmTradeNotFound'] . "</div></td></tr>";}	
?>
	
</table>
</form>
<br />

</div>

<?php include "Footer.php";?>
