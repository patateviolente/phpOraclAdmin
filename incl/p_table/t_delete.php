<?php

// connexion + lang
if(!defined('IN_POA')) include('../../info.php');
$LG = new Lang('t_delete', $lang);

$req = 'DROP TABLE '.$_GET['table'].' CASCADE CONSTRAINTS';


if(isset($_POST['yes'])){
	$ret = $AC->query($req);
	if(!$ret)
		echo display_code($LG->g(0).'"'.$AC->error().'"', $req, false);
	else
		echo display_code($LG->g(1).' '.$_GET['table'].' '.$LG->g(2), $req, true);
} else{
	// Form + nb lines
	echo display_code($LG->g(10).$_GET['table'].' ?', $req, false);
	$AC->query('SELECT COUNT(*) AS NB FROM '.$_GET['table']);
	$ret = $AC->fetchAll();
	$nblines = $ret[0]['NB'];
	echo '
	<form action="'.rootURL($_SERVER['REQUEST_URI']).'" method="POST" 
		onsubmit="if(confirm(\''.addslashes($LG->g(10)).$_GET['table'].' ?\')){ submitDeleteTable(\''.getURLArgs().'\'); return !ajax; } else return false;">
		<p>
			<!--<input type="hidden" name="table" value="'.$_GET['table'].'" />-->
			<input type="submit" name="yes" value="'.$LG->g(11).$_GET['table'].'" />
		</p>
	</form>';

}

?>
