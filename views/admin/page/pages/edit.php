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
	<?php echo $breadcrumbs?>
</div>

<?php echo Form::open(NULL, array('class' => 'ajax-validate'))?>

	<fieldset>
		<legend>Metadata</legend>
		<div class="field">
			<?php echo 
				Form::label('title', __('Title'), NULL, $errors).
				Form::input('title', $_POST['title'], NULL, $errors)
			?>
		</div>
		<div class="field">
			<?php echo 
				Form::label('uri', __('URI'), NULL, $errors).
				Form::input('uri', $_POST['uri'], NULL, $errors)
			?>
		</div>
		<div class="field">
			<?php echo 
				Form::label('description', __('Description'), NULL, $errors).
				Form::input('description', $_POST['description'], NULL, $errors)
			?>
		</div>
	</fieldset>
	
	<fieldset>
		<legend>Categorize</legend>
		<div class="field">
			<?php echo 
				Form::label('parent_id', __('Parent page'), NULL, $errors, 'admin/messages/label_error').
				Form::select('parent_id', $pages, ($_POST['parent_id'] ? $_POST['parent_id'] : $parent_id), NULL, $errors)
			?>
		</div>
		
	</fieldset>
	
	<fieldset>
		<legend>Publishing</legend>
		<div class="field datepicker-wrapper clear">
			<div class="clear">
				<?php echo 
					Form::label('visible_from', __('Visible from'), NULL, $errors);
				?>
			</div>
			<div>
				<?php echo
					Form::input('visible_from', $_POST['visible_from'], array('class' => 'datepicker'), $errors);
				?>
			</div>
		</div>
		<div class="field datepicker-wrapper clear">
			<div class="clear">
				<?php echo 
					Form::label('visible_to', __('Visible to'), NULL, $errors)
				?>
			</div>
			<div>
				<?php echo 
					Form::input('visible_to', $_POST['visible_to'], array('class' => 'datepicker'), $errors);
				?>
			</div>
		</div>
	</fieldset>

	<fieldset>
		<legend>Content</legend>
		<div class="field">
			<?php echo 
				Form::label('body', __('Body content'), NULL, $errors),
				Form::textarea('body', $_POST['body'], array('class' => 'wysiwyg'), TRUE, $errors)
			?>
		</div>		
	</fieldset>
	<?php echo Form::button('save', 'Save', array('type' => 'submit', 'class' => 'ui-button save'))?>
	
	<div class="helper-right">
	<?php echo Form::button('preview', 'Preview', array('type' => 'submit', 'class' => 'ui-button save'))?>	
	<?php echo Form::button('publish', 'Publish', array('type' => 'submit', 'class' => 'ui-button save'))?>
	</div>
	
<?php echo Form::close()?>
