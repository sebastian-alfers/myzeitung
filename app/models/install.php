<?php
class Install extends AppModel {
	var $name = 'Install';

	var $primaryKey = 'namespace';

	public function run($sql){
		if($this->query($sql)){
			return true;
		} else {
			$error_msg = mysql_error();
			CakeLog::write(InstallController::LOG_FILE, '**********');
			CakeLog::write(InstallController::LOG_FILE, 'Error while placing sql from install: ' . $error_msg);
			CakeLog::write(InstallController::LOG_FILE, '**********');
			return false;
		}

	}
}
?>