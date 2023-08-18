<?php include "Header.php";
If ($lang == "fr"){include 'LanguageFR-League.php';}else{include 'LanguageEN-League.php';}
$LeagueName = (string)"";
$InformationMessage = (string)"";
$Player = (integer)0;
$PlayerName = (string)"";
If (file_exists($DatabaseFile) == false){
	Goto STHSErrorWaiver;
}else{try{
	$db = new SQLite3($DatabaseFile);
	$Query = "SELECT Waiver.*, TeamProInfo.Name As FromTeamName, TeamProInfo_ToTeam.Name AS ToTeamName, TeamProInfo.TeamThemeID as FromTeamThemeID, TeamProInfo_ToTeam.TeamThemeID as ToTeamThemeID FROM (Waiver LEFT JOIN TeamProInfo ON Waiver.FromTeam = TeamProInfo.Number) LEFT JOIN TeamProInfo AS TeamProInfo_ToTeam ON Waiver.ToTeam = TeamProInfo_ToTeam.Number ORDER BY Waiver.Player";
	$Waiver = $db->query($Query);
	$Query = "SELECT WaiverOrder.*, TeamProInfo.Name FROM WaiverOrder LEFT JOIN TeamProInfo ON WaiverOrder.TeamProNumber = TeamProInfo.Number ORDER BY WaiverOrder.Number";
	$WaiverOrder = $db->query($Query);
	
	$Query = "Select Name, OutputName from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	
	$Query = "Select WaiversEnable from LeagueSimulation";
	$LeagueSimulation = $db->querySingle($Query,true);		
		
	if($CookieTeamNumber > 0 AND $CookieTeamNumber <= 100 AND $LeagueSimulation['WaiversEnable'] == "True"){
		$Query = "Select Name from TeamProInfo Where Number = " . $CookieTeamNumber;
		$TeamInfo =  $db->querySingle($Query,true);
		
		if(isset($_GET['Player'])){
			$Player = filter_var($_GET['Player'], FILTER_SANITIZE_NUMBER_INT);
		
			If ($Player > 10000){ //Goalie
				$Query = "SELECT Name FROM GoalerInfo WHERE GoalerInfo.Number = " . $Player - 10000;
				$GoalerInfo = $db->querySingle($Query,true);
				$PlayerName = $GoalerInfo['Name'];
			}elseif ($Player > 0){ //Player
				$Query = "SELECT Name FROM PlayerInfo WHERE PlayerInfo.Number = " . $Player;
				$PlayerInfo = $db->querySingle($Query,true);
				$PlayerName = $PlayerInfo['Name'];
			}
				
			$Query = "SELECT count(*) AS count FROM WaiverInterest WHERE Player = " . $Player . " AND TeamProNumber = " . $CookieTeamNumber ;
			$Result = $db->querySingle($Query,true);

			If ($Result['count'] == 0){ //No Interest Found
				if(isset($_GET['ShowInterest'])){ // Show Interest, Adding in Table
					$Query = "INSERT INTO WaiverInterest (Player,TeamProNumber) VALUES('" . $Player . "','" . $CookieTeamNumber . "')";
					$db->exec($Query);
					$InformationMessage = $TeamInfo['Name'] . $WaiverLang['ShowInterestIn'] . $PlayerName . ".";
				}elseif(isset($_GET['RemoveInterest'])){ // No Interest to Remove
					$InformationMessage = $TeamInfo['Name'] . $WaiverLang['NotShowInterest'] . $PlayerName . ".";
				}
			}else{
				if(isset($_GET['ShowInterest'])){ //Show Interest but already in Table
					$InformationMessage = $TeamInfo['Name'] . $WaiverLang['AlreadyShowInterest'] . $PlayerName . ".";
				}elseif(isset($_GET['RemoveInterest'])){ //Remove Interest, Remove from Table
					$Query = "DELETE FROM WaiverInterest WHERE Player = " . $Player . " AND TeamProNumber = " . $CookieTeamNumber ;
					$db->exec($Query);
					$InformationMessage = $TeamInfo['Name'] . $WaiverLang['InterestIn'] . $PlayerName . $WaiverLang['Remove'];
				}
			}
		}
		
		if(isset($_GET['SendToWaiver'])){
			$Player = filter_var($_GET['SendToWaiver'], FILTER_SANITIZE_NUMBER_INT);
			
			If ($Player > 10000){ //Goalie
				$Query = "SELECT Name FROM GoalerInfo WHERE GoalerInfo.Number = " . $Player - 10000;
				$GoalerInfo = $db->querySingle($Query,true);
				$PlayerName = $GoalerInfo['Name'];
			}elseif ($Player > 0){ //Player
				$Query = "SELECT Name FROM PlayerInfo WHERE PlayerInfo.Number = " . $Player;
				$PlayerInfo = $db->querySingle($Query,true);
				$PlayerName = $PlayerInfo['Name'];
			}

			$Query = "SELECT count(*) AS count FROM WaiverSendTo WHERE Player = " . $Player . " AND TeamProNumber = " . $CookieTeamNumber ;
			$Result = $db->querySingle($Query,true);
			If ($Result['count'] == 0){ //No Interest Found
				$Query = "INSERT INTO WaiverSendTo (Player,TeamProNumber) VALUES('" . $Player . "','" . $CookieTeamNumber . "')";
				$db->exec($Query);
				$InformationMessage =  $PlayerName . $WaiverLang['SendToWaiverByForce'] . $TeamInfo['Name'];
			}else{
				$InformationMessage = $PlayerName . $WaiverLang['AlreadySentWaiver'];
			}
		}
	}else{
		$InformationMessage = $ThisPageNotAvailable;
		$Waiver = Null;
		$WaiverOrder = Null;
		echo "<style>#WaiverMainDiv{display:none}</style>";
	}
} catch (Exception $e) {
STHSErrorWaiver:
	$LeagueName = $DatabaseNotFound;
	$Waiver = Null;
	$WaiverOrder = Null;
}}
echo "<title>" . $LeagueName . " - " . $WaiverLang['Title'] . "</title>";
?>
</head><body>
<?php include "Menu.php";?>
<br />

<?php if ($InformationMessage != ""){echo "<div class=\"STHSDivInformationMessage\">" . $InformationMessage . "<br /><br /></div>\n";}?>
<div id="WaiverMainDiv" style="width:95%;margin:auto;">
<h1><?php echo $WaiverLang['Waiver'];?></h1>
<table class="STHSWaiver_Table"><thead><tr>
<th title="Player"><?php echo $WaiverLang['PlayerName'];?> </th>
<th title="From Team"><?php echo $WaiverLang['FromTeam'];?> </th>
<th title="Picked by"><?php echo $WaiverLang['Pickedby'];?> </th>
<th title="Day Put on Waivers"><?php echo $WaiverLang['DayPutonWaivers'];?> </th>
<th title="Day Removed from Waivers"><?php echo $WaiverLang['DayRemovedfromWaivers'];?> </th>
<th title="Action"><?php echo $WaiverLang['Action'];?> </th>
</tr></thead>
<tbody>
<?php
if (empty($Waiver) == false){while ($Row = $Waiver ->fetchArray()) {
	If ($Row['Player'] > 10000){
		echo "<tr><td><a href=\"GoalieReport.php?Goalie=" . ($Row['Player'] - 10000) . "\">" . $Row['PlayerNameOV'] . "</a></td>";
	}else{
		echo "<tr><td><a href=\"PlayerReport.php?Player=" . $Row['Player'] . "\">" . $Row['PlayerNameOV'] . "</a></td>";
	}
	echo "<td>";
	If ($Row['FromTeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Row['FromTeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPTeamStatsTeamImage\" />";}		
	echo $Row['FromTeamName'] . "</td>";
	echo "<td>";
	If ($Row['ToTeamThemeID'] > 0){echo "<img src=\"" . $ImagesCDNPath . "/images/" . $Row['ToTeamThemeID'] .".png\" alt=\"\" class=\"STHSPHPTeamStatsTeamImage\" />";}		
	echo $Row['ToTeamName'] . "</td>";
	echo "<td>" . $Row['DayPutOnWaiver'] . "</td>";
	echo "<td>" . $Row['DayRemoveFromWaiver'] . "</td>";
	if($CookieTeamNumber > 0 AND $CookieTeamNumber <= 100 AND $Row['ToTeam'] != $CookieTeamNumber AND $Row['FromTeam'] != $CookieTeamNumber){
		$Query = "SELECT count(*) AS count FROM WaiverInterest WHERE Player = " . $Row['Player'] . " AND TeamProNumber = " . $CookieTeamNumber ;
		$Result = $db->querySingle($Query,true);
		If ($Result['count'] == 1){
			echo "<td><a href=\"Waivers.php?RemoveInterest&Player=" . $Row['Player'] . "\">" . $WaiverLang['RemoveInterest']. "</a></td>";
		}else{
			If ($Row['ToTeamName'] > 0){
				/* A Team Already Show Interest in Player, must check Waiver Order */
				$Query = "SELECT WaiverOrder.* FROM WaiverOrder ORDER BY WaiverOrder.Number";
				$WaiverOrderCheck = $db->query($Query);
				if (empty($WaiverOrder) == false){while ($RowOrder = $WaiverOrderCheck ->fetchArray()) {
					if ($RowOrder['Number'] == $Row['ToTeam']){
						echo "<td>N/A</td>";
						break;
					}elseif($RowOrder['Number'] == $CookieTeamNumber){
						echo "<td><a href=\"Waivers.php?ShowInterest&Player=" . $Row['Player'] . "\">" . $WaiverLang['ShowInterest']. "</a></td>";
						break;
					}
				}}
			}else{
				echo "<td><a href=\"Waivers.php?ShowInterest&Player=" . $Row['Player'] . "\">" . $WaiverLang['ShowInterest']. "</a></td>";
			}
		}
	}else{echo "<td>N/A</td>";}
	echo "</tr>\n"; /* The \n is for a new line in the HTML Code */
}}
?>
</tbody></table>
<br />
<table class="STHSWaiver_Table2"><tr><td>
<h1><?php echo $WaiverLang['WaiverOrder'];?></h1>
<?php
if (empty($WaiverOrder) == false){while ($Row = $WaiverOrder ->fetchArray()) {
	echo $Row['Number'] . " - " . $Row['Name'];
	echo "<br />\n"; /* The \n is for a new line in the HTML Code */
}}
?>
<?php
if($CookieTeamNumber > 0 AND $CookieTeamNumber <= 100 AND $LeagueSimulation['WaiversEnable'] == "True"){
	$Query = "Select Name from TeamProInfo Where Number = " . $CookieTeamNumber;
	$TeamInfo =  $db->querySingle($Query,true);
	
	echo "</td><td><br /><h1>" . $WaiverLang['ForcePlayerWaiver'] ." for " . $TeamInfo['Name'] . "</h1><form action=\"Waivers.php\" method=\"get\"><table class=\"STHSTable\"><tr><td class=\"STHSW200\"><strong>" . $PlayersLang['PlayerName'] . "</strong></td><td class=\"STHSW250\">\n";
	echo "<select name=\"SendToWaiver\" class=\"STHSSelect STHSW250\">\n";
	$Query = "SELECT MainTable.* FROM (SELECT PlayerInfo.Number, PlayerInfo.Name, PlayerInfo.Retire as Retire FROM PlayerInfo Where Team =" . $CookieTeamNumber . " UNION ALL SELECT GoalerInfo.Number + 10000, GoalerInfo.Name, GoalerInfo.Retire as Retire FROM GoalerInfo Where Team =" . $CookieTeamNumber . " ) AS MainTable ORDER BY MainTable.Name";
	If (isset($db)){$PlayerNameSearch = $db->query($Query);}
	if (empty($PlayerNameSearch ) == false){while ($Row = $PlayerNameSearch  ->fetchArray()) {
		echo "<option value=\"" . $Row['Number'] . "\">" . $Row['Name'] . "</option>\n"; 
	}
	echo "</select>";
	echo "<tr><td colspan=\"2\" class=\"STHSCenter\"><input type=\"submit\" class=\"SubmitButton STHSCenter\" value=\"" . $SearchLang['Submit'] . $WaiverLang['SendToWaiverWarning'] . "\"></td></tr></table></form>\n";
	}
}
?>
</td></tr></table>
<br />
</div>

<?php include "Footer.php";?>
