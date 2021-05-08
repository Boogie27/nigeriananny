<?php
$settings = $connection->select('settings')->where('id', 1)->first();
?>


<!-- page alert bottom -->
<div class="page-aliert-bottom">
	<div class="page-alert-content">Pending...</div>
</div>





<div class="bottom-footer">
    <ul class="ul-footer">
        <li><a href="<?= url('/') ?>">Find a worker</a></li>
        <li><a href="<?= url('/privacy') ?>">Privacy Policy</a></li>
        <li><a href="<?= url('/terms') ?>">Terms & Conditions</a></li>
        <li><a href="<?= url('/shop') ?>">Market place</a></li>
        <li><a href="<?= url('/courses') ?>">Download courses</a></li>
        <li><a href="<?= url('/contact') ?>">Contact</a></li>
    </ul>
    <ul class="ul-social-media">
        <?php if($settings->facebook): ?>
       <li><a href="<?= $settings->facebook ?>"><i class="fa fa-facebook"></i></a></li>
        <?php endif; ?>
        <?php if($settings->twitter): ?>
       <li><a href="<?=$settings->twitter ?>"><i class="fa fa-twitter"></i></a></li>
       <?php endif; ?>
        <?php if($settings->linkedin): ?>
       <li><a href="<?= $settings->linkedin ?>"><i class="fa fa-linkedin"></i></a></li>
       <?php endif; ?>
        <?php if($settings->instagram): ?>
       <li><a href="<?= $settings->instagram ?>"><i class="fa fa-instagram"></i></a></li>
       <?php endif; ?>
    </ul>
    <div class="rights"><?= $settings->alrights?></div>
</div>





<a class="scrollToHome" href="#"><i class="flaticon-up-arrow-1"></i></a>


<a href="<?= url('/courses/ajax.php') ?>" id="ajax_url_page" style="display: none;"></a> <!-- ajax url-->



</div>
<!-- Wrapper End -->
<script data-cfasync="false" src="https://grandetest.com/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/jquery-3.3.1.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/jquery-migrate-3.0.0.min.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/popper.min.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/jquery.mmenu.all.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/ace-responsive-menu.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/bootstrap-select.min.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/snackbar.min.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/simplebar.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/parallax.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/scrollto.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/jquery-scrolltofixed-min.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/jquery.counterup.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/wow.min.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/progressbar.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/slider.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/timepicker.js"></script>
<!-- Custom script for all pages --> 
<script type="text/javascript" src="<?= SITE_URL ?>/js/script.js"></script>









<script>
$(document).ready(function(){
// ====================================
// OPEN AND CLOSE SIDE NAVIGATION
// ====================================
var state = false;
$(".toggle-side-navigation").click(function(){
    if($(window).width() > 1125) return;
    
    var left = 0;
    if(state){
        left = '-330px';
        state = false;
        $('html, body').css({position: 'static'})
    }else{
        left = '-15px';
        state = true;
        $('html, body').css({position: 'fixed'})
    }
   $(".side-navigation").css({
       left: left,
   });

    //close all child links
    $(".child-drop-down").hide();
});




// ===================================
// OPEN AND CLOSE NAV LINK DROP DOWN
// ===================================
var toggleState = false;
$(".nav-drop-down").click(function(e){
    e.preventDefault();
    if(toggleState)
    {
        toggleState = false;
        $(".side-navigation").css({
            paddingBottom: '0px'
        });
    }else{
        toggleState = true;
        $(".side-navigation").css({
            paddingBottom: '50px'
        });
    }
    $(this).parent().children('.child-drop-down').slideToggle(200);
});




// ===================================
// SIDE NAVIGATION FIXED ON DESKTOP
// ===================================
if($(window).width() > 1125)
{
    $(window).scroll(function(){
        var nav_container = $("#side-navigation-container");
        var nav = $(".side-navigation");
        var navTop = $(nav_container).offset().top;
        
        $(nav).removeClass('activate');
        if($(this).scrollTop() > navTop){
            $(nav).addClass('activate');
        }
    });
}






// ************* CLOSE MODAL ******************
$(".main-modal-close").click(function(e){
    e.preventDefault();
    $(".main-modal-container").hide(150);
});


$(window).click(function(e){
    if($(e.target).hasClass('modal-inner')){
        $(".main-modal-container").hide();
    }
})






// ************** COURSE USERS LOGOUT ***********//
$("#course_user_logout_btn").click(function(e){
    e.preventDefault();
    $(".little-preloader-container").show();
    var url = $("#ajax_url_page").attr('href');

    $.ajax({
        url: url,
        method: 'post',
        data: {
            course_user_logout_action: 'course_user_logout_action'
        },
        success: function(response){
            var data = JSON.parse(response);
            location.reload()
        }
    });
})


// end
});
</script>



</body>

<!-- Mirrored from grandetest.com/theme/edumy-html/index2.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 30 Nov 2020 11:05:06 GMT -->
</html>






