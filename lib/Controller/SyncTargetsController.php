<?php
namespace OCA\Joplin\Controller;

use OCP\IRequest;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Controller;
use OCA\Joplin\Service\SyncTargetService;

class SyncTargetsController extends Controller {

	private $syncTargetService_;

	public function __construct($AppName, IRequest $request, $UserId, SyncTargetService $SyncTargetService){
		parent::__construct($AppName, $request);
		$this->userId = $UserId;
		$this->syncTargetService_ = $SyncTargetService;
	}

	/**
	 * CAUTION: the @Stuff turns off security checks; for this page no admin is
	 *          required and no CSRF check. If you don't know what CSRF is, read
	 *          it up in the docs or you might create a security hole. This is
	 *          basically the only required method to add this exemption, don't
	 *          add it to any other method if you don't exactly know what it does
	 *
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function index() {
		die('SynctargetController::index');
		return new TemplateResponse('joplin', 'index');  // templates/index.php
	}

}
