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
		'counterCache' => true,
		'counterScope' => array('repost' => true)			
		),
	'User' => array(
		'className' => 'User',
		'foreignKey' => 'user_id',
		'conditions' => '',
		'fields' => '',
		'order' => '',
		//counting just reposts
		'counterCache' => true,
		'counterScope' => array('repost' => true)
		)

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
					$this->_publishPostInPaperAndCategories($userData);	
				}
				

			}
				
				


				
			private function _publishPostInPaperAndCategories($userData){
				App::import('model','User');
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
						$categoryPaperPostData = array('post_id' => $post_id, 'paper_id' => $wholeUserReference['Paper']['id'], 'post_user_id' => $this->id, 'content_paper_id' => $wholeUserReference['ContentPaper']['id'], 'reposter_id' => $reposter_id, 'reposter_username' => $reposter_username);
						
						if($wholeUserReference['Category']['id']){
							$categoryPaperPostData['category_id'] = $wholeUserReference['Category']['id'];
						}
							
						$this->CategoryPaperPost->create();
						$this->CategoryPaperPost->save($categoryPaperPostData);
					}

					//@ todo sebastian -> refactor -> alles referenzen zu dem topic laden (wenn vorhanden) und dann post in index schreiben
					//now all references to all topics
					$topicReferences = $this->User->getUserTopicReferences($user_id);


					foreach($topicReferences as $topicReference){
							
						if($topicReference['Topic']['id'] != $this->data['PostUser']['topic_id']){
							//check, if current topic-reference is equal to the topic, the post has been placed in
							continue;
						}
						//if($categoryPaperPostData[''])
						//place post in paper or category associated to the posts topic
							
						$categoryPaperPostData = array('post_id' => $post_id, 'paper_id' => $topicReference['Paper']['id'], 'post_user_id' => $this->id, 'content_paper_id' => $topicReference['ContentPaper']['id'], 'reposter_id' => $reposter_id, 'reposter_username' => $reposter_username);
						
						if($topicReference['Category']['id']){
							$categoryPaperPostData['category_id'] = $topicReference['Category']['id'];
						}
							
							
							
						$this->CategoryPaperPost->create();
						$this->CategoryPaperPost->save($categoryPaperPostData);
					}

					//update index to associate paper->content / category->content

				}
				else{
					$this->debug('Error while reading user for Post!');
				}
			}
				
			function __construct(){
				parent::__construct();
			}
}
?>