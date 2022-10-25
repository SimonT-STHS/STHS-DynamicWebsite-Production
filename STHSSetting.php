<?php $PerformanceMonitorStart = microtime(true);
$DatabaseFile = (string)"";
$CareerStatDatabaseFile = (string)"";
$NewsDatabaseFile = (string)"";
$GameHTMLDatabaseFile = (string)"";
$GameJSONDatabaseFile = (string)"";
$LegacyHTMLDatabaseFile = (string)"";
$AllStarDatabaseFile = (string)"";
$Cookie_Name = (string)"";
$CookieTeamNumberKey = (string)"";
$LeagueOwner = (string)"";
$MetaContent = (string)"";
$WebClientHeadCode = (string)"";
$DoNotRequiredLoginDynamicWebsite = (boolean)FALSE;
$LangOverwrite = (boolean)FALSE;
$lang = (string)"en"; /* The $lang option must be either "en" or "fr" */
$LangOverwrite = (boolean)FALSE;
$WebClientHeadCode = "<link href=\"STHSMain.css\" rel=\"stylesheet\" type=\"text/css\" />";
If (file_exists("STHSMain-CSSOverwrite.css") == true){$WebClientHeadCode = $WebClientHeadCode . "<link href=\"STHSMain-CSSOverwrite.css\" rel=\"stylesheet\" type=\"text/css\" />";}

If (file_exists("D:\WWW\V4Output\STHSSetting.ini") == True){
	$dbSTHSOptions = new SQLite3("D:\WWW\V4Output\STHSSetting.ini");
	$Query = "Select * FROM STHSOptions";
	$STHSOptions = $dbSTHSOptions->querySingle($Query,true);
	if (isset($STHSOptions)){
		$DatabaseFile = "D:\\WWW\\V4Output\\" . $STHSOptions['DatabaseFile'];
		$CareerStatDatabaseFile = "D:\\WWW\\V4Output\\" . $STHSOptions['CareerStatDatabaseFile'];
		$NewsDatabaseFile = "D:\\WWW\\V4Output\\" . $STHSOptions['NewsDatabaseFile'];
		$GameHTMLDatabaseFile = "D:\\WWW\\V4Output\\" . $STHSOptions['GameHTMLDatabaseFile'];
		$GameJSONDatabaseFile = "D:\\WWW\\V4Output\\" . $STHSOptions['GameJSONDatabaseFile'];
		$LegacyHTMLDatabaseFile = "D:\\WWW\\V4Output\\" . $STHSOptions['LegacyHTMLDatabaseFile'];
		$AllStarDatabaseFile = "D:\\WWW\\V4Output\\" . $STHSOptions['AllStarDatabaseFile'];
		$Cookie_Name =  $STHSOptions['Cookie_Name'];
		$CookieTeamNumberKey =  $_SERVER['SERVER_NAME'] . $STHSOptions['CookieTeamNumberKey'];
		$LeagueOwner =  $STHSOptions['LeagueOwner'];
		$MetaContent =  $STHSOptions['MetaContent'];
		If ($STHSOptions['DoNotRequiredLoginDynamicWebsite'] == "True"){$DoNotRequiredLoginDynamicWebsite = True;}
		$lang = $STHSOptions['Lang']; 
	}
}
// $DatabaseFile = (string)"ProAM-STHS.bin";
// $CareerStatDatabaseFile = (string)"LEHS1-STHSCareerStat.bin";

if(isset($_GET['Lang'])){$lang  = filter_var($_GET['Lang'], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH);$LangOverwrite=TRUE;}  /* Allow Users Language Overwrite */
If ($lang == "fr"){include 'LanguageFR.php';}else{include 'LanguageEN.php';}
require_once "Cookie.php";
?>