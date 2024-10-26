<?php include "Header.php";
If ($lang == "fr"){include 'LanguageFR-Main.php';}else{include 'LanguageEN-Main.php';}
$LeagueName = (string)"";
If (file_exists($DatabaseFile) == false){
	Goto STHSErrorLeagueInformation;
}else{try{
	$LeagueName = (string)"";
		
	$db = new SQLite3($DatabaseFile);
	
	$Query = "Select Name FROM LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	
	$Query = "Select * FROM LeagueInformation";
	$LeagueInformation = $db->querySingle($Query,true);			
	
} catch (Exception $e) {
STHSErrorLeagueInformation:
	$LeagueName = $DatabaseNotFound;
	echo "<title>" . $DatabaseNotFound . "</title>";
	echo "<style>.STHSLeagueInformation_MainDiv{display:none}</style>";
}}
echo "<title>" . $LeagueName . " - " . $LeagueInformationLang['LeagueInformation'] . "</title>";
?>
</head><body>
<?php 
include "Menu.php";
echo "<div class=\"STHSLeagueInformation_MainDiv\">";
echo "<h1>" . $LeagueInformationLang['LeagueInformation'] . "</h1>";
?>

<div style="width:99%;margin:auto;">
<?php 
echo "<h1>" . $LeagueInformationLang['LeagueOwner'] . "</h1><div class=\"STHSPHPLeagueInformationDiv\">" . $LeagueInformation['LeagueOwner'] . "</div><br>";
echo "<h1>" . $LeagueInformationLang['HowToJoinLeague'] . "</h1><div class=\"STHSPHPLeagueInformationDiv\">" . $LeagueInformation['HowToJoinLeague'] . "</div><br>";
echo "<h1>" . $LeagueInformationLang['LeagueRules'] . "</h1><div class=\"STHSPHPLeagueInformationDiv\">" . str_replace("vbLf","<br>",$LeagueInformation['LeagueRules']) . "</div><br>";
?>
</div></div>

<?php include "Footer.php";?>
