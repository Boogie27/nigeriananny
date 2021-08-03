<?php


function url($string)
{
    if($string)
    {
        return SITE_URL.$string;
    }
    return false;
}




function asset($string)
{
    if($string)
    {
        return SITE_URL.$string;
    }
    return false;
}





function money($string)
{
   if($string)
   {
        if($string){
            // $sign = "&#8358;";
            $sign = '₦';
            return $sign.number_format($string);
        }
   }
   return '₦0';
}




// ======================================
// GET FILE NAME
// ======================================
function path($string){
    $back_url = $_SERVER['PHP_SELF'];
    $filename = explode('.', basename($back_url));
    if($filename[0] == 'index' && $string == '/')
    {
        return true;
    }
    if($filename[0] == $string)
    {
        return true;
    }
    return false;
}









// =====================================
// GET PAGE TITLE
//======================================
function title(){
    $back_url = $_SERVER['PHP_SELF'];
    $filename = explode('.', basename($back_url));
    
    $main_title = implode(' ', explode('-', $filename[0]));
    if($main_title == 'index')
    {
        $main_title = 'home';
    }
    return $main_title;
}











function dd($string)
{
    var_dump($string);
    die();
}







function back()
{
    $back_url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
   return header("Location: ".$back_url);
}








function image($image, $index = null)
{
    if(!isset($index))
    {
        return $image;
    }
    if($index || $index == 0)
    {
        return $item = explode(',', $image)[$index];
    }
    
}









function response($array = array())
{
    if(count($array))
    {
        return print_r(json_encode($array));
    }
    return false;
}









function star($ratings, $count)
{
   if($ratings && is_numeric($ratings))
   {
       if(is_numeric($count))
       {
            $total = ceil($ratings / $count);
            for($i = 0; $i < 5; $i++)
            {
                if($i < $total)
                {
                    echo '<li class="list-inline-item"><a href="#"><i class="fa fa-star text-warning"></i></a></li>';
                }else{
                    echo '<li class="list-inline-item"><a href="#"><i class="fa fa-star text-secondary"></i></a></li>';
                }
            }
       }
   }else{
    for($i = 0; $i < 5; $i++)
    {
        echo '<li class="list-inline-item"><a href="#"><i class="fa fa-star text-secondary"></i></a></li>';
    }
   }
   return false;
}







function stars($ratings, $count)
{
   if($ratings && is_numeric($ratings))
   {
       if(is_numeric($count))
       {
            $total = ceil($ratings / $count);
            for($i = 0; $i < 5; $i++)
            {
                if($i < $total)
                {
                    echo '<i class="fa fa-star text-warning"></i>';
                }else{
                    echo '<i class="fa fa-star text-secondary"></i>';
                }
            }
       }
   }else{
    for($i = 0; $i < 5; $i++)
    {
        echo '<i class="fa fa-star text-secondary"></i>';
    }
   }
   return false;
}










function employee_star($ratings)
{
    if($ratings && is_numeric($ratings))
    {
        for($i = 0; $i < 5; $i++)
        {
            if($i < $ratings)
            {
                echo '<i class="fa fa-star text-warning review_star_icon"></i>';
            }else{
                echo '<i class="fa fa-star text-secondary review_star_icon"></i>';
            }
        }
    }
    return false;
}










function ratings($ratings)
{
    if($ratings && is_numeric($ratings))
    {
        for($i = 0; $i < 5; $i++)
        {
            if($i < $ratings)
            {
                echo '<i class="fa fa-star text-warning"></i>';
            }else{
                echo '<i class="fa fa-star text-secondary"></i>';
            }
        }
    }
    return false;
}










function user_star($ratings)
{
    if($ratings && is_numeric($ratings))
    {
        for($i = 0; $i < 5; $i++)
        {
            if($i < $ratings)
            {
                echo '<li class="list-inline-item"><a href="#"><i class="fa fa-star text-warning review_star_icon"></i></a></li>';
            }else{
                echo '<li class="list-inline-item"><a href="#"><i class="fa fa-star text-secondary review_star_icon"></i></a></li>';
            }
        }
    }
    return false;
}







function csrf_token()
{
    return Token::generate();
}





function page_expired()
{
    $page_expired = '<!DOCTYPE html>
                        <html lang="en">
                        <head>
                            <meta charset="UTF-8">
                            <meta name="viewport" content="width=device-width, initial-scale=1.0">
                            <meta http-equiv="X-UA-Compatible" content="ie=edge">
                            <title>page expired</title>
                            <style>
                                *{
                                    padding: 0px;
                                    margin: 0px;
                                    color: #555;
                                }
                                body{
                                    font-family: "poppins" sans-serif;
                                }
                                .container{
                                    display: table;
                                    width: 100%;
                                    height: 100vh;
                                    text-align: center;
                                }
                                .inner{
                                    font-size: 17px;
                                    font-weight: 600;
                                    display: table-cell;
                                    vertical-align: middle;
                                }
                            </style>
                        </head>
                        <body>
                                <div class="container">
                                    <p class="inner">419 | page expired</p>
                                </div>
                        </body>
                        </html>';
        return $page_expired;
}






function current_url()
{
    return 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
}










function old($name)
{
    if(isset($_SESSION['old']) && isset($_SESSION['old'][$name]))
    {
        return $_SESSION['old'][$name];
    }
    return false;
}











function shipping_fee($city_ids = null)
{
    $city_id = $city_ids ? $city_ids : 76581;

    $connection = new DB();
    $shipping_fee = $connection->select('tbl_shipping_fee')->where('city_id', $city_id)->where('active', 1)->first();
    if($shipping_fee)
    {
        return $shipping_fee->shipping_fee;
    }
    return '00.0';
}











function view($string)
{
    if($string)
    {
        if($string == '/')
        {
            $view = SITE_URL;
        }else{
            $view = SITE_URL.$string;
        }
        return header("Location:".$view);
    }
    return false;
}











function current_page()
{
    return '/'.basename($_SERVER['REQUEST_URI']);
}











function saved_jobs($worker_id)
{
    if(Auth_employer::is_loggedin() && $worker_id)
    {
        $connection = new DB();
        $employer_id = Auth_employer::employer('id');
        $saved_job = $connection->select('save_jobs')->where('s_employer_id', $employer_id)->where('s_worker_id', $worker_id)->first();
        if($saved_job)
        {
            return true;
        }
    }
    return false;
}








// =============================================
// GET FLAGGED EMPLOYEE
// =============================================
function flagged_employee($employee_id = null)
{
    if($employee_id)
    {
        $connection = new DB();
        $reports = $connection->select('employer_reports')->where('employee_rid', $employee_id)->get();
        if(count($reports))
        {
            return count($reports);
        }
    }
    return false;
}







function settings()
{
    $connection = new DB();
    $settings = $connection->select('settings')->where('id', 1)->first();
    if($settings)
    {
        return $settings;
    }
    return false;
}






function unapproved_members($member_type)
{
    $connection = new DB();
    if($member_type && $member_type == 'employee')
    {
        $members = $connection->select('employee')->where('e_approved', 0)->get();
        if(count($members))
        {
            return count($members);
        }
    }
    if($member_type && $member_type == 'employer')
    {
        $members = $connection->select('employers')->where('employer_approved', 0)->get();
        if(count($members))
        {
            return count($members);
        }
    }
    return false;
}





// ************** GET NEWS LETTER PAGE **************//
function get_news_letter_page($logo, $app_name, $address, $header, $body)
{
    $news_letters = '';
    $news_letters .= '<!DOCTYPE html>
                    <html lang="en">
                    <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <meta http-equiv="X-UA-Compatible" content="ie=edge">
                        <style>
                            *{
                                padding: 0px;
                                margin: 0px;
                            }
                            .content{
                                padding: 30px;
                                background-color: rgb(240, 240, 240);
                            }
                            .news-header{
                                text-align: center;
                            }
                            .container{
                                width: 80%;
                                margin: 0 auto;
                                padding: 30px 20px;
                                background-color: #fff;
                            }
                            .news-header h4{
                                color: #333333;
                                margin-top: 10px;
                                font-family: Arial,Helvetica Neue,Helvetica,sans-serif;
                                font-size: 30px;
                            }
                            h4, h3, h2, h1, h5, h6, p, li{
                                color: #333333;
                                margin: 0px;
                                font-family: Arial,Helvetica Neue,Helvetica,sans-serif;
                            }
                            .content-header p{
                                margin-top: 20px;
                                text-align: center;
                            }
                            p.content-body{
                                color: #555;
                                margin: 0 auto;
                                margin-top: 20px;
                                font-size: 18px;
                            }
                            .footer{
                                padding: 50px 0px;
                                text-align: center;
                            }
                            .footer ul{
                                padding:0px;
                                margin: 0px;
                                list-style: none;
                            }
                            .footer ul li{
                                
                            }
                            .footer-header{
                                font-size: 20px;
                            }
                            .anchor{
                                float: right;
                                color: blue;
                                text-decoration: none;
                            }
                            @media only screen and (max-width: 767px){
                                .container{
                                    width: 90%;
                                    padding: 30px 10px;
                                }
                                .content{
                                    padding: 20px 0px;
                                    width: 95%;
                                    margin: 0 auto;
                                    background-color: rgb(240, 240, 240);
                                }
                            }
                        </style>
                    </head>
                    <body>
                        <div class="content">
                            <div class="container">
                                <div class="news-header">
                                        <img src="'.asset($logo).'" alt="'.$app_name.'">
                                        <h4>'.$app_name.'</h4>
                                </div>
                                <div class="news-body">
                                        <div class="content-header"><p>'.$header.'</p></div>
                                        <p class="content-body">'.$body.'</p>
                                        <div class="footer">
                                            <ul>
                                                <li class="footer-header">'.$app_name.'</li>
                                                <li>'.$address.'</li>
                                                <li><a href="'.url('/unsubscribe-newsletter').'">Unsubscribe newsletter</a></li>
                                            </ul>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </body>
                    </html>';

        return $news_letters;
}






function mail_view($logo, $app_name, $address, $header, $body)
{
    $mail = '';
    $mail .= '<!DOCTYPE html>
                    <html lang="en">
                    <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <meta http-equiv="X-UA-Compatible" content="ie=edge">
                        <style>
                            *{
                                padding: 0px;
                                margin: 0px;
                            }
                            .content{
                                padding: 30px;
                                background-color: rgb(240, 240, 240);
                            }
                            .news-header{
                                text-align: center;
                            }
                            .container{
                                width: 80%;
                                margin: 0 auto;
                                padding: 30px 20px;
                                background-color: #fff;
                            }
                            .news-header h4{
                                color: #333333;
                                margin-top: 10px;
                                font-family: Arial,Helvetica Neue,Helvetica,sans-serif;
                                font-size: 30px;
                            }
                            h4, h3, h2, h1, h5, h6, p, li{
                                color: #333333;
                                margin: 0px;
                                font-family: Arial,Helvetica Neue,Helvetica,sans-serif;
                            }
                            .content-header p{
                                margin-top: 20px;
                                text-align: center;
                            }
                            p.content-body{
                                color: #555;
                                margin: 0 auto;
                                margin-top: 20px;
                                font-size: 15px;
                                text-align: center
                            }
                            .footer{
                                padding: 50px 0px;
                                text-align: center;
                            }
                            .footer ul{
                                padding:0px;
                                margin: 0px;
                                list-style: none;
                            }
                            .footer ul li{
                                
                            }
                            .footer-header{
                                font-size: 20px;
                            }
                            .anchor{
                                float: right;
                                color: blue;
                                text-decoration: none;
                            }
                            @media only screen and (max-width: 767px){
                                .container{
                                    width: 90%;
                                    padding: 30px 10px;
                                }
                                .content{
                                    padding: 20px 0px;
                                    width: 95%;
                                    margin: 0 auto;
                                    background-color: rgb(240, 240, 240);
                                }
                            }
                        </style>
                    </head>
                    <body>
                        <div class="content">
                            <div class="container">
                                <div class="news-header">
                                        <img src="'.asset($logo).'" alt="'.$app_name.'">
                                        <h4>'.$app_name.'</h4>
                                </div>
                                <div class="news-body">
                                        <div class="content-header"><p>'.$header.'</p></div>
                                        <p class="content-body">'.$body.'</p>
                                        <div class="footer">
                                            <ul>
                                                <li class="footer-header">'.$app_name.'</li>
                                                <li>'.$address.'</li>
                                            </ul>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </body>
                    </html>';

        return $mail;
}