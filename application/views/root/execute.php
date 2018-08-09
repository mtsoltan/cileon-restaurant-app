<div class="row">
	<div class="errors">
		<?php echo validation_errors(); ?>
	</div>
	<?= form_open(current_url()) ?>
		<?= form_textarea([
			'placeholder' => $this->lang->line('form_field_sql_ph'),
			'name' => 'sql',
			'rows' => '3',
			'cols' => '150',
			'value' => set_value('sql'),
		]) ?>
		<?= form_submit('submit', 'Run') ?>
	<?= form_close() ?>
	<?php
	if (isset($result) && $result) {
		var_dump($result->result());
	}
	?>
</div>
