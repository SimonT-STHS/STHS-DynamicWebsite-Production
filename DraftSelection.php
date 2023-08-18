<?php include "Header.php";
If ($lang == "fr"){include 'LanguageFR-Main.php';}else{include 'LanguageEN-Main.php';}
$InformationMessage = "";
$EntryDrafAvailable = Null;
$EntryDrafSelect = Null;
$FantasyDrafAvailable = Null;
$FantasyDrafSelect = Null;
$Title = (string)"";
$EntryDraft = (boolean)false;
$FantasyDraft = (boolean)false;

If (file_exists($DatabaseFile) == false){
	Goto STHSErrorEntryDraft;
}else{try{
	$LeagueName = (string)"";
	$db = new SQLite3($DatabaseFile);
	
	$Query = "Select Name, EntryDraftStart, EntryDraftStop, FantasyDraftStart,OffSeason from LeagueGeneral";
	$LeagueGeneral = $db->querySingle($Query,true);		
	$LeagueName = $LeagueGeneral['Name'];
	
	$Query = "Select AllowDraftSelectionfromWebsite from LeagueWebClient";
	$LeagueWebClient = $db->querySingle($Query,true);		
	
	if(isset($_GET['EntryDraft'])){
		if ($LeagueGeneral['OffSeason'] == "True" AND $LeagueGeneral['EntryDraftStart'] == "True" AND $LeagueGeneral['EntryDraftStop'] == "False" AND $LeagueGeneral['FantasyDraftStart'] == "False"){$EntryDraft = True;} 
	}elseif(isset($_GET['FantasyDraft'])){
		if ($LeagueGeneral['OffSeason'] == "True" AND $LeagueGeneral['EntryDraftStart'] == "False" AND $LeagueGeneral['EntryDraftStop'] == "False" AND $LeagueGeneral['FantasyDraftStart'] == "True"){$FantasyDraft = True;}
	} 	
	
	If ($CookieTeamNumber > 0 AND $CookieTeamNumber <= 100 AND $LeagueWebClient['AllowDraftSelectionfromWebsite'] == "True"){
		$Query = "SELECT Name FROM TeamProInfo WHERE Number = " . $CookieTeamNumber;
		$TeamInfo = $db->querySingle($Query,true);
		
		// Confirm Submit Offer Team Match Cookie
		if(isset($_POST['DraftSelectionTeamNumber'])){$Team = filter_var($_POST['DraftSelectionTeamNumber'], FILTER_SANITIZE_NUMBER_INT);}		
		if ($Team == $CookieTeamNumber AND ($EntryDraft == True OR $FantasyDraft == True)){
			if(isset($_POST['DraftSelection'])){
				$DraftSelection = explode("+", filter_var($_POST['DraftSelection'], FILTER_SANITIZE_NUMBER_INT),-1);
				if (empty($DraftSelection) == false){
					try {
						// Delete Previous Draft
						$Query = "DELETE from DraftSelection WHERE FromTeam = " . $Team;
						$db->exec($Query);
						
						// Update New Draft Order
						$intCount = 0;
						foreach ($DraftSelection as $value){
							$intCount = $intCount + 1;
							$Query = "INSERT INTO DraftSelection(FromTeam,SelectionNumber,SelectionOrder) VALUES('" . $Team . "','" . $value . "','" . $intCount . "')";
							$db->exec($Query);
							If ($intCount >= 250){
								$InformationMessage = $DraftSelectionLang['FantasyDraftSelection'];
								break;
							}
						}	
						If ($intCount < 250){ $InformationMessage = $DraftSelectionLang['DraftSelectionUpdate'] . $TeamInfo['Name'];}
					} catch (Exception $e) {
						echo $DraftSelectionLang['DraftSelectionFail'];
					}							
				}				
			}elseif(isset($_POST['EraseDraftSelection'])){
					try {
						// Delete Previous Draft
						$Query = "DELETE from DraftSelection WHERE FromTeam = " . $Team;
						$db->exec($Query);	
						$InformationMessage = $DraftSelectionLang['DraftSelectionUpdate'] . $TeamInfo['Name'];
					} catch (Exception $e) {
						echo $DraftSelectionLang['DraftSelectionFail'];
					}
			}
		}
			
		if ($EntryDraft == True){ // Entry Draft
			
			// Player not Select by Team
			$Query = "SELECT EntryDraftProspectAvailable.*, DraftSelection.* FROM EntryDraftProspectAvailable LEFT JOIN DraftSelection ON EntryDraftProspectAvailable.Number = DraftSelection.SelectionNumber AND DraftSelection.FromTeam = " . $CookieTeamNumber . " ORDER BY ProspectName";
			$EntryDrafAvailable = $db->query($Query);			
			
			// Player Select by Team
			$Query = "SELECT EntryDraftProspectAvailable.*, DraftSelection.* FROM EntryDraftProspectAvailable JOIN DraftSelection ON EntryDraftProspectAvailable.Number = DraftSelection.SelectionNumber AND DraftSelection.FromTeam = " . $CookieTeamNumber . " ORDER BY SelectionOrder";
			$EntryDrafSelect = $db->query($Query);
			
			echo "<title>" . $LeagueName . " - " . $DraftSelectionLang['EntryDraftSelection'] . "</title>";

		}elseif($FantasyDraft == True){ // Fantasy Draft
			$Query = "SELECT MainTable.*, DraftSelection.* FROM (SELECT PlayerInfo.Number, PlayerInfo.Name, PlayerInfo.Overall, PlayerInfo.PosC, PlayerInfo.PosLW, PlayerInfo.PosRW, PlayerInfo.PosD, 'False' AS PosG FROM PlayerInfo WHERE Team = 0 AND Retire = 'False' AND Number > 0 UNION ALL SELECT GoalerInfo.Number + 10000, GoalerInfo.Name, GoalerInfo.Overall, 'False' AS PosC, 'False' AS PosLW, 'False' AS PosRW, 'False' AS PosD, 'True' AS PosG FROM GoalerInfo WHERE Team = 0 AND Retire = 'False' AND Number > 0) AS MainTable LEFT JOIN DraftSelection ON MainTable.Number = DraftSelection.SelectionNumber AND DraftSelection.FromTeam = " . $CookieTeamNumber . " ORDER BY Overall DESC";
			$FantasyDrafAvailable = $db->query($Query);
			
			$Query = "SELECT MainTable.*, DraftSelection.* FROM (SELECT PlayerInfo.Number, PlayerInfo.Name, PlayerInfo.Overall, PlayerInfo.PosC, PlayerInfo.PosLW, PlayerInfo.PosRW, PlayerInfo.PosD, 'False' AS PosG FROM PlayerInfo WHERE Team = 0 AND Retire = 'False' AND Number > 0 UNION ALL SELECT GoalerInfo.Number + 10000, GoalerInfo.Name, GoalerInfo.Overall, 'False' AS PosC, 'False' AS PosLW, 'False' AS PosRW, 'False' AS PosD, 'True' AS PosG FROM GoalerInfo WHERE Team = 0 AND Retire = 'False' AND Number > 0) AS MainTable JOIN DraftSelection ON MainTable.Number = DraftSelection.SelectionNumber AND DraftSelection.FromTeam = " . $CookieTeamNumber . " ORDER BY Overall DESC";	
			$FantasyDrafSelect = $db->query($Query);
			
			echo "<title>" . $LeagueName . " - " . $DraftSelectionLang['FantasyDraftSelection'] . "</title>";
		
		}else{
			$InformationMessage = $ThisPageNotAvailable;
			echo "<style>#DraftSelectionMainDiv{display:none}</style>";
			echo "<title>" . $LeagueName .  "</title>";
		}
	}else{
		$InformationMessage = $ThisPageNotAvailable;
		echo "<style>#DraftSelectionMainDiv{display:none}</style>";
		echo "<title>" . $LeagueName .  "</title>";
	}

	
} catch (Exception $e) {
STHSErrorEntryDraft:
	$LeagueName = $DatabaseNotFound;
	$EntryDrafAvailable = Null;
	$EntryDrafSelect = Null;
	$FantasyDrafAvailable = Null;
	$FantasyDrafSelect = Null;	
	echo "<title>" . $DatabaseNotFound ."</title>";
}}?>
<script type="text/javascript">
	$(document).ready(function(){
		var dsl = $('#DraftSelection').DualSelectList({

			// Change Item from pure String to an Json Object.
			// the "value" field will be displayed on the list.
			'candidateItems' : [
<?php
if ($EntryDraft == True){if (empty($EntryDrafAvailable) == false){while ($row = $EntryDrafAvailable ->fetchArray()) { 
	If (isset($row['FromTeam'])){}else{echo "{'id':" . $row['Number'] . ", 'value':'" . str_replace("'","\'",$row['ProspectName']) . "'},\n";}
}}}
if ($FantasyDraft == True){if (empty($FantasyDrafAvailable) == false){while ($row = $FantasyDrafAvailable ->fetchArray()) { 
	If (isset($row['FromTeam'])){}else{
		$Position = (string)"";
		if ($row['PosC']== "True"){if ($Position == ""){$Position = "C";}else{$Position = $Position . "/C";}}
		if ($row['PosLW']== "True"){if ($Position == ""){$Position = "LW";}else{$Position = $Position . "/LW";}}
		if ($row['PosRW']== "True"){if ($Position == ""){$Position = "RW";}else{$Position = $Position . "/RW";}}
		if ($row['PosD']== "True"){if ($Position == ""){$Position = "D";}else{$Position = $Position . "/D";}}
		if ($row['PosG']== "True"){if ($Position == ""){$Position = "G";}}
		echo "{'id':" . $row['Number'] . ", 'value':'" . str_replace("'","\'",$row['Name']) . " - " . $Position . " - " . $row['Overall'] . "'},\n";
	}
}}}
?>
								],
			'selectionItems' : [
<?php
if ($EntryDraft == True){if (empty($EntryDrafSelect) == false){while ($row = $EntryDrafSelect ->fetchArray()) { 
	echo "{'id':" . $row['Number'] . ", 'value':'" . str_replace("'","\'",$row['ProspectName']) . "'},\n";
}}}
if ($FantasyDraft == True){if (empty($FantasyDrafSelect) == false){while ($row = $FantasyDrafSelect ->fetchArray()) { 
	$Position = (string)"";
	if ($row['PosC']== "True"){if ($Position == ""){$Position = "C";}else{$Position = $Position . "/C";}}
	if ($row['PosLW']== "True"){if ($Position == ""){$Position = "LW";}else{$Position = $Position . "/LW";}}
	if ($row['PosRW']== "True"){if ($Position == ""){$Position = "RW";}else{$Position = $Position . "/RW";}}
	if ($row['PosD']== "True"){if ($Position == ""){$Position = "D";}else{$Position = $Position . "/D";}}
	if ($row['PosG']== "True"){if ($Position == ""){$Position = "G";}}
	echo "{'id':" . $row['Number'] . ", 'value':'" . str_replace("'","\'",$row['Name']) . " - " . $Position . " - " . $row['Overall'] . "'},\n";
}}}
?>
			],
			'colors' : {
				'itemText' : 'black',
			}
		});

		$('#getSel').click(function(){
			var strOnly = $('#strOnly').prop("checked");
			var res = dsl.getSelection(strOnly);
			var str = '';
			for (var n=0; n<res.length; ++n) str += (res[n].id) + '+';
			$('#selResult').val(str);
		});
	});
</script>
</head><body>
<?php include "Menu.php";?>

<?php if ($InformationMessage != ""){echo "<div class=\"STHSDivInformationMessage\">" . $InformationMessage . "<br /><br /></div>";}?>
<div id="DraftSelectionMainDiv" style="width:99%;margin:auto;">
<?php 
if ($EntryDraft == True){
	echo "<h1>" . $DraftSelectionLang['EntryDraftSelection']. "</h1>"; 
}elseif($FantasyDraft == True){
	echo "<h1>" . $DraftSelectionLang['FantasyDraftSelection']. "</h1>"; 
}?>
<br />
<div class="STHSPHPDraftSelectionTitle"><?php if ($EntryDraft == True){echo $DraftSelectionLang['AvailablesProspects'];}elseif($FantasyDraft == True){echo $DraftSelectionLang['AvailablesPlayers'];}?></div><div class="STHSPHPDraftSelectionTitle"><?php echo $DraftSelectionLang['Selection'];?></div>
<div id="DraftSelection" class="STHSPHPDraftSelectionMain"></div><br>
<table><tr><td style="padding:20px">
<form action="DraftSelection.php<?php If($EntryDraft == True){echo "?EntryDraft";}elseif($FantasyDraft == True){echo "?FantasyDraft";} If ($lang == "fr"){echo "&Lang=fr";}?>" method="post">
<input type="hidden" id="selResult" name="DraftSelection">
<input type="hidden" name="DraftSelectionTeamNumber" value="<?php echo $CookieTeamNumber;?>">
<input id="getSel" type="submit" class="SubmitButton" value="<?php echo $DraftSelectionLang['SubmitSelection'];?>">
</form>
</td><td style="padding:20px">
<form action="DraftSelection.php<?php If($EntryDraft == True){echo "?EntryDraft";}elseif($FantasyDraft == True){echo "?FantasyDraft";} If ($lang == "fr"){echo "&Lang=fr";}?>" method="post">
<input type="hidden" value="EraseDraftSelection" name="EraseDraftSelection">
<input type="hidden" name="DraftSelectionTeamNumber" value="<?php echo $CookieTeamNumber;?>">
<input type="submit" class="SubmitButton" value="<?php echo $DraftSelectionLang['EraseSelection'];?>">
</form>
</td></tr></table>
</div>

<?php include "Footer.php";?>
