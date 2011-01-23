<?php

// cnx & language
if(!defined('IN_POA')){	
	include('../../info.php');
	header("Content-Type: text/html; charset=".ENCODING_ajax);
} else	
	$LG = new Lang('t_display', $lang);

$table = $_GET['table'];
if(isset($_POST['req'])) $table = preg_replace('#^.*from\s+([A-Za-z][A-Za-z0-9_]*)(\s|$).*$#i', '$1', $_POST['req']);

$primarykey = getPrimaryKey($AC, $table);		// need key for recept




////////////////////////////////////////////
// P  R  O  C  E  S  S
// EDITION
//    -> one or several
if(isset($_POST['edit']) || isset($_POST['edit_uniq_row'])){					// Multiple edition
	
	// test one -> $_POST['toedit'] ~ '4;8;9;0;...'		-> end with ';'
	if(isset($_POST['edit_uniq_row']))
		$_POST['toedit'] = $_POST['edit_uniq_row'].';';
	else{
		$_POST['toedit'] = '';
		foreach($_POST as $key => $value){
			if(preg_match('/^select[0-9]+$/', $key)){
				$num = str_replace('select', '', $key);
				$_POST['toedit'] .= $num.';';
			}
		}
	}
	// any checked ? incl t_insert
	if(!empty($_POST['toedit'])){
		$_POST['toedit'] = substr($_POST['toedit'], 0, -1);
		include('t_insert.php');
		$stop = true;
	}
} 
// SUPRIMER
//    -> one or several
if(isset($_POST['suppr']) || isset($_POST['del_uniq_row'])){
	if(isset($_POST['del_uniq_row']))
		$where = $_POST['primarykey'].' = \''.$_POST['del_uniq_row'].'\'';
	else{
		$where = '';
		foreach($_POST as $key => $value){
			if(preg_match('/^select[0-9]+$/', $key)){
				$num = str_replace('select', '', $key);
				$where .= $_POST['primarykey'].' = \''.$num.'\' OR ';
			}
		}
		$where = substr($where, 0, -4);
	}
	// delete if any
	if(!empty($where)){
		$req = 'DELETE FROM '.$_GET['table'].' WHERE '.$where;
		$ret = $AC->query($req);
		echo display_code($LG->g(0).((!$ret)?' : '.$AC->error():''), $req, $ret);
	}
}







////////////////////////////////////////////
// D  I  S  P  L  A  Y
// CST
$_SERVER['REQUEST_URI'] = preg_replace('#incl/.*main\.php#', 'index.php', $_SERVER['REQUEST_URI']);	// if ajax
$begin = 		isset($_POST['begin']) ? $_POST['begin'] : 1;
$nb = isset($_POST['nb']) ? $_POST['nb'] : 20;
$orderby = '';
$orderasc = true;
$orderby = 		isset($_GET['orderby']) ? $_GET['orderby'] : '';
$orderasc = 	!isset($_GET['desc']);

// NAVIGATION -> cst
if(isset($_POST['gofirst']))			{ $begin = 1;  }										// <<
else if(isset($_POST['goprevious']))	{ $begin -= $_POST['nb']; $begin = max($begin, 0);  }	// <
else if(isset($_POST['gonext']))		{ $begin += $_POST['nb'];  }							//   >
else if(isset($_POST['goprevious']))	{ $begin += $_POST['nb'];  }							//   >>
if(isset($_POST['chgdisplay']) && is_numeric($_POST['linter']) && is_numeric($_POST['lbegin'])){
	$nb = $_POST['linter'];
	$begin = $_POST['lbegin'];
}
$isFirstPage = ($begin <= 0);
$end = $begin + $nb -1;		// can determinate $end now $begin is not set again


// REQUEST
// force request : adapt for next pages
if(isset($_POST['req'])){
	if(preg_match('/between/', $_POST['req'])){
		$req = preg_replace('/between +[0-9]+ AND [0-9]+([^(between)]*)$/', 'between '.$begin.' AND '.$end.'$1', $_POST['req']);
	} else{
		$req = 'SELECT * FROM (SELECT RESULT.* , ROWNUM r FROM ('.$_POST['req'].') RESULT ) WHERE r between 0 AND 20';
	}
}
else{
	$req = 'SELECT * FROM (SELECT RESULT.* , ROWNUM r FROM (SELECT * FROM '.$table.' ';
	if(!empty($orderby))
		$req .= ' ORDER BY '.$orderby.' '.(($orderasc) ? 'ASC' : 'DESC');
	$req .= ' ) RESULT ) WHERE r between '.$begin.' AND '.($begin+$nb);
}

// TOTAL ROWS
$reqnb = preg_replace('#(?:SELECT.*?SELECT.*?)(SELECT).*?(FROM[^\)]*).*$#i', '$1 COUNT(*) AS NBROW $2', $req);
$ret = $AC->query($reqnb);
if(!$ret){
	echo display_code($LG->g(1).$AC->error(), $reqnb, false);
	$stop = true;
} else{
	$r = $AC->fetchall();
	$nbrow = $r[0]['NBROW'];
}

if(isset($_POST['goend']))			{ $begin = (int)($nbrow/$nb)*$nb; }
$isLastPage = 	(($begin + $nb) > $nbrow);



// possible error
if(!$AC->query($req)){
	echo display_code('Erreur : "'.$AC->error().'"', $req);
	$stop = true;
}
else $rows = $AC->fetchAll();



if(!isset($stop)){
	// DISPLAY
	echo display_code($LG->g(10).' <b>'.$begin.' - '.($begin+$nb).$LG->g(11).$nbrow.'</b>', $req, true);
	// Navigation
	$url = preg_replace('/page=.*?(&|$)/', 'page=t_display$1', $_SERVER['REQUEST_URI']);
	$url = str_replace('incl/p_table/t_sql.php', 'index.php', $url);
	$ajaxurl = jsURL($url);

	echo '
	<form name="displayNavig" method="post" action="'.$url.'"
		onsubmit="">
		
		<div id="commandtop">
			<input type="hidden" name="nb" value="'.$nb.'" />
			<input type="hidden" name="begin" value="'.$begin.'" />
			<input type="hidden" name="req" value="'.$req.'" />
			
			<input type="submit" value="<<" name="gofirst" '.(($isFirstPage) ? 'disabled="disabled"' : '' ).'/>
			<input type="submit" value="<" name="goprevious" '.(($isFirstPage) ? 'disabled="disabled"' : '' ).'/>
			<input type="submit" value="'.$LG->g(12).'" name="chgdisplay" id="chgdisplay" /> 
			<input type="text" name="linter" id="linter" value="'.($nb).'" /> '.$LG->g(13).' 
			<input type="text" name="lbegin" id="lbegin" value="'.(($isLastPage) ? 1 : ($begin+$nb)).'" />
			<input type="submit" value=">" name="gonext" '.(($isLastPage) ? 'disabled="disabled"' : '' ).'/>
			<input type="submit" value=">>" name="goend" '.(($isLastPage) ? 'disabled="disabled"' : '' ).'/>
		</div>';

	// Array
	echo '<table cellspacing="2" cellpadding="0" class="tab_display">';
	$count = 0;
	$fl = true;		// first line (displayed)
	$checkit = (isset($_GET['checkall'])) ? 'checked="checked" ' : '';
	$trclass = (isset($_GET['checkall'])) ? ' select' : '';

	// list rows...
	foreach($rows as $row){
		// titles bar
		if($fl){
			echo '<tr><th colspan="3"></th>';
			// Titles
			foreach($row as $key => $val){
				if(!is_numeric($key) && !empty($key) && $key != 'R'){
					echo '<th>
					<a href="'.preg_replace('/&(?:orderby|asc|desc)=.*?(&|$)/', '$1', $_SERVER['REQUEST_URI']).'&orderby='.$key;
					if($orderby == $key)		// Sort (arrows)
						echo ((isset($_GET['desc']))? '&amp;asc=' : '&amp;desc=').'">'.$key.
							' <img src="style/img/action/'.((isset($_GET['desc']))? 's_desc.png' : 's_asc.png').'" 
								alt="Tri" title="'.$LG->g(20).((isset($_GET['desc']))? $LG->g(21) : $LG->g(22)).'" /></a></th>';
					else
						echo '">'.$key.'</a></th>';
				}
			}
			echo '</tr>';
			$fl = false;
		}

		// checkbox
		$nameid = (empty($primarykey)) ? $count : $row[$primarykey];
		echo '
		<tr class="'.(($count%2 == 0) ? 'even' : 'odd').$trclass.'" onclick="switchSelectDisp(\'select'.$count.'\');" id="t_select'.$count.'">
			<td class="count">
				<input type="checkbox" name="select'.$nameid.'" id="select'.$count.'" '.$checkit.'  onclick="switchSelectDisp(\'select'.$count.'\');" />
			</td>';
		
		// edit & suppr
		$urledit = str_replace('t_display', 't_insert', $_SERVER['REQUEST_URI']);
		echo '
			<td>
				<button type="submit" name="edit_uniq_row" value="'.$nameid.'" title="'.$LG->g(30).'" class="actionbutton">
					<img src="style/img/action/b_edit.png" title="'.$LG->g(30).'" alt="'.$LG->g(31).'" class="icon" />
				</button>
			</td>
			<td>
				<button type="submit" name="del_uniq_row" value="'.$nameid.'" title="'.$LG->g(32).'" class="actionbutton"
					onclick="return confirm(\''.addslashes($LG->g(33)).'\');">
					<img src="style/img/action/b_drop.png" title="'.$LG->g(34).'" alt="'.$LG->g(35).'" class="icon" />
				</button>
			</td>
			';
			
		// all values
		for($i=0; $i<count($row)/2-1; $i++)		// /2 car entrées répétées par la sortie de PDO
			echo '<td>'.(($row[$i] == null) ? 'null' : $row[$i]).'</td>';
		echo '
		</tr>';
		$count++;
	}
	echo '
		</table>
		
		
		<!-- selection -->
		<a href="'.preg_replace('/&(un)?checkall=/', '', $_SERVER['REQUEST_URI']).'&checkall=" 
			onclick="selectAll(\'select\', 0, '.($count-1).', true); return false;">'.$LG->g(40).'</a> / 
		<a href="'.preg_replace('/&(un)?checkall=/', '', $_SERVER['REQUEST_URI']).'&uncheckall=" 
			onclick="selectAll(\'select\', 0, '.($count-1).', false); return false;">'.$LG->g(41).'</a>
		
		<!-- edition -->
		'.$LG->g(42).'
		<button type="submit" name="edit" value="'.$LG->g(31).'" title="'.$LG->g(36).'" class="actionbutton">
			<img src="style/img/action/b_edit.png" title="'.$LG->g(36).'" alt="'.$LG->g(31).'" class="icon" />
		</button>
		<button type="submit" name="suppr" value="'.$LG->g(35).'" title="'.$LG->g(37).'" class="actionbutton">
			<img src="style/img/action/b_drop.png" title="'.$LG->g(37).'" alt="'.$LG->g(35).'" class="icon" />
		</button>
		<input type="hidden" name="primarykey" value="'.$primarykey.'" />
	</form>';
}


?>
