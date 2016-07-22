<!DOCTYPE html>
<?php include "Header.php";?>
<?php
$LeagueName = (string)"";
$Active = 1; /* Show Webpage Top Menu */
$CanSendEmail = (integer)0; /* 0 = Nothing / 1 = Good Password /  2 = Bad Password */
If (file_exists($DatabaseFile) == false){
	$LeagueName = $DatabaseNotFound;
	$TodayGame = Null;
	echo "<title>" . $DatabaseNotFound . "</title>";
}else{
	$db = new SQLite3($DatabaseFile);
		
	$Query = "Select Name, LeagueWebPassword from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	
	$Query = "Select WebsiteURL, EmailServer, EmailServerReplyAddress from LeagueOutputOption";
	$LeagueOutputOption = $db->querySingle($Query,true);		
	
	/* Confirm League Password is Correct to Send Email */
	if (isset($_POST["Password"]) && !empty($_POST["Password"])) {
		$Password = filter_var($_POST["Password"], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH);
		mb_internal_encoding("UTF-8");
		$LeagueCalculateHash = strtoupper(Hash('sha512', mb_convert_encoding(($LeagueName . $Password), 'ASCII')));
		$LeagueDatabaseHash = $LeagueGeneral['LeagueWebPassword'];
		If ($LeagueCalculateHash == $LeagueDatabaseHash && $LeagueDatabaseHash != "" && $LeagueGeneral['LeagueWebPassword'] != ""){$CanSendEmail = 1;}else{$CanSendEmail = 2;}
	}
	
}
echo "<title>" . $LeagueName . $SendEmail['Title'] . "</title>";
?>
</head><body>
<?php include "Menu.php";?>
<br />


<div style="width:95%;margin:auto;">
<h1><?php echo $SendEmail['Title'];?></h1>
<br />
<?php 
$GameToday = (boolean)False;

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
	$TeamTextTitle = "Today's Game for " . $Row['Name'] . " - Create at " . $Date->format('l jS F Y \a\\t\ g:ia ');
	
	/* Query TodayGame for Game Play by this Team */
	$Query = "SELECT TodayGame.* FROM TodayGame WHERE VisitorTeamNumber = '" . $Row['Number'] . "' OR HomeTeamNumber = '" . $Row['Number'] . "'";
	$TeamTodayGame =  $db->query($Query);

	if (empty($TeamTodayGame) == false){while ($TeamRow = $TeamTodayGame ->fetchArray()) {
		/* Loop Result and Build Text String */
		$TeamText = $TeamText . "Game " . $TeamRow['GameNumber'] . "<br />" . $TeamRow['VisitorTeam'] . " : <strong>" . $TeamRow['VisitorTeamScore'] . "</strong> vs " . $TeamRow['HomeTeam'] . " : <strong>" . $TeamRow['HomeTeamScore'] . "</strong><br /><a href=\"" . $LeagueOutputOption['WebsiteURL'] . "/" . $TeamRow['Link'] . "\">Link to Boxscore</a><br /><br />";		
	}}
	
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
			echo "Email : " . $Row['Email'] . "<br />Title : " . $TeamTextTitle . "<br />Message : <br />" . $TeamText . "<br />";*/ 
		}else{
			/* Show Webpage who will get email from system */
			Echo $SendEmail['Emailwillbesend'] . $Row['GMName']  . " (" . $Row['Email'] . ")<br />\n";
			$GameToday = True;
		}
	}
}}
If ($GameToday == False){echo "<h3 class=\"STHSCenter\">" . $TodayGamesLang['NoGameToday'] . "</h3>";}
?>
<br />
<form id="SendEmailForm" data-sample="1" action="SendEmail.php<?php If ($lang == "fr"){echo "?Lang=fr";}?>" method="post" data-sample-short="">
<strong><?php echo $SendEmail['Password'];?></strong><input type="password" name="Password" size="20" value="" required><br /><br />
<input type="submit" class="submitBtn" style="padding-left:20px;padding-right:20px" value="<?php echo $SendEmail['SendEmail']?>"<?php If ($GameToday == False){echo " disabled";}?>>
</form>

<script type="text/javascript">

$(function(){
 $(".submitBtn").click(function () {
   $(".submitBtn").attr("disabled", true);
   $('#SendEmailForm').submit();
 });
});

</script>
<br />
<strong>Note:</strong><br />
<em><?php echo $SendEmail['Note1'] . "<br />" . $SendEmail['Note2'];?></em>

</div>

<?php include "Footer.php";?>
