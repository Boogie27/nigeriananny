<?php include('../Connection.php');?>
<?php 



// ==========================================
// ADD CATEGORY
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

    $category = $connection->select('course_categories')->where('category_name', Input::get('category'))->first();
    if($category)
    {
        return response(['error' => ['category' => '*Category already exist']]);
    }
    $category_s = explode(' ', Input::get('category'));
    $category_slug = implode('-', $category_s);

    $create = $connection->create('course_categories', [
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

        $category = $connection->select('course_categories')->where('category_id', Input::get('category_id'))->where('category_name', Input::get('category'))->first();
        if(!$category)
        {
            $category = $connection->select('course_categories')->where('category_name', Input::get('category'))->first();
            if($category)
            {
                return response(['error' => ['category' => '*Category already exist']]);
            }
            $category_s = explode(' ', Input::get('category'));
            $category_slug = implode('-', $category_s);

            $update = $connection->update('course_categories', [
                'category_name' => Input::get('category'),
                'category_slug' => $category_slug
            ])->where('category_id', Input::get('category_id'))->save();
            if($update)
            {
                $data = true;
                Session::flash('success', 'Category updated successfully!');
            }
        }
        if($category)
        {
            $data = true;
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
        $category = $connection->select('course_categories')->where('category_id', Input::get('category_id'))->first();
        if($category)
        {
          $delete = $connection->delete('course_categories')->where('category_id', Input::get('category_id'))->save();
          if($delete)
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
if(Input::post('is_category_feature'))
{
    $data = false;
    if(Input::get('category_id'))
    {
        $category = $connection->select('course_categories')->where('category_id', Input::get('category_id'))->first();
        if($category)
        {
            $is_featured = $category->is_categoryFeature ? 0 : 1;
            $update = $connection->update('course_categories', [
                    'is_categoryFeature' => $is_featured
            ])->where('category_id', Input::get('category_id'))->save();
            if($update)
            {
                $data = true;
            }
        }
    }
    return response(['data' => $data]);
}














// ==========================================
// DELETE COURSE
// ==========================================
if(Input::get('delete_course_action'))
{
    $data = false;
    $course_id = Input::get('course_id');
    if(!empty($course_id))
    {
        $connection = new DB();
        $course = $connection->select('courses')->where('course_id', $course_id)->first();
        if($course)
        {
            $tutor = json_decode($course->tutor, true);

            if($course->course_poster)
            {
                Image::delete('../'.$course->course_poster);
            }
            if($course->course_poster)
            {
                Image::delete('../'.$tutor['image']);
            }

            $delete = $connection->delete('courses')->where('course_id', $course_id)->save();
            if($delete)
            {
               $data = true;
               Session::flash('success', 'Course deleted successfully!');
            }
        }
    }
    return response(['data' => $data]);
}













// ===========================================
// FEATURE COURSE
// ===========================================
if(Input::post('update_course_feature'))
{
    $data = false;
    $connection = new DB();
    $course =  $connection->select('courses')->where('course_id', Input::get('course_id'))->first();
    $is_featured = $course->is_feature ? 0 : 1;

    $update = $connection->update('courses', [
            'is_feature' => $is_featured
        ])->where('course_id', Input::get('course_id'))->save();
    if($update)
    {
        Session::flash('success', 'Course features updated successfully!');
        $data = true;
    }
    return response(['data' => $data]);
}














// ********* GET WHAT YOU LEARN **********//
if(Input::post('add_what_to_learn'))
{
    $data = false;
    $validate = new Validator();
    $validation = $validate->validate([
        'what_to_learn' => 'required|min:6|max:100',
    ]);

    if(!$validation->passed())
    {
        return response(['error' => $validation->error()]);
    }

    $stored = array();
    if(Session::has('learn'))
    {
        $stored = Session::get('learn');
    }

    $stored[] = Input::get('what_to_learn');

    if(Session::put('learn', $stored))
    {
        $data = true;
    }

    return response(['data' => $data]);
}














// **** POPULATE WHAT TO LEARN ******//
if(Input::post('get_what_to_learn'))
{
    include('common/ajax-what-to-learn-add.php');
}




// ************ DELETE WHAT TO LEARN ***********//
if(Input::post('delete_what_to_learn'))
{
    $data = false;
    $key = Input::get('key');
    $stored = array();
    if(Session::has('learn'))
    {
        $stored = Session::get('learn');
        if(array_key_exists($key, $stored))
        {
            unset($stored[$key]);
        }
        
        if(!count($stored))
        {
            Session::delete('learn');
        }else{
            Session::put('learn', $stored);
        }
        $data = true;
    }

    return response(['data' => $data]);
}











// *********** UPLOAD COURSE IMAGE ***********//
if(Input::post('update_course_image'))
{
    $data = false;
    $expiry = 604800;
    if(Image::exists('course_image'))
    {
        $image = new Image();
        $file = Image::files('course_image');

        $file_name = Image::name('course_image', 'course_image');
        $image->upload_image($file, [ 'name' => $file_name, 'size_allowed' => 1000000,'file_destination' => '../courses/video/']);
            
        $image_name = '/courses/video/'.$file_name;

        if(!$image->passed())
        {
            return response(['error' => ['course_image' => $image->error()]]);
        }
        
        if(Cookie::has('course_img'))
        {
            Image::delete('../'.Cookie::get('course_img'));
            Cookie::delete('course_img');
        }
        if(Cookie::put('course_img', $image_name, $expiry))
        {
            $data = asset($image_name);
        }
    }

    return response(['data' => $data]);
}












// *********** UPLOAD TUTOR IMAGE ***********//
if(Input::post('update_tutor_image'))
{
    $data = false;
    $expiry = 604800;
    if(Image::exists('tutor_image'))
    {
        $image = new Image();
        $file = Image::files('tutor_image');

        $file_name = Image::name('tutor_image', 'tutor_image');
        $image->upload_image($file, [ 'name' => $file_name, 'size_allowed' => 1000000,'file_destination' => '../courses/images/tutor/']);
            
        $image_name = '/courses/images/tutor/'.$file_name;

        if(!$image->passed())
        {
            return response(['error' => ['tutor_image' => $image->error()]]);
        }
        
        if(Cookie::has('tutor_img'))
        {
            Image::delete('../'.Cookie::get('tutor_img'));
            Cookie::delete('tutor_img');
        }
        if(Cookie::put('tutor_img', $image_name, $expiry))
        {
            $data = asset($image_name);
        }
    }

    return response(['data' => $data]);
}













// ********* EDIT WHAT YOU LEARN **********//
if(Input::post('edit_what_to_learn'))
{
    $data = false;
    $validate = new Validator();
    $validation = $validate->validate([
        'what_to_learn' => 'required|min:6|max:100',
    ]);

    if(!$validation->passed())
    {
        return response(['error' => $validation->error()]);
    }

    $stored = array();
    $course = $connection->select('courses')->where('course_id', Input::get('course_id'))->first(); 
    if($course && $course->learn)
    {
        $stored = json_decode($course->learn, true);
    }

    $stored[] = Input::get('what_to_learn');

    $update = $connection->update('courses', [
                   'learn' => json_encode($stored)
                ])->where('course_id', Input::get('course_id'))->save();
    if($update){
        $data = true;
    }

    return response(['data' => $data]);
}













// **** POPULATE WHAT TO LEARN ******//
if(Input::post('get_edit_what_to_learn'))
{
    $learns = array();
    $course = $connection->select('courses')->where('course_id', Input::get('course_id'))->first(); 
    if($course && $course->learn)
    {
        $learns = json_decode($course->learn, true);
    }

    include('common/ajax-what-to-learn-edit.php');
}












// ************ DELETE EDIT WHAT TO LEARN ***********//
if(Input::post('delete_edit_what_to_learn'))
{
    $data = false;
    $learn = null;
    $key = Input::get('key');
    $course = $connection->select('courses')->where('course_id', Input::get('course_id'))->first(); 
    
    if($course && $course->learn)
    {
        $stored_learn = json_decode($course->learn, true);
        if(array_key_exists($key, $stored_learn))
        {
            unset($stored_learn[$key]);
        }

        if(count($stored_learn))
        {
            $learn = json_encode($stored_learn);
        }

        $update = $connection->update('courses', [
            'learn' => $learn
         ])->where('course_id', Input::get('course_id'))->save();

        if($update){
            $data = true;
        }
    }

    return response(['data' => $data]);
}













// *********** UPLOAD EDIT TUTOR IMAGE ***********//
if(Input::post('update_edit_tutor_image'))
{
    $data = false;
    $expiry = 604800;
    if(Image::exists('tutor_image'))
    {
        $image = new Image();
        $tutor = array();
        $file = Image::files('tutor_image');

        $file_name = Image::name('tutor_image', 'tutor_image');
        $image->upload_image($file, [ 'name' => $file_name, 'size_allowed' => 1000000,'file_destination' => '../courses/images/tutor/']);
            
        $image_name = '/courses/images/tutor/'.$file_name;

        if(!$image->passed())
        {
            return response(['error' => ['tutor_image' => $image->error()]]);
        }

        $course = $connection->select('courses')->where('course_id', Input::get('course_id'))->first(); 
        
        if($course->tutor)
        {
            $tutor_image = json_decode($course->tutor, true);
           
            Image::delete('../'.$tutor_image['image']);

            $tutor = ["name" => $tutor_image['name'], "image" => $image_name, "title" => $tutor_image['title'], "about" => $tutor_image['about']];

            $update = $connection->update('courses', [
                'tutor' => json_encode($tutor)
                ])->where('course_id', Input::get('course_id'))->save();

            if($update){
                $data = asset($image_name);
            }
        }
    }

    return response(['data' => $data]);
}
















// *********** UPLOAD EDIT COURSE IMAGE ***********//
if(Input::post('update_edit_course_image'))
{
    $data = false;
    $expiry = 604800;
    if(Image::exists('course_image'))
    {
        $image = new Image();
        $file = Image::files('course_image');

        $file_name = Image::name('course_image', 'course_image');
        $image->upload_image($file, [ 'name' => $file_name, 'size_allowed' => 1000000,'file_destination' => '../courses/video/']);
            
        $image_name = '/courses/video/'.$file_name;

        if(!$image->passed())
        {
            return response(['error' => ['course_image' => $image->error()]]);
        }
        
        $course = $connection->select('courses')->where('course_id', Input::get('course_id'))->first(); 
        if($course->course_poster)
        {

            Image::delete('../'.$course->course_poster);
        }

        $update = $connection->update('courses', [
            'course_poster' => $image_name
         ])->where('course_id', Input::get('course_id'))->save();

        if($update){
            $data = asset($image_name);
        }
    }

    return response(['data' => $data]);
}
















// ======================================
// ADD USER PROFILE IMAGE
// ======================================
if(Input::post('upload_ourse_user_image'))
{
    $data = true;
    if(Image::exists('image'))
    {
        $image = new Image();
        $file = Image::files('image');

        $file_name = Image::name('image', 'user');
        $image->resize_image($file, ['name' => $file_name, 'width' => 200, 'height' => 200, 'size_allowed' => 1000000,'file_destination' => '../courses/images/user/']);
            
        $image_name = '/courses/images/user/'.$file_name;

        if(!$image->passed())
        {
            return response(['error' => ['image' => $image->error()]]);
        }
        
        $connection = new DB();
        $user = $connection->select('course_users')->where('id', Input::get('user_id'))->first();
        if($user->image)
        {
            Image::delete('../'.$user->image);
        }
        
        $update = $connection->update('course_users', [
            'image' => $image_name
        ])->where('id', Input::get('user_id'))->save();

        if($update)
        {
            $data = asset($image_name);
        }
    }
    return response(['data' => $data]);
}













// ===========================================
// DEACTIVATE USERS
// ===========================================
if(Input::post('update_course_user_deactivate'))
{
    $data = false;
    $connection = new DB();
    $user =  $connection->select('course_users')->where('id', Input::get('user_id'))->first();
    $is_deactivate = $user->is_deactivate ? 0 : 1;

    $update = $connection->update('course_users', [
            'is_deactivate' => $is_deactivate,
            'is_active' => 0
        ])->where('id', Input::get('user_id'))->save();
    if($update)
    {
        if($is_deactivate)
        {
            send_deactivate_mail($user);
        }else{
            send_activate_mail($user);
        }
        Session::flash('success', 'User status updated successfully!');
        $data = true;
    }

    if(!$data){
        Session::flash('error', 'Network error, try again later!');
    }
    return response(['data' => $data]);
}











// ==========================================
// DELETE USERS
// ==========================================
if(Input::get('delete_course_user_action'))
{
    $data = false;
    if(!empty(Input::get('user_id')))
    {
        $connection = new DB();
        $user = $connection->select('course_users')->where('id', Input::get('user_id'))->first();
        if($user)
        {
            if($user->image)
            {
                Image::delete('../'.$user->image);
            }

            $delete = $connection->delete('course_users')->where('id', Input::get('user_id'))->save();
            
            $cours_reviews =  $connection->select('course_reviews')->where('course_user_id', Input::get('user_id'))->get();
            if(count($cours_reviews))
            {
                foreach($cours_reviews as $cours_review)
                {
                    $connection->delete('course_reviews')->where('course_user_id', Input::get('user_id'))->save();
                }
            }

            if($delete)
            {
               $data = true;
               Session::flash('success', 'User deleted successfully!');
            }
        }
    }
    return response(['data' => $data]);
}











// ************ DELETE COURSE REVIEW ***************//
if(Input::get('delete_course_user_review'))
{
    $data = false;
    $delete = $connection->delete('course_reviews')->where('course_id', Input::get('course_id'))->where('course_user_id', Input::get('user_id'))->save();
    if($delete)
    {
        $data = true;
        Session::flash('success', 'User review deleted successfully!');
    }
    return response(['data' => $data]);
}












// ******** DELETE TUTOR IMAGE IN ADD COURSE PAGE ***********//
if(Input::get('delete_add_tutor_image'))
{
    $data = false;
    if(Cookie::has('tutor_img'))
    {
        Image::delete('../'.Cookie::get('tutor_img'));
        Cookie::delete('tutor_img');
        $data = asset('/images/camera-icon.jpg');
    }
    return response(['data' => $data]);
}













// ******** DELETE COURSE IMAGE IN ADD COURSE PAGE ***********//
if(Input::get('delete_add_course_image'))
{
    $data = false;
    if(Cookie::has('course_img'))
    {
        Image::delete('../'.Cookie::get('course_img'));
        Cookie::delete('course_img');
        $data = asset('/images/camera-icon.jpg');
    }
    return response(['data' => $data]);
}









// ************** SEND NEWSLETTER *************//
if(Input::post('send_newsletter'))
{
    $data = false;
    $newsletter_id = Input::get('newsletter_id');

    $news_letter = $connection->select('news_letters')->where('id', $newsletter_id)->first();
    if($news_letter)
    {
        if($stored_ids = Input::get('stored_id'))
        {
            $member_type = Input::get('member_type');
            $banner =  $connection->select('settings')->where('id', 1)->first(); //get site details like app name, address and logo
            $newsLetter = get_news_letter_page($banner->logo, $banner->app_name, $banner->address, $news_letter->header, $news_letter->body);
            
            foreach($stored_ids as $id)
            {
                $member = $connection->select('course_users')->where('id', $id)->first();
                if($member)
                {
                    $mail = new Mail();
                    $send = $mail->mail([
                        'to' => $member->email,
                        'subject' => $news_letter->subject,
                        'body' => $newsLetter,
                    ]);
                    $send->send_email();
                    $data = true;
                }
            }
        }
    }
    return response(['data' => $data]);
}












if(Input::post('activate_users'))
{
    $data = false;
    if($stored_ids = Input::get('stored_id'))
    {
        foreach($stored_ids as $id)
        {
            $member = $connection->select('course_users')->where('id', $id)->first();
                if($member)
                {
                    $data = true;
                    $connection->update('course_users', [
                        'is_deactivate' => 0
                    ])->where('id', $id)->save();

                    send_activate_mail($member);
                }
        }
        if($data)
        {
            Session::flash('success', 'Activated successfully!');
        }
    }
    
    return response(['data' => $data]);
}

















function send_activate_mail($user)
{
    if($user)
    {
        $app = settings();
        $header = 'Account Activation';
        $body = 'Congratulations, Your account has been activated, 
                 You are able to see this mail because you are a member of nigeriananny,
                 If this is wrong kindly ignore or delete this mail. Thank you.';

        $mail_view = mail_view($app->logo, $app->app_name, $app->address, $header, $body);

        $mail = new Mail();
        $send = $mail->mail([
            'to' => $user->email,
            'subject' => $header,
            'body' => $mail_view,
        ]);
        $send->send_email();
        return true;
    }
    return false;
}







if(Input::post('deactivate_users'))
{
    $data = false;
    if($stored_ids = Input::get('stored_id'))
    {
        foreach($stored_ids as $id)
        {
            $member = $connection->select('course_users')->where('id', $id)->first();
                if($member)
                {
                    $data = true;
                    $connection->update('course_users', [
                        'is_deactivate' => 1,
                        'is_active' => 0
                    ])->where('id', $id)->save();

                    send_deactivate_mail($member);
                }
        }
        if($data)
        {
            Session::flash('success', 'Deactivated successfully!');
        }
    }
    
    return response(['data' => $data]);
}











function send_deactivate_mail($user)
{
    if($user)
    {
        $app = settings();
        $header = 'Account deactivation';
        $body = 'We are sorry to notify you that Your account has been deactivated, 
                kindly contact the admin if you wish to reactivate your account, Thank you.';

        $mail_view = mail_view($app->logo, $app->app_name, $app->address, $header, $body);

        $mail = new Mail();
        $send = $mail->mail([
            'to' => $user->email,
            'subject' => $header,
            'body' => $mail_view,
        ]);
        $send->send_email();
        return true;
    }
    return false;
}


















