<?php

/***************************************************************************
 *   phpOraclAdmin - Oracle manager, phpMyAdmin like
 *   Page info.php - Main informations header
 *   
 *   Copyright            : (C) 2010 - 
 *   Creator              : Patrick Portal - Brice Hyaumet
 *   Site-web             : http://xn--thta-hpa.net/poa
 *   Em@il                : See website
 *   See GNU condition on index.php head
 ***************************************************************************/

session_start();		// re-use variable, could get fastest execution time

///////////////////////////////////////////////////
// GET arguments :
//   - user=xxx 			xxx is the activ user
//   - table=yyy 			yyy is the activ table of xxx (if user is not defined and if there is only one user, take it)
//   - section=zzz			zzz is the page to include, 
//
// VARIABLES
//   - $connexions			all connexions, Connexion type
//   - $activ_user			selected base name, null if no one
//   - $AC					Shortcut to : $AC = $connexions[$activ_user] = Active Connexion
//   - $activ_userid		
//   - $directIncl			index.php must include all pages (!= htmlhttprequest)
//   - $sectNum				contains offset of page defition ([0]=home pages [1]=user pages [2]=table pages)


// User selection (no js)
if(isset($_POST['user_selector']))
	header('Location: index.php?user='.$_POST['user_selector']);

// inclusions
include('incl/class/fct_URL.php');
include('incl/class/Connexion.php');
include('incl/class/notifs.php');
include('incl/class/req_std.php');
include('info_page.php');		// call config + 



////////////////////////////////////
// main variables
define('IN_POA', true);	// all page will be forced to include this file to get working
$connexions = array();	// contains all connexions (Connexion type)

////////////////////////////////////
// Test Oracle connexion plug-in
function notifAndDie($msg){
	$notifanddie = $msg;
	include('connexion.php');
	die();
}
if($pdo_plug){
	if(!class_exists('PDO')) 
		notifAndDie('Selected Oracle connexion plug-in : PDO<br />Is not available.');
} else{ 	
	if (!function_exists('oci_connect')) 
		notifAndDie('Selected Oracle connexion plug-in : oci_<br />Is not available.');
}

////////////////// CONNEXION ////////////////////
// Checking defined databases (config_bdd.php)
$u = 0;
for($i=0; $i<count($user_bdd); $i++){	// list config_bdd entries
	if(!empty($user_bdd[$i]) && !empty($server_bdd[$i])){
		$c = new Connexion($i);
		if($c->connect())
			$connexions[$u++] = $c;
		else if(preg_match('/find driver/', $c->error()))
			notifAndDie('PDO answered : '.$c->error());
	}
}

// checking cookies
$cookie_connexion = false;
if(isset($_COOKIE['user']) && isset($_COOKIE['pswd']) && isset($_COOKIE['serv'])){
	$c = new Connexion(-1);
	$c->init_manual($_COOKIE['user'], $_COOKIE['pswd'], $_COOKIE['serv']);
	if($c->connect()){
		$cookie_connexion = true;
		$connexions[$u++] = $c;
	} if(preg_match('/find driver/', $c->error()))
		notifAndDie('PDO answered : '.$c->error());
}
unset($c);

if($u == 0 && !isset($_POST['connexionform'])){	// connexion must exist to get poa working !
	include('connexion.php');
	exit(0);
}

///////////////// Selected user //////////////////
if($u > 0){
	if(isset($_GET['user'])){		// user GET
		$activ_user = $_GET['user'];
		$activ_userid = getOffsetUser($activ_user);
		if($activ_userid == -1)			// take default if unvalid
			$activ_user = $connexions[0]->getUser();
	}
	else{ 								// default : first user
		$activ_user = $connexions[0]->getUser();
	}
	// get offset and initialize connexion
	$activ_userid = getOffsetUser($activ_user);
	$AC = $connexions[$activ_userid];
}

?>
