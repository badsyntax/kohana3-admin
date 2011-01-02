<div class="tabs">
	
	<ul>
		<li><a href="#browse">Browse</a></li>
		<li><a href="#upload">Upload</a></li>
	</ul>
	<div id="browse">
		<?php echo $browse_html?>

	</div>	
	<div id="upload">
		<?php echo $upload_html?>
	</div>	
</div>

<fieldset id="page-links" class="last ui-widget ui-helper-hidden">
	<div style="float:right"><?php echo $page_links?></div>
	Showing <?php echo $assets->count()?> of <?php echo $total?> assets
</fieldset>