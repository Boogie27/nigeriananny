

<?php $profile_image = $employee->w_image ? $employee->w_image : '/employee/images/demo.png' ?>
<img src="<?= asset($profile_image) ?>" alt="<?= $employee->first_name ?>" class="acc-img" id="profile_image_img">
<i class="fa fa-camera" id="profile_img_open"></i>
<input type="file" class="profile_img_input" style="display: none;">
<div class="text-danger alert_profile_img text-center"></div>