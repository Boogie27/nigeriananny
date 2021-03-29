<?php include('../Connection.php');  ?>



<?php 
// ======================================
// CHECK FOR EMPLOYEE INFOR
// ======================================
if(Auth_employee::is_loggedin() || !Session::has('work_details'))
{
    return view('/');
}





// =======================================
// CHECK FOR EMPLOYEE LOGIN
// =======================================

if(Input::post('post_job'))
{
    $validate = new DB();
   
    $validation = $validate->validate([
        'bio' => 'required|min:3|max:4000',
        'phone' => 'required|min:11|max:11|number:phone',
        'city' => 'required|min:3|max:50',
        'state' => 'required|min:3|max:50',
        'country' => 'required|min:3|max:50',
        'address' => 'required|min:3|max:300',       
    ]);

    if($validation->passed())
    {
        Session::put('work_details_2', Input::all());
        return view('/employee/job-info');
    }

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






<?php 
// =======================================
// GET CATEGORIES
// =======================================
$categories = $connection->select('job_categories')->where('is_category_featured', 1)->get(); 


// =======================================
// GET INPUTS
// =======================================
$input = null;
if(Session::has('work_details_2'))
{
    $input = Session::get('work_details_2');
}



?>






   <!-- jobs  start-->
   <div class="page-content">
        <div class="post-jobs-container">
        <div class="pj-head"><span><b>Apply as job seeker</b></span> <span class="float-right" style="border-radius: 0px 3px 3px 0px;">3/4</span></div>
                <form action="<?= current_url() ?>" method="post" id="post_job_form">
                     <!-- Contact start -->
                     <div class="post-body">
                        <div class="head-x-x flex-item"><i class="fa fa-folder icon"></i><h4>Contact information</h4></div><br>
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-6">
                                <div class="form-group">
                                    <?php  if(isset($errors['phone'])) : ?>
                                        <div class="text-danger"><?= $errors['phone']; ?></div>
                                     <?php endif; ?>
                                    <label for="">Phone:</label>
                                    <input type="text" name="phone" class="form-control h50" value="<?= $input['phone'] ?? old('phone') ?>">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-6">
                                <div class="form-group">
                                    <?php  if(isset($errors['city'])) : ?>
                                        <div class="text-danger"><?= $errors['city']; ?></div>
                                     <?php endif; ?>
                                    <label for="">City:</label>
                                    <input type="text" name="city" class="form-control h50" value="<?= $input['city'] ?? old('city') ?>">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="form-group">
                                    <?php  if(isset($errors['state'])) : ?>
                                        <div class="text-danger"><?= $errors['state']; ?></div>
                                     <?php endif; ?>
                                    <label for="">State:</label>
                                    <input type="text" name="state" class="form-control h50" value="<?= $input['state'] ?? old('state') ?>">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="form-group">
                                    <?php  if(isset($errors['country'])) : ?>
                                        <div class="text-danger"><?= $errors['country']; ?></div>
                                     <?php endif; ?>
                                    <label for="">Country:</label>
                                    <input type="text" name="country" class="form-control h50" value="<?= $input['country'] ?? old('country') ?>">
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <?php  if(isset($errors['address'])) : ?>
                                        <div class="text-danger"><?= $errors['address']; ?></div>
                                     <?php endif; ?>
                                    <label for="">Address:</label>
                                    <textarea name="address" class="form-control" cols="30" rows="3"><?= $input['address'] ?? old('address') ?></textarea>
                                    <label for="" class="cv-label">Max 400 characters</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Contact end -->


                     <!-- bio start -->
                     <div class="post-body">
                        <div class="head-x-x flex-item"><i class="fa fa-file-o icon"></i><h4>About information</h4></div><br>
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="form-group">
                                    <?php  if(isset($errors['bio'])) : ?>
                                        <div class="text-danger"><?= $errors['bio']; ?></div>
                                     <?php endif; ?>
                                    <label for="">Bio:</label>
                                    <textarea name="bio" class="form-control" cols="30" rows="5"><?= $input['bio'] ?? old('bio') ?></textarea>
                                    <label for="" class="cv-label">Max 5000 characters</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- bio end -->

                     <div class="col-xl-12">
                        <div class="form-group text-right">
                            <a href="<?= url('/employee/post-job') ?>" class="pr-2"><i class="fa fa-angle-left"></i><i class="fa fa-angle-left"></i> BACK</a>
                            <button type="submit" name="post_job" class="view-btn-fill">NEXT <i class="fa fa-angle-right"></i><i class="fa fa-angle-right"></i></button>
                        </div>
                    </div>
            </form>
        </div>
    </div>






<a href="<?= url('/ajax.php') ?>" class="ajax_url_page" style="display: none;"></a>






    <!-- Our Footer -->
<?php include('includes/footer.php');  ?>








