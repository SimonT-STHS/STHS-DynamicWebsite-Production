<?php include "Header.php";
If ($lang == "fr"){include 'LanguageFR-League.php';}else{include 'LanguageEN-League.php';}
$Title = (string)"";
$Search = (boolean)False;
$Team = (integer)0; /* 0 All Team */
$SinceLast = (boolean)False; /* FALSE = Show All --- FALSE = Show Only Transaction since last SQLite Database Output */
$TradeHistory = (boolean)False;
$TradeLogHistory = (boolean)False;
$MaximumResult = (integer)0;
$LeagueName = (string)"";
$TradeLogHistoryCurrentDate = (string)"";
$Type = (integer)0;
include "SearchPossibleOrderField.php";

If (file_exists($DatabaseFile) == false){
	Goto STHSErrorTransaction;
}else{try{
	
	if(isset($_GET['SinceLast'])){$SinceLast = True;} /* Capitalize Letters are Important */
	if(isset($_GET['TradeHistory'])){$TradeHistory = True;} /* Capitalize Letters are Important */
	if(isset($_GET['TradeLogHistory'])){$TradeLogHistory  = True;} /* Capitalize Letters are Important */
	if(isset($_GET['Max'])){$MaximumResult = filter_var($_GET['Max'], FILTER_SANITIZE_NUMBER_INT);} 
	if(isset($_GET['Team'])){$Team = filter_var($_GET['Team'], FILTER_SANITIZE_NUMBER_INT);} 
	if(isset($_GET['Type'])){$Type = filter_var($_GET['Type'], FILTER_SANITIZE_NUMBER_INT);} 
	
	$db = new SQLite3($DatabaseFile);
	
	$Query = "Select Name, LastTransactionOutput from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	
	If ($TradeHistory == True){
		$Title = $TransactionLang['TradeHistory'];
		$Query = "SELECT LeagueLog.* FROM LeagueLog WHERE LeagueLog.TransactionType = 1 ORDER BY LeagueLog.Number DESC";
	}elseif($TradeLogHistory == True){
		$Title = $TransactionLang['TradeHistory'];
		If ($Team == 0){
			$Query = "SELECT TradeLog.* FROM TradeLog ORDER BY TradeLog.Number ASC";
		}else{
			$Query = "SELECT Name FROM TeamProInfo WHERE Number = " . $Team ;
			$TeamName = $db->querySingle($Query);
			$Title = $Title . " - " . $TeamName;
			
			$Query = "SELECT TradeLog.* FROM TradeLog WHERE SendingTeamNumber = " . $Team . " OR ReceivingTeamNumber = " . $Team . " ORDER BY TradeLog.Number ASC";
		}
	}elseIf ($Team == 0){
		If ($SinceLast == False){
			$Title = $TransactionLang['LeagueTitle'];
			if ($Type == 0){
				$Query = "SELECT LeagueLog.* FROM LeagueLog ORDER BY LeagueLog.Number DESC";
			}else{
				$Query = "SELECT LeagueLog.* FROM LeagueLog WHERE TransactionType = " . $Type . " ORDER BY LeagueLog.Number DESC";
								
				foreach ($TransactionType as $Value) {
				If (strtoupper($Value[0]) == strtoupper($Type)){
					$Title = $Title . " - " . $Value[1];
					Break;
				}
			}
				
		}
		}else{
			$Title = $TransactionLang['SinceLast'];
			$Query = "SELECT LeagueLog.* FROM LeagueLog WHERE LeagueLog.Number >= " . $LeagueGeneral['LastTransactionOutput'] ." ORDER BY LeagueLog.Number DESC";
		}
	}else{
		$Query = "SELECT Name FROM TeamProInfo WHERE Number = " . $Team ;
		$TeamName = $db->querySingle($Query);
		$Title = $TransactionLang['TeamTitle'] . $TeamName;
		$Query = "SELECT TeamLog.*, '' AS Color FROM TeamLog WHERE TeamLog.TeamNumber = " . $Team . " ORDER BY TeamLog.Number DESC";
	}

	If ($MaximumResult > 0){$Query = $Query . " LIMIT " . $MaximumResult;}
	$Transaction = $db->query($Query);

	echo "<title>" . $LeagueName . " - " . $Title . "</title>";
} catch (Exception $e) {
STHSErrorTransaction:
	$LeagueName = $DatabaseNotFound;
	$Transaction = Null;
	echo "<title>" . $DatabaseNotFound ."</title>";
	$Title = $DatabaseNotFound;
}}?>
</head><body>
<?php include "Menu.php";?>

<div style="width:99%;margin:auto;">
<?php echo "<h1>" . $Title . "</h1>"; ?>
<div id="ReQueryDiv" style="display:none;">
<?php include "SearchTransaction.php";?>
</div>
<div class="tablesorter_ColumnSelectorWrapper">
	<button class="tablesorter_Output" id="ReQuery"><?php echo $SearchLang['ChangeSearch'];?></button>
</div>
<br />

<?php
if($TradeLogHistory == True){
	echo "<table class=\"STHSPHPTradeLogHistory_Table\">";
	if (empty($Transaction) == false){while ($row = $Transaction ->fetchArray()) {
		If ($TradeLogHistoryCurrentDate != $row['DateTxt']){echo "<tr><th colspan=\"4\" class=\"STHSCenter\">" . $row['DateTxt'] . "</th></tr>";$TradeLogHistoryCurrentDate = $row['DateTxt'];}
		echo "<tr><td>";
		If ($row['SendingTeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $row['SendingTeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPTradeLogHistoryTeamImage\" />";}else{echo $row['SendingTeamName'];}
		echo "</td><td><img src=\"" . $ImagesCDNPath . "/images/TradeArrow.png\" alt=\"Trade Arrow\" width=\"25\" height=\"25\"></td><td>";
		If ($row['ReceivingTeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $row['ReceivingTeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPTradeLogHistoryTeamImage\" />";}else{echo $row['ReceivingTeamName'];}
		echo "</td><td style=\"text-align:left;padding-left:20px;\">" . $row['ReceivingTeamText'] . "</td></tr>";
	}}
	echo "</table>";
}else{
	if (empty($Transaction) == false){while ($row = $Transaction ->fetchArray()) {
		if ($row['Color'] == "" OR $TradeHistory == True){
			echo "[" . $row['DateTime'] . "] " . $row['Text'] . "<br />\n"; /* The \n is for a new line in the HTML Code */
		}else{
			echo "<span style=\"color:" . $row['Color'] . "\">[" . $row['DateTime'] . "] " . $row['Text'] . "</span><br />\n"; /* The \n is for a new line in the HTML Code */
		}
	}}
}

?>

<br />
</div>

<?php include "Footer.php";?>
