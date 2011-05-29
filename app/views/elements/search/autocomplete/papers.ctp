<?php 
/**
 * @param $paper_documents - array of type Apache_Solr_Document Object
 */
?>

<?php if(isset($paper_documents)): ?>
<hr />
<h2><?php __('Papers');?> (<?php echo count($paper_documents); ?>)</h2>
	<ul>
	<?php foreach($paper_documents as $paper): ?>
		<?php if($paper instanceof Apache_Solr_Document): ?>
			<li  onclick="window.location = '<?php  echo DS.APP_DIR.DS; ?>papers/view/<?php echo $paper->id; ?>';"><?php echo $paper->paper_title;?>
			<br />
			by <?php echo $paper->user_name?> <?php if(isset($paper->user_username) && !empty($paper->user_username)) echo '('.$paper->user_username. ')'; ?> - <?php echo $this->Time->timeAgoInWords($paper->timestamp, array('end' => '+1 Year'));?>
			
			</li>
			
		<?php endif; ?>			
	<?php endforeach; ?>
	</ul>
<?php endif; ?>
