function valChange(field,type,sid,updown,BlockPlayerFromPlayingLines12,BlockPlayerFromPlayingLines123,BlockPlayerFromPlayingLines12inPPPK,
						ProForceGameStrategiesTo,ProForceGameStrategiesAt5,FarmForceGameStrategiesTo,FarmForceGameStrategiesAt5,
						PullGoalerMinGoal,PullGoalerMinGoalEnforce,PullGoalerMinPct,PullGoalerRemoveGoaliesSecond,PullGoalerMax){

	var fieldvalue = parseInt(document.getElementById(field).value);
	var curvalue = (updown == 'up') ? fieldvalue + 1 : fieldvalue - 1;
	var curtotal = 0;
	var maxmin = 0;
	var last = 0;
	var val = 0;
	var vallast = 100000000;
	var flag = false;
	var typesplit = type.split("-");
	if(type == 'Strat'){	
		var fOF = document.getElementById(sid + 'OF').value;
		var fDF = document.getElementById(sid + 'DF').value;
		var fPhy = document.getElementById(sid + 'Phy').value;
		curtotal = parseInt(fOF) + parseInt(fDF) + parseInt(fPhy); 
		maxmin = (updown == 'up') ? 5: 0;
		
		if(updown == 'up' && curtotal < maxmin && fieldvalue < maxmin){document.getElementById(field).value = curvalue;}
		if(updown == 'down' && curtotal > maxmin && fieldvalue > maxmin){document.getElementById(field).value = curvalue;}
	}else if(typesplit[0] == 'Int'){
		switch(field){
			case 'Strategy1GoalDiff':
				maxmin = (updown == 'up') ? (typesplit[1]-1) : parseInt(document.getElementById('Strategy2GoalDiff').value) + 1;
			break;
			case 'Strategy2GoalDiff':
				maxmin = (updown == 'up') ? parseInt(document.getElementById('Strategy1GoalDiff').value) - 1 : 1;
			break;
			case 'Strategy4GoalDiff':
				maxmin = (updown == 'up') ? parseInt(document.getElementById('Strategy5GoalDiff').value) - 1 : 1;
			break;
			case 'Strategy5GoalDiff':
				maxmin = (updown == 'up') ? (typesplit[1]-1) : parseInt(document.getElementById('Strategy4GoalDiff').value) + 1;
			break;
			case 'RemoveGoaliesSecond':
				maxmin = (updown == 'up') ? PullGoalerMax : 0;
			break;
			case 'PullGoalerMinGoal':
				maxmin = (updown == 'up') ? 10 : PullGoalerMinGoalEnforce;
			break;
			default:
				maxmin = (updown == 'up') ? (typesplit[1]-1): 1;
			break;
		}
		
		if(!flag && updown == 'up' && curvalue <= maxmin){document.getElementById(field).value = curvalue;}
		if(!flag && updown == 'down' && curvalue >= maxmin){document.getElementById(field).value = curvalue;}
	}else{
		if(sid == 'Line15vs5Forward' || sid == 'Line25vs5Forward' || sid == 'Line35vs5Forward' || sid == 'Line45vs5Forward'){
			for(x=1;x<=4;x++){
				val = (sid == 'Line'+ x +'5vs5Forward') ? curvalue : parseInt(document.getElementById('Line'+ x +'5vs5ForwardTime').value);
				if(val > vallast){flag = true;break;}
				curtotal += val;
				vallast = val;
			}
		}else if(sid == 'Line15vs5Defense' || sid == 'Line25vs5Defense' || sid == 'Line35vs5Defense' || sid == 'Line45vs5Defense'){
			for(x=1;x<=4;x++){
				val = (sid == 'Line'+ x +'5vs5Defense') ? curvalue : parseInt(document.getElementById('Line'+ x +'5vs5DefenseTime').value);
				if(val > vallast){flag = true;break;}
				curtotal += val;
				vallast = val;
			}
		}else if(sid == 'Line1PPForward' || sid == 'Line2PPForward'){
			for(x=1;x<=2;x++){
				val = (sid == 'Line'+ x +'PPForward') ? curvalue : parseInt(document.getElementById('Line'+ x +'PPForwardTime').value);
				if(val > vallast){flag = true;break;}
				curtotal += val;
				vallast = val;
			}
		}else if(sid == 'Line1PPDefense' || sid == 'Line2PPDefense'){
			for(x=1;x<=2;x++){
				val = (sid == 'Line'+ x +'PPDefense') ? curvalue : parseInt(document.getElementById('Line'+ x +'PPDefenseTime').value);
				if(val > vallast){flag = true;break;}
				curtotal += val;
				vallast = val;
			}
		}if(sid == 'Line14VS4Forward' || sid == 'Line24VS4Forward'){
			for(x=1;x<=2;x++){
				val = (sid == 'Line'+ x +'4VS4Forward') ? curvalue : parseInt(document.getElementById('Line'+ x +'4VS4ForwardTime').value);
				if(val > vallast){flag = true;break;}
				curtotal += val;
				vallast = val;
			}
		}else if(sid == 'Line14VS4Defense' || sid == 'Line24VS4Defense'){
			for(x=1;x<=2;x++){
				val = (sid == 'Line'+ x +'4VS4Defense') ? curvalue : parseInt(document.getElementById('Line'+ x +'4VS4DefenseTime').value);
				if(val > vallast){flag = true;break;}
				curtotal += val;
				vallast = val;
			}
		}else if(sid == 'Line1PK4Forward' || sid == 'Line2PK4Forward'){
			for(x=1;x<=2;x++){
				val = (sid == 'Line'+ x +'PK4Forward') ? curvalue : parseInt(document.getElementById('Line'+ x +'PK4ForwardTime').value);
				if(val > vallast){flag = true;break;}
				curtotal += val;
				vallast = val;
			}
		}else if(sid == 'Line1PK4Defense' || sid == 'Line2PK4Defense'){
			for(x=1;x<=2;x++){
				val = (sid == 'Line'+ x +'PK4Defense') ? curvalue : parseInt(document.getElementById('Line'+ x +'PK4DefenseTime').value);
				if(val > vallast){flag = true;break;}
				curtotal += val;
				vallast = val;
			}
		}else if(sid == 'Line1PK3Forward' || sid == 'Line2PK3Forward'){
			for(x=1;x<=2;x++){
				val = (sid == 'Line'+ x +'PK3Forward') ? curvalue : parseInt(document.getElementById('Line'+ x +'PK3ForwardTime').value);
				if(val > vallast){flag = true;break;}
				curtotal += val;
				vallast = val;
			}
		}else if(sid == 'Line1PK3Defense' || sid == 'Line2PK3Defense'){
			for(x=1;x<=2;x++){
				val = (sid == 'Line'+ x +'PK3Defense') ? curvalue : parseInt(document.getElementById('Line'+ x +'PK3DefenseTime').value);
				if(val > vallast){flag = true;break;}
				curtotal += val;
				vallast = val;
			}
		}

		maxmin = (updown == 'up') ? 100: 1;
		if(!flag && updown == 'up' && curtotal <= maxmin && fieldvalue <= maxmin){document.getElementById(field).value = curvalue;}
		if(!flag && updown == 'down' && curtotal >= maxmin && fieldvalue >= maxmin){document.getElementById(field).value = curvalue;}
	}
	line_validator(BlockPlayerFromPlayingLines12,BlockPlayerFromPlayingLines123,BlockPlayerFromPlayingLines12inPPPK,
						ProForceGameStrategiesTo,ProForceGameStrategiesAt5,FarmForceGameStrategiesTo,FarmForceGameStrategiesAt5,
						PullGoalerMinGoal,PullGoalerMinGoalEnforce,PullGoalerMinPct,PullGoalerRemoveGoaliesSecond,PullGoalerMax);	
}
function getGroups(){
	var group = [];
	group[0] = ['Line15vs5ForwardCenter','Line15vs5ForwardLeftWing','Line15vs5ForwardRightWing'];
	group[1] = ['Line25vs5ForwardCenter','Line25vs5ForwardLeftWing','Line25vs5ForwardRightWing'];
	group[2] = ['Line35vs5ForwardCenter','Line35vs5ForwardLeftWing','Line35vs5ForwardRightWing'];
	group[3] = ['Line45vs5ForwardCenter','Line45vs5ForwardLeftWing','Line45vs5ForwardRightWing'];
	group[4] = ['Line15vs5DefenseDefense1','Line15vs5DefenseDefense2'];
	group[5] = ['Line25vs5DefenseDefense1','Line25vs5DefenseDefense2'];
	group[6] = ['Line35vs5DefenseDefense1','Line35vs5DefenseDefense2'];
	group[7] = ['Line45vs5DefenseDefense1','Line45vs5DefenseDefense2'];
	group[8] = ['Line1PPForwardCenter','Line1PPForwardLeftWing','Line1PPForwardRightWing','Line1PPDefenseDefense1','Line1PPDefenseDefense2'];
	group[9] = ['Line2PPForwardCenter','Line2PPForwardLeftWing','Line2PPForwardRightWing','Line2PPDefenseDefense1','Line2PPDefenseDefense2'];
	group[10] = ['Line14VS4ForwardCenter','Line14VS4ForwardWing','Line14VS4DefenseDefense1','Line14VS4DefenseDefense2'];
	group[11] = ['Line24VS4ForwardCenter','Line24VS4ForwardWing','Line24VS4DefenseDefense1','Line24VS4DefenseDefense2'];
	group[12] = ['Line1PK4ForwardCenter','Line1PK4ForwardWing','Line1PK4DefenseDefense1','Line1PK4DefenseDefense2'];
	group[13] = ['Line2PK4ForwardCenter','Line2PK4ForwardWing','Line2PK4DefenseDefense1','Line2PK4DefenseDefense2'];
	group[14] = ['Line1PK3ForwardCenter','Line1PK3DefenseDefense1','Line1PK3DefenseDefense2'];
	group[15] = ['Line2PK3ForwardCenter','Line2PK3DefenseDefense1','Line2PK3DefenseDefense2'];
	group[16] = ['Goaler1','Goaler2','Goaler3'];
	group[17] = ['ExtraForwardN1','ExtraForwardN2','ExtraForwardN3'];
	group[18] = ['ExtraForwardPP1','ExtraForwardPP2'];
	group[19] = ['ExtraForwardPK'];
	group[20] = ['ExtraDefenseN1','ExtraDefenseN2','ExtraDefenseN3'];
	group[21] = ['ExtraDefensePK1','ExtraDefensePK2'];
	group[22] = ['ExtraDefensePP'];
	group[23] = ['PenaltyShots1','PenaltyShots2','PenaltyShots3','PenaltyShots4','PenaltyShots5'];
	group[24] = ['LastMinOffForwardCenter','LastMinOffForwardLeftWing','LastMinOffForwardRightWing','LastMinOffDefenseDefense1','LastMinOffDefenseDefense2'];
	group[25] = ['LastMinDefForwardCenter','LastMinDefForwardLeftWing','LastMinDefForwardRightWing','LastMinDefDefenseDefense1','LastMinDefDefenseDefense2'];
	return group;	
}
function getSections(){

	var groups = getGroups();
	var section = [];
	for(x=0;x<groups.length;x++){
		section[x] = [];
		for(i=0;i<groups[x].length;i++){
			section[x][i] = document.getElementById(groups[x][i]).value;
		}
	}
	return section;	
}
function getText(){
	text = [];
	text[0] = "5vs5 Forward Line #1";
	text[1] = "5vs5 Forward Line #2";
	text[2] = "5vs5 Forward Line #3";
	text[3] = "5vs5 Forward Line #4";
	text[4] = "5vs5 Defense Line #1";
	text[5] = "5vs5 Defense Line #2";
	text[6] = "5vs5 Defense Line #3";
	text[7] = "5vs5 Defense Line #4";
	text[8] = "PP Line #1";
	text[9] = "PP Line #2";
	text[10] = "4vs4 Line #1";
	text[11] = "4vs4 Line #2";
	text[12] = "PK4 Line #1";
	text[13] = "PK4 Line #2";
	text[14] = "PK3 Line #1";
	text[15] = "PK3 Line #2";
	text[16] = "Goaltending";
	text[17] = "Extra Forward Normal";
	text[18] = "Extra Forward PP";
	text[19] = "Extra Forward PK";
	text[20] = "Extra Defense Normal";
	text[21] = "Extra Defense PK";
	text[22] = "Extra Defense PP";
	text[23] = "Penalty Shots";
	text[24] = "Last Minute Offensive";
	text[25] = "Last Minute Defensive";
	return text;
}
function eliminateDuplicates(names) {
	var uniqueNames = [];
	$.each(names, function(i, el){
	    if($.inArray(el, uniqueNames) === -1) uniqueNames.push(el);
	});
	return uniqueNames;
}
function isDuplicates(arr1,x){
	var arrCount1 = arr1.length;
	
	var arr2 = eliminateDuplicates(arr1);
	var arrCount2 = arr2.length;
	
	var ret = (arrCount1 == arrCount2) ? false : true;

	return ret;
}
function verifyLines(){
	var errortext = "";
	var section = getSections();
	var text = getText();
	for(x=0;x<section.length;x++){
		if(isDuplicates(section[x],x)){
			errortext += '<div class="erroritem">' + text[x] + '</div>';
		}else{
			for(i=0;i<section[x].length;i++){
				if (x != 16 && section[x][i] == "" || x != 16 && section[x][i].length == 0 || x != 16 && section[x][i] == null || 
					x == 16 && section[x][0] == "" || x == 16 && section[x][0].length == 0 || x == 16 && section[x][0] == null || 
					x == 16 && section[x][1] == "" || x == 16 && section[x][1].length == 0 || x == 16 && section[x][1] == null){
					errortext += '<div class="erroritem">' + text[x] + '</div>';
				}else if(x == 16){

				}
			}
		}
	}

	return errortext;
}
function verifyStrat(){

	var fOF = 10000;
	var fDF = 10000;
	var fPhy = 10000;

	var errortext = '';
	var ss = ['Line15vs5Forward','Line25vs5Forward','Line35vs5Forward','Line45vs5Forward'];
	ss.push('Line15vs5Defense','Line25vs5Defense','Line35vs5Defense','Line45vs5Defense');
	ss.push('Line1PPForward','Line2PPForward','Line1PPDefense','Line2PPDefense');
	ss.push('Line14VS4Forward','Line24VS4Forward','Line14VS4Defense','Line24VS4Defense');
	ss.push('Line1PK4Forward','Line2PK4Forward','Line1PK4Defense','Line2PK4Defense');
	ss.push('Line1PK3Forward','Line2PK3Forward','Line1PK3Defense','Line2PK3Defense');
	ss.push('Strategy1','Strategy2','Strategy3','Strategy4','Strategy5');

	var st = ['Line #1 5vs5 Forward','Line #2 5vs5 Forward','Line #3 5vs5 Forward','Line #4 5vs5 Forward'];
	st.push('Line #1 5vs5 Defense','Line #2 5vs5 Defense','Line #3 5vs5 Defense','Line #4 5vs5 Defense');
	st.push('Line #1 PP Forward','Line #2 PP Forward','Line #1 PP Defense','Line #2 PP Defense');
	st.push('Line #1 4VS4 Forward','Line #2 4VS4 Forward','Line #1 4VS4 Defense','Line #2 4VS4 Defense');
	st.push('Line #1 PK4 Forward','Line #2 PK4 Forward','Line #1 PK4 Defense','Line #2 PK4 Defense');
	st.push('Line #1 PK3 Forward','Line #2 PK3 Forward','Line #1 PK3 Defense','Line #2 PK3 Defense');
	st.push('Winning(1)','Winning(2)','Equal','Losing(1)','Losing(2)');


	for(x=0;x<ss.length;x++){
		
		fPhy = parseInt(document.getElementById(ss[x] + 'Phy').value);
		fDF = parseInt(document.getElementById(ss[x] + 'DF').value);
		fOF = parseInt(document.getElementById(ss[x] + 'OF').value);
		
		if((fOF + fDF + fPhy) != 5){errortext += '<div class="erroritem">Strategy '+ st[x] +'</div>';} 
	}
	
	return errortext;
}
function verifyTime(){
	
	var errortext = '';
	var timetotal = 0;
	var ss = [];
	ss[0] = ['Line15vs5Forward','Line25vs5Forward','Line35vs5Forward','Line45vs5Forward'];
	ss[1] = ['Line15vs5Defense','Line25vs5Defense','Line35vs5Defense','Line45vs5Defense'];
	ss[2] = ['Line1PPForward','Line2PPForward'];
	ss[3] = ['Line1PPDefense','Line2PPDefense'];
	ss[4] = ['Line14VS4Forward','Line24VS4Forward'];
	ss[5] = ['Line14VS4Defense','Line24VS4Defense'];
	ss[6] = ['Line1PK4Forward','Line2PK4Forward'];
	ss[7] = ['Line1PK4Defense','Line2PK4Defense']
	ss[8] = ['Line1PK3Forward','Line2PK3Forward'];
	ss[9] = ['Line1PK3Defense','Line2PK3Defense'];
	
	var st = [];
	st[0] = 'Time 5vs5 Forward';
	st[1] = 'Time 5vs5 Defense';
	st[2] = 'Time PP Forward';
	st[3] = 'Time PP Defense';
	st[4] = 'Time 4vs4 Forward';
	st[5] = 'Time 4vs4 Defense';
	st[6] = 'Time PK4 Forward';
	st[7] = 'Time PK4 Defense';
	st[8] = 'Time PK3 Forward';
	st[9] = 'Time PK3 Defense';
	

	for(x=0;x<ss.length;x++){
		timetotal = 0;
		for(i=0;i<ss[x].length;i++){
			timetotal += parseInt(document.getElementById(ss[x][i] + 'Time').value);
		}

		if(timetotal != 100){
			errortext += '<div class="erroritem">'+ st[x] +'-'+ timetotal +'%</div>';
		}
	}
	return errortext;
}
function findPlayerInRoster(selected,type,league){
	
	var allpos = make_position_list();
	var index = (league === "Pro") ? 0 : 1;
	var pos = allpos[index];
	var foundIt = false;

	var explode = selected.split("|");
	var player = explode[0];

	if(type == 0 || type == 3){
		for(x=0;x<pos[4].length;x++){
			if(player === pos[4][x]){

				foundIt = true;
				break;
			}
		}
	}else if(type == 2){
		for(x=0;x<pos[3].length;x++){
			if(player === pos[3][x]){
				foundIt = true;
				break;
			}
		}
	}else{
		for(x=0;x<3;x++){
			for(i=0;i<pos[x].length;i++){
				if(player === pos[x][i]){
					foundIt = true;
					break;
				}
			}
		}
	}

	return foundIt;
}
function ChangePlayer(id,league,BlockPlayerFromPlayingLines12,BlockPlayerFromPlayingLines123,BlockPlayerFromPlayingLines12inPPPK,ProForceGameStrategiesTo,ProForceGameStrategiesAt5,FarmForceGameStrategiesTo,FarmForceGameStrategiesAt5,PullGoalerMinGoal,PullGoalerMinGoalEnforce,PullGoalerMinPct,PullGoalerRemoveGoaliesSecond,PullGoalerMax){
	var selected = document.querySelector('input[name="sltPlayerList"]:checked').value;
	var explode = selected.split("|");
	var groups = getGroups();
	
	var foundIt = 11111110;
	var testtext = '';
	var changeIt = false;
	for(x=0;x<groups.length;x++){
		for(i=0;i<groups[x].length;i++){
			if(id === groups[x][i]){
				foundIt = x;
				break;
			}
		}
	}

	if(selected == ''){
		changeIt = true;
	}else if(foundIt == 16){
		/* Has to be Goalie */
		if(findPlayerInRoster(explode[0],3,league)){changeIt = true;}
	}else if(foundIt >=17 && foundIt <= 19){
		/* Has to be Forward*/
		if(findPlayerInRoster(explode[0],1,league)){changeIt = true;}
	}else if(foundIt >=20 && foundIt <= 22){
		/* Has to be Defense*/
		if(findPlayerInRoster(explode[0],2,league)){changeIt = true;}
	}else{
		/* Has to be Skater */
		if(!findPlayerInRoster(explode[0],3,league)){changeIt = true;}
	}
	
	if(changeIt){
		//alert(foundIt);
		document.getElementById(id).value = explode[0];
		line_validator(BlockPlayerFromPlayingLines12,BlockPlayerFromPlayingLines123,BlockPlayerFromPlayingLines12inPPPK,
						ProForceGameStrategiesTo,ProForceGameStrategiesAt5,FarmForceGameStrategiesTo,FarmForceGameStrategiesAt5,
						PullGoalerMinGoal,PullGoalerMinGoalEnforce,PullGoalerMinPct,PullGoalerRemoveGoaliesSecond,PullGoalerMax);
	};
}
function verifyBlockPlayerFromPlaying(Lines12,Lines123,Lines12inPPPK){
	var errortext = '';
	if(Lines12 || Lines123){
		var ss = [];
		var pp = [];
		var check = [];
		var ssuse = [];
		var ppuse = [];

		ss[0] = ['Line15vs5Forward','Line25vs5Forward'];
		ss[1] = ['Line15vs5Defense','Line25vs5Defense'];
		
		pp[0] = ['Center','LeftWing','RightWing'];
		pp[1] = ['Defense1','Defense2'];
		
		check[0] = [];
		check[1] = [];

		var player = '';
		var lineid = '';
		var baseText = 'Duplicate Player Lines 1,2';
		var duplicateText = '';

		if(Lines123){
			ss[0].push('Line35vs5Forward');
			ss[1].push('Line35vs5Defense');
			duplicateText += ',3';
		}

		for(var x=0;x<2;x++){
			check = [];
			ssuse = ss[x];
			ppuse = pp[x];
			duplicateText = (x==0) ? "Forward " + baseText : "Defense " + baseText;

			for(var s=0;s<ssuse.length;s++){
				for(p=0;p<ppuse.length;p++){
					lineid = ssuse[s] + ppuse[p];
					player = document.getElementById(lineid).value;
					
					if(inArray(player,check)){
						errortext += '<div class="erroritem">'+ duplicateText +'</div>';
						break;
					}else{
						check.push(player);
					}	
				}
			}
		}		
	}
	return errortext;
}
function line_validator(BlockPlayerFromPlayingLines12,BlockPlayerFromPlayingLines123,BlockPlayerFromPlayingLines12inPPPK,ProForceGameStrategiesTo,ProForceGameStrategiesAt5,FarmForceGameStrategiesTo,FarmForceGameStrategiesAt5,PullGoalerMinGoal,PullGoalerMinGoalEnforce,PullGoalerMinPct,PullGoalerRemoveGoaliesSecond,PullGoalerMax){
	var headertext = '';
	var headerstyle = '';
	var display = '';
	var disabled = '';

	var lines = verifyLines();
	var blockplayer = verifyBlockPlayerFromPlaying(BlockPlayerFromPlayingLines12,BlockPlayerFromPlayingLines123,BlockPlayerFromPlayingLines12inPPPK);
	var strat = verifyStrat();
	var linetime = verifyTime();
	

	
	
	var errors = lines + strat + linetime + blockplayer;
	
	if(errors.trim() === ""){
		headertext = "Lines are Complete";
		headerstyle = "linescomplete";
		disabled = false;
	}else{
		headertext = "Incomplete Lines";
		headerstyle = "error";
		disabled = true;
	}
	display  = "<div class='errorwrapper "+ headerstyle +"'>";
	display += "	<div id='errorheader'>";
	display += "		<div> "+ headertext +"</div>";
	display += "	</div>";
	display += errors;
	display += "</div>";

	document.getElementById('errors').innerHTML = display;	
	document.getElementById("linesubmit").disabled = disabled;
}