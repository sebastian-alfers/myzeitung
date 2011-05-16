<?php
/**
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake.console.libs.templates.skel.views.layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<?php echo $this->Html->docType('xhtml-trans'); ?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $this->Html->charset(); ?>
	
	<title>
		<?php __('myZeitung'); ?>
		<?php echo $title_for_layout; ?>
	</title>
	
	<script type="text/javascript">
	
	var base_url = '<?php echo DS.APP_DIR; ?>'
	
	</script>	
	
	<?php
		echo $this->Html->meta('icon');
		
				
		e($html->script('jquery-1.5.1.min'));
		e($html->script('jquery.fileupload'));
		e($html->script('jquery.fileupload-ui'));
		e($html->script('jquery-ui-1.8.11.min'));
		
		e($html->css('jquery.fileupload-ui'));
		e($html->css('jquery-ui-1.8.11'));		
		
		
		//echo $this->Html->css('mz.custom');		
		
		echo $this->Html->css('style');
	

		echo $scripts_for_layout;
	?>
	
</head>
	<body>
		<div id="main-wrapper">
			<?php echo $this->element('header'); ?>
			<div id="content">
			
			<?php echo $this->Session->flash(); ?>

			<?php echo $content_for_layout; ?>
			
			<?php echo $this->element('footer'); ?>
				
					
			</div><!-- / #content -->
		</div> <!-- / #main-wapper -->
		<?php echo $this->element('sql_dump'); ?>
	</body>
</html>
