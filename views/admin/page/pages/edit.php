<div class="action-bar clear">
	<div class="action-menu helper-right">
		<button>Actions</button>
		<ul>
			<li><?php echo HTML::anchor('admin/pages/add/'.$page->id, __('Add child page'))?></li>
			<li><?php echo HTML::anchor('admin/pages/delete/'.$page->id, __('Delete page'))?></li>
		</ul>
	</div>
	<script type="text/javascript">
	(function($){
		$('#delete-page').click(function(){

			return confirm('<?php echo __('Are you sure you want to delete this page? All children pages will also be deleted!')?>');
		});
	})(this.jQuery);
	</script>
	<h1>Edit page</h1>
</div>

<?php echo Form::open(NULL, array('class' => 'ajax-validate'))?>
	<fieldset>
		
		<div class="field">
			<?php echo 
				Form::label('parent_id', __('Parent page'), NULL, $errors),
				Form::select('parent_id', $pages, $_POST['parent_id'], NULL, $errors)
			?>
		</div>
		<div class="field">
			<?php echo 
				Form::label('title', __('Title'), NULL, $errors),
				Form::input('title', $_POST['title'], NULL, $errors)
			?>
		</div>
		<div class="field">
			<?php echo 
				Form::label('uri', __('URI'), NULL, $errors),
				Form::input('uri', $_POST['uri'], NULL, $errors)
			?>
		</div>
		<div class="field">
			<?php echo 
				Form::label('description', __('Description'), NULL, $errors),
				Form::input('description', $_POST['description'], NULL, $errors)
			?>
		</div>
		<div class="field">
			<?php echo 
				Form::label('body', __('Content'), NULL, $errors),
				Form::textarea('body', $_POST['body'], NULL, TRUE, $errors)
			?>
		</div>

		<?php echo Form::button('save', 'Save', array('type' => 'submit', 'class' => 'ui-button save'))?>
		
	</fieldset>
<?php echo Form::close()?>
