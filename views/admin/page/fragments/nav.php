<nav class="second-level">
	
		<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
		<?php
			$nav = Kohana::config('admin/nav');
			$uri_segments = explode('/', Request::instance()->uri);

			foreach($nav['links'] as $url => $text) {
				$classes = ($url === Request::instance()->uri OR $url === $uri_segments[0].'/'.@$uri_segments[1])
					? ' ui-tabs-selected ui-state-active'
					: '';
				echo '<li class="ui-state-default ui-corner-top'.$classes.'">'.HTML::anchor($url, $text).'</li>';
			}
		?>
		<li class="ui-state-default ui-corner-top ui-helper-right">
			<?php echo HTML::anchor('admin/signout', 'Sign out')?>
		</li>
		</ul>

</nav>
