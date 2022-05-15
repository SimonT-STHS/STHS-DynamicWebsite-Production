<?php include "Header.php";?>
<?php
$LeagueName = (string)"";
$CanSendEmail = (integer)0; /* 0 = Nothing / 1 = Good Password /  2 = Bad Password */
If (file_exists($DatabaseFile) == false){
	$LeagueName = $DatabaseNotFound;
	$TodayGame = Null;
	echo "<title>" . $DatabaseNotFound . "</title>";
	$LeagueOutputOption = Null;
}else{
	$db = new SQLite3($DatabaseFile);
		
	$Query = "Select Name, LeagueWebPassword from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	
	$Query = "Select WebsiteURL, EmailServer, EmailServerReplyAddress from LeagueOutputOption";
	$LeagueOutputOption = $db->querySingle($Query,true);

	$Query = "Select FarmEnable from LeagueSimulation";
	$LeagueSimulationMenu = $db->querySingle($Query,true);		
	
	/* Confirm League Password is Correct to Send Email */
	if (isset($_POST["SubmitMail"])) {
		If ($CookieTeamNumber == 102){$CanSendEmail = 1;}else{$CanSendEmail = 2;}
	}

}
echo "<title>" . $LeagueName . $SendEmail['Title'] . "</title>";
If ($CookieTeamNumber != 102){
	echo "<style>#MainDIV {display : none;}</style>";
}
?>
</head><body>
<?php include "Menu.php";?>
<br />

<?php If ($CookieTeamNumber != 102){echo "<div style=\"color:#FF0000; font-weight: bold;padding:1px 1px 1px 5px;text-align:center;\">" . $NoUserLogin . "<br /><br /></div>";}?>
<div id="MainDIV" style="width:95%;margin:auto;">
<h1><?php echo $SendEmail['Title'];?></h1>
<br />
<?php

$InformationAvailable = (boolean)False;
If ($CookieTeamNumber == 102){

/* Show Incorrect Password is Needed */
if ($CanSendEmail == 2){echo "<div style=\"color:#FF0000; font-weight: bold;padding:1px 1px 1px 5px;text-align:center;\">" . $SendEmail['IncorrectPassword'] . "<br /><br /></div>";}

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
If ($LeagueOutputOption['EmailServerReplyAddress'] != ""){
	$headers .= 'From: ' . $LeagueName . ' <' . $LeagueOutputOption['EmailServerReplyAddress'] . '>' . "\r\n";
}else{
	$headers .= 'From: ' . $LeagueName . ' <noreply@noreply.com>' . "\r\n";
}
If ($LeagueOutputOption['EmailServer'] != ""){ini_set('SMTP',$LeagueOutputOption['EmailServer']);}

/* Query Team Where Email is found in Database */
If (file_exists($DatabaseFile) == true){
	$Query = "Select Number, Name, GMName, Email from TeamProInfo WHERE Email <> ''";
	$Team = $db->query($Query);
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
		$TeamText = $TeamText . "Game " . $TeamRow['GameNumber'] . "<br />" . $TeamRow['VisitorTeam'] . " : <strong>" . $TeamRow['VisitorTeamScore'] . "</strong> vs " . $TeamRow['HomeTeam'] . " : <strong>" . $TeamRow['HomeTeamScore'] . "</strong><br /><a href=\"" . $LeagueOutputOption['WebsiteURL'] . "/" . $TeamRow['Link'] . "\">" . $SendEmail['LinktoBoxscore'] . "</a><br /><br />";		
	}}
	
	$Query = "SELECT CurrentLineValid from TeamProInfo WHERE Number = '" . $Row['Number'] . "'";
	$CurrentLineValid = $db->querySingle($Query,true);		
	If ($CurrentLineValid['CurrentLineValid'] == "False"){$TeamText = $TeamText . "Pro Lines are Invalid<br />";}
		
	If ($LeagueSimulationMenu['FarmEnable'] == "True"){	
		$Query = "SELECT CurrentLineValid from TeamFarmInfo WHERE Number = '" . $Row['Number'] . "'";
		$CurrentLineValid = $db->querySingle($Query,true);		
		If ($CurrentLineValid['CurrentLineValid'] == "False"){$TeamText = $TeamText . "Farm Lines are Invalid<br />";}
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
			Echo "<div style=\"color:#FF0000; font-weight: bold;\">" . $SendEmail['EmailSend'] . $Row['GMName']  . " (" . $Row['Email'] . ")</div>\n";
			
			/* Test Code 
			"Email : " . $Row['Email'] . "<br />Title : " . $TeamTextTitle . "<br />Message : <br />" . $TeamText . "<br />"; */
			$InformationAvailable = True;
		}else{
			/* Show Webpage who will get email from system */
			Echo $SendEmail['Emailwillbesend'] . $Row['GMName']  . " (" . $Row['Email'] . ")<br />\n";
			$InformationAvailable = True;
		}
	}
}}
If ($InformationAvailable == False){echo "<h3 class=\"STHSCenter\">" . $SendEmail['NoInformation'] . "</h3>";}
}?>
<br />
<form id="SendEmailForm" name="frmEmail" data-sample="1" action="SendEmail.php<?php If ($lang == "fr"){echo "?Lang=fr";}?>" method="POST" data-sample-short="">
<input type="hidden" id="SubmitMail" name="SubmitMail" value="SubmitMail">
<input type="submit" class="SubmitButton" style="padding-left:20px;padding-right:20px" value="<?php echo $SendEmail['SendEmail']?>"<?php If ($InformationAvailable == False){echo " disabled";}?>>
</form>

<script>

$(function(){
 $(".SubmitButton").click(function () {
   $(".SubmitButton").attr("disabled", true);
   $('#SendEmailForm').submit();
 });
});

</script>
<br />
<strong>Note:</strong><br />
<em><?php echo $SendEmail['Note1'] . "<br />" . $SendEmail['Note2'];?></em>

</div>

<?php include "Footer.php";?>
