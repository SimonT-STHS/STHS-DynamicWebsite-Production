function update_position_list(element,byName,display){
	var expval;
	var exppos;
	var checkedValue = null; 
	var positions = ['C','LW','RW','D','G'];
	var pos = [];
	var pcount = 0;
	var inputElements = document.getElementsByClassName('position');
	for(var i=0; inputElements[i]; ++i){
		if(inputElements[i].checked){
		   pos[pcount++] = positions[i];
		}
	}

	var elements = (byName) ? document.getElementsByName(element) : document.getElementsByClassName(element);

	for (i=0;i<elements.length;i++) {
		expval = elements[i].value.split('|');
		if(expval.length > 2){
			exppos = expval[3].split(',');
			for (p=0;p<exppos.length;p++) {
				if(inArray(exppos[p],pos)){
					document.getElementById("line1_"+expval[7]).style.display = "" + display;
					break;
				}else{
					// display none for li
					document.getElementById("line1_"+expval[7]).style.display = "none";
				}
			}
		}
	}
}
function inArray(needle, haystack) {
	var ret = false;
    var length = haystack.length;
    for(var i = 0; i < length; i++) {
        if(haystack[i] == needle){ret = true;}
    }
    return ret;
}