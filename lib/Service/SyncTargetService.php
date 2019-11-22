<?php
// db/authordao.php

namespace OCA\Joplin\Service;

use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\IDBConnection;

class SyncTargetService {

	private $db;

	public function __construct(IDBConnection $db) {
		$this->db = $db;

	}

	public function addOne() {
		$query = $this->db->getQueryBuilder();

		$query->insert('joplin_sync_targets')
			->values(array(
				'path' => '/Joplin',
			))->execute();

			var_dump('DDDDDDDDDDDDDD');
die();
	}

	// public function find(int $id) {
	//     $qb = $this->db->getQueryBuilder();

	//     $qb->select('*')
	//        ->from('myapp_authors')
	//        ->where(
	//            $qb->expr()->eq('id', $qb->createNamedParameter($id, IQueryBuilder::PARAM_INT))
	//        );

	//     $cursor = $qb->execute();
	//     $row = $cursor->fetch();
	//     $cursor->closeCursor();

	//     return $row;
	// }

}