<script type="text/javascript">
<!--
$(document).ready(function() {	
	$("#link_save_changes").click(function(){
		$('#PaperAddForm').submit();
		$('#PaperEditForm').submit();
	});	
});
//-->
</script>
<?php echo $this->element('users/sidebar'); ?>

<div id="maincolwrapper"> 
	<div id="maincol" class="account">
			<h4 class="account-title"><?php echo __('New Paper', true);?></h4>
			
				<?php echo $this->Form->create('Paper' , array( 'inputDefaults' => array('error' => false, 'div' => false)));?>
				<?php echo $this->Form->hidden('id');?>
				<?php echo $this->Form->hidden('owner_id'); ?>
				
				<div><?php  echo $this->Form->input('title', array('type' => 'text', 'class' => 'textinput', 'label' => __('Title', true))); ?>
				<?php if(!is_null($this->Form->error('Paper.title'))): ?>
				 	<div class="error-message"><b></b><?php echo $this->Form->error('Paper.title', array('wrap'=> false));?></div>
				<?php endif; ?>
				</div>
			
				<div class="optional info-p"><?php  echo $this->Form->input('description', array('type' => 'text', 'class' => 'textinput', 'label' => __('Description', true))); ?>
					<span class="info"><?php echo __('(optional)', true);?>&nbsp;<?php echo __('If you want to describe the contents of this paper.', true);?></span>
					<?php if(!is_null($this->Form->error('Paper.description'))): ?>
						<div class="error-message"><b></b><?php echo $this->Form->error('Paper.description', array('wrap'=> false));?></div>
					<?php endif; ?>
				</div>
			
				<div class="optional info-p"><?php  echo $this->Form->input('url', array('type' => 'text', 'class' => 'textinput', 'label' => __('URL / Link', true))); ?>
					<span class="info"><?php echo __('(optional)', true);?>&nbsp;<?php echo __('If you want to specify a link of which this paper is about.', true);?></span>
					<?php if(!is_null($this->Form->error('Paper.url'))): ?>
						<div class="error-message"><b></b><?php echo $this->Form->error('Paper.url', array('wrap'=> false));?></div>
					<?php endif; ?>
				</div>
			
			
				<div class="accept">	
					<a class="btn big" id="link_save_changes"><span>+</span><?php echo __('Save paper', true);?></a>
				</div>
								
				<?php echo $this->Form->end(array('div' => false,'class' => 'hidden')); ?>	
		
	</div><!-- / #maincol -->

</div><!-- / #maincolwrapper -->