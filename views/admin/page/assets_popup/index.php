<div class="tabs">
	
	<ul>
		<li><a href="#browse">Browse</a></li>
		<li><a href="#upload">Upload</a></li>
		<li><a href="#resize">Resize</a></li>
	</ul>
	<div id="browse">
		<?php echo $browse_html?>

	</div>
	
	<div id="upload">
		<?php echo $upload_html?>
	</div>	
	
	<div id="resize">

		<div id="resize-image-loading">
			Caching image, please wait...
		</div>
		
		<div id="resize-image-contents" class="ui-helper-hidden">
			<p>Use the slider to adjust the image size.</p>
			<div id="resize-slider"></div>		
			<div class="clear">
				<span id="resize-asset-width-mix" class="helper-left">
					0px
				</span>
				<span id="resize-asset-width-max" class="helper-right">
					0px
				</span>
			</div>
			<div id="resize-image-wrapper">
				<div id="resize-image-dimensions">
					<span id="resize-image-dimension-width">0</span> x
					<span id="resize-image-dimension-height">0</span> px
				</div>
			</div>
			<div>
				<button type="button" id="resize-insert" class="ui-button save">
					Insert Resized
				</button>
			
				<button type="button" resize="resize-insert-full" class="ui-button save">
					Insert Full Size
				</butotn>
			</div>
		</div>
	</div>
	
</div>

<fieldset id="page-links" class="last ui-helper-hidden">
	<div style="float:right"><?php echo $page_links?></div>
	Showing <?php echo $assets->count()?> of <?php echo $total?> assets
</fieldset>