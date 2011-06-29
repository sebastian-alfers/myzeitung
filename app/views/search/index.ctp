<?php echo $this->element('search/results/sidebar'); ?>
<div id="maincolwrapper"> 
	<div id="maincol">
	
	<h4 class="nav-title"><span><?php echo __('Searchresults for')?></span> <?php echo $query;?></h4>
	<?php echo $this->element('search/results', array('results' => $results)); ?>			
	</div><!-- / #maincol -->
</div><!-- / #maincolwrapper -->


<script type="text/javascript">
<!--
$('.more-results').bind('click', function() {
	var type="more";
	alert(base_url + '/search/index/');
	var req = $.post(base_url + '/search/index/', {type:type, q:"<?php echo $query; ?>"})
	.success(function( obj ){
		alert(obj);		
	})		   
	.error(function(){
			alert('error');
	});			  
});	
//-->
</script>


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