<div class="input-field <?= isset($properties['parent-class']) ? $properties['parent-class'] : '' ?>">
    <textarea id="<?= $name ?>" name="<?= $name ?>"
    class="materialize-textarea<?= isset($properties['class']) ? ' ' . $properties['class'] : '' ?>"
    <?php
    foreach ($properties as $key => $property) {
      if ($key != 'class' && $key != 'value' && $key != 'parent-class') {
        if ($property !== true)
          echo $key . '="' . $property . '" ';
        else
          echo $key . ' ';
      }
    }?>><?= $this->input->post($name) ? htmlspecialchars($this->input->post($name)) : '' ?></textarea>
    <label for="<?= $name ?>"><?= $this->lang->line($langline) ?></label>
</div>