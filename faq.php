<?php include('Connection.php');  ?>
<?php 




// ===========================================
// GET EMPLOYEES FREQUESNTLY ASK QUESTIONS
// ===========================================
if(!Input::get('search'))
{
    $employee_faqs = $connection->select('faqs')->where('faq_type', 'employee')->get();

    // ===========================================
    // GET EMPLOYERS FREQUESNTLY ASK QUESTIONS
    // ===========================================
    $employer_faqs = $connection->select('faqs')->where('faq_type', 'employer')->get();
    
    // ===========================================
    // GET OTHERS FREQUESNTLY ASK QUESTIONS
    // ===========================================
    $others = $connection->select('faqs')->where('faq_type', 'others')->get();
    
}



// ===========================================================
// GET EMPLOYEES FREQUESNTLY ASK QUESTIONS BY KEYS WORDS
// ===========================================================
if(Input::exists('get') && Input::get('search'))
{
    $searchs = $connection->select('faqs')->where('faq_type', 'RLIKE', Input::get('search'))->get();
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
    
 
    
   <!-- jobs  start-->
   <div class="page-content">
        <div class="register-container">
            <div class="faq-container">
                <?php if(Session::has('success')): ?>
                    <div class="alert alert-success text-center p-3"><?= Session::flash('success') ?></div><br>
                <?php endif; ?>
               <form action="<?= current_url()?>" method="GET">
                    <h1 class="rh-head">How can we help you?</h1>
                   <div class="faq-form">
                        <form action="<?= current_url() ?>" method="GET">
                            <div class="cl-lg-12">
                                <div class="form-group">
                                    <input type="text" name="search" class="form-control h50" value="" placeholder="Type keywords to find answers" required>
                                </div>
                            </div>
                        </form>
                        <p class="text-center text-primary">You can also browse the topic belpow to find what you are loooking for.</p>
                   </div>
                    <hr>
                    <div class="row">
                
                    </div>
                </form>
                
                <div class="faq-container-body"> <!-- content start-->
                    <?php if(!input::exists('get') && !Input::get('fid') && !Input::get('search')): ?>
                    <h3 class="rh-faq">Frequently Asked Questions</h3>
                    <div class="faq-body">
                        <div class="row">
                            <?php if($employee_faqs): ?>
                                <div class="col-lg-6">
                                    <div class="head text-primary">Employee</div>
                                    <ul>
                                    <?php foreach($employee_faqs as $employee_faq): ?>
                                        <li><a href="<?= url('/faq.php?fid='.$employee_faq->id) ?>"><?= $employee_faq->faq ?></a></li>
                                    <?php endforeach; ?>
                                    </ul>
                                </div>
                                <?php endif; ?>

                                <?php if($employer_faqs): ?>
                                <div class="col-lg-6">
                                    <div class="head text-primary">Employer</div>
                                    <ul>
                                    <?php foreach($employer_faqs as $employer_faq): ?>
                                        <li><a href="<?= url('/faq.php?fid='.$employer_faq->id) ?>"><?= $employer_faq->faq ?></a></li>
                                    <?php endforeach; ?>
                                    </ul>
                                </div>
                                <?php endif; ?>
                            </div>

                            <?php if($others): ?>
                                <div class="col-lg-6">
                                    <div class="head text-primary">Others</div>
                                    <ul>
                                    <?php foreach($others as $other): ?>
                                        <li><a href="<?= url('/faq.php?fid='.$other->id) ?>"><?= $other->faq ?></a></li>
                                    <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php if(input::exists('get') && Input::get('fid')): 
                        $faqs_single =  $connection->select('faqs')->where('id', Input::get('fid'))->first();
                        ?>
                            <div class="inner-faq-answer"> <!-- faq detail start-->
                                <h3 class="rh-faq text-center pb-2">Frequently Asked Questions</h3>
                                <div class="inner-faq-answer-body">
                                    <p class="text-primary" style="font-size: 15px;"><b><?= $faqs_single->faq?>:</b></p>
                                    <p style="font-size: 15px;"><?= $faqs_single->content ?></p>
                                    <div class="link-faq text-right">
                                        <a href="<?= url('/faq')?>" class="text-primary"><i class="fa fa-angle-left"></i><i class="fa fa-angle-left"></i> Back</a>
                                    </div>
                                </div>
                            </div><!-- faq detail end-->
                        <?php endif; ?>
                        
                        <?php if(input::exists('get') && Input::get('search')): ?>
                            <h3 class="rh-faq">Frequently Asked Questions</h3>
                            <div class="faq-body">
                                <?php if($searchs): ?>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="head text-primary">Search result (<?= count($searchs) ?>)</div>
                                        <ul>
                                        <?php foreach($searchs as $search): ?>
                                            <li><a href="<?= url('/faq.php?fid='.$search->id) ?>"><?= $search->faq ?></a></li>
                                        <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                                <?php else: ?>
                                    <div class="text-center text-primary" style="font-size: 13px;">There are no search results</div>
                                <?php endif; ?>
                                <div class="link-faq text-right">
                                    <a href="<?= url('/faq')?>" class="text-primary"><i class="fa fa-angle-left"></i><i class="fa fa-angle-left"></i> Back</a>
                                </div>
                            </div>
                        <?php endif; ?>

                    </div>
                </div><!-- content end-->
            </div>
          
           
        </div>
   </div>






<a href="<?= url('/ajax.php') ?>" class="ajax_url_page" style="display: none;"></a>



    <!-- Our Footer -->
    <?php include('includes/footer.php');  ?>


































