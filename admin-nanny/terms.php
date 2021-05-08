<?php include('../Connection.php');  ?>
<?php
if(!Admin_auth::is_loggedin())
{
  Session::delete('admin');
  Session::put('old_url', '/admin-nanny/terms');
  return view('/admin/login');
}



// =======================================
// GET PRIVACY POLICY
// =======================================
$terms = $connection->select('settings')->where('id', 1)->first();


// ============================================
    // app banner settings
// ============================================
$banner =  $connection->select('settings')->where('id', 1)->first();



// ============================================
// UPDATE PRIVACY
// ============================================
if(Input::post('update_terms'))
{
    $validate = new DB();
   
    $validation = $validate->validate([
        'terms' => 'required',
    ]);

    if($validation->passed())
    {
        $privacy = new DB();
        $update = $privacy->update('settings', [
                    'terms' => Input::get('terms'),
                ])->where('id', 1)->save();
        if($update->passed())
        {
            Session::flash('success', 'Terms and Condition updated successfully!');
            return view('/admin-nanny/terms');
        }
    }
}
?>


<?php include('includes/header.php'); ?>


<!-- Main Header Nav -->
<?php include('includes/navigation.php') ?>

<!-- Main Header Nav For Mobile -->
<?php include('includes/mobile-navigation.php') ?>


<!-- Our Dashbord Sidebar -->
<?php include('includes/side-navigation.php') ?>





<!-- Our Dashbord -->
<div class="our-dashbord dashbord">
    <div class="dashboard_main_content">
        <div class="container-fluid">
            <div class="main_content_container">
                <div class="row">
                    <div class="col-lg-12">
                        <?php include('includes/mobile-drop-nav.php') ?><!-- mobile-navigation -->
                    </div>
                    <div class="col-lg-12">
                    <?php if(Session::has('success')): ?>
                        <div class="alert alert-success text-center p-3 mb-2"><?= Session::flash('success') ?></div>
                    <?php endif; ?>
                    <?php if(Session::has('error')): ?>
                        <div class="alert alert-danger text-center p-3 mb-2"><?= Session::flash('error') ?></div>
                    <?php endif; ?>
                    <div class="alert alert-danger text-center p-3 mb-2 page_alert_danger" style="display: none;"></div>
                        <nav class="breadcrumb_widgets" aria-label="breadcrumb mb30">
                            <h4 class="title float-left">Manage terms & condition</h4>
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="<?= url('/admin-nanny') ?>">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><a href="<?= url('/admin-nanny/terms') ?>">Terms and condition</a></li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-lg-12"> <!-- terms start-->
                      <div class="account-x">
                        <h3 class="rh-head">Terms and condition</h3><br><br>
                            <form action="<?= current_url() ?>" method="POST">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <div class="alert-div">
                                                <?php  if(isset($errors['terms'])) : ?>
                                                    <div class="text-danger"><?= $errors['terms']; ?></div>
                                                <?php endif; ?>
                                            </div>
                                            <label for="">Terms body:</label>
                                            <textarea id="terms" name="terms" class="form-control" placeholder="Write something"><?= $terms->terms ?? old('terms') ?></textarea>
                                            <script>
                                                    CKEDITOR.replace( 'terms' );
                                            </script> 
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group text-right">
                                            <button type="submit" name="update_terms" class="btn btn-primary">update...</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                      </div>
                    </div><!-- privacy end-->
                </div>
                <div class="row mt50 mb50">
                    <div class="col-lg-6 offset-lg-3">
                        <div class="copyright-widget text-center">
                            <p class="color-black2"><?= $banner->alrights ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<a class="scrollToHome" href="#"><i class="flaticon-up-arrow-1"></i></a>
</div>






<?php  include('includes/footer.php') ?>


