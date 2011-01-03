<div class="action-bar clear">
	<a href="<?php echo URL::site('admin/users/delete/'.$user->id)?>" id="delete-user" class="button ui-button default helper-right">
		<span>Delete user</span>
	</a>
	<script type="text/javascript">
	(function($){
		$('#delete-user').click(function(){

			return confirm('<?php echo __('Are you sure you want to delete this user?')?>');
		});
	})(this.jQuery);
	</script>

	<?php echo $breadcrumbs?>
</div>

<?php echo Form::open()?>
	<fieldset>
		<legend>Edit user</legend>
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
	</fieldset>
	<fieldset>
		<legend>Roles</legend>
		<div class="field">
			<?php foreach($roles as $role){?>
			<div class="checkbox">
				<?php echo 
					Form::checkbox('roles[]', $role->id, in_array($role, $user_roles), array('id' => 'role-'.$role->id)),
					Form::label('role-'.$role->id, $role->name)
				?>
			</div>
			<?php }?>
		</div>
	</fieldset>
	<fieldset>
		<legend>Groups</legend>
		<div class="field">
			<?php echo
				Form::label('groups', __('Groups'))
			?>
			<?php foreach($groups as $group){?>
			<div class="checkbox">
				<?php echo 
					Form::checkbox('groups[]', $group->id, in_array($group, $user_groups), array('id' => 'group-'.$group->id)),
					Form::label('group-'.$group->id, $group->name)
				?>
			</div>
			<?php }?>
		</div>
	</fieldset>
			<?php echo Form::button('save', 'Update', array('class' => 'ui-button save'))?>
<?php echo Form::close()?>
