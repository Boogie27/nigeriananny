








<!-- Modal -->
<div class="sign_up_modal modal fade" id="change_password" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" id="modal_change_password_close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="login_form">
                        <form action="#">
                            <div class="heading_img">
                               <?php if(Auth::user('user_image')):?>
                                    <img src="<?= asset(Auth::user('user_image')) ?>" alt="<?= Auth::user('first_name'); ?>">
                                <?php else: ?>
                                    <img src="<?= asset('/shop/images/users/demo.png')?>" alt="<?=  Auth::user('first_name'); ?>">
                                <?php endif; ?>
                            </div>
                            <div class="heading">
                                <h3 class="text-center">Change password</h3>
                                <p class="text-center">Do you wish to change your password?</p>
                            </div>
                            <div class="form-group">
                                <div class="alert-change alert_0 text-danger"></div>
                                <input type="password" class="form-control" id="old_password_input" placeholder="Old password">
                            </div>
                            <div class="form-group">
                                 <div class="alert-change alert_1 text-danger"></div>
                                <input type="password" class="form-control" id="new_password_input" placeholder="New password">
                            </div>
                            <div class="form-group">
                                <div class="alert-change alert_2 text-danger"></div>
                                <input type="password" class="form-control" id="confirm_password_input" placeholder="Confirm password">
                            </div>
                            <button type="button" data-url="<?= url('/shop/ajax.php') ?>" id="change_password_btn" class="btn btn-log btn-block btn-thm2">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="<?= url('/shop/js/jquery-3.3.1.js') ?>"></script>


<script>
$(document).ready(function(){
// ==========================================
// CHANGE PASSWORD 
// ==========================================
$("#change_password_btn").click(function(e){
     e.preventDefault();
     var url = $(this).attr('data-url');
    $(".alert-change").html('');

    change_password(url);
});



function change_password(url)
{
    var old_password = $("#old_password_input").val();
    var new_password = $("#new_password_input").val();
    var confirm_password = $("#confirm_password_input").val();

      $.ajax({
        url: url,
        method: 'post',
        data: {
            old_password: old_password,
            new_password: new_password,
            confirm_password: confirm_password,
            change_password: 'change_password'
        },
        success: function(response){
            var data = JSON.parse(response);
            if(data.error)
            {
               get_error(data.error);
            }else if(data.not_loggedin)
            {
                location.assign(data.not_loggedin)
            }else if(data.data)
            {
                $("#modal_change_password_close").click();
                $(".page_alert_success").show();
                $(".page_alert_success").html('Password has been updated successfully!');
                remove_alert();
                clear_input_field();
            }
        }
    });

}



function get_error(error){
    $(".alert_0").html(error.old_password);
    $(".alert_1").html(error.new_password);
    $(".alert_2").html(error.confirm_password);
}


function clear_input_field(){
    $("#old_password_input").val('');
    $("#new_password_input").val('');
    $("#confirm_password_input").val('');
}




function remove_alert(){
    setTimeout(function(){
        $(".page_alert_success").hide();
        $(".page_alert_success").html('');
    }, 4000);
}








// end
});
</script>




