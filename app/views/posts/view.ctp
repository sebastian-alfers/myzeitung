<?php if(count($post['Post']['image']) > 0): ?>
<style type="text/css">

/*Make sure your page contains a valid doctype at the top*/
#simplegallery1{ //CSS for Simple Gallery Example 1
position: relative; /*keep this intact*/
visibility: hidden; /*keep this intact*/
}

#simplegallery1 .gallerydesctext{ //CSS for description DIV of Example 1 (if defined)
text-align: left;
padding: 2px 5px;
}

</style>

<script type="text/javascript" src="/js/simplegallery.js">

/***********************************************
* Simple Controls Gallery- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for this script and 100s more
***********************************************/

</script>

<script type="text/javascript">

var mygallery=new simpleGallery({
	wrapperid: "simplegallery1", //ID of main gallery container,
	dimensions: [250, 180], //width/height of gallery in pixels. Should reflect dimensions of the images exactly
	imagearray: [
        <?php
          foreach($post['Post']['image'] as $post_image){
            $img_details = $image->resize($post_image['path'],250, 250, null, true);
            echo '["/img/'.$img_details['path'].'", "", "", ""],';
          }
        ?>
		/*["http://i26.tinypic.com/11l7ls0.jpg", "", "_new", ""],
		["http://i29.tinypic.com/xp3hns.jpg", "", "", ""],
		["http://i30.tinypic.com/531q3n.jpg", "", "", ""],
		["http://i31.tinypic.com/119w28m.jpg", "", "", ""]*/
	],
	autoplay: [false, 2500, 2], //[auto_play_boolean, delay_btw_slide_millisec, cycles_before_stopping_int]
	persist: false, //remember last viewed slide and recall within same session?
	fadeduration: 500, //transition duration (milliseconds)
	oninit:function(){ //event that fires when gallery has initialized/ ready to run
		//Keyword "this": references current gallery instance (ie: try this.navigate("play/pause"))
	},
	onslide:function(curslide, i){ //event that fires after each slide is shown
		//Keyword "this": references current gallery instance
		//curslide: returns DOM reference to current slide's DIV (ie: try alert(curslide.innerHTML)
		//i: integer reflecting current image within collection being shown (0=1st image, 1=2nd etc)
	}
})

</script>

        <?php endif; ?>



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
				    <?php // tt-title -> class for tipsy &&  'title=...' text for typsy'?>
                     <?php $tipsy_title = sprintf(__n('%d repost', '%d reposts', $post['Post']['posts_user_count'],true), $post['Post']['posts_user_count']);?>
                    <li class="reposts tt-title" title="<?php echo $tipsy_title;?>"><?php echo $post['Post']['posts_user_count'];?></li>
                     <?php $tipsy_title = sprintf(__n('%d time viewed', '%d times viewed', $post['Post']['view_count'],true), $post['Post']['view_count']);?>
                    <li class="views tt-title" title="<?php echo $tipsy_title;?>"><?php echo $post['Post']['view_count'];?></li>
                     <?php $tipsy_title = sprintf(__n('%d comment', '%d comments', $post['Post']['comment_count'],true), $post['Post']['comment_count']);?>
                    <li class="comments tt-title" title="<?php echo $tipsy_title;?>"><?php echo $post['Post']['comment_count'];?><span>.</span></li>
				</ul>
		
				<ul class="social-links">
				<li><a class="btn"><span class="repost-ico icon"></span><?php echo __('Repost', true);?></a></li>
				<?php if($session->read('Auth.User.id') != null && $post['Post']['user_id'] == $session->read('Auth.User.id')): ?>
					<li><?php echo $this->Html->link(__('Edit', true), array('controller' => 'posts',  'action' => 'edit', $post['Post']['id'])); ?></li>
				<?php endif; ?>
				</ul><!-- / .social-links -->
			
		</div><!-- / .article-nav -->
		
		<div class="articleview-wrapper">
			<div class="articleview">
			<p><strong><?php echo __('posted', true).' '.$this->Time->timeAgoInWords($post['Post']['created'], array('end' => '+1 Year'));?></strong></p>
			<h1><?php echo $post['Post']['title'];?></h1>
			<p class="first-paragraph" ><?php echo $first_paragraph;?></p>
            <?php if(isset ($post['Post']['image'][0]) && !empty($post['Post']['image'][0])):?>
				<?php
				$link_data = array();
				$link_data['url'] = array('controller' => 'users', 'action' => 'view', $user['User']['id']);
				$link_data['additional'] = array('class' => 'user-image');

                //$img_details['image'] = $post['Post']['image'][0];

				//unset($post['Post']['image'][0]);
				//$images = $post['Post']['image'];
  
				?>
				<span class="main-article-imgs">
                    <div id="simplegallery1"></div>
					<?php //echo $image->render($img_details, 300, 200, array("alt" => 'main image')); ?>
                    <?php //echo $this->element('posts/horizontal_image_scroll', array('images' => $images)); ?>
				</span>
			<?php endif;?>


			<?php echo $content_after_first_paragraph;?>
			</div><!-- /. articleview -->
			
			<?php if($post['Post']['allow_comments'] == PostsController::ALLOW_COMMENTS_TRUE || ($post['Post']['allow_comments'] == PostsController::ALLOW_COMMENTS_DEFAULT && $user['User']['allow_comments'] == true)):?>
			<div class="comments" style="clear:both">
				<?php // Comment Input Box?>
				<?php if($session->read('Auth.User.id')):?>
					<?php echo $this->element('comments/add', array('post_id' => $post['Post']['id'])); ?>
				<?php endif; ?>
				<?php // Comments Pagination?>
				<?php echo $this->element('comments/navigator'); ?>
			</div> <!-- / .comments -->
			<?php endif;?>
		</div> <!-- /. articleview-wrapper -->							
	
	</div><!-- / #maincol -->

</div><!-- / #maincolwrapper -->