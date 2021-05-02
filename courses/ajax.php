<?php include('../Connection.php');  ?>
<?php 


// ************** DETAIL SIDE LOGIN ****************
if(Input::post('detail_side_login'))
{
    $data = false;
    $validate = new Validator();
    
    $validation = $validate->validate([
        'email' => 'required|email',
        'password' => 'required|min:6|max:12',
    ]);
   
    if(!$validation->passed())
    {
        return response(['error' => $validation->error()]);
    }
  
    $verification = $validate->select('course_users')->where('email', Input::get('email'))->first();
    if(!$verification)
    {
        return response(['error' => ['email' => '*Wrong email provided, try again!']]);
    }

    if(!password_verify(Input::get('password'), $verification->password))
    {
        return response(['error' => ['password' => '*Wrong password, try again!']]);
    }

    if($verification->is_deactivate)
    {
        return response(['deactivated' => '*Account deactivated, please contact the admin!']);
    }

    $remember_me = Input::get('remember_me') ? true : false;

    $logged_in = Auth_course::login(Input::get('email'), $remember_me);
    if($logged_in)
    {
        Session::flash('success', 'You have Logged successfully!');
        $data = true;
    }

    return response(['data' => $data]);
}





// ************** REVIEW COURSE ****************
if(Input::post('course_review'))
{
    $data = true;
    $course_id = Input::get('course_id');
    if(!Auth_course::is_loggedin())
    {
        return response(['login' => ['*Signup or login to review course!']]);
    }

    $validate = new Validator();
    
    $validation = $validate->validate([
        'star_rate' => 'required',
        'comment' => 'required|min:6|500',
    ]);
   
    if(!$validation->passed())
    {
        return response(['error' => $validation->error()]);
    }

    // ------check if user has rated this course------//
    $check = $validate->select('course_reviews')->where('course_user_id', Auth_course::user('id'))->where('course_id', $course_id)->first();
    if($check)
    {
        return response(['rated' => '*You have rated this course!']);
    }

    $review = $validate->create('course_reviews', [
                'course_user_id' => Auth_course::user('id'),
                'course_id' => $course_id,
                'comment' => Input::get('comment'),
                'review_stars' => Input::get('star_rate'),
            ]);
    // ------- update course rating ------- //
    $all_reviews = $validate->select('course_reviews')->where('course_id', $course_id)->get();
    
    $ratings = 0;
    foreach($all_reviews as $count){
        $ratings += $count->review_stars;
    }

    $update = $validate->update('courses', [
        'ratings' => $ratings,
        'rating_count' => count($all_reviews),
    ])->where('course_id', Input::get('course_id'))->save();

    if($review)
    {
        $data = $course_id;
    }

    return response(['data' => $data]);
}






// *************** GET REVIEWS *******************//
if(Input::post('get_course_reviews'))
{
    $course = $connection->select('courses')->where('course_id', Input::get('course_id'))->where('is_feature', 1)->first();

    $reviews = $connection->select('course_reviews')->leftJoin('course_users', 'course_reviews.course_user_id', '=', 'course_users.id')->where('course_id', Input::get('course_id'))->get();
    return include('common/ajax-course-review.php');
}



// ************ EDIT COURSE REVIEW MODAL OPEN ******************//
if(Input::post('open_edit_course_review_modal'))
{
    $user_review = $connection->select('course_reviews')->where('course_user_id', Auth_course::user('id'))->where('course_id', Input::get('course_id'))->first();
    return include('common/ajax-edit-course-review-modal.php');
}


// ************ EDIT COURSE REVIEW MODAL ******************//
if(Input::post('edit_course_review'))
{
    $data = false;
    $validate = new Validator();
    
    $validation = $validate->validate([
        'star_rate' => 'required',
        'comment' => 'required|min:6|500',
    ]);

    if(!$validation->passed())
    {
        return response(['error' => $validation->error()]);
    }

    $update = $connection->update('course_reviews', [
                    'comment' => Input::get('comment'),
                    'review_stars' => Input::get('star_rate'),  
        ])->where('course_user_id', Auth_course::user('id'))->where('course_id', Input::get('course_id'))->save();
     
    // ------- update course rating ------- //
    $all_reviews = $validate->select('course_reviews')->where('course_id', Input::get('course_id'))->get();
    
    $ratings = 0;
    foreach($all_reviews as $count){
        $ratings += $count->review_stars;
    }

    $update = $validate->update('courses', [
        'ratings' => $ratings
    ])->where('course_id', Input::get('course_id'))->save();

    if($update)
    {
        $data = Input::get('course_id');
    }
    return response(['data' => $data]);
}





// ************ GET UPDATED COURSE REVIEW ******************//
if(Input::post('get_updated_course_reviews'))
{
    $course = $connection->select('courses')->where('course_id', Input::get('course_id'))->where('is_feature', 1)->first();

    $reviews = $connection->select('course_reviews')->leftJoin('course_users', 'course_reviews.course_user_id', '=', 'course_users.id')->where('course_id', Input::get('course_id'))->get();
    return include('common/ajax-updated-course-review.php');
}




// ************* DELETE COURSE REVIEW ***************//
if(Input::post('delete_course_reviews'))
{
    $data = false;
    $delete = $connection->delete('course_reviews')->where('course_user_id', Auth_course::user('id'))->where('course_id', Input::get('course_id'))->save();
    
    // ------- update course rating ------- //
    $all_reviews = $connection->select('course_reviews')->where('course_id', Input::get('course_id'))->get();
    
    $ratings = 0;
    foreach($all_reviews as $count){
        $ratings += $count->review_stars;
    }

    $update = $connection->update('courses', [
        'ratings' => $ratings,
        'rating_count' => count($all_reviews)
    ])->where('course_id', Input::get('course_id'))->save();

    if($delete){
        $data = Input::get('course_id');
    }
    return response(['data' => $data]);
}






// ************ COURSE LIKE ******************//
if(Input::post('like_course_action'))
{
    $data = false;
    if(!Auth_course::is_loggedin())
    {
        Session::flash('error', '*Signup or login to like a course!');
        return response(['login' => true]);
    }

    $course_like = $connection->select('course_likes')->where('like_user_id', Auth_course::user('id'))->where('like_course_id', Input::get('course_id'))->first();

    if(!$course_like )
    {
        $create = $connection->create('course_likes', [
            'like_user_id' => Auth_course::user('id'),
            'like_course_id' => Input::get('course_id'),
            'likes' => 1,
        ]);
        if($create)
        {
            $data = true;
        }
    }else{
        $update = $connection->update('course_likes', [
            'likes' => 1,
            'dislikes' => 0
        ])->where('like_user_id', Auth_course::user('id'))->where('like_course_id', Input::get('course_id'))->save();
        
        if($update)
        {
            $data = true;
        }
    }

    return response(['data' => $data]);
}






// ************ COURSE DISLIKE ******************//
if(Input::post('dislike_course_action'))
{
    $data = true;
    if(!Auth_course::is_loggedin())
    {
        Session::flash('error', '*Signup or login to like a course!');
        return response(['login' => true]);
    }

    $course_like = $connection->select('course_likes')->where('like_user_id', Auth_course::user('id'))->where('like_course_id', Input::get('course_id'))->first();

    if(!$course_like )
    {
        $create = $connection->create('course_likes', [
            'like_user_id' => Auth_course::user('id'),
            'like_course_id' => Input::get('course_id'),
            'dislikes' => 1,
        ]);
        if($create)
        {
            $data = true;
        }
    }else{
        $update = $connection->update('course_likes', [
            'likes' => 0,
            'dislikes' => 1
        ])->where('like_user_id', Auth_course::user('id'))->where('like_course_id', Input::get('course_id'))->save();
        
        if($update)
        {
            $data = true;
        }
    }

    return response(['data' => $data]);
}





// *************** GET ALL LIKES ********************//
if(Input::post('get_al_likes'))
{
    $like = $connection->select('course_likes')->where('like_course_id', Input::get('course_id'))->where('likes', 1)->get();
    $dislike = $connection->select('course_likes')->where('like_course_id', Input::get('course_id'))->where('dislikes', 1)->get();
    
    $data = ['like' => count($like), 'dislike' => count($dislike)];
    return response(['type' => $data]);
}








// *************** SAVE COURSE ********************//
if(Input::post('save_course_action'))
{
    $data = false;
    $expiry = 3153600;

    if(empty(Input::get('course_id')))
    {
        return response(['error' => true]);
    }

    $stored_course = array();
    $stored_course = ["course_id" => Input::get('course_id')];
    
    if(Cookie::has('saved_course'))
    {
        $old_save = json_decode(Cookie::get('saved_course'), true);
        if(array_key_exists(Input::get('course_id'), $old_save))
        {
            unset($old_save[Input::get('course_id')]);
            $save_course = json_encode($old_save);
            Cookie::delete('saved_course');

            if(Cookie::put('saved_course', $save_course, $expiry))
            {
                return response(['unsaved' => 'Course unsaved']);
            }

        }
        Cookie::delete('saved_course');
    }

    $old_save[Input::get('course_id')] = $stored_course;
    $save_course = json_encode($old_save);
    if(Cookie::put('saved_course', $save_course, $expiry))
    {
        $data = true;
    }

    return response(['data' => $data]);
}






// ************** COURSE USERS  LOGOUT **************//
if(Input::post('course_user_logout_action'))
{
    $data = false;
    if(Auth_course::is_loggedin())
    {
        $data = true;
        Auth_course::logout();
    }

    return response(['data' => $data]);
}





































