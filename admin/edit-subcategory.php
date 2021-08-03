<?php include('../Connection.php');  ?>


<?php
if(!Admin_auth::is_loggedin())
{
  Session::delete('admin');
  return Redirect::to('login.php');
}

if(!Input::exists('get') || !Input::get('scid') || !is_numeric(Input::get('scid')))
{
    return Redirect::to('sub-category.php');
}


$connection = new DB();
$single_subcategory = $connection->select('shop_subcategories')->where('shop_subCategory_id ', Input::get('scid'))->first();
if(!$single_subcategory)
{
    return Redirect::to('category.php');
}


$categories = $connection->select('shop_categories')->get();








if(Input::post('edit_subcategory'))
{
    if(Token::check())
    {
        $validate = new DB();
        
        $validation = $validate->validate([
            'category' => 'required',
            'subcategory' => 'required|min:3|max:50',
        ]);

        if(!$validation->passed())
        {
            return back();
        }
        
        if($validation->passed())
        {
            $update = $connection->update('shop_subcategories', [
                        'shop_subCategory_name' => Input::get('subcategory'),
                        'shop_categories_id' => Input::get('category')
                    ])->where('shop_subCategory_id', Input::get('scid'))->save(); 
            if($update)
            {
                Session::flash('success', 'Subcategory has been updated successfully!');
                return back();
            }
        }
    }
}




// app banner settings
$banner =  $connection->select('settings')->where('id', 1)->first();

?>

<?php include('includes/header.php'); ?>


<!-- Main Header Nav -->
<?php include('includes/navigation.php') ?>

<!-- Main Header Nav For Mobile -->
<?php include('includes/mobile-navigation.php') ?>


<!-- Our Dashbord Sidebar -->
<?php include('includes/side-navigation.php') ?>


<!-- Our Dashbord -->
<div class="our-dashbord dashbord">
    <div class="dashboard_main_content">
        <div class="container-fluid">
            <div class="main_content_container">
                <div class="row">
                    <div class="col-lg-12">
                        <?php include('includes/mobile-drop-nav.php') ?><!-- mobile-navigation -->
                    </div>
                    <div class="col-lg-12">
                        <nav class="breadcrumb_widgets" aria-label="breadcrumb mb30">
                            <h4 class="title float-left">Manage Subcategory</h4>
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="<?= url('/admin/index.php') ?>">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><a href="<?= url('/admin/sub-category.php') ?>"> subcategory</a></li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-lg-12">
                        <div class="edit-product-form">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="product-detail">
                                        <div class="p-header">
                                            <h3 class="text-center">Edit subcategory </h3>
                                            <br>
                                        </div>
                                        <div class="form-center">
                                        <?php if(Session::has('success')): ?>
                                            <div class="alert-success text-center p-3 mb-2"><?= Session::flash('success') ?></div>
                                        <?php endif;?>
                                        <div class="alert-danger text-center p-3 mb-2 category_alert_danger" hidden></div>
                                            <form action="<?= current_url() ?>" method="post">
                                                 <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="ui_kit_select_box">
                                                            <div class="alert-category alert_1 text-danger"></div>
                                                            <label for="">Category</label>
                                                            <select name="category" class="selectpicker custom-select-lg mb-3">
                                                                <?php if($categories): 
                                                                    foreach($categories as $category): ?>
                                                                    <option value="<?= $category->category_id ?>" <?= $category->category_id == $single_subcategory->shop_categories_id ? 'selected' : '' ?>><?= $category->category_name ?></option>
                                                                    <?php endforeach; ?>
                                                                <?php else: ?>
                                                                <option value="local pickup">Category empty</option>
                                                                <?php endif;?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="form-group">
                                                            <?php  if(isset($errors['subcategory'])) : ?>
                                                                <div class="form-alert text-danger"><?= $errors['subcategory']; ?></div>
                                                            <?php endif; ?>
                                                            <input type="text" name="subcategory" class="form-control h50" value="<?= $single_subcategory->shop_subCategory_name ?? old('category_title')?>" placeholder="Subcategory">
                                                        </div>
                                                    </div>
                                                 </div>
                                                <button type="submit" name="edit_subcategory" class="btn bg-danger btn-log btn-block h50" style="color: #fff;">Submit</button>                                                 
                                                <?= csrf_token() ?>
                                            </form>
                                        </div>
                                        <br>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt50 mb50">
                    <div class="col-lg-6 offset-lg-3">
                        <div class="copyright-widget text-center">
                            <p class="color-black2"><?= $banner->alrights ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<a class="scrollToHome" href="#"><i class="flaticon-up-arrow-1"></i></a>
</div>












<a href="<?= url('/admin/ajax.php') ?>" class="ajax_url_tag" style="display: none;"></a>





<?php  include('includes/footer.php') ?>


