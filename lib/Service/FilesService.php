<?php
namespace OCA\Joplin\Service;

use OCA\Joplin\Error\NotFoundException;
use OCP\Files\IRootFolder;

class FilesService {

	private $rootFolder_;
	
	public function __construct(IRootFolder $rootFolder) {
		$this->rootFolder_ = $rootFolder;
	}

	public function noteFileContent($userId, $syncTargetPath, $noteId) {
		$filePath = $syncTargetPath . '/' . $noteId . '.md';
		$file = $this->file($userId, $filePath);
		return $file->getContent();
	}

	private function file($userId, $path) {
		try {
			return $this->rootFolder_->getUserFolder($userId)->get($path);
		} catch (\Exception $e) {
			if (get_class($e) === "OCP\Files\NotFoundException") throw new NotFoundException('Could not find file: ' . $path);
			throw $e;
		}
	}

}