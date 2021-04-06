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
$subscribers = $connection->select('newsletters_subscriptions')->where('client_type', 'employer')->paginate(15);


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
                    <?php if(!Input::exists('get') || !Input::get('nid')): ?>
                        <div class="text-warning text-center p-3"><i class="fa fa-bell"></i> Click <a href="<?= url('/admin-nanny/news-letters') ?>" class="text-primary">here</a> to select message to send to employers</div>
                    <?php endif; ?>
                    <div class="alert-danger text-center p-3 mb-2 page_alert_danger" style="display: none;"></div>
                        <nav class="breadcrumb_widgets" aria-label="breadcrumb mb30">
                            <h4 class="title float-left">Manage news letter</h4>
                            <ol class="breadcrumb float-right">
                            <?php if(Input::exists('get') && Input::get('nid')):?>
                                <li class="breadcrumb-item active" aria-current="page"><a href="#" id="#" data-toggle="modal" data-target="#employer_send_news_letter_btn"class="view-btn-fill">Send</a></li>
                            <?php endif; ?>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-lg-12">
                        <div class="table-header">
                            <div class="custom-control custom-checkbox text-right">
                                <input type="checkbox" class="custom-control-input news_letter_all_btn" id="customCheck_all" <?= Session::has('employer_all') ? 'checked' : ''?>>
                                <label class="custom-control-label" for="customCheck_all"><span>Mark all / Unmark all</span></label>
                            </div>
                        </div>
                    <div class="item-table table-responsive"> <!-- table start-->
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                    <th scope="col">Full name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Client type</th>
                                    <th scope="col">Select</th>
                                    <th scope="col">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="item-table-t">
                                <?php if($subscribers->result()): 
                                foreach($subscribers->result() as $subscriber):    
                                ?>
                                    <tr>
                                        <td><?= ucfirst($subscriber->full_name)?></td>
                                        <td><?= $subscriber->email ?></td>
                                        <td><?= ucfirst($subscriber->client_type) ?></td>
                                        <td>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" data-id="<?= $subscriber->id?>" class="custom-control-input news_letter_select_btn" id="customCheck_<?= $subscriber->id?>" <?= $client_type && array_key_exists($subscriber->id, $client_type) ? 'checked' : '' ?>>
                                                <label class="custom-control-label" for="customCheck_<?= $subscriber->id?>"></label>
                                            </div>
                                        </td>
                                        <td><?= date('d M Y', strtotime($subscriber->date)) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                         
                                <?php endif; ?>
                                </tbody>
                            </table>
                            <div class="col-lg-12">
                                <!-- pagination -->
                                <?php $subscribers->links(); ?>

                                <?php if(!$subscribers->result()): ?>
                                    <div class="empty-table">There are no subscribers yet!</div>
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









<!-- Modal send newsletter-->
<div class="sign_up_modal modal fade" id="employer_send_news_letter_btn" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" id="modal_newsletter_close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="login_form">
                        <form action="#">
                            <div class="heading">
                                <p class="text-center">Do you wish to send newsletters?</p>
                                <input type="hidden" id="employee_newsletter_id" value="<?= Input::get('nid')?>">
                            </div>
                            <button type="button" data-url="<?= url('/admin-nanny/ajax.php') ?>" id="submit_employer_newsletter_btn" class="btn bg-primary btn-log btn-block" style="color: #fff;">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
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







// ========================================
// SELECT NEWS LETTER CLIENTS 
// ========================================
$(".news_letter_select_btn").click(function(){
    var url = $(".ajax_url_page").attr('href');
    var news_id = $(this).attr('data-id');
    $(".news_letter_all_btn").prop('checked', false);

     $.ajax({
        url: url,
        method: "post",
        data: {
            news_id: news_id,
            select_news_letter_clients: ' select_news_letter_clients'
        },
        success: function (response){
            var data = JSON.parse(response);
            console.log(response)
        }
    });
});






// ======================================
// NEWS LETTER ALL BUTTON
// ======================================
$(".news_letter_all_btn").click(function(){
    var state = false;
    if($(this).prop('checked') == true)
    {
        $(".preloader-container").show(); //show preloader
        $('.news_letter_select_btn').prop('checked', true);
        state = true;
    }else{
       $('.news_letter_select_btn').prop('checked', false);
    }

    news_letter_check_all(state);
});



function news_letter_check_all(state){
    var url = $(".ajax_url_page").attr('href');
    var state = state ? 1 : 0;

     $.ajax({
        url: url,
        method: "post",
        data: {
            state: state,
            select_news_letter_employer_all: ' select_news_letter_employer_all'
        },
        success: function (response){
            var data = JSON.parse(response);
            if(data.data){
                remove_preloader();
            }
        }
    });
}








// =============================================
// SEND NEWS LETTER TO EMPLOYEES
// =============================================
$("#submit_employer_newsletter_btn").click(function(e){
    e.preventDefault();
    var news_id = $('#employee_newsletter_id').val();
    var url = $(".ajax_url_page").attr('href');
    $("#modal_newsletter_close").click();
    $(".preloader-container").show(); //show preloader
    
    $.ajax({
        url: url,
        method: "post",
        data: {
            news_id: news_id,
            send_news_letter_to_employer: 'send_news_letter_to_employer'
        },
        success: function (response){
            var data = JSON.parse(response);
            location.reload();
            remove_preloader();
            console.log(response)
        },
        error: function(){

        }
    });
});








// end
});
</script>