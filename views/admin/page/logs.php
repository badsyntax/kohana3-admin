<div class="logs-directories">
<?php echo $directories ?>
</div>

<script type="text/javascript">
(function($){
	$('a[href="#year"], a[href="#month"]').click(function(){

		$(this).next().fadeToggle('fast');

		return false;
	});
})(this.jQuery);
</script>

<?php if ($entries !== NULL){?>
<div class="logs-entries">
	<?php foreach($entries as $entry){?>
		<div><?php echo $entry?></div>
	<?php }?>
</div>
<?php }?>
