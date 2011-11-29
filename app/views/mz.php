<?php

/**
 * load custom CacheHelper for caching problems
 * http://cakebaker.42dh.com/2008/11/07/an-idea-for-hacking-core-helpers/
 */
class MzView extends View {

    public function &_loadHelpers(&$loaded, $helpers, $parent = null) {
        $loaded = parent::_loadHelpers($loaded, $helpers, $parent);

        $helper = 'Cache';

        if (isset($loaded[$helper])) {
            App::import('Helper', 'MzCache');
            $loaded[$helper] = new MzCacheHelper();

            // everything from here on is copied from View::_loadHelpers()

            $vars = array('base', 'webroot', 'here', 'params', 'action', 'data', 'theme', 'plugin');
            $c = count($vars);

            for ($j = 0; $j < $c; $j++) {
                $loaded[$helper]->{$vars[$j]} = $this->{$vars[$j]};
            }

            if (!empty($this->validationErrors)) {
                $loaded[$helper]->validationErrors = $this->validationErrors;
            }
            if (is_array($loaded[$helper]->helpers) && !empty($loaded[$helper]->helpers)) {
                $loaded =& $this->_loadHelpers($loaded, $loaded[$helper]->helpers, $helper);
            }
        }

        return $loaded;
    }
}