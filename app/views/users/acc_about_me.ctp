<script type="text/javascript">
<!--
$(document).ready(function() {	
	$("#link_save_changes").click(function(){
		$('#UserAccAboutMeForm').submit();
	});	
});
//-->
</script>
<?php echo $this->element('users/sidebar'); ?>

<div id="maincolwrapper"> 
	<div id="maincol" class="account">
		<h4 class="account-title"><?php echo __('About Me', true);?></h4>

				<?php echo $this->Form->create('User' , array( 'inputDefaults' => array('error' => false, 'div' => false)));?>
				<?php echo $this->Form->hidden('id' , array('value' => $user['User']['id']));?>
				<?php echo $this->Form->hidden('username' , array('value' => $user['User']['username']));?>
				<?php echo $this->Form->hidden('image' , array('value' => $user['User']['image']));?>
				
				
				<div class="optional info-p"><?php  echo $this->Form->input('name', array('maxlength' => 40,'type' => 'text', 'class' => 'textinput', 'label' => __('Name', true))); ?>
					<span class="info"><?php echo __('(optional)', true);?>&nbsp;<?php echo __('If you want to be found by your name', true);?></span>
					<?php if(!is_null($this->Form->error('User.name'))): ?>
						<div class="error-message"><b></b><?php echo $this->Form->error('User.name', array('wrap'=> false));?></div>
					<?php endif; ?>
				</div>
				
				<div class="optional info-p"><?php  echo $this->Form->input('description', array('type' => 'text', 'class' => 'textinput', 'label' => __('About Me', true))); ?>
					<span class="info"><?php echo __('(optional)', true);?>&nbsp;<?php echo __('Tell people something about yourself.', true);?></span>
					<?php if(!is_null($this->Form->error('User.description'))): ?>
						<div class="error-message"><b></b><?php echo $this->Form->error('User.description', array('wrap'=> false));?></div>
					<?php endif; ?>
				</div>

                 <?php /* setting default value 'http://' if there is nothing typed in yet */
                    if(empty($this->data['User']['url'])){
                        $url_value = "http://";
                    } else {
                        $url_value = $this->data['User']['url'];
                    } ?>

                <div class="optional info-p"><?php  echo $this->Form->input('url', array('value' => $url_value, 'type' => 'text', 'class' => 'textinput', 'label' => __('Link', true))); ?>
					<span class="info"><?php echo __('(optional)', true);?>&nbsp;<?php echo __('If you want to specify a link to your homepage.', true);?></span>
					<?php if(!is_null($this->Form->error('User.url'))): ?>
						<div class="error-message"><b></b><?php echo $this->Form->error('User.url', array('wrap'=> false));?></div>
					<?php endif; ?>
				</div>


				<div class="accept">	
					<a class="btn big" id="link_save_changes"><span>+</span><?php echo __('Save Changes', true);?></a>
				</div>
								
				<?php echo $this->Form->end(array('div' => false,'class' => 'hidden')); ?>	
							
	</div><!-- / #maincol -->

</div><!-- / #maincolwrapper -->