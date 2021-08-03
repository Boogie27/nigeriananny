



// *********** BOTTOM ALERT DANGER ****************//
var time;
function bottom_alert_error(string){
    var bottom = '0px';
    var alert =  $("#bottom_alert_danger").children('.bottom-alert-danger')

    

    if($(window).width() > 767){
        bottom = '5px'
    }

    $(alert).html(string)
    $(alert).css({ bottom: bottom })
    var newBottom = parseInt($(alert).css('bottom'));
    
    if(newBottom >= 0){
        $(alert).css({
            bottom: '-100px'
        })
        return clearTimeout(time)
    }

    time = setTimeout(function(){
            $(alert).css({
                bottom: '-100px'
            })
        }, 4000)
}








// *********** BOTTOM ALERT SUCCESS ****************//
function bottom_alert_success(string){
    var bottom = '0px';
    var alert =  $("#bottom_alert_success").children('.bottom-alert-success')

    if($(window).width() > 767){
        bottom = '5px'
    }

    $(alert).html(string)
    $(alert).css({ bottom: bottom })
    var newBottom = parseInt($(alert).css('bottom'));
    
    if(newBottom >= 0){
        $(alert).css({
            bottom: '-100px'
        })
        return clearTimeout(time)
    }

    time = setTimeout(function(){
        $(alert).css({
            bottom: '-100px'
        })
    }, 4000)
}











$(document).ready(function(){

// =========================================
// QUICK ADD ITEM TO CART
// ==========================================
$(".ajax_add_to_cart_btn").click(function(e){
   e.preventDefault();
   var url = $(this).attr('href');
   var id = $(this).attr('id');

    $.ajax({
        url: url,
        method: 'post',
        data: {
            product_id: id,
            quick_add_to_cart: 'quick_add_to_cart'
        },
        success: function(response){
            $('.cart_total_quantity').html(response);
        }
    });
});









var stored_id = []
var member_type = null

// ************* CHECK/UNCHECK SINGLE MEMBERS ***********//
$("#members_parent_table_container").on('click', '.check-box-members-input-btn', function(){
    id = $(this).attr('id')
    member_type = $(this).attr('data-type')

    if($(this).prop('checked'))
    {
        check_single_member(id, true)
    }else{
        check_single_member(id, false)
    }
    $("#mass_member_check_box_input").prop('checked', false)
})





function check_single_member(id, data)
{
    if(stored_id.includes(id))
    {
        for(var i = 0; i < stored_id.length; i++){
            if(data == false && stored_id[i] == id){
                stored_id.splice(i, 1)
            }
        }
    }else{
        stored_id.push(id)
    }
    console.log(stored_id)
}











// *********** CHECK ALL MEMBERS ************//
$("#members_parent_table_container").on('click', '#mass_member_check_box_input', function(){
    if($(this).prop('checked'))
    {
        check_all(true)
        $(".check-box-members-input-btn").prop('checked', true)
    }else{
        check_all(false)
        $(".check-box-members-input-btn").prop('checked', false)
    }
})






function check_all(state){
    var checkbox = $(".check-box-members-input-btn");
    $.each(checkbox, function(index, current){
        var id = $(current).attr('id')
        check_single_member(id, state)
    })
}











// *********** OPEN NEWSLETTER MODAL MODAL ************//
$("#open_mass_member_newsletter_modal_btn").click(function(e){
    e.preventDefault()
    if(stored_id.length == 0)
    {
        return bottom_alert_error('No member was selected!')
    }
    $("#member_newesletter_modal_popup_box").show()
})







// *************OPEN NEWSLETTER CONFIRM MODAL **********//
var newsletter_id 
$(".send-member-newsletter-btn").click(function(e){
    e.preventDefault()
    newsletter_id = $(this).attr('id')

    $("#member_newesletter_modal_popup_box").hide()
    $("#member_newesletter_confirm_modal_popup_box").show()
    $("#send_members_newsletter_confirm_submit_btn").html('Proceed')
})





// ******** SEND NEWSLETTER *************//
$("#send_members_newsletter_confirm_submit_btn").click(function(e){
    e.preventDefault()

    var url = $(this).attr('data-url')
    $(this).html('Please wait...')

    $.ajax({
        url: url,
        method: "post",
        data: {
            stored_id: stored_id,
            newsletter_id: newsletter_id,
            send_newsletter: 'send_newsletter'
        },
        success: function (response){
            var data = JSON.parse(response);
            if(data.data){
                bottom_alert_success('Newsletter sent successfully!')
            }else{
                bottom_alert_error('Network error, try again later!')
            }
            stored_id = []
            $(".modal-alert-popup").hide()
            $("#mass_member_check_box_input").prop('checked', false)
            $(".check-box-members-input-btn").prop('checked', false) 
            
            console.log(data)
        }, 
        error: function(){
            stored_id = []
            $(".modal-alert-popup").hide()
            bottom_alert_error('Network error, try again later!')
            $("#mass_member_check_box_input").prop('checked', false)
            $(".check-box-members-input-btn").prop('checked', false)  
        }
    });
})








// ********* OPEN DROP DOWN **********//
$(window).click(function(e){
    $('ul.drop-down-body').hide()
    if($(e.target).hasClass('drop-down-open') || $(e.target).hasClass('drop-down-body')){
        $(e.target).parent().children('ul.drop-down-body').show()
    }
})






// ********* CLOSE CONFIRM BOX *********//
$(".confirm-box-close").click(function(e){
    e.preventDefault()
    $(".modal-alert-popup").hide()
})



// ********* DARK SKIN CLOSE CONFIRM BOX *********//
$(window).click(function(e){
    if($(e.target).hasClass('sub-confirm-dark-theme'))
    {
        $(".modal-alert-popup").hide()
    }
})














// *********** OPEN DEACTIVATED MEMBERS MODAL *************//
$("#open_mass_users_activate_modal_btn").click(function(e){
    e.preventDefault()
    if(stored_id.length == 0)
    {
        return bottom_alert_error('No member was selected!')
    }
    $("#member_activate_confirm_modal_popup_box").show()
})












// ******** ACTIVATE MEMBERS *************//
$("#members_activate_confirm_submit_btn").click(function(e){
    e.preventDefault()
 
    var url = $(this).attr('data-url')
    $(this).html('Please wait...')

    $.ajax({
        url: url,
        method: "post",
        data: {
            stored_id: stored_id,
            activate_users: 'activate_users'
        },
        success: function (response){
            var data = JSON.parse(response);
            if(data.data){
                location.reload()
            }else{
                bottom_alert_error('Network error, try again later!')
            }
            stored_id = []
            $(".modal-alert-popup").hide()
            $("#mass_member_check_box_input").prop('checked', false)
            $(".check-box-members-input-btn").prop('checked', false) 
            
            console.log(data)
        }, 
        error: function(){
            stored_id = []
            $(".modal-alert-popup").hide()
            bottom_alert_error('Network error, try again later!')
            $("#mass_member_check_box_input").prop('checked', false)
            $(".check-box-members-input-btn").prop('checked', false)  
        }
    });
})













// *********** OPEN DEACTIVATED MEMBERS MODAL *************//
$("#open_mass_member_deactivate_modal_btn").click(function(e){
    e.preventDefault()
    if(stored_id.length == 0)
    {
        return bottom_alert_error('No member was selected!')
    }
    $("#member_deactivate_confirm_modal_popup_box").show()
})









// ******** ACTIVATE MEMBERS *************//
$("#members_deactivate_confirm_submit_btn").click(function(e){
    e.preventDefault()
 
    var url = $(this).attr('data-url')
    $(this).html('Please wait...')

    $.ajax({
        url: url,
        method: "post",
        data: {
            stored_id: stored_id,
            deactivate_users: 'deactivate_users'
        },
        success: function (response){
            var data = JSON.parse(response);
            if(data.data){
                location.reload()
            }else{
                bottom_alert_error('Network error, try again later!')
            }
            stored_id = []
            $(".modal-alert-popup").hide()
            $("#mass_member_check_box_input").prop('checked', false)
            $(".check-box-members-input-btn").prop('checked', false) 
            
            console.log(data)
        }, 
        error: function(){
            stored_id = []
            $(".modal-alert-popup").hide()
            bottom_alert_error('Network error, try again later!')
            $("#mass_member_check_box_input").prop('checked', false)
            $(".check-box-members-input-btn").prop('checked', false)  
        }
    });
})























    // end
});