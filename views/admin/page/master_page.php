<!DOCTYPE html>
<html lang="en" class="no-js admin <?php echo Kohana::$environment?> <?php echo Request::instance()->controller?>" dir="ltr">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title><?php echo $title ?></title>
	<?php echo implode("\n\t", array_map('HTML::style', $styles)), "\n";?>
	<?php echo implode("\n\t", array_map('HTML::script', $scripts)), "\n" ?>
	<?php echo View::factory('page/fragments/analytics'), "\n"?>
</head>
	<!--[if lt IE 7 ]> <body class="ie6"> <![endif]-->
	<!--[if IE 7 ]>    <body class="ie7"> <![endif]-->
	<!--[if IE 8 ]>    <body class="ie8"> <![endif]-->
	<!--[if IE 9 ]>    <body class="ie9"> <![endif]-->
	<!--[if (gt IE 9)|!(IE)]><!--> <body> <!--<![endif]-->

	<?php echo View::factory('admin/page/fragments/header') ?>

	<div id="ajax-loading">
		<img src="/modules/admin/media/img/ajax_loader.gif" />
	</div>

	<div id="content">

		<div id="admin-nav" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
			
			<?php echo View::factory('admin/page/fragments/nav') ?>
			
			<div class="ui-tabs-panel ui-widget-content ui-corner-bottom">
				
				<div id="messages">
					<?php echo Message::render() ?>
				</div>

				<?php echo $content ?>
			</div>
		
		</div> <!-- /#admin-nav -->

	</div> <!-- /#content -->

	<?php echo View::factory('admin/page/fragments/footer', array('paths' => $paths)) ?>
</body>
</html>