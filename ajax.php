<?php include('Connection.php');?>
<?php


// =====================================
//    SAVE JOB
// =====================================
if(Input::post('save_job'))
{
    $data = false;
    $expiry = 3153600;
    $employer_id = Auth_employer::employer('id');
    $connection = new DB();
     
    $worker = $connection->select('workers')->where('worker_id', Input::get('worker_id'))->first();
    if(!$worker)
    {
        return response(['error' => ['error' => 'Something went wrong']]);
    }

    $stored_workers = array();
    $stored_workers = ["worker_id" => Input::get('worker_id'), "title" => $worker->job_title];
    if(Cookie::has('saved_worker'))
    {
        $old_save = json_decode(Cookie::get('saved_worker'), true);
        if(array_key_exists(Input::get('worker_id'), $old_save))
        {
            unset($old_save[Input::get('worker_id')]);
            $save_workers = json_encode($old_save);
            Cookie::delete('saved_worker');

            if(Cookie::put('saved_worker', $save_workers, $expiry))
            {
                return response(['unsaved' => 'worker unsaved']);
            }

        }
        Cookie::delete('saved_worker');
    }

    $old_save[Input::get('worker_id')] = $stored_workers;
    $save_workers = json_encode($old_save);
    if(Cookie::put('saved_worker', $save_workers, $expiry))
    {
        $data = true;
    }

    return response(['data' => $data]);
}


// ********** GET ALL SAVED WORKERS ***********//
if(Input::post('get_save_job'))
{
    $data = 0;
    if(Cookie::has('saved_worker'))
    {
        $data = count(json_decode(Cookie::get('saved_worker'), true));
        
    }
    return response(['data' => $data]);
}
    





// =====================================
//     EMPLOYER PAY NOW 
// =====================================
if(Input::post('subscribe_now'))
{
    $data = false;
    if(!Auth_employer::is_loggedin())
    {
        $login = url('/employer/login');
        Session::put('old_url', '/subscription');
        return response(['login' => ['login' => $login]]);
    }

    if(Input::get('sub_id') && Auth_employer::is_loggedin())
    {
        $subscription_pans = $connection->select('subscription_pan')->where('sub_id', Input::get('sub_id'))->where('is_feature', 1)->first();
        if($subscription_pans)
        {
            $subscription['id'] = $subscription_pans->sub_id;
            $subscription['amount'] = $subscription_pans->amount;

            Session::put('page_success', true);
            Session::put('subscription', $subscription);
            $data = true;
        }
    }
    return response(['data' => $data]);
}






// ======================================
// ADD EMPLOYER PROFILE IMAGE
// ======================================
if(Input::post('upload_employer_image'))
{
    $data = true;
    if(!Auth_employer::is_loggedin())
    {
        Session::flash('error', '*Signup or Login to be able to hire a worker');
        return response(['not_login' => ['login' => true]]);
    }

    if(Image::exists('image'))
    {
        $image = new Image();
        $file = Image::files('image');

        $file_name = Image::name('image', 'employer');
        $image->resize_image($file, ['name' => $file_name, 'width' => 200, 'height' => 200, 'size_allowed' => 1000000,'file_destination' => './employer/images/']);
            
        $image_name = '/employer/images/'.$file_name;

        if(!$image->passed())
        {
            return response(['error' => ['image' => $image->error()]]);
        }
        
        $connection = new DB();
        $employer = $connection->select('employers')->where('id', Auth_employer::employer('id'))->first();
        if($employer->e_image)
        {
            Image::delete('./'.$employer->e_image);
        }
        
        $update = $connection->update('employers', [
            'e_image' => $image_name
        ])->where('id', Auth_employer::employer('id'))->save();
       
        if($update)
        {
            $employer_image = $connection->select('employers')->where('id', Auth_employer::employer('id'))->first();
            $data = asset($employer_image->e_image);
        }
    }
    return response(['data' => $data]);
}





// ========================================
// UPDATE EMPLOYEE REVIEW
// ========================================
if(Input::post('edit_employee_review'))
{
    $data = false;
    $validate = new Validator();
    $validation = $validate->validate([
        'star' => 'required',
        'title' => 'required|min:3|max:50',
        'comment' => 'required|min:6|max:200',
    ]);

    if(!$validation->passed())
    {
        return response(['error' => $validation->error()]);
    }

    // update worker rating_count and ratings
    $old_review = $connection->select('employee_reviews')->where('review_id', Input::get('review_id'))->first();
    if($old_review)
    {
        $worker = $connection->select('workers')->where('worker_id', $old_review->r_employee_id)->first();
        $rating = $worker->ratings - $old_review->review_stars;
        $new_rating = $rating + Input::get('star');

        $connection->update('workers', [
                    'ratings' => $new_rating,
                ])->where('worker_id', $old_review->r_employee_id)->save();
        
        $date = date('Y-m-d H:i:s');
        $update = $connection->update('employee_reviews', [
                'title' => Input::get('title'),
                'comment' => Input::get('comment'),
                'review_stars' => Input::get('star'),
                'review_date' => $date
            ])->where('review_id', Input::get('review_id'))->save();

                
        if($update)
        {
            $data = true;
        }
    }    
    
    return response(['data' => $data]);
}










// =======================================
//   DELETE REVIEW
// =======================================
if(Input::post('delete_employee_review'))
{
    $data = false;
    $old_review = $connection->select('employee_reviews')->where('review_id', Input::get('review_id'))->first();
    if(!$old_review)
    {
        return response(['error' => ['error' => '*Something went wrong, try again later!']]);
    }

    $worker = $connection->select('workers')->where('worker_id', $old_review->r_employee_id)->first();
    if(!$worker)
    {
        return response(['error' => ['error' => '*Employee does not exist!']]);
    }

    $new_rating = $worker->ratings - $old_review->review_stars;

    $update = $connection->update('workers', [
            'ratings' => $new_rating,
            'rating_count' => $worker->rating_count -= 1
        ])->where('worker_id', $old_review->r_employee_id)->save();
    if($update)
    {
        $data = true;
        $connection->delete('employee_reviews')->where('review_id', Input::get('review_id'))->save();
    }
    return response(['data' => $data]);
}









// =======================================
//   GET ALL EMPLOYEE REVIEWS
// =======================================
if(Input::post('get_all_employee_reviews'))
{
    if(Input::get('employe_id'))
    {
        $reviews = $connection->select('employee_reviews')->leftJoin('employers', 'employee_reviews.r_employer_id', '=', 'employers.id')->where('r_employee_id', Input::get('employe_id'))->get();        
        return include('employer/common/ajax-employee-review.php');
    }
}








// ========================================
// REPORT AN EMPLOYEE
// ========================================
if(Input::post('report_employee'))
{
    $data = false;
    $validate = new Validator();
    $validation = $validate->validate([
        'reason' => 'required',
        'comment' => 'min:6|max:200',
    ]);

    if(!$validation->passed())
    {
        return response(['error' => $validation->error()]);
    }

    $old_reports = $connection->select('employer_reports')->where('employer_rid', Auth_employer::employer('id'))->where('employee_rid',Input::get('employee_id'))->where('is_answered', 0)->first();
    if($old_reports)
    {
        $data = true;
        Session::flash('error', 'You have a pending report for this employee!');
        return response(['data' => $data]);
    }
    $comment = Input::get('comment') ? Input::get('comment') : null;
    $reports =  $connection->select('reports')->where('report_id', Input::get('reason'))->where('is_feature', 1)->first();

    if($reports)
    {
        $create = $connection->create('employer_reports', [
                'r_request_id' => Input::get('request_id'),
                'employer_rid' => Auth_employer::employer('id'),
                'employee_rid' => Input::get('employee_id'),
                'work_rid' => Input::get('work_id'),
                'report' => $reports->report,
                'comment' => $comment
        ]);
        if($create){
            $data = true;
            Session::flash('success', 'Report has been sent successfully and will be reviewed soon');
        }
    }
    return response(['data' => $data]);
}








// ======================================
// ADD PROFILE IMAGE
// ======================================
if(Input::post('upload_employee_image'))
{
    $data = true;
    if(!Auth_employee::is_loggedin())
    {
        Session::flash('error', '*Signup or Login to be able to hire a worker');
        return response(['not_login' => ['login' => true]]);
    }

    if(Image::exists('image'))
    {
        $image = new Image();
        $file = Image::files('image');

        $file_name = Image::name('image', 'employee');
        $image->resize_image($file, ['name' => $file_name, 'width' => 200, 'height' => 200, 'size_allowed' => 1000000,'file_destination' => './employee/images/']);
            
        $image_name = '/employee/images/'.$file_name;

        if(!$image->passed())
        {
            return response(['error' => ['image' => $image->error()]]);
        }
        
        $connection = new DB();
        $employer = $connection->select('employee')->where('e_id', Auth_employee::employee('id'))->first();
        if($employer->w_image)
        {
            Image::delete('./'.$employer->w_image);
        }
        
        $update = $connection->update('employee', [
            'w_image' => $image_name
        ])->where('e_id', Auth_employee::employee('id'))->save();

        $image = $connection->select('employee')->where('e_id', Auth_employee::employee('id'))->first();

        if($update)
        {
            $data = asset($image->w_image);
        }
    }
    return response(['data' => $data]);
}





// ========================================
// ACCPET REQUEST
// ========================================
if(Input::post('employee_accept_offer'))
{
    $data = false;
    $requests = $connection->select('request_workers')->where('request_id', Input::get('request_id'))->first();    
    if(!$requests){
        Session::flash('error', 'Something went wrong, try again later!');
        return response(['error' => ['error' => true]]);
    }

    $update = $connection->update('request_workers', [
        'is_accept' => 1,
        'accepted_date' => date('Y-m-d H:i:s')
    ])->where('request_id', Input::get('request_id'))->save();

    if($update)
    {
        Session::flash('success', 'Job offer has been accepted successfully, you will be contacted be the employee soon');
        Session::flash('success-m', 'Job offer has been accepted successfully, you will be contacted be the employee soon');
        $data = true;
    }
   
   
    return response(['data' => $data]);
}






// ========================================
// CANCLE JOB OFFER
// ========================================
if(Input::post('employee_cancle_action'))
{
    $data = false;
    $requests = $connection->select('request_workers')->where('request_id', Input::get('request_id'))->first();    
    if(!$requests){
        Session::flash('error', 'Something went wrong, try again later!');
        return response(['error' => ['error' => true]]);
    }

    $update = $connection->update('request_workers', [
        'is_cancle' => 1,
        'cancled_date' => date('Y-m-d H:i:s')
    ])->where('request_id', Input::get('request_id'))->save();

    if($update)
    {
        Session::flash('error', 'Job offer has been cancled!');
        Session::flash('error-m', 'Job offer has been cancled!');
        $data = true;
    }
   
   
    return response(['data' => $data]);
}








// ========================================
// DELETE JOB OFFER
// ========================================
if(Input::post('employee_delete_request'))
{
    $data = false;
    $requests = $connection->select('request_workers')->where('request_id', Input::get('request_id'))->first();    
    if(!$requests){
        Session::flash('error', 'Something went wrong, try again later!');
        return response(['error' => ['error' => true]]);
    }

    $update = $connection->update('request_workers', [
        'is_employee_delete' => 1,
    ])->where('request_id', Input::get('request_id'))->save();

    if($update)
    {
        Session::flash('error', 'Deleted successfully!');
        Session::flash('error-m', 'Deleted successfully!');
        $data = true;
    }
   
   
    return response(['data' => $data]);
}





// ========================================
// DELETE JOB OFFER
// ========================================
if(Input::post('employer_delete_request'))
{
    $data = false;
    $requests = $connection->select('request_workers')->where('request_id', Input::get('request_id'))->first();    
    if(!$requests){
        Session::flash('error', 'Something went wrong, try again later!');
        return response(['error' => ['error' => true]]);
    }

    $update = $connection->update('request_workers', [
        'is_employer_delete' => 1,
        'is_employee_delete' => 1,
    ])->where('request_id', Input::get('request_id'))->save();

    if($update)
    {
        Session::flash('error', 'Deleted successfully!');
        Session::flash('error-m', 'Deleted successfully!');
        $data = true;
    }
   
   
    return response(['data' => $data]);
}





// =========================================
// ADD EDUCATION
// =========================================
if(Input::post('update_institution'))
{
    $data = false;
    $validate = new Validator();
    $validation = $validate->validate([
        'qualification' => 'required|min:1|max:100',
        'institution' => 'required|min:1|max:255',
        'city' => 'required|min:1|max:50',
        'state' => 'required|min:1|max:50',
        'country' => 'required|min:1|max:50',
        'start_month' => 'required',
        'start_year' => 'required',
    ]);

    if(!$validation->passed())
    {
        return response(['error' => $validation->error()]);
    }

    if(Input::get('inview') == 'true')
    {
        $end_date = null;
    }else{
         $validation = $validate->validate([
            'end_month' => 'required',
            'end_year' => 'required',
        ]);

        if(!$validation->passed())
        {
            return response(['error' => $validation->error()]);
        }

        if(Input::get('start_year') > Input::get('end_year'))
        {
            return response(['error' => ['start_year' => '*Start year is greater than end year']]);
        }

        if(Input::get('start_year') == Input::get('end_year'))
        {
            if(Input::get('start_month') > Input::get('end_month'))
            {
                return response(['error' => ['start_month' => '*Error, check starting month']]);
            }
        }

        $end_date = empty(Input::get('end_day')) ? date('M Y', strtotime(Input::get('end_year').'-'.Input::get('end_month'))) : date('d M Y', strtotime(Input::get('end_year').'-'.Input::get('end_month').'-'.Input::get('end_day')));
    }

    $start_date = empty(Input::get('start_day')) ? date('M Y', strtotime(Input::get('start_year').'-'.Input::get('start_month'))) : date('d M Y', strtotime(Input::get('start_year').'-'.Input::get('start_month').'-'.Input::get('start_day')));
 
    $inview = Input::get('inview') == 'true' ? true : false;
    $educate = [
                    "qualification" => Input::get('qualification'), 
                    "institution" => Input::get('institution'), 
                    "city" => Input::get('city'), 
                    "state" => Input::get('state'), 
                    "country" => Input::get('country'), 
                    "start_date" => $start_date,
                    's_day' => Input::get('start_day'),
                    's_month' => Input::get('start_month'),
                    's_year' => Input::get('start_year'),
                    "end_date" => $end_date,
                    'e_day' => Input::get('end_day'),
                    'e_month' => Input::get('end_month'),
                    'e_year' => Input::get('end_year'),
                    "inview" => $inview ]; 

    // $worker = $connection->select('workers')->where('employee_id', Auth_employee::employee('id'))->first();
    // if(!$worker->education)
    // {
    //     $education_array[1] = $educate;
    // }else{
    //     $education_array = json_decode($worker->education, true);
    //     array_push($education_array, $educate);
    // }
    $education = json_encode($educate);

    $update = $connection->update('workers', [
        'education' => $education
    ])->where('employee_id', Auth_employee::employee('id'))->save();

    if($update)
    {
        Session::flash('success', 'Education addedd successfully!');
        Session::flash('success-m', 'Education addedd successfully!');
        $url = url('/employee/account');

        return response(['url' => $url]);
    }

    return response(['data' => $data]);
}









// ======================================
// EDIT EDUCATION
// ======================================
if(Input::post('edit_update_institution'))
{
    $data = false;
    $validate = new Validator();
    $validation = $validate->validate([
        'qualification' => 'required|min:1|max:100',
        'institution' => 'required|min:1|max:255',
        'city' => 'required|min:1|max:50',
        'state' => 'required|min:1|max:50',
        'country' => 'required|min:1|max:50',
        'start_month' => 'required',
        'start_year' => 'required',
    ]);

    if(!$validation->passed())
    {
        return response(['error' => $validation->error()]);
    }

    if(Input::get('inview') == 'true')
    {
        $end_date = null;
    }else{
         $validation = $validate->validate([
            'end_month' => 'required',
            'end_year' => 'required',
        ]);

        if(!$validation->passed())
        {
            return response(['error' => $validation->error()]);
        }

        if(Input::get('start_year') > Input::get('end_year'))
        {
            return response(['error' => ['start_year' => '*Start year is greater than end year']]);
        }

        if(Input::get('start_year') == Input::get('end_year'))
        {
            if(Input::get('start_month') > Input::get('end_month'))
            {
                return response(['error' => ['start_month' => '*Error, check starting month']]);
            }
        }
        $end_date = empty(Input::get('end_day')) ? date('M Y', strtotime(Input::get('end_year').'-'.Input::get('end_month'))) : date('d M Y', strtotime(Input::get('end_year').'-'.Input::get('end_month').'-'.Input::get('end_day')));
    }
    $start_date = empty(Input::get('start_day')) ? date('M Y', strtotime(Input::get('start_year').'-'.Input::get('start_month'))) : date('d M Y', strtotime(Input::get('start_year').'-'.Input::get('start_month').'-'.Input::get('start_day')));

    $inview = Input::get('inview') == 'true' ? true : false;
 
    $worker = $connection->select('workers')->where('employee_id', Auth_employee::employee('id'))->first();
    if($worker->education)
    {
        $educate = json_decode($worker->education, true);
        $educate['qualification'] = Input::get('qualification');
        $educate['institution'] = Input::get('institution');
        $educate['city'] = Input::get('city');
        $educate['state'] = Input::get('state');
        $educate['country'] = Input::get('country');
        $educate['start_date'] = $start_date;
        $educate['s_day'] = Input::get('start_day');
        $educate['s_month'] = Input::get('start_month');
        $educate['s_year'] = Input::get('start_year');
        $educate['end_date'] = $end_date;
        $educate['e_day'] = Input::get('end_day');
        $educate['e_month'] = Input::get('end_month');
        $educate['e_year'] = Input::get('end_year');
        $educate['inview'] = $inview;
    }

    $education = json_encode($educate);

    $update = $connection->update('workers', [
        'education' => $education
    ])->where('employee_id', Auth_employee::employee('id'))->save();

    if($update)
    {
        Session::flash('success', 'Education updated successfully!');
        Session::flash('success-m', 'Education updated successfully!');
        $url = url('/employee/account');

        return response(['url' => $url]);
    }


    return response(['data' => $data]);
}








// ===================================
// DELETE EDUCATION 
// ===================================
if(Input::post('delete_education_action'))
{
    $data = false;
    $worker = $connection->select('workers')->where('employee_id', Auth_employee::employee('id'))->first();
    if(!$worker->education)
    {
        Session::flash('error', '*Error, try agin later!');
        Session::flash('error-m', '*Error, try agin later!');
        return response(['error' => ['error' => true]]);
    }

    $update = $connection->update('workers', [
        'education' => null
    ])->where('employee_id', Auth_employee::employee('id'))->save();
    
    if($update)
    {
        Session::flash('success', 'Education updated successfully!');
        Session::flash('success-m', 'Education updated successfully!');
        return true;
    }

    if(!$data)
    {
        Session::flash('error', '*Network error, try again later!');
        Session::flash('error-m', '*Network error, try again later!');
    }
     
    return response(['data' => $data]);
}







// ====================================
// UPDATE JOB EXPERIENCE
// ====================================
if(Input::post('update_job_experience'))
{
    $data = false;
    $validate = new Validator();
    $validation = $validate->validate([
        'employer_email' => 'required|email',
        'job_title' => 'required|min:1|max:50',
        'job_function' => 'required|min:1|max:50',
        'employer_name' => 'required|min:1|max:50',
        'employer_phone' => 'required|min:11|max:11|number:employer_phone',  
        'description' => 'required|min:6|max:3000',
        'start_month' => 'required',
        'start_year' => 'required',      
    ]);

    
    if(!$validation->passed())
    {
        return response(['error' => $validation->error()]);
    }

    if(Input::get('inview') == 'true')
    {
        $end_date = null;
    }else{
         $validation = $validate->validate([
            'end_month' => 'required',
            'end_year' => 'required',
        ]);

        if(!$validation->passed())
        {
            return response(['error' => $validation->error()]);
        }

        if(Input::get('start_year') > Input::get('end_year'))
        {
            return response(['error' => ['start_year' => '*Start year is greater than end year']]);
        }

        if(Input::get('start_year') == Input::get('end_year'))
        {
            if(Input::get('start_month') > Input::get('end_month'))
            {
                return response(['error' => ['start_month' => '*Error, check starting month']]);
            }
        }
        $end_date = empty(Input::get('end_day')) ? date('M Y', strtotime(Input::get('end_year').'-'.Input::get('end_month'))) : date('d M Y', strtotime(Input::get('end_year').'-'.Input::get('end_month').'-'.Input::get('end_day')));
    }

    $start_date = empty(Input::get('start_day')) ? date('M Y', strtotime(Input::get('start_year').'-'.Input::get('start_month'))) : date('d M Y', strtotime(Input::get('start_year').'-'.Input::get('start_month').'-'.Input::get('start_day')));

    $inview = Input::get('inview') == 'true' ? true : false;

    $worker = $connection->select('workers')->where('employee_id', Auth_employee::employee('id'))->first();

    $experience['job_title'] = Input::get('job_title');
    $experience['job_function'] = Input::get('job_function');
    $experience['employer_name'] = Input::get('employer_name');
    $experience['employer_email'] = Input::get('employer_email');
    $experience['employer_phone'] = Input::get('employer_phone');
    $experience['description'] = Input::get('description');
    $experience['start_date'] = $start_date;
    $experience['s_day'] = Input::get('start_day');
    $experience['s_month'] = Input::get('start_month');
    $experience['s_year'] = Input::get('start_year');
    $experience['end_date'] = $end_date;
    $experience['e_day'] = Input::get('end_day');
    $experience['e_month'] = Input::get('end_month');
    $experience['e_year'] = Input::get('end_year');
    $experience['inview'] = $inview;

    // if(!$worker->work_experience)
    // {
    //     $stored_experience[1] = $experience;
    // }else{
    //     $stored_experience = json_decode($worker->work_experience, true);
    //     array_push($stored_experience, $experience);
    // }

    $store_experience = json_encode($experience);

    $update = $connection->update('workers', [
        'work_experience' => $store_experience
    ])->where('employee_id', Auth_employee::employee('id'))->save();

    if($update)
    {
        Session::flash('success', 'Experience addedd successfully!');
        Session::flash('success-m', 'Experience addedd successfully!');
        $url = url('/employee/account');

        return response(['url' => $url]);
    }
   

    return response(['data' => $data]);
}







// ====================================
// EDIT JOB EXPERIENCE
// ====================================
if(Input::post('edit_job_experience'))
{
    $data = false;
    $validate = new Validator();
    $validation = $validate->validate([
        'employer_email' => 'required|email',
        'job_title' => 'required|min:1|max:50',
        'job_function' => 'required|min:1|max:50',
        'employer_name' => 'required|min:1|max:50',
        'employer_phone' => 'required|min:11|max:11|number:employer_phone',  
        'description' => 'required|min:6|max:3000',
        'start_month' => 'required',
        'start_year' => 'required',      
    ]);

    
    if(!$validation->passed())
    {
        return response(['error' => $validation->error()]);
    }

    if(Input::get('inview') == 'true')
    {
        $end_date = null;
    }else{
         $validation = $validate->validate([
            'end_month' => 'required',
            'end_year' => 'required',
        ]);

        if(!$validation->passed())
        {
            return response(['error' => $validation->error()]);
        }

        if(Input::get('start_year') > Input::get('end_year'))
        {
            return response(['error' => ['start_year' => '*Start year is greater than end year']]);
        }

        if(Input::get('start_year') == Input::get('end_year'))
        {
            if(Input::get('start_month') > Input::get('end_month'))
            {
                return response(['error' => ['start_month' => '*Error, check starting month']]);
            }
        }
        $end_date = empty(Input::get('end_day')) ? date('M Y', strtotime(Input::get('end_year').'-'.Input::get('end_month'))) : date('d M Y', strtotime(Input::get('end_year').'-'.Input::get('end_month').'-'.Input::get('end_day')));
    }

    $start_date = empty(Input::get('start_day')) ? date('M Y', strtotime(Input::get('start_year').'-'.Input::get('start_month'))) : date('d M Y', strtotime(Input::get('start_year').'-'.Input::get('start_month').'-'.Input::get('start_day')));

    $inview = Input::get('inview') == 'true' ? true : false;

    $worker = $connection->select('workers')->where('employee_id', Auth_employee::employee('id'))->first();
    if(!$worker->work_experience)
    {
        Session::flash('error', 'Error, try again later!');
        Session::flash('error-m', 'Error, try again later!');
        $url = url('/employee/account');

        return response(['not_exist' => $url]);
    }

    $experience['job_title'] = Input::get('job_title');
    $experience['job_function'] = Input::get('job_function');
    $experience['employer_name'] = Input::get('employer_name');
    $experience['employer_email'] = Input::get('employer_email');
    $experience['employer_phone'] = Input::get('employer_phone');
    $experience['description'] = Input::get('description');
    $experience['start_date'] = $start_date;
    $experience['s_day'] = Input::get('start_day');
    $experience['s_month'] = Input::get('start_month');
    $experience['s_year'] = Input::get('start_year');
    $experience['end_date'] = $end_date;
    $experience['e_day'] = Input::get('end_day');
    $experience['e_month'] = Input::get('end_month');
    $experience['e_year'] = Input::get('end_year');
    $experience['inview'] = $inview;

    $store_experience = json_encode($experience);

    $update = $connection->update('workers', [
        'work_experience' => $store_experience
    ])->where('employee_id', Auth_employee::employee('id'))->save();

    if($update)
    {
        Session::flash('success', 'Experience updated successfully!');
        Session::flash('success-m', 'Experience updated successfully!');
        $url = url('/employee/account');

        return response(['url' => $url]);
    }
   

    return response(['data' => $data]);
}







// ===================================
// DELETE EXPERIENCE
// ===================================
if(Input::post('delete_experience_action'))
{
    $data = false;
    $worker = $connection->select('workers')->where('employee_id', Auth_employee::employee('id'))->first();
    if(!$worker->work_experience)
    {
        Session::flash('error', '*Error, try agin later!');
        Session::flash('error-m', '*Error, try agin later!');
        return response(['error' => ['error' => true]]);
    }

    $update = $connection->update('workers', [
        'work_experience' => null
    ])->where('employee_id', Auth_employee::employee('id'))->save();
    
    if($update)
    {
        Session::flash('success', 'Work experience updated successfully!');
        Session::flash('success-m', 'Work experience updated successfully!');
        $url = url('/employee/account');

        return response(['url' => $url]);
    }

    if(!$data)
    {
        Session::flash('error', '*Network error, try again later!');
        Session::flash('error-m', '*Network error, try again later!');
    }
     
    return response(['data' => $data]);
}





// =========================================
// READING ABOLITY
// =========================================
if(Input::post('employee_reading_ability'))
{
    $data = false;
    $worker = $connection->select('workers')->where('employee_id', Auth_employee::employee('id'))->first();
    
    $reading = Input::get('reading') == 'true' ? '1' : '0';
    $update = $connection->update('workers', [
        'reading' => $reading
    ])->where('employee_id', Auth_employee::employee('id'))->save();
    if($update)
    {
        $data = true;
    }
    return response(['data' => $data]);
}








// =========================================
// WRITING ABOLITY
// =========================================
if(Input::post('employee_writing_ability'))
{
    $data = false;
    $worker = $connection->select('workers')->where('employee_id', Auth_employee::employee('id'))->first();
    
    $reading = Input::get('writing') == 'true' ? '1' : '0';
    $update = $connection->update('workers', [
        'writing' => $reading
    ])->where('employee_id', Auth_employee::employee('id'))->save();
    if($update)
    {
        $data = true;
    }
    return response(['data' => $data]);
}







// =========================================
// EMPLOYEE LIVE IN
// =========================================
if(Input::post('employee_live_in'))
{
    $data = false;
    $worker = $connection->select('workers')->where('employee_id', Auth_employee::employee('id'))->first();
    
    $update = $connection->update('workers', [
        'job_type' => 'live in'
    ])->where('employee_id', Auth_employee::employee('id'))->save();
    if($update)
    {
        $data = true;
    }
    return response(['data' => $data]);
}







// =========================================
// EMPLOYEE LIVE OUT
// =========================================
if(Input::post('employee_live_out'))
{
    $data = false;
    $validate = new Validator();
    $validation = $validate->validate([
        'city' => 'required|min:1|max:100',
        'state' => 'required|min:1|max:100',      
    ]);

    
    if(!$validation->passed())
    {
        return response(['error' => $validation->error()]);
    }

    $liveout["liveout"] = true;
    $liveout["city"] = Input::get('city');
    $liveout["state"] = Input::get('state');
    
    
    $living = json_encode($liveout);
    $update = $connection->update('workers', [
        'job_type' => $living
    ])->where('employee_id', Auth_employee::employee('id'))->save();
    if($update)
    {
        $data = true;
        Session::flash('success', 'Job type updated successfully!');
        Session::flash('success-m', 'Job type updated successfully!');
    }

    return response(['data' => $data]);
}









// =========================================
// EMPLOYEE PAYMENT AMOUNT
// =========================================
if(Input::post('employee_payment_amounts'))
{
    $data = false;
    $validate = new Validator();
    $validation = $validate->validate([
        'amount_from' => 'required'    
    ]);

    
    if(!$validation->passed())
    {
        return response(['error' => $validation->error()]);
    }

    $amount_to = !empty(Input::get('amount_to')) ? Input::get('amount_to') : null;

    $update = $connection->update('workers', [
       'amount_form' => Input::get('amount_from'),
       'amount_to' => $amount_to,
    ])->where('employee_id', Auth_employee::employee('id'))->save();
    if($update)
    {
        $data = true;
        Session::flash('success', 'Job type updated successfully!');
        Session::flash('success-m', 'Job type updated successfully!');
    }

    return response(['data' => $data]);
}










// =========================================
// UPLOAD EMPLOYEE CV
// =========================================
if(Input::post('upload_employee_cv'))
{
    $data = false;

    $image = new Image();
    $file = Image::files('image');

    $ext = Image::ext('image');
    $extensions = ['pdf', 'docx'];
    if(!in_array($ext, $extensions))
    { 
        return response(['error' => ['image' => '*Document must be of pdf, docx type']]);
    }

    $file_name = Image::name('image', 'cv');
    $image->upload_image($file, [
        'name' => $file_name,
        'size_allowed' => 1000000,
        'file_destination' => './employee/images/cv/'
    ]);

    if(!$image->passed())
    {
        return response(['error' => ['image' => $image->error()]]);
    }

    $worker = $connection->select('workers')->where('employee_id', Auth_employee::employee('id'))->first();
    if($worker->cv)
    {
        $store = json_decode($worker->cv, true);
        Image::delete('./'.$store['cv']);
    }

    $store["name"] = $file_name;
    $store["cv"] = '/employee/images/cv/'.$file_name;
    $store["date"] = date('d M Y', strtotime(date('Y-m-d')));
    $store_cv = json_encode($store);


    $update = $connection->update('workers', [
        'cv' => $store_cv
     ])->where('employee_id', Auth_employee::employee('id'))->save();
     if($update)
     {
         $data = true;
         Session::flash('success', 'Cv uploaded successfully!');
         Session::flash('success-m', 'Cv uploaded successfully!');
     }


    return response(['data' => $data]);
}







// ==========================================
// DELETE CV
// ==========================================
if(Input::post('delete_employee_cv'))
{
    $data = false;
    $worker = $connection->select('workers')->where('employee_id', Auth_employee::employee('id'))->first();
    if($worker->cv)
    {
        $store = json_decode($worker->cv, true);
        $delete_image = Image::delete('./'.$store['cv']);

        if(!$delete_image)
        {
            return response(['error' => ['image' => "*Network error, try again later"]]);
        }

        $update = $connection->update('workers', [
            'cv' => null
         ])->where('employee_id', Auth_employee::employee('id'))->save();

        if($update)
        {   
            $data = true;
            Session::flash('error', 'Cv delete successfully!');
            Session::flash('error-m', 'Cv delete successfully!');
        }
    }
    return response(['data' => $data]);
}










// ==========================================
// UPDATE JOB TITLE
// ==========================================
if(Input::post('update_employee_job_title'))
{
    $data = false;
    $validate = new Validator();
    $validation = $validate->validate([
        'job_title' => 'required'     
    ]);

    
    if(!$validation->passed())
    {
        return response(['error' => $validation->error()]);
    }

    $category = $connection->select('job_categories')->where('job_category_id', Input::get('job_title'))->where('is_category_featured', 1)->first();
    if(!$category)
    {
        return response(['error' => ['category' => "*Category does not exist"]]);
    }

    $update = $connection->update('workers', [
        'job_title' => $category->category_name,
        'slug' => $category->category_slug,
        'job_category_id' => $category->job_category_id 
     ])->where('employee_id', Auth_employee::employee('id'))->save();

    if($update)
    {   
        $data = true;
        Session::flash('success', 'Job title updated successfully!');
        Session::flash('success-m', 'Job title updated successfully!');
    }

    return response(['data' => $data]);
}








// ========================================
// SUBSCRIBE TO NEWS LETTER
// ========================================
if(Input::post('subscribe_news_letter'))
{
    $validate = new Validator();
    $validation = $validate->validate([
        'email' => 'required|email|unique:newsletters_subscriptions',
        'client_type' => 'required',
    ]);

    if(!$validation->passed())
    {
        return response(['error' => $validation->error()]);
    }


    $create = $connection->create('newsletters_subscriptions', [
            'email' => Input::get('email'),
            'client_type' => Input::get('client_type'),
    ]);

    if($create)
    {
        $data = true;
    }


    return response(['data' => $data]);
}








// ===========================================
// STOP SEEING NEWS LETTER FORM
// ===========================================
if(Input::post('stop_news_letter'))
{
    $data = false;
    $expiry = 3153600;

    if(Cookie::has('remove_news_letter'))
    {
        Cookie::delete('remove_news_letter');
    }
    if(Cookie::put('remove_news_letter', true, $expiry))
    {
        $data = true;
    }
    return response(['data' => $data]);
}




// ************** EMPLOYEE LOGOUT **************//
if(Input::post('employee_logout_action'))
{
    $data = false;
    if(Auth_employee::is_loggedin())
    {
        $data = true;
        Auth_employee::logout();
    }

    return response(['data' => $data]);
}



// ************** EMPLOYEE LOGOUT **************//
if(Input::post('employer_logout_action'))
{
    $data = false;
    if(Auth_employer::is_loggedin())
    {
        $data = true;
        Auth_employer::logout();
    }

    return response(['data' => $data]);
}




// ************* DELETE SUBSCRIPTION*********//
if(Input::post('delete_subscription_btn'))
{
    $data = false;
    $update = $connection->update('employer_subscriptions', [
               'is_employer_delete' => 1
            ])->where('subscription_id', Input::get('sub_id'))->where('is_expire', 1)->save();
    if($update)
    {
        $data = true;
    }
    return response(['data' => $data]);
}



// *********** GET VERIFY ALERT **************//
if(Input::post('check_account_verify'))
{
    $data = false;
    if(Auth_employee::is_loggedin())
    {
        $employee = $connection->select('employee')->where('e_id', Auth_employee::employee('id'))->where('e_is_deactivate', 0)->first();
        if(!$employee->e_approved)
        {
            $data = asset('/employee/account');
        }
    }
    return response(['data' => $data]);
}





