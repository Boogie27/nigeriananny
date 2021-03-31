<?php include('Connection.php');  ?>


<?php 

$flags = $connection->select('employee')->where('is_flagged', 1)->paginate(12);
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
                <h3>Flagged employees</h3>
                <p>Employees who are appear here has been flagged based on their misdeeds</p>
            </div>
            <br><br>
            <div class="row">
                <?php if($flags->result()): 
                foreach($flags->result() as $flag):
                ?>
                <!-- flagg start -->
                <div class="col-lg-4 col-md-4 col-sm-6 col-12">
                    <div class="flag-body">
                        <div class="flag-img">
                            <?php $profile_image = $flag->w_image ? $flag->w_image : '/employee/images/demo.png';  ?>
                            <img src="<?= asset($profile_image) ?>" alt="<?= $flag->first_name?>">
                        </div>
                        <ul class="ul-flagg pt-2">
                            <li><?= ucfirst($flag->first_name.' '.$flag->last_name)?></li>
                            <li><b>Flagged on</b> <span class="text-success"><?= date('d M Y', strtotime($flag->flagged_date)) ?></span></li>
                        </ul>
                       <div class="link-flag text-right">
                           <i class="fa fa-thumbs-down text-danger"></i><span> (<?= flagged_employee($flag->e_id)?>)</span>
                            <a href="<?= url('/flag-detail.php?fid='.$flag->e_id) ?>" class="text-primary">view detail</a>
                       </div>
                    </div>
                </div>
                <!-- flagg end -->
                <?php endforeach; ?>
                <!-- pagination -->
                <?php $flags->links(); ?>

                <?php else: ?>
                    <div class="empty-page-flag">
                        <div class="inner-flag">
                            <h4>FLag empty</h4>
                            <p>There are no flagged employees yet!</p>
                            <a href="<?= url('/') ?>" class="text-primary"><i class="fa fa-angle-left"></i><i class="fa fa-angle-left"></i> back</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
   <!-- flagged end -->






    <!-- Our Footer -->
    <?php include('includes/footer.php');  ?>
