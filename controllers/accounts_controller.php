<?php
class AccountsController extends AppController {

	var $name = 'Account';
	var $uses = array();

	var $components = array('JqImgcrop', 'Upload');

	function index() {
		echo "account";
	}

	function image(){
		if(!empty($this->data)){
			
			debug($this->data);die();
			
			if($this->Upload->hasImagesInHashFolder($this->data['Account']['hash'])){
				$image = array();
				$image = $this->Upload->copyImagesFromHash($this->data['Account']['hash'], 666, null, $this->data['Account']['images']);
				debug($image);die();
				if(is_array($this->images)){
					debug($this->images);	
				}
			}
			else{
				$this->Session->setFlash(__('Can not save profile image', true));
				$this->redirect($this->referer());
			}
		}

		$this->set('hash', $this->Upload->getHash());
	}


	/**
	 * handle upload of user profile image
	 *
	 */
	function ajxProfileImageProcess(){

		if(isset($_FILES['file'])){


			$file = $_FILES['file'];

			if(!isset($_POST['hash']) || empty($_POST['hash'])){
				$this->log('error. hash value not available. can not upload picture');
				return '{"name":"error"}';
			}
			$hash = $_POST['hash'];
			$img = $file['name'];
			if(!$img){
				return '{"name":"error"}';
			}

			$imgPath = 'img'.DS.'tmp'.DS.$hash.DS;

			//remove whitespace etc from img name
			$file['name'] = $this->Upload->transformFileName($file['name']);

			$uploaded = $this->JqImgcrop->uploadImage($file, $imgPath, '');

			$ret = '{"name":"'.$file['name'].'","path":"' . $imgPath.$file['name'] . '","type":"'.$file['type'].'","size":"'.$file['size'].'"}';
			//$this->log($ret);
			$this->set('files', $ret);
		}

		$this->render('ajxProfileImageProcess', 'ajax');//custom ctp, ajax for blank layout
			

	}

}
?>