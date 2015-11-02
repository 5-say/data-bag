<?php namespace FiveSay\DataBag;

class DataBag {

    /**
     * 存储于 session 中的 key
     */
    const SESSION_NAME = 'FiveSay\DataBag';

    /**
     * 默认允许保留的历史纪录数量
     * @var integer
     */
    const DEFAULT_HISTORY_NUM = 5;


    /**
     * 获取指定数据的值
     * @param  string $key session key
     * @return mixed
     */
    public static function get($key)
    {
        return self::session($key);
    }

    /**
     * 设置指定数据的值
     * @param string $key   session key
     * @param mixed  $value session value
     * @return void
     */
    public static function set($key, $value)
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
    public static function all()
    {
        return self::session();
    }

    /**
     * 获取历史纪录
     * @param string $key   历史纪录 key
     * @return mixed
     */
    public static function history($key = null)
    {
        return self::sessionHistory($key);
    }

    /**
     * 读取/设置 允许保留的历史纪录数量
     * @param  integer $historyNum 历史纪录数
     * @return mixed
     */
    public static function historyNum($historyNum = null)
    {
        if (is_null($historyNum)) {
            return $_SESSION[self::SESSION_NAME.'-historyNum'] ?: self::DEFAULT_HISTORY_NUM;
        }
        else {
            $_SESSION[self::SESSION_NAME.'-historyNum'] = $historyNum;
        }
    }

    /**
     * 存储 或 获取 session 中的数据
     * @param string $key   session key
     * @param mixed  $value session value
     * @return mixed
     */
    private static function session($key = null, $value = null)
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
    private static function sessionHistory($key = null, $value = null)
    {
        if (is_null($key)) {
            return $_SESSION[self::SESSION_NAME.'-history'];
        }
        elseif (is_null($value)) {
            return $_SESSION[self::SESSION_NAME.'-history'][$key];
        }
        else {
            // 构造历史纪录
            $timestamp = time();
            $time      = date('Y-m-d H:i:s', $timestamp);
            $url       = self::currentUrl();
            $data      = array(
                'value'     => $value,
                'time'      => $time,
                'timestamp' => $timestamp,
                'url'       => $url,
            );

            // 存储历史纪录
            isset($_SESSION[self::SESSION_NAME.'-history'][$key]) OR $_SESSION[self::SESSION_NAME.'-history'][$key] = array();
            array_unshift($_SESSION[self::SESSION_NAME.'-history'][$key], $data);

            // 历史纪录数量限制
            $_SESSION[self::SESSION_NAME.'-history'][$key] = array_slice(
                $_SESSION[self::SESSION_NAME.'-history'][$key],
                0,
                self::historyNum()
            );
        }
    }

    /**
     * 获取当前 URL
     * @return string
     */
    public static function currentUrl()
    {
        $protocol = (
            ! empty($_SERVER['HTTPS'])
            && $_SERVER['HTTPS'] !== 'off'
            || $_SERVER['SERVER_PORT'] === 443
        ) ? 'https://' : 'http://';

        if (isset($_SERVER['HTTP_X_FORWARDED_HOST'])) {
            $host = $_SERVER['HTTP_X_FORWARDED_HOST'];
        }
        else {
            $host = $_SERVER['HTTP_HOST'];
        }

        return $protocol.$host.$_SERVER['REQUEST_URI'];
    }






}