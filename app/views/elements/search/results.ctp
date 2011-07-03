<?php if(isset($results)): ?>
	<ul class="search-result">	 
	<?php  foreach ($results['results'] as $result): ?>
		<?php if($result instanceof Apache_Solr_Document): ?>
			<?php if($result->type == 'user'):?>
			<?php echo $this->element('search/results/user', array('user' => $result)); ?>
			<?php elseif($result->type == 'post'):?>
			<?php echo $this->element('search/results/post', array('post' => $result)); ?>
			<?php elseif($result->type == 'paper'):?>
			<?php echo $this->element('search/results/paper', array('paper' => $result)); ?>
			<?php endif;?>
		<?php endif;?>
	<?php endforeach; ?>
	</ul> <!-- / .search-result -->	
<?php endif; ?>

