<?php

/***************************************************************************
 *   phpOraclAdmin - Oracle manager, phpMyAdmin like
 *   Page index.php - HTML main structure and inclusions
 *   
 *   Copyright            : (C) 2010 - 
 *   Creator              : Patrick Portal - Brice Hyaumet
 *   Site-web             : http://xn--thta-hpa.net/poa
 *   Em@il                : See website
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *       
 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *   
 *   You should have received a copy of the GNU General Public License
 *   along with this program; if not, write to the Free Software
 *   Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 *   MA 02110-1301, USA.
 ***************************************************************************/

	include('info.php');	// among other include connexion.php if no any connexion configured
	
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
<head>
	<!-- Page informations -->
	<title>phpOraclAdmin</title>
	<link rel="shortcut icon" href="favicon.gif" />
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo FILES_ENCODING; ?>" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
	
	<!-- External calls -->
	<link rel="stylesheet" type="text/css" href="style/<?php echo $style; ?>"/>
	<?php echo '<script type="text/javascript">var ajax = '.$ajax.';</script>
	'; ?>
	<script type="text/javascript" src="script/onload.js"></script>
	<script type="text/javascript">goResidentURL();</script>
	<script type="text/javascript" src="script/ajax_req.js"></script>
	<script type="text/javascript" src="script/display.js"></script>
	<script type="text/javascript" src="script/list.js"></script>
	<?php
		
		$scdir = opendir('script/to_incl/');
		while(($file = readdir($scdir)))
			if($file[0] != '.')
				echo '<script type="text/javascript" src="script/to_incl/'.$file.'"></script>
	';
	?>
</head>
<body>
	<!-- Users/ tables/ views selector -->
	<div id="side">
		<a href="index.php"><img src="style/img/home/logo.png" alt="phpOraclAdmin logo" /></a>
		<div class="home_icons">
			<?php 
			
			echo '
			<a href="index.php"><img src="style/img/home/b_home.png" alt="Home" title="'.$LG->g(0).'" /></a>
			<a href="index.php?page=u_sql"><img src="style/img/home/b_selboard.png" alt="SQL injection" title="'.$LG->g(1).'" /></a>
			<a href="http://xn--thta-hpa.net/poa/"><img src="style/img/home/b_docs.png" alt="phpOraclAdmin doc" title="'.$LG->g(2).'" /></a>
			<a href="http://www.oracle.com"><img src="style/img/home/b_oracle.gif" alt="Oracle website" title="'.$LG->g(3).'" /></a>
			'; ?>
		</div>
		<!-- users list, tables & vues -->
		<div id="list"><?php 
			include('incl/l_list.php');
			?>
		</div>
	</div>
	
	
	
	<!-- MAIN CONTENT -->
	<?php
	echo '
	<div id="main">
		<!-- connexion -->
		<div id="c_tree">';
			include('incl/t_top.php'); 
			echo '
		</div>
		
		<!-- server + three -->
		<div id="a_arbo">';
			include('incl/t_three.php'); 
			echo '
		</div>
		
		<!-- menu -->
		<div id="c_menu">';
			include('incl/m_menu.php');
			echo '
		</div>
		
		<!-- content -->
		<div id="c_content">';
			include('incl/m_main.php'); ?>
		</div>
	</div>
</body>
</html>
