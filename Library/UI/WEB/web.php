<?php
require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '../../../OpenZ.php';

/***
 * Load all Modules
 */
$modules = Module::getAll();
foreach ( $modules as $module ) {
	$mod = new Module($module);
	require $mod->getFile();
}