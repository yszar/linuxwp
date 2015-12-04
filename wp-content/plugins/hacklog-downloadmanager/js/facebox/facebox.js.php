<?php 
/**
 * Copyright (C)  2011 荒野无灯 <admin@ihacklog.com>
 * All rights reserved.
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * facebox.js 's copyright is owned by Chris Wanstrath
 */

/**
 * $Id: facebox.js.php 449107 2011-10-09 16:06:44Z ihacklog $
 * $Revision: 449107 $
 * $Date: 2011-10-09 16:06:44 +0000 (Sun, 09 Oct 2011) $
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
header("Content-type: text/javascript");
header('Cache-Control: must-revalidate');
$offset = 3600 *24  * 7;
$expire_str = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
header($expire_str);
header('Last-Modified: ' . $last_modified);
header("Etag: $etag"); 


$plugin_url = dirname($_SERVER['REQUEST_URI']).'/';
$plugin_url = 'http://'. $_SERVER['HTTP_HOST'] . $plugin_url;
?>
/*
 * Facebox (for jQuery)
 * version: 1.2 (05/05/2008)
 * @requires jQuery v1.2 or later
 *
 * Examples at http://famspam.com/facebox/
 *
 * Licensed under the MIT:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Copyright 2007, 2008 Chris Wanstrath [ chris@ozmm.org ]
 *
 * Usage:
 *
 *  jQuery(document).ready(function() {
 *    jQuery('a[rel*=facebox]').facebox()
 *  })
 *
 *  <a href="#terms" rel="facebox">Terms</a>
 *    Loads the #terms div in the box
 *
 */
(function(f){f.facebox=function(m,l){f.facebox.loading();if(m.ajax){g(m.ajax,l);}else{if(m.image){c(m.image,l);}else{if(m.div){j(m.div,l);}else{if(f.isFunction(m)){m.call(f);}else{f.facebox.reveal(m,l);}}}}};f.extend(f.facebox,{settings:{opacity:0.2,overlay:true,loadingImage:"loading.gif",closeImage:"closelabel.png",imageTypes:["png","jpg","jpeg","gif"],faceboxHtml:'    <div id="facebox" style="display:none;">       <div class="popup">         <div class="content">         </div>         <a href="#" class="close"><img src="<?php echo $plugin_url;?>closelabel.png" title="close" class="close_image" /></a>       </div>     </div>'},loading:function(){k();if(f("#facebox .loading").length==1){return true;}e();f("#facebox .content").empty();f("#facebox .body").children().hide().end().append('<div class="loading"><img src="'+f.facebox.settings.loadingImage+'"/></div>');f("#facebox").css({top:h()[1]+(i()/10),left:f(window).width()/2-205}).show();f(document).bind("keydown.facebox",function(l){if(l.keyCode==27){f.facebox.close();}return true;});f(document).trigger("loading.facebox");},reveal:function(m,l){f(document).trigger("beforeReveal.facebox");if(l){f("#facebox .content").addClass(l);}f("#facebox .content").append(m);f("#facebox .loading").remove();f("#facebox .body").children().fadeIn("normal");f("#facebox").css("left",f(window).width()/2-(f("#facebox .popup").width()/2));f(document).trigger("reveal.facebox").trigger("afterReveal.facebox");},close:function(){f(document).trigger("close.facebox");return false;}});f.fn.facebox=function(l){if(f(this).length==0){return;}k(l);function m(){f.facebox.loading(true);var n=this.rel.match(/facebox\[?\.(\w+)\]?/);if(n){n=n[1];}j(this.href,n);return false;}return this.bind("click.facebox",m);};function k(n){if(f.facebox.settings.inited){return true;}else{f.facebox.settings.inited=true;}f(document).trigger("init.facebox");d();var l=f.facebox.settings.imageTypes.join("|");f.facebox.settings.imageTypesRegexp=new RegExp(".("+l+")$","i");if(n){f.extend(f.facebox.settings,n);}f("body").append(f.facebox.settings.faceboxHtml);var m=[new Image(),new Image()];m[0].src=f.facebox.settings.closeImage;m[1].src=f.facebox.settings.loadingImage;f("#facebox").find(".b:first, .bl").each(function(){m.push(new Image());m.slice(-1).src=f(this).css("background-image").replace(/url\((.+)\)/,"$1");});f("#facebox .close").click(f.facebox.close);f("#facebox .close_image").attr("src",f.facebox.settings.closeImage);}function h(){var m,l;if(self.pageYOffset){l=self.pageYOffset;m=self.pageXOffset;}else{if(document.documentElement&&document.documentElement.scrollTop){l=document.documentElement.scrollTop;m=document.documentElement.scrollLeft;}else{if(document.body){l=document.body.scrollTop;m=document.body.scrollLeft;}}}return new Array(m,l);}function i(){var l;if(self.innerHeight){l=self.innerHeight;}else{if(document.documentElement&&document.documentElement.clientHeight){l=document.documentElement.clientHeight;}else{if(document.body){l=document.body.clientHeight;}}}return l;}function d(){var l=f.facebox.settings;l.loadingImage=l.loading_image||l.loadingImage;l.closeImage=l.close_image||l.closeImage;l.imageTypes=l.image_types||l.imageTypes;l.faceboxHtml=l.facebox_html||l.faceboxHtml;}function j(m,l){if(m.match(/#/)){var n=window.location.href.split("#")[0];var o=m.replace(n,"");if(o=="#"){return;}f.facebox.reveal(f(o).html(),l);}else{if(m.match(f.facebox.settings.imageTypesRegexp)){c(m,l);}else{g(m,l);}}}function c(m,l){var n=new Image();n.onload=function(){f.facebox.reveal('<div class="image"><img src="'+n.src+'" /></div>',l);};n.src=m;}function g(m,l){f.get(m,function(n){f.facebox.reveal(n,l);});}function b(){return f.facebox.settings.overlay==false||f.facebox.settings.opacity===null;}function e(){if(b()){return;}if(f("#facebox_overlay").length==0){f("body").append('<div id="facebox_overlay" class="facebox_hide"></div>');}f("#facebox_overlay").hide().addClass("facebox_overlayBG").css("opacity",f.facebox.settings.opacity).click(function(){f(document).trigger("close.facebox");}).fadeIn(200);return false;}function a(){if(b()){return;}f("#facebox_overlay").fadeOut(200,function(){f("#facebox_overlay").removeClass("facebox_overlayBG");f("#facebox_overlay").addClass("facebox_hide");f("#facebox_overlay").remove();});return false;}f(document).bind("close.facebox",function(){f(document).unbind("keydown.facebox");f("#facebox").fadeOut(function(){f("#facebox .content").removeClass().addClass("content");f("#facebox .loading").remove();f(document).trigger("afterClose.facebox");});a();});})(jQuery);

 	jQuery(function($) {  
 		    $.facebox.settings.closeImage = '<?php echo $plugin_url;?>closelabel.png';
    		$.facebox.settings.loadingImage = '<?php echo $plugin_url;?>loading.gif';
			//$.facebox.settings.overlay = false; //remove bg overlay
			$('a[rel*=facebox]').facebox();
		});  

<?php if(extension_loaded('zlib')) {ob_end_flush();} ?>		