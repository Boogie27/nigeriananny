<?php if(count($reviews)): 
    foreach($reviews as $review):
    ?>
    <div class="emp-rev flex-item">
        <?php $review_image = $review->e_image ? $review->e_image : '/employer/images/employer/demo.png';  ?>
        <img src="<?= asset($review_image) ?>" alt="<?= $review->first_name ?>" class="review-img">
        <ul class="info">
            <ul>
                <li>
                    <?= employee_star($review->review_stars)?>
                    <span class="float-right text-success"><?= date('d M Y', strtotime($review->review_date)) ?></span>
                </li>
            </ul>
            <li><b>Title: </b><?= ucfirst($review->title)?></li>
            <li><b>Review: </b><?= ucfirst($review->comment)?>
            </li>
            <?php if($review->r_employer_id == Auth_employer::employer('id')):?>
                <li class="text-right">
                    <a href="#" data-toggle="modal" data-title="<?= $review->title ?>" data-comment="<?= $review->comment?>" data-star="<?= $review->review_stars?>" id="<?= $review->review_id ?>" data-target="#employee_edit_review" class="text-primary employee_star_rating_btn">Edit</a>
                    <a href="#" data-toggle="modal" data-target="#employee_delete_review" id="<?= $review->review_id ?>" class="text-danger delete_employee_review_modal">Delete</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
    <?php endforeach; ?>
<?php endif; ?>