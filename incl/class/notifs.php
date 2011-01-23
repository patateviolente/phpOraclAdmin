<?php

/***************************************************************************
 *   phpOraclAdmin - Oracle manager, phpMyAdmin like
 *   Page incl/Class/notifs.php - Display code zone
 *   
 *   Copyright            : (C) 2010 - 
 *   Creator              : Patrick Portal - Brice Hyaumet
 *   Site-web             : http://xn--thta-hpa.net/poa
 *   Em@il                : See website
 *   See GNU conditions on index.php head
 ***************************************************************************/

function display_code($infos, $req, $success){
	return '
	<div class="codetop '.(($success) ? 'success' : 'fail').'">'.$infos.'</div>
	<div class="codebox">
		<div class="code">'.$req.'</div>
		<div class="codebottom">...</div>
	</div>';
}

/** Error zone **/
function display_error($msg){
	return '
	<div class="errorbox">
		<p>'.$msg.'</p>
	</div>';
}

/** Quick Hide/ display code zone **/
function displaySwitchVisibilityZone($name, $content){
	global $_SERVER, $_GET;
	$url = removeOneArg(rootURL($_SERVER['REQUEST_URI']), $name);
	$show = isset($_GET[$name]);
	if(!$show) $url .= '&amp;'.$name.'=';
	
	echo '
	<div id="'.$name.'" class="rollzone">
		<div class="up'.(($show) ? ' rollhiden':'').'" id="'.$name.'up">
			<a href="'.$url.'" onclick="getElementById(\''.$name.'down\').style.height = \'auto\'; 
				getElementById(\''.$name.'up\').className = \'rollhiden\'; return false;">[+] display req</a>
		</div>
		<div class="down" id="'.$name.'down"'.(($show) ? ' style="height:auto;"':'').'>'.$content.'</div>
	</div>';
}

?>
