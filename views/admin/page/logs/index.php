<div class="action-bar clear">
	<div class="action-menu helper-right">
		<button>Actions</button>
		<ul>
			<li><?php echo HTML::anchor('admin/logs/download/tar', 'Download logs')?></li>
		</ul>
	</div>
	<?php echo $breadcrumbs?>
</div>

<div class="clear">
	<div class="logs-directories">
	<?php echo $directories ?>
	</div>

	<script type="text/javascript">
	(function($){
		$('a[href="#year"], a[href="#month"]').click(function(){

			$(this).next().fadeToggle('fast');

			return false;
		});
	})(this.jQuery);
	</script>

	<div class="logs-entries">
		<?php if ($entries !== NULL){?>
			<p>
				<strong><?php echo count($entries)?></strong> entries
			</p>
			<?php foreach($entries as $entry){?>
				<div id="<?php echo $entry['timestamp']?>">
					<?php echo $entry['date'].' : '.$entry['log']?>
				</div>
			<?php }?>
		<?php } else {?>
			<p>
				There <?php echo $total_files?> log files. Select a directory on the left to navigate the logs.
			</p>
		<?php }?>
	</div>
</div>
