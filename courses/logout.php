<?php include('../Connection.php');  ?>

<?php

if(Auth_course::is_loggedin())
{
    Auth_course::logout();
    return view('/courses');
   
}