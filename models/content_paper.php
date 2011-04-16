<?php
class ContentPaper extends AppModel {
	var $name = 'ContentPaper';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	const PAPER 	= 'paper';
	const CATEGORY 	= 'category';
	const USER 		= 'user';
	const TOPIC 	= 'topic';
	const CONTENT_DATA = 'content_data';

	//to concatinate for frontend e.g. category_#id (category_44)
	const SEPERATOR = '_';


	var $belongsTo = array(
		'Paper' => array(
			'className' => 'Paper',
			'foreignKey' => 'paper_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'counterCache' => true
			),
		'Category' => array(
			'className' => 'Category',
			'foreignKey' => 'category_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'counterCache' => true,
			),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'counterCache' => true,
			
			),
		'Topic' => array(
			'className' => 'Topic',
			'foreignKey' => 'topic_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'counterCache' => true,
			)
			);

			var $hasMany = array(
		'CategoryPaperPost' => array(
			'className' => 'CategoryPaperPost',
			'foreignKey' => 'content_paper_id',
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

			public function afterSave(){

				$this->updateIndex($this->data);
			}

			/**
			 * after content (user or post) as been associatet to to paper / category
			 * add posts from user/post to index
			 *
			 * @param array $data
			 */
			public function updateIndex($data){

				$data = $data['ContentPaper'];

				App::import('model','PostUser');
				$this->PostUser = new PostUser();
				$this->PostUser->contain();//no fields

				if(isset($data['user_id']) && !empty($data['user_id'])){
					$conditions = array('PostUser.user_id' => $data['user_id']);
				}

				if(isset($data['topic_id']) && !empty($data['topic_id'])){
					$conditions = array('PostUser.topic_id' => $data['topic_id']);
				}

				$posts = $this->PostUser->find('all', array('fields' => 'id, post_id' , 'conditions' => $conditions));


				App::import('model','PostUser');

				foreach($posts as $post){
					$this->CategoryPaperPost = new CategoryPaperPost();
					$new_posts = array();
					$new_posts['CategoryPaperPost']['post_id'] = $post['PostUser']['post_id'];
					$new_posts['CategoryPaperPost']['paper_id'] = $data['paper_id'];
					$new_posts['CategoryPaperPost']['post_user_id'] = $post['PostUser']['id'];
					$new_posts['CategoryPaperPost']['content_paper_id'] = $this->id;

					if(isset($data['category_id']) && !empty($data['category_id'])){
						$new_posts['CategoryPaperPost']['category_id'] = $data['category_id'];
					}
					$this->CategoryPaperPost->save($new_posts);
				}



			}
}
?>