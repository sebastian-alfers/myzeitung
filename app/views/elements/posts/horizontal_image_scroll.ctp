<?php e($html->script('jquery.tools.min.js')); ?>

<?php
if(!isset($images)) {
	return;
}



//remove emtpy elements
$imgs = array();
foreach($images as $img){
	$imgs[] = $img;
}
$images = $imgs;
unset($imgs);

//can be passed from the place this element is included in
if(!isset($width)){
	$width = 200;
}
?>

<?php
$first_img_width = $width;
$count_sub_images = count($images);


$div_width = 80;
$div_height = 80;
$sub_width = $div_width * $count_sub_images;

//check, if horizonatl scrolling needed
$sub_width += 40; //padding for buddon (left and right)

$is_scroling = $count_sub_images > 1;//now, we scroll only two images
?>


	<?php if($is_scroling): ?>
	<a class="prev browse left"></a>
	<?php endif; //end scrolling or not ?>
<p>
	<div class="scrollable">
		<div class="items">

			<?php for($i = 0; $i <= $count_sub_images-1; $i++): ?>
	            <div>
				<?php 
				if(!isset($images[$i])) continue;
		        $img_details['image'] = $images[$i];
                $link_data = array();
				$link_data['url'] = '/img/'.$img_details['image']['path'];
				$link_data['custom'] = array('class' => 'pirobox_gall', 'rel' => 'gallery', 'rev' => $i+1);

                echo $image->render($img_details, 180, 150, array("alt" => 'sub'), $link_data);
				//$info = $image->resize($images[$i]['path'], 80, 80, null, true);//return array because of last param -> true
				//echo $this->Html->image($info['path'], array('style' => $info['inline'])); ?>

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



