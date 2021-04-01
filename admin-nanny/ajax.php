<?php include('../Connection.php');?>
<?php 



// ======================================
// UPLOAD ADMIN IMAGE
// ======================================
if(Input::post('upload_admin_image'))
{
    $data = false;
    $admin_id = Input::get('admin_id');
    if(!empty($admin_id))
    {
        $connection = new DB();
        $admin = $connection->select('admins')->where('id', $admin_id)->first();
        if($admin)
        {
            if(Image::exists('image'))
            {
                $image = new Image();
                $file = Image::files('image');

                $file_name = Image::name('image', 'admins');
                $image->resize_image($file, [ 'name' => $file_name, 'width' => 100, 'height' => 100, 'size_allowed' => 1000000,'file_destination' => '../admin/images/admin-img/']);
                    
                $image_name = '/admin/images/admin-img/'.$file_name;

                if(!$image->passed())
                {
                    return response(['error' => ['image' => $image->error()]]);
                }
                
                if($admin->image)
                {
                    Image::delete('../'.$admin->image);
                }
                
                $update = $connection->update('admins', [
                    'image' => $image_name
                ])->where('id', $admin_id)->save();

                if($update)
                {
                    $data = $admin_id;
                }
            }
            
        }
    }
    return response(['data' => $data]);
}






// =========================================
// GET ADMIN EDIT IMAGE
// =========================================
if(Input::get('get_edit_admin_image'))
{
    $admin_id = Input::get('admin_id');
    if(!empty($admin_id))
    {
        $connection = new DB();
        $admin = $connection->select('admins')->where('id', $admin_id)->first();
        return require_once('common/ajax-edit-admin-img.php');
    }
}







// ==========================================
// EMPLOYEE DEACTIVATE BUTTON
// ==========================================
if(Input::post('is_employee_deactivate'))
{
    $data = false;
    $employee_id = Input::get('employee_id');
    if(!empty($employee_id))
    {
        $connection = new DB();
        $employee = $connection->select('employee')->where('e_id', $employee_id)->first();
        $is_deactivate	 = $employee->e_is_deactivate ? 0 : 1;

        $update = $connection->update('employee', [
                    'e_is_deactivate' => $is_deactivate,
                    'is_active' => 0,
                    'is_feature' => 0
                ])->where('e_id', $employee_id)->save();
        if($update)
        {
            Session::flash('success', "Employee status updated successfully!");
            $data = true;
        }
    }
    if(!$data)
    {
        Session::flash('error', "*Network error, try again later");
    }
    return response(['data' => $data]);
}






// ==========================================
// EMPLOYEE APPROVE BUTTON
// ==========================================
if(Input::post('update_employee_approve'))
{
    $data = false;
    $employee_id = Input::get('employee_id');
    if(!empty($employee_id))
    {
        $connection = new DB();
        $employee = $connection->select('employee')->where('e_id', $employee_id)->first();
        $e_approved	 = $employee->e_approved ? 0 : 1;
        
        $update = $connection->update('employee', [
                    'e_is_deactivate' => 1,
                    'is_active' => 0,
                    'is_feature' => 0,
                    'e_approved' => $e_approved
                ])->where('e_id', $employee_id)->save();
        if($update)
        {
            Session::flash('success', "Employee status updated successfully!");
            $data = true;
        }
    }
    if(!$data)
    {
        Session::flash('error', "*Network error, try again later");
    }
    return response(['data' => $data]);
}






// ==========================================
// DELETE EMPLOYEE
// ==========================================
if(Input::get('delete_employee_action'))
{
    $data = false;
    $employee_id = Input::get('employee_id');
    if(!empty($employee_id))
    {
        $connection = new DB();
        $employee = $connection->select('employee')->where('e_id', $employee_id)->first();
        if($employee)
        {
            if($employee->w_image)
            {
                Image::delete('../'.$employee->w_image);
            }

            $worker = $connection->select('workers')->where('employee_id', $employee_id)->first();
            if($worker)
            {
                $store = json_decode($worker->cv, true);
                if($worker->cv)
                {
                    Image::delete('../'.$store['cv']);
                }
                $delete = $connection->delete('workers')->where('employee_id', $employee_id)->save();
            }

            $delete = $connection->delete('employee')->where('e_id', $employee_id)->save();
            if($delete)
            {
               $data = true;
               Session::flash('success', 'Employee deleted successfully!');
            }
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
     ])->where('employee_id', Input::get('employee_id'))->save();

    if($update)
    {   
        $data = true;
        Session::flash('success', 'Job title updated successufully!');
    }

    return response(['data' => $data]);
}






// =========================================
// UPDATE EDUCATION
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

    $worker = $connection->select('workers')->where('employee_id', Input::get('employee_id'))->first();
    if(!$worker->education)
    {
        $education_array[1] = $educate;
    }else{
        $education_array = json_decode($worker->education, true);
        array_push($education_array, $educate);
    }
    $education = json_encode($education_array);

    $update = $connection->update('workers', [
        'education' => $education
    ])->where('employee_id', Input::get('employee_id'))->save();

    if($update)
    {
        Session::flash('success', 'Education addedd successfully!');
        $url = url('/admin-nanny/employee-detail?wid='.Input::get('employee_id'));

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
 
    $worker = $connection->select('workers')->where('employee_id', Input::get('employee_id'))->first();
    if($worker->education)
    {
        $stored_education = json_decode($worker->education, true);
        if(array_key_exists(Input::get('key'), $stored_education))
        {
            $educate = $stored_education[Input::get('key')];
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

            $stored_education[Input::get('key')] = $educate;
        }
    }

    $education = json_encode($stored_education);

    $update = $connection->update('workers', [
        'education' => $education
    ])->where('employee_id', Input::get('employee_id'))->save();

    if($update)
    {
        Session::flash('success', 'Education updated successfully!');
        $url = url('/admin-nanny/employee-detail?wid='.Input::get('employee_id'));

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
    $worker = $connection->select('workers')->where('employee_id', Input::get('employee_id'))->first();
    if(!$worker->education)
    {
        Session::flash('error', '*Error, try agin later!');
        return response(['error' => ['error' => true]]);
    }

    $stored_education = json_decode($worker->education, true);
    if(!array_key_exists(Input::get('key'), $stored_education))
    {
        Session::flash('error', '*Education does not exist!');
        return response(['error' => ['error' => true]]);
    }

    unset($stored_education[Input::get('key')]);
   
    $education = count($stored_education) ? json_encode($stored_education) : null;

    $update = $connection->update('workers', [
        'education' => $education
    ])->where('employee_id', Input::get('employee_id'))->save();
    
    if($update)
    {
        Session::flash('success', 'Education updated successufully!');
        $url = url('/admin-nanny/employee-detail?wid='.Input::get('employee_id'));

        return response(['url' => $url]);
    }

    if(!$data)
    {
        Session::flash('error', '*Network error, try again later!');
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

    $worker = $connection->select('workers')->where('employee_id', Input::get('employee_id'))->first();

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

    if(!$worker->work_experience)
    {
        $stored_experience[1] = $experience;
    }else{
        $stored_experience = json_decode($worker->work_experience, true);
        array_push($stored_experience, $experience);
    }

    $store_experience = json_encode($stored_experience);

    $update = $connection->update('workers', [
        'work_experience' => $store_experience
    ])->where('employee_id', Input::get('employee_id'))->save();

    if($update)
    {
        Session::flash('success', 'Experience addedd successufully!');
        $url = url('/admin-nanny/employee-detail?wid='.Input::get('employee_id'));

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

    $worker = $connection->select('workers')->where('employee_id', Input::get('employee_id'))->first();
    if(!$worker->work_experience)
    {
        Session::flash('error', 'Error, try again later!');
        $url = url('/admin-nanny/employee-detail?wid='.Input::get('employee_id'));

        return response(['not_exist' => $url]);
    }else{
        $old_experience = json_decode($worker->work_experience, true);
        if(array_key_exists(Input::get('key'), $old_experience))
        {
            $experience = $old_experience[Input::get('key')];
        }
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

    $old_experience[Input::get('key')] = $experience;
    

    $store_experience = json_encode($old_experience);

    $update = $connection->update('workers', [
        'work_experience' => $store_experience
    ])->where('employee_id', Input::get('employee_id'))->save();

    if($update)
    {
        Session::flash('success', 'Experience updated successfully!');
        $url = url('/admin-nanny/employee-detail?wid='.Input::get('employee_id'));

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
    $worker = $connection->select('workers')->where('employee_id', Input::get('employee_id'))->first();
    if(!$worker->work_experience)
    {
        Session::flash('error', '*Error, try agin later!');
        return response(['error' => ['error' => true]]);
    }

    $stored_experience = json_decode($worker->work_experience, true);
    if(!array_key_exists(Input::get('key'), $stored_experience))
    {
        Session::flash('error', '*Work experience does not exist!');
        return response(['error' => ['error' => true]]);
    }

    unset($stored_experience[Input::get('key')]);
   
    $experience = count($stored_experience) ? json_encode($stored_experience) : null;

    $update = $connection->update('workers', [
        'work_experience' => $experience
    ])->where('employee_id', Input::get('employee_id'))->save();
    
    if($update)
    {
        Session::flash('success', 'Work experience updated successfully!');
        $url = url('/admin-nanny/employee-detail?wid='.Input::get('employee_id'));

        return response(['url' => $url]);
    }

    if(!$data)
    {
        Session::flash('error', '*Network error, try again later!');
    }
     
    return response(['data' => $data]);
}








// =========================================
// READING ABOLITY
// =========================================
if(Input::post('employee_reading_ability'))
{
    $data = false;
    $worker = $connection->select('workers')->where('employee_id', Input::get('employee_id'))->first();
    
    $reading = Input::get('reading') == 'true' ? '1' : '0';
    $update = $connection->update('workers', [
        'reading' => $reading
    ])->where('employee_id', Input::get('employee_id'))->save();
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
    $worker = $connection->select('workers')->where('employee_id', Input::get('employee_id'))->first();
    
    $reading = Input::get('writing') == 'true' ? '1' : '0';
    $update = $connection->update('workers', [
        'writing' => $reading
    ])->where('employee_id', Input::get('employee_id'))->save();
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
    $worker = $connection->select('workers')->where('employee_id', Input::get('employee_id'))->first();
    
    $update = $connection->update('workers', [
        'job_type' => 'live in'
    ])->where('employee_id', Input::get('employee_id'))->save();
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
    ])->where('employee_id', Input::get('employee_id'))->save();
    if($update)
    {
        $data = true;
        Session::flash('success', 'Job type updated successfully!');
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
    ])->where('employee_id', Input::get('employee_id'))->save();
    if($update)
    {
        $data = true;
        Session::flash('success', 'Job type updated successfully!');
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
        'file_destination' => '../employee/images/cv/'
    ]);

    if(!$image->passed())
    {
        return response(['error' => ['image' => $image->error()]]);
    }

    $worker = $connection->select('workers')->where('employee_id', Input::get('employee_id'))->first();
    if($worker->cv)
    {
        $store = json_decode($worker->cv, true);
        Image::delete('../'.$store['cv']);
    }

    $store["name"] = $file_name;
    $store["cv"] = '/employee/images/cv/'.$file_name;
    $store["date"] = date('d M Y', strtotime(date('Y-m-d')));
    $store_cv = json_encode($store);


    $update = $connection->update('workers', [
        'cv' => $store_cv
     ])->where('employee_id', Input::get('employee_id'))->save();
     if($update)
     {
         $data = true;
         Session::flash('success', 'Cv uploaded successfully!');
     }


    return response(['data' => $data]);
}








// ==========================================
// DELETE CV
// ==========================================
if(Input::post('delete_employee_cv'))
{
    $data = false;
    $worker = $connection->select('workers')->where('employee_id', Input::get('employee_id'))->first();
    if($worker->cv)
    {
        $store = json_decode($worker->cv, true);
        $delete_image = Image::delete('../'.$store['cv']);

        if(!$delete_image)
        {
            return response(['error' => ['image' => "*Network error, try again later"]]);
        }

        $update = $connection->update('workers', [
            'cv' => null
         ])->where('employee_id', Input::get('employee_id'))->save();

        if($update)
        {   
            $data = true;
            Session::flash('error', 'Cv delete successfully!');
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
        $image->resize_image($file, ['name' => $file_name, 'width' => 200, 'height' => 200, 'size_allowed' => 1000000,'file_destination' => '../employee/images/']);
            
        $image_name = '/employee/images/'.$file_name;

        if(!$image->passed())
        {
            return response(['error' => ['image' => $image->error()]]);
        }
        
        $connection = new DB();
        $employer = $connection->select('employee')->where('e_id', Input::get('employee_id'))->first();
        if($employer->w_image)
        {
            Image::delete('../'.$employer->w_image);
        }
        
        $update = $connection->update('employee', [
            'w_image' => $image_name
        ])->where('e_id', Input::get('employee_id'))->save();

        if($update)
        {
            $data = true;
        }
    }
    return response(['data' => $data]);
}









// ========================================
//     GET EMPLOYER IMAGE
// ========================================
if(Input::post('get_employee_img'))
{
    $data = false;
    $employee = $connection->select('employee')->where('e_id', Input::get('employee_id'))->first();
    return include('common/ajax-employee.php');
}





// ==========================================\
// REGISTER EMPLOYEE
// ===========================================
if(Input::post('add_new_employee'))
{
    $data = false;
    $validate = new Validator();
    $validation = $validate->validate([
        'first_name' => 'required|min:3|max:50',
        'last_name' => 'required|min:3|max:50',
        'email' => 'required|email|unique:employee',
        'gender' => 'required',
    ]);

    if(!$validation->passed())
    {
        return response(['error' => $validation->error()]);
    }

    if($validation->passed())
    {
        $create = new DB();
        $employee = $create->create('employee', [
                    'first_name' => Input::get('first_name'),
                    'last_name' => Input::get('last_name'),
                    'email' => Input::get('email'),
                    'gender' => Input::get('gender'),
                ]);
        if($employee->passed())
        {
            $employer = $connection->select('employee')->where('email', Input::get('email'))->first();
            $create->create('workers', [
                'employee_id' => $employer->e_id,
            ]);
            $data = true;
            Session::flash('success', 'Employee created successfully!');
        }
    }
    return response(['data' => $data]);
}







// ==========================================
// EMPLOYER DEACTIVATE BUTTON
// ==========================================
if(Input::post('is_employer_deactivate'))
{
    $data = false;
    $employer_id = Input::get('employer_id');
    if(!empty($employer_id))
    {
        $connection = new DB();
        $employer = $connection->select('employers')->where('id', $employer_id)->first();
        $is_deactivate	 = $employer->e_deactivate ? 0 : 1;

        $update = $connection->update('employers', [
                    'e_deactivate' => $is_deactivate,
                    'e_active' => 0
                ])->where('id', $employer_id)->save();
        if($update)
        {
            $data = true;
        }
    }
    if(!$data)
    {
        Session::flash('error', "*Network error, try again later");
    }
    return response(['data' => $data]);
}





// ======================================
// ADD EMPLOYER PROFILE IMAGE
// ======================================
if(Input::post('upload_employer_image'))
{
    $data = true;
    if(Image::exists('image'))
    {
        $image = new Image();
        $file = Image::files('image');

        $file_name = Image::name('image', 'employer');
        $image->resize_image($file, ['name' => $file_name, 'width' => 200, 'height' => 200, 'size_allowed' => 1000000,'file_destination' => '../employer/images/employer/']);
            
        $image_name = '/employer/images/employer/'.$file_name;

        if(!$image->passed())
        {
            return response(['error' => ['image' => $image->error()]]);
        }
        
        $connection = new DB();
        $employer = $connection->select('employers')->where('id', Input::get('employer_id'))->first();
        if($employer->e_image)
        {
            Image::delete('../'.$employer->e_image);
        }
        
        $update = $connection->update('employers', [
            'e_image' => $image_name
        ])->where('id', Input::get('employer_id'))->save();

        if($update)
        {
            $data = true;
        }
    }
    return response(['data' => $data]);
}









// ========================================
//     GET EMPLOYER IMAGE
// ========================================
if(Input::post('get_employer_img'))
{
    $data = false;
    $employer = $connection->select('employers')->where('id', Input::get('employer_id'))->first();
    return include('common/ajax-employer.php');
}






// ==========================================
// DELETE EMPLOYEE
// ==========================================
if(Input::get('delete_employer_action'))
{
    $data = false;
    $employer_id = Input::get('employer_id');
    if(!empty($employer_id))
    {
        $connection = new DB();
        $employer = $connection->select('employers')->where('id', $employer_id)->first();
        if($employer)
        {
            if($employer->e_image)
            {
                Image::delete('../'.$employer->e_image);
            }

            $delete = $connection->delete('employers')->where('id', $employer_id)->save();
            if($delete)
            {
               $data = true;
               Session::flash('success', 'Employer deleted successfully!');
            }
        }
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

    $update = $connection->delete('request_workers')->where('request_id', Input::get('request_id'))->save();
    if($update)
    {
        Session::flash('error', 'Job offer has been deleted successfully!');
        $data = true;
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
        return include('common/ajax-employee-review.php');
    }
}









// ==========================================
// CATEGORY FEATURE BUTTON
// ==========================================
if(Input::post('is_category_feature'))
{
    $data = false;
    if(Input::get('category_id'))
    {
        $category = $connection->select('job_categories')->where('job_category_id', Input::get('category_id'))->first();
        if($category)
        {
            $is_featured = $category->is_category_featured ? 0 : 1;
            $update = $connection->update('job_categories', [
                    'is_category_featured' => $is_featured
            ])->where('job_category_id', Input::get('category_id'))->save();
            if($update)
            {
                $data = true;
            }
        }
    }
    return response(['data' => $data]);
}








// ==========================================
// CATEGORY FEATURE BUTTON
// ==========================================
if(Input::post('delete_category_action'))
{
    $data = false;
    if(Input::get('category_id'))
    {
        $category = $connection->select('job_categories')->where('job_category_id', Input::get('category_id'))->first();
        if($category)
        {
          $delete = $connection->delete('job_categories')->where('job_category_id', Input::get('category_id'))->save();
          if($delete)
          {
              $data = true;
          }
        }
    }
    return response(['data' => $data]);
}








// ==========================================
// EDIT CATEGORY
// ==========================================
if(Input::post('category_edit_action'))
{
    $data = false;
    if(Input::get('category_id'))
    {
        $validate = new Validator();
        $validation = $validate->validate([
            'category' => 'required|min:2|max:50',
        ]);

        if(!$validation->passed())
        {
            return response(['error' => $validation->error()]);
        }

        $category = $connection->select('job_categories')->where('job_category_id', Input::get('category_id'))->where('category_name', Input::get('category'))->first();
        if(!$category)
        {
            $category = $connection->select('job_categories')->where('category_name', Input::get('category'))->first();
            if($category)
            {
                return response(['error' => ['category' => '*Category already exist']]);
            }
            $category_s = explode(' ', Input::get('category'));
            $category_slug = implode('-', $category_s);

            $update = $connection->update('job_categories', [
                'category_name' => Input::get('category'),
                'category_slug' => $category_slug
            ])->where('job_category_id', Input::get('category_id'))->save();
            if($update)
            {
                $data = true;
                Session::flash('success', 'Category updated successfully!');
            }
        }
    }
    return response(['data' => $data]);
}








// ==========================================
// EDIT CATEGORY
// ==========================================
if(Input::post('add_new_category'))
{
    $data = false;
    $validate = new Validator();
    $validation = $validate->validate([
        'category' => 'required|min:2|max:50',
    ]);

    if(!$validation->passed())
    {
        return response(['error' => $validation->error()]);
    }

    $category = $connection->select('job_categories')->where('category_name', Input::get('category'))->first();
    if($category)
    {
        return response(['error' => ['category' => '*Category already exist']]);
    }
    $category_s = explode(' ', Input::get('category'));
    $category_slug = implode('-', $category_s);

    $create = $connection->create('job_categories', [
        'category_name' => Input::get('category'),
        'category_slug' => $category_slug
    ]);
    if($create)
    {
        $data = true;
        Session::flash('success', 'Category created successfully!');
    }
    return response(['data' => $data]);
}








// ========================================
// DELETE EMPLOYER SUBSCRIPTION
// ========================================
if(Input::post('delete_employer_subscription'))
{
    $data = false;
    if(Input::get('sub_id'))
    {
        $employers = $connection->select('employer_subscriptions')->where('subscription_id', Input::get('sub_id'))->first();
        if($employers)
        {
            $data = true;
            $daelete = $connection->delete('employer_subscriptions')->where('subscription_id', Input::get('sub_id'))->save();
            if($daelete)
            {
                $data = true;
                Session::flash('success', 'Subscription deleted successfully!');
            }
        }   
    }
    return response(['data' => $data]);
}









// ==========================================
// SUBSCRIPTION FEATURE BUTTON
// ==========================================
if(Input::post('is_subscription_feature'))
{
    $data = false;
    $sub_id = Input::get('sub_id');
    if(!empty($sub_id))
    {
        $connection = new DB();
        $subscription = $connection->select('subscription_pan')->where('sub_id', $sub_id)->first();
        $is_feature	 = $subscription->is_feature ? 0 : 1;

        $update = $connection->update('subscription_pan', [
                    'is_feature' => $is_feature,
                ])->where('sub_id', $sub_id)->save();
        if($update)
        {
            $data = true;
        }
    }
    if(!$data)
    {
        Session::flash('error', "*Network error, try again later");
    }
    return response(['data' => $data]);
}








// ========================================
// DELETE SUBSCRIPTION
// ========================================
if(Input::post('delete_subscription_action'))
{
    $data = false;
    if(Input::get('sub_id'))
    {
        $subscription = $connection->select('subscription_pan')->where('sub_id', Input::get('sub_id'))->first();
        if($subscription)
        {
            $data = true;
            $daelete = $connection->delete('subscription_pan')->where('sub_id', Input::get('sub_id'))->save();
            if($daelete)
            {
                $data = true;
                Session::flash('success', 'Subscription deleted successfully!');
            }
        }   
    }
    return response(['data' => $data]);
}






// ==========================================
// EMPLOYEE FLAG
// ==========================================
if(Input::post('admin_employee_flag'))
{
    $data = false;
    $employee_id = Input::get('employee_id');
    if(!empty($employee_id))
    {
        $connection = new DB();
        $employee = $connection->select('employee')->where('e_id', $employee_id)->first();

        $update = $connection->update('employee', [
                    'e_is_deactivate' => 0,
                    'is_active' => 0,
                    'is_flagged' => 1,
                    'flagged_date' => date('Y-m-d H:i:s')
                ])->where('e_id', $employee_id)->save();
        if($update)
        {
            $data = true;
            Session::flash('error', 'Employee has been flagged!');
        }
    }
    if(!$data)
    {
        Session::flash('error', "*Network error, try again later");
    }
    return response(['data' => $data]);
}








// ==========================================
// EMPLOYEE CLEAR AND RESTORE
// ==========================================
if(Input::post('admin_restore_deactivate'))
{
    $data = false;
    $employee_id = Input::get('employee_id');
    if(!empty($employee_id))
    {
        $connection = new DB();
        $employee = $connection->select('employee')->where('e_id', $employee_id)->first();

        $update = $connection->update('employee', [
                    'e_is_deactivate' => 1,
                    'is_flagged' => 0,
                    'flagged_date' => null
                ])->where('e_id', $employee_id)->save();
        if($update)
        {
            $reports = $connection->select('employer_reports')->where('employee_rid', $employee_id)->get();
            foreach($reports as $report)
            {
                $connection->delete('employer_reports')->where('employee_rid', $employee_id)->save();
            }
            $data = true;
            Session::flash('success', 'Employee has been clear and activated!');
        }
    }
    if(!$data)
    {
        Session::flash('error', "*Network error, try again later");
    }
    return response(['data' => $data]);
}






// ==========================================
// EMPLOYEE ANSWERED BUTTON
// ==========================================
if(Input::post('is_answered_employer'))
{
    $data = false;
    $answered_id = Input::get('answered_id');
    if(!empty($answered_id))
    {
        $connection = new DB();
        $report = $connection->select('employer_reports')->where('rid', $answered_id)->first();
        $is_answered = $report->is_answered ? 0 : 1;
        $update = $connection->update('employer_reports', [
                    'is_answered' => $is_answered
                ])->where('rid', $answered_id)->save();
        if($update)
        {
            $data = true;
        }
    }
    if(!$data)
    {
        Session::flash('error', "*Network error, try again later");
    }
    return response(['data' => $data]);
}










// ==========================================
// ADMIN UNFLAG EMPLOYEE
// ==========================================
if(Input::post('admin_unflag_employee'))
{
    $data = false;
    $employee_id = Input::get('employee_id');
    if(!empty($employee_id))
    {
        $connection = new DB();
        $employee = $connection->select('employee')->where('e_id', $employee_id)->first();

        $update = $connection->update('employee', [
                    'e_is_deactivate' => 0,
                    'is_flagged' => 0,
                    'flagged_date' => null
                ])->where('e_id', $employee_id)->save();
        if($update)
        {
            $data = true;
            Session::flash('success', 'Employee has been Unflagged!');
        }
    }
    if(!$data)
    {
        Session::flash('error', "*Network error, try again later");
    }
    return response(['data' => $data]);
}










// =================================================
//  UPLOAD APP LOGO IMAGE
// =================================================\

if(Input::post('upload_app_logo_image'))
{
    $data = false;
    if(Image::exists('app_logo'))
    {
        $image = new Image();
        $file = Image::files('app_logo');

        $file_name = Image::name('app_logo', 'logo');
        $image->resize_image($file, [ 'name' => $file_name, 'width' => 50, 'height' => 56, 'size_allowed' => 1000000,'file_destination' => '../admin/images/']);
            
        $image_name = '/admin/images/'.$file_name;

        if(!$image->passed())
        {
            return response(['error' => ['app_logo' => $image->error()]]);
        }
        
        $connection = new DB();
        $settings = $connection->select('settings')->where('id', 1)->first();
        if($settings->logo)
        {
            Image::delete('../'.$settings->logo);
        }
        
        $update = $connection->update('settings', [
            'logo' => $image_name
        ])->where('id', 1)->save();

        if($update)
        {
            $data =  true;
        }
    }
    return response(['data' => $data]);
}







// ==========================================
//   GET APP LOGOS
// ==========================================
if(Input::post('get_app_logos'))
{
    $data = false;
    $connection = new DB();
    $banner = $connection->select('settings')->where('id', 1)->first();
    if($banner->logo)
    {
        return require_once('common/ajax-app-logo-img.php');
    }
}








// =================================================
//  UPLOAD APP LOGO IMAGE
// =================================================\

if(Input::post('upload_footer_logo_image'))
{
    $data = false;
    if(Image::exists('footer_logo'))
    {
        $image = new Image();
        $file = Image::files('footer_logo');

        $file_name = Image::name('footer_logo', 'footer_logo');
        $image->resize_image($file, [ 'name' => $file_name, 'width' => 50, 'height' => 56, 'size_allowed' => 1000000,'file_destination' => '../admin/images/']);
            
        $image_name = '/admin/images/'.$file_name;

        if(!$image->passed())
        {
            return response(['error' => ['footer_logo' => $image->error()]]);
        }
        
        $connection = new DB();
        $settings = $connection->select('settings')->where('id', 1)->first();
        if($settings->footer_logo)
        {
            Image::delete('../'.$settings->footer_logo);
        }
        
        $update = $connection->update('settings', [
            'footer_logo' => $image_name
        ])->where('id', 1)->save();

        if($update)
        {
            $data =  true;
        }
    }
    return response(['data' => $data]);
}









// ==========================================
//   GET APP LOGOS
// ==========================================
if(Input::post('get_footer_logos'))
{
    $data = false;
    $connection = new DB();
    $banner = $connection->select('settings')->where('id', 1)->first();
    if($banner->footer_logo)
    {
        return require_once('common/ajax-footer-logo-img.php');
    }
}






// ==================================================
// ACTIVATE OR DEACTIVATE PAYSTACK SECRETE KEY
// ==================================================
if(Input::post('paystack_is_activate'))
{
    $data = false;
    $connection = new DB();
    $setting =  $connection->select('settings')->where('id', 1)->first();
    $is_activate = $setting->is_paystack_activate ? 0 : 1;

    $update = $connection->update('settings', [
            'is_paystack_activate' => $is_activate
        ])->where('id', 1)->save();
    if($update)
    {
        $data = true;
    }
    return response(['data' => $data]);
}







// ======================================
// ADD TESTIMONIAL PROFILE IMAGE
// ======================================
if(Input::post('upload_testimonial_image'))
{
    $data = true;
    if(Image::exists('image'))
    {
        $image = new Image();
        $file = Image::files('image');

        $file_name = Image::name('image', 'testimonial');
        $image->resize_image($file, ['name' => $file_name, 'width' => 200, 'height' => 200, 'size_allowed' => 1000000,'file_destination' => '../images/testimonial/']);
            
        $image_name = '/images/testimonial/'.$file_name;

        if(!$image->passed())
        {
            return response(['error' => ['image' => $image->error()]]);
        }
        
        $connection = new DB();
        $testimonial = $connection->select('testimonial')->where('id', Input::get('testimonial_id'))->first();
        if($testimonial->image)
        {
            Image::delete('../'.$testimonial->image);
        }
        
        $update = $connection->update('testimonial', [
            'image' => $image_name
        ])->where('id', Input::get('testimonial_id'))->save();

        if($update)
        {
            $data = true;
        }
    }
    return response(['data' => $data]);
}








// ========================================
//     GET TESTIMONIAL IMAGE
// ========================================
if(Input::post('get_testimonial_img'))
{
    $data = false;
    $testimonial = $connection->select('testimonial')->where('id', Input::get('testimonial_id'))->first();
    return include('common/ajax-testimonial-img.php');
}











// =======================================
// UPDATE TESTIMONIAL FUNCTIONS
// =======================================
if(Input::post('update_function_action'))
{
    $data = true;
    if(Input::get('testimonial_id'))
    {
        $validate = new Validator();
        $validation = $validate->validate([
            'functions' => 'required|min:1|max:50',  
        ]);

        if(!$validation->passed())
        {
            return response(['error' => $validation->error()]);
        }

        $old_functions = $connection->select('testimonial')->where('id', Input::get('testimonial_id'))->first(); 

        if($old_functions->function)
        {
            $check_function = json_decode($old_functions->function, true);
            if(in_array(Input::get('functions'), $check_function))
            {
                return response(['error' => ['functions' => '*Function already exist']]);
            }
        }

        if($old_functions->function)
        {
            $store_function = json_decode($old_functions->function, true);
            array_push($store_function, Input::get('functions'));
        }else{
            $store_function = [];
            array_push($store_function, Input::get('functions'));
        }

        $store = json_encode($store_function);

        $update = $connection->update('testimonial', [
            'function' => $store
        ])->where('id', Input::get('testimonial_id'))->save();
        if($update)
        {
            $data = true;
        }
    }
    return response(['data' => $data]);
}







// ========================================
// GET TESTIMONIAL FUNCTION 
// ========================================
if(Input::post('get_function_action'))
{
    $data = false;
    $testimonial = $connection->select('testimonial')->where('id', Input::get('testimonial_id'))->first();
    return include('common/ajax-testimonial-function.php');
}







// ========================================
// TESTIMONIAL FUNCTION CANCLE
// ========================================
if(Input::post('function_cancle_action'))
{
    $data = false;
    if(Input::get('testimonial_id'))
    {
        $old_functions = $connection->select('testimonial')->where('id', Input::get('testimonial_id'))->first(); 
        if($old_functions->function)
        {
            $store_function = json_decode($old_functions->function, true);
            if(array_key_exists(Input::get('key'), $store_function))
            {
                unset($store_function[Input::get('key')]);
                $store = json_encode($store_function);
            }
        }

        if(count($store_function) == 0)
        {
            $store = null;
        }

        $update = $connection->update('testimonial', [
            'function' => $store
        ])->where('id', Input::get('testimonial_id'))->save();
        if($update)
        {
            $data = true;
        }
    }
    return response(['data' => $data]);
}






// ==========================================
// TESTIMONIAL FEATURE BUTTON
// ==========================================
if(Input::post('is_testimonial_feature'))
{
    $data = false;
    $connection = new DB();
    $testimonial =  $connection->select('testimonial')->where('id', Input::get('testimonial_id'))->first();
    if(!$testimonial)
    {
        Session::flash('error', '*Testimonial does not exist');
        return response(['error' => true]);
    }
    $is_featured = $testimonial->is_featured ? 0 : 1;

    $update = $connection->update('testimonial', [
        'is_featured' => $is_featured
    ])->where('id', Input::get('testimonial_id'))->save();
    if($update)
    {
        $data = true;
    }
    return response(['data' => $data]);
}







// ========================================
// DELETE TESTIMONIAL
// ========================================
if(Input::post('delete_testimonial_action'))
{
    $data = false;
    $connection = new DB();
    $testimonial =  $connection->select('testimonial')->where('id', Input::get('testimonial_id'))->first();
    if(!$testimonial)
    {
        Session::flash('error', '*Testimonial does not exist');
        return response(['error' => true]);
    }

    if($testimonial->image)
    {
        Image::delete('../'.$testimonial->image);
    }

    $delete = $connection->delete('testimonial')->where('id', Input::get('testimonial_id'))->save();
    if($delete)
    {
        $data = true;
    }
    return response(['data' => $data]);
}







// =======================================
// ADD TESTIMONIAL FUNCTIONS
// =======================================
if(Input::post('add_function_action'))
{
    $data = false;
    $validate = new Validator();
    $validation = $validate->validate([
        'functions' => 'required|min:1|max:50',  
    ]);

    if(!$validation->passed())
    {
        return response(['error' => $validation->error()]);
    }

    if(Session::has('functions'))
    {
        $check_function = Session::get('functions');
        if(in_array(Input::get('functions'), $check_function))
        {
            return response(['error' => ['functions' => '*Function already exist']]);
        }
    }

    $stored_function = Session::has('functions') ? Session::get('functions') : array();

    array_push($stored_function, Input::get('functions'));

    if(Session::put('functions', $stored_function))
    {
        $data = true;
    }
    
    return response(['data' => $data]);
}










// ========================================
// GET ADD TESTIMONIAL FUNCTION 
// ========================================
if(Input::post('get_add_function_action'))
{
    return include('common/ajax-add-testimonial-function.php');
}






// ========================================
// ADD TESTIMONIAL FUNCTION CANCLE
// ========================================
if(Input::post('add_testimonial_function_cancle'))
{
    $data = false;
    if(Session::has('functions'))
    {
        $stored_functions = Session::get('functions');
        if(array_key_exists(Input::get('key'), $stored_functions))
        {
            unset($stored_functions[Input::get('key')]);
        }
    
        if(count($stored_functions) == 0)
        {
            $data = true;
            Session::delete('functions');
            return response(['data' => $data]);
        }

        if(Session::put('functions', $stored_functions))
        {
            $data = true;
        }
    }
    return response(['data' => $data]);
}








// ======================================
// ADD TESTIMONIAL PROFILE IMAGE
// ======================================
if(Input::post('add_testimonial_image'))
{
    $data = true;
    if(Image::exists('image'))
    {
        $expiry = 604800;

        $image = new Image();
        $file = Image::files('image');

        $file_name = Image::name('image', 'testimonial');
        $image->resize_image($file, ['name' => $file_name, 'width' => 200, 'height' => 200, 'size_allowed' => 1000000,'file_destination' => '../images/testimonial/']);
            
        $image_name = '/images/testimonial/'.$file_name;

        if(!$image->passed())
        {
            return response(['error' => ['image' => $image->error()]]);
        }
        
        
        if(Cookie::has('testimoial_image'))
        {
            Image::delete('../'.Cookie::get('testimoial_image'));
        }
        Cookie::put('testimoial_image', $image_name, $expiry);
        
      
    }
    return response(['data' => $data]);
}








// ========================================
//     GET ADD TESTIMONIAL  IMAGE
// ========================================
if(Input::post('get_add_testimonial_img'))
{
    return include('common/ajax-add-testimonial-img.php');
}






// ======================================
// DELETE TESTIMONIAL PROFILE IMAGE
// ======================================
if(Input::post('delete_testimonial_img'))
{
    $data = false;
    if(Cookie::has('testimoial_image'))
    {
        Image::delete('../'.Cookie::get('testimoial_image'));
        Cookie::delete('testimoial_image');
        $data = true;
    }
    return response(['data' => $data]);
}






// ==========================================
// MESSAGE  SEEN BUTTON
// ==========================================
if(Input::post('is_message_seen'))
{
    $data = false;
    $connection = new DB();
    $contact =  $connection->select('contact_us')->where('id', Input::get('message_id'))->first();
    $is_seen = $contact->is_seen ? 0 : 1;

    $update = $connection->update('contact_us', [
            'is_seen' => $is_seen
        ])->where('id', Input::get('message_id'))->save();
    if($update)
    {
        $data = true;
    }
    return response(['data' => $data]);
}






// ==========================================
// DELETE MESSAGE
// ==========================================
if(Input::post('delete_message_action'))
{
    $data = false;
    $connection = new DB();
    $delete = $connection->delete('contact_us')->where('id', Input::get('message_id'))->save();
    if($delete)
    {
        $data = true;
    }
    return response(['data' => $data]);
}







// ===========================================
// FEATURE EMPLOYEE
// ===========================================
if(Input::post('update_employee_feature'))
{
    $data = false;
    $connection = new DB();
    $employee =  $connection->select('employee')->where('e_id', Input::get('employee_id'))->first();
    $is_featured = $employee->is_feature ? 0 : 1;

    $update = $connection->update('employee', [
            'is_feature' => $is_featured
        ])->where('e_id', Input::get('employee_id'))->save();
    if($update)
    {
        Session::flash('success', 'Employee features updated successfully!');
        $data = true;
    }
    return response(['data' => $data]);
}




// ===========================================
// EMPLOYEE ADD TO TOP
// ===========================================
if(Input::post('update_employee_top'))
{
    $data = false;
    $connection = new DB();
    $worker =  $connection->select('workers')->where('employee_id', Input::get('employee_id'))->first();
    $is_top = $worker->is_top ? 0 : 1;

    $update = $connection->update('workers', [
            'is_top' => $is_top
        ])->where('employee_id', Input::get('employee_id'))->save();
    if($update)
    {
        Session::flash('success', 'Employee updated successfully!');
        $data = true;
    }
    return response(['data' => $data]);
}





