<div class="papers addcontent">

<?php echo $this->Form->create('User');?>

<?php if(isset($user_topic_chooser)): ?>
	<?php echo $this->Form->input('user_topic_content_data', $user_topic_chooser); ?>
<?php endif; //drop down for paper / category?>

<?php if(isset($user_id)): ?>
	<?php echo $this->Form->hidden('user_id',array('value' => $user_id)); ?>
    <?php echo $this->Form->hidden('username',array('value' => $username)); ?>
<?php endif; //isset user_id?>


<?php if(isset($paper_id)): ?>
	<?php echo $this->Form->hidden('paper_id',array('value' => $paper_id)); ?>
<?php endif; //isset paper_id?>

<?php if(isset($paper_category_chooser)): ?>
    <h2><?php __('Choose your paper:'); ?></h2>
	<?php echo $this->Form->input('paper_category_content_data' , array('type'=>'select','options'=> $paper_category_chooser, 'label' => false)); ?>
<?php endif; //drop down for paper / category?>

<?php if(isset($paper_name)): ?>
	<?php __('Into: '); ?><?php echo $paper_name; ?>
<?php endif; ?>

</form>

</div>
    <div>
        <a class="btn big" id="btn-submit-subscription" onclick="$('#UserSubscribeForm').submit();"><span class="icon icon-send"></span><?php __('Save'); ?></a>
    </div>

