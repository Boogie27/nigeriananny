<?php include('../Connection.php');  ?>


<?php
if(!Input::exists('get') || !Input::get('cid'))
{
    return view('/courses');
}

// ************* GET COURSE *********************//
$course = $connection->select('courses')->where('course_id', Input::get('cid'))->where('is_feature', 1)->first();



// ************* GET RELATED COURSES ************//
$related_courses = $connection->select('courses')->leftJoin('course_categories', 'courses.categories_id', '=', 'course_categories.category_id')->where('categories_id', $course->categories_id)->where('is_feature', 1)->get();


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
                        <div class="col-lg-8"> <!-- video body start -->
                            <div class="course-div">
                                <ul>
                                    <li class="title"><h4>Design a responsive mobile website</h4></li>
                                    <li>Ratings: <?= stars($course->ratings, $course->rating_count) ?></li>
                                    <li class="c-font"><i class="fa fa-comment-o"></i> (25) Reviews </li>
                                </ul>
                                <div class="video-x">
                                    <video src="<?= asset($course->video)?>" id="video_input_tag" class="video-frame" poster="<?= asset($course->course_poster)?>" controls></video>
                                </div>
                                <ul class="ul-share">
                                    <li>
                                        <a href="#"><i class="fa fa-heart" title="Save video"></i></a>
                                    </li>
                                    <li>
                                        <a href="#" id="social_share_toggle"><i class="fa fa-share-alt"></i><span> Share</span></a>
                                    </li>
                                    <li>
                                        <a href="#"><i class="fa fa-thumbs-up"></i></a>
                                    </li>
                                    <li>
                                        <a href="#"><i class="fa fa-thumbs-down"></i></a>
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
                                            <li><i class="fa fa-check"></i> <?= $learn ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div><!-- course what you learn end -->
                                <?php endif; ?>
                                <div class="course-description"><!-- course who the course for start -->
                                    <div class="title"><h4>Who the course is for</h4></div>
                                    <p><?= $course->course_for?></p>
                                </div><!-- course who the course for end -->
                                <div class="course-description"><!-- course size start -->
                                    <div class="title"><h4>Course size</h4></div>
                                    <ul class="course-download">
                                        <li><?= $course->course_size?></li>
                                        <li>Uploaded: <?= date('d M Y', strtotime($course->date_posted))?></li>
                                        <li class="download"><a href="<?= url($course->video)?>" download><i class="fa fa-arrow-down"></i> Download course</a></li>
                                    </ul>
                                </div><!-- course size end -->
                            </div>
                       </div><!-- video body end -->
                       <div class="col-lg-4"><!-- related video start -->
                            <div class="related-course">
                                <div class="title text-center"><h4>Related courses</h4></div>
                                <div class="related-body">
                                    <?php if(count($related_courses)):?>
                                    <div class="row">
                                        <?php foreach($related_courses as $related):?>
                                        <div class="col-lg-12 col-md-6 col-sm-12"><!-- related item start -->
                                            <div class="course-item"> 
                                                <div class="course-img">
                                                    <a href="<?= url('/detail.php?cid='.$related->course_id) ?>"><img src="<?= asset($related->course_poster) ?>" alt="<?= $related->title?>"></img></a>
                                                    <span class="duration"><?= $related->duration?></span>
                                                </div> 
                                                    <ul class="ul-related">
                                                        <li>
                                                            <i class="fa fa-star text-warning"></i>
                                                            <i class="fa fa-star text-warning"></i>
                                                            <i class="fa fa-star text-warning"></i>
                                                            <i class="fa fa-star text-secondary"></i>
                                                            <i class="fa fa-star text-secondary"></i>
                                                        </li>
                                                        <li><h4><?= substr($related->title, 0, 25)?></h4></li>
                                                        <li class="text-secondary c-font"><?=$related->category_name ?></li>
                                                        <li class="text-secondary c-font"><i class="fa fa-comment-o"></i> (25) Reviews </li>
                                                    </ul>
                                                </div>
                                        </div><!-- related item end -->
                                        <?php endforeach; ?>
                                    </div>
                                    <?php else: ?>
                                    <div class="alert alert-warning text-center">There are no related courses</div>
                                    <?php endif; ?>
                                </div>
                            </div>
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
                                        <li><b><?= $tutor["title"] ?></b></li>
                                        <li><?= $tutor["about"]?></li>
                                    </ul>
                                </div>
                            </div>
                        </div><!-- instructor end -->
                        <?php endif; ?>
                        <div class="col-lg-12"><!-- review start -->
                            <div class="start-review">
                                <div class="title"><h4>Student reviews</h4></div>
                                <div class="review-body">
                                    <ul>
                                        <li>Ratings:
                                            <i class="fa fa-star text-warning"></i>
                                            <i class="fa fa-star text-warning"></i>
                                            <i class="fa fa-star text-warning"></i>
                                            <i class="fa fa-star text-secondary"></i>
                                            <i class="fa fa-star text-secondary"></i>
                                        </li>
                                    </ul>
                                    <div class="review-comments"><!-- student review start -->
                                        <img src="<?= asset('/employee/images/demo.png') ?>" alt="author-name">
                                        <ul>
                                            <li><h5>anonye charles</h5></li>
                                            <li>
                                                <i class="fa fa-star text-warning"></i>
                                                <i class="fa fa-star text-warning"></i>
                                                <i class="fa fa-star text-warning"></i>
                                                <i class="fa fa-star text-secondary"></i>
                                                <i class="fa fa-star text-secondary"></i>
                                                <span class="review-time">2 march 2021</span>
                                            </li>
                                            <li>
                                                <p>
                                                    This course delivers what it promises (hands-on approach) and more 
                                                    (in the form of bonuses and additional references). Recommended for people 
                                                    who know the theory but seriously lack the implementation practice
                                                </p>
                                            </li>
                                            <li class="review-edit">
                                                <a href="#">Edit</a>
                                                <a href="#">Delete</a>
                                            </li>
                                        </ul>
                                    </div><!-- student review end -->
                                    <div class="review-comments"><!-- student review start -->
                                        <img src="<?= asset('/employee/images/demo.png') ?>" alt="author-name">
                                        <ul>
                                            <li><h5>anonye charles</h5></li>
                                            <li>
                                                <i class="fa fa-star text-warning"></i>
                                                <i class="fa fa-star text-warning"></i>
                                                <i class="fa fa-star text-warning"></i>
                                                <i class="fa fa-star text-secondary"></i>
                                                <i class="fa fa-star text-secondary"></i>
                                                <span class="review-time">2 march 2021</span>
                                            </li>
                                            <li>
                                                <p>
                                                    This course delivers what it promises (hands-on approach) and more 
                                                    (in the form of bonuses and additional references). Recommended for people 
                                                    who know the theory but seriously lack the implementation practice
                                                </p>
                                            </li>
                                        </ul>
                                    </div><!-- student review end -->
                                </div>
                            </div>
                            <div class="review-form"><!-- student form end -->
                                <div class="title"><h4>Review and rate courses</h4></div>
                                <ul>
                                    <li>
                                        <i class="fa fa-star text-warning"></i>
                                        <i class="fa fa-star text-warning"></i>
                                        <i class="fa fa-star text-warning"></i>
                                        <i class="fa fa-star text-secondary"></i>
                                        <i class="fa fa-star text-secondary"></i>
                                    </li>
                                </ul>
                                <form action="" method="POST">
                                    <div class="row">
                                        <div class="col-lg-8 col-md-8">
                                            <div class="form-group">
                                                <textarea id="comment" class="form-control" cols="30" rows="5" placeholder="Write something..."></textarea>
                                            </div>
                                            <div class="form-group text-right">
                                                <button type="submit" class="btn button">Review course</button>
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
                                        <div class="col-lg-3 col-md-4 col-sm-6 col-6"><!-- more course start -->
                                            <div class="more-course-single">
                                                <a href="#"><img src="video/poster/1.png" alt="course-name"></a>
                                                <ul>
                                                    <li>
                                                        <a href="#"><h4>Artificial Intelligence A-Z™:</h4></a>
                                                    </li>
                                                    <li>
                                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, </p>
                                                    </li>
                                                    <li>
                                                        <i class="fa fa-star text-warning"></i>
                                                        <i class="fa fa-star text-warning"></i>
                                                        <i class="fa fa-star text-warning"></i>
                                                        <i class="fa fa-star text-secondary"></i>
                                                        <i class="fa fa-star text-secondary"></i>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div><!--more course end -->
                                        <div class="col-lg-3 col-md-4 col-sm-6 col-6"><!-- more course start -->
                                            <div class="more-course-single">
                                                <a href="#"><img src="video/poster/1.png" alt="course-name"></a>
                                                <ul>
                                                    <li>
                                                        <a href="#"><h4>Artificial Intelligence A-Z™:</h4></a>
                                                    </li>
                                                    <li>
                                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, </p>
                                                    </li>
                                                    <li>
                                                        <i class="fa fa-star text-warning"></i>
                                                        <i class="fa fa-star text-warning"></i>
                                                        <i class="fa fa-star text-warning"></i>
                                                        <i class="fa fa-star text-secondary"></i>
                                                        <i class="fa fa-star text-secondary"></i>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div><!--more course end -->
                                        <div class="col-lg-3 col-md-4 col-sm-6 col-6"><!-- more course start -->
                                            <div class="more-course-single">
                                                <a href="#"><img src="video/poster/1.png" alt="course-name"></a>
                                                <ul>
                                                    <li>
                                                        <a href="#"><h4>Artificial Intelligence A-Z™:</h4></a>
                                                    </li>
                                                    <li>
                                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, </p>
                                                    </li>
                                                    <li>
                                                        <i class="fa fa-star text-warning"></i>
                                                        <i class="fa fa-star text-warning"></i>
                                                        <i class="fa fa-star text-warning"></i>
                                                        <i class="fa fa-star text-secondary"></i>
                                                        <i class="fa fa-star text-secondary"></i>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div><!--more course end -->
                                        <div class="col-lg-3 col-md-4 col-sm-6 col-6"><!-- more course start -->
                                            <div class="more-course-single">
                                                <a href="#"><img src="video/poster/1.png" alt="course-name"></a>
                                                <ul>
                                                    <li>
                                                        <a href="#"><h4><?= substr('Artificial icial icial icial icial Intelligence A-Z™:', 0, 50)?></h4></a>
                                                    </li>
                                                    <li>
                                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, </p>
                                                    </li>
                                                    <li>
                                                        <i class="fa fa-star text-warning"></i>
                                                        <i class="fa fa-star text-warning"></i>
                                                        <i class="fa fa-star text-warning"></i>
                                                        <i class="fa fa-star text-secondary"></i>
                                                        <i class="fa fa-star text-secondary"></i>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div><!--more course end -->
                                        <div class="col-lg-3 col-md-4 col-sm-6 col-6"><!-- more course start -->
                                            <div class="more-course-single">
                                                <a href="#"><img src="video/poster/1.png" alt="course-name"></a>
                                                <ul>
                                                    <li>
                                                        <a href="#"><h4><?= substr('Artificial icial icial icial icial Intelligence A-Z™:', 0, 50)?></h4></a>
                                                    </li>
                                                    <li>
                                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, </p>
                                                    </li>
                                                    <li>
                                                        <i class="fa fa-star text-warning"></i>
                                                        <i class="fa fa-star text-warning"></i>
                                                        <i class="fa fa-star text-warning"></i>
                                                        <i class="fa fa-star text-secondary"></i>
                                                        <i class="fa fa-star text-secondary"></i>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div><!--more course end -->
                                        <div class="col-lg-3 col-md-4 col-sm-6 col-6"><!-- more course start -->
                                            <div class="more-course-single">
                                                <a href="#"><img src="video/poster/1.png" alt="course-name"></a>
                                                <ul>
                                                    <li>
                                                        <a href="#"><h4><?= substr('Artificial icial icial icial icial Intelligence A-Z™:', 0, 50)?></h4></a>
                                                    </li>
                                                    <li>
                                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, </p>
                                                    </li>
                                                    <li>
                                                        <i class="fa fa-star text-warning"></i>
                                                        <i class="fa fa-star text-warning"></i>
                                                        <i class="fa fa-star text-warning"></i>
                                                        <i class="fa fa-star text-secondary"></i>
                                                        <i class="fa fa-star text-secondary"></i>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div><!--more course end -->
                                        <div class="col-lg-3 col-md-4 col-sm-6 col-6"><!-- more course start -->
                                            <div class="more-course-single">
                                                <a href="#"><img src="video/poster/1.png" alt="course-name"></a>
                                                <ul>
                                                    <li>
                                                        <a href="#"><h4><?= substr('Artificial icial icial icial icial Intelligence A-Z™:', 0, 50)?></h4></a>
                                                    </li>
                                                    <li>
                                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, </p>
                                                    </li>
                                                    <li>
                                                        <i class="fa fa-star text-warning"></i>
                                                        <i class="fa fa-star text-warning"></i>
                                                        <i class="fa fa-star text-warning"></i>
                                                        <i class="fa fa-star text-secondary"></i>
                                                        <i class="fa fa-star text-secondary"></i>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div><!--more course end -->
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








<script>
$(document).ready(function(){
// ======================================
// SOCIAL SHARE OPEN AND CLOSE
// ======================================
$("#social_share_toggle").click(function(e){
    e.preventDefault();
    $("#social_share_container").slideToggle(100);
});






// ======================================
// STOP ONCLICK VIDEO PLAY/PAUSE
// ======================================
$("#video_input_tag").click(function(e){
    e.preventDefault()
})




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




// end
});
</script>