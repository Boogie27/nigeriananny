<?php

class Auth_course{

    public static function user($string = null)
    {
        if(Session::has('course_user'))
        {
            if($string)
            {
                return Session::get('course_user')[$string];
            }
            return Session::get('course_user');
        }
        return false;
    }






    public static function login($email, $remember_me = null)
    {
        if($email)
        {
            $cookie_hash = null;
            $connection = new DB();
            $user = $connection->select('course_users')->where('email', $email)->first();
            if($user)
            {
                $login_user['id'] = $user->id;
                $login_user['email'] = $user->email;
                $login_user['first_name'] = $user->first_name;
                $login_user['last_name'] = $user->last_name;
                $login_user['image'] = $user->image;   
                $login_user['date'] = $user->date;       
                
                if($remember_me)
                {
                    $cookie_expiry = 604800;
                    $cookie_hash = uniqid();

                    if($user->remember_me)
                    {
                        $cookie_hash = $user->remember_me;
                    }
        
                    Cookie::put('course_remember_me', $cookie_hash, $cookie_expiry);
                }else if($user->remember_me){
                    $cookie_hash = $user->remember_me;
                }

                $connection->update('course_users', [
                        'is_active' => 1,
                        'remember_me' => $cookie_hash
                ])->where('id', $user->id)->save();

                if($connection->passed())
                {
                    Session::put('course_user', $login_user);
                    return true;
                }
            }
        }
        return false;
    }










    public static function is_loggedin(){
        if(Session::has('course_user'))
        {
            $user = Session::get('course_user');
            $connection = new DB();
            $loggedIn = $connection->select('course_users')->where('email', $user['email'])->where('id', $user['id'])->where('is_active', 1)->first();
            if($loggedIn)
            {
                return true;
            }
        }
        return false;
    }






    public static function logout()
    {
        if(Session::has('course_user'))
        {
            $user = Session::get('course_user');
            $connection = new DB();
            
            $user = $connection->select('course_users')->where('email', $user['email'])->where('id', $user['id'])->first();
            if($user)
            {
                if(Cookie::exists('course_remember_me'))
                {
                    Cookie::delete('course_remember_me');
                }

                $connection->update('course_users', [
                        'is_active' => 0,
                        'last_login' => date('Y-m-d H:i:s')
                ])->where('id', $user->id)->save();
                Session::delete('course_users');
                return true;
            }
        }
        return false;
    }








    public static function remember_login($remember_me)
    {
        if($remember_me){
            $connection = new DB();
            $user = $connection->select('course_users')->where('remember_me', $remember_me)->first();
            if($user)
            {
                if(self::login($user->email))
                {
                    return true;
                }
            }
        }
        return false;
    }

    
// end
}