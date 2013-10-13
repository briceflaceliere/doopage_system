<?php
$microtime = microtime(true);

define('PUBLIC_DIR', dirname(__FILE__));
define('ENV', !empty($_ENV['ENV']) ? $_ENV['ENV'] : "PROD");

require_once '../Core/App.php';


\DoopageFramework\Core\App::instance()
    ->addAppDirectory('User', dirname(__FILE__).'/../User/')
    ->exec();


$test = new \App\Lib_Test();
$test->test();

$microtime =  microtime(true) - $microtime;
echo '<br />'.$microtime.'s';
?>
