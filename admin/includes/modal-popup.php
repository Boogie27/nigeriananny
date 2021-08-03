<!-- BOTTOM ALERT DANGER POPUP START-->
<div class="bottom-alert" id="bottom_alert_danger">
    <div class="bottom-alert-danger">Network error, try again later!</div>
</div>
<!--  BOTTOM ALERT DANGER POPUP START-->





<!-- BOTTOM ALERT SUCCESS POPUP START-->
<div class="bottom-alert" id="bottom_alert_success">
    <div class="bottom-alert-success">Network error, try again later</div>
</div>
<!--  BOTTOM ALERT SUCCESS POPUP START-->












<!--  MEMBER NEWSLETTER MODAL ALERT START -->
<?php  
$newsletters = $connection->select('news_letters')->get();
?>
<section class="modal-alert-popup" id="member_newesletter_modal_popup_box">
    <div class="sub-confirm-container">
        <div class="sub-confirm-dark-theme">
            <div class="sub-inner-content">
                <div class="text-right p-2">
                    <button class="confirm-box-close"><i class="fa fa-times"></i></button>
                </div>
                <div class="confirm-header">
                    <p><b>Newsletter</b></p>
                </div>
                <?php if(count($newsletters)): 
                    $x = 1;
                ?>
                <ul class="ul-member-newsletter">
                <?php foreach($newsletters as $newsletter): ?>
                    <li class="main">
                        <div class="inner-newsletter">
                            <span>
                                <a href="<?= url('/admin-nanny/edit-newsletter/'.$newsletter->id) ?>" style="color: #555;"><?= $x.'. '.substr($newsletter->header, 0, 50) ?></a>
                            </span>
                            <div class="text-right pb-3">
                                <div class="drop-down">
                                    <i class="fa fa-ellipsis-h drop-down-open"></i>
                                    <ul class="drop-down-body">
                                        <li class="text-left">
                                            <a href="<?= url('/admin-nanny/edit-newsletter.php?nid='.$newsletter->id) ?>" class="">Edit</a>
                                            <a href="#" id="<?= $newsletter->id ?>" class="send-member-newsletter-btn">Send</a>
                                            <a href="<?= url('/admin-nanny/newsletter_preview.php?nid='.$newsletter->id) ?>" class="">Preview</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </li>
                  
                <?php 
                $x++;
                endforeach; ?>
                </ul>
                <?php else: ?>
                    <div class="text-center pb-3">There are no newsletters</div>
                <?php endif;  ?>
            </div>
        </div>
    </div>
</section>
<!--  SEND MODAL ALERT END -->













<!--  SEND MODAL ALERT START -->
<section class="modal-alert-popup" id="member_newesletter_confirm_modal_popup_box">
    <div class="sub-confirm-container">
        <div class="sub-confirm-dark-theme">
            <div class="sub-inner-content">
                <div class="text-right p-2">
                    <button class="confirm-box-close"><i class="fa fa-times"></i></button>
                </div>
                <div class="confirm-header">
                    <p>Do you wish to send this newsletter?</p>
                </div>
                <div class="confirm-form">
                    <form action="" method="POST" class="p-2">
                        <button type="button" data-url="<?= url('/admin/ajax.php') ?>" id="send_members_newsletter_confirm_submit_btn" class="btn btn-block btn-primary">Proceed</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!--  SEND MODAL ALERT END -->


















<!--  SEND MODAL ALERT START -->
<section class="modal-alert-popup" id="member_activate_confirm_modal_popup_box">
    <div class="sub-confirm-container">
        <div class="sub-confirm-dark-theme">
            <div class="sub-inner-content">
                <div class="text-right p-2">
                    <button class="confirm-box-close"><i class="fa fa-times"></i></button>
                </div>
                <div class="confirm-header">
                    <p>Do you wish to activate these customers?</p>
                </div>
                <div class="confirm-form">
                    <form action="" method="POST" class="p-2">
                        <button type="button" data-url="<?= url('/admin/ajax.php') ?>" id="members_activate_confirm_submit_btn" class="btn btn-block btn-primary">Proceed</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!--  SEND MODAL ALERT END -->











<!--  SEND MODAL ALERT START -->
<section class="modal-alert-popup" id="member_deactivate_confirm_modal_popup_box">
    <div class="sub-confirm-container">
        <div class="sub-confirm-dark-theme">
            <div class="sub-inner-content">
                <div class="text-right p-2">
                    <button class="confirm-box-close"><i class="fa fa-times text-danger"></i></button>
                </div>
                <div class="confirm-header">
                    <p>Do you wish to deactivate these customers?</p>
                </div>
                <div class="confirm-form">
                    <form action="" method="POST" class="p-2">
                        <button type="button" data-url="<?= url('/admin/ajax.php') ?>" id="members_deactivate_confirm_submit_btn" class="btn btn-block btn-danger">Proceed</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!--  SEND MODAL ALERT END -->