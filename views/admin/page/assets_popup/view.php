<fieldset class="clear">
	<legend>Preview</legend>
	<input type="hidden" id="insert_path" value="<?php echo URL::site($asset->image_url(300, 300))?>" />
	<div style="float:left;margin-right:10px;min-height: 130px;min-width:100px;">
		<img src="<?php echo URL::site($asset->image_url(200, 300))?>" style="border:1px solid #ccc;padding:3px;"/>
	</div>
	<div>
		<p>
			<strong>Filename:</strong> <?php echo $asset->filename?>
		</p>
		<p>
			<strong>Mimetype:</strong> <?php echo $asset->mimetype->subtype.'/'.$asset->mimetype->type?> 
		</p>
		<?php if ($asset->is_image()){?>
		<p>
			<strong>Dimensions:</strong> <span class="asset-width"><?php echo $asset->width?></span> x <span class="asset-height"><?php echo $asset->height?></span> px
		</p>
		<?php }?>
		<p>
			<strong>Filesize:</strong> <?php echo Text::bytes($asset->filesize)?>
		</p>
		<p>
			<?php if ($asset->is_image()){?>
				<div class="ui-buttonset helper-left" style="margin-right:10px">
					<?php echo Form::button('insert', 'Insert Asset', array('type' => 'button', 'id' => 'insert-'.$asset->id, 'class' => 'insert-asset ui-button save'))?>
					<button id="action-<?php echo $asset->id?>">Select an action</button>
				</div>
				<ul>
					<li><?php echo HTML::anchor('admin/assets/popup/resize/'.$asset->id, 'Insert resized image', array('class' => 'resize-insert'))?></li>
				</ul>
			<?php } else {?>
				<?php echo Form::button('insert', 'Insert Asset', array('type' => 'button', 'id' => 'insert-'.$asset->id, 'class' => 'insert-asset ui-button save'))?>
			<?php }?>
			<?php echo HTML::anchor('/admin/assets/popup/download/'.$asset->id, 'Download', array('class' => 'ui-button default'))?>
		</p>
	</div>
</fieldset>