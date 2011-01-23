<?php

// LANG & connexion infos (not directely included)
if(!defined('IN_POA') && isset($_POST['yes']))	// need $AC to execute
		include('../../info.php');	
else	$LG = new Lang('t_emptyout', $lang);

// REQ
$req = 'DELETE FROM '.$_GET['table'].'';

///////////////////////////////////////////
// P R O C E S S I N G
if(isset($_POST['yes'])){
	$ret = $AC->query($req);
	if(!$ret)
		echo display_code($LG->g(0).'"'.$AC->error().'"', $req, false);
	else
		echo display_code($LG->g(1).$_GET['table'].$LG->g(2), $req, true);
}


//////////////////////////////////////////
// D I S P L A Y
else{
	if(!defined('IN_POA')) include('../class/fct_URL.php');		// need url fct
	
	// Form + nb lines
	echo display_code($LG->g(10).'<b>'.$_GET['table'].'</b>', $req, false);
	$AC->query('SELECT COUNT(*) AS NB FROM '.$_GET['table']);
	$ret = $AC->fetchAll();
	$nblines = $ret[0]['NB'];
	echo '
	<form action="'.rootURL($_SERVER['REQUEST_URI']).'" method="POST" 
		onsubmit="if(confirm(\''.addslashes($LG->g(14)).'\')){ submitEmptyForm(\''.getURLArgs().'\'); return !ajax; } else return false;">
		<p>
			<input type="submit" name="yes" value="'.$LG->g(12).$nblines.$LG->g(13).'" />
		</p>
	</form>';

}

?>
