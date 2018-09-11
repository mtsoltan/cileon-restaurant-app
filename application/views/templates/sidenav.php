<div class="col s3 side-nav">
  <div class="infosec">
    <div class="white-text compname">
      <h4><?php if(isset($logged_user_group) && $logged_user_group) echo $logged_user_group->name; else echo $this->lang->line('dev_missing_usergroup'); ?></h4>
      <small><?php if(isset($title)) echo $title; else echo $this->lang->line('dev_missing_title'); ?></small>
    </div>
  </div>
  <div class="firstblock">
    <div class="collection">
      <?php
      $sidenav_items = [
        ['route' => 'dashboard'  , 'title' => 'page_title_dashboard'   , 'active' => 'dashboard'],
        ['route' => 'products'   , 'title' => 'page_title_products'    , 'permission' => 'product/view' , 'active' => 'product.*'],
        ['route' => 'orders'     , 'title' => 'page_title_orders'      , 'permission' => 'order/view'   , 'active' => 'order.*'],
        ['route' => 'customers'  , 'title' => 'page_title_customers'   , 'permission' => 'customer/view', 'active' => 'customer.*'],
        ['route' => 'financials' , 'title' => 'page_title_financials'  , 'permission' => 'financial/own', 'active' => 'financials'],
        ['route' => 'users'      , 'title' => 'page_title_users'       , 'permission' => 'user/own'     , 'active' => 'users'],
        ['route' => 'settings'   , 'title' => 'page_title_settings'    , 'permission' => 'group/own'    , 'active' => 'settings'],
        ['route' => 'users'      , 'title' => 'page_title_admin_users' , 'permission' => 'user/view'    , 'active' => 'users'],
        ['route' => 'groups'     , 'title' => 'page_title_admin_groups', 'permission' => 'group/view'   , 'active' => 'groups'],
        ['route' => 'admin/stats', 'title' => 'page_title_admin_stats' , 'permission' => 'admin'        , 'active' => 'admin/stats'],
        ['route' => 'root/sql'   , 'title' => 'page_title_root_sql'    , 'permission' => 'root'         , 'active' => 'root/sql'],
        ['route' => 'logout'     , 'title' => 'page_title_logout'      , 'active' => 'logout'],
      ];
      foreach ($sidenav_items as $item) {
        if (!isset($item['permission']) || $logged_user->hasPermission($item['permission'])) {
          echo anchor($item['route'], $this->lang->line($item['title']), [
            'class' => 'collection-item' . (preg_match('%'.$item['active'].'%', uri_string()) ? ' active' : '')
          ]);
        }
      }
      ?>
    </div>
  </div>
</div>