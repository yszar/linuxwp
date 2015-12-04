<?php
class al_option {
	public $anylinkOptions;
	public function __construct() {
		add_action( 'admin_menu', array( &$this, 'addMenu' ) );
		add_action( 'admin_init', array( &$this, 'alAdminInit' ) );
		add_action( 'update_option_anylink_options', array( $this, 'flushRules' ) );
		$this -> anylinkOptions = get_option( 'anylink_options' );
	}
	public function addMenu() {
		$al_option_page = add_submenu_page( 'options-general.php', __( 'anylink Settings', 'anylink' ), __( 'anylink Settings', 'anylink' ), 'manage_options', 'anyLinkSetting', array( &$this, 'anyLinkSettingPage' ) );
		add_action( 'admin_print_scripts-' . $al_option_page, array( &$this, 'anylinkAdminScripts' ) );
		add_action( 'admin_print_styles-' . $al_option_page, array( &$this, 'anylinkAdminScripts' ) );
	}
	//output scripts and styles
	public function anylinkAdminScripts() {
		wp_enqueue_script( 'anylink_script' );
		wp_enqueue_style( 'anylink_style' );
	}
	public function anyLinkSettingPage() {
		if( ! current_user_can( 'manage_options' ) )
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		include_once( ANYLNK_PATH . '/al_setting.php' );
	}
	public function alAdminInit() {
		//register a javascript and css files to output later
		wp_register_script( 'anylink_script', plugins_url( '/images/al_script.js', dirname( __FILE__ ) ) );
		wp_register_style( 'anylink_style', plugins_url( '/images/al_style.css', dirname( __FILE__ ) ) );
		register_setting( 'anylink_options_group', 'anylink_options', array( &$this, 'alCatValidate' ) );
		add_settings_section( 'al_general_settings', __( 'General Settings', 'anylink'), array( &$this, 'alGeneralDisp' ), 'anyLinkSetting' );
		add_settings_field( 'al_redirect_cat', __( 'Redirect catalog', 'anylink' ), array( &$this, 'dispRedirectCat' ), 'anyLinkSetting', 'al_general_settings' );
		add_settings_field( 'al_redirect_type', __( 'Redirect HTTP code', 'anylink'), array( &$this, 'dispRedirctType' ), 'anyLinkSetting', 'al_general_settings' );
		add_settings_field( 'al_slug_num', __( 'Length of slugs', 'anylink'), array( &$this, 'dispSlugNum' ), 'anyLinkSetting', 'al_general_settings' );
		add_settings_field( 'al_slug_char', __( 'Component of slug', 'anylink' ), array( &$this, 'dispSlugChar' ), 'anyLinkSetting', 'al_general_settings' );
		add_settings_field( 'al_form_identify', '', array( &$this, 'hiddenFormIdentify' ), 'anyLinkSetting', 'al_general_settings' );
		add_settings_field( 'al_post_types', __( 'Post Types', 'anylink' ), array( $this, 'dispPostTypes' ), 'anyLinkSetting', 'al_general_settings' );
        add_settings_field( 'al_url_properties', __( 'Link properties', 'anylink' ), array( $this, 'dispLinkPro'), 'anyLinkSetting', 'al_general_settings' );
        add_settings_field(  'filter_comment_toggle', __( 'Turn on comment filter.' ), array( $this, 'display_comment_toggle'), 'anyLinkSetting', 'al_general_settings' );
		load_plugin_textdomain( 'anylink', false, ANYLNK_PATH . '/i18n/' );
	}
	public function alGeneralDisp() {
		echo "";
	}
	public function dispRedirectCat() {
		$cat = $this -> anylinkOptions['redirectCat'];
		echo site_url() . "/<input id='anylink_options' name='anylink_options[redirectCat]' value='{$cat}' class='small-text' type='text' size='8' />/ab12<br />" . __( 'Make sure it starts with a letter, ONLY contains letters, numbers, underscore and dash. The max. length is 12', 'anylink' );
	}
	public function dispRedirctType() {
		$type = ( int )$this -> anylinkOptions['redirectType'];
		//determine which radio button should be checked
		$checked301 = ' ';
		$checked307 = ' ';
		$checked200 = ' ';
		switch( $type ) {
			case 301:
				$checked301 = 'checked="checked"';
				break;
			case 307:
				$checked307 = 'checked="checked"';
				break;
			case 200;
				$checked200 = 'checked="checked"';
				break;
		}
		$redirectType  = "<input type='radio' id='al_redirect_type_301' name='anylink_options[redirectType]' value='301' $checked301 />";
		$redirectType .= "<label for='al_redirect_type_301'>" . __( '301 Moved Permanently', 'anylink' ) . "</label><br />";
		$redirectType .= "<input type='radio' id='al_redirect_type_307' name='anylink_options[redirectType]' value='307' $checked307 />";
		$redirectType .= "<label for='al_redirect_type_307'>" . __( '307 Temporary Redirect', 'anylink' ) . "</label><br />";
		$redirectType .= "<input type='radio' id='al_redirect_type_200' name='anylink_options[redirectType]' value='200' $checked200 />";
		$redirectType .= "<label for='al_redirect_type_200'>" . __( 'Redirect using Javascript on a single page', 'anylink' ) . "</label><br />";		
		echo $redirectType;
	}
	public function dispSlugNum() {
		$num = $this -> anylinkOptions['slugNum'];
		echo "<input type='text' id='slugNum' name='anylink_options[slugNum]' value='{$num}' class='small-text' size='4' maxlength='2' /><br />" . __( 'No less than 4 and no more than 12 characters', 'anylink' );
	}
	public function dispSlugChar() {
		$chars = $this -> anylinkOptions['slugChar'];
		$htmlChar  = "<input type='radio' id='slugCharNum' name='anylink_options[slugChar]' value='0' ";
		$htmlChar .= $chars == 0 ? 'checked' : '';
		$htmlChar .= " /><label for='slugCharNum'>" . __( 'Pure digits', 'anylink' ) . "</label><br />";
		$htmlChar .= "<input type='radio' id='slugCharChar' name='anylink_options[slugChar]' value='1' ";
		$htmlChar .= $chars == 1 ? 'checked' : '';
		$htmlChar .= " /><label for='slugCharChar'>" . __( 'Pure alphabets', 'anylink' ) . "</label><br />";
		$htmlChar .= "<input type='radio' id='slugCharNumchar' name='anylink_options[slugChar]' value='2' ";
		$htmlChar .= $chars == 2 ? 'checked' : '';
		$htmlChar .= " /><label for='slugCharNumchar'>" . __( 'Digits and alphabets', 'anylink') . "</label>";
		$htmlChar .= "<br /><b>" . __( 'Recommended setting is 4 digits and alphabets. If using PURE DIGITS please set the length no less than 6.', 'anylink' ) . "</b>";
		echo $htmlChar;
	}
	/**
	 * Display all post types
	 */
	public function dispPostTypes() {
		$types = $this -> anylinkOptions['postType'];
		$args = array( 
				'public' => true,
			);
		$output = 'names';
		$post_types = get_post_types( $args, $output );
		$html = '';
		foreach( $post_types as $post_type ) {
			$checked = '';
			if( ! empty( $types ) ){
				if( ( is_string( $types ) && $post_type == $types ) || ( is_array( $types ) && array_search( $post_type, $types ) !== false ) )
					$checked = "checked='checked'";
			}
			$html .= "<input type='checkbox' id='{$post_type}' name='anylink_options[postType][]' value='{$post_type}' {$checked} /><label for='{$post_type}'> " . $post_type . "</label><br />";
		}
		$html .= "<b>" . __( 'Select which type(s) of post you want to covert. Even though you select none of these, this plug-in is still working. Once you changed these options, you needn\'t regenerate slugs at all.', 'anylink' ) . "</b>";
		echo $html;
	}
    public function dispLinkPro() {
        if( ! isset( $this -> anylinkOptions['rel'] ) )
            $rel = '';
        else
            $rel = $this -> anylinkOptions['rel'];
        $html  = "rel=<input type='text' id='anylink_rel' name='anylink_options[rel]' value='{$rel}' class='regular-text' size='20' /><br />";
        $html .= __( "Set the property 'rel' of URLs. If you want to use the default property, please leave it blank.",'anylink' );
        $html .= "<br />" . __( "Use single blank character to seperate its values.", 'anylink' );
        echo $html;
    }
    public function display_comment_toggle() {
        if( !isset( $this -> anylinkOptions['filter-comment'] ) )
            $filter_comment = 1;
        else
            $filter_comment = $this -> anylinkOptions['filter-comment'];
        $html  = __( "Allow anylink to filter external url in comment." );
        $html .= "<br /><input type='radio' id='filter-comment-n' name='anylink_options[filter-comment]' value= '0'";
        $html .= $filter_comment == 0 ? ' checked' : '';
        $html .= "/><label for='filter-comment-n'>" . __( "Please leave comments unfiltered." ) . "</label><br />";
        $html .= "<input type='radio' id='filter-comment-y' name='anylink_options[filter-comment]' value= '1'";
        $html .= $filter_comment == 1 ? ' checked' : '';
        $html .= "/><label for='filter-comment-y'>" . __( "Filter link(s) in comment" ) . "</label>";
        echo $html;
    }
	/*  I should put some validations here
	 *  a filter named "sanitize_option_$optionname" is applied when you can update_option
	 *  so we need an identify key to determine which form the data come form
	 */ 
	public function alCatValidate( $input ) {
		if( ! array_key_exists( 'identify', $input ) )
			return $input;
		$oldOptions = $this -> anylinkOptions;
		//$input = array_map( "trim", $input );
		if( preg_match( '/^[a-z][a-z0-9_-]{0,11}/', $input['redirectCat'] ) )
			$oldOptions['redirectCat'] = $input['redirectCat'];
		if( $input['redirectType'] == 301 || $input['redirectType'] == 307 || $input['redirectType'] == 200 )
			$oldOptions['redirectType'] = $input['redirectType'];
		if( is_int( ( int )$input['slugNum'] ) && $input['slugNum'] < 13 && $input['slugNum'] > 3 )
			$oldOptions['slugNum'] = $input['slugNum'];
		$oldOptions['slugChar'] = $input['slugChar'];
		$oldOptions['postType'] = $input['postType'];
        $oldOptions['filter-comment'] = $input['filter-comment'];
        $oldOptions['rel'] = sanitize_text_field( $input['rel'] );
		return $oldOptions;
	}
	//out put a hidden field to identify the form
	public function hiddenFormIdentify() {
		$hiddenHtml = "<input type='hidden' name='anylink_options[identify]' id='al_identify' value='anylink' />";
		echo $hiddenHtml;
	}
	//flush the rewrite rules
	public function flushRules() {
		$alOptions = get_option( 'anylink_options' );
		if( $alOptions['redirectCat'] != $alOptions['oldCat'] || $alOptions['redirectType'] != $alOptions['oldRedirectType'] ) {
			$cat = $alOptions['redirectCat'];
			$type = $alOptions['redirectType'];
			global $wp_rewrite;            //since 0.1.9
			$wp_rewrite -> flush_rules( true );
			add_rewrite_rule( "$cat/([0-9a-z]{4,})/?$", 'index.php?' . $cat . '=$matches[1]', 'top' );
			flush_rewrite_rules();
			$alOptions['oldCat'] = $alOptions['redirectCat'];
			$alOptions['oldRedirectType'] = $alOptions['redirectType'];
			update_option( 'anylink_options', $alOptions );
		}
	}
}
?>