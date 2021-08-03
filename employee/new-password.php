<?php include('../Connection.php');  ?>

<?php
if(!Input::exists('get') && !Input::get('tid'))
{
   return view('/');
}
  


 if(Input::post('new_password_btn'))
 {
    if(Token::check())
    {
        $validate = new DB(); 
        $validation = $validate->validate([
            'new_password' => 'required|min:6|max:12',
            'confirm_password' => 'required|min:6|max:12|match:new_password'
        ]);

        if(!$validation->passed())
        {
            return back();
        }

        $reset = $connection->select('employee_reset_password')->where('reset_token', Input::get('reset_token'))->first();
        if($reset)
        {
            $update_password = $connection->update('employee', [
                'password' => password_hash(Input::get('new_password'), PASSWORD_DEFAULT)
            ])->where('email', $reset->reset_email)->save();

            if($update_password)
            {
                $connection->delete('employee_reset_password')->where('reset_token', Input::get('reset_token'))->save();
                Session::delete('get_passsword');
                return Redirect::to('login.php', ['success', 'Password reset successfully, you can now login!']);
            }
        }
        return back();
    }
 }

?>
<?php include('../includes/header.php');  ?>


<!-- top navigation-->
<?php include('../includes/navigation.php');  ?>

<?php include('../includes/side-navigation.php');  ?>




<div class="page-content">
    <div class="job-seeker-conatiner">
        <div class="sr-head"><h4>Reset to new password</h4></div>
        <form action="<?= current_url() ?>" method="POST">
            <?php if(Session::has('error')): ?>
                <div class="alert alert-danger text-center p-3 mb-2"><?= Session::flash('error') ?></div>
            <?php endif; ?>
            <div class="form-seeker form-employer-container">
                <div class="row">
                    <div class="col-lg-12 col-sm-6">
                        <div class="form-group">
                            <?php  if(isset($errors['new_password'])) : ?>
                                <div class="text-danger"><?= $errors['new_password']; ?></div>
                            <?php endif; ?>
                            <label for="">New password:</label>
                            <input type="password" name="new_password" class="form-control h50" value="<?= old('new_password')?>" required>
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-6">
                        <div class="form-group">
                            <?php  if(isset($errors['confirm_password'])) : ?>
                                <div class="text-danger"><?= $errors['confirm_password']; ?></div>
                            <?php endif; ?>
                            <label for="">Confirm password:</label>
                            <input type="password" name="confirm_password" class="form-control h50" value="<?= old('confirm_password')?>" required>
                        </div>
                    </div>
                   <div class="col-lg-12">
                        <div class="form-group">
                            <input type="hidden" name="reset_token" value="<?= Input::get('tid') ?>">
                            <button type="submit" name="new_password_btn" class="btn-fill">Reset password</button>
                            <p class="apply-p"><a href="<?= url('/') ?>" class="text-primary">Back</a></p>
                        </div>
                    </div>
                    <?= csrf_token() ?>
                </div>
            </div>
        </form>
    </div>
</div>




    
<!-- Our Footer -->
<?php include('../includes/footer.php');  ?>