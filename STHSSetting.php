<?php
$DatabaseFile = (string)"D:\WWW\V4Output\STHSDemo-STHS.db";
$CareerStatDatabaseFile = (string)"D:\WWW\V4Output\STHSDemo-STHSCareerStat.db";

$DatabaseFile = (string)"LPHS-STH S.db";
$CareerStatDatabaseFile = (string)"LPHS-STHSCa reerStat.db";

$DatabaseFile = (string)"D:\WWW\V4Output\STHSDemo-STHS.db";
$CareerStatDatabaseFile = (string)"D:\WWW\V4Output\STHSDemo-STHSCareerStat.db";

$NewsDatabaseFile = (string)"D:\WWW\V4Output\STHSDemo-STHSNews.db";
$Cookie_Name = (string)"STHS-STHSDemo";
$CookieTeamNumberKey = (string)"8Khs4ntr+1+9fcVym8zlyWzZR60W3FaNB28F1WIQtcsT1YuMGMgsxC5hqbtWzaht7hG3VLHPnDDYKvFNtcPedw==";
$LeagueOwner = (string)"Simon Tremblay";
$MetaContent = (string)"STHS - Version : 3/Demo - STHSDemo-STHS.db - STHSDemo-STHSCareerStat.db";
$WebClientHeadCode = "<link href=\"STHSMain.css\" rel=\"stylesheet\" type=\"text/css\" />";
If (file_exists("STHSMain-CSSOverwrite.css") == true){$WebClientHeadCode = $WebClientHeadCode . "<link href=\"STHSMain-CSSOverwrite.css\" rel=\"stylesheet\" type=\"text/css\" />";}
$DoNotRequiredLoginDynamicWebsite = (boolean)FALSE;
$LangOverwrite = (boolean)FALSE;
$lang = (string)"en"; /* The $lang option must be either "en" or "fr" */
if(isset($_GET['Lang'])){$lang  = filter_var($_GET['Lang'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH);$LangOverwrite=TRUE;}  /* Allow Users Language Overwrite */
If ($lang == "fr"){include 'LanguageFR.php';}else{include 'LanguageEN.php';}
require_once "Cookie.php";
?>
