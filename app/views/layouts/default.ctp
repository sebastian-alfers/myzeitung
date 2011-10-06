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
	    <?php echo $this->element('global/title');?>
	</title>

    <?php echo $this->element('global/open_graph'); ?>

    <?php if(isset($canonical_for_layout)):?>
        <?php echo $this->Html->meta('canonical', $canonical_for_layout, array('rel'=>'canonical', 'type'=>null, 'title'=>null, 'inline' => false)); ?>
    <?php endif;?>
    <?php if(isset($paginator_prev_for_layout)):?>
      <link rel="prev" href="<?php echo $paginator_prev_for_layout;?>" />
    <?php endif;?>
    <?php if(isset($paginator_next_for_layout)):?>
      <link rel="next" href="<?php echo $paginator_next_for_layout;?>" />
    <?php endif;?>
    <?php if(isset($rss_for_layout)):?>
          <?php echo $this->Html->meta('rss', $rss_for_layout); ?>
    <?php endif;?>
    <?php // all scripts and css declarations must be added to the following element?>
    <?php echo $this->element('scripts_css'); ?>
    <?php echo $this->element('tracking/google_analytics/track'); ?>
</head>
	<body class="<?php echo $body_class; ?>">
    <?php echo $this->element('global/mzslides/main'); ?>

    <?php
    if($session->read('Auth.User.id')){
        echo $this->element('users/modal_subscribe');
    }
    ?>
    <div class="popbox shadow" id="subscribe-box"></div>
    <?php echo $this->element('helpcenter/main'); ?>

		<div id="main-wrapper">
           			<?php echo $this->element('header'); ?>
			<div id="content" <?php if(isset($content_class)){ echo 'class="'.$content_class.'"';} ?>>
            <cake:nocache>
			    <?php echo $this->Session->flash(); ?>
            </cake:nocache>

			<?php echo $content_for_layout; ?>
			
			<?php echo $this->element('footer'); ?>
				
					
			</div><!-- / #content -->
		</div> <!-- / #main-wapper -->
		<?php echo $this->element('sql_dump'); ?>
        <?php if(($this->params['controller'] == 'posts' && $this->params['action'] == 'view') || ($this->params['controller'] == 'papers')): ?>
            <script type="text/javascript" src="https://apis.google.com/js/plusone.js">
              {lang: 'de'}
            </script>
        <?php endif; ?>
	</body>
</html>
