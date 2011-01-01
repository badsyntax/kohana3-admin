<div class="assets-list clear">
	<ul>
	<?php foreach($assets as $asset){?>
		<li>
			<a href="<?php echo URL::site('admin/assets/popup/edit/'.$asset->id)?>">
				<img src="<?php echo URL::site($asset->image_url(100, 100, TRUE))?>" />
			</a>
		</li>
	<?php }?>
	</ul>
</div>