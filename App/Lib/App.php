<?php declare( strict_types = 1 );
/**
 * @author Lola Reifs <lolareifscarmona@gmail.com>
 */

namespace App\Lib;

use App\Lib\Config;
use App\Lib\Logger;
use App\Model\User;
use App\Model\VideoFrame;
use App\Helper\Data;

/**
 * Class App
 * @package App\Lib
 */
class App
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var Data
     */
    private $helperData;

    /**
     * App constructor.
     * @param User $user
     */
    public function __construct(
        User $user,
        Data $helperData
    )
    {
        $this->user = $user;
        $this->helperData = $helperData;
    }

    /**
     * Run app function
     */
    public function run() : void
    {
        $clientAnswer = json_decode(file_get_contents("php://input"), TRUE);

        /** Check if get input from Telegram bot before making the action */
        if(isset($clientAnswer)) {

            /** Create and set data object VideoFrame */
            $dataFrame = new VideoFrame($this->helperData->getVideoFrameData());
            $dataFrame->setUserName("'" . $clientAnswer["message"]["chat"]["first_name"] . " ". $clientAnswer["message"]["chat"]["last_name"] . "'");

            $chatId = $clientAnswer["message"]["chat"]["id"];
            $messageClient = $clientAnswer["message"]["text"];

            Logger::enableSystemLogs();
            $logger = Logger::getInstance();

            $user = $this->user->getUser($chatId);
            $message = false;

            try{
                /** Update VideoFrame based in the client answer */
                switch ($messageClient) {
                    case '/No':
                    case 'No':
                        if ( !empty($user) ) {
                            $dataFrame->setStartFrame($user['current_frame'] + 1); //IF "NO" => start frame is now current + 1
                            $dataFrame->setEndFrame($user['end_frame']);
                        }
                        break;
                    case '/Yes':
                    case 'Yes':
                        if ( !empty($user) ) {
                            $dataFrame->setStartFrame($user['start_frame']);
                            $dataFrame->setEndFrame($user['current_frame']); //If "YES" => end frame is now the current frame
                        }
                        break;
                    case "/rocket":
                    case "/start":
                    case "Start":
                        break;
                    default:
                        $message = "Did the rocket lauched in frame " . $user['current_frame'] . "? ( /Yes /No /Start) ";
                        /** Any other message return a message with the available commands */
                        file_get_contents($this->helperData->getBotUrl()."/sendmessage?chat_id=".$chatId."&text=" . $message);
                        exit;
                }
                /** Make the bisection to get the current frame to be showed in Telegram */
                $dataFrame->setCurrentFrame($dataFrame->bisect($dataFrame->getEndFrame(),$dataFrame->getStartFrame()));

                /** Update db User data to be got in next interation */
                if(empty($user)) {
                    $this->user->setUser($chatId,$dataFrame);
                } else {
                    $this->user->updateUser($chatId, $dataFrame);
                }
                /** Update message if the frame is found  */
                if($dataFrame->getStartFrame() == $dataFrame->getEndFrame() || $dataFrame->getCurrentFrame() == $dataFrame->getStartFrame() ){
                    $message = "\u{2705} Found! Take-off = ". $dataFrame->getCurrentFrame() . " ( " . Logger::getDate() . " ) ";
                }
                /** Send Telegram Message to client */
                $this->helperData->sendPhoto($chatId, $dataFrame->getCurrentFrame(), $message, $dataFrame->getUrlApi());
            }catch (\Exception $e){
                $logger->error(print_r($clientAnswer, true));
            }

        }

    }
}