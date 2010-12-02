<nav class="second-level">
	<?php
		$nav = Kohana::config('admin/nav');
		$uri_segments = explode('/', Request::instance()->uri);

		foreach($nav['links'] as $url => $text) {
			$attributes = $url === $uri_segments[0].'/'.@$uri_segments[1]
				? array('class' => 'selected')
				: NULL;
			echo HTML::anchor($url, $text, $attributes);
		}
	?>
</nav>
