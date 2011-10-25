<?php
class Reason extends AppModel {

    var $displayField = 'value';

	var $name = 'Reason';
	var $validate = array(
		'value' => array(
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
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $hasMany = array(
		'Complaint' => array(
			'className' => 'Complaint',
			'foreignKey' => 'reason_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

    function __construct(){
		parent::__construct();
        $this->text = array(
            1 => __('It is a picture of my person', true),
            2 => __('Other reason', true),
            3 => __('The picture of the user profile is inappropriate', true),
            4 => __('I am molested by this user', true),
            5 => __('Posts/Comments by this user do not match with the terms of service (extremism/glorification of violence/pornography)', true),
            6 => __('The user sends spam', true),
            7 => __('It is a hacked user profile', true),
            8 => __('Contents in this article do not match with the terms of service (extremism/glorification of violence/pornography)', true),
            9 => __('Comments on this article do not match with the terms of service (extremism/glorification of violence/pornography)', true),
            10 => __('Contents of this paper do not match with the terms of service (extremism/glorification of violence/pornography)', true),
            11 => __('The description of the paper is inappropriate', true),
            12 => __( 'The image of the paper is inappropriate', true),


        );
    }


}
