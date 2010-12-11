<div class="action-bar clear">
	<a href="<?php echo URL::site('admin/pages/add/'.$page->id)?>" id="add-child-page" class="button add small helper-right">
		<span>Add child page</span>
	</a>
	<a href="<?php echo URL::site('admin/pages/delete/'.$page->id)?>" id="delete-page" class="button add small helper-right">
		<span>Delete page</span>
	</a>
	<script type="text/javascript">
	(function($){
		$('#delete-page').click(function(){

			return confirm('<?php echo __('Are you sure you want to delete this page? All children pages will also be deleted!')?>');
		});
	})(this.jQuery);
	</script>
	<h1>Edit page</h1>
</div>
<?php echo Form::open()?>
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

		<?php echo Form::submit('save', 'Save', array('class' => 'button'))?>
	</fieldset>
<?php echo Form::close()?>
