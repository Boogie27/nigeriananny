<?php include('Connection.php');  ?>


<?php
if(!Session::has('subscription') || !Auth_employer::is_loggedin())
{
    return view('/');
}

if(!Session::has('page_success'))
{
    Session::delete('subscription');
}



if(Session::has('subscription'))
{
    // $reference = 3456789098;
    $callBack = new Paystack();
    $reference = $callBack->call_back();
    store_subscription($reference);
}



function store_subscription($reference)
{
    if($reference)
    {
        $connection = new DB();
        $sub = Session::get('subscription');
        $employer_id = Auth_employer::employer('id');

        $subscription_pans = $connection->select('subscription_pan')->where('sub_id', $sub['id'])->where('is_feature', 1)->first();
        if($subscription_pans)
        {
            $duration = explode(' ', $subscription_pans->duration);
            if($duration[1] == 'years' || $duration[1] == 'year')
            {
                $time = $duration[0].'year';
                $date = date('Y-m-d H:i:s', strtotime('+ '.$time));
            }else{
                $time = $duration[0].'month';
                $date = date('Y-m-d H:i:s', strtotime('+ '.$time));
            }

            $subsc = $connection->select('employer_subscriptions')->where('s_employer_id', $employer_id)->where('is_expire', 0)->first();
            if($subsc)
            {
                $is_expire_date = date('Y-m-d H:i:s');
                $connection->update('employer_subscriptions', [
                       'is_expire' => 1,
                       'is_expire_date' => $is_expire_date
                ])->where('reference', $subsc->reference)->save();
            }

            $create = $connection->create('employer_subscriptions', [
                    'reference' => $reference,
                    'subs_id' => $sub['id'],
                    's_employer_id' => $employer_id,
                    's_amount' => $subscription_pans->amount,
                    's_type' => $subscription_pans->type,
                    's_duration' => $subscription_pans->duration,
                    'end_date' => $date,
                ]);
            if($create)
            {
                Session::delete('page_success');
                return view('/success');
            }
        }
    }
}


?>


<?php include('includes/header.php');  ?>



<div class="wrapper page_wrapper">
    <div class="preloader"></div> <!-- preloader -->

    <div class="success-page">
        <div class="success-container">
            <img src="<?= asset('/images/success-icon.png') ?>" alt="">
            <h4>You have subscribed successfully</h4>
            <a href="<?= url('/') ?>" class="view-btn-fill">Back to home</a>
        </div>
    </div>
</div>





 <!-- Wrapper End -->
<script data-cfasync="false" src="https://grandetest.com/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/jquery-3.3.1.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/jquery-migrate-3.0.0.min.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/popper.min.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/jquery.mmenu.all.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/ace-responsive-menu.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/bootstrap-select.min.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/snackbar.min.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/simplebar.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/parallax.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/scrollto.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/jquery-scrolltofixed-min.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/jquery.counterup.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/wow.min.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/progressbar.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/slider.js"></script>
<script type="text/javascript" src="<?= SITE_URL ?>/js/timepicker.js"></script>
<!-- Custom script for all pages --> 
<script type="text/javascript" src="<?= SITE_URL ?>/js/script.js"></script>
</body>

<!-- Mirrored from grandetest.com/theme/edumy-html/index2.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 30 Nov 2020 11:05:06 GMT -->
</html>