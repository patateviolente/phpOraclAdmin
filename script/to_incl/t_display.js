
function chgdisplayPost(url, begin, inter){
	writediv(
		'c_content', 
		filePost(url, 'chgdisplay=&linter='+inter+'&lbegin='+begin, true)
	);
}
