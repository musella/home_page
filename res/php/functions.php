<?php

/**
 * 
 * This file is part of Home_P@ge
 * 
 * Home_P@ge is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */


/**
 * Renders a link.
 */
function bookmark($url,$name,$target="_blank",$opentag="<td>",$closetag="</td>")
{
        $ret = "";
	if ( $opentag != "" ) $ret .= $opentag;
	$encurl = $url;
	$ret .= "[<a href=\"$encurl\" target=\"$target\">$name</a>]";
	if ( $closetag != "" ) $ret .= $closetag;
        return $ret;
}

function resource($url,$name)
{
         return bookmark($url, $name, "_blank", "", "");
}


/**
 * Reads a list of links from a file and renders them.
 *
 * The expected file format is
 * url && linkname 
 */
function get_bookmarks($filename,$target="_blank",$rowlen=12,$opentable="<table>",$closetable="</table>",$openrow="<tr>",$closerow="</tr>",$opencell="<td>",$closecell="</td>")
{
	$file = file_get_contents($filename);
	$bookmarks=split("\n",$file);
  
        $ret = $opentable;
        $n = 1;
        foreach( $bookmarks as $bm ) {
                 if( $bm == "" ) { continue; }
		 list($url,$name) = split("[ ]*&&[ ]*",$bm);
        	 if( ! empty($url) ) {
      	     	     if( $rowlen > 0 && $n % $rowlen == 1 ) { $ret .= $openrow; }
     	     	     $ret .= bookmark( $url, $name, $target, $opencell, $closecell );
      	     	     if( $rowlen > 0 && $n % $rowlen == 0 ) { $ret .= $closerow; }
      	     	     $n++;
        	 }
        }
        if( $rowlen > 0 && $n % $rowlen != 1 ) { $ret .= $closerow; }
        $ret .= $closetable;
        return $ret;
}

function display_bookmarks($filename,$target="_blank",$rowlen=12,$openrow="<tr>",$closerow="</tr>",$opencell="<td>",$closecell="</td>")
{
        echo get_bookmarks($filename,$target,$rowlen,$openrow,$closerow,$opencell,$closecell);
}

/**
 * Directory listing
 */
function list_dir($path)
{
         $dir_handle = @opendir($path) or die("Unable to open $path");
         //running the while loop
         while ($file = readdir($dir_handle)) 
         {
             echo "<a href='$file'>$file</a><br/>";
	 }
        
         closedir($dir_handle);
}

?>
