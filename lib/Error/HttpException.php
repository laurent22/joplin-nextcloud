<?php

namespace OCA\Joplin\Error;

class HttpException extends \Exception {

	private $httpStatus_;

	public function __construct($message = '', $httpStatus = 400) {
		$this->httpStatus_ = $httpStatus;
		parent::__construct($message);
	}

	public function httpStatus() {
		return $this->httpStatus_;
	}

}