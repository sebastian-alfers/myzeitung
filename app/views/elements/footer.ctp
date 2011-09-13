
<div id="footer">
	<p>
		myZeitung © 2011 | <a href="">Impressum</a> | <a href="">AGBs</a> | <a
			href="">Datenschutzrichtlinien</a> | <a href="">Kontakt</a>
	</p>
</div>
<!-- / #footer -->
<?php
//helpcenter

$data = array(array('key' => '#user-sidebar-subscribe-btn a', 'value' => 'super text'), array('key' => '#show-subscribers', 'value' => 'lalalalala55'));


?>

<script type="text/javascript">
    var helpcenter = <?php echo json_encode($data) ?>;
</script>
