<?php include('Connection.php');  ?>
<?php 


header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Accept: application/vnd.github.v3+json');


// $id = null;
// if(Input::exists('get') && Input::get('id'))
// {
//     $id = Input::get('id');
// }
// $reads = Api::read($id);




// ===========================================
// POST CONTACT MESSAGE
// ===========================================
if(Input::post('verify_bvn_number'))
{
     $validate = new DB();
     $validation = $validate->validate([
            'first_name' => 'required|min:3|max:50',
            'last_name' => 'required|min:3|max:50',
            'bvn' => 'required',
            'bank_code' => 'required',
            'account_number' => 'required',
        ]);

    if(!$validation->passed())
    {
        return back();
    }

    
    
    $params['bvn'] = Input::get('bvn');
    $params['first_name'] = Input::get('first_name');
    $params['last_name'] = Input::get('last_name');
    $params['bank_code'] = '070';
    $params['account_number'] = Input::get('account_number');

    $bvn = new BVN();
    $verify_bvn = $bvn->verify($params);


    dd($verify_bvn);

}





// ===========================================
// GET FREQUESNTLY ASK QUESTIONS
// ===========================================
$faqs = $connection->select('faqs')->where('is_feature', 1)->get();



 

// $bvn = new BVN();

// $verify_bvn = $bvn->verify($params);

// dd($verify_bvn);


?>
<?php include('includes/header.php');  ?>


<!--  navigation-->
<?php include('includes/navigation.php');  ?>

<?php include('includes/side-navigation.php');  ?>

    
 
    
   <!-- jobs  start-->
   <div class="page-content">
        <div class="register-container">
            <div class="register-forms" id="contact-form">
            <?php if(Session::has('success')): ?>
                <div class="alert alert-success text-center p-3"><?= Session::flash('success') ?></div><br>
            <?php endif; ?>
               <form action="<?= current_url()?>" method="POST">
                    <h1 class="rh-head">Verify BVN number</h3>
                    <br>
                    <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="">First name:</label>
                                    <input type="text" name="first_name" class="form-control h50" value="<?= old('first_name') ?>">
                                    <div class="alert-cont">
                                         <?php  if(isset($errors['first_name'])) : ?>
                                            <div class="text-danger"><?= $errors['first_name']; ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="">Last name:</label>
                                    <input type="text" name="last_name" class="form-control h50" value="<?= old('last_name') ?>">
                                    <div class="alert-cont">
                                         <?php  if(isset($errors['last_name'])) : ?>
                                            <div class="text-danger"><?= $errors['last_name']; ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="">Account number:</label>
                                    <input type="text" name="account_number" class="form-control h50" value="<?= old('account_number') ?>">
                                    <div class="alert-cont">
                                         <?php  if(isset($errors['account_number'])) : ?>
                                            <div class="text-danger"><?= $errors['account_number']; ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="">Bvn:</label>
                                  <input type="text" name="bvn" class="form-control h50"><?= old('bvn') ?>
                                    <div class="alert-cont">
                                         <?php  if(isset($errors['bvn'])) : ?>
                                            <div class="text-danger"><?= $errors['bvn']; ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="">Bank code:</label>
                                  <input type="text" name="bank_code" class="form-control h50"><?= old('bank_code') ?>
                                    <div class="alert-cont">
                                         <?php  if(isset($errors['bank_code'])) : ?>
                                            <div class="text-danger"><?= $errors['bank_code']; ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <button type="submit" name="verify_bvn_number" class="btn btn-fill">SUBMIT</button>
                                </div>
                            </div>
                    </div>
                </form>
            </div>
        </div>
   </div>




    <!-- Our Footer -->
    <?php include('includes/footer.php');  ?>

