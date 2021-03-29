





<label for="">Prooduct image</label>
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