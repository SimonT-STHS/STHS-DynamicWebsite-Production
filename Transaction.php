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
	$Type = (integer)0;
	
	if(isset($_GET['SinceLast'])){$SinceLast = True;} /* Capitalize Letters are Important */
	if(isset($_GET['TradeHistory'])){$TradeHistory = True;} /* Capitalize Letters are Important */
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
	}elseIf ($Team == 0){
		If ($SinceLast == False){
			$Title = $TransactionLang['LeagueTitle'];
			if ($Type == 0){
				$Query = "SELECT LeagueLog.* FROM LeagueLog ORDER BY LeagueLog.Number DESC";
			}else{
				$Query = "SELECT LeagueLog.* FROM LeagueLog WHERE TransactionType = " . $Type . " ORDER BY LeagueLog.Number DESC";
				
				$TransactionType = array(
				array("0","Other"),
				array("1","Trade"),
				array("2","Injury"),
				array("3","Waiver"),	
				array("4","Send To Pro"),
				array("5","Send To Farm"),
				array("6","Suspension"),
				array("7","Roster or Line Error"),
				array("8","Information"),
				array("9","Players"),
				array("10","Team"),
				array("11","Option Change"),	
				);
				
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
