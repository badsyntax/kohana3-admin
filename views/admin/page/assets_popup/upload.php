<?php echo Form::open('admin/assets/popup#upload', array('enctype' => 'multipart/form-data'))?>

	<fieldset>
		<legend>Select file</legend>
		
		<?php echo Form::label('asset', 'File', NULL, $errors)?>
		<div class="field">	
			<?php echo Form::file($field_name.'[]', array('id' => '', 'class' => "multi", 'maxlength' => $max_upload), $errors)?>
		</div>
		
		<?php echo Form::button('save', 'Upload', array(
			'type' => 'submit', 
			'class' => 'ui-button save ui-helper-hiddens', 
			'id' => 'upload-asset'
		))?>		

	</fieldset>

<?php echo Form::close()?>