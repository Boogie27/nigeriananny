<?php

class Auth{

    public static function user($string = null)
    {
        if(Session::has('user'))
        {
            if($string)
            {
                return Session::get('user')[$string];
            }
            return Session::get('user');
        }
        return false;
    }




    public static function login($email, $remember_me = null)
    {
        if($email)
        {
            $connection = new DB();
            $user = $connection->select('users')->where('email', $email)->first();
            if($user)
            {
                $login_user['id'] = $user->id;
                $login_user['email'] = $user->email;
                $login_user['first_name'] = $user->first_name;
                $login_user['last_name'] = $user->last_name;
                $login_user['user_image'] = $user->user_image;
                $login_user['gender'] = $user->gender;
                $login_user['birth_date'] = $user->birth_date;
                
                $cookie_hash = null;
                if($remember_me)
                {
                    $cookie_expiry = 604800;
                    $cookie_hash = uniqid();

                    if($user->remember_me)
                    {
                        $cookie_hash = $user->remember_me;
                    }
        
                    Cookie::put('remember_me', $cookie_hash, $cookie_expiry);
                }else if($user->remember_me){
                    $cookie_hash = $user->remember_me;
                }

                $connection->update('users', [
                     'is_active' => 1,
                      'remember_me' => $cookie_hash
                ])->where('id', $user->id)->save();

                $passwordReset = $connection->delete('reset_password')->where('reset_email', $email)->first();
                if($passwordReset)
                {
                    $connection->delete('reset_password')->where('reset_email', $email)->save();
                }


                if($connection->passed())
                {
                    Session::put('user', $login_user);
                    return true;
                }
            }
        }
        return false;
    }


    





    public static function remember_login($remember_me)
    {
        if($remember_me){
            $connection = new DB();
            $user = $connection->select('users')->where('remember_me', $remember_me)->first();
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




    public static function logout()
    {
        if(Session::has('user'))
        {
            $user = Session::get('user');
            $connection = new DB();
            $user = $connection->select('users')->where('email', $user['email'])->where('id', $user['id'])->first();
            if($user)
            {
                if(Cookie::exists('remember_me'))
                {
                    Cookie::delete('remember_me');
                }

                $connection->update('users', [
                            'is_active' => 0,
                    ])->where('id', $user->id)->save();
                    Session::delete('user');
                    return true;
            }
        }
        return false;
    }







    public static function is_loggedin(){
        if(Session::has('user'))
        {
            $user = Session::get('user');
            $connection = new DB();
            $loggedIn = $connection->select('users')->where('email', $user['email'])->where('id', $user['id'])->first();
            if($loggedIn)
            {
                return true;
            }
        }
        return false;
    }







    // end class
}