<?php
namespace OCA\Joplin\Controller;

use OCP\IRequest;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Http\JSONResponse;
use OCP\AppFramework\ApiController;
use OCA\Joplin\Service\ModelService;
use OCA\Joplin\Db\SyncTargetModel;
use OCA\Joplin\Error\ErrorHandler;
use OCA\Joplin\Error\BadRequestException;

class ApiSyncTargetsController extends ApiController {

	private $userId_;

	public function __construct($AppName, IRequest $request, $UserId, ModelService $ModelService){
		parent::__construct($AppName, $request);
		$this->userId_ = $UserId;
		$this->models_ = $ModelService;
	}
	
	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 * 
	 */
	public function create($webDavUrl) {
		try {
			$model = $this->models_->get('syncTarget');

			$path = $model->pathFromWebDavUrl($webDavUrl);
			$syncTarget = $model->fetchByPath($this->userId_, $path);
			if ($syncTarget) return $model->toApiOutputObject($syncTarget);

			$syncTarget = [
				'user_id' => $this->userId_,
				'path' => $path,
			];

			return $model->toApiOutputObject($model->insert($syncTarget));
		} catch (\Exception $e) {
			return ErrorHandler::toJsonResponse($e);
		}
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 * 
	 */
	public function noteIndex($syncTargetId, $noteId) {
		try {
			$shares = $this->syncTargetMapper_->findByNoteId($syncTargetId, $noteId);
			return new JSONResponse($shares);
		} catch (\Exception $e) {
			return ErrorHandler::toJsonResponse($e);
		}
	}

}

