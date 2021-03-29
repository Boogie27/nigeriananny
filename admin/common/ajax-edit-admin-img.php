



<?php if($admin->image):?>
    <img src="<?= asset($admin->image) ?>" alt="<?= $admin->first_name ?>">
<?php else: ?>
    <img src="<?= asset('/admin/images/admin-img/demo.png') ?>" alt="<?= $admin->first_name ?>">
<?php endif;?>

<br> 
<a href="#"> <i class="fa fa-camera" id="customer_image_icon_btn"></i></a>
<input type="file" class="admin_image_input" data-id="<?= $admin->id?>" hidden>
<label for="" class="<?= $admin->is_active ? 'text-success' : 'text-danger' ?>"><?= $admin->is_active ? 'online' : 'offline' ?></label>
<div class="alert_0 text-danger text-center"></div>
<br><br>