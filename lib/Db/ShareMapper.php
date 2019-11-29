<?php

namespace OCA\Joplin\Db;

use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\IDBConnection;
use OCP\AppFramework\Db\QBMapper;

class ShareMapper extends QBMapper {

    public function __construct(IDBConnection $db) {
        parent::__construct($db, 'joplin_shares');
    }
    
    public function findByInternalId($internalId) {
        $qb = $this->db->getQueryBuilder();

        $qb->select('*')
           ->from('joplin_shares')
            ->where('id = :id')
            ->setParameters(array(
                ':id' => $internalId,
            ));

        return $this->findEntity($qb);
    }

    public function findByNoteId($syncTargetId, $itemId) {
        $qb = $this->db->getQueryBuilder();

        $qb->select('*')
            ->from('joplin_shares')
            //->where('sync_target_id = :sync_target_id')
            ->where('item_id = :item_id')
            ->setParameters(array(
                ':item_id' => $itemId,
            ));

        return $this->findEntities($qb);
    }
	
	// public function find($userId, $uuid) {
    //     $qb = $this->db->getQueryBuilder();

    //     $qb->select('*')
    //        ->from('joplin_sync_targets')
    //         ->where('user_id = :user_id AND uuid = :uuid')
    //         ->setParameters(array(
    //             ':user_id' => $userId,
    //             ':uuid' => $uuid,
    //         ));
    //     //    ->where(
    //     //        $qb->expr()->eq('id', $qb->createNamedParameter($id, IQueryBuilder::PARAM_INT))
    //     //    );

    //     return $this->findEntity($qb);
    // }

    // public function findAllByUser($userId) {
    //     $qb = $this->db->getQueryBuilder();

    //     $qb->select('*')
    //        ->from('joplin_sync_targets')
    //        ->where($qb->expr()->eq('user_id', $qb->createNamedParameter($userId, IQueryBuilder::PARAM_STR)));

    //     return $this->findEntities($qb);
    // }

    // public function findAll($limit=null, $offset=null) {
    //     $qb = $this->db->getQueryBuilder();

    //     $qb->select('*')
    //        ->from('joplin_sync_targets')
    //        ->setMaxResults($limit)
    //        ->setFirstResult($offset);

    //     return $this->findEntities($qb);
    // }

}