<?php include('../Connection.php'); ?>
<?php

if(Admin_auth::is_loggedin())
{
   if(Admin_auth::logout())
   {
       return view('/admin/login');
   }
}