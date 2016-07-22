<!DOCTYPE html>
<?php include "Header.php";?>
<?php
$LeagueName = (string)"";
$Active = 1; /* Show Webpage Top Menu */
If (file_exists($DatabaseFile) == false){
	$LeagueName = $DatabaseNotFound;
	$LeagueNews = Null;
}else{
	$db = new SQLite3($DatabaseFile);
	$Query = "Select * FROM LeagueNews WHERE Remove = 'False' ORDER BY Number DESC";
	$LeagueNews = $db->query($Query);

	$Query = "Select Name FROM LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
}
echo "<title>" . $LeagueName . " - " . $News['LeagueNewsManagement'] . "</title>";

?>
<script src="//cdn.ckeditor.com/4.5.9/standard/ckeditor.js"></script>
</head><body>
<?php include "Menu.php";?>
<h1><?php echo $News['LeagueNewsManagement'];?></h1>
<br />
<div style="width:95%;margin:auto;">
<h1 class="STHSCenter"><a href="NewsEditor.php"><?php echo $News['CreateNews'];?></a></h1>
<hr />
<h1><?php echo $News['EditNews'];?></h1>
<table class="tablesorter STHSPHPNewsMangement_Table">
<?php
$UTC = new DateTimeZone("UTC");
$ServerTimeZone = new DateTimeZone(date_default_timezone_get());

echo "<thead><tr><th style=\"width:200px;\">" . $News['Time'] . "</th><th style=\"width:200px;\">" . $News['By'] . "</th><th style=\"width:400px;\">" . $News['Title'] . "</th><th class=\"STHSW100\">" . $News['Action'] . "</th></tr></thead><tbody>\n"; 
if (empty($LeagueNews) == false){while ($row = $LeagueNews ->fetchArray()) { 
	$Date = new DateTime($row['Time'], $UTC );
	$Date->setTimezone($ServerTimeZone);
	echo "<tr><td>" . $Date->format('l jS F Y \a\\t\ g:ia ') . "</td>\n"; 
	echo "<td>" . $row['Owner'] . "</td>\n";
	echo "<td>" . $row['Title'] . "</td>\n";
	echo "<td class=\"STHSCenter\"><a href=\"NewsEditor.php?NewsID=" . $row['Number'] . "\">" . $News['EditErase'] . "</a></td></tr>\n";
}}
?>

</tbody></table>
</div>

<?php include "Footer.php";?>
