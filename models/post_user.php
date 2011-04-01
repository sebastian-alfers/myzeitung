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
			'order' => ''
			),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
			)

			);



			/**
			 * @author, Sebastian
			 * Part 1)
			 * after a topic has been saved, it it has do be added to CategoryPaperPost table
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
			 * @todo part2
			 * @author Tim
			 * Part 2)
			 * counting the Posts/Reposts of a User and writing it to the User's counter variables
			 *
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
				

				//Part 2 - Post-Counter / Repost-Counter
				if($created){
					//uses userdate of read in part1
					//@todo if redudant : in aftersave and beforedelete
					if($userData['User']['id']){
						//count users reposts
						$userData['User']['count_reposts'] = $this->find('count',array('conditions' => array('PostUser.user_id' => $userData['User']['id'], 'repost' => true)));
						//count users posts and reposts as a sum
						$userData['User']['count_posts_reposts'] = $this->find('count',array('conditions' => array('PostUser.user_id' => $userData['User']['id'])));
						$this->User->save($userData);
					}

				}
			}
				
				
			function beforeDelete(){
				$this->contain();
				$this->data = $this->read(null, $this->id);
				//reading user
				$this->User->contain();
				$userData = $this->User->read(null, $this->data['PostUser']['user_id']);
				//@todo if redudant : in aftersave and beforedelete
				if($userData['User']['id']){
					//count users reposts (-1 + count, because the count is executed BEFORE the delete)
					$userData['User']['count_reposts'] = -1 + $this->find('count',array('conditions' => array('PostUser.user_id' => $userData['User']['id'], 'repost' => true)));
					//count users posts and reposts as a sum (-1 + count, because the count is executed BEFORE the delete)
					$userData['User']['count_posts_reposts'] = -1 + $this->find('count',array('conditions' => array('PostUser.user_id' => $userData['User']['id'])));
					$this->User->save($userData);
				}
				return true;

			}
				
			private function _publishPostInPaperAndCategories($userData){
				if($userData['User']['id']){

					$this->CategoryPaperPost = new CategoryPaperPost();
					$post_id = $this->data['PostUser']['post_id'];

					$topic_id = null;
					if(isset($this->data['PostUser']['topic_id'])){
						$topic_id = $this->data['PostUser']['topic_id'];
					}

					$user_id = $this->data['PostUser']['user_id'];

					//now all references to whole user
					$wholeUserReferences = $this->User->getWholeUserReferences($user_id);


					foreach($wholeUserReferences as $wholeUserReference){
							
						//place post in paper or category associated to the whole user
						$categoryPaperPostData = array('post_id' => $post_id, 'paper_id' => $wholeUserReference['Paper']['id'], 'post_user_id' => $this->id, 'content_paper_id' => $wholeUserReference['ContentPaper']['id']);
						
						if($wholeUserReference['Category']['id']){
							$categoryPaperPostData['category_id'] = $wholeUserReference['Category']['id'];
						}
							
						$this->CategoryPaperPost->create();
						$this->CategoryPaperPost->save($categoryPaperPostData);
					}

					//now all references to all topics
					$topicReferences = $this->User->getUserTopicReferences($user_id);


					foreach($topicReferences as $topicReference){
							
						if($topicReference['Topic']['id'] != $this->data['PostUser']['topic_id']){
							//check, if current topic-reference is equal to the topic, the post has been placed in
							continue;
						}
						//if($categoryPaperPostData[''])
						//place post in paper or category associated to the posts topic
							
						$categoryPaperPostData = array('post_id' => $post_id, 'paper_id' => $topicReference['Paper']['id'], 'post_user_id' => $this->id, 'content_paper_id' => $topicReference['ContentPaper']['id']);
						
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