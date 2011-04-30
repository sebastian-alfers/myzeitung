<?php 
/**
 * @param $post_documents - array of type Apache_Solr_Document Object
 */
?>

<?php if(isset($post_documents)): ?>
<hr />
<h2><?php __('Posts');?> (<?php echo count($post_documents); ?>)</h2>
	<ul>
	<?php foreach($post_documents as $post): ?>
		<?php if($post instanceof Apache_Solr_Document): ?>
			<li  onclick="window.location = '<?php  echo DS.APP_DIR.DS; ?>posts/view/<?php echo $post->id; ?>';"><?php echo $post->post_title;?>
			<br />
			by <?php echo $post->user_name?> <?php if(isset($post->user_username) && !empty($post->user_username)) echo '('.$post->user_username. ')'; ?> - <?php echo $this->Time->timeAgoInWords($post->timestamp, array('end' => '+1 Year'));?>
			
			</li>
			
		<?php endif; ?>			
	<?php endforeach; ?>
	</ul>
<?php endif; ?>
