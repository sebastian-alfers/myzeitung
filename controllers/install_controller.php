<?php
/**
 * based on the install foler
 * - this folder contians namespaces (folder)
 * - each folder contains files containing (sql)commands to be run for that version
 * - a install file is named like:
 * -- [namespace]_[version].php
 *
 * e.g.
 * namespces:
 * - paper, user
 *
 * install files
 * - paper/paper_0.1.0.php
 * - paper/paper_0.1.1.php
 * - user/user_0.1.0.php
 * - user/user_0.1.1.php
 *
 *  versioning:
 *  - major version updates (e.g. from 0.2.7 to 1.0) should only be used
 *    for major changes
 *
 * Enter description here ...
 * @author sebastianalfers
 *
 */
class InstallController extends AppController {

	var $name = 'Install';

	//all namespaces /folders
	var $_installFolders = null;

	//namespaces and containing files
	var $_namespaceFiles = null;

	//currently installed DB versions
	var $_systemInstallations = null;

	//namespaces, files and corresponding DB versions
	var $_globalSystemStatus = null;

	//collects versions to be updated to DB
	var $_latestVersionUpdates = null;

	//boolean that shows is installations have been done during a call to this controller
	var $_installed = false;

	//logfile for installations
	const LOG_FILE = 'install';

	//logStack
	var $_logStack = array();

	//folder contains namespaces in root folder of cake
	const INSTALL_FOLDER = 'install';

	//array index
	const DB_INSTALLED_VERSION = 'db_installed_version';

	//array index
	const FILE_VERSIONS = 'file_versions';

	/**
	 * construct
	 */
	public function __construct(){
		parent::__construct();
		$this->_log('init installation');
	}
	/**
	 * performs install. does not place mysql commands if dryRun = true
	 * Enter description here ...
	 */
	function index(){
		if($this->isSystemUpToDate()){
			$this->_log('noting to update');
		}
		else{
			$this->_log('system updated successfully');
		}
		debug($this->_logStack);
	}

	/**
	 * reads all namespaces and containing install files and checks,
	 * if DB is up to date
	 */
	function isSystemUpToDate(){
		$this->_installed = false;

		foreach($this->_getGlobalSystemStatus() as $namespaceName => $data){
			if(count($data) > 0){
				$currentDbVersion = key($data[self::DB_INSTALLED_VERSION]);

				//check, if a version from file is larger then the installed version from DB
				foreach($data[self::FILE_VERSIONS] as $intFileVersion => $fileName){
					if($intFileVersion > $currentDbVersion){
						//since the list of files is sorted by version, install order is corrent
						//$this->_installFile($namespaceName, $fileName);
						$this->_installNamespace($namespaceName, array($fileName));//can be uses for multiple files as well
					}
				}
			}


		}
		$this->_updateLatestVersionsInDB();
		//_installed == false -> nothing has been installed -> upToDate == true
		return !$this->_installed;
	}

	/**
	 * provides an overview of the system
	 * 1) installs / initializes new modules
	 * 2) get all versions installed in DB for namespaces
	 * 3) get an overview of files and versions of file-systems
	 *
	 * @param bool $initNamespaces - install new namespaces before getting system status
	 */
	private function _getGlobalSystemStatus(){
		if($this->_globalSystemStatus != null){
			return $this->_globalSystemStatus;
		}


		//install new modules in corret version, install files in correct order
		$this->_globalSystemStatus = $this->_initializeNamespaces();



		foreach($this->_getSystemInstallations() as $index => $data){

			$data = $data['Install'];
			foreach($this->getNamespacesFiles() as $namespaceName => $namespaceFiles){

				if($data['namespace'] == $namespaceName){
					//found namespace in db
					$currentDbVersion = $data['version'];

					$this->_globalSystemStatus[$namespaceName][self::FILE_VERSIONS] = $this->_tranformFilesToSortedVersions($namespaceName, $namespaceFiles);

					//
					$intVersion = $this->_getIntVersionFromFileName($namespaceName, $data['version']);
					$this->_globalSystemStatus[$namespaceName][self::DB_INSTALLED_VERSION][$intVersion] =  $data['version'];
				}
			}
		}

		return $this->_globalSystemStatus;
	}

	/**
	 * updates or create last installed version of namespace in db
	 */
	private function _updateLatestVersionsInDB(){
		if(count($this->_latestVersionUpdates) == 0) return;

		foreach($this->_latestVersionUpdates as $namespaceName => $data){

			if($this->_isNamespceInstalledInDB($namespaceName)){
				//$this->data
			}
			else{
				$this->Install->create();
			}
			if ($this->Install->save(array('Install' => $data))){
				$this->_log('Updated DB version of namespace ' . $namespaceName . ' to version ' . $data['version']);
			}
		}
	}


	/**
	 * setting the namespaces /folder to array
	 * install uninstalled namespaces
	 *
	 */
	private function _initializeNamespaces(){
		$this->_log('initialize Namespaces');

		foreach($this->getInstallNamespaces() as $namespaceName => $data){
			if(!isset($this->_globalSystemStatus[$namespaceName])){
				$this->_globalSystemStatus[$namespaceName] = array();

				if(!$this->_isNamespceInstalledInDB($namespaceName)){
					$this->_log('install new namespace ' . $namespaceName);
					if($this->_installNamespace($namespaceName)){
						$this->_log('Successfully Installed new Namespace ' . $namespaceName);
					}
					else{
						$this->_log('Error while installing new namespace ' . $namespaceName);
					}
				}
			}
		}
			
		//update new installes namespaces in DB
		$this->_updateLatestVersionsInDB();
			
		//reset values
		$this->_latestVersionUpdates = array();
		$this->_namespaceFiles = null;
		$this->_systemInstallations = null;

		return $this->_globalSystemStatus;
	}

	/**
	 * Gets a namespace (folder) and install containing files
	 * if $files contains files, install only this files
	 *
	 * @param string $namespace
	 * @param array $files
	 */
	private function _installNamespace($namespaceName, $files = array()){
		if(empty($files)){
			//all files -> new installation of namespace
			$files = $this->getFilesInNamespace($namespaceName);
			$files = $this->_tranformFilesToSortedVersions($namespaceName, $files);
		}

		if(empty($files)){
			$this->_log('Can not install namespace '. $namespaceName . '. No files to install.');
			return false;
		}

		foreach($files as $file){
			if(!$this->isInstallFileValid($namespaceName, $file)){
				//log
				$this->_log('ERROR! file ' . $namespaceName . '/' . $file . ' is not valid! Can not install it.');
				return false;
			}

			if($this->_installFile($namespaceName, $file)){
				$this->_installed = true;
			}
			else{
				$this->_installed = false;
				return false;
			}

		}

		if($this->_latestVersionUpdates == null){
			$this->_latestVersionUpdates = array();
		}
		if(!isset($this->_latestVersionUpdates[$namespaceName])){
			$this->_latestVersionUpdates[$namespaceName] = array();
		}
		//override until last file ist installed
		$this->_latestVersionUpdates[$namespaceName]['namespace'] = $namespaceName;
		$this->_latestVersionUpdates[$namespaceName]['version'] = max($files);

		return true;

	}

	/**
	 * installs a file
	 *
	 *
	 * @param unknown_type $file
	 */
	private function _installFile($namespaceName, $file){

		$fullPath = $this->getRootFolder().self::INSTALL_FOLDER.DS.$namespaceName.DS.$file;

		if(file_exists($fullPath)){
			$sql = array();//important for includes!!!
			$log = '';//important for logging!!!
			try {
				require_once $fullPath;
				if(!is_array($sql)){
					$this->_log('error while installing ' . $namespaceName . '/' . $file . '. $sql must be array');
					return false;
				}
				foreach($sql as $query){
					if($this->_run($query)){
						$this->_log('installed file ' . $namespaceName . '/' . $file);
					}
					else{
						$this->_log('SQL error in file ' . $namespaceName . '/' . $file);
						return false;
					}
					$this->_log($log);
					return true;
				}

			} catch (Exception $e) {
				$this->_log($e);
				return false;
			}
		}
		else{
			$this->_log('Error wile loading ' . $fullPath);
			return false;
		}

	}

	/**
	 * runs a given sql statement
	 * called by the install scripts included
	 *
	 * @param string $sql
	 * @param string $logMsg
	 */
	private function _run($sql){
		if($this->Install->run($sql)){
			$this->_log(' > ' . $sql);
			return true;
		}
		else{
			$this->_log('Error while placing query: ' . $sql);
			return false;
		}
	}

	private function _log($msg){
		CakeLog::write(self::LOG_FILE, $msg);
		$this->_logStack[microtime()] = $msg;
	}

	/**
	 * checks, if a namespace (folder) is installed in DB
	 *
	 * @param string $namespace
	 */
	private function _isNamespceInstalledInDB($namespace){
		foreach($this->_getSystemInstallations() as $index => $data){
			$data = $data['Install'];
			if($data['namespace'] == $namespace) return true;
		}
		return false;
	}

	/**
	 * transforms a "list[int] => file_name.php" into
	 * a "list[(int)version] => file_name.php" and sorts it by version
	 *
	 * @return string $namespaceName - name of namespace (folder)
	 * @param array $namespaceFiles
	 * @return array $namespaceFilesVersions
	 */
	private function _tranformFilesToSortedVersions($namespaceName, $namespaceFiles){

		$namespaceFilesVersions = array();
		foreach($namespaceFiles as $file){
			if($this->isInstallFileValid($namespaceName, $file)){
				$intVersion = $this->_getIntVersionFromFileName($namespaceName, $file);
				$namespaceFilesVersions[$intVersion] = $file;
			}
			else{
				$this->_log('can not install file ' . $file . ' in namespace ' . $namespaceName);
			}

		}
		//sort by version (key of array)
		ksort($namespaceFilesVersions);
		return $namespaceFilesVersions;
	}

	private function _getIntVersionFromFileName($namespaceName, $file){
		//remove namespeace prefix and "_"
		$intVersion = substr($file, strlen($namespaceName) + 1);//also remove "_" in file name

		//remove file extentsion (.php)
		$intVersion = substr($intVersion, 0, -4);

		//make an integer out of dot-separated string
		$intVersion = str_replace('.', '', $intVersion);

		if(is_int((int)$intVersion)){
			$intVersion = (int)$intVersion;
			return $intVersion;
		}
		else{
			$this->_log('Error while getting the (int) version of the file ' . $file . ' in namespace ' . $namespaceName);
			return false;
		}
	}

	/**
	 * reads the data from DB
	 */
	private function _getSystemInstallations(){
		if($this->_systemInstallations !== null){
			return $this->_systemInstallations;
		}
			
		$this->_systemInstallations = $this->Install->find('all');
		return $this->_systemInstallations;
	}


	/**
	 * get all namspaces (folder) and files
	 */
	function getNamespacesFiles(){
		if($this->_namespaceFiles !== null){
			return $this->_namespaceFiles;
		}
		//get folder
		$namespaceNames = array();
		$namespaceNames = $this->getInstallNamespaces();

		foreach($namespaceNames as $namespaceName => $files){
			$namespaceNames[$namespaceName] = $this->getFilesInNamespace($namespaceName);
		}
		$this->_namespaceFiles = $namespaceNames;
		return $this->_namespaceFiles;
	}

	/**
	 * get a list of files in dir
	 * @param string $namespaceName
	 */
	function getFilesInNamespace($namespaceName){
		$files = array();
		$dir = $this->getRootFolder().self::INSTALL_FOLDER.DS.$namespaceName.DS;
		if (is_dir($dir)) {
			if ($dh = opendir($dir)) {
				while (($file = readdir($dh)) !== false) {
					if(strlen($file) > 2){//remove . and ..
						if($this->isInstallFileValid($namespaceName, $file)){
							$files[] = $file;
						}
						else{
							$this->_log('The file ' . $file . ' in namespace ' . $namespaceName . ' is not valid and will not be touched!');
						}

					}
				}
				closedir($dh);
			}
			else{
				$this->_log('Not able to open directory: ' . $dir);
			}
		}
		else{
			$this->_log('Not able to read directory: ' . $dir);
		}
		return $files;
	}

	/**
	 * an install file places in a namespace MUST contain
	 * the name of the namespace (folder) as the starting letter
	 *
	 * @param string $namespaceName
	 * @param string $file
	 */
	function isInstallFileValid($namespaceName, $file){
		//check file type
		if('.php' != substr($file, -4)){
			$this->_log('Namespace-File validation Error. Wrong file extension. Only .php allowed. The file ' . $file . ' in namespace ' . $namespaceName . ' is wrong format. Format should be: [namespace]_[version].php');
			return false;
		}

		//check if the file starts with namespace
		if($namespaceName != substr($file, 0, strlen($namespaceName))) {
			$this->_log('Namespace-File validation Error. Missing / Wrong namespace in name. The file ' . $file . ' in namespace ' . $namespaceName . ' is wrong format. Format should be: [namespace]_[version].php');
			return false;
		}

		if(substr($file, strlen($namespaceName), 1) != '_'){
			$this->_log('Namespace-File validation Error. Missing "_" after namespace. The file ' . $file . ' in namespace ' . $namespaceName . ' is wrong format. Format should be: [namespace]_[version].php');
			return false;
		}

		return true;
	}

	/**
	 * get all namespaces (folder) in install folder
	 */
	function getInstallNamespaces(){
		if($this->_installFolders == null){
			$this->_installFolders = array();

			$path = $this->getRootFolder().'/'.self::INSTALL_FOLDER . '/';
			if ($handle = opendir($path)) {
				/* This is the correct way to loop over the directory. */
				while (false !== ($file = readdir($handle))) {
					if(strlen($file) > 2)//remove . and ..
					$this->_installFolders[$file] = array();
				}
				closedir($handle);
			}
			else{
				$this->_log('Not able to read install dir in ' . $path);
			}
		}
		return $this->_installFolders;

	}

	/**
	 * get root directory
	 */
	function getRootFolder(){
		return ROOT.DS.APP_DIR.DS;
	}
}
