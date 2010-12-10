<div class="action-bar clear">
        <h1>Activity</h1>
</div>

<ul>
<?php foreach($activities as $activity){?>
	
	<li>
		<?php echo date('d-m-Y', strtotime($activity->date))?>
		<?php echo $activity->type?>
		<?php echo HTML::anchor('admin/users/edit/'.$activity->user->id, $activity->user->username)?>
		<?php echo $activity->text?>
	</li>
	
<?php }?>
</ul>