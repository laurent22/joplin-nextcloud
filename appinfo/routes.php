<?php

return [
    'routes' => [
	   ['name' => 'shares#get', 'url' => '/shares/{token}', 'verb' => 'GET'],
	   ['name' => 'index#get', 'url' => '/', 'verb' => 'GET'],

	   // TODO: add CORS OPTION request: https://docs.nextcloud.com/server/15/developer_manual/app/requests/api.html
	   ['name' => 'api_shares#note_index', 'url' => '/api/notes/{syncTargetId}/{noteId}/shares', 'verb' => 'GET'],
	   ['name' => 'api_shares#create', 'url' => '/api/shares', 'verb' => 'POST'],
	   ['name' => 'api_sync_targets#create', 'url' => '/api/sync_targets', 'verb' => 'POST'],
    ]
];
