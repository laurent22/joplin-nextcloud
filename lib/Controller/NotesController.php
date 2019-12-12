<?php
namespace OCA\Joplin\Controller;

use OCP\IRequest;
use OCP\ISession;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Http\DataResponse;
use OCA\Joplin\Error\NotFoundException;
use OCP\AppFramework\ApiController;
use OCA\Joplin\Service\FilesService;
use OCA\Joplin\Service\JoplinUtils;
use OCA\Joplin\Service\JoplinService;
use OCA\Joplin\Service\ServerService;
use OCA\Joplin\Db\SyncTargetMapper;
use OCA\Joplin\Db\SyncTarget;
use OCA\Joplin\Service\ModelService;
use OCP\AppFramework\PublicShareController;

class NotesController extends PublicShareController {
	private $userId_;
	private $joplinService_;
	private $models_;
	private $request_;
	private $serverService_;

	public function __construct($AppName, IRequest $request, ISession $session, $UserId, ServerService $ServerService, ModelService $ModelService, JoplinService $JoplinService){
		parent::__construct($AppName, $request, $session);
		$this->userId_ = $UserId;
		$this->request_ = $request;
		$this->serverService_ = $ServerService;
		$this->joplinService_ = $JoplinService;
		$this->models_ = $ModelService;
	}

	protected function getPasswordHash(): string {
		return '';
}

	public function isValidToken(): bool {
		return true;
	}

	protected function isPasswordProtected(): bool {
		return false;
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
	 * @PublicPage
	 */
	public function get($syncTargetId, $noteId) {
		$shareId = $this->serverService_->getQueryParam('t');
		$share = $this->models_->get('share')->fetchByUuid($shareId);
		if (!$share) throw new NotFoundException('No share with ID ' . $shareId);

		$note = $this->joplinService_->note($share['user_id'], $syncTargetId, $noteId);
		if (!$note) throw new NotFoundException('No note with ID ' . $syncTargetId . '/' . $noteId);

		return new TemplateResponse('joplin', 'index', array(
			'pageName' => 'note',
			'page' => array(
				'note' => $note,
			)
		));
	}

}
