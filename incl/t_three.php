<?php

/***************************************************************************
 *   phpOraclAdmin - Oracle manager, phpMyAdmin like
 *   Page incl/t_three.php - Display three type SERVER > USER > TABLE with links
 *   
 *   Copyright            : (C) 2010 - 
 *   Creator              : Patrick Portal - Brice Hyaumet
 *   Site-web             : http://xn--thta-hpa.net/poa
 *   Em@il                : See website
 *   See GNU conditions on index.php head
 ***************************************************************************/
 
// Server
echo '
	<a href="index.php">
		<img src="style/img/navig/s_host.png" alt="(Server)" width="16" height="16" />
		'.((isset($AC)) ? $AC->getServ() : $_GET['serv']).'
	</a>';


// User
if(isset($_GET['user']))
	echo '
	<img src="style/img/navig/item_ltr.png" alt="&gt;" />
	<a href="index.php?user='.$_GET['user'].'">
		<img src="style/img/navig/s_db.png" alt="(User)" width="16" height="16" />
		'.$_GET['user'].'
	</a>';

// Table
if(isset($_GET['table']) && !empty($_GET['table']))
	echo '
	<img src="style/img/navig/item_ltr.png" alt="&gt;" />
	<a href="index.php?user='.$_GET['user'].'&amp;table='.$_GET['table'].'">
		<img src="style/img/navig/s_tbl.png" alt="(Table)" width="16" height="16" />
		'.$_GET['table'].'
	</a>';

?>
