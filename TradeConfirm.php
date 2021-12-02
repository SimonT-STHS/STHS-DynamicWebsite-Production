<?php include "Header.php";?>
<?php
$Title = (string)"";
$Team1 = (integer)0;
$Team2 = (integer)0;
$Team1Player = Null;
$Team2Player = Null;
$Team1Prospect = Null;
$Team2Prospect = Null;
$Team1DraftPick = Null;
$Team2DraftPick = Null;
$Team1DraftPickCon = Null;
$Team2DraftPickCon = Null;
$Team1Money = (integer)0;
$Team2Money = (integer)0;
$Team1SalaryCap = (integer)0;
$Team2SalaryCap = (integer)0;
$Boofound = (boolean)False;
$Team1Info = "";
$Team2Info = "";

$Confirm = False;	
$InformationMessage = (string)"";

If (file_exists($DatabaseFile) == false){
	$LeagueName = $DatabaseNotFound;
	$LeagueOutputOption = Null;
	echo "<title>" . $DatabaseNotFound . "</title>";
	$Title = $DatabaseNotFound;
	echo "<style>#Trade{display:none}</style>";
}else{
	$db = new SQLite3($DatabaseFile);
	$db->enableExceptions(true);
	$LeagueName = (string)"";
	if(isset($_POST['Team1'])){$Team1 = filter_var($_POST['Team1'], FILTER_SANITIZE_NUMBER_INT);}
	if(isset($_POST['Team2'])){$Team2 = filter_var($_POST['Team2'], FILTER_SANITIZE_NUMBER_INT);}
	if(isset($_POST['Confirm'])){
		if ($_POST['Confirm'] == "YES"){
			if(isset($_POST['Team1Player'])){$Team1Player = json_decode($_POST['Team1Player']);}
			if(isset($_POST['Team2Player'])){$Team2Player = json_decode($_POST['Team2Player']);}
			if(isset($_POST['Team1Prospect'])){$Team1Prospect = json_decode($_POST['Team1Prospect']);}
			if(isset($_POST['Team2Prospect'])){$Team2Prospect = json_decode($_POST['Team2Prospect']);}
			if(isset($_POST['Team1DraftPick'])){$Team1DraftPick = json_decode($_POST['Team1DraftPick']);}
			if(isset($_POST['Team2DraftPick'])){$Team2DraftPick = json_decode($_POST['Team2DraftPick']);}
			if(isset($_POST['Team1DraftPickCon'])){$Team1DraftPickCon = json_decode($_POST['Team1DraftPickCon']);}
			if(isset($_POST['Team2DraftPickCon'])){$Team2DraftPickCon = json_decode($_POST['Team2DraftPickCon']);}			
			if(isset($_POST['Team1Money'])){$Team1Money = filter_var($_POST['Team1Money'], FILTER_SANITIZE_NUMBER_INT);} 
			if(isset($_POST['Team2Money'])){$Team2Money = filter_var($_POST['Team2Money'], FILTER_SANITIZE_NUMBER_INT);} 
			if(isset($_POST['Team1SalaryCap'])){$Team1SalaryCap = filter_var($_POST['Team1SalaryCap'], FILTER_SANITIZE_NUMBER_INT);} 
			if(isset($_POST['Team2SalaryCap'])){$Team2SalaryCap = filter_var($_POST['Team2SalaryCap'], FILTER_SANITIZE_NUMBER_INT);} 		
			If ($Team1 == $CookieTeamNumber AND $CookieTeamNumber > 0){$Confirm = True;}else{$InformationMessage = $News['IllegalAction'];;}			
		}else{
			if(isset($_POST['Team1Player'])){$Team1Player = $_POST['Team1Player'];}
			if(isset($_POST['Team2Player'])){$Team2Player = $_POST['Team2Player'];}
			if(isset($_POST['Team1Prospect'])){$Team1Prospect = $_POST['Team1Prospect'];}
			if(isset($_POST['Team2Prospect'])){$Team2Prospect = $_POST['Team2Prospect'];}
			if(isset($_POST['Team1DraftPick'])){$Team1DraftPick = $_POST['Team1DraftPick'];}
			if(isset($_POST['Team2DraftPick'])){$Team2DraftPick = $_POST['Team2DraftPick'];}
			if(isset($_POST['Team1DraftPickCon'])){$Team1DraftPickCon = $_POST['Team1DraftPickCon'];}
			if(isset($_POST['Team2DraftPickCon'])){$Team2DraftPickCon = $_POST['Team2DraftPickCon'];}			
			if(isset($_POST['Team1Money'])){$Team1Money = filter_var($_POST['Team1Money'], FILTER_SANITIZE_NUMBER_INT);} 
			if(isset($_POST['Team2Money'])){$Team2Money = filter_var($_POST['Team2Money'], FILTER_SANITIZE_NUMBER_INT);} 
			if(isset($_POST['Team1SalaryCap'])){$Team1SalaryCap = filter_var($_POST['Team1SalaryCap'], FILTER_SANITIZE_NUMBER_INT);} 
			if(isset($_POST['Team2SalaryCap'])){$Team2SalaryCap = filter_var($_POST['Team2SalaryCap'], FILTER_SANITIZE_NUMBER_INT);} 
			if(isset($_POST['Team1Money'])){$Team1Money = filter_var($_POST['Team1Money'], FILTER_SANITIZE_NUMBER_INT);} 
			if(isset($_POST['Team2Money'])){$Team2Money = filter_var($_POST['Team2Money'], FILTER_SANITIZE_NUMBER_INT);} 
			if(isset($_POST['Team1SalaryCap'])){$Team1SalaryCap = filter_var($_POST['Team1SalaryCap'], FILTER_SANITIZE_NUMBER_INT);} 
			if(isset($_POST['Team2SalaryCap'])){$Team2SalaryCap = filter_var($_POST['Team2SalaryCap'], FILTER_SANITIZE_NUMBER_INT);} 
		}
	}
	
	$Query = "Select Name, TradeDeadLine from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	$Title = $TradeLang['Trade'];
	
	If ($Team1 == 0 or $Team2 == 0 or $Team1 == $Team2){
		echo "<style>#Trade{display:none}</style>";
	}else{
		$Query = "SELECT Number, Name, TeamThemeID FROM TeamProInfo Where Number = " . $Team1;
		$Team1Info =  $db->querySingle($Query,true);	
		$Query = "SELECT Number, Name, TeamThemeID FROM TeamProInfo Where Number = " . $Team2;
		$Team2Info =  $db->querySingle($Query,true);	
		
	}

	echo "<title>" . $LeagueName . " - " . $TradeLang['Trade']  . "</title>";
}?>
</head><body>
<?php include "Menu.php";?>

<br />

<div style="width:99%;margin:auto;">
<?php echo "<h1>" . $Title . "</h1>"; 
if ($InformationMessage != ""){echo "<div style=\"color:#FF0000; font-weight: bold;padding:1px 1px 1px 5px;text-align:center;\">" . $InformationMessage . "<br /><br /></div>";}?>
<form id="Trade" name="Trade" method="post" action="TradeConfirm.php<?php If ($lang == "fr" ){echo "?Lang=fr";}?>">
	<input type="hidden" id="Team1" name="Team1" value="<?php echo $Team1;?>">
	<input type="hidden" id="Team2" name="Team2" value="<?php echo $Team2;?>">
	<input type="hidden" id="Confirm" name="Confirm" value="YES">
	<table class="STHSTableFullW">
	<tr>
		<td class="STHSPHPTradeTeamName"><?php if($Team1Info != Null){If ($Team1Info['TeamThemeID'] > 0){echo "<img src=\"./images/" . $Team1Info['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPTradeTeamImage \" />";}echo $Team1Info['Name'];}?></td>
		<td class="STHSPHPTradeTeamName"><?php if($Team2Info != Null){If ($Team2Info['TeamThemeID'] > 0){echo "<img src=\"./images/" . $Team2Info['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPTradeTeamImage \" />";}echo $Team2Info['Name'];}?></td>
	</tr>
	
	
	<tr><td colspan="2" class="STHSPHPTradeType"><hr /><?php ?></td></tr>
	<tr><td>
	
	<?php
	if (empty($Team1Player) == false){
		$Count = 0;
		echo $TradeLang['Players'] . " : ";
		foreach ($Team1Player as $values){
			If ($values > 0 and $values < 10000){
				/* Players */
				$Count +=1;if ($Count > 1){echo " / ";}
				$Query = "SELECT Name FROM PlayerInfo WHERE Number = " . $values;
				$Data = $db->querySingle($Query,true);	
				echo $Data['Name'];
			}elseif($values > 10000 and $values < 11000){
				/* Goalies */
				$Count +=1;if ($Count > 1){echo " / ";}
				$Query = "SELECT Name FROM GoalerInfo WHERE Number = (" . $values . " - 10000)";
				$Data = $db->querySingle($Query,true);	
				echo $Data['Name'];				
			}
			If ($Confirm == True){
				/* Create Entry */
				$Query = "INSERT INTO Trade (FromTeam,ToTeam,Player,ConfirmFrom,ConfirmTo) VALUES('" . $Team1 . "','" . $Team2 . "','" . $values . "','True','False')";
				try {
					$db->exec($Query);
				} catch (Exception $e) {
					echo $TradeLang['Fail'];
				}
			}
		}
		If ($Count > 0){echo "<input type=\"hidden\" id=\"Team1Player\" name=\"Team1Player\" value=\"" . htmlspecialchars(json_encode($Team1Player),ENT_QUOTES) . "\">";}
	}
	if (empty($Team1Prospect) == false){
		$Count = 0;
		echo "<br />" . $TradeLang['Prospects'] . " : ";
		foreach ($Team1Prospect as $values){
			$Count +=1;if ($Count > 1){echo " / ";}
			$Query = "SELECT Name FROM Prospects WHERE Number = " . $values;
			$Data = $db->querySingle($Query,true);	
			echo $Data['Name'];
			If ($Confirm == True){
				/* Create Entry */
				$Query = "INSERT INTO Trade (FromTeam,ToTeam,Prospect,ConfirmFrom,ConfirmTo) VALUES('" . $Team1 . "','" . $Team2 . "','" . $values . "','True','False')";
				try {
					$db->exec($Query);
				} catch (Exception $e) {
					echo $TradeLang['Fail'];
				}
			}
		}
		If ($Count > 0){echo "<input type=\"hidden\" id=\"Team1Prospect\" name=\"Team1Prospect\" value=\"" . htmlspecialchars(json_encode($Team1Prospect),ENT_QUOTES) . "\">";}
	}
	if (empty($Team1DraftPick) == false){
		echo "<br />" .  $TradeLang['DraftPicks'] . " : ";
		$Count = 0;
		foreach ($Team1DraftPick as $values){
			$Count +=1;if ($Count > 1){echo " / ";}
			$Query = "SELECT * FROM DraftPick WHERE InternalNumber = " . $values . " AND TeamNumber = " . $Team1;
			$Data = $db->querySingle($Query,true);	
			echo "Y:" . $Data['Year'] . "-RND:" . $Data['Round'] . "-" . $Data['FromTeamAbbre'];
			If ($Confirm == True){
				/* Create Entry */
				$Query = "INSERT INTO Trade (FromTeam,ToTeam,DraftPick,ConfirmFrom,ConfirmTo) VALUES('" . $Team1 . "','" . $Team2 . "','" . $values . "','True','False')";
				try {
					$db->exec($Query);
				} catch (Exception $e) {
					echo $TradeLang['Fail'];
				}
			}
		}
		If ($Count > 0){echo "<input type=\"hidden\" id=\"Team1DraftPick\" name=\"Team1DraftPick\" value=\"" . htmlspecialchars(json_encode($Team1DraftPick),ENT_QUOTES) . "\">";}
	}
	if (empty($Team1DraftPickCon) == false){
		echo "<br />" .  $TradeLang['DraftPicksCon'] . " : ";
		$Count = 0;
		foreach ($Team1DraftPickCon as $values){
			$Boofound = False;
			If($Team1DraftPick != Null){foreach ($Team1DraftPick as $temp){If ($values == $temp){$Boofound = True;}}}	/* Check if Conditionnal Draft Pick already in Trade */
			If ($Boofound == False){			
				$Count +=1;if ($Count > 1){echo " / ";}
				$Query = "SELECT * FROM DraftPick WHERE InternalNumber = " . $values . " AND TeamNumber = " . $Team1;
				$Data = $db->querySingle($Query,true);	
				echo "Y:" . $Data['Year'] . "-RND:" . $Data['Round'] . "-" . $Data['FromTeamAbbre'];
				If ($Confirm == True){
					/* Create Entry */
					$Query = "INSERT INTO Trade (FromTeam,ToTeam,DraftPick,ConfirmFrom,ConfirmTo) VALUES('" . $Team1 . "','" . $Team2 . "','" . ($values + 10000) . "','True','False')";
					try {
						$db->exec($Query);
					} catch (Exception $e) {
						echo $TradeLang['Fail'];
					}
				}
			}
		}
		If ($Count > 0){echo "<input type=\"hidden\" id=\"Team1DraftPickCon\" name=\"Team1DraftPickCon\" value=\"" . htmlspecialchars(json_encode($Team1DraftPickCon),ENT_QUOTES) . "\">";}
	}	
	echo "<br />";
	If ($Team1Money  > 0){echo $TradeLang['Money'] . " : " . number_format($Team1Money,0) . "$<input type=\"hidden\" name=\"Team1Money\" value=\"" . $Team1Money . "\"><br />";}
	If ($Team1SalaryCap > 0){echo $TradeLang['SalaryCap'] . " : " . number_format($Team1SalaryCap,0) . "$<input type=\"hidden\" name=\"Team1SalaryCap\" value=\"" . $Team1SalaryCap . "\"><br />";}
	If ($Confirm == True){
		$Query = "INSERT INTO Trade (FromTeam,ToTeam,Money,SalaryCap,ConfirmFrom,ConfirmTo) VALUES('" . $Team1 . "','" . $Team2 . "','" . $Team1Money . "','" . $Team1SalaryCap . "','True','False')";
		try {
			$db->exec($Query);
		} catch (Exception $e) {
			echo $TradeLang['Fail'];
		}
	}	
	
	?>
	</td><td>
	<?php	
	if (empty($Team2Player) == false){
		echo $TradeLang['Players'] . " : ";
		$Count = 0;
		foreach ($Team2Player as $values){
			If ($values > 0 and $values < 10000){
				/* Players */
				$Count +=1;if ($Count > 1){echo " / ";}
				$Query = "SELECT Name FROM PlayerInfo WHERE Number = " . $values;
				$Data = $db->querySingle($Query,true);	
				echo $Data['Name'];
			}elseif($values > 10000 and $values < 11000){
				/* Goalies */
				$Count +=1;if ($Count > 1){echo " / ";}
				$Query = "SELECT Name FROM GoalerInfo WHERE Number = (" . $values . " - 10000)";
				$Data = $db->querySingle($Query,true);	
				echo $Data['Name'];
			}
			If ($Confirm == True){
				/* Create Entry */
				$Query = "INSERT INTO Trade (FromTeam,ToTeam,Player,ConfirmFrom,ConfirmTo) VALUES('" . $Team2 . "','" . $Team1 . "','" . $values . "','False','True')";
				try {
					$db->exec($Query);
				} catch (Exception $e) {
					echo $TradeLang['Fail'];
				}
			}
		}
		If ($Count > 0){echo "<input type=\"hidden\" id=\"Team2Player\" name=\"Team2Player\" value=\"" . htmlspecialchars(json_encode($Team2Player),ENT_QUOTES) . "\">";}
	}
	if (empty($Team2Prospect) == false){
		echo "<br />" . $TradeLang['Prospects'] . " : ";
		$Count = 0;
		foreach ($Team2Prospect as $values){
			$Count +=1;if ($Count > 1){echo " / ";}
			$Query = "SELECT Name FROM Prospects WHERE Number = " . $values;
			$Data = $db->querySingle($Query,true);	
			echo $Data['Name'];
			If ($Confirm == True){
				/* Create Entry */
				$Query = "INSERT INTO Trade (FromTeam,ToTeam,Prospect,ConfirmFrom,ConfirmTo) VALUES('" . $Team2 . "','" . $Team1 . "','" . $values . "','False','True')";
				try {
					$db->exec($Query);
				} catch (Exception $e) {
					echo $TradeLang['Fail'];
				}
			}
		}
		If ($Count > 0){echo "<input type=\"hidden\" id=\"Team2Prospect\" name=\"Team2Prospect\" value=\"" . htmlspecialchars(json_encode($Team2Prospect),ENT_QUOTES) . "\">";}
	}
	if (empty($Team2DraftPick) == false){
		echo "<br />" .  $TradeLang['DraftPicks'] . " : ";
		$Count = 0;
		foreach ($Team2DraftPick as $values){
			$Count +=1;if ($Count > 1){echo " / ";}
			$Query = "SELECT * FROM DraftPick WHERE InternalNumber = " . $values . " AND TeamNumber = " . $Team2;
			$Data = $db->querySingle($Query,true);	
			echo "Y:" . $Data['Year'] . "-RND:" . $Data['Round'] . "-" . $Data['FromTeamAbbre'];
			If ($Confirm == True){
				/* Create Entry */
				$Query = "INSERT INTO Trade (FromTeam,ToTeam,DraftPick,ConfirmFrom,ConfirmTo) VALUES('" . $Team2 . "','" . $Team1 . "','" . $values . "','False','True')";
				$db->exec($Query);
				try {
					
				} catch (Exception $e) {
					echo $TradeLang['Fail'];
				}
			}
		}
		If ($Count > 0){echo "<input type=\"hidden\" id=\"Team2DraftPick\" name=\"Team2DraftPick\" value=\"" . htmlspecialchars(json_encode($Team2DraftPick),ENT_QUOTES) . "\">";}
	}
	if (empty($Team2DraftPickCon) == false){
		echo "<br />" .  $TradeLang['DraftPicksCon'] . " : ";
		$Count = 0;
		foreach ($Team2DraftPickCon as $values){
			$Boofound = False;
			If($Team2DraftPick != Null){foreach ($Team2DraftPick as $temp){If ($values == $temp){$Boofound = True;}}}	/* Check if Conditionnal Draft Pick already in Trade */
			If ($Boofound == False){
				$Count +=1;if ($Count > 1){echo " / ";}
				$Query = "SELECT * FROM DraftPick WHERE InternalNumber = " . $values . " AND TeamNumber = " . $Team2;
				$Data = $db->querySingle($Query,true);	
				echo "Y:" . $Data['Year'] . "-RND:" . $Data['Round'] . "-" . $Data['FromTeamAbbre'];
				If ($Confirm == True){
					/* Create Entry */
					$Query = "INSERT INTO Trade (FromTeam,ToTeam,DraftPick,ConfirmFrom,ConfirmTo) VALUES('" . $Team2 . "','" . $Team1 . "','" . ($values + 10000) . "','False','True')";
					$db->exec($Query);
					try {
						
					} catch (Exception $e) {
						echo $TradeLang['Fail'];
					}
				}
			}
		}
		If ($Count > 0){echo "<input type=\"hidden\" id=\"Team2DraftPickCon\" name=\"Team2DraftPickCon\" value=\"" . htmlspecialchars(json_encode($Team2DraftPickCon),ENT_QUOTES) . "\">";}
	}	
	echo "<br />";
	If ($Team2Money  > 0){echo $TradeLang['Money'] . " : " . number_format($Team2Money,0) . "$<input type=\"hidden\" name=\"Team2Money\" value=\"" . $Team2Money . "\"><br />";}
	If ($Team2SalaryCap > 0 ){echo $TradeLang['SalaryCap'] . " : " . number_format($Team2SalaryCap,0) . "$<input type=\"hidden\" name=\"Team2SalaryCap\" value=\"" . $Team2SalaryCap . "\"><br />";}
	If ($Confirm == True){
		$Query = "INSERT INTO Trade (FromTeam,ToTeam,Money,SalaryCap,ConfirmFrom,ConfirmTo) VALUES('" . $Team2 . "','" . $Team1 . "','" . $Team2Money . "','" . $Team2SalaryCap . "','False','True')";
		try {
			$db->exec($Query);
		} catch (Exception $e) {
			echo $TradeLang['Fail'];
		}
	}
	?>
	
	</td>
	</tr>
	
	<tr>
	 	<td colspan="2" class="STHSPHPTradeType">
		<?php
		if($Team1Info != Null){
			If ($Confirm == True){
				echo $TradeLang['Confirm'];
			}else{
				echo "<input class=\"SubmitButton\" type=\"submit\" name=\"Submit\" value=\"" . $Team1Info['Name'] . " - " . $TradeLang['ConfirmSubmit'] . "\" /></td>";
			}
		}?>
    </tr>
	</table>
</form>
<br />


<?php 
If ($Team1 == 0 or $Team2 == 0 or $Team1 == $Team2){echo "<div class=\"STHSCenter\">" . $TradeLang['Error'] . "</div>";}
?>
</div>

<?php include "Footer.php";?>
