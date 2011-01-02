<ul id="breadcrumbs">
	<?php foreach($pages as $c => $page){?>
			<li>
				<?php echo HTML::anchor($page['url'], $page['title'])?>
				
				<?php if ($c < count($pages)-1){?>
					&raquo;
				<?php }?>
			</li>
	<?php }?>
</ul>