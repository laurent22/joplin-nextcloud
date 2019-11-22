<?php

namespace OCA\Joplin\Db;

use OCP\AppFramework\Db\Entity;
use Hidehalo\Nanoid\Client;

class SyncTarget extends Entity {

    protected $uuid;
    protected $path;
    protected $userId;

    static public function newEntity($userId, $path) {
		$nanoid = new Client();
        
        $e = new SyncTarget();
        $e->setUuid($nanoid->formattedId('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', 22));
        $e->setUserId($userId);
        $e->setPath($path);

        return $e;
    }

}