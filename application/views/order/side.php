<div class="col s3 side-nav">
  <div class="infosec">
    <div class="white-text compname">
      <h4><?php if(isset($logged_user_group) && $logged_user_group) echo $logged_user_group->name; else echo $this->lang->line('dev_missing_usergroup'); ?></h4>
      <small><?php if(isset($title)) echo $title; else echo $this->lang->line('dev_missing_title'); ?></small>
    </div>
  </div>
  <div class="firstblock">
    <?php $this->load->view('templates/fieldinput', [
      'name' => 'customer_id',
      'disabled' => true,
      'langline' => 'form_field_customersrc',
      'properties' => [
        'type' => 'text',
        'required' => true,
      ]
    ]); ?>
    <div class="row">
    <?php $this->load->view('templates/fieldinput', [
      'name' => 'customer_name',
      'langline' => 'form_field_customername',
      'properties' => [
        'disabled' => true,
        'type' => 'text',
        // 'parent-class' => ' col s6',
        'required' => true,
      ]
    ]); ?>
    <?php $this->load->view('templates/fieldinput', [
      'name' => 'customer_contact',
      'langline' => 'form_field_customercont',
      'properties' => [
        'disabled' => true,
        'type' => 'text',
        // 'parent-class' => ' col s6',
        'required' => true,
      ]
    ]); ?>
    </div>
    <?php $this->load->view('templates/fieldtextarea', [
      'name' => 'customer_address',
      'langline' => 'form_field_customeraddr',
      'properties' => [
        'disabled' => true,
        'required' => true,
      ]
    ]); ?>
  </div>
</div>
