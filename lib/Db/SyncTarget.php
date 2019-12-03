<?php

namespace OCA\Joplin\Db;

use JsonSerializable;
use OCP\AppFramework\Db\Entity;
use OCA\Joplin\Service\Uuid;
use OCA\Joplin\Service\TimeUtils;

class SyncTarget extends Entity implements JsonSerializable {

    protected $uuid;
    protected $path;
    protected $userId;
    protected $createdTime;
    protected $updatedTime;

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

    public function jsonSerialize() {
        return [
            'id' => $this->uuid,
            'path' => $this->path,
            'updatedTime' => $this->updatedTime,
            'createdTime' => $this->createdTime,
        ];
    }

}