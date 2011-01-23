<?php

/***************************************************************************
 *   phpOraclAdmin - Oracle manager, phpMyAdmin like
 *   Page incl/Class/selectpage.php - Page functions
 *   
 *   Copyright            : (C) 2010 - 
 *   Creator              : Patrick Portal - Brice Hyaumet
 *   Site-web             : http://xn--thta-hpa.net/poa
 *   Em@il                : See website
 *   See GNU conditions on index.php head
 ***************************************************************************/

/**
 * @return name of the category deducted by $_GET variables
 */
function getPageCategory(){
	global $_GET;
	if(isset($_GET['table']))
		return 2; 	// TABLE
	else if(isset($_GET['user']))
		return 1;	// USER
	else
		return 0;	// HOME
}

?>
