<?php include('../Connection.php');  ?>

<?php
// print_r(Session::get('cart'));
// Session::delete('cart');


    $product_detail = $connection->select('shop_products')->where('id', Input::get('pid'))->where('product_is_featured', 1)
       ->first();

    if(!$product_detail)
    {
        return Redirect::to('404.php');
	}
	
	

	// ADD PRODUCT TO CART
	if(Input::post('add_to_cart'))
	{
		$product_id = Input::get('product_id');
		$quantity = Input::get('quantity');
		$onyYear = date('Y-m-d H:i:s', strtotime('+1year'));
		
		$productToAdd = $connection->select('shop_products')->where('id', $product_id)->where('product_is_featured', 1)
		->first();

		$oldCart = Session::has('cart') ? Session::get('cart') : null;
		$cart = new Cart($oldCart);
		$cart->add($product_id, $productToAdd, $quantity);
		Session::put('cart', $cart);
	
	
		Session::flash('success', 'Item has been added to cart successfully!');
		return back();
	}
?>

<?php include('includes/header.php') ?>

	
<!-- Main Header Nav -->
<?php include('includes/navigation.php') ?>
<!-- main header nav end -->

<!-- serach bar -->
<?php include('includes/search-bar.php') ?>

	<!-- Inner Page Breadcrumb -->
	<section class="inner_page_breadcrumb" style="background-image: url('../shop/images/banner/5.jpg');">
		<div class="container">
			<div class="row">
				<div class="col-xl-6 offset-xl-3 text-center">
					<div class="breadcrumb_content">
						<h4 class="breadcrumb_title">Product Detail</h4>
						<ol class="breadcrumb">
						    <li class="breadcrumb-item"><a href="<?= url('/shop/index.php') ?>">Home</a></li>
						    <li class="breadcrumb-item active" aria-current="page">product detail</li>
						</ol>
					</div>
				</div>
			</div>
		</div>
	</section>

	

	<!-- Shop Single Content -->
	<section class="shop-single-content pb0">
		<div class="container">
			<div class="row">
				<div class="col-lg-6 col-xl-6">
					<div class="single_product_grid">
						<div class="single_product_slider"> 
							<?php $product_images = explode(',', $product_detail->big_image); 
						    if(count($product_images) > 1):
								foreach($product_images as $image):
								?>
								<div class="item">
									<div class="single_product">
										<div class="single_item">
											<div class="thumb"><img class="img-fluid" src="<?= url($image) ?>" alt="ss1.png"></div>
										</div>
										<a class="product_popup popup-img" href="<?= url($image) ?>"><span class="flaticon-zoom-in"></span></a>
									</div>
								</div>
								<?php endforeach; ?>
							<?php endif; ?>
						</div>
					</div>
				</div>
				<div class="col-lg-6 col-xl-6">
					<?php if(Session::has('success')): ?>
					    <div class="alert alert-success text-center app_alert_ajax"><?= Session::flash('success'); ?></div>
					<?php endif; ?>
					<div class="shop_single_product_details">
						<h3 class="title"><?= ucfirst($product_detail->product_name) ?></h3>
						<div class="sspd_review mb20">
							<ul>
							    <?= star($product_detail->star_ratings, $product_detail->total_rating_count)?>
								<li class="list-inline-item"><a href="#">(<?= $product_detail->star_ratings ? $product_detail->star_ratings : 0 ?>)</a></li>
							</ul>
						</div>
						<b>Product detail:</b>
						<p class="mb20"><?= $product_detail->product_detail ?></p>
						<div class="sspd_price mb25"><?= money($product_detail->product_price ) ?></div>
						<form action="<?= current_url() ?>" method="post">
							<ul class="cart_btns ui_kit_button mb30">
								<li class="list-inline-item">
									<input type="number" name="quantity" min="1" value="1">
								</li>
								<?php if($product_detail->product_quantity):?>
								<li class="list-inline-item">
									<input type="hidden" name="product_id" value="<?= $product_detail->id ?>">
									<button type="submit" name="add_to_cart" class="btn"><span class="flaticon-shopping-bag pr5 fz20"></span> Add To Cart</button>
								</li>
								<?php endif; ?>
							</ul>
						</form>
                        <?php 
                            $category = $connection->select('shop_categories')->where('category_id', $product_detail->product_category_id)->first();
                            $subCategory = $connection->select('shop_subcategories')->where('shop_subCategory_id', $product_detail->product_subCategory_id)->first();
                        ?>
						<ul class="sspd_sku mb30">
							<li><a href="#">Category: <?=  ucfirst($category->category_name) ?></a></li>
							<li><a href="#">Sub category: <?= ucfirst($subCategory->shop_subCategory_name)?></a></li>
							<br>
							<li><b class="availability_header">Availability:</b> 
							<?php if($product_detail->product_quantity):?>
							    <span class="alert_available">Available</span>
							<?php else: ?>
				            	<span class="alert_out_of_stock">Out of stock</span>
							<?php endif; ?>
							</li>
						</ul>
						<ul class="sspd_social_icon">
							<li class="list-inline-item">Share:</li>
							<li class="list-inline-item"><a href="#"><i class="fa fa-facebook"></i></a></li>
							<li class="list-inline-item"><a href="#"><i class="fa fa-twitter"></i></a></li>
							<li class="list-inline-item"><a href="#"><i class="fa fa-instagram"></i></a></li>
							<li class="list-inline-item"><a href="#"><i class="fa fa-pinterest"></i></a></li>
							<li class="list-inline-item"><a href="#"><i class="fa fa-dribbble"></i></a></li>
							<li class="list-inline-item"><a href="#"><i class="fa fa-google"></i></a></li>
						</ul>
					</div>
				</div>
				

				<div class="col-lg-12">
					<div class="shop_single_tab_content mt40">
						<ul class="nav nav-tabs justify-content-center" id="myTab" role="tablist">
							<li class="nav-item">
							    <a class="nav-link active" id="description-tab" data-toggle="tab" href="#description" role="tab" aria-controls="description" aria-selected="true">Description</a>
							</li>
							<li class="nav-item">
							    <a class="nav-link" id="review-tab" data-toggle="tab" href="#review" role="tab" aria-controls="review" aria-selected="false">Reviews</a>
							</li>
						</ul>
					<div class="container">
						<!-- review container start -->
						<div class="tab-content" id="myTabContent">
							<div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
								<div class="product_single_content">
									<div class="mbp_pagination_comments">
										<div class="mbp_first media">
											<div class="media-body pb45">
										    	
										    	<p class="mb25 mt10"><?= $product_detail->description ?></p>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="tab-pane fade" id="review" role="tabpanel" aria-labelledby="review-tab">
								<div class="product_single_content">
									<div class="mbp_pagination_comments">
									    <div class="review-container" >
											<!-- review start -->
											<?php
											$product_reviews = $connection->select('product_review')
											->leftJoin('users', 'product_review.p_user_id', '=', 'users.id')->where('product_id', Input::get('pid'))->get();
											if(count($product_reviews)):
											foreach($product_reviews as $product_review):
												$user_image = $product_review->user_image ? $product_review->user_image : '/shop/images/users/demo.png';
											?>
												<div class="mbp_first media p-3" id="review-container">
													<img src="<?= asset($user_image) ?>" style="border-radius: 50%;" class="mr-3" alt="review1.png">
													<div class="media-body m-3">
														<h4 class="sub_title mt-0"><?= ucfirst($product_review->first_name) ?>
															<span class="sspd_review float-right">
																<ul>
																	<?= user_star($product_review->product_stars); ?>
																	<li class="list-inline-item"></li>
																</ul>
															</span>										    		
														</h4>
														<a class="sspd_postdate fz14" href="#"><?= Input::date($product_review->review_date_added, 'd M Y'); ?></a>
														<p class="fz15 mt20"><b><?= ucfirst($product_review->review_title); ?></b></p>
														<p class="fz15 mt25"><?= $product_review->review_comment; ?></p>
													</div>
												</div>
											<div class="custom_hr"></div>
											<?php endforeach; ?>
											<!-- review end -->
											<?php  endif; ?>
										</div><!-- review cintainer end -->
										
										<div class="mbp_comment_form style2">
											<h4>Add Reviews & Rate</h4>
											<ul>
											<div class="row">
												<div class="col-lg-6">
													<div class="alert bg-danger text-center p-3 alert_field_x" style="color: #fff; display: none;"></div>
													<div class="alert bg-success text-center p-3 alert_field_0" style="color: #fff; display: none;"></div>
												</div>
											</div>

												<li class="list-inline-item pr15"><p>What is this product like?</p></li>
												<li class="list-inline-item">
												<div class="text-danger alert_field_1"></div>
													<span class="sspd_review">
														<ul>
															<li class="list-inline-item"><a href="#"><i class="fa fa-star fz18 text-secondary star_rating"></i></a></li>
															<li class="list-inline-item"><a href="#"><i class="fa fa-star fz18 text-secondary star_rating"></i></a></li>
															<li class="list-inline-item"><a href="#"><i class="fa fa-star fz18 text-secondary star_rating"></i></a></li>
															<li class="list-inline-item"><a href="#"><i class="fa fa-star fz18 text-secondary star_rating"></i></a></li>
															<li class="list-inline-item"><a href="#"><i class="fa fa-star fz18 text-secondary star_rating"></i></a></li>
															<!-- <li class="list-inline-item"></li> -->
														</ul>
													</span>
												</li>
											</ul>
											<form class="comments_form">
												<div class="form-group">
												    <div class="alert_field_2 text-danger"></div>
											    	<label for="exampleInputName1">Review Title</label>
											    	<input type="text" class="form-control star_rating_header_field" value="">
												</div>
												<div class="form-group">
												    <div class="alert_field_3 text-danger"></div>
													<label for="exampleFormControlTextarea">Review Content</label>
													<textarea class="form-control star_rating_description_field" cols="30" rows="3"></textarea>
										
													<input type="hidden" name="star_rate" class="star_rate_input" value="">
													<input type="hidden" name="product_id" class="product_id_input" value="<?= $product_detail->id ?>">
												</div>
												<a  href="<?= url('/shop/ajax.php') ?>" class="btn btn-primary product_star_rating_btn" style="color: #fff;">Submit Review <span class="flaticon-right-arrow-1"></span></a>
											</form>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
						<!-- review container start -->
						
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- Our Product -->
	<section class="our-product pb20">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="main-title text-center">
						<h3 class="mb0 mt0">Related Products</h3>
					</div>
				</div>
            </div>
            <?php 
            $related_products = $connection->select('shop_products')->where('product_quantity_sold', '>=', 1)->where('product_category_id', $product_detail->product_category_id)->where('product_is_featured', 1)->limit(6)->get();
            if(count($related_products)):
            ?>
			<div class="row">
				<div class="col-lg-12">
					<div class="shop_product_slider">
                        <?php foreach($related_products as $related_product): ?>
						<div class="item">
							<div class="shop_grid">
								<div class="thumb text-center front_img">
								    <a href="detail.php?pid=<?= $related_product->id ?>">
									    <img class="img-fluid" src="<?= url(image($related_product->big_image, 0)) ?>" alt="<?= $related_product->product_name ?>">
								    </a>
								</div>
								<div class="details float-left">
									<h4 class="price"><?= money($related_product->product_price) ?></h4>
									<h4 class="item-tile">
									    <a href="detail.php?pid=<?= $related_product->id ?>">
									<?= $related_product->product_name ?></h4>
									</a>
									<ul>
								     	<?= star($related_product->star_ratings, $related_product->total_rating_count)?>
										<li class="list-inline-item"><a href="#">(<?= $related_product->star_ratings? $related_product->star_ratings : 0 ?>)</a></li>
									</ul>
								</div>
								<a  href="<?= url('/shop/ajax.php'); ?>" id="<?=$related_product->id ?>" class="cart_bag float-right ajax_add_to_cart_btn"><span class="flaticon-shopping-bag"></span></a>
							</div>
						</div>
                    <?php endforeach; ?>
					</div>
				</div>
            </div>
            <?php endif; ?>
		</div>
    </section>
    









<a href="<?= url('/shop/ajax.php') ?>" id="<?= Input::get('pid') ?>" class="ajax_detail_page_url" style="display: none;"></a>


<!-- footer -->
<?php include('includes/footer.php') ?>




<script>

$(document).ready(function(){

// =======================================
// STAR CLICK EFFECT
// =======================================
var star = $(".star_rating");

function star_effect(){
	$.each(star, function(index, current){
	$(current).click(function(e){
		e.preventDefault();
		for(var i = 0; i < star.length; i++){
			if(i <= index){
				$(star[i]).addClass('text-warning');
				$(".star_rate_input").val(index+1);
			}else{
				$(star[i]).removeClass('text-warning'); 
			}
		}
	    });
    });
}

star_effect();



// =============================================
// GET REVIEWS
// ==============================================
function get_reviews()
{
	var url = $(".ajax_detail_page_url").attr('href');
	var id =  $(".ajax_detail_page_url").attr('id');

	$.ajax({
        url: url,
		method: 'post',
		data: {
			product_id: id,
			get_reviews: 'get_reviews'
		},
		success: function(response){
            $('.review-container').html(response)
		}
	});
}


// =====================================================
// RATE PRODUCT
// =====================================================
$(".product_star_rating_btn").click(function(e){
	e.preventDefault();
    var url = $(this).attr('href');
	var star_rate = $(".star_rate_input").val();
	var  product_id = $(".product_id_input").val();
	var header = $(".star_rating_header_field").val();
	var review = $(".star_rating_description_field").val();
	
	claar_alert() //initalize alert fields;

	$.ajax({
        url: url,
		method: 'post',
		data: {
			product_id: product_id,
			star_rate: star_rate,
			header: header,
			review: review,
			rate_product: 'rate_product'
		},
		success: function(response){
			information = JSON.parse(response);
			if(information.alert){
				preloader();
				$('.alert_field_x').show();
				$('.alert_field_x').html(information.alert.is_loggedin);
				return;
			}
			
			if(information.error){
				preloader();
                $(".alert_field_1").html(information.error.star);
				$(".alert_field_2").html(information.error.title);
				$(".alert_field_3").html(information.error.review);
			}else if(information.data == 'reviewed')
			{
				preloader();
				init();
				get_reviews() //get all reviews
				$('.alert_field_0').html('Product has been review successfully');
				$('.alert_field_0').show();
				remove_alert();
			}
		}
	});

});


function init(){
	$(".star_rate_input").val('');
	$(".star_rating_header_field").val('');
	$(".star_rating_description_field").val('');

	$('.star_rating').removeClass('text-warning');
	$('.star_rating').addClass('text-secondary');
}



function claar_alert(){
	$(".alert_field_1").html('');
	$(".alert_field_2").html('');
	$(".alert_field_3").html('');
	$('.alert_field_x').hide();
	$('.alert_field_0').hide();
}


function remove_alert(){
	setTimeout(function(){
		init();
	}, 5000);
}


function preloader()
{
	claar_alert();
	$(".detail_preloader").show();
	setTimeout(function(){
		$(".detail_preloader").hide();
	}, 1000);
}









});
</script>