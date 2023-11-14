<?php include('../Connection_Admin.php');  ?>


<?php
if(!Admin_auth::is_loggedin())
{
  Session::delete('admin');
  return Redirect::to('login.php');
}

if(!Input::exists('get') || !Input::get('eid') || !is_numeric(Input::get('eid')))
{
    return Redirect::to('category.php');
}


$connection = new DB();
$single_category = $connection->select('shop_categories')->where('category_id', Input::get('eid'))->first();
if(!$single_category)
{
    return Redirect::to('category.php');
}








if(Input::post('edit_category'))
{
    if(Token::check())
    {
        $validate = new DB();
        
        $validation = $validate->validate([
            'category_name' => 'required|min:3|max:50',
            'category_title' => 'required|min:3|max:100',
        ]);
        
        if($validation->passed())
        {
            $update = $connection->update('shop_categories', [
                        'category_name' => Input::get('category_name'),
                        'category_header' => Input::get('category_title')
                    ])->where('category_id', $single_category->category_id)->save(); 
            if($update)
            {
                Session::flash('success', 'Category has been updated successfully!');
                return back();
            }
        }
    }
}





// app banner settings
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
                        <nav class="breadcrumb_widgets" aria-label="breadcrumb mb30">
                            <h4 class="title float-left">Manage Category </h4>
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="<?= url('/admin/index.php') ?>">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><a href="<?= url('/admin/category.php') ?>">Category</a></li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-lg-12">
                        <div class="edit-product-form">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="product-detail">
                                        <div class="p-header">
                                            <h3 class="text-center">Edit Category </h3>
                                            <br>
                                        </div>
                                        <div class="form-center">
                                        <?php if(Session::has('success')): ?>
                                            <div class="alert-success text-center p-3 mb-2"><?= Session::flash('success') ?></div>
                                        <?php endif;?>
                                        <div class="alert-danger text-center p-3 mb-2 category_alert_danger" style="display: none;"></div>
                                            <form action="<?= current_url() ?>" method="post" enctype="multipart/form-data">
                                                 <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="form-group">
                                                            <?php  if(isset($errors['category_name'])) : ?>
                                                                <div class="form-alert text-danger"><?= $errors['category_name']; ?></div>
                                                            <?php endif; ?>
                                                            <input type="text" name="category_name" class="form-control h50" value="<?= $single_category->category_name ?? old('category_name')?>" placeholder="Name">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="form-group">
                                                            <?php  if(isset($errors['category_title'])) : ?>
                                                                <div class="form-alert text-danger"><?= $errors['category_title']; ?></div>
                                                            <?php endif; ?>
                                                            <input type="text" name="category_title" class="form-control h50" value="<?= $single_category->category_header ?? old('category_title')?>" placeholder="Title">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="category-img text-center" id="category_edit_img">
                                                            <img src="<?= asset($single_category->category_image) ?>" alt="<?= $single_category->category_name ?>">
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="alert-category alert_1 text-danger text-center"></div>
                                                            <input type="file"  data-id="<?= $single_category->category_id ?>" id="category_file_input" class="form-control h50" value="" hidden>
                                                            <button type="button" id="category_file_btn" class="category_image_btn h50">Upload image...</button>
                                                        </div>
                                                    </div>
                                                 </div>
                                                <button type="submit" name="edit_category" class="btn bg-danger btn-log btn-block h50" style="color: #fff;">Submit</button>                                                 
                                                <?= csrf_token() ?>
                                            </form>
                                        </div>
                                        <br>
                                    </div>
                                </div>
                            </div>
                        </div>
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












<a href="<?= url('/admin/ajax.php') ?>" class="ajax_url_tag" style="display: none;"></a>





<?php  include('includes/footer.php') ?>


<script>
$(document).ready(function(){

// ======================================
// OPEN FILE FIELD
// =====================================
$('#category_file_btn').click(function(e){
    $("#category_file_input").click();
    $(".alert-category").html('');
    $('.category_alert_danger').hide();
});




// ======================================
// UPLOAD CATEGORY EDIT IMAGE
// ======================================
$("#category_file_input").change(function(){
    var id = $(this).attr('data-id');
    var url = $(".ajax_url_tag").attr('href');
    var image = $("#category_file_input");

    var data = new FormData();
    var image = $(image)[0].files[0];

    data.append('image', image);
    data.append('category_id', id);
    data.append('upload_category_image', true);

    $.ajax({
        url: url,
        method: "post",
        data: data,
        contentType: false,
        processData: false,
        success: function (response){
           var info = JSON.parse(response);
           if(info.error){
               $('.alert-category').html(info.error.image)
           }else if(info.data){
                get_images(info.data);
           }else{
            $('.category_alert_danger').show();
            $('.category_alert_danger').html('*Something went worng!')
           }
        }
    });
});





function get_images(id){
    var url = $(".ajax_url_tag").attr('href');

    $.ajax({
        url: url,
        method: "post",
        data: {
           category_id: id,
           get_edit_category_image: 'get_edit_category_image'
        },
        success: function (response){
           $("#category_edit_img").html(response);
        }
    });
}












});
</script>
