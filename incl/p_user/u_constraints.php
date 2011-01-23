<?php

if(!defined('IN_POA'))	include('../../info.php');

///////////////////////////////////////////////
// P  R  O  C  E  S  S
// CONSTRAINTS
//   -> several
if(isset($_POST['cst_enable']) || isset($_POST['cst_disable']) || isset($_POST['cst_delete'])){	
	if(isset($_POST['cst_enable'])) 		$cmd = 'ENABLE';
	else if(isset($_POST['cst_disable']))	$cmd = 'DISABLE';
	else									$cmd = 'DROP';
	
	$enable = isset($_POST['cst_enable']);
	$req = 'ALTER TABLE ';
	// search checked values
	foreach($_POST as $k => $p){
		if(preg_match('/^slct-c[0-9]+$/', $k)){
			$offset = preg_replace('/^.*([0-9]+).*$/', '$1', $k);
			$toreq = $req.$_POST['csttable_'.$offset].' '.$cmd.' CONSTRAINT '.$_POST['cstname_'.$offset];
			$ret = $AC->query($toreq);
			echo display_code('Contrainte ['.$cmd.']'.(($ret)?'':', erreur : '.$AC->error()), $toreq, $ret);
		}
	}
}
//    -> unique enable/ disable
else if(isset($_POST['ena_uniq_cst']) || isset($_POST['dis_uniq_cst']) || isset($_POST['del_uniq_cst'])){
	if(isset($_POST['ena_uniq_cst'])){ 		$cmd = 'ENABLE'; $field = 'ena_uniq_cst'; }
	else if(isset($_POST['dis_uniq_cst'])){	$cmd = 'DISABLE'; $field = 'dis_uniq_cst'; }
	else{									$cmd = 'DROP'; $field = 'del_uniq_cst'; }
	$NandV = explode(';', $_POST[$field]);
	$cst = $NandV[1];
	$tabname = $NandV[0];
	
	$req = 'ALTER TABLE '.$tabname.' '.$cmd.' CONSTRAINT '.$cst;
	$ret = $AC->query($req);
	echo display_code('Contrainte ['.$cmd.']'.(($ret)?'':', erreur : '.$AC->error()), $req, $ret);
}







///////////////////////////////////////////////
// D I S P L A Y
// REQS
$req_constraint = '
	select CONSTRAINT_NAME, CONSTRAINT_TYPE, SEARCH_CONDITION,
		STATUS, DEFERRABLE, DEFERRED, VALIDATED, GENERATED, LAST_CHANGE, INDEX_OWNER, TABLE_NAME
	from USER_CONSTRAINTS 
	where TABLE_NAME NOT LIKE \'BIN%\'
	order by TABLE_NAME';


$ret = $AC->query($req_constraint);
displaySwitchVisibilityZone('roll_str2', display_code('Requète de contraintes : ', $req_constraint, $ret));

if(!$ret)
	echo display_code('Contraites : '.$AC->error(), $req_constraint, $ret);
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
			<th>Nom</th>
			<th>Type</th>
			<th>Condition</th>
			<th>Status</th>
			<th>Déférabilité</th>
			<th>Validité</th>
			<th>Nommage</th>
			<th>Edition</th>
			<th>Propriétaire</th>
			<th colspan="3">Action</th>
		</tr>';

	// list
	$count = 0;
	foreach($rows as $row){
		// values
		echo '
		<tr '.(($count%2 == 0) ? 'class="even"' : 'class="odd"').' id="t_slct-c'.$count.'" onclick="switchSelectDisp(\'slct-c'.$count.'\');">
			<td class="count"><input type="checkbox" name="slct-c'.$count.'" id="slct-c'.$count.'" onclick="switchSelectDisp(\'slct-c'.$count.'\');" /></td>
			<td>'.$row['CONSTRAINT_NAME'].'<input type="hidden" name="cstname_'.$count.'" value="'.$row['CONSTRAINT_NAME'].'" />
				<input type="hidden" name="csttable_'.$count.'" value="'.$row['TABLE_NAME'].'" /></td>
			<td>'.$row['CONSTRAINT_TYPE'].'</td>
			<td>'.str_replace("','", "', '", $row['SEARCH_CONDITION']).'</td>
			<td'.(($row['STATUS'] == 'DISABLED')?' class="disabled"':' class="enable"').'>'.$row['STATUS'].'</td>
			<td>'.(($row['DEFERRABLE'] = 'NOT DEFERRABLE') ? 'Non' : 'Oui').' <i>('.$row['DEFERRED'].')</i></td>
			<td'.(($row['VALIDATED'] == 'NOT VALIDATED')?' class="disabled"':' class="enable"').'>'.$row['VALIDATED'].'</td>
			<td>'.(($row['GENERATED'] == 'USER NAME')?'utilisateur':'auto.').'</td>
			<td>'.$row['LAST_CHANGE'].'</td>
			<td>'.$row['INDEX_OWNER'].'</td>
			<td>
				<button type="submit" name="ena_uniq_cst" value="'.$row['TABLE_NAME'].';'.$row['CONSTRAINT_NAME'].'" title="Activer la contrainte" class="actionbutton">
					<img src="style/img/action/eye.png" title="Activer la contrainte" alt="Activer" class="icon" width="16" height="16"/>
				</button>
			</td>
			<td>
				<button type="submit" name="dis_uniq_cst" value="'.$row['TABLE_NAME'].';'.$row['CONSTRAINT_NAME'].'" title="Activer la contrainte" class="actionbutton">
					<img src="style/img/action/eyebar.png" title="Désactiver la contrainte" alt="Désactiver" class="icon" width="16" height="16"/>
				</button>
			</td>
			<td>
				<button type="submit" name="del_uniq_cst" value="'.$row['TABLE_NAME'].';'.$row['CONSTRAINT_NAME'].'" title="Supprimer la contrainte" class="actionbutton">
					<img src="style/img/action/b_drop.png" title="Désactiver la contrainte" alt="Supprimer" class="icon" width="16" height="16"/>
				</button>
			</td>
		</tr>';
		$count++;
	}
	echo '</table>
		<!-- selection -->
		<a href="" onclick="selectAll(\'slct-c\', 0, '.($count-1).', true); return false;">Tout cocher</a> / 
		<a href="" onclick="selectAll(\'slct-c\', 0, '.($count-1).', false); return false;">Tout décocher</a>
		
		<!-- edition -->
		Pour la sélection : 
		<button type="submit" name="cst_enable" value="Activer" title="Activer la contrainte" class="actionbutton">
			<img src="style/img/action/eye.png" title="Activer la contrainte" alt="Activer" class="icon" width="16" height="16"/>
		</button>
		<button type="submit" name="cst_disable" value="Désactiver" title="Déscativer la contrainte" class="actionbutton">
			<img src="style/img/action/eyebar.png" title="Désactiver la contrainte" alt="Désactiver" class="icon" width="16" height="16"/>
		</button>
		<button type="submit" name="cst_delete" value="Supprimer" title="Supprimer la contrainte" class="actionbutton">
			<img src="style/img/action/b_drop.png" title="Supprimer" alt="Supprimer" class="icon" width="16" height="16"/>
		</button>
	</form>';
}



?>
