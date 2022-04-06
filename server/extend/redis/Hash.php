<?php

namespace redis;

class Hash
{
    private $redis;

    private $name;

    public function __construct()
    {
        $this->redis = new \Redis();
        $this->redis->connect('127.0.0.1', 6379);
    }

    public function name($name)
    {
        $this->name = $name;
        return $this;
    }

    public function select($prex = '')
    {
        return $this->redis->keys($prex . '*');
    }

    public function exists()
    {
        return $this->redis->exists($this->name);
    }

    public function destory()
    {
        return $this->redis->del($this->name);
    }

    public function len()
    {
        return $this->redis->hLen($this->name);
    }

    public function all()
    {
        return $this->redis->hGetAll($this->name);
    }

    public function keys()
    {
        return $this->redis->hKeys($this->name);
    }

    public function vals()
    {
        return $this->redis->hVals($this->name);
    }

    public function get($key)
    {
        return $this->redis->hGet($this->name, $key);
    }

    public function gets($keys)
    {
        return $this->redis->hMGet($this->name, $keys);
    }

    public function set($key, $val)
    {
        return $this->redis->hSet($this->name, $key, $val);
    }

    public function sets($kvArray)
    {
        return $this->redis->hMSet($this->name, $kvArray);
    }

    public function inc($key, $number)
    {
        return $this->redis->hIncrBy($this->name, $key, $number);
    }

    public function has($key)
    {
        return $this->redis->hExists($this->name, $key);
    }

    public function del($key)
    {
        return $this->redis->hDel($this->name, $key);
    }
}