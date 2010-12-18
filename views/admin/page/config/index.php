<div class="action-bar clear">
	<div class="action-menu helper-right">
		<button>Actions</button>
		<ul>
			<li><?php echo HTML::anchor('admin/config/create', __('Create config'))?></li>
		</ul>
	</div>

	<h1>Config</h1>
</div>

<?php echo Form::open()?>

	<?php foreach($config as $group => $items){?>
	<fieldset>
		<legend><?php echo ucfirst($group)?></legend>
		<?php foreach($items as $item){?>
			<div class="field">
			<?php if ($item->field_type == 'text'){
				echo 
					Form::label("{$group}_{$item->config_key}", $item->label, NULL, $errors) . '<br />' .
					Form::input("{$group}_{$item->config_key}", $_POST[$group.'_'.$item->config_key], NULL, $errors);
			}?>
			</div>
		<?php }?>
		<br />
		<?php echo Form::button('save', 'Save', array('type' => 'submit', 'class' => 'ui-button save'))?>
	</fieldset>
	<br/>
	<?php }?>
	
<?php echo Form::close()?>