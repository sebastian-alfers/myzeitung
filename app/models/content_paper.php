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
			'counterCache' => true,
            'counterScope' => array('ContentPaper.enabled' => true),
            'counterQuery' => 'DISTINCT ContentPaper.user_id'
			),
		'Category' => array(
			'className' => 'Category',
			'foreignKey' => 'category_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'counterCache' => true,
            'counterScope' => array('ContentPaper.enabled' => true),
			),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'counterCache' => true,
            'counterScope' => array('ContentPaper.enabled' => true),
			
			),
		'Topic' => array(
			'className' => 'Topic',
			'foreignKey' => 'topic_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'counterCache' => true,
            'counterScope' => array('ContentPaper.enabled' => true),
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

    public function afterSave($created){
        if($created && $this->data['ContentPaper']['enabled']){
            $this->getOldPostsOfNewSubscription();
        }
      //  debug($this->data);
        //$this->sendEmailToSubscribedAuthor();

    }

    /**
     * after content (user or topic) has been associated to to paper / category
     * add posts  and reposts from user or user-topic to CategoryPaperPost-index

     */
    public function getOldPostsOfNewSubscription(){
        App::import('model','PostUser');
        $this->PostUser = new PostUser();

        $conditions = array();
        if(isset($this->data['ContentPaper']['user_id']) && !empty($this->data['ContentPaper']['user_id'])){
            $conditions['PostUser.user_id']= $this->data['ContentPaper']['user_id'];
        }

        if(isset($this->data['ContentPaper']['topic_id']) && !empty($this->data['ContentPaper']['topic_id'])){
            $conditions['PostUser.topic_id'] = $this->data['ContentPaper']['topic_id'];
        }


        $this->PostUser->contain();
        $posts = $this->PostUser->find('all', array('conditions' => $conditions));
        App::import('model','CategoryPaperPost');
        $this->CategoryPaperPost = new CategoryPaperPost();

        foreach($posts as $post){
            $new_post = array();
            $new_post['CategoryPaperPost']['post_id'] = $post['PostUser']['post_id'];
            $new_post['CategoryPaperPost']['paper_id'] = $this->data['ContentPaper']['paper_id'];
            $new_post['CategoryPaperPost']['post_user_id'] = $post['PostUser']['id'];
            //very important to keep the postuser created date!!!
            $new_post['CategoryPaperPost']['created'] = $post['PostUser']['created'];
            $new_post['CategoryPaperPost']['content_paper_id'] = $this->id;
            if(isset($post['PostUser']['repost']) && $post['PostUser']['repost'] == true){
                //set the id and username of the reposter, if PostUser-Entry is just a repost
                $user = $this->User->read('username', $post['PostUser']['user_id']);
                $new_post['CategoryPaperPost']['reposter_id'] = $post['PostUser']['user_id'];
                $new_post['CategoryPaperPost']['reposter_username'] = $user['User']['username'];
            }
            //if(isset($this->data['ContentPaper']['category_id']) && !empty($this->data['ContentPaper']['category_id'])){
            $new_post['CategoryPaperPost']['category_id'] = $this->data['ContentPaper']['category_id'];
            //}
            $this->CategoryPaperPost->create();
            $this->CategoryPaperPost->save($new_post);

        }



    }

    function disable(){

        if($this->data['ContentPaper']['enabled'] == true){
            //disable association
            $this->data['ContentPaper']['enabled'] = false;
            $this->save($this->data);

            return true;
        }
        //already disabled
        return false;
    }
    function enable(){

        if($this->data['ContentPaper']['enabled'] == false){


            //enable association
            $this->data['ContentPaper']['enabled'] = true;
            $this->save($this->data);

            return true;
        }
        //already enabled
        return false;
    }
    function cleanUpIndex(){

        App::import('model','CategoryPaperPost');
        $this->CategoryPaperPost = new CategoryPaperPost();

        //get all ContentPaper entries and delete posts from the cpp index if user is disabled
        $this->contain('User.id','User.enabled');
        $subscriptions = $this->find('all', null);
        foreach($subscriptions as $subscription){
            if(!isset($subscription['User']['id']) || $subscription['User']['enabled'] == false){
                //if user is disabled or deleted - disable association
                $this->id = $subscription['ContentPaper']['id'];
                $this->data = $subscription;
                $this->disable();
                $this->CategoryPaperPost->deleteAll(array('content_paper_id' => $subscription['ContentPaper']['id']), false, true);
            }

        }
        //read all cppEntries grouped by content_paper_id
        $this->CategoryPaperPost->contain('ContentPaper.id','ContentPaper.enabled');
        $cppEntries = $this->CategoryPaperPost->find('all',array('fields' => array('content_paper_id'),'group' => array('content_paper_id')));
        foreach($cppEntries as $cppEntry){
            if(!isset($cppEntry['ContentPaper']['id']) || $cppEntry['ContentPaper']['enabled'] == false){
                $this->CategoryPaperPost->deleteAll(array('content_paper_id' => $cppEntry['CategoryPaperPost']['content_paper_id']), false, true);
            }
        }
        //read all cppEntries grouped by post_user_id
        $this->CategoryPaperPost->contain('PostUser.id', 'PostUser.enabled');
        $cppEntries = $this->CategoryPaperPost->find('all',array('fields' => array('post_user_id'),'group' => array('post_user_id')));
        foreach($cppEntries as $cppEntry){
            if(!isset($cppEntry['PostUser']['id']) || $cppEntry['PostUser']['enabled'] == false){
                $this->CategoryPaperPost->deleteAll(array('post_user_id' => $cppEntry['CategoryPaperPost']['post_user_id']), false, true);
            }
        }
    }
    function updateCounterCache($keys = array(), $created = false){
        $keys = empty($keys) ? $this->data[$this->alias] : $keys;
        debug('hier');

        //update user
        $count = $this->find('count',array('conditions' => array('ContentPaper.enabled' => true, 'ContentPaper.user_id' => $keys['user_id']),'fields' => 'distinct ContentPaper.paper_id'));
        $this->User->id = $keys['user_id'];
        $this->User->saveField('subscriber_count', $count, array('callbacks' => 0, 'validate' => 0));
    /*
        //update user
        $count_reposts = $this->find('count',array('conditions' => array('PostUser.enabled' => true,'repost' => true, 'PostUser.user_id' => $keys['user_id'])));
        $count_posts = $this->find('count',array('conditions' => array('PostUser.enabled' => true, 'repost' => false, 'PostUser.user_id' => $keys['user_id'])));
        $this->User->id = $keys['user_id'];
        $this->User->save(array('repost_count' => $count_reposts, 'post_count' => $count_posts),array('callbacks' => 0, 'validate' => 0, 'fieldList' => array('repost_count', 'post_count')));

        //update topic
        if(isset($keys['topic_id']) && !empty($keys['topic_id'])){
            $count_reposts = $this->find('count',array('conditions' => array('PostUser.enabled' => true,'repost' => true, 'PostUser.topic_id' => $keys['topic_id'])));
            $count_posts = $this->find('count',array('conditions' => array('PostUser.enabled' => true, 'repost' => false, 'PostUser.topic_id' => $keys['topic_id'])));
            $this->Topic->id = $keys['topic_id'];
            $this->Topic->save(array('repost_count' => $count_reposts, 'post_count' => $count_posts),array('callbacks' => 0, 'validate' => 0, 'fieldList' => array('repost_count', 'post_count')));

        }*/

    }
}
?>