<div class="action-bar clear">
	<div class="action-menu helper-right">
		<button>Actions</button>
		<ul>
			<li><?php echo HTML::anchor('admin/users/add/', __('Add user'))?></li>
			<li><?php echo HTML::anchor('admin/groups/add/', __('Add group'))?></li>
			<li><?php echo HTML::anchor('admin/roles', __('Edit roles'))?></li>
		</ul>
	</div>

	<h1>Users</h1>
</div>

<fieldset class="last">
	
	<div id="page-tree" class="ui-tree">
		loading tree...
	</div>
	
</fieldset>