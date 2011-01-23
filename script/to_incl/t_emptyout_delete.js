
function submitEmptyForm(args){
	writediv('c_content', filePost('incl/p_table/t_emptyout.php'+args, 'yes=', true));
}

function submitDeleteTable(args){
	writediv('c_content', filePost('incl/p_table/t_delete.php'+args, 'yes=', true));
}
