<?php include "Header.php";
If ($lang == "fr"){include 'LanguageFR-Main.php';}else{include 'LanguageEN-Main.php';}
$LeagueName = (string)"";
$InformationMessage = "";
If (file_exists($DatabaseFile) == false){
	Goto STHSWebClientIndex;
}else{try{
	$db = new SQLite3($DatabaseFile);
	
	$Query = "Select FarmEnable from LeagueSimulation";
	$LeagueSimulationMenu = $db->querySingle($Query,true);	

	$Query = "Select Name FROM LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	
	$Query = "Select ShowWebClientInDymanicWebsite FROM LeagueOutputOption";
	$LeagueOutputOption = $db->querySingle($Query,true);
	
	If ($LeagueOutputOption['ShowWebClientInDymanicWebsite'] == "False"){
		$Team = Null;
		$InformationMessage = $ThisPageNotAvailable;
		echo "<style>#WebClientIndexMainDiv{display:none}</style>";
	}elseif($CookieTeamNumber > 0 AND $CookieTeamNumber <= 100){
		$Query = "SELECT Number, Name FROM TeamProInfo Where Number = " . $CookieTeamNumber;
		$Team = $db->query($Query);
	}elseif($DoNotRequiredLoginDynamicWebsite == TRUE OR $CookieTeamNumber == 102){  // Commish is allow to edit any Teams
		$Query = "SELECT Number, Name FROM TeamProInfo ORDER BY Name";
		$Team = $db->query($Query);
	}else{
		$Team = Null;
		$InformationMessage = $NoUserLogin;
		echo "<style>#WebClientIndexMainDiv{display:none}</style>";
	}

} catch (Exception $e) {
STHSWebClientIndex:
	$LeagueName = $DatabaseNotFound;
	$Team = Null;
}}
echo "<title>" . $LeagueName . " - " . $WebClientLang['Title'] . "</title>";

?>
</head><body>
<?php include "Menu.php";?>
<h1><?php echo $WebClientLang['Title'];?></h1>
<br />
<?php if ($InformationMessage != ""){echo "<div class=\"STHSDivInformationMessage\">" . $InformationMessage . "<br /><br /></div>";}?>
<div id="WebClientIndexMainDiv" style="width:95%;margin:auto;">
<table class="tablesorter STHSPHPWebClient_Table">
<?php
echo "<thead><tr>";
echo "<th style=\"width:400px;\">" . $WebClientLang['Team'] . "</th><th>" . $WebClientLang['Roster'] . "</th><th>" . $WebClientLang['ProLines'] . "</th>";
if(isset($LeagueSimulationMenu)){If ($LeagueSimulationMenu['FarmEnable'] == "True"){echo "<th>" . $WebClientLang['FarmLines'] . "</th>";}}
If($CookieTeamNumber > 0 AND $CookieTeamNumber <= 100){echo "<th style=\"width:400px;\">" . $WebClientLang['TeamInfo'] . "</th>";}
echo "</tr></thead><tbody>\n";
if (empty($Team) == false){while ($row = $Team ->fetchArray()) { 
	echo "<tr><td><a href=\"ProTeam.php?Team=" . $row['Number'] . "\">" . $row['Name'] . "</a></td>\n";
	echo "<td class=\"STHSCenter\"><a href=\"WebClientRoster.php?TeamID=" . $row['Number'] . "\">" . $WebClientLang['Edit'] . "</a></td>\n"; 
	echo "<td class=\"STHSCenter\"><a href=\"WebClientLines.php?League=Pro&TeamID=" . $row['Number'] . "\">" . $WebClientLang['Edit'] . "</a></td>\n"; 
	If ($LeagueSimulationMenu['FarmEnable'] == "True"){echo "<td class=\"STHSCenter\"><a href=\"WebClientLines.php?League=Farm&TeamID=" . $row['Number'] . "\">" . $WebClientLang['Edit'] . "</a></td>\n";} 
	If($CookieTeamNumber > 0 AND $CookieTeamNumber <= 100){echo "<td class=\"STHSCenter\"><a href=\"WebClientTeam.php\">" . $WebClientLang['Edit'] . "</a></td>\n";}
	echo "</tr>";
}}
?>

</tbody></table>
</div>

<?php include "Footer.php";?>
