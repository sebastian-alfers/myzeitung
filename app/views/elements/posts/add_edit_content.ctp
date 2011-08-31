<?php // we use the src of tinymce since we changed it in line 9332 ?>
<?php $this->MzJavascript->link('tiny_mce/tiny_mce_src.js'); ?>
<?php $this->MzJavascript->link('post/add_edit.js'); ?>

<?php
/*
$this->MzJavascript->link('tiny_mce/plugins/autolink/editor_plugin.js');
$this->MzJavascript->link('tiny_mce/plugins/lists/editor_plugin.js');
$this->MzJavascript->link('tiny_mce/plugins/spellchecker/editor_plugin.js');
$this->MzJavascript->link('tiny_mce/plugins/pagebreak/editor_plugin.js');
$this->MzJavascript->link('tiny_mce/plugins/style/editor_plugin.js');
$this->MzJavascript->link('tiny_mce/plugins/layer/editor_plugin.js');
$this->MzJavascript->link('tiny_mce/plugins/table/editor_plugin.js');
$this->MzJavascript->link('tiny_mce/plugins/save/editor_plugin.js');
$this->MzJavascript->link('tiny_mce/plugins/advhr/editor_plugin.js');
$this->MzJavascript->link('tiny_mce/plugins/advimage/editor_plugin.js');
$this->MzJavascript->link('tiny_mce/plugins/emotions/editor_plugin.js');
$this->MzJavascript->link('tiny_mce/plugins/advlink/editor_plugin.js');
$this->MzJavascript->link('tiny_mce/plugins/iespell/editor_plugin.js');
$this->MzJavascript->link('tiny_mce/plugins/inlinepopups/editor_plugin.js');
$this->MzJavascript->link('tiny_mce/plugins/insertdatetime/editor_plugin.js');
$this->MzJavascript->link('tiny_mce/plugins/media/editor_plugin.js');
$this->MzJavascript->link('tiny_mce/plugins/preview/editor_plugin.js');
$this->MzJavascript->link('tiny_mce/plugins/print/editor_plugin.js');
$this->MzJavascript->link('tiny_mce/plugins/searchreplace/editor_plugin.js');
$this->MzJavascript->link('tiny_mce/plugins/paste/editor_plugin.js');
$this->MzJavascript->link('tiny_mce/plugins/directionality/editor_plugin.js');
$this->MzJavascript->link('tiny_mce/plugins/fullscreen/editor_plugin.js');
$this->MzJavascript->link('tiny_mce/plugins/noneditable/editor_plugin.js');
$this->MzJavascript->link('tiny_mce/plugins/visualchars/editor_plugin.js');
$this->MzJavascript->link('tiny_mce/plugins/nonbreaking/editor_plugin.js');
$this->MzJavascript->link('tiny_mce/plugins/xhtmlxtras/editor_plugin.js');
$this->MzJavascript->link('tiny_mce/plugins/template/editor_plugin.js');
$this->MzJavascript->link('tiny_mce/themes/advanced/editor_template.js');
$this->MzJavascript->link('tiny_mce/langs/en.js');
*/
?>


<?php echo $this->element('topics/modal_add'); ?>
<?php echo $this->element('posts/modal_add_url'); ?>
<?php echo $this->element('posts/modal_add_video_url'); ?>

<div class="article-nav">
    <?php if($this->params['action'] == 'add'):?>
      <h1><?php echo __('New Post', true); ?></h1>
    <?php else:?>
       <h1><?php echo __('Edit your Post', true); ?></h1>
    <?php endif;?>


    <ul class="create-actions">
        <?php
        $form = 'PostAddForm';
        if($this->params['action'] == 'edit') $form = 'PostEditForm';
        ?>


        <?php if($this->params['action'] == 'edit'):?>
         <li><?php echo $this->Html->link(__('Delete', true), array('controller' => 'posts',  'action' => 'delete', $this->data['Post']['id']), null, sprintf(__('Are you sure you want to delete your post: %s?', true), $this->data['Post']['title'])); ?></li>
        <?php endif;?>
       <li class="big-btn" id="submit-post" onclick="preSubmitActions($('#<?php echo $form; ?>'));"><a class="btn"><span class="icon icon-tick"></span><?php __('Save Post'); ?></a></li>



       <?php // <li class="big-btn"><a href="create-article.html" class="btn"><span class="icon icon-circle"></span>Vorschau</a></li> */ ?>
    </ul>
</div>


<?php echo $this->Form->create('Post', array("enctype" => "multipart/form-data"));?>
<?php echo $this->Form->input('id'); ?>
<?php //echo $this->Form->input('topic_id'); ?>
<?php echo $this->Form->hidden('topic_id',array('value' => $mzform->value($this, 'Post', 'topic_id'))); ?>
<?php echo $this->Form->hidden('user_id',array('value' => $user_id)); ?>
<?php echo $this->Form->hidden('hash',array('value' => $hash)); ?>
<?php echo $this->Form->hidden('media',array('value' => '')); ?>
<?php echo $this->Form->hidden('links',array('value' => '')); ?>
<?php echo $this->Form->hidden('allow_comments',array('value' => $mzform->value($this, 'Post', 'allow_comments'))); ?>

    <p>
        <label><?php __('Title'); ?></label>
        <input name="data[Post][title]" type="text" maxlength="100" id="PostTitle" class="textinput" value="<?php echo $mzform->value($this, 'Post', 'title'); ?>"/>
    </p>

    <div class="article-content">
        <label><?php __('Content'); ?></label>

    <textarea id="PostContent" name="data[Post][content]" rows="5" cols="10" style="height:200px;" class="tinymce">
    <?php echo $mzform->value($this, 'Post', 'content'); ?>
        </textarea>
    </div>

</form>

<div id="files"></div>
    <label><?php __('Media (images and videos): '); ?></label>
<span id="main-teaser"><?php __('Preview Picture'); ?></span>
<ul id="sortable" class="add-article-images">
<?php if(isset($images)): ?>
<?php foreach($images as $img): ?>
	<li id="<?php echo $img['name']; ?>"
		class="ui-state-default teaser-sort video">
        <?php if($img['item_type'] == 'video'): ?>
            <span class="video-item">video</span>
        <?php endif; ?>
		<a class="remove_li_item" name="img/<?php echo $img['path']; ?>" id="<?php echo $this->data['Post']['id']; ?>" style="cursor: pointer; vertical-align: top;"><?php __('remove'); ?></a>
        <?php echo $this->Html->image($img['path'], array('style' => 'width:100px')); ?>

        <div class="item_data" style="display: none;">
            <input type="hidden" name="item_type" value="<?php echo $img['item_type']; ?>" />
            <input type="hidden" name="img_name" value="<?php echo $img['name']; ?>" />

            <?php if($img['item_type'] == 'video'): ?>
                <?php foreach($img['video'] as $key => $value): ?>
                    <input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>" />
                <?php endforeach; ?>
                </div>
            <?php endif; ?>
		</li>
		<?php endforeach; ?>
		<?php endif;?>
</ul>

</div>


<script>
		$(document).ready(function() {
			$( "#sortable" ).sortable();
			$( "#sortable" ).disableSelection();
		});
		</script>


<div>

</div>
<!-- / sortable -->
