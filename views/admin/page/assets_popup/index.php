<div class="tabs">
	
	<ul>
		<li><a href="#browse">Browse</a></li>
		<li><a href="#upload">Upload</a></li>
		<!--<li><a href="#search">Search</a></li>-->
	</ul>
	<div id="browse">
		<?php echo $browse_html?>
	</div>	
	<div id="upload">
		<?php echo $upload_html?>
	</div>
	<!--
	<div id="search">
		<?php echo Form::open('admin/assets/popup#browse')?>

			<fieldset class="last">

				<div class="field">	
					<?php echo 
						Form::label('search-query', __('Query'), NULL, $errors),
						Form::input('search-query', $_POST['search-query'], NULL, $errors)
					?>
				</div>
				<?php echo Form::button('search-submit', 'Search', array('type' => 'submit', 'class' => 'ui-button default'))?>	

			</fieldset>

		<?php echo Form::close()?>
	</div>
	-->
</div>

<fieldset id="page-links" class="last ui-widget">
	<div style="float:right"><?php echo $pagination->render()?></div>
	Showing <?php echo $assets->count()?> of <?php echo $total?> assets
</fieldset>