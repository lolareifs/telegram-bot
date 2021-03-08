<?php declare( strict_types = 1 );
session_start();
/**
 * Index Telegram Bot
 * @author Lola Reifs <lolareifscarmona@gmail.com>
 */

require __DIR__ . '/vendor/autoload.php';

use App\Lib\App;
use App\Connector\Init;

/** If it is the first time, create DB */
if(!isset($_SESSION['first_run'])){
    $_SESSION['first_run'] = 1;
    $schema = new Init();
}

/** App run */
$db = new \App\Connector\DB();
$user = new \App\Model\User($db);
$helperData = new \App\Helper\Data();
$app = new App($user, $helperData);
$app->run();





