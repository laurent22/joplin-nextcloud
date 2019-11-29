<?php
namespace OCA\Joplin\Service;

class TimeUtils {

	static public function milliseconds() {
		return round(microtime(true) * 1000);
	}

}
