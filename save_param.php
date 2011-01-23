<?php

/***************************************************************************
 *   phpOraclAdmin - Oracle manager, phpMyAdmin like
 *   Page save_param.php - Save parameters from home form -> redir to index.php
 *   
 *   Copyright            : (C) 2010 - 
 *   Creator              : Patrick Portal - Brice Hyaumet
 *   Site-web             : http://xn--thta-hpa.net/poa
 *   Em@il                : See website
 *   See GNU condition on index.php head
 ***************************************************************************/

if(isset($_POST['editparam'])){
	$timeout = time()+(3600*24*365);
	if(isset($_POST['lang'])) setcookie("lang", $_POST['lang'], $timeout);
	if(isset($_POST['style'])) setcookie("style", $_POST['style'], $timeout);
	if(isset($_POST['ajax'])) setcookie("ajax", $_POST['ajax'], $timeout);
}
header('Location: index.php');

?>
