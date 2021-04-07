$(document).ready(function(){

// =========================================
// QUICK ADD ITEM TO CART
// ==========================================
$(".ajax_add_to_cart_btn").click(function(e){
   e.preventDefault();
   var url = $(this).attr('href');
   var id = $(this).attr('id');
   if($(window).width() <= 767)
    {
        $("#shop_alert_success_container").css({ top: 0 });
    }else{
        $("#shop_alert_success_container").css({ right: 0 });
    }

    $.ajax({
        url: url,
        method: 'post',
        data: {
            product_id: id,
            quick_add_to_cart: 'quick_add_to_cart'
        },
        success: function(response){
            remove_alert();
            $('.cart_total_quantity').html(response);
        }
    });
});





// ========================================
// REMOVE ALERTS
// ========================================
function remove_alert(){
    if($(window).width() <= 767)
    {
        setTimeout(function(){
            $("#shop_alert_success_container").css({
                top: '-100px'
            });
        }, 5000);
    }else{
        setTimeout(function(){
            $("#shop_alert_success_container").css({
                right: '-700px'
            });
        }, 5000);
    }
    
}


























    // end
});