<?php

/***************************************************************************
 *   phpOraclAdmin - Oracle manager, phpMyAdmin like
 *   Page incl/l_list.php - Draw table/ user list
 *   
 *   Copyright            : (C) 2010 - 
 *   Creator              : Patrick Portal - Brice Hyaumet
 *   Site-web             : http://xn--thta-hpa.net/poa
 *   Em@il                : See website
 *   See GNU conditions on index.php head
 ***************************************************************************/

 
if(!defined('IN_POA'))
	include('../info.php'); 
$reqtablist = 'SELECT TABLE_NAME AS T FROM USER_TABLES ORDER BY T';

		
///////////////////////
// direct inclusion ?
if(defined('IN_POA') 
	&& !(isset($_POST['users']) && isset($_POST['select']))){		// got $connexions array
	// users
	if(count($connexions) > 1){		// several users, display select
		echo '
		<form method="post" action="index.php" onsubmit="">
			<p>
			<select name="user_selector" id="user_selector" class="user_select">';
		foreach($connexions as $c){
			echo '
				<option value="'.$c->getUser().'" '.(($activ_user == $c->getUser()) ? ' selected="selected"' : '').
				' onclick="document.location=\'index.php?user='.$c->getUser().'&amp;page=u_structure\'; return !ajax;">'.$c->getUser().'</option>';
		}
		echo '</select>
			<input type="submit" name="send" value="'.$LG->g(10).'" />
			</p>
		</form>';
	}
	else
		echo '<a class="uniq_user" href="index.php?user='.$activ_user.'&amp;page=u_structure">'.$activ_user.'</a>';
	
	// tables
	if($activ_user){
		$AC->query($reqtablist);
		$data = $AC->fetchAll();
		echo '
		<div class="l_tab">';
			if(count($connexions) > 1)
				echo '<a href="index.php?user='.$AC->getUser().'">'.$activ_user.'</a> ('.count($data).')';
			echo '
			<ul id="listul">';
		if(!isset($_GET['table'])) $_GET['table'] = '';	
		
		// tables
		$i = 0;
		foreach($data as $d){
			$inurl = 'user='.$activ_user.'&amp;table='.$d['T'];
			echo '
				<li '.(($_GET['table'] == $d['T']) ? ' class="select"' : '' ).' id="li_tab'.$i.'" title="'.$LG->g(11).' '.$d['T'].
					'" onclick="gotoTable(\''.$_GET['table'].'\', \''.$inurl.'\', \''.$AC->getServ().'\'); selectTabList(\''.$i.'\', \''.count($data).
					'\'); return !ajax;">
					<pre><a href="index.php?'.$inurl.'">'.$d['T'].'</a></pre></li>';
			++$i;
		}
		echo '
			</ul>
			</td></tr></table>
		</div>';
	}
}

/*
//////////////////////////
else // AJAX /////////////
{
	// POST informations ?
	echo '<select name="user_selector" class="user_select">';
	$users = explode(';', $_POST['users']);
	foreach($users as $user)
		echo '<option name="'.$user.'" '.(($_POST['select'] == $user) ? ' selected="selected"' : '').'>'.$user.'</option>';
	echo '</select>';
	
	// list tables (one selected or in GET)
	echo '<div class="l_tab">';
	if(isset($_POST['tables'])){
		echo '<ul>';
		$tables = explode(';', $_POST['tables']);
		foreach($tables as $table)
			echo '<li><a href="index.php?user='.$_POST['select'].'&table='.$table.'">'.$table.'</a></li>';
		echo '</ul>';
	}
	echo '
	</div>';
}*/
?>
