<?php

namespace OCA\Joplin\Service;

use OCP\IDBConnection;

class NextcloudService {

	public $db;

	public function __construct(IDBConnection $db) {
		$this->db = $db;
	}

}