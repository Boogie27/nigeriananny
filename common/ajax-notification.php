<?php if(count($notifications)): ?>
<?php foreach($notifications as $notification): ?>
    <li class="not-link">
        <a href="<?= url($notification->link)?>">
            <h5><?= $notification->name ?></h5>
            <p><?= $notification->body?></p>
        </a>
    </li>
<?php endforeach; ?>
<?php else: ?>
    No notification
<?php endif; ?>