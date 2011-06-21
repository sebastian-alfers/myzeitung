<?php echo $this->element('search/results/sidebar'); ?>
<div id="maincolwrapper"> 
	<div id="maincol">
	
	<h4 class="nav-title"><span><?php echo __('Searchresults for')?></span> <?php echo $query;?></h4>
		<ul class="search-result">
		<?php if(isset($results)): ?>	 
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
		<?php endif; ?>
		</ul> <!-- / .search-result -->
						
	<p><a href="" class="more-results" >Weitere Ergebnisse anzeigen</a></p>
	
	</div><!-- / #maincol -->
</div><!-- / #maincolwrapper -->





<?php /*?>

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
<?php */ ?>