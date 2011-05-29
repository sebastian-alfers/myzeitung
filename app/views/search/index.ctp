 <form  accept-charset="utf-8" method="get">
      <label for="q">Search:</label>
      <input id="q" name="q" type="text" value="<?php echo htmlspecialchars($query, ENT_QUOTES, 'utf-8'); ?>"/>
      <input type="submit"/>
    </form>

<?php if(isset($results)): ?>

 <?php  foreach ($results['results'] as $type => $docs): ?>
 	<?php echo "type: " . $type; ?> (<?php echo count($docs)?>)<br />
	
 	<?php for($i=0 ; $i < count($docs); $i++): ?>
 		<?php $doc = $docs[$i]; ?>
 		<ul onclick="alert('');">
 		<?php foreach ($doc as $field => $value): ?>
	    	<li><?php echo $field?>: <?php echo $value; ?></li>
		<?php endforeach; ?>
		</ul>
		<hr />	
	<?php endfor; ?>
	
<?php endforeach; ?>
  
<?php endif; ?>