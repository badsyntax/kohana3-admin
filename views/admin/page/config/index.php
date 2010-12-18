<h1>Config</h1>

<?php foreach($config as $group => $items){?>
<?php echo Form::open()?>
<fieldset>
	<legend><?php echo ucfirst($group)?></legend>
	<?php foreach($items as $item){?>
		<div>
		<?php if ($item->field_type == 'text'){
			echo 
				Form::label("{$group}_{$item->config_key}", $item->label, NULL, $errors) .
				Form::input("{$group}_{$item->config_key}", $_POST[$group.'_'.$item->config_key], NULL, $errors);
		}?>
		</div>
	<?php }?>
	<br />
	<?php echo Form::submit('save', 'Save', array('class' => 'button'))?>
</fieldset>
<?php echo Form::close()?>
<?php }?>


