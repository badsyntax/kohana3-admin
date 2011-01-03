<div class="ui-paging ui-helper-clearfix">
	
	<?php if ($previous_page !== FALSE): ?>
		<a class="ui-paging-prev ui-state-default ui-corner-left" href="<?php echo HTML::chars($page->url($previous_page)) ?>">
			<span title="<?php echo __('Previous') ?>" class="ui-icon ui-icon-triangle-1-w"></span>
		</a>
	<?php else: ?>
		<a class="ui-paging-prev ui-state-default ui-corner-left" href="<?php echo HTML::chars($page->url($previous_page)) ?>">
			<span title="<?php echo __('Previous') ?>" class="ui-icon ui-icon-triangle-1-w"></span>
		</a>
	<?php endif ?>
	

	<?php for ($i = 1; $i <= $total_pages; $i++): ?>

		<?php if ($i == $current_page): ?>
			<a class="ui-paging-num ui-state-default ui-state-focus" href="<?php echo HTML::chars($page->url($i)) ?>"><span title="page <?php echo $i ?>"><?php echo $i ?></span></a>
		<?php else: ?>
			<a class="ui-paging-num ui-state-default" href="<?php echo HTML::chars($page->url($i)) ?>"><span title="page <?php echo $i ?>"><?php echo $i ?></span></a>
		<?php endif ?>

	<?php endfor ?>
	
	<?php if ($next_page !== FALSE): ?>
		<a class="ui-paging-next ui-state-default ui-corner-right" href="<?php echo HTML::chars($page->url($next_page)) ?>">
			<span title="<?php echo __('Next') ?>" class="ui-icon ui-icon-triangle-1-e"></span>
		</a>
	<?php else: ?>
		<a class="ui-paging-next ui-state-default ui-corner-right" href="<?php echo HTML::chars($page->url($next_page)) ?>">
			<span title="<?php echo __('Next') ?>" class="ui-icon ui-icon-triangle-1-e"></span>
		</a>
	<?php endif ?>
</div>