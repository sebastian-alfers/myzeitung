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
		<?php __('myBackend'); ?>
	</title>
	<?php // all scripts and css declarations must be added to the following element?>
	<?php echo $this->element('admin_scripts_css'); ?>
	
</head>
	<body>
		<div id="main-wrapper">
			<?php echo $this->element('admin_header'); ?>
			<div id="content" <?php if(isset($content_class)){ echo 'class="'.$content_class.'"';} ?>>
			
			<?php echo $this->Session->flash(); ?>

			<?php echo $content_for_layout; ?>
			
			<?php echo $this->element('footer'); ?>
				
					
			</div><!-- / #content -->
		</div> <!-- / #main-wapper -->
		<?php echo $this->element('sql_dump'); ?>
	</body>
</html>
