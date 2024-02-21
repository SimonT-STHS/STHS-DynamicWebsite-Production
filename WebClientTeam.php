<?php include "Header.php";
If ($lang == "fr"){include 'LanguageFR-Main.php';}else{include 'LanguageEN-Main.php';}
If ($lang == "fr"){include 'LanguageFR-Stat.php';}else{include 'LanguageEN-Stat.php';}
$LeagueName = (string)"";
$Team = (integer)0;
$TeamEdit = (integer)0;
$EditType = (integer)0;
$TeamProName = $TeamLang['IncorrectTeam'];
$TeamFarmName = $TeamLang['IncorrectTeam'];
$InformationMessage = "";
$Title = (string)"";
If (file_exists($DatabaseFile) == false){
	$LeagueName = $DatabaseNotFound;
	Goto STHSErrorWebClientTeam;
}else{try{	
	$db = new SQLite3($DatabaseFile);
	
	$Query = "Select Name, OffSeason FROM LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	
	If($CookieTeamNumber > 0 AND $CookieTeamNumber <= 100){$Team = $CookieTeamNumber;}
	
If ($Team == 0 OR $Team > 100){
	Goto STHSErrorWebClientTeam;
}else{	

	$Query = "Select ProMaxTicketPrice1,ProMaxTicketPrice2,ProMaxTicketPrice3,ProMaxTicketPrice4,ProMaxTicketPrice5,FarmMaxTicketPrice1,FarmMaxTicketPrice2,GMCanChangeTicketPrice,EmergencyRecallLimitbyTeam from LeagueWebClient";
	$LeagueWebClient = $db->querySingle($Query,true);
	
	$Query = "SELECT Count(MainTable.EmergencyRecall) AS CountOFEmergencyRecall FROM (SELECT PlayerInfo.EmergencyRecall FROM PlayerInfo WHERE Team = " . $Team . " AND Retire = \"False\" AND EmergencyRecall = \"True\" UNION ALL SELECT GoalerInfo.EmergencyRecall FROM GoalerInfo WHERE Team = " . $Team . " AND Retire = \"False\" AND EmergencyRecall = \"True\") AS MainTable";
	$TeamEmergencyRecall = $db->querySingle($Query,true);

	if(isset($_POST['TeamEdit'])){$TeamEdit = filter_var($_POST['TeamEdit'], FILTER_SANITIZE_NUMBER_INT);}
	if(isset($_POST['EditType'])){$EditType = filter_var($_POST['EditType'], FILTER_SANITIZE_NUMBER_INT);}
	If ($Team == $TeamEdit AND $EditType > 0){
		$Captain = (integer)0;
		$Assistant1 = (integer)0;
		$Assistant2 = (integer)0;
		$TicketL1 = (integer)0;
		$TicketL2 = (integer)0;
		$TicketL3 = (integer)0;
		$TicketL4 = (integer)0;
		$TicketLuxury = (integer)0;
		If ($EditType == 1){
			if(isset($_POST['ProCaptain'])){$Captain = filter_var($_POST['ProCaptain'], FILTER_SANITIZE_NUMBER_INT);} 
			if(isset($_POST['ProAssistant1'])){$Assistant1 = filter_var($_POST['ProAssistant1'], FILTER_SANITIZE_NUMBER_INT);} 
			if(isset($_POST['ProAssistant2'])){$Assistant2 = filter_var($_POST['ProAssistant2'], FILTER_SANITIZE_NUMBER_INT);}
			if ($Captain > 0 AND $Captain <= 10000 AND $Assistant1 > 0 AND $Assistant1 <= $Assistant1 AND $Assistant2 > 0 ANd $Assistant2 <= 10000){
				try {
					$Query = "Update TeamProInfo SET Captain = '" . $Captain . "', Assistant1 = '" . $Assistant1 . "', Assistant2 = '" . $Assistant2 . "', WebClientModify = 'True' WHERE Number = " . $TeamEdit;
					$db->exec($Query);
					$InformationMessage = $WebClientLang['EditConfirm'];
				} catch (Exception $e) {
					echo $WebClientLang['EditFail'];
				}					
			}
		}elseIf ($EditType == 2){
			if(isset($_POST['FarmCaptain'])){$Captain = filter_var($_POST['FarmCaptain'], FILTER_SANITIZE_NUMBER_INT);} 
			if(isset($_POST['FarmAssistant1'])){$Assistant1 = filter_var($_POST['FarmAssistant1'], FILTER_SANITIZE_NUMBER_INT);} 
			if(isset($_POST['FarmAssistant2'])){$Assistant2 = filter_var($_POST['FarmAssistant2'], FILTER_SANITIZE_NUMBER_INT);} 
			if ($Captain > 0 AND $Captain <= 10000 AND $Assistant1 > 0 AND $Assistant1 <= $Assistant1 AND $Assistant2 > 0 ANd $Assistant2 <= 10000){
				try {
					$Query = "Update TeamFarmInfo SET Captain = '" . $Captain . "', Assistant1 = '" . $Assistant1 . "', Assistant2 = '" . $Assistant2 . "', WebClientModify = 'True' WHERE Number = " . $TeamEdit;
					$db->exec($Query);
					$InformationMessage = $WebClientLang['EditConfirm'];
				} catch (Exception $e) {
					$InformationMessage = $WebClientLang['EditFail'];
				}					
			}			
		}elseIf ($EditType == 3){
			if(isset($_POST['ProTicketPriceL1'])){$TicketL1 = filter_var($_POST['ProTicketPriceL1'], FILTER_SANITIZE_NUMBER_INT);} 
			if(isset($_POST['ProTicketPriceL2'])){$TicketL2 = filter_var($_POST['ProTicketPriceL2'], FILTER_SANITIZE_NUMBER_INT);} 
			if(isset($_POST['ProTicketPriceL3'])){$TicketL3 = filter_var($_POST['ProTicketPriceL3'], FILTER_SANITIZE_NUMBER_INT);} 
			if(isset($_POST['ProTicketPriceL4'])){$TicketL4 = filter_var($_POST['ProTicketPriceL4'], FILTER_SANITIZE_NUMBER_INT);} 
			if(isset($_POST['ProTicketPriceLuxury'])){$TicketLuxury = filter_var($_POST['ProTicketPriceLuxury'], FILTER_SANITIZE_NUMBER_INT);} 
			if ($TicketL1 > 0 AND $TicketL2 > 0 AND $TicketL3 > 0  AND $TicketL4 > 0 AND $TicketLuxury > 0 AND $TicketL1 <= $LeagueWebClient['ProMaxTicketPrice1'] AND $TicketL2 <= $LeagueWebClient['ProMaxTicketPrice2'] AND $TicketL3 <= $LeagueWebClient['ProMaxTicketPrice3'] AND $TicketL4 <= $LeagueWebClient['ProMaxTicketPrice4'] AND $TicketLuxury <= $LeagueWebClient['ProMaxTicketPrice5']){
				try {
					$Query = "Update TeamProFinance SET TicketPriceL1 = '" . $TicketL1 . "', TicketPriceL2 = '" . $TicketL2 . "', TicketPriceL3 = '" . $TicketL3 . "',TicketPriceL4 = '" . $TicketL4 . "',TicketPriceLuxury = '" . $TicketLuxury . "', WebClientModify = 'True' WHERE Number = " . $TeamEdit;
					$db->exec($Query);
					$InformationMessage = $WebClientLang['EditConfirm'];
				} catch (Exception $e) {
					$InformationMessage = $WebClientLang['EditFail'];
				}					
			}else{$InformationMessage = $WebClientLang['EditFail'];}		
		}elseIf ($EditType == 4){
			if(isset($_POST['FarmTicketPriceL1'])){$TicketL1 = filter_var($_POST['FarmTicketPriceL1'], FILTER_SANITIZE_NUMBER_INT);} 
			if(isset($_POST['FarmTicketPriceL2'])){$TicketL2 = filter_var($_POST['FarmTicketPriceL2'], FILTER_SANITIZE_NUMBER_INT);} 
			if ($TicketL1 > 0 AND $TicketL2 > 0 AND $TicketL1 <= $LeagueWebClient['FarmMaxTicketPrice1'] AND $TicketL2 <= $LeagueWebClient['FarmMaxTicketPrice2']){
				try {
					$Query = "Update TeamFarmFinance SET TicketPriceL1 = '" . $TicketL1 . "', TicketPriceL2 = '" . $TicketL2 . "', WebClientModify = 'True' WHERE Number = " . $TeamEdit;
					$db->exec($Query);
					$InformationMessage = $WebClientLang['EditConfirm'];
				} catch (Exception $e) {
					echo $WebClientLang['EditFail'];
				}					
			}else{$InformationMessage = $WebClientLang['EditFail'];}	
		}elseIf ($EditType == 5){
			$PlayerNumber = (integer)0;
			$PlayerName = (string)"";	
			$PlayerPProtected = (string)"False";
			$PlayerForceUFA = (string)"False";
			$PlayerEmergencyRecall = (string)"False";
			$PlayerAvailableForTrade = (string)"False";
			$PlayerAutoRosterCanPlayPro = (string)"False";
			$PlayerAutoRosterCanPlayFarm = (string)"False";
			if(isset($_POST['PlayerNumber'])){$PlayerNumber = filter_var($_POST['PlayerNumber'], FILTER_SANITIZE_NUMBER_INT);} 
			if(isset($_POST['PlayerName'])){$PlayerName =  filter_var($_POST['PlayerName'], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH || FILTER_FLAG_NO_ENCODE_QUOTES || FILTER_FLAG_STRIP_BACKTICK);}
			if(isset($_POST['PProtected'])){$PlayerPProtected = "True";}
			if(isset($_POST['ForceUFA'])){$PlayerForceUFA = "True";}
			if(isset($_POST['EmergencyRecall'])){$PlayerEmergencyRecall = "True";}
			if(isset($_POST['AvailableForTrade'])){$PlayerAvailableForTrade = "True";}
			if(isset($_POST['AutoRosterCanPlayPro'])){$PlayerAutoRosterCanPlayPro = "True";}
			if(isset($_POST['AutoRosterCanPlayFarm'])){$PlayerAutoRosterCanPlayFarm = "True";}

			try {
				If ($PlayerNumber > 0 and $PlayerNumber <= 10000){
					$Query = "Update PlayerInfo SET PProtected = '" . $PlayerPProtected . "', ForceUFA = '" . $PlayerForceUFA . "', AvailableForTrade = '" . $PlayerAvailableForTrade . "', AutoRosterCanPlayPro = '" . $PlayerAutoRosterCanPlayPro . "', AutoRosterCanPlayFarm = '" . $PlayerAutoRosterCanPlayFarm  . "', WebClientModify = 'True' WHERE Number = " . $PlayerNumber;
					$db->exec($Query);
					$InformationMessage = $PlayersLang['EditConfirm'] . $PlayerName;
				}elseif($PlayerNumber > 10000 and $PlayerNumber <= 11000){
					$Query = "Update GoalerInfo SET PProtected = '" . $PlayerPProtected . "', ForceUFA = '" . $PlayerForceUFA . "', AvailableForTrade = '" . $PlayerAvailableForTrade . "', AutoRosterCanPlayPro = '" . $PlayerAutoRosterCanPlayPro . "', AutoRosterCanPlayFarm = '" . $PlayerAutoRosterCanPlayFarm  . "', WebClientModify = 'True' WHERE Number = " . ($PlayerNumber - 10000);
					$db->exec($Query);
					$InformationMessage = $PlayersLang['EditConfirm'] . $PlayerName;
				}else{
					$InformationMessage = $PlayersLang['EditFail'];
				}
			} catch (Exception $e) {
				$InformationMessage = $PlayersLang['EditFail'];
			}
			
			if($PlayerEmergencyRecall == "True"){
				If ($TeamEmergencyRecall['CountOFEmergencyRecall'] < $LeagueWebClient['EmergencyRecallLimitbyTeam']){
					If ($PlayerNumber > 0 and $PlayerNumber <= 10000){
						$Query = "Update PlayerInfo SET EmergencyRecall = '" . $PlayerEmergencyRecall . "', WebClientModify = 'True' WHERE Number = " . $PlayerNumber;
						$db->exec($Query);
					}elseif($PlayerNumber > 10000 and $PlayerNumber <= 11000){
						$Query = "Update GoalerInfo SET EmergencyRecall = '" . $PlayerEmergencyRecall . "',  WebClientModify = 'True' WHERE Number = " . ($PlayerNumber - 10000);
						$db->exec($Query);
					}else{
						$InformationMessage = $PlayersLang['EditFail'];
					}				
				}else{
					$InformationMessage = $InformationMessage . $PlayersLang['EditFailEmergencyRecall'];
				}					
			}			
		}
	}

	$Query = "SELECT Name, Captain, Assistant1, Assistant2 FROM TeamProInfo WHERE Number = " . $Team;
	$TeamProInfo = $db->querySingle($Query,true);
	$TeamProName = $TeamProInfo['Name'];	
	$Query = "SELECT TeamProInfo.Name as TeamName, PlayerInfo_1.Name As CaptainName, PlayerInfo_1.Number As CaptainNumber, PlayerInfo_2.Name as Assistant1Name, PlayerInfo_2.Number as Assistant1Number, PlayerInfo_3.Name as Assistant2Name, PlayerInfo_3.Number as Assistant2Number FROM ((TeamProInfo LEFT JOIN PlayerInfo AS PlayerInfo_1 ON TeamProInfo.Captain = PlayerInfo_1.Number) LEFT JOIN PlayerInfo AS PlayerInfo_2 ON TeamProInfo.Assistant1 = PlayerInfo_2.Number) LEFT JOIN PlayerInfo AS PlayerInfo_3 ON TeamProInfo.Assistant2 = PlayerInfo_3.Number WHERE TeamProInfo.Number = " . $Team;
	$TeamProLeader = $db->querySingle($Query,true);	
	$Query = "SELECT Name, Number FROM PlayerInfo WHERE Team = " . $Team . " AND Status1 >= 2 ORDER BY Name ASC";
	$PlayerProRoster = $db->query($Query);	
	$Query = "SELECT TicketPriceL1,TicketPriceL2,TicketPriceL3,TicketPriceL4,TicketPriceLuxury,ArenaCapacityL1,ArenaCapacityL2,ArenaCapacityL3,ArenaCapacityL4,ArenaCapacityLuxury FROM TeamProFinance WHERE Number = " . $Team;
	$TeamProFinance = $db->querySingle($Query,true);	
	
	$Query = "SELECT Name, Captain, Assistant1, Assistant2 FROM TeamFarmInfo WHERE Number = " . $Team;
	$TeamFarmInfo = $db->querySingle($Query,true);	
	$TeamFarmName = $TeamFarmInfo['Name'];		
	$Query = "SELECT TeamFarmInfo.Name as TeamName, PlayerInfo_1.Name As CaptainName, PlayerInfo_1.Number As CaptainNumber, PlayerInfo_2.Name as Assistant1Name, PlayerInfo_2.Number as Assistant1Number, PlayerInfo_3.Name as Assistant2Name, PlayerInfo_3.Number as Assistant2Number FROM ((TeamFarmInfo LEFT JOIN PlayerInfo AS PlayerInfo_1 ON TeamFarmInfo.Captain = PlayerInfo_1.Number) LEFT JOIN PlayerInfo AS PlayerInfo_2 ON TeamFarmInfo.Assistant1 = PlayerInfo_2.Number) LEFT JOIN PlayerInfo AS PlayerInfo_3 ON TeamFarmInfo.Assistant2 = PlayerInfo_3.Number WHERE TeamFarmInfo.Number = " . $Team;
	$TeamFarmLeader = $db->querySingle($Query,true);	
	$Query = "SELECT Name, Number FROM PlayerInfo WHERE Team = " . $Team . " AND Status1 <= 1 ORDER BY Name ASC";
	$PlayerFarmRoster = $db->query($Query);	
	$Query = "SELECT TicketPriceL1,TicketPriceL2,ArenaCapacityL1,ArenaCapacityL2 FROM TeamFarmFinance WHERE Number = " . $Team;
	$TeamFarmFinance = $db->querySingle($Query,true);	
	
	$Query = "SELECT MainTable.* FROM (SELECT PlayerInfo.Number, PlayerInfo.Name, PlayerInfo.Team, PlayerInfo.PProtected, PlayerInfo.ForceUFA, PlayerInfo.EmergencyRecall, PlayerInfo.AvailableForTrade, PlayerInfo.AutoRosterCanPlayPro, PlayerInfo.AutoRosterCanPlayFarm, PlayerInfo.PosC, PlayerInfo.PosLW, PlayerInfo.PosRW, PlayerInfo.PosD, 'False' AS PosG, PlayerInfo.Retire as Retire FROM PlayerInfo WHERE Team = " . $Team . " AND Retire = \"False\" UNION ALL SELECT GoalerInfo.Number, GoalerInfo.Name, GoalerInfo.Team, GoalerInfo.PProtected, GoalerInfo.ForceUFA, GoalerInfo.EmergencyRecall, GoalerInfo.AvailableForTrade, GoalerInfo.AutoRosterCanPlayPro, GoalerInfo.AutoRosterCanPlayFarm, 'False' AS PosC, 'False' AS PosLW, 'False' AS PosRW, 'False' AS PosD, 'True' AS PosG, GoalerInfo.Retire as Retire FROM GoalerInfo WHERE Team = " . $Team . " AND Retire = \"False\") AS MainTable ORDER BY MainTable.Name ASC";
	$PlayerInfo = $db->query($Query);	

}} catch (Exception $e) {
STHSErrorWebClientTeam:
	$TeamProLeader = Null;
	$TeamFarmLeader = Null;
	$TeamProFinance = Null;
	$PlayerProRoster = Null;
	$PlayerFarmRoster = Null;
	$TeamFarmFinance = Null;
	echo "<style>#WebClientMainDiv {display:none}</style>\n";
	If ($CookieTeamNumber == 0){$InformationMessage = $NoUserLogin;}elseif($CookieTeamNumber > 0){$InformationMessage = $ThisPageNotAvailable;}
}}
If ($LeagueWebClient['GMCanChangeTicketPrice'] == "False"){
	echo "<style>#ProTicketForm, #FarmTicketForm {display:none};</style>";
}
$Title = $LeagueName . " - " . $WebClientLang['TeamInfo'] . " - " . $TeamProName;
echo "<title>" . $Title . "</title>";
?>
</head><body>
<?php include "Menu.php";
echo "<h1>" . $Title . "</h1>";
?>
<script>
function validateFormProCap() {
   var x = document.getElementById("ProCaptain").value;
   var y = document.getElementById("ProAssistant1").value;
   var z = document.getElementById("ProAssistant2").value;
   if (x==null || x=="" || y==null || y=="" || z==null || z=="")
   {
      alert("<?php echo $WebClientLang['ErrorCap'];?>");
      return false;
   }

   if (x==y || x==z || y==z )
   {
      alert("<?php echo $WebClientLang['ErrorCap'];?>");
      return false;
   }
   return true;
}
function validateFormFarmCap() {
   var x = document.getElementById("FarmCaptain").value;
   var y = document.getElementById("FarmAssistant1").value;
   var z = document.getElementById("FarmAssistant2").value;
   if (x==null || x=="" || y==null || y=="" || z==null || z=="")
   {
      alert("<?php echo $WebClientLang['ErrorCap'];?>!");
      return false;
   }

   if (x==y || x==z || y==z )
   {
      alert("<?php echo $WebClientLang['ErrorCap'];?>");
      return false;
   }
   return true;
}
function validateFormProTicket() {
   var L1 = document.getElementsByName('ProTicketPriceL1')[0].valueAsNumber;
   var L2 = document.getElementsByName('ProTicketPriceL2')[0].valueAsNumber;
   var L3 = document.getElementsByName('ProTicketPriceL3')[0].valueAsNumber;
   var L4 = document.getElementsByName('ProTicketPriceL4')[0].valueAsNumber;
   if (L1 <= L2 || L2 <= L3 || L3 <= L4)
   {
      alert("<?php echo $WebClientLang['ErrorProTicket'];?>");
      return false;
   } 
	
   return true
}
function validateFormFarmTicket() {
   var L1 = document.getElementsByName('FarmTicketPriceL1')[0].valueAsNumber;
   var L2 = document.getElementsByName('FarmTicketPriceL2')[0].valueAsNumber;
   if (L1 <= L2 )
   {
      alert("<?php echo $WebClientLang['ErrorFarmTicket'];?>");
      return false;
   }   
   return true
}
function validateForm(fName) {

   if (document[fName]["AutoRosterCanPlayPro"].checked==false && document[fName]["AutoRosterCanPlayFarm"].checked==false)
   {
      alert("Auto Can Sent Pro Or Auto Can Sent Farm must be Select!");
      return false;
   }  
   return true;
}
</script>
<?php if ($InformationMessage != ""){echo "<div class=\"STHSDivInformationMessage\">" . $InformationMessage . "<br /></div>";}?>
<div id="WebClientMainDiv" style="width:99%;margin:auto;">
<?php 
echo "<table class=\"STHSPHPWebClientTeam_Main\"><tr><td style=\"width:50%;text-align:center;font-size:25px;padding:20px;margin:auto;\">" . $TeamProName . "</td><td style=\"width:50%;text-align:center;font-size:25px;padding:20px;margin:auto;\">" . $TeamFarmName . "</td></tr><tr><td>\n";

echo "<form name=\"" . $Team . "\" action=\"WebClientTeam.php";If ($lang == "fr"){echo "?Lang=fr";} echo "\" method=\"post\" onsubmit=\"return validateFormProCap()\">";
echo "<table class=\"STHSPHPWebClientTeam_Table\"><tr><th>" . $TeamLang['Captain'] . "</th><th>" . $TeamLang['Assistant1'] . "</th><th>" . $TeamLang['Assistant2'] . "</th></tr>\n";
If ($TeamProInfo <> Null){
	echo "<tr><td>";
	echo "<select id=\"ProCaptain\" name=\"ProCaptain\" class=\"STHSSelect STHSW250\">";
	if (empty($PlayerProRoster) == false){while ($Row = $PlayerProRoster->fetchArray()) {
		echo "<option value=\"" . $Row['Number'] . "\""; 
		if ($Row['Number'] == $TeamProLeader['CaptainNumber']){echo " selected=\"selected\"";}
		echo ">" . $Row['Name'] . "</option>\n"; 
	}}	
	echo "</select></td><td>";
	echo "<select id=\"ProAssistant1\" name=\"ProAssistant1\" class=\"STHSSelect STHSW250\">";
	if (empty($PlayerProRoster) == false){while ($Row = $PlayerProRoster->fetchArray()) {
		echo "<option value=\"" . $Row['Number'] . "\""; 
		if ($Row['Number'] == $TeamProLeader['Assistant1Number']){echo " selected=\"selected\"";}
		echo ">" . $Row['Name'] . "</option>\n"; 
	}}	
	echo "</select></td><td>";
	echo "<select id=\"ProAssistant2\" name=\"ProAssistant2\" class=\"STHSSelect STHSW250\">";
	if (empty($PlayerProRoster) == false){while ($Row = $PlayerProRoster->fetchArray()) {
		echo "<option value=\"" . $Row['Number'] . "\""; 
		if ($Row['Number'] == $TeamProLeader['Assistant2Number']){echo " selected=\"selected\"";}
		echo ">" . $Row['Name'] . "</option>\n"; 
	}}	
	echo "</select>";
	echo "<input type=\"hidden\" name=\"TeamEdit\" value=\"" . $Team . "\">";
	echo "<input type=\"hidden\" name=\"EditType\" value=\"1\">";
	echo "</td></tr><tr><td colspan=\"3\" class=\"STHSCenter\"><input type=\"submit\" class=\"SubmitButton\" value=\"" . $WebClientLang['SubmitCapAss'] . "\"></td></tr>\n";
}
echo "</table></form>";

echo "</td><td>";

echo "<form name=\"" . $Team . "\" action=\"WebClientTeam.php";If ($lang == "fr"){echo "?Lang=fr";} echo "\" method=\"post\" onsubmit=\"return validateFormFarmCap()\">\n";
echo "<table class=\"STHSPHPWebClientTeam_Table\"><tr><th>" . $TeamLang['Captain'] . "</th><th>" . $TeamLang['Assistant1'] . "</th><th>" . $TeamLang['Assistant2'] . "</th></tr>\n";
If ($TeamFarmInfo <> Null){
	echo "<tr><td>";
	echo "<select id=\"FarmCaptain\" name=\"FarmCaptain\" class=\"STHSSelect STHSW250\">";
	if (empty($PlayerFarmRoster) == false){while ($Row = $PlayerFarmRoster->fetchArray()) {
		echo "<option value=\"" . $Row['Number'] . "\""; 
		if ($Row['Number'] == $TeamFarmLeader['CaptainNumber']){echo " selected=\"selected\"";}
		echo ">" . $Row['Name'] . "</option>\n"; 
	}}	
	echo "</select></td><td>";
	echo "<select id=\"FarmAssistant1\" name=\"FarmAssistant1\" class=\"STHSSelect STHSW250\">";
	if (empty($PlayerFarmRoster) == false){while ($Row = $PlayerFarmRoster->fetchArray()) {
		echo "<option value=\"" . $Row['Number'] . "\""; 
		if ($Row['Number'] == $TeamFarmLeader['Assistant1Number']){echo " selected=\"selected\"";}
		echo ">" . $Row['Name'] . "</option>\n"; 
	}}	
	echo "</select></td><td>";
	echo "<select id=\"FarmAssistant2\" name=\"FarmAssistant2\" class=\"STHSSelect STHSW250\">";
	if (empty($PlayerFarmRoster) == false){while ($Row = $PlayerFarmRoster->fetchArray()) {
		echo "<option value=\"" . $Row['Number'] . "\""; 
		if ($Row['Number'] == $TeamFarmLeader['Assistant2Number']){echo " selected=\"selected\"";}
		echo ">" . $Row['Name'] . "</option>\n"; 
	}}	
	echo "</select>";
	echo "<input type=\"hidden\" name=\"TeamEdit\" value=\"" . $Team . "\">\n";
	echo "<input type=\"hidden\" name=\"EditType\" value=\"2\">\n";
	echo "</td></tr><tr><td colspan=\"3\" class=\"STHSCenter\"><input type=\"submit\" class=\"SubmitButton\" value=\"" . $WebClientLang['SubmitCapAss'] . "\"></td></tr>\n";
}
echo "</table></form>";

echo "</td></tr><tr><th colspan=\"2\" style=\"padding:20px;margin:auto;\"></th><tr><td>\n";

echo "<form id=\"ProTicketForm\" name=\"" . $Team . "\" action=\"WebClientTeam.php";If ($lang == "fr"){echo "?Lang=fr";} echo "\" method=\"post\" onsubmit=\"return validateFormProTicket()\">\n";
echo "<table class=\"STHSPHPWebClientTeam_Table\"><tr><th>" . $TeamLang['Level'] . "</th><th>" . $TeamLang['ArenaCapacity'] . "</th><th>" . $TeamLang['TicketPrice'] . "</th></tr>\n";
echo "<tr><td>" . $TeamLang['Level'] ." 1</td><td>" . $TeamProFinance['ArenaCapacityL1'] . "</td><td><input type=\"number\" name=\"ProTicketPriceL1\" min=\"4\" max=\"" . $LeagueWebClient['ProMaxTicketPrice1'] . "\" value=\"" . $TeamProFinance['TicketPriceL1'] . "\"></td></tr>\n";
echo "<tr><td>" . $TeamLang['Level'] ." 2</td><td>" . $TeamProFinance['ArenaCapacityL2'] . "</td><td><input type=\"number\" name=\"ProTicketPriceL2\" min=\"3\" max=\"" . $LeagueWebClient['ProMaxTicketPrice2'] . "\" value=\"" . $TeamProFinance['TicketPriceL2'] . "\"></td></tr>\n";
echo "<tr><td>" . $TeamLang['Level'] ." 3</td><td>" . $TeamProFinance['ArenaCapacityL3'] . "</td><td><input type=\"number\" name=\"ProTicketPriceL3\" min=\"2\" max=\"" . $LeagueWebClient['ProMaxTicketPrice3'] . "\" value=\"" . $TeamProFinance['TicketPriceL3'] . "\"></td></tr>\n";
echo "<tr><td>" . $TeamLang['Level'] ." 4</td><td>" . $TeamProFinance['ArenaCapacityL4'] . "</td><td><input type=\"number\" name=\"ProTicketPriceL4\" min=\"1\" max=\"" . $LeagueWebClient['ProMaxTicketPrice4'] . "\" value=\"" . $TeamProFinance['TicketPriceL4'] . "\"></td></tr>\n";
echo "<tr><td>" . $TeamLang['Luxury'] ." </td><td>" . $TeamProFinance['ArenaCapacityLuxury'] . "</td><td><input type=\"number\" name=\"ProTicketPriceLuxury\" min=\"1\" max=\"" . $LeagueWebClient['ProMaxTicketPrice5'] . "\"  value=\"" . $TeamProFinance['TicketPriceLuxury']  . "\"></td></tr>\n";
echo "</tr><tr><td colspan=\"3\" class=\"STHSCenter\"><input type=\"hidden\" name=\"TeamEdit\" value=\"" . $Team . "\"><input type=\"hidden\" name=\"EditType\" value=\"3\"><input type=\"submit\" class=\"SubmitButton\" value=\"" . $WebClientLang['SubmitTicket'] . "\"></td></tr>\n";
echo "</table></form>";

echo "</td><td style=\"vertical-align: top;\">";

echo "<form id=\"FarmTicketForm\" name=\"" . $Team . "\" action=\"WebClientTeam.php";If ($lang == "fr"){echo "?Lang=fr";} echo "\" method=\"post\" onsubmit=\"return validateFormFarmTicket()\">\n";
echo "<table class=\"STHSPHPWebClientTeam_Table\"><tr><th>" . $TeamLang['Level'] . "</th><th>" . $TeamLang['ArenaCapacity'] . "</th><th>" . $TeamLang['TicketPrice'] . "</th></tr>\n";
echo "<tr><td>" . $TeamLang['Level'] ." 1</td><td>" . $TeamFarmFinance['ArenaCapacityL1'] . "</td><td><input type=\"number\" name=\"FarmTicketPriceL1\" min=\"2\" max=\"" . $LeagueWebClient['FarmMaxTicketPrice1'] . "\" value=\"" . $TeamFarmFinance['TicketPriceL1'] . "\"></td></tr>\n";
echo "<tr><td>" . $TeamLang['Level'] ." 2</td><td>" . $TeamFarmFinance['ArenaCapacityL2'] . "</td><td><input type=\"number\" name=\"FarmTicketPriceL2\" min=\"1\" max=\"" . $LeagueWebClient['FarmMaxTicketPrice2'] . "\" value=\"" . $TeamFarmFinance['TicketPriceL2'] . "\"></td></tr>\n";
echo "</tr><tr><td colspan=\"3\" class=\"STHSCenter\"><input type=\"hidden\" name=\"TeamEdit\" value=\"" . $Team . "\"><input type=\"hidden\" name=\"EditType\" value=\"4\"><input type=\"submit\" class=\"SubmitButton\" value=\"" . $WebClientLang['SubmitTicket'] . "\"></td></tr>";
echo "</table></form>\n";

echo "</td></tr></table>\n";
echo "<br /><br />\n";
echo "<div style=\"width:90%;margin:auto;\">\n";
echo "<h1>" . $WebClientLang['EditPlayer'] . "</h1>\n";
echo "<table class=\"tablesorter STHSPHPAllPlayerInformation_Table\"><thead><tr>\n";
echo "<th data-priority=\"critical\" title=\"Player Name\" class=\"STHSW140Min\">" . $PlayersLang['PlayerName'] . "</th>\n";
echo "<th data-priority=\"2\" title=\"Position\" class=\"STHSW45\">POS</th>\n";
echo "<th data-priority=\"4\" title=\"AvailableForTrade\" class=\"STHSW55\">" . $PlayersLang['AvailableForTrade'] . "</th>\n";
If ($LeagueGeneral['OffSeason'] == "True"){
	echo "<th data-priority=\"4\" title=\"Protected\" class=\"STHSW55\">" . $PlayersLang['Protected'] . "</th>\n";
	echo "<th data-priority=\"4\" title=\"Protected\" class=\"STHSW55\">" . $PlayersLang['ForceUFA'] . "</th>\n";
}
echo "<th data-priority=\"4\" title=\"AutoRosterCanPlayPro\" class=\"STHSW55\">" . $PlayersLang['AutoRosterCanPlayPro'] . "</th>\n";
echo "<th data-priority=\"3\" title=\"AutoRosterCanPlayFarm\" class=\"STHSW55\">" . $PlayersLang['AutoRosterCanPlayFarm'] . "</th>\n";
echo "<th data-priority=\"3\" title=\"AutoRosterCanPlayFarm\" class=\"STHSW55\">" . $PlayersLang['EmergencyRecall'] . "</th>\n";
echo "<th data-priority=\"2\" title=\"Edit\" class=\"STHSW55\">" . $PlayersLang['Edit'] . "</th>\n";
echo "</tr></thead><tbody>\n";
 
if (empty($PlayerInfo) == false){while ($Row = $PlayerInfo ->fetchArray()) { 
	echo "<tr><td>";
	if ($Row['PosG']== "True"){echo "<a href=\"GoalieReport.php?Goalie=";}else{echo "<a href=\"PlayerReport.php?Player=";}
	echo $Row['Number'] . "\">" . $Row['Name'] . "</a></td>";
	echo "<td>" .$Position = (string)"";
	if ($Row['PosC']== "True"){if ($Position == ""){$Position = "C";}else{$Position = $Position . "/C";}}
	if ($Row['PosLW']== "True"){if ($Position == ""){$Position = "LW";}else{$Position = $Position . "/LW";}}
	if ($Row['PosRW']== "True"){if ($Position == ""){$Position = "RW";}else{$Position = $Position . "/RW";}}
	if ($Row['PosD']== "True"){if ($Position == ""){$Position = "D";}else{$Position = $Position . "/D";}}
	if ($Row['PosG']== "True"){if ($Position == ""){$Position = "G";}}
	echo $Position . "</td>";	
	echo "<td class=\"STHSCenter\"><form name=\"" . $Row['Number'] . "\" action=\"WebClientTeam.php";If ($lang == "fr"){echo "?Lang=fr";} echo "\" method=\"post\" onsubmit=\"return validateForm(" . $Row['Number'] .")\" >";
	echo "<input type=\"checkbox\" name=\"AvailableForTrade\""; if($Row['AvailableForTrade'] == "True"){echo " checked";}echo "></td>";
	If ($LeagueGeneral['OffSeason'] == "True"){
		echo "<td class=\"STHSCenter\"><input type=\"checkbox\" name=\"PProtected\""; if($Row['PProtected'] == "True"){echo " checked";}echo "></td>";
		echo "<td class=\"STHSCenter\"><input type=\"checkbox\" name=\"ForceUFA\""; if($Row['ForceUFA'] == "True"){echo " checked";}echo "></td>";
		
	}
	echo "<td class=\"STHSCenter\"><input type=\"checkbox\" name=\"AutoRosterCanPlayPro\""; if($Row['AutoRosterCanPlayPro'] == "True"){echo " checked";}echo "></td>";
	echo "<td class=\"STHSCenter\"><input type=\"checkbox\" name=\"AutoRosterCanPlayFarm\""; if($Row['AutoRosterCanPlayFarm'] == "True"){echo " checked";}echo "></td>";
	echo "<td class=\"STHSCenter\"><input type=\"checkbox\" name=\"EmergencyRecall\""; if($Row['EmergencyRecall'] == "True"){echo " checked disabled";}echo "></td>";
	echo "<td class=\"STHSCenter\"><input type=\"submit\" class=\"SubmitButtonSmall\" value=\"" . $PlayersLang['Edit'] . "\">";
	echo "<input type=\"hidden\" name=\"TeamEdit\" value=\"" . $CookieTeamNumber . "\">";
	echo "<input type=\"hidden\" name=\"EditType\" value=\"5\">";
	echo "<input type=\"hidden\" name=\"PlayerName\" value=\"" . $Row['Name'] . "\">";
	echo "<input type=\"hidden\" name=\"PlayerNumber\" value=\"";If($Row['PosG']== "True"){echo ($Row['Number']+10000);}else{echo $Row['Number'];}echo "\"></form></td>";
	echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
}}
?>
</tbody></table></div>


</div>


<?php include "Footer.php";?>
