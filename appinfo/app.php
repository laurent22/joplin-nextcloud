<?php

namespace OCA\Joplin\AppInfo;

use OCP\AppFramework\App;
use OCA\Joplin\Service\FilesService;
use OCA\Joplin\Service\ModelService;
use OCA\Joplin\Service\DbService;
use OCA\Joplin\Db\BaseModel;
use OCA\Joplin\Controller\NotesController;
use OCA\Joplin\Controller\SyncTargetsController;

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


// $container->registerService('ModelService', function($c) {
// 	return new ModelService($c->query('DbService'), $c->getURLGenerator());
// });



// $container->registerService('NotesController', function($c){
// 	return new NotesController(
// 	  $c->query('AppName'),
// 	  $c->query('Request'),
// 	  $c->query('UserId'),
// 	  $c->query('FilesService')
// 	);
//   });

//   $container->registerService('SyncTargetsController', function($c){
// 	return new SyncTargetsController(
// 	  $c->query('AppName'),
// 	  $c->query('Request'),
// 	  $c->query('UserId'),
// 	  $c->query('SyncTargetService')
// 	);
//   });



// $container->query('OCP\INavigationManager')->add(function () use ($container) {
// 	$urlGenerator = $container->query('OCP\IURLGenerator');
// 	$l10n = $container->query('OCP\IL10N');
// 	return [
// 		'id' => 'notes',
// 		'order' => 10,
// 		'href' => $urlGenerator->linkToRoute('notes.page.index'),
// 		'icon' => $urlGenerator->imagePath('notes', 'notes.svg'),
// 		'name' => $l10n->t('Notes')
// 	];
// });