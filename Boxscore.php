<?php include "Header.php";
$Title = (string)"";
$TypeText = (string)"True";
$Farm = (bool)False;
if(isset($_GET['Farm'])){$TypeText = "False";$Farm = True;}
$GameNumber = (int)0;
$GameYear = (int)0;
$GameHTML = (string)"";
$YearH1 = (int)0;
$Playoff = (bool)False;
$Preseason = (bool)False;
$AllStar = (bool)False;

If (file_exists($DatabaseFile) == false){
	Goto STHSErrorBoxscore;
}else{try{
	
	$db = new SQLite3($DatabaseFile);

	$Query = "Select Name, LeagueYear, PlayOffStarted, PreSeasonSchedule, OutputName, OutputFileFormat from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	$GameYear = $LeagueGeneral['LeagueYear'];
	
	$Query = "Select OutputGameHTMLToSQLiteDatabase, WebsiteURL from LeagueOutputOption";
	$LeagueOutputOption = $db->querySingle($Query,true);	
	
	if(isset($_GET['Game'])){$GameNumber = (int)filter_var($_GET['Game'], FILTER_SANITIZE_NUMBER_INT);} 
	if(isset($_GET['Year'])){
		$GameYear = filter_var($_GET['Year'], FILTER_SANITIZE_NUMBER_INT);$YearH1=$GameYear;
		if(isset($_GET['Playoff'])){$Playoff=True;}
		if(isset($_GET['Preseason'])){$Preseason=True;}
	}else{
		if($LeagueGeneral['PlayOffStarted'] == "True"){$Playoff=True;}
		if($LeagueGeneral['PreSeasonSchedule'] == "True"){$Preseason=True;}
		if(isset($_GET['Preseason'])){$Preseason=True;}
	}
	
	If($CookieTeamNumber == 0 AND $STHSBotProtectionLevel1 == True){$GameNumber = 0;}
	
	If ($GameNumber > 0){
		If ($LeagueOutputOption['OutputGameHTMLToSQLiteDatabase'] == "True"){
			If($Playoff == True){$GameHTMLDatabaseFile = str_replace("-STHSGame","-PLF-STHSGame",$GameHTMLDatabaseFile);}
			If($Preseason == True){$GameHTMLDatabaseFile = str_replace("-STHSGame","-PRE-STHSGame",$GameHTMLDatabaseFile);}
			$GameDatabaseFile = str_replace("@-@",$GameYear."-".floor($GameNumber/200),$GameHTMLDatabaseFile);
			If ($GameNumber == 9999){$GameDatabaseFile = $AllStarDatabaseFile;$GameNumber=0;$AllStar=True;}
			
			If (file_exists($GameDatabaseFile) == false){
				If($STHSIntegratedHosting == True){
					echo "<title>" . $DatabaseNotFound . "</title>";
					$GameHTML = "<h1>" . $DatabaseNotFound . "</h1>";
				}else{ // Check if BoxScore File Exist when not Integrated Hosting 
					If (file_exists($LeagueGeneral['OutputName']."-".$GameNumber.".".$LeagueGeneral['OutputFileFormat']) == true AND $Farm == false){
						echo "<meta http-equiv=\"refresh\" content=\"0;url=" . $LeagueOutputOption['WebsiteURL'] . "/" . $LeagueGeneral['OutputName'] . "-" .$GameNumber . "." . $LeagueGeneral['OutputFileFormat'] . "\"/>";
					}elseif(file_exists($LeagueGeneral['OutputName']."-Farm-".$GameNumber.".".$LeagueGeneral['OutputFileFormat']) == true AND $Farm == true){
						echo "<meta http-equiv=\"refresh\" content=\"0;url=" . $LeagueOutputOption['WebsiteURL'] . "/" . $LeagueGeneral['OutputName'] . "-Farm-" .$GameNumber . "." . $LeagueGeneral['OutputFileFormat']. "\"/>";
					}else{			
						echo "<title>" . $DatabaseNotFound . "</title>";
						$GameHTML = "<h1>" . $DatabaseNotFound . "</h1>";
					}
				}
			}else{
				$Gamedb = new SQLite3($GameDatabaseFile);
				$Query = "Select * from GameResult WHERE Number = '" . $GameNumber . "' AND Pro = '" . $TypeText . "'";
				$GameResult = $Gamedb ->querySingle($Query,true);
				If ($GameResult != Null){
					$GameHTML = gzdecode(base64_decode($GameResult['HTML']));
					echo $GameResult['Engine']. "\n"; 
					echo $GameResult['Title']; 				
				}else{	
					If($STHSIntegratedHosting == True){
						echo "<title>" . $IncorrectGameQuery . "</title>";
						$GameHTML = "<h1>" . $IncorrectGameQuery . "</h1>";
					}else{ // Check if BoxScore File Exist when not Integrated Hosting 
						if (file_exists($LeagueGeneral['OutputName']."-".$GameNumber.".".$LeagueGeneral['OutputFileFormat']) == true AND $Farm == false){
							echo "<meta http-equiv=\"refresh\" content=\"0;url=" . $LeagueOutputOption['WebsiteURL'] . "/" . $LeagueGeneral['OutputName'] . "-" .$GameNumber . "." . $LeagueGeneral['OutputFileFormat'] . "\"/>";
						}elseif (file_exists($LeagueGeneral['OutputName']."-Farm-".$GameNumber.".".$LeagueGeneral['OutputFileFormat']) == true AND $Farm == true){
							echo "<meta http-equiv=\"refresh\" content=\"0;url=" . $LeagueOutputOption['WebsiteURL'] . "/" . $LeagueGeneral['OutputName'] . "-Farm-" .$GameNumber . "." . $LeagueGeneral['OutputFileFormat']. "\"/>";
						}else{	
							echo "<title>" . $IncorrectGameQuery . "</title>";
							$GameHTML = "<h1>" . $IncorrectGameQuery . "</h1>";
						}
					}
				}
			}				
		}else{
			echo "<title>" . $IncorrectGameQuery . "</title>";
			$GameHTML = "<h1>" . $IncorrectGameQuery . "</h1>";
		}
	}else{
		If ($STHSBotProtectionLevel1 == False){
			echo "<title>" . $IncorrectGameQuery . "</title>";
			$GameHTML = "<h1>" . $IncorrectGameQuery . "</h1>";			
		}else{
			echo "<title>" . $NoUserLogin . "</title>";
			$GameHTML = "<h1>" . $NoUserLogin . "</h1>";		
		}
	}
} catch (Exception $e) {
STHSErrorBoxscore:
	$LeagueName = $DatabaseNotFound;
	echo "<title>" . $DatabaseNotFound . "</title>";
	$GameHTML = "";
}}?>
<style>
.tabmain-links {grid-template-columns: repeat(5, minmax(0, 1fr));}
</style>
</head><body>
<?php 
include "Menu.php";
if($YearH1 > 0){
	If ($lang == "fr"){include 'LanguageFR-Main.php';}else{include 'LanguageEN-Main.php';}
	echo "<h1>" . $BoxscoreLang['BoxscorefromYear'] . $YearH1;
	If ($Playoff == True){echo $TopMenuLang['Playoff'];}
	echo "</h1>";
}elseif($AllStar == True){
	echo "<h1>" . $TopMenuLang['AllStar'] . "</h1>";
}
If($STHSIntegratedHosting == True){
	echo(str_replace("./images/",$ImagesCDNPath."/images/",$GameHTML,));
}else{
	echo($GameHTML);
}	
include "Footer.php";?>
