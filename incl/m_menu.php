<?php

/***************************************************************************
 *   phpOraclAdmin - Oracle manager, phpMyAdmin like
 *   Page incl/m_menu.php - Display menu (need info_page.php)
 *   
 *   Copyright            : (C) 2010 -
 *   Creator              : Patrick Portal - Brice Hyaumet
 *   Site-web             : http://xn--thta-hpa.net/poa
 *   Em@il                : See website
 *   See GNU conditions on index.php top
 ***************************************************************************/

if(!defined('IN_POA')){
	include('../info_page.php');
	header("Content-Type: text/html; charset=".ENCODING_AJAX);	
}

// config url
$urlargs = $_SERVER['REQUEST_URI'];
$urlargs = str_replace('incl/m_menu.php', 'index.php', $urlargs);	// ajax
$urlargs = preg_replace('/^.*index.php/', '', $urlargs);			// relative
$urlargs = preg_replace('/&?page=[a-zA-Z_]*/', '', $urlargs);		// re assign page
$startArg = (empty($urlargs) || $urlargs[count($urlargs)-1] == '/') ? '?' : '';	// dernier carcatère = '/' -> pas de index.php

// Display
echo '
			<div class="line">
				<ul>';
for($u=0; $u<count($m[$sectNum]); $u++){
	$file = $incl_folder[$sectNum].$m[$sectNum][$u][1];
	$args = $urlargs.'&amp;page='.substr($m[$sectNum][$u][1], 0, -4);
	echo '
					<li>
						<a href="'.$startArg.$args.'" title="'.$m[$sectNum][$u][0].'" class="'
							.(($m[$sectNum][$u][3]) ? 'danger' : '')
							.(($u == $m_activ[$sectNum]) ? 'activ' : '').
							'"
							onclick="
								writediv(\'c_content\', file(\'incl/m_main.php'.$startArg.$args.'\', true));
								writediv(\'c_menu\', file(\'incl/m_menu.php'.$startArg.$args.'\', true));
								document.location.hash=\''.substr($args, 1).'\';
								return !ajax;">
							<img src="style/img/menu/'.$m[$sectNum][$u][2].'" width="16" height="16" alt="img" />'.$m[$sectNum][$u][0].'</a>
					</li>';
}
echo '
				</ul>
				<div style="clear:both"></div>
			</div>
';

?>
