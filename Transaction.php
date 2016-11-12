<!DOCTYPE html>
<?php include "Header.php";?>
<?php
$Title = (string)"";
$Active = 4; /* Show Webpage Top Menu */
If (file_exists($DatabaseFile) == false){
	$LeagueName = $DatabaseNotFound;
	$Transaction = Null;
	echo "<title>" . $DatabaseNotFound ."</title>";
	$Title = $DatabaseNotFound;
}else{
	$Team = (integer)0; /* 0 All Team */
	$SinceLast = (boolean)False; /* FALSE = Show All --- FALSE = Show Only Transaction since last SQLite Database Output */
	$TradeHistory = (boolean)False;
	$MaximumResult = (integer)0;
	$LeagueName = (string)"";
	
	if(isset($_GET['SinceLast'])){$SinceLast = True;} /* Capitalize Letters are Important */
	if(isset($_GET['TradeHistory'])){$TradeHistory = True;} /* Capitalize Letters are Important */
	if(isset($_GET['Max'])){$MaximumResult = filter_var($_GET['Max'], FILTER_SANITIZE_NUMBER_INT);} 
	if(isset($_GET['Team'])){$Team = filter_var($_GET['Team'], FILTER_SANITIZE_NUMBER_INT);} 
	
	$db = new SQLite3($DatabaseFile);
	
	$Query = "Select Name, LastTransactionOutput from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	
	If ($TradeHistory == True){
		$Title = $TransactionLang['TradeHistory'];
		$Query = "SELECT LeagueLog.* FROM LeagueLog WHERE LeagueLog.TransactionType = 1 ORDER BY LeagueLog.Number DESC";
	}elseIf ($Team == 0){
		If ($SinceLast == False){
			$Title = $TransactionLang['LeagueTitle'];
			$Query = "SELECT LeagueLog.* FROM LeagueLog ORDER BY LeagueLog.Number DESC";
		}else{
			$Title = $TransactionLang['SinceLast'];
			$Query = "SELECT LeagueLog.* FROM LeagueLog WHERE LeagueLog.Number > " . $LeagueGeneral['LastTransactionOutput'] ." ORDER BY LeagueLog.Number DESC";
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
}?>
</head><body>
<?php include "Menu.php";?>
<?php echo "<h1>" . $Title . "</h1>"; ?>


<div style="width:99%;margin:auto;">

<?php
if (empty($Transaction) == false){while ($row = $Transaction ->fetchArray()) { 
	If ($row['Color'] == "" OR $TradeHistory == True){
		echo "[" . $row['DateTime'] . "] " . $row['Text'] . "<br />\n"; /* The \n is for a new line in the HTML Code */
	}else{
		echo "<span style=\"color:" . $row['Color'] . "\">[" . $row['DateTime'] . "] " . $row['Text'] . "</span><br />\n"; /* The \n is for a new line in the HTML Code */
	}
}}
?>

<br />
</div>

<?php include "Footer.php";?>
