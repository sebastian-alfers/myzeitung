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
                    return true;
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
            // it kinda represents the wish to un-associate the feed)
            return true;
        }
    }

    private function deletePostsForDeletedFeedAssociation($user_id, $feed_id){
        //RssFeedsUser is already imported here, since this function is just called from "removeFeedFromUser"

        //$this->RssFeedUser->find('all',)
    }

    private function deleteAssociation($id){
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

