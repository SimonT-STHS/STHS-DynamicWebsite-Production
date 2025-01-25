<?php
require_once "STHSSetting.php";
$EmptyReturn = (string)"Backend Error";

If (file_exists($DatabaseFile) == false){
	echo $EmptyReturn;
}elseif($CookieTeamNumber > 0 AND $CookieTeamNumber <= 102){
	$db = new SQLite3($DatabaseFile);
	
	$Query = "Select AllowProspectEditionFromWebsite, AllowPlayerEditionFromWebsite, AllowFreeAgentOfferfromWebsite, MinimumFreeAgentsOffer, MaxFreeAgentOffer from LeagueWebClient";
	$LeagueWebClient = $db->querySingle($Query,true);

	// Prospects Edit
	if(isset($_POST['EditProspectNumber']) AND $LeagueWebClient['AllowProspectEditionFromWebsite'] == "True"){
		If ($lang == "fr"){include 'LanguageFR-League.php';}else{include 'LanguageEN-League.php';}
		
		$ProspectNumber = (integer)0;
		$ProspectName = (string)"";	
		$ProspectYear = (integer)0;
		$ProspectOverallPick = (integer)0;
		$ProspectInformation = (string)"";
		$ProspectLink = (string)"";
		if(isset($_POST['EditProspectNumber'])){$ProspectNumber = filter_var($_POST['EditProspectNumber'], FILTER_SANITIZE_NUMBER_INT);} 		
		if(isset($_POST['Year'])){$ProspectYear = filter_var($_POST['Year'], FILTER_SANITIZE_NUMBER_INT, FILTER_SANITIZE_NUMBER_INT);} If (empty($ProspectYear)){$ProspectYear =0 ;}
		if(isset($_POST['OverallPick'])){$ProspectOverallPick = filter_var($_POST['OverallPick'], FILTER_SANITIZE_NUMBER_INT);} If (empty($ProspectOverallPick)){$ProspectOverallPick =0 ;}
		if(isset($_POST['Information'])){$ProspectInformation  = filter_var($_POST['Information'], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH || FILTER_FLAG_NO_ENCODE_QUOTES || FILTER_FLAG_STRIP_BACKTICK);}	
		if(isset($_POST['Hyperlink'])){$ProspectLink = filter_var($_POST['Hyperlink'], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH || FILTER_FLAG_NO_ENCODE_QUOTES || FILTER_FLAG_STRIP_BACKTICK);}	
		if ($ProspectNumber > 0){try {
			$Query = "SELECT Name, Number from Prospects WHERE Number = " . $ProspectNumber;
			$ProspectQuery = $db->querySingle($Query,true);
			
			$Query = "Update Prospects SET Year = '" . $ProspectYear . "', OverallPick = '" . $ProspectOverallPick . "', Information = '" . str_replace("'","''",$ProspectInformation) . "', URLLink = '" . str_replace("'","''",$ProspectLink). "', WebClientModify = 'True' WHERE Number = " . $ProspectNumber;
			$db->exec($Query);
			
			echo $PlayersLang['EditConfirm'] . $ProspectQuery['Name'];
		} catch (Exception $e) {
			echo $ProspectsLang['EditFail'];
		}}else{
			echo $ProspectsLang['EditFail'];
		}	
	}
	
	// Players Edit
	if(isset($_POST['EditPlayerNumber']) AND $LeagueWebClient['AllowPlayerEditionFromWebsite'] == "True"){
		$PlayerNumber = (integer)0;
		$PlayerName = (string)"";	
		$PlayerDraftYear = (integer)0;
		$PlayerDraftOverallPick = (integer)0;
		$PlayerNHLID = (integer)0;
		$PlayerJersey = (integer)0;
		$PlayerLink = (string)"";
	
		if(isset($_POST['EditPlayerNumber'])){$PlayerNumber = filter_var($_POST['EditPlayerNumber'], FILTER_SANITIZE_NUMBER_INT);} 
		if(isset($_POST['PlayerName'])){$PlayerName =  filter_var($_POST['PlayerName'], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH || FILTER_FLAG_NO_ENCODE_QUOTES || FILTER_FLAG_STRIP_BACKTICK);}
		if(isset($_POST['DraftYear'])){$PlayerDraftYear = filter_var($_POST['DraftYear'], FILTER_SANITIZE_NUMBER_INT, FILTER_SANITIZE_NUMBER_INT);} If (empty($PlayerDraftYear)){$PlayerDraftYear =0 ;}
		if(isset($_POST['DraftOverallPick'])){$PlayerDraftOverallPick = filter_var($_POST['DraftOverallPick'], FILTER_SANITIZE_NUMBER_INT);} If (empty($PlayerDraftOverallPick)){$PlayerDraftOverallPick =0 ;}
		if(isset($_POST['NHLID'])){$PlayerNHLID = filter_var($_POST['NHLID'], FILTER_SANITIZE_NUMBER_INT);} If (empty($PlayerNHLID)){$PlayerNHLID ="" ;}
		if(isset($_POST['Jersey'])){$PlayerJersey = filter_var($_POST['Jersey'], FILTER_SANITIZE_NUMBER_INT);} If (empty($PlayerJersey)){$PlayerJersey =0 ;}
		if(isset($_POST['Hyperlink'])){$PlayerLink = filter_var($_POST['Hyperlink'], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH || FILTER_FLAG_NO_ENCODE_QUOTES || FILTER_FLAG_STRIP_BACKTICK);}	
		try {
			If ($PlayerNumber > 0 and $PlayerNumber <= 10000){
				$Query = "SELECT Name, Number from PlayerInfo WHERE Number = " . $PlayerNumber;
				$PlayerQuery = $db->querySingle($Query,true);
				
				$Query = "Update PlayerInfo SET DraftYear = '" . $PlayerDraftYear . "', DraftOverallPick = '" . $PlayerDraftOverallPick . "', NHLID = '" . $PlayerNHLID . "', Jersey = '" . $PlayerJersey  . "', URLLink = '" . str_replace("'","''",$PlayerLink). "', WebClientModify = 'True' WHERE Number = " . $PlayerNumber;
				$db->exec($Query);
				
				echo $PlayersLang['EditConfirm'] . $PlayerQuery['Name'];
			}elseif($PlayerNumber > 10000 and $PlayerNumber <= 11000){
				$Query = "SELECT Name, Number from GoalerInfo WHERE Number = " . $PlayerNumber;
				$PlayerQuery = $db->querySingle($Query,true);					
				
				$Query = "Update GoalerInfo SET DraftYear = '" . $PlayerDraftYear . "', DraftOverallPick = '" . $PlayerDraftOverallPick . "', NHLID = '" . $PlayerNHLID . "', Jersey = '" . $PlayerJersey  . "', NHLID = '" . $PlayerNHLID . "', URLLink = '" . str_replace("'","''",$PlayerLink). "', WebClientModify = 'True' WHERE Number = " . ($PlayerNumber - 10000);
				$db->exec($Query);
				
				echo $PlayersLang['EditConfirm'] . $PlayerQuery['Name'];;
			}else{
				echo $PlayersLang['EditFail'];
			}
		} catch (Exception $e) {
			echo $PlayersLang['EditFail'];
		}	
	}
	
	//Free Agents Offer
	If ($CookieTeamNumber <= 100 AND $LeagueWebClient['AllowFreeAgentOfferfromWebsite'] == "True" AND ((isset($_POST['EraseFreeAgentOfferPlayerNumber']) OR (isset($_POST['UpdateFreeAgentOfferPlayerNumber']))))){
		$InformationMessage = (string)"";	
		$MinimumSalary = (integer)0;	
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
			
		$Query = "Select MaxContractDuration, PlayerMinimumSalary, PlayerMaxSalary from LeagueFinance";
		$LeagueFinance = $db->querySingle($Query,true);				

		If ($LeagueFinance['PlayerMinimumSalary'] >= $LeagueWebClient['MinimumFreeAgentsOffer']){$MinimumSalary = $LeagueFinance['PlayerMinimumSalary'];}else{$MinimumSalary = $LeagueWebClient['MinimumFreeAgentsOffer'];}
			
		$Query = "SELECT WebMaxFreeAgentOffer FROM TeamProInfo WHERE Number = " . $CookieTeamNumber;
		$TeamInfo = $db->querySingle($Query,true);
		$WebMaxFreeAgentOffer = $TeamInfo['WebMaxFreeAgentOffer'];
		
		$Query = "SELECT Count(FromTeam) as CountNumber FROM FreeAgentOffers WHERE FromTeam = " . $CookieTeamNumber;
		$Result = $db->querySingle($Query,true);
		If ($Result['CountNumber'] > 0){$WebMaxFreeAgentOffer = $WebMaxFreeAgentOffer - $Result['CountNumber'];}
		If (isset($_POST['EraseFreeAgentOfferPlayerNumber'])){ // Delete Previous Offer if Exist
			if(isset($_POST['EraseFreeAgentOfferPlayerNumber'])){$PlayerNumber = filter_var($_POST['EraseFreeAgentOfferPlayerNumber'], FILTER_SANITIZE_NUMBER_INT);} 
			If ($PlayerNumber > 0 and $PlayerNumber <= 10000){
				$Query = "SELECT Name, Number from PlayerInfo WHERE Number = " . $PlayerNumber;
				$PlayerQuery = $db->querySingle($Query,true);
				$PlayerName = $PlayerQuery['Name'];
			}elseif($PlayerNumber > 10000 and $PlayerNumber <= 11000){
				$Query = "SELECT Name, Number from GoalerInfo WHERE Number = " . ($PlayerNumber - 10000);
				$PlayerQuery = $db->querySingle($Query,true);					
				$PlayerName = $PlayerQuery['Name'];
			}else{
				$PlayerName = "Player Error";
			}			

			$Query = "SELECT Count(FromTeam) as CountNumber FROM FreeAgentOffers WHERE FromTeam = " . $CookieTeamNumber . " AND PlayerNumber = " . $PlayerNumber;
			$Result = $db->querySingle($Query,true);
			If ($Result['CountNumber'] > 0){				
				$Query = "DELETE from FreeAgentOffers WHERE FromTeam = " . $CookieTeamNumber . " AND PlayerNumber = " . $PlayerNumber;

				try {
					$db->exec($Query);
					$WebMaxFreeAgentOffer = $WebMaxFreeAgentOffer + 1; // Raise the number because we delete previous offer who was counting.
					$InformationMessage = $PlayersLang['FreeAgentDeleteOffer'] . $PlayerName;
				} catch (Exception $e) {
					$InformationMessage = $PlayersLang['FreeAgentFailOffer'];
				}
			}else{
				$InformationMessage = $PlayersLang['NoOffertoDelete'] . $PlayerName;
			}
			echo $InformationMessage . "@" . $WebMaxFreeAgentOffer;
		}
		If (isset($_POST['UpdateFreeAgentOfferPlayerNumber']) ){ //Create New Offer
			If ($WebMaxFreeAgentOffer > 0){
				if(isset($_POST['UpdateFreeAgentOfferPlayerNumber'])){$PlayerNumber = filter_var($_POST['UpdateFreeAgentOfferPlayerNumber'], FILTER_SANITIZE_NUMBER_INT);} 				
				if(isset($_POST['SalaryOffer'])){$SalaryOffer = filter_var($_POST['SalaryOffer'], FILTER_SANITIZE_NUMBER_INT);} 
				if(isset($_POST['DurationOffer'])){$DurationOffer = filter_var($_POST['DurationOffer'], FILTER_SANITIZE_NUMBER_INT);} 
				if(isset($_POST['BonusOffer'])){$BonusOffer = filter_var($_POST['BonusOffer'], FILTER_SANITIZE_NUMBER_INT);} 
				if(isset($_POST['CommentOffer'])){$CommentOffer = filter_var($_POST['CommentOffer'], FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW || FILTER_FLAG_STRIP_HIGH || FILTER_FLAG_NO_ENCODE_QUOTES || FILTER_FLAG_STRIP_BACKTICK);}			
				if(isset($_POST['CanPlayPro'])) {$CanPlayPro = "True";}
				if(isset($_POST['CanPlayFarm'])){$CanPlayFarm = "True";}
				if(isset($_POST['ProSalaryinFarm'])){$ProSalaryinFarm = "True";}
				if(isset($_POST['NoTrade'])){$NoTrade = "True";}
				if ($CanPlayPro == "False" AND $CanPlayFarm = "False"){$CanPlayPro = "True";$CanPlayFarm = "True";}
				If ($PlayerNumber > 0 and $PlayerNumber <= 10000){
					$Query = "SELECT Name, Number from PlayerInfo WHERE Number = " . $PlayerNumber;
					$PlayerQuery = $db->querySingle($Query,true);
					$PlayerName = $PlayerQuery['Name'];
				}elseif($PlayerNumber > 10000 and $PlayerNumber <= 11000){
					$Query = "SELECT Name, Number from GoalerInfo WHERE Number = " . ($PlayerNumber - 10000);
					$PlayerQuery = $db->querySingle($Query,true);					
					$PlayerName = $PlayerQuery['Name'];
				}else{
					$PlayerName = "Player Error";
				}					
				
				// Verify Offer Validy
				If ($SalaryOffer >= $MinimumSalary AND $SalaryOffer <= $LeagueFinance['PlayerMaxSalary'] ANd $DurationOffer > 0 AND $DurationOffer <= $LeagueFinance['MaxContractDuration'] And $PlayerNumber > 0 and $PlayerNumber <= 11000){
					// Delete Previous Offer if Exist
					$Query = "SELECT Count(FromTeam) as CountNumber FROM FreeAgentOffers WHERE FromTeam = " . $CookieTeamNumber . " AND PlayerNumber = " . $PlayerNumber;
					$Result = $db->querySingle($Query,true);
					If ($Result['CountNumber'] > 0){				
						$Query = "DELETE from FreeAgentOffers WHERE FromTeam = " . $CookieTeamNumber . " AND PlayerNumber = " . $PlayerNumber;
						try {
							$db->exec($Query);
							$WebMaxFreeAgentOffer = $WebMaxFreeAgentOffer + 1; // Raise the number because we delete previous offer who was counting.
						} catch (Exception $e) {
							$InformationMessage = $PlayersLang['FreeAgentFailOffer'];
						}						
					}

					// Save Offer
					$Query = "INSERT INTO FreeAgentOffers (FromTeam,PlayerNumber,SalaryOffer,DurationOffer,BonusOffer,CommentOffer,OfferDate,NoTradeOffer,CanPlayProOffer,CanPlayFarmOffer,ProSalaryinFarm1WayContractOffer) VALUES('" . $CookieTeamNumber . "','" . $PlayerNumber . "','" . $SalaryOffer . "','" . $DurationOffer . "','" . $BonusOffer . "','" . str_replace("'","''",$CommentOffer) . "','" . date("Y-m-d H:i:s")  . "','" . $NoTrade . "','" . $CanPlayPro . "','" . $CanPlayFarm . "','" . $ProSalaryinFarm . "')";
					try {
						$db->exec($Query);
						$InformationMessage =$PlayersLang['FreeAgentConfirmOffer'] . $PlayerName;
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
		echo $InformationMessage . "@" . $WebMaxFreeAgentOffer;
		}	
	}
}
?>