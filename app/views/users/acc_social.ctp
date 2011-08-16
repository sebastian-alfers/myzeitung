<script type="text/javascript">
<!--
$(document).ready(function() {
	$('#cb_twitter').click(function(){
        var options = {};
        $('#content_twitter').toggle( 'blind', options, 500 );
        timeout = setTimeout(function() {
                document.location = 'http://localhost:8180/twitter/clear';
        }, 700);

    });
});
//-->
</script>
<?php echo $this->element('users/sidebar'); ?>

<div id="maincolwrapper"> 
	<div id="maincol" class="account">
		<h4 class="account-title"><?php echo __('Privacy Settings', true);?></h4>

				<?php echo $this->Form->create('User', array('class' => 'jqtransform',  'inputDefaults' => array('error' => false, 'div' => false)));?>
				<?php echo $this->Form->hidden('id' , array('value' => $user['User']['id']));?>
				<?php echo $this->Form->hidden('username' , array('value' => $user['User']['username']));?>
				<?php echo $this->Form->hidden('image' , array('value' => $user['User']['image']));?>
				<div class="accept">	
					<p><?php  echo $this->Form->input('use_twitter', array('id' => 'cb_twitter', 'type' => 'checkbox', 'class' => 'textinput','label' => false)); ?><strong><?php echo __('Twitter', true);?></strong></p>
                    <?php if($this->data['User']['use_twitter']): ?>
                        <a href="/twitter/remove"><?php __('Remove Twitter'); ?></a>
                    <?php endif; ?>

                    <div style="display: none;padding-left:20px;" id="content_twitter">
                        <img src="/img/assets/loader.gif" />
                    </div>

					<p><?php  echo $this->Form->input('use_faceboook', array('type' => 'checkbox', 'class' => 'textinput','label' => false)); ?><strong><?php echo __('Facebook', true);?></strong></p>
				</div>
								
				<?php echo $this->Form->end(array('div' => false,'class' => 'hidden')); ?>	
							
	</div><!-- / #maincol -->

</div><!-- / #maincolwrapper -->