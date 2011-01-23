<?php

if(!defined('IN_POA')) include('../../info.php');
$LG = new Lang('t_operations', $lang);

///////////////////////////////////////////
// P R O C E S S I N G
if(isset($_POST['rename']) && $_POST['rename'] != $_GET['table']){
	// Need connexion
	
	$rename_req = 'RENAME '.$_GET['table'].' TO '.$_POST['rename'];
	$ret = $AC->query($rename_req);
	if(!$ret) echo display_code($LG->g(5).'"'.$AC->error().'"', $rename_req, false);
	else{
		echo display_code('Table '.$_GET['table'].$LG->g(6).$_POST['rename'], $rename_req, true);
		$_SERVER['REQUEST_URI'] = str_replace('table='.$_GET['table'], 'table='.$_POST['rename'], $_SERVER['REQUEST_URI']);
		$_GET['table'] = $_POST['rename'];
	}
}



//////////////////////////////////////////
// D I S P L A Y
// Need url fct
echo '
<form method="post" action="'.rootURL($_SERVER['REQUEST_URI']).'" 
	onsubmit="submitRename(
			\''.$_GET['table'].'\', 
			document.getElementById(\'rename\').value, 
			\''.getURLArgs().'\'); 
		return !ajax;">
	<p>
		<label for="rename">'.$LG->g(0).'</label>
		<input type="text" name="rename" onkeyup="correctTableName(this.id)" id="rename" value="'.$_GET['table'].'" />
		<input type="submit" value="'.$LG->g(1).'" />
	</p>
</form>';






?>
