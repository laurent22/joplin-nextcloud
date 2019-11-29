<?php
namespace OCA\Joplin\Service;

use OCA\Joplin\Db\SyncTargetMapper;
use OCA\Joplin\Service\FilesService;
use OCA\Joplin\Service\JoplinUtils;
use OCA\Joplin\Error\NotFoundException;

class JoplinService {

	private $userId_;
	private $syncTargetMapper_;
	private $fileService_;
	private $joplinUtils_;

	public function __construct($UserId, SyncTargetMapper $SyncTargetMapper, FilesService $FilesService, JoplinUtils $JoplinUtils) {
		$this->userId_ = $UserId;
		$this->syncTargetMapper_ = $SyncTargetMapper;
		$this->fileService_ = $FilesService;
		$this->joplinUtils_ = $JoplinUtils;
	}

	public function item($syncTargetId, $itemId) {
		$syncTarget = $this->syncTargetMapper_->find($this->userId_, $syncTargetId);
		$file = $this->fileService_->getNoteFile($syncTarget, $itemId);
		return $this->joplinUtils_->unserializeItem($file->getContent());
	}

	public function note($syncTargetId, $noteId) {
		$note = $this->item($syncTargetId, $noteId);
		if ($note['type_'] !== 1) throw new NotFoundException("Could not find note $noteId on sync target $syncTargetId");
		return $note;
	}

}