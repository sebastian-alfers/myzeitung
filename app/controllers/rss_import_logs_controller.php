<?php
class RssImportLogsController extends AppController {

	var $name = 'RssImportLogs';

    public function beforeFilter()
    {
        parent::beforeFilter();
    }

	function admin_index() {

		$this->RssImportLog->recursive = 0;
		$this->set('rssImportLogs', $this->paginate());
	}

	function admin_view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid rss import log', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('rssImportLog', $this->RssImportLog->read(null, $id));
	}

	function admin_add() {
		if (!empty($this->data)) {
			$this->RssImportLog->create();
			if ($this->RssImportLog->save($this->data)) {
				$this->Session->setFlash(__('The rss import log has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The rss import log could not be saved. Please, try again.', true));
			}
		}
		$rssFeeds = $this->RssImportLog->RssFeed->find('list');
		$this->set(compact('rssFeeds'));
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid rss import log', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->RssImportLog->save($this->data)) {
				$this->Session->setFlash(__('The rss import log has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The rss import log could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->RssImportLog->read(null, $id);
		}
		$rssFeeds = $this->RssImportLog->RssFeed->find('list');
		$this->set(compact('rssFeeds'));
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for rss import log', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->RssImportLog->delete($id)) {
			$this->Session->setFlash(__('Rss import log deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Rss import log was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
