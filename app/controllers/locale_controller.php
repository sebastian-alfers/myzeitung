<?php
class LocaleController extends AppController {

    var $name = 'Locale';

    var $uses = array();

	public function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow('eng', 'deu');

	}

    function eng(){
        $this->Session->write('Config.language', 'eng');
        $this->Cookie->write('lang', 'eng', false, '20 days');

        $this->redirect($this->referer());
    }

    function deu(){
        $this->Session->write('Config.language', 'deu');
        $this->Cookie->write('lang', 'deu', false, '20 days');

        $this->redirect($this->referer());
    }
}
