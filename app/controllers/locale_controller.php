<?php
class LocaleController extends AppController {

    var $name = 'Locale';

    var $components = array('MzSession');

    var $uses = array();

	public function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow('eng', 'deu');

	}

    function eng(){
        $this->MzSession->setLocale('eng');

        $this->redirect($this->referer());
    }

    function deu(){

        $this->MzSession->setLocale('deu');

        $this->redirect($this->referer());
    }


}
