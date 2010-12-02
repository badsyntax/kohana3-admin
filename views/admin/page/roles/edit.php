<div class="action-bar clear">
	<a href="<?php echo URL::site('admin/roles/delete/'.$role->id)?>" id="delete-role" class="button delete small helper-right">
		<span>Delete role</span>
	</a>
	<script type="text/javascript">
	(function($){
		$('#delete-role').click(function(){

			return confirm('<?php echo __('Are you sure you want to delete this role?')?>');
		});
	})(this.jQuery);
	</script>

	<h1>Edit role</h1>
</div>

<?php echo Form::open()?>
	<fieldset>
		
		<div class="field">
			<?php echo 
				Form::label('name', __('Name'), NULL, $errors),
				Form::input('name', $_POST['name'], NULL, $errors)
			?>
		</div>
		<div class="field">
			<?php echo 
				Form::label('description', __('Descripton'), NULL, $errors),
				Form::input('description', $_POST['description'], NULL, $errors)
			?>
		</div>

		<?php echo Form::submit('save', 'Save', array('class' => 'button'))?>
	</fieldset>
<?php echo Form::close()?>
