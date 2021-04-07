<?php include('../Connection.php');  ?>





<?php
$connection = new DB();
$products = $connection->select('shop_products')->where('product_quantity', '>=', 1)->where('product_is_featured', 1);


// product search filter from
if(!empty(Input::get('from')))
{
	$from = Input::get('from');
	$products->where('product_price', '>=', $from);
}

// product search to
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


// ============================================
// app banner settings
// ============================================
$banner =  $connection->select('settings')->where('id', 1)->first();


// dd(Session::get('cart'));
?>


<?php require_once('includes/header.php') ?>

<!-- Main Header Nav -->
<?php require_once('includes/navigation.php') ?>
<!-- main header nav end -->

<!-- serach bar -->
<?php require_once('includes/search-bar.php') ?>











	<!-- Inner Page Breadcrumb -->
	<section class="inner_page_breadcrumb" style="background-image: url('<?= asset($banner->home_banner); ?>');">
		<div class="container">
			<div class="row">
				<div class="col-xl-6 offset-xl-3 text-center breadcrumb_content_x">
					<div class="breadcrumb_content">
						<h4 class="page_title">Shop</h4>
						<ol class="breadcrumb">
						    <li class="breadcrumb-item"><a href="#">Home</a></li>
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







	<!-- Our Team Members -->
	<section class="our-team pb50">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
				<div class="col-lg-6 offset-lg-3">
					<div class="text-center">
						<h3 class="mt0">Recently added products</h3>
						<?php if(Session::has('success')): ?>
							<div class="alert alert-success text-center"><?= Session::flash('success'); ?></div>
						<?php endif; ?>
					</div>
					<br><br>
				</div>
					<?php $recentProduct = $connection->select('shop_products')->where('product_quantity', '>=', 1)->where('product_is_featured', 1)->orderBy('product_date_added', 'DESC')->limit(4)->get(); 
					
					?>
					<div class="row">
					<?php foreach($recentProduct as $product):?>
						<div class="col-xl-3 col-lg-4 col-md-6">
							<div class="shop_grid">
								<div class="thumb text-center front_img">
									<a href="detail.php?pid=<?= $product->id ?>">
										<img class="img-fluid" src="<?= url(explode(',', $product->big_image)[0]) ?>" alt="<?= $product->product_name ?>">
									</a>
								</div>
								<div class="details float-left">
									<h4 class="price"><?= money($product->product_price) ?></h4>
									<a href="detail.php?pid=<?= $product->id ?>">
										<h4 class="item-tile"><?= $product->product_name ?></h4>
									</a>
									<ul>
										<?= star($product->star_ratings, $product->total_rating_count) ?>
										<li class="list-inline-item"><a href="#">(<?= $product->star_ratings? $product->star_ratings : 0 ?>)</a></li>
									</ul>
								</div>
								<a href="<?= url('/shop/ajax.php'); ?>" id="<?=$product->id ?>" class="cart_bag float-right ajax_add_to_cart_btn" href="#"><span class="flaticon-shopping-bag"></span></a>
							</div>
						</div>
					<?php endforeach; ?>
					</div>
				</div>
			
			</div>
		</div>
	</section>






	<!-- Our Blog Post -->
	<section class="our-blog">
		<div class="container">
			<div class="row">
				<div class="col-lg-6 offset-lg-3">
					<div class="main-title text-center">
						<h3 class="mt0">Product categories</h3>
						<p>Featured product categories section.</p>
					</div>
				</div>
			</div>
			<?php 
			$ProductCategories = $connection->select('shop_categories')->where('is_category_feature', 1)->get();
			if($ProductCategories):
			?>
			<div class="row">
				<div class="col-lg-12">
					<div class="blog_post_slider_home2">
					<?php foreach($ProductCategories as $category): ?>
						<div class="item">
							<div class="blog_post_home2">
								<div class="bph2_header">
									<img class="img-fluid" src="<?= asset($category->category_image); ?>" alt="<?= $category->category_name ?>">
									<a href="#" class="bph2_date_meta">
										<span class="year"><?= date('d', strtotime($category->category_date_added))?> <br> <?= date('M', strtotime($category->category_date_added))?></span>
									</a>
								</div>
								<div class="details">
									<div class="post_meta">
										<ul>
											<li class="list-inline-item"><a href="<?= url('/shop/category.php?category='.$category->category_slug.'&cid='.$category->category_id); ?>"><i class=""></i><?= ucfirst($category->category_name) ?></a></li>
										</ul>
									</div>
									<h4><?= $category->category_header ?></h4>
								</div>
							</div>
						</div>
						<?php endforeach;?>
					</div>
				</div>
				<?php else: ?>
					<div class="text-center p-3">There are no featured categories yet!</div>
				<?php endif; ?>
			</div>
		</div>
	</section>



	<!-- Our Team Members -->
	<section class="our-team pb50">
		<div class="container">
			<div class="row">
				<div class="col-md-12 col-lg-8 col-xl-9">
				        <div class="feature-header">
							<h3 class="mt10 text-secondary">Featured products</h3>
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
									<a href="detail.php?pid=<?= $product->id ?>">
									    <h4 class="item-tile"><?= $product->product_name ?></h4>
									</a>
									<ul>
								        <?= star($product->star_ratings, $product->total_rating_count) ?>
										<li class="list-inline-item"><a href="#">(<?= $product->star_ratings? $product->star_ratings : 0 ?>)</a></li>
									</ul>
								</div>
								<a href="<?= url('/shop/ajax.php'); ?>" id="<?=$product->id ?>" class="cart_bag float-right ajax_add_to_cart_btn" href="#"><span class="flaticon-shopping-bag"></span></a>
							</div>
						</div>
					<?php endforeach; ?>
						<div class="col-lg-12">
						    <!-- pagination -->
						    <?php $products->links(); ?>
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
							        	<a href="#panelBodyPrice" class="accordion-toggle link fz20 mb15" data-toggle="collapse" data-parent="#accordion">Price</a>
							        </h4>
								  </div>
								<!-- price start-->
								<form action="<?= url('/shop/index.php') ?>" method="GET">
									<div class="row">
										<div class="col-lg-6">
											<div class="form-group">
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
												<button type="submit" class="btn btn-primary">Filter price</button>
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
                                              <div class="bg-danger p-2 text-center" style="color: #fff;">There are no categories yet!</div>
											<?php endif; ?>
							        	</div>
							        </div>
							    </div>
						    </div>
						</div>
					</div>
					<!-- where price was -->
				</div>
			</div>
		</div>
	</section>




	



<!-- footer -->
<?php include('includes/footer.php') ?>



