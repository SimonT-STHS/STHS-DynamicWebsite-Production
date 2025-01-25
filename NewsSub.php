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
	If ($row['TeamNumber'] > 0 AND $row['TeamNumber'] <= 100){echo " (";If ($row['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSIndex_TheNewsTeamImage\">";}echo $row['Name'] . ") ";}
	echo $IndexLang['On'] . " " . $Date->format('l jS F Y / g:ia ')  . "</strong><br>";
	echo "<div class=\"ck-content\">" . $row['Message'] . "</div>\n"; /* The \n is for a new line in the HTML Code */
	
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
			If ($ReplyRow['TeamNumber'] > 0){echo " (";If ($ReplyRow['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $ReplyRow['TeamThemeID'] .".png\" alt=\"\" class=\"STHSIndex_TheNewsTeamImage\">";}echo $ReplyRow['Name'] . ") ";}
			echo "</span> <span class=\"STHSIndex_NewsReplyTime\">" . $IndexLang['On'] . " " . $Date->format('jS F / g:ia ') . "</span> : " . $ReplyRow['Message'] . "</td></tr>";			
		}}
		echo "<tr><td><a href=\"NewsEditor.php?ReplyNews=" . $row['Number'] . "\">" . $IndexLang['Comment'] . "</a><hr /></td></tr>";
		echo "</tbody></table>";
	
	}else{
		/* No Reply, print link to create the first reply */
		echo "<a href=\"NewsEditor.php?ReplyNews=" . $row['Number'] . "\">" . $IndexLang['Comment'] . "</a><hr />\n";	
	}
}

echo "<style>.ck-content {& .image {display: table;clear: both;text-align: center;margin: 0.9em auto;min-width: 50px;& img {display: block;margin: 0 auto;max-width: 100%;min-width: 100%;height: auto;}}& .image-inline {display: inline-flex;max-width: 100%;align-items: flex-start;& picture {display: flex;}& picture, & img {flex-grow: 1;flex-shrink: 1;max-width: 100%;}}}.ck-content img.image_resized {height: auto;}.ck-content .image.image_resized {max-width: 100%;display: block;box-sizing: border-box;& img {width: 100%;}& > figcaption {display: block;}}.ck.ck-editor__editable {& td, & th {& .image-inline.image_resized img {max-width: 100%;}}}.ck-content {& .image {&.image-style-block-align-left, &.image-style-block-align-right {max-width: calc(100% - var(--ck-image-style-spacing));}&.image-style-align-left, &.image-style-align-right {clear: none;}&.image-style-side {float: right;margin-left: var(--ck-image-style-spacing);max-width: 50%;}&.image-style-align-left {float: left;margin-right: var(--ck-image-style-spacing);}&.image-style-align-right {float: right;margin-left: var(--ck-image-style-spacing);}&.image-style-block-align-right {margin-right: 0;margin-left: auto;}&.image-style-block-align-left {margin-left: 0;margin-right: auto;}}& .image-style-align-center {margin-left: auto;margin-right: auto;}& .image-style-align-left {float: left;margin-right: var(--ck-image-style-spacing);}& .image-style-align-right {float: right;margin-left: var(--ck-image-style-spacing);}& p + .image.image-style-align-left, & p + .image.image-style-align-right, & p + .image.image-style-side {margin-top: 0;}& .image-inline {&.image-style-align-left, &.image-style-align-right {margin-top: var(--ck-inline-image-style-spacing);margin-bottom: var(--ck-inline-image-style-spacing);}&.image-style-align-left {margin-right: var(--ck-inline-image-style-spacing);}&.image-style-align-right {margin-left: var(--ck-inline-image-style-spacing);}}}.ck.ck-splitbutton {&.ck-splitbutton_flatten {&:hover, &.ck-splitbutton_open {& > .ck-splitbutton__action:not(.ck-disabled), & > .ck-splitbutton__arrow:not(.ck-disabled), & > .ck-splitbutton__arrow:not(.ck-disabled):not(:hover) {background-color: var(--ck-color-button-on-background);&::after {display: none;}}}&.ck-splitbutton_open:hover {& > .ck-splitbutton__action:not(.ck-disabled), & > .ck-splitbutton__arrow:not(.ck-disabled), & > .ck-splitbutton__arrow:not(.ck-disabled):not(:hover) {background-color: var(--ck-color-button-on-hover-background);}}}}</style>";
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
}else{  If (isset($NewsDatabaseNotFound) == True){echo "<br><h3>" . $NewsDatabaseNotFound . "</h3>";}}
?>