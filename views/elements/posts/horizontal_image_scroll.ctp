<?php e($html->script('jquery.tools.min.js')); ?>
<?php 
/**
 * @param array - arry of images with this structure
 *   [] => Array
        (
            [path] => path/to/file.jpg //start from img/*
            [size] => Array //resuld of size method
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

$is_scroling = $count_sub_images > 2;
?>
<p>

		<?php if($is_scroling): ?>
			<a class="prev browse left"></a>
		<?php endif; //end scrolling or or?>
		
		<?php 
		
		$divAspectRatio = 1.7094017094; // -> 200/117;
		?>
		 
		<div class="scrollable" style="width: 180px;">
		<div class="items">
		<div>
		<?php for($i = 0; $i <= $count_sub_images; $i++): ?>
		
			<?php if(!isset($images[$i])) continue; ?>
			<?php //debug($post['Post']['image'][$i]['path']); ?> <?php //echo $this->Html->image($image->resize($post['Post']['image'][$i]['path'], 80, 80));?>
			
			<?php
			//aspect ratio of div for image preview

			//get aspect ratio of post image
			$img_width = $images[$i]['size'][0];
			$img_height = $images[$i]['size'][1];
			$imageAspectRatio =  $img_width/$img_height;
			
			$inline_styles = '';
			if($imageAspectRatio > $divAspectRatio){
				//landscape aspect ratio
				$img_resize_info = $image->resize($images[$i]['path'], 190, 80, true, true);//return array bacuse of last param -> true
				$rel_path = $img_resize_info['path'];
				//if image is x px wider then the div -> move the half of x to left
				//$inline_styles = 'margin-left:-'.(($img_resize_info['width'] - $div_width) / 2) . 'px';
			}
			else{
				//portrait aspect ratio
					
				$img_resize_info = $image->resize($images[$i]['path'],80, 130, true, true);//return array bacuse of last param -> true
				$rel_path = $img_resize_info['path'];
				//if image is x px wider then the div -> move the half of x to left
				//$inline_styles = 'margin-top:-'.(($img_resize_info['height'] - $div_height) / 2) . 'px';
			}
			$inline_styles = '';
			//debug($post['Post']['image'][0]);die();
			
			?> <?php  echo $this->Html->image($rel_path, array('style' => $inline_styles)); ?>
			
			<?php if((($i+1)%2) == 0 && isset($images[($i+1)])): ?>
				</div><div>
			<?php endif; ?>
			
			
		<?php endfor; ?>
		</div>
		
		</div><!-- end items -->
		</div><!-- end scrollable -->
		
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


