<?php
namespace OCA\Joplin\Controller;

use OCP\IRequest;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\ApiController;
use OCA\Joplin\Service\FilesService;
use OCA\Joplin\Service\JoplinUtils;
use OCA\Joplin\Db\SyncTargetMapper;
use OCA\Joplin\Db\SyncTarget;

class ApiNotesController extends ApiController {

	private $userId_;
	private $fileService_;
	private $syncTargetMapper_;
	private $utils_;

	public function __construct($AppName, IRequest $request, $UserId, FilesService $FilesService, SyncTargetMapper $SyncTargetMapper, JoplinUtils $JoplinUtils){
		parent::__construct($AppName, $request);
		$this->userId_ = $UserId;
		$this->fileService_ = $FilesService;
		$this->syncTargetMapper_ = $SyncTargetMapper;
		$this->joplinUtils_ = $JoplinUtils;

		// $syncTarget = SyncTarget::newEntity($this->userId_, '/Joplin');
		// $this->syncTargetMapper_->insert($syncTarget);
	

		// $syncTarget = new SyncTarget();
		// $syncTarget->setUserId($UserId);
		// $syncTarget->setPath('/Joplin');
		// $this->syncTargetMapper_->insert($syncTarget);

		// $syncTarget = $this->syncTargetMapper_->find(1);

		// var_dump($syncTarget->getPath());

		// die('ond');

		//var_dump(!!$this->syncTargetMapper_);die();
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 * @CORS
	 */
	public function index() {
		die('ici api');
		$this->fileService_->getFile('test');
		die('NotesController::index');
		return new TemplateResponse('joplin', 'index');  // templates/index.php
	}

	/**
	 * @NoAdminRequired
	 * @CORS
	 * @NoCSRFRequired
	 */
	public function get($syncTargetId, $noteId) {
		$syncTarget = $this->syncTargetMapper_->find($this->userId_, $syncTargetId);
		$file = $this->fileService_->getNoteFile($syncTarget, $noteId);
		$note = $this->joplinUtils_->unserializeItem($file->getContent());
		var_dump($note);die();
	}

}
