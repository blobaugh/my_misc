/*
 * This will eventually become a full class for managing ini files from
 * PHP. Currently the PHP language only supports reading ini files, this
 * class will allow you to read and write ini files in a transperant manner,
 * completely abstracted away through the use of an object
 */


class Ini {

	// Reads in an ini file and places the contents in 
	// a class level assoc array
	function open($file){}

	// Sets a value in the ini file
	// Should this write out to the file immediately?
	function set($section, $key, $value){}

	// Returns the value of the specified section or key
	function get($section, $key){}

	// Writes the values to the ini file
	function save($filename){}


	// Prints out the contents of the object
	function __toString(){}

} // end class
