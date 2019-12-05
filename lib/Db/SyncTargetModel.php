<?php

namespace OCA\Joplin\Db;

use OCA\Joplin\Service\DbService;

class SyncTargetModel extends BaseModel {

	public function __construct(DbService $DbService) {
		parent::__construct($DbService);

		$this->setTableName('joplin_sync_targets');
	}

	public function fetchByPath($userId, $path) {
		return $this->db()->fetchOne('
			SELECT * FROM *PREFIX*joplin_sync_targets
			WHERE user_id = :user_id
			AND path = :path
		', [
			'user_id' => $userId,	
			'path' => $path,
		]);
	}

	public function pathFromWebDavUrl($url) {
		$s = explode('remote.php/webdav', $url);
		if (count($s) !== 2) throw new \Exception('Unsupport WebDAV URL format: ' . $url);
		return trim($s[1], '/');
	}

	public function toApiOutputObject($model) {
		unset($model['id']);
		unset($model['user_id']);
		return $model;
	}

}