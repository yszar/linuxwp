<?php
/*
 Plugin Name: Hacklog DownloadManager
 Plugin URI: http://ihacklog.com/?p=3775
 Description: WordPress下载管理器,根据Lester 'GaMerZ' Chan的WP-DownloadManager 1.5版修改完善。A download manager for your WordPress blog,modified from WP-DownloadManager 1.5 originally by Lester 'GaMerZ' Chan
 Version: 2.1.4
 Author: <a href="http://ihacklog.com/">荒野无灯</a>
 Author URI: http://ihacklog.com/
 */

/**
 * $Id: hacklog-downloadmanager.php 475262 2011-12-14 09:33:23Z ihacklog $
 * $Revision: 475262 $
 * $Date: 2011-12-14 09:33:23 +0000 (Wed, 14 Dec 2011) $
 * @package Hacklog-DownloadManager
 * @encoding UTF-8 
 * @author 荒野无灯 <HuangYeWuDeng> 
 * @link http://ihacklog.com 
 * @copyright Copyright (C) 2011 荒野无灯 
 * @license http://www.gnu.org/licenses/
 * some of the code is from Lester "GaMerZ" Chan	's WP-downloadmanger plugin,
 * Thanks to  Lester "GaMerZ" Chan.  <http://lesterchan.net/wordpress/category/plugins/wp-downloadmanager/>
 */

/*
 Copyright 2011 荒野无灯 

 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

define('HACKLOGDM_LOADER',__FILE__);
require plugin_dir_path(__FILE__) . '/includes/hacklogdm.class.php';

hacklogdm::init();
