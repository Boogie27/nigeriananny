<?php include('../Connection.php');  ?>

<?php
//    banner 
$banner =  $connection->select('settings')->where('id', 1)->first();

if(!Input::exists('get') && Input::get('tid'))
{
   return Redirect::to('login.php');
}


if(Input::post('reset_password'))
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

        $reset = $connection->select('reset_password')->where('reset_token', Input::get('reset_token'))->first();
        if($reset)
        {
            $update_passowrd = $connection->update('users', [
                'password' => password_hash(Input::get('new_password'), PASSWORD_DEFAULT)
            ])->where('email', $reset->reset_email)->save();

            if($update_passowrd)
            {
                $connection->delete('reset_password')->where('reset_token', Input::get('reset_token'))->save();
                Session::delete('get_passsword');
                return Redirect::to('login.php', ['success', 'Password reset successfully, you can login!']);
            }
        }
    }
}


?>
<?php include('includes/header.php') ?>

<!-- Main Header Nav -->
<?php include('includes/navigation.php') ?>
<!-- main header nav end -->

<!-- serach bar -->
<?php include('includes/search-bar.php') ?>


<section class="inner_page_breadcrumb" style="background-image: url('<?= asset($banner->home_banner); ?>');">
    <div class="container">
        <div class="row">
            <div class="col-xl-6 offset-xl-3 text-center">
                <div class="breadcrumb_content">
                    <h4 class="page_title">Reset password</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= url('/shop') ?>">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Reset password</li>
                    </ol>
                </div>
                <div class="banner-icon-x">
                    <i class="fa fa-shopping-cart"></i>
                    <span class="cart_total_quantity"><?= Session::has('cart') ? Session::get('cart')->_totalQty : 0 ?></span>
                </div>
            </div>
        </div>
    </div>
</section>

<section>
    <!-- login start-->
    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
        <div class="login_form shop_form_container">
            <form action="<?= current_url() ?>" method="post">
                <div class="heading">
                    <h3 class="text-center">Reset password</h3>
                    <p class="text-center">Password reset field.</p>
                </div>
                <div class="form-group">
                    <?php  if(isset($errors['new_password'])) : ?>
                        <div class="text-danger"><?= $errors['new_password']; ?></div>
                    <?php endif; ?>
                    <input type="password" class="form-control" name="new_password" placeholder="New password">
                </div>
                <div class="form-group">
                    <?php  if(isset($errors['confirm_password'])) : ?>
                        <div class="text-danger"><?= $errors['confirm_password']; ?></div>
                    <?php endif; ?>
                    <input type="password" class="form-control" name="confirm_password" placeholder="Confirm password">
                    <input type="hidden" name="reset_token" value="<?= Input::get('tid') ?>">
                </div>
                <button type="submit" name="reset_password" class="btn btn-log btn-block btn-thm2">Reset password</button>
                <hr>
                <?= csrf_token() ?>
            </form>
        </div>
    </div>
    <!-- login end-->
</section>

<!-- footer -->
<?php include('includes/footer.php') ?>



