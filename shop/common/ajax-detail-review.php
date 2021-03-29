






<!-- review start -->
<?php
$product_reviews = $connection->select('product_review')
->leftJoin('users', 'product_review.p_user_id', '=', 'users.id')->where('product_id', $product_id)->get();
if(count($product_reviews)):
foreach($product_reviews as $product_review):
    $user_image = $product_review->user_image ? $product_review->user_image : '/shop/images/users/demo.png';
?>
    <div class="mbp_first media p-3">
        <img src="<?= url($user_image) ?>" style="border-radius: 50%;" class="mr-3" alt="review1.png">
        <div class="media-body m-3">
            <h4 class="sub_title mt-0"><?= ucfirst($product_review->name) ?>
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