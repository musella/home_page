<?php

/// error_reporting(E_ALL);
ini_set('display_errors', '1');

define( 'CONTENT_ROOT', "content/" );
define( 'BASE_URL', "http://your_web_page_address" );

require_once('res/php/home_page.php');

$hp=new HomePage("My Home Page");

$hp->contact="your email address";
$hp->media_dir = "media/";
$hp->add_side_content( "Links", "bookmarks", array("bookmarks") );
$hp->add_side_content( "More",  "mdown",     array("more") );
$hp->add_side_content( "From the arXiv", "feed", array("http://export.arxiv.org/rss/hep-ex",7) );
$hp->process_input();

echo $hp;
?>
