<?php

namespace OCA\Joplin\Db;

use JsonSerializable;
use OCP\AppFramework\Db\Entity;
use OCA\Joplin\Service\Uuid;
use OCA\Joplin\Service\JoplinUtils;
use OCA\Joplin\Service\TimeUtils;

class Share extends Entity implements JsonSerializable {

    protected $uuid;
    protected $userId;
    protected $itemId;
    protected $syncTargetId;
    protected $createdTime;
    protected $updatedTime;

    static public function newEntity($userId, $syncTargetId, $itemId) {
        $now = TimeUtils::milliseconds();

        $e = new Share();
        $e->setUuid(Uuid::gen());
        $e->setUserId($userId);
        $e->setItemId($itemId);
        $e->setSyncTargetId($syncTargetId);
        $e->setCreatedTime($now);
        $e->setUpdatedTime($now);
        return $e;
    }

    public function jsonSerialize() {
        return [
            'id' => $this->uuid,
            'itemId' => $this->itemId,
            'syncTargetId' => $this->syncTargetId,
            'updatedTime' => $this->updatedTime,
            'createdTime' => $this->createdTime,
        ];
    }

}