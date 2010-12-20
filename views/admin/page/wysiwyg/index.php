<?php echo Form::open()?>
	<fieldset>
		
		<div class="field">
			<?php echo 
				Form::textarea('body', '<p>Some HTML <em>perhaps</em>?</p>', array('class' => 'wysiwyg'), TRUE)
			?>	
		</div>
	</fieldset>
<?php echo Form::close()?>

<br />

<button class="button default" id="open-dialog">
	Open Dialog
</button>

<div id="dialog-content" class="ui-helper-hidden" title="Media module coming soon!">
	<p>This is some content that will go into the dialog.</p> 
</div>

<script type="text/javascript">
	(function($){
		
		$('#open-dialog').click(function(){
			$( "#dialog-content" ).dialog({
				height: 140,
				modal: true
			});
			
		})
	})(this.jQuery);
</script>