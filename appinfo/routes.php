<?php

/**
 * Create your routes in here. The name is the lowercase name of the controller
 * without the controller part, the stuff after the hash is the method.
 * e.g. page#index -> OCA\Joplin\Controller\PageController->index()
 *
 * The controller class has to be registered in the application.php file since
 * it's instantiated in there
 */
return [
    'routes' => [
	   ['name' => 'page#index', 'url' => '/', 'verb' => 'GET'],
	   ['name' => 'page#do_echo', 'url' => '/echo', 'verb' => 'POST'],
	   ['name' => 'notes#index', 'url' => '/notes', 'verb' => 'GET'],
	   ['name' => 'sync_targets#index', 'url' => '/sync_targets', 'verb' => 'GET'],

	   ['name' => 'notes#get', 'url' => '/notes/{syncTargetId}/{noteId}', 'verb' => 'GET'],

	   // TODO: add CORS OPTION request: https://docs.nextcloud.com/server/15/developer_manual/app/requests/api.html
	   ['name' => 'api_notes#index', 'url' => '/api/notes', 'verb' => 'GET'],
	   ['name' => 'api_notes#get', 'url' => '/api/notes/{syncTargetId}/{noteId}', 'verb' => 'GET'],
	   ['name' => 'api_shares#index', 'url' => '/api/shares', 'verb' => 'GET'],
	   ['name' => 'api_shares#note_index', 'url' => '/api/notes/{syncTargetId}/{noteId}/shares', 'verb' => 'GET'],
	   ['name' => 'api_shares#create', 'url' => '/api/shares', 'verb' => 'POST'],
	   ['name' => 'api_sync_targets#create', 'url' => '/api/sync_targets', 'verb' => 'POST'],
    ]
];
