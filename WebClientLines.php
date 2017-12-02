<?php
	$lang = "en"; 
	require_once("LanguageEN.php");
	$LeagueName = Null;
	session_start();
	mb_internal_encoding("UTF-8");
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
	$l = (isset($_REQUEST["League"])) ? filter_var($_REQUEST["League"], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH) : false;
	If (strtolower($l) <> "farm"){$l = "Pro";}else{$l = "Farm";}
	$row = array();
	if($t > 0){
		$rs = api_dbresult_teamsbyname($db,"Pro",$t);
		$row = $rs->fetchArray();
	}
	// Make a default header 
	api_layout_header("lineeditor",$db,$t,$l,$WebClientHeadCode);
	include "Menu.php";
	api_alpha_testing();
	api_html_form_teamid($db,$t,true);
	api_security_logout();
	api_security_authenticate($_POST,$row);
	

	if(api_security_access($row)){
		// Display the line editor page using API.
		// use 4 paramaters Database, TeamID, $league("Pro","Farm"), showH1Tag (DEFAULT true/false)   
		if($t > 0){api_pageinfo_editor_lines($db,$t,$l);}
	}else{
		api_html_login_form($row);
	}

	// Close the db connection
	$db->close();

	// Display the default footer.
	api_layout_footer();
?>