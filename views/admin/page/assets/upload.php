<h1>Upload assets</h1>

<?php echo Form::open(NULL, array('enctype' => 'multipart/form-data'))?>
	<fieldset class="last">
		<legend>Select file</legend>
		
		<p>Allowed types: <?php echo $allowed_upload_type?></p>
		<p>Max uploads: <?php echo $max_file_uploads?></p>
		
		
		<?php echo Form::label('asset', '', NULL, $errors)?>	
		
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