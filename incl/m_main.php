<?php

/***************************************************************************
 *   phpOraclAdmin - Oracle manager, phpMyAdmin like
 *   Page incl/m_main.php - Connexion to server permanent configuration
 *   
 *   Copyright            : (C) 2010 - 
 *   Creator              : Patrick Portal - Brice Hyaumet
 *   Site-web             : http://xn--thta-hpa.net/poa
 *   Em@il                : See website
 *   See GNU conditions on index.php head
 ***************************************************************************/

if(!defined('IN_POA')){
	@header("Content-Type: text/html; charset=ISO-8859-1");	
	chdir('../');			// incl file from root
	include('info.php');
}

// Choose default page (if don't get ?PAGE=)
if(!isset($_GET['page'])){
	$_GET['page'] = substr($m[$sectNum][		// take page from section...
							$m_activ[$sectNum]][1], 0, -4);	// at default page
}

// Verify table exists
if(!empty($_GET['table'])){
	$ret = $AC->query('SELECT count(*) AS N FROM USER_TABLES WHERE TABLE_NAME = \''.$_GET['table'].'\'');
	$nb = $AC->fetchAll();
	$nb = $nb[0]['N'];
	if($nb < 1){
		echo display_error('La table '.$_GET['table'].' n\'existe pas.');
		$stop = true;
	}
}

if(!isset($stop)){
	// page allowed
	if(isset($_GET['page']) && in_array($_GET['page'].'.php', $pages_auth[$sectNum])){
		if(file_exists($incl_folder[$sectNum].$_GET['page'].'.php'))	// ok
			include($incl_folder[$sectNum].$_GET['page'].'.php');
		else  // file erased ?
			echo display_error('<p>Le fichier '.$_GET['page'].' n\'existe pas.</p>
				<p>Mettez à jour l\'application en ajoutant le fichier "'.$incl_folder[$sectNum].$_GET['page'].'.php"</p>');
	}
	else{	
		// unknown page
		echo display_error('La page "'.$incl_folder[$sectNum].$_GET['page'].'.php" ne fait pas partie de cette version de phpOraAdmin.');
	}
}

?>
