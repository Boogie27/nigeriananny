<!-- 2nd Home Slider -->
<div class="home2-slider">
    <div class="container-fluid p0">
        <div class="row">
            <div class="col-lg-12">
                <div class="main-banner-wrapper">
                    <div class="banner-style-one owl-theme owl-carousel">
                        <?php if($settings->sliders):
                        $sliders = json_decode($settings->sliders, true); 
                        foreach($sliders as $key => $slider):   
                        ?>
                            <div class="slide slide-one sh2" style="background-image: url(<?= asset($slider['image']) ?>);">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-lg-12 text-center">
                                            <h3 class="banner-title"><?= isset($slider['title']) ? $slider['title'] : ''?></h3>
                                            <p><?= isset($slider) ? $slider['body'] : ''?></p>
                                            <div class="btn-block">
                                                <?php if(isset($slider['link']) && isset($slider['button'])): ?>
                                                    <a href="<?= url($slider['link'])?>" class="banner-btn"><?= $slider['button']?></a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <?php else: ?>
                            <div class="slide slide-one sh2" style="background-image: url(images/slider/3.jpg);">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-lg-12 text-center">
                                            <h3 class="banner-title">Find amazing employees</h3>
                                            <p>Explore from the list of amazing employees we provide</p>
                                            <div class="btn-block">
                                                <a href="<?= url('/jobs') ?>" class="banner-btn">Ready to get Started?</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                              <div class="slide slide-one sh2" style="background-image: url(images/slider/1.jpg);">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-lg-12 text-center">
                                            <h3 class="banner-title">Are you looking for your dream job?</h3>
                                            <p>Technology is brining a massive wave of evolution create a new career with us.</p>
                                            <div class="btn-block">
                                                <a href="<?= url('/employee/register')?>" class="banner-btn">Employee register</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="carousel-btn-block banner-carousel-btn">
                        <span class="carousel-btn left-btn"><i class="flaticon-left-arrow left"></i> <span class="left">PR <br> EV</span></span>
                        <span class="carousel-btn right-btn"><span class="right">NE <br> XT</span> <i class="flaticon-right-arrow-1 right"></i></span>
                    </div><!-- /.carousel-btn-block banner-carousel-btn -->
                </div><!-- /.main-banner-wrapper -->
            </div>
        </div>
    </div>
</div>
