	<?php

/***************************************************************************
 *   phpOraclAdmin - Oracle manager, phpMyAdmin like
 *   Page logout.php - Procede Logout, destroy session & personnal informations
 *   
 *   Copyright            : (C) 2010 - 
 *   Creator              : Patrick Portal - Brice Hyaumet
 *   Site-web             : http://xn--thta-hpa.net/poa
 *   Em@il                : See website
 *   See GNU condition on index.php head
 ***************************************************************************/

// delete all personnal connexions informations
session_start();
$time = time()-1;
setcookie("user", $_POST['user'], $time);
setcookie("pswd", $_POST['password'], $time);
setcookie("serv", $_POST['server'], $time);

session_unset();
session_destroy();

header('Location: index.php');

?>
