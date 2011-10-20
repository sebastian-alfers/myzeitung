<?php
class AjaxController extends AppController {

    var $helpers = array('Image');
    var $components = array('RequestHandler', 'JqImgcrop', 'Upload');

    var $name = 'Ajax';
    var $uses = array('JsonResponse', 'Complaint', 'Paper', 'UrlContentExtract', 'Conversation', 'ConversationMessage', 'Category');

	public function beforeFilter(){
		parent::beforeFilter();
		//declaration which actions can be accessed without being logged in
		$this->Auth->allow('validateEmail');
	}


    /**
     * action to be called with json suffix
     * like /upload/picture.json
     * @return void
     */
/*
    function uploadPicture(){

        $this->log('enter upload');

        $hash = $this->params['form']['hash'];
        $file = $this->params['form']['file'];

        if($hash && $file){
            $imgPath = 'img'.DS.'tmp'.DS.$hash.DS;
            //remove whitespace etc from img name
            $file['name'] = $this->Upload->transformFileName($file['name']);
            $uploaded = $this->JqImgcrop->uploadImage($file, $imgPath, '');

            $return = array(
                'name' => $file['name'],
                'path' => $imgPath.$file['name'],
                'type' => $file['type'],
                'size' => $file['size']
            );

            $this->log($this->JsonResponse->success($return));


            $this->set(JsonResponse::RESPONSE, $this->JsonResponse->success($return));
        }
        else{
            $this->log('hash / files missing for ajx upload picture');
            $this->set(JsonResponse::RESPONSE, $this->JsonResponse->failure());
            //return error json
        }
    }
    */

    /**
     * we use the Complaint model to validate a string to be an email
     *
     * @return void
     */
    function validateEmail(){

        $email = '';

        if(isset($this->params['form']['email'])){
            $email = $this->params['form']['email'];
        }

        $this->Complaint->set(array('reporter_email' => $email));

        if($this->Complaint->validates(array('fieldList' => array('reporter_email')))){

            $this->set(JsonResponse::RESPONSE, $this->JsonResponse->success());
        }
        else{
            $messages = $this->Complaint->invalidFields();
            $this->set(JsonResponse::RESPONSE, $this->JsonResponse->failure(array('msg' => $messages['reporter_email'])));
        }
    }
    
    function validateNewMessage(){
    	
        $this->Conversation->set(array('title' => $this->params['form']['title']));
        $this->ConversationMessage->set(array('message' => $this->params['form']['message']));
    	
    	if(!$this->Conversation->validates(array('fieldList' => array('title')))){

			$messages = $this->Conversation->invalidFields();
            $this->set(JsonResponse::RESPONSE, $this->JsonResponse->failure(array('msg' => $messages['title'])));    		
    	}
    	elseif(!$this->ConversationMessage->validates(array('fieldList' => array('message')))){

			$messages = $this->ConversationMessage->invalidFields();
            $this->set(JsonResponse::RESPONSE, $this->JsonResponse->failure(array('msg' => $messages['message'])));    		    	
    	}
    	else{
            $this->set(JsonResponse::RESPONSE, $this->JsonResponse->success());
    	}
    }
    
    function validateNewCategory(){
        $this->Category->set(array('name' => $this->params['form']['name']));
    	
    	if(!$this->Category->validates(array('fieldList' => array('name')))){
			$messages = $this->Category->invalidFields();
            $this->set(JsonResponse::RESPONSE, $this->JsonResponse->failure(array('msg' => $messages['name'])));
    	}
    	else{
            $this->set(JsonResponse::RESPONSE, $this->JsonResponse->success());
    	}    
    }

        /**
     * we use the Paper model to validate a string to be an url
     *
     * @return void
     */
     function validateUrl(){

        $url = '';

        if(isset($this->params['form']['url'])){
            $url = $this->params['form']['url'];
        }

        $this->Paper->set(  array('url' => $url));

        if($this->Paper->validates(array('fieldList' => array('url')))){
            $this->set(JsonResponse::RESPONSE, $this->JsonResponse->success());

        }
        else{
            $messages = $this->Paper->invalidFields();
            $this->set(JsonResponse::RESPONSE, $this->JsonResponse->failure(array('msg' => $messages['url'])));
         }
    }


    /**
     *
     *
     * @return json-string
     */
    function getVideoPreview(){

        if(!isset($this->params['form']['url']) || !isset($this->params['form']['hash'])){
            $this->set(JsonResponse::RESPONSE, $this->JsonResponse->failure());
            return;
        }

        //from json-request
        $url = $this->params['form']['url'];
        $hash= $this->params['form']['hash'];

        if(substr($url, 0, 7) != 'http://'){
            $url = 'http://'.$url;
        }
        $this->Paper->set(  array('url' => $url));


        if($this->Paper->validates(array('fieldList' => array('url')))){
            $this->log($url);
            //check if it is video url
            $pattern = '/youtube/i';
            if(!preg_match($pattern, $url)){
                $this->log('a');
                $this->set(JsonResponse::RESPONSE, $this->JsonResponse->customStatus('valid_url_no_video', array('msg' => __('Please enter a youtube or vimeo link', true))));
                //$this->set(JsonResponse::RESPONSE, $this->JsonResponse->failure());
                return;
            }
            else{
                $this->log('b');
                //fetch preview img
                $graph = OpenGraph::fetch($url);

                $this->log(count($graph));

                $open_graph_data = array();
                foreach ($graph as $key => $value) {
                        $open_graph_data[$key] = $value;
                }

                if(count($open_graph_data) > 1){

                    //check if file exists

                    $url = getimagesize($open_graph_data['image']);

                    if(is_array($url))
                    {
                        //copy preview from remote url to tmp
                        $image = new GetImage;
                        // just an image URL
                        $image->source = $open_graph_data['image'];

                        $path = $this->Upload->getWebrootUrl().$this->Upload->getPathToTmpHashFolder($hash);

                        if(!is_dir($path)){
                            if (!mkdir($path, 0755, true)) {
                                $this->set(JsonResponse::RESPONSE, $this->JsonResponse->failure());
                                return;
                            }
                        }

                        //check if file exists

                        $file_name = basename($image->source);
                        $view_file_path = '/img/tmp/'.$hash.'/';
                        $view_file_name = $file_name;

                        $file_name = uniqid().'_';//add a prefix, orig file_name will be concatinated later
                        $view_file_name =  $file_name.$view_file_name;

                        $image->save_to = $path.$file_name;
                        $get = $image->download('gd'); // using GD


                        if($get){
                            $open_graph_data['image'] = $view_file_path.$view_file_name;


                            $this->set(JsonResponse::RESPONSE, $this->JsonResponse->success(array('open_graph_data' => $open_graph_data, 'file_name' => $view_file_name)));
                            return;
                        }
                    }
                    else{
                        $this->set(JsonResponse::RESPONSE, $this->JsonResponse->failure());
                        return;
                    }
                }
            }
        }
        else{
            $messages = $this->Paper->invalidFields();
            $this->set(JsonResponse::RESPONSE, $this->JsonResponse->failure(array('msg' => $messages['url'])));
         }
        //$url = $this->UrlContentExtract->getVideoPreview($url);

        //$this->set(JsonResponse::RESPONSE, $this->JsonResponse->success(array('url' => $url)));

    }

}

?>