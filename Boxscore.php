<?php include "Header.php";?>
<?php
$Title = (string)"";
$TypeText = (string)"True";
$Farm = (boolean)False;
if(isset($_GET['Farm'])){$TypeText = "False";$Farm = True;}
$GameNumber = (integer)0;
$GameYear = (integer)0;
$GameHTML = (string)"";
$YearH1 = (integer)0;
$Playoff = (boolean)False;
$Preseason = (boolean)False;
$AllStar = (boolean)False;

If (file_exists($DatabaseFile) == false){
	$LeagueName = $DatabaseNotFound;
	echo "<title>" . $DatabaseNotFound . "</title>";
	$GameHTML = "<h1>" . $DatabaseNotFound . "</h1>";
}else{
	
	$db = new SQLite3($DatabaseFile);

	$Query = "Select Name, LeagueYear, PlayOffStarted, PreSeasonSchedule, OutputName from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	$GameYear = $LeagueGeneral['LeagueYear'];
	
	$Query = "Select OutputGameHTMLToSQLiteDatabase, WebsiteURL from LeagueOutputOption";
	$LeagueOutputOption = $db->querySingle($Query,true);	
	
	if(isset($_GET['Game'])){$GameNumber = filter_var($_GET['Game'], FILTER_SANITIZE_NUMBER_INT);} 
	if(isset($_GET['Year'])){
		$GameYear = filter_var($_GET['Year'], FILTER_SANITIZE_NUMBER_INT);$YearH1=$GameYear;
		if(isset($_GET['Playoff'])){$Playoff=True;}
		if(isset($_GET['Preseason'])){$Preseason=True;}
	}else{
		if($LeagueGeneral['PlayOffStarted'] == "True"){$Playoff=True;}
		if($LeagueGeneral['PreSeasonSchedule'] == "True"){$Preseason=True;}
	}
	
	If ($GameNumber > 0){
		If ($LeagueOutputOption['OutputGameHTMLToSQLiteDatabase'] == "True"){
			If($Playoff == True){$GameHTMLDatabaseFile = str_replace("-STHSGame","-PLF-STHSGame",$GameHTMLDatabaseFile);}
			If($Preseason == True){$GameHTMLDatabaseFile = str_replace("-STHSGame","-PRE-STHSGame",$GameHTMLDatabaseFile);}
			$GameDatabaseFile = str_replace("@-@",$GameYear."-".floor($GameNumber/200),$GameHTMLDatabaseFile);
			If ($GameNumber == 9999){$GameDatabaseFile = $AllStarDatabaseFile;$GameNumber=0;$AllStar=True;}
			
			If (file_exists($GameDatabaseFile) == false){
				If (file_exists($LeagueGeneral['OutputName']."-".$GameNumber.".php") == true AND $Farm = false){
					echo "<meta http-equiv=\"refresh\" content=\"0;url=" . $LeagueOutputOption['WebsiteURL'] . "/" . $LeagueGeneral['OutputName'] . "-" .$GameNumber . ".php" . "\"/>";
				}elseif(file_exists($LeagueGeneral['OutputName']."-Farm-".$GameNumber.".php") == true AND $Farm = true){
					echo "<meta http-equiv=\"refresh\" content=\"0;url=" . $LeagueOutputOption['WebsiteURL'] . "/" . $LeagueGeneral['OutputName'] . "-Farm-" .$GameNumber . ".php". "\"/>";
				}else{			
					echo "<title>" . $DatabaseNotFound . "</title>";
					$GameHTML = "<h1>" . $DatabaseNotFound . "</h1>";
				}
			}else{
				$Gamedb = new SQLite3($GameDatabaseFile);
				$Query = "Select * from GameResult WHERE Number = '" . $GameNumber . "' AND Pro = '" . $TypeText . "'";
				$GameResult = $Gamedb ->querySingle($Query,true);
				If ($GameResult != Null){
					$GameHTML = gzdecode(base64_decode($GameResult['HTML']));
					echo $GameResult['Engine']. "\n"; 
					echo $GameResult['Title']; 					
				}elseif (file_exists($LeagueGeneral['OutputName']."-".$GameNumber.".php") == true AND $Farm = false){
					echo "<meta http-equiv=\"refresh\" content=\"0;url=" . $LeagueOutputOption['WebsiteURL'] . "/" . $LeagueGeneral['OutputName'] . "-" .$GameNumber . ".php" . "\"/>";
				}elseif (file_exists($LeagueGeneral['OutputName']."-Farm-".$GameNumber.".php") == true AND $Farm = true){
					echo "<meta http-equiv=\"refresh\" content=\"0;url=" . $LeagueOutputOption['WebsiteURL'] . "/" . $LeagueGeneral['OutputName'] . "-Farm-" .$GameNumber . ".php". "\"/>";
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
if($YearH1 > 0){
	echo "<h1>" . $Boxscore['BoxscorefromYear'] . $YearH1;
	If ($Playoff == True){echo $TopMenuLang['Playoff'];}
	echo "</h1>";
}elseif($AllStar == True){
	echo "<h1>" . $TopMenuLang['AllStar'] . "</h1>";
}
echo($GameHTML);
include "Footer.php";?>
