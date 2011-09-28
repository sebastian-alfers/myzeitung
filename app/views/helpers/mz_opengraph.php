<?php



class MzOpengraphHelper extends AppHelper {


    var $helpers = array('Cf');

    const DEFAULT_TYPE = self::TYPE_WEBSITE;

    const TYPE_ARTICLE = ''; //post
    //const TYPE_AUTHOR = 'author';   //user
    const TYPE_BLOG = 'blog';       //paper / user
    const TYPE_WEBSITE = ''; //default (rest)


    var $_data = array();


    function __construct(){

        debug($this->params);


    }


    public function set($key, $value){
        debug($this->_data);
        debug($key);
        $this->_data[$key] = $value;

        debug($this->_data);
    }

    /**
     * set open graph data to view
     *
     * @return void
     */
    function beforeRender(){

        debug('before');
        debug($this->_data);



        $this->getView()->set('open_graph', $this->_data);
    }


    /**
     * Access to the view for special operatoins
     */
    protected function getView() {
        return ClassRegistry::getObject('view');
    }


}
