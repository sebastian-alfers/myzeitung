<?php
class CategoriesController extends AppController {


	var $name = 'Categories';
	
	var $uses = array('Paper', 'Category', 'ContentPaper');
	var $helpers = array('MzTime', 'Image', 'Html', 'Javascript', 'Ajax');
    var $components = array('Upload');




    /**
     * @return void

	function index($paper_id) {
		if(!$paper_id){
            $this->Session->setFlash(__('No Permission', true));
			$this->redirect(array('controller' => 'papers', 'action' => 'index'));
        }
        if(!$this->canEdit('Paper', $paper_id, 'owner_id')){
            $this->Session->setFlash(__('No Permission', true));
			$this->redirect(array('controller' => 'papers', 'action' => 'index'));
        }
        $this->Paper->contain(array('Category'));
        $paper = $this->Paper->read(null, $paper_id);

        $this->set('hash', $this->Upload->getHash());

        //get number of authors for frontpage
        $this->ContentPaper->contain();
        $frontpage_authors = $this->ContentPaper->find('count', array('conditions' => array('ContentPaper.paper_id' => $paper['Paper']['id'], 'ContentPaper.category_id' => NULL)));
        $paper['Paper']['frontpage_authors_count'] = $frontpage_authors;

        $this->set('paper', $paper);
	}
    */

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid category', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('category', $this->Category->read(null, $id));
	}

	function addcategory(){


	}

	/**
	 * add a category for a paper or for a category(subcategory)
	 * @param type     category / paper
	 * @param id    	paper_id / parent_category_id
	 * @todo rewrite this crap with proper params 

	function add() {


		if (empty($this->data)) {
			//check for param in get url
			if(empty($this->params['pass'][0]) || !isset($this->params['pass'][0])){
				$this->Session->setFlash(__('Invalid parameters', true));
				$this->redirect(array('controller' => 'papers', 'action' => 'index'));
			}
			//pass param for paper or category for hidden value
			if($this->params['pass'][0] == Category::PARAM_PAPER){
				$this->set('paper_id', $this->params['pass'][1]);
			}
			elseif($this->params['pass'][0] == Category::PARAM_CATEGORY){
				$this->set('parent_id', $this->params['pass'][1]);
			}
				
		}


        /*
        if(isset($this->data['Category']['parent_id']) && !empty($this->data['Category']['parent_id'])){
            //read paper from parent id
            $this->Category->contain('Paper.id', 'Paper.owner_id');
            $category = $this->Category->read(null, $this->data['Category']['parent_id']);

            if($category['Category']['id'] && $category['Paper']['id']){
                    $this->data['Category']['paper_id'] = $category['Paper']['id'];
            }
            else{
                $this->Session->setFlash(__('Unable to read parent category.', true));
                $this->redirect(array('controller' => 'papers', 'action' => 'index'));
            }

        }

        if(isset($this->data['Category']['paper_id']) && !empty($this->data['Category']['paper_id'])){
            if(!$this->canEdit('Paper', $this->data['Category']['paper_id'], 'owner_id')){
                $this->Session->setFlash(__('No Permission', true));
                $this->redirect(array('controller' => 'papers', 'action' => 'view', $this->data['Category']['paper_id']));
            }
        }

        $this->Category->create();
        if ($this->Category->save($this->data)) {
            $this->Session->setFlash(__('The category has been saved', true), 'default', array('class' => 'success'));
            $this->redirect(array('controller' => 'papers', 'action' => 'view', $this->data['Category']['paper_id']));
        } else {
            $this->Session->setFlash(__('The category could not be saved. Please, try again.', true));
        }

	}
    */

	function edit($id = null) {

        if($id == null && isset($this->data['Category']['post_id']) && !empty($this->data['Category']['post_id'])){
            $id = $this->data['Category']['post_id'];
        }


		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid category', true));
			$this->redirect(array('action' => 'index'));
		}


        if(!$this->canEdit('Paper', $this->data['Category']['paper_id'], 'owner_id')){
            $this->Session->setFlash(__('No Permission', true));
			$this->redirect(array('controller' => 'papers', 'action' => 'index'));
        }


		if (!empty($this->data)) {

            if(!isset($this->data['Category']['id']) || $this->data['Category']['id'] == ''){
                $this->Category->create();
            }


			if ($this->Category->save($this->data)) {
				$this->Session->setFlash(__('The category has been saved', true), 'default', array('class' => 'success'));
				$this->redirect(array('controller' => 'papers',  'action' => 'view', $this->data['Category']['paper_id']));
			} else {
				$this->Session->setFlash(__('The category could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Category->read(null, $id);
		}
		$papers = $this->Category->Paper->find('list');
		$routes = $this->Category->Route->find('list');
		$this->set(compact('papers', 'routes'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for category', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Category->delete($id, true)) {
			$this->Session->setFlash(__('Category deleted', true), 'default', array('class' => 'success'));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Category was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
?>