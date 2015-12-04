<?php
/**
 * 网址：junzibuqi.com--君子不器（Junzibuqi.Com）
**/

?>
<?php
if (!defined('ABSPATH')){
    exit;
}
if(ot_get_option('shop_system') != 'on'){
    return;
}
function create_orders_table(){
     if(ot_get_option('shop_system') == 'on'){
         global $wpdb;
         include_once(ABSPATH . '/wp-admin/includes/upgrade.php');
         $table_charset = '';
         $prefix = $wpdb -> prefix;
         $orders_table = $prefix . 'tin_orders';
         $promotes_table = $prefix . 'tin_promotes';
         if($wpdb -> has_cap('collation')){
             if(!empty($wpdb -> charset)){
                 $table_charset = "DEFAULT CHARACTER SET $wpdb->charset";
                 }
             if(!empty($wpdb -> collate)){
                 $table_charset .= " COLLATE $wpdb->collate";
                 }
             }
         $create_orders_sql = "CREATE TABLE $orders_table (id int(11) NOT NULL auto_increment,order_id varchar(30) NOT NULL,trade_no varchar(30) NOT NULL,product_id int(20) NOT NULL,product_name varchar(250),order_time datetime NOT NULL default '0000-00-00 00:00:00',order_success_time datetime NOT NULL default '0000-00-00 00:00:00',order_price double(10,2) NOT NULL,order_currency varchar(20) NOT NULL default 'credit',order_quantity int(11) NOT NULL,order_total_price double(10,2) NOT NULL,order_status tinyint(4) NOT NULL default 0,order_note text,user_id int(11) NOT NULL,user_name varchar(60),user_email varchar(100),user_address varchar(250),user_zip varchar(10),user_phone varchar(20),user_cellphone varchar(20),user_message text,user_alipay varchar(100),PRIMARY KEY (id),INDEX orderid_index(order_id),INDEX tradeno_index(trade_no),INDEX productid_index(product_id),INDEX uid_index(user_id)) ENGINE = MyISAM $table_charset;";
         maybe_create_table($orders_table, $create_orders_sql);
         $create_promotes_sql = "CREATE TABLE $promotes_table (id int(11) NOT NULL auto_increment,promote_code varchar(20) NOT NULL,promote_type varchar(20) NOT NULL default 'once',promote_status int(11) NOT NULL default 1,discount_value double(10,2) NOT NULL default 0.90,expire_date datetime NOT NULL default '0000-00-00 00:00:00',PRIMARY KEY (id),INDEX promotecode_index(promote_code)) ENGINE = MyISAM $table_charset;";
         maybe_create_table($promotes_table, $create_promotes_sql);
         }
    }
add_action('admin_menu', 'create_orders_table');
function create_store_post_type(){
     register_post_type('store',
         array(
            'labels' => array(
                'name' => _x('商品', 'taxonomy general name'),
                 'singular_name' => _x('商品', 'taxonomy singular name'),
                 'add_new' => __('添加商品', 'tinection'),
                 'add_new_item' => __('添加新商品', 'tinection'),
                 'edit' => __('编辑', 'tinection'),
                 'edit_item' => __('编辑商品', 'tinection'),
                 'new_item' => __('新商品', 'tinection'),
                 'view' => __('浏览', 'tinection'),
                 'all_items' => __('所有商品', 'tinection'),
                 'view_item' => __('浏览商品', 'tinection'),
                 'search_items' => __('搜索商品', 'tinection'),
                 'not_found' => __('未找到商品', 'tinection'),
                 'not_found_in_trash' => __('回收站未找到商品', 'tinection'),
                 'parent' => __('父级商品', 'tinection'),
                 'menu_name' => __('商品资源', 'tinection'),
                ),
             'public' => true,
             'menu_position' => 15,
             'supports' => array('title', 'author', 'editor', 'comments', 'excerpt', 'thumbnail', 'custom-fields'),
             'taxonomies' => array(''),
             'menu_icon' => 'dashicons-cart',
             'has_archive' => true,
             'rewrite' => array('slug' => 'store')
            )
        );
    }
add_action('init', 'create_store_post_type');
function include_store_template_function($template_path){
     if (get_post_type() == 'store'){
         if (is_single()){
             if ($theme_file = locate_template(array ('includes/store/product.php'))){
                 $template_path = $theme_file;
                 }
             }elseif(is_archive()){
             if ($theme_file = locate_template(array ('includes/store/product-archives.php'))){
                 $template_path = $theme_file;
                 }
             }
         }
     return $template_path;
    }
add_filter('template_include', 'include_store_template_function', 1);
function store_seo_info(){
     if (get_post_type() == 'store' && !is_singular()){
         $id = get_queried_object_id();
         if((taxonomy_exists('products_category') || taxonomy_exists('products_tag')) && $id != 0){
             $term_name = get_term_field('name', $id, 'products_category');
             $title = $term_name;
             $paged = get_query_var('paged');
            if ($paged > 1) $title .= sprintf(__(' - 第 %s 页 ', 'tinection'), $paged);
             $title .= ' - ' . get_bloginfo('name') . '商城';
             $keywords = $term_name . ",商城";
             $description = strip_tags(term_description());
             }elseif(is_archive() && $id == 0){
             $title = ot_get_option('shop_archives_title', 'WordPress商店');
             $paged = get_query_var('paged');
            if ($paged > 1) $title .= sprintf(__(' - 第 %s 页 ', 'tinection'), $paged);
             $title .= ' - ' . get_bloginfo('name');
             $keywords = '商品归档,商城,归档';
             $description = get_bloginfo('name') . '商城';
             }
         $seo_text = '<title>' . $title . '</title><meta name="description" content="' . $description . '" /><meta name="keywords" content="' . $keywords . '" />';
         }else{
         $seo_text = '';
         }
     return $seo_text;
    }
function create_store_taxonomies(){
     // Categories
    $products_category_labels = array(
        'name' => _x('商品分类', 'taxonomy general name'),
         'singular_name' => _x('商品分类', 'taxonomy singular name'),
         'search_items' => __('搜索商品分类', 'tinection'),
         'all_items' => __('所有商品分类', 'tinection'),
         'parent_item' => __('父级商品分类', 'tinection'),
         'parent_item_colon' => __('父级商品分类:', 'tinection'),
         'edit_item' => __('编辑商品分类', 'tinection'),
         'update_item' => __('更新商品分类', 'tinection'),
         'add_new_item' => __('添加新商品分类', 'tinection'),
         'new_item_name' => __('新商品分类名称', 'tinection'),
         'menu_name' => __('商品分类', 'tinection'),
        );
     register_taxonomy('products_category', 'store', array(
            'hierarchical' => true,
             'labels' => $products_category_labels,
             'show_ui' => true,
             'query_var' => true,
             'rewrite' => array(
                'slug' => 'store/category',
                 'with_front' => false,
                ),
            ));
     // Tags
    $products_tag_labels = array(
        'name' => _x('商品标签', 'taxonomy general name'),
         'singular_name' => _x('商品标签', 'taxonomy singular name'),
         'search_items' => __('搜索商品标签', 'tinection'),
         'popular_items' => __('热门商品标签', 'tinection'),
         'all_items' => __('所有商品标签', 'tinection'),
         'parent_item' => null,
         'parent_item_colon' => null,
         'edit_item' => __('编辑商品标签', 'tinection'),
         'update_item' => __('更新商品标签', 'tinection'),
         'add_new_item' => __('添加新商品标签', 'tinection'),
         'new_item_name' => __('新商品标签名称', 'tinection'),
         'separate_items_with_commas' => __('逗号分割不同商品标签', 'tinection'),
         'add_or_remove_items' => __('添加或移除商品标签', 'tinection'),
         'choose_from_most_used' => __('从最常用商品标签中选择', 'tinection'),
         'menu_name' => __('商品标签', 'tinection'),
        );
    
     register_taxonomy('products_tag', 'store', array(
            'hierarchical' => false,
             'labels' => $products_tag_labels,
             'show_ui' => true,
             'update_count_callback' => '_update_post_term_count',
             'query_var' => true,
             'rewrite' => array(
                'slug' => 'store/tag',
                 'with_front' => false,
                ),
            ));
    }
add_action('init', 'create_store_taxonomies', 0);
function custom_store_link($link, $post = 0){
     if ($post -> post_type == 'store'){
         return home_url('store/goods/' . $post -> post_name . '.html');
         }else{
         return $link;
         }
    }
add_filter('post_type_link', 'custom_store_link', 1, 3);
function custom_store_rewrites_init(){
     add_rewrite_rule(
        'store/goods/([一-龥a-zA-Z0-9_-]+)?.html([\s\S]*)?$',
         'index.php?post_type=store&name=$matches[1]',
         'top');
    }
add_action('init', 'custom_store_rewrites_init');
function store_columns($columns){
     $columns['product_ID'] = '商品编号';
     $columns['product_price'] = '价格';
     $columns['product_quantity'] = '数量';
     $columns['product_sales'] = '销量';
     unset($columns['comments']);
     return $columns;
    }
add_filter('manage_edit-store_columns', 'store_columns');
function populate_columns($column){
     if ('product_ID' == $column){
         $product_ID = esc_html(get_the_ID());
         echo $product_ID;
         }
    elseif ('product_price' == $column){
         $product_price = get_post_meta(get_the_ID(), 'product_price', true) ? get_post_meta(get_the_ID(), 'product_price', true) : '0.00';
         $currency = get_post_meta(get_the_ID(), 'pay_currency', true);
         if($currency == 0)$text = '积分';
        else $text = '元';
         $price = $product_price . ' ' . $text;
         echo $price;
         }elseif('product_quantity' == $column){
         $product_quantity = get_post_meta(get_the_ID(), 'product_amount', true) ? (int)get_post_meta(get_the_ID(), 'product_amount', true) : 0;
         echo $product_quantity . ' 件';
         }elseif('product_sales' == $column){
         $product_sales = get_post_meta(get_the_ID(), 'product_sales', true) ? (int)get_post_meta(get_the_ID(), 'product_sales', true) : 0;
         echo $product_sales . ' 件';
         }
    }
add_action('manage_posts_custom_column', 'populate_columns');
function sort_store_columns($columns){
     $columns['product_ID'] = '商品编号';
     $columns['product_price'] = '价格';
     $columns['product_quantity'] = '数量';
     $columns['product_sales'] = '销量';
     return $columns;
    }
add_filter('manage_edit-store_sortable_columns', 'sort_store_columns');
function column_orderby($vars){
     if(!is_admin())
         return $vars;
     if(isset($vars['orderby']) && 'product_price' == $vars['orderby']){
         $vars = array_merge($vars, array('meta_key' => 'product_price', 'orderby' => 'meta_value'));
         }elseif(isset($vars['orderby']) && 'product_quantity' == $vars['orderby']){
         $vars = array_merge($vars, array('meta_key' => 'product_quantity', 'orderby' => 'meta_value'));
         }elseif(isset($vars['orderby']) && 'product_sales' == $vars['orderby']){
         $vars = array_merge($vars, array('meta_key' => 'product_sales', 'orderby' => 'meta_value'));
         }
     return $vars;
    }
add_filter('request', 'column_orderby');
function store_filter_list(){
     $screen = get_current_screen();
     global $wp_query;
     if ($screen -> post_type == 'store'){
         wp_dropdown_categories(array(
                'show_option_all' => '显示所有分类',
                 'taxonomy' => 'products_category',
                 'name' => '商品分类',
                 'id' => 'filter-by-products_category',
                 'orderby' => 'name',
                 'selected' => (isset($wp_query -> query['products_category']) ? $wp_query -> query['products_category'] : ''),
                 'hierarchical' => false,
                 'depth' => 3,
                 'show_count' => false,
                 'hide_empty' => true,
                ));
         }
    }
add_action('restrict_manage_posts', 'store_filter_list');
function perform_filtering($query){
     $qv = & $query -> query_vars;
     if (isset($qv['products_category']) && is_numeric($qv['products_category'])){
         $term = get_term_by('id', $qv['products_category'], 'products_category');
         $qv['products_category'] = $term -> slug;
         }
     return $query;
    }
add_filter('parse_query', 'perform_filtering');
function tin_get_product_price($product_id = 0){
     if($product_id == 0) $price = 0;
     else $price = get_post_meta($product_id, 'product_price', true) ? get_post_meta($product_id, 'product_price', true) : 0;
     return sprintf('%0.2f', $price);
    }
function product_smallest_price($product_id){
     $original_price = tin_get_product_price($product_id);
     $vip_discount = json_decode(get_post_meta($product_id, 'product_vip_discount', true), true);
     $vip_discount = empty($vip_discount)?1:$vip_discount;
     $vip_discount1 = isset($vip_discount['product_vip1_discount'])?$vip_discount['product_vip1_discount']:1;
     $vip_discount2 = isset($vip_discount['product_vip2_discount'])?$vip_discount['product_vip2_discount']:1;
     $vip_discount3 = isset($vip_discount['product_vip3_discount'])?$vip_discount['product_vip3_discount']:1;
     if(is_user_logged_in()){
        $vip = getUserMemberType();
        $discount_type = $vip?'vip_discount' . $vip:'vip_discount';
        $vip_discount = $vip?$$discount_type:1;
    }else{
        $vip_discount = 1;
    }
     $promote_discount = get_post_meta($product_id, 'product_promote_discount', true);
     if($vip_discount < 1 && $vip_discount >= 0){
        $vip_price = $original_price * $vip_discount;
    }else{
        $vip_price = $original_price;
    }
     $discount_begin_date = get_post_meta($product_id, 'product_discount_begin_date', true) ? get_post_meta($product_id, 'product_discount_begin_date', true) : 0;
     $discount_period = get_post_meta($product_id, 'product_discount_period', true) ? get_post_meta($product_id, 'product_discount_period', true) : 0;
     if($discount_begin_date == 0 || $discount_period == 0){
         $promote_price = $original_price;
         }elseif(strtotime($discount_begin_date) <= time() && strtotime('+' . $discount_period . ' days', strtotime($discount_begin_date)) >= time()){
         $promote_price = $promote_discount * $original_price;
         }else{
         $promote_price = $original_price;
         }
     $vip_discount_arr = array($vip_discount1, $vip_discount2, $vip_discount3);
     sort($vip_discount_arr);
     $vip_price_show = ($vip_discount_arr[0] < 1 && $vip_discount_arr[0] < $promote_discount)? 1:0;
     $promote_price_show = ($promote_price < $original_price)? 1:0;
     $price_arr = array($original_price, $vip_price, $promote_price);
     sort($price_arr);
     $last_price = sprintf('%0.2f', $price_arr[0]);
     $price = array($original_price, $vip_price, $promote_price, $vip_price_show, $promote_price_show, $last_price);
     return $price;
    }
function get_user_autofill_info(){
     $autofill = array();
     if(is_user_logged_in()){
         $current_user = wp_get_current_user();
         $autofill['user_name'] = $current_user -> display_name;
         $autofill['user_email'] = $current_user -> user_email;
         $id = $current_user -> ID;
         global $wpdb;
         $prefix = $wpdb -> prefix;
         $history_orders = $wpdb -> get_Results("select * from " . $prefix . "tin_orders where user_id=" . $id . " order by id DESC", 'ARRAY_A');
         if($history_orders){
             $order = $history_orders[0];
             return $order;
             }else{
             return $autofill;
             }
         }else{
         return $autofill;
         }
    }
function get_user_order_records($product_id = 0, $user_id = 0, $success_orders = 0)
{
     $record = array();
     if(is_user_logged_in()){
         $current_user = wp_get_current_user();
         $autofill['user_name'] = $current_user -> display_name;
         if($user_id == 0){
            $id = $current_user -> ID;
        }else{
            $id = $user_id;
        }
         global $wpdb;
         $prefix = $wpdb -> prefix;
         if($product_id == 0):
             if($success_orders == 0){
                $orders = $wpdb -> get_Results("select * from " . $prefix . "tin_orders where user_id=" . $id, 'ARRAY_A');
            }else{
                $orders = $wpdb -> get_Results("select * from " . $prefix . "tin_orders where order_status=4 and user_id=" . $id, 'ARRAY_A');
            }
            else:
                 if($success_orders == 0){
                    $orders = $wpdb -> get_Results("select * from " . $prefix . "tin_orders where user_id=" . $id . " and product_id=" . $product_id, 'ARRAY_A');
                }else{
                    $orders = $wpdb -> get_Results("select * from " . $prefix . "tin_orders where order_status=4 and user_id=" . $id . " and product_id=" . $product_id, 'ARRAY_A');
                }
                 endif;
                 $record = $orders;
                 }
             return $record;
}
function get_the_order($order_id){
 global $wpdb;
 $prefix = $wpdb -> prefix;
 $order = $wpdb -> get_row("select * from " . $prefix . "tin_orders where order_id=" . $order_id);
 return $order;
}
function output_order_status($code){
 switch($code){
 case 1:
     $status_text = '等待买家付款';
     break;
 case 2:
     $status_text = '已付款，等待卖家发货';
     break;
 case 3:
     $status_text = '已发货，等待买家确认';
     break;
 case 4:
     $status_text = '交易成功';
     break;
 case 9:
     $status_text = '交易关闭';
     break;
 default:
     $status_text = '订单建立成功';
     }
 return $status_text;
}
function generate_order_num(){
 $orderNum = mt_rand(10, 25) . time() . mt_rand(1000, 9999);
 return $orderNum;
}
function update_promote_code_total_price($code = '', $total_price = 0, $ajax = 1){
 if(isset($_POST['promote_code']) && isset($_POST['order_total_price']) && $ajax = 1){
    $code = $_POST['promote_code'];
    $total_price = $_POST['order_total_price'];
}
 $success = 0;
 $new_total_price = $total_price;
 global $wpdb;
 $prefix = $wpdb -> prefix;
 $table = $prefix . 'tin_promotes';
 $row = $wpdb -> get_row("select * from " . $table . " where promote_code='" . $code . "'", 'ARRAY_A');
 if(!$row){
     $msg = '优惠码不存在';
     }elseif($row['promote_status'] != 1 || strtotime($row['expire_date']) <= time()){
     $msg = '优惠码已被使用或过期';
     }else{
     if($row['discount_value'] < 1){
         $new_total_price = sprintf('%0.2f', $total_price * $row['discount_value']);
         if($row['promote_type'] == 'once' && $ajax != 1)$wpdb -> query("UPDATE $table SET promote_status=0 WHERE promote_code='$code'");
         $success = 1;
         $msg = '已成功使用优惠码';
         }else{
         $msg = '优惠码无效';
         }
     }
 if($ajax == 1){
     $return = array('msg' => $msg, 'success' => $success, 'total_price' => $new_total_price);
     echo json_encode($return);
     exit;
     }else{
     return $new_total_price;
     }
}
add_action('wp_ajax_nopriv_use_promote_code', 'update_promote_code_total_price');
add_action('wp_ajax_use_promote_code', 'update_promote_code_total_price');
function insert_order($product_id, $product_name, $order_price = '', $order_quantity, $order_total_price, $order_status = 0, $order_note = '', $user_id, $user_name, $user_email = '', $user_address = '', $user_zip = '', $user_phone = '', $user_cellphone = '', $user_message = ''){
 date_default_timezone_set ('Asia/Shanghai');
 global $wpdb;
 $prefix = $wpdb -> prefix;
 $table = $prefix . 'tin_orders';
 $order_id = generate_order_num();
 $order_time = date("Y-m-d H:i:s");
 if(empty($order_price)){
    $order_price_arr = product_smallest_price($product_id);
    $order_price = $order_price_arr[5];
}
 if($product_id > 0){
    $order_currency = (get_post_meta($product_id, 'pay_currency', true) != 1)?'credit':'cash';
}else{
    $order_currency = 'cash';
}
 if($wpdb -> query("INSERT INTO $table (order_id,product_id,product_name,order_time,order_price,order_currency,order_quantity,order_total_price,order_status,order_note,user_id,user_name,user_email,user_address,user_zip,user_phone,user_cellphone,user_message) VALUES ('$order_id','$product_id','$product_name','$order_time','$order_price','$order_currency','$order_quantity','$order_total_price','$order_status','$order_note','$user_id','$user_name','$user_email','$user_address','$user_zip','$user_phone','$user_cellphone','$user_message')")) return $order_id;
 return 0;
}
function create_the_order(){
 $redirect = 0;
 $success = 0;
 $msg = '';
 $order_note = '';
 $order_id = 0;
 if (!wp_verify_nonce(trim($_POST['wp_nonce']), 'order-nonce')){
     $msg = 'NonceIsInvalid';
     }else{
     $price = product_smallest_price($_POST['product_id']);
     // 获取折扣后总价
    $cost = $price[5] * $_POST['order_quantity'];
     // 获取使用优惠码后总价
    $promote_support = get_post_meta($_POST['product_id'], 'product_promote_code_support', true);
     if($promote_support == 1 && !empty($_POST['promote_code'])){
         $cost_promoted = update_promote_code_total_price($_POST['promote_code'], $cost, 0);
         $order_note = json_encode(array('promote_code' => $_POST['promote_code']));
         }else{
         $cost_promoted = $cost;
         }
     $currency = get_post_meta($_POST['product_id'], 'pay_currency', true);
     $current_user = wp_get_current_user();
     $uid = $current_user -> ID;
     if($currency == 0){
         // 使用积分直接支付
        // 获取用户当前积分并判断是否足够消费
        $credit = (int)get_user_meta($uid, 'tin_credit', true);
         if($credit < $cost){
             // 积分不足
            $msg = '积分不足，立即<a href="' . tin_get_user_url('credit') . '" target="_blank">充值积分</a>';
             }else{
             // 插入数据库记录
            $insert = insert_order($_POST['product_id'], $_POST['order_name'], $price[5], $_POST['order_quantity'], $cost, 4, $order_note, $uid, $_POST['receive_name'], $_POST['receive_email'], $_POST['receive_address'], $_POST['receive_zip'], $_POST['receive_phone'], $_POST['receive_mobile'], $_POST['order_msg']);
             if($insert):
                 // 扣除积分//发送站内信
                update_tin_credit($uid , $cost , 'cut' , 'tin_credit' , '下载资源消费' . $cost . '积分');
             // 更新已消费积分
            if(get_user_meta($uid, 'tin_credit_void', true)){
                 $void = get_user_meta($uid, 'tin_credit_void', true);
                 $void = $void + $cost;
                 update_user_meta($uid, 'tin_credit_void', $void);
                 }else{
                 add_user_meta($uid, 'tin_credit_void', $cost, true);
                 }
             // 给资源发布者添加积分并更新积分消息记录
            $author = get_post_field('post_author', $_POST['product_id']);
             update_tin_credit($author , $cost , 'add' , 'tin_credit' , sprintf(__('你发布收费商品资源《%1$s》被其他用户购买，获得售价%2$s积分', 'tinection') , get_post_field('post_title', $_POST['product_id']), $cost)); //出售获得积分
             // 更新资源购买次数与剩余数量
            update_success_order_product($_POST['product_id'], $_POST['order_quantity']);
             // 发送邮件
            $to = $_POST['receive_email'];
             $dl_links = get_post_meta($_POST['product_id'], 'product_download_links', true);
             $pay_content = get_post_meta($_POST['product_id'], 'product_pay_content', true);
             // 如果包含付费可见下载链接则附加链接内容至邮件
            if(!empty($dl_links) || !empty($pay_content)){
                 $title = '你在' . get_bloginfo('name') . '购买的内容';
                 $content = '<p>你在' . get_bloginfo('name') . '使用积分购买了以下内容:</p>';
                 $content .= deal_pay_dl_content($dl_links);
                 $content .= '<p style="margin-top:10px;">' . $pay_content . '</p><p>感谢你的来访与支持，祝生活愉快！</p>';
                 }else{
                 $title = '感谢你在' . get_bloginfo('name') . '使用积分付费购买资源';
                 $content = '<p>你在' . get_bloginfo('name') . '使用积分付费购买资源' . $_POST['order_name'] . '</p><p>支付已成功,扣除了你' . $cost . '积分。</p><p>感谢你的来访与支持，祝生活愉快！</p>';
                 }
             $type = '积分商城';
             tin_basic_mail('', $to, $title, $content, $type);
             $msg = '购买成功，已扣除' . $cost . '积分';
             $success = 1;
             else:
                 $msg = '创建订单失败，请重新再试';
             endif;
             }
         }else{
         // 现金支付方式，首先插入数据库订单记录
        $insert = insert_order($_POST['product_id'], $_POST['order_name'], $price[5], $_POST['order_quantity'], $cost_promoted, 1, $order_note, $uid, $_POST['receive_name'], $_POST['receive_email'], $_POST['receive_address'], $_POST['receive_zip'], $_POST['receive_phone'], $_POST['receive_mobile'], $_POST['order_msg']);
         if($insert){
             $redirect = 1;
             $success = 1;
             $order_id = $insert;
             if(!empty($_POST['receive_email'])){
                store_email_template($order_id, '', $_POST['receive_email']);
            }
             }else{
             $msg = '创建订单失败，请重新再试';
             }
         }
     }
 $return = array('success' => $success, 'msg' => $msg, 'redirect' => $redirect, 'order_id' => $order_id);
 echo json_encode($return);
 exit;
}
add_action('wp_ajax_nopriv_create_order', 'create_the_order');
add_action('wp_ajax_create_order', 'create_the_order');
function deal_pay_dl_content($dl_links){
 $content = '';
 if(!empty($dl_links)):
     $arr_links = explode(PHP_EOL, $dl_links);
 foreach($arr_links as $arr_link){
     $arr_link = explode('|', $arr_link);
     $arr_link[0] = isset($arr_link[0]) ? $arr_link[0]:'';
     $arr_link[1] = isset($arr_link[1]) ? $arr_link[1]:'';
     $arr_link[2] = isset($arr_link[2]) ? $arr_link[2]:'';
     $content .= '<li><p>' . $arr_link[0] . '</p><p>下载链接：<a href="' . $arr_link[1] . '" title="' . $arr_link[0] . '" target="_blank">' . $arr_link[1] . '</a>下载密码：' . $arr_link[2] . '</p></li>';
     }
 endif;
 return $content;
}
function store_pay_content_show($content){
 $hidden_content = '';
 $content = do_shortcode($content);
 $content = wpautop($content);
 $content = lightbox_gall_replace($content);
 if(is_single()){
     $price = product_smallest_price(get_the_ID()); //get_post_meta(get_the_ID(),'product_price',true);
     $dl_links = get_post_meta(get_the_ID(), 'product_download_links', true);
     $pay_content = get_post_meta(get_the_ID(), 'product_pay_content', true);
     $hidden_content = deal_pay_dl_content($dl_links);
     $hidden_content .= $pay_content;
     if($price[5] == 0 || count(get_user_order_records(get_the_ID(), 0, 1)) > 0):
         $see_content = empty($hidden_content)?$content:$content . '<div class="label-title"><span>付费内容</span><p>' . $hidden_content . '</p></div>';
     else:
         $see_content = empty($hidden_content)?$content:$content . '<div class="label-title"><span>付费内容</span><p>你只有购买支付后才能查看该内容！</p></div>';
     endif;
     }else{
     $see_content = $content;
     }
 return $see_content;
}
function update_success_order_product($product_id, $amount_minus = 1){
 if($product_id > 0){
     $amount = (int)get_post_meta($product_id, 'product_amount', true);
     $amount = $amount - $amount_minus;
     if($amount < 0)$amount = 0;
     update_post_meta($product_id, 'product_amount', $amount);
     $sales = get_post_meta($product_id, 'product_sales', true) ? (int)get_post_meta($product_id, 'product_sales', true) : 0;
     $sales = $sales + $amount_minus;
     update_post_meta($product_id, 'product_sales', $sales);
     }
}
function store_email_template_wrap($user_name = '', $content){
 $blogname = get_bloginfo('name');
 $bloghome = get_bloginfo('url');
 $logo = ot_get_option('logo-img');
 $html = '<html><head><meta http-equiv="content-type" content="text/html; charset=UTF-8" /><meta name="viewport" content="target-densitydpi=device-dpi, width=800, initial-scale=1, maximum-scale=1, user-scalable=1"><style>a:hover{text-decoration:underline !important;}</style></head><body><div style="width:800px;margin: 0 auto;"><table width="800" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FBF8F1" style="border-radius:5px; overflow:hidden; border-top:4px solid #00c3b6; border-right:1px solid #dbd1ce; border-bottom:1px solid #dbd1ce; border-left:1px solid #dbd1ce;font-family:微软雅黑;"><tbody><tr><td><table width="800" border="0" align="center" cellpadding="0" cellspacing="0" height="48"><tbody><tr><td width="74" height="35" border="0" align="center" valign="middle" style="padding-left:20px;"><a href="' . $bloghome . '" target="_blank" style="text-decoration: none;">';
 if(!empty($logo)){
    $html .= '<img style="vertical-align:middle;background-color:#838fa8" src="' . $logo . '" height="35" border="0">';
}else{
    $html .= '<span style="vertical-align:middle;font-size:20px;line-height:32px;">' . $blogname . '</span>';
}
 $html .= '</a></td><td width="703" height="48" colspan="2" align="right" valign="middle" style="color:#333; padding-right:20px;font-size:14px;font-family:微软雅黑"><a style="padding:0 10px;text-decoration:none;" target="_blank" href="' . $bloghome . '">首页</a><a style="padding:0 10px;text-decoration:none;" target="_blank" href="' . $bloghome . '/articles">文章</a><a style="padding:0 10px;text-decoration:none;" target="_blank" href="' . $bloghome . '/store">商城</a></td></tr></tbody></table></td></tr><tr><td><div style="padding:10px 20px;font-size:14px;color:#333333;border-top:1px solid #dbd1ce;font-family:微软雅黑">';
 if(!empty($user_name)){
    $html .= '<p><strong>亲爱的会员' . $user_name . ' 您好：</strong></p><p>感谢您在' . $blogname . '( <a target="_blank" href="' . $bloghome . '">' . $bloghome . '</a>)购物!<br></p>';
}else{
    $html .= '';
}
 $html .= $content;
 $html .= '<p style="padding:10px 0;margin-top:30px;margin-bottom:0;color:#a8979a;font-size:12px;border-top:1px dashed #dbd1ce;">此为系统邮件请勿回复<span style="float:right">&copy;&nbsp;' . date('Y') . '&nbsp;' . $blogname . '</span></p></div></td></tr></tbody></table></div></body></html>';
 return $html;
}
function store_email_template($order_id, $from = '', $to, $title = ''){
 $blogname = get_bloginfo('name');
 $order = get_the_order($order_id);
 $order_url = tin_get_user_url('orders', $order -> user_id);
 $admin_email = get_bloginfo ('admin_email');
 $user_name = $order -> user_name;
 $user_ucenter_url = get_author_posts_url($order -> user_id);
 $product_name = $order -> product_name;
 $order_status_text = output_order_status($order -> order_status);
 $order_total_price = $order -> order_total_price;
 $order_time = $order -> order_time;
 $content = '<p>以下是您的订单最新信息，您可进入“<a target="_blank" href="' . $order_url . '">订单详情</a>”页面随时关注订单状态，如有任何疑问，请及时联系我们（Email:<a href="mailto:' . $admin_email . '" target="_blank">' . $admin_email . '</a>）。</p><div style="background-color:#fefcc9; padding:10px 15px; border:1px solid #f7dfa4; font-size: 12px;line-height:160%;">商品名：' . $product_name . '<br>订单号：' . $order_id . '<br>总金额：' . $order_total_price . '<br>下单时间：' . $order_time . '<br>交易状态：<strong>' . $order_status_text . '</strong></div>';
 $html = store_email_template_wrap($user_name, $content);
 if(empty($from)){
    $wp_email = 'no-reply@' . preg_replace('#^www\.#', '', strtolower($_SERVER['SERVER_NAME']));
}else{
    $wp_email = $from;
}
 if(empty($title)){
    $title = $blogname . '商城提醒';
}
 $fr = "From: \"" . $blogname . "\" <$wp_email>";
 $headers = "$fr\nContent-Type: text/html; charset=" . get_option('blog_charset') . "\n";
 wp_mail($to, $title, $html, $headers);
 // 如果交易成功通知管理员
if($order -> order_status == 4){
     $content_admin = '<p>你的站点有新完成的支付宝交易订单,以下是订单信息:<div style="background-color:#fefcc9; padding:10px 15px; border:1px solid #f7dfa4; font-size: 12px;line-height:160%;">买家名：<a href="' . $user_ucenter_url . '" title="用户个人中心" target="_blank">' . $user_name . '</a><br>商品名：' . $product_name . '<br>订单号：' . $order_id . '<br>总金额：' . $order_total_price . '<br>下单时间：' . $order_time . '<br>交易状态：<strong>' . $order_status_text . '</strong></div>';
     $html_admin = store_email_template_wrap('', $content_admin);
     wp_mail($admin_email, $title, $html_admin, $headers);
     }
}
function send_goods_by_order($order_id, $from = '', $to, $title = ''){
 $order = get_the_order($order_id);
 $product_id = $order -> product_id;
 $blogname = get_bloginfo('name');
 $user_id = $order -> user_id;
 $user_name = $order -> user_name;
 if($product_id > 0){
     $dl_links = get_post_meta($product_id, 'product_download_links', true);
     $pay_content = get_post_meta($product_id, 'product_pay_content', true);
     // 如果包含付费可见下载链接则附加链接内容至邮件
    if(!empty($dl_links) || !empty($pay_content)){
         $content = '<p>你在' . $blogname . '商城付费购买了以下内容:</p>';
         $content .= deal_pay_dl_content($dl_links);
         $content .= '<p style="margin-top:10px;">' . $pay_content . '</p><p>感谢你的支持，祝生活愉快！</p>';
         }else{
         $content = '<p>你在' . $blogname . '商城付费购买了资源' . $order -> product_name . '已成功支付' . $order -> order_total_price . '元。</p><p>感谢你的来访与支持，祝生活愉快！</p>';
         }
     $html = store_email_template_wrap($user_name, $content);
     if(empty($from)){
        $wp_email = 'no-reply@' . preg_replace('#^www\.#', '', strtolower($_SERVER['SERVER_NAME']));
    }else{
        $wp_email = $from;
    }
     if(empty($title)){
        $title = $blogname . '商城提醒';
    }
     $fr = "From: \"" . $blogname . "\" <$wp_email>";
     $headers = "$fr\nContent-Type: text/html; charset=" . get_option('blog_charset') . "\n";
     wp_mail($to, $title, $html, $headers);
     }elseif($product_id == -1){
     elevate_user_vip(1, $user_id, $user_name, $from, $to);
     }elseif($product_id == -2){
     elevate_user_vip(2, $user_id, $user_name, $from, $to);
     }elseif($product_id == -3){
     elevate_user_vip(3, $user_id, $user_name, $from, $to);
     }elseif($product_id == -4){
     add_user_credits_by_order($order -> order_total_price, $user_id, $user_name, $from, $to);
     }else{
    
     }
}
function add_tin_promotecode($p_code, $p_type, $p_discount, $p_expire_date){
 global $wpdb;
 $prefix = $wpdb -> prefix;
 $table = $prefix . 'tin_promotes';
 $row = $wpdb -> query("INSERT INTO $table (promote_code,promote_type,discount_value,expire_date) VALUES ('$p_code','$p_type','$p_discount','$p_expire_date')");
}
function delete_tin_promotecode($p_id){
 global $wpdb;
 $prefix = $wpdb -> prefix;
 $table = $prefix . 'tin_promotes';
 $row = $wpdb -> query("DELETE FROM $table WHERE id='" . $p_id . "'");
}
function output_tin_promotecode(){
 global $wpdb;
 $prefix = $wpdb -> prefix;
 $table = $prefix . 'tin_promotes';
 $promotecodes = $wpdb -> get_Results("SELECT * FROM $table ORDER BY id DESC", 'ARRAY_A');
 return $promotecodes;
}
function create_credit_recharge_order(){
 $success = 0;
 $order_id = '';
 $msg = '';
 $credits = ((int)$_POST['creditrechargeNum']) * 100;
 $order_name = '充值' . $credits . '积分';
 $product_id = $_POST['product_id'];
 if(!is_user_logged_in()){
    $msg = '请先登录';
}else{
     $user_info = wp_get_current_user();
    $uid = $user_info -> ID;
    $user_name = $user_info -> display_name;
    $user_email = $user_info -> user_email;
     if($product_id != -4){
        $msg = '系统发生错误，请刷新再试';
    }else{
         $order_price = $credits / (int)ot_get_option('tin_cash_credit_ratio', 50);
         $order_price = sprintf('%0.2f', $order_price);
         $insert = insert_order($product_id, $order_name, $order_price, 1, $order_price, 1, '', $uid, $user_name, $user_email, '', '', '', '', '');
         if($insert){
             $success = 1;
             $order_id = $insert;
             if(!empty($user_email)){
                store_email_template($order_id, '', $user_email);
            }
             }else{
             $msg = '创建订单失败，请重新再试';
             }
         }
     }
 $return = array('success' => $success, 'msg' => $msg, 'order_id' => $order_id);
 echo json_encode($return);
 exit;
}
add_action('wp_ajax_nopriv_create_credit_recharge_order', 'create_credit_recharge_order');
add_action('wp_ajax_create_credit_recharge_order', 'create_credit_recharge_order');
function add_user_credits_by_order($money, $user_id, $user_name, $from, $to){
 date_default_timezone_set ('Asia/Shanghai');
 $ratio = ot_get_option('tin_cash_credit_ratio', 50);
 $credits = (int)($ratio * $money);
 $admin_email = get_bloginfo ('admin_email');
 $blogname = get_bloginfo('name');
 // add credits and msg
update_tin_credit($user_id , $credits , 'add' , 'tin_credit' , sprintf(__('充值积分%1$s，花费%2$s元', 'tinection') , $credits, $money));
 // email
$content = '<p>您已成功充值了' . $credits . '积分，当前积分为：' . $new_credits . '，如有任何疑问，请及时联系我们（Email:<a href="mailto:' . $admin_email . '" target="_blank">' . $admin_email . '</a>）。</p>';
 $html = store_email_template_wrap($user_name, $content);
 if(empty($from)){
    $wp_email = 'no-reply@' . preg_replace('#^www\.#', '', strtolower($_SERVER['SERVER_NAME']));
}else{
    $wp_email = $from;
}
 $title = '会员状态变更提醒';
 $fr = "From: \"" . $blogname . "\" <$wp_email>";
 $headers = "$fr\nContent-Type: text/html; charset=" . get_option('blog_charset') . "\n";
 wp_mail($to, $title, $html, $headers);
}
function get_tin_orders($uid = 0 , $count = 0, $where = '', $limit = 0, $offset = 0){
 $uid = intval($uid);
 $where_prefix = '';
 if($uid != 0){
     $where_prefix = "WHERE user_id='" . $uid . "'";
     if($where) $where = $where_prefix . " AND ($where)";
     }else{
     if($where) $where = "WHERE ($where)";
     }
 global $wpdb;
 $table_name = $wpdb -> prefix . 'tin_orders';
 if($count){
     $check = $wpdb -> get_var("SELECT COUNT(*) FROM $table_name $where");
     }else{
     $check = $wpdb -> get_results("SELECT id,order_id,product_name,order_time,order_price,order_quantity,order_total_price,order_status,user_name FROM $table_name $where ORDER BY id DESC LIMIT $offset,$limit");
     }
 if($check) return $check;
 return 0;
}
function close_expire_order(){
 $success = 0;
 $msg = '';
 $id = $_POST['id'];
 if(empty($id) || !current_user_can('edit_users')){
    $msg = '系统出错或你无权限执行该动作';
}else{
     global $wpdb;
     $prefix = $wpdb -> prefix;
     $table = $prefix . 'tin_orders';
     $id = intval($id);
     $check = $wpdb -> get_row("select * from " . $table . " where id=" . $id);
     if($check){
         $wpdb -> query("UPDATE $table SET order_status=9, order_success_time=NOW() WHERE id='$id'");
         if(!empty($check -> user_email))store_email_template($check -> order_id, '', $check -> user_email);
         $success = 1;
         }
     }
 $return = array('success' => $success, 'msg' => $msg);
 echo json_encode($return);
 exit;
}
add_action('wp_ajax_closeorder', 'close_expire_order');
add_action('wp_ajax_nopriv_closeorder', 'close_expire_order');
        ?>