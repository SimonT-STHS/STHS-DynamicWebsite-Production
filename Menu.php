<?php
If (isset($Active) == False){$Active = 1;} /* Show Webpage Top Menu */
$FreeAgentYear = (integer)1;
If (file_exists($DatabaseFile) == false){
	$LeagueName = $DatabaseNotFound;
	$LeagueOutputOptionMenu = Null;
	$LeagueGeneralMenu = Null;
	$LeagueSimulationMenu = Null;
	$TeamProMenu = Null;
	$TeamFarmMenu = Null;
}else{
	If ($LeagueName == ""){
		$Query = "Select Name, LastTransactionOutput from LeagueGeneral";
		$LeagueGeneral = $db->querySingle($Query,true);		
		$LeagueName = $LeagueGeneral['Name'];
	}
	$Query = "Select ShowExpansionDraftLinkinTopMenu, ShowWebClientInDymanicWebsite, ShowRSSFeed, OutputCustomURL1, OutputCustomURL1Name, OutputCustomURL2, OutputCustomURL2Name from LeagueOutputOption";
	$LeagueOutputOptionMenu = $db->querySingle($Query,true);
	$Query = "Select OutputName, OutputFileFormat, EntryDraftStart, OffSeason, DatabaseCreationDate from LeagueGeneral";
	$LeagueGeneralMenu = $db->querySingle($Query,true);
	$Query = "Select FarmEnable from LeagueSimulation";
	$LeagueSimulationMenu = $db->querySingle($Query,true);	
	$Query = "Select Number, Abbre from TeamProInfo ORDER BY Name";
	$TeamProMenu = $db->query($Query);	
	$Query = "Select Number, Abbre from TeamFarmInfo ORDER BY Name";
	$TeamFarmMenu = $db->query($Query);	
	
	if ($LeagueGeneralMenu['OffSeason'] == "True"){$FreeAgentYear = 0;}
}
?>

<div class="tabsmenu standard"><ul class="tabmenu-links">
<li><a class="tabmenuhome" href="./index.php"><?php echo $LeagueName . $TopMenuLang['Home'];?></a></li>
<li id="STHSMenu-Main" class="activemenu"><a href="#tabmenu1"><?php echo $TopMenuLang['Main'];?></a></li>
<li id="STHSMenu-ProLeague"><a href="#tabmenu2"><?php echo $TopMenuLang['ProLeague'];?></a></li>
<?php If ($LeagueSimulationMenu['FarmEnable'] == "True"){echo "<li id=\"STHSMenu-FarmLeague\"><a href=\"#tabmenu3\">" . $TopMenuLang['FarmLeague'] . "</a></li>";}?>
<li id="STHSMenu-League"><a href="#tabmenu4"><?php echo $TopMenuLang['League'];?></a></li>
<li id="STHSMenu-Record"><a href="#tabmenu5"><?php echo $TopMenuLang['Records'];?></a></li>
<li id="STHSMenu-DirectLink"><a href="#tabmenu6"><?php echo $TopMenuLang['TeamsDirectLink'];?></a></li>
<li id="STHSMenu-OldWebsitePage"><a href="#tabmenu7"><?php echo $TopMenuLang['OldWebsitePage'];?></a></li>
<li id="STHSMenu-Help"><a href="#tabmenu8"><?php echo $TopMenuLang['Help'];?></a></li>
</ul><div class="tab-contentmenu">
<div class="tabmenu<?php if($Active ==1){echo " active";}?>" id="tabmenu1">
<table class="MenuSTHS"><tr>
<td><a href="<?php echo $LeagueGeneralMenu['OutputName'] . ".stc";?>"><?php echo $TopMenuLang['STHSClientLeagueFile'];?></a></td>
<td><a href="TodayGames.php"><?php echo $TopMenuLang['TodaysGames'];?></a></td>
<td><a href="Transaction.php?SinceLast"><?php echo $TopMenuLang['TodaysTransactions'];?></a></td>
<td><a href="Search.php"><?php echo $TopMenuLang['Search'];?></a></td>
<td><a href="NewsManagement.php"><?php echo $TopMenuLang['LeagueNewsManagement'];?></a></td>
<?php if ($LeagueOutputOptionMenu['ShowWebClientInDymanicWebsite'] == "True"){echo "<td><a href=\"WebClientIndex.php\">" . $TopMenuLang['WebClient'] . "</a></td>";}
If ($LeagueOutputOptionMenu['OutputCustomURL1'] != "" and $LeagueOutputOptionMenu['OutputCustomURL1Name'] != ""){echo "<td><a href=\"" . $LeagueOutputOptionMenu['OutputCustomURL1'] . "\">" . $LeagueOutputOptionMenu['OutputCustomURL1Name'] . "</a></td>\n";}
If ($LeagueOutputOptionMenu['OutputCustomURL2'] != "" and $LeagueOutputOptionMenu['OutputCustomURL2Name'] != ""){echo "<td><a href=\"" . $LeagueOutputOptionMenu['OutputCustomURL2'] . "\">" . $LeagueOutputOptionMenu['OutputCustomURL2Name'] . "</a></td>\n";}
?>
<td class="STHSW1"></td></tr></table></div>
<div class="tabmenu<?php if($Active ==2){echo " active";}?>" id="tabmenu2">
<table class="MenuSTHS"><tr>
<td><a href="Standing.php"><?php echo $TopMenuLang['Standing'];?></a></td>
<td><a href="Schedule.php"><?php echo $TopMenuLang['Schedule'];?></a></td>
<td><a href="PlayersStat.php?Order=P&MinGP&Max=50"><?php echo $TopMenuLang['PlayersLeader'];?></a></td>
<td><a href="GoaliesStat.php?Order=P&MinGP&Max=10"><?php echo $TopMenuLang['GoaliesLeader'];?></a></td>
<td><a href="IndividualLeaders.php"><?php echo $TopMenuLang['IndividualLeaders'];?></a></td>
<td><a href="PlayersStat.php"><?php echo $TopMenuLang['AllPlayersStats'];?></a></td>
<td><a href="GoaliesStat.php"><?php echo $TopMenuLang['AllGoaliesStats'];?></a></td>
<td><a href="TeamsStat.php"><?php echo $TopMenuLang['TeamsStats'];?></a></td>
<td><a href="PowerRanking.php"><?php echo $TopMenuLang['PowerRanking'];?></a></td>
<td class="STHSW1"></td></tr></table></div>
<?php If ($LeagueSimulationMenu['FarmEnable'] == "True"){
		if($Active ==3){echo "<div class=\"tabmenu active\" id=\"tabmenu3\">";}else{echo "<div class=\"tabmenu\" id=\"tabmenu3\">";}
	}else{
		echo "<div class=\"tabmenu\" id=\"tabmenu3\" style=\"display:none;\">";}
?>
<table class="MenuSTHS"><tr>
<td><a href="Standing.php?Farm"><?php echo $TopMenuLang['Standing'];?></a></td>
<td><a href="Schedule.php?Farm"><?php echo $TopMenuLang['Schedule'];?></a></td>
<td><a href="PlayersStat.php?Farm&MinGP&Order=P&Max=50"><?php echo $TopMenuLang['PlayersLeader'];?></a></td>
<td><a href="GoaliesStat.php?Farm&MinGP&Order=P&Max=10"><?php echo $TopMenuLang['GoaliesLeader'];?></a></td>
<td><a href="IndividualLeaders.php?Farm"><?php echo $TopMenuLang['IndividualLeaders'];?></a></td>
<td><a href="PlayersStat.php?Farm"><?php echo $TopMenuLang['AllPlayersStats'];?></a></td>
<td><a href="GoaliesStat.php?Farm"><?php echo $TopMenuLang['AllGoaliesStats'];?></a></td>
<td><a href="TeamsStat.php?Farm"><?php echo $TopMenuLang['TeamsStats'];?></a></td>
<td><a href="PowerRanking.php?Farm"><?php echo $TopMenuLang['PowerRanking'];?></a></td>
<td class="STHSW1"></td></tr></table></div>
<div class="tabmenu<?php if($Active ==4){echo " active";}?>" id="tabmenu4">
<table class="MenuSTHS"><tr>
<?php if ($LeagueGeneralMenu['EntryDraftStart'] == "True" AND $LeagueGeneralMenu['OffSeason'] == "True"){echo "<td><a href=\"EntryDraft.php\">" . $TopMenuLang['EntryDraft'] . "</a></td>";}?>
<td><a href="Coaches.php"><?php echo $TopMenuLang['Coaches'];?></a></td>
<td><a href="Transaction.php"><?php echo $TopMenuLang['Transactions'];?></a></td>
<td><a href="Waivers.php"><?php echo $TopMenuLang['Waivers'];?></a></td>
<td><span class="MenuSTHSSpan"><?php echo $TopMenuLang['Unassigned'];?>: <a href="PlayersRoster.php?Team=0&Type=0" style="padding-right:0px;padding-left:0px;"><?php echo $TopMenuLang['Players'];?></a> / <a href="GoaliesRoster.php?Team=0&Type=0" style="padding-left:0px"><?php echo $TopMenuLang['Goalies'];?></a></span></td>
<td><span class="MenuSTHSSpan"><?php echo $TopMenuLang['FreeAgents'];?>: <a href="PlayersRoster.php?Type=0&FreeAgent=<?php echo $FreeAgentYear;?>" style="padding-right:0px;padding-left:0px;"><?php echo $TopMenuLang['Players'];?></a> / <a href="GoaliesRoster.php?Type=0&FreeAgent=<?php echo $FreeAgentYear;?>" style="padding-left:0px"><?php echo $TopMenuLang['Goalies'];?></a></span></td>
<?php if ($LeagueOutputOptionMenu['ShowExpansionDraftLinkinTopMenu'] == "True"){echo "<td><span class=\"MenuSTHSSpan\">" . $TopMenuLang['ExpansionDraft'] . ": <a href=\"PlayersRoster.php?Expansion\" style=\"padding-right:0px;padding-left:0px;\">" . $TopMenuLang['Players'] . "</a> / <a href=\"GoaliesRoster.php?Expansion\" style=\"padding-left:0px\">" . $TopMenuLang['Goalies'] . "</a></span></td>";}?>
<td><a href="TeamsAndGMInfo.php"><?php echo $TopMenuLang['Team/GM'];?></a></td>
<td><a href="Transaction.php?TradeHistory"><?php echo $TopMenuLang['TradeHistory'];?></a></td>
<td><a href="Prospects.php"><?php echo $TopMenuLang['Prospects'];?></a></td>
<?php if ($LeagueOutputOptionMenu['ShowRSSFeed'] == "True"){echo "<td><a href=\"RSSFeed.xml\">" . $TopMenuLang['RSSFeed'] ."</a></td>";}?>
<td class="STHSW1"></td></tr></table></div>
<div class="tabmenu<?php if($Active ==5){echo " active";}?>" id="tabmenu5">
<table class="MenuSTHS"><tr>
<td><a href="LeagueRecords.php"><?php echo $TopMenuLang['LeagueRecords'];?></a></td>
<td><a href="TeamsRecords.php"><?php echo $TopMenuLang['TeamRecords'];?></a></td>
<td class="STHSW1"></td></tr></table></div>
<div class="tabmenu<?php if($Active ==6){echo " active";}?>" id="tabmenu6">
<?php
/* Pro */
echo "<table class=\"MenuSTHS\"><tr>";
$LoopCount =0;
if (empty($TeamProMenu) == false){while ($Row = $TeamProMenu ->fetchArray()) {
	echo "<td><a href=\"ProTeam.php?Team=" . $Row['Number'] . "\">" . $Row['Abbre'] . "</a></td>\n"; 
	$LoopCount +=1;
	If ($LoopCount % 18 == 0){echo "</tr><tr>\n";}
}}
If ($LoopCount >= 18){
	echo "<td colspan=\"" . (18 - ($LoopCount % 18)) . "\" class=\"STHSW1\"></td></tr></table>\n";
}else{
	echo "</tr></table>\n";
}

If ($LeagueSimulationMenu['FarmEnable'] == "True"){
	/* Farm */
	echo "<table class=\"MenuSTHS\"><tr>";
	$LoopCount =0;
	if (empty($TeamFarmMenu) == false){while ($Row = $TeamFarmMenu ->fetchArray()) {
		echo "<td><a href=\"FarmTeam.php?Team=" . $Row['Number'] . "\">" . $Row['Abbre'] . "</a></td>\n"; 
		$LoopCount +=1;
		If ($LoopCount % 18 == 0){echo "</tr><tr>\n";}
	}}
	If ($LoopCount >= 18){
		echo "<td colspan=\"" . (18 - ($LoopCount % 18)) . "\" class=\"STHSW1\"></td></tr></table>\n";
	}else{
		echo "</tr></table>\n";
	}
}
?>
</div>

<div class="tabmenu<?php if($Active ==7){echo " active";}?>" id="tabmenu7">
<?php
If (file_exists("STHSLegacy.dat") == True){
echo "<table class=\"MenuSTHS\"><tr>";
$HTMLFiles = file("STHSLegacy.dat", FILE_IGNORE_NEW_LINES);
$LoopCount =0;
foreach($HTMLFiles As $File){
	$Data = explode(",",$File);
	echo "<td><a href=\"" . $Data[0] . "\">" . $Data[1] ."</a></td>\n";
	$LoopCount +=1;
	If ($LoopCount % 7 == 0){echo "</tr><tr>\n";}
}
If ($LoopCount >= 7){
	echo "<td colspan=\"" . (7 - ($LoopCount % 7)) . "\" class=\"STHSW1\"></td></tr></table>\n";
}else{
	echo "</tr></table>\n";
}
}?></div>

<div class="tabmenu<?php if($Active ==8){echo " active";}?>" id="tabmenu8">
<table class="MenuSTHS"><tr>
<td><a href="http://sths.simont.info/DownloadLatestClient.php"><?php echo $TopMenuLang['LatestSTHSClient'];?></a></td>
<td><a href="http://sths.simont.info/<?php echo $TopMenuLang['ManualLink'];?>"><?php echo $TopMenuLang['ManualLinkTitle'];?></a></td>
<td class="STHSW1"></td></tr></table></div>

</div></div><br />

