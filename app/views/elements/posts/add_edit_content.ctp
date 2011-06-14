<?php echo $html->script('post/add_edit.js'); ?>
<?php echo $this->element('topics/modal_add_topic'); ?>
<?php echo $this->element('posts/modal_add_url'); ?>

<div class="article-nav">
    <h1><?php __('New Post'); ?></h1>

    <ul class="create-actions">
        <?php
        $form = 'PostAddForm';
        if($this->params['action'] == 'edit') $form = 'PostEditForm';
        ?>
        <li class="big-btn" onclick="$('#PostImages').val($('#sortable').sortable('toArray'));$('#<?php echo $form; ?>').submit();"><a class="btn"><span class="icon icon-tick"></span><?php __('Save Post'); ?></a></li>
        <li class="big-btn"><a href="create-article.html" class="btn"><span class="icon icon-circle"></span>Vorschau</a></li>
    </ul>
</div>


<?php echo $this->Form->create('Post', array("enctype" => "multipart/form-data"));?>
<?php echo $this->Form->input('id'); ?>
<?php echo $this->Form->input('topic_id'); ?>
<?php echo $this->Form->hidden('user_id',array('value' => $user_id)); ?>
<?php echo $this->Form->hidden('hash',array('value' => $hash)); ?>
<?php echo $this->Form->hidden('images',array('value' => '')); ?>
<?php echo $this->Form->input('allow_comments',array('type'=>'select','options'=>$allow_comments)); ?>

    <p>
        <label><?php __('Title'); ?></label>
        <input name="data[Post][title]" type="text" maxlength="200" id="PostTitle" class="textinput" value="<?php echo $mzform->value($this, 'Post', 'title'); ?>"/>
    </p>

    <div class="article-content">
        <label><?php __('Content'); ?></label>

    <textarea id="elm1"  name="data[Post][content]" rows="15" cols="80" style="height:400px;" class="tinymce">
    <?php echo $mzform->value($this, 'Post', 'content'); ?>
        </textarea>
    </div>

</form>

<div id="files"></div>
    <label><?php __('Media (images and videos): '); ?></label>

<ul id="sortable" class="add-article-images">
<?php if(isset($images)): ?>
<?php foreach($images as $img): ?>
	<li id="<?php echo $img['name']; ?>"
		class="ui-state-default" style="cursor: move;height:200px;width:100px;"><?php echo $this->Html->image($img['path'], array('style' => 'width:100px')); ?>
		<a class="remove_li_item" name="img/<?php echo $img['path']; ?>" id="<?php echo $this->data['Post']['id']; ?>" style="cursor: pointer; vertical-align: top;"><?php __('remove'); ?></a>
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
