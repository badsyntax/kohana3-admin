<div class="action-bar clear">
	<div class="action-menu helper-right">
		<button>Actions</button>
		<ul>
			<li><?php echo HTML::anchor('admin/users/add/', __('Add user'))?></li>
			<li><?php echo HTML::anchor('admin/groups/add/', __('Add group'))?></li>
			<li><?php echo HTML::anchor('admin/roles', __('Edit roles'))?></li>
		</ul>
	</div>

	<?php echo $breadcrumbs?>
</div>

<fieldset>
	
	<div id="page-tree" class="ui-tree">
		loading tree...
	</div>
	
</fieldset>

<fieldset id="users-information" class="users-information ui-helper-hidden last">
	Showing <span id="total-users"></span> users and groups
</fieldset>