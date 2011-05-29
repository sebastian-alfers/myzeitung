<?php 
/**
 * @param $user_documents - array of type Apache_Solr_Document Object
 */
?>

<?php if(isset($user_documents)): ?>
<hr />
<h2><?php __('users');?> (<?php echo count($user_documents); ?>)</h2>
	<ul>
	<?php foreach($user_documents as $user): ?>
		<?php if($user instanceof Apache_Solr_Document): ?>
			<li  onclick="window.location = '<?php  echo DS.APP_DIR.DS; ?>users/view/<?php echo $user->id; ?>';">
			<br />
			by <?php echo $user->user_name?> <?php if(isset($user->user_username) && !empty($user->user_username)) echo '('.$user->user_username. ')'; ?> - <?php echo $this->Time->timeAgoInWords($user->timestamp, array('end' => '+1 Year'));?>
			
			</li>
			
		<?php endif; ?>			
	<?php endforeach; ?>
	</ul>
<?php endif; ?>
