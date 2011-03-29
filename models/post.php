<?php
class Post extends AppModel {
	var $name = 'Post';
	var $displayField = 'title';


	var $actsAs = array('Increment'=>array('incrementFieldName'=>'count_views'));



	var $CategoryPaperPost = null;

	var $validate = array(
		'user_id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
	//'message' => 'Your custom message here',
	//'allowEmpty' => false,
	//'required' => false,
	//'last' => false, // Stop validation after this rule
	//'on' => 'create', // Limit validation to 'create' or 'update' operations
	),
	),
		'title' => array(
			'notempty' => array(
				'rule' => array('notempty'),
	//'message' => 'Your custom message here',
	//'allowEmpty' => false,
	//'required' => false,
	//'last' => false, // Stop validation after this rule
	//'on' => 'create', // Limit validation to 'create' or 'update' operations
	),
	),
		'content' => array(
			'notempty' => array(
				'rule' => array('notempty'),
	//'message' => 'Your custom message here',
	//'allowEmpty' => false,
	//'required' => false,
	//'last' => false, // Stop validation after this rule
	//'on' => 'create', // Limit validation to 'create' or 'update' operations
	),
	),
	);
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
			),
		'Topic' => array(
			'className' => 'Topic',
			'foreignKey' => 'topic_id',
			'conditions' => 'Post.topic_id != null',
			'fields' => '',
			'order' => ''

			)
			);

	var $hasMany = array(
		'Comment' => array(
			'className' => 'Comment',
			'foreignKey' => 'post_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
			)
			);

			// temp. not necessary

	/*	var $hasAndBelongsToMany = array(
			 'User' => array(
			 'className' => 'User',
			 'joinTable' => 'posts_users',
			 'foreignKey' => 'post_id',
			 'associationForeignKey' => 'user_id',
			 'unique' => true,
			 'conditions' => '',
			 'fields' => '',
			 'order' => '',
			 'limit' => '',
			 'offset' => '',
			 'finderQuery' => '',
			 'deleteQuery' => '',
			 'insertQuery' => ''
			 )
			 );*/

	// CALLBACKS
	
	/**
	 * @author tim
	 *
	 * reposting means to recommend a post of another user to your followers.
	 * it will be shown on your own blog-page and be marked as reposted. (comparable to re-tweet)
	 * -> to do so: this function creates an entry in the table posts_users
	 * 		with the redirected post and the user who is recommending it and his topic_id in which he reposts it.
	 * Furthermore an entry in the "reposters" array of the Post is added, to check quickly if a
	 *  User already reposted a post (especially for better performance in views)
	 *
	 * @param int $paper_id  -> reposted post
	 * @param int $topic_id -> (optional) topic of the _reposter_ in which he wants to repost the post (!this is not the topic in which the original author publicized it!)
	 * 
	 * 14.03.11 /tim - added:	 user can't repost own post
	 * 				   debugged: user can't repost twice 
	 * 						  
	 * 27.02.11 /tim - rewrote procedure; added topic_id into post_users; added check for existing posts 
	 */
	function repost($user_id, $topic_id = null){
		App::import('model','PostUser');
		$this->PostUser = new PostUser();
		$PostUserData = array('repost' => true,
								'post_id' => $this->id,
							   	'PostUser.user_id' => $user_id);
		$repostCount = $this->PostUser->find('count',array('conditions' => $PostUserData));
		// if there are no reposts for this post/user combination yet
		if($repostCount == 0){
			if($this->data['Post']['user_id'] != $user_id){
			//post is not from reposting user		
				$this->PostUser->create();
				// adding the topic_id to the PostUser - array
				$PostUserData = array('PostUser' => array('repost' => true,
										'post_id' => $this->id,
										'topic_id' =>  $topic_id,
									   	'user_id' => $user_id));
				if($this->PostUser->save($PostUserData)){
						//repost was saved
						// writing the reposter's user id into the reposters-array of the post, if not already in reposters array		
						if((empty($this->reposters)) || (!in_array($user_id,$this->reposters))){
							$this->data['Post']['reposters'][] = $user_id;
							//increment count_reposts for the reposted post (by count reposters array)
							$this->data['Post']['count_reposts'] = count($this->data['Post']['reposters']);							
							$this->save($this->data['Post']);
						}
						return true;
					}	
			} else {
				//user tried to repost his own post
				$this->log('Post/Repost: User '.$user_id.' tried to repost  Post'.$this->id.' which is his own post.');
			}
		}else{
			// already reposted
			// writing the reposter's user id into the reposters-array of the post, if not already in reposters array		
				if((empty($this->reposters)) || (!in_array($user_id,$this->reposters))){
					$this->data['Post']['reposters'][] = $user_id;
					//increment count_reposts for the reposted post (by count reposters array)
					$this->data['Post']['count_reposts'] = count($this->data['Post']['reposters']);							
					$this->save($this->data['Post']);
				}
			$this->log('Post/Repost: User '.$user_id.' tried to repost  Post'.$this->id.' which he had already reposted.');
		}
		return false;
	}
			
/**
	 * @author tim
	 *
	 * deleting a repost: if a user wants to undo a repost this function will delete the repost from the posts_user table. additionally
	 * the repost_counter will be decremented and the user will be deleted from the reposters array in the post.
	 *
	 * @param $user_id - id of the user, that wants to delete the repost of this paper
	 */
	function undoRepost($user_id){
		App::import('model','PostUser');
		$this->PostUser = new PostUser();
		// just in case there are several reposts (PostUser.repost => true) for the combination post/user - all will be deleted.
		$this->PostUser->contain();
		$reposts =  $this->PostUser->find('all',array(
								'conditions' => array(
										'PostUser.repost' => true,
										'PostUser.post_id' => $this->id,
										'PostUser.user_id' => $user_id)));
		$delete_counter = 0;
		foreach($reposts as $repost){
			//deleting the repost from the PostUser-table
			$this->PostUser->delete($repost['PostUser']['id']);
			$delete_counter += 1;
		}
		//writing log entry if there were more than one entries for this repost (shouldnt be possible)
		if($delete_counter > 1){
			$this->log('Post/undoRepost: User '.$user_id.' had more then 1 repost entry (posts_user table) for Post '.$this->id.'. (now deleted) This should not be possible.');
		}

		if($delete_counter >= 1){
			//deleting user-id entry from reposters-array in post-model
			while(in_array($user_id,$this->data['Post']['reposters'])){
				$pos = array_search($user_id,$this->data['Post']['reposters']);
				unset($this->data['Post']['reposters'][$pos]);
			}
			$this->data['Post']['count_reposts'] = count($this->data['Post']['reposters']);
			$this->save($this->data['Post']);
			return true;
		} 
		return false;
	
	

		
	}
	
			
	/**
	* @author: tim
	* unserializing the reposters-array after being read from the db.
	*
	*/
	function afterFind($results) {
		foreach ($results as $key => $val) {
			if (!empty($val['Post']['reposters']) ) {
				$results[$key]['Post']['reposters'] = unserialize($results[$key]['Post']['reposters']);
			}else {
				if(isset($results[$key]['Post']['reposters'])){
					$results[$key]['Post']['reposters'] = array();
				}
			}
			if (!empty($val['reposters']) ) {
				$results[$key]['reposters'] = unserialize($results[$key]['reposters']);
			}else {
				if(isset($results[$key]['reposters'])){
					$results[$key]['reposters'] = array();
				}
			}
		}
		return $results;
	}

	/**
	 * @author: tim
	 * serializing the reposters-array before being written to the db.
	 *
	 */
	function beforeSave(&$Model) {
		if(!empty($this->data['Post']['reposters'])){
			$this->data['Post']['reposters'] = serialize($this->data['Post']['reposters']);
		}
		return true;
	}



	/**
	 * 1)
	 * update solr index with saved data
	 */
	function afterSave(){

		App::import('model','Solr');

		$userData = $this->User->read(null, $this->data['Post']['user_id']);

		if($userData['User']['id']){
			if(isset($this->data['Post']['topic_id'])){
				$topicData = $this->Topic->read(null, $this->data['Post']['topic_id']);

				if($topicData['Topic']['id'] && !empty($topicData['Topic']['name'])){
					$this->data['Post']['topic_name'] = $topicData['Topic']['name'];
				}
			}
				
			$this->data['Post']['index_id'] = 'post_'.$this->id;
			$this->data['Post']['id'] = $this->id;
			$this->data['Post']['user_name'] = $userData['User']['name'];
			$this->data['Post']['type'] = Solr::TYPE_POST;
			$solr = new Solr();
			$solr->add($this->removeFieldsForIndex($this->data));

		}
		else{
			$this->log('Error while reading user for Post! No solr index update');
		}
	}


	/**
	 * @todo move to abstract for all models
	 * Enter description here ...
	 */
	private function removeFieldsForIndex($data){
		unset($data['Post']['enabled']);
		unset($data['Post']['count_views']);
		unset($data['Post']['count_reposts']);
		unset($data['Post']['count_comments']);
		unset($data['Post']['topic_id']);
		unset($data['Post']['modified']);
		unset($data['Post']['created']);
		unset($data['Post']['reposters']);
		unset($data['Post']['image']);
		unset($data['Post']['image_details']);



		return $data;

	}


	function __construct(){
		parent::__construct();
	}

	function delete($id){
		$this->removeUserFromSolr($id);
		return parent::delete($id);
	}

	/**
	 * remove the user from solr index
	 *
	 * @param string $id
	 */
	function removeUserFromSolr($id){
		App::import('model','Solr');
		$solr = new Solr();
		$solr->delete(Solr::TYPE_POST . '_' . $id);
	}

}

?>