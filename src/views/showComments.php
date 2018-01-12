<?php
use helpers\Text;
use helpers\User;

$text = new Text();
$userHelper = new User;

$parentId = $this->id;
foreach ($this->comments as $post) {
    $user = @$userHelper->makeInfo($this->users['user:'.$post['uid']]);
    if(isset($post['touid'])) {
        $toUser = @$userHelper->makeInfo($this->users['user:'.$post['touid']]);

    }
    require (VIEWS.'/blocks/post.php');
    echo '</li>';
}

if(isset($this->limit)) {
    $counter = @$this->counters[$parentId];
    if($counter > 5) echo '<div class="comment-body show-comments" data-comment="'.$parentId.'">Показать все '.$counter.' коментари'.$text->humanNum($counter, 'й','я', 'ев').'</div>';
}

