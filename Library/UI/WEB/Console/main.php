<?php
?>
<table>
<tr>
	<td>
	</td>
</tr>
<tr>
	<td>
<?php 
if ( empty( $_GET[ 'module' ] ) ) {
?>
<table border="1">
<?php 
/* Load Menu Files */
if ( $_SESSION[ 'profile_type' ] == 'admin' )
	$module_type = 'Admin';
else 
	$module_type = 'Client';

$module_path = OZ_PATH_WEBUI . 'Modules/';
$old_path = getcwd();
/* Change Directory to Module Path */
chdir( $module_path );

$files = $zppFilesystem->dirRead( $module_path );
if ( !empty( $files ) ) {
	foreach( $files as $file ) {
		if ( is_file( $file ) ) {
			if ( preg_match( "/^.*\.Menu\.$module_type\.Mod\.php$/" , $file ) ) {
				require $file;
			}
		}
		require $path . $file;
	}
}
?>
</table>
<?php
}
		/* Construct console 	*/
		$console = "<table border=\"1\">\r\n<tr>\r\n";
		if ( !empty( $menu ) ) {
			$i = 1; 
			foreach( $menu as $title => $discription ) {
				if ( $i <= 5 ) {
					$console .= "<td>$title<br>$discription</td>";
					$i++;
				} else {
					$console .= "\r\n</tr>\r\n<tr>\r\n";
					$i = 1;
				}
			}
		} else {
			$console .= "<td>No Modules</td>\r\n</tr>\r\n";
		}
		$console .= "</table>\r\n";
		
?>
	</td>
</tr>
</table>