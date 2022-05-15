<?php
	$lang = "en"; 
	require_once("LanguageEN.php");
	$LeagueName = Null;
	session_start();
	mb_internal_encoding("UTF-8");
	$PerformanceMonitorStart = microtime(true); 
	require_once("STHSSetting.php");
	//  Get STHS Setting $Database Value	

	require_once("WebClientAPI.php");
	// exempt is an array of api names.
	// example, if you do not need the html or layout api then add as an array item
	// $exempt = array("html","layout");
	$exempt = array();
	
	// Call the required APIs
	load_apis($exempt);
	
	// Make a connection variable to pass to API
	$db = api_sqlite_connect($DatabaseFile);
	
	// Look for a team ID in the URL, if non exists use 0

	$t = (isset($_REQUEST["TeamID"])) ? filter_var($_REQUEST["TeamID"], FILTER_SANITIZE_NUMBER_INT): 0;
	$row = array();
	if($t > 0){
		$rs = api_dbresult_teamsbyname($db,"Pro",$t);
		$row = $rs->fetchArray();
	}
	// Make a default header 
	// 5 Paramaters. PageID, database, teamid, League = Pro/Farm, $headcode (custom headercode can be added. DEFAULT "")
	api_layout_header("rostereditor",$db,$t,false,$WebClientHeadCode);
	include "Menu.php";
	
	If ($CookieTeamNumber == 102){$DoNotRequiredLoginDynamicWebsite = TRUE;} // Commish is allow to upload anything so we are using the code from the 'Do Not Required Login Dynamic Website' to achieve this goal.
	
	if($CookieTeamNumber == $t OR $DoNotRequiredLoginDynamicWebsite == TRUE){
		// Display the roster editor page using API.
		// use 3 paramaters Database, TeamID, showH1Tag (DEFAULT true/false)   
		if($t > 0){api_pageinfo_editor_roster($db,$t);}
	}else{
		echo "<div style=\"color:#FF0000; font-weight: bold;padding:1px 1px 1px 5px;text-align:center;\">" . $NoUserLogin . "<br /><br /></div>";
	}

	// Close the db connection
	$db->close();

	// Display the default footer.
	api_layout_footer();
?>