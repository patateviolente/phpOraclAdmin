<?php

/***************************************************************************
 *   phpOraclAdmin - Oracle manager, phpMyAdmin like
 *   Page incl/Class/Lang.php - Language manager
 * 									-> include specific lang file
 *   
 *   Copyright            : (C) 2010 - 
 *   Creator              : Patrick Portal - Brice Hyaumet
 *   Site-web             : http://xn--thta-hpa.net/poa
 *   Em@il                : See website
 *   See GNU conditions on index.php head
 ***************************************************************************/

/* Constructor include the specified language file.php
 * Or DIE if not any.
 */
class Lang{
	private $path, $lang;
	private $txt;
	
	/**
	 * Construct a new Lang Object which contain the text (use g(offset))
	 * Constructor will die if file does not exists.
	 */
	public function __construct($file, $lang){
		$this->lang = $lang;
		
		$l = $this->canInclLang($file);
		$this->path = preg_replace('#(\.\./)*#', '', $this->path);
		if($l){
			// inclusion -> $txt
			include($l);
			if(!isset($L) || !is_array($L)) 
				die('ERROR : "'.$this->path.'" is not a correct language file.');
			$this->txt = $L;
		}
		else{
			// no inclusion possible -> DIE
			die('ERROR : The language file "'.$this->path.'" does not exists.<br />
				Please update the "'.$lang.'" language folder or choose another.');
		}
	}
	
	/** 
	 * Verify if page can be included checking 3 anterior levels 
	 * (./ ../ . . .) before aborting.
	 * @return the path if the file exists OR NULL else.
	 * @param Page name (without .php extension)
	 */
	private function canInclLang($page){
		$this->path = LANG_FOLDER.$this->lang.'/'.$page.'.php';
		
		// direct incl
		if(file_exists($this->path))
			return $this->path;
		else{	// try another levels
			for($i = 0; $i< 3; $i++){
				$this->path = '../'.$this->path;
				if(file_exists($this->path)) return $this->path;
			}
		}
		return null;
	}
	
	/**
	 * Return the text at offset
	 */
	public function g($pos){
		return htmlentities($this->txt[''.$pos]);
	}
}

?>
