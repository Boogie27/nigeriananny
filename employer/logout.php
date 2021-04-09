<?php include('../Connection.php');  ?>

<?php
if(Auth_employer::is_loggedin())
{
    Auth_employer::logout();
    return view('/');
}