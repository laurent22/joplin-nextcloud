<?php

namespace OCA\Joplin\AppInfo;

use OCP\AppFramework\App;
use OCA\Joplin\Service\FilesService;

require (dirname(__DIR__) . '/vendor/autoload.php');

class JoplinApp extends App {

	public function __construct($appName, $urlParams=[]) {
		parent::__construct($appName, $urlParams);
	}

}

$app = new JoplinApp('joplin');

$container = $app->getContainer();

$container->registerService('FilesService', function($c) {
	return new FilesService($c->query('ServerContainer')->getRootFolder());
});
