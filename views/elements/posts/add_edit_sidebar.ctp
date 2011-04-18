<div id="leftcolwapper">
<div class="leftcol">
	<div class="leftcolcontent">
			<div class="userstart">
			<form id="file_upload"
				action="http://localhost/myzeitung/posts/ajxImageProcess"
				method="POST" enctype="multipart/form-data"><input
				type="file" name="file" multiple>
			
			
			<a class="btn"><span>+</span><?php __('Choose image'); ?></a>
			
			<input type="hidden" name="hash" value="<?php echo $hash; ?>" />
			</form>
			<p>
				<a class="btn" id="add_topick_link"><span>+</span><?php __('Add Topic'); ?></a>
			</p>
			<br /><br />
			<p>
				<a class="btn" id="add_url_link"><span>+</span><?php __('Add URL'); ?></a>
			</p>			
			</div>
			

			
		 </div><!-- /.leftcolcontent -->	
		</div><!-- /.leftcol -->
		
		<iframe src="http://www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Fplatform&amp;width=218&amp;colorscheme=light&amp;show_faces=true&amp;stream=false&amp;header=false&amp;height=268" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:218px; height:268px;"></iframe>
		
</div><!-- / #leftcolwapper -->