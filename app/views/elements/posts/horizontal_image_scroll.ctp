<?php e($html->script('jquery.tools.min.js')); ?>
<?php 
/**
 * @param array - arry of images with this structure
 *   [] => Array
        (
            [path] => path/to/file.jpg //starts from folder img/*
            [size] => Array //result of size method
                (
                    [0] => 800
                    [1] => 640
                    [2] => 3
                    [3] => width="800" height="640"
                    [bits] => 8
                    [mime] => image/png
                )

        )
        
    @param int - width of desired container to be scrolled in
 */
?>

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

$is_scroling = $count_sub_images > 2;//now, we scroll only two images
?>
<p>

	<?php if($is_scroling): ?>
	<a class="prev browse left"></a>
	<?php endif; //end scrolling or not?>
	 
	<div class="scrollable" style="width: 160px;">
		<div class="items">
			<div>
			<?php for($i = 0; $i <= $count_sub_images; $i++): ?>
	
				<?php 
				if(!isset($images[$i])) continue;
		        $img_details['image'] = $images[$i];
                echo $image->render($img_details, 80, 80, array("alt" => 'sub'), array('tag' => 'div'));
				//$info = $image->resize($images[$i]['path'], 80, 80, null, true);//return array because of last param -> true
				//echo $this->Html->image($info['path'], array('style' => $info['inline'])); ?>
		
				<?php if((($i+1)%2) == 0 && isset($images[($i+1)])): ?>
					</div><div><?php //seperator after 2 images?>
				<?php endif; ?>
			<?php endfor; //images to be scrolled ?>
			</div><?php //divs for scrolling ?>	
		</div><?php //end items ?>
	</div><?php //end scrollable ?>
	
	<?php if($is_scroling): ?>
		<a class="next browse right"></a>
	<?php endif; ?>
	<script> 
	// execute your scripts when the DOM is ready. this is mostly a good habit
	$(function() {
	 
		// initialize scrollable
		$(".scrollable").scrollable();
	 
	});
	</script>
		

</p><!-- end wrapper for small images -->


