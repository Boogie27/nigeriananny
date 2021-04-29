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