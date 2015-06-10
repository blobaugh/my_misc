<?php
/**
 * Takes care of finding and autoloading class files
 *
 * Uses an index to keep track of class files. This index is stored in a JSON
 * encoded file
 *
 * @author Ben Lobaugh <ben@lobaugh.net>
 * @link http://sirgecko.com/projects/php-autoloader
 * @version 1.0
 * @package Autoloader
 * @category Autoloader
 **/

class Autoloader {
	
	/**
	 * Internal Path List
	 * @var Array
	 **/ 
	private $mPaths = array();
	
	/**
	 * Contains a listing of all the class files
	 * @var Array
	 **/
	private $mIndex = array();
	
	/**
	 * Rebuild index when class not found? 
	 * Useful for development purposes
	 * May drastically increase time an resources
	 * Only use when library is changing
	 * @var Boolean
	 **/
	private $mRebuildIndex;
	
	/**
	 * Sets up the object and defines some default paths to locate classes
	 *
	 * @param Mixed $Paths - Array | String
	 * @param Boolean $RebuildIndex - Rebuild index when class not found? Useful for dev
	 **/
	public function __construct($Path, $RebuildIndex = false) {  
		$this->mRebuildIndex = $RebuildIndex;
		if($RebuildIndex) $this->refreshIndex();
		$this->addPath($Path);
		$this->checkIndex();
		$this->loadIndex();
	}
	
	/**
	 * Loads the class index file into memory
	 **/
	private function loadIndex() {
		global $Reg;
		if(file_exists($Reg->get('autoloader_index'))) {
			// Load
			$filename = $Reg->get('autoloader_index');
			$handle =  fopen($filename, "r") or die("can't open autoloader index file");
			$contents = @fread($handle, filesize($filename));
			fclose($handle);
			$this->mIndex = (array)json_decode($contents);
		}
	}
	
	/** 
	 * Erases the current index and rebuilds it from the
	 * internal paths.
	 *
	 * Used by outside devs
	 **/
	public function refreshIndex() {
		$this->createIndex();		
	}
	
	/**
	 * Creates a new index file from the paths
	 **/
	private function createIndex() {
		global $Reg;
		foreach($this->mPaths AS $p) {
			$i = new RecursiveDirectoryIterator($p);
			foreach (new RecursiveIteratorIterator($i) as $f ) {
				
				if($f->isFile()) {
					$ext = explode('.', $f->getFileName());
					if($ext[1] == 'class' && $ext[2] == 'php') {
		    			// Load all files .class.php
						$this->mIndex[$ext[0]] = $f->getPathName();
					}
				}
			}
		}
	
		$myFile = $Reg->get('autoloader_index');
		$fh = fopen($myFile, 'w+') or die("can't open autoloader index file");
		fwrite($fh, json_encode($this->mIndex));
		fclose($fh);
		
	}
	
	/**
	 * Check for the existance of the index file, 
	 * if it does not exist it will be loaded
	 **/
	private function checkIndex() { 
		global $Reg;
		// If !exist file create it
		if(!file_exists($Reg->get('autoloader_index'))) { 
			$this->createIndex();
		}
	}
	
	/**
	 * Adds a new path or paths to the class path list
	 *
	 * @param Mixed $Path - Array | String
	 **/
	private function addPath($Path) {
		if(is_array($Path)) {
			foreach($Path AS $p) {
				$this->mPaths[] = $p;
			}
		} else {
			$this->mPaths[] = $Path;
		}
		// Create the index for this new path if it does not exist
	}
	
	/**
	 * Includes the requested file if found in the index
	 *
	 **/
	public function load($Params, $Time = 0) {
		$ret = false;
		// Look in paths for the right class
		if(isset($this->mIndex[$Params])) {
			require_once($this->mIndex[$Params]);
			$ret = true;
		} else if($this->mRebuildIndex && $Time == 0){ 
			$this->refreshIndex();
			$ret = $this->load($Params, 1);
		}
		return $ret;
	}
	
} // end class