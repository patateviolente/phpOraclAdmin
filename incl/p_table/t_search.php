<?php

// Language + lang
if(!defined('IN_POA')) include('../../info.php');
$LG = new Lang('t_search', $lang);


// Get attribute array
$req = '
	SELECT COLUMN_NAME N, 	DATA_TYPE T,
		DATA_LENGTH LEN, DATA_PRECISION PRE, DATA_SCALE SCL
	FROM USER_TAB_COLUMNS
	WHERE TABLE_NAME = \''.$_GET['table'].'\'';
$ret = $AC->query($req);
if(!$ret){
	echo display_error($AC->error());
	$fin = true;
} else
	$attr = $AC->fetchAll();


/////////////////////////////////////////////////////////
// P R O C E S S
if(isset($_POST['search'])){
	// init
	$sign = array('eq', 'gt', 'ge', 'lt', 'le', 'ne', 'like', 'likejoker', 'isnull', 'isnotnull', 'notlike');
	$op =   array(' = ', ' > ', ' >= ', ' < ', ' <= ', ' <> ', ' LIKE ', ' LIKE ', ' IS NULL ', ' IS NOT NULL ', ' NOT LIKE ');
	$leftop = array(true, true, true,   true,   true,   true,    true,    true,        false,         false,          false);
	$where = $orderby = '';
	
	// where
	if(empty($_POST['where'])){
		// each params
		for($i=0; $i<count($_POST['count']); $i++){
			// each attributes
			foreach($attr as $at){
				
				if(!empty($_POST['val'.$at['N'].$i])){
					$s = $_POST['op'.$at['N'].$i];		// ne, like, ... (operator)
					$pos = array_search($s, $sign);
					if($pos == -1) continue;
					
					$where .= ' '.
						$at['N']			// atribute name
						.$op[$pos];			// sql operator
					if($leftop[$pos]){	
						if($s == 'likejoker')
							$where .= '\'%'.addslashes($_POST['val'.$at['N'].$i]).'%\' OR ';
						else
							$where .= '\''.addslashes($_POST['val'.$at['N'].$i]).'\' OR ';
					} else $where .= '    ';
				}
			}
		}
	} else	// manual where
		$where = addslashes($_POST['where']);
	if(strlen($where) == 0) $continue = true;		// empty form !
	$where = substr($where, 0, -4);
	
	if(empty($where)) $where = '1=1';
	
	// order by
	if(!empty($_POST['orderby']))
		$orderby = ' ORDER BY '.$_POST['orderby'].' '.$_POST['sort'];
	
	// final req, include display
	$req = 'SELECT * FROM '.$_GET['table'].' WHERE '.$where.$orderby;
	$_POST['req'] = $req;
	include('t_display.php');
}

	
/////////////////////////////////////////////////////////
// D I S P L A Y
else if(isset($continue) || !isset($fin))
{
	echo '
	<form method="post" action="'.rootURL($_SERVER['REQUEST_URI']).'">
		<fieldset>
		<legend>'.$LG->g(0).'</legend>
		<div class="top">
		<table cellspacing="2" cellpadding="0" class="searchtable">
			<tr>
				<th>'.$LG->g(1).'</th>
				<th>'.$LG->g(2).'</th>
				<th>'.$LG->g(3).'</th>
				<th>'.$LG->g(4).'</th>
			</tr>';
			$id = 0;
			$count = 0;
			// Display each lines
			foreach($attr as $row){
				
				echo '
			<tr '.(($count++%2 == 0) ? 'class="even"' : 'class="odd"').'>
				<td>'.$row['N'].'</td>';
				
				// according type
				if($row['PRE'] != null){	// N U M B E R
					echo '<td>'.$row['T'].' ('.$row['PRE'].', '.$row['SCL'].')</td>';
					echo '<td>
						<select name="op'.$row['N'].$id.'">
							<option value="eq">=</option>
							<option value="gt">&gt;</option>
							<option value="ge">&gt;=</option>
							<option value="lt">&lt;</option>
							<option value="le">&lt;=</option>
							<option value="ne">!=</option>
							<option value="like">LIKE</option>
							<option value="isnull">IS NULL</option>
							<option value="isnotnull">IS NOT NULL</option>
						</select>
						</td>
						<td>
							<input type="text" name="val'.$row['N'].$id.'" value="" />
						</td>';
				} else{						// T E X T
					echo '<td>'.$row['T'].' ('.$row['LEN'].')</td>';
					echo '<td>
						<select name="op'.$row['N'].$id.'">
							<option value="like">LIKE</option>
							<option value="likejoker" selected="selected">LIKE %..%</option>
							<option value="notlike">NOT LIKE</option>
							<option value="eq">=</option>
							<option value="ne">!=</option>
							<option value="isnull">IS NULL</option>
							<option value="isnotnull">IS NOT NULL</option>
						</select>
					</td>
					<td><input type="text" name="val'.$row['N'].$id.'" maxlength="'.$row['LEN'].'"  /></td>';
				}
				echo '</tr>';
			}
			echo '</table>';
			
			// Where + orderby + count
			echo '
				<fieldset class="searchopt">
					<input type="hidden" name="count" value="'.++$id.'" /> 
					<legend>'.$LG->g(10).'</legend>
					<p><label for="where">'.$LG->g(11).'
						<input type="text" value="" name="where" id="where" /></label><p>
					<p><label for="nblines">'.$LG->g(12).'</label>
						<input type="text" name="nblines" id="nblines" value="30" /></p>
					<p>'.$LG->g(13).'
					<select name="orderby">	
						<option value="" selected="selected"></option>';
						foreach($attr as $att)
							echo '<option value="'.$att['N'].'">'.$att['N'].'</option>';
						echo '
					</select>
					<input type="radio" name="sort" value="ASC" id="asc" checked="checked" /> <label for="asc">'.$LG->g(14).'</label>
					<input type="radio" name="sort" value="DESC" id="desc" selected="selected" /> <label for="desc">'.$LG->g(15).'</label> 
					</p>
				</fieldset>
			</div>
			<div class="bottom">
				<input type="submit" name="search" value="'.$LG->g(20).'" />
			</div>
		</fieldset>
	</form>';
}


?>
