<?php namespace FiveSay\DataBag;

class DataBag {

    /**
     * 存储于 session 中的 key
        "jwage/purl": "~0.0.6"
    
     */
    const SESSION_NAME = 'FiveSay\DataBag';

    /**
     * 允许保留的历史纪录数量
     * @var integer
     */
    static private $historyNum = 5;


    /**
     * 获取指定数据的值
     * @param  string $key session key
     * @return mixed
     */
    static public function get($key)
    {
        return self::session($key);
    }

    /**
     * 设置指定数据的值
     * @param string $key   session key
     * @param mixed  $value session value
     * @return void
     */
    static public function set($key, $value)
    {
        self::session($key, $value);
        self::sessionHistory($key, $value);
    }

    /**
     * 获取所有的数据
     * @param string $key   session key
     * @param mixed  $value session value
     * @return mixed
     */
    static public function all()
    {
        return self::session();
    }

    /**
     * 获取历史纪录
     * @param string $key   历史纪录 key
     * @return mixed
     */
    static public function history($key = null)
    {
        return self::sessionHistory($key);
    }

    /**
     * 设置历史纪录数
     * @param  integer $historyNum 历史纪录数
     * @return void
     */
    static public function historyNum($historyNum)
    {
        return self::$historyNum = $historyNum;
    }

    /**
     * 存储 或 获取 session 中的数据
     * @param string $key   session key
     * @param mixed  $value session value
     * @return mixed
     */
    static private function session($key = null, $value = null)
    {
        if (is_null($key)) {
            return $_SESSION[self::SESSION_NAME];
        }
        elseif (is_null($value)) {
            return $_SESSION[self::SESSION_NAME][$key];
        }
        else {
            $_SESSION[self::SESSION_NAME][$key] = $value;
        }
    }

    /**
     * 存储 或 获取 历史纪录 中的数据
     * @param string $key   历史纪录 key
     * @param mixed  $value 历史纪录 value
     * @return mixed
     */
    static private function sessionHistory($key = null, $value = null)
    {
        if (is_null($key)) {
            return $_SESSION[self::SESSION_NAME.'-history'];
        }
        elseif (is_null($value)) {
            return $_SESSION[self::SESSION_NAME.'-history'][$key];
        }
        else {
            $timestamp = time();
            $time      = date('Y-m-d H:i:s', $timestamp);
            $data      = array(
                'value'     => $value,
                'time'      => $time,
                'timestamp' => $timestamp,
            );

            array_unshift($_SESSION[self::SESSION_NAME.'-history'][$key], $data);

            $_SESSION[self::SESSION_NAME.'-history'][$key] = array_slice(
                $_SESSION[self::SESSION_NAME.'-history'][$key],
                0,
                self::$historyNum
            );
        }
    }






}