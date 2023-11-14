<?php include('../Connection_Admin.php');  ?>


<?php
if(!Admin_auth::is_loggedin())
{
  Session::delete('admin');
  return view('/admin/login');
}


//************ app banner settings *************//
$banner =  $connection->select('settings')->where('id', 1)->first();


// ********* GET NOTIFICATION *****************//
$notifications = $connection->select('notifications')->where('to_id', 1)->where('to_user', 'admin')->orderBy('date', 'DESC')->paginate(20);



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
                            <div class="alert-success text-center p-3 mb-2"><?= Session::flash('success') ?></div>
                       <?php endif;?>
                        <nav class="breadcrumb_widgets" aria-label="breadcrumb mb30">
                            <h4 class="title float-left">Notifications</h4>
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="<?= url('/admin') ?>">Home</a></li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-lg-12">
                        <div class="notification-body">
                            <h4>All notifications</h4>
                            <?php if(count($notifications->result())): 
                             foreach($notifications->result() as $notification):    
                            ?>
                            <ul class="ul-notification <?= !$notification->is_seen ? 'not-seen' : ''?>">
                                <a href="<?= url($notification->link) ?>">
                                    <li><h4><?= $notification->name ?></h4> <span class="float-right text-success"><?= date('d M Y', strtotime($notification->date))?></span></li>
                                    <li><p><?= $notification->body ?></p></li>
                                </a>
                            </ul>
                            <?php endforeach; ?>
                            <?php else: ?>
                                <div class="">
                                     Empty notification
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php $notifications->links(); ?>
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


<a href="<?= url('/admin/ajax.php') ?>" class="ajax_url_tag" style="display: none;"></a>








<?php  include('includes/footer.php') ?>



