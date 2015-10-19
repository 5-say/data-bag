<?php namespace FiveSay\DataBag;

class Bag {

    const SESSION_NAME = 'FiveSay\DataBag';

    /**
     * 从 session 中取值
     * @param  string $key session key
     * @return mixed
     */
    static public function getSession($key)
    {
        return $_SESSION[$key];
    }

    /**
     * 获取指定数据的值
     */
    static public function get($key)
    {
        $bag = self::all();
        return $bag[$key];
    }

    static public function all()
    {
        return self::getSession(self::SESSION_NAME);
    }

}