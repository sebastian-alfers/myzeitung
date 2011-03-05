<?php
class Post extends AppModel {
	var $name = 'Post';
	var $displayField = 'title';

	const TEST = 666;

	var $actsAs = array(/*'Serializeable'/* => array('reposters' => 'reposters'),*/'Containable');

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
			'conditions' => '',
			'fields' => '',
			'order' => ''
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

			function afterFind($results) {
				foreach ($results as $key => $val) {
					if (!empty($val['Post']['reposters'])) {
						$results[$key]['Post']['reposters'] = unserialize($results[$key]['Post']['reposters']);
					}else {
						$results[$key]['Post']['reposters'] = array();
					}
				}
				return $results;
			}



			function beforeSave(&$Model) {
				if(!empty($this->data['Post']['reposters'])){
					$this->data['Post']['reposters'] = serialize($this->data['Post']['reposters']);
				}
				return true;
			}

		/**
		 * 1)
		 * update index with saved data
		 *
		 * 2)
		 * after a topic has been saved, it it hast do be added to CategoryPaperPost table
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
		function afterSave(){
			App::import('model','CategoryPaperPost');
			App::import('model','Solr');

			$userData = $this->User->read(null, $this->data['Post']['user_id']);

			if($userData['User']['id']){
				$topicData = $this->Topic->read(null, $this->data['Post']['topic_id']);
				if($topicData['Topic']['id'] && !empty($topicData['Topic']['name'])){
					$this->data['Post']['topic_name'] = $topicData['Topic']['name'];
				}
					
				$this->data['Post']['index_id'] = 'post_'.$this->id;
				$this->data['Post']['id'] = $this->id;
				$this->data['Post']['user_name'] = $userData['User']['name'];
				$solr = new Solr();
				$solr->add($this->removeFieldsForIndex($this->data));

				//debug($this->data);
				$this->CategoryPaperPost = new CategoryPaperPost();
				$post_id = $this->id;
				$topic_id = $this->data['Post']['topic_id'];
				$user_id = $this->data['Post']['user_id'];


				//now all references to whole user
				$wholeUserReferences = $this->User->getWholeUserReferences($user_id);
				foreach($wholeUserReferences as $wholeUserReference){
					//if($this->addPostToIndex($post_id, $wholeUserReference['Paper']['id'])){

					//}
						
					//place post in paper or category associated to the whole user
					$categoryPaperPostData = array('post_id' => $post_id, 'paper_id' => $wholeUserReference['Paper']['id']);
					if($wholeUserReference['Category']['id']){
						$categoryPaperPostData = array('category_id' => $wholeUserReference['Category']['id']);
					}
					$this->CategoryPaperPost->create();
					$this->CategoryPaperPost->save($categoryPaperPostData);
				}

				//now all references to all topics
				$topicReferences = $this->User->getUserTopicReferences($user_id);
				foreach($topicReferences as $topicReferences){
					debug($topicReferences);die();
					//place post in paper or category associated to the posts topic
					$categoryPaperPostData = array('post_id' => $post_id, 'paper_id' => $wholeUserReference['Paper']['id']);
					if($wholeUserReference['Category']['id']){
						$categoryPaperPostData = array('category_id' => $wholeUserReference['Category']['id']);
					}
					$this->CategoryPaperPost->create();
					$this->CategoryPaperPost->save($categoryPaperPostData);
				}

				//update index to associate paper->content / category->content

			}
			else{
				$this->debug('Error while reading user for Post! No solr index update and no post_index update');
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
		
		return $data;
	}


}





?>