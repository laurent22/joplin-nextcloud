<?php

namespace OCA\Joplin\Db;

use OCA\Joplin\Service\DbService;
use OCA\Joplin\Service\Uuid;
use OCA\Joplin\Service\TimeUtils;

class BaseModel {

	private $db_;
	private $tableName_;
	private $hasUuid_ = true;

	public function __construct(DbService $DbService) {
		$this->db_ = $DbService;
	}

	protected function setTableName($v) {
		$this->tableName_ = $v;
	}

	protected function tableName() {
		if (!$this->tableName_) throw new \Exception('tableName has not been set!!');
		return $this->tableName_;
	}

	protected function db() {
		return $this->db_;
	}

	protected function hasUuid() {
		return $this->hasUuid_;
	}

	private function idKey() {
		return $this->hasUuid() ? 'uuid' : 'id';
	}

	public function toApiOutputObject($model) {
		return $model;
	}

	public function toApiOutputArray($models) {
		$output = [];
		foreach ($models as $model) {
			$output[] = $this->toApiOutputObject($model);
		}
		return $output;
	}

	public function fetchByUuid($userId, $uuid) {
		return $this->db()->fetchOne('SELECT * FROM *PREFIX*' . $this->tableName() . ' WHERE user_id = :user_id AND uuid = :uuid', [
			'user_id' => $userId,
			'uuid' => $uuid,
		]);
	}

	public function insert($model) {
		if ($this->hasUuid()) $model['uuid'] = Uuid::gen();

		$now = TimeUtils::milliseconds();
		$model['created_time'] = $now;
		$model['updated_time'] = $now;

		$this->db()->insert($this->tableName(), $model);

		return $model;
	}

	public function update($model) {
		$idKey = $this->idKey();
		if (!isset($model[$idKey])) throw new \Exception('Missing ID on model');

		$conditions = [];
		$conditions[$idKey] = $model[$idKey];

		unset($model[$idKey]);

		$model['updated_time'] = TimeUtils::milliseconds();

		return $this->db()->update($this->tableName(), $model, $conditions);
	}
	
}