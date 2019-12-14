<?php

namespace OCA\Joplin\Error;

use OCP\AppFramework\Http\JSONResponse;
use OCA\Joplin\Error\HttpException;

class ErrorHandler {

	static private function toResponse($exception, $format) {
		$httpStatus = 500;
		if ($exception instanceof HttpException) $httpStatus = $exception->httpStatus();

		$message = "Error $httpStatus";
		if ($exception->getMessage()) $message = $exception->getMessage();

		return new JSONResponse(array('error' => $message, 'stacktrace' => $exception->getTraceAsString()), $httpStatus);
	}
	
	static public function toJsonResponse($exception) {
		return self::toResponse($exception, 'json');
	}

	static public function toHtmlResponse($exception) {
		return self::toResponse($exception, 'json');
	}

}