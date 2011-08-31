<?php
App::import('Helper', 'Number');

class MzNumberHelper extends NumberHelper {


    /**
 	* Returns a formatted-for-humans counter number.
 	*
 	* @param integer $count
	* @return string Human readable counter
	* @access public

 	*/
 	function counterToReadableSize($count) {
     	switch (true) {
     	case $count < 1000:
    	return $count;
    	case round($count / 1000) < 1000:
    	return sprintf(__('%.1ft', true), $this->precision($count / 1000, 1));
    	case round($count / 1000 / 1000, 2) < 1000:
    	return sprintf(__('%.1fm', true), $this->precision($count / 1000 / 1000, 1));
    	case round($count / 1000 / 1000 / 1000, 2) < 1000:
    	return sprintf(__('%.1fM', true), $this->precision($count / 1000 / 1000 / 1000, 1));
    	default:
    	return $count;
     	}
	}
}
?>