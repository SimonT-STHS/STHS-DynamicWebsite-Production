<?php
$Cookie_Name = (string)"STHS-STHSDemo";
$CookieTeamNumberKey = (string)"8Khs4ntr+1+9fcVym8zlyWzZR60W3FaNB28F1WIQtcsT1YuMGMgsxC5hqbtWzaht7hG3VLHPnDDYKvFNtcPedw==";

$DatabaseFile = (string)"LHSM-STHS.db";
$CareerStatDatabaseFile = (string)"LHSM-STHSCareerStat.db";

$DatabaseFile = (string)"D:\WWW\V4Output\STHSDemo-STHS.db";
$CareerStatDatabaseFile = (string)"D:\WWW\V4Output\STHSDemo-STHSCareerStat.db";

$NewsDatabaseFile = (string)"D:\WWW\V4Output\STHSDemo-STHSNews.db";
$LangOverwrite = (boolean)FALSE;

$lang = (string)"en"; /* The $lang option must be either "en" or "fr" */
if(isset($_GET['Lang'])){$lang  = filter_var($_GET['Lang'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH || FILTER_FLAG_NO_ENCODE_QUOTES || FILTER_FLAG_STRIP_BACKTICK);$LangOverwrite=TRUE;}  /* Allow Users Language Overwrite */
If ($lang == "fr"){include 'LanguageFR.php';}else{include 'LanguageEN.php';}
include "Cookie.php"; ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><head>
<script src="STHSMain.js"></script>
<meta name="author" content="Simon Tremblay, sths.simont.info" />
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="Decription" content="Simon Tremblay - STHS - Version : 3.Debug - <?php echo $DatabaseFile . " - " . $CareerStatDatabaseFile; ?>" />
<link href="STHSMain.css" rel="stylesheet" type="text/css" />
<?php If (file_exists("STHSMain-CSSOverwrite.css") == True){echo "<link href=\"STHSMain-CSSOverwrite.css\" rel=\"stylesheet\" type=\"text/css\" />";}?>
