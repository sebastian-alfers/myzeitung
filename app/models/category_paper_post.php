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
	/*	'Reposter' => array(
			'className' => 'User',
			'foreignKey' => 'reposter_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
			),	 */
		'Paper' => array(
			'className' => 'Paper',
			'foreignKey' => 'paper_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'counterCache' => 1,
            'counterScope' => array('Paper.enabled' => true )

			),
		'Category' => array(
			'className' => 'Category',
			'foreignKey' => 'category_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'counterCache' => 1,
            'counterScope' => array('Category.enabled' => true )
            
			),
	/*	'PostUser' => array(
			'className' => 'PostUser',
			'foreignKey' => 'post_user_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',

			),
        'ContentPaper' => array(
			'className' => 'ContentPaper',
			'foreignKey' => 'content_paper_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',

			),
*/
    );

    function __construct(){
        parent::__construct();
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
        App::import('model','ContentPapers');
        $this->ContentPaper = new ContentPaper();
        //get all content_associations from any user/topic to any paper/category
        $allContentData = $this->ContentPaper->find('all');

    }

    function updateCounterCache($keys = array(), $created = false){
        $keys = empty($keys) ? $this->data[$this->alias] : $keys;
        //update paper
        $count = $this->find('count',array('conditions' => array('CategoryPaperPost.paper_id' => $keys['paper_id']),'fields' => 'distinct CategoryPaperPost.post_id'));
        $this->Paper->id = $keys['paper_id'];
        $this->Paper->saveField('post_count', $count, array('callbacks' => 0, 'validate' => 0));

        //update category
        if(isset($keys['category_id']) && !empty($keys['category_id'])){
            $count = $this->find('count',array('conditions' => array('CategoryPaperPost.category_id' => $keys['category_id']),'fields' => 'distinct CategoryPaperPost.post_id'));
            $this->Category->id = $keys['category_id'];
            $this->Category->saveField('post_count', $count, array('callbacks' => 0, 'validate' => 0));
        }

    }



}
?>