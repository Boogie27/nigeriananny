<?php

class Session{


        public static function put($name, $string){
            return $_SESSION[$name] = $string;
        }



        public static function has($name){
            return (isset($_SESSION[$name]))? true : false;
        }

        public static function get($name){
                return $_SESSION[$name];
        }

        public static function delete($name){
            if(self::has($name)){
                unset($_SESSION[$name]);
            }
        }


        


        public static function flash($name, $message = null){
            if(self::has($name)){
                $flash = self::get($name);
                self::delete($name);
                return $flash;
            }else{
                self::put($name, $message);
            }
            return false;
        }



        public static function errors($name, $message = null){
            if(self::has($name)){
                $error = self::get($name);
                self::delete($name);
                return $error;
            }else{
                self::put($name, $message);
            }
            return false;
        }


    //   end;
}