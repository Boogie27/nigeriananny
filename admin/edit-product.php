<?php include('../Connection.php');  ?>

<?php
if(!Admin_auth::is_loggedin())
{
  Session::delete('admin');
  return Redirect::to('login.php');
}



    if(!Input::exists('get') || !Input::get('eid') || !is_numeric(Input::get('eid')))
    {
        return Redirect::to('products.php');
    }

    $product_id = Input::get('eid');
    $categories = $connection->select('shop_categories')->get();

    $products = $connection->select('shop_products')->where('id', $product_id)->first();







    if(Input::post('update_product'))
    {
        if(Token::check())
        {
            $validate = new DB();
        
            $validation = $validate->validate([
                'product_name' => 'required|min:3|max:50',
                'category' => 'required',
                'subcategory' => 'required',
                'product_price' => 'required',
                'product_quantity' => 'required',
                'product_detail' => 'required|min:6|max:2000',
                'description' => 'required|min:6|max:5000',
            ]);

            if(!$validation->passed())
            {
                return back();
            }


            $products = $connection->select('shop_products')->where('id', $product_id)->first();
            if(!$products->big_image)
            {
                Session::errors('errors', ['image' => '*Product image is required!']);
                return back();
            }

            $update = $connection->update('shop_products', [
                'product_name' => Input::get('product_name'),
                'product_category_id' => Input::get('category'),
                'product_subCategory_id' => Input::get('subcategory'),
                'product_price' => Input::get('product_price'),
                'product_quantity' => Input::get('product_quantity'),
                'shipping_fee' => Input::get('shipping_fee'),
                'product_detail' => Input::get('product_detail'),
                'description' => Input::get('description'),
            ])->where('id', $product_id)->save();
    
            if($update)
            {
                Session::flash('success', 'Product has been updated successfully!');
                return back();
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
                       <?php if(Session::has('success')): ?>
                            <div class="alert-success text-center p-3 mb-2"><?= Session::flash('success') ?></div>
                       <?php endif;?>
                        <nav class="breadcrumb_widgets" aria-label="breadcrumb mb30">
                            <h4 class="title float-left">Edit products</h4>
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="<?= url('/admin/add-product.php') ?>" class="btn-fill">Add product</a></li>
                            </ol>
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
                                            <input type="text" name="product_name" class="form-control h50" id="exampleInputText" value="<?= $products->product_name ?? old('product_name') ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="ui_kit_select_box">
                                        <?php  if(isset($errors['category'])) : ?>
                                                <div class="form-alert text-danger"><?= $errors['category']; ?></div>
                                            <?php endif; ?>
                                            <label for="">Category</label>
                                            <select name="category" class="selectpicker custom-select-lg mb-3" id="edit_product_category_btn">
                                                <?php foreach($categories as $category): ?>
                                                    <option value="<?= $category->category_id ?>" <?= $category->category_id == $products->product_category_id ? 'selected' : ''?>><?= $category->category_name ?></option>
                                                <?php endforeach;?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="ui_kit_select_box">
                                            <?php  
                                            $subCategories = $connection->select('shop_subcategories')->where('shop_categories_id', $products->product_category_id)->get();
                                            if(isset($errors['subcategory'])) : ?>
                                                <div class="form-alert text-danger"><?= $errors['subcategory']; ?></div>
                                            <?php endif; ?>
                                            <label for="">Sub category</label>
                                            <select name="subcategory" class="selectpicker custom-select-lg mb-3" id="edit_product_subcategory_btn">
                                                <?php foreach($subCategories as $subCategory): ?>
                                                    <option value="<?= $subCategory->shop_subCategory_id  ?>" <?= $subCategory->shop_subCategory_id  == $products->product_subCategory_id ? 'selected' : ''?>><?= $subCategory->shop_subCategory_name ?></option>
                                                <?php endforeach;?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <?php  if(isset($errors['product_price'])) : ?>
                                                <div class="form-alert text-danger"><?= $errors['product_price']; ?></div>
                                            <?php endif; ?>
                                            <label for="">Product price</label>
                                            <input type="number" min="1" name="product_price" class="form-control h50" id="exampleInputText" value="<?= $products->product_price ?? old('product_price') ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <?php  if(isset($errors['product_quantity'])) : ?>
                                                <div class="form-alert text-danger"><?= $errors['product_quantity']; ?></div>
                                            <?php endif; ?>
                                            <label for="">Product quantity</label>
                                            <input type="number" min="1" name="product_quantity" class="form-control h50" id="exampleInputText" value="<?= $products->product_quantity ?? old('product_quantity') ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <?php  if(isset($errors['shipping_fee'])) : ?>
                                                <div class="form-alert text-danger"><?= $errors['shipping_fee']; ?></div>
                                            <?php endif; ?>
                                            <label for="">Shipping fee</label>
                                            <input type="number" min="1" name="shipping_fee" class="form-control h50" value="<?= $products->shipping_fee ?? old('shipping_fee') ?>">
                                        </div>
                                    </div>
                            
                                    <div class="col-lg-12">
                                        <div class="product-img-container">
                                            <?php  if(isset($errors['image'])) : ?>
                                                <div class="form-alert text-danger"><?= $errors['image']; ?></div>
                                            <?php endif; ?>
                                            <label for="">Product image</label>
                                             <div class="row">
                                                <?php 
                                                if($products->big_image):
                                                    $images  = explode(',', $products->big_image);
                                                    foreach($images as $key => $image):
                                                    ?>
                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-12">
                                                        <div class="img-box">
                                                            <a href="<?= url('/admin/ajax.php') ?>" id="<?= $products->id ?>" data-key="<?= $key ?>" data-toggle="modal" data-target="#product_delete_img" class="delete_edit_product_img"><i class="fa fa-times"></i></a>
                                                            <img src="<?= asset($image) ?>" alt="<?= $products->product_name ?>">
                                                        </div>
                                                    </div>
                                                    <?php endforeach;?>
                                                <?php endif;?>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-12">
                                                    <div class="img-box">
                                                        <input type="file" name="product_image" id="<?= $products->id ?>" class="product_image_input" style="display: none;">
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
                                            <textarea class="form-control" name="product_detail" id="exampleFormControlTextarea1" rows="5"><?= $products->product_detail ?? old('product_detail') ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                       <div class="form-group">
                                            <?php  if(isset($errors['description'])) : ?>
                                                <div class="form-alert text-danger"><?= $errors['description']; ?></div>
                                            <?php endif; ?>
                                            <label for="label">Description:</label>
                                            <textarea id="description" name="description" class="form-control" placeholder="Write something"><?= $products->description ?? old('description') ?></textarea>
                                            <script>
                                                    CKEDITOR.replace( 'description' );
                                            </script> 
                                       </div> 
                                    </div>

                                     <div class="col-lg-12">
                                       <div class="form-group">
                                           <button type="submit" name="update_product" class="btn bg-primary float-right" style="color: #fff;">Update product</button>
                                       </div> 
                                    </div>
                                    <?= csrf_token() ?>
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
                                <input type="hidden" id="product_img_delete_id" value="">
                                <input type="hidden" id="product_img_delete_key" value="">
                            </div>
                            <button type="button" data-url="<?= url('/admin/ajax.php') ?>" id="delete_edit_product_img_btn" class="btn bg-danger btn-log btn-block" style="color: #fff;">Delete</button>
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
// DELETE IMAGE
// =====================================
$('.product-img-container').on('click', '.delete_edit_product_img', function(e){
   e.preventDefault();
   var id = $(this).attr('id');
   var key = $(this).attr('data-key');
  
    $("#product_img_delete_id").val(id);
    $("#product_img_delete_key").val(key);
});



$("#delete_edit_product_img_btn").click(function(e){
    e.preventDefault();
    var url = $(this).attr('data-url');
    var id = $("#product_img_delete_id").val();
    var key = $("#product_img_delete_key").val();

    $.ajax({
		url: url,
		method: 'post',
		data: {
            key: key,
            product_id: id,
			delete_product_img: 'delete_product_img'
		},
		success: function(response){
            $(".product-img-container").html(response)
            $("#modal_product_img_delete_close").click();
		}
	});
});





// ================================================
// GET EDIT PRODUCT IMAGES
// ================================================
function get_images(id){
    var url = $('.ajax_url_tag').attr('href');
    $.ajax({
		url: url,
		method: 'post',
		data: {
            product_id: id,
			get_edit_product_img: 'get_edit_product_img'
		},
		success: function(response){
            $(".product-img-container").html(response)
		}
	});
}




// =================================================
//  UPLOAD PRODUCT IMAGE
// =================================================\
$(".product-img-container").on('change', '.product_image_input', function(e){
    var id = $(this).attr('id');
    var url = $(".ajax_url_tag").attr('href');
    var image = $(".product_image_input");

    var data = new FormData();
    var image = $(image)[0].files[0];

    data.append('image', image);
    data.append('product_id', id);
    data.append('upload_edit_Product_image', true);

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
                get_images(info.data);
           }else{
            $('.edit-alert-img').html('*Something went worng!')
           }
        }
    });
});







// ===========================================
// GET EDIT PRODUCT SUBCATEGORY
// ===========================================
$("#edit_product_category_btn").change(function(e){
    var category_id = $(this).val();
    var url = $(".ajax_url_tag").attr('href');
    var product_id = $(".edit_product_id_btn").attr('id')
 
    $.ajax({
        url: url,
        method: "post",
        data: {
            category_id: category_id,
            get_subCategory: 'get_subCategory'
        },
        success: function (response){
           var info = JSON.parse(response);
           if(info.empty){
               $("#edit_product_subcategory_btn").html(info.empty).selectpicker('refresh');
           }else if(info.data){
            $("#edit_product_subcategory_btn").html(info.data).selectpicker('refresh');
           }
        }
    });
});

	// $('#select_city_container').html(cities.cities).selectpicker('refresh');

// end
});
</script>