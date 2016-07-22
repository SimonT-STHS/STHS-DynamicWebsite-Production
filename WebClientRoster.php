<?php
	require_once("STHSSetting.php");
	$lang = "en"; 
	require_once("LanguageEN.php");
	$LeagueName = (string) "";
	$Active = 1; /* Show Webpage Top Menu */

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
	$t = (isset($_REQUEST["TeamID"])) ? $_REQUEST["TeamID"] : 0;

	// Make a default header 
	// 5 Paramaters. PageID, database, teamid, League = Pro/Farm, $headcode (custom headercode can be added. DEFAULT "")
	api_layout_header("rostereditor",$db,$t,false,$WebClientHeadCode);

	include "Menu.php";
	
	// Display the roster editor page using API.
	// use 4 paramaters Database, TeamID, showTeamDropdown (DEFAULT true/false), showH1Tag (DEFAULT true/false)   
	api_pageinfo_editor_roster($db,$t);

	// Close the db connection
	$db->close();

	// Display the default footer.
	include "Footer.php";
	
?>