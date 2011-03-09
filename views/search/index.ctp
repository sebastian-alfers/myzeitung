 <form  accept-charset="utf-8" method="get">
      <label for="q">Search:</label>
      <input id="q" name="q" type="text" value="<?php echo htmlspecialchars($query, ENT_QUOTES, 'utf-8'); ?>"/>
      <input type="submit"/>
    </form>

    
<?php if(isset($results)): ?>

<?php debug($results); ?>

 <?php  foreach ($results['results']->response->docs as $doc): ?>
	<?php foreach ($doc as $field => $value): ?>
	
    	<?php if(!empty($field)) echo htmlspecialchars($field, ENT_NOQUOTES, 'utf-8'); ?>:
        <?php if(!empty($field)) echo htmlspecialchars($value, ENT_NOQUOTES, 'utf-8'); ?>;
	<?php endforeach; ?>
	<br />
<?php endforeach; ?>
  
<?php endif; ?>