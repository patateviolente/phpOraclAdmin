<?php

/***************************************************************************
 *   phpOraclAdmin - Oracle manager, phpMyAdmin like
 *   Page login.php - Process connexion.php form
 *                        -> redir to index.php or connexion.php
 *   
 *   Copyright            : (C) 2010 - 
 *   Creator              : Patrick Portal - Brice Hyaumet
 *   Site-web             : http://xn--thta-hpa.net/poa
 *   Em@il                : See website
 *   See GNU condition on index.php head
 ***************************************************************************/

include('info.php');
$_SESSION = array();
$headto = str_replace('login.php', 'index.php', $_SERVER['REQUEST_URI']);

// is a correct/ complete form ?
if(isset($_POST['user']) && isset($_POST['password']) && isset($_POST['remember']) && isset($_POST['server'])
	 && !empty($_POST['user']) && !empty($_POST['server']) && is_numeric($_POST['remember'])){
	 
	// test the connection
	$c = new Connexion(-1);
	$c->init_manual($_POST['user'], $_POST['password'], $_POST['server']);
	if(!$c->connect()){		// not correct logs
		$invalid_cnx = true;
		include('connexion.php');	// STOP
		die();
	}
	unset($c);
	
	// then add into cookies
	$_SESSION['user'] = $_POST['user'];
	$_SESSION['pswd'] = $_POST['password'];
	$_SESSION['serv'] = $_POST['server'];
	
	// remember cookies ?
	setcookie("user", $_POST['user'], time()+$_POST['remember']);
	setcookie("pswd", $_POST['password'], time()+$_POST['remember']);
	setcookie("serv", $_POST['server'], time()+$_POST['remember']);
	
	header('Location: '.$headto);
}
else{
	$invalid_cnx = true;
	include('connexion.php');
}
?>
