<?php

/***************************************************************************
 *   phpOraclAdmin - Oracle manager, phpMyAdmin like
 *   Page incl/Class/Connexion.php - Connexion assistant
 * 									-> for PDO or oci8_ plugin
 *   
 *   Copyright            : (C) 2010 - 
 *   Creator              : Patrick Portal - Brice Hyaumet
 *   Site-web             : http://xn--thta-hpa.net/poa
 *   Em@il                : See website
 *   See GNU conditions on index.php head
 ***************************************************************************/

/** 
 * This class allow user to connect to a database using different
 * plug-in, and use those defined fonctions to get the same results.
 * @author Patrick Portal, Brice Hyaumet
 */
class Connexion{
	// variables 
	private $error = 'No error registered.';		// last message error
	private $db, $qu;					// PDO or oci connexion element
	private $serv, $user, $pswd, $pdo;	// logs vars are duplicated
	
		
	
					/**********************
						I N I T   &   C O N N E C T
								**********************/
	/**
	 * Import server data to the class, from config_bdd file.
	 * @param Offset of connexions logs
	 */
	public function __construct($offset){
		global $pdo_plug, $user_bdd, $pswd_bdd, $server_bdd;
		$this->type = ($pdo_plug) ? 'pdo' : 'oci';
		if($offset >= 0){
			$this->pdo = ($this->type == 'pdo');
			$this->serv = $server_bdd[$offset];
			$this->user = $user_bdd[$offset];
			$this->pswd = $pswd_bdd[$offset];
		}
	}
	
	/**
	 * Close connexion before destruct object.
	 */
	public function __destruct(){
		if($this->pdo) $this->db = null;
		else @oci_close($this->db);
		
		if($this->db != null){
			if($this->pdo) $this->db = null;
			else oci_close($this->db);
		}
	}
	
	public function init_manual($user, $pswd, $serv){
		$this->serv = $serv;
		$this->user = $user;
		$this->pswd = $pswd;
	}
	
	/** @return Last error message. */
	public function error()		{ return $this->error;  }
	/** @return User. */
	public function getUser()	{ return $this->user; }
	/** @return Server. */
	public function getServ()	{ return $this->serv; }
	
	
	/** 
	 * Connect to an Oracle database.
	 * @return true if connexion success
	 */
	public function connect(){
		// with PDO
		
		if($this->pdo){
			try {
				$this->db = new PDO('oci:dbname='.$this->serv.';charset=WE8ISO8859P1', $this->user, $this->pswd);
				return true;
			} 
			catch (PDOException $e) {
				$this->error = $e->getMessage();
				return false;
			}
		}
		// with oci8_
		else{
			$this->db = @oci_connect($this->user, $this->pswd, $this->serv);
			if($this->db) return true;
			$e = oci_error($this->qu);
			$this->error = $e['message'];
			return false;
		}
	}
	
	
					/**********************
						Q U E R Y   &   F E T C H
								**********************/
	/**
	 * Request to database the argument.
	 * You'll need fetch() to get lines.
	 * @return false if error happen.
	 */
	public function query($query){
		if($this->pdo){
			$this->qu = $this->db->query($query);
			if(!$this->qu){
				$e = $this->db->errorInfo();
				$this->error = $e[2];
			}
			return $this->qu;
		}
		else{
			$this->qu = oci_parse($this->db, $query);
			if(!$this->qu){
				$e = oci_error($this->qu);
				$this->error = $e['message'];
				return null;
			}
			$r = oci_execute($this->qu);		// @ don't display error now
			if(!$r){
				$e = oci_error($this->qu);
				$this->error = $e['message'];
			}
			return $r;
		}
	}
	
	/**
	 * Fetch results as pdo fetch or oci_fetch_array do.<br />
	 * WILL NOT PERMIT A ROWCOUNT, USE fetchAll(), then count()
	 * @return false if end, array else. 
	 */
	public function fetch($arg = null){
		if($this->pdo)
			return $this->qu->fetch($arg);
		else
			return oci_fetch_array($this->qu);
	}
	
	/**
	 * Fetch results as pdo fetch or oci_fetch_array do.<br />
	 * WARNING : PDO is prefered. fetchAll from PDO and oci_fetch_all from
	 * oci plugin give 2 differents results. So results comming from 
	 * oci_fetch_all is entierelly reformated, it will take more longer to generate !
	 * @return false if end, array else.
	 */
	public function fetchAll(){
		if($this->pdo){
			$res = $this->qu->fetchAll();
			return $res;
		}
		else{
			$rc = oci_fetch_all($this->qu, $res);
			$res2 = array();
			// --> format as pdo::fetchAll give
			$keys = array(); $i = 0;
			foreach($res as $key => $val) 	$keys[$i++] = $key;		// get keys
			for($i=0; $i<count($res[$keys[0]]); $i++){		// reformat
				$res2[$i] = array();
				for($u=0; $u<count($keys); $u++){
					$res2[$i][$keys[$u]] = $res[$keys[$u]][$i];
					$res2[$i][$u] = $res[$keys[$u]][$i];
				}
			}
			return $res2;
		}
	}
	
	/** Apply htmlentities to all contents **/
	public function htmlentitiesResults($rows){
		for($u=0; $u<count($rows); $u++)
			foreach($rows[$u] as $key => $val)		// /2 car entrées répétées par la sortie de PDO
				$rows[$u][$key] = htmlentities($val);
		return $rows;
	}
	
	
	
				/**********************
					D E S C R I B E   T A B L E  
								**********************/
	/**
	 * @return String description of the field.
	 * @arg Field Name
	 */
	public function getType($fieldName){
		if($this->pdo)	return "Use oci_";
		else			return oci_field_type($this->qu, $fieldName);
	}
	
	/**
	 * @return [Boolean] can be this field null ?
	 * @arg Field Name
	 */
	public function isNull($fieldName){
		if($this->pdo)	return false;
		else			return oci_field_is_null($this->qu, $fieldName);
	}
	
	/**
	 * @return [Int] Field size
	 * @arg Field Name
	 */
	public function getSize($fieldName){
		if($this->pdo)	return 0;
		else			return oci_field_size($this->qu, $fieldName);
	}
	
	/**
	 * @return Fields count in actual table.
	 */
	public function getNumFields(){
		if($this->pdo)	return 0;
		else			return oci_num_fields($this->qu);
	}
	
	
	
}



/**
 * @return offset user of $connexions variable 
 * from username (argument). -1 if not found.
 */
function getOffsetUser($user){
	global $connexions;
	for($i=0; $i<count($connexions); $i++)
		if($connexions[$i]->getUser() == $user) return $i;
	return -1;
}

?>
