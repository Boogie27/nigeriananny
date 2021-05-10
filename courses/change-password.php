<?php include('../Connection.php');  ?>


<?php 
 if(!Auth_course::is_loggedin())
 {
    return view('/courses');
 }


 


//  ********** REST PASSWORD ************//
 if(Input::post('change_password'))
 {
     $validate = new DB(); 
     $validation = $validate->validate([
            'old_password' => 'required|min:6|max:12',
            'new_password' => 'required|min:6|max:12',
            'confirm_password' => 'required|min:6|max:12|match:new_password'
     ]);

     if(!$validation->passed())
    {
        return back();
    }

    $check = $connection->select('course_users')->where('email', Auth_course::user('email'))->where('id', Auth_course::user('id'))->first();
    if(!$check)
    {
        Session::delete('course_user');
        Session::flash('error', 'Wrong user, login and try again');
        return view('/courses/login');
    }

    if(!password_verify(Input::get('old_password'), $check->password))
    {
        Session::errors('errors', ['old_password' => '*Wrong password, try again!']);
        return back();
    }

    $update = $connection->update('course_users', [
                    'password' => password_hash(Input::get('new_password'), PASSWORD_DEFAULT),
                ])->where('email', Auth_course::user('email'))->where('id', Auth_course::user('email'))->save();

    if($update)
    {
        Auth_course::login($check->email);

        Session::flash('success', 'Password updated successfully!');
        return back();
    }
 }
?>




<?php include('includes/header.php');  ?>


<?php include('includes/navigation.php');  ?>










<div class="page-content-x">
    <div class="row" id="page-expand">
        <div class="col-lg-3" id="side-navigation-container">
            <?php include('includes/side-navigation.php');  ?>
        </div>
        <div class="col-lg-9 body-expand">
            <div class="body-content home-body-content">
                <div class="parent-container forgot-password">
                    <div class="sign_up_form auth-form-course">
                        <?php if(Session::has('error')): ?>
                            <div class="alert alert-danger text-center p-3 mb-2"><?= Session::flash('error') ?></div>
                        <?php endif; ?>
                        <?php if(Session::has('success')): ?>
                            <div class="alert alert-success text-center p-3"><?= Session::flash('success') ?></div>
                        <?php endif; ?>
						<div class="heading">
							<h3 class="text-center">Change password</h3>
						</div>
						<div class="details">
							<form action="<?= current_url()?>" method="POST">
                                <div class="row">
                                     <div class="col-lg-12">
                                        <div class="form-group">
                                            <div class="alert_no_label">
                                                <?php  if(isset($errors['old_password'])) : ?>
                                                    <div class="text-danger"><?= $errors['old_password']; ?></div>
                                                <?php endif; ?>
                                            </div>
                                            <input type="password" name="old_password" class="form-control" placeholder="Old password">
                                        </div>
                                     </div>
                                     <div class="col-lg-12">
                                        <div class="form-group">
                                            <div class="alert_no_label">
                                                <?php  if(isset($errors['new_password'])) : ?>
                                                    <div class="text-danger"><?= $errors['new_password']; ?></div>
                                                <?php endif; ?>
                                            </div>
                                            <input type="password" name="new_password" class="form-control" placeholder="New password">
                                        </div>
                                     </div>
                                     <div class="col-lg-12">
                                        <div class="form-group">
                                            <div class="alert_no_label">
                                                <?php  if(isset($errors['confirm_password'])) : ?>
                                                    <div class="text-danger"><?= $errors['confirm_password']; ?></div>
                                                <?php endif; ?>
                                            </div>
                                            <input type="password" name="confirm_password" class="form-control" placeholder="Confirm password">
                                        </div>
                                     </div>
                                     <div class="col-lg-12">
                                          <div class="form-group">
                                            <button type="submit" name="change_password" class="btn btn-log btn-block button">Change password</button>                                              
                                          </div>
                                     </div>
                                </div>
							</form>
						</div>
                    </div>
                </div>
            </div>
            <!-- footer -->
            <?php include('includes/footer.php') ?>
        </div>
    </div>
</div>


<!-- $2y$10$w9sWWqlDloXbT3kA.rbhgewK8dHfKRoOOsChF22uLQ8G3ZUciENZy -->