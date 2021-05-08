<?php if($settings->sliders):
$sliders = json_decode($settings->sliders, true); 
foreach($sliders as $key => $slider):   
?>
    <div class="col-lg-6 col-md-6 col-sm-6"><!-- banner start-->
        <div class="home-slider-body">
            <a href="#" data-key="<?= $key ?>" data-toggle="modal" data-target="#exampleModal_slide_app_slider_delete" class="slider-banner-delete-btn"><i class="fa fa-times"></i></a>
            <img src="<?= asset($slider['image']) ?>" alt="slider" class="slider-img">
            <ul>
                <li><b>Header: </b><?= $slider['title']?></li>
                <li><b>Paragraph: </b><?= $slider['body']?></li>
                <li><b>Button:</b> <?= $slider['button']?></li>
                <li><b>Link:</b> <span class="text-primary"><?= $slider['link']?></span></li>
            </ul>
        </div>
    </div><!-- banner end-->
<?php endforeach; ?>
<?php endif; ?>
<div class="col-lg-6 col-md-6 col-sm-6"><!-- banner start-->
    <div class="home-slider-body text-center">
            <div class="icon-camera">
            <a href="#" data-toggle="modal" data-target="#exampleModal_add_app_slider_open" class="slider-banner-icon"><i class="fa fa-camera"></i></a>
            </div>
    </div>
</div><!-- banner end-->