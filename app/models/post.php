<?php
class Post extends AppModel {

    
    var $name = 'Post';
    var $displayField = 'title';
    var $useCustom = false;
    var $topicChanged = false;

    var $actsAs = array('Increment'=>array('incrementFieldName'=>'view_count'));
   
    var $updateSolr = false;

    var $CategoryPaperPost = null;
    
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'counterCache' => true,
            'counterScope' => array('Post.enabled' => true),
	),
		'Topic' => array(
			'className' => 'Topic',
			'foreignKey' => 'topic_id',
			'conditions' => 'Post.topic_id != null',
			'fields' => '',
			'order' => '',
			'counterCache' => true,
            'counterScope' => array('Post.enabled' => true),
	    ),
	);

	var $hasMany = array(
		'Comment' => array(
			'className' => 'Comment',
			'foreignKey' => 'post_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
			),
		'PostUser' => array(
			'className' => 'PostUser',
			'foreignKey' => 'post_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
			),
       'Route' => array(
            'className' => 'Route',
            'foreignKey' => 'ref_id',
            'dependent' => 'false',
            'limit' => 1,
            'conditions' => array('parent_id' => null,
                                 'ref_type' => 'POST'),
            ),


      );





    function disable(){

        if($this->data['Post']['enabled'] == true){
            $postData = $this->data;
            //disable all posts_users entries with cascading and callbacks
            App::import('model','PostUser');
            $this->PostUser = new PostUser();
            $this->PostUser->contain();
             $postUsers = $this->PostUser->find('all',array('conditions' => array('post_id' => $this->id)));
            foreach($postUsers as $postUser){
                $this->PostUser->data = $postUser;
                $this->PostUser->disable();
            }
            //disable post
            $this->data = $postData;
            $this->data['Post']['enabled'] = false;
            $this->save($this->data);
            //delete solr entry
            $this->deleteFromSolr();

            return true;
        }
        //already disabled
        return false;

    }
    function enable(){

        if($this->data['Post']['enabled'] == false){
            $postData = $this->data;
            //enable all posts_users entries with cascading and callbacks
            App::import('model','PostUser');
            $this->PostUser = new PostUser();
            $this->PostUser->contain();
            $postUsers = $this->PostUser->find('all',array('conditions' => array('post_id' => $this->id)));
            foreach($postUsers as $postUser){
                $this->PostUser->data = $postUser;
                $this->PostUser->enable();
            }
            //enable post
            $this->data = $postData;
            $this->data['Post']['enabled'] = true;
            $this->updateSolr = true;
            $this->save($this->data);

            return true;
        }
        //already enabled
        return false;
    }
    function addRoute(){

        App::import('model','Route');
        $this->Route = new Route();
        $this->Route->create();

        $routeString = $this->generateRouteString();
        if($routeString != ''){
            $routeData['Route'] = array('source' => $routeString,
                               'target_controller' 	=> 'posts',
                               'target_action'     	=> 'view',
                                'target_param'		=> $this->id,
                                'ref_type'          => Route::TYPE_POST,
                                'ref_id'            => $this->id);

             if($this->Route->save($routeData,false)){
                 $newRouteId = $this->Route->id;
                 // update children
                 $this->Route->contain();
                 $oldRoutes =$this->Route->find('all',array('conditions' => array('ref_type' => Route::TYPE_POST,  'ref_id' => $this->id)));
                 foreach($oldRoutes as $oldRoute){
                      if($oldRoute['Route']['id'] !=  $newRouteId){
                          $oldRoute['Route']['parent_id'] = $newRouteId;
                          $this->Route->save($oldRoute);
                          $this->Route->deleteRouteCache($oldRoute['Route']['source']);
                      }
                 }
                 return true;
             }
        }else{
            return true;
        }

        return false;
    }

    private function generateRouteString(){
        App::import('model','User');
        $this->User = new User();
        $this->User->contain();
        $user = $this->User->read(array('id','username'),$this->data['Post']['user_id']);


        $this->Inflector = ClassRegistry::init('Inflector');
        $routeUsername = strtolower($user['User']['username']);
        $routeTitle= strtolower($this->Inflector->slug($this->data['Post']['title'],'-'));
        $routeString = '/a/'.$routeUsername.'/'.$routeTitle;
        $this->Route->contain();
        $existingRoutes = $this->Route->findAllBySource($routeString);

        if(count($existingRoutes) >0){
            //the route is already used
            $sameTarget = true;
            foreach($existingRoutes as $existingRoute){
                if($existingRoute['Route']['ref_type'] !=  Route::TYPE_POST || $existingRoute['Route']['ref_id'] != $this->id){
                    $sameTarget = false;
                }
            }
            if($sameTarget){
                //route already exists - nothing needed
                return '';
            }else{
                //generate new routestring with uniqid
                //$routeString = 'a/'.$routeUsername.'/'.$routeTitle.'-'.uniqid();
                //generate new routestring with added id
                $routeString = '/a/'.$routeUsername.'/'.$routeTitle.'-a'.$this->id;
                $this->Route->contain();
                $existingRoutes = $this->Route->findAllBySource($routeString);
                //if this specific routestring does exist, it must direct to the same target
                if(count($existingRoutes)>0){
                    return '';
                }
                return $routeString;
            }

        }else{
            //the route is not used yet
            return $routeString;

        }



        return $route_title;
    }



    function deleteRoutes(){
        App::import('model','Route');
        $this->Route = new Route();
        $conditions = array('Route.ref_type' => Route::TYPE_POST, 'Route.ref_id' 	=> $this->id);
        //cascade = false, callbackes = true
        $this->Route->contain();
        $this->Route->deleteAll($conditions, false, true);
    }
    function refreshRoutes(){
        $this->contain();
        $posts = $this->find('all');

        foreach($posts as $post){
            $this->id = $post['Post']['id'];
            $this->data = $post;
            $this->addRoute();
            $this->addToOrUpdateSolr();
        }

    }

    function deleteFromSolr(){
        App::import('model','Solr');
        $solr = new Solr();
        $solr->delete(Solr::TYPE_POST.'_'.$this->id);
        return true;
    }
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
				$this->PostUser->contain();
				$repostCount = $this->PostUser->find('count',array('conditions' => $PostUserData));
				// if there are no reposts for this post/user combination yet
				if($repostCount == 0)
                {

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
							$this->addUserToReposters($user_id);
							return true;
						}
					} else {
						//user tried to repost his own post
						$this->log('Post/Repost: User '.$user_id.' tried to repost  Post'.$this->id.' which is his own post.');
					}
				}else{
					// already reposted
					// writing the reposter's user id into the reposters-array of the post, if not already in reposters array
                    $this->addUserToReposters($user_id);
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
					$this->PostUser->delete($repost['PostUser']['id'], true);
					$delete_counter += 1;

				}
				//writing log entry if there were more than one entries for this repost (shouldnt be possible)
				if($delete_counter > 1){
					$this->log('Post/undoRepost: User '.$user_id.' had more then 1 repost entry (posts_user table) for Post '.$this->id.'. (now deleted) This should not be possible.');
				}

				if($delete_counter >= 1){
					//deleting user-id entry from reposters-array in post-model
                    $this->removeUserFromReposters($user_id);
					return true;
				}
				return false;
			}

            function removeUserFromReposters($user_id){

                if(!is_null($this->data['Post']['reposters'])){
                    //if not null: check if filled and not already an array
                    if(!empty($this->data['Post']['reposters']) && !is_array($this->data['Post']['reposters'])){
                      $reposters = unserialize($this->data['Post']['reposters']);
                    }
                }else{
                    $reposters = array();
                }

                //deleting user-id entry from reposters-array in post-model

                while(in_array($user_id,$reposters)){
                    $pos = array_search($user_id,$reposters);
                    unset($reposters[$pos]);
                }
                $this->data['Post']['reposters'] = serialize($reposters);

                $this->save($this->data, false);
            }
    
            function addUserToReposters($user_id){

                if(!is_null($this->data['Post']['reposters'])){
                    //if not null: check if filled and not already an array
                    if(!empty($this->data['Post']['reposters']) && !is_array($this->data['Post']['reposters'])){

                        $reposters = unserialize($this->data['Post']['reposters']);
                    }
                }else{
                    $reposters = array();
                }


                //adding user-id entry to reposters-array in post-model
                if(!in_array($user_id,$reposters)){
                            $reposters[] = $user_id;
                            $this->data['Post']['reposters'] = serialize($reposters);

                            //save without validation, otherwise we have validation error while update the post
                            $this->save($this->data, false);
                }

            }


			/**
			 * @author: tim
			 * unserializing the reposters-array after being read from the db.
			 *
			 * the structure of the results array differs depending of the relation in which the posts were read.
			 *
			 */
			function afterFind($results) {
				//	if(isset($results[0])){
		/*		foreach ($results as $key => $val) {
					
					
					// $results[0]['Post']['reposters']
					if (!empty($val['Post']['reposters']) ) {
						$results[$key]['Post']['reposters'] = unserialize($results[$key]['Post']['reposters']);
					}else {
						if(isset($results[$key]['Post']['reposters'])){
							$results[$key]['Post']['reposters'] = array();
						}
					}

					
					if (!empty($val['Post']['image']) ) {
						$results[$key]['Post']['image'] = unserialize($results[$key]['Post']['image']);
					}else {
						if(isset($results[$key]['Post']['image'])){
							$results[$key]['Post']['image'] = array();
						}
					}

					// $results[0]['reposters']
					if (!empty($val['reposters']) ) {

						$results[$key]['reposters'] = unserialize($results[$key]['reposters']);
					}else {
						if(isset($results[$key]['reposters'])){
							$results[$key]['reposters'] = array();
						}
					}


					if (!empty($val['image']) ) {
						$results[$key]['image'] = unserialize($results[$key]['image']);
					}else {
						if(isset($results[$key]['image'])){
							$results[$key]['image'] = array();
						}
					}
				}*/
				/*	} else {
				 //$results['reposters']
				 if (!empty($results['reposters'])) {
				 $results['reposters'] = unserialize($results['reposters']);
				 }else {
				 if(isset($results['reposters'])){
					$results['reposters'] = array();
					}
					}
					}*/

				return $results;
			}

			/**
			 * @author: tim
			 * serializing the reposters-array before being written to the db.
			 *
			 */
			function beforeSave() {

				/*if(!empty($this->data['Post']['reposters']) && is_array($this->data['Post']['reposters']) && !empty($this->data['Post']['reposters'])){
					$this->data['Post']['reposters'] = serialize($this->data['Post']['reposters']);
				}*/
				
				if(!empty($this->data['Post']['image']) && is_array($this->data['Post']['image']) && !empty($this->data['Post']['image'])){
					$this->data['Post']['image'] = serialize($this->data['Post']['image']);
				}

				//generate preview of post				
				$content = explode(' ', strip_tags($this->data['Post']['content']));
				for($i = 0; $i < count($content); $i++){
					$content[$i] = trim($content[$i]);
					$content[$i] = preg_replace('/\s\s+/', ' ', $content[$i]);
				}

                //redundant because we process content with texthelpers trunate function
				$prev = '';
                $max_chars = 175;
                $chars = 0;
                for($i = 0; $i < count($content); $i++){
                    $word = $content[$i];
					if(($chars+strlen($word)) < $max_chars){
						$chars += strlen($word);
						$prev .= ' ' . $word;
					}else{
                        break;
                    }

				}
				$this->data['Post']['content_preview'] = $prev;

             
				return true;
			}


			// OVERRIDES the standard paginationCount
			function paginateCount($conditions = null, $recursive = 0, $extra = array()) {

				if(!$this->useCustom )	{
					//copy of standard paginationcount for normal pagination queries
					$parameters = compact('conditions');

					if ($recursive != $this->recursive) {
						$parameters['recursive'] = $recursive;
					}
					$this->contain();
					$count = $this->find('count', array_merge($parameters, $extra));

					return $count;

				} else {

					//customized paginationcount for controller:papers - action:view  (posts inner join over category_paper_posts)
					// all unique posts (distinct on post_id) of one specific paper (defined in $conditions) are counted
					App::import('model','PostUser');
					$this->CategoryPaperPost = new CategoryPaperPost();
					$this->CategoryPaperPost->contain();
					$count = $this->CategoryPaperPost->find('count', array('conditions' => $conditions, 'fields' => 'distinct CategoryPaperPost.post_id'));
					return $count;
				}
			}


			/**
			 * 1)
			 * writing / updating PostUser-entry
			 * 2)
			 * update solr index with saved data
			 */
    function afterSave($created){


        //1) updating PostUser-Entry
        App::import('model','PostUser');
        $this->PostUser = new PostUser();

        $PostUserData = array('user_id' => $this->data['Post']['user_id'],
                           'post_id' => $this->id);

        if($created){
            //write PostUser-Entry

            if(isset($this->data['Post']['topic_id']) && $this->data['Post']['topic_id'] != PostsController::NO_TOPIC_ID){
                $PostUserData['topic_id'] = $this->data['Post']['topic_id'];
            }

            $this->PostUser->create();
            $this->PostUser->save($PostUserData);

        } else {
            //update PostUser-Entry - but ONLY IF the topic_id has changed
            if($this->topicChanged){

                //delete old entry -> important for deleting all data-associations (from old topic)
                $this->PostUser->contain();
                // params 1. conditions 2. cascading 3. callbacks
                $this->PostUser->deleteAll(array('repost'=> false, 'user_id' => $this->data['Post']['user_id'], 'post_id' => $this->data['Post']['id']), true, true);

                //creating new postuser entry for new associations for new topic
                $this->PostUser->create();
                //keep the old created date - to prevent the post to be more up to date
                $PostUserData['created'] = $this->data['Post']['created'];
                if(isset($this->data['Post']['topic_id']) && $this->data['Post']['topic_id'] != PostsController::NO_TOPIC_ID){
                    $PostUserData['topic_id'] = $this->data['Post']['topic_id'];
                }

                $this->PostUser->save($PostUserData);
            }

        }

        $this->addRoute();

        if($this->updateSolr){
            // update solr index with saved date
            $this->addToOrUpdateSolr();
        }

    }
    function addToOrUpdateSolr(){
        App::import('model','Solr');
        $this->contain('Route', 'User');
        $this->data = $this->read(null, $this->id);
      //  $this->User->contain();
       // $userData = $this->User->read(null, $this->data['Post']['user_id']);




        if($this->data['User']['id']){
            if(isset($this->data['Post']['topic_id'])){
                $this->Topic->contain();
                $topicData = $this->Topic->read(null, $this->data['Post']['topic_id']);

                if($topicData['Topic']['id'] && !empty($topicData['Topic']['name'])){
                    $this->data['Post']['topic_name'] = $topicData['Topic']['name'];
                }
            }


            if(!empty($this->data['Post']['image'])){
                $this->data['Post']['image'] = $this->generateSearchPreviewPicture($this->data['Post']['image']);
            }
            $this->data['Post']['index_id'] = Solr::TYPE_POST.'_'.$this->id;
            $this->data['Post']['id'] = $this->id;
            $this->data['Post']['user_name'] = $this->data['User']['name'];
            $this->data['Post']['user_username'] = $this->data['User']['username'];
            $this->data['Post']['user_id'] = $this->data['User']['id'];
            $this->data['Post']['type'] = Solr::TYPE_POST;
            $this->data['Post']['route_source'] = $this->data['Route'][0]['source'];

            $solr = new Solr();
            $solr->add($this->addFieldsForIndex($this->data));

        }
        else{
            $this->log('Error while reading user for Post! No solr index update');
        }
    }

    private function generateSearchPreviewPicture($images = null){
        if(!empty($images)){
            $images = unserialize($images);
            if(isset($images[0])){
                return serialize($images[0]);
            }
        }
    }

    /**
     * @todo move to abstract for all models
     * Enter description here ...
     */
    private function addFieldsForIndex($data){
        $solrFields = array();
        $solrFields['Post']['id'] = $data['Post']['id'];
        $solrFields['Post']['post_title'] = $data['Post']['title'];
        $solrFields['Post']['post_content'] = strip_tags($data['Post']['content']);
       // debug($data);
        if(isset($data['Post']['image'])){
            $solrFields['Post']['post_image'] = $data['Post']['image'];
        }
        if(isset($data['Post']['topic_name'])){
            $solrFields['Post']['topic_name'] = $data['Post']['topic_name'];
        }
        $solrFields['Post']['user_name'] = $data['Post']['user_name'];
        $solrFields['Post']['user_username'] = $data['Post']['user_username'];
        $solrFields['Post']['user_id'] = $data['Post']['user_id'];
        $solrFields['Post']['index_id'] = $data['Post']['index_id'];
        $solrFields['Post']['type'] = $data['Post']['type'];
        $solrFields['Post']['post_content_preview'] = $data['Post']['content_preview'];
        $solrFields['Post']['route_source'] =   $data['Post']['route_source'];
        return $solrFields;
    }


	function __construct(){
		parent::__construct();
		$this->validate = array(
			'title' => array(
				'empty' => array(
					'rule' 			=> 'notEmpty',
					'message' 		=> __('Please enter a Title.', true),
					'last' 			=> true,
				),
				'maxlength' => array(
					'rule'			=> array('maxlength', 100),
					'message'		=> __('Titles can only be 100 characters long.', true),
					'last' 			=> true,
				),
			),
			'content' => array(
				'empty' => array(
					'rule' 			=> 'checkForContent',
					'message' 		=> __('Please provide additional content: a picture, a video, a message or a link.', true),
					'last' 			=> true,
				),
			),
		);
}

    function checkForContent($data) {
        if(empty($data['content']) && empty($this->data['Post']['media']) && empty($this->data['Post']['links'])){
            return false;
        }
        return true;
    }


    function afterDelete(){
        $this->deleteFromSolr();
        return true;
    }



}

?>