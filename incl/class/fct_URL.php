<?php

/***************************************************************************
 *   phpOraclAdmin - Oracle manager, phpMyAdmin like
 *   Page incl/Class/fct_URL.php - URL function (add/ remove args - ajax help)
 *   
 *   Copyright            : (C) 2010 - 
 *   Creator              : Patrick Portal - Brice Hyaumet
 *   Site-web             : http://xn--thta-hpa.net/poa
 *   Em@il                : See website
 *   See GNU conditions on index.php head
 ***************************************************************************/

/* Usefull fonction wthout including info.php
	(urls, javascript&ajax, ...) */

/** @return ?arg1=...$ from the full url */
function getURLArgs(){
	return preg_replace('/^.*?(\?.*)?$/', '$1', $_SERVER['REQUEST_URI']);
}

function rootURL(){
	return str_replace('incl/m_main.php', 'index.php', $_SERVER['REQUEST_URI']);
}

function jsURL(){
	return str_replace('index.php', 'incl/m_main.php', $_SERVER['REQUEST_URI']);
}

function replaceTableArg($url){
	return preg_replace('/page=.*?(&|$)/', 'page=t_display$1', $url);
}

function renewPageArg($page, $url){
	return preg_replace('/page=.*?(&|$)/', 'page='.$page.'$1', $url);
}

function removeOneArg($url, $argname){
	$url = preg_replace('/&&*/', '&', preg_replace('/'.$argname.'=.*?(&|$)/', '$1', $url));		// remove + clean &&
	return preg_replace('/&$/', '', $url);
}



?>
