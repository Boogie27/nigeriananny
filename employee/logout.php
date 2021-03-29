<?php include('../Connection.php');  ?>

<?php

if(Auth_employee::is_loggedin())
{
   if(Auth_employee::logout())
   {
        return view('/');
   }
}