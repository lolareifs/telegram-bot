<?php declare( strict_types = 1 );
/**
 * @author Lola Reifs <lolareifscarmona@gmail.com>
 */

namespace App\Model;

/**
 * Class VideoFrame
 * @package App\Model
 */
class VideoFrame
{
    /**
     * Video start frame
     */
    const START_FRAME = 0;

    /**
     * @var int
     */
    public $start_frame;

    /**
     * @var int
     */
    public $end_frame;

    /**
     * @var int
     */
    public $current_frame;

    /**
     * @var string|string[]
     */
    public $url_api;

    /**
     * VideoFrame constructor.
     * @param $data
     */
    function __construct($data)
    {
        $this->start_frame = SELF::START_FRAME;
        $this->end_frame = $data[0]->frames - 1;
        $this->current_frame = $this->bisect($this->end_frame,$this->start_frame);
        $this->url_api = str_replace('http','https',$data[0]->url);
        $this->user_name = "null";
    }


    /**
     * @return int
     */
    public function getStartFrame(): int{
        return intVal($this->start_frame);
    }

    /**
     * @return int
     */
    public function getEndFrame(): int{
        return intVal($this->end_frame);
    }

    /**
     * @return int
     */
    public function getCurrentFrame(): int{
        return intVal($this->current_frame);
    }

    /**
     * @return string
     */
    public function getUserName(): string{
        return $this->user_name;
    }

    /**
     * @return string
     */
    public function getUrlApi(): string{
        return $this->url_api;
    }

    /**
     * @param $frame
     */
    public function setStartFrame(int $frame): void{
        $this->start_frame = $frame;
    }

    /**
     * @param $frame
     */
    public function setEndFrame(int $frame): void{
        $this->end_frame = $frame;
    }

    /**
     * @param $frame
     */
    public function setCurrentFrame(int $frame): void{
        $this->current_frame = $frame;
    }


    /**
     * @param $frame
     */
    public function setUserName(string $name): void{
        $this->user_name = $name;
    }


    /**
     * Bisect function
     * @param $end
     * @param $start
     * @return int
     */
    public function bisect(int $end,int $start): int{
        return intval(($end + $start) / 2 );
    }
}
