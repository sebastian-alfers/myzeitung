
<?php if(isset($results) && !empty($results) && is_array($results)): ?>
 <?php  foreach ($results['results'] as $type => $docs): ?>
 	
 	<?php if($type == 'post'):?>
		<li class="type-article">
			<h6><?php echo __('Posts', true);?></h6>
			<?php echo $this->element('search/autocomplete/posts', array('post_documents' => $docs, 'home' => $home)); ?>
		</li><!-- /type-article -->
 	<?php elseif($type == 'paper'): ?>
 		<li class="type-newspaper">
			<h6><?php echo __('Papers', true);?></h6>
 			<?php echo $this->element('search/autocomplete/papers', array('paper_documents' => $docs, 'home' => $home)); ?>
 		</li>
 	<?php elseif($type == 'user'): ?>
		<li class="type-user">
			<h6><?php echo __('Authors', true);?></h6>
 			<?php echo $this->element('search/autocomplete/users', array('user_documents' => $docs, 'home' => $home)); ?>
		</li> 	
 	<?php endif; ?>
<?php endforeach; ?>
<li class="big-btn">
<a href="#" onclick="$('#search').submit();" class="btn big"><span class="send-icon"></span><?php __('Show all results'); ?></a>
</li>  
<?php endif; ?>