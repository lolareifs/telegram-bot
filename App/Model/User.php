<?php declare( strict_types = 1 );
/**
 * @author Lola Reifs <lolareifscarmona@gmail.com>
 */

namespace App\Model;
use App\Connector\DB;

/**
 * Class User
 * @package App\Model
 */
class User {

    /**
     * @var DB
     */
    private $db;

    /**
     * User constructor.
     * @param DB $db
     */
    public function __construct(
        DB $db
    ) {
        $this->conn = $db;
    }

    /**
     * Get User data from database by telegram chatId
     * @param $chatId
     * @return array
     */
    public function getUser(string $chatId) : array {
        $conn = $this->conn->dbConn();
        $query = "SELECT current_frame, start_frame, end_frame FROM user_entity WHERE chat_id = $chatId";
        $result = $conn->query($query);
        $arrayDataChat = [];
        if ( $result ) {
            while ($row = $result->fetch_row()) {
                $arrayDataChat = array(
                    'current_frame' => intval($row[0]),
                    'start_frame' => intval($row[1]),
                    'end_frame' => intval($row[2])
                );
            }
        }
        return $arrayDataChat;
    }

    /**
     * Set User data in database by telegram chatId
     * @param $chatId
     * @param $dataFrame
     * @return void
     */
    public function setUser(string $chatId,object $dataFrame) : void {
        $conn = $this->conn->dbConn();
        $query = "INSERT INTO user_entity(chat_id, start_frame, end_frame, current_frame, user_name) VALUES($chatId, " . $dataFrame->getStartFrame() . ", " . $dataFrame->getEndFrame() . "," . $dataFrame->getCurrentFrame() . ", " . $dataFrame->getUserName() . ")";

        $result = mysqli_query($conn, $query);
    }

    /**
     * Update User data in database by telegram chatId
     * @param $chatId
     * @param $dataFrame
     * @return void
     */
    public function updateUser(string $chatId, object $dataFrame) : void{
        $conn = $this->conn->dbConn();
        $query = "UPDATE user_entity SET current_frame = $dataFrame->current_frame,start_frame = $dataFrame->start_frame ,end_frame= $dataFrame->end_frame ,user_name = $dataFrame->user_name WHERE chat_id = $chatId";
        $result = mysqli_query($conn, $query);
    }

}