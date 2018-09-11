<?php
/*
| Private languages file. For all the dutch lines in the app.
| Check the english strings_lang.php for an idea what those are supposed to look like.
| Untranslated strings exist in this file and should be translated.
*/

defined('BASEPATH') OR exit('No direct script access allowed');

$lang['tax'] = 'BTW';
$lang['class_0'] = 'Root';
$lang['class_1'] = 'Admin';
$lang['class_2'] = 'Restaurant Owner';
$lang['class_3'] = 'Normal User';

$lang['page_title_login']        = 'Login';
$lang['page_title_logout']       = 'Logout';
$lang['page_title_user_reset']   = 'Reset Password';
$lang['page_title_dashboard']    = 'Dashboard';
$lang['page_title_products']     = 'Producten';
$lang['page_title_orders']       = 'Orders';
$lang['page_title_customers']    = 'Klanten';
$lang['page_title_financials']   = 'Financials';
$lang['page_title_users']        = 'Users';
$lang['page_title_user_add']     = 'Add a User';
$lang['page_title_group_add']    = 'Add a Restaurant';
$lang['page_title_product_add']  = 'Add a Product';
$lang['page_title_user_edit']    = 'Edit User: %s';
$lang['page_title_group_edit']   = 'Edit Restaurant: %s';
$lang['page_title_product_edit'] = 'Edit Product: %s';
$lang['page_title_settings']     = 'Instellingen';
$lang['page_title_admin_users']  = 'Manage Site Users';
$lang['page_title_admin_groups'] = 'Manage Site Restaurants';
$lang['page_title_admin_stats']  = 'Site Statistics';
$lang['page_title_root_sql']     = 'Execute SQL';
$lang['page_title_root_migrate'] = 'Migrate Site';

$lang['nav_button_back']         = 'Terug';

$lang['form_select_option']      = 'Kies een optie';
$lang['form_field_username']     = 'Inlognaam'; // Gebruikersnaam?
$lang['form_field_password']     = 'Wachtwoord';
$lang['form_field_password_r']   = 'Repeat Password';
$lang['form_field_recovery_key'] = 'Recovery Key';
$lang['form_field_email']        = 'Email';
$lang['form_field_state_text']   = 'Note (Optional)';
$lang['form_field_class']        = 'User Class';
$lang['form_field_group_id']     = 'Restaurant ID';
$lang['form_field_groupname']    = 'Restaurant Name';
$lang['form_field_productname']  = 'Product Name';
$lang['form_field_productdesc']  = 'Product Description';
$lang['form_field_productprice'] = 'Product Price';
$lang['form_field_producttax']   = 'Product BTW';
$lang['form_field_productid']    = 'Assigned ID Number';
$lang['form_field_product_np']   = 'Purchase Count';
$lang['form_field_producttop']   = 'Is Top Product';
$lang['form_control_login']      = 'Login';
$lang['form_control_send']       = 'Verstuur';
$lang['form_control_action']     = 'Action';
$lang['form_control_edit']       = 'Edit';
$lang['form_control_add']        = 'Add';
$lang['form_control_delete']     = 'Delete';
$lang['form_control_forgot']     = 'Wachtwoord vergeten?';
$lang['form_desc_forgot']        = 'Vul hier uw gekoppelde email in en wij zullen u uw wachtwoord toesturen.'; // TODO: Change this to send password reset link instead.

$lang['form_field_sql']          = 'SQL';
$lang['form_field_sql_ph']       = 'Type your SQL here.';
$lang['form_control_execute']    = 'Execute';

$lang['table_create_ts']         = 'Created On';
$lang['table_users_username']    = 'Username';
$lang['table_users_email']       = 'E-Mail';
$lang['table_users_notes']       = 'Note';
$lang['table_users_last_login']  = 'Last Login';
$lang['table_users_ip']          = 'IP';
$lang['table_users_class']       = 'Class';
$lang['table_users_group_id']    = 'Restaurant';
$lang['table_groups_name']       = 'Restaurant Name';
$lang['table_groups_admin']      = 'Restaurant Owner';
$lang['table_products_name' ]    = 'Product';
$lang['table_products_price']    = 'Prijs';
$lang['table_products_tax']      = 'BTW';
$lang['table_products_np']       = 'Purchase Count';

$lang['error_404']               = '404 Page Not Found';
$lang['error_403']               = '403 Access Denied';

$lang['notice_action_200']       = 'The action you tried to perform was successful.';
$lang['notice_no_such_x_404']    = 'No entity was found with the ID provided.';
$lang['notice_permission_403']   = 'You do not have permission to view this page.';
$lang['notice_groupnull_403']    = 'Please set your root user group before accessing this page.';
$lang['notice_migration_200']    = 'Migration Successful!';
$lang['notice_sql_200']          = 'SQL Executed Successfully!';
$lang['notice_user_reset']       = 'You have to reset your password before you can proceed.';
$lang['notice_no_reset_403']     = 'This user was not required to reset password.';
$lang['notice_recovery_key_403'] = 'The recovery key did not match the one for this user. Please try logging in again and resetting password again.';
$lang['notice_user_add_200']     = 'User added successfully. The one time password is %s. Please give the user this password so that he can log in and change it.';
$lang['notice_login_banned_403'] = 'Your acccount has been disabled. Please contact an administrator if you need to log in.';
$lang['notice_login_group_403']  = 'This restaurant has been disabled. Please contact an administrator if you have further questions.';
$lang['notice_login_wrong_403']  = 'Login Incorrect. Please make sure you entered the username and the password correctly.';
$lang['notice_login_atts_left']  = 'You have %s login attempts left.';
$lang['notice_db_soft_500']      = 'A database error has been encountered';
$lang['notice_product_repeated'] = 'A product with the same name or ID was found before in our database. Please use a different ID or name.';

$lang['dev_missing_title']       = 'ERROR::TITLE_MISSING';
$lang['dev_missing_usergroup']   = 'ERROR::GROUP_MISSING';
