<?php include('../Connection_Admin.php');  ?>
<?php
if(!Admin_auth::is_loggedin())
{
  Session::delete('admin');
  return view('/admin/login');
}



if(!Input::exists('get') || !Input::get('sid'))
{
    return view('/admin-nanny/subscriptions');
}


  
if(Input::post('subscription'))
{
    if(Token::check())
    {
        $validate = new DB();
        $validation = $validate->validate([
                'type' => 'required|min:2|max:50',
                'duration' => 'required',
                'amount' => 'required',
                'access' => 'required',
                'description' => 'required|min:6|max:500'
        ]);

        if(!$validation->passed())
        {
            return back();
        }
        
        if($validation->passed())
        {
            $type = $connection->select('subscription_pan')->where('type', Input::get('type'))->where('sub_id', Input::get('sid'))->first();
            if(!$type)
            {
                $type_other = $connection->select('subscription_pan')->where('type', Input::get('type'))->get();
                if(count($type_other))
                {
                    Session::errors('errors', ['type' => '*Type already exist']);
                    return back();
                }
            }
        }
        
        $is_feature = Input::get('feature') ? 1 : 0;
        $update = $connection->update('subscription_pan', [
                    'type' => Input::get('type'),
                    'duration' => Input::get('duration'),
                    'access' => Input::get('access'),
                    'description' => Input::get('description'),
                    'amount' => Input::get('amount'),
                    'is_feature' => $is_feature,
        ])->where('sub_id', Input::get('sid'))->save();
        if($update){
            Session::flash('success', 'Subscription updated successfully!');
            return view('/admin-nanny/subscriptions');
        }
    }
}




// ===========================================
// GET EDIT SUBSCRIPTION
// ===========================================
$subscription = $connection->select('subscription_pan')->where('sub_id', Input::get('sid'))->first();


?>


<?php include('includes/header.php'); ?>


<!-- Main Header Nav -->
<?php include('includes/navigation.php') ?>

<!-- Main Header Nav For Mobile -->
<?php include('includes/mobile-navigation.php') ?>


<!-- Our Dashbord Sidebar -->
<?php include('includes/side-navigation.php') ?>


<!-- Our Dashbord -->
<div class="our-dashbord dashbord">
    <div class="dashboard_main_content">
        <div class="container-fluid">
            <div class="main_content_container">
                <div class="row">
                    <div class="col-lg-12">
                        <?php include('includes/mobile-drop-nav.php') ?><!-- mobile-navigation -->
                    </div>
                    <div class="col-lg-12">
                    <?php if(Session::has('success')): ?>
                        <div class="alert-success text-center p-3 mb-2"><?= Session::flash('success') ?></div>
                    <?php endif; ?>
                    <div class="alert alert-danger text-center p-3 mb-2 page_alert_danger" style="display: none;"></div>
                        <nav class="breadcrumb_widgets" aria-label="breadcrumb mb30">
                            <h4 class="title float-left">Manage subscriptions</h4>
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="<?= url('/admin-nanny') ?>">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><a href="<?= url('/admin-nanny/subscriptions') ?>">Subscriptions</a></li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-lg-12">
                        <form action="<?= current_url() ?>" method="POST" id="subscription_form">
                            <div class="sr-head text-center"><h4>Edit subscription</h4></div><br>
                            <div class="row">
                                <div class="col-lg-6 col-sm-6">
                                    <div class="form-group">
                                        <div class="alert-div">
                                            <?php  if(isset($errors['type'])) : ?>
                                                <div class="text-danger"><?= $errors['type']; ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <label for="">Type:</label>
                                        <input type="text" name="type" class="form-control h50" value="<?= $subscription->type ?? old('type')?>" required>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-6">
                                    <div class="form-group">
                                        <div class="alert-div">
                                            <?php  if(isset($errors['duration'])) : ?>
                                                <div class="text-danger"><?= $errors['duration']; ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <label for="">Duration</label>
                                        <div class="ui_kit_select_box">
                                            <select name="duration" class="selectpicker custom-select-lg mb-3">
                                                <option value="<?= $subscription->duration ?? 'select' ?>" ><?= $subscription->duration ?? 'Select' ?></option>
                                                <option value="1 month">1 month</option>
                                                <option value="3 months">3 months</option>
                                                <option value="6 months">6 months</option>
                                                <option value="6 months">9 months</option>
                                                <option value="1 year">1 year</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-6">
                                    <div class="form-group">
                                        <div class="alert-div">
                                            <?php  if(isset($errors['amount'])) : ?>
                                                <div class="text-danger"><?= $errors['amount']; ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <label for="">Amount:</label>
                                        <input type="number" min="1" name="amount" class="form-control h50" value="<?= $subscription->amount ?? old('amount')?>" required>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-6">
                                    <div class="form-group">
                                        <div class="alert-div">
                                            <?php  if(isset($errors['access'])) : ?>
                                                <div class="text-danger"><?= $errors['access']; ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <label for="">Number of access:</label>
                                        <input type="number" min="1" name="access" class="form-control h50" value="<?= $subscription->access ?? old('access')?>" required>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <div class="alert-div">
                                            <?php  if(isset($errors['description'])) : ?>
                                                <div class="text-danger"><?= $errors['description']; ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <label for="">Description:</label>
                                        <textarea name="description"  class="form-control h50" cols="30" rows="5"><?= $subscription->description ?? old('description') ?></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <div class="ui_kit_whitchbox">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox"  data-id="1" class="custom-control-input subscription_feature_btn" id="customSwitch_1" <?= $subscription->is_feature ? 'checked' : ''?>>
                                                <label class="custom-control-label" for="customSwitch_1">Feature</label>
                                            </div>
                                        </div>
                                        <input type="hidden" name="feature" id="subscription_feature_input" value="<?= $subscription->is_feature ?? old('feature')?>">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group text-right">
                                        <a href="<?= url('/admin-nanny/subscriptions') ?>" class="mr-2"><i class="fa fa-angle-left"></i><i class="fa fa-angle-left"></i> back</a>
                                       <button name="subscription" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </div>
                            <?= csrf_token() ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="footer-copy-right">
    <p><?= $banner->alrights ?></p>
</div>
<a class="scrollToHome" href="#"><i class="flaticon-up-arrow-1"></i></a>
</div>


















<a href="<?= url('/admin-nanny/ajax.php') ?>" class="ajax_url_page" style="display: none;"></a>





<?php  include('includes/footer.php') ?>



<script>
$(document).ready(function(){

// ==========================================
// SUBSCRIPTION FEATURE BUTTON
// ==========================================
$(".subscription_feature_btn").click(function(){
    $("#subscription_feature_input").val('');
    if($(this).prop('checked'))
    {
        $("#subscription_feature_input").val(1);
    }
});




// end
});
</script>