<?php

/**
 * 
 * Home_P@ge: a simple PHP class to build personal home pages
 *
 * Author: Pasquale Musella - 2011
 * Contact: pasquale.musella@cern.ch
 * 
 * This program is free software: you can redistribute it and/or modify
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

require_once('simplepie.inc');
require_once('markdown.php');
require_once('functions.php');

/**
 * Main class
 */
class HomePage
{
    public $site_url="";
    public $site_title="";
    public $contact="contact email";
    protected $attributes = Array();

    public $page_path="";
    public $page_url="";
    public $page_title="";
    public $style="res/style.css";

    protected $elements = Array();
    protected $side_plugins = Array();
    protected $side_contents = Array();

    public function __construct($site_title="<i>_Home_P@ge_</i>",$title="") {
           $this->site_title=$site_title;
           if( $title=="" ) {
                $this->title=$site_title;
           } else {
                $this->title=$title;
           }
    }

    public function __get($key){
          return array_key_exists($key, $this->attributes) ? $this->attributes[$key] : null;
    }
     
    public function __set($key, $value){
          $this->attributes[$key] = $value;
    }

    public function process_input() {
           $this->fetch_content();
           $this->fetch_side_content();
    }

    public function fetch_content() {
	    $page="home";
	    if( !empty($_GET["p"]) ) { $page=$_GET["p"]; } 
	    
	    if( $content = @file_get_contents(CONTENT_ROOT . $page. ".markdown") ) {
		    $this->content=Markdown($content);
	    } else {
		    $this->content = "<br/> <br/><span style=\"font-size: 150; color : red;\" >Ooops! The page was not found.</span> &gt;&gt; <a href=\"". BASE_URL."\">home</a>\n";
	    }
	    $this->page_name = $page;
	    $this->site_url = BASE_URL;
            $this->media_url = BASE_URL . "/" . $this->media_dir;
	    $this->page_url = $this->site_url;
	    if( $page != "home" ) { $this->page_url .= "/?p=" . $page; }
    }

    public function fetch_side_content() {
           foreach( $this->side_plugins as $side_plugin ) {
                  list($title,$func,$args) = $side_plugin;
                  // $title = Markdown($title);
                  $this->side_contents[] = Array( $title, $func( $args ) );
           }
    }
    
    public function add_side_content($title,$func,$args) {
           $this->side_plugins[] = Array($title,$func,$args);
    }
    
    public function __toString() {
	    return  $this->header() . "\n" .
		    $this->side()   . "\n" . 
		    $this->content(). "\n" . 
		    $this->footer() . "\n" ;
    }

    protected function header() {
            $navbar = "";
            if( $this->page_name != "home" ) {
                $navbar = <<< EOS
<div style="text-align: left;">&nbsp;&nbsp;&nbsp;&gt;&gt;&nbsp;<a href="$this->page_url">$this->page_name</a>&nbsp; [<a href="$this->site_url">home</a>&nbsp;] &lt;&lt;</div>
EOS;
	    }
	    return <<< EOS
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML Basic 1.1//EN" "http://www.w3.org/TR/xhtml-basic/xhtml-basic11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta content="text/html;charset=ISO-8859-1" http-equiv="Content-Type"/>
<meta name="links_base" content="$this->media_url" />
<meta name="url" content="$this->site_url" />
<meta name="keywords" content="$this->keywords" />
<link rel="stylesheet" type="text/css" href="$this->style"></link>
<title>$this->title</title>

</head>
<body>

<div id="header">
  <fieldset class="round">
  <h1>$this->site_title</h1>
  </fieldset>
  $navbar
</div>
EOS;
    }

    protected function side() {
            $side  = <<< EOS
<div id="side">
EOS;
            foreach( $this->side_contents as $side_content ) {
                   list($title,$content) = $side_content;
                   $side .= <<< EOS
<fieldset class="box">
  <legend>$title</legend>
  $content
</fieldset>
EOS;
            }
	    $side .= <<< EOS
</div>
EOS;
	    return $side;
    }

    protected function content() {
	    return <<< EOS
<div id="content">
$this->content
</div>
EOS;

    }

    protected function footer() {
	    return <<< EOS
<div id="footer">

<p style="font-style: italic; text-align: left">contact: $this->contact</p>
<img src="http://www.w3.org/Icons/valid-xhtmlbasic10-blue.png" alt="[valid xhtml basic 1.0]"/>

</div>

</body>
</html>
EOS;
    }
 
}



/**
 * Functions wrappers
 */
function bookmarks($args)
{
        list($filename) = $args;
        return get_bookmarks(CONTENT_ROOT .  $filename . ".txt" );
}

function feed($args)
{
        list($url,$nitems) = $args;
        $ret = "";
        $feed = new SimplePie();

        $feed->enable_cache(0);
        $feed->set_feed_url($url);
        $feed->init();
        $ret .= "<ul>";
        foreach ($feed->get_items(0, $nitems) as $item) {
		$ret .= "<li>";
		$ret .= $item->get_title();
		$ret .= resource($item->get_permalink(),"read");
	        $ret .= "<br/>";
	        $ret .= "</li>";
        }
        $ret .= "</ul>";
	return $ret;
}

function mdown($args)
{
	list($file)=$args;
	if( $content = @file_get_contents(CONTENT_ROOT . $file. ".markdown") ) {
	       return Markdown($content);
	} else {
               return Markdown($file);
	}
}
	
?>
