<div class="clear" style="display: none;margin-bottom: 1em">

	<div class="helper-right">
		<span style="float:left;padding-top:.3em;padding-right:.5em;font-size:110%">
		View:
		</span>
		<select class="helper-left" style="width: 10em">
			<option>Thumbnails</option>
			<option>List</opton>
		</select>
	</div>
	
	<?php echo Form::open()?>

		<?php echo Form::input('search', 'search', array('class' => 'helper-left', 'style' => 'width: 18em'), $errors)?>
	
		<?php echo Form::button('search', 'Search', array('class' => 'helper-left ui-button default'))?>

	<?php echo Form::close()?>
</div>

<?php
/*
<div class="assets-list view-thumbs clear">
	<ul>
	<?php foreach($assets as $asset){?>
		<li>
			<a href="<?php echo URL::site('admin/assets/popup/view/'.$asset->id)?>" data-id="<?php echo $asset->id?>">
				<img src="<?php echo URL::site($asset->image_url(100, 100, TRUE))?>" />
			</a>
		</li>
	<?php }?>
	</ul>
</div>
*/?>

<div class="assets-list view-list clear">
<table>
	<thead>
		<tr>
			<th>Filename</th>
			<th>Type</th>
			<th>Size</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($assets as $asset){?>
		<tr>
			<td>
				<?php echo HTML::anchor('admin/assets/popup/view/'.$asset->id, $asset->filename, array(
					'class' => 'asset subtype-'.$asset->mimetype->subtype.' type-'.$asset->mimetype->type, 
					'data-id' => $asset->id,
					'data-mimetype' => $asset->mimetype->subtype.'/'.$asset->mimetype->type,
					'data-filename' => $asset->filename
				))?></td>
			<td><?php echo $asset->mimetype->type?></td>
			<td><?php echo Text::bytes($asset->filesize)?></td>
		</tr>
		<?php }?>
	</tbody>
</table>
</div>	