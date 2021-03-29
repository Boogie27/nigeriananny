<?php include('../Connection.php');  ?>

<?php
if(Auth_employer::is_loggedin())
{
   if(Auth_employer::logout())
   {
        return view('/');
   }
}