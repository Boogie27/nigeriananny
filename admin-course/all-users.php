<?php include('../Connection.php');  ?>
<?php
if(!Admin_auth::is_loggedin())
{
  Session::delete('admin');
  Session::put('old_url', '/admin-course/users');
  return view('/admin/login');
}


// ===========================================
// GET ALL EMPLOYEES
// ===========================================
$users = $connection->select('course_users');

if($search = Input::get('search'))
{
    if(preg_match('/@/', $search))
    {
        $users->where('email', $search);
    }else{
        $users->where('first_name', 'RLIKE', $search);
    }
}
$users->paginate(50);




// ============================================
    // app banner settings
// ============================================
$banner =  $connection->select('settings')->where('id', 1)->first();
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
                    <div class="alert alert-danger text-center p-3 mb-2 page_alert_danger" style="display: none;"></div>
                        <nav class="breadcrumb_widgets" aria-label="breadcrumb mb30">
                            <h4 class="title float-left">Manage users</h4>
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item active" aria-current="page"><a href="<?= url('/admin-course/add-user') ?>" class="view-btn-fill">Add user</a></li>
                            </ol>
                        </nav>
                        <div class="text">
                            Total Users: <?= count($users->result())?>
                            <a href="#" class="text-primary" id="open_mass_member_newsletter_modal_btn">| Send newsletter |</a>
                            <a href="#" class="text-primary" id="open_mass_users_deactivate_modal_btn"> Deactivate |</a>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="top-table-container">
                            <div class="icon-container"><i class="fa fa-users"></i></div>
                            <form action="" method="GET" class="form-search-input">
                                <div class="form-group">
                                    <input type="text" name="search" class="form-control" value="" placeholder="Search...">
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="table-responsive"> <!-- table start-->
                            <table class="table table-striped" id="members_parent_table_container">
                                <thead>
                                    <tr>
                                        <th scope="col"><input type="checkbox" id="mass_member_check_box_input"></th>
                                        <th scope="col">Image</th>
                                        <th scope="col">Employee name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Last login</th>
                                        <th scope="col">Date registered</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="item-table-t">
                                <?php if($users->result()): 
                                foreach($users->result() as $user):    
                                    $image = $user->image ? $user->image : '/courses/images/user/demo.png';
                                ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" data-type="employee" id="<?= $user->id ?>" class="check-box-members-input-btn">
                                        </td>
                                        <td>
                                            <a href="<?= url('/admin-course/user-detail?uid='.$user->id) ?>">
                                                <img src="<?= asset($image) ?>" alt="" class="table-img <?= $user->is_active ? 'online' : 'offline' ?>">
                                            </a>
                                        </td>
                                        <td><?= ucfirst($user->last_name).' '.ucfirst($user->first_name)?></td>
                                        <td><?= $user->email ?></td>
                                      
                                        <td>
                                           <a href="#" data-id="<?= $user->id ?>" data-toggle="modal"  data-target="#exampleModal_deactivate_user_delete" class="course_user_deactivate_btn <?= !$user->is_deactivate ? 'deactivate' : 'delivered'?>"><?= !$user->is_deactivate ? 'Deactivate' : 'Activate'?></a>
                                        </td>
                                        <td><?= date('d M Y', strtotime($user->last_login)) ?></td>
                                        <td><?= date('d M Y', strtotime($user->date)) ?></td>
                                        <td>
                                            <a href="<?= url('/admin-course/user-detail?uid='.$user->id) ?>" title="Edit user"><i class="fa fa-edit"></i></a>
                                           <span class="expand"></span>
                                           <a href="#"  data-toggle="modal"  data-target="#exampleModal_course_user_delete" id="<?= $user->id ?>" class="delete_course_user_btn" title="Delete user"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                         
                                <?php endif; ?>
                                </tbody>
                            </table>
                            <div class="col-lg-12">
                                <!-- pagination -->
                                <?php $users->links(); ?>

                                <?php if(!$users->result()): ?>
                                    <div class="empty-table">There are no user yet!</div>
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





<!-- Modal -->
<div class="sign_up_modal modal fade" id="exampleModal_course_user_delete" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" id="modal_delete_close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="login_form">
                        <form action="#">
                            <div class="heading">
                                <p class="text-center">Do you wish to delete this user?</p>
                                <input type="hidden" id="course_user_delete_id" value="">
                            </div>
                            <button type="button" data-url="<?= url('/admin-course/ajax.php') ?>" id="submit_course_user_delete_btn" class="btn bg-danger btn-log btn-block" style="color: #fff;">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<!-- Modal deactivate-->
<div class="sign_up_modal modal fade" id="exampleModal_deactivate_user_delete" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close modal_dropdown_close" id="modal_delete_close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="login_form">
                        <form action="#">
                            <div class="heading">
                                <p class="text-center">Do you wish to Update this user's status?</p>
                                <input type="hidden" id="course_user_deactivate_id" value="">
                            </div>
                            <button type="button" data-url="<?= url('/admin-course/ajax.php') ?>" id="submit_course_user_deactivate_btn" class="btn bg-primary btn-log btn-block" style="color: #fff;">Proceed</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>













<a href="<?= url('/admin-course/ajax.php') ?>" class="ajax_url_page" style="display: none;"></a>





<?php  include('includes/footer.php') ?>



<script>
$(document).ready(function(){
// ********** OPEN DEACTIVATED MODAL **********//
$(".course_user_deactivate_btn").click(function(e){
    e.preventDefault();
    var id = $(this).attr('data-id')
    $("#course_user_deactivate_id").val(id)
})

// ===========================================
// DEACTIVATE USERS
// ===========================================
$("#submit_course_user_deactivate_btn").click(function(e){
    e.preventDefault();
    var url = $(".ajax_url_page").attr('href');
    var user_id =  $("#course_user_deactivate_id").val()
    $(".preloader-container").show() //show preloader
    $(".modal_dropdown_close").click();

    $.ajax({
        url: url,
        method: "post",
        data: {
            user_id: user_id,
            update_course_user_deactivate: 'update_course_user_deactivate'
        },
        success: function (response){
            var data = JSON.parse(response);
            location.reload();
        },
        error: function(){
            $(".page_alert_danger").show();
            $(".page_alert_danger").html('*Network error, try again later!');
        }
    });
    
});



// ==========================================
// OPEN DELETE USER MODAL
// ==========================================
$(".delete_course_user_btn").click(function(e){
    e.preventDefault();
    var id = $(this).attr('id');
    $("#course_user_delete_id").val(id);
    $('.page_alert_danger').hide();
});





// ========================================
// DELETE USER
// ========================================
$("#submit_course_user_delete_btn").click(function(e){
    e.preventDefault();
     var id = $("#course_user_delete_id").val();
     var url = $(this).attr('data-url');
     $(".preloader-container").show() //show preloader
     $("#modal_delete_close").click();
     

    $.ajax({
		url: url,
		method: 'post',
		data: {
			user_id: id,
			delete_course_user_action: 'delete_course_user_action'
		},
		success: function(response){
            var data = JSON.parse(response);
            if(data.data){
                location.reload();
            }else{
                remove_preloader();
                $('.page_alert_danger').show();
                $('.page_alert_danger').html('*Network error, try again later');
            }
		},
        error: function(){
            $(".page_alert_danger").show();
            $(".page_alert_danger").html('*Network error, try again later!');
        }
	});
});




// ========================================
// REMOVE PRELOADER
// ========================================
function remove_preloader(){
    setTimeout(function(){
        $(".preloader-container").hide();
        $(".alert-success").hide();
    }, 2000);
}










// end
});
</script>