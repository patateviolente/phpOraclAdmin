<?php

if(!defined('IN_POA')) include('../../info.php');
$LG = new Lang('u_search', $lang);

/////////////////////////////////////
// D  I  S  P  L  A  Y
if(isset($_POST['sel']) && isset($_POST['table']))
{
	// redir to search page in table $_POST['userachselect']
	header('Location: ../index.php?user='.$_GET['user'].'&table='.$_POST['table'].'&page=t_search');
}

/////////////////////////////////////
// D  I  S  P  L  A  Y
$req = 'SELECT TABLE_NAME N FROM USER_TABLES';
$ret = $AC->query($req);
$rows = $AC->fetchAll();
echo '
<form method="post" action="'.jsURL($_SERVER['REQUEST_URI']).'">
	<p><select name="table" class="userachselect" size="'.(count($rows)+0).'">';
		$first = true;
		foreach($rows as $r){
			echo '<option value="'.$r['N'].'"'.(($first)?' selected=selected"':'').'>'.$r['N'].'</option>';
			if($first) $first = false;
		}
	echo '
	</select></p>
	<p>
		<input type="submit" name="sel" value="'.$LG->g(0).'" />
	</p>
</form>';


?>
