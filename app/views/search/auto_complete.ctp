<?php if(isset($results) && !empty($results)): ?>
 <?php  foreach ($results['results'] as $type => $docs): ?>
 	
 	<?php if($type == 'post'):?>
		<li class="type-article">
			<h6>Artikel</h6>
			<?php echo $this->element('search/autocomplete/posts', array('post_documents' => $docs)); ?>
		</li><!-- /type-article --> 	
 	<?php elseif($type == 'paper'): ?>
 		<li class="type-newspaper">
			<h6>Zeitungen</h6>
 			<?php echo $this->element('search/autocomplete/papers', array('paper_documents' => $docs)); ?>
 		</li>
 	<?php elseif($type == 'user'): ?>
		<li class="type-user">
			<h6>Autoren</h6>
 			<?php echo $this->element('search/autocomplete/users', array('user_documents' => $docs)); ?>
		</li> 	
 	<?php endif; ?>
 	<?php echo $type;?>
<?php endforeach; ?>
<li class="big-btn">
<a href="" class="btn big"><span class="send-icon"></span>Alle Suchergebnisse anzeigennn<?php echo count($results); ?></a>
</li>  
<?php endif; ?>


					