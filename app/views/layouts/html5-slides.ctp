<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js ie6" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8" lang="en"> <![endif]-->
<!--[if gt IE 8]><!-->  <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	
	<title>Getting Started with deck.js</title>
	
	<meta name="description" content="A jQuery library for modern HTML presentations">
	<meta name="author" content="Caleb Troughton">
	<meta name="viewport" content="width=1024, user-scalable=no">
	
	
<?php

$this->MzJavascript->link('jquery.1.6.4.min');
$this->MzJavascript->link('slides/core/deck.core.js');
$this->MzJavascript->link('slides/extensions/menu/deck.menu.js');
$this->MzJavascript->link('slides/extensions/goto/deck.goto.js');
$this->MzJavascript->link('slides/extensions/status/deck.status.js');
$this->MzJavascript->link('slides/extensions/navigation/deck.navigation.js');
$this->MzJavascript->link('slides/extensions/hash/deck.hash.js');
$this->MzJavascript->link('slides/modernizr.custom.js');
$this->MzJavascript->link('slides/main/main.js');

$this->MzHtml->css('slides/core/deck.core');
$this->MzHtml->css('slides/extensions/goto/deck.goto');
$this->MzHtml->css('slides/extensions/menu/deck.menu');
$this->MzHtml->css('slides/extensions/navigation/deck.navigation');
$this->MzHtml->css('slides/extensions/status/deck.status');
$this->MzHtml->css('slides/extensions/hash/deck.hash');
$this->MzHtml->css('slides/main/main');
$this->MzHtml->css('slides/themes/transition/fade');

echo $asset->scripts_for_layout();

?>
</head>
<body class="deck-container">
	<?php echo $content_for_layout; ?>
</body>
</html>
