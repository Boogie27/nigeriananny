<?php include('../Connection.php');  ?>
<?php
if(!Admin_auth::is_loggedin())
{
  Session::delete('admin');
  Session::put('old_url', '/admin-nanny/edit-faq.php?fid='.Input::get('fid'));
  return view('/admin/login');
}



// ==============================================
// CHECK IF FAQ WAS CLICK
// ==============================================
if(!Input::exists('get') && !Input::get('fid'))
{
    return view('/admin-nanny/faq');
}






// =============================================
// EDIT FAQ
// =============================================
if(Input::post('edit_faq'))
{
    if(Token::check())
    {
        $validate = new DB();
    
        $validation = $validate->validate([
            'faq' => 'required|min:3|max:200',
            'faq_content' => 'required|min:6',
        ]);

        if($validation->passed())
        {
            $update = $connection->update('faqs', [
                'faq' => Input::get('faq'),
                'content' => Input::get('faq_content'),
            ])->where('id', Input::get('fid'))->save();
        
            if($update)
            {
                Session::flash('success', 'FAQ updated successfully!');
                return back();
            }
        }
    }
}


// ===========================================
// GET OTHERS FREQUESNTLY ASK QUESTIONS
// ===========================================
$faq = $connection->select('faqs')->where('id', Input::get('fid'))->first();


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
                            <h4 class="title float-left">Edit FAQ</h4>
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="<?= url('/admin-nanny') ?>">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><a href="<?= url('/admin-nanny/faq') ?>">FAQ</a></li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-lg-12"> <!-- edit faq start-->
                       <div class="account-x">
                            <div class="faq-form">
                                <form action="<?= current_url() ?>" method="POST">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <div class="alert-div">
                                                    <?php  if(isset($errors['faq'])) : ?>
                                                        <div class="text-danger"><?= $errors['faq']; ?></div>
                                                    <?php endif; ?>
                                                </div>
                                                <label for="">FAQ: </label>
                                                <input type="text" name="faq" class="form-control h50" value="<?= $faq->faq ?? old('faq') ?>">
                                            </div>    
                                        </div> 
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <div class="alert-div">
                                                    <?php  if(isset($errors['faq_content'])) : ?>
                                                        <div class="text-danger"><?= $errors['faq_content']; ?></div>
                                                    <?php endif; ?>
                                                </div>
                                                <label for="">FAQ content:</label>
                                                <textarea id="faq_content" name="faq_content" class="form-control" placeholder="Write something"><?= $faq->content ?? old('faq_content') ?></textarea>
                                                <script>
                                                        CKEDITOR.replace( 'faq_content' );
                                                </script> 
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group text-right">
                                                <a href="<?= url('/admin-nanny/faq') ?>" class="mr-2"><i class="fa fa-angle-left"></i><i class="fa fa-angle-left"></i> back</a>
                                                <button type="submit" name="edit_faq" class="btn btn-primary">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                    <?= csrf_token() ?>
                                </form>
                            </div>
                       </div>
                    </div><!-- edit faq end-->
                </div>
            </div>
        </div>
    </div>
</div>
<div class="footer-copy-right">
    <p><?= $banner->alrights ?></p>
</div>
<a class="scrollToHome" href="#"><i class="flaticon-up-arrow-1"></i></a>
</div>




<a href="<?= url('/admin-nanny/ajax.php') ?>" class="ajax_url_page" style="display: none;"></a>





<?php  include('includes/footer.php') ?>



