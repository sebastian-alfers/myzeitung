<?php
class PapersController extends AppController {

	var $name = 'Papers';
	var $components = array('Auth', 'Session', 'Papercomp', 'Upload');
	var $uses = array('Paper', 'Subscription', 'Category', 'Route', 'User', 'ContentPaper', 'Topic', 'CategoryPaperPost');
	var $helpers = array('Text' ,'Time', 'Image', 'Html', 'Javascript', 'Ajax', 'Reposter');

	var $allowedSettingsActions = array('image');

	//var $test = 'adsf';


	public function beforeFilter(){
		parent::beforeFilter();
		//declaration which actions can be accessed without being logged in
		$this->Auth->allow('index','view');

		//add security
		//$this->Security->requireAuth('add', 'edit', 'saveImage');

		$this->Security->disableFields = array('new_image', 'data[Paper][id]', 'id', 'data[Paper][hash]', 'hash');

	}

	function index() {

		$this->paginate = array(
		 	 'Paper' => array(
		//fields
	          			 'fields' => array('id', 'image', 'owner_id','title','description','created','subscription_count', 'content_paper_count', 'category_paper_post_count'),
		//limit of records per page
			            'limit' => 12,
		//order
	     		        'order' => 'Paper.title ASC',
		//contain array: limit the (related) data and models being loaded per paper
			             'contain' => array('User.id', 'User.image', 'User.username', 'User.name'),
		)
		);



		$papers = 	$this->paginate($this->Paper);
		//add temp variable to papers array: subscribed = true, if user is logged in and has already subscribed the paper
		// @todo !! REDUNDANT users_subscriptions and papers index -> build a component or something like that for this
		if(is_array($papers)){
			for($i = 0; $i < count($papers); $i++){
				$papers[$i]['Paper']['subscribed'] = false;
				if($this->Auth->user('id')){
					//check for subscriptions - if yes -> subscribed = true
					$this->Subscription->contain();
					if(($this->Subscription->find('all', array('conditions' => array('Subscription.user_id' => $this->Auth->user('id'),'Subscription.paper_id' => $papers[$i]['Paper']['id'])))) > 0){
						$papers[$i]['Paper']['subscribed'] = true;
					}
				}
			}
		}
		$this->set('papers', $papers);
	}


	/**
	 * @author Tim
	 * Action for viewing and browse a papers content.
	 * @param $paper_id
	 * @param $category_id
	 */
	function view($paper_id = null, $category_id = null) {
		if (!$paper_id) {
			$this->Session->setFlash(__('Invalid paper', true));
			$this->redirect(array('action' => 'index'));
		}
		/*writing all settings for the paginate function.
		 important here is, that only the paper's posts are subject for pagination.*/
		// PROBLEM: tried to only show every post once. if a post is reposted, the newest date counts,
		$this->paginate = array(
			        'Post' => array(
		//setting up the joins. this conditions describe which posts are gonna be shown
			            'joins' => array(
		array(
				                 		'table' => 'category_paper_posts',
				                 		'alias' => 'CategoryPaperPost',
				                		'type' => 'RIGHT',
				                  		'conditions' => array('CategoryPaperPost.post_id = Post.id'),
		),
		),
			
		//order
			        	'order' => 'last_post_repost_date DESC',
	          			'group' => array('CategoryPaperPost.post_id'),
		//limit of records per page
			          	'limit' => 9,
			
  						'fields' => array('Post.*', 'MAX(CategoryPaperPost.created) as last_post_repost_date', 'CategoryPaperPost.reposter_id', 'CategoryPaperPost.id'),
  						'conditions' => array('CategoryPaperPost.paper_id' => $paper_id),

		//contain array: limit the (related) data and models being loaded per post
			            'contain' => array('User.id','User.username','User.name',  'User.image'),
		),
		);

		//useCustom defines which kind of paginateCount the Post-Model should use. "true" -> counting entries in category_paper_posts
		$this->Paper->Post->useCustom = true;

		if($category_id != null){
			//adding the category to the conditions array for the pagination - join
			$this->paginate['Post']['conditions']['CategoryPaperPost.category_id'] = $category_id;
		}
		$posts = $this->paginate($this->Paper->Post);

		// finding the last relevant reposter for the post in a paper / category
		$conditions = array('paper_id' => $paper_id);
		if($category_id != null){
			//adding the category to the conditions array
			$conditions['category_id'] = $category_id;
		}
		foreach($posts as &$post){
			$conditions['post_id'] = $post['Post']['id'];
			$this->CategoryPaperPost->contain();
			$last_relevant_post = $this->CategoryPaperPost->find('first',array('order' => 'created DESC', 'conditions' => $conditions, 'fields' => array('reposter_id', 'reposter_username')));
			$post['lastReposter']['id'] = $last_relevant_post['CategoryPaperPost']['reposter_id'];
			$post['lastReposter']['username'] = $last_relevant_post['CategoryPaperPost']['reposter_username'];
            if($post['lastReposter']['id']){
                $this->User->contain();
                $tempUser = $this->User->read('name',$post['lastReposter']['id']);
                $post['lastReposter']['name'] = $tempUser['User']['name'];
            }
		}
		// END - last relevant reposter
		 
		$this->Paper->contain('User.id', 'User.username', 'User.image', 'Category.content_paper_count', 'Category.name', 'Category.id', 'Category.category_paper_post_count');
		$paper = $this->Paper->read(null, $paper_id);
		//add information if the user (if logged in) has already subscribed the paper
		$this->Subscription->contain();
		if($this->Auth->user('id') && ($this->Subscription->find('count', array('conditions' => array('Subscription.user_id' => $this->Auth->user('id'),'Subscription.paper_id' => $paper['Paper']['id'])))) > 0){
			$paper['Paper']['subscribed'] = true;
		}else{
			$paper['Paper']['subscribed'] = false;
		}

		$this->set('hash', $this->Upload->getHash());

		$this->set('paper', $paper);
		$this->set('posts', $posts);


	}

	function saveImage(){


		if (empty($this->data)) {
			$this->Session->setFlash(__('Error while uploading photo', true));
			$this->redirect($this->referer());
		}

		$paper_id = $this->data['Paper']['id'];

		//check is user owns this paper id
		if(parent::canEdit('Paper', $paper_id, 'owner_id')){
			$this->log('can edit');
			$hash = $this->data['Paper']['hash'];
			if($this->Upload->hasImagesInHashFolder($hash)){
				$this->log('has in hash');
				$image = array();
				$this->Paper->contain();
				$paper_data = $this->Paper->read(null, $paper_id);
				$paper_created = $paper_data['Paper']['created'];
				$image = $this->Upload->copyImagesFromHash($hash, $paper_id, $paper_created, $this->data['Paper']['new_image'], 'paper');

				if(is_array($image)){

					$paper_data['Paper']['image'] = $image;
					$this->Paper->doAfterSave = true;
					if($this->Paper->save($paper_data, true, array('image'))){
						$this->Session->setFlash(__('Image has been saved', true), 'default', array('class' => 'success'));
						$this->User->updateSolr = true;
						$this->redirect($this->referer());
					}
					else{
						$this->Session->setFlash(__('Could not save paper picture, please try again.', true));
						$this->redirect($this->referer());
					}
				}
			}
			else{
				$this->Session->setFlash(__('Could not save paper picture, please try again.', true));
				$this->redirect($this->referer());
			}

		}
		else{
			$this->Session->setFlash(__('You do not have permissions', true));
			$this->redirect($this->referer());
		}






	}

	/**
	 * @author tim
	 * Function for a user to subscribe a paper.
	 * @param int $paper_id - paper to subscribe
	 */
	function subscribe($paper_id){
		if(isset($paper_id)){

			$this->Paper->contain();
			if($this->Paper->read(null, $paper_id)){

				if($this->Paper->subscribe($this->Auth->user('id'))){
					$this->Session->setFlash(__('Subscribed successfully.', true), 'default', array('class' => 'success'));
				} else {
					$this->Session->setFlash(__('Could not subscribe', true));
				}
			} else {
				$this->Session->setFlash(__('Invalid paper id', true));
			}
		}
		else {
			// no paper $id
			$this->Session->setFlash(__('No paper id', true));
		}
		$this->redirect($this->referer());
	}

	/**
	 * @autohr Tim
	 * Function for a user to unsubscribe a paper.
	 * @param int $paper_id
	 */
	function unsubscribe($paper_id){
		if(isset($paper_id)){

			$this->Paper->contain();
			if($this->Paper->read(null, $paper_id)){

				if($this->Paper->unsubscribe($this->Auth->user('id'))){
					$this->Session->setFlash(__('Unsubscribed successfully.', true), 'default', array('class' => 'success'));
				} else {
					$this->Session->setFlash(__('Could not unsubscribe', true));
				}
			} else {
				$this->Session->setFlash(__('Invalid paper id', true));
			}
		}
		else {
			// no paper $id
			$this->Session->setFlash(__('No paper id', true));
		}
		$this->redirect($this->referer());
	}


	/**
	 * view controller to show references for a paper or a category
	 *
	 * @author sebastian.alfers
	 */
	function references($type, $id, $category_id = null){

		if(!$this->_validateShowContentData($this->params)){
			$this->Session->setFlash(__('invalid data for content view ', true));
			$this->redirect(array('action' => 'index'));
		}
		else{

            //check, if a user is requested to be deleted from references
            if($this->_getContentPaperDeleteId()){
                $content_paper_id = $this->_getContentPaperDeleteId();

                //check, if the logged in user owns the paper
                if(parent::canEdit('Paper', $id, 'owner_id')){
                    //delete the subscription
                    $this->log($content_paper_id);

                    $this->ContentPaper->delete($content_paper_id, true);
                }
                else{
                    $this->log('controller/papers/references: a user, who is not the owner of the paper with id ' . $id . ', wants to delete a subscribed user (ContentPaper) with id ' . $content_paper_id);
                }


            }


			$this->Paper->contain();
			$this->Paper->read(null, $id);

			$references = array();
			$references = $this->Paper->getContentReferences($category_id);

			$this->set('references', $references);

		}

		$this->render('references', 'ajax');//custom ctp, ajax for blank layout

	}

    /**
     * to remove a user that is subscribed into a paper,
     * the owner can do this.
     *
     * this methos checks, if a user should be removed from the subscriber list
     *
     * @return boolean|int
     */
    private function _isDelteContentPaperRequeset(){
        return (isset($this->params['form']['content_paper_id']) && !empty($this->params['form']['content_paper_id']));
    }

    /**
     * get the id of the user, that should be removed from subscriber list
     *
     * @return bool|int
     */
    private function _getContentPaperDeleteId(){
        if($this->_isDelteContentPaperRequeset()){
            return $this->params['form']['content_paper_id'];
        }

        return false;

    }

	/**
	 * returns true if all data are valid
	 */
	private function _validateShowContentData($data){
		if (empty($data)) return false;

		//only paper or category dadta
		if(!in_array($data['pass'][0], array(ContentPaper::PAPER, ContentPaper::CATEGORY))) return false;

		return true;
	}

	/**
	 * controller to add content asociaation for:
	 * - paper / category / subcategory of category
	 *
	 * Enter description here ...
	 */
	function addcontent(){

		if (!empty($this->data)) {

			//moved to users_controller

		}
		else{
			//check if paper id is as param
			if(empty($this->params['pass'][1]) || !isset($this->params['pass'][1])){
				//no param for category
				$this->Session->setFlash(__('No param for paper', true));
				$this->redirect(array('action' => 'index'));
			}
			if(!$this->Paper->isValidTargetType($this->params['pass'][0]) || !isset($this->params['pass'][1])){
				$this->Session->setFlash(__('Wrong type do add for', true));
				$this->redirect(array('action' => 'index'));
			}
			//type for content for hidden field
			$this->set('target_type', $this->params['pass'][0]);

			//id for content for hidden field, associated to target_type
			$this->set('target_id', $this->params['pass'][1]);

			//generated user data for select drop down
			$content_data = $this->_generateUserSelectData();
			$this->set(ContentPaper::CONTENT_DATA, $content_data);
		}
	}


	function add() {
		if (!empty($this->data)) {
			//adding a route

			$this->Paper->create();
			$this->data['Paper']['owner_id'] = $this->Auth->User("id");
			$this->Paper->doAfterSave = true;
			if ($this->Paper->save($this->data)) {
		/*		$routeData = array('Route' => array(
									'source' => $this->data['Paper']['title'],
									'ref_id' => $this->Paper->id,
									'target_controller' => 'papers',
									'target_action' => 'view',
									'target_param' => $this->Paper->id
				));
            */
			//	$route = $this->Route->save($routeData);

					
			//	if(!empty($route)){
					$this->Session->setFlash(__('The paper has been saved', true), 'default', array('class' => 'success'));
					$this->redirect(array('controller' => 'papers', 'action' => 'view', $this->Paper->id));
			//	}
			//	else{
					$this->Session->setFlash(__('Paper saved, error wile saving the paper route', true));
			//	}
			} else {
				$this->Session->setFlash(__('The paper could not be saved. Please, try again.', true));
			}




		}
		//unbinding irrelevant relations for the query
		$this->User->contain('Topic.id', 'Topic.name', 'Topic.post_count', 'Paper.id' , 'Paper.title', 'Paper.image');
		$this->set('user', $this->User->read(array('id','name','username','created','image' ,'posts_user_count','post_count','comment_count', 'content_paper_count', 'subscription_count', 'paper_count', 'allow_messages'), $this->Session->read('Auth.User.id')));
		$papers = $this->paginate($this->User->Paper);
		//same template for add and edit
		$this->render('add_edit');
	}


	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid paper', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			$this->Paper->doAfterSave = true;
			if ($this->Paper->save($this->data)) {
				$this->Session->setFlash(__('The paper has been saved', true), 'default', array('class' => 'success'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The paper could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Paper->read(null, $id);
			$this->set('owner_id', $this->data['Paper']['owner_id']);
		}
		//unbinding irrelevant relations for the query
		$this->User->contain('Topic.id', 'Topic.name', 'Topic.post_count', 'Paper.id' , 'Paper.title', 'Paper.image');
		$this->set('user', $this->User->read(array('id','name','username','created','image' ,'posts_user_count','post_count','comment_count', 'content_paper_count', 'subscription_count', 'paper_count', 'allow_messages'), $this->Session->read('Auth.User.id')));
		$papers = $this->paginate($this->User->Paper);
		//same template for add and edit
		$this->render('add_edit');
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for paper', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Paper->delete($id, true)) {
			$this->Session->setFlash(__('Paper deleted', true), 'default', array('class' => 'success'));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Paper was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}

	/**
	 * wrapper action for differend actions
	 * @param  $path - action to be called
	 * @param  $paper_id
	 * @return void
	 */
	public function settings($path, $paper_id){
		if (!$paper_id || !in_array($path, $this->allowedSettingsActions)) {
			$this->Session->setFlash(__('Invalid paper', true));
			$this->redirect($this->referer());
		}
		//check if paper belongs to logged in user
		if(!parent::canEdit('Paper', $paper_id, 'owner_id')){
			$this->Session->setFlash(__('Wrong permissions', true));
			$this->redirect($this->referer());
		}
		switch ($path){
			case "image":
				$this->settingsImage();
				break;

				echo $path;
				echo $paper_id;
		}

		die();

	}

	/**
	 * process image save action
	 * @return void
	 */
	private function settingsImage(){
		echo "save image";
	}


	/**
	 * build data for dropdown so select user or topic
	 * to as reference for paper_content
	 *
	 * @return array()
	 */
	private function _generateUserSelectData(){
		//moved to users_controller
	}

	/**
	 *
	 * content for a paper
	 *
	 * @param string $sourceType
	 * @param int $sourceId
	 * @param int $targetId
	 */
	public function newContentForCategory($sourceType, $sourceId, $targetId){
		//this->_saveNewContent(paper, ....)
		$this->data['ContentPaper'] = array();
		$this->data['ContentPaper']['source_type'] = $sourceType;
		$this->data['ContentPaper']['source_id'] = $sourceId;
		$this->data['ContentPaper']['target_id'] = $targetId;
		$this->data['ContentPaper']['target_type'] = ContentPaper::TARGET_TYPE_CATEGORY;

		$this->ContentPaper->create();


		return $this->ContentPaper->save($this->data);
	}

	function blackHoleCallback(){
		echo "jo";
		die();
	}




	private function _getSourceTypeId($sourceType){
		if($sourceType == ContentPaper::USER) return ContentPaper::SOURCE_TYPE_USER;
		if($sourceType == ContentPaper::TOPIC) return ContentPaper::SOURCE_TYPE_TOPIC;

		// @todo error log
	}

}
?>