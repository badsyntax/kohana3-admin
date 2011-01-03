<div class="action-bar clear">
       <?php echo $breadcrumbs?>
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