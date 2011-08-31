<div class="modal-content">
    <?php echo $this->Form->create('User');?>

    <?php if(isset($data['user_topic_chooser'])): ?>
        <?php echo $this->Form->input('user_topic_content_data', $data['user_topic_chooser']); ?>
    <?php endif; //drop down for paper / category?>

    <?php if(isset($data['user_id'])): ?>
        <?php echo $this->Form->hidden('user_id',array('value' => $data['user_id'])); ?>
    <?php endif; //isset user_id?>


    <?php if(isset($data['paper_id'])): ?>
        <?php echo $this->Form->hidden('paper_id',array('value' => $data['paper_id'])); ?>
    <?php endif; //isset paper_id?>

    <?php if(isset($data['paper_category_chooser'])): ?>
        <h2><?php __('Choose your paper:'); ?></h2>
        <?php echo $this->Form->input('paper_category_content_data' , array('type'=>'select','options'=> $data['paper_category_chooser'], 'label' => false)); ?>
    <?php endif; //drop down for paper / category?>

    <?php if(isset($data['paper_name'])): ?>
        <?php __('Into: '); ?><?php echo $data['paper_name']; ?>
    <?php endif; ?>

    </form>
</div>
<div class="modal-buttons">
    <ul>
        <li><a class="btn" id="btn-submit-subscription" onclick="$('#UserSubscribeForm').submit();"><span>+</span><?php __('Save Subscription'); ?></a></li>
        <li><a href="#" class="btn" onclick="$('#dialog-subscribe').dialog('close');"><span>-</span><?php __('Cancel'); ?></a></li>
    </ul>
</div>

