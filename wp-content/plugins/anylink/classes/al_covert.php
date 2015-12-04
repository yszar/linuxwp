<?php
class al_covert {
	//get all the posts/pages' id
	//I don't use the get_posts() method, coz I needn't that much vars
	//return an array
	public function arrGetPostIDs() {
		global $wpdb;
		$arrPostIDs = array();
			$arrPostIDs = $wpdb -> get_col( $wpdb -> prepare( 
				"SELECT ID 
				FROM " . $wpdb -> prefix . "posts 
				WHERE post_type <> %s",
				'revision' 
			));
		return $arrPostIDs;
	}
    public function get_all_comments(){
        global $wpdb;
        $all_comment_IDs = array();
        $all_comment_IDs = $wpdb -> get_col( $wpdb -> prepare( 
            "SELECT comment_ID 
            FROM " . $wpdb -> prefix . "comments 
            WHERE comment_approved = %d",
            1
        ));
        return $all_comment_IDs;
    }
    
	public function arrGetAllLnks( $content ) {
		$pattern = '/(?<=href=["\'])https?:\/\/[^"\']+/i';
		preg_match_all($pattern, $content, $matches);
		$arrAllLnks = array_unique( $matches[0] );
		return $arrAllLnks;
	}
	//site URL should not be pull out
	private function filterLocalURL( array $arrURL ) {
		$siteURL = home_url();
		$result = array();
		foreach( $arrURL as $URL ) {
			if ( index2Of( $siteURL, $URL ) )
				continue;
			$result[] = $URL;	
		}
		return $result;
	}
	public function storeExtLnks( $arrURLs ) {
		global $wpdb;
		$urlIDs = array();
		$slugs = new al_slug();
		foreach( $arrURLs as $URL ) {
			//if the URL to be stored is already in DB then return its ID
			$idInTb = $wpdb -> get_var( $wpdb -> prepare( 
								"SELECT al_id
								FROM " . ANYLNK_DBTB . "
								WHERE al_origURL = %s",
								$URL 
								) );
			if( ! is_null( $idInTb ) ){
				$urlIDs[] = $idInTb;
				continue;
			}
			//if not, generate a slug and insert
			$slug = $slugs -> generateSlug();
			$wpdb -> insert( ANYLNK_DBTB, 
							array( 
								'al_slug'    => $slug,
								'al_origURL' => $URL,
								'al_crtime'  => '',
								),
							array( 
								'%s',
								'%s',
								'NOW()',
								)
							);
			$urlIDs[] = $wpdb -> insert_id;
		}
		return $urlIDs;
	}
	public function storeRel ( $post_id, $arrUrlIDs, $comment_id = 0 ) {
		global $wpdb;
		$arrOldIndex = array();
		//some links may be deleted after your editing the post
		//the relationship should be deleted as well
		//pull out all IDs form data table as the old entries
		$arrOldRel = $wpdb -> get_results( $wpdb -> prepare( 
						"SELECT al_url_id
						FROM " . ANYLNK_DBINDEX . " 
						WHERE al_post_id = %d 
                        AND al_comm_id = %d",
						$post_id, 
                        $comment_id
						), ARRAY_A );
		foreach( $arrOldRel as $oldRel ){
			foreach( $oldRel as $urlID ){
				$arrOldIndex[] = $urlID; 
			}
		}
		//$arrUrlIDs is an array of news entries
		//compare both arrays with each other
		//if one record is found in new array but not in old array it means we add an new URL
		//on the contrary, a record if found if old array but not in new array. It means we deleted a URL
		$arrToAdd = array_diff( $arrUrlIDs, $arrOldIndex );
		$arrToDel = array_diff( $arrOldIndex, $arrUrlIDs );
		if( ! empty( $arrToAdd ) ) {
			foreach( $arrToAdd as $urlID ) {
				$wpdb -> insert( ANYLNK_DBINDEX,
								array( 
									'al_url_id'   => $urlID,
									'al_post_id'  => $post_id,
                                    'al_comm_id'  => $comment_id,
									),
								array( 
									'%d',
									'%d',
                                    '%d',
									)
								);
			}
		}
		if ( ! empty( $arrToDel ) ) {
			foreach( $arrToDel as $urlID ) {
				$wpdb -> delete( ANYLNK_DBINDEX,
								array(
									'al_url_id'  => $urlID,
									'al_post_id' => $post_id,
                                    'al_comm_id' => $comment_id,
									),
								array(
									'%d',
									'%d',
                                    '%d',
									)
								);
			}
		}
	}
	//get all URL's ID
	public function getAllSlugID() {
		$arrSlugID = array();
		global $wpdb;
		$arrSlugID = $wpdb -> get_col( $wpdb -> prepare( 
								"SELECT al_id
								FROM " . ANYLNK_DBTB
								, '' ) );
		return $arrSlugID;
	} //end getAllSlugID
	//regenerate slugs
	public function regenerateSlugByID( $slugID ) {
		$slugs = new slug();
		global $wpdb;
		$newSlug = $slugs -> generateSlug();
		$wpdb -> update( ANYLNK_DBTB, 
						array( 'al_slug' => $newSlug ),
						array( 
							'al_id' => $slugID,
							'al_isAuto' => 1
							),
						array('%s'),
						array('%s', '%d')
						);
	} //end regenerateSlugByID
	public function covertURLs( $id, $comment = false) {
	   //if this is a post
        if( !$comment ) {
            $thePost = get_post( $id,  ARRAY_A );
            $content = $thePost['post_content']; //get post content
            $arrURLs = array();
            $arrIDs  = array();
            $arrURLs = $this -> arrGetAllLnks( $content );
            $arrURLs = $this -> filterLocalURL( $arrURLs );
            if( empty( $arrURLs ) )
            	return;
            $arrIDs  = $this -> storeExtLnks( $arrURLs );
            $this -> storeRel( $id, $arrIDs );
        } else { //if this is a comment
            $the_comment = get_comment( $id, ARRAY_A );
            $comment_body = $the_comment['comment_content'];
            $comment_post_id = $the_comment['comment_post_ID'];
            $comment_body_urls = $this -> arrGetAllLnks( $comment_body );   //get all urls in comment body
            //if comment author's homepage url is not empty
            //add it to the array of urls
            if( !empty( $the_comment['comment_author_url'] ) ) {
                array_push( $comment_body_urls, $the_comment['comment_author_url'] );
                $comment_urls = array_unique( $comment_body_urls );
            }
            if( empty( $comment_urls ) )
                return;
            $comment_urls = $this -> filterLocalURL( $comment_urls );  //filte local urls
            if( empty ( $comment_urls ) )
                return;
            $comment_url_IDs = $this -> storeExtLnks( $comment_urls ); //insert all external links into data table and get IDs
            $this -> storeRel( $comment_post_id, $comment_url_IDs, $id );
        }
	}
}
?>