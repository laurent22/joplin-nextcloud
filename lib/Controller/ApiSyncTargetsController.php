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

		$m = $this->models_->get('syncTarget');

		// $m->insert([
		// 	'user_id' => $UserId,
		// 	'path' => 'Joplin',
		// ]);

		$m->update([
			'uuid' => 'c8YUbVhvvS2HOAq7St40zr',
			'path' => 'NewOne',
		]);


		die();

		// var_dump('ii', $this->db_->fetchAll('select * from oc_users where uid = :uid', ['uid' => 'admin']));die();
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
	public function create($path) {
		try {
			$model = $this->models_->get('syncTarget');

			// $existingSyncTarget = $model->

			$existingSyncTarget = $this->syncTargetMapper_->findByPath($this->userId_, $path);
			if ($existingSyncTarget) throw new BadRequestException("Sync target with path \"$path\" already exists");

			$syncTarget = SyncTarget::newEntity($this->userId_, $path);
			$this->syncTargetMapper_->insert($syncTarget);
			return $syncTarget;
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

