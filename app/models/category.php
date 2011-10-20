<?php
class Category extends AppModel {
	var $name = 'Category';
	var $displayField = 'name';
	
	const PARAM_CATEGORY = 'category';
	const PARAM_PAPER	 = 'paper';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'Paper' => array(
			'className' => 'Paper',
			'foreignKey' => 'paper_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
			),
/*		'Parent' => array(
			'className' => 'Category',
			'foreignKey' => 'parent_id'
			)*/
			);

			var $hasOne = array(
		'Route' => array(
			'className' => 'Route',
			'foreignKey' => 'ref_id',//important for FK
			'conditions' => '',
			'fields' => '',
			'order' => ''
			)
			);

	var $hasMany = array(/*
		'Children' => array(
			'className' => 'Category',
			'foreignKey' => 'parent_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
        ),*/
		'ContentPaper' => array(
			'className' => 'ContentPaper',
			'foreignKey' => 'category_id',
			'dependent' => true,
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

    function __construct(){
        parent::__construct();
        $this->validate = array(
			'name' => array(
				'empty' => array(
					'rule'			=> 'notEmpty',
					'message' 		=> __('Please enter a name for the category', true),
					'last' 			=> true,
                ),
				'maxlength' => array(
					'rule'			=> array('maxlength', 100),
					'message'		=> __('Category names can only be 100 characters long.', true),
					'last' 			=> true,
                ),
            ),
        );
    }
    
    /**
     * 1)
     * update solr index with saved data
     */
    function afterSave(){

			/*	App::import('model','Solr');
				App::import('model','User');
				App::import('model','Paper');

				if($this->id){

					//get User information
					$paper = new Paper();
					$paperData = $paper->read(null, $this->data['Category']['paper_id']);
					$this->data['Category']['paper_id'] = $paperData['Paper']['id'];
					$this->data['Category']['title'] = $paperData['Paper']['title'];
					$this->data['Category']['user_id'] = $paperData['User']['id'];
					$this->data['Category']['user_name'] = $paperData['User']['username'];
						
						
					$this->data['Category']['index_id'] = 'category_'.$this->id;
					$this->data['Category']['id'] = $this->id;
					$this->data['Category']['type'] = Solr::TYPE_CATEGORY;

					//$this->data['Category']['user_id'] = $userData['User']['id'];
					//$this->data['Category']['user_name'] = $userData['User']['username'];
					$solr = new Solr();
					$solr->add($this->addFieldsForIndex($this->data));
					

				}
				else{
					$this->log('Error while adding category to solr! No category id in afterSave()');
				}
				*/
			}


			/**
			 * @todo move to abstract for all models
			 * Enter description here ...
			 */
			private function addFieldsForIndex($data){
				unset($data['Category']['modified']);
				unset($data['Category']['created']);
				unset($data['Category']['route_id']);
				debug($data);
				$solrFields = array();
				$solrFields['Category']['created'] = $data['Category']['created'];

				return $data;
			}

			function delete($id){
			//	$this->removeCategoryFromSolr($id);
				return parent::delete($id);
			}

			/**
			 * remove the user from solr index
			 *
			 * @param string $id
			 */
			function removeCategoryFromSolr($id){
				App::import('model','Solr');
				$solr = new Solr();
				$solr->delete(Solr::TYPE_CATEGORY . '_' . $id);
			}



				
}
?>