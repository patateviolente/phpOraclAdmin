
// unexpected chars :
var regexpTableName = /[\[\]\{\}\(\)\^\*\?\.\-\|\/,;&"':£¤!§%°]/;

/** AJAX : submit form if table name different from older.
 * Then update list, menu & load operations with form. */
function submitRename(tableName, newName, newURL){
	if(newName.length > 30)
		alert('Un nom de table sous Oracle ne peut exéder 30 caractères.')
	else if(regexpTableName.test(newName))
		alert('Le nom contient un caractère interdit.');
	else if(newName != tableName){
		writediv(
			'c_content', 
			filePost('incl/p_table/t_operations.php'+newURL, 
				'rename='+newName, true)
		);
		newURL = newURL.replace('table='+tableName, 'table='+newName);
		writediv('list', file('incl/l_list.php', true));
		writediv('c_menu', file('incl/m_menu.php'+newURL, true));
	}
	else
		alert('Choisissez un nom de table différent de l\'actuel.');
}

function correctTableName(id){
	//getElementById(id).value = regexpTableName.exec(getElementById(id).value)
}
