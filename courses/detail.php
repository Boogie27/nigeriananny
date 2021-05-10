<?php include('../Connection.php');  ?>


<?php
if(!Input::exists('get') || !Input::get('cid'))
{
    return view('/courses');
}

// ************* GET COURSE *********************//
$course = $connection->select('courses')->where('course_id', Input::get('cid'))->where('is_feature', 1)->first();



// ************* GET RELATED COURSES ************//
$related_courses = $connection->select('courses')->leftJoin('course_categories', 'courses.categories_id', '=', 'course_categories.category_id')->where('categories_id', $course->categories_id)->where('course_id', '!=', Input::get('cid'))->where('is_feature', 1)->get();


// ************* COURSE REVIEWS ****************//
$reviews = $connection->select('course_reviews')->leftJoin('course_users', 'course_reviews.course_user_id', '=', 'course_users.id')->where('course_id', Input::get('cid'))->get();


// ************* MORE COURSES *****************//
$others = $connection->select('courses')->where('course_id', '!=',Input::get('cid'))->where('is_feature', 1)->random()->limit(8)->get();



// ************* USER REVIEW ******************//
$user_review = $connection->select('course_reviews')->where('course_user_id', Auth_course::user('id'))->where('course_id', Input::get('cid'))->first();



// ************* COURSE LIKES *****************//
$course_like = $connection->select('course_likes')->where('like_course_id', Input::get('cid'))->where('likes', 1)->get();


// ************* COURSE DISLIKES *****************//
$course_dislike = $connection->select('course_likes')->where('like_course_id', Input::get('cid'))->where('dislikes', 1)->get();



// ************* SAVED COURSE *****************//
$saved_course = false;
if(Cookie::has('saved_course'))
{
    $old_save = json_decode(Cookie::get('saved_course'), true);
    if(array_key_exists(Input::get('cid'), $old_save))
    {
        $saved_course = true;
    }
}








?>













<?php include('includes/header.php');  ?>


<?php include('includes/navigation.php');  ?>



<div class="page-content-x">
    <div class="row" id="page-expand">
        <div class="col-lg-3" id="side-navigation-container">
            <?php include('includes/side-navigation.php');  ?>
        </div>
        <div class="col-lg-9 body-expand">
            <div class="body-content">
                <div class="single-content">
                   <div class="row">
                        <div class="col-xl-8 col-lg-12"> <!-- video body start -->
                            <?php if(Session::has('success')): ?>
                                <div class="alert alert-success text-center"><?= Session::flash('success') ?></div>
                            <?php endif; ?>
                            <?php if(Session::has('error')): ?>
                                <div class="alert alert-danger text-center"><?= Session::flash('error') ?></div>
                            <?php endif; ?>
                            <div class="course-div">
                                <ul>
                                    <li class="title"><h4><?= ucfirst($course->title)?></h4></li>
                                    <li>Ratings: <?= stars($course->ratings, $course->rating_count) ?></li>
                                    <li class="c-font"><i class="fa fa-comment-o"></i> (<?= $course->rating_count ?>) Reviews </li>
                                </ul>
                                <div class="course-iframe">
                                    <iframe width="560" height="315" src="<?= $course->video_link ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>
                                <ul class="ul-share">
                                    <li>
                                        <a href="#" id="course_save_btn"><i class="fa fa-heart" title="Save video"></i> <span class="saves"><?= $saved_course ? 'Unsave' : 'Save'?></span></a>
                                    </li>
                                    <li>
                                        <a href="#" id="social_share_toggle"><i class="fa fa-share-alt"></i><span> Share</span></a>
                                    </li>
                                    <li>
                                        <a href="#" class="<?= Auth_course::is_loggedin() ? 'course_like_btn' : ''?>"><i class="fa fa-thumbs-up"></i><span class="likes">(<?= count($course_like)?>) likes</span></a>
                                    </li>
                                    <li>
                                        <a href="#" class="<?= Auth_course::is_loggedin() ? 'course_disLike_btn' : ''?>"><i class="fa fa-thumbs-down"></i><span class="likes">(<?= count($course_dislike) ?>) likes</span></a>
                                    </li>
                                    <li class="other-likes float-right">
                                        <?php if(count($course_like) && Auth_course::is_loggedin()): ?>
                                            you and <span><?= count($course_like) ?></span> other like this
                                        <?php endif; ?>
                                    </li>
                                </ul>
                                <ul id="social_share_container">
                                    <li>
                                        <a href="#" class="facebook_share" title="Share on facebook"><i class="fa fa-facebook share-icon"></i></a>
                                        <a href="#" class="twitter_share" title="Share on twitter"><i class="fa fa-twitter share-icon"></i></a>
                                        <a href="#" class="linkedin_share" title="Share on linkedin"><i class="fa fa-linkedin share-icon"></i></a>
                                        <a href="#" class="whatsapp_share" title="Share on whatsapp"><i class="fa fa-whatsapp share-icon"></i></a>
                                    </li>
                                </ul>
                                
                            </div>
                            <div class="course-description"><!-- course description start -->
                                <div class="title"><h4>Description</h4></div>
                                <p><?= $course->description ?></p>
                            </div><!-- course description end -->
                            <div id="item_show_container">
                                <?php if($course->learn): ?>
                                <div class="course-description"><!-- course what you learn start -->
                                    <div class="title"><h4>What you will learn</h4></div>
                                    <ul class="w-y-learn">
                                        <?php $learns = json_decode($course->learn, true);
                                        foreach($learns as $learn):?>
                                            <li><i class="fa fa-check"></i><p><?= $learn ?></p></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div><!-- course what you learn end -->
                                <?php endif; ?>
                                <div class="course-description"><!-- course who the course for start -->
                                    <div class="title"><h4>Who the course is for</h4></div>
                                    <p><?= $course->course_for?></p>
                                </div><!-- course who the course for end -->
                                
                                <div class="course-description"><!-- course size start -->
                                    <div class="title"><h4>Course detail</h4></div>
                                    <ul class="course-download">
                                        <li>Size: <?= $course->course_size?></li>
                                        <li>Duration: <?= $course->duration?></li>
                                        <li>Uploaded on: <?= date('d M Y', strtotime($course->date_posted))?></li>
                                    </ul>
                                </div><!-- course size end -->
                            </div>
                       </div><!-- video body end -->
                       <div class="col-xl-4 col-lg-12"><!-- related video start -->
                            <div class="related-course">
                                <div class="title text-center"><h4>Related courses</h4></div>
                                <div class="related-body">
                                    <?php if(count($related_courses)):?>
                                    <div class="row">
                                        <?php foreach($related_courses as $related):?>
                                        <div class="col-xl-12 col-lg-6 col-md-6 col-sm-12"><!-- related item start -->
                                            <div class="course-item"> 
                                                <div class="course-img">
                                                    <a href="<?= url('/courses/detail.php?cid='.$related->course_id) ?>"><img src="<?= asset($related->course_poster) ?>" alt="<?= $related->title?>"></img></a>
                                                    <span class="duration"><?= $related->duration?></span>
                                                </div> 
                                                <ul class="ul-related">
                                                    <li>
                                                        <?= stars($related->ratings, $related->rating_count) ?>
                                                    </li>
                                                    <li><h4><?= substr($related->title, 0, 25)?></h4></li>
                                                    <li class="text-secondary c-font"><?=$related->category_name ?></li>
                                                    <li class="text-secondary c-font"><i class="fa fa-comment-o"></i> (<?=$related->rating_count ?>) Reviews </li>
                                                </ul>
                                            </div>
                                        </div><!-- related item end -->
                                        <?php endforeach; ?>
                                    </div>
                                    <?php else: ?>
                                   <div class="related-error">
                                        <div class="alert alert-warning text-center">There are no related courses</div>
                                   </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <!-- user login start-->
                            <?php if(!Auth_course::is_loggedin()): ?>
                            <div class="side-login-form">
                                <div class="alert alert-success text-center main_form_alert" id="main_form_alert_success" style="display: none"></div>
                                <div class="alert alert-danger text-center main_form_alert" id="main_form_alert_danger" style="display: none"></div>
                                <form action="<?= current_url()?>" method="POST" id="submit_login_form">
                                    <div class="login-header">
                                        <h4 class="text-center">Login to review course</h4>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <p class="login-detail text-center">Use your social account to login</p>
                                            <div class="socialBtn text-center">
                                                <div class="socialBtn-innner">
                                                    <button type="submit" class="btn login-social-facebook"><i class="fa fa-facebook"></i></button>
                                                </div>
                                                <div class="socialBtn-innner">
                                                    <button type="submit" class="btn login-social-google"><i class="fa fa-google"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <p class="login-detail text-center">Or with email</p>
                                            <div class="form-group">
                                                <div class="form-alert alert_0 text-danger"></div>
                                                <input type="email" name="email" id="email_input" class="form-control" value="" placeholder="Email">
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <div class="form-alert alert_1 text-danger"></div>
                                                <input type="password" name="password" id="password_input" class="form-control" value="" placeholder="Password">
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <input type="checkbox" name="remember_me" id="remember_me_input">
                                                <label for="" class="remember-me">Remember me</label>
                                                <a href="<?= url('/courses/forgot-password')?>" class="forgortPassword text-danger float-right">Forgort password</a>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <input type="hidden" name="detail_side_login" value="true">
                                               <button type="submit"class="btn btn-block btn-primary" id="side_login_btn">Login</button>
                                            </div>
                                        </div>
                                    </div>
                               </form>
                            </div>
                            <?php endif; ?>
                            <!-- user login end -->
                       </div><!-- related video end -->
                       <?php if($course->tutor): 
                        $tutor = json_decode($course->tutor, true);
                        ?>
                        <div class="col-lg-12"><!-- instructor start -->
                            <div class="course-author">
                                <div class="title text-center pb-3"><h4>Instructor</h4></div>
                                <div class="autho-info">
                                    <?php $tutor_image = $tutor["image"] ? $tutor["image"] : "/courses/images/tutor/demo.png"; ?>
                                    <img src="<?= asset($tutor_image) ?>" alt="author-name">
                                    <ul>
                                        <li>
                                            <h4><?= $tutor["name"]?></h4>
                                        </li>
                                        <li class="auto-title"><b><?= $tutor["title"] ?></b></li>
                                        <li><p><?= $tutor["about"]?></p></li>
                                    </ul>
                                </div>
                            </div>
                        </div><!-- student end -->
                        <?php endif; ?>
                        
                        <div class="col-lg-12"><!-- review start -->
                            <div class="start-review">
                                <div class="title"><h4>Student reviews</h4></div>
                                <div class="review-body" id="student_course_review">
                                    <ul>
                                        <li>Ratings:
                                            <?= stars($course->ratings, $course->rating_count) ?>
                                        </li>
                                    </ul>
                                    <?php if(count($reviews)): 
                                        foreach($reviews as $review):    
                                        $user_image = $review->image ? $review->image : '/courses/images/user/demo.png';
                                        ?>
                                        <div class="review-comments"><!-- student review start -->
                                            <img src="<?= asset($user_image) ?>" alt="<?= $review->first_name?>">
                                            <ul>
                                                <li><h5><?= ucfirst($review->last_name.' '.$review->first_name)?></h5></li>
                                                <li>
                                                    <?= ratings($review->review_stars) ?>
                                                    <span class="review-time"><?= date('d M Y', strtotime($review->review_date))?></span>
                                                </li>
                                                <li>
                                                    <p><?= $review->comment ?></p>
                                                </li>
                                                <?php if(Auth_course::user('id') == $review->course_user_id): ?>
                                                    <li class="review-edit">
                                                        <a href="#" id="<?= $review->review_id ?>" class="edit-course-review-btn">Edit</a>
                                                        <a href="#"  data-toggle="modal"  data-target="#exampleModal_delete_course_review" id="delete_course_review_btn">Delete</a>
                                                    </li>
                                                <?php endif; ?>
                                            </ul>
                                        </div><!-- student review end -->
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                    <div class="error-alert">
                                        <div class="error-alert-content">There are no reviews yet!</div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="review-form"><!-- student form end -->
                                <div class="alert alert-success text-center main_form_alert" id="main_form_alert_success" style="display: none"></div>
                                <div class="alert alert-danger text-center main_form_alert" id="main_review_alert_danger" style="display: none;"></div>
                                <div class="title"><h4>Review and rate courses</h4></div>
                                <ul>
                                    <li> Rate:
                                        <i class="fa fa-star star_rating text-secondary ml-2"></i>
                                        <i class="fa fa-star star_rating text-secondary ml-2"></i>
                                        <i class="fa fa-star star_rating text-secondary ml-2"></i>
                                        <i class="fa fa-star star_rating text-secondary ml-2"></i>
                                        <i class="fa fa-star star_rating text-secondary ml-2"></i>
                                        <span class="form-alert alert_2 text-danger"></span>
                                    </li>
                                </ul>
                                <form action="" method="POST" id="review_submit_form">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <textarea name="comment" id="review_comment_input" class="form-control" cols="30" rows="5" placeholder="Write something..."></textarea>
                                                <div class="form-alert alert_3 text-danger"></div>
                                            </div>
                                            <div class="form-group text-right">
                                                <input type="hidden" id="review_star_input" name="star_rate" class="star_rate_input" value="">
                                                <button type="submit" id="review_btn" class="btn button">Review course</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div><!-- student form end -->
                        </div><!-- review end -->
                        <div class="col-lg-12"><!-- more courses container end -->
                           <div class="more-course">
                                <div class="title"><h4>More courses</h4></div>
                                <div class="more-course-body">
                                    <div class="row">
                                        <?php if(count($others)): 
                                        foreach($others as $other):    
                                        ?>
                                        <div class="col-lg-3 col-md-4 col-sm-6 col-6"><!-- more course start -->
                                            <div class="more-course-single">
                                                <a href="<?= url('/courses/detail.php?cid='.$other->course_id) ?>"><img src="<?= asset($other->course_poster) ?>" alt="<?= $other->title ?>"></a>
                                                <ul>
                                                    <li>
                                                        <a href="#"><h4><?= substr(ucfirst($other->title), 0, 30)?></h4></a>
                                                    </li>
                                                    <li>
                                                        <p><?= substr($other->description, 0, 50)?></p>
                                                    </li>
                                                    <li><?= stars($other->ratings, $other->rating_count)?></li>
                                                </ul>
                                            </div>
                                        </div><!--more course end -->
                                        <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                           </div>
                        </div><!-- morder course container end -->
                   </div>
                </div>
            </div>
            <!-- footer -->
            <?php include('includes/footer.php') ?>
        </div>
    </div>
</div>









<!-- *********** EDIT REVIEW MODAL ************** -->
<div class="modal-container main-modal-container">
    <div class="modal-dark-theme">
      <div class="modal-inner">
         <div class="modal-content-small">
            <div class="text-right"><button type="button" class="modal-close main-modal-close"><i class="fa fa-times"></i></button></div>
            <div class="modal-content-main"> <!-- modal content start-->
               <form action="<?= current_url() ?>" method="POST" id="edit_course_review_form">
                    <div class="row">
                       <div class="col-lg-12">
                            <div class="alert alert-danger text-center main_form_alert" id="edit_review_alert_danger" style="display: none;"></div>
                            <div class="title"><h4>Edit course reviews</h4></div>
                            <ul>
                                <li> Rate:
                                    <i class="fa fa-star star_rating text-secondary"></i>
                                    <i class="fa fa-star star_rating text-secondary"></i>
                                    <i class="fa fa-star star_rating text-secondary"></i>
                                    <i class="fa fa-star star_rating text-secondary"></i>
                                    <i class="fa fa-star star_rating text-secondary"></i>
                                    <span class="form-alert alert_4 text-danger"></span>
                                </li>
                            </ul>
                       </div>
                       <div class="col-lg-12">
                            <div class="form-group">
                                <textarea id="edit_review_comment_input" class="form-control" cols="30" rows="5" placeholder="Write something..."></textarea>
                                <div class="form-alert alert_5 text-danger"></div>
                            </div>
                       </div>
                       <div class="col-lg-12">
                            <div class="form-group text-right">
                                <input type="hidden" id="edit_review_star_input" class="edit_star_rate_input" value="">
                                <button type="button" id="submit_edit_review_btn" class="btn button submit_edit_review_btn">Update review</button>
                            </div>
                       </div>
                   </div>
               </form>
            </div><!-- modal content end-->
         </div>
      </div>
    </div>
</div>











<!-- *********** DELETE REVIEW MODAL ************** -->
<div class="sign_up_modal modal fade" id="exampleModal_delete_course_review" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header-x">
                <button type="button" class="close modal_delete_close" id="modal_delete_close" data-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="login_form">
                        <form action="<?= current_url() ?>" mehtod="POST">
                            <div class="heading">
                                <div class="alert alert-danger text-center main_form_alert" id="delete_review_alert_danger" style="display: none;"></div>
                                <p class="text-center">Do you wish to delete this review?</p>
                                <input type="hidden" id="delete_review_id_input" value="<?= Input::get('cid')?>">
                            </div>
                            <button type="button" data-url="<?= url('/admin-nanny/ajax.php') ?>" id="submit_delete_course_review_btn" class="btn bg-danger btn-log btn-block" style="color: #fff;">Delete review</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>













<a href="<?= asset('/courses/ajax.php') ?>" id="app_ajax_url" style="display: none;"></a>
<a href="#" id="course_id_input" data-id="<?= Input::get('cid')?>" style="display: none;"></a>



<script>
$(document).ready(function(){
// ======================================
// SOCIAL SHARE OPEN AND CLOSE
// ======================================
$("#social_share_toggle").click(function(e){
    e.preventDefault();
    $("#social_share_container").slideToggle(100);
});




// =========================================
//SOCIAL MEDIA SHARE BUTTON
// =========================================
var facebook = $(".facebook_share");
var twitter = $(".twitter_share");
var linkedin = $(".linkedin_share");
var whatsapp = $(".whatsapp_share");

var post_url = encodeURI($(location).attr('href'));
var post_title = encodeURI( "Get access to courses on nigeriananny company");

$(facebook).attr('href', `https://www.facebook.com/sharer/sharer.php?u=${post_url}`);
$(twitter).attr('href', `https://twitter.com/share?url=${post_url}&text=${post_title}`);
$(linkedin).attr('href', ` https://www.linkedin.com/shareArticle?url=${post_url}&title=${post_title}`);
$(whatsapp).attr('href', `https://api.whatsapp.com/send?text=${post_title} ${post_url}`);




// ======================================
// STOP ONCLICK VIDEO PLAY/PAUSE
// ======================================
$("#video_input_tag").click(function(e){
    e.preventDefault()
})





// ======================================
// LOGIN
// ======================================
$("#submit_login_form").submit(function(e){
   e.preventDefault();
  
   $(".main_form_alert").hide()
   $(".form-alert").html('');
   $("#side_login_btn").html('Please wait...')
   var url = $("#app_ajax_url").attr('href');
   var email = $("#email_input").val();
   var password = $("#password_input").val();
 
   if(validate_login(email, password)){
        $("#side_login_btn").html('Login')
        return;
   }

   $.ajax({
        url: url,
        method: 'post',
        data: {
            email: email,
            password: password,
            detail_side_login: 'detail_side_login'
        },
        success: function(response){
            var data = JSON.parse(response);
            if(data.error){
                get_error(data.error)
            }else if(data.deactivated){
                $("#main_form_alert_danger").show()
                $("#main_form_alert_danger").html(data.deactivated)
            }else if(data.data){
                location.reload()
            }else{
                general_error()
            }
            $("#side_login_btn").html('Login')
        },
        error: function(){
            general_error()
        }
    });
});





// ************GET FORM ERROR*************
function get_error(error){
   $(".alert_0").html(error.email)
   $(".alert_1").html(error.password)
}


function general_error(){
    $("#side_login_btn").html('Login')
    $("#main_form_alert_danger").show()
    $("#main_form_alert_danger").html("*Something went wrong, try again later!")
}



// ********** VALIDATE LOGIN FORM ***************//

function validate_login(email, password){
    var login_error = false;
    if(email.length == ''){
        login_error = true;
        $(".alert_0").html('*Email field is required')
    }
    if(password.length == ''){
        login_error = true;
        $(".alert_1").html('*Password field is required')
    }else if(password.length > 12){
        login_error = true;
        $(".alert_1").html('*Password must be maximum of 12 characters')
    }else if(password.length < 6){
        login_error = true;
        $(".alert_1").html('*Password must be minimum of 6 characters')
    }

    return login_error;
}









// =======================================
// STAR CLICK EFFECT
// =======================================
var star = $(".star_rating");

function star_effect(){
	$.each(star, function(index, current){
	$(current).click(function(e){
		e.preventDefault();
		for(var i = 0; i < star.length; i++){
			if(i <= index){
				$(star[i]).addClass('text-warning');
				$(".star_rate_input").val(index+1);
			}else{
				$(star[i]).removeClass('text-warning'); 
			}
		}
	    });
    });
}

star_effect();




// *********** REVIEW COURSE *******************
$("#review_submit_form").submit(function(e){
    e.preventDefault();
    review_error = false;
    $(".main_form_alert").hide()
    $(".form-alert").html('')
    $("#review_btn").html('Please wait...')
    var url = $("#app_ajax_url").attr('href');
    var star = $("#review_star_input").val();
    var course_id = $("#course_id_input").attr('data-id');
    var comment = $("#review_comment_input").val();

    if(validate_review_form(comment, star)){
        $("#review_btn").html('Review course')
        return;
    }

    $.ajax({
        url: url,
        method: 'post',
        data: {
            star_rate: star,
            comment: comment,
            course_id: course_id,
            course_review: 'course_review'
        },
        success: function(response){
            var data = JSON.parse(response);
            if(data.login){
                $("#review_btn").html('Review course')
                $("#main_review_alert_danger").show()
                $("#main_review_alert_danger").html(data.login)
            }else if(data.error){
                get_review_error(data.error)
            }else if(data.rated){
                $("#review_btn").html('Review course')
                $("#main_review_alert_danger").show()
                $("#main_review_alert_danger").html(data.rated)
            }else if(data.data){
                get_reviews(data.data)
            }else{
                $("#review_btn").html('Review course')
                $("#main_review_alert_danger").show()
                $("#main_review_alert_danger").html('*Network error, try again later')
            }
        },
        error: function(){
            $("#review_btn").html('Review course')
            $("#main_review_alert_danger").show()
            $("#main_review_alert_danger").html('*Network error, try again later')
        }
    });
});



function get_review_error(error){
    $(".alert_2").html(error.star_rate)
    $(".alert_3").html(error.comment)
}




// ************** VALIDATE REVIEW FORM **************//
function validate_review_form(comment, star){
    var review_error = false;
    if(star == ''){
        review_error = true;
        $(".alert_2").html('*star rate is required')
    }
    if(comment == ''){
        review_error = true;
        $(".alert_3").html('*Comment field is required')
    }else if(comment.length > 500){
        review_error = true;
        $(".alert_3").html('*Comment must be maximum of 500 characters')
    }else if(comment.length < 6){
        review_error = true;
        $(".alert_3").html('*Comment must be minimum of 6 characters')
    }

    return review_error;
}




// *************** GET REVIEWS *******************//
function get_reviews(course_id){
    var url = $("#app_ajax_url").attr('href');
   
    $.ajax({
        url: url,
        method: 'post',
        data: {
            course_id: course_id,
            get_course_reviews: 'get_course_reviews'
        },
        success: function(response){
            $("#review_btn").html('Review course')
            $("#student_course_review").html(response)
            $("#review_comment_input").val('');
            $(".star_rating").removeClass('text-warning')
            $(".star_rating").addClass('text-secondary')
            $("#main_form_alert_success").show()
            $("#main_form_alert_success").html('Course has been rated successfully!')
        }
    })
}



// ************ EDIT COURSE REVIEW MODAL OPEN ******************//
$("#student_course_review").on('click', '.edit-course-review-btn', function(e){
    e.preventDefault();
    $(".little-preloader-container").show();
    var url = $("#app_ajax_url").attr('href');
    var course_id = $("#course_id_input").attr('data-id');
    
    $.ajax({
        url: url,
        method: 'post',
        data: {
            course_id: course_id,
            open_edit_course_review_modal: 'open_edit_course_review_modal'
        },
        success: function(response){
            setTimeout(function(){
                $("#edit_course_review_form").html(response)
                $(".little-preloader-container").hide();
                $(".main-modal-container").show();
            }, 1000)
        }
    })
})




// *********** EDIT REVIEW STAR EFFECT **************//
function edit_star_effect(){
    $("#edit_course_review_form").on('click', '.edit_star_rating', function(){
        var edit_star = $(".edit_star_rating");
        $.each(edit_star, function(index, current){
        $(current).click(function(e){
            e.preventDefault();
            for(var i = 0; i < edit_star.length; i++){
                if(i <= index){
                    $(edit_star[i]).removeClass('text-secondary'); 
                    $(edit_star[i]).addClass('text-warning');
                    $("#edit_review_star_input").val(index+1);
                }else{
                    $(edit_star[i]).removeClass('text-warning');
                    $(edit_star[i]).addClass('text-secondary'); 
                }
            }
            });
        });
    })
}
edit_star_effect();




// ************* UPDATE COURSE REVIEWS *****************//
$("#edit_course_review_form").on('click', '.submit_edit_review_btn', function(e){
    e.preventDefault()
    $(".main_form_alert").hide()
    $(".form-alert").html('')
    $(".submit_edit_review_btn").html('Please wait...')
    var url = $("#app_ajax_url").attr('href');
    var star = $("#edit_review_star_input").val()
    var comment = $("#edit_review_comment_input").val()
    var course_id = $("#course_id_input").attr('data-id')
     
    if(validate_edit_review(star, comment)){
        $(".submit_edit_review_btn").html('Update review')
        return;
    }

    $.ajax({
        url: url,
        method: 'post',
        data: {
            star_rate: star,
            comment: comment,
            course_id: course_id,
            edit_course_review: 'edit_course_review'
        },
        success: function(response){
            var data = JSON.parse(response);
            if(data.error){
                get_edit_error(data.error)
                $(".submit_edit_review_btn").html('Update review')
            }else if(data.data){
                get_updated_reviews(data.data)
            }
        },
        error: function(){
            $("#edit_review_alert_danger").show();
            $("#edit_review_alert_danger").html('*Network error, try again later')
        }
    })
})



// validate course review modal inputs
function validate_edit_review(star, comment){
    var state = false;
    if(star.length == ''){
        state = true;
        $(".alert_4").html('*star rating is required')
    }
    if(comment.length == ''){
        state = true;
        $(".alert_5").html('*Comment field is required')
    }else if(comment.length > 500){
        state = true;
        $(".alert_5").html('*Comment must be maximum of 500 characters')
    }else if(comment.length < 6){
        state = true;
        $(".alert_5").html('*Comment must be minimum of 6 characters')
    }

    return state;
}


// get edit course review modal error
function get_edit_error(error){
    $(".alert_4").html(error.star_rate)
    $(".alert_5").html(error.comment)
}


// get update reviews
function get_updated_reviews(course_id){
    var url = $("#app_ajax_url").attr('href');
   
    $.ajax({
        url: url,
        method: 'post',
        data: {
            course_id: course_id,
            get_updated_course_reviews: 'get_updated_course_reviews'
        },
        success: function(response){
            $("#student_course_review").html(response)
            $("#edit_review_comment_input").val('');
            $(".edit_star_rating").removeClass('text-warning')
            $(".edit_star_rating").addClass('text-secondary')
            $(".main-modal-close").click()
            $(".modal_delete_close").click()
            $(".submit_edit_review_btn").html('Update review')
            $("#submit_delete_course_review_btn").html('Delete review')
        },
        error: function(){
            $(".submit_edit_review_btn").html('Update review')
            $("#edit_review_alert_danger").show();
            $("#edit_review_alert_danger").html('*Network error, try again later')
        }
   })
}




// ************* OPEN DELETE MODAL *******************//
$("#student_course_review").on('click', '#delete_course_review_btn', function(e){
   
})


// ************* DELETE COURSE REVIEW ***************//
$("#submit_delete_course_review_btn").click(function(e){
    e.preventDefault();
    $("#submit_delete_course_review_btn").html('Please wait...')
    $(".main_form_alert").hide()
    var url = $("#app_ajax_url").attr('href');
    var course_id = $("#delete_review_id_input").val()

    $.ajax({
        url: url,
        method: 'post',
        data: {
            course_id: course_id,
            delete_course_reviews: 'delete_course_reviews'
        },
        success: function(response){
            var data = JSON.parse(response);
            if(data.data){
                get_updated_reviews(data.data)
            }else{
                $("#delete_review_alert_danger").show();
                $("#submit_delete_course_review_btn").html('Delete review')
                $("#delete_review_alert_danger").html('*Network error, try again later')
            }
        },
        error: function(){
            $("#delete_review_alert_danger").show();
            $("#delete_review_alert_danger").html('*Network error, try again later')
        }
   })
})








// ************ COURSE LIKE ******************//
$(".course_like_btn").click(function(e){
    e.preventDefault();
    var url = $("#app_ajax_url").attr('href');
    var course_id = $("#course_id_input").attr('data-id')

    $.ajax({
        url: url,
        method: 'post',
        data: {
            course_id: course_id,
            like_course_action: 'like_course_action'
        },
        success: function(response){
            var data = JSON.parse(response);
            if(data.login){
                location.reload()
            }else if(data.data){
                get_all_likes()
            }
        }
   })
})





// ************ COURSE DISLIKE ******************//
$(".course_disLike_btn").click(function(e){
    e.preventDefault();
    var url = $("#app_ajax_url").attr('href');
    var course_id = $("#course_id_input").attr('data-id')

    $.ajax({
        url: url,
        method: 'post',
        data: {
            course_id: course_id,
            dislike_course_action: 'dislike_course_action'
        },
        success: function(response){
            var data = JSON.parse(response);
            if(data.login){
                location.reload()
            }else if(data.data){
                get_all_likes()
            }
        }
   })
})






// *************** GET ALL LIKES ********************//
function get_all_likes(){
    var url = $("#app_ajax_url").attr('href');
    var course_id = $("#course_id_input").attr('data-id')

    $.ajax({
        url: url,
        method: 'post',
        data: {
            course_id: course_id,
            get_al_likes: 'get_al_likes'
        },
        success: function(response){
            var data = JSON.parse(response);
            $(".course_like_btn").children('.likes').html('('+data.type.like+') likes')
            $(".course_disLike_btn").children('.likes').html('('+data.type.dislike+') likes')

            if(data.type.like > 0){
                $("ul.ul-share .other-likes").html(`you and <span>${data.type.like}</span> other like this`)
            }else{
                $("ul.ul-share .other-likes").html('');
            }
        }
   })
}







// *************** SAVE COURSE ********************//
$("#course_save_btn").click(function(e){
    e.preventDefault();
    var url = $("#app_ajax_url").attr('href');
    var course_id = $("#course_id_input").attr('data-id')
    $(".little-preloader-container").show();

    $.ajax({
        url: url,
        method: 'post',
        data: {
            course_id: course_id,
            save_course_action: 'save_course_action'
        },
        success: function(response){
            var data = JSON.parse(response);
            if(data.unsaved){
                get_saved_course()
                $("#course_save_btn").children('.saves').html('save')
                $(".little-preloader-container").hide();
            }else if(data.data){
                get_saved_course()
                set_preloader('Course saved successfully!')
                $("#course_save_btn").children('.saves').html('Unsave')
            }else{
                set_preloader('Network error, try again later!') 
            }
        },
        error: function(error){
            set_preloader('Network error, try again later!')
        }
   })
})




// *********** GET ALL SAVED COURSE **************//
function get_saved_course(){
    var url = $("#app_ajax_url").attr('href');

    $.ajax({
        url: url,
        method: 'post',
        data: {
            get_all_save_course: 'get_all_save_course'
        },
        success: function(response){
            saved_course_count()
            $("#save_course_ul_dropdown").html(response)
        }
   })
}


// *********** SAVED COURSE COUNT ****************//
function saved_course_count(){
    var url = $("#app_ajax_url").attr('href');

    $.ajax({
        url: url,
        method: 'post',
        data: {
            saved_course_count: 'saved_course_count'
        },
        success: function(response){
            var data = JSON.parse(response);
            $("#saved_course_count").html(data.count ? '('+data.count+')' : '')
        }
    })
}


// ************* SHOW / REMOVE PRELOADER *********//
function set_preloader(string){
    setTimeout(function(){
        get_bottom_alert(string)
        $(".little-preloader-container").hide();
    }, 2000)
}




function get_bottom_alert(string){
    var bottom = '10px';
    if($(window).width() < 567){
        bottom = '5px';
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






