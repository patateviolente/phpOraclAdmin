/**  * Write into division 'div' tag text in argument. * @arg div Division name * @arg text Texte to write into */function writediv(div, text){	document.getElementById(div).innerHTML = text;}/** * @arg fichier File name to call * @arg asynchrone [boolean]   * @return File name content */function file(fichier, asynchrone){if(ajax){	if(window.XMLHttpRequest) // FIREFOX		xhr_object = new XMLHttpRequest();	else if(window.ActiveXObject) // IE		xhr_object = new ActiveXObject("Microsoft.XMLHTTP");	else return(false);		xhr_object.open("GET", fichier, false);	xhr_object.send(null);	return (xhr_object.readyState == 4) ? xhr_object.responseText : false;}}/** * Call file with post method * @arg fichier File name to call  * @arg params Parameters in post method * @return File name content */function filePost(fichier, params, asynchrone){if(ajax){	var xajax = null; 	if(window.XMLHttpRequest) // main browsers		xhr_object = new XMLHttpRequest();	else if(window.ActiveXObject) // IE		xhr_object = new ActiveXObject("Microsoft.XMLHTTP");	else		return(false);	xhr_object.open("POST", fichier, false);	//Send the proper header information along with the request	xhr_object.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); 	xhr_object.send(params);		return (xhr_object.readyState == 4) ? xhr_object.responseText : false;}}