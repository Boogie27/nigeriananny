<?php

class Admin_auth{

    public static function admin($string = null)
    {
        if(Session::has('admin'))
        {
            if($string)
            {
                return Session::get('admin')[$string];
            }
            return Session::get('admin');
        }
        return false;
    }





    public static function login($email, $remember_me = null)
    {
        if($email)
        {
            $connection = new DB();
            $admin = $connection->select('admins')->where('email', $email)->first();
            if($admin)
            {
                $login_user['id'] = $admin->id;
                $login_user['email'] = $admin->email;
                $login_user['first_name'] = $admin->first_name;
                $login_user['last_name'] = $admin->last_name;
                $login_user['image'] = $admin->image;   
                $login_user['gender'] = $admin->gender;     
                $login_user['birth_date'] = $admin->birth_date;   
                $login_user['address'] = $admin->address;     
                $login_user['city'] = $admin->city;     
                $login_user['state'] = $admin->state;   
                $login_user['country'] = $admin->country;       

                $connection->update('admins', [
                     'is_active' => 1,
                ])->where('id', $admin->id)->save();

                if($connection->passed())
                {
                    if(Auth_course::is_loggedin())
                    {
                        Session::delete('course_users');
                    }
                    if(Auth_employee::is_loggedin())
                    {
                        Session::delete('employee');
                    }
                    if(Auth_employer::is_loggedin())
                    {
                        Session::delete('employer');
                    }
                    if(Auth::is_loggedin())
                    {
                        Session::delete('user');
                    }
                    Session::delete('old_url');
                    Session::put('admin', $login_user);
                    return true;
                }
            }
        }
        return false;
    }










    
    public static function logout()
    {
        if(Session::has('admin'))
        {
            $admin = Session::get('admin');
            $connection = new DB();
            $admin = $connection->select('admins')->where('email', $admin['email'])->where('id', $admin['id'])->first();
            if($admin)
            {
                $connection->update('users', [
                            'is_active' => 0,
                    ])->where('id', $admin->id)->save();
                    Session::delete('admin');
                    return true;
            }
        }
        return false;
    }







    public static function is_loggedin(){
        if(Session::has('admin'))
        {
            $admin = Session::get('admin');
            $connection = new DB();
            $loggedIn = $connection->select('admins')->where('email', $admin['email'])->where('id', $admin['id'])->first();
            if($loggedIn)
            {
                return true;
            }
        }
        return false;
    }





// end
}