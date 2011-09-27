<?php
class Setting extends AppModel {
	var $name = 'Setting';
	var $displayField = 'value';

    const MODEL_TYPE_USER = 'user';
    const NAMESPACE_DEFAULT = 'default';
    const KEY_LOCALE = 'locale';


    const TW_OAUTH_TOKEN = 'tw_oauth_token';
    const TW_OAUTH_TOKEN_SECRET = 'tw_oauth_token_secret';



	var $validate = array(
		'model_type' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'model_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'key' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
    );


    /**
     * loading settings for the optional params.
     *
     * if no model_id is specified only default values will be loaded.
     * if a model_id is specified, all default values for that combination of params will be loaded,
     * and joined (left) together with the values for the model_id. if a specific value for that
     * model_id is found, the default value gets overridden. otherwise the default value will be
     * returned.
     *
     * structure of the returned array:
     * ['model_type']['namespace']['key'] => array(
     *                                      'value'              (default or specific if found)
     *                                      'value_data_type'   (from default)
     *                                      'default_id'        (id from the default setting)
     *                                      'specific_id'       (id of specific setting value IF FOUND)
     *
     *
     *
     * @param null $model_type
     * @param null $model_id
     * @param null $namespace
     * @param null $key
     * @return array
     */
    function get($model_type = null, $model_id = null, $namespace = null, $key = null){

        //define join and conditions
        $options = array();
        $options['conditions']['Setting.model_id'] = null;
        if($model_type != null){
            $options['conditions']['Setting.model_type'] = $model_type;
        }
        if($namespace != null){
            $options['conditions']['Setting.namespace'] = $namespace;
        }
        if($key != null){
            $options['conditions']['Setting.key'] = $key;
        }
        $options['fields'] = array('Setting.id', 'Setting.model_type',  'Setting.namespace','Setting.key', 'Setting.value', 'Setting.value_data_type');
        //join just needed if there is a model_id, otherwise ONLY default values will be loaded.
        if($model_id !=null){
            $options['fields'][] = 'SpecificValue.value';
            $options['fields'][] = 'SpecificValue.id';

            $options['joins'] = array(
                array('table' => 'settings',
                      'alias' => 'SpecificValue',
                      'type' => 'left',
                      'conditions' => array(
                          'Setting.model_type = SpecificValue.model_type',
                          'Setting.namespace = SpecificValue.namespace',
                          'Setting.key = SpecificValue.key',
                          'SpecificValue.model_id' => $model_id
                      )
                ),
            );

        }


        $values = $this->find('all', $options);
        //take specific values and put them to the value field of the default value.
        $results = array();

            foreach($values as $value){
                //overriding default values if specifics are found
                if($model_id != null){
                    if($value['SpecificValue']['value'] != null){
                        $value['Setting']['value'] = $value['SpecificValue']['value'];
                        $value['Setting']['specific_id'] = $value['SpecificValue']['id'];
                    }else{
                        $value['Setting']['specific_id'] = null;
                    }
                    $value['Setting']['default_id'] = $value['Setting']['id'];

                }
                //building rearranged result-array (like in function description)
                $results[$value['Setting']['model_type']][$value['Setting']['namespace']][$value['Setting']['key']]['value'] = $value['Setting']['value'];
                $results[$value['Setting']['model_type']][$value['Setting']['namespace']][$value['Setting']['key']]['value_data_type'] = $value['Setting']['value_data_type'];
                $results[$value['Setting']['model_type']][$value['Setting']['namespace']][$value['Setting']['key']]['default_id'] = $value['Setting']['default_id'];
                $results[$value['Setting']['model_type']][$value['Setting']['namespace']][$value['Setting']['key']]['specific_id'] = $value['Setting']['specific_id'];
            }
        
        return $results;
    }
}
