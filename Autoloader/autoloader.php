<?php
/**
 * Takes care of finding and autoloading class files
 *
 * Uses an index to keep track of class files. This index is stored in a JSON
 * encoded file
 *
 * @author Ben Lobaugh <ben@lobaugh.net>
 * @link http://sirgecko.com/projects/php-autoloader
 * @package Autoloader
 * @category Autoloader
 **/

// Pull the path apart on ',' to get an array
$sg_dir = explode(',', $Reg->get('lib_path'));



/*
 * YOU SHOULD NOT NEED TO EDIT UNDER THIS LINE
 */


require_once('Autoloader.class.php');
$Al = new AutoLoader($sg_dir, false);
spl_autoload_register('SG_Autoloader');

/**
 * Calls on the autoloader class to do the heavy lifting
 *
 * @param String $Params
 **/
function SG_Autoloader($Params) {
	global $Al;
	return $Al->load($Params);
}