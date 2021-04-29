<div class="row">
    <div class="col-lg-12">
        <div class="alert alert-danger text-center main_form_alert" id="edit_review_alert_danger" style="display: none;"></div>
        <div class="title"><h4>Edit course reviews</h4></div>
        <ul>
            <li> Rate:
                <?php 
                    for($i = 0; $i < 5; $i++)
                    {
                        if($i < $user_review->review_stars)
                        {
                            echo '<i class="fa fa-star edit_star_rating text-warning ml-2"></i>';
                        }else{
                            echo '<i class="fa fa-star edit_star_rating text-secondary ml-2"></i>';
                        }
                    }
                ?>
                <span class="form-alert alert_4 text-danger"></span>
            </li>
        </ul>
    </div>
    <div class="col-lg-12">
        <div class="form-group">
            <textarea id="edit_review_comment_input" class="form-control" cols="30" rows="5" placeholder="Write something..."><?= $user_review->comment ?></textarea>
            <div class="form-alert alert_5 text-danger"></div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="form-group text-right">
            <input type="hidden" id="edit_review_star_input" class="edit_star_rate_input" value="<?=$user_review->review_stars ?>">
            <button type="button" id="submit_edit_review_btn" class="btn button submit_edit_review_btn">Update review</button>
        </div>
    </div>
</div>