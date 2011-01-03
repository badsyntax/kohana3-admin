<div class="clear">
	<div class="action-bar">
		<div class="action-menu helper-right">
			<button>Actions</button>
			<ul>
				<li><?php echo HTML::anchor('admin/assets/upload', __('Upload assets'))?></li>
				<li><?php echo HTML::anchor('admin/config/group/asset', __('Edit config'), array('id' => 'edit-config'))?></li>
				<li><?php echo HTML::anchor('admin/assets/delete', __('Delete assets'), array('id' => 'delete-assets'))?></li>
			</ul>
		</div>
	</div>	

	<?php echo $breadcrumbs?>
</div>
	
<h1>Assets</h1>

<fieldset style="padding:.6em .8em;margin-top:.5em">
	<div id="page-links">
		<div style="float:right"><?php echo $page_links?></div>
		Showing <?php echo $assets->count()?> of <?php echo $total?> assets
	</div>
</fieldset>

<form id="assets-list">
	<fieldset>	
		<div class="assets-list view-list clear">
		<table>
			<thead>
				<tr>
					<th><input type="checkbox" class="checkbox helper-left" style="margin-right:6px"/> File</th>
					<th>Type</th>
					<th>Size</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($assets as $asset){?>
				<tr>
					<td>
						<a href="<?php echo URL::site('admin/assets/edit/'.$asset->id)?>" class="helper-left" style="background:transparent;padding:0">
						<img src="<?php echo URL::site($asset->image_url(60, 60, TRUE))?>" class="asset-thumb helper-left" />
						</a>
						<input type="checkbox" class="checkbox helper-left" name="asset-<?php echo $asset->id?>" value="<?php echo $asset->id?>" id="asset-<?php echo $asset->id?>" style="margin-right:5px;margin-left:5px"/>
						<?php echo HTML::anchor('admin/assets/edit/'.$asset->id, $asset->filename, array(
							'class' => 'asset'
						))?>
					</td>
					<td><a href="#" class="asset-type subtype-<?php echo $asset->mimetype->subtype?> type-<?php echo $asset->mimetype->type?>"><?php echo $asset->mimetype->type?></a></td>
					<td><?php echo Text::bytes($asset->filesize)?></td>
				</tr>
				<?php }?>
			</tbody>
		</table>
		</div>
	</fieldset>
</form>

<fieldset class="last">
	<div id="page-links">
		<div style="float:right"><?php echo $page_links?></div>
		Showing <?php echo $assets->count()?> of <?php echo $total?> assets
	</div>
</fieldset>