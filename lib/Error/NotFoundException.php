<?php

namespace OCA\Joplin\Error;

use OCA\Joplin\Error\HttpException;

class NotFoundException extends HttpException {

	public function __construct($message = '') {
		parent::__construct($message, 404);
	}

}