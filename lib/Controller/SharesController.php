<?php
namespace OCA\Joplin\Controller;

use OCP\IRequest;
use OCP\ISession;
use OCP\AppFramework\Http\TemplateResponse;
use OCA\Joplin\Error\NotFoundException;
use OCA\Joplin\Service\JoplinService;
use OCA\Joplin\Service\ModelService;
use OCP\AppFramework\PublicShareController;

class SharesController extends PublicShareController {

	private $joplinService_;
	private $models_;

	public function __construct($AppName, IRequest $request, ISession $session, ModelService $ModelService, JoplinService $JoplinService){
		parent::__construct($AppName, $request, $session);
		$this->joplinService_ = $JoplinService;
		$this->models_ = $ModelService;
	}

	/**
	 * @NoCSRFRequired
	 * @PublicPage
	 */
	public function get($token) {
		$share = $this->models_->get('share')->fetchByUuid($token);
		if (!$share) throw new NotFoundException('No share with token ' . $token);

		$note = $this->joplinService_->note($share['user_id'], $share['sync_target_id'], $share['item_id']);
		if (!$note) throw new NotFoundException('No note with ID ' . $share['sync_target_id'] . '/' . $share['item_id']);

		return $this->joplinService_->renderTemplate('content/note', [
			'jsFiles' => ['markdown-it', 'note'],
			'cssFiles' => ['note'],
			'note' => $note,
		]);
	}

	// ---------------------------------------------
	// PublicShareController interface
	// ---------------------------------------------
	protected function getPasswordHash(): string {
		return '';
	}

	public function isValidToken(): bool {
		return true;
	}

	protected function isPasswordProtected(): bool {
		return false;
	}
	// ---------------------------------------------
	// PublicShareController interface
	// ---------------------------------------------

}
