<?php 
/**
 * @param $paper_documents - array of type Apache_Solr_Document Object
 */
?>

<?php if(isset($paper_documents)): ?>
	<ul>
		<?php foreach($paper_documents as $paper): ?>
			<?php if($paper instanceof Apache_Solr_Document): ?>
			<?php //debug($paper); die(); ?>
				<li class="art">
					<a href="#"><img src="../img/assets/news-thumb.jpg"></a>
					<h6><a href=""><?php echo $paper->paper_title; ?></a></h6>
					<p class="discr"><?php echo $paper->paper_description; ?></p>
					<p><strong>20320 Leser</strong>
				</p></li>				
			<?php endif; ?>			
		<?php endforeach; ?>
	</ul>
<?php endif; ?>
