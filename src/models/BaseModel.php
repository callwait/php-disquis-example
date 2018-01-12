<?php

namespace models;

use Limen\RedModel\Model;

class BaseModel extends Model
{
    protected $key = '{model}:{id}';
    protected $type = 'hash';
    protected $range = false;

    public function getUser($id)
    {
        return $this->where('model', 'user')->where('id', $id)->hGetAll();
    }


    public function getUsers(array $id)
    {
        $this->type = 'hash';
        $this->key = '{model}:{id}';
        $this->newQuery();
        return $this->where('model', 'user')->whereIn('id', $id)->get();
    }


    public function add($model, $value, $ttl = null, $force = true)
    {
        $this->newQuery();
        $this->queryBuilder->whereEqual('model', $model)->whereEqual('id', 'counter')->firstQueryKey();
        $id = $this->incr();
        $value['id'] = $id;
        $this->newQuery();
        $this->queryBuilder->whereEqual('model', $model)->whereEqual('id', $id)->firstQueryKey();
        $this->hmset($value);
        return $value;
    }


    public function addComment($userId, $id, $msg, $replyId, $parentId)
    {
        $this->key = 'comments:{id}:{type}';
        $this->newQuery();
        $this->queryBuilder->whereEqual('id', $id)->whereEqual('type', 'counter')->firstQueryKey();
        $pk = $this->hincrby(0, 1);

        $comment = [
            'id' => $pk,
            'uid' => $userId,
            'msg' => $msg,
            'rid' => $replyId,
            't' => time()
        ];

        if ($parentId > 0) {
            $replyId = $parentId . ":" . $replyId;
            $id = $id . ":" . $parentId;
            $this->hincrby($parentId, 1);
        }

        $this->newQuery();
        $replyComment = $this->where('id', '1')->where('type', $replyId)->first();
        if (isset($replyComment['uid'])) {
            $comment['touid'] = $replyComment['uid'];

        }

        $this->newQuery();
        $this->queryBuilder->whereEqual('id', $id)->whereEqual('type', $pk)->firstQueryKey();
        $this->hmset($comment);

        $this->newQuery();
        $this->queryBuilder->whereEqual('id', $id)->whereEqual('type', 'list')->firstQueryKey();
        $this->lpush($pk);
    }

    public function getComments()
    {
        $this->type = 'list';
        $this->key = 'comments:{id}:{type}';
        $this->newQuery();
        return $this;
    }


    public function __construct($redis)
    {
        $this->redClient = $redis;
        $this->newQuery();
        $this->setCommandFactory();
    }

    public function setType($type)
    {
        $this->type = $type;
    }


    public function getRange()
    {
        return $this->range;
    }


    public function setRange($var)
    {
        return $this->range = $var;
    }

    protected function executeCommand($command)
    {

        $script = $command->getScript();
        if ($r = $this->getRange()) {
            $script = str_replace("('lrange', v, 0, -1)", "('lrange', v, $r)", $script);
        }

        $data = $this->evalScript($script, $command->getArguments());
        return $command->parseResponse($data);
    }

    protected function evalScript($script, array $keys = [], array $argv = [])
    {
        if ($this->redClient instanceof \Redis) {
            return $this->redClient->eval($script, array_merge($keys, $argv), count($keys));
        }
        $params = array_merge([$script, count($keys)], $keys, $argv);
        return call_user_func_array([$this->redClient, 'eval'], $params);
    }

    public function prepareKeys($forGet = true)
    {
        $queryKeys = $this->queryBuilder->getQueryKeys();

        if (!$queryKeys) {
            $queryKeys = [$this->key];
        }
        return $queryKeys;
    }


    public function toLists($arr)
    {
        foreach ($arr as $k => $v) {
            $arr[$k] = $v . ":list";
        }
        return $arr;
    }

    public function makeSubKey($arr)
    {
        $out = [];
        foreach ($arr as $k => $v) {
            $ex = explode(":", $k);
            foreach ($v as $value) {
                $out[] = $ex[2] . ":" . $value;
            }
        }
        return $out;
    }

    public function keysToTree($arr)
    {
        $out = [];
        foreach ($arr as $k => $v) {
            $ex = explode(":", $k);
            if (isset($ex[3])) {
                $out[$ex[2]]['sub'][$ex[3]] = $v;
            } else {
                $out[$ex[2]] = $v;
            }
        }
        return $out;
    }

    public function getUsersKeys($arr)
    {
        $out = $users = [];
        foreach ($arr as $k => $v) {
            if (isset($v['uid'])) $out[$v['uid']] = 1;
        }
        foreach ($out as $k => $v) {
            $users[] = $k;
        }
        return $users;

    }
}
