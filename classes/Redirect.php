<?php

class Redirect {


    public static function to($location = null, $message = null){
        if($location){
            if(is_numeric($location)){
                switch($location){
                    case "404":
                          header("HTTP/1.0 404 Not found");
                          include "404.php";
                          exit();
                    break;
                }
            }
            if($message && count($message) == 2){
                  $name = $message[0];
                  $string = $message[1];
                  Session::flash($name, $string);
            }
           return  header("Location: ".$location);
        }
    }





    public static function back($location = null, $message = null)
    {
           if($location)
           {
            $back_url = $location;
           }else{
            $back_url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
           }
           
           Self::to($back_url, $message);
        return true;
    }








    // end
}