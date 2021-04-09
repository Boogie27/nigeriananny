<?php if($settings->job_banner):?>
    <img src="<?= asset($settings->job_banner) ?>" alt="<?=$settings->app_name ?>">
<?php else: ?>
    <a href="#" class="home_banner_img_update"><i class="fa fa-camera"></i></a>
<?php endif; ?>