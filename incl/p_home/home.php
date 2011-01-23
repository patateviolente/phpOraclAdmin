<?php

$LG = new Lang('home', $lang);

// Home - phpOraAdmin
echo '
<!-- RIGHT (info) -->
<div class="right_col">
	<div class="bloc_new">
		<div class="btitle h3">Oracle</div>
		<div class="bcontent">
			<ul>
				<li>'.$LG->g(0).$AC->getServ().'</li>
				<li>'.$LG->g(1).$AC->getUser().'</li>
			</ul>
		</div>
	</div>
	<div class="bloc_new">
		<div class="btitle h3">'.$LG->g(2).'</div>
		<div class="bcontent">
			<ul>
				<li>'.$_SERVER['SERVER_SOFTWARE'].'</li>
				<li>'.$LG->g(3).''.ENCODING.'</li>
				<li>'.$LG->g(4).(($pdo_plug)? 'PDO' :'OCI_').'</li>
			</ul>
		</div>
	</div>
</div>



<!-- LEFT (form) -->
<div class="left_col">
	<div class="bloc_new">
		<div class="btitle h3">'.$LG->g(10).'</div>
		<div class="bcontent">
			<!-- LANGUAGE -->
			<form action="save_param.php" method="post" onsubmit="
				if(document.getElementById(\'ajax1\').checked){
					alert(\''.addslashes($LG->g(11)).'\')
				}">
			<p>
				'.$LG->g(12);
				$d = opendir('lang/');
				while(($f = readdir($d))){
					if($f[0] != '.' && is_dir(LANG_FOLDER.$f)){
						$id = 'l_'.(($lang == $f) ? 'activ' : $f);
						echo '
					<input type="radio" name="lang" value="'.$f.'" id="'.$id.'"'.(($lang == $f)? ' checked="checked"':'').' />
						<label for="'.$id.'"><img src="'.LANG_FOLDER.$f.'/logo.gif" alt="logo '.$f.'" title="Passer la langue en '.$f.'" /> '.$f.'</label>';
					}
				}
				closedir($d);
				echo '
			</p>
			
			<!-- STYLE -->
			<p>
				'.$LG->g(15).'
				<select name="style" id="style">';
				$d = opendir('style/');
				while(($f = readdir($d))){
					if(preg_match('/\.css$/', $f))
						echo '
					<option value="'.$f.'" id="l_'.$f.'"'.(($style == $f)? ' selected="selected"':'').'>'.str_replace('.css', '', $f).'</option>';
				}
				closedir($d);
				echo '
				</select>
				
			</p>
			<p>'.$LG->g(16).'
				<input type="radio" name="ajax" value="1" id="ajax1"'.(($ajax) ? ' checked="checked"':'').' />
						<label for="ajax1">'.$LG->g(17).'</label>
				<input type="radio" name="ajax" value="0" id="ajax0"'.((!$ajax) ? ' checked="checked"':'').' />
						<label for="ajax0">'.$LG->g(18).'</label>
			</p>
			<p>
				<input type="submit" name="editparam" value="'.$LG->g(19).'" />
			</p>
			</form>
		</div>
	</div>
</div>
<div style="clear:both;"></div>';


?>
