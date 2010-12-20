<li>
	<?php echo HTML::anchor('admin/groups/edit/'.$group->id, $group->name)?>
	<?php if (count($group->users)){?>
		<ul>
		<?php foreach($group->users->find_all() as $user){?>
			<li><?php echo HTML::anchor('admin/users/edit'.$user->id, $user->username)?></li>
		<?php } ?>
		</ul>
	<?php }?>