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
    <?php echo $this->element('global/open_graph'); ?>

	<?php // all scripts and css declarations must be added to the following element?>
	<?php echo $this->element('scripts_css'); ?>
    <?php echo $this->element('tracking/google_analytics/track'); ?>

</head>
	<body>
            <?php echo $this->element('global/mzslides/main_modal'); ?>
            <cake:nocache>
			    <?php echo $this->Session->flash(); ?>
            </cake:nocache>
			<?php echo $content_for_layout; ?>
			
			<?php echo $this->element('footer'); ?>
				
					
			</div><!-- / #content -->
		</div> <!-- / #main-wapper -->
		<?php echo $this->element('sql_dump'); ?>

        <script type="text/javascript" src="https://apis.google.com/js/plusone.js">
            {lang: 'de'}
        </script>
	</body>
</html>
