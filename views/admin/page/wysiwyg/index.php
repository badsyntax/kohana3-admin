<?php echo Form::open()?>
	<fieldset>		
		<div class="field">
			<?php echo 
				Form::textarea('body', '<p>Some HTML <em>perhaps</em>?</p>', array('class' => 'wysiwyg'), TRUE)
			?>	
		</div>
	</fieldset>
<?php echo Form::close()?>