<?php

class Auth_employer{

    public static function employer($string = null)
    {
        if(Session::has('employer'))
        {
            if($string)
            {
                return Session::get('employer')[$string];
            }
            return Session::get('employer');
        }
        return false;
    }






    public static function login($email, $remember_me = null)
    {
        if($email)
        {
            $cookie_hash = null;
            $connection = new DB();
            $employer = $connection->select('employers')->where('email', $email)->first();
            if($employer)
            {
                $login_user['id'] = $employer->id;
                $login_user['email'] = $employer->email;
                $login_user['first_name'] = $employer->first_name;
                $login_user['last_name'] = $employer->last_name;
                $login_user['image'] = $employer->image;   
                $login_user['gender'] = $employer->gender;     
                $login_user['birth_date'] = $employer->birth_date;   
                $login_user['address'] = $employer->address;     
                $login_user['city'] = $employer->city;     
                $login_user['state'] = $employer->state;   
                $login_user['country'] = $employer->country; 
                $login_user['e_date_joined'] = $employer->e_date_joined;       
                
                if($remember_me)
                {
                    $cookie_expiry = 604800;
                    $cookie_hash = uniqid();

                    if($employer->e_remember_me)
                    {
                        $cookie_hash = $employer->e_remember_me;
                    }
        
                    Cookie::put('employer_remember_me', $cookie_hash, $cookie_expiry);
                }else if($employer->e_remember_me){
                    $cookie_hash = $employer->e_remember_me;
                }

                $connection->update('employers', [
                        'e_active' => 1,
                        'e_remember_me' => $cookie_hash,
                        'e_last_login' => null
                ])->where('id', $employer->id)->save();

                if($connection->passed())
                {
                    Session::put('employer', $login_user);
                    return true;
                }
            }
        }
        return false;
    }







    public static function is_loggedin(){
        if(Session::has('employer'))
        {
            $employer = Session::get('employer');
            $connection = new DB();
            $loggedIn = $connection->select('employers')->where('email', $employer['email'])->where('id', $employer['id'])->where('e_active', 1)->first();
            if($loggedIn)
            {
                return true;
            }
        }
        return false;
    }










    public static function logout()
    {
        if(Session::has('employer'))
        {
            $user = Session::get('employer');
            $connection = new DB();
            $user = $connection->select('employers')->where('email', $user['email'])->where('id', $user['id'])->first();
            if($user)
            {
                if(Cookie::exists('employer_remember_me'))
                {
                    Cookie::delete('employer_remember_me');
                }

                $connection->update('employers', [
                            'e_active' => 0,
                            'e_last_login' => date('Y-m-d H:i:s')
                    ])->where('id', $user->id)->save();
                    Session::delete('employer');
                    return true;
            }
        }
        return false;
    }






    public static function remember_login($remember_me)
    {
        if($remember_me){
            $connection = new DB();
            $employer = $connection->select('employers')->where('e_remember_me', $remember_me)->first();
            if($employer)
            {
                if(self::login($employer->email))
                {
                    return true;
                }
            }
        }
        return false;
    }

    
// end
}