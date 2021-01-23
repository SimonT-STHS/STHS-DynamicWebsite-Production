$(function() {
	$( "#accordion" ).accordion({
		collapsible: true,
		heightStyle: "content"
	});
});

$(function() {
    $("#change").click(function() {
    	var copyProDress = $(".sortProDress1 > li");
    	var copyProScratch = $(".sortProScratch1 > li");
    	var copyFarmDress = $(".sortFarmDress1 > li");
    	var copyFarmScratch = $(".sortFarmScratch1 > li");

		var currCol;
    	var colType;
    	
    	var leagues = ['Pro','Farm'];
    	var types = ['Dress','Scratch'];
    	var colText = '';
    	
    	for(s=2;s<=10;s++){
    		leagues.forEach(function(league) {
			    types.forEach(function(type) {
			    	colType = league + type;
			    	currCol = '.sort'+ colType + s;
				    $(currCol).empty();
				    
				    colText = '<h4 class="columnheader">'+ league + ' ' + type +'</h4><input class="rosterline'+ s +'" type="hidden" name="txtRoster['+ s +'][]" value="LINE|'+ colType +'">';
				    $(currCol).append(colText);	
					
					$('.sort'+ colType + '1 > li').each(function(){
						var text = $(this).html();
                        var explode = text.split("|");
                        if(explode[4] == 3){colType = "ProDress";}
                        else if(explode[4] == 2){colType = "ProScratch";}
                        else if(explode[4] == 1){colType = "FarmDress";}
                        else{colType = "FarmScratch";}

						text = text.replace('txtRoster[1]','txtRoster['+ s +']');
						text = text.replace('rosterline1','rosterline'+s)
						$(currCol).append('<li class="playerrow '+ colType +'">' + text + '</div>');
					});
			    
				});
			});
    	}
    	
    	roster_validator(
    		document.getElementById("MaximumPlayerPerTeam").value,
    		document.getElementById("MinimumPlayerPerTeam").value,
            document.getElementById("isWaivers").value,
    		document.getElementById("BlockSenttoFarmAfterTradeDeadline").value,
    		document.getElementById("isAfterTradeDeadline").value,
    		document.getElementById("ProTeamEliminatedCannotSendPlayerstoFarm").value,
    		document.getElementById("isEliminated").value,
    		document.getElementById("ForceCorrect10LinesupbeforeSaving").value,
    		document.getElementById("ProMinC").value,
    		document.getElementById("ProMinLW").value,
    		document.getElementById("ProMinRW").value,
    		document.getElementById("ProMinD").value,
    		document.getElementById("ProMinForward").value,
    		document.getElementById("ProGoalerInGame").value,
    		document.getElementById("ProPlayerInGame").value,
    		document.getElementById("ProPlayerLimit").value,
    		document.getElementById("FarmMinC").value,
    		document.getElementById("FarmMinLW").value,
    		document.getElementById("FarmMinRW").value,
    		document.getElementById("FarmMinD").value,
    		document.getElementById("FarmMinForward").value,
    		document.getElementById("FarmGoalerInGame").value,
    		document.getElementById("FarmPlayerInGame").value,
    		document.getElementById("FarmPlayerLimit").value,
    		document.getElementById("MaxFarmOv").value,
    		document.getElementById("MaxFarmOvGoaler").value,
            document.getElementById("GamesLeft").value
    		);
		
    	
    });
});

$(function() {
  $( "#tabs" ).tabs();
});