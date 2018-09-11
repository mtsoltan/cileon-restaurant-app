<div class="input-field">
    <textarea id="<?= $name ?>" name="<?= $name ?>"
    class="materialize-textarea<?= isset($properties['class']) ? ' ' . $properties['class'] : '' ?>"
    <?php
    foreach ($properties as $key => $property) {
      if ($key != 'class') {
        if ($property !== true)
          echo $key . '="' . $property . '" ';
        else
          echo $key . ' ';
      }
    }?>><?= $this->input->post($name) ? $this->input->post($name) : '' ?></textarea>
    <label for="<?= $name ?>"><?= $this->lang->line($langline) ?></label>
</div>