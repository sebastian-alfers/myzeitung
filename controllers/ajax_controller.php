<?php
class AjaxController extends AppController {

    var $helpers = array('Image');
    var $components = array('RequestHandler', 'JqImgcrop', 'Upload');

    var $name = 'Ajax';
    var $uses = array('JsonResponse');

    /**
     * action to be called with json suffix
     * like /upload/picture.json
     * @return void
     */
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

}

?>