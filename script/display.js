

/** Switch selected state of box at id idcheckbox **/
function switchSelectDisp(idcheckbox){
	var checkbox = document.getElementById(idcheckbox);
	
	checkbox.checked = !checkbox.checked;
	colorizeRow(idcheckbox, 't_'+idcheckbox, checkbox.checked);
}

/** (un)check all box like "prefix"+"min" TO "prefix"+"max" **/
function selectAll(prefix, min, max, value){
	for(i=min; i<=max; i++){
		document.getElementById(prefix+i).checked = value;
		colorizeRow(prefix+i, 't_'+prefix+i, value);
	}
}

/** Switch the color of box with idcb 
 * (even or odd deducted from the name). **/
function colorizeRow(idcb, rowid, checked){
	var getid = /[0-9]+/;
	var row = document.getElementById(rowid);
	
	if(checked)	// check color
		row.className = 'select';
	else{					// default color
		// get number id and apply 'odd' or 'even'
		var idcb = getid.exec(idcb);
		if(idcb%2 == 0)
			row.className = 'even';
		else
			row.className = 'odd';
	}
}
