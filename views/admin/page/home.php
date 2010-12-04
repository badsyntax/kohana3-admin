<h2>Database</h2>

<div><strong>Type:</strong> <?php echo $db_config['type']?></div>
<div><strong>Name:</strong> <?php echo $db_config['connection']['database']?></div>
<div><strong>Size:</strong> <?php echo $db_size?></div>

<hr />

<h2>Enabled Modules</h2>

<ul>
<?php foreach($modules as $name => $path){?>
	<li><?php echo $name?></li>
<?php }?>
</ul>

<hr />

<?php echo HTML::anchor('admin/logs', 'View all logs', array('style' => 'float:right'))?>

<h2>Logs</h2>

<div style="white-space:nowrap;overflow:auto;">
	<?php foreach($logs as $c => $log){?>
		<?php echo $log?><br />
		<?php if ($c == 10) break; ?>
	<?php }?>
</div>
