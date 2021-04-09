<?php if($settings->construction_banner):?>
    <img src="<?= asset($settings->construction_banner) ?>" alt="<?=$settings->app_name ?>">
<?php else: ?>
    <a href="#" class="construction_banner_img_update"><i class="fa fa-camera"></i></a>
<?php endif; ?>