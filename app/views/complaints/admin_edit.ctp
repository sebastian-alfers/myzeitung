<div class="complaints form">
<?php echo $this->Form->create('Complaint');?>
	<fieldset>
		<legend><?php __('Admin Edit Complaint'); ?></legend>
	<?php

        if(!empty($this->data['Paper']['id'])){
            echo $this->element('complaints/models/paper');
        }

        if(!empty($this->data['Post']['id'])){
            echo $this->element('complaints/models/post');
        }

        if(!empty($this->data['User']['id'])){
            echo $this->element('complaints/models/user');
        }

        if(!empty($this->data['Comment']['id'])){
            echo $this->element('complaints/models/comment');
        }

		echo $this->Form->input('id');

		echo $this->Form->input('reason_id');
        echo $this->Form->input('complaintstatus_id');

		echo $this->Form->input('comments', array('name' => "data[Complaint][new_comment]", 'id' => 'new_comment', 'value' => '', 'label' => false));




	?>
    <?php echo $this->Form->end(__('Submit', true));?>
	</fieldset>
</div>

<h2><?php __('Comment History'); ?></h2>
<div>
    <?php
    foreach(array_reverse(unserialize($this->data['Complaint']['comments'])) as $comment){
            if(isset($this->data['Complaint']['comment_author'])){
                echo __('Written by', true) . $this->data['Complaint']['comment_author'];
            }
            echo $this->Time->timeAgoInWords($comment['date'], array('end' => '+1 Week'));
            echo "<br />";
            echo $comment['comment'];
            echo "<hr>";
    }


            echo date('Y-m-d H:i:s');
    ?>
</div>

