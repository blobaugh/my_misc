<?php
/**
 * This class acts like a registry to hold various config values
 **/

/**
 * Singleton. Use Registry->GetRegistry
 **/
class Registry {

	/**
	* Holds whether a registry exists already or not.
	*
	* @var Boolean
	*/
	private static $Exists = FALSE;

	/**
	 * Holds the info
	 *
	 * @var Array
	 **/
	private $Registry;

	/**
	 * Default constructor
	 *
	 * Sets up some normal defaults for the cms
	 **/
	private function __construct() {}

	public function getRegistry() {

		if(!self::$Exists) {
			self::$Exists = new Registry();
		}

		return self::$Exists;
	}

	public function set($Var, $Value) {
		$this->Registry[$Var] = $Value;
	}
	
	public function setByArray($Array) {
		foreach($Array AS $k => $v) {
			$this->set($k, $v);
		}
	}

	public function get($Var) {
		return $this->Registry[$Var];
	}

	public function getHttpLink($Link) {
		return $this->Registry['http_link'] . $this->Registry[$Link];
	}

	public function remove($Var) {
	        unset($this->Registry[$Var]);
	}

	public function __toString() {
	        ksort($this->Registry);
	        new dBug(($this->Registry));
			return 1; // Required by php
	}
} // end class
