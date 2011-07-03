<div id="more-results<? echo $start ?>" style="display:none">
<?php echo $this->element('search/results', array('results' => $results)); ?>
</div>

<?php if(($start + $per_page) >= $rows): ?>
<script type="text/javascript">
<!--
$('#link-more-results').hide();
//-->
</script>
<?php endif; ?>		