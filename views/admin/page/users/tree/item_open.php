<li>
	<?php echo HTML::anchor('admin/groups/edit/'.$group->id, $group->name, array(
		'class' => 'page'.(in_array($group->id, $open_items) ? ' tree-open':''), 
		'data-id' => $group->id
	))?>