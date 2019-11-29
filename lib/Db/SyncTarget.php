<?php

namespace OCA\Joplin\Db;

use OCP\AppFramework\Db\Entity;
use OCA\Joplin\Service\Uuid;

class SyncTarget extends Entity {

    protected $uuid;
    protected $path;
    protected $userId;

    static public function newEntity($userId, $path) {      
        $now = TimeUtils::milliseconds();
        
        $e = new SyncTarget();
        $e->setUuid(Uuid::gen());
        $e->setUserId($userId);
        $e->setPath($path);
        $e->setCreatedTime($now);
        $e->setUpdatedTime($now);

        return $e;
    }

}