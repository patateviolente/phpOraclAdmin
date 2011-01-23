<?php

/***************************************************************************
 *   phpOraclAdmin - Oracle manager, phpMyAdmin like
 *   Page connexion.php - Connexion form
 *   
 *   Copyright            : (C) 2010 - 
 *   Creator              : Patrick Portal - Brice Hyaumet
 *   Site-web             : http://xn--thta-hpa.net/poa
 *   Em@il                : See website
 *   See GNU conditions on index.php head
 ***************************************************************************/

if(!defined('IN_POA') && !isset($invalid_cnx)) 	// don't incl info if connexion failed (ever included)
	include('info.php');
	
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
<head>
	<!-- Page informations -->
	<title>PHPoraclAdmin - Connexion</title>
	<link rel="shortcut icon" href="favicon.ico" />
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo FILES_ENCODING; ?>" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
	
	<!-- External calls -->
	<link rel="stylesheet" type="text/css" href="style/style_pma.css"/>
	<script type="text/javascript" src="script/ajax_req.js"></script>
	<script type="text/javascript" src="script/onload.js"></script>
</head>
<body>
	<div id="conn_form">
		<form name="connexion" id="fcnx" method="POST" action="login.php">
			<p class="cf">
				<a href="index.php"><img src="style/img/home/logo.png" alt="phpOraclAdmin logo" /></a><br />
				<?php
				if(isset($notifanddie)){
					echo '<div class="cnx_error_msg">'.$notifanddie.'</div>';
				}
				else{
					echo '
				<p><label for="user">User : </label>
					<input type="text" id="user" name="user" value="'.((isset($_POST['user'])) ? $_POST['user'] : '').'" /></p>
				<p><label for="password">Password : </label>
					<input type="password" id="password" name="password" value="'.((isset($_POST['password'])) ? $_POST['password'] : '').'" /></p>
				<p><label for="server">Server : </label>
					<input type="text" id="server" name="server" value="'.((isset($server_bdd[0])) ? $server_bdd[0] : '').'" /></p>
				<p><label for="remember">Remember me : </label>
					<select name="remember">
						<option value="0" selected="selected">Don\'t remember (safer)</option>
						<option value="3600">1 hour</option>
						<option value="43200">12 hours</option>
						<option value="86400">1 day</option>
						<option value="604800">1 week</option>
						<option value="2592000">1 month</option>
						<option value="31536000">1 year</option>
					</select>
					'.((isset($invalid_cnx)) ? '<div class="cnx_error_msg">Connexion échouée</div>' : '').'
				</p>
				<input type="submit" name="connexionform" value="Connexion" />
					';
				}
				?>
			</p>
		</form>
	</div>
</body>
</html>
