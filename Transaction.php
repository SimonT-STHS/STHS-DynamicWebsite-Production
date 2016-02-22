<?php include "Header.php";?>
<?php
$Title = (string)"";
If (file_exists($DatabaseFile) == false){
	$LeagueName = $DatabaseNotFound;
	$Schedule = Null;
	echo "<title>" . $DatabaseNotFound ."</title>";
	$Title = $DatabaseNotFound;
}else{
	$Team = (integer)0; /* 0 All Team */
	$SinceLast = (boolean)False; /* FALSE = Show All --- FALSE = Show Only Transaction since last SQLite Database Output */
	$MaximumResult = (integer)0;
	$LeagueName = (string)"";
	
	if(isset($_GET['SinceLast'])){$SinceLast = True;} /* Capitalize Letters are Important */
	if(isset($_GET['Max'])){$MaximumResult = filter_var($_GET['Max'], FILTER_SANITIZE_NUMBER_INT);} 
	if(isset($_GET['Team'])){$Team = filter_var($_GET['Team'], FILTER_SANITIZE_NUMBER_INT);} 
	
	$db = new SQLite3($DatabaseFile);
	
	$Query = "Select Name, LastTransactionOutput from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	
	If ($Team == 0){
		$Title = $TransactionLang['LeagueTitle'];
		If ($SinceLast == False){
			$Query = "SELECT LeagueLog.* FROM LeagueLog ORDER BY LeagueLog.Number DESC";
		}else{
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
<!-- TOP MENU PLACE HOLDER -->
<?php echo "<h1>" . $Title . "</h1>"; ?>


<div style="width:99%;margin:auto;">

<?php
if (empty($Transaction) == false){while ($row = $Transaction ->fetchArray()) { 
	If ($row['Color'] == ""){
		echo "[" . $row['DateTime'] . "] " . $row['Text'] . "<br />\n"; /* The \n is for a new line in the HTML Code */
	}else{
		echo "<span style=\"color:" . $row['Color'] . "\">[" . $row['DateTime'] . "] " . $row['Text'] . "</span><br />\n"; /* The \n is for a new line in the HTML Code */
	}
}}
?>

<br />
</div>

<?php include "Footer.php";?>
