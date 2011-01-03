<div class="action-bar clear">
	<div class="action-menu helper-right">
		<button>Actions</button>
		<ul>
			<li> <?php echo HTML::anchor('admin/cache/purge', 'Purge cache', array('id' => 'purge-cache'))?></li>
		</ul>
	</div>
	
	<script type="text/javascript">
	(function($){
	        $('#purge-cache').click(function(e){

				e.preventDefault();
				
				function confirmed(){
					window.location = e.target.href;
				}
			
				VEX.dialog.confirm('<?php echo __('Are you sure you want to purge the cache? All cache entries will be deleted!')?>', confirmed);
				
				return false;
			});
	})(this.jQuery);
	</script>

	<?php echo $breadcrumbs?>
</div>

<h2>Application cache</h2>
<div>
	<strong>Cache directory:</strong>
	<?php echo $cache_dir?>
</div>
<div>
	<strong>Total size:</strong>
	<?php echo $total_size?>
</div>
<div>
	<strong>Total files:</strong>
	<?php echo $total_files?>
</div>
