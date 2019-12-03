<?php

namespace OCA\Joplin\Error;

use OCA\Joplin\Error\HttpException;

class BadRequestException extends HttpException {

	public function __construct($message = '') {
		parent::__construct($message, 400);
	}

}