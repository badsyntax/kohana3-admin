<?php echo Form::open()?>
	<fieldset>
		
		<div class="field">
			<?php echo 
				Form::label('parent_id', __('Parent page'), NULL, $errors),
				Form::select('parent_id', $pages, ($_POST['parent_id'] ? $_POST['parent_id'] : $parent_id), NULL, $errors)
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
