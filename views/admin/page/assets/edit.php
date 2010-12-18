<div class="action-bar clear">
	<div class="action-menu helper-right">
		<button>Actions</button>
		<ul>
			<li><?php echo HTML::anchor('admin/assets/download/'.$asset->id, __('Download asset'))?></li>
		</ul>
	</div>
	<h1>Edit asset</h1>
</div>

<?php echo Form::open()?>

	<fieldset>
		<legend>Edit asset</legend>
		
		<img src="<?php echo URL::site('admin/assets/get_asset/'.$asset->id)?>/300/300" />
		<div class="field">
			<?php echo
				Form::label('filename', 'Filename', NULL, $errors).
				Form::input('filenmae', $_POST['filename'], NULL, $errors)
			?>
		</div>
		
		<?php echo Form::button('save', 'Save', array('type' => 'submit', 'class' => 'ui-button save'))?>

<?php echo Form::close()?>