function roster_validator(	MaximumPlayerPerTeam,MinimumPlayerPerTeam,isWaivers,BlockSenttoFarmAfterTradeDeadline,isPastTradeDeadline,ProTeamEliminatedCannotSendPlayerstoFarm,isEliminated,ForceCorrect10LinesupbeforeSaving,
							ProMinC,ProMinLW,ProMinRW,ProMinD,ProMinForward,ProGoalerInGame,ProPlayerInGame,ProPlayerLimit,
							FarmMinC,FarmMinLW,FarmMinRW,FarmMinD,FarmMinForward,FarmGoalerInGame,FarmPlayerInGame,FarmPlayerLimit,MaxFarmOv,MaxFarmOvGoaler,GamesLeft){
	
	// Declare variables needed inside the loop. Set to Null
	var explode, status, proPlayerLimit, farmPlayerLimit, playerProToFarmTradeDeadline, playerProToFarmEliminated, playerProToFarmOverall;

	// Declare variables with a default value of empty text or 0
	var playerCount = 0, waiverCount = 0;
	var errorText = '', waiverText = '';

	// Declare array variables for counting during the loops
	var proC = [], proLW = [], proRW = [], proD = [], proG = [], proF = []; 
	var proDressC = [], proDressLW = [], proDressRW = [], proDressD = [], proGoalerInGame = [], proDressF = [];

	var farmC = [], farmLW = [], farmRW = [], farmD = [], farmG = [], farmF = [];
	var farmDressC = [], farmDressLW = [], farmDressRW = [], farmDressD = [], farmGoalerInGame = [], farmDressF = [];

	// Declare variables for validation.
	var validated = true;
	var lineValidated = [false,false,false,false,false,false,false,false,false,false];
	var waiverList = [];
	// Loop through how many games are left up to 10.
	// Only 1 if no schedule.
	for(g=1;g<=GamesLeft;g++){
		// Get the players from the current gameleft. And set the error element to display errors.
		// Reset error text for each gameleft. 
		
		var players = document.getElementsByClassName( 'rosterline'+g ), res  = {}, i;
		errorElement = document.getElementById("rostererror"+g);
		errorText = '';
		
		// Reset count of pro and farm positions
		proC[g] = 0; proLW[g] = 0; proRW[g] = 0; proD[g] = 0; proG[g] = 0; proF[g] = 0;
		proDressC[g] = 0;  proDressLW[g] = 0; proDressRW[g] = 0; proDressD[g] = 0; proGoalerInGame[g] = 0; proDressF[g] = 0;
		proPlayerLimit = 0;
		
		farmC[g] = 0; farmLW[g] = 0; farmRW[g] = 0; farmD[g] = 0; farmG[g] = 0; farmF[g] = 0;
		farmDressC[g] = 0; farmDressLW[g] = 0; farmDressRW[g] = 0; farmDressD[g] = 0; farmGoalerInGame[g] = 0; farmDressF[g] = 0;
		farmPlayerLimit = 0;

		// Reset other variables.
		playerProToFarmTradeDeadline = 0;
		playerProToFarmEliminated = 0;
		playerProToFarmOverall = 0;

		// Loop through each player
		for (x = 0; x < players.length; x++) {
			// Split the value at the pipe "|" and use each section for checking.
		    explode = players[x].value.split("|");

		    // If the explode array only has 2 sections then its a change in which status we are going through.
		    if(explode.length == 2){
		    	if(explode[1] == "ProDress"){status = 3;}
		    	else if (explode[1] == "ProScratch"){status = 2;}
		    	else if (explode[1] == "FarmDress"){status = 1;}
		    	else{status = 0;}
		    // Else its a player and increment variables where needed
		    }else{
		    	if(g == 1){
		    		playerCount++;
		    	}
		    	
		    	// Add information for Centres
		    	// explode[2] is PositionNumber passed through from the SQLite record. ie 1=centre 16 = goalie 
	    		if(explode[2] == 1 || explode[2] == 2 || explode[2] == 3 || explode[2] == 4 || explode[2] == 5 || explode[2] == 6 || explode[2] == 7 || explode[2] == 8){
	    			if(status == 3){proC[g]++;proDressC[g]++;}
	    			else if(status == 2){proC[g]++;}
	    			else if(status == 1){farmC[g]++;farmDressC[g]++;}
	    			else{farmC[g]++;}
	    		}
	    		// Add information for Left Wings
	    		if(explode[2] == 2 || explode[2] == 4 || explode[2] == 6 || explode[2] == 8 || explode[2] == 9 || explode[2] == 10 || explode[2] == 11 || explode[2] == 12){
	    			if(status == 3){proLW[g]++;proDressLW[g]++;}
	    			else if(status == 2){proLW[g]++;}
	    			else if(status == 1){farmLW[g]++;farmDressLW[g]++;}
	    			else{farmLW[g]++;}
	    		}
	    		// Add information for Right Wings
	    		if(explode[2] == 3 || explode[2] == 4 || explode[2] == 7 || explode[2] == 8 || explode[2] == 10 || explode[2] == 12 || explode[2] == 13 || explode[2] == 14){
	    			if(status == 3){proRW[g]++;proDressRW[g]++;}
	    			else if(status == 2){proRW[g]++;}
	    			else if(status == 1){farmRW[g]++;farmDressRW[g]++;}
	    			else{farmRW[g]++;}
	    		}
	    		// Add information for Defense
	    		if(explode[2] == 5 || explode[2] == 6 || explode[2] == 7 || explode[2] == 8 || explode[2] == 11 || explode[2] == 12 || explode[2] == 14 || explode[2] == 15){
	    			if(status == 3){proD[g]++;proDressD[g]++;}
	    			else if(status == 2){proD[g]++;}
	    			else if(status == 1){farmD[g]++;farmDressD[g]++;}
	    			else{farmD[g]++;}
	    		}
	    		// Add information for Forwards
	    		if(explode[2] == 1 || explode[2] == 2 || explode[2] == 3 || explode[2] == 4 || explode[2] == 9 || explode[2] == 10 || explode[2] == 13){
	    			if(status == 3){proF[g]++;proDressF[g]++;}
	    			else if(status == 2){proF[g]++;}
	    			else if(status == 1){farmF[g]++;farmDressF[g]++;}
	    			else{farmF[g]++;}
	    		}
		    	// Add information for Goalies
		    	if(explode[2] == 16){
		    		if(status == 3){proG[g]++;proGoalerInGame[g]++;}
		    		else if(status == 2){proG[g]++;}
		    		else if(status == 1){farmG[g]++;farmGoalerInGame[g]++;}
		    		else{farmG[g]++;}
	    		}

	    		// If flagged properly and trying to send to farm, not allowed if sending player to the farm after trade deadline 
	    		if(BlockSenttoFarmAfterTradeDeadline == "true" && isPastTradeDeadline == "true" && explode[4] >= 2 && status <= 1){playerProToFarmTradeDeadline++;}
	    		// If flagged properly and trying to send to farm, not allowed if sending player to the farm if eliminated from the playoffs
	    		if(ProTeamEliminatedCannotSendPlayerstoFarm == "true" && isEliminated == "true" && explode[4] >= 2 && status <= 1){playerProToFarmEliminated++;}
	    		// If flagged properly and player is forced to waivers set up waiver info. Can only do this on Game 1
	    		if(isWaivers == true && explode[6]=="true" && g == 1 && status <= 1 && explode[4] >= 2){
	    			var elem = document.getElementById('line1_'+explode[7]);
	    			// Add the movetowaiver class to the li
	    			if(elem.className.match(/\bmovetowaiver\b/)){var me;}else{elem.className = elem.className + " movetowaiver";} 					
					// Remove the li from where it was dropped and tag it to the bottom of the scratch list
					elem.parentNode.removeChild(elem);
					var scratchlist = document.getElementsByClassName("sortFarmScratch1");
					scratchlist[0].appendChild(elem); 
					// Add to the waiver count.
					// waiverText += waiverCount++;
					//if(waiverList.indexOf(explode[0]) == -1){
					//	waiverList.push(explode[0]);
					//}
					//alert(waiverList);
	    		}else{
		    		if (g == 1 && status >= 2){
		    			// If moving the player back up to the pros, remove the movetowaiver class.
		    			var elemLine = 'line1_' + explode[7];
		    			var elem = document.getElementById(elemLine);
		    			elem.className = elem.className.replace(" movetowaiver","");
		    		}
		    		
		    	}

		    	// Check for Overall to see if their overall is allowed in the farm
		    	if(status <= 1 && explode[2] != 16 && explode[5] > MaxFarmOv || status <= 1 && explode[2] == 16 && explode[5] > MaxFarmOvGoaler){
		    		playerProToFarmOverall++;
		    	}
		    }
		}

		// Check for waived players
		if(g == 1){
			for (x = 0; x < players.length; x++) {
				// Split the value at the pipe "|" and use each section for checking.
			    explode = players[x].value.split("|");

			    // If the explode array only has 2 sections then its a change in which status we are going through.
			    if(explode.length == 2){
			    	if(explode[1] == "ProDress"){status = 3;}
			    	else if (explode[1] == "ProScratch"){status = 2;}
			    	else if (explode[1] == "FarmDress"){status = 1;}
			    	else{status = 0;}
			    // Else its a player and increment variables where needed
			    }else{
			    	if(isWaivers == "true" && explode[6]=="true" && g == 1 && status <= 1 && explode[4] >= 2){
						if(waiverList.indexOf(explode[0]) == -1){
							waiverList.push(explode[0]);
						}
						
		    		}
			    }
			}
		}
		
		// Add to the waiverText if there is at least a player in the waivers section.
		
		if(waiverList.length > 0 && g == 1){
			waiverText = '<div class="notice waivernotice">Saving will send ' + waiverList.length + ' players to waivers.</div>';
		}
		
		// Add to error text if conditions met.
		if(proDressC[g] < ProMinC){errorText += '<div class="error errorposition notproposition">Not enough Pro Centres dressed. ' + proDressC[g] + ' Dressed, ' + ProMinC + ' required.</div>';}
		if(proDressLW[g] < ProMinLW){errorText += '<div class="error errorposition notproposition">Not enough Pro Left Wings dressed. ' + proDressLW[g] + ' Dressed, ' + ProMinLW + ' required.</div>';}
		if(proDressRW[g] < ProMinRW){errorText += '<div class="error errorposition notproposition">Not enough Pro Right Wings dressed. ' + proDressRW[g] + ' Dressed, ' + ProMinRW + ' required.</div>';}
		if(proDressD[g] < ProMinD){errorText += '<div class="error errorposition notproposition">Not enough Pro Defense dressed. ' + proDressD[g] + ' Dressed, ' + ProMinD + ' required.</div>';}
		if(proGoalerInGame[g] < ProGoalerInGame){errorText += '<div class="error errorposition notproposition">Not enough Pro Goalies dressed. ' + proGoalerInGame[g] + ' Dressed, ' + ProGoalerInGame + ' required.</div>';}
		if(proDressF[g] < ProMinForward){errorText += '<div class="error errorposition notproposition">Not enough Pro Forwards dressed. ' + proDressF[g] + ' Dressed, ' + ProMinForward + ' required.</div>';}
		if(proF[g] + proD[g] + proG[g] > ProPlayerLimit){errorText += '<div class="error playercount limitprodressed">You have too many players on your pro roster.</div>';}
		if(farmDressC[g] < FarmMinC){errorText += '<div class="error errorposition notfarmposition">Not enough Farm Centres dressed. ' + farmDressC[g] + ' Dressed, ' + FarmMinC + ' required.</div>';}
		if(farmDressLW[g] < FarmMinLW){errorText += '<div class="error errorposition notfarmposition">Not enough Farm Left Wings dressed. ' + farmDressLW[g] + ' Dressed, ' + FarmMinLW + ' required.</div>';}
		if(farmDressRW[g] < FarmMinRW){errorText += '<div class="error errorposition notfarmposition">Not enough Farm Right Wings dressed. ' + farmDressRW[g] + ' Dressed, ' + FarmMinRW + ' required.</div>';}
		if(farmDressF[g] < FarmMinForward){errorText += '<div class="error errorposition notfarmposition">Not enough Farm Forwards dressed. ' + farmDressF[g] + ' Dressed, ' + FarmMinForward + ' required.</div>';}
		if(farmDressD[g] < FarmMinD){errorText += '<div class="error errorposition notfarmposition">Not enough Farm Defense dressed. ' + farmDressD[g] + ' Dressed, ' + FarmMinD + ' required.</div>';}
		if(farmGoalerInGame[g] < FarmGoalerInGame){errorText += '<div class="error errorposition notfarmposition">Not enough Farm Goalies dressed. ' + farmGoalerInGame[g] + ' Dressed, ' + FarmGoalerInGame + ' required.</div>';}
		if(farmF[g] + farmG[g] > FarmPlayerLimit){errorText += '<div class="error playercount limitfarmdressed">You have too many players on your farm roster.</div>';}
		if(playerCount > MaximumPlayerPerTeam){errorText += '<div class="error playercount toomanyplayers">You have too many players on your roster.</div>';}
		if(playerCount < MinimumPlayerPerTeam){errorText += '<div class="error playercount notenoughplayers">You do not have enough players on your roster.</div>';}
		if(playerProToFarmTradeDeadline > 0){errorText += '<div class="error farmmove tradedeadline">You cannot send ' + playerProToFarmTradeDeadline + ' players to the farm. (After Trade Deadline).</div>';}
		if(playerProToFarmEliminated > 0){errorText += '<div class="error farmmove eliminated">You cannot send ' + playerProToFarmEliminated + ' players to the farm. (Eliminated From Playoffs).</div>';}
		if(playerProToFarmOverall > 0){errorText += '<div class="error farmmove overall">You cannot send ' + playerProToFarmOverall + ' players to the farm. (Farm Overall Limit).</div>';}
		// If the error text is empty still then the roster is complete and display
		if(errorText == ''){
			errorElement.innerHTML = waiverText + '<div class="rostercomplete">Roster is complete.</div>';
			lineValidated[g] = true;
		// Else there are errors, display them
		}else{
			errorElement.innerHTML = waiverText + errorText;
			
		}

	}	

	// Check to see if the flag is on for forcing all lines completed before saving.
	// If not just check the first line.
	if(ForceCorrect10LinesupbeforeSaving && GamesLeft > 1){
		for(var g=1;g<=GamesLeft;g++){
			if(lineValidated[g]){
				validated = true;
			}else{
				validated = false;
				break;
			}
		}
	}else{
		validated = (lineValidated[1] == true) ? true : false;
	}

	document.getElementById("saveroster").disabled = (validated) ? false : true;
}
