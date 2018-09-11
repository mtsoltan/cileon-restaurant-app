  <div class="input-field">
      <input id="<?= $name ?>" name="<?= $name ?>"
      class="validate<?= isset($properties['class']) ? ' ' . $properties['class'] : '' ?>"
      <?php
      foreach ($properties as $key => $property) {
        if ($key != 'class') {
          if ($property !== true)
            echo $key . '="' . $property . '" ';
          else
            echo $key . ' ';
        }
      }
      echo !is_null($this->input->post($name)) ? 'value="' . $this->input->post($name) . '"' : ''
      ?>>
      <label for="<?= $name ?>"><?= $this->lang->line($langline) ?></label>
  </div>