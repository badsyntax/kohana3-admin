<ol class="logs-folders">
<?php foreach($logs as $year => $months){?>
<li>
	<?php echo HTML::anchor('#year', str_replace('logs/', '', $year))?>
	<?php if (is_array($months)){?>
	<ol>
		<?php foreach($months as $month => $day){?>
		<li>
			<?php echo HTML::anchor('#month', date('F', mktime(0, 0, 0, (int) str_replace($year.'/', '', $month), 1, date('Y'))))?>
			<?php if (is_array($day)){?>
			<ol>
				<?php foreach($day as $log){?>
				<li>
					<?php echo HTML::anchor('admin/'.$month.'/'.basename($log), preg_replace('/.*?(\d+)'.EXT.'$/', '$1', $log))?>
				</li>
				<?php }?>
			</ol>
			<?php }?>
		</li>
		<?php }?>
	</ol>
	<?php }?>
</li>
<?php }?>
<script type="text/javascript">
(function($){
	$('a[href="#year"], a[href="#month"]').click(function(){

		$(this).next().fadeToggle('fast');

		return false;
	});
})(this.jQuery);
</script>
