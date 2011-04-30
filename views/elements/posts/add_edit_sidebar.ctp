<div id="leftcolwapper">
<div class="leftcol">
<div class="leftcolcontent">
<div class="userstart" id="user_sidebar_content"
	style="display: none;">
<p>


<form id="file_upload"
	action="http://localhost/myzeitung/posts/ajxImageProcess"
	method="POST" enctype="multipart/form-data"><input
	type="file" name="file" multiple> <span>+</span><?php __('Add Images'); ?>

<input type="hidden" name="hash"
	value="<?php echo $hash; ?>" /></form>
</p>
<br />
<br />
<p><a class="btn" id="add_topick_link"><span>+</span><?php __('Add Topic'); ?></a>
</p>
<br />
<br />
<p><a class="btn" id="add_url_link"><span>+</span><?php __('Add URL'); ?></a>
</p>
<br />
<br />
<p>
<hr />
<br /><br />
<div id="files" style="float: left"></div>
	<script>
	$(document).ready(function() {
		$( "#sortable" ).sortable();
		$( "#sortable" ).disableSelection();
	});
	</script>


<div >
<ul id="sortable">
	<?php if(isset($images)): ?>
		<?php foreach($images as $img): ?>
			<?php //debug($img); ?>
			<li id="<?php echo $img['name']; ?>" class="ui-state-default" style="cursor: move;"><?php echo $this->Html->image($img['path'], array('style' => 'width:100px')); ?><a class="remove_li_item" style="cursor:pointer;vertical-align:top;"><?php __('remove'); ?></a></li>
		<?php endforeach; ?>
	<?php endif;?>
</ul>
</div><!-- / sortable -->
</p>
<p>
<ul id="links"></ul>
</p>
</div><!-- / userstart -->

</div><!-- /.leftcolcontent -->
</div><!-- /.leftcol -->
</div><!-- / #leftcolwapper -->
