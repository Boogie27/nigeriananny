<?php if($settings->cart_banner):?>
    <img src="<?= asset($settings->cart_banner) ?>" alt="<?= $settings->app_name?>">
<?php else: ?>
    <a href="#"  class="cart_banner_img_update"></a><i class="fa fa-camera"></i></a>
<?php endif; ?>