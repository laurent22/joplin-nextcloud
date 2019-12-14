<?php
namespace OCA\Joplin\Controller;

use OCP\IRequest;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Http\JSONResponse;
use OCP\AppFramework\ApiController;
use OCA\Joplin\Service\FilesService;
use OCA\Joplin\Service\JoplinUtils;
use OCA\Joplin\Service\JoplinService;
use OCA\Joplin\Service\ModelService;
use OCA\Joplin\Db\SyncTargetMapper;
use OCA\Joplin\Db\SyncTarget;
use OCA\Joplin\Db\Share;
use OCA\Joplin\Db\ShareMapper;
use OCA\Joplin\Error\ErrorHandler;

class ApiSharesController extends ApiController {

	private $userId_;
	private $fileService_;
	private $joplinService_;
	private $models_;

	public function __construct($AppName, IRequest $request, $UserId, FilesService $FilesService, ModelService $ModelService, JoplinService $JoplinService){
		parent::__construct($AppName, $request);
		$this->userId_ = $UserId;
		$this->fileService_ = $FilesService;
		$this->joplinService_ = $JoplinService;
		$this->models_ = $ModelService;
	}
	
	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function create($syncTargetId, $noteId) {
		try {
			$model = $this->models_->get('share');
			$share = $model->fetchByNoteId($this->userId_, $syncTargetId, $noteId);
			if (!$share) {
				$note = $this->joplinService_->note($this->userId_, $syncTargetId, $noteId);
				$share = [
					'user_id' => $this->userId_,
					'sync_target_id' => $syncTargetId,
					'item_type' => JoplinUtils::TYPE_NOTE,
					'item_id' => $noteId,
				];
				$share = $model->insert($share);
			}
			return $model->toApiOutputObject($share);
		} catch (\Exception $e) {
			return ErrorHandler::toJsonResponse($e);
		}
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function noteIndex($syncTargetId, $noteId) {
		$model = $this->models_->get('share');
		$shares = $model->fetchAllByNoteId($this->userId_, $syncTargetId, $noteId);
		return $model->toApiOutputArray($shares);
	}

}

