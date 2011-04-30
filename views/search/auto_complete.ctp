<?php if(isset($results)): ?>
 <?php  foreach ($results['results'] as $type => $docs): ?>
 	
 	<?php if($type == 'post'):?>
 		<div class="posts">
 			<?php echo $this->element('search/autocomplete/posts', array('post_documents' => $docs)); ?>
 		</div>
 	<?php elseif($type == 'paper'): ?>
 		<div class="papers">
 			<?php echo $this->element('search/autocomplete/papers', array('paper_documents' => $docs)); ?>
 		</div>
 	<?php elseif($type == 'user'): ?>
 		<div class="user">
 			<?php echo $this->element('search/autocomplete/users', array('user_documents' => $docs)); ?>
 		</div> 	
 	<?php endif; ?>
<?php endforeach; ?>
  
<?php endif; ?>