<?php if($settings->checkout_banner):?>
    <img src="<?= asset($settings->checkout_banner) ?>" alt="<?= $settings->app_name?>">
<?php else: ?>
    <a href="#"  class="checkout_banner_img_update"></a><i class="fa fa-camera"></i></a>
<?php endif; ?>