 <!-- content three start -->
 <div class="content-three">
    <div class="news-letter-body">
        <div class="row">
            <div class="col-lg-7 col-md-7">
                <div class="update-content">
                    <h4>Stay updated</h4>
                    <p>Join our and get the latest information, listings and career insights delivered straight to your inbox.</p>
                </div>
            </div>
            <div class="col-lg-5 col-md-5">
                <form action="<?= current_url() ?>" method="POST">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <div class="alert_news alert_news_1 text-danger text-left"></div>
                                <input type="email" id="news_letter_email" class="form-control h50" value="" placeholder="Email">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group" style="margin: 0px;">
                                <div class="alert_news alert_news_2 text-danger"></div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                            <div class="form-group news-checker" style="margin: 0px;">
                                <input type="checkbox" class="news-letter-checker" value="employee">
                                <label for="">Job seeker</label>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                            <div class="form-group news-checker" tyle="margin: 0px;">
                                <input type="checkbox" class="news-letter-checker" value="employer">
                                <label for="">Employer</label>
                            </div>
                        </div>
                        
                        <div class="col-lg-12">
                            <input type="hidden" id="client_type_input" value="">
                            <button type="submit" name="submit_letter" class="btn btn-button">
                                <i class="fa fa-envelope mt5"></i> 
                                SUBSCRIBE
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- content three start -->
