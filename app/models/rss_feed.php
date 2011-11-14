<?php
class RssFeed extends AppModel {
    var $name = 'RssFeed';

    var $validate = array(
		'url' => array(
			'url' => array(
				'rule' => array('url'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'enabled' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);


    var $hasMany = array(
        'RssFeedsUser' => array(
			'className' => 'RssFeedsUser',
			'foreignKey' => 'feed_id',
			'dependent' => true
        ),

        'RssFeedsItem' => array(
			'className' => 'RssFeedsItem',
			'foreignKey' => 'feed_id',
			'dependent' => true
        ),
        'RssImportLog' => array(
			'className' => 'RssImportLog',
			'foreignKey' => 'rss_feed_id',
			'dependent' => false,
            'limit' => '1',
            'order' => 'RssImportLog.created DESC'
        )
    );

    var $hasAndBelongsToMany = array(
        'RssItem' =>
            array(
                'className'              => 'RssItem',
                'joinTable'              => 'rss_feeds_items',
                'foreignKey'             => 'feed_id',
                'associationForeignKey'  => 'item_id',
                'unique'                 => true,
                'conditions'             => '',
                'fields'                 => '',
                'order'                  => '',
                'limit'                  => '',
                'offset'                 => '',
                'finderQuery'            => '',
                'deleteQuery'            => '',
                'insertQuery'            => ''
            )
    );


    function addFeedToUser($user_id, $feed_url){
        App::import('model','RssFeedsUser');
        $this->RssFeedsUser = new RssFeedsUser();

        $this->contain();
        $existent_feed = $this->find('first', array('conditions' => arraY('url' => $feed_url)));
        //check if the Feed did already exist in the system
        if(isset($existent_feed['RssFeed']['id']) && $existent_feed['RssFeed']['id'] != null){
            //feed already does already exist

            //check if the feed is already associated with the user
            $this->RssFeedsUser->contain();
            if($this->RssFeedsUser->find('count', array('conditions' => array('user_id' => $user_id, 'feed_id' => $existent_feed['RssFeed']['id'])))){
                //feed is already associated
                return $existent_feed['RssFeed']['id'];
            }else{
                //feed not associated yet
                //associate feed to user
                if($this->RssFeedsUser->save(array('feed_id' => $existent_feed['RssFeed']['id'], 'user_id' => $user_id))){
                    //existent feed was successfully associated to user
                    return $existent_feed['RssFeed']['id'];
                }else{
                    //feed could not be associated
                    /*
                    * @todo add logging or something
                    */
                    return false;
                }
            }
            
        }else{
            //feed does not exist
            //add feed to database
            $this->create();
            if($this->save(array('url' => $feed_url))){
                //asssociate feed with the user


                $this->RssFeedsUser->create();
                if($this->RssFeedsUser->save(array('feed_id' => $this->id, 'user_id' => $user_id))){
                    return $this->id;
                }else{
                    //problem - association could not be saved
                    /*
                     * @todo add logging or something
                     */
                    return false;
                }
            }else{
                //did not save the feed w0000t
                /*
                * @todo add logging or something
                */
                return false;
            }
        }
    }


    function removeFeedFromUser($user_id, $feed_id, $delete_posts = false){
        App::import('model','RssFeedsUser');
        $this->RssFeedsUser = new RssFeedsUser();

        //check if the user is associated to the feed
        $feed_user = array();
        $feed_user = $this->RssFeedsUser->find('first', array('conditions' => array('user_id' => $user_id, 'feed_id' => $feed_id)));
        if(isset($feed_user['RssFeedsUser']['id']) && !empty($feed_user['RssFeedsUser']['id'])){
            //user is associated to feed
            //should posts created by this feed be deleted too?
            if($delete_posts){
                //also delete posts

                if($this->deleteAssociation($feed_user['RssFeedsUser']['id'])){
                    if($this->deletePostsForDeletedFeedAssociation($user_id, $feed_id)){
                        return true;
                    }else{
                        return false;
                    }
                }else{
                    return false;
                }

            }else{
                //posts dont need to be deleted - success
                //just delete association
                if($this->deleteAssociation($feed_user['RssFeedsUser']['id'])){
                    return true;
                }
            }


        }else{
            //user is not associated to the feed
            // no reason to do anything
            /*
             * @todo add logging that user did want to do crap
             */

            // (yes ... returning true does not really get to the point,  but since the user is not associated
            // it kinda represents the users' wish to un-associate the feed)
            return true;
        }
    }


/*
         * @todo make PRIVATE after testing
         */
   function deletePostsForDeletedFeedAssociation($user_id, $feed_id){
        //RssFeedsUser is already imported here, since this function is just called from "removeFeedFromUser"


       //get all feeds of this user with all items associated to those feeds
       // goal: cross check which posts are generated from RSS items of OTHER rss feeds of THIS user
       // why?: if a feed is deleted with posts, we cannot delete a posts if it came from another RSS-feed
       //       which is not deleted (yet)
       $contain = array('RssFeed' => array('RssItem' => array('id')));
       $user_feeds = $this->RssFeedsUser->find('all',array('contain' => $contain, 'conditions' => array('user_id' => $user_id)));

       //get all item-ids from any feed of the user
       foreach($user_feeds as $user_feed){
            foreach($user_feed['RssFeed']['RssItem'] as $item){
                if($user_feed['RssFeed']['id'] == $feed_id){
                    //this is the feed which is going to be remove for the user
                    $item_ids_to_delete[] = $item['id'];
                }else{
                    //another feed
                    $item_ids_of_other_feeds[] = $item['id'];
                }
            }
       }

       //unset all ids in the "to_Delete" that are also in other feeds
       foreach($item_ids_to_delete as $key => $value){
            if(in_array($value, $item_ids_of_other_feeds)){
                unset($item_ids_to_delete[$key]);
            }
       }

       if(count($item_ids_to_delete)){
           //delete all posts of this user which were created out of these items
           App::import('model','Post');
           $this->Post = new Post();
           if($this->Post->deleteAll(array('Post.user_id' => $user_id, 'Post.rss_item_id' => $item_ids_to_delete), true, true)){
               return true;
           }else{
               return false;
           }
       }else{
           //nothing to delete
           return true;
       }
   }



        /*
         * @todo make PRIVATE after testing
         */
    function deleteAssociation($id){
        //RssFeedsUser is already imported here, since this function is just called from "removeFeedFromUser"
        if($this->RssFeedsUser->delete($id, false)){
            return true;
        }else{
            //could no unassociate
            /*
            * @todo add logging or something
            */
            return false;
        }
    }



}
?>

