<?php declare( strict_types = 1 );
/**
 * @author Lola Reifs <lolareifscarmona@gmail.com>
 */

namespace App\Helper;
use App\Lib\Config;

/**
 * Class Data
 * @package App\Helper
 */
class Data
{
    /**
     * Data constructor.
     */
    public function __construct()
    {
        $this->bot_path = Config::get('BOT_PATH', '');
        $this->bot_name = Config::get('BOT_NAME', '');
        $this->bot_token = Config::get('BOT_TOKEN', '');
        $this->api_video_path = Config::get('VIDEO_API', '');
    }

    /**
     * Get bot telegram url
     * @return string
     */
    public function getBotPath() : string{
        return $this->bot_path;
    }

    /** Get Bot Telegram name
     * @return string
     */
    public function getBotName() : string{
        return $this->bot_name;
    }

    /**
     * Get Bot Telegram Token
     * @return string
     */
    public function getBotToken() : string{
        return $this->bot_token;
    }

    /**
     * Get bot Telegram path to be able to send message (path + token)
     * @return string
     */
    public function getBotUrl() : string{
        return $bot_url = $this->getBotPath() . $this->getBotToken();
    }

    /**
     * Get Api Video Data
     * @return array
     */
    public function getVideoFrameData() : array{
        $jsonApiBase = file_get_contents( $this->api_video_path);
        return json_decode($jsonApiBase);
    }

    /**
     * Send message with the bisected frame to Telegram user
     * @param $chatId
     * @param $frame
     * @param $message
     */
    public function sendPhoto($chatId, $frame, $message, $urlFrame) : void
    {

        $ch = curl_init($this->getBotUrl() . "/sendPhoto");

        $buttons = [];
        if ($message) {
            $caption = $message;
            $buttons = [['/start']];
        } else {
            $caption = "f" . $frame . " - Did the rocket launch yet?  \u{1F680}";
            $buttons = [['Yes'], ['No'], ['Start']];
        }

        $keyboard = [
            'keyboard' => $buttons,
            'resize_keyboard' => true,
            'one_time_keyboard' => true,
            'selective' => true
        ];

        $params = [
            'chat_id' => $chatId,
            'photo' => $urlFrame . "frame/" . $frame . "/",
            'allow_sending_without_reply' => true,
            'caption' => $caption,
            'reply_markup' => json_encode($keyboard, 1)
        ];

        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, ($params));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);

        print_r($params); //TODO Remove this code before upload

    }

}