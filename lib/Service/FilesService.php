<?php
namespace OCA\Joplin\Service;

use OCA\Joplin\Error\NotFoundException;

class FilesService {

	private $storage_;
	
	public function __construct($storage) {
		$this->storage_ = $storage;
	}

	public function noteFileContent($syncTargetPath, $noteId) {
		$filePath = $syncTargetPath . '/' . $noteId . '.md';
		$file = $this->file($filePath);
		return $file->getContent();
	}

	// public function getNoteFile($syncTarget, $noteId) {
	// 	$filePath = $syncTarget->getPath() . '/' . $noteId . '.md';
	// 	return $this->getFile($filePath);
	// }

	private function file($path) {
		try {
			$file = $this->storage_->get($path);
			return $file;
		} catch (\Exception $e) {
			if (get_class($e) === "OCP\Files\NotFoundException") throw new NotFoundException('Could not find file: ' . $path);
			throw $e;
		}
	}

}