<?php
class PapersController extends AppController {

	var $name = 'Papers';
	var $components = array('Auth', 'Session', 'Papercomp', 'Upload', 'RequestHandler');
	var $uses = array('Paper', 'Subscription', 'Category', 'Route', 'User', 'ContentPaper', 'Topic', 'CategoryPaperPost');
	var $helpers = array('MzHtml', 'MzTime','MzText' , 'Image',  'Javascript', 'Ajax', 'Reposter', 'MzRss');

	var $allowedSettingsActions = array('image');

    //callback-param is important!
    var $cacheAction = array(
        //'view'  => array('callbacks' => true, 'duration' => '+1 month')
    );

	public function beforeFilter(){
		parent::beforeFilter();
		//declaration which actions can be accessed without being logged in
		$this->Auth->allow('index','view', 'references', 'feed');

		//add security
		//$this->Security->requireAuth('add', 'edit', 'saveImage');

		$this->Security->disableFields = array('new_image', 'data[Paper][id]', 'id', 'data[Paper][hash]', 'hash');

	}

    public function beforeRender(){
        $this->_open_graph_data['type'] = 'article';

        //need to be called after setting open_graph
        parent::beforeRender();
    }

	function index() {

		$this->paginate = array(
		 	 'Paper' => array(
		//fields
	          			 'fields' => array('id', 'image', 'owner_id','title','description','created','subscription_count', 'author_count', 'post_count'),
		//limit of records per page
			            'limit' => 12,
		//order
	     		        'order' => 'Paper.title ASC',
		//contain array: limit the (related) data and models being loaded per paper
			            'contain' => array('Route','User.id', 'User.image', 'User.username', 'User.name'),
                        'conditions' => array('Paper.enabled' => true, 'Paper.visible_index' => true, 'Paper.title <>' => 'myZeitung'),
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
        $this->set('canonical_for_layout', '/papers');
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
        $paper = $this->_getPaperForSidebar($paper_id);
        if(!isset($paper['Paper']['id'])){
            $this->Session->setFlash(__('invalid paper', true));
			$this->redirect(array('action' => 'index'));
        }
        if($paper['Paper']['enabled'] == false){
            $this->Session->setFlash(__('This paper has been blocked temporarily due to infringement.', true));
			$this->redirect(array('action' => 'index'));
        }
        if($category_id == null && isset($this->params['category_id'])){
            $category_id = $this->params['category_id'];

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
				                  		'conditions' => array('CategoryPaperPost.post_id = Post.id',
                                                            //post.enabled coses bugs. but it shouldnt be shown here anyway
                                                                /*'Post.enabled' =>true*/),
		    ),
		),

		//order
			        	'order' => 'last_post_repost_date DESC',
	          			'group' => array('CategoryPaperPost.post_id'),
		//limit of records per page
			          	'limit' => 9,
		//the created field last_post_repost_date is important to just get the last entry with the last_Reposter
  						'fields' => array('Post.*', 'MAX(CategoryPaperPost.created) as last_post_repost_date', 'CategoryPaperPost.reposter_id', 'CategoryPaperPost.id'),
  						'conditions' => array('CategoryPaperPost.paper_id' => $paper_id),


		//contain array: limit the (related) data and models being loaded per post
			            'contain' => array('Route', 'User.id','User.username','User.name',  'User.image'),
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

        if(count($posts) == 0 ){
            if($paper['Paper']['owner_id'] == $this->Auth->User("id")){
                    $this->Session->setFlash(sprintf(__('This paper does not contain any posts yet. If you want to change that, just use the <i>Help Center</i> at the top of the page or read our %s.', true),"<a href='/faq'>FAQs</a>"), 'default', array('class' => 'success'));
                }else{
                    $this->Session->setFlash(__('This paper does not contain any posts yet.', true), 'default', array('class' => 'success'));
            }
        }

		//add information if the user (if logged in) has already subscribed the paper
		$this->Subscription->contain();
		if($this->Auth->user('id') && ($this->Subscription->find('count', array('conditions' => array('Subscription.user_id' => $this->Auth->user('id'),'Subscription.paper_id' => $paper['Paper']['id'])))) > 0){
			$paper['Paper']['subscribed'] = true;
		}else{
			$paper['Paper']['subscribed'] = false;
		}

        $this->set('canonical_for_layout', $paper['Route'][0]['source']);
        $this->set('rss_for_layout', $paper['Route'][0]['source'].'/feed');
		$this->set('hash', $this->Upload->getHash());

		$this->set('paper', $paper);
		$this->set('posts', $posts);


	}


    function feed($paper_id = null, $category_id = null) {

		if (!$paper_id) {
			$this->Session->setFlash(__('Invalid paper', true));
			$this->redirect(array('action' => 'index'));
		}
        $this->Paper->contain(array('Route', 'User.id', 'User.name', 'User.username', 'User.image',
                                      'Category' => array('fields' => array('author_count', 'name', 'id', 'post_count'),'order' => array('name asc'))));
        $paper = $this->Paper->read(null, $paper_id);
        if(!isset($paper['Paper']['id'])){
            $this->Session->setFlash(__('invalid paper', true));
			$this->redirect(array('action' => 'index'));
        }
        if($paper['Paper']['enabled'] == false){
            $this->Session->setFlash(__('This paper has been blocked temporarily due to infringement.', true));
			$this->redirect(array('action' => 'index'));
        }
        if($category_id == null && isset($this->params['category_id'])){
            $category_id = $this->params['category_id'];

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
                        'conditions' => array('CategoryPaperPost.post_id = Post.id',
                        //post.enabled coses bugs. but it shouldnt be shown here anyway
                            /*'Post.enabled' =>true*/),
		    ),
		),
		//order
                    'order' => 'last_post_repost_date DESC',
                    'group' => array('CategoryPaperPost.post_id'),
    //limit of records per page
                    'limit' => 20,
    //the created field last_post_repost_date is important to just get the last entry with the last_Reposter
                    'fields' => array('Post.*', 'MAX(CategoryPaperPost.created) as last_post_repost_date', 'CategoryPaperPost.reposter_id', 'CategoryPaperPost.id'),
                    'conditions' => array('CategoryPaperPost.paper_id' => $paper_id),
    //contain array: limit the (related) data and models being loaded per post
                    'contain' => array('Route', 'User.id','User.username','User.name',  'User.image'),
	    	),
		);

		//useCustom defines which kind of paginateCount the Post-Model should use. "true" -> counting entries in category_paper_posts
		$this->Paper->Post->useCustom = true;

		if($category_id != null){
			//adding the category to the conditions array for the pagination - join
			$this->paginate['Post']['conditions']['CategoryPaperPost.category_id'] = $category_id;
		}
        $posts = $this->paginate($this->Paper->Post);

        $this->set('paper', $paper);
        $this->set('posts' , $posts);
        $this->set('channel', array('title' => 'test','description' => 'testDescription' ));
    }




    private function _getPaperForSidebar($paper_id){
        $this->Paper->contain(array('Route', 'User.id', 'User.name', 'User.username', 'User.image',
            'Category' => array('fields' => array('author_count', 'name', 'id', 'post_count'),'order' => array('name asc'))));

        $paper = $this->Paper->read(null, $paper_id);

        //get number of authors for frontpage
        $this->ContentPaper->contain();
        $frontpage_authors = $this->ContentPaper->find('count', array('conditions' => array('ContentPaper.paper_id' => $paper['Paper']['id'], 'ContentPaper.category_id' => NULL)));
        $paper['Paper']['frontpage_authors_count'] = $frontpage_authors;

        return $paper;
    }


	function saveImage(){
		if (empty($this->params['form'])) {
			$this->Session->setFlash(__('Error during photo upload', true));
			$this->redirect($this->referer());
            
		}

		$paper_id = $this->params['form']['paper_id'];

		//check is user owns this paper id
		if(parent::canEdit('Paper', $paper_id, 'owner_id')){
			$this->log('can edit');
			$hash = $this->params['form']['hash'];
			if($this->Upload->hasImagesInHashFolder($hash)){
				$this->log('has in hash');
				$image = array();
				$this->Paper->contain('Route');
				$paper_data = $this->Paper->read(null, $paper_id);
				$paper_created = $paper_data['Paper']['created'];

				$image = $this->Upload->copyImagesFromHash($hash, $paper_id, $paper_created, $this->params['form']['new_image'], 'paper');

				if(is_array($image)){


					$paper_data['Paper']['image'] = $image;
					$this->Paper->updateSolr = true;

					if($this->Paper->save($paper_data, true, array('image'))){
						$this->Session->setFlash(__('Image has been saved', true), 'default', array('class' => 'success'));
						$this->User->updateSolr = true;

                        //remove tmp hash folder
                        $this->Upload->removeTmpHashFolder($hash);

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

			$this->Paper->contain('Route');
            $this->data= $this->Paper->read(null, $paper_id);
			if($this->data['Paper']['id']){
                if($this->data['Paper']['owner_id'] != $this->Auth->user('id')){
                    if($this->Paper->subscribe($this->Auth->user('id'))){
                        $this->Session->setFlash(sprintf(__("You successfully subscribed to the paper: %s. You can find it under 'Subscribed Papers'", true),$this->Paper->data['Paper']['title']), 'default', array('class' => 'success'));
                    } else {
                        $this->Session->setFlash(__('Could not subscribe to this paper.', true));
                    }
                }else {
                      $this->Session->setFlash(__("This is your own paper. Your papers can be found under 'My Papers'.", true));
                }
            } else {
                $this->Session->setFlash(__('Invalid paper id.', true));
            }

		}
		else {
			// no paper $id
			$this->Session->setFlash(__('Invalid paper id.', true));
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

			$this->Paper->contain('Route');

            $this->data= $this->Paper->read(null, $paper_id);
			if($this->data['Paper']['id']){
                if($this->data['Paper']['owner_id'] != $this->Auth->user('id')){
                    if($this->Paper->unsubscribe($this->Auth->user('id'))){
                        $this->Session->setFlash(sprintf(__('You successfully unsubscribed the paper: %s', true),$this->Paper->data['Paper']['title']), 'default', array('class' => 'success'));
                    } else {
                        $this->Session->setFlash(__('Could not unsubscribe', true));
                    }
                }else {
                      $this->Session->setFlash(__('This is your own paper. You cannot unsubscribe your own papers, but you could delete them instead of that.', true));
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
            $paper_owner = false;
            if(parent::canEdit('Paper', $id, 'owner_id')){
                $paper_owner = true;
            }

            //check, if a user is requested to be deleted from references
            if($this->_getContentPaperDeleteId()){
                $content_paper_id = $this->_getContentPaperDeleteId();

                //check, if the logged in user owns the paper
                if($paper_owner){
                    //delete the subscription
                    $this->log($content_paper_id);

                    $this->ContentPaper->delete($content_paper_id, true);
                }
                else{
                    $this->log('controller/papers/references: a user, who is not the owner of the paper with id ' . $id . ', wants to delete a subscribed user (ContentPaper) with id ' . $content_paper_id);
                }


            }


			$this->Paper->contain('Category', 'Route');
			$paper_data = $this->Paper->read(null, $id);

            //check if "all" authors is selected
            $all = false;
            if(isset($this->params['form']['all']) && !empty($this->params['form']['all']) && $this->params['form']['all'] == 'all'){
                $all = true;

                //not delete of authors here
            }

			$references = array();
			$references = $this->Paper->getContentReferences($category_id, $all);

            $this->set('owner', $paper_owner);
            $this->set('all', $all);
            $this->set('paper_id', $paper_data['Paper']['id']);
			$this->set('references', $references);

            //buid dropdown to filter the references within the popup
            $types = array();

            $types['refs_all'] = __('All Authors' ,true);

            $frontpage_authors = $this->ContentPaper->find('count', array('conditions' => array('ContentPaper.paper_id' => $paper_data['Paper']['id'], 'ContentPaper.category_id' => NULL)));
            $types['refs_paper/'.$paper_data['Paper']['id']] = sprintf(__('front page (%d)' ,true), $frontpage_authors);

            foreach($paper_data['Category'] as $cat){
                $types['refs_paper/'.$paper_data['Paper']['id'].'/'.$cat['id']] = $cat['name'] . ' ('.$cat['author_count'].')';
            }

            //set selected type for dropdown

            if($category_id == null){
                $this->set('type', 'refs_paper/'.$paper_data['Paper']['id']);
            }
            else{
                $this->set('type', 'refs_paper/'.$paper_data['Paper']['id'].'/'.$category_id);
            }




            $this->set('types' , $types);

		}


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
				$this->Session->setFlash(__('No paper parameter passed.', true));
				$this->redirect(array('action' => 'index'));
			}
			if(!$this->Paper->isValidTargetType($this->params['pass'][0]) || !isset($this->params['pass'][1])){
				$this->Session->setFlash(__('Invalid parameter for the target passed.', true));
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
        $this->log('start');
		if (!empty($this->data)) {
			//adding a route
                $this->log('data da');
			$this->Paper->create();
			$this->data['Paper']['owner_id'] = $this->Auth->User("id");
			$this->Paper->updateSolr = true;
            $this->Paper->updateRoute = true;
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
                    $this->Paper->contain('Route');
                    $paper = $this->Paper->read(array('id'),$this->Paper->id);
					$this->redirect($paper['Route'][0]['source']);
			//	}
			//	else{
					$this->Session->setFlash(__('Paper saved, error while saving the paper route', true));
			//	}
			} else {
				$this->Session->setFlash(__('The paper could not be saved. Please, try again.', true));
			}




		}

		//unbinding irrelevant relations for the query
		$this->User->contain('Topic.id', 'Topic.name', 'Topic.post_count');
		$this->set('user', $this->User->getUserForSidebar($this->Session->read('Auth.User.id')));
		//$papers = $this->paginate($this->User->Paper);
		//same template for add and edit
        $this->set('edit', false);
		$this->render('add_edit');
	}


	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid paper', true));
			$this->redirect(array('action' => 'index'));
		}
        $this->Paper->contain('Route');
        $paper =  $this->Paper->read(array('id','owner_id'), $id);
        if($paper['Paper']['owner_id'] == $this->Session->read('Auth.User.id')){
            if (!empty($this->data)) {
                $this->Paper->updateSolr = true;

                $this->Paper->updateRoute = true;

                if ($this->Paper->save($this->data)) {
                    $this->Session->setFlash(__('The paper has been saved', true), 'default', array('class' => 'success'));

                    $this->redirect($this->data['Paper']['route_source']);


                } else {
                    $this->Session->setFlash(__('The paper could not be saved. Please, try again.', true));
                }
            }
            if (empty($this->data)) {
                $this->Paper->contain('Route');
                $this->data = $this->Paper->read(null, $id);
                $this->data['Paper']['route_source'] = $this->data['Route'][0]['source'];
                $this->set('owner_id', $this->data['Paper']['owner_id']);
            }
         } else {
            $this->Session->setFlash(__('The Paper does not belong to you.', true));
            $this->redirect($this->referer());
        }

        //unbinding irrelevant relations for the query
		$this->User->contain('Topic.id', 'Topic.name', 'Topic.post_count');
		$this->set('user', $this->User->getUserForSidebar($this->Session->read('Auth.User.id')));
		//$papers = $this->paginate($this->User->Paper);
		//same template for add and edit

        $this->set('paper_id', $id);
        $this->set('edit', true);

		$this->render('add_edit');
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for paper', true));
			$this->redirect($this->referer());
		}
        $this->Paper->contain();
        $paper =  $this->Paper->read(array('id','owner_id'), $id);
        if($paper['Paper']['owner_id'] == $this->Session->read('Auth.User.id')){
            if ($this->Paper->delete($id, true)) {
                $this->Session->setFlash(__('Paper deleted', true), 'default', array('class' => 'success'));
                $this->redirect(array('controller' => 'users', 'action'=>'viewSubscriptions', 'username' => strtolower($this->Session->read('Auth.User.username')),'own_paper' => Paper::FILTER_OWN));
            }
            $this->Session->setFlash(__('Paper was not deleted', true));
            $this->redirect($this->referer());
        } else {
            $this->Session->setFlash(__('The Paper does not belong to you.', true));
            $this->redirect($this->referer());
        }
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
			$this->Session->setFlash(__('No permissions', true));
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


    function deleteImage($paper_id = null){
        if(empty($paper_id) || $paper_id == null){
            if(isset($this->data['Paper']['paper_id'])){
                $paper_id = $this->data['Paper']['paper_id'];
            }

        }

        if(!parent::canEdit('Paper', $paper_id, 'owner_id')){
			$this->Session->setFlash(__('No permissions', true));
			$this->redirect($this->referer());
        }

        if(isset($this->data)){
            $paper = $this->_getPaperForSidebar($paper_id);
            $paper['Paper']['image'] = '';
            if($this->Paper->save($paper)){
                $this->Session->setFlash(__('The paper picture has been removed', true),'default', array('class' => 'success'));
            }
            $this->redirect($paper['Route'][0]['source']);
        }

        $paper = $this->_getPaperForSidebar($paper_id);

        $this->set('paper_id', $paper_id);
        $this->set('paper', $paper);
        $this->set('hash', $this->Upload->getHash());

        /*
         *         if(!empty($this->data)){
            $user_id = $this->Session->read('Auth.User.id');
            $this->User->updateSolr = true;
            $data = array('User' => array('id' => $user_id,
                                          'image' => NULL,
                                           'username' => $this->Session->read('Auth.User.username'),
                                           'name'  => $this->Session->read('Auth.User.name')));

            if($this->User->save($data)){
                $this->Session->write('Auth.User.image', '');
                $this->Session->setFlash(__('Your Profile Picture has been removed', true),'default', array('class' => 'success'));
                $this->redirect('/settings');
            }
        }

         */

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
    function admin_index() {
        $this->paginate = array('contain' => array('User.id', 'User.username', 'Route'));
		$this->set('papers', $this->paginate());
	}
    function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for paper', true));
			$this->redirect($this->referer());
		}
        // second param = cascade -> delete associated records from hasmany , hasone relations
        if ($this->Paper->delete($id, true)) {
            $this->Session->setFlash(__('Paper deleted', true), 'default', array('class' => 'success'));
          //  $this->redirect(array('controller' => 'users',  'action' => 'view',  $this->Session->read('Auth.User.id')));
        $this->redirect($this->referer());
        }
        $this->Session->setFlash(__('Paper was not deleted', true));
        $this->redirect($this->referer());

    }
    function admin_disable($paper_id){
        $this->Paper->contain();
        $paper = $this->Paper->read(null, $paper_id);
        if(isset($paper['Paper']['id']) && !empty($paper['Paper']['id'])){
            if($paper['Paper']['enabled'] == false){
                $this->Session->setFlash('This paper is already disabled');
                $this->redirect($this->referer());
            }else{
                if($this->Paper->disable()){
                    $this->Session->setFlash('Paper has been disabled successfully','default', array('class' => 'success'));
                    $this->redirect($this->referer());
                }else{
                    $this->Session->setFlash('This paper could not be disabled. Please try again.');
                    $this->redirect($this->referer());
                }
            }
        }else{
            $this->Session->setFlash('Invalid paper');
            $this->redirect($this->referer());

        }
    }
    function admin_enable($paper_id){
        $this->Paper->contain('User');
        $paper = $this->Paper->read(null, $paper_id);
        if(isset($paper['Paper']['id']) && !empty($paper['Paper']['id'])){
            if($paper['Paper']['enabled'] == true){
                $this->Session->setFlash('This paper is already enabled');
                $this->redirect($this->referer());
            }else{
                if($paper['User']['enabled'] == false){
                    $this->Session->setFlash('The User that created the paper is disabled. You cannot enable this paper.');
                    $this->redirect($this->referer());
                }
                if($this->Paper->enable()){
                    $this->Session->setFlash('Paper has been enabled successfully','default', array('class' => 'success'));
                    $this->redirect($this->referer());

                }else{
                    $this->Session->setFlash('This Paper could not be enabled. Please try again.');
                    $this->redirect($this->referer());
                }
            }
        }else{
            $this->Session->setFlash('Invalid paper');
            $this->redirect($this->referer());

        }
    }


    function admin_toggleVisible($type, $id = null){

		if (!$type || (!in_array($type, array('home', 'index'))) ||!$id) {
			$this->Session->setFlash(__('Invalid id for paper', true));
			$this->redirect($this->referer());
		}
        $field = 'visible_home';
        if($type == 'index'){
            $field = 'visible_index';
        }

        $this->Paper->contain();
        $paper = $this->Paper->read(null, $id);

        $this->Paper->set($field, !((boolean)$paper['Paper'][$field]));

        if($this->Paper->save()){

            clearCache('element_my_cached_element_home_index');

            $this->Session->setFlash(__('Paper saved', true), 'default', array('class' => 'success'));
            $this->redirect($this->referer());
        }
        else{
            $this->Session->setFlash(__('Could not save paper, please try again.', true));
            $this->redirect($this->referer());
        }

        // second param = cascade -> delete associated records from hasmany , hasone relations

    }

    function admin_editPremium($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid paper', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
            $this->Paper->updateRoute = true;
			if ($this->Paper->save($this->data)) {
				$this->Session->setFlash(__('The paper has been saved', true));
                Cache::delete('premium_papers');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The paper could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Paper->read(null, $id);

		}
	}

}
?>