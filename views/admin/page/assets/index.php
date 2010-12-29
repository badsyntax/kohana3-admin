<div class="action-bar clear">
	
	<div class="action-menu helper-right">
		<button>Actions</button>
		<ul>
			<li><?php echo HTML::anchor('admin/assets/upload', __('Upload assets'))?></li>
			<li><?php echo HTML::anchor('admin/assets/delete', __('Delete assets'), array('id' => 'delete-assets'))?></li>
		</ul>
	</div>
	<h1>Assets</h1>
</div>

<form id="assets-list">
<fieldset>	
	<div class="assets-list">
		<ul>
		<?php foreach($assets as $asset){?>
			<li>
				<input type="checkbox" class="checkbox" name="asset-<?php echo $asset->id?>" value="<?php echo $asset->id?>" id="asset-<?php echo $asset->id?>" />
				<a href="<?php echo URL::site('admin/assets/edit/'.$asset->id)?>">
					<img src="<?php echo URL::site('admin/assets/get_asset/'.$asset->id.'/100/100/1')?>" width="100" />
				</a>
			</li>
		<?php }?>
		</ul>
	</div>	
</fieldset>
</form>

<fieldset class="last">
	<div id="page-links">
		<div style="float:right"><?php echo $page_links?></div>
		Showing <?php echo $assets->count()?> of <?php echo $total?> assets
	</div>
</fieldset>