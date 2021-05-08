<?php include('../Connection.php');  ?>
<?php
if(!Admin_auth::is_loggedin())
{
    Session::delete('admin');
    Session::put('old_url', '/admin-course/edit-course.php?cid='.Input::get('cid'));
    return view('/admin/login');
}



// ******** CHECK IF COURSE WAS CLICKED ******//
if(!Input::exists('get') || !Input::get('cid'))
{
    return view('/admin-course/courses');
}



// ============================================
// CREATE EMPLOYEE
// ============================================
if(Input::post('update_course'))
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

    if($validation->passed())
    {
        $is_feature = Input::get('feature') == 1 ? 1 : 0;
        $course = $connection->select('courses')->where('course_id', Input::get('cid'))->first(); 
        
        $tutor = json_decode($course->tutor, true);

        $tutor_info = ["name" => Input::get('tutor_name'), "image" => $tutor['image'], "title" => Input::get('tutor_title'), "about" => Input::get('tutor_about')];
        
        $category = $connection->select('course_categories')->where('category_id', Input::get('category'))->first();

        $course = $connection->update('courses', [
            'title' => Input::get('course_title'),
            'categories_id' => Input::get('category'),
            'slug' => $category->category_slug,
            'duration' => Input::get('course_duration'),
            'course_size' => Input::get('course_size'),
            'video_link' => Input::get('video_link'),
            'description' => Input::get('description'),
            'course_for' => Input::get('course_for'),
            'tutor' => json_encode($tutor_info),
            'is_feature' => $is_feature
        ])->where('course_id', Input::get('cid'))->save();
        if($course->passed())
        {
            Session::flash('success', 'Course update successfully!');
            return back();
        }
    }
}






// ******* GET CATEGORIES ******************//
$course_categories = $connection->select('course_categories')->get();



// ******* GET COURSE ******************//
$course = $connection->select('courses')->where('course_id', Input::get('cid'))->first();
if(!$course)
{
    return view('/admin-course/courses');
}


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
                            <h4 class="title float-left">Edit course</h4>
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
                                        <div class="sr-head text-center"><h4>Edit course</h4></div><br>
                                        
                                            <div class="row">
                                                <div class="col-lg-6 col-sm-6">
                                                    <div class="form-group">
                                                        <div class="alert_label">
                                                            <?php  if(isset($errors['course_title'])) : ?>
                                                                <div class="text-danger"><?= $errors['course_title']; ?></div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <label for="">Course title:</label>
                                                        <input type="text" name="course_title" class="form-control h50" value="<?= $course->title ?? old('course_title')?>">
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
                                                                <option value="<?= $category->category_id ?>" <?= $course->categories_id == $category->category_id ? 'selected' : ''?>><?= $category->category_name ?></option>
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
                                                        <input type="text" name="course_duration" class="form-control h50" value="<?= $course->duration ?? old('course_duration')?>" placeholder="Example: 1:30:00">
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
                                                        <input type="text" name="course_size" class="form-control h50" value="<?= $course->course_size ??  old('course_size')?>" placeholder="Example: 1gig, 2gig">
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
                                                        <input type="text" name="video_link" class="form-control h50" value="<?= $course->video_link ?? old('video_link')?>" placeholder="Url link to the course">
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <?php $learns = $course->learn ? json_decode($course->learn, true) : array() ?>
                                                    <div class="what_learn" id="what_learn_container">
                                                        <?php if(count($learns)):?>
                                                        <ul class="ul_what_learn">
                                                            <?php foreach($learns as $key => $learn):?>
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
                                                        <textarea id="description" name="description" class="form-control" placeholder="Write something"><?= $course->description ?? old('description') ?></textarea>
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
                                                        <textarea id="course_for" name="course_for" class="form-control" placeholder="Write something"><?= $course->course_for ?? old('course_for') ?></textarea>
                                                        <script>
                                                                CKEDITOR.replace( 'course_for' );
                                                        </script> 
                                                    </div> 
                                                </div>
                                        
                                                <div class="col-lg-6">
                                                    <?php $tutor = json_decode($course->tutor, true)?>
                                                    <div class="form-group">
                                                        <div class="alert_label">
                                                            <?php  if(isset($errors['tutor_name'])) : ?>
                                                                <div class="text-danger"><?= $errors['tutor_name']; ?></div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <label for="">Tutor name:</label>
                                                        <input type="text" name="tutor_name" class="form-control h50" value="<?= $tutor['name'] ?? old('tutor_name') ?>">
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
                                                        <input type="text" name="tutor_title" class="form-control h50" value="<?= $tutor['title'] ?? old('tutor_title') ?>" placeholder="Example: Developer, tutor, singer...">
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
                                                        <textarea id="tutor_about" name="tutor_about" class="form-control" placeholder="Write something"><?= $tutor['about'] ?? old('tutor_about') ?></textarea>
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
                                                        <?php $tutor_img = $tutor['image'] ?? '/images/camera-icon.jpg' ?>
                                                        <img src="<?= asset($tutor_img) ?>" alt="camera" class="upload_tutor_img_open">
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
                                                        <?php $course_img = $course->course_poster ?? '/images/camera-icon.jpg' ?>
                                                        <img src="<?= asset($course_img) ?>" alt="camera" class="upload_course_img_open">
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
                                                            <input type="checkbox" id="course_feature_btn" <?= $course->is_feature ? 'checked' : ''?>>
                                                            <label class="cover_letter_btn" for="cover_letter_btn" style="font-size: 13px;">Feature course</label>
                                                            <input type="hidden" name="feature" id="course_feature_input" value="<?= $course->is_feature ?? old('feature')?>">
                                                        </div>	
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="form-group text-right">
                                                        <button type="submit" name="update_course" id="create_employee" class="btn btn-primary">Update course</button>
                                                    </div>
                                                </div>
                                            </div>

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
<a href="#" id="<?= Input::get('cid')?>" class="course_id_input" style="display: none;"></a>




<?php  include('includes/footer.php') ?>









<script>
$(document).ready(function(){

// ********* KEY PRESS EDIT WHAT YOU LEARN **********//
$("#what_you_learn_input").keypress(function(e){
    if(e.keyCode == 13 || e.which == 13){
        e.preventDefault();
        add_course();
    }
});

// ********* EDIT WHAT YOU LEARN **********//
function add_course()
{
    $(".alert_0").html('')
    var learn = $("#what_you_learn_input").val();
    var url = $(".ajax_url_page").attr('href')
    var course_id = $(".course_id_input").attr('id')
    if(validate_learn(learn)){
        return;
    }


   $.ajax({
		url: url,
		method: 'post',
		data: {
            course_id: course_id,
			what_to_learn: learn,
			edit_what_to_learn: 'edit_what_to_learn'
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
    var course_id = $(".course_id_input").attr('id')

    $.ajax({
		url: url,
		method: 'post',
		data: {
            course_id: course_id,
			get_edit_what_to_learn: 'get_edit_what_to_learn'
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
    var course_id = $(".course_id_input").attr('id')

    $.ajax({
		url: url,
		method: 'post',
		data: {
            key: key,
            course_id: course_id,
			delete_edit_what_to_learn: 'delete_edit_what_to_learn'
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







// ***********OPEN EDIT UPLOAD COURSE IMAGE ***********//
$("#upload_course_img_main_container").on('click', '.upload_course_img_open', function(){
    $("#course_img_input").click()
})

// *********** UPLOAD EDIT COURSE IMAGE ***********//
$("#course_img_input").on('change', function(e){
	$(".alert_2").html('');
    var url = $(".ajax_url_page").attr('href')
	var image = $("#course_img_input");
    var course_id = $(".course_id_input").attr('id')
    $(".upload_course_img_open").html('Please wait...')

	var data = new FormData();
	var image = $(image)[0].files[0];

    if(image == undefined) return;

    data.append('course_image', image);
    data.append('course_id', course_id)
    data.append('update_edit_course_image', true);

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
		   $(".upload_course_img_open").html('Upload image...')
		},
		error: function(){
			$('.alert_2').html('*Network error, try again later!');
		}
    });
});









// ***********OPEN UPLOAD EDIT TUTOR IMAGE ***********//
$("#upload_tutor_img_main_container").on('click', '.upload_tutor_img_open', function(){
    $("#tutor_img_input").click()
})

// *********** UPLOAD EDIT TUTOR IMAGE ***********//
$("#tutor_img_input").on('change', function(e){
	$(".alert_1").html('');
    var url = $(".ajax_url_page").attr('href')
	var image = $("#tutor_img_input");
    var course_id = $(".course_id_input").attr('id')
    $(".upload_tutor_img_open").html('Please wait...')



	var data = new FormData();
	var image = $(image)[0].files[0];
    
    if(image == undefined) return;

    data.append('tutor_image', image)
    data.append('course_id', course_id)
    data.append('update_edit_tutor_image', true)

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
		   $(".upload_tutor_img_open").html('Upload image...')
		},
		error: function(){
			$('.alert_1').html('*Network error, try again later!');
		}
    });
});


// end
});
</script>

                        