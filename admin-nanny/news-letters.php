<?php include('../Connection.php');  ?>
<?php
if(!Admin_auth::is_loggedin())
{
  Session::delete('admin');
  Session::put('old_url', '/admin-nanny/job-alert');
  return view('/admin/login');
}


// ===========================================
// GET ALL NEWS LETTER SUBSCRIBERS
// ===========================================
$news_letters = $connection->select('news_letters')->paginate(15);


// ============================================
    // app banner settings
// ============================================
$banner =  $connection->select('settings')->where('id', 1)->first();





// ===========================================
// GET CLIENT TYPE
// ===========================================
$client_type = Session::has('employer_type') ? Session::get('employer_type') : null;


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
                    <div class="alert-danger text-center p-3 mb-2 page_alert_danger" style="display: none;"></div>
                        <nav class="breadcrumb_widgets" aria-label="breadcrumb mb30">
                            <h4 class="title float-left">Manage news letter</h4>
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item active" aria-current="page"><a href="<?= url('/admin-nanny/send-message') ?>" class="view-btn-fill">Write news letter</a></li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-lg-12">
                    <div class="item-table table-responsive"> <!-- table start-->
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                    <th scope="col">Type</th>
                                    <th scope="col">Header</th>
                                    <th scope="col">Body </th>
                                    <th scope="col">Status </th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Send</th>
                                    </tr>
                                </thead>
                                <tbody class="item-table-t">
                                <?php if($news_letters->result()): 
                                foreach($news_letters->result() as $news_letter):    
                                ?>
                                    <tr>
                                        <td><?= ucfirst($news_letter->n_client_type)?></td>
                                        <td><?= $news_letter->header ?></td>
                                        <td><?= substr($news_letter->body, 0, 300) ?></td>
                                        <td><span class="<?= $news_letter->is_sent ? 'delivered' : 'deactivate bg-warning'?>"><?= $news_letter->is_sent ? 'Sent' : 'Pending'?></span></td>
                                        <td><?= date('d M Y', strtotime($news_letter->date)) ?></td>
                                        <td>
                                        <div class="news-letter-dropdown">
                                            <div class="drop-down">
                                                <i class="fa fa-ellipsis-h dot-icon"></i>
                                                <ul class="drop-down-ul">
                                                    <li><a href="#" id="<?= $news_letter->id ?>" class="employee_add_top_btn">Send to All</a></li>
                                                    <li><a href="<?= url('/admin-nanny/employee-newsletter.php?nid='.$news_letter->id) ?>" class="employee_feature_btn">Sent to Employee</a></li>
                                                    <li><a href="<?= url('/admin-nanny/employer-newsletter.php?nid='.$news_letter->id) ?>" class="employee_deactivate_btn">Send to Employer</a></li>
                                                    <li><a href="<?= url('/admin-nanny/newsletter_preview?nid='.$news_letter->id) ?>"  class="">Preview</a></li>
                                                    <li><a href="#" id="<?= $news_letter->id ?>" class="delete_news_letter_btn">Delete</a></li>                                        
                                                </ul>
                                            </div>
                                        </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                         
                                <?php endif; ?>
                                </tbody>
                            </table>
                            <div class="col-lg-12">
                                <!-- pagination -->
                                <?php $news_letters->links(); ?>

                                <?php if(!$news_letters->result()): ?>
                                    <div class="empty-table">There are no news letters yet!</div>
                                <?php endif; ?>
                            </div>
                        </div><!-- table end-->
                    </div>
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












<a href="<?= url('/admin-nanny/ajax.php') ?>" class="ajax_url_page" style="display: none;"></a>





<?php  include('includes/footer.php') ?>



<script>
$(document).ready(function(){


// ========================================
// REMOVE PRELOADER
// ========================================
function remove_preloader(){
    setTimeout(function(){
        $(".preloader-container").hide();
        $(".alert-success").hide();
    }, 2000);
}





// ================================================
// DELETE NEWS LETTER
// ================================================
$(".delete_news_letter_btn").click(function(e){
    e.preventDefault();
    var id = $(this).attr('id');
    var url = $(".ajax_url_page").attr('href');
    $(".preloader-container").show() //show preloader

    $.ajax({
        url: url,
        method: "post",
        data: {
            id: id,
            admin_delete_news_letter: ' admin_delete_news_letter'
        },
        success: function (response){
            var data = JSON.parse(response);
            if(data.data){
                location.reload();
            }
            remove_preloader();
        }
    });

});








// end
});
</script>