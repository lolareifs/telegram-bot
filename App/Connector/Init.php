<?php declare( strict_types = 1 );
/**
 * @author Lola Reifs <lolareifscarmona@gmail.com>
 */

namespace App\Connector;
use App\Lib\Config;

/**
 * Class Init
 * @package App\Connector
 */
class Init {

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
        $this->initDB();

    }

    public function initDB(){
        if($this->servername == "" OR $this->username == "" OR $this->password == "" OR $this->dbname == ""){
            die("Please Enter DATABASE details in mysqli_conf.php file");
        }

        $this->conn = new \mysqli($this->servername, $this->username, $this->password);
        if($this->conn->connect_errno > 0){
            die('Unable to connect to database [' . $this->conn->connect_error . ']');
        }

        // Create database if not exist
        $sql = "CREATE DATABASE IF NOT EXISTS " . $this->dbname . ";";
        if (!$this->conn->query($sql) === TRUE) {
            echo "Error creating database: " . $this->conn->error;
        }

        if ($this->conn->connect_error) {
            echo $this->conn->connect_error;

            die("Connection failed: " . $this->conn->connect_error);
        }
        $this->prepareDBSchema();
    }


    /**
     * Prepare schema DB
     */
    public function prepareDBSchema() : void{
        $this->conn = new \mysqli($this->servername, $this->username, $this->password, $this->dbname);
        $query = "CREATE TABLE IF NOT EXISTS user_entity (
                    entity_id bigint(20) NOT NULL AUTO_INCREMENT,
                    chat_id varchar(255) NOT NULL,
                    current_frame int(11) NOT NULL,
                    start_frame int(11) NOT NULL,
                    end_frame int(11) NOT NULL,
                    user_name text NULL,
                    PRIMARY KEY(chat_id),
                    INDEX (entity_id, chat_id)
                      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        $result = mysqli_query($this->conn, $query);
    }
}






