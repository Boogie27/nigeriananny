<?php include('../Connection.php');  ?>
<?php
if(!Admin_auth::is_loggedin())
{
    Session::delete('admin');
    Session::put('old_url', '/admin/add-product');
    return view('/admin/login');
}

    // ******* GET CATEGORIES ******************//
    $categories = $connection->select('shop_categories')->get();



    
    if(Input::post('add_product'))
    {
        $validate = new DB();
       
        $validation = $validate->validate([
            'product_name' => 'required|min:3|max:50',
            'category' => 'required',
            'subcategory' => 'required',
            'product_price' => 'required',
            'product_quantity' => 'required',
            'shipping_fee' => 'required',
            'product_detail' => 'required|min:6|max:2000',
            'description' => 'required|min:6|max:5000',
        ]);

        if(!Cookie::has('product_image'))
        {
            Session::errors('errors', ['image' => '*Product image is required!']);
            return back();
        }
        
        $store_image = json_decode(Cookie::get('product_image'), true);
        $image = implode(',', $store_image);

        $create = $connection->create('shop_products', [
            'product_name' => Input::get('product_name'),
            'product_category_id' => Input::get('category'),
            'product_subCategory_id' => Input::get('subcategory'),
            'product_price' => Input::get('product_price'),
            'product_quantity' => Input::get('product_quantity'),
            'shipping_fee' => Input::get('shipping_fee'),
            'product_detail' => Input::get('product_detail'),
            'description' => Input::get('description'),
            'big_image' => $image,
        ]);

        if($create)
        {
            Cookie::delete('product_image');
            Session::flash('success', 'Product has been add successfully!');
            return back();
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
                       <?php if(Session::has('success')): ?>
                            <div class="alert alert-success text-center p-3 mb-2"><?= Session::flash('success') ?></div>
                       <?php endif;?>
                        <nav class="breadcrumb_widgets" aria-label="breadcrumb mb30">
                            <h4 class="title float-left">Add products</h4>
                        </nav>
                    </div>
                    <div class="col-lg-12">
                        <div class="edit-product-form">
                            <form action="<?= current_url() ?>" method="post">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <?php  if(isset($errors['product_name'])) : ?>
                                                <div class="form-alert text-danger"><?= $errors['product_name']; ?></div>
                                            <?php endif; ?>
                                            <label for="">Product name</label>
                                            <input type="text" name="product_name" class="form-control h50" id="exampleInputText" value="<?= old('product_name') ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="ui_kit_select_box">
                                        <?php  if(isset($errors['category'])) : ?>
                                                <div class="form-alert text-danger"><?= $errors['category']; ?></div>
                                            <?php endif; ?>
                                            <label for="">Category</label>
                                            <select name="category" class="selectpicker custom-select-lg mb-3" id="add_product_category_btn">
                                                <?php foreach($categories as $category): ?>
                                                    <option value="<?= $category->category_id ?>"><?= $category->category_name ?></option>
                                                <?php endforeach;?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="ui_kit_select_box">
                                            <?php  if(isset($errors['subcategory'])) : ?>
                                                <div class="form-alert text-danger"><?= $errors['subcategory']; ?></div>
                                            <?php endif; ?>
                                            <label for="">Sub category</label>
                                            <select name="subcategory" class="selectpicker custom-select-lg mb-3" id="add_product_subcategory_btn">
                                                <option value="">Select</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <?php  if(isset($errors['product_price'])) : ?>
                                                <div class="form-alert text-danger"><?= $errors['product_price']; ?></div>
                                            <?php endif; ?>
                                            <label for="">Product price</label>
                                            <input type="number" min="1" name="product_price" class="form-control h50" id="exampleInputText" value="<?= old('product_price') ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <?php  if(isset($errors['product_quantity'])) : ?>
                                                <div class="form-alert text-danger"><?= $errors['product_quantity']; ?></div>
                                            <?php endif; ?>
                                            <label for="">Product quantity</label>
                                            <input type="number" min="1" name="product_quantity" class="form-control h50" id="exampleInputText" value="<?= old('product_quantity') ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <?php  if(isset($errors['shipping_fee'])) : ?>
                                                <div class="form-alert text-danger"><?= $errors['shipping_fee']; ?></div>
                                            <?php endif; ?>
                                            <label for="">Shipping fee</label>
                                            <input type="number" min="1" name="shipping_fee" class="form-control h50" value="<?= old('shipping_fee') ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="product-img-container">
                                            <?php  if(isset($errors['image'])) : ?>
                                                <div class="form-alert text-danger"><?= $errors['image']; ?></div>
                                            <?php endif; ?>
                                            <label for="">Product image</label>
                                             <div class="row">
                                                <?php if(Cookie::has('product_image')): 
                                                    $product_images = json_decode(Cookie::get('product_image'), true);
                                                    foreach($product_images as $key => $product_image):
                                                    ?>
                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-12">
                                                        <div class="img-box">
                                                            <a href="<?= url('/admin/ajax.php') ?>" id="<?= $key ?>" data-toggle="modal" data-target="#product_delete_img" class="delete_add_product_img"><i class="fa fa-times"></i></a>
                                                            <img src="<?= asset($product_image) ?>" alt="product_image">
                                                        </div>
                                                    </div>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-12">
                                                    <div class="img-box">
                                                        <input type="file" name="product_image" id="" class="product_image_input" style="display: none;">
                                                        <div class="add-box product_image_btn"><i class="fa fa-plus"></i></div>
                                                    </div>
                                                    <div class="edit-alert-img text-danger"></div>
                                                </div>
                                             </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12"><br>
                                        <div class="form-group">
                                            <?php  if(isset($errors['product_detail'])) : ?>
                                                <div class="form-alert text-danger"><?= $errors['product_detail']; ?></div>
                                            <?php endif; ?>
                                            <h5>Details</h5>
                                            <textarea class="form-control" name="product_detail" id="exampleFormControlTextarea1" rows="5"><?= old('product_detail') ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                       <div class="form-group">
                                            <?php  if(isset($errors['description'])) : ?>
                                                <div class="form-alert text-danger"><?= $errors['description']; ?></div>
                                            <?php endif; ?>
                                            <label for="label">Description:</label>
                                            <textarea id="description" name="description" class="form-control" placeholder="Write something"><?= old('description') ?></textarea>
                                            <script>
                                                    CKEDITOR.replace( 'description' );
                                            </script> 
                                       </div> 
                                    </div>

                                     <div class="col-lg-12">
                                       <div class="form-group">
                                           <button type="submit" name="add_product" class="btn bg-primary float-right" style="color: #fff;">Add product</button>
                                       </div> 
                                    </div>

                                </div>
                            </form>
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
<div id="<?= $products->id?>" class="edit_product_id_btn" style="display: none;"></div>




<!-- Modal -->
<div class="sign_up_modal modal fade" id="product_delete_img" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" id="modal_product_img_delete_close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="login_form">
                        <form action="#">
                            <div class="heading">
                                <p class="text-center">Do you wish to delete this image?</p>
                                <input type="hidden" id="product_img_delete_key" value="">
                            </div>
                            <button type="button" data-url="<?= url('/admin/ajax.php') ?>" id="delete_add_product_img_btn" class="btn bg-danger btn-log btn-block" style="color: #fff;">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<?php  include('includes/footer.php') ?>



<script>
$(document).ready(function(){

// ======================================
// OPEN FILE FIELD
// =====================================
$(".product-img-container").on('click', '.product_image_btn', function(e){
    $(".product_image_input").click();
    $('.edit-alert-img').html('');
});





// =====================================
// DELETE ADD PRODUCT IMAGE
// =====================================
$('.product-img-container').on('click', '.delete_add_product_img', function(e){
   e.preventDefault();
   var key = $(this).attr('id');
  
    $("#product_img_delete_key").val(key);
});



$("#delete_add_product_img_btn").click(function(e){
    e.preventDefault();
    var url = $(this).attr('data-url');
    var key = $("#product_img_delete_key").val();

    $.ajax({
		url: url,
		method: 'post',
		data: {
            key: key,
			remove_add_product_img: 'remove_add_product_img'
		},
		success: function(response){
            var info = JSON.parse(response);
            if(info.data){
                get_product_images();
                $("#modal_product_img_delete_close").click();
            }else{
                $('.edit-alert-img').html('*Something went worng!')
            }
		}
	});
});





// ================================================
// GET ADD PRODUCT IMAGES
// ================================================
function get_product_images(){
    var url = $('.ajax_url_tag').attr('href');
    $.ajax({
		url: url,
		method: 'post',
		data: {
			get_add_product_img: 'get_add_product_img'
		},
		success: function(response){
            $(".product-img-container").html(response)
		}
	});
}




// =================================================
//  UPLOAD ADD PRODUCT IMAGE
// =================================================\
$(".product-img-container").on('change', '.product_image_input', function(e){
    var url = $(".ajax_url_tag").attr('href');
    var image = $(".product_image_input");

    var data = new FormData();
    var image = $(image)[0].files[0];

    data.append('image', image);
    data.append('upload_add_Product_image', true);

    $.ajax({
        url: url,
        method: "post",
        data: data,
        contentType: false,
        processData: false,
        success: function (response){
           var info = JSON.parse(response);
           if(info.error){
               $('.edit-alert-img').html(info.error)
           }else if(info.data){
                get_product_images();
           }else{
            $('.edit-alert-img').html('*Something went worng!')
           }
        }
    });
});







// ===========================================
// GET ADD PRODUCT SUBCATEGORY
// ===========================================
$("#add_product_category_btn").change(function(e){
    var category_id = $(this).val();
    var url = $(".ajax_url_tag").attr('href');
 
    $.ajax({
        url: url,
        method: "post",
        data: {
            category_id: category_id,
            get_add_subCategory: 'get_add_subCategory'
        },
        success: function (response){
           var info = JSON.parse(response);
           if(info.empty){
               $("#add_product_subcategory_btn").html(info.empty).selectpicker('refresh');
           }else if(info.data){
            $("#add_product_subcategory_btn").html(info.data).selectpicker('refresh');
           }
        }
    });
});

	

// end
});
</script>