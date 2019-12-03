<?php
namespace OCA\Joplin\Service;

use OCA\Joplin\Service\ModelService;
use OCA\Joplin\Service\FilesService;
use OCA\Joplin\Service\JoplinUtils;
use OCA\Joplin\Error\NotFoundException;

class JoplinService {

	private $models_;
	private $fileService_;

	public function __construct(ModelService $ModelService, FilesService $FilesService) {
		$this->models_ = $ModelService;
		$this->fileService_ = $FilesService;
	}

	public function item($userId, $syncTargetId, $itemId) {
		$syncTarget = $this->models_->get('syncTarget')->fetchByUuid($userId, $syncTargetId);
		if (!$syncTarget) throw new NotFoundException("Could not find sync target \"$syncTargetId\"");
		$noteContent = $this->fileService_->noteFileContent($syncTarget['path'], $itemId);
		return JoplinUtils::unserializeItem($noteContent);
	}

	public function note($userId, $syncTargetId, $noteId) {
		$note = $this->item($userId, $syncTargetId, $noteId);
		if ($note['type_'] !== 1) throw new NotFoundException("Could not find note $noteId on sync target $syncTargetId");
		return $note;
	}

}