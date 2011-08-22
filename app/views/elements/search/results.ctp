<?php if(isset($results)): ?>
    <?php
    $has_topics = false;
    if($session->read('Auth.User.topic_count') > 0){
        $has_topics = true;
    }
    if($has_topics){

        echo $this->element('posts/repost_modal_choose_topic');
    }
    ?>
	<ul class="search-result">
	<?php  foreach ($results['results'] as $result): ?>
		<?php if($result instanceof Apache_Solr_Document): ?>
			<?php if($result->type == 'user'):?>
			<?php echo $this->element('search/results/user', array('user' => $result, 'subscribe_link' => $subscribe_link)); ?>
			<?php elseif($result->type == 'post'):?>
			<?php echo $this->element('search/results/post', array('post' => $result, 'has_topics' => $has_topics)); ?>
			<?php elseif($result->type == 'paper'):?>
			<?php echo $this->element('search/results/paper', array('paper' => $result)); ?>
			<?php endif;?>
		<?php endif;?>
	<?php endforeach; ?>
	</ul> <!-- / .search-result -->	
<?php endif; ?>

