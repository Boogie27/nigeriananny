<?php if(Cookie::has('saved_course')):
    $savedCourses = json_decode(Cookie::get('saved_course'), true);
    foreach($savedCourses as $savedCourse):?>
        <li><a href="<?= url('/courses/detail.php?cid='.$savedCourse['course_id']) ?>"><?= ucfirst(substr($savedCourse['title'], 0, 20)).'...'?></a></li>
    <?php endforeach; ?>
<?php else: ?>
        <li>Empty</li>
<?php endif; ?>