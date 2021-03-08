<?php declare( strict_types = 1 );
/**
 * @author Lola Reifs <lolareifscarmona@gmail.com>
 */

namespace App\Lib;

use Monolog\ErrorHandler;
use Monolog\Handler\StreamHandler;

/**
 * Class Logger
 * @package App\Lib
 */
class Logger extends \Monolog\Logger
{
    private static $loggers = [];

    /**
     * Logger constructor.
     * @param string $key
     * @param null $config
     * @throws \Exception
     */
    public function __construct($key = "app", $config = null)
    {
        parent::__construct($key);

        if (empty($config)) {
            $LOG_PATH = Config::get('LOG_PATH', __DIR__ . '/../../logs');
            $config = [
                'logFile' => "{$LOG_PATH}/{$key}.log",
                'logLevel' => \Monolog\Logger::DEBUG
            ];
        }

        $this->pushHandler(new StreamHandler($config['logFile'], $config['logLevel']));
    }

    /**
     * Get Logger instance
     * @param string $key
     * @param null $config
     * @return Logger|mixed
     */
    public static function getInstance($key = "app", $config = null) : Logger
    {
        if (empty(self::$loggers[$key])) {
            self::$loggers[$key] = new Logger($key, $config);
        }

        return self::$loggers[$key];
    }

    /**
     * Enable Logs
     */
    public static function enableSystemLogs() : void
    {
        $LOG_PATH = Config::get('LOG_PATH', '../var/logs');
        // Error Log
        self::$loggers['error'] = new Logger('errors');
        self::$loggers['error']->pushHandler(new StreamHandler("{$LOG_PATH}/errors.log"));
        ErrorHandler::register(self::$loggers['error']);
    }

    /**
     * Get current date
     * @return string
     */
    public function getDate() : string {
        $now = new \DateTime();
        $date = $now->format("Y-m-d h:i");
        return $date;
    }

}