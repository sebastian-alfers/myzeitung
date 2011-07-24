<?php
class CategoriesController extends AppController {


	var $name = 'Categories';
	
	var $uses = array('Paper', 'Category');
	var $helpers = array('MzTime', 'Image', 'Html', 'Javascript', 'Ajax');
	
	function index() {
		$this->Category->recursive = 0;
		$this->set('categories', $this->paginate());
	}

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
	 */
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
		else{
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
				$this->Paper->contain();
				$paper = $this->Paper->read(array('id', 'owner_id'),$this->data['Category']['paper_id']);
				$owner_id = $paper['Paper']['owner_id'];
			}
			if($this->Auth->user('id') != $owner_id){
					$this->Session->setFlash(__('This paper does not belong to you. You cannot add a category.', true));
					$this->redirect(array('controller' => 'papers', 'action' => 'view', $this->data['Category']['paper_id']));
			}
				
			$this->Category->create();
			if ($this->Category->save($this->data)) {
				$this->Session->setFlash(__('The category has been saved', true), 'default', array('class' => 'success'));
				$this->redirect(array('controller' => 'papers', 'action' => 'view', $this->data['Category']['paper_id']));
			} else {
				$this->Session->setFlash(__('The category could not be saved. Please, try again.', true));
			}
		}
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid category', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Category->save($this->data)) {
				$this->Session->setFlash(__('The category has been saved', true), 'default', array('class' => 'success'));
				$this->redirect(array('action' => 'index'));
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