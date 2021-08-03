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
    











    public static function shop_google_login($email)
    {
        if($email)
        {
            $connection = new DB();
            $check_user = $connection->select('users')->where('email', $email)->first();
            if($check_user && $check_user->is_deactivate)
            {
                return 'deactivated';
            }    

            if(!$check_user)
            {
                $user = $connection->create('users', [
                    'email' => $email,
                ]);
            }

            Session::delete('shop_login');
            $logged_in = Auth::login($email); //login user in

            // CHECK AND REDIRECT IF OLD URL EXIST
            if(Session::has('old_url'))
            {
                $old_url = Session::get('old_url');
                return $old_url;
            }

            return 'login';
        }
    }





    public static function employer_google_login($email)
    {
        if($email)
        {
            $connection = new DB();
            $check_employee = $connection->select('employers')->where('email', $email)->first();
            if($check_employee && $check_employee->e_deactivate)
            {
                return 'deactivated';
            }

            // IF EMAIL DOES NOT EXIST THEN CREATE NEW EMPLOYEE
            if(!$check_employee)
            {
                $employer = $connection->create('employers', [
                    'email' => $email,
                ]);
            }

            
            Session::delete('employer_login');
            $logged_in = Auth_employer::login($email); //login employer in


            // CHECK AND REDIRECT IF OLD URL EXIST
            if(Session::has('old_url'))
            {
                $old_url = Session::get('old_url');
                return $old_url;
            }

            return 'login';
        }
    }







    public static function employee_google_login($email)
    {
      if($email)
      {
        $connection = new DB();
        $check_employee = $connection->select('employee')->where('email', $email)->first();
		if($check_employee && $check_employee->e_is_deactivate)
		{
            return 'deactivated';
		}

		// IF EMAIL DOES NOT EXIST THEN CREATE NEW EMPLOYEE
		if(!$check_employee)
		{
			$employee = $connection->create('employee', [
				'email' => $email,
			]);

			if($employee->passed())
            {
                $employer = $connection->select('employee')->where('email', $email)->first();
                $connection->create('workers', [
                    'employee_id' => $employer->e_id,
                ]);
            }
		}

        Session::delete('employee_login');
        Auth_employee::login($email);

		// CHECK AND REDIRECT IF OLD URL EXIST
		if(Session::has('old_url'))
		{
			$old_url = Session::get('old_url');
			return $old_url;
		}

            return 'login';
        }
    }






    public static function course_google_login($email)
    {
      if($email)
      {
        $connection = new DB();
        $course_user = $connection->select('course_users')->where('email', $email)->first();
		if($course_user && $course_user->is_deactivate)
		{
            return 'deactivated';
		}

		// IF EMAIL DOES NOT EXIST THEN CREATE NEW EMPLOYEE
		if(!$course_user)
		{
			$create = $connection->create('course_users', [
				'email' => $email
			]);
		}

        if(Session::has('course_google_login'))
        {
            Session::delete('course_google_login');
        }

        if(Session::has('course_facebook_login'))
        {
            Session::delete('course_facebook_login');
        }

        Auth_course::login($email);

		// CHECK AND REDIRECT IF OLD URL EXIST
		if(Session::has('old_url'))
		{
			$old_url = Session::get('old_url');
			return $old_url;
		}

            return 'login';
        }
    }








    public static function facebook_employee_login($email)
    {
        if($email)
        {
            return   self::employee_google_login($email);
        }
        return false;
    }





    public static function facebook_employer_login($email)
    {
        if($email)
        {
            return  self::employer_google_login($email);
        }
        return false;
    }

    



    public static function facebook_shop_login($email)
    {
        if($email)
        {
            return  self::shop_google_login($email);
        }
        return false;
    }





    public static function facebook_course_login($email)
    {
        if($email)
        {
            return  self::course_google_login($email);
        }
        return false;
    }
    




    // end;
  }