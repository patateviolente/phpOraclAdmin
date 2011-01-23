<?php

// cnx & lang
if(!defined('IN_POA')) include('../../info.php');

//////////////////////////////////////////////////
// P  R  O  C  E  S  S
if(isset($_POST['newtable']) && !empty($_POST['newtable'])){
	
	$req = "CREATE TABLE ".$_POST['tablename']." (\n";
	$cst = '';
	$total = 0;
	// each field
	for($i=0; $i<$_POST['nbfields']; $i++){
		if(!empty($_POST['name'.$i]) && !empty($_POST['type'.$i])){
			$total++;
			$req .= "\t".$_POST['name'.$i]."\t".$_POST['type'.$i]."(".$_POST['size'.$i].")".
				((isset($_POST['isnull'.$i])) ? '' : "\tnot null").
				((empty($_POST['dft'.$i])) ? '' : "\tdefault '".$_POST['dft'.$i]."'").
				',';
			if(!empty($_POST['key'.$i])){
				$cst .= "\n\tconstraint cle".substr($_POST['key'.$i], 0, 3)."_".$_POST['name'.$i].
				(($_POST['key'.$i]) ? ' primary key' : ' foreign key').'('.$_POST['name'.$i].'),';
			}
		}
	}
	// attr + constr
	$cst = empty($cst) ? $cst : substr($cst, 0, -1);	// remove ','
	if(empty($cst)) $req = substr($req, 0, -1);
	$req .= $cst;
	$req .= "\n)";
	
	// -> show sql to user
	if($total > 0){
		$end = true;
		$reqthis = $req;
		include('incl/p_user/u_sql.php');
	}
}



if(!isset($end))
{
// C O N S T
$nbfields = isset($_POST['nbcol']) ? $_POST['nbcol'] : 6;
$types = array(
	'Dates' => '',
	'DATE' => "repr�sente une valeur de date et d'heure comprises entre le 1er janvier 4712 BC et le 31 d�cembre 4712 AD.", 
	'TIME' => "repr�sente une valeur horaire avec des heures comprises entre 00 et 23, des minutes comprises entre 00 et 59 et des secondes entre 00 et 61.999. Le nombre Nb indique le nombre de chiffres dans les secondes, compris entre 0 et 6.", 
	'TIMESTAMP' => "utilis� pour le sockage, repr�sente des valeurs de date : ann�e, mois et jour, et des valeurs horaires : heure, minute et seconde.", 
	
	'Texte' => "",
	'CHAR' => "repr�sente une cha�ne de caract�res non-Unicode d'une longueur fixe d'un maximum de 4 000 caract�res. L'instruction VARYING permet d'adopter une longueur variable � l'image de VARCHAR.",
	'VARCHAR2' => "repr�sente une cha�ne de caract�res d'une longueur variable maximum de 4 096 octets.", 
	'LONG VARCHAR' => "repr�sente une cha�ne de caract�res d'une longueur variable maximum de 2 gigaoctets.", 
	
	'Entiers' => "",
	'INTEGER' => "repr�sente un nombre entier compris entre -231 et 231.",
	'BIGINT' => "repr�sente un nombre entier avec une pr�cision de 19 chiffres compris entre -10^19 et 10^19.",
	'TINYINT' => "repr�sente un nombre entier compris entre -128 et +127.",
	'SMALLINT' => "repr�sente une valeur num�rique enti�re comprise entre -32 768 et 32 767.",
	
	'R�els' => "",
	'NUMBER' => "repr�sente un nombre avec une pr�cision p de 1 � 38 chiffres et un �chelle e comprise dans l'intervalle de -84 � 127.",
	'NUMERIC' => "repr�sente un nombre avec une pr�cision et une �chelle comprises entre 0 et 38.",
	'DECIMAL' => "repr�sente un nombre d�cimal avec une certaine pr�cision p et une certaine �chelle e comprises entre 0 et 38.",
	'REAL' => "repr�sente un nombre � virgule flottante de simple pr�cision, compris entre 10^-38 et 10^38.",
	'FLOAT' => "repr�sente un nombre � virgule flottante avec une certaine pr�cision, compris entre 10^-308 et 10^308.",
	'DOUBLE PRECISION' => "repr�sente un nombre � virgule flottante de double pr�cision, compris entre 10^-308 et 10^308.",

	'Binaire' => "",
	'BIT' => "repr�sente la valeur d'un bit, soit 0 ou 1",
	'BINARY' => "repr�sente une valeur binaire de taille fixe d'un maximum de 4 096 octets",
	'VARBINARY' => "utilis� pour le stockage, repr�sente une valeur binaire d'une longueur variable.",
	'LONG VARBINARY' => "utilis� pour le stockage, repr�sente des donn�es binaires bruts d'une taille varaiable maximum de 2 gigaoctets.",
	'ROWID' => "repr�sente une valeur hexad�cimale de 16 octets repr�sentant l'adresse unique d'une ligne de tableau.",

	'Stockage' => "",
	'RAW' => "repr�sente des donn�es binaires bruts d'une taille maximum de 2 000 octets.",
	'LONG RAW' => "repr�sente des donn�es binaires bruts d'une taille maximum de 2 gigaoctets.",
	'BLOB' => "repr�sente une grande valeur binaire d'une taille d'un maximum de 2 147 483 647 octets.",
	'CLOB' => "repr�sente une grande cha�ne de caract�res UNICODE-UCS-2 d'une longueur variable d'un maximum de 2 gigaoctets.",
);





//////////////////////////////////////////////////
// D  I  S  P  L  A  Y
echo '
<form method="post" action="'.rootURL($_SERVER['REQUEST_URI']).'">
	<fieldset>
		<legend>Edition des attributs : table 
			<input type="text" name="tablename" value="'.(isset($_POST['name']) ? $_POST['name'] : '').'" size="20" />
		</legend>
	<div class="top">
	<table cellspacing="0" cellpadding="0" class="tab_table">
		<tr>
			<th>Nom</th>
			<th>Type</th>
			<th>Taille</th>
			<th>D�faut</th>
			<th>Null</th>
			<th>Index</th>
			<th>Commentaires</th>
		</tr>
	';
	
	for($i=0; $i<$nbfields; $i++){
		echo '
		<tr>
			<td><input type="text" name="name'.$i.'" value="" size="20" /></td>
			<td>
				<select name="type'.$i.'">
					<option value=""></option>';
					foreach($types as $nom => $def){
						if(empty($def)) echo '</optgroup><optgroup label="'.$nom.'">';
						else echo '<option value="'.$nom.'">'.$nom.'</option>';
					}
				echo '
					</optgroup>
				</select>
			</td>
			<td><input type="text" name="size'.$i.'" value="" size="6" /></td>
			<td><input type="text" name="dft'.$i.'" value="" size="6" /></td>
			<td><input type="checkbox" name="isnull'.$i.'" /></td>
			<td>
				<select name="key'.$i.'">
					<option value=""></option>
					<option value="PRIMARY">PRIMARY</option>
					<option value="FOREIGN">FOREIGN</option>
				</select>
			</td>
			<td><input type="text" name="comment'.$i.'" value="" size="25" /></td>
		</tr>
		';
	
	}
	
	echo '
	</table>
	</div>
	<div class="bottom">
		<input type="hidden" name="nbfields" value="'.$nbfields.'" />
		<input type="submit" name="newtable" value="G�n�rer le code SQL de la table" />
	</div>
	</fieldset>
</form>';

};


?>