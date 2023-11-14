<?php include('../Connection_Admin.php');  ?>
<?php
if(!Admin_auth::is_loggedin())
{
    Session::delete('admin');
    Session::put('old_url', '/admin-course/add-course');
    return view('/admin/login');
}



// ============================================
// CREATE EMPLOYEE
// ============================================
if(Input::post('create_course'))
{
    if(Token::check())
    {
        $validate = new DB();
    
        $validation = $validate->validate([
            'course_title' => 'required|min:3|max:50',
            'category' => 'required',
            'course_duration' => 'required',
            'course_size' => 'required',
            'video_link' => 'required',
            'description' => 'required|min:6|max:5000',
            'course_for' => 'required|min:6|max:5000',
            'tutor_title' => 'required|min:3|max:100',
            'tutor_name' => 'required|min:3|max:50',
            'tutor_about' => 'required|min:3|max:500',
        ]);

        if(!Session::has('learn'))
        {
            Session::errors('errors', ['what_you_learn' => '*What to learn is required']);
            return back();
        }

        if(!Cookie::has('tutor_img'))
        {
            Session::errors('errors', ['tutor_image' => '*Tutor image is required']);
            return back();
        }
        if(!Cookie::has('course_img'))
        {
            Session::errors('errors', ['course_image' => '*Course image is required']);
            return back();
        }

        if(!$validation->passed())
        {
            return back();
        }

        if($validation->passed())
        {
            $is_feature = Input::get('feature') == 1 ? 1 : 0;
            $tutor_info = ["name" => Input::get('tutor_name'), "image" => Cookie::get('tutor_img'), "title" => Input::get('tutor_title'), "about" => Input::get('tutor_about')];
            $category = $connection->select('course_categories')->where('category_id', Input::get('category'))->first();

            $course = $connection->create('courses', [
                'title' => Input::get('course_title'),
                'categories_id' => Input::get('category'),
                'slug' => $category->category_slug,
                'duration' => Input::get('course_duration'),
                'course_size' => Input::get('course_size'),
                'video_link' => Input::get('video_link'),
                'description' => Input::get('description'),
                'course_for' => Input::get('course_for'),
                'learn' => json_encode(Session::get('learn')),
                'course_poster' => Cookie::get('course_img'),
                'tutor' => json_encode($tutor_info),
                'is_feature' => $is_feature
            ]);
            if($course->passed())
            {
                Session::delete('learn');
                Cookie::delete('course_img');
                Cookie::delete('tutor_img');
                Session::flash('success', 'Course created successfully!');
                return view('/admin-course/courses');
            }
        }
    }
}






// ******* GET CATEGORIES ******************//
$course_categories = $connection->select('course_categories')->get();



// ===============================================
// app banner settings
// ===========================================
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
                    <div class="alert alert-danger text-center p-3 mb-2 page_alert_danger" style="display: none;"></div>
                        <nav class="breadcrumb_widgets" aria-label="breadcrumb mb30">
                            <h4 class="title float-left">Add course</h4>
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item"><a href="<?= url('/admin-course') ?>">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><a href="<?= url('/admin-course/courses') ?>">Courses</a></li>
                            </ol>
                        </nav>
                    </div>
                    
                    <div class="col-lg-12"><!-- content start-->
                            <div class="mobile-alert">
                                <?php if(Session::has('error')): ?>
                                    <div class="alert-danger text-center p-3 mb-2"><?= Session::flash('error') ?></div>
                                <?php endif; ?>
                                <?php if(Session::has('success')): ?>
                                    <div class="alert-success text-center p-3 mb-2"><?= Session::flash('success') ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="account-x">
                                <div class="account-x-body" id="account-x-body"><br>
                                    <form action="<?= current_url()?>" method="POST" enctype="multipart/form-data">
                                        <div class="sr-head text-center"><h4>Create course</h4></div><br>
                                        
                                            <div class="row">
                                                <div class="col-lg-6 col-sm-6">
                                                    <div class="form-group">
                                                        <div class="alert_label">
                                                            <?php  if(isset($errors['course_title'])) : ?>
                                                                <div class="text-danger"><?= $errors['course_title']; ?></div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <label for="">Course title:</label>
                                                        <input type="text" name="course_title" class="form-control h50" value="<?= old('course_title')?>">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="ui_kit_select_box">
                                                        <div class="alert_label">
                                                            <?php  if(isset($errors['category'])) : ?>
                                                                <div class="form-alert text-danger"><?= $errors['category']; ?></div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <label for="">Category</label>
                                                        <select name="category" class="selectpicker custom-select-lg mb-3" id="add_product_category_btn">
                                                            <?php foreach($course_categories as $category): ?>
                                                                <option value="<?= $category->category_id ?>"><?= $category->category_name ?></option>
                                                            <?php endforeach;?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="form-group">
                                                        <div class="alert_label">
                                                            <?php  if(isset($errors['course_duration'])) : ?>
                                                                <div class="text-danger"><?= $errors['course_duration']; ?></div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <label for="">Duration:</label>
                                                        <input type="text" name="course_duration" class="form-control h50" value="<?= old('course_duration')?>" placeholder="Example: 1:30:00">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="form-group">
                                                        <div class="alert_label">
                                                            <?php  if(isset($errors['course_size'])) : ?>
                                                                <div class="text-danger"><?= $errors['course_size']; ?></div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <label for="">Course size:</label>
                                                        <input type="text" name="course_size" class="form-control h50" value="<?= old('course_size')?>" placeholder="Example: 1gig, 2gig">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6">
                                                    <div class="form-group">
                                                        <div class="alert_label">
                                                            <?php  if(isset($errors['video_link'])) : ?>
                                                                <div class="text-danger"><?= $errors['video_link']; ?></div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <label for="">Course link:</label>
                                                        <input type="text" name="video_link" class="form-control h50" value="<?= old('video_link')?>" placeholder="Url link to the course">
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="what_learn" id="what_learn_container">
                                                        <?php if(Session::has('learn')):?>
                                                        <ul class="ul_what_learn">
                                                            <?php foreach(Session::get('learn') as $key => $learn):?>
                                                            <li>
                                                                <span><?= $learn ?> <a href="#" id="<?= $key ?>" class="delete-what-to-learn"><i class="fa fa-times"></i></a></span>
                                                            </li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="alert_label">
                                                            <?php  if(isset($errors['what_you_learn'])) : ?>
                                                                <div class="text-danger"><?= $errors['what_you_learn']; ?></div>
                                                            <?php endif; ?>
                                                            <div class="text-danger alert_0"></div>
                                                        </div>
                                                        <label for="">What to learn:</label>
                                                        <input type="text" id="what_you_learn_input" class="form-control h50" value="" placeholder="What your learn...">
                                                    </div>
                                                </div>
            
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <div class="alert_label">
                                                            <?php  if(isset($errors['description'])) : ?>
                                                                <div class="form-alert text-danger"><?= $errors['description']; ?></div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <label for="label">Description:</label>
                                                        <textarea id="description" name="description" class="form-control" placeholder="Write something"><?= old('description') ?></textarea>
                                                        <script>
                                                                CKEDITOR.replace( 'description' );
                                                        </script> 
                                                    </div> 
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <div class="alert_label">
                                                            <?php  if(isset($errors['course_for'])) : ?>
                                                                <div class="form-alert text-danger"><?= $errors['course_for']; ?></div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <label for="label">Who course is for:</label>
                                                        <textarea id="course_for" name="course_for" class="form-control" placeholder="Write something"><?= old('course_for') ?></textarea>
                                                        <script>
                                                                CKEDITOR.replace( 'course_for' );
                                                        </script> 
                                                    </div> 
                                                </div>
                                        
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <div class="alert_label">
                                                            <?php  if(isset($errors['tutor_name'])) : ?>
                                                                <div class="text-danger"><?= $errors['tutor_name']; ?></div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <label for="">Tutor name:</label>
                                                        <input type="text" name="tutor_name" class="form-control h50" value="<?= old('tutor_name') ?>">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <div class="alert_label">
                                                            <?php  if(isset($errors['tutor_title'])) : ?>
                                                                <div class="text-danger"><?= $errors['tutor_title']; ?></div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <label for="">Tutor title:</label>
                                                        <input type="text" name="tutor_title" class="form-control h50" value="<?= old('tutor_title') ?>" placeholder="Example: Developer, tutor, singer...">
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <div class="alert_label">
                                                            <?php  if(isset($errors['tutor_about'])) : ?>
                                                                <div class="form-alert text-danger"><?= $errors['tutor_about']; ?></div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <label for="label">Tutor about:</label>
                                                        <textarea id="tutor_about" name="tutor_about" class="form-control" placeholder="Write something"><?= old('tutor_about') ?></textarea>
                                                        <script>
                                                                CKEDITOR.replace( 'tutor_about' );
                                                        </script> 
                                                    </div> 
                                                </div> 
                                                <div class="col-lg-6" id="upload_tutor_img_main_container">
                                                    <div class="alert_label">
                                                        <?php  if(isset($errors['tutor_image'])) : ?>
                                                            <div class="form-alert text-danger"><?= $errors['tutor_image']; ?></div>
                                                        <?php endif; ?>
                                                        <div class="alert_1 text-danger"></div>
                                                    </div>
                                                    <label for="">Tutor image:</label><br>
                                                    <div class="tutor-img-preview img">
                                                        <?php $tutor_img = Cookie::has('tutor_img') ? Cookie::get('tutor_img') : '/images/camera-icon.jpg' ?>
                                                        <img src="<?= asset($tutor_img) ?>" alt="camera" class="upload_tutor_img_open">
                                                        <a href="#" class="img-delete-btn" id="delete_add_tutor_image" title="Delete tutor image"><i class="fa fa-times text-danger"></i></a>
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="file" id="tutor_img_input" style="display: none;">
                                                        <div><b>Size:</b> 1MB max</div>
                                                        <label for="" class="upload-course-img-btn upload_tutor_img_open">Upload image...</label>
                                                       
                                                    </div>
                                                </div>
                                                <div class="col-lg-6" id="upload_course_img_main_container">
                                                    <div class="alert_label">
                                                        <?php  if(isset($errors['course_image'])) : ?>
                                                            <div class="form-alert text-danger"><?= $errors['course_image']; ?></div>
                                                        <?php endif; ?>
                                                        <div class="alert_2 text-danger"></div>
                                                    </div>
                                                    <label for="">Course image:</label><br>
                                                    <div class="course-img-preview">
                                                        <?php $course_img = Cookie::has('course_img') ? Cookie::get('course_img') : '/images/camera-icon.jpg' ?>
                                                        <img src="<?= asset($course_img) ?>" alt="camera" class="upload_course_img_open">
                                                        <a href="#" class="img-delete-btn" id="delete_add_course_image" title="Delete course image"><i class="fa fa-times text-danger"></i></a>
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="file" id="course_img_input" style="display: none;">
                                                        <div><b>Size:</b> 1MB max</div>
                                                        <label for="" class="upload-course-img-btn upload_course_img_open">Upload image...</label>
                                                       
                                                    </div>
                                                </div>
                                               
                                                <div class="col-lg-6">
                                                    <div class="form-group"><br>
                                                        <div class="apply_checkbox_d">
                                                            <input type="checkbox" id="course_feature_btn" <?= old('feature') ? 'checked' : ''?>>
                                                            <label class="cover_letter_btn" for="cover_letter_btn" style="font-size: 13px;">Feature course</label>
                                                            <input type="hidden" name="feature" id="course_feature_input" value="<?= old('feature') ? '1' : ''?>">
                                                        </div>	
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="form-group text-right">
                                                        <button type="submit" name="create_course" id="create_employee" class="btn btn-primary">Create course</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <?= csrf_token() ?>
                                        </form>
                                </div>
                            </div>

                        </div><!-- content end-->

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
                       
                        




<a href="<?= url('/admin-course/ajax.php') ?>" class="ajax_url_page" style="display: none;"></a>




<?php  include('includes/footer.php') ?>









<script>
$(document).ready(function(){

// ********* KEY PRESS GET WHAT YOU LEARN **********//
$("#what_you_learn_input").keypress(function(e){
    if(e.keyCode == 13 || e.which == 13){
        e.preventDefault();
        add_course();
    }
});

// ********* GET WHAT YOU LEARN **********//
function add_course()
{
    $(".alert_0").html('')
    var learn = $("#what_you_learn_input").val();
    var url = $(".ajax_url_page").attr('href')

    if(validate_learn(learn)){
        return;
    }

   $.ajax({
		url: url,
		method: 'post',
		data: {
			what_to_learn: learn,
			add_what_to_learn: 'add_what_to_learn'
		},
		success: function(response){
            var data = JSON.parse(response);
            if(data.error){
                $(".alert_0").html(data.error.what_to_learn)
            }else if(data.data){
                get_what_to_learn()
            }
		},
        error: function(){
            $(".alert_0").html('*Network error, try again!')
        }
	});
}



function validate_learn(learn){
    var state = false
    if(learn == '')
    {   
        state = true;
        $(".alert_0").html('*What to learn field is required')
    }
    if(learn.length > 100){
        state = true;
        $(".alert_0").html('*Must be maximum of 100 characters')
    }
    if(learn.length < 6){
        state = true;
        $(".alert_0").html('*Must be minimum of 6 characters')
    }
    return state;
}





// **** POPULATE WHAT TO LEARN ******//
function get_what_to_learn(){
    var url = $(".ajax_url_page").attr('href')
    $.ajax({
		url: url,
		method: 'post',
		data: {
			get_what_to_learn: 'get_what_to_learn'
		},
		success: function(response){
            $("#what_learn_container").html(response)
		},
	});
}





// ************ DELETE WHAT TO LEARN ***********//
$('#what_learn_container').on('click', '.delete-what-to-learn', function(e){
    e.preventDefault();
    var key = $(this).attr('id')
    var url = $(".ajax_url_page").attr('href')

    $.ajax({
		url: url,
		method: 'post',
		data: {
            key: key,
			delete_what_to_learn: 'delete_what_to_learn'
		},
		success: function(response){
            var data = JSON.parse(response);
            if(data.data){
                get_what_to_learn()
            }else{
                $(".alert_0").html('*Network error, try again!')
            }
		},
        error: function(){
            $(".alert_0").html('*Network error, try again!')
        }
	});
})






// ********* COURSE FEATURE **********//
$("#course_feature_btn").change(function(){
    $("#course_feature_input").val('')
    if($(this).prop('checked')){
        $("#course_feature_input").val(1)
    }
})







// ***********OPEN UPLOAD COURSE IMAGE ***********//
$("#upload_course_img_main_container").on('click', '.upload_course_img_open', function(){
    $("#course_img_input").click()
})

// *********** UPLOAD COURSE IMAGE ***********//
$("#course_img_input").on('change', function(e){
	$(".alert_2").html('');
    var url = $(".ajax_url_page").attr('href')
	var image = $("#course_img_input");
    $(".upload_course_img_open").html('Please wait...')

	var data = new FormData();
	var image = $(image)[0].files[0];

    data.append('course_image', image);
    data.append('update_course_image', true);

	$.ajax({
        url: url,
        method: "post",
        data: data,
        contentType: false,
        processData: false,
        success: function (response){
           var data = JSON.parse(response);
           if(data.error){
				$('.alert_2').show();
				$('.alert_2').html(data.error.course_image);
           }else if(data.data){
               $(".course-img-preview img").attr('src', data.data)
           }else{
				$('.alert_2').html('*Network error, try again later!');
		   }
		   console.log(response)
		   $(".upload_course_img_open").html('Upload image...')
		},
		error: function(){
			$('.alert_2').html('*Network error, try again later!');
		}
    });
});









// ***********OPEN UPLOAD TUTOR IMAGE ***********//
$("#upload_tutor_img_main_container").on('click', '.upload_tutor_img_open', function(){
    $("#tutor_img_input").click()
})

// *********** UPLOAD TUTOR IMAGE ***********//
$("#tutor_img_input").on('change', function(e){
	$(".alert_1").html('');
    var url = $(".ajax_url_page").attr('href')
	var image = $("#tutor_img_input");
    $(".upload_tutor_img_open").html('Please wait...')

	var data = new FormData();
	var image = $(image)[0].files[0];

    data.append('tutor_image', image);
    data.append('update_tutor_image', true);

	$.ajax({
        url: url,
        method: "post",
        data: data,
        contentType: false,
        processData: false,
        success: function (response){
           var data = JSON.parse(response);
           if(data.error){
				$('.alert_1').show();
				$('.alert_1').html(data.error.course_image);
           }else if(data.data){
               $(".tutor-img-preview img").attr('src', data.data)
           }else{
				$('.alert_1').html('*Network error, try again later!');
		   }
		   console.log(response)
		   $(".upload_tutor_img_open").html('Upload image...')
		},
		error: function(){
			$('.alert_1').html('*Network error, try again later!');
		}
    });
});







// ******** DELETE TUTOR IMAGE IN ADD COURSE PAGE ***********//
$("#delete_add_tutor_image").click(function(e){
    e.preventDefault()
    var url = $(".ajax_url_page").attr('href')
    $(".preloader-container").show();

    $.ajax({
        url: url,
        method: "post",
        data: {
            delete_add_tutor_image: 'delete_add_tutor_image'
        },
        success: function (response){
           var data = JSON.parse(response);
            if(data.data){
               $(".tutor-img-preview img").attr('src', data.data)
           }
		   console.log(response)
           $(".preloader-container").hide();
		},
		error: function(){
            $(".preloader-container").hide();
			$('.alert_1').html('*Network error, try again later!');
		}
    });
})





// ******** DELETE COURSE IMAGE IN ADD COURSE PAGE ***********//
$("#delete_add_course_image").click(function(e){
    e.preventDefault()
    var url = $(".ajax_url_page").attr('href')
    $(".preloader-container").show();

    $.ajax({
        url: url,
        method: "post",
        data: {
            delete_add_course_image: 'delete_add_course_image'
        },
        success: function (response){
           var data = JSON.parse(response);
            if(data.data){
                $(".course-img-preview img").attr('src', data.data)
           }
		   console.log(response)
           $(".preloader-container").hide();
		},
		error: function(){
            $(".preloader-container").hide();
			$('.alert_1').html('*Network error, try again later!');
		}
    });
})








// end
});
</script>

                        