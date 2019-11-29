<?php

namespace OCA\Joplin\Service;

use Hidehalo\Nanoid\Client;

class Uuid {

	static private $client_;

	static private function client() {
		if (self::$client_) return self::$client_;
		self::$client_ = new Client();
		return self::$client_;
	}

    static public function gen() {
        return self::client()->formattedId('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', 22);
    }

}