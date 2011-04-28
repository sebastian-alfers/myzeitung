<?php echo $this->element('users/sidebar'); ?>
<?php
// extracting the first paragraph and the rest of the post-content
// important to not copy the <p> </p> tags, because these are already described in the view with a class
$end = strpos($post['Post']['content'], '</p>', 0);
$first_paragraph = substr($post['Post']['content'], 3 , $end+3);
$content_after_first_paragraph = substr($post['Post']['content'], $end+4);
?>
<div id="maincolwrapper"> 
	<div id="maincol">
		<div class="article-nav">
				<ul class="iconbar">
					<li class="reposts"><?php echo $post['Post']['posts_user_count'];?></li>
					<li class="views"><?php echo $post['Post']['view_count'];?></li>
					<li class="comments"><?php echo $post['Post']['comment_count'];?><span></span></li>								
				</ul>
		
				<ul class="social-links">
				<li><a class="btn"><span class="repost-ico icon"></span><?php echo __('Repost', true);?></a></li>
				</ul><!-- / .social-links -->
			
		</div><!-- / .article-nav -->
		
		<div class="articleview-wrapper">
			<div class="articleview">
			<p><strong><?php echo __('posted', true).' '.$this->Time->timeAgoInWords($post['Post']['created'], array('end' => '+1 Year'));?></strong><?php echo ' '.$post['Post']['comment_count'].' '.__('Comments', true);?></p>
			<h1><?php echo $post['Post']['title'];?></h1>
			<?php if(isset ($post['Post']['image'][0]) && !empty($post['Post']['image'][0])):?>
				<?php 
				$infos = $image->resize($post['Post']['image'][0]['path'], 388, 291, true, true);

				$rel_path = $infos['path'];  
				?>
				<span class="main-article-imgs"><?php echo $this->Html->image($rel_path);?>
				
				<?php if(isset($post['Post']['image'][1])): ?>
					<?php 
					$first_img_width = $infos['width'];
					$count_sub_images = count($post['Post']['image']) -1;
					
					$div_width = 80;
					$div_height = 80;
					$sub_width = $div_width * $count_sub_images;
					
					//check, if horizonatl scrolling needed
					$sub_width += 40; //padding for buddon (left and right)
					
					if($sub_width > $first_img_width){
						//make horizonal scrolling
					}
					else{ ?>
					<p>
					<?php 
					
					$divAspectRatio = 1.7094017094; // -> 200/117;				
					?>
					<div id="examples">
					<?php for($i = 1; $i < count($post['Post']['image']); $i++): ?>
						<?php //debug($post['Post']['image'][$i]['path']); ?>
						<?php //echo $this->Html->image($image->resize($post['Post']['image'][$i]['path'], 80, 80));?>
	
						<?php 
						//aspect ratio of div for image preview
	
	
						//get aspect ratio of post image
						$img_width = $post['Post']['image'][$i]['size'][0];
						$img_height = $post['Post']['image'][$i]['size'][1];
						$imageAspectRatio =  $img_width/$img_height;
	
						$inline_styles = '';
						if($imageAspectRatio > $divAspectRatio){
							//landscape aspect ratio
							$img_resize_info = $image->resize($post['Post']['image'][$i]['path'], 190, 80, true, true);//return array bacuse of last param -> true
							$rel_path = $img_resize_info['path']; 
							//if image is x px wider then the div -> move the half of x to left
							//$inline_styles = 'margin-left:-'.(($img_resize_info['width'] - $div_width) / 2) . 'px';	
						}
						else{
							//portrait aspect ratio
							
							$img_resize_info = $image->resize($post['Post']['image'][$i]['path'],80, 130, true, true);//return array bacuse of last param -> true
							$rel_path = $img_resize_info['path']; 
							//if image is x px wider then the div -> move the half of x to left
							//$inline_styles = 'margin-top:-'.(($img_resize_info['height'] - $div_height) / 2) . 'px';
						}
						$inline_styles = '';
						//debug($post['Post']['image'][0]);die(); 
						
						?>
      					<a href="/myzeitung/img/<?php echo $post['Post']['image'][$i]['path']; ?>"><?php  echo $this->Html->image($rel_path, array('style' => $inline_styles)); ?></a>

							<?php  //echo $this->Html->image($rel_path, array('style' => $inline_styles)); ?>
					
						
					<?php endfor; ?>
					    </div>
    <script type="text/javascript">
      TopUp.addPresets({
        "#examples a": {
          title: "Gallery {alt} ({current} of {total})",
          group: "examples",
          readAltText: 1,
          shaded: 1
        }
      });
    </script>
						<?php 					
					}
					
					?>
					
				<?php endif; ?>
				</p>
				</span>
			<?php endif;?>
			<p class="first-paragraph" ><?php echo $first_paragraph;?></p>
			<?php echo $content_after_first_paragraph;?>			
			</div><!-- /. articleview -->
			
			<div class="comments">
			<?php // Comment Input Box?>
			<?php echo $this->element('comments/add', array('post_id' => $post['Post']['id'])); ?>
			<?php // Comments Pagination?>
			<?php echo $this->element('comments/navigator'); ?>
			</div> <!-- / .comments -->
			
		</div> <!-- /. articleview-wrapper -->							
	
	</div><!-- / #maincol -->

</div><!-- / #maincolwrapper -->