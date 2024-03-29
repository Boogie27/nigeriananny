<?php include('../Connection.php');  ?>

<?php include('includes/header.php') ?>

<!-- Main Header Nav -->
<?php include('includes/navigation.php') ?>
<!-- main header nav end -->

<!-- serach bar -->
<?php include('includes/search-bar.php') ?>


<?php
$products = $connection->select('shop_products')->where('product_quantity', '>=', 1)->where('product_is_featured', 1);

if(!empty(Input::get('category')))
{
	$category_slug = Input::get('category');
	$category_id = Input::get('cid');
	$products->where('product_category_id', $category_id);
}

if(!empty(Input::get('scid')))
{
	$subCategory_id = Input::get('scid');
	$products->where('product_subCategory_id', $subCategory_id);
}

// price filter from
if(!empty(Input::get('from')))
{
	$from = Input::get('from');
	$products->where('product_price', '>=', $from);
}

// price filter to
if(!empty(Input::get('to')))
{
	$to = Input::get('to');
	$products->where('product_price', '<=', $to);
}

if(!empty(Input::get('search')))
{
	$search = Input::get('search');
	$products->where('product_name', 'RLIKE', $search);
}



$products->paginate(12);

$banner =  $connection->select('settings')->where('id', 1)->first();
?>


	<!-- Inner Page Breadcrumb -->
	<section class="inner_page_breadcrumb" style="background-image: url('<?= asset($banner->category_banner); ?>');">
		<div class="container">
			<div class="row">
				<div class="col-xl-6 offset-xl-3 text-center breadcrumb_content_x">
					<div class="breadcrumb_content">
						<h4 class="breadcrumb_title">Shop</h4>
						<ol class="breadcrumb">
						    <li class="breadcrumb-item"><a href="<?= url('/shop') ?>">Home</a></li>
						    <li class="breadcrumb-item active" aria-current="page">Shop</li>
						</ol>
					</div>
					<div class="banner-icon-x">
						<i class="fa fa-shopping-cart"></i>
						<span class="cart_total_quantity"><?= Session::has('cart') ? Session::get('cart')->_totalQty : 0 ?></span>
					</div>
				</div>
			</div>
		</div>
	</section>



 <!-- <form action="index.php" method="post" enctype="multipart/form-data">
    <input type="file" name="image">
    <button type="submit" name="upload">upload...</button>
</form> -->



	<!-- Our Team Members -->
	<section class="our-team pb50">
		<div class="container">
			<div class="row">
				<div class="col-md-12 col-lg-8 col-xl-9">
					<div class="row">
						<div class="col-sm-6 col-lg-6 col-xl-6">
							<div class="instructor_search_result">
								<p class="mt10 fz15"><span class="color-dark pr10">15 results</span> Showing 1–9 of</p>
							</div>
						</div>
					</div>
				
					<?php 
					if(count($products->result())):
					?>
					<div class="row">
					<?php foreach($products->result() as $product):?>
						<div class="col-sm-6 col-lg-6 col-xl-4">
							<div class="shop_grid">
								<div class="thumb text-center front_img">
									<a href="detail.php?pid=<?= $product->id ?>">
									    <img class="img-fluid" src="<?= url(explode(',', $product->big_image)[0]) ?>" alt="<?= $product->product_name ?>">
                                    </a>
								</div>
								<div class="details float-left">
									<h4 class="price"><?= money($product->product_price) ?></h4>
									<h4 class="item-tile"><?= $product->product_name ?></h4>
									<ul>
									   <?= star($product->star_ratings, $product->total_rating_count) ?>
										<li class="list-inline-item"><a href="#">(<?= $product->star_ratings ? $product->star_ratings : 0 ?>)</a></li>
									</ul>
								</div>
								<a href="<?= url('/shop/ajax.php'); ?>" id="<?=$product->id ?>" class="cart_bag float-right ajax_add_to_cart_btn" href="#"><span class="flaticon-shopping-bag"></span></a>
							</div>
						</div>
					<?php endforeach; ?>
						<div class="col-lg-12">
							<?php $products->links() ?>  <!-- pagination links--> 
						</div>
					</div>
					
					<?php else: ?>
						<div class="alert p-3 text-center">There are no products yet!</div>
					<?php endif; ?>

				</div>
				<div class="col-lg-4 col-xl-3">
					<div class="selected_filter_widget style2 mb30">
					  	<div id="accordion" class="panel-group">
						    <div class="panel">
						      	<div class="panel-heading">
							      	<h4 class="panel-title">
							        	<a href="#panelBodyfilter" class="accordion-toggle link fz20 mb15" data-toggle="collapse" data-parent="#accordion">Selected Filters</a>
							        </h4>
						      	</div>
                                <!-- price start-->
								
                                <form action="" method="GET">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
											    <!-- hidden field start -->
											    <input type="hidden" name="category" class="form-control" value="<?= $category_slug ?>">
												<input type="hidden" name="cid" class="form-control" value="<?= $category_id ?>">
												<input type="hidden" name="scid" class="form-control" value="<?= $subCategory_id ?>">
												<!-- end of hidden field -->
                                                <input type="number" min="1" name="from" class="form-control" value="" placeholder="From">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <input type="number" min="1" name="to" class="form-control" value="" placeholder="To">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <button type="submit"class="btn btn-primary">Filter price</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
								<!-- price end-->
						    </div>
						</div>
					</div>
					<div class="selected_filter_widget style2 mb30">
					  	<div id="accordion" class="panel-group">
						    <div class="panel">
						      	<div class="panel-heading">
							      	<h4 class="panel-title">
							        	<a href="#panelBodySoftware" class="accordion-toggle link fz20 mb15" data-toggle="collapse" data-parent="#accordion">Categories</a>
							        </h4>
						      	</div>
							    <div id="panelBodySoftware" class="panel-collapse collapse show">
							        <div class="panel-body">
                                        <div class="category_sidebar_widget">
                                            <?php $categories = $connection->select('shop_categories')->where('is_category_feature', 1)->limit(12)->get();
                                            if(count($categories)):
                                            ?>
                                            <ul class="category_list">
                                                <?php foreach($categories as $category):?>
                                                    <li><a href="<?= url('/shop/category.php?category='.$category->category_slug.'&cid='.$category->category_id); ?>"><?= $category->category_name ?></a></li>
                                                <?php endforeach; ?>
                                            </ul>
                                            <?php else: ?>
                                            <div class="bg-danger p-2 hidden-center" style="color: #fff;">There are no categories yet!</div>
                                            <?php endif; ?>
							        	</div>
							        </div>
							    </div>
						    </div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>


<!-- footer -->
<?php include('includes/footer.php') ?>