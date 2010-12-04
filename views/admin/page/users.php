<div class="action-bar clear">
	<a href="<?php echo URL::site('admin/roles')?>" class="button add small helper-right">
		<span>Edit roles</span>
	</a>
	<a href="<?php echo URL::site('admin/users/add')?>" class="button add small helper-right">
		<span>Add user</span>
	</a>

	<h1>Users</h1>
</div>

<table class="crud-list">
	<thead>
		<tr>
			<th>
				<input type="checkbox" class="checkbox" />
				<!-- ID -->
			</th>
			<th>Username</th>
			<th>Email</th>
			<th>Role</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($users as $user){?>
		<tr>
			<td>
				<input type="checkbox" class="checkbox" />
				<!-- <?php echo $user->id;?> -->
			</td>
			<td>
				<?php echo HTML::anchor('admin/users/edit/'.$user->id, $user->username)?>
			</td>
			<td><?php echo $user->email?></td>
			<td><?php
				$roles = array();
				foreach($user->roles->find_all() as $role) $roles[] = $role->name;
				echo implode(', ', $roles);
			?></td>
		</tr>
		<?php }?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="5">
				<div style="float:right"><?php echo $page_links?></div>
				Showing <?php echo $users->count()?> of <?php echo $total?> users
			</td>
		</tr>
	</tfoot>
</table>
<script>
(function($){

	$('table.crud-list thead input[type=checkbox]').click(function(){

		$('table.crud-list tbody input[type=checkbox]').attr('checked', this.checked);
	});
})(this.jQuery);
</script>
