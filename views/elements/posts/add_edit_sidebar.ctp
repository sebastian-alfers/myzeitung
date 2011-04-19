<div id="leftcolwapper">
<div class="leftcol">
<div class="leftcolcontent">
<div class="userstart" id="user_sidebar_content" style="display:none;">
<p>
<form id="file_upload"
	action="http://localhost/myzeitung/posts/ajxImageProcess"
	method="POST" enctype="multipart/form-data"><input
	type="file" name="file" multiple> <span>+</span><?php __('Add Images'); ?>

<input type="hidden" name="hash"
	value="<?php echo $hash; ?>" /></form>
</p>
<br /><br />
<p><a class="btn" id="add_topick_link"><span>+</span><?php __('Add Topic'); ?></a>
</p>
<br />
<br />
<p><a class="btn" id="add_url_link"><span>+</span><?php __('Add URL'); ?></a>
</p>
</div>



</div>
<!-- /.leftcolcontent --></div>
<!-- /.leftcol --> 

</div>
<!-- / #leftcolwapper -->
