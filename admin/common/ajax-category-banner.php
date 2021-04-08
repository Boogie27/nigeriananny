<?php if($settings->category_banner):?>
    <img src="<?= asset($settings->category_banner) ?>" alt="<?= $settings->app_name?>">
<?php else: ?>
    <a href="#"  class="category_banner_img_update"></a><i class="fa fa-camera"></i></a>
<?php endif; ?>