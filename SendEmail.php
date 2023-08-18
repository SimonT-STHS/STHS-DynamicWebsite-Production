<?php include "Header.php";
If ($lang == "fr"){include 'LanguageFR-Main.php';}else{include 'LanguageEN-Main.php';}
$LeagueName = (string)"";
$CanSendEmail = (integer)0; /* 0 = Nothing / 1 = Good Password /  2 = Bad Password */
$DebugMode = (boolean)False;
If (file_exists($DatabaseFile) == false){
	Goto STHSErrorSendEmail;

}else{try{
	$db = new SQLite3($DatabaseFile);
		
	$Query = "Select Name, LeagueWebPassword from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	
	$Query = "Select WebsiteURL, EmailServer, EmailServerReplyAddress, OutputGameHTMLToSQLiteDatabase from LeagueOutputOption";
	$LeagueOutputOption = $db->querySingle($Query,true);

	$Query = "Select FarmEnable from LeagueSimulation";
	$LeagueSimulationMenu = $db->querySingle($Query,true);		
	
	/* Confirm League Password is Correct to Send Email */
	If ($CookieTeamNumber == 102){
		if (isset($_POST["DebugMode"])) {
			$CanSendEmail = 2;
			$DebugMode = True;
		}elseif(isset($_POST["SubmitMail"])) {		
			$CanSendEmail = 1;
		}else{
			$CanSendEmail = 2;
		}
		
		/* Query Team Where Email is found in Database */
		$Query = "Select Number, Name, GMName, Email from TeamProInfo WHERE Email <> ''";
		If ($DebugMode == True){$Query = "Select Number, Name, GMName, Email from TeamProInfo";}
		$Team = $db->query($Query);
	}
		
} catch (Exception $e) {
STHSErrorSendEmail:
	$LeagueName = $DatabaseNotFound;
	$TodayGame = Null;
	echo "<title>" . $DatabaseNotFound . "</title>";
	$LeagueOutputOption = Null;	
}}

echo "<title>" . $LeagueName . $SendEmailLang['Title'] . "</title>";
If ($CookieTeamNumber != 102){
	echo "<style>#MainDIV {display : none;}</style>";
}
?>
</head><body>
<?php include "Menu.php";?>
<br />

<?php If ($CookieTeamNumber != 102){echo "<div class=\"STHSDivInformationMessage\">";If ($CookieTeamNumber >0 ){echo $ThisPageNotAvailable;}else{echo $NoUserLogin;} echo "<br /><br /></div>";}?>
<div id="MainDIV" style="width:95%;margin:auto;">
<h1><?php echo $SendEmailLang['Title'];?></h1>
<br />
<?php

$InformationAvailable = (boolean)False;
If ($CookieTeamNumber == 102){

/* Get Server Time */
$UTC = new DateTimeZone("UTC");
$ServerTimeZone = new DateTimeZone(date_default_timezone_get());
$Date = new DateTime();
$Date->setTimezone($ServerTimeZone);

/* Configured Varialbe*/
$TeamTextTitle = (string)"";
$TeamText = (string)"";
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
If (isset($LeagueOutputOption) == True){
	If ($LeagueOutputOption['EmailServerReplyAddress'] != ""){
		$headers .= 'From: ' . $LeagueName . ' <' . $LeagueOutputOption['EmailServerReplyAddress'] . '>' . "\r\n";
	}else{
		$headers .= 'From: ' . $LeagueName . ' <noreply@noreply.com>' . "\r\n";
	}
	If ($LeagueOutputOption['EmailServer'] != ""){ini_set('SMTP',$LeagueOutputOption['EmailServer']);}
}

if (empty($Team) == false){while ($Row = $Team ->fetchArray()) {
	/* Loop Team */
	
	/* Reset Variable */
	$TeamTodayGame = Null;
	$TeamText = "";
	$TeamTextTitle = $LeagueName . " - Today's Information for " . $Row['Name'] . " - Create at " . $Date->format('l jS F Y / g:ia ');
	
	/* Query TodayGame for Game Play by this Team */
	$Query = "SELECT TodayGame.* FROM TodayGame WHERE VisitorTeamNumber = '" . $Row['Number'] . "' OR HomeTeamNumber = '" . $Row['Number'] . "'";
	$TeamTodayGame =  $db->query($Query);

	if (empty($TeamTodayGame) == false){while ($TeamRow = $TeamTodayGame ->fetchArray()) {
		/* Loop Result and Build Text String */
		If ($LeagueOutputOption['OutputGameHTMLToSQLiteDatabase'] == "True"){
			If (substr($TeamRow['GameNumber'],0,3) == "Pro"){
				$TeamText = $TeamText . "Game " . $TeamRow['GameNumber'] . "<br />" . $TeamRow['VisitorTeam'] . " : <strong>" . $TeamRow['VisitorTeamScore'] . "</strong> vs " . $TeamRow['HomeTeam'] . " : <strong>" . $TeamRow['HomeTeamScore'] . "</strong><br /><a href=\"" . $LeagueOutputOption['WebsiteURL'] . "/Boxscore.php?Game=" .  substr($TeamRow['GameNumber'],3) ."\">" . $SendEmailLang['LinktoBoxscore'] . "</a><br /><br />";		
			}elseif(substr($TeamRow['GameNumber'],0,4) == "Farm"){
				$TeamText = $TeamText . "Game " . $TeamRow['GameNumber'] . "<br />" . $TeamRow['VisitorTeam'] . " : <strong>" . $TeamRow['VisitorTeamScore'] . "</strong> vs " . $TeamRow['HomeTeam'] . " : <strong>" . $TeamRow['HomeTeamScore'] . "</strong><br /><a href=\"" . $LeagueOutputOption['WebsiteURL'] . "/Boxscore.php?Game=" .  substr($TeamRow['GameNumber'],4) ."&Farm\">" . $SendEmailLang['LinktoBoxscore'] . "</a><br /><br />";									
			}else{
				$TeamText = $TeamText . "Game " . $TeamRow['GameNumber'] . "<br />" . $TeamRow['VisitorTeam'] . " : <strong>" . $TeamRow['VisitorTeamScore'] . "</strong> vs " . $TeamRow['HomeTeam'] . " : <strong>" . $TeamRow['HomeTeamScore'] . "</strong><br /><br />";		
			}
		}else{
			$TeamText = $TeamText . "Game " . $TeamRow['GameNumber'] . "<br />" . $TeamRow['VisitorTeam'] . " : <strong>" . $TeamRow['VisitorTeamScore'] . "</strong> vs " . $TeamRow['HomeTeam'] . " : <strong>" . $TeamRow['HomeTeamScore'] . "</strong><br /><a href=\"" . $LeagueOutputOption['WebsiteURL'] . "/" . $TeamRow['Link'] . "\">" . $SendEmailLang['LinktoBoxscore'] . "</a><br /><br />";		
		}
	}}
	
	$Query = "SELECT CurrentLineValid, Name from TeamProInfo WHERE Number = '" . $Row['Number'] . "'";
	$CurrentLineValid = $db->querySingle($Query,true);
	If ($CurrentLineValid['CurrentLineValid'] == "False"){$TeamText = $TeamText . "<b>Pro Lines are Invalid</b><br />";}
		
	If ($LeagueSimulationMenu['FarmEnable'] == "True"){	
		$Query = "SELECT CurrentLineValid, Name from TeamFarmInfo WHERE Number = '" . $Row['Number'] . "'";
		$CurrentLineValid = $db->querySingle($Query,true);		
		If ($CurrentLineValid['CurrentLineValid'] == "False"){$TeamText = $TeamText . "<b>Farm Lines are Invalid</b><br />";}
	}
	
	if ($TeamText != ""){
		/* For team who had Play Game */
		
		If ($CanSendEmail == 1){
			/* Send Email */
			
			/* Format Email in HTML */
			$TeamText = "<html><head><title>" . $TeamTextTitle . "</title></head><body>" . $TeamText . "</body></html>";
			
			/* Send Email */
			mail($Row['Email'],$TeamTextTitle,$TeamText,$headers);
			
			/* Confirmation to Webpage */
			Echo "<div style=\"color:#FF0000; font-weight: bold;\">" . $SendEmailLang['EmailSend'] . $Row['GMName']  . " (" . $Row['Email'] . ")</div>\n";
			$InformationAvailable = True;
		}else{
			/* Show Webpage who will get email from system */
			Echo $SendEmailLang['Emailwillbesend'] . $Row['GMName']  . " (" . $Row['Email'] . ")<br />\n";
			$InformationAvailable = True;
			
			If ($DebugMode == True){
				If (empty($Row['Email'])){
					echo "<span style=\"color:#FF0000; font-weight: bold;\">No Valid Email Address</span><br />Title : " . $TeamTextTitle . "<br />Message : <br />" . $TeamText . "<br />";
				}else{
					echo "Title : " . $TeamTextTitle . "<br />Message : <br />" . $TeamText . "<br />";
				}
			}
		}
	}
}}
If ($InformationAvailable == False){echo "<h3 class=\"STHSCenter\">" . $SendEmailLang['NoInformation'] . "</h3>";}
}?>
<br />
<form id="SendEmailForm" name="frmEmail" data-sample="1" action="SendEmail.php<?php If ($lang == "fr"){echo "?Lang=fr";}?>" method="POST" data-sample-short="">
<input type="hidden" id="SubmitMail" name="SubmitMail" value="SubmitMail">
<input type="submit" class="SubmitButton" style="padding-left:20px;padding-right:20px" value="<?php echo $SendEmailLang['SendEmail']?>"<?php If ($InformationAvailable == False){echo " disabled";}?>>
</form>
<br />
<form id="DebugModeForm" name="frmDebugMode" data-sample="1" action="SendEmail.php<?php If ($lang == "fr"){echo "?Lang=fr";}?>" method="POST" data-sample-short="">
<input type="hidden" id="DebugMode" name="DebugMode" value="DebugMode">
<input type="submit" class="SubmitButton" style="padding-left:20px;padding-right:20px" value="<?php echo $SendEmailLang['DebugMode']?>"<?php If ($InformationAvailable == False){echo " disabled";}?>>
</form>
<br />
<strong>Note:</strong><br />
<em><?php echo $SendEmailLang['Note1'] . "<br />" . $SendEmailLang['Note2'];?></em>

</div>

<?php include "Footer.php";?>
