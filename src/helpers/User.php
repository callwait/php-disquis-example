<?php
namespace helpers;

class User
{
    public function makeInfo($user)
    {
        if(!isset($user['avatar_url'])) {
            $user['avatar_url'] = 'css/av.png';
        }
        return $user;
    }
}
?>