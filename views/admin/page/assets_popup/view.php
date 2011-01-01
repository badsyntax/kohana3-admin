<fieldset class="clear">
	<legend>Preview</legend>
	<input type="hidden" id="insert_path" value="<?php echo URL::site($asset->image_url(300, 300))?>" />
	<a href="<?php echo URL::site('admin/assets/get_asset/'.$asset->id)?>" class="thumb ui-lightbox" style="float:left;margin-right:10px">
		<img src="<?php echo URL::site($asset->image_url(200, 300))?>" style="border:1px solid #ccc;padding:3px;"/>
	</a>
	<div>
		<p>
		<strong>Mimetype:</strong> <?php echo $asset->mimetype->subtype.'/'.$asset->mimetype->type?> 
		</p>			
		<p>
		<?php echo Form::button('insert', 'Insert Asset', array('type' => 'button', 'id' => 'insert-asset', 'class' => 'ui-button save'))?>
		</p>
	</div>
</fieldset>
