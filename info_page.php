<?php

/***************************************************************************
 *   phpOraclAdmin - Oracle manager, phpMyAdmin like
 *   Page info_page.php - Define menus/ logos/ url for all 3 sections
 *   
 *   Copyright            : (C) 2010 - 
 *   Creator              : Patrick Portal - Brice Hyaumet
 *   Site-web             : http://xn--thta-hpa.net/poa
 *   Em@il                : See website
 *   See GNU condition on index.php head
 ***************************************************************************/

// dependencies (config + lang)
include('incl/class/selectpage.php');
include('incl/class/Lang.php');
include('config.php');

// define global language
$LG = new Lang('a_global', $lang);
$sectNum = getPageCategory();


////////////// P A G E S inclusion + M E N U data /////////////////////
// Reminder : [0]=home pages [1]=user pages [2]=table pages
// MENU ('name', 'link', 'image', danger)		-> img in style/img/menu/
$incl_folder = array('incl/p_home/', 'incl/p_user/', 'incl/p_table/');
$m_activ = array(0, 0, 1);		// activated page offset (init by DEFAULT PAGE)
$m = array(
	array(	// HOME
		array($LG->g(20),'home.php', 's_db.png', false), 
		array($LG->g(21), 'u_sql.php', 'b_sql.png', false),
		//array('Recherche', 'b_search.php', 's_db.png', false),
		//array('Export', 'b_export.png', 's_db.png', false),
		//array('Import', 'b_import.png', 's_db.png', false),
		//array('Infos', 'b_infos.php', 's_db.png', false) 
		),
	array(	// USER
		array($LG->g(30),'u_structure.php', 'b_props.png', false), 
		array($LG->g(34), 'u_constraints.php', 'eye.png', false),
		array($LG->g(32), 'u_sql.php', 'b_sql.png', true),
		array($LG->g(31), 'u_search.php', 's_db.png', false),
		array('Table', 'u_table.php', 's_db.png', false),
		//array($LG->g(23), 'u_import.php', 'b_tblexport.png', false),
		array($LG->g(22), 'u_export.php', 'b_sql.png', false),
		//array('Infos', 'u_infos.php', 's_db.png', false),
		//array($LG->g(33), 'u_delete.php', 'b_deltbl.png', true)
		),
	array(	// TABLE
		array($LG->g(40),'t_display.php', 'b_browse.png', false), 
		array($LG->g(41),'t_structure.php', 'b_props.png', false), 
		array($LG->g(42), 't_sql.php', 'b_sql.png', false),
		array($LG->g(43), 't_search.php', 'b_search.png', false),
		array($LG->g(44), 't_insert.php', 'b_insrow.png', false),
		array($LG->g(22), 't_export.php', 'b_tblexport.png', false),
		array($LG->g(45), 't_operations.php', 'b_tblops.png', false),
		array($LG->g(46), 't_emptyout.php', 'b_empty.png', true),
		array($LG->g(47), 't_delete.php', 'b_deltbl.png', true) 
		) 
	);


// selected page (to update $m_activ and get page selected offset)
if(isset($_GET['page'])){
	for($i=0; $i<count($m[$sectNum]); $i++)
		if($m[$sectNum][$i][1] == $_GET['page'].'.php'){
			$m_activ[$sectNum] = $i;
			break;
		}
}

// page incl authorization, every pages in $m will be added
for($i=0; $i<count($m_activ); $i++)
	for($u=0; $u<count($m[$i]); $u++)
		$pages_auth[$i][$u] = $m[$i][$u][1];


?>
