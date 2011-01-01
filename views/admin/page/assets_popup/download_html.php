<span class="asset-download asset-<?php echo $asset->mimetype->type?>">
	<a href="<?php echo $asset->url(TRUE)?>">Download <?php echo $asset->filename?></a> 
	(<?php echo Text::bytes($asset->filesize)?>)
</span>