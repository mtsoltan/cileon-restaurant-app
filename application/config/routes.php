<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|  example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|  https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|  $route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|  $route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|  $route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:  my-controller/index  -> my_controller/index
|    my-controller/my-method  -> my_controller/my_method
*/

// ============== //
// Public Routes  //
// ============== //
$route['login'][ 'GET'] = 'UserController/login';
$route['login']['POST'] = 'UserController/handleLogin';
$route['reset']['POST'] = 'UserController/handleReset';
// TODO: Add forgot password and reset email and the stuff...

// ============== //
// Private Routes //
// ============== //

// General Routes
$route['logout']     [ 'GET'] = 'UserController/logout';
$route['dashboard']  [ 'GET'] = 'IndexController/dashboard';
$route['admin/stats'][ 'GET'] = 'AdminController/stats'; // TODO: not done

// Product Routes
$route['products']             [ 'GET'] = 'ProductController/index';
$route['product/delete/(:num)']['POST'] = 'ProductController/delete/$1';
$route['product/edit/(:num)']  [ 'GET'] = 'ProductController/edit/$1';
$route['product/edit/(:num)']  ['POST'] = 'ProductController/handleEdit/$1';
$route['product/add']          [ 'GET'] = 'ProductController/add';
$route['product/add']          ['POST'] = 'ProductController/handleAdd';
$route['product/(:num)']       [ 'GET'] = 'ProductController/view/$1'; // TODO: not done // USES GROUP SPECIFIED ASSIGNED_IDS
$route['product/api']          [ 'GET'] = 'ProductController/xhrGet';
$route['product/api']          ['POST'] = 'ProductController/xhrGet';

// Orders Routes
$route['orders((/pending|/all)?)']['GET'] = 'OrderController/index';
$route['order/edit/(:num)']  [ 'GET'] = 'OrderController/edit/$1';
$route['order/edit/(:num)']  ['POST'] = 'OrderController/handleEdit/$1'; // TODO: not done
$route['order/add']          [ 'GET'] = 'OrderController/add';
$route['order/add']          ['POST'] = 'OrderController/handleAdd';
$route['order/(:num)']       [ 'GET'] = 'OrderController/view/$1'; // TODO: not done

// TODO: Financial Calculations, order printing

// Customers Routes
$route['customers']             [ 'GET'] = 'CustomerController/index';
$route['customer/delete/(:num)']['POST'] = 'CustomerController/delete/$1';
$route['customer/edit/(:num)']  [ 'GET'] = 'CustomerController/edit/$1';
$route['customer/edit/(:num)']  ['POST'] = 'CustomerController/handleEdit/$1';
$route['customer/add']          [ 'GET'] = 'CustomerController/add';
$route['customer/add']          ['POST'] = 'CustomerController/handleAdd';
$route['customer/(:num)']       [ 'GET'] = 'CustomerController/view/$1'; // TODO: not done
$route['customer/api']          [ 'GET'] = 'CustomerController/xhrGet';
$route['customer/api']          ['POST'] = 'CustomerController/xhrGet';

// Group Routes
$route['settings']           [ 'GET'] = 'GroupController/editOwn'; // TODO: not done
$route['settings']           ['POST'] = 'GroupController/handleEditOwn'; // TODO: not done
$route['groups']             [ 'GET'] = 'GroupController/index';
$route['group/delete/(:num)']['POST'] = 'GroupController/delete/$1';
$route['group/edit/(:num)']  [ 'GET'] = 'GroupController/edit/$1';
$route['group/edit/(:num)']  ['POST'] = 'GroupController/handleEdit/$1';
$route['group/add']          [ 'GET'] = 'GroupController/add';
$route['group/add']          ['POST'] = 'GroupController/handleAdd';
$route['group/(:num)']       [ 'GET'] = 'GroupController/view/$1'; // TODO: not done

// User Routes
$route['users']             [ 'GET'] = 'UserController/index';
$route['user/delete/(:num)']['POST'] = 'UserController/delete/$1'; // TODO: not done
$route['user/edit/(:num)']  [ 'GET'] = 'UserController/edit/$1'; // TODO: not done
$route['user/edit/(:num)']  ['POST'] = 'UserController/handleEdit/$1'; // TODO: not done
$route['user/add']          [ 'GET'] = 'UserController/add';
$route['user/add']          ['POST'] = 'UserController/handleAdd';
$route['user/(:num)']       [ 'GET'] = 'UserController/view/$1'; // TODO: not done

// Root Routes
$route['root/sql']    [ 'GET'] = 'RootController/sql';
$route['root/sql']    ['POST'] = 'RootController/handleSql';
$route['root/migrate'][ 'GET'] = 'RootController/migrate';

$route['default_controller'] = 'IndexController/index';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
