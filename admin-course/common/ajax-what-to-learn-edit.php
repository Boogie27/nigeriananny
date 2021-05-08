<?php if(count($learns)):?>
    <ul class="ul_what_learn">
        <?php foreach($learns as $key => $learn):?>
        <li>
            <span><?= $learn ?> <a href="#" id="<?= $key ?>" class="delete-what-to-learn"><i class="fa fa-times"></i></a></span>
        </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>