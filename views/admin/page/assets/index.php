<div class="action-bar clear">
	<div class="action-menu helper-right">
		<button>Actions</button>
		<ul>
			<li><?php echo HTML::anchor('admin/assets/upload', __('Upload asset'))?></li>
		</ul>
	</div>
	<h1>Assets</h1>
</div>

<fieldset>
	
	<div class="assets-list">
		
		<?php foreach($assets as $asset){?>
			<a href="<?php echo URL::site('admin/assets/edit/'.$asset->id)?>">
				<img src="<?php echo URL::site('admin/assets/get_asset/'.$asset->id.'/100/100/1')?>" width="100" />
			</a>
		<?php }?>
	</div>
	
</fieldset>	
<br />
<fieldset>
	<div id="page-links">
		<div style="float:right"><?php echo $page_links?></div>
		Showing <?php echo $assets->count()?> of <?php echo $total?> assets
	</div>
</fieldset>