<?php
if ( !empty( $_GET[ 'AuthError' ] ) ) {
	if ( $_GET[ 'AuthError' ] == 'invalid' ) 
		$str = 'Username or Password invalid';
	elseif ( $_GET[ 'AuthError' ] == 'disabled' ) 
		$str = 'Accound disabled';
	else 
		$str = 'Unknown Error';
	$str .= '<br>Login attempt: /4';
	print $this->errorBox( $str );
}
	
/* Custom check */
if ( file_exists( OZ_PATH_BASE . '.shell' ) ) {
	$_GET[ 'AuthType' ] = 'admin';
}
/* Set title */
$title = ( @$_GET[ 'AuthType' ] == 'admin' ) ? 'Administrator' : 'Client';
	
// AuthAuthenticate
$ozHtml->formHead();
?>
 <fieldset style="width: 205px">
   <legend><?php print $title?> Login</legend>
   <input type="hidden" name="AuthAuthenticate" value="true">
   <input type="hidden" name="AuthType" value="<?php print @$_GET[ 'AuthType' ]?>">
    <table border="0" style="width: 200px">
	<tr>
	 <td>
       Username
     </td>
    </tr>
    <tr>
     <td>
       <input type="text" size="30" name="oz_uid" >
     </td>
    </tr>
    <tr>
     <td>
      Password
     </td>	  
    </tr>
    <tr>
     <td>
       <input type="password" size="30" name="oz_pwd" >
     </td>
    </tr>
    <tr>
     <td style="text-align: right;">
       <input type="submit" value="Login" class="button">
     </td>
    </tr>
   </table>
 </fieldset>	
 <?php
 $ozHtml->formFooter(); 

