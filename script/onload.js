
// SQL textarea default height
var reqheight = 270;

/* javascript to execute on load (load page etc.) */
window.onload = function() {
	// after a complete loading
}

/* Stop loading page and Change window location if there 
 * is hash argument.
 * Call before page content for more ractivity. */
function goResidentURL(){
	if(document.location.hash.length > 1){
		window.stop();
		document.location = 'index.php?'+document.location.hash.substr(1, document.location.hash.length);
		document.execCommand('Stop')
		//alert(document.location.hash.substr(1, document.location.hash.length));
	}
}
