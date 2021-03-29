



 <div class="s-inner-img">
    <?php if($banner->footer_logo): ?>
        <img src="<?= asset($banner->footer_logo) ?>" alt="<?= $banner->app_name?>">
    <?php else: ?>
        <img src="<?= asset('/shop/images/header-logo.png') ?>" alt="<?= $banner->app_name?>">
    <?php endif; ?>
</div>
