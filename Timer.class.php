<?php
class Timer implements ArrayAccess {

	/**
	 * Holds timing marks
	 *
	 * @var Array
	 **/
	private $mMarks;
	
	/**
	 * Default constructor
	 **/
	 public function __construct() {
		$this->mMarks = array();
		$this->mark('timer_start');
	 }
	 
	 /**
	  * Creates a new timer mark
	  *
	  * @param String $name
	  **/
	  public function mark($name) {
		$this->mMarks[$name] = microtime(true);
	  }
	  
	  /**
	   * Finds the elapsed time between two specified marks
	   *
	   * @param String $start
	   * @param String $stop
	   * @param Integer $decimals - Optional - Default: 4
	   * @return Float
	   **/
	   public function elapsed($start, $stop, $decimals = 4) {
		return number_format($this->mMarks[$stop] - $this->mMarks[$start], $decimals);
	   }

	/**
	 * Dumps all of the items in the marks array
	 *
	 * @return String
	 **/
	 public function __toString() {
		$this->mark('toString');
		$str = '';
		$prev = 'timer_start';
		foreach($this->mMarks AS $m => $v) {
			$str .= "\n$m, " . $this->elapsed('timer_start', $m) . ", " . $this->elapsed($prev, $m);
			$prev = $m;
		}
		//$str .= "\n\nTotal Time: " . $this->elapsed('timer_start', 'toString') . " seconds";
		return $str;
	}
	
	/**
	 * Allows access to object like an array
	 * @see http://uk2.php.net/manual/en/class.arrayaccess.php
	 **/
	 public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->mMarks[] = $value;
        } else {
            $this->mMarks[$offset] = $value;
        }
    }
    public function offsetExists($offset) {
        return isset($this->mMarks[$offset]);
    }
    public function offsetUnset($offset) {
        unset($this->mMarks[$offset]);
    }
    public function offsetGet($offset) {
        return isset($this->mMarks[$offset]) ? $this->mMarks[$offset] : null;
    }
} // end class