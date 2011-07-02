<?php
$installCfg[ 'base_dir' ] = 'userman-1.0/';

$installCfg[ 'mod' ][ 'Name' ] = 'User Management';
$installCfg[ 'mod' ][ 'Disc' ] = 'Usermanage Management';
$installCfg[ 'mod' ][ 'File' ] = 'userman-1.0/mod.php';


//$installCfg[ 'mod' ][ 'Email.Type']

$installCfg[ 'email' ][] = array(
	'Name' => 'new_admin_pass',
	'UseEmailFromType' => 'Admin',
	'Subject' => 'Your new OpenZ Administrative Credentials',
	'Heading' => '',
	'Footer' => '',
	'Flags' => 'u'
);