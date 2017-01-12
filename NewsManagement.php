<!DOCTYPE html>
<?php include "Header.php";?>
<?php
$LeagueName = (string)"";
$InformationMessage = (string)"";
$Active = 1; /* Show Webpage Top Menu */
If (file_exists($DatabaseFile) == false){
	$LeagueName = $DatabaseNotFound;
	$LeagueNews = Null;
	echo "<style type=\"text/css\">#MainDIV {display : none;}</style>";
}else{
	$db = new SQLite3($DatabaseFile);

	$Query = "Select Name,LeagueWebPassword FROM LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	
	If (file_exists($NewsDatabaseFile) == false){
		$LeagueNews = Null;
		$InformationMessage = $NewsDatabaseNotFound;	
		echo "<style type=\"text/css\">#MainDIV {display : none;}</style>";
	}else{
		$dbNews = new SQLite3($NewsDatabaseFile);
		
		/* Process Mass Delete */
		if (isset($_POST["MassDelete"]) && !empty($_POST["MassDelete"]) && isset($_POST["Password"]) && !empty($_POST["Password"])) {
			
			/* Get Variable */
			$HashMatch = (boolean)FALSE;
			$Password = filter_var($_POST["Password"], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH);
			$MassDeleteNumber = filter_var($_POST['MassDelete'], FILTER_SANITIZE_NUMBER_INT);
			
			/* League Management Hash for League*/
			$LeagueCalculateHash = strtoupper(Hash('sha512', mb_convert_encoding(($LeagueName . $Password), 'ASCII')));
			$LeagueDatabaseHash = $LeagueGeneral['LeagueWebPassword'];
			If ($LeagueCalculateHash == $LeagueDatabaseHash && $LeagueDatabaseHash != "" && $LeagueGeneral['LeagueWebPassword'] != ""){$HashMatch = True;} /* Can only match if LeagueWebPassword is not empty */
			
			If ($HashMatch == True){
				/* Delete From Database if Password Match */
				
				/* Get the News via SQLite Query of Limit */
				$Query = "Select * FROM LeagueNews WHERE Remove = 'False' AND AnswerNumber = 0 ORDER BY Number Limit " . $MassDeleteNumber;
				$LeagueNewsDelete = $dbNews->query($Query);
				
				/* Loop the News to also delete Comment */
				if (empty($LeagueNewsDelete) == false){while ($row = $LeagueNewsDelete ->fetchArray()) {
					/* Delete News Comment */
					$sql = "DELETE from LeagueNews WHERE LeagueNews.AnswerNumber = " . $row['Number'];
					$dbNews->exec($sql);
					/* Delete News itself */
					$sql = "DELETE from LeagueNews WHERE LeagueNews.Number = " . $row['Number'];
					$dbNews->exec($sql);
				}}
				
				$InformationMessage = $News['MassDeleteSuccess1'] . $MassDeleteNumber . $News['MassDeleteSuccess2'];
			}else{
				$InformationMessage = $News['IncorrectPassword'];
			}
		}
		
		$Query = "Select * FROM LeagueNews WHERE Remove = 'False' ORDER BY Time DESC";
		$LeagueNews = $dbNews->query($Query);
	}
}
echo "<title>" . $LeagueName . " - " . $News['LeagueNewsManagement'] . "</title>";

Function PrintMainNews($row, $IndexLang, $News, $dbNews){
	/* This Function Print a News */
	$UTC = new DateTimeZone("UTC");
	$ServerTimeZone = new DateTimeZone(date_default_timezone_get());	
	$Date = new DateTime($row['Time'], $UTC );
	$Date->setTimezone($ServerTimeZone);
	echo "<tr><td>" . $Date->format('l jS F Y / g:ia ') . "</td>\n"; 
	echo "<td>" . $row['Owner'] . "</td>\n";
	echo "<td>" . $row['Title'] . "</td>\n";
	echo "<td class=\"STHSCenter\"><a href=\"NewsEditor.php?NewsID=" . $row['Number'] . "\">" . $News['EditErase'] . "</a> - <a href=\"NewsEditor.php?ReplyNews=" . $row['Number']. "\">" . $IndexLang['Comment'] . "</a></td></tr>\n";
	
	/* Query Reply */
	$NewsReply = Null;
	$Query = "Select * FROM LeagueNews WHERE Remove = 'False' AND AnswerNumber = " . $row['Number'] . " ORDER BY Number";
	$NewsReply = $dbNews->query($Query);

	$Comment = 1;
	if (empty($NewsReply) == false){while ($ReplyRow = $NewsReply ->fetchArray()) {
		$Date = new DateTime($ReplyRow['Time'], $UTC );
		$Date->setTimezone($ServerTimeZone);
		echo "<tr><td>" . $Date->format('l jS F Y / g:ia ') . "</td>\n"; 
		echo "<td>" . $ReplyRow['Owner'] . "</td>\n";
		echo "<td>" . $News['Comment'] . $Comment . " : " . $row['Title'] . "</td>\n";
		echo "<td class=\"STHSCenter\"><a href=\"NewsEditor.php?NewsID=" . $ReplyRow['Number'] . "\">" . $News['EditErase'] . "</a></td></tr>\n";
		$Comment++;
		
	}}	
}
?>
<script src="//cdn.ckeditor.com/4.5.9/standard/ckeditor.js"></script>
</head><body>
<?php include "Menu.php";?>
<h1><?php echo $News['LeagueNewsManagement'];?></h1>
<br />
<?php if ($InformationMessage != ""){echo "<div style=\"color:#FF0000; font-weight: bold;padding:1px 1px 1px 5px;text-align:center;\">" . $InformationMessage . "<br /><br /></div>";}?>
<div id="MainDIV" style="width:95%;margin:auto;">
<h1 class="STHSCenter"><a href="NewsEditor.php"><?php echo $News['CreateNews'];?></a></h1>
<hr />
<h1><?php echo $News['EditNews'];?></h1>
<table class="tablesorter STHSPHPNewsMangement_Table">
<?php
$NewsPublish = array(); /* Array that Contain News Publish Already Publish */

echo "<thead><tr><th style=\"width:200px;\">" . $News['Time'] . "</th><th style=\"width:200px;\">" . $News['By'] . "</th><th style=\"width:400px;\">" . $News['Title'] . "</th><th class=\"STHSW200\">" . $News['Action'] . "</th></tr></thead><tbody>\n"; 
if (empty($LeagueNews) == false){while ($row = $LeagueNews ->fetchArray()) { 
	if (in_array($row['Number'],$NewsPublish) == FALSE AND in_array($row['AnswerNumber'],$NewsPublish) == FALSE ){ /* Make sure we already didn't publish this news */
		if ($row['AnswerNumber'] == 0){
			/* This row of the Table is not answer comment so it's main news */
			PrintMainNews($row, $IndexLang, $News, $dbNews);  /* Print the News */
		}else{
			/* This is row is answer to previous news. Finding the Main News Information */
			
			$Query = "Select * FROM LeagueNews WHERE Number = " . $row['AnswerNumber'];
			$NewsTemp = $dbNews->querySingle($Query,True);
					
			/* Print the News */
			PrintMainNews($NewsTemp, $IndexLang,$News, $dbNews);  
					
			/* Add in the Array the Main News will be publish */
			array_push($NewsPublish, $row['AnswerNumber']); 
		}
	}
	
}}else{echo "<br /><h3>" . $NewsDatabaseNotFound . "</h3>";}
?>

</tbody></table>
<br />
<form data-sample="1" action="NewsManagement.php<?php If ($lang == "fr"){echo "?Lang=fr";}?>" method="post" data-sample-short="">
<strong><?php echo $News['MassDeletion'];?></strong><input type="number" size="5" name="MassDelete" required><br />
<strong><?php echo $News['Password'];?></strong><input type="password" name="Password" size="20" value="" required><br /><br />
<input type="submit" style="padding-left:20px;padding-right:20px" value="<?php echo $News['MassDelete'];?>"> <-- <strong><?php echo $News['MassDeleteWarning'];?></strong>
</form>

</div>

<?php include "Footer.php";?>
