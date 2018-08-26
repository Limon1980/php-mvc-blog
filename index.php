<?php
// http://w91470j7.beget.tech/
require __DIR__ . '/Application/Lib/Dev.php';
use Application\Core\Router;

spl_autoload_register(function ($class) {
	$path = str_replace('\\', '/', $class . '.php');
	if (file_exists($path)) {
		require $path;
	}
});





require __DIR__.'/Application/Lib/PHPMailer-master/src/Exception.php';
require __DIR__.'/Application/Lib/PHPMailer-master/src/PHPMailer.php';
require __DIR__.'/Application/Lib/PHPMailer-master/src/SMTP.php';




session_start();

$router = new Router;
$router->run();


?>