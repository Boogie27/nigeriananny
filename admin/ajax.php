<?php include('../Connection.php');?>
<?php


// =============================================
// PRODUCT FEATURE 
// =============================================
if(Input::post('is_product_feature'))
{
    $product_id = Input::get('product_id');
    if(!empty($product_id))
    {
        $connection = new DB();
        $product_feature = $connection->select('shop_products')->where('id', $product_id)->first();
        $is_feature = $product_feature->product_is_featured ? 0 : 1;

        $connection->update('shop_products', [
            'product_is_featured' => $is_feature
        ])->where('id', $product_id)->save();
    }
}







// ============================================
// DELETE PRODUCTS
// ============================================
if(Input::post('delete_product'))
{
    $product_id = Input::get('product_id');
    if(!empty($product_id))
    {
        $connection = new DB();
        $product = $connection->select('shop_products')->where('id', $product_id)->first();
        if($product)
        {
            // Image::delete('../'.$product->product_image);
            // Image::delete('../'.$product->small_image);
            $images = explode(',', $product->big_image);

            foreach($images as $image)
            {
                Image::delete('../'.$image);
            }

            $product_feature = $connection->delete('shop_products')->where('id', $product_id)->save();
            if($product_feature)
            {
                return response(['data' => true]);
            }
        }
    }
    return false;
}







// ==========================================
// DELETE PRODUCT IMAGE
// ==========================================
if(Input::post('delete_product_img'))
{
    $key = Input::get('key');
    $product_id = Input::get('product_id');
    if(!empty($product_id))
    {
        $connection = new DB();
        $product = $connection->select('shop_products')->where('id', $product_id)->first();
        if($product)
        {
            $product_image = explode(',', $product->big_image);
            Image::delete('../'.$product_image[$key]);
            unset($product_image[$key]);

            $updateImage = $connection->update('shop_products', [
                'big_image' => implode(',', $product_image)
            ])->where('id', $product_id)->save();

            return require_once('common/ajax-edit-image.php');
        }
        return response(['data' => false]);
    }
}






// =============================================
// GET EDIT PRODUCT IMAGE
// =============================================
if(Input::post('get_edit_product_img'))
{
    $product_id = Input::get('product_id');
    if(!empty($product_id))
    {
        return require_once('common/ajax-edit-image.php');
    }
}





// ===================================================
// UPLOAD EDIT PRODUCT IMAGE
// ===================================================
if(Input::post('upload_edit_Product_image'))
{
    $product_id = Input::get('product_id');
    $connection = new DB();
    $product = $connection->select('shop_products')->where('id', $product_id)->first();
    if($product)
    {
        if(Image::exists('image'))
        {
            $image = new Image();
            $file = Image::files('image');
    
            $file_name = Image::name('image', 'product');
            $image->resize_image($file, [ 'name' => $file_name, 'width' => 320, 'height' => 391, 'size_allowed' => 1000000,'file_destination' => '../shop/images/products/big_image/']);
                
            $image_name = '/shop/images/products/big_image/'.$file_name;
            if(!$image->passed())
            {
                return response(['error' => $image->error()]);
            }

            if(!$product->big_image)
            {
                $old_image = $image_name;
            }else{
                $old_image = explode(',', $product->big_image);
                array_push($old_image, $image_name);
            }

            $updateImage = $connection->update('shop_products', [
                'big_image' => implode(',', $old_image)
            ])->where('id', $product_id)->save();

            return response(['data' => $product_id]);
        }
    }
    return response(['data' => false]);
}







// ===========================================
// GET EDIT PRODUCT SUBCATEGORY
// ===========================================
if(Input::post('get_subCategory'))
{
    $category_id = Input::get('category_id');
    
    $connection = new DB();
    $subCategories = $connection->select('shop_subcategories')->where('shop_categories_id', $category_id)->get();
    if($subCategories)
    {
        $data = '';
        foreach($subCategories as $subCategory)
        {
            $data .= '<option value="'.$subCategory->shop_subCategory_id.'">'.$subCategory->shop_subCategory_name.'</option>';
        }

        return response(['data' => $data]);
    }

    $empty = '<option>No subcategory</option>';
    return response(['empty' => $empty]);
}







// =================================================
//  UPLOAD ADD PRODUCT IMAGE
// =================================================\
if(Input::post('upload_add_Product_image'))
{
    $data = false;
    if(Image::exists('image'))
    {
        $expiry = 604800;
        $image = new Image();
        $file = Image::files('image');

        $file_name = Image::name('image', 'product');
        $image->resize_image($file, [ 'name' => $file_name, 'width' => 320, 'height' => 391, 'size_allowed' => 1000000,'file_destination' => '../shop/images/products/big_image/']);
            
        $image_name = '/shop/images/products/big_image/'.$file_name;

        if(!$image->passed())
        {
            return response(['error' => $image->error()]);
        }

        if(!Cookie::has('product_image'))
        {
            $product_image[] = $image_name;
        }else{
            $product_image = json_decode(Cookie::get('product_image'), true);
            array_push($product_image, $image_name);
        }

        $data = true;
        Cookie::put('product_image', json_encode($product_image), $expiry);
    }
    return response(['data' => $data]);
}






// ================================================
// GET ADD PRODUCT IMAGES
// ================================================
if(Input::post('get_add_product_img'))
{
    return require_once('common/ajax-add-product-image.php');
}





// =====================================
// DELETE ADD PRODUCT IMAGE
// =====================================
if(Input::post('remove_add_product_img'))
{
    $data = false;
    $expiry = 604800;
    $key = Input::get('key') ? Input::get('key') : 0;
    if(Cookie::has('product_image'))
    {
        $stored_image = json_decode(Cookie::get('product_image'), true);
        if(array_key_exists($key, $stored_image))
        {
            Image::delete('../'.$stored_image[$key]);
            unset($stored_image[$key]);
            $data = true;
            Cookie::put('product_image', json_encode($stored_image), $expiry);
            if(empty($stored_image))
            {
                Cookie::delete('product_image');
            }
        }
    }
    return response(['data' => $data]);
}







// ===========================================
// GET ADD PRODUCT SUBCATEGORY
// ===========================================
if(Input::post('get_add_subCategory'))
{
    $category_id = Input::get('category_id');
    
    $connection = new DB();
    $subCategories = $connection->select('shop_subcategories')->where('shop_categories_id', $category_id)->get();
    if($subCategories)
    {
        $data = '';
        foreach($subCategories as $subCategory)
        {
            $data .= '<option value="'.$subCategory->shop_subCategory_id.'">'.$subCategory->shop_subCategory_name.'</option>';
        }

        return response(['data' => $data]);
    }

    $empty = '<option>No subcategory</option>';
    return response(['empty' => $empty]);
}






// ==========================================
// CATEGORY FEATURE BUTTON
// ==========================================
if(Input::post('is_category_feature'))
{
    $category_id = Input::get('category_id');
    if(!empty($category_id))
    {
        $connection = new DB();
        $category_feature = $connection->select('shop_categories')->where('category_id', $category_id)->first();
        $is_feature = $category_feature->is_category_feature ? 0 : 1;

        $connection->update('shop_categories', [
            'is_category_feature' => $is_feature
        ])->where('category_id', $category_id)->save();
    }
}







// ==========================================
// ADD NEW CATEGORY
// ==========================================
if(Input::post('add_new_category'))
{
    $data = false;
    $Validate = new Validator();
    $Validate->validate([
        'category_name' => 'required|min:3|max:50',
        'category_title' => 'required|min:3|max:100'
    ]);

    if(!$Validate->passed())
    {
        return response(['error' => $Validate->error()]);
    }

    if(!Image::exists('image'))
    {
        return response(['error' => ['image' => '*Image field is required']]);
    }
    
    if(Image::exists('image'))
    {
        $image = new Image();
        $file = Image::files('image');

        $file_name = Image::name('image', 'category');
        $image->resize_image($file, [ 'name' => $file_name, 'width' => 500, 'height' => 600, 'size_allowed' => 1000000,'file_destination' => '../shop/images/category/']);
            
        $image_name = '/shop/images/category/'.$file_name;

        if(!$image->passed())
        {
            return response(['error' => ['image' => $image->error()]]);
        }
    }
    
    $category = explode(' ', Input::get('category_name'));
    $category_slug = implode('-', $category);

    $connection = new DB();
    $create = $connection->create('shop_categories', [
          'category_name' => Input::get('category_name'),
          'category_header' => Input::get('category_title'),
          'category_image' => $image_name,
          'category_slug' => $category_slug,
    ]);

    if($create)
    {
       $data = true;
       Session::flash('success', 'The category '.Input::get('category_name').' has been added successfully!');
    }
    return response(['data' => $data]);
}








// ==========================================
// DELETE CATEORY
// ==========================================
if(Input::post('delete_category'))
{
    $data = false;
    $category_id = Input::get('category_id');
    if(!empty($category_id))
    {
        $connection = new DB();
        $category = $connection->select('shop_categories')->where('category_id', $category_id)->first();
        if($category)
        {
            Image::delete('../'.$category->category_image);

            $delete = $connection->delete('shop_categories')->where('category_id', $category_id)->save();
            if($delete)
            {
                return response(['data' => true]);
            }
        }
    }
    return response(['data' => $data]);
}








// ======================================
// UPLOAD CATEGORY EDIT IMAGE
// ======================================
if(Input::post('upload_category_image'))
{
    $data = false;
    $category_id = Input::get('category_id');
    if(!empty($category_id))
    {
        if(!Image::exists('image'))
        {
            return response(['error' => ['image' => '*Image field is required']]);
        }

        if(Image::exists('image'))
        {
            $image = new Image();
            $file = Image::files('image');

            $file_name = Image::name('image', 'category');
            $image->resize_image($file, [ 'name' => $file_name, 'width' => 350, 'height' => 400, 'size_allowed' => 1000000,'file_destination' => '../shop/images/category/']);
                
            $image_name = '/shop/images/category/'.$file_name;

            if(!$image->passed())
            {
                return response(['error' => ['image' => $image->error()]]);
            }

            $connection = new DB();   
            $category = $connection->select('shop_categories')->where('category_id', $category_id)->first();   
            if($category)
            {
                Image::delete('../'.$category->category_image);
                
                $update = $connection->update('shop_categories', [
                            'category_image' => $image_name
                        ])->where('category_id', $category_id)->save(); 

                if($update)
                {
                    $data = $category_id;
                }
            }
        }
    }
    return response(['data' => $data]);
}







// ====================================
// GET EDIT CATEGORY IMAGES 
// ====================================
if(Input::post('get_edit_category_image'))
{
    $category_id = Input::get('category_id');
    $connection = new DB();   
    $category = $connection->select('shop_categories')->where('category_id', $category_id)->first();

    return require_once('common/ajax-edit-category-image.php');
}








// ==========================================
// SUBCATEGORY FEATURE BUTTON
// ==========================================
if(Input::post('is_subCategory_feature'))
{
    $subCategory_id = Input::get('subCategory_id');
    if(!empty($subCategory_id))
    {
        $connection = new DB();
        $product_feature = $connection->select('shop_subcategories')->where('shop_subCategory_id ', $subCategory_id)->first();
        $is_feature = $product_feature->shop_subCategory_isFeature ? 0 : 1;

        $connection->update('shop_subcategories', [
            'shop_subCategory_isFeature' => $is_feature
        ])->where('shop_subCategory_id ', $subCategory_id)->save();
    }
}






// ==========================================
// DELETE SUBCATEORY
// ==========================================
if(Input::post('delete_subCategory'))
{
    $data = false;
    $subCategory_id = Input::get('subCategory_id');
    if(!empty($subCategory_id))
    {
        $connection = new DB();
        $subcategory = $connection->select('shop_subcategories')->where('shop_subCategory_id', $subCategory_id)->first();
        if($subcategory)
        {
            $product_feature = $connection->delete('shop_subcategories')->where('shop_subCategory_id', $subCategory_id)->save();
            if($product_feature)
            {
               $data = true;
            }
        }
    }
    return response(['data' => $data]);
}








// ==========================================
// ADD NEW CATEGORY
// ==========================================
if(Input::post('add_subcategory'))
{
    $data = false;
    $Validate = new Validator();
    $Validate->validate([
        'category_name' => 'required',
        'subcategory' => 'required|min:3|max:50'
    ]);

    if(!$Validate->passed())
    {
        return response(['error' => $Validate->error()]);
    }

    $connection = new DB();
    $create = $connection->create('shop_subcategories', [
          'shop_categories_id' => Input::get('category_name'),
          'shop_subCategory_name' => Input::get('subcategory'),
    ]);

    if($create)
    {
        $data = true;
        Session::flash('success', 'The subcategory '.Input::get('subcategory').' has been added successfully!');
    }
    

    return response(['data' => $data]);
}







// ==========================================
// CUSTOMER DEACTIVATE BUTTON
// ==========================================
if(Input::post('is_customer_deactivate'))
{
    $customer_id = Input::get('customer_id');
    if(!empty($customer_id))
    {
        $connection = new DB();
        $customer = $connection->select('users')->where('id', $customer_id)->first();
        $is_deactivate	 = $customer->is_deactivate	 ? 0 : 1;

        $connection->update('users', [
            'is_deactivate	' => $is_deactivate,
            'is_active' => 0
        ])->where('id', $customer_id)->save();
    }
}





// ==========================================
// DELETE CUSTOMERS
// ==========================================
if(Input::get('delete_customer'))
{
    $data = false;
    $customer_id = Input::get('customer_id');
    if(!empty($customer_id))
    {
        $connection = new DB();
        $customer = $connection->select('users')->where('id', $customer_id)->first();
        if($customer)
        {
            if($customer->user_image)
            {
                Image::delete('../'.$customer->user_image);
            }
            $delete = $connection->delete('users')->where('id', $customer_id)->save();
            if($delete)
            {
               $data = true;
            }
        }
    }
    return response(['data' => $data]);
}





// ======================================
// UPLOAD CUSTOMER IMAGE
// ======================================
if(Input::get('upload_customer_image'))
{
    $data = false;
    $customer_id = Input::get('customer_id');
    if(!empty($customer_id))
    {
        $connection = new DB();
        $customer = $connection->select('users')->where('id', $customer_id)->first();
        if($customer)
        {
            if(Image::exists('image'))
            {
                $image = new Image();
                $file = Image::files('image');

                $file_name = Image::name('image', 'users');
                $image->resize_image($file, [ 'name' => $file_name, 'width' => 100, 'height' => 100, 'size_allowed' => 1000000,'file_destination' => '../shop/images/users/']);
                    
                $image_name = '/shop/images/users/'.$file_name;

                if(!$image->passed())
                {
                    return response(['error' => ['image' => $image->error()]]);
                }
                
                if($customer->user_image)
                {
                    Image::delete('../'.$customer->user_image);
                }
                
                $update = $connection->update('users', [
                    'user_image' => $image_name
                ])->where('id', $customer_id)->save();

                if($update)
                {
                    $data = $customer_id;
                }
            }
            
        }
    }
    return response(['data' => $data]);
}








// =========================================
// GET CUSTOMER EDIT IMAGE
// =========================================
if(Input::get('get_edit_customer_image'))
{
    $customer_id = Input::get('customer_id');
    if(!empty($customer_id))
    {
        $connection = new DB();
        $customer = $connection->select('users')->where('id', $customer_id)->first();
        return require_once('common/ajax-get-customer-img.php');
    }
}








// ======================================
// UPLOAD ADMIN IMAGE
// ======================================
if(Input::post('upload_admin_image'))
{
    $data = false;
    $admin_id = Input::get('admin_id');
    if(!empty($admin_id))
    {
        $connection = new DB();
        $admin = $connection->select('admins')->where('id', $admin_id)->first();
        if($admin)
        {
            if(Image::exists('image'))
            {
                $image = new Image();
                $file = Image::files('image');

                $file_name = Image::name('image', 'admins');
                $image->resize_image($file, [ 'name' => $file_name, 'width' => 100, 'height' => 100, 'size_allowed' => 1000000,'file_destination' => '../admin/images/admin-img/']);
                    
                $image_name = '/admin/images/admin-img/'.$file_name;

                if(!$image->passed())
                {
                    return response(['error' => ['image' => $image->error()]]);
                }
                
                if($admin->image)
                {
                    Image::delete('../'.$admin->image);
                }
                
                $update = $connection->update('admins', [
                    'image' => $image_name
                ])->where('id', $admin_id)->save();

                if($update)
                {
                    $data = $admin_id;
                }
            }
            
        }
    }
    return response(['data' => $data]);
}








// =========================================
// GET ADMIN EDIT IMAGE
// =========================================
if(Input::get('get_edit_admin_image'))
{
    $admin_id = Input::get('admin_id');
    if(!empty($admin_id))
    {
        $connection = new DB();
        $admin = $connection->select('admins')->where('id', $admin_id)->first();
        return require_once('common/ajax-edit-admin-img.php');
    }
}








// =========================================
// SET ORDER DELIVERY STATUS
// =========================================
if(Input::post('order_delivery_status'))
{
    $data = false;
    $order_id = Input::get('order_id');
    if(!empty($order_id))
    {
        $connection = new DB();
        $paid_order = $connection->select('paid_products')->where('paid_product_id', $order_id)->first();
        $is_delivered = $paid_order->is_delivered ? 0 : 1;
        $delivered_on =  !$paid_order->is_delivered ? date('Y-m-d H:i:s') : null;

        $connection->update('paid_products', [
            'is_delivered' => $is_delivered,
            'delivered_on' => $delivered_on
        ])->where('paid_product_id ', $order_id)->save();
        $data = true;
    }
    return response(['data' => $data]);
}








// =========================================
// SET ORDER SHIPPING STATUS
// =========================================
if(Input::post('order_shipping_status'))
{
    $data = false;
    $order_id = Input::get('order_id');
    if(!empty($order_id))
    {
        $connection = new DB();
        $paid_order = $connection->select('paid_products')->where('paid_product_id', $order_id)->first();
        $shipped_on = !$paid_order->shipped_on ? date('Y-m-d H:i:s') : null;

        $connection->update('paid_products', [
            'shipped_on' => $shipped_on
        ])->where('paid_product_id ', $order_id)->save();
        $data = true;
    }
    return response(['data' => $data]);
}







// =========================================
// SET ORDER SHIPPING STATUS
// =========================================
if(Input::post('cancle_refund_status'))
{
    $data = false;
    $cancle_id = Input::get('cancle_id');
    if(!empty($cancle_id))
    {
        $connection = new DB();
        $cancle_order = $connection->select('cancled_product')->where('cancled_id ', $cancle_id)->first();
        $is_refund = $cancle_order->is_refund ? 0 : 1;
        $refund_date = !$cancle_order->refund_date ? date('Y-m-d H:i:s') : null;

        $connection->update('cancled_product', [
            'is_refund' => $is_refund,
            'refund_date' => $refund_date
        ])->where('cancled_id ', $cancle_id)->save();
        $data = true;
    }
    return response(['data' => $data]);
}







// =========================================
// SET ORDER SHIPPING STATUS
// =========================================
if(Input::post('transaction_status'))
{
    $data = false;
    $reference = Input::get('reference');
    if(!empty($reference))
    {
        $connection = new DB();
        $transaction = $connection->select('shop_transactions')->where('reference', $reference)->first();

        $connection->update('shop_transactions', [
            'transaction_is_cancled' => 1
        ])->where('reference', $reference)->save();
        
        $location = url('/admin/transactions.php');
        return response(['location' => $location]);
    }
    return response(['data' => $data]);
}







// =================================================
//  UPLOAD APP LOGO IMAGE
// =================================================\

if(Input::post('upload_app_logo_image'))
{
    $data = false;
    if(Image::exists('app_logo'))
    {
        $image = new Image();
        $file = Image::files('app_logo');

        $file_name = Image::name('app_logo', 'logo');
        $image->resize_image($file, [ 'name' => $file_name, 'width' => 50, 'height' => 56, 'size_allowed' => 1000000,'file_destination' => '../admin/images/']);
            
        $image_name = '/admin/images/'.$file_name;

        if(!$image->passed())
        {
            return response(['error' => ['app_logo' => $image->error()]]);
        }
        
        $connection = new DB();
        $settings = $connection->select('settings')->where('id', 1)->first();
        if($settings->logo)
        {
            Image::delete('../'.$settings->logo);
        }
        
        $update = $connection->update('settings', [
            'logo' => $image_name
        ])->where('id', 1)->save();

        if($update)
        {
            $data =  true;
        }
    }
    return response(['data' => $data]);
}









// ==================================================
// ACTIVATE OR DEACTIVATE PAYSTACK SECRETE KEY
// ==================================================
if(Input::post('paystack_is_activate'))
{
    $data = false;
    $connection = new DB();
    $setting =  $connection->select('settings')->where('id', 1)->first();
    $is_activate = $setting->is_paystack_activate ? 0 : 1;

    $update = $connection->update('settings', [
            'is_paystack_activate' => $is_activate
        ])->where('id', 1)->save();
    if($update)
    {
        $data = true;
    }
    return response(['data' => $data]);
}







// ==========================================
//   GET APP LOGOS
// ==========================================
if(Input::post('get_app_logos'))
{
    $data = false;
    $connection = new DB();
    $banner = $connection->select('settings')->where('id', 1)->first();
    if($banner->logo)
    {
        return require_once('common/ajax-app-logo-img.php');
    }
}








// =================================================
//  UPLOAD APP LOGO IMAGE
// =================================================\

if(Input::post('upload_footer_logo_image'))
{
    $data = false;
    if(Image::exists('footer_logo'))
    {
        $image = new Image();
        $file = Image::files('footer_logo');

        $file_name = Image::name('footer_logo', 'footer_logo');
        $image->resize_image($file, [ 'name' => $file_name, 'width' => 50, 'height' => 56, 'size_allowed' => 1000000,'file_destination' => '../admin/images/']);
            
        $image_name = '/admin/images/'.$file_name;

        if(!$image->passed())
        {
            return response(['error' => ['footer_logo' => $image->error()]]);
        }
        
        $connection = new DB();
        $settings = $connection->select('settings')->where('id', 1)->first();
        if($settings->footer_logo)
        {
            Image::delete('../'.$settings->footer_logo);
        }
        
        $update = $connection->update('settings', [
            'footer_logo' => $image_name
        ])->where('id', 1)->save();

        if($update)
        {
            $data =  true;
        }
    }
    return response(['data' => $data]);
}









// ==========================================
//   GET APP LOGOS
// ==========================================
if(Input::post('get_footer_logos'))
{
    $data = false;
    $connection = new DB();
    $banner = $connection->select('settings')->where('id', 1)->first();
    if($banner->footer_logo)
    {
        return require_once('common/ajax-footer-logo-img.php');
    }
}






// ============================================
// UPDATE HOME BANNER IMAGE
// ============================================
if(Input::post('update_home_banner_image'))
{
    $data = false;
    if(Image::exists('home_banner'))
    {
        $image = new Image();
        $file = Image::files('home_banner');

        $file_name = Image::name('home_banner', 'home_banner');
        $image->resize_image($file, [ 'name' => $file_name, 'width' => 1920, 'height' => 1000, 'size_allowed' => 1000000,'file_destination' => '../shop/images/banner/']);
            
        $image_name = '/shop/images/banner/'.$file_name;

        if(!$image->passed())
        {
            return response(['error' => ['home_banner' => $image->error()]]);
        }
        
        $connection = new DB();
        $settings = $connection->select('settings')->where('id', 1)->first();
        if($settings->home_banner)
        {
            Image::delete('../'.$settings->home_banner);
        }
        
        $update = $connection->update('settings', [
            'home_banner' => $image_name
        ])->where('id', 1)->save();

        if($update)
        {
            $data =  true;
        }
    }
    return response(['data' => $data]);
}






// ========================================
// GET HOME BANNER IMAGE
// ========================================
if(Input::post('get_home_banner_img'))
{
    $data = false;
    $connection = new DB();
    $settings = $connection->select('settings')->where('id', 1)->first();
    return require_once('common/ajax-home-banner.php');
}







// ========================================
// DELETE HOME BANNER IMAGE
// ========================================
if(Input::post('delete_home_banner_img'))
{
    $data = false;
    $connection = new DB();
    $settings = $connection->select('settings')->where('id', 1)->first();
    if($settings->home_banner)
    {
        Image::delete('../'.$settings->home_banner);
    }

    $update = $connection->update('settings', [
        'home_banner' => null
    ])->where('id', 1)->save();

    if($update)
    {
        $data =  true;
    }
    return response(['data' => $data]);
}










// ============================================
// UPDATE CATEGORY BANNER IMAGE
// ============================================
if(Input::post('update_category_banner_image'))
{
    $data = false;
    if(Image::exists('category_banner'))
    {
        $image = new Image();
        $file = Image::files('category_banner');

        $file_name = Image::name('category_banner', 'category_banner');
        $image->resize_image($file, [ 'name' => $file_name, 'width' => 1920, 'height' => 1000, 'size_allowed' => 1000000,'file_destination' => '../shop/images/banner/']);
            
        $image_name = '/shop/images/banner/'.$file_name;

        if(!$image->passed())
        {
            return response(['error' => ['category_banner' => $image->error()]]);
        }
        
        $connection = new DB();
        $settings = $connection->select('settings')->where('id', 1)->first();
        if($settings->category_banner)
        {
            Image::delete('../'.$settings->category_banner);
        }
        
        $update = $connection->update('settings', [
            'category_banner' => $image_name
        ])->where('id', 1)->save();

        if($update)
        {
            $data =  true;
        }
    }
    return response(['data' => $data]);
}






// ========================================
// GET CATEGORY BANNER IMAGE
// ========================================
if(Input::post('get_category_banner_img'))
{
    $data = false;
    $connection = new DB();
    $settings = $connection->select('settings')->where('id', 1)->first();
    return require_once('common/ajax-category-banner.php');
}






// =========================================
// DELETE CATEGORY BANNER
// =========================================
if(Input::post('delete_category_banner_img'))
{
    $data = false;
    $connection = new DB();
    $settings = $connection->select('settings')->where('id', 1)->first();
    if($settings->category_banner)
    {
        Image::delete('../'.$settings->category_banner);
    }

    $update = $connection->update('settings', [
        'category_banner' => null
    ])->where('id', 1)->save();

    if($update)
    {
        $data =  true;
    }
    return response(['data' => $data]);
}








// ============================================
// UPDATE CART BANNER IMAGE
// ============================================
if(Input::post('update_cart_banner_image'))
{
    $data = false;
    if(Image::exists('cart_banner'))
    {
        $image = new Image();
        $file = Image::files('cart_banner');

        $file_name = Image::name('cart_banner', 'cart_banner');
        $image->resize_image($file, [ 'name' => $file_name, 'width' => 1920, 'height' => 1000, 'size_allowed' => 1000000,'file_destination' => '../shop/images/banner/']);
            
        $image_name = '/shop/images/banner/'.$file_name;

        if(!$image->passed())
        {
            return response(['error' => ['cart_banner' => $image->error()]]);
        }
        
        $connection = new DB();
        $settings = $connection->select('settings')->where('id', 1)->first();
        if($settings->cart_banner)
        {
            Image::delete('../'.$settings->cart_banner);
        }
        
        $update = $connection->update('settings', [
            'cart_banner' => $image_name
        ])->where('id', 1)->save();

        if($update)
        {
            $data =  true;
        }
    }
    return response(['data' => $data]);
}






// ========================================
// GET CART BANNER IMAGE
// ========================================
if(Input::post('get_cart_banner_img'))
{
    $data = false;
    $connection = new DB();
    $settings = $connection->select('settings')->where('id', 1)->first();
    return require_once('common/ajax-cart-banner.php');
}








// =========================================
// DELETE CART BANNER
// =========================================
if(Input::post('delete_cart_banner_img'))
{
    $data = false;
    $connection = new DB();
    $settings = $connection->select('settings')->where('id', 1)->first();
    if($settings->cart_banner)
    {
        Image::delete('../'.$settings->cart_banner);
    }

    $update = $connection->update('settings', [
        'cart_banner' => null
    ])->where('id', 1)->save();

    if($update)
    {
        $data =  true;
    }
    return response(['data' => $data]);
}








// ============================================
// UPDATE FORM BANNER IMAGE
// ============================================
if(Input::post('update_form_banner_image'))
{
    $data = false;
    if(Image::exists('form_banner'))
    {
        $image = new Image();
        $file = Image::files('form_banner');

        $file_name = Image::name('form_banner', 'form_banner');
        $image->resize_image($file, [ 'name' => $file_name, 'width' => 1920, 'height' => 1000, 'size_allowed' => 1000000,'file_destination' => '../shop/images/banner/']);
            
        $image_name = '/shop/images/banner/'.$file_name;

        if(!$image->passed())
        {
            return response(['error' => ['form_banner' => $image->error()]]);
        }
        
        $connection = new DB();
        $settings = $connection->select('settings')->where('id', 1)->first();
        if($settings->form_banner)
        {
            Image::delete('../'.$settings->form_banner);
        }
        
        $update = $connection->update('settings', [
            'form_banner' => $image_name
        ])->where('id', 1)->save();

        if($update)
        {
            $data =  true;
        }
    }
    return response(['data' => $data]);
}






// ========================================
// GET FORM BANNER IMAGE
// ========================================
if(Input::post('get_form_banner_img'))
{
    $data = false;
    $connection = new DB();
    $settings = $connection->select('settings')->where('id', 1)->first();
    return require_once('common/ajax-form-banner.php');
}






















