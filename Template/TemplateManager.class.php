<?php
/*
 * Loads and fills in template files based upon passed in values
 *
 * Provides limited caching ability
 *
 * Author: Ben Lobaugh (ben@lobaugh.net)
 */

require_once('simple_html_dom.php');

class TemplateManager {

	/**
	 * Holds the simple_html_dom
	 *
	 * @var simple_html_dom
	 **/
	var $mDom;
	
	/**
	 * Holds special template vars that are not
	 * able to be accessed through the dom
	 *
	 * @var Associative Array
	 **/
	var $mSpecialTags;

	public function __construct() {
		$this->mDom = new simple_html_dom();
		$this->mSpecialTags = array();
	}
	
	public function __toString() { 
		return $this->display(false);
		//$this->replaceSpecialTags();
		//echo $this->mDom->innertext;
	}
	
	public function loadFile($File) {
		$this->mDom->load_file($File);
	}
	
	public function loadString($String) {
		$this->mDom->load($String);
	}
	
	public function display($Echo = true, $EraseSpecialTags = true) { 
		// Replace all the special tags with their values
		$this->replaceSpecialTags(); 
		// Erase non-used special tags if desired. This is the default
		if($EraseSpecialTags) {
			$this->EraseSpecialTags();
		}
	
		if($Echo) {
			echo $this->mDom->innertext;
			return;
		}
		return $this->mDom->innertext;
	}
	
	
	public function setTag($Tag, $Value, $Type = 's') {
		
		switch($Type) {
			case 's': 
				// Set the tag (relaces existing content)
				$this->mDom->find($Tag, 0)->innertext = $Value;
				break;
			case 'p':
				// Prepend - adds to the beginning of content
				$this->mDom->find($Tag, 0)->innertext = $Value . $this->mDom->find($Tag, 0)->innertext;
				break;
			case 'a':
				// Append - adds to the end of content
				$this->mDom->find($Tag, 0)->innertext .= $Value;
				break;
		}
		
	}
	
	public function setById($Id, $Value, $Type = 's') {
		
		switch($Type) {
			case 's': 
				// Set the tag (relaces existing content)
				$this->mDom->find("#$Id", 0)->innertext = $Value;
				break;
			case 'p':
				// Prepend - adds to the beginning of content
				$this->mDom->find("#$Id", 0)->innertext = $Value . $this->mDom->find("#$Id", 0)->innertext;
				break;
			case 'a':
				// Append - adds to the end of content
				$this->mDom->find("#$Id", 0)->innertext .= $Value;
				break;
		}
		
	}

	public function setSpecialTag($Tag, $Value) { 
		// Allow dev to pass in an array of tags to ease setting multiple tags 
		if(is_array($Value)) {
			foreach($Value AS $k => $v) {
				$this->setSpecialTag($k, $v);
			}
		} else {
			// This must be a usable key/value pair
			$this->mSpecialTags["{{".$Tag."}}"] = $Value;
		}
	}

	private function replaceSpecialTags() {
		foreach($this->mSpecialTags AS $t => $v) { //echo "replacing $t with $v<br>";
			$this->mDom->innertext = str_replace($t, $v, $this->mDom->innertext);
		}
	}
	
	private function EraseSpecialTags() {
		
	}
} // end class TemplateManager