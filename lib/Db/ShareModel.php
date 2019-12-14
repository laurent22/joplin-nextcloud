<?php

namespace OCA\Joplin\Db;

use OCA\Joplin\Service\DbService;
use OCA\Joplin\Service\JoplinUtils;

class ShareModel extends BaseModel {

	private $baseUrl_;

	public function __construct(DbService $DbService, $baseUrl) {
		parent::__construct($DbService);

		$this->baseUrl_ = $baseUrl;
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

	public function fetchByNoteId($userId, $syncTargetId, $noteId) {
		return $this->db()->fetchOne('
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
		$model['_url'] = $this->makeShareLink($model);
		unset($model['id']);
		unset($model['user_id']);
		return $model;
	}

	private function makeShareLink($model) {
		// http://..../shares/:share_id
		return $this->baseUrl_ . '/shares/' . $model['uuid'];
	}

}