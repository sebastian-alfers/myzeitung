<?php e($html->script('jquery.tools.min.js')); ?>

<?php
if(!isset($images)) {
	return;
}

$is_scroling = count($images) > 1;//now, we scroll only two images
?>


	<?php if($is_scroling): ?>
	<a class="prev browse left"></a>
	<?php endif; //end scrolling or not ?>
<p>
	<div class="scrollable">
		<div class="items">

			<?php for($i = 0; $i <= count($images)-1; $i++): ?>
                 <?php if(!isset($images[$i])) continue;
                       $img_details['image'] = $images[$i];

                $link_data = array();
                if(isset($images[$i]['item_type']) && $images[$i]['item_type'] == 'video'){
                    $href = $images[$i]['video']['video'];
                    $rel = 'iframe-640-505';
                }
                else{
                    $href = $img_details['image']['path'];
                    $href = $this->Cf->url($this->Image->resize($href, 800, null));
                    $rel = 'gallery';

                }


				$link_data['url'] = $href;

				$link_data['custom'] = array('class' => 'pirobox_gall', 'rel' => $rel, 'rev' => $i+1);
                ?>

	            <div>
                 <?php if(isset($img_details['image']['item_type']) && $img_details['image']['item_type'] == 'video'): ?>
                        <span class="post-view video-item">video</span>
                 <?php endif; ?>
				 <?php echo $image->render($img_details, 255, 200, array("alt" => 'sub'), $link_data);?>


                </div>
			<?php endfor; //images to be scrolled ?>
			</div><?php //divs for scrolling ?>	
		</div><?php //end items ?>

	
	<?php if($is_scroling): ?>
		<a class="next browse right"></a>
	<?php endif; ?>
    </div><?php //end scrollable ?>
	<script> 
	// execute your scripts when the DOM is ready. this is mostly a good habit
	$(function() {
	 
		// initialize scrollable
		$(".scrollable").scrollable();
	 
	});
	</script>
		

</p><!-- end wrapper for small images -->



