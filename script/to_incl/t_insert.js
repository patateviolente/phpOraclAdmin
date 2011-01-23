

function clearIfDefault(input, txt){
	if(input.value == txt)
		input.value = '';
}

function checkAlwaysNull(check, input){
	check.checked = (input.value == '');
}

function submitInsert(args){
	//writediv('result_insert', filePost('incl/p_table/t_insert.php'+args));
}

