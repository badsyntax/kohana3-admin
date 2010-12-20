<h1>Admin dashboard</h1>

<fieldset class="dashboard-database">
	<legend>Database</legend>

	<div><strong>Type:</strong> <?php echo $db_config['type']?></div>
	<div><strong>Name:</strong> <?php echo $db_config['connection']['database']?></div>
	<div><strong>Size:</strong> <?php echo $db_size?></div>
</fieldset>

<fieldset class="dashboard-modules">
	<legend>Enabled Modules</legend>

	<ul>
	<?php foreach($modules as $name => $path){?>
		<li><?php echo $name?></li>
	<?php }?>
	</ul>
</fieldset>

<fieldset class="dashboard-logs last">

	<legend><?php echo HTML::anchor('admin/logs', 'Latest logs')?></legend>

	<ul>
	<?php foreach($logs as $c => $log){?>
		<li>
			<?php echo HTML::anchor('admin/logs/'.$log['path'].'#'.$log['timestamp'], $log['date'].' : '.Text::limit_chars($log['log']))?>
		</li>
		<?php if ($c == 10) break; ?>
	<?php }?>
	</ul>

</fieldset>