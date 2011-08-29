<?php
class PostUser extends AppModel {
	var $name = 'PostUser';


	var $uses = array('Topic');

	var $useTable = 'posts_users';            /// changing to "abc" table works fine


	//The Associations below have been created with all possible keys, those that are not needed can be removed
var $belongsTo = array(
	'Post' => array(
		'className' => 'Post',
		'foreignKey' => 'post_id',
		'conditions' => '',
		'fields' => '',
		'order' => '',
		//counting just reposts
		'counterCache' => 'repost_count',
		'counterScope' => array('PostUser.repost' => 1,
                                'PostUser.enabled' => 1),
		),
	'User' => array(
		'className' => 'User',
		'foreignKey' => 'user_id',
		'conditions' => '',
		'fields' => '',
		'order' => '',
		//counting just reposts
		'counterCache' => 'repost_count',
		'counterScope' => array('PostUser.repost' => 1,
                             'PostUser.enabled' => 1),
		),


);

	var $hasMany = array(
		'CategoryPaperPost' => array(
			'className' => 'CategoryPaperPost',
			'foreignKey' => 'post_user_id',
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
		
			);		


			/**
			 * @author, Sebastian
			 * Part 1)
			 * after a post has been saved, it it has do be added to CategoryPaperPost table
			 * a post can come to this table bacause:
			 * - the posts user is associated to a paper/category or
			 * - the posts topic is associated to a paper/category
			 *
			 * so: this function does:
			 * 1. get all associations to the posts user (all posts by this user)
			 *  - this can be paper itself or one of its categories
			 *
			 * 2. get all associations to the posts topic
			 *  - this can be paper itself or one of its categories
			 *
			 * 3. validate collected data
			 *  - it is very important, that a paper (and his categories) containt the posts
			 *    only once!

			 *
			 */
			function afterSave($created){
					
				App::import('model','CategoryPaperPost');
				App::import('model','Topic');
				

				//Part 1 - Associations
				if(isset($this->data['PostUser']['PostUser.user_id'])){
					$this->data['PostUser']['user_id'] = $this->data['PostUser']['PostUser.user_id'];
				}
				$this->User->contain();
				$userData = $this->User->read(null, $this->data['PostUser']['user_id']);

				if($created){
					//$this->_publishPostInPaperAndCategories($userData);
                    $this->_publishInPapersAndCategories();

				}
				

			}
			/**
             * function to publish a post in papers:
             * if someones writes a post or reposts a posts, this function reads all user/topic subscriptions (content_papers) of the author of the post, or the reposter ( PostUser-user_id).
             * after that it writes a new record in category_paper_posts for each subscription (content_papers).
             */
				
            private function _publishInPapersAndCategories(){
                App::import('model','ContentPaper');
                $this->ContentPaper = new ContentPaper();
                //read all subscriptions for the PostUser entry
                //post posted into a topic:
                //post needs to be published everywhere, where the whole user (topic = null) or the specific topic is subscribed
                if(isset($this->data['PostUser']['topic_id']) && !empty($this->data['PostUser']['topic_id'])){
                    $conditions = array('OR' => array(
                        //user_id and topic=Null
                                                 array('user_id' => $this->data['PostUser']['user_id'],
                                                       'topic_id' => null),
                        // OR  user_id and topic
                                                array('user_id' => $this->data['PostUser']['user_id'],
                                                      'topic_id' =>$this->data['PostUser']['topic_id']),
                    ));
                }else{
                    //post not posted into a topic:
                    //post needs only to be  published everywhere, where the whole user (topic = null) subscribed
                    $conditions = array('user_id' => $this->data['PostUser']['user_id'],
                                        'topic_id' => null);
                }
                $this->ContentPaper->contain();
                $subscriptions = $this->ContentPaper->find('all',array('conditions' => $conditions));

                //publish post in each relevant paper or category
                foreach($subscriptions as $subscription){
                    //set CategoryPaperPost data
                    $CategoryPaperPostData['post_id'] = $this->data['PostUser']['post_id'];
                    $CategoryPaperPostData['post_user_id'] = $this->id;
                    $CategoryPaperPostData['content_paper_id'] = $subscription['ContentPaper']['id'];
                    $CategoryPaperPostData['paper_id'] = $subscription['ContentPaper']['paper_id'];
                    $CategoryPaperPostData['category_id'] = $subscription['ContentPaper']['category_id'];
                    //very important! : keep the posts_user created date
                    $CategoryPaperPostData['created'] = $this->data['PostUser']['created'];
                    if(isset($this->data['PostUser']['repost']) && $this->data['PostUser']['repost'] == true){
                        //set the id and username of the reposter, if PostUser-Entry is just a repost
                        $user = $this->User->read('username', $this->data['PostUser']['user_id']);
                        $CategoryPaperPostData['reposter_id'] = $this->data['PostUser']['user_id'];
						$CategoryPaperPostData['reposter_username'] = $user['User']['username'];
                    }
                    $this->CategoryPaperPost->create();
                    $this->CategoryPaperPost->save($CategoryPaperPostData);
                }
            }
            private function _unpublishFromPapersOrCategories(){
                App::import('model','CategoryPaperPost');
                $this->CategoryPaperPost = new CategoryPaperPost();

                $this->CategoryPaperPost->contain();
                $this->CategoryPaperPost->deleteAll(array('post_user_id' => $this->id),false, true);

            }

				/*
			private function _publishPostInPaperAndCategories($userData){


                App::import('model','User');
				// arrays for Papers and Categories the new post / repost goes into. Needed to know which post-counters have to be updated afterwards.
				$affectedPapers = array();
				$affectedCategories = array();
				
				$this->User = new User();
				if($userData['User']['id']){

					$this->CategoryPaperPost = new CategoryPaperPost();
					$post_id = $this->data['PostUser']['post_id'];
					

					
					$topic_id = null;
					if(isset($this->data['PostUser']['topic_id'])){
						$topic_id = $this->data['PostUser']['topic_id'];
					}
					
					$user_id = $this->data['PostUser']['user_id'];
					
					$reposter_id = null;
					$reposter_username = null;
 					if(isset($this->data['PostUser']['repost']) && $this->data['PostUser']['repost'] == true){
						$reposter_id = $user_id;
						$this->User->Contain();
						$user = $this->User->read('username', $user_id);
						$reposter_username = $user['User']['username'];
					}

					//now all references to whole user
					$wholeUserReferences = $this->User->getWholeUserReferences($user_id);

                    foreach($wholeUserReferences as $wholeUserReference){
							
						//place post in paper or category associated to the whole user
						$categoryPaperPostData = array('created' => $this->data['PostUser']['created'], 'post_id' => $post_id, 'paper_id' => $wholeUserReference['Paper']['id'], 'post_user_id' => $this->id, 'content_paper_id' => $wholeUserReference['ContentPaper']['id'], 'reposter_id' => $reposter_id, 'reposter_username' => $reposter_username);
						
						$affectedPapers[$wholeUserReference['Paper']['id']] = '';
						
						if($wholeUserReference['Category']['id']){
							$categoryPaperPostData['category_id'] = $wholeUserReference['Category']['id'];
							$affectedCategories[$wholeUserReference['Category']['id']] = '';
						}
							
						$this->CategoryPaperPost->create();
                        
						$this->CategoryPaperPost->save($categoryPaperPostData);
					}

					//@ todo sebastian -> refactor -> alles referenzen zu dem topic laden (wenn vorhanden) und dann post in index schreiben
					//now all references to all topics
					$topicReferences = $this->User->getUserTopicReferences($user_id);
                    $this->log('topicref');
                    $this->log($topicReferences);
					if(isset($this->data['PostUser']['topic_id'])){
						foreach($topicReferences as $topicReference){
								
                            $this->log($topicReference['Topic']['id']);

                            $this->log($this->data['PostUser']['topic_id']);
                            
							if($topicReference['Topic']['id'] != $this->data['PostUser']['topic_id']){
								//check, if current topic-reference is equal to the topic, the post has been placed in
                                continue;
							}
							//if($categoryPaperPostData[''])
							//place post in paper or category associated to the posts topic
								
							$categoryPaperPostData = array('created' => $this->data['PostUser']['created'], 'post_id' => $post_id, 'paper_id' => $topicReference['Paper']['id'], 'post_user_id' => $this->id, 'content_paper_id' => $topicReference['ContentPaper']['id'], 'reposter_id' => $reposter_id, 'reposter_username' => $reposter_username);
							
							$affectedPapers[$topicReference['Paper']['id']] = '';
							
							if(isset($topicReference['Category']['id']) && !empty($topicReference['Category']['id'])){
                                $this->log('AWESOME');
								$categoryPaperPostData['category_id'] = $topicReference['Category']['id'];
								$affectedCategories[$topicReference['Category']['id']] = '';
							}
								
								
								
							$this->CategoryPaperPost->create();
                           
                            $this->log($categoryPaperPostData);
							$this->CategoryPaperPost->save($categoryPaperPostData);
						}
					}

					//update index to associate paper->content / category->content


					
					//updating category_paper_post_counters of all papers and categores the post was added too in category_paper_post table (a post counts only once per paper or category - a post and several reposts of the same post are counted as one. )
					foreach($affectedCategories as $key => $value){
						App::import('model','Category');
						$this->Category = new Category();
						//counting distinct posts
						$this->CategoryPaperPost->contain();
						$counter_result = $this->CategoryPaperPost->find('all', array('fields' =>array('COUNT(DISTINCT(post_id)) as category_paper_post_count'),'conditions' => array('category_id' => $key)));
						$categoryData['Category']['id'] = $key;
						$categoryData['Category']['category_paper_post_count'] = $counter_result[0][0]['category_paper_post_count'];
						//saving category	
						$this->Category->save($categoryData);
					}
					foreach($affectedPapers as $key => $value){
						App::import('model','Paper');
						$this->Paper = new Paper();
						//counting distinct posts
						$this->CategoryPaperPost->contain();
						$counter_result = $this->CategoryPaperPost->find('all', array('fields' =>array('COUNT(DISTINCT(post_id)) as category_paper_post_count'),'conditions' => array('paper_id' => $key)));
						$paperData['Paper']['id'] = $key;
						$paperData['Paper']['category_paper_post_count'] = $counter_result[0][0]['category_paper_post_count'];
						//saving paper
						$this->Paper->doAfterSave = false;
						$this->Paper->save($paperData);
					}
					
			
					
					
					
				}
				else{
					$this->debug('Error while reading user for Post!');
				}
			} */
				
    function __construct(){
        parent::__construct();
    }
    function disable(){

        if($this->data['PostUser']['enabled'] == true){
            //disable subscription
            $this->data['PostUser']['enabled'] = false;
            $this->_unpublishFromPapersOrCategories();
            $this->save($this->data);
            return true;
        }
        //already disabled
        return false;
    }
    function enable(){

        if($this->data['PostUser']['enabled'] == false){


            //disable subscription
            $this->data['PostUser']['enabled'] = true;
            $this->_publishInPapersAndCategories();
            $this->save($this->data);

            return true;
        }
        //already enabled
        return false;
    }
    /**
     * groups all postuser entries by post_id
     *      check if posts exists and is enabled. delete or disable if post is not existent or disabled
     * reads all enabled posts and checks if there is an active (non-repost) post user entry.
     * and collects all reposts
     *       if no entry is found, one is created
     * saves post with new reposters array
     */
    function cleanUpIndex(){

        //read all posts_users entries grouped by post_id
        $this->contain('Post');
        $publishedPosts = $this->find('all',array('group' => 'post_id','order' => 'post_id'));

        foreach($publishedPosts as $publishedPost){
            if(!isset($publishedPost['Post']['id'])){
                //post has been deleted
                //delete all post user entries belonging to deleted post
                $this->deleteAll(array('post_id' => $publishedPost['PostUser']['post_id']), true, true);
            }elseif($publishedPost['Post']['enabled'] == false){
                //post has been disabled
                //disable all postUser entries belonging to disabled post
                $entriesToDisable = $this->find('all', array('conditions' => array('post_id' => $publishedPost['PostUser']['post_id'])));
                foreach($entriesToDisable as $entryToDisable){
                    $this->id = $entryToDisable['PostUser']['id'];
                    $this->data = $entryToDisable;
                    $this->disable();
                }
            }

        }
        App::import('model','Post');
        $this->Post = new Post();
        $this->Post->contain('PostUser');
        $posts = $this->Post->find('all', array('conditions' => array('Post.enabled' => true)));


        foreach($posts as $post){
            //check if active non-repost post-user entry is found
            //collect all reposts to renew the reposters array (just in case...)
                $activePostUserEntryFound = false;
                $reposters = array();
                if(isset($post['PostUser']) && count($post['PostUser']) >0){
                    foreach($post['PostUser'] as $postUserEntry){
                        if($postUserEntry['repost'] == false){
                            if($postUserEntry['enabled'] == false){
                                $this->id = $postUserEntry['id'];
                                $this->data = array('PostUser' => $postUserEntry);
                                $this->enabled();
                            }
                            $activePostUserEntryFound = true;
                        }else{
                            //save reposter to temp reposters array
                            $reposters[] = $postUserEntry['user_id'];
                        }
                    }
                }
            //write a post-user entry for the post (not a repost) if none was found
            if(!$activePostUserEntryFound){
                $postUserData['PostUser']['post_id'] = $post['Post']['id'];
                $postUserData['PostUser']['topic_id'] = $post['Post']['topic_id'];
                $postUserData['PostUser']['user_id'] = $post['Post']['user_id'];
                $postUserData['PostUser']['enabled'] = true;
                $postUserData['PostUser']['repost'] = false;
                $postUserData['PostUser']['created'] = $post['Post']['created'];
                $this->save($postUserData);
            }
            if(count($reposters) > 0){
                $this->log($reposters);
            }
            $reposters = serialize($reposters);

            //renew the reposters array if necessary
            if($reposters != $post['Post']['reposters']){
                $post['Post']['reposters'] = $reposters;
                $this->Post->save($post);
            }

        }

    }
}
?>