<?php

if(!defined('IN_POA')){
	include('../../info.php');
} 
$LG = new Lang('t_structure', $lang);

///////////////////////////////////////////////
// P  R  O  C  E  S  S
// CONSTRAINTS
//   -> several
if(isset($_POST['cst_enable']) || isset($_POST['cst_disable']) || isset($_POST['cst_delete'])){	
	if(isset($_POST['cst_enable'])) 		$cmd = 'ENABLE';
	else if(isset($_POST['cst_disable']))	$cmd = 'DISABLE';
	else									$cmd = 'DROP';
	
	$enable = isset($_POST['cst_enable']);
	$req = 'ALTER TABLE '.$_GET['table'].' '.$cmd.' CONSTRAINT ';
	// search checked values
	foreach($_POST as $k => $p){
		if(preg_match('/^slct-c[0-9]+$/', $k)){
			$offset = preg_replace('/^.*([0-9]+).*$/', '$1', $k);
			$toreq = $req.$_POST['cstname_'.$offset];
			$ret = $AC->query($toreq);
			echo display_code($LG->g(0).' ['.$cmd.']'.(($ret)?'':', '.$LG->g(1).' : '.$AC->error()), $toreq, $ret);
		}
	}
}
//    -> unique enable/ disable
else if(isset($_POST['ena_uniq_cst']) || isset($_POST['dis_uniq_cst']) || isset($_POST['del_uniq_cst'])){
	if(isset($_POST['ena_uniq_cst'])){ 		$cmd = 'ENABLE'; $cst = $_POST['ena_uniq_cst']; }
	else if(isset($_POST['dis_uniq_cst'])){	$cmd = 'DISABLE'; $cst = $_POST['dis_uniq_cst']; }
	else{									$cmd = 'DROP'; $cst = $_POST['del_uniq_cst']; }
	$req = 'ALTER TABLE '.$_GET['table'].' '.$cmd.' CONSTRAINT '.$cst;
	$ret = $AC->query($req);
	echo display_code($LG->g(0).' ['.$cmd.']'.(($ret)?'':', '.$LG->g(1).' : '.$AC->error()), $req, $ret);
}


// ATTRIBUTES
//    -> several
if(isset($_POST['edit_slct_attr']) || isset($_POST['del_slct_attr'])){
	echo 'edition/ suppression multiple à faire dans l\'éditeur de table';
}
else if(isset($_POST['edit_uniq_attr']) || isset($_POST['del_uniq_attr'])){
	echo 'edition/ suppression multiple à faire dans l\'éditeur de table';	
}




///////////////////////////////////////////////
// D I S P L A Y
// REQS
$req_attrib = '
	select TABLE_NAME, COLUMN_NAME, DATA_TYPE, DATA_LENGTH, DATA_PRECISION, DATA_SCALE,
		NULLABLE, COLUMN_ID, DEFAULT_LENGTH, DATA_DEFAULT, NUM_DISTINCT, DENSITY, 
		NUM_NULLS, CHARACTER_SET_NAME
	from USER_TAB_COLUMNS 
	where TABLE_NAME = \''.$_GET['table'].'\'';
$req_constraint = '
	select CONSTRAINT_NAME, CONSTRAINT_TYPE, SEARCH_CONDITION,
		STATUS, DEFERRABLE, DEFERRED, VALIDATED, GENERATED, LAST_CHANGE, INDEX_OWNER
	from USER_CONSTRAINTS 
	where TABLE_NAME = \''.$_GET['table'].'\'';
$req_trigger = '
	select TRIGGER_NAME, TRIGGER_TYPE, TABLE_OWNER, BASE_OBJECT_TYPE TRIGGERING_EVENT,
		STATUS, DESCRIPTION, ACTION_TYPE, TRIGGER_BODY
	from USER_TRIGGERS 
	where TABLE_NAME = \''.$_GET['table'].'\'';

		
///////////////////////////////////////////////////
// ATTRIBUTE NAMES & type
$ret = $AC->query($req_attrib);

if(!$ret)
	echo display_error($AC->error());
else
{
	$rows = $AC->fetchAll();
	
	echo '<h2>'.$LG->g(5).'</h2>';
	displaySwitchVisibilityZone('roll_str1', display_code($LG->g(10).' : ', $req_attrib, $ret));
	echo '
	<form method="post" action="'.rootURL($_SERVER['REQUEST_URI']).'">
	<table cellspacing="2" cellpadding="0" class="tab_attr">';
	
	// titles bar
	echo '
		<tr>
			<td class="empty"></td>
			<th>'.$LG->g(11).'</th>
			<th>'.$LG->g(12).'</th>
			<th>'.$LG->g(13).'</th>
			<th>'.$LG->g(14).'</th>
			<th>'.$LG->g(15).'</th>
			<th>'.$LG->g(16).'</th>
			<th colspan="2">'.$LG->g(17).'</th>
		</tr>';

	// list
	$count = 0;
	foreach($rows as $row){
		// values
		echo '
			<tr '.(($count%2 == 0) ? 'class="even"' : 'class="odd"').' id="t_slct-a'.$count.'" onclick="switchSelectDisp(\'slct-a'.$count.'\');">
				<td class="count"><input type="checkbox" name="slct-a'.$count.'" id="slct-a'.$count.'" onclick="switchSelectDisp(\'slct-a'.$count.'\');" /></td>
				<td>'.$row['COLUMN_NAME'].'</td>
				<td>'.$row['DATA_TYPE'].' ('.
					(empty($row['DATA_PRECISION'])
						? $row['DATA_LENGTH']
						: $row['DATA_PRECISION'].', '.$row['DATA_SCALE']).')</td>
				<td>'.$row['CHARACTER_SET_NAME'].'</td>
				<td title="'.(($row['NULLABLE'] == 'Y') 
						? $LG->g(20).' '.$row['NUM_NULLS'].' '.$LG->g(21) 
						: $LG->g(22)).'">
					'.(($row['NULLABLE'] == 'Y') ? ' ('.$row['NUM_NULLS'].')' : $row['NULLABLE']).'</td>
				<td>'.$row['DATA_DEFAULT'].'</td>
				<td>'.floor(str_replace(',', '.', $row['DENSITY'])*100).'%</td>
				<td>
					<button type="submit" disabled="disabled" name="edit_uniq_attr" value="'.$LG->g(30).'" title="'.$LG->g(31).'" class="actionbutton">
						<img src="style/img/action/b_edit.png" title="'.$LG->g(31).'" alt="'.$LG->g(30).'" class="icon" width="16" height="16"/>
					</button>
				</td>
				<td>
					<button type="submit" disabled="disabled" name="del_uniq_attr" value="'.$LG->g(32).'" title="'.$LG->g(33).'" class="actionbutton">
						<img src="style/img/action/b_drop.png" title="'.$LG->g(33).'" alt="'.$LG->g(32).'" class="icon" width="16" height="16"/>
					</button>
				</td>
			</tr>
			';
		$count++;
	}
	echo '</table>
		<!-- selection -->
		<p>
			<a href="#" onclick="selectAll(\'slct-a\', 0, '.($count-1).', true); return false;">'.$LG->g(40).'</a> / 
			<a href="#" onclick="selectAll(\'slct-a\', 0, '.($count-1).', false); return false;">'.$LG->g(41).'</a>
			
			<!-- edition -->
			'.$LG->g(42).'
			<!--
			<button type="submit" disabled="disabled" name="edit_slct_attr" value="'.$LG->g(34).'" title="'.$LG->g(35).'" class="actionbutton">
				<img src="style/img/action/b_edit.png" title="'.$LG->g(35).'" alt="'.$LG->g(34).'" class="icon" width="16" height="16"/>
			</button>
			<button type="submit" disabled="disabled" name="del_slct_attr" value="'.$LG->g(36).'" title="'.$LG->g(37).'" class="actionbutton">
				<img src="style/img/action/b_drop.png" title="'.$LG->g(37).'" alt="'.$LG->g(36).'" class="icon" width="16" height="16"/>
			</button>
			-->
		</p>
	</form>';
}





// C O N S T R A I N T S
echo '<h2>'.$LG->g(50).'</h2>';
$ret = $AC->query($req_constraint);
displaySwitchVisibilityZone('roll_str2', display_code($LG->g(51).' : ', $req_constraint, $ret));

if(!$ret)
	echo display_code($LG->g(52).' : '.$AC->error(), $req_constraint, $ret);
else
{
	$rows = $AC->fetchAll();
	
	echo '
	<form method="post" action="'.rootURL($_SERVER['REQUEST_URI']).'">
	<table cellspacing="2" cellpadding="0" class="tab_constr">';
	
	// titles bar
	echo '
		<tr>
			<td class="empty"><input type="hidden" name="gourl" value="'.rootURL($_SERVER['REQUEST_URI']).'" /></td>
			<th>'.$LG->g(53).'</th>
			<th>'.$LG->g(54).'</th>
			<th>'.$LG->g(55).'</th>
			<th>'.$LG->g(56).'</th>
			<th>'.$LG->g(57).'</th>
			<th>'.$LG->g(58).'</th>
			<th>'.$LG->g(59).'</th>
			<th>'.$LG->g(60).'</th>
			<th>'.$LG->g(61).'</th>
			<th colspan="3">'.$LG->g(62).'</th>
		</tr>';

	// list
	$count = 0;
	foreach($rows as $row){
		// values
		echo '
		<tr '.(($count%2 == 0) ? 'class="even"' : 'class="odd"').' id="t_slct-c'.$count.'" onclick="switchSelectDisp(\'slct-c'.$count.'\');">
			<td class="count"><input type="checkbox" name="slct-c'.$count.'" id="slct-c'.$count.'" onclick="switchSelectDisp(\'slct-c'.$count.'\');" /></td>
			<td>'.$row['CONSTRAINT_NAME'].'<input type="hidden" name="cstname_'.$count.'" value="'.$row['CONSTRAINT_NAME'].'" /></td>
			<td>'.$row['CONSTRAINT_TYPE'].'</td>
			<td>'.str_replace("','", "', '", $row['SEARCH_CONDITION']).'</td>
			<td'.(($row['STATUS'] == 'DISABLED')?' class="disabled"':' class="enable"').'>'.$row['STATUS'].'</td>
			<td>'.(($row['DEFERRABLE'] = 'NOT DEFERRABLE') ? $LG->g(65) : $LG->g(66)).' <i>('.$row['DEFERRED'].')</i></td>
			<td'.(($row['VALIDATED'] == 'NOT VALIDATED')?' class="disabled"':' class="enable"').'>'.$row['VALIDATED'].'</td>
			<td>'.(($row['GENERATED'] == 'USER NAME') ? $LG->g(67) : $LG->g(68)).'</td>
			<td>'.$row['LAST_CHANGE'].'</td>
			<td>'.$row['INDEX_OWNER'].'</td>
			<td>
				<button type="submit" name="ena_uniq_cst" value="'.$row['CONSTRAINT_NAME'].'" title="'.$LG->g(71).'" class="actionbutton">
					<img src="style/img/action/eye.png" title="'.$LG->g(71).'" alt="'.$LG->g(70).'" class="icon" width="16" height="16"/>
				</button>
			</td>
			<td>
				<button type="submit" name="dis_uniq_cst" value="'.$row['CONSTRAINT_NAME'].'" title="'.$LG->g(73).'" class="actionbutton">
					<img src="style/img/action/eyebar.png" title="'.$LG->g(73).'" alt="'.$LG->g(72).'" class="icon" width="16" height="16"/>
				</button>
			</td>
			<td>
				<button type="submit" name="del_uniq_cst" value="'.$row['CONSTRAINT_NAME'].'" title="'.$LG->g(75).'" class="actionbutton">
					<img src="style/img/action/b_drop.png" title="'.$LG->g(75).'" alt="'.$LG->g(74).'" class="icon" width="16" height="16"/>
				</button>
			</td>
		</tr>';
		$count++;
	}
	echo '</table>
		<!-- selection -->
		<a href="" onclick="selectAll(\'slct-c\', 0, '.($count-1).', true); return false;">'.$LG->g(40).'</a> / 
		<a href="" onclick="selectAll(\'slct-c\', 0, '.($count-1).', false); return false;">'.$LG->g(41).'</a>
		
		<!-- edition -->
		'.$LG->g(42).'
		<button type="submit" name="cst_enable" value="Activer" title="'.$LG->g(81).'" class="actionbutton">
			<img src="style/img/action/eye.png" title="'.$LG->g(81).'" alt="'.$LG->g(80).'" class="icon" width="16" height="16"/>
		</button>
		<button type="submit" name="cst_disable" value="Désactiver" title="'.$LG->g(83).'" class="actionbutton">
			<img src="style/img/action/eyebar.png" title="'.$LG->g(83).'" alt="'.$LG->g(82).'" class="icon" width="16" height="16"/>
		</button>
		<button type="submit" name="cst_delete" value="Supprimer" title="'.$LG->g(85).'" class="actionbutton">
			<img src="style/img/action/b_drop.png" title="'.$LG->g(85).'" alt="'.$LG->g(84).'" class="icon" width="16" height="16"/>
		</button>
	</form>';
}





// T R I G G E R S
echo '<h2>'.$LG->g(90).'</h2>';
$ret = $AC->query($req_trigger);
displaySwitchVisibilityZone('roll_str3', display_code($LG->g(91).' : ', $req_trigger, $ret));

if(!$ret)
	echo display_error($AC->error());
else
{
	$rows = $AC->fetchAll();
	
	echo '
	<table cellspacing="2" cellpadding="0" class="tab_trig">';
	
	// titles bar
	echo '
		<tr>
			<td class="empty"></td>
			<th>'.$LG->g(92).'</th>
			<th>'.$LG->g(93).'</th>
			<th>'.$LG->g(94).'</th>
			<th>'.$LG->g(95).'</th>
			<th>'.$LG->g(96).'</th>
			<th>'.$LG->g(97).'</th>
			<th>'.$LG->g(98).'</th>
			<th>'.$LG->g(99).'</th>
		</tr>';
	// list
	$count = 0;
	foreach($rows as $row){
		// values
		echo '
		<tr '.(($count%2 == 0) ? 'class="even"' : 'class="odd"').' id="t_slct-t'.$count.'" onclick="switchSelectDisp(\'slct-t'.$count.'\');">
			<td class="count"><input type="checkbox" name="slct-t'.$count.'" id="slct-t'.$count.'"  onclick="switchSelectDisp(\'slct-t'.$count.'\');" /></td>
			<td>'.$row['TRIGGER_NAME'].'</td>
			<td>'.$row['TRIGGER_TYPE'].'</td>
			<td>'.$row['TABLE_OWNER'].'</td>
			<td>'.$row['TRIGGERING_EVENT'].'</td>
			<td>'.$row['STATUS'].'</td>
			<td>'.$row['DESCRIPTION'].'</td>
			<td>'.$row['ACTION_TYPE'].'</td>
			<td>'.$row['TRIGGER_BODY'].'</td>
		</tr>';
		$count++;
	}
	echo '</table>
	<p></p>';
}


?>
