<?php include('../Connection.php');?>
<?php


// RATE PRODUCT
if(Input::post('rate_product'))
{
    $data = false;
    $error = null;
    $is_loggedin = null;
    $header = Input::get('header');
    $product_id = Input::get('product_id');
    $review = Input::get('review');
    $star_rate = Input::get('star_rate');

    if(!Auth::is_loggedin())
    {
        $is_loggedin['is_loggedin'] = 'Signup or login to review a product!';
    }else{
        if(empty($star_rate))
        {
            $error['star'] = '*Star is required';
        }
    
        if(empty($header))
        {
            $error['title'] = '*Title field is required';
        }else if(strlen($header) < 3){
            $error['title'] = '*Title must be minimum of 3 characters';
        }else if(strlen($header) > 50){
            $error['title'] = '*Title must be maximum of 50 characters';
        }
    
        if(empty($review))
        {
            $error['review'] = '*Review content field is required';
        }else if(strlen($review) < 3){
            $error['review'] = '*Review content must be minimum of 3 characters';
        }else if(strlen($review) > 10000){
            $error['review'] = '*Review content must be maximum of 5000 characters';
        }

        $connection = new DB();
        $review_check = $connection->select('product_review')->where('p_user_id', Auth::user('id'))->where('product_id', $product_id)->first();
        if($review_check)
        {
            $is_loggedin['is_loggedin'] = 'This product has been reviewed by you!';
        }
    } 


    
    
   if(!empty($error))
   {
        echo json_encode(['error' => $error]);
   }else if(!empty($is_loggedin)){
        echo json_encode(['alert' => $is_loggedin]);
   }else{
       $connection = new DB();
       $user = Session::get('user');
       $create = $connection->create('product_review', [
           'p_user_id' => $user['id'],
           'product_id' => $product_id,
           'review_title' => $header,
           'review_comment' => $review,
           'product_stars' => $star_rate
       ]);
       if($create)
       {
           $data = 'reviewed';
       }

       echo json_encode(['data' => $data]);
   }
}





// ===========================================
// GET REVIEWS
// ===========================================
if(Input::post('get_reviews'))
{
    $product_id = Input::get('product_id');
    include('common/ajax-detail-review.php');
}






// ============================================
// INCREASE OR DECREASE CART QUANTITY
// ============================================
if(Input::post('cart_quantity'))
{
    $quantity = Input::get('quantity');
    if(!$quantity)return;
    $product_id = Input::get('product_id');
    $connection = new DB();
    $productToAdd = $connection->select('shop_products')->where('id', $product_id)->where('product_is_featured', 1)->first();
    if($productToAdd)
    {
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->update_quantity($product_id, $productToAdd, $quantity);
        Session::put('cart', $cart);
        
        echo true;
    }
}




// ==============================================
//  DELETE ITEM FROM CART
// ===============================================
if(Input::post('delete_cart_item'))
{
   $product_id = Input::get('product_id');
   if($product_id)
   {
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->delete_item($product_id);
        Session::put('cart', $cart);

        if(Session::get('cart')->_totalQty == 0)
        {
            Session::delete('cart');
        }

        echo true;
   }
}








// =============================================
// QUICK ADD ITEMS TO CART
// =============================================
if(Input::post('quick_add_to_cart'))
{
    $product_id = Input::get('product_id');
    if($product_id)
    {
        $connection = new DB();
        $productToAdd = $connection->select('shop_products')->where('id', $product_id)->where('product_is_featured', 1)->first();
        if($productToAdd)
        {
            $quantity = 1;
            $oldCart = Session::has('cart') ? Session::get('cart') : null;
            $cart = new Cart($oldCart);
            $cart->add($product_id, $productToAdd, $quantity);
            Session::put('cart', $cart);
            
            echo Session::get('cart')->_totalQty;
        }
    }
}








// ================================================
// CHECK OUT
// ================================================
if(Input::post('check_out_check'))
{
    $user = Auth::is_loggedin();
    if(!$user)
    {
        Session::put('old_url', url('/shop/cart.php'));
        echo url('/shop/login.php');
    }else{
        echo url('/shop/checkout.php');
    }
}






// =====================================================
// GET STATE 
// =====================================================
if(Input::post('get_state'))
{
    $country_id = Input::get('country_id');

    $state_picker = '';
    $connection= new DB();
    $states = $connection->select('tbl_state')->where('country_id', $country_id)->where('active', 1)->get();
    $first_state_id = $states ? $states[0]->id : 0;

    if($states){
        $state_picker .= '';
        foreach($states as $state)
        {
            $state_picker .= ' <option value="'.$state->id.'">'.$state->state.'</option>';
        }
    }else{
        $state_picker = '<option value="">No state</option>';
    }
    

    
    $states = ['first_state_id' => $first_state_id, 'states' => $state_picker];
    echo json_encode($states);
}



// ======================================================
// GET CITY
// ======================================================
if(Input::post('get_city'))
{
    $state_id = Input::get('state_id');
    $city_picker = '';

    $connection= new DB();
    $cities = $connection->select('tbl_city')->where('state_id', $state_id)->where('active', 1)->get(); 
    $first_city_id = $cities ? $cities[0]->id : 0;

    if($cities)
    {
        $city_picker .= '';
        foreach($cities as $city)
        {
            $city_picker .= ' <option value="'.$city->id.'">'.$city->city.'</option>';
        }
    }else{
        $city_picker = '<option value="">No city</option>';
    }

    $cities = ['first_city_id' => $first_city_id, 'cities' => $city_picker];
    echo json_encode($cities);
}






// ========================================================
// GET SHIPPING FEE
// ========================================================
if(Input::post('get_shipping_fee'))
{
    $shipping_fee = 0;
    $city_id = Input::get('city_id');
    if($city_id)
    {
        $shipping_fee =  shipping_fee($city_id);
    }

    $total = Session::get('cart')->_totalPrice + $shipping_fee;
    $shipping = ['shipping_fee' => money($shipping_fee), 'total' => money($total) , 'raw_total' => $total];
    echo json_encode($shipping);
}














// =============================================
// PAY NOW
// =============================================
if(Input::post('pay_now'))
{
    $data = false;
    $user = Auth::is_loggedin();
    if($user)
    {
        $total = Input::get('total');
        $validate = new Validator();
        $validation = $validate->validate([
            'first_name' => 'required|min:3|max:50',
            'last_name' => 'required|min:3|max:50',
            'email' => 'required|email',
            'phone' => 'required|min:11|max:15',
            'city' => 'required|min:2|max:50',
            'message' => 'required|min:3',
            'address' => 'required|min:3|max:200',
            'zip_code' => 'required|min:3|max:10',
        ]);

        if(!$validation->passed())
        {
            return print_r(json_encode(['error' =>  $validation->error()]));
        }

        if($validation->passed())
        {
            $data = true;
            Session::put('buyer_details', Input::all('pay_now'));
        }
    }

    return print_r(json_encode(['data' =>  $data]));
}











// ===================================
//       CHANGE PASSWORD           
// ===================================
if(Input::post('change_password'))
{
    if(!Auth::is_loggedin())
    {
        Session::delete('user');
        return print_r(json_encode(['not_loggedin' =>  url('/shop/login.php') ]));
    }
    $data = false;
    $old_password = Input::get('old_password');
    $new_password = Input::get('new_password');
    $confirm_password = Input::get('confirm_password');
    
    $validate = new Validator();
    $validation = $validate->validate([
        'old_password' => 'required|min:6|max:12',
        'new_password' => 'required|min:6|max:12',
        'confirm_password' => 'required|min:6|max:12|match:new_password',
    ]);

    $user_detail = $connection->select('users')->where('id', Auth::user('id'))->first();

    if(!empty($old_password) && !password_verify($old_password, $user_detail->password))
    {
        return print_r(json_encode(['error' =>  ['old_password' => '*Wrong password, try again!']]));
    }
  
    if(!$validation->passed())
    {
        return print_r(json_encode(['error' =>  $validation->error()]));
    }


    if($validation->passed())
    {
        $data = true;
        $connection = new DB();
        if($user_detail)
        {
            $connection->update('users', [
                            'password' => password_hash($new_password, PASSWORD_DEFAULT)
                        ])->where('id', Auth::user('id'))->save();
            if($connection->passed())
            {
                $data = true;
            }
        }
    }
    return print_r(json_encode(['data' =>  $data]));
}












// ===================================================
// CANCLE A PRODUCT
// ===================================================
if(Input::post('cancle_product'))
{
    $data = false;
    $quantity = Input::get('quantity');
    $message = Input::get('message');
    $reference = Input::get('reference');
    $paid_product_id = Input::get('paid_product_id');

    $validate = new Validator();
    $validation = $validate->validate([
        'message' => 'required|min:6|max:2000',
        'quantity' => 'required'
    ]);

    if(!$validation->passed())
    {
        return print_r(json_encode(['error' => $validation->error()]));
    }

    if($validation->passed())
    {
        $connection = new DB();
        $paid_product = $connection->select('paid_products')->where('paid_buyer_id', Auth::user('id'))->where('paid_product_id', $paid_product_id)->where('paid_reference', $reference)->first();
        if($paid_product)
        {
            $available = $paid_product->quantity - $paid_product->cancled_quantity;
            if($quantity > $available)
            {
                return print_r(json_encode(['error' => ['quantity' => '*Quantity must be maximum of '.$available]]));
            }

            $update = $connection->update('paid_products', [
                'cancled_quantity' => $paid_product->cancled_quantity += $quantity
            ])->where('paid_buyer_id', Auth::user('id'))->where('paid_product_id', $paid_product_id)->where('paid_reference', $reference)->save();
           
            if($update->passed())
            {
                $cancle = $connection->create('cancled_product', [
                    'cancled_user_id' => Auth::user('id'),
                    'cancled_product_id' => $paid_product->product_id,
                    'cancled_reference' => $reference,
                    'cancled_product_quantity' => $quantity,
                    'cancled_product_price' => $paid_product->price,
                    'cancled_total' => $paid_product->price * $quantity,
                    'message' => $message
                ]);

                $data = true;
                if($cancle)
                {
                    $product = $connection->select('shop_products')->where('id', $paid_product->product_id)->first();
                    $connection->update('shop_products', [
                        'product_quantity' => $product->product_quantity += $quantity,
                        'product_quantity_sold' => $product->product_quantity_sold -= $quantity
                    ])->where('id', $paid_product->product_id)->save();

                    Session::put('success', "Order has been cancled");
                }else{
                    Session::put('error', "Error has occured, please try again later!");
                }
            }
            
        }
    }

    return print_r(json_encode(['data' => $data]));

}