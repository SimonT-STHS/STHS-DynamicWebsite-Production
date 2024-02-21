<?php include "Header.php";
$Title = (string)"";
$Team = (integer)-1; 
$WebMaxFreeAgentOffer = (integer)0; 
$InformationMessage = (string)"";
If (file_exists($DatabaseFile) == false){
	Goto STHSErrorFreeAgentOffers;
}else{try{
	$MinimumSalary = (integer)0;
	$MaximumResult = (integer)0;
	$OrderByField = (string)"Overall";
	$OrderByFieldText = (string)"Overall";
	$OrderByInput = (string)"";	
	$LeagueName = (string)"";
	
	$PlayerNumber = (integer)0;
	$PlayerName = (string)"";
	$SalaryOffer = (integer)0;
	$DurationOffer = (integer)0;
	$BonusOffer = (integer)0;
	$ommentOffer = (string)"";	
	$CanPlayPro = (string)"False";
	$CanPlayFarm = (string)"False";
	$NoTrade = (string)"False";
	$ProSalaryinFarm  = (string)"False";

	$db = new SQLite3($DatabaseFile);
	$Query = "Select Name, RFAAge, UFAAge, OffSeason from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	$Query = "Select MaxContractDuration, PlayerMinimumSalary, PlayerMaxSalary from LeagueFinance";
	$LeagueFinance = $db->querySingle($Query,true);		
	$Query = "Select MergeRosterPlayerInfo, FreeAgentUseDateInsteadofDay, FreeAgentRealDate, UnassignedasFreeAgent from LeagueOutputOption";
	$LeagueOutputOption = $db->querySingle($Query,true);	
	$Query = "Select AllowFreeAgentSalaryRequestInSTHSClient,MinimumFreeAgentsOffer,MaxFreeAgentOffer,AllowFreeAgentOfferfromWebsite from LeagueWebClient";
	$LeagueWebClient = $db->querySingle($Query,true);	

	If ($LeagueFinance['PlayerMinimumSalary'] >= $LeagueWebClient['MinimumFreeAgentsOffer']){$MinimumSalary = $LeagueFinance['PlayerMinimumSalary'];}else{$MinimumSalary = $LeagueWebClient['MinimumFreeAgentsOffer'];}
	
	If ($CookieTeamNumber > 0 AND $CookieTeamNumber <= 100 AND $LeagueWebClient['AllowFreeAgentOfferfromWebsite'] == "True"){
		
		$Query = "SELECT WebMaxFreeAgentOffer FROM TeamProInfo WHERE Number = " . $CookieTeamNumber;
		$TeamInfo = $db->querySingle($Query,true);
		$WebMaxFreeAgentOffer = $TeamInfo['WebMaxFreeAgentOffer'];
		
		$Query = "SELECT Count(FromTeam) as CountNumber FROM FreeAgentOffers WHERE FromTeam = " . $CookieTeamNumber;
		$Result = $db->querySingle($Query,true);
		If ($Result['CountNumber'] > 0){$WebMaxFreeAgentOffer = $WebMaxFreeAgentOffer - $Result['CountNumber'];}
		
		//Confirm Erase Offer Team Match Cookie
		if(isset($_POST['Erase'])){$Team = filter_var($_POST['Erase'], FILTER_SANITIZE_NUMBER_INT);}
		If (isset($_POST['Erase']) AND $Team == $CookieTeamNumber){
			if(isset($_POST['PlayerNumber'])){$PlayerNumber = filter_var($_POST['PlayerNumber'], FILTER_SANITIZE_NUMBER_INT);} 
			if(isset($_POST['PlayerName'])){$PlayerName =  filter_var($_POST['PlayerName'], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH || FILTER_FLAG_NO_ENCODE_QUOTES || FILTER_FLAG_STRIP_BACKTICK);}

			// Delete Previous Offer if Exist
				$Query = "SELECT Count(FromTeam) as CountNumber FROM FreeAgentOffers WHERE FromTeam = " . $Team . " AND PlayerNumber = " . $PlayerNumber;
				$Result = $db->querySingle($Query,true);
				If ($Result['CountNumber'] > 0){				
					$Query = "DELETE from FreeAgentOffers WHERE FromTeam = " . $Team . " AND PlayerNumber = " . $PlayerNumber;
					try {
						$db->exec($Query);
						$WebMaxFreeAgentOffer = $WebMaxFreeAgentOffer + 1; // Raise the number because we delete previous offer who was counting.
						$InformationMessage = $PlayersLang['FreeAgentDeleteOffer'] . $PlayerName;
					} catch (Exception $e) {
						$InformationMessage = $PlayersLang['FreeAgentFailOffer'];
					}						
				}			
		}
		
		// Confirm Submit Offer Team Match Cookie
		if(isset($_POST['Offer'])){$Team = filter_var($_POST['Offer'], FILTER_SANITIZE_NUMBER_INT);}
		If (isset($_POST['Offer']) AND $Team == $CookieTeamNumber){
			If ($WebMaxFreeAgentOffer > 0){
				if(isset($_POST['PlayerNumber'])){$PlayerNumber = filter_var($_POST['PlayerNumber'], FILTER_SANITIZE_NUMBER_INT);} 
				if(isset($_POST['PlayerName'])){$PlayerName =  filter_var($_POST['PlayerName'], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH || FILTER_FLAG_NO_ENCODE_QUOTES || FILTER_FLAG_STRIP_BACKTICK);}
				if(isset($_POST['SalaryOffer'])){$SalaryOffer = filter_var($_POST['SalaryOffer'], FILTER_SANITIZE_NUMBER_INT);} 
				if(isset($_POST['DurationOffer'])){$DurationOffer = filter_var($_POST['DurationOffer'], FILTER_SANITIZE_NUMBER_INT);} 
				if(isset($_POST['BonusOffer'])){$BonusOffer = filter_var($_POST['BonusOffer'], FILTER_SANITIZE_NUMBER_INT);} 
				if(isset($_POST['CommentOffer'])){$CommentOffer = filter_var($_POST['CommentOffer'], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH || FILTER_FLAG_NO_ENCODE_QUOTES || FILTER_FLAG_STRIP_BACKTICK);}			
				if(isset($_POST['CanPlayPro'])) {$CanPlayPro = "True";}
				if(isset($_POST['CanPlayFarm'])){$CanPlayFarm = "True";}
				if(isset($_POST['ProSalaryinFarm'])){$ProSalaryinFarm = "True";}
				if(isset($_POST['NoTrade'])){$NoTrade = "True";}
				
				if ($CanPlayPro == "False" AND $CanPlayFarm = "False"){$CanPlayPro = "True";$CanPlayFarm = "True";}
				
				// Verify Offer Validy
				If ($SalaryOffer >= $MinimumSalary AND $SalaryOffer <= $LeagueFinance['PlayerMaxSalary'] ANd $DurationOffer > 0 AND $DurationOffer <= $LeagueFinance['MaxContractDuration'] And $PlayerNumber > 0 and $PlayerNumber <= 11000){
					// Delete Previous Offer if Exist
					$Query = "SELECT Count(FromTeam) as CountNumber FROM FreeAgentOffers WHERE FromTeam = " . $Team . " AND PlayerNumber = " . $PlayerNumber;
					$Result = $db->querySingle($Query,true);
					If ($Result['CountNumber'] > 0){				
						$Query = "DELETE from FreeAgentOffers WHERE FromTeam = " . $Team . " AND PlayerNumber = " . $PlayerNumber;
						try {
							$db->exec($Query);
							$WebMaxFreeAgentOffer = $WebMaxFreeAgentOffer + 1; // Raise the number because we delete previous offer who was counting.
						} catch (Exception $e) {
							$InformationMessage = $PlayersLang['FreeAgentFailOffer'];
						}						
					}

					// Save Offer
					$Query = "INSERT INTO FreeAgentOffers (FromTeam,PlayerNumber,SalaryOffer,DurationOffer,BonusOffer,CommentOffer,OfferDate,NoTradeOffer,CanPlayProOffer,CanPlayFarmOffer,ProSalaryinFarm1WayContractOffer) VALUES('" . $Team . "','" . $PlayerNumber . "','" . $SalaryOffer . "','" . $DurationOffer . "','" . $BonusOffer . "','" . str_replace("'","''",$CommentOffer) . "','" . date("Y-m-d H:i:s")  . "','" . $NoTrade . "','" . $CanPlayPro . "','" . $CanPlayFarm . "','" . $ProSalaryinFarm . "')";
					try {
						$db->exec($Query);
						$InformationMessage = $PlayersLang['FreeAgentConfirmOffer'] . $PlayerName;
						$WebMaxFreeAgentOffer = $WebMaxFreeAgentOffer - 1; // Lower the number because offer was saved correctly.
					} catch (Exception $e) {
						$InformationMessage = $PlayersLang['FreeAgentFailOffer'];
					}
				}else{
					$InformationMessage = $PlayersLang['InvalidOffer'];
				}
			}else{
				$InformationMessage = $PlayersLang['MaximumFreeAgentOfferReach'];
			}
		}
		
		$QueryPlayer = "SELECT MainTable.*, FreeAgentOffers.* FROM (SELECT * FROM PlayerInfo WHERE Retire = 'False' AND PlayerInfo.Contract = 0 AND PlayerInfo.Team > 0 AND PlayerInfo.Age >= " . $LeagueGeneral['RFAAge'];
		$QueryGoaler = "SELECT MainTable.*, FreeAgentOffers.* FROM (SELECT * FROM GoalerInfo WHERE Retire = 'False' AND GoalerInfo.Contract = 0 AND GoalerInfo.Team > 0 AND GoalerInfo.Age >= " . $LeagueGeneral['RFAAge'];
		If ($LeagueOutputOption['UnassignedasFreeAgent'] == "True"){
			$QueryPlayer = $QueryPlayer . " UNION ALL SELECT * FROM PlayerInfo WHERE Retire = 'False' AND PlayerInfo.Team = 0";
			$QueryGoaler = $QueryGoaler . " UNION ALL SELECT * FROM GoalerInfo WHERE Retire = 'False' AND GoalerInfo.Team = 0";
		}else{
			$QueryPlayer = $QueryPlayer . " AND PlayerInfo.Team > 0";
			$QueryGoaler = $QueryGoaler . " AND GoalerInfo.Team > 0";
		}	
		$QueryPlayer = $QueryPlayer . " UNION ALL SELECT * FROM PlayerInfo WHERE Retire = 'False' AND PlayerInfo.Contract = 0 AND PlayerInfo.Team = " . $CookieTeamNumber . " AND PlayerInfo.Age <= " . $LeagueGeneral['RFAAge'] .") AS MainTable LEFT JOIN FreeAgentOffers ON MainTable.Number = FreeAgentOffers.PlayerNumber AND  FreeAgentOffers.FromTeam = " . $CookieTeamNumber . "  ORDER by MainTable.Overall DESC LIMIT 500";
		$QueryGoaler = $QueryGoaler . " UNION ALL SELECT * FROM GoalerInfo WHERE Retire = 'False' AND GoalerInfo.Contract = 0 AND GoalerInfo.Team = " . $CookieTeamNumber . " AND GoalerInfo.Age <= " . $LeagueGeneral['RFAAge'] .") AS MainTable LEFT JOIN FreeAgentOffers ON MainTable.Number = (FreeAgentOffers.PlayerNumber - 10000) AND  FreeAgentOffers.FromTeam = " . $CookieTeamNumber . "  ORDER by MainTable.Overall DESC  LIMIT 500";
		$PlayerFreeAgentOffers = $db->query($QueryPlayer);
		$GoalieFreeAgentOffers = $db->query($QueryGoaler);
		
	}else{
		$PlayerFreeAgentOffers = Null;
		$GoalieFreeAgentOffers = Null;
		If ($CookieTeamNumber > 0){
			$InformationMessage = $ThisPageNotAvailable;
		}else{
			$InformationMessage = $NoUserLogin;
		}
		echo "<style>#FreeAgentMainDiv {display:none};</style>";
	}
	$Title = $Title . $DynamicTitleLang['ThisYearFreeAgents'];	
	
	echo "<title>" . $LeagueName . " - " . $Title . "</title>";

} catch (Exception $e) {
STHSErrorFreeAgentOffers:
	$LeagueName = $DatabaseNotFound;
	$PlayerFreeAgentOffers = Null;
	$GoalieFreeAgentOffers = Null;
	$LeagueOutputOption = Null;
	$MinimumSalary = (integer)0;
	echo "<title>" . $DatabaseNotFound . "</title>";
	$Title = $DatabaseNotFound;
}}?>
<style>
#tablesorter_colSelectPlayer:checked + label {background: #5797d7;  border-color: #555;}
#tablesorter_colSelectPlayer:checked ~ #tablesorter_ColumnSelectorPlayer {display: block;}
#tablesorter_colSelectGoalie:checked + label {background: #5797d7;  border-color: #555;}
#tablesorter_colSelectGoalie:checked ~ #tablesorter_ColumnSelectorGoalie {display: block;}
</style>
</head><body>
<?php include "Menu.php";?>
<script>
function validateForm(fName) {
   var x = document[fName]["SalaryOffer"].value;
   if (x==null || x=="" || x < <?php echo $MinimumSalary;?> || x > <?php echo $LeagueFinance['PlayerMaxSalary'];?> )
   {
      alert("Salary Incorrect");
      return false;
   }
   
   var x = document[fName]["DurationOffer"].value;
   if (x==null || x=="" || x < 0 || x > <?php echo $LeagueFinance['MaxContractDuration'];?> )
   {
      alert("Duration Incorrect");
      return false;
   }
 
   if (document[fName]["CanPlayPro"].checked==false && document[fName]["CanPlayFarm"].checked==false)
   {
      alert("Can Play Pro Or Can Play Farm must be Select!");
      return false;
   }  
   return true;
}
$(function() {
  $(".STHSPHPPlayerFreeAgentOffers_Table").tablesorter({
    widgets: ['columnSelector', 'stickyHeaders', 'filter', 'output'],
    widgetOptions : {
      columnSelector_container : $('#tablesorter_ColumnSelectorPlayer'),
      columnSelector_layout : '<label><input type="checkbox">{name}</label>',
      columnSelector_name  : 'title',
      columnSelector_mediaquery: true,
      columnSelector_mediaqueryName: 'Automatic',
      columnSelector_mediaqueryState: true,
      columnSelector_mediaqueryHidden: true,
      columnSelector_breakpoints : [ '40em', '65em', '70em', '78em', '94em', '99em' ],
	  filter_columnFilters: true,
      filter_placeholder: { search : '<?php echo $TableSorterLang['Search'];?>' },
	  filter_searchDelay : 500,	  
      filter_reset: '.tablesorter_Reset',	 
    }
  }); 
});
$(function() {
  $(".STHSPHPGoalieFreeAgentOffers_Table").tablesorter({
    widgets: ['columnSelector', 'stickyHeaders', 'filter', 'output'],
    widgetOptions : {
      columnSelector_container : $('#tablesorter_ColumnSelectorGoalie'),
      columnSelector_layout : '<label><input type="checkbox">{name}</label>',
      columnSelector_name  : 'title',
      columnSelector_mediaquery: true,
      columnSelector_mediaqueryName: 'Automatic',
      columnSelector_mediaqueryState: true,
      columnSelector_mediaqueryHidden: true,
      columnSelector_breakpoints : [ '40em', '65em', '70em', '78em', '94em', '99em' ],
	  filter_columnFilters: true,
      filter_placeholder: { search : '<?php echo $TableSorterLang['Search'];?>' },
	  filter_searchDelay : 500,	  
      filter_reset: '.tablesorter_Reset',	 
    }
  }); 
});
</script>
<?php if ($InformationMessage != ""){echo "<div class=\"STHSDivInformationMessage\">" . $InformationMessage . "<br /><br /></div>";}?>
<div id="FreeAgentMainDiv" style="width:99%;margin:auto;">
<?php 
If($LeagueGeneral['OffSeason'] == "True"){
	echo "<h2 class=\"STHSCenter\">You can make " . $WebMaxFreeAgentOffer . " offers in total.</h2>";
}else{
	echo "<h2 class=\"STHSCenter\">You can make " . $WebMaxFreeAgentOffer . " offers to players you do not owned.</h2>";
}
echo "<h1>" . $Title . " - " . $DynamicTitleLang['Players']  . "</h1>"; ?>

<div class="tablesorter_ColumnSelectorWrapper">
	<input id="tablesorter_colSelectPlayer" type="checkbox" class="hidden">
    <label class="tablesorter_ColumnSelectorButton" for="tablesorter_colSelectPlayer"><?php echo $TableSorterLang['ShoworHideColumn'];?></label>
    <div id="tablesorter_ColumnSelectorPlayer" class="tablesorter_ColumnSelector"></div>
	<?php include "FilterTip.php";?>
</div>

<table class="tablesorter STHSPHPPlayerFreeAgentOffers_Table"><thead><tr>
<th data-priority="critical" title="Player Name" class="STHSW140Min"><?php echo $PlayersLang['PlayerName'];?></th>
<?php if($Team >= 0){echo "<th class=\"columnSelector-false STHSW140\" data-priority=\"6\" title=\"Team Name\">" . $PlayersLang['TeamName'] . "</th>";}else{echo "<th data-priority=\"2\" title=\"Team Name\" class=\"STHSW140Min\">" . $PlayersLang['TeamName'] ."</th>";}?>
<th data-priority="4" title="Position" class="STHSW25">POS</th>
<th data-priority="4" title="Checking" class="columnSelector-false STHSW25">CK</th>
<th data-priority="4" title="Fighting" class="columnSelector-false STHSW25">FG</th>
<th data-priority="4" title="Discipline" class="columnSelector-false STHSW25">DI</th>
<th data-priority="4" title="Skating" class="columnSelector-false STHSW25">SK</th>
<th data-priority="4" title="Strength" class="columnSelector-false STHSW25">ST</th>
<th data-priority="4" title="Endurance" class="columnSelector-false STHSW25">EN</th>
<th data-priority="4" title="Durability" class="columnSelector-false STHSW25">DU</th>
<th data-priority="4" title="Puck Handling" class="columnSelector-false STHSW25">PH</th>
<th data-priority="4" title="Face Offs" class="columnSelector-false STHSW25">FO</th>
<th data-priority="4" title="Passing" class="columnSelector-false STHSW25">PA</th>
<th data-priority="4" title="Scoring" class="columnSelector-false STHSW25">SC</th>
<th data-priority="4" title="Defense" class="columnSelector-false STHSW25">DF</th>
<th data-priority="4" title="Penalty Shot" class="columnSelector-false STHSW25">PS</th>
<th data-priority="4" title="Experience" class="columnSelector-false STHSW25">EX</th>
<th data-priority="4" title="Leadership" class="columnSelector-false STHSW25">LD</th>
<th data-priority="4" title="Potential" class="columnSelector-false STHSW25">PO</th>
<th data-priority="critical" title="Overall" class="STHSW25">OV</th>
<?php if ($PlayerFreeAgentOffers != Null){
	echo "<th data-priority=\"4\" class=\"STHSW25\" title=\"Status\">" . $PlayersLang['Status'] . "</th>";
	if ($LeagueWebClient['AllowFreeAgentSalaryRequestInSTHSClient'] == "True"){echo "<th data-priority=\"4\" class=\"STHSW75\" title=\"Free Agent Salary Request\">" . $PlayersLang['SalaryRequest'] . "</th>";}
	echo "<th data-priority=\"6\" title=\"Star Power\" class=\"columnSelector-false STHSW25\">SP</th>";	
	echo "<th data-priority=\"5\" class=\"STHSW25\" title=\"Age\">" . $PlayersLang['Age'] . "</th>";
	echo "<th data-priority=\"6\" class=\"columnSelector-false STHSW25\" title=\"Contract\">" . $PlayersLang['Contract'] . "</th>";
	echo "<th data-priority=\"5\" class=\"STHSW65\" title=\"Salary Last Year\">" . $PlayersLang['SalaryLastYear'] ."</th>";
	echo "<th data-priority=\"1\" class=\"STHSW65\" title=\"Salary Offer\">" . $PlayersLang['SalaryOffer'] ."</th>";
	echo "<th data-priority=\"1\" class=\"STHSW65\" title=\"Duration Offer\">" . $PlayersLang['DurationOffer'] ."</th>";
	echo "<th data-priority=\"2\" class=\"STHSW65\" title=\"Bonus Offer\">" . $PlayersLang['BonusOffer'] ."</th>";
	echo "<th data-priority=\"2\" class=\"STHSW65\" title=\"Comment Offer\">" . $PlayersLang['CommentOffer'] ."</th>";	
	echo "<th data-priority=\"3\" class=\"STHSW65\" title=\"Can Play Pro\">" . $PlayersLang['CanPlayPro'] ."</th>";
	echo "<th data-priority=\"3\" class=\"STHSW65\" title=\"Can Play Farm\">" . $PlayersLang['CanPlayFarm'] ."</th>";
	echo "<th data-priority=\"3\" class=\"STHSW65\" title=\"NoTrade\">" . $PlayersLang['NoTrade'] ."</th>";
	echo "<th data-priority=\"3\" class=\"STHSW65\" title=\"Pro Salary in Farm - 1 Way Contract\">" . $PlayersLang['ProSalaryinFarm'] ."</th>";	
	echo "<th data-priority=\"1\" class=\"STHSW65\" title=\"Submit\">" . $PlayersLang['SubmitOffer'] ."</th>";	
	echo "<th data-priority=\"4\" class=\"STHSW65\" title=\"Erase\">" . $PlayersLang['EraseOffer'] ."</th>";	
}?>
</tr></thead><tbody>
<?php
if (empty($PlayerFreeAgentOffers) == false){while ($Row = $PlayerFreeAgentOffers ->fetchArray()) {
	$strTemp = (string)$Row['Name'];
	If ($Row['Rookie']== "True"){ $strTemp = $strTemp . " (R)";}
	echo "<tr><td><a href=\"PlayerReport.php?Player=" . $Row['Number'] . "\">" . $strTemp . "</a></td>";
	echo "<td>";
	If ($Row['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPPlayersRosterTeamImage\" />";}			
	echo $Row['ProTeamName'] . "</td>";	
	echo "<td>";
	$Position = (string)"";
	if ($Row['PosC']== "True"){if ($Position == ""){$Position = "C";}else{$Position = $Position . " - C";}}
	if ($Row['PosLW']== "True"){if ($Position == ""){$Position = "LW";}else{$Position = $Position . " - LW";}}
	if ($Row['PosRW']== "True"){if ($Position == ""){$Position = "RW";}else{$Position = $Position . " - RW";}}
	if ($Row['PosD']== "True"){if ($Position == ""){$Position = "D";}else{$Position = $Position . " - D";}}	
	echo $Position . "</td>";		
	echo "<td>" . $Row['CK'] . "</td>";
	echo "<td>" . $Row['FG'] . "</td>";
	echo "<td>" . $Row['DI'] . "</td>";
	echo "<td>" . $Row['SK'] . "</td>";
	echo "<td>" . $Row['ST'] . "</td>";
	echo "<td>" . $Row['EN'] . "</td>";
	echo "<td>" . $Row['DU'] . "</td>";
	echo "<td>" . $Row['PH'] . "</td>";
	echo "<td>" . $Row['FO'] . "</td>";
	echo "<td>" . $Row['PA'] . "</td>";
	echo "<td>" . $Row['SC'] . "</td>";
	echo "<td>" . $Row['DF'] . "</td>";
	echo "<td>" . $Row['PS'] . "</td>";
	echo "<td>" . $Row['EX'] . "</td>";
	echo "<td>" . $Row['LD'] . "</td>";
	echo "<td>" . $Row['PO'] . "</td>";
	echo "<td>" . $Row['Overall'] . "</td>"; 

	if ($Row['Age'] >= $LeagueGeneral['UFAAge']){echo "<td>" . $PlayersLang['UFAAbbre'] . "</td>";}elseif($Row['Age'] >= $LeagueGeneral['RFAAge']){echo "<td>" . $PlayersLang['RFAAbbre'] . "</td>";}else{echo "<td>" . $PlayersLang['ELCAbbre'] . "</td>";}
	if ($LeagueWebClient['AllowFreeAgentSalaryRequestInSTHSClient'] == "True"){echo "<td>" . number_format($Row['FreeAgentSalaryRequest'],0) . "$ / " . $Row['FreeAgentContratRequest'] . "</td>";}		

	echo "<td>" . $Row['StarPower'] . "</td>";
	echo "<td>" . $Row['Age'] . "</td>";
	echo "<td>" . $Row['Contract'] . "</td>";
	echo "<td>" . number_format($Row['LastYearSalary'],0) . "$</td>";	
	echo "<td class=\"STHSCenter\"><form name=\"" . $Row['Number'] . "\" action=\"FreeAgentOffers.php\"";If ($lang == "fr"){echo "?Lang=fr";} echo " method=\"post\" onsubmit=\"return validateForm(" . $Row['Number'] .")\" >";
	echo "<input type=\"number\" name=\"SalaryOffer\" value=\"";If(isset($Row['SalaryOffer'])){Echo $Row['SalaryOffer'];}echo "\" min=\"" . $MinimumSalary . "\" max=\"" . $LeagueFinance['PlayerMaxSalary'] . "\" required></td>";
	echo "<td class=\"STHSCenter\"><input type=\"number\" name=\"DurationOffer\" value=\"";If(isset($Row['DurationOffer'])){Echo $Row['DurationOffer'];}echo "\" min=\"1\" max=\"" . $LeagueFinance['MaxContractDuration'] . "\" required></td>";
	echo "<td class=\"STHSCenter\"><input type=\"number\" name=\"BonusOffer\" value=\"";If(isset($Row['BonusOffer'])){Echo $Row['BonusOffer'];}echo "\"></td>";
	echo "<td class=\"STHSCenter\"><input type=\"text\" name=\"CommentOffer\" value=\"";If(isset($Row['CommentOffer'])){Echo $Row['CommentOffer'];}echo "\" size=\"40\"></td>";	
	echo "<td class=\"STHSCenter\"><input type=\"checkbox\" name=\"CanPlayPro\" ";If(isset($Row['CanPlayProOffer'])){If($Row['CanPlayProOffer'] == "True"){echo "checked";}}elseif ($Row['CanPlayPro'] == "True"){echo "checked";} echo "></td>";
	echo "<td class=\"STHSCenter\"><input type=\"checkbox\" name=\"CanPlayFarm\" ";If(isset($Row['CanPlayFarmOffer'])){If($Row['CanPlayFarmOffer'] == "True"){echo "checked";}}elseif ($Row['CanPlayFarm'] == "True"){echo "checked";} echo "></td>";
	echo "<td class=\"STHSCenter\"><input type=\"checkbox\" name=\"NoTrade\" ";If(isset($Row['NoTradeOffer'])){If($Row['NoTradeOffer'] == "True"){echo "checked";}}elseif ($Row['NoTrade'] == "True"){echo "checked";} echo "></td>";
	echo "<td class=\"STHSCenter\"><input type=\"checkbox\" name=\"ProSalaryinFarm\" ";If(isset($Row['ProSalaryinFarm1WayContractOffer'])){If($Row['ProSalaryinFarm1WayContractOffer'] == "True"){echo "checked";}}elseif ($Row['ProSalaryinFarm'] == "True"){echo "checked";} echo "></td>";
	echo "<td class=\"STHSCenter\"><input type=\"submit\" class=\"SubmitButtonSmall\" value=\"" .  $PlayersLang['Submit'] . "\">";
	echo "<input type=\"hidden\" name=\"Offer\" value=\"" . $CookieTeamNumber . "\">";
	echo "<input type=\"hidden\" name=\"PlayerName\" value=\"" . $Row['Name'] . "\">";
	echo "<input type=\"hidden\" name=\"PlayerNumber\" value=\"" . $Row['Number'] . "\"></form></td>";
	echo "<td class=\"STHSCenter\"><form action=\"FreeAgentOffers.php\"";If ($lang == "fr"){echo "?Lang=fr";} echo " method=\"post\"><input type=\"submit\" class=\"SubmitButtonSmall\" value=\"" .  $PlayersLang['Erase'] . "\">";
	echo "<input type=\"hidden\" name=\"Erase\" value=\"" . $CookieTeamNumber . "\">";
	echo "<input type=\"hidden\" name=\"PlayerName\" value=\"" . $Row['Name'] . "\">";
	echo "<input type=\"hidden\" name=\"PlayerNumber\" value=\"" . $Row['Number'] . "\"></form></td>";	
	echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
}}
?>
</tbody></table>

<hr /><br />

<?php echo "<h1>" . $Title . " - " . $DynamicTitleLang['Goalies']  . "</h1>"; ?>

<div class="tablesorter_ColumnSelectorWrapper">
	<input id="tablesorter_colSelectGoalie" type="checkbox" class="hidden">
    <label class="tablesorter_ColumnSelectorButton" for="tablesorter_colSelectGoalie"><?php echo $TableSorterLang['ShoworHideColumn'];?></label>
    <div id="tablesorter_ColumnSelectorGoalie" class="tablesorter_ColumnSelector"></div>
	<?php include "FilterTip.php";?>
</div>

<table class="tablesorter STHSPHPGoalieFreeAgentOffers_Table"><thead><tr>
<th data-priority="critical" title="Goalie Name" class="STHSW140Min"><?php echo $PlayersLang['GoalieName'];?></th>
<?php if($Team >= 0){echo "<th class=\"columnSelector-false STHSW140Min\" data-priority=\"6\" title=\"Team Name\">" . $PlayersLang['TeamName'] . "</th>";}else{echo "<th data-priority=\"2\" title=\"Team Name\" class=\"STHSW140Min\">" . $PlayersLang['TeamName'] ."</th>";}?>
<th data-priority="4" title="Skating" class="columnSelector-false STHSW25">SK</th>
<th data-priority="4" title="Durability" class="columnSelector-false STHSW25">DU</th>
<th data-priority="4" title="Endurance" class="columnSelector-false STHSW25">EN</th>
<th data-priority="4" title="Size" class="columnSelector-false STHSW25">SZ</th>
<th data-priority="4" title="Agility" class="columnSelector-false STHSW25">AG</th>
<th data-priority="4" title="Rebound Control" class="columnSelector-false STHSW25">RB</th>
<th data-priority="4" title="Style Control" class="columnSelector-false STHSW25">SC</th>
<th data-priority="4" title="Hand Speed" class="columnSelector-false STHSW25">HS</th>
<th data-priority="4" title="Reaction Time" class="columnSelector-false STHSW25">RT</th>
<th data-priority="4" title="Puck Handling" class="columnSelector-false STHSW25">PH</th>
<th data-priority="4" title="Penalty Shot" class="columnSelector-false STHSW25">PS</th>
<th data-priority="4" title="Experience" class="columnSelector-false STHSW25">EX</th>
<th data-priority="4" title="Leadership" class="columnSelector-false STHSW25">LD</th>
<th data-priority="4" title="Potential" class="columnSelector-false STHSW25">PO</th>
<th data-priority="critical" title="Overall" class="STHSW25">OV</th>
<?php if ($GoalieFreeAgentOffers != Null){
	
	echo "<th data-priority=\"4\" class=\"STHSW25\" title=\"Status\">" . $PlayersLang['Status'] . "</th>";
	if ($LeagueWebClient['AllowFreeAgentSalaryRequestInSTHSClient'] == "True"){echo "<th data-priority=\"4\" class=\"STHSW75\" title=\"Free Agent Salary Request\">" . $PlayersLang['SalaryRequest'] . "</th>";}		
	
	echo "<th data-priority=\"6\" title=\"Star Power\" class=\"columnSelector-false STHSW25\">SP</th>";	
	echo "<th data-priority=\"5\" class=\"STHSW25\" title=\"Age\">" . $PlayersLang['Age'] . "</th>";
	echo "<th data-priority=\"6\" class=\"columnSelector-false STHSW25\" title=\"Contract\">" . $PlayersLang['Contract'] . "</th>";
	echo "<th data-priority=\"5\" class=\"STHSW65\" title=\"Salary Last Year\">" . $PlayersLang['SalaryLastYear'] ."</th>";
	echo "<th data-priority=\"1\" class=\"STHSW100\" title=\"Salary Offer\">" . $PlayersLang['SalaryOffer'] ."</th>";
	echo "<th data-priority=\"1\" class=\"STHSW45\" title=\"Duration Offer\">" . $PlayersLang['DurationOffer'] ."</th>";
	echo "<th data-priority=\"2\" class=\"STHSW100\" title=\"Bonus Offer\">" . $PlayersLang['BonusOffer'] ."</th>";
	echo "<th data-priority=\"3\" class=\"STHSW100\" title=\"Comment Offer\">" . $PlayersLang['CommentOffer'] ."</th>";
	echo "<th data-priority=\"3\" class=\"STHSW45\" title=\"Can Play Pro\">" . $PlayersLang['CanPlayPro'] ."</th>";
	echo "<th data-priority=\"3\" class=\"STHSW45\" title=\"Can Play Farm\">" . $PlayersLang['CanPlayFarm'] ."</th>";
	echo "<th data-priority=\"3\" class=\"STHSW45\" title=\"NoTrade\">" . $PlayersLang['NoTrade'] ."</th>";
	echo "<th data-priority=\"3\" class=\"STHSW65\" title=\"Pro Salary in Farm - 1 Way Contract\">" . $PlayersLang['ProSalaryinFarm'] ."</th>";	
	echo "<th data-priority=\"1\" class=\"STHSW65\" title=\"Submit\">" . $PlayersLang['SubmitOffer'] ."</th>";	
	echo "<th data-priority=\"4\" class=\"STHSW65\" title=\"Erase\">" . $PlayersLang['EraseOffer'] ."</th>";	
}?>
</tr></thead><tbody>
<?php
if (empty($GoalieFreeAgentOffers) == false){while ($Row = $GoalieFreeAgentOffers ->fetchArray()) {
	$strTemp = (string)$Row['Name'];
	if ($Row['Rookie']== "True"){ $strTemp = $strTemp . " (R)";}
	echo "<tr><td><a href=\"GoalieReport.php?Goalie=" . $Row['Number'] . "\">" . $strTemp . "</a></td>";
	echo "<td>";
	If ($Row['TeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Row['TeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPGoaliesRosterTeamImage\" />";}			
	echo $Row['ProTeamName'] . "</td>";	
	echo "<td>" . $Row['SK'] . "</td>";
	echo "<td>" . $Row['DU'] . "</td>";
	echo "<td>" . $Row['EN'] . "</td>";
	echo "<td>" . $Row['SZ'] . "</td>";
	echo "<td>" . $Row['AG'] . "</td>";
	echo "<td>" . $Row['RB'] . "</td>";
	echo "<td>" . $Row['SC'] . "</td>";
	echo "<td>" . $Row['HS'] . "</td>";
	echo "<td>" . $Row['RT'] . "</td>";
	echo "<td>" . $Row['PH'] . "</td>";
	echo "<td>" . $Row['PS'] . "</td>";
	echo "<td>" . $Row['EX'] . "</td>";
	echo "<td>" . $Row['LD'] . "</td>";
	echo "<td>" . $Row['PO'] . "</td>";
	echo "<td>" . $Row['Overall'] . "</td>"; 

	if ($Row['Age'] >= $LeagueGeneral['UFAAge']){echo "<td>" . $PlayersLang['UFAAbbre'] . "</td>";}elseif($Row['Age'] >= $LeagueGeneral['RFAAge']){echo "<td>" . $PlayersLang['RFAAbbre'] . "</td>";}else{echo "<td>" . $PlayersLang['ELCAbbre'] . "</td>";}
	if ($LeagueWebClient['AllowFreeAgentSalaryRequestInSTHSClient'] == "True"){echo "<td>" . number_format($Row['FreeAgentSalaryRequest'],0) . "$ / " . $Row['FreeAgentContratRequest'] . "</td>";}

	echo "<td>" . $Row['StarPower'] . "</td>"; 	
	echo "<td>" . $Row['Age'] . "</td>";
	echo "<td>" . $Row['Contract'] . "</td>";
	echo "<td>" . number_format($Row['LastYearSalary'],0) . "$</td>";
	echo "<td class=\"STHSCenter\"><form name=\"" . ($Row['Number'] + 10000) . "\" action=\"FreeAgentOffers.php\"";If ($lang == "fr"){echo "?Lang=fr";} echo " method=\"post\" onsubmit=\"return validateForm(" . ($Row['Number'] + 10000) .")\" >";
	echo "<input type=\"number\" name=\"SalaryOffer\" value=\"";If(isset($Row['SalaryOffer'])){Echo $Row['SalaryOffer'];}echo "\" size=\"10\" min=\"" . $MinimumSalary . "\" max=\"" . $LeagueFinance['PlayerMaxSalary'] . "\" required></td>";
	echo "<td class=\"STHSCenter\"><input type=\"number\" name=\"DurationOffer\" value=\"";If(isset($Row['DurationOffer'])){Echo $Row['DurationOffer'];}echo "\" size=\"2\" min=\"1\" max=\"" . $LeagueFinance['MaxContractDuration'] . "\" required></td>";
	echo "<td class=\"STHSCenter\"><input type=\"number\" name=\"BonusOffer\" value=\"";If(isset($Row['BonusOffer'])){Echo $Row['BonusOffer'];}echo "\" size=\"10\"></td>";
	echo "<td class=\"STHSCenter\"><input type=\"text\" name=\"CommentOffer\" value=\"";If(isset($Row['CommentOffer'])){Echo $Row['CommentOffer'];}echo "\" size=\"40\"></td>";	
	echo "<td class=\"STHSCenter\"><input type=\"checkbox\" name=\"CanPlayPro\" ";If(isset($Row['CanPlayProOffer'])){If($Row['CanPlayProOffer'] == "True"){echo "checked";}}elseif ($Row['CanPlayPro'] == "True"){echo "checked";} echo "></td>";
	echo "<td class=\"STHSCenter\"><input type=\"checkbox\" name=\"CanPlayFarm\" ";If(isset($Row['CanPlayFarmOffer'])){If($Row['CanPlayFarmOffer'] == "True"){echo "checked";}}elseif ($Row['CanPlayFarm'] == "True"){echo "checked";} echo "></td>";
	echo "<td class=\"STHSCenter\"><input type=\"checkbox\" name=\"NoTrade\" ";If(isset($Row['NoTradeOffer'])){If($Row['NoTradeOffer'] == "True"){echo "checked";}}elseif ($Row['NoTrade'] == "True"){echo "checked";} echo "></td>";
	echo "<td class=\"STHSCenter\"><input type=\"checkbox\" name=\"ProSalaryinFarm\" ";If(isset($Row['ProSalaryinFarm1WayContractOffer'])){If($Row['ProSalaryinFarm1WayContractOffer'] == "True"){echo "checked";}}elseif ($Row['ProSalaryinFarm'] == "True"){echo "checked";} echo "></td>";
	echo "<td class=\"STHSCenter\"><input type=\"submit\" class=\"SubmitButtonSmall\" value=\"" .  $PlayersLang['Submit'] . "\">";	
	echo "<input type=\"hidden\" name=\"Offer\" value=\"" . $CookieTeamNumber . "\">";
	echo "<input type=\"hidden\" name=\"PlayerName\" value=\"" . $Row['Name']  . "\">";
	echo "<input type=\"hidden\" name=\"PlayerNumber\" value=\"" . ($Row['Number'] + 10000) . "\"></form></td>";
	echo "<td class=\"STHSCenter\"><form action=\"FreeAgentOffers.php\"";If ($lang == "fr"){echo "?Lang=fr";} echo " method=\"post\"><input type=\"submit\" class=\"SubmitButtonSmall\" value=\"" .  $PlayersLang['Erase'] . "\">";
	echo "<input type=\"hidden\" name=\"Erase\" value=\"" . $CookieTeamNumber . "\">";
	echo "<input type=\"hidden\" name=\"PlayerName\" value=\"" . $Row['Name']  . "\">";
	echo "<input type=\"hidden\" name=\"PlayerNumber\" value=\"" . ($Row['Number'] + 10000) . "\"></form></td>";	
	echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
}}
?>
</tbody></table>
<br />
</div>
<?php include "Footer.php";?>
