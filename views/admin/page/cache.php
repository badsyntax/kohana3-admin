<div class="action-bar clear">
        <?php echo HTML::anchor('admin/cache/purge', 'Purge cache', array('id' => 'purge-cache', 'class' => 'button small helper-right'))?>
        <script type="text/javascript">
        (function($){
                $('#purge-cache').click(function(){

                        return confirm('<?php echo __('Are you sure you want to purge the cache? All cache entries will be deleted!')?>');
                });
        })(this.jQuery);
        </script>

        <h1>Cache</h1>
</div>

<h2>Application cache</h2>
<div>
	<strong>Cache directory:</strong>
	<?php echo $cache_dir?>
</div>
<div>
	<strong>Total size:</strong>
	<?php echo $total_size?>
</div>
<div>
	<strong>Total files:</strong>
	<?php echo $total_files?>
</div>
