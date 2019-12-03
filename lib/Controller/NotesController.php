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

class NotesController extends ApiController {
	private $userId_;
	private $fileService_;

	public function __construct($AppName, IRequest $request, $UserId, FilesService $FilesService, SyncTargetMapper $SyncTargetMapper){
		parent::__construct($AppName, $request);
		$this->userId_ = $UserId;
		$this->fileService_ = $FilesService;
		$this->syncTargetMapper_ = $SyncTargetMapper;
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function index() {
		die('ici');
		$this->fileService_->getFile('test');
		die('NotesController::index');
		return new TemplateResponse('joplin', 'index');  // templates/index.php
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function get($syncTargetId, $noteId) {
		$syncTarget = $this->syncTargetMapper_->find($this->userId_, $syncTargetId);
		$file = $this->fileService_->getNoteFile($syncTarget, $noteId);
		$note = JoplinUtils::unserializeItem($file->getContent());
		return new TemplateResponse('joplin', 'index', array(
			'pageName' => 'note',
			'page' => array(
				'note' => $note,
			)
		));
		//var_dump($note);
	}

}
