
<div class="tabs">
	

	<ul>
		<li><a href="#tabs-1">Thumbails</a></li>
		<li><a href="#tabs-2">Details list</a></li>

	</ul>
	
	<div class="action-bar clear" style="position: absolute;
    right: 0.4em;
    top: 0.4em;">

		<div class="action-menu helper-right">
			<button>Actions</button>
			<ul>
				<li><?php echo HTML::anchor('admin/assets/upload', __('Upload assets'))?></li>
				<li><?php echo HTML::anchor('admin/assets/delete', __('Delete assets'), array('id' => 'delete-assets'))?></li>
			</ul>
		</div>

	</div>
	<div id="tabs-1">
	<h1>Assets</h1>

<form id="assets-list">
<fieldset>	
	<div class="assets-list">
		<ul>
		<?php foreach($assets as $asset){?>
			<li>
				<input type="checkbox" class="checkbox" name="asset-<?php echo $asset->id?>" value="<?php echo $asset->id?>" id="asset-<?php echo $asset->id?>" />
				<a href="<?php echo URL::site('admin/assets/edit/'.$asset->id)?>">
					<img src="<?php echo URL::site($asset->image_url(100, 100, TRUE))?>" />
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
		Showing <?php echo $assets->count()?> of <?php echo $total?> assets |
		<a id="select-all" href="#select-all">Select all</a> |
		<a id="select-none" href="#select-none">Select none</a>
	</div>
</fieldset>

</div>
<div id="tabs-2">
	<p>Morbi tincidunt, dui sit amet facilisis feugiat, odio metus gravida ante, ut pharetra massa metus id nunc. Duis scelerisque molestie turpis. Sed fringilla, massa eget luctus malesuada, metus eros molestie lectus, ut tempus eros massa ut dolor. Aenean aliquet fringilla sem. Suspendisse sed ligula in ligula suscipit aliquam. Praesent in eros vestibulum mi adipiscing adipiscing. Morbi facilisis. Curabitur ornare consequat nunc. Aenean vel metus. Ut posuere viverra nulla. Aliquam erat volutpat. Pellentesque convallis. Maecenas feugiat, tellus pellentesque pretium posuere, felis lorem euismod felis, eu ornare leo nisi vel felis. Mauris consectetur tortor et purus.</p>
</div>

</div>