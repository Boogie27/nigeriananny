<?php

  class Input{


    public static function json_decode($string){
        return json_decode($string, true);
    }



    public static function money($string){
        if($string){
            $sign = "&#8358;";
            return $sign.number_format($string);
        }
    }


    public static function post($string)
    {
        if($string)
        {
            if(isset($_POST[$string]))
            {
                return true;
            }
        }
        return false;
    }


    public static function get($string = null){
        if($string){
            if(isset($_POST[$string])){
                return $_POST[$string];
            }else if(isset($_GET[$string])){
                return $_GET[$string];
            }
        }
        return false;
    }


    public static function exists($string = "post"){
        $string = strtolower($string);
        switch($string){
            case "post":
                  return (!empty($_POST)) ? true : false;
            break;
            case "get":
                  return (!empty($_GET)) ? true : false;
            break;
            default:
                  return false;
            break;
        }
    }




    public static function all($string = null)
    {
        if(self::post($string))
        {
            return $_POST;
        }
        return $_POST;
    }







    public static function date($date, $param)
    {
        if($date)
        {
            return date($param, strtotime($date));
        }
        return false;
    }


    public static function current_ur()
    {
        return 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    }
    


    // end;
  }