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

































    // end
});