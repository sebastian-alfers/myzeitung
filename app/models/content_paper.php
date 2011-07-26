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
    //    debug($this->data);
     //   $this->sendEmailToSubscribedAuthor($this->data);

    }

    /**
     * after content (user or topic) has been associatet to to paper / category
     * add posts from user/topics to index
     *
     * @param array $data
     */
    public function updateIndex($data){
        App::import('model','PostUser');
        $this->PostUser = new PostUser();

        $conditions = array();
        if(isset($data['ContentPaper']['user_id']) && !empty($data['ContentPaper']['user_id'])){
            $conditions['PostUser.user_id']= $data['ContentPaper']['user_id'];
        }

        if(isset($data['ContentPaper']['topic_id']) && !empty($data['ContentPaper']['topic_id'])){
            $conditions['PostUser.topic_id'] = $data['ContentPaper']['topic_id'];
        }


        $this->PostUser->contain();
        $posts = $this->PostUser->find('all', array('conditions' => $conditions));

       debug($posts);
        App::import('model','CategoryPaperPost');

        foreach($posts as $post){
            $this->CategoryPaperPost = new CategoryPaperPost();
            $new_posts = array();
            $new_posts['CategoryPaperPost']['post_id'] = $post['PostUser']['post_id'];
            $new_posts['CategoryPaperPost']['paper_id'] = $data['ContentPaper']['paper_id'];
            $new_posts['CategoryPaperPost']['post_user_id'] = $post['PostUser']['id'];
            //very important to keep the postuser created date!!!
            $new_posts['CategoryPaperPost']['created'] = $post['PostUser']['created'];
            $new_posts['CategoryPaperPost']['content_paper_id'] = $this->id;
            if(isset($post['PostUser']['repost']) && $post['PostUser']['repost'] == true){
                debug('repost');
                //set the id and username of the reposter, if PostUser-Entry is just a repost
                $user = $this->User->read('username', $post['PostUser']['user_id']);
                $new_posts['CategoryPaperPost']['reposter_id'] = $post['PostUser']['user_id'];
                $new_posts['CategoryPaperPost']['reposter_username'] = $user['User']['username'];
            }
            //if(isset($data['ContentPaper']['category_id']) && !empty($data['ContentPaper']['category_id'])){
            $new_posts['CategoryPaperPost']['category_id'] = $data['ContentPaper']['category_id'];
            //}
            debug($new_posts);
            $this->CategoryPaperPost->save($new_posts);
        }



    }
}
?>