<?php

namespace OCA\Joplin\Service;

use OCA\Joplin\Service\DbService;
use OCA\Joplin\Service\ServerService;
use OCA\Joplin\Db\SyncTargetModel;
use OCA\Joplin\Db\ShareModel;

class ModelService {

	private $models_ = [];

	public function __construct(DbService $DbService, ServerService $ServerService) {
		$this->models_ = [
			'syncTarget' => new SyncTargetModel($DbService),
			'share' => new ShareModel($DbService, $ServerService->baseUrl()),
		];
	}

	public function get($name) {
		return $this->models_[$name];
	}

}