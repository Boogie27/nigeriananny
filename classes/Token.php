<?php

class Token{


    public $_token;

    public static function generate()
    {
        $token = md5(uniqid());

        $stored_token = Session::put('_token', password_hash($token, PASSWORD_DEFAULT));

        $input = ' <input type="hidden" name="_token" value="'.$stored_token.'">';

        return $input;
    }






    public static function check()
    {
        if(self::exists())
        {
            if(isset($_POST['_token']) && self::get() === $_POST['_token'])
            {
                self::delete();
                return true;
            }
        }
        self::delete();
        return die(page_expired());
    }





    public static function exists()
    {
        if(Session::has('_token'))
        {
            return true;
        }
        return false;
    }






    public static function get()
    {
        if(self::exists())
        {
            return Session::get('_token');
        }
        return false;
    }







    public static function delete()
    {
        if(self::exists())
        {
            Session::delete('_token');
        }
    }






    // end
}