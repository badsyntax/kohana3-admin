<div class="action-bar clear">
	
	<?php echo $breadcrumbs?>
</div>

<?php echo Form::open()?>

	<?php foreach($config as $group => $items){?>
	<fieldset>
		<legend><?php echo ucfirst($group)?></legend>
		<?php foreach($items as $item){?>
			<div class="field">
			<?php if ($item->field_type == 'text'){
				echo 
					Form::label("{$group}-{$item->config_key}", $item->label, NULL, $errors) . '<br />' .
					Form::input("{$group}-{$item->config_key}", $_POST[$group.'-'.$item->config_key], NULL, $errors);
			}?>
			</div>
		<?php }?>
		<br />
		<?php echo Form::button('save', 'Save', array('type' => 'submit', 'class' => 'ui-button save'))?>
	</fieldset>
	<?php }?>
	
<?php echo Form::close()?>