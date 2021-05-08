<?php

class Auth_employee{

    public static function employee($string = null)
    {
        if(Session::has('employee'))
        {
            if($string)
            {
                return Session::get('employee')[$string];
            }
            return Session::get('employee');
        }
        return false;
    }






    public static function login($email, $remember_me = null)
    {
        if($email)
        {
            $cookie_hash = null;
            $connection = new DB();
            $employee = $connection->select('employee')->where('email', $email)->first();
            if($employee)
            {
                $login_user['id'] = $employee->e_id;
                $login_user['email'] = $employee->email;
                $login_user['first_name'] = $employee->first_name;
                $login_user['last_name'] = $employee->last_name;
                $login_user['w_image'] = $employee->w_image;   
                $login_user['phone'] = $employee->phone; 
                $login_user['gender'] = $employee->gender;     
                $login_user['dob'] = $employee->dob;   
                $login_user['address'] = $employee->address;     
                $login_user['city'] = $employee->city;     
                $login_user['state'] = $employee->state;   
                $login_user['country'] = $employee->country; 
                $login_user['date_joined'] = $employee->date_joined;       
                
                if($remember_me)
                {
                    $cookie_expiry = 604800;
                    $cookie_hash = uniqid();

                    if($employee->remember_me)
                    {
                        $cookie_hash = $employee->remember_me;
                    }
        
                    Cookie::put('employee_remember_me', $cookie_hash, $cookie_expiry);
                }else if($employee->remember_me){
                    $cookie_hash = $employee->remember_me;
                }

                $connection->update('employee', [
                        'is_active' => 1,
                        'remember_me' => $cookie_hash
                ])->where('e_id', $employee->e_id)->save();

                if($connection->passed())
                {
                    Session::put('employee', $login_user);
                    
                    if(Auth_employer::is_loggedin())
                    {
                        Session::delete('employer');
                    }
                    return true;
                }
            }
        }
        return false;
    }










    public static function is_loggedin(){
        if(Session::has('employee'))
        {
            $employee = Session::get('employee');
            $connection = new DB();
            $loggedIn = $connection->select('employee')->where('email', $employee['email'])->where('e_id', $employee['id'])->where('is_active', 1)->first();
            if($loggedIn)
            {
                return true;
            }
        }
        return false;
    }






    public static function logout()
    {
        if(Session::has('employee'))
        {
            $employee = Session::get('employee');
            $connection = new DB();
            
            $employee = $connection->select('employee')->where('email', $employee['email'])->where('e_id', $employee['id'])->first();
            if($employee)
            {
                if(Cookie::exists('employee_remember_me'))
                {
                    Cookie::delete('employee_remember_me');
                }

                $connection->update('employee', [
                        'is_active' => 0,
                        'last_login' => date('Y-m-d H:i:s')
                ])->where('e_id', $employee->e_id)->save();
               
                if(Auth_employer::is_loggedin())
                {
                    Session::delete('employer');
                }
                Session::delete('employee'); 
                return true;
            }
        }
        return false;
    }








    public static function remember_login($remember_me)
    {
        if($remember_me){
            $connection = new DB();
            $employee = $connection->select('employee')->where('remember_me', $remember_me)->first();
            if($employee)
            {
                if(self::login($employee->email))
                {
                    return true;
                }
            }
        }
        return false;
    }

    
// end
}