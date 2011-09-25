<?php

/**
 * @GlobalSettings
 */
$ozConfig[ 'Debugging' ][ 'Verbose' ] = 0;
$ozConfig[ 'Url' ][ 'Host' ] = 'nemesis.bombshellz.net';

/* Image Extensions and MIME */
$OZCFG[ 'Valid_Image_FileExtensions' ][ 'jpg' ]  = 'image/jpeg';
$OZCFG[ 'Valid_Image_FileExtensions' ][ 'jpeg' ] = 'image/jpeg';
$OZCFG[ 'Valid_Image_FileExtensions' ][ 'png' ]  = 'image/png';

$OZCFG[ 'Hash_Algo' ] = 'md5'; /* Possible values are: md5 , sha256 or sha512. Defaults to md5 if the hash algo is missing. See: http://www.php.net/manual/en/function.hash-algos.php */

$ozConfig[ 'Database' ][ 'Location' ] = 'bombshellz.net';
$ozConfig[ 'Database' ][ 'Name' ]     = 'bombshel_oz';
$ozConfig[ 'Database' ][ 'Username' ] = 'bombshel_ozuser';
$ozConfig[ 'Database' ][ 'Password' ] = '([]@(l8q8Acr';

/* Do not modify any of settings below */
$ozConfig[ 'Path' ][ 'ZippeeConfig' ] = 'Config/Zippee/OZ.php'; /* Make the aproproitate changes to Z_local.php for local testing */

/***
 * Check Custom Vouch when adding users
 */
$ozConfig[ 'Activate' ][ 'CheckVouch' ] = true;


/***
 * Package id 
 * If Package default parameter is missing, openZ will default to package 1
 * 
***/
$ozConfig[ 'Package' ][ 'Default' ] = '1';
$ozConfig[ 'Package' ][1][ 'Name' ] = 'Standard';
$ozConfig[ 'Package' ][1][ 'Quota' ] = '50000';
$ozConfig[ 'Package' ][1][ 'SystemGroup' ] = 'clients';
$ozConfig[ 'Package' ][1][ 'SystemGroups' ] = 'standard'; /* Separated By a Comma */
$ozConfig[ 'Package' ][1][ 'SSHAccess' ] = true;
$ozConfig[ 'Package' ][1][ 'Email' ] = true;
$ozConfig[ 'Package' ][1][ 'Storage' ] = '50MB';
$ozConfig[ 'Package' ][1][ 'BackgroundProcesses' ] = '2';
$ozConfig[ 'Package' ][1][ 'BindablePorts' ] = '2';
$ozConfig[ 'Package' ][1][ 'MySQL' ] = '-';
$ozConfig[ 'Package' ][1][ 'GCCAccess' ] = true;
$ozConfig[ 'Package' ][1][ 'PublicBNC' ] = true;
$ozConfig[ 'Package' ][1][ 'Python/Perl' ] = false;
$ozConfig[ 'Package' ][1][ 'Donation' ] = '$0';

$ozConfig[ 'Package' ][2][ 'Name' ] = 'Contrib';
$ozConfig[ 'Package' ][2][ 'Quota' ] = '100000';
$ozConfig[ 'Package' ][2][ 'SystemGroup' ] = 'users';
$ozConfig[ 'Package' ][2][ 'SystemGroups' ] = 'contrib';
$ozConfig[ 'Package' ][2][ 'SSHAccess' ] = true;
$ozConfig[ 'Package' ][2][ 'Email' ] = true;
$ozConfig[ 'Package' ][2][ 'Storage' ] = '100MB';
$ozConfig[ 'Package' ][2][ 'BackgroundProcesses' ] = '3';
$ozConfig[ 'Package' ][2][ 'BindablePorts' ] = '2';
$ozConfig[ 'Package' ][2][ 'MySQL' ] = '-';
$ozConfig[ 'Package' ][2][ 'GCCAccess' ] = true;
$ozConfig[ 'Package' ][2][ 'PublicBNC' ] = true;
$ozConfig[ 'Package' ][2][ 'Python/Perl' ] = true;
$ozConfig[ 'Package' ][2][ 'Donation' ] = '$5';
$ozConfig[ 'Package' ][2][ 'Donation' ] = <<<EOF
$5 <br>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----MIIHVwYJKoZIhvcNAQcEoIIHSDCCB0QCAQExggEwMIIBLAIBADCBlDCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb20CAQAwDQYJKoZIhvcNAQEBBQAEgYBwgExHTMbceTQe0WgMr3NRPsk7vrT8FFoUhrxYTBBU7AgTnbjBHCXWS7I0E1QrqGFi54Q/cwvKesWBIlnCT6ctOcyu3T5Q/2biITc31tqcJlupsXKO6ni25RXqh/L3Dtk70dVhnyFzP5xfNxQznpX1ndd82xWp7FBax1/AAvKK/TELMAkGBSsOAwIaBQAwgdQGCSqGSIb3DQEHATAUBggqhkiG9w0DBwQI93bBf4N/vxiAgbBWL/+Uu4yt7YmYiXbiuxhhMEcfvWNhXhDm6MS/BbTBUjZaaBPM0fzra4kBKINstyiRWagJ6MzDZhL6hIla8IgM4vbDsxAZhKeiPfCKwFhMaNWY5/8rUEr4EgWJpWXxiaz3y+9YiZC/6Q5Hufxk/OCeZBZeH96jhsNC1IEEEL4xs7I/qt0CbZcRlTTJYArSMKcy5xh9BSSKoTZD5Agfo2EkIlzziwd6BWrmVSd+5D7jNKCCA4cwggODMIIC7KADAgECAgEAMA0GCSqGSIb3DQEBBQUAMIGOMQswCQYDVQQGEwJVUzELMAkGA1UECBMCQ0ExFjAUBgNVBAcTDU1vdW50YWluIFZpZXcxFDASBgNVBAoTC1BheVBhbCBJbmMuMRMwEQYDVQQLFApsaXZlX2NlcnRzMREwDwYDVQQDFAhsaXZlX2FwaTEcMBoGCSqGSIb3DQEJARYNcmVAcGF5cGFsLmNvbTAeFw0wNDAyMTMxMDEzMTVaFw0zNTAyMTMxMDEzMTVaMIGOMQswCQYDVQQGEwJVUzELMAkGA1UECBMCQ0ExFjAUBgNVBAcTDU1vdW50YWluIFZpZXcxFDASBgNVBAoTC1BheVBhbCBJbmMuMRMwEQYDVQQLFApsaXZlX2NlcnRzMREwDwYDVQQDFAhsaXZlX2FwaTEcMBoGCSqGSIb3DQEJARYNcmVAcGF5cGFsLmNvbTCBnzANBgkqhkiG9w0BAQEFAAOBjQAwgYkCgYEAwUdO3fxEzEtcnI7ZKZL412XvZPugoni7i7D7prCe0AtaHTc97CYgm7NsAtJyxNLixmhLV8pyIEaiHXWAh8fPKW+R017+EmXrr9EaquPmsVvTywAAE1PMNOKqo2kl4Gxiz9zZqIajOm1fZGWcGS0f5JQ2kBqNbvbg2/Za+GJ/qwUCAwEAAaOB7jCB6zAdBgNVHQ4EFgQUlp98u8ZvF71ZP1LXChvsENZklGswgbsGA1UdIwSBszCBsIAUlp98u8ZvF71ZP1LXChvsENZklGuhgZSkgZEwgY4xCzAJBgNVBAYTAlVTMQswCQYDVQQIEwJDQTEWMBQGA1UEBxMNTW91bnRhaW4gVmlldzEUMBIGA1UEChMLUGF5UGFsIEluYy4xEzARBgNVBAsUCmxpdmVfY2VydHMxETAPBgNVBAMUCGxpdmVfYXBpMRwwGgYJKoZIhvcNAQkBFg1yZUBwYXlwYWwuY29tggEAMAwGA1UdEwQFMAMBAf8wDQYJKoZIhvcNAQEFBQADgYEAgV86VpqAWuXvX6Oro4qJ1tYVIT5DgWpE692Ag422H7yRIr/9j/iKG4Thia/Oflx4TdL+IFJBAyPK9v6zZNZtBgPBynXb048hsP16l2vi0k5Q2JKiPDsEfBhGI+HnxLXEaUWAcVfCsQFvd2A1sxRr67ip5y2wwBelUecP3AjJ+YcxggGaMIIBlgIBATCBlDCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb20CAQAwCQYFKw4DAhoFAKBdMBgGCSqGSIb3DQEJAzELBgkqhkiG9w0BBwEwHAYJKoZIhvcNAQkFMQ8XDTExMDgyNjE3MDc1NFowIwYJKoZIhvcNAQkEMRYEFG5nBrZY57fPQkkWN/cpew+/9piUMA0GCSqGSIb3DQEBAQUABIGAL3YSiBDZjJRD0af81agvQrvmhNRPPMNxVhabqYVjqlXodJU3IyUz8p1Jp+y58E8xe9dCp664pul2cbBFr2KdyAg6qU0PhQiFClraq/L6hTeUOKhpXTFvZE2zzT0I4SURL6Nc8n1o2QW6cSlqD9pArzF2Oca0KeauxwPnydDGMT0=-----END PKCS7-----
">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
EOF;

$ozConfig[ 'Package' ][3][ 'Name' ] = 'Contrib+';
$ozConfig[ 'Package' ][3][ 'Quota' ] = '150000';
$ozConfig[ 'Package' ][3][ 'SystemGroup' ] = 'users';
$ozConfig[ 'Package' ][3][ 'SystemGroups' ] = 'contribplus';
$ozConfig[ 'Package' ][3][ 'SSHAccess' ] = true;
$ozConfig[ 'Package' ][3][ 'Email' ] = true;
$ozConfig[ 'Package' ][3][ 'Storage' ] = '150MB';
$ozConfig[ 'Package' ][3][ 'BackgroundProcesses' ] = '4';
$ozConfig[ 'Package' ][3][ 'BindablePorts' ] = '2';
$ozConfig[ 'Package' ][3][ 'MySQL' ] = '-';
$ozConfig[ 'Package' ][3][ 'GCCAccess' ] = true;
$ozConfig[ 'Package' ][3][ 'PublicBNC' ] = true;
$ozConfig[ 'Package' ][3][ 'Python/Perl' ] = true;
$ozConfig[ 'Package' ][3][ 'Donation' ] = '$8';

/***
 * Password
 */
$ozPassword[ 'Client' ][ 'PasswordAging' ] = true;
$ozPassword[ 'Client' ][ 'Maximum.Days' ] = '60';
$ozPassword[ 'Client' ][ 'Maximum.Inactive' ] = '3';

$ozPassword[ 'Admin' ][ 'PasswordAging' ] = true;
$ozPassword[ 'Admin' ][ 'Maximum.Days' ] = '30';
$ozPassword[ 'Admin' ][ 'Maximum.Inactive' ] = '0';


/***
 * Email
***/

/*** Default E-Mail used as an recipent when sending messages ***/
$ozConfig[ 'Email.From' ][ 'Default' ] = array( 
	'Address' => 'noreply@bombshellz.net',
	'Name' => 'Bombshellz Network' 
); 

/*** Administrator E-Mail ***/
$ozConfig[ 'Email' ][ 'Admin' ] = array( 
	'Address' => 'admin@bombshellz.net',
	'Name' => '' 
); 


$ozConfig[ 'Path' ][ 'EmailLogo' ] = 'Library/Email/BombshellzLogo.txt';
$ozConfig[ 'Path' ][ 'EmailSig' ] = 'Library/Email/BombshellzSig.txt';

/***
 * Lock Reasons
***/
$ozConfig[ 'LockReasons' ][1] = 'Too many background processes';
$ozConfig[ 'LockReasons' ][2] = 'Using more then allotted ports';
$ozConfig[ 'LockReasons' ][3] = 'Prohibited Software';
$ozConfig[ 'LockReasons' ][4] = 'Multiple Accounts';
$ozConfig[ 'LockReasons' ][5] = 'Policy Violation(s)';
$ozConfig[ 'LockReasons' ][6] = 'Account Inactive';
$ozConfig[ 'LockReasons' ][7] = 'Trial Expired';
//$ozConfig[ 'LockReasons' ][2] = '';

/***
 * Profile
 * 1 = Pending
 * 2 = Active
 * 
 * Default ClientStatus
 *   Pending
 * Default AdminStatus
 *   Active
 *   
***/
//$ozConfig[ 'Profile' ][ 'Default.Add.ClientStatus' ] = 1;
//$ozConfig[ 'Profile' ][ 'Default.Add.AdminStatus' ]  = 2;
$ozConfig[ 'Profile' ][ 'Add.ReserveNicks' ] = array( 'bombshell' , 'bombshellz' );

/***
 * Quota
***/
$ozConfig[ 'Path' ][ 'Quota' ] = '/home';

/***
 * Commands 
 * 
 * <%oz_uid%> Username
 * 
***/
$ozCommands[ 'Post.Activate' ][] = 'chgrp www /home/%oz_uid%/';
$ozCommands[ 'Post.Activate' ][] = 'chmod 0710 /home/%oz_uid%/';

/* Absolute path to Zippee FrameWork 
  Note: If left empty, openZ will default to $BASEPATH . 'Library/Zippee/'
*/
$ZippeeFramework = '';

/***
 * Notes 
***/
$ozConfig[ 'Note' ][ 'Total.Displayed' ] = '5'; /* Total Notes to display at one time */

/***
 * Modfications
 */
$ozConfig[ 'AccountModifications' ][ 'TotalDisplayed' ] = '20';

/***
 * Admin Groups
 */
$ozAdminGroup[1][ 'Name' ] = 'Lead';
$ozAdminGroup[1][ 'SystemGroups' ] = 'lead';
$ozAdminGroup[1][ 'BackgroundProcesses' ] = '6';

$ozAdminGroup[2][ 'Name' ] = 'Technician';
$ozAdminGroup[2][ 'SystemGroups' ] = 'technician';
$ozAdminGroup[2][ 'BackgroundProcesses' ] = '6';

$ozAdminGroup[3][ 'Name' ] = 'Support Technician';
$ozAdminGroup[3][ 'SystemGroups' ] = 'support-technician';
$ozAdminGroup[3][ 'BackgroundProcesses' ] = '6';

/***
 * Service configuration
 */


$ozConfig[ 'Service' ][ 'MaxProcs' ] = 2; /* Total Services to launch at one time */

/***
 * Administrator Permissions
 */
$ozPermission[2][ 'Activate' ] = true;
$ozPermission[2][ 'Lock' ]     = true;

/*** Do Not Modify Anything Below This Line ***/
if ( file_exists( OZ_PATH_BASE . 'LocalSettings.php') ) {
	require OZ_PATH_BASE . 'LocalSettings.php';
}
