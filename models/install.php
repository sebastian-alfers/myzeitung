<?php
class Install extends AppModel {
	var $name = 'Install';


	public function run($sql){
		try {
			$this->query($sql);
		} catch (Exception $e) {
			debug($e);
		}

	}
}
?>