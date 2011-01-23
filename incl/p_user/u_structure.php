<?php

// $AC + lang
if(!defined('IN_POA')) include('../../info.php');
$LG = new Lang('u_structure', $lang);


////////////////////////////////////////////////
// P  R  O  C  E  S  S
if(isset($_POST['suppr'])){
	for($i=0; $i<$_POST['totalrows']; $i++){
		if(isset($_POST['select'.$i]) && isset($_POST['tabname'.$i])){
			$reqdel = 'DROP TABLE '.$_POST['tabname'.$i]." CASCADE CONSTRAINTS\n";
			$ret = $AC->query($reqdel);
			echo display_code('Suppression des tables'.((!$ret)?' échouée : "'.$AC->error().'"': 'effectué.'), $reqdel, $ret);
		}
	}
}




////////////////////////////////////////////////
// D  I  S  P  L  A  Y
// req
$req = 'SELECT TABLE_NAME, TABLESPACE_NAME, STATUS, BACKED_UP, u.LOGGING, BACKED_UP NUM_ROWS, u.BLOCKS, COMPRESSION
  AVG_ROW_LEN, SAMPLE_SIZE,         -- stockage
  LAST_ANALYZED,                    -- date
  PCT_FREE, INI_TRANS, MAX_TRANS, INITIAL_EXTENT, MIN_EXTENTS, MAX_EXTENTS, CACHE, TABLE_LOCK
  from USER_TABLES U';
$ret = $AC->query($req);
displaySwitchVisibilityZone('roll_str2', display_code((!$ret) ? $AC->error() : 'Requète :', $req, $ret));	



if($ret){
	$fl = true;
	$rows = $AC->fetchAll();
	
	echo '
	<form action="'.rootURL($_SERVER['REQUEST_URI']).'" method="post"
		onsubmit="return confirm(\'Voulez-vous vraiment supprimer les tables sélectionnées ?\');">
	<table cellspacing="2" cellpadding="0" class="tab_ustruct">';
	
	// titles bar
	if($fl){
		echo '
		<tr>
			<th></th>
			<th>'.$LG->g(10).'</th>
			<th colspan="6">'.$LG->g(11).'</th>
			<th>'.$LG->g(12).'</th>
			<th>'.$LG->g(13).'</th>
			<th>'.$LG->g(14).'</th>
			<th>'.$LG->g(15).'</th>
			<th>'.$LG->g(16).'</th>
			<th>'.$LG->g(17).'</th>
			<th>'.$LG->g(18).'</th>
			<th>'.$LG->g(19).'</th>
			<th>'.$LG->g(20).'</th>
			<th>'.$LG->g(21).'</th>
			<th>'.$LG->g(22).'</th>
			<th>'.$LG->g(23).'</th>
			<th>'.$LG->g(24).'</th>
		</tr>';
		$fl = false;
	}

	
	// count vars :
	$valid = $backedup = $fullenabled = $cache = true;
	$count = $nbblocs = 0;
	
	// list
	foreach($rows as $row){
		echo '
		<tr '.(($count%2 == 0) ? 'class="even"' : 'class="odd"').' id="t_slct-a'.$count.'" onclick="switchSelectDisp(\'slct-a'.$count.'\');">
			<td class="count">
				<input type="checkbox" name="select'.$count.'" id="slct-a'.$count.'" onclick="switchSelectDisp(\'slct-a'.$count.'\');" />
				<input type="hidden" name="tabname'.$count.'" value="'.$row['TABLE_NAME'].'" />
			</td>
			<td>'.$row['TABLE_NAME'].'</td>
			
			<!-- actions -->
			<td><a href="index.php?user='.$_GET['user'].'&amp;table='.$row['TABLE_NAME'].'&amp;page=t_display">
				<img src="style/img/action/b_browse.png" width="16" height="16" alt="Displ" title="Afficher" /></a></td>
			<td><a href="index.php?user='.$_GET['user'].'&amp;table='.$row['TABLE_NAME'].'&amp;page=t_structure">
				<img src="style/img/action/b_props.png" width="16" height="16" alt="Struct" title="Structure" /></a></td>
			<td><a href="index.php?user='.$_GET['user'].'&amp;table='.$row['TABLE_NAME'].'&amp;page=t_search">
				<img src="style/img/action/b_select.png" width="16" height="16" alt="Search" title="Rechercher" /></a></td>
			<td><a href="index.php?user='.$_GET['user'].'&amp;table='.$row['TABLE_NAME'].'&amp;page=t_insert">
				<img src="style/img/action/b_insrow.png" width="16" height="16" alt="Ins" title="Insérer" /></a></td>
			<td><a href="index.php?user='.$_GET['user'].'&amp;table='.$row['TABLE_NAME'].'&amp;page=t_emptyout">
				<img src="style/img/action/b_empty.png" width="16" height="16" alt="Empty" title="Vider" /></a></td>
			<td><a href="index.php?user='.$_GET['user'].'&amp;table='.$row['TABLE_NAME'].'&amp;page=t_delete">
				<img src="style/img/action/b_drop.png" width="16" height="16" alt="Del" title="Supprimer" /></a></td>
			
			<!-- infos -->
			<td>'.$row['TABLESPACE_NAME'].'</td>
			<td>'.$row['STATUS'].'</td>
			<td>'.$row['LOGGING'].'</td>
			<td>'.$row['BACKED_UP'].'</td>
			<td>'.$row['NUM_ROWS'].'</td>
			<td>'.$row['BLOCKS'].'</td>
			<td>'.$row['AVG_ROW_LEN'].'</td>
			<td>'.$row['INITIAL_EXTENT'].'</td>
			<td>'.$row['MIN_EXTENTS'].'</td>
			<td>'.$row['MAX_EXTENTS'].'</td>
			<td>'.$row['CACHE'].'</td>
			<td>'.$row['TABLE_LOCK'].'</td>
			<td>'.$row['LAST_ANALYZED'].'</td>
		</tr>
		';
		
		// count total + stats
		$count++;
		if($backedup) $backedup = ($row['BACKED_UP'] == 'Y');
		if($valid) $valid = ($row['STATUS'] == 'VALID');
		if($cache) $cache = ($row['CACHE'] == 'Y');
		if($fullenabled) $fullenabled = ($row['TABLE_LOCK'] == 'Y');
		$nbblocs += $row['BLOCKS'];
		
	}
	
	// last line : total
	echo '
		<tr>
			<th><input type="hidden" name="totalrows" value="'.$count.'" /></th>
			<th>'.$count.' table'.(($count>1)?'s':'').'</th>
			<th colspan="6">- - - - - -</th>
			<th>-</th>
			<th>'.(($valid)?'VALID':'UNVALID').'</th>
			<th>-</th>
			<th>'.(($backedup)?'Y':'N').'</th>
			<th></th>
			<th>'.$nbblocs.'</th>
			<th>-</th>
			<th>-</th>
			<th>-</th>
			<th>-</th>
			<th>'.(($cache)?'Y':'N').'</th>
			<th>'.(($fullenabled)?'Y':'N').'</th>
			<th>-</th>
		</tr>
	</table>
	<!-- selection -->
	<a href="" onclick="selectAll(\'slct-a\', 0, '.($count-1).', true); return false;">'.$LG->g(30).'</a> / 
	<a href="" onclick="selectAll(\'slct-a\', 0, '.($count-1).', false); return false;">'.$LG->g(31).'</a>
	
	<!-- edition -->
	'.$LG->g(32).'
	';
	
	//<button type="submit" name="edit" value="'.$LG->g(33).'" title="'.$LG->g(34).'" class="actionbutton">
	//	<img src="style/img/action/b_edit.png" title="'.$LG->g(34).'" alt="'.$LG->g(33).'" class="icon" width="16" height="16"/>
	//</button>
	echo '<button type="submit" name="suppr" value="'.$LG->g(35).'" title="'.$LG->g(36).'" class="actionbutton">
		<img src="style/img/action/b_drop.png" title="'.$LG->g(36).'" alt="'.$LG->g(35).'" class="icon" width="16" height="16"/>
	</button>
	</form>
	

	<!-- form new table -->
	<fieldset class="fs_ustruct">
		<legend>'.$LG->g(40).'</legend>
		<form action="'.renewPageArg('u_table', rootURL($_SERVER['REQUEST_URI'])).'" method="post">
			<div class="top">
				<label for="nomnewtable">'.$LG->g(41).'</label>
					<input type="text" name="name" id="nomnewtable" value="" />
				 || <label for="nbcol">'.$LG->g(42).'</label>
					<input type="text" name="nbcol" id="nbcol" value="10" />
			</div>
			<div class="bottom">
				<input type="submit" name="createnewtable" value="'.$LG->g(43).'" />
			</div>
		</form>
	</fieldset>';
}
?>
