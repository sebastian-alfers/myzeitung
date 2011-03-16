<?php
echo $this->Form->create('User', array('action' => 'login'));
echo $this->Form->input('username');
echo $this->Form->input('password');
echo $this->Form->input('auto_login', array('type' => 'checkbox', 'label' => __('Log me in automatically?', true))); 
echo $this->Form->end('Login');
?>