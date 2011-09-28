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
        <?php if($this->params['action'] == 'add'):?>
			<h2 class="account-title"><?php echo __('New Paper', true);?></h2>
        <?php else: ?>
            <h2 class="account-title"><?php echo __('Edit Paper', true);?></h2>
        <?php endif;?>
  <div class="article-nav">



    <ul class="create-actions">
        <?php if($this->params['action'] == 'edit'):?>
        <li><?php echo $this->Html->link(__('Delete Paper', true), array('controller' => 'papers', 'action' => 'delete', $this->data['Paper']['id']) ,array('escape' => false, 'rel' => 'nofollow'), sprintf(__('Are you sure you want to delete your paper: %s?', true), $this->data['Paper']['title'])); ?></li>
        <?php endif;?>
        <?php // <li class="big-btn"><a href="create-article.html" class="btn"><span class="icon icon-circle"></span>Vorschau</a></li> */ ?>
        <li> <a class="btn" id="link_save_changes"><span>+</span><?php echo __('Save paper', true);?></a></li>
    </ul>
</div>
			    <p>
				<?php echo $this->Form->create('Paper' , array( 'inputDefaults' => array('error' => false, 'div' => false)));?>
				<?php echo $this->Form->hidden('id');?>
				<?php echo $this->Form->hidden('owner_id'); ?>
                <?php echo $this->Form->hidden('route_source'); ?>
                    </p>
				
				<div><?php  echo $this->Form->input('title', array('maxlength' => 55 ,'type' => 'text', 'class' => 'textinput', 'label' => __('Title', true))); ?>
				<?php if(!is_null($this->Form->error('Paper.title'))): ?>
				 	<div class="error-message"><b></b><?php echo $this->Form->error('Paper.title', array('wrap'=> false));?></div>
				<?php endif; ?>
				</div>
			
				<div class="optional info-p textarea"><?php  echo $this->Form->input('description', array('maxlength' => 1000, 'type' => 'textarea', 'class' => 'textinput', 'label' => __('Description', true))); ?>
					<span class="info"><?php echo __('(optional)', true);?>&nbsp;<?php echo __('If you want to describe the contents of this paper.', true);?></span>
					<?php if(!is_null($this->Form->error('Paper.description'))): ?>
						<div class="error-message"><b></b><?php echo $this->Form->error('Paper.description', array('wrap'=> false));?></div>
					<?php endif; ?>
				</div>

		         <?php /* setting default value 'http://' if there is nothing typed in yet */
                    if(empty($this->data['Paper']['url'])){
                        $url_value = "http://";
                    } else {
                        $url_value = $this->data['Paper']['url'];
                    } ?>

				<div class="optional info-p"><?php  echo $this->Form->input('url', array('value' => $url_value , 'type' => 'text', 'class' => 'textinput', 'label' => __('URL / Link', true))); ?>
					<span class="info"><?php echo __('(optional)', true);?>&nbsp;<?php echo __('If you want to specify a link of which this paper is about.', true);?></span>
					<?php if(!is_null($this->Form->error('Paper.url'))): ?>
						<div class="error-message"><b></b><?php echo $this->Form->error('Paper.url', array('wrap'=> false));?></div>
					<?php endif; ?>
				</div>
			
			
				<div class="accept">	
				<?php /*	<a class="btn big" id="link_save_changes"><span>+</span><?php echo __('Save paper', true);?></a> */ ?>
				</div>

				<?php echo $this->Form->end(array('div' => false,'class' => 'hidden')); ?>
     
<?php /*
                <?php if($edit): ?>

                <hr />
				<div class="accept">
                    <?php echo $this->Html->link("<span>-</span>".__('Delete Paper', true), array('controller' => 'papers', 'action' => 'delete', $this->data['Paper']['id']) ,array('escape' => false, 'class' => 'btn big', 'rel' => 'nofollow'), sprintf(__('Are you sure you want to delete your paper: %s?', true), $this->data['Paper']['title'])); ?>
				</div>
                <?php endif; ?>

		        */ ?>
	</div><!-- / #maincol -->

</div><!-- / #maincolwrapper -->