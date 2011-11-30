<?php
App::import('Helper', 'Cache');

class MzCacheHelper extends CacheHelper {


/**
 * Write a cached version of the file
 *
 * @param string $content view content to write to a cache file.
 * @param sting $timestamp Duration to set for cache file.
 * @return boolean success of caching view.
 * @access private
 */
	function __writeFile($content, $timestamp, $useCallbacks = false) {
		$now = time();

		if (is_numeric($timestamp)) {
			$cacheTime = $now + $timestamp;
		} else {
			$cacheTime = strtotime($timestamp, $now);
		}

		$path = $this->here;
		if ($this->here == '/') {
			$path = 'home';
		}
		$cache = strtolower($this->controllerName);

		if (empty($cache)) {
			return;
		}

        //custom
        //add related models to path
        $models = '';
        if(isset($this->params['models']) && is_array($this->params['models']) && !empty($this->params['models'])){
            foreach($this->params['models'] as $model){
                $models.= '_'. strtolower(Inflector::pluralize($model));
            }

        }

        #$class = $this->controllerName;
        #$class .= 'Controller';
        #$controller = new $class;


		$cache = $cache . $models . '.php';
		$file = '<!--cachetime:' . $cacheTime . '--><?php';

		if (empty($this->plugin)) {
			$file .= '
			App::import(\'Controller\', \'' . $this->controllerName. '\');
			';
		} else {
			$file .= '
			App::import(\'Controller\', \'' . $this->plugin . '.' . $this->controllerName. '\');
			';
		}

		$file .= '$controller =& new ' . $this->controllerName . 'Controller();
				$controller->plugin = $this->plugin = \''.$this->plugin.'\';
				$controller->helpers = $this->helpers = unserialize(\'' . serialize($this->helpers) . '\');
				$controller->base = $this->base = \'' . $this->base . '\';
				$controller->layout = $this->layout = \'' . $this->layout. '\';
				$controller->webroot = $this->webroot = \'' . $this->webroot . '\';
				$controller->here = $this->here = \'' . $this->here . '\';
				$controller->params = $this->params = unserialize(stripslashes(\'' . addslashes(serialize($this->params)) . '\'));
				$controller->action = $this->action = unserialize(\'' . serialize($this->action) . '\');
				$controller->data = $this->data = unserialize(stripslashes(\'' . addslashes(serialize($this->data)) . '\'));
				$controller->viewVars = $this->viewVars = unserialize(base64_decode(\'' . base64_encode(serialize($this->viewVars)) . '\'));
				$controller->theme = $this->theme = \'' . $this->theme . '\';
				Router::setRequestInfo(array($this->params, array(\'base\' => $this->base, \'webroot\' => $this->webroot)));';

		if ($useCallbacks == true) {
			$file .= '
				$controller->constructClasses();
				$controller->Component->initialize($controller);
				$controller->beforeFilter();
				$controller->Component->startup($controller);';
		}

		$file .= '
				$loadedHelpers = array();
				$loadedHelpers = $this->_loadHelpers($loadedHelpers, $this->helpers);
				foreach (array_keys($loadedHelpers) as $helper) {
					$camelBackedHelper = Inflector::variable($helper);
					${$camelBackedHelper} =& $loadedHelpers[$helper];
					$this->loaded[$camelBackedHelper] =& ${$camelBackedHelper};
					$this->{$helper} =& $loadedHelpers[$helper];
				}
				extract($this->viewVars, EXTR_SKIP);
		?>';
		$content = preg_replace("/(<\\?xml)/", "<?php echo '$1';?>",$content);
		$file .= $content;
		return cache('views' . DS . $cache, $file, $timestamp);
	}


}


