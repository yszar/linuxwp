<?php

/**
 * $Id: download_list.php 474566 2011-12-13 10:52:27Z ihacklog $
 * $Revision: 474566 $
 * $Date: 2011-12-13 10:52:27 +0000 (Tue, 13 Dec 2011) $
 * @filename download_list.php 
 * @encoding UTF-8 
 * @author 荒野无灯 <HuangYeWuDeng, admin@ihacklog.com> 
 * @link http://ihacklog.com 
 * @copyright Copyright (C) 2011 荒野无灯 
 * @license http://www.gnu.org/licenses/
 * @datetime Sep 19, 2011  11:04:23 PM
 * @version 1.0
 * @Description
  */
if ( !defined( 'ABSPATH' ) ) 
{ 
      header( 'HTTP/1.1 403 Forbidden', true, 403 );
      die ('Please do not load this page directly. Thanks!');
}

$ihacklog_tab = 0;
		$current_file_base_name = isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : $_SERVER['PHP_SELF'];
		if( basename( $current_file_base_name ) == 'download-upload-or-add.php' )
		{
			$ihacklog_tab = 1;
		}
global $wpdb;		
$file_path = hacklogdm_admin::get_opt('download_path');
$file_page = intval( hacklogdm_admin::get('filepage',1));
$file_sortby = trim(hacklogdm_admin::get('by'));
$file_sortby_text = '';
$file_sortorder = trim( hacklogdm_admin::get('order') );
$file_sortorder_text = '';
$file_perpage = intval( hacklogdm_admin::get('perpage',10));
$file_sort_url = '';
$file_search = addslashes( hacklogdm_admin::get('search', '') );
$file_search_query = '';
		
### Form Sorting URL
if(!empty($file_sortby)) {
	$file_sort_url .= '&amp;by='.$file_sortby;
}
if(!empty($file_sortorder)) {
	$file_sort_url .= '&amp;order='.$file_sortorder;
}
if(!empty($file_perpage)) {
	$file_sort_url .= '&amp;perpage='.$file_perpage;
}


### Searching
if(!empty($file_search)) {
	$file_search_query = "AND (file LIKE ('%$file_search%') OR file_name LIKE('%$file_search%') OR file_des LIKE ('%$file_search%'))";
	$file_sort_url .= '&amp;search='.stripslashes($file_search);
}


### Get Order By
switch($file_sortby) {
	case 'id':
		$file_sortby = 'file_id';
		$file_sortby_text = __('File ID', hacklogdm::textdomain );
		break;
	case 'file':
		$file_sortby = 'file';
		$file_sortby_text = __('File', hacklogdm::textdomain );
		break;
	case 'size':
		$file_sortby = '(file_size+0.00)';
		$file_sortby_text = __('File Size', hacklogdm::textdomain );
		break;
	case 'hits':
		$file_sortby = 'file_hits';
		$file_sortby_text = __('File Hits', hacklogdm::textdomain);
		break;
	case 'permission':
		$file_sortby = 'file_permission';
		$file_sortby_text = __('File Permission', hacklogdm::textdomain);
		break;
	case 'date':
		$file_sortby = 'file_date';
		$file_sortby_text = __('File Date', hacklogdm::textdomain);
		break;
	case 'updated_date':
		$file_sortby = 'file_updated_date';
		$file_sortby_text = __('File Updated Date', hacklogdm::textdomain);
		break;
	case 'last_downloaded_date':
		$file_sortby = 'file_last_downloaded_date';
		$file_sortby_text = __('File Last Downloaded Date', hacklogdm::textdomain);
		break;
	case 'name':
	default:
		//还是默认为ID方便一点
		$file_sortby = 'file_id';
		$file_sortby_text = __('File ID', hacklogdm::textdomain);
}


### Get Sort Order
switch($file_sortorder) {
	case 'asc':
		$file_sortorder = 'ASC';
		$file_sortorder_text = __('Ascending', hacklogdm::textdomain);
		break;
	case 'desc':
	default:
		//默认为ID的 DESC方便些
		$file_sortorder = 'DESC';
		$file_sortorder_text = __('Descending', hacklogdm::textdomain );
}


	$get_total_files = $wpdb->get_var("SELECT COUNT(file_id) FROM $wpdb->downloads WHERE 1=1 $file_search_query");
			
	### Checking $file_page and $offset
	if(empty($file_page) || $file_page == 0) { $file_page = 1; }
	if(empty($offset)) { $offset = 0; }
	if(empty($file_perpage) || $file_perpage == 0) { $file_perpage = 20; }

	### Determin $offset
	$offset = ($file_page-1) * $file_perpage;

	### Determine Max Number Of Polls To Display On Page
	if(($offset + $file_perpage) > $get_total_files) {
		$max_on_page = $get_total_files;
	} else {
		$max_on_page = ($offset + $file_perpage);
	}

	### Determine Number Of Polls To Display On Page
	if (($offset + 1) > ($get_total_files)) {
		$display_on_page = $get_total_files;
	} else {
		$display_on_page = ($offset + 1);
	}

	### Determing Total Amount Of Pages
	$total_pages = ceil($get_total_files / $file_perpage);

	### Get Files
	$files = $wpdb->get_results("SELECT * FROM $wpdb->downloads WHERE 1=1 $file_search_query ORDER BY $file_sortby $file_sortorder LIMIT $offset, $file_perpage");
	
?>
<div class="wrap">
	<div id="icon-hacklog-downloadmanager" class="icon32"><br /></div>
<?php if( !$ihacklog_tab ):?>
<h2><?php _e('Manage Downloads', hacklogdm::textdomain ); ?></h2>
<h3><?php _e('Downloads', hacklogdm::textdomain ); ?></h3>
<?php endif;?>
<p><?php printf(__('Displaying <strong>%s</strong> To <strong>%s</strong> Of <strong>%s</strong> Files', hacklogdm::textdomain ), number_format_i18n($display_on_page), number_format_i18n($max_on_page), number_format_i18n($get_total_files)); ?> / <?php printf(__('Sorted By <strong>%s</strong> In <strong>%s</strong> Order', hacklogdm::textdomain ), $file_sortby_text, $file_sortorder_text); ?></p>
<?php if( !$ihacklog_tab ):?>
<p>
<span style="text-align:center;padding:0 400px;"> <?php _e('File id:', hacklogdm::textdomain ); ?>
<input type="text" name="edit_id" id="edit_id" /> <input type="button"
	value="<?php _e('Edit it!', hacklogdm::textdomain ); ?>"
	class="button"
	onclick="window.location.href='<?php echo "$base_page&mode=edit&id="?>'+document.getElementById('edit_id').value;return false;" />
</span></p>
<?php endif;?>

<table class="widefat <?php if( $ihacklog_tab ):?> table-downloadmanager-tab<?php endif;?>">
	<thead>
		<tr>
			<th><?php _e('ID', hacklogdm::textdomain ); ?></th>
			<th><?php _e('File', hacklogdm::textdomain ); ?></th>
			<th><?php _e('Size', hacklogdm::textdomain ); ?></th>
			<?php if( !$ihacklog_tab ):?><th><?php _e('Hits', hacklogdm::textdomain ); ?></th><?php endif;?>
			<th><?php _e('Permission', hacklogdm::textdomain ); ?></th>
			<th><?php _e('Date/Time Added', hacklogdm::textdomain ); ?></th>
			<th colspan="2"><?php _e('Action', hacklogdm::textdomain ); ?></th>
		</tr>
	</thead>
	<?php
	if($files) {;
	$i = 0;
	foreach($files as $file) {
		$file_id = intval($file->file_id);
		$file_name = stripslashes($file->file);
		$file_des = stripslashes($file->file_des);
		$file_nicename = !hacklogdm::is_remote_file(stripslashes($file->file)) && !file_exists($file_path.stripslashes($file->file))?'<span style="color:red;">'.sprintf(__('file <strong>%s</strong> does not exists!', hacklogdm::textdomain ),$file->file_name).'</span>':stripslashes($file->file_name);
		$file_des = stripslashes($file->file_des);
		$file_size = $file->file_size;
		$file_date = mysql2date(get_option('date_format'), gmdate('Y-m-d H:i:s', $file->file_date));
		$file_time = mysql2date(get_option('time_format'), gmdate('Y-m-d H:i:s', $file->file_date));
		$file_updated_date = mysql2date(get_option('date_format'), gmdate('Y-m-d H:i:s', $file->file_updated_date));
		$file_updated_time = mysql2date(get_option('time_format'), gmdate('Y-m-d H:i:s', $file->file_updated_date));
		$file_last_downloaded_date = mysql2date(get_option('date_format'), gmdate('Y-m-d H:i:s', $file->file_last_downloaded_date));
		$file_last_downloaded_time = mysql2date(get_option('time_format'), gmdate('Y-m-d H:i:s', $file->file_last_downloaded_date));
		$file_hits = intval($file->file_hits);
		$file_permission = hacklogdm_admin::file_permission($file->file_permission);
		$file_name_actual = hacklogdm::get_basename($file_name);
		if($i%2 == 0) {
			$style = '';
		}  else {
			$style = ' class="alternate"';
		}
		echo "<tr$style>\n";
		echo '<td valign="top">'.number_format_i18n($file_id).'</td>'."\n";
		echo "<td>$file_nicename<br /><strong>&raquo;</strong> <i dir=\"ltr\">".hacklogdm::snippet_text($file_name, 45)."</i><br /><br />";
		if( !$ihacklog_tab )
		{
		echo "<i>".sprintf(__('Last Updated: %s, %s', hacklogdm::textdomain ), $file_updated_time, $file_updated_date)."</i><br /><i>".sprintf(__('Last Downloaded: %s, %s', hacklogdm::textdomain ), $file_last_downloaded_time, $file_last_downloaded_date)."</i>";
		}
		echo "</td>\n";
		echo '<td style="text-align: center;">'.hacklogdm::format_filesize($file_size).'</td>'."\n";
		echo !$ihacklog_tab ? '<td style="text-align: center;">'.number_format_i18n($file_hits).'</td>'."\n" : '';
		echo '<td style="text-align: center;">'.$file_permission.'</td>'."\n";
		echo "<td>$file_date , $file_time</td>\n";
		if(!$ihacklog_tab)
		{
		echo "<td style=\"text-align: center;\"><a href=\"$base_page&amp;mode=edit&amp;id=$file_id\" class=\"edit\">".__('Edit', hacklogdm::textdomain )."</a></td>\n";
		echo "<td style=\"text-align: center;\"><a href=\"$base_page&amp;mode=delete&amp;id=$file_id\" class=\"delete\">".__('Delete', hacklogdm::textdomain )."</a></td>\n";
		}
		else
		{
		echo "<td style=\"text-align: center;\"><input type=\"button\" onclick=\"insert_into_post($file_id);\" class=\"button button-primary\" name=\"insertintopost\" value=\"" . __('Insert into post', 'hacklog-downloadmanager') . "\" /></td>\n";	
		}
		echo '</tr>';
		$i++;
	}
	} else {
		echo '<tr><td colspan="9" align="center"><strong>'.__('No Files Found', hacklogdm::textdomain ).'</strong></td></tr>';
	}
	?>
</table>
<!-- <Paging> --> <?php
if($total_pages > 1) {
	?> <br />
<table class="widefat<?php if( $ihacklog_tab ):?> table-downloadmanager-tab<?php endif;?>">
	<tr>
		<td
			align="<?php echo ('rtl' == $text_direction) ? 'right' : 'left'; ?>"
			width="50%"><?php
			if($file_page > 1 && ((($file_page*$file_perpage)-($file_perpage-1)) <= $get_total_files)) {
				echo '<strong>&laquo;</strong> <a class="download-page-item" href="'.$base_page.'&amp;filepage='.($file_page-1).$file_sort_url.'" title="&laquo; '.__('Previous Page', hacklogdm::textdomain ).'">'.__('Previous Page', hacklogdm::textdomain ).'</a>';
			} else {
				echo '&nbsp;';
			}
			?></td>
		<td
			align="<?php echo ('rtl' == $text_direction) ? 'left' : 'right'; ?>"
			width="50%"><?php
			if($file_page >= 1 && ((($file_page*$file_perpage)+1) <= $get_total_files)) {
				echo '<a class="download-page-item" href="'.$base_page.'&amp;filepage='.($file_page+1).$file_sort_url.'" title="'.__('Next Page', hacklogdm::textdomain ).' &raquo;">'.__('Next Page', hacklogdm::textdomain ).'</a> <strong>&raquo;</strong>';
			} else {
				echo '&nbsp;';
			}
			?></td>
	</tr>
	<tr class="alternate">
		<td colspan="2" align="center"><?php _e('Total Pages', hacklogdm::textdomain ); ?>
		(<?php echo number_format_i18n($total_pages); ?>): <?php
		if ($file_page >= 4) {
			echo '<strong><a class="download-page-item" href="'.$base_page.'&amp;filepage=1'.$file_sort_url.'" title="'.__('Go to First Page', hacklogdm::textdomain ).'">&laquo; '.__('First', hacklogdm::textdomain ).'</a></strong> ... ';
		}
		if($file_page > 1) {
			echo ' <strong><a class="download-page-item" href="'.$base_page.'&amp;filepage='.($file_page-1).$file_sort_url.'" title="&laquo; '.__('Go to Page', hacklogdm::textdomain ).' '.number_format_i18n($file_page-1).'">&laquo;</a></strong> ';
		}
		for($i = $file_page - 2 ; $i  <= $file_page +2; $i++) {
			if ($i >= 1 && $i <= $total_pages) {
				if($i == $file_page) {
					echo '<strong class="download-page-item-current" >['.number_format_i18n($i).']</strong> ';
				} else {
					echo '<a class="download-page-item" href="'.$base_page.'&amp;filepage='.($i).$file_sort_url.'" title="'.__('Page', hacklogdm::textdomain ).' '.number_format_i18n($i).'">'.number_format_i18n($i).'</a> ';
				}
			}
		}
		if($file_page < $total_pages) {
			echo ' <strong><a class="download-page-item" href="'.$base_page.'&amp;filepage='.($file_page+1).$file_sort_url.'" title="'.__('Go to Page', hacklogdm::textdomain ).' '.number_format_i18n($file_page+1).' &raquo;">&raquo;</a></strong> ';
		}
		if (($file_page+2) < $total_pages) {
			echo ' ... <strong><a class="download-page-item" href="'.$base_page.'&amp;filepage='.($total_pages).$file_sort_url.'" title="'.__('Go to Last Page', hacklogdm::textdomain ), 'hacklog-downloadmanager'.'">'.__('Last', hacklogdm::textdomain ).' &raquo;</a></strong>';
		}
		?></td>
	</tr>
</table>
<!-- </Paging> --> <?php
}
?> <br />
<form action="<?php echo !$ihacklog_tab ? $download_list_action: 'download-upload-or-add.php?tab=downloads' ; ?>"
	method="get">
<table class="widefat">
	<tr>
		<th><?php _e('Filter Options: ', hacklogdm::textdomain ); ?></th>
		<td><?php _e('Keywords:', hacklogdm::textdomain ); ?><input
			type="text" name="search" size="30" maxlength="200"
			value="<?php echo stripslashes($file_search); ?>" /></td>
	</tr>
	<tr>
		<th><?php _e('Sort Options:', hacklogdm::textdomain ); ?></th>
		<!-- if page variable is null, wp will give you a error: you do not have permission to access this page.
		so ,this var MUST be set OR delete this hidden input totally!
		-->
		<?php if( !$ihacklog_tag ):?>
		<input type="hidden" name="page" value="<?php echo $base_name;?>" />
		<?php endif;?>
		<td>
		<select name="by" size="1">
			<option value="id"
			<?php if($file_sortby == 'file_id') { echo ' selected="selected"'; }?>><?php _e('File ID', hacklogdm::textdomain ); ?></option>
			<option value="file"
			<?php if($file_sortby == 'file') { echo ' selected="selected"'; }?>><?php _e('File', hacklogdm::textdomain ); ?></option>
			<option value="name"
			<?php if($file_sortby == 'file_name') { echo ' selected="selected"'; }?>><?php _e('File Name', hacklogdm::textdomain ); ?></option>
			<option value="date"
			<?php if($file_sortby == 'file_date') { echo ' selected="selected"'; }?>><?php _e('File Date', hacklogdm::textdomain ); ?></option>
			<option value="updated_date"
			<?php if($file_sortby == 'updated_date') { echo ' selected="selected"'; }?>><?php _e('File Updated Date', hacklogdm::textdomain ); ?></option>
			<option value="last_downloaded_date"
			<?php if($file_sortby == 'last_downloaded_date') { echo ' selected="selected"'; }?>><?php _e('File Last Downloaded Date', hacklogdm::textdomain ); ?></option>
			<option value="size"
			<?php if($file_sortby == '(file_size+0.00)') { echo ' selected="selected"'; }?>><?php _e('File Size', hacklogdm::textdomain ); ?></option>
			<option value="hits"
			<?php if($file_sortby == 'file_hits') { echo ' selected="selected"'; }?>><?php _e('File Hits', hacklogdm::textdomain ); ?></option>
			<option value="permission"
			<?php if($file_sortby == 'file_timestamp') { echo ' selected="selected"'; }?>><?php _e('File Permission', hacklogdm::textdomain ); ?></option>
		</select> &nbsp;&nbsp;&nbsp; <select name="order" size="1">
			<option value="asc"
			<?php if($file_sortorder == 'ASC') { echo ' selected="selected"'; }?>><?php _e('Ascending', hacklogdm::textdomain ); ?></option>
			<option value="desc"
			<?php if($file_sortorder == 'DESC') { echo ' selected="selected"'; } ?>><?php _e('Descending', hacklogdm::textdomain ); ?></option>
		</select> &nbsp;&nbsp;&nbsp; <select name="perpage" size="1">
		<?php
		for($k=10; $k <= 100; $k+=10) {
			if($file_perpage == $k) {
				echo "<option value=\"$k\" selected=\"selected\">".__('Per Page', hacklogdm::textdomain ).": ".number_format_i18n($k)."</option>\n";
			} else {
				echo "<option value=\"$k\">".__('Per Page', hacklogdm::textdomain ).": ".number_format_i18n($k)."</option>\n";
			}
		}
		?>
		</select></td>
	</tr>
	<tr>
		<td colspan="2" align="center"><input type="submit"
			value="<?php _e('Go', hacklogdm::textdomain ); ?>" class="button" /></td>
	</tr>
</table>
	<?php if( $ihacklog_tab ):?>
	<input type="hidden" name="tab" value="downloads" />
	<?php endif;?>
</form>
</div>
