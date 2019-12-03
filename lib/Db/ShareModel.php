<?php

namespace OCA\Joplin\Db;

use OCA\Joplin\Service\DbService;
use OCA\Joplin\Service\JoplinUtils;

class ShareModel extends BaseModel {

	public function __construct(DbService $DbService) {
		parent::__construct($DbService);

		$this->setTableName('joplin_shares');
	}

	public function fetchAllByNoteId($userId, $syncTargetId, $noteId) {
		return $this->db()->fetchAll('
			SELECT * FROM *PREFIX*joplin_shares
			WHERE user_id = :user_id
			AND sync_target_id = :sync_target_id
			AND item_type = :item_type
			AND item_id = :item_id
		', [
			'user_id' => $userId,	
			'sync_target_id' => $syncTargetId,
			'item_type' => JoplinUtils::TYPE_NOTE,
			'item_id' => $noteId,
		]);
	}

	public function toApiOutputObject($model) {
		unset($model['id']);
		unset($model['user_id']);
		return $model;
	}

}