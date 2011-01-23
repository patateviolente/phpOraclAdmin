<?php

/***************************************************************************
 *   phpOraclAdmin - Oracle manager, phpMyAdmin like
 *   Page config_bdd.php - Connexion to server permanent configuration
 *                    Should protect folder by htaccess & htpasswd !
 * 	                  + parameters
 *   
 *   Copyright            : (C) 2010 - 
 *   Creator              : Patrick Portal - Brice Hyaumet
 *   Site-web             : http://xn--thta-hpa.net/poa
 *   Em@il                : See website
 *   See GNU condition on index.php head
 ***************************************************************************/

///////////////////////////////////////////////////////////////////
//          SERVER CONFIGURATION - PERMANENT INFORMATION
// Please protect this folder or don't complete those lines (form+cookies)
// if a user fail to connect, he will be ignored and signalised in home page (but it can slow down page load)

define('FILES_ENCODING', 'ISO-8859-1');		// encode (in meta)
define('ENCODING', 'ISO-8859-1');			// in dft header
define('ENCODING_AJAX', 'UTF-8');			// (in headers for ajax)
define('DEFAULT_STYLESHEET', 'style_pma.css');			// style_pma | style_poa included
define('LANG_FOLDER', 'lang/');

$pdo_plug = !true;				// if false ==> oci_
$lang = 'en';					// default language
$style = DEFAULT_STYLESHEET;	// default stylesheet (in style/)
$ajax = true;					// ajax activation

////////////////////////////////////
// First user
$user_bdd[0] = '';			// ex : user
$pswd_bdd[0] = '';			// ex : mdp
$server_bdd[0] = '';		// ex : localhost/XE, or 2.55.167.12/XE, or softbb.dyndns.org/XE, etc. (local or distant)

////////////////////////////////////
// second user
$user_bdd[1] = '';
$pswd_bdd[1] = '';
$server_bdd[1] = '';

////////////////////////////////////
// third user
$user_bdd[2] = '';
$pswd_bdd[2] = '';
$server_bdd[2] = '';

////////////////////////////////////
// and another...
$user_bdd[3] = '';
$pswd_bdd[3] = '';
$server_bdd[3] = '';

//    .  .  .

//## E N D   O F   C O N F I G U R A T I O N ##//
/////////////////////////////////////////////////




// APPLY ARGS
// apply directely lang
$dftlang = $lang;
if(isset($_COOKIE['lang']) && is_dir(LANG_FOLDER.$_COOKIE['lang'])){
	$lang = $_COOKIE['lang'];
} if(isset($_COOKIE['ajax'])){
	$ajax = ($_COOKIE['ajax'] == 1) ? true : false;
} if(isset($_COOKIE['style'])){
	$style = $_COOKIE['style'];
} 

?>
