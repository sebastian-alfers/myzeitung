<?php

App::import('Core', 'Dispatcher');
if (!class_exists('Dispatcher')) {
	require CAKE . 'dispatcher.php';
}



class RssCrawlShell extends Shell{

    var $_limit = 5;

    public $_dispatcher;

    public function main(){

        if(isset($this->params['limit']) && !empty($this->params['limit'])){
            $this->_limit = $this->params['limit'];
        }

        $this->_dispatcher = new Dispatcher();


        $result = $this->_dispatcher->dispatch('/rss/crawlNextFeeds', array('robot' => true, 'limit' => $this->_limit, 'bare' => true, 'return' => true));

        $this->out("done...");
        $this->out(json_encode($result));

    }
}
?>