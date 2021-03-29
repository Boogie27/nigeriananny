

<?php
// this $product_id is included from ajax file
 $products = $connection->select('shop_products')->where('id', $product_id)->first();
?>



    <label for="">Prooduct image</label>
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
