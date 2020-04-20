<?php

require_once(__DIR__ . '/autoloader.php');
spl_autoload_register('myAutoload');

use controllers\NewsController;
use controllers\FilesController;

$api = explode('/', trim($_SERVER['REQUEST_URI'],'/'))[1];

try {
	$apiNews = new NewsController();
	$apiFiles = new FilesController();

	switch ($api) {
		case 'news':
			echo $apiNews->run();
			break;

		case 'files':
			echo $apiFiles->run();
			break;
		}

} catch (Exception $e) {
    echo json_encode(Array('error' => $e->getMessage()));
}

?>
