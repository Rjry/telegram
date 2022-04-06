<?php

namespace redis;

class Queue
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
        return $this->redis->llen($this->name);
    }

    public function all()
    {
        $len = self::len();
        $dat = $this->redis->lrange($this->name, 0, $len);
        return $dat;
    }

    public function volist($page = 1, $show = 10)
    {
        $len  = self::len();
        $num  = $show--;
        $sub  = ($page == 1) ? 0 : ( ($page - 1) * $show + 1);
        $end  = $sub + $show;
        $dat  = $this->redis->lrange($this->name, $sub, $end);

        $list = [];
        foreach ($dat as $key => $val) {
            $list[] = [
                'key' => ($sub + $key),
                'val' => $val,
            ];
        }

        return [
            'count' => $len,
            'list'  => $list,
        ];
    }

    public function lpush($val)
    {
        return $this->redis->lpush($this->name, $val);
    }

    public function rpush($val)
    {
        return $this->redis->rpush($this->name, $val);
    }

    public function lpull()
    {
        return $this->redis->lpop($this->name);
    }

    public function rpull()
    {
        return $this->redis->rpop($this->name);
    }
}