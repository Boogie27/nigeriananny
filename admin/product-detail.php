<?php include('../Connection_Admin.php');  ?>


<?php
if(!Admin_auth::is_loggedin())
{
  Session::delete('admin');
  return Redirect::to('login.php');
}


if(!Input::exists('get') || empty(Input::get('pid')) || !is_numeric(Input::get('pid')))
{
    return Redirect::to('products.php');
}

$connection = new DB();
$product = $connection->select('shop_products')->where('id', Input::get('pid'))->first();

$category =  $connection->select('shop_categories')->where('category_id', $product->product_category_id)->first();

$subCategory =  $connection->select('shop_subcategories')->where('shop_subCategory_id ', $product->product_subCategory_id)->first();


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
                            <h4 class="title float-left">Products Details</h4>
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
                                        <div class="product-detail">
                                            <div class="p-header">
                                                <h3>School bag <span class="money"><?= money($product->product_price)?></span></h3>
                                                <ul>
                                                    <li><b>Rating:</b>
                                                        <?= star($product->star_ratings, $product->total_rating_count)?>
                                                    </li>
                                                    <li><b>Rating count:</b> (<?= $product->total_rating_count ?>)</li>
                                                    <li><b>Date:</b>  <?= date('d M Y', strtotime($product->product_date_added)) ?></li>
                                                    <li><b>Category:</b> <?= $category->category_name ?></li>
                                                    <li><b>Sub category:</b> <?= $subCategory->shop_subCategory_name ?></li>
                                                    <br>
                                                    <li><b>Featured:</b> <span class="<?= $product->product_is_featured ? 'featured' : 'not-featured'?>"><?= $product->product_is_featured ? 'Featured' : 'Not featured'?></span></li>
                                                    <li><b>Quantity:</b> (<?= $product->product_quantity ?>)</li>
                                                    <li><b>Quantity sold:</b> (<?= $product->product_quantity_sold ?>)</li>
                                                    <li><b>Shiiping fee:</b> <span class="text-danger"><?= money($product->shipping_fee) ?></span></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="product-detail">
                                             <div class="d-img">
                                                <img src="<?= asset(image($product->big_image, 0)) ?>" alt="product_image">                                                 
                                             </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="product-img-container">
                                            <label for="">Product image</label>
                                             <div class="row">
                                                <?php if($product->big_image): 
                                                    $product_images = explode(',', $product->big_image);
                                                    foreach($product_images as $key => $product_image):
                                                    ?>
                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-12">
                                                        <div class="img-box">
                                                            <img src="<?= asset($product_image) ?>" alt="product_image">
                                                        </div>
                                                    </div>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                             </div>
                                        </div>
                                    </div>
                                   
                                   
                                    <div class="col-lg-12">
                                        <div class="product-detail">
                                            <label for=""><b>Product detail</b></label>
                                             <p><?= $product->product_detail; ?></p>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="product-detail">
                                            <label for=""><b>Product description</b></label>
                                             <p><?= $product->description; ?></p>
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
                            <p class="color-black2"><?= $banner->alrights?></p>
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







<?php  include('includes/footer.php') ?>



