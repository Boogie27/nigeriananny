



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