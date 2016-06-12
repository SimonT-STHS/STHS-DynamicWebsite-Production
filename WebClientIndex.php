<!DOCTYPE html>
<?php include "Header.php";?>
<?php
$LeagueName = (string)"";

If (file_exists($DatabaseFile) == false){
	$LeagueName = $DatabaseNotFound;
	$Team = Null;
}else{
	$db = new SQLite3($DatabaseFile);
	
	$Query = "SELECT Number, Name FROM TeamProInfo ORDER BY Name";
	$Team = $db->query($Query);

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
<table class="tablesorter STHSPHPWebClient_Table">
<?php
echo "<thead><tr><th style=\"width:400px;\">" . $WebClientIndex['Team'] . "</th><th>" . $WebClientIndex['Roster'] . "</th><th>" . $WebClientIndex['ProLines'] . "</th><th>" . $WebClientIndex['FarmLines'] . "</th></tr></thead><tbody>\n"; 
if (empty($Team) == false){while ($row = $Team ->fetchArray()) { 
	echo "<tr><td><a href=\"ProTeam.php?Team=" . $row['Number'] . "\">" . $row['Name'] . "</a></td>\n";
	echo "<td class=\"STHSCenter\"><a href=\"WebClientRoster.php?TeamID=" . $row['Number'] . "\">" . $WebClientIndex['Edit'] . "</a></td>\n"; 
	echo "<td class=\"STHSCenter\"><a href=\"WebClientLines.php?League=Pro&TeamID=" . $row['Number'] . "\">" . $WebClientIndex['Edit'] . "</a></td>\n"; 
	echo "<td class=\"STHSCenter\"><a href=\"WebClientLines.php?League=Farm&TeamID=" . $row['Number'] . "\">" . $WebClientIndex['Edit'] . "</a></td></tr>\n"; 
}}
?>

</tbody></table>
</div>

<?php include "Footer.php";?>
