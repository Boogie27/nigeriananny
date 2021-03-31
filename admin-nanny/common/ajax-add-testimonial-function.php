<?php if(Session::has('functions')): 
    $functions = Session::get('functions');?>
    <div class="inner-function">
        <?php foreach($functions as $key => $function): ?>
            <span><?= $function ?> <a href="#" class="funciton_cancle_btn" id="<?= $key ?>"><i class="fa fa-times"></i></a></span>
        <?php endforeach; ?>
    </div>
<?php endif; ?>