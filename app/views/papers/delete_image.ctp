<script type="text/javascript">
<!--
$(document).ready(function() {
	$("#link_send_delete_paper_picture").click(function(){
			$('#delete-paper-picture-form').submit();
	});
});
//-->
</script>
<?php
if(!($session->read('Auth.User.id')) || $paper['Paper']['owner_id'] != $session->read('Auth.User.id')){
    $paper_belongs_to_user = false;
}elseif($paper['Paper']['owner_id'] == $session->read('Auth.User.id')){
    $paper_belongs_to_user = true;
}


?>

<?php $this->MzJavascript->link('paper/view'); ?>

<?php echo $this->element('papers/sidebar', array('paper_belongs_to_user' => $paper_belongs_to_user)); ?>


<div id="maincolwrapper">
    <div id="maincol">
        <div>
            <div>
                <h2><?php __('Are you sure?'); ?></h2>
                <?php __('A paper picture will help other authors and reads to find your paper more easily'); ?>
   
             </div>
            <?php echo $this->Form->create('Paper', array('id' => 'delete-paper-picture-form', 'controller' => 'paper', 'action' => 'deleteImage',
                                                        'inputDefaults' => array('error' => false, 'div' => false)));?>

                <?php echo $this->Form->input('paper_id', array('type' => 'hidden', 'value' => $paper_id)); ?>

                <div class="accept">
                    <a class="btn big" id="link_send_delete_paper_picture"><span>+</span><?php echo __('Yes, Delete Paper Picture', true);?></a><a class="btn big" href="/settings"><span>-</span><?php echo __('No, keep current picture', true);?></a>
                </div>

                <?php echo $this->Form->end(array('div' => false,'class' => 'hidden')); ?>
        </div> <!-- /register -->
    </div><!-- / #maincol -->
</div><!-- / #maincolwra