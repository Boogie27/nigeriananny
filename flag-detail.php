<?php include('Connection.php');  ?>


<?php 
if(!Input::exists('get') || !Input::get('fid'))
{
    return view('/flagged');
}





// ==========================================
// GET EMPLOYER REPORTS
// ==========================================
$reports = $connection->select('employer_reports')->leftJoin('employers', 'employer_reports.employer_rid', '=', 'employers.id')->where('employee_rid', Input::get('fid'))->get();
 


// ==========================================
// GET EMPLOYEE
// ==========================================
$employee = $connection->select('employee')->where('e_id', Input::get('fid'))->first();
?>






<?php include('includes/header.php');  ?>

<!-- top navigation-->
<?php include('includes/top-navigation.php');  ?>

<!-- top navigation-->
<?php include('includes/navigation.php');  ?>




	

	<!-- mobile navigation-->
    <?php include('includes/mobile-navigation.php');  ?>
    

    
   <!-- flagged  start-->
   <div class="page-content">
        <div class="flagged-container">
            <div class="flagged-h">
                <h3>Employee Flagged</h3>
                <p>Bellow are the employers who flagged <b><?= ucfirst($employee->last_name.' '.$employee->first_name) ?></b> and the obious reasons they presented</p>
            </div>
            <br>
            <div class="employee-flag">
                <div class="employee-review">
                        <?php foreach($reports as $report): ?>
                        <div class="emp-rev flex-item">
                            <?php $profile_image = $report->e_image ? $report->e_image : '/employer/images/employer/demo.png';  ?>
                            <img src="<?= asset($profile_image) ?>" alt="<?= $report->first_name ?>" class="review-img">
                            <ul class="info pt-2">
                                <li><b>Name: </b><?= ucfirst($report->first_name.' '.$report->last_name)?> <span class="float-right text-success"><?= date('d M Y', strtotime($report->date_reported))?></span></li>
                                <li><b>Email: </b><?= $report->email ?></li>
                                <li><b>Report: </b><?= $report->report ?></li>
                                <li><b>Comment: </b><br><?= $report->comment ?></li>
                            </ul>
                        </div>
                        <br>
                        <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
   <!-- flagged end -->






    <!-- Our Footer -->
    <?php include('includes/footer.php');  ?>
