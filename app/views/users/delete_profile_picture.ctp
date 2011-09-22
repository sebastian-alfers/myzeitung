<script type="text/javascript">
<!--
$(document).ready(function() {
	$("#link_send_delete_picture").click(function(){
			$('#delete-profile-picture-form').submit();
	});
});
//-->
</script>
<?php echo $this->element('users/sidebar'); ?>

<div id="maincolwrapper">
    <div id="maincol">
        <div>
            <div>
                <h2><?php __('Are you sure?'); ?></h2>
                <?php __('A profile picture will help other authors, readers or paper administrators to find your profile more easily.'); ?>

             </div>
            <?php echo $this->Form->create('User', array('id' => 'delete-profile-picture-form', 'controller' => 'users', 'action' => 'deleteProfilePicture',
                                                        'inputDefaults' => array('error' => false, 'div' => false)));?>

                <input type="hidden" value="delete" name="data" />

                <div class="accept">
                    <a class="btn big" id="link_send_delete_picture"><span>+</span><?php echo __('Yes, Delete Profile Picture', true);?></a><a class="btn big" href="/settings"><span>-</span><?php echo __('No, keep current profile picture', true);?></a>
                </div>

                <?php echo $this->Form->end(array('div' => false,'class' => 'hidden')); ?>
        </div> <!-- /register -->
    </div><!-- / #maincol -->
</div><!-- / #maincolwrapper -->