<?php
/**
 * Nextcloud - Notes
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Bernhard Posselt <dev@bernhard-posselt.com>
 * @copyright Bernhard Posselt 2012, 2014
 */
namespace OCA\Joplin\AppInfo;
use OCP\AppFramework\App;
use OCA\Joplin\Service\FilesService;
use OCA\Joplin\Controller\NotesController;
use OCA\Joplin\Controller\SyncTargetsController;

require (dirname(__DIR__) . '/vendor/autoload.php');

$app = new App('joplin');
$container = $app->getContainer();

$container->registerService('FilesService', function($c) {
	return new FilesService($c->query('RootStorage'));
});

$container->registerService('RootStorage', function($c) {
	return $c->query('ServerContainer')->getUserFolder();
});

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