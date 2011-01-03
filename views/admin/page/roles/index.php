<div class="action-bar clear">
	<div class="action-menu helper-right">
		<button>Actions</button>
		<ul>
			<li><?php echo HTML::anchor('admin/roles/add', __('Add role'))?></li>
		</ul>
	</div>
	<?php echo $breadcrumbs?>
</div>

<table>
	<thead>
		<tr>
			<th>ID</th>
			<th>Name</th>
			<th>Description</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($roles as $role){?>
		<tr>
			<td><?php echo $role->id;?></td>
			<td>
				<?php echo HTML::anchor('admin/roles/edit/'.$role->id, $role->name)?>
			</td>
			<td><?php echo $role->description?></td>
		</tr>
		<?php }?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="4">
				<div style="float:right"><?php echo $page_links?></div>
				Showing <?php echo $roles->count()?> of <?php echo $total?> roles
			</td>	
		</tr>		
	</tfoot>   
</table>
