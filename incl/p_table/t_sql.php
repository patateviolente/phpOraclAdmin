<?php

// lang & connexion infos (not directely included)
if(!defined('IN_POA') && isset($_POST['req'])){	// need $AC to execute
	include('../../info.php');
	@header("Content-Type: text/html; charset=".ENCODING_AJAX);
}
else	$LG = new Lang('t_sql', $lang);


//////////////////////////////////////////////
// P R O C E S S
if(isset($_POST['req'])){
	// For triggers : parse ; into Create Trigger ... /
	//die(nl2br($_POST['req']));
	$_POST['req'] = preg_replace('#(/\*).*?(\*/)#', '', $_POST['req']);		// remove comments /* ...*/
	$_POST['req'] = preg_replace('#\-\-.*?\n#', '', $_POST['req']);		// remove comments -- ... \n
	$_POST['req'] = preg_replace('#(create +trigger)([^/;]+(/\*\*/)?[^/;]+?);#i', "\\1\\2/**/;", $_POST['req']);		// place /**/ before ; => /**/;
	$_POST['req'] = preg_replace('#end\s*;\s*/#i', "end/**/;;", $_POST['req']);		// place /**/ before ; => /**/;
	
	$_POST['req'] = preg_replace('#([^/]);#', '$1 ;', $_POST['req']);	// Add blank for standard ;
	$lines = preg_split("#[^/][;]#", $_POST['req']);			// slip ; not preceding / (for /**/)
	
	$executed = '';
	$nbtrue = 0; $nbfalse = 0;
	$nbreq = count($lines);
	foreach($lines as $line)		// Commandes
	{
		if(isset($fin)) break;
		// Empty line
		if(empty($line) || preg_match('/^\s+$/', $line)) continue;
		
		// SELECT
		if(preg_match('/^ *select/i', $line) && $nbreq == 1){
			$req_FORCE = $line;
			header("Content-Type: text/html; charset=".ENCODING_AJAX);
			include('t_display.php');
		} 
		// OTHER
		else{
			$ret = $AC->query($line);
			if(!$ret){
				echo display_code($LG->g(0).$AC->error(), $line, false);
				if(isset($_POST['breakOnError'])) $fin = true;
			} else{
				$executed .= '<br />'.$line;
				$nbtrue++;
			}
		}
	}
	if($nbtrue > 0 && !isset($fin))
		echo display_code($LG->g(1), $executed, true);
}




////////////////////////////////////////////////
// DISPLAY
else{	
	echo '
<form action="'.
	renewPageArg(
		((!empty($_GET['table'])) ? 't_sql' : 'u_sql'), 
		rootURL($_SERVER['REQUEST_URI'])).'" method="POST" 
	onsubmit="writediv((getElementById(\'directdispl\').checked) ? \'result_rea\' : \'c_content\', 
			filePost(\'incl/p_table/t_sql.php'.getURLArgs($_SERVER['REQUEST_URI']).'\', \'req=\'+document.getElementById(\'req\').value, true)); return !ajax;">
	<fieldset id="exe_sql">
		<legend> "'.$LG->g(10).$AC->getServ().'" 
			<a href="#" onclick="reqheight += 30; getElementById(\'req\').style.height = reqheight; return false;">'.$LG->g(11).'</a> 
			<a href="#" onclick="reqheight -= 30; getElementById(\'req\').style.height = reqheight; return false;">'.$LG->g(12).'</a> 
		</legend>
		<div class="top">
			<textarea name="req" id="req">';
			if(isset($_POST['req']))		echo $_POST['req'];
			else if(isset($reqthis))		echo $reqthis;
			else if(!empty($_GET['table'])) echo 'SELECT * FROM '.$_GET['table'].' WHERE 1=1';
			echo '</textarea>
		</div>
		<div class="bottom">
			<input type="submit" name="execute" value="'.$LG->g(13).'" /> 
			<input type="checkbox" id="directdispl" name="directdispl" checked="checked" />
				<label for="directdispl">'.$LG->g(14).'</label> || 
			<input type="checkbox" id="breakOnError" name="breakOnError" />
				<label for="breakOnError">'.$LG->g(15).'</label>		
		</div>
	</fieldset>
</form>
<p></p>
<div id="result_rea"></div>
';

}


?>
