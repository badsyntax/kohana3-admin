<div class="action-bar clear">
	<div class="action-menu helper-right">
		<button>Actions</button>
		<ul>
			<li><?php echo HTML::anchor('admin/pages/add/', __('Add page'))?></li>
		</ul>
	</div>
	<?php echo $breadcrumbs?>
</div>

<h1>Pages</h1>


<fieldset class="pages-list last">
<div id="page-tree" class="ui-tree">
	loading tree...
</div>
</fieldset>

<fieldset id="pages-information" class="pages-information ui-helper-hidden last">
	Showing <span id="total-pages"></span> pages
</fieldset>