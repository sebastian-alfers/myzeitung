<?php
$subscribe_link = '/login';
if($session->read('Auth.User.id')){
    $subscribe_link = '#';
}
?>
<?php echo $this->element('search/results/sidebar'); ?>
<div id="maincolwrapper"> 
	<div id="maincol">

			<h4 class="nav-title"><span><?php echo __('Searchresults for')?></span> <?php echo $query;?></h4>
            <?php if(isset($results)): ?>
       		    <?php echo $this->element('search/results', array('results' => $results, 'subscribe_link' => $subscribe_link)); ?>
            <?php endif;?>
		
    <?php if(isset($results)): ?>
	    <?php if(($start + $per_page) < $rows): ?>
		    <p><a class="more-results" id="link-more-results"><?php __('more results'); ?></a></p>
         <?php endif;?>
	<?php endif; ?>		
	</div><!-- / #maincol -->
</div><!-- / #maincolwrapper -->

<script type="text/javascript">
<!--

var start = 0;
var increment = <?php echo $per_page?>;

$('.more-results').live('click', function() {
	start +=increment;
	var req = $.post(base_url + '/search/index/', {q:"<?php echo $query; ?>", start:start})
	.success(function( obj ){

		$('.search-result').append(obj);
		var options = {};
		$('#more-results'+start).toggle( 'blind', options, 500 );
	})		   
	.error(function(){
			alert('error');
	});		
});	
//-->
</script>