<div class="action-bar clear">
	<a href="<?php echo URL::site('admin/users/delete/'.$user->id)?>" id="delete-user" class="button delete small helper-right">
		<span>Delete user</span>
	</a>
	<script type="text/javascript">
	(function($){
		$('#delete-user').click(function(){

			return confirm('<?php echo __('Are you sure you want to delete this user?')?>');
		});
	})(this.jQuery);
	</script>

	<h1>Edit user</h1>
</div>

<?php echo Form::open()?>
	<fieldset>
		
		<div class="field">
			<?php echo 
				Form::label('username', 'Username', NULL, $errors),
				Form::input('username', $_POST['username'], array('class' => 'test'), $errors)
			?>
		</div>
		<div class="field">
			<?php echo 
				Form::label('email', 'Email', NULL, $errors),
				Form::input('email', $_POST['email'], array('type' => 'email'), $errors)
			?>
		</div>
		<div class="field">
			<?php echo
				Form::label('roles', 'Roles')
			?>
			<?php foreach($roles as $role){?>
			<div class="checkbox">
				<?php echo 
					Form::checkbox('roles[]', $role->id, in_array($role, $user_roles), array('id' => 'role-'.$role->id)),
					Form::label('role-'.$role->id, $role->name)
				?>
			</div>
			<?php }?>
		</div>
		<div class="field">
			<?php echo 
				Form::label('password', 'New password', NULL, $errors),
				Form::password('password', NULL, $errors)
			?>
		</div>
		<div class="field">
			<?php echo 
				Form::label('password_confirm', 'Confirm password', NULL, $errors),
				Form::password('password_confirm', NULL, $errors)
			?>
		</div>

		<?php echo Form::submit('update', 'Update', array('class' => 'button'))?>
	</fieldset>
<?php echo Form::close()?>
