<div class="action-bar clear">
	<div class="action-menu helper-right">
		<button>Actions</button>
		<ul>
			<li><?php echo HTML::anchor('admin/groups/add/', __('Add group'))?></li>
		</ul>
	</div>

	<?php echo $breadcrumbs?>
</div>

<fieldset>
	
	<div id="groups-tree" class="ui-tree">
		loading tree...
	</div>
	
</fieldset>

<fieldset id="groups-information" class="groups-information ui-helper-hidden last">
	Showing <span id="total-groups"></span> groups
</fieldset>