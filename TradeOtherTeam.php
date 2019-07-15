<!DOCTYPE html>
<?php include "Header.php";?>
<?php
$Title = (string)"";
$Active = 2; /* Show Webpage Top Menu */
$Team = (integer)0;
$Password = (string)"";
$Confirm = False;
$Refuse = False;
$InformationMessage = (string)"";
$TradeLog = (string)"";
$TeamName = (string)"";

If (file_exists($DatabaseFile) == false){
	$LeagueName = $DatabaseNotFound;
	$LeagueOutputOption = Null;
	echo "<title>" . $DatabaseNotFound . "</title>";
	$Title = $DatabaseNotFound;
	echo "<style>#Trade{display:none}</style>";
}else{
	$db = new SQLite3($DatabaseFile);
	
	$LeagueName = (string)"";
	if(isset($_POST['Team'])){$Team = filter_var($_POST['Team'], FILTER_SANITIZE_NUMBER_INT);}
	if(isset($_POST['Submit'])){
		if ($_POST['Submit'] == $TradeLang['ConfirmSubmit'] ){
			if(isset($_POST["Password"]) && !empty($_POST["Password"])) {
				$Password = filter_var($_POST["Password"], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH);
				/* GM Hash */
				$Query = "SELECT GMName, WebPassword FROM TeamProInfo WHERE Number = " . $Team;
				$TeamPassword = $db->querySingle($Query,true);

				/* Confirm GM Hash */
				$GMCalculateHash = strtoupper(Hash('sha512', mb_convert_encoding(($TeamPassword['GMName'] . $Password), 'ASCII')));
				$GMDatabaseHash = $TeamPassword['WebPassword'];
				If ($GMCalculateHash == $GMDatabaseHash && $GMDatabaseHash != ""){$Confirm = True;}else{$InformationMessage = $News['IncorrectPassword'];}			
				}
		}
		if ($_POST['Submit'] == $TradeLang['RefuseSubmit']){
			if(isset($_POST["Password"]) && !empty($_POST["Password"])) {
				$Password = filter_var($_POST["Password"], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH);
				/* GM Hash */
				$Query = "SELECT GMName, WebPassword FROM TeamProInfo WHERE Number = " . $Team;
				$TeamPassword = $db->querySingle($Query,true);

				/* Refuse GM Hash */
				$GMCalculateHash = strtoupper(Hash('sha512', mb_convert_encoding(($TeamPassword['GMName'] . $Password), 'ASCII')));
				$GMDatabaseHash = $TeamPassword['WebPassword'];
				If ($GMCalculateHash == $GMDatabaseHash && $GMDatabaseHash != ""){$Refuse = True;}else{$InformationMessage = $News['IncorrectPassword'];}			
			}
		}
	}	
	If ($Team > 0 and $Team <= 100){
		$Query = "SELECT Number, Name FROM TeamProInfo Where Number = " . $Team;
		$TeamInfo =  $db->querySingle($Query,true);
		If ($TeamInfo != Null){$TeamName = $TeamInfo['Name'];}else{$TeamName="";}
		$Query = "SELECT Count(DISTINCT FromTeam) as CountNumber FROM TRADE WHERE ToTeam = " . $Team . " AND ConfirmTo = 'False'";
		$Result = $db->querySingle($Query,true);
		If ($Result['CountNumber'] == 0){
			echo "<style>#Trade{display:none}</style>";
			$InformationMessage = $TradeLang['NoTrade']. $TeamName;
		}
	}else{
		echo "<style>#Trade{display:none}</style>";
		$TeamInfo = Null;
	}
	
	$Query = "Select Name, TradeDeadLine from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	$Title = $TradeLang['Trade'];
	
	echo "<title>" . $LeagueName . " - " . $TradeLang['Trade']  . "</title>";
}?>
</head><body>
<?php include "Menu.php";?>
<?php echo "<h1>" . $Title . "</h1>"; ?>
<br />

<div style="width:99%;margin:auto;">
<?php if ($InformationMessage != ""){echo "<div style=\"color:#FF0000; font-weight: bold;padding:1px 1px 1px 5px;text-align:center;\">" . $InformationMessage . "<br /><br /></div>";}?>
<form id="Trade" name="Trade" method="post" action="TradeOtherTeam.php<?php If ($lang == "fr" ){echo "?Lang=fr";}?>">
	<input type="hidden" id="Team" name="Team" value="<?php echo $Team;?>">
	<table class="STHSTableFullW">
	<tr>
		<td colspan="2" class="STHSPHPTradeTeamName"><?php echo $TeamName . $TradeLang['TradeOffer']?></td>
	</tr>
	
	
	<tr><td colspan="2" class="STHSPHPTradeType"><hr /><?php ?></td></tr>
	<tr><td style="vertical-align:top">
	
	<?php
	$Query = "Select * From Trade WHERE FromTeam = " . $Team . " AND (ConfirmFrom = 'False' Or ConfirmTo = 'False')";
	$TradeMain =  $db->querySingle($Query,true);
	
	If ($TradeMain != Null){
	$Query = "SELECT Number, Name, Abbre FROM TeamProInfo Where Number = " . $TradeMain['FromTeam'];
	$TeamFrom =  $db->querySingle($Query,true);
	$Query = "SELECT Number, Name, Abbre FROM TeamProInfo Where Number = " . $TradeMain['ToTeam'];
	$TeamTo =  $db->querySingle($Query,true);
	
	echo "<div class=\"STHSPHPTradeTeamName\">" .  $TradeLang['From'] . $TeamFrom['Name'] . "</div><br />";	
	
	$TradeLog = "TRADE : From " . $TeamFrom['Name'] . " to " . $TeamTo['Name'] . " : ";
	
	$Query = "Select * From Trade WHERE FromTeam = " . $Team . " AND Player > 0 AND (ConfirmFrom = 'False' Or ConfirmTo = 'False') ORDER BY Player";
	$Trade =  $db->query($Query);	
	$Count = 0;
	if (empty($Trade) == false){while ($Row = $Trade ->fetchArray()) {
		If ($Row['Player']> 0 and $Row['Player']< 10000){
			/* Players */
			$Count +=1;if ($Count > 1){echo " / ";}else{echo $TradeLang['Players'] . " : ";}
			$Query = "SELECT Name FROM PlayerInfo WHERE Number = " . $Row['Player'];
			$Data = $db->querySingle($Query,true);	
			echo $Data['Name'];
			$TradeLog = $TradeLog . $Data['Name'] . ",";
			If ($Confirm == True){
				// Update Player in Database
				$Query = "UPDATE PlayerInfo SET Team = '". $TeamTo['Number'] . "', TeamName = '" . $TeamTo['Name'] . "' WHERE Number = " . $Row['Player'];
				try {
					$db->exec($Query);
				} catch (Exception $e) {
					echo $TradeLang['FailPlayerUpdate'];
				}
			}
		}elseif($Row['Player']> 10000 and $Row['Player']< 11000){
			/* Goalies */
			$Count +=1;if ($Count > 1){echo " / ";}else{echo $TradeLang['Players'] . " : ";}
			$Query = "SELECT Name FROM GoalerInfo WHERE Number = (" . $Row['Player'] . " - 10000)";
			$Data = $db->querySingle($Query,true);	
			echo $Data['Name'];		
			$TradeLog = $TradeLog . $Data['Name']. ",";	
			If ($Confirm == True){
				// Update Goalies in Database
				$Query = "UPDATE GoalerInfo SET Team = '". $TeamTo['Number'] . "', TeamName = '" . $TeamTo['Name'] . "' WHERE Number = (" . $Row['Player'] . " - 10000)";
				try {
					$db->exec($Query);
				} catch (Exception $e) {
					echo $TradeLang['FailPlayerUpdate'];
				}
			}
		}
	}}
	
	$Query = "Select * From Trade WHERE FromTeam = " . $Team . " AND Prospect > 0 AND (ConfirmFrom = 'False' Or ConfirmTo = 'False') ORDER BY Prospect";
	$Trade =  $db->query($Query);	
	$Count = 0;
	if (empty($Trade) == false){while ($Row = $Trade ->fetchArray()) {
			$Count +=1;if ($Count > 1){echo " / ";}else{echo "<br />" . $TradeLang['Prospects'] . " : ";}
			$Query = "SELECT Name FROM Prospects WHERE Number = " . $Row['Prospect'];
			$Data = $db->querySingle($Query,true);	
			echo $Data['Name'];
			$TradeLog = $TradeLog . $Data['Name']. ",";
	}}
	
	$Query = "Select * From Trade WHERE FromTeam = " . $Team . " AND DraftPick > 0 AND (ConfirmFrom = 'False' Or ConfirmTo = 'False') ORDER BY DraftPick";
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
			If ($Row['DraftPick'] >= 10000){
				echo " (CON)";
				$TradeLog = $TradeLog . "Y:" . $Data['Year'] . "-RND:" . $Data['Round'] . "-" . $Data['FromTeamAbbre'] . " (CON),";
			}else{
				$TradeLog = $TradeLog . "Y:" . $Data['Year'] . "-RND:" . $Data['Round'] . "-" . $Data['FromTeamAbbre'] . ",";
			}
	}}
	echo "<br />";
	
	$Query = "Select Sum(Money) as SumofMoney, Sum(SalaryCap) as SumofSalaryCap From Trade WHERE FromTeam = "  . $Team . " AND (ConfirmFrom = 'False' Or ConfirmTo = 'False')";
	$Trade =  $db->querySingle($Query,true);	
	
	If ($Trade['SumofMoney'] > 0){
		echo $TradeLang['Money'] . " : "  . number_format($Trade['SumofMoney'],0) . "$<br />";
		$TradeLog = $TradeLog . $TradeLang['Money'] . " : "  . number_format($Trade['SumofMoney'],0). ",";
		}
	If ($Trade['SumofSalaryCap'] > 0){	
		echo $TradeLang['SalaryCap'] . " : " . number_format($Trade['SumofSalaryCap'] ,0) . "$<br />";
		$TradeLog = $TradeLog . $TradeLang['SalaryCap'] . " : " . number_format($Trade['SumofSalaryCap'] ,0). ",";
	}
	
	If ($Confirm == True){
		/* Create Entry */
		$Query = "UPDATE TRADE SET ConfirmFrom = 'True' WHERE FromTeam = " . $Team . " AND ToTeam = " . $TradeMain['ToTeam'] ;
		try {
			$db->exec($Query);
		} catch (Exception $e) {
			echo $TradeLang['Fail'];
		}
		
		$Query = "INSERT Into LeagueLog (Number, Text, DateTime, TransactionType) VALUES ('" . rand(90000,99999) . "','" . $TradeLog . "','" . gmdate('Y-m-d H:i:s') . "','1')";
		try {
			$db->exec($Query);
		} catch (Exception $e) {
		}
	}elseif ($Refuse == True){
		/* Delete Entry */
		$Query = "DELETE from TRADE WHERE FromTeam = " . $Team . " AND ToTeam = " . $TradeMain['ToTeam'] ;
		try {
			$db->exec($Query);
		} catch (Exception $e) {
			echo $TradeLang['Fail'];
		}
	}
	
	echo "</td><td style=\"vertical-align:top\">";
	echo "<div class=\"STHSPHPTradeTeamName\">" .  $TradeLang['From'] . $TeamTo['Name'] . "</div><br />";
	
	$TradeLog = "TRADE : From " . $TeamTo['Name'] . " to " . $TeamFrom['Name'] . " : ";
	
	$Query = "Select * From Trade WHERE ToTeam = " . $Team . "  AND Player > 0 AND (ConfirmFrom = 'False' Or ConfirmTo = 'False') ORDER BY Player";
	$Trade =  $db->query($Query);	
	$Count = 0;
	if (empty($Trade) == false){while ($Row = $Trade ->fetchArray()) {
		If ($Row['Player']> 0 and $Row['Player']< 10000){
			/* Players */
			$Count +=1;if ($Count > 1){echo " / ";}else{echo $TradeLang['Players'] . " : ";}
			$Query = "SELECT Name FROM PlayerInfo WHERE Number = " . $Row['Player'];
			$Data = $db->querySingle($Query,true);	
			echo $Data['Name'];
			$TradeLog = $TradeLog . $Data['Name'] . ",";
			If ($Confirm == True){
				// Update Player in Database
				$Query = "UPDATE PlayerInfo SET Team = '". $TeamFrom['Number'] . "', TeamName = '" . $TeamFrom['Name'] . "' WHERE Number = " . $Row['Player'];
				try {
					$db->exec($Query);
				} catch (Exception $e) {
					echo $TradeLang['FailPlayerUpdate'];
				}
			}			
		}elseif($Row['Player']> 10000 and $Row['Player']< 11000){
			/* Goalies */
			$Count +=1;if ($Count > 1){echo " / ";}else{echo $TradeLang['Players'] . " : ";}
			$Query = "SELECT Name FROM GoalerInfo WHERE Number = (" . $Row['Player'] . " - 10000)";
			$Data = $db->querySingle($Query,true);	
			echo $Data['Name'];				
			$TradeLog = $TradeLog . $Data['Name'] . ",";
			If ($Confirm == True){
				// Update Goalies in Database
				$Query = "UPDATE GoalerInfo SET Team = '". $TeamFrom['Number'] . "', TeamName = '" . $TeamFrom['Name'] . "' WHERE Number = (" . $Row['Player'] . " - 10000)";
				try {
					$db->exec($Query);
				} catch (Exception $e) {
					echo $TradeLang['FailPlayerUpdate'];
				}
			}
		}
	}}
		
	$Query = "Select * From Trade WHERE ToTeam = " . $Team . "  AND Prospect > 0 AND (ConfirmFrom = 'False' Or ConfirmTo = 'False') ORDER BY Prospect";
	$Trade =  $db->query($Query);	
	$Count = 0;
	if (empty($Trade) == false){while ($Row = $Trade ->fetchArray()) {
			$Count +=1;if ($Count > 1){echo " / ";}else{echo "<br />" . $TradeLang['Prospects'] . " : ";}
			$Query = "SELECT Name FROM Prospects WHERE Number = " . $Row['Prospect'];
			$Data = $db->querySingle($Query,true);	
			echo $Data['Name'];
			$TradeLog = $TradeLog . $Data['Name'] . ",";
	}}
	
	$Query = "Select * From Trade WHERE ToTeam = " . $Team . "  AND DraftPick > 0 AND (ConfirmFrom = 'False' Or ConfirmTo = 'False') ORDER BY DraftPick";
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
			If ($Row['DraftPick'] >= 10000){
				echo " (CON)";
				$TradeLog = $TradeLog . "Y:" . $Data['Year'] . "-RND:" . $Data['Round'] . "-" . $Data['FromTeamAbbre'] . " (CON),";
			}else{
				$TradeLog = $TradeLog . "Y:" . $Data['Year'] . "-RND:" . $Data['Round'] . "-" . $Data['FromTeamAbbre'] . ",";
			}			
	}}
	echo "<br />";
	
	$Query = "Select Sum(Money) as SumofMoney, Sum(SalaryCap) as SumofSalaryCap From Trade WHERE ToTeam = "  . $Team . " AND (ConfirmFrom = 'False' Or ConfirmTo = 'False')";
	$Trade =  $db->querySingle($Query,true);	
		
	If ($Trade['SumofMoney'] > 0){
		echo $TradeLang['Money'] . " : "  . number_format($Trade['SumofMoney'],0) . "$<br />";
		$TradeLog = $TradeLog . $TradeLang['Money'] . " : "  . number_format($Trade['SumofMoney'],0). ",";
		}
	If ($Trade['SumofSalaryCap'] > 0){	
		echo $TradeLang['SalaryCap'] . " : " . number_format($Trade['SumofSalaryCap'] ,0) . "$<br />";
		$TradeLog = $TradeLog . $TradeLang['SalaryCap'] . " : " . number_format($Trade['SumofSalaryCap'] ,0). ",";
	}
	
	If ($Confirm == True){
		/* Create Entry */
		$Query = "UPDATE TRADE SET ConfirmTo = 'True' WHERE ToTeam = " . $Team . " AND FromTeam = " . $TradeMain['ToTeam'];
		try {
			$db->exec($Query);
		} catch (Exception $e) {
			echo $TradeLang['Fail'];
		}
		
		$Query = "INSERT Into LeagueLog (Number, Text, DateTime, TransactionType) VALUES ('" . rand(90000,99999) . "','" . $TradeLog . "','" . gmdate('Y-m-d H:i:s') . "','1')";
		try {
			$db->exec($Query);
		} catch (Exception $e) {
		}
	}elseif ($Refuse == True){
		/* Delete Entry */
		$Query = "DELETE from TRADE WHERE ToTeam = " . $Team . " AND FromTeam = " . $TradeMain['ToTeam'] ;
		try {
			$db->exec($Query);
		} catch (Exception $e) {
			echo $TradeLang['Fail'];
		}
	}
	}
	?>
	
	</td>
	</tr>
	
	<tr>
		<td colspan="2" class="STHSPHPTradeType">
		<?php If ($Confirm == False AND $Refuse == False){echo "<strong>" . $TeamInfo['Name']. " " . $News['Password'] ."</strong><input type=\"password\" name=\"Password\" size=\"20\" value=\"\" required>";}?>
		</td>
		</tr><tr>
	 	<td colspan="2" class="STHSPHPTradeType">
		<?php
		If ($Confirm == True){
			echo $TradeLang['Confirm'];
		}elseif ($Refuse == True){
			echo $TradeLang['Refuse'];
		}else{
			echo "<input class=\"SubmitButton\" type=\"submit\" name=\"Submit\" value=\"" . $TradeLang['ConfirmSubmit'] . "\" /> ";
			echo "<input class=\"SubmitButton\" style=\"margin-left: 30px\" type=\"submit\" name=\"Submit\" value=\"" . $TradeLang['RefuseSubmit'] . "\" /></td>";
		}?>
    </tr>
	</table>
</form>
<br />


<?php 
If ($Team == 0){
	echo "<div class=\"STHSCenter\">";
	echo "<form action=\"TradeOtherTeam.php";If ($lang == "fr" ){echo "?Lang=fr";}echo "\" method=\"post\">";
	echo "<table class=\"STHSTableFullW\"><tr>";
	echo "<th class=\"STHSPHPTradeType STHSW250\">" . $TradeLang['Team'] . "</th><tr>";
	echo "<td><select name=\"Team\" class=\"STHSW250\"><option selected value=\"\"></option>";
	$Query = "SELECT Number, Name FROM TeamProInfo Order By Name";
	$TeamName = $db->query($Query);	
	if (empty($TeamName) == false){while ($Row = $TeamName ->fetchArray()) {
		echo "<option value=\"" . $Row['Number'] . "\">" . $Row['Name'] . "</option>"; 
	}}
	echo "</select></td></tr>";
	echo "<tr><td class=\"STHSCenter\"><br /><input class=\"SubmitButton\" type=\"submit\" value=\"" . $SearchLang['Submit'] . "\"></td></tr>";
	echo "</table></form></div>";
}
?>
</div>


<?php include "Footer.php";?>
