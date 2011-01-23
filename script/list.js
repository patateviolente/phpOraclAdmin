
/* */
function gotoTable(table, url, server){
	writediv('c_content', file('incl/p_table/t_structure.php?'+url+'&', true)); 
	writediv('c_menu', file('incl/m_menu.php?'+url, true)); 
	writediv('a_arbo', file('incl/t_three.php?'+url+'&serv='+server, true ));
	document.location.hash = url;
}

/* unselect all tables then select table at offset. */
function selectTabList(offset, nbtab){
	for(i=0; i<nbtab; i++)
		document.getElementById('li_tab'+i).className = '';
	document.getElementById('li_tab'+offset).className = 'select';	
}