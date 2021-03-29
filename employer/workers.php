<?php include('../Connection.php');  ?>

<?php include('includes/header.php');  ?>

<!-- top navigation-->
<?php include('includes/top-navigation.php');  ?>

<!-- top navigation-->
<?php include('includes/navigation.php');  ?>

<!-- images/home/4.jpg -->
	

	<!-- mobile navigation-->
    <?php include('includes/mobile-navigation.php');  ?>
    

    
	<!-- job search start-->
		<?php include('includes/search.php');  ?>
	<!-- job search end-->

    


<?php $jobs = $connection->select('workers')->where('is_deactivate', 0)->where('is_job_feature', 1)->paginate(5); ?>




   <!-- jobs  start-->
    <div class="page-content">
        <div class="inner-jobs">
            <div class="advert-banner">
              <a href="#">  <img src="<?= asset('/images/adverts/1.jpg')?>" alt=""></a>
            </div>
            <div class="job-head">
                <br>
                <h3>Featured jobs</h3>
            </div>
            <div class="row">
                <div class="col-lg-3">  <!-- category jobs start-->
                   <div class="ui_kit_input">
                        <form>
                            <div class="form-group">
                                <input type="text" class="form-control h50" id="exampleInputText" placeholder="Search by name">
                            </div>
                        </form>
                    </div>
                    <div class="job-category">
                        <div class="job-cat-head">
                            <h3>Categories</h3>
                        </div>
                        <div class="selected_filter_widget style2 mb30" id="job-category">
                            <div id="accordion" class="panel-group">
                                <div class="panel">
                                    <div id="panelBodySoftware" class="panel-collapse collapse show">
                                        <div class="panel-body">
                                            <div class="category_sidebar_widget">
                                                <ul class="category_list">
                                                <?php $categories = $connection->select('job_categories')->where('is_category_featured', 1)->get(); 
                                                if(count($categories)):
                                                    foreach($categories as $category) :?>
                                                    <li><a href="#"><?= ucfirst($category->category_name) ?></a></li>
                                                    <?php endforeach;?>
                                                <?php else: ?>
                                                    <li class="text-center">There are no categories</li>
                                                <?php endif; ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="job-alert-banner"><!-- job-alert jobs start-->
                        <div class="alert-header">
                            <h3>Jobs in Nigeria</h3>
                        </div>
                        <div class="alert-body">
                            <p><b>1280</b> jobs found</p>
                            <a href="#">Create job alert</a>
                        </div>
                    </div><!-- job-alert jobs start-->

                    <div class="advert-banner-2">
                        <a href="#"><img src="<?= asset('/images/adverts/4.jpg')?>" alt=""></a>
                    </div>
                    <div class="advert-banner-2">
                        <a href="#"><img src="<?= asset('/images/adverts/4.jpg')?>" alt=""></a>
                    </div>
                </div><!-- category jobs end-->

                <div class="col-lg-9">
                    <div class="job-head-2">
                        <br>
                        <h3>Featured jobs</h3>
                        
                    </div>
                    <?php if($jobs->result()): 
                          foreach($jobs->result() as $job): 
                            $savedJob = saved_jobs($job->worker_id);
                            $function = $job->job_function ? implode(',', explode(',', $job->job_function)) : '';
                            $w_image = $job->w_image ? $job->w_image : '/images/worker/demo.png'; 
                            $amount = !$job->amount_to ? money($job->amount_form) : money($job->amount_form).' - '.money($job->amount_to);?>
                     <!-- featured jobs start-->
                    <div class="job-body">
                        <div class="jobs-info">
                            <img src="<?= asset($w_image) ?>" alt="">
                            <ul class="ul">
                                <li>
                                    <h4>
                                        <a href="<?= url('/employer/worker-detail.php?jid='.$job->worker_id) ?>"><?= ucfirst($job->job_title) ?></a> 
                                        <span class="date text-success float-right"><i class="fa fa-clock-o text-success "></i> <?= date('d M Y', strtotime($job->date_added)) ?></span>
                                    </h4>
                                </li>
                                <li><?= ucfirst($job->first_name.' '.$job->last_name) ?></li>
                                <li><?= ucfirst($job->job_state) ?> | <?= ucfirst($job->job_type) ?> | <span class="text-warning money-amount"><?= $amount ?></span></li>
                                <li><?= $function ?></li>
                                 <li class="text-right j-action">
                                    <a href="<?= url('/ajax.php') ?>" title="Save job" class="work_wishlist_btn item-action" id="<?= $job->worker_id ?>">
                                        <i class="fa <?= $savedJob ? 'fa-heart text-danger' : 'fa-heart-o text-primary'?>"></i>
                                        <span class="save_job alert_success">Job has been saved</span>
                                    </a>
                                    <i class="fa fa-eye  text-primary" title="views"></i> <span style="font-size: 13px;"><span><?= ucfirst($job->job_views) ?></span></span>
                                </li>
                            </ul>
                        </div>
                        <div class="jobs-detail">
                            <img src="images/icons/1.svg" alt="">
                            <p><?=substr( $job->summary, 0, 300) ?></p>
                        </div>
                        <div class="view-btn">
                            <a href="<?= url('/employer/worker-detail.php?jid='.$job->worker_id) ?>" class="view-btn-fill">view details</a>
                        </div>
                    </div>
                    <!-- featured jobs end-->
                    <?php endforeach; ?>
                    <?php else: ?>
                        <div class="job-body">
                            There are no jobs yet
                        </div>
                    <!-- featured jobs end-->
                    <?php endif; ?>
                    
                    <?php if($jobs->result()):  ?>
                         <div class="pagination"><?= $jobs->links() ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <!-- jobs end-->






    <!-- Our Footer -->
<?php include('includes/footer.php');  ?>












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