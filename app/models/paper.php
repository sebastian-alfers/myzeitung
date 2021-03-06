<?php
class Paper extends AppModel {

    const DEFAULT_PAPER_IMAGE = 'assets/default-paper-img.png';

    const FILTER_OWN = 'own';
    const FILTER_ALL = 'all';
    const FILTER_SUBSCRIBED = 'subscriptions';
    const RETURN_CODE_SUCCESS_CREATED_PAPER = 0;
    const RETURN_CODE_SUCCESS = 1;
    const RETURN_CODE_SUCCESS_DELETED_TOPICS = 2;
    const RETURN_CODE_ERROR_DUPLICATE_EXACT_ASSOCIATION = 3;
    const RETURN_CODE_ERROR_WHOLE_USER_ALREADY_SUBCRIBED = 4;
    const RETURN_CODE_ERROR_INVALID_TOPIC = 5;
    const RETURN_CODE_ERROR_ASSO_NOT_SAVED = 6;
    const RETURN_CODE_ERROR_NO_PAPER_TO_CATEGORY = 7;
    const RETURN_CODE_ERROR_NO_VALID_TARGET_OR_SOURCE = 8;

    var $return_codes_success =
                array(self::RETURN_CODE_SUCCESS,
                      self::RETURN_CODE_SUCCESS_DELETED_TOPICS,
                      self::RETURN_CODE_SUCCESS_CREATED_PAPER
                );


    var $return_code_messages = array();

    var $name = 'Paper';
	var $actsAs = array('Increment'=>array('incrementFieldName'=>'count_subscriptions'));
	var $displayField = 'title';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $updateSolr = true;
    var $updateRoute = false;

	private  $_contentReferences = null;


	var $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'owner_id',
			'counterCache' => true,
            'counterScope' => array('Paper.enabled' => true),
		),
	);

	var $hasAndBelongsToMany = array(
		'Post' => array(
			'className' => 'Post',
			'joinTable' => 'category_paper_posts',
			'foreignKey' => 'paper_id',
			'associationForeignKey' => 'post_id',
			//	'unique' => true,
			'fields' => '',
			'order' => 'CategoryPaperPost.created DESC',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => ''
		)
	);



	var $hasMany = array(
		'Category' => array(
			'className' => 'Category',
			'foreignKey' => 'paper_id',
			'dependent' => true,
			'conditions' => array('parent_id' => 0),//IMPORTANT! to avoid show sub-category in root
            'order' => 'Category.name',
			//	'dependent' => false
		),
		'Subscription' => array(
			'className' => 'Subscription',
			'foreignKey' => 'paper_id',
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
		'ContentPaper' => array(
			'className' => 'ContentPaper',
			'foreignKey' => 'paper_id',
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
            'conditions' => array('Route.parent_id' => null,
                                'Route.ref_type' => 'PAPER'),
            ),
	);



    function beforeValidate() {
        if (isset($this->data['Paper']['url']) && $this->data['Paper']['url'] == 'http://') {
            $this->data['Paper']['url'] = '';
        }
        return true;
    }


function __construct(){
		parent::__construct();

		$this->validate = array(

			'title' => array(
				'empty' => array(
					'rule'			=> 'notEmpty',
					'message' 		=> __('Please enter a title for your paper', true),
					'last' 			=> true,
				),
				'maxlength' => array(
					'rule'			=> array('maxlength', 55),
					'message'		=> __('Paper titles can only be 55 characters long.', true),
					'last' 			=> true,
				),
			),
			'description' => array(
				'maxlength' => array(
					'rule'			=> array('maxlength', 1000),
					'message'		=> __('Descriptions can only be 1000 characters long.', true),
					'last'			=> true,
				),
			),
			'url' => array(
				'valid_url' => array(
					'rule'			=> array('url', true), /* second param defines wether you force an input of a protocol like http:// ftp:// etc */
					'message'		=> __('Please provide a valid URL. http://your-link.domain', true),
					'allowEmpty'    => true,
                    'last'			=> true,
				),
			),
            'premium_route' => array(
				'minLength_alphaNumeric' => array(
					'rule'			=> 'premiumRouteFormat',
					'message'		=> __('The premium route must be at least 3 letters long and must only contain letters and numbers and "-"', true),
					'last' 			=> true,
				),
				'availability' => array(
					'rule'			=> 'premiumRouteAvailability',
					'message'		=> __('This Route does already belong to another paper. (even if it might not be still in use)', true),
					'last' 			=> true,
				),

			),
		);
        $this->return_code_messages = array(
            self::RETURN_CODE_SUCCESS_CREATED_PAPER =>  __('We created a new paper for you.',true).' '.__('The subscription has been saved to your just created paper.', true),
            self::RETURN_CODE_SUCCESS => __('User/Topic successfully subscribed to your Paper/Category', true),
            self::RETURN_CODE_SUCCESS_DELETED_TOPICS => __('Subscription successfully saved. There was at least one topic of this user subscribed to your Paper/Category which has been replaced to allow this new Subscription.', true),
            self::RETURN_CODE_ERROR_DUPLICATE_EXACT_ASSOCIATION => __('This exact Subscription already exists', true),
            self::RETURN_CODE_ERROR_WHOLE_USER_ALREADY_SUBCRIBED => __('You already subscribed this User (with all topics) to this Paper/Category', true),
            self::RETURN_CODE_ERROR_INVALID_TOPIC => __('Invalid Topic chosen', true),
            self::RETURN_CODE_ERROR_ASSO_NOT_SAVED => __('The Subscription could not be saved, please try again', true),
            self::RETURN_CODE_ERROR_NO_PAPER_TO_CATEGORY => __('The Paper of the your chosen Category could not be found.', true),
            self::RETURN_CODE_ERROR_NO_VALID_TARGET_OR_SOURCE => __('No valid User/Topic or Paper/Category chosen', true),
        );
	}


    function premiumRouteFormat($data) {
        if($data['premium_route'] != NULL && !empty($data['premium_route'])){
            if(strlen($data['premium_route']) < 3){
                return false;
            }
            if(!preg_match('/^[A-Za-z0-9-]+$/',$data['premium_route'])){
                return false;
            }
        }
        return true;
    }
   function premiumRouteAvailability($data) {
       if($data['premium_route'] != NULL && !empty($data['premium_route'])){
           //dryrun of route string generation to check availability
            $routeString = $this->generateRouteString(true);

            if($routeString != '/'.$data['premium_route']){
                return false;
            }
       }
        return true;
    }

			/**
			 * @author tim
			 * Function for a user to subscribe a paper.
			 * @param int $user_id - user to subscribe a given paper
			 */
			public function subscribe($user_id){
				//check if the user already subscribed the paper -> just one paper/user combination allowed
				$subscriptionData = array(
							'paper_id' => $this->id,
						   	'user_id' => $user_id);

				if(($this->Subscription->find('count',array('conditions' => $subscriptionData))) == 0){

					if($this->data['Paper']['owner_id'] != $user_id){
						//paper is not from subscribing user
						//creating subscription
						$this->Subscription->create();

						if($this->Subscription->save($subscriptionData)){

							//subscription was saved

							return true;
						}	else {
							// subscription couldn't be saved
							$this->log('Paper/Subscribe: The paper '.$this->id.' could not be subscribed by user '.$user_id);

						}
					} else {
						//user tried to subscribe his own paper
						$this->log('Paper/Subscribe: User '.$user_id.' tried to subscribe Paper.'.$this->id.' which is his own paper.');

					}

				}else{
					// already subscribed
					$this->log('Paper/Subscribe: User '.$user_id.' tried to subscribe  Paper'.$this->id.' which he had already subscribed.');

					return true;
				}
				return false;
			}



			/**
			 * @author tim
			 * deletes subscription for a paper for a specific user (param)
			 * @param int $user_id
			 */
			public function unsubscribe($user_id){

				if($this->data['Paper']['owner_id'] != $user_id){

					// just in case there are several subscriptions for the combination post/user - all will be deleted.
					$subscriptions =  $this->Subscription->find('all',array('conditions' => array('Subscription.paper_id' => $this->id, 'Subscription.user_id' => $user_id)));
					$delete_counter = 0;
					foreach($subscriptions as $subscription){
						//deleting the subscriptions from the db
						$this->Subscription->delete($subscription['Subscription']['id']);
						$delete_counter += 1;
					}
					//writing log entry if there were more than one entries for this repost (shouldnt be possible)
					if($delete_counter > 1){
						$this->log('Paper/unsubscribe: User '.$user_id.' had more then 1 subscription entry for Paper '.$this->id.'. (now deleted) This should not be possible.');
					}

					if($delete_counter < 1){

						$this->log('Paper/unsubscribe: Subscription could not be removed or no subscription found');
					}
					return true;
				} else {
					//$this->Session->setFlash(__('You cannot unsubscribe your own paper. You can delete it.', true));
					$this->log('Paper/unsubscribe: User '.$user_id.' tried to unsubscribe his own Paper '.$this->id.'. This should not be possible.');
				}
				return false;
			}


            /**
             * get authors for a paper / category
             *
             * @param int $category_id - filter by category
             * @param bool $show_all - if true show a (distinct) list of authors
             *
             * @return array
             */
			function getContentReferences($category_id = null, $show_all = false){

				if($this->_contentReferences == null){

					App::import('model','ContentPaper');
					$contentPaper = new ContentPaper();
					$conditions = array('conditions' => array(
						'ContentPaper.paper_id' => $this->id));
					if($show_all){
                        $conditions['fields'] = array('DISTINCT ContentPaper.user_id', 'ContentPaper.id');
                        $conditions['group'] = array('ContentPaper.user_id');//each user only once
                    }
					else if($category_id !=  null){
						$conditions['conditions']['ContentPaper.category_id'] = $category_id;
					}
                    else{
                        $conditions['conditions']['ContentPaper.category_id'] = NULL;
                    }


					$paperReferences = array();
					$contentPaper->contain(array('Topic.id', 'Topic.name' ,'Paper.id', 'Category', 'User' => array('fields' => array('id', 'username', 'name', 'image'), 'order' => array('User.id'))));#, 'User.Post.id'
					$paperReferences = $contentPaper->find('all', $conditions);

                    $this->_contentReferences = $paperReferences;
				}

				return $this->_contentReferences;

			}

			/**
			 * get a list of all topic associations related to this paper
			 */
			function getTopicReferencesToOnlyThisPaper(){
				$allReferences = $this->getContentReferences();
				$topicReferences = array();
				if(count($allReferences) > 0){
					foreach($allReferences as $reference){

						//only topics that are not associated to a category -> direkt in paper

						if($reference['ContentPaper']['topic_id'] && !$reference['ContentPaper']['category_id']	){
							$topicReferences[] = $reference;
						}
					}
				}
				return $topicReferences;
			}

			/**
			 * get a list of all topic associations related to this paper
			 */
			function getTopicReferencesToOnlyThisCategory($category_id = null){
				$allReferences = $this->getContentReferences($category_id);
				$categoryReferences = array();
				if(count($allReferences) > 0){
					foreach($allReferences as $reference){
						//only topics that are not associated to a category -> direkt in paper
						if($reference['ContentPaper']['topic_id'] && $reference['ContentPaper']['category_id']){
							$categoryReferences[] = $reference;
						}
					}
				}
				return $categoryReferences;
			}

			/**
			 *
			 * 1) adding default image to the paper
			 */
/*		function afterFind($results){

			//adding default paper image to users without an image
			 foreach($results as $key => $val) {
			 	if(isset($val['Paper'])){
					if(isset($val['Paper']['image'])){
						if(empty($val['Paper']['image'])){
								$results[$key]['Paper']['image'] = 'assets/news-image.jpg';
						}
					}

			 	}
		 		if(isset($val['image'])){
					if(empty($val['image'])){
							$results[$key]['image'] = 'assets/news-image.jpg';
					}
				}
			 }
			 if(isset($results['Paper'])){
			 	if(empty($results['image'])){
			 		$results['image'] = 'assets/news-image.jpg';
			 	}
			 }
			return $results;
		}
 */


    /**
     * returns false if no route was written
     */
    /**
     * returns false if no route was written
     */
    function addRoute(){

        $this->contain();
        $this->data = $this->read(null,$this->id);

        $routeString = $this->generateRouteString();
        //if NOT the exact route was currently active
        if($routeString != ''){
            if(substr($routeString,0,strlen(ROUTE::TYPE_NEW_ROUTE)) == ROUTE::TYPE_NEW_ROUTE){
                //new route has to be created
                $routeData['Route'] = array('source' => substr($routeString,strlen(ROUTE::TYPE_NEW_ROUTE)),
                                        'target_controller' 	=> 'papers',
                                        'target_action'     	=> 'view',
                                        'target_param'		=> $this->id,
                                        'ref_type'          => Route::TYPE_PAPER,
                                        'ref_id'            => $this->id);
                if($this->data['Paper']['premium_route'] != NULL){
                    $routeData['Route']['premium'] = true;
                }
                $this->Route->create();
                if($this->Route->save($routeData,false)){
                    $currentRouteId = $this->Route->id;
                }
            }elseif(substr($routeString,0,strlen(ROUTE::TYPE_OLD_ROUTE_ID)) == ROUTE::TYPE_OLD_ROUTE_ID){
                //the route did already exist but was not active - has to be made parent again.
                $currentRouteId = substr($routeString,strlen(ROUTE::TYPE_OLD_ROUTE_ID));

            }

            // update children
            $this->updateRouteChildren($currentRouteId);

            return true;
        }else{
            return false;
        }

        return false;
    }
    private function updateRouteChildren($newParentId){
        $this->Route->contain();
        $oldRoutes =$this->Route->find('all',array('conditions' => array('ref_type' => Route::TYPE_PAPER,  'ref_id' => $this->id)));
        foreach($oldRoutes as $oldRoute){
             if($oldRoute['Route']['id'] !=  $newParentId){
                 $oldRoute['Route']['parent_id'] = $newParentId;
                 $this->Route->save($oldRoute);
             }
             $this->Route->deleteRouteCache($oldRoute['Route']['source']);
        }
    }

    private function generateRouteString($dryRun = false){

        if(!empty($this->data['Paper']['premium_route'])){
            // TAKE PREMIUM ROUTE
            $routeString = '/'.$this->data['Paper']['premium_route'];
        }else{
            // GENERATE STRING
            $this->User->contain();
            $user = $this->User->read(array('id','username'),$this->data['Paper']['owner_id']);

            //generate the theoretically needed route
            $this->Inflector = ClassRegistry::init('Inflector');
            $routeUsername = strtolower($user['User']['username']);
            $routeTitle= strtolower($this->Inflector->slug($this->data['Paper']['title'],'-'));
            if(empty($routeTitle)){
                $routeTitle = 'paper';
            }
            $routeString = '/p/'.$routeUsername.'/'.$routeTitle;
        }

        //check if such a route does already exist
        $this->Route->contain();
        $existingRoutes = $this->Route->findAllBySource($routeString);

        if(count($existingRoutes) >0){
            //the route is already used
            $sameTarget = true;
            $sameTargetId = null;

            foreach($existingRoutes as $existingRoute){
                if($existingRoute['Route']['ref_type'] !=  Route::TYPE_PAPER || $existingRoute['Route']['ref_id'] != $this->id){
                    $sameTarget = false;
                }else{
                    //old route has the same target. if it is a child it needs to be made parent and the children being updated to new parent
                    if(!$dryRun){
                        if($existingRoute['Route']['parent_id'] != null){
                            $sameTargetId = Route::TYPE_OLD_ROUTE_ID.$existingRoute['Route']['id'];
                            $existingRoute['Route']['parent_id'] = null;
                            $this->Route->save($existingRoute);
                        }
                    }
                }
            }
            if($sameTarget){
                if($sameTargetId == null){
                    //route already exists - nothing needed
                    if(!$dryRun){
                        return '';
                    }else{
                        return $routeString;
                    }
                }else{
                    //old route reactivated -> needs to be parent again. children need to be updated to new parent
                    if(!$dryRun){
                        return $sameTargetId;
                    }else{
                        return $routeString;
                    }

                }
            }else{
                //desired route did already exist. it was not directing to the same target - so it cant be taken.
                //generate new routestring with added id
               // $routeString = '/p/'.$routeUsername.'/'.$routeTitle.'-p'.$this->id;
                $routeString = $routeString.'-p'.$this->id;
                $this->Route->contain();
                $existingRoutes = $this->Route->findAllBySource($routeString);
                //if this specific routestring does exist, it must direct to the same target
                if(count($existingRoutes)>0){
                    return '';
                }
                return Route::TYPE_NEW_ROUTE.$routeString;
            }

        }else{
            //the route is not used yet
            if(!$dryRun){
                return Route::TYPE_NEW_ROUTE.$routeString;
            }else{
                return $routeString;
            }

        }
        return $route_title;
    }


    function deleteRoutes(){
        App::import('model','Route');
        $this->Route = new Route();
        $conditions = array('Route.ref_type' => Route::TYPE_PAPER,  'Route.ref_id' 	=> $this->id);
        //cascade = false, callbackes = true
        $this->Route->contain();
        $this->Route->deleteAll($conditions, false, true);
    }
    function refreshRoutes(){
        $this->contain();
        $papers = $this->find('all');

        App::import('model','Route');
        $this->Route = new Route();
        App::import('model','User');
        $this->User = new User();
        App::import('model','Solr');
        $this->Solr = new Solr();

        $solrEntries = array();


        foreach($papers as $paper){
            $this->id = $paper['Paper']['id'];
            $this->data = $paper;
            if($this->addRoute()){
                $solrFields = $this->generateSolrData();
                $solrEntries[] =  $solrFields;
            }
        }


        if(count($solrEntries) > 0){
            $this->Solr->add($solrEntries);
        }
    }


    /**
     * 1)
     * update solr index with saved data
     */
    function afterSave($created){


        App::import('model','Route');
        $this->Route = new Route();
        App::import('model','User');
        $this->User = new User();


        if($this->updateRoute){

            $this->addRoute();
        }

        if(!$this->updateSolr)return;

        if($this->id){
             App::import('model','Solr');
             $this->Solr = new Solr();
             $this->addToOrUpdateSolr();

            //create subscription for created paper
            if($created){
                $subscriptionData = array('paper_id' => $this->id,
                                    'user_id' => $this->data['Paper']['owner_id'],
                                    'own_paper' => true,
                );
                $this->Subscription = new Subscription();
                $this->Subscription->create();
                $this->Subscription->save($subscriptionData);

            }

        }
        else{
            $this->log('Error while adding paper to solr! No paper id in afterSave()');
        }

        $this->deleteCache();
    }
    /**
     * add or update solr records. if no param is given, it takes this->id.
     * param can be single id or array with ids
     * @param null $papers
     * @return bool
     */
    function addToOrUpdateSolr($papers = null){
        //no param and not id
        if($papers == null && $this->id == null){
            return false;
        }
        //param is single id -> form array
        if($papers != null && !is_array($papers)){
            $papers = array($papers);
        }
        //no param but id -> form array
        if($papers == null){
            $papers = array($this->id);
        }

        $solrRecords = array();
        foreach($papers as $paper_id){
            $this->id = $paper_id;
            $solrRecords[] = $this->generateSolrData();
        }

        if($this->Solr->add($solrRecords)){
            return true;
        }
        return false;

    }



    /**
     * associate content to a paper or category
     *
     * @param $data data from controller
     * 		  $data['Paper] [ContentPaper::CONTENT_DATA] = user_#userid | paper_#paperid
     * 		  $data['Paper']['target_type'] = ContentPaper::PAPER | ContentPaper::CATEGORY
     *		  $data['Paper']['target_id'] = #paperid | #categoryid
     *
     */
    public function associateContent($data){

        if(isset($data['Paper'][ContentPaper::CONTENT_DATA]) && !empty($data['Paper'][ContentPaper::CONTENT_DATA])){
            //validate if hidden field is paper or category

            //add content for
            $source = $data['Paper'][ContentPaper::CONTENT_DATA];
            //split
            $source = explode(ContentPaper::SEPERATOR, $source);
            $sourceType = $source[0];
            $sourceId   = $source[1];
            $targetType = $data['Paper']['target_type'];
           // $this->log($data);
            if($this->isValidTargetType($targetType) &&
            $this->isValidSourceType($sourceType) &&
            isset($data['Paper']['target_id']))
            {
                if(count($source) == 2){
                    if($sourceType == ContentPaper::USER){
                      $user_id = $sourceId;
                    } else{

                        $user_id = $data['Paper']['user_id'];
                    }
                    //prepare variables to indicate whole user or only topic as source
                   // $user_id = $sourceId;

                    $topic_id = null;
                    if($sourceType == ContentPaper::TOPIC) $topic_id = $sourceId;
                    switch ($targetType){
                        case ContentPaper::PAPER:
                            return $this->_associateContentForPaper($data, $user_id, $topic_id);//$topic_id can be null
                            break;
                        case ContentPaper::CATEGORY:
                            $category_id = $data['Paper']['target_id'];
                            //get paper for category
                            $this->Category->contain('Paper');
                            $category = $this->Category->read(null, $category_id);

                            if($category['Paper']['id']){
                                $paper_id = $category['Paper']['id'];
                                $return_code = $this->newContentForPaper($paper_id, $category_id, $user_id, $topic_id, false);
                                if(in_array($return_code, $this->return_codes_success)){
                                    // todo: save data here
                                    return $return_code;
                                }
                            }
                            else{
                                //category MUST have a paper
                                //not able to read paper for category -> error
                                //$this->Session->setFlash(__('error while reading paper for category!', true));
                                //$this->redirect(array('action' => 'index'));
                                return self::RETURN_CODE_ERROR_NO_PAPER_TO_CATEGORY;
                            }
                            break;
                    }
                }
            }else{
                //no valid source or target type
             //   $this->log('numero uno');
                return self::RETURN_CODE_ERROR_NO_VALID_TARGET_OR_SOURCE;

            }


        }
       return $return_code;


    }


    /**
     *	returns true if is allowed to associate data (user or topic) to a paper (or category)
     *
     *
     * validate by following criteria
     * - if constelation(paper_id, category_id, user_id, topic_id) isnt already there
     *
     * - if the whole user is associated to a paper or category, check if there are already
     *   other associations to one or more topic of the user IN THIS paper or category
     *
     *   @todo ask is want to delete all other refs to the user�s topics and add whole user
     *
     * - if a topic is associated to a paper or a category, check if this topic isnt already
     *   associated in this category
     *
     * @param int $categoryId
     * @param int $userId
     * @param int $topicId
     */
    private function _canAssociateDataToPaper($paperId, $categoryId, $userId, $topicId, $created){

        if($created){
            $returnCodeSucess = self::RETURN_CODE_SUCCESS_CREATED_PAPER;
        }else{
             $returnCodeSucess = self::RETURN_CODE_SUCCESS;
        }
        //check if there is already a subscription for exactly this constellation
        $conditions = array('conditions' => array(
                                    'ContentPaper.paper_id' => $paperId,
                                    'ContentPaper.category_id' => $categoryId,
                                    'ContentPaper.user_id' => $userId,
                                    'ContentPaper.topic_id' => $topicId));
        App::import('model','ContentPaper');
        $contentPaper = new ContentPaper();
        $checkDoubleReference = $contentPaper->find('all', $conditions);

        //if we get an result -> not allowed to add this constelation
        if(isset($checkDoubleReference[0]['ContentPaper']['id'])){
            return self::RETURN_CODE_ERROR_DUPLICATE_EXACT_ASSOCIATION;
        }

       if(!$topicId){
            //get user topics
            $this->User->contain('Topic.id');
            $user = $this->User->read(null, $userId);


            $userTopics = $user['Topic'];

            //if user has no topcis (should not be possible...)
            if(count($userTopics) == 0) return $returnCodeSucess;

            $this->contain();
            $paper = $this->read(null, $this->id);


            if($categoryId){
                //whole user to a category
                //reading all topics of the category.
                $categoryTopics = $this->getTopicReferencesToOnlyThisCategory($categoryId);
                //if category has no topics referenced
                if(count($categoryTopics) == 0) return $returnCodeSucess;
            }

            //whole user to paper
            //get all user topics associated to that paper  ( front page)
            //check if already one of the users topics is associated to this paper itself (front page)
            $paperTopics = $this->getTopicReferencesToOnlyThisPaper();
           // debug(count($paperTopics && !$categoryId));
            //if paper has no topics referenced and there is no category referenced
            if(count($paperTopics) == 0  && !$categoryId) return $returnCodeSucess;

            //"overwrite" all topic associations of the user with the "whole user association" by deleting all topics of this user
            // in the specific category or paper-frontpage
            if($categoryId == null){
                //check if one of the user topics is in the paper topics (front page)

                foreach($userTopics as $userTopic){

                    foreach($paperTopics as $paperTopic){
                        if($userTopic['id'] == $paperTopic['ContentPaper']['topic_id']){
                            //delete the topic association, because it is gonna be redundant after the whole user is subscribed
                            $this->ContentPaper->delete($paperTopic['ContentPaper']['id'], true);
                        }
                    }
                }
            }else{
               //check if one of the user topics is in the paper topics (front page)
                foreach($userTopics as $userTopic){
                    foreach($categoryTopics as $categoryTopic){
                        if($userTopic['id'] == $categoryTopic['ContentPaper']['topic_id']){
                            //delete the topic association, because it is gonna be redundant after the whole user is subscribed
                            $this->ContentPaper->delete($categoryTopic['ContentPaper']['id'], true);
                          }
                    }
                }
            }
            return self::RETURN_CODE_SUCCESS_DELETED_TOPICS;


        }
        else{


            //check if the complete user is not already in the same category (or frontpage) as the topic thats gonna be subscribed

            App::import('model','Topic');
            $topic = new Topic();
            $topic->contain();
            $topic->read(null, $topicId);

            if(!$topic->id){
                return self::RETURN_CODE_ERROR_INVALID_TOPIC;
            }else{


                if($topic->data['Topic']['user_id']){
                    //check if the topics user is not in this part of the paper (frontpage or category)
                    $userId = $topic->data['Topic']['user_id'];
                    $conditions = array('conditions' => array(
                                    'ContentPaper.paper_id' => $this->id,
                                    'ContentPaper.user_id' => $userId,
                                    'ContentPaper.category_id' => null,
                                    'ContentPaper.topic_id' => null));

                    if($categoryId > 0){
                        //add category
                        $conditions['conditions']['ContentPaper.category_id'] = $categoryId;
                    }


                    $contentPaper->contain();
                    $checkUser = $contentPaper->find('all', $conditions);

                    if(isset($checkUser[0]['ContentPaper']['id'])){
                        //user is already in paper
                        //$this->Session->setFlash(__('The owner of this topic is already in this category ', true));
                        //$this->redirect(array('action' => 'index'));
                        return self::RETURN_CODE_ERROR_WHOLE_USER_ALREADY_SUBCRIBED;
                    }
                    //topics user is not in the paper -> can add topic to paper / category
                    return self::RETURN_CODE_SUCCESS;
                }
                else{
                    return self::RETURN_CODE_ERROR_INVALID_TOPIC;
                }
            }
        }
        return self::RETURN_CODE_SUCCESS;
    }

    /**
     * after content (user or category) has be
     *
     */
    function initialImportPosts(){


    }


    /**
     * associate content to paper
     *
     * @param int $categoryId
     * @param int $userId
     * @param int $topicId
     *
     * @return boolean
     */
    public function newContentForPaper($paperId, $categoryId, $userId, $topicId, $created){

        $return_code = $this->_canAssociateDataToPaper($paperId, $categoryId, $userId, $topicId, $created);
        if(!in_array($return_code, $this->return_codes_success)){
            return $return_code;
        }

        //$this->ContentPaper->find('all', )

        //this->_saveNewContent(paper, ....)
        $data = array();
        $data['ContentPaper'] = array();
        $data['ContentPaper']['paper_id'] 	= $this->id;
        $data['ContentPaper']['category_id'] 	= $categoryId;
        $data['ContentPaper']['user_id'] 		= $userId;
        $data['ContentPaper']['topic_id'] 	= $topicId;

        App::import('model','ContentPaper');
        $contentPaper = new ContentPaper();
        $contentPaper->create();
        if(!$contentPaper->save($data)){
            return self::RETURN_CODE_ERROR_ASSO_NOT_SAVED;
        }else{
            return $return_code;
        }
    }

    /**
     *
     * associate contenet (user or topic) to a paper
     *
     * @param int $user_id
     * @param int $topic_id
     */
    private function _associateContentForPaper($data, $user_id, $topic_id){

        $paper_id = $data['Paper']['target_id'];
        $created = $data['Paper']['created'];
        return  $this->newContentForPaper($paper_id, null, $user_id, $topic_id,$created);

    }



    /**
     * get $data and checks if is type
     * paper or category for paper_content
     *
     * @return boolean
     */
    public function isValidTargetType($targetType){
        return (($targetType == ContentPaper::PAPER) ||
        ($targetType == ContentPaper::CATEGORY));
    }

    /**
     * get $data and checks if is type
     * paper or category for paper_content
     *
     * @return boolean
     */
    public function isValidSourceType($sourceType){

        return (($sourceType == ContentPaper::USER) ||
        ($sourceType == ContentPaper::TOPIC));
    }


    function generateSolrData(){

      //  if(!isset($this->data['User']) ||  !isset($this->data['Route']) || !isset($this->data['Paper'])){
            $this->contain('User', 'Route');
            $this->data = $this->read(null, $this->id);
        // }

        $solrFields = array();
        $solrFields['id']					= $this->data['Paper']['id'];
        $solrFields['paper_title']			= $this->data['Paper']['title'];
        $solrFields['paper_description']	= $this->data['Paper']['description'];
        $solrFields['index_id']			= Solr::TYPE_PAPER.'_'.$this->id;
        $solrFields['user_id']				= $this->data['User']['id'];
        $solrFields['user_name']			= $this->data['User']['name'];
        $solrFields['user_username']		= $this->data['User']['username'];
        $solrFields['type']				= Solr::TYPE_PAPER;




        $solrFields['route_source']		= $this->data['Route'][0]['source'];

        if(!isset($this->data['Route'][0])){
            debug($this->data);
            die();
        }


         if(isset($this->data['Paper']['image'])){
            $solrFields['paper_image'] = $this->data['Paper']['image'];
        }

        return $solrFields;
    }


    function deleteFromSolr(){
        App::import('model','Solr');
        $solr = new Solr();
        $solr->delete(Solr::TYPE_PAPER.'_'.$this->id);
        return true;
    }


    function beforeSave(){
        if(!empty($this->data['Paper']['image']) && is_array($this->data['Paper']['image']) && !empty($this->data['Paper']['image'])){
            $this->data['Paper']['image'] = serialize($this->data['Paper']['image']);
        }
         if(isset($this->data['Paper']['premium_route']) && !empty($this->data['Paper']['premium_route'])){
            $this->data['Paper']['premium_route'] = strtolower($this->data['Paper']['premium_route']);
        }

        return true;
    }
    function afterDelete(){
        $this->deleteFromSolr();
        $this->deleteRoutes();
        return true;
     }

    function disable(){

        if($this->data['Paper']['enabled'] == true){
            //disable Paper
            $this->data['Paper']['enabled'] = false;
            $this->save($this->data);
            //delete solr entry
            $this->deleteFromSolr();

            return true;
        }
        //already disabled
        return false;
    }
    function enable(){

        if($this->data['Paper']['enabled'] == false){
            //delete all posts_users entries with cascading and callbacks

            //disable post
            $this->data['Paper']['enabled'] = true;
            $this->updateSolr = true;
            $this->save($this->data);

            return true;
        }
        //already enabled
        return false;
    }


    function deleteCache(){
        Cache::delete('home_papers');
    }



}
?>