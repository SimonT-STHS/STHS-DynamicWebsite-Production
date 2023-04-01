<?php include "Header.php";?>
<?php
$LeagueName = (string)"";

If (file_exists($DatabaseFile) == false){
	$LeagueName = $DatabaseNotFound;
	$Team = Null;
}else{
	$db = new SQLite3($DatabaseFile);
	
	if($CookieTeamNumber > 0 AND $CookieTeamNumber <= 100){
		$Query = "SELECT Number, Name FROM TeamProInfo Where Number = " . $CookieTeamNumber;
		$Team = $db->query($Query);
	}elseif($DoNotRequiredLoginDynamicWebsite == TRUE OR $CookieTeamNumber == 102){  // Commish is allow to edit any Teams
		$Query = "SELECT Number, Name FROM TeamProInfo ORDER BY Name";
		$Team = $db->query($Query);
	}else{
		$Team = Null;
	}
		
	$Query = "Select FarmEnable from LeagueSimulation";
	$LeagueSimulationMenu = $db->querySingle($Query,true);	

	$Query = "Select Name FROM LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
}
echo "<title>" . $LeagueName . " - " . $WebClientIndex['Title'] . "</title>";

?>
</head><body>
<?php include "Menu.php";?>
<h1><?php echo $WebClientIndex['Title'];?></h1>
<br />
<div style="width:95%;margin:auto;">
<?php if ($CookieTeamNumber == 0 AND $DoNotRequiredLoginDynamicWebsite == FALSE){echo "<div style=\"color:#FF0000; font-weight: bold;padding:1px 1px 1px 5px;text-align:center;\">" . $NoUserLogin . "<br /><br /></div>";}?>
<table class="tablesorter STHSPHPWebClient_Table">
<?php
echo "<thead><tr>";
echo "<th style=\"width:400px;\">" . $WebClientIndex['Team'] . "</th><th>" . $WebClientIndex['Roster'] . "</th><th>" . $WebClientIndex['ProLines'] . "</th>";
if(isset($LeagueSimulationMenu)){If ($LeagueSimulationMenu['FarmEnable'] == "True"){echo "<th>" . $WebClientIndex['FarmLines'] . "</th>";}}
echo "</tr></thead><tbody>\n";
if (empty($Team) == false){while ($row = $Team ->fetchArray()) { 
	echo "<tr><td><a href=\"ProTeam.php?Team=" . $row['Number'] . "\">" . $row['Name'] . "</a></td>\n";
	echo "<td class=\"STHSCenter\"><a href=\"WebClientRoster.php?TeamID=" . $row['Number'] . "\">" . $WebClientIndex['Edit'] . "</a></td>\n"; 
	echo "<td class=\"STHSCenter\"><a href=\"WebClientLines.php?League=Pro&TeamID=" . $row['Number'] . "\">" . $WebClientIndex['Edit'] . "</a></td>\n"; 
	If ($LeagueSimulationMenu['FarmEnable'] == "True"){echo "<td class=\"STHSCenter\"><a href=\"WebClientLines.php?League=Farm&TeamID=" . $row['Number'] . "\">" . $WebClientIndex['Edit'] . "</a></td></tr>\n";} 
}}
?>

</tbody></table>
</div>

<?php include "Footer.php";?>
