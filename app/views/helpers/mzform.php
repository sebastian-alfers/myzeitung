<?php
class MzformHelper extends Helper {


    /**
     * return value or empty if not found
     *
     * @param  $object - instance of view to get $this->data
     * @param  $model
     * @param  $field
     *
     * @return string
     */
    function value($object, $model, $field){
        return (isset($object->data[$model][$field])) ? $object->data[$model][$field] : '';
    }
}
?>