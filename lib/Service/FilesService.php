<?php
namespace OCA\Joplin\Service;

class FilesService {

	private $storage_;
	
	public function __construct($storage) {
		$this->storage_ = $storage;
	}

	public function getNoteFile($syncTarget, $noteId) {
		$filePath = $syncTarget->getPath() . '/' . $noteId . '.md';
		return $this->getFile($filePath);
	}

	public function getFile($path) {
		$file = $this->storage_->get($path);
		return $file;
		// $file = $this->storage_->get('/Joplin/test.md');
		// var_dump($file->getContent());die();

		// $files = $this->storage_->getDirectoryListing();
		// foreach ($files as $file) {
		// 	var_dump($file->getInternalPath());
		// }
		// die();
		
		// $file = $this->storage_->get('About.txt');
		// var_dump($file->getContent());die();
		// return $file;		
	}

}