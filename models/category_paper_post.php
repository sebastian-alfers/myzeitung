<?php
class CategoryPaperPost extends AppModel {
	var $name = 'CategoryPaperPost';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'Post' => array(
			'className' => 'Post',
			'foreignKey' => 'post_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
			),
		'Paper' => array(
			'className' => 'Paper',
			'foreignKey' => 'paper_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
			),
		'Category' => array(
			'className' => 'Category',
			'foreignKey' => 'category_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''

			)
			);

			function __construct(){
				parent::__construct();

				App::import('model','ContentPapers');
				$this->ContentPaper = new ContentPaper();
			}

			/**
			 * builds the complete index table
			 *
			 * if $truncate = true  -> truncate table and build index
			 * if $truncate = false -> build index and add only posts that are not in the index
			 *
			 * @
			 *
			 */
			public function buildWholeIndex($truncate = true){
				//get all content_associations from any user/topic to any paper/category
				$allContentData = $this->ContentPaper->find('all');

				debug($allContentData);
			}





}
?>