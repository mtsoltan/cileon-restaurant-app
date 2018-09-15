  <div class="input-field <?= isset($properties['parent-class']) ? $properties['parent-class'] : '' ?>">
      <input
      <?= strpos($name, '[]') ? 'class="' . explode('[', $name)[0] . '"' : ''?>
      id="<?= $labelFor = strpos($name, '[]') ?  explode('[', $name)[0] . random_string() : $name ?>" name="<?= $name ?>"
      class="validate<?= isset($properties['class']) ? ' ' . $properties['class'] : '' ?>"
      <?php
      foreach ($properties as $key => $property) {
        if ($key != 'class' && $key != 'value' && $key != 'parent-class') {
          if ($property !== true)
            echo $key . '="' . $property . '" ';
          else
            echo $key . ' ';
        }
      }
      echo !is_null($this->input->post($name)) && !strpos($name, '[]') ? 'value="' . htmlspecialchars($this->input->post($name)) . '"' :
      (isset($properties['value']) ?  'value="' . $properties['value'] . '"' : '');
      ?>>
      <label for="<?= $labelFor ?>"
        <?= isset($properties['label-class']) ? 'class="' . $properties['label-class'] . '"' : '' ?>>
          <?= $this->lang->line($langline) ?></label>
  </div>