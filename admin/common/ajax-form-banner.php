<?php if($settings->form_banner):?>
    <img src="<?= asset($settings->form_banner) ?>" alt="<?= $settings->app_name?>">
<?php else: ?>
    <a href="#"  class="form_banner_img_update"></a><i class="fa fa-camera"></i></a>
<?php endif; ?>