<div class="col s3 side-nav">
  <div class="infosec">
    <div class="white-text compname">
      <h4><?php if(isset($logged_user_group) && $logged_user_group) echo $logged_user_group->name; else echo $this->lang->line('dev_missing_usergroup'); ?></h4>
      <small><?php if(isset($title)) echo $title; else echo $this->lang->line('dev_missing_title'); ?></small>
    </div>
  </div>
  <div class="firstblock">
    <div class="collection">
      <?php $sidenav_for(); ?>
    </div>
  </div>
</div>
