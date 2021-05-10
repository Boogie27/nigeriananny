<?php include('../Connection.php');  ?>

<?php include('includes/header.php');  ?>


<?php include('includes/navigation.php');  ?>


<?php
//************ GET ALL VIDEOS ****************** 
$courses = $connection->select('courses')->where('is_feature', 1);


if(Input::exists('get') && Input::get('search'))
{
    $courses->where('title', 'RLIKE',Input::get('search'));
}

$courses->paginate(24);


// ***********CHECK FOR SEARCH ***********//
$page_alert = null;
if(!count($courses->result()))
{
    $page_alert = 'There are no courses in <b>'.Input::get('search').'</b>!';
    $courses = $connection->select('courses')->where('is_feature', 1)->paginate(24);
}else{
    $page_alert = 'Search result in <b>'.Input::get('search').'</b>!';
}


// dd(count($courses->result()));
?>

<div class="page-content-x">
    <div class="row" id="page-expand">
        <div class="col-lg-3" id="side-navigation-container">
            <?php include('includes/side-navigation.php');  ?>
        </div>
        <div class="col-lg-9 body-expand">
            <div class="body-content home-body-content">
                <div class="parent-container">
                    <?php if(Session::has('success')): ?>
                        <div class="alert alert-success text-center"><?= Session::flash('success') ?></div>
                    <?php endif; ?>
                    <?php if($page_alert): ?>
                        <div class="page-alert"><?= $page_alert ?></div>
                    <?php endif; ?>
                    <?php if(count($courses->result())): ?>
                    <div class="row">
                        <?php foreach($courses->result() as $course):?>
                        <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12 expand">
                            <div class="course-img-body"><!-- course start-->
                                <div class="video_image_container">
                                    <a href="<?= url('/courses/detail.php?cid='.$course->course_id) ?>"><img src="<?= asset($course->course_poster)?>" alt="course-name"></a>
                                    <?php if($course->duration):?>
                                        <span class="duration"><?= $course->duration?></span>
                                    <?php endif; ?>
                                </div>
                                <ul>
                                    <li>
                                        <a href="<?= url('/courses/detail.php?cid='.$course->course_id) ?>">
                                           <h4><?= substr($course->title, 0, 50)?></h4>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= url('/courses/detail.php?cid='.$course->course_id) ?>">
                                            <p><?= substr($course->description, 0, 60)?></p>
                                        </a>
                                    </li>
                                    <li>
                                        <?= stars($course->ratings, $course->rating_count) ?>
                                        <span class="views-course"><?= $course->rating_count ?> reviews</span>
                                    </li>
                                </ul>
                            </div><!-- course end-->
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <!-- footer -->
            <?php include('includes/footer.php') ?>
        </div>
    </div>
</div>


