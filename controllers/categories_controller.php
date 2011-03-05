<?php
class CategoriesController extends AppController {

	const PARAM_CATEGORY = 'category';
	const PARAM_PAPER	 = 'paper';

	var $name = 'Categories';

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
	 *
	 */
	function add() {

		if (empty($this->data)) {
			//check for param in get url
			if(empty($this->params['pass'][0]) || !isset($this->params['pass'][0])){
				$this->Session->setFlash(__('No param for category', true));
				$this->redirect(array('controller' => 'papers', 'action' => 'index'));
			}
			//pass param for paper or category for hidden value
			if($this->params['pass'][0] == 'paper'){
				$this->set('paper_id', $this->params['pass'][1]);
			}
			elseif($this->params['pass'][0] == 'category'){
				$this->set('parent_id', $this->params['pass'][1]);
			}
				
		}
		else{
			if(isset($this->data['Category']['parent_id']) && !empty($this->data['Category']['parent_id'])){
				//read paper from parent id
				$category = $this->Category->read(null, $this->data['Category']['parent_id']);

				if($category['Category']['id'] && $category['Paper']['id']){
						$this->data['Category']['paper_id'] = $category['Paper']['id'];
				}
				else{
					$this->Session->setFlash(__('Error! Unable to read parent category!', true));
					$this->redirect(array('controller' => 'categories', 'action' => 'index'));
				}
			}
				
			$this->Category->create();
			if ($this->Category->save($this->data)) {
				$this->Session->setFlash(__('The category has been saved', true));
				$this->redirect(array('controller' => 'papers', 'action' => 'index'));
			} else {
				$this->Session->setFlash(__('The category could not be saved. Please, try again.', true));
			}
		}
		$papers = $this->Category->Paper->find('list');
		$routes = $this->Category->Route->find('list');
		$this->set(compact('papers', 'routes'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid category', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Category->save($this->data)) {
				$this->Session->setFlash(__('The category has been saved', true));
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
		if ($this->Category->delete($id)) {
			$this->Session->setFlash(__('Category deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Category was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
?>