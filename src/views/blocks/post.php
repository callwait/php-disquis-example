<li class="comment" data-comment="<?=$post['id'];?>">
<a class="pull-left" href="<?=@$user['html_url'];?>" target="_blank">


        <div style="position: relative;">
        <img class="avatar" src="<?=$user['avatar_url'];?>" alt="avatar">
                <div class="wp-social-wrap">
                        <div class="wp-social wp-social-sm wp-<?=$user['provider'];?>">
                                <?=$text->loadSvg($user['provider']);?>
                        </div>
                </div>
        </div>
</a>

<div class="comment-body">
        <div class="comment-heading">
            <h4 class="user"><?=$user['username'];?></h4>
                <?php
                if(isset($toUser['username'])) {
                        echo '<h5 class="time">ответил <a href="'.@$toUser['html_url'].'" target="_blank">'.$toUser['username'].'</a></h5>';
                }
                ?>
                <h4 class="time"><?=$text->relativeTime((int)$post['t'])?></h4>
        </div>
        <p><?=$post['msg'];?></p>

        <div class="b-comment__actions">
        <span class="b-comment__reply">
        <a class="" ><span class="b-comment__reply__text mkMakeReply"  data-comment="<?=$post['id'];?>" data-pid="<?=$parentId;?>">Ответить</span>
        <svg xmlns="http://www.w3.org/2000/svg" width="14.394" height="8.158" viewBox="0 0 14.394 8.158"><path d="M6.496 7.802L.41 1.75C.023 1.365.023.74.41.356c.387-.385 1.015-.385 1.402 0L7.197 5.71 12.582.356c.387-.385 1.015-.385 1.402 0s.387 1.01 0 1.394L7.897 7.802c-.193.193-.447.29-.7.29s-.507-.098-.7-.29z"></path></svg>
        </a></span>
        <div id="replyComment<?=$post['id'];?>" class="mkCommentReply"></div>
        </div>
</div>
