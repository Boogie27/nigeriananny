<?php
$settings = $connection->select('settings')->where('id', 1)->first();
?>


<div class="bottom-footer">
    <ul class="ul-footer">
        <li><a href="<?= url('/') ?>">Find a worker</a></li>
        <li><a href="<?= url('/privacy') ?>">Privacy Policy</a></li>
        <li><a href="<?= url('/terms') ?>">Terms & Conditions</a></li>
        <li><a href="<?= url('/shop') ?>">Market place</a></li>
        <li><a href="<?= url('/courses') ?>">Download courses</a></li>
        <li><a href="<?= url('/contact') ?>">Contact</a></li>
    </ul>
    <div class="rights"><?= $settings->alrights?></div>
</div>

	
<a class="scrollToHome" href="#"><i class="flaticon-up-arrow-1"></i></a>


<a href="<?= url('/ajax.php') ?>" class="ajax_url_page_news_letter" style="display: none;"></a> <!-- ajax url-->



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
    if(!state){
        state = true;
        $(".side-bar-dark-theme").show()
        $(".side-navigation").css({
            left: '-0px',
        });
    }
    //close all child links
    $(".child-drop-down").hide();
});





// *************** CLOSE SIDE NAVIGATION ***********//
$(".side-navigation-close").click(function(e){
    e.preventDefault()
    if(state){
        state = false;
        $(".side-bar-dark-theme").hide()
        $(".side-navigation").css({
            left: '-310px'
        });
    }
   //close all child links
   $(".child-drop-down").hide();
})





// ********** CLICK DARK THEME CLOSE SIDE NAV ***************//
$(".side-bar-dark-theme").click(function(){
    if(state){
        state = false;
        $(".side-bar-dark-theme").hide()
        $(".side-navigation").css({
            left: '-310px'
        });
    }
     //close all child links
   $(".child-drop-down").hide();
})

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





// ********** SIDE NAVIGATION FIXED ON DESKTOP ***************//
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











// end
});
</script>





</body>

<!-- Mirrored from grandetest.com/theme/edumy-html/index2.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 30 Nov 2020 11:05:06 GMT -->
</html>






