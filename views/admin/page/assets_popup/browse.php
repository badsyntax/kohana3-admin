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

<div class="ui-grid assets-list view-list clear">
<table>
	<thead>
		<tr>
			<th>
				<a href="#">
					Filename <span title="sort ascending" class="ui-icon ui-icon-triangle-1-s"></span>
				</a>
			</th>
			<th>
				<a href="#">
					Type <span title="sort ascending" class="ui-icon ui-icon-triangle-1-s"></span>
				</a>
			</th>
			<th>
				<a href="#">
					Size <span title="sort ascending" class="ui-icon ui-icon-triangle-1-s"></span>
				</a>
			</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($assets as $asset){?>
		<tr>
			<td>
				<a 
					href="<?php echo URL::site('admin/assets/popup/view/'.$asset->id)?>" 
					class="asset" 
					data-id="<?php echo $asset->id?>"
					data-mimetype="<?php echo $asset->mimetype->subtype.'/'.$asset->mimetype->type?>"
					data-filename="<?php echo $asset->filename?>">
					
					<img src="<?php echo URL::site($asset->image_url(40, 40, TRUE))?>" class="asset-thumb helper-left" />
					
					<?php echo $asset->filename?>
				</a>
			</td>
			<td><a href="#" class="asset-type subtype-<?php echo $asset->mimetype->subtype?> type-<?php echo $asset->mimetype->type?>"><?php echo $asset->mimetype->type?></a></td>
			<td><?php echo Text::bytes($asset->filesize)?></td>
		</tr>
		<?php }?>
	</tbody>
</table>
</div>	