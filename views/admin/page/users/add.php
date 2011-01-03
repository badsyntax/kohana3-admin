<?php echo $breadcrumbs?>

<?php echo Form::open()?>
	<fieldset>
		<legend>Account</legend>
		<div class="field">
			<?php echo 
				Form::label('username', __('Username'), NULL, $errors),
				Form::input('username',	$_POST['username'], NULL, $errors)
			?>
		</div>
		<div class="field">
			<?php echo 
				Form::label('email', __('Email'), NULL, $errors),
				Form::input('email', $_POST['email'], array('type' => 'email'), $errors)
			?>
		</div>
		<div class="field">
			<?php echo 
				Form::label('password', __('New password'), NULL, $errors),
				Form::password('password', NULL, NULL, $errors)
			?>
		</div>
		<div class="field">
			<?php echo 
				Form::label('password_confirm', __('Confirm password'), NULL, $errors),
				Form::password('password_confirm', NULL, NULL, $errors)
			?>
		</div>
	</fieldset>
	<fieldset>
		<legend>Roles</legend>
		<div class="field">
			<?php foreach($roles as $role){?>
			<div class="checkbox">
				<?php echo 
					Form::checkbox('roles[]', $role->id, FALSE, array('id' => 'role-'.$role->id)),
					Form::label('role-'.$role->id, $role->name)
				?>
			</div>
			<?php }?>
		</div>
	</fieldset>
	<fieldset>
		<legend>Groups</legend>
		<div class="field">
			<div id="groups-tree">Loading tree...</div>
		</div>
	</fieldset>	
	<?php echo Form::button('save', 'Save', array('class' => 'ui-button save'))?>			
<?php echo Form::close()?>