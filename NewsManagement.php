<?php include "Header.php";
If ($lang == "fr"){include 'LanguageFR-Main.php';}else{include 'LanguageEN-Main.php';}
$LeagueName = (string)"";
$InformationMessage = (string)"";
If (file_exists($DatabaseFile) == false){
	Goto STHSErrorNewsManagement;
}else{try{
	$db = new SQLite3($DatabaseFile);

	$Query = "Select Name FROM LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	
	if (isset($_POST["CreateNewsDatabase"]) && !empty($_POST["CreateNewsDatabase"]) && (file_exists($NewsDatabaseFile) == false)){
		$dbNews = new SQLite3($NewsDatabaseFile);
		$Query = "CREATE TABLE IF NOT EXISTS LeagueNews (Number INTEGER PRIMARY KEY AUTOINCREMENT,Time timestamp,TeamNumber integer,TeamNewsNumber integer, Owner string,Title string,Message string,Remove Boolean,WebClientModify Boolean,AnswerNumber integer)";
		$LeagueNewsCreate = $dbNews->query($Query);	
		$InformationMessage	= $NewsLang['CreateNewsDatabaseDone'];
	}
	
	If (file_exists($NewsDatabaseFile) == false){
		$LeagueNews = Null;
		$InformationMessage = $NewsDatabaseNotFound;	
		echo "<style>#MainDIV {display : none;}</style>";		
	}else{
		$dbNews = new SQLite3($NewsDatabaseFile);
		
		If ($CookieTeamNumber == 0){
			$InformationMessage = $NoUserLogin;
			echo "<style>#MainDIV {display : none;}</style>";
		}
		
		/* Process Mass Delete */
		if (isset($_POST["MassDelete"]) && !empty($_POST["MassDelete"])) {
			
			/* Get Variable */
			$MassDeleteNumber = filter_var($_POST['MassDelete'], FILTER_SANITIZE_NUMBER_INT);

			If ($CookieTeamNumber == 102){
				/* Delete From Database if Cookie Match */
				
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
				
				$InformationMessage = $NewsLang['MassDeleteSuccess1'] . $MassDeleteNumber . $NewsLang['MassDeleteSuccess2'];
			}else{
				$InformationMessage = $NewsLang['IllegalAction'];
			}
		}
		
		$Query = "Select LeagueNews.*, TeamProInfo.TeamThemeID, TeamProInfo.Name FROM LeagueNews LEFT JOIN TeamProInfo ON LeagueNews.TeamNumber = TeamProInfo.Number WHERE Remove = 'False' ORDER BY Time DESC";
		$dbNews -> query("ATTACH DATABASE '".realpath($DatabaseFile)."' AS CurrentDB");	
		$LeagueNews = $dbNews->query($Query);
	}
} catch (Exception $e) {
STHSErrorNewsManagement:	
	$LeagueName = $DatabaseNotFound;
	$LeagueNews = Null;
	$InformationMessage = $NewsDatabaseNotFound;
	echo "<style>#MainDIV {display : none;}</style>";
}}
echo "<title>" . $LeagueName . " - " . $NewsLang['LeagueNewsManagement'] . "</title>";

Function PrintMainNews($row, $IndexLang, $NewsLang, $dbNews, $CookieTeamNumber, $ImagesCDNPath ){
	/* This Function Print a News */
	$UTC = new DateTimeZone("UTC");
	$ServerTimeZone = new DateTimeZone(date_default_timezone_get());	
	$Date = new DateTime($row['Time'], $UTC );
	$Date->setTimezone($ServerTimeZone);
	echo "<tr><td>" . $Date->format('l jS F Y / g:ia ') . "</td>\n"; 
	echo "<td>" . $row['Owner'];
	If ($row['TeamNumber'] > 0 AND $row['TeamNumber'] <= 100){echo " (";If ($row['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSIndex_TheNewsTeamImage\" />";}echo $row['Name'] . ") ";}
	echo "</td>\n";
	echo "<td>" . $row['Title'] . "</td>\n";
	echo "<td class=\"STHSCenter\">";
	If ($row['TeamNumber'] == $CookieTeamNumber OR $CookieTeamNumber == 102){echo "<a href=\"NewsEditor.php?NewsID=" . $row['Number'] . "\">" . $NewsLang['EditErase'] . "</a> - ";}
	echo "<a href=\"NewsEditor.php?ReplyNews=" . $row['Number']. "\">" . $IndexLang['Comment'] . "</a></td></tr>\n";
	
	/* Query Reply */
	$NewsReply = Null;
	$Query = "Select LeagueNews.*, TeamProInfo.TeamThemeID, TeamProInfo.Name FROM LeagueNews LEFT JOIN TeamProInfo ON LeagueNews.TeamNumber = TeamProInfo.Number WHERE Remove = 'False' AND AnswerNumber = " . $row['Number'] . " ORDER BY Number";
	$NewsReply = $dbNews->query($Query);

	$Comment = 1;
	if (empty($NewsReply) == false){while ($ReplyRow = $NewsReply ->fetchArray()) {
		$Date = new DateTime($ReplyRow['Time'], $UTC );
		$Date->setTimezone($ServerTimeZone);
		echo "<tr><td>" . $Date->format('l jS F Y / g:ia ') . "</td>\n"; 
		echo "<td>" . $ReplyRow['Owner'];
		If ($ReplyRow['TeamNumber'] > 0){echo " (";If ($ReplyRow['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $ReplyRow['TeamThemeID'] .".png\" alt=\"\" class=\"STHSIndex_TheNewsTeamImage\" />";}echo $ReplyRow['Name'] . ") ";}
		echo "</td>\n";
		echo "<td>" . $NewsLang['Comment'] . $Comment . " : " . $row['Title'] . "</td>\n";
		echo "<td class=\"STHSCenter\">";
		If ($ReplyRow['TeamNumber'] == $CookieTeamNumber OR $CookieTeamNumber == 102){echo "<a href=\"NewsEditor.php?NewsID=" . $ReplyRow['Number'] . "\">" . $NewsLang['EditErase'] . "</a>";}
		echo "</td></tr>\n";
		$Comment++;
		
	}}	
}
?>
</head><body>
<?php include "Menu.php";?>
<h1><?php echo $NewsLang['LeagueNewsManagement'];?></h1>
<br />
<?php if ($InformationMessage != ""){echo "<div class=\"STHSDivInformationMessage\">" . $InformationMessage . "<br /><br /></div>\n";}

If ($CookieTeamNumber == 102){
	If (file_exists($NewsDatabaseFile) == false){
		echo "<div style=\"text-align:center;\">";
		echo "<form data-sample=\"1\" action=\"NewsManagement.php";If ($lang == "fr"){echo "?Lang=fr";}; echo "\" method=\"post\" data-sample-short=\"\">";
		echo "<input type=\"hidden\" name=\"CreateNewsDatabase\" value=\"CreateNewsDatabase\">";
		echo "<input type=\"submit\" class=\"SubmitButton\" value=\"" . $NewsLang['CreateNewsDatabase'] . "\"></form></div>";
	}
}
?>

<div id="MainDIV" style="width:95%;margin:auto;">
<h1 class="STHSCenter"><a href="NewsEditor.php"><?php echo $NewsLang['CreateNews'];?></a></h1>
<hr />
<h1><?php echo $NewsLang['EditNews'];?></h1>
<table class="tablesorter STHSPHPNewsMangement_Table">
<?php
$NewsPublish = array(); /* Array that Contain News Publish Already Publish */

echo "<thead><tr><th style=\"width:200px;\">" . $NewsLang['Time'] . "</th><th style=\"width:200px;\">" . $NewsLang['By'] . "</th><th style=\"width:400px;\">" . $NewsLang['Title'] . "</th><th class=\"STHSW200\">" . $NewsLang['Action'] . "</th></tr></thead><tbody>\n"; 
if (empty($LeagueNews) == false){while ($row = $LeagueNews ->fetchArray()) { 
	if (in_array($row['Number'],$NewsPublish) == FALSE AND in_array($row['AnswerNumber'],$NewsPublish) == FALSE ){ /* Make sure we already didn't publish this news */
		if ($row['AnswerNumber'] == 0){
			/* This row of the Table is not answer comment so it's main news */
			PrintMainNews($row, $IndexLang, $NewsLang, $dbNews, $CookieTeamNumber, $ImagesCDNPath );  /* Print the News */
		}else{
			/* This is row is answer to previous news. Finding the Main News Information */
			$Query = "Select LeagueNews.*, TeamProInfo.TeamThemeID, TeamProInfo.Name FROM LeagueNews LEFT JOIN TeamProInfo ON LeagueNews.TeamNumber = TeamProInfo.Number WHERE LeagueNews.Number = " . $row['AnswerNumber'];
			$NewsTemp = $dbNews->querySingle($Query,True);
					
			/* Print the News */
			PrintMainNews($NewsTemp, $IndexLang, $NewsLang, $dbNews, $CookieTeamNumber, $ImagesCDNPath );  
					
			/* Add in the Array the Main News will be publish */
			array_push($NewsPublish, $row['AnswerNumber']); 
		}
	}
	
}}else{echo "<br /><h3>" . $NewsDatabaseNotFound . "</h3>";}
?>

</tbody></table>
<br />
<?php
If ($CookieTeamNumber == 102){
	echo "<form data-sample=\"1\" action=\"NewsManagement.php";If ($lang == "fr"){echo "?Lang=fr";}; echo "\" method=\"post\" data-sample-short=\"\">";
	echo "<strong>" . $NewsLang['MassDeletion'] . "</strong><input type=\"number\"  name=\"MassDelete\" required><br /><br />";
	echo "<input type=\"submit\" class=\"SubmitButton\" value=\"" .  $NewsLang['MassDelete'] . "\"> &lt;-- <strong>" . $NewsLang['MassDeleteWarning'] . "</strong></form>";
}
?>


</div>

<?php include "Footer.php";?>
