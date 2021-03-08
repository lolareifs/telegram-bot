<?php declare( strict_types = 1 );
/**
 * @author Lola Reifs <lolareifscarmona@gmail.com>
 */

namespace App\Lib;

/**
 * Class Config
 * @package App\Lib
 */
class Config
{
    /**
     * @var
     */
    private static $config;

    /**
     * @param $key
     * @param null $default
     * @return mixed|null
     */
    public static function get($key, $default = null) : string
    {
        if (is_null(self::$config)) {
            self::$config = require_once(__DIR__.'/../../config.php');
        }

        return !empty(self::$config[$key])?self::$config[$key]:$default;
    }
}