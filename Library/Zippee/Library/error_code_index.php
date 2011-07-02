<?php
// @(#) $Id: error_code_index.php 0.0.1 07/12/2010 evil-genius $
// +-----------------------------------------------------------------------+
// | Copyright (C) 2010, http://yoursite                                   |
// +-----------------------------------------------------------------------+
// | This file and/or program may not be distributed and/or modfied        |
// | without the consent of the author.                                    |
// | This file and/or program is provided WITHOUT ANY WARRANTY; without    |
// | the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR   |
// | PURPOSE.                                                              |
// +-----------------------------------------------------------------------+
// | Author: Lutchy Horace                                                 |
// +-----------------------------------------------------------------------+
//

	$cfg[ 'ERR_ID' ] = array('ERR0000' => array('Unknow error','SYS'), 
                     // - Other; Count start 100; 
                     'ERRx0101' => array('Invalid boolen value'.'SYS'),
										 'ERRx0102' => array('No input specfied', 'SYS'),
										 'ERRx0103' => array('String out of range', 'SYS'),
										 'ERRx0104' => array('Invalid error code', 'SYS'),
										 'ERRx0105' => array('Config data missing', 'SYS'),
										 'ERRx0106' => array('function not loaded', 'SYS'),
										 'ERRx0107' => array('function configuration not found', 'SYS'),
										 'ERRx0108' => array('Unable to parse function configuration', 'SYS'),
										 'ERRx0109' => array('Invalid Input', 'SYS'),
										 'ERRx0110' => array('Computer Banned', 'SYS'),
										 'ERRx0111' => array('Unauthorize Access', 'SEC', true),
										 'ERRx0112' => array('Unable to set constant', 'SYS', true),
										 'ERRx0113' => array('Session Auth Disabled', 'SEC', true ),
										 'ERRx0114' => array('Not Authorize/Permission Denied', 'SEC' ),
										 'ERRx0115' => array('Unable to open registry', 'SYS' ),
										 'ERRx0116' => array('General Error', 'SYS' ),
										 'ERRx0117' => array('E-Mail Error', 'SYS' ),
										 'ERRx0118' => array('General Notice', 'SYS' ),
										 'ERRx0119' => array('Username is reserved' , 'SYS' ),
										 'ERRx0120' => array('Connection Error' , 'SYS' ),
                     // Count start 100; file manipulation errors 
                     'ERR0101' => array('File not found','SYS'), 
										 'ERR0103' => array('Unable to open file for reading','SYS'), 
										 'ERR0104' => array('Unable to read file','SYS'),
										 'ERR0105' => array('Unable to open file for writing','SYS'),
										 'ERR0106' => array('Unable to write to file','SYS'),
										 'ERR0107' => array('Directory not writable','SYS'),
										 'ERR0108' => array('Directory not found','SYS'),
										 'ERR0109' => array('Unable to create directory','SYS'),
										 'ERR0110' => array('Unable to open library(write_contents)','SYS'),
										 // Count start 200, log errors
										 'ERR0201' => array('Log directory not found, possibly the directory is not writable', 'SYS'),
										 'ERR0202' => array('Unable to write log error', 'SYS'),
										 'ERR0203' => array('FNC%write_contents% is not set', 'SYS', TRUE),
										 'ERR0204' => array('Error log management, not loaded', 'SYS'),
										 'ERR0205' => array('Invalid input type', 'SYS'),
										 'ERR0206' => array('Filesystem support not available', 'SYS', TRUE),
										 // Count start 300, http errors
										 'ERR0301' => array('DNS function(s) unavailable', 'SYS'),
										 'ERR0302' => array('CLASS%OBJ%_parse_url Empty url string', 'SYS', TRUE),
										 'ERR0303' => array('Unable to redirect', 'SYS'),
										 'ERR0304' => array('Invalid url', 'SYS'),
										 'ERR0305' => array('Invalid DNS ip(s)', 'SYS'),
										 'ERR0306' => array('Invalid ip' , 'SYS' ),
	                                     'ERR0307' => array('Invalid protocol' , 'SYS' ),
										 'ERR0308' => array('Invalid host' , 'SYS' ),
										 'ERR0309' => array('Invalid port' , 'SYS' ),
										 'ERR0310' => array('Unable to establish an encrypted channel' , 'SYS' ),
										 // Count start 400, db errors
										 'ERR0401' => array('DB Error', 'SYS', TRUE),
										 'ERR0402' => array('Empty Query', 'SYS', TRUE),
	                                     'ERR0403' => array('Unable to open connection', 'SYS', TRUE),
										 'ERR0404' => array('Invalid database object', 'SYS', TRUE),
										 // Count start 500, session errors
										 'ERR0501' => array('Session Error', 'SYS', TRUE),
										 // Count start 600, login errors
										 'ERR0601' => array('Invalid Login', 'SEC'),
										 'ERR0602' => array('Login Expired', 'SEC'),
										 'ERR0603' => array('Secure Area', 'SEC'),
										 'ERR0604' => array('Account Inactive', 'SYS'),
										 'ERR0605' => array('Account Temporarily Disabled', 'SYS'),
										 'ERR0606' => array('Unable to login', 'SEC'),
										 // Count start 700, account errors
										 'ERR0701' => array('Invalid Account', 'SYS'),
										 'ERR0702' => array('Not Authorize to view this account', 'SYS'),
										 'ERR0703' => array('Account already exist', 'SYS'),
	                                     'ERR0704' => array('Account already exist with this E-Mail', 'SYS'),
	                                     //'ERR0705' => array('Username is reserved', 'SYS'),
										 // Count start 800, object errors
										 'ERR0801' => array('Object Error', 'SYS', true),
	                                     'ERR0802' => array('Class missing', 'SYS', true),
										 // Count start 800, object errors
										 'ERR0901' => array('PHP Error' , 'SYS' , true),
										 // Count start 1000, Encryption errors
										 'ERR1001' => array('Missing Encryption Algo' , 'SYS' , true)
										 
			);	