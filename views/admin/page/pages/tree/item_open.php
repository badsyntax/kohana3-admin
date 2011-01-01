<li>
	<?php echo HTML::anchor('admin/pages/edit/'.$page->id, $page->title, array(
		'class' => 'page'.(in_array($page->id, $open_items) ? ' tree-open':''), 
		'data-id' => $page->id
	))?>