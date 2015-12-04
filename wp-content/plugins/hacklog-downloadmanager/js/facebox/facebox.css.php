<?php
/**
 * $Id: facebox.css.php 449108 2011-10-09 16:07:05Z ihacklog $
 * $Revision: 449108 $
 * $Date: 2011-10-09 16:07:05 +0000 (Sun, 09 Oct 2011) $
 * @package Hacklog-DownloadManager
 * @encoding UTF-8 
 * @author 荒野无灯 <HuangYeWuDeng> 
 * @link http://ihacklog.com 
 * @copyright Copyright (C) 2011 荒野无灯 
 * @license http://www.gnu.org/licenses/
 */

//$etag = md5_file(__FILE__);	
$fp = fopen(__FILE__, "r");
$fstat = fstat($fp);
$fstat['atime']='';
$etag = md5(serialize($fstat)); 
fclose($fp);
	
$last_modified_time = filemtime(__FILE__);
$last_modified = gmdate('D, d M Y H:i:s', $last_modified_time) . ' GMT';
// did the browser send an if-modified-since request?
if ( isset($_SERVER['HTTP_IF_NONE_MATCH']) || isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ) 
{
  // parse header
  $if_modified_since = preg_replace('/;.*$/', '', $_SERVER['HTTP_IF_MODIFIED_SINCE']);

  if (
  	  $if_modified_since == $last_modified ||  
  	  (isset($_SERVER['HTTP_IF_NONE_MATCH']) && trim($_SERVER['HTTP_IF_NONE_MATCH']) == $etag )
   ) 
   {
    // the browser's cache is still up to date
    header('HTTP/1.0 304 Not Modified');
    header('Cache-Control: must-revalidate');
    exit;
 	}
}

if ( !( ( ini_get( 'zlib.output_compression' ) == 'On' || ini_get( 'zlib.output_compression_level' ) > 0 ) || ini_get( 'output_handler' ) == 'ob_gzhandler' )  && extension_loaded( 'zlib' ) )
{	
	ob_start( 'ob_gzhandler' );	
} 
header("Content-type: text/css"); 
header('Cache-Control: must-revalidate');
$offset = 3600 *24  * 7;
$expire_str = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
header($expire_str);
header('Last-Modified: ' . $last_modified);
header("Etag: $etag"); 
?>


#facebox{position:absolute;top:0;left:0;z-index:100;text-align:left}#facebox .popup{position:relative;border:3px solid rgba(0,0,0,0);-webkit-border-radius:5px;-moz-border-radius:5px;border-radius:5px;-webkit-box-shadow:0 0 18px rgba(0,0,0,0.4);-moz-box-shadow:0 0 18px rgba(0,0,0,0.4);box-shadow:0 0 18px rgba(0,0,0,0.4)}#facebox .content{display:table;width:500px;padding:10px;background:#fff;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px}#facebox .content>p:first-child{margin-top:0}#facebox .content>p:last-child{margin-bottom:0}#facebox .close{position:absolute;top:5px;right:5px;padding:2px;background:#fff}#facebox .close img{opacity:.3}#facebox .close:hover img{opacity:1.0}#facebox .loading{text-align:center}#facebox .image{text-align:center}#facebox img{border:0;margin:0}#facebox_overlay{position:fixed;top:0;left:0;height:100%;width:100%}.facebox_hide{z-index:-100}.facebox_overlayBG{background-color:#000;z-index:99}

<?php if(extension_loaded('zlib')) {ob_end_flush();} ?>