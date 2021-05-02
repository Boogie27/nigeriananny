<?php
$settings = $connection->select('settings')->where('id', 1)->first();
?>



 <!-- little preloader start-->
 <div class="little-preloader-container">
    <div class="little-dark-theme">
        <div class="preloader-back-light">
          <div class="little-p-content">
                <div class="little-loader"></div>
          </div>
        </div>
    </div>
 </div>
<!-- littl preloader end -->



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
    <div class="rights"><?= $settings->alrights?></div>
</div>

	
<a class="scrollToHome" href="#"><i class="flaticon-up-arrow-1"></i></a>


<a href="<?= url('/ajax.php') ?>" id="ajax_url_page" style="display: none;"></a> <!-- ajax url-->



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






// ************** EMPLOYEE LOGOUT ***********//
$("#employee_logout_btn").click(function(e){
    e.preventDefault();
    $(".little-preloader-container").show();
    var url = $("#ajax_url_page").attr('href');

    $.ajax({
        url: url,
        method: 'post',
        data: {
            employee_logout_action: 'employee_logout_action'
        },
        success: function(response){
            var data = JSON.parse(response);
            location.reload()
        }
    });
})





// ************** EMPLOYER LOGOUT ***********//
$("#employer_logout_btn").click(function(e){
    e.preventDefault();
    $(".little-preloader-container").show();
    var url = $("#ajax_url_page").attr('href');

    $.ajax({
        url: url,
        method: 'post',
        data: {
            employer_logout_action: 'employer_logout_action'
        },
        success: function(response){
            var data = JSON.parse(response);
            location.reload()
        }
    });
})




// ************ ASSIGN GENDER FIELD *************//
var client = $(".news-letter-checker");
$.each($(".news-letter-checker"), function(index, current){
    $(this).click(function(){
        for(var i = 0; i < client.length; i++){
            if(index != i)
            {
               $($(client)[i]).prop('checked', false);
            }else{
                $($(client)[i]).prop('checked', true);
            }
        }
    });
});


$(client).click(function(){
    $("#client_type_input").val($(this).val());
});





// *********** SUBSCRIBE TO NEWS LETTER **************//
$("#submit_newsletter_request_btn").click(function(e){
	e.preventDefault();
	$(".alert_news").html('');
	var url = $("#ajax_url_page").attr('href');
	var email = $("#news_letter_email").val();
	var client_type = $("#client_type_input").val();
	$(".news-letter-sub").html('Please wait...');

    if(get_validation(email, client_type)){
        $(".news-letter-sub").html('SUBSCRIBE');
        return;
    }


	$.ajax({
        url: url,
        method: 'post',
        data: {
			email: email,
			client_type: client_type,
            subscribe_news_letter: 'subscribe_news_letter'
        },
        success: function(response){
            var data = JSON.parse(response);
            if(data.error){
				get_newsletter_error(data.error)
			}else if(data.data){
                clear_fields();
                get_bottom_alert('Newsletter subscribed successfully!')
			}
            $(".news-letter-sub").html('SUBSCRIBE');
        },
		error: function(){
            $(".news-letter-sub").html('SUBSCRIBE');
            get_bottom_alert('*Nework error, try again later!')
		}
    });
});




function get_validation(email, client_type){
    var state = false;
    if(email == ''){
        state = true;
        $(".alert_news_1").html('*Email field is required')
    }
    if(client_type == ''){
        state = true;
        $(".alert_news_2").html('*Client field is required')
    }
    return  state;
}



// ********* CLEAR FIELDS ************//
function clear_fields(){
	$("#client_type_input").val('');
	$("#news_letter_email").val('');
	$(".news-letter-checker").prop('checked', false);
}




// ******** NEWS LETTER ERROR ***********//
function get_newsletter_error(error){
	$(".alert_news_1").html(error.email)
	$(".alert_news_2").html(error.client_type)
}




// ******** PAGE BOTTOM ALERT ***********//
function get_bottom_alert(string){
    var bottom = '10px';
    if($(window).width() < 567){
        bottom = '0px';
    }
    $(".page-aliert-bottom .page-alert-content").css({
        bottom: bottom
    })
    $(".page-aliert-bottom .page-alert-content").html(string)
    setTimeout(function(){
        get_bottom_alert(string)
            $(".page-aliert-bottom .page-alert-content").css({
            bottom: '-100px'
        })
    }, 3000)
}




// end
});
</script>





</body>

<!-- Mirrored from grandetest.com/theme/edumy-html/index2.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 30 Nov 2020 11:05:06 GMT -->
</html>






