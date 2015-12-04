<?php
class al_filter {
	public $arrU2S = array();
	public $redirectCat;
	public $redirectType;
    public $rel;
	function __construct() {
		$anylinkOptions = get_option( 'anylink_options' );
		$this -> redirectCat  = $anylinkOptions['redirectCat'];
		$this -> redirectType = $anylinkOptions['redirectType'];
        $this -> rel = $anylinkOptions['rel'];
	}
    /**
     * Get all links belongd to the specified post or comment
     * 
     * @param int $post_id the id of the post
     * @param int $cimment_id the id of the comment, default 0, means it's a post rather than a comment
     * @since 0.1.9
     */
	public function getAllLnks( $id, $is_comment = false ) {
		$arrURL = array();
		global $wpdb;
        if( !$is_comment )
            $column = 'I.al_post_id';
        else
            $column = 'I.al_comm_id';
		$arrURL = $wpdb -> get_results( $wpdb -> prepare( 
			"
			SELECT U.al_id, U.al_slug,U.al_origURL 
			FROM " . ANYLNK_DBINDEX . " I
			LEFT JOIN " . ANYLNK_DBTB . " U
			ON I.al_url_id = U.al_id
			WHERE " . $column . "= %d",
			$id
		), ARRAY_A );
		return $arrURL;
	}
	//restore all URL
	public function replaceURL( $matches ) {
		$U2S = $this -> arrU2S;
		$siteURL = home_url();
        //$matches[2], $matches[5] already have key names: URL and rel
        unset( $matches[2], $matches[5] );
        //$mathes[0]  is the full matched string, delete it to use implode later
        array_shift( $matches );
		//only replace the links which have slugs, or return the original URL
        //Since 0.2.1
        //UEditor神经病，会把wordpress已经转义的&amp;再还原成&，导致字符串比较出现问题，因此要再再次还原
        if(function_exists('enable_ueditor') && strpos($matches['URL'],'&#038;') !== false){
            $matches['URL'] = str_replace('&#038;','&',$matches['URL']);
        }
		if( array_key_exists( $matches['URL'], $U2S ) ) {
            $matches['URL'] = $U2S[$matches['URL']];
            //if need set rel manually
            //get rel attributes which are already set in url and then merge them
            if( ! empty( $this -> rel ) ) {
                if( empty( $matches['rel'] ) )
                    $rel = ' rel="' . $this -> rel . '"';
                else {
                    $_rel1 = explode( " ", $this -> rel );
                    $_rel2 = explode( " ", $matches['rel'] );
                    $_rel  = array_merge( $_rel1, $_rel2 );
                    $_rel  = array_unique( $_rel );
                    $rel   = implode( " ", $_rel );
                }
                $matches['rel'] = $rel;
            }
            return implode( '', $matches );
        } else {
            //if external link not indexed, or
            //it is a interal link
			return implode( '', $matches );
        }
	}
    /**
     * Filter all external links which are exist in database
     * 
     * @param String $content is the content which cotains external link(s)
     * @return string $content 
     */
	public function applyFilter( $content, $id, $is_comment = false ) {
		global $wp_query, $wp_rewrite;
		$arrUrlSlug = $this -> getAllLnks( $id, $is_comment );
		if( $arrUrlSlug ) {
			foreach( $arrUrlSlug as $arrSlugs ) {
				$this -> arrU2S[$arrSlugs['al_origURL']] = $this -> getInternalLinkBySlug( $arrSlugs['al_slug'] );
			}
		}
		/*$pattern  = '#(<a\s*';
		$pattern .= 'href=")(?P<URL>https?://[^"]+?)("';
        $pattern .= '[^>]*?)(?<rel>rel="[^"]*")?';
		$pattern .= '([^>]*?>)#i';*/
        $pattern = '#(<a[^>]*?href=")(?<URL>https?://[^"]*)(")(?(?=[^>]*?rel=)([^>]*?rel=")(?<rel>[^"]*)([^>]*)|([^>]*))(>)#';
		//covert ALL URLs, we can't just use str_replace, 
		//coz a post may contain a plain URL, 
		//even some text like this: href="http://dudo.org"
		//Or, this plain text will be replaced as well
		$content  = preg_replace_callback( $pattern, array( $this, 'replaceURL' ), $content );
		return $content;
	}
	public function addQueryVars( $qvars ) {
		array_push( $qvars, $this -> redirectCat );
		return $qvars;
	}
	public function alter_the_query( $wp ) {
		global $wp_query, $wp_rewrite;
		$gotoURL = '';
		if( $wp_rewrite -> using_permalinks() )
			if ( array_key_exists( $this -> redirectCat, $wp -> query_vars ) && $wp -> query_vars[$this -> redirectCat] != '')  
				$gotoURL = $wp -> query_vars[$this -> redirectCat];
			else
				return;
		elseif( isset( $_GET[$this -> redirectCat] ))
			$gotoURL = $_GET[$this -> redirectCat];
		if( ! empty( $gotoURL ) ) {
            //since 0.1.9
            // changed redirect method by javascript
            $gotoLink = htmlspecialchars_decode( $this -> getUrlBySlug( $gotoURL ) );
            $redirectType = ( int )$this -> redirectType;
            if( $redirectType == 200 ) {
                require_once( ANYLNK_PATH . '/re.php');
                exit;
            } else { //end
                wp_redirect( $gotoLink, $redirectType );
                exit;
            }
		}
	}
	public function getUrlBySlug( $slug ) {
		global $wpdb;
		$URL = $wpdb -> get_var( $wpdb -> prepare( 
			"
			SELECT al_origURL
			FROM " . ANYLNK_DBTB . " 
			WHERE al_slug = %s",
			$slug
		) );
		return $URL;
	}
	
	public function getSlugById( $id ) {
		global $wpdb;
		$arrSlug = $wpdb -> get_row( $wpdb -> prepare( 
			"SELECT * 
			FROM " . ANYLNK_DBTB . "
			WHERE al_id = %s", 
			$id
		), ARRAY_A );
		return $arrSlug;
	}
    /**
     * 通过URL地址查找对应的slug
     * 
     * @param string $url 给定的链接
     * @return arrary $arr_slug 返回查找到的数组
     * @since 0.2
     */
    public function get_slug_by_url( $url ) {
        global $wpdb;
        $arr_slug = array();
        $arr_slug = $wpdb -> get_row( $wpdb -> prepare( 
            "SELECT *
            FROM " . ANYLNK_DBTB ." 
            WHERE al_origURL = %s",
            $url
        ), ARRAY_A );
        return $arr_slug;
    }
	
	public function getInternalLinkBySlug( $slug ) {
		global $wp_rewrite;
		$siteURL = home_url();
		if( $wp_rewrite -> using_permalinks() )
			$internalLink = $siteURL . '/'  . $this -> redirectCat . '/' . $slug;
		else
			$internalLink = $siteURL . '/?' . $this -> redirectCat . '=' . $slug;
		return $internalLink;
	}
}
?>