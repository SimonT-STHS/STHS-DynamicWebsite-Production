<?php

Function PrintMainNews($row, $IndexLang, $dbNews, $ImagesCDNPath ){
	/* This Function Print a News */
	$UTC = new DateTimeZone("UTC");
	$ServerTimeZone = new DateTimeZone(date_default_timezone_get());
	echo "<h2>" . $row['Title'] . "</h2>";
	$Date = new DateTime($row['Time'], $UTC );
	$Date->setTimezone($ServerTimeZone);
	
	/* The following two lines publish the news */
	
	echo "<strong>" . $IndexLang['By'] . " " . $row['Owner'];
	If ($row['TeamNumber'] > 0 AND $row['TeamNumber'] <= 100){echo " (";If ($row['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSIndex_TheNewsTeamImage\" />";}echo $row['Name'] . ") ";}
	echo $IndexLang['On'] . " " . $Date->format('l jS F Y / g:ia ')  . "</strong><br />";
	echo $row['Message'] . "\n"; /* The \n is for a new line in the HTML Code */
	
	/* Get the Number of Reply */
	$NewsReplyCount = Null;
	$Query = "Select Count(Message) as CountMessage FROM LeagueNews WHERE Remove = 'False' AND AnswerNumber = " . $row['Number'] . " ORDER BY Number";
	$NewsReplyCount = $dbNews->querySingle($Query,true);
	
	If ($NewsReplyCount['CountMessage'] > 0 ){ /* If Reply are Found */

		/* Query Reply */
		$NewsReply = Null;
		$Query = "Select LeagueNews.*, TeamProInfo.TeamThemeID, TeamProInfo.Name FROM LeagueNews LEFT JOIN TeamProInfo ON LeagueNews.TeamNumber = TeamProInfo.Number WHERE Remove = 'False' AND AnswerNumber = " . $row['Number'] . " ORDER BY Number";
		$NewsReply = $dbNews->query($Query);
	
		/* Show the Number of News + Create the Link */
		echo "<a href=\"javascript:toggleDiv('News" . $row['Number'] . "');\">" . $IndexLang['Viewcomments'] . " (" .  $NewsReplyCount['CountMessage'] . ")</a>"; 

		/* Publish all the Comments in Table */
		echo "<table class=\"STHSIndex_NewsReplyTable\" id=\"News" . $row['Number'] . "\"><tbody>";
		if (empty($NewsReply) == false){
			while ($ReplyRow = $NewsReply ->fetchArray()) { 
			$Date = new DateTime($ReplyRow['Time'], $UTC );
			$Date->setTimezone($ServerTimeZone);
			echo "<tr><td><span class=\"STHSIndex_NewsReplyOwner\">" . $ReplyRow['Owner'];
			If ($ReplyRow['TeamNumber'] > 0){echo " (";If ($ReplyRow['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $ReplyRow['TeamThemeID'] .".png\" alt=\"\" class=\"STHSIndex_TheNewsTeamImage\" />";}echo $ReplyRow['Name'] . ") ";}
			echo "</span> <span class=\"STHSIndex_NewsReplyTime\">" . $IndexLang['On'] . " " . $Date->format('jS F / g:ia ') . "</span> : " . $ReplyRow['Message'] . "</td></tr>";			
		}}
		echo "<tr><td><a href=\"NewsEditor.php?ReplyNews=" . $row['Number'] . "\">" . $IndexLang['Comment'] . "</a><hr /></td></tr>";
		echo "</tbody></table>";
	
	}else{
		/* No Reply, print link to create the first reply */
		echo "<a href=\"NewsEditor.php?ReplyNews=" . $row['Number'] . "\">" . $IndexLang['Comment'] . "</a><hr />\n";	
	}
}

$NewsPublish = array(); /* Array that Contain News Publish Already Publish */
$CountNews = 0; /* Number of New Publish so we can apply the STHS option 'Number of News in Home Page' */

if (empty($LeagueNews) == false){while ($row = $LeagueNews ->fetchArray()) { /* Loop News in Reserve Order of Publish Time */
	if (in_array($row['Number'],$NewsPublish) == FALSE AND in_array($row['AnswerNumber'],$NewsPublish) == FALSE ){ /* Make sure we already didn't publish this news */
		if ($row['AnswerNumber'] == 0){
			/* This row of the Table is not answer comment so it's main news */
			PrintMainNews($row, $IndexLang, $dbNews,$ImagesCDNPath );  /* Print the News */
			
			/* Increment the Number of News Publish */
			$CountNews +=1; 
			
			/* If we publish enough news based on the the STHS option 'Number of News in Home Page', we close the loop */
			If ($CountNews >= $LeagueOutputOption['NumberofNewsinPHPHomePage']){break;} 
		}else{
			/* This is row is answer to previous news. Finding the Main News Information */
			
			$Query = "Select LeagueNews.*, TeamProInfo.TeamThemeID, TeamProInfo.Name FROM LeagueNews LEFT JOIN TeamProInfo ON LeagueNews.TeamNumber = TeamProInfo.Number WHERE Remove = 'False' AND LeagueNews.Number = " . $row['AnswerNumber'];
			$NewsTemp = $dbNews->querySingle($Query,True);
					
			/* Print the News */
			PrintMainNews($NewsTemp, $IndexLang, $dbNews,$ImagesCDNPath );  
			
			/* Increment the Number of News Publish */
			$CountNews +=1; 
			
			/* If we publish enough news based on the the STHS option 'Number of News in Home Page', we close the loop */
			If ($CountNews >= $LeagueOutputOption['NumberofNewsinPHPHomePage']){break;} 
			
			/* Add in the Array the Main News will be publish */
			array_push($NewsPublish, $row['AnswerNumber']); 
		}
	}
}
if($CountNews == 0){Echo $SearchLang ['NoNewsFound'];}
}else{  If (isset($NewsDatabaseNotFound) == True){echo "<br /><h3>" . $NewsDatabaseNotFound . "</h3>";}}
?>