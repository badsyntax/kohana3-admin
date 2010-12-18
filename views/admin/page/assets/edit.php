<h1>Edit asset</h1>

<?php echo Form::open()?>

	<fieldset>
		<legend>Edit asset</legend>
		
		<div class="field">
			<?php echo
				Form::label('filename', 'Filename', NULL, $errors).
				Form::input('filenmae', $_POST['filename'], NULL, $errors)
			?>
		</div>
		
		<?php echo Form::submit('save', 'Save', array('type' => 'submit', 'class' => 'ui-button save'))?>

<?php echo Form::close()?>