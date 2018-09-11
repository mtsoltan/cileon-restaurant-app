<div class="section">
  <label for="<?= $name ?>">
    <input id="<?= $name ?>" name="<?= $name ?>" type="checkbox"
    class="filled-in<?= isset($properties['class']) ? ' ' . $properties['class'] : '' ?>"
    <?php
    foreach ($properties as $key => $property) {
      if ($key != 'class') {
        if ($property !== true)
          echo $key . '="' . $property . '" ';
        else
          echo $key . ' ';
      }
    }
    echo !is_null($this->input->post($name)) ? 'checked' : ''
    ?>>
    <span><?= $this->lang->line($langline) ?></span>
  </label>
</div>