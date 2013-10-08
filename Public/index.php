<?php

define('BASE_DIR', dirname(__FILE__));

require_once '../User/App.php';

\DoopageFramework\User\App::instance()->exec();

?>
