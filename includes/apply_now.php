
<form action="<?= current_url() ?>" method="POST" class="p-apply-container">
    <div class="apply-h"><h4>Hire worker here</h4></div>
    <div class="apply-container">
        <div class="row">
        <div class="col-lg-12 col-sm-6">
            <div class="form-group">
                <div class="all_alert alert_1 text-danger"></div>
                <input type="text" class="first_name_input_mobile form-control h50" placeholder="Frist name" required>
            </div>
        </div>
        <div class="col-lg-12 col-sm-6">
            <div class="form-group">
            <div class="all_alert alert_2 text-danger"></div>
                <input type="text" class="last_name_input_mobile form-control h50" placeholder="Last name" required>
            </div>
        </div>
        <div class="col-lg-12 col-sm-6">
            <div class="form-group">
            <div class="all_alert alert_3 text-danger"></div>
                <input type="text" class="phone_input_mobile form-control h50" placeholder="Phone number" required>
            </div>
        </div>
        <div class="col-lg-12 col-sm-6">
            <div class="form-group">
            <div class="all_alert alert_4 text-danger"></div>
                <input type="number" class="amount_input_mobile form-control h50" placeholder="Amount" required>
            </div>
        </div>
        <div class="col-lg-12 col-sm-6">
            <div class="form-group">
                <div class="all_alert alert_8 text-danger"></div>
                <textarea  class="address_input_mobile form-control h50" cols="30" rows="3" placeholder="Job address" required></textarea>
            </div>
        </div>
        <div class="col-lg-12 col-sm-6">
            <div class="form-group">
            <div class="all_alert alert_6 text-danger"></div>
                <input type="text" class="city_input_mobile form-control h50" placeholder="City" required>
            </div>
        </div>
        <div class="col-lg-12 col-sm-6">
            <div class="form-group">
            <div class="all_alert alert_7 text-danger"></div>
                <input type="text" class="state_input_mobile form-control h50" placeholder="State" required>
            </div>
        </div>
    
        <div class="col-lg-12">
            <div class="form-group">
                <div class="all_alert alert_9 text-danger"></div>
                    <textarea class="message_input_mobile form-control h50" cols="30" rows="5" placeholder="Message..."></textarea>
                    <label for="" class="cv-label">Max 400 characters</label>

                    <?php $amount = !$job->amount_to ? money($job->amount_form) : money($job->amount_form).' - '.money($job->amount_to); 
                        if($job->amount_to): ?>
                        <p style="font-size: 13px;" class="text-center">Employee salary can be negotiated with the employee before any form of employment</p>
                        <p style="font-size: 13px;" class="text-success text-center"><i class="fa fa-money"></i><b> Salary:</b> <?= $amount ?></p>
                    <?php else: ?>
                        <h5 class="text-success text-center"><i class="fa fa-money"></i> <b>Salary: <?= $amount ?></b></h5>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-lg-12 apply_now_btn">
            <div class="form-group">
                <div class="loading_container text-center" style="display: none;">Loading...</div>
                <div class="all_alert alert_0 text-center text-danger p-2" style="font-size: 13px;"></div>
                <div class="btn-anchor" id="j-apply-btn">
                    <a href="<?= url('/ajax.php') ?>" class="mobile_employer_hire_btn" id="<?= Input::get('wid') ?>">Hire now</a>
                </div>
                <p class="apply-p">
                    By click <b>'Apply now'</b>, You agree to our <a href="#" class="text-primary">terms & conditions</a>
                    and <a href="#" class="text-primary">Privacy policy</a>
                </p>
            </div>
        </div>
    </div>
</form>