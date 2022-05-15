<?php
require_once "STHSSetting.php";
$MenuFreeAgentYear = (integer)1;
$MenuTeamTeamID = (integer)0;
If (file_exists($DatabaseFile) == false){
	$LeagueName = $DatabaseNotFound;
	$LeagueOutputOptionMenu = Null;
	$LeagueGeneralMenu = Null;
	$LeagueSimulationMenu = Null;
	$TeamProMenu = Null;
	$TeamProMenu1 = Null;
	$TeamProMenu2 = Null;
	$TeamFarmMenu = Null;
	$TeamFarmMenu1 = Null;
	$TeamFarmMenu2 = Null;
	echo "<h1>" . $DatabaseNotFound . "</h1>";
	echo "<style>#cssmenu{display:none;}<style>";
}else{
	$dbMenu = new SQLite3($DatabaseFile);

	$Query = "Select ShowExpansionDraftLinkinTopMenu, ShowWebClientInDymanicWebsite, ProcessDatabaseTransaction, ShowRSSFeed, OutputCustomURL1, OutputCustomURL1Name, OutputCustomURL2, OutputCustomURL2Name, SplitTodayGames from LeagueOutputOption";
	$LeagueOutputOptionMenu = $dbMenu->querySingle($Query,true);
	$Query = "Select Name, OutputName, LeagueOwner, OutputFileFormat, EntryDraftStart, OffSeason, ExpireWarningDateYear, ExpireWarningDateMonth, DatabaseCreationDate, PlayOffStarted, ProConferenceName1, ProConferenceName2, FarmConferenceName1, FarmConferenceName2 from LeagueGeneral";
	$LeagueGeneralMenu = $dbMenu->querySingle($Query,true);
	$Query = "Select FarmEnable, WaiversEnable, ProTwoConference, FarmTwoConference from LeagueSimulation";
	$LeagueSimulationMenu = $dbMenu->querySingle($Query,true);	
	
	If (isset($LeagueName ) == False){$LeagueName = $LeagueGeneralMenu['Name'];}
	If ($LeagueName == ""){$LeagueName = $LeagueGeneralMenu['Name'];}
	If (isset($LeagueOwner) == False){$LeagueOwner = $LeagueGeneralMenu['LeagueOwner'];}
	
	if ($LeagueGeneralMenu['OffSeason'] == "True"){$MenuFreeAgentYear = 0;}
	
}
If (file_exists("STHSMenuStart.php") == true){include "STHSMenuStart.php";}

If (file_exists($DatabaseFile) == True){
	If (date("Y") > $LeagueGeneralMenu['ExpireWarningDateYear']){
		echo "<div class=\"STHSPHPMenuOutOfDate\">" . $OutOfDateVersion . "</div>";
	}elseif(date("Y") == $LeagueGeneralMenu['ExpireWarningDateYear'] AND date("m") > $LeagueGeneralMenu['ExpireWarningDateMonth']){
		echo "<div class=\"STHSPHPMenuOutOfDate\">" . $OutOfDateVersion . "</div>";
	}	
		
	$Query = "Select Number, Name, Abbre, TeamThemeID from TeamProInfo Where TeamThemeID > 0 ORDER BY Name ";
	$TeamProMenu = $dbMenu->query($Query);
	echo "<div class=\"STHSPHPMenuDiv\">";
	if (empty($TeamProMenu) == false){while ($Row = $TeamProMenu ->fetchArray()) {
		If ($Row['TeamThemeID'] > 0){echo "<a href=\"ProTeam.php?Team=" . $Row['Number'] . "\"><img src=\"./images/" . $Row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPMenuDivTeamImage\" /></a>";}
	}}
	echo "</div>";
}
/* Following 3 Lines Required for Game Output Before 3.2.9 */
If (isset($CookieTeamNumber) == False){$CookieTeamNumber  = (integer)0;}
If (isset($CookieTeamName) == False){$CookieTeamName  = (string)"";}
If (isset($LoginLink) == False){$LoginLink = (string)"";}
If (isset($LeagueOwner) == False){$LeagueOwner = (string)"";}

if($CookieTeamNumber > 0 AND $CookieTeamNumber <= 100){
	$Query = "Select Number, Name, Abbre, TeamThemeID from TeamProInfo Where Number = " . $CookieTeamNumber;
	$TeamMenuCookie =  $dbMenu->querySingle($Query,true);
	$MenuTeamTeamID = $TeamMenuCookie['TeamThemeID'];
}
?>

<div id='cssmenu'>

<ul style="margin: auto;">
<li class="MenuImage"><div id="STHSImageHeader" class="MenuImageDiv"><img src="./images/sthsheader.png" width="187" height="90" alt="STHS Header" /></div></li>
<li><a href="./index.php"><?php echo $TopMenuLang['Home'];?></a></li>
<li><a href="#"><?php echo $TopMenuLang['Main'];?></a><ul>
<li><a href="<?php If (file_exists($DatabaseFile) == True){echo $LeagueGeneralMenu['OutputName'] . ".stc";}?>"><?php echo $TopMenuLang['STHSClientLeagueFile'];?></a></li>
<?php If (file_exists($DatabaseFile) == True){if ($LeagueOutputOptionMenu['SplitTodayGames'] == "True"){echo "<li><a href=\"TodayGames.php?Type=1\">" . $DynamicTitleLang['Pro'] . " " . $TopMenuLang['TodaysGames'] . "</a></li><li><a href=\"TodayGames.php?Type=2\">" . $DynamicTitleLang['Farm'] . " " . $TopMenuLang['TodaysGames'] . "</a></li>";}else{echo "<li><a href=\"TodayGames.php\">" . $TopMenuLang['TodaysGames'] . "</a></li>";}}?>
<li><a href="Transaction.php?SinceLast"><?php echo $TopMenuLang['TodaysTransactions'];?></a></li>
<li><a href="Search.php"><?php echo $TopMenuLang['Search'];?></a></li>
<?php 
If (file_exists($DatabaseFile) == True){
if($CookieTeamNumber > 0){
	echo "<li><a href=\"NewsManagement.php\">" . $TopMenuLang['LeagueNewsManagement'] . "</a></li>";
	if($CookieTeamNumber > 0){echo "<li><a href=\"Upload.php\">" . $TopMenuLang['UploadLine'] . "</a></li>";}
	if ($LeagueOutputOptionMenu['ProcessDatabaseTransaction'] == "True"){echo "<li><a href=\"Trade.php\">". $TopMenuLang['Trade'] . "</a></li>";}
	if ($LeagueOutputOptionMenu['ShowWebClientInDymanicWebsite'] == "True"){echo "<li><a href=\"WebClientIndex.php\">" . $TopMenuLang['WebClient'] . "</a></li>";}
	echo "<li><a href=\"" . $LoginLink . "\">". $IndexLang['Logout'] . "</a></li>";
}elseif($DoNotRequiredLoginDynamicWebsite == TRUE){
	echo "<li><a href=\"Upload.php\">" . $TopMenuLang['UploadLine'] . "</a></li>";
	if ($LeagueOutputOptionMenu['ShowWebClientInDymanicWebsite'] == "True"){echo "<li><a href=\"WebClientIndex.php\">" . $TopMenuLang['WebClient'] . "</a></li>";}
	echo "<li><a href=\"Login.php\">". $IndexLang['Login'] . "</a></li>";
}else{
	echo "<li><a href=\"Login.php\">". $IndexLang['Login'] . "</a></li>";
}
If ($LeagueOutputOptionMenu['OutputCustomURL1'] != "" and $LeagueOutputOptionMenu['OutputCustomURL1Name'] != ""){echo "<li><a href=\"" . $LeagueOutputOptionMenu['OutputCustomURL1'] . "\">" . $LeagueOutputOptionMenu['OutputCustomURL1Name'] . "</a></li>\n";}
If ($LeagueOutputOptionMenu['OutputCustomURL2'] != "" and $LeagueOutputOptionMenu['OutputCustomURL2Name'] != ""){echo "<li><a href=\"" . $LeagueOutputOptionMenu['OutputCustomURL2'] . "\">" . $LeagueOutputOptionMenu['OutputCustomURL2Name'] . "</a></li>\n";}
If ($CookieTeamNumber == 102){echo "<li><a href=\"SendEmail.php\">" . $TodayGamesLang['Email'] . "</a></li>";}
}?>

</ul></li>
<li><a href="#"><?php echo $TopMenuLang['TeamsDirectLink'];?></a><ul>
<?php
If (file_exists($DatabaseFile) == True){
/* Pro */
echo "<li><a href=\"#\">". $TopMenuLang['ProTeam'] , "</a><ul>\n";

If ($LeagueSimulationMenu['ProTwoConference'] == "True"){
	/* 2 Conference */
	echo "<li><a href=\"#\">". $LeagueGeneralMenu['ProConferenceName1'] , "</a><ul>\n";
	$Query = "Select Number, Name, Abbre, TeamThemeID from TeamProInfo Where Conference = '" . str_replace("'","''",$LeagueGeneralMenu['ProConferenceName1']) . "' ORDER BY Name";
	$TeamProMenu1 = $dbMenu->query($Query);	
	if (empty($TeamProMenu1) == false){while ($Row = $TeamProMenu1 ->fetchArray()) {
		echo "<li><a href=\"ProTeam.php?Team=" . $Row['Number'] . "\">";
		If ($Row['TeamThemeID'] > 0){echo "<img src=\"./images/" . $Row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPMenuTeamImage\" />";}
		echo $Row['Name'] . "</a></li>\n"; 
	}}
	echo "</ul></li>\n";

	echo "<li><a href=\"#\">". $LeagueGeneralMenu['ProConferenceName2'] , "</a><ul>\n";
	$Query = "Select Number, Name, Abbre, TeamThemeID from TeamProInfo Where Conference = '" . str_replace("'","''",$LeagueGeneralMenu['ProConferenceName2'])  . "' ORDER BY Name";
	$TeamProMenu2 = $dbMenu->query($Query);	
	if (empty($TeamProMenu2) == false){while ($Row = $TeamProMenu2 ->fetchArray()) {
		echo "<li><a href=\"ProTeam.php?Team=" . $Row['Number'] . "\">";
		If ($Row['TeamThemeID'] > 0){echo "<img src=\"./images/" . $Row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPMenuTeamImage\" />";}
		echo $Row['Name'] . "</a></li>\n";
	}}
	echo "</ul></li>\n";
}else{
	/* 1 Conference Only */
	$Query = "Select Number, Name, Abbre, TeamThemeID from TeamProInfo ORDER BY Name";
	$TeamProMenu = $dbMenu->query($Query);	
	if (empty($TeamProMenu) == false){while ($Row = $TeamProMenu ->fetchArray()) {
		echo "<li><a href=\"ProTeam.php?Team=" . $Row['Number'] . "\">";
		If ($Row['TeamThemeID'] > 0){echo "<img src=\"./images/" . $Row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPMenuTeamImage\" />";}
		echo $Row['Name'] . "</a></li>\n";
	}}
}

echo "</ul></li>\n";

If ($LeagueSimulationMenu['FarmEnable'] == "True"){
	/* Farm */
	echo "<li><a href=\"#\">". $TopMenuLang['FarmTeam'] , "</a><ul>\n";
	
	If ($LeagueSimulationMenu['FarmTwoConference'] == "True"){
		/* 2 Conference */
		echo "<li><a href=\"#\">". $LeagueGeneralMenu['FarmConferenceName1'] , "</a><ul>\n";
		$Query = "Select Number, Name, Abbre, TeamThemeID from TeamFarmInfo Where Conference = '" . $LeagueGeneralMenu['FarmConferenceName1'] . "' ORDER BY Name";
		$TeamFarmMenu1 = $dbMenu->query($Query);	
		if (empty($TeamFarmMenu1) == false){while ($Row = $TeamFarmMenu1 ->fetchArray()) {
			echo "<li><a href=\"FarmTeam.php?Team=" . $Row['Number'] . "\">";
		If ($Row['TeamThemeID'] > 0){echo "<img src=\"./images/" . $Row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPMenuTeamImage\" />";}
		echo $Row['Name'] . "</a></li>\n";
		}}
		echo "</ul></li>\n";

		echo "<li><a href=\"#\">". $LeagueGeneralMenu['FarmConferenceName2'] , "</a><ul>\n";
		$Query = "Select Number, Name, Abbre, TeamThemeID from TeamFarmInfo Where Conference = '" . $LeagueGeneralMenu['FarmConferenceName2'] . "' ORDER BY Name";
		$TeamFarmMenu2 = $dbMenu->query($Query);	
		if (empty($TeamFarmMenu2) == false){while ($Row = $TeamFarmMenu2 ->fetchArray()) {
			echo "<li><a href=\"FarmTeam.php?Team=" . $Row['Number'] . "\">";
		If ($Row['TeamThemeID'] > 0){echo "<img src=\"./images/" . $Row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPMenuTeamImage\" />";}
		echo $Row['Name'] . "</a></li>\n";
		}}
		echo "</ul></li>\n";
	}else{
		/* 1 Conference Only */
		$Query = "Select Number, Name, Abbre, TeamThemeID from TeamFarmInfo ORDER BY Name";
		$TeamFarmMenu = $dbMenu->query($Query);	
		if (empty($TeamFarmMenu) == false){while ($Row = $TeamFarmMenu ->fetchArray()) {
			echo "<li><a href=\"FarmTeam.php?Team=" . $Row['Number'] . "\">";
		If ($Row['TeamThemeID'] > 0){echo "<img src=\"./images/" . $Row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPMenuTeamImage\" />";}
		echo $Row['Name'] . "</a></li>\n";
		}}
	}
	
	echo "</ul></li>\n";
}}
?>
</ul></li>
<li class="MenuImage"><div class="MenuImageDiv"><img id="MenuProLeagueImage" src="./images/proleague.png" width="90" height="90" alt="Pro League Menu" /></div></li>
<li><a href="#" class="MenuAfterImage"><?php echo $TopMenuLang['ProLeague'];?></a><ul>
<li><a href="Standing.php"><?php echo $TopMenuLang['Standing'];?></a></li>
<li><a href="Schedule.php"><?php echo $TopMenuLang['Schedule'];?></a></li>
<li><a href="PlayersStat.php?Order=P&MinGP&Max=50"><?php echo $TopMenuLang['PlayersLeader'];?></a></li>
<li><a href="GoaliesStat.php?Order=P&MinGP&Max=10"><?php echo $TopMenuLang['GoaliesLeader'];?></a></li>
<li><a href="IndividualLeaders.php"><?php echo $TopMenuLang['IndividualLeaders'];?></a></li>
<li><a href="PlayersStat.php"><?php echo $TopMenuLang['AllPlayersStats'];?></a></li>
<li><a href="GoaliesStat.php"><?php echo $TopMenuLang['AllGoaliesStats'];?></a></li>
<li><a href="TeamsStat.php"><?php echo $TopMenuLang['TeamsStats'];?></a></li>
<li><a href="Finance.php"><?php echo $TopMenuLang['Finance'];?></a></li>
<li><a href="PowerRanking.php"><?php echo $TopMenuLang['PowerRanking'];?></a></li>
</ul></li>

<?php 
If (file_exists($DatabaseFile) == True){
If ($LeagueSimulationMenu['FarmEnable'] == "True"){
	echo "<li class=\"MenuImage\"><div class=\"MenuImageDiv\"><img id=\"MenuFarmLeagueImage\" src=\"./images/farmleague.png\" width=\"90\" height=\"90\" alt=\"Farm League Menu\" /></div></li>";
	echo "<li><a href=\"#\" class=\"MenuAfterImage\">" . $TopMenuLang['FarmLeague'] . "</a><ul>";
	echo "<li><a href=\"Standing.php?Farm\">" . $TopMenuLang['Standing'] . "</a></li>";
	echo "<li><a href=\"Schedule.php?Farm\">" . $TopMenuLang['Schedule'] . "</a></li>";
	echo "<li><a href=\"PlayersStat.php?Farm&MinGP&Order=P&Max=50\">" . $TopMenuLang['PlayersLeader'] . "</a></li>";
	echo "<li><a href=\"GoaliesStat.php?Farm&MinGP&Order=P&Max=10\">" . $TopMenuLang['GoaliesLeader'] . "</a></li>";
	echo "<li><a href=\"IndividualLeaders.php?Farm\">" . $TopMenuLang['IndividualLeaders'] . "</a></li>";
	echo "<li><a href=\"PlayersStat.php?Farm\">" . $TopMenuLang['AllPlayersStats'] . "</a></li>";
	echo "<li><a href=\"GoaliesStat.php?Farm\">" . $TopMenuLang['AllGoaliesStats'] . "</a></li>";
	echo "<li><a href=\"TeamsStat.php?Farm\">" . $TopMenuLang['TeamsStats'] . "</a></li>";
	echo "<li><a href=\"Finance.php?Farm\">" . $TopMenuLang['Finance'] . "</a></li>";
	echo "<li><a href=\"PowerRanking.php?Farm\">" . $TopMenuLang['PowerRanking'] . "</a></li>";
	echo "</ul></li>";}
}
?>
<li class="MenuImage"><div class="MenuImageDiv"><img id="MenuLeagueImage" src="./images/league.png" width="90" height="90" alt="League Menu" /></div></li>
<li><a href="#" class="MenuAfterImage"><?php echo $TopMenuLang['League'];?></a><ul>
<?php If (file_exists($DatabaseFile) == True){if ($LeagueGeneralMenu['EntryDraftStart'] == "True" AND $LeagueGeneralMenu['OffSeason'] == "True"){
	echo "<li><a href=\"EntryDraft.php\">" . $TopMenuLang['EntryDraft'] . "</a></li>";
}else{
	echo "<li><a href=\"EntryDraftProjection.php\">" . $TopMenuLang['EntryDraftProjection'] . "</a></li>";
}}?>
<li><a href="Coaches.php"><?php echo $TopMenuLang['Coaches'];?></a></li>
<li><a href="Transaction.php"><?php echo $TopMenuLang['Transactions'];?></a></li>
<?php 
If (file_exists($DatabaseFile) == True){If ($LeagueSimulationMenu['WaiversEnable'] == "True"){echo "<li><a href=\"Waivers.php\">" . $TopMenuLang['Waivers'] . "</a></li>";}}
If (file_exists($DatabaseFile) == True){if ($LeagueOutputOptionMenu['ShowExpansionDraftLinkinTopMenu'] == "True"){echo "<li><a href=\"#\">" . $TopMenuLang['ExpansionDraft'] . "</a><ul><li><a href=\"PlayersRoster.php?Expansion\">" . $TopMenuLang['Players'] . "</a></li><li><a href=\"GoaliesRoster.php?Expansion\">" . $TopMenuLang['Goalies'] . "</a></li></ul></li>";}}
If ($CookieTeamNumber > 0){echo "<li><a href=\"TeamsAndGMInfo.php\">" . $TopMenuLang['Team/GM'] . "</a></li>";}
?>
<li><a href="Transaction.php?TradeLogHistory"><?php echo $TopMenuLang['TradeHistory'];?></a></li>
<li><a href="Prospects.php"><?php echo $TopMenuLang['Prospects'];?></a></li>
<?php If (file_exists($DatabaseFile) == True){if ($LeagueOutputOptionMenu['ShowRSSFeed'] == "True"){echo "<li><a href=\"RSSFeed.xml\">" . $TopMenuLang['RSSFeed'] ."</a></li>";}}?>
	<li><a href="#"><?php echo $TopMenuLang['Unassigned'];?></a><ul>
		<li><a href="PlayersRoster.php?Team=0&Type=0"><?php echo $TopMenuLang['Players'];?></a></li>
		<li><a href="GoaliesRoster.php?Team=0&Type=0"><?php echo $TopMenuLang['Goalies'];?></a></li>
	</ul></li>
	<li><a href="#"><?php echo $TopMenuLang['AvailableForTrade'];?></a><ul>
		<li><a href="PlayersRoster.php?AvailableForTrade"><?php echo $TopMenuLang['Players'];?></a></li>
		<li><a href="GoaliesRoster.php?AvailableForTrade"><?php echo $TopMenuLang['Goalies'];?></a></li>
	</ul></li>
	<li><a href="#"><?php echo $TeamLang['InjurySuspension'];?></a><ul>
		<li><a href="PlayersRoster.php?Type=0&Injury=on"><?php echo $TopMenuLang['Players'];?></a></li>
		<li><a href="GoaliesRoster.php?Type=0&Injury=on"><?php echo $TopMenuLang['Goalies'];?></a></li>
	</ul></li>	
	<li><a href="#"><?php echo $TopMenuLang['Compare'];?></a><ul>
		<li><a href="PlayersCompare.php"><?php echo $TopMenuLang['Players'];?></a></li>
		<li><a href="GoaliesCompare.php"><?php echo $TopMenuLang['Goalies'];?></a></li>
	</ul></li>	
	<li><a href="#"><?php echo $TopMenuLang['FreeAgents'];?></a><ul>
		<li><a href="PlayersRoster.php?Type=0&FreeAgent=<?php echo $MenuFreeAgentYear . "\">" . $TopMenuLang['Players'];?></a></li>
		<li><a href="GoaliesRoster.php?Type=0&FreeAgent=<?php echo $MenuFreeAgentYear . "\">" . $TopMenuLang['Goalies'];?></a></li>
	</ul>
	<li><a href="#"><?php echo $TopMenuLang['Retire'];?></a><ul>
		<li><a href="PlayersRoster.php?Retire"><?php echo $TopMenuLang['Players'];?></a></li>
		<li><a href="GoaliesRoster.php?Retire"><?php echo $TopMenuLang['Goalies'];?></a></li>
	</ul></li>
</ul></li>


<li><a href="#"><?php If (file_exists($CareerStatDatabaseFile) == true){echo $TopMenuLang['RecordsAndCareerStat'];}else{echo $TopMenuLang['Records'];}?></a><ul>
<li><a style="width:375px;" href="LeagueRecords.php"><?php echo $TopMenuLang['LeagueRecords'];?></a></li>
<li><a style="width:375px;" href="TeamsRecords.php"><?php echo $TopMenuLang['TeamRecords'];?></a></li>
<?php 
	If (file_exists($CareerStatDatabaseFile) == true){
		echo "<li><a style=\"width:375px;\" href=\"CupWinner.php\"> " . $TopMenuLang['CupWinner'] . "</a></li>";
		echo "<li><a style=\"width:375px;\" href=\"CareerStatTeamsStat.php\"> " . $TopMenuLang['TeamCareerStat'] . "</a></li>";
		echo "<li><a style=\"width:375px;\" href=\"CareerStatPlayersStat.php\"> " . $TopMenuLang['PlayersCareerStat'] . "</a></li>";
		echo "<li><a style=\"width:375px;\" href=\"CareerStatGoaliesStat.php\"> " . $TopMenuLang['GoaliesCareerStat'] . "</a></li>";
		echo "<li><a style=\"width:375px;\" href=\"CareerStatIndividualLeaders.php\"> " . $TopMenuLang['CareerStatsIndividualLeaders'] . "</a></li>";
		echo "<li><a style=\"width:375px;\" href=\"CareerStatTeamsStat.php?Playoff=on\"> " . $TopMenuLang['TeamCareerStat'] . $TopMenuLang['Playoff'] . "</a></li>";
		echo "<li><a style=\"width:375px;\" href=\"CareerStatPlayersStat.php?Playoff=on\"> " . $TopMenuLang['PlayersCareerStat'] . $TopMenuLang['Playoff'] . "</a></li>";
		echo "<li><a style=\"width:375px;\" href=\"CareerStatGoaliesStat.php?Playoff=on\"> " . $TopMenuLang['GoaliesCareerStat'] . $TopMenuLang['Playoff'] . "</a></li>";
		echo "<li><a style=\"width:375px;\" href=\"CareerStatIndividualLeaders.php?Playoff=on\"> " . $TopMenuLang['CareerStatsIndividualLeaders'] . $TopMenuLang['Playoff'] . "</a></li>";	
		echo "<li><a style=\"width:375px;\" href=\"HistoryStanding.php\"> " . $TopMenuLang['PreviousStanding'] . "</a></li>";				
		echo "<li><a style=\"width:375px;\" href=\"Search.php#History\"> " . $TopMenuLang['SearchHistory'] . "</a></li>";	
	}
?>
</ul></li>

<li><a href="Search.php"><?php echo $TopMenuLang['Search'];?></a></li>
<?php
If (file_exists($DatabaseFile) == True){
if ($LeagueGeneralMenu['PlayOffStarted'] == "True"){
	echo "<li><a href=\"#\">" . $TopMenuLang['SeasonStat'] . "</a><ul>\n";
	echo "<li><a href=\"#\">" . $TopMenuLang['ProLeague'] . "</a><ul>\n";
	echo "<li><a  href=\"Standing.php?Season\">" . $TopMenuLang['Standing'] . "</a></li>\n";
	echo "<li><a  href=\"PlayersStat.php?Order=P&MinGP&Max=50&Season\">" .  $TopMenuLang['PlayersLeader'] . "</a></li>\n";
	echo "<li><a  href=\"GoaliesStat.php?Order=P&MinGP&Max=10&Season\">" .  $TopMenuLang['GoaliesLeader'] . "</a></li>\n";
	echo "<li><a  href=\"PlayersStat.php?Season\">" .  $TopMenuLang['AllPlayersStats'] . "</a></li>\n";
	echo "<li><a  href=\"GoaliesStat.php?Season\">" .  $TopMenuLang['AllGoaliesStats'] . "</a></li>\n";
	echo "<li><a  href=\"TeamsStat.php?Season\">" .  $TopMenuLang['TeamsStats'] . "</a></li>\n";	
	echo "</ul></li>";
    echo "<li><a href=\"#\">" .$TopMenuLang['FarmLeague'] . "</a><ul>\n";
	echo "<li><a  href=\"Standing.php?Season&Farm\">" . $TopMenuLang['Standing'] . "</a></li>\n";
	echo "<li><a  href=\"PlayersStat.php?Farm&MinGP&Order=P&Max=50&Season\">" .  $TopMenuLang['PlayersLeader'] . "</a></li>\n";
	echo "<li><a  href=\"GoaliesStat.php?Farm&MinGP&Order=P&Max=10&Season\">" .  $TopMenuLang['GoaliesLeader'] . "</a></li>\n";
	echo "<li><a  href=\"PlayersStat.php?Farm&Season\">" .  $TopMenuLang['AllPlayersStats'] . "</a></li>\n";
	echo "<li><a  href=\"GoaliesStat.php?Farm&Season\">" .  $TopMenuLang['AllGoaliesStats'] . "</a></li>\n";
	echo "<li><a  href=\"TeamsStat.php?Farm&Season\">" .  $TopMenuLang['TeamsStats'] . "</a></li>\n";	
	echo "</ul></li>\n";
	echo "</ul></li>\n";
}}	
?>
<?php
If (file_exists("STHSLegacy.dat") == True){
echo "<li><a href=\"#\">" . $TopMenuLang['OldWebsitePage'] . "</a><ul>\n";
$HTMLFiles = file("STHSLegacy.dat", FILE_IGNORE_NEW_LINES);
foreach($HTMLFiles As $File){
	$Data = explode(",",$File);
	echo "<li><a href=\"" . $Data[0] . "\">" . $Data[1] ."</a></li>\n";
}
echo "</ul></li>\n";
}?>
<li><a href='#'><?php echo $TopMenuLang['Help'];?></a><ul>
	<li><a href="http://sths.simont.info/DownloadLatestClient.php"><?php echo $TopMenuLang['LatestSTHSClient'];?></a></li>
	<li><a href="http://sths.simont.info/ManualV3_En.php#Team_Management"><?php echo $TopMenuLang['ManualLinkTitle'];?></a></li>
</ul></li>

</ul>
</div>
<div class="STHSPHP_Login">
	<div style="font-size:16px;">
	<?php
	If ($CookieTeamNumber > 0 AND $CookieTeamNumber <= 100){
		echo "<div id=\"cssmenuLogin\" style=\"display:inline-block\"><ul style=\"max-width:150px;width:100%;margin:0 auto\"><li style=\"font-size:24px;cursor:pointer;line-height:0\">&#9660;<ul style=\"max-height:450px;overflow-x:hidden;overflow-y:scroll\">\n";
		echo "<li style=\"text-align:left;display:flex\"><a href=\"ProTeam.php?Team=" . $CookieTeamNumber ."&SubMenu=1\" class=\"STHSPHPTeamHeader_TeamNameColor_" . $MenuTeamTeamID . "\" >" . $TeamLang['Roster'] . "</a></li>\n";
		echo "<li style=\"text-align:left;display:flex\"><a href=\"ProTeam.php?Team=" . $CookieTeamNumber ."&SubMenu=2\" class=\"STHSPHPTeamHeader_TeamNameColor_" . $MenuTeamTeamID . "\" >" . $TeamLang['Scoring'] . "</a></li>\n";
		echo "<li style=\"text-align:left;display:flex\"><a href=\"ProTeam.php?Team=" . $CookieTeamNumber ."&SubMenu=3\" class=\"STHSPHPTeamHeader_TeamNameColor_" . $MenuTeamTeamID . "\" >" . $TeamLang['PlayersInfo'] . "</a></li>\n";
		echo "<li style=\"text-align:left;display:flex\"><a href=\"ProTeam.php?Team=" . $CookieTeamNumber ."&SubMenu=4\" class=\"STHSPHPTeamHeader_TeamNameColor_" . $MenuTeamTeamID . "\" >" . $TeamLang['Lines'] . "</a></li>\n";
		echo "<li style=\"text-align:left;display:flex\"><a href=\"ProTeam.php?Team=" . $CookieTeamNumber ."&SubMenu=5\" class=\"STHSPHPTeamHeader_TeamNameColor_" . $MenuTeamTeamID . "\" >" . $TeamLang['TeamStats'] . "</a></li>\n";
		echo "<li style=\"text-align:left;display:flex\"><a href=\"ProTeam.php?Team=" . $CookieTeamNumber ."&SubMenu=6\" class=\"STHSPHPTeamHeader_TeamNameColor_" . $MenuTeamTeamID . "\" >" . $TeamLang['Schedule'] . "</a></li>\n";
		echo "<li style=\"text-align:left;display:flex\"><a href=\"ProTeam.php?Team=" . $CookieTeamNumber ."&SubMenu=7\" class=\"STHSPHPTeamHeader_TeamNameColor_" . $MenuTeamTeamID . "\" >" . $TeamLang['Finance'] . "</a></li>\n";
		echo "<li style=\"text-align:left;display:flex\"><a href=\"ProTeam.php?Team=" . $CookieTeamNumber ."&SubMenu=8\" class=\"STHSPHPTeamHeader_TeamNameColor_" . $MenuTeamTeamID . "\" >" . $TeamLang['Depth'] . "</a></li>\n";		
		echo "<li style=\"text-align:left;display:flex\"><a href=\"ProTeam.php?Team=" . $CookieTeamNumber ."&SubMenu=9\" class=\"STHSPHPTeamHeader_TeamNameColor_" . $MenuTeamTeamID . "\" >" . $TeamLang['History'] . "</a></li>\n";		
		echo "<li style=\"text-align:left;display:flex\"><a href=\"ProTeam.php?Team=" . $CookieTeamNumber ."&SubMenu=10\" class=\"STHSPHPTeamHeader_TeamNameColor_" . $MenuTeamTeamID . "\" >" . $TeamLang['TeamTransaction'] . "</a></li>\n";		
		If (file_exists($CareerStatDatabaseFile) == true){echo "<li style=\"text-align:left;display:flex\"><a href=\"ProTeam.php?Team=" . $CookieTeamNumber ."&SubMenu=11\" class=\"STHSPHPTeamHeader_TeamNameColor_" . $MenuTeamTeamID . "\" >" . $TeamLang['CareerTeamStat'] . "</a></li>\n";}
		echo "<li style=\"text-align:left;display:flex\"><a href=\"ProTeam.php?Team=" . $CookieTeamNumber ."&SubMenu=12\" class=\"STHSPHPTeamHeader_TeamNameColor_" . $MenuTeamTeamID . "\" >" . $TeamLang['InjurySuspension'] . "</a></li>\n";	
		echo "</ul></li></ul></div>\n";			
		echo "<a href=\"ProTeam.php?Team=" . $CookieTeamNumber ."\" class=\"STHSPHPTeamHeader_TeamNameColor_" . $MenuTeamTeamID . "\" style=\"font-size:20px;\">" . $CookieTeamName . "</a>";
		echo "<br />" . "<a class=\"STHSPHPLogoutButton\" href=\"" . $LoginLink . "\">". $IndexLang['Logout'] . "</a>";	
	}elseif($CookieTeamNumber == 101){
		echo $News['Guest'] . "<br />" . "<a class=\"STHSPHPLogoutButton\" href=\"" . $LoginLink . "\">". $IndexLang['Logout'] . "</a>";	
	}elseif($CookieTeamNumber == 102){
		echo $News['LeagueManagement'] . "<br />" . "<a class=\"STHSPHPLogoutButton\" href=\"" . $LoginLink . "\">". $IndexLang['Logout'] . "</a>";	
	}else{
		echo "<div><a class=\"STHSPHPLoginButton\" href=\"Login.php\">". $IndexLang['Login'] . "</a></div>";
	}
	?>
	</div>
</div>
<?php If (file_exists("STHSMenuEnd.php") == true){include "STHSMenuEnd.php";}?>
