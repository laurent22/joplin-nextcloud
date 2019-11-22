<?php

namespace OCA\Joplin\Service;

use OCP\IL10N;
use OCP\ILogger;
use OCP\Encryption\Exceptions\GenericEncryptionException;
use OCP\Files\IRootFolder;
use OCP\Files\FileInfo;
use OCP\Files\File;
use OCP\Files\Folder;

use OCA\Notes\Db\Note;

class JoplinUtils {

	private $l10n;
	private $root;
	private $logger;
	private $appName;

	/**
	 * @param IRootFolder $root
	 * @param IL10N $l10n
	 * @param ILogger $logger
	 * @param String $appName
	 */
	public function __construct(
		IRootFolder $root,
		IL10N $l10n,
		ILogger $logger,
		$appName
	) {
		$this->root = $root;
		$this->l10n = $l10n;
		$this->logger = $logger;
		$this->appName = $appName;
	}

	// public function getFoldersForUser($userId) {
	// 	$path = '/' . $userId . '/files/Joplin'; // TODO: move to settings
	// 	if (!$this->root->nodeExists($path)) throw new \Exception('No such path: ' . $path);
	// 	$folder = $this->root->get($path);
	// 	return [$folder];
	// }

	// public function getNotes($userId, $onlyMeta) {
	// 	$items = $this->getItems($userId);
	// 	$notes = [];
	// 	foreach ($items as $item) {
	// 		if ($item['type_'] !== 1) continue;
	// 		$notes[] = $this->itemToNote($item, $onlyMeta);
	// 	}
	// 	return $notes;
	// }

	// private function getItemByFileId($userId, $fileId) {
	// 	$items = $this->getItems($userId);
	// 	foreach ($items as $item) {
	// 		if ($item['fileId_'] === $fileId) return $item;
	// 	}
	// 	return null;
	// }

	// private function getItems($userId) {
	// 	$folders = $this->getFoldersForUser($userId);
	// 	$files = $this->getItemFiles($folders);
	// 	$items = [];
	// 	try {
	// 		foreach ($files as $file) {
	// 			$item = $this->unserializeItem($file->getContent());
	// 			$item['fileId_'] = $file->getId();
	// 			$items[] = $item;
	// 		}
	// 	} catch (\Exception $e) {
	// 		var_dump($e->getMessage());
	// 		var_dump($e->getTraceAsString());
	// 	}

	// 	return $items;
	// }

	// public function getNote($userId, $id) {
	// 	$notes = $this->getNotes($userId, false);
	// 	foreach ($notes as $note) {
	// 		if ($note->getId() === $id) return $note;
	// 	}
	// 	return null;
	// }

	private function isoDateToMilliseconds($date) {
		$d = date_parse($date);
		$ms = strtotime($date);
		$ms *= 1000;
		$ms += $d['fraction'] * 1000;
		return (int)$ms;
	}

	private function milliseconds() {
		return round(microtime(true) * 1000);
	}

	private function millisecondsToIsoDate($ms) {
		$s = date('c', $ms / 1000);
		$remain = str_pad($ms % 1000, 3, '0', STR_PAD_LEFT);
		return str_replace('+00:00', '.' . $remain, $s) . 'Z';
	}

	// public function updateNote($userId, $id, $content, $options = null) {
	// 	if (!$options) $options = [];

	// 	$updatedTime = $this->milliseconds();
		
	// 	$item = $this->getItemByFileId($userId, $id);
		
	// 	if (!$content) $content = '';
	// 	$idx = strpos($content, "\n");

	// 	$title = '';
	// 	$body = '';

	// 	if ($idx !== false) {
	// 		$title = substr($content, 0, $idx);
	// 		$body = ltrim(substr($content, $idx));
	// 	}

	// 	$item['title'] = $title;
	// 	$item['body'] = $body;

	// 	if ($updatedTime) {
	// 		$item['updated_time'] = $updatedTime;
	// 		$item['user_updated_time'] = $updatedTime;
	// 	}

	// 	$newContent = $this->serializeItem($item);

	// 	$file = $this->getFileById($userId, $id);
		
	// 	$file->putContent($newContent);
	// 	if ($updatedTime) $file->touch(floor($updatedTime / 1000));

	// 	return $this->getNote($userId, $id);
	// }

	// private function itemToNote($item, $onlyMeta = false) {
	// 	$output = [];

	// 	$note = new Note();

	// 	$note->setId($item['fileId_']);
	// 	if (!$onlyMeta) $note->setContent($item['title'] . "\n\n" . $item['body']);
	// 	$note->setModified(1573592772); // TODO: Parse timestamp
	// 	$note->setTitle($item['title']);
	// 	$note->setCategory('');

	// 	return $note;
	// }

	public function serializeItem($item) {
		$output = [];

		if ($item['type_'] === 1 || $item['type_'] === 2) {
			$output['title'] = isset($item['title']) ? $item['title'] : '';
		}

		if ($item['type_'] === 1) {
			$output['body'] = isset($item['body']) ? $item['body'] : '';
		}

		$output['props'] = [];

		foreach ($item as $k => $v) {
			if (in_array($k, ['title', 'body', 'fileId_'])) continue;
			$output['props'][] = $k . ': ' . $this->serialize_format($k, $v);
		}

		$temp = [];
		if ($item['type_'] === 1 || $item['type_'] === 2) $temp[] = isset($item['title']) ? $item['title'] : '';
		if ($item['type_'] === 1) $temp[] = isset($item['body']) ? $item['body'] : '';
		if (count($output['props'])) $temp[] = implode("\n", $output['props']);

		return implode("\n\n", $temp);
	}

	public function unserializeItem($content) {
		$lines = explode("\n", $content);
		$output = [];
		$state = 'readingProps';
		$body = [];

		for ($i = count($lines) - 1; $i >= 0; $i--) {
			$line = $lines[$i];

			if ($state === 'readingProps') {
				$line = trim($line);

				if ($line === '') {
					$state = 'readingBody';
					continue;
				}

				$p = strpos($line, ':');
				if ($p === false) throw new \Exception("Invalid property format: $line: $content");
				$key = trim(substr($line, 0, $p));
				$value = trim(substr($line, $p + 1));
				$output[$key] = $value;
			} else if ($state === 'readingBody') {
				array_unshift($body, $line);
			}
		}

		if (!isset($output['type_'])) throw new \Exception("Missing required property: type_: $content");
		$output['type_'] = (int)$output['type_'];

		if (count($body)) {
			$title = array_shift($body);
			array_shift($body);
			$output['title'] = $title;
		}
		
		if ($output['type_'] === 1) $output['body'] = implode("\n", $body);
		
		// TODO:
		// const ItemClass = this.itemClass(output.type_);
		// output = ItemClass.removeUnknownFields(output);

		foreach ($output as $k => $v) {
			$output[$k] = $this->unserialize_format($k, $v);
		}

		return $output;
	}

	private function unserialize_format($propName, $propValue) {
		if ($propName[strlen($propName) - 1] === '_') return $propValue; // Private property

		if (in_array($propName, ['created_time', 'updated_time', 'user_created_time', 'user_updated_time'])) {
			if (!$propValue) return 0;
			$propValue = $this->isoDateToMilliseconds($propValue);
		} else {
			//propValue = Database.formatValue(ItemClass.fieldType(propName), propValue);
		}

		return $propValue;
	}

	private function serialize_format($propName, $propValue) {
		if (in_array($propName, ['created_time', 'updated_time', 'sync_time', 'user_updated_time', 'user_created_time'])) {
			if (!$propValue) return '';
			$propValue = $this->millisecondsToIsoDate($propValue);
		} else if ($propValue === null) {
			$propValue = '';
		}

		return $propValue;
	}

	// private function getItemFiles($folders) {
	// 	$notes = [];
	// 	foreach ($folders as $folder) {
	// 		$nodes = $folder->getDirectoryListing();
	// 		foreach ($nodes as $node) {
	// 			if ($node->getType() === FileInfo::TYPE_FOLDER) {
	// 				continue;
	// 			}
	// 			if ($this->isJoplinItem($node)) {
	// 				$notes[] = $node;
	// 			}
	// 		}
	// 	}
	// 	return $notes;
	// }

	// private function isJoplinItem($file) {
	// 	$allowedExtensions = ['md'];

	// 	if ($file->getType() !== 'file') {
	// 		return false;
	// 	}

	// 	$ext = pathinfo($file->getName(), PATHINFO_EXTENSION);
	// 	$iext = strtolower($ext);
	// 	if (!in_array($iext, $allowedExtensions)) {
	// 		return false;
	// 	}
	// 	return true;
	// }

	// public function isItemFile($userId, $id) {
	// 	$items = $this->getItems($userId);
	// 	foreach ($items as $item) {
	// 		if ($item['fileId_'] === $id) return true;
	// 	}
	// 	return false;
	// }

	// private function getFileById($userId, $id) : File {
	// 	$folders = $this->getFoldersForUser($userId);
	// 	foreach ($folders as $folder) {
	// 		$file = $folder->getById($id);
	// 		if (count($file) <= 0 || !($file[0] instanceof File)) continue;
	// 		return $file[0];
	// 	}
	// 	return null;
	// }

}
