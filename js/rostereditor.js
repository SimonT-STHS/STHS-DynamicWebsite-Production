
function roster_validator(	MaximumPlayerPerTeam,MinimumPlayerPerTeam,isWaivers,BlockSenttoFarmAfterTradeDeadline,isPastTradeDeadline,ProTeamEliminatedCannotSendPlayerstoFarm,isEliminated,ForceCorrect10LinesupbeforeSaving,
							ProMinC,ProMinLW,ProMinRW,ProMinD,ProMinForward,ProGoalerInGame,ProPlayerInGame,ProPlayerLimit,
							FarmMinC,FarmMinLW,FarmMinRW,FarmMinD,FarmMinForward,FarmGoalerInGame,FarmPlayerInGame,FarmPlayerLimit,MaxFarmOv,MaxFarmOvGoaler,GamesLeft,FullFarmEnableGlobal,FullFarmEnableLocal,MaxFarmSalary){
	//Must have at least 18 players on the Pro Roster and farm if FullFarm enabled.
	var FullRoster = 18;
	var MinimumGoaliesDressed = 2;

	// Declare variables needed inside the loop. Set to Null
	var explode, status, proPlayerLimit, farmPlayerLimit, playerProToFarmTradeDeadline, playerProToFarmEliminated;

	// Declare variables with a default value of empty text or 0
	var playerCount = 0, waiverCount = 0;
	var errorText = '', waiverText = '', s = '';

	// Declare array variables for counting during the loops
	var pro = [], proDress = [], farm = [], farmDress = [];

	for(s=0;s<=5;s++){
		pro[s] = [];
		proDress[s] = [];
		farm[s] = [];
		farmDress[s] = [];
	}
	
	// Declare variables for validation.
	var validated = true;
	var lineValidated = [false,false,false,false,false,false,false,false,false,false];
	var waiverList = [];
	var injsus = false;
	var positionNumbers = position_number_array(); // array;

	// Loop through how many games are left up to 10.
	// Only 1 if no schedule.
	for(g=1;g<=GamesLeft;g++){
		// Get the players from the current gameleft. And set the error element to display errors.
		// Reset error text for each gameleft. 
		
		var players = document.getElementsByClassName( 'rosterline'+g ), res  = {}, i;
		errorElement = document.getElementById('errors rostererror' + g);
		errorText = '';
		
		// Reset count of pro and farm positions
		for(s=0;s<=5;s++){
			pro[s][g] = 0;
			proDress[s][g] = 0;
			farm[s][g] = 0;
			farmDress[s][g] = 0;
		}

		// Reset other variables.
		playerProToFarmTradeDeadline = 0;
		playerProToFarmEliminated = 0;

		// Loop through each player
		for (x = 0; x < players.length; x++) {
			// Split the value at the pipe "|" and use each section for checking.
			//Anthony Marchant|571|1|C|3|80|true|anthonymarchant|false|100|2|1999000|true|true
			// 0 = Name
			// 1 = Number
			// 2 = Position Number
			// 3 = Position String
			// 4 = Status1
			// 5 = Overall
			// 6 = ForceWaiver
			// 7 = NameID
			// 8 = Injure/Suspension
			// 9 = Condition
			//10 = Contract
			//11 = Salary1
			//12 = CanPlayPro
			//13 = CamPlayFarm
			//14 = PossibleWaiver
		    explode = players[x].value.split("|");
		    // If the explode array only has 2 sections then its a change in which status we are going through.
		    if(explode.length == 2){
		    	if(explode[1] == "ProDress"){status = 3;}
		    	else if (explode[1] == "ProScratch"){status = 2;}
		    	else if (explode[1] == "FarmDress"){status = 1;}
		    	else{status = 0;}
		    // Else its a player and increment variables where needed
		    }else{
		    	if(g == 1){playerCount++;}
		    	 injsus = (explode[8] == 'true' || explode[9] <= 95) ? true : false;
		    	// explode[2] is PositionNumber passed through from the SQLite record. ie 1=centre 16 = goalie 
		    	for(p=0;p<=5;p++){
		    		if(inArray(explode[2],positionNumbers[p])){
		    			if(status == 3){pro[p][g]++;proDress[p][g]++;}
		    			else if(status == 2 && !injsus){pro[p][g]++;}
		    			else if(status == 1){farm[p][g]++;farmDress[p][g]++;}
		    			else if(status == 0){farm[p][g]++;}
	    			}
		    	}
		    	var elem = document.getElementById('line1_'+explode[7]);
	    		// If flagged properly and trying to send to farm, not allowed if sending player to the farm after trade deadline 
	    		if(BlockSenttoFarmAfterTradeDeadline == "true" && isPastTradeDeadline == "true" && explode[4] >= 2 && status <= 1){playerProToFarmTradeDeadline++;}
	    		// If flagged properly and trying to send to farm, not allowed if sending player to the farm if eliminated from the playoffs
	    		if(ProTeamEliminatedCannotSendPlayerstoFarm == "true" && isEliminated == "true" && explode[4] >= 2 && status <= 1){playerProToFarmEliminated++;}
	    		// Check for Overall to see if their overall is allowed in the farm
		    	if(status <= 1 && explode[2] != 16 && explode[5] > MaxFarmOv || status <= 1 && explode[2] == 16 && explode[5] > MaxFarmOvGoaler){
					errorText += '<div class="erroritem errorplayer">' + explode[0] + ' overall is too high for farm.</div>'
		    		if(elem.className.match(/\bprotofarmov\b/)){var me;}else{elem.className = elem.className + " protofarmov";} 
		    	}
		    	// Check for Salary to see if their Salary is allowed in the farm
		    	if(status <= 1 && explode[11] > MaxFarmSalary && MaxFarmSalary > 0){
					errorText += '<div class="erroritem errorplayer">' + explode[0] + ' salary is too high for farm.</div>'
		    		if(elem.className.match(/\bprotofarmsalary\b/)){var me;}else{elem.className = elem.className + " protofarmsalary";} 	
		    	}
				// Check for if CanPlayPro
		    	if(status >= 2 && explode[12] == "false") {
					errorText += '<div class="erroritem errorplayer">' + explode[0] + ' can\'t play pro.</div>'
		    		if(elem.className.match(/\bproplaypro\b/)){var me;}else{elem.className = elem.className + " canplaypro";} 	
		    	}
				// Check for if CanPlayFarm
		    	if(status <= 1 && explode[13] == "false") {
					errorText += '<div class="erroritem errorplayer">' + explode[0] + ' can\'t play farm.</div>'
		    		if(elem.className.match(/\bproplayfarm\b/)){var me;}else{elem.className = elem.className + " canplayfarm";} 	
		    	}			
	    		// If flagged properly and player is forced to waivers set up waiver info. Can only do this on Game 1
	    		if(isWaivers == true && explode[14]=="true" && g == 1 && status <= 1 && explode[4] >= 2){
	    			// Add the movetowaiver class to the li
	    			if(elem.className.match(/\bmovetowaiver\b/)){var me;}else{elem.className = elem.className + " movetowaiver";} 					
					// Remove the li from where it was dropped and tag it to the bottom of the scratch list
					elem.parentNode.removeChild(elem);
					var scratchlist = document.getElementsByClassName("sortFarmScratch1");
					scratchlist[0].appendChild(elem); 
	    		}

	    		if (g == 1 && status >= 2){
	    			// If moving the player back up to the pros, remove the some class.
	    			var elemLine = 'line1_' + explode[7];
	    			var elem = document.getElementById(elemLine);
	    			elem.className = elem.className.replace(" movetowaiver","");
	    			elem.className = elem.className.replace(" protofarmsalary","");
	    			elem.className = elem.className.replace(" protofarmov","");
					elem.className = elem.className.replace(" canplayfarm","");
	    		}
	    		if (g == 1 && status <= 1){
	    			// If moving the player back up to the farm, remove the some class.
	    			var elemLine = 'line1_' + explode[7];
	    			var elem = document.getElementById(elemLine);
					elem.className = elem.className.replace(" canplaypro","");
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
			    	if(isWaivers == true && explode[14]=="true" && g == 1 && status <= 1 && explode[4] >= 2){
						if(waiverList.indexOf(explode[0]) == -1){
							waiverList.push(explode[0]);
						}
		    		}
			    }
			}
		}
		
		// Add to the waiverText if there is at least a player in the waivers section.
		if(waiverList.length > 0 && g == 1){
			waiverText = '<div class="notice waivernotice">Saving will send ' + waiverList.length + ' player(s) to waivers.</div>';
		}
		
		// Add to error text if conditions met.
		if(proDress[0][g] < ProMinC){errorText += '<div class="erroritem errorposition notproposition">Pro C: ' + proDress[0][g] + ' dressed, ' + ProMinC + ' required.</div>';}
		if(proDress[1][g] < ProMinLW){errorText += '<div class="erroritem errorposition notproposition">Pro LW: ' + proDress[1][g] + ' dressed, ' + ProMinLW + ' required.</div>';}
		if(proDress[2][g] < ProMinRW){errorText += '<div class="erroritem errorposition notproposition">Pro RW:  ' + proDress[2][g] + ' dressed, ' + ProMinRW + ' required.</div>';}
		if(proDress[3][g] < ProMinD){errorText += '<div class="erroritem errorposition notproposition">Pro D:  ' + proDress[3][g] + ' dressed, ' + ProMinD + ' required.</div>';}
		if(proDress[4][g] < MinimumGoaliesDressed || proDress[4][g] > ProGoalerInGame){errorText += '<div class="erroritem errorposition notproposition">Pro G:  ' + proDress[4][g] + ' dressed, ' + ProGoalerInGame + ' required.</div>';}
		if(proDress[5][g] < ProMinForward){errorText += '<div class="erroritem errorposition notproposition">Pro Fwd:  ' + proDress[5][g] + ' dressed, ' + ProMinForward + ' required.</div>';}
		if(proDress[5][g] < FullRoster){errorText += '<div class="erroritem playercount notenoughprodressed">Not Enough Pro players dressed.</div>';}
		if(proDress[5][g] > ProPlayerInGame){errorText += '<div class="erroritem playercount limitprodressed">Too many Pro Dress players.</div>';}
		if(pro[5][g] + pro[4][g] > ProPlayerLimit){errorText += '<div class="erroritem playercount limitprodressed">Too many Pro players.</div>';}		
		if(FullFarmEnableGlobal || FullFarmEnableLocal){
			if(farmDress[0][g] < FarmMinC){errorText += '<div class="erroritem errorposition notfarmposition">Farm C:  ' + farmDress[0][g] + ' dressed, ' + FarmMinC + ' required.</div>';}
			if(farmDress[1][g] < FarmMinLW){errorText += '<div class="erroritem errorposition notfarmposition">Farm LW:  ' + farmDress[1][g] + ' dressed, ' + FarmMinLW + ' required.</div>';}
			if(farmDress[2][g] < FarmMinRW){errorText += '<div class="erroritem errorposition notfarmposition">Farm RW:  ' + farmDress[2][g] + ' dressed, ' + FarmMinRW + ' required.</div>';}
			if(farmDress[5][g] < FarmMinForward){errorText += '<div class="erroritem errorposition notfarmposition">Farm Fwd:  ' + farmDress[5][g] + ' dressed, ' + FarmMinForward + ' required.</div>';}
			if(farmDress[3][g] < FarmMinD){errorText += '<div class="erroritem errorposition notfarmposition">Farm D:  ' + farmDress[3][g] + ' dressed, ' + FarmMinD + ' required.</div>';}
			if(farmDress[4][g] < MinimumGoaliesDressed || farmDress[4][g] > FarmGoalerInGame){errorText += '<div class="erroritem errorposition notfarmposition">Farm G:  ' + farmDress[4][g] + ' dressed, ' + FarmGoalerInGame + ' required.</div>';}
			if(farmDress[5][g] < FullRoster){errorText += '<div class="erroritem playercount notenoughfarmdressed">Not Enough Farm players dressed.</div>';}
			if(farmDress[5][g] > FarmPlayerInGame){errorText += '<div class="erroritem playercount limitfarmdressed">Too many Farm Dress players.</div>';}
			if(farm[5][g] + farm[4][g] > FarmPlayerLimit){errorText += '<div class="erroritem playercount limitfarmdressed">Too many Farm players.</div>';}				
		}
		if(playerCount > MaximumPlayerPerTeam){errorText += '<div class="erroritem playercount toomanyplayers">Too many players on your roster.</div>';}
		if(playerCount < MinimumPlayerPerTeam){errorText += '<div class="erroritem playercount notenoughplayers">Not enough players on your roster.</div>';}
		if(playerProToFarmTradeDeadline > 0){errorText += '<div class="erroritem farmmove tradedeadline">Cannot send ' + playerProToFarmTradeDeadline + ' players to the farm. (After Trade Deadline).</div>';}
		if(playerProToFarmEliminated > 0){errorText += '<div class="erroritem farmmove eliminated">Cannot send ' + playerProToFarmEliminated + ' players to the farm. (Eliminated From Playoffs).</div>';}
		// If the error text is empty still then the roster is complete and display
		if(errorText == ''){
			errorElement.innerHTML = waiverText + '<div class="rostercomplete">Roster is complete.</div>';
			lineValidated[g] = true;
			document.getElementById("saveroster").disabled = false;
		// Else there are errors, display them
		}else{
			var displayErrors = '';
			displayErrors += "<div class='errorwrapper error'>";
			displayErrors += "		<div id='errorheader'>";
			displayErrors += "			<div> Incomplete Lines</div>";
			displayErrors += "		</div>";
			displayErrors += 		errorText;
			displayErrors += "</div>";

			errorElement.innerHTML = waiverText + displayErrors;	
			document.getElementById("saveroster").disabled = true;
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
