<?php

/*** 

	Copyright (c) http://wiki.bombshellz.net/
	Author: Lutchy Horace
	Version: 0.0.1
	
	Redistribution and use in source or binary forms are permitted provided that the following conditions are met:
		
		* Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
		* Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
		* Neither the name of the BombShellz.net nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.
		* Modification to this file or program is not permitted without the consent of the author.
		* THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
	
***/

class Html
{
	/**
	 * 
	 * Prints the header of the page
	 * @access Public
	 * @param (string) $title
	 * @param (string) $docType Available doctypes are strict, frameset, or default transitional 
	 * @param (array)  $cssLinks Array of CSS href links pointing .css file
	 * @param (array)  $javascriptLinks Array of JS href links pointing .css file
	 */
	public function head( $title = null , $docType = null , $cssLinks = array() , $javascriptLinks = array() )
	{
		global $zppFilesystem, $oz;
		
		if ( $docType == 'strict' )
			$doctype = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">';
		elseif ( $docType == 'frameset')
			$doctype = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">';
		else 
			$doctype = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">';
		
		if ( empty( $title ) )
			$title = OZ_NAME . ' ' . OZ_VER;
			
		$head = $doctype . "\r\n";
		$head .= "<HTML>\r\n";
		$head .= "<HEAD>\r\n";
		$head .= " <TITLE>$title</TITLE>\r\n";
		if ( !empty( $cssLinks ) ) {
			foreach ( $cssLinks as $url ) {
				$head .= "<LINK rel=\"stylesheet\" type=\"text/css\" href=\"$url\" />\r\n";
			}
		}
		/*** Load Css External Files ***/
		$css_path = OZ_PATH_LIBRARY . path_rewrite( 'Media/Css/Auto/' );
		$dir_contents = $zppFilesystem->dirRead( $css_path );
		if ( !empty( $dir_contents ) ) {
			foreach ( $dir_contents as $file ) {
				$path = $css_path . $file;
				if ( is_file( $path ) ) {
					$file_contents = $oz->fileRead( $path );
					$head .= " " . '<STYLE type="text/css">';
					$head .= "\r\n$file_contents\r\n";
					$head .= ' </STYLE>' . "\r\n";
				}
			}
		}
		if ( !empty( $javascriptLinks ) ) {
			foreach ( $javascriptLinks as $url ) {
				$head .= "<SCRIPT type=\"text/javascript\" src=\"$url\"></SCRIPT>\r\n";
			}
		}
		/*** Load Css External Files ***/
		$js_path = OZ_PATH_LIBRARY . path_rewrite( 'Media/JavaScript/Auto/' );
		$dir_contents = $zppFilesystem->dirRead( $js_path );
		if ( !empty( $dir_contents ) ) {
			foreach ( $dir_contents as $file ) {
				$path = $js_path . $file;
				if ( is_file( $path ) ) {
					$file_contents = $oz->fileRead( $js_path . $file );
					$head .= ' <SCRIPT type="text/javascript">';
					$head .= "\r\n$file_contents\r\n";
					$head .= " </SCRIPT>\r\n";
				}
			}
		}
		$head .= "</HEAD>\r\n";
		
		print $head;
	}
	
	/**
	 * 
	 * Print body
	 * @param (string) $str
	 * 
	 */
	public function body( $showBodyTags = true )
	{
		global $ozSession, $zppFilesystem;
		
		/* Checks before we continue */
		if ( !empty( $_POST[ 'FormType' ] ) ) {
			$path = OZ_PATH_LIBRARY . path_rewrite( 'Forms/' . $_POST[ 'FormType' ] . '.php' );
			if ( file_exists( $path ) ) {
				require $path;
			} else {
				oz_quit( 'Error: Unable to process form: Missing file to process ' . $_POST[ 'FormType' ] );
			}
			exit;
		}
		if ( isset( $_GET[ 'media' ] ) ) {
			require OZ_PATH_WEBUI . 'media.php';
			exit;
		}
		
		/* Set object to variable so sub scripts can access this object */
		$ozHtml = $this;
		
		if ( $showBodyTags )
			print "<BODY>\r\n";
		if ( !$ozSession->isAuth() ) {
			require OZ_PATH_WEBUI . path_rewrite( 'Auth.php' );
		} else {
			require OZ_PATH_WEBUI . path_rewrite( 'Console.php' );
		}
		if ( $showBodyTags )
			print "</BODY>\r\n";
	}
	
	/**
	 * 
	 * Prints HTML Footer
	 * @access Public
	 * 
	 */
	public function footer()
	{
		print "\r\n" . '</HTML>';
	}
	
	public function formHead()
	{
		print '<form method="post" action="' . $_SERVER[ 'PHP_SELF' ] . '" class="forms font">' . "\r\n";
	}
	
	public function formFooter()
	{
		print "\r\n</form>";
	}
	
	public function errorBox( $str )
	{
$error_box = <<<EOF
 <div class="font red_box">
  $str
 </div>\r\n
EOF;
		return $error_box;
	}
	
	
	public function getLoginForm( $loginType = null )
	{
		
			
@$loginForm .= <<<EOF
  
EOF;
		return $this->form( $loginForm );
	}
	
	public function buildConsoleMenus()
	{
		global $zppFilesystem;
		
		
	}
	
}

$ozInitThisExtension = true;