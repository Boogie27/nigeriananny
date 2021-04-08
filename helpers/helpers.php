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


















// function view($string)
// {
//     if($string)
//     {
//         if($string == '/')
//         {
//             $view = SITE_URL;
//         }else{
//             $url = explode(':', $string); 
//             // if(array_key_exists('http', $url))
//             // {
//             //     $view = $url[count($url) - 1];
//             //     dd($view);
//             // }else{
//             //     $view = SITE_URL.$string;
//             // }
//             $view = $url[count($url) - 1];
//         }
//         return header("Location:".$view);
//     }
//     return false;
// }