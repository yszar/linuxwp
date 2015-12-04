<?php
class al_slug {
	public $numSlugChar;
	public $slugChar;
	function __construct() {
		$arrOptions = get_option( 'anylink_options' );
		$this -> numSlugChar = $arrOptions['slugNum'];
		$this -> slugChar = $arrOptions['slugChar'];
	}
	//generate 4 characters randomly
	public function generate4Chars() {
		$slugChar = $this -> slugChar;
		switch( $slugChar ) {
			case 0:
				$chars = '0123456789';
				break;
			case 1:
				$chars = 'abcdefghijklmnopqrstuvwxyz';
				break;
			case 2:
				$chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
				break;
			default:
				$chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
		}
		$str = '';
		$num = $this -> numSlugChar;
		$length = strlen( $chars ) - 1;
		for( $i = 0; $i < $num; $i++ )
			$str .= $chars[mt_rand( 0,$length )];
		return $str;
	}
	//get a slug
	public function generateSlug() {
		global $wpdb;
		global $ANYLNK_DBTB;
		$slug = $this -> generate4Chars();
		$query = "SELECT al_slug FROM " . ANYLNK_DBTB;
		$slugs = $wpdb->get_col( $query , 0 );
		while( in_array( $slug,$slugs ) )
			$slug = $this -> generateSlug();
		return $slug;
	}
	//generate a slug array by the array given
	public function generateSlugArr( $arr ) {
		if ( ! is_array( $arr ) )
			return null;
		$slugArr = array();
		foreach ( $arr as $value ) {
			$slug = $this -> generateSlug();
			$slugArr["$slug"] = $value;
		}
		return $slugArr;
	}
	public function getLinkBySlug( $slug ) {
		global $wpdb;
		$link = $wpdb -> get_var( $wpdb -> prepare( 
			"SELECT al_origURL
			FROM " . ANYLNK_DBTB . " 
			WHERE al_slug = %s",
			$slug 
			) );
		return $link;
	}
}