<?php

namespace helpers;

class RedisSessionHandler extends \SessionHandler implements \SessionHandlerInterface
{
    const STATE_SESSION_WRITE = 'state_session_write';
    const STATE_SESSION_READ = 'state_session_read';
    const STATE_SESSION_CLOSE = 'state_session_close';

    private $dbNumber = 0;
    private $ttl = 0;
    private $prefix = '';
    private $redis = NULL;
    private $state = null;
    private $sessionId = '';

    public function __construct($redis, $database = 0, $ttl = 1800, $prefix = 'session:')
    {
        $this->redis = $redis;
        $this->dbNumber = $database;
        $this->prefix = $prefix;
        $this->ttl = intval($ttl);

        $result = session_set_save_handler(
            array(&$this, "open"),
            array(&$this, "close"),
            array(&$this, "read"),
            array(&$this, "write"),
            array(&$this, "destroy"),
            array(&$this, "cleanup")
        );
        session_register_shutdown();
        if ($result == false) {
            throw new \Exception("Fail to sets user-level session storage functions.");
        }
    }

    public function read($sessionId)
    {
        //$this->setState(self::STATE_SESSION_READ);
        $sessionId = $this->prefix . $sessionId;
        $this->sessionId = $sessionId;
        //$multi = $this->redis->multi(\Redis::PIPELINE);
        //$multi->select($this->dbNumber);
        //$multi->expire($sessionId, $this->ttl);
        $exec = $this->redis->hGetAll($sessionId);
        //$exec = $multi->exec();
        if (!empty($exec)) {
            $_SESSION = $exec;
        }
        if (!is_array($_SESSION)) $_SESSION = array();
        return session_encode();
    }

    public function write($sessionId, $data)
    {
        $sessionId = $this->prefix . $sessionId;
        $data = array();
        foreach ($_SESSION as $key => $value) {
            $data[$key] = (string)strval($value);
        }
        $multi = $this->redis->multi(\Redis::PIPELINE);
        $multi->hmset($sessionId, $data);
        $multi->expire($sessionId, $this->ttl);
        $multi->exec();
        return true;
    }

    public function save()
    {
        $sessionId = $this->sessionId;
        $data = array();
        foreach ($_SESSION as $key => $value) {
            $data[$key] = (string)strval($value);
        }
        $multi = $this->redis->multi(\Redis::PIPELINE);
        //$multi->hmset($sessionId, $data);
        //$multi->expire($sessionId, $this->ttl);
        $multi->exec();
        return true;
    }

    public function destroy($sessionId)
    {
        //$this->redis->select($this->dbNumber);
        $this->redis->del($this->prefix . $sessionId);
    }

    public function cleanup($maxlifetime)
    {
        return true;
    }

    public function close()
    {
        //$this->setState(self::STATE_SESSION_CLOSE);
        return true;
    }

    public function __destruct()
    {
    }

    protected function sessionCommit()
    {
        session_write_close();
    }

    public function __set($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    public function __get($name)
    {
        return $_SESSION[$name];
    }

    public function getState()
    {
        return $this->state;
    }

    public function setState($state)
    {
        $this->state = $state;
    }
}
