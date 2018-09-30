<?php
defined('BASEPATH') or exit('No direct script access allowed');

// The Actual Restaurant Controller Base
class MY_Controller extends CI_Controller {

  /** @var \Entity\User $user */
  protected $user;

  /** @var array */
  protected $flashNow = array();

  /** @var array $topnavItems
   * An array required by $this->loadDefaultVars() to set topnav to true.
   * Unset this in pages that should not have it by running $this->topnavItems = array();
   * Each item consists of an array including the keys:
   *   route      -> Route for this item to point to. Set to # to not point anywhere.
   *   permission -> [OPTIONAL] Set this to the permission required to view this item.
   *   title      -> The lang->line key used to display the text on this item.
   *   active     -> The REGEX that is compared to uri_string() and
   *                 used to validate if this item is currently active.
   *   class      -> [OPTIONAL] Any extra class this item should have. Used for Javascript links.
   */
  protected $topnavItems = array();

  protected $sidenavItems = [
    ['route' => 'dashboard', 'title' => 'page_title_dashboard', 'active' => 'dashboard'],
    ['route' => 'products', 'title' => 'page_title_products', 'permission' => 'product/view', 'active' => 'product.*'],
    ['route' => 'orders', 'title' => 'page_title_orders', 'permission' => 'order/view', 'active' => 'order.*'],
    ['route' => 'customers', 'title' => 'page_title_customers', 'permission' => 'customer/view', 'active' => 'customer.*'],
    ['route' => 'financials', 'title' => 'page_title_financials', 'permission' => 'financial/own', 'active' => 'financials'],
    ['route' => 'users', 'title' => 'page_title_users', 'permission' => 'user/own', 'active' => 'users'],
    ['route' => 'settings', 'title' => 'page_title_settings', 'permission' => 'group/own', 'active' => 'settings'],
    ['route' => 'users', 'title' => 'page_title_admin_users', 'permission' => 'user/view', 'active' => 'users'],
    ['route' => 'groups', 'title' => 'page_title_admin_groups', 'permission' => 'group/view', 'active' => 'groups'],
    ['route' => 'admin/stats', 'title' => 'page_title_admin_stats', 'permission' => 'admin', 'active' => 'admin/stats'],
    ['route' => 'root/sql', 'title' => 'page_title_root_sql', 'permission' => 'root', 'active' => 'root/sql'],
    ['route' => 'logout', 'title' => 'page_title_logout', 'active' => 'logout'],
  ];

  public function __construct()
  {
    parent::__construct();
     $this->load->library('migration'); // Uncomment this line whenever you want to migrate.

    $this->lang->load('strings');
    $this->load->model('User');
    $this->load->model('Group');
    $this->load->helper('string');

    // Get the flash last page set and reset the flash.
    if (!isset($_SESSION['flash_messages'])) {
      $_SESSION['flash_messages'] = array();
    }
    if (count($_SESSION['flash_messages'])) {
      $this->flashNow = array_merge($this->flashNow, $_SESSION['flash_messages']);
      $_SESSION['flash_messages'] = array();
    }

    // session_destroy(); // Uncomment this line to destroy current session completely.

    if (!isset($this->session->user_id)) {
      $this->user = false;
    } else {
      $this->user = $this->User->getById($this->session->user_id);
      if ($this->user) {
        $this->load->vars(['logged_user' => $this->user]);
        $this->load->vars(['logged_user_group' => $this->user->getGroup($this->Group)]);
      }
    }
  }

  /**
   * Always return this function if calling it inside a controller method.
   * Only ever use this function for API calls that do not accept anything other than JSON responses.
   * Use MY_Controller::ajaxRespondWithJson instead for when serving form responses.
   *
   * @param array $array
   * @param number $status
   * @see MY_Controller::ajaxRespondWithJson
   */
  protected function respondWithJson($array, $status = 200)
  {
    $this->output
    ->set_content_type('application/json')
    ->set_status_header($status)
    ->set_output(json_encode($array));
  }

  /**
   * Always return this function if calling it inside a controller method.
   * Responds with JSON if the request is AJAX. Sets flash and redirects if it isn't.
   * Please use this function to respond with JSON on form submission in case JS is disabled.
   * @param array $array The JSON / Flash to be passed.
   * @param string $location The location to be used for redirects.
   * @param number $status The status code for JSON response.
   * @return void
   */
  protected function ajaxRespondWithJson($array, $location, $status = 200)
  {
    if ($this->input->is_ajax_request() || $this->input->post('jsenabled')) {
      if (isset($array['location'])) {
        // Javascript is going to set window.location. We need to delay all the flash going on.
        foreach ($this->form_validation->error_array() as $item)
          $this->addFlash($item, 'error');
          $this->addFlash($array);
      }
      return $this->respondWithJson($array, $status);
    }
    $this->addFlash($array);
    return $this->redirect($location);
  }

  /**
   * Check if user is logged in.
   * Updates last access for the given user.
   * @return void
   */
  protected function isPrivate()
  {
    // Send user back to login screen.
    if(!$this->user) {
      return $this->redirectToHome();
    }

    $this->user->last_access = time();
    $this->user->save();
    return;
  }

  /**
   * Check if user is logged out.
   * @return void
   */
  protected function isPublic()
  {
    if($this->user) {
      return $this->redirectToHome();
    }
    return;
  }

  // Requires a certain permission from the user.
  protected function requiresPermission($permission)
  {
    $this->isPrivate();
    if ($this->user->hasPermission($permission)) return;
    $this->showError(403, 'notice_permission_403');
  }

  // Always return this function.
  protected function showError($status, $langString, $headerString = NULL)
  {
    if (is_null($headerString)) {
      $headerString = 'Error Encountered';
      if ($status == 403) $headerString = $this->lang->line('error_403');
      if ($status == 404) $headerString = $this->lang->line('error_404');
    }
    return show_error($this->lang->line($langString), $status, $headerString);
  }

  /**
   * Calls $this->loadDefaultVars() then adds header and footer and responds with view.
   * Always return this function.
   * @param string $view A string for the relative view path.
   * @param array $vars Variables to be loaded into the view.
   * @param boolean $return Whether to return the view output or leave it to the Output class
   * @see MY_Controller::loadDefaultVars
   * @see CI_Loader::view
   */
  protected function respondWithView($view, $vars = array(), $return = false)
  {
    $this->loadDefaultVars();
    $this->load->vars($vars);
    $rv = $this->load->view('templates/layout.php', ['VIEW' => $view], $return);
    return $return ? $rv : null;
  }

  /**
   * Loads the default style, title, flash, and validation_errors.
   * Sets sidenav to true.
   * To have no sidenav, a different style, a differetn title, please explicitly set them
   * when calling respondWithView.
   * To have no topnavItems when they're set in the Controller, please unset them at the start of the method.
   */
  protected function loadDefaultVars()
  {
    $sidenavFor = function($anchorClass = 'collection-item', $fillable = '%s') {
      foreach ($this->sidenavItems as $item) {
        if (!isset($item['permission']) || $this->user->hasPermission($item['permission'])) {
          echo sprintf($fillable, anchor($item['route'], $this->lang->line($item['title']), [
            'class' => $anchorClass . (preg_match('%' . $item['active'] . '%', uri_string()) ? ' active' : '')
          ]));
        }
      }
    };
    $defaults = [
      // 'cdn' => true, // Uncomment this line to fetch scripts and styles from CDN instead of from server.
      'style' => '2',
      'title' => 'Cileon Rest',
      'sidenav' => true,
      'sidenav_for' => $sidenavFor,
      'flash' => $this->flashNow,
      'validation_errors' => $this->form_validation->error_array(),
    ];
    if (isset($this->topnavItems) && $this->topnavItems && count($this->topnavItems)) {
      $defaults['topnav_items'] = $this->topnavItems;
    }
    if (isset($this->topnavBack) && $this->topnavBack) {
      $defaults['topnav_back'] = $this->topnavBack;
    }
    $this->load->vars($defaults);
  }

  /**
   * Adds a flash message to be displayed by header.php
   * @param string|array $message The message to be displayed.
   * @param string $type One of success, warning, error, and other.
   * @param boolean $now Adds the message to this response if true.
   */
  protected function addFlash($message, $type = 'success', $now = false)
  {
    // JSON compatibility flash input.
    if (is_array($message)) {
      foreach ($message as $key => $item) {
        $this->addFlash($item, $key, $now);
      }
      return;
    }

    // Check that the type is valid.
    if (!in_array($type, ['warning', 'success', 'error', 'other'])) {
      return;
    }

    // Add flash to session.
    if ($now) {
      $this->flashNow[$type][] = $message;
    } else {
      $_SESSION['flash_messages'][$type][] = $message;
    }
  }

  protected function addFlashNow($message, $type = 'success')
  {
    $this->addFlash($message, $type, true);
  }

  protected function redirectToHome()
  {
    if (!$this->user) {
      return $this->redirect(base_url('login'));
    }

    return $this->redirect(base_url('dashboard'));
  }

  protected function redirect($uri, $method = 'auto', $code = null)
  {
    foreach ($this->form_validation->error_array() as $item)
      $this->addFlash($item, 'error');
    return redirect($uri, $method, $code);
  }
}
