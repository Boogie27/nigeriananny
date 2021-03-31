<?php $profile_image = Cookie::has('testimoial_image') ? Cookie::get('testimoial_image') : '/images/testimonial/demo.png'?>
<img src="<?= asset($profile_image) ?>" alt="name" class="acc-img" id="profile_image_img">
<?php if(Cookie::has('testimoial_image')): ?>
    <i class="fa fa-trash" id="profile_img_delete"></i>
<?php endif; ?>
<i class="fa fa-camera" id="profile_img_open"></i>
<input type="file" class="profile_img_input" style="display: none;">
<div class="text-danger alert_profile_img text-center"></div>