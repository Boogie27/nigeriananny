<?php include('Connection.php');  ?>

<?php 
if(Input::post('unsubscribe'))
{
    $validate = new DB();   
    $validation = $validate->validate([
        'email' => 'required|email'
    ]);

    if($validation->passed())
    {
        $email_check = $connection->select('newsletters_subscriptions')->where('email', Input::get('email'))->first();
        if(!$email_check)
        {
            Session::flash('error', 'The email '.Input::get('email').' deos not exist!');
            return back();
        }

        if($email_check)
        {
            $delete = $connection->delete('newsletters_subscriptions')->where('id', $email_check->id)->save();
            if($delete)
            {
                Session::flash('success', 'Unsubscribed successfully!');
                return back();
            }
        }
    }
    Session::flash('error', 'Network error, try again later!');
    return back();
}

?>



<?php include('includes/header.php');  ?>


<!--  navigation-->
<?php include('includes/navigation.php');  ?>

<?php include('includes/side-navigation.php');  ?>

<?php include('includes/slider.php');  ?>


 <!-- content three start -->
 <div class="content-three">
    <div class="news-letter-body">
        <div class="row">
            <div class="col-lg-7 col-md-7">
                <div class="update-content">
                    <h4>Unsubscribe newsletter</h4>
                    <p>By unsubscribing from our newsletter you will no longer receive <br>latest information, listings and career delivered to your inbox.</p>
                </div>
            </div>
            <div class="col-lg-5 col-md-5">
                <?php if(Session::has('success')): ?>
                    <div class="alert alert-success text-center p-3 mb-2"><?= Session::flash('success') ?></div>
                <?php endif; ?>
                <?php if(Session::has('error')): ?>
                    <div class="alert alert-danger text-center p-3 mb-2"><?= Session::flash('error') ?></div>
                <?php endif; ?>
                <form action="<?= current_url() ?>" method="POST">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <?php  if(isset($errors['email'])) : ?>
                                    <div class="text-danger"><?= $errors['email']; ?></div>
                                <?php endif; ?>
                                <input type="email" name="email" class="form-control h50" value="<?= old('email') ?>" placeholder="Email">
                            </div>
                        </div>
                        
                        <div class="col-lg-12">
                            <button type="submit" name="unsubscribe" class="btn btn-button">
                                <i class="fa fa-envelope mt5"></i> 
                                <span class="news-letter-sub">UNSUBSCRIBE</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- content three start -->





<!-- Our Footer -->
<?php include('includes/footer.php');  ?>