<?php include "Header.php";
$Title = (string)"";
If (file_exists($DatabaseFile) == false){
	$LeagueName = $DatabaseNotFound;
}else{try{
	$LeagueName = (string)"";
		
	$db = new SQLite3($DatabaseFile);
	
	$Query = "Select Name FROM LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
} catch (Exception $e) {
STHSError:
$LeagueName = $DatabaseNotFound;
}}
echo "<title>" . $LeagueName . " - Blank Page</title>";
?>
</head><body>
<?php include "Menu.php";?>
<h1>Blank Page Title</h1>

<div style="width:99%;margin:auto;">
Blank Page Content<br /><br />P.S. Don't forget to change title.
</div>
<?php

echo "HTTP_HOST [{$_SERVER['HTTP_HOST']}]<br>"; 
echo "SERVER_NAME [{$_SERVER['SERVER_NAME']}]"; 


?>

<?php include "Footer.php";?>
