<?php

namespace OCA\Joplin\Service;

use OCP\IDBConnection;
use OCA\Joplin\Service\DbService;
use OCA\Joplin\Db\SyncTargetModel;
use OCA\Joplin\Db\ShareModel;

class ModelService {

	private $models_ = [];

	public function __construct(DbService $DbService) {
		$this->models_ = [
			'syncTarget' => new SyncTargetModel($DbService),
			'share' => new ShareModel($DbService),
		];
	}

	public function get($name) {
		return $this->models_[$name];
	}

}