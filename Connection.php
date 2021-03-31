<?php
ob_start();

require_once('vendor/autoload.php');

spl_autoload_register(function($class){
    require_once 'classes/'.$class.'.php';
});

session_start(); // starts app session

error_reporting(E_ALL);

ini_set('display_errors', 1);

defined('SITE_URL') ? NULL : define('SITE_URL', "http://localhost/JOB/nigeriananny");

$GLOBALS['mysql'] = array(
    'host' => 'localhost',
    'username' => 'root',
    'password' => '',
    'db' => 'nanny'
);




include('helpers/helpers.php');



$connection = DB::instantiate(); //use this instantiated class to make query to database;

$errors = Session::flash('errors'); //get field errors from session
     




// ==========================================
// SHOP REMEMBER ME
// ==========================================
if(Cookie::exists('remember_me') && !Session::has('user'))
{
    if(Auth::remember_login(Cookie::get('remember_me')))
    {
        return view('/shop');
    }
}



// ==========================================
// EMPLOYEE REMEMBER ME
// ==========================================
if(Cookie::exists('employee_remember_me') && !Session::has('user'))
{
    if(Auth_employee::remember_login(Cookie::get('employee_remember_me')))
    {
        return view('/');
    }
}




// ==========================================
// EMPLOYER REMEMBER ME
// ==========================================
if(Cookie::exists('employee_remember_me') && !Session::has('user'))
{
    if(Auth_employer::remember_login(Cookie::get('employee_remember_me')))
    {
        return view('/');
    }
}




// FACEBOOK APP ID = 268608751649972
// FACEBOOK SECRETE KEY = 2b29b58cddf417552c5abe64c7373000


?>
  