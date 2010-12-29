<h1>Upload assets</h1>

<?php echo Form::open(NULL, array('enctype' => 'multipart/form-data'))?>
	<fieldset class="last">
		<legend>Select file</legend>
		
		<p>Allowed upload types: <?php echo $allowed_upload_type?></p>
		
		<!--
		<?php echo Form::label('asset', 'File', NULL, $errors)?>
		-->
		<div class="field">	
			<?php echo Form::file('asset', NULL, $errors)?>
		</div>
		
		<?php echo Form::button('save', 'Upload', array(
			'type' => 'submit', 
			'class' => 'ui-button save ui-helper-hiddens', 
			'id' => 'upload-asset',
			'style' => 'displsay:none'
		))?>		
	</fieldset>
<?php echo Form::close()?>