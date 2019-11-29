<?php

namespace OCA\Joplin\Error;

use OCP\AppFramework\Http\JSONResponse;
use OCA\Joplin\Error\HttpException;

class ErrorHandler {
	
	static public function toJsonResponse($exception) {
		$httpStatus = 500;
		if ($exception instanceof HttpException) $httpStatus = $exception->httpStatus();

		$message = "Error $httpStatus";
		if ($exception->getMessage()) $message = $exception->getMessage();

		return new JSONResponse(array('message' => $message), $httpStatus);
	}

}