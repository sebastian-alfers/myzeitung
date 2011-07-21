<?php
class Complaint extends AppModel {

    const TYPE_ALL = 0;
    const TYPE_USER = 1;
    const TYPE_POST = 2;
    const TYPE_PAPER = 3;

	var $name = 'Complaint';
	/*var $validate = array(
		'comments' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'reporter_email' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'email' => array(
				'rule' => array('email'),
				'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);*/
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'Paper' => array(
			'className' => 'Paper',
			'foreignKey' => 'paper_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Post' => array(
			'className' => 'Post',
			'foreignKey' => 'post_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Comment' => array(
			'className' => 'Comment',
			'foreignKey' => 'comment_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Reason' => array(
			'className' => 'Reason',
			'foreignKey' => 'reason_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Reporter' => array(
			'className' => 'User',
			'foreignKey' => 'reporter_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Complaintstatus' => array(
			'className' => 'Complaintstatus',
			'foreignKey' => 'complaintstatus_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

    function __construct(){
		parent::__construct();

	$this->validate = array(
		'comments' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
        'reporter_email' => array(
				'empty' => array(
					'rule'			=> 'notEmpty',
					'message' 		=> __('Please enter your email address.', true),
					'last' 			=> true,
				),
				'email' => array(
					'rule'			=> array('email'),
					'message'		=> __('Please enter a valid email address.', true),
					'last' 			=> true,
				),

			),

	);

    }

}
