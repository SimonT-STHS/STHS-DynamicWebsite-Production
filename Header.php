<?php $PerformanceMonitorStart = microtime(true); require_once "STHSSetting.php"; ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><head>
<meta name="author" content="Simon Tremblay, sths.simont.info">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php 
echo "<meta name=\"Decription\" content=\"" . $LeagueOwner . " - " . $MetaContent . "\">\n";
echo "<link href=\"" . $CSSJSCDNPath . "STHSMain.css\" rel=\"stylesheet\" type=\"text/css\">";
If (file_exists("ThemeFunction.php") == true){
include "ThemeFunction.php";
If ($CookieTeamWebsiteThemeID >= 2000 AND $CookieTeamWebsiteThemeID <= 2999){
	GetLeagueCustomTheme($CookieTeamWebsiteThemeID,$CSSJSCDNPath,$NewsDatabaseFile);
}elseif($CookieTeamWebsiteThemeID >= 1 AND $CookieTeamWebsiteThemeID <= 1999){
	GetThemeFunction($CookieTeamWebsiteThemeID,$CSSJSCDNPath);
}else{
	GetThemeFunction($DefaultTheme,$CSSJSCDNPath);
}}
If($STHSIntegratedHosting == True){
	echo "<script src=\"https://www.sths.ca/CDN/STHSMain.js\"></script>\n";
	echo "<meta name=\"robots\" content=\"noindex\">\n";
	echo "<link rel=\"icon\" type=\"image/ico\" href=\"https://www.sths.ca/CDN/images/STHSIco.ico\">\n";
}else{
	echo "<script src=\"STHSMain.js\"></script>\n";
	If (file_exists("STHSMain-CSSOverwrite.css") == True){echo "<link  href=\"" . $CSSJSCDNPath . "STHSMain-CSSOverwrite.css\" rel=\"stylesheet\" type=\"text/css\">\n";}
}	
?>