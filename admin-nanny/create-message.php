<?php include('../Connection_Admin.php');  ?>
<?php
if(!Admin_auth::is_loggedin())
{
  Session::delete('admin');
  Session::put('old_url', '/admin-nanny/create-message');
  return view('/admin/login');
}



// =============================================
// ADD FAQ
// =============================================
if(Input::post('create_news_letter'))
{
    if(Token::check())
    {
        $validate = new DB();
    
        $validation = $validate->validate([
            'header' => 'required|min:3|max:200',
            'client_type' => 'required',
            'body' => 'required|min:6',
            'subject' => 'required|min:6|max:100'
        ]);

        if(!$validation->passed())
        {
            return back();
        }

        if($validation->passed())
        {
            $create = $connection->create('news_letters', [
                'header' => Input::get('header'),
                'subject' => Input::get('subject'),
                'n_client_type' => Input::get('client_type'),
                'body' => Input::get('body'),
            ]);
        
            if($create)
            {
                Session::flash('success', 'Newsletter created successfully!');
                return view('/admin-nanny/news-letters');
            }
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
                            <h4 class="title float-left">Create newsletter</h4>
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="<?= url('/admin-nanny') ?>">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><a href="<?= url('/admin-nanny/news-letters') ?>">News letter</a></li>
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
                                                    <?php  if(isset($errors['header'])) : ?>
                                                        <div class="text-danger"><?= $errors['header']; ?></div>
                                                    <?php endif; ?>
                                                </div>
                                                <label for="">Header: </label>
                                                <input type="text" name="header" class="form-control h50" value="<?= old('header') ?>">
                                            </div>    
                                        </div> 
                                        <div class="col-lg-6 col-sm-6">
                                            <div class="form-group">
                                                <div class="alert-div">
                                                    <?php  if(isset($errors['subject'])) : ?>
                                                        <div class="text-danger"><?= $errors['subject']; ?></div>
                                                    <?php endif; ?>
                                                </div>
                                                <label for="">Subject: </label>
                                                <input type="text" name="subject" class="form-control h50" value="<?= $news_letter->subject ?? old('subject') ?>">
                                            </div>    
                                        </div> 
                                        <div class="col-lg-6 col-sm-6">
                                            <div class="form-group">
                                                <div class="alert-div">
                                                    <?php  if(isset($errors['client_type'])) : ?>
                                                        <div class="text-danger"><?= $errors['client_type']; ?></div>
                                                    <?php endif; ?>
                                                </div>
                                                <label for="">Client type</label>
                                                <div class="ui_kit_select_box">
                                                    <select name="client_type" class="selectpicker custom-select-lg mb-3">
                                                        <option value="">Select</option>
                                                        <option value="employee" <?= old('client_type') == 'employee' ? 'selected' : ''?>>Employee</option>
                                                        <option value="employer" <?= old('client_type') == 'employer' ? 'selected' : ''?>>Employer</option>
                                                        <option value="other" <?= old('client_type') == 'other' ? 'selected' : ''?>>Other</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <div class="alert-div">
                                                    <?php  if(isset($errors['body'])) : ?>
                                                        <div class="text-danger"><?= $errors['body']; ?></div>
                                                    <?php endif; ?>
                                                </div>
                                                <label for="">Newsletter body:</label>
                                                <textarea id="body" name="body" class="form-control" placeholder="Write something"><?= old('body') ?></textarea>
                                                <script>
                                                        CKEDITOR.replace( 'body' );
                                                </script> 
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group text-right">
                                                <a href="<?= url('/admin-nanny/news-letters') ?>" class="mr-2"><i class="fa fa-angle-left"></i><i class="fa fa-angle-left"></i> back</a>
                                                <button type="submit" name="create_news_letter" class="btn btn-primary">Create...</button>
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


