<div class="action-bar clear">
	<div class="action-menu helper-right">
		<button>Actions</button>
		<ul>
			<li><?php echo HTML::anchor('admin/assets/download/'.$asset->id, __('Download asset'))?></li>
			<li><?php echo HTML::anchor('admin/assets/delete/'.$asset->id, __('Delete asset'))?></li>
		</ul>
	</div>
	<h1>Edit asset</h1>
</div>

<?php echo Form::open(NULL, array('class' => 'assets-edit ajax-validate'))?>

	<fieldset>
		<legend>Preview</legend>
		<a href="<?php echo URL::site('admin/assets/get_asset/'.$asset->id)?>" class="thumb ui-lightbox">
			<img src="<?php echo URL::site('admin/assets/get_asset/'.$asset->id)?>/300/300" />
		</a>
	</fieldset>

	<fieldset>
		<legend>Information</legend>
		<strong>Uploaded by:</strong> <?php echo HTML::anchor('admin/users/view/'.$asset->user->id, $asset->user->username).' on '.$asset->date?> <br />
		<strong>Mimetype:</strong> <?php echo $asset->mimetype->subtype.'/'.$asset->mimetype->type?> <br />
		<strong>Filesize:</strong> <?php echo Text::bytes($asset->filesize)?><br />


	</fieldset>
	<fieldset>
		<legend>Image actions</legend>
	
		<ul>
			<li><?php echo HTML::anchor('admin/assets/rotate/'.$asset->id, 'Rotate 90deg')?></li>
			<li><?php echo HTML::anchor('admin/assets/sharpen/'.$asset->id, 'Sharpen')?></li>
			<li><?php echo HTML::anchor('admin/assets/flip_horizontal/'.$asset->id, 'Flip horizontal')?></li>
			<li><?php echo HTML::anchor('admin/assets/flip_vertical/'.$asset->id, 'Flip vertical')?></li>						
		</ul>
	</fieldset>
	
	<fieldset class="last">
		<legend>Edit asset</legend>
		
		<div class="field">
			<?php echo
				Form::label('filename', 'Filename', NULL, $errors).
				Form::input('filename', $_POST['filename'], NULL, $errors)
			?>
		</div>
		<div class="field">
			<?php echo
				Form::label('title', 'Title', NULL, $errors).
				Form::input('title', $_POST['title'], NULL, $errors)
			?>
		</div>
		<div class="field">
			<?php echo
				Form::label('description', 'Description', NULL, $errors).
				Form::input('description', $_POST['description'], NULL, $errors)
			?>
		</div>
		
		<?php echo Form::button('save', 'Save', array('type' => 'submit', 'class' => 'ui-button save'))?>

<?php echo Form::close()?>