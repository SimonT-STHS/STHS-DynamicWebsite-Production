<?php include "Header.php";
If ($lang == "fr"){include 'LanguageFR-Main.php';}else{include 'LanguageEN-Main.php';}
$Title = (string)"";
$Team1 = (integer)0;
$Team2 = (integer)0;
$Team1Player = Null;
$Team2Player = Null;
$Team1Prospect = Null;
$Team2Prospect = Null;
$Team1DraftPick = Null;
$Team2DraftPick = Null;		
$InformationMessage = (string)"";
$Team1Info = Null;	
$Team2Info = Null;	
$TradeQueryOK = (boolean)False;
If (file_exists($DatabaseFile) == false){
	Goto STHSErrorTrade;
}else{try{
	$LeagueName = (string)"";
	if(isset($_GET['Team1'])){$Team1 = filter_var($_GET['Team1'], FILTER_SANITIZE_NUMBER_INT);}
	if(isset($_GET['Team2'])){$Team2 = filter_var($_GET['Team2'], FILTER_SANITIZE_NUMBER_INT);}

	$db = new SQLite3($DatabaseFile);
	
	$Query = "Select Name, TradeDeadLine, ProScheduleTotalDay, ScheduleNextDay, PlayOffStarted, TradeDeadLinePass from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	$Title = $TradeLang['Trade'];
	
	$Query = "Select AllowTradefromWebsite from LeagueWebClient";
	$LeagueWebClient = $db->querySingle($Query,true);
	
	if($LeagueGeneral['TradeDeadLinePass'] == "True" OR $LeagueWebClient['AllowTradefromWebsite'] == "False"){
		echo "<style>#SelectTeam1, #SelectTeam2, #SubmitTrade, #TradeTeam1, #TradeTeam2, #Trade, #MainTradeDiv {display:none};</style>";
		$Team1 = (integer)0;
		$Team2 = (integer)0;
		$InformationMessage = $ThisPageNotAvailable;
	}elseif ($CookieTeamNumber == 0 OR $CookieTeamNumber > 100 ){
		echo "<style>#SelectTeam1, #SelectTeam2, #SubmitTrade, #TradeTeam1, #TradeTeam2,#Trade {display:none};</style>";
		$Team1 = (integer)0;
		$Team2 = (integer)0;		
	}elseif ($Team1 == 0 or $Team2 == 0 or $Team1 == $Team2){
		$Team1 = (integer)0;
		$Team2 = (integer)0;
		echo "<style>#Trade{display:none}</style>";
	}else{
				
		$Query = "SELECT Count(ToTeam) as CountNumber FROM Trade WHERE (ToTeam = " . $Team1 . " OR ToTeam =  " . $Team2 . ")  AND (ConfirmTo = 'False' OR ConfirmFrom ='False')";
		$Result1 = $db->querySingle($Query,true);
		
		If ($Result1['CountNumber'] == 0){
		
			$Query = "SELECT Number, Name, TeamThemeID FROM TeamProInfo Where Number = " . $Team1;
			$Team1Info =  $db->querySingle($Query,true);	
			$Query = "SELECT Number, Name, TeamThemeID FROM TeamProInfo Where Number = " . $Team2;
			$Team2Info =  $db->querySingle($Query,true);			
			
			$Query = "SELECT MainTable.* FROM (SELECT PlayerInfo.Number, PlayerInfo.Name,PlayerInfo.AvailableForTrade FROM PlayerInfo WHERE Team = " . $Team1 . " AND Number > 0 UNION ALL SELECT (GoalerInfo.Number + 10000), GoalerInfo.Name, GoalerInfo.AvailableForTrade FROM GoalerInfo WHERE Team = " . $Team1 . " AND Number > 0) AS MainTable WHERE NOT EXISTS (SELECT 1 FROM Trade WHERE Trade.Player = MainTable.Number) ORDER BY MainTable.Name ASC";
			$Team1Player = $db->query($Query);
			$Query = "SELECT MainTable.* FROM (SELECT PlayerInfo.Number, PlayerInfo.Name,PlayerInfo.AvailableForTrade FROM PlayerInfo WHERE Team = " . $Team2 . " AND Number > 0 UNION ALL SELECT (GoalerInfo.Number + 10000), GoalerInfo.Name, GoalerInfo.AvailableForTrade FROM GoalerInfo WHERE Team = " . $Team2 . " AND Number > 0) AS MainTable WHERE NOT EXISTS (SELECT 1 FROM Trade WHERE Trade.Player = MainTable.Number) ORDER BY MainTable.Name ASC";
			$Team2Player = $db->query($Query);	
			
			$Query = "SELECT Prospects.* FROM Prospects WHERE NOT EXISTS (SELECT 1 FROM Trade WHERE Trade.Prospect = Prospects.Number) AND TeamNumber = " . $Team1 . " ORDER By Name ASC";
			$Team1Prospect = $db->query($Query);
			$Query = "SELECT Prospects.* FROM Prospects WHERE NOT EXISTS (SELECT 1 FROM Trade WHERE Trade.Prospect = Prospects.Number) AND TeamNumber = " . $Team2 . " ORDER By Name ASC";
			$Team2Prospect = $db->query($Query);		
			
			/* Look at Condition Trade in the Future*/
			$Query = "SELECT * FROM DraftPick WHERE NOT EXISTS (SELECT 1 FROM Trade WHERE Trade.DraftPick = DraftPick.InternalNumber AND (FromTeam= " . $Team1 . " OR ToTeam = " . $Team1 . ")) AND NOT EXISTS (SELECT 1 FROM Trade WHERE (Trade.DraftPick -10000) = DraftPick.InternalNumber AND (FromTeam = " . $Team1 . " OR ToTeam = " . $Team1 . ")) AND ConditionalTrade = '' AND TeamNumber = " . $Team1 . " ORDER BY Year, Round, FromTeamAbbre";
			$Team1DraftPick = $db->query($Query);
			$Team1DraftPickCon = $db->query($Query);
			$Query = "SELECT * FROM DraftPick WHERE NOT EXISTS (SELECT 1 FROM Trade WHERE Trade.DraftPick = DraftPick.InternalNumber AND (FromTeam= " . $Team2 . " OR ToTeam = " . $Team2 . ")) AND NOT EXISTS (SELECT 1 FROM Trade WHERE (Trade.DraftPick -10000) = DraftPick.InternalNumber AND (FromTeam = " . $Team2 . " OR ToTeam = " . $Team2 . ")) AND ConditionalTrade = '' AND TeamNumber = " . $Team2 . " ORDER BY Year, Round, FromTeamAbbre";
			$Team2DraftPick = $db->query($Query);
			$Team2DraftPickCon = $db->query($Query);
		}else{
			echo "<style>#Trade{display:none}</style>";
			$InformationMessage = $TradeLang['PendingTrade'];
			$Team1 =0;
			$Team2 = 0;
		}
	}

	echo "<title>" . $LeagueName . " - " . $TradeLang['Trade']  . "</title>";
	$TradeQueryOK = True;
} catch (Exception $e) {
STHSErrorTrade:
	$LeagueName = $DatabaseNotFound;
	$LeagueOutputOption = Null;
	echo "<title>" . $DatabaseNotFound . "</title>";
	$Title = $DatabaseNotFound;
	echo "<style>#Trade{display:none}</style>";
}}?>
</head><body>
<?php include "Menu.php";
if ($InformationMessage != ""){echo "<div class=\"STHSDivInformationMessage\">" . $InformationMessage . "<br /><br /></div>";}?>
<div id="MainTradeDiv" style="width:99%;margin:auto;">
<?php echo "<h1>" . $Title . "</h1>";?>
<form id="Trade" name="Trade" method="post" action="TradeConfirm.php<?php If ($lang == "fr" ){echo "?Lang=fr";}?>">
	<input type="hidden" id="Team1" name="Team1" value="<?php echo $Team1;?>">
	<input type="hidden" id="Team2" name="Team2" value="<?php echo $Team2;?>">
	<input type="hidden" id="Confirm" name="Confirm" value="NO">
	<table class="STHSTableFullW">
	<tr>
		<td class="STHSPHPTradeTeamName"><?php if($Team1Info != Null){If ($Team1Info['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Team1Info['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPTradeTeamImage \" />";}echo $Team1Info['Name'];}?></td>
		<td class="STHSPHPTradeTeamName"><?php if($Team2Info != Null){If ($Team2Info['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Team2Info['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPTradeTeamImage \" />";}echo $Team2Info['Name'];}?></td>
	</tr>
	
	
	<tr><td colspan="2" class="STHSPHPTradeType"><hr /><?php echo $TradeLang['Players']?></td></tr>
	<tr>
	<td><select id="Team1Player" name="Team1Player[]"  multiple="multiple">
	<?php
	if (empty($Team1Player) == false){while ($Row = $Team1Player ->fetchArray()) { 
		echo "<option value=\"" . $Row['Number'] . "\">" . $Row['Name'] . "</option>";
	}}?>
	</select></td>
	<td><select id="Team2Player" name="Team2Player[]" multiple="multiple">
	<?php
	if (empty($Team2Player) == false){while ($Row = $Team2Player ->fetchArray()) { 
		echo "<option value=\"" . $Row['Number'] . "\">" . $Row['Name'] . "</option>";
	}}?>
	</select></td>
	</tr>
	
	<tr><td colspan="2" class="STHSPHPTradeType"><hr /><?php echo $TradeLang['Prospects']?></td></tr>
	<tr>
	<td><select id="Team1Prospect" name="Team1Prospect[]"  multiple="multiple">
	<?php
	if (empty($Team1Prospect) == false){while ($Row = $Team1Prospect ->fetchArray()) { 
		echo "<option value=\"" . $Row['Number'] . "\">" . $Row['Name'] . "</option>";
	}}?>
	</select></td>
	<td><select id="Team2Prospect" name="Team2Prospect[]" multiple="multiple">
	<?php
	if (empty($Team2Prospect) == false){while ($Row = $Team2Prospect ->fetchArray()) { 
		echo "<option value=\"" . $Row['Number'] . "\">" . $Row['Name'] . "</option>";
	}}?>
	</select></td>
	</tr>
	
	<tr><td colspan="2" class="STHSPHPTradeType"><hr /><?php echo $TradeLang['DraftPicks']?></td></tr>
	<tr>
	<td><select id="Team1DraftPick" name="Team1DraftPick[]"  multiple="multiple">
	<?php
	if (empty($Team1DraftPick) == false){while ($Row = $Team1DraftPick ->fetchArray()) { 
		echo "<option value=\"" . $Row['InternalNumber'] . "\">Y:" . $Row['Year'] . "-RND:" . $Row['Round'] . "-" . $Row['FromTeamAbbre'] . "</option>";
	}}?>
	</select></td>
	<td><select id="Team2DraftPick" name="Team2DraftPick[]" multiple="multiple">
	<?php
	if (empty($Team2DraftPick) == false){while ($Row = $Team2DraftPick ->fetchArray()) { 
		echo "<option value=\"" . $Row['InternalNumber'] . "\">Y:" . $Row['Year'] . "-RND:" . $Row['Round'] . "-" . $Row['FromTeamAbbre'] . "</option>";
	}}?>
	</select></td>
	</tr>
	
	<tr><td colspan="2" class="STHSPHPTradeType"><hr /><?php echo $TradeLang['DraftPicksCon']?></td></tr>
	<tr>
	<td><select id="Team1DraftPickCon" name="Team1DraftPickCon[]"  multiple="multiple">
	<?php
	if (empty($Team1DraftPickCon) == false){while ($Row = $Team1DraftPickCon ->fetchArray()) { 
		echo "<option value=\"" . $Row['InternalNumber'] . "\">Y:" . $Row['Year'] . "-RND:" . $Row['Round'] . "-" . $Row['FromTeamAbbre'] . "</option>";
	}}?>
	</select></td>
	<td><select id="Team2DraftPickCon" name="Team2DraftPickCon[]" multiple="multiple">
	<?php
	if (empty($Team2DraftPickCon) == false){while ($Row = $Team2DraftPickCon ->fetchArray()) { 
		echo "<option value=\"" . $Row['InternalNumber'] . "\">Y:" . $Row['Year'] . "-RND:" . $Row['Round'] . "-" . $Row['FromTeamAbbre'] . "</option>";
	}}?>
	</select></td>
	</tr>	
	
	<tr><td colspan="2" class="STHSPHPTradeType"><hr /><?php echo $TradeLang['Money']?></td></tr>
	<tr>
	<td class="STHSPHPTradeType"><input type="number" name="Team1Money" size="20" value="0"></td>
	<td class="STHSPHPTradeType"><input type="number" name="Team2Money" size="20" value="0"></td>
	</tr>
	
	<tr><td colspan="2" class="STHSPHPTradeType"><hr /><?php echo $TradeLang['SalaryCapY1']?></td></tr>
	<tr>
	<td class="STHSPHPTradeType"><input type="number" name="Team1SalaryCapY1" size="20" value="0"></td>
	<td class="STHSPHPTradeType"><input type="number" name="Team2SalaryCapY1" size="20" value="0"></td>
	</tr>
	
	<tr><td colspan="2" class="STHSPHPTradeType"><hr /><?php echo $TradeLang['SalaryCapY2']?></td></tr>
	<tr>
	<td class="STHSPHPTradeType"><input type="number" name="Team1SalaryCapY2" size="20" value="0"></td>
	<td class="STHSPHPTradeType"><input type="number" name="Team2SalaryCapY2" size="20" value="0"></td>
	</tr>	
	
	<tr><td colspan="2" class="STHSPHPTradeType"><hr /><?php echo $TradeLang['MessageWhy']?></td></tr>
	<tr>
	<td colspan="2" class="STHSPHPTradeType"><textarea name="MessageWhy" rows="4" cols="100"></textarea>
	</tr>	
	
	<tr>
      <td colspan="2" class="STHSPHPTradeType"><input class="SubmitButton" type="submit" name="Submit" value="<?php echo $TradeLang['Submit'];?>" /></td>
    </tr>
	</table>
</form>
<br />


<?php
If ($TradeQueryOK == True){
	If ($Team1 == 0 or $Team2 == 0 or $Team1 == $Team2){
		echo "<div class=\"STHSCenter\">";
		If ($LeagueGeneral['TradeDeadLinePass'] == "True"){echo "<div class=\"STHSDivInformationMessage\">" . $TradeLang['TradeDeadline'] . "<br /><br /></div>";}
		echo "<form action=\"Trade.php\" id=\"Team\" name=\"Team\"  method=\"get\">";
		If ($lang == "fr"){echo "<input type=\"hidden\" name=\"Lang\" value=\"fr\">";}
		echo "<table class=\"STHSTableFullW\"><tr>";
		echo "<th id=\"TradeTeam1\" class=\"STHSPHPTradeType STHSW250\">" . $TradeLang['Team1'] . "</th><th id=\"TradeTeam2\" class=\"STHSPHPTradeType STHSW250\">" . $TradeLang['Team2'] . "</th></tr><tr>";
		echo "<td><select disabled ID=\"SelectTeam1\" name=\"Team1\" class=\"STHSW250\">";
		If ($CookieTeamNumber > 0 AND $CookieTeamNumber <= 100){
			$Query = "SELECT Number, Name FROM TeamProInfo WHERE Number = " . $CookieTeamNumber;
			$TeamName = $db->querySingle($Query,true);
			echo "<option selected=\"selected\" value=\"" . $TeamName ['Number'] . "\">" . $TeamName ['Name'] . "</option>"; 
		}else{
			echo "<option selected value=\"\"></option>";
		}
		echo "</select></td><td>";
		
		echo "<select ID=\"SelectTeam2\" name=\"Team2\" class=\"STHSW250\"><option selected value=\"\"></option>";
		$Query = "SELECT Number, Name FROM TeamProInfo Order By Name";
		$TeamName = $db->query($Query);	
		if (empty($TeamName) == false){while ($Row = $TeamName ->fetchArray()) {
			If ($Row['Number'] != $CookieTeamNumber){echo "<option value=\"" . $Row['Number'] . "\">" . $Row['Name'] . "</option>";}
		}}
		echo "</select></td></tr>";
		If ($LeagueWebClient['AllowTradefromWebsite'] == "True"){
			If ($CookieTeamNumber > 0 AND  $CookieTeamNumber <= 100){
				echo "<tr><td colspan=\"2\" class=\"STHSPHPTradeType\"><br /><input id=\"SubmitTrade\" class=\"SubmitButton\" type=\"submit\" value=\"" . $TradeLang['CreateOffer'] . "\"></td></tr>";
				echo "<tr><td colspan=\"2\" class=\"STHSPHPTradeType\"><a href=\"TradeOtherTeam.php\">" . $TradeLang['ConfirmTradeAlreadyEnter'] . "</a></td></tr>";
			}
			echo "<tr><td colspan=\"2\" class=\"STHSPHPTradeType \"><a href=\"TradeView.php\">" . $TradeLang['ViewConfirmTrade'] . "</a></td></tr>";
			echo "<tr><td colspan=\"2\" class=\"STHSPHPTradeType \"><a href=\"TradePending.php\">" . $TradeLang['ViewPendingTrade'] . "</a></td></tr>";
		}
		echo "</table></form></div>";
	}else{
		echo "<script type=\"text/javascript\">";
		echo "$('#Team1Player').multiSelect();";
		echo "$('#Team2Player').multiSelect();";
		echo "$('#Team1Prospect').multiSelect();";
		echo "$('#Team2Prospect').multiSelect();";	
		echo "$('#Team1DraftPick').multiSelect();";
		echo "$('#Team2DraftPick').multiSelect();";		
		echo "$('#Team1DraftPickCon').multiSelect();";
		echo "$('#Team2DraftPickCon').multiSelect();";			
		echo "</script>";
	}
}
?>

</div>

<?php include "Footer.php";?>
