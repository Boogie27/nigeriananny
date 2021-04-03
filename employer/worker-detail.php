<?php include('../Connection.php');  ?>

<?php 
if(!Input::exists('get') && !Input::get('jid'))
{
    return Redirect::to('jobs');
}

if(Auth_employer::is_loggedin() && Auth_employer::employer('id'))
{
    $data = false;
    $cookie_expiry = 2419200;
    $w_id = Input::get('jid');
    $jobview = $connection->select('workers')->where('worker_id', Input::get('jid'))->first();
    if($jobview)
    {
        if(Cookie::exists('viewed_job'))
        {
            $oldView = json_decode(Cookie::get('viewed_job'), true);
            if(!array_key_exists($w_id, $oldView))
            {
                $oldView[$w_id] = ['worker_id' => $w_id];

                $updateViews = $connection->update('workers', [
                            'job_views' => $jobview->job_views + 1,
                        ])->where('worker_id', Input::get('jid'))->save();
                if($updateViews)
                {
                    $data = true;
                }
            }
        }else{
            $oldView[$w_id] = ["worker_id" => $w_id];

            $updateViews = $connection->update('workers', [
                        'job_views' => $jobview->job_views + 1,
                    ])->where('worker_id', Input::get('jid'))->save();
            if($updateViews)
            {
                $data = true;
            }
        }
           
        
        if($data)
        {
            $viewd = json_encode($oldView);
            Cookie::put('viewed_job', $viewd, $cookie_expiry);
        }
    }
}




$job = $connection->select('workers')->where('worker_id', Input::get('jid'))->where('is_deactivate', 0)->where('is_job_feature', 1)->first(); 
if(!$job)
{
    return Redirect::to('jobs');
}
?>


<?php include('includes/header.php');  ?>

<!-- top navigation-->
<?php include('includes/top-navigation.php');  ?>

<!-- top navigation-->
<?php include('includes/navigation.php');  ?>

<!-- images/home/4.jpg -->
	

<!-- mobile navigation-->
<?php include('includes/mobile-navigation.php');  ?>




    
	   <!-- jobs  start-->
       <div class="page-content">
           <div class="inner-job-detail">
                <div class="j-header"><b>Job seeker</b></div>
                <div class="j-body">
                    <div class="row">
                        <div class="col-lg-4" id="apply_now_1">
                            <form action="" method="" class="p-apply-container">
                                <div class="apply-h"><h4>Apply here</h4></div>
                                <div class="apply-container">
                                    <div class="row">
                                        <div class="col-lg-12 col-sm-6">
                                            <div class="form-group">
                                                <input type="text" name="first_name" class="form-control h50" placeholder="Frist name">
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-sm-6">
                                            <div class="form-group">
                                                <input type="text" name="last_name" class="form-control h50" placeholder="Last name">
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-sm-6">
                                            <div class="form-group">
                                                <input type="text" name="phone" class="form-control h50" placeholder="Phone number">
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-sm-6">
                                            <div class="form-group">
                                                <div class="ui_kit_select_box">
                                                    <select class="selectpicker custom-select-lg mb-3">
                                                        <option value="">Select qualification</option>
                                                        <option value="Two">Item 2</option>
                                                        <option value="Three">Item 3</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-sm-6">
                                            <div class="form-group">
                                                <div class="ui_kit_select_box">
                                                    <select class="selectpicker custom-select-lg mb-3">
                                                        <option value="">Years of expirience</option>
                                                        <option value="Two">Item 2</option>
                                                        <option value="Three">Item 3</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-sm-6">
                                            <div class="form-group">
                                                <input type="text" name="salary" class="form-control h50" placeholder="Salary expected">
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <textarea name="cover_letter" class="form-control h50" cols="30" rows="5" placeholder="Cover letter"></textarea>
                                                <label for="" class="cv-label">Max 200 characters</label>
                                                <div class="apply_checkbox_d">
                                                    <input type="checkbox" class="">
                                                    <label class="cover_letter_btn" for="cover_letter_btn" style="font-size: 13px;">Save my cover letter to my profile</label>
                                                </div>	
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="apply-container">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group apply_checkbox_d">
                                                <input type="checkbox" class="">
                                                <label class="cover_letter" style="font-size: 13px;">Apply with uploaded CV: <span class="text-primary">developer_cv.pdf</span></label>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-group apply_checkbox_d">
                                                <input type="checkbox" class="attach_cv">
                                                <label class="cover_letter" style="font-size: 13px;">Attach a  CV</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-sm-6">
                                            <div class="form-group">
                                                <input type="file" name="cv" class="form-control cv_input h50">
                                            </div>
                                        </div>
                                        <div class="col-lg-12 apply_now_btn">
                                            <div class="form-group">
                                            <button type="submit" id="submit_apply_side" class="btn-fill">Apply now</button>
                                            <p class="apply-p">
                                                By click 'Apply now', You agree to our <a href="#" class="text-primary">terms & conditions</a>
                                                and <a href="#" class="text-primary">Privacy policy</a>
                                            </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-lg-8">
                            <!-- featured jobs start-->
                           <?php 
                                $savedJob = saved_jobs($job->worker_id);
                                $w_image = $job->w_image ? $job->w_image : '/images/worker/demo.png'; 
                                $amount = !$job->amount_to ? money($job->amount_form) : money($job->amount_form).' - '.money($job->amount_to);?>
                            <div class="job-body">
                                <div class="jobs-info">
                                    <img src="<?= asset($w_image)?>" alt="">
                                    <ul class="ul">
                                        <li>
                                            <h4>
                                                <?= ucfirst($job->job_title)?>
                                                <span class="date text-success float-right"><i class="fa fa-clock-o text-success "></i> 30/2/2020</span>
                                            </h4>
                                        </li>
                                        <li> <?= ucfirst($job->first_name.' '.$job->last_name)?></li>
                                        <li><?= ucfirst($job->job_state) ?> | <?= ucfirst($job->job_type) ?> | <span class="text-warning money-amount"><?= $amount ?></span></li>
                                        <li class="text-right j-action">
                                            <a href="<?= url('/ajax.php') ?>" title="Save job" class="work_wishlist_btn item-action" id="<?= $job->worker_id ?>">
                                                <i class="fa <?= $savedJob ? 'fa-heart text-danger' : 'fa-heart-o text-primary'?>"></i>
                                                <span class="save_job alert_success">Job has been saved</span>
                                            </a>
                                            <i class="fa fa-eye  text-primary" title="views"></i> <span style="font-size: 13px;"><span><?= ucfirst($job->job_views) ?></span></span>
                                        </li>
                                    </ul>
                                </div>
                               
                                <div class="j-expirience">
                                    <ul>
                                        <li><b>Qualification:</b> <?= $job->qualification ?></li>
                                        <li><b>Expirience level:</b> <?= $job->expirience_level ?></li>
                                        <li><b>Expirience length:</b> <?= $job->expirience_length ?></li>
                                    </ul>
                                </div>
                                <div class="j-bio">
                                    <div class="js-head">Bio:</div>
                                     <p><?= $job->bio ?></p>
                                </div>
                                <div class="j-contact">
                                    <div class="js-head">Contact info:</div>
                                     <ul>
                                         <li><i class="fa fa-phone text-success"></i> <b>Phone:</b> <?= $job->phone ?></li>
                                         <li><i class="fa fa-envelope text-success"></i> <b>Email:</b> <?= $job->email ?></li>
                                         <li><i class="fa fa-home text-success"></i> <b>Address:</b> <?= $job->address ?></li>
                                         <li><i class="fa fa-circle text-success"></i> <b>City:</b> <?= $job->city ?></li>
                                         <li><i class="fa fa-users text-success"></i> <b>state:</b> <?= $job->state ?></li>
                                         <li><i class="fa fa-flag text-success"></i> <b>Country:</b> <?= $job->country ?></li>
                                     </ul>
                                </div>
                                <div class="j-summary">
                                    <div class="j-summary-h">Job Detail</div>
                                    <div class="j-summary-detail">
                                        <img src="images/icons/1.svg" alt="">
                                        <p><?= $job->summary ?></p>
                                    </div>
                                </div>
                                <div class="j-description">
                                    <div class="js-head">Job functions</div>
                                    <ul>
                                        <?php if($job->job_function): 
                                            $functions = json_decode($job->job_function, true);
                                            foreach($functions as $function):?>
                                            <li><?= ucfirst($function) ?></li>
                                            <?php endforeach ?>
                                        <?php else: ?>
                                        <li class="text-danger">There are no function</li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>
                            <!-- featured jobs end-->
                            <div class="j-apply apply_now_1">
                                <div class="btn-anchor" id="j-apply-btn">
                                    <a href="#" class="">apply now</a>
                                </div>
                                <p>
                                    By click 'Apply now', You agree to our <a href="#" class="text-primary">terms & conditions</a>
                                    and <a href="#" class="text-primary">Privacy policy</a>
                                </p>
                            </div>
                        </div>
                        <div class="col-lg-3" id="apply_now_2">
                             <?php include('includes/apply_now.php') ?>
                        </div>
                    </div>
                </div>
           </div>
       </div>




<!-- Our Footer -->
<?php include('../includes/footer.php');  ?>


















<script>
$(document).ready(function(){
     
// =======================================
// ADD JOB TO WISHLIST
// ======================================
var wishListIcon = $('.work_wishlist_btn');
$(wishListIcon).click(function(e){
    e.preventDefault();
    var url = $(this).attr('href');
    var worker_id = $(this).attr('id');
    var icon = $(this).children('i');

    if($(this).children().hasClass('fa-heart-o')){
        $(icon).removeClass('fa-heart-o text-primary');
        $(icon).addClass('fa-heart text-danger');
         
        $(this).children('.alert_success').show();
        alert_success();
    }else{
        $(this).children('.alert_success').hide();
        $(icon).removeClass('fa-heart text-danger')
        $(icon).addClass('fa-heart-o text-primary')
    }
 
    save_for_later(url, worker_id);
});

function save_for_later(url, worker_id){
        $.ajax({
            url: url,
            method: 'post',
            data: {
                worker_id: worker_id,
                save_job: 'save_job'
            },
            success: function(response){
                var data = JSON.parse(response);
                if(data.error){
                    console.log(data.error);
                }else if(data.data){
                    console.log('job saved');
                }
            }
        });
}

// =====================================
// REMOVE ALERT SUCCESS
// =====================================
function alert_success(){
    setTimeout(function(){
        $(".alert_success").hide();
    }, 3000)
}




// end
});
</script>