<?php

/**
 * class that capules a json response
 */
class JsonResponse extends AppModel {
    //name of variable for view
    const RESPONSE = 'response';

    //status of json call
    const SUCCESS = 'success';
    const FAILURE = 'failure';

    //fields within json data
    const RESPONSE_DATA = 'data';
    const RESPONSE_STATUS = 'status';

	var $name = 'JsonResponse';
    var $useTable = false;

    private $_responseStatus = self::FAILURE;
    private $_responseData = array();

    function __construct(){
        parent::__construct();
    }


    /**
     * gets data and sets them as response
     * set status to success
     *
     * @param  $data mixed
     * @return json_encoded string
     */
    public function success($data = array()){
        $this->_responseStatus = self::SUCCESS;
        $this->_setResponseData($data);

        return $this->_generateResult();
    }

    /**
     * gets data and sets them as response
     * set status to success
     *
     * @param  $data mixed
     * @return json_encoded string
     */
    public function failure($data = array()){
        $this->_responseStatus = self::FAILURE;
        $this->_setResponseData($data);

        return $this->_generateResult();
    }

    /**
     * set data to be returned
     *
     * @param array $data mixed
     */
    private function _setResponseData($data = array()){
        $this->_responseData = $data;

    }

    /**
     * builds array with status and data,
     * encode to json and returns the string
     *
     * @return string - json encoded
     */
    private function _generateResult(){
        return  json_encode(array(
            self::RESPONSE_STATUS => $this->_responseStatus,
            self::RESPONSE_DATA => $this->_responseData
        ));
    }

    /**
     * @param  $status - msg of custom status
     * @param array $data
     * @return void
     */
    public function customStatus($status, $data = array()){
        $this->_responseStatus = $status;
        $this->_setResponseData($data);

        return $this->_generateResult();
    }


}
?>