<?php include('Connection.php');  ?>

<?php 
// ===========================================
// GET ALL JOBS
// ===========================================
$workers = $connection->select('workers')->leftJoin('employee', 'workers.employee_id', '=', 'employee.e_id')->where('employee.e_approved', 1)->where('is_flagged', 0)->where('employee.e_is_deactivate', 0);


// ==========================================
// GET JOBS BY CATEGORIES
// ==========================================
if(Input::exists('get') && Input::get('category'))
{
    $workers->where('slug', Input::get('category'));
}



// ==========================================
// GET JOBS BY SEARCH STATES
// ==========================================
if(Input::exists('get') && Input::get('state'))
{
    $workers->where('employee.state', Input::get('state'));
}




//************ GET EMPLOYEES BY SEARCH **************//
if(Input::exists('get') && Input::get('search'))
{
    $workers->where('job_title', 'RLIKE', Input::get('search'));
}



$workers->paginate(15); 

// dd($workers->result());

$page_alert = null;
$job_title = 'Featured Employees';
if(Input::get('category') && !count($workers->result()))
{
    $job_title = implode(' ', explode('-', Input::get('category')));
    $page_alert = 'There are no employees in <b>'.$job_title.'</b> category!';
    $workers = $connection->select('workers')->leftJoin('employee', 'workers.employee_id', '=', 'employee.e_id')->where('employee.e_approved', 1)->where('is_flagged', 0)->where('employee.e_is_deactivate', 0)->paginate(15);
}


if(Input::get('search') && !count($workers->result()))
{
    $page_alert = 'There are no employees under <b>'.Input::get('search').'</b> !';
    $workers = $connection->select('workers')->leftJoin('employee', 'workers.employee_id', '=', 'employee.e_id')->where('employee.e_approved', 1)->where('is_flagged', 0)->where('employee.e_is_deactivate', 0)->paginate(15);
}


?>


<?php include('includes/header.php');  ?>


<!-- top navigation-->
<?php include('includes/navigation.php');  ?>

<?php include('includes/side-navigation.php');  ?>




<!-- main jobs container start-->
<div class="jobs-container">
    <div class="jobs-body">
        <div class="row">
            <div class="col-lg-3"><!-- jobs side start-->
                <div class="job-side">
                    <div class="search_input_x">
                        <form action="<?= current_url() ?>" method="GET">
                            <div class="form-group">
                                <input type="text" class="form-control h50" name="search" value="" placeholder="Search by title" required>
                                <button type="submit" class="btn btn-fill mt-1">Search jobs</button>
                            </div>
                        </form>
                    </div>
                    <ul class="ul-job-side">
                        <div class="title"><h4>Categories</h4></div>
                        <?php if(count($categories)): ?>
                        <li><a href="<?= url('/jobs') ?>">All employee</a></li>
                        
                        <?php foreach($categories as $category) :?>
                            <li><a href="<?= url('/jobs.php?category='.$category->category_slug) ?>"><?= ucfirst($category->category_name) ?></a></li>
                            <?php endforeach;?>
                        <?php else: ?>
                            <li class="text-center">There are no categories</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div><!-- jobs side end-->
            <div class="col-lg-9"><!-- jobs start-->
                <div class="main-jobs-body">
                    <?php if($page_alert): ?>
                    <div class="page-alert"><?= $page_alert ?></div>
                    <?php endif; ?>
                    <div class="title"><h3><?= $job_title ?></h3></div>
                    <div class="jobs-jobs">
                        <div class="row">
                            <?php foreach($workers->result() as $worker): 
                            $w_image = $worker->w_image ?  $worker->w_image : '/images/employee/demo.png';
                            $amount = !$worker->amount_to ? money($worker->amount_form) : money($worker->amount_form).' - '.money($worker->amount_to);
                            $location = $worker->job_type != 'live in' ? json_decode($worker->job_type, true) : null;
                            ?>
                            <div class="col-xl-4 col-lg-6 col-md-4 col-sm-6 col-12 expand-grid">
                                <div class="inner-content flex">
                                    <a href="<?= url('/job-detail.php?wid='.$worker->worker_id) ?>">
                                            <img src="<?= asset($w_image)?>" alt="<?= $worker->first_name?>" class="inner-img">
                                    </a>
                                    <ul class="ul-content">
                                            <li><h4><a href="<?= url('/job-detail.php?wid='.$worker->worker_id) ?>"><?= ucfirst($worker->job_title) ?></a></h4></li>
                                            <li><?= ucfirst($worker->first_name.' '.$worker->last_name) ?></li>
                                            <li><?= $worker->job_type != 'live in' ? 'Live out | '.$location['state'] : 'Live in';?></li>
                                            <li><span class="text-warning"><?= $amount ?></span> <span class="float-right"><?= date('d M Y', strtotime($worker->date_added)) ?></span></li>
                                        </ul>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <!-- pagination start -->
                    <?php if(count($workers->result())): ?>
                        <div class="paginate">
                            <?php $workers->links()?>
                        </div>
                    <?php endif;?>
                     <!-- pagination end -->
                </div>
            </div><!-- jobs end-->
        </div>
    </div>

    <!-- news letter start-->
    <div class="newsletter">
        <?php include('includes/news-letter.php') ?>
    </div>
     <!-- news letter end-->
</div>
<!-- main jobs container end-->









<!-- Our Footer -->
<?php include('includes/footer.php');  ?>