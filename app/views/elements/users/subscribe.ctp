<?php
$this->log($data);
$me = (boolean)($data['User']['id'] == $session->read('Auth.User.id'));
?>
<div class="modal-content">
    <?php //debug($data); ?>
    <div style="float:left; width:70px;">
        <?php echo $image->render($data['User'], 50, 50, array("alt" => $data['User']['username']), array('tag' => 'div')); ?>
        <div class="arrow">
            <span class="icon">
        </div>
        <?php if(isset($data['paper'])): ?>
            <?php //only one paper -> show image ?>
            <div style="float:left; width:70px;">
                <?php echo $image->render($data['paper']['Paper'], 50, 50, array("alt" => $data['paper']['Paper']), array('tag' => 'div'), 'paper'); ?>
            </div>
        <?php endif; ?>
        <?php if(isset($data['paper_category_chooser']['categories'])): ?>
            <?php //different paper -> show images ?>
            <?php $i = 0; ?>
            <?php foreach($data['paper_category_chooser']['categories'] as $category): ?>
                <div class="choose-paper-image" id="choose-paper-image-<?php echo $category['paper']['Paper']['id']; ?>"  <?php if($i > 0) echo 'style="display:none;"'; ?>>
                    <?php echo $image->render($category['paper']['Paper'], 50, 50, array("alt" => $category['paper']['Paper']), array('tag' => 'div'), 'paper'); ?>
                    <?php $i++; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>
    <div style="float:left; width: 400px">
        <?php echo $this->Form->create('User');?>

        <?php if(isset($data['user_topic_chooser'])): ?>
            <?php if($me): ?>
                <?php __('You have one or more topics.'); ?>
            <?php else: ?>
                <?php __('The user has one or more topics.'); ?>
            <?php endif; ?>
            <div class="choose-text">
                <span class="icon"></span><?php __('Choose the topic that you want to subscribe:'); ?>
            </div>

            <?php //echo $this->Form->input('user_topic_content_data', array('options' =>$data['user_topic_chooser'], 'label' => '')); ?>

            <div class="input select">
                <select name="data[User][user_topic_content_data]" id="UserUserTopicContentData">
                    <?php $i = 0; ?>
                    <?php foreach($data['user_topic_chooser']['options'] as $key => $value): ?>
                        <?php if($i == 1) echo '<optgroup label="'.__('Topics', true).'">'; ?>
                        <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                        <?php $i++; ?>
                    <?php endforeach; ?>
                    <?php echo '</optgroup>'; ?>
                </select></div>
        <?php else: ?>
            <div class="choose-text">
                <span class="icon"></span><?php __('You are about to subscribe to all Posts of the user.'); ?>
            </div>
            <br /><br />
        <?php endif; //drop down for paper / category?>

        <?php if(isset($data['User']['id'])): ?>
            <?php echo $this->Form->hidden('user_id',array('value' => $data['User']['id'])); ?>
        <?php endif; //isset user_id?>

        <br /><br />

        <?php if(isset($data['paper_id'])): ?>
            <?php echo $this->Form->hidden('paper_id',array('value' => $data['paper_id'])); ?>
        <?php endif; //isset paper_id?>

        <?php if(isset($data['paper_category_chooser'])): ?>
            <?php __('You have one or more papers.'); ?>
            <div class="choose-text">
                <span class="icon"></span><?php __('Choose the paper that you want to subscribe the user to:'); ?>
            </div>

            <?php echo $this->Form->input('paper_content_data' , array('type'=>'select','options'=> $data['paper_category_chooser']['options'], 'label' => false)); ?>


            <?php if(isset($data['paper_category_chooser']['categories'])): ?>
                <?php $i = 0; ?>
                <div class="choose-text">
                    <span class="icon"></span><?php __('Choose the category that you want to subscribe the user to:'); ?>
                     <?php __('(All articles of a category are also shown on the front page)'); ?>
                                    </div>
                  <?php foreach($data['paper_category_chooser']['categories'] as $category): ?>

                    <div id="choose-category-<?php echo $category['paper_id']; ?>" class="category-choose-content" <?php if($i > 0) echo 'style="display:none;"'; ?>>
                        <?php echo $this->Form->input('category_content_data_'.$category['paper_id'] , array('type'=>'select','options'=> $category['options'], 'label' => false)); ?>
                    </div>
                    <?php $i++; ?>
                <?php endforeach; ?>
            <?php endif; //end categories?>
        <?php endif; //content for paper?>



        <?php if(isset($data['paper_name'])): ?>

            <?php if(isset($data['paper_name']) && $data['created'] == true): ?>
                <?php __('We created a new paper for you.'); ?>
            <?php else: ?>
                <?php __('You have only one paper without categories.'); ?>
            <?php endif; ?>
            <div class="choose-text">
                <?php if(isset($data['paper_name']) && $data['created'] == true): ?>
                    <span class="icon"></span><?php echo sprintf(__('The subscription will be saved to your just created paper %s. You can always rename this paper in the settings.', true), $data['paper_name']); ?>
                <?php else: ?>
                    <span class="icon"></span><?php echo sprintf(__('The subscription will be saved to your paper %s.', true), $data['paper_name']); ?>
                <?php endif; ?>

            </div>

        <?php endif; ?>

        </form>
    </div>
</div>
<div class="modal-buttons">
    <ul>
        <li><a class="btn" id="btn-submit-subscription" onclick="$('#UserSubscribeForm').submit();"><span>+</span><?php __('Save Subscription'); ?></a></li>
        <li><a href="#" class="btn" onclick="$('#dialog-subscribe').dialog('close');"><span>-</span><?php __('Cancel'); ?></a></li>
    </ul>
</div>

