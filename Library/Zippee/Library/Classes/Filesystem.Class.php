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

/**
 * @category Class
 * @name Filesystem
 * @version 0.2.0
 * 
 */
class Filesystem
{
	public function __construct()
	{
		//parent::__construct(@$path_config);
	}
	
	/**
	 * 
	 * Reads directory contents into array
	 * @param (string) $dir Directory Path
	 * @return (array) Returns directory contents in array or False on error
	 * 
	 */
	public function dirRead( $dir , $returnFullPath = false )
	{
		/* Check if directory exists */
		/* Open directory handle */
		/* Build directory array */
		/*** Default Vars ***/
		$array = array();		
		/*** Series of Checks ***/
		if ( !is_dir( $dir ) ) {
			$this->errorId = 'ERRx0116';
			$this->errorMsg = "Error: is not a directory: '$dir'";
			return false;
		} 
		
		/*** Feature added 08/21/2011 : Check for ending slash ***/
		if ( !preg_match( '/(\\\|\/)$/' , $dir ) ) {
			$dir .= DS;
		}
		
		if ( @is_resource( $this->storage[ 'dirHandles' ][ $dir ][ 'handle' ] ) ) 
			$h = $this->storage[ 'dirHandles' ][ $dir ][ 'handle' ];
		elseif ( !$h = @opendir( $dir ) ) {
			$this->errorId = 'ERR0111';
			$this->errorMsg = "Error: Unable to open directory '$dir': Permission Denied";
		} else {
			/* Store handle in memory */
			$this->storage[ 'dirHandles' ][ $dir ][ 'handle' ] = $h;
		}
		
		while( false !== ($file = readdir( $h ) ) ) {
			if ( $file != '.' && $file != '..' ) {
				if ( $returnFullPath ) {
					/*** Feature added 08/21/2011 : Add feature to return full path ***/
					$array[] = $dir . $file;
				} else {
					$array[] = $file;
				}
			}
		}
		return $array;
	}
	
	/**
	 * 
	 * Copy files to new destination 
	 * @access Public
	 * @param (string) $src Source File(s)
	 * @param (string) $dest Destinate
	 * @return (bool) True on sucess or false on failure
	 * 
	 */
	public function copy( $src , $dest )
	{	
		if( is_dir( $src ) )
        {
        	/* Check if we can write to distination */
        	if ( !is_dir( $dest ) ) {
        		if ( preg_match( '/(c:\\\|\/)/i' , $dest , $match ) ) 
        			$path = $match[1];
        		$dirs = preg_split( '/(\\\|\/)/' , $dest );
        		foreach( $dirs as $dir ) {
        			if ( !empty( $dir ) )
        				if ( is_dir( $path . $dir ) )
        			 		@$path .= $dir . DS;
        				elseif ( !is_writable( $path ) ) {
        					return false;
        				}
        		}
        	}
            @mkdir( $dest );
            $objects = scandir( $src );
            if( count( $objects ) > 0 )
            {
                foreach( $objects as $file )
                {
                    if( $file == "." || $file == ".." )
                        continue;
                    //var_dump( $file );
                    // go on
                    if( is_dir( $src . DS . $file ) )
                    {
                        $this->copy( $src . DS . $file , $dest . DS . $file );
                    }
                    else
                    {
                        copy( $src . DS . $file , $dest . DS . $file );
                    }
                }
            }
            return true;
        }
        elseif( is_file($path) )
        {
            return copy($path, $dest);
        }
        else
        {
            return false;
        }
	}
}