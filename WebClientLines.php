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
	if ($db != Null){
		
		$Query = "Select ShowWebClientInDymanicWebsite FROM LeagueOutputOption";
		$LeagueOutputOption = $db->querySingle($Query,true);
		
		$Query = "Select BlockAutoProLineFunctionForGM,BlockAutoFarmLineFunctionForGM FROM LeagueWebClient";
		$LeagueWebClient = $db->querySingle($Query,true);

		// Look for a team ID in the URL, if non exists use 0
		$t = (isset($_REQUEST["TeamID"])) ? filter_var($_REQUEST["TeamID"], FILTER_SANITIZE_NUMBER_INT): 0;
		$l = (isset($_REQUEST["League"])) ? filter_var($_REQUEST["League"], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH) : false;
		If (strtolower($l) <> "farm"){$l = "Pro";}else{$l = "Farm";}
		$row = array();
		if($t > 0 AND $t <= 100){
			$rs = api_dbresult_teamsbyname($db,"Pro",$t);
			$row = $rs->fetchArray();
		}
		If ($l == "Pro" AND $LeagueWebClient['BlockAutoProLineFunctionForGM'] == "True"){echo "<style>#autolines {display:none};</style>";}
		If ($l == "Farm" AND $LeagueWebClient['BlockAutoFarmLineFunctionForGM'] == "True"){echo "<style>#autolines {display:none};</style>";}
		
		// Make a default header 
		api_layout_header("lineeditor",$db,$t,$l,$WebClientHeadCode);
		include "Menu.php";

		If ($CookieTeamNumber == 102){$DoNotRequiredLoginDynamicWebsite = TRUE;} // Commish is allow to edit any Teams so we are using the code from the 'Do Not Required Login Dynamic Website' to achieve this goal.
		
		if ($LeagueOutputOption['ShowWebClientInDymanicWebsite'] == "False"){
			echo "<div class=\"STHSDivInformationMessage\">" . $ThisPageNotAvailable . "<br /><br /></div>";
		}elseif(($CookieTeamNumber == $t OR $DoNotRequiredLoginDynamicWebsite == TRUE) AND $t > 0 AND $t <= 100){
			// Display the line editor page using API.
			// use 4 paramaters Database, TeamID, $league("Pro","Farm"), showH1Tag (DEFAULT true/false)   
			if($t > 0){api_pageinfo_editor_lines($db,$t,$l);}
		}else{
			echo "<div class=\"STHSDivInformationMessage\">" . $NoUserLogin . "<br /><br /></div>";		
		}

		// Close the db connection
		$db->close();

		// Display the default footer.
		api_layout_footer();
	}
?>