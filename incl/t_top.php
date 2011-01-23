<?php

/***************************************************************************
 *   phpOraclAdmin - Oracle manager, phpMyAdmin like
 *   Page incl/t_top.php - Top page display (cookie connexion)
 *   
 *   Copyright            : (C) 2010 - 
 *   Creator              : Patrick Portal - Brice Hyaumet
 *   Site-web             : http://xn--thta-hpa.net/poa
 *   Em@il                : See website
 *   See GNU conditions on index.php head
 ***************************************************************************/

 
// OR connexion
if($cookie_connexion){
	echo '
	<div class="deconnect_me">
		<a href="logout.php">Clear cookies</a>
	</div>';
} else{
	echo '
	<div class="deconnect_me">
		<a href="connexion.php">Temp connexion</a>
	</div>';
}
	

?>
