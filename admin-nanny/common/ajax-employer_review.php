<?php if(count($reviews)):
foreach($reviews as $review):
    $e_image = $review->e_image ? $review->e_image : '/employer/images/demo.png'; 
?>
    <div class="review-body flex">
        <img src="<?= asset($e_image)?>" alt="<?= $reviews->first_name ?>">
        <ul class="ul-review-comment">
            <li>
                Raings:  <?= employee_star($review->review_stars)?>
                <span class="float-right date"><?= date('d M Y', strtotime($review->review_date)) ?></span>
            </li>
            <li><b>Name:</b> <span><?= ucfirst($review->last_name.' '.$review->first_name) ?></span></li>
            <li><b>Title:</b> <span><?= ucfirst($review->title) ?></span></li>
            <li><b>Comment: </b><span><?= $review->comment ?></span></li>
            <li class="text-right"><a href="#" data-id="<?= $review->review_id  ?>" data-toggle="modal" data-target="#modal_employee_delete_review_btn" id="employee_delete_review_btn" class="text-danger">Delete</a></li>
        </ul>
    </div>
<?php endforeach; ?>
<?php else: ?>
    <div class="alert text-center"> There are no reviews yet!</div>
<?php endif; ?>