<h1>Upload asset</h1>

<?php echo Form::open(NULL, array('enctype' => 'multipart/form-data'))?>

	<fieldset>
		<legend>Select file</legend>
		
		<?php echo Form::label('asset', 'File', NULL, $errors)?>
		<div class="field">	
			<?php echo Form::file('asset', NULL, $errors)?>
		</div>
		
		<?php echo Form::button('save', 'Upload', array('type' => 'submit', 'class' => 'ui-button save'))?>
		
	</fieldset>

<?php echo Form::close()?>