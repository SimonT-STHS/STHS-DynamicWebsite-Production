<?php include "Header.php";
$Title = (string)"";
$Number = (integer)0;
$HTML = (string)"";

If (file_exists($DatabaseFile) == false){
	Goto STHSErrorLegacyPages;
}else{try{
	
	$db = new SQLite3($DatabaseFile);

	$Query = "Select Name from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
		
	if(isset($_GET['Number'])){$Number = filter_var($_GET['Number'], FILTER_SANITIZE_NUMBER_INT);} 
	If ($Number > 0){
		
		If (file_exists($LegacyHTMLDatabaseFile) == false){
			echo "<title>" . $DatabaseNotFound . "</title>";
			$GameHTML = "<h1>" . $DatabaseNotFound . "</h1>";
		}else{
			$Legacydb = new SQLite3($LegacyHTMLDatabaseFile);
			$Query = "Select * from LegacyPage WHERE Number = '" . $Number . "'";
			$Result = $Legacydb ->querySingle($Query,true);
			If ($Result != Null){
				echo "<title>" . $LeagueName . " - " . $Result['Title'] . "</title>";
				$HTML = gzdecode(base64_decode($Result['HTML']));			
			}else{
				echo "<title>" . $IncorrectLegacyPagesQuery . "</title>";
				$HTML = "<h1>" . $IncorrectLegacyPagesQuery . "</h1>";
			}
		}				
	}else{
		echo "<title>" . $IncorrectLegacyPagesQuery . "</title>";
		$HTML = "<h1>" . $IncorrectLegacyPagesQuery . "</h1>";
	}
} catch (Exception $e) {
STHSErrorLegacyPages:
	$LeagueName = $DatabaseNotFound;
	echo "<title>" . $DatabaseNotFound . "</title>";
	$HTML = "<h1>" . $DatabaseNotFound . "</h1>";	
}}?>

</head><body>
<?php 
include "Menu.php";
echo($HTML);
include "Footer.php";?>
