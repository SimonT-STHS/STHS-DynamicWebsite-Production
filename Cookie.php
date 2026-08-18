<?php
$CookieTeamNumber = (int)0;
$CookieTeamName = (string)"";
$CookieTeamGM = (string)"";
$CookieTeamWebsiteThemeID = (int)-1;
$CookieTeamWebsiteLang = (string)"";
$LoginLink = (string)"";
If (isset($Cookie_Name) == False){$Cookie_Name = (string)"";}

if (isset($_POST["Logoff"]) OR isset($_GET['Logoff'])) {
RemoveCookie:
	$CookieArray = [
		'expires'  => time() - 3600, // past timestamp
		'path'     => '/',
		'domain' => $_SERVER['HTTP_HOST'],
		'secure'   => isset($_SERVER['HTTPS']),
		'httponly' => true,
		'samesite' => 'Strict'
	];

	setcookie($Cookie_Name, '', $CookieArray);
	unset($_COOKIE[$Cookie_Name]);
	$CookieRemove = True;
	$CookieTeamNumber = 0;
	$CookieTeamName = 
	$CookieTeamWebsiteThemeID = -1;
	$CookieTeamWebsiteLang = "";	
}elseif(isset($_COOKIE[$Cookie_Name])) {
    $encryption_key = base64_decode($CookieTeamNumberKey);
    list($encrypted_data, $iv) = explode('::', base64_decode($_COOKIE[$Cookie_Name]), 2);
    $CookieArray = unserialize(openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, $iv));
	if(isset($CookieArray['TeamNumber'])){$CookieTeamNumber = $CookieArray['TeamNumber'];}else{$CookieTeamNumber = 0;}
	if(isset($CookieArray['TeamName'])){$CookieTeamName = $CookieArray['TeamName'];}else{$CookieTeamNumber = "";}
	if(isset($CookieArray['TeamGM'])){$CookieTeamGM = $CookieArray['TeamGM'];}else{$CookieTeamGM = "";}
	if(isset($CookieArray['TeamWebsiteThemeID'])){$CookieTeamWebsiteThemeID = $CookieArray['TeamWebsiteThemeID'];}else{$CookieTeamWebsiteThemeID = -1;}
	if(isset($CookieArray['TeamWebsiteLang'])){$CookieTeamWebsiteLang = $CookieArray['TeamWebsiteLang'];}else{$CookieTeamWebsiteLang = "en";}
	unset($CookieArray);
	$CurrentLink = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	if (strpos($_SERVER['REQUEST_URI'],'?') !== false) {
		$LoginLink = $CurrentLink . "&Logoff";
	}else{
		$LoginLink = $CurrentLink . "?Logoff";
	}
	If ($CookieTeamNumber == 0){Goto RemoveCookie;} //Remove Cookie if cookie provived doesn't match.
}
?>