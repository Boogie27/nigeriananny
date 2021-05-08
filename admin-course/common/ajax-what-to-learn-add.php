<?php if(Session::has('learn')):?>
<ul class="ul_what_learn">
    <?php foreach(Session::get('learn') as $key => $learn):?>
    <li>
        <span><?= $learn ?> <a href="#" id="<?= $key ?>" class="delete-what-to-learn"><i class="fa fa-times"></i></a></span>
    </li>
    <?php endforeach; ?>
</ul>
<?php endif; ?>