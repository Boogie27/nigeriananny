<?php include('../Connection.php');  ?>


<?php
if(!Admin_auth::is_loggedin())
{
  Session::delete('admin');
  return Redirect::to('login.php');
}

if(!Input::exists('get') || !Input::get('cid') || !is_numeric(Input::get('cid')))
{
    return Redirect::to('customer.php');
}


$connection = new DB();
$customer = $connection->select('users')->where('id', Input::get('cid'))->first();
if(!$customer)
{
    return Redirect::to('customer.php');
}




if(Input::post('edit_customer'))
{
    if(Token::check())
    {
        $validate = new DB();
        
        $validation = $validate->validate([
            'first_name' => 'required|min:3|max:50',
            'last_name' => 'required|min:3|max:50',
            'email' =>'required|min:3|max:50',
            'phone' =>'required|min:11|max:11|number',
            'birth_date' =>'required',
            'address' =>'required|min:3|max:200',
            'city' => 'required|max:100',
            'state' => 'required|max:100',
            'country' => 'required|max:100',
            'gender' => 'required',
        ]);

        if($customer->email != Input::get('email'))
        {
            $other_email = $connection->select('users')->where('email', Input::get('email'))->get();
            if(count($other_email))
            {
                Session::errors('errors', ['email' => '*email already exists!']);
                return back();
            }
        }

        if($validation->passed())
        {
        
        $update = $connection->update('users', [
                'first_name' =>  Input::get('first_name'),
                'last_name' =>  Input::get('last_name'),
                'email' => Input::get('email'),
                'phone' => Input::get('phone'),
                'birth_date' => Input::get('birth_date'),
                'address' => Input::get('address'),
                'city' => strtoupper(Input::get('city')),
                'state' => strtoupper(Input::get('state')),
                'country' => strtoupper(Input::get('country')),
                'gender' => Input::get('gender'),
        ])->where('id', $customer->id)->save();

        if($update)
        {
                Session::flash('success', 'Profile has been updated successfully!');
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
                            <h4 class="title float-left">Manage Customer </h4>
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="<?= url('/admin/index.php') ?>">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><a href="<?= url('/admin/customers.php') ?>">Customer</a></li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-lg-12">
                        <div class="edit-product-form">
                            <div class="edit-customer">
                                
                                <div class="p-header">
                                    <!-- <h3 class="text-center">Edit Customer </h3> -->
                                </div>
                                <div class="">
                                    <?php if(Session::has('success')): ?>
                                        <div class="alert-success text-center p-3 mb-2"><?= Session::flash('success') ?></div>
                                    <?php endif;?>
                                    <div class="alert-danger text-center p-3 mb-2 category_alert_danger" style="display: none;"></div>
                                        <form action="<?= current_url() ?>" method="post" class="form-input-single-border">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="c-img-c">
                                                        <?php if($customer->user_image):?>
                                                            <img src="<?= asset($customer->user_image) ?>" alt="">
                                                        <?php else: ?>
                                                            <img src="<?= asset('/shop/images/users/demo.png') ?>" alt="">
                                                        <?php endif;?>
                                                        
                                                        <br> 
                                                       <a href="#"> <i class="fa fa-camera" id="customer_image_icon_btn"></i></a>
                                                        <input type="file" class="customer_image_input" data-id="<?= $customer->id?>" hidden>
                                                        <label for="" class="<?= $customer->is_active ? 'text-success' : 'text-danger' ?>"><?= $customer->is_active ? 'online' : 'offline' ?></label>
                                                        <div class="alert_0 text-danger text-center"></div>
                                                        <br><br>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <?php  if(isset($errors['first_name'])) : ?>
                                                            <div class="form-alert text-danger"><?= $errors['first_name']; ?></div>
                                                        <?php endif; ?>
                                                        <input type="text" name="first_name" class="form-control h50" value="<?= $customer->first_name ?? old('first_name') ?>" placeholder="First name">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <?php  if(isset($errors['last_name'])) : ?>
                                                            <div class="form-alert text-danger"><?= $errors['last_name']; ?></div>
                                                        <?php endif; ?>
                                                        <input type="text" name="last_name" class="form-control h50" value="<?= $customer->last_name ?? old('last_name') ?>" placeholder="Last name">
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <?php  if(isset($errors['email'])) : ?>
                                                            <div class="form-alert text-danger"><?= $errors['email']; ?></div>
                                                        <?php endif; ?>
                                                        <input type="email" name="email" class="form-control h50" value="<?= $customer->email ?? old('email') ?>" placeholder="Email">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <?php  if(isset($errors['phone'])) : ?>
                                                            <div class="form-alert text-danger"><?= $errors['phone']; ?></div>
                                                        <?php endif; ?>
                                                        <input type="text" name="phone" class="form-control h50" value="<?= $customer->phone ?? old('phone') ?>" placeholder="Phone">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                                                    <div class="form-group">
                                                    <?php  if(isset($errors['birth_date'])) : ?>
                                                        <div class="form-alert text-danger"><?= $errors['birth_date']; ?></div>
                                                    <?php endif; ?>
                                                        <input type="date" name="birth_date" class="form-control h50" value="<?= date('Y-m-d', strtotime($customer->birth_date)) ?? old('birth_date') ?>">
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <?php  if(isset($errors['address'])) : ?>
                                                            <div class="form-alert text-danger"><?= $errors['address']; ?></div>
                                                        <?php endif; ?>
                                                        <input type="text" name="address" class="form-control h50" value="<?= $customer->address ?? old('address') ?>" placeholder="Address">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <?php  if(isset($errors['city'])) : ?>
                                                            <div class="form-alert text-danger"><?= $errors['city']; ?></div>
                                                        <?php endif; ?>
                                                        <input type="text" name="city" class="form-control h50" value="<?= $customer->city ?? old('city') ?>" placeholder="City">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <?php  if(isset($errors['state'])) : ?>
                                                            <div class="form-alert text-danger"><?= $errors['state']; ?></div>
                                                        <?php endif; ?>
                                                        <input type="text" name="state" class="form-control h50" value="<?= $customer->state ?? old('state') ?>" placeholder="State">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <?php  if(isset($errors['country'])) : ?>
                                                            <div class="form-alert text-danger"><?= $errors['country']; ?></div>
                                                        <?php endif; ?>
                                                        <input type="text" name="country" class="form-control h50" value="<?= $customer->country ?? old('country') ?>" placeholder="country">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="ui_kit_checkbox">
                                                        <?php  if(isset($errors['gender'])) : ?>
                                                            <div class="form-alert text-danger"><?= $errors['gender']; ?></div>
                                                        <?php endif; ?>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="gender_input custom-control-input" id="gender_input_1" <?= $customer->gender == 'male' ? 'checked' : '' ?>>
                                                            <label class="custom-control-label" for="gender_input_1">Male</label>
                                                        </div>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="gender_input custom-control-input" id="gender_input_2" <?= $customer->gender == 'female' ? 'checked' : '' ?>>
                                                            <label class="custom-control-label" for="gender_input_2">Female</label>
                                                        </div>
                                                        <input type="hidden" class="form_gender_input" name="gender" value="<?= $customer->gender ?? old('gender') ?>">
                                                    </div>	
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="form-group"> <br><br>
                                                        <button type="submit" name="edit_customer" class="btn bg-danger btn-log btn-block h50" style="color: #fff;">Submit</button>                                                 
                                                    </div>
                                                </div>
                                                <?= csrf_token() ?>
                                            </div>
                                        </form>
                                    </div>
                                <br>
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

// =======================================
// GENDER CHECKBOX
// =======================================
var button = $(".gender_input");
$.each($(".gender_input"), function(index, current){
    $(this).click(function(){
        for(var i = 0; i < button.length; i++){
            if(index != i)
            {
               $($(button)[i]).prop('checked', false);
            }
        }
    });
});

// GET CHECKBOX FREE SHIPPING
$(".form_gender_input").val('male');
$("#gender_input_1").click(function(){
    $(this).val('');
    $('.form_gender_input').val('');
    if($(this).prop('checked')){
        $('.form_gender_input').val('male');
    }
});

// GET CHECKBOX LOCAL PICKUP
$("#gender_input_2").click(function(){
    $(this).val('');
    $('.form_gender_input').val('');
    if($(this).prop('checked')){
        $('.form_gender_input').val('female');
    }
});




// ======================================
// OPEN FILE FIELD
// =====================================
$('.c-img-c').on('click', '#customer_image_icon_btn', function(e){
    $('.alert_0').html('');
    $(".customer_image_input").click();
});





// ======================================
// UPLOAD CUSTOMER IMAGE
// ======================================
$('.c-img-c').on('change', ".customer_image_input", function(){
    var id = $(this).attr('data-id');
    var url = $(".ajax_url_tag").attr('href');
    var image = $(".customer_image_input");

    var data = new FormData();
    var image = $(image)[0].files[0];

    data.append('image', image);
    data.append('customer_id', id);
    data.append('upload_customer_image', true);

    $.ajax({
        url: url,
        method: "post",
        data: data,
        contentType: false,
        processData: false,
        success: function (response){
           var info = JSON.parse(response);
           if(info.error){
               $('.alert_0').html(info.error.image)
           }else if(info.data){
                get_images(info.data);
           }else{
            $('.alert_0').show();
            $('.alert_0').html('*Something went worng!')
           }
        console.log(response)
        }
    });
});





function get_images(id){
    var url = $(".ajax_url_tag").attr('href');

    $.ajax({
        url: url,
        method: "post",
        data: {
           customer_id: id,
           get_edit_customer_image: 'get_edit_customer_image'
        },
        success: function (response){
           $(".c-img-c").html(response);
        }
    });
}












});
</script>
