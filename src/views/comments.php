<?php
use helpers\Text;
use helpers\User;

$text = new Text();
$userHelper = new User;


$showWriteBlock = true;
if(isset($this->onlyComments) and $this->onlyComments == true) {
    $showWriteBlock = false;
}
?>


<?php if($showWriteBlock) { ?>
<div id="myModal" class="modal">

    <div class="modal-content">
        <span class="close">&times;</span>
        <p></p>
    </div>

</div>

        <div class="post" style="max-width: 600px;">


            <div class="post-footer">


                <?php
                if(isset($this->user['id'])) {
                    $this->user = $userHelper->makeInfo($this->user);
                ?>
                <div class="pull-left image" style="position: relative;">
                    <img src="<?=$this->user['avatar_url'];?>" class="avatar" alt="user profile image">
                    <div class="wp-social-wrap">
                        <div class="wp-social wp-social-sm wp-<?=$this->user['provider'];?>">
                            <?=$text->loadSvg($this->user['provider']);?>
                        </div>
                    </div>
                </div>
                <? } ?>
                <div id="mkForm">

                    <form class="mkWrite" action="/write/1" method="POST" data-comment="0" data-pid="0">
                        <div class="input-group">
                            <textarea class="form-control mkText" placeholder="Add a comment" type="text"></textarea>
                            <span class="input-group-addon mkWriteButton">
                              OK
                            </span>
                        </div>
                    </form>
                </div>



                <div class="panel-info">

                        <span style="padding:5px; border-bottom: 1px solid #bababa;float: right;" onclick="dropDown();" class="dropbtn">
                            Комментарии: <?=@$this->counters[0];?> <svg xmlns="http://www.w3.org/2000/svg" width="14.394" height="8.158" viewBox="0 0 14.394 8.158"><path d="M6.496 7.802L.41 1.75C.023 1.365.023.74.41.356c.387-.385 1.015-.385 1.402 0L7.197 5.71 12.582.356c.387-.385 1.015-.385 1.402 0s.387 1.01 0 1.394L7.897 7.802c-.193.193-.447.29-.7.29s-.507-.098-.7-.29z"></path></svg>
                        <div id="myDropdown" class="dropdown-content">
                            <a href="#">Новые сверху</a>
                            <a href="#">Старые сверху</a>
                        </div>
                                      </span>

                    <span  style="margin-right:5px; padding:5px; float: right;">Просмотров: <?=$this->viewsTotal;?></span>


                </div>

                <ul class="comments-list">
<?php } ?>
                    <?php
                    //echo '<div class="comment-body show-comments" style="border-top:0px; border-bottom: 1px solid #bababa;" >Показать новые коментарии</div>';

                    $users = $this->users;
                    $totalCounter = @$this->counters[0];

                    foreach ($this->comments as $post) {

                        $user = @$userHelper->makeInfo($users['user:'.$post['uid']]);
                        $counter = @$this->counters[$post['id']];
                        $parentId = $post['id'];
                        require (VIEWS.'/blocks/post.php');

                            echo '<div class="hide-comments hide'.$parentId.'" data-comment="'.$parentId.'"></div>';

                            echo '<ul class="comments-list commentsBlock'.$parentId.'">';
                            if(!empty($post['sub'])) {

                                foreach ($post['sub'] as $post) {
                                    $user = @$userHelper->makeInfo($users['user:'.$post['uid']]);
                                    if(isset($post['touid'])) {
                                        $toUser = @$userHelper->makeInfo($users['user:'.$post['touid']]);
                                    }

                                    require (VIEWS.'/blocks/post.php');
                                    echo '</li>';
                                }

                                if($counter > 5) echo '<div class="comment-body show-comments" data-comment="'.$parentId.'">Показать все '.$counter.' коментари'.$text->humanNum($counter, 'й','я', 'ев').'</div>';
                            }
                            echo '</ul>';


                        echo '</li>';
                    }


                    if($this->showNext) {
                        echo '<div class="comment-body show-comments" data-offset="'.$this->offset.'">Показать предыдущие коментарии</div>';
                        echo '<div id="containerNext'.$this->offset.'"></div>';
                    }
?>
                        </ul>
                    </li>
                </ul>
<?php if($showWriteBlock) { ?>
            </div>
        </div>

<?php
if(isset($this->user['id'])) {
?>
<script>
    var userId = <?=$this->user['id'];?>;
</script>
<? } } ?>


