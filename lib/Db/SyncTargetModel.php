<?php

namespace OCA\Joplin\Db;

use OCA\Joplin\Service\DbService;

class SyncTargetModel extends BaseModel {

	public function __construct(DbService $DbService) {
		parent::__construct($DbService);

		$this->setTableName('joplin_sync_targets');
	}

	public function find($userId, $uuid) {
		
	}

	public function findByPath($userId, $path) {
		
		//return $this->db()->fetchAll('select * from *PREFIX*users where uid = :uid', ['uid' => 'admin']);
	}

}