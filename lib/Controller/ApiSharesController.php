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
use OCA\Joplin\Db\SyncTargetMapper;
use OCA\Joplin\Db\SyncTarget;
use OCA\Joplin\Db\Share;
use OCA\Joplin\Db\ShareMapper;
use OCA\Joplin\Error\ErrorHandler;

class ApiSharesController extends ApiController {

	private $userId_;
	private $fileService_;
	private $shareMapper_;
	private $joplinService_;
	private $utils_;

	public function __construct($AppName, IRequest $request, $UserId, FilesService $FilesService, ShareMapper $ShareMapper, JoplinUtils $JoplinUtils, JoplinService $JoplinService){
		parent::__construct($AppName, $request);
		$this->userId_ = $UserId;
		$this->fileService_ = $FilesService;
		$this->joplinService_ = $JoplinService;
		$this->shareMapper_ = $ShareMapper;
		$this->joplinUtils_ = $JoplinUtils;
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function index() {
		die('SHARE');
	}
	
	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 * 
	 */
	public function create($syncTargetId, $noteId) {
		try {
			$note = $this->joplinService_->note($syncTargetId, $noteId);
			$share = Share::newEntity($this->userId_, $syncTargetId, $note['id']);
			$this->shareMapper_->insert($share);
			return $share;
		} catch (\Exception $e) {
			return ErrorHandler::toJsonResponse($e);
		}

		// TODO: Add timestamps to share table
		// TODO: Add sync target ID to share table
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 * 
	 */
	public function noteIndex($syncTargetId, $noteId) {
		try {
			$shares = $this->shareMapper_->findByNoteId($syncTargetId, $noteId);
			return new JSONResponse($shares);
		} catch (\Exception $e) {
			return ErrorHandler::toJsonResponse($e);
		}
	}

}

