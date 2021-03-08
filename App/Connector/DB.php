<?php declare( strict_types = 1 );
/**
 * DB Class
 * @author Lola Reifs <lolareifscarmona@gmail.com>
 */

namespace App\Connector;
use App\Lib\Config;

/**
 * Class DB
 * @package App\Connector
 */
class DB {

    /**
     * @var DB
     */
    public $conn;

    /**
     * DB constructor.
     */
    public function __construct() {
        $this->servername = Config::get('HOST', '');
        $this->username = Config::get('USERNAME', '');
        $this->password = Config::get('PASSWORD', '');
        $this->dbname = Config::get('DBNAME', '');
    }

    /**
     * DB Connector
     * @return \mysqli
     */
    public function dbConn() : \mysqli{
        return new \mysqli($this->servername, $this->username, $this->password, $this->dbname);
    }

}






