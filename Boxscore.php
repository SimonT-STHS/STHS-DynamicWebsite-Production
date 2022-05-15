<?php include "Header.php";?>
<?php
$Title = (string)"";
$TypeText = (string)"True";
if(isset($_GET['Farm'])){$TypeText = "False";}
$GameNumber = (integer)0;
$GameYear = (integer)0;
$GameHTML = (string)"";
$YearH1 = (integer)0;

If (file_exists($DatabaseFile) == false){
	$LeagueName = $DatabaseNotFound;
	echo "<title>" . $DatabaseNotFound . "</title>";
	$GameHTML = "<h1>" . $DatabaseNotFound . "</h1>";
}else{
	
	$db = new SQLite3($DatabaseFile);

	$Query = "Select Name, LeagueYear from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	$GameYear = $LeagueGeneral['LeagueYear'];
	
	$Query = "Select OutputGameHTMLToSQLiteDatabase from LeagueOutputOption";
	$LeagueOutputOption = $db->querySingle($Query,true);	
	
	if(isset($_GET['Game'])){$GameNumber = filter_var($_GET['Game'], FILTER_SANITIZE_NUMBER_INT);} 
	if(isset($_GET['Year'])){$GameYear = filter_var($_GET['Year'], FILTER_SANITIZE_NUMBER_INT);$YearH1=$GameYear;}
	If ($GameNumber > 0){
		If ($LeagueOutputOption['OutputGameHTMLToSQLiteDatabase'] == "True"){
			$GameDatabaseFile = str_replace("@-@",$GameYear."-".floor($GameNumber/200),$GameHTMLDatabaseFile);
			
			If (file_exists($GameDatabaseFile) == false){
				echo "<title>" . $DatabaseNotFound . "</title>";
				$GameHTML = "<h1>" . $DatabaseNotFound . "</h1>";
			}else{
				$Gamedb = new SQLite3($GameDatabaseFile);
				$Query = "Select * from GameResult WHERE Number = '" . $GameNumber . "' AND Pro = '" . $TypeText . "'";
				$GameResult = $Gamedb ->querySingle($Query,true);
				If ($GameResult != Null){
					$GameHTML = gzdecode(base64_decode($GameResult['HTML']));
					echo $GameResult['Engine']. "\n"; 
					echo $GameResult['Title']; 
					
				}else{
					echo "<title>" . $IncorrectGameQuery . "</title>";
					$GameHTML = "<h1>" . $IncorrectGameQuery . "</h1>";
				}
			}				
		}else{
			echo "<title>" . $IncorrectGameQuery . "</title>";
			$GameHTML = "<h1>" . $IncorrectGameQuery . "</h1>";
		}
	}else{
		echo "<title>" . $IncorrectGameQuery . "</title>";
		$GameHTML = "<h1>" . $IncorrectGameQuery . "</h1>";
	}
}?>

</head><body>
<?php 
include "Menu.php";
if($YearH1 > 0){echo "<h1>" . $Boxscore['BoxscorefromYear'] . $YearH1 . "</h1>";}
echo($GameHTML);
include "Footer.php";?>
