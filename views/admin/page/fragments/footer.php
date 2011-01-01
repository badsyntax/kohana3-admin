<footer>

<?php if (Kohana::$environment === Kohana::DEVELOPMENT){?>
	
	<div class="benchmark"> 
		<a href="#profiler" class="profiler helper-right">
			Profiler
			<span class="ui-icon ui-icon-plus helper-right"></span>
			<span class="ui-icon ui-icon-minus ui-helper-hidden helper-right"></span>
		</a>
		{execution_time} - {memory_usage}
	</div>

	<a name="profiler" id="profiler"></a>
	<div id="profiler-container">
		{profiler}
	</div>
	
<?php } else {?>
	<!-- {execution_time} - {memory_usage} -->
<?php }?>

</footer>

<script type="text/javascript">
(function(){
	Admin.init({
		environment: '<?php echo Kohana::$environment?>',
		paths: <?php echo $paths?>,
		<?php if ($param){?>param: <?php echo $param?>,<?php }?>
		route: {
			controller: '<?php echo Request::instance()->controller?>',
			action: '<?php echo Request::instance()->action?>'
		}
	});
})(this.jQuery);
</script>