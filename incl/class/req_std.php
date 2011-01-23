<?php

/***************************************************************************
 *   phpOraclAdmin - Oracle manager, phpMyAdmin like
 *   Page incl/Class/req_std.php - Frequent queries
 *   
 *   Copyright            : (C) 2010 - 
 *   Creator              : Patrick Portal - Brice Hyaumet
 *   Site-web             : http://xn--thta-hpa.net/poa
 *   Em@il                : See website
 *   See GNU conditions on index.php head
 ***************************************************************************/


/**
 * @return One primary key. Else return empty string
 * @param $c Connexion
 * @param $table Table
 */
function getPrimaryKey($C, $table){
	$ret = $C->query("
		select UCC.COLUMN_NAME 
		from USER_CONSTRAINTS CST 
		join USER_CONS_COLUMNS UCC on CST.CONSTRAINT_NAME = UCC.CONSTRAINT_NAME
		where CST.TABLE_NAME = '".$table."' and CST.CONSTRAINT_TYPE = 'P'");
	if(!$ret) return '';
	$d = $C->fetchAll();
	if(count($d) == 0) return '';
	else return $d[0][0];
}


/**
 * @return SQL code to build the table.
 * @param $c Connexion
 * @param $table Table
 * @param $struct Export  structures
 * @param $data Export datas
 * @param $drop Export drop
 */
function getStructCode($C, $tables, $struct = true, $data = true, $drop = false){
	$txt = '';
	// each lines
    foreach ($tables as $tab)
	{
		// drops
		if($drop) $txt .= 'DROP TABLE '.$tab.";\n";
		// then structures
		if($struct){
			$txt .= getSqlCreateTable($C, $tab, true);
			//$txt .= $_table->getSqlCreateIndexes();
		}
		// then rows
		//if($data) $_table->printSqlInsertRows($page->getExportCompleteIns(), $page->getExportLobs());
	}
	return $txt;
}


function getSqlCreateTable($C, $table_name, $showHeader = false){
	$cons_type_arr = array('P' => 'PRIMARY KEY', 'R' => 'FOREIGN KEY', 'U' => 'UNIQUE');
	$owner = $C->getUser();
	$txt = '';
	// VIEW
	if (false) {
		//$txt = '';
		// Comments
		//if ($showHeader) {
		//	$txt .= "/*==============================================================*/\n".
		//	"/* View: $table_name". str_repeat(' ', 55 - strlen($table_name)). "*/\n".
		//	"/*==============================================================*/\n\n";
		//}
		//$contents .= 'CREATE OR REPLACE VIEW '.$table_name.' AS\n'.$table_name.'\n/\n\n\n';
    }
	// TABLE
	if(true) {
		// Comments
		if ($showHeader) {
			$txt .= "\n/*==============================================================*/\n".
"/* Table: $table_name". str_repeat(' ', 54 - strlen($table_name)). "*/\n".
"/*==============================================================*/\n\n";
		}
		
		$txt .= 'CREATE TABLE '.$table_name." (\n";
		
		// list attributes
		$ret = $C->query('
			select TABLE_NAME, COLUMN_NAME N, DATA_TYPE T, DATA_LENGTH LEN, NULLABLE NUL, DEFAULT_LENGTH DFT, 
				DATA_DEFAULT, NUM_DISTINCT, DENSITY, NUM_NULLS, CHARACTER_SET_NAME 
			from USER_TAB_COLUMNS 
			where TABLE_NAME = \''.$table_name.'\'');
		if(!$ret) return '_1_';
		
		$rows = $C->fetchAll();
		for ($i = 0; $i < count($rows); $i++) {
			if ($i > 0) $txt .= ",\n";			// no , after create
			$txt .= '  '. $rows[$i]['N'] . ' ' ; $rows[$i]['T'].
				(isset($rows[$i]['LEN']) ? '('. $rows[$i]['LEN']. ')' : '').
				(isset($rows[$i]['DFT']) ? ' DEFAULT '. $rows[$i]['DFT'] : '').
				($rows[$i]['NUL'] == 'N' ? ' NOT NULL' : '');
		}
			
		
		// list constraints
		$ret = $C->query('SELECT C.CONSTRAINT_NAME, C.CONSTRAINT_TYPE, C.R_CONSTRAINT_NAME, C.DELETE_RULE, CC.COLUMN_NAME
			FROM ALL_CONSTRAINTS C, ALL_CONS_COLUMNS CC
			WHERE C.OWNER=\''.strtoupper($C->getUser()).'\' AND C.TABLE_NAME=\''.strtoupper($table_name).'\' AND C.CONSTRAINT_TYPE!=\'C\'
				AND C.CONSTRAINT_NAME=CC.CONSTRAINT_NAME AND C.OWNER=CC.OWNER AND C.TABLE_NAME=CC.TABLE_NAME
			ORDER BY C.CONSTRAINT_TYPE, C.CONSTRAINT_NAME, CC.POSITION');
		if(!$ret) return '_2_';
		$rows = $C->fetchAll();
		$row = $rows;
		$n = count($row);
		
		for ($i=0; $i<$n; $i++) {
			$txt .= ",\n  constraint " . ($cons_name = $row[$i]['CONSTRAINT_NAME']). ' '.
				$cons_type_arr[($cons_type = $row[$i]['CONSTRAINT_TYPE'])].' ('.$row[$i]['COLUMN_NAME'];
			
			//while ($cons_name == $rows[$i+1]['CONSTRAINT_NAME'] && !empty($cons_name))
			//	$txt .= ', '.$row[++$i]['COLUMN_NAME'];
			$txt .= ')'; 
			
			
			// Included type constraints
			if ($cons_type == 'R') {
				$ret = $C->query('SELECT TABLE_NAME, COLUMN_NAME
					FROM ALL_CONS_COLUMNS
					WHERE OWNER=\''.$owner.'\' AND CONSTRAINT_NAME=\''.$row[$i]['R_CONSTRAINT_NAME'].'\'
					ORDER BY POSITION');
				if(!$ret) return '_3_';
				$rows = $C->fetchAll();
				if(count($rows) == 0) break;
				$row = $rows[0];
			
				$references = $row['TABLE_NAME'];
				$txt .= "\n    REFERENCES ".$references.' (';
				
				$col_name = '';
				//do {
					if ($col_name) $txt .= ', ';
					$txt .= $col_name = $row['COLUMN_NAME'];
				//} while (ocifetch($ret));
				
				$txt .= ')';
				// ON DELETE
				//if ($row['DELETE_RULE'][$i] != 'NO ACTION')
				//	$txt .= "\n    ON DELETE ", $row['DELETE_RULE'][$i];
			}
		}
      $txt .= "\n)\n/\n\n";
    }
	
	return $txt;
}

?>
