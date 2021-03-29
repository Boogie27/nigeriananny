<?php include('../Connection.php');  ?>

<?php

if(Auth::is_loggedin())
{
   if(Auth::logout())
   {
        return view('/shop');
   }
}