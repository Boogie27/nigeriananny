<?php include('Connection.php');  ?>
<?php

// =======================================
// GET PRIVACY POLICY
// =======================================
$terms = $connection->select('settings')->where('id', 1)->first();

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
   <div class="privacy-policy">
       <h3 class="ph"><b>Terms & Conditions</b></h3>
       <br>
        <?php if($terms->terms):?>
            <?=  $terms->terms; ?>
        <?php endif;?>
    </div>











<a href="<?= url('/ajax.php') ?>" class="ajax_url_page" style="display: none;"></a>




    <!-- Our Footer -->
    <?php include('includes/footer.php');  ?>

