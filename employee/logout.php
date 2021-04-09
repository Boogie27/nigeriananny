<?php include('../Connection.php');  ?>

<?php

if(Auth_employee::is_loggedin())
{
    Auth_employee::logout();
    return view('/');
   
}