<?php
class Post extends AppModel {
	var $name = 'Post';
	var $displayField = 'title';


	var $actsAs = array('Containable','Increment'=>array('incrementFieldName'=>'count_views'));



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
			'conditions' => 'Post.topic_id != null',
			'fields' => '',
			'order' => ''

			)
			);

			var $hasMany = array(
		'Comment' => array(
			'className' => 'Comment',
			'foreignKey' => 'post_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
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
			/**
			 * @author: tim
			 * unserializing the reposters-array after being read from the db.
			 *
			 */
			function afterFind($results) {
				foreach ($results as $key => $val) {
					if (!empty($val['Post']['reposters']) ) {
						$results[$key]['Post']['reposters'] = unserialize($results[$key]['Post']['reposters']);
					}else {
						if(isset($results[$key]['Post']['reposters'])){
							$results[$key]['Post']['reposters'] = array();
						}
					}
					if (!empty($val['reposters']) ) {
						$results[$key]['reposters'] = unserialize($results[$key]['reposters']);
					}else {
						if(isset($results[$key]['reposters'])){
							$results[$key]['reposters'] = array();
						}
					}
				}
				return $results;
			}

			/**
			 * @author: tim
			 * serializing the reposters-array before being written to the db.
			 *
			 */
			function beforeSave(&$Model) {
				if(!empty($this->data['Post']['reposters'])){
					$this->data['Post']['reposters'] = serialize($this->data['Post']['reposters']);
				}
				return true;
			}



		/**
		 * 1)
		 * update solr index with saved data
		 */
		function afterSave(){

			App::import('model','Solr');

			$userData = $this->User->read(null, $this->data['Post']['user_id']);

			if($userData['User']['id']){
				if(isset($this->data['Post']['topic_id'])){
					$topicData = $this->Topic->read(null, $this->data['Post']['topic_id']);
	
					if($topicData['Topic']['id'] && !empty($topicData['Topic']['name'])){
						$this->data['Post']['topic_name'] = $topicData['Topic']['name'];
					}
				}
					
				$this->data['Post']['index_id'] = 'post_'.$this->id;
				$this->data['Post']['id'] = $this->id;
				$this->data['Post']['user_name'] = $userData['User']['name'];
				$this->data['Post']['type'] = Solr::TYPE_POST;
				$solr = new Solr();
				$solr->add($this->removeFieldsForIndex($this->data));

			}
			else{
				$this->log('Error while reading user for Post! No solr index update');
			}
		}

		function beforeDelete(){
			App::import('model','PostUser');
			if($this->id){
				//deleting all posts_users entries 
				$this->PostUser = new PostUser();
				// params conditions, cascade, fallbacks 
				$this->PostUser->deleteAll(array('post_id' => $this->id), false, true);
				return true;
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
		unset($data['Post']['reposters']);
		unset($data['Post']['image']);
		unset($data['Post']['image_details']);
		
		return $data;

	}
	

	function __construct(){
		parent::__construct();
	}
	
}

?>