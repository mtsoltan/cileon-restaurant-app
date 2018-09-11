<div class="input-field">
  <select id="_<?= $name ?>" name="_<?= $name ?>">
    <option disabled value="0"
    <?= in_array($this->input->post($name), array_map('strval', array_keys($options))) ? '' : 'selected' ?>
    ><?= $this->lang->line('form_select_option') ?></option>
    <?php foreach ($options as $value => $text): ?>
    <option <?= $this->input->post($name) === strval($value) ? 'selected' : '' ?> value="<?= $value ?>"><?= $text ?></option>
    <?php endforeach; ?>
  </select>
  <label for="<?= $name ?>"><?= $this->lang->line($langline) ?></label>
</div>
<input type="hidden" name="<?= $name ?>" id="<?= $name ?>"
  value="<?= in_array($this->input->post($name), array_map('strval', array_keys($options))) ? $this->input->post($name) : '' ?>">