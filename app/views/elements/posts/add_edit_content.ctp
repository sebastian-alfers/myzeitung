<?php echo $html->script('post/add_edit.js'); ?>
<?php echo $this->element('topics/modal_add_topic'); ?>
<?php echo $this->element('posts/modal_add_url'); ?>
<?php echo $this->element('posts/modal_add_video_url'); ?>

<script type="text/javascript">
$(document).ready(function(){
	$(".scroll").click(function(event){
		//prevent the default action for the click event
		event.preventDefault();

        scrollTo('sortable');

	});
});
</script>

        <a href="#sortable" class="scroll">test</a>


<div class="article-nav">
    <h1><?php __('New Post'); ?></h1>

    <ul class="create-actions">
        <?php
        $form = 'PostAddForm';
        if($this->params['action'] == 'edit') $form = 'PostEditForm';
        ?>
        <li class="big-btn" onclick="preSubmitActions();$('#<?php echo $form; ?>').submit();"><a class="btn"><span class="icon icon-tick"></span><?php __('Save Post'); ?></a></li>
       <?php // <li class="big-btn"><a href="create-article.html" class="btn"><span class="icon icon-circle"></span>Vorschau</a></li> */ ?>
    </ul>
</div>


<?php echo $this->Form->create('Post', array("enctype" => "multipart/form-data"));?>
<?php echo $this->Form->input('id'); ?>
<?php //echo $this->Form->input('topic_id'); ?>
<?php echo $this->Form->hidden('topic_id',array('value' => $mzform->value($this, 'Post', 'topic_id'))); ?>
<?php echo $this->Form->hidden('user_id',array('value' => $user_id)); ?>
<?php echo $this->Form->hidden('hash',array('value' => $hash)); ?>
<?php echo $this->Form->hidden('images',array('value' => '')); ?>
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
		class="ui-state-default teaser-sort">
		<a class="remove_li_item" name="img/<?php echo $img['path']; ?>" id="<?php echo $this->data['Post']['id']; ?>" style="cursor: pointer; vertical-align: top;"><?php __('remove'); ?></a>
        <?php echo $this->Html->image($img['path'], array('style' => 'width:100px')); ?>
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
